<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class surat_masuk_pegawai_check_json extends CI_Controller {

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
		$this->SATUAN_KERJA_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID;
		$this->SATUAN_KERJA_BKD_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_BKD_ID;
		$this->LOGIN_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_ID;
		$this->LOGIN_PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID;
	}	
	
	function add()
	{
		$this->load->model('persuratan/SuratMasukPegawaiCheck');
		
		$reqId= $this->input->post("reqId");
		$reqRowId= $this->input->post("reqRowId");
		$reqMode= $this->input->post("reqMode");
		$reqKategori= $this->input->post("reqKategori");

		$reqKategoriFileId= $this->input->post("reqKategoriFileId");
		$reqKeteranganTeknis= $this->input->post("reqKeteranganTeknis");
		$reqSuratMasukPegawaiCheckId= $this->input->post("reqSuratMasukPegawaiCheckId");
		$reqJenisId= $this->input->post("reqJenisId");
		$reqSuratMasukBkdId= $this->input->post("reqSuratMasukBkdId");
		$reqSuratMasukUptId= $this->input->post("reqSuratMasukUptId");
		$reqPegawaiId= $this->input->post("reqPegawaiId");
		$reqNomor= $this->input->post("reqNomor");
		$reqJenisPelayananId= $this->input->post("reqJenisPelayananId");
		$reqTipe= $this->input->post("reqTipe");
		$reqNama= $this->input->post("reqNama");
		$reqInfoChecked= $this->input->post("reqInfoChecked");
		$reqLinkFile= $this->input->post("reqLinkFile");
		$reqStatusInformasi= $this->input->post("reqStatusInformasi");
		$reqInformasiTable= $this->input->post("reqInformasiTable");
		$reqInformasiField= $this->input->post("reqInformasiField");
		
		$tempJumlahSimpan=$tempJumlahSimpaDatan=0;
		for($i=0; $i < count($reqSuratMasukPegawaiCheckId); $i++)
		{
			$set = new SuratMasukPegawaiCheck();
			$set->setField("SURAT_MASUK_PEGAWAI_ID", $reqRowId);
			$set->setField("KATEGORI", $reqKategori);
			$set->setField("JENIS_ID", $reqJenisId[$i]);
			$set->setField("SURAT_MASUK_BKD_ID", $reqSuratMasukBkdId[$i]);
			$set->setField("SURAT_MASUK_UPT_ID", ValToNullDB($reqSuratMasukUptId[$i]));
			$set->setField("PEGAWAI_ID", $reqPegawaiId[$i]);
			$set->setField("NOMOR", $reqNomor[$i]);
			$set->setField("JENIS_PELAYANAN_ID", $reqJenisPelayananId[$i]);
			$set->setField("TIPE", $reqTipe[$i]);
			$set->setField("NAMA", $reqNama[$i]);
			$set->setField("INFO_CHECKED", ValToNullDB($reqInfoChecked[$i]));
			$set->setField("LINK_FILE", $reqLinkFile[$i]);
			$set->setField("STATUS_INFORMASI", ValToNullDB($reqStatusInformasi[$i]));
			$set->setField("INFORMASI_TABLE", $reqInformasiTable[$i]);
			$set->setField("INFORMASI_FIELD", $reqInformasiField[$i]);
			$set->setField("KATEGORI_FILE_ID", ValToNullDB($reqKategoriFileId[$i]));
			$set->setField("SURAT_MASUK_PEGAWAI_CHECK_ID", $reqSuratMasukPegawaiCheckId[$i]);
			
			if($reqSuratMasukPegawaiCheckId[$i] == "")
			{
				if($set->insert())
				{
					if($reqInfoChecked[$i] == "1")
						$tempJumlahSimpan++;
					
					$tempJumlahSimpaDatan++;
				}
			}
			else
			{
				if($set->update())
				{
					if($reqInfoChecked[$i] == "1")
						$tempJumlahSimpan++;
					
					$tempJumlahSimpaDatan++;
				}
				//echo $tempJumlahSimpan;exit;
				//echo $set->query;exit;
			}
			// echo $set->query."\n";
		}
		// exit;
		
		$set = new SuratMasukPegawaiCheck();
		$set->setField("SURAT_MASUK_PEGAWAI_ID", $reqRowId);
		$set->setField("KETERANGAN_TEKNIS", $reqKeteranganTeknis);
		$set->updateKeteranganTeknis();
		//echo $set->query;exit;
		// echo $tempJumlahSimpan." == ".$i;exit;
		if($tempJumlahSimpan == $i)
		{
			$set = new SuratMasukPegawaiCheck();
			$set->setField("SURAT_MASUK_PEGAWAI_ID", $reqRowId);
			$set->setField("STATUS_VERIFIKASI", "1");
			$set->updateStatusVerifikasi();
			echo $reqRowId."-Data berhasil disimpan";
		}
		else 
		{
			if($tempJumlahSimpaDatan == $i)
			{
				echo $reqRowId."-Data berhasil disimpan";
			}
			else
			echo "xxx-Data gagal disimpan.";
		}
		
		
	}
	
	function status_batal_verifikasi()
	{
		$this->load->model('persuratan/SuratMasukPegawaiCheck');
		
		$reqRowId= $this->input->get("reqRowId");
		$reqStatusSuratKeluar= $this->input->get("reqStatusSuratKeluar");
		
		$set = new SuratMasukPegawaiCheck();
		$set->setField("SURAT_MASUK_PEGAWAI_ID", $reqRowId);
		$set->setField("INFO_CHECKED", "NULL");
		//kalau batal
		if($reqStatusSuratKeluar == "2")
		{
			
			$set->setField("STATUS_VERIFIKASI", "NULL");
			//$set->updateBatalStatusSuratKeluar();
		}
		else
		{
			$set->setField("STATUS_VERIFIKASI", $reqStatusSuratKeluar);
		}
		$set->updateBatalValidasiKarpeg();
	}
	
	function status_surat_keluar()
	{
		$this->load->model('persuratan/SuratMasukPegawaiCheck');
		
		$reqRowId= $this->input->get("reqRowId");
		$reqStatusSuratKeluar= $this->input->get("reqStatusSuratKeluar");
		
		$set = new SuratMasukPegawaiCheck();
		$set->setField("SURAT_MASUK_PEGAWAI_ID", $reqRowId);
		//kalau batal
		if($reqStatusSuratKeluar == "2")
		{
			
			$set->setField("STATUS_SURAT_KELUAR", "NULL");
			//$set->updateBatalStatusSuratKeluar();
		}
		else
		{
			$set->setField("STATUS_SURAT_KELUAR", $reqStatusSuratKeluar);
		}
		$set->updateStatusSuratKeluar();
	}
	
	function turun_status()
	{
		$this->load->model('persuratan/SuratMasukPegawaiTurunStatus');
		//exit;
		$reqId= $this->input->post("reqId");
		$reqRowId= $this->input->post("reqRowId");
		$reqMode= $this->input->post("reqMode");
		$reqKeterangan= $this->input->post("reqKeterangan");
		
		$set= new SuratMasukPegawaiTurunStatus();
		$set->setField("SURAT_MASUK_PEGAWAI_ID", $reqRowId);
		$set->setField("KETERANGAN", $reqKeterangan);
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("LAST_DATE", "NOW()");
		if($set->insert())
			echo $reqId."-Data berhasil disimpan";
		else 
			echo "xxx-Data gagal disimpan.";
	}
	
	function kirim_ulang()
	{
		$this->load->model('persuratan/SuratMasukPegawaiTurunStatus');
		$this->load->model('persuratan/SuratMasukBkd');
		$this->load->model('persuratan/SuratMasukPegawai');
		
		$reqId= $this->input->post("reqId");
		$reqRowId= $this->input->post("reqRowId");
		$reqPegawaiId= $this->input->post("reqPegawaiId");
		$reqMode= $this->input->post("reqMode");
		$reqKeterangan= $this->input->post("reqKeterangan");

		$statement= " AND A.SURAT_MASUK_BKD_ID = ".$reqId;
		$set= new SuratMasukBkd();
		$set->selectByParamsSimple(array(), -1, -1, $statement);
		$set->firstRow();
		// echo $set->query;exit;
		$reqJenis= $set->getField("JENIS_ID");

		$infovalid= 0;
		// untuk cek apakah belum valid
		// saat ii hanya karpeg dan karsu
		if($reqJenis == "3" || $reqJenis == "4")
		{
			$statement= " AND A.SURAT_MASUK_BKD_ID = ".$reqId." AND A.PEGAWAI_ID = ".$reqPegawaiId;
			$set= new SuratMasukPegawai();
			$set->selectparam(array(), -1,-1, $statement);
			// echo $set->query;
			$set->firstRow();
			$infostatusefilebkd= $set->getField("STATUS_E_FILE_BKD");
			$infostatusefileupt= $set->getField("STATUS_E_FILE_UPT");

			if(empty($infostatusefilebkd) || empty($infostatusefileupt))
			{
				$infovalid= 1;
			}
		}

		// untuk cek apakah belum valid
		if($infovalid > 0)
		{
			echo "xxx-Data gagal di kirim, karena ada data belum valid simpan e file terlebih dahulu.";
			exit;
		}
		// exit;

		$set= new SuratMasukPegawaiTurunStatus();
		$set->setField("SURAT_MASUK_PEGAWAI_ID", $reqRowId);
		$set->setField("KETERANGAN", $reqKeterangan);
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("LAST_DATE", "NOW()");
		if($set->kirimUlang())
			echo $reqId."-Data berhasil disimpan";
		else 
			echo "xxx-Data gagal disimpan.";
	}
	
	function status_tms()
	{
		$this->load->model('persuratan/SuratMasukPegawaiTurunStatus');
		
		$reqRowId= $this->input->get("reqRowId");
		$reqStatusTms= $this->input->get("reqStatusTms");
		
		$set= new SuratMasukPegawaiTurunStatus();
		$set->setField("SURAT_MASUK_PEGAWAI_ID", $reqRowId);
		$set->setField("STATUS_TMS", ValToNullDB($reqStatusTms));
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("LAST_DATE", "NOW()");
		if($set->statusPegawaiTms())
			echo $reqId."-Data berhasil disimpan";
		else 
			echo "xxx-Data gagal disimpan.";
	}

	function validasi_efile_dinas()
	{
		// validasi untuk file
		$this->load->library('globalfilepegawai');
		$this->load->library("FileHandler");

		$reqDokumenTipe= $this->input->post("reqDokumenTipe");
		$reqDokumenPilih= $this->input->post("reqDokumenPilih");
		// print_r($reqDokumenTipe);exit;

		$file= new FileHandler();
		$reqLinkFile= $_FILES['reqLinkFile'];
		// print_r($reqLinkFile);exit;

		if(!empty($reqLinkFile))
		{
			foreach ($reqDokumenTipe as $key => $value) {

				if($reqDokumenPilih[$key] == "2" || empty($reqDokumenPilih[$key]))
					continue;

				if($value == "1")
				{
					$infokecualifile= "jpg";
					$arrkecualifile= array("jpg", "jpg");
				}
				else
				{
					$infokecualifile= "pdf";
					$arrkecualifile= array("pdf");
				}

				// Allow certain file formats
				$bolehupload = "";
				$fileuploadexe= strtolower(getExtension($reqLinkFile['name'][$key]));

				if(!empty($fileuploadexe))
				{
					$filesize= $reqLinkFile['size'][$key];
					if (($filesize > 2097152))
					{
						echo "xxx-Data gagal disimpan, check file upload harus di bawah 2 MB.";
						exit();
					}

					if($fileuploadexe == "")
					{
						$bolehupload = 1;
					}
					else
					{
						// print_r($arrkecualifile);exit;
						// echo $fileuploadexe;exit;
						if(in_array($fileuploadexe, $arrkecualifile))
							$bolehupload = 1;
					}

					if($bolehupload == "")
					{
						echo "xxx-Data gagal disimpan, check file upload harus format ".$infokecualifile.".";
						exit();
					}
				}
				else
				{
					echo "xxx-Data gagal disimpan, upload file scan baru harus diisi";
					exit();
				}
			}
		}
		// echo "xxx-coba cek file";
		// exit;

		$reqId= $this->input->post("reqId");
		$reqRowId= $this->input->post('reqRowId');

		$vpost= $this->input->post();
		// print_r($vpost);exit;
		$vsimpanfilepegawai= new globalfilepegawai();
		$vsimpanfilepegawai->simpanfilepegawai($vpost, "", $reqLinkFile);

		echo "-Data berhasil simpan";

	}

	function usulanbatal()
	{
		$this->load->model('persuratan/SuratMasukPegawaiTurunStatus');
		//exit;
		$reqId= $this->input->post("reqId");
		$reqRowId= $this->input->post("reqRowId");
		$reqMode= $this->input->post("reqMode");
		$reqJenisTipe= $this->input->post("reqJenisTipe");
		$reqKeterangan= $this->input->post("reqKeterangan");
		
		$set= new SuratMasukPegawaiTurunStatus();
		$set->setField("SURAT_MASUK_PEGAWAI_ID", $reqRowId);
		$set->setField("JENIS", $reqJenisTipe);
		$set->setField("KETERANGAN", setQuote($reqKeterangan));
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("LAST_DATE", "NOW()");
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));

		$simpan= "";
		if($set->insert())
		{
			$simpan= "1";
		}

		if(!empty($simpan))
		{
			$set= new SuratMasukPegawaiTurunStatus();
			$set->setField("SURAT_MASUK_PEGAWAI_ID", $reqRowId);
			$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
			$set->setField("LAST_USER", $this->LOGIN_USER);
			$set->setField("LAST_DATE", "NOW()");
			$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
			$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			$set->setField("STATUS_BERKAS", "99");
			$set->statusberkas();
			
			echo $reqId."-Data berhasil disimpan";
		}
		else 
			echo "xxx-Data gagal disimpan.";
	}

	function wa_kirim()
	{
		$this->load->model('persuratan/SuratMasukPegawaiTurunStatus');
		//exit;
		$reqId= $this->input->post("reqId");
		$reqRowId= $this->input->post("reqRowId");
		$reqMode= $this->input->post("reqMode");
		$reqJenisTipe= $this->input->post("reqJenisTipe");
		$reqKeterangan= $this->input->post("reqKeterangan");
		
		$set= new SuratMasukPegawaiTurunStatus();
		$set->setField("SURAT_MASUK_PEGAWAI_ID", $reqRowId);
		$set->setField("JENIS", $reqJenisTipe);
		$set->setField("KETERANGAN", setQuote($reqKeterangan));
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("LAST_DATE", "NOW()");
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));

		$simpan= "";
		if($set->insertdata())
		{
			$simpan= "1";
		}

		if(!empty($simpan))
		{
			echo $reqId."-Data berhasil disimpan";
		}
		else 
			echo "xxx-Data gagal disimpan.";
	}
	
}
?>