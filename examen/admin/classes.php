<?php
require_once '../config.php';

if (!isset($_SESSION)) {
    session_start();
}

$isAdmin = !empty($_SESSION['role']) && $_SESSION['role'] === 'admin';

if (!$isAdmin) {
    header('Location: ../error-permisos.php');
    exit;
}

$action = $_GET['action'] ?? 'list';
$message = '';
$error = '';
$classeEdit = null;

// Obtener lista de instruments para el select
$instruments = [];
$resultInst = $mysqli->query("SELECT id, nom FROM instruments ORDER BY nom");
if ($resultInst) {
    while ($row = $resultInst->fetch_assoc()) {
        $instruments[] = $row;
    }
}

// Obtener lista de professors para el select
$professors = [];
$resultProf = $mysqli->query("SELECT id, nom, cognoms FROM professors ORDER BY nom, cognoms");
if ($resultProf) {
    while ($row = $resultProf->fetch_assoc()) {
        $professors[] = $row;
    }
}

// Obtener lista de alumnes para el select
$alumnes = [];
$resultAlum = $mysqli->query("SELECT id, nom, cognoms FROM usuaris WHERE rol = 'alumne' ORDER BY nom, cognoms");
if ($resultAlum) {
    while ($row = $resultAlum->fetch_assoc()) {
        $alumnes[] = $row;
    }
}

// Procesar formulario POST (crear/editar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $instrument_id = (int)($_POST['instrument_id'] ?? 0);
    $professor_id = (int)($_POST['professor_id'] ?? 0);
    $alumne_id = (int)($_POST['alumne_id'] ?? 0);
    $data_hora = $_POST['data_hora'] ?? '';
    $duracio = (int)($_POST['duracio'] ?? 60);
    $aula = trim($_POST['aula'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    
    if (empty($instrument_id) || empty($professor_id) || empty($alumne_id) || empty($data_hora)) {
        $error = "Els camps Instrument, Professor, Alumne i Data/Hora són obligatoris.";
    } else {
        if (!empty($id)) {
            // Editar classe existent
            $stmt = $mysqli->prepare("UPDATE classes SET instrument_id = ?, professor_id = ?, alumne_id = ?, data_hora = ?, duracio = ?, aula = ?, notes = ? WHERE id = ?");
            $stmt->bind_param("iiisisssi", $instrument_id, $professor_id, $alumne_id, $data_hora, $duracio, $aula, $notes, $id);
            
            if ($stmt->execute()) {
                header('Location: classes.php?msg=updated');
                exit;
            } else {
                $error = "Error al actualitzar la classe: " . $mysqli->error;
            }
            $stmt->close();
        } else {
            // Crear nova classe
            $stmt = $mysqli->prepare("INSERT INTO classes (instrument_id, professor_id, alumne_id, data_hora, duracio, aula, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiisiss", $instrument_id, $professor_id, $alumne_id, $data_hora, $duracio, $aula, $notes);
            
            if ($stmt->execute()) {
                header('Location: classes.php?msg=created');
                exit;
            } else {
                $error = "Error al crear la classe: " . $mysqli->error;
            }
            $stmt->close();
        }
    }
}

// Procesar acción de eliminar
if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $mysqli->prepare("DELETE FROM classes WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header('Location: classes.php?msg=deleted');
        exit;
    } else {
        $error = "Error al eliminar la classe: " . $mysqli->error;
    }
    $stmt->close();
}

// Cargar datos de la classe para editar
if ($action === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $mysqli->prepare("SELECT * FROM classes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $classeEdit = $result->fetch_assoc();
    $stmt->close();
    
    if (!$classeEdit) {
        header('Location: classes.php?error=notfound');
        exit;
    }
}

// Mensajes de confirmación
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'created':
            $message = "Classe creada correctament.";
            break;
        case 'updated':
            $message = "Classe actualitzada correctament.";
            break;
        case 'deleted':
            $message = "Classe eliminada correctament.";
            break;
    }
}

// Filtros
$filterInstrument = $_GET['instrument'] ?? '';
$filterProfessor = $_GET['professor'] ?? '';
$filterDate = $_GET['data'] ?? '';

// Obtener lista de classes con datos relacionados
$sql = "SELECT c.*, 
        i.nom as instrument_nom,
        p.nom as professor_nom, p.cognoms as professor_cognoms,
        u.nom as alumne_nom, u.cognoms as alumne_cognoms
        FROM classes c 
        LEFT JOIN instruments i ON c.instrument_id = i.id
        LEFT JOIN professors p ON c.professor_id = p.id
        LEFT JOIN usuaris u ON c.alumne_id = u.id
        WHERE 1=1";

if (!empty($filterInstrument)) {
    $sql .= " AND c.instrument_id = " . (int)$filterInstrument;
}
if (!empty($filterProfessor)) {
    $sql .= " AND c.professor_id = " . (int)$filterProfessor;
}
if (!empty($filterDate)) {
    $sql .= " AND DATE(c.data_hora) = '" . $mysqli->real_escape_string($filterDate) . "'";
}

$sql .= " ORDER BY c.data_hora DESC";

$result = $mysqli->query($sql);
$classes = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }
}

require_once '../header.php';
?>

<section class="admin-section">
    <div class="container">
        <div class="admin-header">
            <h1><i class="fas fa-calendar-alt"></i> Gestió de Classes</h1>
            <div class="header-actions">
                <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Tornar</a>
                <?php if ($action === 'list'): ?>
                    <a href="classes.php?action=create" class="btn btn-primary"><i class="fas fa-plus"></i> Nova Classe</a>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ($action === 'create' || $action === 'edit'): ?>
        <!-- Formulario Crear/Editar solo se llama si es create o edit hostia -->
        <div class="form-card">
            <h2>
                <?php if ($action === 'create'): ?>
                    <i class="fas fa-calendar-plus"></i> Crear Nova Classe
                <?php else: ?>
                    <i class="fas fa-edit"></i> Editar Classe
                <?php endif; ?>
            </h2>
            
            <form action="classes.php" method="POST" class="admin-form">
                <input type="hidden" name="id" value="<?php echo $classeEdit['id'] ?? ''; ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="instrument_id">
                            <i class="fas fa-guitar"></i> Instrument *
                        </label>
                        <select id="instrument_id" name="instrument_id" required>
                            <option value="">-- Selecciona un instrument --</option>
                            <?php foreach ($instruments as $inst): ?>
                            <option value="<?php echo $inst['id']; ?>" <?php echo (($classeEdit['instrument_id'] ?? '') == $inst['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($inst['nom']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="professor_id">
                            <i class="fas fa-user-tie"></i> Professor *
                        </label>
                        <select id="professor_id" name="professor_id" required>
                            <option value="">-- Selecciona un professor --</option>
                            <?php foreach ($professors as $prof): ?>
                            <option value="<?php echo $prof['id']; ?>" <?php echo (($classeEdit['professor_id'] ?? '') == $prof['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($prof['nom'] . ' ' . $prof['cognoms']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="alumne_id">
                            <i class="fas fa-user-graduate"></i> Alumne *
                        </label>
                        <select id="alumne_id" name="alumne_id" required>
                            <option value="">-- Selecciona un alumne --</option>
                            <?php foreach ($alumnes as $alum): ?>
                            <option value="<?php echo $alum['id']; ?>" <?php echo (($classeEdit['alumne_id'] ?? '') == $alum['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($alum['nom'] . ' ' . $alum['cognoms']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="aula">
                            <i class="fas fa-door-open"></i> Aula
                        </label>
                        <input type="text" id="aula" name="aula" placeholder="Ex: Aula 1, Sala de Piano..." value="<?php echo htmlspecialchars($classeEdit['aula'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="data_hora">
                            <i class="fas fa-calendar"></i> Data i Hora *
                        </label>
                        <input type="datetime-local" id="data_hora" name="data_hora" required value="<?php echo isset($classeEdit['data_hora']) ? date('Y-m-d\TH:i', strtotime($classeEdit['data_hora'])) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="duracio">
                            <i class="fas fa-clock"></i> Duració (minuts)
                        </label>
                        <select id="duracio" name="duracio">
                            <option value="30" <?php echo (($classeEdit['duracio'] ?? 60) == 30) ? 'selected' : ''; ?>>30 minuts</option>
                            <option value="45" <?php echo (($classeEdit['duracio'] ?? 60) == 45) ? 'selected' : ''; ?>>45 minuts</option>
                            <option value="60" <?php echo (($classeEdit['duracio'] ?? 60) == 60) ? 'selected' : ''; ?>>60 minuts (1 hora)</option>
                            <option value="90" <?php echo (($classeEdit['duracio'] ?? 60) == 90) ? 'selected' : ''; ?>>90 minuts (1.5 hores)</option>
                            <option value="120" <?php echo (($classeEdit['duracio'] ?? 60) == 120) ? 'selected' : ''; ?>>120 minuts (2 hores)</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="notes">
                        <i class="fas fa-sticky-note"></i> Notes
                    </label>
                    <textarea id="notes" name="notes" rows="3" 
                              placeholder="Notes addicionals sobre la classe..."><?php echo htmlspecialchars($classeEdit['notes'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> 
                        <?php echo $action === 'create' ? 'Crear Classe' : 'Guardar Canvis'; ?>
                    </button>
                    <a href="classes.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel·lar
                    </a>
                </div>
            </form>
        </div>
        
        <?php else: ?>
        <!-- Filtros -->
        <div class="filters-section">
            <form method="GET" class="filters-row">
                <div class="filter-group">
                    <label><i class="fas fa-guitar"></i> Instrument:</label>
                    <select name="instrument" onchange="this.form.submit()">
                        <option value="">Tots</option>
                        <?php foreach ($instruments as $inst): ?>
                        <option value="<?php echo $inst['id']; ?>" <?php echo ($filterInstrument == $inst['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($inst['nom']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label><i class="fas fa-calendar"></i> Data:</label>
                    <input type="date" name="data" value="<?php echo htmlspecialchars($filterDate); ?>" onchange="this.form.submit()">
                </div>
                <div class="filter-group">
                    <label><i class="fas fa-user-tie"></i> Professor:</label>
                    <select name="professor" onchange="this.form.submit()">
                        <option value="">Tots</option>
                        <?php foreach ($professors as $prof): ?>
                        <option value="<?php echo $prof['id']; ?>" <?php echo ($filterProfessor == $prof['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($prof['nom'] . ' ' . $prof['cognoms']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if (!empty($filterInstrument) || !empty($filterProfessor) || !empty($filterDate)): ?>
                <div class="filter-group">
                    <a href="classes.php" class="btn btn-secondary btn-sm"><i class="fas fa-times"></i> Netejar filtres</a>
                </div>
                <?php endif; ?>
            </form>
        </div>
        
        <!-- Lista de Classes -->
        <div class="table-responsive">
            <table class="data-table" id="classesTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data i Hora</th>
                        <th>Instrument</th>
                        <th>Professor</th>
                        <th>Alumne</th>
                        <th>Aula</th>
                        <th>Duració</th>
                        <th>Accions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($classes)): ?>
                        <tr>
                            <td colspan="8" class="text-center">No hi ha classes registrades</td>
                        </tr>
                    <?php else: ?>
                    <?php foreach ($classes as $classe): ?>
                    <tr>
                        <td><?php echo $classe['id']; ?></td>
                        <td><i class="fas fa-calendar"></i> <?php echo date('d/m/Y H:i', strtotime($classe['data_hora'])); ?></td>
                        <td><span class="badge badge-primary"><i class="fas fa-guitar"></i> <?php echo htmlspecialchars($classe['instrument_nom'] ?? 'N/A'); ?></span></td>
                        <td><?php echo htmlspecialchars(($classe['professor_nom'] ?? '') . ' ' . ($classe['professor_cognoms'] ?? '')); ?></td>
                        <td><?php echo htmlspecialchars(($classe['alumne_nom'] ?? '') . ' ' . ($classe['alumne_cognoms'] ?? '')); ?></td>
                        <td><?php echo htmlspecialchars($classe['aula'] ?? '-'); ?></td>
                        <td><?php echo $classe['duracio']; ?> min</td>
                        <td class="actions">
                            <a href="classes.php?action=edit&id=<?php echo $classe['id']; ?>" class="btn-icon btn-edit" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="classes.php?action=delete&id=<?php echo $classe['id']; ?>" class="btn-icon btn-delete" title="Eliminar"
                               onclick="return confirm('Segur que vols eliminar aquesta classe?');">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once '../footer.php'; ?>
