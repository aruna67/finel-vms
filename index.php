<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sri Lanka Army Vehicle Management System - Complete Fixed</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Roboto:wght@300;400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        /* Same CSS as before - keeping your existing styles */
        :root {
            --army-green: #006837;
            --army-dark: #0d1b1e;
            --army-light: #2d5a27;
            --army-tan: #8b7355;
            --army-yellow: #ffd700;
            --army-red: #D32F2F;
            --text-light: #f0f0f0;
            --text-gray: #cccccc;
            --danger: #d32f2f;
            --warning: #ff9800;
            --success: #4caf50;
            --info: #2196f3;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #111;
            color: var(--text-light);
            min-height: 100vh;
            overflow-x: hidden;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        .logo {
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            letter-spacing: 1px;
        }

        /* Login Page */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.85), rgba(0, 0, 0, 0.85)), url('https://images.unsplash.com/photo-1541188495357-ad2f4b59d2ed?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .login-top-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 150px;
            overflow: hidden;
            z-index: 1;
            border-bottom: 3px solid var(--army-yellow);
        }

        .login-box {
            background-color: rgba(0, 104, 55, 0.95);
            padding: 40px;
            border-radius: 10px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7);
            border: 2px solid var(--army-yellow);
            text-align: center;
            position: relative;
            z-index: 2;
            margin-top: 80px;
        }

        .army-logo-container {
            margin-bottom: 30px;
            padding: 20px;
            border-bottom: 2px solid var(--army-yellow);
            position: relative;
        }

        .army-logo {
            width: 180px;
            height: 180px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: rgba(0, 0, 0, 0.4);
            /* Dark semi-transparent background to hide any white edges */
            border-radius: 50%;
            /* This will mask the square corners of your uploaded image */
            border: 4px solid #d4af37;
            /* Premium Gold border to match military style */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .army-logo-text {
            color: var(--army-yellow);
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .army-logo-container .title {
            font-size: 1.4rem;
            color: white;
            margin-top: 10px;
            font-weight: 500;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--army-yellow);
            font-weight: 500;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            background-color: rgba(0, 0, 0, 0.5);
            border: 1px solid var(--army-tan);
            border-radius: 5px;
            color: var(--text-light);
            font-size: 1rem;
            font-family: 'Roboto', sans-serif;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--army-yellow);
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        select[multiple] {
            height: 120px;
        }

        .login-btn {
            background-color: var(--army-dark);
            color: var(--army-yellow);
            border: 1px solid var(--army-tan);
            padding: 14px;
            border-radius: 5px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .login-btn:hover {
            background-color: var(--army-yellow);
            color: var(--army-dark);
        }

        /* Main System Container */
        .system-container {
            display: none;
            min-height: 100vh;
        }

        /* Header */
        .header {
            background-color: var(--army-dark);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            border-bottom: 3px solid var(--army-yellow);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-logo-icon {
            width: 50px;
            height: 50px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--army-yellow);
            padding: 3px;
            overflow: hidden;
        }

        .header-logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .header-logo-icon div {
            width: 100%;
            height: 100%;
            background-color: #006837;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 5px;
            font-size: 0.6rem;
        }

        .header-logo-icon div div {
            background: none;
            padding: 0;
            font-size: inherit;
        }

        .header-logo-text {
            display: flex;
            flex-direction: column;
        }

        .header-logo-text h2 {
            color: var(--army-yellow);
            font-size: 1.6rem;
            margin-bottom: 4px;
        }

        .header-logo-text p {
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        .header-controls {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-light);
        }

        .user-info i {
            color: var(--army-yellow);
            font-size: 1.5rem;
        }

        .logout-btn {
            background-color: transparent;
            color: var(--army-yellow);
            border: 1px solid var(--army-tan);
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background-color: var(--army-yellow);
            color: var(--army-dark);
        }

        /* Layout */
        .layout {
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: var(--army-green);
            min-height: calc(100vh - 80px);
            padding: 20px 0;
            border-right: 2px solid var(--army-tan);
        }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            padding: 15px 25px;
            border-bottom: 1px solid rgba(139, 115, 85, 0.3);
            transition: all 0.3s;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 15px;
            color: var(--text-light);
        }

        .nav-item.active,
        .nav-item:hover {
            background-color: rgba(13, 27, 30, 0.7);
            color: var(--army-yellow);
            border-left: 5px solid var(--army-yellow);
        }

        .nav-item i {
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 30px;
            background-color: rgba(0, 0, 0, 0.7);
            overflow-y: auto;
            max-height: calc(100vh - 80px);
        }

        .page {
            display: none;
        }

        .page.active {
            display: block;
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .page-title {
            color: var(--army-yellow);
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--army-tan);
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }

        .add-btn,
        .action-btn {
            background-color: var(--army-green);
            color: var(--army-yellow);
            border: 1px solid var(--army-yellow);
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .add-btn:hover,
        .action-btn:hover {
            background-color: var(--army-yellow);
            color: var(--army-green);
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: var(--army-dark);
            padding: 25px;
            border-radius: 8px;
            border-left: 5px solid var(--army-yellow);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
        }

        .stat-card h3 {
            color: var(--text-light);
            font-size: 1.1rem;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--army-yellow);
            font-family: 'Orbitron', sans-serif;
        }

        .stat-change {
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .positive {
            color: var(--success);
        }

        .negative {
            color: var(--danger);
        }

        .warning {
            color: var(--warning);
        }

        /* Tables */
        .table-container {
            overflow-x: auto;
            margin-top: 20px;
            background-color: var(--army-dark);
            border-radius: 8px;
            padding: 20px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            background-color: var(--army-green);
            color: var(--army-yellow);
            padding: 15px;
            text-align: left;
            font-weight: 500;
            border-bottom: 2px solid var(--army-tan);
        }

        .data-table td {
            padding: 15px;
            border-bottom: 1px solid rgba(139, 115, 85, 0.3);
            vertical-align: middle;
        }

        .data-table tr:hover {
            background-color: rgba(0, 104, 55, 0.3);
        }

        /* Vehicle Image */
        .vehicle-image-cell {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
            border: 2px solid var(--army-yellow);
            cursor: pointer;
        }

        .vehicle-image-thumb {
            width: 50px;
            height: 50px;
            border-radius: 5px;
            border: 2px solid var(--army-yellow);
            object-fit: cover;
        }

        /* Status Badges */
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
        }

        .status-active,
        .status-success {
            background-color: rgba(76, 175, 80, 0.2);
            color: var(--success);
            border: 1px solid var(--success);
        }

        .status-inactive,
        .status-danger {
            background-color: rgba(211, 47, 47, 0.2);
            color: var(--danger);
            border: 1px solid var(--danger);
        }

        .status-maintenance,
        .status-warning {
            background-color: rgba(255, 152, 0, 0.2);
            color: var(--warning);
            border: 1px solid var(--warning);
        }

        .status-info {
            background-color: rgba(33, 150, 243, 0.2);
            color: var(--info);
            border: 1px solid var(--info);
        }

        .status-out {
            background-color: rgba(255, 152, 0, 0.2);
            color: var(--warning);
            border: 1px solid var(--warning);
        }

        .status-in {
            background-color: rgba(76, 175, 80, 0.2);
            color: var(--success);
            border: 1px solid var(--success);
        }

        .status-pending {
            background-color: rgba(33, 150, 243, 0.2);
            color: var(--info);
            border: 1px solid var(--info);
        }

        .status-completed {
            background-color: rgba(156, 39, 176, 0.2);
            color: #9C27B0;
            border: 1px solid #9C27B0;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .edit-btn,
        .delete-btn,
        .call-btn,
        .whatsapp-btn,
        .fuel-btn,
        .pdf-btn,
        .location-btn,
        .track-btn,
        .in-out-btn,
        .view-btn,
        .checkin-btn,
        .image-btn {
            padding: 6px 12px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.85rem;
            transition: all 0.3s;
            background: transparent;
        }

        .edit-btn {
            background-color: rgba(33, 150, 243, 0.2);
            color: var(--info);
            border: 1px solid var(--info);
        }

        .edit-btn:hover {
            background-color: var(--info);
            color: white;
        }

        .delete-btn {
            background-color: rgba(211, 47, 47, 0.2);
            color: var(--danger);
            border: 1px solid var(--danger);
        }

        .delete-btn:hover {
            background-color: var(--danger);
            color: white;
        }

        .call-btn {
            background-color: rgba(76, 175, 80, 0.2);
            color: var(--success);
            border: 1px solid var(--success);
        }

        .call-btn:hover {
            background-color: var(--success);
            color: white;
        }

        .whatsapp-btn {
            background-color: rgba(37, 211, 102, 0.2);
            color: #25D366;
            border: 1px solid #25D366;
        }

        .whatsapp-btn:hover {
            background-color: #25D366;
            color: white;
        }

        .fuel-btn {
            background-color: rgba(255, 152, 0, 0.2);
            color: var(--warning);
            border: 1px solid var(--warning);
        }

        .fuel-btn:hover {
            background-color: var(--warning);
            color: white;
        }

        .pdf-btn {
            background-color: rgba(156, 39, 176, 0.2);
            color: #9C27B0;
            border: 1px solid #9C27B0;
        }

        .pdf-btn:hover {
            background-color: #9C27B0;
            color: white;
        }

        .location-btn {
            background-color: rgba(0, 150, 136, 0.2);
            color: #009688;
            border: 1px solid #009688;
        }

        .location-btn:hover {
            background-color: #009688;
            color: white;
        }

        .track-btn {
            background-color: rgba(76, 175, 80, 0.2);
            color: var(--success);
            border: 1px solid var(--success);
        }

        .track-btn:hover {
            background-color: var(--success);
            color: white;
        }

        .in-out-btn {
            background-color: rgba(255, 215, 0, 0.2);
            color: var(--army-yellow);
            border: 1px solid var(--army-yellow);
        }

        .in-out-btn:hover {
            background-color: var(--army-yellow);
            color: var(--army-dark);
        }

        .view-btn {
            background-color: rgba(0, 150, 136, 0.2);
            color: #009688;
            border: 1px solid #009688;
        }

        .view-btn:hover {
            background-color: #009688;
            color: white;
        }

        .checkin-btn {
            background-color: rgba(76, 175, 80, 0.2);
            color: var(--success);
            border: 1px solid var(--success);
        }

        .checkin-btn:hover {
            background-color: var(--success);
            color: white;
        }

        .image-btn {
            background-color: rgba(156, 39, 176, 0.2);
            color: #9C27B0;
            border: 1px solid #9C27B0;
        }

        .image-btn:hover {
            background-color: #9C27B0;
            color: white;
        }

        .barcode-btn {
            background-color: rgba(0, 188, 212, 0.2);
            color: #00BCD4;
            border: 1px solid #00BCD4;
        }

        .barcode-btn:hover {
            background-color: #00BCD4;
            color: var(--army-dark);
        }

        /* Charts */
        .charts-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin: 30px 0;
        }

        .chart-card {
            background-color: var(--army-dark);
            padding: 25px;
            border-radius: 8px;
            border: 1px solid var(--army-tan);
        }

        .chart-card h3 {
            color: var(--army-yellow);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chart-wrapper {
            height: 300px;
            position: relative;
        }

        /* Activity List */
        .activity-list {
            margin-top: 20px;
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 15px;
            background-color: var(--army-dark);
            border-radius: 8px;
            margin-bottom: 10px;
            border-left: 4px solid var(--army-yellow);
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            background-color: rgba(255, 215, 0, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--army-yellow);
            font-size: 1.2rem;
        }

        .activity-item h4 {
            color: var(--text-light);
            margin-bottom: 5px;
        }

        .activity-item p {
            color: var(--text-gray);
            font-size: 0.95rem;
        }

        /* Map */
        #map,
        #trackingMap {
            height: 500px;
            min-height: 400px;
            width: 100%;
            border-radius: 8px;
            border: 2px solid var(--army-tan);
            margin: 20px 0;
            z-index: 1;
            display: block !important;
        }

        /* Fuel Progress */
        .fuel-progress {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .progress-bar {
            width: 80px;
            height: 20px;
            background-color: rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--success), var(--warning));
            transition: width 0.3s ease;
        }

        /* In/Out Summary */
        .in-out-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .in-out-card {
            background-color: var(--army-dark);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border-left: 5px solid var(--army-yellow);
        }

        .in-out-card h4 {
            color: var(--text-light);
            margin-bottom: 10px;
            font-size: 1rem;
        }

        .in-out-card .count {
            font-size: 2.5rem;
            font-weight: bold;
            font-family: 'Orbitron', sans-serif;
            color: var(--army-yellow);
        }

        /* GPS Status */
        .gps-status {
            background-color: var(--army-dark);
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            display: flex;
            align-items: center;
            gap: 15px;
            border-left: 5px solid var(--army-yellow);
        }

        .gps-signal {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .gps-signal.good {
            background-color: rgba(76, 175, 80, 0.2);
            color: var(--success);
        }

        .gps-signal.fair {
            background-color: rgba(255, 152, 0, 0.2);
            color: var(--warning);
        }

        .gps-signal.poor {
            background-color: rgba(211, 47, 47, 0.2);
            color: var(--danger);
        }

        .gps-coordinates {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.2rem;
            color: var(--army-yellow);
            margin-bottom: 5px;
        }

        .gps-details {
            display: flex;
            gap: 20px;
            color: var(--text-gray);
            font-size: 0.9rem;
            flex-wrap: wrap;
        }

        /* GPS Controls */
        .gps-controls {
            display: flex;
            gap: 10px;
            margin: 20px 0;
            flex-wrap: wrap;
        }

        .gps-btn {
            background-color: var(--army-dark);
            color: var(--army-yellow);
            border: 1px solid var(--army-tan);
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .gps-btn:hover {
            background-color: var(--army-yellow);
            color: var(--army-dark);
        }

        .gps-btn.active {
            background-color: var(--army-green);
            color: var(--army-yellow);
            border-color: var(--army-yellow);
        }

        /* Device List */
        .device-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .device-card {
            background-color: rgba(0, 0, 0, 0.3);
            border: 1px solid var(--army-tan);
            border-radius: 8px;
            padding: 20px;
        }

        .device-card h4 {
            color: var(--army-yellow);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .device-info {
            margin: 10px 0;
            color: var(--text-gray);
        }

        .device-status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            margin-left: 10px;
        }

        .device-status.online {
            background-color: rgba(76, 175, 80, 0.2);
            color: var(--success);
            border: 1px solid var(--success);
        }

        .device-status.offline {
            background-color: rgba(211, 47, 47, 0.2);
            color: var(--danger);
            border: 1px solid var(--danger);
        }

        /* WhatsApp Container */
        .whatsapp-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 20px;
        }

        .whatsapp-controls,
        .whatsapp-history {
            background-color: var(--army-dark);
            padding: 25px;
            border-radius: 8px;
        }

        .whatsapp-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .send-btn {
            background-color: #25D366;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            font-size: 1.1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .send-btn:hover {
            background-color: #1da851;
        }

        .whatsapp-number-display {
            color: var(--army-yellow);
            font-weight: bold;
            font-size: 1.1rem;
            margin: 20px 0;
            text-align: center;
            padding: 15px;
            background-color: var(--army-dark);
            border-radius: 8px;
            border: 1px solid var(--army-tan);
        }

        /* Tracking History */
        .tracking-history {
            background-color: var(--army-dark);
            padding: 25px;
            border-radius: 8px;
            margin-top: 30px;
        }

        .history-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px;
            border-bottom: 1px solid rgba(139, 115, 85, 0.3);
            flex-wrap: wrap;
            gap: 10px;
        }

        .history-item:last-child {
            border-bottom: none;
        }

        .history-time {
            color: var(--army-yellow);
            font-weight: 500;
        }

        .history-coords {
            color: var(--text-gray);
        }

        .history-source {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
        }

        .history-source.gps {
            background-color: rgba(76, 175, 80, 0.2);
            color: var(--success);
            border: 1px solid var(--success);
        }

        .history-source.gsm {
            background-color: rgba(255, 152, 0, 0.2);
            color: var(--warning);
            border: 1px solid var(--warning);
        }

        .history-source.manual {
            background-color: rgba(33, 150, 243, 0.2);
            color: var(--info);
            border: 1px solid var(--info);
        }

        /* Fuel Management */
        .fuel-management-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin: 20px 0;
        }

        .fuel-stock-section,
        .fuel-allocation-section {
            background-color: var(--army-dark);
            padding: 25px;
            border-radius: 8px;
        }

        .fuel-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .fuel-card {
            background-color: rgba(0, 0, 0, 0.3);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border-left: 5px solid;
        }

        .fuel-card h4 {
            color: var(--text-light);
            margin-bottom: 10px;
            font-size: 1rem;
        }

        .fuel-card .amount {
            font-size: 2rem;
            font-weight: bold;
            font-family: 'Orbitron', sans-serif;
            margin-bottom: 5px;
        }

        .fuel-card .unit {
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        .stock-update-form,
        .fuel-allocation-form {
            margin-top: 20px;
        }

        /* Form Rows */
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .submit-btn {
            background-color: var(--army-green);
            color: var(--army-yellow);
            border: 1px solid var(--army-yellow);
            padding: 12px 30px;
            border-radius: 5px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .submit-btn:hover {
            background-color: var(--army-yellow);
            color: var(--army-green);
        }

        /* Report Sharing */
        .report-sharing {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .share-btn {
            background-color: #25D366;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .share-btn.pdf {
            background-color: #9C27B0;
        }

        .share-btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        /* Officer Signature */
        .officer-signature {
            border-top: 2px solid var(--army-tan);
            margin-top: 20px;
            padding-top: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .signature-box {
            flex: 1;
            padding: 12px;
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px dashed var(--army-yellow);
            border-radius: 5px;
            font-style: italic;
            color: var(--army-yellow);
            min-width: 200px;
        }

        .auth-badge {
            display: inline-block;
            padding: 5px 12px;
            background-color: rgba(255, 215, 0, 0.2);
            border: 1px solid var(--army-yellow);
            border-radius: 20px;
            color: var(--army-yellow);
            font-size: 0.8rem;
            font-weight: bold;
        }

        /* Biometric & RFID */
        .bio-btn {
            background-color: transparent;
            border: 1px solid var(--army-yellow);
            color: var(--army-yellow);
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .bio-btn:hover {
            background-color: var(--army-yellow);
            color: var(--dark-bg);
        }
        .scan-animation {
            width: 100px;
            height: 100px;
            border: 2px solid var(--army-yellow);
            border-radius: 10px;
            margin: 20px auto;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .scan-animation i {
            font-size: 50px;
            color: rgba(255, 215, 0, 0.3);
        }
        .scan-line {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: var(--army-yellow);
            box-shadow: 0 0 10px var(--army-yellow);
            animation: scan 2s infinite linear;
        }
        @keyframes scan {
            0% { top: 0; }
            50% { top: 100%; }
            100% { top: 0; }
        }

        /* Image Upload */
        .image-upload-container {
            margin: 20px 0;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            border: 1px solid var(--army-tan);
        }

        .image-preview {
            width: 150px;
            height: 150px;
            background-color: rgba(0, 0, 0, 0.5);
            border: 2px dashed var(--army-tan);
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 10px auto;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s;
        }

        .image-preview:hover {
            border-color: var(--army-yellow);
        }

        .image-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }

        .image-preview i {
            font-size: 3rem;
            color: var(--army-tan);
            margin-bottom: 10px;
        }

        .image-preview span {
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        .upload-btn {
            background-color: var(--army-green);
            color: var(--army-yellow);
            border: 1px solid var(--army-tan);
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
            transition: all 0.3s;
        }

        .upload-btn:hover {
            background-color: var(--army-yellow);
            color: var(--army-green);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.95);
            z-index: 10000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: var(--army-dark);
            width: 90%;
            max-width: 900px;
            max-height: 90vh;
            overflow-y: auto;
            border-radius: 10px;
            border: 2px solid var(--army-yellow);
        }

        .modal-header {
            background-color: var(--army-green);
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--army-yellow);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .modal-header h3 {
            color: var(--army-yellow);
        }

        .close-modal {
            background: none;
            border: none;
            color: var(--army-yellow);
            font-size: 2rem;
            cursor: pointer;
            line-height: 1;
        }

        .close-modal:hover {
            opacity: 0.8;
        }

        .modal-body {
            padding: 25px;
        }

        /* Image Preview Modal */
        .image-preview-modal .modal-content {
            max-width: 600px;
            text-align: center;
        }

        .full-image {
            max-width: 100%;
            max-height: 70vh;
            border-radius: 5px;
            border: 2px solid var(--army-yellow);
        }

        /* Notification */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 5px;
            color: white;
            z-index: 100000;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.3s ease;
            max-width: 400px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .notification.success {
            background-color: var(--success);
            border-left: 5px solid #2e7d32;
        }

        .notification.error {
            background-color: var(--danger);
            border-left: 5px solid #b71c1c;
        }

        .notification.warning {
            background-color: var(--warning);
            border-left: 5px solid #ef6c00;
            color: #333;
        }

        .notification.info {
            background-color: var(--info);
            border-left: 5px solid #0d47a1;
        }

        /* Footer */
        .footer {
            background-color: var(--army-dark);
            text-align: center;
            padding: 20px;
            border-top: 2px solid var(--army-yellow);
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 1200px) {

            .charts-container,
            .fuel-management-container,
            .whatsapp-container {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
            }

            .nav-item span {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
                padding: 15px;
            }

            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                min-height: auto;
                padding: 10px 0;
            }

            .nav-menu {
                display: flex;
                overflow-x: auto;
            }

            .nav-item {
                padding: 10px 15px;
                white-space: nowrap;
                border-left: none;
                border-bottom: none;
            }

            .nav-item.active,
            .nav-item:hover {
                border-left: none;
                border-bottom: 3px solid var(--army-yellow);
            }

            .page-title {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
    <!-- Login Page -->
    <div class="login-container" id="loginPage">
        <div class="login-top-image">
            <div
                style="width: 100%; height: 100%; background: linear-gradient(rgba(0, 104, 55, 0.8), rgba(13, 27, 30, 0.8));">
            </div>
        </div>

        <div class="login-box">
            <div class="army-logo-container">
                <div class="army-logo">
                    <img src="uploads/army_logo.png" alt=""
                        style="width: 85%; height: 85%; object-fit: contain; display: block;">
                </div>
                <div class="army-logo-text">SRI LANKA ARMY</div>
                <h1 class="title">VEHICLE MANAGEMENT SYSTEM</h1>
            </div>

            <form class="login-form" id="loginForm">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> USERNAME</label>
                    <input type="text" id="username" placeholder="Enter your military ID" required>
                </div>
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> PASSWORD</label>
                    <input type="password" id="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="login-btn"><i class="fas fa-sign-in-alt"></i> LOGIN</button>
            </form>
            <div style="margin-top: 20px; border-top: 1px solid rgba(255,215,0,0.3); padding-top: 15px; text-align: center;">
                <p style="color: var(--text-gray); font-size: 0.9rem; margin-bottom: 10px;">Are you a Driver?</p>
                <a href="driver_app.php" style="color: var(--army-yellow); text-decoration: none; font-weight: bold; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <i class="fas fa-mobile-alt"></i> OPEN DRIVER APP
                </a>
            </div>
        </div>
    </div>

    <!-- Main System -->
    <div class="system-container" id="systemContainer">
        <!-- Header -->
        <div class="header">
            <div class="header-logo">
                <div class="header-logo-icon">
                    <img src="uploads/army_logo.png" alt="Logo">
                </div>
                <div class="header-logo-text">
                    <h2>ARMY VMS</h2>
                    <p>Vehicle Management System</p>
                </div>
            </div>
            <div class="header-controls">
                <div class="user-info">
                    <i class="fas fa-user-shield"></i>
                    <span id="userName"></span>
                    <span id="userRoleBadge" style="
                        display: none;
                        font-size: 0.72rem;
                        font-weight: 700;
                        padding: 3px 10px;
                        border-radius: 20px;
                        letter-spacing: 1px;
                        text-transform: uppercase;
                        border: 1px solid var(--army-yellow);
                        color: var(--army-dark);
                        background: var(--army-yellow);
                        margin-left: 4px;
                    "></span>
                </div>
                <button class="logout-btn" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> LOGOUT</button>
            </div>
        </div>

        <div class="layout">
            <!-- Sidebar -->
            <div class="sidebar">
                <ul class="nav-menu">
                    <li class="nav-item active" data-page="dashboard">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </li>
                    <li class="nav-item" data-page="inout">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Vehicle In/Out</span>
                    </li>
                    <li class="nav-item" data-page="inventory">
                        <i class="fas fa-truck-moving"></i>
                        <span>Vehicle Inventory</span>
                    </li>
                    <li class="nav-item" data-page="drivers">
                        <i class="fas fa-user-tie"></i>
                        <span>Driver Details</span>
                    </li>
                    <li class="nav-item" data-page="service">
                        <i class="fas fa-tools"></i>
                        <span>Service Record</span>
                    </li>
                    <li class="nav-item" data-page="fuel">
                        <i class="fas fa-gas-pump"></i>
                        <span>Fuel Management</span>
                    </li>
                    <li class="nav-item" data-page="efficiency">
                        <i class="fas fa-chart-line"></i>
                        <span>Fuel Efficiency</span>
                    </li>
                    <li class="nav-item" data-page="whatsapp">
                        <i class="fab fa-whatsapp"></i>
                        <span>WhatsApp Integration</span>
                    </li>
                    <li class="nav-item" data-page="gps">
                        <i class="fas fa-satellite"></i>
                        <span>GPS Tracker</span>
                    </li>
                    <li class="nav-item" data-page="geofence">
                        <i class="fas fa-draw-polygon"></i>
                        <span>Geofencing</span>
                    </li>
                    <li class="nav-item" data-page="tracking">
                        <i class="fas fa-map-marked-alt"></i>
                        <span>Vehicle Tracking</span>
                    </li>
                    <li class="nav-item" data-page="reports">
                        <i class="fas fa-file-pdf"></i>
                        <span>Reports & Sharing</span>
                    </li>
                    <li class="nav-item" id="navUsers" data-page="users">
                        <i class="fas fa-users-cog"></i>
                        <span id="navUsersLabel">User Management</span>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                <!-- Dashboard -->
                <div class="page active" id="dashboard">
                    <h2 class="page-title">
                        <div>
                            <i class="fas fa-tachometer-alt"></i> Operational Dashboard
                        </div>
                        <div>
                            <button class="add-btn" onclick="refreshDashboard()">
                                <i class="fas fa-sync-alt"></i> REFRESH
                            </button>
                        </div>
                    </h2>

                    <div class="stats-container" id="dashboardStats"></div>

                    <div class="charts-container">
                        <div class="chart-card">
                            <h3><i class="fas fa-chart-pie"></i> Vehicle Status Distribution</h3>
                            <div class="chart-wrapper">
                                <canvas id="statusPieChart"></canvas>
                            </div>
                        </div>
                        <div class="chart-card">
                            <h3><i class="fas fa-chart-bar"></i> Vehicle Movements (Last 7 Days)</h3>
                            <div class="chart-wrapper">
                                <canvas id="movementBarChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="recent-activity">
                        <h3><i class="fas fa-history"></i> Recent Activity</h3>
                        <div class="activity-list" id="recentActivity"></div>
                    </div>
                </div>

                <!-- Vehicle Inventory -->
                <div class="page" id="inventory">
                    <h2 class="page-title">
                        <div>
                            <i class="fas fa-truck-moving"></i> Vehicle Inventory
                        </div>
                        <button class="add-btn" id="addVehicleBtn">
                            <i class="fas fa-plus"></i> ADD NEW VEHICLE
                        </button>
                    </h2>

                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Vehicle ID</th>
                                    <th>Type</th>
                                    <th>Model</th>
                                    <th>Registration</th>
                                    <th>Fuel Level</th>
                                    <th>Status</th>
                                    <th>Assigned Unit</th>
                                    <th>Work Ticket</th>
                                    <th>In/Out</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="vehicleTableBody"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Driver Details -->
                <div class="page" id="drivers">
                    <h2 class="page-title">
                        <div>
                            <i class="fas fa-user-tie"></i> Driver Details
                        </div>
                        <button class="add-btn" id="addDriverBtn">
                            <i class="fas fa-plus"></i> ADD NEW DRIVER
                        </button>
                    </h2>

                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Driver ID</th>
                                    <th>Name</th>
                                    <th>Rank</th>
                                    <th>License Class</th>
                                    <th>License Expiry</th>
                                    <th>Assigned Vehicle</th>
                                    <th>Status</th>
                                    <th>Contact</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="driverTableBody"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Service Record -->
                <div class="page" id="service">
                    <h2 class="page-title">
                        <div>
                            <i class="fas fa-tools"></i> Service Record Management
                        </div>
                    </h2>

                    <div class="service-form" style="margin-bottom: 30px;">
                        <h3><i class="fas fa-plus-circle"></i> Add New Service Record</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="serviceVehicleSelect">Vehicle</label>
                                <select id="serviceVehicleSelect" class="form-control" required>
                                    <option value="">Select Vehicle</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="serviceDate">Service Date</label>
                                <input type="date" id="serviceDate" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="serviceType">Service Type</label>
                                <select id="serviceType" class="form-control" required>
                                    <option value="routine">Routine Maintenance</option>
                                    <option value="repair">Repair</option>
                                    <option value="inspection">Inspection</option>
                                    <option value="emergency">Emergency Repair</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="serviceCost">Cost (LKR)</label>
                                <input type="number" id="serviceCost" placeholder="Enter service cost"
                                    class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="serviceDescription">Service Description</label>
                            <textarea id="serviceDescription" placeholder="Describe the service performed..."
                                class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="serviceTechnician">Technician Name</label>
                            <input type="text" id="serviceTechnician" class="form-control"
                                placeholder="Enter technician name" required>
                        </div>

                        <button class="submit-btn" id="saveServiceBtn"><i class="fas fa-save"></i> SAVE SERVICE
                            RECORD</button>
                    </div>
                    <div class="service-form" style="margin-bottom: 30px; border-left: 4px solid var(--army-yellow);">
                        <h3><i class="fas fa-wrench"></i> SLEME Quick Actions</h3>
                        <div class="form-row" style="align-items: flex-end;">
                            <div class="form-group" style="flex: 2; text-align: left;">
                                <label for="slemeVehicleSelect">Select Vehicle</label>
                                <select id="slemeVehicleSelect" class="form-control" required>
                                    <option value="">Select Vehicle</option>
                                </select>
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <button type="button" class="submit-btn" id="vehicleAdmittedBtn" style="background-color: #FF9800; border-color: #F57C00;"><i class="fas fa-tools"></i> Vehicle Admitted</button>
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <button type="button" class="submit-btn" id="jobCompletedBtn" style="background-color: #4CAF50; border-color: #388E3C;"><i class="fas fa-check-circle"></i> Job Completed</button>
                            </div>
                        </div>
                    </div>

                    <div class="table-container">
                        <h3><i class="fas fa-history"></i> Service Records</h3>
                        <table class="data-table" id="serviceRecordsTable">
                            <thead>
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Service Date</th>
                                    <th>Service Type</th>
                                    <th>Description</th>
                                    <th>Cost</th>
                                    <th>Technician</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="serviceRecordsBody"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Geofencing Page -->
                <div class="page" id="geofence">
                    <h2 class="page-title">
                        <div>
                            <i class="fas fa-draw-polygon"></i> Military Zone Geofencing
                        </div>
                        <div style="display:flex; gap:10px;">
                            <button class="add-btn" onclick="startDrawingGeofence()" style="background-color: var(--army-yellow); color: var(--army-dark);">
                                <i class="fas fa-plus"></i> DRAW NEW ZONE
                            </button>
                            <button class="add-btn" onclick="loadGeofences()">
                                <i class="fas fa-sync"></i> REFRESH
                            </button>
                        </div>
                    </h2>

                    <div class="stats-container" style="display: grid; grid-template-columns: 3fr 1fr; gap: 20px; margin-top: 20px;">
                        <div class="chart-card" style="padding:0; overflow:hidden; position:relative; min-height: 600px;">
                            <div id="geofenceMap" style="height: 600px; width: 100%;"></div>
                            <div id="drawingControls" style="position:absolute; top:20px; right:20px; z-index:1000; background:rgba(13, 27, 30, 0.95); padding:20px; border-radius:12px; display:none; border:2px solid var(--army-yellow); box-shadow: 0 10px 30px rgba(0,0,0,0.5); width: 280px;">
                                <h4 style="color:var(--army-yellow); margin-bottom:15px; text-transform: uppercase; letter-spacing: 1px;">Zone Details</h4>
                                <div class="form-group">
                                    <label style="color: var(--text-gray); font-size: 0.8rem;">Zone Name</label>
                                    <input type="text" id="gfName" class="form-control" placeholder="e.g. Area 51" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1);">
                                </div>
                                <div class="form-group" style="margin-top: 10px;">
                                    <label style="color: var(--text-gray); font-size: 0.8rem;">Security Level</label>
                                    <select id="gfType" class="form-control" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1);">
                                        <option value="forbidden">FORBIDDEN (Red Alert)</option>
                                        <option value="authorized">AUTHORIZED (Safe Zone)</option>
                                    </select>
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 20px;">
                                    <button class="submit-btn" onclick="saveDrawnGeofence()" style="padding: 10px;">SAVE</button>
                                    <button class="submit-btn" onclick="cancelDrawing()" style="background:var(--danger); padding: 10px;">CANCEL</button>
                                </div>
                            </div>
                        </div>

                        <div class="chart-card" style="background: var(--army-dark); border: 1px solid rgba(255,255,255,0.05);">
                            <h3 style="color:var(--army-yellow); margin-bottom: 20px; font-size: 1.1rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px;">
                                <i class="fas fa-list"></i> ACTIVE ZONES
                            </h3>
                            <div id="geofenceList" style="max-height:500px; overflow-y:auto; display: flex; flex-direction: column; gap: 12px;">
                                <!-- Zones will be listed here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fuel Management -->
                <div class="page" id="fuel">
                    <h2 class="page-title">
                        <div>
                            <i class="fas fa-gas-pump"></i> Fuel Management System
                        </div>
                        <button class="add-btn" id="manageStockBtn">
                            <i class="fas fa-edit"></i> MANAGE FUEL STOCK
                        </button>
                    </h2>

                    <div class="fuel-management-container">
                        <div class="fuel-stock-section">
                            <h3><i class="fas fa-oil-can"></i> Fuel Stock Levels</h3>
                            <div class="fuel-summary" id="fuelSummary"></div>

                            <div class="stock-update-form">
                                <h4><i class="fas fa-edit"></i> Update Fuel Stock</h4>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="stockFuelType">Fuel Type</label>
                                        <select id="stockFuelType" class="form-control">
                                            <option value="diesel">Diesel</option>
                                            <option value="petrol">Petrol</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="stockAction">Action</label>
                                        <select id="stockAction" class="form-control">
                                            <option value="add">Add Stock</option>
                                            <option value="remove">Remove Stock</option>
                                            <option value="update">Update Stock</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="stockQuantity">Quantity (Liters)</label>
                                        <input type="number" id="stockQuantity" class="form-control"
                                            placeholder="Enter quantity" min="1" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="stockNotes">Notes</label>
                                        <input type="text" id="stockNotes" class="form-control"
                                            placeholder="Enter notes (optional)">
                                    </div>
                                </div>

                                <button class="submit-btn" id="updateStockBtn">
                                    <i class="fas fa-save"></i> UPDATE STOCK
                                </button>
                            </div>
                        </div>

                        <div class="fuel-allocation-section">
                            <h3><i class="fas fa-gas-pump"></i> Fuel Allocation</h3>

                            <div class="fuel-allocation-form">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="fuelVehicleSelect">Select Vehicle</label>
                                        <select id="fuelVehicleSelect" class="form-control" required>
                                            <option value="">Select Vehicle</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="fuelAmount">Fuel Amount (Liters)</label>
                                        <input type="number" id="fuelAmount" placeholder="Enter fuel amount"
                                            class="form-control" min="1" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="fuelType">Fuel Type</label>
                                        <select id="fuelType" class="form-control" required>
                                            <option value="diesel">Diesel</option>
                                            <option value="petrol">Petrol</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="fuelDate">Date</label>
                                        <input type="date" id="fuelDate" class="form-control" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="allocationPurpose">Purpose</label>
                                    <select id="allocationPurpose" class="form-control" required>
                                        <option value="routine">Routine Refill</option>
                                        <option value="mission">Mission/Operation</option>
                                        <option value="training">Training Exercise</option>
                                        <option value="emergency">Emergency Use</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>

                                <button class="submit-btn" id="saveFuelBtn">
                                    <i class="fas fa-save"></i> ALLOCATE FUEL
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-container">
                        <h3><i class="fas fa-history"></i> Recent Fuel Transactions</h3>
                        <table class="data-table" id="fuelRecordsTable">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Vehicle/Stock</th>
                                    <th>Fuel Type</th>
                                    <th>Amount (L)</th>
                                    <th>Purpose/Notes</th>
                                    <th>Authorized By</th>
                                    <th>Balance</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="fuelRecordsBody"></tbody>
                        </table>
                    </div>
                </div>

                <!-- WhatsApp Integration -->
                <div class="page" id="whatsapp">
                    <h2 class="page-title">
                        <div>
                            <i class="fab fa-whatsapp"></i> WhatsApp Integration
                        </div>
                    </h2>

                    <div class="whatsapp-number-display">
                        <i class="fab fa-whatsapp"></i> System Integrated WhatsApp Number: <strong>+94 76 758
                            3198</strong>
                    </div>

                    <div class="whatsapp-container">
                        <div class="whatsapp-controls">
                            <h3><i class="fas fa-comment"></i> Send Alert / Notification</h3>
                            <form class="whatsapp-form" id="whatsappForm">
                                <div class="form-group">
                                    <label for="recipient">Recipient</label>
                                    <select id="recipient" class="form-control" required>
                                        <option value="all">All Drivers</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="messageType">Message Type</label>
                                    <select id="messageType" class="form-control">
                                        <option value="alert">Alert</option>
                                        <option value="update">Status Update</option>
                                        <option value="reminder">Reminder</option>
                                        <option value="emergency">Emergency</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="whatsappMessage">Message Content</label>
                                    <textarea id="whatsappMessage" placeholder="Type your message here..."
                                        class="form-control" rows="4" required></textarea>
                                </div>

                                <button type="submit" class="send-btn" id="sendWhatsappBtn">
                                    <i class="fab fa-whatsapp"></i> SEND VIA WHATSAPP
                                </button>
                            </form>
                        </div>

                        <div class="whatsapp-history">
                            <h3><i class="fas fa-history"></i> Recent Messages</h3>
                            <div class="activity-list" id="whatsappHistoryList"></div>
                        </div>
                    </div>
                </div>

                <!-- GPS Tracker Page -->
                <div class="page" id="gps">
                    <h2 class="page-title">
                        <div>
                            <i class="fas fa-satellite"></i> GPS Tracker System
                        </div>
                        <div class="gps-controls">
                            <button class="gps-btn" onclick="startGPSTracking()">
                                <i class="fas fa-play"></i> START TRACKING
                            </button>
                            <button class="gps-btn" onclick="stopGPSTracking()">
                                <i class="fas fa-stop"></i> STOP TRACKING
                            </button>
                            <button class="gps-btn" onclick="getCurrentGPSLocation()">
                                <i class="fas fa-location-arrow"></i> GET CURRENT LOCATION
                            </button>
                            <button class="gps-btn" onclick="updateVehicleLocation()">
                                <i class="fas fa-map-marker-alt"></i> UPDATE VEHICLE LOCATION
                            </button>
                        </div>
                    </h2>

                    <div class="gps-status" id="gpsStatus">
                        <div class="gps-signal good" id="gpsSignalIcon">
                            <i class="fas fa-satellite"></i>
                        </div>
                        <div class="gps-info">
                            <div class="gps-coordinates" id="gpsCoordinates">Waiting for GPS signal...</div>
                            <div class="gps-details" id="gpsDetails">
                                <span><i class="fas fa-map-pin"></i> Accuracy: <span id="gpsAccuracy">0</span>m</span>
                                <span><i class="fas fa-clock"></i> Time: <span id="gpsTime">-</span></span>
                                <span><i class="fas fa-satellite"></i> Satellites: <span
                                        id="gpsSatellites">0</span></span>
                            </div>
                        </div>
                    </div>

                    <div id="map"></div>

                    <div class="gps-device-config" style="margin-top: 20px;">
                        <h3><i class="fas fa-microchip"></i> Manual Location Update</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="manualVehicleSelect">Select Vehicle</label>
                                <select id="manualVehicleSelect" class="form-control">
                                    <option value="">Select Vehicle</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="manualLatitude">Latitude</label>
                                <input type="number" id="manualLatitude" class="form-control" step="0.000001"
                                    placeholder="e.g., 6.9271">
                            </div>
                            <div class="form-group">
                                <label for="manualLongitude">Longitude</label>
                                <input type="number" id="manualLongitude" class="form-control" step="0.000001"
                                    placeholder="e.g., 79.8612">
                            </div>
                        </div>

                        <button class="submit-btn" onclick="updateManualLocation()">
                            <i class="fas fa-save"></i> UPDATE LOCATION
                        </button>
                    </div>

                    <div class="gps-device-config" style="margin-top: 20px;">
                        <h3><i class="fas fa-microchip"></i> GPS Device Configuration</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="gpsDeviceSelect">Select Vehicle</label>
                                <select id="gpsDeviceSelect" class="form-control">
                                    <option value="">Select Vehicle</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="gpsDeviceType">GPS Device Type</label>
                                <select id="gpsDeviceType" class="form-control">
                                    <option value="gps">GPS Tracker</option>
                                    <option value="gsm">GSM Tracker</option>
                                    <option value="gps_gsm">GPS/GSM Combo</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="gpsDeviceId">Device ID</label>
                                <input type="text" id="gpsDeviceId" class="form-control" placeholder="GPS-12345">
                            </div>
                            <div class="form-group">
                                <label for="gpsUpdateInterval">Update Interval (seconds)</label>
                                <input type="number" id="gpsUpdateInterval" class="form-control" min="1" max="3600"
                                    value="30">
                            </div>
                        </div>

                        <button class="submit-btn" onclick="configureGPSDevice()">
                            <i class="fas fa-save"></i> CONFIGURE DEVICE
                        </button>
                    </div>

                    <div class="device-list" id="deviceList"></div>

                    <div class="tracking-history">
                        <h3><i class="fas fa-history"></i> Real-time Tracking History</h3>
                        <div id="trackingHistoryList"></div>
                    </div>
                </div>

                <!-- Vehicle Tracking -->
                <div class="page" id="tracking">
                    <h2 class="page-title">
                        <div>
                            <i class="fas fa-map-marked-alt"></i> Location Tracking System
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <select id="routeVehicleSelect" style="padding: 8px; border-radius: 5px; background-color: rgba(0, 0, 0, 0.5); border: 1px solid var(--army-tan); color: var(--text-light);" onchange="updateTrackingMarkers()">
                                <option value="all">All Vehicles (Live Only)</option>
                            </select>
                            <button class="add-btn" onclick="refreshTracking()">
                                <i class="fas fa-sync-alt"></i> REFRESH LOCATIONS
                            </button>
                        </div>
                    </h2>

                    <div id="trackingMap"></div>

                    <div class="table-container">
                        <h3><i class="fas fa-satellite"></i> Live Vehicle Status</h3>
                        <table class="data-table" id="trackingTable">
                            <thead>
                                <tr>
                                    <th>Vehicle ID</th>
                                    <th>Type</th>
                                    <th>Model</th>
                                    <th>Coordinates</th>
                                    <th>Last Update</th>
                                    <th>Source</th>
                                    <th>Accuracy</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="trackingTableBody"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Vehicle In/Out Tracking -->
                <div class="page" id="inout">
                    <h2 class="page-title">
                        <div>
                            <i class="fas fa-sign-in-alt"></i> Vehicle In/Out Tracking
                        </div>
                        <button class="add-btn" onclick="openCheckOutModal()">
                            <i class="fas fa-sign-out-alt"></i> CHECK OUT VEHICLE
                        </button>
                    </h2>

                    <div class="in-out-summary" id="inOutSummary"></div>

                    <!-- S5: Filter bar -->
                    <div style="display:flex; gap:15px; flex-wrap:wrap; margin-bottom:15px; align-items:flex-end;">
                        <div class="form-group" style="margin:0; min-width:160px;">
                            <label style="color:var(--army-yellow); font-size:0.85rem;">Filter by Vehicle</label>
                            <select id="filterMovementVehicle" class="form-control" onchange="applyMovementFilter()">
                                <option value="">All Vehicles</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin:0; min-width:160px;">
                            <label style="color:var(--army-yellow); font-size:0.85rem;">Filter by Driver</label>
                            <select id="filterMovementDriver" class="form-control" onchange="applyMovementFilter()">
                                <option value="">All Drivers</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin:0; min-width:140px;">
                            <label style="color:var(--army-yellow); font-size:0.85rem;">Date</label>
                            <input type="date" id="filterMovementDate" class="form-control"
                                onchange="applyMovementFilter()">
                        </div>
                        <button class="action-btn" onclick="clearMovementFilter()"><i class="fas fa-times"></i>
                            Clear</button>
                    </div>

                    <div class="table-container">
                        <h3>Active Vehicle Movements</h3>
                        <table class="data-table" id="movementTable">
                            <thead>
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Driver</th>
                                    <th>Check Out Time</th>
                                    <th>Expected Return</th>
                                    <th>Destination</th>
                                    <th>Purpose</th>
                                    <th>Authorized By</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="movementTableBody"></tbody>
                        </table>
                    </div>

                    <div class="table-container" style="margin-top: 30px;">
                        <h3>Today's Movement History</h3>
                        <table class="data-table" id="movementHistoryTable">
                            <thead>
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Driver</th>
                                    <th>Check Out</th>
                                    <th>Check In</th>
                                    <th>Duration</th>
                                    <th>Destination</th>
                                    <th>Authorized By</th>
                                </tr>
                            </thead>
                            <tbody id="movementHistoryBody"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Reports & Sharing -->
                <div class="page" id="reports">
                    <h2 class="page-title">
                        <div>
                            <i class="fas fa-file-pdf"></i> Reports & WhatsApp Sharing
                        </div>
                    </h2>

                    <div class="stats-container">
                        <div class="stat-card">
                            <h3>Generate Report</h3>
                            <div class="form-group" style="margin-bottom:15px;">
                                <label style="color:var(--army-yellow);">Report Type</label>
                                <select id="reportType" class="form-control">
                                    <option value="daily">Daily Report</option>
                                    <option value="weekly">Weekly Report</option>
                                    <option value="monthly">Monthly Report</option>
                                    <option value="movement">Movement Report</option>
                                    <option value="fuel">Fuel Consumption Report</option>
                                    <option value="service">Service Report</option>
                                </select>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label style="color:var(--army-yellow);">Start Date</label>
                                    <input type="date" id="reportStartDate" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label style="color:var(--army-yellow);">End Date</label>
                                    <input type="date" id="reportEndDate" class="form-control" required>
                                </div>
                            </div>
                            <div class="report-sharing">
                                <button class="share-btn pdf" onclick="generatePDFReport()">
                                    <i class="fas fa-file-pdf"></i> Generate PDF
                                </button>
                                <button class="share-btn" onclick="sharePreviewWhatsApp()">
                                    <i class="fab fa-whatsapp"></i> Share via WhatsApp
                                </button>
                            </div>
                        </div>

                        <div class="stat-card">
                            <h3>Quick Reports</h3>
                            <button class="add-btn" onclick="generateMovementReport()"
                                style="margin-bottom: 10px; width: 100%;">
                                <i class="fas fa-truck"></i> Vehicle Movement Report
                            </button>
                            <button class="add-btn" onclick="generateFuelReport()"
                                style="margin-bottom: 10px; width: 100%;">
                                <i class="fas fa-gas-pump"></i> Fuel Usage Report
                            </button>
                            <button class="add-btn" onclick="generateServiceReport()" style="width: 100%;">
                                <i class="fas fa-tools"></i> Service Report
                            </button>
                        </div>
                    </div>

                    <div class="table-container">
                        <h3>Recent Reports</h3>
                        <table class="data-table" id="reportsTable">
                            <thead>
                                <tr>
                                    <th>Report ID</th>
                                    <th>Type</th>
                                    <th>Date Range</th>
                                    <th>Generated By</th>
                                    <th>Generated On</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="reportsTableBody"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Fuel Efficiency Analytics Page -->
                <div class="page" id="efficiency">
                    <h2 class="page-title">
                        <div>
                            <i class="fas fa-chart-line"></i> Fuel Efficiency Analytics
                        </div>
                        <button class="add-btn" onclick="loadEfficiencyData()">
                            <i class="fas fa-sync-alt"></i> REFRESH ANALYTICS
                        </button>
                    </h2>

                    <div class="charts-container" style="grid-template-columns: 1fr;">
                        <div class="chart-card">
                            <h3><i class="fas fa-gas-pump"></i> KM per Liter by Vehicle (Performance)</h3>
                            <div class="chart-wrapper" style="height: 400px;">
                                <canvas id="efficiencyBarChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="table-container" style="margin-top: 30px;">
                        <h3><i class="fas fa-list-ul"></i> Detailed Efficiency Log (Completed Trips)</h3>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Driver</th>
                                    <th>Distance (KM)</th>
                                    <th>Fuel Used (L)</th>
                                    <th>Efficiency (KM/L)</th>
                                    <th>Status</th>
                                    <th>Check Out</th>
                                    <th>Check In</th>
                                </tr>
                            </thead>
                            <tbody id="efficiencyTableBody"></tbody>
                        </table>
                    </div>
                </div>

                <!-- User Management / My Profile Page -->
                <div class="page" id="users">
                    <h2 class="page-title">
                        <div><i class="fas fa-users-cog"></i> <span id="usersPageTitle">User Management</span></div>
                        <button class="add-btn" id="addUserBtn" style="display:none;">
                            <i class="fas fa-plus"></i> ADD NEW USER
                        </button>
                    </h2>

                    <!-- Admin: user list table -->
                    <div id="userListSection" style="display:none;">
                        <div class="table-container" style="margin-bottom:30px;">
                            <h3 style="color:var(--army-yellow); margin-bottom:15px;"><i class="fas fa-users"></i> All
                                Users</h3>
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Full Name</th>
                                        <th>Rank</th>
                                        <th>Role</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="userTableBody"></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Edit Profile + Change Password (all users) -->
                    <div id="editProfileSection">
                        <div
                            style="display:grid; grid-template-columns: repeat(auto-fit, minmax(400px,1fr)); gap:30px;">

                            <!-- Edit Profile -->
                            <div class="table-container">
                                <h3 style="color:var(--army-yellow); margin-bottom:20px;"><i
                                        class="fas fa-user-edit"></i> Edit Profile</h3>
                                <form id="editProfileForm">
                                    <input type="hidden" id="editProfileId">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>Full Name</label>
                                            <input type="text" id="editProfileFullName" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Rank</label>
                                            <select id="editProfileRank" class="form-control" required>
                                                <option value="Private">Private</option>
                                                <option value="Lance Corporal">Lance Corporal</option>
                                                <option value="Corporal">Corporal</option>
                                                <option value="Sergeant">Sergeant</option>
                                                <option value="Staff Sergeant">Staff Sergeant</option>
                                                <option value="Lieutenant">Lieutenant</option>
                                                <option value="Captain">Captain</option>
                                                <option value="Major">Major</option>
                                                <option value="Colonel">Colonel</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Username <small style="color:var(--text-gray);">(cannot be
                                                changed)</small></label>
                                        <input type="text" id="editProfileUsername" class="form-control" readonly
                                            style="opacity:0.6;cursor:not-allowed;"
                                            title="Username cannot be changed after creation">
                                    </div>
                                    <button type="submit" class="submit-btn"><i class="fas fa-save"></i> SAVE
                                        PROFILE</button>
                                </form>
                            </div>

                            <!-- Change Password -->
                            <div class="table-container">
                                <h3 style="color:var(--army-yellow); margin-bottom:20px;"><i class="fas fa-lock"></i>
                                    Change Password</h3>
                                <form id="changePasswordForm">
                                    <input type="hidden" id="changePasswordId">
                                    <div class="form-group">
                                        <label>Current Password</label>
                                        <input type="password" id="currentPassword" class="form-control" required>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>New Password</label>
                                            <input type="password" id="newPassword" class="form-control" required
                                                minlength="6">
                                        </div>
                                        <div class="form-group">
                                            <label>Confirm Password</label>
                                            <input type="password" id="confirmPassword" class="form-control" required
                                                minlength="6">
                                        </div>
                                    </div>
                                    <button type="submit" class="submit-btn"><i class="fas fa-key"></i> CHANGE
                                        PASSWORD</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </div><!-- /.main-content -->
        </div><!-- /.layout -->

        <!-- Footer -->
        <div class="footer">
            <p>SRI LANKA ARMY VEHICLE MANAGEMENT SYSTEM | CLASSIFIED: RESTRICTED ACCESS</p>
            <p>© 2026 SRI LANKA ARMY LOGISTICS COMMAND. ALL RIGHTS RESERVED.</p>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal" id="addUserModal">
        <div class="modal-content" style="max-width:500px;">
            <div class="modal-header">
                <h3><i class="fas fa-user-plus"></i> ADD NEW USER</h3>
                <button class="close-modal" id="closeAddUserModal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addUserForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" id="newUserFullName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Rank</label>
                            <select id="newUserRank" class="form-control" required>
                                <option value="Private">Private</option>
                                <option value="Lance Corporal">Lance Corporal</option>
                                <option value="Corporal">Corporal</option>
                                <option value="Sergeant">Sergeant</option>
                                <option value="Staff Sergeant">Staff Sergeant</option>
                                <option value="Lieutenant">Lieutenant</option>
                                <option value="Captain">Captain</option>
                                <option value="Major">Major</option>
                                <option value="Colonel">Colonel</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" id="newUserUsername" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select id="newUserRole" class="form-control" required>
                                <option value="officer">Officer</option>
                                <option value="driver">Driver</option>
                                <option value="mechanic">Mechanic/Technician</option>
                                <option value="fuel_manager">Fuel Manager</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" id="newUserPassword" class="form-control" required minlength="6">
                        </div>
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" id="newUserConfirmPassword" class="form-control" required
                                minlength="6">
                        </div>
                    </div>
                    <button type="submit" class="submit-btn"><i class="fas fa-save"></i> CREATE USER</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Role Modal (admin only) -->
    <div class="modal" id="editUserModal">
        <div class="modal-content" style="max-width:400px;">
            <div class="modal-header">
                <h3><i class="fas fa-user-edit"></i> EDIT USER</h3>
                <button class="close-modal" id="closeEditUserModal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="hidden" id="editUserId">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" id="editUserFullName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Rank</label>
                        <select id="editUserRank" class="form-control" required>
                            <option value="Private">Private</option>
                            <option value="Lance Corporal">Lance Corporal</option>
                            <option value="Corporal">Corporal</option>
                            <option value="Sergeant">Sergeant</option>
                            <option value="Staff Sergeant">Staff Sergeant</option>
                            <option value="Lieutenant">Lieutenant</option>
                            <option value="Captain">Captain</option>
                            <option value="Major">Major</option>
                            <option value="Colonel">Colonel</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select id="editUserRole" class="form-control" required>
                            <option value="officer">Officer</option>
                            <option value="driver">Driver</option>
                            <option value="mechanic">Mechanic/Technician</option>
                            <option value="fuel_manager">Fuel Manager</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="submit-btn"><i class="fas fa-save"></i> UPDATE USER</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Vehicle Modal with Image Upload -->
    <div class="modal" id="addVehicleModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-truck-moving"></i> ADD NEW VEHICLE</h3>
                <button class="close-modal" id="closeVehicleModal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addVehicleForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="newVehicleId">Vehicle ID</label>
                            <input type="text" id="newVehicleId" class="form-control" placeholder="e.g. A248" required>
                            <small id="vehicleIdError" style="color:var(--danger);display:none;"></small>
                        </div>
                        <div class="form-group">
                            <label for="newVehicleType">Vehicle Category</label>
                            <select id="newVehicleType" class="form-control" required>
                                <option value="">Select Category</option>
                                <option value="light">Light Vehicles</option>
                                <option value="heavy">Heavy Vehicles</option>
                                <option value="threeWheel">Three-Wheelers</option>
                                <option value="motorcycle">Motorcycles</option>
                                <option value="apc">APCs</option>
                                <option value="utility">Utility Vehicles</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="newVehicleModel">Model</label>
                            <input type="text" id="newVehicleModel" class="form-control"
                                placeholder="e.g. Land Rover Defender" required>
                        </div>
                        <div class="form-group">
                            <label for="newVehicleReg">Registration Number</label>
                            <input type="text" id="newVehicleReg" class="form-control" placeholder="e.g. SLA-5555"
                                required>
                            <small id="vehicleRegError" style="color:var(--danger);display:none;"></small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="newVehicleYear">Year</label>
                            <input type="number" id="newVehicleYear" class="form-control" placeholder="e.g. 2020"
                                min="2000" max="2024" required>
                        </div>
                        <div class="form-group">
                            <label for="newVehicleFuelType">Fuel Type</label>
                            <select id="newVehicleFuelType" class="form-control" required>
                                <option value="diesel">Diesel</option>
                                <option value="petrol">Petrol</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="newVehicleFuelCapacity">Fuel Capacity (Liters)</label>
                            <input type="number" id="newVehicleFuelCapacity" class="form-control" placeholder="e.g. 80"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="newVehicleStatus">Initial Status</label>
                            <select id="newVehicleStatus" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="newVehicleUnit">Assigned Unit</label>
                            <select id="newVehicleUnit" class="form-control" required>
                                <option value="">Select Unit</option>
                                <option value="1st Battalion">1st Battalion</option>
                                <option value="2nd Battalion">2nd Battalion</option>
                                <option value="3rd Battalion">3rd Battalion</option>
                                <option value="Supply Unit">Supply Unit</option>
                                <option value="Medical Unit">Medical Unit</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="newVehicleDriver">Assigned Driver</label>
                            <select id="newVehicleDriver" class="form-control">
                                <option value="">Select Driver (Optional)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="newWorkTicketNumber">Initial Work Ticket Number</label>
                            <input type="text" id="newWorkTicketNumber" class="form-control" placeholder="e.g. SLA/2026/001">
                        </div>
                        <div class="form-group">
                            <label for="next_maintenance_date">Next Maintenance Date</label>
                            <input type="date" id="next_maintenance_date" class="form-control">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="newVehicleOdometer">Current Odometer (KM)</label>
                            <input type="number" id="newVehicleOdometer" class="form-control" placeholder="e.g. 5000">
                        </div>
                        <div class="form-group">
                            <label for="newVehicleNextMaintenanceOdometer">Next Maintenance Odometer (KM)</label>
                            <input type="number" id="newVehicleNextMaintenanceOdometer" class="form-control" placeholder="e.g. 10000">
                        </div>
                    </div>
                    <!-- Images at bottom -->
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-top:20px;">
                        <div class="image-upload-container">
                            <h4><i class="fas fa-book"></i> Vehicle Book Image</h4>
                            <div class="image-preview" id="vehicleImagePreview"
                                onclick="document.getElementById('vehicleImageInput').click()">
                                <i class="fas fa-book"></i>
                                <span>Click to upload</span>
                            </div>
                            <input type="file" id="vehicleImageInput" accept="image/*" style="display:none;">
                            <div style="text-align:center;">
                                <button type="button" class="upload-btn"
                                    onclick="document.getElementById('vehicleImageInput').click()">
                                    <i class="fas fa-upload"></i> Choose Image
                                </button>
                            </div>
                            <small style="color:var(--text-gray);display:block;text-align:center;">Max 2MB (JPG,
                                PNG)</small>
                        </div>
                        <div class="image-upload-container">
                            <h4><i class="fas fa-truck"></i> Vehicle Image</h4>
                            <div class="image-preview" id="vehiclePhotoPreview"
                                onclick="document.getElementById('vehiclePhotoInput').click()">
                                <i class="fas fa-truck"></i>
                                <span>Click to upload</span>
                            </div>
                            <input type="file" id="vehiclePhotoInput" accept="image/*" style="display:none;">
                            <div style="text-align:center;">
                                <button type="button" class="upload-btn"
                                    onclick="document.getElementById('vehiclePhotoInput').click()">
                                    <i class="fas fa-upload"></i> Choose Image
                                </button>
                            </div>
                            <small style="color:var(--text-gray);display:block;text-align:center;">Max 2MB (JPG,
                                PNG)</small>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn" style="margin-top:20px;"><i class="fas fa-save"></i> SAVE
                        VEHICLE</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Vehicle Modal -->
    <div class="modal" id="editVehicleModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-edit"></i> EDIT VEHICLE</h3>
                <button class="close-modal" id="closeEditVehicleModal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editVehicleForm">
                    <input type="hidden" id="editVehicleId">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editVehicleType">Vehicle Category</label>
                            <select id="editVehicleType" class="form-control" required>
                                <option value="">Select Category</option>
                                <option value="light">Light Vehicles</option>
                                <option value="heavy">Heavy Vehicles</option>
                                <option value="threeWheel">Three-Wheelers</option>
                                <option value="motorcycle">Motorcycles</option>
                                <option value="apc">APCs</option>
                                <option value="utility">Utility Vehicles</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editVehicleModel">Model</label>
                            <input type="text" id="editVehicleModel" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editVehicleReg">Registration</label>
                            <input type="text" id="editVehicleReg" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editVehicleFuelType">Fuel Type</label>
                            <select id="editVehicleFuelType" class="form-control" required>
                                <option value="diesel">Diesel</option>
                                <option value="petrol">Petrol</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editVehicleFuelLevel">Fuel Level (%)</label>
                            <input type="number" id="editVehicleFuelLevel" class="form-control" min="0" max="100"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="editVehicleStatus">Status</label>
                            <select id="editVehicleStatus" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editVehicleOdometer">Current Odometer (KM)</label>
                            <input type="number" id="editVehicleOdometer" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editVehicleNextMaintenanceOdometer">Next Maint. Odometer (KM)</label>
                            <input type="number" id="editVehicleNextMaintenanceOdometer" class="form-control">
                        </div>
                    </div>

                    <button type="submit" class="submit-btn"><i class="fas fa-save"></i> UPDATE VEHICLE</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Driver Modal -->
    <div class="modal" id="addDriverModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-user-tie"></i> ADD NEW DRIVER</h3>
                <button class="close-modal" id="closeDriverModal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addDriverForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="newDriverId">Driver ID</label>
                            <input type="text" id="newDriverId" class="form-control" placeholder="e.g. D-9999" required>
                        </div>
                        <div class="form-group">
                            <label for="newDriverFirstName">First Name</label>
                            <input type="text" id="newDriverFirstName" class="form-control" placeholder="e.g. Janaka"
                                required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="newDriverLastName">Last Name</label>
                            <input type="text" id="newDriverLastName" class="form-control" placeholder="e.g. Perera"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="newDriverRank">Rank</label>
                            <select id="newDriverRank" class="form-control" required>
                                <option value="">Select Rank</option>
                                <option value="Private">Private</option>
                                <option value="Lance Corporal">Lance Corporal</option>
                                <option value="Corporal">Corporal</option>
                                <option value="Sergeant">Sergeant</option>
                                <option value="Staff Sergeant">Staff Sergeant</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="newDriverLicense">License Class</label>
                            <select id="newDriverLicense" class="form-control" required>
                                <option value="">Select License</option>
                                <option value="A">Class A (All Vehicles)</option>
                                <option value="B">Class B (Heavy Vehicles)</option>
                                <option value="C">Class C (Light Vehicles)</option>
                                <option value="D">Class D (Motorcycles)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="newDriverLicenseExpiry">License Expiry Date</label>
                            <input type="date" id="newDriverLicenseExpiry" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="newDriverPhone">Phone Number</label>
                            <input type="text" id="newDriverPhone" class="form-control" placeholder="+94 76 XXX XXXX"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="newDriverWhatsapp">WhatsApp Number</label>
                            <input type="text" id="newDriverWhatsapp" class="form-control" placeholder="+94 76 XXX XXXX"
                                required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="newDriverVehicle">Assigned Vehicle(s)</label>
                            <select id="newDriverVehicle" class="form-control" multiple>
                                <!-- Options will be populated dynamically -->
                            </select>
                            <small style="color: var(--text-gray);">Hold Ctrl/Cmd to select multiple</small>
                        </div>
                        <div class="form-group">
                            <label for="newDriverStatus">Initial Status</label>
                            <select id="newDriverStatus" class="form-control" required>
                                <option value="idle">Idle (Available)</option>
                                <option value="on_duty">On Duty</option>
                                <option value="off">Off Duty</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="newDriverEmail">Email Address (Optional)</label>
                        <input type="email" id="newDriverEmail" class="form-control" placeholder="driver@army.lk">
                    </div>

                    <div class="form-group">
                        <label for="newDriverNotes">Notes (Optional)</label>
                        <textarea id="newDriverNotes" class="form-control" rows="2"
                            placeholder="Any additional information..."></textarea>
                    </div>

                    <button type="submit" class="submit-btn"><i class="fas fa-save"></i> SAVE DRIVER</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Driver Modal -->
    <div class="modal" id="editDriverModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-edit"></i> EDIT DRIVER</h3>
                <button class="close-modal" id="closeEditDriverModal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editDriverForm">
                    <input type="hidden" id="editDriverId">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editDriverFirstName">First Name</label>
                            <input type="text" id="editDriverFirstName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editDriverLastName">Last Name</label>
                            <input type="text" id="editDriverLastName" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editDriverRank">Rank</label>
                            <select id="editDriverRank" class="form-control" required>
                                <option value="">Select Rank</option>
                                <option value="Private">Private</option>
                                <option value="Lance Corporal">Lance Corporal</option>
                                <option value="Corporal">Corporal</option>
                                <option value="Sergeant">Sergeant</option>
                                <option value="Staff Sergeant">Staff Sergeant</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editDriverLicenseExpiry">License Expiry Date</label>
                            <input type="date" id="editDriverLicenseExpiry" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editDriverPhone">Phone Number</label>
                            <input type="text" id="editDriverPhone" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editDriverWhatsapp">WhatsApp Number</label>
                            <input type="text" id="editDriverWhatsapp" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editDriverStatus">Status</label>
                            <select id="editDriverStatus" class="form-control" required>
                                <option value="idle">Idle (Available)</option>
                                <option value="on_duty">On Duty</option>
                                <option value="off">Off Duty</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn"><i class="fas fa-save"></i> UPDATE DRIVER</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Check Out Modal -->
    <div class="modal" id="checkOutModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-sign-out-alt"></i> VEHICLE CHECK OUT</h3>
                <button class="close-modal" id="closeCheckOutModal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="checkOutForm">
                    <div class="form-group">
                        <label for="checkOutVehicle">Select Vehicle</label>
                        <div style="display:flex;gap:8px;">
                            <select id="checkOutVehicle" class="form-control" required>
                                <option value="">Select Vehicle</option>
                            </select>
                            <button type="button" class="bio-btn" onclick="startVehicleScanner('checkout')" title="Scan vehicle QR/barcode"><i class="fas fa-qrcode"></i> SCAN</button>
                        </div>
                        <small id="checkOutVehicleDetails" style="color:var(--text-gray);"></small>
                    </div>

                    <div class="form-group">
                        <label for="checkOutDriver">Select Driver</label>
                        <select id="checkOutDriver" class="form-control" required>
                            <option value="">Select Driver</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="destination">Destination</label>
                        <input type="text" id="destination" class="form-control" placeholder="Enter destination"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="purpose">Purpose</label>
                        <select id="purpose" class="form-control" required>
                            <option value="mission">Mission/Operation</option>
                            <option value="training">Training Exercise</option>
                            <option value="transport">Transport</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="expectedReturn">Expected Return Time</label>
                        <input type="datetime-local" id="expectedReturn" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="authorizingOfficer">Authorizing Officer</label>
                        <select id="authorizingOfficer" class="form-control" required>
                            <option value="">Select Officer</option>
                            <option value="Maj. Perera">Maj. Perera</option>
                            <option value="Capt. Silva">Capt. Silva</option>
                            <option value="Lt. Fernando">Lt. Fernando</option>
                            <option value="Col. Wijesinghe">Col. Wijesinghe</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="authorizingOfficerPhone">Authorized Officer Mobile (SMS)</label>
                        <input type="tel" id="authorizingOfficerPhone" class="form-control" placeholder="94771234567" pattern="[0-9+ ]{9,16}" required>
                    </div>

                    <div class="form-group">
                        <label for="checkOutOdometer">Starting Odometer (KM)</label>
                        <input type="number" id="checkOutOdometer" class="form-control" placeholder="Current KM reading"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="officerPassword">Officer Authorization Code</label>
                        <input type="password" id="officerPassword" class="form-control"
                            placeholder="Enter authorization code or use biometrics" required>
                        <small style="color: var(--text-gray);"></small>
                        <button type="button" class="bio-btn" onclick="startBiometricScan('officerPassword')">
                            <i class="fas fa-fingerprint"></i> Scan Fingerprint / RFID
                        </button>
                    </div>

                    <div class="form-group">
                        <label for="additionalNotes">Additional Notes</label>
                        <textarea id="additionalNotes" class="form-control" rows="3"
                            placeholder="Any additional information..."></textarea>
                    </div>

                    <div class="officer-signature">
                        <div class="signature-box" id="signatureDisplay">
                            <i class="fas fa-pen"></i> Officer signature will appear here
                        </div>
                        <span class="auth-badge">AUTHORIZED</span>
                    </div>

                    <button type="submit" class="submit-btn" style="width: 100%;">
                        <i class="fas fa-check-circle"></i> AUTHORIZE & CHECK OUT
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Check In Modal -->
    <div class="modal" id="checkInModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-sign-in-alt"></i> VEHICLE CHECK IN</h3>
                <button class="close-modal" id="closeCheckInModal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="checkInForm">
                    <input type="hidden" id="checkInMovementId">

                    <div class="form-group">
                        <label for="checkInVehicle">Vehicle</label>
                        <div style="display:flex;gap:8px;">
                            <input type="text" id="checkInVehicle" class="form-control" readonly>
                            <button type="button" class="bio-btn" onclick="startVehicleScanner('checkin')" title="Scan vehicle QR/barcode"><i class="fas fa-qrcode"></i> SCAN</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="checkInDriver">Driver</label>
                        <input type="text" id="checkInDriver" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="checkInTime">Check In Time</label>
                        <input type="datetime-local" id="checkInTime" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="checkInOdometer">Closing Odometer (KM)</label>
                        <input type="number" id="checkInOdometer" class="form-control" placeholder="New KM reading"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="fuelLevel">Fuel Level (%)</label>
                        <input type="number" id="fuelLevel" class="form-control" min="0" max="100" required>
                    </div>

                    <div class="form-group">
                        <label for="vehicleCondition">Vehicle Condition</label>
                        <select id="vehicleCondition" class="form-control" required>
                            <option value="good">Good</option>
                            <option value="fair">Fair</option>
                            <option value="needs_maintenance">Needs Maintenance</option>
                            <option value="damaged">Damaged</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="returnNotes">Return Notes</label>
                        <textarea id="returnNotes" class="form-control" rows="3"
                            placeholder="Any issues or notes..."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="checkInOfficer">Authorizing Officer</label>
                        <select id="checkInOfficer" class="form-control" required>
                            <option value="">Select Officer</option>
                            <option value="Maj. Perera">Maj. Perera</option>
                            <option value="Capt. Silva">Capt. Silva</option>
                            <option value="Lt. Fernando">Lt. Fernando</option>
                            <option value="Col. Wijesinghe">Col. Wijesinghe</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="checkInPassword">Officer Authorization Code</label>
                        <input type="password" id="checkInPassword" class="form-control"
                            placeholder="Enter authorization code or use biometrics" required>
                        <small style="color: var(--text-gray);"></small>
                        <button type="button" class="bio-btn" onclick="startBiometricScan('checkInPassword')">
                            <i class="fas fa-fingerprint"></i> Scan Fingerprint / RFID
                        </button>
                    </div>

                    <button type="submit" class="submit-btn" style="width: 100%;">
                        <i class="fas fa-check-circle"></i> AUTHORIZE & CHECK IN
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Fuel Stock Modal -->
    <div class="modal" id="fuelStockModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-oil-can"></i> MANAGE FUEL STOCK</h3>
                <button class="close-modal" id="closeFuelStockModal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="editStockDiesel">Diesel Stock (Liters)</label>
                        <input type="number" id="editStockDiesel" class="form-control" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="editStockPetrol">Petrol Stock (Liters)</label>
                        <input type="number" id="editStockPetrol" class="form-control" min="0" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="editDieselThreshold">Diesel Low Threshold</label>
                        <input type="number" id="editDieselThreshold" class="form-control" min="0"
                            placeholder="Alert when below this level">
                    </div>
                    <div class="form-group">
                        <label for="editPetrolThreshold">Petrol Low Threshold</label>
                        <input type="number" id="editPetrolThreshold" class="form-control" min="0"
                            placeholder="Alert when below this level">
                    </div>
                </div>

                <div class="form-group">
                    <label for="editStockNotes">Stock Management Notes</label>
                    <textarea id="editStockNotes" class="form-control" rows="3"
                        placeholder="Add any notes about fuel stock..."></textarea>
                </div>

                <button class="submit-btn" id="saveStockSettingsBtn">
                    <i class="fas fa-save"></i> SAVE STOCK SETTINGS
                </button>
            </div>
        </div>
    </div>

    <!-- Report Preview Modal -->
    <div class="modal" id="reportPreviewModal">
        <div class="modal-content" style="max-width: 900px;">
            <div class="modal-header">
                <h3><i class="fas fa-file-pdf"></i> REPORT PREVIEW</h3>
                <button class="close-modal" id="closeReportModal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="reportPreviewContent"
                    style="background: white; color: black; padding: 30px; border-radius: 5px; font-family: Arial, sans-serif; max-height: 60vh; overflow-y: auto;">
                </div>
                <div class="report-sharing" style="margin-top: 20px; justify-content: center;">
                    <button class="share-btn pdf" onclick="downloadPDF()">
                        <i class="fas fa-download"></i> Download PDF
                    </button>
                    <button class="share-btn" onclick="sharePreviewWhatsApp()">
                        <i class="fab fa-whatsapp"></i> Share via WhatsApp
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Preview Modal -->
    <div class="modal image-preview-modal" id="imagePreviewModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-image"></i> VEHICLE IMAGE</h3>
                <button class="close-modal" id="closeImageModal">&times;</button>
            </div>
            <div class="modal-body" style="text-align: center;">
                <img id="fullSizeImage" src="" alt="Vehicle Image" class="full-image">
                <p id="imageVehicleInfo" style="margin-top: 15px; color: var(--army-yellow);"></p>
            </div>
        </div>
    </div>

    <!-- Biometric Scan Modal -->
    <div class="modal" id="biometricModal" style="z-index: 1100;">
        <div class="modal-content" style="max-width: 400px; text-align: center;">
            <div class="modal-header">
                <h3><i class="fas fa-fingerprint"></i> BIOMETRIC AUTHENTICATION</h3>
                <button class="close-modal" onclick="document.getElementById('biometricModal').style.display='none'">&times;</button>
            </div>
            <div class="modal-body">
                <p>Please place your finger on the scanner or tap your RFID card.</p>
                <div class="scan-animation">
                    <i class="fas fa-fingerprint"></i>
                    <div class="scan-line"></div>
                </div>
                <p id="scanStatus" style="color: var(--army-yellow); font-weight: bold; margin-top: 15px;">Waiting for input...</p>
            </div>
        </div>
    </div>

    <script>
        // ========== IN-MEMORY STATE (populated from API) ==========
        const appState = {
            vehicles: [], drivers: [], movements: [], serviceRecords: [],
            fuelTransactions: [], fuelStock: { diesel: 0, petrol: 0, diesel_threshold: 2000, petrol_threshold: 1000 },
            gpsDevices: [], gpsTracking: [],
            vehicleImages: {}
        };

        // Keep the UI permissions in one place so every role gets the same
        // navigation and action rules.
        const ROLE_ACCESS = {
            admin: { pages: ['dashboard', 'inout', 'inventory', 'drivers', 'service', 'fuel', 'efficiency', 'whatsapp', 'gps', 'geofence', 'tracking', 'reports', 'users'], vehicleEdit: true, serviceEdit: true, fuelEdit: true, userManage: true },
            officer: { pages: ['dashboard', 'inout', 'inventory', 'drivers', 'service', 'fuel', 'efficiency', 'whatsapp', 'gps', 'geofence', 'tracking', 'reports', 'users'], vehicleEdit: true, serviceEdit: true, fuelEdit: true, userManage: false },
            mechanic: { pages: ['dashboard', 'inventory', 'service', 'reports', 'users'], vehicleEdit: false, serviceEdit: true, fuelEdit: false, userManage: false },
            fuel_manager: { pages: ['dashboard', 'inventory', 'fuel', 'reports', 'users'], vehicleEdit: false, serviceEdit: false, fuelEdit: true, userManage: false },
            driver: { pages: ['dashboard', 'inout', 'tracking', 'users'], vehicleEdit: false, serviceEdit: false, fuelEdit: false, userManage: false }
        };

        function getRoleAccess(user = window._currentUser) {
            const role = String(user?.role || '').trim().toLowerCase();
            return ROLE_ACCESS[role] || { pages: ['dashboard', 'users'], vehicleEdit: false, serviceEdit: false, fuelEdit: false, userManage: false };
        }

        function canAccessPage(page) {
            return getRoleAccess().pages.includes(page);
        }

        // Compatibility shim so existing code using db.getX() / db.saveX() works unchanged
        const db = {
            getVehicles: () => appState.vehicles,
            saveVehicles: (d) => { appState.vehicles = d; },
            getDrivers: () => appState.drivers,
            saveDrivers: (d) => { appState.drivers = d; },
            getMovements: () => appState.movements,
            saveMovements: (d) => { appState.movements = d; },
            getServiceRecords: () => appState.serviceRecords,
            saveServiceRecords: (d) => { appState.serviceRecords = d; },
            getFuelTransactions: () => appState.fuelTransactions,
            saveFuelTransactions: (d) => { appState.fuelTransactions = d; },
            getFuelStock: () => appState.fuelStock,
            saveFuelStock: (d) => { appState.fuelStock = d; },
            getGPSDevices: () => appState.gpsDevices,
            saveGPSDevices: (d) => { appState.gpsDevices = d; },
            getGPSTracking: () => appState.gpsTracking,
            saveGPSTracking: (d) => { appState.gpsTracking = d; },
            getReports: () => [],
            getVehicleImages: () => appState.vehicleImages,
            saveVehicleImages: (d) => { appState.vehicleImages = d; },
            getUsers: () => []
        };


        // ========== UTILITY FUNCTIONS ==========
        function startBiometricScan(targetInputId) {
            const modal = document.getElementById('biometricModal');
            const status = document.getElementById('scanStatus');
            modal.style.display = 'flex';
            status.textContent = 'Scanning...';
            status.style.color = 'var(--army-yellow)';
            
            // Simulate hardware scan delay
            setTimeout(() => {
                status.textContent = 'Authorization Successful!';
                status.style.color = '#4CAF50';
                
                setTimeout(() => {
                    document.getElementById(targetInputId).value = '';
                    modal.style.display = 'none';
                    showNotification('Biometric authentication verified', 'success');
                }, 1000);
            }, 2500);
        }

        function generateId(prefix) {
            return prefix + Math.floor(Math.random() * 10000).toString().padStart(4, '0');
        }

        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i><span>${message}</span>`;
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 5000);
        }

        function formatDate(date) {
            return new Date(date).toLocaleString();
        }

        function getTimeAgo(date) {
            const seconds = Math.floor((new Date() - new Date(date)) / 1000);
            if (seconds < 60) return seconds + ' seconds ago';
            const minutes = Math.floor(seconds / 60);
            if (minutes < 60) return minutes + ' minutes ago';
            const hours = Math.floor(minutes / 60);
            if (hours < 24) return hours + ' hours ago';
            const days = Math.floor(hours / 24);
            return days + ' days ago';
        }

        function applyUserRole(user) {
            const role = String(user.role || '').trim().toLowerCase();
            const access = getRoleAccess({ ...user, role });
            const isAdmin = role === 'admin';
            const isOfficer = role === 'officer';

            const sidebar = document.querySelector('.sidebar');
            if (sidebar) sidebar.style.display = '';
            const mainContent = document.querySelector('.main-content');
            if (mainContent) {
                mainContent.style.flex = '1';
                mainContent.style.width = '';
            }

            // Restore all pages to default layout
            document.querySelectorAll('.page').forEach(pg => {
                pg.style.display = '';
            });

            // Sidebar and page visibility use the same allow-list.
            document.querySelectorAll('.nav-item').forEach(item => {
                const page = item.dataset.page;
                item.style.display = access.pages.includes(page) ? '' : 'none';
            });
            document.querySelectorAll('.page').forEach(page => {
                page.style.display = access.pages.includes(page.id) ? '' : 'none';
            });

            // Set explicit active tab based on role
            document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
            document.querySelectorAll('.page').forEach(page => page.classList.remove('active'));

            const landingPage = role === 'mechanic' ? 'service' : role === 'fuel_manager' ? 'fuel' : 'dashboard';
            const landingTab = document.querySelector(`.nav-item[data-page="${landingPage}"]`);
            const landingContent = document.getElementById(landingPage);
            if (landingTab) landingTab.classList.add('active');
            if (landingContent) landingContent.classList.add('active');

            document.getElementById('navUsersLabel').textContent = isAdmin ? 'User Management' : 'My Profile';
            document.getElementById('addUserBtn').style.display = isAdmin ? 'inline-flex' : 'none';

            const addVehicleBtn = document.getElementById('addVehicleBtn');
            const addDriverBtn = document.getElementById('addDriverBtn');
            if (addVehicleBtn) addVehicleBtn.style.display = access.vehicleEdit ? '' : 'none';
            if (addDriverBtn) addDriverBtn.style.display = access.vehicleEdit ? '' : 'none';

            document.querySelectorAll('#service .service-form').forEach(form => {
                form.style.display = access.serviceEdit ? '' : 'none';
            });
            const stockForm = document.querySelector('#fuel .stock-update-form');
            const allocationForm = document.querySelector('#fuel .fuel-allocation-form');
            const manageStockBtn = document.getElementById('manageStockBtn');
            if (stockForm) stockForm.style.display = access.fuelEdit ? '' : 'none';
            if (allocationForm) allocationForm.style.display = access.fuelEdit ? '' : 'none';
            if (manageStockBtn) manageStockBtn.style.display = access.fuelEdit ? '' : 'none';

            window._canEdit = access.vehicleEdit;
            window._canEditService = access.serviceEdit;
            window._canEditFuel = access.fuelEdit;
            window._canManageUsers = isAdmin;
        }

        // ========== AUTHENTICATION ==========
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            fetch('api/login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ username, password })
            }).then(r => r.json()).then(res => {
                if (res.success) {
                    const user = res.user;
                    window._currentUser = user; // session-only, no localStorage
                    document.getElementById('userName').textContent = user.full_name || user.username;

                    // Show role badge in header
                    const badge = document.getElementById('userRoleBadge');
                    if (badge) {
                        const roleLabels = {
                            'admin':        { label: 'Admin',        bg: '#D32F2F', color: '#fff',     border: '#D32F2F' },
                            'officer':      { label: 'Officer',      bg: '#1565C0', color: '#fff',     border: '#1565C0' },
                            'mechanic':     { label: 'Mechanic',     bg: '#E65100', color: '#fff',     border: '#E65100' },
                            'fuel_manager': { label: 'Fuel Mgr',    bg: '#6A1B9A', color: '#fff',     border: '#6A1B9A' },
                            'driver':       { label: 'Driver',       bg: '#1B5E20', color: '#ffd700', border: '#ffd700' }
                        };
                        const roleStyle = roleLabels[user.role] || { label: user.role, bg: '#555', color: '#fff', border: '#888' };
                        badge.textContent  = roleStyle.label;
                        badge.style.background   = roleStyle.bg;
                        badge.style.color         = roleStyle.color;
                        badge.style.borderColor   = roleStyle.border;
                        badge.style.display       = 'inline-block';
                    }

                    document.getElementById('loginPage').style.display = 'none';
                    document.getElementById('systemContainer').style.display = 'block';
                    applyUserRole(user);
                    loadAllData();
                    initCharts();
                    // Maps are initialized on tab-click to avoid hidden-element Leaflet bug
                    initLiveDriverTracking();
                    initEmergencyMonitoring();
                    showNotification('Login successful!', 'success');
                } else {
                    showNotification(res.message || 'Invalid credentials', 'error');
                }
            }).catch(() => showNotification('Server unreachable', 'error'));
        });

        document.getElementById('logoutBtn').addEventListener('click', function () {
            if (typeof autoDbLocationInterval !== 'undefined' && autoDbLocationInterval) clearInterval(autoDbLocationInterval);
            if (typeof mapAutoRefreshInterval !== 'undefined' && mapAutoRefreshInterval) clearInterval(mapAutoRefreshInterval);
            window._currentUser = null;
            // Reset header badge on logout
            const badge = document.getElementById('userRoleBadge');
            if (badge) { badge.style.display = 'none'; badge.textContent = ''; }
            document.getElementById('userName').textContent = '';
            document.getElementById('systemContainer').style.display = 'none';
            document.getElementById('loginPage').style.display = 'flex';
            showNotification('Logged out', 'info');
        });

        // ========== NAVIGATION ==========
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function () {
                if (!canAccessPage(this.dataset.page)) return;
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
                document.querySelectorAll('.page').forEach(page => page.classList.remove('active'));
                document.getElementById(this.dataset.page).classList.add('active');

                const page = this.dataset.page;
                if (page === 'dashboard') refreshDashboard();
                else if (page === 'inventory') loadVehicles();
                else if (page === 'drivers') loadDrivers();
                else if (page === 'service') loadServiceRecords();
                else if (page === 'fuel') loadFuelData();
                else if (page === 'whatsapp') loadWhatsAppData();
                else if (page === 'gps') {
                    // Init GPS map after page is visible (fixes Leaflet hidden-element bug)
                    setTimeout(() => {
                        if (!map) {
                            initMap();
                        } else {
                            map.invalidateSize();
                        }
                        loadGPSData();
                        // Auto-start browser GPS on entering page
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(function(pos) {
                                const lat = pos.coords.latitude;
                                const lon = pos.coords.longitude;
                                const acc = pos.coords.accuracy;
                                document.getElementById('gpsCoordinates').textContent = lat.toFixed(6) + ', ' + lon.toFixed(6);
                                document.getElementById('gpsAccuracy').textContent = acc.toFixed(0);
                                document.getElementById('gpsTime').textContent = new Date().toLocaleTimeString();
                                document.getElementById('gpsSatellites').textContent = Math.floor(Math.random() * 8) + 8;
                                const signal = document.getElementById('gpsSignalIcon');
                                if (signal) signal.className = acc < 20 ? 'gps-signal good' : acc < 50 ? 'gps-signal fair' : 'gps-signal poor';
                                if (map) {
                                    map.setView([lat, lon], 14);
                                    // Remove old self-marker if exists
                                    if (window._selfMarker) map.removeLayer(window._selfMarker);
                                    window._selfMarker = L.marker([lat, lon], {
                                        icon: L.divIcon({
                                            className: '',
                                            html: '<div style="background:#00e676;width:18px;height:18px;border-radius:50%;border:3px solid white;box-shadow:0 0 12px #00e676;"></div>',
                                            iconSize: [18, 18], iconAnchor: [9, 9]
                                        })
                                    }).addTo(map).bindPopup('<b>📍 Your Location</b><br>Accuracy: ' + acc.toFixed(0) + 'm').openPopup();
                                }
                            }, function(err) {
                                showNotification('GPS: ' + err.message, 'error');
                            }, { enableHighAccuracy: true, timeout: 10000 });
                        }
                    }, 200);
                }
                else if (page === 'tracking') {
                    // Init tracking map after page is visible
                    setTimeout(() => {
                        if (!trackingMap) {
                            initTrackingMap();
                        } else {
                            trackingMap.invalidateSize();
                            updateTrackingMarkers();
                        }
                        loadTrackingData();
                    }, 200);
                }
                else if (page === 'inout') loadMovementData();
                else if (page === 'efficiency') loadEfficiencyData();
                else if (page === 'geofence') {
                    setTimeout(() => { initGeofenceMap(); }, 200);
                }
                else if (page === 'reports') loadReportsData();
                else if (page === 'users') loadUsersPage();
            });
        });

        // ========== VEHICLE INVENTORY WITH IMAGES ==========
        function loadVehicles() {
            fetch('api/vehicles.php')
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        appState.vehicles = res.data || [];
                        // Populate vehicleImages from server-stored image_path
                        appState.vehicles.forEach(v => {
                            if (v.image_path && !appState.vehicleImages[v.id]) {
                                appState.vehicleImages[v.id] = v.image_path;
                            }
                        });
                    }
                })
                .finally(() => {
                    renderVehicles();
                    checkMaintenanceAlert();
                    populateAllSelects();
                });
        }

        function checkMaintenanceAlert() {
            const vehicles = db.getVehicles();
            if (!vehicles) return;
            
            vehicles.forEach(v => {
                if (v.next_maintenance_odometer > 0 && v.status !== 'maintenance') {
                    const diff = v.next_maintenance_odometer - (v.current_odometer || 0);
                    if (diff > 0 && diff <= 500) {
                        showNotification(`⚠️ Maintenance Alert: ${v.id} (${v.model}) is within ${diff}km of next service!`, 'warning');
                    } else if (diff <= 0) {
                        showNotification(`🚨 OVERDUE: ${v.id} (${v.model}) needs maintenance immediately!`, 'danger');
                    }
                }
            });
        }

        function renderVehicles() {
            const vehicles = db.getVehicles();
            const drivers = db.getDrivers();
            const vehicleImages = db.getVehicleImages();
            const tbody = document.getElementById('vehicleTableBody');
            if (!tbody) return;
            tbody.innerHTML = '';
            if (!vehicles || vehicles.length === 0) {
                tbody.innerHTML = '<tr><td colspan="11" style="text-align: center; padding: 30px;">No vehicles found. Click "ADD NEW VEHICLE" to add your first vehicle.</td></tr>';
                return;
            }

            vehicles.forEach(v => {
                const row = tbody.insertRow();
                const driver = drivers.find(d => d.id === v.assigned_driver);
                const driverName = driver ? driver.first_name + ' ' + driver.last_name : 'Not Assigned';
                const vehicleImage = vehicleImages[v.id] || null;

                let fuelColor = v.fuel_level > 50 ? '#4CAF50' : v.fuel_level > 20 ? '#FF9800' : '#D32F2F';
                let statusClass = v.status === 'active' ? 'success' : v.status === 'maintenance' ? 'warning' : 'danger';
                let inOutClass = v.in_out_status === 'idle' ? 'success' : v.in_out_status === 'out' ? 'warning' : 'info';
                let geofenceBadge = v.is_geofence_violation == 1 ? `<br><span class="status-badge status-danger" style="margin-top:5px; animation: pulse 1s infinite;"><i class="fas fa-exclamation-triangle"></i> RED ZONE</span>` : '';

                let maintenanceBadge = '';
                if (v.next_maintenance_odometer > 0) {
                    const diff = v.next_maintenance_odometer - (v.current_odometer || 0);
                    if (diff > 0 && diff <= 500) {
                        maintenanceBadge = `<br><span class="status-badge status-warning" style="font-size:0.7rem; margin-top:5px;"><i class="fas fa-tools"></i> Service in ${diff}km</span>`;
                    } else if (diff <= 0) {
                        maintenanceBadge = `<br><span class="status-badge status-danger" style="font-size:0.7rem; margin-top:5px; animation: pulse 1s infinite;"><i class="fas fa-exclamation-circle"></i> SERVICE OVERDUE</span>`;
                    } else {
                        maintenanceBadge = `<br><span class="status-badge status-info" style="font-size:0.7rem; margin-top:5px;"><i class="fas fa-tachometer-alt"></i> ${v.current_odometer || 0} / ${v.next_maintenance_odometer} km</span>`;
                    }
                } else if (v.current_odometer > 0) {
                    maintenanceBadge = `<br><span class="status-badge status-info" style="font-size:0.7rem; margin-top:5px;"><i class="fas fa-tachometer-alt"></i> ${v.current_odometer} km</span>`;
                }

                // Image cell with click handler
                const imageCell = document.createElement('td');
                if (vehicleImage) {
                    imageCell.innerHTML = `<img src="${vehicleImage}" class="vehicle-image-cell" onclick="showFullImage('${v.id}')" title="Click to view full image">`;
                } else {
                    imageCell.innerHTML = `<div class="vehicle-image-thumb" style="display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.3);" onclick="showFullImage('${v.id}')"><i class="fas fa-image" style="font-size: 1.5rem; color: var(--army-tan);"></i></div>`;
                }
                row.appendChild(imageCell);

                row.innerHTML += `
                    <td><strong>${v.id}</strong>${geofenceBadge}</td>
                    <td>${v.type || 'N/A'}</td>
                    <td>${v.model || 'N/A'}${maintenanceBadge}</td>
                    <td>${v.registration || 'N/A'}</td>
                    <td>
                        <div class="fuel-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: ${v.fuel_level || 0}%; background: ${fuelColor};"></div>
                            </div>
                            <span>${v.fuel_level || 0}%</span>
                        </div>
                    </td>
                    <td><span class="status-badge status-${statusClass}">${(v.status || 'unknown').toUpperCase()}</span></td>
                    <td>${v.unit || 'N/A'}<br><small>${driverName}</small></td>
                    <td>${v.work_ticket || 'N/A'}</td>
                    <td><span class="status-badge status-${inOutClass}">${(v.in_out_status || 'unknown').toUpperCase()}</span></td>
                    <td>
                        <div class="action-buttons">
                            ${window._canEdit ? `<button class="edit-btn" onclick="editVehicle('${v.id}')" title="Edit Vehicle"><i class="fas fa-edit"></i></button>` : ''}
                            ${window._canEdit ? `<button class="delete-btn" onclick="deleteVehicle('${v.id}')" title="Delete Vehicle"><i class="fas fa-trash"></i></button>` : ''}
                            <button class="fuel-btn" onclick="openFuelAllocation('${v.id}')" title="Allocate Fuel"><i class="fas fa-gas-pump"></i></button>
                            <button class="in-out-btn" onclick="openCheckOutModal('${v.id}')" title="Check Out"><i class="fas fa-sign-out-alt"></i></button>
                            <button class="barcode-btn" onclick="generateVehicleBarcode('${v.id}')" title="Create / Print Barcode"><i class="fas fa-barcode"></i></button>
                            <button class="image-btn" onclick="uploadVehicleImage('${v.id}')" title="Upload Image"><i class="fas fa-camera"></i></button>
                        </div>
                    </td>
                `;
            });
        }

        window.generateVehicleBarcode = function (vehicleId) {
            const vehicle = appState.vehicles.find(v => String(v.id) === String(vehicleId));
            if (!vehicle) {
                showNotification('Vehicle details are not available.', 'error');
                return;
            }

            const popup = window.open('', '_blank', 'width=620,height=520');
            if (!popup) {
                showNotification('Please allow pop-ups to create the barcode.', 'error');
                return;
            }

            const safe = value => String(value || '').replace(/[&<>"']/g, char => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
            }[char]));
            popup.document.write(`<!doctype html>
                <html><head><title>Vehicle Barcode - ${safe(vehicle.id)}</title>
                <style>body{font-family:Arial;text-align:center;padding:35px;color:#111}svg{max-width:100%;height:auto}.meta{margin:15px 0;font-size:18px}button{padding:10px 20px;background:#006837;color:#fff;border:0;border-radius:4px;cursor:pointer}@media print{button{display:none}}</style>
                </head><body>
                <h2>ARMY VMS - VEHICLE IDENTIFICATION</h2>
                <svg id="vehicleBarcode"></svg>
                <div class="meta"><strong>ID:</strong> ${safe(vehicle.id)}<br><strong>Registration:</strong> ${safe(vehicle.registration)}<br><strong>Model:</strong> ${safe(vehicle.model)}</div>
                <button onclick="window.print()">PRINT BARCODE</button>
                <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"><\/script>
                <script>JsBarcode('#vehicleBarcode', ${JSON.stringify(String(vehicle.id))}, {format:'CODE128',displayValue:true,fontSize:22,height:90,margin:12});<\/script>
                </body></html>`);
            popup.document.close();
        };

        // Show full image
        window.showFullImage = function (vehicleId) {
            const vehicleImages = db.getVehicleImages();
            const vehicles = db.getVehicles();
            const vehicle = vehicles.find(v => v.id === vehicleId);
            const image = vehicleImages[vehicleId];

            if (image) {
                document.getElementById('fullSizeImage').src = image;
                document.getElementById('imageVehicleInfo').textContent = `Vehicle: ${vehicleId} - ${vehicle ? vehicle.model : ''}`;
                document.getElementById('imagePreviewModal').style.display = 'flex';
            } else {
                showNotification('No image available for this vehicle', 'info');
            }
        };

        // Upload vehicle image
        window.uploadVehicleImage = function (vehicleId) {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.onchange = function (e) {
                const file = e.target.files[0];
                if (file) {
                    if (file.size > 2 * 1024 * 1024) {
                        showNotification('Image size should be less than 2MB', 'error');
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function (event) {
                        appState.vehicleImages[vehicleId] = event.target.result;
                        loadVehicles();
                        showNotification('Image uploaded successfully', 'success');
                    };
                    reader.readAsDataURL(file);
                }
            };
            input.click();
        };

        // Edit Vehicle
        window.editVehicle = function (id) {
            const vehicles = db.getVehicles();
            const vehicle = vehicles.find(v => v.id === id);
            if (vehicle) {
                document.getElementById('editVehicleId').value = vehicle.id;
                document.getElementById('editVehicleType').value = vehicle.category || 'light';
                document.getElementById('editVehicleModel').value = vehicle.model || '';
                document.getElementById('editVehicleReg').value = vehicle.registration || '';
                document.getElementById('editVehicleFuelType').value = vehicle.fuel_type || 'diesel';
                document.getElementById('editVehicleFuelLevel').value = vehicle.fuel_level || 0;
                document.getElementById('editVehicleStatus').value = vehicle.status || 'active';
                document.getElementById('editVehicleOdometer').value = vehicle.current_odometer || 0;
                document.getElementById('editVehicleNextMaintenanceOdometer').value = vehicle.next_maintenance_odometer || 0;
                document.getElementById('editVehicleModal').style.display = 'flex';
            } else {
                showNotification('Vehicle not found', 'error');
            }
        };

        // Delete Vehicle
        window.deleteVehicle = function (id) {
            if (confirm('Are you sure you want to delete this vehicle? This action cannot be undone.')) {
                fetch(`api/vehicles.php?id=${id}`, { method: 'DELETE' })
                    .finally(() => { loadVehicles(); refreshDashboard(); });
                showNotification('Vehicle deleted successfully', 'success');
            }
        };

        // Open Fuel Allocation
        window.openFuelAllocation = function (id) {
            document.getElementById('fuelVehicleSelect').value = id;
            document.querySelector('[data-page="fuel"]').click();
        };

        // Add Vehicle Form with Image
        document.getElementById('addVehicleBtn').addEventListener('click', function () {
            populateDriverSelect('newVehicleDriver', false);
            document.getElementById('vehicleImagePreview').innerHTML = '<i class="fas fa-book"></i><span>Click to upload</span>';
            document.getElementById('vehiclePhotoPreview').innerHTML = '<i class="fas fa-truck"></i><span>Click to upload</span>';
            document.getElementById('vehicleIdError').style.display = 'none';
            document.getElementById('vehicleRegError').style.display = 'none';
            document.getElementById('addVehicleModal').style.display = 'flex';
        });

        // Image preview for add vehicle (book image)
        document.getElementById('vehicleImageInput').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            if (file.size > 2 * 1024 * 1024) { showNotification('Image size should be less than 2MB', 'error'); this.value = ''; return; }
            const reader = new FileReader();
            reader.onload = ev => { document.getElementById('vehicleImagePreview').innerHTML = `<img src="${ev.target.result}" alt="Preview">`; };
            reader.readAsDataURL(file);
        });

        // Image preview for vehicle photo
        document.getElementById('vehiclePhotoInput').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            if (file.size > 2 * 1024 * 1024) { showNotification('Image size should be less than 2MB', 'error'); this.value = ''; return; }
            const reader = new FileReader();
            reader.onload = ev => { document.getElementById('vehiclePhotoPreview').innerHTML = `<img src="${ev.target.result}" alt="Preview">`; };
            reader.readAsDataURL(file);
        });

        document.getElementById('addVehicleForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const vehicleId = document.getElementById('newVehicleId').value.trim();
            const registration = document.getElementById('newVehicleReg').value.trim();
            const maintenanceDate = document.getElementById('next_maintenance_date').value;

            // Clear previous inline errors
            document.getElementById('vehicleIdError').style.display = 'none';
            document.getElementById('vehicleRegError').style.display = 'none';

            // Frontend duplicate check
            if (appState.vehicles.some(v => v.id === vehicleId)) {
                document.getElementById('vehicleIdError').textContent = 'Vehicle ID already exists.';
                document.getElementById('vehicleIdError').style.display = 'block';
                return;
            }
            if (appState.vehicles.some(v => v.registration === registration)) {
                document.getElementById('vehicleRegError').textContent = 'Registration Number already exists.';
                document.getElementById('vehicleRegError').style.display = 'block';
                return;
            }

            const newVehicle = {
                id: vehicleId,
                type: document.getElementById('newVehicleType').selectedOptions[0]?.text || 'Light Vehicle',
                category: document.getElementById('newVehicleType').value || 'light',
                model: document.getElementById('newVehicleModel').value || 'Unknown',
                registration,
                year: parseInt(document.getElementById('newVehicleYear').value) || 2024,
                fuel_type: document.getElementById('newVehicleFuelType').value || 'diesel',
                fuel_capacity: parseInt(document.getElementById('newVehicleFuelCapacity').value) || 80,
                fuel_level: 100,
                status: document.getElementById('newVehicleStatus').value || 'active',
                unit: document.getElementById('newVehicleUnit').value || 'Unassigned',
                assigned_driver: document.getElementById('newVehicleDriver').value || null,
                work_ticket: document.getElementById('newWorkTicketNumber')?.value || '',
                work_ticket_status: 'active',
                in_out_status: 'idle',
                latitude: 6.9271 + (Math.random() - 0.5) * 0.1,
                longitude: 79.8612 + (Math.random() - 0.5) * 0.1,
                location_accuracy: 10,
                location_source: 'gps',
                location_timestamp: new Date().toISOString(),
                current_odometer: parseInt(document.getElementById('newVehicleOdometer')?.value) || 0,
                next_maintenance_odometer: parseInt(document.getElementById('newVehicleNextMaintenanceOdometer')?.value) || 0,
                next_maintenance_date: document.getElementById('next_maintenance_date')?.value || null
            };

            const sendToServer = (bookBase64, photoBase64) => {
                const payload = { ...newVehicle };
                if (bookBase64) {
                    payload.image_base64 = bookBase64;
                    appState.vehicleImages[newVehicle.id] = bookBase64;
                }
                if (photoBase64) payload.vehicle_photo_base64 = photoBase64;
                fetch('api/vehicles.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                }).then(r => r.json()).then(res => {
                    if (res.success) {
                        document.getElementById('addVehicleModal').style.display = 'none';
                        this.reset();
                        loadVehicles();
                        refreshDashboard();
                        showNotification('Vehicle added successfully', 'success');
                    } else {
                        // Handle DB-level duplicate errors
                        if (res.message && res.message.toLowerCase().includes('id')) {
                            document.getElementById('vehicleIdError').textContent = res.message;
                            document.getElementById('vehicleIdError').style.display = 'block';
                        } else if (res.message && res.message.toLowerCase().includes('registration')) {
                            document.getElementById('vehicleRegError').textContent = res.message;
                            document.getElementById('vehicleRegError').style.display = 'block';
                        } else {
                            showNotification(res.message || 'Server error', 'error');
                        }
                    }
                }).catch(() => showNotification('Server unreachable', 'error'));
            };

            const bookInput = document.getElementById('vehicleImageInput');
            const photoInput = document.getElementById('vehiclePhotoInput');

            const readFile = (input) => new Promise(resolve => {
                if (!input.files.length) return resolve(null);
                const reader = new FileReader();
                reader.onload = ev => resolve(ev.target.result);
                reader.readAsDataURL(input.files[0]);
            });

            Promise.all([readFile(bookInput), readFile(photoInput)])
                .then(([bookB64, photoB64]) => sendToServer(bookB64, photoB64));
        });

        // Edit Vehicle Form
        document.getElementById('editVehicleForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const id = document.getElementById('editVehicleId').value;
            const updated = {
                category: document.getElementById('editVehicleType').value,
                type: document.getElementById('editVehicleType').selectedOptions[0]?.text,
                model: document.getElementById('editVehicleModel').value,
                registration: document.getElementById('editVehicleReg').value,
                fuel_type: document.getElementById('editVehicleFuelType').value,
                fuel_level: parseInt(document.getElementById('editVehicleFuelLevel').value) || 0,
                status: document.getElementById('editVehicleStatus').value,
                current_odometer: parseInt(document.getElementById('editVehicleOdometer').value) || 0,
                next_maintenance_odometer: parseInt(document.getElementById('editVehicleNextMaintenanceOdometer').value) || 0
            };
            fetch(`api/vehicles.php?id=${id}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(updated)
            }).then(r => r.json()).then(res => {
                if (res.success) { loadVehicles(); refreshDashboard(); }
                else showNotification(res.message || 'Server error', 'error');
            }).catch(() => { });
            document.getElementById('editVehicleModal').style.display = 'none';
            showNotification('Vehicle updated successfully', 'success');
        });

        // ========== DRIVER FUNCTIONS ==========
        function loadDrivers() {
            fetch('api/drivers.php')
                .then(r => r.json())
                .then(res => { if (res.success) appState.drivers = res.data || []; })
                .catch(() => { })
                .finally(() => renderDrivers());
        }

        function renderDrivers() {
            const drivers = db.getDrivers();
            const vehicles = db.getVehicles();
            const tbody = document.getElementById('driverTableBody');
            if (!tbody) return;
            tbody.innerHTML = '';
            if (!drivers || drivers.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" style="text-align: center; padding: 30px;">No drivers found. Click "ADD NEW DRIVER" to add your first driver.</td></tr>';
                return;
            }

            drivers.forEach(d => {
                const row = tbody.insertRow();
                const today = new Date();
                const expiry = new Date(d.license_expiry);
                const daysLeft = Math.ceil((expiry - today) / (1000 * 60 * 60 * 24));

                let assignedVehicle = 'None';
                try {
                    const assignedIds = JSON.parse(d.assigned_vehicles || '[]');
                    if (assignedIds.length > 0) {
                        const vehicleNames = assignedIds.map(id => {
                            const v = vehicles.find(v => v.id === id);
                            return v ? `${v.id} (${v.model})` : id;
                        });
                        assignedVehicle = vehicleNames.join(', ');
                    }
                } catch (e) {
                    assignedVehicle = 'Error parsing assignments';
                }

                let statusClass = d.status === 'idle' ? 'success' : d.status === 'on_duty' ? 'warning' : 'danger';

                row.innerHTML = `
                    <td><strong>${d.id}</strong></td>
                    <td>${d.first_name || ''} ${d.last_name || ''}</td>
                    <td>${d.rank || 'N/A'}</td>
                    <td>Class ${d.license_class || 'N/A'}</td>
                    <td>
                        ${d.license_expiry || 'N/A'}
                        ${daysLeft < 30 && daysLeft > 0 ? `<br><span class="status-badge status-warning">Expires in ${daysLeft} days</span>` : ''}
                        ${daysLeft <= 0 ? `<br><span class="status-badge status-danger">EXPIRED</span>` : ''}
                    </td>
                    <td>${assignedVehicle}</td>
                    <td><span class="status-badge status-${statusClass}">${(d.status || 'unknown').toUpperCase()}</span></td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <button class="call-btn" onclick="window.open('tel:${d.phone || ''}')" ${!d.phone ? 'disabled' : ''}><i class="fas fa-phone"></i></button>
                            <button class="whatsapp-btn" onclick="window.open('https://wa.me/${(d.whatsapp || '').replace(/\D/g, '')}')" ${!d.whatsapp ? 'disabled' : ''}><i class="fab fa-whatsapp"></i></button>
                        </div>
                    </td>
                    <td>
                        <div class="action-buttons">
                            ${window._canEdit ? `<button class="edit-btn" onclick="editDriver('${d.id}')"><i class="fas fa-edit"></i></button>` : ''}
                            ${window._canEdit ? `<button class="delete-btn" onclick="deleteDriver('${d.id}')"><i class="fas fa-trash"></i></button>` : ''}
                        </div>
                    </td>
                `;
            });
        }

        window.editDriver = function (id) {
            const drivers = db.getDrivers();
            const driver = drivers.find(d => d.id === id);
            if (driver) {
                document.getElementById('editDriverId').value = driver.id;
                document.getElementById('editDriverFirstName').value = driver.first_name || '';
                document.getElementById('editDriverLastName').value = driver.last_name || '';
                document.getElementById('editDriverRank').value = driver.rank || '';
                document.getElementById('editDriverLicenseExpiry').value = driver.license_expiry || '';
                document.getElementById('editDriverPhone').value = driver.phone || '';
                document.getElementById('editDriverWhatsapp').value = driver.whatsapp || '';
                document.getElementById('editDriverStatus').value = driver.status || 'idle';
                document.getElementById('editDriverModal').style.display = 'flex';
            } else {
                showNotification('Driver not found', 'error');
            }
        };

        window.deleteDriver = function (id) {
            if (confirm('Are you sure you want to delete this driver?')) {
                fetch(`api/drivers.php?id=${id}`, { method: 'DELETE' })
                    .finally(() => loadDrivers());
                showNotification('Driver deleted successfully', 'success');
            }
        };

        // Add Driver Form
        document.getElementById('addDriverBtn').addEventListener('click', function () {
            populateVehicleSelectForDriver('newDriverVehicle', true);
            document.getElementById('addDriverModal').style.display = 'flex';
        });

        document.getElementById('addDriverForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const driverId = document.getElementById('newDriverId').value.trim();
            if (!driverId) { showNotification('Driver ID is required', 'error'); return; }

            if (appState.drivers.some(d => d.id === driverId)) {
                showNotification('Driver ID already exists.', 'error'); return;
            }

            const selectedVehicles = Array.from(document.getElementById('newDriverVehicle').selectedOptions).map(o => o.value);

            const newDriver = {
                id: driverId,
                first_name: document.getElementById('newDriverFirstName').value || '',
                last_name: document.getElementById('newDriverLastName').value || '',
                rank: document.getElementById('newDriverRank').value || '',
                license_class: document.getElementById('newDriverLicense').value || '',
                license_expiry: document.getElementById('newDriverLicenseExpiry').value || '',
                phone: document.getElementById('newDriverPhone').value || '',
                whatsapp: document.getElementById('newDriverWhatsapp').value || '',
                email: document.getElementById('newDriverEmail').value || '',
                status: document.getElementById('newDriverStatus').value || 'idle',
                assigned_vehicles: JSON.stringify(selectedVehicles),
                notes: document.getElementById('newDriverNotes').value || '',
                created_at: new Date().toISOString()
            };

            fetch('api/drivers.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(newDriver)
            }).then(r => r.json()).then(res => {
                if (res.success) { loadDrivers(); loadVehicles(); }
                else showNotification(res.message || 'Server error', 'error');
            }).catch(() => showNotification('Server unreachable', 'error'));

            document.getElementById('addDriverModal').style.display = 'none';
            this.reset();
            showNotification('Driver added successfully', 'success');
        });

        // Edit Driver Form
        document.getElementById('editDriverForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const id = document.getElementById('editDriverId').value;
            const updated = {
                first_name: document.getElementById('editDriverFirstName').value,
                last_name: document.getElementById('editDriverLastName').value,
                rank: document.getElementById('editDriverRank').value,
                license_expiry: document.getElementById('editDriverLicenseExpiry').value,
                phone: document.getElementById('editDriverPhone').value,
                whatsapp: document.getElementById('editDriverWhatsapp').value,
                status: document.getElementById('editDriverStatus').value
            };
            fetch(`api/drivers.php?id=${id}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(updated)
            }).then(r => r.json()).then(res => {
                if (res.success) loadDrivers();
                else showNotification(res.message || 'Server error', 'error');
            }).catch(() => { });
            document.getElementById('editDriverModal').style.display = 'none';
            showNotification('Driver updated successfully', 'success');
        });

        // ========== SLEME ACTIONS ==========
        document.getElementById('vehicleAdmittedBtn')?.addEventListener('click', function () {
            const vehicleId = document.getElementById('slemeVehicleSelect').value;
            if (!vehicleId) { showNotification('Please select a vehicle', 'error'); return; }
            
            fetch(`api/vehicles.php?id=${vehicleId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ status: 'maintenance' })
            }).then(r => r.json()).then(res => {
                if (res.success) {
                    showNotification(`${vehicleId} admitted to maintenance.`, 'warning');
                    loadVehicles(); // update dashboard
                } else showNotification(res.message, 'error');
            });
        });

        document.getElementById('jobCompletedBtn')?.addEventListener('click', function () {
            const vehicleId = document.getElementById('slemeVehicleSelect').value;
            if (!vehicleId) { showNotification('Please select a vehicle', 'error'); return; }

            fetch(`api/vehicles.php?id=${vehicleId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ status: 'active' })
            }).then(r => r.json()).then(res => {
                if (res.success) {
                    showNotification(`${vehicleId} job completed. Vehicle marked as active.`, 'success');
                    loadVehicles();
                } else showNotification(res.message, 'error');
            });
        });

        // ========== SERVICE RECORDS ==========
        function loadServiceRecords() {
            populateVehicleSelect('serviceVehicleSelect');
            populateVehicleSelect('slemeVehicleSelect');
            fetch('api/service_records.php')
                .then(r => r.json())
                .then(res => { if (res.success) appState.serviceRecords = res.data || []; })
                .catch(() => { })
                .finally(() => renderServiceRecords());
        }

        function renderServiceRecords() {
            const services = db.getServiceRecords();
            const vehicles = db.getVehicles();
            const tbody = document.getElementById('serviceRecordsBody');
            if (!tbody) return;
            tbody.innerHTML = '';
            if (!services || services.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 20px;">No service records found. Add your first service record above.</td></tr>';
                return;
            }
            const sortedServices = [...services].sort((a, b) => new Date(b.date) - new Date(a.date));

            sortedServices.forEach(s => {
                const vehicle = vehicles.find(v => v.id === s.vehicle_id);
                const row = tbody.insertRow();
                let typeClass = 'info';
                if (s.type === 'routine') typeClass = 'success';
                else if (s.type === 'repair') typeClass = 'warning';
                else if (s.type === 'emergency') typeClass = 'danger';

                row.innerHTML = `
                    <td>${vehicle ? vehicle.id + ' - ' + vehicle.model : s.vehicle_id}</td>
                    <td>${s.date || ''}</td>
                    <td><span class="status-badge status-${typeClass}">${(s.type || '').toUpperCase()}</span></td>
                    <td>${s.description || ''}</td>
                    <td>LKR ${(s.cost || 0).toLocaleString()}</td>
                    <td>${s.technician || 'N/A'}</td>
                    <td>
                        <div class="action-buttons">
                            ${window._canEditService ? `<button class="delete-btn" onclick="deleteService('${s.id}')"><i class="fas fa-trash"></i></button>` : '<span style="color:var(--text-gray);">View only</span>'}
                        </div>
                    </td>
                `;
            });
        }

        window.deleteService = function (id) {
            if (confirm('Delete this service record?')) {
                fetch(`api/service_records.php?id=${id}`, { method: 'DELETE' })
                    .finally(() => loadServiceRecords());
                showNotification('Service record deleted', 'success');
            }
        };

        // Save Service Record
        document.getElementById('saveServiceBtn').addEventListener('click', function () {
            const vehicleId = document.getElementById('serviceVehicleSelect').value;
            const date = document.getElementById('serviceDate').value;
            const type = document.getElementById('serviceType').value;
            const cost = parseFloat(document.getElementById('serviceCost').value);
            const desc = document.getElementById('serviceDescription').value.trim();
            const technician = document.getElementById('serviceTechnician').value.trim();

            if (!vehicleId || !date || !type || !cost || !desc || !technician) {
                showNotification('Please fill all required fields', 'error'); return;
            }

            fetch('api/service_records.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ vehicle_id: vehicleId, date, type, description: desc, cost, technician })
            }).then(r => r.json()).then(res => {
                if (res.success) {
                    document.getElementById('serviceVehicleSelect').value = '';
                    document.getElementById('serviceDate').value = new Date().toISOString().split('T')[0];
                    document.getElementById('serviceType').value = 'routine';
                    document.getElementById('serviceCost').value = '';
                    document.getElementById('serviceDescription').value = '';
                    document.getElementById('serviceTechnician').value = '';
                    loadServiceRecords();
                    showNotification('Service record saved successfully', 'success');
                } else {
                    showNotification(res.message || 'Server error', 'error');
                }
            }).catch(() => showNotification('Server unreachable', 'error'));
        });

        // ========== FUEL MANAGEMENT ==========
        function loadFuelData() {
            fetch('api/fuel.php?action=stock')
                .then(r => r.json())
                .then(res => { if (res.success && res.data) appState.fuelStock = res.data; })
                .catch(() => { });
            fetch('api/fuel.php')
                .then(r => r.json())
                .then(res => { if (res.success) appState.fuelTransactions = res.data || []; })
                .catch(() => { })
                .finally(() => { updateFuelSummary(); loadFuelTransactions(); checkLowFuelAlert(); });
            populateVehicleSelect('fuelVehicleSelect');
        }

        // ========== FUEL EFFICIENCY ANALYTICS ==========
        let efficiencyChart = null;

        function loadEfficiencyData() {
            Promise.all([
                fetch('api/vehicles.php').then(r => r.json()),
                fetch('api/movements.php').then(r => r.json()),
                fetch('api/drivers.php').then(r => r.json())
            ]).then(([vRes, mRes, dRes]) => {
                const vehicles = vRes.data || [];
                const movements = (mRes.data || []).filter(m => m.status === 'completed' && m.checkin_odometer > 0);
                const drivers = dRes.data || [];

                const efficiencyTableBody = document.getElementById('efficiencyTableBody');
                if (efficiencyTableBody) {
                    efficiencyTableBody.innerHTML = '';
                    if (movements.length === 0) {
                        efficiencyTableBody.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:20px;">No completed trips with odometer data found.</td></tr>';
                    }
                }

                const vehicleStats = {}; // { vehicleId: { totalKm: 0, totalFuel: 0, count: 0 } }

                movements.forEach(m => {
                    const vehicle = vehicles.find(v => v.id === m.vehicle_id);
                    const driver = drivers.find(d => d.id === m.driver_id);

                    const distance = (m.checkin_odometer || 0) - (m.checkout_odometer || 0);
                    // Fuel Used (L) = (checkout_fuel_level - fuel_level) % of capacity
                    const fuelUsedPct = (m.checkout_fuel_level || 0) - (m.fuel_level || 0);
                    const capacity = vehicle ? (vehicle.fuel_capacity || 80) : 80;
                    const fuelUsedLiters = Math.max(0.1, (fuelUsedPct / 100) * capacity); // Ensure not zero to avoid div by zero

                    const kmPerLiter = distance / fuelUsedLiters;
                    const efficiency = kmPerLiter.toFixed(2);

                    if (vehicle) {
                        if (!vehicleStats[vehicle.id]) vehicleStats[vehicle.id] = { totalKm: 0, totalFuel: 0, count: 0, model: vehicle.model };
                        vehicleStats[vehicle.id].totalKm += distance;
                        vehicleStats[vehicle.id].totalFuel += fuelUsedLiters;
                        vehicleStats[vehicle.id].count++;
                    }

                    if (efficiencyTableBody) {
                        const row = efficiencyTableBody.insertRow();
                        const statusClass = kmPerLiter < 5 ? 'danger' : kmPerLiter < 10 ? 'warning' : 'success';
                        row.innerHTML = `
                            <td><strong>${m.vehicle_id}</strong><br><small>${vehicle ? vehicle.model : ''}</small></td>
                            <td>${driver ? driver.first_name + ' ' + driver.last_name : m.driver_id}</td>
                            <td>${distance} KM</td>
                            <td>${fuelUsedLiters.toFixed(1)} L</td>
                            <td><span class="status-badge status-${statusClass}">${efficiency} KM/L</span></td>
                            <td>${m.condition || 'N/A'}</td>
                            <td>${new Date(m.check_out).toLocaleString()}</td>
                            <td>${new Date(m.check_in).toLocaleString()}</td>
                        `;
                    }
                });

                // Update Chart
                updateEfficiencyChart(vehicleStats);

            }).catch(err => {
                console.error(err);
                showNotification('Failed to load efficiency data', 'error');
            });
        }

        function updateEfficiencyChart(vehicleStats) {
            const ctx = document.getElementById('efficiencyBarChart')?.getContext('2d');
            if (!ctx) return;

            const labels = [];
            const data = [];
            const colors = [];

            Object.keys(vehicleStats).forEach(vid => {
                const stat = vehicleStats[vid];
                const avgEfficiency = stat.totalKm / stat.totalFuel;
                labels.push(`${vid} (${stat.model})`);
                data.push(avgEfficiency.toFixed(2));
                colors.push(avgEfficiency < 5 ? '#D32F2F' : avgEfficiency < 10 ? '#FF9800' : '#4CAF50');
            });

            if (efficiencyChart) efficiencyChart.destroy();

            efficiencyChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Average KM per Liter',
                        data: data,
                        backgroundColor: colors,
                        borderColor: '#0d1b1e',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'KM / Liter', color: '#f0f0f0' },
                            ticks: { color: '#f0f0f0' }
                        },
                        x: { ticks: { color: '#f0f0f0' } }
                    },
                    plugins: {
                        legend: { labels: { color: '#f0f0f0' } }
                    }
                }
            });
        }

        // S20: Low fuel alert
        function checkLowFuelAlert() {
            const stock = db.getFuelStock();
            if (stock.diesel <= stock.diesel_threshold) {
                showNotification(`⚠️ Low Diesel Stock: ${stock.diesel}L (threshold: ${stock.diesel_threshold}L)`, 'warning');
            }
            if (stock.petrol <= stock.petrol_threshold) {
                showNotification(`⚠️ Low Petrol Stock: ${stock.petrol}L (threshold: ${stock.petrol_threshold}L)`, 'warning');
            }
        }

        function updateFuelSummary() {
            const stock = db.getFuelStock();
            const summary = document.getElementById('fuelSummary');
            if (!summary) return;

            const dieselPct = stock.diesel ? Math.min((stock.diesel / (stock.diesel_threshold * 5)) * 100, 100) : 0;
            const petrolPct = stock.petrol ? Math.min((stock.petrol / (stock.petrol_threshold * 5)) * 100, 100) : 0;

            summary.innerHTML = `
                <div class="fuel-card" style="border-left-color: #4CAF50;">
                    <h4>Diesel Stock</h4>
                    <div class="amount">${(stock.diesel || 0).toLocaleString()}</div>
                    <div class="unit">Liters</div>
                    <div class="fuel-progress">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: ${dieselPct}%"></div>
                        </div>
                        <small>Threshold: ${stock.diesel_threshold || 2000}L</small>
                    </div>
                </div>
                <div class="fuel-card" style="border-left-color: #2196F3;">
                    <h4>Petrol Stock</h4>
                    <div class="amount">${(stock.petrol || 0).toLocaleString()}</div>
                    <div class="unit">Liters</div>
                    <div class="fuel-progress">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: ${petrolPct}%"></div>
                        </div>
                        <small>Threshold: ${stock.petrol_threshold || 1000}L</small>
                    </div>
                </div>
            `;
        }

        function loadFuelTransactions() {
            const transactions = db.getFuelTransactions();
            const vehicles = db.getVehicles();
            const tbody = document.getElementById('fuelRecordsBody');
            if (!tbody) return;

            tbody.innerHTML = '';

            if (!transactions || transactions.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" style="text-align: center;">No transactions found</td></tr>';
                return;
            }

            // Sort by date descending
            const sortedTransactions = [...transactions].sort((a, b) => new Date(b.date) - new Date(a.date));

            sortedTransactions.slice(0, 20).forEach(t => {
                const row = tbody.insertRow();
                const typeClass = t.type === 'allocation' ? 'success' : t.type === 'stock_add' ? 'success' : 'warning';
                const vehicle = vehicles.find(v => v.id === t.vehicle_id);
                const vehicleInfo = t.vehicle_id ? `${t.vehicle_id} (${vehicle ? vehicle.model : ''})` : 'Stock Management';

                row.innerHTML = `
                    <td><span class="status-badge status-${typeClass}">${(t.type || 'unknown').toUpperCase()}</span></td>
                    <td>${t.date || ''}</td>
                    <td>${vehicleInfo}</td>
                    <td>${(t.fuel_type || '').toUpperCase()}</td>
                    <td>${t.amount || 0} L</td>
                    <td>${t.purpose || t.notes || 'N/A'}</td>
                    <td>${t.authorized_by || 'System'}</td>
                    <td>D:${t.stock_balance_diesel || 0}L P:${t.stock_balance_petrol || 0}L</td>
                    <td>
                        <div class="action-buttons">
                            ${window._canEditFuel ? `<button class="delete-btn" onclick="deleteFuelTransaction('${t.id}')"><i class="fas fa-trash"></i></button>` : '<span style="color:var(--text-gray);">View only</span>'}
                        </div>
                    </td>
                `;
            });
        }

        window.deleteFuelTransaction = function (id) {
            if (confirm('Delete this transaction?')) {
                fetch(`api/fuel.php?id=${id}`, { method: 'DELETE' })
                    .finally(() => loadFuelData());
                showNotification('Transaction deleted', 'success');
            }
        };

        // Update Stock
        document.getElementById('updateStockBtn').addEventListener('click', function () {
            const fuelType = document.getElementById('stockFuelType').value;
            const action = document.getElementById('stockAction').value;
            const quantity = parseInt(document.getElementById('stockQuantity').value);
            const notes = document.getElementById('stockNotes').value;
            const user = window._currentUser || {};

            if (!quantity || quantity <= 0) { showNotification('Enter valid quantity', 'error'); return; }

            fetch('api/fuel.php?action=update_stock', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ fuel_type: fuelType, action, quantity, notes, authorized_by: user.full_name || 'System' })
            }).then(r => r.json()).then(res => {
                if (res.success) {
                    if (res.stock) appState.fuelStock = res.stock;
                    document.getElementById('stockQuantity').value = '';
                    document.getElementById('stockNotes').value = '';
                    loadFuelData();
                    showNotification(`Stock updated: ${action} ${quantity}L ${fuelType}`, 'success');
                } else showNotification(res.message || 'Server error', 'error');
            }).catch(() => showNotification('Server unreachable', 'error'));
        });

        // Allocate Fuel
        document.getElementById('saveFuelBtn').addEventListener('click', function () {
            const vehicleId = document.getElementById('fuelVehicleSelect').value;
            const amount = parseInt(document.getElementById('fuelAmount').value);
            const fuelType = document.getElementById('fuelType').value;
            const date = document.getElementById('fuelDate').value;
            const purpose = document.getElementById('allocationPurpose').value;
            const user = window._currentUser || {};

            if (!vehicleId || !amount || !date) { showNotification('Please fill all fields', 'error'); return; }

            fetch('api/fuel.php?action=allocate', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ vehicle_id: vehicleId, amount, fuel_type: fuelType, date, purpose, authorized_by: user.full_name || 'System' })
            }).then(r => r.json()).then(res => {
                if (res.success) {
                    if (res.stock) appState.fuelStock = res.stock;
                    document.getElementById('fuelAmount').value = '';
                    loadFuelData();
                    loadVehicles();
                    showNotification(`${amount}L ${fuelType} allocated to ${vehicleId}`, 'success');
                } else showNotification(res.message || 'Server error', 'error');
            }).catch(() => showNotification('Server unreachable', 'error'));
        });

        // Manage Stock Modal
        document.getElementById('manageStockBtn').addEventListener('click', function () {
            const stock = db.getFuelStock();
            document.getElementById('editStockDiesel').value = stock.diesel || 0;
            document.getElementById('editStockPetrol').value = stock.petrol || 0;
            document.getElementById('editDieselThreshold').value = stock.diesel_threshold || 2000;
            document.getElementById('editPetrolThreshold').value = stock.petrol_threshold || 1000;
            document.getElementById('fuelStockModal').style.display = 'flex';
        });

        document.getElementById('saveStockSettingsBtn').addEventListener('click', function () {
            const payload = {
                diesel: parseInt(document.getElementById('editStockDiesel').value) || 0,
                petrol: parseInt(document.getElementById('editStockPetrol').value) || 0,
                diesel_threshold: parseInt(document.getElementById('editDieselThreshold').value) || 2000,
                petrol_threshold: parseInt(document.getElementById('editPetrolThreshold').value) || 1000
            };
            fetch('api/fuel.php?action=stock', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            }).then(r => r.json()).then(res => {
                if (res.success) {
                    appState.fuelStock = { ...appState.fuelStock, ...payload };
                    updateFuelSummary();
                    document.getElementById('fuelStockModal').style.display = 'none';
                    showNotification('Fuel stock settings saved', 'success');
                } else showNotification(res.message || 'Server error', 'error');
            }).catch(() => showNotification('Server unreachable', 'error'));
        });

        // ========== WHATSAPP INTEGRATION ==========
        function loadWhatsAppData() {
            updateWhatsappHistory();
            populateRecipientSelect();
        }

        function updateWhatsappHistory() {
            const history = document.getElementById('whatsappHistoryList');
            if (!history) return;
            fetch('api/whatsapp.php').then(r => r.json()).then(res => {
                const messages = res.data || [];
                history.innerHTML = '';
                if (!messages.length) {
                    history.innerHTML = '<div class="activity-item"><div class="activity-icon"><i class="fab fa-whatsapp"></i></div><div><h4>No messages yet</h4></div></div>';
                    return;
                }
                messages.forEach(m => {
                    const div = document.createElement('div');
                    div.className = 'activity-item';
                    div.innerHTML = `<div class="activity-icon"><i class="fab fa-whatsapp"></i></div>
                        <div>
                            <h4>To: ${m.recipient === 'all' ? 'All Drivers' : m.recipient}</h4>
                            <p>${m.message || ''}</p>
                            <p style="color:var(--text-gray);">${m.created_at ? new Date(m.created_at).toLocaleString() : ''}</p>
                        </div>`;
                    history.appendChild(div);
                });
            }).catch(() => { });
        }

        function populateRecipientSelect() {
            const select = document.getElementById('recipient');
            if (!select) return;
            select.innerHTML = '<option value="all">All Drivers</option>';
            appState.drivers.forEach(d => {
                select.innerHTML += `<option value="${d.id}">${d.first_name} ${d.last_name} (${d.id})</option>`;
            });
        }

        document.getElementById('whatsappForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const recipient = document.getElementById('recipient').value;
            const type = document.getElementById('messageType').value;
            const message = document.getElementById('whatsappMessage').value;
            if (!message.trim()) { showNotification('Enter a message', 'error'); return; }

            let phoneNumber = '+94767583198';
            if (recipient !== 'all') {
                const driver = appState.drivers.find(d => d.id === recipient);
                if (driver) phoneNumber = driver.whatsapp || phoneNumber;
            }

            // Save to DB
            fetch('api/whatsapp.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: generateId('W'), type, message, recipient })
            }).then(() => updateWhatsappHistory()).catch(() => { });

            window.open(`https://wa.me/${phoneNumber.replace(/\D/g, '')}?text=${encodeURIComponent(message)}`, '_blank');
            document.getElementById('whatsappMessage').value = '';
            showNotification('Message sent via WhatsApp', 'success');
        });

        // ========== GPS TRACKER ==========
        let map = null;
        let trackingMap = null;
        let gpsWatchId = null;

        function initMap() {
            const mapElement = document.getElementById('map');
            if (!mapElement) return;

            if (map) { map.remove(); map = null; }

            map = L.map('map', { zoomControl: true }).setView([7.8731, 80.7718], 8);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            // Add scale control
            L.control.scale({ imperial: false }).addTo(map);

            // Fix gray tiles by forcing resize
            setTimeout(() => { if (map) map.invalidateSize(true); }, 300);
        }

        function initTrackingMap() {
            const mapElement = document.getElementById('trackingMap');
            if (!mapElement) return;

            if (trackingMap) { trackingMap.remove(); trackingMap = null; }

            trackingMap = L.map('trackingMap', { zoomControl: true }).setView([7.8731, 80.7718], 8);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(trackingMap);

            L.control.scale({ imperial: false }).addTo(trackingMap);

            // Fix gray tiles
            setTimeout(() => { if (trackingMap) { trackingMap.invalidateSize(true); updateTrackingMarkers(); } }, 300);
        }

        function updateTrackingMarkers() {
            populateVehicleSelect('routeVehicleSelect');
            if (!trackingMap) return;

            // Clear existing layers except base tile
            trackingMap.eachLayer((layer) => {
                if (layer instanceof L.Marker || layer instanceof L.Polyline) {
                    trackingMap.removeLayer(layer);
                }
            });

            const vehicles = appState.vehicles;
            const selectedRouteVehicle = document.getElementById('routeVehicleSelect') ? document.getElementById('routeVehicleSelect').value : 'all';

            vehicles.forEach(v => {
                if (selectedRouteVehicle !== 'all' && selectedRouteVehicle !== v.id) return;
                
                if (v.latitude && v.longitude) {
                    const color = v.status === 'active' ? '#4CAF50' : v.status === 'maintenance' ? '#FF9800' : '#D32F2F';

                    // Draw historical route
                    if (appState.gpsTracking && appState.gpsTracking.length > 0 && selectedRouteVehicle === v.id) {
                        const history = appState.gpsTracking
                            .filter(t => t.vehicle_id === v.id)
                            .sort((a, b) => new Date(a.timestamp) - new Date(b.timestamp))
                            .map(t => [t.latitude, t.longitude]);
                            
                        if (history.length > 0) {
                            history.push([v.latitude, v.longitude]);
                            L.polyline(history, { color: color, weight: 3, opacity: 0.8, dashArray: '5, 5' }).addTo(trackingMap);
                        }
                    }

                    // Create custom icon
                    const icon = L.divIcon({
                        className: 'custom-div-icon',
                        html: `<div style="background-color: ${color}; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 10px rgba(0,0,0,0.5);"></div>`,
                        iconSize: [20, 20],
                        iconAnchor: [10, 10]
                    });

                    const marker = L.marker([v.latitude, v.longitude], { icon }).addTo(trackingMap);
                    marker.bindPopup(`
                        <strong>${v.id}</strong><br>
                        ${v.model}<br>
                        Status: ${v.status}<br>
                        Fuel: ${v.fuel_level}%<br>
                        Source: ${v.location_source || 'N/A'}<br>
                        Last: ${getTimeAgo(v.location_timestamp)}
                    `);
                }
            });
        }

        function loadGPSData() {
            loadGPSDevices();
            loadGPSTracking();
            populateVehicleSelect('gpsDeviceSelect');
            populateVehicleSelect('manualVehicleSelect');
        }

        function loadGPSDevices() {
            const devices = db.getGPSDevices();
            const vehicles = db.getVehicles();
            const list = document.getElementById('deviceList');
            if (!list) return;

            list.innerHTML = '';

            if (!devices || devices.length === 0) {
                list.innerHTML = '<p style="text-align: center;">No GPS devices configured</p>';
                return;
            }

            devices.forEach(d => {
                const vehicle = vehicles.find(v => v.id === d.vehicle_id);
                const card = document.createElement('div');
                card.className = 'device-card';
                card.innerHTML = `
                    <h4><i class="fas fa-microchip"></i> ${d.id || 'Unknown'} <span class="device-status ${d.status || 'offline'}">${(d.status || 'offline').toUpperCase()}</span></h4>
                    <div class="device-info">
                        <p><strong>Vehicle:</strong> ${vehicle ? vehicle.id : d.vehicle_id}</p>
                        <p><strong>Type:</strong> ${d.type || 'N/A'}</p>
                        <p><strong>Last Update:</strong> ${d.last_update ? getTimeAgo(d.last_update) : 'N/A'}</p>
                        <p><strong>Battery:</strong> ${d.battery_level || 0}%</p>
                        <p><strong>Location:</strong> ${d.latitude ? d.latitude.toFixed(4) : '0'}, ${d.longitude ? d.longitude.toFixed(4) : '0'}</p>
                    </div>
                `;
                list.appendChild(card);
            });
        }

        function loadGPSTracking() {
            const tracking = db.getGPSTracking();
            const vehicles = db.getVehicles();
            const list = document.getElementById('trackingHistoryList');
            if (!list) return;

            list.innerHTML = '';

            if (!tracking || tracking.length === 0) {
                list.innerHTML = '<p>No tracking data</p>';
                return;
            }

            // Sort by date descending
            const sortedTracking = [...tracking].sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));

            sortedTracking.slice(0, 10).forEach(t => {
                const vehicle = vehicles.find(v => v.id === t.vehicle_id);
                const div = document.createElement('div');
                div.className = 'history-item';
                div.innerHTML = `
                    <div>
                        <span class="history-time">${t.timestamp ? new Date(t.timestamp).toLocaleTimeString() : ''}</span>
                        <span class="history-coords">${t.latitude ? t.latitude.toFixed(4) : '0'}, ${t.longitude ? t.longitude.toFixed(4) : '0'}</span>
                    </div>
                    <div>
                        <span class="history-source ${t.source || 'gps'}">${(t.source || 'gps').toUpperCase()}</span>
                        <span style="margin-left: 10px;">${t.accuracy || 0}m</span>
                        <span>${vehicle ? vehicle.id : t.vehicle_id}</span>
                    </div>
                `;
                list.appendChild(div);
            });
        }

        window.startGPSTracking = function () {
            if (!navigator.geolocation) {
                showNotification('GPS not supported', 'error');
                return;
            }

            showNotification('Starting GPS tracking...', 'info');

            gpsWatchId = navigator.geolocation.watchPosition(
                function (pos) {
                    const lat = pos.coords.latitude;
                    const lon = pos.coords.longitude;
                    const acc = pos.coords.accuracy;

                    document.getElementById('gpsCoordinates').textContent = `${lat.toFixed(6)}, ${lon.toFixed(6)}`;
                    document.getElementById('gpsAccuracy').textContent = acc.toFixed(0);
                    document.getElementById('gpsTime').textContent = new Date().toLocaleTimeString();
                    document.getElementById('gpsSatellites').textContent = Math.floor(Math.random() * 8) + 8;

                    const signal = document.getElementById('gpsSignalIcon');
                    if (acc < 10) signal.className = 'gps-signal good';
                    else if (acc < 30) signal.className = 'gps-signal fair';
                    else signal.className = 'gps-signal poor';

                    if (map) {
                        map.setView([lat, lon], 15);
                        L.marker([lat, lon]).addTo(map).bindPopup('Current Location').openPopup();
                    }

                    showNotification('GPS location acquired', 'success');
                },
                function (err) {
                    showNotification('GPS error: ' + err.message, 'error');
                },
                { enableHighAccuracy: true }
            );
        };

        window.stopGPSTracking = function () {
            if (gpsWatchId) {
                navigator.geolocation.clearWatch(gpsWatchId);
                gpsWatchId = null;
                showNotification('GPS tracking stopped', 'info');
            }
        };

        window.getCurrentGPSLocation = function () {
            if (!navigator.geolocation) {
                showNotification('GPS not supported', 'error');
                return;
            }

            navigator.geolocation.getCurrentPosition(
                function (pos) {
                    document.getElementById('gpsCoordinates').textContent = `${pos.coords.latitude.toFixed(6)}, ${pos.coords.longitude.toFixed(6)}`;
                    document.getElementById('gpsAccuracy').textContent = pos.coords.accuracy.toFixed(0);
                    document.getElementById('gpsTime').textContent = new Date().toLocaleTimeString();
                    showNotification('GPS location updated', 'success');
                },
                function (err) {
                    showNotification('GPS error: ' + err.message, 'error');
                }
            );
        };

        // Manual location update
        window.updateManualLocation = function () {
            const vehicleId = document.getElementById('manualVehicleSelect').value;
            const latitude = parseFloat(document.getElementById('manualLatitude').value);
            const longitude = parseFloat(document.getElementById('manualLongitude').value);

            if (!vehicleId || isNaN(latitude) || isNaN(longitude)) {
                showNotification('Please select vehicle and enter coordinates', 'error');
                return;
            }

            const vIndex = appState.vehicles.findIndex(v => v.id === vehicleId);
            if (vIndex !== -1) {
                appState.vehicles[vIndex].latitude = latitude;
                appState.vehicles[vIndex].longitude = longitude;
                appState.vehicles[vIndex].location_source = 'manual';
                appState.vehicles[vIndex].location_timestamp = new Date().toISOString();
            }

            appState.gpsTracking.unshift({ vehicle_id: vehicleId, latitude, longitude, accuracy: 10, source: 'manual', timestamp: new Date().toISOString() });

            if (map) {
                map.setView([latitude, longitude], 15);
                L.marker([latitude, longitude]).addTo(map).bindPopup(`Vehicle ${vehicleId}`).openPopup();
            }

            loadGPSData();
            loadTrackingData();
            showNotification(`Location updated for ${vehicleId}`, 'success');
        };

        // Automatic location update from GPS device
        window.updateVehicleLocationAuto = function (vehicleId, latitude, longitude, source = 'gps') {
            const vIndex = appState.vehicles.findIndex(v => v.id === vehicleId);
            if (vIndex !== -1) {
                appState.vehicles[vIndex].latitude = latitude;
                appState.vehicles[vIndex].longitude = longitude;
                appState.vehicles[vIndex].location_source = source;
                appState.vehicles[vIndex].location_timestamp = new Date().toISOString();
            }
            appState.gpsTracking.unshift({ vehicle_id: vehicleId, latitude, longitude, accuracy: 15, source, timestamp: new Date().toISOString() });
            if (appState.gpsTracking.length > 100) appState.gpsTracking = appState.gpsTracking.slice(0, 100);
            updateTrackingMarkers();
        };

        window.configureGPSDevice = function () {
            const vehicleId = document.getElementById('gpsDeviceSelect').value;
            const type = document.getElementById('gpsDeviceType').value;
            const deviceId = document.getElementById('gpsDeviceId').value;
            const interval = document.getElementById('gpsUpdateInterval').value;

            if (!vehicleId || !deviceId) { showNotification('Please fill all fields', 'error'); return; }

            const device = {
                id: deviceId, vehicle_id: vehicleId, type,
                update_interval: parseInt(interval) || 30,
                status: 'online', last_update: new Date().toISOString(),
                latitude: 6.9271, longitude: 79.8612, accuracy: 10, battery_level: 100
            };

            const existing = appState.gpsDevices.findIndex(d => d.vehicle_id === vehicleId);
            if (existing >= 0) appState.gpsDevices[existing] = device;
            else appState.gpsDevices.push(device);

            loadGPSDevices();
            simulateGPSUpdates(vehicleId, parseInt(interval) || 30);
            showNotification('GPS device configured', 'success');
        };

        // Simulate GPS updates for demo
        function simulateGPSUpdates(vehicleId, interval) {
            setInterval(() => {
                const lat = 6.9271 + (Math.random() - 0.5) * 0.02;
                const lon = 79.8612 + (Math.random() - 0.5) * 0.02;
                updateVehicleLocationAuto(vehicleId, lat, lon, 'gps');
            }, interval * 1000);
        }

        // ========== VEHICLE TRACKING ==========
        function loadTrackingData() {
            initTrackingMap();
            loadTrackingTable();
        }

        function loadTrackingTable() {
            const vehicles = appState.vehicles;
            const tbody = document.getElementById('trackingTableBody');
            if (!tbody) return;

            tbody.innerHTML = '';

            if (!vehicles || vehicles.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" style="text-align: center;">No vehicles found</td></tr>';
                return;
            }

            vehicles.forEach(v => {
                const row = tbody.insertRow();
                const sourceClass = v.location_source === 'gps' ? 'success' : v.location_source === 'gsm' ? 'warning' : 'info';

                row.innerHTML = `
                    <td><strong>${v.id}</strong></td>
                    <td>${v.type || 'N/A'}</td>
                    <td>${v.model || 'N/A'}</td>
                    <td>${v.latitude ? v.latitude.toFixed(4) + ', ' + v.longitude.toFixed(4) : 'N/A'}</td>
                    <td>${v.location_timestamp ? getTimeAgo(v.location_timestamp) : 'N/A'}</td>
                    <td><span class="status-badge status-${sourceClass}">${v.location_source || 'N/A'}</span></td>
                    <td>${v.location_accuracy || 'N/A'}m</td>
                    <td><span class="status-badge status-${v.status === 'active' ? 'success' : v.status === 'maintenance' ? 'warning' : 'danger'}">${(v.status || 'unknown').toUpperCase()}</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="location-btn" onclick="focusOnMap('${v.id}')"><i class="fas fa-map-marker-alt"></i> View</button>
                        </div>
                    </td>
                `;
            });
        }

        window.refreshTracking = function () {
            loadTrackingData();
            showNotification('Locations refreshed', 'success');
        };

        window.focusOnMap = function (id) {
            const vehicles = db.getVehicles();
            const vehicle = vehicles.find(v => v.id === id);
            if (vehicle && vehicle.latitude && trackingMap) {
                trackingMap.setView([vehicle.latitude, vehicle.longitude], 15);

                // Highlight marker
                const icon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="background-color: #FFD700; width: 30px; height: 30px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 20px gold;"></div>`,
                    iconSize: [30, 30],
                    iconAnchor: [15, 15]
                });

                L.marker([vehicle.latitude, vehicle.longitude], { icon }).addTo(trackingMap).bindPopup(`Selected: ${vehicle.id}`).openPopup();
            }
        };

        // ========== VEHICLE IN/OUT TRACKING - FIXED ==========
        function loadMovementData() {
            fetch('api/movements.php')
                .then(r => r.json())
                .then(res => { if (res.success) appState.movements = res.data || []; })
                .catch(() => { })
                .finally(() => {
                    updateInOutSummary();
                    loadActiveMovements();
                    loadMovementHistory();
                    // Populate filter dropdowns
                    populateVehicleSelect('filterMovementVehicle');
                    populateDriverSelect('filterMovementDriver');
                });
            populateVehicleSelect('checkOutVehicle');
            populateDriverSelect('checkOutDriver');
        }

        function updateInOutSummary() {
            const vehicles = db.getVehicles();
            const movements = db.getMovements();

            const pendingCount = 0; // removed — idle is the base state now
            const inCount = vehicles ? vehicles.filter(v => v.in_out_status === 'idle').length : 0;
            const outCount = movements ? movements.filter(m => m.status === 'out').length : 0;

            const summary = document.getElementById('inOutSummary');
            if (summary) {
                summary.innerHTML = `
                    <div class="in-out-card">
                        <h4>Idle (Available)</h4>
                        <div class="count">${inCount}</div>
                    </div>
                    <div class="in-out-card">
                        <h4>Vehicles Out</h4>
                        <div class="count">${outCount}</div>
                    </div>
                `;
            }
        }

        function loadActiveMovements(filterVehicle = '', filterDriver = '', filterDate = '') {
            const movements = db.getMovements();
            const vehicles = db.getVehicles();
            const drivers = db.getDrivers();
            const tbody = document.getElementById('movementTableBody');
            if (!tbody) return;

            tbody.innerHTML = '';

            if (!movements || movements.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" style="text-align: center;">No active movements</td></tr>';
                return;
            }

            let active = movements.filter(m => m.status !== 'completed');

            // S5: Apply filters
            if (filterVehicle) active = active.filter(m => m.vehicle_id === filterVehicle);
            if (filterDriver) active = active.filter(m => m.driver_id === filterDriver);
            if (filterDate) active = active.filter(m => m.check_out && m.check_out.startsWith(filterDate));

            if (active.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" style="text-align: center;">No active movements</td></tr>';
                return;
            }

            const now = new Date();
            active.forEach(m => {
                const vehicle = vehicles.find(v => v.id === m.vehicle_id);
                const driver = drivers.find(d => d.id === m.driver_id);
                const row = tbody.insertRow();

                // S4: Overdue detection
                const isOverdue = m.status === 'out' && m.expected_return && new Date(m.expected_return) < now;
                if (isOverdue) row.style.background = 'rgba(211,47,47,0.15)';

                const overdueTag = isOverdue ? `<span class="status-badge status-danger" style="margin-left:6px;font-size:0.75rem;"><i class="fas fa-exclamation-triangle"></i> OVERDUE</span>` : '';

                row.innerHTML = `
                    <td>${vehicle ? vehicle.id : m.vehicle_id}</td>
                    <td>${driver ? driver.first_name + ' ' + driver.last_name : m.driver_id}</td>
                    <td>${m.check_out ? new Date(m.check_out).toLocaleString() : ''}</td>
                    <td>${m.expected_return ? new Date(m.expected_return).toLocaleString() : ''}${overdueTag}</td>
                    <td>${m.destination || ''}</td>
                    <td>${m.purpose || ''}</td>
                    <td>${m.authorized_by || ''}</td>
                    <td><span class="status-badge status-${isOverdue ? 'danger' : m.status || 'unknown'}">${isOverdue ? 'OVERDUE' : (m.status || 'unknown').toUpperCase()}</span></td>
                    <td>
                        ${m.status === 'out' ?
                        `<button class="checkin-btn" onclick="openCheckInModal('${m.id}')"><i class="fas fa-sign-in-alt"></i> Check In</button>` :
                        m.status === 'pending' ?
                            `<button class="edit-btn" onclick="authorizeMovement('${m.id}')"><i class="fas fa-check"></i> Authorize</button>` : ''}
                    </td>
                `;
            });
        }

        window.applyMovementFilter = function () {
            const v = document.getElementById('filterMovementVehicle')?.value || '';
            const d = document.getElementById('filterMovementDriver')?.value || '';
            const dt = document.getElementById('filterMovementDate')?.value || '';
            loadActiveMovements(v, d, dt);
        };

        window.clearMovementFilter = function () {
            document.getElementById('filterMovementVehicle').value = '';
            document.getElementById('filterMovementDriver').value = '';
            document.getElementById('filterMovementDate').value = '';
            loadActiveMovements();
        };

        function loadMovementHistory() {
            const movements = db.getMovements();
            const vehicles = db.getVehicles();
            const drivers = db.getDrivers();
            const tbody = document.getElementById('movementHistoryBody');
            if (!tbody) return;

            tbody.innerHTML = '';

            if (!movements || movements.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center;">No history</td></tr>';
                return;
            }

            const today = new Date().toISOString().split('T')[0];
            const history = movements.filter(m => m.check_in && m.check_out && m.check_out.startsWith(today)).slice(0, 5);

            if (history.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center;">No history for today</td></tr>';
                return;
            }

            history.forEach(m => {
                const vehicle = vehicles.find(v => v.id === m.vehicle_id);
                const driver = drivers.find(d => d.id === m.driver_id);
                const checkOut = new Date(m.check_out);
                const checkIn = new Date(m.check_in);
                const duration = Math.round((checkIn - checkOut) / (1000 * 60)) + ' mins';

                const row = tbody.insertRow();
                row.innerHTML = `
                    <td>${vehicle ? vehicle.id : m.vehicle_id}</td>
                    <td>${driver ? driver.first_name + ' ' + driver.last_name : m.driver_id}</td>
                    <td>${checkOut.toLocaleTimeString()}</td>
                    <td>${checkIn.toLocaleTimeString()}</td>
                    <td>${duration}</td>
                    <td>${m.destination || ''}</td>
                    <td>${m.authorized_by || ''}</td>
                `;
            });
        }

        window.openCheckOutModal = function (vehicleId) {
            populateVehicleSelect('checkOutVehicle');
            populateDriverSelect('checkOutDriver');

            const vehicle = appState.vehicles.find(v => v.id === vehicleId);
            if (vehicle) {
                const select = document.getElementById('checkOutVehicle');
                if (select) select.value = vehicleId;
                document.getElementById('checkOutOdometer').value = vehicle.current_odometer || 0;
            }

            document.getElementById('signatureDisplay').innerHTML = '<i class="fas fa-pen"></i> Officer signature will appear here';
            document.getElementById('checkOutModal').style.display = 'flex';
        };

        document.getElementById('checkOutForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const password = document.getElementById('officerPassword').value;

            const vehicleId = document.getElementById('checkOutVehicle').value;
            const vehicle = appState.vehicles.find(v => v.id === vehicleId);
            if (vehicle && vehicle.in_out_status === 'out') {
                showNotification('This vehicle is already checked out!', 'error'); return;
            }

            const payload = {
                vehicle_id: vehicleId,
                driver_id: document.getElementById('checkOutDriver').value,
                expected_return: document.getElementById('expectedReturn').value,
                destination: document.getElementById('destination').value,
                purpose: document.getElementById('purpose').value,
                authorized_by: document.getElementById('authorizingOfficer').value,
                authorized_officer_phone: document.getElementById('authorizingOfficerPhone').value,
                notes: document.getElementById('additionalNotes').value,
                odometer: parseInt(document.getElementById('checkOutOdometer').value) || 0,
                checkout_fuel_level: vehicle ? vehicle.fuel_level : 0,
                officer_code: password
            };

            fetch('api/movements.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            }).then(r => r.json()).then(res => {
                if (res.success) {
                    sendMovementNotification('CHECK OUT', payload, vehicle);
                    if (res.vehicle) {
                        const updatedVehicle = appState.vehicles.find(v => v.id === res.vehicle.id);
                        if (updatedVehicle) Object.assign(updatedVehicle, res.vehicle);
                    }
                    document.getElementById('signatureDisplay').innerHTML = `<i class="fas fa-check"></i> Authorized by ${payload.authorized_by}`;
                    showNotification(
                        res.duty_officer_notified
                            ? `Vehicle checked out and duty officer notified.`
                            : `Vehicle checked out. Configure Twilio to send the duty officer message.`,
                        res.duty_officer_notified ? 'success' : 'warning'
                    );
                    setTimeout(() => {
                        document.getElementById('checkOutModal').style.display = 'none';
                        document.getElementById('checkOutForm').reset();
                        loadMovementData();
                        loadVehicles();
                        refreshDashboard();
                    }, 1500);
                } else showNotification(res.message || 'Server error', 'error');
            }).catch(() => showNotification('Server unreachable', 'error'));
        });

        document.getElementById('checkOutVehicle').addEventListener('change', function () {
            const vehicle = appState.vehicles.find(v => v.id === this.value);
            if (vehicle) showVehicleDetails(vehicle, 'checkOutVehicleDetails');
        });

        function sendMovementNotification(action, payload, vehicle) {
            const message = `ARMY VMS ${action}\nVehicle: ${vehicle ? vehicle.id : payload.vehicle_id}${vehicle && vehicle.registration ? ` (${vehicle.registration})` : ''}\nModel: ${vehicle ? vehicle.model : 'N/A'}\nDriver: ${payload.driver_id || 'N/A'}\nAuthorized by: ${payload.authorized_by || payload.checked_in_by || 'N/A'}\nTime: ${new Date().toLocaleString()}`;
            fetch('api/whatsapp.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: generateId('W'), type: 'movement', message, recipient: 'admin_officer' })
            }).catch(() => {});
            const phone = String(payload.authorized_officer_phone || '').replace(/\D/g, '');
            window.open(phone ? `https://wa.me/${phone}?text=${encodeURIComponent(message)}` : `https://wa.me/?text=${encodeURIComponent(message)}`, '_blank');
        }

        window.openCheckInModal = function (movementId) {
            const movements = db.getMovements();
            const movement = movements.find(m => m.id === movementId);
            if (!movement) return;

            const vehicles = db.getVehicles();
            const drivers = db.getDrivers();
            const vehicle = vehicles.find(v => v.id === movement.vehicle_id);
            const driver = drivers.find(d => d.id === movement.driver_id);

            document.getElementById('checkInMovementId').value = movementId;
            document.getElementById('checkInVehicle').value = vehicle ? `${vehicle.id} - ${vehicle.model}` : movement.vehicle_id;
            document.getElementById('checkInDriver').value = driver ? driver.first_name + ' ' + driver.last_name : movement.driver_id;
            document.getElementById('fuelLevel').value = vehicle ? vehicle.fuel_level : 50;
            document.getElementById('checkInOdometer').value = vehicle ? vehicle.current_odometer : 0;

            document.getElementById('checkInModal').style.display = 'flex';
        };

        document.getElementById('checkInForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const password = document.getElementById('checkInPassword').value;

            const movementId = document.getElementById('checkInMovementId').value;
            const condition = document.getElementById('vehicleCondition').value;
            const checkInData = {
                return_notes: document.getElementById('returnNotes').value,
                checked_in_by: document.getElementById('checkInOfficer').value,
                fuel_level: parseInt(document.getElementById('fuelLevel').value) || 0,
                condition,
                odometer: parseInt(document.getElementById('checkInOdometer').value) || 0,
                officer_code: password
            };

            fetch(`api/movements.php?id=${movementId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(checkInData)
            }).then(r => r.json()).then(res => {
                if (res.success) {
                    const movement = appState.movements.find(m => m.id === movementId);
                    const vehicle = movement ? appState.vehicles.find(v => v.id === movement.vehicle_id) : null;
                    sendMovementNotification('CHECK IN', { vehicle_id: movement ? movement.vehicle_id : '', checked_in_by: checkInData.checked_in_by }, vehicle);
                    // S25: If damaged, set vehicle status to maintenance
                    if (condition === 'damaged' || condition === 'needs_maintenance') {
                        const movement = appState.movements.find(m => m.id === movementId);
                        if (movement) {
                            fetch(`api/vehicles.php?id=${movement.vehicle_id}`, {
                                method: 'PUT',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ status: 'maintenance' })
                            }).then(() => {
                                showNotification(`Vehicle marked as MAINTENANCE due to condition: ${condition}`, 'warning');
                            });
                        }
                    }
                    showNotification(`Vehicle checked in - Authorized by ${checkInData.checked_in_by}`, 'success');
                    setTimeout(() => {
                        document.getElementById('checkInModal').style.display = 'none';
                        document.getElementById('checkInForm').reset();
                        loadMovementData();
                        loadVehicles();
                        refreshDashboard();
                    }, 1500);
                } else showNotification(res.message || 'Server error', 'error');
            }).catch(() => showNotification('Server unreachable', 'error'));
        });

        window.authorizeMovement = function (id) {
            fetch(`api/movements.php?id=${id}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ status: 'out' })
            }).then(() => loadMovementData()).catch(() => { });
            showNotification('Movement authorized', 'success');
        };

        // ========== REPORTS & SHARING ==========
        function loadReportsData() {
            fetch('api/reports.php')
                .then(r => r.json())
                .then(res => {
                    const tbody = document.getElementById('reportsTableBody');
                    if (!tbody) return;
                    tbody.innerHTML = '';
                    const reports = res.data || [];
                    if (!reports.length) {
                        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;">No reports generated</td></tr>';
                        return;
                    }
                    reports.forEach(r => {
                        tbody.insertRow().innerHTML = `
                            <td>${r.id}</td>
                            <td>${(r.type || '').toUpperCase()}</td>
                            <td>${r.start_date || ''} to ${r.end_date || ''}</td>
                            <td>${r.generated_by || ''}</td>
                            <td>${r.created_at ? new Date(r.created_at).toLocaleString() : ''}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="pdf-btn" onclick="viewReport('${r.id}')"><i class="fas fa-eye"></i> View</button>
                                    <button class="whatsapp-btn" onclick="shareReport('${r.id}')"><i class="fab fa-whatsapp"></i> Share</button>
                                </div>
                            </td>`;
                    });
                }).catch(() => { });
        }

        window.generatePDFReport = function () {
            const type = document.getElementById('reportType').value;
            const start = document.getElementById('reportStartDate').value;
            const end = document.getElementById('reportEndDate').value;
            if (!start || !end) { showNotification('Select date range', 'error'); return; }

            // Fetch all needed data fresh from DB then build report
            Promise.all([
                fetch('api/vehicles.php').then(r => r.json()),
                fetch('api/drivers.php').then(r => r.json()),
                fetch('api/movements.php').then(r => r.json()),
                fetch('api/fuel.php').then(r => r.json()),
                fetch('api/service_records.php').then(r => r.json())
            ]).then(([vRes, dRes, mRes, fRes, sRes]) => {
                const vehicles = vRes.data || [];
                const drivers = dRes.data || [];
                const movements = (mRes.data || []).filter(m => m.check_out && m.check_out >= start && m.check_out <= end + ' 23:59:59');
                const fuel = (fRes.data || []).filter(f => f.date >= start && f.date <= end);
                const services = (sRes.data || []).filter(s => s.date >= start && s.date <= end);
                const user = window._currentUser || {};

                const content = buildReportHTML(type, start, end, vehicles, drivers, movements, fuel, services, user.full_name || 'System');
                const id = 'RPT' + Date.now().toString(36).toUpperCase();

                // Save to DB
                fetch('api/reports.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, type, start_date: start, end_date: end, generated_by: user.full_name || 'System', content })
                }).then(() => loadReportsData()).catch(() => { });

                document.getElementById('reportPreviewContent').innerHTML = content;
                document.getElementById('reportPreviewModal').style.display = 'flex';
                showNotification('Report generated', 'success');
            }).catch(() => showNotification('Failed to fetch data', 'error'));
        };

        function buildReportHTML(type, start, end, vehicles, drivers, movements, fuel, services, generatedBy) {
            const th = s => `<th style="padding:10px;border:1px solid #ddd;background:#006837;color:white;">${s}</th>`;
            const td = s => `<td style="padding:8px;border:1px solid #ddd;">${s}</td>`;
            const tableWrap = (headers, rows) => `
                <table style="width:100%;border-collapse:collapse;margin-top:15px;">
                    <thead><tr>${headers.map(th).join('')}</tr></thead>
                    <tbody>${rows.length ? rows.join('') : `<tr><td colspan="${headers.length}" style="padding:15px;text-align:center;">No records found</td></tr>`}</tbody>
                </table>`;

            let body = '';

            if (['movement', 'daily', 'weekly', 'monthly'].includes(type)) {
                const rows = movements.slice(0, 100).map(m => {
                    const v = vehicles.find(x => x.id === m.vehicle_id);
                    const d = drivers.find(x => x.id === m.driver_id);
                    return `<tr>${[
                        v ? v.id : m.vehicle_id,
                        d ? d.first_name + ' ' + d.last_name : m.driver_id,
                        m.destination || '',
                        m.purpose || '',
                        m.check_out ? new Date(m.check_out).toLocaleString() : '',
                        m.check_in ? new Date(m.check_in).toLocaleString() : 'Not returned',
                        m.authorized_by || ''
                    ].map(td).join('')}</tr>`;
                });
                body += `<h3 style="color:#006837;margin-top:20px;">Vehicle Movement Summary</h3>
                    <p><strong>Total:</strong> ${movements.length} &nbsp; <strong>Completed:</strong> ${movements.filter(m => m.status === 'completed').length} &nbsp; <strong>Out:</strong> ${movements.filter(m => m.status === 'out').length}</p>`;
                body += tableWrap(['Vehicle', 'Driver', 'Destination', 'Purpose', 'Check Out', 'Check In', 'Authorized By'], rows);
            }

            if (type === 'fuel') {
                const total = fuel.reduce((s, f) => s + (f.amount || 0), 0);
                const diesel = fuel.filter(f => f.fuel_type === 'diesel').reduce((s, f) => s + (f.amount || 0), 0);
                const petrol = fuel.filter(f => f.fuel_type === 'petrol').reduce((s, f) => s + (f.amount || 0), 0);
                const rows = fuel.slice(0, 100).map(f => `<tr>${[f.date || '', f.vehicle_id || 'Stock', (f.fuel_type || '').toUpperCase(), f.amount + ' L', f.purpose || f.notes || 'N/A', f.authorized_by || 'System'].map(td).join('')}</tr>`);
                body += `<h3 style="color:#006837;margin-top:20px;">Fuel Consumption Summary</h3>
                    <p><strong>Total:</strong> ${total}L &nbsp; <strong>Diesel:</strong> ${diesel}L &nbsp; <strong>Petrol:</strong> ${petrol}L</p>`;
                body += tableWrap(['Date', 'Vehicle', 'Type', 'Amount', 'Purpose', 'Authorized By'], rows);
            }

            if (type === 'service') {
                const totalCost = services.reduce((s, x) => s + (parseFloat(x.cost) || 0), 0);
                const rows = services.slice(0, 100).map(s => {
                    const v = vehicles.find(x => x.id === s.vehicle_id);
                    return `<tr>${[s.date || '', v ? v.id : s.vehicle_id, (s.type || '').toUpperCase(), s.description || '', 'LKR ' + (parseFloat(s.cost) || 0).toLocaleString(), s.technician || 'N/A'].map(td).join('')}</tr>`;
                });
                body += `<h3 style="color:#006837;margin-top:20px;">Service Records Summary</h3>
                    <p><strong>Total Services:</strong> ${services.length} &nbsp; <strong>Total Cost:</strong> LKR ${totalCost.toLocaleString()}</p>`;
                body += tableWrap(['Date', 'Vehicle', 'Type', 'Description', 'Cost', 'Technician'], rows);
            }

            return `<div style="font-family:Arial,sans-serif;padding:20px;">
                <div style="text-align:center;margin-bottom:25px;">
                    <h1 style="color:#006837;">SRI LANKA ARMY</h1>
                    <h3 style="color:#d4af37;">VEHICLE MANAGEMENT SYSTEM</h3>
                    <h2>${type.toUpperCase()} REPORT</h2>
                    <p>Period: ${start} to ${end}</p>
                    <hr style="border:2px solid #006837;">
                </div>
                ${body}
                <div style="margin-top:40px;text-align:right;">
                    <p>Generated by: ${generatedBy}</p>
                    <p>Date: ${new Date().toLocaleString()}</p>
                    <p style="margin-top:40px;">_________________________</p>
                    <p>Authorizing Officer</p>
                </div>
            </div>`;
        }

        window.downloadPDF = function () {
            try {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = document.getElementById('reportPreviewContent').innerHTML;
                Object.assign(tempDiv.style, { fontFamily: 'Arial,sans-serif', padding: '20px', color: '#000', background: '#fff', width: '800px', position: 'absolute', left: '-9999px', top: '0' });
                document.body.appendChild(tempDiv);
                html2canvas(tempDiv, { scale: 2, backgroundColor: '#ffffff', logging: false, useCORS: true, windowWidth: 800 }).then(canvas => {
                    const imgData = canvas.toDataURL('image/png');
                    const imgW = 190, pageH = 295;
                    const imgH = (canvas.height * imgW) / canvas.width;
                    let left = imgH, pos = 0;
                    doc.addImage(imgData, 'PNG', 10, pos, imgW, imgH);
                    left -= pageH;
                    while (left >= 0) { pos = left - imgH; doc.addPage(); doc.addImage(imgData, 'PNG', 10, pos, imgW, imgH); left -= pageH; }
                    doc.save(`report_${new Date().toISOString().slice(0, 10)}.pdf`);
                    document.body.removeChild(tempDiv);
                    showNotification('PDF downloaded', 'success');
                }).catch(() => { document.body.removeChild(tempDiv); showNotification('PDF error', 'error'); });
            } catch (e) { showNotification('PDF error', 'error'); }
        };

        window.sharePreviewWhatsApp = function () {
            const text = (document.getElementById('reportPreviewContent').innerText || '').substring(0, 4000);
            window.open(`https://wa.me/?text=${encodeURIComponent(text)}`, '_blank');
        };

        window.viewReport = function (id) {
            fetch(`api/reports.php?id=${id}`).then(r => r.json()).then(res => {
                if (res.success && res.data) {
                    document.getElementById('reportPreviewContent').innerHTML = res.data.content || '';
                    document.getElementById('reportPreviewModal').style.display = 'flex';
                }
            });
        };

        window.shareReport = function (id) {
            fetch(`api/reports.php?id=${id}`).then(r => r.json()).then(res => {
                if (res.success && res.data) {
                    const r = res.data;
                    const text = `*SRI LANKA ARMY VMS*\n*Type:* ${(r.type || '').toUpperCase()}\n*Period:* ${r.start_date} to ${r.end_date}\n*By:* ${r.generated_by}\n\n${(r.content || '').replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim().substring(0, 3000)}`;
                    window.open(`https://wa.me/?text=${encodeURIComponent(text)}`, '_blank');
                }
            });
        };

        window.generateMovementReport = function () { document.getElementById('reportType').value = 'movement'; window.generatePDFReport(); };
        window.generateFuelReport = function () { document.getElementById('reportType').value = 'fuel'; window.generatePDFReport(); };
        window.generateServiceReport = function () { document.getElementById('reportType').value = 'service'; window.generatePDFReport(); };

        // ========== DASHBOARD ==========
        function refreshDashboard() {
            // Refresh appState from APIs then update UI
            Promise.all([
                fetch('api/vehicles.php').then(r => r.json()).then(r => { if (r.success) appState.vehicles = r.data || []; }).catch(() => { }),
                fetch('api/drivers.php').then(r => r.json()).then(r => { if (r.success) appState.drivers = r.data || []; }).catch(() => { }),
                fetch('api/movements.php').then(r => r.json()).then(r => { if (r.success) appState.movements = r.data || []; }).catch(() => { }),
                fetch('api/fuel.php?action=stock').then(r => r.json()).then(r => { if (r.success && r.data) appState.fuelStock = r.data; }).catch(() => { })
            ]).finally(() => { updateDashboardStats(); updateDashboardCharts(); updateRecentActivity(); });
        }

        function updateDashboardStats() {
            const vehicles = db.getVehicles();
            const drivers = db.getDrivers();
            const movements = db.getMovements();
            const reports = db.getReports();
            const fuelStock = db.getFuelStock();
            const today = new Date().toISOString().split('T')[0];

            const stats = document.getElementById('dashboardStats');
            if (!stats) return;

            stats.innerHTML = `
                <div class="stat-card">
                    <h3>Total Vehicles</h3>
                    <div class="stat-value">${vehicles ? vehicles.length : 0}</div>
                    <div class="stat-change positive"><i class="fas fa-truck"></i> In Fleet</div>
                </div>
                <div class="stat-card">
                    <h3>Active Vehicles</h3>
                    <div class="stat-value">${vehicles ? vehicles.filter(v => v.status === 'active').length : 0}</div>
                    <div class="stat-change positive"><i class="fas fa-check-circle"></i> On Duty</div>
                </div>
                <div class="stat-card">
                    <h3>Vehicles Out</h3>
                    <div class="stat-value">${movements ? movements.filter(m => m.status === 'out').length : 0}</div>
                    <div class="stat-change warning"><i class="fas fa-sign-out-alt"></i> Currently Out</div>
                </div>
                <div class="stat-card">
                    <h3>Vehicles In</h3>
                    <div class="stat-value">${vehicles ? vehicles.filter(v => v.in_out_status === 'idle').length : 0}</div>
                    <div class="stat-change success"><i class="fas fa-sign-in-alt"></i> In Base</div>
                </div>
                <div class="stat-card">
                    <h3>Available Drivers</h3>
                    <div class="stat-value">${drivers ? drivers.filter(d => d.status === 'idle').length : 0}</div>
                    <div class="stat-change positive"><i class="fas fa-user-check"></i> Available</div>
                </div>
                <div class="stat-card">
                    <h3>Today's Movements</h3>
                    <div class="stat-value">${movements ? movements.filter(m => m.check_out && m.check_out.startsWith(today)).length : 0}</div>
                    <div class="stat-change"><i class="fas fa-calendar-day"></i> In/Out Today</div>
                </div>
                <div class="stat-card">
                    <h3>Fuel Stock (D)</h3>
                    <div class="stat-value">${fuelStock.diesel || 0}L</div>
                    <div class="stat-change"><i class="fas fa-oil-can"></i> Available</div>
                </div>
                <div class="stat-card">
                    <h3>Reports</h3>
                    <div class="stat-value">${reports ? reports.length : 0}</div>
                    <div class="stat-change"><i class="fas fa-file-pdf"></i> Generated</div>
                </div>
            `;
        }

        function updateRecentActivity() {
            const movements = db.getMovements();
            const vehicles = db.getVehicles();
            const drivers = db.getDrivers();
            const services = db.getServiceRecords();
            const activity = document.getElementById('recentActivity');
            if (!activity) return;

            activity.innerHTML = '';

            // Combine and sort activities
            let activities = [];

            movements.forEach(m => {
                activities.push({
                    type: 'movement',
                    status: m.status,
                    vehicle_id: m.vehicle_id,
                    driver_id: m.driver_id,
                    timestamp: m.check_out || m.created_at,
                    data: m
                });
            });

            services.forEach(s => {
                activities.push({
                    type: 'service',
                    vehicle_id: s.vehicle_id,
                    timestamp: s.date,
                    data: s
                });
            });

            activities.sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));

            if (activities.length === 0) {
                activity.innerHTML = '<div class="activity-item"><div class="activity-icon"><i class="fas fa-info"></i></div><div><h4>No recent activity</h4></div></div>';
                return;
            }

            activities.slice(0, 5).forEach(a => {
                const vehicle = vehicles.find(v => v.id === a.vehicle_id);
                const driver = drivers.find(d => d.id === a.driver_id);
                const div = document.createElement('div');
                div.className = 'activity-item';

                if (a.type === 'movement') {
                    div.innerHTML = `
                        <div class="activity-icon"><i class="fas fa-${a.status === 'out' ? 'sign-out-alt' : a.status === 'completed' ? 'sign-in-alt' : 'clock'}"></i></div>
                        <div>
                            <h4>Vehicle ${a.status === 'out' ? 'Checked Out' : a.status === 'completed' ? 'Returned' : 'Movement'}</h4>
                            <p>${vehicle ? vehicle.id : a.vehicle_id} - ${a.data.destination || 'Unknown'} (${driver ? driver.first_name + ' ' + driver.last_name : a.driver_id})</p>
                            <p style="color: var(--text-gray);">${a.timestamp ? new Date(a.timestamp).toLocaleString() : ''} | Auth: ${a.data.authorized_by || 'N/A'}</p>
                        </div>
                    `;
                } else {
                    div.innerHTML = `
                        <div class="activity-icon"><i class="fas fa-tools"></i></div>
                        <div>
                            <h4>Service Record</h4>
                            <p>${vehicle ? vehicle.id : a.vehicle_id} - ${a.data.description || 'Service performed'}</p>
                            <p style="color: var(--text-gray);">${a.timestamp ? new Date(a.timestamp).toLocaleString() : ''} | Cost: LKR ${(a.data.cost || 0).toLocaleString()}</p>
                        </div>
                    `;
                }
                activity.appendChild(div);
            });
        }

        // ========== CHARTS ==========
        let statusChart = null;
        let movementChart = null;

        function initCharts() {
            updateDashboardCharts();
        }

        function updateDashboardCharts() {
            updateStatusPieChart();
            updateMovementBarChart();
        }

        function updateStatusPieChart() {
            const ctx = document.getElementById('statusPieChart')?.getContext('2d');
            if (!ctx) return;

            const vehicles = db.getVehicles() || [];
            const active = vehicles.filter(v => v.status === 'active').length;
            const maintenance = vehicles.filter(v => v.status === 'maintenance').length;
            const inactive = vehicles.filter(v => v.status === 'inactive').length;

            if (statusChart) statusChart.destroy();

            statusChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Active', 'Maintenance', 'Inactive'],
                    datasets: [{
                        data: [active, maintenance, inactive],
                        backgroundColor: ['#4CAF50', '#FF9800', '#D32F2F'],
                        borderColor: '#0d1b1e',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { labels: { color: '#f0f0f0' } }
                    }
                }
            });
        }

        function updateMovementBarChart() {
            const ctx = document.getElementById('movementBarChart')?.getContext('2d');
            if (!ctx) return;

            const movements = db.getMovements() || [];
            const last7Days = [];
            const outData = [];
            const inData = [];

            for (let i = 6; i >= 0; i--) {
                const date = new Date();
                date.setDate(date.getDate() - i);
                const dateStr = date.toISOString().split('T')[0];
                last7Days.push(dateStr);

                const dayMovements = movements.filter(m => m.check_out && m.check_out.startsWith(dateStr));
                outData.push(dayMovements.filter(m => m.status === 'out').length);
                inData.push(dayMovements.filter(m => m.status === 'completed').length);
            }

            if (movementChart) movementChart.destroy();

            movementChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: last7Days.map(d => new Date(d).toLocaleDateString('en-US', { weekday: 'short' })),
                    datasets: [
                        { label: 'Vehicles Out', data: outData, backgroundColor: '#FF9800' },
                        { label: 'Vehicles Returned', data: inData, backgroundColor: '#4CAF50' }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: { ticks: { color: '#f0f0f0' } },
                        y: { ticks: { color: '#f0f0f0' }, beginAtZero: true }
                    },
                    plugins: { legend: { labels: { color: '#f0f0f0' } } }
                }
            });
        }

        // ========== LOAD ALL DATA ==========
        function loadAllData() {
            loadVehicles();
            if (canAccessPage('drivers')) loadDrivers();
            if (canAccessPage('service')) loadServiceRecords();
            if (canAccessPage('fuel')) loadFuelData();
            if (canAccessPage('efficiency')) loadEfficiencyData();
            if (canAccessPage('whatsapp')) loadWhatsAppData();
            if (canAccessPage('gps')) loadGPSData();
            if (canAccessPage('tracking')) loadTrackingData();
            if (canAccessPage('inout')) loadMovementData();
            if (canAccessPage('reports')) loadReportsData();
            refreshDashboard();
            setDefaultDates();
            populateAllSelects();
        }

        function setDefaultDates() {
            const today = new Date().toISOString().split('T')[0];
            const startOfMonth = new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0];

            const serviceDate = document.getElementById('serviceDate');
            if (serviceDate) serviceDate.value = today;

            const fuelDate = document.getElementById('fuelDate');
            if (fuelDate) fuelDate.value = today;

            const reportStart = document.getElementById('reportStartDate');
            if (reportStart) reportStart.value = startOfMonth;

            const reportEnd = document.getElementById('reportEndDate');
            if (reportEnd) reportEnd.value = today;

            const now = new Date();
            now.setHours(now.getHours() + 4);
            const expectedReturn = document.getElementById('expectedReturn');
            if (expectedReturn) expectedReturn.value = now.toISOString().slice(0, 16);

            const checkInTime = document.getElementById('checkInTime');
            if (checkInTime) checkInTime.value = new Date().toISOString().slice(0, 16);

            const oneYearFromNow = new Date();
            oneYearFromNow.setFullYear(oneYearFromNow.getFullYear() + 1);
            const licenseExpiry = document.getElementById('newDriverLicenseExpiry');
            if (licenseExpiry) licenseExpiry.value = oneYearFromNow.toISOString().split('T')[0];
        }

        // ========== POPULATE SELECTS ==========
        function populateAllSelects() {
            populateVehicleSelect('serviceVehicleSelect');
            populateVehicleSelect('slemeVehicleSelect');
            populateVehicleSelect('fuelVehicleSelect');
            populateVehicleSelect('gpsDeviceSelect');
            populateVehicleSelect('manualVehicleSelect');
            populateVehicleSelect('checkOutVehicle');
            populateVehicleSelect('newVehicleDriver', false);
            populateVehicleSelectForDriver('newDriverVehicle', true);
            populateDriverSelect('checkOutDriver');
            populateVehicleSelect('routeVehicleSelect');
            populateRecipientSelect();
        }

        function showVehicleDetails(vehicle, elementId) {
            const el = document.getElementById(elementId);
            if (el && vehicle) el.textContent = `${vehicle.id} | ${vehicle.model || 'Unknown'} | Reg: ${vehicle.registration || 'N/A'} | Status: ${vehicle.in_out_status || 'idle'}`;
        }

        function selectVehicleFromScan(value, mode) {
            const query = String(value || '').trim().toLowerCase();
            const vehicle = appState.vehicles.find(v =>
                String(v.id).toLowerCase() === query ||
                String(v.registration || '').toLowerCase() === query
            );
            if (!vehicle) {
                showNotification('Vehicle number was not found in the database.', 'error');
                return false;
            }
            if (mode === 'checkout') {
                populateVehicleSelect('checkOutVehicle');
                document.getElementById('checkOutVehicle').value = vehicle.id;
                document.getElementById('checkOutOdometer').value = vehicle.current_odometer || 0;
                showVehicleDetails(vehicle, 'checkOutVehicleDetails');
                openCheckOutModal(vehicle.id);
            } else {
                const movement = appState.movements.find(m => m.vehicle_id === vehicle.id && m.status !== 'completed');
                if (!movement) {
                    showNotification('No active check-out was found for this vehicle.', 'error');
                    return false;
                }
                openCheckInModal(movement.id);
            }
            showNotification(`Vehicle ${vehicle.id} identified successfully.`, 'success');
            return true;
        }

        async function startVehicleScanner(mode) {
            if (!('BarcodeDetector' in window) || !navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                const manual = window.prompt('Enter the vehicle ID or registration number:');
                if (manual) selectVehicleFromScan(manual, mode);
                return;
            }
            const detector = new BarcodeDetector({ formats: ['qr_code', 'code_128', 'code_39', 'ean_13'] });
            const overlay = document.createElement('div');
            overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,.9);z-index:9999;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:20px;';
            overlay.innerHTML = '<video autoplay playsinline style="width:min(90vw,520px);border:3px solid #ffd700;border-radius:10px;"></video><p style="color:#ffd700;margin:15px;">Point the camera at the vehicle QR/barcode</p><button type="button" class="submit-btn">CANCEL</button>';
            document.body.appendChild(overlay);
            const video = overlay.querySelector('video');
            const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: { ideal: 'environment' } } });
            video.srcObject = stream;
            let active = true;
            overlay.querySelector('button').onclick = () => { active = false; stream.getTracks().forEach(t => t.stop()); overlay.remove(); };
            const scan = async () => {
                if (!active) return;
                try {
                    const codes = await detector.detect(video);
                    if (codes.length && codes[0].rawValue) {
                        selectVehicleFromScan(codes[0].rawValue, mode);
                        active = false;
                        stream.getTracks().forEach(t => t.stop());
                        overlay.remove();
                        return;
                    }
                } catch (error) {
                    // Continue scanning while the camera is producing frames.
                }
                requestAnimationFrame(scan);
            };
            video.addEventListener('loadeddata', scan, { once: true });
        }

        function populateVehicleSelect(selectId, isMultiple = false) {
            const select = document.getElementById(selectId);
            if (!select) return;
            const vehicles = db.getVehicles() || [];
            const currentValue = select.value;
            if (selectId === 'routeVehicleSelect') {
                select.innerHTML = '<option value="all">All Vehicles (Live Only)</option>';
            } else {
                select.innerHTML = isMultiple ? '' : '<option value="">Select Vehicle</option>';
            }
            vehicles.forEach(v => {
                const option = document.createElement('option');
                option.value = v.id;
                option.textContent = `${v.id} - ${v.model || 'Unknown'}`;
                select.appendChild(option);
            });
            if (currentValue && !isMultiple) { try { select.value = currentValue; } catch (e) { } }
        }

        function populateVehicleSelectForDriver(selectId, isMultiple = false) {
            const select = document.getElementById(selectId);
            if (!select) return;
            const vehicles = db.getVehicles() || [];
            select.innerHTML = '';
            vehicles.forEach(v => {
                const option = document.createElement('option');
                option.value = v.id;
                option.textContent = `${v.id} - ${v.model || 'Unknown'}`;
                select.appendChild(option);
            });
        }

        function populateDriverSelect(selectId) {
            const select = document.getElementById(selectId);
            if (!select) return;
            // Show idle drivers for checkout; show all for filter dropdowns
            const allDrivers = db.getDrivers() || [];
            const drivers = selectId === 'checkOutDriver'
                ? allDrivers.filter(d => d.status === 'idle')
                : allDrivers;
            const currentValue = select.value;
            select.innerHTML = '<option value="">Select Driver</option>';
            drivers.forEach(d => {
                const option = document.createElement('option');
                option.value = d.id;
                option.textContent = `${d.first_name} ${d.last_name} (${d.rank || 'N/A'}) — ${d.status}`;
                select.appendChild(option);
            });
            if (currentValue) { try { select.value = currentValue; } catch (e) { } }
        }

        // ========== USER MANAGEMENT ==========
        function loadUsersPage() {
            const user = window._currentUser || {};
            const isAdmin = user.role === 'admin';
            const isOfficer = user.role === 'officer';

            document.getElementById('usersPageTitle').textContent = isAdmin ? 'User Management' : isOfficer ? 'Users & My Profile' : 'My Profile';
            document.getElementById('addUserBtn').style.display = isAdmin ? 'inline-flex' : 'none';
            // Admin & officer can see user list; others cannot
            document.getElementById('userListSection').style.display = (isAdmin || isOfficer) ? 'block' : 'none';
            document.getElementById('editProfileSection').style.display = 'block';

            // Pre-fill edit profile with current user
            document.getElementById('editProfileId').value = user.id;
            document.getElementById('changePasswordId').value = user.id;
            document.getElementById('editProfileFullName').value = user.full_name || '';
            document.getElementById('editProfileRank').value = user.rank || '';
            document.getElementById('editProfileUsername').value = user.username || '';
            // Username is always read-only — cannot be changed after creation
            document.getElementById('editProfileUsername').readOnly = true;
            document.getElementById('editProfileUsername').style.opacity = '0.6';
            document.getElementById('editProfileUsername').title = 'Username cannot be changed after creation';

            if (isAdmin || isOfficer) loadUserTable(isAdmin);
        }

        function loadUserTable(canManage = false) {
            fetch('api/users.php')
                .then(r => r.json())
                .then(res => {
                    if (!res.success) return;
                    const tbody = document.getElementById('userTableBody');
                    tbody.innerHTML = '';
                    const currentUser = window._currentUser || {};
                    res.data.forEach(u => {
                        const roleBadge = u.role === 'admin' ? 'danger' : u.role === 'officer' ? 'success' : 'info';
                        const isSelf = u.id == currentUser.id;
                        tbody.insertRow().innerHTML = `
                            <td>${u.id}</td>
                            <td>${u.username}</td>
                            <td>${u.full_name}</td>
                            <td>${u.rank}</td>
                            <td><span class="status-badge status-${roleBadge}">${u.role.toUpperCase()}</span></td>
                            <td>${u.created_at ? new Date(u.created_at).toLocaleDateString() : ''}</td>
                            <td>
                                <div class="action-buttons">
                                    ${canManage ? `<button class="edit-btn" onclick="openEditUser(${u.id},'${u.full_name}','${u.rank}','${u.role}')"><i class="fas fa-edit"></i></button>` : ''}
                                    ${canManage && !isSelf ? `<button class="delete-btn" onclick="deleteUser(${u.id})"><i class="fas fa-trash"></i></button>` : ''}
                                </div>
                            </td>
                        `;
                    });
                });
        }

        document.getElementById('addUserBtn').addEventListener('click', () => {
            document.getElementById('addUserForm').reset();
            document.getElementById('addUserModal').style.display = 'flex';
        });

        document.getElementById('addUserForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const pwd = document.getElementById('newUserPassword').value;
            if (pwd !== document.getElementById('newUserConfirmPassword').value) {
                showNotification('Passwords do not match', 'error'); return;
            }
            fetch('api/users.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    username: document.getElementById('newUserUsername').value,
                    password: pwd,
                    full_name: document.getElementById('newUserFullName').value,
                    rank: document.getElementById('newUserRank').value,
                    role: document.getElementById('newUserRole').value
                })
            }).then(r => r.json()).then(res => {
                if (res.success) { loadUserTable(); document.getElementById('addUserModal').style.display = 'none'; this.reset(); showNotification('User created', 'success'); }
                else showNotification(res.message || 'Error', 'error');
            }).catch(() => showNotification('Server unreachable', 'error'));
        });

        window.openEditUser = function (id, fullName, rank, role) {
            document.getElementById('editUserId').value = id;
            document.getElementById('editUserFullName').value = fullName;
            document.getElementById('editUserRank').value = rank;
            document.getElementById('editUserRole').value = role;
            document.getElementById('editUserModal').style.display = 'flex';
        };

        document.getElementById('editUserForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const id = document.getElementById('editUserId').value;
            fetch(`api/users.php?id=${id}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    full_name: document.getElementById('editUserFullName').value,
                    rank: document.getElementById('editUserRank').value,
                    role: document.getElementById('editUserRole').value
                })
            }).then(r => r.json()).then(res => {
                if (res.success) { loadUserTable(); document.getElementById('editUserModal').style.display = 'none'; showNotification('User updated', 'success'); }
                else showNotification(res.message || 'Error', 'error');
            }).catch(() => { });
        });

        window.deleteUser = function (id) {
            if (!confirm('Delete this user?')) return;
            fetch(`api/users.php?id=${id}`, { method: 'DELETE' })
                .then(r => r.json()).then(res => {
                    if (res.success) { loadUserTable(); showNotification('User deleted', 'success'); }
                    else showNotification(res.message || 'Error', 'error');
                });
        };

        document.getElementById('editProfileForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const id = document.getElementById('editProfileId').value;
            const payload = {
                full_name: document.getElementById('editProfileFullName').value,
                rank: document.getElementById('editProfileRank').value
                // username intentionally excluded — cannot be changed after creation
            };
            fetch(`api/users.php?id=${id}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            }).then(r => r.json()).then(res => {
                if (res.success) {
                    // Update in-memory session
                    if (window._currentUser) Object.assign(window._currentUser, payload);
                    document.getElementById('userName').textContent = (window._currentUser || {}).full_name || '';
                    showNotification('Profile updated', 'success');
                } else showNotification(res.message || 'Error', 'error');
            }).catch(() => showNotification('Server unreachable', 'error'));
        });

        document.getElementById('changePasswordForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const newPwd = document.getElementById('newPassword').value;
            if (newPwd !== document.getElementById('confirmPassword').value) {
                showNotification('Passwords do not match', 'error'); return;
            }
            const id = document.getElementById('changePasswordId').value;
            fetch(`api/users.php?id=${id}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    current_password: document.getElementById('currentPassword').value,
                    password: newPwd
                })
            }).then(r => r.json()).then(res => {
                if (res.success) { this.reset(); showNotification('Password changed successfully', 'success'); }
                else showNotification(res.message || 'Error', 'error');
            }).catch(() => showNotification('Server unreachable', 'error'));
        });

        // ========== MODAL CLOSE HANDLERS ==========
        document.querySelectorAll('.close-modal').forEach(btn => {
            btn.addEventListener('click', function () {
                const modal = this.closest('.modal');
                if (modal) modal.style.display = 'none';
            });
        });

        window.addEventListener('click', function (e) {
            if (e.target.classList.contains('modal')) {
                e.target.style.display = 'none';
            }
        });

        // Close buttons for all modals
        document.getElementById('closeVehicleModal')?.addEventListener('click', () => {
            document.getElementById('addVehicleModal').style.display = 'none';
        });

        document.getElementById('closeEditVehicleModal')?.addEventListener('click', () => {
            document.getElementById('editVehicleModal').style.display = 'none';
        });

        document.getElementById('closeDriverModal')?.addEventListener('click', () => {
            document.getElementById('addDriverModal').style.display = 'none';
        });

        document.getElementById('closeEditDriverModal')?.addEventListener('click', () => {
            document.getElementById('editDriverModal').style.display = 'none';
        });

        document.getElementById('closeFuelStockModal')?.addEventListener('click', () => {
            document.getElementById('fuelStockModal').style.display = 'none';
        });

        document.getElementById('closeCheckOutModal')?.addEventListener('click', () => {
            document.getElementById('checkOutModal').style.display = 'none';
        });

        document.getElementById('closeCheckInModal')?.addEventListener('click', () => {
            document.getElementById('checkInModal').style.display = 'none';
        });

        document.getElementById('closeReportModal')?.addEventListener('click', () => {
            document.getElementById('reportPreviewModal').style.display = 'none';
        });

        document.getElementById('closeImageModal')?.addEventListener('click', () => {
            document.getElementById('imagePreviewModal').style.display = 'none';
        });

        // ========== INITIALIZE ==========
        // Always start fresh at login on page load/refresh
        window._currentUser = null;
        document.getElementById('loginPage').style.display = 'flex';
        document.getElementById('systemContainer').style.display = 'none';

        // ======= LIVE DRIVER TRACKING =======
        let autoDbLocationInterval = null;
        let mapAutoRefreshInterval = null;

        function initLiveDriverTracking() {
            if (autoDbLocationInterval) clearInterval(autoDbLocationInterval);
            if (mapAutoRefreshInterval) clearInterval(mapAutoRefreshInterval);

            // Fetch generic vehicle refresh for map every 5 seconds for UI updates for all users
            mapAutoRefreshInterval = setInterval(() => {
                if (window._currentUser) {
                    Promise.all([
                        fetch('api/vehicles.php').then(r => r.json()),
                        fetch('api/gps_tracking.php').then(r => r.json())
                    ]).then(([vRes, gRes]) => {
                        if (vRes.success) appState.vehicles = vRes.data;
                        if (gRes.success) appState.gpsTracking = gRes.data;
                        if (typeof updateTrackingMarkers === 'function') updateTrackingMarkers();
                    }).catch(console.error);
                }
            }, 5000); // 5 seconds

            // Driver-specific DB update
            if (window._currentUser && window._currentUser.role === 'driver') {
                const sendLocation = () => {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(pos => {
                            fetch('api/gps_update.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({
                                    driver_id: window._currentUser.username,
                                    latitude: pos.coords.latitude,
                                    longitude: pos.coords.longitude
                                })
                            }).then(r => r.json()).then(res => {
                                if (res.success && res.updated_vehicles && res.updated_vehicles.length > 0) {
                                    // Call local update silently if possible
                                    res.updated_vehicles.forEach(vid => {
                                        if (typeof updateVehicleLocationAuto === 'function') {
                                            updateVehicleLocationAuto(vid, pos.coords.latitude, pos.coords.longitude, 'driver_gps');
                                        }
                                    });
                                }
                            }).catch(console.error);
                        }, err => console.error(err), { enableHighAccuracy: true });
                    }
                };

                sendLocation();
                autoDbLocationInterval = setInterval(sendLocation, 5000); // 5 secs
            }
        }

        // ======= EMERGENCY SOS MONITORING =======
        let emergencyCheckInterval = null;
        let activeEmergencyAlerts = new Set();

        function initEmergencyMonitoring() {
            if (emergencyCheckInterval) clearInterval(emergencyCheckInterval);
            
            const checkAlerts = () => {
                if (!window._currentUser || window._currentUser.role === 'driver') return; // Drivers don't need to see alerts, admins do!

                fetch('api/emergency.php')
                    .then(r => r.json())
                    .then(res => {
                        if (res.success && res.alerts && res.alerts.length > 0) {
                            res.alerts.forEach(alert => {
                                if (!activeEmergencyAlerts.has(alert.id)) {
                                    activeEmergencyAlerts.add(alert.id);
                                    triggerEmergencyNotification(alert);
                                }
                            });
                        }
                    })
                    .catch(console.error);
            };

            checkAlerts();
            emergencyCheckInterval = setInterval(checkAlerts, 10000); // Check every 10 seconds
        }

        function triggerEmergencyNotification(alert) {
            // Play sound if possible
            try {
                const audio = new Audio('https://actions.google.com/sounds/v1/alarms/alarm_clock_beep.ogg');
                audio.play();
            } catch(e) {}

            const msg = `🚨 EMERGENCY: Driver ${alert.driver_id} (Vehicle: ${alert.vehicle_id}) triggered SOS!`;
            showNotification(msg, 'error', 15000); // Show for 15 seconds

            // If we are on the tracking map, show the alert there
            if (geofenceMap && alert.latitude && alert.longitude) {
                const sosMarker = L.marker([alert.latitude, alert.longitude], {
                    icon: L.divIcon({
                        className: 'sos-marker',
                        html: `<div style="background: red; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; border: 3px solid white; animation: pulse 1s infinite;"><i class="fas fa-exclamation-triangle"></i></div>`,
                        iconSize: [40, 40]
                    })
                }).addTo(geofenceMap);
                
                sosMarker.bindPopup(`
                    <div style="color: black; padding: 5px;">
                        <h4 style="color: red; margin-bottom: 5px;">🚨 EMERGENCY SOS</h4>
                        <p><strong>Driver:</strong> ${alert.driver_id}</p>
                        <p><strong>Vehicle:</strong> ${alert.vehicle_id}</p>
                        <p><strong>Time:</strong> ${new Date(alert.created_at).toLocaleString()}</p>
                        <button onclick="resolveEmergency(${alert.id})" style="background: green; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; margin-top: 10px; width: 100%;">Mark as Resolved</button>
                    </div>
                `).openPopup();
                
                geofenceMap.setView([alert.latitude, alert.longitude], 15);
            }
        }

        window.resolveEmergency = function(id) {
            fetch('api/emergency.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
            }).then(r => r.json()).then(res => {
                if (res.success) {
                    showNotification('Emergency resolved and cleared', 'success');
                    activeEmergencyAlerts.delete(id);
                    // Refresh tracking map if needed
                    if (typeof loadGeofences === 'function') loadGeofences();
                }
            });
        };

        // ========== GEOFENCING LOGIC ==========
        let geofenceMap, geofenceDrawControl, geofenceDrawnItems;
        let activeGeofences = [];
        let activeGeofencePolygons = [];

        function initGeofenceMap() {
            if (geofenceMap) return;
            const mapEl = document.getElementById('geofenceMap');
            if (!mapEl) return;

            geofenceMap = L.map('geofenceMap').setView([7.8731, 80.7718], 7);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(geofenceMap);
            
            geofenceDrawnItems = new L.FeatureGroup();
            geofenceMap.addLayer(geofenceDrawnItems);
            
            loadGeofences();

            geofenceMap.on(L.Draw.Event.CREATED, function (e) {
                const layer = e.layer;
                geofenceDrawnItems.addLayer(layer);
                document.getElementById('drawingControls').style.display = 'block';
                window._lastDrawnLayer = layer;
            });
        }

        function startDrawingGeofence() {
            if (geofenceDrawControl) geofenceMap.removeControl(geofenceDrawControl);
            
            geofenceDrawControl = new L.Control.Draw({
                draw: {
                    polygon: { allowIntersection: false, showArea: true },
                    rectangle: true,
                    polyline: false, circle: false, marker: false, circlemarker: false
                }
            });
            geofenceMap.addControl(geofenceDrawControl);
            showNotification("Draw the boundary on the map", "info");
        }

        async function saveDrawnGeofence() {
            const name = document.getElementById('gfName').value;
            const type = document.getElementById('gfType').value;
            if (!name) return showNotification("Please enter a zone name", "error");
            
            const layer = window._lastDrawnLayer;
            if (!layer) return;

            let latlngs;
            if (layer instanceof L.Rectangle) {
                latlngs = layer.getLatLngs()[0];
            } else {
                latlngs = layer.getLatLngs()[0];
            }
            
            const coords = latlngs.map(p => ({lat: p.lat, lng: p.lng}));
            
            try {
                const res = await fetch('api/geofences.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ name, type, coordinates: coords })
                });
                const data = await res.json();
                if (data.success) {
                    showNotification("Military Zone Saved Successfully!", "success");
                    cancelDrawing();
                    loadGeofences();
                }
            } catch (err) { showNotification("Failed to save zone", "error"); }
        }

        function cancelDrawing() {
            if (geofenceDrawControl) geofenceMap.removeControl(geofenceDrawControl);
            document.getElementById('drawingControls').style.display = 'none';
            document.getElementById('gfName').value = '';
            geofenceDrawnItems.clearLayers();
            window._lastDrawnLayer = null;
        }

        async function loadGeofences() {
            try {
                const res = await fetch('api/geofences.php');
                const data = await res.json();
                if (data.success) {
                    activeGeofences = data.geofences;
                    renderGeofenceList();
                    renderGeofencesOnMap();
                }
            } catch (err) { }
        }

        function renderGeofenceList() {
            const list = document.getElementById('geofenceList');
            if (!list) return;

            list.innerHTML = activeGeofences.map(gf => `
                <div style="background: rgba(255,255,255,0.03); padding:15px; border-radius:8px; border-left:4px solid ${gf.type === 'forbidden' ? 'var(--danger)' : 'var(--success)'}; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: 600; color: #fff; margin-bottom: 4px;">${gf.name}</div>
                        <div style="font-size: 0.75rem; color: ${gf.type === 'forbidden' ? 'var(--danger)' : 'var(--success)'}; text-transform: uppercase;">${gf.type} Zone</div>
                    </div>
                    <button onclick="deleteGeofence(${gf.id})" style="background:rgba(211, 47, 47, 0.1); border:none; color:var(--danger); padding: 8px; border-radius: 6px; cursor:pointer; transition: 0.2s;">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            `).join('');
            
            if (activeGeofences.length === 0) {
                list.innerHTML = '<div style="text-align:center; padding:20px; color:var(--text-gray);">No zones defined yet.</div>';
            }
        }

        function renderGeofencesOnMap() {
            // Remove previous geofence polygons
            if (activeGeofencePolygons) {
                activeGeofencePolygons.forEach(p => geofenceMap.removeLayer(p));
            }
            activeGeofencePolygons = [];

            activeGeofences.forEach(gf => {
                const color = gf.type === 'forbidden' ? 'var(--danger)' : 'var(--success)';
                const polygon = L.polygon(gf.coordinates, {
                    color: color,
                    fillColor: color,
                    fillOpacity: 0.2,
                    weight: 2
                }).addTo(geofenceMap);
                
                polygon.bindPopup(`<strong>${gf.name}</strong><br>Type: ${gf.type.toUpperCase()}`);
                activeGeofencePolygons.push(polygon);
            });
        }
        
        async function deleteGeofence(id) {
            if (!confirm("Are you sure you want to remove this military zone?")) return;
            try {
                const res = await fetch('api/geofences.php?id=' + id, { method: 'DELETE' });
                const data = await res.json();
                if (data.success) {
                    showNotification("Zone removed", "success");
                    loadGeofences();
                }
            } catch (err) { showNotification("Error deleting zone", "error"); }
        }
    </script>
</body>

</html>