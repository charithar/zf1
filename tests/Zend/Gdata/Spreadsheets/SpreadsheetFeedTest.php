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
 * @package    Zend_Gdata_Spreadsheets
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id $
 */

require_once 'Zend/Gdata/Spreadsheets.php';
require_once 'Zend/Http/Client.php';

/**
 * @category   Zend
 * @package    Zend_Gdata_Spreadsheets
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Gdata
 * @group      Zend_Gdata_Spreadsheets
 */
class Zend_Gdata_Spreadsheets_SpreadsheetFeedTest extends \PHPUnit\Framework\TestCase
{

    protected function setUp(): void
    {
        $this->sprFeed = new Zend_Gdata_Spreadsheets_SpreadsheetFeed(
                file_get_contents(dirname(__FILE__) . '/_files/TestDataSpreadsheetFeedSample1.xml'),
                true);
    }

    public function testToAndFromString()
    {
        $this->assertTrue(count($this->sprFeed->entries) == 1);
        foreach($this->sprFeed->entries as $entry)
        {
            $this->assertTrue($entry instanceof Zend_Gdata_Spreadsheets_SpreadsheetEntry);
        }

        $newSprFeed = new Zend_Gdata_Spreadsheets_SpreadsheetFeed();
        $doc = new DOMDocument();
        $doc->loadXML($this->sprFeed->saveXML());
        $newSprFeed->transferFromDom($doc->documentElement);

        $this->assertTrue(count($newSprFeed->entries) == 1);
        foreach($newSprFeed->entries as $entry)
        {
            $this->assertTrue($entry instanceof Zend_Gdata_Spreadsheets_SpreadsheetEntry);
        }
    }

}
