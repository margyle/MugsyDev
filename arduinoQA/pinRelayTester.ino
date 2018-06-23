
/*
Mugsy Pin and Relay Tester
Pin 12 can be replaced with any other digital pin, just make sure to also update the pin# in the void loop()
Wiring: Connect digital pin 12 to the S pin on relay
        Connect 3V3 pin to + pin on relay
        Connect GND pin to - pin on relay
 */

void setup() {
  // initialize  pin 12
  pinMode(12, OUTPUT);
}

void loop() {
  digitalWrite(12, HIGH);   // turn on relay
  delay(1000);              // wait
  digitalWrite(12, LOW);    // turn off relay
  delay(500);              // wait 
}
