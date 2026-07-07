<?php
/**
 * HRFlowSn - Employee Model
 * Modèle pour la gestion des employés
 */

require_once __DIR__ . '/Model.php';

class Employee extends Model {
    protected $table = 'employees';
    
    /**
     * Récupérer les employés avec leurs détails
     */
    public function getAllWithDetails() {
        $sql = "SELECT e.*, 
                d.name as department_name, 
                p.name as position_name,
                u.email as user_email,
                r.name as role_name,
                CONCAT(m.first_name, ' ', m.last_name) as manager_name
                FROM {$this->table} e
                LEFT JOIN departments d ON e.department_id = d.id
                LEFT JOIN positions p ON e.position_id = p.id
                LEFT JOIN users u ON e.user_id = u.id
                LEFT JOIN roles r ON u.role_id = r.id
                LEFT JOIN employees m ON e.manager_id = m.id
                ORDER BY e.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Compter le nombre total d'employés
     */
    public function countTotal() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    /**
     * Compter les employés par statut
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
     * Compter les employés par département
     */
    public function countByDepartment() {
        $sql = "SELECT d.name as department, COUNT(e.id) as count
                FROM departments d
                LEFT JOIN employees e ON d.id = e.department_id
                GROUP BY d.id, d.name
                ORDER BY count DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer les employés récents
     */
    public function getRecent($limit = 5) {
        $sql = "SELECT e.*, d.name as department_name, p.name as position_name
                FROM {$this->table} e
                LEFT JOIN departments d ON e.department_id = d.id
                LEFT JOIN positions p ON e.position_id = p.id
                ORDER BY e.created_at DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
