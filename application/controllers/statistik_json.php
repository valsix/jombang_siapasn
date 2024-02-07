<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class statistik_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// trow to unauthenticated page!
			//redirect('Login');
		}       
		
		/* GLOBAL VARIABLE */
		$this->USER_GROUP= $this->kauth->getInstance()->getIdentity()->USER_GROUP;
		$this->LOGIN_USER= $this->kauth->getInstance()->getIdentity()->LOGIN_USER;
		$this->LOGIN_LEVEL= $this->kauth->getInstance()->getIdentity()->LOGIN_LEVEL;
		$this->SATUAN_KERJA_TIPE= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_TIPE;
		$this->SATUAN_KERJA_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID;
		$this->SATUAN_KERJA_BKD_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_BKD_ID;
		$this->STATUS_SATUAN_KERJA_BKD= $this->kauth->getInstance()->getIdentity()->STATUS_SATUAN_KERJA_BKD;
	}	
	
	function table_golongan_ruang() 
	{
		$this->load->model('Statistik');
		$this->load->model('SatuanKerja');
		
		$set = new Statistik();
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$tempSatuanKerjaId= $reqSatuanKerjaId;

		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE)
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
		)";

		if($reqSatuanKerjaId == ""){}
		else
		{
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
			{
				$reqSatuanKerjaId= "";
				if($tempSatuanKerjaId == ""){}
				else
				{
					$reqSatuanKerjaId= $tempSatuanKerjaId;
					$skerja= new SatuanKerja();
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
					unset($skerja);
					//echo $reqSatuanKerjaId;exit;
					$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				}
			}
			else
			{
				$skerja= new SatuanKerja();
				// if($this->SATUAN_KERJA_TIPE == "1")
				// {
				// 	$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
				// }
				// else
				// {
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				// }
				// echo $skerja->query;exit();
				unset($skerja);
				// echo $reqSatuanKerjaId;exit;
				$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			}
		}
		
		$set->selectByParamsGolonganPangkat($statementAktif.$statement);
		//echo $set->query;exit;
		
		$str='
		<table class="bordered striped md-text table_list tabel-responsif small-td-th">
		<thead>
			<tr>
				<th>Golongan Ruang</th>
				<th>Jumlah</th>
			</tr>
		</thead>
		<tbody>
		';

		$tempTotal= 0;
		while($set->nextRow())
		{
		$str.='
			<tr class="md-text">
				<td>'.$set->getField("NAMA").'</td>
				<td>'.$set->getField("JUMLAH").'</td>
			</tr>
		';
		$tempTotal+= $set->getField("JUMLAH");
		}
			
		$str.='
		<tr class="md-text">
			<td>Total</td>
			<td>'.$tempTotal.'</td>
		</tr>
		</tbody>
		</table>
        ';
		echo $str;
	}
	
	function grafik_golongan_ruang() 
	{
		$this->load->model('Statistik');
		$this->load->model('SatuanKerja');
		//$tempData[0]= array("x"=>1369209600000,"y"=>10,"myData"=>"07:00 s/d 09:00<br/>Rp 7.450.000,-");
		//$tempData[1]= array("x"=>1369216800000,"y"=>20,"myData"=>"ASDASD");
		
		$result= "";
		$result= array();
		
		$set = new Statistik();
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$tempSatuanKerjaId= $reqSatuanKerjaId;
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE)
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
		)";

		if($reqSatuanKerjaId == ""){}
		else
		{
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
			{
				$reqSatuanKerjaId= "";
				if($tempSatuanKerjaId == ""){}
				else
				{
					$reqSatuanKerjaId= $tempSatuanKerjaId;
					$skerja= new SatuanKerja();
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
					unset($skerja);
					//echo $reqSatuanKerjaId;exit;
					$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				}
			}
			else
			{
				$skerja= new SatuanKerja();
				// if($this->SATUAN_KERJA_TIPE == "1")
				// {
				// 	$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
				// }
				// else
				// {
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				// }
				// echo $skerja->query;exit();
				unset($skerja);
				// echo $reqSatuanKerjaId;exit;
				$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			}
		}
		
		$arrData= [];
		$index_data= 0;
		$set= new Statistik();
		$set->selectByParamsGolonganPangkat($statementAktif.$statement);
		//echo $set->query;exit;
		while($set->nextRow())
		{
			$arrData[$index_data]["NAMA"] = $set->getField("NAMA");
			$arrData[$index_data]["JUMLAH"] = $set->getField("JUMLAH");
			$index_data++;
		}
		$jumlah_data= $index_data;
		//print_r($arrData);exit;
		
		for($index=0; $index < $jumlah_data; $index++)
		{
			$tempNama= $arrData[$index]["NAMA"];
			$tempJumlah= $arrData[$index]["JUMLAH"];

			// $tempNama.= "<br/>".$tempJumlah;
			
			$kategori[$index]= $tempNama;
			
			$tempData= "";
			$rows['name']= $tempNama;
			for($index_detil=0; $index_detil < $jumlah_data; $index_detil++)
			{
				if($index_detil == $index)
				{
					$tempJumlahDetil= (int)$tempJumlah;
				}
				else
				$tempJumlahDetil= null;
				
				$tempData[$index_detil]= $tempJumlahDetil;
			}
			
			$rows['data']= $tempData;
			array_push($result,$rows);
		}
		
		//print_r($result);exit;
		
		//$kategori= array("a", "b");
		print json_encode(array("kategori"=>$kategori, "result"=>$result));
	}
	
	function table_eselon() 
	{
		$this->load->model('Statistik');
		$this->load->model('SatuanKerja');
		
		$set = new Statistik();
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$tempSatuanKerjaId= $reqSatuanKerjaId;
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE)
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
		)";

		if($reqSatuanKerjaId == ""){}
		else
		{
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
			{
				$reqSatuanKerjaId= "";
				if($tempSatuanKerjaId == ""){}
				else
				{
					$reqSatuanKerjaId= $tempSatuanKerjaId;
					$skerja= new SatuanKerja();
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
					unset($skerja);
					//echo $reqSatuanKerjaId;exit;
					$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				}
			}
			else
			{
				$skerja= new SatuanKerja();
				// if($this->SATUAN_KERJA_TIPE == "1")
				// {
				// 	$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
				// }
				// else
				// {
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				// }
				// echo $skerja->query;exit();
				unset($skerja);
				// echo $reqSatuanKerjaId;exit;
				$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			}
		}

		$set->selectByParamsEselon($statementAktif.$statement);
		//echo $set->query;exit;
		
		$str='
		<table class="bordered striped md-text table_list tabel-responsif small-td-th">
		<thead>
			<tr>
				<th>Eselon</th>
				<th>Jumlah</th>
			</tr>
		</thead>
		<tbody>
		';
		
		$tempTotal= 0;
		while($set->nextRow())
		{
		$str.='
			<tr class="md-text">
				<td>'.$set->getField("NAMA").'</td>
				<td>'.$set->getField("JUMLAH").'</td>
			</tr>
		';
		$tempTotal+= $set->getField("JUMLAH");
		}
			
		$str.='
		<tr class="md-text">
			<td>Total</td>
			<td>'.$tempTotal.'</td>
		</tr>
		</tbody>
		</table>
        ';
		echo $str;
	}
	
	function grafik_eselon() 
	{
		$this->load->model('Statistik');
		$this->load->model('SatuanKerja');
		
		$result= "";
		$result= array();
		
		$set = new Statistik();
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$tempSatuanKerjaId= $reqSatuanKerjaId;
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE)
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
		)";

		if($reqSatuanKerjaId == ""){}
		else
		{
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
			{
				$reqSatuanKerjaId= "";
				if($tempSatuanKerjaId == ""){}
				else
				{
					$reqSatuanKerjaId= $tempSatuanKerjaId;
					$skerja= new SatuanKerja();
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
					unset($skerja);
					//echo $reqSatuanKerjaId;exit;
					$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				}
			}
			else
			{
				$skerja= new SatuanKerja();
				// if($this->SATUAN_KERJA_TIPE == "1")
				// {
				// 	$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
				// }
				// else
				// {
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				// }
				// echo $skerja->query;exit();
				unset($skerja);
				// echo $reqSatuanKerjaId;exit;
				$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			}
		}
		
		$arrData= [];
		$index_data= 0;
		$set= new Statistik();
		$set->selectByParamsEselon($statementAktif.$statement);
		//echo $set->query;exit;
		while($set->nextRow())
		{
			$arrData[$index_data]["NAMA"] = $set->getField("NAMA");
			$arrData[$index_data]["JUMLAH"] = $set->getField("JUMLAH");
			$index_data++;
		}
		$jumlah_data= $index_data;
		//print_r($arrData);exit;
		
		for($index=0; $index < $jumlah_data; $index++)
		{
			$tempNama= $arrData[$index]["NAMA"];
			$tempJumlah= $arrData[$index]["JUMLAH"];

			// $tempNama.= "<br/>".$tempJumlah;
			
			$kategori[$index]= $tempNama;
			
			$tempData= "";
			$rows['name']= $tempNama;
			for($index_detil=0; $index_detil < $jumlah_data; $index_detil++)
			{
				if($index_detil == $index)
				{
					$tempJumlahDetil= (int)$tempJumlah;
				}
				else
				$tempJumlahDetil= null;
				
				$tempData[$index_detil]= $tempJumlahDetil;
			}
			
			$rows['data']= $tempData;
			array_push($result,$rows);
		}
		
		print json_encode(array("kategori"=>$kategori, "result"=>$result));
	}
	
	function table_pendidikan() 
	{
		$this->load->model('Statistik');
		$this->load->model('SatuanKerja');
		
		$set = new Statistik();
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$tempSatuanKerjaId= $reqSatuanKerjaId;
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE)
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
		)";

		if($reqSatuanKerjaId == ""){}
		else
		{
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
			{
				$reqSatuanKerjaId= "";
				if($tempSatuanKerjaId == ""){}
				else
				{
					$reqSatuanKerjaId= $tempSatuanKerjaId;
					$skerja= new SatuanKerja();
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
					unset($skerja);
					//echo $reqSatuanKerjaId;exit;
					$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				}
			}
			else
			{
				$skerja= new SatuanKerja();
				// if($this->SATUAN_KERJA_TIPE == "1")
				// {
				// 	$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
				// }
				// else
				// {
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				// }
				// echo $skerja->query;exit();
				unset($skerja);
				// echo $reqSatuanKerjaId;exit;
				$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			}
		}

		$set->selectByParamsPendidikan($statementAktif.$statement);
		//echo $set->query;exit;
		
		$str='
		<table class="bordered striped md-text table_list tabel-responsif small-td-th">
		<thead>
			<tr>
				<th>Pendidikan</th>
				<th>Jumlah</th>
			</tr>
		</thead>
		<tbody>
		';
		
		$tempTotal= 0;
		while($set->nextRow())
		{
		$str.='
			<tr class="md-text">
				<td>'.$set->getField("NAMA").'</td>
				<td>'.$set->getField("JUMLAH").'</td>
			</tr>
		';
		$tempTotal+= $set->getField("JUMLAH");
		}
			
		$str.='
		<tr class="md-text">
			<td>Total</td>
			<td>'.$tempTotal.'</td>
		</tr>
		</tbody>
		</table>
        ';
		echo $str;
	}
	
	function grafik_pendidikan() 
	{
		$this->load->model('Statistik');
		$this->load->model('SatuanKerja');
		
		$result= "";
		$result= array();
		
		$set = new Statistik();
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$tempSatuanKerjaId= $reqSatuanKerjaId;
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE)
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
		)";

		if($reqSatuanKerjaId == ""){}
		else
		{
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
			{
				$reqSatuanKerjaId= "";
				if($tempSatuanKerjaId == ""){}
				else
				{
					$reqSatuanKerjaId= $tempSatuanKerjaId;
					$skerja= new SatuanKerja();
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
					unset($skerja);
					//echo $reqSatuanKerjaId;exit;
					$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				}
			}
			else
			{
				$skerja= new SatuanKerja();
				// if($this->SATUAN_KERJA_TIPE == "1")
				// {
				// 	$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
				// }
				// else
				// {
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				// }
				// echo $skerja->query;exit();
				unset($skerja);
				// echo $reqSatuanKerjaId;exit;
				$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			}
		}

		$arrData= [];
		$index_data= 0;
		$set= new Statistik();
		$set->selectByParamsPendidikan($statementAktif.$statement);
		//echo $set->query;exit;
		while($set->nextRow())
		{
			$arrData[$index_data]["NAMA"] = $set->getField("NAMA");
			$arrData[$index_data]["JUMLAH"] = $set->getField("JUMLAH");
			$index_data++;
		}
		$jumlah_data= $index_data;
		//print_r($arrData);exit;
		
		for($index=0; $index < $jumlah_data; $index++)
		{
			$tempNama= $arrData[$index]["NAMA"];
			$tempJumlah= $arrData[$index]["JUMLAH"];

			// $tempNama.= "<br/>".$tempJumlah;
			
			$kategori[$index]= $tempNama;
			
			$tempData= "";
			$rows['name']= $tempNama;
			for($index_detil=0; $index_detil < $jumlah_data; $index_detil++)
			{
				if($index_detil == $index)
				{
					$tempJumlahDetil= (int)$tempJumlah;
				}
				else
				$tempJumlahDetil= null;
				
				$tempData[$index_detil]= $tempJumlahDetil;
			}
			
			$rows['data']= $tempData;
			array_push($result,$rows);
		}
		
		print json_encode(array("kategori"=>$kategori, "result"=>$result));
	}
	
	function table_jenis_kelamin() 
	{
		$this->load->model('Statistik');
		$this->load->model('SatuanKerja');
		
		$set = new Statistik();
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$tempSatuanKerjaId= $reqSatuanKerjaId;
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE)
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
		)";

		if($reqSatuanKerjaId == ""){}
		else
		{
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
			{
				$reqSatuanKerjaId= "";
				if($tempSatuanKerjaId == ""){}
				else
				{
					$reqSatuanKerjaId= $tempSatuanKerjaId;
					$skerja= new SatuanKerja();
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
					unset($skerja);
					//echo $reqSatuanKerjaId;exit;
					$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				}
			}
			else
			{
				$skerja= new SatuanKerja();
				// if($this->SATUAN_KERJA_TIPE == "1")
				// {
				// 	$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
				// }
				// else
				// {
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				// }
				// echo $skerja->query;exit();
				unset($skerja);
				// echo $reqSatuanKerjaId;exit;
				$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			}
		}

		$set->selectByParamsJenisKelamin($statementAktif.$statement);
		//echo $set->query;exit;
		
		$str='
		<table class="bordered striped md-text table_list tabel-responsif small-td-th">
		<thead>
			<tr>
				<th>Jenis Kelamin</th>
				<th>Jumlah</th>
			</tr>
		</thead>
		<tbody>
		';
		
		$tempTotal= 0;
		while($set->nextRow())
		{
		$str.='
			<tr class="md-text">
				<td>'.$set->getField("NAMA").'</td>
				<td>'.$set->getField("JUMLAH").'</td>
			</tr>
		';
		$tempTotal+= $set->getField("JUMLAH");
		}
			
		$str.='
		<tr class="md-text">
			<td>Total</td>
			<td>'.$tempTotal.'</td>
		</tr>
		</tbody>
		</table>
        ';
		echo $str;
	}
	
	function grafik_jenis_kelamin() 
	{
		$this->load->model('Statistik');
		$this->load->model('SatuanKerja');
		
		$result= "";
		$result= array();
		
		$set = new Statistik();
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$tempSatuanKerjaId= $reqSatuanKerjaId;
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE)
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
		)";

		if($reqSatuanKerjaId == ""){}
		else
		{
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
			{
				$reqSatuanKerjaId= "";
				if($tempSatuanKerjaId == ""){}
				else
				{
					$reqSatuanKerjaId= $tempSatuanKerjaId;
					$skerja= new SatuanKerja();
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
					unset($skerja);
					//echo $reqSatuanKerjaId;exit;
					$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				}
			}
			else
			{
				$skerja= new SatuanKerja();
				// if($this->SATUAN_KERJA_TIPE == "1")
				// {
				// 	$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
				// }
				// else
				// {
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				// }
				// echo $skerja->query;exit();
				unset($skerja);
				// echo $reqSatuanKerjaId;exit;
				$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			}
		}

		$arrData= [];
		$index_data= 0;
		$set= new Statistik();
		$set->selectByParamsJenisKelamin($statementAktif.$statement);
		//echo $set->query;exit;
		while($set->nextRow())
		{
			$arrData[$index_data]["NAMA"] = $set->getField("NAMA");
			$arrData[$index_data]["JUMLAH"] = $set->getField("JUMLAH");
			$index_data++;
		}
		$jumlah_data= $index_data;
		//print_r($arrData);exit;
		
		for($index=0; $index < $jumlah_data; $index++)
		{
			$tempNama= $arrData[$index]["NAMA"];
			$tempJumlah= $arrData[$index]["JUMLAH"];

			// $tempNama.= "<br/>".$tempJumlah;
			
			$kategori[$index]= $tempNama;
			
			$tempData= "";
			$rows['name']= $tempNama;
			for($index_detil=0; $index_detil < $jumlah_data; $index_detil++)
			{
				if($index_detil == $index)
				{
					$tempJumlahDetil= (int)$tempJumlah;
				}
				else
				$tempJumlahDetil= null;
				
				$tempData[$index_detil]= $tempJumlahDetil;
			}
			
			$rows['data']= $tempData;
			array_push($result,$rows);
		}
		
		print json_encode(array("kategori"=>$kategori, "result"=>$result));
	}
	
	function table_agama() 
	{
		$this->load->model('Statistik');
		$this->load->model('SatuanKerja');
		
		$set = new Statistik();
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$tempSatuanKerjaId= $reqSatuanKerjaId;
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE)
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
		)";

		if($reqSatuanKerjaId == ""){}
		else
		{
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
			{
				$reqSatuanKerjaId= "";
				if($tempSatuanKerjaId == ""){}
				else
				{
					$reqSatuanKerjaId= $tempSatuanKerjaId;
					$skerja= new SatuanKerja();
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
					unset($skerja);
					//echo $reqSatuanKerjaId;exit;
					$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				}
			}
			else
			{
				$skerja= new SatuanKerja();
				// if($this->SATUAN_KERJA_TIPE == "1")
				// {
				// 	$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
				// }
				// else
				// {
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				// }
				// echo $skerja->query;exit();
				unset($skerja);
				// echo $reqSatuanKerjaId;exit;
				$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			}
		}

		$set->selectByParamsAgama($statementAktif.$statement);
		//echo $set->query;exit;
		
		$str='
		<table class="bordered striped md-text table_list tabel-responsif small-td-th">
		<thead>
			<tr>
				<th>Agama</th>
				<th>Jumlah</th>
			</tr>
		</thead>
		<tbody>
		';
		
		$tempTotal= 0;
		while($set->nextRow())
		{
		$str.='
			<tr class="md-text">
				<td>'.$set->getField("NAMA").'</td>
				<td>'.$set->getField("JUMLAH").'</td>
			</tr>
		';
		$tempTotal+= $set->getField("JUMLAH");
		}
			
		$str.='
		<tr class="md-text">
			<td>Total</td>
			<td>'.$tempTotal.'</td>
		</tr>
		</tbody>
		</table>
        ';
		echo $str;
	}
	
	function grafik_agama() 
	{
		$this->load->model('Statistik');
		$this->load->model('SatuanKerja');
		
		$result= "";
		$result= array();
		
		$set = new Statistik();
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$tempSatuanKerjaId= $reqSatuanKerjaId;
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE)
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
		)";

		if($reqSatuanKerjaId == ""){}
		else
		{
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
			{
				$reqSatuanKerjaId= "";
				if($tempSatuanKerjaId == ""){}
				else
				{
					$reqSatuanKerjaId= $tempSatuanKerjaId;
					$skerja= new SatuanKerja();
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
					unset($skerja);
					//echo $reqSatuanKerjaId;exit;
					$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				}
			}
			else
			{
				$skerja= new SatuanKerja();
				// if($this->SATUAN_KERJA_TIPE == "1")
				// {
				// 	$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
				// }
				// else
				// {
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				// }
				// echo $skerja->query;exit();
				unset($skerja);
				// echo $reqSatuanKerjaId;exit;
				$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			}
		}
		
		$arrData= [];
		$index_data= 0;
		$set= new Statistik();
		$set->selectByParamsAgama($statementAktif.$statement);
		//echo $set->query;exit;
		while($set->nextRow())
		{
			$arrData[$index_data]["NAMA"] = $set->getField("NAMA");
			$arrData[$index_data]["JUMLAH"] = $set->getField("JUMLAH");
			$index_data++;
		}
		$jumlah_data= $index_data;
		//print_r($arrData);exit;
		
		for($index=0; $index < $jumlah_data; $index++)
		{
			$tempNama= $arrData[$index]["NAMA"];
			$tempJumlah= $arrData[$index]["JUMLAH"];

			// $tempNama.= "<br/>".$tempJumlah;
			
			$kategori[$index]= $tempNama;
			
			$tempData= "";
			$rows['name']= $tempNama;
			for($index_detil=0; $index_detil < $jumlah_data; $index_detil++)
			{
				if($index_detil == $index)
				{
					$tempJumlahDetil= (int)$tempJumlah;
				}
				else
				$tempJumlahDetil= null;
				
				$tempData[$index_detil]= $tempJumlahDetil;
			}
			
			$rows['data']= $tempData;
			array_push($result,$rows);
		}
		
		print json_encode(array("kategori"=>$kategori, "result"=>$result));
	}
	
	function table_golongan_umur() 
	{
		$this->load->model('Statistik');
		$this->load->model('SatuanKerja');
		
		$set = new Statistik();
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$tempSatuanKerjaId= $reqSatuanKerjaId;
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE)
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
		)";

		if($reqSatuanKerjaId == ""){}
		else
		{
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
			{
				$reqSatuanKerjaId= "";
				if($tempSatuanKerjaId == ""){}
				else
				{
					$reqSatuanKerjaId= $tempSatuanKerjaId;
					$skerja= new SatuanKerja();
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
					unset($skerja);
					//echo $reqSatuanKerjaId;exit;
					$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				}
			}
			else
			{
				$skerja= new SatuanKerja();
				// if($this->SATUAN_KERJA_TIPE == "1")
				// {
				// 	$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
				// }
				// else
				// {
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				// }
				// echo $skerja->query;exit();
				unset($skerja);
				// echo $reqSatuanKerjaId;exit;
				$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			}
		}
		
		$set->selectByParamsUmur($statementAktif.$statement);
		//echo $set->query;exit;
		
		$str='
		<table class="bordered striped md-text table_list tabel-responsif small-td-th">
		<thead>
			<tr>
				<th>Golongan Umur</th>
				<th>Jumlah</th>
			</tr>
		</thead>
		<tbody>
		';

		$tempTotal= 0;
		while($set->nextRow())
		{
		$str.='
			<tr class="md-text">
				<td>'.$set->getField("NAMA").'</td>
				<td>'.$set->getField("JUMLAH").'</td>
			</tr>
		';
		$tempTotal+= $set->getField("JUMLAH");
		}
			
		$str.='
		<tr class="md-text">
			<td>Total</td>
			<td>'.$tempTotal.'</td>
		</tr>
		</tbody>
		</table>
        ';
		echo $str;
	}
	
	function grafik_golongan_umur() 
	{
		$this->load->model('Statistik');
		$this->load->model('SatuanKerja');
		
		$result= "";
		$result= array();
		
		$set = new Statistik();
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$tempSatuanKerjaId= $reqSatuanKerjaId;
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE)
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
		)";

		if($reqSatuanKerjaId == ""){}
		else
		{
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
			{
				$reqSatuanKerjaId= "";
				if($tempSatuanKerjaId == ""){}
				else
				{
					$reqSatuanKerjaId= $tempSatuanKerjaId;
					$skerja= new SatuanKerja();
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
					unset($skerja);
					//echo $reqSatuanKerjaId;exit;
					$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				}
			}
			else
			{
				$skerja= new SatuanKerja();
				// if($this->SATUAN_KERJA_TIPE == "1")
				// {
				// 	$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
				// }
				// else
				// {
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				// }
				// echo $skerja->query;exit();
				unset($skerja);
				// echo $reqSatuanKerjaId;exit;
				$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			}
		}
		
		$arrData= [];
		$index_data= 0;
		$set= new Statistik();
		$set->selectByParamsUmur($statementAktif.$statement);
		//echo $set->query;exit;
		while($set->nextRow())
		{
			$arrData[$index_data]["NAMA"] = $set->getField("NAMA");
			$arrData[$index_data]["JUMLAH"] = $set->getField("JUMLAH");
			$index_data++;
		}
		$jumlah_data= $index_data;
		//print_r($arrData);exit;
		
		for($index=0; $index < $jumlah_data; $index++)
		{
			$tempNama= $arrData[$index]["NAMA"];
			$tempJumlah= $arrData[$index]["JUMLAH"];

			// $tempNama.= "<br/>".$tempJumlah;
			
			$kategori[$index]= $tempNama;
			
			$tempData= "";
			$rows['name']= $tempNama;
			for($index_detil=0; $index_detil < $jumlah_data; $index_detil++)
			{
				if($index_detil == $index)
				{
					$tempJumlahDetil= (int)$tempJumlah;
				}
				else
				$tempJumlahDetil= null;
				
				$tempData[$index_detil]= $tempJumlahDetil;
			}
			
			$rows['data']= $tempData;
			array_push($result,$rows);
		}
		
		print json_encode(array("kategori"=>$kategori, "result"=>$result));
	}
	
	function table_tipe_pegawai() 
	{
		$this->load->model('Statistik');
		$this->load->model('SatuanKerja');
		
		$set = new Statistik();
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$tempSatuanKerjaId= $reqSatuanKerjaId;
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE)
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
		)";

		if($reqSatuanKerjaId == ""){}
		else
		{
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
			{
				$reqSatuanKerjaId= "";
				if($tempSatuanKerjaId == ""){}
				else
				{
					$reqSatuanKerjaId= $tempSatuanKerjaId;
					$skerja= new SatuanKerja();
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
					unset($skerja);
					//echo $reqSatuanKerjaId;exit;
					$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				}
			}
			else
			{
				$skerja= new SatuanKerja();
				// if($this->SATUAN_KERJA_TIPE == "1")
				// {
				// 	$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
				// }
				// else
				// {
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				// }
				// echo $skerja->query;exit();
				unset($skerja);
				// echo $reqSatuanKerjaId;exit;
				$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			}
		}

		$set->selectByParamsTipePegawai($statementAktif.$statement);
		//echo $set->query;exit;
		
		$str='
		<table class="bordered striped md-text table_list tabel-responsif small-td-th">
		<thead>
			<tr>
				<th>Tipe Pegawai</th>
				<th>Jumlah</th>
			</tr>
		</thead>
		<tbody>
		';
		
		$tempTotal= 0;
		while($set->nextRow())
		{
		$str.='
			<tr class="md-text">
				<td>'.$set->getField("NAMA").'</td>
				<td>'.$set->getField("JUMLAH").'</td>
			</tr>
		';
		$tempTotal+= $set->getField("JUMLAH");
		}
			
		$str.='
		<tr class="md-text">
			<td>Total</td>
			<td>'.$tempTotal.'</td>
		</tr>
		</tbody>
		</table>
        ';
		echo $str;
	}
	
	function grafik_tipe_pegawai() 
	{
		$this->load->model('Statistik');
		$this->load->model('SatuanKerja');
		
		$result= "";
		$result= array();
		
		$set = new Statistik();
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$tempSatuanKerjaId= $reqSatuanKerjaId;
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE)
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
		)";

		if($reqSatuanKerjaId == ""){}
		else
		{
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
			{
				$reqSatuanKerjaId= "";
				if($tempSatuanKerjaId == ""){}
				else
				{
					$reqSatuanKerjaId= $tempSatuanKerjaId;
					$skerja= new SatuanKerja();
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
					unset($skerja);
					//echo $reqSatuanKerjaId;exit;
					$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				}
			}
			else
			{
				$skerja= new SatuanKerja();
				// if($this->SATUAN_KERJA_TIPE == "1")
				// {
				// 	$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
				// }
				// else
				// {
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				// }
				// echo $skerja->query;exit();
				unset($skerja);
				// echo $reqSatuanKerjaId;exit;
				$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			}
		}
		
		$arrData= [];
		$index_data= 0;
		$set= new Statistik();
		$set->selectByParamsTipePegawai($statementAktif.$statement);
		//echo $set->query;exit;
		while($set->nextRow())
		{
			$arrData[$index_data]["NAMA"] = $set->getField("NAMA");
			$arrData[$index_data]["JUMLAH"] = $set->getField("JUMLAH");
			$index_data++;
		}
		$jumlah_data= $index_data;
		//print_r($arrData);exit;
		
		for($index=0; $index < $jumlah_data; $index++)
		{
			$tempNama= $arrData[$index]["NAMA"];
			$tempJumlah= $arrData[$index]["JUMLAH"];

			// $tempNama.= "<br/>".$tempJumlah;
			
			$kategori[$index]= $tempNama;
			
			$tempData= "";
			$rows['name']= $tempNama;
			for($index_detil=0; $index_detil < $jumlah_data; $index_detil++)
			{
				if($index_detil == $index)
				{
					$tempJumlahDetil= (int)$tempJumlah;
				}
				else
				$tempJumlahDetil= null;
				
				$tempData[$index_detil]= $tempJumlahDetil;
			}
			
			$rows['data']= $tempData;
			array_push($result,$rows);
		}
		
		print json_encode(array("kategori"=>$kategori, "result"=>$result));
	}

	function dashboard_tipe_pegawai() 
	{
		$this->load->model('Statistik');
		$this->load->model('SatuanKerja');
		
		$set = new Statistik();
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$tempSatuanKerjaId= $reqSatuanKerjaId;
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE)
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
		)";

		if($reqSatuanKerjaId == ""){}
		else
		{
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
			{
				$reqSatuanKerjaId= "";
				if($tempSatuanKerjaId == ""){}
				else
				{
					$reqSatuanKerjaId= $tempSatuanKerjaId;
					$skerja= new SatuanKerja();
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
					unset($skerja);
					//echo $reqSatuanKerjaId;exit;
					$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				}
			}
			else
			{
				$skerja= new SatuanKerja();
				// if($this->SATUAN_KERJA_TIPE == "1")
				// {
				// 	$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
				// }
				// else
				// {
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				// }
				// echo $skerja->query;exit();
				unset($skerja);
				// echo $reqSatuanKerjaId;exit;
				$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			}
		}


		$set->selectByParamsTipePegawai($statementAktif.$statement);
		//echo $set->query;exit;
		
		$nyoba = array();
		$nyoba["data"] = array();
		$tempTotal= 0;
		while($set->nextRow())
		{

			$wadah['nama'] = $set->getfield("NAMA");
			$wadah['jumlah'] = $set->getfield("JUMLAH");
		//echo $set->getfield("NAMA");
		//echo $set->getfield("JUMLAH");

		    $tempTotal+= $set->getField("JUMLAH");

		    array_push($nyoba["data"], $wadah);

		}
		
		$wadahtotal['nama'] = "Total ASN";
		$wadahtotal['jumlah'] = $tempTotal;

		array_push($nyoba["data"], $wadahtotal);

		echo json_encode($nyoba["data"], JSON_UNESCAPED_SLASHES);
		//echo $tempTotal;


	}

	function dashboard_eselon() 
	{
		$this->load->model('Statistik');
		$this->load->model('SatuanKerja');
		
		$result= "";
		$result= array();
		
		$set = new Statistik();
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$tempSatuanKerjaId= $reqSatuanKerjaId;
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE)
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
		)";

		if($reqSatuanKerjaId == ""){}
		else
		{
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
			{
				$reqSatuanKerjaId= "";
				if($tempSatuanKerjaId == ""){}
				else
				{
					$reqSatuanKerjaId= $tempSatuanKerjaId;
					$skerja= new SatuanKerja();
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
					unset($skerja);
					//echo $reqSatuanKerjaId;exit;
					$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				}
			}
			else
			{
				$skerja= new SatuanKerja();
				// if($this->SATUAN_KERJA_TIPE == "1")
				// {
				// 	$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
				// }
				// else
				// {
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				// }
				// echo $skerja->query;exit();
				unset($skerja);
				// echo $reqSatuanKerjaId;exit;
				$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			}
		}
		
		$set->selectByParamsEselon($statementAktif.$statement);
		//echo $set->query;exit;

		$nyoba = array();
		$nyoba["data"] = array();
		$tempTotal= 0;
		while($set->nextRow())
		{

			$wadah['nama'] = $set->getfield("NAMA");
			$wadah['jumlah'] = $set->getfield("JUMLAH");
		//echo $set->getfield("NAMA");
		//echo $set->getfield("JUMLAH");

		    $tempTotal+= $set->getField("JUMLAH");

		    array_push($nyoba["data"], $wadah);

		}
		
		$wadahtotal['nama'] = "Total ASN";
		$wadahtotal['jumlah'] = $tempTotal;

		array_push($nyoba["data"], $wadahtotal);

		echo json_encode($nyoba["data"], JSON_UNESCAPED_SLASHES);

	}

	function dashboard_pensiun() 
	{
		$this->load->model('Statistik');
		$this->load->model('SatuanKerja');
		
		$result= "";
		$result= array();
		
		$set = new Statistik();
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$tempSatuanKerjaId= $reqSatuanKerjaId;
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		$statementAktif= "EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE)
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
		)";

		if($reqSatuanKerjaId == ""){}
		else
		{
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
			{
				$reqSatuanKerjaId= "";
				if($tempSatuanKerjaId == ""){}
				else
				{
					$reqSatuanKerjaId= $tempSatuanKerjaId;
					$skerja= new SatuanKerja();
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
					unset($skerja);
					//echo $reqSatuanKerjaId;exit;
					$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				}
			}
			else
			{
				$skerja= new SatuanKerja();
				// if($this->SATUAN_KERJA_TIPE == "1")
				// {
				// 	$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
				// }
				// else
				// {
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				// }
				// echo $skerja->query;exit();
				unset($skerja);
				// echo $reqSatuanKerjaId;exit;
				$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			}
		}
		
		$set->selectByParamsPensiun($statementAktif.$statement);
		//echo $set->query;exit;

		$nyoba = array();
		$nyoba["data"] = array();
		$tempTotal= 0;
		while($set->nextRow())
		{

			$wadah['tmt_pensiun'] = $set->getfield("TMT_BULAT");
			$wadah['jumlah'] = $set->getfield("JUMLAH");
		    $tempTotal+= $set->getField("JUMLAH");
		    array_push($nyoba["data"], $wadah);

		}
		
		$wadahtotal['tmt_pensiun'] = "Total Pensiun ASN";
		$wadahtotal['jumlah'] = $tempTotal;
		array_push($nyoba["data"], $wadahtotal);

		$hasil = $nyoba["data"];

		if(count($hasil)==25){
      		//lanjut sudah sesuai
	    }
	    else {

			// cek semua isi di array, pastikan semua tmt ada
	        $a_bln  = sprintf('%02d', 1);
	        $bulan_berjalan = date("Y");

			for($x=1;$x<25;$x++){

				$tmt_berjalan = $bulan_berjalan."-".$a_bln."-01";

		        $arrayKey= in_array_column($tmt_berjalan, "tmt_pensiun", $hasil);

		        if(!empty($arrayKey)) {
		       		//tidak perlu aksi
		        }
		        else { 
		        	array_push($hasil, array("tmt_pensiun"=>$tmt_berjalan, "jumlah"=>"0"));
		        }


		        //setting a_hr, a_bl, bln_berjalan
             	if($x <= 11){
	            	$a_bln = sprintf('%02d', $a_bln+1);
	            	$bulan_berjalan = date('Y');
            	} elseif ($x == 12) {
	            	$a_bln = sprintf('%02d', 1);
	            	$bulan_berjalan = date('Y', strtotime('+1 year'));
            	} else {
	            	$a_bln = sprintf('%02d', $a_bln+1);
            		$bulan_berjalan = date('Y', strtotime('+1 year'));
            	}
	    	}
	    }

	    $sort = array_column($hasil, 'tmt_pensiun');
	    array_multisort($sort, SORT_ASC, $hasil);
	    // print_r($hasil);exit;

	    // array_multisort('tmt_pensiun', SORT_ASC, $hasil);
	    // print_r($hasil);exit;

		// $hasil = array_multisort('tmt_pensiun', SORT_ASC, $hasil);
		echo json_encode($hasil, JSON_UNESCAPED_SLASHES);

	}

}
?>