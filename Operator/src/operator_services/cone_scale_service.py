import asyncio
import json
import configparser
import os
import logging
from HX711 import SimpleHX711, Mass
import pigpio

# Set up logging
logging.basicConfig(level=logging.DEBUG, format='%(asctime)s - %(levelname)s - %(message)s')

# Read configuration
config = configparser.ConfigParser()
config_path = os.path.join('src', 'operator_app', 'hardware_config.ini')
config.read(config_path)

# Configuration
DATA_PIN = config.getint('SCALES', 'cone_scale_data_pin')
CLOCK_PIN = config.getint('SCALES', 'cone_scale_clock_pin')
REF_UNIT = int(config.getfloat('SCALES', 'cone_scale_reference_unit'))
OFFSET = int(config.getfloat('SCALES', 'cone_scale_zero_value'))
UNIT = config.get('SCALES', 'cone_scale_unit')
SAMPLE_SIZE = 15
SOCKET_PATH = "/tmp/cone_scale_service.sock"

pi = pigpio.pi()
hx = None

def initialize_hx711():
    global hx
    if not pi.connected:
        raise ConnectionError("Failed to connect to pigpio daemon.")
    logging.debug(f"Initializing HX711 with DATA_PIN={DATA_PIN}, CLOCK_PIN={CLOCK_PIN}, REF_UNIT={REF_UNIT}, OFFSET={OFFSET}")
    hx = SimpleHX711(DATA_PIN, CLOCK_PIN, REF_UNIT, OFFSET)
    hx.setUnit(getattr(Mass.Unit, UNIT.upper()))
    zero_scale()
    logging.info("HX711 initialized")

def zero_scale():
    global hx
    if hx:
        hx.zero()
        logging.info("Scale zeroed")
    else:
        logging.error("Cannot zero scale: HX711 not initialized")

async def handle_client(reader, writer):
    global hx
    if not hx:
        try:
            initialize_hx711()
        except Exception as e:
            logging.error(f"Failed to initialize HX711: {e}")
            writer.write(json.dumps({"error": "Failed to initialize scale"}).encode() + b"\n")
            await writer.drain()
            writer.close()
            return
    
    try:
        while True:
            logging.debug("Waiting for command...")
            data = await reader.readline()
            if not data:
                logging.debug("Client disconnected")
                break
            
            command = data.decode().strip()
            logging.debug(f"Received command: {command}")
            
            if command == "single_read":
                logging.debug("Performing single read")
                try:
                    weight = hx.weight(SAMPLE_SIZE)
                    response = json.dumps({"weight": float(weight), "unit": UNIT}) + "\n"
                    logging.debug(f"Read weight: {weight}")
                except Exception as e:
                    logging.error(f"Error reading weight: {e}")
                    response = json.dumps({"error": "Failed to read weight"}) + "\n"
                writer.write(response.encode())
                await writer.drain()
                logging.debug(f"Sent response: {response.strip()}")
            
            elif command == "stream_start":
                logging.debug("Zeroing scale before starting stream")
                zero_scale()
                logging.debug("Starting stream")
                try:
                    while True:
                        weight = hx.weight(SAMPLE_SIZE)
                        response = json.dumps({"weight": float(weight), "unit": UNIT}) + "\n"
                        try:
                            writer.write(response.encode())
                            await writer.drain()
                        except ConnectionResetError:
                            logging.warning("Client disconnected during stream")
                            break
                        except BrokenPipeError:
                            logging.warning("Broken pipe, client likely disconnected")
                            break
                        await asyncio.sleep(0.1)  # Adjust the delay as needed
                except asyncio.CancelledError:
                    logging.info("Stream cancelled")
                except Exception as e:
                    logging.error(f"Error in weight streaming: {e}")
                finally:
                    logging.info("Streaming stopped")
            
            elif command == "stream_stop":
                logging.debug("Stopping stream")
                break
            
            elif command == "zero":
                logging.debug("Zeroing scale")
                zero_scale()
                response = json.dumps({"status": "Scale zeroed"}) + "\n"
                writer.write(response.encode())
                await writer.drain()
            
            else:
                logging.warning(f"Unknown command: {command}")
                response = json.dumps({"error": "Unknown command"}) + "\n"
                writer.write(response.encode())
                await writer.drain()

    except (ConnectionResetError, BrokenPipeError) as e:
        logging.warning(f"Client disconnected: {e}")
    except Exception as e:
        logging.error(f"Error handling client: {e}", exc_info=True)
    finally:
        writer.close()
        try:
            await writer.wait_closed()
        except Exception as e:
            logging.warning(f"Error while closing writer: {e}")
        logging.debug("Client connection closed")

async def main():
    try:
        os.unlink(SOCKET_PATH)
    except FileNotFoundError:
        pass

    server = await asyncio.start_unix_server(handle_client, SOCKET_PATH)
    logging.info(f"Server started on {SOCKET_PATH}")
    
    async with server:
        await server.serve_forever()

if __name__ == "__main__":
    try:
        asyncio.run(main())
    except KeyboardInterrupt:
        logging.info("Service stopped by user.")
    except Exception as e:
        logging.error(f"Unexpected error: {e}", exc_info=True)
    finally:
        if pi:
            pi.stop()
        logging.info("Service shut down.")