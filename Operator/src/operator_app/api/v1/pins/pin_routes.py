from fastapi import APIRouter, BackgroundTasks, Depends
from operator_app.auth import auth_handler
from typing import Optional
from pydantic import BaseModel
from .pin_services import turn_on, turn_off

router = APIRouter()

class PinData(BaseModel):
    gpio_pin: int
    time: Optional[int] = None

@router.post("/on")
def turn_on_route(pin_data: PinData, background_tasks: BackgroundTasks, payload=Depends(auth_handler.decode_token)):
    return turn_on(pin_data.gpio_pin, pin_data.time, background_tasks)

@router.post("/off")
def turn_off_route(pin_data: PinData):
    return turn_off(pin_data.gpio_pin)
