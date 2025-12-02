-- =====================================================
-- Esquema SQL para GameZone - Portal de Noticias de Videojuegos
-- IMPORTANTE: Crea la base de datos desde el panel de AlwaysData
-- antes de importar este archivo
-- =====================================================

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- =====================================================
-- TABLA: users (Usuarios del sistema)
-- Roles: user (lector), editor (puede crear noticias), admin (control total)
-- =====================================================
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('user','editor','admin') DEFAULT 'user',
  display_name VARCHAR(100),
  avatar VARCHAR(255) DEFAULT 'default-avatar.png',
  bio TEXT,
  is_active BOOLEAN DEFAULT TRUE,
  email_verified BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
  last_login TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: platforms (Plataformas de videojuegos)
-- =====================================================
CREATE TABLE IF NOT EXISTS platforms (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL UNIQUE,
  slug VARCHAR(50) NOT NULL UNIQUE,
  icon VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: categories (Categorías de noticias)
-- =====================================================
CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL UNIQUE,
  slug VARCHAR(80) NOT NULL UNIQUE,
  description TEXT,
  color VARCHAR(7) DEFAULT '#6366f1',
  icon VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: news (Noticias de videojuegos)
-- =====================================================
CREATE TABLE IF NOT EXISTS news (
  id INT AUTO_INCREMENT PRIMARY KEY,
  author_id INT NOT NULL,
  category_id INT,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  summary TEXT,
  content LONGTEXT,
  image VARCHAR(255),
  image_caption VARCHAR(255),
  views INT DEFAULT 0,
  is_featured BOOLEAN DEFAULT FALSE,
  status ENUM('draft','published','archived') DEFAULT 'draft',
  published_at DATETIME,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: games (Videojuegos referenciados en noticias)
-- =====================================================
CREATE TABLE IF NOT EXISTS games (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  slug VARCHAR(200) NOT NULL UNIQUE,
  developer VARCHAR(150),
  publisher VARCHAR(150),
  release_date DATE,
  cover_image VARCHAR(255),
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: reviews (Análisis de videojuegos)
-- =====================================================
CREATE TABLE IF NOT EXISTS reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  game_id INT NOT NULL,
  author_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  content LONGTEXT,
  score DECIMAL(3,1) CHECK (score >= 0 AND score <= 10),
  pros TEXT,
  cons TEXT,
  verdict TEXT,
  status ENUM('draft','published','archived') DEFAULT 'draft',
  published_at DATETIME,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE,
  FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: comments (Comentarios en noticias)
-- =====================================================
CREATE TABLE IF NOT EXISTS comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  news_id INT NOT NULL,
  parent_id INT DEFAULT NULL,
  content TEXT NOT NULL,
  is_approved BOOLEAN DEFAULT TRUE,
  likes_count INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (news_id) REFERENCES news(id) ON DELETE CASCADE,
  FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: tags (Etiquetas para noticias)
-- =====================================================
CREATE TABLE IF NOT EXISTS tags (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL UNIQUE,
  slug VARCHAR(50) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: news_tags (Relación N:M entre noticias y tags)
-- =====================================================
CREATE TABLE IF NOT EXISTS news_tags (
  news_id INT NOT NULL,
  tag_id INT NOT NULL,
  PRIMARY KEY(news_id, tag_id),
  FOREIGN KEY (news_id) REFERENCES news(id) ON DELETE CASCADE,
  FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: news_platforms (Relación N:M entre noticias y plataformas)
-- =====================================================
CREATE TABLE IF NOT EXISTS news_platforms (
  news_id INT NOT NULL,
  platform_id INT NOT NULL,
  PRIMARY KEY(news_id, platform_id),
  FOREIGN KEY (news_id) REFERENCES news(id) ON DELETE CASCADE,
  FOREIGN KEY (platform_id) REFERENCES platforms(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: game_platforms (Relación N:M entre juegos y plataformas)
-- =====================================================
CREATE TABLE IF NOT EXISTS game_platforms (
  game_id INT NOT NULL,
  platform_id INT NOT NULL,
  PRIMARY KEY(game_id, platform_id),
  FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE,
  FOREIGN KEY (platform_id) REFERENCES platforms(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: faqs (Preguntas frecuentes)
-- =====================================================
CREATE TABLE IF NOT EXISTS faqs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  question VARCHAR(255) NOT NULL,
  answer TEXT NOT NULL,
  category VARCHAR(100) DEFAULT 'General',
  display_order INT DEFAULT 0,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: contact_messages (Mensajes de contacto)
-- =====================================================
CREATE TABLE IF NOT EXISTS contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  subject VARCHAR(200),
  message TEXT NOT NULL,
  is_read BOOLEAN DEFAULT FALSE,
  replied_at TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: newsletters (Suscriptores al newsletter)
-- =====================================================
CREATE TABLE IF NOT EXISTS newsletters (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(150) NOT NULL UNIQUE,
  is_active BOOLEAN DEFAULT TRUE,
  subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  unsubscribed_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: user_favorites (Noticias favoritas de usuarios)
-- =====================================================
CREATE TABLE IF NOT EXISTS user_favorites (
  user_id INT NOT NULL,
  news_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY(user_id, news_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (news_id) REFERENCES news(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ÍNDICES para optimización
-- =====================================================
CREATE INDEX idx_news_status ON news(status);
CREATE INDEX idx_news_published ON news(published_at);
CREATE INDEX idx_news_featured ON news(is_featured);
CREATE INDEX idx_news_category ON news(category_id);
CREATE INDEX idx_comments_news ON comments(news_id);
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_users_active ON users(is_active);

-- =====================================================
-- DATOS INICIALES
-- =====================================================

-- Insertar usuario administrador por defecto (password: admin123)
INSERT INTO users (username, email, password, role, display_name, bio) VALUES
('admin', 'admin@gamezone.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Administrador', 'Administrador del portal GameZone'),
('editor', 'editor@gamezone.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'editor', 'Editor Principal', 'Editor de contenido gaming'),
('gamer123', 'gamer@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'Gamer Pro', 'Apasionado de los videojuegos');

-- Insertar plataformas
INSERT INTO platforms (name, slug, icon) VALUES
('PlayStation 5', 'ps5', 'playstation'),
('Xbox Series X', 'xbox-series-x', 'xbox'),
('Nintendo Switch', 'nintendo-switch', 'nintendo'),
('PC', 'pc', 'windows'),
('Steam Deck', 'steam-deck', 'steam');

-- Insertar categorías
INSERT INTO categories (name, slug, description, color, icon) VALUES
('Noticias', 'noticias', 'Últimas noticias del mundo gaming', '#3b82f6', 'newspaper'),
('Análisis', 'analisis', 'Reviews y análisis de videojuegos', '#10b981', 'star'),
('Avances', 'avances', 'Previews y avances de juegos', '#8b5cf6', 'eye'),
('Guías', 'guias', 'Guías y tutoriales de videojuegos', '#f59e0b', 'book'),
('eSports', 'esports', 'Noticias de deportes electrónicos', '#ef4444', 'trophy'),
('Tecnología', 'tecnologia', 'Hardware y tecnología gaming', '#06b6d4', 'cpu');

-- Insertar tags populares
INSERT INTO tags (name, slug) VALUES
('RPG', 'rpg'),
('Shooter', 'shooter'),
('Aventura', 'aventura'),
('Indie', 'indie'),
('Multijugador', 'multijugador'),
('Exclusivo', 'exclusivo'),
('Free to Play', 'free-to-play'),
('Battle Royale', 'battle-royale'),
('Mundo Abierto', 'mundo-abierto'),
('Remake', 'remake');

-- Insertar juegos de ejemplo
INSERT INTO games (title, slug, developer, publisher, release_date, description) VALUES
('The Legend of Zelda: Tears of the Kingdom', 'zelda-totk', 'Nintendo EPD', 'Nintendo', '2023-05-12', 'Secuela del aclamado Breath of the Wild'),
('Elden Ring', 'elden-ring', 'FromSoftware', 'Bandai Namco', '2022-02-25', 'RPG de acción en mundo abierto'),
('God of War Ragnarök', 'god-of-war-ragnarok', 'Santa Monica Studio', 'Sony Interactive Entertainment', '2022-11-09', 'Aventura épica nórdica'),
('Baldurs Gate 3', 'baldurs-gate-3', 'Larian Studios', 'Larian Studios', '2023-08-03', 'RPG basado en D&D'),
('Starfield', 'starfield', 'Bethesda Game Studios', 'Bethesda Softworks', '2023-09-06', 'RPG espacial de nueva generación');

-- Insertar noticias de ejemplo
INSERT INTO news (author_id, category_id, title, slug, summary, content, image, is_featured, status, published_at) VALUES
(2, 1, 'Nintendo anuncia el sucesor de Switch para 2025', 'nintendo-sucesor-switch-2025', 
'Nintendo ha confirmado oficialmente que el sucesor de Nintendo Switch llegará durante el año fiscal 2025.',
'<p>En un comunicado oficial, Nintendo ha revelado finalmente que la tan esperada sucesora de Nintendo Switch verá la luz durante el año fiscal 2025, que concluye en marzo de 2026.</p>
<p>La compañía japonesa ha mantenido un hermetismo absoluto sobre las especificaciones técnicas del nuevo hardware, aunque fuentes cercanas al desarrollo sugieren mejoras significativas en potencia gráfica y rendimiento.</p>
<p>Se espera que la nueva consola mantenga el concepto híbrido que tanto éxito ha dado a Switch, permitiendo jugar tanto en modo portátil como conectada al televisor.</p>
<h3>Retrocompatibilidad confirmada</h3>
<p>Nintendo ha confirmado que la nueva consola será compatible con los juegos de Nintendo Switch, una noticia que ha sido recibida con entusiasmo por la comunidad.</p>',
'nintendo-switch-2.jpg', TRUE, 'published', NOW()),

(2, 1, 'GTA 6 confirma fecha de lanzamiento para otoño 2025', 'gta-6-fecha-lanzamiento-otono-2025',
'Rockstar Games ha confirmado que Grand Theft Auto VI llegará en otoño de 2025 exclusivamente para consolas.',
'<p>Rockstar Games ha puesto fin a años de especulación confirmando que Grand Theft Auto VI llegará a PlayStation 5 y Xbox Series X|S en otoño de 2025.</p>
<p>El juego estará ambientado en Vice City y sus alrededores, presentando el mapa más grande y detallado jamás creado por la compañía.</p>
<p>Por primera vez en la saga principal, los jugadores podrán controlar a una protagonista femenina llamada Lucia, quien junto a su compañero Jason protagonizará una historia inspirada en Bonnie y Clyde.</p>',
'gta-6.jpg', TRUE, 'published', DATE_SUB(NOW(), INTERVAL 1 DAY)),

(2, 2, 'Análisis: Final Fantasy VII Rebirth - Una obra maestra moderna', 'analisis-final-fantasy-vii-rebirth',
'Square Enix entrega la que posiblemente sea la mejor entrega de la saga en décadas.',
'<p>Final Fantasy VII Rebirth llega como la segunda parte de la trilogía remake y no decepciona en absoluto. Square Enix ha conseguido crear una experiencia que honra al original mientras ofrece algo completamente nuevo.</p>
<h3>Jugabilidad</h3>
<p>El sistema de combate híbrido ha sido refinado a la perfección, combinando acción en tiempo real con elementos estratégicos.</p>
<h3>Historia</h3>
<p>La narrativa toma riesgos audaces que sorprenderán incluso a los fans más veteranos del original.</p>
<h3>Veredicto</h3>
<p>Final Fantasy VII Rebirth es un logro monumental que establece nuevos estándares para los remakes de videojuegos. Puntuación: 9.5/10</p>',
'ff7-rebirth.jpg', FALSE, 'published', DATE_SUB(NOW(), INTERVAL 2 DAY)),

(2, 5, 'Worlds 2024: T1 se corona campeón mundial por quinta vez', 'worlds-2024-t1-campeon-mundial',
'El equipo coreano hace historia al conseguir su quinto campeonato mundial de League of Legends.',
'<p>T1 ha vuelto a escribir su nombre en la historia de los esports al proclamarse campeón del Campeonato Mundial de League of Legends 2024.</p>
<p>En una final épica contra Gen.G, el equipo liderado por Faker demostró una vez más por qué es considerado el mejor jugador de la historia del MOBA de Riot Games.</p>
<p>Con este título, T1 se convierte en el primer equipo en conseguir cinco campeonatos mundiales, consolidando su legado como la organización más exitosa en la historia de League of Legends.</p>',
'worlds-2024.jpg', FALSE, 'published', DATE_SUB(NOW(), INTERVAL 3 DAY)),

(2, 3, 'Avance: Death Stranding 2 promete revolucionar el género', 'avance-death-stranding-2',
'Hideo Kojima muestra más gameplay de su esperada secuela que llegará en 2025.',
'<p>Durante el último State of Play, Hideo Kojima ha ofrecido una extensa presentación de Death Stranding 2: On The Beach, mostrando nuevas mecánicas de juego y personajes.</p>
<p>El visionario creador japonés promete una experiencia aún más ambiciosa que su predecesora, con un enfoque renovado en la narrativa y la conexión entre jugadores.</p>
<p>Entre las novedades destacan nuevas herramientas para atravesar el terreno, un sistema de combate mejorado y la incorporación de vehículos más versátiles.</p>',
'death-stranding-2.jpg', FALSE, 'published', DATE_SUB(NOW(), INTERVAL 4 DAY));

-- Insertar FAQs
INSERT INTO faqs (question, answer, category, display_order) VALUES
('¿Cómo puedo crear una cuenta en GameZone?', 'Puedes crear una cuenta haciendo clic en "Registrarse" en la parte superior de la página y completando el formulario con tus datos.', 'Cuenta', 1),
('¿Puedo comentar en las noticias sin registrarme?', 'No, necesitas crear una cuenta gratuita para poder comentar en nuestras noticias y análisis.', 'Cuenta', 2),
('¿Cómo puedo contactar con el equipo de GameZone?', 'Puedes usar nuestro formulario de contacto o enviarnos un email a contacto@gamezone.com', 'General', 3),
('¿GameZone tiene app móvil?', 'Actualmente no disponemos de aplicación móvil, pero nuestra web está optimizada para dispositivos móviles.', 'General', 4),
('¿Puedo enviar noticias o colaborar con GameZone?', 'Sí, aceptamos colaboraciones. Envíanos tu propuesta a colaboraciones@gamezone.com', 'Colaboración', 5);

-- Insertar algunos comentarios de ejemplo
INSERT INTO comments (user_id, news_id, content) VALUES
(3, 1, '¡Por fin! Llevaba años esperando noticias sobre la nueva Nintendo. Espero que sea retrocompatible con todos los juegos de Switch.'),
(3, 2, 'GTA 6 va a ser el juego del año seguro. La espera ha sido larga pero parece que valdrá la pena.'),
(3, 3, 'FF7 Rebirth es una obra maestra. He jugado más de 100 horas y aún me quedan cosas por descubrir.');
