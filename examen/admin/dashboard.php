<?php
require_once '../config.php';
require_once '../header.php';

if (!$isAdmin) {
    header('Location: ../error-permisos.php');
    exit;
}

// Obtener estadísticas de la base de datos
$totalInstruments = 0;
$totalAlumnes = 0;
$totalProfessors = 0;
$totalClasses = 0;

// Contar instruments
$result = $mysqli->query("SELECT COUNT(*) as total FROM instruments");
if ($result) {
    $row = $result->fetch_assoc();
    $totalInstruments = $row['total'];
}

// Contar alumnes (usuarios con rol 'alumne')
$result = $mysqli->query("SELECT COUNT(*) as total FROM usuaris WHERE rol = 'alumne'");
if ($result) {
    $row = $result->fetch_assoc();
    $totalAlumnes = $row['total'];
}

// Contar professors
$result = $mysqli->query("SELECT COUNT(*) as total FROM professors");
if ($result) {
    $row = $result->fetch_assoc();
    $totalProfessors = $row['total'];
}

// Contar classes
$result = $mysqli->query("SELECT COUNT(*) as total FROM classes");
if ($result) {
    $row = $result->fetch_assoc();
    $totalClasses = $row['total'];
}

// Obtener últimas classes programadas con información relacionada
$ultimesClasses = [];
$sql = "SELECT c.id, c.data_hora, c.aula, 
               i.nom as instrument_nom, 
               p.nom as professor_nom,
               u.nom as alumne_nom
        FROM classes c
        LEFT JOIN instruments i ON c.instrument_id = i.id
        LEFT JOIN professors p ON c.professor_id = p.id
        LEFT JOIN usuaris u ON c.alumne_id = u.id
        ORDER BY c.data_hora DESC
        LIMIT 5";

$result = $mysqli->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $ultimesClasses[] = $row;
    }
}
?>

<section class="admin-section">
    <div class="container">
        <div class="admin-header">
            <h1><i class="fas fa-tachometer-alt"></i> Dashboard Administració</h1>
            <p>Benvingut al panell d'administració de MusicSchool Academy</p>
        </div>
        
        <!-- Estadístiques -->
        <div class="stats-grid">
            <div class="stat-card stat-primary">
                <div class="stat-icon">
                    <i class="fas fa-guitar"></i>
                </div>
                <div class="stat-content">
                    <h3>Instruments</h3>
                    <p class="stat-number"><?php echo $totalInstruments; ?></p>
                </div>
                <a href="instruments.php" class="stat-link">Gestionar <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="stat-card stat-success">
                <div class="stat-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-content">
                    <h3>Alumnes</h3>
                    <p class="stat-number"><?php echo $totalAlumnes; ?></p>
                </div>
                <a href="alumnes.php" class="stat-link">Gestionar <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="stat-card stat-warning">
                <div class="stat-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-content">
                    <h3>Professors</h3>
                    <p class="stat-number"><?php echo $totalProfessors; ?></p>
                </div>
                <a href="professors.php" class="stat-link">Gestionar <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="stat-card stat-info">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-content">
                    <h3>Classes</h3>
                    <p class="stat-number"><?php echo $totalClasses; ?></p>
                </div>
                <a href="classes.php" class="stat-link">Gestionar <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
        
        <!-- Accions ràpides -->
        <div class="quick-actions">
            <h2><i class="fas fa-bolt"></i> Accions Ràpides</h2>
            <div class="actions-grid">
                <a href="instruments.php?action=create" class="action-card">
                    <i class="fas fa-plus-circle"></i>
                    <span>Nou Instrument</span>
                </a>
                <a href="alumnes.php?action=create" class="action-card">
                    <i class="fas fa-user-plus"></i>
                    <span>Nou Alumne</span>
                </a>
                <a href="professors.php?action=create" class="action-card">
                    <i class="fas fa-user-tie"></i>
                    <span>Nou Professor</span>
                </a>
                <a href="classes.php?action=create" class="action-card">
                    <i class="fas fa-calendar-plus"></i>
                    <span>Nova Classe</span>
                </a>
            </div>
        </div>
        
        <!-- Últimes classes -->
        <div class="recent-section">
            <h2><i class="fas fa-clock"></i> Últimes Classes Programades</h2>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Data i Hora</th>
                            <th>Instrument</th>
                            <th>Professor</th>
                            <th>Alumne</th>
                            <th>Aula</th>
                            <th>Accions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($ultimesClasses)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">No hi ha classes programades</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($ultimesClasses as $classe): ?>
                        <tr>
                            <td><i class="fas fa-calendar"></i> <?php echo date('d/m/Y H:i', strtotime($classe['data_hora'])); ?></td>
                            <td><i class="fas fa-music"></i> <?php echo htmlspecialchars($classe['instrument_nom'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($classe['professor_nom'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($classe['alumne_nom'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($classe['aula'] ?? 'N/A'); ?></td>
                            <td class="actions">
                                <a href="classes.php?action=edit&id=<?php echo $classe['id']; ?>" class="btn-icon btn-edit" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="classes.php?action=delete&id=<?php echo $classe['id']; ?>" class="btn-icon btn-delete" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php require_once '../footer.php'; ?>
