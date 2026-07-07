<?php
/**
 * HRFlowSn - Contract Model
 * Modèle pour la gestion des contrats
 */

require_once __DIR__ . '/Model.php';

class Contract extends Model {
    protected $table = 'contracts';
    
    /**
     * Compter les contrats par statut
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
     * Récupérer tous les contrats avec détails
     */
    public function getAllWithDetails() {
        $sql = "SELECT c.*, 
                CONCAT(e.first_name, ' ', e.last_name) as employee_name,
                e.employee_code
                FROM {$this->table} c
                LEFT JOIN employees e ON c.employee_id = e.id
                ORDER BY c.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer un contrat avec détails
     */
    public function getByIdWithDetails($id) {
        $sql = "SELECT c.*, 
                CONCAT(e.first_name, ' ', e.last_name) as employee_name,
                e.employee_code,
                d.name as department_name,
                p.name as position_name
                FROM {$this->table} c
                LEFT JOIN employees e ON c.employee_id = e.id
                LEFT JOIN departments d ON e.department_id = d.id
                LEFT JOIN positions p ON e.position_id = p.id
                WHERE c.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Obtenir les contrats d'un employé
     */
    public function getByEmployee($employeeId) {
        $sql = "SELECT * FROM {$this->table} WHERE employee_id = :employee_id ORDER BY start_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':employee_id', $employeeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer les contrats qui expirent bientôt (30 jours)
     */
    public function getExpiringSoon($days = 30) {
        $sql = "SELECT c.*, CONCAT(e.first_name, ' ', e.last_name) as employee_name
                FROM {$this->table} c
                LEFT JOIN employees e ON c.employee_id = e.id
                WHERE c.end_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL :days DAY)
                AND c.status = 'En cours'
                ORDER BY c.end_date ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
