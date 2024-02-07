<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class rumpun_nilai_json extends CI_Controller {

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
	}

	function jsonpendidikan()
	{
		$this->load->model("base/RumpunNilai");

		$reqPencarian= $this->input->get("reqPencarian");
		$reqGlobalValidasiCheck= $this->input->get("reqGlobalValidasiCheck");
		$arrGlobalValidasiCheck= explode(",", $reqGlobalValidasiCheck);

		$set= new RumpunNilai();

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

		$searchJson= "";
		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A.NAMA) LIKE '%".strtoupper($reqPencarian)."%'
				OR UPPER(A.NAMA) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}
		
		$sOrder = " ORDER BY A.PENDIDIKAN_ID DESC";
		$set->selectrumpunpendidikan(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
		// echo $set->query;exit;

		$infobatasdetil= $_REQUEST['start'] + $_REQUEST['length'];
		$infonomor= 0;
		while ($set->nextRow()) 
		{
			$infocheckid= $set->getField("PENDIDIKAN_ID");
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

	function addpendidikan()
	{
		$this->load->model("base/RumpunNilai");
		
		$reqId= $this->input->post("reqId");
		$reqInfoMode= $this->input->post("reqInfoMode");
		$reqRumpunId= $this->input->post("reqRumpunId");
		$reqPermenId= $this->input->post("reqPermenId");
		$reqNilai= $this->input->post("reqNilai");
		$reqInfoId= $this->input->post("reqInfoId");

		foreach ($reqInfoId as $k=>$v)
		{
			$vnilai= $reqNilai[$k];
			$statement= " AND INFOMODE = '".$reqInfoMode."' AND A.PERMEN_ID = ".$reqPermenId." AND A.RUMPUN_ID = ".$reqRumpunId." AND A.INFOID = ".$v;
			
			$setdetil= new RumpunNilai();
			$setdetil->selectparams(array(), -1,-1, $statement);
			// echo $setdetil->query;exit;
			if(!$setdetil->firstRow())
				$modesimpan= "insert";
			else
				$modesimpan= "update";

			// echo $modesimpan;exit;

			$setdetil= new RumpunNilai();
			$setdetil->setField("INFOMODE", $reqInfoMode);
			$setdetil->setField("PERMEN_ID", $reqPermenId);
			$setdetil->setField("RUMPUN_ID", $reqRumpunId);
			$setdetil->setField("INFOID", $v);
			$setdetil->setField("NILAI", $vnilai);

			if($modesimpan == "insert")
				$setdetil->insert();
			else
				$setdetil->update();

			$setdetil= new RumpunNilai();
			$setdetil->setField("PENDIDIKAN_ID", $v);
			$setdetil->setField("NILAI", $vnilai);
			$setdetil->updatependidikanjurusan();
		}

		echo "-Data berhasil disimpan.";
	}
	
	function jsonjurusan()
	{
		$this->load->model("base/RumpunNilai");

		$reqPendidikanId= $this->input->get("reqPendidikanId");
		$reqPencarian= $this->input->get("reqPencarian");
		$reqGlobalValidasiCheck= $this->input->get("reqGlobalValidasiCheck");
		$arrGlobalValidasiCheck= explode(",", $reqGlobalValidasiCheck);

		$set= new RumpunNilai();

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

		$searchJson= "";
		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(B.NAMA) LIKE '%".strtoupper($reqPencarian)."%'
				OR UPPER(B.NAMA) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}

		if(!empty($reqPendidikanId))
		{
			$statement.= " AND A.PENDIDIKAN_ID = ".$reqPendidikanId;
		}
		
		$sOrder = " ORDER BY A.PENDIDIKAN_ID DESC, B.NAMA";
		$set->selectrumpunkualifikasi(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
		// echo $set->query;exit;

		$infobatasdetil= $_REQUEST['start'] + $_REQUEST['length'];
		$infonomor= 0;
		while ($set->nextRow()) 
		{
			$infocheckid= $set->getField("PENDIDIKAN_JURUSAN_ID");
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

	function addjurusan()
	{
		$this->load->model("base/RumpunNilai");
		
		$reqId= $this->input->post("reqId");
		$reqInfoMode= $this->input->post("reqInfoMode");
		$reqRumpunId= $this->input->post("reqRumpunId");
		$reqPermenId= $this->input->post("reqPermenId");
		$reqNilai= $this->input->post("reqNilai");
		$reqInfoId= $this->input->post("reqInfoId");

		foreach ($reqInfoId as $k=>$v)
		{
			$vnilai= $reqNilai[$k];
			$vrumpunid= $reqRumpunId[$k];

			if($vnilai > 0)
			{
				$statement= " AND INFOMODE = '".$reqInfoMode."' AND A.PERMEN_ID = ".$reqPermenId." AND A.RUMPUN_ID = ".$vrumpunid." AND A.INFOID = ".$v;
				$setdetil= new RumpunNilai();
				$setdetil->selectparams(array(), -1,-1, $statement);
				// echo $setdetil->query;exit;
				if(!$setdetil->firstRow())
					$modesimpan= "insert";
				else
					$modesimpan= "update";

				// echo $modesimpan;exit;

				$setdetil= new RumpunNilai();
				$setdetil->setField("INFOMODE", $reqInfoMode);
				$setdetil->setField("PERMEN_ID", $reqPermenId);
				$setdetil->setField("RUMPUN_ID", $vrumpunid);
				$setdetil->setField("INFOID", $v);
				$setdetil->setField("NILAI", $vnilai);

				if($modesimpan == "insert")
					$setdetil->insert();
				else
					$setdetil->update();
			}
			else
			{
				$setdetil= new RumpunNilai();
				$setdetil->setField("INFOMODE", $reqInfoMode);
				$setdetil->setField("PERMEN_ID", $reqPermenId);
				$setdetil->setField("RUMPUN_ID", $vrumpunid);
				$setdetil->setField("INFOID", $v);
				$setdetil->delete();
			}
		}

		echo "-Data berhasil disimpan.";
	}

	function jsondiklatkursus()
	{
		$this->load->model("base/RumpunNilai");

		$reqPencarian= $this->input->get("reqPencarian");
		$reqGlobalValidasiCheck= $this->input->get("reqGlobalValidasiCheck");
		$arrGlobalValidasiCheck= explode(",", $reqGlobalValidasiCheck);

		$set= new RumpunNilai();

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

		$searchJson= "";
		if(!empty($reqPencarian))
		{
			$searchJson= " 
			AND 
			(
				UPPER(A.NAMA) LIKE '%".strtoupper($reqPencarian)."%'
				OR UPPER(A.NAMA) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}
		
		$sOrder = " ORDER BY A.TIPE_KURSUS_ID";
		$set->selecttipekursus(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
		// echo $set->query;exit;

		$infobatasdetil= $_REQUEST['start'] + $_REQUEST['length'];
		$infonomor= 0;
		while ($set->nextRow()) 
		{
			$infocheckid= $set->getField("TIPE_KURSUS_ID");
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

	function adddiklatkursus()
	{
		$this->load->model("base/RumpunNilai");
		
		$reqId= $this->input->post("reqId");
		$reqInfoMode= $this->input->post("reqInfoMode");
		$reqRumpunId= $this->input->post("reqRumpunId");
		$reqPermenId= $this->input->post("reqPermenId");
		$reqNilai= $this->input->post("reqNilai");
		$reqInfoId= $this->input->post("reqInfoId");

		foreach ($reqInfoId as $k=>$v)
		{
			$vnilai= $reqNilai[$k];
			$statement= " AND INFOMODE = '".$reqInfoMode."' AND A.PERMEN_ID = ".$reqPermenId." AND A.RUMPUN_ID = ".$reqRumpunId." AND A.INFOID = ".$v;
			
			$setdetil= new RumpunNilai();
			$setdetil->selectparams(array(), -1,-1, $statement);
			// echo $setdetil->query;exit;
			if(!$setdetil->firstRow())
				$modesimpan= "insert";
			else
				$modesimpan= "update";

			// echo $modesimpan;exit;

			$setdetil= new RumpunNilai();
			$setdetil->setField("INFOMODE", $reqInfoMode);
			$setdetil->setField("PERMEN_ID", $reqPermenId);
			$setdetil->setField("RUMPUN_ID", $reqRumpunId);
			$setdetil->setField("INFOID", $v);
			$setdetil->setField("NILAI", $vnilai);

			if($modesimpan == "insert")
				$setdetil->insert();
			else
				$setdetil->update();
		}

		echo "-Data berhasil disimpan.";
	}

}
?>