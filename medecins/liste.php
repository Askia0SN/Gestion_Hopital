<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/configuration.php';
require_once __DIR__ . '/../config/fonctions.php';
requireLogin();
$doctors = $pdo->query('SELECT * FROM doctors ORDER BY id DESC')->fetchAll();
require_once __DIR__ . '/../vues/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Medecins</h1>
    <a class="btn btn-primary" href="<?= e(url('/medecins/formulaire.php')) ?>">Nouveau medecin</a>
</div>
<div class="card shadow-sm"><div class="card-body"><div class="table-responsive">
<table class="table table-striped">
<thead><tr><th>ID</th><th>Nom</th><th>Specialite</th><th>Telephone</th><th>Email</th><th>Actions</th></tr></thead>
<tbody>
<?php if (!$doctors): ?><tr><td colspan="6" class="text-center text-muted">Aucun medecin trouve.</td></tr><?php endif; ?>
<?php foreach ($doctors as $doctor): ?>
<tr>
<td><?= (int) $doctor['id'] ?></td><td><?= e($doctor['name']) ?></td><td><?= e($doctor['specialty']) ?></td><td><?= e($doctor['phone']) ?></td><td><?= e($doctor['email']) ?></td>
<td>
<a class="btn btn-sm btn-warning" href="<?= e(url('/medecins/formulaire.php?id=' . (int) $doctor['id'])) ?>">Modifier</a>
<a class="btn btn-sm btn-danger" href="<?= e(url('/medecins/supprimer.php?id=' . (int) $doctor['id'])) ?>" onclick="return confirm('Supprimer ce medecin ?')">Supprimer</a>
</td>
</tr>
<?php endforeach; ?>
</tbody></table></div></div></div>
<?php require_once __DIR__ . '/../vues/footer.php'; ?>

