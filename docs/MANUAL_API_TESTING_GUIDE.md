# 🎯 MANUAL API TESTING GUIDE - BRANCH API

## **📋 DAFTAR ENDPOINT UNTUK TESTING**

### **Basic CRUD Operations**
```
GET    /api/branches           - List all branches
POST   /api/branches           - Create new branch  
GET    /api/branches/{id}      - Get specific branch
PUT    /api/branches/{id}      - Update branch
DELETE /api/branches/{id}      - Delete branch
```

### **Custom Endpoints**
```
GET    /api/branches/filter/active              - Get active branches only
GET    /api/branches/analytics/statistics       - Get branch statistics
POST   /api/branches/bulk/update-status         - Bulk update status
GET    /api/branches/search/advanced            - Advanced search
```

---

## **🧪 MANUAL TESTING SCENARIOS**

### **Scenario 1: Basic CRUD Testing**

#### **1.1 Create Branch (POST)**
```bash
# Using curl
curl -X POST http://localhost:8000/api/branches \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "branch_name": "Jakarta Pusat",
    "branch_address": "Jl. Sudirman No. 123, Jakarta",
    "branch_telephone": "021-12345678"
  }'
```

**Expected Response:**
```json
{
  "success": true,
  "message": "Branch created successfully",
  "data": {
    "id": 1,
    "branch_name": "Jakarta Pusat",
    "branch_address": "Jl. Sudirman No. 123, Jakarta",
    "branch_telephone": "021-12345678",
    "is_active": true,
    "status": "Aktif",
    "display_name": "✅ Jakarta Pusat"
  }
}
```

#### **1.2 Get All Branches (GET)**
```bash
curl -X GET http://localhost:8000/api/branches \
  -H "Accept: application/json"
```

**Expected Response:**
```json
{
  "data": [
    {
      "id": 1,
      "branch_name": "Jakarta Pusat",
      "is_active": true,
      "status": "Aktif"
    }
  ],
  "meta": {
    "total": 1,
    "active_count": 1,
    "inactive_count": 0,
    "percentage_active": 100
  }
}
```

#### **1.3 Get Specific Branch (GET)**
```bash
curl -X GET http://localhost:8000/api/branches/1 \
  -H "Accept: application/json"
```

#### **1.4 Update Branch (PUT)**
```bash
curl -X PUT http://localhost:8000/api/branches/1 \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "branch_name": "Jakarta Pusat Updated",
    "branch_address": "Jl. Sudirman No. 456, Jakarta",
    "branch_telephone": "021-87654321",
    "is_active": false
  }'
```

#### **1.5 Delete Branch (DELETE)**
```bash
curl -X DELETE http://localhost:8000/api/branches/1 \
  -H "Accept: application/json"
```

---

### **Scenario 2: Validation Testing**

#### **2.1 Create with Missing Required Fields**
```bash
curl -X POST http://localhost:8000/api/branches \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "branch_address": "Alamat saja"
  }'
```

**Expected Response (422):**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "branch_name": ["Nama cabang wajib diisi."],
    "branch_telephone": ["Telepon cabang wajib diisi."]
  }
}
```

#### **2.2 Create with Invalid Data Length**
```bash
curl -X POST http://localhost:8000/api/branches \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "branch_name": "AB",
    "branch_address": "XY", 
    "branch_telephone": "12"
  }'
```

---

### **Scenario 3: Search and Filtering**

#### **3.1 Search Branches**
```bash
# Create multiple branches first, then search
curl -X GET "http://localhost:8000/api/branches?search=Jakarta" \
  -H "Accept: application/json"
```

#### **3.2 Filter by Status**
```bash
# Filter active branches
curl -X GET "http://localhost:8000/api/branches?status=active" \
  -H "Accept: application/json"

# Filter inactive branches  
curl -X GET "http://localhost:8000/api/branches?status=inactive" \
  -H "Accept: application/json"
```

#### **3.3 Combined Search and Filter**
```bash
curl -X GET "http://localhost:8000/api/branches?search=Jakarta&status=active" \
  -H "Accept: application/json"
```

---

### **Scenario 4: Custom Endpoints**

#### **4.1 Get Active Branches Only**
```bash
curl -X GET http://localhost:8000/api/branches/filter/active \
  -H "Accept: application/json"
```

#### **4.2 Get Branch Statistics**
```bash
curl -X GET http://localhost:8000/api/branches/analytics/statistics \
  -H "Accept: application/json"
```

**Expected Response:**
```json
{
  "success": true,
  "data": {
    "total_branches": 5,
    "active_branches": 3,
    "inactive_branches": 2,
    "latest_branch": {
      "branch_name": "Latest Branch",
      "created_at": "2025-08-25T10:30:00Z"
    },
    "city_distribution": {
      "Jakarta": 2,
      "Surabaya": 1
    }
  }
}
```

#### **4.3 Advanced Search**
```bash
# Search with specific fields
curl -X GET "http://localhost:8000/api/branches/search/advanced?q=jakarta&fields[]=name&status=active" \
  -H "Accept: application/json"
```

#### **4.4 Bulk Update Status**
```bash
curl -X POST http://localhost:8000/api/branches/bulk/update-status \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "branch_ids": [1, 2, 3],
    "status": true
  }'
```

---

### **Scenario 5: Error Handling**

#### **5.1 Non-existent Branch (404)**
```bash
curl -X GET http://localhost:8000/api/branches/999999 \
  -H "Accept: application/json"
```

**Expected Response (404):**
```json
{
  "success": false,
  "message": "Cabang tidak ditemukan!"
}
```

#### **5.2 Invalid ID Format (400)**
```bash
curl -X GET http://localhost:8000/api/branches/invalid-id \
  -H "Accept: application/json"
```

**Expected Response (400):**
```json
{
  "success": false,
  "message": "Invalid branch ID format"
}
```

---

## **✅ TESTING CHECKLIST**

### **Functional Testing:**
- [ ] Can create new branch with valid data
- [ ] Can retrieve list of all branches
- [ ] Can get specific branch by ID
- [ ] Can update existing branch
- [ ] Can delete existing branch
- [ ] Can search branches by name/address/phone
- [ ] Can filter branches by status
- [ ] Can get active branches only
- [ ] Can get branch statistics
- [ ] Can perform bulk operations

### **Validation Testing:**
- [ ] Rejects empty required fields
- [ ] Rejects data too short (min validation)
- [ ] Rejects data too long (max validation)
- [ ] Rejects duplicate branch names
- [ ] Validates phone number format
- [ ] Validates boolean fields

### **Error Handling:**
- [ ] Returns 404 for non-existent resources
- [ ] Returns 400 for invalid ID format
- [ ] Returns 422 for validation errors
- [ ] Returns 500 for server errors (gracefully)
- [ ] Consistent error response format

### **Response Format:**
- [ ] Consistent JSON structure
- [ ] Proper HTTP status codes
- [ ] Includes metadata in collections
- [ ] Resources format data correctly
- [ ] Timestamps in human-readable format
- [ ] Boolean values properly cast

### **Performance:**
- [ ] Responds quickly (< 1 second)
- [ ] Handles large datasets
- [ ] Pagination works correctly
- [ ] Search is efficient

---

## **🔧 DEBUGGING TIPS**

### **Check Logs:**
```bash
tail -f storage/logs/laravel.log
```

### **Clear Cache:**
```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

### **Database Check:**
```bash
php artisan tinker
>>> App\Models\Branch::count()
>>> App\Models\Branch::all()
```

### **Route List:**
```bash
php artisan route:list --name=api.branches
```

---

## **📱 POSTMAN COLLECTION**

Import this JSON to Postman for easy testing:

```json
{
  "info": {
    "name": "Branch API Tests",
    "description": "Complete test collection for Branch API"
  },
  "item": [
    {
      "name": "Get All Branches",
      "request": {
        "method": "GET",
        "header": [{"key": "Accept", "value": "application/json"}],
        "url": "{{base_url}}/api/branches"
      }
    },
    {
      "name": "Create Branch",
      "request": {
        "method": "POST",
        "header": [
          {"key": "Accept", "value": "application/json"},
          {"key": "Content-Type", "value": "application/json"}
        ],
        "body": {
          "raw": "{\n  \"branch_name\": \"Test Branch\",\n  \"branch_address\": \"Test Address\",\n  \"branch_telephone\": \"021-12345678\"\n}"
        },
        "url": "{{base_url}}/api/branches"
      }
    }
  ],
  "variable": [
    {
      "key": "base_url",
      "value": "http://localhost:8000"
    }
  ]
}
```

Gunakan manual testing guide ini untuk memvalidasi API Anda! 🚀
