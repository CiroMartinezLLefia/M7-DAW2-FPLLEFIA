<?php
session_start();

$isLogged = !empty($_SESSION['role']);
$isAdmin = $isLogged && $_SESSION['role'] === 'admin';
$isAlumne = $isLogged && $_SESSION['role'] === 'alumne';

// Detectar la ruta base según desde dónde se incluye el header
$scriptPath = $_SERVER['SCRIPT_NAME'];
$basePath = '';
if (strpos($scriptPath, '/admin/') !== false || strpos($scriptPath, '/alumne/') !== false) {
    $basePath = '../';
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MusicSchool Academy - Gestor Acadèmic</title>
    <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <div class="logo">
                <a href="<?php echo $basePath; ?>index.php">
                    <i class="fas fa-music"></i>
                    <span>MusicSchool Academy</span>
                </a>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="<?php echo $basePath; ?>index.php"><i class="fas fa-home"></i> Inici</a></li>
                    
                    <?php if (!$isLogged): ?>
                    <li><a href="<?php echo $basePath; ?>login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                    <li><a href="<?php echo $basePath; ?>register.php"><i class="fas fa-user-plus"></i> Registre</a></li>
                    <?php endif; ?>
                    
                    <?php if ($isAdmin): ?>
                    <!-- Admin -->
                    <li class="dropdown">
                        <a href="#"><i class="fas fa-cogs"></i> Admin <i class="fas fa-caret-down"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo $basePath; ?>admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                            <li><a href="<?php echo $basePath; ?>admin/instruments.php"><i class="fas fa-guitar"></i> Instruments</a></li>
                            <li><a href="<?php echo $basePath; ?>admin/alumnes.php"><i class="fas fa-user-graduate"></i> Alumnes</a></li>
                            <li><a href="<?php echo $basePath; ?>admin/professors.php"><i class="fas fa-user-tie"></i> Professors</a></li>
                            <li><a href="<?php echo $basePath; ?>admin/classes.php"><i class="fas fa-chalkboard-teacher"></i> Classes</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    
                    <?php if ($isAlumne): ?>
                    <!-- Alumne -->
                    <li class="dropdown">
                        <a href="#"><i class="fas fa-user"></i> Alumne <i class="fas fa-caret-down"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo $basePath; ?>alumne/dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                            <li><a href="<?php echo $basePath; ?>alumne/classes.php"><i class="fas fa-calendar"></i> Classes</a></li>
                            <li><a href="<?php echo $basePath; ?>alumne/horaris.php"><i class="fas fa-clock"></i> Horaris</a></li>
                            <li><a href="<?php echo $basePath; ?>alumne/perfil.php"><i class="fas fa-user-edit"></i> Perfil</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    
                    <?php if ($isLogged): ?>
                    <li><a href="<?php echo $basePath; ?>logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <button class="mobile-menu-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>
    <main class="main-content">
