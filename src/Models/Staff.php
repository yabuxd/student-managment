<?php

namespace App\Models;

class Staff extends BaseModel {
    protected $table = "staff_users";

    public function create($data) {
        $sql = "INSERT INTO staff_users (username, password_hash, role, school_id, full_name, email) 
                VALUES (:username, :password_hash, :role, :school_id, :full_name, :email)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
}
