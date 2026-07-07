<?php
$months = [
    1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
    5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
    9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
];

ob_start();
?>

<div class="page-header mb-4 d-flex justify-content-between align-items-center d-print-none">
    <div>
        <h1 class="fw-bold">Bulletin de Paie</h1>
        <p class="text-muted">Aperçu détaillé du bulletin</p>
    </div>
    <div>
        <button onclick="window.print()" class="btn btn-primary clay-btn me-2">
            <i class="bi bi-printer"></i> Imprimer / PDF
        </button>
        <a href="/HRFlowSn/index.php?route=payroll" class="btn btn-outline-secondary clay-btn-outline">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

<style>
    @media print {
        body { background-color: white; padding: 0; }
        .sidebar, .navbar, .page-header, .d-print-none { display: none !important; }
        .main-content { margin-left: 0 !important; padding: 0 !important; }
        .payslip-container { box-shadow: none !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }
    }
    .payslip-container {
        background-color: white;
        max-width: 850px;
        margin: 0 auto;
        color: #333;
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    }
    .payslip-header { border-bottom: 2px solid #2c3e50; padding-bottom: 15px; margin-bottom: 20px; }
    .payslip-table th { background-color: #f8f9fa; }
    .payslip-table th, .payslip-table td { padding: 10px; border: 1px solid #dee2e6; }
    .amount-col { text-align: right; }
</style>

<div class="clay-card p-5 payslip-container">
    
    <!-- En-tête de l'entreprise -->
    <div class="row payslip-header">
        <div class="col-6">
            <h2 class="fw-bold text-primary mb-1">HRFlow SN</h2>
            <p class="mb-0 small">123 Avenue Leopold Senghor<br>Dakar, Sénégal<br>NINEA: 123456789</p>
        </div>
        <div class="col-6 text-end">
            <h3 class="fw-bold text-uppercase mb-1">Bulletin de Paie</h3>
            <p class="mb-0 fw-semibold text-secondary">
                Période : <?= $months[$payroll['month']] ?> <?= $payroll['year'] ?>
            </p>
            <p class="mb-0 small text-muted">Référence : <?= htmlspecialchars($payroll['payroll_code']) ?></p>
        </div>
    </div>
    
    <!-- Informations Employé -->
    <div class="row mb-4">
        <div class="col-6">
            <div class="border p-3 rounded h-100 bg-light">
                <h6 class="fw-bold text-uppercase text-muted mb-3 border-bottom pb-2">Salarié</h6>
                <div class="fw-bold mb-1 fs-5"><?= htmlspecialchars($payroll['first_name'] . ' ' . $payroll['last_name']) ?></div>
                <div class="small">Matricule : <?= htmlspecialchars($payroll['employee_code']) ?></div>
                <div class="small">Département : <?= htmlspecialchars($payroll['department_name'] ?? '-') ?></div>
                <div class="small">Poste : <?= htmlspecialchars($payroll['position_name'] ?? '-') ?></div>
            </div>
        </div>
        <div class="col-6">
            <div class="border p-3 rounded h-100 bg-light">
                <h6 class="fw-bold text-uppercase text-muted mb-3 border-bottom pb-2">Détails Contrat</h6>
                <div class="small mb-1">Embauche : <?= date('d/m/Y', strtotime($payroll['hire_date'])) ?></div>
                <div class="small mb-1">Sit. Familiale : <?= htmlspecialchars($payroll['marital_status'] ?: '-') ?></div>
            </div>
        </div>
    </div>
    
    <!-- Tableau des éléments de paie -->
    <table class="table payslip-table mb-4">
        <thead>
            <tr>
                <th width="50%">Désignation</th>
                <th class="amount-col" width="25%">Gains (FCFA)</th>
                <th class="amount-col" width="25%">Retenues (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            <!-- Gains -->
            <tr>
                <td>Salaire de base</td>
                <td class="amount-col"><?= number_format($payroll['base_salary'], 0, ',', ' ') ?></td>
                <td class="amount-col"></td>
            </tr>
            
            <?php if ($payroll['overtime_hours'] > 0): ?>
            <tr>
                <td>Heures Supplémentaires (<?= $payroll['overtime_hours'] ?>h)</td>
                <td class="amount-col text-success">+ <?= number_format((($payroll['base_salary']/173.33) * $payroll['overtime_hours']), 0, ',', ' ') ?></td>
                <td class="amount-col"></td>
            </tr>
            <?php endif; ?>
            
            <?php if ($payroll['bonus'] > 0): ?>
            <tr>
                <td>Primes Exceptionnelles</td>
                <td class="amount-col text-success">+ <?= number_format($payroll['bonus'], 0, ',', ' ') ?></td>
                <td class="amount-col"></td>
            </tr>
            <?php endif; ?>
            
            <?php if ($payroll['absence_days'] > 0): ?>
            <tr>
                <td>Absences (<?= $payroll['absence_days'] ?> jours)</td>
                <td class="amount-col"></td>
                <td class="amount-col text-danger">- <?= number_format((($payroll['base_salary']/30) * $payroll['absence_days']), 0, ',', ' ') ?></td>
            </tr>
            <?php endif; ?>
            
            <!-- Brut -->
            <tr class="fw-bold bg-light">
                <td>SALAIRE BRUT</td>
                <td class="amount-col"><?= number_format($payroll['gross_salary'], 0, ',', ' ') ?></td>
                <td class="amount-col"></td>
            </tr>
            
            <!-- Retenues / Cotisations -->
            <tr>
                <td>IPRES (Retraite - Part Employé 8%)</td>
                <td class="amount-col"></td>
                <td class="amount-col text-danger">- <?= number_format($payroll['ipres'], 0, ',', ' ') ?></td>
            </tr>
            
            <tr>
                <td>Cotisation CSS (Information)</td>
                <td class="amount-col"></td>
                <td class="amount-col text-muted small">(<?= number_format($payroll['css'], 0, ',', ' ') ?> - Ch. Pat)</td>
            </tr>
            
            <tr>
                <td>Cotisation CFCE (Information)</td>
                <td class="amount-col"></td>
                <td class="amount-col text-muted small">(<?= number_format($payroll['cfce'], 0, ',', ' ') ?> - Ch. Pat)</td>
            </tr>
            
            <?php if ($payroll['income_tax'] > 0): ?>
            <tr>
                <td>IR (Impôt sur le Revenu)</td>
                <td class="amount-col"></td>
                <td class="amount-col text-danger">- <?= number_format($payroll['income_tax'], 0, ',', ' ') ?></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <!-- Total net -->
    <div class="row align-items-center">
        <div class="col-6">
            <p class="text-muted small mb-0">Mode de paiement : Virement bancaire</p>
            <p class="text-muted small">Payé le : <?= date('d/m/Y', strtotime($payroll['generated_at'])) ?></p>
        </div>
        <div class="col-6">
            <div class="border rounded p-3 bg-light text-end">
                <span class="text-uppercase fw-bold text-muted me-3">Net à payer</span>
                <span class="fs-4 fw-bold text-primary"><?= number_format($payroll['net_salary'], 0, ',', ' ') ?> FCFA</span>
            </div>
        </div>
    </div>
    
    <div class="mt-5 pt-3 border-top text-center text-muted small">
        <p>Pour vous aider à faire valoir vos droits, conservez ce bulletin de paie sans limitation de durée.</p>
    </div>
</div>

<?php
$content = ob_get_clean();

$data = [
    'pageTitle' => 'Bulletin de Paie',
    'activeMenu' => 'payroll',
    'content' => $content
];

$this->view('layouts/main.php', $data);
