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

// Call Zend_View_Helper_HeadScriptTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "Zend_View_Helper_HeadScriptTest::main");
}

/** Zend_View_Helper_HeadScript */
require_once 'Zend/View/Helper/HeadScript.php';

/** Zend_View_Helper_Placeholder_Registry */
require_once 'Zend/View/Helper/Placeholder/Registry.php';

/** Zend_Registry */
require_once 'Zend/Registry.php';

/**
 * Test class for Zend_View_Helper_HeadScript.
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_View
 * @group      Zend_View_Helper
 */
class Zend_View_Helper_HeadScriptTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Zend_View_Helper_HeadScript
     */
    public $helper;

    /**
     * @var string
     */
    public $basePath;

    /**
     * Runs the test methods of this class.
     *
     * @return void
     */
    public static function main()
    {
        $suite  = new \PHPUnit\Framework\TestSuite("Zend_View_Helper_HeadScriptTest");
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
        $regKey = Zend_View_Helper_Placeholder_Registry::REGISTRY_KEY;
        if (Zend_Registry::isRegistered($regKey)) {
            $registry = Zend_Registry::getInstance();
            unset($registry[$regKey]);
        }
        $this->basePath = dirname(__FILE__) . '/_files/modules';
        $this->helper = new Zend_View_Helper_HeadScript();
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->helper);
    }

    public function testNamespaceRegisteredInPlaceholderRegistryAfterInstantiation()
    {
        $registry = Zend_View_Helper_Placeholder_Registry::getRegistry();
        if ($registry->containerExists('Zend_View_Helper_HeadScript')) {
            $registry->deleteContainer('Zend_View_Helper_HeadScript');
        }
        $this->assertFalse($registry->containerExists('Zend_View_Helper_HeadScript'));
        $helper = new Zend_View_Helper_HeadScript();
        $this->assertTrue($registry->containerExists('Zend_View_Helper_HeadScript'));
    }

    public function testHeadScriptReturnsObjectInstance()
    {
        $placeholder = $this->helper->headScript();
        $this->assertTrue($placeholder instanceof Zend_View_Helper_HeadScript);
    }

    public function testSetPrependAppendAndOffsetSetThrowExceptionsOnInvalidItems()
    {
        $this->expectNotToPerformAssertions();
        try {
            $this->helper->append('foo');
            $this->fail('Append should throw exception with invalid item');
        } catch (Zend_View_Exception $e) { }
        try {
            $this->helper->offsetSet(1, 'foo');
            $this->fail('OffsetSet should throw exception with invalid item');
        } catch (Zend_View_Exception $e) { }
        try {
            $this->helper->prepend('foo');
            $this->fail('Prepend should throw exception with invalid item');
        } catch (Zend_View_Exception $e) { }
        try {
            $this->helper->set('foo');
            $this->fail('Set should throw exception with invalid item');
        } catch (Zend_View_Exception $e) { }
    }

    protected function _inflectAction($type)
    {
        return ucfirst(strtolower($type));
    }

    protected function _testOverloadAppend($type)
    {
        $action = 'append' . $this->_inflectAction($type);
        $string = 'foo';
        for ($i = 0; $i < 3; ++$i) {
            $string .= ' foo';
            $this->helper->$action($string);
            $values = $this->helper->getArrayCopy();
            $this->assertEquals($i + 1, count($values));
            if ('file' == $type) {
                $this->assertEquals($string, $values[$i]->attributes['src']);
            } elseif ('script' == $type) {
                $this->assertEquals($string, $values[$i]->source);
            }
            $this->assertEquals('text/javascript', $values[$i]->type);
        }
    }

    protected function _testOverloadPrepend($type)
    {
        $action = 'prepend' . $this->_inflectAction($type);
        $string = 'foo';
        for ($i = 0; $i < 3; ++$i) {
            $string .= ' foo';
            $this->helper->$action($string);
            $values = $this->helper->getArrayCopy();
            $this->assertEquals($i + 1, count($values));
            $first = array_shift($values);
            if ('file' == $type) {
                $this->assertEquals($string, $first->attributes['src']);
            } elseif ('script' == $type) {
                $this->assertEquals($string, $first->source);
            }
            $this->assertEquals('text/javascript', $first->type);
        }
    }

    protected function _testOverloadSet($type)
    {
        $action = 'set' . $this->_inflectAction($type);
        $string = 'foo';
        for ($i = 0; $i < 3; ++$i) {
            $this->helper->appendScript($string);
            $string .= ' foo';
        }
        $this->helper->$action($string);
        $values = $this->helper->getArrayCopy();
        $this->assertEquals(1, count($values));
        if ('file' == $type) {
            $this->assertEquals($string, $values[0]->attributes['src']);
        } elseif ('script' == $type) {
            $this->assertEquals($string, $values[0]->source);
        }
        $this->assertEquals('text/javascript', $values[0]->type);
    }

    protected function _testOverloadOffsetSet($type)
    {
        $action = 'offsetSet' . $this->_inflectAction($type);
        $string = 'foo';
        $this->helper->$action(5, $string);
        $values = $this->helper->getArrayCopy();
        $this->assertEquals(1, count($values));
        if ('file' == $type) {
            $this->assertEquals($string, $values[5]->attributes['src']);
        } elseif ('script' == $type) {
            $this->assertEquals($string, $values[5]->source);
        }
        $this->assertEquals('text/javascript', $values[5]->type);
    }

    public function testOverloadAppendFileAppendsScriptsToStack()
    {
        $this->_testOverloadAppend('file');
    }

    public function testOverloadAppendScriptAppendsScriptsToStack()
    {
        $this->_testOverloadAppend('script');
    }

    public function testOverloadPrependFileAppendsScriptsToStack()
    {
        $this->_testOverloadPrepend('file');
    }

    public function testOverloadPrependScriptAppendsScriptsToStack()
    {
        $this->_testOverloadPrepend('script');
    }

    public function testOverloadSetFileOverwritesStack()
    {
        $this->_testOverloadSet('file');
    }

    public function testOverloadSetScriptOverwritesStack()
    {
        $this->_testOverloadSet('script');
    }

    public function testOverloadOffsetSetFileWritesToSpecifiedIndex()
    {
        $this->_testOverloadOffsetSet('file');
    }

    public function testOverloadOffsetSetScriptWritesToSpecifiedIndex()
    {
        $this->_testOverloadOffsetSet('script');
    }

    public function testOverloadingThrowsExceptionWithInvalidMethod()
    {
        $this->expectNotToPerformAssertions();
        try {
            $this->helper->fooBar('foo');
            $this->fail('Invalid method should raise exception');
        } catch (Zend_View_Exception $e) {
        }
    }

    public function testOverloadingWithTooFewArgumentsRaisesException()
    {
        $this->expectNotToPerformAssertions();
        try {
            $this->helper->setScript();
            $this->fail('Too few arguments should raise exception');
        } catch (Zend_View_Exception $e) {
        }

        try {
            $this->helper->offsetSetScript(5);
            $this->fail('Too few arguments should raise exception');
        } catch (Zend_View_Exception $e) {
        }
    }

    public function testHeadScriptAppropriatelySetsScriptItems()
    {
        $this->helper->headScript('FILE', 'foo', 'set')
                     ->headScript('SCRIPT', 'bar', 'prepend')
                     ->headScript('SCRIPT', 'baz', 'append');
        $items = $this->helper->getArrayCopy();
        for ($i = 0; $i < 3; ++$i) {
            $item = $items[$i];
            switch ($i) {
                case 0:
                    $this->assertObjectHasProperty('source', $item);
                    $this->assertEquals('bar', $item->source);
                    break;
                case 1:
                    $this->assertObjectHasProperty('attributes', $item);
                    $this->assertTrue(isset($item->attributes['src']));
                    $this->assertEquals('foo', $item->attributes['src']);
                    break;
                case 2:
                    $this->assertObjectHasProperty('source', $item);
                    $this->assertEquals('baz', $item->source);
                    break;
            }
        }
    }

    public function testToStringRendersValidHtml()
    {
        $this->helper->headScript('FILE', 'foo', 'set')
                     ->headScript('SCRIPT', 'bar', 'prepend')
                     ->headScript('SCRIPT', 'baz', 'append');
        $string = $this->helper->toString();

        $scripts = substr_count($string, '<script ');
        $this->assertEquals(3, $scripts);
        $scripts = substr_count($string, '</script>');
        $this->assertEquals(3, $scripts);
        $scripts = substr_count($string, 'src="');
        $this->assertEquals(1, $scripts);
        $scripts = substr_count($string, '><');
        $this->assertEquals(1, $scripts);

        $this->assertStringContainsStringIgnoringCase('src="foo"', $string);
        $this->assertStringContainsStringIgnoringCase('bar', $string);
        $this->assertStringContainsStringIgnoringCase('baz', $string);

        $doc = new DOMDocument;
        $dom = $doc->loadHtml($string);
        $this->assertTrue($dom !== false);
    }

    public function testCapturingCapturesToObject()
    {
        $this->helper->captureStart();
        echo 'foobar';
        $this->helper->captureEnd();
        $values = $this->helper->getArrayCopy();
        $this->assertEquals(1, count($values), var_export($values, 1));
        $item = array_shift($values);
        $this->assertStringContainsStringIgnoringCase('foobar', $item->source);
    }

    public function testIndentationIsHonored()
    {
        $this->helper->setIndent(4);
        $this->helper->appendScript('
var foo = "bar";
    document.write(foo.strlen());');
        $this->helper->appendScript('
var bar = "baz";
document.write(bar.strlen());');
        $string = $this->helper->toString();

        $scripts = substr_count($string, '    <script');
        $this->assertEquals(2, $scripts);
        $this->assertStringContainsStringIgnoringCase('    //', $string);
        $this->assertStringContainsStringIgnoringCase('var', $string);
        $this->assertStringContainsStringIgnoringCase('document', $string);
        $this->assertStringContainsStringIgnoringCase('    document', $string);
    }

    public function testDoesNotAllowDuplicateFiles()
    {
        $this->helper->headScript('FILE', '/js/prototype.js');
        $this->helper->headScript('FILE', '/js/prototype.js');
        $this->assertEquals(1, count($this->helper));
    }

    public function testRenderingDoesNotRenderArbitraryAttributesByDefault()
    {
        $this->helper->headScript()->appendFile('/js/foo.js', 'text/javascript', array('bogus' => 'deferred'));
        $test = $this->helper->headScript()->toString();
        $this->assertStringNotContainsStringIgnoringCase('bogus="deferred"', $test);
    }

    public function testCanRenderArbitraryAttributesOnRequest()
    {
        $this->helper->headScript()->appendFile('/js/foo.js', 'text/javascript', array('bogus' => 'deferred'))
             ->setAllowArbitraryAttributes(true);
        $test = $this->helper->headScript()->toString();
        $this->assertStringContainsStringIgnoringCase('bogus="deferred"', $test);
    }

    public function testCanPerformMultipleSerialCaptures()
    {
        $this->expectNotToPerformAssertions();
        $this->helper->headScript()->captureStart();
        echo "this is something captured";
        $this->helper->headScript()->captureEnd();
        try {
            $this->helper->headScript()->captureStart();
        } catch (Zend_View_Exception $e) {
            $this->fail('Serial captures should be allowed');
        }
        echo "this is something else captured";
        $this->helper->headScript()->captureEnd();
    }

    public function testCannotNestCaptures()
    {
        $this->helper->headScript()->captureStart();
        echo "this is something captured";
        try {
            $this->helper->headScript()->captureStart();
            $this->helper->headScript()->captureEnd();
            $this->fail('Should not be able to nest captures');
        } catch (Zend_View_Exception $e) {
            $this->helper->headScript()->captureEnd();
            $this->assertStringContainsStringIgnoringCase('Cannot nest', $e->getMessage());
        }
    }

    /**
     * @group ZF-3928
     * @link http://framework.zend.com/issues/browse/ZF-3928
     */
    public function testTurnOffAutoEscapeDoesNotEncodeAmpersand()
    {
        $this->helper->setAutoEscape(false)->appendFile('test.js?id=123&foo=bar');
        $this->assertEquals('<script type="text/javascript" src="test.js?id=123&foo=bar"></script>', $this->helper->toString());
    }

    public function testConditionalScript()
    {
        $this->helper->headScript()->appendFile('/js/foo.js', 'text/javascript', array('conditional' => 'lt IE 7'));
        $test = $this->helper->headScript()->toString();
        $this->assertStringContainsStringIgnoringCase('<!--[if lt IE 7]>', $test);
    }

    public function testConditionalScriptWidthIndentation()
    {
        $this->helper->headScript()->appendFile('/js/foo.js', 'text/javascript', array('conditional' => 'lt IE 7'));
        $this->helper->headScript()->setIndent(4);
        $test = $this->helper->headScript()->toString();
        $this->assertStringContainsStringIgnoringCase('    <!--[if lt IE 7]>', $test);
    }

    /**
     * @group ZF-5435
     */
    public function testContainerMaintainsCorrectOrderOfItems()
    {

        $this->helper->offsetSetFile(1, 'test1.js');
        $this->helper->offsetSetFile(20, 'test2.js');
        $this->helper->offsetSetFile(10, 'test3.js');
        $this->helper->offsetSetFile(5, 'test4.js');


        $test = $this->helper->toString();

        $expected = '<script type="text/javascript" src="test1.js"></script>' . PHP_EOL
                  . '<script type="text/javascript" src="test4.js"></script>' . PHP_EOL
                  . '<script type="text/javascript" src="test3.js"></script>' . PHP_EOL
                  . '<script type="text/javascript" src="test2.js"></script>';

        $this->assertEquals($expected, $test);
    }
    
    /**
     * @group ZF-12048
     */
    public function testSetFileStillOverwritesExistingFilesWhenItsADuplicate()
    {
        $this->helper->appendFile('foo.js');
        $this->helper->appendFile('bar.js');
        $this->helper->setFile('foo.js');
        
        $expected = '<script type="text/javascript" src="foo.js"></script>';
        $test = $this->helper->toString();
        $this->assertEquals($expected, $test);
    }

    /**
     * @group ZF-12287
     */
    public function testConditionalWithAllowArbitraryAttributesDoesNotIncludeConditionalScript()
    {
        $this->helper->setAllowArbitraryAttributes(true);
        $this->helper->appendFile(
            '/js/foo.js', 'text/javascript', array('conditional' => 'lt IE 7')
        );
        $test = $this->helper->toString();

        $this->assertStringNotContainsStringIgnoringCase('conditional', $test);
    }

    /**
     * @group ZF-12287
     */
    public function testNoEscapeWithAllowArbitraryAttributesDoesNotIncludeNoEscapeScript()
    {
        $this->helper->setAllowArbitraryAttributes(true);
        $this->helper->appendScript(
            '// some script', 'text/javascript', array('noescape' => true)
        );
        $test = $this->helper->toString();

        $this->assertStringNotContainsStringIgnoringCase('noescape', $test);
    }

    /**
     * @group ZF-12287
     */
    public function testNoEscapeDefaultsToFalse()
    {
        $this->helper->appendScript(
            '// some script' . PHP_EOL, 'text/javascript', array()
        );
        $test = $this->helper->toString();

        $this->assertStringContainsStringIgnoringCase('//<!--', $test);
        $this->assertStringContainsStringIgnoringCase('//-->', $test);
    }

    /**
     * @group ZF-12287
     */
    public function testNoEscapeTrue()
    {
        $this->helper->appendScript(
            '// some script' . PHP_EOL, 'text/javascript', array('noescape' => true)
        );
        $test = $this->helper->toString();

        $this->assertStringNotContainsStringIgnoringCase('//<!--', $test);
        $this->assertStringNotContainsStringIgnoringCase('//-->', $test);
    }

    /**
     * @group GH-515
     */
    public function testConditionalScriptNoIE()
    {
        $this->helper->setAllowArbitraryAttributes(true);
        $this->helper->appendFile(
            '/js/foo.js', 'text/javascript', array('conditional' => '!IE')
        );
        $test = $this->helper->toString();
        $this->assertStringContainsStringIgnoringCase('<!--[if !IE]><!--><', $test);
        $this->assertStringContainsStringIgnoringCase('<!--<![endif]-->', $test);
    }
}

// Call Zend_View_Helper_HeadScriptTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "Zend_View_Helper_HeadScriptTest::main") {
    Zend_View_Helper_HeadScriptTest::main();
}
