<?php
/**
 * Lib_Url_Uri Class
 *
 * @package Lib_Url
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Url_Uri
 *
 * @package Lib_Url
 *
 * @abstract
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 *
 * 
 */
class Lib_Url_Uri
{

    const SCHEME_HTTP = "http";
    const SCHEME_HTTPS = "https";



    /**
     * @var null|Zend_uri_Http
     */
    protected $_zendUri;


    /**
     * @param null|string $uri
     */
    public function __construct($uri = null)
    {
        if ($uri !== null) {
            $this->setUri($uri);
        }
    }

    /**
     * @throws Exception
     * @param  string $uri
     * @return void
     */

    public function setUri($uri)
    {
        try {
            $this->_zendUri = Zend_Uri_Http::fromString($uri);
        } catch(Exception $error) {
            throw new Exception(
                "Invalid parameter 'uri' at ".__METHOD__
                . " error: ".$error->getMessage()
            );
        }
    }



    /**
     * @param  string|Zend_Uri_Http $uri
     * @return bool
     */
    public function isValidUri($uri)
    {
        $result = false;

        if ((
                (is_string($uri))
                || ($uri instanceof Zend_Uri_Http)
        )!==true) {
            return $result;
        }

        try {

            if (($uri instanceof Zend_Uri_Http)!==true) {
                $uri = Zend_Uri_Http::fromString($uri);
            }
            /**
             * @var Zend_Uri_Http $uri
             */

           
            if ((is_string($uri->getUri())) && (strlen($uri->getUri())>0)) {
                return (bool)($uri->valid()===true);
            }


            return $result;

        } catch(Exception $error) {
            return $result;
        }

    }


    /**
     * @return bool
     */
    public function hasValidUri()
    {
        $result = false;
        if (($this->_zendUri instanceof Zend_Uri_Http)!==true) {
            return $result;
        }

        return (bool)($this->isValidUri($this->_zendUri)===true);
    }


    /**
     * @throws Lib_Application_Exception
     * @param  string|null $errorMethod
     * @param  string|null $errorMessage
     * @return void
     */
    public function requireValidUri($errorMethod, $errorMessage)
    {
        if (((is_string($errorMethod)) && (strlen($errorMethod)>0))!==true) {
            $errorMethod = __METHOD__;
        }

        if ((
                    (is_string($errorMessage))
                    && (strlen($errorMessage)>0)
            )!==true) {
            $errorMessage = "Invalid uri at ".__METHOD__;
        }


        if ($this->hasValidUri() !== true) {

            $error = new Lib_Application_Exception($errorMessage);
            $error->setMethod($errorMethod);
            $error->setFault(array(
                                "details" => "Invalid Uri."
                                    . "You must call setUri(uri) first"
                                    . " and provide a valid uri."
                                    . " at ".$errorMethod
                                    . " for ".get_class($this),
                             ));

            throw $error;
            
        }
    }

  

    /**
     * @return string
     */
    public function getFragment()
    {
        $result = "";
        $zendUri = $this->_zendUri;
        if (($zendUri instanceof Zend_Uri_Http)!==true) {
            return $result;
        }
        $value = $zendUri->getHost();
        if (is_string($value)!==true) {
            return $result;
        }

        $result = $value;
        return $result;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        $result = "";
        $zendUri = $this->_zendUri;
        if (($zendUri instanceof Zend_Uri_Http)!==true) {
            return $result;
        }
        $value = $zendUri->getHost();
        if (is_string($value)!==true) {
            return $result;
        }

        $result = $value;
        return $result;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        $result = "";
        $zendUri = $this->_zendUri;
        if (($zendUri instanceof Zend_Uri_Http)!==true) {
            return $result;
        }
        $value = $zendUri->getPath();
        if (is_string($value)!==true) {
            return $result;
        }

        $result = $value;
        return $result;
    }

    /**
     * @return string
     */
    public function getPort()
    {
        $result = "";
        $zendUri = $this->_zendUri;
        if (($zendUri instanceof Zend_Uri_Http)!==true) {
            return $result;
        }
        $value = $zendUri->getPort();
        if (is_string($value)!==true) {
            return $result;
        }

        $result = $value;
        return $result;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        $result = "";
        $zendUri = $this->_zendUri;
        if (($zendUri instanceof Zend_Uri_Http)!==true) {
            return $result;
        }
        $value = $zendUri->getQuery();
        if (is_string($value)!==true) {
            return null;
        }

        $result = $value;
        return $result;
    }


    /**
     * @return string
     */
    public function getScheme()
    {
        $result = "";
        $zendUri = $this->_zendUri;
        if (($zendUri instanceof Zend_Uri_Http)!==true) {
            return $result;
        }
        $value = $zendUri->getScheme();
        if (is_string($value)!==true) {
            return $result;
        }

        $result = $value;
        return $result;
    }

    /**
     * @return string
     */
    public function getProtocol()
    {
        $result = "";
        $zendUri = $this->_zendUri;
        if (($zendUri instanceof Zend_Uri_Http)!==true) {
            return $result;
        }
        $scheme = $zendUri->getScheme();
        if ((strlen($scheme)>0)!==true) {
            return $result;
        }

        return $scheme . "://";

    }

    /**
     * @return string
     */
    public function getUri()
    {
        $result = "";
        $zendUri = $this->_zendUri;
        if (($zendUri instanceof Zend_Uri_Http)!==true) {
            return $result;
        }
        $value = $zendUri->getUri();
        if (is_string($value)!==true) {
            return $result;
        }

        $result = $value;
        return $result;
    }

    /**
     * @return array
     */
    public function getQueryAsArray()
    {
        $result = array();
        $zendUri = $this->_zendUri;
        if (($zendUri instanceof Zend_Uri_Http)!==true) {
            return $result;
        }
        $value = $zendUri->getQueryAsArray();
        if (is_array($value)!==true) {
            return array();
        }

        $result = $value;
        return $result;
    }

    /**
     * @param  string $key
     * @return array|mixed|null
     */
    public function getQueryParameter($key)
    {
        $result = array();
        $zendUri = $this->_zendUri;
        if (($zendUri instanceof Zend_Uri_Http)!==true) {
            return $result;
        }

        $params = $this->getQueryAsArray();

        return Lib_Utils_Array::getProperty($params, $key, true);

    }

    /**
     * @return array
     */
    public function getQueryParameters()
    {
        $result = array();
        $zendUri = $this->_zendUri;
        if (($zendUri instanceof Zend_Uri_Http)!==true) {
            return $result;
        }

        return $this->getQueryAsArray();

    }


    /**
     * @throws Exception
     * @param  string| null $fragmentString
     * @return string
     */
    public function setFragment($fragmentString)
    {
        if ($fragmentString === null) {
            $fragmentString = "";
        }
        if (is_string($fragmentString)!==true) {
            throw new Exception(
                "Invalid parameter 'fragmentString' at ".__METHOD__
            );
        }

        $zendUri = $this->_zendUri;
        if (($zendUri instanceof Zend_Uri_Http)!==true) {
            $this->requireValidUri(__METHOD__, null);
        }

        $oldValue = $this->getFragment();
        $this->_zendUri->setFragment($fragmentString);
        $result = $oldValue;
        return $result;

    }

     /**
     * @throws Exception
     * @param  string| null $pathString
     * @return string
     */
    public function setPath(
        // NOTICE pathString must start with "/" 
        $pathString
    )
    {
        if ($pathString === null) {
            $pathString = "";
        }
        if (is_string($pathString)!==true) {
            throw new Exception(
                "Invalid parameter 'pathString' at ".__METHOD__
            );
        }

        $zendUri = $this->_zendUri;
        if (($zendUri instanceof Zend_Uri_Http)!==true) {
            $this->requireValidUri(__METHOD__, null);
        }

        $oldValue = $this->getPath();
        $this->_zendUri->setPath($pathString);
        $result = $oldValue;
        return $result;

    }



    /**
     * @throws Exception
     * @param  string $host
     * @return string
     */
    public function setHost(
        $host
    )
    {
        if (is_string($host)!==true) {
            throw new Exception(
                "Invalid parameter 'host' at ".__METHOD__
            );
        }

        $zendUri = $this->_zendUri;
        if (($zendUri instanceof Zend_Uri_Http)!==true) {
            $this->requireValidUri(__METHOD__, null);
        }

        $oldValue = $this->getHost();
        $this->_zendUri->setHost($host);
        $result = $oldValue;
        return $result;

    }


    /**
     * @throws Exception
     * @param null|string $schemeString
     * @return string
     */
    public function toString($schemeString = null)
    {
        $zendUri = $this->_zendUri;
        if (($zendUri instanceof Zend_Uri_Http)!==true) {
            $this->requireValidUri(__METHOD__, null); 
        }

        if (($schemeString === null)||($schemeString==="")) {
            return $this->getUri();
        }

         if (in_array(
                 $schemeString,
                 array(self::SCHEME_HTTP, self::SCHEME_HTTPS),
                 true) !==true) {
             throw new Exception(
                "Invalid parameter 'schemeString' at ".__METHOD__
            );
        }


        $url = "".$this->getUri();

        $url = Lib_Utils_String::removePrefixByDelimiterIfExists($url, "://");
        return $schemeString."://".$url;
        
    }





    /**
     * @throws Exception
     * @param  $queryString
     * @return string
     */
    public function setQuery($queryString)
    {
        if ($queryString === null) {
            $queryString = "";
        }
        if (is_string($queryString)!==true) {
            throw new Exception(
                "Invalid parameter 'queryString' at ".__METHOD__
            );
        }
        $zendUri = $this->_zendUri;
        if (($zendUri instanceof Zend_Uri_Http)!==true) {
            $this->requireValidUri(__METHOD__, null);
        }

        $oldValue = $this->getQuery();
        $this->_zendUri->setQuery($queryString);
        $result = $oldValue;
        return $result;
    }


    /**
     * @throws Exception
     * @param array $queryParams
     * @return array oldValue
     */
    public function setQueryParameters(array $queryParams)
    {
        $zendUri = $this->_zendUri;
        if (($zendUri instanceof Zend_Uri_Http)!==true) {
            $this->requireValidUri(__METHOD__, null); 
        }
        $oldValue = $this->getQueryAsArray();
        $this->_zendUri->setQuery($queryParams);
        $result = $oldValue;
        return $result;
    }

    /**
     * @throws Exception
     * @param array $queryParams
     * @return array oldValue
     */
    public function mixinQueryParameters(array $queryParams)
    {
        $zendUri = $this->_zendUri;
        if (($zendUri instanceof Zend_Uri_Http)!==true) {
            $this->requireValidUri(__METHOD__, null); 
        }

        $oldValue = $this->getQueryAsArray();
        $zendUri->addReplaceQueryParameters($queryParams);
        $result = $oldValue;
        return $result;

    }

    /**
     * @throws Exception
     * @param array $queryParams
     * @return array oldValue
     */
    public function setQueryParameter($key, $value)
    {
        $zendUri = $this->_zendUri;
        if (($zendUri instanceof Zend_Uri_Http)!==true) {
            $this->requireValidUri(__METHOD__, null); 
        }


        $oldValue = $this->getQueryParameter($key);


        $params = $this->getQueryParameters();
        $params[$key] = $value;
        $this->setQueryParameters($params);
        
        return $oldValue;

    }


    /**
     * @throws Exception
     * @param array $queryParams
     * @return array oldValue
     */
    public function unsetQueryParameter($key)
    {
        $zendUri = $this->_zendUri;
        if (($zendUri instanceof Zend_Uri_Http)!==true) {
            $this->requireValidUri(__METHOD__, null); 
        }


        $oldValue = $this->getQueryParameter($key);

        $filter = $this->newFilter();
        $filter->setWhitelist(array($key));
        $this->removeQueryParametersByFilter($filter);
        
        return $oldValue;

    }


    /**
     * @return Lib_Fnmatch_Filter
     */
    public function newFilter()
    {
        return new Lib_Fnmatch_Filter();
    }

    /**
     * @throws Exception
     * @param Lib_Fnmatch_Filter $filter
     * @return array oldValue
     */
    public function filterQueryParameters(Lib_Fnmatch_Filter $filter)
    {
        $zendUri = $this->_zendUri;
        if (($zendUri instanceof Zend_Uri_Http)!==true) {
            $this->requireValidUri(__METHOD__, null); 
        }

        $queryParams = $this->getQueryAsArray();
        if (is_array($queryParams)!==true) {
            $queryParams = array();
        }

        $oldValue = (array)$queryParams;

        $params = array();
        foreach($queryParams as $key => $value) {

            $isWhitelisted = $filter->isWhitelisted($key, null);
            $isBlacklisted = $filter->isBlacklisted($key, null);
            $keepParam = (($isWhitelisted ===true) && ($isBlacklisted!==true));

            if ($keepParam === true) {
                $params[$key] = $value;
            }

        }

        $zendUri->setQuery($params);

        $result = $oldValue;
        return $result;

    }


    /**
     * @throws Exception
     * @param Lib_Fnmatch_Filter $filter
     * @return array oldValue
     */
    public function removeQueryParametersByFilter(Lib_Fnmatch_Filter $filter)
    {
        $zendUri = $this->_zendUri;
        if (($zendUri instanceof Zend_Uri_Http)!==true) {
            $this->requireValidUri(__METHOD__, null); 
        }

        $queryParams = $this->getQueryAsArray();
        if (is_array($queryParams)!==true) {
            $queryParams = array();
        }

        $oldValue = (array)$queryParams;

        $params = array();
        foreach($queryParams as $key => $value) {

            $isWhitelisted = $filter->isWhitelisted($key, null);
            $isBlacklisted = $filter->isBlacklisted($key, null);
            $removeParam = (($isWhitelisted ===true) && ($isBlacklisted!==true));


            if ($removeParam !== true) {
                $params[$key] = $value;
            }

        }

        $zendUri->setQuery($params);

        $result = $oldValue;
        return $result;

    }


    /**
     * @throws Exception
     * @param array $keysList
     * @return array
     */
    public function unsetQueryParameters(array $keysList)
    {
        $zendUri = $this->_zendUri;
        if (($zendUri instanceof Zend_Uri_Http)!==true) {
            $this->requireValidUri(__METHOD__, null); 
        }


        $queryParams = $this->getQueryParameters();

        $oldValue = (array)$queryParams;

        foreach($keysList as $key) {
            try {
                unset($queryParams[$key]);
            } catch (Exception $error) {
                //NOP
            }
        }

        $this->setQueryParameters($queryParams);

        $result = $oldValue;
        return $result;

    }

    


    /**
     * @return string
     */
    public function getCurrentScheme()
    {
        $result = "";
        try {
            if (isset($_SERVER)!==true) {
                return $result;
            }
        }catch (Exception $error) {
            return $result;
        }
        $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'
          ? 'https'
          : 'http';
        return $scheme;
    }

    /**
     * @return string
     */
    public function getCurrentProtocol()
    {
        $result = "";
        $scheme = $this->getCurrentScheme();
        if(strlen($scheme)>0) {
            return $scheme."://";
        } else {
            return $result;
        }

    }


    /**
     * @return string
     */
    public function getCurrentUrl()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'
          ? 'https://'
          : 'http://';
        $currentUrl = $protocol
                      . $_SERVER['HTTP_HOST']
                      . $_SERVER['REQUEST_URI'];
        return $currentUrl;
    }



}
