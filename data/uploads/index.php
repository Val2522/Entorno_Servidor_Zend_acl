<?php
// Archivo de seguridad para evitar listado de directorios
// Redirigir al inicio si alguien intenta acceder directamente
header('Location: /');
exit;
