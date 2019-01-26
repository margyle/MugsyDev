//need to switch AccelStepper lib as well as integrate into the larger runtime.
//200 steps = one rotation of stepper, 1600 steps = one rotation of pour over cone
//commands are chainable
//format = <CW,1600,60.00> (Direction, Steps, Speed)
//Chained example: <CW,800,60.00><CC,800,60.00><CW,800,60.00><CC,1600,60.00><CW,1600,60.00>
//lots of this is snagged from the awesome serial control tutorial by Robin2 @ http://forum.arduino.cc/index.php?topic=396450.0
#include <Stepper.h>
const int stepsPerRevolution = 200;  
Stepper coneStepper(stepsPerRevolution, 7,6,4,5 );

const byte numChars = 32;
char receivedChars[numChars];
char tempChars[numChars];        // temporary array for use when parsing

// variables to hold the parsed data
char stepperDirection[numChars] = {0};
int totalSteps = 0;
float stepperSpeed = 0.0;
boolean newData = false;

//============

void setup() {
    coneStepper.setSpeed(60);
    Serial.begin(19200);
    delay(500);
    Serial.println("Mugsy Motor Controller: Listening");
    

}

//============

void loop() {
    recvWithStartEndMarkers();
    if (newData == true) {
        strcpy(tempChars, receivedChars);
        parseData();
        moveCone();
        newData = false;
    }
}

//============

void recvWithStartEndMarkers() {
    static boolean recvInProgress = false;
    static byte ndx = 0;
    char startMarker = '<';
    char endMarker = '>';
    char rc;

    while (Serial.available() > 0 && newData == false) {
        rc = Serial.read();

        if (recvInProgress == true) {
            if (rc != endMarker) {
                receivedChars[ndx] = rc;
                ndx++;
                if (ndx >= numChars) {
                    ndx = numChars - 1;
                }
            }
            else {
                receivedChars[ndx] = '\0'; // terminate the string
                recvInProgress = false;
                ndx = 0;
                newData = true;
            }
        }

        else if (rc == startMarker) {
            recvInProgress = true;
        }
    }
}

//============

void parseData() {      // split the data into its parts

    char * strtokIndx; // this is used by strtok() as an index

    strtokIndx = strtok(tempChars,",");      // get the first part - the string
    strcpy(stepperDirection, strtokIndx); // copy it to stepperDirection
 
    strtokIndx = strtok(NULL, ","); // this continues where the previous call left off
    totalSteps = atoi(strtokIndx);     // convert this part to an integer

    strtokIndx = strtok(NULL, ",");
    stepperSpeed = atof(strtokIndx);     // convert this part to a float

}

//============
//serial command format:
///<CW,1600,60.00>
//<CW,800,60.00><CC,800,60.00><CW,800,60.00><CC,1600,60.00><CW,1600,60.00>
void moveCone() {
    Serial.print("Command Recieved: ");
    Serial.println(receivedChars);
    Serial.print("Direction: ");
    Serial.println(stepperDirection);
    Serial.print("Steps: ");
    Serial.println(totalSteps);
    Serial.print("Speed: ");
    Serial.println(stepperSpeed);
    if (strcmp(stepperDirection,"CW")==0){
    coneStepper.step(totalSteps);}
    else {
      coneStepper.step(-totalSteps);
    }


}
