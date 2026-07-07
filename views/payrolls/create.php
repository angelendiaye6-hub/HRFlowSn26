<?php
$months = [
    1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
    5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
    9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
];

$currentMonth = date('n');
$currentYear = date('Y');

ob_start();
?>

<div class="page-header mb-4">
    <h1 class="fw-bold">Générer un Bulletin de Paie</h1>
    <p class="text-muted">Saisissez les variables du mois (Primes, Absences, etc.) pour calculer la paie</p>
</div>

<div class="clay-card p-4 mx-auto" style="max-width: 800px;">
    <form action="/HRFlowSn/index.php?route=payroll/create" method="POST">
        
        <h5 class="fw-bold mb-3 border-bottom pb-2">Informations Générales</h5>
        
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <label for="month" class="form-label">Mois *</label>
                <select class="form-select clay-input" id="month" name="month" required>
                    <?php foreach ($months as $num => $name): ?>
                    <option value="<?= $num ?>" <?= $currentMonth == $num ? 'selected' : '' ?>><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="year" class="form-label">Année *</label>
                <select class="form-select clay-input" id="year" name="year" required>
                    <?php for($y = $currentYear - 1; $y <= $currentYear + 1; $y++): ?>
                    <option value="<?= $y ?>" <?= $currentYear == $y ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            
            <div class="col-12 mb-3">
                <label for="employee_id" class="form-label">Employé *</label>
                <select class="form-select clay-input" id="employee_id" name="employee_id" required>
                    <option value="" disabled selected>Sélectionnez un employé</option>
                    <?php foreach ($employees as $emp): ?>
                    <option value="<?= $emp['id'] ?>">
                        <?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?> - 
                        <?= htmlspecialchars($emp['employee_code']) ?> 
                        (Salaire base: <?= number_format($emp['base_salary'], 0, ',', ' ') ?> FCFA)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <h5 class="fw-bold mb-3 border-bottom pb-2">Variables du Mois</h5>
        
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="bonus" class="form-label">Primes Exceptionnelles (FCFA)</label>
                <input type="number" class="form-control clay-input" id="bonus" name="bonus" value="0" min="0" step="1">
            </div>
            
            <div class="col-md-4 mb-3">
                <label for="overtime_hours" class="form-label">Heures Supplémentaires</label>
                <input type="number" class="form-control clay-input" id="overtime_hours" name="overtime_hours" value="0" min="0" step="0.5">
            </div>
            
            <div class="col-md-4 mb-3">
                <label for="absence_days" class="form-label">Jours d'Absence (Non justifiés)</label>
                <input type="number" class="form-control clay-input" id="absence_days" name="absence_days" value="0" min="0" max="31" step="0.5">
                <small class="text-muted d-block mt-1">Sera déduit du salaire</small>
            </div>
        </div>
        
        <div class="alert alert-info mt-3 clay-card border-0 bg-light text-dark">
            <i class="bi bi-info-circle me-2"></i> Les cotisations sociales (IPRES, CSS, CFCE) et l'impôt seront calculés automatiquement lors de la génération.
        </div>
        
        <div class="mt-4 text-end">
            <a href="/HRFlowSn/index.php?route=payroll" class="btn btn-outline-secondary clay-btn-outline me-2">
                <i class="bi bi-x-lg"></i> Annuler
            </a>
            <button type="submit" class="btn btn-primary clay-btn">
                <i class="bi bi-calculator"></i> Calculer & Générer
            </button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();

$data = [
    'pageTitle' => 'Générer un Bulletin',
    'activeMenu' => 'payroll',
    'content' => $content
];

$this->view('layouts/main.php', $data);
