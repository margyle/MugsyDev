# MIT License

# Copyright (c) 2021 Daniel Robertson

# Permission is hereby granted, free of charge, to any person obtaining a copy
# of this software and associated documentation files (the "Software"), to deal
# in the Software without restriction, including without limitation the rights
# to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
# copies of the Software, and to permit persons to whom the Software is
# furnished to do so, subject to the following conditions:

# The above copyright notice and this permission notice shall be included in all
# copies or substantial portions of the Software.

# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
# SOFTWARE.

# Contributions by Matthew Oswald, 2024

# Permission is hereby granted, free of charge, to any person obtaining a copy
# of this software and associated documentation files (the "Software"), to deal
# in the Software without restriction, including without limitation the rights
# to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
# copies of the Software, and to permit persons to whom the Software is
# furnished to do so, subject to the following conditions:

# The above copyright notice and this permission notice shall be included in all
# copies or substantial portions of the Software.

# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
# SOFTWARE.

from HX711 import SimpleHX711, GpioException, TimeoutException, Mass
import pigpio
import sys
import signal

# Define a function to handle the cleanup
def cleanup(hx, pi):
    print("Cleaning up GPIO...")
    if hx:
        hx.cleanup()
    if pi:
        pi.stop()
    print("Cleanup done.")

# Handle keyboard interrupt signal
def signal_handler(sig, frame):
    cleanup(hx, pi)
    sys.exit(0)

# Register the signal handler
signal.signal(signal.SIGINT, signal_handler)

# Initialize variables for HX711 and pigpio
hx = None
pi = None

try:
    # Define the pins used by the HX711
    data_pin = 5
    clock_pin = 6
    ref_unit = 473
    offset = 97870

    # Connect to pigpio daemon
    pi = pigpio.pi()
    if not pi.connected:
        print("Failed to connect to pigpio daemon.")
        sys.exit(1)

    # Initialize HX711 with your specific parameters
    hx = SimpleHX711(data_pin, clock_pin, ref_unit, offset)
    print("Successfully connected to HX711 chip.")

    # Set the scale to output weights in grams
    hx.setUnit(Mass.Unit.G)

    # Zero the scale
    hx.zero()

    # Constantly output weights using the median of 35 samples
    while True:
        print(hx.weight(35))

except GpioException:
    print("Failed to connect to HX711 chip due to GPIO exception.")
except TimeoutException:
    print("Failed to connect to HX711 chip due to timeout.")
except Exception as e:
    print(f"An error occurred: {e}")
finally:
    cleanup(hx, pi)
