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

            // 0. Fetch director's plan limits
            $planQuery = "SELECT p.id as plan_id, p.max_schools FROM staff_users u JOIN plans p ON u.plan_id = p.id WHERE u.id = :director_id";
            $planStmt = $this->db->prepare($planQuery);
            $planStmt->bindParam(":director_id", $directorId);
            $planStmt->execute();
            $directorRecord = $planStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$directorRecord) {
                return ["success" => false, "message" => "Invalid director or no plan assigned."];
            }
            $planId = $directorRecord['plan_id'];
            $maxSchools = (int)$directorRecord['max_schools'];

            // Check current school count
            $countQuery = "SELECT COUNT(*) FROM schools WHERE director_id = :director_id";
            $countStmt = $this->db->prepare($countQuery);
            $countStmt->bindParam(":director_id", $directorId);
            $countStmt->execute();
            $currentSchools = (int)$countStmt->fetchColumn();

            if ($currentSchools >= $maxSchools) {
                return ["success" => false, "message" => "Plan Limit Reached: You can only create up to " . $maxSchools . " school(s) on your current plan."];
            }

            // 1. Generate School Code
            $schoolCode = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $data['name']), 0, 3)) . rand(100, 999);

            // 2. Insert School
            $query = "INSERT INTO schools (name, school_code, subdomain, plan_id, director_id) VALUES (:name, :code, :subdomain, :plan_id, :director_id)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":name", $data['name']);
            $stmt->bindParam(":code", $schoolCode);
            $stmt->bindParam(":subdomain", $data['subdomain']);
            $stmt->bindParam(":plan_id", $planId);
            $stmt->bindParam(":director_id", $directorId);
            $stmt->execute();

            $schoolId = $this->db->lastInsertId();

            // 3. Update Director's school_id
            $queryUpdate = "UPDATE staff_users SET school_id = :school_id WHERE id = :director_id";
            $stmtUpdate = $this->db->prepare($queryUpdate);
            $stmtUpdate->bindParam(":school_id", $schoolId);
            $stmtUpdate->bindParam(":director_id", $directorId);
            $stmtUpdate->execute();

            // 4. Insert default site content
            $templateName = isset($data['template']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', strtolower($data['template'])) : 'vibrant';
            $themePath = isset($data['theme_path']) ? $data['theme_path'] : 'assets/css/themes/theme1.css';
            $heroTitle = "Welcome to " . $data['name'];
            $heroSubtitle = "School management software that actually has a personality. Fast, loud, and built for the modern institution.";
            
            $querySiteContent = "INSERT INTO school_site_content (school_id, template_name, theme_path, hero_title, hero_subtitle) VALUES (:school_id, :template_name, :theme_path, :hero_title, :hero_subtitle)";
            $stmtSiteContent = $this->db->prepare($querySiteContent);
            $stmtSiteContent->bindParam(":school_id", $schoolId);
            $stmtSiteContent->bindParam(":template_name", $templateName);
            $stmtSiteContent->bindParam(":theme_path", $themePath);
            $stmtSiteContent->bindParam(":hero_title", $heroTitle);
            $stmtSiteContent->bindParam(":hero_subtitle", $heroSubtitle);
            $stmtSiteContent->execute();

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
            $stmt = $this->db->query("SELECT * FROM plans");
            return ["success" => true, "plans" => $stmt->fetchAll(PDO::FETCH_ASSOC)];
        } catch (\PDOException $e) {
            return ["success" => false, "message" => "Error: " . $e->getMessage()];
        }
    }

    public function getSchools($directorId) {
        if (!$directorId) {
            return ["success" => false, "message" => "Unauthorized access."];
        }

        try {
            $query = "SELECT s.id, s.name, s.school_code, s.subdomain, s.plan_id, p.name as plan_name 
                      FROM schools s 
                      LEFT JOIN plans p ON s.plan_id = p.id 
                      WHERE s.director_id = :director_id
                      ORDER BY s.created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":director_id", $directorId);
            $stmt->execute();
            $schools = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch the director's assigned plan details
            $planQuery = "SELECT p.* FROM staff_users u JOIN plans p ON u.plan_id = p.id WHERE u.id = :director_id";
            $planStmt = $this->db->prepare($planQuery);
            $planStmt->bindParam(":director_id", $directorId);
            $planStmt->execute();
            $currentPlan = $planStmt->fetch(PDO::FETCH_ASSOC);

            return [
                "success" => true, 
                "schools" => $schools,
                "plan" => $currentPlan
            ];
        } catch (\PDOException $e) {
            return ["success" => false, "message" => "Error: " . $e->getMessage()];
        }
    }

    public function saveCustomPage($data) {
        if (!isset($data['subdomain'], $data['path'], $data['html'])) {
            return ["success" => false, "message" => "Missing required fields."];
        }
        
        try {
            // Get school_id from subdomain
            $stmt = $this->db->prepare("SELECT id FROM schools WHERE subdomain = :subdomain");
            $stmt->bindParam(":subdomain", $data['subdomain']);
            $stmt->execute();
            $schoolId = $stmt->fetchColumn();
            
            if (!$schoolId) return ["success" => false, "message" => "School not found."];
            
            // Get current custom_pages JSON
            $stmtContent = $this->db->prepare("SELECT custom_pages FROM school_site_content WHERE school_id = :school_id");
            $stmtContent->bindParam(":school_id", $schoolId);
            $stmtContent->execute();
            $customPagesJson = $stmtContent->fetchColumn();
            
            $customPages = $customPagesJson ? json_decode($customPagesJson, true) : [];
            $customPages[$data['path']] = $data['html'];
            
            // Update
            $updateJson = json_encode($customPages);
            $updateStmt = $this->db->prepare("UPDATE school_site_content SET custom_pages = :custom_pages WHERE school_id = :school_id");
            $updateStmt->bindParam(":custom_pages", $updateJson);
            $updateStmt->bindParam(":school_id", $schoolId);
            $updateStmt->execute();
            
            return ["success" => true, "message" => "Page saved successfully."];
        } catch (\PDOException $e) {
            return ["success" => false, "message" => "Error: " . $e->getMessage()];
        }
    }
    public function saveSettings($data) {
        if (!isset($data['subdomain'])) {
            return ["success" => false, "message" => "Missing required fields."];
        }

        try {
            // Get school_id from subdomain
            $stmt = $this->db->prepare("SELECT id FROM schools WHERE subdomain = :subdomain");
            $stmt->bindParam(":subdomain", $data['subdomain']);
            $stmt->execute();
            $schoolId = $stmt->fetchColumn();
            
            if (!$schoolId) return ["success" => false, "message" => "School not found."];

            $updateFields = [];
            $params = [":school_id" => $schoolId];

            if (isset($data['theme_path'])) {
                $updateFields[] = "theme_path = :theme_path";
                $params[":theme_path"] = $data['theme_path'];
            }
            if (isset($data['typography'])) {
                $updateFields[] = "typography = :typography";
                $params[":typography"] = $data['typography'];
            }

            if (empty($updateFields)) {
                return ["success" => true, "message" => "Nothing to update."];
            }

            $query = "UPDATE school_site_content SET " . implode(", ", $updateFields) . " WHERE school_id = :school_id";
            $updateStmt = $this->db->prepare($query);
            $updateStmt->execute($params);

            return ["success" => true, "message" => "Settings saved successfully."];
        } catch (\PDOException $e) {
            return ["success" => false, "message" => "Error: " . $e->getMessage()];
        }
    }
}
