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
 * @package    Zend_Tag
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_Tag_AllTests::main');
}

require_once 'Zend/Tag/ItemTest.php';
require_once 'Zend/Tag/ItemListTest.php';
require_once 'Zend/Tag/Cloud/AllTests.php';

/**
 * @category   Zend
 * @package    Zend_Tag
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Tag
 */
class Zend_Tag_AllTests
{
    public static function main()
    {
        self::suite()->run();
    }

    public static function suite()
    {
        $suite = new \PHPUnit\Framework\TestSuite('Zend Framework - Zend_Tag');

        $suite->addTestSuite('Zend_Tag_ItemTest');
        $suite->addTestSuite('Zend_Tag_ItemListTest');
        $suite->addTest(Zend_Tag_Cloud_AllTests::suite());

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Zend_Tag_AllTests::main') {
    Zend_Tag_AllTests::main();
}
