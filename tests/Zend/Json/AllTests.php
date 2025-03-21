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
 * @package    Zend_Json
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_Json_AllTests::main');
}

require_once 'Zend/Json/JsonXMLTest.php';
require_once 'Zend/Json/ServerTest.php';
require_once 'Zend/Json/Server/AllTests.php';

/**
 * @category   Zend
 * @package    Zend_Json
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Json
 */
class Zend_Json_AllTests
{
    public static function main()
    {
        self::suite()->run();
    }

    public static function suite()
    {
        $suite = new \PHPUnit\Framework\TestSuite('Zend Framework - Zend_Json');

        $suite->addTestSuite('Zend_Json_JsonXMLTest');
        $suite->addTestSuite('Zend_Json_ServerTest');
        $suite->addTest(Zend_Json_Server_AllTests::suite());

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Zend_Json_AllTests::main') {
    Zend_Json_AllTests::main();
}
