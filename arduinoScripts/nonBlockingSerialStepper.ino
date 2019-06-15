
#include <AccelStepper.h>
// Define cone stepper
AccelStepper stepper1; // Defaults to AccelStepper::FULL4WIRE (4 pins) on 2, 3, 4, 5

//define waterPump
//pump running off relay while testing non-blocking
const int channel1 = 7;
int x = 0;
unsigned long previousMillis_1 = 0; 
long channel1_interval = 0;
int channel1_state = LOW;

//set up serial listeners
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
    pinMode(channel1, OUTPUT);    
    stepper1.setMaxSpeed(200.0);
    stepper1.setAcceleration(300.0);
    Serial.begin(19200);
    Serial.println("Mugsy Motor Controller: Listening");
    }

//============

void loop() {
    unsigned long currentMillis_1 = millis();
    unsigned long currentMillis_stepper = millis();
    
    if (stepper1.distanceToGo() == 0 ) {
        if (newData == true) {
          strcpy(tempChars, receivedChars);
          parseData();
          moveCone();
          newData = false;
    }
    }

    //serial listener
    recvWithStartEndMarkers();
    if (stepper1.distanceToGo() == 0 ) {
        if (newData == true) {
          strcpy(tempChars, receivedChars);
          parseData();
          moveCone();
          newData = false;
    }
    }

//water pump relay
    if(channel1_interval!=0 && stepper1.distanceToGo() != 0 ){
    if (currentMillis_1 - previousMillis_1 >= channel1_interval) {
        // save the last time you blinked the LED
        previousMillis_1 = currentMillis_1;
    //if the LED is off turn it on and vice-versa:
        if (channel1_state == LOW) {
        channel1_state = HIGH;
        } else {
        channel1_state = LOW;
        }
        // set the LED with the ledState of the variable:
        digitalWrite(channel1, channel1_state);
    }   
    }

  
  stepper1.run();
//  Serial.println(stepper1.currentPosition()); 
//  Serial.println(".");
//  Serial.println(stepper1.distanceToGo());



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
    //get pump relay timing
    strtokIndx = strtok(NULL, ","); // this continues where the previous call left off
    channel1_interval = atoi(strtokIndx);     // convert this part to an integer

}

//============
//serial command format:
///<CW,400,60.00><CC,400,60.00>
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
    Serial.print("Water Pump Interval: ");
    Serial.println(channel1_interval);
  

    if (strcmp(stepperDirection,"CW")!=0){
    stepper1.move(totalSteps);}
    else {
    stepper1.move(-totalSteps);;
    }


}



