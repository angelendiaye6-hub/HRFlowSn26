<?php
ob_start();
?>

<div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="fw-bold">Formations</h1>
        <p class="text-muted">Gestion des formations</p>
    </div>
    <a href="/HRFlowSn/index.php?route=trainings/create" class="btn btn-primary clay-btn">
        <i class="bi bi-plus-lg"></i> Nouvelle Formation
    </a>
</div>

<div class="clay-card p-4">
    <div class="table-container">
        <table class="clay-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Titre</th>
                    <th>Lieu</th>
                    <th>Dates</th>
                    <th>Participants</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trainings as $training): ?>
                <tr>
                    <td><span class="badge badge-primary"><?= htmlspecialchars($training['training_code'] ?? '-') ?></span></td>
                    <td>
                        <div class="fw-semibold"><?= htmlspecialchars($training['title']) ?></div>
                        <small class="text-muted"><?= htmlspecialchars(substr($training['description'] ?: '-', 0, 50)) ?></small>
                    </td>
                    <td><?= htmlspecialchars($training['location'] ?: '-') ?></td>
                    <td>
                        <?php if ($training['start_date']): ?>
                        <small><?= date('d/m/Y', strtotime($training['start_date'])) ?></small>
                        <br>
                        <small class="text-muted">au <?= $training['end_date'] ? date('d/m/Y', strtotime($training['end_date'])) : '-' ?></small>
                        <?php else: ?>
                        -
                        <?php endif; ?>
                    </td>
                    <td><?= $training['participant_count'] ?></td>
                    <td>
                        <?php
                        $badgeClass = 'badge-primary';
                        if ($training['status'] === 'En cours') $badgeClass = 'badge-warning';
                        elseif ($training['status'] === 'Terminee') $badgeClass = 'badge-success';
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($training['status']) ?></span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="/HRFlowSn/index.php?route=trainings/show&id=<?= $training['id'] ?>" class="btn btn-sm btn-outline-primary" title="Voir">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/HRFlowSn/index.php?route=trainings/edit&id=<?= $training['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="/HRFlowSn/index.php?route=trainings/delete&id=<?= $training['id'] ?>" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette formation ?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($trainings)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Aucune formation trouvée</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();

$data = [
    'pageTitle' => 'Formations',
    'activeMenu' => 'trainings',
    'content' => $content
];

$this->view('layouts/main.php', $data);
