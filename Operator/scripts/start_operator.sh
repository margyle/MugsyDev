#!/bin/bash

# Activate the virtual environment
source /home/maos/Mugsy/dev/Operator/venv/bin/activate

# Set PYTHONPATH
export PYTHONPATH=/home/maos/Mugsy/dev/Operator/src:$PYTHONPATH

# Start the FastAPI app
uvicorn operator_app.app:app --reload --host 0.0.0.0 --port 8000