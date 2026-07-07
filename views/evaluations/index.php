<?php
ob_start();
?>

<div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="fw-bold">Évaluations</h1>
        <p class="text-muted">Gestion des évaluations des employés</p>
    </div>
    <a href="/HRFlowSn/index.php?route=evaluations/create" class="btn btn-primary clay-btn">
        <i class="bi bi-plus-lg"></i> Nouvelle Évaluation
    </a>
</div>

<div class="clay-card p-4">
    <div class="table-container">
        <table class="clay-table">
            <thead>
                <tr>
                    <th>Employé</th>
                    <th>Département</th>
                    <th>Poste</th>
                    <th>Date d'évaluation</th>
                    <th>Score</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($evaluations as $evaluation): ?>
                <tr>
                    <td>
                        <div class="fw-semibold"><?= htmlspecialchars($evaluation['employee_name']) ?></div>
                        <small class="text-muted"><?= htmlspecialchars($evaluation['employee_code']) ?></small>
                    </td>
                    <td><?= htmlspecialchars($evaluation['department_name']) ?></td>
                    <td><?= htmlspecialchars($evaluation['position_name']) ?></td>
                    <td><?= $evaluation['evaluation_date'] ? date('d/m/Y', strtotime($evaluation['evaluation_date'])) : '-' ?></td>
                    <td>
                        <?php if ($evaluation['score'] !== null): ?>
                        <span class="badge badge-<?= $evaluation['score'] >= 80 ? 'success' : ($evaluation['score'] >= 60 ? 'warning' : 'danger') ?>">
                            <?= $evaluation['score'] ?>/100
                        </span>
                        <?php else: ?>
                        <span class="badge badge-secondary">Non noté</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="/HRFlowSn/index.php?route=evaluations/show&id=<?= $evaluation['id'] ?>" class="btn btn-sm btn-outline-primary" title="Voir">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/HRFlowSn/index.php?route=evaluations/edit&id=<?= $evaluation['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="/HRFlowSn/index.php?route=evaluations/delete&id=<?= $evaluation['id'] ?>" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette évaluation ?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($evaluations)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Aucune évaluation trouvée</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();

$data = [
    'pageTitle' => 'Évaluations',
    'activeMenu' => 'evaluations',
    'content' => $content
];

$this->view('layouts/main.php', $data);
