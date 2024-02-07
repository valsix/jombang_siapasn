<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class pegawai_file_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// trow to unauthenticated page!
			//redirect('Login');
		}       
		
		/* GLOBAL VARIABLE */
		$this->LOGIN_LEVEL= $this->kauth->getInstance()->getIdentity()->LOGIN_LEVEL;
		$this->LOGIN_USER= $this->kauth->getInstance()->getIdentity()->LOGIN_USER;
		$this->LOGIN_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_ID;
		$this->LOGIN_PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID;
	}	
	
	function setting()
	{
		$this->load->model('PegawaiFile');
		$set = new PegawaiFile();
		
		$reqId= $this->input->post("reqId");
		$reqMode= $this->input->post("reqMode");
		$reqRowId= $this->input->post('reqRowId');

		$reqRiwayatTable= $this->input->post("reqRiwayatTable");
		$reqRiwayatField= $this->input->post("reqRiwayatField");
		$reqRiwayatId= $this->input->post("reqRiwayatId");
		$reqUrlFile= $this->input->post("reqUrlFile");
		$reqKualitasFileId= $this->input->post("reqKualitasFileId");
		$reqKategoriFileId= $this->input->post("reqKategoriFileId");
		$reqKeterangan= $this->input->post("reqKeterangan");
		
		$set->setField("PEGAWAI_ID", $reqId);
		$set->setField("RIWAYAT_TABLE", $reqRiwayatTable);
		$set->setField("RIWAYAT_FIELD", $reqRiwayatField);
		$set->setField("FILE_KUALITAS_ID", $reqKualitasFileId);
		$set->setField("KATEGORI_FILE_ID", $reqKategoriFileId);
		$set->setField("RIWAYAT_ID", ValToNullDB($reqRiwayatId));
		$set->setField("PATH", $reqUrlFile);
		$set->setField("KETERANGAN", $reqKeterangan);
		$set->setField("PEGAWAI_FILE_ID", $reqRowId);
		
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		
		if($reqMode == "insert")
		{
			if($set->insert())
			{
				$reqRowId= $set->id;
				echo $reqRowId."-Data berhasil disimpan.";
			}
			else
				echo "xxx-Data gagal disimpan.";
		}
		else
		{
			if($set->update())
				echo $reqRowId."-Data berhasil disimpan.";
			else 
				// echo $set->query;exit;
				echo "xxx-Data gagal disimpan.";
		}
		
	}

	function upload()
	{
		//$this->load->model('PegawaiFile');
		//$set = new PegawaiFile();

		//$reqId= $this->input->post("reqId");
		$reqId= $this->input->get("reqId");
		//$reqId=3;
		//echo $reqId;exit;
		//$target_dir= "uploads/";
		$target_dir= "uploads/".$reqId."/";
		if(file_exists($target_dir)){}
		else
		{
			makedirs($target_dir);
		}
		
		$fileName = basename($_FILES["file"]["name"]);
		$target_file= $target_dir.$fileName;
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

		/*$set->setField('PEGAWAI_ID', $reqId);
		$set->setField('PATH', $fileName);*/

		// Check if file already exists
		// if (file_exists($target_file)) {
		// 	$error = "Sorry, file already exists.";
		// 	$uploadOk = 0;
		// }

		// Check file size
		// if ($_FILES["file"]["size"] > 2000000) {
		// 	$error = "Sorry, your file is too large.";
		// 	$uploadOk = 0;
		// }

		// Allow certain file formats
		// 	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		// 		&& $imageFileType != "gif" ) {
		// 		echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		// 	$uploadOk = 0;
		// }

		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo $uploadOk."-".$error;
		} 
		else 
		{
			if(file_exists($target_file)){}
			else
			{
				if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
					echo "Data berhasil disimpan.";
					/*if($set->insert())
					{
						$reqRowId= $set->id;
						echo "Data berhasil disimpan.";
					}
					else
						echo "xxx-Data gagal disimpan.";*/
				} else {
					echo "Sorry, there was an error uploading your file.";
				}
			}
		}
	}
	
	function jenis_dokumen()
	{
		$this->load->model('PegawaiFile');
		
		$reqId= $this->input->get("reqId");
		$reqKategoriFileId= $this->input->get("reqKategoriFileId");
		$reqCheckImage= $this->input->get("reqCheckImage");
		
		$i=0;
		$statement= " AND A.PEGAWAI_ID = ".$reqId." AND NO_URUT = ".$reqKategoriFileId." AND TIPE_FILE = ".$reqCheckImage;
		$set_detil= new PegawaiFile();
		$set_detil->selectByParamsJenisDokumen(array(), -1,-1, $statement);
		// echo $set_detil->query;exit;
		while($set_detil->nextRow())
		{
			$arrID[$i] = $set_detil->getField("RIWAYAT_TABLE").";".$set_detil->getField("RIWAYAT_ID").";".$set_detil->getField("RIWAYAT_FIELD");
			$arrNama[$i] = $set_detil->getField("INFO_DATA");
			$i += 1;
		}
		unset($set_detil);
		
		$arrFinal = array("arrID" => $arrID, "arrNama" => $arrNama);
		echo json_encode($arrFinal);
	}
	
	function delete()
	{
		
		$this->load->model('PegawaiFile');
		
		$reqId= $this->input->get("reqId");
		$reqPegawaiId= $this->input->get("reqPegawaiId");
		$reqUrl= $this->input->get("reqUrl");

		if($reqId == ""){}
		else
		{
			$set= new PegawaiFile();
			$set->setField("PEGAWAI_FILE_ID", $reqId);
			$set->delete();
			unset($set);
		}

		$target_dir= "uploads/".$reqPegawaiId."/";
		$fn= $target_dir.$reqUrl;
		$needle= $reqUrl;
		$newfn= $target_dir.setfiledeleteinfolder($target_dir, $needle);
		// echo $fn."---".$newfn;exit();
		if(rename($fn,$newfn))
		{
		 echo "Data berhasil dihapus.";
		}
		
	}
}
?>