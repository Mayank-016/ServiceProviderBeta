## Service Booking App (Laravel 12)

A web-based **Service Booking Application** built with **Laravel 12** and **MySQL**. This app allows users to browse, book, and manage services online. Admins can manage service listings, bookings, and users through a clean interface.

---

## 🚀 Features

- User registration & login
- Browse and book services
- Booking history and status tracking
- Admin panel for managing services & users
- Notifications and email confirmations

---

## ✅ Requirements

- PHP 8.2 or higher
- Composer
- MySQL (5.7+ or MariaDB 10.2+)
- Node.js & NPM (for front-end assets, optional)
- PHP extensions:
  - `pdo`, `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`
  - `xml`, `ctype`, `json`, `bcmath`, `fileinfo`

---

## 🧩 Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd service-booking-app
````

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Configure Environment

```bash
cp .env.example .env
```

Update `.env` with your MySQL credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Database Migrations

```bash
php artisan migrate
```

(Optional) Seed the database with default data:

```bash
php artisan db:seed
```

---

## 🖥️ Run the Development Server

```bash
php artisan serve
```

Open your browser and navigate to:
[http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 🌐 Frontend Assets (Optional)

If your app uses custom CSS/JS via Laravel Mix or Vite:

```bash
npm install
npm run dev
```

---

## 🧪 Testing

To run tests (if available):

```bash
php artisan test
```

---

## 🔐 Security

Make sure you:

* Don’t expose `.env` in production.
* Set appropriate permissions for storage and bootstrap/cache:

```bash
chmod -R 775 storage bootstrap/cache
```
