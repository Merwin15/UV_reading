from flask import Flask, request, jsonify, render_template_string
import json
from datetime import datetime
from threading import Thread
import requests

app = Flask(__name__)

# Store UV readings in memory
uv_readings = []
current_uv = 0
last_update = None

HTML_DASHBOARD = '''
<!DOCTYPE html>
<html>
<head>
    <title>UV Sensor Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 500px;
            width: 100%;
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        
        .uv-display {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            color: white;
            margin-bottom: 20px;
        }
        
        .uv-value {
            font-size: 3.5em;
            font-weight: bold;
            margin: 20px 0;
        }
        
        .uv-label {
            font-size: 1.2em;
            opacity: 0.9;
        }
        
        .status {
            text-align: center;
            padding: 15px;
            background: #f0f0f0;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .status.online {
            background: #d4edda;
            color: #155724;
        }
        
        .status.offline {
            background: #f8d7da;
            color: #721c24;
        }
        
        .readings {
            background: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
        }
        
        .readings h3 {
            margin-bottom: 15px;
            color: #333;
        }
        
        .reading-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        
        .reading-item:last-child {
            border-bottom: none;
        }
        
        .refresh-btn {
            width: 100%;
            padding: 10px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            margin-top: 20px;
        }
        
        .refresh-btn:hover {
            background: #764ba2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>☀️ UV Sensor Monitor</h1>
        
        <div class="uv-display">
            <div class="uv-label">Current UV Index</div>
            <div class="uv-value" id="uvValue">--</div>
        </div>
        
        <div class="status" id="status">
            Loading...
        </div>
        
        <div class="readings">
            <h3>Recent Readings</h3>
            <div id="readingsList">
                <p style="color: #999;">No readings yet...</p>
            </div>
        </div>
        
        <button class="refresh-btn" onclick="refreshData()">Refresh</button>
    </div>
    
    <script>
        function refreshData() {
            fetch('/api/current')
                .then(r => r.json())
                .then(data => {
                    document.getElementById('uvValue').textContent = data.current_uv || '--';
                    
                    const status = document.getElementById('status');
                    if (data.last_update) {
                        status.textContent = '🟢 Online - Last update: ' + data.last_update;
                        status.className = 'status online';
                    } else {
                        status.textContent = '🔴 Offline - Waiting for data...';
                        status.className = 'status offline';
                    }
                    
                    const list = document.getElementById('readingsList');
                    if (data.readings.length > 0) {
                        list.innerHTML = data.readings.map(r => 
                            `<div class="reading-item">
                                <span>${r.value}%</span>
                                <span style="color: #999; font-size: 0.9em;">${r.time}</span>
                            </div>`
                        ).join('');
                    }
                });
        }
        
        // Auto-refresh every 2 seconds
        refreshData();
        setInterval(refreshData, 2000);
    </script>
</body>
</html>
'''

@app.route('/')
def dashboard():
    return render_template_string(HTML_DASHBOARD)

@app.route('/api/sensor-data', methods=['POST'])
def receive_sensor_data():
    global current_uv, last_update
    
    try:
        data = request.get_json()
        uv_value = data.get('uv_reading')
        
        if uv_value is None:
            return jsonify({'success': False, 'error': 'Missing uv_reading'}), 400
        
        # Store in memory
        current_uv = uv_value
        last_update = datetime.now().strftime('%H:%M:%S')
        
        # Add to readings list (keep last 20)
        uv_readings.insert(0, {
            'value': uv_value,
            'time': last_update
        })
        if len(uv_readings) > 20:
            uv_readings.pop()
        
        print(f"[RECEIVED] UV: {uv_value}% at {last_update}")
        
        # Try to forward to Laravel (non-blocking)
        Thread(target=forward_to_laravel, args=(uv_value,)).start()
        
        return jsonify({
            'success': True,
            'message': 'Data received',
            'uv_reading': uv_value
        }), 201
        
    except Exception as e:
        print(f"[ERROR] {str(e)}")
        return jsonify({'success': False, 'error': str(e)}), 500

@app.route('/api/current')
def get_current():
    return jsonify({
        'current_uv': current_uv,
        'last_update': last_update,
        'readings': uv_readings
    })

def forward_to_laravel(uv_value):
    try:
        response = requests.post(
            'http://127.0.0.1:8000/api/sensor-data',
            json={'uv_reading': uv_value},
            timeout=5
        )
        if response.status_code in [200, 201]:
            print(f"[SUCCESS] Forwarded to Laravel")
        else:
            print(f"[WARNING] Laravel returned {response.status_code}")
    except Exception as e:
        print(f"[INFO] Could not reach Laravel: {str(e)}")

if __name__ == '__main__':
    print("=" * 50)
    print("UV Sensor Server Starting...")
    print("=" * 50)
    print()
    print("📊 Dashboard: http://192.168.254.106:8001")
    print("📡 API Endpoint: http://192.168.254.106:8001/api/sensor-data")
    print()
    print("Waiting for ESP32 data...")
    print()
    
    app.run(host='127.0.0.1', port=5000, debug=False)