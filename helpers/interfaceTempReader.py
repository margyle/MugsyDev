#this is super hacky and ugly. It writes the current temperature to a text file for realtime display.
#Will be rewritten for the flask conversion. It Does not impact brewing, only the display!
import serial
import shutil

ser = serial.Serial('/dev/ttyACM0',9600)

f = open('waterTemp.txt','w')

while 1 :
    f.write(ser.readline())
    f.flush
    f = open('waterTemp.txt','w')
    shutil.copy('/var/mugsy/code/v1/middleware/helpers/tempReadWrite/waterTemp.txt', '/var/www/html/heymugsy.com/helpers/')
