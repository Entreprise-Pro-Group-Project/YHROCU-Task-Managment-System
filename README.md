README


# Workflow Management System – Team 12

A custom-built workflow management system designed for the Yorkshire and Humber Regional Organised Crime Unit (YHROCU) to manage non-crime-related activity efficiently.

## 🔧 Tech Stack

- **Frontend:** React.js, Tailwind CSS, Blade
- **Backend:** PHP (Laravel Framework)
- **Database:** MySQL
- **Auth:** Laravel Breeze (with some Livewire integration)
- **Deployment:** Local server environment

---

## 🚀 Features

### ✅ User Management
- Admins can add, edit, and delete users
- Role-based access (admin, supervisor, user)
- Invite via email with role assignment

### ✅ Task & Project Management
- Supervisors can create/edit tasks and projects
- Admins have delete permissions
- Tasks can have custom fields and are manually assigned

### ✅ Access Control
- Users can only access tasks/projects they are assigned
- Central login page with secure session handling

### ✅ Progress Tracking
- Timestamped logs for each action
- Admins can view descending task logs
- Logs exportable as CSV/PDF

### ✅ Notifications
- Email alerts for new task assignments

### ✅ Security
- Auto logout after 30 mins of inactivity
- Encrypted passwords
- Secure reset via email

---

## 🛠️ Setup Instructions

### 📦 Prerequisites
- Node.js + npm
- PHP (>=8.1) + Composer
- MySQL
- Git

### 🚚 Installation

#### 1. Clone the repo
```bash
git clone https://github.com/Entreprise-Pro-Group-Project/YHROCU-Task-Managment-System.git
cd WMS
```

#### 2. Setup Laravel backend
```bash
/WMS

composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

#### 3. Setup React frontend
```bash
/WMS

npm install
npm run dev
```

---


## 📄 License

MIT License 

Copyright (c) 2025 Team 12

---

