<?php
/**
 * GameZone - Footer
 */
?>
<footer class="site-footer">
  <div class="container">
    <div class="row g-4">
      <!-- Marca y descripción -->
      <div class="col-lg-4 col-md-6">
        <div class="footer-brand">
          <div class="logo">GZ</div>
          <span class="name"><?= SITE_NAME ?></span>
        </div>
        <p class="footer-description">
          Tu fuente de confianza para noticias, análisis y guías del mundo de los videojuegos. 
          Mantente al día con las últimas novedades gaming.
        </p>
        <div class="footer-social">
          <a href="#" title="Twitter/X"><i class="bi bi-twitter-x"></i></a>
          <a href="#" title="Discord"><i class="bi bi-discord"></i></a>
          <a href="#" title="YouTube"><i class="bi bi-youtube"></i></a>
          <a href="#" title="Twitch"><i class="bi bi-twitch"></i></a>
        </div>
      </div>
      
      <!-- Enlaces rápidos -->
      <div class="col-lg-2 col-md-6">
        <h6 class="footer-title">Navegación</h6>
        <ul class="footer-links">
          <li><a href="index.php">Inicio</a></li>
          <li><a href="news.php">Noticias</a></li>
          <li><a href="reviews.php">Análisis</a></li>
          <li><a href="contact.php">Contacto</a></li>
        </ul>
      </div>
      
      <!-- Categorías -->
      <div class="col-lg-2 col-md-6">
        <h6 class="footer-title">Categorías</h6>
        <ul class="footer-links">
          <li><a href="news.php?category=noticias">Noticias</a></li>
          <li><a href="news.php?category=analisis">Análisis</a></li>
          <li><a href="news.php?category=avances">Avances</a></li>
          <li><a href="news.php?category=esports">eSports</a></li>
        </ul>
      </div>
      
      <!-- Plataformas -->
      <div class="col-lg-2 col-md-6">
        <h6 class="footer-title">Plataformas</h6>
        <ul class="footer-links">
          <li><a href="news.php?platform=ps5">PlayStation 5</a></li>
          <li><a href="news.php?platform=xbox">Xbox Series X</a></li>
          <li><a href="news.php?platform=switch">Nintendo Switch</a></li>
          <li><a href="news.php?platform=pc">PC Gaming</a></li>
        </ul>
      </div>
      
      <!-- Newsletter -->
      <div class="col-lg-2 col-md-6">
        <h6 class="footer-title">Newsletter</h6>
        <p class="text-muted small">Recibe las últimas noticias en tu email.</p>
        <form action="newsletter.php" method="POST" class="mt-3">
          <div class="input-group">
            <input type="email" name="email" class="form-control form-control-sm" placeholder="tu@email.com" required>
            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-send"></i></button>
          </div>
        </form>
      </div>
    </div>
    
    <!-- Bottom -->
    <div class="footer-bottom">
      <div class="row align-items-center">
        <div class="col-md-6 text-center text-md-start">
          <p class="mb-0">
            &copy; <span id="year"></span> <strong><?= SITE_NAME ?></strong>. Todos los derechos reservados.
          </p>
        </div>
        <div class="col-md-6 text-center text-md-end">
          <a href="#" class="text-muted me-3">Términos de uso</a>
          <a href="#" class="text-muted me-3">Privacidad</a>
          <a href="#" class="text-muted">Cookies</a>
        </div>
      </div>
    </div>
  </div>
  <script>document.getElementById('year').textContent = new Date().getFullYear();</script>
</footer>

