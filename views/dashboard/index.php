<?php
ob_start();
?>

<div class="page-header mb-4">
    <h1 class="fw-bold">Dashboard</h1>
    <p class="text-muted">Vue d'ensemble du système RH</p>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card clay-card">
        <div class="stat-icon stat-icon-primary">
            <i class="bi bi-people-fill"></i>
        </div>
        <div class="stat-info">
            <h4><?= $stats['total_employees'] ?></h4>
            <p>Total Employés</p>
        </div>
    </div>
    
    <div class="stat-card clay-card">
        <div class="stat-icon stat-icon-success">
            <i class="bi bi-person-check-fill"></i>
        </div>
        <div class="stat-info">
            <h4><?= $stats['active_employees'] ?></h4>
            <p>Employés Actifs</p>
        </div>
    </div>
    
    <div class="stat-card clay-card">
        <div class="stat-icon stat-icon-warning">
            <i class="bi bi-clock-fill"></i>
        </div>
        <div class="stat-info">
            <h4><?= $stats['pending_leaves'] ?></h4>
            <p>Congés en Attente</p>
        </div>
    </div>
    
    <div class="stat-card clay-card">
        <div class="stat-icon stat-icon-secondary">
            <i class="bi bi-cash-stack"></i>
        </div>
        <div class="stat-info">
            <h4><?= number_format($stats['masse_salariale'], 0, ',', ' ') ?> F</h4>
            <p>Masse Salariale</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Chart: Employees by Department -->
    <div class="col-lg-6">
        <div class="clay-card p-4">
            <h5 class="fw-bold mb-4">Employés par Département</h5>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="departmentChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Chart: Masse Salariale -->
    <div class="col-lg-6">
        <div class="clay-card p-4">
            <h5 class="fw-bold mb-4">Évolution Masse Salariale (Brut)</h5>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="payrollChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Recent Leave Requests -->
    <div class="col-lg-6">
        <div class="clay-card p-4">
            <h5 class="fw-bold mb-4">Demandes de Congé Récentes</h5>
            <div class="table-container">
                <table class="clay-table">
                    <thead>
                        <tr>
                            <th>Employé</th>
                            <th>Type</th>
                            <th>Dates</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentLeaves as $leave): ?>
                        <tr>
                            <td><?= htmlspecialchars($leave['employee_name']) ?></td>
                            <td><?= htmlspecialchars($leave['leave_type_name']) ?></td>
                            <td>
                                <small><?= date('d/m/Y', strtotime($leave['start_date'])) ?></small>
                                <br>
                                <small class="text-muted">au <?= date('d/m/Y', strtotime($leave['end_date'])) ?></small>
                            </td>
                            <td>
                                <?php
                                $badgeClass = 'badge-primary';
                                if ($leave['status'] === 'Approved') $badgeClass = 'badge-success';
                                elseif ($leave['status'] === 'Rejected') $badgeClass = 'badge-danger';
                                elseif ($leave['status'] === 'Pending') $badgeClass = 'badge-warning';
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($leave['status']) ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($recentLeaves)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">Aucune demande récente</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Expiring Contracts -->
    <div class="col-lg-6">
        <div class="clay-card p-4">
            <h5 class="fw-bold mb-4">Contrats Expirant Bientôt</h5>
            <div class="table-container">
                <table class="clay-table">
                    <thead>
                        <tr>
                            <th>Employé</th>
                            <th>Type</th>
                            <th>Fin de Contrat</th>
                            <th>Jours Restants</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($expiringContracts as $contract): ?>
                        <tr>
                            <td><?= htmlspecialchars($contract['employee_name']) ?></td>
                            <td><?= htmlspecialchars($contract['contract_type']) ?></td>
                            <td><?= date('d/m/Y', strtotime($contract['end_date'])) ?></td>
                            <td>
                                <?php
                                $daysLeft = (new DateTime($contract['end_date']))->diff(new DateTime())->days;
                                ?>
                                <span class="badge badge-warning"><?= $daysLeft ?> jours</span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($expiringContracts)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">Aucun contrat expirant bientôt</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

$deptLabels = json_encode(array_column($employeesByDepartment, 'department'));
$deptData = json_encode(array_column($employeesByDepartment, 'count'));

// Préparation des données du graphique Masse Salariale
$monthsLabels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
$payrollGrossData = array_fill(0, 12, 0);

foreach ($monthlyPayrollMass as $mp) {
    $mIndex = (int)$mp['month'] - 1;
    $payrollGrossData[$mIndex] = (float)$mp['total_gross'];
}
$payLabelsJson = json_encode($monthsLabels);
$payDataJson = json_encode($payrollGrossData);

$extraJs = <<<JS
<script>
// Department Chart
const departmentCtx = document.getElementById('departmentChart').getContext('2d');
const departmentLabels = $deptLabels;
const departmentData = $deptData;

new Chart(departmentCtx, {
    type: 'doughnut',
    data: {
        labels: departmentLabels,
        datasets: [{
            data: departmentData,
            backgroundColor: [
                '#8B5CF6', '#EC4899', '#10B981', '#F59E0B', '#3B82F6', '#EF4444'
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { padding: 20, font: { family: 'Outfit' } }
            }
        }
    }
});

// Payroll Chart
const payrollCtx = document.getElementById('payrollChart').getContext('2d');
new Chart(payrollCtx, {
    type: 'line',
    data: {
        labels: $payLabelsJson,
        datasets: [{
            label: 'Masse Salariale (Brut)',
            data: $payDataJson,
            borderColor: '#8B5CF6',
            backgroundColor: 'rgba(139, 92, 246, 0.1)',
            borderWidth: 3,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: '#8B5CF6',
            pointBorderWidth: 2,
            pointRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0,0,0,0.05)' },
                ticks: {
                    callback: function(value) { return value.toLocaleString() + ' F'; }
                }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});
</script>
JS;

$data = [
    'pageTitle' => 'Dashboard',
    'activeMenu' => 'dashboard',
    'content' => $content,
    'extraJs' => $extraJs
];

$this->view('layouts/main.php', $data);
