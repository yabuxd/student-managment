<?php

namespace App\Controllers;

use PDO;

class AuthController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function register($data) {
        if (!isset($data['username'], $data['password'], $data['full_name'], $data['email'])) {
            return ["success" => false, "message" => "Missing required fields."];
        }

        try {
            $query = "INSERT INTO staff_users (username, password_hash, role, full_name, email, plan_id, must_change_password) 
                      VALUES (:username, :password, 'director', :full_name, :email, :plan_id, 0)";
            $stmt = $this->db->prepare($query);
            
            $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
            $plan_id = isset($data['plan_id']) ? $data['plan_id'] : null;

            $stmt->bindParam(":username", $data['username']);
            $stmt->bindParam(":password", $password_hash);
            $stmt->bindParam(":full_name", $data['full_name']);
            $stmt->bindParam(":email", $data['email']);
            $stmt->bindParam(":plan_id", $plan_id);

            if ($stmt->execute()) {
                $userId = $this->db->lastInsertId();
                return ["success" => true, "message" => "Registration successful", "user_id" => $userId];
            }
            return ["success" => false, "message" => "Registration failed."];
        } catch (\PDOException $e) {
            return ["success" => false, "message" => "Error: " . $e->getMessage()];
        }
    }

    public function login($data, $currentSchoolId = null) {
        if (!$currentSchoolId && !isset($data['username'], $data['password']) ) {
            return ["success" => false, "message" => "Missing username or password."]; 
        }

        if ($currentSchoolId && !isset($data['username'], $data['password'], $data['role'])) {
            return ["success" => false, "message" => "Missing username, password, or role."];
        } 
        $username = trim($data['username']);
        $password = $data['password'];
        $role = $data['role'] ?? 'director';

        try {
            if ($role === 'director') {
                $query = "SELECT id, password_hash, full_name, role, school_id FROM staff_users 
                          WHERE username = :username AND role = 'director'";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(":username", $username);
                $stmt->execute();

                $school_query = "SELECT director_id FROM schools WHERE id = :school_id";
                $school_stmt = $this->db->prepare($school_query);
                $school_stmt->bindParam(":school_id", $currentSchoolId);
                $school_stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($currentSchoolId && $row['id'] != $school_stmt->fetchColumn()) {
                        return ["success" => false, "message" => "Unauthorized: You do not belong to this school subdomain."];
                    }
                    if (password_verify($password, $row['password_hash'])) {
                        $token = base64_encode(json_encode(["user_id" => $row['id'], "role" => 'director', "school_id" => $row['school_id'], "name" => $row['full_name']]));
                        return [
                            "success" => true,
                            "message" => "Login successful",
                            "token" => $token,
                            "user_id" => $row['id'],
                            "user_name" => $row['full_name'],
                            "school_id" => $row['school_id']
                        ];
                    }
                }
            } elseif ($role === 'teacher') {
                // Support login by email or teacher code
                $query = "SELECT id, teacher_id_code, full_name, password_hash, school_id FROM teachers 
                          WHERE (email = :username OR teacher_id_code = :username) AND status = 'active'";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(":username", $username);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($currentSchoolId && $row['school_id'] != $currentSchoolId) {
                        return ["success" => false, "message" => "Unauthorized: You do not belong to this school subdomain."];
                    }
                    if (password_verify($password, $row['password_hash'])) {
                        $token = base64_encode(json_encode(["user_id" => $row['id'], "role" => 'teacher', "school_id" => $row['school_id'], "name" => $row['full_name']]));
                        return [
                            "success" => true,
                            "message" => "Login successful",
                            "token" => $token,
                            "user_id" => $row['id'],
                            "user_name" => $row['full_name'],
                            "school_id" => $row['school_id']
                        ];
                    }
                }
            } elseif ($role === 'student') {
                // Support login by email or student ID
                $query = "SELECT id, student_id, full_name, password_hash, school_id, section_id FROM students 
                          WHERE (email = :username OR student_id = :username) AND status = 'active'";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(":username", $username);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($currentSchoolId && $row['school_id'] != $currentSchoolId) {
                        return ["success" => false, "message" => "Unauthorized: You do not belong to this school subdomain."];
                    }
                    if (password_verify($password, $row['password_hash'])) {
                        $token = base64_encode(json_encode(["user_id" => $row['id'], "role" => 'student', "school_id" => $row['school_id'], "name" => $row['full_name']]));
                        return [
                            "success" => true,
                            "message" => "Login successful",
                            "token" => $token,
                            "user_id" => $row['id'],
                            "user_name" => $row['full_name'],
                            "school_id" => $row['school_id'],
                            "section_id" => $row['section_id']
                        ];
                    }
                }
            } elseif ($role === 'parent') {
                // Parent login by email
                $query = "SELECT id, full_name, password_hash FROM parents WHERE email = :username";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(":username", $username);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if (password_verify($password, $row['password_hash'])) {
                        // For parent, check if they have at least one student in this school
                        if ($currentSchoolId) {
                            $checkChild = $this->db->prepare("
                                SELECT COUNT(*) FROM parent_student ps
                                JOIN students s ON ps.student_id = s.id
                                WHERE ps.parent_id = ? AND s.school_id = ?
                            ");
                            $checkChild->execute([$row['id'], $currentSchoolId]);
                            if ($checkChild->fetchColumn() == 0) {
                                return ["success" => false, "message" => "Unauthorized: You do not have children registered in this school."];
                            }
                        }
                        
                        $token = base64_encode(json_encode(["user_id" => $row['id'], "role" => 'parent', "school_id" => $currentSchoolId, "name" => $row['full_name']]));
                        return [
                            "success" => true,
                            "message" => "Login successful",
                            "token" => $token,
                            "user_id" => $row['id'],
                            "user_name" => $row['full_name'],
                            "school_id" => $currentSchoolId
                        ];
                    }
                }
            }

            return ["success" => false, "message" => "Invalid credentials."];
        } catch (\PDOException $e) {
            return ["success" => false, "message" => "Error: " . $e->getMessage()];
        }
    }
}
