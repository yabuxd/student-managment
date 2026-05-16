<?php

namespace App\Models;

use PDO;

class Student extends BaseModel {
    protected $table = "students";

    public function findBySchoolID($studentID) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE student_id = ?");
        $stmt->execute([$studentID]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO students (student_id, school_id, full_name, email, password_hash, enrollment_year, section_id) 
                VALUES (:student_id, :school_id, :full_name, :email, :password_hash, :enrollment_year, :section_id)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function updateSection($id, $sectionID) {
        $stmt = $this->db->prepare("UPDATE students SET section_id = ? WHERE id = ?");
        return $stmt->execute([$sectionID, $id]);
    }
}
