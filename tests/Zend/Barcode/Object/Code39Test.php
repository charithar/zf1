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
 * @package    Zend_Barcode
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

require_once dirname(__FILE__) . '/TestCommon.php';

require_once 'Zend/Barcode/Object/Code39.php';

/**
 * @category   Zend
 * @package    Zend_Barcode
 * @subpackage UnitTests
 * @group      Zend_Barcode
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Barcode_Object_Code39Test extends Zend_Barcode_Object_TestCommon
{

    protected function _getBarcodeObject($options = null)
    {
        return new Zend_Barcode_Object_Code39($options);
    }

    public function testType()
    {
        $this->assertSame('code39', $this->_object->getType());
    }

    public function testChecksum()
    {
        $this->assertSame(2, $this->_object->getChecksum('0123456789'));
        $this->assertSame('W', $this->_object->getChecksum('CODE39'));
        $this->assertSame('J', $this->_object->getChecksum('FRAMEWORK-ZEND-COM'));
    }

    public function testSetText()
    {
        $this->_object->setText('0123456789');
        $this->assertSame('0123456789', $this->_object->getRawText());
        $this->assertSame('*0123456789*', $this->_object->getText());
        $this->assertSame('*0123456789*', $this->_object->getTextToDisplay());
    }

    public function testSetTextWithSpaces()
    {
        $this->_object->setText(' 0123456789 ');
        $this->assertSame(' 0123456789 ', $this->_object->getRawText());
        $this->assertSame('* 0123456789 *', $this->_object->getText());
        $this->assertSame('* 0123456789 *', $this->_object->getTextToDisplay());
    }

    public function testSetTextWithChecksum()
    {
        $this->_object->setText('0123456789');
        $this->_object->setWithChecksum(true);
        $this->assertSame('0123456789', $this->_object->getRawText());
        $this->assertSame('*01234567892*', $this->_object->getText());
        $this->assertSame('*0123456789*', $this->_object->getTextToDisplay());
    }

    public function testSetTextWithChecksumDisplayed()
    {
        $this->_object->setText('0123456789');
        $this->_object->setWithChecksum(true);
        $this->_object->setWithChecksumInText(true);
        $this->assertSame('0123456789', $this->_object->getRawText());
        $this->assertSame('*01234567892*', $this->_object->getText());
        $this->assertSame('*01234567892*', $this->_object->getTextToDisplay());
    }

    public function testBadTextAlwaysAllowed()
    {
        $this->_object->setText('&');
        $this->assertSame('&', $this->_object->getRawText());
    }

    public function testBadTextDetectedIfChecksumWished()
    {
        $this->expectException(Zend_Barcode_Object_Exception::class);
        $this->_object->setText('&');
        $this->_object->setWithChecksum(true);
        $this->_object->getText();
    }

    public function testCheckGoodParams()
    {
        $this->_object->setText('0123456789');
        $this->assertTrue($this->_object->checkParams());
    }

    public function testCheckParamsWithLowRatio()
    {
        $this->expectException(Zend_Barcode_Object_Exception::class);
        $this->_object->setText('TEST');
        $this->_object->setBarThinWidth(21);
        $this->_object->setBarThickWidth(40);
        $this->_object->checkParams();
    }

    public function testCheckParamsWithHighRatio()
    {
        $this->expectException(Zend_Barcode_Object_Exception::class);
        $this->_object->setText('TEST');
        $this->_object->setBarThinWidth(20);
        $this->_object->setBarThickWidth(61);
        $this->_object->checkParams();
    }

    public function testGetKnownWidthWithoutOrientation()
    {
        $this->_object->setText('0123456789');
        $this->assertEquals(211, $this->_object->getWidth());
        $this->_object->setWithQuietZones(false);
        $this->assertEquals(191, $this->_object->getWidth(true));
    }

    public function testCompleteGeneration()
    {
        $this->_object->setText('0123456789');
        $this->_object->draw();
        $instructions = $this->loadInstructionsFile('Code39_0123456789_instructions');
        $this->assertEquals($instructions, $this->_object->getInstructions());
    }

    public function testCompleteGenerationWithStretchText()
    {
        $this->_object->setText('0123456789');
        $this->_object->setStretchText(true);
        $this->_object->draw();
        $instructions = $this->loadInstructionsFile(
                'Code39_0123456789_stretchtext_instructions');
        $this->assertEquals($instructions, $this->_object->getInstructions());
    }

    public function testCompleteGenerationWithBorder()
    {
        $this->_object->setText('0123456789');
        $this->_object->setWithBorder(true);
        $this->_object->draw();
        $instructions = $this->loadInstructionsFile(
                'Code39_0123456789_border_instructions');
        $this->assertEquals($instructions, $this->_object->getInstructions());
    }

    public function testCompleteGenerationWithOrientation()
    {
        $this->_object->setText('0123456789');
        $this->_object->setOrientation(60);
        $this->_object->draw();
        $instructions = $this->loadInstructionsFile(
                'Code39_0123456789_oriented_instructions');
        $this->assertEquals($instructions, $this->_object->getInstructions());
    }

    public function testCompleteGenerationWithStretchTextWithOrientation()
    {
        $this->_object->setText('0123456789');
        $this->_object->setOrientation(60);
        $this->_object->setStretchText(true);
        $this->_object->draw();
        $instructions = $this->loadInstructionsFile(
                'Code39_0123456789_stretchtext_oriented_instructions');
        $this->assertEquals($instructions, $this->_object->getInstructions());
    }

    public function testCompleteGenerationWithBorderWithOrientation()
    {
        $this->_object->setText('0123456789');
        $this->_object->setOrientation(60);
        $this->_object->setWithBorder(true);
        $this->_object->draw();
        $instructions = $this->loadInstructionsFile(
                'Code39_0123456789_border_oriented_instructions');
        $this->assertEquals($instructions, $this->_object->getInstructions());
    }
}
