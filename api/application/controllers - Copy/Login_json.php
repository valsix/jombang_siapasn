<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class login_json extends REST_Controller {
 
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
        
        $user_login_log = new UserLoginLog();
		$user_login_log->setField("LOGIN_USER", $reqUser);
		$user_login_log->setField("LOGIN_PASS", $reqPasswd);
		$user_login_log->setField("WAKTU_LOGIN", "NOW()");
        $user_login_log->setField("STATUS", "1");
        $user_login_log->setField("TOKEN_FIREBASE", $reqTokenFirebase);
        $user_login_log->setField("IMEI", $reqImei);
		
        $temp = array();
		
		if(!empty($reqUser) AND !empty($reqPasswd))
		{
			// if($reqUser == $reqPasswd)
			// {
				// if($reqUser == '9014140KP')
					$respon_bypass = '1';
				// else
				// 	$respon_bypass = '0';
			// }
			// else
			// $respon = $this->kauth->portalAuthenticate($reqUser,$reqPasswd);

			// if($respon->RESPONSE == "1" || $respon->RESPONSE == "PAGE" || $respon_bypass == "1")
			// {
				if ($user_login_log->insert()) 
				{
					$this->response(array('status' => 'success', 'message' => 'Anda berhasil Login.','token' => $user_login_log->idToken, 'code' => 200));
				} else {
					$this->response(array('status' => 'fail', 'message' => 'Anda Gagal Login, Cobalah Beberapa Saat Lagi.', 'code' => 502));
				}
			// }
			// else
			// {
				// $responMessage = $this->kauth->localAuthenticate($reqUser,$reqPasswd);
				// if($responMessage == "")
				// {
				// 	if ($user_login_log->insert()) 
				// 	{
				// 		$this->response(array('status' => 'success', 'message' => 'Anda berhasil Login.','token' => $user_login_log->idToken, 'code' => 200));
				// 	} else {
				// 		$this->response(array('status' => 'fail', 'message' => 'Anda Gagal Login, Cobalah Beberapa Saat Lagi.', 'code' => 502));
				// 	}
				// }
				// else
			// 		$this->response(array('status' => 'fail', 'message' => 'Username atau Password salah.', 'code' => 502));
			// }
		}
		else
		{
				$this->response(array('status' => 'fail', 'message' => 'Masukkan Username dan Password.', 'code' => 502));
		}

    }
 
    // update data entitas
    function index_put() {
    }
 
    // delete entitas
    function index_delete() {
    }
 
}