<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class aksi_menu_json extends CI_Controller {

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
		$this->LOGIN_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_ID;
		$this->LOGIN_PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID;
	}

	function add()
	{
		$this->load->model('AksiMenu');
		
		$reqId= $this->input->post("reqId");
		$reqMode= $this->input->post("reqMode");
		$reqMenuId= $this->input->post("reqMenuId");
		$reqMenuIconId= $this->input->post("reqMenuIconId");
		$reqCheck= $this->input->post("reqCheck");

		$set= new AksiMenu();
		foreach ($reqMenuId as $key => $value) 
		{
			$vstatus= $reqCheck[$key];
			if(empty($vstatus)) $vstatus= "A";

			$set->setField("STATUS_".$value, $vstatus);
			$set->setField("ICON_".$value, $reqMenuIconId[$key]);
		}
		$set->setField("MENU_AKSI_ID", $reqId);

		if($reqMode == "insert")
		{
			$set->insert();
		}
		else
		{
			$set->update();
		}

	  	echo $reqId."yyyData berhasil disimpan.";
	}
}