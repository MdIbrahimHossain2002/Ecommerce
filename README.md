# ShopHub E-Commerce

Monorepo with separate **backend** (Laravel API) and **frontend** (React) folders.

```
Ecommerce/
├── backend/     # Laravel 10 API
├── frontend/    # React + Vite storefront & admin
└── SETUP.md     # Full setup instructions
```

## Quick Start

### 1. Backend
```bash
cd backend
composer install
php artisan migrate
php artisan db:seed
php artisan serve
```

### 2. Frontend
```bash
cd frontend
npm install
npm run dev
```

- **Store**: http://localhost:5173
- **API**: http://localhost:8000/api
- **Admin**: `admin@gmail.com` / `password`

See [SETUP.md](./SETUP.md) for detailed instructions.
