<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/configuration.php';
require_once __DIR__ . '/../config/fonctions.php';
requireLogin();

$search = trim($_GET['search'] ?? '');

if ($search !== '') {
    $stmt = $pdo->prepare(
        'SELECT * FROM patients
         WHERE first_name LIKE :term OR last_name LIKE :term OR phone LIKE :term
         ORDER BY id DESC'
    );
    $stmt->execute(['term' => '%' . $search . '%']);
    $patients = $stmt->fetchAll();
} else {
    $patients = $pdo->query('SELECT * FROM patients ORDER BY id DESC')->fetchAll();
}

require_once __DIR__ . '/../vues/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Patients</h1>
    <a class="btn btn-primary" href="<?= e(url('/patients/formulaire.php')) ?>">Nouveau patient</a>
</div>

<form method="get" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Rechercher un patient..." value="<?= e($search) ?>">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-outline-secondary w-100">Rechercher</button>
    </div>
</form>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead><tr><th>ID</th><th>Nom</th><th>Date naissance</th><th>Sexe</th><th>Telephone</th><th>Adresse</th><th>Actions</th></tr></thead>
                <tbody>
                <?php if (!$patients): ?><tr><td colspan="7" class="text-center text-muted">Aucun patient trouve.</td></tr><?php endif; ?>
                <?php foreach ($patients as $patient): ?>
                    <tr>
                        <td><?= (int) $patient['id'] ?></td>
                        <td><?= e($patient['first_name'] . ' ' . $patient['last_name']) ?></td>
                        <td><?= e($patient['birth_date']) ?></td>
                        <td><?= e($patient['gender']) ?></td>
                        <td><?= e($patient['phone']) ?></td>
                        <td><?= e($patient['address']) ?></td>
                        <td>
                            <a class="btn btn-sm btn-warning" href="<?= e(url('/patients/formulaire.php?id=' . (int) $patient['id'])) ?>">Modifier</a>
                            <a class="btn btn-sm btn-danger" href="<?= e(url('/patients/supprimer.php?id=' . (int) $patient['id'])) ?>" onclick="return confirm('Supprimer ce patient ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../vues/footer.php'; ?>

