<?php
/**
 * GameZone - Listado de Noticias
 */
require_once __DIR__ . '/config.php';
initSession();

// Parámetros de filtrado y paginación
$page = max(1, intval($_GET['page'] ?? 1));
$category = $_GET['category'] ?? '';
$platform = $_GET['platform'] ?? '';
$search = trim($_GET['search'] ?? '');
$perPage = NEWS_PER_PAGE;
$offset = ($page - 1) * $perPage;

$pdo = getDBConnection();
$news = [];
$categories = [];
$platforms = [];
$totalNews = 0;

if ($pdo) {
    try {
        // Obtener categorías para el filtro
        $categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
        
        // Obtener plataformas para el filtro
        $platforms = $pdo->query("SELECT * FROM platforms ORDER BY name")->fetchAll();
        
        // Construir query de noticias
        $whereConditions = ["n.status = 'published'"];
        $params = [];
        
        if ($category) {
            $whereConditions[] = "c.slug = ?";
            $params[] = $category;
        }
        
        if ($search) {
            $whereConditions[] = "(n.title LIKE ? OR n.summary LIKE ? OR n.content LIKE ?)";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $whereClause = implode(' AND ', $whereConditions);
        
        // Contar total
        $countSql = "
            SELECT COUNT(*) 
            FROM news n
            LEFT JOIN categories c ON n.category_id = c.id
            WHERE $whereClause
        ";
        $countStmt = $pdo->prepare($countSql);
        $countStmt->execute($params);
        $totalNews = $countStmt->fetchColumn();
        
        // Obtener noticias paginadas
        $sql = "
            SELECT n.*, c.name as category_name, c.slug as category_slug, c.color as category_color,
                   u.display_name as author_name, u.avatar as author_avatar
            FROM news n
            LEFT JOIN categories c ON n.category_id = c.id
            LEFT JOIN users u ON n.author_id = u.id
            WHERE $whereClause
            ORDER BY n.published_at DESC
            LIMIT $perPage OFFSET $offset
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $news = $stmt->fetchAll();
        
    } catch (PDOException $e) {
        error_log("Error cargando noticias: " . $e->getMessage());
    }
}

$totalPages = ceil($totalNews / $perPage);
$categoryName = $category ? ucfirst(str_replace('-', ' ', $category)) : 'Todas las noticias';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias<?= $category ? ' - ' . e($categoryName) : '' ?> — <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>
    
    <main class="container py-5">
      <!-- Header de sección -->
      <div class="row mb-4">
        <div class="col-lg-8">
          <h1 class="mb-2">
            <i class="bi bi-newspaper text-primary me-2"></i>
            <?= $search ? 'Resultados de búsqueda' : ($category ? e($categoryName) : 'Noticias') ?>
          </h1>
          <p class="text-secondary">
            <?php if ($search): ?>
              <?= $totalNews ?> resultado(s) para "<?= e($search) ?>"
            <?php elseif ($category): ?>
              Las últimas noticias de <?= e($categoryName) ?>
            <?php else: ?>
              Las últimas novedades del mundo de los videojuegos
            <?php endif; ?>
          </p>
        </div>
        <div class="col-lg-4">
          <!-- Buscador -->
          <form action="news.php" method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="Buscar noticias..." value="<?= e($search) ?>">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
          </form>
        </div>
      </div>
      
      <!-- Filtros -->
      <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="news.php" class="category-badge <?= !$category ? 'active' : '' ?>">
          <i class="bi bi-grid"></i> Todas
        </a>
        <?php foreach ($categories as $cat): ?>
        <a href="news.php?category=<?= e($cat['slug']) ?>" 
           class="category-badge <?= $category === $cat['slug'] ? 'active' : '' ?>"
           style="<?= $category === $cat['slug'] ? 'border-color: ' . e($cat['color']) . '; color: ' . e($cat['color']) : '' ?>">
          <i class="bi bi-<?= e($cat['icon'] ?: 'tag') ?>"></i>
          <?= e($cat['name']) ?>
        </a>
        <?php endforeach; ?>
      </div>
      
      <!-- Grid de noticias -->
      <div class="row g-4">
        <?php if (!empty($news)): ?>
          <?php foreach ($news as $item): ?>
          <div class="col-md-6 col-lg-4">
            <article class="news-card h-100 animate-slide-up">
              <div class="card-image">
                <img src="assets/img/news/<?= e($item['image'] ?: 'placeholder.jpg') ?>" 
                     alt="<?= e($item['title']) ?>"
                     onerror="this.src='assets/img/placeholder-news.svg'">
                <span class="card-category" style="background: <?= e($item['category_color'] ?: 'var(--primary)') ?>">
                  <?= e($item['category_name'] ?: 'General') ?>
                </span>
              </div>
              <div class="card-body">
                <h5 class="card-title">
                  <a href="news-detail.php?slug=<?= e($item['slug']) ?>"><?= e($item['title']) ?></a>
                </h5>
                <p class="card-text"><?= e(truncateText($item['summary'] ?: strip_tags($item['content']), 120)) ?></p>
                <div class="card-meta">
                  <div class="author">
                    <img src="assets/img/avatars/<?= e($item['author_avatar'] ?: 'default-avatar.png') ?>" 
                         alt="<?= e($item['author_name']) ?>"
                         onerror="this.src='assets/img/avatars/default-avatar.png'">
                    <span><?= e($item['author_name']) ?></span>
                  </div>
                  <span><?= formatDate($item['published_at']) ?></span>
                </div>
              </div>
            </article>
          </div>
          <?php endforeach; ?>
        <?php else: ?>
          <!-- Sin resultados / Noticias de ejemplo -->
          <div class="col-12 text-center py-5">
            <i class="bi bi-inbox" style="font-size: 4rem; color: var(--muted);"></i>
            <h4 class="mt-3">No hay noticias disponibles</h4>
            <p class="text-secondary">
              <?php if ($search): ?>
                No se encontraron resultados para "<?= e($search) ?>". Intenta con otros términos.
              <?php else: ?>
                Pronto publicaremos nuevas noticias. ¡Vuelve pronto!
              <?php endif; ?>
            </p>
            <?php if ($search || $category): ?>
              <a href="news.php" class="btn btn-outline mt-2">Ver todas las noticias</a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
      
      <!-- Paginación -->
      <?php if ($totalPages > 1): ?>
      <nav aria-label="Paginación de noticias" class="mt-5">
        <ul class="pagination justify-content-center">
          <?php if ($page > 1): ?>
          <li class="page-item">
            <a class="page-link" href="?page=<?= $page - 1 ?><?= $category ? '&category=' . e($category) : '' ?><?= $search ? '&search=' . e($search) : '' ?>">
              <i class="bi bi-chevron-left"></i>
            </a>
          </li>
          <?php endif; ?>
          
          <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
          <li class="page-item <?= $i === $page ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $i ?><?= $category ? '&category=' . e($category) : '' ?><?= $search ? '&search=' . e($search) : '' ?>">
              <?= $i ?>
            </a>
          </li>
          <?php endfor; ?>
          
          <?php if ($page < $totalPages): ?>
          <li class="page-item">
            <a class="page-link" href="?page=<?= $page + 1 ?><?= $category ? '&category=' . e($category) : '' ?><?= $search ? '&search=' . e($search) : '' ?>">
              <i class="bi bi-chevron-right"></i>
            </a>
          </li>
          <?php endif; ?>
        </ul>
      </nav>
      <?php endif; ?>
    </main>
    
    <?php include __DIR__ . '/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

