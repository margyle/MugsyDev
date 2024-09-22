#!/bin/bash

export $(grep -v '^#' .env.dev | xargs)
uvicorn operator_app.app:app --reload --host 0.0.0.0 --port 8000 --log-level debug