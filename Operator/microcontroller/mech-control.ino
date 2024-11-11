#include <CmdMessenger.h>
#include <AccelStepper.h>

// Stepper definitions
#define CONE_STEP_PIN 3
#define CONE_DIR_PIN 2
#define CONE_ENABLE_PIN 4
#define SPOUT_STEP_PIN 6
#define SPOUT_DIR_PIN 5
#define SPOUT_ENABLE_PIN 7

// Microstepping pins
#define M0 11
#define M1 10
#define M2 9

// Limit switch pin
#define LIMIT_SWITCH_PIN 8

AccelStepper coneStepper(AccelStepper::DRIVER, CONE_STEP_PIN, CONE_DIR_PIN);
AccelStepper spoutStepper(AccelStepper::DRIVER, SPOUT_STEP_PIN, SPOUT_DIR_PIN);

const float STEP_ANGLE = 0.9;            // Step angle in degrees
const int MICROSTEPS = 32;               // Microsteps
const float PULLEY_RATIO = 40.0 / 20.0;  // Pulley ratio
const float SPOUT_STEPS_PER_DEGREE = (1.0 / STEP_ANGLE) * MICROSTEPS * PULLEY_RATIO;

// Track zeroing state
bool isZeroed = false;

// Command IDs
enum {
    move_cone,
    cone_done,
    move_spout,
    spout_done,
    move_both,
    both_done,
    zero_spout,  // New command
    zero_done,   // New acknowledgment
    error
};

// Command queue structure
struct Command {
    int commandId;
    long coneSteps;
    long spoutDegrees;
    int coneSpeed;
    int spoutSpeed;
    int direction;
    bool needsAck;
};

Command commandQueue[20];
int queueFront = 0;
int queueRear = 0;

bool isQueueEmpty() {
    return queueFront == queueRear;
}

bool isQueueFull() {
    return (queueRear + 1) % 20 == queueFront;
}

void enqueueCommand(Command cmd) {
    if (!isQueueFull()) {
        commandQueue[queueRear] = cmd;
        queueRear = (queueRear + 1) % 20;
    }
}

Command dequeueCommand() {
    Command cmd = commandQueue[queueFront];
    queueFront = (queueFront + 1) % 20;
    return cmd;
}

// CmdMessenger setup
CmdMessenger c = CmdMessenger(Serial, ',', ';', '/');

void on_zero_spout(void) {
    if (isQueueFull()) {
        c.sendCmd(error, "Queue full");
        return;
    }
    Command cmd = {zero_spout, 0, 0, 0, 0, 0, true};
    enqueueCommand(cmd);
}

void on_move_cone(void) {
    if (isQueueFull()) {
        c.sendCmd(error, "Queue full");
        return;
    }
    long steps = c.readBinArg<long>();
    int speed = c.readBinArg<int>();
    int direction = c.readBinArg<int>();
    Command cmd = {move_cone, steps, 0, speed, 0, direction, true};
    enqueueCommand(cmd);
}

void on_move_spout(void) {
    if (isQueueFull()) {
        c.sendCmd(error, "Queue full");
        return;
    }
    if (!isZeroed) {
        c.sendCmd(error, "Spout not zeroed");
        return;
    }
    long degrees = c.readBinArg<long>();
    int speed = c.readBinArg<int>();
    int direction = c.readBinArg<int>();
    Command cmd = {move_spout, 0, degrees, 0, speed, direction, true};
    enqueueCommand(cmd);
}

void on_move_both(void) {
    if (isQueueFull()) {
        c.sendCmd(error, "Queue full");
        return;
    }
    if (!isZeroed) {
        c.sendCmd(error, "Spout not zeroed");
        return;
    }
    long coneSteps = c.readBinArg<long>();
    int coneSpeed = c.readBinArg<int>();
    long spoutDegrees = c.readBinArg<long>();
    int spoutSpeed = c.readBinArg<int>();
    int direction = c.readBinArg<int>();
    Command cmd = {move_both, coneSteps, spoutDegrees, coneSpeed, spoutSpeed, direction, true};
    enqueueCommand(cmd);
}

void executeCommand(Command cmd) {
    switch (cmd.commandId) {
        case zero_spout:
            zeroSpoutStepper();
            if (cmd.needsAck) {
                c.sendCmd(zero_done);
            }
            break;

        case move_cone:
            coneStepper.setMaxSpeed(abs(cmd.coneSpeed));
            coneStepper.setPinsInverted(cmd.direction == 1, false, true);
            coneStepper.move(cmd.coneSteps);
            if (cmd.needsAck) {
                c.sendCmd(cone_done);
            }
            break;

        case move_spout:
            spoutStepper.setMaxSpeed(abs(cmd.spoutSpeed));
            spoutStepper.setPinsInverted(cmd.direction == 1, false, true);
            spoutStepper.moveTo(cmd.spoutDegrees * SPOUT_STEPS_PER_DEGREE);
            if (cmd.needsAck) {
                c.sendCmd(spout_done);
            }
            break;

        case move_both:
            coneStepper.setMaxSpeed(abs(cmd.coneSpeed));
            spoutStepper.setMaxSpeed(abs(cmd.spoutSpeed));
            coneStepper.setPinsInverted(cmd.direction == 1, false, true);
            spoutStepper.setPinsInverted(cmd.direction == 1, false, true);
            coneStepper.move(cmd.coneSteps);
            spoutStepper.moveTo(cmd.spoutDegrees * SPOUT_STEPS_PER_DEGREE);
            if (cmd.needsAck) {
                c.sendCmd(both_done);
            }
            break;
    }
}

void zeroSpoutStepper() {
    // Enable the motor
    digitalWrite(SPOUT_ENABLE_PIN, LOW);
    spoutStepper.setSpeed(-3000);
    
    while (digitalRead(LIMIT_SWITCH_PIN) == LOW) {
        spoutStepper.runSpeed();
    }

    // Test movement
    long stepsToMove = (long)(44 * SPOUT_STEPS_PER_DEGREE);
    spoutStepper.move(stepsToMove);
    while (spoutStepper.distanceToGo() != 0) {
        spoutStepper.run();
    }

    spoutStepper.setCurrentPosition(0);
    isZeroed = true;
    spoutStepper.disableOutputs();
}

void attach_callbacks(void) {
    c.attach(move_cone, on_move_cone);
    c.attach(move_spout, on_move_spout);
    c.attach(move_both, on_move_both);
    c.attach(zero_spout, on_zero_spout);  // Add this line
}

void setup() {
    Serial.begin(9600);

    // Set up the microstepping pins as outputs and configure for 1/32 microstepping
    pinMode(M0, OUTPUT);
    pinMode(M1, OUTPUT);
    pinMode(M2, OUTPUT);
    digitalWrite(M0, HIGH);
    digitalWrite(M1, HIGH);
    digitalWrite(M2, HIGH);

    pinMode(LIMIT_SWITCH_PIN, INPUT_PULLUP);

    // Configure steppers
    coneStepper.setEnablePin(CONE_ENABLE_PIN);
    spoutStepper.setEnablePin(SPOUT_ENABLE_PIN);
    
    coneStepper.setMaxSpeed(1000);
    coneStepper.setAcceleration(10000);
    spoutStepper.setMaxSpeed(2000);
    spoutStepper.setAcceleration(10000);
    
    coneStepper.setPinsInverted(false, false, true);
    spoutStepper.setPinsInverted(false, false, true);

    coneStepper.disableOutputs();
    spoutStepper.disableOutputs();

    attach_callbacks();
}

void loop() {
    c.feedinSerialData();

    if (!isQueueEmpty() && !coneStepper.isRunning() && !spoutStepper.isRunning()) {
        Command cmd = dequeueCommand();
        executeCommand(cmd);
    }

    if (coneStepper.isRunning()) {
        coneStepper.enableOutputs();
        coneStepper.run();
    } else {
        coneStepper.disableOutputs();
    }

    if (spoutStepper.isRunning()) {
        spoutStepper.enableOutputs();
        spoutStepper.run();
    } else {
        spoutStepper.disableOutputs();
    }
}
