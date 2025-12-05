    </main>
    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><i class="fas fa-music"></i> MusicSchool Academy</h3>
                    <p>Centre dedicat a la formació musical d'excel·lència.</p>
                </div>
                <div class="footer-section">
                    <h4>Contacte</h4>
                    <p><i class="fas fa-envelope"></i> info@musicschool.academy</p>
                    <p><i class="fas fa-phone"></i> +34 93 XXX XX XX</p>
                    <p><i class="fas fa-map-marker-alt"></i> Barcelona, Catalunya</p>
                </div>
                <div class="footer-section">
                    <h4>Enllaços</h4>
                    <ul>
                        <li><a href="<?php echo $basePath; ?>index.php">Inici</a></li>
                        <li><a href="<?php echo $basePath; ?>login.php">Iniciar Sessió</a></li>
                        <li><a href="<?php echo $basePath; ?>register.php">Registrar-se</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 MusicSchool Academy. Tots els drets reservats.</p>
            </div>
        </div>
    </footer>
    <script>
        // Toggle mobile menu
        document.querySelector('.mobile-menu-toggle')?.addEventListener('click', function() {
            document.querySelector('.main-nav').classList.toggle('active');
        });
        
        // Dropdown menu toggle
        document.querySelectorAll('.dropdown > a').forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                this.parentElement.classList.toggle('active');
            });
        });
    </script>
</body>
</html>
