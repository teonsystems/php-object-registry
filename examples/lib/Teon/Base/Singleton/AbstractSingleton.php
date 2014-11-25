<?php
/*
 * PHP Abstract Singleton
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
namespace Teon\Base\Singleton;



/*
 * ABSTRACT CLASS: Singleton
 *
 * Provides singleton functionality to it's descendants
 *
 * Usage:
 *
 *     Your class should just extend this abstract class. After that,
 *     doing "new YourClass()" will throw and exception which points
 *     you towards static method YourClass::getInstance(). This method
 *     always returns the same YourClass object instance.
 */
abstract class AbstractSingleton
{



    /*
     * getInstance fuse value to be used when factoring singleton object
     *
     * @type   string
     */
    protected static $_getInstanceFuseValue = 'calledFromgetInstanceMethod';



    /*
     * getInstance class name to instantiate
     *
     * @type   string
     */
    protected static $_singletonClassName = false;



    /*
     * Get singleton instance
     *
     * @return   Some\Class   Object singleton instance
     */
    public static function getInstance ()
    {
        static $instance = false;

        // If already instantiated, use that
        if (false !== $instance) {
            return $instance;
        }

        // Otherwise create new instance
        // Get class name to create: Use late static binding here,
        // if method is overriden by derived classes.
        $singletonClassName = static::_getInstanceClassName();

        // Now create that singleton instance
        $instance = new $singletonClassName (self::_getInstanceFuseValue());

        return $instance;
    }



    /*
     * Returns singleton class name to use when creating new instance
     *
     * When called by derived classes, this method return derived class name
     * instead of Teon\Base\Singleton\AbstractSingleton.
     *
     * If required, this method might be overidden in derived class.
     *
     * @return   string    Your\Class\Name
     */
    protected static function _getInstanceClassName ()
    {
        /*
         * Do not use __CLASS__ here as it returns this (abstract) class
         * name instead of derived class name (YourClass, if
         * "class YourClass extends \Teon\Base\AbstractSingleton"
         * Same applies for get_class().
         * Using get_called_class is the only correct implementation here.
         */
        return get_called_class();
    }



    /*
     * Returns singleton creation fuse value
     *
     * This value is used to check if new My\Singleton() is called
     * from getInstance() and not from somewhere else.
     *
     * @return   Some\Class   Object singleton instance
     */
    protected static function _getInstanceFuseValue ()
    {
        return self::$_getInstanceFuseValue;
    }



    /*
     * Constructor
     *
     * Check if correct getInstance fuse value was provided, to prevent accidental
     * creation of multiple object instances of this class.
     *
     * If you extend this class and create custom constructor, make sure you call
     * parent constructor, like this:
     *
     *     parent::__construct(self::_getInstanceFuseValue())
     *
     * @param    string   getInstance() fuse value
     *
     * @return   void
     */
    public function __construct ($getInstanceFuse='')
    {
        // Protect against accidental multiple instantiations
        if ($getInstanceFuse != self::_getInstanceFuseValue()) {
            throw new \Exception(static::_getInstanceClassName() ." is a singleton. Use ::getInstance() method to retrieve an instance");
        }
    }
}
