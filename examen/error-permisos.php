<?php
require_once 'header.php';
?>

<section class="error-section">
    <div class="error-container">
        <div class="error-content">
            <div class="error-icon">
                <i class="fas fa-lock"></i>
            </div>
            <h1>403</h1>
            <h2>No tens permisos per accedir a aquesta secció</h2>
            <p>Ho sentim, però no tens els permisos necessaris per veure aquesta pàgina.</p>
            
            <div class="error-actions">
                <a href="index.php" class="btn btn-primary">
                    <i class="fas fa-home"></i> Tornar a l'Inici
                </a>
                <?php if (!$isLogged): ?>
                <a href="login.php" class="btn btn-secondary">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sessió
                </a>
                <?php else: ?>
                <a href="<?php echo $isAdmin ? 'admin/dashboard.php' : 'alumne/dashboard.php'; ?>" class="btn btn-secondary">
                    <i class="fas fa-tachometer-alt"></i> Anar al Dashboard
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once 'footer.php'; ?>