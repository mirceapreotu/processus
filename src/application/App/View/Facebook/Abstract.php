<?php
/**
 * App_View_Facebook_Abstract
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_View_Facebook
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * App_View_Facebook_Abstract
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_View_Facebook
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */


class App_View_Facebook_Abstract
{


    /**
     * @var string|null
     */
    protected $_viewName;

    /**
    * @var App_Ctrl_Facebook_Abstract
    */
    protected $_controller;

    /**
     * @var string
     */
    protected $_templateFilename = "View.tpl.php";

    /**
     * @var bool
     */
    protected $_isRendered;


    /**
     * @throws Exception
     * @return string
     */
    public function getViewName()
    {
        if (Lib_Utils_String::isEmpty($this->_viewName)) {
            $class = get_class($this);
            $viewName = explode("_", $class);
            $viewName = array_pop($viewName);
            $this->_viewName = $viewName;
        }
        if (Lib_Utils_String::isEmpty($this->_viewName)) {
            throw new Exception(
                "Method returns invalid result at "
                        .__METHOD__. "for ".get_class($this)
            );
        }

        return $this->_viewName;
    }


    /**
     * @return string
     */
    public function getTemplateBasename()
    {
        $filename = $this->getTemplateFilename();
        $basename = "".trim(basename("".$filename));
        $result = Lib_Utils_String::removePostfix($basename, ".php", true);
        return $result;
    }


    /**
     * @return App_Ctrl_Facebook_Abstract
     */
    public function getController()
    {
        return $this->_controller;
    }


    /**
     * @return string
     */
    public function getTemplateFilename()
    {
        $file = $this->_templateFilename;
        return $file;
    }

    /**
     * @param  string $value
     * @return void
     */
    public function setTemplateFilename($value)
    {
        $this->_templateFilename = $value;
    }

    /**
     * @return string
     */
    public function getTemplateLocation()
    {
        $file = $this->getTemplateFilename();
        $location = $this->getController()
                ->getApplication()
                ->getSrcPath("view/Facebook/".$this->getViewName()."/".$file);
        return $location;
    }



    /**
     * @return void
     */
    public function onBeforeRender()
    {
        // your hooks here
    }




    /**
     * @param App_Ctrl_Facebook_Abstract
     * @return void
     */
    public function render(App_Ctrl_Facebook_Abstract $controller)
    {
        $this->_controller = $controller;
        if ($this->_isRendered === true) {
            throw new Exception(
                "View ".get_class($this)." already rendered at "
                        .__METHOD__." for ".get_class($this)
            );
        }

        $this->onBeforeRender();
        $this->_isRendered = true;
        
        ob_start();

        // the template

        $content = "";

        try {
            include $this->getTemplateLocation();
            $content = ob_get_contents();
            ob_clean();

        } catch(Exception $e) {
            $content = ob_get_contents();
            ob_clean();
            throw $e;                
        }

        echo $content;


    }



}

