<?php
ob_start();
?>

<div class="page-header mb-4">
    <h1 class="fw-bold">Nouvelle Demande de Congé</h1>
    <p class="text-muted">Créer une nouvelle demande de congé</p>
</div>

<div class="clay-card p-4">
    <form method="POST" action="/HRFlowSn/index.php?route=leaves/create">
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
                <label for="leave_type_id" class="form-label">Type de congé *</label>
                <select class="form-select clay-input" id="leave_type_id" name="leave_type_id" required>
                    <?php foreach ($leaveTypes as $type): ?>
                    <option value="<?= $type['id'] ?>" data-days="<?= $type['allowed_days'] ?>">
                        <?= htmlspecialchars($type['name']) ?> (<?= $type['allowed_days'] ?> jours)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="start_date" class="form-label">Date de début *</label>
                <input type="date" class="form-control clay-input" id="start_date" name="start_date" required>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="end_date" class="form-label">Date de fin *</label>
                <input type="date" class="form-control clay-input" id="end_date" name="end_date" required>
            </div>
            
            <div class="col-12 mb-3">
                <label for="reason" class="form-label">Motif</label>
                <textarea class="form-control clay-input" id="reason" name="reason" rows="3"></textarea>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="status" class="form-label">Statut</label>
                <select class="form-select clay-input" id="status" name="status">
                    <option value="Pending">En attente</option>
                    <option value="Approved">Approuvé</option>
                    <option value="Rejected">Rejeté</option>
                    <option value="Cancelled">Annulé</option>
                </select>
            </div>
        </div>
        
        <div class="mt-4">
            <button type="submit" class="btn btn-primary clay-btn me-2">
                <i class="bi bi-check-lg"></i> Enregistrer
            </button>
            <a href="/HRFlowSn/index.php?route=leaves" class="btn btn-outline-secondary clay-btn-outline">
                <i class="bi bi-x-lg"></i> Annuler
            </a>
        </div>
    </form>
</div>

<?php
$extraJs = <<<JS
<script>
document.addEventListener('DOMContentLoaded', function() {
    var leaveTypeSelect = document.getElementById('leave_type_id');
    var startDateInput = document.getElementById('start_date');
    var endDateInput = document.getElementById('end_date');
    
    function calculateEndDate() {
        var typeId = leaveTypeSelect.value;
        var startDateVal = startDateInput.value;
        
        if (typeId && startDateVal) {
            var selectedOption = leaveTypeSelect.options[leaveTypeSelect.selectedIndex];
            var days = parseInt(selectedOption.getAttribute('data-days'), 10) || 0;
            
            if (days > 0) {
                var startDate = new Date(startDateVal);
                startDate.setDate(startDate.getDate() + days - 1); // -1 because start date counts as 1 day
                
                var yyyy = startDate.getFullYear();
                var mm = String(startDate.getMonth() + 1).padStart(2, '0');
                var dd = String(startDate.getDate()).padStart(2, '0');
                
                endDateInput.value = yyyy + '-' + mm + '-' + dd;
            }
        }
    }
    
    leaveTypeSelect.addEventListener('change', calculateEndDate);
    startDateInput.addEventListener('change', calculateEndDate);
});
</script>
JS;

$content = ob_get_clean();

$data = [
    'pageTitle' => 'Nouvelle Demande de Congé',
    'activeMenu' => 'leaves',
    'content' => $content,
    'extraJs' => $extraJs
];

$this->view('layouts/main.php', $data);
