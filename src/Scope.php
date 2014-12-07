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
 * Class definition
 */
class     Scope
{



    /*
     * Scope identifier (usually object class name)
     *
     * @type   string
     */
    protected $scopeId;



    /*
     * Object storage
     *
     * @type   array
     */
    protected $objectStorage = array();



    /*
     * Constructor
     *
     * @param    string   Scope ID (usually object class)
     * @return   void
     */
    public function __construct ($scopeId)
    {
        $this->scopeId = $scopeId;
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
        $objectId=null
    ) {
        $objectId = $this->_determineObjectId($object, $objectId);

        // Store object if not yet stored
        if (!isset($this->objectStorage[$objectId])) {
            $this->objectStorage[$objectId] = $object;
            return;
        }

        // Object with this key is already in scope - compare if the same, throw if not
        if ($object !== $this->objectStorage[$objectId]) {
            throw new Exception("Scope '$this->scopeId' already contains this object ID instance ($objectId), but it is not the same instance as the one passed as argument to this method");
        }

        // Object is already stored, just return
        return;
    }



    /*
     * Find object by scope ID and object ID
     *
     * Look for given object in internal storage
     *
     * @param    string          Key of the object that we are looking for
     *
     * @return   object|null   Return object if found, null if not
     */
    public function find ($objectId)
    {
        // Object ID must be string
        $objectId = (string) $objectId;

        // Return object reference if exists?
        if (isset($this->objectStorage[$objectId])) {
            return $this->objectStorage[$objectId];
        }

        // Object not cached
        return;
    }



    /*
     * Get object by scope and object ID - throws error if not found
     *
     * @param    string          Key of the object that we are looking for
     *
     * @return   object|null    Return object if found, null if not
     */
    public function get ($objectId)
    {
        $object = $this->find($objectId);
        if ($object === false) {
            throw new Exception("Object with id '$objectId' not found in scope '$this->scopeId'");
        }

        return $object;
    }



    /*
     * Check if object is stored, by scope and object ID
     *
     * @param    string          Key of the object that we are looking for
     *
     * @return   boolean         Return true if exists, false if not
     */
    public function exists ($objectId)
    {
        $object = $this->find($objectId);

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
     * @return   void
     */
    public function remove ($object=null, $objectId=null)
    {
        $objectId = $this->_determineObjectId($object, $objectId);
        if ($this->exists($objectId)) {
            unset($this->objectStorage[$objectId]);
        }
    }



    /*
     * Get scope ID
     *
     * @return   string   Scope ID
     */
    public function getScopeId ()
    {
        return $this->scopeId;
    }



    /*
     * Determine object ID
     *
     * @param    mixed    Object to operate on
     * @param    string   Optional object ID to use
     * @return   int|string
     */
    protected function _determineObjectId (
        $object,
        $objectId=null
    ) {
        // Only objects are suppoted at this point
        if (!is_object($object)) {
            throw new Exception("Only object storage is currently supported");
        }

        // Object ID is required
        if (null === $objectId) {
            if (!is_callable(array($object, 'getId'))) {
                throw new Exception("No ID provided, and object of class ". get_class($object) ." does not provide getId() method");
            }
            $objectId = $object->getId();
        }
        $objectId = (string) $objectId;

        return $objectId;
    }



    /*
     * Dump internal data array
     *
     * @return   void
     */
    public function dump ()
    {
        print_r($this->objectStorage);
    }
}
