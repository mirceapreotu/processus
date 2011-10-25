<?php
/**
 * Lib_Http_ProxyServer_Image
 *
 * @package		Lib_Http_ProxyServer
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 *
 */

/**
 * Lib_Http_ProxyServer_Image
 *
 * @package Lib_Http_ProxyServer
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */
class Lib_Http_ProxyServer_Image
    extends Lib_Http_ProxyServer_ProxyServerAbstract
{


    // from gd lib
    const IMAGETYPE_GIF = 	"image/gif";
    const IMAGETYPE_JPEG = 	"image/jpeg";
    const IMAGETYPE_PNG = 	"image/png";
    const IMAGETYPE_SWF = 	"application/x-shockwave-flash";
    const IMAGETYPE_PSD = 	"image/psd";
    const IMAGETYPE_BMP = 	"image/bmp";
    const IMAGETYPE_TIFF_II = "image/tiff"; // (intel byte order)
    const IMAGETYPE_TIFF_MM = "image/tiff"; //(motorola byte order)
    const IMAGETYPE_JPC =  	"application/octet-stream";
    const IMAGETYPE_JP2 = 	"image/jp2";
    const IMAGETYPE_JPX = 	"application/octet-stream";
    const IMAGETYPE_JB2 = 	"application/octet-stream";
    const IMAGETYPE_SWC = 	"application/x-shockwave-flash";
    const IMAGETYPE_IFF = 	"image/iff";
    const IMAGETYPE_WBMP = 	"image/vnd.wap.wbmp";
    const IMAGETYPE_XBM = 	"image/xbm";
    const IMAGETYPE_ICO = 	"image/vnd.microsoft.icon";

    // more mimetypes : http://www.homepage-forum.de/showthread.php?t=20316
    // http://www.iana.org/assignments/media-types/index.html


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
                "image/*",
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
     * @param  string $mime
     * @return bool
     */
    /*
    public function isAllowedMimeType($mime)
    {
        $result = false;

        $mime = strtolower(trim($mime));
        if (Lib_Utils_String::startsWith($mime,"image/",true)) {
            return true;
        }


        return $result;
    }
    */

    /**
     * @return void
     */
    protected function _onSuccess()
    {


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
     * we have a response, but with an error
     * @return void
     */
    protected function _onError()
    {
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
//var_dump($e);
//var_dump(json_encode($this->getConfig()->toArray()));

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



}
