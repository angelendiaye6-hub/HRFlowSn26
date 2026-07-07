<?php
/**
 * HRFlowSn - Training Controller
 * Contrôleur pour la gestion des formations
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../includes/session.php';

class TrainingController extends Controller {
    private $trainingModel;
    private $employeeModel;
    
    public function __construct() {
        if (!$this->isAuthenticated()) {
            $this->redirect('/HRFlowSn/index.php?route=auth/login');
        }
        
        // Vérifier les permissions (RH et Admin uniquement)
        $this->checkPermission(['Administrateur', 'RH']);
        
        $this->trainingModel = $this->model('Training');
        $this->employeeModel = $this->model('Employee');
    }
    
    /**
     * Liste des formations
     */
    public function index() {
        $trainings = $this->trainingModel->getAllWithDetails();
        
        $data = [
            'pageTitle' => 'Formations',
            'activeMenu' => 'trainings',
            'trainings' => $trainings
        ];
        
        $this->view('trainings/index.php', $data);
    }
    
    /**
     * Formulaire de création de formation
     */
    public function create() {
        if ($this->isPost()) {
            $trainingData = [
                'training_code' => $this->generateTrainingCode(),
                'title' => $this->post('title'),
                'description' => $this->post('description'),
                'location' => $this->post('location'),
                'start_date' => $this->post('start_date'),
                'end_date' => $this->post('end_date'),
                'status' => $this->post('status', 'Programmee')
            ];
            
            if ($this->trainingModel->create($trainingData)) {
                $this->setFlash('success', 'Formation créée avec succès');
                $this->redirect('/HRFlowSn/index.php?route=trainings');
            } else {
                $this->setFlash('error', 'Erreur lors de la création de la formation');
                $this->redirect('/HRFlowSn/index.php?route=trainings/create');
            }
        }
        
        $data = [
            'pageTitle' => 'Nouvelle Formation',
            'activeMenu' => 'trainings'
        ];
        
        $this->view('trainings/create.php', $data);
    }
    
    /**
     * Formulaire d'édition de formation
     */
    public function edit() {
        $id = $this->get('id');
        
        if (!$id) {
            $this->setFlash('error', 'ID formation manquant');
            $this->redirect('/HRFlowSn/index.php?route=trainings');
        }
        
        $training = $this->trainingModel->getById($id);
        
        if (!$training) {
            $this->setFlash('error', 'Formation non trouvée');
            $this->redirect('/HRFlowSn/index.php?route=trainings');
        }
        
        if ($this->isPost()) {
            $trainingData = [
                'title' => $this->post('title'),
                'description' => $this->post('description'),
                'location' => $this->post('location'),
                'start_date' => $this->post('start_date'),
                'end_date' => $this->post('end_date'),
                'status' => $this->post('status')
            ];
            
            if ($this->trainingModel->update($id, $trainingData)) {
                $this->setFlash('success', 'Formation mise à jour avec succès');
                $this->redirect('/HRFlowSn/index.php?route=trainings');
            } else {
                $this->setFlash('error', 'Erreur lors de la mise à jour de la formation');
            }
        }
        
        $data = [
            'pageTitle' => 'Modifier Formation',
            'activeMenu' => 'trainings',
            'training' => $training
        ];
        
        $this->view('trainings/edit.php', $data);
    }
    
    /**
     * Supprimer une formation
     */
    public function delete() {
        $id = $this->get('id');
        
        if (!$id) {
            $this->setFlash('error', 'ID formation manquant');
            $this->redirect('/HRFlowSn/index.php?route=trainings');
        }
        
        if ($this->trainingModel->delete($id)) {
            $this->setFlash('success', 'Formation supprimée avec succès');
        } else {
            $this->setFlash('error', 'Erreur lors de la suppression de la formation');
        }
        
        $this->redirect('/HRFlowSn/index.php?route=trainings');
    }
    
    /**
     * Voir les détails d'une formation
     */
    public function show() {
        $id = $this->get('id');
        
        if (!$id) {
            $this->setFlash('error', 'ID formation manquant');
            $this->redirect('/HRFlowSn/index.php?route=trainings');
        }
        
        $training = $this->trainingModel->getByIdWithDetails($id);
        $participants = $this->trainingModel->getParticipants($id);
        $employees = $this->employeeModel->getAllWithDetails();
        
        if (!$training) {
            $this->setFlash('error', 'Formation non trouvée');
            $this->redirect('/HRFlowSn/index.php?route=trainings');
        }
        
        $data = [
            'pageTitle' => 'Détails Formation',
            'activeMenu' => 'trainings',
            'training' => $training,
            'participants' => $participants,
            'employees' => $employees
        ];
        
        $this->view('trainings/view.php', $data);
    }
    
    /**
     * Ajouter un participant
     */
    public function addParticipant() {
        $trainingId = $this->post('training_id');
        $employeeId = $this->post('employee_id');
        
        if (!$trainingId || !$employeeId) {
            $this->setFlash('error', 'Paramètres manquants');
            $this->redirect('/HRFlowSn/index.php?route=trainings');
        }
        
        if ($this->trainingModel->addParticipant($trainingId, $employeeId)) {
            $this->setFlash('success', 'Participant ajouté avec succès');
        } else {
            $this->setFlash('error', 'Erreur lors de l\'ajout du participant');
        }
        
        $this->redirect('/HRFlowSn/index.php?route=trainings/show&id=' . $trainingId);
    }
    
    /**
     * Supprimer un participant
     */
    public function removeParticipant() {
        $trainingId = $this->get('training_id');
        $employeeId = $this->get('employee_id');
        
        if (!$trainingId || !$employeeId) {
            $this->setFlash('error', 'Paramètres manquants');
            $this->redirect('/HRFlowSn/index.php?route=trainings');
        }
        
        if ($this->trainingModel->removeParticipant($trainingId, $employeeId)) {
            $this->setFlash('success', 'Participant supprimé avec succès');
        } else {
            $this->setFlash('error', 'Erreur lors de la suppression du participant');
        }
        
        $this->redirect('/HRFlowSn/index.php?route=trainings/show&id=' . $trainingId);
    }
    
    /**
     * Marquer la présence
     */
    public function markAttendance() {
        $trainingId = $this->post('training_id');
        $employeeId = $this->post('employee_id');
        $attended = $this->post('attended') === '1';
        
        if (!$trainingId || !$employeeId) {
            $this->setFlash('error', 'Paramètres manquants');
            $this->redirect('/HRFlowSn/index.php?route=trainings');
        }
        
        if ($this->trainingModel->markAttendance($trainingId, $employeeId, $attended)) {
            $this->setFlash('success', 'Présence marquée avec succès');
        } else {
            $this->setFlash('error', 'Erreur lors du marquage de la présence');
        }
        
        $this->redirect('/HRFlowSn/index.php?route=trainings/show&id=' . $trainingId);
    }
    
    /**
     * Générer un code formation unique
     */
    private function generateTrainingCode() {
        $prefix = 'FRM';
        $year = date('Y');
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        return $prefix . $year . $random;
    }
}
