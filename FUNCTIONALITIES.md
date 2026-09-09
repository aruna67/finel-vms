# Sri Lanka Army Vehicle Management System — Functionalities & User Stories

> Last updated: 2026-03-31  
> All data is DB-backed (MySQL/MariaDB). No localStorage used. Session is in-memory only.

---

## Role-Based Access Control

| Feature | Admin | Officer | Other (Driver etc.) |
|---|---|---|---|
| All sidebar pages | ✅ | ✅ | Dashboard + My Profile only |
| Add / Edit / Delete vehicles | ✅ | ✅ | ❌ |
| Add / Edit / Delete drivers | ✅ | ✅ | ❌ |
| View all users list | ✅ | ✅ (view only) | ❌ |
| Add / Edit / Delete users | ✅ | ❌ | ❌ |
| My Profile / Change Password | ✅ | ✅ | ✅ |
| Username field | 🔒 locked for all roles | | |

---

## 1. Authentication

| Story | Description | Status |
|---|---|---|
| Login | Username + password login via API (SHA-256 hashed) | ✅ |
| Logout | Clears in-memory session, returns to login screen | ✅ |
| Fresh session | Every page refresh requires re-login (no auto-restore) | ✅ |
| Role enforcement | UI adapts on login based on role (admin/officer/other) | ✅ |

---

## 2. Dashboard

| Story | Description | Status |
|---|---|---|
| Fleet stats | Total vehicles, active vehicles, vehicles out, idle vehicles, available drivers, today's movements, diesel stock, report count | ✅ |
| Vehicle status chart | Pie chart: Active / Maintenance / Inactive | ✅ |
| Movement bar chart | Last 7 days check-out/return counts | ✅ |
| Recent activity feed | Latest movements and service records with timestamps | ✅ |
| Refresh | Manual refresh button reloads all stats from DB | ✅ |

---

## 3. Vehicle In/Out

| Story | Description | Status |
|---|---|---|
| S1 — Routine checkout | Select vehicle + driver, enter destination, purpose, expected return, authorizing officer + code (1234) | ✅ |
| S2 — Emergency dispatch | Same checkout form; optional fields skippable | ✅ |
| S3 — Check-in | Select active movement, enter fuel level, condition, return notes, officer auth | ✅ |
| S4 — Overdue monitoring | Vehicles past expected return highlighted red with OVERDUE badge | ✅ |
| S5 — Movement filters | Filter active movements by vehicle, driver, or date | ✅ |
| S26 — Auth code | Officer authorization code required for both checkout and check-in | ✅ |
| Status lifecycle | New vehicle → **idle** → checkout → **out** → check-in → **idle** | ✅ |
| Driver status sync | Checkout sets driver → **on_duty**; check-in sets driver → **idle** | ✅ |
| Summary cards | Idle (Available) count + Vehicles Out count | ✅ |
| Active movements table | Shows all non-completed movements with Check In button | ✅ |
| Today's history table | Completed movements for today with duration | ✅ |
| Authorize pending | Officer can authorize pending movements | ✅ |

---

## 4. Vehicle Inventory

| Story | Description | Status |
|---|---|---|
| S6 — Register vehicle | Unique Vehicle ID + Registration, category, model, year, fuel type/capacity, unit, driver, work ticket | ✅ |
| S7 — Duplicate prevention | Frontend + DB-level check on Vehicle ID and Registration with inline field errors | ✅ |
| S8 — Assign driver | Assign driver during vehicle creation or edit | ✅ |
| S9 — Quick dispatch | Check Out button per row pre-selects vehicle in checkout form | ✅ |
| S10 — Image update | Camera button per row triggers image upload | ✅ |
| Vehicle Book Image | Upload during creation (bottom of form) | ✅ |
| Vehicle Photo | Upload during creation (bottom of form, side-by-side with book image) | ✅ |
| Edit vehicle | Update category, model, registration, fuel type/level, status | ✅ |
| Delete vehicle | Remove vehicle with confirmation | ✅ |
| Fuel allocation shortcut | Fuel button per row redirects to fuel allocation | ✅ |
| Status badges | Active (green) / Maintenance (orange) / Inactive (red) | ✅ |
| In/Out badge | Idle (green) / Out (orange) | ✅ |

---

## 5. Driver Management

| Story | Description | Status |
|---|---|---|
| S11 — Add driver | ID, name, rank, license class/expiry, phone, WhatsApp, assigned vehicles, initial status | ✅ |
| S12 — License expiry warning | ≤30 days = warning badge; expired = danger badge in driver table | ✅ |
| S13 — Assign to vehicle | Multi-select vehicle assignment in driver form | ✅ |
| S14 — Quick contact | Call and WhatsApp buttons per driver row | ✅ |
| S15 — Deactivate driver | Status: Idle / On Duty / Off Duty | ✅ |
| Edit driver | Update name, rank, license expiry, phone, WhatsApp, status | ✅ |
| Delete driver | Remove with confirmation | ✅ |
| Status badges | Idle (green) / On Duty (orange) / Off (red) | ✅ |
| Checkout filter | Only **idle** drivers shown in checkout driver dropdown | ✅ |

---

## 6. Service Records

| Story | Description | Status |
|---|---|---|
| S16 — Log service | Vehicle, date, type (routine/repair/inspection/emergency), cost, description, technician | ✅ |
| S17 — View history | All records sorted by date descending | ✅ |
| S18 — Delete record | Remove incorrect entries (admin/officer only) | ✅ |
| S25 — Breakdown case | Check-in with condition = damaged/needs_maintenance auto-sets vehicle status → Maintenance | ✅ |
| Type badges | Routine (green) / Repair (orange) / Emergency (red) / Inspection (blue) | ✅ |

---

## 7. Fuel Management

| Story | Description | Status |
|---|---|---|
| S19 — Monitor stock | Diesel and petrol stock levels with progress bars and thresholds | ✅ |
| S20 — Low fuel alert | Toast warning fires when stock ≤ threshold on page load | ✅ |
| S21 — Allocate fuel | Vehicle, fuel type, amount, date, purpose | ✅ |
| S22 — Adjust stock | Add / Remove / Set stock with notes | ✅ |
| S23 — Transaction log | Last 20 transactions with type, vehicle, amount, balance | ✅ |
| Manage stock modal | Set diesel/petrol levels and low-stock thresholds | ✅ |
| Delete transaction | Remove incorrect fuel entries | ✅ |

---

## 8. WhatsApp Integration

| Story | Description | Status |
|---|---|---|
| Send message | Send to all drivers or a specific driver via WhatsApp web link | ✅ |
| Message types | Alert / Status Update / Reminder / Emergency | ✅ |
| Message history | Last 20 messages loaded from DB | ✅ |
| Persist messages | All sent messages saved to `whatsapp_messages` table | ✅ |

---

## 9. GPS Tracker

| Story | Description | Status |
|---|---|---|
| Browser GPS | Start/stop/get current location via browser Geolocation API | ✅ |
| Manual location | Set lat/lng per vehicle manually | ✅ |
| GPS device config | Configure device type, ID, update interval per vehicle | ✅ |
| Device status cards | Shows device ID, type, battery, last update, coordinates | ✅ |
| Tracking history | Last 10 GPS updates with source and accuracy | ✅ |
| Live map | OpenStreetMap/Leaflet map with current GPS position | ✅ |

---

## 10. Vehicle Tracking

| Story | Description | Status |
|---|---|---|
| Live map | All vehicles plotted with color-coded markers (active/maintenance/inactive) | ✅ |
| Tracking table | Vehicle ID, type, model, coordinates, last update, source, accuracy, status | ✅ |
| Focus on map | Click View button to zoom map to that vehicle | ✅ |
| Refresh | Reload all vehicle locations | ✅ |

---

## 11. Reports & Sharing

| Story | Description | Status |
|---|---|---|
| S27 — Generate report | Types: Daily, Weekly, Monthly, Movement, Fuel, Service | ✅ |
| Date range | Custom start/end date selection | ✅ |
| Live data | Report fetches fresh data from all APIs at generation time | ✅ |
| PDF download | html2canvas + jsPDF with multi-page support | ✅ |
| WhatsApp share | Share report preview text via WhatsApp | ✅ |
| Save to DB | Generated reports saved to `reports` table | ✅ |
| View saved report | Load report content from DB by ID | ✅ |
| Share saved report | Share any saved report via WhatsApp | ✅ |
| Quick reports | One-click Movement / Fuel / Service report buttons | ✅ |
| Recent reports table | Last 50 reports with type, date range, generated by, actions | ✅ |

---

## 12. User Management

| Story | Description | Status |
|---|---|---|
| List users | All users with ID, username, name, rank, role, created date (admin + officer) | ✅ |
| Add user | Username, password, full name, rank, role (admin only) | ✅ |
| Edit user | Update name, rank, role (admin only) | ✅ |
| Delete user | Cannot delete self (admin only) | ✅ |
| My Profile | Edit full name and rank (all users) | ✅ |
| Change password | Requires current password verification (all users) | ✅ |
| Username locked | Username field is read-only after creation for all roles | ✅ |

---

## 13. Cross-Module Scenarios

| Story | Description | Status |
|---|---|---|
| S24 — Full lifecycle | Add vehicle → assign driver → allocate fuel → check out → check in → service record | ✅ |
| S25 — Breakdown | Check in as damaged → vehicle auto → Maintenance status | ✅ |
| S26 — Auth prevention | Officer code required on every checkout and check-in | ✅ |

---

## Tech Stack

| Layer | Technology |
|---|---|
| Frontend | Vanilla JS, HTML5, CSS3 |
| Charts | Chart.js |
| Maps | Leaflet + OpenStreetMap |
| PDF | jsPDF + html2canvas |
| Backend | PHP 8.x (REST API) |
| Database | MySQL / MariaDB (`army_vms` schema) |
| File storage | `uploads/vehicles/` for vehicle images |

---

## Database Tables

| Table | Purpose |
|---|---|
| `users` | System users with roles |
| `vehicles` | Vehicle fleet with status and images |
| `drivers` | Driver records with license and contact info |
| `driver_vehicles` | Many-to-many driver-vehicle assignments |
| `movements` | Vehicle check-out / check-in records |
| `fuel_stock` | Current diesel and petrol stock levels |
| `fuel_transactions` | All fuel allocation and stock update events |
| `service_records` | Vehicle maintenance and repair history |
| `gps_devices` | GPS device configurations per vehicle |
| `gps_tracking` | Historical GPS location data |
| `reports` | Generated report metadata and HTML content |
| `whatsapp_messages` | Sent WhatsApp message log |

---

## API Endpoints

| Endpoint | Methods | Purpose |
|---|---|---|
| `api/login.php` | POST | Authenticate user |
| `api/vehicles.php` | GET, POST, PUT, DELETE | Vehicle CRUD |
| `api/drivers.php` | GET, POST, PUT, DELETE | Driver CRUD |
| `api/movements.php` | GET, POST, PUT | Check-out / Check-in |
| `api/fuel.php` | GET, POST, DELETE | Fuel stock and transactions |
| `api/service_records.php` | GET, POST, DELETE | Service record CRUD |
| `api/users.php` | GET, POST, PUT, DELETE | User management |
| `api/reports.php` | GET, POST | Report save and retrieve |
| `api/whatsapp.php` | GET, POST | WhatsApp message log |
