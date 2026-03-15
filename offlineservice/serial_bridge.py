import serial
import json
import requests
from threading import Thread
import time
import sys

# Serial configuration
SERIAL_PORT = 'COM5'  # Change if needed
BAUD_RATE = 115200

# Server configuration
SERVER_URL = 'http://127.0.0.1:5000/api/sensor-data' # Python server

def send_to_server(uv_value):
    try:
        response = requests.post(
            SERVER_URL,
            json={'uv_reading': uv_value},
            timeout=5
        )
        
        if response.status_code in [200, 201]:
            print(f"[SUCCESS] Sent to server: {uv_value}%")
        else:
            print(f"[WARNING] Server returned {response.status_code}")
    
    except Exception as e:
        print(f"[ERROR] Could not reach server: {str(e)}")

def main():
    print("=" * 60)
    print("ESP32 Serial Bridge")
    print("=" * 60)
    print(f"Serial Port: {SERIAL_PORT}")
    print(f"Baud Rate: {BAUD_RATE}")
    print(f"Server: {SERVER_URL}")
    print()
    
    try:
        print(f"Connecting to {SERIAL_PORT}...")
        ser = serial.Serial(SERIAL_PORT, BAUD_RATE, timeout=1)
        print(f"✓ Connected!")
        print("Waiting for data from ESP32...\n")
        
        while True:
            try:
                if ser.in_waiting > 0:
                    line = ser.readline().decode('utf-8').strip()
                    
                    if line:
                        print(f"[RECEIVED] {line}")
                        
                        try:
                            data = json.loads(line)
                            uv_reading = data.get('uv_reading')
                            
                            if uv_reading is not None:
                                # Send to server in background
                                Thread(target=send_to_server, args=(uv_reading,)).start()
                        
                        except json.JSONDecodeError:
                            print(f"[ERROR] Invalid JSON")
            
            except Exception as e:
                print(f"[ERROR] {str(e)}")
                time.sleep(1)
    
    except serial.SerialException as e:
        print(f"✗ Failed to connect to {SERIAL_PORT}")
        print(f"Error: {e}")
        print()
        print("Troubleshooting:")
        print("1. Make sure ESP32 is connected via USB")
        print("2. Check Device Manager for COM port")
        print("3. Edit this file and change SERIAL_PORT = 'COM5'")
        print("4. Make sure CH340 drivers are installed")
        sys.exit(1)

if __name__ == '__main__':
    main()