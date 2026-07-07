<?php ob_start(); ?>

<div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="fw-bold">Paramètres & Administration</h1>
        <p class="text-muted">Gérez les accès et surveillez l'activité du système</p>
    </div>
    <a href="/HRFlowSn/index.php?route=settings/backup" class="btn btn-primary clay-btn">
        <i class="bi bi-database-down"></i> Sauvegarder la base de données
    </a>
</div>

<div class="row g-4">
    <!-- Audit Logs -->
    <div class="col-lg-8">
        <div class="clay-card p-4 h-100">
            <h5 class="fw-bold mb-4">
                <i class="bi bi-shield-check text-primary me-2"></i> Journal d'Audit (50 derniers)
            </h5>
            
            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                <table class="table clay-table table-sm">
                    <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                        <tr>
                            <th>Date / Heure</th>
                            <th>Utilisateur</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                        <tr>
                            <td class="text-muted small" style="white-space: nowrap;">
                                <?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?>
                            </td>
                            <td>
                                <div class="fw-semibold small"><?= htmlspecialchars($log['email'] ?? 'Système') ?></div>
                                <span class="badge bg-secondary" style="font-size: 0.65rem;"><?= htmlspecialchars($log['role_name'] ?? '-') ?></span>
                            </td>
                            <td class="small">
                                <?= htmlspecialchars($log['action']) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">
                                Aucun événement enregistré.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Users -->
    <div class="col-lg-4">
        <div class="clay-card p-4 h-100">
            <h5 class="fw-bold mb-4">
                <i class="bi bi-people text-primary me-2"></i> Comptes Utilisateurs
            </h5>
            
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <tbody>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td class="align-middle">
                                <div class="fw-semibold small"><?= htmlspecialchars($u['email']) ?></div>
                                <?php if ($u['last_login']): ?>
                                <div class="text-muted" style="font-size: 0.7rem;">
                                    Dernière connexion: <?= date('d/m/y H:i', strtotime($u['last_login'])) ?>
                                </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

$data = [
    'pageTitle' => 'Paramètres',
    'activeMenu' => 'settings',
    'content' => $content
];

$this->view('layouts/main.php', $data);
