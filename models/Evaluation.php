<?php
/**
 * HRFlowSn - Evaluation Model
 * Modèle pour la gestion des évaluations
 */

require_once __DIR__ . '/Model.php';

class Evaluation extends Model {
    protected $table = 'evaluations';
    
    /**
     * Récupérer toutes les évaluations avec détails
     */
    public function getAllWithDetails() {
        $sql = "SELECT e.*, 
                CONCAT(emp.first_name, ' ', emp.last_name) as employee_name,
                emp.employee_code,
                d.name as department_name,
                p.name as position_name
                FROM {$this->table} e
                LEFT JOIN employees emp ON e.employee_id = emp.id
                LEFT JOIN departments d ON emp.department_id = d.id
                LEFT JOIN positions p ON emp.position_id = p.id
                ORDER BY e.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer une évaluation avec détails
     */
    public function getByIdWithDetails($id) {
        $sql = "SELECT e.*, 
                CONCAT(emp.first_name, ' ', emp.last_name) as employee_name,
                emp.employee_code,
                d.name as department_name,
                p.name as position_name
                FROM {$this->table} e
                LEFT JOIN employees emp ON e.employee_id = emp.id
                LEFT JOIN departments d ON emp.department_id = d.id
                LEFT JOIN positions p ON emp.position_id = p.id
                WHERE e.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Récupérer les évaluations d'un employé
     */
    public function getByEmployee($employeeId) {
        $sql = "SELECT * FROM {$this->table} WHERE employee_id = :employee_id ORDER BY evaluation_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':employee_id', $employeeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Calculer la moyenne des scores d'un employé
     */
    public function getAverageScore($employeeId) {
        $sql = "SELECT AVG(score) as average FROM {$this->table} WHERE employee_id = :employee_id AND score IS NOT NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':employee_id', $employeeId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['average'] ?: 0;
    }
}
