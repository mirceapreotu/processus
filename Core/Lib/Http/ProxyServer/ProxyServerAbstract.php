<?php
/**
 * Lib_Http_ProxyServer_ProxyServerAbstract
 *
 * @package		Lib_Http_ProxyServer
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 *
 */

/**
 * Lib_Http_ProxyServer_ProxyServerAbstract
 *
 * @package Lib_Http_ProxyServer
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */
class Lib_Http_ProxyServer_ProxyServerAbstract
{

    const HTTP_METHOD_POST = Zend_Http_Client::POST;
    const HTTP_METHOD_GET = Zend_Http_Client::GET;
    const HTTP_METHOD_PUT = Zend_Http_Client::PUT;
    const HTTP_METHOD_DELETE = Zend_Http_Client::DELETE;
    const HTTP_METHOD_HEAD = Zend_Http_Client::HEAD;
    const HTTP_METHOD_TRACE = Zend_Http_Client::TRACE;
    const HTTP_METHOD_CONNECT = Zend_Http_Client::CONNECT;

    const HTTP_VERSION_1_0 = Zend_Http_Client::HTTP_0;
    const HTTP_VERSION_1_1 = Zend_Http_Client::HTTP_1;


    const CONFIG_PROPERTY_MAXREDIRECTS = "maxredirects";
    const CONFIG_PROPERTY_STRICTREDIRECTS = "strictredirects";
    const CONFIG_PROPERTY_USERAGENT = "useragent";
    const CONFIG_PROPERTY_TIMEOUT = "timeout";
    const CONFIG_PROPERTY_HTTPVERSION = "httpversion";
    const CONFIG_PROPERTY_KEEPALIVE = "keepalive";

    const HEADER_CONTENT_TYPE = 'content-type';
    const HEADER_CONTENT_LENGTH = 'content-length';
    const CONTENT_TYPE_TEXT_HTML = 'text/html';
    const CONTENT_TYPE_TEXT_PLAIN = 'text/plain';


    const ERROR_INVALID_URI = "INVALID_URI";
    const ERROR_INVALID_MIME_TYPE = "INVALID_MIME_TYPE";
    const ERROR_INVALID_DOMAIN = "INVALID_DOMAIN";
    const ERROR_INVALID_CLIENT_IP = "INVALID_CLIENT_IP";



    /**
     * @var Zend_Config
     */
    protected $_config;
    /**
     * override in subclass or inject using Zend_Config
     * @var array
     */
    protected $_configDefault = array(
        "httpClient" => array(
            "maxredirects" => 5,
            "strictredirects" => false,
            "useragent" => null,
            "timeout" => 10,
            "httpversion" => "1.1",
            "keepalive" => false,
        ),
        "domain" => array(
            "whitelist" => array(

            ),
            "blacklist" => array(

            ),
        ),
        "mimeType" => array(
            "whitelist" => array(

            ),
            "blacklist" => array(

            ),
        ),


        "clientIP" => array(
            "whitelist" => array(

            ),
            "blacklist" => array(

            ),
            
        ),


    );


/**
     * @return Zend_Config
     */
    public function getConfig()
    {
        if (($this->_config instanceof Zend_Config) !== true) {
            $this->_parseConfig();
            if ($this->_config instanceof Zend_Config) {
                $this->_onConfigParsed();
            } else {
                throw new Exception(
                    "Method returns invalid result at ".__METHOD__
                );
            }
        }
        return $this->_config;
    }


    public function setConfig(Zend_Config $config)
    {
        $this->_config = $config;

    }



    /**
     * @return Zend_Config
     */
    public function getConfigDefault()
    {
        return new Zend_Config((array)$this->_configDefault, true);
    }



    protected function _parseConfig()
    {
        $configDefault = $this->getConfigDefault();
/*
        $zendConfig = Lib_Utils_Config_Parser::loadByClassName(
            get_class($this)
        );
*/
        //loadByClassOrSuperClasses($instance)
        $zendConfig = Lib_Utils_Config_Parser::loadByClassOrSuperClasses(
            $this
        );
        $config = Lib_Utils_Config_Parser::merge(
            array(
                 $configDefault,
                 $zendConfig
            )
        );

        $this->_config = $config;


    }


    /**
     * your hooks here
     * @return void
     */
    protected function _onConfigParsed()
    {
        $config = $this->getConfig();

    }




    /**
     * @param  string $domain
     * @return bool
     */

    public function isAllowedDomain($domain)
    {
        $isWhitelisted = $this->isDomainWhitelisted($domain);
        $isBlacklisted = $this->isDomainBlacklisted($domain);

        return (bool)(($isWhitelisted===true) && ($isBlacklisted !== true));

    }

    /**
     * @param  string $mimeType
     * @return bool
     */

    public function isAllowedMimeType($mimeType)
    {
        $isWhitelisted = $this->isMimeTypeWhitelisted($mimeType);
        $isBlacklisted = $this->isMimeTypeBlacklisted($mimeType);

        return (bool)(($isWhitelisted===true) && ($isBlacklisted !== true));

    }


    /**
     * @param  string $ip
     * @return bool
     */

    public function isAllowedClientIP($ip)
    {

        $isWhitelisted = $this->isClientIpWhitelisted($ip);
        $isBlacklisted = $this->isClientIpBlacklisted($ip);

        return (bool)(($isWhitelisted===true) && ($isBlacklisted !== true));

    }



    /**
     * @param  string $domain
     * @return bool
     */
    public function isDomainWhitelisted($domain)
    {

        $result = false;
        $listConfig = $this->getConfig()->domain->whitelist;
        if (($listConfig instanceof Zend_Config)!== true) {
            return $result;
        }
        /**
         * @var Zend_Config $listConfig
         */
        $list = $listConfig->toArray();

        foreach($list as $pattern) {

            if (fnmatch($pattern, $domain, FNM_CASEFOLD)) {
                return true;
            }
        }
        return $result;
    }
    /**
     * @param  $domain
     * @return bool
     */
    public function isDomainBlacklisted($domain)
    {

        $result = false;
        $listConfig = $this->getConfig()->domain->blacklist;
        if (($listConfig instanceof Zend_Config)!== true) {
            return $result;
        }
        /**
         * @var Zend_Config $listConfig
         */
        $list = $listConfig->toArray();

        foreach($list as $pattern) {

            if (fnmatch($pattern, $domain, FNM_CASEFOLD)) {
                return true;
            }
        }
        return $result;

    }


    /**
     * @param  string $mimeType
     * @return bool
     */
    public function isMimeTypeWhitelisted($mimeType)
    {

        $result = false;
        $listConfig = $this->getConfig()->mimeType->whitelist;
        if (($listConfig instanceof Zend_Config)!== true) {
            return $result;
        }
        /**
         * @var Zend_Config $listConfig
         */
        $list = $listConfig->toArray();

        foreach($list as $pattern) {

            if (fnmatch($pattern, $mimeType, FNM_CASEFOLD)) {
                return true;
            }
        }
        return $result;
    }

    /**
     * @param  $mimeType
     * @return bool
     */
    public function isMimeTypeBlacklisted($mimeType)
    {

        $result = false;
        $listConfig = $this->getConfig()->mimeType->blacklist;
        if (($listConfig instanceof Zend_Config)!== true) {
            return $result;
        }
        /**
         * @var Zend_Config $listConfig
         */
        $list = $listConfig->toArray();

        foreach($list as $pattern) {

            if (fnmatch($pattern, $mimeType, FNM_CASEFOLD)) {
                return true;
            }
        }
        return $result;

    }



    /**
     * @param  string $ip
     * @return bool
     */
    public function isClientIpWhitelisted($ip)
    {

        $result = false;

        $listConfig = $this->getConfig()->clientIP->whitelist;

        if (($listConfig instanceof Zend_Config)!== true) {
            return $result;
        }

        /**
         * @var Zend_Config $listConfig
         */
        $list = $listConfig->toArray();

        foreach($list as $pattern) {

            if (fnmatch($pattern, $ip, FNM_CASEFOLD)) {
                return true;
            }
        }
        return $result;
    }

    /**
     * @param  $ip
     * @return bool
     */
    public function isClientIpBlacklisted($ip)
    {

        $result = false;
        $listConfig = $this->getConfig()->clientIP->blacklist;
        if (($listConfig instanceof Zend_Config)!== true) {
            return $result;
        }
        /**
         * @var Zend_Config $listConfig
         */
        $list = $listConfig->toArray();

        foreach($list as $pattern) {

            if (fnmatch($pattern, $ip, FNM_CASEFOLD)) {
                return true;
            }
        }
        return $result;

    }


	/**
     * @var Zend_Http_Client
     */
    protected $_client;

    /**
     * @return Zend_Http_Client
     */
    public function getClient()
    {
        if (($this->_client instanceof Zend_Http_Client) !== true) {
            $this->_client = new Zend_Http_Client();



        }
        return $this->_client;
    }

    /**
     * @var Zend_Http_Response
     */
    protected $_response;

    /**
     * @return Zend_Http_Response
     */
    public function getResponse()
    {
        if (($this->_response instanceof Zend_Http_Response) !== true) {
            $this->_response = new Zend_Http_Response(0, array());
        }
        return $this->_response;
    }




    /**
     * @return void
     */
    public function run($uri = null, $method = null)
    {
        try {
            $client = $this->getClient();




            $clientIP = Lib_utils_Array::getProperty($_SERVER, "REMOTE_ADDR");

            if ($this->isAllowedClientIP($clientIP)!==true) {
                $e = new Lib_Application_Exception(
                    self::ERROR_INVALID_CLIENT_IP
                );
                $e->setMethod(__METHOD__);
                $e->setFault(array(
                    "clientIp" =>$clientIP,
                ));
                throw $e;
            }

            if ($uri !== null) {
                $client->setUri($uri);

                if ($client->getUri()->valid() !== true) {
                    $e = new Lib_Application_Exception(self::ERROR_INVALID_URI);
                    $e->setMethod(__METHOD__);
                    $e->setFault(array(
                        "uri" =>$uri,
                    ));
                    throw $e;
                }

            }

            if ($method !== null) {
                $client->setMethod($method);
            }

            $domain = $client->getUri()->getHost();
            if ($this->isAllowedDomain($domain) !== true) {
                $e = new Lib_Application_Exception(self::ERROR_INVALID_DOMAIN);
                $e->setMethod($this);
                $e->setFault(array(
                    "domain" => $domain,
                ));
                throw $e;
            }

            $client->setConfig($this->getConfig()->httpClient->toArray());

            /*
            var_dump(array(
                "method" => __METHOD__,
                "uriString" => $client->getUri(true),
                "cacheFileLocation" => $this->_newCacheFileLocation(
                    $client->getUri(true),
                    "myfolder/",
                    "",
                    ""
                ),
            ));


             */


            $userAgent = null;
            try {
                //playstation
                $userAgent = Lib_Utils_Array::getProperty(
                    $_SERVER, 'HTTP_USER_AGENT'
                );
            } catch(Exception $e) {

            }
            if ($userAgent !== null) {
                $client->setHeaders('User-Agent', $userAgent);
            }



            $response = $client->request();
            $this->_response = $response;

            $this->_onResponse();
            
        } catch (Exception $e) {

            $this->_onFault($e);
            
        }

    }


    /**
     * @return array
     */
    public function getMimeTypeListFromResponse()
    {
        $response = $this->getResponse();
        $mime = $response->getHeader(self::HEADER_CONTENT_TYPE);
        if (is_array($mime) === true) {
            return $mime;
        }
        if (is_string($mime)) {
            return array(
                $mime
            );
        }

        return array();

    }

    /**
     * @return string|null
     */
    public function getMimeTypeFromResponse()
    {
        $mimeTypeList = $this->getMimeTypeListFromResponse();
        return Lib_Utils_Array::getProperty($mimeTypeList, 0);
    }


    // ++++++++++++++++++ event callbacks ++++++++++++++++


    /**
     * @return
     */
    protected function _onResponse()
    {
        if ($this->getResponse()->isError() === true) {
            $this->_onError();
            return;
        }

        if ($this->getResponse()->isRedirect() === true) {
            // max redirects exceeded
            $this->_onError();
            return;
        }

        if ($this->getResponse()->isSuccessful() === true) {
            $this->_onSuccess();
            return;
        }
    }

    /**
     * @return void
     */
    protected function _onSuccess()
    {
        throw new Exception(
            "Implement in subclasses! Method="
                    .__METHOD__." class=".get_class($this)
        );

        $client = $this->getClient();
        $response = $this->getResponse();

        $mime = $this->getMimeTypeFromResponse();
        $isAllowedMime = $this->isAllowedMimeType($mime);
        if ($isAllowedMime !== true) {

            $e = new Lib_Application_Exception(self::ERROR_INVALID_MIME_TYPE);
            $e->setMethod(__METHOD__);
            $e->setFault(array(
                "mimeType" => $mime,
            ));

            throw $e;
        }


        $headers = $response->getHeaders();
        foreach($headers as $key => $value)
		{
            if ($this->_isStringEqual(
                $key,
                self::HEADER_CONTENT_LENGTH,
                true,
                true
            ))
			{
				$value = $this->getRealContentLength();
                //var_dump($value);
			}
			header("".$key.": ".$value);
		}
        echo $response->getBody();


        return;


    }

    /**
     * @return void
     */
    protected function _onError()
    {
        throw new Exception(
            "Implement in subclasses! Method="
                    .__METHOD__." class=".get_class($this)
        );
        $response = $this->getResponse();

        $isMaxRedirectsExceeded = ($this->getResponse()->isRedirect() === true);

        $response = $this->getResponse();

        $isMaxRedirectsExceeded = ($this->getResponse()->isRedirect() === true);

        if ($isMaxRedirectsExceeded) {
            throw new Exception(
                "MAX_REDIRECTS_EXCEEDED at "
                        .__METHOD__." for ".get_class($this)
            );
        }

        $statusCode = $response->getStatus();
        $statusReasonPhrase = $response->getMessage();
        $version = $response->getVersion();

		if (Lib_Utils_String::isEmpty($version)) {
            $version="1.1";
        }

		$errorHeader = "HTTP/".$version." ".$statusCode." ".$statusReasonPhrase;
		header($errorHeader);
    }



    /**
    * @param Exception $e
    * @return void
    */
    protected function _onFault(Exception $e)
    {
       throw new Exception(
           "Implement in subclasses! Method="
                   .__METHOD__." class=".get_class($this)
       );

       switch($e->getMessage()) {
           case self::ERROR_INVALID_URI:
           case self::ERROR_INVALID_DOMAIN:
           case self::ERROR_INVALID_MIME_TYPE: {
               header('HTTP/1.1 403 Forbidden');
               break;
           }

           default: {
               header('HTTP/1.1 404 Not Found');
               break;
           }
       }

       if (Bootstrap::getRegistry()->isDebugMode()) {
           var_dump(__METHOD__." ".$e->getMessage());
       }
    }




    // +++++++++++++++ utils ++++++++++++++++++++++



    /**
     * @return int
     */
    public function getBodyLength()
    {
		$result = 0;
		$body = $this->getResponse()->getBody();
		if (!is_string($body)) {return $result;}
		try{
			$result = (int)strlen($body);
		}catch(Exception $e){

		}
		return $result;
	}

    /**
     * @return int
     */
	public function getContentLength()
	{
		$result = 0;
		try {
			$result = (int)$this->getResponse()
                    ->getHeader(self::HEADER_CONTENT_LENGTH);
		}catch(Exception $e){

		}

		return $result;
	}
    /**
     * @return null|string
     */
	public function getContentType()
	{
		$result = null;
		try {
			$result = $this->getResponse()
                    ->getHeader(self::HEADER_CONTENT_TYPE);
		}catch(Exception $e){

		}

		return $result;
	}

    /**
     * @return int
     */
	public function getRealContentLength()
	{
		$bodyLength = (int)$this->getBodyLength();
		$contentLength = (int)$this->getContentLength();

		$result = (int)max(	(int)$bodyLength, (int)$contentLength	);

		return $result;
	}


    /**
     * @param  string $source
     * @param  string $target
     * @param bool $trim
     * @param bool $ignoreCase
     * @return bool
     */
    protected function _isStringEqual(
        $source,
        $target,
        $trim = true,
        $ignoreCase = true
    )
    {
        $result = false;
        if (is_string($source) !== true) {
            return $result;
        }
        if (is_string($target) !== true) {
            return $result;
        }

        if ($trim !== false) {
            $trim = true;
        }
        if ($ignoreCase !== false) {
            $ignoreCase = true;
        }
        if ($trim===true) {
            $target = trim($target);
            $source = trim($source);
        }
        if ($ignoreCase===true) {
            $target = strtolower($target);
            $source = strtolower($source);
        }

        return (bool)($source === $target);
    }

    // +++++++++++++++++ caching ++++++++++++++++++++++++++++++

    /**
     * @throws Exception
     * @param  string $uri
     * @param  string $directory
     * @param  string $filenamePrefix
     * @param  string $filenameSuffix
     * @return string
     */
    protected function _newCacheFileLocation(
                $uri,
                $directory,
                $filenamePrefix,
                $filenameSuffix
    )
    {

        $zendUri = Zend_Uri_Http::factory($uri);
        if ($zendUri->valid() !== true) {
            $e = new Lib_Application_Exception(self::ERROR_INVALID_URI);
            $e->setMethod(__METHOD__);
            $e->setFault(array(
                "uri" =>$uri,
            ));
            throw $e;

        }

        $filename = $zendUri->getUri();
        $filename = $this->_urlToFilename($filename);


        $directory = trim($directory);
        $prefix = trim($filenamePrefix);
        $suffix = trim($filenameSuffix);

        if (Lib_Utils_String::isEmpty($directory)) {
            throw new Exception(
                "Parameter 'directory' must be a string and cant be empty"
                        ." at ".__METHOD__
                        ." for ".get_class($this)
            );
        }
        if (is_string($filenamePrefix)!==true) {
            throw new Exception(
                "Parameter 'filenamePrefix' must be a string and cant be empty"
                        ." at ".__METHOD__
                        ." for ".get_class($this)
            );
        }

        if (is_string($filenameSuffix)!==true) {
            throw new Exception(
                "Parameter 'filenameSuffix' must be a string and cant be empty"
                        ." at ".__METHOD__
                        ." for ".get_class($this)
            );
        }

        $location = "".$directory;
        if (Lib_Utils_String::endsWith($location, "/") !== true) {
            $location .= "/";
        }

        $location .= $prefix.$filename.$suffix;

        return $location;
    }


    /**
     * @param  string $url
     * @return null|string
     */
    protected function _urlToFilename($url)
    {
        $filename = $url;

        $filename = Lib_Utils_Base64::encodeUrlSafe($filename);
        $filename = rawurlencode($filename);
        return $filename;
    }

    /**
     * @param  string $filename
     * @return null|string
     */
    protected function _filenameToUrl($filename)
    {
        $url = $filename;
        $url = rawurldecode($url);
        $url = Lib_Utils_Base64::decodeUrlSafe($url);

        return $url;
    }



    /**
     * returns the location (string) of the file created
     * @throws Exception
     * @param  string $location
     *
     * @return string
     */
    protected function _saveResponseBodyToFile(
        $location
    )
    {
        $response = $this->getResponse();

        $content = $response->getBody();

        if (Lib_Utils_String::isEmpty($location)) {
            throw new Exception(
                "Invalid location at ".__METHOD__." for ".get_class($this)
            );
        }

        file_put_contents($location, $content);

        return $location;
    }



}
