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

// KEY DARI DENY
// define( 'API_ACCESS_KEY', 'AAAA0GMbFaA:APA91bH-S7XG_WNnMQBBmr_maQMB-vLFTOnuAh801KSRO6pcDok1kxWjswb407uURsmbkiQLJI6JrC-wJxtQcqMlzdwtuPvF3Va741nzXdXqqtLrz27r1dmANYgx7pIwqF3QYCKXWsfz');

// KEY DARI PJBS
// define( 'API_ACCESS_KEY', 'AAAAo3iHmm8:APA91bEbE1KxWRglN57Ep_xNmG-1XSpGbRCRP4--t7TCwKZt1DpwoM8RN1EmbvpjO81E3hl0wWOPtsDOhhUOkGFPWtvkJp8WvXCz7RMKW_HndAfvqB6pqNo0qqOMhvyMkTRq6gz9k_si');

define( 'API_ACCESS_KEY', 'AAAAj_z4Pcg:APA91bFF6hSY60lWcHq7t5SZ7Re-x7J7zlOyFhUkCgN7do3tfQF6ueazflAgG_q5pE9fsyBOgyurVnHUte9UO_sMyn8Efy_Jxwh-2bqCaZiip2OJIelKgb83oUMBpzunRx8BNjPjmMUv');



class PushNotification{
	var $tokenFirebase; 
	var $type;
	var $id;
	var $jenis;
	var $title;
	var $body;
	
    /******************** CONSTRUCTOR **************************************/
    function PushNotification(){
		 $this->emptyProps();
    }

    /******************** METHODS ************************************/
    /** Empty the properties **/
    function emptyProps(){
		$this->tokenFirebase = "";
		$this->type = "";
		$this->id = "";
		$this->jenis = "";
		$this->title = "";
		$this->body = "";

    }
		
    
    /** Verify user login. True when login is valid**/
    function send_notification($tokenFirebase, $type, $id, $jenis, $title, $body){
    	// echo 'Hello';

		#prep the bundle
		$msg = array
		(
			'body' 	=> $body,
			'title'	=> $title,
			'icon'=>'default',
			'sound'=>'default'
		);
		
		// $data = array
		// (
		// 	'type'	=> $type,
		// 	'id'	=> $id,
		// 	'jenis'	=> $jenis
		// );

		$fields = array
		(
			'to'			=> $tokenFirebase,
			'notification'	=> $msg,
			'icon'			=>'default',
			'sound'			=>'default',
			'data' 			=> array ( 
								'id'	=> $id,
								'type' 	=> $type,
			                    'jenis'	=> $jenis,
								'body' 	=> $body,
								'title'	=> $title,
							)
		);
		
		$headers = array
		(
			'Authorization: key=' . API_ACCESS_KEY,
			'Content-Type: application/json'
		);
		
		#Send Reponse To FireBase Server	
		
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		// print_r($result);

		$this->hasil = $result;

		curl_close( $ch );

	}
			   
}
	
  /***** INSTANTIATE THE GLOBAL OBJECT */
  $pushNotification = new PushNotification();

?>
