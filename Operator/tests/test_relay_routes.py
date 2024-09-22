import pytest
from unittest.mock import patch, MagicMock, AsyncMock
from fastapi.testclient import TestClient
from fastapi import FastAPI
import time
import pigpio
from operator_app.api.v1.relay.relay_routes import router as relay_router, set_relay_off_after_timer

app = FastAPI()
app.include_router(relay_router)

client = TestClient(app)

@patch('operator_app.api.v1.relay.relay_routes.pi')
@patch('operator_app.api.v1.relay.relay_routes.send_command_to_watchtower')
def test_set_relay_turns_on_given_relay_channel(mocked_send_command_to_watchtower, mocked_pi):
    response = client.post("/", json={"relay_channel": 1, "state": "on"})
    assert response.status_code == 200
    mocked_pi.set_mode.assert_called_once_with(22, pigpio.OUTPUT)
    mocked_pi.write.assert_called_once_with(22, 0)
    mocked_send_command_to_watchtower.assert_called_once()

def test_set_relay_returns_400_for_invalid_channel():
    response = client.post("/", json={"relay_channel": 5, "state": "on"})
    assert response.status_code == 400

@pytest.mark.asyncio
@patch('operator_app.api.v1.relay.relay_routes.asyncio.sleep', new_callable=AsyncMock)
async def test_set_relay_off_after_timer(mock_sleep):
    with patch('operator_app.api.v1.relay.relay_routes.pi.write') as mock_write:
        await set_relay_off_after_timer(22, 5)
        mock_sleep.assert_awaited_with(5)
        mock_write.assert_called_with(22, 1)