<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Upload_file_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
        $this->load->library('globalvalidasifilepegawai');
    }

    // insert new data to entitas
    function index_post() {
        $this->load->model('UserLoginLog');

        $user_login_log= new UserLoginLog;

        $reqToken = $this->input->post("reqToken");
        // $this->response(array('reqToken' => $reqToken));exit();

        //CEK PEGAWAI ID DARI TOKEN
        $user_login_log = new UserLoginLog();
        $reqPegawaiId= $user_login_log->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        // echo $reqPegawaiId;exit;
        // echo $user_login_log->query;exit();

        if($reqPegawaiId == "")
        {
            $this->response(array('status' => 'fail', 'message' => 'Anda tidak berhak untuk mendapatkan info personal pegawai.', 'code' => 502));
        }
        else
        {
            $vpost= $this->input->post();
            $reqTempValidasiId= $this->input->post("reqTempValidasiId");
            $reqRowId= $this->input->post("reqRowId");
            $reqLinkFile= $_FILES["reqLinkFile"];
            // print_r($reqLinkFile);exit;

        	$arrparam= ["reqPegawaiId"=>$reqPegawaiId, "reqRowId"=>$reqRowId, "reqTempValidasiId"=>$reqTempValidasiId];
        	$vsimpanfilepegawai= new globalvalidasifilepegawai();
        	$vsimpanfilepegawai->simpanfilepegawai($vpost, $reqLinkFile, $arrparam);
        }
    }
}