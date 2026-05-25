<?php

namespace App\Controllers;

use PDO;

class CommunicationController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getMessages($role, $userId, $schoolId) {
        // Query to list all messages involving this user (either as sender or receiver)
        // We will enrich it with sender and receiver names
        $query = "
            SELECT c.*,
                   CASE 
                       WHEN c.sender_role = 'teacher' THEN t.full_name
                       WHEN c.sender_role = 'director' THEN d.full_name
                       WHEN c.sender_role = 'parent' THEN p.full_name
                   END as sender_name,
                   CASE 
                       WHEN c.receiver_role = 'teacher' THEN t2.full_name
                       WHEN c.receiver_role = 'director' THEN d2.full_name
                       WHEN c.receiver_role = 'parent' THEN p2.full_name
                   END as receiver_name
            FROM communications c
            LEFT JOIN teachers t ON c.sender_role = 'teacher' AND c.sender_id = t.id
            LEFT JOIN staff_users d ON c.sender_role = 'director' AND c.sender_id = d.id
            LEFT JOIN parents p ON c.sender_role = 'parent' AND c.sender_id = p.id
            LEFT JOIN teachers t2 ON c.receiver_role = 'teacher' AND c.receiver_id = t2.id
            LEFT JOIN staff_users d2 ON c.receiver_role = 'director' AND c.receiver_id = d2.id
            LEFT JOIN parents p2 ON c.receiver_role = 'parent' AND c.receiver_id = p2.id
            WHERE c.school_id = :school_id
              AND (
                  (c.sender_role = :role AND c.sender_id = :user_id)
                  OR
                  (c.receiver_role = :role AND c.receiver_id = :user_id)
              )
            ORDER BY c.created_at ASC
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":school_id", $schoolId);
        $stmt->bindParam(":role", $role);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch contacts list depending on the role to send new messages
        $contacts = [];
        if ($role === 'teacher') {
            // Teachers talk to parents of students in their assigned sections
            $stmt = $this->db->prepare("
                SELECT DISTINCT p.id, p.full_name, 'parent' as role
                FROM parents p
                JOIN parent_student ps ON p.id = ps.parent_id
                JOIN students s ON ps.student_id = s.id
                JOIN teaching_assignments ta ON s.section_id = ta.section_id
                WHERE ta.teacher_id = ?
            ");
            $stmt->execute([$userId]);
            $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } elseif ($role === 'parent') {
            // Parents talk to teachers of their children's classes
            $stmt = $this->db->prepare("
                SELECT DISTINCT t.id, t.full_name, 'teacher' as role
                FROM teachers t
                JOIN teaching_assignments ta ON t.id = ta.teacher_id
                JOIN students s ON ta.section_id = s.section_id
                JOIN parent_student ps ON s.id = ps.student_id
                WHERE ps.parent_id = ?
            ");
            $stmt->execute([$userId]);
            $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } elseif ($role === 'director') {
            // Directors can talk to all teachers and parents in the school
            $stmt = $this->db->prepare("SELECT id, full_name, 'teacher' as role FROM teachers WHERE school_id = ?");
            $stmt->execute([$schoolId]);
            $teachersList = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = $this->db->prepare("
                SELECT DISTINCT p.id, p.full_name, 'parent' as role
                FROM parents p
                JOIN parent_student ps ON p.id = ps.parent_id
                JOIN students s ON ps.student_id = s.id
                WHERE s.school_id = ?
            ");
            $stmt->execute([$schoolId]);
            $parentsList = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $contacts = array_merge($teachersList, $parentsList);
        }

        return [
            "success" => true,
            "messages" => $messages,
            "contacts" => $contacts
        ];
    }

    public function sendMessage($data, $schoolId, $senderRole, $senderId) {
        if (!isset($data['receiver_role'], $data['receiver_id'], $data['message'])) {
            return ["success" => false, "message" => "Incomplete message details."];
        }

        $stmt = $this->db->prepare("
            INSERT INTO communications (school_id, sender_role, sender_id, receiver_role, receiver_id, message)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        if ($stmt->execute([$schoolId, $senderRole, $senderId, $data['receiver_role'], $data['receiver_id'], trim($data['message'])])) {
            return ["success" => true, "message" => "Message sent successfully."];
        }
        return ["success" => false, "message" => "Failed to send message."];
    }
}
