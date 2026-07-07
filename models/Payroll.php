<?php
require_once __DIR__ . '/Model.php';

class Payroll extends Model {
    protected $table = 'payrolls';
    
    /**
     * Obtenir tous les bulletins avec les détails de l'employé
     */
    public function getAllWithDetails() {
        $sql = "SELECT p.*, e.first_name, e.last_name, e.employee_code, e.department_id, d.name as department_name 
                FROM {$this->table} p 
                JOIN employees e ON p.employee_id = e.id 
                LEFT JOIN departments d ON e.department_id = d.id 
                ORDER BY p.year DESC, p.month DESC, p.created_at DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Obtenir un bulletin avec détails complets (Employé, Département, Poste)
     */
    public function getByIdWithDetails($id) {
        $sql = "SELECT p.*, e.first_name, e.last_name, e.employee_code, e.hire_date, e.marital_status, 
                       d.name as department_name, pos.name as position_name
                FROM {$this->table} p 
                JOIN employees e ON p.employee_id = e.id 
                LEFT JOIN departments d ON e.department_id = d.id 
                LEFT JOIN positions pos ON e.position_id = pos.id
                WHERE p.id = :id";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Vérifier si un bulletin existe déjà pour cet employé, ce mois et cette année
     */
    public function checkExists($employeeId, $month, $year) {
        $sql = "SELECT id FROM {$this->table} WHERE employee_id = :employee_id AND month = :month AND year = :year";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':employee_id', $employeeId, PDO::PARAM_INT);
        $stmt->bindParam(':month', $month, PDO::PARAM_INT);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }
    
    /**
     * Obtenir les bulletins d'un employé spécifique
     */
    public function getByEmployee($employeeId) {
        $sql = "SELECT * FROM {$this->table} WHERE employee_id = :employee_id ORDER BY year DESC, month DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':employee_id', $employeeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Obtenir la masse salariale par mois pour les graphiques
     */
    public function getMonthlyPayrollMass($year) {
        $sql = "SELECT month, SUM(gross_salary) as total_gross, SUM(net_salary) as total_net 
                FROM {$this->table} 
                WHERE year = :year 
                GROUP BY month 
                ORDER BY month ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Obtenir la masse salariale totale pour un mois donné
     */
    public function getCurrentMonthTotalMass($month, $year) {
        $sql = "SELECT SUM(gross_salary) as total_gross FROM {$this->table} WHERE month = :month AND year = :year";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':month', $month, PDO::PARAM_INT);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total_gross'] ?? 0;
    }
}
