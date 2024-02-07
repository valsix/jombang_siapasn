<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class jabatan_pns_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// trow to unauthenticated page!
			//redirect('Login');
		}       
		
		/* GLOBAL VARIABLE */
		$this->LOGIN_USER= $this->kauth->getInstance()->getIdentity()->LOGIN_USER;
		$this->LOGIN_LEVEL= $this->kauth->getInstance()->getIdentity()->LOGIN_LEVEL;
		$this->LOGIN_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_ID;
		$this->LOGIN_PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID;
	}

	
    function index(){
        $reqNip = $this->input->get('reqNip');

        if(empty($reqNip)){
          $arrDataStatus =array("PESAN"=>'Data gagal di proses','status' => 'fail',"code"=>502);
          echo json_encode( $arrDataStatus,true);exit;
        }

       $settingurlapi= $this->config->config["settingurlapi"];
       $url =  $settingurlapi.'Data_jabatan_pns_json?nip='.$reqNip;
        // echo $url;

       $arrContextOptions=array(
        "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
            ),
        );

       $this->db->query("delete from bkn.jabatan where nipbaru='".$reqNip."'");
       $html= file_get_contents($url, false, stream_context_create($arrContextOptions));
       $arrData = json_decode($html,true);
       $arrDataResult = $arrData['result'];
       if($arrDataResult=='record not found'){
        echo json_encode($arrData);exit;
       }
       
       for($i=0;$i<count($arrDataResult);$i++){
        $arrDataResultField =$arrDataResult[0];       
        $arrFieldkeys = array_change_key_case($arrDataResultField, CASE_LOWER);
        $this->db->insert('bkn.jabatan', $arrFieldkeys);
      }

       $arrDataStatus =array("PESAN"=>'Data berhasil di proses','status' => 'succes',"code"=>200);
       echo json_encode( $arrDataStatus,true);
     

    }
  

}
?>