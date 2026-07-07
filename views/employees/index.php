<?php
require_once __DIR__ . '/../../models/Department.php';
require_once __DIR__ . '/../../models/Position.php';

$departmentModel = new Department();
$positionModel = new Position();
$departments = $departmentModel->getAll();
$positions = $positionModel->getAll();

ob_start();
?>

<div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="fw-bold">Collaborateurs</h1>
        <p class="text-muted">Gestion des employés</p>
    </div>
    <a href="/HRFlowSn/index.php?route=employees/create" class="btn btn-primary clay-btn">
        <i class="bi bi-plus-lg"></i> Nouveau Collaborateur
    </a>
</div>

<div class="clay-card p-4">
    <div class="table-container table-responsive">
        <table class="clay-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Nom Complet</th>
                    <th>Email</th>
                    <th>Département</th>
                    <th>Poste</th>
                    <th>Date d'embauche</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $employee): ?>
                <tr>
                    <td><span class="badge badge-primary"><?= htmlspecialchars($employee['employee_code']) ?></span></td>
                    <td>
                        <div class="fw-semibold"><?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?></div>
                    </td>
                    <td><?= htmlspecialchars($employee['email']) ?></td>
                    <td><?= htmlspecialchars($employee['department_name']) ?></td>
                    <td><?= htmlspecialchars($employee['position_name']) ?></td>
                    <td><?= !empty($employee['hire_date']) ? date('d/m/Y', strtotime($employee['hire_date'])) : '<span class="text-muted fst-italic">Non définie</span>' ?></td>
                    <td>
                        <?php
                        $badgeClass = 'badge-success';
                        if ($employee['status'] === 'On Leave') $badgeClass = 'badge-warning';
                        elseif ($employee['status'] === 'Suspended') $badgeClass = 'badge-danger';
                        elseif ($employee['status'] === 'Resigned') $badgeClass = 'badge-secondary';
                        elseif ($employee['status'] === 'Retired') $badgeClass = 'badge-secondary';
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($employee['status']) ?></span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="/HRFlowSn/index.php?route=employees/show&id=<?= $employee['id'] ?>" class="btn btn-sm btn-outline-primary" title="Voir">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/HRFlowSn/index.php?route=employees/edit&id=<?= $employee['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="/HRFlowSn/index.php?route=employees/delete&id=<?= $employee['id'] ?>" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet employé ?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($employees)): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">Aucun employé trouvé</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();

$data = [
    'pageTitle' => 'Collaborateurs',
    'activeMenu' => 'employees',
    'content' => $content
];

$this->view('layouts/main.php', $data);
