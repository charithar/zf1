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
 * @package    Zend_Mobile
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: AllTests.php $
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_Mobile_Push_Response_AllTests::main');
}

require_once 'Zend/Mobile/Push/Response/GcmTest.php';

/**
 * @category   Zend
 * @package    Zend_Mobile_Push_Response
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Mobile
 */
class Zend_Mobile_Push_Response_AllTests
{
    public static function main()
    {
        self::suite()->run();
    }

    public static function suite()
    {
        $suite = new \PHPUnit\Framework\TestSuite('Zend Framework - Zend_Mobile_Push_Response');

        $suite->addTest(Zend_Mobile_Push_Response_Mpns_AllTests::suite());
         
        $suite->addTestSuite('Zend_Mobile_Push_Response_GcmTest');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Zend_Mobile_Push_Response_AllTests::main') {
    Zend_Mobile_Push_Response_AllTests::main();
}
