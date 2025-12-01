<?php
// Header (navbar) con placeholders para nombre e imagen
?>
<header class="site-header mb-4">
  <nav class="navbar navbar-expand-lg navbar-dark bg-transparent border-bottom">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="index.php">
        <img src="assets/img/logo.svg" alt="Logo" width="42" height="42" class="me-2">
        <div>
          <div class="fw-bold">2High2Work</div>
          <small class="muted">TAGLINE_PLACEHOLDER</small>
        </div>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="news.php">Noticias</a></li>
          <li class="nav-item"><a class="nav-link" href="portfolio.php">Portfolio</a></li>
          <li class="nav-item"><a class="nav-link" href="testimonials.php">Testimonios</a></li>
          <li class="nav-item"><a class="nav-link" href="faqs.php">FAQs</a></li>
          <li class="nav-item"><a class="btn btn-primary ms-2 glow-on-hover" href="contact.php">Contacto</a></li>
        </ul>
      </div>
    </div>
  </nav>
</header>
