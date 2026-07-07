<?php
/**
 * HRFlowSn - Contract Controller
 * Contrôleur pour la gestion des contrats
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../includes/session.php';

class ContractController extends Controller {
    private $contractModel;
    private $employeeModel;
    
    public function __construct() {
        if (!$this->isAuthenticated()) {
            $this->redirect('/HRFlowSn/index.php?route=auth/login');
        }
        
        // Vérifier les permissions (RH et Admin uniquement)
        $this->checkPermission(['Administrateur', 'RH']);
        
        $this->contractModel = $this->model('Contract');
        $this->employeeModel = $this->model('Employee');
    }
    
    /**
     * Liste des contrats
     */
    public function index() {
        $contracts = $this->contractModel->getAllWithDetails();
        
        $data = [
            'pageTitle' => 'Contrats',
            'activeMenu' => 'contracts',
            'contracts' => $contracts
        ];
        
        $this->view('contracts/index.php', $data);
    }
    
    /**
     * Formulaire de création de contrat
     */
    public function create() {
        if ($this->isPost()) {
            $contractData = [
                'contract_code' => $this->generateContractCode(),
                'employee_id' => $this->post('employee_id'),
                'contract_type' => $this->post('contract_type'),
                'salary' => $this->post('salary'),
                'start_date' => $this->post('start_date'),
                'end_date' => $this->post('end_date') ?: null,
                'trial_period' => $this->post('trial_period', 0),
                'signed_at' => $this->post('signed_at') ?: null,
                'status' => $this->post('status', 'En cours')
            ];
            
            if ($this->contractModel->create($contractData)) {
                // Synchroniser le salaire de base de l'employé et la date d'embauche si non définie
                $employee = $this->employeeModel->getById($contractData['employee_id']);
                $updateData = ['base_salary' => $contractData['salary']];
                if (empty($employee['hire_date'])) {
                    $updateData['hire_date'] = $contractData['start_date'];
                }
                $this->employeeModel->update($contractData['employee_id'], $updateData);
                
                $this->setFlash('success', 'Contrat créé avec succès');
                $this->redirect('/HRFlowSn/index.php?route=contracts');
            } else {
                $this->setFlash('error', 'Erreur lors de la création du contrat');
                $this->redirect('/HRFlowSn/index.php?route=contracts/create');
            }
        }
        
        $employees = $this->employeeModel->getAllWithDetails();
        
        $data = [
            'pageTitle' => 'Nouveau Contrat',
            'activeMenu' => 'contracts',
            'employees' => $employees
        ];
        
        $this->view('contracts/create.php', $data);
    }
    
    /**
     * Formulaire d'édition de contrat
     */
    public function edit() {
        $id = $this->get('id');
        
        if (!$id) {
            $this->setFlash('error', 'ID contrat manquant');
            $this->redirect('/HRFlowSn/index.php?route=contracts');
        }
        
        $contract = $this->contractModel->getById($id);
        
        if (!$contract) {
            $this->setFlash('error', 'Contrat non trouvé');
            $this->redirect('/HRFlowSn/index.php?route=contracts');
        }
        
        if ($this->isPost()) {
            $contractData = [
                'employee_id' => $this->post('employee_id'),
                'contract_type' => $this->post('contract_type'),
                'salary' => $this->post('salary'),
                'start_date' => $this->post('start_date'),
                'end_date' => $this->post('end_date') ?: null,
                'trial_period' => $this->post('trial_period', 0),
                'signed_at' => $this->post('signed_at') ?: null,
                'status' => $this->post('status')
            ];
            
            if ($this->contractModel->update($id, $contractData)) {
                // Synchroniser le salaire de base de l'employé et la date d'embauche si non définie
                $employee = $this->employeeModel->getById($contractData['employee_id']);
                $updateData = ['base_salary' => $contractData['salary']];
                if (empty($employee['hire_date'])) {
                    $updateData['hire_date'] = $contractData['start_date'];
                }
                $this->employeeModel->update($contractData['employee_id'], $updateData);
                
                $this->setFlash('success', 'Contrat mis à jour avec succès');
                $this->redirect('/HRFlowSn/index.php?route=contracts');
            } else {
                $this->setFlash('error', 'Erreur lors de la mise à jour du contrat');
            }
        }
        
        $employees = $this->employeeModel->getAllWithDetails();
        
        $data = [
            'pageTitle' => 'Modifier Contrat',
            'activeMenu' => 'contracts',
            'contract' => $contract,
            'employees' => $employees
        ];
        
        $this->view('contracts/edit.php', $data);
    }
    
    /**
     * Supprimer un contrat
     */
    public function delete() {
        $id = $this->get('id');
        
        if (!$id) {
            $this->setFlash('error', 'ID contrat manquant');
            $this->redirect('/HRFlowSn/index.php?route=contracts');
        }
        
        if ($this->contractModel->delete($id)) {
            $this->setFlash('success', 'Contrat supprimé avec succès');
        } else {
            $this->setFlash('error', 'Erreur lors de la suppression du contrat');
        }
        
        $this->redirect('/HRFlowSn/index.php?route=contracts');
    }
    
    /**
     * Voir les détails d'un contrat
     */
    public function show() {
        $id = $this->get('id');
        
        if (!$id) {
            $this->setFlash('error', 'ID contrat manquant');
            $this->redirect('/HRFlowSn/index.php?route=contracts');
        }
        
        $contract = $this->contractModel->getById($id);
        
        if (!$contract) {
            $this->setFlash('error', 'Contrat non trouvé');
            $this->redirect('/HRFlowSn/index.php?route=contracts');
        }
        
        $data = [
            'pageTitle' => 'Détails Contrat',
            'activeMenu' => 'contracts',
            'contract' => $contract
        ];
        
        $this->view('contracts/view.php', $data);
    }
    
    /**
     * Générer un code contrat unique
     */
    private function generateContractCode() {
        $prefix = 'CTR';
        $year = date('Y');
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        return $prefix . $year . $random;
    }
}
