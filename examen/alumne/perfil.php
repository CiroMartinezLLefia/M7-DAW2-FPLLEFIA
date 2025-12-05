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
$message = '';
$error = '';

// Cargar datos del alumno
$alumne = null;
$stmt = $mysqli->prepare("SELECT * FROM usuaris WHERE id = ? AND rol = 'alumne'");
$stmt->bind_param("i", $alumneId);
$stmt->execute();
$result = $stmt->get_result();
$alumne = $result->fetch_assoc();
$stmt->close();

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $cognoms = trim($_POST['cognoms'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $foto = trim($_POST['foto'] ?? '');
    
    if (empty($nom) || empty($cognoms) || empty($email)) {
        $error = "Nom, Cognoms i Email són obligatoris.";
    } else {
        $stmt = $mysqli->prepare("UPDATE usuaris SET nom = ?, cognoms = ?, email = ?, foto = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $nom, $cognoms, $email, $foto, $alumneId);
        if ($stmt->execute()) {
            $message = "Perfil actualitzat correctament.";
            // Actualizar datos en memoria
            $alumne['nom'] = $nom;
            $alumne['cognoms'] = $cognoms;
            $alumne['email'] = $email;
            $alumne['foto'] = $foto;
            $_SESSION['user_name'] = $nom;
        } else {
            $error = "Error al actualitzar el perfil.";
        }
        $stmt->close();
    }
}

require_once '../header.php';
?>

<section class="alumne-section">
    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-user-edit"></i> El Meu Perfil</h1>
            <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Tornar</a>
        </div>
        
        <?php if (!empty($message)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <div class="form-card">
            <form action="perfil.php" method="POST" class="admin-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nom"><i class="fas fa-user"></i> Nom *</label>
                        <input type="text" id="nom" name="nom" required value="<?php echo htmlspecialchars($alumne['nom'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="cognoms"><i class="fas fa-user"></i> Cognoms *</label>
                        <input type="text" id="cognoms" name="cognoms" required value="<?php echo htmlspecialchars($alumne['cognoms'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Correu electrònic *</label>
                    <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($alumne['email'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="foto"><i class="fas fa-image"></i> URL Foto de Perfil</label>
                    <input type="url" id="foto" name="foto" placeholder="https://exemple.com/imatge.jpg" value="<?php echo htmlspecialchars($alumne['foto'] ?? ''); ?>">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Canvis</button>
                    <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel·lar</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php require_once '../footer.php'; ?>
