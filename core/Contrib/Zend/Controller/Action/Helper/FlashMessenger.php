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
 * @package    Zend_Controller
 * @subpackage Zend_Controller_Action_Helper
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @namespace
 */
namespace Zend\Controller\Action\Helper;
use Zend\Session, Zend\Stdlib\SplQueue;

/**
 * Flash Messenger - implement session-based messages
 *
 * @uses       \Zend\Controller\Action\Helper\AbstractHelper
 * @uses       \Zend\Session\Manager
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage Zend_Controller_Action_Helper
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class FlashMessenger extends AbstractHelper implements \IteratorAggregate, 
\Countable
{

    /**
     * $_messages - Messages from previous request
     *
     * @var array
     */
    protected static $_messages = array();

    /**
     * $_session - Zend_Session storage object
     *
     * @var \Zend\Session\Manager
     */
    protected static $_session = null;

    /**
     * $_messageAdded - Wether a message has been previously added
     *
     * @var boolean
     */
    protected static $_messageAdded = false;

    /**
     * $_namespace - Instance namespace, default is 'default'
     *
     * @var string
     */
    protected $_namespace = 'default';

    /**
     * __construct() - Instance constructor, needed to get iterators, etc
     *
     * @param  string $namespace
     * @return void
     */
    public function __construct ()
    {
        if (! self::$_session instanceof Session\Container) {
            self::$_session = new Session\Container($this->getName());
            
            // Should not modify the iterator while iterating; aggregate 
            // namespaces so they may be deleted after retrieving messages.
            $namespaces = array();
            foreach (self::$_session as $namespace => $messages) {
                self::$_messages[$namespace] = $messages;
                $namespaces[] = $namespace;
            }
            foreach ($namespaces as $namespace) {
                unset(self::$_session->{$namespace});
            }
        }
    }

    /**
     * postDispatch() - runs after action is dispatched, in this
     * case, it is resetting the namespace in case we have forwarded to a different
     * action, Flashmessage will be 'clean' (default namespace)
     *
     * @return \Zend\Controller\Action\Helper\FlashMessenger Provides a fluent interface
     */
    public function postDispatch ()
    {
        $this->resetNamespace();
        return $this;
    }

    /**
     * setNamespace() - change the namespace messages are added to, useful for
     * per action controller messaging between requests
     *
     * @param  string $namespace
     * @return \Zend\Controller\Action\Helper\FlashMessenger Provides a fluent interface
     */
    public function setNamespace ($namespace = 'default')
    {
        $this->_namespace = $namespace;
        return $this;
    }

    /**
     * resetNamespace() - reset the namespace to the default
     *
     * @return \Zend\Controller\Action\Helper\FlashMessenger Provides a fluent interface
     */
    public function resetNamespace ()
    {
        $this->setNamespace();
        return $this;
    }

    /**
     * addMessage() - Add a message to flash message
     *
     * @param  string $message
     * @return \Zend\Controller\Action\Helper\FlashMessenger Provides a fluent interface
     */
    public function addMessage ($message)
    {
        if (self::$_messageAdded === false) {
            self::$_session->setExpirationHops(1, null, true);
        }
        
        if (! isset(self::$_session->{$this->_namespace}) ||
         ! (self::$_session->{$this->_namespace} instanceof SplQueue)) {
            self::$_session->{$this->_namespace} = new SplQueue();
        }
        
        self::$_session->{$this->_namespace}->push($message);
        
        return $this;
    }

    /**
     * hasMessages() - Wether a specific namespace has messages
     *
     * @return boolean
     */
    public function hasMessages ()
    {
        return isset(self::$_messages[$this->_namespace]);
    }

    /**
     * getMessages() - Get messages from a specific namespace
     *
     * @return array
     */
    public function getMessages ()
    {
        if ($this->hasMessages()) {
            return self::$_messages[$this->_namespace]->toArray();
        }
        
        return array();
    }

    /**
     * Clear all messages from the previous request & current namespace
     *
     * @return boolean True if messages were cleared, false if none existed
     */
    public function clearMessages ()
    {
        if ($this->hasMessages()) {
            unset(self::$_messages[$this->_namespace]);
            return true;
        }
        
        return false;
    }

    /**
     * hasCurrentMessages() - check to see if messages have been added to current
     * namespace within this request
     *
     * @return boolean
     */
    public function hasCurrentMessages ()
    {
        return isset(self::$_session->{$this->_namespace});
    }

    /**
     * getCurrentMessages() - get messages that have been added to the current
     * namespace within this request
     *
     * @return array
     */
    public function getCurrentMessages ()
    {
        if ($this->hasCurrentMessages()) {
            return self::$_session->{$this->_namespace}->toArray();
        }
        
        return array();
    }

    /**
     * clear messages from the current request & current namespace
     *
     * @return boolean
     */
    public function clearCurrentMessages ()
    {
        if ($this->hasCurrentMessages()) {
            unset(self::$_session->{$this->_namespace});
            return true;
        }
        
        return false;
    }

    /**
     * getIterator() - complete the IteratorAggregate interface, for iterating
     *
     * @return ArrayObject
     */
    public function getIterator ()
    {
        if ($this->hasMessages()) {
            return new \ArrayObject($this->getMessages());
        }
        
        return new \ArrayObject();
    }

    /**
     * count() - Complete the countable interface
     *
     * @return int
     */
    public function count ()
    {
        if ($this->hasMessages()) {
            return count($this->getMessages());
        }
        
        return 0;
    }

    /**
     * Strategy pattern: proxy to addMessage()
     *
     * @param  string $message
     * @return void
     */
    public function direct ($message)
    {
        return $this->addMessage($message);
    }
}
