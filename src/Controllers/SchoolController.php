<?php

namespace App\Controllers;

use PDO;

class SchoolController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createSchool($data, $directorId) {
        if (!isset($data['name'], $data['subdomain'])) {
            return ["success" => false, "message" => "Missing required fields."];
        }

        try {
            $this->db->beginTransaction();

            // 0. Fetch director's plan_id
            $planQuery = "SELECT plan_id FROM staff_users WHERE id = :director_id";
            $planStmt = $this->db->prepare($planQuery);
            $planStmt->bindParam(":director_id", $directorId);
            $planStmt->execute();
            $directorRecord = $planStmt->fetch(PDO::FETCH_ASSOC);
            $planId = $directorRecord ? $directorRecord['plan_id'] : null;

            // 1. Generate School Code
            $schoolCode = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $data['name']), 0, 3)) . rand(100, 999);

            // 2. Insert School
            $query = "INSERT INTO schools (name, school_code, subdomain, plan_id) VALUES (:name, :code, :subdomain, :plan_id)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":name", $data['name']);
            $stmt->bindParam(":code", $schoolCode);
            $stmt->bindParam(":subdomain", $data['subdomain']);
            $stmt->bindParam(":plan_id", $planId);
            $stmt->execute();

            $schoolId = $this->db->lastInsertId();

            // 3. Update Director's school_id
            $queryUpdate = "UPDATE staff_users SET school_id = :school_id WHERE id = :director_id";
            $stmtUpdate = $this->db->prepare($queryUpdate);
            $stmtUpdate->bindParam(":school_id", $schoolId);
            $stmtUpdate->bindParam(":director_id", $directorId);
            $stmtUpdate->execute();

            // 4. Physical Site Generation
            $subdomain = preg_replace('/[^a-zA-Z0-9-]/', '', strtolower($data['subdomain']));
            $sitePath = __DIR__ . "/../../../public/sites/" . $subdomain;
            
            if (!file_exists($sitePath)) {
                mkdir($sitePath, 0777, true);
            }

            // Determine template
            $templateName = isset($data['template']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', strtolower($data['template'])) : 'vibrant';
            $templatePath = __DIR__ . "/../../../templates/" . $templateName;

            if (is_dir($templatePath)) {
                $this->recursiveCopy($templatePath, $sitePath);
            } else {
                // Fallback
                file_put_contents($sitePath . "/index.php", "<?php echo '<h1>Welcome to " . htmlspecialchars($data['name']) . "</h1>'; ?>");
            }

            $this->db->commit();

            return [
                "success" => true, 
                "message" => "School created successfully", 
                "school_id" => $schoolId,
                "school_code" => $schoolCode
            ];
        } catch (\PDOException $e) {
            $this->db->rollBack();
            return ["success" => false, "message" => "Error: " . $e->getMessage()];
        }
    }

    public function getPlans() {
        try {
            // If plans table is empty, insert mock data
            $check = $this->db->query("SELECT COUNT(*) FROM plans")->fetchColumn();
            if ($check == 0) {
                $this->db->exec("INSERT INTO plans (name, price, max_students, max_teachers, features) VALUES 
                ('Starter', 49.99, 500, 20, 'Basic Features, Email Support'),
                ('Professional', 99.99, 2000, 100, 'Advanced Reporting, Priority Support'),
                ('Enterprise', 249.99, 10000, 500, 'Custom Integrations, 24/7 Support')");
            }

            $stmt = $this->db->query("SELECT * FROM plans");
            return ["success" => true, "plans" => $stmt->fetchAll(PDO::FETCH_ASSOC)];
        } catch (\PDOException $e) {
            return ["success" => false, "message" => "Error: " . $e->getMessage()];
        }
    }

    private function recursiveCopy($src, $dst) {
        $dir = opendir($src);
        @mkdir($dst, 0777, true);
        while (( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->recursiveCopy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}
