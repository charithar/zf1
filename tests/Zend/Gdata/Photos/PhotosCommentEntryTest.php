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
 * @package    Zend_Gdata_Photos
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id $
 */

require_once 'Zend/Gdata/Photos.php';
require_once 'Zend/Gdata/Photos/CommentEntry.php';
require_once 'Zend/Http/Client.php';
require_once 'Zend/Http/Client/Adapter/Test.php';

/**
 * @category   Zend
 * @package    Zend_Gdata_Photos
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Gdata
 * @group      Zend_Gdata_Photos
 */
class Zend_Gdata_Photos_PhotosCommentEntryTest extends \PHPUnit\Framework\TestCase
{

    protected $commentEntry = null;

    /**
      * Called before each test to setup any fixtures.
      */
    protected function setUp(): void
    {
        $commentEntryText = file_get_contents(
                '_files/TestCommentEntry.xml',
                true);
        $this->commentEntry = new Zend_Gdata_Photos_CommentEntry($commentEntryText);
    }

    /**
      * Verify that a given property is set to a specific value
      * and that the getter and magic variable return the same value.
      *
      * @param object $obj The object to be interrogated.
      * @param string $name The name of the property to be verified.
      * @param object $value The expected value of the property.
      */
    protected function verifyProperty($obj, $name, $value)
    {
        $propName = $name;
        $propGetter = "get" . ucfirst($name);

        $this->assertEquals($obj->$propGetter(), $obj->$propName);
        $this->assertEquals($value, $obj->$propGetter());
    }

    /**
      * Verify that a given property is set to a specific value
      * and that the getter and magic variable return the same value.
      *
      * @param object $obj The object to be interrogated.
      * @param string $name The name of the property to be verified.
      * @param string $secondName 2nd level accessor function name
      * @param object $value The expected value of the property.
      */
    protected function verifyProperty2($obj, $name, $secondName, $value)
    {
        $propName = $name;
        $propGetter = "get" . ucfirst($name);
        $secondGetter = "get" . ucfirst($secondName);

        $this->assertEquals($obj->$propGetter(), $obj->$propName);
        $this->assertEquals($value, $obj->$propGetter()->$secondGetter());
    }

    /**
      * Verify that a given property is set to a specific value,
      * that it keeps that value when set using the setter,
      * and that the getter and magic variable return the same value.
      *
      * @param object $obj The object to be interrogated.
      * @param string $name The name of the property to be verified.
      * @param string $secondName 2nd level accessor function name
      * @param object $value The expected value of the property.
      */
    protected function verifyProperty3($obj, $name, $secondName, $value)
    {
        $propName = $name;
        $propGetter = "get" . ucfirst($name);
        $propSetter = "set" . ucfirst($name);
        $secondGetter = "get" . ucfirst($secondName);
        $secondSetter = "set" . ucfirst($secondName);

        $this->assertEquals($obj->$propGetter(), $obj->$propName);
        $obj->$propSetter($obj->$propName);
        $this->assertEquals($value, $obj->$propGetter()->$secondGetter());
    }

    /**
      * Check for the existence of an <atom:id> and verify that it contains
      * the expected value.
      */
    public function testId()
    {
        $entry = $this->commentEntry;

        // Assert that the entry's ID is correct
        $this->assertTrue($entry->getId() instanceof Zend_Gdata_App_Extension_Id);
        $this->verifyProperty2($entry, "id", "text",
                "http://picasaweb.google.com/data/entry/api/user/sample.user/albumid/1/photoid/100/commentid/5");
    }

    /**
      * Check for the existence of an <atom:author> and verify that they
      * contain the expected values.
      */
    public function testAuthor()
    {
        $entry = $this->commentEntry;

        // Assert that the entry's author is correct
        $entryAuthor = $entry->getAuthor();
        $this->assertEquals($entryAuthor, $entry->author);
        $this->assertEquals(1, count($entryAuthor));
        $this->assertTrue($entryAuthor[0] instanceof Zend_Gdata_App_Extension_Author);
        $this->verifyProperty2($entryAuthor[0], "name", "text", "sample");
        $this->assertTrue($entryAuthor[0]->getUri() instanceof Zend_Gdata_App_Extension_Uri);
        $this->verifyProperty2($entryAuthor[0], "uri", "text", "http://picasaweb.google.com/sample.user");
    }

    /**
      * Check for the existence of an <atom:published> and verify that it contains
      * the expected value.
      */
    public function testPublished()
    {
        $entry = $this->commentEntry;

        // Assert that the photo entry has an Atom Published object
        $this->assertTrue($entry->getPublished() instanceof Zend_Gdata_App_Extension_Published);
        $this->verifyProperty2($entry, "published", "text", "2007-09-21T18:22:53.000Z");
    }

    /**
      * Check for the existence of an <atom:updated> and verify that it contains
      * the expected value.
      */
    public function testUpdated()
    {
        $entry = $this->commentEntry;

        // Assert that the entry's updated date is correct
        $this->assertTrue($entry->getUpdated() instanceof Zend_Gdata_App_Extension_Updated);
        $this->verifyProperty2($entry, "updated", "text",
                "2007-09-21T18:22:53.000Z");
    }

    /**
      * Check for the existence of an <atom:title> and verify that it contains
      * the expected value.
      */
    public function testTitle()
    {
        $entry = $this->commentEntry;

        // Assert that the entry's title is correct
        $this->assertTrue($entry->getTitle() instanceof Zend_Gdata_App_Extension_Title);
        $this->verifyProperty2($entry, "title", "text", "sample");
    }

    /**
      * Check for the existence of an <atom:content> and verify that it contains
      * the expected value.
      */
    public function testContent()
    {
        $entry = $this->commentEntry;

        // Assert that the entry's title is correct
        $this->assertTrue($entry->getContent() instanceof Zend_Gdata_App_Extension_Content);
        $this->verifyProperty2($entry, "content", "text", "test comment");
    }

    /**
      * Check for the existence of an <gphoto:id> and verify that it contains
      * the expected value.
      */
    public function testGphotoId()
    {
        $entry = $this->commentEntry;

        // Assert that the entry's title is correct
        $this->assertTrue($entry->getGphotoId() instanceof Zend_Gdata_Photos_Extension_Id);
        $this->verifyProperty2($entry, "gphotoId", "text",
                "5");
        $this->verifyProperty3($entry, "gphotoId", "text",
                "5");
    }

    /**
      * Check for the existence of an <gphoto:photoid> and verify that it contains
      * the expected value.
      */
    public function testGphotoPhotoId()
    {
        $entry = $this->commentEntry;

        // Assert that the entry's title is correct
        $this->assertTrue($entry->getGphotoPhotoId() instanceof Zend_Gdata_Photos_Extension_PhotoId);
        $this->verifyProperty2($entry, "gphotoPhotoId", "text",
                "100");
        $this->verifyProperty3($entry, "gphotoPhotoId", "text",
                "100");
    }

}
