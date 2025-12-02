<?php
/**
 * GameZone - Página Principal
 * Portal de noticias de videojuegos
 */
require_once __DIR__ . '/config.php';
initSession();

// Obtener noticias destacadas y recientes
$pdo = getDBConnection();
$featuredNews = [];
$latestNews = [];
$categories = [];

if ($pdo) {
    try {
        // Noticias destacadas (featured)
        $stmt = $pdo->query("
            SELECT n.*, c.name as category_name, c.slug as category_slug, c.color as category_color,
                   u.display_name as author_name, u.avatar as author_avatar
            FROM news n
            LEFT JOIN categories c ON n.category_id = c.id
            LEFT JOIN users u ON n.author_id = u.id
            WHERE n.status = 'published' AND n.is_featured = 1
            ORDER BY n.published_at DESC
            LIMIT 3
        ");
        $featuredNews = $stmt->fetchAll();
        
        // Últimas noticias
        $stmt = $pdo->query("
            SELECT n.*, c.name as category_name, c.slug as category_slug, c.color as category_color,
                   u.display_name as author_name, u.avatar as author_avatar
            FROM news n
            LEFT JOIN categories c ON n.category_id = c.id
            LEFT JOIN users u ON n.author_id = u.id
            WHERE n.status = 'published'
            ORDER BY n.published_at DESC
            LIMIT 6
        ");
        $latestNews = $stmt->fetchAll();
        
        // Categorías
        $stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
        $categories = $stmt->fetchAll();
        
    } catch (PDOException $e) {
        error_log("Error cargando datos: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= SITE_NAME ?> - Tu portal de noticias, análisis y guías de videojuegos. Las últimas novedades del mundo gaming.">
    <title><?= SITE_NAME ?> — <?= SITE_TAGLINE ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>

    <main>
      <!-- Hero Section -->
      <section class="hero">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-lg-6 hero-content animate-slide-up">
              <h1>
                Tu universo <span class="highlight">gaming</span> en un solo lugar
              </h1>
              <p class="lead">
                Descubre las últimas noticias, análisis en profundidad y guías completas 
                del mundo de los videojuegos. Únete a nuestra comunidad de gamers.
              </p>
              <div class="d-flex gap-3 flex-wrap">
                <a href="news.php" class="btn btn-primary btn-lg">
                  <i class="bi bi-newspaper me-2"></i>Ver Noticias
                </a>
                <a href="register.php" class="btn btn-outline btn-lg">
                  <i class="bi bi-person-plus me-2"></i>Únete Gratis
                </a>
              </div>
              
              <!-- Stats rápidas -->
              <div class="row mt-5 text-center">
                <div class="col-4">
                  <div class="h3 mb-0 text-primary">500+</div>
                  <small class="text-muted">Noticias</small>
                </div>
                <div class="col-4">
                  <div class="h3 mb-0 text-primary">100+</div>
                  <small class="text-muted">Análisis</small>
                </div>
                <div class="col-4">
                  <div class="h3 mb-0 text-primary">10K+</div>
                  <small class="text-muted">Usuarios</small>
                </div>
              </div>
            </div>
            <div class="col-lg-6 text-center d-none d-lg-block">
              <div class="position-relative">
                <div class="hero-glow" style="position: absolute; width: 400px; height: 400px; background: radial-gradient(circle, rgba(99,102,241,0.3) 0%, transparent 70%); top: 50%; left: 50%; transform: translate(-50%, -50%); border-radius: 50%;"></div>
                <i class="bi bi-controller" style="font-size: 15rem; color: var(--primary); opacity: 0.8;"></i>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Flash messages -->
      <?php $flash = getFlashMessage(); if ($flash): ?>
      <div class="container">
        <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
          <?= e($flash['message']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      </div>
      <?php endif; ?>

      <!-- Categorías -->
      <section class="container py-4">
        <div class="d-flex flex-wrap gap-2 justify-content-center">
          <?php foreach ($categories as $cat): ?>
          <a href="news.php?category=<?= e($cat['slug']) ?>" class="category-badge">
            <i class="bi bi-<?= e($cat['icon'] ?: 'tag') ?>"></i>
            <?= e($cat['name']) ?>
          </a>
          <?php endforeach; ?>
        </div>
      </section>

      <!-- Noticias Destacadas -->
      <?php if (!empty($featuredNews)): ?>
      <section class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2 class="mb-0"><i class="bi bi-star-fill text-warning me-2"></i>Destacados</h2>
          <a href="news.php?featured=1" class="btn btn-outline btn-sm">Ver todos</a>
        </div>
        
        <div class="row g-4">
          <?php foreach ($featuredNews as $news): ?>
          <div class="col-md-4">
            <article class="news-card featured h-100 animate-slide-up">
              <div class="card-image">
                <img src="assets/img/news/<?= e($news['image'] ?: 'placeholder.jpg') ?>" 
                     alt="<?= e($news['title']) ?>"
                     onerror="this.src='assets/img/placeholder-news.svg'">
                <span class="card-category" style="background: <?= e($news['category_color'] ?: 'var(--primary)') ?>">
                  <?= e($news['category_name'] ?: 'General') ?>
                </span>
              </div>
              <div class="card-body">
                <h5 class="card-title">
                  <a href="news-detail.php?slug=<?= e($news['slug']) ?>"><?= e($news['title']) ?></a>
                </h5>
                <p class="card-text"><?= e(truncateText($news['summary'] ?: strip_tags($news['content']), 120)) ?></p>
                <div class="card-meta">
                  <div class="author">
                    <img src="assets/img/avatars/<?= e($news['author_avatar'] ?: 'default-avatar.png') ?>" 
                         alt="<?= e($news['author_name']) ?>"
                         onerror="this.src='assets/img/avatars/default-avatar.png'">
                    <span><?= e($news['author_name']) ?></span>
                  </div>
                  <span><i class="bi bi-calendar3 me-1"></i><?= formatDate($news['published_at']) ?></span>
                </div>
              </div>
            </article>
          </div>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endif; ?>

      <!-- Últimas Noticias -->
      <section class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2 class="mb-0"><i class="bi bi-lightning-fill text-primary me-2"></i>Últimas Noticias</h2>
          <a href="news.php" class="btn btn-outline btn-sm">Ver todas</a>
        </div>
        
        <div class="row g-4">
          <?php if (!empty($latestNews)): ?>
            <?php foreach ($latestNews as $news): ?>
            <div class="col-md-6 col-lg-4">
              <article class="news-card h-100 animate-slide-up">
                <div class="card-image">
                  <img src="assets/img/news/<?= e($news['image'] ?: 'placeholder.jpg') ?>" 
                       alt="<?= e($news['title']) ?>"
                       onerror="this.src='assets/img/placeholder-news.svg'">
                  <span class="card-category" style="background: <?= e($news['category_color'] ?: 'var(--primary)') ?>">
                    <?= e($news['category_name'] ?: 'General') ?>
                  </span>
                </div>
                <div class="card-body">
                  <h5 class="card-title">
                    <a href="news-detail.php?slug=<?= e($news['slug']) ?>"><?= e($news['title']) ?></a>
                  </h5>
                  <p class="card-text"><?= e(truncateText($news['summary'] ?: strip_tags($news['content']), 100)) ?></p>
                  <div class="card-meta">
                    <div class="author">
                      <img src="assets/img/avatars/<?= e($news['author_avatar'] ?: 'default-avatar.png') ?>" 
                           alt="<?= e($news['author_name']) ?>"
                           onerror="this.src='assets/img/avatars/default-avatar.png'">
                      <span><?= e($news['author_name']) ?></span>
                    </div>
                    <span><i class="bi bi-eye me-1"></i><?= number_format($news['views']) ?></span>
                  </div>
                </div>
              </article>
            </div>
            <?php endforeach; ?>
          <?php else: ?>
            <!-- Noticias de ejemplo cuando no hay DB -->
            <div class="col-md-6 col-lg-4">
              <article class="news-card h-100 animate-slide-up">
                <div class="card-image">
                  <img src="assets/img/placeholder-news.svg" alt="Nintendo Switch 2">
                  <span class="card-category">Noticias</span>
                </div>
                <div class="card-body">
                  <h5 class="card-title">
                    <a href="#">Nintendo anuncia el sucesor de Switch para 2025</a>
                  </h5>
                  <p class="card-text">Nintendo ha confirmado oficialmente que el sucesor de Nintendo Switch llegará durante el año fiscal 2025.</p>
                  <div class="card-meta">
                    <div class="author">
                      <img src="assets/img/avatars/default-avatar.png" alt="Editor">
                      <span>Editor</span>
                    </div>
                    <span><i class="bi bi-calendar3 me-1"></i>2 Dic, 2025</span>
                  </div>
                </div>
              </article>
            </div>
            <div class="col-md-6 col-lg-4">
              <article class="news-card h-100 animate-slide-up">
                <div class="card-image">
                  <img src="assets/img/placeholder-news.svg" alt="GTA 6">
                  <span class="card-category">Noticias</span>
                </div>
                <div class="card-body">
                  <h5 class="card-title">
                    <a href="#">GTA 6 confirma fecha de lanzamiento para otoño 2025</a>
                  </h5>
                  <p class="card-text">Rockstar Games ha confirmado que Grand Theft Auto VI llegará en otoño de 2025 exclusivamente para consolas.</p>
                  <div class="card-meta">
                    <div class="author">
                      <img src="assets/img/avatars/default-avatar.png" alt="Editor">
                      <span>Editor</span>
                    </div>
                    <span><i class="bi bi-calendar3 me-1"></i>1 Dic, 2025</span>
                  </div>
                </div>
              </article>
            </div>
            <div class="col-md-6 col-lg-4">
              <article class="news-card h-100 animate-slide-up">
                <div class="card-image">
                  <img src="assets/img/placeholder-news.svg" alt="FF7 Rebirth">
                  <span class="card-category">Análisis</span>
                </div>
                <div class="card-body">
                  <h5 class="card-title">
                    <a href="#">Análisis: Final Fantasy VII Rebirth - Una obra maestra</a>
                  </h5>
                  <p class="card-text">Square Enix entrega la que posiblemente sea la mejor entrega de la saga en décadas.</p>
                  <div class="card-meta">
                    <div class="author">
                      <img src="assets/img/avatars/default-avatar.png" alt="Editor">
                      <span>Editor</span>
                    </div>
                    <span><i class="bi bi-calendar3 me-1"></i>30 Nov, 2025</span>
                  </div>
                </div>
              </article>
            </div>
          <?php endif; ?>
        </div>
      </section>

      <!-- CTA Newsletter -->
      <section class="py-5" style="background: linear-gradient(135deg, rgba(99,102,241,0.1) 0%, rgba(139,92,246,0.05) 100%);">
        <div class="container text-center">
          <h3 class="mb-3">¿No te pierdas ninguna noticia?</h3>
          <p class="text-secondary mb-4">Suscríbete a nuestro newsletter y recibe las últimas novedades gaming directamente en tu email.</p>
          <form action="newsletter.php" method="POST" class="d-flex justify-content-center gap-2 flex-wrap" style="max-width: 500px; margin: 0 auto;">
            <input type="email" name="email" class="form-control" placeholder="tu@email.com" required style="max-width: 300px;">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-envelope me-2"></i>Suscribirse
            </button>
          </form>
        </div>
      </section>

      <!-- FAQs Resumen -->
      <section class="container py-5">
        <div class="row align-items-center">
          <div class="col-lg-5 mb-4 mb-lg-0">
            <h3>¿Tienes preguntas?</h3>
            <p class="text-secondary">Consulta nuestras preguntas frecuentes o contáctanos directamente. Estamos aquí para ayudarte.</p>
            <div class="d-flex gap-2">
              <a href="faqs.php" class="btn btn-outline">Ver FAQs</a>
              <a href="contact.php" class="btn btn-primary">Contactar</a>
            </div>
          </div>
          <div class="col-lg-7">
            <div class="accordion" id="homeFaqs">
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                    ¿Cómo puedo crear una cuenta?
                  </button>
                </h2>
                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#homeFaqs">
                  <div class="accordion-body">
                    Puedes crear una cuenta gratuita haciendo clic en "Registrarse" en la parte superior de la página y completando el formulario con tus datos.
                  </div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                    ¿Puedo comentar sin registrarme?
                  </button>
                </h2>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#homeFaqs">
                  <div class="accordion-body">
                    Para comentar en nuestras noticias y análisis necesitas crear una cuenta gratuita. ¡Es rápido y fácil!
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>

    <?php include __DIR__ . '/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

