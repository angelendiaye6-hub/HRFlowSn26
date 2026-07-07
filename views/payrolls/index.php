<?php
ob_start();

$months = [
    1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
    5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
    9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
];
?>

<div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="fw-bold">Gestion de la Paie</h1>
        <p class="text-muted">Consultez et générez les bulletins de salaire</p>
    </div>
    <a href="/HRFlowSn/index.php?route=payroll/create" class="btn btn-primary clay-btn">
        <i class="bi bi-plus-lg"></i> Générer un Bulletin
    </a>
</div>

<div class="clay-card p-4">
    <div class="table-responsive">
        <table class="table clay-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Période</th>
                    <th>Employé</th>
                    <th>Salaire Brut</th>
                    <th>Net à payer</th>
                    <th>Date génération</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payrolls as $payroll): ?>
                <tr>
                    <td><span class="badge badge-primary"><?= htmlspecialchars($payroll['payroll_code']) ?></span></td>
                    <td class="fw-semibold"><?= $months[$payroll['month']] ?> <?= $payroll['year'] ?></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="ms-2">
                                <div class="fw-semibold"><?= htmlspecialchars($payroll['first_name'] . ' ' . $payroll['last_name']) ?></div>
                                <div class="text-muted small"><?= htmlspecialchars($payroll['department_name'] ?? '-') ?></div>
                            </div>
                        </div>
                    </td>
                    <td><?= number_format($payroll['gross_salary'], 2, ',', ' ') ?> FCFA</td>
                    <td class="fw-bold text-success"><?= number_format($payroll['net_salary'], 2, ',', ' ') ?> FCFA</td>
                    <td><?= date('d/m/Y H:i', strtotime($payroll['generated_at'])) ?></td>
                    <td>
                        <a href="/HRFlowSn/index.php?route=payroll/show&id=<?= $payroll['id'] ?>" class="btn btn-sm btn-outline-primary me-1" title="Voir/Imprimer">
                            <i class="bi bi-file-earmark-text"></i>
                        </a>
                        <form action="/HRFlowSn/index.php?route=payroll/delete" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce bulletin ?');">
                            <input type="hidden" name="id" value="<?= $payroll['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <?php if (empty($payrolls)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                        Aucun bulletin de paie généré.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();

$data = [
    'pageTitle' => 'Gestion de la Paie',
    'activeMenu' => 'payroll',
    'content' => $content
];

$this->view('layouts/main.php', $data);
