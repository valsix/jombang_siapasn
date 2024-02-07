<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class setting_klarifikasi_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// trow to unauthenticated page!
			redirect('login');
		}		
		
		// $this->db->query("SET DATESTYLE TO PostgreSQL,European;");  
		$this->USER_GROUP= $this->kauth->getInstance()->getIdentity()->USER_GROUP;
		$this->LOGIN_USER= $this->kauth->getInstance()->getIdentity()->LOGIN_USER;
		$this->LOGIN_LEVEL= $this->kauth->getInstance()->getIdentity()->LOGIN_LEVEL;
		$this->SATUAN_KERJA_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID;
		$this->SATUAN_KERJA_TIPE= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_TIPE;
		$this->SATUAN_KERJA_BKD_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_BKD_ID;
		$this->LOGIN_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_ID;
		$this->LOGIN_PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID;
		$this->STATUS_SATUAN_KERJA_BKD= $this->kauth->getInstance()->getIdentity()->STATUS_SATUAN_KERJA_BKD;
	}	

	function add() 
	{
		$this->load->model('main/SettingKlarifikasi');

		$reqMode= $this->input->post('reqMode');
		$reqId= $this->input->post('reqId');
		$reqMasaBerlakuAwal= $this->input->post('reqMasaBerlakuAwal');
		$reqMasaBerlakuAkhir= $this->input->post('reqMasaBerlakuAkhir');

		$set = new SettingKlarifikasi();
		$set->setField("SETTING_KLARIFIKASI_ID", $reqId);
		$set->setField("MASA_BERLAKU_AWAL", dateToDBCheck($reqMasaBerlakuAwal));
		$set->setField("MASA_BERLAKU_AKHIR", dateToDBCheck($reqMasaBerlakuAkhir));
		
		if($reqMode == "insert")
		{
			$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");
			$set->setField("LAST_CREATE_USER", $this->LOGIN_USER);
			if($set->insert())
			{
				$reqId= $set->id;
				$simpan=1;
			}
		}
		else
		{
			$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
			$set->setField("LAST_UPDATE_USER", $this->LOGIN_USER);
			if($set->update())
				$simpan=1;
		}

		if($simpan == "1")
			echo $reqId."-Data berhasil disimpan.";
		else
			echo "xxx-Data gagal disimpan.";
	}

}

