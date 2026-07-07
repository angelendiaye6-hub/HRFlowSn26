<?php
/**
 * HRFlowSn - Evaluation Controller
 * Contrôleur pour la gestion des évaluations
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../includes/session.php';

class EvaluationController extends Controller {
    private $evaluationModel;
    private $employeeModel;
    
    public function __construct() {
        if (!$this->isAuthenticated()) {
            $this->redirect('/HRFlowSn/index.php?route=auth/login');
        }
        
        // Vérifier les permissions (RH, Admin, Manager)
        $this->checkPermission(['Administrateur', 'RH', 'Manager']);
        
        $this->evaluationModel = $this->model('Evaluation');
        $this->employeeModel = $this->model('Employee');
    }
    
    /**
     * Liste des évaluations
     */
    public function index() {
        $evaluations = $this->evaluationModel->getAllWithDetails();
        
        $data = [
            'pageTitle' => 'Évaluations',
            'activeMenu' => 'evaluations',
            'evaluations' => $evaluations
        ];
        
        $this->view('evaluations/index.php', $data);
    }
    
    /**
     * Formulaire de création d'évaluation
     */
    public function create() {
        if ($this->isPost()) {
            $evaluationData = [
                'employee_id' => $this->post('employee_id'),
                'evaluation_date' => $this->post('evaluation_date'),
                'objectives' => $this->post('objectives'),
                'comments' => $this->post('comments')
            ];
            
            if ($this->evaluationModel->create($evaluationData)) {
                $this->setFlash('success', 'Évaluation créée avec succès');
                $this->redirect('/HRFlowSn/index.php?route=evaluations');
            } else {
                $this->setFlash('error', 'Erreur lors de la création de l\'évaluation');
                $this->redirect('/HRFlowSn/index.php?route=evaluations/create');
            }
        }
        
        $employees = $this->employeeModel->getAllWithDetails();
        
        $data = [
            'pageTitle' => 'Nouvelle Évaluation',
            'activeMenu' => 'evaluations',
            'employees' => $employees
        ];
        
        $this->view('evaluations/create.php', $data);
    }
    
    /**
     * Formulaire d'édition d'évaluation
     */
    public function edit() {
        $id = $this->get('id');
        
        if (!$id) {
            $this->setFlash('error', 'ID évaluation manquant');
            $this->redirect('/HRFlowSn/index.php?route=evaluations');
        }
        
        $evaluation = $this->evaluationModel->getById($id);
        
        if (!$evaluation) {
            $this->setFlash('error', 'Évaluation non trouvée');
            $this->redirect('/HRFlowSn/index.php?route=evaluations');
        }
        
        if ($this->isPost()) {
            $evaluationData = [
                'employee_id' => $this->post('employee_id'),
                'evaluation_date' => $this->post('evaluation_date'),
                'objectives' => $this->post('objectives'),
                'comments' => $this->post('comments')
            ];
            
            if ($this->evaluationModel->update($id, $evaluationData)) {
                $this->setFlash('success', 'Évaluation mise à jour avec succès');
                $this->redirect('/HRFlowSn/index.php?route=evaluations');
            } else {
                $this->setFlash('error', 'Erreur lors de la mise à jour de l\'évaluation');
            }
        }
        
        $employees = $this->employeeModel->getAllWithDetails();
        
        $data = [
            'pageTitle' => 'Modifier Évaluation',
            'activeMenu' => 'evaluations',
            'evaluation' => $evaluation,
            'employees' => $employees
        ];
        
        $this->view('evaluations/edit.php', $data);
    }
    
    /**
     * Supprimer une évaluation
     */
    public function delete() {
        $id = $this->get('id');
        
        if (!$id) {
            $this->setFlash('error', 'ID évaluation manquant');
            $this->redirect('/HRFlowSn/index.php?route=evaluations');
        }
        
        if ($this->evaluationModel->delete($id)) {
            $this->setFlash('success', 'Évaluation supprimée avec succès');
        } else {
            $this->setFlash('error', 'Erreur lors de la suppression de l\'évaluation');
        }
        
        $this->redirect('/HRFlowSn/index.php?route=evaluations');
    }
    
    /**
     * Voir les détails d'une évaluation
     */
    public function show() {
        $id = $this->get('id');
        
        if (!$id) {
            $this->setFlash('error', 'ID évaluation manquant');
            $this->redirect('/HRFlowSn/index.php?route=evaluations');
        }
        
        $evaluation = $this->evaluationModel->getByIdWithDetails($id);
        
        if (!$evaluation) {
            $this->setFlash('error', 'Évaluation non trouvée');
            $this->redirect('/HRFlowSn/index.php?route=evaluations');
        }
        
        $data = [
            'pageTitle' => 'Détails Évaluation',
            'activeMenu' => 'evaluations',
            'evaluation' => $evaluation
        ];
        
        $this->view('evaluations/view.php', $data);
    }
    
    /**
     * Attribuer une note à l'évaluation
     */
    public function score() {
        if (!$this->isPost()) {
            $this->redirect('/HRFlowSn/index.php?route=evaluations');
        }
        
        $id = $this->post('id');
        $score = $this->post('score');
        
        if (!$id || $score === '') {
            $this->setFlash('error', 'ID évaluation ou note manquant');
            $this->redirect('/HRFlowSn/index.php?route=evaluations');
        }
        
        if ($this->evaluationModel->update($id, ['score' => $score])) {
            $this->setFlash('success', 'Note attribuée avec succès');
        } else {
            $this->setFlash('error', 'Erreur lors de l\'attribution de la note');
        }
        
        $this->redirect('/HRFlowSn/index.php?route=evaluations/show&id=' . $id);
    }
}
