# PHP JWT Authentication System - ITS Steve Jobs Academy

This repository contains a PHP-based JWT (JSON Web Token) authentication system developed at the ITS Steve Jobs Academy. The system provides a secure way to handle user authentication, authorization, and account management using JWT tokens.

## Repository Structure

The repository is organized into functional modules:

- **auth/**: Authentication system core
  - Authentication controller
  - User repository
  - JWT configuration and middleware
  - Authentication providers

- **database/**: Database connectivity
  - Connection provider
  - Transaction management

- **products/**: Product management module
  - Product controller
  - Product repository
  - Product service providers

- **public/**: Public-facing web interfaces
  - Authentication pages (login, register, account management)
  - CSS styling
  - Main index page

- **utils/**: Utility functions
  - Response handling
  - Token validation
  - Role management
  - Input validation
  - Error messages

## Key Features

- **JWT-based Authentication**: Secure token-based authentication system
- **Role-based Authorization**: Different access levels (admin, user)
- **Account Management**:
  - User registration
  - Login/logout functionality
  - Email and password updates
  - Account deletion
- **Token Management**:
  - Access tokens
  - Refresh tokens
  - Token validation and renewal
- **Admin Functions**:
  - User management
  - Role assignment
  - Account activation/deactivation
- **Product Management**:
  - CRUD operations for products
  - Visibility control

## Technical Implementation

### Authentication Flow

```php
// User login process
// 1. Validate credentials
// 2. Generate JWT tokens
// 3. Store tokens in session
// 4. Redirect to protected area
```

### JWT Token Structure

```php
// JWT payload structure
// - User ID
// - Email
// - Role
// - Issuer
// - Audience
// - Expiration time
```

### Security Features

```php
// Password hashing with Argon2id/Bcrypt
// Token validation and refresh mechanism
// Role-based access control
```

## How to Use

### Requirements

- PHP 8.0 or higher
- Composer for dependency management
- MySQL/MariaDB database
- Web server (Apache/Nginx)

### Installation

1. Clone the repository:
```bash
git clone https://github.com/your-username/its-php-jwt.git
```

2. Install dependencies:
```bash
composer install
```

3. Configure environment variables:
```bash
# Create .env file with the following variables
SECRET=your_jwt_secret
ISSUER=your_issuer
AUDIENCE=your_audience
EXP=token_expiration_time
HEAD=token_header
ACCESS_TOKEN_EXP=access_token_expiration
REFRESH_TOKEN_EXP=refresh_token_expiration
```

4. Set up the database:
```sql
-- Create auth table
CREATE TABLE auth (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(50) DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL
);

-- Create products table
CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  visible BOOLEAN DEFAULT TRUE
);
```

## Authentication Endpoints

- **/public/auth/register.php**: User registration
- **/public/auth/login.php**: User login
- **/public/auth/update.php**: Account management (update email/password, delete account)

## Security Considerations

- Passwords are hashed using secure algorithms
- JWT tokens have configurable expiration times
- Refresh token mechanism for extended sessions
- Input validation to prevent injection attacks
- Role-based access control for protected resources

## Educational Objectives

This project was designed to:
- Demonstrate secure authentication practices
- Implement JWT-based authorization
- Create a structured PHP application
- Apply MVC-like patterns in PHP
- Implement role-based access control

---

*This repository was created as part of the educational journey at ITS Steve Jobs Academy.*