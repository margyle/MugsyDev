#!/bin/bash

# Activate the virtual environment
source /home/maos/Mugsy/dev/Operator/venv/bin/activate

# Set PYTHONPATH
export PYTHONPATH=/home/maos/Mugsy/dev/Operator:$PYTHONPATH

# Start watchtower
python3 src/operator_services/cone_scale_service.py