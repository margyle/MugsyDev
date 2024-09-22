import logging
from fastapi import APIRouter, WebSocket, WebSocketDisconnect
from .cone_weight_service import start_weight_stream, get_current_weight, zero_scale

router = APIRouter()

@router.websocket("/stream")
async def websocket_endpoint(websocket: WebSocket):
    await websocket.accept()
    logging.info("WebSocket connection opened")
    try:
        await start_weight_stream(websocket)
    except WebSocketDisconnect:
        logging.info("WebSocket disconnected")
    except Exception as e:
        logging.error(f"Error in WebSocket: {e}", exc_info=True)
    finally:
        logging.info("WebSocket connection closed")

@router.get("/current")
async def get_weight():
    return await get_current_weight()

@router.post("/zero")
async def zero_weight_scale():
    return await zero_scale()