# 🏥 Clinic PWA – Clinic Management System

A Progressive Web Application (PWA) built using Laravel for managing clinic operations efficiently. The system streamlines patient registration, appointment scheduling, doctor consultations, prescription management, and administrative tasks through role-based dashboards.

---

## 🚀 Features

### 🔐 Authentication & Authorization
- Secure user authentication
- Role-based access control
- Super Admin, Doctor, and Receptionist dashboards
- Middleware-protected routes

### 👨‍⚕️ Patient Management
- Register new patients
- Edit and update patient information
- Patient history management
- Search patients by name or ID

### 📅 Appointment Management
- Generate daily appointment tokens
- Patient check-in system
- Mark patients as arrived
- Mark patients as not present with remarks
- Daily token reset

### 👨‍⚕️ Doctor Dashboard
- View pending patients
- View arrived patients
- View consultation history
- Complete consultations

### 💊 Prescription Management
- Create digital prescriptions
- Diagnosis and treatment notes
- Medicine search with auto-suggestions
- Automatic medicine category selection
- Dose pattern selection
- Start time selection
- Printable PDF prescriptions

### 📚 Masters Management
- Medicine Categories
- Medicine Master
- Dose Master
- Start Time Master

### 📊 Reports
- Daily appointment reports
- Consultation reports
- Patient statistics

---

# 🛠️ Tech Stack

## Backend
- Laravel
- PHP
- Eloquent ORM

## Frontend
- Blade Templates
- Bootstrap
- Tailwind CSS
- JavaScript

## Database
- MySQL

## PDF Generation
- Barryvdh Laravel DomPDF

---

# 📂 Project Structure

```
app/
bootstrap/
config/
database/
public/
resources/
routes/
storage/
```

---

# ⚙️ Installation

## Clone Repository

```bash
git clone https://github.com/SubhanPeerzade/clinic-pwa.git
```

## Navigate to Project

```bash
cd clinic-pwa
```

## Install Dependencies

```bash
composer install
```

```bash
npm install
```

## Configure Environment

```bash
cp .env.example .env
```

Update your database credentials inside `.env`.

## Generate Application Key

```bash
php artisan key:generate
```

## Run Migrations

```bash
php artisan migrate
```

## Seed Database

```bash
php artisan db:seed
```

or

```bash
php artisan db:seed --class=LoginUsersSeeder
```

## Start Development Server

```bash
php artisan serve
```

Visit:

```
http://127.0.0.1:8000
```

---

# 👥 User Roles

- Super Admin
- Doctor
- Receptionist

Each role has dedicated dashboards and permissions.

---

# 📋 Main Modules

- Authentication
- Patient Management
- Appointment Management
- Reception Dashboard
- Doctor Dashboard
- Prescription Management
- Medicine Master
- Dose Master
- Start Time Master
- Reports
- Role Management

---

# 📸 Screenshots

You can add screenshots here.

Example:

```
screenshots/
    login.png
    dashboard.png
    prescription.png
```

---

# 📈 Future Enhancements

- SMS Notifications
- WhatsApp Appointment Alerts
- Email Notifications
- Online Appointment Booking
- Patient Portal
- Medical History Uploads
- Billing & Invoicing
- Inventory Management
- Multi-Clinic Support
- Analytics Dashboard

---

# 🤝 Contributing

Contributions are welcome.

1. Fork the repository
2. Create your feature branch

```bash
git checkout -b feature/NewFeature
```

3. Commit changes

```bash
git commit -m "Added new feature"
```

4. Push

```bash
git push origin feature/NewFeature
```

5. Open a Pull Request

---

# 👨‍💻 Author

**Subhan Peerzade**

- GitHub: https://github.com/SubhanPeerzade
- LinkedIn: *(Add your LinkedIn profile URL here)*

---

# ⭐ Support

If you found this project helpful, please consider giving it a ⭐ on GitHub.