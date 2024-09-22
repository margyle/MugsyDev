# Mugsy: Operator Middleware
[![codecov](https://codecov.io/gh/margyle/operator/graph/badge.svg?token=Q1BR1UE0FG)](https://codecov.io/gh/margyle/operator)

**Please note that this app is not ready for prime time just yet and this Read Me may be out of date!**
## Introduction
This repository contains the source code for the Operator App, a FastAPI application designed to manage and control Mugsys hardware devices. All GPIO control is handled by Operator to keep hardware related functionality isolated from the primary DECAF api, allowing for increased hardware compatibility. 

Requests from the Mugsy frontend are sent to the DECAF API which handles all primary functionality. When a GPIO action is required, DECAF calls Operator to perform the action.

## Getting Started

### Prerequisites
Before you begin, ensure you have the following installed on your system:
- Python 3.8 or higher
- pip
- virtualenv (optional but recommended)

### Setting Up the Environment

To set up the project environment:
1. Clone the repository:
   ```bash
   git clone https://github.com/margyle/operator.git
   cd operator
   ```

2. Create and activate a virtual environment (optional but recommended):
   - For Unix/MacOs:
     ```bash
     python -m venv venv
     source venv/bin/activate
     ```
   - For Windows:
     ```cmd
     python -m venv venv
     venv\Scripts\activate
     ```


### Installing the Application

#### Development Mode
To install the application in editable mode, which is recommended for development purposes:
```bash
pip install -e .
```
This command allows you to modify the source code and see changes without reinstalling the package.

#### Production Mode
To install the application for production use:
```bash
pip install .
```
This command installs the application as a standard Python package.

## Usage

To start the FastAPI application, run:
```bash
uvicorn operator_app.app:app --reload --host 0.0.0.0 --port 8000
```
This command will start the FastAPI server with live reloading enabled.

## Tests

To run tests, run:
```bash
pytest
```

## Troubleshooting

If you are getting import errors, you may need to add your app source to the ENV, run:
```bash
export PYTHONPATH=$PYTHONPATH:/path/to/Operator/src
```
## Scale Setup

### Install Libraries
After testing numerous libs to handle the HX711 load cell amps, the best option was found to be the [Raspberry Pi HX711 C++ Library](https://github.com/endail/hx711) by Daniel Robertson (@endail on Github), and its associated Python wrappers for interacting with it from Operator. 

Installation: 
In order to use the Python wrapper we must first install the C++ library:
* In your terminal, enter: ```sudo apt-get install -y liblgpio-dev```
* If it is not available, manually build and install it: 

```
pi@raspberrypi:~ $ git clone --depth=1 https://github.com/endail/hx711
pi@raspberrypi:~ $ cd hx711
pi@raspberrypi:~/hx711 $ make && sudo make install
```

* Run `ldconfig` , it should not require any additional actions once completed.

Install Python Wrapper:
* ```pip3 install --upgrade hx711-rpi-py```

- - -

### Calibrate Scales (headless):
The easiest method to calibrate the scales without the UI is to start Operator and connect to the calibration endpoint in Postman: ws://mugsy-0.local:8000/v1/calibrate-scales/CONE_SCALE

> If you want to calibrate the mug platform, just swap out CONE_SCALE for MUG_SCALE in the endpoint’s URL.

Once connected, you will be walked through the calibration process. Here are the steps and the response formatting for each step: 

* Step 1: Request unit
  * Text: "Enter the unit you want to measure the object in (e.g., g, kg, lb, oz):"
  * Asking: The unit of measurement for the object. Mugsy is set up to work with grams, so enter g.
  * Expected response: ```{"unit": “g”}```
* Step 2: Request known weight
  * Text: "Enter the weight of the object in [unit]:"
  * Asking: The weight of the object you are using to calibrate the scale in grams. It currently expects a float so you should enter the weight to two decimal spots.
  * Expected response: ```{"known_weight": 100.00}```
* Step 3: Request number of samples
  * Text: "Enter the number of samples to take from the HX711 chip (e.g., 15):"
  * Asking: The number of samples to take from the HX711 chip. We have had great results with a total of 15 samples.
  * Expected response: ```{"samples": 15}```
* Step 4: Zero the scale
  * Text: "Remove all objects from the scale and then press enter."
  * Asking: Confirmation that all objects have been removed from the scale
  * Expected response: ```{“ready”: true}```
* Step 5: Measure known weight
  * Text: "Place the object on the scale and then press enter."
  * Asking: Confirmation that the object has been placed on the scale
  * Expected response: ```{“ready”: true}```
 
After these steps, the endpoint sends a final JSON response with the calibration results, including:
* known_weight
* unit
* raw_value
* samples
* reference_unit
* zero_value

The calibration values are automatically written to the hardware configuration file, ready to be used in any weight related functions.

## Contributing
Contributions to this project are welcome. Please ensure that all pull requests are well-documented and include tests where applicable.

## Third-Party Libraries

### Modified Library File

The file `src/operator_services/libs/hx711.py` in this project was based on [HX711](https://github.com/gandalf15/HX711) which is licensed under the BSD 3-Clause License, Copyright (c) 2017, Marcel Zak. Modifications were made to make it compatible with pigpio.

Original Library License: [BSD 3-Clause License](https://opensource.org/licenses/BSD-3-Clause)

Please refer to the original source for the unmodified version and further details about the library.

