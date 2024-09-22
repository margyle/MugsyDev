import pytest
from unittest.mock import patch, MagicMock
from fastapi.testclient import TestClient
from fastapi import FastAPI
from operator_app.api.v1.pump.pump_routes import router as pump_router
from operator_app.auth import auth_handler

app = FastAPI()
app.include_router(pump_router)

client = TestClient(app)

# Mock the authentication dependency
@pytest.fixture(autouse=True)
def mock_auth(monkeypatch):
    async def mock_decode_token():
        return {"sub": "test_user"}
    monkeypatch.setattr(auth_handler, "decode_token", mock_decode_token)

@patch('operator_app.api.v1.pump.pump_service_client.send_command')
def test_control_pump(mock_send_command):
    mock_send_command.return_value = {"status": "success"}
    response = client.post("/", json={"direction": "forward"})
    assert response.status_code == 200
    assert response.json() == {"status": "Pump set to forward", "result": {"status": "success"}}
    mock_send_command.assert_called_once_with({
        'action': 'control',
        'params': {'direction': 'forward'}
    })

@patch('operator_app.api.v1.pump.pump_service_client.send_command')
def test_control_pump_speed(mock_send_command):
    mock_send_command.return_value = {"status": "success"}
    response = client.post("/flow-rate", json={"direction": "forward", "speed": 50})
    assert response.status_code == 200
    assert response.json() == {"status": "Pump set to forward at 50% speed", "result": {"status": "success"}}
    mock_send_command.assert_called_once_with({
        'action': 'control_speed',
        'params': {'direction': 'forward', 'speed': 50}
    })

@patch('operator_app.api.v1.pump.pump_service_client.send_command')
def test_get_pump_status(mock_send_command):
    mock_send_command.return_value = {"direction": "forward", "speed": 75, "status_flag": True}
    response = client.get("/status")
    assert response.status_code == 200
    assert response.json() == {"direction": "forward", "speed": 75, "status_flag": True}
    mock_send_command.assert_called_once_with({'action': 'status'})

@patch('operator_app.api.v1.pump.pump_service_client.send_command')
def test_control_pump_error(mock_send_command):
    mock_send_command.side_effect = Exception("Connection error")
    response = client.post("/", json={"direction": "forward"})
    assert response.status_code == 500
    assert response.json() == {"detail": "Error controlling pump: Connection error"}

@patch('operator_app.api.v1.pump.pump_service_client.send_command')
def test_control_pump_speed_error(mock_send_command):
    mock_send_command.side_effect = Exception("Connection error")
    response = client.post("/flow-rate", json={"direction": "forward", "speed": 50})
    assert response.status_code == 500
    assert response.json() == {"detail": "Error controlling pump speed: Connection error"}

@patch('operator_app.api.v1.pump.pump_service_client.send_command')
def test_get_pump_status_error(mock_send_command):
    mock_send_command.side_effect = Exception("Connection error")
    response = client.get("/status")
    assert response.status_code == 500
    assert response.json() == {"detail": "Error getting pump status: Connection error"}