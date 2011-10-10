<?php
/**
 * App_Facebook_Mock
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_Facebook
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * App_Facebook_Mock
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_Facebook
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class App_Facebook_Mock extends Lib_Facebook_Mock
{

    
    /**
    * @override
    * @var array
    */
    protected $_configDefault = array(

        "mock" => array(
            "enabled" => true,
            //"session" => null, //'{"access_token":"161026780619431|2.lT6fHigIovyj_VDjR8QsQg__.3600.1300712400-100001680154141|-c9zLjVACoJUXaJ4qsOMfKLHxLw","expires":"1300712400","sig":"5d100e7536637b08dbc76042353cbee1","uid":"100001680154141"}';

            "session" => array(
                "signed_request" => null,//see etc/config(!) "mFETaYDibHsDAIkJqToh3GSGdxuPwTazopdBKah0Iek.eyJhbGdvcml0aG0iOiJITUFDLVNIQTI1NiIsImV4cGlyZXMiOjEzMTA0MDAwMDAsImlzc3VlZF9hdCI6MTMxMDM5NDU3MCwib2F1dGhfdG9rZW4iOiIxMjM3MTMwNTQzODI5MTR8Mi5BUUJ5Sk9lT1NYYTdzY0FOLjM2MDAuMTMxMDQwMDAwMC4xLTEwMTM2ODA2ODh8azdwblVuNDF0TEdsa0xIeElqbFNIV0hzQVMwIiwidXNlciI6eyJjb3VudHJ5IjoiZGUiLCJsb2NhbGUiOiJlbl9HQiIsImFnZSI6eyJtaW4iOjIxfX0sInVzZXJfaWQiOiIxMDEzNjgwNjg4In0",
            )
        ),


    );


}
