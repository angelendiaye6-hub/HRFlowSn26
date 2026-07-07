<?php
/**
 * HRFlowSn - Authentication Controller
 * Contrôleur pour la gestion de l'authentification (login, logout, register)
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../includes/session.php';

class AuthController extends Controller {
    private $userModel;
    
    public function __construct() {
        $this->userModel = $this->model('User');
    }
    
    /**
     * Page de connexion
     */
    public function login() {
        // Si déjà connecté, rediriger vers le dashboard
        if ($this->isAuthenticated()) {
            $this->redirect('/HRFlowSn/index.php?route=dashboard');
        }
        
        if ($this->isPost()) {
            $email = $this->post('email');
            $password = $this->post('password');
            
            $user = $this->userModel->authenticate($email, $password);
            
            if ($user) {
                // Créer la session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role_name'];
                $_SESSION['user_role_id'] = $user['role_id'];
                
                $this->logAction("Connexion au système");
                
                $this->setFlash('success', 'Connexion réussie');
                $this->redirect('/HRFlowSn/index.php?route=dashboard');
            } else {
                $this->setFlash('error', 'Email ou mot de passe incorrect');
            }
        }
        
        $this->view('auth/login.php');
    }
    
    /**
     * Déconnexion
     */
    public function logout() {
        session_destroy_all();
        $this->redirect('/HRFlowSn/index.php?route=auth/login');
    }
    
    /**
     * Page d'inscription
     */
    public function register() {
        // Si déjà connecté, rediriger vers le dashboard
        if ($this->isAuthenticated()) {
            $this->redirect('/HRFlowSn/index.php?route=dashboard');
        }
        
        if ($this->isPost()) {
            $email = $this->post('email');
            $password = $this->post('password');
            $confirmPassword = $this->post('confirm_password');
            $roleId = $this->post('role_id', 4); // Par défaut: Employé
            
            // Validation
            if (empty($email) || empty($password)) {
                $this->setFlash('error', 'Veuillez remplir tous les champs');
            } elseif ($password !== $confirmPassword) {
                $this->setFlash('error', 'Les mots de passe ne correspondent pas');
            } elseif (strlen($password) < 6) {
                $this->setFlash('error', 'Le mot de passe doit contenir au moins 6 caractères');
            } elseif ($this->userModel->emailExists($email)) {
                $this->setFlash('error', 'Cet email est déjà utilisé');
            } else {
                // Créer l'utilisateur
                $data = [
                    'email' => $email,
                    'password' => $password,
                    'role_id' => $roleId
                ];
                
                if ($this->userModel->register($data)) {
                    $this->setFlash('success', 'Compte créé avec succès. Veuillez vous connecter.');
                    $this->redirect('/HRFlowSn/index.php?route=auth/login');
                } else {
                    $this->setFlash('error', 'Erreur lors de la création du compte');
                }
            }
        }
        
        $this->view('auth/register.php');
    }
}
