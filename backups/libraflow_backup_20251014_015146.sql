-- LibraFlow Database Backup
-- Generated: 2025-10-14 01:51:46
-- Tables: authors, book_reservations, books, borrowings, cache, cache_locks, categories, failed_jobs, job_batches, jobs, migrations, password_reset_tokens, sessions, system_settings, users

SET FOREIGN_KEY_CHECKS = 0;

-- Table structure for authors
DROP TABLE IF EXISTS authors;
CREATE TABLE `authors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for authors
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("1", "Percy Wunsch", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("2", "Golden McKenzie", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("3", "Sasha Rutherford IV", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("4", "Ms. Keely Leuschke", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("5", "Hattie Kohler", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("6", "Henriette Considine", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("7", "Prof. Meredith Murray DDS", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("8", "Dr. Linda Erdman PhD", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("9", "Fritz Hyatt", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("10", "Prof. Perry Kozey III", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("11", "Vincent DuBuque", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("12", "Juwan Ebert", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("13", "Mr. Ryder Barrows V", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("14", "Dolores Yost", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("15", "Blake VonRueden", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("16", "Arlo Lubowitz", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("17", "Heber Jerde", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("18", "Garth Hilpert", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("19", "Prof. Name Dibbert", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("20", "Chasity Murray", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("21", "Wilfrid Hodkiewicz MD", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("22", "Darryl O\'Kon", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("23", "Rhea Rodriguez", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("24", "Mr. Dangelo Sauer", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("25", "Jaclyn Turner Sr.", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("26", "Prof. Maya Nader DVM", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("27", "Seth Towne", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("28", "Estel Carter", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("29", "Charles Sipes DVM", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("30", "Mrs. Susana Ward", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("31", "José Rizal", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("32", "Francisco Balagtas", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("33", "Anonymous", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("34", "William Shakespeare", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("35", "Jane Austen", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("36", "Charles Dickens", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("37", "Harper Lee", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("38", "J.D. Salinger", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("39", "William Golding", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("40", "George Orwell", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("41", "John Green", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("42", "Suzanne Collins", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("43", "Veronica Roth", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("44", "James Dashner", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("45", "R.J. Palacio", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("46", "Lois Lowry", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("47", "Various", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("48", "E.B. White", NULL, "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("49", "Antoine de Saint-Exupéry", NULL, "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("50", "Roald Dahl", NULL, "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("51", "Maurice Sendak", NULL, "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO authors (id, name, bio, created_at, updated_at) VALUES ("52", "Eric Carle", NULL, "2025-10-14 01:07:54", "2025-10-14 01:07:54");

-- Table structure for book_reservations
DROP TABLE IF EXISTS book_reservations;
CREATE TABLE `book_reservations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `book_id` bigint(20) unsigned NOT NULL,
  `reserved_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('active','fulfilled','cancelled') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `book_reservations_user_id_status_index` (`user_id`,`status`),
  KEY `book_reservations_book_id_status_index` (`book_id`,`status`),
  CONSTRAINT `book_reservations_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  CONSTRAINT `book_reservations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for books
DROP TABLE IF EXISTS books;
CREATE TABLE `books` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `author_id` bigint(20) unsigned NOT NULL,
  `genre` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'available',
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `available_quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `books_category_id_foreign` (`category_id`),
  KEY `books_author_id_foreign` (`author_id`),
  CONSTRAINT `books_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `books_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for books
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("1", "Noli Me Tangere", "21", "31", "Filipino Literature", "available", NULL, NULL, "5", "3", "2025-10-14 01:07:53", "2025-10-14 01:13:04");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("2", "El Filibusterismo", "21", "31", "Filipino Literature", "available", NULL, "hdhd", "5", "5", "2025-10-14 01:07:53", "2025-10-14 01:13:39");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("3", "Florante at Laura", "21", "32", "Filipino Literature", "available", NULL, NULL, "3", "1", "2025-10-14 01:07:53", "2025-10-14 01:43:34");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("4", "Ibong Adarna", "21", "33", "Filipino Literature", "available", NULL, NULL, "3", "3", "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("5", "Romeo and Juliet", "22", "34", "Classic Literature", "available", NULL, NULL, "4", "4", "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("6", "Macbeth", "22", "34", "Classic Literature", "available", NULL, NULL, "4", "4", "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("7", "The Merchant of Venice", "22", "34", "Classic Literature", "available", NULL, NULL, "3", "3", "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("8", "Pride and Prejudice", "22", "35", "Classic Literature", "available", NULL, NULL, "3", "3", "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("9", "Great Expectations", "22", "36", "Classic Literature", "available", NULL, NULL, "3", "3", "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("10", "To Kill a Mockingbird", "22", "37", "Classic Literature", "available", NULL, NULL, "4", "4", "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("11", "The Catcher in the Rye", "22", "38", "Classic Literature", "available", NULL, NULL, "3", "3", "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("12", "Lord of the Flies", "22", "39", "Classic Literature", "available", NULL, NULL, "3", "3", "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("13", "Animal Farm", "22", "40", "Classic Literature", "available", NULL, NULL, "4", "4", "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("14", "1984", "22", "40", "Classic Literature", "available", NULL, NULL, "3", "3", "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("15", "The Fault in Our Stars", "10", "41", "Young Adult", "available", NULL, NULL, "4", "4", "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("16", "The Hunger Games", "10", "42", "Young Adult", "available", NULL, NULL, "5", "5", "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("17", "Catching Fire", "10", "42", "Young Adult", "available", NULL, NULL, "4", "3", "2025-10-14 01:07:53", "2025-10-14 01:43:47");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("18", "Mockingjay", "10", "42", "Young Adult", "available", NULL, NULL, "4", "4", "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("19", "Divergent", "10", "43", "Young Adult", "available", NULL, NULL, "4", "4", "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("20", "Insurgent", "10", "43", "Young Adult", "available", NULL, NULL, "3", "3", "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("21", "Allegiant", "10", "43", "Young Adult", "available", NULL, NULL, "3", "3", "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("22", "The Maze Runner", "10", "44", "Young Adult", "available", NULL, NULL, "4", "4", "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("23", "Wonder", "10", "45", "Young Adult", "available", NULL, NULL, "4", "4", "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("24", "The Giver", "10", "46", "Young Adult", "available", NULL, NULL, "4", "4", "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("25", "Filipino-English Dictionary", "23", "47", "Reference", "available", NULL, NULL, "3", "2", "2025-10-14 01:07:54", "2025-10-14 01:21:52");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("26", "World History", "24", "47", "Educational", "available", NULL, NULL, "5", "5", "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("27", "Basic Mathematics", "24", "47", "Educational", "available", NULL, NULL, "6", "6", "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("28", "General Science", "24", "47", "Educational", "available", NULL, NULL, "5", "5", "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("29", "Filipino Grammar and Composition", "24", "47", "Educational", "available", NULL, NULL, "4", "4", "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("30", "Charlotte\'s Web", "25", "48", "Children\'s Literature", "available", NULL, NULL, "4", "4", "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("31", "The Little Prince", "25", "49", "Children\'s Literature", "available", NULL, NULL, "3", "3", "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("32", "Matilda", "25", "50", "Children\'s Literature", "available", NULL, NULL, "4", "4", "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("33", "Charlie and the Chocolate Factory", "25", "50", "Children\'s Literature", "available", NULL, NULL, "4", "4", "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("34", "Where the Wild Things Are", "25", "51", "Children\'s Literature", "available", NULL, NULL, "3", "3", "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("35", "The Very Hungry Caterpillar", "25", "52", "Children\'s Literature", "available", NULL, NULL, "5", "5", "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("36", "Philippine History for Students", "26", "47", "Philippine History", "available", NULL, NULL, "5", "5", "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("37", "The Philippine Revolution", "26", "47", "Philippine History", "available", NULL, NULL, "3", "3", "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("38", "Filipino Heroes Biography", "8", "47", "Biography", "available", NULL, NULL, "4", "4", "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("39", "Traditional Filipino Tales", "27", "47", "Filipino Folklore", "available", NULL, NULL, "3", "3", "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("40", "Introduction to Computer Science", "28", "47", "Technology", "available", NULL, NULL, "4", "4", "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("41", "Basic Physics", "29", "47", "Science", "available", NULL, NULL, "5", "5", "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("42", "Chemistry for Beginners", "29", "47", "Science", "available", NULL, NULL, "4", "4", "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("43", "Biology Fundamentals", "29", "47", "Science", "available", NULL, NULL, "5", "5", "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("44", "Filipino Poetry Collection", "18", "47", "Poetry", "available", NULL, NULL, "3", "3", "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("45", "World Poetry Anthology", "18", "47", "Poetry", "available", NULL, NULL, "3", "3", "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO books (id, title, category_id, author_id, genre, status, description, location, quantity, available_quantity, created_at, updated_at) VALUES ("46", "Short Stories Collection", "30", "47", "Short Stories", "available", NULL, NULL, "4", "4", "2025-10-14 01:07:54", "2025-10-14 01:07:54");

-- Table structure for borrowings
DROP TABLE IF EXISTS borrowings;
CREATE TABLE `borrowings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `book_id` bigint(20) unsigned NOT NULL,
  `borrowed_at` timestamp NULL DEFAULT NULL,
  `due_date` timestamp NULL DEFAULT NULL,
  `renewal_count` int(11) NOT NULL DEFAULT 0,
  `last_renewed_at` timestamp NULL DEFAULT NULL,
  `returned_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'borrowed',
  `fine_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `fine_paid` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `borrowings_user_id_foreign` (`user_id`),
  KEY `borrowings_book_id_foreign` (`book_id`),
  CONSTRAINT `borrowings_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  CONSTRAINT `borrowings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for borrowings
INSERT INTO borrowings (id, user_id, book_id, borrowed_at, due_date, renewal_count, last_renewed_at, returned_at, status, fine_amount, fine_paid, created_at, updated_at) VALUES ("1", "3", "1", "2025-10-14 01:12:18", "2025-10-28 01:12:18", "0", NULL, NULL, "borrowed", "0.00", "0", "2025-10-14 01:12:18", "2025-10-14 01:12:18");
INSERT INTO borrowings (id, user_id, book_id, borrowed_at, due_date, renewal_count, last_renewed_at, returned_at, status, fine_amount, fine_paid, created_at, updated_at) VALUES ("2", "3", "1", "2025-10-14 01:13:04", "2025-10-28 01:13:04", "0", NULL, NULL, "borrowed", "0.00", "0", "2025-10-14 01:13:04", "2025-10-14 01:13:04");
INSERT INTO borrowings (id, user_id, book_id, borrowed_at, due_date, renewal_count, last_renewed_at, returned_at, status, fine_amount, fine_paid, created_at, updated_at) VALUES ("3", "1", "25", "2025-10-14 01:21:52", "2025-10-28 01:21:52", "0", NULL, NULL, "borrowed", "0.00", "0", "2025-10-14 01:21:52", "2025-10-14 01:21:52");
INSERT INTO borrowings (id, user_id, book_id, borrowed_at, due_date, renewal_count, last_renewed_at, returned_at, status, fine_amount, fine_paid, created_at, updated_at) VALUES ("4", "3", "3", "2025-10-14 01:23:17", "2025-10-28 01:23:17", "0", NULL, NULL, "borrowed", "0.00", "0", "2025-10-14 01:23:17", "2025-10-14 01:23:17");
INSERT INTO borrowings (id, user_id, book_id, borrowed_at, due_date, renewal_count, last_renewed_at, returned_at, status, fine_amount, fine_paid, created_at, updated_at) VALUES ("5", "3", "3", "2025-10-14 01:43:34", "2025-10-21 01:43:34", "0", NULL, NULL, "borrowed", "0.00", "0", "2025-10-14 01:43:34", "2025-10-14 01:43:34");
INSERT INTO borrowings (id, user_id, book_id, borrowed_at, due_date, renewal_count, last_renewed_at, returned_at, status, fine_amount, fine_paid, created_at, updated_at) VALUES ("6", "3", "17", "2025-10-14 01:43:47", "2025-10-21 01:43:47", "0", NULL, NULL, "borrowed", "0.00", "0", "2025-10-14 01:43:47", "2025-10-14 01:43:47");

-- Table structure for cache
DROP TABLE IF EXISTS cache;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for cache_locks
DROP TABLE IF EXISTS cache_locks;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for categories
DROP TABLE IF EXISTS categories;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for categories
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("1", "Fantasy", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("2", "Science Fiction", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("3", "Mystery", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("4", "Thriller", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("5", "Romance", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("6", "Historical Fiction", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("7", "Non-Fiction", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("8", "Biography", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("9", "Self-Help", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("10", "Young Adult", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("11", "Children", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("12", "Horror", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("13", "Classic", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("14", "Adventure", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("15", "Dystopian", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("16", "Memoir", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("17", "Graphic Novel", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("18", "Poetry", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("19", "Crime", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("20", "Literary Fiction", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("21", "Filipino Literature", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("22", "Classic Literature", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("23", "Reference", NULL, "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("24", "Educational", NULL, "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("25", "Children\'s Literature", NULL, "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("26", "Philippine History", NULL, "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("27", "Filipino Folklore", NULL, "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("28", "Technology", NULL, "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("29", "Science", NULL, "2025-10-14 01:07:54", "2025-10-14 01:07:54");
INSERT INTO categories (id, name, description, created_at, updated_at) VALUES ("30", "Short Stories", NULL, "2025-10-14 01:07:54", "2025-10-14 01:07:54");

-- Table structure for failed_jobs
DROP TABLE IF EXISTS failed_jobs;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for job_batches
DROP TABLE IF EXISTS job_batches;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for jobs
DROP TABLE IF EXISTS jobs;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for migrations
DROP TABLE IF EXISTS migrations;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for migrations
INSERT INTO migrations (id, migration, batch) VALUES ("1", "0001_01_01_000000_create_users_table", "1");
INSERT INTO migrations (id, migration, batch) VALUES ("2", "0001_01_01_000001_create_cache_table", "1");
INSERT INTO migrations (id, migration, batch) VALUES ("3", "0001_01_01_000002_create_jobs_table", "1");
INSERT INTO migrations (id, migration, batch) VALUES ("4", "2025_07_02_021600_create_categories_table", "1");
INSERT INTO migrations (id, migration, batch) VALUES ("5", "2025_07_02_021610_create_authors_table", "1");
INSERT INTO migrations (id, migration, batch) VALUES ("6", "2025_07_02_021620_create_books_table", "1");
INSERT INTO migrations (id, migration, batch) VALUES ("7", "2025_07_02_021630_create_borrowings_table", "1");
INSERT INTO migrations (id, migration, batch) VALUES ("8", "2025_07_05_114151_add_student_id_to_users_table", "1");
INSERT INTO migrations (id, migration, batch) VALUES ("9", "2025_07_08_111600_add_qr_code_to_users_table", "1");
INSERT INTO migrations (id, migration, batch) VALUES ("10", "2025_09_10_191128_add_fine_system_to_borrowings_table", "1");
INSERT INTO migrations (id, migration, batch) VALUES ("11", "2025_09_10_191146_create_system_settings_table", "1");
INSERT INTO migrations (id, migration, batch) VALUES ("12", "2025_09_18_122119_add_location_and_quantity_to_books_table", "1");
INSERT INTO migrations (id, migration, batch) VALUES ("13", "2025_10_13_224945_create_book_reservations_table", "1");
INSERT INTO migrations (id, migration, batch) VALUES ("14", "2025_10_13_231846_add_rfid_to_users_table", "1");

-- Table structure for password_reset_tokens
DROP TABLE IF EXISTS password_reset_tokens;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for sessions
DROP TABLE IF EXISTS sessions;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for system_settings
DROP TABLE IF EXISTS system_settings;
CREATE TABLE `system_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'string',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `system_settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for system_settings
INSERT INTO system_settings (id, key, value, type, description, created_at, updated_at) VALUES ("1", "borrowing_duration_days", "7", "number", "Number of days a book can be borrowed", "2025-10-14 01:06:05", "2025-10-14 01:42:37");
INSERT INTO system_settings (id, key, value, type, description, created_at, updated_at) VALUES ("2", "max_renewals", "2", "number", "Maximum number of renewals allowed", "2025-10-14 01:06:05", "2025-10-14 01:06:05");
INSERT INTO system_settings (id, key, value, type, description, created_at, updated_at) VALUES ("3", "fine_per_day", "0", "number", "Fine amount per day for overdue books", "2025-10-14 01:06:05", "2025-10-14 01:43:08");
INSERT INTO system_settings (id, key, value, type, description, created_at, updated_at) VALUES ("4", "max_books_per_user", "5", "number", "Maximum number of books a user can borrow at once", "2025-10-14 01:06:05", "2025-10-14 01:43:08");
INSERT INTO system_settings (id, key, value, type, description, created_at, updated_at) VALUES ("5", "self_service_enabled", "true", "boolean", "Enable self-service checkout for students", "2025-10-14 01:06:05", "2025-10-14 01:06:05");
INSERT INTO system_settings (id, key, value, type, description, created_at, updated_at) VALUES ("6", "email_notifications_enabled", "true", "boolean", "Enable email notifications for overdue books", "2025-10-14 01:06:05", "2025-10-14 01:06:05");
INSERT INTO system_settings (id, key, value, type, description, created_at, updated_at) VALUES ("7", "grace_period_days", "3", "number", "Days after due date before fines start accruing", "2025-10-14 01:35:36", "2025-10-14 01:35:36");
INSERT INTO system_settings (id, key, value, type, description, created_at, updated_at) VALUES ("8", "max_overdue_days", "7", "number", "Maximum days a book can be overdue before special action is required", "2025-10-14 01:35:37", "2025-10-14 01:43:08");
INSERT INTO system_settings (id, key, value, type, description, created_at, updated_at) VALUES ("9", "weekend_due_dates", "move_to_monday", "string", "How to handle due dates that fall on weekends", "2025-10-14 01:35:37", "2025-10-14 01:35:37");
INSERT INTO system_settings (id, key, value, type, description, created_at, updated_at) VALUES ("10", "holiday_handling", "strict", "string", "How to handle due dates that fall on school holidays", "2025-10-14 01:35:37", "2025-10-14 01:43:08");
INSERT INTO system_settings (id, key, value, type, description, created_at, updated_at) VALUES ("12", "library_announcement", "java man on the loose", "string", "Library announcement", "2025-10-14 01:45:24", "2025-10-14 01:45:24");

-- Table structure for users
DROP TABLE IF EXISTS users;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'student',
  `profile_photo` varchar(255) DEFAULT NULL,
  `student_id` varchar(255) DEFAULT NULL,
  `rfid_card` varchar(255) DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_rfid_card_unique` (`rfid_card`),
  KEY `users_rfid_card_index` (`rfid_card`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for users
INSERT INTO users (id, name, email, email_verified_at, password, role, profile_photo, student_id, rfid_card, qr_code, remember_token, created_at, updated_at) VALUES ("1", "Test User", "test@example.com", "2025-10-14 01:07:53", "$2y$12$5/kl84frPRoKq1UV/DGvd.WsCH2ZeYd6fPD8BcmDvf3j4OlycmGii", "student", NULL, NULL, NULL, NULL, "SG1J3eLAmy", "2025-10-14 01:07:53", "2025-10-14 01:07:53");
INSERT INTO users (id, name, email, email_verified_at, password, role, profile_photo, student_id, rfid_card, qr_code, remember_token, created_at, updated_at) VALUES ("2", "Admin", "admin@gmail.com", NULL, "$2y$12$PvEn67e6z9epdGpC15WC6e680.qfPxIPg22/Wq99zZde98fnLts76", "admin", NULL, NULL, NULL, NULL, NULL, "2025-10-14 01:07:53", "2025-10-14 01:44:23");
INSERT INTO users (id, name, email, email_verified_at, password, role, profile_photo, student_id, rfid_card, qr_code, remember_token, created_at, updated_at) VALUES ("3", "JOHN VINCENT", "vincentedrosolancaalim@gmail.com", NULL, "$2y$12$hEiNc74b6l28RhbI.1KwKOoiDEBA2tjhrlrNWc.Yn3whwo/Byf8FK", "student", NULL, "1111", NULL, NULL, NULL, "2025-10-14 01:12:11", "2025-10-14 01:26:07");

SET FOREIGN_KEY_CHECKS = 1;
