<?php
/**
 * App_Ctrl_Facebook_CanvasInvite
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_Ctrl_Facebook
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * App_Ctrl_Facebook_CanvasInvite
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_Ctrl_Facebook
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
 
class App_Ctrl_Facebook_CanvasInvite extends
    App_Ctrl_Facebook_CtrlInviteAbstract
{


     /**
     * override
     * @var string
     */
    protected $_pageType = Lib_Facebook_Config::ENVIRONMENT_TYPE_CANVAS;



    /**
     * @return App_View_Facebook_CanvasInvite
     */
    public function getView()
    {
        if (($this->_view instanceof App_View_Facebook_CanvasInvite) !== true) {
            $this->_view = $this->newView();
        }
        return $this->_view;
    }



    /**
     * @throws Exception
     * @return App_View_Facebook_CanvasInvite
     */

    public function newView()
    {

        $viewClass = $this->getViewClassName();

        if (class_exists($viewClass)!==true) {
            throw new Exception(
                "ViewClass does not exist! viewClass=".$viewClass." at "
                        .__METHOD__
            );
        }
        $view = new $viewClass();
        
        return $view;
    }





}


