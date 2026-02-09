# Quick Test Execution Guide

## 🚀 Run All Tests

```bash
php artisan test
```

## Run Specific Test Files

### Authentication Tests
```bash
php artisan test tests/Feature/AuthenticationTest.php
```

### Search Tests
```bash
php artisan test tests/Feature/SearchTest.php
```

### Admin Package Management Tests
```bash
php artisan test tests/Feature/AdminPackageManagementTest.php
```

### Admin Agency Management Tests
```bash
php artisan test tests/Feature/AdminAgencyManagementTest.php
```

### User Account Tests
```bash
php artisan test tests/Feature/UserAccountTest.php
```

### Role-Based Access Control Tests
```bash
php artisan test tests/Feature/RoleBasedAccessControlTest.php
```

### Security & Validation Tests
```bash
php artisan test tests/Feature/SecurityAndValidationTest.php
```

## Run Tests with Coverage Report

```bash
php artisan test --coverage
```

## Run Specific Test Method

```bash
php artisan test tests/Feature/AuthenticationTest.php --filter=test_user_can_login_with_valid_credentials
```

## Run Tests with Verbose Output

```bash
php artisan test --verbose
```

## Database Setup for Testing

```bash
php artisan migrate:fresh --seed
```

## Key Test Categories

### 1. Authentication (13 tests)
- Login/Register validation
- Role-based redirects
- Session management
- Remember-me functionality

### 2. Search Functionality (8 tests)
- Filter testing (destination, budget, duration, date)
- Multiple filters combination

### 3. Admin Operations (16 tests)
- Package CRUD operations
- Agency management
- Access control verification

### 4. User Account (10 tests)
- Profile management
- Password changes
- Feedback submission
- Access restrictions

### 5. RBAC (10 tests)
- Admin access verification
- User access restrictions
- Cross-role access prevention

### 6. Security (16 tests)
- SQL Injection prevention
- XSS protection
- CSRF token validation
- Input validation
- Sensitive data protection

## Expected Test Results

All 73 tests should PASS ✅

### Test Distribution
- Feature Tests: 73 ✅
- Unit Tests: 0 (can be added for business logic)

## Troubleshooting

### If tests fail with database errors:
```bash
php artisan migrate:fresh
php artisan test
```

### If factories are not found:
```bash
composer dump-autoload
php artisan test
```

### If permission errors occur:
```bash
php artisan cache:clear
php artisan config:clear
```

## Continuous Integration

For CI/CD pipelines (GitHub Actions, GitLab CI, etc.):

```yaml
- Run Tests:
  - php artisan test --coverage
  - Check coverage percentage
  - Fail if coverage < 80%
```

## Test Data

All tests use `RefreshDatabase` trait which:
- Runs migrations before each test
- Rolls back after each test
- Uses in-memory SQLite (fast execution)
- No side effects between tests

## Performance

Expected execution time:
- All tests: ~30-60 seconds
- Individual test file: ~5-10 seconds
- Single test method: ~1-2 seconds

---

**Ready to test your application! 🧪**
