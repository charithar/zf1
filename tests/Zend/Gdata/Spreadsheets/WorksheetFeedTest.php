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
class Zend_Gdata_Spreadsheets_WorksheetFeedTest extends \PHPUnit\Framework\TestCase
{

    protected function setUp(): void
    {
        $this->wksFeed = new Zend_Gdata_Spreadsheets_WorksheetFeed(
                file_get_contents(dirname(__FILE__) . '/_files/TestDataWorksheetFeedSample1.xml'),
                true);
    }

    public function testToAndFromString()
    {
        $this->assertTrue(count($this->wksFeed->entries) == 1);
        foreach($this->wksFeed->entries as $entry)
        {
            $this->assertTrue($entry instanceof Zend_Gdata_Spreadsheets_WorksheetEntry);
        }

        $newWksFeed = new Zend_Gdata_Spreadsheets_WorksheetFeed();
        $doc = new DOMDocument();
        $doc->loadXML($this->wksFeed->saveXML());
        $newWksFeed->transferFromDom($doc->documentElement);

        $this->assertTrue(count($newWksFeed->entries) == 1);
        foreach($newWksFeed->entries as $entry)
        {
            $this->assertTrue($entry instanceof Zend_Gdata_Spreadsheets_WorksheetEntry);
        }
    }

}
