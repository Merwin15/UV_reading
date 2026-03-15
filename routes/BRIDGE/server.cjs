const http = require('http');

const ESP32_PORT = 8001;  // ESP32 sends to this port
const LARAVEL_URL = 'http://127.0.0.1:8000/api/sensor-data';

const server = http.createServer((req, res) => {
  res.setHeader('Access-Control-Allow-Origin', '*');
  res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
  res.setHeader('Access-Control-Allow-Headers', 'Content-Type');
  res.setHeader('Content-Type', 'application/json');

  if (req.method === 'OPTIONS') {
    res.writeHead(200);
    res.end();
    return;
  }

  if (req.method === 'POST' && req.url === '/api/sensor-data') {
    let body = '';

    req.on('data', chunk => {
      body += chunk.toString();
    });

    req.on('end', () => {
      try {
        const data = JSON.parse(body);
        console.log(`[RECEIVED] UV: ${data.uv_reading}%`);

        // Forward to Laravel
        const options = {
          hostname: '127.0.0.1',
          port: 8000,
          path: '/api/sensor-data',
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Content-Length': Buffer.byteLength(body),
          }
        };

        const req2 = http.request(options, (res2) => {
          let responseData = '';
          res2.on('data', chunk => {
            responseData += chunk;
          });
          res2.on('end', () => {
            console.log(`[SUCCESS] Forwarded to Laravel`);
            res.writeHead(201);
            res.end(JSON.stringify({success: true, message: 'Data saved'}));
          });
        });

        req2.on('error', (error) => {
          console.error(`[ERROR] ${error.message}`);
          res.writeHead(500);
          res.end(JSON.stringify({success: false, error: error.message}));
        });

        req2.write(body);
        req2.end();

      } catch (error) {
        res.writeHead(400);
        res.end(JSON.stringify({success: false, error: 'Invalid JSON'}));
      }
    });
  } else {
    res.writeHead(404);
    res.end(JSON.stringify({success: false, message: 'Not found'}));
  }
});

server.listen(ESP32_PORT, '0.0.0.0', () => {
  console.log(`Bridge server running on port ${ESP32_PORT}`);
  console.log(`Forwarding to: ${LARAVEL_URL}`);
});