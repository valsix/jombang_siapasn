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
		$reqPrioritas= $this->input->post("reqPrioritas");
		$reqNameFileEdit= $this->input->post("reqNameFileEdit");

		if(empty($reqUrlFile))
		{
			// kalau kosong return data
			echo "xxx-Data gagal disimpan.";exit;
		}
		
		$set->setField("PEGAWAI_ID", $reqId);
		$set->setField("RIWAYAT_TABLE", $reqRiwayatTable);
		$set->setField("RIWAYAT_FIELD", $reqRiwayatField);
		$set->setField("FILE_KUALITAS_ID", ValToNullDB($reqKualitasFileId));
		$set->setField("KATEGORI_FILE_ID", ValToNullDB($reqKategoriFileId));
		$set->setField("RIWAYAT_ID", ValToNullDB($reqRiwayatId));
		$set->setField("PATH", $reqUrlFile);
		$set->setField("PATH_ASLI", $reqNameFileEdit);
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
		$set->setField("PRIORITAS", $reqPrioritas);

		if($reqMode == "insert")
		{
			// untuk rename
			$namagenerate= generateRandomString();
			$infoext= getExtension($reqUrlFile);
			$namagenerate= "uploads/".$reqId."/".str_replace("xxx", "b1a", $namagenerate).".".$infoext;

			$reqUrlFile= str_replace("''", "'", $reqUrlFile);

			/*if($reqId == 11655)
			{
				// KGB'02_19650717 198903 2 010.pdf
				// KGB'02_19650717 198903 2 010.pdf

				// if(file_exists($reqUrlFile))
				// {
				// 	rename($reqUrlFile, $namagenerate);
				// }
				echo $reqUrlFile."\n";
				echo $reqNameFileEdit."\n";
				echo $namagenerate;exit;
			}*/
			
			$set->setField("PATH", $namagenerate);
			$set->setField('EXT', $infoext);

			if($set->insert())
			{
				// ubah mode file
				if(file_exists($reqUrlFile))
				{
					// exec ("find /var/www/siapasn/uploads/".$reqId."/ -type d -exec chmod 0777 {} +");//for sub directory
					// exec ("find /var/www/siapasn/uploads/".$reqId."/ -type f -exec chmod 0777 {} +");//for files inside directory

					if($reqId == 11655)
					{
						/*$requrlfilenew= "/var/www/siapasn/".$reqUrlFile;
						// exec("chmod -R 0777 /var/www/siapasn/uploads/".$reqId."/");
						// exec("chmod -R 0755 ".$requrlfilenew);
						$namageneratenew= "/var/www/siapasn/".$namagenerate;
						echo $requrlfilenew."\n";
						echo $namageneratenew."\n";
						chmod("uploads/", 0777);
						chmod("uploads/".$reqId, 0777);
						chmod($reqUrlFile, 0777);
						// echo $reqUrlFile."\n";
						// echo $namagenerate;
						$fileRenameSuccess = rename($requrlfilenew, $namageneratenew);
						// $fileRenameSuccess = rename($reqUrlFile, $namagenerate);
						echo ($fileRenameSuccess ? "y":"n");
						exit;*/
					}
				}
				rename($reqUrlFile, $namagenerate);

				if($reqId == 11655)
				{
					// echo $reqUrlFile."\n";
					// echo $namagenerate;exit;
				}

				if($reqId == 11655)
				{
					// echo "xx";exit;
				}

				$reqRowId= $set->id;

				echo $reqRowId."-Data berhasil disimpan.-".urlencode($namagenerate);
			}
			else
				echo "xxx-Data gagal disimpan.";
		}
		else
		{

			if(!empty($reqPrioritas))
			{
				if($set->update())
				{
					if($set->updateprioritas())
					{
						echo $reqRowId."-Data berhasil disimpan.";
					}
					else
					{
						echo "xxx-Data gagal disimpan.";
					}

				}
				else
				{ 
					echo "xxx-Data gagal disimpan.";
				}
				
			}
			else
			{
				if($set->update())
				{
					echo $reqRowId."-Data berhasil disimpan.";
				}
				else
				{ 
					echo "xxx-Data gagal disimpan.";
				}
			}
			
			
		}
		
	}

	function cek()
	{
		echo sfgetipaddress();exit;
		echo sfgetmac();exit;
		echo getHostName();exit;
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
		$fileNameInfo = substr($fileName, 0, strpos($fileName, "."));
		$file_name = preg_replace( '/[^a-zA-Z0-9_]+/', '_', $fileNameInfo);

		$infoext = pathinfo($_FILES['file']['name']);
		$ext = $infoext['extension'];

		$target_file_asli= $file_name;
		$uploadOk = 1;
		$namagenerate =generateRandomString().".".$ext;
		$target_file_generate= $target_dir.$namagenerate; 
		// print_r($ext);exit;

		if(empty($target_file_generate))
		{
			// kalau kosong return data
			echo "";exit;
		}

		// kalau ada nama file yg sama
		$setcheck= new PegawaiFile();
		$setcheck->selectByParamsFile(array(), -1, -1, " AND COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL AND A.PATH_ASLI = '".$file_name."'", $reqId);
		$setcheck->firstRow();
		$vcheck= $setcheck->getField("PEGAWAI_FILE_ID");
		// echo $setcheck->query;exit;
		if(!empty($vcheck))
		{
			echo "";exit;
		}

		$set->setField('PEGAWAI_ID', $reqId);
		$set->setField('PATH', $target_file_generate);
		$set->setField('PATH_ASLI', $target_file_asli);
		$set->setField('EXT', $ext);

		$check="";
		$set->setField('PEGAWAI_ID', $reqId);
		$set->setField("RIWAYAT_TABLE",  ValToNullDB($check));
		$set->setField("RIWAYAT_FIELD",  ValToNullDB($check));
		$set->setField("FILE_KUALITAS_ID", ValToNullDB($check));
		$set->setField("KATEGORI_FILE_ID", ValToNullDB($check));
		$set->setField("RIWAYAT_ID", ValToNullDB($check));

		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("CREATE_USER", $this->LOGIN_USER);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");

		$set->setField("IPCLIENT", sfgetipaddress());
		$set->setField("MACADDRESS", sfgetmac());
		$set->setField("NAMACLIENT", getHostName());

		if ($uploadOk == 0) {
			echo $uploadOk."-".$error;
		} 
		else 
		{
			if(file_exists($target_file_generate)){}
			else
			{
				if(empty($target_file_generate) || empty($target_file_asli))
				{
					echo "xxx-Data gagal disimpan.";
				}
				else
				{
					if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file_generate)) {
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
			$inforiwayattable= $set_detil->getField("RIWAYAT_TABLE");
			$inforiwayatid= $set_detil->getField("RIWAYAT_ID");
			$inforiwayatfield= $set_detil->getField("RIWAYAT_FIELD");
			$inforiwayatdata= $set_detil->getField("INFO_DATA");

			if($set_detil->getField("NO_URUT") == 19 && $inforiwayatid == 19)
        		continue;

        	if($inforiwayattable == "HUKUMAN")
        	{
	        	$infohukumanmenuid= "0111";
	        	$infoakseshukumanmenu= $this->checkmenupegawai($this->LOGIN_ID, $infohukumanmenuid);
	        	// echo $infoakseshukumanmenu;exit;

	        	if($infoakseshukumanmenu == "A"){}
	        	else
	        	{
	        		continue;
	        	}
        	}
			$arrID[$i] = $inforiwayattable.";".$inforiwayatid.";".$inforiwayatfield;
			$arrNama[$i] = $inforiwayatdata;
			$i += 1;
		}
		unset($set_detil);
		
		$arrFinal = array("arrID" => $arrID, "arrNama" => $arrNama);
		echo json_encode($arrFinal);
	}

	public function checkmenupegawai($userlogin, $menuid)
	{
		$this->load->model('AksesAppSimpeg');

		$statement= " AND M.MENU_ID = '".$menuid."' AND A.USER_LOGIN_ID = ".$userlogin;
		$akses= new AksesAppSimpeg();
		$akses->selectByParamsAkses($statement);
		$akses->firstRow();
		// echo $akses->query;exit();
		$tempAksesMenu= $akses->getField("AKSES");
		return $tempAksesMenu;
	}
	
	function resetefile()
	{
		$this->load->model('PegawaiFile');
		$reqId= $this->input->get("reqId");

		$set= new PegawaiFile();
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("LAST_DATE", "CURRENT_DATE");
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("IPCLIENT", sfgetipaddress());
		$set->setField("MACADDRESS", sfgetmac());
		$set->setField("NAMACLIENT", getHostName());
		$set->setField("PEGAWAI_FILE_ID", $reqId);
		$set->resetefile();

		echo "Data berhasil direset.";
	}

	function delete()
	{	
		$this->load->model('PegawaiFile');
		
		$reqId= $this->input->get("reqId");
		$reqPegawaiId= $this->input->get("reqPegawaiId");
		$reqUrl= $this->input->get("reqUrl");
		// print_r($reqId);exit;

		$target_dir= "uploads/".$reqPegawaiId."/";
		$fn= $target_dir.$reqUrl;
		$needle= $reqUrl;
		$newfn= $target_dir.setfiledeleteinfolder($target_dir, $needle);
		// echo $fn."---".$newfn;exit();
		if(rename($fn,$newfn))
		{
			if($reqId == ""){}
			else
			{
				$set= new PegawaiFile();
				$set->setField("PEGAWAI_FILE_ID", $reqId);
				$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
				$set->setField("LAST_USER", $this->LOGIN_USER);
				$set->setField("LAST_DATE", "CURRENT_DATE");
				$set->setField("IPCLIENT", sfgetipaddress());
				$set->setField("MACADDRESS", sfgetmac());
				$set->setField("NAMACLIENT", getHostName());
				$set->setField("PATH", $newfn);
				$set->renamelokasi();
				// $set->deletelog();
				// $set->deleteNew();
				unset($set);
			}
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