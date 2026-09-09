<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Driver Location App - Army VMS</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- PWA Settings -->
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#0d1b1e">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    
    <style>
        :root {
            --bg-color: #0d1214;
            --surface: #1a2226;
            --surface-glass: rgba(26, 34, 38, 0.7);
            --primary: #00e676; /* Bright neon green */
            --primary-glow: rgba(0, 230, 118, 0.4);
            --danger: #ff3d00;
            --text-main: #f0f4f8;
            --text-muted: #8b9bb4;
            --army-yellow: #ffd700;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Glassmorphism Container */
        .glass-panel {
            background: var(--surface-glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 24px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
        }

        /* ----- LOGIN SCREEN ----- */
        #loginScreen {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            position: relative;
            z-index: 10;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 40px 30px;
            text-align: center;
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .logo-container {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            border: 2px solid var(--army-yellow);
            overflow: hidden;
            box-shadow: 0 0 20px var(--primary-glow);
        }

        .logo-img {
            width: 90%;
            height: 90%;
            object-fit: contain;
        }

        h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        
        p.subtitle {
            color: var(--text-muted);
            margin-bottom: 30px;
            font-size: 0.95rem;
        }

        .input-group {
            margin-bottom: 20px;
            position: relative;
            text-align: left;
        }

        .input-group i {
            position: absolute;
            top: 50%;
            left: 20px;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1.1rem;
        }

        input {
            width: 100%;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            padding: 16px 20px 16px 50px;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 230, 118, 0.2);
        }

        .btn-modern {
            width: 100%;
            background: linear-gradient(135deg, #00b0ff, #00e676);
            color: #000;
            border: none;
            padding: 16px;
            font-size: 1.1rem;
            font-weight: 700;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(0, 230, 118, 0.3);
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 10px;
        }

        .btn-modern:active {
            transform: scale(0.97);
        }

        /* ----- DASHBOARD SCREEN ----- */
        #dashboardScreen {
            display: none;
            flex-direction: column;
            min-height: 100vh;
            padding: 24px;
            position: relative;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            animation: fadeInDown 0.6s ease;
        }

        .user-greeting h2 {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .user-greeting p {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .logout-btn {
            background: rgba(255, 61, 0, 0.1);
            color: var(--danger);
            border: 1px solid rgba(255, 61, 0, 0.2);
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .pulsing-map {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 40px 0;
            position: relative;
        }

        .radar-circle {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: rgba(0, 230, 118, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            box-shadow: 0 0 50px var(--primary-glow);
        }

        /* Pulse animation */
        .radar-circle::before, .radar-circle::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 2px solid var(--primary);
            opacity: 0;
            animation: radarPulse 3s linear infinite;
        }

        .radar-circle::after {
            animation-delay: 1.5s;
        }

        .car-icon {
            font-size: 3.5rem;
            color: var(--primary);
            z-index: 2;
            text-shadow: 0 0 10px var(--primary);
        }

        @keyframes radarPulse {
            0% { transform: scale(0.5); opacity: 0; }
            50% { opacity: 0.5; }
            100% { transform: scale(1.5); opacity: 0; }
        }

        .status-card {
            padding: 20px;
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            animation: fadeInUp 0.8s ease;
        }

        .status-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .status-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .status-label {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-muted);
        }

        .status-value {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .live-tag {
            background: rgba(0, 230, 118, 0.2);
            color: var(--primary);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .live-dot {
            width: 8px;
            height: 8px;
            background: var(--primary);
            border-radius: 50%;
            animation: blink 1s infinite alternate;
        }

        @keyframes blink {
            from { opacity: 0.4; }
            to { opacity: 1; }
        }

        /* Ambient Background Elements */
        .bg-blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(100px);
            z-index: 0;
            opacity: 0.4;
        }
        .blob-1 { top: -10%; left: -10%; width: 300px; height: 300px; background: rgba(0, 176, 255, 0.3); }
        .blob-2 { bottom: -10%; right: -10%; width: 300px; height: 300px; background: rgba(0, 230, 118, 0.2); }

        /* Error Notification */
        .toast {
            position: fixed;
            bottom: -100px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 61, 0, 0.9);
            color: white;
            padding: 12px 24px;
            border-radius: 30px;
            font-weight: 500;
            transition: bottom 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 100;
            box-shadow: 0 10px 30px rgba(255, 61, 0, 0.4);
            white-space: nowrap;
        }
        .toast.show { bottom: 30px; }
        .toast.success {
            background: rgba(0, 230, 118, 0.9);
            box-shadow: 0 10px 30px rgba(0, 230, 118, 0.4);
        }

        .sos-container {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 999999 !important;
            text-align: center;
        }

        .sos-btn {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #ff1744, #d50000);
            border-radius: 50%;
            border: 4px solid rgba(255,255,255,0.3);
            color: white;
            font-size: 1.1rem;
            font-weight: 800;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(255, 23, 68, 0.6);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin: 0 auto;
        }

        .sos-btn:active {
            transform: scale(0.85);
            box-shadow: 0 5px 15px rgba(255, 23, 68, 1);
        }

        .sos-btn i {
            font-size: 1.8rem;
            margin-bottom: 3px;
        }

        .sos-btn::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 2px solid #ff1744;
            animation: sosRipple 2s linear infinite;
        }

        @keyframes sosRipple {
            0% { transform: scale(1); opacity: 0.8; }
            100% { transform: scale(1.6); opacity: 0; }
        }

        .sos-label {
            margin-top: 10px;
            font-weight: 600;
            color: #ff5252;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.7rem;
            text-shadow: 0 0 10px rgba(0,0,0,0.5);
            background: rgba(0,0,0,0.4);
            padding: 2px 8px;
            border-radius: 10px;
        }

        /* Ensure dashboard is scrollable if content overflows */
        #dashboardScreen {
            overflow-y: auto;
            padding-bottom: 50px;
        }

    </style>
</head>
<body>

    <!-- Ambient background -->
    <div class="bg-blob blob-1"></div>
    <div class="bg-blob blob-2"></div>

    <div id="toast" class="toast">Message goes here</div>

    <!-- LOGIN SCREEN -->
    <main id="loginScreen">
        <div class="glass-panel login-card">
            <div class="logo-container">
                <img src="uploads/army_logo.png" alt="Logo" class="logo-img">
            </div>
            <h1>Driver Connect</h1>
            <p class="subtitle">Army Vehicle Management System</p>

            <form id="loginForm">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" id="username" placeholder="Driver ID (Username)" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn-modern">LOG IN</button>
                <button type="button" id="installBtn" class="btn-modern" style="background: rgba(255,255,255,0.1); color: var(--army-yellow); border: 1px solid var(--army-yellow); display:none; margin-top: 15px;">
                    <i class="fas fa-download"></i> INSTALL APP
                </button>
            </form>
        </div>
    </main>
    
    <!-- SOS UI (Outside main screens to avoid flex issues) -->
    <div class="sos-container" id="sosContainer" style="display: none;">
        <div class="sos-btn" id="sosBtn">
            <i class="fas fa-shield-alt"></i>
            <span>SOS</span>
        </div>
        <div class="sos-label">Hold for 2 seconds</div>
    </div>

    <!-- DASHBOARD SCREEN -->
    <main id="dashboardScreen" style="display: none;">
        <div class="top-bar">
            <div class="user-greeting">
                <h2 id="driverName">Driver Name</h2>
                <p>Tracking Active</p>
            </div>
            <button class="logout-btn" id="logoutBtn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </div>

        <div class="pulsing-map">
            <div class="radar-circle">
                <i class="fas fa-truck-monster car-icon"></i>
            </div>
        </div>

        <div class="glass-panel status-card">
            <div class="status-item">
                <div class="status-label"><i class="fas fa-signal"></i> Network</div>
                <div class="status-value text-primary">Connected</div>
            </div>
            <div class="status-item">
                <div class="status-label"><i class="fas fa-satellite-dish"></i> GPS Status</div>
                <div class="status-value live-tag"><div class="live-dot"></div> LIVE</div>
            </div>
            <div class="status-item">
                <div class="status-label"><i class="fas fa-location-arrow"></i> Last Update</div>
                <div class="status-value" id="lastUpdateTime">Just now</div>
            </div>
            <div class="status-item">
                <div class="status-label"><i class="fas fa-map-marker-alt"></i> Coordinates</div>
                <div class="status-value" id="coordsDisplay" style="font-size: 0.9rem; color: var(--text-muted);">Fetching...</div>
            </div>
        </div>
    </main>

    <script>
        // Register Service Worker for PWA
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('sw.js')
                    .then(reg => console.log('Service Worker registered'))
                    .catch(err => console.log('Service Worker failed', err));
            });
        }

        const loginScreen = document.getElementById('loginScreen');
        const dashboardScreen = document.getElementById('dashboardScreen');
        const sosContainer = document.getElementById('sosContainer');
        const toast = document.getElementById('toast');
        const installBtn = document.getElementById('installBtn');
        
        let driverId = null;
        let watchId = null;
        let wakeLock = null;
        let deferredPrompt;
        let db = null;
        let overspeedAlarmActive = false;
        let audioContext = null;

        // Initialize IndexedDB for offline storage
        const dbRequest = indexedDB.open("GPSCacheDB", 1);
        dbRequest.onupgradeneeded = (e) => {
            db = e.target.result;
            if (!db.objectStoreNames.contains("coordinates")) {
                db.createObjectStore("coordinates", { keyPath: "id", autoIncrement: true });
            }
        };
        dbRequest.onsuccess = (e) => { db = e.target.result; syncOfflineData(); };

        // Function to cache location locally
        async function cacheLocation(latitude, longitude, timestamp) {
            if (!db) return;
            const transaction = db.transaction(["coordinates"], "readwrite");
            const store = transaction.objectStore("coordinates");
            store.add({ latitude, longitude, timestamp, driver_id: driverId });
        }

        // Function to sync offline data to server
        async function syncOfflineData() {
            if (!db || !navigator.onLine) return;
            const transaction = db.transaction(["coordinates"], "readwrite");
            const store = transaction.objectStore("coordinates");
            const allRecords = store.getAll();

            allRecords.onsuccess = async () => {
                const records = allRecords.result;
                if (records.length === 0) return;

                showToast(`Syncing ${records.length} offline records...`);
                for (const record of records) {
                    try {
                        const response = await fetch('api/gps_update.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(record)
                        });
                        if (response.ok) {
                            db.transaction(["coordinates"], "readwrite").objectStore("coordinates").delete(record.id);
                        }
                    } catch (err) { break; }
                }
                showToast("Offline sync complete!", "success");
            };
        }

        window.addEventListener('online', syncOfflineData);

        // PWA Install Logic
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            installBtn.style.display = 'block';
        });

        installBtn.addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                if (outcome === 'accepted') installBtn.style.display = 'none';
                deferredPrompt = null;
            }
        });

        // Function to keep screen active (Wake Lock API)
        async function requestWakeLock() {
            try {
                if ('wakeLock' in navigator) {
                    wakeLock = await navigator.wakeLock.request('screen');
                    console.log('Wake Lock isActive');
                    wakeLock.addEventListener('release', () => {
                        console.log('Wake Lock was released');
                    });
                }
            } catch (err) {
                console.error(`${err.name}, ${err.message}`);
            }
        }

        function showToast(msg, type = 'error') {
            toast.textContent = msg;
            toast.className = `toast show ${type}`;
            setTimeout(() => toast.className = 'toast', 3000);
        }

        // Handle Login
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = e.target.querySelector('button');
            const originalText = btn.textContent;
            btn.textContent = 'CONNECTING...';
            btn.disabled = true;

            const u = document.getElementById('username').value;
            const p = document.getElementById('password').value;

            try {
                const res = await fetch('api/login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username: u, password: p })
                });
                
                const data = await res.json();
                
                if (data.success && data.user.role === 'driver') {

                    // Login Success
                    driverId = data.user.username;
                    document.getElementById('driverName').textContent = data.user.full_name || driverId;
                    
                    loginScreen.style.display = 'none';
                    dashboardScreen.style.display = 'flex';
                    
                    // Force the SOS container to show properly
                    sosContainer.style.setProperty('display', 'block', 'important');
                    sosContainer.style.visibility = 'visible';
                    sosContainer.style.opacity = '1';
                    
                    
                    showToast('Connected Successfully!', 'success');
                    requestWakeLock();
                    startTracking();
                } else if (data.success && data.user.role !== 'driver') {
                    showToast('Access denied. Driver account required.');
                } else {
                    showToast(data.message || 'Invalid credentials');
                }
            } catch (err) {
                showToast('Network error');
            } finally {
                btn.textContent = originalText;
                btn.disabled = false;
            }
        });

        // Logout
        document.getElementById('logoutBtn').addEventListener('click', () => {
            if (watchId) navigator.geolocation.clearWatch(watchId);
            if (wakeLock) {
                wakeLock.release();
                wakeLock = null;
            }
            driverId = null;
            dashboardScreen.style.display = 'none';
            sosContainer.style.display = 'none';
            loginScreen.style.display = 'flex';
            document.getElementById('loginForm').reset();
            showToast('Logged out securely', 'success');
        });

        // GPS Tracking Logic using watchPosition for background efficiency
        function startTracking() {
            if (!navigator.geolocation) {
                showToast('GPS not supported on this device');
                return;
            }

            const options = {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            };

            watchId = navigator.geolocation.watchPosition(pos => {
                const lat = pos.coords.latitude;
                const lon = pos.coords.longitude;
                const speedKmh = pos.coords.speed == null ? 0 : Math.max(0, pos.coords.speed * 3.6);
                const ts = new Date().toISOString();
                
                document.getElementById('coordsDisplay').textContent = `${lat.toFixed(6)}, ${lon.toFixed(6)}`;
                const timeString = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit', second: '2-digit'});
                document.getElementById('lastUpdateTime').textContent = timeString;

                // Send to backend
                updateLocationOnServer(lat, lon, ts, speedKmh);

            }, err => {
                showToast('GPS Error: ' + err.message);
            }, options);
        }

        async function updateLocationOnServer(lat, lon, timestamp, speedKmh) {
            const data = {
                driver_id: driverId,
                latitude: lat,
                longitude: lon,
                timestamp: timestamp
                ,speed_kmh: Number(speedKmh.toFixed(2))
            };

            try {
                const response = await fetch('api/gps_update.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                if (result.speed_alert && speedKmh > 50) {
                    showOverspeedAlarm(speedKmh);
                }
                if (!result.success) {
                    console.error('Update failed:', result.message);
                    cacheLocation(lat, lon, timestamp);
                }

                function showOverspeedAlarm(speedKmh) {
                    showToast(`OVERSPEED ALERT: ${speedKmh.toFixed(1)} km/h`, 'error');
                    if (overspeedAlarmActive) return;
                    overspeedAlarmActive = true;
                    audioContext = audioContext || new (window.AudioContext || window.webkitAudioContext)();
                    const oscillator = audioContext.createOscillator();
                    const gain = audioContext.createGain();
                    oscillator.frequency.value = 880;
                    oscillator.connect(gain);
                    gain.connect(audioContext.destination);
                    gain.gain.setValueAtTime(0.25, audioContext.currentTime);
                    oscillator.start();
                    oscillator.stop(audioContext.currentTime + 1);
                    setTimeout(() => { overspeedAlarmActive = false; }, 3000);
                }
            } catch (error) {
                console.error('Network error, caching location:', error);
                cacheLocation(lat, lon, timestamp);
                showToast("Offline: Location saved locally", "info");
            }
        }

        // SOS Functionality
        let sosTimer;
        const sosBtn = document.getElementById('sosBtn');
        
        sosBtn.addEventListener('mousedown', startSosTimer);
        sosBtn.addEventListener('touchstart', startSosTimer);
        sosBtn.addEventListener('mouseup', cancelSosTimer);
        sosBtn.addEventListener('mouseleave', cancelSosTimer);
        sosBtn.addEventListener('touchend', cancelSosTimer);

        function startSosTimer(e) {
            e.preventDefault();
            sosBtn.style.transform = 'scale(0.95)';
            sosTimer = setTimeout(triggerSos, 2000); // 2 second hold
        }

        function cancelSosTimer() {
            clearTimeout(sosTimer);
            sosBtn.style.transform = 'scale(1)';
        }

        async function triggerSos() {
            if (!driverId) return;
            
            sosBtn.style.background = '#ffd700'; // Yellow briefly for feedback
            
            // Get current high accuracy location for SOS
            navigator.geolocation.getCurrentPosition(async (pos) => {
                const data = {
                    driver_id: driverId,
                    latitude: pos.coords.latitude,
                    longitude: pos.coords.longitude,
                    type: 'emergency',
                    message: 'SOS Triggered by driver!'
                };

                try {
                    const res = await fetch('api/emergency.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(data)
                    });
                    const result = await res.json();
                    
                    if (result.success) {
                        showToast('SOS ALERT SENT!', 'success');
                        sosBtn.style.background = 'linear-gradient(135deg, #00c853, #1b5e20)'; // Green for success
                        setTimeout(() => {
                            sosBtn.style.background = 'linear-gradient(135deg, #ff1744, #d50000)';
                        }, 3000);
                    } else {
                        showToast('Error sending SOS: ' + result.message);
                        sosBtn.style.background = 'linear-gradient(135deg, #ff1744, #d50000)';
                    }
                } catch (err) {
                    showToast('Connection error, SOS failed!');
                    sosBtn.style.background = 'linear-gradient(135deg, #ff1744, #d50000)';
                }
            }, (err) => {
                showToast('GPS Error sending SOS');
            }, { enableHighAccuracy: true });
        }
    </script>
</body>
</html>
