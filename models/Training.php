<?php
/**
 * HRFlowSn - Training Model
 * Modèle pour la gestion des formations
 */

require_once __DIR__ . '/Model.php';

class Training extends Model {
    protected $table = 'trainings';
    
    /**
     * Récupérer toutes les formations avec détails
     */
    public function getAllWithDetails() {
        $sql = "SELECT t.*, 
                (SELECT COUNT(*) FROM training_participants tp WHERE tp.training_id = t.id) as participant_count
                FROM {$this->table} t
                ORDER BY t.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer une formation avec détails
     */
    public function getByIdWithDetails($id) {
        $sql = "SELECT t.*, 
                (SELECT COUNT(*) FROM training_participants tp WHERE tp.training_id = t.id) as participant_count
                FROM {$this->table} t
                WHERE t.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Récupérer les participants d'une formation
     */
    public function getParticipants($trainingId) {
        $sql = "SELECT tp.*, 
                CONCAT(e.first_name, ' ', e.last_name) as employee_name,
                e.employee_code,
                d.name as department_name
                FROM training_participants tp
                LEFT JOIN employees e ON tp.employee_id = e.id
                LEFT JOIN departments d ON e.department_id = d.id
                WHERE tp.training_id = :training_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':training_id', $trainingId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Ajouter un participant à une formation
     */
    public function addParticipant($trainingId, $employeeId) {
        $sql = "INSERT INTO training_participants (training_id, employee_id) 
                VALUES (:training_id, :employee_id)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':training_id', $trainingId, PDO::PARAM_INT);
        $stmt->bindParam(':employee_id', $employeeId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Supprimer un participant d'une formation
     */
    public function removeParticipant($trainingId, $employeeId) {
        $sql = "DELETE FROM training_participants 
                WHERE training_id = :training_id AND employee_id = :employee_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':training_id', $trainingId, PDO::PARAM_INT);
        $stmt->bindParam(':employee_id', $employeeId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Marquer la présence d'un participant
     */
    public function markAttendance($trainingId, $employeeId, $attended) {
        $sql = "UPDATE training_participants 
                SET attendance = :attendance 
                WHERE training_id = :training_id AND employee_id = :employee_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':attendance', $attended, PDO::PARAM_BOOL);
        $stmt->bindParam(':training_id', $trainingId, PDO::PARAM_INT);
        $stmt->bindParam(':employee_id', $employeeId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Obtenir les formations d'un employé
     */
    public function getByEmployee($employeeId) {
        $sql = "SELECT t.* 
                FROM {$this->table} t 
                JOIN training_participants tp ON t.id = tp.training_id 
                WHERE tp.employee_id = :employee_id 
                ORDER BY t.start_date DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':employee_id', $employeeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
