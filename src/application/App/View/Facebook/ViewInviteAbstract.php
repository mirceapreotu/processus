<?php
/**
 * App_View_Facebook_ViewInviteAbstract
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
 * App_View_Facebook_ViewInviteAbstract
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

class App_View_Facebook_ViewInviteAbstract
{


     /**
     * @var array
     */
    protected $_config = array(
        "width" => 625,
        "action" => "&foo=bar",
        "method" => "POST",
        "type" => "MYTYPE",
        "content" => "MYCONTENT",
        "choiceUrls" => array(
            array("label"=>"yes", "url"=>"choice.yes.php"),
            array("label"=>"no", "url"=>"http://www.facebook.com"),
        ),

        "user_id" => "12345",
        "actiontext" =>"ACTIONTEXT",
		"showborder"=>true,
		"rows"=>3,
		"exclude_ids"=>"0",
		"cols"=>5,
		"max"=>5,
		"email_invite"=>false,
		"import_external_friends"=>false,
    );



    /**
     * @var string|null
     */
    protected $_viewName;

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
     * @var App_Ctrl_Facebook_CtrlInviteAbstract
     */
    protected $_controller;



    /**
     * @return App_Ctrl_Facebook_CtrlInviteAbstract
     */
    public function getController()
    {
        return $this->_controller;
    }


    protected $_templateFilename = "View.tpl.php";

    /**
     * @return string
     */
    protected function _getTemplateFilename()
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
     * @return void
     */
    protected function _getTemplateLocation()
    {
        $file = $this->_getTemplateFilename();
        $location = $this->getController()
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


    protected $_isRendered;

    /**
     * @param App_Ctrl_Facebook_CtrlInviteAbstract
     * @return void
     */
    public function render(App_Ctrl_Facebook_CtrlInviteAbstract $controller)
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
        try {
            include $this->_getTemplateLocation();
            $content = ob_get_contents();
            ob_clean();
            echo $content;
        } catch(Exception $e) {
            $content = ob_get_contents();
            ob_clean();
            throw $e;                
        }



    }






    /**
     * @return array
     */
    public function getConfig()
    {
        if (is_array($this->_config)!==true) {
            $this->_config = array();
        }
        return $this->_config;
    }

    /**
     * @param  string $name
     * @return mixed|null|string
     */
    public function getConfigProperty($name)
    {

        $config = $this->getConfig();

        $value = null;
        if (isset($config[$name])) {
            $value = $config[$name];
        }




        return $value;
    }

    /**
     * @param  string $name
     * @return void
     */
    public function renderConfigProperty($name)
    {
        $value = $this->getConfigProperty($name);
        // ready to inject

        switch($name) {
            case "action": {

                $actionUrl = $this->getController()->getFriendSelectorInviteUrl();

                if (is_string($actionUrl)) {
                    $actionUrl = trim($actionUrl);
                    if (strpos($actionUrl,"?")===FALSE) {
                        $actionUrl.="?choiceComplete=1";
                    } else {
                        $actionUrl.="&choiceComplete=1";
                    }
                }
                if (is_string($value)) {
                    $value = trim($value);
                    $actionUrl .= $value;
                }

                if (is_string($actionUrl)) {
                    $actionUrl = htmlentities($actionUrl);
                }
                $value = $actionUrl;

                break;
            }

            default: {
                break;
            }
        }


        if (is_string($value)) {
            echo($value);
            return;
        }
        $value = json_encode($value);
        echo($value);
    }


}

