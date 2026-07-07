<?php
/**
 * HRFlowSn - Dashboard Controller
 * Contrôleur pour le tableau de bord principal
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../includes/session.php';

class DashboardController extends Controller {
    private $employeeModel;
    private $leaveRequestModel;
    private $contractModel;
    private $payrollModel;
    
    public function __construct() {
        // Vérifier l'authentification
        if (!$this->isAuthenticated()) {
            $this->redirect('/HRFlowSn/index.php?route=auth/login');
        }
        
        $this->employeeModel = $this->model('Employee');
        $this->leaveRequestModel = $this->model('LeaveRequest');
        $this->contractModel = $this->model('Contract');
        $this->payrollModel = $this->model('Payroll');
    }
    
    /**
     * Page principale du dashboard
     */
    public function index() {
        $currentMonth = (int)date('m');
        $currentYear = (int)date('Y');
        
        // Statistiques
        $stats = [
            'total_employees' => $this->employeeModel->countTotal(),
            'active_employees' => $this->employeeModel->countByStatus('Active'),
            'pending_leaves' => $this->leaveRequestModel->countByStatus('Pending'),
            'active_contracts' => $this->contractModel->countByStatus('En cours'),
            'masse_salariale' => $this->payrollModel->getCurrentMonthTotalMass($currentMonth, $currentYear)
        ];
        
        // Employés récents
        $recentEmployees = $this->employeeModel->getRecent(5);
        
        // Demandes de congé récentes
        $recentLeaves = $this->leaveRequestModel->getRecent(5);
        
        // Contrats qui expirent bientôt
        $expiringContracts = $this->contractModel->getExpiringSoon(30);
        
        // Employés par département
        $employeesByDepartment = $this->employeeModel->countByDepartment();
        
        // Masse salariale mensuelle pour le graphique
        $monthlyPayrollMass = $this->payrollModel->getMonthlyPayrollMass($currentYear);
        
        $data = [
            'pageTitle' => 'Dashboard',
            'activeMenu' => 'dashboard',
            'stats' => $stats,
            'recentEmployees' => $recentEmployees,
            'recentLeaves' => $recentLeaves,
            'expiringContracts' => $expiringContracts,
            'employeesByDepartment' => $employeesByDepartment,
            'monthlyPayrollMass' => $monthlyPayrollMass
        ];
        
        $this->view('dashboard/index.php', $data);
    }
}
