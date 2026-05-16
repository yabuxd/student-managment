<?php

namespace App\Models;

class Teacher extends BaseModel {
    protected $table = "teachers";

    public function create($data) {
        $sql = "INSERT INTO teachers (teacher_id_code, school_id, full_name, email, password_hash, specialization) 
                VALUES (:teacher_id_code, :school_id, :full_name, :email, :password_hash, :specialization)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
}
