<?php

namespace App\Controllers;

use PDO;
use App\Services\GradingService;

class PortalController {
    private $db;
    private $gradingService;

    public function __construct($db) {
        $this->db = $db;
        $this->gradingService = new GradingService($db);
    }

    private function getActiveTermId($schoolId) {
        $stmt = $this->db->prepare("
            SELECT t.id FROM terms t
            JOIN academic_years ay ON t.academic_year_id = ay.id
            WHERE ay.school_id = ? AND ay.is_active = 1 AND t.is_active = 1
            LIMIT 1
        ");
        $stmt->execute([$schoolId]);
        return $stmt->fetchColumn() ?: null;
    }

    private function getActiveYearId($schoolId) {
        $stmt = $this->db->prepare("
            SELECT id FROM academic_years
            WHERE school_id = ? AND is_active = 1
            LIMIT 1
        ");
        $stmt->execute([$schoolId]);
        return $stmt->fetchColumn() ?: null;
    }

    // ==========================================
    // STUDENT PORTAL ENDPOINTS
    // ==========================================

    public function getStudentCourses($studentId) {
        $query = "
            SELECT s.id as subject_id, s.name as subject_name, t.full_name as teacher_name, ta.id as teaching_assignment_id
            FROM subjects s
            JOIN teaching_assignments ta ON s.id = ta.subject_id
            JOIN students st ON ta.section_id = st.section_id
            LEFT JOIN teachers t ON ta.teacher_id = t.id
            WHERE st.id = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$studentId]);
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch overall average and active term
        $studentStmt = $this->db->prepare("SELECT school_id, section_id FROM students WHERE id = ?");
        $studentStmt->execute([$studentId]);
        $student = $studentStmt->fetch(PDO::FETCH_ASSOC);

        $termId = $student ? $this->getActiveTermId($student['school_id']) : null;
        $overallAverage = ($student && $termId) ? $this->gradingService->getStudentOverallAverage($studentId, $termId) : 0;

        // Fetch section details
        $sectionDetails = "";
        if ($student && $student['section_id']) {
            $sectStmt = $this->db->prepare("
                SELECT CONCAT('Grade ', g.grade_level, ' - Section ', s.name) as section_name 
                FROM sections s 
                JOIN grades g ON s.grade_id = g.id 
                WHERE s.id = ?
            ");
            $sectStmt->execute([$student['section_id']]);
            $sectionDetails = $sectStmt->fetchColumn() ?: "";
        }

        return [
            "success" => true,
            "courses" => $courses,
            "overall_average" => round($overallAverage, 2),
            "section_name" => $sectionDetails,
            "term_id" => $termId
        ];
    }

    public function getStudentCourseGrades($studentId, $subjectId) {
        $studentStmt = $this->db->prepare("SELECT school_id, section_id FROM students WHERE id = ?");
        $studentStmt->execute([$studentId]);
        $student = $studentStmt->fetch(PDO::FETCH_ASSOC);

        if (!$student) {
            return ["success" => false, "message" => "Student not found."];
        }

        $termId = $this->getActiveTermId($student['school_id']);
        if (!$termId) {
            return ["success" => false, "message" => "No active semester/term found."];
        }

        $query = "
            SELECT a.id as assessment_id, a.title, a.max_score, a.assessment_date, 
                   at.name as type_name, at.weight, ge.score
            FROM assessments a
            JOIN assessment_types at ON a.assessment_type_id = at.id
            JOIN teaching_assignments ta ON a.teaching_assignment_id = ta.id
            LEFT JOIN grades_entries ge ON a.id = ge.assessment_id AND ge.student_id = :student_id
            WHERE ta.subject_id = :subject_id
              AND ta.section_id = :section_id
              AND a.term_id = :term_id
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":student_id", $studentId);
        $stmt->bindParam(":subject_id", $subjectId);
        $stmt->bindParam(":section_id", $student['section_id']);
        $stmt->bindParam(":term_id", $termId);
        $stmt->execute();
        $grades = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $weightedAverage = $this->gradingService->getStudentSubjectAverage($studentId, $subjectId, $termId);

        return [
            "success" => true,
            "grades" => $grades,
            "weighted_average" => round($weightedAverage, 2)
        ];
    }

    public function getStudentFinalEvaluation($studentId) {
        $studentStmt = $this->db->prepare("SELECT school_id FROM students WHERE id = ?");
        $studentStmt->execute([$studentId]);
        $schoolId = $studentStmt->fetchColumn();

        if (!$schoolId) {
            return ["success" => false, "message" => "Student not found."];
        }

        // Verify if final assessment mode is turned on by the director
        $schoolStmt = $this->db->prepare("SELECT is_final_assessment_active FROM schools WHERE id = ?");
        $schoolStmt->execute([$schoolId]);
        $isActive = $schoolStmt->fetchColumn();

        if (!$isActive) {
            return ["success" => true, "is_active" => false, "message" => "Year-end evaluation mode is currently closed."];
        }

        $yearId = $this->getActiveYearId($schoolId);
        if (!$yearId) {
            return ["success" => false, "message" => "No active academic year."];
        }

        $stmt = $this->db->prepare("
            SELECT fe.*, t.full_name as evaluator_name 
            FROM student_final_evaluations fe
            LEFT JOIN teachers t ON fe.evaluated_by = t.id
            WHERE fe.student_id = ? AND fe.academic_year_id = ?
        ");
        $stmt->execute([$studentId, $yearId]);
        $evaluation = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$evaluation) {
            return ["success" => true, "is_active" => true, "status" => "pending", "message" => "Your homeroom teacher is compiling evaluations."];
        }

        return [
            "success" => true,
            "is_active" => true,
            "evaluation" => $evaluation
        ];
    }

    // ==========================================
    // TEACHER PORTAL ENDPOINTS
    // ==========================================

    public function getTeacherClasses($teacherId) {
        $query = "
            SELECT ta.id as assignment_id, ta.section_id, ta.subject_id,
                   s.name as subject_name, sec.name as section_name, g.grade_level, g.stream
            FROM teaching_assignments ta
            JOIN subjects s ON ta.subject_id = s.id
            JOIN sections sec ON ta.section_id = sec.id
            JOIN grades g ON sec.grade_id = g.id
            WHERE ta.teacher_id = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$teacherId]);
        $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if teacher is a homeroom teacher for any section
        $homeroomStmt = $this->db->prepare("
            SELECT s.id as section_id, s.name as section_name, g.grade_level 
            FROM sections s
            JOIN grades g ON s.grade_id = g.id
            WHERE s.homeroom_teacher_id = ?
            LIMIT 1
        ");
        $homeroomStmt->execute([$teacherId]);
        $homeroomClass = $homeroomStmt->fetch(PDO::FETCH_ASSOC);

        return [
            "success" => true,
            "classes" => $classes,
            "homeroom_class" => $homeroomClass ?: null
        ];
    }

    public function getTeacherClassStudents($sectionId) {
        $stmt = $this->db->prepare("SELECT id, student_id, full_name, email FROM students WHERE section_id = ? ORDER BY full_name ASC");
        $stmt->execute([$sectionId]);
        return [
            "success" => true,
            "students" => $stmt->fetchAll(PDO::FETCH_ASSOC)
        ];
    }

    public function getAssessments($assignmentId, $schoolId) {
        $termId = $this->getActiveTermId($schoolId);
        if (!$termId) {
            return ["success" => false, "message" => "No active term."];
        }

        $stmt = $this->db->prepare("
            SELECT a.id, a.title, a.max_score, a.assessment_date, at.name as type_name, at.weight 
            FROM assessments a
            JOIN assessment_types at ON a.assessment_type_id = at.id
            WHERE a.teaching_assignment_id = ? AND a.term_id = ?
        ");
        $stmt->execute([$assignmentId, $termId]);
        
        // Fetch assessment types for creating new ones
        $typesStmt = $this->db->prepare("SELECT id, name, weight FROM assessment_types WHERE school_id = ?");
        $typesStmt->execute([$schoolId]);

        return [
            "success" => true,
            "assessments" => $stmt->fetchAll(PDO::FETCH_ASSOC),
            "assessment_types" => $typesStmt->fetchAll(PDO::FETCH_ASSOC),
            "term_id" => $termId
        ];
    }

    public function createAssessment($data, $schoolId) {
        if (!isset($data['assignment_id'], $data['title'], $data['max_score'], $data['type_id'])) {
            return ["success" => false, "message" => "Incomplete data fields."];
        }

        $termId = $this->getActiveTermId($schoolId);
        if (!$termId) {
            return ["success" => false, "message" => "No active term."];
        }

        $date = isset($data['date']) ? $data['date'] : date('Y-m-d');

        $stmt = $this->db->prepare("
            INSERT INTO assessments (teaching_assignment_id, term_id, assessment_type_id, title, max_score, assessment_date)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        if ($stmt->execute([$data['assignment_id'], $termId, $data['type_id'], $data['title'], $data['max_score'], $date])) {
            return ["success" => true, "message" => "Assessment created successfully."];
        }
        return ["success" => false, "message" => "Failed to create assessment."];
    }

    public function submitGrades($data) {
        if (!isset($data['assessment_id'], $data['scores']) || !is_array($data['scores'])) {
            return ["success" => false, "message" => "Incomplete or malformed grades payload."];
        }

        try {
            $this->db->beginTransaction();

            $stmtDelete = $this->db->prepare("DELETE FROM grades_entries WHERE assessment_id = ? AND student_id = ?");
            $stmtInsert = $this->db->prepare("INSERT INTO grades_entries (assessment_id, student_id, score) VALUES (?, ?, ?)");

            foreach ($data['scores'] as $scoreRow) {
                $studentId = $scoreRow['student_id'];
                $score = $scoreRow['score'] === "" ? null : $scoreRow['score'];

                $stmtDelete->execute([$data['assessment_id'], $studentId]);
                if ($score !== null) {
                    $stmtInsert->execute([$data['assessment_id'], $studentId, $score]);
                }
            }

            $this->db->commit();
            return ["success" => true, "message" => "Grades submitted successfully."];
        } catch (\PDOException $e) {
            $this->db->rollBack();
            return ["success" => false, "message" => "Failed: " . $e->getMessage()];
        }
    }

    public function getHomeroomClassRoster($sectionId, $teacherId) {
        // Verify this teacher is indeed the homeroom teacher
        $check = $this->db->prepare("SELECT id, school_id FROM sections WHERE id = ? AND homeroom_teacher_id = ?");
        $check->execute([$sectionId, $teacherId]);
        $section = $check->fetch(PDO::FETCH_ASSOC);
        if (!$section) {
            return ["success" => false, "message" => "Access Denied: You are not the homeroom teacher for this class."];
        }

        $schoolId = $section['school_id'];
        $termId = $this->getActiveTermId($schoolId);
        $yearId = $this->getActiveYearId($schoolId);

        if (!$termId || !$yearId) {
            return ["success" => false, "message" => "Active term or year missing."];
        }

        // Fetch if final assessment active
        $schoolStmt = $this->db->prepare("SELECT is_final_assessment_active FROM schools WHERE id = ?");
        $schoolStmt->execute([$schoolId]);
        $isFinalActive = $schoolStmt->fetchColumn();

        // Get rankings
        $rankings = $this->gradingService->getSectionRankings($sectionId, $termId);

        // Fetch saved evaluations if any
        $evalsStmt = $this->db->prepare("
            SELECT student_id, average_score, class_rank, status 
            FROM student_final_evaluations
            WHERE academic_year_id = ?
        ");
        $evalsStmt->execute([$yearId]);
        $savedEvals = $evalsStmt->fetchAll(PDO::FETCH_KEY_PAIR | PDO::FETCH_GROUP); // returns [student_id => [[...]]]
        
        $roster = [];
        foreach ($rankings as $student) {
            $studentId = $student['id'];
            $status = 'pending';
            
            // Check if we have a saved final evaluation
            $saved = $this->db->prepare("SELECT status FROM student_final_evaluations WHERE student_id = ? AND academic_year_id = ?");
            $saved->execute([$studentId, $yearId]);
            $savedStatus = $saved->fetchColumn();
            if ($savedStatus) {
                $status = $savedStatus;
            }

            $roster[] = [
                "id" => $student['id'],
                "student_code" => $student['student_code'],
                "full_name" => $student['full_name'],
                "average" => $student['average'],
                "rank" => $student['rank'],
                "status" => $status
            ];
        }

        return [
            "success" => true,
            "roster" => $roster,
            "is_final_active" => (bool)$isFinalActive,
            "academic_year_id" => $yearId
        ];
    }

    public function submitHomeroomEvaluations($data, $teacherId) {
        if (!isset($data['section_id'], $data['evaluations']) || !is_array($data['evaluations'])) {
            return ["success" => false, "message" => "Malformed evaluations input."];
        }

        $sectionId = $data['section_id'];

        $check = $this->db->prepare("SELECT school_id FROM sections WHERE id = ? AND homeroom_teacher_id = ?");
        $check->execute([$sectionId, $teacherId]);
        $schoolId = $check->fetchColumn();
        if (!$schoolId) {
            return ["success" => false, "message" => "Access Denied."];
        }

        $yearId = $this->getActiveYearId($schoolId);
        if (!$yearId) {
            return ["success" => false, "message" => "No active year."];
        }

        try {
            $this->db->beginTransaction();

            $stmtUpsert = $this->db->prepare("
                INSERT INTO student_final_evaluations (student_id, academic_year_id, average_score, class_rank, status, evaluated_by)
                VALUES (:student_id, :year_id, :average, :rank, :status, :teacher_id)
                ON DUPLICATE KEY UPDATE 
                    average_score = :average,
                    class_rank = :rank,
                    status = :status,
                    evaluated_by = :teacher_id
            ");

            foreach ($data['evaluations'] as $eval) {
                $stmtUpsert->bindParam(":student_id", $eval['student_id']);
                $stmtUpsert->bindParam(":year_id", $yearId);
                $stmtUpsert->bindParam(":average", $eval['average']);
                $stmtUpsert->bindParam(":rank", $eval['rank']);
                $stmtUpsert->bindParam(":status", $eval['status']); // 'pass' or 'fail'
                $stmtUpsert->bindParam(":teacher_id", $teacherId);
                $stmtUpsert->execute();
            }

            $this->db->commit();
            return ["success" => true, "message" => "Class evaluations computed and saved successfully!"];
        } catch (\PDOException $e) {
            $this->db->rollBack();
            return ["success" => false, "message" => "Error saving evaluations: " . $e->getMessage()];
        }
    }

    public function updateAssessment($data) {
        if (!isset($data['assessment_id'], $data['title'], $data['max_score'], $data['type_id'], $data['date'])) {
            return ["success" => false, "message" => "Incomplete data fields."];
        }
        $stmt = $this->db->prepare("
            UPDATE assessments 
            SET title = ?, max_score = ?, assessment_type_id = ?, assessment_date = ?
            WHERE id = ?
        ");
        if ($stmt->execute([$data['title'], $data['max_score'], $data['type_id'], $data['date'], $data['assessment_id']])) {
            return ["success" => true, "message" => "Assessment updated successfully."];
        }
        return ["success" => false, "message" => "Failed to update assessment."];
    }

    public function deleteAssessment($assessmentId) {
        if (!$assessmentId) {
            return ["success" => false, "message" => "Missing assessment ID."];
        }
        try {
            $this->db->beginTransaction();
            // Delete grade entries first
            $stmt = $this->db->prepare("DELETE FROM grades_entries WHERE assessment_id = ?");
            $stmt->execute([$assessmentId]);
            
            // Delete assessment
            $stmt2 = $this->db->prepare("DELETE FROM assessments WHERE id = ?");
            $stmt2->execute([$assessmentId]);

            $this->db->commit();
            return ["success" => true, "message" => "Assessment deleted successfully."];
        } catch (\PDOException $e) {
            $this->db->rollBack();
            return ["success" => false, "message" => "Failed to delete assessment: " . $e->getMessage()];
        }
    }

    public function getStudentAssignmentGrades($studentId, $assignmentId) {
        if (!$studentId || !$assignmentId) {
            return ["success" => false, "message" => "Missing required parameters."];
        }
        $stmt = $this->db->prepare("
            SELECT a.id as assessment_id, a.title, a.max_score, a.assessment_date, 
                   at.name as type_name, ge.score
            FROM assessments a
            JOIN assessment_types at ON a.assessment_type_id = at.id
            LEFT JOIN grades_entries ge ON a.id = ge.assessment_id AND ge.student_id = ?
            WHERE a.teaching_assignment_id = ?
            ORDER BY a.assessment_date DESC, a.id DESC
        ");
        $stmt->execute([$studentId, $assignmentId]);
        $grades = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            "success" => true,
            "grades" => $grades
        ];
    }

    public function submitStudentSingleGrades($data) {
        if (!isset($data['student_id'], $data['scores']) || !is_array($data['scores'])) {
            return ["success" => false, "message" => "Incomplete or malformed grades payload."];
        }
        try {
            $this->db->beginTransaction();
            $stmtDelete = $this->db->prepare("DELETE FROM grades_entries WHERE assessment_id = ? AND student_id = ?");
            $stmtInsert = $this->db->prepare("INSERT INTO grades_entries (assessment_id, student_id, score) VALUES (?, ?, ?)");

            foreach ($data['scores'] as $g) {
                $assessmentId = $g['assessment_id'];
                $score = $g['score'] === "" || $g['score'] === null ? null : $g['score'];

                $stmtDelete->execute([$assessmentId, $data['student_id']]);
                if ($score !== null) {
                    $stmtInsert->execute([$assessmentId, $data['student_id'], $score]);
                }
            }
            $this->db->commit();
            return ["success" => true, "message" => "Student grades updated successfully."];
        } catch (\PDOException $e) {
            $this->db->rollBack();
            return ["success" => false, "message" => "Failed: " . $e->getMessage()];
        }
    }

    // ==========================================
    // PARENT PORTAL ENDPOINTS
    // ==========================================

    public function getParentStudents($parentId) {
        $stmt = $this->db->prepare("
            SELECT s.id, s.student_id as student_code, s.full_name, s.email, s.section_id, s.school_id, sch.name as school_name
            FROM parent_student ps
            JOIN students s ON ps.student_id = s.id
            JOIN schools sch ON s.school_id = sch.id
            WHERE ps.parent_id = ?
        ");
        $stmt->execute([$parentId]);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $results = [];
        foreach ($students as $stud) {
            $termId = $this->getActiveTermId($stud['school_id']);
            $avg = $termId ? $this->gradingService->getStudentOverallAverage($stud['id'], $termId) : 0;
            
            // Get section name
            $secName = "";
            if ($stud['section_id']) {
                $secStmt = $this->db->prepare("
                    SELECT CONCAT('Grade ', g.grade_level, ' - ', s.name) 
                    FROM sections s JOIN grades g ON s.grade_id = g.id 
                    WHERE s.id = ?
                ");
                $secStmt->execute([$stud['section_id']]);
                $secName = $secStmt->fetchColumn() ?: "";
            }

            $results[] = [
                "id" => $stud['id'],
                "student_code" => $stud['student_code'],
                "full_name" => $stud['full_name'],
                "email" => $stud['email'],
                "school_name" => $stud['school_name'],
                "section_name" => $secName,
                "overall_average" => round($avg, 2)
            ];
        }

        return [
            "success" => true,
            "students" => $results
        ];
    }
}
