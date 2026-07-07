<?php
/**
 * HRFlowSn - Role Model
 * Modèle pour la gestion des rôles
 */

require_once __DIR__ . '/Model.php';

class Role extends Model {
    protected $table = 'roles';
    
    /**
     * Récupérer tous les rôles
     */
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
