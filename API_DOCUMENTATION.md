# LAMDAKU CMS Backend API Documentation

## Base URL
```
http://127.0.0.1:8000/api
```

## Public API Endpoints (untuk Frontend React)

### Services API
- **GET** `/v1/services` - Get all active services
- **GET** `/v1/services/{id}` - Get specific service
- **POST** `/v1/services` - Create new service
- **PUT/PATCH** `/v1/services/{id}` - Update service
- **DELETE** `/v1/services/{id}` - Delete service

### Timeline API
- **GET** `/v1/timelines` - Get all active timeline events
- **GET** `/v1/timelines/{id}` - Get specific timeline event
- **POST** `/v1/timelines` - Create new timeline event
- **PUT/PATCH** `/v1/timelines/{id}` - Update timeline event
- **DELETE** `/v1/timelines/{id}` - Delete timeline event

### Pages API
- **GET** `/v1/pages` - Get all published pages
- **GET** `/v1/pages/{id}` - Get specific page
- **POST** `/v1/pages` - Create new page
- **PUT/PATCH** `/v1/pages/{id}` - Update page
- **DELETE** `/v1/pages/{id}` - Delete page

### Contact API
- **GET** `/v1/contacts` - Get all contacts
- **POST** `/v1/contacts` - Submit contact form
- **GET** `/v1/contacts/{id}` - Get specific contact
- **PUT/PATCH** `/v1/contacts/{id}` - Update contact
- **DELETE** `/v1/contacts/{id}` - Delete contact
- **PATCH** `/v1/contacts/{id}/mark-as-read` - Mark contact as read

## Admin API Endpoints (untuk CMS Dashboard)

### Admin Services
- **GET** `/admin/services` - Get all services (including inactive)
- **POST** `/admin/services` - Create new service
- **GET** `/admin/services/{id}` - Get specific service
- **PUT/PATCH** `/admin/services/{id}` - Update service
- **DELETE** `/admin/services/{id}` - Delete service

### Admin Timeline
- **GET** `/admin/timelines` - Get all timeline events
- **POST** `/admin/timelines` - Create new timeline event
- **GET** `/admin/timelines/{id}` - Get specific timeline event
- **PUT/PATCH** `/admin/timelines/{id}` - Update timeline event
- **DELETE** `/admin/timelines/{id}` - Delete timeline event

### Admin Pages
- **GET** `/admin/pages` - Get all pages
- **POST** `/admin/pages` - Create new page
- **GET** `/admin/pages/{id}` - Get specific page
- **PUT/PATCH** `/admin/pages/{id}` - Update page
- **DELETE** `/admin/pages/{id}` - Delete page

### Admin Contacts
- **GET** `/admin/contacts` - Get all contacts
- **GET** `/admin/contacts/{id}` - Get specific contact
- **PUT/PATCH** `/admin/contacts/{id}` - Update contact
- **DELETE** `/admin/contacts/{id}` - Delete contact
- **PATCH** `/admin/contacts/{id}/mark-as-read` - Mark contact as read

## Sample API Responses

### Services Response
```json
[
  {
    "id": 1,
    "title": "Akreditasi Klinik",
    "slug": "akreditasi-klinik",
    "description": "Layanan akreditasi profesional untuk klinik kesehatan dengan standar nasional dan internasional.",
    "content": "Detailed content...",
    "icon": "hospital",
    "image": null,
    "price": null,
    "is_active": true,
    "sort_order": 1,
    "created_at": "2025-06-11T02:46:04.000000Z",
    "updated_at": "2025-06-11T02:46:04.000000Z"
  }
]
```

### Timeline Response
```json
[
  {
    "id": 1,
    "year": 2008,
    "title": "Pendirian LAMDAKU",
    "description": "LAMDAKU didirikan sebagai lembaga akreditasi kesehatan pertama di Indonesia dengan fokus pada peningkatan mutu pelayanan kesehatan.",
    "icon": "building",
    "is_active": true,
    "sort_order": 1,
    "created_at": "2025-06-11T02:46:04.000000Z",
    "updated_at": "2025-06-11T02:46:04.000000Z"
  }
]
```

## CORS Configuration

API sudah dikonfigurasi untuk mendukung CORS dari frontend React di `http://localhost:3000`.

## Database

- **Database Name**: `lamdaku_cms`
- **Tables**: `pages`, `services`, `contacts`, `timelines`, `users`, `cache`, `jobs`

## How to Use with React Frontend

### Fetch Services
```javascript
const response = await fetch('http://127.0.0.1:8000/api/v1/services');
const services = await response.json();
```

### Submit Contact Form
```javascript
const response = await fetch('http://127.0.0.1:8000/api/v1/contacts', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    name: 'John Doe',
    email: 'john@example.com',
    phone: '08123456789',
    company: 'PT Example',
    subject: 'Inquiry',
    message: 'Hello, I need information about your services.'
  })
});
```

### Fetch Timeline
```javascript
const response = await fetch('http://127.0.0.1:8000/api/v1/timelines');
const timeline = await response.json();
```
