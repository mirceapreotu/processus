<?php
/**
 * Lib_Url_Manager Class
 *
 * @experimental !!!!!!!!!!!!!
 * @package Lib_Url
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Url_Manager
 *
 * @experimental  !!!!!!!!
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
class Lib_Url_Manager
{

    //@TODO: TIDY UP
    //@TODO: Parse relative files/urls ,e.g.: foo/bar/../..baz and check if this is allowed by filter
        protected $_accessFilterConfig = array(
            "flags" => null,
            "whitelist" => array(
                "{PROJECTROOT}/*"
            ),
            array(
            )
        );


    /**
     * @return Zend_Config
     */
        public function getAccessFilterConfig()
        {
            $config = (array)$this->_accessFilterConfig;


            $projectRoot = $this->getProjectRoot();
            if (Lib_Utils_String::endsWith($projectRoot, "/", false)) {
                $projectRoot = Lib_Utils_String::removePostfix(
                    $projectRoot, "/", false
                );
            }


            $_config = array(
                "flags" => Lib_Utils_Array::getProperty($config, "flags"),
                "whitelist" => (array)Lib_Utils_Array::getProperty(
                    $config, "whitelist"
                ),
                "blacklist" => (array)Lib_Utils_Array::getProperty(
                    $config, "blacklist"
                ),
            );
            $config = $_config;

            $whitelist = $config["whitelist"];
            foreach($whitelist as $key => $pattern) {
                $pattern = str_replace(
                    array(
                        "{PROJECTROOT}",
                    ),
                    array(
                        $projectRoot,
                    ),
                    $pattern
                );
                $whitelist[$key] = $pattern;
            }
            $config["whitelist"] = $whitelist;

            $blacklist = $config["blacklist"];
            foreach($blacklist as $key => $pattern) {
                $pattern = str_replace(
                    array(
                        "{PROJECTROOT}",
                    ),
                    array(
                        $projectRoot,
                    ),
                    $pattern
                );
                $blacklist[$key] = $pattern;
            }
            $config["blacklist"] = $blacklist;


            return new Zend_Config($config);

        }


    /**
     * @var Lib_Fnmatch_Filter
     */
        protected $_accessFilter;







    /**
     * @return Lib_Fnmatch_Filter
     */
        public function getAccessFilter()
        {
            if (($this->_accessFilter instanceof Lib_Fnmatch_Filter)!==true) {
                $config = $this->getAccessFilterConfig();
                $filter = new Lib_Fnmatch_Filter();
                $filter->applyConfig($config);
                $this->_accessFilter = $filter;
            }

            return $this->_accessFilter;
        }

    /**
     * @param  $file
     * @return bool
     */
    public function isAllowedFile($file) {

        $filter = $this->getAccessFilter();


        $isWhitelisted = $filter->isWhitelisted($file, null);
        $isBlacklisted = $filter->isBlacklisted($file, null);

        return (bool)(($isWhitelisted===true) && ($isBlacklisted!==true));

    }

        /**
         * @throws Exception
         * @return string
         */
        public function getProjectRoot()
        {
            $value = realpath(Bootstrap::getRegistry()->getHtdocsPath("/.."));
            if (Lib_Utils_String::isEmpty($value)) {
                throw new Exception("Invalid 'projectRoot' at ".__METHOD__);
            }
            if (Lib_Utils_String::endsWith($value, "/", false)!==true) {
                $value .= "/";
            }
            return $value;
        }









        /**
         * @return array
         */
        public function getDomainConfig()
        {

            $result = array(
                "www.foo.exitb.de" => array(
                    "docRoot"=> "{PROJECTROOT}/htdocs/",
                    "isSSL" => false,
                ),
                "ssl-foo.exitb.de" => array(
                    "docRoot"=> "{PROJECTROOT}/htdocs/",
                    "isSSL" => true,
                ),


                "fb.foo.exitb.de" => array(
                    "docRoot"=> "{PROJECTROOT}/htdocs/fb/",
                    "isSSL" => false,
                ),
                "ssl-fb.foo.exitb.de" => array(
                    "docRoot"=> "{PROJECTROOT}/htdocs/fb/",
                    "isSSL" => true,
                ),
            );
            return $result;

        }



        public function getDomainConfigParsed()
        {
            $result = array();
            $config = $this->getDomainConfig();
            if (is_array($config)!==true) {
                return $result;
            }

            $projectRoot = $this->getProjectRoot();
            $projectRoot = Lib_Utils_String::removePostfix($projectRoot, "/", false);
            if (Lib_Utils_String::isEmpty($projectRoot)) {
                throw new Exception("Invalid projectRoot at ".__METHOD__);
            }

            foreach($config as $domain => $info) {

                if (Lib_Utils_String::isEmpty($domain)) {
                    throw new Exception("Invalid domain at ".__METHOD__);
                }
                $domain = strtolower($domain);
                $info["host"] = $domain;
                $info["isSSL"] = (Lib_Utils_Array::getProperty($info, "isSSL")===true);


                $docRoot = Lib_Utils_Array::getProperty($info, "docRoot");
                $docRoot = Lib_Utils_String::removePostfix($docRoot, "/", false);
                if (Lib_Utils_String::isEmpty($docRoot)) {
                    throw new Exception("Invalid docRoot for domain='".$domain."' at ".__METHOD__);
                }

                $docRoot = str_replace("{PROJECTROOT}", $projectRoot, $docRoot );
                if (Lib_Utils_String::isEmpty($docRoot)) {
                    throw new Exception("Invalid docRoot for domain='".$domain."' at ".__METHOD__);
                }
                if (Lib_Utils_String::endsWith($docRoot, "/", false)!==true) {
                    $docRoot .= "/";
                }

                $info["docRoot"] = $docRoot;

                $result[$domain] = $info;


            }

            return $result;

        }



        /**
         * @throws Exception
         * @param  $host
         * @return array
         */
        public function getDomainInfoByHost($host)
        {
            if (is_string($host)!==true) {
                throw new Exception("Invalid parameter 'host' at ".__METHOD__);
            }
            $domain = strtolower($host);


            $domainConfig = $this->getDomainConfigParsed();
            $info = Lib_Utils_Array::getProperty($domainConfig, $domain);

            $result = array(
                "host" => Lib_Utils_Array::getProperty($info, "host"),
                "isSSL" => Lib_Utils_Array::getProperty($info, "isSSL"),
                "docRoot" => Lib_Utils_Array::getProperty($info, "docRoot"),
            );

            if ($result["host"]===null) {
                $result["host"] = $domain;
            }
            return $result;
        }




    /**
     * @throws Exception
     * @param  string $file
     * @param  string $currentUrl
     * @return string
     */
        public function fileToUrl($file, $currentUrl)
        {




            $zendUri = new Lib_Url_Uri(null);
            if ($zendUri->isValidUri($currentUrl)!==true) {
                throw new Exception("Invalid 'currentUrl' at ".__METHOD__);
            }
            $zendUri->setUri($currentUrl);

            if (is_string($file)!==true) {
                throw new Exception("Invalid parameter 'file' at ".__METHOD__);
            }
            if (Lib_Utils_String::isEmpty($file)) {
                throw new Exception("Invalid parameter 'file' at ".__METHOD__);
            }


            if ($this->isAllowedFile($file)!==true) {
                throw new Exception(
                    "Invalid parameter 'file' is not allowed by filter at "
                    .__METHOD__);
            }


            $host = $zendUri->getHost();
            $protocol = $zendUri->getProtocol();

            $domainInfo = $this->getDomainInfoByHost($host);


            $domainDocRoot = $domainInfo["docRoot"];
            if (Lib_Utils_String::isEmpty($domainDocRoot)===true) {
                throw new Exception(
                    "No docRoot defined for domain '".$host."' at ".__METHOD__
                );
            }

            if (Lib_Utils_String::endsWith($domainDocRoot, "/", false)!==true) {
                $domainDocRoot .= "/";
            }



            if (Lib_Utils_String::endsWith($file, "/", false)!==true) {
                $file .= "/";
            }

            if (Lib_Utils_String::startsWith($file, $domainDocRoot, false)!==true) {
                throw new Exception(
                    "file must equal/be inside of domainDocRoot at "
                    .__METHOD__
                );
            }


            $path = Lib_Utils_String::removePrefix($file, $domainDocRoot, false);

            $path = Lib_Utils_String::removePostfix($path, "/", false);


            $domainHost = $domainInfo["host"];
            $domainIsSSL = ($domainInfo["isSSL"]===true);
            //return $path;

            if (Lib_Utils_String::startsWith($path, "/", false)!==true) {
                $path = "/".$path; // to make it zend conform
            }
            $zendUri->setPath($path);


            $zendUri->setHost($domainHost);
            if ($domainIsSSL === true) {
                $result = $zendUri->toString(Lib_Url_Uri::SCHEME_HTTPS);
                $result = Lib_Utils_String::removePostfix($result, "/", false);
                return $result;
            }

            if ($zendUri->getProtocol() === Lib_Url_Uri::SCHEME_HTTPS) {
                $result = $zendUri->toString(Lib_Url_Uri::SCHEME_HTTPS);
                $result = Lib_Utils_String::removePostfix($result, "/", false);
                return $result;
            }

            $result = $zendUri->toString(null);
            $result = Lib_Utils_String::removePostfix($result, "/", false);
            return $result;


        }


        public function urlToFile($url)
        {

            /*
            $currentUrlUri = new Lib_Url_Uri(null);
            if ($currentUrlUri->isValidUri($currentUrl)!==true) {
                throw new Exception("Invalid 'currentUrl' at ".__METHOD__);
            }
            $currentUrlUri->setUri($currentUrl);

            */
            $urlUri = new Lib_Url_Uri(null);
            if ($urlUri->isValidUri($url)!==true) {
                throw new Exception("Invalid 'url' at ".__METHOD__);
            }
            $urlUri->setUri($url);

            $urlHost = $urlUri->getHost();
            $urlInfo = $this->getDomainInfoByHost($urlHost);

            $urlInfoHost = $urlInfo["host"];
            $urlInfoIsSSL = ($urlInfo["isSSL"]===true);
            $urlInfoDocRoot = $urlInfo["docRoot"];
            /*
            $urlInfoDocRoot = Lib_Utils_String::removePostfix(
                $urlInfoDocRoot, "/", false
            );
            */
            if (Lib_Utils_String::isEmpty($urlInfoDocRoot)) {
                throw new Exception("Invalid docRoot for url at ".__METHOD__);
            }

            $urlPath = $urlUri->getPath();

            $urlPath = Lib_Utils_String::removePrefix($urlPath, "/", false);
            $urlPath = Lib_Utils_String::removePostfix($urlPath, "/", false);

            $urlInfoDocRoot = Lib_Utils_String::removePostfix($urlInfoDocRoot, "/", false);

            $result = $urlInfoDocRoot;
            if (Lib_Utils_String::isEmpty($urlPath)!==true) {
                $result .= "/".$urlPath;
            }

            return $result;
        }



}
