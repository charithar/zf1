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
 * @package    Zend_Memory
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_Memory_AllTests::main');
}

require_once 'Zend/Memory/MemoryTest.php';
require_once 'Zend/Memory/ValueTest.php';
require_once 'Zend/Memory/MovableTest.php';
require_once 'Zend/Memory/LockedTest.php';
require_once 'Zend/Memory/AccessControllerTest.php';
require_once 'Zend/Memory/MemoryManagerTest.php';

/**
 * @category   Zend
 * @package    Zend_Memory
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Memory
 */
class Zend_Memory_AllTests
{
    public static function main()
    {
        self::suite()->run();
    }

    public static function suite()
    {
        $suite = new \PHPUnit\Framework\TestSuite('Zend Framework - Zend_Memory');

        $suite->addTestSuite('Zend_Memory_MemoryTest');
        $suite->addTestSuite('Zend_Memory_ValueTest');
        $suite->addTestSuite('Zend_Memory_Container_MovableTest');
        $suite->addTestSuite('Zend_Memory_Container_LockedTest');
        $suite->addTestSuite('Zend_Memory_Container_AccessControllerTest');
        $suite->addTestSuite('Zend_Memory_MemoryManagerTest');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Zend_Memory_AllTests::main') {
    Zend_Memory_AllTests::main();
}
