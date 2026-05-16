# ShopHub E-Commerce Setup Guide

## Project Structure

```
Ecommerce/
├── backend/     # Laravel 10 API (PHP)
└── frontend/    # React + Vite + Tailwind (JavaScript)
```

## Requirements

- PHP 8.1+ with extensions: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`
- Composer
- Node.js 18+
- MySQL (XAMPP/WAMP) running on port 3306

## Backend Setup

1. Start MySQL and create database:
```sql
CREATE DATABASE Ecommerce;
```

2. Go to backend folder and configure `.env`:
```bash
cd backend
```

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Ecommerce
DB_USERNAME=root
DB_PASSWORD=
```

3. Run migrations and seed demo data:
```bash
php artisan migrate
php artisan db:seed
```

4. Start Laravel API server:
```bash
php artisan serve
```

**Admin Login**: `admin@gmail.com` / `password`

## Frontend Setup

```bash
cd frontend
npm install
npm run dev
```

Open http://localhost:5173

The frontend proxies `/api` requests to `http://127.0.0.1:8000` (backend must be running).

## API Endpoints

### Public
- `GET /api/home` - Homepage data
- `GET /api/products` - Product listing
- `GET /api/products/{slug}` - Product details
- `GET /api/categories` - Categories

### Auth (Bearer token)
- `POST /api/register`, `POST /api/login`
- `GET /api/cart`, `POST /api/cart`
- `POST /api/orders` - Place order
- `GET /api/wishlist`

### Admin
- `GET /api/admin/dashboard`
- CRUD: `/api/admin/products`, `/orders`, `/customers`, etc.

## Features Implemented

### Storefront
- Homepage with banners, categories, featured/bestseller/new/sale products
- Product search, filter, sort
- Product detail with variants, reviews, related products
- Shopping cart with coupon codes
- Checkout (COD, bKash, Nagad, Rocket, SSLCommerz, Stripe, PayPal, Bank Transfer)
- Wishlist, user registration/login
- CMS pages (About, Contact, Privacy, Terms), FAQ, Newsletter

### Admin Panel
- Dashboard with stats and recent orders
- Product, category, brand management
- Order status management
- Customer management (block/unblock)
- Coupon management
- Review moderation
- Banner management
- Inventory tracking
- Site settings

## Demo Coupon Codes
- `WELCOME10` - 10% off (min ৳500)
- `FLAT200` - ৳200 off (min ৳1000)
