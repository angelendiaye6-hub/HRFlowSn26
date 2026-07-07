<?php
require_once __DIR__ . '/../../models/Department.php';
require_once __DIR__ . '/../../models/Position.php';

$departmentModel = new Department();
$positionModel = new Position();

$departments = $departmentModel->getAll();
$positions = $positionModel->getAll();

ob_start();
?>

<div class="page-header mb-4">
    <h1 class="fw-bold">Modifier Collaborateur</h1>
    <p class="text-muted">Modifier les informations de l'employé</p>
</div>

<div class="clay-card p-4">
    <form method="POST" action="/HRFlowSn/index.php?route=employees/edit?id=<?= $employee['id'] ?>">
        <div class="row">
            <!-- Informations personnelles -->
            <div class="col-12 mb-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-person"></i> Informations Personnelles</h5>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="first_name" class="form-label">Prénom *</label>
                <input type="text" class="form-control clay-input" id="first_name" name="first_name" value="<?= htmlspecialchars($employee['first_name']) ?>" required>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="last_name" class="form-label">Nom *</label>
                <input type="text" class="form-control clay-input" id="last_name" name="last_name" value="<?= htmlspecialchars($employee['last_name']) ?>" required>
            </div>
            
            <div class="col-md-4 mb-3">
                <label for="gender" class="form-label">Genre *</label>
                <select class="form-select clay-input" id="gender" name="gender" required>
                    <option value="Male" <?= $employee['gender'] === 'Male' ? 'selected' : '' ?>>Masculin</option>
                    <option value="Female" <?= $employee['gender'] === 'Female' ? 'selected' : '' ?>>Féminin</option>
                </select>
            </div>
            
            <div class="col-md-4 mb-3">
                <label for="birth_date" class="form-label">Date de naissance *</label>
                <input type="date" class="form-control clay-input" id="birth_date" name="birth_date" value="<?= $employee['birth_date'] ?>" required>
            </div>
            
            <div class="col-md-4 mb-3">
                <label for="birth_place" class="form-label">Lieu de naissance</label>
                <input type="text" class="form-control clay-input" id="birth_place" name="birth_place" value="<?= htmlspecialchars($employee['birth_place']) ?>">
            </div>
            
            <div class="col-md-4 mb-3">
                <label for="nationality" class="form-label">Nationalité</label>
                <input type="text" class="form-control clay-input" id="nationality" name="nationality" value="<?= htmlspecialchars($employee['nationality']) ?>">
            </div>
            
            <div class="col-md-4 mb-3">
                <label for="marital_status" class="form-label">Situation matrimoniale</label>
                <select class="form-select clay-input" id="marital_status" name="marital_status">
                    <option value="">Sélectionner...</option>
                    <option value="Single" <?= $employee['marital_status'] === 'Single' ? 'selected' : '' ?>>Célibataire</option>
                    <option value="Married" <?= $employee['marital_status'] === 'Married' ? 'selected' : '' ?>>Marié(e)</option>
                    <option value="Divorced" <?= $employee['marital_status'] === 'Divorced' ? 'selected' : '' ?>>Divorcé(e)</option>
                    <option value="Widowed" <?= $employee['marital_status'] === 'Widowed' ? 'selected' : '' ?>>Veuf/Veuve</option>
                </select>
            </div>
            
            <div class="col-md-4 mb-3">
                <label for="phone" class="form-label">Téléphone</label>
                <input type="text" class="form-control clay-input" id="phone" name="phone" value="<?= htmlspecialchars($employee['phone']) ?>">
            </div>
            
            <div class="col-12 mb-3">
                <label for="address" class="form-label">Adresse</label>
                <textarea class="form-control clay-input" id="address" name="address" rows="2"><?= htmlspecialchars($employee['address']) ?></textarea>
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
                    <?php foreach ($departments as $dept): ?>
                    <option value="<?= $dept['id'] ?>" <?= $employee['department_id'] == $dept['id'] ? 'selected' : '' ?>><?= htmlspecialchars($dept['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="position_id" class="form-label">Poste *</label>
                <select class="form-select clay-input" id="position_id" name="position_id" required>
                    <?php foreach ($positions as $pos): ?>
                    <option value="<?= $pos['id'] ?>" <?= $employee['position_id'] == $pos['id'] ? 'selected' : '' ?>><?= htmlspecialchars($pos['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="qualification" class="form-label">Qualification</label>
                <input type="text" class="form-control clay-input" id="qualification" name="qualification" value="<?= htmlspecialchars($employee['qualification']) ?>">
            </div>
            

            <div class="col-md-6 mb-3">
                <label for="status" class="form-label">Statut</label>
                <select class="form-select clay-input" id="status" name="status">
                    <option value="Active" <?= $employee['status'] === 'Active' ? 'selected' : '' ?>>Actif</option>
                    <option value="On Leave" <?= $employee['status'] === 'On Leave' ? 'selected' : '' ?>>En congé</option>
                    <option value="Suspended" <?= $employee['status'] === 'Suspended' ? 'selected' : '' ?>>Suspendu</option>
                    <option value="Resigned" <?= $employee['status'] === 'Resigned' ? 'selected' : '' ?>>Démissionné</option>
                    <option value="Retired" <?= $employee['status'] === 'Retired' ? 'selected' : '' ?>>Retraité</option>
                </select>
            </div>
        </div>
        
        <div class="mt-4">
            <button type="submit" class="btn btn-primary clay-btn me-2">
                <i class="bi bi-check-lg"></i> Mettre à jour
            </button>
            <a href="/HRFlowSn/index.php?route=employees" class="btn btn-outline-secondary clay-btn-outline">
                <i class="bi bi-x-lg"></i> Annuler
            </a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();

$data = [
    'pageTitle' => 'Modifier Collaborateur',
    'activeMenu' => 'employees',
    'content' => $content
];

$this->view('layouts/main.php', $data);
