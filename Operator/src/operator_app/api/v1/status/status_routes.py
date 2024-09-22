from fastapi import APIRouter
from .status_services import read_state

router = APIRouter()

@router.get("/state/{gpio_pin}")
def read_state_route(gpio_pin: int):
    return read_state(gpio_pin)
