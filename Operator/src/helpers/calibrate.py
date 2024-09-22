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
import os
import sys
from datetime import timedelta

from HX711 import *

if len(sys.argv) != 3:
    print("Usage: calibrate.py [data pin] [clock pin]", file=sys.stderr)
    sys.exit(os.EX_USAGE)

try:
    hx = SimpleHX711(int(sys.argv[1]), int(sys.argv[2]), 1, 0)
except GpioException:
    print("Failed to connect to HX711 chip", file=sys.stderr)
    sys.exit(os.EX_UNAVAILABLE)
except TimeoutException:
    print("Failed to connect to HX711 chip", file=sys.stderr)
    sys.exit(os.EX_UNAVAILABLE)


print("""
\x1B[2J\x1B[H
========================================
HX711 Calibration
========================================

Find an object you know the weight of. If you can't find anything,
try searching Google for your phone's specifications to find its weight.
You can then use your phone to calibrate your scale.
""")

unit = input("1. Enter the unit you want to measure the object in (eg. g, kg, lb, oz): ")

knownWeight = float(input("2. Enter the weight of the object in the unit you chose (eg. " +
                    "if you chose 'g', enter the weight of the object in grams): "))

samples = int(input("3. Enter the number of samples to take from the HX711 chip (eg. 15): "))

input("4. Remove all objects from the scale and then press enter.")
print("Working...")

zeroValue = hx.read(Options(int(samples)))

input("5. Place object on the scale and then press enter.")
print("Working...")

raw = hx.read(Options(int(samples)))
refUnitFloat = (raw - zeroValue) / knownWeight
refUnit = round(refUnitFloat, 0)

if refUnit == 0:
    refUnit = 1

print(
    "\n\n" +
    "Known weight (your object) " + str(knownWeight) + " " + unit + "\n" +
    "Raw value over " + str(samples) + " samples: " + str(raw) + "\n" +
    "\n" +
    "-> REFERENCE UNIT: " + str(round(refUnit)) + "\n" +
    "-> ZERO VALUE: " + str(round(zeroValue)) + "\n" +
    "\n" +
    "You can provide these values to the constructor when you create the "
    "HX711 objects or later on. For example: \n" +
    "\n" +
    "hx = SimpleHX711(" + sys.argv[1] + ", " + sys.argv[2] + ", " + 
    str(round(refUnit)) + ", " + str(round(zeroValue)) + ")\n" +
    "OR\n" +
    "hx.setReferenceUnit(" + str(round(refUnit)) + ") and " +
    "hx.setOffset(" + str(round(zeroValue)) + ")\n"
)

sys.exit(os.EX_OK)