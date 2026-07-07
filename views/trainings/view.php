<?php
ob_start();
?>

<div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="fw-bold">Détails Formation</h1>
        <p class="text-muted">Informations détaillées de la formation</p>
    </div>
    <div>
        <a href="/HRFlowSn/index.php?route=trainings/edit&id=<?= $training['id'] ?>" class="btn btn-primary clay-btn me-2">
            <i class="bi bi-pencil"></i> Modifier
        </a>
        <a href="/HRFlowSn/index.php?route=trainings" class="btn btn-outline-secondary clay-btn-outline">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="clay-card p-4">
            <h5 class="fw-bold mb-4"><i class="bi bi-mortarboard"></i> Informations de la Formation</h5>
            
            <div class="mb-3">
                <label class="text-muted small">Code Formation</label>
                <div class="fw-semibold"><span class="badge badge-primary"><?= htmlspecialchars($training['training_code'] ?? '-') ?></span></div>
            </div>
            
            <div class="mb-3">
                <label class="text-muted small">Titre</label>
                <div class="fw-semibold fs-5"><?= htmlspecialchars($training['title']) ?></div>
            </div>
            
            <div class="mb-3">
                <label class="text-muted small">Description</label>
                <div><?= htmlspecialchars($training['description'] ?: 'Non spécifié') ?></div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="text-muted small">Lieu</label>
                    <div><?= htmlspecialchars($training['location'] ?: '-') ?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="text-muted small">Statut</label>
                    <div>
                        <?php
                        $badgeClass = 'badge-primary';
                        if ($training['status'] === 'En cours') $badgeClass = 'badge-warning';
                        elseif ($training['status'] === 'Terminee') $badgeClass = 'badge-success';
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($training['status']) ?></span>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="text-muted small">Date de début</label>
                    <div><?= $training['start_date'] ? date('d/m/Y', strtotime($training['start_date'])) : '-' ?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="text-muted small">Date de fin</label>
                    <div><?= $training['end_date'] ? date('d/m/Y', strtotime($training['end_date'])) : '-' ?></div>
                </div>
            </div>
            
            <hr>
            
            <div class="mb-3">
                <label class="text-muted small">Date de création</label>
                <div><?= date('d/m/Y H:i', strtotime($training['created_at'])) ?></div>
            </div>
            
            <div class="mb-3">
                <label class="text-muted small">Dernière mise à jour</label>
                <div><?= date('d/m/Y H:i', strtotime($training['updated_at'])) ?></div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="clay-card p-4">
            <h5 class="fw-bold mb-4"><i class="bi bi-people"></i> Participants</h5>
            
            <div class="mb-3">
                <label class="text-muted small">Nombre de participants</label>
                <div class="fw-semibold fs-4"><?= $training['participant_count'] ?></div>
            </div>
        </div>
    </div>
</div>

<div class="clay-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0"><i class="bi bi-people-fill"></i> Liste des Participants</h5>
        <button class="btn btn-primary clay-btn" data-bs-toggle="modal" data-bs-target="#addParticipantModal">
            <i class="bi bi-plus-lg"></i> Ajouter Participant
        </button>
    </div>
    
    <div class="table-container">
        <table class="clay-table">
            <thead>
                <tr>
                    <th>Employé</th>
                    <th>Département</th>
                    <th>Présence</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($participants as $participant): ?>
                <tr>
                    <td>
                        <div class="fw-semibold"><?= htmlspecialchars($participant['employee_name']) ?></div>
                        <small class="text-muted"><?= htmlspecialchars($participant['employee_code']) ?></small>
                    </td>
                    <td><?= htmlspecialchars($participant['department_name'] ?? '-') ?></td>
                    <td>
                        <?php if ($participant['attendance']): ?>
                        <span class="badge badge-success"><i class="bi bi-check-lg"></i> Présent</span>
                        <?php else: ?>
                        <span class="badge badge-secondary">Absent</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="btn-group">
                            <form method="POST" action="/HRFlowSn/index.php?route=trainings/markAttendance" style="display: inline;">
                                <input type="hidden" name="training_id" value="<?= $training['id'] ?>">
                                <input type="hidden" name="employee_id" value="<?= $participant['employee_id'] ?>">
                                <input type="hidden" name="attended" value="<?= $participant['attendance'] ? '0' : '1' ?>">
                                <button type="submit" class="btn btn-sm btn-outline-<?= $participant['attendance'] ? 'warning' : 'success' ?>" title="Marquer présence">
                                    <i class="bi bi-<?= $participant['attendance'] ? 'x-lg' : 'check-lg' ?>"></i>
                                </button>
                            </form>
                            <a href="/HRFlowSn/index.php?route=trainings/removeParticipant?training_id=<?= $training['id'] ?>&employee_id=<?= $participant['employee_id'] ?>" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="return confirm('Supprimer ce participant ?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($participants)): ?>
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">Aucun participant</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Add Participant -->
<div class="modal fade" id="addParticipantModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content clay-card">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Ajouter un Participant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/HRFlowSn/index.php?route=trainings/addParticipant">
                <div class="modal-body">
                    <input type="hidden" name="training_id" value="<?= $training['id'] ?>">
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Employé</label>
                        <select class="form-select clay-input" id="employee_id" name="employee_id" required>
                            <option value="">Sélectionner un employé...</option>
                            <?php foreach ($employees as $employee): ?>
                            <option value="<?= $employee['id'] ?>">
                                <?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?> 
                                (<?= htmlspecialchars($employee['employee_code']) ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary clay-btn-outline" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary clay-btn">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

$data = [
    'pageTitle' => 'Détails Formation',
    'activeMenu' => 'trainings',
    'content' => $content
];

$this->view('layouts/main.php', $data);
