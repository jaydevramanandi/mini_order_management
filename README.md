# Mini Order Management API

A Laravel-based RESTful API for managing products and orders with authentication.

## Features

- **User Authentication** - Register, login, logout with Laravel Sanctum
- **Product Management** - Full CRUD operations for products
- **Order Management** - Create and view orders with order items
- **API Authentication** - Token-based authentication using Laravel Sanctum

## Tech Stack

- **Framework**: Laravel 11.x
- **Authentication**: Laravel Sanctum
- **Database**: MySQL/SQLite (configurable)
- **API**: RESTful JSON API

## Installation

1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   ```
3. Copy environment file:
   ```bash
   cp .env.example .env
   ```
4. Generate application key:
   ```bash
   php artisan key:generate
   ```
5. Run migrations:
   ```bash
   php artisan migrate
   ```
6. Start the development server:
   ```bash
   php artisan serve
   ```

## API Endpoints

### Authentication

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/auth/register` | Register new user |
| POST | `/api/auth/login` | Login user |
| POST | `/api/auth/logout` | Logout user (protected) |
| GET | `/api/auth/me` | Get current user (protected) |

### Products

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/products` | List all products |
| GET | `/api/products/{id}` | Get single product |
| POST | `/api/products` | Create product (protected) |
| PUT | `/api/products/{id}` | Update product (protected) |
| DELETE | `/api/products/{id}` | Delete product (protected) |

### Orders

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/orders` | List all orders (protected) |
| POST | `/api/orders` | Create new order (protected) |
| GET | `/api/orders/{id}` | Get single order (protected) |

## Request/Response Examples

### Register
```json
POST /api/auth/register
{
    “name”: “John Doe”,
    “email”: “john@example.com”,
    “password”: “password123”
}
```

### Login
```json
POST /api/auth/login
{
    “email”: “john@example.com”,
    “password”: “password123”
}
```

### Create Order
```json
POST /api/orders
{
    “items”: [
        { “product_id”: 1, “quantity”: 2 },
        { “product_id”: 2, “quantity”: 1 }
    ]
}
```

## Authentication

All protected routes require a Bearer token in the Authorization header:
```
Authorization: Bearer {your_token}
```

## License

MIT License
