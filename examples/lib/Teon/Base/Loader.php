<?php
/*
 * Teon PHP Base
 *
 * Copyright (C) 2012-2014 Teon d.o.o.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */



/*
 * This software is namespaced
 */
namespace Teon\Base;



/*
 * Class: PHP Loader (autoloader)
 */
class Loader
{



    /*
     * METHOD: autoloaderTemplate
     *
     * Template autoloader to be used by actual autoloader functions.
     * Removes namespace, converts remaining class name to path and tries said
     * path for file existence.
     *
     * Example usage:
     *
     *     namespace My\Module;
     *
     *     function autoload ($className) {
     *         \Teon\Base\Loader::autoloaderTemplate($className, __NAMESPACE__, __DIR__."/src");
     *     }
     *
     *     spl_autoload_register(__NAMESPACE__ . '\autoload', true, false);
     */
    static function autoloaderTemplate ($className, $nameSpace, $srcDir)
    {
        // Only process given namespace
        $className     = ltrim($className, "\\");
        $regex         = '/^'. str_replace("\\", "\\\\?", $nameSpace) ."\\\\?/";
        $classNameNoNs = preg_replace($regex, '', $className);
        if (NULL === $classNameNoNs) {
            throw new \Exception("$nameSpace autoload error: ". preg_last_error());
        }
        if ($className == $classNameNoNs) {
            return;
        }

        // Generate file name and final path
        $fileNameNoNs = str_replace("\\", DIRECTORY_SEPARATOR, $classNameNoNs);
        $filePath     = $srcDir . DIRECTORY_SEPARATOR . $fileNameNoNs .'.php';

        // Include if file exists
        if (is_readable($filePath)) {
            require $filePath;
        }
    }
}
