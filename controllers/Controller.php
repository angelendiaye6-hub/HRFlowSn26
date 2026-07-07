<?php
/**
 * HRFlowSn - Base Controller Class
 * Classe de base pour tous les contrôleurs
 */

abstract class Controller {
    protected $model;
    
    /**
     * Charger une vue
     */
    protected function view($view, $data = []) {
        extract($data);
        require_once __DIR__ . "/../views/$view";
    }
    
    /**
     * Charger un modèle
     */
    protected function model($model) {
        require_once __DIR__ . "/../models/$model.php";
        return new $model();
    }
    
    /**
     * Rediriger vers une URL
     */
    protected function redirect($url) {
        header("Location: $url");
        exit();
    }
    
    /**
     * Vérifier si la requête est POST
     */
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    /**
     * Récupérer une valeur POST
     */
    protected function post($key, $default = null) {
        return $_POST[$key] ?? $default;
    }
    
    /**
     * Récupérer une valeur GET
     */
    protected function get($key, $default = null) {
        return $_GET[$key] ?? $default;
    }
    
    /**
     * Définir un message flash en session
     */
    protected function setFlash($type, $message) {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }
    
    /**
     * Récupérer et effacer le message flash
     */
    protected function getFlash() {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }
    
    /**
     * Vérifier si l'utilisateur est connecté
     */
    protected function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Obtenir l'ID de l'utilisateur connecté
     */
    protected function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Obtenir le rôle de l'utilisateur connecté
     */
    protected function getUserRole() {
        return $_SESSION['user_role'] ?? null;
    }
    
    /**
     * Vérifier les permissions
     */
    protected function checkPermission($allowedRoles) {
        if (!$this->isAuthenticated()) {
            $this->redirect('/HRFlowSn/index.php?route=auth/login');
        }
        
        $userRole = $this->getUserRole();
        if (!in_array($userRole, $allowedRoles)) {
            $this->setFlash('error', 'Accès non autorisé');
            $this->redirect('/HRFlowSn/index.php?route=dashboard');
        }
    }
    
    /**
     * Enregistrer une action dans le journal d'audit
     */
    protected function logAction($action) {
        $userId = $this->getUserId();
        if ($userId) {
            require_once __DIR__ . '/../models/AuditLog.php';
            $auditModel = new AuditLog();
            $auditModel->create([
                'user_id' => $userId,
                'action' => $action
            ]);
        }
    }
}
