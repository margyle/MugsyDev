import socket
import json
from pydantic import BaseModel
from enum import Enum
import logging

SOCKET_PATH = '/tmp/pump_control.sock'

class PumpDirection(str, Enum):
    forward = "forward"
    reverse = "reverse"
    stop = "stop"

class PumpControl(BaseModel):
    direction: PumpDirection

class PumpSpeedControl(BaseModel):
    direction: PumpDirection
    speed: int

class PumpStatus(BaseModel):
    direction: PumpDirection
    speed: int
    status_flag: bool

def send_command(command: dict) -> dict:
    try:
        with socket.socket(socket.AF_UNIX, socket.SOCK_STREAM) as client:
            client.connect(SOCKET_PATH)
            client.send(json.dumps(command).encode('utf-8'))
            response = client.recv(1024).decode('utf-8')
            return json.loads(response) if response else {}
    except Exception as e:
        logging.error(f"Error communicating with pump service: {e}")
        raise

def control_pump(pump: PumpControl) -> dict:
    command = {
        'action': 'control',
        'params': pump.dict()
    }
    return send_command(command)

def control_pump_speed(pump: PumpSpeedControl) -> dict:
    command = {
        'action': 'control_speed',
        'params': pump.model_dump()
    }
    return send_command(command)

def get_pump_status() -> PumpStatus:
    command = {'action': 'status'}
    status_dict = send_command(command)
    return PumpStatus(**status_dict)