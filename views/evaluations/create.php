<?php
ob_start();
?>

<div class="page-header mb-4">
    <h1 class="fw-bold">Nouvelle Évaluation</h1>
    <p class="text-muted">Créer une nouvelle évaluation</p>
</div>

<div class="clay-card p-4">
    <form method="POST" action="/HRFlowSn/index.php?route=evaluations/create">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="employee_id" class="form-label">Employé *</label>
                <select class="form-select clay-input" id="employee_id" name="employee_id" required>
                    <option value="">Sélectionner un employé...</option>
                    <?php foreach ($employees as $employee): ?>
                    <option value="<?= $employee['id'] ?>">
                        <?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?> 
                        (<?= htmlspecialchars($employee['employee_code']) ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="evaluation_date" class="form-label">Date d'évaluation</label>
                <input type="date" class="form-control clay-input" id="evaluation_date" name="evaluation_date">
            </div>
            

            
            <div class="col-12 mb-3">
                <label for="objectives" class="form-label">Objectifs</label>
                <textarea class="form-control clay-input" id="objectives" name="objectives" rows="3" placeholder="Lister les objectifs de l'évaluation..."></textarea>
            </div>
            
            <div class="col-12 mb-3">
                <label for="comments" class="form-label">Commentaires</label>
                <textarea class="form-control clay-input" id="comments" name="comments" rows="3" placeholder="Commentaires et observations..."></textarea>
            </div>
        </div>
        
        <div class="mt-4">
            <button type="submit" class="btn btn-primary clay-btn me-2">
                <i class="bi bi-check-lg"></i> Enregistrer
            </button>
            <a href="/HRFlowSn/index.php?route=evaluations" class="btn btn-outline-secondary clay-btn-outline">
                <i class="bi bi-x-lg"></i> Annuler
            </a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();

$data = [
    'pageTitle' => 'Nouvelle Évaluation',
    'activeMenu' => 'evaluations',
    'content' => $content
];

$this->view('layouts/main.php', $data);
