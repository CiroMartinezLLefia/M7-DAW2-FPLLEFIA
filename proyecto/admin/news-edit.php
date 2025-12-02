<?php
/**
 * GameZone - Crear/Editar Noticia (Admin)
 */
require_once __DIR__ . '/../config.php';
initSession();

if (!canEdit()) {
    redirect('../login.php');
}

$pdo = getDBConnection();
$newsId = intval($_GET['id'] ?? 0);
$isEdit = $newsId > 0;
$news = null;
$categories = [];
$errors = [];

// Obtener categorías
if ($pdo) {
    $categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
}

// Si es edición, cargar la noticia
if ($isEdit && $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->execute([$newsId]);
    $news = $stmt->fetch();
    
    if (!$news) {
        setFlashMessage('error', 'Noticia no encontrada.');
        redirect('news.php');
    }
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $summary = trim($_POST['summary'] ?? '');
    $content = $_POST['content'] ?? '';
    $categoryId = intval($_POST['category_id'] ?? 0) ?: null;
    $status = $_POST['status'] ?? 'draft';
    $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
    $image = trim($_POST['image'] ?? '');
    $imageCaption = trim($_POST['image_caption'] ?? '');
    
    // Validaciones
    if (empty($title)) {
        $errors['title'] = 'El título es obligatorio.';
    }
    
    if (empty($slug)) {
        $slug = generateSlug($title);
    } else {
        $slug = generateSlug($slug);
    }
    
    if (empty($content)) {
        $errors['content'] = 'El contenido es obligatorio.';
    }
    
    // Verificar slug único
    if (empty($errors) && $pdo) {
        $checkSql = "SELECT id FROM news WHERE slug = ? AND id != ?";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([$slug, $newsId]);
        if ($checkStmt->fetch()) {
            $slug = $slug . '-' . time();
        }
    }
    
    // Guardar
    if (empty($errors) && $pdo) {
        try {
            $publishedAt = $status === 'published' ? ($news['published_at'] ?? date('Y-m-d H:i:s')) : null;
            
            if ($isEdit) {
                $sql = "UPDATE news SET 
                    title = ?, slug = ?, summary = ?, content = ?, category_id = ?,
                    status = ?, is_featured = ?, image = ?, image_caption = ?, 
                    published_at = ?, updated_at = NOW()
                    WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $title, $slug, $summary, $content, $categoryId,
                    $status, $isFeatured, $image, $imageCaption,
                    $publishedAt, $newsId
                ]);
                setFlashMessage('success', 'Noticia actualizada correctamente.');
            } else {
                $sql = "INSERT INTO news 
                    (author_id, title, slug, summary, content, category_id, status, is_featured, image, image_caption, published_at, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    getCurrentUser()['id'],
                    $title, $slug, $summary, $content, $categoryId,
                    $status, $isFeatured, $image, $imageCaption, $publishedAt
                ]);
                setFlashMessage('success', 'Noticia creada correctamente.');
            }
            
            redirect('news.php');
            
        } catch (PDOException $e) {
            error_log("Error guardando noticia: " . $e->getMessage());
            $errors['general'] = 'Error al guardar la noticia. Inténtalo de nuevo.';
        }
    }
    
    // Mantener datos en caso de error
    $news = [
        'title' => $title,
        'slug' => $slug,
        'summary' => $summary,
        'content' => $content,
        'category_id' => $categoryId,
        'status' => $status,
        'is_featured' => $isFeatured,
        'image' => $image,
        'image_caption' => $imageCaption
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEdit ? 'Editar' : 'Nueva' ?> Noticia — <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="admin-layout">
      <!-- Sidebar -->
      <aside class="admin-sidebar">
        <a href="../index.php" class="navbar-brand mb-4 d-block">
          <div class="d-flex align-items-center gap-2">
            <div class="logo" style="width: 40px; height: 40px; font-size: 1rem;">GZ</div>
            <div>
              <span class="brand-name" style="font-size: 1.1rem;"><?= SITE_NAME ?></span>
              <small class="d-block text-muted" style="font-size: 0.7rem;">Panel Admin</small>
            </div>
          </div>
        </a>
        
        <ul class="admin-nav">
          <li><a href="index.php"><i class="bi bi-speedometer2"></i>Dashboard</a></li>
          <li><a href="news.php" class="active"><i class="bi bi-newspaper"></i>Noticias</a></li>
          <li><a href="categories.php"><i class="bi bi-tags"></i>Categorías</a></li>
          <?php if (isAdmin()): ?>
          <li><a href="users.php"><i class="bi bi-people"></i>Usuarios</a></li>
          <?php endif; ?>
          <li><a href="comments.php"><i class="bi bi-chat-dots"></i>Comentarios</a></li>
          <li><a href="faqs.php"><i class="bi bi-question-circle"></i>FAQs</a></li>
        </ul>
        
        <hr class="border-subtle">
        
        <ul class="admin-nav">
          <li><a href="../index.php"><i class="bi bi-box-arrow-left"></i>Volver al sitio</a></li>
        </ul>
      </aside>
      
      <!-- Contenido -->
      <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h1 class="h3 mb-1"><?= $isEdit ? 'Editar Noticia' : 'Nueva Noticia' ?></h1>
            <p class="text-muted mb-0">
              <a href="news.php" class="text-primary"><i class="bi bi-arrow-left me-1"></i>Volver al listado</a>
            </p>
          </div>
        </div>
        
        <?php if (!empty($errors['general'])): ?>
          <div class="alert alert-danger"><?= e($errors['general']) ?></div>
        <?php endif; ?>
        
        <form method="POST" class="row g-4">
          <div class="col-lg-8">
            <!-- Título -->
            <div class="card p-4 mb-4">
              <div class="mb-3">
                <label class="form-label">Título *</label>
                <input type="text" name="title" class="form-control form-control-lg <?= isset($errors['title']) ? 'is-invalid' : '' ?>"
                       value="<?= e($news['title'] ?? '') ?>" placeholder="Título de la noticia" required>
                <?php if (isset($errors['title'])): ?>
                  <div class="invalid-feedback"><?= e($errors['title']) ?></div>
                <?php endif; ?>
              </div>
              
              <div class="mb-3">
                <label class="form-label">Slug (URL)</label>
                <div class="input-group">
                  <span class="input-group-text bg-dark border-subtle"><?= SITE_URL ?>/news-detail.php?slug=</span>
                  <input type="text" name="slug" class="form-control" value="<?= e($news['slug'] ?? '') ?>" placeholder="se-genera-automaticamente">
                </div>
                <small class="text-muted">Déjalo vacío para generarlo automáticamente</small>
              </div>
              
              <div class="mb-3">
                <label class="form-label">Resumen</label>
                <textarea name="summary" class="form-control" rows="2" placeholder="Breve descripción de la noticia"><?= e($news['summary'] ?? '') ?></textarea>
              </div>
              
              <div class="mb-0">
                <label class="form-label">Contenido *</label>
                <textarea name="content" class="form-control <?= isset($errors['content']) ? 'is-invalid' : '' ?>" rows="15" 
                          placeholder="Escribe el contenido de la noticia (HTML permitido)" required><?= e($news['content'] ?? '') ?></textarea>
                <?php if (isset($errors['content'])): ?>
                  <div class="invalid-feedback"><?= e($errors['content']) ?></div>
                <?php endif; ?>
                <small class="text-muted">Puedes usar HTML para formatear el contenido</small>
              </div>
            </div>
          </div>
          
          <div class="col-lg-4">
            <!-- Opciones de publicación -->
            <div class="card p-4 mb-4">
              <h6 class="mb-3">Publicación</h6>
              
              <div class="mb-3">
                <label class="form-label">Estado</label>
                <select name="status" class="form-select">
                  <option value="draft" <?= ($news['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Borrador</option>
                  <option value="published" <?= ($news['status'] ?? '') === 'published' ? 'selected' : '' ?>>Publicado</option>
                  <option value="archived" <?= ($news['status'] ?? '') === 'archived' ? 'selected' : '' ?>>Archivado</option>
                </select>
              </div>
              
              <div class="mb-3">
                <label class="form-label">Categoría</label>
                <select name="category_id" class="form-select">
                  <option value="">Sin categoría</option>
                  <?php foreach ($categories as $cat): ?>
                  <option value="<?= $cat['id'] ?>" <?= ($news['category_id'] ?? 0) == $cat['id'] ? 'selected' : '' ?>>
                    <?= e($cat['name']) ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>
              
              <div class="form-check mb-3">
                <input type="checkbox" name="is_featured" class="form-check-input" id="is_featured"
                       <?= ($news['is_featured'] ?? 0) ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_featured">
                  <i class="bi bi-star text-warning me-1"></i>Noticia destacada
                </label>
              </div>
              
              <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-check-lg me-2"></i><?= $isEdit ? 'Actualizar' : 'Crear' ?> Noticia
              </button>
            </div>
            
            <!-- Imagen destacada -->
            <div class="card p-4">
              <h6 class="mb-3">Imagen Destacada</h6>
              
              <div class="mb-3">
                <label class="form-label">Nombre del archivo</label>
                <input type="text" name="image" class="form-control" value="<?= e($news['image'] ?? '') ?>" 
                       placeholder="imagen.jpg">
                <small class="text-muted">Coloca la imagen en assets/img/news/</small>
              </div>
              
              <div class="mb-0">
                <label class="form-label">Pie de imagen</label>
                <input type="text" name="image_caption" class="form-control" value="<?= e($news['image_caption'] ?? '') ?>"
                       placeholder="Descripción de la imagen">
              </div>
            </div>
          </div>
        </form>
      </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
