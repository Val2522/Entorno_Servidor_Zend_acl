#!/usr/bin/env php
<?php
/**
 * Script de Verificación de la Aplicación
 * 
 * Verifica que todos los archivos necesarios existan
 * y que la aplicación esté correctamente instalada
 */

echo "\n╔════════════════════════════════════════════════════════════╗\n";
echo "║            🔍 VERIFICACIÓN DE INSTALACIÓN 🔍               ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

$errors = [];
$warnings = [];
$success = [];

// Directorio raíz
$root = __DIR__;

// Lista de archivos/directorios que deben existir
$required_paths = [
    // Directorios
    'public' => 'dir',
    'config' => 'dir',
    'module' => 'dir',
    'module/Application' => 'dir',
    'module/Application/src/Application' => 'dir',
    'module/Application/src/Application/Controller' => 'dir',
    'module/Application/src/Application/Model' => 'dir',
    'module/Application/src/Application/Form' => 'dir',
    'module/Application/view/application' => 'dir',
    'data' => 'dir',
    'data/uploads' => 'dir',
    
    // Archivos principales
    'public/index.php' => 'file',
    'config/application.config.php' => 'file',
    'module/Application/Module.php' => 'file',
    'module/Application/config/module.config.php' => 'file',
    'install.php' => 'file',
    'composer.json' => 'file',
    '.htaccess' => 'file',
    'README.md' => 'file',
    'QUICKSTART.md' => 'file',
    'ARQUITECTURA.md' => 'file',
];

// Verificar rutas
echo "📁 Verificando estructura de directorios:\n";
echo "────────────────────────────────────────────────────────────\n";

foreach ($required_paths as $path => $type) {
    $full_path = $root . '/' . $path;
    $exists = false;
    
    if ($type === 'dir') {
        $exists = is_dir($full_path);
        $icon = is_dir($full_path) ? '✓' : '✗';
    } else {
        $exists = file_exists($full_path);
        $icon = file_exists($full_path) ? '✓' : '✗';
    }
    
    if ($exists) {
        $success[] = $path;
        echo "  $icon $path\n";
    } else {
        $errors[] = "Falta: $path";
        echo "  ✗ $path (FALTA)\n";
    }
}

// Verificar permisos
echo "\n📋 Verificando permisos:\n";
echo "────────────────────────────────────────────────────────────\n";

$writable_dirs = [
    'data/uploads',
];

foreach ($writable_dirs as $dir) {
    $full_dir = $root . '/' . $dir;
    if (is_writable($full_dir)) {
        echo "  ✓ $dir (lectura/escritura)\n";
    } else {
        echo "  ⚠ $dir (sin permisos de escritura)\n";
        $warnings[] = "Sin permisos en: $dir. Ejecuta: chmod 777 $dir";
    }
}

// Verificar PHP
echo "\n⚙️  Verificando entorno PHP:\n";
echo "────────────────────────────────────────────────────────────\n";

$php_version = phpversion();
$required_version = '7.2';

if (version_compare($php_version, $required_version, '>=')) {
    echo "  ✓ PHP $php_version (OK)\n";
} else {
    echo "  ✗ PHP $php_version (Se requiere $required_version+)\n";
    $errors[] = "Versión de PHP insuficiente";
}

// Extensiones requeridas
$required_extensions = ['pdo', 'pdo_sqlite', 'date', 'json', 'filter'];
echo "\n📦 Extensiones de PHP:\n";

foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "  ✓ $ext\n";
    } else {
        echo "  ✗ $ext (FALTA)\n";
        $errors[] = "Falta extensión PHP: $ext";
    }
}

// Verificar base de datos
echo "\n💾 Verificando base de datos:\n";
echo "────────────────────────────────────────────────────────────\n";

$db_path = $root . '/data/dbase.db';
if (file_exists($db_path)) {
    echo "  ✓ Base de datos existe\n";
    $size = filesize($db_path);
    $size_kb = round($size / 1024, 2);
    echo "    Tamaño: $size_kb KB\n";
} else {
    echo "  ⚠ Base de datos no existe (se creará al ejecutar install.php)\n";
    $warnings[] = "Debes ejecutar: php install.php";
}

// Verificar imágenes
echo "\n🖼️  Verificando imágenes:\n";
echo "────────────────────────────────────────────────────────────\n";

$images_dir = $root . '/data/uploads';
if (is_dir($images_dir)) {
    $images = glob($images_dir . '/*.{jpeg,jpg,png}', GLOB_BRACE);
    echo "  Imágenes encontradas: " . count($images) . "\n";
    foreach ($images as $img) {
        echo "    ✓ " . basename($img) . "\n";
    }
    
    if (count($images) === 0) {
        $warnings[] = "No hay imágenes en data/uploads/";
    }
}

// Resumen
echo "\n╔════════════════════════════════════════════════════════════╗\n";
echo "║                      📊 RESUMEN 📊                         ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

echo "✓ Archivos/Directorios correctos: " . count($success) . "\n";
echo "⚠ Advertencias: " . count($warnings) . "\n";
echo "✗ Errores: " . count($errors) . "\n\n";

if (count($warnings) > 0) {
    echo "⚠️  ADVERTENCIAS:\n";
    echo "────────────────────────────────────────────────────────────\n";
    foreach ($warnings as $warning) {
        echo "  • $warning\n";
    }
    echo "\n";
}

if (count($errors) > 0) {
    echo "❌ ERRORES ENCONTRADOS:\n";
    echo "────────────────────────────────────────────────────────────\n";
    foreach ($errors as $error) {
        echo "  • $error\n";
    }
    echo "\n";
    
    echo "📝 SOLUCIÓN:\n";
    echo "  1. Verifica que has extraído todos los archivos\n";
    echo "  2. Ejecuta: php install.php\n";
    echo "  3. Verifica permisos: chmod -R 755 data/\n";
    echo "\n";
    
    exit(1);
} else {
    echo "✅ ¡INSTALACIÓN CORRECTA!\n\n";
    
    echo "🚀 PRÓXIMOS PASOS:\n";
    echo "────────────────────────────────────────────────────────────\n";
    echo "  1. Ejecuta la instalación:\n";
    echo "     php install.php\n\n";
    echo "  2. Inicia el servidor:\n";
    echo "     php -S localhost:8000 -t public/\n\n";
    echo "  3. Accede a:\n";
    echo "     http://localhost:8000/\n\n";
    echo "  4. Login con:\n";
    echo "     Usuario: admin\n";
    echo "     Contraseña: admin123\n";
    echo "\n";
    
    exit(0);
}
