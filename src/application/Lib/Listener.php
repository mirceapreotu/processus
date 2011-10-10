<?php
/**
 * Lib_Listener Class
 *
 * @package Lib
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Listener
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
 */
class Lib_Listener
{

    /**
     * Holds the callback
     *
     * This property holds the function callback.
     *
     * It is an array, where the first element is the object reference or class
     * name, and the second element is the method name.
     *
     * @access private
     * @var callback
     */
    private $_callback;

    /**
     * Constructor
     *
     * Constructs the Delegate class. Pass the callback function to this
     * method.
     *
     * @internal    This method assigns the specified callback to {@link
     *          $callback}
     *
     * @param   mixed   $object An object reference or class name defining the
     * class the method is in
     * @param   string  $method Name of the callback method
     *
     * @return  void
     * @access  public
     */
    public function __construct($object, $method)
    {
        $callback = array($object, $method);

        // Make sure the method is callable;
        // i.e., make sure it is a valid method
        if (!is_callable($callback)) {
        	
            throw new Exception('Invalid callback');
        }
        $this->_callback = $callback;
    }

    /**
     * Call the callback function
     *
     * This method is called by an {@link Lib_Event} to 
     * execute the given function.
     *
     * @param   mixed   $parameter,...  List of parameters to pass to the
     *                              function
     *
     * @return void
     * @access public
     */
    public function call()
    {
        $parameters = func_get_args();
        call_user_func_array($this->_callback, $parameters);
    }


}
