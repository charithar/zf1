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
 * @package    Zend_Locale
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_Locale_AllTests::main');
}

// define('TESTS_ZEND_LOCALE_BCMATH_ENABLED', false); // uncomment to disable use of bcmath extension by Zend_Date

require_once 'Zend/Locale/DataTest.php';
require_once 'Zend/Locale/FormatTest.php';
require_once 'Zend/Locale/MathTest.php';

// echo "BCMATH is ", Zend_Locale_Math::isBcmathDisabled() ? 'disabled':'not disabled', "\n";

/**
 * @category   Zend
 * @package    Zend_Locale
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Locale
 */
class Zend_Locale_AllTests
{
    public static function main()
    {
        if (defined('TESTS_ZEND_LOCALE_FORMAT_SETLOCALE') && TESTS_ZEND_LOCALE_FORMAT_SETLOCALE) {
            // run all tests in a special locale
            setlocale(LC_ALL, TESTS_ZEND_LOCALE_FORMAT_SETLOCALE);
        }

        self::suite()->run();
    }

    public static function suite()
    {
        $suite = new \PHPUnit\Framework\TestSuite('Zend Framework - Zend_Locale');

        $suite->addTestSuite('Zend_Locale_DataTest');
        $suite->addTestSuite('Zend_Locale_FormatTest');
        $suite->addTestSuite('Zend_Locale_MathTest');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Zend_Locale_AllTests::main') {
    Zend_Locale_AllTests::main();
}
