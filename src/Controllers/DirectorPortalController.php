<?php

namespace App\Controllers;

use PDO;

class DirectorPortalController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    private function getActiveYearId($schoolId) {
        $stmt = $this->db->prepare("SELECT id FROM academic_years WHERE school_id = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$schoolId]);
        return $stmt->fetchColumn() ?: null;
    }

    public function getStats($schoolId) {
        $stats = [];

        // 1. Total Students
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM students WHERE school_id = ? AND status = 'active'");
        $stmt->execute([$schoolId]);
        $stats['total_students'] = (int)$stmt->fetchColumn();

        // 2. Total Teachers
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM teachers WHERE school_id = ? AND status = 'active'");
        $stmt->execute([$schoolId]);
        $stats['total_teachers'] = (int)$stmt->fetchColumn();

        // 3. Total Subjects
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM subjects WHERE school_id = ?");
        $stmt->execute([$schoolId]);
        $stats['total_subjects'] = (int)$stmt->fetchColumn();

        // 4. Total Sections
        $stmt = $this->db->prepare("
            SELECT COUNT(s.id) FROM sections s
            JOIN grades g ON s.grade_id = g.id
            WHERE g.school_id = ?
        ");
        $stmt->execute([$schoolId]);
        $stats['total_sections'] = (int)$stmt->fetchColumn();

        // 5. Final Assessment mode state
        $stmt = $this->db->prepare("SELECT is_final_assessment_active FROM schools WHERE id = ?");
        $stmt->execute([$schoolId]);
        $stats['is_final_active'] = (bool)$stmt->fetchColumn();

        return [
            "success" => true,
            "stats" => $stats
        ];
    }

    public function getAssignmentData($schoolId) {
        // Fetch all teachers in this school
        $stmt = $this->db->prepare("SELECT id, full_name, teacher_id_code, specialization FROM teachers WHERE school_id = ? AND status = 'active'");
        $stmt->execute([$schoolId]);
        $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch all subjects in this school
        $stmt = $this->db->prepare("SELECT id, name, grade_level FROM subjects WHERE school_id = ? ORDER BY grade_level ASC, name ASC");
        $stmt->execute([$schoolId]);
        $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch all sections with student counts
        $stmt = $this->db->prepare("
            SELECT s.id, s.name as section_name, g.grade_level, g.stream, s.homeroom_teacher_id,
                   t.full_name as homeroom_teacher_name,
                   (SELECT COUNT(*) FROM students st WHERE st.section_id = s.id AND st.status = 'active') as student_count
            FROM sections s
            JOIN grades g ON s.grade_id = g.id
            LEFT JOIN teachers t ON s.homeroom_teacher_id = t.id
            WHERE g.school_id = ?
            ORDER BY g.grade_level ASC, s.name ASC
        ");
        $stmt->execute([$schoolId]);
        $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch active assignments
        $stmt = $this->db->prepare("
            SELECT ta.id as assignment_id, t.full_name as teacher_name, s.name as subject_name, 
                   sec.name as section_name, g.grade_level
            FROM teaching_assignments ta
            JOIN teachers t ON ta.teacher_id = t.id
            JOIN subjects s ON ta.subject_id = s.id
            JOIN sections sec ON ta.section_id = sec.id
            JOIN grades g ON sec.grade_id = g.id
            WHERE t.school_id = ?
        ");
        $stmt->execute([$schoolId]);
        $assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            "success" => true,
            "teachers" => $teachers,
            "subjects" => $subjects,
            "sections" => $sections,
            "assignments" => $assignments
        ];
    }

    public function assignTeacherSubject($data, $schoolId) {
        if (!isset($data['teacher_id'], $data['subject_id'], $data['section_id'])) {
            return ["success" => false, "message" => "Incomplete details."];
        }

        $yearId = $this->getActiveYearId($schoolId);
        if (!$yearId) {
            return ["success" => false, "message" => "No active academic year."];
        }

        // Check duplicate
        $stmt = $this->db->prepare("
            SELECT id FROM teaching_assignments 
            WHERE teacher_id = ? AND subject_id = ? AND section_id = ? AND academic_year_id = ?
        ");
        $stmt->execute([$data['teacher_id'], $data['subject_id'], $data['section_id'], $yearId]);
        if ($stmt->fetchColumn()) {
            return ["success" => false, "message" => "This teaching assignment already exists."];
        }

        $stmt = $this->db->prepare("
            INSERT INTO teaching_assignments (teacher_id, subject_id, section_id, academic_year_id)
            VALUES (?, ?, ?, ?)
        ");
        if ($stmt->execute([$data['teacher_id'], $data['subject_id'], $data['section_id'], $yearId])) {
            return ["success" => true, "message" => "Teacher successfully assigned to subject and class."];
        }
        return ["success" => false, "message" => "Failed to assign teacher."];
    }

    public function removeTeacherAssignment($assignmentId) {
        $stmt = $this->db->prepare("DELETE FROM teaching_assignments WHERE id = ?");
        if ($stmt->execute([$assignmentId])) {
            return ["success" => true, "message" => "Assignment removed successfully."];
        }
        return ["success" => false, "message" => "Failed to remove assignment."];
    }

    public function assignHomeroomTeacher($data) {
        if (!isset($data['section_id'], $data['teacher_id'])) {
            return ["success" => false, "message" => "Missing section or teacher ID."];
        }

        $teacherId = $data['teacher_id'] === "" ? null : $data['teacher_id'];

        $stmt = $this->db->prepare("UPDATE sections SET homeroom_teacher_id = ? WHERE id = ?");
        if ($stmt->execute([$teacherId, $data['section_id']])) {
            return ["success" => true, "message" => "Homeroom teacher updated successfully."];
        }
        return ["success" => false, "message" => "Failed to assign homeroom teacher."];
    }

    public function toggleFinalAssessmentMode($data, $schoolId) {
        if (!isset($data['active'])) {
            return ["success" => false, "message" => "State parameter missing."];
        }

        $active = $data['active'] ? 1 : 0;

        $stmt = $this->db->prepare("UPDATE schools SET is_final_assessment_active = ? WHERE id = ?");
        if ($stmt->execute([$active, $schoolId])) {
            return [
                "success" => true, 
                "message" => $active ? "Year-end final assessment mode is now ACTIVE. homeroom teachers can now submit student evaluations." : "Year-end final assessment mode is now CLOSED."
            ];
        }
        return ["success" => false, "message" => "Failed to toggle final assessment mode."];
    }

    // ==========================================
    // STUDENT SECTIONING ENDPOINTS
    // ==========================================

    public function getStudentSectioningData($schoolId) {
        // Fetch all sections
        $stmt = $this->db->prepare("
            SELECT s.id, s.name as section_name, g.grade_level, g.stream
            FROM sections s
            JOIN grades g ON s.grade_id = g.id
            WHERE g.school_id = ?
            ORDER BY g.grade_level ASC, s.name ASC
        ");
        $stmt->execute([$schoolId]);
        $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch students (including whether they are sectioned or not)
        $stmt = $this->db->prepare("
            SELECT st.id, st.student_id as student_code, st.full_name, st.email, 
                   st.section_id, sec.name as section_name, g.grade_level
            FROM students st
            LEFT JOIN sections sec ON st.section_id = sec.id
            LEFT JOIN grades g ON sec.grade_id = g.id
            WHERE st.school_id = ? AND st.status = 'active'
            ORDER BY st.full_name ASC
        ");
        $stmt->execute([$schoolId]);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            "success" => true,
            "sections" => $sections,
            "students" => $students
        ];
    }

    public function assignStudentSection($data) {
        if (!isset($data['student_id'], $data['section_id'])) {
            return ["success" => false, "message" => "Missing student or section ID."];
        }

        $sectionId = $data['section_id'] === "" ? null : $data['section_id'];

        $stmt = $this->db->prepare("UPDATE students SET section_id = ? WHERE id = ?");
        if ($stmt->execute([$sectionId, $data['student_id']])) {
            return ["success" => true, "message" => "Student section updated successfully."];
        }
        return ["success" => false, "message" => "Failed to section student."];
    }

    public function createSection($data, $schoolId) {
        if (empty($data['name']) || empty($data['grade_level'])) {
            return ["success" => false, "message" => "Section name and grade level are required."];
        }
        $sectionName = strtoupper(trim($data['name']));
        $gradeLevel = (int)$data['grade_level'];
        $stream = !empty($data['stream']) ? trim($data['stream']) : 'general';

        if (!in_array($stream, ['general', 'natural_science', 'social_science'])) {
            return ["success" => false, "message" => "Invalid stream selection."];
        }

        try {
            $this->db->beginTransaction();

            // 1. Find or create the grade record
            $stmt = $this->db->prepare("SELECT id FROM grades WHERE school_id = ? AND grade_level = ? AND stream = ?");
            $stmt->execute([$schoolId, $gradeLevel, $stream]);
            $gradeId = $stmt->fetchColumn();

            if (!$gradeId) {
                $stmt = $this->db->prepare("INSERT INTO grades (school_id, grade_level, stream) VALUES (?, ?, ?)");
                $stmt->execute([$schoolId, $gradeLevel, $stream]);
                $gradeId = $this->db->lastInsertId();
            }

            // 2. Check if section name already exists for this grade
            $stmt = $this->db->prepare("SELECT id FROM sections WHERE grade_id = ? AND name = ?");
            $stmt->execute([$gradeId, $sectionName]);
            if ($stmt->fetchColumn()) {
                $this->db->rollBack();
                return ["success" => false, "message" => "Section '$sectionName' already exists for Grade $gradeLevel ($stream)."];
            }

            // 3. Insert section
            $stmt = $this->db->prepare("INSERT INTO sections (grade_id, name) VALUES (?, ?)");
            $stmt->execute([$gradeId, $sectionName]);

            $this->db->commit();
            return ["success" => true, "message" => "Section '$sectionName' created successfully for Grade $gradeLevel ($stream)."];
        } catch (\PDOException $e) {
            $this->db->rollBack();
            return ["success" => false, "message" => "Failed to create section: " . $e->getMessage()];
        }
    }

    public function updateSection($data, $schoolId) {
        if (empty($data['section_id']) || empty($data['name']) || empty($data['grade_level'])) {
            return ["success" => false, "message" => "Section ID, name, and grade level are required."];
        }
        $sectionId = (int)$data['section_id'];
        $sectionName = strtoupper(trim($data['name']));
        $gradeLevel = (int)$data['grade_level'];
        $stream = !empty($data['stream']) ? trim($data['stream']) : 'general';

        if (!in_array($stream, ['general', 'natural_science', 'social_science'])) {
            return ["success" => false, "message" => "Invalid stream selection."];
        }

        try {
            $this->db->beginTransaction();

            // 1. Find or create the grade record
            $stmt = $this->db->prepare("SELECT id FROM grades WHERE school_id = ? AND grade_level = ? AND stream = ?");
            $stmt->execute([$schoolId, $gradeLevel, $stream]);
            $gradeId = $stmt->fetchColumn();

            if (!$gradeId) {
                $stmt = $this->db->prepare("INSERT INTO grades (school_id, grade_level, stream) VALUES (?, ?, ?)");
                $stmt->execute([$schoolId, $gradeLevel, $stream]);
                $gradeId = $this->db->lastInsertId();
            }

            // 2. Check if section name already exists for this grade (excluding current section ID)
            $stmt = $this->db->prepare("SELECT id FROM sections WHERE grade_id = ? AND name = ? AND id != ?");
            $stmt->execute([$gradeId, $sectionName, $sectionId]);
            if ($stmt->fetchColumn()) {
                $this->db->rollBack();
                return ["success" => false, "message" => "Another section '$sectionName' already exists for Grade $gradeLevel ($stream)."];
            }

            // 3. Update section
            $stmt = $this->db->prepare("UPDATE sections SET grade_id = ?, name = ? WHERE id = ?");
            $stmt->execute([$gradeId, $sectionName, $sectionId]);

            $this->db->commit();
            return ["success" => true, "message" => "Section updated successfully."];
        } catch (\PDOException $e) {
            $this->db->rollBack();
            return ["success" => false, "message" => "Failed to update section: " . $e->getMessage()];
        }
    }

    public function deleteSection($data, $schoolId) {
        if (empty($data['section_id'])) {
            return ["success" => false, "message" => "Section ID is required."];
        }
        $sectionId = (int)$data['section_id'];

        // Verify section exists and belongs to school
        $stmt = $this->db->prepare("
            SELECT s.id FROM sections s
            JOIN grades g ON s.grade_id = g.id
            WHERE s.id = ? AND g.school_id = ?
        ");
        $stmt->execute([$sectionId, $schoolId]);
        if (!$stmt->fetchColumn()) {
            return ["success" => false, "message" => "Section not found or access denied."];
        }

        try {
            $this->db->beginTransaction();

            // 1. Find all teaching assignments for this section
            $stmt = $this->db->prepare("SELECT id FROM teaching_assignments WHERE section_id = ?");
            $stmt->execute([$sectionId]);
            $assignments = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (!empty($assignments)) {
                // For each teaching assignment, delete related assessment grades and assessments
                foreach ($assignments as $assignmentId) {
                    // Find assessments
                    $stmt = $this->db->prepare("SELECT id FROM assessments WHERE teaching_assignment_id = ?");
                    $stmt->execute([$assignmentId]);
                    $assessments = $stmt->fetchAll(PDO::FETCH_COLUMN);

                    if (!empty($assessments)) {
                        // Delete grade entries
                        $inAssessments = implode(',', array_fill(0, count($assessments), '?'));
                        $stmt = $this->db->prepare("DELETE FROM grades_entries WHERE assessment_id IN ($inAssessments)");
                        $stmt->execute($assessments);

                        // Delete assessments
                        $stmt = $this->db->prepare("DELETE FROM assessments WHERE teaching_assignment_id = ?");
                        $stmt->execute([$assignmentId]);
                    }
                }

                // Delete teaching assignments
                $stmt = $this->db->prepare("DELETE FROM teaching_assignments WHERE section_id = ?");
                $stmt->execute([$sectionId]);
            }

            // 2. Set student section_id to NULL
            $stmt = $this->db->prepare("UPDATE students SET section_id = NULL WHERE section_id = ?");
            $stmt->execute([$sectionId]);

            // 3. Delete section
            $stmt = $this->db->prepare("DELETE FROM sections WHERE id = ?");
            $stmt->execute([$sectionId]);

            $this->db->commit();
            return ["success" => true, "message" => "Section and all related assignments/grades deleted successfully."];
        } catch (\PDOException $e) {
            $this->db->rollBack();
            return ["success" => false, "message" => "Failed to delete section: " . $e->getMessage()];
        }
    }

    public function randomSectioning($data, $schoolId) {
        if (!isset($data['grade_level'])) {
            return ["success" => false, "message" => "Missing grade level."];
        }

        $gradeLevel = (int)$data['grade_level'];

        // Get all sections for this grade level in this school
        $stmt = $this->db->prepare("
            SELECT s.id FROM sections s
            JOIN grades g ON s.grade_id = g.id
            WHERE g.school_id = ? AND g.grade_level = ?
        ");
        $stmt->execute([$schoolId, $gradeLevel]);
        $sections = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (empty($sections)) {
            return ["success" => false, "message" => "No sections created for Grade $gradeLevel."];
        }

        // Get all active students in the school who do NOT have a section, OR get all active students of this grade level
        // For standard randomized sectioning, we redistribute students in the school without sections, or we can distribute all students who entered in a certain year.
        // Let's section all students in this school that do NOT currently have a section!
        $stmt = $this->db->prepare("SELECT id FROM students WHERE school_id = ? AND section_id IS NULL AND status = 'active'");
        $stmt->execute([$schoolId]);
        $unsectionedStudents = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (empty($unsectionedStudents)) {
            return ["success" => true, "message" => "No unsectioned students found to assign."];
        }

        // Shuffle students randomly
        shuffle($unsectionedStudents);

        // Evenly distribute among sections
        $sectionCount = count($sections);
        try {
            $this->db->beginTransaction();
            $stmtUpdate = $this->db->prepare("UPDATE students SET section_id = ? WHERE id = ?");

            foreach ($unsectionedStudents as $index => $studentId) {
                $sectionId = $sections[$index % $sectionCount];
                $stmtUpdate->execute([$sectionId, $studentId]);
            }

            $this->db->commit();
            return ["success" => true, "message" => "Successfully distributed " . count($unsectionedStudents) . " students randomly into " . $sectionCount . " sections."];
        } catch (\PDOException $e) {
            $this->db->rollBack();
            return ["success" => false, "message" => "Random assignment failed: " . $e->getMessage()];
        }
    }

    // ==========================================
    // PARENT MANAGEMENT ENDPOINTS
    // ==========================================

    public function getParentsList($schoolId) {
        $stmt = $this->db->prepare("
            SELECT DISTINCT p.id, p.full_name, p.email, p.phone, p.created_at
            FROM parents p
            JOIN parent_student ps ON p.id = ps.parent_id
            JOIN students s ON ps.student_id = s.id
            WHERE s.school_id = ?
        ");
        $stmt->execute([$schoolId]);
        $parents = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch all students for linking dropdown
        $stmt = $this->db->prepare("SELECT id, full_name, student_id FROM students WHERE school_id = ?");
        $stmt->execute([$schoolId]);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            "success" => true,
            "parents" => $parents,
            "students" => $students
        ];
    }

    public function createParentAndLink($data, $schoolId) {
        if (!isset($data['full_name'], $data['email'], $data['phone'], $data['student_id'], $data['relation_type'])) {
            return ["success" => false, "message" => "Incomplete fields."];
        }

        try {
            $this->db->beginTransaction();

            // Create or fetch parent
            $stmt = $this->db->prepare("SELECT id FROM parents WHERE email = ?");
            $stmt->execute([$data['email']]);
            $parentId = $stmt->fetchColumn();

            if (!$parentId) {
                $passwordHash = password_hash('password123', PASSWORD_DEFAULT);
                $stmt = $this->db->prepare("INSERT INTO parents (full_name, email, password_hash, phone) VALUES (?, ?, ?, ?)");
                $stmt->execute([$data['full_name'], $data['email'], $passwordHash, $data['phone']]);
                $parentId = $this->db->lastInsertId();
            }

            // Link to student
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM parent_student WHERE parent_id = ? AND student_id = ?");
            $stmt->execute([$parentId, $data['student_id']]);
            if ($stmt->fetchColumn() == 0) {
                $stmt = $this->db->prepare("INSERT INTO parent_student (parent_id, student_id, relation_type) VALUES (?, ?, ?)");
                $stmt->execute([$parentId, $data['student_id'], $data['relation_type']]);
            }

            $this->db->commit();
            return ["success" => true, "message" => "Parent successfully registered and linked to student!"];
        } catch (\PDOException $e) {
            $this->db->rollBack();
            return ["success" => false, "message" => "Failed: " . $e->getMessage()];
        }
    }

    // ==========================================
    // CURRICULUM SUBJECT CRUD
    // ==========================================

    public function getSubjectsList($schoolId) {
        $stmt = $this->db->prepare("SELECT id, name, grade_level FROM subjects WHERE school_id = ? ORDER BY grade_level ASC, name ASC");
        $stmt->execute([$schoolId]);
        return [
            "success" => true,
            "subjects" => $stmt->fetchAll(PDO::FETCH_ASSOC)
        ];
    }

    public function addSubject($data, $schoolId) {
        if (empty($data['name']) || empty($data['grade_level'])) {
            return ["success" => false, "message" => "Subject name and grade level are required."];
        }
        $name = trim($data['name']);
        $gradeLevel = (int)$data['grade_level'];

        $stmt = $this->db->prepare("INSERT INTO subjects (school_id, name, grade_level) VALUES (?, ?, ?)");
        if ($stmt->execute([$schoolId, $name, $gradeLevel])) {
            return ["success" => true, "message" => "Subject '$name' added successfully for Grade $gradeLevel."];
        }
        return ["success" => false, "message" => "Failed to add subject."];
    }

    public function editSubject($data, $schoolId) {
        if (empty($data['subject_id']) || empty($data['name']) || empty($data['grade_level'])) {
            return ["success" => false, "message" => "Missing subject ID, name, or grade level."];
        }
        $id = (int)$data['subject_id'];
        $name = trim($data['name']);
        $gradeLevel = (int)$data['grade_level'];

        $stmt = $this->db->prepare("UPDATE subjects SET name = ?, grade_level = ? WHERE id = ? AND school_id = ?");
        if ($stmt->execute([$name, $gradeLevel, $id, $schoolId])) {
            return ["success" => true, "message" => "Subject updated successfully."];
        }
        return ["success" => false, "message" => "Failed to update subject."];
    }

    public function deleteSubject($data, $schoolId) {
        if (empty($data['subject_id'])) {
            return ["success" => false, "message" => "Subject ID is required."];
        }
        $id = (int)$data['subject_id'];

        $stmt = $this->db->prepare("DELETE FROM subjects WHERE id = ? AND school_id = ?");
        if ($stmt->execute([$id, $schoolId])) {
            return ["success" => true, "message" => "Subject deleted successfully."];
        }
        return ["success" => false, "message" => "Failed to delete subject."];
    }

    // ==========================================
    // ACADEMIC TERM MANAGEMENT
    // ==========================================

    public function getTermsList($schoolId) {
        $yearId = $this->getActiveYearId($schoolId);
        if (!$yearId) {
            return ["success" => false, "message" => "No active academic year found.", "terms" => []];
        }
        $stmt = $this->db->prepare("SELECT id, name, is_active FROM terms WHERE academic_year_id = ? ORDER BY id ASC");
        $stmt->execute([$yearId]);
        return [
            "success" => true,
            "terms" => $stmt->fetchAll(PDO::FETCH_ASSOC),
            "academic_year_id" => $yearId
        ];
    }

    public function configureTermSystem($data, $schoolId) {
        if (empty($data['system_type'])) {
            return ["success" => false, "message" => "System type (2-term or 3-term) is required."];
        }
        $type = trim($data['system_type']); // "2-term" or "3-term"

        $yearId = $this->getActiveYearId($schoolId);
        if (!$yearId) {
            return ["success" => false, "message" => "No active academic year found."];
        }

        try {
             $this->db->beginTransaction();

             // Delete old terms for this active year
             $stmtDel = $this->db->prepare("DELETE FROM terms WHERE academic_year_id = ?");
             $stmtDel->execute([$yearId]);

             if ($type === '3-term') {
                 $terms = ['Trimester 1', 'Trimester 2', 'Trimester 3'];
             } else {
                 $terms = ['Semester 1', 'Semester 2'];
             }

             $stmtIns = $this->db->prepare("INSERT INTO terms (academic_year_id, name, is_active) VALUES (?, ?, ?)");
             foreach ($terms as $index => $termName) {
                 $isActive = ($index === 0) ? 1 : 0; // First term active by default
                 $stmtIns->execute([$yearId, $termName, $isActive]);
             }

             $this->db->commit();
             return ["success" => true, "message" => "Academic cycle reconfigured successfully to " . ($type === '3-term' ? "Trimester (3 Terms)" : "Semester (2 Terms)") . "."];
        } catch (\PDOException $e) {
             $this->db->rollBack();
             return ["success" => false, "message" => "Failed to reconfigure terms: " . $e->getMessage()];
        }
    }

    public function setActiveTerm($data, $schoolId) {
        if (empty($data['term_id'])) {
            return ["success" => false, "message" => "Term ID is required."];
        }
        $termId = (int)$data['term_id'];

        $yearId = $this->getActiveYearId($schoolId);
        if (!$yearId) {
            return ["success" => false, "message" => "No active academic year."];
        }

        try {
             $this->db->beginTransaction();

             // Set all terms of this year inactive
             $stmtInact = $this->db->prepare("UPDATE terms SET is_active = 0 WHERE academic_year_id = ?");
             $stmtInact->execute([$yearId]);

             // Set specific term active
             $stmtAct = $this->db->prepare("UPDATE terms SET is_active = 1 WHERE id = ? AND academic_year_id = ?");
             $stmtAct->execute([$termId, $yearId]);

             $this->db->commit();
             return ["success" => true, "message" => "Active term updated successfully."];
        } catch (\PDOException $e) {
             $this->db->rollBack();
             return ["success" => false, "message" => "Failed to change active term: " . $e->getMessage()];
        }
    }

    // ==========================================
    // USER MANAGEMENT
    // ==========================================

    public function createSingleUser($data, $schoolId) {
        if (empty($data['full_name']) || empty($data['email']) || empty($data['role'])) {
            return ["success" => false, "message" => "Full name, email, and role are required."];
        }

        $fullName = trim($data['full_name']);
        $email = trim($data['email']);
        $role = $data['role']; // 'student' or 'teacher'
        $sectionId = !empty($data['section_id']) ? (int)$data['section_id'] : null;

        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["success" => false, "message" => "Invalid email format."];
        }

        // Check if email already exists
        $chk = $this->db->prepare("SELECT id FROM teachers WHERE email = ? UNION SELECT id FROM students WHERE email = ?");
        $chk->execute([$email, $email]);
        if ($chk->fetchColumn()) {
            return ["success" => false, "message" => "Email is already registered."];
        }

        $password = bin2hex(random_bytes(4)); // 8 chars
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        // Get School Code
        $stmt = $this->db->prepare("SELECT school_code FROM schools WHERE id = ?");
        $stmt->execute([$schoolId]);
        $schoolCode = $stmt->fetchColumn();

        if (!$schoolCode) {
            return ["success" => false, "message" => "Invalid school ID."];
        }

        $currentYear = date('y');

        try {
            if ($role === 'teacher') {
                $nextNo = $this->getNextSequence($schoolId, 'teacher');
                $generatedId = "{$schoolCode}T" . str_pad($nextNo, 4, '0', STR_PAD_LEFT) . "/{$currentYear}";
                
                $stmt = $this->db->prepare("INSERT INTO teachers (teacher_id_code, school_id, full_name, email, password_hash, status, must_change_password) VALUES (?, ?, ?, ?, ?, 'active', 0)");
                $stmt->execute([$generatedId, $schoolId, $fullName, $email, $passwordHash]);

                return [
                    "success" => true, 
                    "message" => "Teacher created successfully.", 
                    "id_code" => $generatedId, 
                    "password" => $password,
                    "full_name" => $fullName,
                    "email" => $email,
                    "role" => $role
                ];
            } else if ($role === 'student') {
                $nextNo = $this->getNextSequence($schoolId, 'student');
                $generatedId = "{$schoolCode}" . str_pad($nextNo, 4, '0', STR_PAD_LEFT) . "/{$currentYear}";
                $enrollmentYear = date('Y');

                $stmt = $this->db->prepare("INSERT INTO students (student_id, school_id, full_name, email, password_hash, enrollment_year, section_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'active')");
                $stmt->execute([$generatedId, $schoolId, $fullName, $email, $passwordHash, $enrollmentYear, $sectionId]);

                return [
                    "success" => true, 
                    "message" => "Student created successfully.", 
                    "id_code" => $generatedId, 
                    "password" => $password,
                    "full_name" => $fullName,
                    "email" => $email,
                    "role" => $role
                ];
            } else {
                return ["success" => false, "message" => "Invalid role."];
            }
        } catch (\PDOException $e) {
            return ["success" => false, "message" => "Database error: " . $e->getMessage()];
        }
    }

    // ==========================================
    // ACADEMIC YEAR MANAGEMENT
    // ==========================================

    public function getAcademicYears($schoolId) {
        $stmt = $this->db->prepare("SELECT id, name, is_active FROM academic_years WHERE school_id = ? ORDER BY id DESC");
        $stmt->execute([$schoolId]);
        $years = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ["success" => true, "years" => $years];
    }

    public function createAcademicYear($data, $schoolId) {
        if (empty($data['name'])) {
            return ["success" => false, "message" => "Academic year name is required. (e.g. 2016/17 E.C)"];
        }
        $name = trim($data['name']);
        // Check duplicate
        $stmt = $this->db->prepare("SELECT id FROM academic_years WHERE school_id = ? AND name = ?");
        $stmt->execute([$schoolId, $name]);
        if ($stmt->fetchColumn()) {
            return ["success" => false, "message" => "Academic year '$name' already exists."];
        }
        $isActive = !empty($data['set_active']) ? 1 : 0;
        try {
            $this->db->beginTransaction();
            if ($isActive) {
                $this->db->prepare("UPDATE academic_years SET is_active = 0 WHERE school_id = ?")->execute([$schoolId]);
            }
            $this->db->prepare("INSERT INTO academic_years (school_id, name, is_active) VALUES (?, ?, ?)")->execute([$schoolId, $name, $isActive]);
            $yearId = $this->db->lastInsertId();
            // Auto-create default semester terms
            $terms = ['Semester 1', 'Semester 2'];
            $stmtT = $this->db->prepare("INSERT INTO terms (academic_year_id, name, is_active) VALUES (?, ?, ?)");
            foreach ($terms as $i => $termName) {
                $stmtT->execute([$yearId, $termName, $i === 0 ? 1 : 0]);
            }
            $this->db->commit();
            return ["success" => true, "message" => "Academic year '$name' created" . ($isActive ? " and set as active." : ".")];
        } catch (\PDOException $e) {
            $this->db->rollBack();
            return ["success" => false, "message" => "Failed: " . $e->getMessage()];
        }
    }

    public function setActiveAcademicYear($data, $schoolId) {
        if (empty($data['year_id'])) {
            return ["success" => false, "message" => "Year ID is required."];
        }
        $yearId = (int)$data['year_id'];
        // Verify belongs to school
        $stmt = $this->db->prepare("SELECT id FROM academic_years WHERE id = ? AND school_id = ?");
        $stmt->execute([$yearId, $schoolId]);
        if (!$stmt->fetchColumn()) {
            return ["success" => false, "message" => "Year not found or access denied."];
        }
        try {
            $this->db->beginTransaction();
            $this->db->prepare("UPDATE academic_years SET is_active = 0 WHERE school_id = ?")->execute([$schoolId]);
            $this->db->prepare("UPDATE academic_years SET is_active = 1 WHERE id = ?")->execute([$yearId]);
            $this->db->commit();
            return ["success" => true, "message" => "Active academic year updated."];
        } catch (\PDOException $e) {
            $this->db->rollBack();
            return ["success" => false, "message" => "Failed: " . $e->getMessage()];
        }
    }

    public function deleteAcademicYear($data, $schoolId) {
        if (empty($data['year_id'])) {
            return ["success" => false, "message" => "Year ID is required."];
        }
        $yearId = (int)$data['year_id'];
        $stmt = $this->db->prepare("SELECT id, is_active FROM academic_years WHERE id = ? AND school_id = ?");
        $stmt->execute([$yearId, $schoolId]);
        $year = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$year) return ["success" => false, "message" => "Year not found."];
        if ($year['is_active']) return ["success" => false, "message" => "Cannot delete the currently active academic year."];
        try {
            $this->db->beginTransaction();
            // Cascade: delete terms first (teaching_assignments has FK to academic_years — cascade not set so manual)
            $this->db->prepare("DELETE FROM terms WHERE academic_year_id = ?")->execute([$yearId]);
            $this->db->prepare("DELETE FROM academic_years WHERE id = ?")->execute([$yearId]);
            $this->db->commit();
            return ["success" => true, "message" => "Academic year deleted."];
        } catch (\PDOException $e) {
            $this->db->rollBack();
            return ["success" => false, "message" => "Failed: " . $e->getMessage()];
        }
    }

    // ==========================================
    // ASSESSMENT TYPES MANAGEMENT
    // ==========================================

    public function getAssessmentTypes($schoolId) {
        $stmt = $this->db->prepare("SELECT id, name, weight FROM assessment_types WHERE school_id = ? ORDER BY id ASC");
        $stmt->execute([$schoolId]);
        $types = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ["success" => true, "assessment_types" => $types];
    }

    public function createAssessmentType($data, $schoolId) {
        if (empty($data['name']) || !isset($data['weight'])) {
            return ["success" => false, "message" => "Name and weight are required."];
        }
        $name = trim($data['name']);
        $weight = (float)$data['weight'];
        
        if ($weight <= 0 || $weight > 1) {
             return ["success" => false, "message" => "Weight must be between 0.01 and 1.0 (e.g. 0.5 for 50%)."];
        }

        // Check duplicate
        $stmt = $this->db->prepare("SELECT id FROM assessment_types WHERE school_id = ? AND name = ?");
        $stmt->execute([$schoolId, $name]);
        if ($stmt->fetchColumn()) {
            return ["success" => false, "message" => "Assessment type '$name' already exists."];
        }

        try {
            $this->db->prepare("INSERT INTO assessment_types (school_id, name, weight) VALUES (?, ?, ?)")
                     ->execute([$schoolId, $name, $weight]);
            return ["success" => true, "message" => "Assessment type created successfully."];
        } catch (\PDOException $e) {
            return ["success" => false, "message" => "Database error: " . $e->getMessage()];
        }
    }

    public function deleteAssessmentType($data, $schoolId) {
        if (empty($data['type_id'])) {
            return ["success" => false, "message" => "Assessment Type ID is required."];
        }
        $typeId = (int)$data['type_id'];

        // Check if it exists and belongs to school
        $stmt = $this->db->prepare("SELECT id FROM assessment_types WHERE id = ? AND school_id = ?");
        $stmt->execute([$typeId, $schoolId]);
        if (!$stmt->fetchColumn()) {
            return ["success" => false, "message" => "Assessment type not found."];
        }

        // Check if there are assessments tied to it
        $stmt = $this->db->prepare("SELECT id FROM assessments WHERE assessment_type_id = ? LIMIT 1");
        $stmt->execute([$typeId]);
        if ($stmt->fetchColumn()) {
            return ["success" => false, "message" => "Cannot delete: This assessment type is already used by teachers for grading."];
        }

        try {
            $this->db->prepare("DELETE FROM assessment_types WHERE id = ?")->execute([$typeId]);
            return ["success" => true, "message" => "Assessment type deleted successfully."];
        } catch (\PDOException $e) {
            return ["success" => false, "message" => "Database error: " . $e->getMessage()];
        }
    }

    private function getNextSequence($schoolId, $type) {
        $column = $type === 'teacher' ? 'next_teacher_no' : 'next_student_no';
        
        $stmt = $this->db->prepare("SELECT $column FROM school_sequences WHERE school_id = ?");
        $stmt->execute([$schoolId]);
        $val = $stmt->fetchColumn();

        if ($val === false) {
            $this->db->prepare("INSERT INTO school_sequences (school_id, next_student_no, next_teacher_no) VALUES (?, 1, 1)")->execute([$schoolId]);
            $val = 1;
        }

        $this->db->prepare("UPDATE school_sequences SET $column = $column + 1 WHERE school_id = ?")->execute([$schoolId]);

        return $val;
    }
}
