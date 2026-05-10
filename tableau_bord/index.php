<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/configuration.php';
require_once __DIR__ . '/../config/fonctions.php';
requireLogin();

$patientsCount = (int) $pdo->query('SELECT COUNT(*) FROM patients')->fetchColumn();
$doctorsCount = (int) $pdo->query('SELECT COUNT(*) FROM doctors')->fetchColumn();
$stmtToday = $pdo->prepare(
    'SELECT a.id, p.first_name, p.last_name, d.name AS doctor_name, a.appointment_date, a.appointment_time, a.status
     FROM appointments a
     JOIN patients p ON p.id = a.patient_id
     JOIN doctors d ON d.id = a.doctor_id
     WHERE a.appointment_date = :today
     ORDER BY a.appointment_time ASC'
);
$stmtToday->execute(['today' => date('Y-m-d')]);
$todayAppointments = $stmtToday->fetchAll();

require_once __DIR__ . '/../vues/header.php';
?>

<h1 class="h3 mb-4">Tableau de bord</h1>
<p class="text-muted">Bienvenue, <?= e($_SESSION['user_name'] ?? 'Utilisateur') ?></p>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm"><div class="card-body"><h2 class="h6 text-muted">Patients</h2><p class="display-6 mb-0"><?= $patientsCount ?></p></div></div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm"><div class="card-body"><h2 class="h6 text-muted">Medecins</h2><p class="display-6 mb-0"><?= $doctorsCount ?></p></div></div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm"><div class="card-body"><h2 class="h6 text-muted">Rendez-vous du jour</h2><p class="display-6 mb-0"><?= count($todayAppointments) ?></p></div></div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <h2 class="h5 mb-3">Rendez-vous aujourd'hui</h2>
        <?php if (!$todayAppointments): ?>
            <p class="text-muted mb-0">Aucun rendez-vous pour aujourd'hui.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead><tr><th>Patient</th><th>Medecin</th><th>Date</th><th>Heure</th><th>Statut</th></tr></thead>
                    <tbody>
                    <?php foreach ($todayAppointments as $appointment): ?>
                        <tr>
                            <td><?= e($appointment['first_name'] . ' ' . $appointment['last_name']) ?></td>
                            <td><?= e($appointment['doctor_name']) ?></td>
                            <td><?= e($appointment['appointment_date']) ?></td>
                            <td><?= e($appointment['appointment_time']) ?></td>
                            <td><?= e($appointment['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../vues/footer.php'; ?>

