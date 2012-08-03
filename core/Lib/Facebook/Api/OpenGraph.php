<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 11/15/11
 * Time: 3:55 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Processus\Lib\Facebook\Api;

use \Processus\Lib\Facebook\FacebookClient;

class OpenGraph extends FacebookClient
{
    /**
     * @param string $achievementUrl
     *
     * @return null
     */
    public function getGameAchievement(\string $achievementUrl)
    {
        $gameAchievements = $this->getAllGameAchievements();
        foreach ($gameAchievements as $gameAchievement) {
            if ($gameAchievement['url'] == $achievementUrl) {
                return $gameAchievement;
            }
        }

        return null;
    }

    /**
     * @return array
     */
    public function getAllGameAchievements()
    {
        // Get a new application access token for this request
        $accessToken = $this->_renew_application_access_token();

        // Prepare request data
        $achievementData = array(
            'access_token'  => $accessToken
        );

        // Execute request
        $response = $this->getFacebookSdk()->api(
            $this->getFacebookSdk()->getAppId() . '/achievements',
            'GET',
            $achievementData
        );

        if (is_array($response) AND isset($response['data'])) {
            return $response['data'];
        }

        throw new \Exception('Failed to get game achievements');
    }

    /**
     * @param string $achievementUrl
     *
     * @return bool
     * @throws \Exception
     */
    public function setGameAchievement(\string $achievementUrl)
    {
        // Get a new application access token for this request
        $accessToken = $this->_renew_application_access_token();

        // Prepare request data
        $achievementData = array(
            'achievement'   => $achievementUrl,
            'display_order' => 1,
            'access_token'  => $accessToken
        );

        // Execute request
        $response = $this->getFacebookSdk()->api(
            $this->getFacebookSdk()->getAppId() . '/achievements',
            'POST',
            $achievementData
        );

        if ($response === True) {
            return True;
        }

        throw new \Exception('Failed to set game achievement');
    }

    /**
     * @param string $achievementUrl
     *
     * @return bool
     * @throws \Exception
     */
    public function deleteGameAchievement(\string $achievementUrl)
    {
        // Get a new application access token for this request
        $accessToken = $this->_renew_application_access_token();

        // Prepare request data
        $achievementData = array(
            'achievement'   => $achievementUrl,
            'access_token'  => $accessToken
        );

        // Execute request
        $response = $this->getFacebookSdk()->api(
            $this->getFacebookSdk()->getAppId() . '/achievements',
            'DELETE',
            $achievementData
        );

        if ($response === True) {
            return True;
        }

        throw new \Exception('Failed to delete game achievement');
    }


    /**
     * @return array
     */
    public function getAllUserAchievements()
    {
        // Prepare request data
        $achievementData = array(
            'access_token'  => $this->getFacebookSdk()->getAccessToken()
        );

        // Execute request
        $response = $this->getFacebookSdk()->api(
            $this->getApplicationContext()->getUserBo()->getFacebookUserId() . '/achievements',
            'GET',
            $achievementData
        );

        if (is_array($response) AND isset($response['data'])) {
            return $response['data'];
        }

        throw new \Exception('Failed to get game achievements');
    }

    /**
     * @param string $achievementUrl
     *
     * @return bool
     * @throws \Exception
     */
    public function setUserAchievement(\string $achievementUrl)
    {
        // Get a new application access token for this request
        $accessToken = $this->_renew_application_access_token();

        // Prepare request data
        $achievementData = array(
            'achievement'   => $achievementUrl,
            'access_token'  => $accessToken
        );

        // Execute request
        $response = $this->getFacebookSdk()->api(
            $this->getApplicationContext()->getUserBo()->getFacebookUserId() . '/achievements',
            'POST',
            $achievementData
        );

        if (is_array($response) AND isset($response['id']) AND is_integer($response['id'])) {
            return True;
        }

        throw new \Exception('Failed to set user achievement');
    }

    /**
     * @param string $achievementUrl
     *
     * @return bool
     * @throws \Exception
     */
    public function deleteUserAchievement(\string $achievementUrl)
    {
        // Get a new application access token for this request
        $accessToken = $this->_renew_application_access_token();

        // Prepare request data
        $achievementData = array(
            'achievement'   => $achievementUrl,
            'access_token'  => $accessToken
        );

        // Execute request
        $response = $this->getFacebookSdk()->api(
            $this->getApplicationContext()->getUserBo()->getFacebookUserId() . '/achievements',
            'DELETE',
            $achievementData
        );

        if ($response === True) {
            return True;
        }

        throw new \Exception('Failed to delete user achievement');
    }


    /**
     * @param int $score
     *
     * @return bool
     * @throws \Exception
     */
    public function setUserScore(\int $score)
    {
        // Get a new application access token for this request
        $accessToken = $this->_renew_application_access_token();

        // Prepare request data
        $achievementData = array(
            'score'         => $score,
            'access_token'  => $accessToken
        );

        // Execute request
        $response = $this->getFacebookSdk()->api(
            $this->getApplicationContext()->getUserBo()->getFacebookUserId() . '/scores',
            'POST',
            $achievementData
        );

        if ($response === True) {
            return True;
        }

        throw new \Exception('Failed to set user score');
    }

    /**
     * @return mixed
     * @throws \Processus\Contrib\Facebook\FacebookApiException
     */
    private function _renew_application_access_token()
    {
        $data = array(
            'client_id'     => $this->getFacebookSdk()->getAppId(),
            'client_secret' => $this->getFacebookSdk()->getApiSecret(),
            'grant_type'    => 'client_credentials'
        );

        // Send access token request
        $ogRequestClient = new \Zend\Http\Client();
        $ogRequestClient->setUri($this->getGraphUrl() . 'oauth/access_token')
            ->setAdapter(new \Zend\Http\Client\Adapter\Curl())
            ->setConfig(array(
                             'curloptions'  => $this->_getCurlOptions()
                        ))
            ->setMethod('GET')
            ->setParameterGet($data)
            ->send();
        $ogResponse = $ogRequestClient->getResponse()->getContent();

        // Parse Facebook response
        $facebookResponseParams = array();
        parse_str($ogResponse, $facebookResponseParams);

        if (!isset($facebookResponseParams['access_token']) OR $facebookResponseParams['access_token'] == '') {
            $ogResponse       = \Zend\Json\Decoder::decode($ogResponse);
            $exceptionDetails = array(
                'error_description' => $ogResponse->error->message,
                'error_code'        => $ogResponse->error->code
            );

            throw new \Processus\Contrib\Facebook\FacebookApiException($exceptionDetails);
        }

        return $facebookResponseParams['access_token'];
    }


    /**
     * @return string
     */
    public function getGraphUrl()
    {
        return 'https://graph.facebook.com/';
    }

    /**
     * @return array
     */
    public function _getCurlOptions()
    {
        $facebookSdk = $this->getFacebookSdk();

        /**
         * @var array
         */
        $CURL_OPTS = array();

        return $facebookSdk::$CURL_OPTS;
    }

}