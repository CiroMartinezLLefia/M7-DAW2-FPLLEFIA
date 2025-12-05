<?php
require_once 'header.php';
?>

            <section class="hero">
                <div class="hero-content">
                    <h1><i class="fas fa-music"></i> Benvingut a MusicSchool Academy</h1>
                    <p>El teu centre de formació musical d'excel·lència</p>
                    <?php if (!$isLogged): ?>
                    <div class="hero-buttons">
                        <a href="register.php" class="btn btn-primary"><i class="fas fa-user-plus"></i> Registra't ara</a>
                        <a href="login.php" class="btn btn-secondary"><i class="fas fa-sign-in-alt"></i> Inicia Sessió</a>
                    </div>
                    <?php endif; ?>
                </div>
            </section>

<section class="features">
    <div class="container">
        <h2>Els Nostres Serveis</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-guitar"></i>
                </div>
                <h3>Instruments Variats</h3>
                <p>Aprèn a tocar guitarra, piano, violí, bateria i molts més instruments amb professors experts.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <h3>Professors Qualificats</h3>
                <p>El nostre equip de professors té àmplia experiència i titulacions reconegudes.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3>Horaris Flexibles</h3>
                <p>Adaptem els horaris a les teves necessitats per facilitar el teu aprenentatge.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Classes Personalitzades</h3>
                <p>Sessions individuals i grupals adaptades al teu nivell i objectius.</p>
            </div>
        </div>
    </div>
</section>

<section class="instruments-preview">
    <div class="container">
        <h2>Instruments que Ensenyem</h2>
        <div class="instruments-grid">
            <div class="instrument-card">
                <i class="fas fa-guitar"></i>
                <span>Guitarra</span>
            </div>
            <div class="instrument-card">
                <i class="fas fa-piano"></i>
                <span>Piano</span>
            </div>
            <div class="instrument-card">
                <i class="fas fa-drum"></i>
                <span>Bateria</span>
            </div>
            <div class="instrument-card">
                <i class="fas fa-violin"></i>
                <span>Violí</span>
            </div>
            <div class="instrument-card">
                <i class="fas fa-saxophone"></i>
                <span>Saxofon</span>
            </div>
            <div class="instrument-card">
                <i class="fas fa-microphone-alt"></i>
                <span>Cant</span>
            </div>
        </div>
    </div>
</section>

<section class="cta">
    <div class="container">
        <div class="cta-content">
            <h2>Comença el teu viatge musical avui!</h2>
            <p>Registra't i accedeix al nostre sistema de gestió acadèmica per consultar les teves classes i horaris.</p>
            <a href="register.php" class="btn btn-primary btn-large"><i class="fas fa-rocket"></i> Comença ara</a>
        </div>
    </div>
</section>

<?php require_once 'footer.php'; ?>
