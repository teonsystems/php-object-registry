<?php



/*
 * Teon ObjectRegistry SPL autoloader.
 *
 * @param string $classname The name of the class to load
 */
function Teon_ObjectRegistry_Autoload ($className)
{
    // Copied from PSR-0 example implementation
    $className = ltrim($className, "\\");
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, "\\")) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace("\\", DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    $filePath = __DIR__ . DIRECTORY_SEPARATOR . 'library/'. $fileName;
    if (is_readable($filePath)) {
        require $filePath;
    }
}



/*
 * Only PHP 5.3+ is supported by this file
 */
spl_autoload_register('Teon_ObjectRegistry_Autoload', true, false);
