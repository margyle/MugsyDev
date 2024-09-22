import time
import datetime
import binascii
import signal
import sys
from typing import Tuple
from pn532pi import Pn532, Pn532I2c
from pn532pi.nfc.pn532 import PN532_MIFARE_ISO14443A_106KBPS
import requests
import configparser
import logging

# Constants
CONFIG_FILE = 'src/operator_app/hardware_config.ini'
SCAN_INTERVAL = 0.1  # seconds
CARD_READ_DELAY = 0.5  # seconds
MAIN_LOOP_DELAY = 1  # seconds
CONSECUTIVE_EMPTY_READS_THRESHOLD = 5

# Set up logging
logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')
logger = logging.getLogger(__name__)

def read_config() -> Tuple[str, int]:
    config = configparser.ConfigParser()
    config.read(CONFIG_FILE)
    return (
        config.get('DECAF_API', 'DECAF_RFID_API_URL'),
        config.getint('NFC', 'I2C_BUS', fallback=1)
    )

def setup_nfc(i2c_bus: int) -> Pn532:
    pn532_i2c = Pn532I2c(i2c_bus)
    nfc = Pn532(pn532_i2c)
    nfc.begin()
    versiondata = nfc.getFirmwareVersion()
    if not versiondata:
        logger.error("Didn't find PN53x board")
        raise RuntimeError("Didn't find PN53x board")
    nfc.SAMConfig()
    logger.info(f"Found chip PN5 {versiondata >> 24 & 0xFF}, Firmware ver. {versiondata >> 16 & 0xFF}.{versiondata >> 8 & 0xFF}")
    return nfc

def send_uid_to_api(url: str, uid: str) -> bool:
    headers = {"Content-Type": "application/json"}
    data = {"uid": uid}
    try:
        response = requests.post(url, headers=headers, json=data, timeout=10)
        response.raise_for_status()
        logger.info("Successfully sent UID to API.")
        logger.info(f"API Response: {response.json()}")
        return True
    except requests.exceptions.RequestException as e:
        logger.error(f"Failed to send UID to API: {str(e)}")
        return False

def loop(nfc: Pn532, api_url: str):
    current_mug_id = None
    consecutive_empty_reads = 0
    last_read_time = 0

    while True:
        current_time = time.time()
        
        if current_time - last_read_time < SCAN_INTERVAL:
            time.sleep(SCAN_INTERVAL - (current_time - last_read_time))
            continue

        last_read_time = current_time
        success, uid = nfc.readPassiveTargetID(PN532_MIFARE_ISO14443A_106KBPS)

        if success:
            logger.info("Read an ISO14443A tag.")
            logger.info(f"UID Length: {len(uid)}")
            uid_value = binascii.hexlify(uid).decode('utf-8')
            logger.info(f"UID Value: {uid_value}")
            time.sleep(CARD_READ_DELAY)

            consecutive_empty_reads = 0
            if uid_value != current_mug_id:
                current_mug_id = uid_value
                if send_uid_to_api(api_url, uid_value):
                    logger.info(f"New mug detected and processed: {uid_value}")
                else:
                    logger.error(f"Failed to process new mug: {uid_value}")
        else:
            consecutive_empty_reads += 1
            if consecutive_empty_reads >= CONSECUTIVE_EMPTY_READS_THRESHOLD:
                if current_mug_id is not None:
                    logger.info(f"Mug {current_mug_id} removed")
                current_mug_id = None
                consecutive_empty_reads = 0

        time.sleep(MAIN_LOOP_DELAY)

def signal_handler(signum, frame):
    logger.info("Received shutdown signal. Exiting gracefully...")
    sys.exit(0)

def main():
    logger.info("Starting RFID service")
    signal.signal(signal.SIGINT, signal_handler)
    signal.signal(signal.SIGTERM, signal_handler)

    try:
        api_url, i2c_bus = read_config()
        nfc_module = setup_nfc(i2c_bus)
        loop(nfc_module, api_url)
    except Exception as e:
        logger.exception(f"An error occurred: {str(e)}")
        sys.exit(1)

if __name__ == "__main__":
    main()