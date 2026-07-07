<?php
/**
 * HRFlowSn - Settings Controller
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/AuditLog.php';

class SettingsController extends Controller {
    private $userModel;
    private $auditModel;
    
    public function __construct() {
        if (!$this->isAuthenticated()) {
            $this->redirect('/HRFlowSn/index.php?route=auth/login');
        }
        
        // Seul l'Administrateur peut accéder aux paramètres techniques
        $this->checkPermission(['Administrateur']);
        
        $this->userModel = new User();
        $this->auditModel = new AuditLog();
    }
    
    /**
     * Page principale des paramètres
     */
    public function index() {
        // Obtenir la liste des utilisateurs
        $users = $this->userModel->getAll();
        
        // Obtenir les derniers logs
        $logs = $this->auditModel->getRecentWithUsers(50);
        
        $data = [
            'pageTitle' => 'Paramètres et Audit',
            'activeMenu' => 'settings',
            'users' => $users,
            'logs' => $logs
        ];
        
        $this->view('settings/index.php', $data);
    }
    
    /**
     * Effectuer une sauvegarde de la base de données
     */
    public function backup() {
        // Enregistrer l'action
        $this->logAction("Génération d'une sauvegarde de la base de données");
        
        // Nom de la base et identifiants
        // Pour XAMPP par défaut : root / pas de mdp
        $dbName = 'hrflowsn';
        $user = 'root';
        $password = '';
        
        $backupName = 'hrflowsn_backup_' . date('Y-m-d_H-i-s') . '.sql';
        $exportPath = __DIR__ . '/../exports/' . $backupName;
        
        // S'assurer que le dossier exports existe
        if (!file_exists(__DIR__ . '/../exports')) {
            mkdir(__DIR__ . '/../exports', 0777, true);
        }
        
        // Commande mysqldump pour XAMPP Mac
        $mysqldumpPath = '/Applications/XAMPP/xamppfiles/bin/mysqldump';
        if (!file_exists($mysqldumpPath)) {
            $mysqldumpPath = 'mysqldump'; // Fallback
        }
        
        $command = "$mysqldumpPath -u $user " . ($password ? "-p$password " : "") . "$dbName > $exportPath";
        
        exec($command, $output, $returnVar);
        
        if ($returnVar === 0 && file_exists($exportPath)) {
            // Télécharger le fichier
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($exportPath).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($exportPath));
            readfile($exportPath);
            
            // Nettoyer après le téléchargement pour ne pas encombrer le serveur
            unlink($exportPath);
            exit;
        } else {
            $this->setFlash('error', 'Erreur lors de la sauvegarde de la base de données (Code: ' . escapeshellarg($returnVar) . ').');
            $this->redirect('/HRFlowSn/index.php?route=settings');
        }
    }
}
