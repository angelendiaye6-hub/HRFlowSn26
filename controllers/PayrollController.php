<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Payroll.php';
require_once __DIR__ . '/../models/Employee.php';
require_once __DIR__ . '/../models/Contract.php';

class PayrollController extends Controller {
    private $payrollModel;
    private $employeeModel;
    private $contractModel;
    
    public function __construct() {
        // Vérifier si l'utilisateur est connecté et a les droits (RH ou Admin)
        $this->checkPermission(['Administrateur', 'RH']);
        
        $this->payrollModel = new Payroll();
        $this->employeeModel = new Employee();
        $this->contractModel = new Contract();
    }
    
    /**
     * Liste des fiches de paie
     */
    public function index() {
        $payrolls = $this->payrollModel->getAllWithDetails();
        
        $data = [
            'pageTitle' => 'Gestion de la Paie',
            'activeMenu' => 'payroll',
            'payrolls' => $payrolls
        ];
        
        $this->view('payrolls/index.php', $data);
    }
    
    /**
     * Création d'une fiche de paie (Génération)
     */
    public function create() {
        if ($this->isPost()) {
            $employeeId = $this->post('employee_id');
            $month = (int)$this->post('month');
            $year = (int)$this->post('year');
            
            // Vérifier si le bulletin existe déjà
            if ($this->payrollModel->checkExists($employeeId, $month, $year)) {
                $this->setFlash('error', 'Un bulletin existe déjà pour cet employé à cette période.');
                $this->redirect('/HRFlowSn/index.php?route=payroll/create');
            }
            
            $bonus = (float)$this->post('bonus', 0);
            $overtimeHours = (float)$this->post('overtime_hours', 0);
            $absenceDays = (float)$this->post('absence_days', 0);
            
            // Récupérer le salaire de base de l'employé
            $employee = $this->employeeModel->getById($employeeId);
            $baseSalary = (float)$employee['base_salary'];
            
            // Calculs simplifiés (Sprint 4)
            // 1 mois = environ 173.33 heures de travail
            $hourlyRate = $baseSalary / 173.33;
            $overtimePay = $hourlyRate * $overtimeHours;
            
            // Retenue sur absence (salaire base / 30 jours)
            $dailyRate = $baseSalary / 30;
            $absenceDeduction = $dailyRate * $absenceDays;
            
            // Salaire brut
            $grossSalary = $baseSalary + $bonus + $overtimePay - $absenceDeduction;
            if ($grossSalary < 0) $grossSalary = 0;
            
            // Déductions (Formules académiques simplifiées)
            $ipres = $grossSalary * 0.08; // 8% part employé
            $css = $grossSalary * 0.01; // 1% indicatif (normalement part patronale)
            $cfce = $grossSalary * 0.01; // 1% indicatif
            
            // Impôt (IR) simplifié : 10% sur le (Brut - IPRES) si > 100 000 par exemple
            $taxableIncome = $grossSalary - $ipres;
            $incomeTax = $taxableIncome > 100000 ? ($taxableIncome - 100000) * 0.10 : 0;
            
            $netSalary = $grossSalary - $ipres - $incomeTax;
            
            $payrollData = [
                'payroll_code' => $this->generatePayrollCode($month, $year),
                'employee_id' => $employeeId,
                'month' => $month,
                'year' => $year,
                'base_salary' => $baseSalary,
                'bonus' => $bonus,
                'overtime_hours' => $overtimeHours,
                'absence_days' => $absenceDays,
                'gross_salary' => $grossSalary,
                'ipres' => $ipres,
                'css' => $css,
                'cfce' => $cfce,
                'income_tax' => $incomeTax,
                'net_salary' => $netSalary,
                'generated_at' => date('Y-m-d H:i:s')
            ];
            
            if ($this->payrollModel->create($payrollData)) {
                $this->logAction("Génération d'un bulletin de paie (Réf: " . $payrollData['payroll_code'] . ")");
                $this->setFlash('success', 'Bulletin de paie généré avec succès');
                $this->redirect('/HRFlowSn/index.php?route=payroll');
            } else {
                $this->setFlash('error', 'Erreur lors de la génération du bulletin');
                $this->redirect('/HRFlowSn/index.php?route=payroll/create');
            }
        }
        
        // Récupérer les employés actifs
        $employees = $this->employeeModel->getAllWithDetails();
        // Filtrer pour ne garder que les actifs
        $employees = array_filter($employees, function($e) {
            return $e['status'] === 'Active';
        });
        
        $data = [
            'pageTitle' => 'Générer une Fiche de Paie',
            'activeMenu' => 'payroll',
            'employees' => $employees
        ];
        
        $this->view('payrolls/create.php', $data);
    }
    
    /**
     * Voir les détails d'un bulletin
     */
    public function show() {
        $id = $this->get('id');
        
        if (!$id) {
            $this->setFlash('error', 'ID bulletin manquant');
            $this->redirect('/HRFlowSn/index.php?route=payroll');
        }
        
        $payroll = $this->payrollModel->getByIdWithDetails($id);
        
        if (!$payroll) {
            $this->setFlash('error', 'Bulletin non trouvé');
            $this->redirect('/HRFlowSn/index.php?route=payroll');
        }
        
        $data = [
            'pageTitle' => 'Bulletin de Paie',
            'activeMenu' => 'payroll',
            'payroll' => $payroll
        ];
        
        $this->view('payrolls/show.php', $data);
    }
    
    /**
     * Supprimer un bulletin
     */
    public function delete() {
        $id = $this->post('id');
        
        if (!$id) {
            $this->setFlash('error', 'ID bulletin manquant');
            $this->redirect('/HRFlowSn/index.php?route=payroll');
        }
        
        if ($this->payrollModel->delete($id)) {
            $this->setFlash('success', 'Bulletin supprimé avec succès');
        } else {
            $this->setFlash('error', 'Erreur lors de la suppression du bulletin');
        }
        
        $this->redirect('/HRFlowSn/index.php?route=payroll');
    }
    
    /**
     * Générer un code unique pour le bulletin
     */
    private function generatePayrollCode($month, $year) {
        $prefix = 'BULL';
        $m = str_pad($month, 2, '0', STR_PAD_LEFT);
        $y = substr($year, 2, 2);
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        return $prefix . $m . $y . $random;
    }
}
