<?php
/**
 * HRFlowSn - LeaveRequest Model
 * Modèle pour la gestion des demandes de congé
 */

require_once __DIR__ . '/Model.php';

class LeaveRequest extends Model {
    protected $table = 'leave_requests';
    
    /**
     * Compter les demandes par statut
     */
    public function countByStatus($status) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE status = :status";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    /**
     * Récupérer toutes les demandes avec détails
     */
    public function getAllWithDetails() {
        $sql = "SELECT lr.*, 
                lt.name as leave_type_name,
                lt.allowed_days,
                CONCAT(e.first_name, ' ', e.last_name) as employee_name,
                e.employee_code
                FROM {$this->table} lr
                LEFT JOIN leave_types lt ON lr.leave_type_id = lt.id
                LEFT JOIN employees e ON lr.employee_id = e.id
                ORDER BY lr.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer une demande avec détails
     */
    public function getByIdWithDetails($id) {
        $sql = "SELECT lr.*, 
                lt.name as leave_type_name,
                lt.allowed_days,
                CONCAT(e.first_name, ' ', e.last_name) as employee_name,
                e.employee_code,
                d.name as department_name
                FROM {$this->table} lr
                LEFT JOIN leave_types lt ON lr.leave_type_id = lt.id
                LEFT JOIN employees e ON lr.employee_id = e.id
                LEFT JOIN departments d ON e.department_id = d.id
                WHERE lr.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Récupérer les demandes récentes
     */
    public function getRecent($limit = 5) {
        $sql = "SELECT lr.*, lt.name as leave_type_name, 
                CONCAT(e.first_name, ' ', e.last_name) as employee_name
                FROM {$this->table} lr
                LEFT JOIN leave_types lt ON lr.leave_type_id = lt.id
                LEFT JOIN employees e ON lr.employee_id = e.id
                ORDER BY lr.created_at DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Approuver une demande de congé
     */
    public function approve($id) {
        $sql = "UPDATE {$this->table} SET status = 'Approved' WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * Rejeter une demande de congé
     */
    public function reject($id) {
        $sql = "UPDATE {$this->table} SET status = 'Rejected' WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * Annuler une demande de congé
     */
    public function cancel($id) {
        $sql = "UPDATE {$this->table} SET status = 'Cancelled' WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * Obtenir les demandes d'un employé spécifique
     */
    public function getByEmployee($employeeId) {
        $sql = "SELECT lr.*, lt.name as leave_type_name, 
                       e.first_name, e.last_name, e.employee_code 
                FROM {$this->table} lr 
                JOIN leave_types lt ON lr.leave_type_id = lt.id 
                JOIN employees e ON lr.employee_id = e.id 
                WHERE lr.employee_id = :employee_id 
                ORDER BY lr.created_at DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':employee_id', $employeeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
