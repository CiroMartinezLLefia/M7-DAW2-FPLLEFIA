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
$alumneEdit = null;

// Procesar formulario POST (crear/editar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $nom = trim($_POST['nom'] ?? '');
    $cognoms = trim($_POST['cognoms'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (!empty($id)) {
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $mysqli->prepare("UPDATE usuaris SET nom = ?, cognoms = ?, email = ?, password = ? WHERE id = ? AND rol = 'alumne'");
            $stmt->bind_param("ssssi", $nom, $cognoms, $email, $hashedPassword, $id);
        } else {
            $stmt = $mysqli->prepare("UPDATE usuaris SET nom = ?, cognoms = ?, email = ? WHERE id = ? AND rol = 'alumne'");
            $stmt->bind_param("sssi", $nom, $cognoms, $email, $id);
        }
        $stmt->execute();
        $stmt->close();
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("INSERT INTO usuaris (nom, cognoms, email, password, rol) VALUES (?, ?, ?, ?, 'alumne')");
        $stmt->bind_param("ssss", $nom, $cognoms, $email, $hashedPassword);
        $stmt->execute();
        $stmt->close();
    }
    header('Location: alumnes.php');
    exit;
}

// Procesar acción de eliminar
if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $mysqli->prepare("DELETE FROM usuaris WHERE id = ? AND rol = 'alumne'");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header('Location: alumnes.php');
    exit;
}

// Cargar datos del alumno para editar
if ($action === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $mysqli->prepare("SELECT * FROM usuaris WHERE id = ? AND rol = 'alumne'");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $alumneEdit = $result->fetch_assoc();
    $stmt->close();
    
    if (!$alumneEdit) {
        header('Location: alumnes.php');
        exit;
    }
}

// Obtener lista de alumnes
$sql = "SELECT * FROM usuaris WHERE rol = 'alumne' ORDER BY nom, cognoms";
$result = $mysqli->query($sql);
$alumnes = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $alumnes[] = $row;
    }
}

require_once '../header.php';
?>

<section class="admin-section">
    <div class="container">
        <div class="admin-header">
            <h1><i class="fas fa-user-graduate"></i> Gestió d'Alumnes</h1>
            <div class="header-actions">
                <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Tornar</a>
                <?php if ($action === 'list'): ?>
                <a href="alumnes.php?action=create" class="btn btn-primary"><i class="fas fa-plus"></i> Nou Alumne</a>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ($action === 'create' || $action === 'edit'): ?>
        <!-- Formulario Crear/Editar -->
        <div class="form-card">
            <h2>
                <?php if ($action === 'create'): ?>
                    <i class="fas fa-user-plus"></i> Crear Nou Alumne
                <?php else: ?>
                    <i class="fas fa-edit"></i> Editar Alumne
                <?php endif; ?>
            </h2>
            
            <form action="alumnes.php" method="POST" class="admin-form">
                <input type="hidden" name="id" value="<?php echo $alumneEdit['id'] ?? ''; ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nom">
                            <i class="fas fa-user"></i> Nom *
                        </label>
                        <input type="text" id="nom" name="nom" placeholder="Nom de l'alumne" required value="<?php echo htmlspecialchars($alumneEdit['nom'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="cognoms">
                            <i class="fas fa-user"></i> Cognoms *
                        </label>
                        <input type="text" id="cognoms" name="cognoms" placeholder="Cognoms de l'alumne" required value="<?php echo htmlspecialchars($alumneEdit['cognoms'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Correu electrònic *
                    </label>
                    <input type="email" id="email" name="email" placeholder="email@exemple.com" required value="<?php echo htmlspecialchars($alumneEdit['email'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Contrasenya <?php echo $action === 'create' ? '*' : '(deixar buit per mantenir)'; ?>
                    </label>
                    <input type="password" id="password" name="password" <?php echo $action === 'create' ? 'required' : ''; ?>>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> 
                        <?php echo $action === 'create' ? 'Crear Alumne' : 'Guardar Canvis'; ?>
                    </button>
                    <a href="alumnes.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel·lar
                    </a>
                </div>
            </form>
        </div>
        
        <?php else: ?>        
        <div class="table-responsive">
            <table class="data-table" id="alumnesTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom Complet</th>
                        <th>Email</th>
                        <th>Accions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alumnes as $alumne): ?>
                    <tr>
                        <td><?php echo $alumne['id']; ?></td>
                        <td><?php echo htmlspecialchars($alumne['nom'] . ' ' . $alumne['cognoms']); ?></td>
                        <td><?php echo htmlspecialchars($alumne['email']); ?></td>
                        <td class="actions">
                            <a href="alumnes.php?action=edit&id=<?php echo $alumne['id']; ?>" class="btn-icon btn-edit"><i class="fas fa-edit"></i></a>
                            <a href="alumnes.php?action=delete&id=<?php echo $alumne['id']; ?>" class="btn-icon btn-delete" onclick="return confirm('Eliminar?');"><i class="fas fa-trash"></i></a>
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
