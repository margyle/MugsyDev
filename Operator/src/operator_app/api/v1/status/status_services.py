import pigpio

pi = pigpio.pi()

def read_state(gpio_pin):
    if not pi.connected:
        return {"error": "GPIO daemon not connected"} # pragma: no cover
    state = pi.read(gpio_pin) # pragma: no cover
    return {"gpio_pin": gpio_pin, "state": state}
