#! /bin/sh
python3 /var/mugsy/decaf/decaf.py 
chromium-browser --no-sandbox --no-first-run --hide-scrollbars --noerrdialogs --start-fullscreen \
 --start-maximized --disable-notifications --disable-infobars --kiosk http://localhost/mugsy/interface/wifi.php 
