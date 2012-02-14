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
 * @package    Zend_Form
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * Zend_Form_DisplayGroup
 * 
 * @category   Zend
 * @package    Zend_Form
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: DisplayGroup.php 10406 2008-07-25 15:13:05Z matthew $
 */
class Base_Form_DisplayDivGroup extends Zend_Form_DisplayGroup
{
    public function __construct($name, Zend_Loader_PluginLoader $loader, $options = null)
    {
    	parent::__construct($name, $loader, $options);
    	$this->clearDecorators();
    	$this->addDecorator('FormElements')
    	->addDecorator('HtmlTag', array('tag'=>'div'));
    }
}
