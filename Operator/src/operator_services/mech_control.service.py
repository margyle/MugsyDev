import PyCmdMessenger
import asyncio
import json
import os
import logging
from typing import Any, List, Dict

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class MechControlService:
    def __init__(self, socket_path: str = "/tmp/mech-control.sock"):
        # Initialize Arduino
        self.arduino = PyCmdMessenger.ArduinoBoard("/dev/tty.usbserial-11330", baud_rate=9600)
        
        # Command definitions
        self.commands = [
            ["move_cone", "lii"],       # steps, speed, direction
            ["cone_done", ""],
            ["move_spout", "lii"],      # degrees, speed, direction
            ["spout_done", ""],
            ["move_both", "lilii"],     # cone_steps, cone_speed, spout_degrees, spout_speed, direction
            ["both_done", ""],
            ["zero_spout", ""],         # Zero the spout stepper
            ["zero_done", ""],          # Zero complete acknowledgment
            ["error", "s"]
        ]
        
        self.messenger = PyCmdMessenger.CmdMessenger(self.arduino, self.commands)
        self.socket_path = socket_path

    async def wait_for_response(self, expected_done_cmd: str, timeout: float = 30) -> List[Any]:
        loop = asyncio.get_running_loop()
        try:
            while True:
                response = await loop.run_in_executor(None, self.messenger.receive)
                if response is not None:
                    logger.debug(f"Received response: {response}")
                    if response[0] == "error":
                        raise Exception(f"Arduino error: {response[1]}")
                    if response[0] == expected_done_cmd:
                        return response
                await asyncio.sleep(0.1)
        except asyncio.CancelledError:
            raise TimeoutError(f"Timeout waiting for {expected_done_cmd}")

    def validate_command_parameters(self, command: Dict[str, Any]) -> None:
        cmd_type = command.get('command')
        if cmd_type == 'move_cone':
            required_fields = ['steps', 'speed', 'direction']
        elif cmd_type == 'move_spout':
            required_fields = ['degrees', 'speed', 'direction']
        elif cmd_type == 'move_both':
            required_fields = ['cone_steps', 'cone_speed', 'spout_degrees', 'spout_speed', 'direction']
        elif cmd_type == 'zero_spout':
            required_fields = []
        else:
            raise ValueError(f"Unknown command type: {cmd_type}")

        for field in required_fields:
            if field not in command or not isinstance(command[field], int):
                raise ValueError(f"Invalid or missing '{field}' in command.")

    async def execute_command(self, command: Dict[str, Any]) -> List[Any]:
        cmd_type = command.get('command')
        loop = asyncio.get_running_loop()
        self.validate_command_parameters(command)

        try:
            if cmd_type == 'zero_spout':
                await loop.run_in_executor(None, self.messenger.send, 'zero_spout')
                logger.info(f"Sent command: zero_spout")
                response = await asyncio.wait_for(self.wait_for_response('zero_done'), timeout=30)

            elif cmd_type == 'move_cone':
                steps = command['steps']
                speed = command['speed']
                direction = command['direction']
                await loop.run_in_executor(None, self.messenger.send, 'move_cone', steps, speed, direction)
                logger.info(f"Sent command: move_cone with steps={steps}, speed={speed}, direction={direction}")
                response = await asyncio.wait_for(self.wait_for_response('cone_done'), timeout=30)

            elif cmd_type == 'move_spout':
                degrees = command['degrees']
                speed = command['speed']
                direction = command['direction']
                await loop.run_in_executor(None, self.messenger.send, 'move_spout', degrees, speed, direction)
                logger.info(f"Sent command: move_spout with degrees={degrees}, speed={speed}, direction={direction}")
                response = await asyncio.wait_for(self.wait_for_response('spout_done'), timeout=30)

            elif cmd_type == 'move_both':
                cone_steps = command['cone_steps']
                cone_speed = command['cone_speed']
                spout_degrees = command['spout_degrees']
                spout_speed = command['spout_speed']
                direction = command['direction']
                await loop.run_in_executor(
                    None,
                    self.messenger.send,
                    'move_both',
                    cone_steps,
                    cone_speed,
                    spout_degrees,
                    spout_speed,
                    direction
                )
                logger.info(f"Sent command: move_both with cone_steps={cone_steps}, cone_speed={cone_speed}, "
                            f"spout_degrees={spout_degrees}, spout_speed={spout_speed}, direction={direction}")
                response = await asyncio.wait_for(self.wait_for_response('both_done'), timeout=30)

            else:
                raise ValueError(f"Unknown command type: {cmd_type}")

            return response

        except Exception as e:
            logger.error(f"Error executing command {cmd_type}: {str(e)}", exc_info=True)
            return ['error', str(e)]

    async def handle_client(self, reader: asyncio.StreamReader, writer: asyncio.StreamWriter) -> None:
        while True:
            try:
                data = await reader.readline()
                if not data:
                    break

                received_data = json.loads(data.decode())
                responses = []

                if isinstance(received_data, list):
                    # Handle array of commands
                    for command in received_data:
                        response = await self.execute_command(command)
                        responses.append({
                            'command': command['command'],
                            'status': response[0] if response else 'no_response',
                            'data': response[1] if response and len(response) > 1 else None
                        })
                else:
                    # Handle single command
                    response = await self.execute_command(received_data)
                    responses.append({
                        'command': received_data['command'],
                        'status': response[0] if response else 'no_response',
                        'data': response[1] if response and len(response) > 1 else None
                    })

                # Send response back to client
                writer.write((json.dumps(responses) + '\n').encode())
                await writer.drain()

            except Exception as e:
                logger.error(f"Error handling command: {e}", exc_info=True)
                error_response = json.dumps({'error': str(e)})
                writer.write((error_response + '\n').encode())
                await writer.drain()

    async def start_server(self) -> None:
        try:
            if os.path.exists(self.socket_path):
                os.unlink(self.socket_path)
        except OSError as e:
            logger.error(f"Error unlinking socket: {e}")

        server = await asyncio.start_unix_server(
            self.handle_client, 
            path=self.socket_path
        )

        logger.info(f"Mech-Control service started on {self.socket_path}")
        
        async with server:
            await server.serve_forever()

    def cleanup(self) -> None:
        self.arduino.close()
        try:
            if os.path.exists(self.socket_path):
                os.unlink(self.socket_path)
        except OSError as e:
            logger.error(f"Error cleaning up socket: {e}")

if __name__ == "__main__":
    service = MechControlService()
    
    try:
        asyncio.run(service.start_server())
    except KeyboardInterrupt:
        logger.info("Shutting down Mech-Control service...")
    finally:
        service.cleanup()
