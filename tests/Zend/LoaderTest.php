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
 * @package    Zend_Loader
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

// Call Zend_LoaderTest::main() if this source file is executed directly.
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_LoaderTest::main');
}

/**
 * Zend_Loader
 */
require_once 'Zend/Loader.php';

/**
 * Zend_Loader_Autoloader
 */
require_once 'Zend/Loader/Autoloader.php';

/**
 * @category   Zend
 * @package    Zend_Loader
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Loader
 */
class Zend_LoaderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Runs the test methods of this class.
     *
     * @return void
     */
    public static function main()
    {
        $suite  = new \PHPUnit\Framework\TestSuite('Zend_LoaderTest');
        $suite->run();
    }

    protected function setUp(): void
    {
        // Store original autoloaders
        $this->loaders = spl_autoload_functions();
        if (!is_array($this->loaders)) {
            // spl_autoload_functions does not return empty array when no
            // autoloaders registered...
            $this->loaders = [];
        }

        // Store original include_path
        $this->includePath = get_include_path();

        $this->error = null;
        $this->errorHandler = null;
        Zend_Loader_Autoloader::resetInstance();
    }

    protected function tearDown(): void
    {
        if ($this->errorHandler !== null) {
            restore_error_handler();
        }

        // Restore original autoloaders
        $loaders = spl_autoload_functions();
        if (is_array($loaders)) {
            foreach ($loaders as $loader) {
                spl_autoload_unregister($loader);
            }
        }

        if (is_array($this->loaders)) {
            foreach ($this->loaders as $loader) {
                spl_autoload_register($loader);
            }
        }

        // Retore original include_path
        set_include_path($this->includePath);

        // Reset autoloader instance so it doesn't affect other tests
        Zend_Loader_Autoloader::resetInstance();
    }

    public function setErrorHandler()
    {
        set_error_handler([$this, 'handleErrors'], E_USER_NOTICE);
        $this->errorHandler = true;
    }

    public function handleErrors($errno, $errstr)
    {
        $this->error = $errstr;
    }

    /**
     * Tests that a class can be loaded from a well-formed PHP file
     */
    public function testLoaderClassValid()
    {
        $this->expectNotToPerformAssertions();

        $dir = implode(DIRECTORY_SEPARATOR, [dirname(__FILE__), '_files', '_testDir1']);

        Zend_Loader::loadClass('Class1', $dir);
    }

    public function testLoaderInterfaceViaLoadClass()
    {
        $this->expectNotToPerformAssertions();
        try {
            Zend_Loader::loadClass('Zend_Controller_Dispatcher_Interface');
        } catch (Zend_Exception $e) {
            self::fail('Loading interfaces should not fail');
        }
    }

    public function testLoaderLoadClassWithDotDir()
    {
        $this->expectNotToPerformAssertions();
        $dirs = ['.'];
        try {
            Zend_Loader::loadClass('Zend_Version', $dirs);
        } catch (Zend_Exception $e) {
            self::fail('Loading from dot should not fail');
        }
    }

    /**
     * Tests that an exception is thrown when a file is loaded but the
     * class is not found within the file
     */
    public function testLoaderClassNonexistent()
    {
        $dir = implode(DIRECTORY_SEPARATOR, [dirname(__FILE__), '_files', '_testDir1']);

        try {
            Zend_Loader::loadClass('ClassNonexistent', $dir);
            static::fail('Zend_Exception was expected but never thrown.');
        } catch (Zend_Exception $e) {
            static::assertMatchesRegularExpression('/file(.*)does not exist or class(.*)not found/i', $e->getMessage());
        }
    }

    /**
     * Tests that an exception is thrown if the $dirs argument is
     * not a string or an array.
     */
    public function testLoaderInvalidDirs()
    {
        try {
            Zend_Loader::loadClass('Zend_Invalid_Dirs', new stdClass());
            static::fail('Zend_Exception was expected but never thrown.');
        } catch (Zend_Exception $e) {
            static::assertEquals('Directory argument must be a string or an array', $e->getMessage());
        }
    }

    /**
     * Tests that a class can be loaded from the search directories.
     */
    public function testLoaderClassSearchDirs()
    {
        $this->expectNotToPerformAssertions();

        $dirs = [];
        foreach (['_testDir1', '_testDir2'] as $dir) {
            $dirs[] = implode(DIRECTORY_SEPARATOR, [dirname(__FILE__), '_files', $dir]);
        }

        // throws exception on failure
        Zend_Loader::loadClass('Class1', $dirs);
        Zend_Loader::loadClass('Class2', $dirs);
    }

    /**
     * Tests that a class locatedin a subdirectory can be loaded from the search directories
     */
    public function testLoaderClassSearchSubDirs()
    {
        $this->expectNotToPerformAssertions();

        $dirs = [];
        foreach (['_testDir1', '_testDir2'] as $dir) {
            $dirs[] = implode(DIRECTORY_SEPARATOR, [dirname(__FILE__), '_files', $dir]);
        }

        // throws exception on failure
        Zend_Loader::loadClass('Class1_Subclass2', $dirs);
    }

    /**
     * Tests that the security filter catches illegal characters.
     */
    public function testLoaderClassIllegalFilename()
    {
        try {
            Zend_Loader::loadClass('/path/:to/@danger');
            static::fail('Zend_Exception was expected but never thrown.');
        } catch (Zend_Exception $e) {
            static::assertMatchesRegularExpression('/security(.*)filename/i', $e->getMessage());
        }
    }

    /**
     * Tests that loadFile() finds a file in the include_path when $dirs is null
     */
    public function testLoaderFileIncludePathEmptyDirs()
    {
        $saveIncludePath = get_include_path();
        set_include_path(implode(PATH_SEPARATOR, [$saveIncludePath, implode(DIRECTORY_SEPARATOR, [dirname(__FILE__), '_files', '_testDir1'])]));

        static::assertTrue(Zend_Loader::loadFile('Class3.php', null));

        set_include_path($saveIncludePath);
    }

    /**
     * Tests that loadFile() finds a file in the include_path when $dirs is non-null
     * This was not working vis-a-vis ZF-1174
     */
    public function testLoaderFileIncludePathNonEmptyDirs()
    {
        $saveIncludePath = get_include_path();
        set_include_path(implode(PATH_SEPARATOR, [$saveIncludePath, implode(DIRECTORY_SEPARATOR, [dirname(__FILE__), '_files', '_testDir1'])]));

        static::assertTrue(Zend_Loader::loadFile('Class4.php', implode(PATH_SEPARATOR, ['foo', 'bar'])));

        set_include_path($saveIncludePath);
    }

    /**
     * Tests that isReadable works
     */
    public function testLoaderIsReadable()
    {
        static::assertTrue(Zend_Loader::isReadable(__FILE__));
        static::assertFalse(Zend_Loader::isReadable(__FILE__ . '.foobaar'));

        // test that a file in include_path gets loaded, see ZF-2985
        static::assertTrue(Zend_Loader::isReadable('Zend/Controller/Front.php'), get_include_path());
    }

    /**
     * Tests that autoload works for valid classes and interfaces
     */
    public function testLoaderAutoloadLoadsValidClasses()
    {
        $this->setErrorHandler();
        static::assertEquals('Zend_Db_Profiler_Exception', Zend_Loader::autoload('Zend_Db_Profiler_Exception'));
        static::assertStringContainsStringIgnoringCase('deprecated', $this->error);
        $this->error = null;
        static::assertEquals('Zend_Auth_Storage_Interface', Zend_Loader::autoload('Zend_Auth_Storage_Interface'));
        static::assertStringContainsStringIgnoringCase('deprecated', $this->error);
    }

    /**
     * Tests that autoload returns false on invalid classes
     */
    public function testLoaderAutoloadFailsOnInvalidClasses()
    {
        $this->setErrorHandler();
        static::assertFalse(Zend_Loader::autoload('Zend_FooBar_Magic_Abstract'));
        static::assertStringContainsStringIgnoringCase('deprecated', $this->error);
    }

    public function testLoaderRegisterAutoloadRegisters()
    {
        if (!function_exists('spl_autoload_register')) {
            static::markTestSkipped('spl_autoload not installed on this PHP installation');
        }

        $this->setErrorHandler();
        Zend_Loader::registerAutoload();
        static::assertStringContainsStringIgnoringCase('deprecated', $this->error);

        $autoloaders = spl_autoload_functions();
        $found       = false;
        foreach($autoloaders as $function) {
            if (is_array($function)) {
                $class = $function[0];
                if ($class == 'Zend_Loader_Autoloader') {
                    $found = true;
                    spl_autoload_unregister($function);
                    break;
                }
            }
        }
        static::assertTrue($found, 'Failed to register Zend_Loader_Autoloader with spl_autoload');
    }

    public function testLoaderRegisterAutoloadExtendedClassNeedsAutoloadMethod()
    {
        if (!function_exists('spl_autoload_register')) {
            static::markTestSkipped('spl_autoload not installed on this PHP installation');
        }

        $this->setErrorHandler();
        Zend_Loader::registerAutoload('Zend_Loader_MyLoader');
        static::assertStringContainsStringIgnoringCase('deprecated', $this->error);

        $autoloaders = spl_autoload_functions();
        $expected    = ['Zend_Loader_MyLoader', 'autoload'];
        $found       = false;
        foreach ($autoloaders as $function) {
            if ($expected == $function) {
                $found = true;
                break;
            }
        }
        static::assertFalse($found, 'Failed to register Zend_Loader_MyLoader::autoload() with spl_autoload');

        spl_autoload_unregister($expected);
    }

    public function testLoaderRegisterAutoloadExtendedClassWithAutoloadMethod()
    {
        if (!function_exists('spl_autoload_register')) {
            static::markTestSkipped('spl_autoload not installed on this PHP installation');
        }

        $this->setErrorHandler();
        Zend_Loader::registerAutoload('Zend_Loader_MyOverloader');
        static::assertStringContainsStringIgnoringCase('deprecated', $this->error);

        $autoloaders = spl_autoload_functions();
        $found       = false;
        foreach ($autoloaders as $function) {
            if (is_array($function)) {
                $class = $function[0];
                if ($class == 'Zend_Loader_Autoloader') {
                    $found = true;
                    break;
                }
            }
        }
        static::assertTrue($found, 'Failed to register Zend_Loader_Autoloader with spl_autoload');

        $autoloaders = Zend_Loader_Autoloader::getInstance()->getAutoloaders();
        $found       = false;
        $expected    = ['Zend_Loader_MyOverloader', 'autoload'];
        static::assertTrue(in_array($expected, $autoloaders, true), 'Failed to register My_Loader_MyOverloader with Zend_Loader_Autoloader: ' . var_export($autoloaders, 1));

        // try to instantiate a class that is known not to be loaded
        $obj = new Zend_Loader_AutoloadableClass();

        // now it should be loaded
        static::assertTrue(class_exists('Zend_Loader_AutoloadableClass'),
            'Expected Zend_Loader_AutoloadableClass to be loaded');

        // and we verify it is the correct type
        static::assertTrue($obj instanceof Zend_Loader_AutoloadableClass,
            'Expected to instantiate Zend_Loader_AutoloadableClass, got '.get_class($obj));

        spl_autoload_unregister($function);
    }

    public function testLoaderRegisterAutoloadFailsWithoutSplAutoload()
    {
        if (function_exists('spl_autoload_register')) {
            static::markTestSkipped('spl_autoload() is installed on this PHP installation; cannot test for failure');
        }

        try {
            Zend_Loader::registerAutoload();
            static::fail('registerAutoload should fail without spl_autoload');
        } catch (Zend_Exception $e) {
        }
    }

    public function testLoaderRegisterAutoloadInvalidClass()
    {
        if (!function_exists('spl_autoload_register')) {
            static::markTestSkipped('spl_autoload() not installed on this PHP installation');
        }

        $this->setErrorHandler();
        try {
            Zend_Loader::registerAutoload('stdClass');
            static::fail('registerAutoload should fail without spl_autoload');
        } catch (Zend_Exception $e) {
            static::assertEquals('The class "stdClass" does not have an autoload() method', $e->getMessage());
            static::assertStringContainsStringIgnoringCase('deprecated', $this->error);
        }
    }

    public function testLoaderUnregisterAutoload()
    {
        if (!function_exists('spl_autoload_register')) {
            static::markTestSkipped('spl_autoload() not installed on this PHP installation');
        }

        $this->setErrorHandler();
        Zend_Loader::registerAutoload('Zend_Loader_MyOverloader');
        static::assertStringContainsStringIgnoringCase('deprecated', $this->error);

        $expected    = ['Zend_Loader_MyOverloader', 'autoload'];
        $autoloaders = Zend_Loader_Autoloader::getInstance()->getAutoloaders();
        static::assertTrue(in_array($expected, $autoloaders, true), 'Failed to register autoloader');

        Zend_Loader::registerAutoload('Zend_Loader_MyOverloader', false);
        $autoloaders = Zend_Loader_Autoloader::getInstance()->getAutoloaders();
        static::assertFalse(in_array($expected, $autoloaders, true), 'Failed to unregister autoloader');

        foreach (spl_autoload_functions() as $function) {
            if (is_array($function)) {
                $class = $function[0];
                if ($class == 'Zend_Loader_Autoloader') {
                    spl_autoload_unregister($function);
                    break;
                }
            }
        }
    }

    /**
     * @group ZF-6605
     */
    public function testRegisterAutoloadShouldEnableZendLoaderAutoloaderAsFallbackAutoloader()
    {
        if (!function_exists('spl_autoload_register')) {
            static::markTestSkipped('spl_autoload() not installed on this PHP installation');
        }

        $this->setErrorHandler();
        Zend_Loader::registerAutoload();
        static::assertStringContainsStringIgnoringCase('deprecated', $this->error);

        $autoloader = Zend_Loader_Autoloader::getInstance();
        static::assertTrue($autoloader->isFallbackAutoloader());

        foreach (spl_autoload_functions() as $function) {
            if (is_array($function)) {
                $class = $function[0];
                if ($class == 'Zend_Loader_Autoloader') {
                    spl_autoload_unregister($function);
                    break;
                }
            }
        }
    }

    /**
     * @group ZF-8200
     */
    public function testLoadClassShouldAllowLoadingPhpNamespacedClasses()
    {
        $this->expectNotToPerformAssertions();

        if (version_compare(PHP_VERSION, '5.3.0') < 0) {
            static::markTestSkipped('PHP < 5.3.0 does not support namespaces');
        }
        Zend_Loader::loadClass('\Zfns\Foo', [dirname(__FILE__) . '/Loader/_files']);
    }

    /**
     * @group ZF-7271
     * @group ZF-8913
     */
    public function testIsReadableShouldHonorStreamDefinitions()
    {
        if (version_compare(PHP_VERSION, '5.3.0', '<')) {
            static::markTestSkipped();
        }

        $pharFile = dirname(__FILE__) . '/Loader/_files/Zend_LoaderTest.phar';
        $phar     = new Phar($pharFile, 0, 'zlt.phar');
        $incPath = 'phar://zlt.phar'
                 . PATH_SEPARATOR . $this->includePath;
        set_include_path($incPath);
        static::assertTrue(Zend_Loader::isReadable('User.php'));
        unset($phar);
    }

    /**
     * @group ZF-8913
     */
    public function testIsReadableShouldNotLockWhenTestingForNonExistantFileInPhar()
    {
        if (version_compare(PHP_VERSION, '5.3.0', '<')) {
            static::markTestSkipped();
        }

        $pharFile = dirname(__FILE__) . '/Loader/_files/Zend_LoaderTest.phar';
        $phar     = new Phar($pharFile, 0, 'zlt.phar');
        $incPath = 'phar://zlt.phar'
                 . PATH_SEPARATOR . $this->includePath;
        set_include_path($incPath);
        static::assertFalse(Zend_Loader::isReadable('does-not-exist'));
        unset($phar);
    }

    /**
     * @group ZF-7271
     */
    public function testExplodeIncludePathProperlyIdentifiesStreamSchemes()
    {
        if (PATH_SEPARATOR != ':') {
            static::markTestSkipped();
        }
        $path = 'phar://zlt.phar:/var/www:.:filter://[a-z]:glob://*';
        $paths = Zend_Loader::explodeIncludePath($path);
        static::assertSame([
            'phar://zlt.phar',
            '/var/www',
            '.',
            'filter://[a-z]',
            'glob://*',
        ], $paths);
    }

    /**
     * @group ZF-9100
     */
    public function testIsReadableShouldReturnTrueForAbsolutePaths()
    {
        set_include_path(dirname(__FILE__) . '../../');
        $path = dirname(__FILE__);
        static::assertTrue(Zend_Loader::isReadable($path));
    }

    /**
     * @group ZF-9263
     * @group ZF-9166
     * @group ZF-9306
     */
    public function testIsReadableShouldFailEarlyWhenProvidedInvalidWindowsAbsolutePath()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) != 'WIN') {
            static::markTestSkipped('Windows-only test');
        }
        $path = 'C:/this/file/should/not/exist.php';
        static::assertFalse(Zend_Loader::isReadable($path));
    }

    /**
     * In order to play nice with spl_autoload, an autoload callback should
     * *not* emit errors (exceptions are okay). ZF-2923 requests that this
     * behavior be applied, which counters the previous request in ZF-2463.
     *
     * As it is, the new behavior *will* hide parse and other errors. However,
     * a fatal error *will* be raised in such situations, which is as
     * appropriate or more appropriate than raising an exception.
     *
     * NOTE: Removed from test suite, as autoload functionality in Zend_Loader
     * is now deprecated.
     *
     * @see    http://framework.zend.com/issues/browse/ZF-2463
     * @group  ZF-2923
     * @return void
    public function testLoaderAutoloadShouldHideParseError()
    {
        if (isset($_SERVER['OS'])  &&  strstr($_SERVER['OS'], 'Win')) {
            $this->markTestSkipped(__METHOD__ . ' does not work on Windows');
        }
        $command = 'php -d include_path='
            . escapeshellarg(get_include_path())
            . ' Zend/Loader/AutoloadDoesNotHideParseError.php 2>&1';
        $output = shell_exec($command);
        $this->assertTrue(empty($output));
    }
     */
}

// Call Zend_LoaderTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD === 'Zend_LoaderTest::main') {
    Zend_LoaderTest::main();
}
