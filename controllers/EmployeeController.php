<?php
/**
 * HRFlowSn - Employee Controller
 * Contrôleur pour la gestion des employés
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../includes/session.php';

class EmployeeController extends Controller {
    private $employeeModel;
    private $userModel;
    
    public function __construct() {
        if (!$this->isAuthenticated()) {
            $this->redirect('/HRFlowSn/index.php?route=auth/login');
        }
        
        // Vérifier les permissions (RH et Admin uniquement)
        $this->checkPermission(['Administrateur', 'RH']);
        
        $this->employeeModel = $this->model('Employee');
        $this->userModel = $this->model('User');
    }
    
    /**
     * Liste des employés
     */
    public function index() {
        $employees = $this->employeeModel->getAllWithDetails();
        
        $data = [
            'pageTitle' => 'Collaborateurs',
            'activeMenu' => 'employees',
            'employees' => $employees
        ];
        
        $this->view('employees/index.php', $data);
    }
    
    /**
     * Formulaire de création d'employé
     */
    public function create() {
        if ($this->isPost()) {
            // Créer d'abord l'utilisateur
            $userData = [
                'email' => $this->post('email'),
                'password' => $this->post('password'),
                'role_id' => $this->post('role_id', 4)
            ];
            
            if ($this->userModel->emailExists($userData['email'])) {
                $this->setFlash('error', 'Cet email est déjà utilisé');
                set_old_input($_POST);
                $this->redirect('/HRFlowSn/index.php?route=employees/create');
            }
            
            if (!$this->userModel->register($userData)) {
                $this->setFlash('error', 'Erreur lors de la création de l\'utilisateur');
                set_old_input($_POST);
                $this->redirect('/HRFlowSn/index.php?route=employees/create');
            }
            
            $userId = $this->userModel->getLastInsertId();
            
            // Créer l'employé (avec les champs simplifiés, valeurs par défaut pour le reste)
            $employeeData = [
                'employee_code' => $this->generateEmployeeCode(),
                'user_id' => $userId,
                'department_id' => $this->post('department_id'),
                'position_id' => $this->post('position_id'),
                'manager_id' => null,
                'first_name' => $this->post('first_name'),
                'last_name' => $this->post('last_name'),
                'gender' => $this->post('gender'),
                'birth_date' => $this->post('birth_date'),
                'birth_place' => '',
                'nationality' => 'Senegalese',
                'marital_status' => 'Single',
                'phone' => '',
                'email' => $this->post('email'),
                'address' => '',
                'qualification' => '',
                'hire_date' => null,
                'base_salary' => null,
                'status' => 'Active'
            ];
            
            if ($this->employeeModel->create($employeeData)) {
                $newEmployeeId = $this->employeeModel->getLastInsertId();
                $this->setFlash('success', 'Employé créé avec succès. Veuillez maintenant créer son contrat pour activer son profil.');
                clear_old_input();
                $this->redirect('/HRFlowSn/index.php?route=contracts/create&employee_id=' . $newEmployeeId);
            } else {
                $this->setFlash('error', 'Erreur lors de la création de l\'employé');
                set_old_input($_POST);
                $this->redirect('/HRFlowSn/index.php?route=employees/create');
            }
        }
        
        $data = [
            'pageTitle' => 'Nouveau Collaborateur',
            'activeMenu' => 'employees'
        ];
        
        $this->view('employees/create.php', $data);
    }
    
    /**
     * Formulaire d'édition d'employé
     */
    public function edit() {
        $id = $this->get('id');
        
        if (!$id) {
            $this->setFlash('error', 'ID employé manquant');
            $this->redirect('/HRFlowSn/index.php?route=employees');
        }
        
        $employee = $this->employeeModel->getById($id);
        
        if (!$employee) {
            $this->setFlash('error', 'Employé non trouvé');
            $this->redirect('/HRFlowSn/index.php?route=employees');
        }
        
        if ($this->isPost()) {
            $employeeData = [
                'department_id' => $this->post('department_id'),
                'position_id' => $this->post('position_id'),
                'manager_id' => $this->post('manager_id') ?: null,
                'first_name' => $this->post('first_name'),
                'last_name' => $this->post('last_name'),
                'gender' => $this->post('gender'),
                'birth_date' => $this->post('birth_date'),
                'birth_place' => $this->post('birth_place'),
                'nationality' => $this->post('nationality'),
                'marital_status' => $this->post('marital_status'),
                'phone' => $this->post('phone'),
                'email' => $this->post('email'),
                'address' => $this->post('address'),
                'qualification' => $this->post('qualification'),
                'status' => $this->post('status')
            ];
            
            if ($this->employeeModel->update($id, $employeeData)) {
                $this->setFlash('success', 'Employé mis à jour avec succès');
                $this->redirect('/HRFlowSn/index.php?route=employees');
            } else {
                $this->setFlash('error', 'Erreur lors de la mise à jour de l\'employé');
            }
        }
        
        $data = [
            'pageTitle' => 'Modifier Collaborateur',
            'activeMenu' => 'employees',
            'employee' => $employee
        ];
        
        $this->view('employees/edit.php', $data);
    }
    
    /**
     * Supprimer un employé
     */
    public function delete() {
        $id = $this->get('id');
        
        if (!$id) {
            $this->setFlash('error', 'ID employé manquant');
            $this->redirect('/HRFlowSn/index.php?route=employees');
        }
        
        if ($this->employeeModel->delete($id)) {
            $this->setFlash('success', 'Employé supprimé avec succès');
        } else {
            $this->setFlash('error', 'Erreur lors de la suppression de l\'employé');
        }
        
        $this->redirect('/HRFlowSn/index.php?route=employees');
    }
    
    /**
     * Voir les détails d'un employé
     */
    public function show() {
        $id = $this->get('id');
        
        if (!$id) {
            $this->setFlash('error', 'ID employé manquant');
            $this->redirect('/HRFlowSn/index.php?route=employees');
        }
        
        $employee = $this->employeeModel->getById($id);
        
        if (!$employee) {
            $this->setFlash('error', 'Employé non trouvé');
            $this->redirect('/HRFlowSn/index.php?route=employees');
        }
        
        // Charger les modèles supplémentaires
        $contractModel = $this->model('Contract');
        $evaluationModel = $this->model('Evaluation');
        $leaveModel = $this->model('LeaveRequest');
        $trainingModel = $this->model('Training');
        $payrollModel = $this->model('Payroll');
        
        // Récupérer les données liées
        $contracts = $contractModel->getByEmployee($id);
        $evaluations = $evaluationModel->getByEmployee($id);
        $leaves = $leaveModel->getByEmployee($id);
        $trainings = $trainingModel->getByEmployee($id);
        $payrolls = $payrollModel->getByEmployee($id);
        
        $data = [
            'pageTitle' => 'Détails Collaborateur',
            'activeMenu' => 'employees',
            'employee' => $employee,
            'contracts' => $contracts,
            'evaluations' => $evaluations,
            'leaves' => $leaves,
            'trainings' => $trainings,
            'payrolls' => $payrolls
        ];
        
        $this->view('employees/view.php', $data);
    }
    
    /**
     * Générer un code employé unique
     */
    private function generateEmployeeCode() {
        $prefix = 'EMP';
        $year = date('Y');
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        return $prefix . $year . $random;
    }
}
