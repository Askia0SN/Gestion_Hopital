<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/configuration.php';
require_once __DIR__ . '/../config/fonctions.php';
requireLogin();
$records = $pdo->query(
    'SELECT mr.*, p.first_name, p.last_name, d.name AS doctor_name
     FROM medical_records mr
     JOIN patients p ON p.id = mr.patient_id
     JOIN doctors d ON d.id = mr.doctor_id
     ORDER BY consultation_date DESC'
)->fetchAll();
require_once __DIR__ . '/../vues/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Dossiers medicaux</h1>
    <a class="btn btn-primary" href="<?= e(url('/dossiers_medicaux/formulaire.php')) ?>">Nouveau dossier</a>
</div>
<div class="card shadow-sm"><div class="card-body"><div class="table-responsive">
<table class="table table-striped">
<thead><tr><th>ID</th><th>Patient</th><th>Medecin</th><th>Date consultation</th><th>Diagnostic</th><th>Traitement</th><th>Actions</th></tr></thead>
<tbody>
<?php if (!$records): ?><tr><td colspan="7" class="text-center text-muted">Aucun dossier trouve.</td></tr><?php endif; ?>
<?php foreach ($records as $record): ?>
<tr>
<td><?= (int) $record['id'] ?></td><td><?= e($record['first_name'] . ' ' . $record['last_name']) ?></td><td><?= e($record['doctor_name']) ?></td><td><?= e($record['consultation_date']) ?></td><td><?= e($record['diagnosis']) ?></td><td><?= e($record['treatment']) ?></td>
<td>
<a class="btn btn-sm btn-warning" href="<?= e(url('/dossiers_medicaux/formulaire.php?id=' . (int) $record['id'])) ?>">Modifier</a>
<a class="btn btn-sm btn-danger" href="<?= e(url('/dossiers_medicaux/supprimer.php?id=' . (int) $record['id'])) ?>" onclick="return confirm('Supprimer ce dossier ?')">Supprimer</a>
</td>
</tr>
<?php endforeach; ?>
</tbody></table></div></div></div>
<?php require_once __DIR__ . '/../vues/footer.php'; ?>

