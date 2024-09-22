import pigpio
import subprocess

# Define the pins used by the HX711
pins_to_release = [5, 6, 17, 27]  # Add all pins that might be used

# Function to kill any process using pigpio
def kill_pigpio_processes():
    try:
        subprocess.run(['sudo', 'killall', 'pigpiod'], check=True)
    except subprocess.CalledProcessError:
        pass

# Function to restart pigpio daemon
def restart_pigpio_daemon():
    try:
        subprocess.run(['sudo', 'pigpiod'], check=True)
    except subprocess.CalledProcessError as e:
        print(f"Failed to start pigpiod daemon: {e}")

# Connect to pigpio daemon
pi = pigpio.pi()
if not pi.connected:
    print("Failed to connect to pigpio daemon. Restarting daemon...")
    kill_pigpio_processes()
    restart_pigpio_daemon()
    pi = pigpio.pi()
    if not pi.connected:
        print("Failed to connect to pigpio daemon after restart.")
        exit(1)

# Reset each pin
for pin in pins_to_release:
    pi.set_mode(pin, pigpio.INPUT)  # Set pin mode to INPUT to release it
    pi.set_pull_up_down(pin, pigpio.PUD_OFF)  # Disable any pull up/down resistors
    pi.write(pin, 0)  # Write a low value to reset the state

pi.stop()
print("Pins released successfully.")
