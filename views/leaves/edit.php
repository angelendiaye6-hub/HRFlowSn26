<?php
ob_start();
?>

<div class="page-header mb-4">
    <h1 class="fw-bold">Modifier Demande de Congé</h1>
    <p class="text-muted">Modifier la demande de congé</p>
</div>

<div class="clay-card p-4">
    <form method="POST" action="/HRFlowSn/index.php?route=leaves/edit?id=<?= $leaveRequest['id'] ?>">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="employee_id" class="form-label">Employé *</label>
                <select class="form-select clay-input" id="employee_id" name="employee_id" required>
                    <option value="">Sélectionner un employé...</option>
                    <?php foreach ($employees as $employee): ?>
                    <option value="<?= $employee['id'] ?>" <?= $leaveRequest['employee_id'] == $employee['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?> 
                        (<?= htmlspecialchars($employee['employee_code']) ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="leave_type_id" class="form-label">Type de congé *</label>
                <select class="form-select clay-input" id="leave_type_id" name="leave_type_id" required>
                    <?php foreach ($leaveTypes as $type): ?>
                    <option value="<?= $type['id'] ?>" <?= $leaveRequest['leave_type_id'] == $type['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($type['name']) ?> (<?= $type['allowed_days'] ?> jours)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="start_date" class="form-label">Date de début *</label>
                <input type="date" class="form-control clay-input" id="start_date" name="start_date" value="<?= $leaveRequest['start_date'] ?>" required>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="end_date" class="form-label">Date de fin *</label>
                <input type="date" class="form-control clay-input" id="end_date" name="end_date" value="<?= $leaveRequest['end_date'] ?>" required>
            </div>
            
            <div class="col-12 mb-3">
                <label for="reason" class="form-label">Motif</label>
                <textarea class="form-control clay-input" id="reason" name="reason" rows="3"><?= htmlspecialchars($leaveRequest['reason']) ?></textarea>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="status" class="form-label">Statut</label>
                <select class="form-select clay-input" id="status" name="status">
                    <option value="Pending" <?= $leaveRequest['status'] === 'Pending' ? 'selected' : '' ?>>En attente</option>
                    <option value="Approved" <?= $leaveRequest['status'] === 'Approved' ? 'selected' : '' ?>>Approuvé</option>
                    <option value="Rejected" <?= $leaveRequest['status'] === 'Rejected' ? 'selected' : '' ?>>Rejeté</option>
                    <option value="Cancelled" <?= $leaveRequest['status'] === 'Cancelled' ? 'selected' : '' ?>>Annulé</option>
                </select>
            </div>
        </div>
        
        <div class="mt-4">
            <button type="submit" class="btn btn-primary clay-btn me-2">
                <i class="bi bi-check-lg"></i> Mettre à jour
            </button>
            <a href="/HRFlowSn/index.php?route=leaves" class="btn btn-outline-secondary clay-btn-outline">
                <i class="bi bi-x-lg"></i> Annuler
            </a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();

$data = [
    'pageTitle' => 'Modifier Demande de Congé',
    'activeMenu' => 'leaves',
    'content' => $content
];

$this->view('layouts/main.php', $data);
