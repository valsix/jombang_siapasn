<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Kauth
 *
 * @author user
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class qrcodegenerator{
	
	var $NIP;
	
    /******************** CONSTRUCTOR **************************************/
    function __construct(){
	
		 $this->emptyProps();
    }

    /******************** METHODS ************************************/
    /** Empty the properties **/
    function emptyProps(){
    }
		
    function generateQr($reqData=''){
    	include_once("functions/image.func.php");
		include_once("functions/string.func.php");
		include_once("functions/encrypt.func.php");
		/* GENERATE QRCODE */
		include_once("libraries/phpqrcode/qrlib.php");
		$CI =& get_instance();
		
		$reqkunci = 'siapasn02052018';
		$arrNip = explode('-',$reqdata);
		$reqNip = $arrNip[1];
		$reqToken =  mencrypt($reqdata, $reqkunci);
 		$reqLokasiParaf = $CI->config->item('base_url').'loginqrcode'.'/'.$reqToken;
 		$FILE_DIR= "uploads/qr/";
		makedirs($FILE_DIR);
		$filename = $FILE_DIR.$reqNip.'.png';
		$filepath =  $filename;
		
		if(file_exists($filename))
			return;

		$errorCorrectionLevel = 'H';   
		$matrixPointSize = 2;

		QRcode::png($reqLokasiParaf, $filename, $errorCorrectionLevel, $matrixPointSize, 2);   
		
		// Apabila mengunakan logo Kecil di tengah
		$logoThumps = "images/logo_jombang.png";
		$QR = imagecreatefrompng($filepath);
		$logo = imagecreatefromstring(file_get_contents($logoThumps));
		imagecolortransparent($logo , imagecolorallocatealpha($logo , 0, 0, 0, 127));
		imagealphablending($logo , false);
		imagesavealpha($logo , true);

		$QR_width = imagesx($QR);
		$QR_height = imagesy($QR);

		$logo_width = imagesx($logo);
		$logo_height = imagesy($logo);
		// Scale logo to fit in the QR Code
		$logo_qr_width = $QR_width/3;
		$scale = ($logo_width/$logo_qr_width)*1.3;
		$logo_qr_height = $logo_height/$scale;
		imagecopyresampled($QR, $logo, $QR_width/3, $QR_height/3, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
		
		// Save QR code again, but with logo on it
		imagepng($QR,$filepath);

    }
    
			   
}
	
  /***** INSTANTIATE THE GLOBAL OBJECT */
  $qrCode = new qrcodegenerator();

?>
