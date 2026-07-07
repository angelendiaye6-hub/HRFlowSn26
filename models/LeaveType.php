<?php
/**
 * HRFlowSn - LeaveType Model
 * Modèle pour la gestion des types de congé
 */

require_once __DIR__ . '/Model.php';

class LeaveType extends Model {
    protected $table = 'leave_types';
    
    /**
     * Récupérer tous les types de congé
     */
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
