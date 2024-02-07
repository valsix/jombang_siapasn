<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 include_once("functions/encrypt.func.php");
class test_baru_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        // $this->db->query("alter session set nls_date_format='DD-MM-YYYY'"); 
        
    }
 
    // show data entitas
	function index_get() {
         $this->load->model('base-new/Pegawai');
         $reqkunci = 'siapasn02052018';
         $pegawai = new Pegawai();
         $pegawai->selectByParams(array());
         $arrData =  $pegawai->rowResult;
         for($i=0;$i<count($arrData);$i++){
            $reqData = $arrData[$i]['pegawai_id'].'-'.$arrData[$i]['nip_baru'];
            $reqToken =  mencrypt($reqData, $reqkunci);
            echo $reqToken.'<br>';
            $arrDatas = mdecrypt($reqToken,$reqkunci);
            $arrDatas = explode('-',$arrDatas);
            print_r($arrDatas).'<br>';

            $arrDBField = array(
                "token_baru_pegawai"=>$reqToken,
            );
            $this->db->where('pegawai_id', $arrData[$i]['pegawai_id']);
            $this->db->update('pegawai', $arrDBField);
         }
      
                
                
                $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($aColumns) ,'result' => $result));
            }
        

    

	
  
 
    // update data entitas
    function index_put() {
    }
 
    // delete entitas
    function index_delete() {
    }
 
}