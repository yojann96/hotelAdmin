# 📘 HotelAdmin

Sistema para gestión de hoteles, tipos de habitación y acomodaciones, desarrollado en Laravel (backend) y Vue 3 (frontend).

---

## 🚀 ¿Qué necesito antes de empezar?...

### 🛠 Requisitos previos
- PHP 8.2 o superior
- Composer
- Node.js 18+
- PostgreSQL o Mysql instalado y corriendo
- Git (opcional, pero recomendado)

---

## 📁 Estructura del proyecto
hotelAdmin/
├── hotel-backend/ # Backend Laravel con PostgreSQL
├── hotel-frontend/ # Frontend Vue 3 + Vite
├── dump.sql # Backup de la base de datos
└── README.md # Este archivo


---

## 🧱 Paso a paso para instalar el proyecto (como si fuera mi abuelita 🧓)

### 1. Clona o descarga el proyecto

```bash
git clone https://github.com/TU_USUARIO/hotelAdmin.git
cd hotelAdmin


2. Configura el Backend
cd hotel-backend
cp .env.example .env
Edita el archivo .env y cambia esto por tus datos de PostgreSQL:
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=hotel_system
DB_USERNAME=postgres
DB_PASSWORD=tu_contraseña


Luego ejecuta los siguientes comandos:
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve

El backend estará disponible en http://localhost:8000

3. Restaura la base de datos desde el archivo dump
En una consola de PostgreSQL o MYSQL:
Ejecutar scripts de archivo: ...\hotel-backend\database\hotel_system.sql
Recordar modificar archivo .env para el backend según credenciales
Ver ejemplo en archivo hotel-backend\.env - ejemplo

--------------

4. Configura el Frontend
cd ../hotel-frontend
npm install
npm run dev
npm install vue-router@4
npm install @vitejs/plugin-vue --save-dev
npm install axios


