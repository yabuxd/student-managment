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

        // Fetch all sections
        $stmt = $this->db->prepare("
            SELECT s.id, s.name as section_name, g.grade_level, g.stream, s.homeroom_teacher_id, t.full_name as homeroom_teacher_name
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
}
