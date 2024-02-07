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

		$set->setField("IPCLIENT", sfgetipaddress());
		$set->setField("MACADDRESS", sfgetmac());
		$set->setField("NAMACLIENT", getHostName());

		// print_r($reqMode);exit;
		
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
		$this->load->model('PegawaiFile');
		$set = new PegawaiFile();

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

		$set->setField('PEGAWAI_ID', $reqId);
		$set->setField('PATH', $fileName);

		$check="";
		$set->setField('PEGAWAI_ID', $reqId);
		$set->setField('PATH', $target_file);
		$set->setField("RIWAYAT_TABLE",  ValToNullDB($check));
		$set->setField("RIWAYAT_FIELD",  ValToNullDB($check));
		$set->setField("FILE_KUALITAS_ID", ValToNullDB($check));
		$set->setField("KATEGORI_FILE_ID", ValToNullDB($check));
		$set->setField("RIWAYAT_ID", ValToNullDB($check));

		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");

		$set->setField("IPCLIENT", sfgetipaddress());
		$set->setField("MACADDRESS", sfgetmac());
		$set->setField("NAMACLIENT", getHostName());

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
					// echo "Data berhasil disimpan.";
					if($set->insert())
					{
						$reqRowId= $set->id;
						echo "Data berhasil disimpan.";
					}
					else
					{
						echo "xxx-Data gagal disimpan.";
					}
				} else {
					$set->setField('KETERANGAN', "fileattact");
					if($set->insert())
					{
						$reqRowId= $set->id;
						echo "Data berhasil disimpan.";
					}
					// echo "Sorry, there was an error uploading your file.";
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
			if($set_detil->getField("NO_URUT") == 19 && $set_detil->getField("RIWAYAT_ID") == 19)
        	continue;
        
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
			$set->deletelog();
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

	function combokenaikanpangkat()
	{
		$this->load->model("PegawaiFile");
		
		$reqId= $this->input->get("reqId");
		
		$set = new PegawaiFile();
		// $statement= " AND A.PEGAWAI_ID = ".$reqId." AND A.RIWAYAT_TABLE = 'PENDIDIKAN_RIWAYAT' AND A.KATEGORI_FILE_ID = 14 ";
		$statement= " AND A.PEGAWAI_ID = ".$reqId." AND A.KATEGORI_FILE_ID = 19 ";
		$set->selectByParamsLastRiwayatTable(array(), -1,-1, $statement);
		// echo $set->query;exit();
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("PEGAWAI_FILE_ID");
			$arr_json[$i]['text'] = $set->getField("PATH_NAMA");	
			$i++;
		}
		echo json_encode($arr_json);
	}

	function combokenaikanpangkatsertifikat()
	{
		$this->load->model("PegawaiFile");
		
		$reqId= $this->input->get("reqId");
		$reqMode= $this->input->get("reqMode");
		
		$set = new PegawaiFile();
		$statement= " AND A.PEGAWAI_ID = ".$reqId." AND A.KATEGORI_FILE_ID = 12 AND A.RIWAYAT_ID = ".$reqMode;
		$set->selectByParamsLastRiwayatTable(array(), -1,-1, $statement);
		// echo $set->query;exit();
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("PEGAWAI_FILE_ID");
			$arr_json[$i]['text'] = $set->getField("PATH_NAMA");	
			$i++;
		}
		echo json_encode($arr_json);
	}

}
?>