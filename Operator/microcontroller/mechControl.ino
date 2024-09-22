// MOVE,0,150,500,-20
// MOVE,0,1500,3185,-10
// <MOVE,0,1500,3185,-10><MOVE,0,1500,3185,10>
// <MOVE,0,1500,3185,-5><MOVE,1,1500,3185,5><MOVE,0,1500,3185,-2><MOVE,1,1500,3185,2><MOVE,0,1500,3185,-5><MOVE,1,1500,3185,5><MOVE,0,1500,3185,0>
#include <AccelStepper.h>
#include <CircularBuffer.hpp>

// Define the pins for the cone stepper (first stepper)
#define coneStepPin 3
#define coneDirPin 2
#define coneEnablePin 4
#define coneMotorInterfaceType 1

// Define the pins for the spout stepper (second stepper)
#define spoutStepPin 6
#define spoutDirPin 5
#define spoutEnablePin 7
#define spoutMotorInterfaceType 1

// Define the microstepping pins for the spout stepper
#define M0 11
#define M1 10
#define M2 9

// Define the limit switch pin for zeroing the spout stepper
#define limitSwitchPin 8

// Create stepper objects
AccelStepper coneStepper = AccelStepper(coneMotorInterfaceType, coneStepPin, coneDirPin);
AccelStepper spoutStepper = AccelStepper(spoutMotorInterfaceType, spoutStepPin, spoutDirPin);

// Track zeroing state for the spout stepper
bool isZeroed = false;

// Constants for spout stepper
const float STEP_ANGLE = 0.9;            // Step angle in degrees for the spout stepper
const int MICROSTEPS = 32;               // Number of microsteps for the spout stepper
const float PULLEY_RATIO = 40.0 / 20.0;  // Ratio of spout pulley to motor pulley

// Calculate steps per degree for the spout stepper, accounting for the pulley ratio
const float SPOUT_STEPS_PER_DEGREE = (1.0 / STEP_ANGLE) * MICROSTEPS * PULLEY_RATIO;

// Constants for cone stepper
const int CONE_STEPS_PER_REVOLUTION = 3185;

// Track the last state of the limit switch
int lastSwitchState = LOW;

// Command buffer
String commandBuffer = "";

// Create a circular buffer to store commands
CircularBuffer<String, 20> commandQueue;

void setup() {
  // Set up the limit switch pin as input
  pinMode(limitSwitchPin, INPUT_PULLUP);
  
  // Set up the enable pins
  coneStepper.setEnablePin(coneEnablePin);
  spoutStepper.setEnablePin(spoutEnablePin);

  // Set up the microstepping pins as outputs and configure for 1/32 microstepping
  pinMode(M0, OUTPUT);  
  pinMode(M1, OUTPUT);
  pinMode(M2, OUTPUT);
  digitalWrite(M0, HIGH);  // Set M0 to HIGH for 1/32 microstepping
  digitalWrite(M1, HIGH);  // Set M1 to HIGH for 1/32 microstepping
  digitalWrite(M2, HIGH);  // Set M2 to HIGH for 1/32 microstepping

  // Set the maximum speed and acceleration for cone stepper
  coneStepper.setMaxSpeed(1000);                    // Adjust speed as needed
  coneStepper.setAcceleration(10000);               // Adjust acceleration as needed
  coneStepper.setPinsInverted(false, false, true);  // Invert enable pin

  // Set maximum speed, acceleration, and enable microstepping for spout stepper
  spoutStepper.setMaxSpeed(2000);                    // Adjust speed as needed
  spoutStepper.setAcceleration(10000);               // Adjust acceleration as needed
  spoutStepper.setPinsInverted(false, false, true);  // Invert enable pin

  // Disable both motors initially
  coneStepper.disableOutputs();
  spoutStepper.disableOutputs();

  // Initialize serial communication
  Serial.begin(9600);

  Serial.println("Starting Mugsy: Mech Control system...");
  Serial.print("Calculated spout steps per degree: ");
  Serial.println(SPOUT_STEPS_PER_DEGREE);
  Serial.print("Cone steps per revolution: ");
  Serial.println(CONE_STEPS_PER_REVOLUTION);

  // Safety check to ensure the limit switch is connected
  if (digitalRead(limitSwitchPin) == HIGH) {
    Serial.println("Error: Limit switch not connected");
    while (true);  // Stop execution
  }

  // Zero the spout stepper motor
  zeroSpoutStepper();
}

void loop() {
  // Process incoming serial data
  while (Serial.available() > 0) {
    char inChar = (char)Serial.read();
    commandBuffer += inChar;
    if (inChar == '>') {
      // Add the command to the queue instead of processing immediately
      commandQueue.push(commandBuffer);
      commandBuffer = "";
    }
  }

  // Process a command from the queue if available and steppers are not moving
  if (!commandQueue.isEmpty() && coneStepper.distanceToGo() == 0 && spoutStepper.distanceToGo() == 0) {
    String nextCommand = commandQueue.shift();
    processCommands(nextCommand);
  }

  // Run both steppers
  runSteppers();
}

void processCommands(String input) {
  int start = 0;
  int end = input.indexOf('>', start);

  while (end != -1) {
    String command = input.substring(start + 1, end);
    handleCommand(command);
    start = end + 1;
    end = input.indexOf('>', start);
  }
  
}

void runSteppers() {
  bool coneMoving = coneStepper.distanceToGo() != 0;
  bool spoutMoving = spoutStepper.distanceToGo() != 0;

  if (coneMoving || spoutMoving) {
    coneStepper.enableOutputs();
    spoutStepper.enableOutputs();

    if (coneMoving) coneStepper.run();
    if (spoutMoving) spoutStepper.run();
  } else {
    coneStepper.disableOutputs();
    spoutStepper.disableOutputs();
  }
}

void handleCommand(String input) {
  // Split the input string into components
  char* command = strtok(&input[0], ",");
  if (command == NULL) return;

  if (String(command) == "MOVE") {
    char* dirStr = strtok(NULL, ",");
    char* speedStr = strtok(NULL, ",");
    char* distanceStr = strtok(NULL, ",");
    char* tiltPosStr = strtok(NULL, ",");

    if (dirStr != NULL && speedStr != NULL && distanceStr != NULL && tiltPosStr != NULL) {
      int direction = atoi(dirStr);
      int speed = atoi(speedStr);
      int distance = atoi(distanceStr);
      int tiltPosition = atoi(tiltPosStr);

      // Move the cone stepper
      long coneSteps = direction == 1 ? distance : -distance;
      coneStepper.move(coneSteps);
      coneStepper.setMaxSpeed(speed);

      Serial.print("Moving cone: ");
      Serial.print(coneSteps);
      Serial.println(" steps");

      // Check the tilt position constraints for the spout stepper
      if (isZeroed) {
        if (tiltPosition >= -45 && tiltPosition <= 45) {
          long stepsToMove = (long)(tiltPosition * SPOUT_STEPS_PER_DEGREE);
          Serial.print("Moving spout to Pour Lane: ");
          Serial.print(tiltPosition);
          Serial.print(" ,Steps: ");
          Serial.println(stepsToMove);
          spoutStepper.moveTo(stepsToMove);
        }
      }
    }
  } else if (String(command) == "STOP") {
    coneStepper.stop();
    spoutStepper.stop();
    disableSteppers();
  }
}

void zeroSpoutStepper() {
  // Enable the motor
  spoutStepper.enableOutputs();

  // Rotate counterclockwise until the limit switch is hit
  spoutStepper.setSpeed(-4000);  // Negative speed for counterclockwise
  Serial.println("Zeroing spout stepper...");
  while (true) {
    int switchState = digitalRead(limitSwitchPin);
    spoutStepper.runSpeed();
    if (switchState != lastSwitchState) {
      if (switchState == HIGH) {
        Serial.println("Limit switch triggered!");
        break;
      }
      lastSwitchState = switchState;
    }
  }

  // Move a small amount to test
  testMovement(44);

  // Set current position as zero
  spoutStepper.setCurrentPosition(0);
  isZeroed = true;

  // Disable the motor
  spoutStepper.disableOutputs();
  Serial.println("Spout stepper zeroed and disabled.");
  Serial.println("Status: Ready");
}

void testMovement(float angle) {
  long stepsToMove = (long)(angle * SPOUT_STEPS_PER_DEGREE);
  Serial.print("Test movement - Angle: ");
  Serial.print(angle);
  Serial.print(" degrees, Steps: ");
  Serial.println(stepsToMove);

  spoutStepper.move(stepsToMove);

  while (spoutStepper.distanceToGo() != 0) {
    spoutStepper.run();
  }

  Serial.print("Final position: ");
  Serial.println(spoutStepper.currentPosition());
}

void disableSteppers() {
  Serial.println("Disabling steppers...");
  spoutStepper.disableOutputs();
  coneStepper.disableOutputs();
}
