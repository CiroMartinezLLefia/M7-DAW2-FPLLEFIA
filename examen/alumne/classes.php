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

// Obtener lista de instruments para el filtro
$instruments = [];
$resultInst = $mysqli->query("SELECT id, nom FROM instruments ORDER BY nom");
if ($resultInst) {
    while ($row = $resultInst->fetch_assoc()) {
        $instruments[] = $row;
    }
}

// Filtros
$filterInstrument = $_GET['instrument'] ?? '';
$filterDate = $_GET['data'] ?? '';

// Obtener classes del alumne logueado
$sql = "SELECT c.*, 
        i.nom as instrument_nom,
        p.nom as professor_nom, p.cognoms as professor_cognoms, p.email as professor_email
        FROM classes c 
        LEFT JOIN instruments i ON c.instrument_id = i.id
        LEFT JOIN professors p ON c.professor_id = p.id
        WHERE c.alumne_id = ?";

if (!empty($filterInstrument)) {
    $sql .= " AND c.instrument_id = " . (int)$filterInstrument;
}
if (!empty($filterDate)) {
    $sql .= " AND DATE(c.data_hora) = '" . $mysqli->real_escape_string($filterDate) . "'";
}

$sql .= " ORDER BY c.data_hora DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $alumneId);
$stmt->execute();
$result = $stmt->get_result();
$classes = [];
while ($row = $result->fetch_assoc()) {
    $classes[] = $row;
}
$stmt->close();

require_once '../header.php';
?>

<section class="alumne-section">
    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-calendar"></i> Les Meves Classes</h1>
            <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Tornar</a>
        </div>
        
        <!-- Filtros -->
        <div class="filters-card">
            <form method="GET" class="filters-form">
                <div class="filter-group">
                    <label for="instrument"><i class="fas fa-guitar"></i> Instrument:</label>
                    <select id="instrument" name="instrument" onchange="this.form.submit()">
                        <option value="">Tots els instruments</option>
                        <?php foreach ($instruments as $inst): ?>
                        <option value="<?php echo $inst['id']; ?>" <?php echo ($filterInstrument == $inst['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($inst['nom']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="data"><i class="fas fa-calendar"></i> Data:</label>
                    <input type="date" id="data" name="data" value="<?php echo htmlspecialchars($filterDate); ?>" onchange="this.form.submit()">
                </div>
                <a href="classes.php" class="btn btn-secondary"><i class="fas fa-times"></i> Netejar</a>
            </form>
        </div>
        
        <!-- Classes en formato tabla -->
        <div class="classes-list" id="listView">
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
                            <tr>
                                <td colspan="5" class="text-center">No tens classes registrades</td>
                            </tr>
                        <?php else: ?>
                        <?php foreach ($classes as $classe): ?>
                        <tr>
                            <td>
                                <strong><?php echo date('d/m/Y', strtotime($classe['data_hora'])); ?></strong><br>
                                <span class="text-muted"><?php echo date('H:i', strtotime($classe['data_hora'])); ?></span>
                            </td>
                            <td><span class="badge badge-primary"><i class="fas fa-guitar"></i> <?php echo htmlspecialchars($classe['instrument_nom']); ?></span></td>
                            <td>
                                <div class="teacher-info">
                                    <strong><?php echo htmlspecialchars($classe['professor_nom'] . ' ' . $classe['professor_cognoms']); ?></strong><br>
                                    <small><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($classe['professor_email'] ?? ''); ?></small>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($classe['aula'] ?? '-'); ?></td>
                            <td><?php echo $classe['duracio']; ?> min</td>
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