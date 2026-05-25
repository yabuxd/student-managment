<?php

require_once __DIR__ . '/Database.php';

use App\Config\Database;

try {
    $dbClass = new Database();
    $db = $dbClass->getConnection();
    if (!$db) {
        throw new Exception("Unable to connect to the database.");
    }

    echo "Database connected successfully.\n";

    // Disable foreign key checks for migration
    $db->exec("SET FOREIGN_KEY_CHECKS = 0;");

    // 1. Alter schools table
    echo "Checking 'schools' table for 'is_final_assessment_active'...\n";
    $stmt = $db->query("SHOW COLUMNS FROM schools LIKE 'is_final_assessment_active'");
    if ($stmt->rowCount() === 0) {
        $db->exec("ALTER TABLE schools ADD COLUMN is_final_assessment_active TINYINT(1) DEFAULT 0;");
        echo "Column 'is_final_assessment_active' added to 'schools'.\n";
    } else {
        echo "Column 'is_final_assessment_active' already exists.\n";
    }

    // 2. Alter sections table
    echo "Checking 'sections' table for 'homeroom_teacher_id'...\n";
    $stmt = $db->query("SHOW COLUMNS FROM sections LIKE 'homeroom_teacher_id'");
    if ($stmt->rowCount() === 0) {
        $db->exec("ALTER TABLE sections ADD COLUMN homeroom_teacher_id INT NULL;");
        echo "Column 'homeroom_teacher_id' added to 'sections'.\n";
    } else {
        echo "Column 'homeroom_teacher_id' already exists.\n";
    }

    // Add Foreign key constraint to sections
    echo "Adding foreign key constraint for 'homeroom_teacher_id' in 'sections'...\n";
    try {
        $db->exec("ALTER TABLE sections ADD CONSTRAINT fk_homeroom_teacher FOREIGN KEY (homeroom_teacher_id) REFERENCES teachers(id) ON DELETE SET NULL;");
        echo "Foreign key constraint 'fk_homeroom_teacher' added.\n";
    } catch (\PDOException $e) {
        echo "Foreign key note/warning: " . $e->getMessage() . "\n";
    }

    // 3. Create communications table
    echo "Creating 'communications' table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS communications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            school_id INT NOT NULL,
            sender_role ENUM('teacher', 'parent', 'director') NOT NULL,
            sender_id INT NOT NULL,
            receiver_role ENUM('teacher', 'parent', 'director') NOT NULL,
            receiver_id INT NOT NULL,
            message TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (school_id) REFERENCES schools(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "Table 'communications' verified.\n";

    // 4. Create student_final_evaluations table
    echo "Creating 'student_final_evaluations' table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS student_final_evaluations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            student_id INT NOT NULL,
            academic_year_id INT NOT NULL,
            average_score DECIMAL(5,2) NULL,
            class_rank INT NULL,
            status ENUM('pass', 'fail', 'pending') DEFAULT 'pending',
            evaluated_by INT NULL,
            evaluated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_student_year (student_id, academic_year_id),
            FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
            FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE,
            FOREIGN KEY (evaluated_by) REFERENCES teachers(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "Table 'student_final_evaluations' verified.\n";

    // Enable foreign key checks back
    $db->exec("SET FOREIGN_KEY_CHECKS = 1;");

    // 5. Seeding Demo Data for a school subdomain named 'vibrant'
    echo "Seeding default data...\n";

    // Seed plan if empty
    $planStmt = $db->query("SELECT COUNT(*) FROM plans");
    if ($planStmt->fetchColumn() == 0) {
        $db->exec("
            INSERT INTO plans (id, name, price, max_students, max_teachers, max_schools, features) VALUES
            (1, 'Free', 0.00, 200, 20, 1, 'Basic Features'),
            (2, 'Starter', 200.00, 500, 200, 2, 'Advanced Features'),
            (3, 'Growth', 399.00, 2000, 500, 5, 'Priority Support'),
            (4, 'Scale', 1000.00, 50000, 1000, 5, 'Enterprise Features')
        ");
        echo "Plans seeded.\n";
    }

    // Check or create director
    $directorHash = password_hash('password123', PASSWORD_DEFAULT);
    $stmt = $db->prepare("SELECT id FROM staff_users WHERE username = ?");
    $stmt->execute(['director']);
    $directorId = $stmt->fetchColumn();
    if (!$directorId) {
        $db->prepare("
            INSERT INTO staff_users (username, password_hash, role, full_name, email, plan_id, must_change_password)
            VALUES (?, ?, 'director', 'Demo Director', 'director@school.com', 4, 0)
        ")->execute(['director', $directorHash]);
        $directorId = $db->lastInsertId();
        echo "Director seeded (username: director, password: password123).\n";
    }

    // Check or create school
    $stmt = $db->prepare("SELECT id FROM schools WHERE subdomain = ?");
    $stmt->execute(['vibrant']);
    $schoolId = $stmt->fetchColumn();
    if (!$schoolId) {
        $db->prepare("
            INSERT INTO schools (name, school_code, subdomain, plan_id, director_id, address)
            VALUES (?, ?, ?, 4, ?, ?)
        ")->execute(['Vibrant Academy', 'VIB123', 'vibrant', $directorId, 'Addis Ababa, Ethiopia']);
        $schoolId = $db->lastInsertId();
        
        // Link director to school
        $db->prepare("UPDATE staff_users SET school_id = ? WHERE id = ?")->execute([$schoolId, $directorId]);
        
        // Insert site content
        $db->prepare("
            INSERT INTO school_site_content (school_id, template_name, theme_path, typography, hero_title, hero_subtitle, primary_color)
            VALUES (?, 'vibrant', 'assets/css/themes/theme1.css', 'Inter', 'Vibrant Academy', 'The peak of education', '#3b82f6')
        ")->execute([$schoolId]);
        echo "School 'vibrant' and site content seeded.\n";
    }

    // Add sequences row if missing
    $stmt = $db->prepare("SELECT school_id FROM school_sequences WHERE school_id = ?");
    $stmt->execute([$schoolId]);
    if (!$stmt->fetchColumn()) {
        $db->prepare("INSERT INTO school_sequences (school_id, next_student_no, next_teacher_no) VALUES (?, 10, 5)")->execute([$schoolId]);
    }

    // Seed teacher
    $teacherHash = password_hash('password123', PASSWORD_DEFAULT);
    $stmt = $db->prepare("SELECT id FROM teachers WHERE email = ?");
    $stmt->execute(['teacher@school.com']);
    $teacherId = $stmt->fetchColumn();
    if (!$teacherId) {
        $db->prepare("
            INSERT INTO teachers (teacher_id_code, school_id, full_name, email, password_hash, specialization, status, must_change_password)
            VALUES (?, ?, ?, ?, ?, ?, ?, 0)
        ")->execute(['VIBT0001/2026', $schoolId, 'Abebe Kebede', 'teacher@school.com', $teacherHash, 'Mathematics', 'active']);
        $teacherId = $db->lastInsertId();
        echo "Teacher seeded (email: teacher@school.com, password: password123).\n";
    }

    // Seed active academic year
    $stmt = $db->prepare("SELECT id FROM academic_years WHERE school_id = ? AND name = ?");
    $stmt->execute([$schoolId, '2016 E.C']);
    $yearId = $stmt->fetchColumn();
    if (!$yearId) {
        $db->prepare("INSERT INTO academic_years (school_id, name, is_active) VALUES (?, ?, 1)")->execute([$schoolId, '2016 E.C']);
        $yearId = $db->lastInsertId();
        echo "Academic year '2016 E.C' seeded.\n";
    }

    // Seed active term
    $stmt = $db->prepare("SELECT id FROM terms WHERE academic_year_id = ? AND name = ?");
    $stmt->execute([$yearId, 'Semester 1']);
    $termId = $stmt->fetchColumn();
    if (!$termId) {
        $db->prepare("INSERT INTO terms (academic_year_id, name, is_active) VALUES (?, ?, 1)")->execute([$yearId, 'Semester 1']);
        $termId = $db->lastInsertId();
        echo "Term 'Semester 1' seeded.\n";
    }

    // Seed grade
    $stmt = $db->prepare("SELECT id FROM grades WHERE school_id = ? AND grade_level = ?");
    $stmt->execute([$schoolId, 10]);
    $gradeId = $stmt->fetchColumn();
    if (!$gradeId) {
        $db->prepare("INSERT INTO grades (school_id, grade_level, stream) VALUES (?, 10, 'general')")->execute([$schoolId]);
        $gradeId = $db->lastInsertId();
        echo "Grade 10 seeded.\n";
    }

    // Seed sections
    $stmt = $db->prepare("SELECT id FROM sections WHERE grade_id = ? AND name = ?");
    $stmt->execute([$gradeId, 'A']);
    $sectionAId = $stmt->fetchColumn();
    if (!$sectionAId) {
        $db->prepare("INSERT INTO sections (grade_id, name, homeroom_teacher_id) VALUES (?, ?, ?)")->execute([$gradeId, 'A', $teacherId]);
        $sectionAId = $db->lastInsertId();
        echo "Section A seeded with homeroom teacher Abebe.\n";
    } else {
        // Just make sure homeroom_teacher_id is linked
        $db->prepare("UPDATE sections SET homeroom_teacher_id = ? WHERE id = ?")->execute([$teacherId, $sectionAId]);
    }

    $stmt->execute([$gradeId, 'B']);
    $sectionBId = $stmt->fetchColumn();
    if (!$sectionBId) {
        $db->prepare("INSERT INTO sections (grade_id, name, homeroom_teacher_id) VALUES (?, ?, NULL)")->execute([$gradeId, 'B']);
        $sectionBId = $db->lastInsertId();
        echo "Section B seeded.\n";
    }

    // Seed student
    $studentHash = password_hash('password123', PASSWORD_DEFAULT);
    $stmt = $db->prepare("SELECT id FROM students WHERE email = ?");
    $stmt->execute(['student@school.com']);
    $studentId = $stmt->fetchColumn();
    if (!$studentId) {
        $db->prepare("
            INSERT INTO students (student_id, school_id, full_name, email, password_hash, enrollment_year, section_id, status, must_change_password)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'active', 0)
        ")->execute(['VIBS0001/2026', $schoolId, 'Betty Abebe', 'student@school.com', $studentHash, 2026, $sectionAId]);
        $studentId = $db->lastInsertId();
        echo "Student seeded (email: student@school.com, password: password123, ID: VIBS0001/2026).\n";
    }

    // Seed parent
    $parentHash = password_hash('password123', PASSWORD_DEFAULT);
    $stmt = $db->prepare("SELECT id FROM parents WHERE email = ?");
    $stmt->execute(['parent@school.com']);
    $parentId = $stmt->fetchColumn();
    if (!$parentId) {
        $db->prepare("
            INSERT INTO parents (full_name, email, password_hash, phone)
            VALUES (?, ?, ?, ?)
        ")->execute(['Kebede Abebe (Parent)', 'parent@school.com', $parentHash, '+251911223344']);
        $parentId = $db->lastInsertId();
        
        // Link parent to student
        $db->prepare("INSERT INTO parent_student (parent_id, student_id, relation_type) VALUES (?, ?, ?)")->execute([$parentId, $studentId, 'Father']);
        echo "Parent seeded (email: parent@school.com, password: password123).\n";
    }

    // Seed subject
    $stmt = $db->prepare("SELECT id FROM subjects WHERE school_id = ? AND name = ?");
    $stmt->execute([$schoolId, 'Mathematics']);
    $subjectId = $stmt->fetchColumn();
    if (!$subjectId) {
        $db->prepare("INSERT INTO subjects (school_id, name, grade_level) VALUES (?, ?, 10)")->execute([$schoolId, 'Mathematics']);
        $subjectId = $db->lastInsertId();
        echo "Subject 'Mathematics' seeded.\n";
    }

    // Seed teaching assignment
    $stmt = $db->prepare("SELECT id FROM teaching_assignments WHERE teacher_id = ? AND subject_id = ? AND section_id = ?");
    $stmt->execute([$teacherId, $subjectId, $sectionAId]);
    $taId = $stmt->fetchColumn();
    if (!$taId) {
        $db->prepare("
            INSERT INTO teaching_assignments (teacher_id, subject_id, section_id, academic_year_id)
            VALUES (?, ?, ?, ?)
        ")->execute([$teacherId, $subjectId, $sectionAId, $yearId]);
        $taId = $db->lastInsertId();
        echo "Teaching Assignment seeded.\n";
    }

    // Seed assessment types
    $stmt = $db->prepare("SELECT id FROM assessment_types WHERE school_id = ? AND name = ?");
    $stmt->execute([$schoolId, 'Continuous Assessment']);
    $typeCAId = $stmt->fetchColumn();
    if (!$typeCAId) {
        $db->prepare("INSERT INTO assessment_types (school_id, name, weight) VALUES (?, ?, ?)")->execute([$schoolId, 'Continuous Assessment', 0.50]);
        $typeCAId = $db->lastInsertId();
    }
    
    $stmt->execute([$schoolId, 'Final Exam']);
    $typeFinalId = $stmt->fetchColumn();
    if (!$typeFinalId) {
        $db->prepare("INSERT INTO assessment_types (school_id, name, weight) VALUES (?, ?, ?)")->execute([$schoolId, 'Final Exam', 0.50]);
        $typeFinalId = $db->lastInsertId();
    }

    // Seed assessments
    $stmt = $db->prepare("SELECT id FROM assessments WHERE teaching_assignment_id = ? AND assessment_type_id = ?");
    $stmt->execute([$taId, $typeCAId]);
    $assCAId = $stmt->fetchColumn();
    if (!$assCAId) {
        $db->prepare("
            INSERT INTO assessments (teaching_assignment_id, term_id, assessment_type_id, title, max_score, assessment_date)
            VALUES (?, ?, ?, ?, 50.00, ?)
        ")->execute([$taId, $termId, $typeCAId, 'Midterm Quiz', date('Y-m-d')]);
        $assCAId = $db->lastInsertId();
    }

    $stmt->execute([$taId, $typeFinalId]);
    $assFinalId = $stmt->fetchColumn();
    if (!$assFinalId) {
        $db->prepare("
            INSERT INTO assessments (teaching_assignment_id, term_id, assessment_type_id, title, max_score, assessment_date)
            VALUES (?, ?, ?, ?, 50.00, ?)
        ")->execute([$taId, $termId, $typeFinalId, 'Final Exam', date('Y-m-d')]);
        $assFinalId = $db->lastInsertId();
    }

    // Seed student scores
    $stmt = $db->prepare("SELECT id FROM grades_entries WHERE assessment_id = ? AND student_id = ?");
    $stmt->execute([$assCAId, $studentId]);
    if (!$stmt->fetchColumn()) {
        $db->prepare("INSERT INTO grades_entries (assessment_id, student_id, score) VALUES (?, ?, 42.50)")->execute([$assCAId, $studentId]);
    }
    
    $stmt->execute([$assFinalId, $studentId]);
    if (!$stmt->fetchColumn()) {
        $db->prepare("INSERT INTO grades_entries (assessment_id, student_id, score) VALUES (?, ?, 45.00)")->execute([$assFinalId, $studentId]);
    }
    echo "Seed grades entries complete.\n";

    echo "Migration completed successfully!\n";

} catch (Exception $e) {
    echo "Error during migration: " . $e->getMessage() . "\n";
    exit(1);
}
