<?php
require_once __DIR__ . '/../../models/Contract.php';

$contractModel = new Contract();
$contract = $contractModel->getByIdWithDetails($contract['id']);

ob_start();
?>

<div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="fw-bold">Détails Contrat</h1>
        <p class="text-muted">Informations détaillées du contrat</p>
    </div>
    <div>
        <a href="/HRFlowSn/index.php?route=contracts/edit&id=<?= $contract['id'] ?>" class="btn btn-primary clay-btn me-2">
            <i class="bi bi-pencil"></i> Modifier
        </a>
        <a href="/HRFlowSn/index.php?route=contracts" class="btn btn-outline-secondary clay-btn-outline">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="clay-card p-4">
            <h5 class="fw-bold mb-4"><i class="bi bi-file-earmark-text"></i> Informations du Contrat</h5>
            
            <div class="mb-3">
                <label class="text-muted small">Code Contrat</label>
                <div class="fw-semibold"><span class="badge badge-primary"><?= htmlspecialchars($contract['contract_code']) ?></span></div>
            </div>
            
            <div class="mb-3">
                <label class="text-muted small">Employé</label>
                <div class="fw-semibold">
                    <?= htmlspecialchars($contract['employee_name']) ?>
                    <small class="text-muted">(<?= htmlspecialchars($contract['employee_code']) ?>)</small>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="text-muted small">Type de contrat</label>
                    <div class="fw-semibold"><?= htmlspecialchars($contract['contract_type']) ?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="text-muted small">Statut</label>
                    <div>
                        <?php
                        $badgeClass = 'badge-success';
                        if ($contract['status'] === 'Expire') $badgeClass = 'badge-danger';
                        elseif ($contract['status'] === 'Resilie') $badgeClass = 'badge-secondary';
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($contract['status']) ?></span>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="text-muted small">Date de début</label>
                    <div class="fw-semibold"><?= date('d/m/Y', strtotime($contract['start_date'])) ?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="text-muted small">Date de fin</label>
                    <div class="fw-semibold"><?= $contract['end_date'] ? date('d/m/Y', strtotime($contract['end_date'])) : 'CDI (Indéterminé)' ?></div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="text-muted small">Période d'essai</label>
                    <div><?= $contract['trial_period'] ?> jours</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="text-muted small">Date de signature</label>
                    <div><?= $contract['signed_at'] ? date('d/m/Y', strtotime($contract['signed_at'])) : '-' ?></div>
                </div>
            </div>
            
            <hr>
            
            <div class="mb-3">
                <label class="text-muted small">Date de création</label>
                <div><?= date('d/m/Y H:i', strtotime($contract['created_at'])) ?></div>
            </div>
            
            <div class="mb-3">
                <label class="text-muted small">Dernière mise à jour</label>
                <div><?= date('d/m/Y H:i', strtotime($contract['updated_at'])) ?></div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="clay-card p-4">
            <h5 class="fw-bold mb-4"><i class="bi bi-currency-dollar"></i> Informations Salariales</h5>
            
            <div class="mb-3">
                <label class="text-muted small">Salaire de base</label>
                <div class="fw-semibold text-success fs-4"><?= number_format($contract['salary'], 2, ',', ' ') ?> FCFA</div>
            </div>
            
            <hr>
            
            <div class="mb-3">
                <label class="text-muted small">Département</label>
                <div class="fw-semibold"><?= htmlspecialchars($contract['department_name'] ?? '-') ?></div>
            </div>
            
            <div class="mb-3">
                <label class="text-muted small">Poste</label>
                <div class="fw-semibold"><?= htmlspecialchars($contract['position_name'] ?? '-') ?></div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

$data = [
    'pageTitle' => 'Détails Contrat',
    'activeMenu' => 'contracts',
    'content' => $content
];

$this->view('layouts/main.php', $data);
