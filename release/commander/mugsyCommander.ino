//--------------------------
//Library Includes
#include <AccelStepper.h>
#include <OneWire.h>
#include <DallasTemperature.h>
//--------------------------


//--------------------------
//Temp sensor
#define ONE_WIRE_BUS 2
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);
int deviceCount = 0;
float temp1;
float temp2;
float averageTemp;
//--------------------------



//--------------------------
//weight sensor
#include "HX711.h"
#define DOUT  A0
#define CLK  A1
#define calibration_factor 246000 
HX711 scale;
//--------------------------



//--------------------------
// Define cone stepper
#define dirPin 5
#define stepPin 4
#define motorInterfaceType 1

AccelStepper stepper1 = AccelStepper(motorInterfaceType, stepPin, dirPin);
//--------------------------



//--------------------------
//define waterPump
const int channel1 = 7;
int x = 0;
unsigned long previousMillis_1 = 0; 
long channel1_interval = 0;
int channel1_state = LOW;
//--------------------------



//--------------------------
//set up serial listeners
const byte numChars = 32;
char receivedChars[numChars];
// temporary array for use when parsing
char tempChars[numChars];        
//--------------------------



//--------------------------
// variables to hold the parsed data
char queryType[numChars] = {0};
int stepperDirection = 0;
int totalSteps = 0;
float stepperSpeed = 0.0;
boolean newData = false;
//--------------------------



//--------------------------
//weight variables
float weight = 0.00;
//--------------------------



//--------------------------
//queryTypes
char moveQuery[] ="M";
char tempQuery[] ="T";
char weightQuery[] ="W";

//--------------------------



//-------------------------- START SETUP
void setup() {
    pinMode(channel1, OUTPUT);
    
    //set up temp sensors
    sensors.begin();   
    deviceCount = sensors.getDeviceCount();

    //set up scale
    scale.begin(DOUT, CLK);
    scale.set_scale(calibration_factor); 
    //Assuming there is no weight on the scale at start up, reset the scale to 0 
    scale.tare();
    
    //set up stepper
    stepper1.setMaxSpeed(400.0);
    stepper1.setAcceleration(500.0);
    stepper1.setEnablePin(A3); //v8 of brainBoard PCB
    stepper1.setPinsInverted(false, false, true);
    
    //let Mugsy know system is online
    Serial.begin(19200);
    Serial.println("200");
    Serial.println("Mugsy Commander: Listening");
    }

//-------------------------- END SETUP



//-------------------------- START LOOP
void loop() {
  unsigned long currentMillis_1 = millis();
  unsigned long currentMillis_stepper = millis();
  
  if (stepper1.distanceToGo() == 0) {
    stepper1.disableOutputs(); //disable stepper to save power
    if (newData == true) {
      strcpy(tempChars, receivedChars);
      parseData();
      newData = false;
   }
   }

//serial listener
recvWithStartEndMarkers();

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


 //stepper1.run();
 stepper1.setSpeed(stepperSpeed);
 stepper1.runSpeedToPosition();
}

//-------------------------- END LOOP



//-------------------------- START recvWithStartEndMarkers
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

//-------------------------- END recvWithStartEndMarkers



//-------------------------- START parseData 
// split the data into its parts
void parseData() {      
  char * strtokIndx; 

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
    
 //if requesting cone movement
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
//-------------------------- END parseData




//-------------------------- START getTemp
void getTemp(){
   sensors.requestTemperatures(); 
   temp1 = sensors.getTempFByIndex(0);
//   temp2 = sensors.getTempCByIndex(1);
//   averageTemp = ((temp1 + temp2)/2); 
//   Serial.println(averageTemp);

//testing single temp sensor: 
    Serial.print("Temperature is: ");
    Serial.println(temp1);

}
//--------------------------  END getTemp



//-------------------------- START moveCone
//move cone command structure 
//<M,0,800,60.00> <M,Direction, Steps, Speed> 
//For direction, 0=ClockWise, 1=CounterClockwise

void moveCone() {
    stepper1.enableOutputs(); 
    if (stepperDirection == 0){
    stepper1.move(totalSteps);}
    else {
    stepper1.move(-totalSteps);;
    }

}
//-------------------------- END moveCone



//-------------------------- START coneResponse
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
}

//-------------------------- END coneResponse



//-------------------------- START getWeight
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
//-------------------------- END get Weight
