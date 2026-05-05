# BRSEC e-Docs

A secure document management platform for BRSEC, developed with Laravel.

---

## Overview

BRSEC e-Docs is a web-based document management system designed for internal organizational use.

The system supports:
- User authentication
- Role-based access control
- Document management
- Admin dashboard

---

## Features

- 🔐 User login system  
- 👥 Role-based access control  
- 📊 Admin dashboard  
- 📄 Document listing and management  
- 🧑‍💼 User management  
- 📝 Activity log tracking  
- 📱 Responsive UI  

---

## Tech Stack

- Laravel  
- PHP  
- MySQL  
- Blade Template  
- Tailwind CSS / Vite  
- JavaScript  

---

## Security

Sensitive files are not included in this repository.

Create your own environment file:

```bash
cp .env.example .env
php artisan key:generate
```

---

## Installation

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build
```
---

## Run Project

```bash
php artisan serve
```

Developer

Thanawat Sangkasen (Bank)
