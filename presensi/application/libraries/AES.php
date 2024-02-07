<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'kloader.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Kauth
 *
 * @author user
 */
class AES {

    public function decrypt($plainText) {
		
		$key = pack("H*", "b4109a5121d74943afb13ed2798d1348");
		$iv =  pack("H*", "8ac1891827f40e0d29c98a12a4161e28");
		//Now we receive the encrypted from the post, we should decode it from base64,
		$encrypted = base64_decode($plainText);
		$shown = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $encrypted, MCRYPT_MODE_CBC, $iv);
		return preg_replace('/[\x00-\x1F\x7F]/', '', $shown);
		return $this->clean($shown);
		
    }
		
	function clean($string) {
	   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
	   $string = preg_replace('/[^A-Za-z0-9\-:]/', '', $string); // Removes special chars.
	
	   return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
	}	

}
?>
