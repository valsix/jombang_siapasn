<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class permohonan_dinas_json extends CI_Controller {

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
		$this->load->model('main/PermohonanLambatPc');
		$this->load->model('main/JamKerja');
		// $this->load->model('NoSurat');
		$this->load->library("FileHandler");

		$reqId = $this->input->post('reqId');

		$set = new PermohonanLambatPc();
		$jamkerja = new JamKerja();
		$set_count_masuk = new PermohonanLambatPc();
		$set_count_pulang = new PermohonanLambatPc();
		$set_count_bulan = new PermohonanLambatPc();

		$reqFile= $_FILES["reqLampiran"];
		$file= new FileHandler();

		/*$reqId= 100;
		$target_dir= "../jombang-web-server/uploads/permohonandinas/".$reqId."/";
		if(file_exists($target_dir)){}
		else
		{
			makedirs($target_dir);
		}

		$file->setuploadlinkfile($reqFile, $target_dir);
		exit();*/

		if(empty($reqId))
		{
			$checkFile= $file->checkfile($reqFile, 6);
			$namaLinkFile= $file->setlinkfile($reqFile);
			// echo $namaLinkFile;exit();

			/*if(empty($namaLinkFile))
			{
				echo "xxx-Anda belum upload file lampiran.";
				exit();
			}*/

			if(!empty($checkFile))
			{
				echo "xxx-File harus berformat (pdf/jpg/jpeg)";
				exit();
			}
			// exit();
		}

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

		$reqMode = $this->input->post('reqMode');
		
		//$reqNomor = $this->input->post('reqNomor');
		$reqJenis = $this->input->post('reqJenis');
		$reqTanggal = $this->input->post('reqTanggal');
		$reqTanggalIjin = $this->input->post('reqTanggalIjin');
		$reqJamDatang = $this->input->post('reqJamDatang');
		$reqJamPulang = $this->input->post('reqJamPulang');
		$reqKeperluan = $this->input->post('reqKeperluan');
		$reqKeterangan = $this->input->post('reqKeterangan');
		$reqPegawaiId = $this->input->post('reqPegawaiId');
		$reqPegawaiIdApproval1 = $this->input->post('reqPegawaiIdApproval1');
		$reqPegawaiIdApproval2 = $this->input->post('reqPegawaiIdApproval2');
		
		$reqTanggalAwal = $this->input->post('reqTanggalAwal');
		$reqTanggalAkhir = $this->input->post('reqTanggalAkhir');
		$reqLokasi = $this->input->post('reqLokasi');

		// set waktu
		$namahari= mktime(0, 0, 0, getMonth($reqTanggalAwal), getYear($reqTanggalAwal), getDay($reqTanggalAwal));
		$namahari= strtoupper(date("l", $namahari));

		/*$statementjam= " AND A.JAM_KERJA_JENIS_ID = 1 AND A.STATUS = '1'";

		if($namahari == "FRIDAY")
			$statementjam.= " AND A.JAM_KERJA_ID IN (99)";
		else
			$statementjam.= " AND A.JAM_KERJA_ID NOT IN (99)";

		$jamkerja= new JamKerja();
		$jamkerja->selectByParams(array(), -1,-1, $statementjam);
		$jamkerja->firstRow();
		$jamawal= $jamkerja->getField("TERLAMBAT_AWAL");

		// ====================================================================================================
		$namahari= mktime(0, 0, 0, getMonth($reqTanggalAkhir), getYear($reqTanggalAkhir), getDay($reqTanggalAkhir));
		$namahari= strtoupper(date("l", $namahari));

		$statementjam= " AND A.JAM_KERJA_JENIS_ID = 1 AND A.STATUS = '1'";

		if($namahari == "FRIDAY")
			$statementjam.= " AND A.JAM_KERJA_ID IN (99)";
		else
			$statementjam.= " AND A.JAM_KERJA_ID NOT IN (99)";

		$jamkerja= new JamKerja();
		$jamkerja->selectByParams(array(), -1,-1, $statementjam);
		$jamkerja->firstRow();
		$jamakhir= $jamkerja->getField("TERLAMBAT_AKHIR");*/
		// echo $jamkerja->query;exit;

		$reqAlasan = $this->input->post('reqAlasan');
		$reqAlasanTolak = $this->input->post('reqAlasanTolak');

		$set->setField("PERMOHONAN_LAMBAT_PC_ID", $reqId);
		$set->setField("PEGAWAI_ID", $reqPegawaiId);
		$set->setField("TAHUN", date('Y'));
		//$set->setField("NOMOR", $reqNomor);
		$set->setField("TANGGAL", dateToDBCheck($reqTanggal));
		$set->setField("JABATAN", $this->JABATAN);
		$set->setField("CABANG_ID", $this->KODE_CABANG);
		$set->setField("CABANG", $this->CABANG);
		$set->setField("DEPARTEMEN", $this->DEPARTEMEN);
		$set->setField("SUB_DEPARTEMEN", $this->SUB_DEPARTEMEN);
		$set->setField("TANGGAL_IJIN", dateToDBCheck($reqTanggalIjin));
		$set->setField("TANGGAL_AWAL", dateToDBCheck($reqTanggalAwal));
		$set->setField("TANGGAL_AKHIR", dateToDBCheck($reqTanggalAkhir));
		$set->setField("JAM_DATANG", $jamawal);
		$set->setField("JAM_PULANG", $jamakhir);
		$set->setField("LOKASI", $reqLokasi);

		$set->setField("LAMPIRAN", $namaLinkFile);
		
		if($reqJenis == "SPPD")
		{}
		else
		{
			$set->setField("KEPERLUAN", $reqKeperluan);
		}
		$set->setField("KETERANGAN", $reqKeterangan);


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

				$target_dir= "uploads/permohonandinas/".$reqId."/";
				if(file_exists($target_dir)){}
				else
				{
					makedirs($target_dir);
				}

				$file->setuploadlinkfile($reqFile, $target_dir);
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

