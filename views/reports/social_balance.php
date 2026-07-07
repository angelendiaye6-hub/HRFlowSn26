<?php ob_start(); ?>

<div class="page-header mb-4 d-flex justify-content-between align-items-center d-print-none">
    <div>
        <h1 class="fw-bold">Bilan Social</h1>
        <p class="text-muted">Tableau de bord de l'entreprise</p>
    </div>
    <div>
        <button onclick="window.print()" class="btn btn-danger clay-btn me-2">
            <i class="bi bi-printer"></i> Imprimer / PDF
        </button>
        <a href="/HRFlowSn/index.php?route=reports" class="btn btn-outline-secondary clay-btn-outline">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

<style>
    @media print {
        body { background-color: white; padding: 0; }
        .sidebar, .navbar, .page-header, .d-print-none { display: none !important; }
        .main-content { margin-left: 0 !important; padding: 0 !important; }
        .report-container { box-shadow: none !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }
        .chart-container { page-break-inside: avoid; }
    }
    .report-container {
        background-color: white;
        max-width: 800px;
        margin: 0 auto;
        color: #333;
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    }
    .report-header { border-bottom: 3px solid #EF4444; padding-bottom: 20px; margin-bottom: 30px; }
</style>

<div class="clay-card p-5 report-container">
    
    <!-- En-tête -->
    <div class="text-center report-header">
        <h2 class="fw-bold text-uppercase mb-2">Bilan Social Annuel</h2>
        <h4 class="text-muted">HRFlow SN</h4>
        <p class="mb-0 small text-muted">Généré le : <?= date('d/m/Y') ?></p>
    </div>
    
    <!-- Indicateurs clés -->
    <div class="row mb-5 text-center g-3">
        <div class="col-4">
            <div class="border rounded p-3 bg-light">
                <h2 class="fw-bold text-primary mb-1"><?= $stats['active'] ?></h2>
                <div class="small text-uppercase text-muted">Employés Actifs</div>
            </div>
        </div>
        <div class="col-4">
            <div class="border rounded p-3 bg-light">
                <h2 class="fw-bold text-success mb-1"><?= number_format($stats['total_salary'], 0, ',', ' ') ?></h2>
                <div class="small text-uppercase text-muted">Masse Sal. de Base (FCFA)</div>
            </div>
        </div>
        <div class="col-4">
            <div class="border rounded p-3 bg-light">
                <h2 class="fw-bold text-warning mb-1"><?= $stats['males'] ?>H / <?= $stats['females'] ?>F</h2>
                <div class="small text-uppercase text-muted">Parité</div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Parité Chart -->
        <div class="col-md-6 mb-4 chart-container">
            <h5 class="fw-bold border-bottom pb-2 mb-3">Répartition Hommes/Femmes</h5>
            <div style="height: 250px; position: relative;">
                <canvas id="genderChart"></canvas>
            </div>
        </div>
        
        <!-- Départements Chart -->
        <div class="col-md-6 mb-4 chart-container">
            <h5 class="fw-bold border-bottom pb-2 mb-3">Effectif par Département</h5>
            <div style="height: 250px; position: relative;">
                <canvas id="deptChart"></canvas>
            </div>
        </div>
    </div>
    
    <div class="mt-5 pt-3 border-top text-center text-muted small">
        <p>Document généré automatiquement par le SIRH HRFlowSn.</p>
    </div>
</div>

<?php
$content = ob_get_clean();

$deptLabels = json_encode(array_keys($stats['departments']));
$deptData = json_encode(array_values($stats['departments']));

$extraJs = <<<JS
<script>
// Gender Chart
new Chart(document.getElementById('genderChart'), {
    type: 'pie',
    data: {
        labels: ['Hommes', 'Femmes'],
        datasets: [{
            data: [{$stats['males']}, {$stats['females']}],
            backgroundColor: ['#3B82F6', '#EC4899'],
            borderWidth: 0
        }]
    },
    options: { responsive: true, maintainAspectRatio: false }
});

// Dept Chart
new Chart(document.getElementById('deptChart'), {
    type: 'bar',
    data: {
        labels: $deptLabels,
        datasets: [{
            label: 'Employés',
            data: $deptData,
            backgroundColor: '#10B981'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});
</script>
JS;

$data = [
    'pageTitle' => 'Bilan Social',
    'activeMenu' => 'reports',
    'content' => $content,
    'extraJs' => $extraJs
];

$this->view('layouts/main.php', $data);
