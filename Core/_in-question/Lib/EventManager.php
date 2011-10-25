<?php
/**
 * Lib_EventManager Class
 *
 * Manages a list of event types, each with a list of linked listeners.
 *
 * @package Lib
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_EventManager
 *
 * @package Lib
 *
 * @abstract
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 * @todo class is unfinished!
 *
 */
class Lib_EventManager
{

	/**
	 * 
	 * @var object
	 */
	protected $_subject = null;

	/**
	 * 
	 * @var array
	 */
	protected $_listeners = array();
	
	/**
	 * Attaches a listener for a specific event type
	 * 
	 * @param int $eventIdentifier
	 * @param Lib_Listener $listener
	 */
    function attachListener($eventIdentifier, Lib_Listener $listener)
    {
    	$dummy = $eventIdentifier;
	    $dummy = $listener;
    }

	/**
	 * Detaches a listener for a specific event type
	 * 
	 * @param int $eventIdentifier
	 * @param Lib_Listener $listener
	 */
    function detachListener($eventIdentifier, Lib_Listener $listener)
    {
	    $dummy = $eventIdentifier;
	    $dummy = $listener;
    }

	/**
	 * Lists all attached listeners
	 * 
	 * @return array
	 */
    function listListeners()
    {
    	// foreach( ..) $event->listListeners()
    }

   	/**
	 * Lists the attached listeners for a specific event Type 
	 * 
	 * For debug purposes
	 * 
	 * @return array
	 */
    function listListenersByEventType($eventType)
    {
    	// foreach( ..) $event->listListeners()
	    $dummy = $eventType;
	    $dummy = null;
    }
    
	/**
	 * Notifies the observers
	 * 
	 * @param int $eventIdentifier
	 */
    function fireEvent($eventIdentifier)
    {
    	// this->_listeners[$eventIdentifier]->fireEvent(,$this->_subject);
		$dummy = $eventIdentifier;
        $dummy = null;
    }

	/**
	 * @param string $subject
	 * @return void
	 */
    public function __construct($subject)
    {
    	$this->_subject = $subject;
    }
}
