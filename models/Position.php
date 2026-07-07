<?php
/**
 * HRFlowSn - Position Model
 * Modèle pour la gestion des postes
 */

require_once __DIR__ . '/Model.php';

class Position extends Model {
    protected $table = 'positions';
    
    /**
     * Récupérer tous les postes
     */
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
