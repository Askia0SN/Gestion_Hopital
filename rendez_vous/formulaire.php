<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/configuration.php';
require_once __DIR__ . '/../config/fonctions.php';
requireLogin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$isEdit = $id > 0;
$patients = $pdo->query('SELECT id, first_name, last_name FROM patients ORDER BY first_name, last_name')->fetchAll();
$doctors = $pdo->query('SELECT id, name FROM doctors ORDER BY name')->fetchAll();
$appointment = ['patient_id' => '', 'doctor_id' => '', 'appointment_date' => '', 'appointment_time' => '', 'status' => 'en_attente'];

if ($isEdit) {
    $stmt = $pdo->prepare('SELECT * FROM appointments WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $found = $stmt->fetch();
    if ($found) {
        $appointment = $found;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment = [
        'patient_id' => (int) ($_POST['patient_id'] ?? 0),
        'doctor_id' => (int) ($_POST['doctor_id'] ?? 0),
        'appointment_date' => $_POST['appointment_date'] ?? '',
        'appointment_time' => $_POST['appointment_time'] ?? '',
        'status' => $_POST['status'] ?? 'en_attente',
    ];
    if ($isEdit) {
        $stmt = $pdo->prepare('UPDATE appointments SET patient_id=:patient_id,doctor_id=:doctor_id,appointment_date=:appointment_date,appointment_time=:appointment_time,status=:status WHERE id=:id');
        $stmt->execute($appointment + ['id' => $id]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO appointments (patient_id,doctor_id,appointment_date,appointment_time,status) VALUES (:patient_id,:doctor_id,:appointment_date,:appointment_time,:status)');
        $stmt->execute($appointment);
    }
    rediriger('/rendez_vous/liste.php');
}

require_once __DIR__ . '/../vues/header.php';
?>
<h1 class="h3 mb-3"><?= $isEdit ? 'Modifier rendez-vous' : 'Nouveau rendez-vous' ?></h1>
<div class="card shadow-sm"><div class="card-body">
<?php if (!$patients || !$doctors): ?>
    <div class="alert alert-warning">Ajoute d'abord au moins un patient et un medecin.</div>
    <a class="btn btn-secondary" href="<?= e(url('/rendez_vous/liste.php')) ?>">Retour</a>
<?php else: ?>
<form method="post"><div class="row g-3">
<div class="col-md-6"><label class="form-label">Patient</label><select name="patient_id" class="form-select" required><option value="">Choisir</option><?php foreach ($patients as $patient): ?><option value="<?= (int) $patient['id'] ?>" <?= (string) $appointment['patient_id'] === (string) $patient['id'] ? 'selected' : '' ?>><?= e($patient['first_name'] . ' ' . $patient['last_name']) ?></option><?php endforeach; ?></select></div>
<div class="col-md-6"><label class="form-label">Medecin</label><select name="doctor_id" class="form-select" required><option value="">Choisir</option><?php foreach ($doctors as $doctor): ?><option value="<?= (int) $doctor['id'] ?>" <?= (string) $appointment['doctor_id'] === (string) $doctor['id'] ? 'selected' : '' ?>><?= e($doctor['name']) ?></option><?php endforeach; ?></select></div>
<div class="col-md-4"><label class="form-label">Date</label><input type="date" name="appointment_date" class="form-control" required value="<?= e((string) $appointment['appointment_date']) ?>"></div>
<div class="col-md-4"><label class="form-label">Heure</label><input type="time" name="appointment_time" class="form-control" required value="<?= e((string) $appointment['appointment_time']) ?>"></div>
<div class="col-md-4"><label class="form-label">Statut</label><select name="status" class="form-select"><option value="en_attente" <?= $appointment['status'] === 'en_attente' ? 'selected' : '' ?>>En attente</option><option value="confirme" <?= $appointment['status'] === 'confirme' ? 'selected' : '' ?>>Confirme</option><option value="termine" <?= $appointment['status'] === 'termine' ? 'selected' : '' ?>>Termine</option></select></div>
</div><div class="mt-3"><button class="btn btn-primary" type="submit">Enregistrer</button> <a class="btn btn-secondary" href="<?= e(url('/rendez_vous/liste.php')) ?>">Retour</a></div></form>
<?php endif; ?>
</div></div>
<?php require_once __DIR__ . '/../vues/footer.php'; ?>

