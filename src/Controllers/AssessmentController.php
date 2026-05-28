<?php

namespace App\Controllers;

use App\Services\GradingService;
use App\Models\Student;

class AssessmentController {
    private $db;
    private $gradingService;

    public function __construct($db) {
        $this->db = $db;
        $this->gradingService = new GradingService($db);
    }

  
    public function recordScore($assessmentID, $studentID, $score) {
        $stmt = $this->db->prepare("INSERT INTO grades_entries (assessment_id, student_id, score) VALUES (?, ?, ?)");
        return $stmt->execute([$assessmentID, $studentID, $score]);
    }

    /*  Gets a comprehensive report for a student in a specific term. */
    public function getStudentReport($studentID, $termID) {
        // Get all subjects
        $sql = "SELECT DISTINCT s.id, s.name 
                FROM subjects s
                JOIN teaching_assignments ta ON s.id = ta.subject_id
                JOIN students st ON st.section_id = ta.section_id
                WHERE st.id = ? AND ta.academic_year_id = (SELECT academic_year_id FROM terms WHERE id = ?)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$studentID, $termID]);
        $subjects = $stmt->fetchAll();

        $report = [
            'subjects' => [],
            'overall_average' => $this->gradingService->getStudentOverallAverage($studentID, $termID)
        ];

        foreach ($subjects as $subject) {
            $report['subjects'][] = [
                'subject_name' => $subject['name'],
                'average' => $this->gradingService->getStudentSubjectAverage($studentID, $subject['id'], $termID)
            ];
        }

        return $report;
    }

    /* Gets section-wide performance and rankings. */
    public function getSectionPerformance($sectionID, $termID) {
        return [
            'section_average' => $this->gradingService->getSectionAverage($sectionID, $termID),
            'rankings' => $this->gradingService->getSectionRankings($sectionID, $termID)
        ];
    }
}
