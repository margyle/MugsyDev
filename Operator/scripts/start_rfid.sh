#!/bin/bash

# Activate the virtual environment
source /home/maos/Mugsy/dev/Operator/venv/bin/activate

# Set PYTHONPATH
export PYTHONPATH=/home/maos/Mugsy/dev/Operator/src:$PYTHONPATH

# Start the RFID service
python src/operator_services/rfid_service.py