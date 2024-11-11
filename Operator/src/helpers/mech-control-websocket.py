import asyncio
import websockets
import json
import os
import logging
from typing import Any, Dict, List

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class WebSocketRelayServer:
    def __init__(self, websocket_port: int = 8765, unix_socket_path: str = "/tmp/mech-control.sock"):
        self.websocket_port = websocket_port
        self.unix_socket_path = unix_socket_path

    async def send_to_unix_socket(self, message: str) -> str:
        try:
            reader, writer = await asyncio.open_unix_connection(self.unix_socket_path)
            try:
                writer.write((message + '\n').encode())
                await writer.drain()

                response = await reader.readline()
                if not response:
                    raise ConnectionError("No response from UNIX socket")
                return response.decode().strip()
            finally:
                writer.close()
                await writer.wait_closed()
        except Exception as e:
            logger.error(f"Error communicating with UNIX socket: {e}", exc_info=True)
            raise

    async def handle_websocket(self, websocket: websockets.WebSocketServerProtocol) -> None:
        try:
            async for message in websocket:
                try:
                    # Parse the incoming WebSocket message
                    commands = json.loads(message)
                    logger.info(f"Received WebSocket message: {commands}")

                    # Convert single command to list if necessary
                    if not isinstance(commands, list):
                        commands = [commands]

                    # Forward to Unix socket
                    response = await self.send_to_unix_socket(json.dumps(commands))
                    logger.info(f"Received response from UNIX socket: {response}")

                    # Parse the response from UNIX socket
                    response_data = json.loads(response)

                    # Create ack message
                    total_commands = len(commands)
                    completed_commands = len([resp for resp in response_data if resp['status'] != 'error'])
                    acknowledgment = {
                        'total_commands': total_commands,
                        'completed_commands': completed_commands,
                        'responses': response_data
                    }

                    # Send ack back to WebSocket client
                    await websocket.send(json.dumps(acknowledgment))

                except Exception as e:
                    logger.error(f"Error processing message: {e}", exc_info=True)
                    error_response = json.dumps({'error': str(e)})
                    await websocket.send(error_response)
        except websockets.exceptions.ConnectionClosedError as e:
            logger.info(f"WebSocket connection closed: {e}")
        except Exception as e:
            logger.error(f"Unexpected error in WebSocket handler: {e}", exc_info=True)

    async def start_server(self) -> None:
        async with websockets.serve(self.handle_websocket, "localhost", self.websocket_port):
            logger.info(f"WebSocket server started on ws://localhost:{self.websocket_port}")
            await asyncio.Future()  # Run forever

if __name__ == "__main__":
    server = WebSocketRelayServer()
    
    try:
        asyncio.run(server.start_server())
    except KeyboardInterrupt:
        logger.info("Shutting down WebSocket server...")
