<?php
/**
 * HRFlowSn - Leave Controller
 * Contrôleur pour la gestion des demandes de congé
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../includes/session.php';

class LeaveController extends Controller {
    private $leaveRequestModel;
    private $leaveTypeModel;
    private $employeeModel;
    
    public function __construct() {
        if (!$this->isAuthenticated()) {
            $this->redirect('/HRFlowSn/index.php?route=auth/login');
        }
        
        // Vérifier les permissions (RH, Admin, Manager)
        $this->checkPermission(['Administrateur', 'RH', 'Manager']);
        
        $this->leaveRequestModel = $this->model('LeaveRequest');
        $this->leaveTypeModel = $this->model('LeaveType');
        $this->employeeModel = $this->model('Employee');
    }
    
    /**
     * Liste des demandes de congé
     */
    public function index() {
        $leaveRequests = $this->leaveRequestModel->getAllWithDetails();
        
        $data = [
            'pageTitle' => 'Congés',
            'activeMenu' => 'leaves',
            'leaveRequests' => $leaveRequests
        ];
        
        $this->view('leaves/index.php', $data);
    }
    
    /**
     * Formulaire de création de demande de congé
     */
    public function create() {
        if ($this->isPost()) {
            $leaveData = [
                'employee_id' => $this->post('employee_id'),
                'leave_type_id' => $this->post('leave_type_id'),
                'start_date' => $this->post('start_date'),
                'end_date' => $this->post('end_date'),
                'reason' => $this->post('reason'),
                'status' => $this->post('status', 'Pending')
            ];
            
            if ($this->leaveRequestModel->create($leaveData)) {
                $this->setFlash('success', 'Demande de congé créée avec succès');
                $this->redirect('/HRFlowSn/index.php?route=leaves');
            } else {
                $this->setFlash('error', 'Erreur lors de la création de la demande');
                $this->redirect('/HRFlowSn/index.php?route=leaves/create');
            }
        }
        
        $employees = $this->employeeModel->getAllWithDetails();
        $leaveTypes = $this->leaveTypeModel->getAll();
        
        $data = [
            'pageTitle' => 'Nouvelle Demande de Congé',
            'activeMenu' => 'leaves',
            'employees' => $employees,
            'leaveTypes' => $leaveTypes
        ];
        
        $this->view('leaves/create.php', $data);
    }
    
    /**
     * Formulaire d'édition de demande de congé
     */
    public function edit() {
        $id = $this->get('id');
        
        if (!$id) {
            $this->setFlash('error', 'ID demande manquant');
            $this->redirect('/HRFlowSn/index.php?route=leaves');
        }
        
        $leaveRequest = $this->leaveRequestModel->getById($id);
        
        if (!$leaveRequest) {
            $this->setFlash('error', 'Demande non trouvée');
            $this->redirect('/HRFlowSn/index.php?route=leaves');
        }
        
        if ($this->isPost()) {
            $leaveData = [
                'employee_id' => $this->post('employee_id'),
                'leave_type_id' => $this->post('leave_type_id'),
                'start_date' => $this->post('start_date'),
                'end_date' => $this->post('end_date'),
                'reason' => $this->post('reason'),
                'status' => $this->post('status')
            ];
            
            if ($this->leaveRequestModel->update($id, $leaveData)) {
                $this->setFlash('success', 'Demande mise à jour avec succès');
                $this->redirect('/HRFlowSn/index.php?route=leaves');
            } else {
                $this->setFlash('error', 'Erreur lors de la mise à jour de la demande');
            }
        }
        
        $employees = $this->employeeModel->getAllWithDetails();
        $leaveTypes = $this->leaveTypeModel->getAll();
        
        $data = [
            'pageTitle' => 'Modifier Demande de Congé',
            'activeMenu' => 'leaves',
            'leaveRequest' => $leaveRequest,
            'employees' => $employees,
            'leaveTypes' => $leaveTypes
        ];
        
        $this->view('leaves/edit.php', $data);
    }
    
    /**
     * Supprimer une demande de congé
     */
    public function delete() {
        $id = $this->get('id');
        
        if (!$id) {
            $this->setFlash('error', 'ID demande manquant');
            $this->redirect('/HRFlowSn/index.php?route=leaves');
        }
        
        if ($this->leaveRequestModel->delete($id)) {
            $this->setFlash('success', 'Demande supprimée avec succès');
        } else {
            $this->setFlash('error', 'Erreur lors de la suppression de la demande');
        }
        
        $this->redirect('/HRFlowSn/index.php?route=leaves');
    }
    
    /**
     * Voir les détails d'une demande
     */
    public function show() {
        $id = $this->get('id');
        
        if (!$id) {
            $this->setFlash('error', 'ID demande manquant');
            $this->redirect('/HRFlowSn/index.php?route=leaves');
        }
        
        $leaveRequest = $this->leaveRequestModel->getByIdWithDetails($id);
        
        if (!$leaveRequest) {
            $this->setFlash('error', 'Demande non trouvée');
            $this->redirect('/HRFlowSn/index.php?route=leaves');
        }
        
        $data = [
            'pageTitle' => 'Détails Demande de Congé',
            'activeMenu' => 'leaves',
            'leaveRequest' => $leaveRequest
        ];
        
        $this->view('leaves/view.php', $data);
    }
    
    /**
     * Approuver une demande
     */
    public function approve() {
        $id = $this->get('id');
        
        if (!$id) {
            $this->setFlash('error', 'ID demande manquant');
            $this->redirect('/HRFlowSn/index.php?route=leaves');
        }
        
        if ($this->leaveRequestModel->approve($id)) {
            $this->setFlash('success', 'Demande approuvée avec succès');
        } else {
            $this->setFlash('error', 'Erreur lors de l\'approbation');
        }
        
        $this->redirect('/HRFlowSn/index.php?route=leaves');
    }
    
    /**
     * Rejeter une demande
     */
    public function reject() {
        $id = $this->get('id');
        
        if (!$id) {
            $this->setFlash('error', 'ID demande manquant');
            $this->redirect('/HRFlowSn/index.php?route=leaves');
        }
        
        if ($this->leaveRequestModel->reject($id)) {
            $this->setFlash('success', 'Demande rejetée');
        } else {
            $this->setFlash('error', 'Erreur lors du rejet');
        }
        
        $this->redirect('/HRFlowSn/index.php?route=leaves');
    }
    
    /**
     * Annuler une demande
     */
    public function cancel() {
        $id = $this->get('id');
        
        if (!$id) {
            $this->setFlash('error', 'ID demande manquant');
            $this->redirect('/HRFlowSn/index.php?route=leaves');
        }
        
        if ($this->leaveRequestModel->cancel($id)) {
            $this->setFlash('success', 'Demande annulée');
        } else {
            $this->setFlash('error', 'Erreur lors de l\'annulation');
        }
        
        $this->redirect('/HRFlowSn/index.php?route=leaves');
    }
}
