<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class pensiun_json extends CI_Controller {

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
	
	function add()
	{
		$this->load->model('Pensiun');
		
		$set = new Pensiun();
		
		$reqId= $this->input->post("reqId");
		$reqMode= $this->input->post("reqMode");
		$reqRowId= $this->input->post('reqRowId');

		$reqNomorSK= $this->input->post('reqNomorSK');
		$reqTanggalSkKematian= $this->input->post('reqTanggalSkKematian');
		$reqTanggalKematian= $this->input->post('reqTanggalKematian');
		$reqTmt= $this->input->post('reqTmt');
		$reqJenis= $this->input->post('reqJenis');
		$reqKeterangan= $this->input->post('reqKeterangan');

		$reqStatusPegawaiId= $this->input->post('reqStatusPegawaiId');
		$reqStatusPegawaiKedudukanId= $this->input->post('reqStatusPegawaiKedudukanId');
		$reqPensiunPegawaiId= $this->input->post('reqPensiunPegawaiId');

		$set->setField('STATUS_PEGAWAI_ID', $reqStatusPegawaiId);
		$set->setField('STATUS_PEGAWAI_KEDUDUKAN_ID', $reqStatusPegawaiKedudukanId);
		// $set->setField('JENIS', "meninggal");
		$set->setField('JENIS', $reqJenis);
		$set->setField('NOMOR_SK', $reqNomorSK);
		$set->setField('TANGGAL_SK_KEMATIAN', dateToDBCheck($reqTanggalSkKematian));
		$set->setField('TANGGAL_KEMATIAN', dateToDBCheck($reqTanggalKematian));
		$set->setField('TMT', dateToDBCheck($reqTmt));
		$set->setField('KETERANGAN', $reqKeterangan);
		$set->setField('PEGAWAI_ID', $reqId);
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");

		$statussimpan= "";
		if($reqMode == "insert")
		{
			$set_detil= new Pensiun();
			$set_detil->setField('PEGAWAI_ID', $reqId);
			if($set_detil->deletePegawai())
			{
				$reqPensiunPegawaiId= "";
			}

			if($set->insert())
			{
				$statussimpan= 1;
				$reqRowId= $set->id;
			}
		}
		else
		{
			$set->setField('PEGAWAI_STATUS_ID', $reqRowId);
			if($set->update())
			{
				$statussimpan= 1;
			}
		}

		if($statussimpan == 1)
		{
			// if($reqStatusPegawaiKedudukanId == "24" || $reqStatusPegawaiKedudukanId == "25")
			// {
				if($reqPensiunPegawaiId == "")
				{
					if($set->insertPensiun())
					{
					}
				}
				else
				{
					if($set->updatePensiun())
					{
					}
				}
			// }
			// echo $set->query;exit();
			echo $reqRowId."-Data berhasil disimpan.";
		}
		else 
			echo "xxx-Data gagal disimpan.";
		
	}

	function batal() 
	{
		$this->load->model('Pensiun');

		$reqId= $this->input->get("reqId");
		$reqRowId= $this->input->get("reqRowId");
		
		$set= new Pensiun();
		$set->setField("PEGAWAI_ID", $reqId);
		$set->setField("PEGAWAI_STATUS_ID", $reqRowId);
		$set->setField("JENIS", 'meninggal');
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		
		if($set->updateBatal())
			$arrJson["PESAN"] = "Data berhasil dibatalkan.";
		else
			$arrJson["PESAN"] = "Data gagal dibatalkan.";		
		
		echo json_encode($arrJson);

	}

}
?>