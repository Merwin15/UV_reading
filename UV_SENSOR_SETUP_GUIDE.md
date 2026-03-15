# UV Monitoring System - Complete Setup Guide

## Project Overview
This is a complete UV monitoring system that reads UV sensor data from an ESP32 and displays it on a Laravel web dashboard.

### Components:
1. **ESP32 Microcontroller** - Reads UV sensor and sends data via WiFi
2. **Laravel Backend API** - Receives and stores UV readings
3. **Blade Frontend** - Displays real-time UV data with charts

---

## Hardware Setup

### Components Needed:
- **ESP32 Development Board** (ESP32-DEVKIT-V1 or similar)
- **UV Sensor** - One of these options:
  - **ML8511** (Recommended) - Analog sensor, voltage output 0-3.3V
  - **GUVA-S12SD** - Photodiode sensor
  - **VEML6075** - I2C digital sensor
- **USB Cable** - For programming ESP32
- **Jumper Wires**
- **3.3V Power Supply** (if not using USB power)

### Wiring Diagram (for ML8511/GUVA-S12SD):
```
UV Sensor Pin          ESP32 Pin
-----------------------------------
VCC (3.3V)    ------>  3V3
GND           ------>  GND
Signal Output ------>  GPIO 35 (ADC1_CH7)
```

### Sensor Output Reference:
- **ML8511**: 0V = 0 UV Index, 3.3V = ~15 UV Index (linear)
- **GUVA-S12SD**: 0V = 0 UV Index, 3.3V = ~15 UV Index (photodiode)
- Output is scaled to 0-100% in the ESP32 code

---

## ESP32 Code Installation

### 1. Install Arduino IDE:
- Download from: https://www.arduino.cc/en/software
- Install version 2.0 or newer

### 2. Add ESP32 Board to Arduino IDE:
- Go to **File > Preferences**
- In "Additional boards manager URLs", paste:
  ```
  https://dl.espressif.com/dl/package_esp32_index.json
  ```
- Click **OK**
- Go to **Tools > Board > Boards Manager**
- Search for "ESP32" by Espressif Systems
- Click **Install**

### 3. Install Required Libraries:
- Go to **Sketch > Include Library > Manage Libraries**
- Search and install:
  - **ArduinoJson** by Benoit Blanchon
  - (WiFi and HTTPClient are built-in with ESP32 core)

### 4. Configure ESP32 Code:
- Download the `esp32_uv_sensor.ino` file from the project root
- Open it in Arduino IDE
- Edit these lines with your settings:
  ```cpp
  // Line ~18-19: WiFi Settings
  const char* ssid = "YOUR_SSID";           // Your WiFi network name
  const char* password = "YOUR_PASSWORD";   // Your WiFi password

  // Line ~22-25: Server Settings
  const char* serverUrl = "http://192.168.1.100";  // Your server IP or domain
  const int serverPort = 80;
  const char* apiEndpoint = "/api/sensor-data";
  ```

### 5. Select Board and Port:
- Go to **Tools > Board > ESP32 > ESP32 Dev Module**
- Go to **Tools > Port** and select your COM port
- (On Windows, it should appear as "COM3", "COM4", etc.)

### 6. Upload Code:
- Click the **Upload** button (arrow icon)
- Wait for "Leaving... Hard resetting via RTS pin" message
- Open **Tools > Serial Monitor** (set baud to 115200)
- You should see debug messages

### 7. Verify Operation:
Look for these messages in Serial Monitor:
```
================================
ESP32 UV Sensor Logger Started
================================
Connecting to WiFi: YOUR_SSID
WiFi connected! IP: 192.168.1.XXX
UV Sensor - Raw: 2048 | Voltage: 1.65V | UV Reading: 50.00%
Sending UV data to server: http://192.168.1.100/api/sensor-data
HTTP Response Code: 201
✓ Data sent successfully!
```

---

## Laravel Backend Setup

The backend is already configured, but here's what's in place:

### 1. Database Migration:
```bash
# The sensor_readings table is created by migration:
# database/migrations/2026_02_09_134634_create_sensor_readings_table.php
# Fields: id, uv_reading, ip_address, created_at, updated_at
```

### 2. API Endpoints:

**POST /api/sensor-data** (ESP32 sends data here)
- Request body: `{ "uv_reading": 45.5 }`
- Response: `{ "success": true, "data": {...} }`

**GET /api/dashboard-data** (Frontend fetches data from here)
- Returns: Latest reading, average, statistics, recent 20 readings
- Used by Blade views for real-time updates

### 3. Run Migrations:
```bash
php artisan migrate
```

### 4. Database Seeding (Optional):
```bash
php artisan db:seed --class=SensorReadingSeeder
# Seeder creates sample UV readings for testing
```

---

## Frontend Setup

### 1. Dashboard Views:
The frontend is already configured in:
- `resources/views/dashboard/uv-history.blade.php` - Shows history with chart
- `resources/views/dashboard/recent-readings.blade.php` - Shows recent data

### 2. Update Routes (if needed):
In `routes/web.php`, make sure you have:
```php
Route::view('/dashboard', 'dashboard')->middleware(['auth', 'verified']);
```

In `routes/api.php`, routes are already configured:
```php
Route::post('/sensor-data', [SensorController::class, 'store']);
Route::get('/dashboard-data', [SensorController::class, 'getDashboardData']);
```

### 3. Start Development Server:
```bash
# Terminal 1: Start Laravel dev server
php artisan serve

# Terminal 2: Build frontend assets
npm run dev

# Or use Vite
npm run dev
```

### 4. Access Dashboard:
- Open browser: http://localhost:8000
- Login with your credentials
- Go to Dashboard > UV History or Recent Readings
- You should see live data from the ESP32

---

## Network Configuration

### Important: Server IP Address
The ESP32 needs to reach your Laravel server. Make sure:

1. **Local Network Setup** (Recommended for testing):
   - ESP32, computer, and server must be on same WiFi network
   - Find your server's local IP:
     - **Windows**: `ipconfig` in CMD, look for IPv4 Address (e.g., 192.168.1.100)
     - **Linux/Mac**: `ifconfig`, look for inet address
   - Update `serverUrl` in ESP32 code with this IP

2. **Public Domain Setup** (For production):
   - Update `serverUrl` to your domain: `http://yourdomain.com`
   - Make sure port 80 (or 443 for HTTPS) is open
   - Ensure Laravel is accessible from the internet

### Test Connectivity:
From ESP32 Serial Monitor, you should see:
```
Sending UV data to server: http://192.168.1.100/api/sensor-data
HTTP Response Code: 201
✓ Data sent successfully!
```

If you get error codes:
- **-1**: Cannot connect to server (wrong IP/WiFi)
- **404**: Endpoint not found (wrong path)
- **422**: Validation error (wrong JSON format)
- **500**: Server error (check Laravel logs)

---

## Troubleshooting

### ESP32 Won't Connect to WiFi:
1. Check SSID and password are correct
2. Make sure WiFi is 2.4GHz (ESP32 doesn't support 5GHz)
3. Check signal strength
4. Restart router and ESP32

### ESP32 Connects but Can't Send Data:
1. Verify server IP address is correct
2. Ping the server from another device on network: `ping 192.168.1.100`
3. Check Laravel is running: `php artisan serve`
4. Check Laravel logs: `tail -f storage/logs/laravel.log`

### No Data Showing on Dashboard:
1. Check API endpoint: Open browser to `http://localhost:8000/api/dashboard-data`
2. Should return JSON with current_level, avg_today, etc.
3. Verify database has readings: `php artisan tinker` then `App\Models\SensorReading::count()`

### Sensor Readings Are Always 0:
1. Check ADC pin (GPIO 35 should be receiving signal)
2. Connect sensor directly to 3.3V to test max value
3. Connect to GND to test min value
4. Check voltage with multimeter (should vary 0-3.3V)

---

## File Structure

```
.
├── esp32_uv_sensor.ino                           # ESP32 firmware
├── app/
│   ├── Http/Controllers/Api/SensorController.php # API endpoints
│   └── Models/SensorReading.php                  # Database model
├── routes/
│   ├── api.php                                   # API routes
│   └── web.php                                   # Web routes
├── database/
│   ├── migrations/                               # Database tables
│   └── seeders/SensorReadingSeeder.php          # Sample data
├── resources/views/dashboard/
│   ├── uv-history.blade.php                    # History & chart view
│   └── recent-readings.blade.php                # Recent readings view
└── README.md                                     # This file
```

---

## API Documentation

### POST /api/sensor-data
Store UV sensor reading

**Request:**
```json
{
  "uv_reading": 45.5
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Data saved successfully",
  "data": {
    "id": 1,
    "uv_reading": 45.5,
    "ip_address": "192.168.1.50",
    "created_at": "2026-03-02T10:30:00.000000Z",
    "updated_at": "2026-03-02T10:30:00.000000Z"
  }
}
```

**Error Response (422 Unprocessable Entity):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "uv_reading": ["The uv_reading must be a number."]
  }
}
```

---

### GET /api/dashboard-data
Get dashboard statistics

**Response:**
```json
{
  "current_level": 45.5,
  "avg_today": 32.8,
  "total_readings": 156,
  "critical_readings": 12,
  "recent_readings": [
    {
      "id": 1,
      "uv_reading": 45.5,
      "ip_address": "192.168.1.50",
      "created_at": "2026-03-02T10:30:00.000000Z",
      "updated_at": "2026-03-02T10:30:00.000000Z"
    },
    ...
  ],
  "last_update": "10 seconds ago"
}
```

---

## Performance Tips

1. **Reduce API Call Frequency**: 
   - Default is 30 seconds. To change in ESP32 code:
     ```cpp
     const int SEND_INTERVAL = 60000;  // Change to 60 seconds
     ```

2. **Optimize Database**:
   ```sql
   CREATE INDEX idx_sensor_readings_created ON sensor_readings(created_at);
   ```

3. **Enable Query Caching**:
   - Update `.env`: `CACHE_DRIVER=redis`
   - Install Redis and Laravel Cache

---

## Next Steps

1. ✅ Set up hardware (UV sensor + ESP32)
2. ✅ Upload ESP32 firmware
3. ✅ Configure WiFi and server IP
4. ✅ Run Laravel migrations
5. ✅ Start Laravel and frontned servers
6. ✅ Access dashboard at http://localhost:8000
7. ✅ Monitor real-time UV data!

---

## Support & Debugging

For detailed logs, check:
- **ESP32 Logs**: Serial Monitor (Tools > Serial Monitor)
- **Laravel Logs**: `storage/logs/laravel.log`
- **Browser Console**: F12 > Console tab

To enable debug mode in Laravel:
- `.env` file: Set `APP_DEBUG=true`
- View detailed error messages

---

**Last Updated**: March 2, 2026
**Version**: 1.0.0
