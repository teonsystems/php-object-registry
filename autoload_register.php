<?php
/*
 * PHP Object Registry
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
namespace Teon\ObjectRegistry;



/*
 * Teon ObjectRegistry SPL autoloader
 *
 * @param string $classname The name of the class to load
 */
function autoload ($className)
{
    \Teon\Base\Loader::autoloaderTemplate($className, __NAMESPACE__, __DIR__."/src");
}



/*
 * Only PHP 5.3+ is supported by this file
 */
spl_autoload_register(__NAMESPACE__ . '\autoload', true, false);
