/*
  ESP32 UV Sensor Data Logger - Complete
  Reads UV sensor and sends to Laravel website
*/

#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>

// WiFi Setup
const char* ssid = "WIGGY@ATUGANDBRIDGE";
const char* password = "WALAYPAPASIBERNARD123";

// Server Configuration
const char* serverUrl = "http://192.168.1.100";  // Change to your server IP
const char* apiEndpoint = "/api/sensor-data";

// Sensor Pin
const int uvPin = 34;

// Timing Variables
unsigned long lastSendTime = 0;
const int SEND_INTERVAL = 5000;  // Send every 5 seconds

void setup() {
  Serial.begin(115200);
  delay(1000);
  
  Serial.println("\n\n");
  Serial.println("╔════════════════════════════════════════╗");
  Serial.println("║   UV Sensor with WiFi Upload         ║");
  Serial.println("╚════════════════════════════════════════╝");
  
  // Configure ADC
  analogReadResolution(12);
  Serial.println("✓ GPIO 34 configured as analog sensor");
  
  // Connect to WiFi
  connectToWiFi();
}

void loop() {
  // Check WiFi connection
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("⚠ WiFi disconnected. Reconnecting...");
    connectToWiFi();
  }
  
  // Read and average UV sensor
  int total = 0;
  for (int i = 0; i < 10; i++) {
    total += analogRead(uvPin);
    delay(5);
  }

  int raw = total / 10;

  // Convert raw (0–4095) to 0–100 scale
  int uvReading = map(raw, 0, 4095, 0, 100);

  // Hard limit safety
  uvReading = constrain(uvReading, 0, 100);

  Serial.print("[SENSOR] Raw: ");
  Serial.print(raw);
  Serial.print(" | UV: ");
  Serial.print(uvReading);
  Serial.println("%");

  // Send to server every SEND_INTERVAL
  if (millis() - lastSendTime >= SEND_INTERVAL) {
    sendToServer(uvReading);
    lastSendTime = millis();
  }

  delay(1000);  // Wait 1 second before next read
}

// Connect to WiFi
void connectToWiFi() {
  Serial.println();
  Serial.print("Connecting to: ");
  Serial.println(ssid);
  
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);
  
  int attempts = 0;
  while (WiFi.status() != WL_CONNECTED && attempts < 40) {
    delay(250);
    Serial.print(".");
    attempts++;
  }
  
  Serial.println();
  if (WiFi.status() == WL_CONNECTED) {
    Serial.print("✓ WiFi connected! IP: ");
    Serial.println(WiFi.localIP());
  } else {
    Serial.println("✗ WiFi connection failed. Check SSID/password.");
  }
}

// Send UV reading to server
void sendToServer(int uvValue) {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("✗ Cannot send - WiFi not connected");
    return;
  }
  
  HTTPClient http;
  String fullUrl = String(serverUrl) + apiEndpoint;
  
  Serial.print("[SEND] ");
  Serial.println(fullUrl);
  
  http.setTimeout(5000);
  http.setConnectTimeout(5000);
  http.begin(fullUrl);
  http.addHeader("Content-Type", "application/json");
  
  // Create JSON payload
  StaticJsonDocument<200> doc;
  doc["uv_reading"] = uvValue;
  
  String jsonString;
  serializeJson(doc, jsonString);
  
  Serial.print("[JSON] ");
  Serial.println(jsonString);
  
  // Send POST request
  int httpCode = http.POST(jsonString);
  
  Serial.print("[RESPONSE] HTTP ");
  Serial.println(httpCode);
  
  if (httpCode == 201) {
    Serial.println("✓ Data sent successfully!");
  } else if (httpCode > 0) {
    Serial.print("⚠ Error: ");
    Serial.println(httpCode);
  } else {
    Serial.print("✗ Connection error: ");
    Serial.println(http.errorToString(httpCode));
  }
  
  http.end();
}


/*
  SETUP INSTRUCTIONS:

  1. Install Arduino IDE Libraries:
     - Sketch > Include Library > Manage Libraries
     - Search and install:
       * "ArduinoJson" by Benoit Blanchon

  2. Configure ESP32 Board:
     - File > Preferences > Additional board URLs:
       https://dl.espressif.com/dl/package_esp32_index.json
     - Tools > Board > ESP32 > ESP32 Dev Module
     - Tools > Port > Select your COM port

  3. Configure WiFi Credentials:
     - Replace "YOUR_SSID" and "YOUR_PASSWORD" with your actual WiFi credentials

  4. Configure Server Address:
     - Replace "192.168.1.100" with your server's IP address or domain name
     - Make sure the server is accessible from the ESP32's network

  5. Hardware Connections:
     - UV Sensor Signal (out) -> GPIO 35 (ADC1_CH7)
     - UV Sensor VCC -> 3.3V
     - UV Sensor GND -> GND

  6. Upload:
     - Click Upload button
     - Watch Serial Monitor (Tools > Serial Monitor) at 115200 baud

  TESTING:
  - Open Serial Monitor to see debug messages
  - Expose sensor to light to see readings change
  - Check your Laravel app Dashboard to see data being recorded
*/
