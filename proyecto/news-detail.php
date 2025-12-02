<?php
/**
 * GameZone - Detalle de Noticia
 */
require_once __DIR__ . '/config.php';
initSession();

$slug = $_GET['slug'] ?? '';
$news = null;
$relatedNews = [];
$comments = [];
$tags = [];

if (empty($slug)) {
    redirect('news.php');
}

$pdo = getDBConnection();

if ($pdo) {
    try {
        // Obtener la noticia
        $stmt = $pdo->prepare("
            SELECT n.*, c.name as category_name, c.slug as category_slug, c.color as category_color,
                   u.display_name as author_name, u.avatar as author_avatar, u.bio as author_bio
            FROM news n
            LEFT JOIN categories c ON n.category_id = c.id
            LEFT JOIN users u ON n.author_id = u.id
            WHERE n.slug = ? AND n.status = 'published'
            LIMIT 1
        ");
        $stmt->execute([$slug]);
        $news = $stmt->fetch();
        
        if (!$news) {
            redirect('news.php');
        }
        
        // Incrementar vistas
        $pdo->prepare("UPDATE news SET views = views + 1 WHERE id = ?")->execute([$news['id']]);
        
        // Obtener tags de la noticia
        $stmt = $pdo->prepare("
            SELECT t.* FROM tags t
            JOIN news_tags nt ON t.id = nt.tag_id
            WHERE nt.news_id = ?
        ");
        $stmt->execute([$news['id']]);
        $tags = $stmt->fetchAll();
        
        // Obtener comentarios
        $stmt = $pdo->prepare("
            SELECT c.*, u.username, u.display_name, u.avatar
            FROM comments c
            LEFT JOIN users u ON c.user_id = u.id
            WHERE c.news_id = ? AND c.is_approved = 1
            ORDER BY c.created_at DESC
            LIMIT 20
        ");
        $stmt->execute([$news['id']]);
        $comments = $stmt->fetchAll();
        
        // Obtener noticias relacionadas
        $stmt = $pdo->prepare("
            SELECT n.*, c.name as category_name, c.color as category_color
            FROM news n
            LEFT JOIN categories c ON n.category_id = c.id
            WHERE n.id != ? AND n.status = 'published' 
            AND (n.category_id = ? OR n.category_id IS NULL)
            ORDER BY n.published_at DESC
            LIMIT 3
        ");
        $stmt->execute([$news['id'], $news['category_id']]);
        $relatedNews = $stmt->fetchAll();
        
    } catch (PDOException $e) {
        error_log("Error cargando noticia: " . $e->getMessage());
        redirect('news.php');
    }
}

// Procesar nuevo comentario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn()) {
    $content = trim($_POST['comment'] ?? '');
    
    if (!empty($content) && $pdo) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO comments (user_id, news_id, content, is_approved, created_at)
                VALUES (?, ?, ?, 1, NOW())
            ");
            $stmt->execute([
                getCurrentUser()['id'],
                $news['id'],
                $content
            ]);
            
            setFlashMessage('success', '¡Comentario publicado correctamente!');
            redirect("news-detail.php?slug=" . e($slug) . "#comments");
        } catch (PDOException $e) {
            error_log("Error al comentar: " . $e->getMessage());
            setFlashMessage('error', 'Error al publicar el comentario.');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e(truncateText($news['summary'] ?: strip_tags($news['content']), 160)) ?>">
    <title><?= e($news['title']) ?> — <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>
    
    <main class="news-detail">
      <!-- Breadcrumb -->
      <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php" class="text-secondary">Inicio</a></li>
          <li class="breadcrumb-item"><a href="news.php" class="text-secondary">Noticias</a></li>
          <?php if ($news['category_name']): ?>
          <li class="breadcrumb-item">
            <a href="news.php?category=<?= e($news['category_slug']) ?>" class="text-secondary">
              <?= e($news['category_name']) ?>
            </a>
          </li>
          <?php endif; ?>
          <li class="breadcrumb-item active text-muted"><?= e(truncateText($news['title'], 30)) ?></li>
        </ol>
      </nav>
      
      <!-- Flash messages -->
      <?php $flash = getFlashMessage(); if ($flash): ?>
        <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show">
          <?= e($flash['message']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>
      
      <!-- Header de la noticia -->
      <header class="news-header">
        <div class="news-meta">
          <span class="badge" style="background: <?= e($news['category_color'] ?: 'var(--primary)') ?>">
            <?= e($news['category_name'] ?: 'General') ?>
          </span>
          <span><i class="bi bi-calendar3 me-1"></i><?= formatDate($news['published_at'], 'd F, Y') ?></span>
          <span><i class="bi bi-eye me-1"></i><?= number_format($news['views']) ?> vistas</span>
        </div>
        
        <h1 class="news-title"><?= e($news['title']) ?></h1>
        
        <?php if ($news['summary']): ?>
        <p class="news-summary"><?= e($news['summary']) ?></p>
        <?php endif; ?>
        
        <!-- Autor -->
        <div class="d-flex align-items-center gap-3 mt-4 p-3 rounded-lg" style="background: var(--bg-card);">
          <img src="assets/img/avatars/<?= e($news['author_avatar'] ?: 'default-avatar.png') ?>" 
               alt="<?= e($news['author_name']) ?>"
               class="rounded-circle"
               width="50" height="50"
               onerror="this.src='assets/img/avatars/default-avatar.png'">
          <div>
            <div class="fw-bold"><?= e($news['author_name']) ?></div>
            <small class="text-muted">Autor</small>
          </div>
        </div>
      </header>
      
      <!-- Imagen principal -->
      <?php if ($news['image']): ?>
      <figure class="news-image">
        <img src="assets/img/news/<?= e($news['image']) ?>" 
             alt="<?= e($news['title']) ?>"
             onerror="this.src='assets/img/placeholder-news.svg'">
        <?php if ($news['image_caption']): ?>
        <figcaption class="text-muted text-center mt-2 small"><?= e($news['image_caption']) ?></figcaption>
        <?php endif; ?>
      </figure>
      <?php endif; ?>
      
      <!-- Contenido -->
      <article class="news-content">
        <?= $news['content'] ?>
      </article>
      
      <!-- Tags -->
      <?php if (!empty($tags)): ?>
      <div class="news-tags">
        <strong class="me-2">Etiquetas:</strong>
        <?php foreach ($tags as $tag): ?>
        <a href="news.php?tag=<?= e($tag['slug']) ?>" class="tag"><?= e($tag['name']) ?></a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
      
      <!-- Compartir -->
      <div class="d-flex align-items-center gap-3 mt-4 pt-4 border-top border-subtle">
        <span class="text-muted">Compartir:</span>
        <a href="https://twitter.com/intent/tweet?url=<?= urlencode(SITE_URL . '/news-detail.php?slug=' . $slug) ?>&text=<?= urlencode($news['title']) ?>" 
           target="_blank" class="btn btn-outline btn-sm">
          <i class="bi bi-twitter-x"></i>
        </a>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(SITE_URL . '/news-detail.php?slug=' . $slug) ?>" 
           target="_blank" class="btn btn-outline btn-sm">
          <i class="bi bi-facebook"></i>
        </a>
        <a href="https://wa.me/?text=<?= urlencode($news['title'] . ' ' . SITE_URL . '/news-detail.php?slug=' . $slug) ?>" 
           target="_blank" class="btn btn-outline btn-sm">
          <i class="bi bi-whatsapp"></i>
        </a>
      </div>
      
      <!-- Comentarios -->
      <section class="comments-section" id="comments">
        <h3><i class="bi bi-chat-dots me-2"></i>Comentarios (<?= count($comments) ?>)</h3>
        
        <!-- Formulario de comentario -->
        <?php if (isLoggedIn()): ?>
        <form method="POST" class="mb-4">
          <div class="mb-3">
            <textarea name="comment" class="form-control" rows="3" placeholder="Escribe tu comentario..." required></textarea>
          </div>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-send me-2"></i>Publicar comentario
          </button>
        </form>
        <?php else: ?>
        <div class="alert alert-info mb-4">
          <i class="bi bi-info-circle me-2"></i>
          <a href="login.php">Inicia sesión</a> o <a href="register.php">regístrate</a> para comentar.
        </div>
        <?php endif; ?>
        
        <!-- Lista de comentarios -->
        <?php if (!empty($comments)): ?>
          <?php foreach ($comments as $comment): ?>
          <div class="comment">
            <div class="comment-header">
              <img src="assets/img/avatars/<?= e($comment['avatar'] ?: 'default-avatar.png') ?>" 
                   alt="<?= e($comment['display_name'] ?: $comment['username']) ?>"
                   onerror="this.src='assets/img/avatars/default-avatar.png'">
              <div>
                <span class="comment-author"><?= e($comment['display_name'] ?: $comment['username']) ?></span>
                <span class="comment-date"><?= formatDate($comment['created_at'], 'd M Y, H:i') ?></span>
              </div>
            </div>
            <div class="comment-content">
              <?= nl2br(e($comment['content'])) ?>
            </div>
          </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-muted">Aún no hay comentarios. ¡Sé el primero en comentar!</p>
        <?php endif; ?>
      </section>
      
      <!-- Noticias relacionadas -->
      <?php if (!empty($relatedNews)): ?>
      <section class="mt-5 pt-4 border-top border-subtle">
        <h3 class="mb-4">Noticias relacionadas</h3>
        <div class="row g-4">
          <?php foreach ($relatedNews as $related): ?>
          <div class="col-md-4">
            <article class="news-card h-100">
              <div class="card-image">
                <img src="assets/img/news/<?= e($related['image'] ?: 'placeholder.jpg') ?>" 
                     alt="<?= e($related['title']) ?>"
                     onerror="this.src='assets/img/placeholder-news.svg'">
                <span class="card-category" style="background: <?= e($related['category_color'] ?: 'var(--primary)') ?>">
                  <?= e($related['category_name'] ?: 'General') ?>
                </span>
              </div>
              <div class="card-body">
                <h5 class="card-title">
                  <a href="news-detail.php?slug=<?= e($related['slug']) ?>"><?= e($related['title']) ?></a>
                </h5>
              </div>
            </article>
          </div>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endif; ?>
    </main>
    
    <?php include __DIR__ . '/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
