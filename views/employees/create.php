<?php
require_once __DIR__ . '/../../models/Department.php';
require_once __DIR__ . '/../../models/Position.php';
require_once __DIR__ . '/../../models/Role.php';

$departmentModel = new Department();
$positionModel = new Position();
$roleModel = new Role();

$departments = $departmentModel->getAll();
$positions = $positionModel->getAll();
$roles = $roleModel->getAll();

ob_start();
?>

<div class="page-header mb-4">
    <h1 class="fw-bold">Nouveau Collaborateur</h1>
    <p class="text-muted">Ajouter un nouvel employé</p>
</div>

<div class="clay-card p-4">
    <form method="POST" action="/HRFlowSn/index.php?route=employees/create">
        <div class="row">
            <!-- Informations de connexion -->
            <div class="col-12 mb-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-person-lock"></i> Informations de Connexion</h5>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email *</label>
                <input type="email" class="form-control clay-input" id="email" name="email" value="<?= htmlspecialchars(get_old_input('email')) ?>" required>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Mot de passe *</label>
                <input type="password" class="form-control clay-input" id="password" name="password" required minlength="6">
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="role_id" class="form-label">Rôle *</label>
                <select class="form-select clay-input" id="role_id" name="role_id" required>
                    <?php foreach ($roles as $role): ?>
                    <option value="<?= $role['id'] ?>" <?= get_old_input('role_id') == $role['id'] ? 'selected' : '' ?>><?= htmlspecialchars($role['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="text-muted d-block mt-1"><i class="bi bi-info-circle"></i> Ce rôle définit uniquement les <strong>droits d'accès au logiciel</strong>, il est indépendant de l'intitulé du poste.</small>
            </div>
        </div>
        
        <hr class="my-4">
        
        <div class="row">
            <!-- Informations personnelles -->
            <div class="col-12 mb-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-person"></i> Informations Personnelles</h5>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="first_name" class="form-label">Prénom *</label>
                <input type="text" class="form-control clay-input" id="first_name" name="first_name" value="<?= htmlspecialchars(get_old_input('first_name')) ?>" required>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="last_name" class="form-label">Nom *</label>
                <input type="text" class="form-control clay-input" id="last_name" name="last_name" value="<?= htmlspecialchars(get_old_input('last_name')) ?>" required>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="gender" class="form-label">Genre *</label>
                <select class="form-select clay-input" id="gender" name="gender" required>
                    <option value="Male" <?= get_old_input('gender') === 'Male' ? 'selected' : '' ?>>Masculin</option>
                    <option value="Female" <?= get_old_input('gender') === 'Female' ? 'selected' : '' ?>>Féminin</option>
                </select>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="birth_date" class="form-label">Date de naissance *</label>
                <input type="date" class="form-control clay-input" id="birth_date" name="birth_date" value="<?= htmlspecialchars(get_old_input('birth_date')) ?>" required>
            </div>
        </div>
        
        <hr class="my-4">
        
        <div class="row">
            <!-- Informations professionnelles -->
            <div class="col-12 mb-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-briefcase"></i> Informations Professionnelles</h5>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="department_id" class="form-label">Département *</label>
                <select class="form-select clay-input" id="department_id" name="department_id" required>
                    <option value="">Sélectionner...</option>
                    <?php foreach ($departments as $dept): ?>
                    <option value="<?= $dept['id'] ?>" <?= get_old_input('department_id') == $dept['id'] ? 'selected' : '' ?>><?= htmlspecialchars($dept['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="position_id" class="form-label">Poste *</label>
                <select class="form-select clay-input" id="position_id" name="position_id" required>
                    <option value="">Sélectionner...</option>
                    <?php foreach ($positions as $pos): ?>
                    <option value="<?= $pos['id'] ?>" <?= get_old_input('position_id') == $pos['id'] ? 'selected' : '' ?>><?= htmlspecialchars($pos['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            

        </div>
        
        <div class="mt-4">
            <button type="submit" class="btn btn-primary clay-btn me-2">
                <i class="bi bi-check-lg"></i> Enregistrer
            </button>
            <a href="/HRFlowSn/index.php?route=employees" class="btn btn-outline-secondary clay-btn-outline">
                <i class="bi bi-x-lg"></i> Annuler
            </a>
        </div>
    </form>
</div>

<?php
clear_old_input();
$content = ob_get_clean();

$data = [
    'pageTitle' => 'Nouveau Collaborateur',
    'activeMenu' => 'employees',
    'content' => $content
];

$this->view('layouts/main.php', $data);
