-- Student Management System Database Setup
-- Use this SQL script to create the database and set up the table structure for the student practice.

-- Step 1: Create Database (Uncomment the line below if you want to create the database)
-- CREATE DATABASE student_db;
-- USE student_db;

-- Step 2: Create the students table
CREATE TABLE IF NOT EXISTS `students` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_id` VARCHAR(20) UNIQUE NOT NULL COMMENT 'Unique Student ID/Roll Number (e.g. STD202601)',
    `first_name` VARCHAR(50) NOT NULL,
    `last_name` VARCHAR(50) NOT NULL,
    `email` VARCHAR(100) UNIQUE NOT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `course` VARCHAR(100) NOT NULL,
    `gpa` DECIMAL(3, 2) DEFAULT NULL CHECK (`gpa` >= 0.00 AND `gpa` <= 4.00),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Step 3: Insert sample student data for testing
INSERT INTO `students` (`student_id`, `first_name`, `last_name`, `email`, `phone`, `course`, `gpa`) VALUES
('STD202601', 'Alex', 'Morgan', 'alex.morgan@univ.edu', '555-0199', 'Computer Science', 3.85),
('STD202602', 'Sarah', 'Chen', 'sarah.chen@univ.edu', '555-0182', 'Data Science', 3.92),
('STD202603', 'Liam', 'O\'Connor', 'liam.oconnor@univ.edu', '555-0174', 'Software Engineering', 3.45),
('STD202604', 'Aisha', 'Patel', 'aisha.patel@univ.edu', '555-0165', 'Information Technology', 3.78),
('STD202605', 'Carlos', 'Gomez', 'carlos.gomez@univ.edu', '555-0153', 'Computer Science', 3.12);
