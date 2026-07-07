<?php
ob_start();
?>

<div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="fw-bold">Congés</h1>
        <p class="text-muted">Gestion des demandes de congé</p>
    </div>
    <a href="/HRFlowSn/index.php?route=leaves/create" class="btn btn-primary clay-btn">
        <i class="bi bi-plus-lg"></i> Nouvelle Demande
    </a>
</div>

<div class="clay-card p-4">
    <div class="table-container">
        <table class="clay-table">
            <thead>
                <tr>
                    <th>Employé</th>
                    <th>Type de congé</th>
                    <th>Dates</th>
                    <th>Durée</th>
                    <th>Motif</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leaveRequests as $leave): ?>
                <?php
                $startDate = new DateTime($leave['start_date']);
                $endDate = new DateTime($leave['end_date']);
                $duration = $startDate->diff($endDate)->days + 1;
                ?>
                <tr>
                    <td>
                        <div class="fw-semibold"><?= htmlspecialchars($leave['employee_name']) ?></div>
                        <small class="text-muted"><?= htmlspecialchars($leave['employee_code']) ?></small>
                    </td>
                    <td><?= htmlspecialchars($leave['leave_type_name']) ?></td>
                    <td>
                        <small><?= date('d/m/Y', strtotime($leave['start_date'])) ?></small>
                        <br>
                        <small class="text-muted">au <?= date('d/m/Y', strtotime($leave['end_date'])) ?></small>
                    </td>
                    <td><?= $duration ?> jour(s)</td>
                    <td><?= htmlspecialchars(substr($leave['reason'] ?: '-', 0, 30)) ?></td>
                    <td>
                        <?php
                        $badgeClass = 'badge-warning';
                        if ($leave['status'] === 'Approved') $badgeClass = 'badge-success';
                        elseif ($leave['status'] === 'Rejected') $badgeClass = 'badge-danger';
                        elseif ($leave['status'] === 'Cancelled') $badgeClass = 'badge-secondary';
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($leave['status']) ?></span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="/HRFlowSn/index.php?route=leaves/show&id=<?= $leave['id'] ?>" class="btn btn-sm btn-outline-primary" title="Voir">
                                <i class="bi bi-eye"></i>
                            </a>
                            <?php if ($leave['status'] === 'Pending'): ?>
                            <a href="/HRFlowSn/index.php?route=leaves/approve&id=<?= $leave['id'] ?>" class="btn btn-sm btn-outline-success" title="Approuver" onclick="return confirm('Approuver cette demande ?')">
                                <i class="bi bi-check-lg"></i>
                            </a>
                            <a href="/HRFlowSn/index.php?route=leaves/reject&id=<?= $leave['id'] ?>" class="btn btn-sm btn-outline-danger" title="Rejeter" onclick="return confirm('Rejeter cette demande ?')">
                                <i class="bi bi-x-lg"></i>
                            </a>
                            <?php endif; ?>
                            <a href="/HRFlowSn/index.php?route=leaves/edit&id=<?= $leave['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="/HRFlowSn/index.php?route=leaves/delete&id=<?= $leave['id'] ?>" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($leaveRequests)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Aucune demande trouvée</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();

$data = [
    'pageTitle' => 'Congés',
    'activeMenu' => 'leaves',
    'content' => $content
];

$this->view('layouts/main.php', $data);
