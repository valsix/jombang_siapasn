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

		$reqUser = urlencode($this->input->post("reqUser"));
		$reqPasswd = urlencode($this->input->post("reqPasswd"));
		$reqMode = $_POST["reqMode"];

		// onecheck
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$data= array(
                'reqUser'=>$reqUser,
                'reqPasswd'=>$reqPasswd,
            );
            $data= $this->security->xss_clean($data);
            if($this->security->xss_clean($data))
            {
            	$reqUser= $data["reqUser"];
            	$reqPasswd= $data["reqPasswd"];
            	// echo $reqUser;exit;

				if(!empty($reqUser) AND !empty($reqPasswd))
				{
					$respon = $this->kauth->localAuthenticate($reqUser,$reqPasswd);
					
					if($respon == true)
					{
						redirect('app');
					}
					else
					{
						$data['pesan']="Masukkan username dan password.";
						$this->load->view('main/login', $data);			
					}
				}
			}
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
