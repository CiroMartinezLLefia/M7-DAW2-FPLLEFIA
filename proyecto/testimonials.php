<?php
/**
 * GameZone - Opiniones de la Comunidad
 * Reseñas y opiniones de usuarios
 */
require_once __DIR__ . '/config.php';
initSession();

$pdo = getDBConnection();
$comments = [];

if ($pdo) {
    try {
        // Obtener comentarios destacados de noticias con información del usuario
        $stmt = $pdo->query("
            SELECT c.*, u.username, u.role, n.title as news_title, n.slug as news_slug
            FROM comments c
            JOIN users u ON c.user_id = u.id
            JOIN news n ON c.news_id = n.id
            WHERE c.status = 'approved'
            ORDER BY c.created_at DESC
            LIMIT 12
        ");
        $comments = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error cargando comentarios: " . $e->getMessage());
    }
}

// Testimonios de ejemplo si no hay datos
if (empty($comments)) {
    $comments = [
        [
            'username' => 'ProGamer88',
            'role' => 'editor',
            'content' => 'GameZone se ha convertido en mi fuente principal de noticias gaming. ¡Siempre están actualizados con los últimos lanzamientos!',
            'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            'news_title' => 'Review: Elden Ring Shadow of the Erdtree',
            'news_slug' => 'review-elden-ring-dlc'
        ],
        [
            'username' => 'NintendoFan',
            'role' => 'user',
            'content' => 'Las reviews son muy objetivas y detalladas. Me ayudan mucho a decidir qué juegos comprar.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
            'news_title' => 'Nuevo Nintendo Direct anunciado',
            'news_slug' => 'nintendo-direct-anuncio'
        ],
        [
            'username' => 'PCMasterRace',
            'role' => 'user',
            'content' => 'La mejor cobertura de PC gaming en español. Muy completos con los análisis técnicos.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 week')),
            'news_title' => 'Guía de optimización para Starfield',
            'news_slug' => 'guia-starfield'
        ],
        [
            'username' => 'RetroGamer',
            'role' => 'user',
            'content' => 'Me encanta que cubran también juegos retro y remakes. Contenido para todos los gustos.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-2 weeks')),
            'news_title' => 'Los mejores remakes de la década',
            'news_slug' => 'mejores-remakes'
        ],
        [
            'username' => 'StreamerPro',
            'role' => 'editor',
            'content' => 'Colaborar con GameZone ha sido genial. El equipo es muy profesional.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-3 weeks')),
            'news_title' => 'Los mejores juegos para streaming',
            'news_slug' => 'juegos-streaming'
        ],
        [
            'username' => 'CasualPlayer',
            'role' => 'user',
            'content' => 'La sección de FAQs me ayudó muchísimo como jugador nuevo. ¡Muy recomendado!',
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 month')),
            'news_title' => 'Guía para principiantes en RPGs',
            'news_slug' => 'guia-principiantes-rpg'
        ]
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comunidad — <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
      .testimonial-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
      }
      .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(103, 58, 183, 0.2);
      }
      .testimonial-quote {
        font-size: 1.1rem;
        font-style: italic;
        color: var(--text-primary);
        position: relative;
        padding-left: 1.5rem;
        margin-bottom: 1rem;
      }
      .testimonial-quote::before {
        content: '"';
        font-size: 3rem;
        position: absolute;
        left: -0.5rem;
        top: -1rem;
        color: var(--primary);
        opacity: 0.5;
        font-family: serif;
      }
      .user-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
      }
      .user-badge.editor {
        background: rgba(0, 188, 212, 0.2);
        color: var(--neon-cyan);
      }
      .user-badge.admin {
        background: rgba(255, 152, 0, 0.2);
        color: var(--accent-orange);
      }
      .user-badge.user {
        background: rgba(103, 58, 183, 0.2);
        color: var(--primary);
      }
    </style>
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>
    
    <main class="container py-5">
      <div class="text-center mb-5">
        <h1><i class="bi bi-people-fill text-primary me-2"></i>Nuestra Comunidad</h1>
        <p class="text-secondary">Opiniones y comentarios de nuestros lectores</p>
      </div>
      
      <!-- Stats de comunidad -->
      <div class="row g-4 mb-5">
        <div class="col-md-4">
          <div class="text-center p-4 rounded" style="background: var(--card-bg); border: 1px solid var(--border-color);">
            <i class="bi bi-people text-primary" style="font-size: 2rem;"></i>
            <h3 class="mt-2 mb-0">5,000+</h3>
            <small class="text-secondary">Miembros registrados</small>
          </div>
        </div>
        <div class="col-md-4">
          <div class="text-center p-4 rounded" style="background: var(--card-bg); border: 1px solid var(--border-color);">
            <i class="bi bi-chat-dots text-info" style="font-size: 2rem;"></i>
            <h3 class="mt-2 mb-0">15,000+</h3>
            <small class="text-secondary">Comentarios publicados</small>
          </div>
        </div>
        <div class="col-md-4">
          <div class="text-center p-4 rounded" style="background: var(--card-bg); border: 1px solid var(--border-color);">
            <i class="bi bi-newspaper text-success" style="font-size: 2rem;"></i>
            <h3 class="mt-2 mb-0">500+</h3>
            <small class="text-secondary">Noticias publicadas</small>
          </div>
        </div>
      </div>
      
      <!-- Testimonios -->
      <h2 class="h4 mb-4">
        <i class="bi bi-chat-quote me-2"></i>Comentarios destacados
      </h2>
      
      <div class="row g-4">
        <?php foreach ($comments as $comment): ?>
        <div class="col-md-6 col-lg-4">
          <div class="testimonial-card animate-slide-up">
            <p class="testimonial-quote">
              <?= e(truncateText($comment['content'], 200)) ?>
            </p>
            
            <div class="d-flex align-items-center justify-content-between mt-auto">
              <div>
                <strong class="d-block"><?= e($comment['username']) ?></strong>
                <span class="user-badge <?= e($comment['role']) ?>">
                  <i class="bi bi-<?= $comment['role'] === 'admin' ? 'shield-check' : ($comment['role'] === 'editor' ? 'pen' : 'person') ?>"></i>
                  <?= ucfirst(e($comment['role'])) ?>
                </span>
              </div>
              <small class="text-muted">
                <i class="bi bi-clock me-1"></i><?= timeAgo($comment['created_at']) ?>
              </small>
            </div>
            
            <?php if (!empty($comment['news_title'])): ?>
            <div class="mt-3 pt-3" style="border-top: 1px solid var(--border-color);">
              <small class="text-muted">
                <i class="bi bi-newspaper me-1"></i>En: 
                <a href="news-detail.php?slug=<?= e($comment['news_slug'] ?? '') ?>" class="text-primary">
                  <?= e(truncateText($comment['news_title'], 40)) ?>
                </a>
              </small>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      
      <!-- CTA para unirse -->
      <div class="text-center mt-5 p-5 rounded" style="background: linear-gradient(135deg, rgba(103, 58, 183, 0.2), rgba(0, 188, 212, 0.2)); border: 1px solid var(--border-color);">
        <h3>¿Quieres unirte a nuestra comunidad?</h3>
        <p class="text-secondary mb-4">Regístrate gratis y participa en las conversaciones</p>
        <?php if (!isLoggedIn()): ?>
        <a href="register.php" class="btn btn-primary btn-lg">
          <i class="bi bi-person-plus me-2"></i>Crear cuenta
        </a>
        <?php else: ?>
        <a href="news.php" class="btn btn-primary btn-lg">
          <i class="bi bi-newspaper me-2"></i>Ver noticias
        </a>
        <?php endif; ?>
      </div>
    </main>
    
    <?php include __DIR__ . '/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

