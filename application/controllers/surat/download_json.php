<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class download_json extends CI_Controller {

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

	// kalau status 1, maka baru proses
	// kalau status 2, maka proses telah selesai tinggal download
	function syncdata()
	{
		$this->load->model('persuratan/LogDownload');

		$reqLogId= $this->input->get("reqLogId");
		$h= $this->input->get("h");
		$reqUser= $this->LOGIN_USER;

		$param["reqLogId"]= $reqLogId;
		$param["reqUser"]= $reqUser;

		// kalau mau proses ulang
		if($h == "1")
		{
			$set= new LogDownload();
			$set->setField("LAST_USER", $reqUser);
			$set->setField("INFO_MODE", $reqLogId);	
			$set->delete();
		}

		$statement= " AND A.LAST_USER = '".$reqUser."' AND A.INFO_MODE = '".$reqLogId."'";
		$set= new LogDownload();
		$set->selectparams(array(), -1, -1, $statement);
		$set->firstRow();
		$infostatus= $set->getField("STATUS");
		$infolink= $set->getField("INFO_LINK");
		// echo $infostatus;exit;

		$requrl= "";
		$infosimpan= "xxx";
		if(empty($infostatus))
		{
			// $this->sync($param);
			$this->synclinux($param);
		}
		else if($infostatus == "2")
		{
			$infosimpan= "";
			$requrl= $infolink;
		}
		echo $infosimpan."***".$requrl;
	}

	function sync($param=[])
	{
		// $reqLogId= $this->input->get("reqLogId");
		$reqLogId= $param["reqLogId"];
		$reqUser= $param["reqUser"];
		
		$WshShell = new COM("WScript.Shell");
		$oExec = $WshShell->Run("php ".getcwd()."\\execdownload.php surat/download_json getdownload ".$reqLogId." ".$reqUser, 0, false);
	}

	function synclinux($param=[])
	{
		// $reqLogId= $this->input->get("reqLogId");
		$reqLogId= $param["reqLogId"];
		$reqUser= $param["reqUser"];

		shell_exec("php ".getcwd()."/execdownload.php surat/download_json getdownload ".$reqLogId." ".$reqUser." 0 false > /dev/null 2>/dev/null &");
		// shell_exec("php ".getcwd()."/execdownload.php surat/download_json getdownload ".$reqLogId." ".$reqUser." 0 false");
	}

	function infodownload()
	{

	}
	
	function getdownload()
	{
		$this->load->library('globalfilepegawai');

		$reqLogId= $this->input->post("reqLogId");
		$reqUser= $this->input->post("reqUser");
		// $reqLogId= $this->input->get("reqLogId");

		$this->load->model('persuratan/LogDownload');

		$arrlogid= explode("/", $reqLogId);
		$arrmode= explode("_", $arrlogid[1]);
		$reqMode= $arrmode[0];
		$reqId= $arrmode[1];

		// untuk awal pertama flag
		$statement= " AND A.LAST_USER = '".$reqUser."' AND A.INFO_MODE = '".$reqLogId."'";
		$set= new LogDownload();
		$set->selectparams(array(), -1, -1, $statement);
		$infoada= $set->firstRow();

		$set= new LogDownload();
		$set->setField("LAST_USER", $reqUser);
		$set->setField("INFO_MODE", $reqLogId);
		if($infoada)
			$set->updatemulai();
		else
			$set->insert();

		$vfpeg= new globalfilepegawai();

		$arrparam= ["reqId"=>$reqId, "reqMode"=>$reqMode];
		$persyaratanpegawaiusulan= $vfpeg->persyaratanpegawaiusulan($arrparam);
		// print_r($persyaratanpegawaiusulan);exit;

		$arrparam= ["reqId"=>$reqId, "reqMode"=>$reqMode];
		$persyaratanusulan= $vfpeg->persyaratanusulan($arrparam);

		// default value ambil dari surat keluar
		$tempNomorSuratKeluar= $persyaratanusulan["SURAT_KELUAR_NOMOR"];
		$tempTanggalSuratKeluar= $persyaratanusulan["SURAT_KELUAR_TANGGAL"];
		// kalau data belum ada ambil dari usulan
		if(empty($tempNomorSuratKeluar))
		{
			$tempNomorSuratKeluar= $persyaratanusulan["SEMENTARA_NOMOR"];
			$tempTanggalSuratKeluar= $persyaratanusulan["SEMENTARA_TANGGAL"];
		}

		// $arrparam= ["reqId"=>$reqId, "reqMode"=>$reqMode];
		// $arrpegawai= $vfpeg->persyaratanvalid($arrparam);
		$arrpegawai= [];
		foreach ($persyaratanpegawaiusulan as $keyusulanpegawai => $valueusulanpegawai)
		{
			$suratmasukpegawaiid= $valueusulanpegawai["SURAT_MASUK_PEGAWAI_ID"];
			// echo $suratmasukpegawaiid."\n";

			$arrparam= ["reqId"=>$suratmasukpegawaiid, "reqMode"=>"personal"];
			$vfrom= $arrparam["vfrom"];
			$arrusulanpegawai= $vfpeg->persyaratanvalid($arrparam);
			// print_r($arrusulanpegawai);
			array_push($arrpegawai, $arrusulanpegawai[0]);
		}
		// print_r($arrpegawai);exit;

		$indexZip= 0;
		$files_to_zip= [];
		foreach ($arrpegawai as $keypegawai => $valuepegawai)
		{
			// print_r($valuepegawai);exit;
			$pegawaiinfoid= $valuepegawai["PEGAWAI_ID"];
			$tempNipBaru= $valuepegawai["PEGAWAI_NIP_BARU"];
			$tempNamaLengkap= $valuepegawai["PEGAWAI_NAMA_LENGKAP"];

			$buatfolderzip= utf8_encode($tempTanggalSuratKeluar."-".str_replace("/","_",$tempNomorSuratKeluar)."/".str_replace("/","_",$tempNamaLengkap)."-".$tempNipBaru);
			// echo $buatfolderzip;exit;

			$direktori= "uploadszip/".$reqUser."/".$reqLogId;
			$direktoriFile= $direktori."/".$buatfolderzip."/";

			if(file_exists($direktoriFile)){}
			else
			{
				makedirs($direktoriFile);
			}

			$arrlistpilihfilepegawai= $valuepegawai["arrlistpilihfilepegawai"];
			// print_r($arrlistpilihfilepegawai);exit;

			foreach ($arrlistpilihfilepegawai as $keyfile => $valuefile)
			{
		    	// print_r($valuefile);exit;
			    $tempPath= $valuefile["vurl"];
			    $tempNewPath= basename($tempPath);
		    	// echo $tempNewPath;exit;

			    // set ghostscript
			    $gsfilepdf= $vfpeg->gsfilepdf($direktoriFile, $tempNewPath);
			    // echo basename($gsfilepdf);exit;
			    $tempNewPath= basename($gsfilepdf);

			    // echo $tempNewPath;exit;
			    $tempFormatBkn= $valuefile["FORMAT_BKN"];

			    if(!file_exists($tempPath) || empty($tempFormatBkn))
			    	continue;

			    $file= $tempPath;
			    $linkfilePath= $direktoriFile;
			    $newfile= $direktoriFile.$tempNewPath;
		    	// echo $newfile;exit;

			    if(file_exists($newfile))
			    {
			    	$files_to_zip[$indexZip]["PEGAWAI_ID"]= $pegawaiinfoid;
			    	$files_to_zip[$indexZip]["LINK"]= $newfile;
			    	$files_to_zip[$indexZip]["LINK_PATH"]= $linkfilePath;
			    	$files_to_zip[$indexZip]["FORMAT_BKN"]= $tempFormatBkn;
			    	$files_to_zip[$indexZip]["FORMAT_BKN_PEGAWAI"]= $tempFormatBkn."-".$pegawaiinfoid;
			    	$indexZip++;
			    }
			    else
			    {
					// echo $file;exit();
					// echo $newfile;exit;
					if(!copy($file,$newfile)){}
					else
					{
				        $files_to_zip[$indexZip]["PEGAWAI_ID"]= $pegawaiinfoid;
				        $files_to_zip[$indexZip]["LINK"]= $newfile;
				        $files_to_zip[$indexZip]["LINK_PATH"]= $linkfilePath;
				        $files_to_zip[$indexZip]["FORMAT_BKN"]= $tempFormatBkn;
				        $files_to_zip[$indexZip]["FORMAT_BKN_PEGAWAI"]= $tempFormatBkn."-".$pegawaiinfoid;
				        $indexZip++;
		      		}
		    	}
		  	}
		}

		$merfefile= [];
		foreach ($files_to_zip as $keyfile => $valuefile)
		{
  			// print_r($valuefile);exit;
			$pegawaiinfoid= $valuefile["PEGAWAI_ID"];
			$varlink= $valuefile["LINK"];
			$infocarikey= $valuefile["FORMAT_BKN"]."-".$pegawaiinfoid;
			$arrkondisicheck= in_array_column($infocarikey, "FORMAT_BKN_PEGAWAI", $files_to_zip);

  			$jumlahmerge= count($arrkondisicheck);
  			// kalau data lebih dari satu
  			if($jumlahmerge > 1)
  			{
  				$indexmerge= $arrkondisicheck[0];
  				$linkmerge= $merfefile[$indexmerge]["LINK_MERGE"];
  				if(empty($linkmerge))
  				{
  					array_push($merfefile, $valuefile);
  					$jumlahmerge= 0;
  				}
  				else
  				{
  					$jumlahmerge= count($merfefile[$indexmerge]["LINK_MERGE"]);
  				}

    			// masukkan data merge link
  				if($jumlahmerge == 0)
  				{
  					$arrmerge[$jumlahmerge]= $varlink;
  					$merfefile[$indexmerge]["LINK_MERGE"]= $arrmerge;
  				}
  				else
  				{
  					array_push($merfefile[$indexmerge]["LINK_MERGE"], $varlink);
  				}
  			}
  			else
  			{
  				array_push($merfefile, $valuefile);
  			}
  		}
		// print_r($merfefile);exit;

  		$data_files_to_zip= [];
  		foreach ($merfefile as $keyfile => $valuefile)
  		{
  			$arrlinkmerge= $valuefile["LINK_MERGE"];
  			// print_r($valuefile);exit;

  			$linkfiledata= $valuefile["LINK_PATH"];
  			$vlinkfile= $valuefile["LINK"];
  			$namafilelama= basename($vlinkfile);
  			$namafilebaru= $valuefile["FORMAT_BKN"].".pdf";

  			$filelokasiformatlama= $vlinkfile;
  			$filelokasiformatbaru= $linkfiledata.$namafilebaru;
  			// penyesuian penamaan
  			$filelokasiformatbaru= str_replace("S-3/Doktor", "S3", str_replace("Diploma III/Sarjana Muda", "D3", str_replace("D4/S-1", "S-1", str_replace("S-1/Sarjana", "S-1", $filelokasiformatbaru))));

  			// kalau ada merge file
  			if(!empty($arrlinkmerge))
  			{
  				if(file_exists($filelokasiformatbaru))
  				{
	  				$pdf = new PDFMerger;
	  				foreach ($arrlinkmerge as $keymerge => $valuemerge)
	  				{
	  					$pdf->addPDF($valuemerge, 'all');
	  				}

	  				array_push($data_files_to_zip, $filelokasiformatbaru);
				    // $pdf->merge('browser'); // send the file to the browser.
				    $pdf->merge('file', $filelokasiformatbaru);
				    unset($pdf);
				}

			    /*foreach ($arrlinkmerge as $keymerge => $valuemerge)
			    {
			      if(file_exists($valuemerge))
			      {
			        unlink($valuemerge);
			      }
			    }*/
			}
			else
			{
				// kalau ada data sebelumnya hapus terlebih dahulu
				if(file_exists($filelokasiformatbaru))
  				{
  					unlink($filelokasiformatbaru);
  				}

				if(file_exists($filelokasiformatlama))
  				{
  					rename($filelokasiformatlama, $filelokasiformatbaru);
  					array_push($data_files_to_zip, $filelokasiformatbaru);
  				}
			}
		}
		
		if(!empty($data_files_to_zip))
		{
			// untuk buat file zip
			$setLokasiZip= $direktori."/".str_replace(" ", "_", str_replace("/","_",$tempNomorSuratKeluar)).".zip";
			if(file_exists($setLokasiZip))
			{
				unlink($setLokasiZip);
			}
		    // echo $setLokasiZip;exit;
		    $result = create_zip($data_files_to_zip,$setLokasiZip);
		    $down = $setLokasiZip;

		    // untuk update kalau file sudah selesai
		    $set= new LogDownload();
			$set->setField("LAST_USER", $reqUser);
			$set->setField("INFO_MODE", $reqLogId);
			$set->setField("INFO_LINK", $setLokasiZip);
			$set->updateselesai();
		}

	}

	function tesgetdownload()
	{
		$this->load->library('globalfilepegawai');

		// $reqLogId= "kenaikan_pangkat/usulan_378";
		$reqUser= "DEMAS";

		// $reqLogId= "kenaikan_pangkat/usulan_376";
		$reqLogId= "kenaikan_pangkat/usulan_366";
		// $reqLogId= "kenaikan_pangkat/usulan_369";
		// $reqUser= "admin";
		// $reqLogId= $this->input->get("reqLogId");

		$this->load->model('persuratan/LogDownload');

		$arrlogid= explode("/", $reqLogId);
		$arrmode= explode("_", $arrlogid[1]);
		$reqMode= $arrmode[0];
		$reqId= $arrmode[1];

		$statement= " AND A.LAST_USER = '".$reqUser."' AND A.INFO_MODE = '".$reqLogId."'";
		$set= new LogDownload();
		$set->selectparams(array(), -1, -1, $statement);
		$infoada= $set->firstRow();

		$set= new LogDownload();
		$set->setField("LAST_USER", $reqUser);
		$set->setField("INFO_MODE", $reqLogId);
		if($infoada)
			$set->updatemulai();
		else
			$set->insert();

		$vfpeg= new globalfilepegawai();

		$arrparam= ["reqId"=>$reqId, "reqMode"=>$reqMode];
		$persyaratanpegawaiusulan= $vfpeg->persyaratanpegawaiusulan($arrparam);
		// print_r($persyaratanpegawaiusulan);exit;

		$arrparam= ["reqId"=>$reqId, "reqMode"=>$reqMode];
		$persyaratanusulan= $vfpeg->persyaratanusulan($arrparam);
		// print_r($persyaratanusulan);exit;

		// default value ambil dari surat keluar
		$tempNomorSuratKeluar= $persyaratanusulan["SURAT_KELUAR_NOMOR"];
		$tempTanggalSuratKeluar= $persyaratanusulan["SURAT_KELUAR_TANGGAL"];
		// kalau data belum ada ambil dari usulan
		if(empty($tempNomorSuratKeluar))
		{
			$tempNomorSuratKeluar= $persyaratanusulan["SEMENTARA_NOMOR"];
			$tempTanggalSuratKeluar= $persyaratanusulan["SEMENTARA_TANGGAL"];
		}

		// $arrparam= ["reqId"=>$reqId, "reqMode"=>$reqMode];
		// $arrpegawai= $vfpeg->persyaratanvalid($arrparam);
		$arrpegawai= [];
		foreach ($persyaratanpegawaiusulan as $keyusulanpegawai => $valueusulanpegawai)
		{
			$suratmasukpegawaiid= $valueusulanpegawai["SURAT_MASUK_PEGAWAI_ID"];
			// echo $suratmasukpegawaiid."\n";

			$arrparam= ["reqId"=>$suratmasukpegawaiid, "reqMode"=>"personal"];
			$vfrom= $arrparam["vfrom"];
			$arrusulanpegawai= $vfpeg->persyaratanvalid($arrparam);
			// print_r($arrusulanpegawai);
			array_push($arrpegawai, $arrusulanpegawai[0]);
		}
		print_r($arrpegawai);exit;

		$indexZip= 0;
		$files_to_zip= [];
		foreach ($arrpegawai as $keypegawai => $valuepegawai)
		{
			// print_r($valuepegawai);exit;
			$pegawaiinfoid= $valuepegawai["PEGAWAI_ID"];
			$tempNipBaru= $valuepegawai["PEGAWAI_NIP_BARU"];
			$tempNamaLengkap= $valuepegawai["PEGAWAI_NAMA_LENGKAP"];

			$buatfolderzip= utf8_encode($tempTanggalSuratKeluar."-".str_replace("/","_",$tempNomorSuratKeluar)."/".str_replace("/","_",$tempNamaLengkap)."-".$tempNipBaru);
			// echo $buatfolderzip;exit;

			$direktori= "uploadszip/".$reqUser."/".$reqLogId;
			$direktoriFile= $direktori."/".$buatfolderzip."/";

			if(file_exists($direktoriFile)){}
			else
			{
				makedirs($direktoriFile);
			}
			// echo $direktoriFile;exit;

			$arrlistpilihfilepegawai= $valuepegawai["arrlistpilihfilepegawai"];
			// print_r($arrlistpilihfilepegawai);exit;

			foreach ($arrlistpilihfilepegawai as $keyfile => $valuefile)
			{
		    	// print_r($valuefile);exit;
			    $tempPath= $valuefile["vurl"];
			    $tempNewPath= basename($tempPath);
		    	// echo $tempNewPath;exit;

			    // set ghostscript
			    $gsfilepdf= $vfpeg->gsfilepdf($direktoriFile, $tempNewPath);
			    // echo basename($gsfilepdf);exit;
			    $tempNewPath= basename($gsfilepdf);

			    // echo $tempNewPath;exit;
			    $tempFormatBkn= $valuefile["FORMAT_BKN"];

			    if(!file_exists($tempPath) || empty($tempFormatBkn))
			    	continue;

			    $file= $tempPath;
			    $linkfilePath= $direktoriFile;
			    $newfile= $direktoriFile.$tempNewPath;
		    	// echo $newfile;exit;

			    if(file_exists($newfile))
			    {
			    	$files_to_zip[$indexZip]["PEGAWAI_ID"]= $pegawaiinfoid;
			    	$files_to_zip[$indexZip]["LINK"]= $newfile;
			    	$files_to_zip[$indexZip]["LINK_PATH"]= $linkfilePath;
			    	$files_to_zip[$indexZip]["FORMAT_BKN"]= $tempFormatBkn;
			    	$files_to_zip[$indexZip]["FORMAT_BKN_PEGAWAI"]= $tempFormatBkn."-".$pegawaiinfoid;
			    	$indexZip++;
			    }
			    else
			    {
					// echo $file;exit();
					// echo $newfile;exit;
					if(!copy($file,$newfile)){}
					else
					{
				        $files_to_zip[$indexZip]["PEGAWAI_ID"]= $pegawaiinfoid;
				        $files_to_zip[$indexZip]["LINK"]= $newfile;
				        $files_to_zip[$indexZip]["LINK_PATH"]= $linkfilePath;
				        $files_to_zip[$indexZip]["FORMAT_BKN"]= $tempFormatBkn;
				        $files_to_zip[$indexZip]["FORMAT_BKN_PEGAWAI"]= $tempFormatBkn."-".$pegawaiinfoid;
				        $indexZip++;
		      		}
		    	}
		  	}
		}

		$merfefile= [];
		foreach ($files_to_zip as $keyfile => $valuefile)
		{
  			// print_r($valuefile);exit;
			$pegawaiinfoid= $valuefile["PEGAWAI_ID"];
			$varlink= $valuefile["LINK"];
			$infocarikey= $valuefile["FORMAT_BKN"]."-".$pegawaiinfoid;
			$arrkondisicheck= in_array_column($infocarikey, "FORMAT_BKN_PEGAWAI", $files_to_zip);

  			$jumlahmerge= count($arrkondisicheck);
  			// kalau data lebih dari satu
  			if($jumlahmerge > 1)
  			{
  				$indexmerge= $arrkondisicheck[0];
  				$linkmerge= $merfefile[$indexmerge]["LINK_MERGE"];
  				if(empty($linkmerge))
  				{
  					array_push($merfefile, $valuefile);
  					$jumlahmerge= 0;
  				}
  				else
  				{
  					$jumlahmerge= count($merfefile[$indexmerge]["LINK_MERGE"]);
  				}

    			// masukkan data merge link
  				if($jumlahmerge == 0)
  				{
  					$arrmerge[$jumlahmerge]= $varlink;
  					$merfefile[$indexmerge]["LINK_MERGE"]= $arrmerge;
  				}
  				else
  				{
  					array_push($merfefile[$indexmerge]["LINK_MERGE"], $varlink);
  				}
  			}
  			else
  			{
  				array_push($merfefile, $valuefile);
  			}
  		}
		// print_r($merfefile);exit;

  		$data_files_to_zip= [];
  		foreach ($merfefile as $keyfile => $valuefile)
  		{
  			$arrlinkmerge= $valuefile["LINK_MERGE"];
  			// print_r($valuefile);exit;

  			$linkfiledata= $valuefile["LINK_PATH"];
  			$vlinkfile= $valuefile["LINK"];
  			$namafilelama= basename($vlinkfile);
  			$namafilebaru= $valuefile["FORMAT_BKN"].".pdf";

  			$filelokasiformatlama= $vlinkfile;
  			$filelokasiformatbaru= $linkfiledata.$namafilebaru;
  			// penyesuian penamaan
  			$filelokasiformatbaru= str_replace("S-3/Doktor", "S3", str_replace("Diploma III/Sarjana Muda", "D3", str_replace("D4/S-1", "S-1", str_replace("S-1/Sarjana", "S-1", $filelokasiformatbaru))));

  			// kalau ada merge file
  			if(!empty($arrlinkmerge))
  			{
  				if(file_exists($filelokasiformatbaru))
  				{
  					$pdf = new PDFMerger;
	  				foreach ($arrlinkmerge as $keymerge => $valuemerge)
	  				{
	  					$pdf->addPDF($valuemerge, 'all');
	  				}

	  				array_push($data_files_to_zip, $filelokasiformatbaru);
	  				// print_r($data_files_to_zip);exit;
				    // $pdf->merge('browser'); // send the file to the browser.
				    $pdf->merge('file', $filelokasiformatbaru);
				    unset($pdf);
  				}

			    /*foreach ($arrlinkmerge as $keymerge => $valuemerge)
			    {
			      if(file_exists($valuemerge))
			      {
			        unlink($valuemerge);
			      }
			    }*/
			}
			else
			{
				// kalau ada data sebelumnya hapus terlebih dahulu
				if(file_exists($filelokasiformatbaru))
  				{
  					// echo "xx:".$filelokasiformatbaru."<br>";
  					unlink($filelokasiformatbaru);
  				}

				if(file_exists($filelokasiformatlama))
  				{
  					// echo "yy:".$filelokasiformatlama."<br>";
  					// exit;
  					// echo $filelokasiformatbaru;exit;
  					rename($filelokasiformatlama, $filelokasiformatbaru);
  					// echo "zz:".$filelokasiformatbaru."<br>";
  					array_push($data_files_to_zip, $filelokasiformatbaru);
  				}
			}
		}

		// echo "Asd";
		print_r($data_files_to_zip);exit;
		if(!empty($data_files_to_zip))
		{
			// untuk buat file zip
			$setLokasiZip= $direktori."/".str_replace(" ", "_", str_replace("/","_",$tempNomorSuratKeluar)).".zip";
			if(file_exists($setLokasiZip))
			{
				unlink($setLokasiZip);
			}
		    // echo $setLokasiZip;exit;
		    $result = create_zip($data_files_to_zip,$setLokasiZip);
		    $down = $setLokasiZip;

		    // untuk update kalau file sudah selesai
		    $set= new LogDownload();
			$set->setField("LAST_USER", $reqUser);
			$set->setField("INFO_MODE", $reqLogId);
			$set->setField("INFO_LINK", $setLokasiZip);
			$set->updateselesai();
		}

	}

	function callcb()
	{
		// shell_exec("php ".getcwd()."/execdownload.php surat/download_json cobazip ".$reqLogId." ".$reqUser." 0 false > /dev/null 2>/dev/null &");
		// shell_exec("php-cli ".getcwd()."/execdownload.php surat/download_json cobazip ".$reqLogId." ".$reqUser." 0 false > /dev/null 2>/dev/null &");

		shell_exec("php ".getcwd()."/execdownload.php surat/download_json cobazip ".$reqLogId." ".$reqUser." 0 false");
		// exec("php-cli -l ".getcwd()."/execdownload.php surat/download_json cobazip ",$error);
		echo "ttt";
	}

	function cobazip()
	{
		// $direktori= "uploadszip/admin/kenaikan_pangkat/usulan_331";
		$direktori= "uploadszip/DEMAS/kenaikan_pangkat/usulan_380";
		$setLokasiZip= $direktori."/tes.zip";

		$data_files_to_zip= [];
		// $filelokasi= $direktori."/2022-12-01-202304-Coba 1/ARIF GUNAWAN, S.Kep., Ns-197905032006041023/PPK_2022_197905032006041023.pdf";
		// array_push($data_files_to_zip, $filelokasi);

		// $filelokasi= $direktori."/2022-12-01-202304-Coba 1/DIAN KUSUMA RAHMAD SUBEKTI, ST., M.Si-197705132003121004/PEMBERHENTIAN_JFT_197705132003121004.pdf";
		// array_push($data_files_to_zip, $filelokasi);

		// $filelokasi= $direktori."/2022-12-01-202304-Coba 1/DIAN KUSUMA RAHMAD SUBEKTI, ST., M.Si-197705132003121004/PPK_2022_197705132003121004.pdf";
		// array_push($data_files_to_zip, $filelokasi);

		$filelokasi= $direktori."/2023-01-03-2023.04 FUNGSIONAL IV A DAN IV B (2)/SRI WAHYUNINGSIH, S.Pd-197411091999122002/zR2y0Alz2e0qWpwP99.pdf";
		array_push($data_files_to_zip, $filelokasi);

		$filelokasi= $direktori."/2023-01-03-2023.04 FUNGSIONAL IV A DAN IV B (2)/SRI WAHYUNINGSIH, S.Pd-197411091999122002/r9xPQwa8zQ0Oojj16DOtBkWiIENr3g.PDF";
		array_push($data_files_to_zip, $filelokasi);

		if(file_exists($setLokasiZip))
		{
			unlink($setLokasiZip);
		}
	    // echo $setLokasiZip;exit;
	    $result = create_zip($data_files_to_zip,$setLokasiZip);
	    $down = $setLokasiZip;
		
	}
}
?>