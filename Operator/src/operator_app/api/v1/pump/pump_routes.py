# pragma: no cover
from fastapi import APIRouter, HTTPException, Depends
from operator_app.auth import auth_handler
from pydantic import BaseModel, Field
from enum import Enum
import configparser
from .pump_service_client import PumpControl, PumpSpeedControl, control_pump, control_pump_speed, get_pump_status

# Load configurations
config = configparser.ConfigParser()
config.read('hardware_config.ini')
min_flow_rate = config.getint('PUMP', 'MIN_FLOW_RATE', fallback=40)

class PumpDirection(str, Enum):
    forward = "forward"
    reverse = "reverse"
    stop = "stop"

class PumpControl(BaseModel):
    direction: PumpDirection

class PumpSpeedControl(BaseModel):
    direction: PumpDirection
    speed: int = Field(..., ge=min_flow_rate, le=100, description=f"Flow rate from {min_flow_rate} to 100")

router = APIRouter()

@router.post("/")
async def route_control_pump(pump: PumpControl, payload=Depends(auth_handler.decode_token)):
    try:
        result = control_pump(pump)
        return {"status": f"Pump set to {pump.direction}", "result": result}
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error controlling pump: {str(e)}")

@router.post("/flow-rate")
async def route_control_pump_speed(pump: PumpSpeedControl, payload=Depends(auth_handler.decode_token)):
    try:
        result = control_pump_speed(pump)
        return {"status": f"Pump set to {pump.direction} at {pump.speed}% speed", "result": result}
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error controlling pump speed: {str(e)}")

@router.get("/status")
async def route_get_pump_status(payload=Depends(auth_handler.decode_token)):
    try:
        status = get_pump_status()
        return status.model_dump()
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error getting pump status: {str(e)}")