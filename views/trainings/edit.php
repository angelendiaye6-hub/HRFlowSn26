<?php
ob_start();
?>

<div class="page-header mb-4">
    <h1 class="fw-bold">Modifier Formation</h1>
    <p class="text-muted">Modifier la formation</p>
</div>

<div class="clay-card p-4">
    <form method="POST" action="/HRFlowSn/index.php?route=trainings/edit?id=<?= $training['id'] ?>">
        <div class="row">
            <div class="col-12 mb-3">
                <label for="title" class="form-label">Titre *</label>
                <input type="text" class="form-control clay-input" id="title" name="title" value="<?= htmlspecialchars($training['title']) ?>" required>
            </div>
            
            <div class="col-12 mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control clay-input" id="description" name="description" rows="3"><?= htmlspecialchars($training['description']) ?></textarea>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="location" class="form-label">Lieu</label>
                <input type="text" class="form-control clay-input" id="location" name="location" value="<?= htmlspecialchars($training['location']) ?>">
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="status" class="form-label">Statut</label>
                <select class="form-select clay-input" id="status" name="status">
                    <option value="Programmee" <?= $training['status'] === 'Programmee' ? 'selected' : '' ?>>Programmée</option>
                    <option value="En cours" <?= $training['status'] === 'En cours' ? 'selected' : '' ?>>En cours</option>
                    <option value="Terminee" <?= $training['status'] === 'Terminee' ? 'selected' : '' ?>>Terminée</option>
                </select>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="start_date" class="form-label">Date de début</label>
                <input type="date" class="form-control clay-input" id="start_date" name="start_date" value="<?= $training['start_date'] ?>">
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="end_date" class="form-label">Date de fin</label>
                <input type="date" class="form-control clay-input" id="end_date" name="end_date" value="<?= $training['end_date'] ?>">
            </div>
        </div>
        
        <div class="mt-4">
            <button type="submit" class="btn btn-primary clay-btn me-2">
                <i class="bi bi-check-lg"></i> Mettre à jour
            </button>
            <a href="/HRFlowSn/index.php?route=trainings" class="btn btn-outline-secondary clay-btn-outline">
                <i class="bi bi-x-lg"></i> Annuler
            </a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();

$data = [
    'pageTitle' => 'Modifier Formation',
    'activeMenu' => 'trainings',
    'content' => $content
];

$this->view('layouts/main.php', $data);
