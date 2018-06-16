#!/usr/bin/python
import RPi.GPIO as GPIO
import time

GPIO.setmode(GPIO.BCM)

# init list with pin numbers

pinList = [23,24,18,12,16,20,21,25]

# loop through pins and set mode and state

for i in pinList: 
    GPIO.setup(i, GPIO.OUT) 
    GPIO.output(i, GPIO.HIGH)

# time to sleep between operations in the main loop
#relay on
SleepTimeS = 1
#relay off
SleepTimeL = 1

# main loop
try:
   while True:
    GPIO.output(23, GPIO.LOW)
    time.sleep(.1);
    GPIO.output(23, GPIO.HIGH)
    GPIO.output(24, GPIO.LOW)
    time.sleep(.1);
    GPIO.output(24, GPIO.HIGH)
    GPIO.output(18, GPIO.LOW)
    time.sleep(.1);
    GPIO.output(18, GPIO.HIGH)
    GPIO.output(12, GPIO.LOW)
    time.sleep(.1);
    GPIO.output(12, GPIO.HIGH)
   # GPIO.output(16, GPIO.LOW)
   # time.sleep(.1);
   # GPIO.output(16, GPIO.HIGH)
   # GPIO.output(20, GPIO.LOW)
   # time.sleep(.1);
   # GPIO.output(20, GPIO.HIGH)
   # GPIO.output(21, GPIO.LOW)
   # time.sleep(.1);
   # GPIO.output(21, GPIO.HIGH)
   # GPIO.output(25, GPIO.LOW)
   # time.sleep(.1);
   # GPIO.output(25, GPIO.HIGH)



# End program cleanly with keyboard
except KeyboardInterrupt:
  print "  Quit"

  # Reset GPIO settings
  GPIO.cleanup()


