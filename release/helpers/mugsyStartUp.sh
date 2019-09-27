#! /bin/sh
python3 /var/mugsy/decaf.py
python3 /var/mugsy/mugsyOS.py
chromium-browser --no-sandbox --no-first-run --hide-scrollbars --noerrdialogs --start-fullscreen \
 --start-maximized --disable-notifications --disable-infobars --kiosk https://localhost/mugsy/interface/setup.php
