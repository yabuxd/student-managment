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
            $query = "INSERT INTO staff_users (username, password_hash, role, full_name, email) 
                      VALUES (:username, :password, 'director', :full_name, :email)";
            $stmt = $this->db->prepare($query);
            
            // In a real app, hash the password using password_hash()
            $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);

            $stmt->bindParam(":username", $data['username']);
            $stmt->bindParam(":password", $password_hash);
            $stmt->bindParam(":full_name", $data['full_name']);
            $stmt->bindParam(":email", $data['email']);

            if ($stmt->execute()) {
                $userId = $this->db->lastInsertId();
                return ["success" => true, "message" => "Registration successful", "user_id" => $userId];
            }
            return ["success" => false, "message" => "Registration failed."];
        } catch (\PDOException $e) {
            return ["success" => false, "message" => "Error: " . $e->getMessage()];
        }
    }

    public function login($data) {
        if (!isset($data['username'], $data['password'])) {
            return ["success" => false, "message" => "Missing username or password."];
        }

        try {
            $query = "SELECT id, password_hash, role, school_id FROM staff_users WHERE username = :username";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":username", $data['username']);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($data['password'], $row['password_hash'])) {
                    // Generate a mock token
                    $token = base64_encode(json_encode(["user_id" => $row['id'], "role" => $row['role'], "school_id" => $row['school_id']]));
                    return [
                        "success" => true, 
                        "message" => "Login successful", 
                        "token" => $token,
                        "school_id" => $row['school_id']
                    ];
                }
            }
            return ["success" => false, "message" => "Invalid credentials."];
        } catch (\PDOException $e) {
            return ["success" => false, "message" => "Error: " . $e->getMessage()];
        }
    }
}
