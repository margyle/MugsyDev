    //  I am maos.
  //Status: Working. This script scans for an RFID tag and sends the resulting key over serial to the pi 
//Built on top of RFID reader code by Aritro Mukherjee from https://www.hackster.io/Aritro
 

 
#include <SPI.h>
#include <MFRC522.h>
 
#define SS_PIN 9
#define RST_PIN 8
MFRC522 mfrc522(SS_PIN, RST_PIN);   // Create MFRC522 instance.
 
void setup() 
{
  Serial.begin(9600);   // Initiate a serial communication
  SPI.begin();      // Initiate  SPI bus
  mfrc522.PCD_Init();   // Initiate MFRC522
  Serial.println("Mugsy: RFID mugReader");
  Serial.println("Mugsy: Listening");


}
void loop() 
{
  // Look for new cards
  if ( ! mfrc522.PICC_IsNewCardPresent()) 
  {
    return;
  }
  // Select one of the cards
  if ( ! mfrc522.PICC_ReadCardSerial()) 
  {
    return;
  }
  //Show UID on serial monitor
  String content= "";
  byte letter;
  for (byte i = 0; i < mfrc522.uid.size; i++) 
  {
     content.concat(String(mfrc522.uid.uidByte[i] < 0x10 ? " 0" : " "));
     content.concat(String(mfrc522.uid.uidByte[i], HEX));
  }
  //Serial.println();
  content.toUpperCase();
  //send the rfid tag over serial to the Raspberry Pi
  Serial.println(content.substring(1))
  


} 
