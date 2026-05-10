<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/configuration.php';
require_once __DIR__ . '/../config/fonctions.php';
requireLogin();
$appointments = $pdo->query(
    'SELECT a.*, p.first_name, p.last_name, d.name AS doctor_name
     FROM appointments a
     JOIN patients p ON p.id = a.patient_id
     JOIN doctors d ON d.id = a.doctor_id
     ORDER BY a.appointment_date DESC, a.appointment_time DESC'
)->fetchAll();
require_once __DIR__ . '/../vues/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Rendez-vous</h1>
    <a class="btn btn-primary" href="<?= e(url('/rendez_vous/formulaire.php')) ?>">Nouveau rendez-vous</a>
</div>
<div class="card shadow-sm"><div class="card-body"><div class="table-responsive">
<table class="table table-striped">
<thead><tr><th>ID</th><th>Patient</th><th>Medecin</th><th>Date</th><th>Heure</th><th>Statut</th><th>Actions</th></tr></thead>
<tbody>
<?php if (!$appointments): ?><tr><td colspan="7" class="text-center text-muted">Aucun rendez-vous trouve.</td></tr><?php endif; ?>
<?php foreach ($appointments as $appointment): ?>
<tr>
<td><?= (int) $appointment['id'] ?></td><td><?= e($appointment['first_name'] . ' ' . $appointment['last_name']) ?></td><td><?= e($appointment['doctor_name']) ?></td><td><?= e($appointment['appointment_date']) ?></td><td><?= e($appointment['appointment_time']) ?></td><td><?= e($appointment['status']) ?></td>
<td>
<a class="btn btn-sm btn-warning" href="<?= e(url('/rendez_vous/formulaire.php?id=' . (int) $appointment['id'])) ?>">Modifier</a>
<a class="btn btn-sm btn-danger" href="<?= e(url('/rendez_vous/supprimer.php?id=' . (int) $appointment['id'])) ?>" onclick="return confirm('Supprimer ce rendez-vous ?')">Supprimer</a>
</td>
</tr>
<?php endforeach; ?>
</tbody></table></div></div></div>
<?php require_once __DIR__ . '/../vues/footer.php'; ?>

