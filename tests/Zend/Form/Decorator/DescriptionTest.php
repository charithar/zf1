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
 * @package    Zend_Form
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

// Call Zend_Form_Decorator_DescriptionTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "Zend_Form_Decorator_DescriptionTest::main");
}

require_once 'Zend/Form/Decorator/Description.php';

require_once 'Zend/Form/Element.php';
require_once 'Zend/View.php';

/**
 * Test class for Zend_Form_Decorator_Description
 *
 * @category   Zend
 * @package    Zend_Form
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Form
 */
class Zend_Form_Decorator_DescriptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Runs the test methods of this class.
     *
     * @return void
     */
    public static function main()
    {

        $suite  = new \PHPUnit\Framework\TestSuite("Zend_Form_Decorator_DescriptionTest");
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
        if (isset($this->html)) {
            unset($this->html);
        }

        $this->element = new Zend_Form_Element('foo');
        $this->element->setDescription('a test description')
                      ->setView($this->getView());
        $this->decorator = new Zend_Form_Decorator_Description();
        $this->decorator->setElement($this->element);
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     */
    protected function tearDown(): void
    {
    }

    public function getView()
    {
        $view = new Zend_View();
        $view->addHelperPath(dirname(__FILE__) . '/../../../../library/Zend/View/Helper');
        return $view;
    }

    public function testRendersDescriptionInParagraphTagsByDefault()
    {
        $html = $this->decorator->render('');
        $this->assertStringContainsStringIgnoringCase('<p', $html, $html);
        $this->assertStringContainsStringIgnoringCase('</p>', $html);
        $this->assertStringContainsStringIgnoringCase($this->element->getDescription(), $html);
        $this->html = $html;
    }

    public function testParagraphTagsContainHintClassByDefault()
    {
        $this->testRendersDescriptionInParagraphTagsByDefault();
        $this->assertMatchesRegularExpression('/<p[^>]*?class="hint"/', $this->html);
    }

    public function testCanSpecifyAlternateTag()
    {
        $this->decorator->setTag('quote');
        $html = $this->decorator->render('');
        $this->assertStringContainsStringIgnoringCase('<quote', $html, $html);
        $this->assertStringContainsStringIgnoringCase('</quote>', $html);
        $this->assertStringContainsStringIgnoringCase($this->element->getDescription(), $html);
        $this->html = $html;
    }

    public function testCanSpecifyAlternateTagViaOption()
    {
        $this->decorator->setOption('tag', 'quote');
        $html = $this->decorator->render('');
        $this->assertStringContainsStringIgnoringCase('<quote', $html, $html);
        $this->assertStringContainsStringIgnoringCase('</quote>', $html);
        $this->assertStringContainsStringIgnoringCase($this->element->getDescription(), $html);
        $this->html = $html;
    }

    public function testAlternateTagContainsHintClass()
    {
        $this->testCanSpecifyAlternateTag();
        $this->assertMatchesRegularExpression('/<quote[^>]*?class="hint"/', $this->html);
    }

    public function testCanSpecifyAlternateClass()
    {
        $this->decorator->setOption('class', 'haha');
        $html = $this->decorator->render('');
        $this->assertMatchesRegularExpression('/<p[^>]*?class="haha"/', $html);
    }

    public function testRenderingEscapesDescriptionByDefault()
    {
        $description = '<span>some spanned text</span>';
        $this->element->setDescription($description);
        $html = $this->decorator->render('');
        $this->assertStringNotContainsStringIgnoringCase($description, $html);
        $this->assertStringContainsStringIgnoringCase('&lt;', $html);
        $this->assertStringContainsStringIgnoringCase('&gt;', $html);
        $this->assertStringContainsStringIgnoringCase('some spanned text', $html);
    }

    public function testCanDisableEscapingDescription()
    {
        $description = '<span>some spanned text</span>';
        $this->element->setDescription($description);
        $this->decorator->setEscape(false);
        $html = $this->decorator->render('');
        $this->assertStringContainsStringIgnoringCase($description, $html);
        $this->assertStringNotContainsStringIgnoringCase('&lt;', $html);
        $this->assertStringNotContainsStringIgnoringCase('&gt;', $html);
    }

    public function testCanSetEscapeFlagViaOption()
    {
        $description = '<span>some spanned text</span>';
        $this->element->setDescription($description);
        $this->decorator->setOption('escape', false);
        $html = $this->decorator->render('');
        $this->assertStringContainsStringIgnoringCase($description, $html);
        $this->assertStringNotContainsStringIgnoringCase('&lt;', $html);
        $this->assertStringNotContainsStringIgnoringCase('&gt;', $html);
    }

    public function testDescriptionIsTranslatedWhenTranslationAvailable()
    {
        require_once 'Zend/Translate.php';
        $translations = array('description' => 'This is the description');
        $translate = new Zend_Translate('array', $translations);
        $this->element->setDescription('description')
                      ->setTranslator($translate);
        $html = $this->decorator->render('');
        $this->assertStringContainsStringIgnoringCase($translations['description'], $html);
    }

    /**
     * @group ZF-8694
     */
    public function testDescriptionIsNotTranslatedTwice()
    {
        // Init translator
        require_once 'Zend/Translate.php';
        $translate = new Zend_Translate(
            array(
                 'adapter' => 'array',
                 'content' => array(
                     'firstDescription'  => 'secondDescription',
                     'secondDescription' => 'thirdDescription',
                 ),
                 'locale'  => 'en'
            )
        );

        // Create element
        $element = new Zend_Form_Element('foo');
        $element->setView($this->getView())
                ->setDescription('firstDescription')
                ->setTranslator($translate);

        $this->decorator->setElement($element);

        // Test
        $this->assertEquals(
            '<p class="hint">secondDescription</p>',
            trim($this->decorator->render(''))
        );
    }
}

// Call Zend_Form_Decorator_DescriptionTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "Zend_Form_Decorator_DescriptionTest::main") {
    Zend_Form_Decorator_DescriptionTest::main();
}
