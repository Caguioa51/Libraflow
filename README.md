# LibraFlow - Library Management System

A modern, web-based library management system built with Laravel for Dagupan City National High School. LibraFlow streamlines book borrowing, user management, and library operations with an intuitive interface and QR code integration.

## ?? Features

- **User Management**: Student, teacher, and admin roles with QR code generation
- **Book Management**: Complete CRUD operations for books, authors, and categories
- **Borrowing System**: Track book loans with due dates and fine calculations
- **Self-Checkout**: Students can borrow books via the self-checkout UI
- **Analytics Dashboard**: Library usage statistics and reports
- **Responsive Design**: Bootstrap 5 interface that works on all devices
- **Admin Panel**: Comprehensive management tools for librarians
- **RFID Integration**: RFID card scanning for user identification
- **Real-time Search**: Fast student search with autocomplete functionality

## ?? Requirements

- **PHP**: 8.2 or higher
- **Composer**: Latest version
- **Node.js**: 18+ and npm
- **Database**: MySQL/PostgreSQL or SQLite
- **Web Server**: Apache/Nginx or PHP built-in server
- **Git**: For version control

## ?? Installation & Setup

### Prerequisites
- Ensure all requirements are installed
- Create a database for the application
- Set up proper file permissions

### Local Development Setup

1. **Clone the repository**
   ```bash
   cd libraflow
   ```

2. **Install PHP dependencies**
   ```bash
   composer install --no-dev
   # For development with dev dependencies:
   # composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database Configuration**
   Edit `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=libraflow
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

7. **Run Database Migrations**
   ```bash
   php artisan migrate --seed
   ```

8. **Build Frontend Assets**
   ```bash
   npm run build
   # For development with hot reload:
   # npm run dev
   ```

9. **Start Development Server**
   ```bash
   php artisan serve
   ```

   Access the application at `http://localhost:8000`

### Default Admin Account
- **Email**: admin@gmail.com
- **Password**: 11111111

## ?? Project Structure

```
libraflow/
+-- app/                          # Application core
¦   +-- Console/Commands/         # Artisan commands
¦   +-- Http/Controllers/         # HTTP controllers
¦   ¦   +-- Admin/               # Admin-specific controllers
¦   ¦   +-- Auth/                # Authentication controllers
¦   ¦   +-- ...
¦   +-- Models/                   # Eloquent models
¦   +-- Notifications/            # Email notifications
¦   +-- Services/                 # Business logic services
+-- database/                     # Database related files
¦   +-- factories/               # Model factories for testing
¦   +-- migrations/              # Database migrations
¦   +-- seeders/                 # Database seeders
+-- resources/                    # Frontend resources
¦   +-- css/                     # Stylesheets
¦   +-- js/                      # JavaScript files
¦   +-- views/                   # Blade templates
+-- routes/                      # Route definitions
+-- storage/                     # File storage
+-- tests/                       # Test files
+-- docker/                      # Docker configuration
```

## ?? Development Best Practices

### Laravel Coding Standards

#### PSR Standards Compliance
- Follow PSR-12 for PHP code style
- Use PSR-4 for autoloading
- Implement PSR-7 for HTTP message interfaces

#### Controller Best Practices
```php
// ? Good: Single Responsibility Principle
class BookController extends Controller
{
    public function index() { /* list books */ }
    public function store(StoreBookRequest $request) { /* create book */ }
    public function update(UpdateBookRequest $request, Book $book) { /* update book */ }
}

// ? Avoid: Fat controllers
class BookController extends Controller
{
    public function index()
    {
        // Don't put business logic here
        $books = Book::all(); // Move to repository/service
    }
}
```

#### Model Best Practices
```php
// ? Good: Proper model relationships
class Book extends Model
{
    protected $fillable = ['title', 'isbn', 'published_date'];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
}
```

#### Request Validation
```php
// ? Good: Use Form Request classes
class StoreBookRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books',
            'author_id' => 'required|exists:authors,id',
        ];
    }
}
```

### Git Workflow Best Practices

#### Branch Naming Convention
```
feature/     # New features
bugfix/      # Bug fixes
hotfix/      # Critical fixes
release/     # Release branches
```

#### Commit Message Standards
```bash
# ? Good commit messages
feat: add RFID card scanning functionality
fix: resolve search autocomplete bug
docs: update README with best practices
test: add unit tests for Book model
refactor: optimize database queries
```

#### Git Workflow
```bash
# 1. Create feature branch
git checkout -b feature/new-functionality

# 2. Make changes and commit
git add .
git commit -m "feat: add new functionality"

# 3. Push and create pull request
git push origin feature/new-functionality

# 4. After merge, clean up
git checkout main
git pull origin main
git branch -d feature/new-functionality
```

### Testing Standards

#### PHPUnit Best Practices
```php
// ? Good: Feature tests
use Tests\TestCase;
use App\Models\User;

class BookManagementTest extends TestCase
{
    public function test_user_can_borrow_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['status' => 'available']);

        $response = $this->actingAs($user)
                        ->post(route('borrowings.store'), [
                            'book_id' => $book->id
                        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('borrowings', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);
    }
}
```

### Security Best Practices

#### Authentication & Authorization
- Use Laravel Sanctum/Breeze for API authentication
- Implement proper middleware for route protection
- Validate all user inputs
- Use CSRF protection for forms

#### Database Security
- Use prepared statements (Eloquent handles this)
- Avoid SQL injection vulnerabilities
- Implement proper access controls
- Regular security audits

### Performance Optimization

#### Database Optimization
```php
// ? Good: Eager loading
$books = Book::with(['author', 'category'])->get();

// ? Good: Query optimization
$availableBooks = Book::where('status', 'available')
                     ->where('available_quantity', '>', 0)
                     ->get();
```

#### Caching Strategies
```php
// Use Laravel's caching mechanisms
Cache::remember('books.count', 3600, function () {
    return Book::count();
});
```

## ?? Additional Resources

### Laravel Documentation
- [Laravel Official Docs](https://laravel.com/docs)
- [Laravel Security](https://laravel.com/docs/security)

### Development Tools
- **PHPStan**: Static analysis
- **Laravel Pint**: Code style fixer
- **Laravel IDE Helper**: Better IDE support
- **Clockwork**: Development toolbar

### Useful Commands
```bash
# Code quality
php artisan insights           # PHP Insights analysis
./vendor/bin/phpstan analyse  # Static analysis

# Testing
php artisan test             # Run tests
php artisan test --coverage  # With coverage report

# Development
php artisan serve           # Start development server
npm run dev                 # Start Vite dev server
php artisan migrate:fresh   # Fresh migration with seed

```

## ?? Contributing Guidelines

### Development Process
1. **Issue Creation**: Create detailed issues with acceptance criteria
2. **Branch Creation**: Use descriptive branch names
3. **Code Development**: Follow coding standards
4. **Testing**: Write tests for new functionality
5. **Code Review**: Submit pull requests for review
6. **Merge**: Merge approved changes

### Code Review Checklist
- [ ] Code follows PSR-12 standards
- [ ] Tests are included and passing
- [ ] Documentation is updated
- [ ] Security considerations addressed
- [ ] Performance impact assessed
- [ ] Backward compatibility maintained

## ?? Support & Contact

For support and questions:
- **Email**: libraflow8@gmail.com

## ?? License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

<div align="center">

**LibraFlow** - Streamlining library management for modern education.

*Built with ?? using Laravel | ?? Modern Library Management | ?? Secure & Reliable*

</div>
