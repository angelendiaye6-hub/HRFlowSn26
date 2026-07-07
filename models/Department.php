<?php
/**
 * HRFlowSn - Department Model
 * Modèle pour la gestion des départements
 */

require_once __DIR__ . '/Model.php';

class Department extends Model {
    protected $table = 'departments';
    
    /**
     * Récupérer tous les départements
     */
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
