# Contact Tracing Application

A web-based contact tracing system for the Department of Computer Engineering that tracks entry and exit of students, faculty, guests, and visitors.

## Application Overview

Users register their information (ID, name, address, contact) when visiting the office for the first time. On subsequent visits, they only need to provide their ID number to sign in. The system automatically timestamps all entries and exits. Administrators can search and view user logs based on various criteria.

## Use Case Diagram

```mermaid
graph TB
    User((User))
    Admin((Administrator))
    System[["Contact Tracing System"]]
    
    User -->|Register| UC1["Register - Enter Full Details"]
    User -->|Sign In| UC2["Sign In - Retrieve Info by ID"]
    User -->|Sign Out| UC3["Sign Out - Log Exit"]
    
    Admin -->|Search & View| UC4["Search Users by Name"]
    Admin -->|Search & View| UC5["Search Users by Location"]
    Admin -->|Search & View| UC6["Search Users by ID"]
    Admin -->|Search & View| UC7["Search by Entry Time/Date"]
    Admin -->|View| UC8["View User Entry/Exit Logs"]
    
    UC1 -.->|timestamp| System
    UC2 -.->|retrieve| System
    UC3 -.->|timestamp| System
    UC4 -.->|query| System
    UC5 -.->|query| System
    UC6 -.->|query| System
    UC7 -.->|query| System
    UC8 -.->|retrieve| System
    
    style UC1 fill:#e1f5ff
    style UC2 fill:#e1f5ff
    style UC3 fill:#e1f5ff
    style UC4 fill:#fff3e0
    style UC5 fill:#fff3e0
    style UC6 fill:#fff3e0
    style UC7 fill:#fff3e0
    style UC8 fill:#fff3e0
```

## Entity-Relationship Diagram (ERD)

```mermaid
erDiagram
    USERS ||--o{ SIGN_IN_OUT : logs
    ADMIN ||--o{ USERS : manages
    
    USERS {
        int id PK "Primary Key"
        string usc_id UK "USC ID (unique, nullable)"
        string first_name "First Name"
        string middle_name "Middle Name"
        string last_name "Last Name"
        string barangay "Barangay (required)"
        string city "City/Town (required)"
        string province "Province (required)"
        string contact_number "Phone Number"
        string email "Email Address"
        datetime created_at "Registration Timestamp"
        datetime updated_at "Last Updated"
    }
    
    SIGN_IN_OUT {
        int id PK "Primary Key"
        int user_id FK "Foreign Key to Users"
        enum action "IN or OUT"
        datetime timestamp "Entry/Exit Timestamp"
    }
    
    ADMIN {
        int id PK "Primary Key"
        string username "Username (unique)"
        string password "Hashed Password"
        datetime created_at "Account Creation"
    }
```

## Database Schema

### Users Table
- **Primary Key**: `id`
- **Unique Keys**: `usc_id` (nullable for guests/visitors)
- Stores: Name (First, Middle, Last), Address (Barangay, City, Province), Contact (Phone, Email)
- Timestamps: `created_at` (registration), `updated_at`

### Sign In/Out Table
- **Primary Key**: `id`
- **Foreign Key**: `user_id` → Users
- **Action**: 'IN' or 'OUT' flag
- **Timestamp**: Automatically recorded entry/exit time

### Admin Table
- **Primary Key**: `id`
- **Unique Key**: `username`
- Stores: Username, Password (hashed or hardcoded)

## Features

### User Features
- ✅ **First-time Registration**: Enter full details (ID, name, address, contact)
- ✅ **Quick Sign In**: Retrieve previous info using ID number
- ✅ **Sign Out**: Log exit from office
- ✅ **Automatic Timestamps**: System records entry/exit times

### Administrator Features
- 🔍 **Search by Name**: First name or Last name
- 🔍 **Search by Location**: Barangay, City, or Province
- 🔍 **Search by ID**: USC ID number
- 🔍 **Search by Time/Date**: View entries for specific dates/times
- 📋 **View Logs**: Complete entry/exit history for users

## Project Structure

```
├── src/
│   └── Models/
│       ├── User.php              # User entity (code-first)
│       ├── Contact.php           # Sign in/out logs
│       └── Admin.php             # Admin entity
├── database/
│   └── migrations/               # Auto-generated SQL migrations
├── bin/
│   └── console.php               # CLI for running migrations
├── composer.json                 # Dependencies
├── .env                          # Configuration
└── doctrine.php                  # ORM setup
```

## Setup Instructions

1. **Configure Database**:
   ```bash
   cp .env.example .env
   # Edit .env with your MySQL credentials
   ```

2. **Install Dependencies**:
   ```bash
   composer install
   ```

3. **Generate Migrations** (from code-first models):
   ```bash
   composer run-script migrate:generate
   ```

4. **Run Migrations**:
   ```bash
   composer run-script migrate
   ```

## Code-First Workflow

1. Modify entity classes in `src/Models/`
2. Run `composer run-script migrate:generate` to create SQL migrations
3. Review generated migration files in `database/migrations/`
4. Execute with `composer run-script migrate`

## Technology Stack

- **Backend**: PHP 8.0+
- **ORM**: Doctrine ORM
- **Database**: MySQL
- **Migrations**: Doctrine Migrations (code-first)
- **Environment**: XAMPP
