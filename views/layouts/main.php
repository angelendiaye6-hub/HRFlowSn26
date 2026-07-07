<?php
require_once __DIR__ . '/../../includes/session.php';
$flash = get_flash_message();
$userRole = get_current_user_role();
$userEmail = session_get('user_email');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' - ' : '' ?>HRFlowSn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="/HRFlowSn/assets/css/style.css" rel="stylesheet">
    <?= isset($extraCss) ? $extraCss : '' ?>
</head>
<body>
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="sidebar clay-card">
            <div class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <h3>HRFlowSn</h3>
            </div>
            
            <nav>
                <ul class="sidebar-nav">
                    <li class="sidebar-nav-item">
                        <a href="/HRFlowSn/index.php?route=dashboard" class="sidebar-nav-link <?= (isset($activeMenu) && $activeMenu === 'dashboard') ? 'active' : '' ?>">
                            <i class="bi bi-speedometer2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="/HRFlowSn/index.php?route=employees" class="sidebar-nav-link <?= (isset($activeMenu) && $activeMenu === 'employees') ? 'active' : '' ?>">
                            <i class="bi bi-person-badge"></i>
                            Collaborateurs
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="/HRFlowSn/index.php?route=contracts" class="sidebar-nav-link <?= (isset($activeMenu) && $activeMenu === 'contracts') ? 'active' : '' ?>">
                            <i class="bi bi-file-earmark-text"></i>
                            Contrats
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="/HRFlowSn/index.php?route=leaves" class="sidebar-nav-link <?= (isset($activeMenu) && $activeMenu === 'leaves') ? 'active' : '' ?>">
                            <i class="bi bi-calendar-check"></i>
                            Congés
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="/HRFlowSn/index.php?route=payroll" class="sidebar-nav-link <?= (isset($activeMenu) && $activeMenu === 'payroll') ? 'active' : '' ?>">
                            <i class="bi bi-currency-dollar"></i>
                            Paie
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="/HRFlowSn/index.php?route=trainings" class="sidebar-nav-link <?= (isset($activeMenu) && $activeMenu === 'trainings') ? 'active' : '' ?>">
                            <i class="bi bi-mortarboard"></i>
                            Formations
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="/HRFlowSn/index.php?route=evaluations" class="sidebar-nav-link <?= (isset($activeMenu) && $activeMenu === 'evaluations') ? 'active' : '' ?>">
                            <i class="bi bi-star"></i>
                            Évaluations
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="/HRFlowSn/index.php?route=reports" class="sidebar-nav-link <?= (isset($activeMenu) && $activeMenu === 'reports') ? 'active' : '' ?>">
                            <i class="bi bi-bar-chart"></i>
                            Rapports
                        </a>
                    </li>
                    <?php if ($userRole === 'Administrateur'): ?>
                    <li class="sidebar-nav-item">
                        <a href="/HRFlowSn/index.php?route=settings" class="sidebar-nav-link <?= (isset($activeMenu) && $activeMenu === 'settings') ? 'active' : '' ?>">
                            <i class="bi bi-gear"></i>
                            Paramètres
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
            
            <div class="mt-auto pt-4 border-top" style="border-color: rgba(139, 92, 246, 0.1) !important;">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="sidebar-brand-icon" style="width: 40px; height: 40px; font-size: 20px;">
                        <i class="bi bi-person"></i>
                    </div>
                    <div>
                        <p class="mb-0 fw-semibold" style="font-size: 14px;"><?= htmlspecialchars($userEmail) ?></p>
                        <p class="mb-0 text-muted" style="font-size: 12px;"><?= htmlspecialchars($userRole) ?></p>
                    </div>
                </div>
                <a href="/HRFlowSn/index.php?route=auth/logout" class="btn btn-outline-danger w-100" style="border-radius: 12px;">
                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                </a>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show clay-card mb-4" role="alert">
                <?= htmlspecialchars($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?= $content ?>
        </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?= isset($extraJs) ? $extraJs : '' ?>
</body>
</html>
