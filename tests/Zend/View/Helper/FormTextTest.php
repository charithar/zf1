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
 * @package    Zend_View
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version $Id$
 */

// Call Zend_View_Helper_FormTextTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "Zend_View_Helper_FormTextTest::main");
}

require_once 'Zend/View.php';
require_once 'Zend/View/Helper/FormText.php';
require_once 'Zend/Registry.php';

/**
 * Zend_View_Helper_FormTextTest
 *
 * Tests formText helper, including some common functionality of all form helpers
 * @category   Zend
 * @package    Zend_View
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_View
 * @group      Zend_View_Helper
 */
class Zend_View_Helper_FormTextTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main()
    {
        $suite  = new \PHPUnit\Framework\TestSuite("Zend_View_Helper_FormTextTest");
        $suite->run();
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     */
    protected function setUp(): void
    {
        if (Zend_Registry::isRegistered('Zend_View_Helper_Doctype')) {
            $registry = Zend_Registry::getInstance();
            unset($registry['Zend_View_Helper_Doctype']);
        }
        $this->view = new Zend_View();
        $this->helper = new Zend_View_Helper_FormText();
        $this->helper->setView($this->view);
    }

    public function testIdSetFromName()
    {
        $element = $this->helper->formText('foo');
        $this->assertStringContainsStringIgnoringCase('name="foo"', $element);
        $this->assertStringContainsStringIgnoringCase('id="foo"', $element);
    }

    public function testSetIdFromAttribs()
    {
        $element = $this->helper->formText('foo', null, array('id' => 'bar'));
        $this->assertStringContainsStringIgnoringCase('name="foo"', $element);
        $this->assertStringContainsStringIgnoringCase('id="bar"', $element);
    }

    public function testSetValue()
    {
        $element = $this->helper->formText('foo', 'bar');
        $this->assertStringContainsStringIgnoringCase('name="foo"', $element);
        $this->assertStringContainsStringIgnoringCase('value="bar"', $element);
    }

    public function testReadOnlyAttribute()
    {
        $element = $this->helper->formText('foo', null, array('readonly' => 'readonly'));
        $this->assertStringContainsStringIgnoringCase('readonly="readonly"', $element);
    }

    /**
     * ZF-1666
     */
    public function testCanDisableElement()
    {
        $html = $this->helper->formText(array(
            'name'    => 'foo',
            'value'   => 'bar',
            'attribs' => array('disable' => true)
        ));

        $this->assertMatchesRegularExpression('/<input[^>]*?(disabled="disabled")/', $html);
    }

    /**
     * ZF-1666
     */
    public function testDisablingElementDoesNotRenderHiddenElements()
    {
        $html = $this->helper->formText(array(
            'name'    => 'foo',
            'value'   => 'bar',
            'attribs' => array('disable' => true)
        ));

        $this->assertDoesNotMatchRegularExpression('/<input[^>]*?(type="hidden")/', $html);
    }

    public function testRendersAsHtmlByDefault()
    {
        $test = $this->helper->formText('foo', 'bar');
        $this->assertStringNotContainsStringIgnoringCase(' />', $test);
    }

    public function testCanRendersAsXHtml()
    {
        $this->view->doctype('XHTML1_STRICT');
        $test = $this->helper->formText('foo', 'bar');
        $this->assertStringContainsStringIgnoringCase(' />', $test);
    }
}

// Call Zend_View_Helper_FormTextTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "Zend_View_Helper_FormTextTest::main") {
    Zend_View_Helper_FormTextTest::main();
}
