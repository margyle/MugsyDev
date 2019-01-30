int enA = 10;
int in1 = 7;
int in2 = 6;
// motor two
int enB = 11;
int in3 = 5;
int in4 = 4;

const byte numChars = 32;
char receivedChars[numChars];
char tempChars[numChars];       

// variables to hold the parsed data
char pourStep[numChars] = {0};
float flowRate = 0.0;
float timeOn = 0.0;
float timeOff = 0.0;
boolean newData = false;

//============

void setup() {
  // set all the motor control pins to outputs
    pinMode(enA, OUTPUT);
    pinMode(enB, OUTPUT);
    pinMode(in1, OUTPUT);
    pinMode(in2, OUTPUT);
    pinMode(in3, OUTPUT);
    pinMode(in4, OUTPUT);
    Serial.begin(19200);
    delay(500);
    Serial.println("Mugsy Water Flow Controller: Listening");
    

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
    strcpy(pourStep, strtokIndx); // copy it to stepperDirection
    strtokIndx = strtok(NULL, ","); // this continues where the previous call left off
    flowRate = atof(strtokIndx);     // convert this part to an float
    
    strtokIndx = strtok(NULL, ","); // this continues where the previous call left off
    timeOn = atof(strtokIndx);     // convert this part to an float

    strtokIndx = strtok(NULL, ",");
    timeOff = atof(strtokIndx);     // convert this part to a float

}

//============

void pumpControl(){
      //pumpControl
      digitalWrite(in1, LOW);
      digitalWrite(in2, HIGH);
      analogWrite(enA, 255);
      // turn on motor B
      digitalWrite(in3, LOW);
      digitalWrite(in4, HIGH);
      analogWrite(enB, 255);
      delay(5000);
    
      // now turn off motors
      digitalWrite(in1, LOW);
      digitalWrite(in2, LOW); 
      digitalWrite(in3, LOW);
      digitalWrite(in4, LOW);
      delay(5000);

}
//============
//command format: <Bloom,4.1,3,2><2,4.1,4,2><3,4.1,5,1><4,4.1,3,5><Final,4.1,4,2>
void moveCone() {
              //pumpControl
    Serial.print("Command Received: ");
    Serial.println(receivedChars);
    Serial.print("Step: ");
    Serial.println(pourStep);
    Serial.print("Flow Rate: ");
    Serial.println(flowRate);
    Serial.print("TimeOn: ");
    Serial.println(timeOn);
    Serial.print("TimeOff: ");
    Serial.println(timeOff);
      {
     digitalWrite(in1, LOW);
      digitalWrite(in2, HIGH);
      analogWrite(enA, 255);
      // turn on motor B
      digitalWrite(in3, LOW);
      digitalWrite(in4, HIGH);
      analogWrite(enB, 255);
      delay(timeOn*1000);
    
      // now turn off motors
      digitalWrite(in1, LOW);
      digitalWrite(in2, LOW); 
      digitalWrite(in3, LOW);
      digitalWrite(in4, LOW);
      delay(timeOff*1000);
      }

      Serial.println("Step Completed");
      Serial.println("*************************");

}
