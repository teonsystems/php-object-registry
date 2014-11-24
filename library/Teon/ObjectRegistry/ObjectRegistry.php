<?php
/*
 * PHP Object Registry
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
 * This software is namespaced, to avoid class name collisions
 */
namespace Teon\ObjectRegistry;



/*
 * CLASS: ObjectRegistry (singleton)
 *
 * Manages storing and retrieving already-created objects
 */
class ObjectRegistry
{



    /*
     * Factory fuse value to be used when factoring singleton object
     *
     * @type   string
     */
    protected static $_factoryFuseValue = 'calledFromFactoryMethod';



    /*
     * Object storage data array
     *
     * This gets to be multidimensional array, like this:
     *   $_data[$registrId][$objectId] = $object
     *
     * @type   array
     */
    protected $_data = array();



    /*
     * Creates ObjectRegistry instance
     *
     * @return   Teon\ObjectRegistry\ObjecRegistry   Object registry instance
     */
    public static function factory ()
    {
        static $instance = false;

        if (false === $instance) {
            $instance = new self(self::$_factoryFuseValue);
        }

        return $instance;
    }



    /*
     * Constructor
     *
     * Check if correct factory fuse value was provided, to prevent accidental
     * creation of multiple object instances of this class.
     *
     * @param    string   Factory fuse value
     * @return   void
     */
    public function __construct ($factoryFuse='')
    {
        // Protect
        if ($factoryFuse != self::$_factoryFuseValue) {
            throw new \Exception(__CLASS__ ." is a singleton. Use ::factory() method to retrieve an instance");
        }
    }



    /*
     * Store given object for later retrieval
     *
     * Store objectGenerates SEPA RF reference, returns raw output, not space-separated 4-char groups string
     *
     * @param    mixed    Object to operate on
     * @param    string   Optional key of the given object; if omitted, $object->getId() is consulted instead
     * @param    string   Optional scope ID; if omitted, get_class($object) is used instead
     * @return   void
     */
    public function store (
        $object,
        $objectId=null,
        $scopeId=null
    ) {
        // Only objects are suppoted at this point
        if (!is_object($object)) {
            throw new \Exception(__CLASS__ ." error: Only object storage is currently supported");
        }

        // Object ID is required
        if (null === $objectId) {
            if (!is_callable(array($object, 'getId'))) {
                throw new \Exception(__CLASS__ ." error: No ID provided, and object of class ". get_class($object) ." does not provide getId() method");
            }
            $objectId = $object->getId();
        }
        $objectId = (string) $objectId;

        // Get scope ID if not passed as argument
        if (null === $scopeId) {
            $scopeId = get_class($object);
        } else {
            if (!is_string($scopeId)) {
                throw new \Exception(__CLASS__ ." error: Registry ID can only be string");
            }
        }

        // Create scopeID array if it does not exist yet
        if (!isset($this->_data[$scopeId])) {
            $this->_data[$scopeId] = array();
        }

        // Store object if not yet in given scope
        if (!isset($this->_data[$scopeId][$objectId])) {
            $this->_data[$scopeId][$objectId] = $object;
            return;
        }

        // Object with this key is already in scope - compare if the same, throw if not
        if ($object !== $this->_data[$scopeId][$objectId]) {
            throw new \Exception(__CLASS__ ." error: Registry '$scopeId' already contains this object ID instance ($objectId), but it is not the same instance as the one passed as argument to this method");
        }

        // Object is already stored, just return
    }



    /*
     * Find object by scope ID and object ID
     *
     * Look for object in internal storage
     *
     * @param    string|object   Registry ID: string or object whose class is used as scope ID
     * @param    string          Key of the object that we are looking for
     *
     * @return   object|false    Return object if found, false if not
     */
    public function find (
        $scopeId,
        $objectId
    ) {
        // If scope ID is passed as object of the same kind (for example User with different ID)
        if (is_object($scopeId)) {
            $scopeId = get_class($scopeId);
        }

        // Object ID must be string
        $objectId = (string) $objectId;

        // Check if given scope exists
        if (!isset($this->_data[$scopeId])) {
            return false;
        }

        // Return object reference if exists?
        if (isset($this->_data[$scopeId][$objectId])) {
            return $this->_data[$scopeId][$objectId];
        }

        // Object not cached
        return false;
    }



    /*
     * Get object by scope and object ID - throws error if not found
     *
     * @param    string|object   Registry ID: string or object whose class is used as scope ID
     * @param    string          Key of the object that we are looking for
     *
     * @return   object|false    Return object if found, false if not
     */
    public function get (
        $scopeId,
        $objectId
    ) {
        $object = $this->find($scopeId, $objectId);
        if ($object === false) {
            throw new \Exception(__CLASS__ ." error: Object with id '$objectId' not found in scope '$scopeId'");
        }

        return $object;
    }



    /*
     * Check if object is stored, by scope and object ID
     *
     * @param    string|object   Scope ID: string or object whose class is used as scope ID
     * @param    string          Key of the object that we are looking for
     *
     * @return   boolean         Return true if exists, false if not
     */
    public function exists (
        $scopeId,
        $objectId
    ) {
        $object = $this->find($scopeId, $objectId);

        if ($object === false) {
            return false;
        } else {
            return true;
        }
    }



    /*
     * Remove object from registry
     *
     * @param    mixed    Optional object to operate on
     * @param    string   Optional key of the given object; if omitted, $object->getId() is consulted instead
     * @param    string   Optional scope ID; if omitted, get_class($object) is used instead
     * @return   void
     */
    public function remove (
        $object=null,
        $objectId=null,
        $scopeId=null
    ) {
        throw new \Exception(__CLASS__ ." error: Object removal not yet implemented");
    }



    /*
     * Dump internal data array
     *
     * @return   void
     */
    public function dump ()
    {
        print_r($this->_data);
    }
}
