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

// Call Zend_View_Helper_FormSubmitTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "Zend_View_Helper_FormSubmitTest::main");
}

require_once 'Zend/View/Helper/FormSubmit.php';
require_once 'Zend/View.php';
require_once 'Zend/Registry.php';

/**
 * Test class for Zend_View_Helper_FormSubmit.
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_View
 * @group      Zend_View_Helper
 */
class Zend_View_Helper_FormSubmitTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Runs the test methods of this class.
     *
     * @return void
     */
    public static function main()
    {
        $suite  = new \PHPUnit\Framework\TestSuite("Zend_View_Helper_FormSubmitTest");
        $suite->run();
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp(): void
    {
        if (Zend_Registry::isRegistered('Zend_View_Helper_Doctype')) {
            $registry = Zend_Registry::getInstance();
            unset($registry['Zend_View_Helper_Doctype']);
        }
        $this->view   = new Zend_View();
        $this->helper = new Zend_View_Helper_FormSubmit();
        $this->helper->setView($this->view);
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->helper, $this->view);
    }

    public function testRendersSubmitInput()
    {
        $html = $this->helper->formSubmit(array(
            'name'    => 'foo',
            'value'   => 'Submit!',
        ));
        $this->assertMatchesRegularExpression('/<input[^>]*?(type="submit")/', $html);
    }

    /**
     * ZF-2254
     */
    public function testCanDisableSubmitButton()
    {
        $html = $this->helper->formSubmit(array(
            'name'    => 'foo',
            'value'   => 'Submit!',
            'attribs' => array('disable' => true)
        ));
        $this->assertMatchesRegularExpression('/<input[^>]*?(disabled="disabled")/', $html);
    }

    /**
     * ZF-2239
     */
    public function testValueAttributeIsAlwaysRendered()
    {
        $html = $this->helper->formSubmit(array(
            'name'    => 'foo',
            'value'   => '',
        ));
        $this->assertMatchesRegularExpression('/<input[^>]*?(value="")/', $html);
    }

    public function testRendersAsHtmlByDefault()
    {
        $test = $this->helper->formSubmit('foo', 'bar');
        $this->assertStringNotContainsStringIgnoringCase(' />', $test);
    }

    public function testCanRendersAsXHtml()
    {
        $this->view->doctype('XHTML1_STRICT');
        $test = $this->helper->formSubmit('foo', 'bar');
        $this->assertStringContainsStringIgnoringCase(' />', $test);
    }

    /**
     * @group ZF-10529
     */
    public function testDoesNotOutputEmptyId()
    {
        $test = $this->helper->formSubmit('', 'bar');
        $this->assertStringNotContainsStringIgnoringCase('id=""', $test);
    }
}

// Call Zend_View_Helper_FormSubmitTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "Zend_View_Helper_FormSubmitTest::main") {
    Zend_View_Helper_FormSubmitTest::main();
}
