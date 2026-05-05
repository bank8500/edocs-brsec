# BRSEC e-Docs

A secure document management platform for BRSEC, developed with Laravel and deployed in a production environment.

---

## Overview

BRSEC e-Docs is a web-based document management system designed for internal organizational use.

The system provides:
- Secure user authentication
- Role-based access control (RBAC)
- Document management and tracking
- Administrative dashboard

---

## Features

- 🔐 User authentication system  
- 👥 Role-based access control  
- 📊 Admin dashboard  
- 📄 Document management system  
- 🧑‍💼 User management  
- 📝 Activity logging  
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

## Project Structure

This project follows a production deployment structure:


edocs.brsec.ac.th/
├── laravel_app/ # Laravel application (core logic)
│ ├── app/
│ ├── routes/
│ ├── config/
│ └── ...
│
├── public_html/ # Public web root (served by web server)
│ ├── index.php
│ ├── .htaccess
│ ├── uploads/
│ └── ...


### Explanation

- **laravel_app/**  
  Contains the full Laravel application including business logic, controllers, models, and configurations.

- **public_html/**  
  Acts as the web root (DocumentRoot) for the server.  
  Requests are routed through `index.php` to the Laravel application.

---

## Security

Sensitive files are not included in this repository.

Create your own environment file:

```bash
cp .env.example .env
php artisan key:generate
```


Installation (Local Development)

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build

```

Running Locally
```bash
php artisan serve
```

Deployment Notes
- The project is deployed using a separated structure:
  - Laravel core in laravel_app
  - Public files in public_html
- Server configuration points to public_html as the document root
- .env file must be configured on the server
- Storage and cache must be writable

Developer

Thanawat Sangkhansen (Bank)
