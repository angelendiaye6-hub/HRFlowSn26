<?php ob_start(); ?>

<div class="page-header mb-4">
    <h1 class="fw-bold">Rapports & Exports</h1>
    <p class="text-muted">Générez des rapports et exportez vos données en un clic</p>
</div>

<div class="row g-4">
    <!-- Export Employés -->
    <div class="col-md-4">
        <div class="clay-card p-4 text-center h-100 d-flex flex-column">
            <div class="mb-3">
                <i class="bi bi-file-earmark-excel text-success" style="font-size: 3rem;"></i>
            </div>
            <h5 class="fw-bold">Liste des Employés</h5>
            <p class="text-muted small mb-4">Export de la base de données complète des collaborateurs avec leurs informations de contact et postes.</p>
            <div class="mt-auto">
                <a href="/HRFlowSn/index.php?route=report/exportEmployees" class="btn btn-outline-success w-100" style="border-radius: 12px;">
                    <i class="bi bi-download"></i> Exporter Excel
                </a>
            </div>
        </div>
    </div>
    
    <!-- Export Paie -->
    <div class="col-md-4">
        <div class="clay-card p-4 text-center h-100 d-flex flex-column">
            <div class="mb-3">
                <i class="bi bi-file-earmark-excel text-primary" style="font-size: 3rem;"></i>
            </div>
            <h5 class="fw-bold">Historique de Paie</h5>
            <p class="text-muted small mb-4">Export de tous les bulletins générés (masse salariale, cotisations, primes, heures supplémentaires).</p>
            <div class="mt-auto">
                <a href="/HRFlowSn/index.php?route=report/exportPayrolls" class="btn btn-outline-primary w-100" style="border-radius: 12px;">
                    <i class="bi bi-download"></i> Exporter Excel
                </a>
            </div>
        </div>
    </div>
    
    <!-- Bilan Social -->
    <div class="col-md-4">
        <div class="clay-card p-4 text-center h-100 d-flex flex-column">
            <div class="mb-3">
                <i class="bi bi-file-earmark-pdf text-danger" style="font-size: 3rem;"></i>
            </div>
            <h5 class="fw-bold">Bilan Social</h5>
            <p class="text-muted small mb-4">Aperçu synthétique et visuel de l'état de l'entreprise (effectif, parité, répartition).</p>
            <div class="mt-auto">
                <a href="/HRFlowSn/index.php?route=report/socialBalance" class="btn btn-outline-danger w-100" style="border-radius: 12px;">
                    <i class="bi bi-eye"></i> Afficher (Imprimable)
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

$data = [
    'pageTitle' => 'Rapports',
    'activeMenu' => 'reports',
    'content' => $content
];

$this->view('layouts/main.php', $data);
