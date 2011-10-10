<?php
/**
 * App_JsonRpc_V1_Fb_Service_Facebook_Viewer_Permissions
 *
 *
 *
 * @category	meetidaaa.com
 * @package     App_JsonRpc_V1_Fb_Service_Facebook_Viewer
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id:$
 */

/**
 * App_JsonRpc_V1_Fb_Service_Facebook_Viewer_Permissions
 *
 *
 *
 * @category	meetidaaa.com
 * @package     App_JsonRpc_V1_Fb_Service_Facebook_Viewer
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id:$
 */
class App_JsonRpc_V1_Fb_Service_Facebook_Viewer_Permissions
    extends App_JsonRpc_V1_Fb_Service
{


    public function getList() {


        $application = $this->getContext()->getApplication();
        $profiler = $application->profileMethodStart(__METHOD__);

        $result = array(
            "data" =>$application->getManagerFacebookPermissions()
                ->getFacebookMePermissionsList()
        );


        $profiler->stop();

        return (object)$result;

    }

    /**
     * @return object
     */
    public function reloadList() {


         $application = $this->getContext()->getApplication();
         $profiler = $application->profileMethodStart(__METHOD__);

         $result = array(
             "data" =>$application->getManagerFacebookPermissions()
                 ->reloadFacebookMePermissionsList()
         );


         $profiler->stop();

         return (object)$result;

     }





    /**
     * @return object
     */
    /*
    public function reloadList() {
        $application = $this->getContext()->getApplication();
        $profiler = $application->profileMethodStart(__METHOD__);


        $result = array(
            "data" => null,
        );

        // try to get from application

        $manager = $application->getManagerFacebookPermissions();

        $manager->reloadFacebookMePermissionsList();


        $permissionsList = $manager->getFacebookMePermissionsList();

        $result["data"] = $permissionsList;
        $profiler->stop();
        return (object)$result;

    }
    */




    /**
     * @return object
     */
    /*
    public function getList() {

        $application = $this->getContext()->getApplication();
        $profiler = $application->profileMethodStart(__METHOD__);


        $result = array(
            "data" => null,
        );

        // try to get from application
        $manager = $application->getManagerFacebookPermissions();
        $permissionsList = $manager->getFacebookMePermissionsList();
        $result["data"] = $permissionsList;
        $profiler->stop();
        return (object)$result;

    }
    */







}