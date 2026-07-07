<?php
ob_start();
?>

<div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="fw-bold">Contrats</h1>
        <p class="text-muted">Gestion des contrats de travail</p>
    </div>
    <a href="/HRFlowSn/index.php?route=contracts/create" class="btn btn-primary clay-btn">
        <i class="bi bi-plus-lg"></i> Nouveau Contrat
    </a>
</div>

<div class="clay-card p-4">
    <div class="table-container">
        <table class="clay-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Employé</th>
                    <th>Type</th>
                    <th>Salaire</th>
                    <th>Début</th>
                    <th>Fin</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contracts as $contract): ?>
                <tr>
                    <td><span class="badge badge-primary"><?= htmlspecialchars($contract['contract_code']) ?></span></td>
                    <td>
                        <div class="fw-semibold"><?= htmlspecialchars($contract['employee_name']) ?></div>
                        <small class="text-muted"><?= htmlspecialchars($contract['employee_code']) ?></small>
                    </td>
                    <td><?= htmlspecialchars($contract['contract_type']) ?></td>
                    <td><?= number_format($contract['salary'], 2, ',', ' ') ?> FCFA</td>
                    <td><?= date('d/m/Y', strtotime($contract['start_date'])) ?></td>
                    <td><?= $contract['end_date'] ? date('d/m/Y', strtotime($contract['end_date'])) : '-' ?></td>
                    <td>
                        <?php
                        $badgeClass = 'badge-success';
                        if ($contract['status'] === 'Expire') $badgeClass = 'badge-danger';
                        elseif ($contract['status'] === 'Resilie') $badgeClass = 'badge-secondary';
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($contract['status']) ?></span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="/HRFlowSn/index.php?route=contracts/show&id=<?= $contract['id'] ?>" class="btn btn-sm btn-outline-primary" title="Voir">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/HRFlowSn/index.php?route=contracts/edit&id=<?= $contract['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="/HRFlowSn/index.php?route=contracts/delete&id=<?= $contract['id'] ?>" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce contrat ?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($contracts)): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">Aucun contrat trouvé</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();

$data = [
    'pageTitle' => 'Contrats',
    'activeMenu' => 'contracts',
    'content' => $content
];

$this->view('layouts/main.php', $data);
