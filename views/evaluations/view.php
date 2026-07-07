<?php
ob_start();
?>

<div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="fw-bold">Détails Évaluation</h1>
        <p class="text-muted">Informations détaillées de l'évaluation</p>
    </div>
    <div>
        <a href="/HRFlowSn/index.php?route=evaluations/edit&id=<?= $evaluation['id'] ?>" class="btn btn-primary clay-btn me-2">
            <i class="bi bi-pencil"></i> Modifier
        </a>
        <a href="/HRFlowSn/index.php?route=evaluations" class="btn btn-outline-secondary clay-btn-outline">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="clay-card p-4">
            <h5 class="fw-bold mb-4"><i class="bi bi-star"></i> Informations de l'Évaluation</h5>
            
            <div class="mb-3">
                <label class="text-muted small">Employé</label>
                <div class="fw-semibold">
                    <?= htmlspecialchars($evaluation['employee_name']) ?>
                    <small class="text-muted">(<?= htmlspecialchars($evaluation['employee_code']) ?>)</small>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="text-muted small">Département</label>
                    <div><?= htmlspecialchars($evaluation['department_name']) ?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="text-muted small">Poste</label>
                    <div><?= htmlspecialchars($evaluation['position_name']) ?></div>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="text-muted small">Date d'évaluation</label>
                <div><?= $evaluation['evaluation_date'] ? date('d/m/Y', strtotime($evaluation['evaluation_date'])) : '-' ?></div>
            </div>
            
            <div class="mb-3">
                <label class="text-muted small">Objectifs</label>
                <div class="p-3 bg-light rounded"><?= htmlspecialchars($evaluation['objectives'] ?: 'Non spécifié') ?></div>
            </div>
            
            <div class="mb-3">
                <label class="text-muted small">Commentaires</label>
                <div class="p-3 bg-light rounded"><?= htmlspecialchars($evaluation['comments'] ?: 'Aucun commentaire') ?></div>
            </div>
            
            <hr>
            
            <div class="mb-3">
                <label class="text-muted small">Date de création</label>
                <div><?= date('d/m/Y H:i', strtotime($evaluation['created_at'])) ?></div>
            </div>
            
            <div class="mb-3">
                <label class="text-muted small">Dernière mise à jour</label>
                <div><?= date('d/m/Y H:i', strtotime($evaluation['updated_at'])) ?></div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="clay-card p-4">
            <h5 class="fw-bold mb-4"><i class="bi bi-bar-chart"></i> Résultat</h5>
            
            <div class="mb-3">
                <label class="text-muted small">Score</label>
                <div class="text-center py-4">
                    <?php if ($evaluation['score'] !== null): ?>
                    <div class="display-4 fw-bold <?= $evaluation['score'] >= 80 ? 'text-success' : ($evaluation['score'] >= 60 ? 'text-warning' : 'text-danger') ?>">
                        <?= $evaluation['score'] ?>
                    </div>
                    <div class="text-muted">/ 100</div>
                    <?php else: ?>
                    <div class="text-muted">Non noté</div>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if ($evaluation['score'] !== null): ?>
            <div class="progress mb-3" style="height: 20px;">
                <div class="progress-bar <?= $evaluation['score'] >= 80 ? 'bg-success' : ($evaluation['score'] >= 60 ? 'bg-warning' : 'bg-danger') ?>" 
                     role="progressbar" 
                     style="width: <?= $evaluation['score'] ?>%">
                    <?= $evaluation['score'] ?>%
                </div>
            </div>
            
            <div class="alert <?= $evaluation['score'] >= 80 ? 'alert-success' : ($evaluation['score'] >= 60 ? 'alert-warning' : 'alert-danger') ?>">
                <i class="bi bi-<?= $evaluation['score'] >= 80 ? 'check-circle' : ($evaluation['score'] >= 60 ? 'exclamation-circle' : 'x-circle') ?>"></i>
                <?= $evaluation['score'] >= 80 ? 'Performance excellente' : ($evaluation['score'] >= 60 ? 'Performance satisfaisante' : 'Performance à améliorer') ?>
            </div>
            
            <hr>
            <form method="POST" action="/HRFlowSn/index.php?route=evaluations/score" class="mt-3">
                <input type="hidden" name="id" value="<?= $evaluation['id'] ?>">
                <label class="form-label text-muted small">Modifier la note</label>
                <div class="input-group input-group-sm">
                    <input type="number" class="form-control" name="score" min="0" max="100" step="0.01" value="<?= $evaluation['score'] ?>" required>
                    <button class="btn btn-outline-primary" type="submit">Enregistrer</button>
                </div>
            </form>
            
            <?php else: ?>
            <form method="POST" action="/HRFlowSn/index.php?route=evaluations/score" class="mt-4">
                <input type="hidden" name="id" value="<?= $evaluation['id'] ?>">
                <label class="form-label fw-bold">Attribuer une note</label>
                <div class="input-group">
                    <input type="number" class="form-control clay-input" name="score" min="0" max="100" step="0.01" placeholder="Ex: 85" required>
                    <button class="btn btn-primary clay-btn" type="submit">Valider</button>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

$data = [
    'pageTitle' => 'Détails Évaluation',
    'activeMenu' => 'evaluations',
    'content' => $content
];

$this->view('layouts/main.php', $data);
