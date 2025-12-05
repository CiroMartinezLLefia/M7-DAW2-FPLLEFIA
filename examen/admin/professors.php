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
$professorEdit = null;
$professorEspecialitats = [];

// Obtener lista de instruments para los checkboxes
$instruments = [];
$resultInst = $mysqli->query("SELECT id, nom FROM instruments ORDER BY nom");
if ($resultInst) {
    while ($row = $resultInst->fetch_assoc()) {
        $instruments[] = $row;
    }
}

// Procesar formulario POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $nom = trim($_POST['nom'] ?? '');
    $cognoms = trim($_POST['cognoms'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $especialitats = $_POST['especialitat'] ?? [];
    
    if (!empty($id)) {
        $stmt = $mysqli->prepare("UPDATE professors SET nom = ?, cognoms = ?, email = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nom, $cognoms, $email, $id);
        $stmt->execute();
        $stmt->close();
        
        $mysqli->query("DELETE FROM professor_instrument WHERE professor_id = $id");
    } else {
        $stmt = $mysqli->prepare("INSERT INTO professors (nom, cognoms, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nom, $cognoms, $email);
        $stmt->execute();
        $id = $mysqli->insert_id;
        $stmt->close();
    }
    
    foreach ($especialitats as $instId) {
        $mysqli->query("INSERT INTO professor_instrument (professor_id, instrument_id) VALUES ($id, $instId)");
    }
    
    header('Location: professors.php');
    exit;
}

// Eliminar
if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $mysqli->query("DELETE FROM professor_instrument WHERE professor_id = $id");
    $mysqli->query("DELETE FROM professors WHERE id = $id");
    header('Location: professors.php');
    exit;
}

// Cargar datos para editar
if ($action === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $result = $mysqli->query("SELECT * FROM professors WHERE id = $id");
    $professorEdit = $result->fetch_assoc();
    
    $resultEsp = $mysqli->query("SELECT instrument_id FROM professor_instrument WHERE professor_id = $id");
    while ($row = $resultEsp->fetch_assoc()) {
        $professorEspecialitats[] = $row['instrument_id'];
    }
}

// Obtener lista de professors
$sql = "SELECT * FROM professors ORDER BY nom, cognoms";
$result = $mysqli->query($sql);
$professors = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $professors[] = $row;
    }
}

require_once '../header.php';
?>

<section class="admin-section">
    <div class="container">
        <div class="admin-header">
            <h1><i class="fas fa-chalkboard-teacher"></i> Gestió de Professors</h1>
            <div class="header-actions">
                <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Tornar</a>
                <?php if ($action === 'list'): ?>
                <a href="professors.php?action=create" class="btn btn-primary"><i class="fas fa-plus"></i> Nou Professor</a>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ($action === 'create' || $action === 'edit'): ?>
        <!-- Formulario Crear/Editar -->
        <div class="form-card">
            <h2>
                <?php if ($action === 'create'): ?>
                    <i class="fas fa-user-tie"></i> Crear Nou Professor
                <?php else: ?>
                    <i class="fas fa-edit"></i> Editar Professor
                <?php endif; ?>
            </h2>
            
            <form action="professors.php" method="POST" class="admin-form">
                <input type="hidden" name="id" value="<?php echo $professorEdit['id'] ?? ''; ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nom">
                            <i class="fas fa-user"></i> Nom *
                        </label>
                        <input type="text" id="nom" name="nom" placeholder="Nom del professor" required value="<?php echo htmlspecialchars($professorEdit['nom'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="cognoms">
                            <i class="fas fa-user"></i> Cognoms *
                        </label>
                        <input type="text" id="cognoms" name="cognoms" placeholder="Cognoms del professor" required value="<?php echo htmlspecialchars($professorEdit['cognoms'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Correu electrònic *
                    </label>
                    <input type="email" id="email" name="email" placeholder="email@exemple.com" required value="<?php echo htmlspecialchars($professorEdit['email'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>
                        <i class="fas fa-guitar"></i> Especialitats (Instruments que ensenya)
                    </label>
                    <div class="checkbox-grid">
                        <?php foreach ($instruments as $instrument): ?>
                        <label class="checkbox-label">
                            <input type="checkbox" name="especialitat[]" value="<?php echo $instrument['id']; ?>"
                                   <?php echo in_array($instrument['id'], $professorEspecialitats) ? 'checked' : ''; ?>>
                            <span><?php echo htmlspecialchars($instrument['nom']); ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> 
                        <?php echo $action === 'create' ? 'Crear Professor' : 'Guardar Canvis'; ?>
                    </button>
                    <a href="professors.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel·lar
                    </a>
                </div>
            </form>
        </div>
        
        <?php else: ?>
        <!-- Lista de Professors -->
        <div class="table-responsive">
            <table class="data-table" id="professorsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom Complet</th>
                        <th>Email</th>
                        <th>Accions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($professors as $professor): ?>
                    <tr>
                        <td><?php echo $professor['id']; ?></td>
                        <td><?php echo htmlspecialchars($professor['nom'] . ' ' . $professor['cognoms']); ?></td>
                        <td><?php echo htmlspecialchars($professor['email']); ?></td>
                        <td class="actions">
                            <a href="professors.php?action=edit&id=<?php echo $professor['id']; ?>" class="btn-icon btn-edit"><i class="fas fa-edit"></i></a>
                            <a href="professors.php?action=delete&id=<?php echo $professor['id']; ?>" class="btn-icon btn-delete" onclick="return confirm('Eliminar?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once '../footer.php'; ?>
