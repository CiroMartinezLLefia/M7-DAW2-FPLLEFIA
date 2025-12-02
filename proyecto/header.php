<?php
/**
 * GameZone - Header con navegación y autenticación
 */
require_once __DIR__ . '/config.php';
initSession();
$currentUser = getCurrentUser();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<header class="site-header">
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <!-- Logo y marca -->
      <a class="navbar-brand" href="index.php">
        <div class="logo">GZ</div>
        <div class="brand-text">
          <span class="brand-name"><?= SITE_NAME ?></span>
          <small class="brand-tagline"><?= SITE_TAGLINE ?></small>
        </div>
      </a>
      
      <!-- Botón hamburguesa móvil -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <!-- Navegación -->
      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav mx-auto">
          <li class="nav-item">
            <a class="nav-link <?= $currentPage === 'index' ? 'active' : '' ?>" href="index.php">
              <i class="bi bi-house-door me-1"></i>Inicio
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $currentPage === 'news' ? 'active' : '' ?>" href="news.php">
              <i class="bi bi-newspaper me-1"></i>Noticias
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $currentPage === 'reviews' ? 'active' : '' ?>" href="reviews.php">
              <i class="bi bi-star me-1"></i>Análisis
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $currentPage === 'faqs' ? 'active' : '' ?>" href="faqs.php">
              <i class="bi bi-question-circle me-1"></i>FAQs
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= $currentPage === 'contact' ? 'active' : '' ?>" href="contact.php">
              <i class="bi bi-envelope me-1"></i>Contacto
            </a>
          </li>
        </ul>
        
        <!-- Auth / Usuario -->
        <div class="d-flex align-items-center gap-2">
          <?php if ($currentUser): ?>
            <!-- Usuario logueado -->
            <div class="dropdown">
              <button class="btn btn-link text-decoration-none dropdown-toggle user-menu p-0" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="assets/img/avatars/<?= e($currentUser['avatar']) ?>" alt="Avatar" class="user-avatar" onerror="this.src='assets/img/avatars/default-avatar.svg'">
                <span class="text-light d-none d-md-inline"><?= e($currentUser['display_name']) ?></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li class="px-3 py-2 border-bottom border-subtle">
                  <small class="text-muted">Sesión iniciada como</small>
                  <div class="fw-bold"><?= e($currentUser['username']) ?></div>
                  <span class="badge badge-<?= $currentUser['role'] ?>"><?= ucfirst($currentUser['role']) ?></span>
                </li>
                <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i>Mi Perfil</a></li>
                <li><a class="dropdown-item" href="favorites.php"><i class="bi bi-heart me-2"></i>Favoritos</a></li>
                <?php if (canEdit()): ?>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="admin/index.php"><i class="bi bi-speedometer2 me-2"></i>Panel Admin</a></li>
                <?php endif; ?>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a></li>
              </ul>
            </div>
          <?php else: ?>
            <!-- Usuario no logueado -->
            <a href="login.php" class="btn btn-auth btn-login">Iniciar Sesión</a>
            <a href="register.php" class="btn btn-auth btn-register">Registrarse</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>
</header>

