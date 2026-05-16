-- Refined Database Schema for Ethiopian School Management System
-- Differentiated tables for Students, Teachers, Parents, and Staff (Admins/Directors)

SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS school_system;
USE school_system;

-- 1. SaaS Plans
CREATE TABLE IF NOT EXISTS plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    max_students INT NOT NULL,
    max_teachers INT NOT NULL,
    max_schools INT NOT NULL DEFAULT 1,
    features TEXT
);

-- 2. Schools
CREATE TABLE IF NOT EXISTS schools (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    school_code VARCHAR(10) UNIQUE NOT NULL,
    subdomain VARCHAR(100) UNIQUE,
    plan_id INT,
    director_id INT,
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (plan_id) REFERENCES plans(id) ON DELETE SET NULL,
    FOREIGN KEY (director_id) REFERENCES staff_users(id) ON DELETE CASCADE
);

-- 2. Staff (Admins and Directors)
CREATE TABLE IF NOT EXISTS staff_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'director') NOT NULL,
    school_id INT, -- NULL for platform admins
    plan_id INT, -- Assigned plan from registration
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    status ENUM('active', 'inactive') DEFAULT 'active',
    must_change_password BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (school_id) REFERENCES schools(id) ON DELETE CASCADE,
    FOREIGN KEY (plan_id) REFERENCES plans(id) ON DELETE SET NULL
);

-- 3. Teachers
CREATE TABLE IF NOT EXISTS teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_id_code VARCHAR(20) UNIQUE, -- Generated: {CODE}T{NNNN}/{YEAR}
    school_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    specialization VARCHAR(100),
    status ENUM('active', 'inactive') DEFAULT 'active',
    must_change_password BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (school_id) REFERENCES schools(id) ON DELETE CASCADE
);

-- 4. Students
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) UNIQUE NOT NULL, -- Generated: {CODE}{NNNN}/{YEAR}
    school_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    enrollment_year INT NOT NULL,
    section_id INT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    must_change_password BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (school_id) REFERENCES schools(id) ON DELETE CASCADE
);

-- 5. Parents
CREATE TABLE IF NOT EXISTS parents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 6. Parent-Student Relation
CREATE TABLE IF NOT EXISTS parent_student (
    parent_id INT NOT NULL,
    student_id INT NOT NULL,
    relation_type VARCHAR(50), -- e.g., 'Father', 'Mother', 'Guardian'
    PRIMARY KEY (parent_id, student_id),
    FOREIGN KEY (parent_id) REFERENCES parents(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- 7. Academic Structure
CREATE TABLE IF NOT EXISTS academic_years (
    id INT AUTO_INCREMENT PRIMARY KEY,
    school_id INT NOT NULL,
    name VARCHAR(20) NOT NULL, -- e.g., "2016 E.C"
    is_active BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (school_id) REFERENCES schools(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS terms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    academic_year_id INT NOT NULL,
    name VARCHAR(50) NOT NULL, -- e.g., "Semester 1"
    is_active BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS grades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    school_id INT NOT NULL,
    grade_level INT NOT NULL, -- 9, 10, 11, 12
    stream ENUM('general', 'natural_science', 'social_science') DEFAULT 'general',
    FOREIGN KEY (school_id) REFERENCES schools(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS sections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    grade_id INT NOT NULL,
    name VARCHAR(10) NOT NULL, -- e.g., "A", "B"
    FOREIGN KEY (grade_id) REFERENCES grades(id) ON DELETE CASCADE
);

-- Add Section FK to Students
ALTER TABLE students ADD CONSTRAINT fk_student_section FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE SET NULL;

CREATE TABLE IF NOT EXISTS subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    school_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    grade_level INT NOT NULL,
    FOREIGN KEY (school_id) REFERENCES schools(id) ON DELETE CASCADE
);

-- 8. Assignments & Grading
CREATE TABLE IF NOT EXISTS teaching_assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_id INT NOT NULL,
    subject_id INT NOT NULL,
    section_id INT NOT NULL,
    academic_year_id INT NOT NULL,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id),
    FOREIGN KEY (section_id) REFERENCES sections(id),
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(id)
);

-- Assessment Types with Weightage (e.g., Quiz = 0.1, Mid = 0.3, Final = 0.4)
CREATE TABLE IF NOT EXISTS assessment_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    school_id INT NOT NULL,
    name VARCHAR(50) NOT NULL,
    weight DECIMAL(3,2) NOT NULL, -- 0.10, 0.40, etc.
    FOREIGN KEY (school_id) REFERENCES schools(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS assessments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    teaching_assignment_id INT NOT NULL,
    term_id INT NOT NULL,
    assessment_type_id INT NOT NULL,
    title VARCHAR(100),
    max_score DECIMAL(5,2) DEFAULT 100.00,
    assessment_date DATE,
    FOREIGN KEY (teaching_assignment_id) REFERENCES teaching_assignments(id),
    FOREIGN KEY (term_id) REFERENCES terms(id),
    FOREIGN KEY (assessment_type_id) REFERENCES assessment_types(id)
);

CREATE TABLE IF NOT EXISTS grades_entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    assessment_id INT NOT NULL,
    student_id INT NOT NULL,
    score DECIMAL(5,2),
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (assessment_id) REFERENCES assessments(id),
    FOREIGN KEY (student_id) REFERENCES students(id)
);

-- 9. Sequences for ID Generation
CREATE TABLE IF NOT EXISTS school_sequences (
    school_id INT PRIMARY KEY,
    next_student_no INT DEFAULT 1,
    next_teacher_no INT DEFAULT 1,
    FOREIGN KEY (school_id) REFERENCES schools(id) ON DELETE CASCADE
);

-- 10. School Site Customization
CREATE TABLE IF NOT EXISTS school_site_content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    school_id INT NOT NULL,
    template_name VARCHAR(50) DEFAULT 'vibrant',
    hero_title VARCHAR(255),
    hero_subtitle TEXT,
    primary_color VARCHAR(20) DEFAULT '#000000',
    logo_url VARCHAR(255),
    about_text TEXT,
    custom_pages JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (school_id) REFERENCES schools(id) ON DELETE CASCADE
);

INSERT INTO `plans` (`id`, `name`, `price`, `max_students`, `max_teachers`, `features`, `max_schools`) VALUES
(1, 'Free', 0.00, 200, 20, 'Basic Features', 1),
(2, 'Starter', 200.00, 500, 200, 'Advanced Features', 2),
(3, 'Growth', 399.00, 2000, 500, 'Priority Support', 5),
(4, 'Scale', 1000.00, 50000, 1000, 'Enterprise Features', 5);

SET FOREIGN_KEY_CHECKS = 1;

