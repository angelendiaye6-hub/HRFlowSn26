<?php
ob_start();
?>

<div class="page-header mb-4">
    <h1 class="fw-bold">Modifier Contrat</h1>
    <p class="text-muted">Modifier les informations du contrat</p>
</div>

<div class="clay-card p-4">
    <form method="POST" action="/HRFlowSn/index.php?route=contracts/edit?id=<?= $contract['id'] ?>">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="employee_id" class="form-label">Employé *</label>
                <select class="form-select clay-input" id="employee_id" name="employee_id" required>
                    <option value="">Sélectionner un employé...</option>
                    <?php foreach ($employees as $employee): ?>
                    <option value="<?= $employee['id'] ?>" <?= $contract['employee_id'] == $employee['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?> 
                        (<?= htmlspecialchars($employee['employee_code']) ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="contract_type" class="form-label">Type de contrat *</label>
                <select class="form-select clay-input" id="contract_type" name="contract_type" required>
                    <option value="CDI" <?= $contract['contract_type'] === 'CDI' ? 'selected' : '' ?>>CDI</option>
                    <option value="CDD" <?= $contract['contract_type'] === 'CDD' ? 'selected' : '' ?>>CDD</option>
                    <option value="Stage" <?= $contract['contract_type'] === 'Stage' ? 'selected' : '' ?>>Stage</option>
                    <option value="Essai" <?= $contract['contract_type'] === 'Essai' ? 'selected' : '' ?>>Essai</option>
                </select>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="salary" class="form-label">Salaire *</label>
                <input type="number" class="form-control clay-input" id="salary" name="salary" step="0.01" value="<?= $contract['salary'] ?>" required>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="start_date" class="form-label">Date de début *</label>
                <input type="date" class="form-control clay-input" id="start_date" name="start_date" value="<?= $contract['start_date'] ?>" required>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="end_date" class="form-label">Date de fin</label>
                <input type="date" class="form-control clay-input" id="end_date" name="end_date" value="<?= $contract['end_date'] ?>">
                <small class="text-muted">Laisser vide pour CDI</small>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="trial_period" class="form-label">Période d'essai (jours)</label>
                <input type="number" class="form-control clay-input" id="trial_period" name="trial_period" value="<?= $contract['trial_period'] ?>" min="0">
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="signed_at" class="form-label">Date de signature</label>
                <input type="date" class="form-control clay-input" id="signed_at" name="signed_at" value="<?= $contract['signed_at'] ?>">
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="status" class="form-label">Statut</label>
                <select class="form-select clay-input" id="status" name="status">
                    <option value="En cours" <?= $contract['status'] === 'En cours' ? 'selected' : '' ?>>En cours</option>
                    <option value="Expire" <?= $contract['status'] === 'Expire' ? 'selected' : '' ?>>Expiré</option>
                    <option value="Resilie" <?= $contract['status'] === 'Resilie' ? 'selected' : '' ?>>Résilié</option>
                </select>
            </div>
        </div>
        
        <div class="mt-4">
            <button type="submit" class="btn btn-primary clay-btn me-2">
                <i class="bi bi-check-lg"></i> Mettre à jour
            </button>
            <a href="/HRFlowSn/index.php?route=contracts" class="btn btn-outline-secondary clay-btn-outline">
                <i class="bi bi-x-lg"></i> Annuler
            </a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();

$data = [
    'pageTitle' => 'Modifier Contrat',
    'activeMenu' => 'contracts',
    'content' => $content
];

$this->view('layouts/main.php', $data);
