# Proyecto 2High2Work — Plantilla Bootstrap

Archivos creados dentro de la carpeta `proyecto/` como base para tu web, usando Bootstrap y una paleta clara con acentos azules y morados.

- `index.php` — página principal.
- `header.php`, `footer.php` — includes comunes.
- `news.php`, `portfolio.php`, `testimonials.php`, `faqs.php`, `contact.php` — páginas de ejemplo.
- `assets/css/styles.css` — variables y estilos ligeros (paleta blanca/negro, azul y morado).
- `assets/img/*` — logos e imágenes placeholder (SVG).
- `schema.sql` — esquema SQL / ER para crear las tablas principales.

Colores y tipografía:
- Fondo: blanco (`--bg`).
- Texto principal: negro (`--text`).
- Acentos: `--primary-blue` (azul suave) y `--accent-purple` (morado suave).

Cómo usar:
1. Copia/ajusta los placeholders `2High2Work`, `TAGLINE_PLACEHOLDER`, y los datos de contacto en `header.php` y `footer.php`.
2. Si vas a usar la base de datos, importa `schema.sql` en tu MySQL/MariaDB y ajusta la conexión en tu `config.php` existente.
3. Reemplaza las imágenes SVG en `assets/img/` por tus gráficos.

Notas sobre el esquema ER:
- `users` contiene usuarios y roles básicos.
- `news` y `portfolio` son entidades principales con relación a `users` y `tags`.
- `comments` soporta comentarios para `news` y `portfolio` (campos `news_id` / `portfolio_id`).

Si quieres que adapte las páginas para consumir la base de datos (PDO o mysqli) lo hago a continuación: ¿quieres integración con la base de datos ahora? 
