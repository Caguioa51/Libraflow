<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeederController extends Controller
{
    /**
     * Display the seeder interface
     */
    public function index()
    {
        try {
            // Check if we can connect to database
            DB::connection()->getPdo();
            
            $users = $this->safeCount('users');
            $books = $this->safeCount('books');
            $authors = $this->safeCount('authors');
            $categories = $this->safeCount('categories');

            return view('admin.database-seeder', compact('users', 'books', 'authors', 'categories'));
            
        } catch (\Exception $e) {
            return view('admin.database-seeder', [
                'users' => 0, 
                'books' => 0, 
                'authors' => 0, 
                'categories' => 0,
                'error' => 'Database connection error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Run all seeders
     */
    public function runAllSeeders(Request $request)
    {
        try {
            // Check database connection first
            DB::connection()->getPdo();
            
            // Run AdminUserSeeder
            $this->runAdminUserSeeder();
            
            // Run RealBooksSeeder  
            $this->runRealBooksSeeder();
            
            // Run SystemSettingsSeeder
            $this->runSystemSettingsSeeder();

            return redirect()->route('database-seeder.index')->with('success', 'All seeders completed successfully!');
            
        } catch (\Exception $e) {
            return redirect()->route('database-seeder.index')->with('error', 'Error running seeders: ' . $e->getMessage());
        }
    }

    /**
     * Safe count for table existence check
     */
    private function safeCount($table)
    {
        try {
            if (!Schema::hasTable($table)) {
                return 0;
            }
            return DB::table($table)->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Run AdminUserSeeder
     */
    private function runAdminUserSeeder()
    {
        // Check if users table exists
        if (!Schema::hasTable('users')) {
            throw new \Exception('Users table does not exist. Please run migrations first.');
        }

        $adminEmail = trim(strtolower(env('ADMIN_EMAIL', 'admin@libraflow.com')));
        $adminName = env('ADMIN_NAME', 'Administrator');
        $adminPassword = env('ADMIN_PASSWORD', 'admin123');

        DB::table('users')->updateOrInsert(
            ['email' => $adminEmail],
            [
                'name' => $adminName,
                'role' => 'admin',
                'password' => Hash::make($adminPassword),
                'email_verified_at' => now(),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return "Admin user created: {$adminEmail} / {$adminPassword}";
    }

    /**
     * Run RealBooksSeeder
     */
    private function runRealBooksSeeder()
    {
        // Check if required tables exist
        $requiredTables = ['books', 'authors', 'categories'];
        foreach ($requiredTables as $table) {
            if (!Schema::hasTable($table)) {
                throw new \Exception("Table '{$table}' does not exist. Please run migrations first.");
            }
        }

        // Clear existing books and related data
        DB::table('books')->truncate();
        DB::table('authors')->truncate();
        DB::table('categories')->truncate();

        $books = [
            // Mathematics & Statistics (8 books)
            ['title' => 'Calculus and Analytic Geometry', 'author' => 'George B. Thomas Jr.', 'genre' => 'Mathematics', 'quantity' => 8],
            ['title' => 'Statistics and Probability', 'author' => 'Douglas C. Montgomery', 'genre' => 'Mathematics', 'quantity' => 6],
            ['title' => 'Discrete Mathematics and Its Applications', 'author' => 'Kenneth H. Rosen', 'genre' => 'Mathematics', 'quantity' => 5],
            ['title' => 'Linear Algebra and Its Applications', 'author' => 'David C. Lay', 'genre' => 'Mathematics', 'quantity' => 5],
            ['title' => 'College Algebra', 'author' => 'Michael Sullivan', 'genre' => 'Mathematics', 'quantity' => 7],
            ['title' => 'Trigonometry', 'author' => 'Margaret L. Lial', 'genre' => 'Mathematics', 'quantity' => 4],
            ['title' => 'Precalculus: Mathematics for Calculus', 'author' => 'James Stewart', 'genre' => 'Mathematics', 'quantity' => 6],
            ['title' => 'Statistics for Business and Economics', 'author' => 'Anderson, Sweeney, Williams', 'genre' => 'Mathematics', 'quantity' => 5],

            // Physics (5 books)
            ['title' => 'Physics for Scientists and Engineers', 'author' => 'Serway and Jewett', 'genre' => 'Physics', 'quantity' => 6],
            ['title' => 'University Physics', 'author' => 'Hugh D. Young', 'genre' => 'Physics', 'quantity' => 5],
            ['title' => 'Fundamentals of Physics', 'author' => 'David Halliday', 'genre' => 'Physics', 'quantity' => 5],
            ['title' => 'Modern Physics', 'author' => 'Tipler and Llewellyn', 'genre' => 'Physics', 'quantity' => 3],
            ['title' => 'Conceptual Physics', 'author' => 'Paul G. Hewitt', 'genre' => 'Physics', 'quantity' => 4],

            // Chemistry (4 books)
            ['title' => 'Chemistry: The Central Science', 'author' => 'Brown, LeMay, Bursten', 'genre' => 'Chemistry', 'quantity' => 6],
            ['title' => 'General Chemistry', 'author' => 'Petrucci, Herring, Madura', 'genre' => 'Chemistry', 'quantity' => 5],
            ['title' => 'Organic Chemistry', 'author' => 'John McMurry', 'genre' => 'Chemistry', 'quantity' => 4],
            ['title' => 'Analytical Chemistry', 'author' => 'Skoog, West, Holler', 'genre' => 'Chemistry', 'quantity' => 3],

            // Biology (5 books)
            ['title' => 'Campbell Biology', 'author' => 'Reece, Urry, Cain, Wasserman', 'genre' => 'Biology', 'quantity' => 7],
            ['title' => 'Human Anatomy and Physiology', 'author' => 'Gerard J. Tortora', 'genre' => 'Biology', 'quantity' => 6],
            ['title' => 'Molecular Biology of the Cell', 'author' => 'Bruce Alberts', 'genre' => 'Biology', 'quantity' => 3],
            ['title' => 'General Microbiology', 'author' => 'Tortora, Funke, Case', 'genre' => 'Biology', 'quantity' => 4],
            ['title' => 'Ecology: Concepts and Applications', 'author' => 'Manuel Molles', 'genre' => 'Biology', 'quantity' => 3],

            // Engineering (5 books)
            ['title' => 'Engineering Mechanics: Statics and Dynamics', 'author' => 'R.C. Hibbeler', 'genre' => 'Engineering', 'quantity' => 5],
            ['title' => 'Thermodynamics: An Engineering Approach', 'author' => 'Yunus A. Çengel', 'genre' => 'Engineering', 'quantity' => 4],
            ['title' => 'Electrical Engineering: Principles and Applications', 'author' => 'Allan R. Hambley', 'genre' => 'Engineering', 'quantity' => 3],
            ['title' => 'Materials Science and Engineering', 'author' => 'William D. Callister', 'genre' => 'Engineering', 'quantity' => 4],
            ['title' => 'Civil Engineering Materials', 'author' => 'Neville and Brooks', 'genre' => 'Engineering', 'quantity' => 3],

            // Computer Science & IT (5 books)
            ['title' => 'Introduction to Algorithms', 'author' => 'Cormen, Leiserson, Rivest, Stein', 'genre' => 'Computer Science', 'quantity' => 3],
            ['title' => 'Computer Networking: A Top-Down Approach', 'author' => 'Kurose and Ross', 'genre' => 'Computer Science', 'quantity' => 4],
            ['title' => 'Database System Concepts', 'author' => 'Silberschatz, Galvin, Gagne', 'genre' => 'Computer Science', 'quantity' => 4],
            ['title' => 'Software Engineering: A Practitioners Approach', 'author' => 'Roger S. Pressman', 'genre' => 'Computer Science', 'quantity' => 3],
            ['title' => 'Computer Organization and Design', 'author' => 'Patterson and Hennessy', 'genre' => 'Computer Science', 'quantity' => 3],

            // Business & Economics (6 books)
            ['title' => 'Principles of Economics', 'author' => 'N. Gregory Mankiw', 'genre' => 'Economics', 'quantity' => 6],
            ['title' => 'Marketing Management', 'author' => 'Philip Kotler', 'genre' => 'Business', 'quantity' => 5],
            ['title' => 'Financial Accounting', 'author' => 'Weygandt, Kimmel, Kieso', 'genre' => 'Business', 'quantity' => 5],
            ['title' => 'Organizational Behavior', 'author' => 'Stephen P. Robbins', 'genre' => 'Business', 'quantity' => 4],
            ['title' => 'Business Ethics', 'author' => 'Manuel G. Velasquez', 'genre' => 'Business', 'quantity' => 3],
            ['title' => 'Introduction to Financial Management', 'author' => 'Brigham and Ehrhardt', 'genre' => 'Business', 'quantity' => 4],

            // Psychology & Social Sciences (5 books)
            ['title' => 'Introduction to Psychology', 'author' => 'James W. Kalat', 'genre' => 'Psychology', 'quantity' => 5],
            ['title' => 'Social Psychology', 'author' => 'David G. Myers', 'genre' => 'Psychology', 'quantity' => 4],
            ['title' => 'Abnormal Psychology', 'author' => 'James N. Butcher', 'genre' => 'Psychology', 'quantity' => 3],
            ['title' => 'Developmental Psychology', 'author' => 'Robert S. Feldman', 'genre' => 'Psychology', 'quantity' => 4],
            ['title' => 'Introduction to Sociology', 'author' => 'Anthony Giddens', 'genre' => 'Sociology', 'quantity' => 5],

            // History & Political Science (6 books)
            ['title' => 'World History', 'author' => 'William J. Duiker', 'genre' => 'History', 'quantity' => 6],
            ['title' => 'Philippine History', 'author' => 'Renato Constantino', 'genre' => 'History', 'quantity' => 7],
            ['title' => 'Political Science', 'author' => 'Michael G. Roskin', 'genre' => 'Political Science', 'quantity' => 4],
            ['title' => 'Constitutional Law', 'author' => 'Ed C. Re', 'genre' => 'Political Science', 'quantity' => 3],
            ['title' => 'Research Methods', 'author' => 'Creswell and Creswell', 'genre' => 'Research', 'quantity' => 5],
            ['title' => 'Philosophy: An Introduction', 'author' => 'Louis P. Pojman', 'genre' => 'Philosophy', 'quantity' => 4],

            // Literature & Language (6 books)
            ['title' => 'World Literature', 'author' => 'M.H. Abrams', 'genre' => 'Literature', 'quantity' => 4],
            ['title' => 'Advanced English Grammar', 'author' => 'Raymond Murphy', 'genre' => 'Language', 'quantity' => 5],
            ['title' => 'Noli Me Tangere', 'author' => 'José Rizal', 'genre' => 'Filipino Literature', 'quantity' => 5],
            ['title' => 'El Filibusterismo', 'author' => 'José Rizal', 'genre' => 'Filipino Literature', 'quantity' => 5],
            ['title' => 'Introduction to Literary Theory', 'author' => 'Jonathan Culler', 'genre' => 'Literature', 'quantity' => 3],
            ['title' => 'Creative Writing', 'author' => 'Janet Burroway', 'genre' => 'Language', 'quantity' => 3],
        ];

        foreach ($books as $b) {
            // Create or find category
            $category = DB::table('categories')->updateOrCreate(
                ['name' => $b['genre']], 
                ['description' => 'Books in ' . $b['genre']]
            );
            $categoryId = $category['id'];

            // Create or find author
            $author = DB::table('authors')->updateOrCreate(
                ['name' => $b['author']], 
                ['bio' => 'Author of ' . $b['genre'] . ' textbooks']
            );
            $authorId = $author['id'];

            $quantity = $b['quantity'] ?? 3;
            $available = $quantity;

            // Create book
            DB::table('books')->insert([
                'title' => $b['title'],
                'author_id' => $authorId,
                'category_id' => $categoryId,
                'status' => 'available',
                'quantity' => $quantity,
                'available_quantity' => $available,
                'location' => 'Library - ' . $b['genre'] . ' Section',
                'description' => 'Standard college textbook for ' . $b['genre'] . ' courses',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return count($books) . " books created";
    }

    /**
     * Run SystemSettingsSeeder
     */
    private function runSystemSettingsSeeder()
    {
        // Check if system_settings table exists
        if (!Schema::hasTable('system_settings')) {
            throw new \Exception('System settings table does not exist. Please run migrations first.');
        }

        $settings = [
            ['key' => 'system_name', 'value' => 'LibraFlow Library Management System'],
            ['key' => 'borrowing_days', 'value' => '14'],
            ['key' => 'max_books_per_user', 'value' => '3'],
            ['key' => 'fine_per_day', 'value' => '5.00'],
            ['key' => 'grace_period_days', 'value' => '2'],
            ['key' => 'announcement_message', 'value' => 'Welcome to LibraFlow Library Management System!'],
            ['key' => 'library_hours', 'value' => '8:00 AM - 8:00 PM'],
            ['key' => 'library_contact', 'value' => 'libraflow@school.edu'],
        ];

        foreach ($settings as $setting) {
            DB::table('system_settings')->updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'type' => 'string', 'updated_at' => now()]
            );
        }

        return "System settings created";
    }

    /**
     * Run only AdminUserSeeder
     */
    public function runAdminSeeder(Request $request)
    {
        try {
            $result = $this->runAdminUserSeeder();
            return redirect()->route('database-seeder.index')->with('success', "Admin seeder completed: {$result}");
        } catch (\Exception $e) {
            return redirect()->route('database-seeder.index')->with('error', 'Error running admin seeder: ' . $e->getMessage());
        }
    }

    /**
     * Run only BooksSeeder
     */
    public function runBooksSeeder(Request $request)
    {
        try {
            $result = $this->runRealBooksSeeder();
            return redirect()->route('database-seeder.index')->with('success', "Books seeder completed: {$result}");
        } catch (\Exception $e) {
            return redirect()->route('database-seeder.index')->with('error', 'Error running books seeder: ' . $e->getMessage());
        }
    }

    /**
     * Run only SystemSettingsSeeder
     */
    public function runSettingsSeeder(Request $request)
    {
        try {
            $result = $this->runSystemSettingsSeeder();
            return redirect()->route('database-seeder.index')->with('success', "Settings seeder completed: {$result}");
        } catch (\Exception $e) {
            return redirect()->route('database-seeder.index')->with('error', 'Error running settings seeder: ' . $e->getMessage());
        }
    }
}
