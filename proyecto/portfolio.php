<?php
/**
 * GameZone - Página de Juegos
 * Catálogo de videojuegos referenciados
 */
require_once __DIR__ . '/config.php';
initSession();

$pdo = getDBConnection();
$games = [];

if ($pdo) {
    try {
        $stmt = $pdo->query("
            SELECT g.*, 
                   (SELECT AVG(r.score) FROM reviews r WHERE r.game_id = g.id AND r.status = 'published') as avg_score
            FROM games g
            ORDER BY g.release_date DESC
            LIMIT 20
        ");
        $games = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error cargando juegos: " . $e->getMessage());
    }
}

// Si no hay juegos, mostrar ejemplos
if (empty($games)) {
    $games = [
        [
            'title' => 'The Legend of Zelda: Tears of the Kingdom',
            'developer' => 'Nintendo EPD',
            'publisher' => 'Nintendo',
            'release_date' => '2023-05-12',
            'description' => 'Secuela del aclamado Breath of the Wild',
            'avg_score' => 9.8
        ],
        [
            'title' => 'Elden Ring',
            'developer' => 'FromSoftware',
            'publisher' => 'Bandai Namco',
            'release_date' => '2022-02-25',
            'description' => 'RPG de acción en mundo abierto',
            'avg_score' => 10.0
        ],
        [
            'title' => 'God of War Ragnarök',
            'developer' => 'Santa Monica Studio',
            'publisher' => 'Sony Interactive Entertainment',
            'release_date' => '2022-11-09',
            'description' => 'Aventura épica nórdica',
            'avg_score' => 9.5
        ],
        [
            'title' => 'Baldurs Gate 3',
            'developer' => 'Larian Studios',
            'publisher' => 'Larian Studios',
            'release_date' => '2023-08-03',
            'description' => 'RPG basado en D&D',
            'avg_score' => 10.0
        ],
        [
            'title' => 'Starfield',
            'developer' => 'Bethesda Game Studios',
            'publisher' => 'Bethesda Softworks',
            'release_date' => '2023-09-06',
            'description' => 'RPG espacial de nueva generación',
            'avg_score' => 7.5
        ],
        [
            'title' => 'Final Fantasy VII Rebirth',
            'developer' => 'Square Enix',
            'publisher' => 'Square Enix',
            'release_date' => '2024-02-29',
            'description' => 'Segunda parte de la trilogía remake',
            'avg_score' => 9.5
        ]
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Juegos — <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
      .game-card .game-score {
        position: absolute;
        bottom: 1rem;
        right: 1rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.85rem;
        background: rgba(0,0,0,0.7);
        backdrop-filter: blur(4px);
      }
      .game-card .card-image {
        aspect-ratio: 3/4;
      }
    </style>
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>
    
    <main class="container py-5">
      <div class="text-center mb-5">
        <h1><i class="bi bi-controller text-primary me-2"></i>Catálogo de Juegos</h1>
        <p class="text-secondary">Descubre información sobre los últimos videojuegos</p>
      </div>
      
      <!-- Grid de juegos -->
      <div class="row g-4">
        <?php foreach ($games as $game): ?>
        <div class="col-6 col-md-4 col-lg-3">
          <article class="news-card game-card h-100 animate-slide-up">
            <div class="card-image">
              <img src="assets/img/games/<?= e($game['cover_image'] ?? 'placeholder.jpg') ?>" 
                   alt="<?= e($game['title']) ?>"
                   onerror="this.src='assets/img/placeholder-news.svg'">
              <?php if ($game['avg_score']): ?>
              <span class="game-score" style="color: <?php 
                $score = $game['avg_score'];
                echo $score >= 9 ? 'var(--accent-green)' : ($score >= 7 ? 'var(--primary)' : 'var(--accent-orange)');
              ?>">
                <i class="bi bi-star-fill me-1"></i><?= number_format($game['avg_score'], 1) ?>
              </span>
              <?php endif; ?>
            </div>
            <div class="card-body">
              <h6 class="card-title mb-1">
                <a href="game.php?slug=<?= generateSlug($game['title']) ?>"><?= e($game['title']) ?></a>
              </h6>
              <small class="text-primary"><?= e($game['developer']) ?></small>
              <p class="card-text small mt-2"><?= e(truncateText($game['description'] ?? '', 60)) ?></p>
              <div class="text-muted small">
                <i class="bi bi-calendar3 me-1"></i><?= formatDate($game['release_date'], 'Y') ?>
              </div>
            </div>
          </article>
        </div>
        <?php endforeach; ?>
      </div>
    </main>
    
    <?php include __DIR__ . '/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

