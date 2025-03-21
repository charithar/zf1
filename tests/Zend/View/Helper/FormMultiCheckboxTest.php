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

// Call Zend_FormMultiCheckboxTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "Zend_View_Helper_FormMultiCheckboxTest::main");
}

require_once 'Zend/View/Helper/FormMultiCheckbox.php';
require_once 'Zend/View.php';
require_once 'Zend/Registry.php';

/**
 * Test class for Zend_View_Helper_FormMultiCheckbox
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_View
 * @group      Zend_View_Helper
 */
class Zend_View_Helper_FormMultiCheckboxTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Runs the test methods of this class.
     *
     * @return void
     */
    public static function main()
    {
        $suite  = new \PHPUnit\Framework\TestSuite("Zend_View_Helper_FormMultiCheckboxTest");
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
        $this->helper = new Zend_View_Helper_FormMultiCheckbox();
        $this->helper->setView($this->view);
        ob_start();
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        ob_end_clean();
    }

    public function testMultiCheckboxHelperRendersLabelledCheckboxesForEachOption()
    {
        $options = array(
            'foo' => 'Foo',
            'bar' => 'Bar',
            'baz' => 'Baz'
        );
        $html = $this->helper->formMultiCheckbox(array(
            'name'    => 'foo',
            'value'   => 'bar',
            'options' => $options,
        ));
        foreach ($options as $key => $value) {
            $pattern = '#((<label[^>]*>.*?)(<input[^>]*?("' . $key . '").*?>)(.*?</label>))#';
            if (!preg_match($pattern, $html, $matches)) {
                $this->fail('Failed to match ' . $pattern . ': ' . $html);
            }
            $this->assertStringContainsStringIgnoringCase($value, $matches[5], var_export($matches, 1));
            $this->assertStringContainsStringIgnoringCase('type="checkbox"', $matches[3], var_export($matches, 1));
            $this->assertStringContainsStringIgnoringCase('name="foo[]"', $matches[3], var_export($matches, 1));
            $this->assertStringContainsStringIgnoringCase('value="' . $key . '"', $matches[3], var_export($matches, 1));
        }
    }

    public function testRendersAsHtmlByDefault()
    {
        $options = array(
            'foo' => 'Foo',
            'bar' => 'Bar',
            'baz' => 'Baz'
        );
        $html = $this->helper->formMultiCheckbox(array(
            'name'    => 'foo',
            'value'   => 'bar',
            'options' => $options,
        ));
        foreach ($options as $key => $value) {
            $pattern = '#(<input[^>]*?("' . $key . '").*?>)#';
            if (!preg_match($pattern, $html, $matches)) {
                $this->fail('Failed to match ' . $pattern . ': ' . $html);
            }
            $this->assertStringNotContainsStringIgnoringCase(' />', $matches[1]);
        }
    }

    public function testCanRendersAsXHtml()
    {
        $this->view->doctype('XHTML1_STRICT');
        $options = array(
            'foo' => 'Foo',
            'bar' => 'Bar',
            'baz' => 'Baz'
        );
        $html = $this->helper->formMultiCheckbox(array(
            'name'    => 'foo',
            'value'   => 'bar',
            'options' => $options,
        ));
        foreach ($options as $key => $value) {
            $pattern = '#(<input[^>]*?("' . $key . '").*?>)#';
            if (!preg_match($pattern, $html, $matches)) {
                $this->fail('Failed to match ' . $pattern . ': ' . $html);
            }
            $this->assertStringContainsStringIgnoringCase(' />', $matches[1]);
        }
    }
}

// Call Zend_View_Helper_FormMultiCheckboxTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "Zend_View_Helper_FormMultiCheckboxTest::main") {
    Zend_View_Helper_FormMultiCheckboxTest::main();
}
