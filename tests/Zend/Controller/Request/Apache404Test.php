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
 * @package    Zend_Controller
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

// Call Zend_Controller_Request_Apache404Test::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "Zend_Controller_Request_Apache404Test::main");
}


require_once 'Zend/Controller/Request/Apache404.php';

/**
 * Test class for Zend_Controller_Request_Apache404.
 * Generated by PHPUnit_Util_Skeleton on 2007-06-25 at 08:20:40.
 *
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Controller
 * @group      Zend_Controller_Request
 */
class Zend_Controller_Request_Apache404Test extends \PHPUnit\Framework\TestCase
{
    /**
     * Copy of $_SERVER
     * @var array
     */
    protected $_server;

    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main()
    {

        $suite  = new \PHPUnit\Framework\TestSuite("Zend_Controller_Request_Apache404Test");
        $suite->run();
    }

    protected function setUp(): void
    {
        $this->_server = $_SERVER;
    }

    protected function tearDown(): void
    {
        $_SERVER = $this->_server;
    }

    public function testRedirectUrlSelectedOverRequestUri()
    {
        $_SERVER['REDIRECT_URL'] = '/foo/bar';
        $_SERVER['REQUEST_URI']  = '/baz/bat';

        $request = new Zend_Controller_Request_Apache404();
        $requestUri = $request->getRequestUri();
        $this->assertEquals('/foo/bar', $requestUri);
    }

    /**
     * @group ZF-3057
     * @group ZF-9776
     */
    public function testRedirectQueryStringShouldBeParsedIntoGetVars()
    {
        $_SERVER['REDIRECT_URL']         = '/foo/bar';
        $_SERVER['REDIRECT_QUERY_STRING'] = 'baz=bat&bat=delta';
        $_SERVER['REQUEST_URI']          = '/baz/bat';

        $request = new Zend_Controller_Request_Apache404();
        $requestUri = $request->getRequestUri();
        $this->assertEquals('/foo/bar', $requestUri);
        $this->assertSame(array('baz' => 'bat', 'bat' => 'delta'), $request->getQuery());
    }
}

// Call Zend_Controller_Request_Apache404Test::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "Zend_Controller_Request_Apache404Test::main") {
    Zend_Controller_Request_Apache404Test::main();
}
