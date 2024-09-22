from fastapi import BackgroundTasks
import pigpio
import asyncio

pi = pigpio.pi()

def turn_on(gpio_pin, time_duration=None, background_tasks: BackgroundTasks = None):
    if not pi.connected:
        return {"error": "GPIO daemon not connected"} # pragma: no cover
    pi.set_mode(gpio_pin, pigpio.OUTPUT) # pragma: no cover
    pi.write(gpio_pin, 1) # pragma: no cover
    if time_duration and background_tasks:
        background_tasks.add_task(wait_and_turn_off, gpio_pin, time_duration)
    return {"gpio_pin": gpio_pin, "status": "on for {} seconds".format(time_duration) if time_duration else "on"}

async def wait_and_turn_off(gpio_pin, time_duration):
  print('hit')
  await asyncio.sleep(time_duration)
  pi.write(gpio_pin, 0) # turn off the pin

def turn_off(gpio_pin):
  if not pi.connected:
    return {"error": "GPIO daemon not connected"} # pragma: no cover
  pi.set_mode(gpio_pin, pigpio.OUTPUT) # pragma: no cover
  pi.write(gpio_pin, 0) # pragma: no cover
  return {"gpio_pin": gpio_pin, "status": "off"}