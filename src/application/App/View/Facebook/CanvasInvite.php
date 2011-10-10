<?php
/**
 * App_View_Facebook_CanvasInvite
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
 * App_View_Facebook_CanvasInvite
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

class App_View_Facebook_CanvasInvite extends
    App_View_Facebook_ViewInviteAbstract
{

     /**
     * @var array
     */
    protected $_config = array(
        "width" => 625,
        "action" => "&foo=bar",
        "method" => "POST",
        "type" => "Sony Oster-Mission",
        "content" => "Mach mit bei der Sony Oster-Mission. Der Sony Osterhase braucht Deine Hilfe! Seine Ostereier sind gestohlen und im Sony Universum verstreut worden. Mit der Sony Oster-Mission App kannst Du dem Osterhasen helfen, alle verlorenen Ostereier einzusammeln. Beweise Spürsinn, folge den täglichen Hinweisen und lass dich belohnen.",
        "choiceUrls" => array(
            array(
                "label"=>"yes",
                "url"=>"http://apps.facebook.com/sony-oster-mission/",// "choice.yes.php"
            ),
            array(
                "label"=>"no",
                "url"=>"http://www.facebook.com"
            ),
        ),

        "user_id" => "12345",
        "actiontext" =>"Lade deine Freunde ein!", // cant be empty!
		"showborder"=>true,
		"rows"=>3,
		"exclude_ids"=>"0",
		"cols"=>5,
		"max"=>20,
		"email_invite"=>false,
		"import_external_friends"=>false,
    );



    /**
     * @var string|null
     */
    protected $_viewName;




    /**
     * @var App_Ctrl_Facebook_CanvasInvite
     */
    protected $_controller;



    /**
     * @return App_Ctrl_Facebook_CanvasInvite
     */
    public function getController()
    {
        return $this->_controller;
    }




}

