<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/image.func.php");

class klarifikasi_add extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// trow to unauthenticated page!
			//redirect('Login');
		}		
		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");  
		$this->ID = $this->kauth->getInstance()->getIdentity()->ID;   		
		$this->NAMA = $this->kauth->getInstance()->getIdentity()->NAMA;   
		$this->USERNAME = $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->KODE_CABANG = $this->kauth->getInstance()->getIdentity()->KODE_CABANG;
		$this->CABANG = $this->kauth->getInstance()->getIdentity()->CABANG;
		$this->DEPARTEMEN = $this->kauth->getInstance()->getIdentity()->DEPARTEMEN;
		$this->SUB_DEPARTEMEN = $this->kauth->getInstance()->getIdentity()->SUB_DEPARTEMEN;
		$this->JABATAN = $this->kauth->getInstance()->getIdentity()->JABATAN;

		$this->PERSONAL_TOKEN= $this->kauth->getInstance()->getIdentity()->PERSONAL_TOKEN;
	}	

	function dinasluar()
	{

	}

	function masukpulang()
	{
		$this->load->model('main/Klarifikasi');

		$reqId 					= $this->input->post('reqId');
		$reqMode 				= $this->input->post('reqMode');
		$reqJenis 				= $this->input->post('reqJenis');

		$reqTanggalAwal 		= $this->input->post('reqTanggalAwal');
		$reqPegawaiId 			= $this->input->post('reqPegawaiId');
		$reqPegawaiJabatanId 	= $this->input->post('reqPegawaiJabatanId');
		$reqPegawaiPangkatId 	= $this->input->post('reqPegawaiPangkatId');
		$reqJenisKlarifikasi 	= $this->input->post('reqJenisKlarifikasi');
		$reqKeterangan 			= $this->input->post('reqKeterangan');
		$reqUbahStatus 			= $this->input->post('reqUbahStatus');
		$reqStatus 				= $this->input->post('reqStatus');
		$reqAlasanTolak 		= $this->input->post('reqAlasanTolak');

		$set= new Klarifikasi();
        // $set->setField("KLARIFIKASI_ID", $reqId);
        $set->setField("PEGAWAI_ID", $reqPegawaiId);
        $set->setField("JABATAN_TAMBAHAN_ID", ValToNullDB($reqPegawaiJabatanId));
        $set->setField("PANGKAT_RIWAYAT_ID", ValToNullDB($reqPegawaiPangkatId));
        $set->setField("TANGGAL_MULAI", dateToDBCheck($reqTanggalAwal));
        $set->setField("TANGGAL_SELESAI", dateToDBCheck($reqTanggalAwal));
        $set->setField("JENIS_KLARIFIKASI", $reqJenis);
        $set->setField("KETERANGAN", $reqKeterangan);
        $set->setField("UBAH_STATUS", $reqUbahStatus);
        $set->setField("STATUS", $reqStatus);
        $set->setField("ALASAN_TOLAK", $reqAlasanTolak);
        $set->setField("KODE", $reqJenisKlarifikasi);

        $set->setField("TANGGAL_SURAT", "NULL");

        $set->setField("LAST_CREATE_USER", $reqPegawaiId);
        $set->setField("LAST_CREATE_DATE", "CURRENT_TIMESTAMP");
        $set->setField("LAST_USER", $reqPegawaiId);
        $set->setField("LAST_UPDATE", "CURRENT_TIMESTAMP");

		$simpan="";
		if($reqMode == "insert")
        {
            if($set->insert())
            {
            	$reqId= $set->id;
            	$set->setField("KLARIFIKASI_ID", $reqId);
	            if($set->deleteDetil())
	            {
	                if($set->insertDetil())
		            {
		                $simpan = "1";
		            }
	            }
            }

        }

		if($simpan == "1")
			echo $reqId."-Data berhasil disimpan.";
		else
			echo "xxx-Data gagal disimpan.";
	}

}

