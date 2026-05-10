<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/configuration.php';
require_once __DIR__ . '/../config/fonctions.php';
requireLogin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$isEdit = $id > 0;
$patients = $pdo->query('SELECT id, first_name, last_name FROM patients ORDER BY first_name, last_name')->fetchAll();
$doctors = $pdo->query('SELECT id, name FROM doctors ORDER BY name')->fetchAll();
$record = ['patient_id' => '', 'doctor_id' => '', 'consultation_date' => '', 'diagnosis' => '', 'treatment' => '', 'notes' => ''];

if ($isEdit) {
    $stmt = $pdo->prepare('SELECT * FROM medical_records WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $found = $stmt->fetch();
    if ($found) {
        $record = $found;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $record = [
        'patient_id' => (int) ($_POST['patient_id'] ?? 0),
        'doctor_id' => (int) ($_POST['doctor_id'] ?? 0),
        'consultation_date' => $_POST['consultation_date'] ?? '',
        'diagnosis' => trim($_POST['diagnosis'] ?? ''),
        'treatment' => trim($_POST['treatment'] ?? ''),
        'notes' => trim($_POST['notes'] ?? ''),
    ];
    if ($isEdit) {
        $stmt = $pdo->prepare('UPDATE medical_records SET patient_id=:patient_id,doctor_id=:doctor_id,consultation_date=:consultation_date,diagnosis=:diagnosis,treatment=:treatment,notes=:notes WHERE id=:id');
        $stmt->execute($record + ['id' => $id]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO medical_records (patient_id,doctor_id,consultation_date,diagnosis,treatment,notes) VALUES (:patient_id,:doctor_id,:consultation_date,:diagnosis,:treatment,:notes)');
        $stmt->execute($record);
    }
    rediriger('/dossiers_medicaux/liste.php');
}

require_once __DIR__ . '/../vues/header.php';
?>
<h1 class="h3 mb-3"><?= $isEdit ? 'Modifier dossier medical' : 'Nouveau dossier medical' ?></h1>
<div class="card shadow-sm"><div class="card-body">
<?php if (!$patients || !$doctors): ?>
    <div class="alert alert-warning">Ajoute d'abord au moins un patient et un medecin.</div>
    <a class="btn btn-secondary" href="<?= e(url('/dossiers_medicaux/liste.php')) ?>">Retour</a>
<?php else: ?>
<form method="post"><div class="row g-3">
<div class="col-md-6"><label class="form-label">Patient</label><select name="patient_id" class="form-select" required><option value="">Choisir</option><?php foreach ($patients as $patient): ?><option value="<?= (int) $patient['id'] ?>" <?= (string) $record['patient_id'] === (string) $patient['id'] ? 'selected' : '' ?>><?= e($patient['first_name'] . ' ' . $patient['last_name']) ?></option><?php endforeach; ?></select></div>
<div class="col-md-6"><label class="form-label">Medecin</label><select name="doctor_id" class="form-select" required><option value="">Choisir</option><?php foreach ($doctors as $doctor): ?><option value="<?= (int) $doctor['id'] ?>" <?= (string) $record['doctor_id'] === (string) $doctor['id'] ? 'selected' : '' ?>><?= e($doctor['name']) ?></option><?php endforeach; ?></select></div>
<div class="col-md-4"><label class="form-label">Date consultation</label><input type="date" name="consultation_date" class="form-control" required value="<?= e((string) $record['consultation_date']) ?>"></div>
<div class="col-md-8"><label class="form-label">Diagnostic</label><input type="text" name="diagnosis" class="form-control" required value="<?= e((string) $record['diagnosis']) ?>"></div>
<div class="col-md-12"><label class="form-label">Traitement</label><input type="text" name="treatment" class="form-control" required value="<?= e((string) $record['treatment']) ?>"></div>
<div class="col-md-12"><label class="form-label">Notes</label><textarea name="notes" rows="3" class="form-control"><?= e((string) $record['notes']) ?></textarea></div>
</div><div class="mt-3"><button class="btn btn-primary" type="submit">Enregistrer</button> <a class="btn btn-secondary" href="<?= e(url('/dossiers_medicaux/liste.php')) ?>">Retour</a></div></form>
<?php endif; ?>
</div></div>
<?php require_once __DIR__ . '/../vues/footer.php'; ?>

