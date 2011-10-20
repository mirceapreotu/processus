<?php
/**
 * App_Filter Class
 *
 * @category	meetidaaa.com
 * @package		App
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */

/**
 * App_Filter 
 *
 * @category	meetidaaa.com
 * @package		App
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */

class App_Filter
{

	/**
	 * Clean up name to leave only lowercase ascii 7bit characters
	 * @param string $name
	 * @return string
	 */
	public static function clean($name)
	{

	    $cleanName = strtolower(trim($name));        
	
	    // Swap tokens if any
	    // "Clash, The/Jackson, Michael" should become 
	    // "The Clash Michael Jackson" 
	    //--
	    $multipleArtists = explode("/", $cleanName);
	    $cleanName = '';
	    foreach ($multipleArtists as $artist) {
	        $tokens = explode(",", $artist);
	        $finalName = '';
	        for ($i=count($tokens) - 1; $i>=0; $i--) {
	            $finalName .= $tokens[$i] . ' ';
	        }
	        $cleanName .= $finalName;
	        $cleanName .= " ";
	    }
	    $cleanName = trim($cleanName);
	
	    // Clean up characters
	    //--
	    $cleanName = str_replace(array("ä", "Ä"), "ae", $cleanName);
	    $cleanName = str_replace(array("ü", "Ü"), "ue", $cleanName);
	    $cleanName = str_replace("ß", "ss", $cleanName);
	    $cleanName = str_replace(array("ö", "Ö"), "oe", $cleanName);
	    $cleanName = str_replace(
	    	array("é", "É", "è", "È", "ê", "Ê"), "e", $cleanName
	    );
	    $cleanName = str_replace(
	    	array("á", "Á", "À", "à", "â", "Â"), "a", $cleanName
	    );
	    $cleanName = str_replace(
	    	array("ú", "Ú", "ù", "Ù", "û", "Û"), "u", $cleanName
	    );
	    $cleanName = str_replace(array("ñ", "Ñ"), "n", $cleanName);
	    $cleanName = preg_replace('/[^A-Z0-9]/', ' ', strtoupper($cleanName));
	    $cleanName = preg_replace('/  +/', ' ', $cleanName);        
	
	    // remove leading or trailing '-' if any
	    
		$cleanName = trim($cleanName);
	
	    return $cleanName;		
		
	}
}
