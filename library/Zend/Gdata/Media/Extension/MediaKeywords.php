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
 * @package    Zend_Gdata
 * @subpackage Media
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @see Zend_Gdata_App_Extension
 */
//require_once 'Zend/Gdata/App/Extension.php';

/**
 * Represents the media:keywords element
 *
 * @category   Zend
 * @package    Zend_Gdata
 * @subpackage Media
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Gdata_Media_Extension_MediaKeywords extends Zend_Gdata_Extension
{
    protected $_rootElement = 'keywords';
    protected $_rootNamespace = 'media';

    /**
     * Constructs a new MediaKeywords element
     */
    public function __construct()
    {
        $this->registerAllNamespaces(Zend_Gdata_Media::$namespaces);
        parent::__construct();
    }

}
