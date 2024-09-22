import socket
import os
import time
import pigpio
import threading

# Socket setup
socket_path = "/tmp/watchtower_socket"
if os.path.exists(socket_path):
    os.unlink(socket_path)

server = socket.socket(socket.AF_UNIX, socket.SOCK_STREAM)
server.bind(socket_path)
server.listen()

# Setup pigpio
pi = pigpio.pi()

def set_pin_high_later(pin, delay):
    time.sleep(delay)
    pi.write(pin, 1)  # Set pin high
    print(f"Pin {pin} set high after {delay} seconds.")

def handle_command(command):
    pin = command.get("pin")
    delay = command.get("delay")
    threading.Thread(target=set_pin_high_later, args=(pin, delay)).start()

print("Mugsy Watchtower: service started, monitoring GPIO...")

# Main loop to listen for commands
try:
    while True:
        conn, _ = server.accept()
        with conn:
            while True:
                data = conn.recv(1024)
                if not data:
                    break
                command = eval(data.decode())  # Be cautious with eval(), consider security
                handle_command(command)
finally:
    server.close()
    pi.stop()
