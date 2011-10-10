<?php
/**
 * Lib_Facebook_OpenGraph Class
 *
 * @package Lib
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Facebook_OpenGraph
 *
 *
 * @package Lib_Facebook
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */
class Lib_Facebook_OpenGraph
{

	/**
	 * 
	 * @var String
	 */
	protected $_redirectUrl;

	/**
	 * 
	 * @var String
	 */
	protected $_applicationId;
	
	/**
	 * 
	 * @var String
	 */
	protected $_applicationSecret;
	
	/**
	 * 
	 * @var String
	 */
	const OPENGRAPH_URI='https://graph.facebook.com';
	
	/**
	 * The locale to use when returning data, keep this to en_US!
	 * @var String
	 */
	protected $_locale = 'en_US';
	
	/**
	 * The security token for users ..
	 * 
	 * @var String
	 */
	protected $_token;
	
	/**
	 * Lib_Facebook_OpenGraph constructor
	 *
	 * Constructs the Lib_Facebook_OpenGraph class.
	 *
	 * @return void
	 * @access public
	 */
	public function __construct()
	{
		
	}

	/**
	 * Returns the URL to show the User for connection w/ Facebook 
	 *
	 * For permissions, see:
	 * http://developers.facebook.com/docs/authentication/permissions
	 *
	 * Example permissions: 'user_likes', 'user_about_me', ..
	 * Valid display parameters: page,popup,wap,touch
	 *
	 * @return string
	 */
	public function getRequestOauthPermissionsUrl(
		$permissions=array(), $display='popup')
	{
		
        $url = self::OPENGRAPH_URI.'/oauth/authorize?'.
	        'client_id='.$this->_applicationId.'&'.
	        'scope='.join(',', $permissions).'&'.
	        'display='.$display.'&'.
	    	'redirect_uri='.$this->_redirectUrl;		

        return $url;
	}

	/**
	 * Retrieves a token by an oauth code
	 * 
	 * @return String
	 */
	public function getOauthTokenByCode($code)
	{
		
        $url = self::OPENGRAPH_URI.'/oauth/access_token?'.
	        'client_id='.$this->_applicationId.'&'.
	        'client_secret='.$this->_applicationSecret.'&'.
	    	'redirect_uri='.$this->_redirectUrl.'&'.		
        	'code='.$code;

		$tokenData=file_get_contents($url);
		preg_match('/token=([^&]+)&/', $tokenData, $match);
		$token = $match[1];
        
		$this->_token = $token;
		
        return $token;
	}

	/**
	 * Retrieves data
	 * 
	 * for different connectionTypes see:
	 * http://developers.facebook.com/docs/reference/api/
	 * http://developers.facebook.com/docs/reference/api/user
	 * 
	 * Examples:
	 * 		$id = 'me', ... facebook object id ...
	 *  	$connectionType = 'likes','movies','friends',...
	 * 
	 * @return Object
	 */
	public function get($objectId, $connectionType='')
	{	
	
		$dataUrl = self::OPENGRAPH_URI.
			"/".$objectId.'/'.$connectionType.
			"?access_token=".$this->_token.
			"&locale=".$this->_locale;

		$data=file_get_contents($dataUrl);

		return json_decode($data);
	}
	
}
