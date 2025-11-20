# HRMS API Documentation

## Base URL

```
http://localhost:8080/api
```

## Authentication

All API endpoints require authentication using Laravel Sanctum. Include the authentication token in the request header:

```
Authorization: Bearer {token}
```

## Response Format

### Success Response
```json
{
    "data": {...},
    "message": "Success message"
}
```

### Error Response
```json
{
    "message": "Error message",
    "errors": {
        "field": ["Error details"]
    }
}
```

## Endpoints

### Employee Management

#### Get All Employees
```
GET /api/employees
```

**Query Parameters:**
- `search`: Search term
- `department_id`: Filter by department
- `per_page`: Items per page (default: 20)

**Response:**
```json
{
    "data": [
        {
            "id": 1,
            "employee_id": "EMP001",
            "first_name": "John",
            "last_name": "Doe",
            "email": "john.doe@example.com",
            "department": {
                "id": 1,
                "name": "IT"
            },
            "designation": {
                "id": 1,
                "name": "Developer"
            }
        }
    ],
    "links": {...},
    "meta": {...}
}
```

#### Get Employee
```
GET /api/employees/{id}
```

#### Create Employee
```
POST /api/employees
```

**Request Body:**
```json
{
    "employee_id": "EMP001",
    "first_name": "John",
    "last_name": "Doe",
    "email": "john.doe@example.com",
    "department_id": 1,
    "designation_id": 1,
    "joining_date": "2024-01-01",
    "employment_type": "full_time"
}
```

#### Update Employee
```
PUT /api/employees/{id}
```

#### Delete Employee
```
DELETE /api/employees/{id}
```

### Attendance

#### Check In
```
POST /api/attendance/check-in
```

**Request Body:**
```json
{
    "employee_id": 1,
    "latitude": 19.0760,
    "longitude": 72.8777,
    "method": "gps"
}
```

#### Check Out
```
POST /api/attendance/check-out
```

### Leave Management

#### Get All Leaves
```
GET /api/leaves
```

#### Create Leave Request
```
POST /api/leaves
```

**Request Body:**
```json
{
    "employee_id": 1,
    "leave_type_id": 1,
    "start_date": "2024-01-15",
    "end_date": "2024-01-17",
    "reason": "Personal work"
}
```

#### Approve Leave
```
PUT /api/leaves/{id}/approve
```

#### Reject Leave
```
PUT /api/leaves/{id}/reject
```

**Request Body:**
```json
{
    "rejection_reason": "Insufficient leave balance"
}
```

### Payroll

#### Get Payroll Records
```
GET /api/payroll
```

**Query Parameters:**
- `year`: Year (default: current year)
- `month`: Month (1-12)
- `employee_id`: Filter by employee

#### Run Payroll
```
POST /api/payroll/run
```

**Request Body:**
```json
{
    "year": 2024,
    "month": 1,
    "employee_ids": [1, 2, 3]
}
```

## Rate Limiting

API endpoints are rate-limited:
- **Authenticated**: 60 requests per minute
- **Unauthenticated**: 20 requests per minute

## Error Codes

- `200`: Success
- `201`: Created
- `400`: Bad Request
- `401`: Unauthorized
- `403`: Forbidden
- `404`: Not Found
- `422`: Validation Error
- `500`: Server Error

## Pagination

Paginated responses include:
- `data`: Array of items
- `links`: Pagination links
- `meta`: Pagination metadata

## Filtering & Sorting

Most list endpoints support:
- `search`: Text search
- `sort`: Sort field
- `order`: Sort direction (asc/desc)
- `per_page`: Items per page

## Swagger Documentation

Interactive API documentation is available at:
```
http://localhost:8080/api/documentation
```

