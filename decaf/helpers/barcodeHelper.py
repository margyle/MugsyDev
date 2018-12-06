import os
import time

#file_path = "/var/mugsy/decaf/helpers/scanned.txt"

def scan():
	#os.system("sudo logkeys -s -d event0 -o /var/mugsy/decaf/helpers/scanned.txt --no-timestamps")
	#with open('scanned.txt', 'rb') as f:
	for line in open("/var/mugsy/decaf/helpers/scanned.txt"):
		last=line
	return(last)

#scan()
def afterScan():
	(os.system("cp /var/mugsy/decaf/helpers/afterScan.txt /var/mugsy/decaf/helpers/upc.txt"))
	(os.system("cp /var/mugsy/decaf/helpers/afterScan.txt /var/mugsy/decaf/helpers/scanned.txt"))


def duringScan():
	(os.system("cp /var/mugsy/decaf/helpers/scanned.txt /var/mugsy/decaf/helpers/upc.txt"))







