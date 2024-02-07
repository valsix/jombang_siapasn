<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once("functions/image.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");

class login extends CI_Controller {

	function __construct() {
		parent::__construct();		
		// $this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->load->library('kauth');   
		// $this->ID = $this->kauth->getInstance()->getIdentity()->ID;
		$this->USER_LOGIN_ID= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;
	}
	
	public function index()
	{
		if(!empty($this->USER_LOGIN_ID))
		{
			redirect('app');
		}

		// $pg = $this->uri->segment(3, "home");
		$pg = $this->uri->segment(3, "login");
		$reqParse1 = $this->uri->segment(4, "");
		$reqParse2 = $this->uri->segment(5, "");
		$reqParse3 = $this->uri->segment(6, "");
		$reqParse4 = $this->uri->segment(7, "");
		$reqParse5 = $this->uri->segment(5, "");

		$view = array(
			'pg' => $pg,
			'reqParse1' => $reqParse1,
			'reqParse2'	=> $reqParse2,
			'reqParse3'	=> $reqParse3,
			'reqParse4'	=> $reqParse4,
			'reqParse5'	=> $reqParse5
		);	
		
		$data = array(
			'content' => $this->load->view("main/".$pg,$view,TRUE),
			'pg' => $pg,
			'reqParse1' => $reqParse1,
			'reqParse2'	=> $reqParse2,
			'reqParse3'	=> $reqParse3,
			'reqParse4'	=> $reqParse4,
			'reqParse5'	=> $reqParse5
		);	


		$this->load->view('main/login', $data);
	}	
	
	public function action()
	{
		$this->load->library("crfs_protect"); $csrf = new crfs_protect('_crfs_login');
		// $this->load->model('UserLoginLog');
		// $this->load->model('UserLoginBlokir');
		// $this->load->model('UserLoginLogStatus');
		
		// $user_login_log = new UserLoginLog;
		// $user_login_log_delete = new UserLoginLog;
		// $user_login_log_cek = new UserLoginLog;
		// $user_login_blokir_cek = new UserLoginBlokir;
		// $user_login_log_status = new UserLoginLogStatus;
		
		// $this->session->set_userdata("AFTER_LOGIN","1");
		$reqUser = $this->input->post("reqUser");
		$reqPasswd = urlencode($this->input->post("reqPasswd"));
		// $reqUser = strtoupper($_POST["reqUser"]);
		// $reqPasswd = strtoupper(urlencode($_POST["reqPasswd"]));
		$reqMode = $_POST["reqMode"];
		

		// if($reqMode != "mobile")
		// {
		// 	if (!$csrf->isTokenValid($_POST['_crfs_login']))
		// 		exit();
		// }
		// echo $_POST['_crfs_login']."-";exit();
		
		// $ada = $user_login_log_cek->getCountByParams(array("A.PEGAWAI_ID" => $reqUser), " AND TANGGAL_LOGIN = CURRENT_DATE");
		// $blokir = $user_login_blokir_cek->getcountByParams(array("A.PEGAWAI_ID" => $reqUser), " AND WAKTU > CURRENT_TIMESTAMP ");
		
		if(!empty($reqUser) AND !empty($reqPasswd))
		{
			$respon = $this->kauth->localAuthenticate($reqUser,$reqPasswd);
			
			if($respon == true)
			{
				// echo "ASd";exit;
				// $reqUser= $this->kauth->getInstance()->getIdentity()->ID;
				
				/*$user_login_log_delete->setField("PEGAWAI_ID", $reqUser);
				$user_login_log_delete->delete();
				
				$user_login_log_status->setField("PEGAWAI_ID", $reqUser);
				$user_login_log_status->setField("TANGGAL_LOGIN", "CURRENT_DATE");
				$user_login_log_status->setField("JAM_LOGIN", date("H:i:s"));
				$user_login_log_status->setField("STATUS", "1");
				$user_login_log_status->insert();*/
				
				redirect('app');
			}
			/*else
			{
				$respon = $this->kauth->ldapAuthenticate($reqUser,$reqPasswd);
				
				if($respon == true)
				{
					$user_login_log_delete->setField("PEGAWAI_ID", $reqUser);
					$user_login_log_delete->delete();
					
					$user_login_log_status->setField("PEGAWAI_ID", $reqUser);
					$user_login_log_status->setField("TANGGAL_LOGIN", "CURRENT_DATE");
					$user_login_log_status->setField("JAM_LOGIN", date("H:i:s"));
					$user_login_log_status->setField("STATUS", "1");
					$user_login_log_status->insert();
					
					redirect('app');
				}
				else
				{
				
					if($ada > 0)
					{
						$user_login_log->setField("PEGAWAI_ID", $reqUser);
						$user_login_log->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
						$user_login_log->updateTotalLogin();
						
						$user_login_log_status->setField("PEGAWAI_ID", $reqUser);
						$user_login_log_status->setField("TANGGAL_LOGIN", "CURRENT_DATE");
						$user_login_log_status->setField("JAM_LOGIN", date("H:i:s"));
						$user_login_log_status->setField("STATUS", "0");
						$user_login_log_status->insert();
					}
					else
					{
						$user_login_log->setField("PEGAWAI_ID", $reqUser);
						$user_login_log->setField("TOTAL_LOGIN", "1");
						$user_login_log->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
						$user_login_log->setField("TANGGAL_LOGIN", "CURRENT_DATE");
						$user_login_log->insert();
						
						$user_login_log_status->setField("PEGAWAI_ID", $reqUser);
						$user_login_log_status->setField("TANGGAL_LOGIN", "CURRENT_DATE");
						$user_login_log_status->setField("JAM_LOGIN", date("H:i:s"));
						$user_login_log_status->setField("STATUS", "0");
						$user_login_log_status->insert();
					}
					
					if($blokir > 0)
					{
						$data['pesan']="Akun Anda Dinonaktifkan selama 5 Menit.";
						$this->load->view('main/login', $data);
					}
					else
					{
						$data['pesan']="Username / password salah.";
						$this->load->view('main/login', $data);
					}
				
				}
			}*/
			
			else
			{
				$data['pesan']="Masukkan username dan password.";
				$this->load->view('main/login', $data);			
			}
			
		}
		else
		{
			$data['pesan']="Masukkan username dan password.";
			$this->load->view('main/login', $data);			
		}
		
	}

	public function loadUrl()
	{
		
		$reqFolder = $this->uri->segment(3, "");
		$reqFilename = $this->uri->segment(4, "");
		$reqParse1 = $this->uri->segment(5, "");
		$reqParse2 = $this->uri->segment(6, "");
		$reqParse3 = $this->uri->segment(7, "");
		$reqParse4 = $this->uri->segment(8, "");
		$reqParse5 = $this->uri->segment(9, "");
		$data = array(
			'reqParse1' => urldecode($reqParse1),
			'reqParse2' => urldecode($reqParse2),
			'reqParse3' => urldecode($reqParse3),
			'reqParse4' => urldecode($reqParse4),
			'reqParse5' => urldecode($reqParse5)
		);
		$this->load->view($reqFolder.'/'.$reqFilename, $data);
	}		
	
	public function logout()
	{
		session_destroy();
		$this->session->unset_userdata('currentUrl');
		$this->kauth->getInstance()->clearIdentity();
		?>
		<script type="text/javascript">
			// window.ReactNativeWebView.postMessage('logout')
		</script>
		<?
		redirect ('login');
	}			

}
