<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/configuration.php';
require_once __DIR__ . '/../config/fonctions.php';
requireLogin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$isEdit = $id > 0;

$patient = ['first_name' => '', 'last_name' => '', 'birth_date' => '', 'gender' => 'M', 'phone' => '', 'address' => ''];

if ($isEdit) {
    $stmt = $pdo->prepare('SELECT * FROM patients WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $found = $stmt->fetch();
    if ($found) {
        $patient = $found;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient = [
        'first_name' => trim($_POST['first_name'] ?? ''),
        'last_name' => trim($_POST['last_name'] ?? ''),
        'birth_date' => $_POST['birth_date'] ?? '',
        'gender' => $_POST['gender'] ?? 'M',
        'phone' => trim($_POST['phone'] ?? ''),
        'address' => trim($_POST['address'] ?? ''),
    ];

    if ($isEdit) {
        $stmt = $pdo->prepare('UPDATE patients SET first_name=:first_name,last_name=:last_name,birth_date=:birth_date,gender=:gender,phone=:phone,address=:address WHERE id=:id');
        $stmt->execute($patient + ['id' => $id]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO patients (first_name,last_name,birth_date,gender,phone,address) VALUES (:first_name,:last_name,:birth_date,:gender,:phone,:address)');
        $stmt->execute($patient);
    }

    rediriger('/patients/liste.php');
}

require_once __DIR__ . '/../vues/header.php';
?>
<h1 class="h3 mb-3"><?= $isEdit ? 'Modifier patient' : 'Nouveau patient' ?></h1>
<div class="card shadow-sm">
    <div class="card-body">
        <form method="post">
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Prenom</label><input type="text" name="first_name" class="form-control" required value="<?= e((string) $patient['first_name']) ?>"></div>
                <div class="col-md-6"><label class="form-label">Nom</label><input type="text" name="last_name" class="form-control" required value="<?= e((string) $patient['last_name']) ?>"></div>
                <div class="col-md-4"><label class="form-label">Date de naissance</label><input type="date" name="birth_date" class="form-control" required value="<?= e((string) $patient['birth_date']) ?>"></div>
                <div class="col-md-4">
                    <label class="form-label">Sexe</label>
                    <select name="gender" class="form-select" required>
                        <option value="M" <?= $patient['gender'] === 'M' ? 'selected' : '' ?>>M</option>
                        <option value="F" <?= $patient['gender'] === 'F' ? 'selected' : '' ?>>F</option>
                    </select>
                </div>
                <div class="col-md-4"><label class="form-label">Telephone</label><input type="text" name="phone" class="form-control" required value="<?= e((string) $patient['phone']) ?>"></div>
                <div class="col-12"><label class="form-label">Adresse</label><input type="text" name="address" class="form-control" required value="<?= e((string) $patient['address']) ?>"></div>
            </div>
            <div class="mt-3">
                <button class="btn btn-primary" type="submit">Enregistrer</button>
                <a class="btn btn-secondary" href="<?= e(url('/patients/liste.php')) ?>">Retour</a>
            </div>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../vues/footer.php'; ?>

