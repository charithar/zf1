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
 * @package    Zend_Service_Flickr
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_Service_Flickr_AllTests::main');
}

require_once 'Zend/Service/Flickr/OfflineTest.php';
require_once 'Zend/Service/Flickr/OnlineTest.php';

/**
 * @category   Zend
 * @package    Zend_Service_Flickr
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Service
 * @group      Zend_Service_Flickr
 */
class Zend_Service_Flickr_AllTests
{
    /**
     * Runs this test suite
     *
     * @return void
     */
    public static function main()
    {
        self::suite()->run();
    }

    /**
     * Creates and returns this test suite
     *
     * @return \PHPUnit\Framework\TestSuite
     */
    public static function suite()
    {
        $suite = new \PHPUnit\Framework\TestSuite('Zend Framework - Zend_Service_Flickr');

        $suite->addTestSuite('Zend_Service_Flickr_OfflineTest');
        if (defined('TESTS_ZEND_SERVICE_FLICKR_ONLINE_ENABLED') &&
            constant('TESTS_ZEND_SERVICE_FLICKR_ONLINE_ENABLED')) {
            $suite->addTestSuite('Zend_Service_Flickr_OnlineTest');
        } else {
            $suite->addTestSuite('Zend_Service_Flickr_OnlineTest_Skip');
        }

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Zend_Service_Flickr_AllTests::main') {
    Zend_Service_Flickr_AllTests::main();
}
