<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller {

	function __construct() {
		parent::__construct();
		//kauth
			
		/* GLOBAL VARIABLE */ 
		$this->db->query("alter session set nls_date_format='DD-MM-YYYY'"); 
	
		
		
	}
	
	public function loadUrl()
	{
		
		$reqFolder = $this->uri->segment(3, "");
		$reqFilename = $this->uri->segment(4, "");
		$reqParse1 = $this->uri->segment(5, "");
		$reqParse2 = $this->uri->segment(6, "");
		$reqParse3 = $this->uri->segment(7, "");
		$reqParse4 = $this->uri->segment(8, "");
		$data = array(
			'reqParse1' => $reqParse1,
			'reqParse2' => $reqParse2,
			'reqParse3' => $reqParse3,
			'reqParse4' => $reqParse4
		);
		$this->load->view($reqFolder.'/'.$reqFilename, $data);
	}	
	
	public function tesEmail()
	{
		
		$this->load->library("KMail");
		$mail = new KMail();
		$mail->Subject  =  'Tes Email - Helpdesk PT Pelindo Marine Service';
		$mail->AddAddress("novanbagus@gmail.com" , "Tes Email");
		
		$message = "Test Email";
		
		$mail->MsgHTML($message);
		
		if(!$mail->Send())
		{
			echo "Mailer Error: " . $mail->ErrorInfo;
		}
		else
		{
			echo 'Message has been sent.';
		}
	}
		
}

