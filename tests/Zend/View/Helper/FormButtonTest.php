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
 * @version    $Id$
 */

// Call Zend_View_Helper_FormButtonTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "Zend_View_Helper_FormButtonTest::main");
}

require_once 'Zend/View.php';
require_once 'Zend/View/Helper/FormButton.php';

/**
 * Test class for Zend_View_Helper_FormButton.
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_View
 * @group      Zend_View_Helper
 */
class Zend_View_Helper_FormButtonTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main()
    {

        $suite  = new \PHPUnit\Framework\TestSuite("Zend_View_Helper_FormButtonTest");
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
        $this->view = new Zend_View();
        $this->helper = new Zend_View_Helper_FormButton();
        $this->helper->setView($this->view);
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     */
    protected function tearDown(): void
    {
    }

    public function testFormButtonRendersButtonXhtml()
    {
        $button = $this->helper->formButton('foo', 'bar');
        $this->assertMatchesRegularExpression('/<button[^>]*?value="bar"/', $button);
        $this->assertMatchesRegularExpression('/<button[^>]*?name="foo"/', $button);
        $this->assertMatchesRegularExpression('/<button[^>]*?id="foo"/', $button);
        $this->assertStringContainsStringIgnoringCase('</button>', $button);
    }

    public function testCanPassContentViaContentAttribKey()
    {
        $button = $this->helper->formButton('foo', 'bar', array('content' => 'Display this'));
        $this->assertStringContainsStringIgnoringCase('>Display this<', $button);
        $this->assertStringContainsStringIgnoringCase('<button', $button);
        $this->assertStringContainsStringIgnoringCase('</button>', $button);
    }

    public function testCanDisableContentEscaping()
    {
        $button = $this->helper->formButton('foo', 'bar', array('content' => '<b>Display this</b>', 'escape' => false));
        $this->assertStringContainsStringIgnoringCase('><b>Display this</b><', $button);

        $button = $this->helper->formButton(array('name' => 'foo', 'value' => 'bar', 'attribs' => array('content' => '<b>Display this</b>', 'escape' => false)));
        $this->assertStringContainsStringIgnoringCase('><b>Display this</b><', $button);

        $button = $this->helper->formButton(array('name' => 'foo', 'value' => 'bar', 'escape' => false, 'attribs' => array('content' => '<b>Display this</b>')));
        $this->assertStringContainsStringIgnoringCase('><b>Display this</b><', $button);
        $this->assertStringContainsStringIgnoringCase('<button', $button);
        $this->assertStringContainsStringIgnoringCase('</button>', $button);
    }

    public function testValueUsedForContentWhenNoContentProvided()
    {
        $button = $this->helper->formButton(array('name' => 'foo', 'value' => 'bar'));
        $this->assertMatchesRegularExpression('#<button[^>]*?value="bar"[^>]*>bar</button>#', $button);
    }

    public function testButtonTypeIsButtonByDefault()
    {
        $button = $this->helper->formButton(array('name' => 'foo', 'value' => 'bar'));
        $this->assertStringContainsStringIgnoringCase('type="button"', $button);
    }

    public function testButtonTypeMayOnlyBeValidXhtmlButtonType()
    {
        $button = $this->helper->formButton(array('name' => 'foo', 'value' => 'bar', 'attribs' => array('type' => 'submit')));
        $this->assertStringContainsStringIgnoringCase('type="submit"', $button);
        $button = $this->helper->formButton(array('name' => 'foo', 'value' => 'bar', 'attribs' => array('type' => 'reset')));
        $this->assertStringContainsStringIgnoringCase('type="reset"', $button);
        $button = $this->helper->formButton(array('name' => 'foo', 'value' => 'bar', 'attribs' => array('type' => 'button')));
        $this->assertStringContainsStringIgnoringCase('type="button"', $button);
        $button = $this->helper->formButton(array('name' => 'foo', 'value' => 'bar', 'attribs' => array('type' => 'bogus')));
        $this->assertStringContainsStringIgnoringCase('type="button"', $button);
    }
}

// Call Zend_View_Helper_FormButtonTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "Zend_View_Helper_FormButtonTest::main") {
    Zend_View_Helper_FormButtonTest::main();
}
