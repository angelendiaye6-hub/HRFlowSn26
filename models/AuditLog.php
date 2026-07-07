<?php
require_once __DIR__ . '/Model.php';

class AuditLog extends Model {
    protected $table = 'audit_logs';
    
    /**
     * Obtenir les derniers logs avec les informations de l'utilisateur
     */
    public function getRecentWithUsers($limit = 50) {
        $sql = "SELECT a.*, u.email, r.name as role_name 
                FROM {$this->table} a 
                LEFT JOIN users u ON a.user_id = u.id 
                LEFT JOIN roles r ON u.role_id = r.id 
                ORDER BY a.created_at DESC 
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
