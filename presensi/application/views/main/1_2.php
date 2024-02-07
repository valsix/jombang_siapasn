<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/image.func.php");

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

		$this->PERSONAL_TOKEN= $this->kauth->getInstance()->getIdentity()->PERSONAL_TOKEN;
	}	

	function add() 
	{
		$sessPersonalToken= $this->PERSONAL_TOKEN;
		// echo $sessPersonalToken;exit();

		$this->load->library("FileHandlerAbsensi");
		$this->load->model('base-curldev/Pegawai');
		$this->load->model('base-curldev/Absensi');

		$set= new Pegawai();
		$set->selectByParamsInfo($sessPersonalToken);
		$set->firstRow();
		$reqLastCreateUser= $set->getField("NIP_BARU");
		$reqLastCreatePegawaiIdUser= $set->getField("PEGAWAI_ID");

		$seturl= new Absensi;
		$seturlinfo= $seturl->geturllinkinfo();

		$reqId = $this->input->post('reqId');
		$reqJenis = $this->input->post('reqJenis');
		$reqTanggal = $this->input->post('reqTanggal');
		$reqKeperluan = "";//$this->input->post('reqKeperluan');
		$reqKeterangan = $this->input->post('reqKeterangan');
		$reqTanggalAwal = $this->input->post('reqTanggalAwal');
		$reqTanggalAkhir = $this->input->post('reqTanggalAkhir');
		$reqLokasi = $this->input->post('reqLokasi');
		// $reqLastCreateUser = $this->input->post('reqLastCreateUser');

		$reqFile= $_FILES["reqLampiran"];
		$filedata= $reqFile;
		$file= new FileHandlerAbsensi();

		if(empty($reqId))
		{
			$checkFile= $file->checkfile($reqFile, 6);
			$namaLinkFile= $file->setlinkfile($reqFile);
			// echo $namaLinkFile;exit();

			if(empty($namaLinkFile))
			{
				echo "xxx-Anda belum upload file lampiran.";
				exit();
			}

			if(!empty($checkFile))
			{
				echo "xxx-File harus berformat (pdf/jpg/jpeg)";
				exit();
			}
			// exit();
		}

		$data= array(
			'reqId'=>$reqId,
			'reqMode'=>"insert",
			'reqJenis'=>$reqJenis,
			'reqTanggal'=>$reqTanggal,
			'reqKeperluan'=>$reqKeperluan,
			'reqKeterangan'=>$reqKeterangan,
			'reqTanggalAwal'=>$reqTanggalAwal,
			'reqTanggalAkhir'=>$reqTanggalAkhir,
			'reqLokasi'=>$reqLokasi,
			'reqLastCreateUser'=>$reqLastCreateUser,
			'namaLinkFile'=>$namaLinkFile,
			'reqToken'=>$sessPersonalToken
		);

		$set= new Absensi();
		$response= $set->insertpermohonandinas($data);
		// print_r($response);exit();
		$returnStatus= $response->status;
		$returnId= $response->id;

		$simpan="";
		if($returnStatus == "success")
		{
			$reqId= $returnId;
			$simpan=1;
			// echo $returnId;
			$folderupload="permohonandinas";
			$target_dir= $seturlinfo.$folderupload."/".$reqId."/";
			if(file_exists($target_dir)){}
			else
			{
				makedirs($target_dir);
			}

			// buat file upload
			$reqTableNama= "permohonan_lambat_pc";
			$target_dir= $seturlinfo.$folderupload."/".$reqId."/";
			$info_dir= "uploads/".$folderupload."/".$reqId."/";
			$multi= true;
			$jumlahdata= count($filedata);
			if($multi == false)
				$jumlahdata= 1;

			// print_r($filedata);exit();

			$value= "";
			for($i=0; $i < $jumlahdata; $i++)
			{
				if($multi == false)
				{
					$namafile= $filedata["name"];
					$fileType= $filedata["type"];
					$datafileupload= $filedata["tmp_name"];
				}
				else
				{
					$namafile= $filedata["name"][$i];
					$fileType= $filedata["type"][$i];
					$datafileupload= $filedata["tmp_name"][$i];
				}

				$filepath= $file->getExtension($namafile);
				// echo $filepath."<br/>";

				if($namafile == ""){}
				else
				{
					$reqNama= md5($namafile).".".strtolower($filepath);
					$reqKeterangan= $namafile;
					$reqFileLink= $info_dir.md5($namafile).".".strtolower($filepath);
					$reqFileType= $filepath;

					$datafile= array(
						'reqTableNama'=>$reqTableNama,
						'reqTableId'=>$reqId,
						'reqNama'=>$reqNama,
						'reqKeterangan'=>$reqKeterangan,
						'reqFileLink'=>$reqFileLink,
						'reqFileType'=>$reqFileType,
						'reqToken'=>$sessPersonalToken
					);

					$set= new Absensi();
					$response= $set->insertpermohonanfile($datafile);
					// print_r($response);exit();
					$returnStatus= $response->status;
					$returnId= $response->id;

					$simpan="";
					if($returnStatus == "success")
					{
						// echo $reqId."\n";
						$simpan=1;
						$target_dir= $seturlinfo.$folderupload."/".$reqId."/".$reqNama;
						// echo $target_dir."\n";

						if($reqFileType == "png" || $reqFileType == "jpeg" || $reqFileType == "jpg")
						{
							if(createThumbnail($datafileupload, $target_dir, 800))
							{
							}
						}
						else
						{
							if (move_uploaded_file($datafileupload, $target_dir))
							{
							}
						}
					}

				}
			}
			// buat file upload

		}

		if($simpan == "1")
			echo $reqId."-Data berhasil disimpan.";
		else
			echo "xxx-Data gagal disimpan.";
	}

}

