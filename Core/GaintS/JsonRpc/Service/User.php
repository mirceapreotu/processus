<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: francis
     * Date: 10/15/11
     * Time: 9:03 PM
     * To change this template use File | Settings | File Templates.
     */
    class App_JsonRpc_V1_App_Service_User extends App_JsonRpc_V1_App_Service
    {
        /**
         * @return array
         */
        public function getInitialData()
        {
            $result = array();

            $this->isUserRegistered();

            $application = $this->getContext()->getApplication();
            $facebook = $application->getFacebook();
            $userId = $facebook->getUserIdFromAvailableData();
            $mvo = new App_GaintS_Vo_Membase_FacebookUserMVO();
            $mvo->setMemId($userId);

            $dto = new App_Model_Dto_BeatguideUser();
            $dto->setData($mvo->getData());
            $result['me'] = $dto->export();

            $tracking = new App_Manager_App_Tracking();
            $tracking->track();

            return $result;
        }

        /**
         *
         */
        private function isUserRegistered()
        {
            $facebook = App_Facebook_Application::getInstance()->getFacebook();
            $userId = $facebook->getUserIdFromAvailableData();
            
            if($userId === 0)
            {
                throw new Lib_Application_Exception("Your are not allowed to use this App!");
            }
            
            $application = $this->getContext()->getApplication();
            $facebook = $application->getFacebook();
            $userId = $facebook->getUserIdFromAvailableData();
            $mvo = new App_GaintS_Vo_Membase_FacebookUserMVO();
            $mvo->setMemId($userId);

            $userTmpData = $mvo->getData();
            $manager = new App_Manager_App_UserManager();
            $manager->saveUserFb($userTmpData);
        }
    }
