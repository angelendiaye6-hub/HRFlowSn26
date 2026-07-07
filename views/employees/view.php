<?php
require_once __DIR__ . '/../../models/Department.php';
require_once __DIR__ . '/../../models/Position.php';

$departmentModel = new Department();
$positionModel = new Position();

$department = $departmentModel->getById($employee['department_id']);
$position = $positionModel->getById($employee['position_id']);

ob_start();
?>

<div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="fw-bold">Détails Collaborateur</h1>
        <p class="text-muted">Informations détaillées de l'employé</p>
    </div>
    <div>
        <a href="/HRFlowSn/index.php?route=employees/edit&id=<?= $employee['id'] ?>" class="btn btn-primary clay-btn me-2">
            <i class="bi bi-pencil"></i> Modifier
        </a>
        <a href="/HRFlowSn/index.php?route=employees" class="btn btn-outline-secondary clay-btn-outline">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

<!-- Onglets de navigation -->
<ul class="nav nav-tabs clay-tabs mb-4" id="employeeTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">Informations</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="contracts-tab" data-bs-toggle="tab" data-bs-target="#contracts" type="button" role="tab">
            Contrats <span class="badge bg-primary rounded-pill"><?= count($contracts) ?></span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="evaluations-tab" data-bs-toggle="tab" data-bs-target="#evaluations" type="button" role="tab">
            Évaluations <span class="badge bg-primary rounded-pill"><?= count($evaluations) ?></span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="leaves-tab" data-bs-toggle="tab" data-bs-target="#leaves" type="button" role="tab">
            Congés <span class="badge bg-primary rounded-pill"><?= count($leaves) ?></span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="trainings-tab" data-bs-toggle="tab" data-bs-target="#trainings" type="button" role="tab">
            Formations <span class="badge bg-primary rounded-pill"><?= count($trainings) ?></span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="payrolls-tab" data-bs-toggle="tab" data-bs-target="#payrolls" type="button" role="tab">
            Paie <span class="badge bg-primary rounded-pill"><?= count($payrolls) ?></span>
        </button>
    </li>
</ul>

<div class="tab-content" id="employeeTabsContent">
    <!-- Tab Informations -->
    <div class="tab-pane fade show active" id="info" role="tabpanel">
        <div class="row g-4">
            <!-- Informations personnelles -->
            <div class="col-lg-6">
                <div class="clay-card p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-person"></i> Informations Personnelles</h5>
                    
                    <div class="mb-3">
                        <label class="text-muted small">Code Employé</label>
                        <div class="fw-semibold"><span class="badge badge-primary"><?= htmlspecialchars($employee['employee_code']) ?></span></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-muted small">Nom Complet</label>
                        <div class="fw-semibold"><?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?></div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Genre</label>
                            <div><?= $employee['gender'] === 'Male' ? 'Masculin' : 'Féminin' ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Date de naissance</label>
                            <div><?= date('d/m/Y', strtotime($employee['birth_date'])) ?></div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Lieu de naissance</label>
                            <div><?= htmlspecialchars($employee['birth_place'] ?: '-') ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Nationalité</label>
                            <div><?= htmlspecialchars($employee['nationality']) ?></div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Situation matrimoniale</label>
                            <div><?= htmlspecialchars($employee['marital_status'] ?: '-') ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Statut</label>
                            <div>
                                <?php
                                $badgeClass = 'badge-success';
                                if ($employee['status'] === 'On Leave') $badgeClass = 'badge-warning';
                                elseif ($employee['status'] === 'Suspended') $badgeClass = 'badge-danger';
                                elseif ($employee['status'] === 'Resigned') $badgeClass = 'badge-secondary';
                                elseif ($employee['status'] === 'Retired') $badgeClass = 'badge-secondary';
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($employee['status']) ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-muted small">Téléphone</label>
                        <div><?= htmlspecialchars($employee['phone'] ?: '-') ?></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-muted small">Email</label>
                        <div><?= htmlspecialchars($employee['email'] ?: '-') ?></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-muted small">Adresse</label>
                        <div><?= htmlspecialchars($employee['address'] ?: '-') ?></div>
                    </div>
                </div>
            </div>
            
            <!-- Informations professionnelles -->
            <div class="col-lg-6">
                <div class="clay-card p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-briefcase"></i> Informations Professionnelles</h5>
                    
                    <div class="mb-3">
                        <label class="text-muted small">Département</label>
                        <div class="fw-semibold"><?= htmlspecialchars($department['name'] ?? '-') ?></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-muted small">Poste</label>
                        <div class="fw-semibold"><?= htmlspecialchars($position['name'] ?? '-') ?></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-muted small">Qualification</label>
                        <div><?= htmlspecialchars($employee['qualification'] ?: '-') ?></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-muted small">Date d'embauche</label>
                        <div class="fw-semibold"><?= date('d/m/Y', strtotime($employee['hire_date'])) ?></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-muted small">Salaire de base (Référence)</label>
                        <div class="fw-semibold text-success"><?= number_format($employee['base_salary'], 2, ',', ' ') ?> FCFA</div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <label class="text-muted small">Date de création</label>
                        <div><?= date('d/m/Y H:i', strtotime($employee['created_at'])) ?></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-muted small">Dernière mise à jour</label>
                        <div><?= date('d/m/Y H:i', strtotime($employee['updated_at'])) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Contrats -->
    <div class="tab-pane fade" id="contracts" role="tabpanel">
        <div class="clay-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0">Historique des Contrats</h5>
                <a href="/HRFlowSn/index.php?route=contracts/create&employee_id=<?= $employee['id'] ?>" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg"></i> Nouveau
                </a>
            </div>
            
            <div class="table-responsive">
                <table class="table clay-table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Type</th>
                            <th>Salaire</th>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contracts as $contract): ?>
                        <tr>
                            <td><span class="badge badge-primary"><?= htmlspecialchars($contract['contract_code']) ?></span></td>
                            <td><?= htmlspecialchars($contract['contract_type']) ?></td>
                            <td class="fw-semibold text-success"><?= number_format($contract['salary'], 2, ',', ' ') ?> FCFA</td>
                            <td><?= date('d/m/Y', strtotime($contract['start_date'])) ?></td>
                            <td><?= $contract['end_date'] ? date('d/m/Y', strtotime($contract['end_date'])) : '-' ?></td>
                            <td>
                                <?php
                                $badge = 'badge-success';
                                if ($contract['status'] === 'Expire') $badge = 'badge-warning';
                                elseif ($contract['status'] === 'Resilie') $badge = 'badge-danger';
                                ?>
                                <span class="badge <?= $badge ?>"><?= htmlspecialchars($contract['status']) ?></span>
                            </td>
                            <td>
                                <a href="/HRFlowSn/index.php?route=contracts/show&id=<?= $contract['id'] ?>" class="btn btn-sm btn-outline-primary" title="Voir"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($contracts)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">Aucun contrat trouvé.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab Evaluations -->
    <div class="tab-pane fade" id="evaluations" role="tabpanel">
        <div class="clay-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0">Évaluations de Performance</h5>
                <a href="/HRFlowSn/index.php?route=evaluations/create&employee_id=<?= $employee['id'] ?>" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg"></i> Nouvelle
                </a>
            </div>
            
            <div class="table-responsive">
                <table class="table clay-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Score</th>
                            <th>Objectifs</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($evaluations as $evaluation): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($evaluation['evaluation_date'])) ?></td>
                            <td>
                                <?php if ($evaluation['score'] !== null): ?>
                                    <span class="badge bg-<?= $evaluation['score'] >= 70 ? 'success' : ($evaluation['score'] >= 50 ? 'warning' : 'danger') ?>">
                                        <?= number_format($evaluation['score'], 1) ?>/100
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">Non noté</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars(substr($evaluation['objectives'], 0, 50)) ?>...</td>
                            <td>
                                <a href="/HRFlowSn/index.php?route=evaluations/show&id=<?= $evaluation['id'] ?>" class="btn btn-sm btn-outline-primary" title="Voir"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($evaluations)): ?>
                        <tr><td colspan="4" class="text-center text-muted py-4">Aucune évaluation trouvée.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab Congés -->
    <div class="tab-pane fade" id="leaves" role="tabpanel">
        <div class="clay-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0">Historique des Congés</h5>
                <a href="/HRFlowSn/index.php?route=leaves/create&employee_id=<?= $employee['id'] ?>" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg"></i> Nouveau
                </a>
            </div>
            
            <div class="table-responsive">
                <table class="table clay-table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leaves as $leave): ?>
                        <tr>
                            <td><span class="badge badge-info"><?= htmlspecialchars($leave['leave_type_name']) ?></span></td>
                            <td><?= date('d/m/Y', strtotime($leave['start_date'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($leave['end_date'])) ?></td>
                            <td>
                                <?php
                                $badge = 'badge-secondary';
                                if ($leave['status'] === 'Approved') $badge = 'badge-success';
                                elseif ($leave['status'] === 'Pending') $badge = 'badge-warning';
                                elseif ($leave['status'] === 'Rejected') $badge = 'badge-danger';
                                ?>
                                <span class="badge <?= $badge ?>"><?= htmlspecialchars($leave['status']) ?></span>
                            </td>
                            <td>
                                <a href="/HRFlowSn/index.php?route=leaves/show&id=<?= $leave['id'] ?>" class="btn btn-sm btn-outline-primary" title="Voir"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($leaves)): ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">Aucun congé trouvé.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab Formations -->
    <div class="tab-pane fade" id="trainings" role="tabpanel">
        <div class="clay-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0">Formations Suivies</h5>
            </div>
            
            <div class="table-responsive">
                <table class="table clay-table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Lieu</th>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($trainings as $training): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($training['title']) ?></td>
                            <td><?= htmlspecialchars($training['location'] ?? '-') ?></td>
                            <td><?= $training['start_date'] ? date('d/m/Y', strtotime($training['start_date'])) : '-' ?></td>
                            <td><?= $training['end_date'] ? date('d/m/Y', strtotime($training['end_date'])) : '-' ?></td>
                            <td>
                                <?php
                                $badge = 'badge-primary';
                                if ($training['status'] === 'Terminee') $badge = 'badge-success';
                                elseif ($training['status'] === 'Programmee') $badge = 'badge-warning';
                                ?>
                                <span class="badge <?= $badge ?>"><?= htmlspecialchars($training['status']) ?></span>
                            </td>
                            <td>
                                <a href="/HRFlowSn/index.php?route=trainings/show&id=<?= $training['id'] ?>" class="btn btn-sm btn-outline-primary" title="Voir"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($trainings)): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">Aucune formation trouvée.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Tab Paie -->
    <div class="tab-pane fade" id="payrolls" role="tabpanel">
        <div class="clay-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0">Historique des Paiements</h5>
                <a href="/HRFlowSn/index.php?route=payroll/create" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg"></i> Générer
                </a>
            </div>
            
            <div class="table-responsive">
                <table class="table clay-table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Période</th>
                            <th>Salaire Brut</th>
                            <th>Net à payer</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $months = [
                            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
                            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
                            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
                        ];
                        foreach ($payrolls as $payroll): 
                        ?>
                        <tr>
                            <td><span class="badge badge-primary"><?= htmlspecialchars($payroll['payroll_code']) ?></span></td>
                            <td class="fw-semibold"><?= $months[$payroll['month']] ?> <?= $payroll['year'] ?></td>
                            <td><?= number_format($payroll['gross_salary'], 0, ',', ' ') ?> FCFA</td>
                            <td class="fw-bold text-success"><?= number_format($payroll['net_salary'], 0, ',', ' ') ?> FCFA</td>
                            <td><?= date('d/m/Y', strtotime($payroll['generated_at'])) ?></td>
                            <td>
                                <a href="/HRFlowSn/index.php?route=payroll/show&id=<?= $payroll['id'] ?>" class="btn btn-sm btn-outline-primary" title="Voir"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($payrolls)): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">Aucun bulletin de paie généré.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

$data = [
    'pageTitle' => 'Détails Collaborateur',
    'activeMenu' => 'employees',
    'content' => $content
];

$this->view('layouts/main.php', $data);
