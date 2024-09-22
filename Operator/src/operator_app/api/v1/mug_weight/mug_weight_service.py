import asyncio
import json
import logging
from pydantic_settings import BaseSettings
from starlette.websockets import WebSocketState, WebSocketDisconnect


logging.basicConfig(level=logging.DEBUG, format='%(asctime)s - %(levelname)s - %(message)s')

class Settings(BaseSettings):
    WEIGHT_SERVICE_SOCKET: str = "/tmp/mug_scale_service.sock"

settings = Settings()

async def start_weight_stream(websocket):
    try:
        reader, writer = await asyncio.open_unix_connection(settings.WEIGHT_SERVICE_SOCKET)
        writer.write(b"stream_start\n")
        await writer.drain()
        
        while True:
            if websocket.client_state == WebSocketState.DISCONNECTED:
                logging.info("WebSocket disconnected, stopping stream")
                break

            try:
                response = await reader.readline()
                if not response:
                    logging.warning("Empty response from weight service, retrying...")
                    await asyncio.sleep(0.1)
                    continue

                data = json.loads(response.decode().strip())
                
                if websocket.client_state == WebSocketState.CONNECTED:
                    await websocket.send_json(data)
                else:
                    logging.info("WebSocket no longer connected, stopping stream")
                    break

            except json.JSONDecodeError as e:
                logging.error(f"Error decoding JSON: {e}")
                continue
            except WebSocketDisconnect:
                logging.info("WebSocket disconnected, stopping stream")
                break
            except Exception as e:
                logging.error(f"Error in weight streaming: {e}", exc_info=True)
                break

    except asyncio.CancelledError:
        logging.info("Weight stream cancelled")
    except Exception as e:
        logging.error(f"Error in weight streaming: {e}", exc_info=True)
    finally:
        if 'writer' in locals():
            try:
                writer.write(b"stream_stop\n")
                await writer.drain()
            except Exception as e:
                logging.error(f"Error stopping stream: {e}", exc_info=True)
            finally:
                writer.close()
                await writer.wait_closed()
        logging.info("Weight stream stopped")

async def get_current_weight():
    try:
        reader, writer = await asyncio.open_unix_connection(settings.WEIGHT_SERVICE_SOCKET)
        writer.write(b"single_read\n")
        await writer.drain()
        
        response = await asyncio.wait_for(reader.readline(), timeout=5.0)
        
        writer.close()
        await writer.wait_closed()
        
        data = json.loads(response.decode().strip())
        return data
    except asyncio.TimeoutError:
        logging.error("Timeout while waiting for weight service response")
        return {"error": "Weight service timed out"}
    except json.JSONDecodeError as e:
        logging.error(f"Error decoding JSON: {e}")
        return {"error": "Invalid response from weight service"}
    except Exception as e:
        logging.error(f"Error getting weight: {e}", exc_info=True)
        return {"error": "Failed to get weight"}

async def zero_scale():
    try:
        reader, writer = await asyncio.open_unix_connection(settings.WEIGHT_SERVICE_SOCKET)
        writer.write(b"zero\n")
        await writer.drain()
        
        response = await asyncio.wait_for(reader.readline(), timeout=5.0)
        
        writer.close()
        await writer.wait_closed()
        
        data = json.loads(response.decode().strip())
        return data
    except asyncio.TimeoutError:
        logging.error("Timeout while waiting for weight service response")
        return {"error": "Weight service timed out"}
    except json.JSONDecodeError as e:
        logging.error(f"Error decoding JSON: {e}")
        return {"error": "Invalid response from weight service"}
    except Exception as e:
        logging.error(f"Error zeroing scale: {e}", exc_info=True)
        return {"error": "Failed to zero scale"}