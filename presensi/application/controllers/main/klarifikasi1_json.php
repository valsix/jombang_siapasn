<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/image.func.php");

class klarifikasi1_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// trow to unauthenticated page!
			//redirect('Login');
		}		
		// $this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->ID = $this->kauth->getInstance()->getIdentity()->ID;   		
		$this->NAMA = $this->kauth->getInstance()->getIdentity()->NAMA;   
		$this->USERNAME = $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->KODE_CABANG = $this->kauth->getInstance()->getIdentity()->KODE_CABANG;
		$this->CABANG = $this->kauth->getInstance()->getIdentity()->CABANG;
		$this->DEPARTEMEN = $this->kauth->getInstance()->getIdentity()->DEPARTEMEN;
		$this->SUB_DEPARTEMEN = $this->kauth->getInstance()->getIdentity()->SUB_DEPARTEMEN;
		$this->JABATAN = $this->kauth->getInstance()->getIdentity()->JABATAN;

		$this->PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->PEGAWAI_ID;

		$this->PERSONAL_TOKEN= $this->kauth->getInstance()->getIdentity()->PERSONAL_TOKEN;
	}	

	function dinasluaradd()
	{
		$this->load->library("FileHandler");
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/PermohonanFile');

		$reqId= $this->input->post('reqId');
		$reqJenis= "klarifikasi_dinas_luar";

		$filedata= $_FILES["reqLinkFile"];
		$file= new FileHandler();

		$checkFile= $file->checkfile($filedata, 6);
		$namaLinkFile= $file->setlinkfile($filedata);

		$setdetil= new Klarifikasi();
		$setdetil->selectByParamsValidasiFile(array(), -1,-1, " AND A.INFO_TABLE = '".$reqJenis."'");
		$setdetil->firstRow();
		$infovalidasifile= $setdetil->getField("STATUS");
		unset($setdetil);

		$jumlahfiledata= 0;
		if(!empty($reqId))
		{
			$statement= " AND A.PERMOHONAN_TABLE_NAMA = '".$reqJenis."' AND A.PERMOHONAN_TABLE_ID = ".$reqId;
			$setfile= new PermohonanFile();
			$jumlahfiledata= $setfile->getCountByParams(array(), $statement);
			unset($setfile);
		}
		
		$jumlahdata= 0;
		if(empty($namaLinkFile))
		{
			if($infovalidasifile == "1")
			{
				if($jumlahfiledata > 0){}
				else
				{
					echo "xxx-Anda belum upload file lampiran.";
					exit();
				}
			}
		}
		else
		{
			if(!empty($checkFile))
			{
				echo "xxx-File harus berformat (pdf/jpg/jpeg)";
				exit();
			}

			$sizeLinkFile= $file->checkmodifsizefile($filedata);
			if(!empty($sizeLinkFile))
			{
				echo "xxx-".$sizeLinkFile;
				exit();
			}

			$jumlahdata= count($filedata);
		}

		$reqMode= $this->input->post('reqMode');

		$reqTanggalAwal= $this->input->post('reqTanggalAwal');
		$reqTanggalAkhir= $this->input->post('reqTanggalAkhir');
		$reqNoSurat= $this->input->post('reqNoSurat');
		$reqTanggalSurat= $this->input->post('reqTanggalSurat');
		$reqKeterangan= $this->input->post('reqKeterangan');
		$reqUbahStatus= $this->input->post('reqUbahStatus');
		$reqStatus= $this->input->post('reqStatus');
		$reqAlasanTolak= $this->input->post('reqAlasanTolak');

		$reqPegawaiId= $this->input->post('reqPegawaiId');
		$reqPegawaiJabatanId= $this->input->post('reqPegawaiJabatanId');
		$reqPegawaiPangkatId= $this->input->post('reqPegawaiPangkatId');
		$reqJenisKlarifikasi= "DL";

		$set= new Klarifikasi();
        $set->setField("KLARIFIKASI_ID", $reqId);
        $set->setField("TANGGAL_SURAT", dateToDBCheck($reqTanggalSurat));
        $set->setField("TANGGAL_MULAI", dateToDBCheck($reqTanggalAwal));
        $set->setField("TANGGAL_SELESAI", dateToDBCheck($reqTanggalAkhir));
        $set->setField("JENIS_KLARIFIKASI", $reqJenis);
        $set->setField("KETERANGAN", $reqKeterangan);
        $set->setField("UBAH_STATUS", $reqUbahStatus);
        $set->setField("STATUS", $reqStatus);
        $set->setField("ALASAN_TOLAK", $reqAlasanTolak);
        $set->setField("KODE", $reqJenisKlarifikasi);
        $set->setField("NOMOR_SURAT", $reqNoSurat);
        $set->setField("LAST_CREATE_USER", $this->USERNAME);
        $set->setField("LAST_CREATE_DATE", "CURRENT_TIMESTAMP");
        $set->setField("LAST_USER", $this->USERNAME);
        $set->setField("LAST_UPDATE", "CURRENT_TIMESTAMP");
        $set->setField("LAST_UPDATE_USER", $this->USERNAME);
        $set->setField("LAST_UPDATE_DATE", "CURRENT_TIMESTAMP");

		$simpan="";
		if($reqMode == "insert")
        {
            if($set->insert())
            {
            	$reqId= $set->id;
            	$simpan = "1";
            }
        }
        else
        {
        	if($set->update())
            {
            	$simpan = "1";
            }
        }

        if($simpan == "1")
        {
        	$set->setField("KLARIFIKASI_ID", $reqId);
        	if($set->deleteDetil())
            {
	        	for($i=0; $i < count($reqPegawaiId); $i++)
	        	{
	        		$set->setField("PEGAWAI_ID", $reqPegawaiId[$i]);
	        		$set->setField("JABATAN_TAMBAHAN_ID", ValToNullDB($reqPegawaiJabatanId[$i]));
	        		$set->setField("PANGKAT_RIWAYAT_ID", ValToNullDB($reqPegawaiPangkatId[$i]));
	        		if($set->insertDetil()){}
	        	}
            }
        }

        // simpan file sesuai format
        $folderfilesimpan= "uploads/".gettanggalperiode($reqTanggalAwal);
        if(file_exists($folderfilesimpan)){}
		else
		{
			makedirs($folderfilesimpan);
		}

		$penamaanfile= $reqId."_".str_replace("-", "", $reqTanggalAwal)."_dinasluar_";

		for($i=0; $i < $jumlahdata; $i++)
		{
			$namafile= $filedata["name"][$i];
			$fileType= $filedata["type"][$i];
			$datafileupload= $filedata["tmp_name"][$i];
			$filepath= $file->getExtension($namafile);

			if($namafile == ""){}
			else
			{
				$linkfile= $penamaanfile.md5($penamaanfile.$namafile).".".strtolower($filepath);
				$targetsimpan= $folderfilesimpan."/".$linkfile;

				if (move_uploaded_file($datafileupload, $targetsimpan))
				{
					$setfile= new PermohonanFile();
					$setfile->setField("PEGAWAI_ID", "dinasluar");
					$setfile->setField("PERMOHONAN_TABLE_NAMA", $reqJenis);
					$setfile->setField("PERMOHONAN_TABLE_ID", $reqId);
					$setfile->setField("NAMA", $linkfile);
					$setfile->setField("KETERANGAN", $namafile);
					$setfile->setField("LINK_FILE", $targetsimpan);
					$setfile->setField("TIPE", strtolower($fileType));
					$setfile->setField("LAST_USER", $this->USERNAME);
					$setfile->setField("USER_LOGIN_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->setField("USER_LOGIN_CREATE_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->insert();
					unset($setfile);
				}
			}
		}
        // exit;

		if($simpan == "1")
			echo $reqId."-Data berhasil disimpan.";
		else
			echo "xxx-Data gagal disimpan.";
	}

	function hapusfile()
	{
		$this->load->model('main/PermohonanFile');
		$reqId= $this->input->get('reqId');

		$setfile= new PermohonanFile();
		$statement= " AND A.PERMOHONAN_FILE_ID = ".$reqId;
		$setfile->selectByParams(array(), -1,-1, $statement);
		// echo $setfile->query;exit;
		$setfile->firstRow();
		$linkfile= $setfile->getField("LINK_FILE");
		// echo $linkfile;

		$setfile->setField("PERMOHONAN_FILE_ID", $reqId);
		if($setfile->delete())
		{
			unlink($linkfile);
		}
		echo "1";
	}

}