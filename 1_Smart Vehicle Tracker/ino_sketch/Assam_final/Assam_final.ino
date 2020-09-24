/*LIST OF FEATURES THAT WORK
 ***************************
 * Collect the following data from sensors and upload it into a Database in a local network or in a remote network using ngrock using nodeMCU's WiFi
 * List of data obtained: Lattitude, Longitude, Speed(Calculated), Time, Direction(Calculated), Temperature & Altitude
 * Display lattitude, longitude, speed and temperature in LCD Display to check if everything is working correctly
 * 
 * LIST OF FEATURES THAT DOESNT WORK
 ************************************
 * Connectivity using GSM module
 * 
 * TO-DO
 ******* 
 * Fix bugs in the GSM PART
 * 
 * CONNECTIONS
 * ***********
 * Module->NodeMCU
 * GSM RX,TX->D10, D9
 * GPS RX,TX->D8, D7
 * MPU6050 SCL,SDA->D1, D2
 * LCD SCL,SDA-> D1, D2(Optional)
*/


//change GPIO numbers

#include <MPU6050.h>
#include <gprs.h>
#include <TinyGPS++.h>
#include <LiquidCrystal_I2C.h>
#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <ESP8266WebServer.h>
#include <ESP8266mDNS.h>
#include <SoftwareSerial.h>
#include <SPI.h>
#include <Wire.h> 

const char* ssid = "ACT108374467862";   //replace this with ssid of hotspot (comment this if using GPRS)
const char* password = "16047668";      //replace this with password of hotspot (comment this if using GPRS)

char server[] = "192.168.0.105";    // change the ip address accordingly...if using ngrock use that ip

LiquidCrystal_I2C lcd(0x27,16,2);  //default address x27
MPU6050 mpu;                       //default address x68
TinyGPSPlus gps;

static const int RXPin = D7, TXPin = D8;
static const uint32_t GPSBaud = 4800;
SoftwareSerial ss(RXPin, TXPin);

SoftwareSerial myserial(D9, D10);

int SCL_PIN=D1;
int SDA_PIN=D2;

float temp = mpu.readTemperature();
unsigned long timer = 0;
float timeStep = 0.01;
float yaw = 0;
String vehicledirection;

WiFiClient client;

void setup() {
  
lcd.begin();
lcd.backlight();

ss.begin(GPSBaud);

Serial.begin(115200);
myserial.begin(9600);

Serial.println("Initialize MPU6050");

while(!mpu.beginSoftwareI2C(SCL_PIN,SDA_PIN,MPU6050_SCALE_2000DPS, MPU6050_RANGE_2G))
{
  Serial.println("Could not find a valid MPU6050 sensor, check wiring!");
  delay(500);
}
mpu.calibrateGyro();
mpu.setThreshold(3);

while (WiFi.status() != WL_CONNECTED) {
  delay(500);
  Serial.print(".");
}
Serial.println("");
Serial.println("WiFi connected");

Serial.println("Server started");
Serial.print(WiFi.localIP());
delay(1000);
Serial.println("connecting...");
}



void loop() {
  
  while (ss.available() > 0)
    if (gps.encode(ss.read()))
    {
      Serial.println("Connected to GPS satellite");
      smartDelay(1000);
    }
    
  

  if (millis() > 5000 && gps.charsProcessed() < 10)
  {
    Serial.println("No GPS detected: check wiring.");
    while(true);
  }

  displayInfoLCD();   //print in LCD
  insertintoDB();    // insert data into DB
}

void displayInfoLCD()
{
  if (gps.location.isValid())
  {
    lcd.setCursor(0,0);
    lcd.print(gps.location.lat(), 6);
    lcd.print(",");
    lcd.print(gps.location.lng(), 6);
  }
  else
  {
    lcd.setCursor(0,0);
    lcd.print("INVALID");
  }

  
  lcd.setCursor(0,1);
  lcd.print("kmph:");
  lcd.print(gps.speed.kmph());
  lcd.setCursor(0,8);
  lcd.print("|");
  lcd.setCursor(0,9);
  lcd.print(temp); 
  lcd.print("*C");

  delay(1000);
}
void yawcalc()
{
  Vector norm = mpu.readNormalizeGyro();
  yaw = yaw + norm.ZAxis * timeStep;
  delay((timeStep*1000) - (millis() - timer));
}
void directioncalc()
{
   if (yaw >= 0 && yaw <= 44){
    vehicledirection="N";
    }
   if (yaw>=45 && yaw<=89){
    vehicledirection="NE";
    }
   if (yaw>=90 && yaw<=134){
    vehicledirection="E";
    }
   if (yaw>=135 && yaw<=179){
    vehicledirection="SE";
    }
   if (yaw>=180 && yaw<=224){
    vehicledirection="S";
    }
   if (yaw>=225 && yaw<=269){
    vehicledirection="SW";
    }
   if (yaw>=270 && yaw<=314){
    vehicledirection="W";
    }
   if (yaw>=315 && yaw<=359){
    vehicledirection="NW";
    }   
}

/*
lati =  gps.location.lat()
longi = gps.location.lng()
speedd = gps.speed.kmph()
timee = DateTime(gps.date, gps.time);
alti = gps.altitude.meters()
direction = vehicledirection
Temp = temp
*/

void insertintoDB()   
 {
   if (client.connect(server, 80)) {
    Serial.println("connected");
    // Make a HTTP request:
    Serial.print("GET /Assamhack/dht.php?lat=");  // foldername in htdocs 
    client.print("GET /Assamhack/dht.php?lat=");     
    Serial.println(gps.location.lat());
    client.print(gps.location.lat());
    client.print("&longitude=");
    Serial.println("&longitude=");
    client.print(gps.location.lng());
    Serial.println(gps.location.lng());
    client.print("&speed=");
    Serial.println("&speed=");
    client.print(gps.speed.kmph());
    Serial.println(gps.speed.kmph());
    client.print("&altitude=");
    Serial.println("&altitude=");
    client.print(gps.altitude.meters());
    Serial.println(gps.altitude.meters());
    client.print("&temperature=");
    Serial.println("&temperature=");
    client.print(temp);
    Serial.println(temp);
    client.print("&direction=");
    Serial.println("&direction=");
    client.print(vehicledirection);
    Serial.println(vehicledirection);
    client.print(" ");      
    client.print("HTTP/1.1");
    client.println();
    client.println("Host: 192.168.0.105");   // change the ip address accordingly...if using ngrock use that ip
    client.println("Connection: close");
    client.println();
  } else {

    Serial.println("connection failed");
  }
 }



static void smartDelay(unsigned long ms)
{
  unsigned long start = millis();
  do 
  {
    while (ss.available())
      gps.encode(ss.read());
  } while (millis() - start < ms);
}

static void DateTime(TinyGPSDate &d, TinyGPSTime &t)
{
  if (!d.isValid())
  {
    Serial.print("********** ");
  }
  else
  {
    char sz[32];
    sprintf(sz, "%02d/%02d/%02d ", d.month(), d.day(), d.year());
    Serial.print(sz);
  }
  
  if (!t.isValid())
  {
    Serial.print("******** ");
  }
  else
  {
    char sz[32];
    sprintf(sz, "%02d:%02d:%02d ", t.hour(), t.minute(), t.second());
    Serial.print(sz);
  }

  printInt(d.age(), d.isValid(), 5);
  smartDelay(0);
}

static void printStr(const char *str, int len)
{
  int slen = strlen(str);
  for (int i=0; i<len; ++i)
    Serial.print(i<slen ? str[i] : ' ');
  smartDelay(0);
}

static void printInt(unsigned long val, bool valid, int len)
{
  char sz[32] = "*****************";
  if (valid)
    sprintf(sz, "%ld", val);
  sz[len] = 0;
  for (int i=strlen(sz); i<len; ++i)
    sz[i] = ' ';
  if (len > 0) 
    sz[len-1] = ' ';
  Serial.print(sz);
  smartDelay(0);
}

  
  
