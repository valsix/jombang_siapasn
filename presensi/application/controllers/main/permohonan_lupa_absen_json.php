<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class permohonan_lupa_absen_json extends CI_Controller {

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
	}	

	function add() 
	{
		$this->load->model('main/PermohonanLupaAbsen');
		$this->load->model('main/JamKerja');
		// $this->load->model('NoSurat');

		$set = new PermohonanLupaAbsen();
		$jamkerja = new JamKerja();
		$set_count_masuk = new PermohonanLupaAbsen();
		$set_count_pulang = new PermohonanLupaAbsen();
		$set_count_bulan = new PermohonanLupaAbsen();

		// $no_surat = new NoSurat();
		// $nomor = new NoSurat();
		
		// $reqPeriode = date('Y');
		// $no_surat->selectByParams(array("PERIODE"=>$reqPeriode, "KODE"=>"LA"), -1, -1 );
		// $nomor->selectByParamsNomor(array("PERIODE"=>$reqPeriode, "KODE"=>"LA", "CABANG_ID" => $this->KODE_CABANG));
		// $no_surat->firstRow();
		// $nomor->firstRow();
		
		// $reqKode = $no_surat->getField("KODE");
		// $reqAwalan = $no_surat->getField("AWALAN");
		// $reqNo = $nomor->getField("NOMOR");
		$reqNomor = "".$reqKode."/".$reqAwalan.$this->KODE_CABANG."/".$reqPeriode."/".$reqNo;
		
		$reqId = $this->input->post('reqId');
		$reqMode = $this->input->post('reqMode');
		
		//$reqNomor = $this->input->post('reqNomor');
		$reqTanggal = $this->input->post('reqTanggal');
		$reqJenisLupaAbsen = $this->input->post('reqJenisLupaAbsen');
		// echo $reqJenisLupaAbsen;exit();
		$reqJenisLupaAbsenMasuk = $this->input->post('reqJenisLupaAbsenMasuk');
		$reqJenisLupaAbsenPulang = $this->input->post('reqJenisLupaAbsenPulang');
		$reqTanggalIjin = $this->input->post('reqTanggalIjin');
		$reqTanggalIjinJam = $this->input->post('reqTanggalIjinJam');
		$reqKeterangan = $this->input->post('reqKeterangan');
		$reqPegawaiId = $this->input->post('reqPegawaiId');
		$reqPegawaiIdApproval1 = $this->input->post('reqPegawaiIdApproval1');
		$reqPegawaiIdApproval2 = $this->input->post('reqPegawaiIdApproval2');
		$reqStatusMasuk = $this->input->post('reqStatusMasuk');
		$reqStatusPulang = $this->input->post('reqStatusPulang');
		
		if($reqJenisLupaAbsen == "izin_masuk_terlambat")
		{

		}
		else
		{
			if($reqTanggalIjinJam == "")
			{
				// set waktu
				$namahari= mktime(0, 0, 0, getMonth($reqTanggalIjin), getYear($reqTanggalIjin), getDay($reqTanggalIjin));
				$namahari= strtoupper(date("l", $namahari));

				$statementjam= " AND A.JAM_KERJA_JENIS_ID = 1 AND A.STATUS = '1'";

				if($namahari == "FRIDAY")
					$statementjam.= " AND A.JAM_KERJA_ID IN (99)";
				else
					$statementjam.= " AND A.JAM_KERJA_ID NOT IN (99)";

				$jamkerja= new JamKerja();
				$jamkerja->selectByParams(array(), -1,-1, $statementjam);
				$jamkerja->firstRow();
				$jamawal= $jamkerja->getField("TERLAMBAT_AWAL");
				$jamakhir= $jamkerja->getField("TERLAMBAT_AKHIR");
				// echo $jamkerja->query;exit;

				$tambahanwaktu= "";
				if($reqStatusMasuk == "1") $tambahanwaktu= " ".$jamawal;
				if($reqStatusPulang == "1") $tambahanwaktu= " ".$jamakhir;

				$reqTanggalIjin= $reqTanggalIjin.$tambahanwaktu;
			}
			else
			{
				$reqTanggalIjin= $reqTanggalIjin." ".$reqTanggalIjinJam;
			}
		}

		$reqAlasan = $this->input->post('reqAlasan');
		$reqAlasanTolak = $this->input->post('reqAlasanTolak');
		
		$set->setField("PERMOHONAN_LUPA_ABSEN_ID", $reqId);
		$set->setField("PEGAWAI_ID", $reqPegawaiId);
		$set->setField("TAHUN", date('Y'));
		//$set->setField("NOMOR", $reqNomor);
		$set->setField("CABANG_ID", $this->KODE_CABANG);
		$set->setField("TANGGAL", dateToDBCheck($reqTanggal));
		$set->setField("JABATAN", $this->JABATAN);
		$set->setField("CABANG", $this->CABANG);
		$set->setField("DEPARTEMEN", $this->DEPARTEMEN);
		$set->setField("SUB_DEPARTEMEN", $this->SUB_DEPARTEMEN);
		$set->setField("JENIS_LUPA_ABSEN", $reqJenisLupaAbsen);
		$set->setField("TANGGAL_IJIN", dateTimeToDBCheck($reqTanggalIjin));
		$set->setField("KETERANGAN", $reqKeterangan);
		$set->setField("STATUS_MASUK", valToNull($reqStatusMasuk));
		$set->setField("STATUS_PULANG", valToNull($reqStatusPulang));

		$set->setField("PEGAWAI_ID_APPROVAL1", $reqPegawaiIdApproval1);
		$set->setField("PEGAWAI_ID_APPROVAL2", $reqPegawaiIdApproval2);

		$set->setField("APPROVAL1", $reqAlasan);
		$set->setField("ALASAN_TOLAK1", $reqAlasanTolak);
		
		/* JIKA PEGAWAI APPROVAL1 TIDAK DIISI PASTI JABATANNYA BUKAN STAFF BIASA :) JADI LANGSUNG BYPASS 'Y' */
		// if($reqPegawaiIdApproval1 == "")// 	$set->setField("APPROVAL1", "Y");
		
		/*if(valToNull($reqStatusMasuk) == '1')
		{
			$statement_masuk = " AND A.TANGGAL_IJIN = ".dateToDBCheck($reqTanggalIjin)." AND STATUS_MASUK = '1' AND (APPROVAL1 != 'T' OR APPROVAL2 != 'T')";
			$jumlah_masuk = $set_count_masuk->getCountByParams(array("A.PEGAWAI_ID" => $reqPegawaiId), $statement_masuk);
			
			if($jumlah_masuk > 0)
			{
				echo "xxx-Anda sudah megajukan permohonan lupa absen masuk pada tanggal ".$reqTanggalIjin."";
				return;
			}
		}
		
		if(valToNull($reqStatusPulang) == '1')
		{
			$statement_pulang = " AND A.TANGGAL_IJIN = ".dateToDBCheck($reqTanggalIjin)." AND STATUS_PULANG = '1' AND (APPROVAL1 != 'T' OR APPROVAL2 != 'T')";
			$jumlah_pulang = $set_count_pulang->getCountByParams(array("A.PEGAWAI_ID" => $reqPegawaiId), $statement_pulang);
			
			if($jumlah_pulang > 0)
			{
				echo "xxx-Anda sudah megajukan permohonan lupa absen pulang pada tanggal ".$reqTanggalIjin."";
				return;
			}
		}
		
		$jumlah_total_bulan = $set_count_bulan->getTotalBulanan($reqPegawaiId, dateToDBCheck($reqTanggalIjin));
		
		if($jumlah_total_bulan >= 3)
		{
			echo "xxx-Anda sudah megajukan permohonan lupa absen 3 kali";
			return;
		}*/
		
		if($reqMode == "insert")
		{
			$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");
			$set->setField("LAST_CREATE_USER", $this->USERNAME);
			if($set->insert())
			{
				$reqId= $set->id;
				$simpan=1;
			}
		}
		else
		{
			$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
			$set->setField("LAST_UPDATE_USER", $this->USERNAME);
			if($set->update())
				$simpan=1;
		}

		if($simpan == "1")
			echo $reqId."-Data berhasil disimpan.";
		else
			echo "xxx-Data gagal disimpan.";
	}

}

