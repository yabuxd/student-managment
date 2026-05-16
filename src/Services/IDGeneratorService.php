<?php

namespace App\Services;

use PDO;

class IDGeneratorService {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Generates a student ID: {SCHOOL_CODE}{NNNN}/{YEAR}
     */
    public function generateStudentID($schoolID, $schoolCode, $year) {
        $this->db->beginTransaction();
        try {
            // Lock the sequence row for this school
            $stmt = $this->db->prepare("SELECT next_student_no FROM school_sequences WHERE school_id = ? FOR UPDATE");
            $stmt->execute([$schoolID]);
            $sequence = $stmt->fetch();

            if (!$sequence) {
                // Initialize sequence if not exists
                $stmt = $this->db->prepare("INSERT INTO school_sequences (school_id, next_student_no) VALUES (?, 2)");
                $stmt->execute([$schoolID]);
                $nextNo = 1;
            } else {
                $nextNo = $sequence['next_student_no'];
                $stmt = $this->db->prepare("UPDATE school_sequences SET next_student_no = next_student_no + 1 WHERE school_id = ?");
                $stmt->execute([$schoolID]);
            }

            $this->db->commit();

            $formattedNo = str_pad($nextNo, 4, '0', STR_PAD_LEFT);
            return "{$schoolCode}{$formattedNo}/{$year}";

        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Generates a teacher ID: {SCHOOL_CODE}T{NNNN}/{YEAR}
     */
    public function generateTeacherID($schoolID, $schoolCode, $year) {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("SELECT next_teacher_no FROM school_sequences WHERE school_id = ? FOR UPDATE");
            $stmt->execute([$schoolID]);
            $sequence = $stmt->fetch();

            if (!$sequence) {
                $stmt = $this->db->prepare("INSERT INTO school_sequences (school_id, next_teacher_no) VALUES (?, 2)");
                $stmt->execute([$schoolID]);
                $nextNo = 1;
            } else {
                $nextNo = $sequence['next_teacher_no'];
                $stmt = $this->db->prepare("UPDATE school_sequences SET next_teacher_no = next_teacher_no + 1 WHERE school_id = ?");
                $stmt->execute([$schoolID]);
            }

            $this->db->commit();

            $formattedNo = str_pad($nextNo, 4, '0', STR_PAD_LEFT);
            return "{$schoolCode}T{$formattedNo}/{$year}";

        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
