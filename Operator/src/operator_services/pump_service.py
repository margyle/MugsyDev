import configparser
import socket
import os
import sys
import signal
import time
import logging
import json
from enum import Enum
import pigpio
from pydantic import BaseModel, Field

# Configure logging
logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')

# Load configurations
config = configparser.ConfigParser()
config.read('hardware_config.ini')
MIN_FLOW_RATE = config.getint('PUMP', 'MIN_FLOW_RATE', fallback=40)
FORWARD_PIN = config.getint('PUMP', 'FORWARD_PIN', fallback=13)
REVERSE_PIN = config.getint('PUMP', 'REVERSE_PIN', fallback=19)
SOCKET_PATH = config.get('SERVICE', 'SOCKET_PATH', fallback='/tmp/pump_control.sock')

class PumpDirection(str, Enum):
    forward = "forward"
    reverse = "reverse"
    stop = "stop"

class PumpControl(BaseModel):
    direction: PumpDirection

class PumpSpeedControl(BaseModel):
    direction: PumpDirection
    speed: int = Field(..., ge=MIN_FLOW_RATE, le=100, description="Flow rate from MIN_FLOW_RATE to 100")

class PumpStatus(BaseModel):
    direction: PumpDirection
    speed: int
    status_flag: bool

class PumpControlService:
    def __init__(self):
        self.pi = pigpio.pi()
        self.status_flag = True
        self.current_direction = PumpDirection.stop
        self.current_speed = 0

        # Initialize GPIO pins
        self.pi.set_mode(FORWARD_PIN, pigpio.OUTPUT)
        self.pi.set_mode(REVERSE_PIN, pigpio.OUTPUT)
        self.stop_pump()

    def control_pump(self, pump: PumpControl):
        if not self.status_flag:
            logging.warning("Cannot control pump due to error flag")
            return

        self.current_direction = pump.direction

        if pump.direction == PumpDirection.forward:
            self.pi.write(REVERSE_PIN, 0)
            self.pi.write(FORWARD_PIN, 1)
        elif pump.direction == PumpDirection.reverse:
            self.pi.write(FORWARD_PIN, 0)
            self.pi.write(REVERSE_PIN, 1)
        elif pump.direction == PumpDirection.stop:
            self.stop_pump()

        logging.info(f"Pump set to {pump.direction}")

    def control_pump_speed(self, pump: PumpSpeedControl):
        if not self.status_flag:
            logging.warning("Cannot control pump due to error flag")
            return

        self.current_direction = pump.direction
        self.current_speed = pump.speed

        duty_cycle = int(pump.speed * 255 / 100)  # Calculate the PWM duty cycle

        if pump.direction == PumpDirection.forward:
            self.pi.write(REVERSE_PIN, 0)
            self.pi.set_PWM_dutycycle(FORWARD_PIN, duty_cycle)
        elif pump.direction == PumpDirection.reverse:
            self.pi.write(FORWARD_PIN, 0)
            self.pi.set_PWM_dutycycle(REVERSE_PIN, duty_cycle)
        elif pump.direction == PumpDirection.stop:
            self.stop_pump()

        logging.info(f"Pump set to {pump.direction} at {pump.speed}% speed")

    def stop_pump(self):
        self.pi.set_PWM_dutycycle(FORWARD_PIN, 0)
        self.pi.set_PWM_dutycycle(REVERSE_PIN, 0)
        self.current_direction = PumpDirection.stop
        self.current_speed = 0
        logging.info("Pump stopped")

    def get_status(self) -> PumpStatus:
        return PumpStatus(
            direction=self.current_direction,
            speed=self.current_speed,
            status_flag=self.status_flag
        )

def handle_client(pump_service: PumpControlService, client_socket: socket.socket):
    try:
        data = client_socket.recv(1024).decode('utf-8')
        command = json.loads(data)

        if command['action'] == 'control':
            pump = PumpControl(**command['params'])
            pump_service.control_pump(pump)
        elif command['action'] == 'control_speed':
            pump = PumpSpeedControl(**command['params'])
            pump_service.control_pump_speed(pump)
        elif command['action'] == 'stop':
            pump_service.stop_pump()
        elif command['action'] == 'status':
            status = pump_service.get_status()
            client_socket.send(status.model_dump_json().encode('utf-8'))
        else:
            logging.warning(f"Unknown command: {command['action']}")
    except Exception as e:
        logging.error(f"Error handling client request: {e}")
    finally:
        client_socket.close()

def main():
    pump_service = PumpControlService()

    if os.path.exists(SOCKET_PATH):
        os.remove(SOCKET_PATH)

    server = socket.socket(socket.AF_UNIX, socket.SOCK_STREAM)
    server.bind(SOCKET_PATH)
    server.listen(1)

    logging.info(f"Pump control service started. Listening on {SOCKET_PATH}")

    def signal_handler(signum, frame):
        logging.info("Shutting down pump control service...")
        pump_service.stop_pump()
        server.close()
        os.remove(SOCKET_PATH)
        sys.exit(0)

    signal.signal(signal.SIGINT, signal_handler)
    signal.signal(signal.SIGTERM, signal_handler)

    while True:
        client, _ = server.accept()
        handle_client(pump_service, client)

if __name__ == "__main__":
    main()