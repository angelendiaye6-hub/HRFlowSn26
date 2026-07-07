<?php
/**
 * HRFlowSn - Report Controller
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Employee.php';
require_once __DIR__ . '/../models/Payroll.php';

class ReportController extends Controller {
    private $employeeModel;
    private $payrollModel;
    
    public function __construct() {
        if (!$this->isAuthenticated()) {
            $this->redirect('/HRFlowSn/index.php?route=auth/login');
        }
        
        $this->checkPermission(['Administrateur', 'RH']);
        
        $this->employeeModel = new Employee();
        $this->payrollModel = new Payroll();
    }
    
    /**
     * Page principale des rapports
     */
    public function index() {
        $data = [
            'pageTitle' => 'Rapports & Exports',
            'activeMenu' => 'reports'
        ];
        
        $this->view('reports/index.php', $data);
    }
    
    /**
     * Export de la liste des employés en CSV
     */
    public function exportEmployees() {
        $this->logAction("Export Excel de la liste des employés");
        
        $employees = $this->employeeModel->getAllWithDetails();
        
        $filename = "employes_hrflowsn_" . date('Ymd') . ".xls";
        
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta charset="utf-8"></head><body>';
        echo '<table border="1">';
        echo '<tr><th>Matricule</th><th>Prénom</th><th>Nom</th><th>Genre</th><th>Téléphone</th><th>Email</th><th>Département</th><th>Poste</th><th>Statut</th><th>Salaire de Base</th></tr>';
        
        foreach ($employees as $emp) {
            $gender = $emp['gender'] === 'Male' ? 'Masculin' : 'Féminin';
            echo "<tr>";
            echo "<td>{$emp['employee_code']}</td>";
            echo "<td>{$emp['first_name']}</td>";
            echo "<td>{$emp['last_name']}</td>";
            echo "<td>{$gender}</td>";
            echo "<td>{$emp['phone']}</td>";
            echo "<td>{$emp['email']}</td>";
            echo "<td>{$emp['department_name']}</td>";
            echo "<td>{$emp['position_name']}</td>";
            echo "<td>{$emp['status']}</td>";
            echo "<td>{$emp['base_salary']}</td>";
            echo "</tr>";
        }
        
        echo '</table></body></html>';
        exit;
    }
    
    /**
     * Export de l'historique des paies en CSV
     */
    public function exportPayrolls() {
        $this->logAction("Export Excel de l'historique de la paie");
        
        $payrolls = $this->payrollModel->getAllWithDetails();
        
        $filename = "historique_paie_hrflowsn_" . date('Ymd') . ".xls";
        
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta charset="utf-8"></head><body>';
        echo '<table border="1">';
        echo '<tr><th>Réf Bulletin</th><th>Mois</th><th>Année</th><th>Matricule</th><th>Prénom</th><th>Nom</th><th>Salaire Base</th><th>Heures Sup</th><th>Primes</th><th>Absences</th><th>Salaire Brut</th><th>IPRES</th><th>IR</th><th>Net à Payer</th></tr>';
        
        foreach ($payrolls as $p) {
            echo "<tr>";
            echo "<td>{$p['payroll_code']}</td>";
            echo "<td>{$p['month']}</td>";
            echo "<td>{$p['year']}</td>";
            echo "<td>{$p['employee_code']}</td>";
            echo "<td>{$p['first_name']}</td>";
            echo "<td>{$p['last_name']}</td>";
            echo "<td>{$p['base_salary']}</td>";
            echo "<td>{$p['overtime_hours']}</td>";
            echo "<td>{$p['bonus']}</td>";
            echo "<td>{$p['absence_days']}</td>";
            echo "<td>{$p['gross_salary']}</td>";
            echo "<td>{$p['ipres']}</td>";
            echo "<td>{$p['income_tax']}</td>";
            echo "<td>{$p['net_salary']}</td>";
            echo "</tr>";
        }
        
        echo '</table></body></html>';
        exit;
    }
    
    /**
     * Affichage du bilan social (imprimable PDF)
     */
    public function socialBalance() {
        $this->logAction("Génération du Bilan Social");
        
        $employees = $this->employeeModel->getAllWithDetails();
        $totalEmployees = count($employees);
        $activeEmployees = 0;
        $males = 0;
        $females = 0;
        $totalBaseSalary = 0;
        
        $departmentStats = [];
        
        foreach ($employees as $emp) {
            if ($emp['status'] === 'Active') {
                $activeEmployees++;
                if ($emp['gender'] === 'Male') $males++;
                else $females++;
                
                $totalBaseSalary += $emp['base_salary'];
                
                $dept = $emp['department_name'] ?? 'Non défini';
                if (!isset($departmentStats[$dept])) {
                    $departmentStats[$dept] = 0;
                }
                $departmentStats[$dept]++;
            }
        }
        
        $data = [
            'pageTitle' => 'Bilan Social',
            'activeMenu' => 'reports',
            'stats' => [
                'total' => $totalEmployees,
                'active' => $activeEmployees,
                'males' => $males,
                'females' => $females,
                'total_salary' => $totalBaseSalary,
                'departments' => $departmentStats
            ]
        ];
        
        $this->view('reports/social_balance.php', $data);
    }
}
