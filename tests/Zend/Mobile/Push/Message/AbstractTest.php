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
 * @package    Zend_Mobile_Push_Message
 * @subpackage Push
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id $
 */

require_once 'Zend/Mobile/Push/Message/Abstract.php';

/**
 * @category   Zend
 * @package    Zend_Mobile_Push_Message
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Mobile
 */
class Zend_Mobile_Push_Message_AbstractTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        $this->msg = new Zend_Mobile_Push_Message_AbstractProxy();
    }

    public function testSetToken()
    {
        $token = 'a-token!';
        $ret = $this->msg->setToken($token);
        $this->assertEquals($this->msg, $ret);
        $this->assertEquals($token, $this->msg->getToken());
    }

    public function testSetTokenThrowsExceptionOnNonStringToken()
    {
        $this->expectException(Zend_Mobile_Push_Message_Exception::class);
        $this->msg->setToken(array('dummy'));
    }

    public function testSetId()
    {
        $id = 'wahooo';
        $ret = $this->msg->setId($id);
        $this->assertEquals($this->msg, $ret);
        $this->assertEquals($id, $this->msg->getId());
    }

    public function testSetIdThrowsExceptionOnNonScalar()
    {
        $this->expectException(Zend_Mobile_Push_Message_Exception::class);
        $this->msg->setId(array('foo'));
    }

    public function testSetOptions()
    {
        $token = 'token';
        $id = 'id';

        $ret = $this->msg->setOptions(array(
            'id' => $id,
            'token' => $token
        ));
        $this->assertEquals($this->msg, $ret);
        $this->assertEquals($token, $this->msg->getToken());
        $this->assertEquals($id, $this->msg->getId());
    }

    public function testSetOptionsThrowsExceptionOnMissingMethod()
    {
        $this->expectException(Zend_Mobile_Push_Message_Exception::class);
        $this->msg->setOptions(array(
            'thisMethodDoesNotExist' => 'value'
        ));
    }

    public function testValidateReturnsTrue()
    {
        $this->assertTrue($this->msg->validate());
    }
}

class Zend_Mobile_Push_Message_AbstractProxy extends Zend_Mobile_Push_Message_Abstract
{
    
}
