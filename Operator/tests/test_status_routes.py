import pytest
from unittest.mock import patch, MagicMock
from fastapi.testclient import TestClient
from fastapi import FastAPI
import pigpio  # Import pigpio for usage in your test
from operator_app.api.v1.status.status_routes import router as status_router

app = FastAPI()
app.include_router(status_router)

client = TestClient(app)

@patch('operator_app.api.v1.status.status_services.pi')
def test_read_state_route(mocked_pi):
  mocked_pi.connected = True
  mocked_pi.read.return_value = 1
  response = client.get("/state/22")
  assert response.status_code == 200
  assert response.json() == {"gpio_pin": 22, "state": 1}
  mocked_pi.read.assert_called_once_with(22)