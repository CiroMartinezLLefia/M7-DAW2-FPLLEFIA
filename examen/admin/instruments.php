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
$instrumentEdit = null;

// Procesar formulario POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $nom = trim($_POST['nom'] ?? '');
    $descripcio = trim($_POST['descripcio'] ?? '');
    
    if (!empty($id)) {
        $stmt = $mysqli->prepare("UPDATE instruments SET nom = ?, descripcio = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nom, $descripcio, $id);
    } else {
        $stmt = $mysqli->prepare("INSERT INTO instruments (nom, descripcio) VALUES (?, ?)");
        $stmt->bind_param("ss", $nom, $descripcio);
    }
    $stmt->execute();
    $stmt->close();
    header('Location: instruments.php');
    exit;
}

// Eliminar
if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $mysqli->query("DELETE FROM professor_instrument WHERE instrument_id = $id");
    $mysqli->query("DELETE FROM instruments WHERE id = $id");
    header('Location: instruments.php');
    exit;
}

// Cargar datos para editar
if ($action === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $result = $mysqli->query("SELECT * FROM instruments WHERE id = $id");
    $instrumentEdit = $result->fetch_assoc();
}

// Obtener lista de instruments
$sql = "SELECT * FROM instruments ORDER BY nom";
$result = $mysqli->query($sql);
$instruments = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $instruments[] = $row;
    }
}

require_once '../header.php';
?>

<section class="admin-section">
    <div class="container">
        <div class="admin-header">
            <h1><i class="fas fa-guitar"></i> Gestió d'Instruments</h1>
            <div class="header-actions">
                <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Tornar</a>
                <?php if ($action === 'list'): ?>
                <a href="instruments.php?action=create" class="btn btn-primary"><i class="fas fa-plus"></i> Nou Instrument</a>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ($action === 'create' || $action === 'edit'): ?>
        <!-- Formulario Crear/Editar -->
        <div class="form-card">
            <h2>
                <?php if ($action === 'create'): ?>
                    <i class="fas fa-plus-circle"></i> Crear Nou Instrument
                <?php else: ?>
                    <i class="fas fa-edit"></i> Editar Instrument
                <?php endif; ?>
            </h2>
            
            <form action="instruments.php" method="POST" class="admin-form">
                <input type="hidden" name="id" value="<?php echo $instrumentEdit['id'] ?? ''; ?>">
                
                <div class="form-group">
                    <label for="nom">
                        <i class="fas fa-music"></i> Nom de l'Instrument *
                    </label>
                    <input type="text" id="nom" name="nom" placeholder="Ex: Guitarra, Piano, Violí..." required
                           value="<?php echo htmlspecialchars($instrumentEdit['nom'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="descripcio">
                        <i class="fas fa-align-left"></i> Descripció
                    </label>
                    <textarea id="descripcio" name="descripcio" rows="4" 
                              placeholder="Descripció de l'instrument i les classes que s'ofereixen..."><?php echo htmlspecialchars($instrumentEdit['descripcio'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> 
                        <?php echo $action === 'create' ? 'Crear Instrument' : 'Guardar Canvis'; ?>
                    </button>
                    <a href="instruments.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel·lar
                    </a>
                </div>
            </form>
        </div>
        <?php else: ?>
        <!-- Lista de Instruments -->
        <div class="table-responsive">
            <table class="data-table" id="instrumentsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Descripció</th>
                        <th>Classes Vinculades</th>
                        <th>Accions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($instruments)): ?>
                    <tr>
                        <td colspan="5" class="text-center">No hi ha instruments registrats</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($instruments as $instrument): ?>
                        <tr>
                            <td><?php echo $instrument['id']; ?></td>
                            <td><?php echo htmlspecialchars($instrument['nom']); ?></td>
                            <td><?php echo htmlspecialchars($instrument['descripcio'] ?? ''); ?></td>
                            <td><span class="badge badge-info"><?php echo $instrument['num_classes'] ?? 0; ?> classes</span></td>
                            <td class="actions">
                                <a href="instruments.php?action=edit&id=<?php echo $instrument['id']; ?>" class="btn-icon btn-edit" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="instruments.php?action=delete&id=<?php echo $instrument['id']; ?>" class="btn-icon btn-delete" title="Eliminar"
                                onclick="return confirm('Segur que vols eliminar aquest instrument?');">
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
