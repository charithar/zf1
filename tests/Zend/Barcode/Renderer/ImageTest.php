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

require_once 'Zend/Barcode/Renderer/Image.php';
require_once 'Zend/Barcode/Object/Code39.php';

/**
 * @category   Zend
 * @package    Zend_Barcode
 * @subpackage UnitTests
 * @group      Zend_Barcode
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Barcode_Renderer_ImageTest extends Zend_Barcode_Renderer_TestCommon
{

    protected function setUp(): void
    {
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('Zend_Barcode_Renderer_ImageTest requires the GD extension');
        }
        parent::setUp();
    }

    protected function _getRendererObject($options = null)
    {
        return new Zend_Barcode_Renderer_Image($options);
    }

    public function testType()
    {
        $this->assertSame('image', $this->_renderer->getType());
    }

    public function testGoodImageResource()
    {
        $this->expectNotToPerformAssertions();
        $imageResource = imagecreatetruecolor(1, 1);
        $this->_renderer->setResource($imageResource);
    }

    public function testObjectImageResource()
    {
        $this->expectException(Zend_Barcode_Renderer_Exception::class);
        $imageResource = new StdClass();
        $this->_renderer->setResource($imageResource);
    }

    public function testGoodHeight()
    {
        $this->assertSame(0, $this->_renderer->getHeight());
        $this->_renderer->setHeight(123);
        $this->assertSame(123, $this->_renderer->getHeight());
        $this->_renderer->setHeight(0);
        $this->assertSame(0, $this->_renderer->getHeight());
    }

    public function testBadHeight()
    {
        $this->expectException(Zend_Barcode_Renderer_Exception::class);
        $this->_renderer->setHeight(- 1);
    }

    public function testGoodWidth()
    {
        $this->assertSame(0, $this->_renderer->getWidth());
        $this->_renderer->setWidth(123);
        $this->assertSame(123, $this->_renderer->getWidth());
        $this->_renderer->setWidth(0);
        $this->assertSame(0, $this->_renderer->getWidth());
    }

    public function testBadWidth()
    {
        $this->expectException(Zend_Barcode_Renderer_Exception::class);
        $this->_renderer->setWidth(- 1);
    }

    public function testAllowedImageType()
    {
        $types = array('gif' => 'gif' , 'jpg' => 'jpeg' , 'jpeg' => 'jpeg' ,
                'png' => 'png');
        foreach ($types as $type => $expectedType) {
            $this->_renderer->setImageType($type);
            $this->assertSame($expectedType,
                    $this->_renderer->getImageType());
        }
    }

    public function testNonAllowedImageType()
    {
        $this->expectException(Zend_Barcode_Renderer_Exception::class);
        $this->_renderer->setImageType('other');
    }

    public function testDrawReturnResource()
    {
        $this->_checkTTFRequirement();

        $barcode = new Zend_Barcode_Object_Code39(array('text' => '0123456789'));
        $this->_renderer->setBarcode($barcode);
        $resource = $this->_renderer->draw();
        $this->assertTrue(gettype($resource) == 'resource', 'Image must be a resource');
        $this->assertTrue(get_resource_type($resource) == 'gd',
                'Image must be a GD resource');
    }

    public function testDrawWithExistantResourceReturnResource()
    {
        $this->_checkTTFRequirement();

        $barcode = new Zend_Barcode_Object_Code39(array('text' => '0123456789'));
        $this->_renderer->setBarcode($barcode);
        $imageResource = imagecreatetruecolor(500, 500);
        $this->_renderer->setResource($imageResource);
        $resource = $this->_renderer->draw();
        $this->assertTrue(gettype($resource) == 'resource', 'Image must be a resource');
        $this->assertTrue(get_resource_type($resource) == 'gd',
                'Image must be a GD resource');
        $this->assertSame($resource, $imageResource);
    }

    public function testGoodUserHeight()
    {
        $barcode = new Zend_Barcode_Object_Code39(array('text' => '0123456789'));
        $this->assertEquals(62, $barcode->getHeight());
        $this->_renderer->setBarcode($barcode);
        $this->_renderer->setHeight(62);
        $this->assertTrue($this->_renderer->checkParams());
    }

    public function testBadUserHeightLessThanBarcodeHeight()
    {
        $this->expectException(Zend_Barcode_Renderer_Exception::class);
        $barcode = new Zend_Barcode_Object_Code39(array('text' => '0123456789'));
        $this->assertEquals(62, $barcode->getHeight());
        $this->_renderer->setBarcode($barcode);
        $this->_renderer->setHeight(61);
        $this->_renderer->checkParams();
    }

    public function testGoodUserWidth()
    {
        $barcode = new Zend_Barcode_Object_Code39(array('text' => '0123456789'));
        $this->assertEquals(211, $barcode->getWidth());
        $this->_renderer->setBarcode($barcode);
        $this->_renderer->setWidth(211);
        $this->assertTrue($this->_renderer->checkParams());
    }

    public function testBadUserWidthLessThanBarcodeWidth()
    {
        $this->expectException(Zend_Barcode_Renderer_Exception::class);
        $barcode = new Zend_Barcode_Object_Code39(array('text' => '0123456789'));
        $this->assertEquals(211, $barcode->getWidth());
        $this->_renderer->setBarcode($barcode);
        $this->_renderer->setWidth(210);
        $this->_renderer->checkParams();
    }

    public function testGoodHeightOfUserResource()
    {
        $barcode = new Zend_Barcode_Object_Code39(array('text' => '0123456789'));
        $this->assertEquals(62, $barcode->getHeight());
        $imageResource = imagecreatetruecolor(500, 62);
        $this->_renderer->setResource($imageResource);
        $this->_renderer->setBarcode($barcode);
        $this->assertTrue($this->_renderer->checkParams());
    }

    public function testBadHeightOfUserResource()
    {
        $this->expectException(Zend_Barcode_Renderer_Exception::class);
        $barcode = new Zend_Barcode_Object_Code39(array('text' => '0123456789'));
        $this->assertEquals(62, $barcode->getHeight());
        $this->_renderer->setBarcode($barcode);
        $imageResource = imagecreatetruecolor(500, 61);
        $this->_renderer->setResource($imageResource);
        $this->_renderer->checkParams();
    }

    public function testGoodWidthOfUserResource()
    {
        $barcode = new Zend_Barcode_Object_Code39(array('text' => '0123456789'));
        $this->assertEquals(211, $barcode->getWidth());
        $imageResource = imagecreatetruecolor(211, 500);
        $this->_renderer->setResource($imageResource);
        $this->_renderer->setBarcode($barcode);
        $this->assertTrue($this->_renderer->checkParams());
    }

    public function testBadWidthOfUserResource()
    {
        $this->expectException(Zend_Barcode_Renderer_Exception::class);
        $barcode = new Zend_Barcode_Object_Code39(array('text' => '0123456789'));
        $this->assertEquals(211, $barcode->getWidth());
        $this->_renderer->setBarcode($barcode);
        $imageResource = imagecreatetruecolor(210, 500);
        $this->_renderer->setResource($imageResource);
        $this->_renderer->checkParams();
    }

    public function testNoFontWithOrientation()
    {
        $this->expectException(Zend_Barcode_Renderer_Exception::class);
        Zend_Barcode::setBarcodeFont('');
        $barcode = new Zend_Barcode_Object_Code39(array('text' => '0123456789'));
        $barcode->setOrientation(1);
        $this->_renderer->setBarcode($barcode);
        $this->_renderer->draw();
    }

    protected function _getRendererWithWidth500AndHeight300()
    {
        return $this->_renderer->setHeight(300)->setWidth(500);
    }

    public function testRendererWithUnknownInstructionProvideByObject()
    {
        $this->expectException(Zend_Barcode_Renderer_Exception::class);
        parent::testRendererWithUnknownInstructionProvideByObject();
    }

    public function testHorizontalPositionToLeft()
    {
        $this->_checkTTFRequirement();

        parent::testHorizontalPositionToLeft();
    }

    public function testHorizontalPositionToCenter()
    {
        $this->_checkTTFRequirement();

        parent::testHorizontalPositionToCenter();
    }

    public function testHorizontalPositionToRight()
    {
        $this->_checkTTFRequirement();

        parent::testHorizontalPositionToRight();
    }

    public function testVerticalPositionToTop()
    {
        $this->_checkTTFRequirement();

        parent::testVerticalPositionToTop();
    }

    public function testVerticalPositionToMiddle()
    {
        $this->_checkTTFRequirement();

        parent::testVerticalPositionToMiddle();
    }

    public function testVerticalPositionToBottom()
    {
        $this->_checkTTFRequirement();

        parent::testVerticalPositionToBottom();
    }

    public function testLeftOffsetOverrideHorizontalPosition()
    {
        $this->_checkTTFRequirement();

        parent::testLeftOffsetOverrideHorizontalPosition();
    }

    public function testTopOffsetOverrideVerticalPosition()
    {
        $this->_checkTTFRequirement();

        parent::testTopOffsetOverrideVerticalPosition();
    }

    protected function _checkTTFRequirement()
    {
        if (!function_exists('imagettfbbox')) {
            $this->markTestSkipped('TTF (FreeType) support is required in order to run this test');
        }
    }
}
