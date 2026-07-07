<?php
/**
 * HRFlowSn - User Model
 * Modèle pour la gestion des utilisateurs et de l'authentification
 */

require_once __DIR__ . '/Model.php';

class User extends Model {
    protected $table = 'users';
    
    /**
     * Authentifier un utilisateur par email et mot de passe
     */
    public function authenticate($email, $password) {
        $sql = "SELECT u.*, r.name as role_name 
                FROM {$this->table} u
                JOIN roles r ON u.role_id = r.id
                WHERE u.email = :email";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Mettre à jour la dernière connexion
            $this->updateLastLogin($user['id']);
            return $user;
        }
        
        return false;
    }
    
    /**
     * Mettre à jour la dernière connexion
     */
    private function updateLastLogin($userId) {
        $sql = "UPDATE {$this->table} SET last_login = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    /**
     * Créer un nouvel utilisateur
     */
    public function register($data) {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO {$this->table} (role_id, email, password) 
                VALUES (:role_id, :email, :password)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':role_id', $data['role_id'], PDO::PARAM_INT);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashedPassword);
        
        return $stmt->execute();
    }
    
    /**
     * Vérifier si un email existe déjà
     */
    public function emailExists($email) {
        $sql = "SELECT id FROM {$this->table} WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }
    
    /**
     * Récupérer un utilisateur avec son rôle
     */
    public function getUserWithRole($userId) {
        $sql = "SELECT u.*, r.name as role_name 
                FROM {$this->table} u
                JOIN roles r ON u.role_id = r.id
                WHERE u.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Mettre à jour le mot de passe
     */
    public function updatePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $sql = "UPDATE {$this->table} SET password = :password WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
}
