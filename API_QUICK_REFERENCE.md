# API Quick Reference

## Base URL
```
http://localhost:8000/api/products
```

## Endpoints Summary

| Method | Endpoint | Purpose | Status |
|--------|----------|---------|--------|
| GET | `/api/products` | List all products (paginated) | 200 |
| POST | `/api/products` | Create new product | 201 |
| GET | `/api/products/{id}` | Get product details | 200 |
| PUT | `/api/products/{id}` | Update product | 200 |
| DELETE | `/api/products/{id}` | Soft delete product | 200 |
| POST | `/api/products/{id}/stock` | Adjust stock quantity | 200 |
| GET | `/api/products/active` | Get active products | 200 |
| GET | `/api/products/low-stock` | Get low stock products | 200 |
| GET | `/api/products/category/{id}` | Get products by category | 200 |

## Common Request/Response Examples

### 1. List Products

**Request:**
```bash
curl -X GET "http://localhost:8000/api/products?per_page=10" \
  -H "Accept: application/json"
```

**Response (200):**
```json
{
  "success": true,
  "message": "Products retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "Product Name",
      "slug": "product-slug",
      "sku": "SKU-001",
      "description": "Product description",
      "price": "99.99",
      "stock_quantity": 50,
      "low_stock_threshold": 10,
      "status": "active",
      "is_active": true,
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z"
    }
  ],
  "meta": {
    "pagination": {
      "total": 100,
      "per_page": 10,
      "current_page": 1,
      "last_page": 10,
      "from": 1,
      "to": 10
    }
  }
}
```

### 2. Create Product

**Request:**
```bash
curl -X POST "http://localhost:8000/api/products" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Laptop",
    "slug": "laptop-dell-xps-13",
    "sku": "LAPTOP-DELL-001",
    "description": "High-performance laptop",
    "price": 1299.99,
    "stock_quantity": 25,
    "low_stock_threshold": 5,
    "status": "active",
    "category_id": 1,
    "is_active": true
  }'
```

**Response (201):**
```json
{
  "success": true,
  "message": "Product created successfully",
  "data": {
    "id": 101,
    "name": "Laptop",
    "slug": "laptop-dell-xps-13",
    "sku": "LAPTOP-DELL-001",
    "description": "High-performance laptop",
    "price": "1299.99",
    "stock_quantity": 25,
    "low_stock_threshold": 5,
    "status": "active",
    "is_active": true,
    "created_at": "2024-01-15T12:00:00Z",
    "updated_at": "2024-01-15T12:00:00Z"
  },
  "meta": {}
}
```

### 3. Get Single Product

**Request:**
```bash
curl -X GET "http://localhost:8000/api/products/1" \
  -H "Accept: application/json"
```

**Response (200):**
```json
{
  "success": true,
  "message": "Product retrieved successfully",
  "data": {
    "id": 1,
    "name": "Product Name",
    "slug": "product-slug",
    "sku": "SKU-001",
    "description": "Product description",
    "price": "99.99",
    "stock_quantity": 50,
    "low_stock_threshold": 10,
    "status": "active",
    "is_active": true,
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:30:00Z"
  },
  "meta": {}
}
```

### 4. Update Product

**Request:**
```bash
curl -X PUT "http://localhost:8000/api/products/1" \
  -H "Content-Type: application/json" \
  -d '{
    "price": 129.99,
    "stock_quantity": 75,
    "low_stock_threshold": 15
  }'
```

**Response (200):**
```json
{
  "success": true,
  "message": "Product updated successfully",
  "data": {
    "id": 1,
    "name": "Product Name",
    "slug": "product-slug",
    "sku": "SKU-001",
    "description": "Product description",
    "price": "129.99",
    "stock_quantity": 75,
    "low_stock_threshold": 15,
    "status": "active",
    "is_active": true,
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T14:30:00Z"
  },
  "meta": {}
}
```

### 5. Adjust Stock (Increase)

**Request:**
```bash
curl -X POST "http://localhost:8000/api/products/1/stock" \
  -H "Content-Type: application/json" \
  -d '{
    "quantity": 10,
    "reason": "restock_received"
  }'
```

**Response (200):**
```json
{
  "success": true,
  "message": "Stock adjusted successfully",
  "data": {
    "id": 1,
    "name": "Product Name",
    "slug": "product-slug",
    "sku": "SKU-001",
    "stock_quantity": 60,
    "low_stock_threshold": 10,
    "status": "active",
    "updated_at": "2024-01-15T15:30:00Z"
  },
  "meta": {}
}
```

### 6. Adjust Stock (Decrease)

**Request:**
```bash
curl -X POST "http://localhost:8000/api/products/1/stock" \
  -H "Content-Type: application/json" \
  -d '{
    "quantity": -5,
    "reason": "sale"
  }'
```

**Response (200):**
```json
{
  "success": true,
  "message": "Stock adjusted successfully",
  "data": {
    "id": 1,
    "name": "Product Name",
    "stock_quantity": 55,
    "updated_at": "2024-01-15T16:30:00Z"
  },
  "meta": {}
}
```

### 7. Delete Product

**Request:**
```bash
curl -X DELETE "http://localhost:8000/api/products/1" \
  -H "Accept: application/json"
```

**Response (200):**
```json
{
  "success": true,
  "message": "Product deleted successfully",
  "data": null,
  "meta": {}
}
```

### 8. Get Low Stock Products

**Request:**
```bash
curl -X GET "http://localhost:8000/api/products/low-stock?threshold=10" \
  -H "Accept: application/json"
```

**Response (200):**
```json
{
  "success": true,
  "message": "Low stock products retrieved successfully",
  "data": [
    {
      "id": 2,
      "name": "Low Stock Item",
      "sku": "SKU-002",
      "stock_quantity": 8,
      "low_stock_threshold": 10
    }
  ],
  "meta": {}
}
```

### 9. Get Active Products

**Request:**
```bash
curl -X GET "http://localhost:8000/api/products/active" \
  -H "Accept: application/json"
```

**Response (200):**
```json
{
  "success": true,
  "message": "Active products retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "Active Product",
      "status": "active",
      "is_active": true
    }
  ],
  "meta": {}
}
```

## Error Examples

### 404 Not Found

**Request:**
```bash
curl -X GET "http://localhost:8000/api/products/9999"
```

**Response (404):**
```json
{
  "success": false,
  "message": "Product not found",
  "errors": {},
  "data": null
}
```

### 422 Validation Error

**Request:**
```bash
curl -X POST "http://localhost:8000/api/products" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test"
  }'
```

**Response (422):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "slug": ["The product slug is required."],
    "sku": ["The SKU is required."],
    "price": ["The price is required."],
    "stock_quantity": ["The stock quantity is required."]
  },
  "data": null
}
```

### 400 Bad Request (Insufficient Stock)

**Request:**
```bash
curl -X POST "http://localhost:8000/api/products/1/stock" \
  -H "Content-Type: application/json" \
  -d '{
    "quantity": -100
  }'
```

**Response (400):**
```json
{
  "success": false,
  "message": "Cannot adjust stock. Insufficient stock or invalid adjustment.",
  "errors": {},
  "data": null
}
```

## Query Parameters

### Pagination
```bash
GET /api/products?per_page=20
GET /api/products?per_page=50&page=2
```

### Low Stock Threshold
```bash
GET /api/products/low-stock?threshold=5
GET /api/products/low-stock?threshold=20
```

## Status Codes

| Code | Meaning | Usage |
|------|---------|-------|
| 200 | OK | Successful GET, PUT, DELETE, POST (stock adjust) |
| 201 | Created | Successful POST (create product) |
| 400 | Bad Request | Invalid adjustment, business logic failure |
| 401 | Unauthorized | Missing/invalid authentication |
| 403 | Forbidden | Insufficient permissions |
| 404 | Not Found | Product doesn't exist |
| 422 | Unprocessable Entity | Validation error |
| 500 | Server Error | Internal server error |

## Quick Testing with cURL

```bash
# Create a product
PRODUCT=$(curl -s -X POST "http://localhost:8000/api/products" \
  -H "Content-Type: application/json" \
  -d '{
    "name":"Test Product",
    "slug":"test-product",
    "sku":"TEST-001",
    "description":"Test",
    "price":99.99,
    "stock_quantity":50,
    "is_active":true
  }')

PRODUCT_ID=$(echo $PRODUCT | jq -r '.data.id')

# Adjust stock
curl -X POST "http://localhost:8000/api/products/$PRODUCT_ID/stock" \
  -H "Content-Type: application/json" \
  -d '{"quantity":10,"reason":"restock"}'

# Get updated product
curl -X GET "http://localhost:8000/api/products/$PRODUCT_ID"

# Delete product
curl -X DELETE "http://localhost:8000/api/products/$PRODUCT_ID"
```
