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
class     ObjectRegistry
extends   \Teon\Base\Singleton\AbstractSingleton
{



    /*
     * Scope storage
     *
     * @type   array
     */
    protected $scopeStorage = array();



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
            throw new Exception("Only object storage is currently supported");
        }

        // Get scope handler
        $Scope = $this->_getScope($this->_determineScopeId($object, $scopeId));

        // Store in the scope then
        return $Scope->store($object, $objectId);
    }



    /*
     * Find object by scope ID and object ID
     *
     * Look for object in given scope
     *
     * @param    string|object   Scope ID: string or object whose class is used as scope ID
     * @param    string          Key/ID of the object that we are looking for
     *
     * @return   object|null     Return object if found, null if not
     */
    public function find ($scopeIdOrSameObject, $objectId)
    {
        $Scope = $this->_getScope($this->_determineScopeId(null, $scopeIdOrSameObject));

        return $Scope->find($objectId);
    }



    /*
     * Get object by scope and object ID - throws error if not found
     *
     * @param    string|object   Scope ID: string or object whose class is used as scope ID
     * @param    string          Key/ID of the object that we are looking for
     *
     * @return   object|null     Return object if found, null if not
     */
    public function get ($scopeIdOrSameObject, $objectId)
    {
        $Scope = $this->_getScope($this->_determineScopeId(null, $scopeIdOrSameObject));

        return $Scope->get($objectId);
    }



    /*
     * Check if object is stored, by scope and object ID
     *
     * @param    string|object   Scope ID: string or object whose class is used as scope ID
     * @param    string          Key of the object that we are looking for
     *
     * @return   boolean         Return true if exists, false if not
     */
    public function exists ($scopeIdOrSameObject, $objectId)
    {
        if (is_object($scopeIdOrSameObject)) {
            $Scope = $this->_getScope($this->_determineScopeId($scopeIdOrSameObject));
        } else {
            $Scope = $this->_getScope($this->_determineScopeId(null, $scopeIdOrSameObject));
        }

        $Object = $Scope->find($objectId);
        if (NULL === $Object) {
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
        $Scope  = $this->_getScope($this->_determineScopeId($object, $scopeId));
        $Scope->remove($object, $objectId);
    }



    /*
     * Dump internal data array
     *
     * @return   void
     */
    public function dump ()
    {
        print_r($this->scopeStorage);
    }



    /*
     * Get scope subsystem
     *
     * @param    string|object   Scope ID or object to get scope for
     * @return   Scope           Scope object
     */
    protected function getScope ($scopeIdOrSameObject)
    {
        return $this->_getScope($this->_determineScopeId(null, $scopeIdOrSameObject));
    }



    /*
     * Figure out proper scope ID
     *
     * @param    mixed    Object to operate on
     * @param    string   Optional scope ID; if omitted, get_class($object) is used instead
     * @return   string   Scope ID
     */
    protected function _determineScopeId ($object=null, $scopeId=null)
    {
        // Sanity check
        if ((NULL === $object) && (NULL === $scopeId)) {
            throw new Exception("Unable to determine requested scope");
        }

        // Get scope ID if not passed as argument
        if (null === $scopeId) {
            $scopeId = get_class($object);
        } else {
            if (!is_string($scopeId)) {
                throw new Exception("Scope ID can only be string for now");
            }
        }

        // Return it
        return $scopeId;
    }



    /*
     * Get scope subsystem
     *
     * @param    string   Scope ID to get Scope for
     * @return   Scope    Scope object
     */
    protected function _getScope ($scopeId)
    {
        // Create Scope for this scope ID if it does not exist yet
        if (!isset($this->scopeStorage[$scopeId])) {
            $this->scopeStorage[$scopeId] = new Scope($scopeId);
        }
        return $this->scopeStorage[$scopeId];
    }
}
