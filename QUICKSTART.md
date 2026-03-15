# ⚡ UV Monitoring System - Quick Start Guide

## What Was Created ✅

Your UV monitoring system is now complete! Here's what's included:

### 1. **ESP32 Firmware** (`esp32_uv_sensor.ino`)
- Reads UV sensor connected to GPIO 35
- Sends data to your Laravel API every 30 seconds
- Handles WiFi connection automatically
- Includes debug output to Serial Monitor

### 2. **Laravel Backend API** (Already Configured)
- `POST /api/sensor-data` - Receives UV readings from ESP32
- `GET /api/dashboard-data` - Provides dashboard statistics
- SensorReading model with database migrations
- Automatic IP logging for each reading

### 3. **Frontend Dashboard** (Ready to Use)
- **UV Reading History** - Shows chart and data table with stats
- **Recent Readings** - Displays latest data with color-coded levels
- Real-time updates every 10 seconds
- Responsive design for mobile/desktop
- Dark mode support

### 4. **Testing Tools**
- `sensor-simulator.php` - Generate sample data without hardware

---

## 🚀 Quick Setup (5 Minutes)

### Step 1: Prepare Hardware
```
UV Sensor (ML8511/GUVA-S12SD)
├── VCC → 3.3V pin on ESP32
├── GND → GND pin on ESP32
└── OUT → GPIO 35 (ADC1_CH7) on ESP32
```

### Step 2: Flash ESP32 Code
1. Download Arduino IDE: https://www.arduino.cc/en/software
2. Add ESP32 board support:
   - File > Preferences
   - Paste: `https://dl.espressif.com/dl/package_esp32_index.json`
   - Tools > Boards Manager > Search "ESP32" > Install
3. Install ArduinoJson library (Sketch > Include Library > Manage Libraries)
4. Edit `esp32_uv_sensor.ino`:
   - Line 18: Change `YOUR_SSID` to your WiFi name
   - Line 19: Change `YOUR_PASSWORD` to your WiFi password
   - Line 22: Change IP to your computer's IP (use `ipconfig` on Windows)
5. Upload: Select COM port > Click Upload

### Step 3: Start Laravel Server
```bash
php artisan migrate                    # Set up database
php artisan serve                      # Start backend (port 8000)
npm run dev                           # Start frontend dev server
```

### Step 4: Access Dashboard
- Open: http://localhost:8000
- Login with your credentials
- Click "UV Reading History" or "Statistics"
- Watch data appear in real-time!

---

## 🧪 Testing Without Hardware

Don't have an ESP32 yet? Test the frontend with sample data:

```bash
# Generate 20 random readings
php sensor-simulator.php

# Generate 50 bulk readings over 24 hours
php sensor-simulator.php --bulk 50

# Keep generating 1 per 10 seconds
php sensor-simulator.php --continuous
```

Then visit your dashboard to see the data!

---

## 📋 File Structure

```
.
├── esp32_uv_sensor.ino                    # Upload this to ESP32
├── sensor-simulator.php                   # Test data generator
├── UV_SENSOR_SETUP_GUIDE.md              # Detailed setup guide
├── app/
│   ├── Http/Controllers/Api/
│   │   └── SensorController.php          # API endpoints
│   └── Models/
│       └── SensorReading.php             # Data model
├── routes/
│   ├── api.php                           # API routes
│   └── web.php                           # Web routes (updated)
├── resources/views/dashboard/
│   ├── uv-history.blade.php             # History view (updated)
│   ├── recent-readings.blade.php        # Recent view (updated)
│   └── dashboard.blade.php               # Main layout
└── database/migrations/
    └── 2026_02_09_134634_create_sensor_readings_table.php
```

---

## 🔧 Configuration

### ESP32 Settings
Edit `esp32_uv_sensor.ino` lines 18-25:
```cpp
const char* ssid = "YOUR_SSID";              // WiFi network
const char* password = "YOUR_PASSWORD";      // WiFi password
const char* serverUrl = "http://192.168.1.100";  // Your server IP
const int READING_INTERVAL = 5000;           // Read every 5 sec
const int SEND_INTERVAL = 30000;             // Send every 30 sec
```

### Find Your Server IP
- **Windows**: Open Command Prompt, type `ipconfig`
- Look for "IPv4 Address" (e.g., 192.168.1.100)
- Use this in `serverUrl`

---

## 📊 Dashboard Features

### UV Reading History Page
- **Current Level** - Latest UV reading
- **Today's Average** - Average UV for the day
- **Total Readings** - Number of records stored
- **Critical Readings** - Count of low readings
- **Trend Chart** - Line graph of recent readings
- **Data Table** - Detailed readout with timestamps

### Recent Readings Page
- **Latest Value** - Current UV level
- **Health Status** - Visual indicator (Safe/Moderate/High/Extreme)
- **Recent List** - Last 10 readings with progress bars
- **Statistics** - Highest, lowest, average readings

---

## ✅ Verification Checklist

- [ ] ESP32 connected to UV sensor
- [ ] Arduino IDE installed with ESP32 board
- [ ] WiFi credentials updated in code
- [ ] Server IP updated in code
- [ ] Code uploaded to ESP32
- [ ] Serial Monitor shows "WiFi connected" message
- [ ] Server IP is pingable from another device
- [ ] Laravel database migrated (`php artisan migrate`)
- [ ] Dashboard server running (`php artisan serve`)
- [ ] Frontend accessible at http://localhost:8000
- [ ] API endpoint returns data: http://localhost:8000/api/dashboard-data

---

## 🐛 Troubleshooting

| Problem | Solution |
|---------|----------|
| **Serial Monitor shows no output** | Check COM port in Tools > Port, restart IDE |
| **WiFi connection fails** | Verify SSID/password, check 2.4GHz network |
| **Can't reach server from ESP32** | Check server IP with `ipconfig`, ensure same network |
| **No data on dashboard** | Check API endpoint in browser: `/api/dashboard-data` |
| **HTTP 404 error from ESP32** | Verify endpoint path: `/api/sensor-data` |
| **HTTP 500 error** | Check Laravel logs: `storage/logs/laravel.log` |

---

## 📖 More Information

For detailed setup, hardware info, and API docs:
→ See **UV_SENSOR_SETUP_GUIDE.md**

---

## 🎯 Next Steps

1. ✅ Flash your ESP32
2. ✅ Expose sensor to UV light
3. ✅ Watch readings appear on dashboard
4. ✅ Set up cron job for data backup (optional)
5. ✅ Deploy to production (optional)

---

## 💡 Pro Tips

- **Reduce Network Load**: Increase `SEND_INTERVAL` to 60000 (1 minute)
- **Better Accuracy**: Calibrate sensor against standard UV meter
- **Data Storage**: Database will store all readings indefinitely
- **Mobile Access**: Use your server's public IP for remote access
- **Performance**: Add database index: `CREATE INDEX idx_created ON sensor_readings(created_at);`

---

## 🎉 You're All Set!

Your UV monitoring system is ready to go! Head to your dashboard and start monitoring UV levels in real-time.

**Questions?** Check UV_SENSOR_SETUP_GUIDE.md for detailed documentation.

---

**Last Updated**: March 2, 2026  
**Status**: ✅ Production Ready
