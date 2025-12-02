# GameZone — Portal de Noticias de Videojuegos

![GameZone Logo](assets/img/logo.svg)

Portal web completo de noticias de videojuegos desarrollado con PHP, MySQL y Bootstrap 5. Incluye sistema de autenticación, roles de usuario, panel de administración y CRUD completo.

## 🎮 Características

### Funcionalidades Públicas
- **Inicio**: Últimas noticias destacadas y recientes
- **Noticias**: Listado paginado con filtros por plataforma
- **Reviews**: Análisis de juegos con puntuaciones
- **Juegos**: Catálogo de videojuegos
- **FAQs**: Preguntas frecuentes
- **Contacto**: Formulario de contacto
- **Comunidad**: Comentarios y testimonios de usuarios

### Sistema de Autenticación
- Registro de usuarios con validación
- Login/Logout con sesiones seguras
- Protección CSRF en formularios
- Hashing de contraseñas con bcrypt
- Perfil de usuario editable

### Roles de Usuario
- **user**: Usuario básico, puede comentar
- **editor**: Puede crear y editar noticias
- **admin**: Acceso completo al panel de administración

### Panel de Administración
- Dashboard con estadísticas
- CRUD de noticias (crear, editar, eliminar)
- Gestión de usuarios (solo admin)
- Gestión de estados (borrador, publicado, archivado)

## 📁 Estructura del Proyecto

```
proyecto/
├── admin/                  # Panel de administración
│   ├── index.php           # Dashboard
│   ├── news.php            # Listado de noticias
│   ├── news-edit.php       # Crear/editar noticia
│   ├── news-delete.php     # Eliminar noticia
│   └── users.php           # Gestión de usuarios
├── assets/
│   ├── css/
│   │   └── styles.css      # Estilos con tema gaming
│   └── img/                # Imágenes y placeholders SVG
├── config.php              # Configuración y funciones helper
├── schema.sql              # Esquema de base de datos
├── index.php               # Página principal
├── news.php                # Listado de noticias
├── news-detail.php         # Detalle de noticia
├── reviews.php             # Listado de reviews
├── portfolio.php           # Catálogo de juegos
├── testimonials.php        # Comunidad
├── faqs.php                # Preguntas frecuentes
├── contact.php             # Formulario de contacto
├── login.php               # Inicio de sesión
├── register.php            # Registro de usuarios
├── logout.php              # Cerrar sesión
├── profile.php             # Perfil de usuario
├── header.php              # Cabecera común
└── footer.php              # Pie de página común
```

## 🗃️ Base de Datos

### Tablas
- `users`: Usuarios con roles (user, editor, admin)
- `news`: Noticias con estado y autor
- `platforms`: Plataformas de videojuegos (PS5, Xbox, PC, Switch...)
- `games`: Catálogo de juegos
- `reviews`: Análisis de juegos con puntuaciones
- `comments`: Comentarios en noticias
- `tags`: Etiquetas para categorizar
- `news_tags`: Relación noticias-etiquetas
- `news_platforms`: Relación noticias-plataformas
- `faqs`: Preguntas frecuentes
- `contact_messages`: Mensajes del formulario de contacto

### Instalación de la BD
```sql
mysql -u root -p < schema.sql
```

O importa `schema.sql` desde phpMyAdmin.

## ⚙️ Configuración

Edita `config.php` con tus credenciales:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');
define('DB_NAME', 'gamezone_db');
```

## 🚀 Instalación

1. Clona o copia los archivos al servidor web
2. Importa `schema.sql` en MySQL/MariaDB
3. Configura las credenciales en `config.php`
4. Accede a `index.php` desde el navegador

### Usuario Admin por defecto
- **Usuario**: admin
- **Contraseña**: admin123

## 🎨 Diseño

### Paleta de Colores (Tema Gaming Oscuro)
- **Fondo principal**: `#0f0f23` (negro azulado)
- **Fondo secundario**: `#1a1a2e` (negro suave)
- **Primary (Neón púrpura)**: `#673ab7`
- **Secondary (Neón cyan)**: `#00bcd4`
- **Acentos**: Verde éxito, naranja advertencia

### Tipografía
- Familia: System fonts (sin dependencias externas)
- Tamaño base: 16px

## 🔒 Seguridad

- Contraseñas hasheadas con `PASSWORD_BCRYPT`
- Protección CSRF en todos los formularios
- Escape de output con `htmlspecialchars()`
- Prepared statements para todas las queries SQL
- Validación de input en servidor
- Cookies seguras (httponly, secure en HTTPS)

## 📱 Responsive

Diseño adaptativo para:
- Desktop (>992px)
- Tablet (768px - 991px)
- Mobile (<768px)

## 🛠️ Tecnologías

- **Backend**: PHP 7.4+
- **Base de datos**: MySQL 5.7+ / MariaDB 10.3+
- **Frontend**: Bootstrap 5.3.2
- **Iconos**: Bootstrap Icons
- **JavaScript**: Vanilla JS (mínimo)

## 📄 Licencia

Este proyecto es parte del módulo M7 del ciclo DAW2.
