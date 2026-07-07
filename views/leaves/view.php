<?php
require_once __DIR__ . '/../../models/LeaveRequest.php';

$leaveRequestModel = new LeaveRequest();
$leaveRequest = $leaveRequestModel->getByIdWithDetails($leaveRequest['id']);

$startDate = new DateTime($leaveRequest['start_date']);
$endDate = new DateTime($leaveRequest['end_date']);
$duration = $startDate->diff($endDate)->days + 1;

ob_start();
?>

<div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="fw-bold">Détails Demande de Congé</h1>
        <p class="text-muted">Informations détaillées de la demande</p>
    </div>
    <div>
        <?php if ($leaveRequest['status'] === 'Pending'): ?>
        <a href="/HRFlowSn/index.php?route=leaves/approve&id=<?= $leaveRequest['id'] ?>" class="btn btn-success clay-btn me-2" onclick="return confirm('Approuver cette demande ?')">
            <i class="bi bi-check-lg"></i> Approuver
        </a>
        <a href="/HRFlowSn/index.php?route=leaves/reject&id=<?= $leaveRequest['id'] ?>" class="btn btn-danger clay-btn me-2" onclick="return confirm('Rejeter cette demande ?')">
            <i class="bi bi-x-lg"></i> Rejeter
        </a>
        <?php endif; ?>
        <a href="/HRFlowSn/index.php?route=leaves/edit&id=<?= $leaveRequest['id'] ?>" class="btn btn-primary clay-btn me-2">
            <i class="bi bi-pencil"></i> Modifier
        </a>
        <a href="/HRFlowSn/index.php?route=leaves" class="btn btn-outline-secondary clay-btn-outline">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="clay-card p-4">
            <h5 class="fw-bold mb-4"><i class="bi bi-calendar-check"></i> Informations de la Demande</h5>
            
            <div class="mb-3">
                <label class="text-muted small">Employé</label>
                <div class="fw-semibold">
                    <?= htmlspecialchars($leaveRequest['employee_name']) ?>
                    <small class="text-muted">(<?= htmlspecialchars($leaveRequest['employee_code']) ?>)</small>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="text-muted small">Département</label>
                <div><?= htmlspecialchars($leaveRequest['department_name'] ?? '-') ?></div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="text-muted small">Type de congé</label>
                    <div class="fw-semibold"><?= htmlspecialchars($leaveRequest['leave_type_name']) ?></div>
                    <small class="text-muted"><?= $leaveRequest['allowed_days'] ?> jours autorisés</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="text-muted small">Statut</label>
                    <div>
                        <?php
                        $badgeClass = 'badge-warning';
                        if ($leaveRequest['status'] === 'Approved') $badgeClass = 'badge-success';
                        elseif ($leaveRequest['status'] === 'Rejected') $badgeClass = 'badge-danger';
                        elseif ($leaveRequest['status'] === 'Cancelled') $badgeClass = 'badge-secondary';
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($leaveRequest['status']) ?></span>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="text-muted small">Date de début</label>
                    <div class="fw-semibold"><?= date('d/m/Y', strtotime($leaveRequest['start_date'])) ?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="text-muted small">Date de fin</label>
                    <div class="fw-semibold"><?= date('d/m/Y', strtotime($leaveRequest['end_date'])) ?></div>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="text-muted small">Durée</label>
                <div class="fw-semibold"><?= $duration ?> jour(s)</div>
            </div>
            
            <div class="mb-3">
                <label class="text-muted small">Motif</label>
                <div><?= htmlspecialchars($leaveRequest['reason'] ?: 'Non spécifié') ?></div>
            </div>
            
            <hr>
            
            <div class="mb-3">
                <label class="text-muted small">Date de création</label>
                <div><?= date('d/m/Y H:i', strtotime($leaveRequest['created_at'])) ?></div>
            </div>
            
            <div class="mb-3">
                <label class="text-muted small">Dernière mise à jour</label>
                <div><?= date('d/m/Y H:i', strtotime($leaveRequest['updated_at'])) ?></div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="clay-card p-4">
            <h5 class="fw-bold mb-4"><i class="bi bi-info-circle"></i> Résumé</h5>
            
            <div class="mb-3">
                <label class="text-muted small">Type</label>
                <div class="fw-semibold"><?= htmlspecialchars($leaveRequest['leave_type_name']) ?></div>
            </div>
            
            <div class="mb-3">
                <label class="text-muted small">Jours demandés</label>
                <div class="fw-semibold fs-4"><?= $duration ?> / <?= $leaveRequest['allowed_days'] ?></div>
            </div>
            
            <?php if ($duration > $leaveRequest['allowed_days']): ?>
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> Dépassement des jours autorisés
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

$data = [
    'pageTitle' => 'Détails Demande de Congé',
    'activeMenu' => 'leaves',
    'content' => $content
];

$this->view('layouts/main.php', $data);
