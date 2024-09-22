from fastapi import APIRouter, HTTPException, BackgroundTasks
from pydantic import BaseModel
from enum import Enum
import pigpio
import asyncio
from typing import Optional, Dict
import socket
import json
from typing import List
import configparser

config = configparser.ConfigParser()
config.read('src/operator_app/hardware_config.ini')

class RelayMapping(BaseModel):
    mapping: Dict[int, int] = { 
        1: int(config['RELAY_CHANNELS']['CHANNEL_1']),
        2: int(config['RELAY_CHANNELS']['CHANNEL_2']),
        3: int(config['RELAY_CHANNELS']['CHANNEL_3']),
        4: int(config['RELAY_CHANNELS']['CHANNEL_4'])
    }

class State(str, Enum):
    on = "on"
    off = "off"

class Relay(BaseModel):
    relay_channel: int
    state: State
    timer: Optional[int] = None

# class WatchTowerDetails(int, Enum):
#     pins: List[int] = [22, 23, 24, 25]
#     delay: 600

router = APIRouter()
pi = pigpio.pi()
socket_path = "/tmp/watchtower_socket"

def send_command_to_watchtower(command):
    with socket.socket(socket.AF_UNIX, socket.SOCK_STREAM) as client:
        client.connect(socket_path)
        client.sendall(json.dumps(command).encode())

async def set_relay_off_after_timer(gpio_pin: int, timer: int):
    await asyncio.sleep(timer)
    pi.write(gpio_pin, 1)  # Turn off the relay after timer

@router.post("/", response_model=Relay)
async def set_relay(relay: Relay, background_tasks: BackgroundTasks):
    relay_mapping = RelayMapping()
    gpio_pin = relay_mapping.mapping.get(relay.relay_channel)
    if gpio_pin is None:
        raise HTTPException(status_code=400, detail="Invalid relay_channel.")
    
    pi.set_mode(gpio_pin, pigpio.OUTPUT)
    
    if relay.state == State.on:
        watchtower_details = {"pin": gpio_pin, "delay": 300} # format command to send to watchtower
        send_command_to_watchtower(watchtower_details)
        pi.write(gpio_pin, 0)  # Turn on the relay
    else:
        pi.write(gpio_pin, 1)  # Turn off the relay

    if relay.timer:
        background_tasks.add_task(set_relay_off_after_timer, gpio_pin, relay.timer)

    return relay