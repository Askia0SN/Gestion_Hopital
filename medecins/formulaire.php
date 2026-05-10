<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/configuration.php';
require_once __DIR__ . '/../config/fonctions.php';
requireLogin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$isEdit = $id > 0;
$doctor = ['name' => '', 'specialty' => '', 'phone' => '', 'email' => ''];

if ($isEdit) {
    $stmt = $pdo->prepare('SELECT * FROM doctors WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $found = $stmt->fetch();
    if ($found) {
        $doctor = $found;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor = [
        'name' => trim($_POST['name'] ?? ''),
        'specialty' => trim($_POST['specialty'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
    ];
    if ($isEdit) {
        $stmt = $pdo->prepare('UPDATE doctors SET name=:name,specialty=:specialty,phone=:phone,email=:email WHERE id=:id');
        $stmt->execute($doctor + ['id' => $id]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO doctors (name,specialty,phone,email) VALUES (:name,:specialty,:phone,:email)');
        $stmt->execute($doctor);
    }
    rediriger('/medecins/liste.php');
}

require_once __DIR__ . '/../vues/header.php';
?>
<h1 class="h3 mb-3"><?= $isEdit ? 'Modifier medecin' : 'Nouveau medecin' ?></h1>
<div class="card shadow-sm"><div class="card-body">
<form method="post">
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Nom complet</label><input type="text" name="name" class="form-control" required value="<?= e((string) $doctor['name']) ?>"></div>
<div class="col-md-6"><label class="form-label">Specialite</label><input type="text" name="specialty" class="form-control" required value="<?= e((string) $doctor['specialty']) ?>"></div>
<div class="col-md-6"><label class="form-label">Telephone</label><input type="text" name="phone" class="form-control" required value="<?= e((string) $doctor['phone']) ?>"></div>
<div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required value="<?= e((string) $doctor['email']) ?>"></div>
</div>
<div class="mt-3"><button class="btn btn-primary" type="submit">Enregistrer</button> <a class="btn btn-secondary" href="<?= e(url('/medecins/liste.php')) ?>">Retour</a></div>
</form>
</div></div>
<?php require_once __DIR__ . '/../vues/footer.php'; ?>

