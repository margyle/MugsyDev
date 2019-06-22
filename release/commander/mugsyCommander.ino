#include <AccelStepper.h>

//weight sensor
#include "HX711.h"
#define DOUT  A0
#define CLK  A1
#define calibration_factor 246000 
HX711 scale;

// Define cone stepper
AccelStepper stepper1; // Defaults to AccelStepper::FULL4WIRE (4 pins) on 2, 3, 4, 5

//define waterPump
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
char queryType[numChars] = {0};
int stepperDirection = 0;
int totalSteps = 0;
float stepperSpeed = 0.0;
boolean newData = false;

//temperature variables
float temp1 = 0.00;
float temp2 = 0.00;
float combinedTemp = 0.00;
float currentTemp = 0.00;

//weight
float weight = 0.00;

//queryTypes
char moveQuery[] ="M";
char tempQuery[] ="T";
char weightQuery[] ="W";
//(WiFi.SSID() == ReadWifiSSID())


//============

void setup() {
    pinMode(channel1, OUTPUT);   
    scale.begin(DOUT, CLK);
    //set up scale
    scale.set_scale(calibration_factor); 
    scale.tare(); //Assuming there is no weight on the scale at start up, reset the scale to 0 
    //set up stepper
    stepper1.setMaxSpeed(200.0);
    stepper1.setAcceleration(300.0);
    //let Mugsy know system is online
    Serial.begin(19200);
    Serial.println("200");
    Serial.println("Mugsy Commander: Listening");
    //Serial.println("Mugsy Motor Controller: Listening");
    }

//============

void loop() {
    unsigned long currentMillis_1 = millis();
    unsigned long currentMillis_stepper = millis();
    
    if (stepper1.distanceToGo() == 0) {
        if (newData == true) {
          strcpy(tempChars, receivedChars);
          parseData();
          newData = false;

    }
    }

//serial listener
recvWithStartEndMarkers();

//============
//water pump relay, currently running by steps remaining, need to convert to time/ml
if(channel1_interval!=0 && stepper1.distanceToGo() != 0 ){
  if (currentMillis_1 - previousMillis_1 >= channel1_interval) {
    previousMillis_1 = currentMillis_1;
    if (channel1_state == LOW) {
      channel1_state = HIGH;
    } else {
      channel1_state = LOW;
    }
    digitalWrite(channel1, channel1_state);
  }
}

stepper1.run();

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

    //get query type
    strtokIndx = strtok(tempChars,","); // get the first part - the string
    strcpy(queryType, strtokIndx);
    
    //if requesting temp
    if(queryType[0] == tempQuery[0]){
        getTemp();
        newData = false;
     }
     //if requesting weight
     else if(queryType[0] == weightQuery[0]){
      getWeight();
      newData = false;
     }
     
     
    else if (queryType[0] == moveQuery[0]){
    
    //get direction:0=CW,1=CC
    strtokIndx = strtok(NULL, ","); // get the first part - the string
    stepperDirection = atoi(strtokIndx); // copy it to stepperDirection
 
    strtokIndx = strtok(NULL, ","); // this continues where the previous call left off
    totalSteps = atoi(strtokIndx);     // convert this part to an integer

    strtokIndx = strtok(NULL, ",");
    stepperSpeed = atof(strtokIndx);     // convert this part to a float
    //get pump relay timing
    strtokIndx = strtok(NULL, ","); // this continues where the previous call left off
    channel1_interval = atoi(strtokIndx);     // convert this part to an integer
    moveCone();
    coneResponse();
        }
}
//============

//============
void getTemp(){
    //todo need to put sensor pulls back in
    // combinedTemp = temp1 + temp2;
    // currentTemp = combinedTemp/2;
    // Serial.println("Command Recieved: ");
    // Serial.print("Current Temp: ");
    // Serial.println(currentTemp);
     
}
//============

//============

void moveCone() {
    if (stepperDirection == 0){
    stepper1.move(totalSteps);}
    else {
    stepper1.move(-totalSteps);;
    }

}
void coneResponse() {
    Serial.print("Command Recieved: ");
    Serial.println(receivedChars);
    Serial.print("Query Type: ");
    Serial.println(queryType);
    Serial.print("Direction: ");
    Serial.println(stepperDirection);
    Serial.print("Steps: ");
    Serial.println(totalSteps);
    Serial.print("Speed: ");
    Serial.println(stepperSpeed);
//    Serial.print("Water Pump Interval: ");
//    Serial.println(channel1_interval);
//     Serial.print("Debug Comparator: ");
//    Serial.println(stepperDirection == 0);
//    Serial.println(queryType);
//    Serial.println(moveQuery);
}

void getWeight(){
  weight = scale.get_units() * 1000;
  if (weight * weight <= 4.00){
    Serial.println("Coffee Scale: Waiting");
  }else{
      Serial.print("Weight: ");
      Serial.print(weight);
      Serial.println(" grams");
  }
}