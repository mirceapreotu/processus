<?php
/**
 * Lib_Event Class
 *
 * @package Lib
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Event
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
class Lib_Event
{

	/**
	 * Array of listeners
	 *
	 * This property contains an array of delegates, which refer to callback
	 * functions that will be notified when the event is fired.
	 *
	 * @var ArrayList
	 * @access private
	 */
	private $_listeners;

	/**
	 * Event constructor
	 *
	 * Constructs the Event class.
	 *
	 * Assigns an empty {@link ArrayList} to {@link $listeners}
	 *
	 * @return void
	 * @access public
	 * @uses    ArrayList   List of delegates is implemented as an ArrayList.
	 */
	public function __construct()
	{
		$this->_listeners = new ArrayList();
	}

	/**
	 * Subscribe an observer to this event
	 *
	 * This method allows an observer to subscribe to a given event.
	 *
	 * @param   Delegate    $listener   New observer
	 *
	 * @return  void
	 * @access  public
	 */
	public function subscribe($listener)
	{
		$this->_listeners->add($listener);
	}

	/**
	 * Unsubscribe an observer
	 *
	 * This method allows an observer to unsubscribe from an event.
	 *
	 * @param   Delegate    $listener   Observer to unsubscribe
	 *
	 * @return  void
	 * @access  public
	 * @uses    Delegate    Holds a reference to the observer
	 */
	public function unsubscribe($listener)
	{
		if ($this->_listeners->contains($listener)) {
	
			$this->_listeners->remove($listener);
				
		} else {
				
			throw new ElementNotFoundException(
				'The given delegate was not found.'
			);
		}
	}

	/**
	 * Fire the event
	 *
	 * This method notifies all observers that the event was fired.
	 *
	 * @return void
	 * @access public
	 */
	public function fire()
	{
		$arguments = func_get_args();
		foreach ($this->_listeners as $listener) {
			
			call_user_func_array(array($listener, 'call'), $arguments);
		}
	}

	/**
	 * Get listeners
	 *
	 * This method allows to retrieve the array of listeners.
	 *
	 * @return array
	 * @access public
	 */
	public function getListeners()
	{
		return $this->_listeners;
	}

}
