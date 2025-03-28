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
 * @package    Zend_Pdf
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */


/** Internally used classes */
//require_once 'Zend/Pdf/Element/Null.php';


/** Zend_Pdf_Element */
//require_once 'Zend/Pdf/Element.php';

/**
 * PDF file 'reference' element implementation
 *
 * @category   Zend
 * @package    Zend_Pdf
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Pdf_Element_Reference extends Zend_Pdf_Element
{
    /**
     * Object value
     * The reference to the object
     *
     * @var mixed
     */
    private $_ref;

    /**
     * Object number within PDF file
     *
     * @var integer
     */
    private $_objNum;

    /**
     * Generation number
     *
     * @var integer
     */
    private $_genNum;

    /**
     * Reference context
     *
     * @var Zend_Pdf_Element_Reference_Context
     */
    private $_context;


    /**
     * Reference to the factory.
     *
     * It's the same as referenced object factory, but we save it here to avoid
     * unnecessary dereferencing, whech can produce cascade dereferencing and parsing.
     * The same for duplication of getFactory() function. It can be processed by __call()
     * method, but we catch it here.
     *
     * @var Zend_Pdf_ElementFactory
     */
    private $_factory;

    /**
     * Object constructor:
     *
     * @param integer $objNum
     * @param integer $genNum
     * @param Zend_Pdf_Element_Reference_Context $context
     * @param Zend_Pdf_ElementFactory $factory
     * @throws Zend_Pdf_Exception
     */
    public function __construct($objNum, $genNum = 0, ?Zend_Pdf_Element_Reference_Context $context = null, ?Zend_Pdf_ElementFactory $factory = null)
    {
        if ( !(is_integer($objNum) && $objNum > 0) ) {
            //require_once 'Zend/Pdf/Exception.php';
            throw new Zend_Pdf_Exception('Object number must be positive integer');
        }
        if ( !(is_integer($genNum) && $genNum >= 0) ) {
            //require_once 'Zend/Pdf/Exception.php';
            throw new Zend_Pdf_Exception('Generation number must be non-negative integer');
        }

        /*
         * These two parameters were set to null by default because original implementation had the $genNum param before
         * the with a default value which is a deprecated behavior now. In order to solve it without breaking parameter
         * order these two were set to null and added a null check
         */
        if (is_null($context)) {
            throw new Zend_Pdf_Exception('Context must not be null');
        }

        if (is_null($factory)) {
            throw new Zend_Pdf_Exception('Factory must not be null');
        }

        $this->_objNum  = $objNum;
        $this->_genNum  = $genNum;
        $this->_ref     = null;
        $this->_context = $context;
        $this->_factory = $factory;
    }

    /**
     * Check, that object is generated by specified factory
     *
     * @return Zend_Pdf_ElementFactory
     */
    public function getFactory()
    {
        return $this->_factory;
    }


    /**
     * Return type of the element.
     *
     * @return integer
     */
    public function getType()
    {
        if ($this->_ref === null) {
            $this->_dereference();
        }

        return $this->_ref->getType();
    }


    /**
     * Return reference to the object
     *
     * @param Zend_Pdf_Factory $factory
     * @return string
     */
    public function toString($factory = null)
    {
        if ($factory === null) {
            $shift = 0;
        } else {
            $shift = $factory->getEnumerationShift($this->_factory);
        }

        return $this->_objNum + $shift . ' ' . $this->_genNum . ' R';
    }


    /**
     * Dereference.
     * Take inderect object, take $value member of this object (must be Zend_Pdf_Element),
     * take reference to the $value member of this object and assign it to
     * $value member of current PDF Reference object
     * $obj can be null
     *
     * @throws Zend_Pdf_Exception
     */
    private function _dereference()
    {
        if (($obj = $this->_factory->fetchObject($this->_objNum . ' ' . $this->_genNum)) === null) {
            $obj = $this->_context->getParser()->getObject(
                           $this->_context->getRefTable()->getOffset($this->_objNum . ' ' . $this->_genNum . ' R'),
                           $this->_context
                                                          );
        }

        if ($obj === null ) {
            $this->_ref = new Zend_Pdf_Element_Null();
            return;
        }

        if ($obj->toString() != $this->_objNum . ' ' . $this->_genNum . ' R') {
            //require_once 'Zend/Pdf/Exception.php';
            throw new Zend_Pdf_Exception('Incorrect reference to the object');
        }

        $this->_ref = $obj;
    }

    /**
     * Detach PDF object from the factory (if applicable), clone it and attach to new factory.
     *
     * @param Zend_Pdf_ElementFactory $factory  The factory to attach
     * @param array &$processed  List of already processed indirect objects, used to avoid objects duplication
     * @param integer $mode  Cloning mode (defines filter for objects cloning)
     * @returns Zend_Pdf_Element
     */
    public function makeClone(Zend_Pdf_ElementFactory $factory, array &$processed, $mode)
    {
        if ($this->_ref === null) {
            $this->_dereference();
        }

        // This code duplicates code in Zend_Pdf_Element_Object class,
        // but allows to avoid unnecessary method call in most cases
        $id = spl_object_hash($this->_ref);
        if (isset($processed[$id])) {
            // Do nothing if object is already processed
            // return it
            return $processed[$id];
        }

        return $this->_ref->makeClone($factory, $processed, $mode);
    }

    /**
     * Mark object as modified, to include it into new PDF file segment.
     */
    public function touch()
    {
        if ($this->_ref === null) {
            $this->_dereference();
        }

        $this->_ref->touch();
    }

    /**
     * Return object, which can be used to identify object and its references identity
     *
     * @return Zend_Pdf_Element_Object
     */
    public function getObject()
    {
        if ($this->_ref === null) {
            $this->_dereference();
        }

        return $this->_ref;
    }

    /**
     * Get handler
     *
     * @param string $property
     * @return mixed
     */
    public function __get($property)
    {
        if ($this->_ref === null) {
            $this->_dereference();
        }

        return $this->_ref->$property;
    }

    /**
     * Set handler
     *
     * @param string $property
     * @param  mixed $value
     */
    public function __set($property, $value)
    {
        if ($this->_ref === null) {
            $this->_dereference();
        }

        $this->_ref->$property = $value;
    }

    /**
     * Call handler
     *
     * @param string $method
     * @param array  $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        if ($this->_ref === null) {
            $this->_dereference();
        }

        return call_user_func_array(array($this->_ref, $method), $args);
    }

    /**
     * Clean up resources
     */
    public function cleanUp()
    {
        $this->_ref = null;
    }

    /**
     * Convert PDF element to PHP type.
     *
     * @return mixed
     */
    public function toPhp()
    {
        if ($this->_ref === null) {
            $this->_dereference();
        }

        return $this->_ref->toPhp();
    }
}
