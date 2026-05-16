<?php

namespace App\Controllers;

use PDO;

class RegistrationController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function processCsv($fileTmpPath, $role, $schoolId) {
        if (!file_exists($fileTmpPath)) {
            return ["success" => false, "message" => "File not found."];
        }

        $results = [];
        if (($handle = fopen($fileTmpPath, "r")) !== FALSE) {
            $header = fgetcsv($handle, 1000, ","); // Skip header
            
            // Get School Code
            $stmt = $this->db->prepare("SELECT school_code FROM schools WHERE id = ?");
            $stmt->execute([$schoolId]);
            $schoolCode = $stmt->fetchColumn();

            if (!$schoolCode) {
                return ["success" => false, "message" => "Invalid school ID."];
            }

            $currentYear = date('Y');

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $fullName = $data[0] ?? '';
                $email = $data[1] ?? '';
                
                if (empty($fullName) || empty($email)) continue;

                // Generate Password
                $password = bin2hex(random_bytes(4)); // 8 chars
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                // Generate ID based on Role
                if ($role === 'teacher') {
                    $nextNo = $this->getNextSequence($schoolId, 'teacher');
                    $generatedId = "{$schoolCode}T" . str_pad($nextNo, 4, '0', STR_PAD_LEFT) . "/{$currentYear}";
                    
                    $stmt = $this->db->prepare("INSERT INTO teachers (teacher_id_code, school_id, full_name, email, password_hash) VALUES (?, ?, ?, ?, ?)");
                    try {
                        $stmt->execute([$generatedId, $schoolId, $fullName, $email, $passwordHash]);
                        $results[] = ["full_name" => $fullName, "email" => $email, "id_code" => $generatedId, "password" => $password];
                    } catch (\PDOException $e) {
                        // Handle duplicates
                    }
                } else if ($role === 'student') {
                    $nextNo = $this->getNextSequence($schoolId, 'student');
                    $generatedId = "{$schoolCode}" . str_pad($nextNo, 4, '0', STR_PAD_LEFT) . "/{$currentYear}";
                    $enrollmentYear = $currentYear;

                    $stmt = $this->db->prepare("INSERT INTO students (student_id, school_id, full_name, email, password_hash, enrollment_year) VALUES (?, ?, ?, ?, ?, ?)");
                    try {
                        $stmt->execute([$generatedId, $schoolId, $fullName, $email, $passwordHash, $enrollmentYear]);
                        $results[] = ["full_name" => $fullName, "email" => $email, "id_code" => $generatedId, "password" => $password];
                    } catch (\PDOException $e) {
                        // Handle duplicates
                    }
                }
            }
            fclose($handle);
            
            // Format as CSV to return
            $csvOutput = "Full Name,Email,Generated ID,Temporary Password\n";
            foreach ($results as $row) {
                $csvOutput .= "\"{$row['full_name']}\",\"{$row['email']}\",\"{$row['id_code']}\",\"{$row['password']}\"\n";
            }

            return ["success" => true, "csv" => $csvOutput, "count" => count($results)];
        }

        return ["success" => false, "message" => "Could not read file."];
    }

    private function getNextSequence($schoolId, $type) {
        $column = $type === 'teacher' ? 'next_teacher_no' : 'next_student_no';
        
        // Ensure row exists
        $stmt = $this->db->prepare("SELECT $column FROM school_sequences WHERE school_id = ?");
        $stmt->execute([$schoolId]);
        $val = $stmt->fetchColumn();

        if ($val === false) {
            $this->db->prepare("INSERT INTO school_sequences (school_id, next_student_no, next_teacher_no) VALUES (?, 1, 1)")->execute([$schoolId]);
            $val = 1;
        }

        // Increment
        $this->db->prepare("UPDATE school_sequences SET $column = $column + 1 WHERE school_id = ?")->execute([$schoolId]);

        return $val;
    }
}
