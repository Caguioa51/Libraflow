<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use App\Models\Author;
use App\Models\Category;

class RealBooksSeeder extends Seeder
{
	public function run(): void
	{
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

		// Handle foreign key constraints properly
		DB::statement('SET FOREIGN_KEY_CHECKS=0');
		Book::truncate();
		DB::statement('SET FOREIGN_KEY_CHECKS=1');

		$categoryCount = 0;
		$authorCount = 0;
		$bookCount = 0;

		foreach ($books as $b) {
			$category = Category::firstOrCreate(['name' => $b['genre']], ['description' => 'Books in ' . $b['genre']]);
			$author = Author::firstOrCreate(['name' => $b['author']], ['bio' => 'Author of ' . $b['genre'] . ' textbooks']);

			$quantity = $b['quantity'] ?? 3;
			$available = $quantity;

			Book::create([
				'title' => $b['title'],
				'author_id' => $author->id,
				'category_id' => $category->id,
				'status' => 'available',
				'quantity' => $quantity,
				'available_quantity' => $available,
				'location' => 'Library - ' . $b['genre'] . ' Section',
				'description' => 'Standard college textbook for ' . $b['genre'] . ' courses',
			]);

			$bookCount++;
			$categoryCount++;
			$authorCount++;
		}

		// Log the results
		$this->command->info("✅ Created {$bookCount} college textbooks");
		$this->command->info("✅ Created {$categoryCount} categories");
		$this->command->info("✅ Created {$authorCount} authors");
		$this->command->info("📚 College library ready with realistic textbooks!");
	}
}
