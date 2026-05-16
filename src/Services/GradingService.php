<?php

namespace App\Services;

use PDO;

class GradingService {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Calculates the weighted average for a student in a specific subject for a term.
     */
    public function getStudentSubjectAverage($studentID, $subjectID, $termID) {
        $sql = "SELECT 
                    ge.score, 
                    a.max_score, 
                    at.weight 
                FROM grades_entries ge
                JOIN assessments a ON ge.assessment_id = a.id
                JOIN assessment_types at ON a.assessment_type_id = at.id
                JOIN teaching_assignments ta ON a.teaching_assignment_id = ta.id
                WHERE ge.student_id = ? 
                  AND ta.subject_id = ? 
                  AND a.term_id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$studentID, $subjectID, $termID]);
        $grades = $stmt->fetchAll();

        if (empty($grades)) return 0;

        $totalWeightedScore = 0;
        $totalWeight = 0;

        foreach ($grades as $grade) {
            // (Score / Max Score) * Weight * 100 (to get a percentage-based score)
            $weighted = ($grade['score'] / $grade['max_score']) * $grade['weight'];
            $totalWeightedScore += $weighted;
            $totalWeight += $grade['weight'];
        }

        // If weights don't sum to 1, normalize it or just return the sum
        // Usually in Ethiopia, the total is out of 100.
        return $totalWeightedScore * 100; 
    }

    /**
     * Calculates the overall average of a student across all subjects in a term.
     */
    public function getStudentOverallAverage($studentID, $termID) {
        // First get all subjects the student is enrolled in for this term
        $sql = "SELECT DISTINCT ta.subject_id 
                FROM teaching_assignments ta
                JOIN sections s ON ta.section_id = s.id
                JOIN students st ON st.section_id = s.id
                WHERE st.id = ? AND ta.academic_year_id = (
                    SELECT academic_year_id FROM terms WHERE id = ?
                )";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$studentID, $termID]);
        $subjects = $stmt->fetchAll();

        if (empty($subjects)) return 0;

        $totalAverages = 0;
        foreach ($subjects as $subject) {
            $totalAverages += $this->getStudentSubjectAverage($studentID, $subject['subject_id'], $termID);
        }

        return $totalAverages / count($subjects);
    }

    /**
     * Calculates section averages and ranks students within a section.
     */
    public function getSectionRankings($sectionID, $termID) {
        // Get all students in the section
        $stmt = $this->db->prepare("SELECT id, full_name, student_id FROM students WHERE section_id = ?");
        $stmt->execute([$sectionID]);
        $students = $stmt->fetchAll();

        $rankings = [];
        foreach ($students as $student) {
            $average = $this->getStudentOverallAverage($student['id'], $termID);
            $rankings[] = [
                'id' => $student['id'],
                'student_code' => $student['student_id'],
                'full_name' => $student['full_name'],
                'average' => round($average, 2)
            ];
        }

        // Sort by average descending
        usort($rankings, function($a, $b) {
            return $b['average'] <=> $a['average'];
        });

        // Assign ranks
        foreach ($rankings as $index => &$record) {
            $record['rank'] = $index + 1;
        }

        return $rankings;
    }

    /**
     * Get section average score
     */
    public function getSectionAverage($sectionID, $termID) {
        $rankings = $this->getSectionRankings($sectionID, $termID);
        if (empty($rankings)) return 0;

        $total = array_sum(array_column($rankings, 'average'));
        return round($total / count($rankings), 2);
    }
}
