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
 * @package    Zend_Soap
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @package Zend_Soap
 * @subpackage UnitTests
 */

/** Zend_Soap_Wsdl */
require_once 'Zend/Soap/Wsdl.php';

require_once 'Zend/Soap/Wsdl/Strategy/ArrayOfTypeSequence.php';

/**
 * @category   Zend
 * @package    Zend_Soap
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Soap
 * @group      Zend_Soap_Wsdl
 */
class Zend_Soap_Wsdl_ArrayOfTypeSequenceStrategyTest extends \PHPUnit\Framework\TestCase
{
    private $wsdl;
    private $strategy;

    protected function setUp(): void
    {
        $this->strategy = new Zend_Soap_Wsdl_Strategy_ArrayOfTypeSequence();
        $this->wsdl = new Zend_Soap_Wsdl('MyService', 'http://localhost/MyService.php', $this->strategy);
    }

    public function testFunctionReturningSimpleArrayOfInts()
    {
        $this->wsdl->addComplexType('int[]');

        $this->assertStringContainsStringIgnoringCase(
            '<xsd:complexType name="ArrayOfInt">'.
                '<xsd:sequence><xsd:element name="item" type="xsd:int" minOccurs="0" maxOccurs="unbounded"/></xsd:sequence>'.
            '</xsd:complexType>',
            $this->wsdl->toXML()
        );
    }

    public function testFunctionReturningSimpleArrayOfString()
    {
        $this->wsdl->addComplexType('string[]');

        $this->assertStringContainsStringIgnoringCase(
            '<xsd:complexType name="ArrayOfString">'.
                '<xsd:sequence><xsd:element name="item" type="xsd:string" minOccurs="0" maxOccurs="unbounded"/></xsd:sequence>'.
            '</xsd:complexType>',
            $this->wsdl->toXML()
        );
    }

    public function testFunctionReturningNestedArrayOfString()
    {
        $return = $this->wsdl->addComplexType('string[][]');
        $this->assertEquals("tns:ArrayOfArrayOfString", $return);

        $wsdl = $this->wsdl->toXML();

        // Check for ArrayOfArrayOfString
        $this->assertStringContainsStringIgnoringCase(
            '<xsd:complexType name="ArrayOfArrayOfString"><xsd:sequence><xsd:element name="item" type="tns:ArrayOfString" minOccurs="0" maxOccurs="unbounded"/></xsd:sequence></xsd:complexType>',
            $wsdl
        );
        // Check for ArrayOfString
        $this->assertStringContainsStringIgnoringCase(
            '<xsd:complexType name="ArrayOfString"><xsd:sequence><xsd:element name="item" type="xsd:string" minOccurs="0" maxOccurs="unbounded"/></xsd:sequence></xsd:complexType>',
            $wsdl
        );
    }

    public function testFunctionReturningMultipleNestedArrayOfType()
    {
        $return = $this->wsdl->addComplexType('string[][][]');
        $this->assertEquals("tns:ArrayOfArrayOfArrayOfString", $return);

        $wsdl = $this->wsdl->toXML();

        // Check for ArrayOfArrayOfArrayOfString
        $this->assertStringContainsStringIgnoringCase(
            '<xsd:complexType name="ArrayOfArrayOfArrayOfString"><xsd:sequence><xsd:element name="item" type="tns:ArrayOfArrayOfString" minOccurs="0" maxOccurs="unbounded"/></xsd:sequence></xsd:complexType>',
            $wsdl
        );
        // Check for ArrayOfArrayOfString
        $this->assertStringContainsStringIgnoringCase(
            '<xsd:complexType name="ArrayOfArrayOfString"><xsd:sequence><xsd:element name="item" type="tns:ArrayOfString" minOccurs="0" maxOccurs="unbounded"/></xsd:sequence></xsd:complexType>',
            $wsdl
        );
        // Check for ArrayOfString
        $this->assertStringContainsStringIgnoringCase(
            '<xsd:complexType name="ArrayOfString"><xsd:sequence><xsd:element name="item" type="xsd:string" minOccurs="0" maxOccurs="unbounded"/></xsd:sequence></xsd:complexType>',
            $wsdl
        );
    }


    public function testAddComplexTypeObject()
    {
        $return = $this->wsdl->addComplexType('Zend_Soap_Wsdl_SequenceTest');

        $this->assertEquals("tns:Zend_Soap_Wsdl_SequenceTest", $return);

        $wsdl = $this->wsdl->toXML();

        $this->assertStringContainsStringIgnoringCase(
            '<xsd:complexType name="Zend_Soap_Wsdl_SequenceTest"><xsd:all><xsd:element name="var" type="xsd:int"/></xsd:all></xsd:complexType>',
            $wsdl
        );
    }

    public function testAddComplexTypeArrayOfObject()
    {

         $return = $this->wsdl->addComplexType('Zend_Soap_Wsdl_SequenceTest[]');

         $this->assertEquals("tns:ArrayOfZend_soap_wsdl_sequencetest", $return);

         $wsdl = $this->wsdl->toXML();

         $this->assertStringContainsStringIgnoringCase(
            '<xsd:complexType name="Zend_Soap_Wsdl_SequenceTest"><xsd:all><xsd:element name="var" type="xsd:int"/></xsd:all></xsd:complexType>',
            $wsdl
         );

         $this->assertStringContainsStringIgnoringCase(
            '<xsd:complexType name="ArrayOfZend_soap_wsdl_sequencetest"><xsd:sequence><xsd:element name="item" type="tns:Zend_Soap_Wsdl_SequenceTest" minOccurs="0" maxOccurs="unbounded"/></xsd:sequence></xsd:complexType>',
            $wsdl
         );
    }

    public function testAddComplexTypeOfNonExistingClassThrowsException()
    {
        $this->expectException("Zend_Soap_Wsdl_Exception");

        $this->wsdl->addComplexType('Zend_Soap_Wsdl_UnknownClass[]');
    }
}

class Zend_Soap_Wsdl_SequenceTest
{
    /**
     * @var int
     */
    public $var = 5;
}
