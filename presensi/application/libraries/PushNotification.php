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

// define( 'API_ACCESS_KEY', 'AAAAITF1z8s:APA91bEieU34RMFZJbR-TMXrcB4tr_7okOMJpAlMhmHQbcj79_pZu1Q6hqdHwHSrK0fmodKZq3N55r9ftbbTxeoBWfv0Qpfbp9YW7Y_OdXM9vJof04s_gnA-UzJc5Rlwi3fuZ211ocXq');
// define( 'API_ACCESS_KEY', 'AAAAvx4PF_w:APA91bFUWgFcarrtLq2XdAjufnRQbSWd5db4J8HiJ0N7JdGrhxavOsNaP_u696IyxKzMdWgyappBN9HpJIzgX6Hrd3CHDefHztdb8dwMzfLnn2mQUZERg7WeCd6d88-W19QupM82Ltpn');

// API DARI FIREBASE BULOG
define( 'API_ACCESS_KEY', 'AAAAFzSlPjo:APA91bGrIsGB5hOE-QbGQoimFliawivh5UubAIHAOaPUpqbGWi2R4RVv-CfI1orehojCoMh59X0iDzw5W2novuMi6IOSujk41GaVMdeA2xdU2V5P5Lw7XrwrsI0HmPnqegAbuoF3F6r1');

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
			'sound' => 'default',
			'icon'	=>'default'
		);
		
		$data = array
		(
			'type'	=> $type,
			'id'	=> $id,
			'jenis'	=> $jenis
		);

		$fields = array
		(
			'to'			=> $tokenFirebase,
			'notification'	=> $msg,
			'data'			=> $data
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

		$this->hasil = $result;

		curl_close( $ch );

	}

    /** Verify user login. True when login is valid**/
    function send_notification_bak($tokenFirebase, $type, $id, $jenis, $title, $body){
    	
  //   	{
		// 	"to":"cuq7DLg-aQw:APA91bG6EAEwnHhn7HVAxhBaUhjvEEp4PgGoHySGU_wjoel7IfRAQimT7SmxcfpL4Wr6D0yuHZjeH4xz0UtNjVC4jaPD3M5cY38aQ6ejnL2w368toUbES7p_D4DbH00m-CKgX3_2Cowj",
		// 	"data": {
		//         "title" : "New Job",
		// 		"text"  : "Ada Job Baru nih..!",
		// 		"click_action":"app.suyaexpedisi.com_job_notification",
		// 		"body":{
		// 			"id": 1,
		// 	        "no_resi": "129920171220125253",
		// 	        "user_id": "12",
		// 	        "service_id": "1",
		// 	        "origin_district_id": "29",
		// 	        "origin_address": "Jl.awal",
		// 	        "consignee_name": "Suya",
		// 	        "consignee_phone": "089672400820",
		// 	        "destinatio_district_id": "9",
		// 	        "destinatio_address": "Jl.kita bersama",
		// 	        "item_name": "kaos oblong 2",
		// 	        "information": "janagan jagan",
		// 	        "weight": "2",
		// 	        "date_of_shipment": "2017-12-20",
		// 	        "status": "new",
		// 	        "price": "10000",
		// 	        "created_at": "2017-12-20 12:52:53",
		// 	        "updated_at": "2018-01-08 07:21:44",
		// 	        "origin": {
		// 	            "id": 29,
		// 	            "name": "kalimantan",
		// 	            "city_id": "1",
		// 	            "city": {
		// 	                "name": "Surabaya",
		// 	                "id": 1
		// 	            }
		// 	        },
		// 	        "destinatio": {
		// 	            "id": 9,
		// 	            "name": "Jambangan",
		// 	            "city_id": "1",
		// 	            "city": {
		// 	                "name": "Surabaya",
		// 	                "id": 1
		// 	            }
		// 	        },
		// 	        "service": {
		// 	            "id": 1,
		// 	            "name": "OKE"
		// 	        },
		// 	        "user": {
		// 	            "id": 12,
		// 	            "name": "dicky",
		// 	            "phone": "089672400820"
		// 	        }
		// 		}
		//     }
			
		// }

		#prep the bundle
		$msg = array
		(
			'body' 	=> $body,
			'title'	=> $title,
			'sound' => 'default',
		);
		
		$data = array
		(
			'title' => $title,
			'text'	=> $body,
			'sound' => 'default',
			'type'	=> $type,
			'jenis'	=> $jenis,
			'body' 	=> $body
		);

		$fields = array
		(
			'to'			=> $tokenFirebase,
			// 'notification'	=> $msg,
			'data'			=> $data
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
		// echo $result;

		$this->hasil = $result;

		curl_close( $ch );

	}
			   
}
	
  /***** INSTANTIATE THE GLOBAL OBJECT */
  $pushNotification = new PushNotification();

?>
