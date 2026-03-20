<?php
/**
 * Script de Instalación de la Aplicación
 * 
 * Ejecuta este script una vez para inicializar la base de datos
 * y cargar los datos de demostración
 * 
 * Uso: php install.php
 */

// Iniciar sesiones
session_start();

// Establecer directorio raíz
define('ROOT_DIR', __DIR__);

// Cargar clases
require ROOT_DIR . '/module/Application/src/Application/Model/Database.php';

use Application\Model\Database;

// Mostrar encabezado HTML si se ejecuta desde navegador
if (php_sapi_name() !== 'cli') {
    echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Instalación - Tienda de Videojuegos Retro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; }
        .card { max-width: 600px; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-body p-5">';
}

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║        🎮 INSTALACIÓN - Tienda de Videojuegos Retro 🎮     ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

try {
    echo "📦 Inicializando base de datos...\n";
    Database::inicializarBase();
    echo "✓ Base de datos creada correctamente\n";
    
    echo "\n📝 Cargando datos de demostración...\n";
    Database::cargarDatos();
    echo "✓ Datos cargados correctamente\n";
    
    echo "\n";
    echo "╔════════════════════════════════════════════════════════════╗\n";
    echo "║              ✓ ¡INSTALACIÓN COMPLETADA! ✓                  ║\n";
    echo "╚════════════════════════════════════════════════════════════╝\n";
    echo "\n";
    
    echo "📊 DATOS DE DEMOSTRACIÓN CARGADOS:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "  • 4 Categorías: Deportes, Arcade, Carreras, Acción\n";
    echo "  • 8 Videojuegos con imágenes y descripciones\n";
    echo "  • 1 Usuario Admin para pruebas\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    echo "\n🔐 CREDENCIALES DE ADMINISTRADOR:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "  Usuario: admin\n";
    echo "  Contraseña: admin123\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    echo "\n🌐 ACCESO A LA APLICACIÓN:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "  URL Local: http://localhost/Actividad_UT6_ZEND_acl/\n";
    echo "  PHP Built-in: php -S localhost:8000 -t public/\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    echo "\n✨ ¡La aplicación está lista para usar!\n\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n\n";
}

// Cerrar HTML si se ejecuta desde navegador
if (php_sapi_name() !== 'cli') {
    echo '        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>';
}
