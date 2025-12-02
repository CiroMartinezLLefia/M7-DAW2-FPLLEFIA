<?php
/**
 * GameZone - Análisis de Videojuegos
 */
require_once __DIR__ . '/config.php';
initSession();

$pdo = getDBConnection();
$reviews = [];

if ($pdo) {
    try {
        $stmt = $pdo->query("
            SELECT r.*, g.title as game_title, g.cover_image, g.developer, g.publisher,
                   u.display_name as author_name, u.avatar as author_avatar
            FROM reviews r
            LEFT JOIN games g ON r.game_id = g.id
            LEFT JOIN users u ON r.author_id = u.id
            WHERE r.status = 'published'
            ORDER BY r.published_at DESC
            LIMIT 12
        ");
        $reviews = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error cargando análisis: " . $e->getMessage());
    }
}

// Si no hay reviews, mostrar ejemplos
if (empty($reviews)) {
    $reviews = [
        [
            'game_title' => 'Final Fantasy VII Rebirth',
            'title' => 'Una obra maestra moderna',
            'score' => 9.5,
            'verdict' => 'Square Enix entrega la que posiblemente sea la mejor entrega de la saga en décadas.',
            'author_name' => 'Editor',
            'published_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
        ],
        [
            'game_title' => 'Elden Ring',
            'title' => 'El RPG definitivo de FromSoftware',
            'score' => 10.0,
            'verdict' => 'Una obra maestra que redefine el género de los juegos de mundo abierto.',
            'author_name' => 'Editor',
            'published_at' => date('Y-m-d H:i:s', strtotime('-1 week'))
        ],
        [
            'game_title' => 'Baldurs Gate 3',
            'title' => 'El mejor RPG de la década',
            'score' => 10.0,
            'verdict' => 'Larian Studios ha creado una experiencia que todo fan de los RPG debe vivir.',
            'author_name' => 'Editor',
            'published_at' => date('Y-m-d H:i:s', strtotime('-2 weeks'))
        ]
    ];
}

// Función para colorear puntuación
function getScoreColor($score) {
    if ($score >= 9) return 'var(--accent-green)';
    if ($score >= 7) return 'var(--primary)';
    if ($score >= 5) return 'var(--accent-orange)';
    return 'var(--accent-red)';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Análisis de Videojuegos — <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
      .review-card .score-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.1rem;
        color: white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
      }
    </style>
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>
    
    <main class="container py-5">
      <div class="text-center mb-5">
        <h1><i class="bi bi-star-fill text-warning me-2"></i>Análisis</h1>
        <p class="text-secondary">Reviews en profundidad de los últimos videojuegos</p>
      </div>
      
      <!-- Escala de puntuación -->
      <div class="card p-3 mb-5">
        <div class="row text-center">
          <div class="col">
            <span class="badge" style="background: var(--accent-green);">9-10</span>
            <small class="d-block text-muted mt-1">Imprescindible</small>
          </div>
          <div class="col">
            <span class="badge" style="background: var(--primary);">7-8</span>
            <small class="d-block text-muted mt-1">Recomendado</small>
          </div>
          <div class="col">
            <span class="badge" style="background: var(--accent-orange);">5-6</span>
            <small class="d-block text-muted mt-1">Regular</small>
          </div>
          <div class="col">
            <span class="badge" style="background: var(--accent-red);">1-4</span>
            <small class="d-block text-muted mt-1">No recomendado</small>
          </div>
        </div>
      </div>
      
      <!-- Grid de análisis -->
      <div class="row g-4">
        <?php foreach ($reviews as $review): ?>
        <div class="col-md-6 col-lg-4">
          <article class="news-card review-card h-100 animate-slide-up">
            <div class="card-image">
              <img src="assets/img/games/<?= e($review['cover_image'] ?? 'placeholder.jpg') ?>" 
                   alt="<?= e($review['game_title']) ?>"
                   onerror="this.src='assets/img/placeholder-news.svg'">
              <span class="score-badge" style="background: <?= getScoreColor($review['score']) ?>">
                <?= number_format($review['score'], 1) ?>
              </span>
            </div>
            <div class="card-body">
              <span class="text-primary fw-bold small"><?= e($review['game_title']) ?></span>
              <h5 class="card-title mt-1">
                <a href="review-detail.php?id=<?= $review['id'] ?? '#' ?>"><?= e($review['title']) ?></a>
              </h5>
              <p class="card-text"><?= e(truncateText($review['verdict'] ?? '', 100)) ?></p>
              <div class="card-meta">
                <span><?= e($review['author_name']) ?></span>
                <span><?= formatDate($review['published_at']) ?></span>
              </div>
            </div>
          </article>
        </div>
        <?php endforeach; ?>
      </div>
      
      <!-- CTA -->
      <div class="text-center mt-5">
        <p class="text-secondary">¿Buscas más análisis?</p>
        <a href="news.php?category=analisis" class="btn btn-outline">Ver todos los análisis</a>
      </div>
    </main>
    
    <?php include __DIR__ . '/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
