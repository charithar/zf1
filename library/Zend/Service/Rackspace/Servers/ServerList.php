<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Service_Rackspace
 * @subpackage Servers
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

//require_once 'Zend/Service/Rackspace/Servers.php';
//require_once 'Zend/Service/Rackspace/Servers/Server.php';

/**
 * List of servers of Rackspace
 *
 * @uses       ArrayAccess
 * @uses       Countable
 * @uses       Iterator
 * @uses       OutOfBoundsException
 * @uses       Zend_Service_Rackspace_Servers
 * @category   Zend
 * @package    Zend_Service_Rackspace
 * @subpackage Servers
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Service_Rackspace_Servers_ServerList implements Countable, Iterator, ArrayAccess
{
    /**
     * @var array of Zend_Service_Rackspace_Servers_Server
     */
    protected $servers = array();
    /**
     * @var int Iterator key
     */
    protected $iteratorKey = 0;
    /**
     * @var Zend_Service_Rackspace_Servers
     */
    protected $service;
    /**
     * Construct
     *
     * @param  Zend_Service_Rackspace_Servers $service
     * @param  array $list
     * @return void
     */
    public function __construct($service,$list = array())
    {
        if (!($service instanceof Zend_Service_Rackspace_Servers) || !is_array($list)) {
            //require_once 'Zend/Service/Rackspace/Servers/Exception.php';
            throw new Zend_Service_Rackspace_Servers_Exception("You must pass a Zend_Service_Rackspace_Servers object and an array");
        }
        $this->service= $service;
        $this->constructFromArray($list);
    }
    /**
     * Transforms the array to array of Server
     *
     * @param  array $list
     * @return void
     */
    private function constructFromArray(array $list)
    {
        foreach ($list as $server) {
            $this->addServer(new Zend_Service_Rackspace_Servers_Server($this->service,$server));
        }
    }
    /**
     * Add a server
     *
     * @param  Zend_Service_Rackspace_Servers_Server $server
     * @return Zend_Service_Rackspace_Servers_ServerList
     */
    protected function addServer (Zend_Service_Rackspace_Servers_Server $server)
    {
        $this->servers[] = $server;
        return $this;
    }
    /**
     * To Array
     * 
     * @return array 
     */
    public function toArray()
    {
        $array= array();
        foreach ($this->servers as $server) {
            $array[]= $server->toArray();
        }
        return $array;
    }
    /**
     * Return number of servers
     *
     * Implement Countable::count()
     *
     * @return int
     */
    #[\ReturnTypeWillChange]
    public function count()
    {
        return count($this->servers);
    }
    /**
     * Return the current element
     *
     * Implement Iterator::current()
     *
     * @return Zend_Service_Rackspace_Servers_Server
     */
    #[\ReturnTypeWillChange]
    public function current()
    {
        return $this->servers[$this->iteratorKey];
    }
    /**
     * Return the key of the current element
     *
     * Implement Iterator::key()
     *
     * @return int
     */
    #[\ReturnTypeWillChange]
    public function key()
    {
        return $this->iteratorKey;
    }
    /**
     * Move forward to next element
     *
     * Implement Iterator::next()
     *
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function next()
    {
        $this->iteratorKey += 1;
    }
    /**
     * Rewind the Iterator to the first element
     *
     * Implement Iterator::rewind()
     *
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function rewind()
    {
        $this->iteratorKey = 0;
    }
    /**
     * Check if there is a current element after calls to rewind() or next()
     *
     * Implement Iterator::valid()
     *
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function valid()
    {
        $numItems = $this->count();
        if ($numItems > 0 && $this->iteratorKey < $numItems) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * Whether the offset exists
     *
     * Implement ArrayAccess::offsetExists()
     *
     * @param   int     $offset
     * @return  bool
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return ($offset < $this->count());
    }
    /**
     * Return value at given offset
     *
     * Implement ArrayAccess::offsetGet()
     *
     * @param   int     $offset
     * @throws  Zend_Service_Rackspace_Servers_Exception
     * @return  Zend_Service_Rackspace_Servers_Server
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->servers[$offset];
        } else {
            //require_once 'Zend/Service/Rackspace/Servers/Exception.php';
            throw new Zend_Service_Rackspace_Servers_Exception('Illegal index');
        }
    }

    /**
     * Throws exception because all values are read-only
     *
     * Implement ArrayAccess::offsetSet()
     *
     * @param   int     $offset
     * @param   string  $value
     * @throws  Zend_Service_Rackspace_Servers_Exception
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        //require_once 'Zend/Service/Rackspace/Servers/Exception.php';
        throw new Zend_Service_Rackspace_Servers_Exception('You are trying to set read-only property');
    }

    /**
     * Throws exception because all values are read-only
     *
     * Implement ArrayAccess::offsetUnset()
     *
     * @param   int     $offset
     * @throws  Zend_Service_Rackspace_Servers_Exception
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        //require_once 'Zend/Service/Rackspace/Servers/Exception.php';
        throw new Zend_Service_Rackspace_Servers_Exception('You are trying to unset read-only property');
    }
}
