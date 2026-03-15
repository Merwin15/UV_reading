import serial

try:
    ser = serial.Serial('COM5', 115200, timeout=1)
    print("Connected to COM5!")
    
    for i in range(10):
        if ser.in_waiting > 0:
            line = ser.readline().decode('utf-8')
            print(line)
    
    ser.close()
except Exception as e:
    print(f"Error: {e}")