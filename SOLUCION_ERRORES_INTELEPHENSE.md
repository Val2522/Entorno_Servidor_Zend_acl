# Solucion De Errores Intelephense

## 1. Problema Habitual

En proyectos Laminas/Zend, Intelephense puede mostrar falsos positivos como:

- Undefined type Zend\\...
- Undefined type Laminas\\...

Estos errores suelen afectar al editor, no a la ejecucion real.

## 2. Verificar Antes De Corregir

Ejecutar validacion real:

```powershell
php -l module/Application/config/module.config.php
php verify-install.php
```

Si esto pasa, el problema es de analisis estatico del editor.

## 3. Acciones Recomendadas En VS Code

1. Command Palette -> Developer: Reload Window.
2. Command Palette -> Intelephense: Clear Cache.
3. Command Palette -> Intelephense: Index workspace.

## 4. Configuracion Del Proyecto

Revisar que existen:

- .intelephense.json
- .vscode/settings.json
- phpstan.neon.dist

## 5. Buenas Practicas

- Mantener dependencias instaladas con composer install.
- Evitar mezclar namespaces antiguos sin bridge.
- Guardar archivos PHP como UTF-8 sin BOM.

## 6. Errores Reales Que Se Confunden Con Intelephense

- Archivos con BOM al inicio (rompen namespace en runtime).
- Dependencias no instaladas en vendor/.
- Extensiones PHP faltantes.

## 7. Diagnostico Rapido

```powershell
composer install
php -v
php -m
php verify-install.php
```

Si la app arranca en http://localhost:8000/ pero el editor sigue marcando errores, es un falso positivo del analizador.
