<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class talenta_2023_json extends CI_Controller {

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
		$this->LOGIN_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_ID;
		$this->LOGIN_PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID;
		$this->SATUAN_KERJA_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID;
		$this->INFO_SIPETA= $this->kauth->getInstance()->getIdentity()->INFO_SIPETA;
	}

	function sessdetil()
	{
		$this->load->library('globalrekappegawai');

		$vparam= json_decode($this->input->get("vparam"));
		// print_r($vparam);exit;

		$vfpeg= new globalrekappegawai();
		$setsessionriwayat= $vfpeg->inforiwayatdetil($vparam);
		if(!empty($setsessionriwayat))
		{
			$setsessionriwayat.= "?reqId=".$vparam->vreqid."&reqRowId=".$vparam->vreqrowid;
		}
		
		// echo $setsessionriwayat;exit;
		$this->session->unset_userdata('setsessionriwayat');
		$this->session->set_userdata('setsessionriwayat', $setsessionriwayat);
		echo "1";
	}

	function hitungulang()
	{
		$this->load->model("talent/RekapTalent2023");
		
		$set= new RekapTalent2023();
		$set->ppetapegawai();
		echo "1";
	}

	function grafik()
	{
		$this->load->model("talent/RekapTalent2023");
		$this->load->model('SatuanKerja');

		$reqSatuanKerjaId= $this->input->get('reqSatuanKerjaId');

		$arr_json= [];

		$statement= $statementdetil= "";
		if(!empty($reqSatuanKerjaId))
		{
		  $skerja= new SatuanKerja();
		  $reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
		  unset($skerja);
		}
		else
		{
		  /*$vSatuanKerjaId= "";
		  $skerja= new SatuanKerja();
		  $vSatuanKerjaId.= $skerja->getSatuanKerja(62);
		  unset($skerja);

		  $skerja= new SatuanKerja();
		  $vSatuanKerjaId.= $skerja->getSatuanKerja(66);
		  unset($skerja);

		  $skerja= new SatuanKerja();
		  $vSatuanKerjaId.= $skerja->getSatuanKerja(76);
		  unset($skerja);

		  $reqSatuanKerjaId= $vSatuanKerjaId;*/
		}

		if(!empty($reqSatuanKerjaId))
		{
			$statement.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}

		// $statement.= " AND A.PEGAWAI_ID = 8300";
		// $statement.= " AND A.PEGAWAI_ID = 11761";
		$set= new RekapTalent2023();
		$set->selectbypegawai2023(array(), -1,-1, $statement);
		// echo $set->query;exit;

		$i=0;
		while($set->nextRow())
		{
			$nilaix= $set->getField("SKOR_POTENSIAL");
			$nilaiy= $set->getField("SKOR_KINERJA");
			$arr_json[$i]= array("x" => (float)$nilaix, "y" => (float)$nilaiy, "myData" => $set->getField("NAMA_LENGKAP")."<br/>Kinerja (".round($nilaiy,2).")"."<br/>Potensi (".round($nilaix,2).")<br/><button type='button' onclick=\"getdetilindividu('".$set->getField("PEGAWAI_ID")."')\">More Details</button>");
			$i++;
		}
		echo json_encode($arr_json);
	}

	function jsonrekap()
	{
		$this->load->model("talent/RekapTalent2023");
		$this->load->library('globalrekappegawai');
		$this->load->model('SatuanKerja');
		$vfpeg= new globalrekappegawai();
		$reqInfoSipeta= $this->INFO_SIPETA;

		$reqPencarian= $this->input->get("reqPencarian");
		$reqStatusPegawaiId= $this->input->get("reqStatusPegawaiId");
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqEselonGroupId= $this->input->get("reqEselonGroupId");
		$reqTipePegawaiId= $this->input->get("reqTipePegawaiId");
		$reqGlobalValidasiCheck= $this->input->get("reqGlobalValidasiCheck");
		$arrGlobalValidasiCheck= explode(",", $reqGlobalValidasiCheck);

		$set= new RekapTalent2023();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			// echo "Sasasas"; exit;
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault); exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= "";
		if($reqSatuanKerjaId == "")
		{
			if($reqInfoSipeta == "sipeta_all"){}
			else
			{
				$tempSatuanKerjaId= $reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
			}
		}
		// untuk filter global

		// $arrparam= array("satuankerjaid"=>$reqSatuanKerjaId, "tipepegawaiid"=>$reqTipePegawaiId, "statuspegawaiid"=>$reqStatusPegawaiId, "eselongroupid"=>$reqEselonGroupId);
		// $statement= $vfpeg->getparampegawai($arrparam);
		// echo $statement;exit;

		$searchJson= "";
		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%'
				OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}

		if(!empty($reqSatuanKerjaId))
		{
		  $skerja= new SatuanKerja();
		  $reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
		  unset($skerja);
		}
		else
		{
		  /*$vSatuanKerjaId= "";
		  $skerja= new SatuanKerja();
		  $vSatuanKerjaId.= $skerja->getSatuanKerja(62);
		  unset($skerja);

		  $skerja= new SatuanKerja();
		  $vSatuanKerjaId.= $skerja->getSatuanKerja(66);
		  unset($skerja);

		  $skerja= new SatuanKerja();
		  $vSatuanKerjaId.= $skerja->getSatuanKerja(76);
		  unset($skerja);

		  $reqSatuanKerjaId= $vSatuanKerjaId;*/
		}

		if(!empty($reqSatuanKerjaId))
		{
			$statement.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}
		
		$sOrder = "";
		$set->selectbypegawai2023(array(), $dsplyRange, $dsplyStart, $statement.$searchJson);
		// echo $set->query;exit;

		$infobatasdetil= $_REQUEST['start'] + $_REQUEST['length'];
		$infonomor= 0;
		while ($set->nextRow()) 
		{
			$infocheckid= $set->getField("PEGAWAI_ID");
			$infonomor++;
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				else if( $valkey == 'NAMA_LENGKAP_NIP_BARU')
				{
					$row[$valkey]= $set->getField("NAMA_LENGKAP")."<br/>".$set->getField("NIP_BARU");
				}
				else if( $valkey == 'JABATAN_TMT_ESELON')
				{
					$row[$valkey]= $set->getField("JABATAN_RIWAYAT_NAMA")."<br/>".dateTimeToPageCheck($set->getField("JABATAN_RIWAYAT_TMT"))."<br/>".$set->getField("JABATAN_RIWAYAT_ESELON");
				}
				else if( $valkey == 'NO')
				{
					$row[$valkey] = $numb;
				}
				else{
					$row[$valkey]= $set->getField($valkey);
				}
			}
			array_push($arrinfodata, $row);
			$numb++;
		}

		// get all raw data
		$alldata = $arrinfodata;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {

			// $data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					// $data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			if(count($columnsDefault) - 2 == $column){}
			else
			{
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			}
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

	function treepilih() 
	{
		$this->load->model("SatuanKerja");
		$set = new SatuanKerja();

		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;//
		$id = isset($_POST['id']) ? $_POST['id'] : 0;//
		$result = array();
		
		$reqId= $this->input->get('reqId');
		$cekquery=  $this->input->get('c');
		//$statement = " AND A.SATKER_ID = '".$reqId."'";
		
		$sesssatuankerjaid= "";
		if($reqSatuanKerjaId == "")
		{
			$sesssatuankerjaid= $this->SATUAN_KERJA_ID;
		}

		$statementAktif= "";
		/*
		if($reqSatuanKerjaId == "")
		{
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1")
			{
				$statementAktif= " AND EXISTS(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
				)";
			}
		}
		else
		{

			$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			)";

			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
			{
				$reqSatuanKerjaId= "";
			}
		}
		// echo $statementAktif;exit();

		$reqSatuanKerjaId= "62,66,76";*/

		$reqInfoSipeta= $this->INFO_SIPETA;
		if(empty($reqInfoSipeta))
		{
			$statement.= " AND 1=2";
		}
		else
		{
			if(!empty($reqSatuanKerjaId))
			{
				$skerja= new SatuanKerja();
				$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				unset($skerja);
			}
			else
			{
				if(empty($sesssatuankerjaid))
				{
					if($reqInfoSipeta == "sipeta_all")
					{
						$statementAktif= " AND EXISTS(
							SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
							AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
						)";
					}
					else
					{
						$statement.= " AND 1=2";
					}
				}
				else
				{
					if($reqInfoSipeta == "sipeta_all")
					{
						$statementAktif= " AND EXISTS(
							SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
							AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
						)";
					}
					else
					{
						$skerja= new SatuanKerja();
						$reqSatuanKerjaId= $skerja->getSatuanKerja($sesssatuankerjaid);
						unset($skerja);
					}
				}
				
			}
		}

		$i=0;
		// echo $id;
		if ($id == 0)
		{
			$result["total"] = 0;

			if($reqSatuanKerjaId == "")
			{
				$statement.= " AND A.SATUAN_KERJA_PARENT_ID = 0";
			}
			else
			{
				$statement.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			}
			
			$tempSatuanKerjaInduk= "Pemerintah Kabupaten Jombang";
			$tempSatuanKerjaIndukInfo= "Semua Satuan Kerja";
			$i=0;
			$items[$i]['ID'] = "0";
			$items[$i]['NAMA'] = "<a onclick=\"calltreeid('', '".$tempSatuanKerjaIndukInfo."')\">".$tempSatuanKerjaInduk."</a>";
			// $items[$i]['state'] = $this->has_child("", $statement) ? 'closed' : 'open';
			$i++;

			$set->selectByParamsTreeMonitoring(array(), -1, -1, $statementAktif.$statement);
			if(!empty($cekquery))
			{
				echo $set->query;exit;
			}
			while($set->nextRow())
			{
				$items[$i]['ID'] = $set->getField("SATUAN_KERJA_ID");
				$items[$i]['NAMA'] = "<a onclick=\"calltreeid('".$set->getField("SATUAN_KERJA_ID")."', '".$set->getField("SATUAN_KERJA_NAMA_DETIL")."')\">".$set->getField("NAMA")."</a>";
				$items[$i]['state'] = $this->has_child($set->getField("SATUAN_KERJA_ID"), $statementAktif) ? 'closed' : 'open';
				$i++;
			}
			$result["rows"] = $items;
		} 
		else 
		{
			$set->selectByParamsTreeMonitoring(array("A.SATUAN_KERJA_PARENT_ID" => $id), -1, -1, $statementAktif.$statement);
			//echo $set->query;exit;
			while($set->nextRow())
			{
				$result[$i]['ID'] = $set->getField("SATUAN_KERJA_ID");
				$result[$i]['NAMA'] = "<a onclick=\"calltreeid('".$set->getField("SATUAN_KERJA_ID")."', '".$set->getField("SATUAN_KERJA_NAMA_DETIL")."')\">".$set->getField("NAMA")."</a>";
				$result[$i]['state'] = $this->has_child($set->getField("SATUAN_KERJA_ID"), $statementAktif) ? 'closed' : 'open';
				$i++;
			}
		}
		
		echo json_encode($result);
	}

	function has_child($id, $stat)
	{
		$child = new SatuanKerja();
		$child->selectByParamsTreeMonitoring(array("SATUAN_KERJA_PARENT_ID" => $id), -1,-1, $stat);
		// echo $child->query;exit;
		$child->firstRow();
		$tempId= $child->getField("SATUAN_KERJA_ID");
		//echo $child->errorMsg;exit;
		//echo $tempId;exit;
		if($tempId == "")
		return false;
		else
		return true;
		unset($child);
	}

	function jsondetil()
	{
		$this->load->model("talent/RekapTalent");
		$this->load->library('globalrekappegawai');
		$vfpeg= new globalrekappegawai();

		$reqMode= $this->input->get("reqMode");
		$reqId= $this->input->get("reqId");

		$inforeturn= "";
		if($reqMode == "jabatanriwayat")
		{
			$infolabel= "RIWAYAT JABATAN/ESELON";
			$arrparam= array("reqMode"=>$reqMode, "reqId"=>$reqId);
			$arrdetilriwayat= $vfpeg->infodetil($arrparam);
			$inforeturn= $vfpeg->ambildetilinfo($infolabel, $arrdetilriwayat);
		}
		else if($reqMode == "pendidikanriwayat")
		{
			$infolabel= "RIWAYAT PENDIDIKAN FORMAL";
			$arrparam= array("reqMode"=>$reqMode, "reqId"=>$reqId);
			$arrdetilriwayat= $vfpeg->infodetil($arrparam);
			$inforeturn= $vfpeg->ambildetilinfo($infolabel, $arrdetilriwayat);
		}
		else if($reqMode == "diklatriwayat")
		{
			$infolabel= "RIWAYAT DIKLAT/PENGEMBANGAN KOMPETENSI";
			$arrparam= array("reqMode"=>$reqMode, "reqId"=>$reqId);
			$arrdetilriwayat= $vfpeg->infodetil($arrparam);
			$inforeturn= $vfpeg->ambildetilinfo($infolabel, $arrdetilriwayat);
		}

		echo $inforeturn;
	}

	function jsonrekamjejak()
	{
		$this->load->model("talent/RekapTalent");
		$this->load->library('globalrekappegawai');
		$this->load->model('SatuanKerja');
		$vfpeg= new globalrekappegawai();

		$reqPencarian= $this->input->get("reqPencarian");
		$reqStatusPegawaiId= $this->input->get("reqStatusPegawaiId");
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqEselonGroupId= $this->input->get("reqEselonGroupId");
		$reqTipePegawaiId= $this->input->get("reqTipePegawaiId");
		$reqGlobalValidasiCheck= $this->input->get("reqGlobalValidasiCheck");
		$arrGlobalValidasiCheck= explode(",", $reqGlobalValidasiCheck);

		$set= new RekapTalent();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			// echo "Sasasas"; exit;
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault); exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= "";
		if($reqSatuanKerjaId == "")
		{
			$tempSatuanKerjaId= $reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		// untuk filter global
		// $reqSatuanKerjaId= 66;
		// $reqTipePegawaiId= 11;
		$arrparam= array("satuankerjaid"=>$reqSatuanKerjaId, "tipepegawaiid"=>$reqTipePegawaiId, "statuspegawaiid"=>$reqStatusPegawaiId, "eselongroupid"=>$reqEselonGroupId);
		$statement= $vfpeg->getparampegawai($arrparam);
		// echo $statement;exit;

		$searchJson= "";
		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%'
				OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}

  		$sOrder = "";
		$set->selectpegawai(array(), $dsplyRange, $dsplyStart, $statement.$searchJson);
		// echo $set->query;exit;

		$infolabel= "RIWAYAT JABATAN/ESELON";
		$infobatasdetil= $_REQUEST['start'] + $_REQUEST['length'];
		$infonomor= 0;
		while ($set->nextRow()) 
		{
			$infocheckid= $set->getField("PEGAWAI_ID");
			$infonomor++;
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				else if ($valkey == "CHECK")
				{
					$checked= "";
					if (in_array($infocheckid, $arrGlobalValidasiCheck))
					{
						$checked= "checked";
					}

					$row[$valkey]= "<input type='checkbox' $checked onclick='setglobalklikcheck()' class='editor-active' id='reqPilihCheck".$infocheckid."' value='".$infocheckid."' /><label for='reqPilihCheck".$infocheckid."'></label>";
				}
				else if( $valkey == 'JABATAN_TMT_ESELON')
				{
					$row[$valkey]= $set->getField("JABATAN_RIWAYAT_NAMA")."<br/>".dateTimeToPageCheck($set->getField("JABATAN_RIWAYAT_TMT"))."<br/>".$set->getField("JABATAN_RIWAYAT_ESELON");
				}
				else if( $valkey == 'REKAM_JEJAK_DETIL')
				{
					$inforeturn= "";
					if($infonomor <= $infobatasdetil)
					{

						$statementdetil= " AND A.PEGAWAI_ID = ".$infocheckid;
						$setdetil= new RekapTalent();
						$setdetil->selectjabatanriwayat(array(), -1,-1, $statementdetil);
						// echo $setdetil->query;exit;
						$arrdetilriwayat=[];
						while($setdetil->nextRow())
						{
						  $arrdata= [];
						  $arrdata["id"]= $setdetil->getField("RUMPUN_ID");
						  $arrdata["nama"]= $setdetil->getField("NAMA");
						  $arrdata["nilai"]= $setdetil->getField("NILAI_REKAM_JEJAK_HITUNG");
						  array_push($arrdetilriwayat, $arrdata);
						}

						$inforeturn= $vfpeg->ambildetilinfo($infolabel, $arrdetilriwayat);

					}
					$row[$valkey]= $inforeturn;
				}
				else if( $valkey == 'NO')
				{
					$row[$valkey] = $numb;
				}
				else
					$row[$valkey]= $set->getField($valkey);
			}
			array_push($arrinfodata, $row);
			$numb++;
		}

		// get all raw data
		$alldata = $arrinfodata;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {

			// $data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					// $data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			if(count($columnsDefault) - 2 == $column){}
			else
			{
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			}
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

	function jsonkualifikasi()
	{
		$this->load->model("talent/RekapTalent");
		$this->load->library('globalrekappegawai');
		$this->load->model('SatuanKerja');
		$vfpeg= new globalrekappegawai();

		$reqPencarian= $this->input->get("reqPencarian");
		$reqStatusPegawaiId= $this->input->get("reqStatusPegawaiId");
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqEselonGroupId= $this->input->get("reqEselonGroupId");
		$reqTipePegawaiId= $this->input->get("reqTipePegawaiId");
		$reqGlobalValidasiCheck= $this->input->get("reqGlobalValidasiCheck");
		$arrGlobalValidasiCheck= explode(",", $reqGlobalValidasiCheck);

		$set= new RekapTalent();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			// echo "Sasasas"; exit;
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault); exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= "";
		if($reqSatuanKerjaId == "")
		{
			$tempSatuanKerjaId= $reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		// untuk filter global
		// $reqSatuanKerjaId= 66;
		// $reqTipePegawaiId= 11;
		$arrparam= array("satuankerjaid"=>$reqSatuanKerjaId, "tipepegawaiid"=>$reqTipePegawaiId, "statuspegawaiid"=>$reqStatusPegawaiId, "eselongroupid"=>$reqEselonGroupId);
		$statement= $vfpeg->getparampegawai($arrparam);
		// echo $statement;exit;

		$searchJson= "";
		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%'
				OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}
		
		$sOrder = "";
		$set->selectpegawai(array(), $dsplyRange, $dsplyStart, $statement.$searchJson);
		// echo $set->query;exit;

		$infolabel= "RIWAYAT PENDIDIKAN FORMAL";
		$infobatasdetil= $_REQUEST['start'] + $_REQUEST['length'];
		$infonomor= 0;
		while ($set->nextRow()) 
		{
			$infocheckid= $set->getField("PEGAWAI_ID");
			$infonomor++;
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				// else if ($valkey == "CHECK")
				// {
				// 	$checked= "";
				// 	if (in_array($infocheckid, $arrGlobalValidasiCheck))
				// 	{
				// 		$checked= "checked";
				// 	}

				// 	$row[$valkey]= "<input type='checkbox' $checked onclick='setglobalklikcheck()' class='editor-active' id='reqPilihCheck".$infocheckid."' value='".$infocheckid."' /><label for='reqPilihCheck".$infocheckid."'></label>";
				// }
				else if( $valkey == 'JABATAN_TMT_ESELON')
				{
					$row[$valkey]= $set->getField("JABATAN_RIWAYAT_NAMA")."<br/>".dateTimeToPageCheck($set->getField("JABATAN_RIWAYAT_TMT"))."<br/>".$set->getField("JABATAN_RIWAYAT_ESELON");
				}
				
				else if( $valkey == 'NO')
				{
					$row[$valkey] = $numb;
				}
				else{
					$row[$valkey]= $set->getField($valkey);
				}
			}
			array_push($arrinfodata, $row);
			$numb++;
		}

		// get all raw data
		$alldata = $arrinfodata;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {

			// $data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					// $data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			if(count($columnsDefault) - 2 == $column){}
			else
			{
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			}
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

	function jsonkompetensi()
	{
		$this->load->model("talent/RekapTalent");
		$this->load->library('globalrekappegawai');
		$this->load->model('SatuanKerja');
		$vfpeg= new globalrekappegawai();

		$reqPencarian= $this->input->get("reqPencarian");
		$reqStatusPegawaiId= $this->input->get("reqStatusPegawaiId");
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqEselonGroupId= $this->input->get("reqEselonGroupId");
		$reqTipePegawaiId= $this->input->get("reqTipePegawaiId");
		$reqGlobalValidasiCheck= $this->input->get("reqGlobalValidasiCheck");
		$arrGlobalValidasiCheck= explode(",", $reqGlobalValidasiCheck);

		$set= new RekapTalent();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			// echo "Sasasas"; exit;
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault); exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= "";
		if($reqSatuanKerjaId == "")
		{
			$tempSatuanKerjaId= $reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		// untuk filter global
		// $reqSatuanKerjaId= 66;
		// $reqTipePegawaiId= 11;
		$arrparam= array("satuankerjaid"=>$reqSatuanKerjaId, "tipepegawaiid"=>$reqTipePegawaiId, "statuspegawaiid"=>$reqStatusPegawaiId, "eselongroupid"=>$reqEselonGroupId);
		$statement= $vfpeg->getparampegawai($arrparam);
		// echo $statement;exit;

		$searchJson= "";
		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%'
				OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}
		
		$sOrder = "";
		$set->selectpegawai(array(), $dsplyRange, $dsplyStart, $statement.$searchJson);
		// echo $set->query;exit;

		$infolabel= "RIWAYAT DIKLAT/PENGEMBANGAN KOMPETENSI";
		$infobatasdetil= $_REQUEST['start'] + $_REQUEST['length'];
		$infonomor= 0;
		while ($set->nextRow()) 
		{
			$infocheckid= $set->getField("PEGAWAI_ID");
			$infonomor++;
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				// else if ($valkey == "CHECK")
				// {
				// 	$checked= "";
				// 	if (in_array($infocheckid, $arrGlobalValidasiCheck))
				// 	{
				// 		$checked= "checked";
				// 	}

				// 	$row[$valkey]= "<input type='checkbox' $checked onclick='setglobalklikcheck()' class='editor-active' id='reqPilihCheck".$infocheckid."' value='".$infocheckid."' /><label for='reqPilihCheck".$infocheckid."'></label>";
				// }
				else if( $valkey == 'JABATAN_TMT_ESELON')
				{
					$row[$valkey]= $set->getField("JABATAN_RIWAYAT_NAMA")."<br/>".dateTimeToPageCheck($set->getField("JABATAN_RIWAYAT_TMT"))."<br/>".$set->getField("JABATAN_RIWAYAT_ESELON");
				}
				else if( $valkey == 'REKAM_JEJAK_DETIL')
				{
					$inforeturn= "";
					if($infonomor <= $infobatasdetil)
					{

						$statementdetil= " AND A.PEGAWAI_ID = ".$infocheckid;
						$setdetil= new RekapTalent();
						$setdetil->selectdiklatriwayat(array(), -1,-1, $statementdetil);
						// echo $setdetil->query;exit;
						$arrdetilriwayat=[];
						while($setdetil->nextRow())
						{
						  $arrdata= [];
						  $arrdata["id"]= $setdetil->getField("RUMPUN_ID");
						  $arrdata["nama"]= $setdetil->getField("DIKLAT_NAMA");
						  $arrdata["nilai"]= $setdetil->getField("NILAI_REKAM_JEJAK");
						  array_push($arrdetilriwayat, $arrdata);
						}

						$inforeturn= $vfpeg->ambildetilinfo($infolabel, $arrdetilriwayat);

					}
					$row[$valkey]= $inforeturn;
				}
				else if( $valkey == 'NO')
				{
					$row[$valkey] = $numb;
				}
				else
					$row[$valkey]= $set->getField($valkey);
			}
			array_push($arrinfodata, $row);
			$numb++;
		}

		// get all raw data
		$alldata = $arrinfodata;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {

			// $data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					// $data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			if(count($columnsDefault) - 2 == $column){}
			else
			{
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			}
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

	function jsonperumpunan()
	{
		$this->load->model("talent/RekapTalent");
		$this->load->library('globalrekappegawai');
		$this->load->model('SatuanKerja');
		$vfpeg= new globalrekappegawai();

		$reqPencarian= $this->input->get("reqPencarian");
		$reqStatusPegawaiId= $this->input->get("reqStatusPegawaiId");
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqEselonGroupId= $this->input->get("reqEselonGroupId");
		$reqTipePegawaiId= $this->input->get("reqTipePegawaiId");
		$reqGlobalValidasiCheck= $this->input->get("reqGlobalValidasiCheck");
		$arrGlobalValidasiCheck= explode(",", $reqGlobalValidasiCheck);

		$reqUrutkan= $this->input->get("reqUrutkan");
		$reqUrutkanVal= $this->input->get("reqUrutkanVal");

		$set= new RekapTalent();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			// echo "Sasasas"; exit;
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault); exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= "";
		if($reqSatuanKerjaId == "")
		{
			$tempSatuanKerjaId= $reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		// untuk filter global
		// $reqSatuanKerjaId= 66;
		// $reqTipePegawaiId= 11;
		$arrparam= array("satuankerjaid"=>$reqSatuanKerjaId, "tipepegawaiid"=>$reqTipePegawaiId, "statuspegawaiid"=>$reqStatusPegawaiId, "eselongroupid"=>$reqEselonGroupId);
		$statement= $vfpeg->getparampegawai($arrparam);
		// echo $statement;exit;

		$searchJson= "";
		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%'
				OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}
		
		$sOrder = "";
		if(!empty($reqUrutkan))
		{
			if($reqUrutkanVal == "desc")
				$sOrder= " ORDER BY (COALESCE(R1.NILAI,0) + COALESCE(R2.NILAI,0) + COALESCE(R3.NILAI,0)) DESC, A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.PANGKAT_RIWAYAT_TMT ASC";
			else
				$sOrder= " ORDER BY (COALESCE(R1.NILAI,0) + COALESCE(R2.NILAI,0) + COALESCE(R3.NILAI,0)) ASC, A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.PANGKAT_RIWAYAT_TMT ASC";

			$set->selectrumpunpegawai(array(), $dsplyRange, $dsplyStart, $reqUrutkan, $statement.$searchJson, $sOrder);
		}
		else
			$set->selectpegawai(array(), $dsplyRange, $dsplyStart, $statement.$searchJson);


		$searchJson= "";
		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%'
				OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}
		// echo $set->query;exit;

		$infobatasdetil= $_REQUEST['start'] + $_REQUEST['length'];
		$infonomor= 0;
		while ($set->nextRow()) 
		{
			$infocheckid= $set->getField("PEGAWAI_ID");
			$infonomor++;
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				else if ($valkey == "CHECK")
				{
					$checked= "";
					if (in_array($infocheckid, $arrGlobalValidasiCheck))
					{
						$checked= "checked";
					}

					$row[$valkey]= "<input type='checkbox' $checked onclick='setglobalklikcheck()' class='editor-active' id='reqPilihCheck".$infocheckid."' value='".$infocheckid."' /><label for='reqPilihCheck".$infocheckid."'></label>";
				}
				else if( $valkey == 'JABATAN_TMT_ESELON')
				{
					$row[$valkey]= $set->getField("JABATAN_RIWAYAT_NAMA")."<br/>".dateTimeToPageCheck($set->getField("JABATAN_RIWAYAT_TMT"))."<br/>".$set->getField("JABATAN_RIWAYAT_ESELON");
				}
				else if( $valkey == 'RUMPUN_TOTAL' || $valkey == 'REKAM_JEJAK_TOTAL' || $valkey == 'KUALIFIKASI_TOTAL' || $valkey == 'KOMPETENSI_TOTAL')
				{
					$inforeturn= "";
					if($infonomor <= $infobatasdetil)
					{
						$arrperumpunan= [];
						if($valkey == 'REKAM_JEJAK_TOTAL')
						{
							$infomode= "jabatanriwayat";
							$arrparam= array("pegawaiid"=>$infocheckid, "infomode"=>$infomode);
							$arrperumpunan= $vfpeg->getparamperumpunan($arrparam);
							$inforeturn= $vfpeg->ambildetilkodeinfo($infomode, $arrperumpunan);
						}
						else if($valkey == 'KUALIFIKASI_TOTAL')
						{
							$infomode= "pendidikanriwayat";
							$arrparam= array("pegawaiid"=>$infocheckid, "infomode"=>$infomode);
							$arrperumpunan= $vfpeg->getparamperumpunan($arrparam);
							$inforeturn= $vfpeg->ambildetilkodeinfo($infomode, $arrperumpunan);
						}
						else if($valkey == 'KOMPETENSI_TOTAL')
						{
							$infomode= "diklatriwayat";
							$arrparam= array("pegawaiid"=>$infocheckid, "infomode"=>$infomode);
							$arrperumpunan= $vfpeg->getparamperumpunan($arrparam);
							$inforeturn= $vfpeg->ambildetilkodeinfo($infomode, $arrperumpunan);
						}
						else if($valkey == 'RUMPUN_TOTAL')
						{
							$infomode= "jabatanriwayat";
							$arrparam= array("pegawaiid"=>$infocheckid, "infomode"=>$infomode);
							$arrperumpunan[$infomode]= $vfpeg->getparamperumpunan($arrparam);
							$infomode= "pendidikanriwayat";
							$arrparam= array("pegawaiid"=>$infocheckid, "infomode"=>$infomode);
							$arrperumpunan[$infomode]= $vfpeg->getparamperumpunan($arrparam);
							$infomode= "diklatriwayat";
							$arrparam= array("pegawaiid"=>$infocheckid, "infomode"=>$infomode);
							$arrperumpunan[$infomode]= $vfpeg->getparamperumpunan($arrparam);
							// print_r($arrperumpunan);exit;

							$infomode= "nilairumpun";
							$arrdetilriwayat=[];
							$inforeturn= $vfpeg->ambildetilkodeinfo($infomode, $arrperumpunan);
						}
					}
					$row[$valkey]= $inforeturn;
				}
				else if( $valkey == 'NO')
				{
					$row[$valkey] = $numb;
				}
				else
					$row[$valkey]= $set->getField($valkey);
			}
			array_push($arrinfodata, $row);
			$numb++;
		}

		// get all raw data
		$alldata = $arrinfodata;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {

			// $data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					// $data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			if(count($columnsDefault) - 2 == $column){}
			else
			{
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			}
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

	function jsonnilaiakhir()
	{
		$this->load->model("talent/RekapTalent");
		$this->load->library('globalrekappegawai');
		$this->load->model('SatuanKerja');
		$vfpeg= new globalrekappegawai();

		$reqPencarian= $this->input->get("reqPencarian");
		$reqStatusPegawaiId= $this->input->get("reqStatusPegawaiId");
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqEselonGroupId= $this->input->get("reqEselonGroupId");
		$reqTipePegawaiId= $this->input->get("reqTipePegawaiId");
		$reqGlobalValidasiCheck= $this->input->get("reqGlobalValidasiCheck");
		$arrGlobalValidasiCheck= explode(",", $reqGlobalValidasiCheck);

		$reqUrutkan= $this->input->get("reqUrutkan");
		$reqUrutkanVal= $this->input->get("reqUrutkanVal");

		$set= new RekapTalent();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			// echo "Sasasas"; exit;
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault); exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= "";
		if($reqSatuanKerjaId == "")
		{
			$tempSatuanKerjaId= $reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		// untuk filter global
		// $reqSatuanKerjaId= 66;
		// $reqTipePegawaiId= 11;
		$arrparam= array("satuankerjaid"=>$reqSatuanKerjaId, "tipepegawaiid"=>$reqTipePegawaiId, "statuspegawaiid"=>$reqStatusPegawaiId, "eselongroupid"=>$reqEselonGroupId);
		$statement= $vfpeg->getparampegawai($arrparam);
		// $statement.= " AND A.PEGAWAI_ID = 8300";
		// echo $statement;exit;

		$searchJson= "";
		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%'
				OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}
		
		$sOrder = "";
		if(!empty($reqUrutkan))
		{
			if($reqUrutkanVal == "desc")
				$sOrder= " ORDER BY COALESCE(R.NILAI,0) DESC, A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.PANGKAT_RIWAYAT_TMT ASC";
			else
				$sOrder= " ORDER BY COALESCE(R.NILAI,0) ASC, A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.PANGKAT_RIWAYAT_TMT ASC";

			$set->selectrumpunnilaiakhir(array(), $dsplyRange, $dsplyStart, $reqUrutkan, $statement.$searchJson, $sOrder);
		}
		else
			$set->selectpegawai(array(), $dsplyRange, $dsplyStart, $statement.$searchJson);


		$searchJson= "";
		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%'
				OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}
		// echo $set->query;exit;

		$infobatasdetil= $_REQUEST['start'] + $_REQUEST['length'];
		$infonomor= 0;
		while ($set->nextRow()) 
		{
			$infocheckid= $set->getField("PEGAWAI_ID");
			$infonomor++;
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				else if ($valkey == "CHECK")
				{
					$checked= "";
					if (in_array($infocheckid, $arrGlobalValidasiCheck))
					{
						$checked= "checked";
					}

					$row[$valkey]= "<input type='checkbox' $checked onclick='setglobalklikcheck()' class='editor-active' id='reqPilihCheck".$infocheckid."' value='".$infocheckid."' /><label for='reqPilihCheck".$infocheckid."'></label>";
				}
				else if( $valkey == 'JABATAN_TMT_ESELON')
				{
					$row[$valkey]= $set->getField("JABATAN_RIWAYAT_NAMA")."<br/>".dateTimeToPageCheck($set->getField("JABATAN_RIWAYAT_TMT"))."<br/>".$set->getField("JABATAN_RIWAYAT_ESELON");
				}
				else if( $valkey == 'NILAI_AKHIR' || $valkey == 'KOMPETENSI_TOTAL' || $valkey == 'KINERJA_TOTAL' || $valkey == 'FAKTOR_KOREKSI_TOTAL' || $valkey == 'NILAI_RUMPUN_TOTAL')
				{
					$inforeturn= "";
					if($infonomor <= $infobatasdetil)
					{
						$arrperumpunan= [];
						if($valkey == 'KOMPETENSI_TOTAL')
						{
							$infomode= "kompetensi";
							$arrparam= array("pegawaiid"=>$infocheckid, "infomode"=>$infomode);
							$arrperumpunan= $vfpeg->getparamperumpunan($arrparam);
							if(!empty($arrperumpunan))
							{
								// print_r($arrperumpunan);exit;
								// print_r($arrperumpunan[0]);exit;
								$inforeturn= $arrperumpunan[0]["nilai"];
							}
							else
								$inforeturn= "0";
						}
						else if($valkey == 'KINERJA_TOTAL')
						{
							$infomode= "kinerja";
							$arrparam= array("pegawaiid"=>$infocheckid, "infomode"=>$infomode);
							$arrperumpunan= $vfpeg->getparamperumpunan($arrparam);
							if(!empty($arrperumpunan))
							{
								// print_r($arrperumpunan);exit;
								// print_r($arrperumpunan[0]);exit;
								$inforeturn= $arrperumpunan[0]["nilai"];
							}
							else
								$inforeturn= "0";
						}
						else if($valkey == 'FAKTOR_KOREKSI_TOTAL')
						{
							$infomode= "faktorkoreksi";
							$arrparam= array("pegawaiid"=>$infocheckid, "infomode"=>$infomode);
							$arrperumpunan= $vfpeg->getparamperumpunan($arrparam);
							$inforeturn= $vfpeg->ambildetilkodeinfo($infomode, $arrperumpunan);
						}
						else if($valkey == 'NILAI_RUMPUN_TOTAL')
						{
							$infomode= "jabatanriwayat";
							$arrparam= array("pegawaiid"=>$infocheckid, "infomode"=>$infomode);
							$arrperumpunan[$infomode]= $vfpeg->getparamperumpunan($arrparam);
							$infomode= "pendidikanriwayat";
							$arrparam= array("pegawaiid"=>$infocheckid, "infomode"=>$infomode);
							$arrperumpunan[$infomode]= $vfpeg->getparamperumpunan($arrparam);
							$infomode= "diklatriwayat";
							$arrparam= array("pegawaiid"=>$infocheckid, "infomode"=>$infomode);
							$arrperumpunan[$infomode]= $vfpeg->getparamperumpunan($arrparam);
							// print_r($arrperumpunan);exit;

							$infomode= "nilairumpun";
							$arrdetilriwayat=[];
							$inforeturn= $vfpeg->ambildetilkodeinfo($infomode, $arrperumpunan);
						}
						else if($valkey == 'NILAI_AKHIR')
						{
							$infomode= "nilaiakhir";
							$arrparam= array("pegawaiid"=>$infocheckid, "infomode"=>$infomode);
							$arrperumpunan= $vfpeg->getparamperumpunan($arrparam);
							$arrdetilriwayat=[];
							$inforeturn= $vfpeg->ambildetilkodeinfo($infomode, $arrperumpunan);
							// echo $inforeturn;exit;
						}
					}
					$row[$valkey]= $inforeturn;
				}
				else if( $valkey == 'NO')
				{
					$row[$valkey] = $numb;
				}
				else
					$row[$valkey]= $set->getField($valkey);
			}
			array_push($arrinfodata, $row);
			$numb++;
		}

		// get all raw data
		$alldata = $arrinfodata;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {

			// $data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					// $data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			if(count($columnsDefault) - 2 == $column){}
			else
			{
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			}
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

	function jsonpetapegawai()
	{
		$this->load->model("talent/RekapTalent");
		$this->load->library('globalrekappegawai');
		$this->load->model('SatuanKerja');
		$vfpeg= new globalrekappegawai();

		$reqPencarian= $this->input->get("reqPencarian");
		$reqKotakRumpun= $this->input->get("reqKotakRumpun");
		$reqStatusPegawaiId= $this->input->get("reqStatusPegawaiId");
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqEselonGroupId= $this->input->get("reqEselonGroupId");
		$reqTipePegawaiId= $this->input->get("reqTipePegawaiId");
		$reqGlobalValidasiCheck= $this->input->get("reqGlobalValidasiCheck");
		$arrGlobalValidasiCheck= explode(",", $reqGlobalValidasiCheck);

		$reqUrutkan= $this->input->get("reqUrutkan");
		$reqUrutkanVal= $this->input->get("reqUrutkanVal");

		$set= new RekapTalent();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			// echo "Sasasas"; exit;
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault); exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= "";
		if($reqSatuanKerjaId == "")
		{
			$tempSatuanKerjaId= $reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		// untuk filter global
		// $reqSatuanKerjaId= 66;
		// $reqTipePegawaiId= 11;
		$arrparam= array("satuankerjaid"=>$reqSatuanKerjaId, "tipepegawaiid"=>$reqTipePegawaiId, "statuspegawaiid"=>$reqStatusPegawaiId, "eselongroupid"=>$reqEselonGroupId);
		$statement= $vfpeg->getparampegawai($arrparam);
		// $statement.= " AND A.PEGAWAI_ID = 8300";
		// echo $statement;exit;

		$searchJson= "";
		if(!empty($reqPencarian))
		{
			// $searchJson= " 
			// AND 
			// (
			// 	UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%'
			// 	OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($reqPencarian)."%'
			// )";
		}

		if(!empty($reqKotakRumpun))
		{
			$statement.= " AND KOTAK_RUMPUN = ".$reqKotakRumpun;
		}
		
		$sOrder = "";
		$set->selectpetatalent(array(), $dsplyRange, $dsplyStart, $statement.$searchJson);

		// $reqKotakRumpun
		// echo $set->query;exit;

		$infobatasdetil= $_REQUEST['start'] + $_REQUEST['length'];
		$infonomor= 0;
		while ($set->nextRow()) 
		{
			$infocheckid= $set->getField("PEGAWAI_ID");
			$infonomor++;
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				else if ($valkey == "CHECK")
				{
					$checked= "";
					if (in_array($infocheckid, $arrGlobalValidasiCheck))
					{
						$checked= "checked";
					}

					$row[$valkey]= "<input type='checkbox' $checked onclick='setglobalklikcheck()' class='editor-active' id='reqPilihCheck".$infocheckid."' value='".$infocheckid."' /><label for='reqPilihCheck".$infocheckid."'></label>";
				}
				else if( $valkey == 'JABATAN_TMT_ESELON')
				{
					$row[$valkey]= $set->getField("JABATAN_RIWAYAT_NAMA")."<br/>".dateTimeToPageCheck($set->getField("JABATAN_RIWAYAT_TMT"))."<br/>".$set->getField("JABATAN_RIWAYAT_ESELON");
				}
				else if( $valkey == 'RUMPUN_INFO')
				{
					$row[$valkey]= $set->getField("RUMPUN_KETERANGAN")." (".$set->getField("RUMPUN_KODE").")";
				}
				else if( $valkey == 'NO')
				{
					$row[$valkey] = $numb;
				}
				else
					$row[$valkey]= $set->getField($valkey);
			}
			array_push($arrinfodata, $row);
			$numb++;
		}

		// get all raw data
		$alldata = $arrinfodata;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {

			// $data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					// $data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			if(count($columnsDefault) - 2 == $column){}
			else
			{
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			}
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

	function jsonpetapegawainew()
	{
		$this->load->model("talent/RekapTalent");
		$this->load->model("talent/RekapTalent2023");
		$this->load->library('globalrekappegawai');
		$this->load->model('SatuanKerja');
		$vfpeg= new globalrekappegawai();
		$reqInfoSipeta= $this->INFO_SIPETA;

		$reqPencarian= $this->input->get("reqPencarian");
		$reqKotakRumpun= $this->input->get("reqKotakRumpun");
		$reqStatusPegawaiId= $this->input->get("reqStatusPegawaiId");
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqEselonGroupId= $this->input->get("reqEselonGroupId");
		$reqTipePegawaiId= $this->input->get("reqTipePegawaiId");
		$reqGlobalValidasiCheck= $this->input->get("reqGlobalValidasiCheck");
		$arrGlobalValidasiCheck= explode(",", $reqGlobalValidasiCheck);

		if(!empty($reqSatuanKerjaId))
		{
		  $arrparam= array("satuankerjaid"=>$reqSatuanKerjaId);
		  $reqSatuanKerjaNama= $vfpeg->getsatuankerjanama($arrparam);
		}

		$reqUrutkan= $this->input->get("reqUrutkan");
		$reqUrutkanVal= $this->input->get("reqUrutkanVal");

		$set= new RekapTalent2023();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			// echo "Sasasas"; exit;
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault); exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= "";
		if($reqSatuanKerjaId == "")
		{
			if($reqInfoSipeta == "sipeta_all"){}
			else
			{
				$tempSatuanKerjaId= $reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
			}
		}
		// untuk filter global
		// $reqSatuanKerjaId= 66;
		// $reqTipePegawaiId= 11;
		$arrparam= array("satuankerjaid"=>$reqSatuanKerjaId, "tipepegawaiid"=>$reqTipePegawaiId, "statuspegawaiid"=>$reqStatusPegawaiId, "eselongroupid"=>$reqEselonGroupId);
		$statement= $vfpeg->getparampegawai($arrparam);
		// $statement.= " AND A.PEGAWAI_ID = 8300";
		// echo $statement;exit;

		$searchJson= "";
		if(!empty($reqPencarian))
		{
			// $searchJson= " 
			// AND 
			// (
			// 	UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%'
			// 	OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($reqPencarian)."%'
			// )";
		}

		if(!empty($reqKotakRumpun))
		{
			$statement.= " AND KOTAK_RUMPUN = ".$reqKotakRumpun;
		}

		if(!empty($reqSatuanKerjaId))
		{
			$statementdetil.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}

		$statement= " AND ORDER_KUADRAN = ".$reqKotakRumpun;
		
		$sOrder = "";
		// $set->selectbykuadranpegawai2023(array(), $dsplyRange, $dsplyStart, $statement.$searchJson);
		$set->selectbykuadranpegawai2023Popup(array(), -1, -1, $statementdetil, $statement);

		// $reqKotakRumpun
		// echo $set->query;exit;

		$infobatasdetil= $_REQUEST['start'] + $_REQUEST['length'];
		$infonomor= 0;
		while ($set->nextRow()) 
		{
			$infocheckid= $set->getField("PEGAWAI_ID");
			$infonomor++;
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				else if ($valkey == "CHECK")
				{
					$checked= "";
					if (in_array($infocheckid, $arrGlobalValidasiCheck))
					{
						$checked= "checked";
					}

					$row[$valkey]= "<input type='checkbox' $checked onclick='setglobalklikcheck()' class='editor-active' id='reqPilihCheck".$infocheckid."' value='".$infocheckid."' /><label for='reqPilihCheck".$infocheckid."'></label>";
				}
				else if( $valkey == 'JABATAN_TMT_ESELON')
				{
					$row[$valkey]= $set->getField("JABATAN_RIWAYAT_NAMA")."<br/>".dateTimeToPageCheck($set->getField("JABATAN_RIWAYAT_TMT"))."<br/>".$set->getField("JABATAN_RIWAYAT_ESELON");
				}
				else if( $valkey == 'RUMPUN_INFO')
				{
					$row[$valkey]= $set->getField("RUMPUN_KETERANGAN")." (".$set->getField("RUMPUN_KODE").")";
				}
				else if( $valkey == 'NO')
				{
					$row[$valkey] = $numb;
				}
				else
					$row[$valkey]= $set->getField($valkey);
			}
			array_push($arrinfodata, $row);
			$numb++;
		}

		// get all raw data
		$alldata = $arrinfodata;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {

			// $data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					// $data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			if(count($columnsDefault) - 2 == $column){}
			else
			{
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			}
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

}
?>