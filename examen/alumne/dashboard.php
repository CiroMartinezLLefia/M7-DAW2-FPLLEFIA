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
$alumneNom = $_SESSION['user_name'] ?? 'Alumne';

// Contar classes
$totalClasses = 0;
$stmt = $mysqli->prepare("SELECT COUNT(*) as total FROM classes WHERE alumne_id = ?");
$stmt->bind_param("i", $alumneId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalClasses = $row['total'];
$stmt->close();

// Properes classes
$properes = [];
$stmt = $mysqli->prepare("SELECT c.*, i.nom as instrument_nom, p.nom as professor_nom 
    FROM classes c 
    LEFT JOIN instruments i ON c.instrument_id = i.id 
    LEFT JOIN professors p ON c.professor_id = p.id 
    WHERE c.alumne_id = ? AND c.data_hora >= NOW() 
    ORDER BY c.data_hora LIMIT 3");
$stmt->bind_param("i", $alumneId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $properes[] = $row;
}
$stmt->close();

require_once '../header.php';
?>

<section class="alumne-section">
    <div class="container">
        <div class="welcome-banner">
            <div class="welcome-content">
                <div class="welcome-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="welcome-text">
                    <h1>Hola, <?php echo htmlspecialchars($alumneNom); ?>!</h1>
                    <p>Benvingut/da al teu panell d'alumne</p>
                </div>
            </div>
        </div>
        
        <div class="stats-grid stats-small">
            <div class="stat-card stat-primary">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <h3>Classes Totals</h3>
                    <p class="stat-number"><?php echo $totalClasses; ?></p>
                </div>
            </div>
        </div>
        
        <div class="upcoming-section">
            <div class="section-header">
                <h2><i class="fas fa-calendar-alt"></i> Properes Classes</h2>
                <a href="classes.php" class="btn btn-link">Veure totes</a>
            </div>
            
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Instrument</th>
                            <th>Professor</th>
                            <th>Aula</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($properes)): ?>
                        <tr><td colspan="4" class="text-center">No tens classes programades</td></tr>
                        <?php else: ?>
                        <?php foreach ($properes as $c): ?>
                        <tr>
                            <td><?php echo date('d/m/Y H:i', strtotime($c['data_hora'])); ?></td>
                            <td><?php echo htmlspecialchars($c['instrument_nom']); ?></td>
                            <td><?php echo htmlspecialchars($c['professor_nom']); ?></td>
                            <td><?php echo htmlspecialchars($c['aula'] ?? '-'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="quick-actions">
            <h2><i class="fas fa-bolt"></i> Accions Ràpides</h2>
            <div class="actions-grid">
                <a href="classes.php" class="action-card">
                    <i class="fas fa-calendar"></i>
                    <span>Les Meves Classes</span>
                </a>
                <a href="horaris.php" class="action-card">
                    <i class="fas fa-clock"></i>
                    <span>Horaris</span>
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once '../footer.php'; ?>
