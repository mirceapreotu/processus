<?php
/**
 * Lib_Facebook_Template_ParserFactory Class
 *
 * @package Lib_Facebook_Template
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Facebook_Template_ParserFactory
 *
 *
 * @package Lib_Facebook_Template
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */
class Lib_Facebook_Template_ParserFactory
{

    /**
     * @return Lib_Facebook_Template_Parser
     */
    public function newParserExampleDialogsFeed()
    {
         
        $template = array(


            "method"=> 'feed',
            "to" => null,
            "name"=> 'Facebook Dialogs',
            "link"=> 'http://developers.facebook.com/docs/reference/dialogs/',
            "picture"=> 'http://fbrell.com/f8.jpg {User.abc} {XYZ.abc}',
           "caption"=> '{User.firstname} {User.lastname} likes sth.',
            "description"=> '{User.firstname} hat seine Freunde {Friends.displayNames} eingeladen.',

            //This field 'message' will be ignored on July 12, 2011
            // The message to prefill the text field that the user will type in.
            // To be compliant with Facebook Platform Policies,
            // your application may only set this field if the user manually
            // generated the content earlier in the workflow.
            // Most applications should not set this.

            "message"=> '', // DO NOT USE!



            "source" => "",

            "properties" => array(


                "foo" => array(
                    "text" => "property foo",
                    "href" => "http:/www.example.com/propertyfoo",
                ),


            ),


            "actions" => array(

                array(
                    "name" => "Action1",
                    "link" => "http:/www.example.com/action1",
                ),


            ),





        );

        $data = array(
                );
                $dataDefault = $this->newDataDefault();

                $data = array_merge_recursive($dataDefault, $data);



        $config = array(
            "template" => $template,
            "data" => $data,
            "description" =>"
                http://developers.facebook.com/docs/reference/dialogs/feed/,
            ",
        );

        $parser = $this->newParser($config);
        return $parser;

    }



    /**
     * @return Lib_Facebook_Template_Parser
     */
    public function newParserExampleDialogsAppRequest()
    {

        // http://developers.facebook.com/docs/reference/dialogs/requests/
        $template = array(

               "method"=>'apprequests',
               "to" => array(), // userId's
               "filters" =>array(),
               "exclude_ids"=>array(),
               "max_recipients" => 25,
               "title" => "",
               "message"=>'You should learn more about this awesome game.',
               "data"=>"tracking information for the user",

        );

        $data = array(
                );
                $dataDefault = $this->newDataDefault();

                $data = array_merge_recursive($dataDefault, $data);



        $config = array(
            "template" => $template,
            "data" => $data,
            "description" =>"

                http://developers.facebook.com/docs/reference/dialogs/requests/
            ",

        );

        $parser = $this->newParser($config);
        return $parser;

    }




    /**
     * @return Lib_Facebook_Template_Parser
     */
     public function newParserExampleDialogsSend()
    {

        //  http://developers.facebook.com/docs/reference/dialogs/send/
        $template = array(

               "method"=>'send',
               "name" => 'People Argue Just to Win',
               "link" =>"http://www.nytimes.com/2011/06/15/arts/people-argue-just-to-win-scholars-assert.html",
            "to" => "",

            "picture" => "",
            "name" => "",
            "description" => "",

        );

        
        $data = array(
        );
        $dataDefault = $this->newDataDefault();

        $data = array_merge_recursive($dataDefault, $data);


        $config = array(
            "template" => $template,
            "data" => $data,
            "description" =>"
                http://developers.facebook.com/docs/reference/dialogs/send/
            ",
        );

        $parser = $this->newParser($config);
        return $parser;

    }








    /**
     * @param  array|null|Zend_Config $config
     * @return Lib_Facebook_Template_Parser
     */
    public function newParser($config) {

        $parser = new Lib_Facebook_Template_Parser();
        $parser->applyConfig($config);


        var_dump($parser->getConfig());

        return $parser;

    }


    public function newDataDefault() {
        $data = array(
            "Page" => array(
                "Fanpage" => array(
                    "url" =>
                        "http://www.facebook.com/pages/MyTestPage/152208658142315",
                ),
                "Canvas" => array(
                    "url" => "http://apps.facebook.com/myapp"
                ),
            ),
            "User" => array(
                "firstname" => "John",
                "lastname" => "Doe",
                "displayName" => "John Doe",

            ),
            "Friends" => array(
                "displayNames" => "Jane Doe, Max Mustermann und Günther Grün"
            ),
        );
        return $data;
    }
	
}
