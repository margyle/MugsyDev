# BSD 3-Clause License

# Copyright (c) 2017, Marcel Zak
# All rights reserved.

# Redistribution and use in source and binary forms, with or without
# modification, are permitted provided that the following conditions are met:

# * Redistributions of source code must retain the above copyright notice, this
#   list of conditions and the following disclaimer.

# * Redistributions in binary form must reproduce the above copyright notice,
#   this list of conditions and the following disclaimer in the documentation
#   and/or other materials provided with the distribution.

# * Neither the name of the copyright holder nor the names of its
#   contributors may be used to endorse or promote products derived from
#   this software without specific prior written permission.

# THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
# AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
# IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
# DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
# FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
# DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
# SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
# CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
# OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
# OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

import pigpio
import statistics as stat
import time

class HX711:

    def __init__(self, pi, dout_pin, pd_sck_pin, gain_channel_A=128, select_channel='A'):
        """
        Initialize a new instance of HX711 using pigpio for GPIO control.
        """
        self.pi = pi
        self.dout = dout_pin
        self.pd_sck = pd_sck_pin
        self.gain_channel_A = gain_channel_A
        self.selected_channel = select_channel  # Rename this

        # Initialize pins
        self.pi.set_mode(self.pd_sck, pigpio.OUTPUT)
        self.pi.set_mode(self.dout, pigpio.INPUT)

        # Set the gain and select the channel initially
        self.set_gain(gain_channel_A)
        self.select_channel(select_channel)
        self.offset = 0
        self.ratio = 1  # Initial scale ratio

    def set_gain(self, gain=128):
        """
        Set the gain; 128 or 64 for channel A, 32 for channel B
        """
        self.gain_channel_A = gain
        self.read()  # Discard the reading after changing the gain as it might be garbage.

    def select_channel(self, channel):
        """
        Select the input channel on the HX711: 'A' or 'B'
        """
        self.selected_channel = channel  # Rename this
        self.read()  # Discard the reading after changing the channel as it might be garbage.


    def zero(self, readings=30):
      """
      Set the current data as an offset for the particular channel, also known as taring the scale.
      """
      values = [self.read() for _ in range(readings)]
      values = [x for x in values if x is not False]  # Filter out invalid readings

      if values:
        self.offset = stat.mean(values)
        return None  # Return None if successful
      else:
        return "Tare is unsuccessful."  # Return an error message if unsuccessful

    def read(self):
        """
        Read a single raw value from the HX711.
        """
        while self.pi.read(self.dout) == 1:
            time.sleep(0.001)

        data = 0
        for _ in range(24):
            self.pi.gpio_trigger(self.pd_sck, 1, 1)
            bit = self.pi.read(self.dout)
            data = (data << 1) | bit

        # Set channel and gain factor for next reading
        for _ in range(self.gain_channel_A // 32):
            self.pi.gpio_trigger(self.pd_sck, 1, 1)

        if data & 0x800000:  # if sign bit is set
            data -= 0x1000000

        return data

    def get_weight(self, readings=5):
        """
        Capture several readings to stabilize the result and calculate the weight.
        """
        values = [self.read() for _ in range(readings)]
        values = [x for x in values if x is not False]  # Filter out invalid readings
        if not values:
            return False

        return (stat.mean(values) - self.offset) / self.ratio

    def set_scale_ratio(self, known_weight):
        """
        Calibrate the scale given a known weight.
        """
        value = self.read()
        print(value)
        if value:
            self.ratio = value / known_weight
        else:
            self.ratio = 1  # Default to 1 to avoid division by zero

    def power_down(self):
        """
        Power down the HX711.
        """
        self.pi.gpio_trigger(self.pd_sck, 1, 1)  # Set high to enter power down mode
        time.sleep(0.01)  # Ensure HX711 has powered down

    def power_up(self):
        """
        Power up the HX711.
        """
        self.pi.write(self.pd_sck, 0)
        time.sleep(0.01)  # Wait for the HX711 to settle after powering up

