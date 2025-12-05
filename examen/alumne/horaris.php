<?php
require_once '../config.php';

if (!isset($_SESSION)) {
    session_start();
}

$isAlumne = !empty($_SESSION['role']) && $_SESSION['role'] === 'alumne';

if (!$isAlumne) {
    header('Location: ../error-permisos.php');
    exit;
}

$alumneId = $_SESSION['user_id'] ?? 0;

// Obtener classes de esta semana
$classes = [];
$stmt = $mysqli->prepare("SELECT c.*, i.nom as instrument_nom, p.nom as professor_nom 
    FROM classes c 
    LEFT JOIN instruments i ON c.instrument_id = i.id 
    LEFT JOIN professors p ON c.professor_id = p.id 
    WHERE c.alumne_id = ? 
    ORDER BY c.data_hora");
$stmt->bind_param("i", $alumneId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $classes[] = $row;
}
$stmt->close();

require_once '../header.php';
?>

<section class="alumne-section">
    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-clock"></i> Els Meus Horaris</h1>
            <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Tornar</a>
        </div>
        
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Data i Hora</th>
                        <th>Instrument</th>
                        <th>Professor</th>
                        <th>Aula</th>
                        <th>Duració</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($classes)): ?>
                    <tr><td colspan="5" class="text-center">No tens classes programades</td></tr>
                    <?php else: ?>
                    <?php foreach ($classes as $c): ?>
                    <tr>
                        <td><?php echo date('d/m/Y H:i', strtotime($c['data_hora'])); ?></td>
                        <td><?php echo htmlspecialchars($c['instrument_nom']); ?></td>
                        <td><?php echo htmlspecialchars($c['professor_nom']); ?></td>
                        <td><?php echo htmlspecialchars($c['aula'] ?? '-'); ?></td>
                        <td><?php echo $c['duracio']; ?> min</td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php require_once '../footer.php'; ?>
