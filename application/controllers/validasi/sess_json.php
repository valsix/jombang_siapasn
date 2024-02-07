<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class sess_json extends CI_Controller {

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
	
	function sessdetil()
	{
		$this->load->library('globallinkpegawai');
		$glp= new globallinkpegawai();

		$mode= $this->input->get("m");
		$reqId= $this->input->get("reqId");
		$reqRowId= $this->input->get("reqRowId");
		$reqTahun= $this->input->get("reqTahun");
		$reqJenisJabatanId= $this->input->get("reqJenisJabatanId");

		$arrparam= array("mode"=>$mode, "reqId"=>$reqId, "reqRowId"=>$reqRowId, "reqJenisJabatanId"=>$reqJenisJabatanId, "reqTahun"=>$reqTahun);
		$setsessionriwayat= $glp->inforiwayatdetil($arrparam);
		if(!empty($setsessionriwayat))
		{
			$setsessionriwayat.= "?reqId=".$reqId."&reqRowId=".$reqRowId;
		}
		
		// echo $setsessionriwayat;exit;
		$this->session->unset_userdata('setsessionriwayat');
		$this->session->set_userdata('setsessionriwayat', $setsessionriwayat);
		echo "1";
	}
}
?>