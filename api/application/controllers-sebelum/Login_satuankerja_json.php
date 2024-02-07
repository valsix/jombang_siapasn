<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class login_satuankerja_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
		$this->load->library('Kauth');
    }
 
    // show data entitas
	function index_get() {

    }
	
    // insert new data to entitas
    function index_post() {

        $reqUser = $this->input->post("reqUser");
		$reqPasswd = $this->input->post("reqPasswd");
		$reqImei = $this->input->post("reqImei");
		$reqTokenFirebase = $this->input->post("reqTokenFirebase");

        $this->load->model('UserLoginLog');
        $this->load->model('base/Pegawai');
        $this->load->model('base/OrangTua');
        
        $user_login_log = new UserLoginLog();
		$user_login_log->setField("LOGIN_USER", $reqUser);
		$user_login_log->setField("LOGIN_PASS", $reqPasswd);
		$user_login_log->setField("WAKTU_LOGIN", "NOW()");
        $user_login_log->setField("STATUS", "1");
        $user_login_log->setField("TOKEN_FIREBASE", $reqTokenFirebase);
        $user_login_log->setField("IMEI", $reqImei);
		
        $temp = array();
		// echo "asd";exit();
		if(!empty($reqUser) AND !empty($reqPasswd))
		{
			// set untuk validasi nama ibu dan tanggal lahir ibu
			$set_detil= new UserLoginLog();
			$statement= " AND A.LOGIN_USER = '".$reqUser."' ";
        	$set_detil->selectByParamsLogin(array(), -1,-1, $statement);
        	$set_detil->firstRow();
        	// echo $set_detil->query;exit();
        	$reqLoginUser= $set_detil->getField("LOGIN_USER");
            $reqPegawaiId= $set_detil->getField("PEGAWAI_ID");
        	$reqSatuanKerjaId= $set_detil->getField("SATUAN_KERJA_ID");
        	unset($set_detil);
        	// echo $reqPegawaiId."-";exit();

            $respon = $this->kauth->localCryptAuthenticate($reqUser,$reqPasswd);

            if($respon == "1")
            {
            	if(empty($reqPegawaiId) && !empty($reqSatuanKerjaId) )
            	{
            		if ($user_login_log->insert()) 
                    {
                        $reqTokenData= $user_login_log->idToken;
                        $this->response(array('status' => 'success', 'message' => 'Anda berhasil Login.','token' => $reqTokenData, 'code' => 200));
                    } 
                    else 
                    {
                        $this->response(array('status' => 'fail', 'message' => 'user pegawai harus null, dan satuan kerja harus ada nilai nya.', 'code' => 502));
                    }
            	}
            }
            else
            {
                $this->response(array('status' => 'fail', 'message' => 'Username atau Password salah.', 'code' => 503));
            }
        }
        else
        {
            $this->response(array('status' => 'fail', 'message' => 'Masukkan Username dan Password.', 'code' => 504));
        }

    }
 
    // update data entitas
    function index_put() {
    }
 
    // delete entitas
    function index_delete() {
    }
 
}