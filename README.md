# Inventory Service Task

## Overview

This project is a modular Laravel Inventory Management Microservice built with clean architecture principles and Docker support.

The system manages:

- Products
- Product Stock
- Low Stock Alerts
- Product Statuses
- Swagger/OpenAPI Documentation
- Automated Testing
- Seeders & Factories

The project uses:

- Laravel
- Docker
- MySQL
- Swagger/OpenAPI
- PHPUnit
- Repository Pattern
- Service Layer
- DTOs
- Events & Listeners

---

# Features

## Product Management

- Create Product
- Update Product
- Delete Product (Soft Delete)
- Get Product Details
- List Products with Pagination

## Stock Management

- Increment Stock
- Decrement Stock
- Prevent Invalid Stock Adjustments
- Low Stock Detection
- Out Of Stock Detection

## Product Statuses

Supported statuses:

- active
- inactive
- discontinued

## API Features

- RESTful API
- Standardized API Responses
- API Resources
- Validation Handling
- Swagger Documentation

## Architecture Features

- Modular Structure
- Repository Pattern
- Service Layer
- DTO Pattern
- Request Validation
- Event Driven Design
- Factory & Seeder Support

---

# Project Structure

```txt
app/
└── Modules/
    ├── Core/
    │   ├── Responses/
    │   └── Traits/
    │
    └── Product/
        ├── Contracts/
        ├── DTOs/
        ├── Enums/
        ├── Events/
        ├── Http/
        │   ├── Controllers/
        │   ├── Requests/
        │   └── Resources/
        ├── Listeners/
        ├── Models/
        ├── Repositories/
        ├── Services/
        ├── Database/
        │   ├── Factories/
        │   ├── Migrations/
        │   └── Seeders/
        └── Routes/
```

---

# Database Enhancements

The Product table was enhanced with:

| Column | Type | Description |
|---|---|---|
| sku | string | Unique product code |
| low_stock_threshold | integer | Minimum stock threshold |
| status | enum | Product status |

Indexes added:

- sku index
- status index
- composite index (sku, status)

---

# Product Model Features

The Product model supports:

- Stock adjustment methods
- Low stock detection
- Query scopes
- Status handling
- Soft Deletes

Implemented methods:

- adjustStock()
- canAdjustStock()
- scopeLowStock()
- scopeByStatus()

---

# DTOs

Implemented DTOs:

| DTO | Purpose |
|---|---|
| CreateProductDTO | Product creation |
| UpdateProductDTO | Product update |
| AdjustStockDTO | Stock adjustment |

---

# Validation

Implemented validation for:

- Product Creation
- Product Update
- Stock Adjustment

Validation includes:

- Unique SKU
- Status validation
- Quantity validation
- Required fields
- Numeric validation

---

# API Response Wrapper

Created unified response structure.

Example:

```json
{
  "success": true,
  "message": "Success",
  "data": {}
}
```

Error Example:

```json
{
  "success": false,
  "message": "Validation Error",
  "errors": {}
}
```

---

# Events & Listeners

Implemented:

| Event | Listener |
|---|---|
| StockAdjusted | NotifyLowStockListener |

Features:

- Low stock logging
- Out of stock logging
- Extensible notification architecture

---

# Swagger Documentation

Implemented Swagger/OpenAPI using:

- swagger-php
- l5-swagger
- PHP 8 Attributes

Documentation includes:

- Product schemas
- Request bodies
- Response structures
- Endpoint documentation

Swagger URL:

```txt
http://localhost/api/documentation
```

---

# Factories & Seeders

Implemented:

- ProductFactory
- ProductSeeder
- DatabaseSeeder

Seeder creates:

- Active products
- Low stock products
- Inactive products
- Categories
- Admin user

---

# API Endpoints

| Method | Endpoint | Description |
|---|---|---|
| GET | /api/products | List products |
| POST | /api/products | Create product |
| GET | /api/products/{id} | Get product |
| PUT | /api/products/{id} | Update product |
| DELETE | /api/products/{id} | Delete product |
| POST | /api/products/{id}/stock | Adjust stock |
| GET | /api/products/low-stock | Low stock products |

---

# Docker Setup

## Build & Start Containers

```bash
docker compose up -d --build
```

## Check Running Containers

```bash
docker compose ps
```

## Stop Containers

```bash
docker compose down
```

## Restart Containers

```bash
docker compose restart
```

## Enter App Container

```bash
docker compose exec app bash
```

---

# Laravel Setup

## Install Composer Dependencies

```bash
docker compose exec app composer install
```

## Generate Application Key

```bash
docker compose exec app php artisan key:generate
```

## Clear Laravel Cache

```bash
docker compose exec app php artisan optimize:clear
```

---

# Database Setup

## Run Migrations

```bash
docker compose exec app php artisan migrate
```

## Run Database Seeders

```bash
docker compose exec app php artisan db:seed
```

## Fresh Migration with Seeder

```bash
docker compose exec app php artisan migrate:fresh --seed
```

---

# Swagger Setup

## Generate Swagger Documentation

```bash
docker compose exec app php artisan l5-swagger:generate
```

## Swagger Documentation URL

```txt
http://localhost/api/documentation
```

---

# Testing

## Run All Tests

```bash
docker compose exec app php artisan test
```

## Run Feature Tests Only

```bash
docker compose exec app php artisan test --testsuite=Feature
```

## Run Product API Tests

```bash
docker compose exec app php artisan test tests/Feature/ProductApiTest.php
```

## Run Stock Adjustment Tests

```bash
docker compose exec app php artisan test tests/Feature/StockAdjustmentTest.php
```

---

# Seeder Commands

## Run Specific Seeder

```bash
docker compose exec app php artisan db:seed --class=ProductSeeder
```

---

# Composer Commands

## Install Swagger

```bash
docker compose exec app composer require darkaonline/l5-swagger
```

```bash
docker compose exec app composer require zircote/swagger-php
```

## Update Composer Packages

```bash
docker compose exec app composer update
```

## Refresh Autoload

```bash
docker compose exec app composer dump-autoload
```

---

# Useful Docker Commands

## View All Logs

```bash
docker compose logs
```

## View App Logs

```bash
docker compose logs app
```

## View Database Logs

```bash
docker compose logs db
```

---

# Vendor Sync From Docker To Local

Useful for fixing VSCode/Intelephense warnings.

```bash
docker compose cp app:/var/www/html/vendor ./vendor
```

Or:

```bash
docker compose cp app:/app/vendor ./vendor
```

Then run:

```bash
composer dump-autoload
```

---

# Testing Coverage

Implemented tests for:

- Product CRUD
- Validation Errors
- Pagination
- Stock Adjustment
- Low Stock Logic
- Event Dispatching
- Response Structure
- Error Handling

---

# Technologies Used

| Technology | Purpose |
|---|---|
| PHP 8+ | Backend |
| Laravel | Framework |
| MySQL | Database |
| Docker | Containerization |
| Swagger/OpenAPI | API Documentation |
| PHPUnit | Testing |

---

# Final Notes

This project focuses on:

- Clean Architecture
- Scalable Design
- Maintainable Code
- Modular Development
- Testability
- Production Ready Structure
- Standardized APIs
- SOLID Principles
- Separation of Concerns
