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

        // Read entire content to sanitize BOM and determine separators
        $content = file_get_contents($fileTmpPath);
        if ($content === false) {
            return ["success" => false, "message" => "Could not read file contents."];
        }

        // Strip UTF-8 BOM if present
        if (substr($content, 0, 3) === pack("CCC", 0xef, 0xbb, 0xbf)) {
            $content = substr($content, 3);
        }

        // Clean up carriage returns
        $content = str_replace("\r", "", $content);
        $lines = explode("\n", $content);
        $lines = array_filter(array_map('trim', $lines));

        if (empty($lines)) {
            return ["success" => false, "message" => "The uploaded CSV file is empty."];
        }

        // Determine separator: check first line
        $firstLine = $lines[0];
        $separator = ",";
        if (strpos($firstLine, ";") !== false && strpos($firstLine, ",") === false) {
            $separator = ";";
        } elseif (strpos($firstLine, "\t") !== false) {
            $separator = "\t";
        }

        // Parse header
        $header = str_getcsv($firstLine, $separator);
        $nameIdx = -1;
        $emailIdx = -1;

        foreach ($header as $idx => $colName) {
            $colClean = strtolower(trim(preg_replace('/[\x{FEFF}\x{200B}-\x{200D}]/u', '', $colName)));
            if (strpos($colClean, 'name') !== false || strpos($colClean, 'full') !== false) {
                $nameIdx = $idx;
            } elseif (strpos($colClean, 'email') !== false || strpos($colClean, 'mail') !== false) {
                $emailIdx = $idx;
            }
        }

        // Fallback if header columns aren't matched by name
        if ($nameIdx === -1) $nameIdx = 0;
        if ($emailIdx === -1) $emailIdx = 1;

        if (count($header) <= max($nameIdx, $emailIdx)) {
            return ["success" => false, "message" => "CSV format invalid. Ensure 'Full Name' and 'Email' columns exist."];
        }

        // Get School Code
        $stmt = $this->db->prepare("SELECT school_code FROM schools WHERE id = ?");
        $stmt->execute([$schoolId]);
        $schoolCode = $stmt->fetchColumn();

        if (!$schoolCode) {
            return ["success" => false, "message" => "Invalid school ID."];
        }

        $currentYear = date('y');
        $successCount = 0;
        $skippedCount = 0;
        $results = [];
        $skippedDetails = [];

        // Process rows
        for ($i = 1; $i < count($lines); $i++) {
            $row = str_getcsv($lines[$i], $separator);
            if (empty($row)) {
                continue;
            }

            $fullName = trim($row[$nameIdx] ?? '');
            $email = trim($row[$emailIdx] ?? '');

            if (empty($fullName) && empty($email)) {
                continue;
            }
            if (empty($fullName) || empty($email)) {
                $skippedCount++;
                $skippedDetails[] = ["name" => $fullName, "email" => $email, "reason" => "Missing name or email in row"];
                continue;
            }

            // Clean email
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $skippedCount++;
                $skippedDetails[] = ["name" => $fullName, "email" => $email, "reason" => "Invalid email format"];
                continue;
            }

            // Generate Password
            $password = bin2hex(random_bytes(4)); // 8 chars
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            if ($role === 'teacher') {
                // Check if email already exists in teachers or students
                $chk = $this->db->prepare("SELECT id FROM teachers WHERE email = ? UNION SELECT id FROM students WHERE email = ?");
                $chk->execute([$email, $email]);
                if ($chk->fetchColumn()) {
                    $skippedCount++;
                    $skippedDetails[] = ["name" => $fullName, "email" => $email, "reason" => "Email already registered"];
                    continue;
                }

                $nextNo = $this->getNextSequence($schoolId, 'teacher');
                $generatedId = "{$schoolCode}T" . str_pad($nextNo, 4, '0', STR_PAD_LEFT) . "/{$currentYear}";
                
                $stmt = $this->db->prepare("INSERT INTO teachers (teacher_id_code, school_id, full_name, email, password_hash, status, must_change_password) VALUES (?, ?, ?, ?, ?, 'active', 0)");
                try {
                    $stmt->execute([$generatedId, $schoolId, $fullName, $email, $passwordHash]);
                    $results[] = ["full_name" => $fullName, "email" => $email, "id_code" => $generatedId, "password" => $password];
                    $successCount++;
                } catch (\PDOException $e) {
                    $skippedCount++;
                    $skippedDetails[] = ["name" => $fullName, "email" => $email, "reason" => "Database insertion error: " . $e->getMessage()];
                }
            } else if ($role === 'student') {
                // Check email
                $chk = $this->db->prepare("SELECT id FROM teachers WHERE email = ? UNION SELECT id FROM students WHERE email = ?");
                $chk->execute([$email, $email]);
                if ($chk->fetchColumn()) {
                    $skippedCount++;
                    $skippedDetails[] = ["name" => $fullName, "email" => $email, "reason" => "Email already registered"];
                    continue;
                }

                $nextNo = $this->getNextSequence($schoolId, 'student');
                $generatedId = "{$schoolCode}" . str_pad($nextNo, 4, '0', STR_PAD_LEFT) . "/{$currentYear}";
                $enrollmentYear = $currentYear;

                $stmt = $this->db->prepare("INSERT INTO students (student_id, school_id, full_name, email, password_hash, enrollment_year, status) VALUES (?, ?, ?, ?, ?, ?, 'active')");
                try {
                    $stmt->execute([$generatedId, $schoolId, $fullName, $email, $passwordHash, $enrollmentYear]);
                    $results[] = ["full_name" => $fullName, "email" => $email, "id_code" => $generatedId, "password" => $password];
                    $successCount++;
                } catch (\PDOException $e) {
                    $skippedCount++;
                    $skippedDetails[] = ["name" => $fullName, "email" => $email, "reason" => "Database insertion error: " . $e->getMessage()];
                }
            }
        }
        
        // Format as CSV to return
        $csvOutput = "Full Name,Email,Generated ID,Temporary Password\n";
        foreach ($results as $row) {
            $csvOutput .= "\"{$row['full_name']}\",\"{$row['email']}\",\"{$row['id_code']}\",\"{$row['password']}\"\n";
        }

        return [
            "success" => true,
            "csv" => $csvOutput,
            "count" => $successCount,
            "skipped" => $skippedCount,
            "skipped_details" => $skippedDetails,
            "results" => $results
        ];
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
