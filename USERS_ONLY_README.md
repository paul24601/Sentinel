# 👥 USERS ONLY SQL FILE

## 📁 **File Created: `users_only.sql`**

### **📊 Users Data Summary:**

**Total Users: 24**

#### **👨‍💼 Role Distribution:**
- **Admins**: 8 users
  - Aeron Paul Daliva (000000)
  - Mariela Ilustre Segura (000001)
  - Alexander Ocampo (011203)
  - Romart Canda (105367)
  - Jade Cordova (105368)
  - Ronaldo Gamol Casaje Jr (306968)
  - Jessie Mallorca Castro (307583)
  - Arvin Esparas (307921)

- **Adjusters**: 6 users
  - Llander Elicor Poliquit (309125)
  - Jade Eduardo Derramas (309246)
  - Sherwin Ramos Sernechez (309325)
  - Kaishu San Jose (309487)
  - Gilbert John Colo Delos Reyes (309535)

- **Quality Control Inspection**: 3 users
  - Aeron Paul QCI Daliva (000111)
  - Albert Gutierrez (306132)
  - Vernabe Bernabe (307477)

- **Quality Assurance Engineer**: 3 users
  - Aeron Paul Daliva QA (010101)
  - Juswell Navallo (105466)
  - James Aldea (303642)

- **Quality Assurance Supervisor**: 4 users
  - John Nero Abreu (308193)
  - Carl Francisco (309582)
  - Ian Ilustresimo (309603)
  - Stephanie Iris Sapno (309787)

- **Supervisors**: 2 users
  - Rolando Corpuz Guarin (301863)
  - Rafael Balasbas Galvez (302997)

### **🔐 Security Features:**
- ✅ All passwords are **bcrypt hashed** ($2y$10$...)
- ✅ **Password changed flags** tracked for security
- ✅ **Role-based access control** implemented
- ✅ **Unique ID numbers** for each user

### **🛡️ Safe Import Features:**
- ✅ `CREATE TABLE IF NOT EXISTS` - Won't overwrite existing table
- ✅ `INSERT IGNORE INTO` - Skips duplicate users
- ✅ **Smart primary key creation** - Only adds if missing
- ✅ **Error suppression** - Handles existing constraints gracefully

### **💾 Usage Instructions:**

#### **Method 1: Command Line**
```bash
mysql -u username -p database_name < users_only.sql
```

#### **Method 2: PowerShell (Windows)**
```powershell
Get-Content "users_only.sql" | mysql -u username -p database_name
```

#### **Method 3: phpMyAdmin**
1. Go to your database
2. Click "Import" tab
3. Choose `users_only.sql`
4. Click "Go"

#### **Method 4: MySQL Workbench**
1. Open connection
2. File → Run SQL Script
3. Select `users_only.sql`

### **🎯 What This File Does:**

1. **Creates users table** (if it doesn't exist)
2. **Inserts 24 user records** (skips if they already exist)
3. **Adds primary key** (only if table doesn't have one)
4. **Maintains data integrity** (no conflicts or duplicates)

### **📋 Verification After Import:**

```sql
-- Check user count
SELECT COUNT(*) as total_users FROM users;

-- View users by role
SELECT role, COUNT(*) as count 
FROM users 
GROUP BY role 
ORDER BY count DESC;

-- Check admin users
SELECT id_number, full_name, role 
FROM users 
WHERE role = 'admin';

-- Verify table structure
DESCRIBE users;
```

### **🔍 Expected Results:**
- **24 total users** imported
- **All roles represented** (6 different role types)
- **No duplicate ID numbers**
- **Primary key on id_number** field
- **UTF-8 character encoding** support

## ✅ **READY TO IMPORT**

This standalone SQL file contains only the essential users data you need and can be safely imported into any existing Sentinel database without conflicts! 🚀

**File Location**: `c:\xampp\htdocs\Sentinel\users_only.sql`
