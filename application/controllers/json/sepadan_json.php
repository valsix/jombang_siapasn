<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class sepadan_json extends CI_Controller {

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

	function sessdetil()
	{
		$mode= $this->input->get("m");
		$reqId= $this->input->get("reqId");
		$reqRowId= $this->input->get("reqRowId");
		$reqTahun= $this->input->get("reqTahun");
		$reqJenisJabatanId= $this->input->get("reqJenisJabatanId");

		$arrparam= array("mode"=>$mode, "reqId"=>$reqId, "reqRowId"=>$reqRowId, "reqJenisJabatanId"=>$reqJenisJabatanId, "reqTahun"=>$reqTahun);
		$setsessionriwayat= $this->inforiwayatdetil($arrparam);
		if(!empty($setsessionriwayat))
		{
			$setsessionriwayat.= "?reqId=".$reqId."&reqRowId=".$reqRowId;
		}
		
		// echo $setsessionriwayat;exit;
		$this->session->unset_userdata('setsessionriwayat');
		$this->session->set_userdata('setsessionriwayat', $setsessionriwayat);
		echo "1";
	}

	function inforiwayatdetil($vparam)
	{
		// print_r($vparam);exit;
		$infomode= $vparam["mode"];

		$vriwayat= "";
		if($infomode == "jabatanriwayat")
		{
			$jenisjabatanid= $vparam["reqJenisJabatanId"];

			if($jenisjabatanid == "1")
				$vriwayat= "pegawai_add_jabatan_struktural_data";
			else if($jenisjabatanid == "2")
				$vriwayat= "pegawai_add_jabatan_fungsional_data";
			else if($jenisjabatanid == "3")
				$vriwayat= "pegawai_add_jabatan_tertentu_data";
		}
		else if($infomode == "kinerja")
		{
			$tahun= $vparam["reqTahun"];

			if($tahun == "2022")
				$vriwayat= "pegawai_add_skp22_data";
			else if($tahun == "2023")
				$vriwayat= "pegawai_add_skp23_data";
		}
		else if($infomode == "pendidikanriwayat")
		{
			$vriwayat= "pegawai_add_pendidikan_data";
		}
		else if($infomode == "diklatkursus")
		{
			$vriwayat= "pegawai_add_diklat_kursus_data";
		}
		else if($infomode == "diklatstruktural")
		{
			$vriwayat= "pegawai_add_diklat_struktural_data";
		}
		else if($infomode == "hukuman")
		{
			$vriwayat= "pegawai_add_hukuman_data";
		}

		return $vriwayat;
	}

	function jsonjabatan()
	{
		$this->load->model("base/Sepadan");
		$this->load->library('globalrekappegawai');
		$this->load->model('SatuanKerja');
		$vfpeg= new globalrekappegawai();

		$reqPencarian= $this->input->get("reqPencarian");
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqJenisJabatan= $this->input->get("reqJenisJabatan");
		$reqTahun= $this->input->get("reqTahun");
		$reqGlobalValidasiCheck= $this->input->get("reqGlobalValidasiCheck");
		$arrGlobalValidasiCheck= explode(",", $reqGlobalValidasiCheck);

		$set= new Sepadan();

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

		$arrparam= array("satuankerjaid"=>$reqSatuanKerjaId);
		$statement= $vfpeg->getparampegawai($arrparam);

		if(!empty($reqJenisJabatan))
		{
			$statement.= " AND A1.JENIS_JABATAN_ID = '".$reqJenisJabatan."'";
		}

		if(!empty($reqTahun))
		{
			$statement.= " AND TO_CHAR(A1.TMT_JABATAN, 'YYYY') = '".$reqTahun."'";
		}
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
		$set->selectjabatan(array(), $dsplyRange, $dsplyStart, $statement.$searchJson);
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
					$row[$valkey]= $set->getField("TMT_JABATAN");
					// $row[$valkey]= dateTimeToPageCheck($set->getField("TMT_JABATAN"));
					// strtotime(
				}
				else if( $valkey == 'TMT_JABATAN')
				{
					$row[$valkey]= dateTimeToPageCheck($set->getField("TMT_JABATAN"));
					// $row[$valkey]= dateToPageCheck(dateTimeToPageCheck($set->getField("TMT_JABATAN")));
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
			if(count($columnsDefault) - 4 == $column){}
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

	function jsonkinerja()
	{
		$this->load->model("base/Sepadan");
		$this->load->library('globalrekappegawai');
		$this->load->model('SatuanKerja');
		$vfpeg= new globalrekappegawai();

		$reqPencarian= $this->input->get("reqPencarian");
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqTahun= $this->input->get("reqTahun");
		$reqGlobalValidasiCheck= $this->input->get("reqGlobalValidasiCheck");
		$arrGlobalValidasiCheck= explode(",", $reqGlobalValidasiCheck);

		$set= new Sepadan();

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

		$arrparam= array("satuankerjaid"=>$reqSatuanKerjaId);
		$statement= $vfpeg->getparampegawai($arrparam);

		if(!empty($reqTahun))
		{
			$statement.= " AND A1.TAHUN = '".$reqTahun."'";
		}
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
		$set->selectkinerja(array(), $dsplyRange, $dsplyStart, $statement.$searchJson);
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
					$row[$valkey]= $set->getField("TAHUN");
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
			if(count($columnsDefault) - 4 == $column){}
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

	function jsondiklatkursus()
	{
		$this->load->model("base/Sepadan");
		$this->load->library('globalrekappegawai');
		$this->load->model('SatuanKerja');
		$vfpeg= new globalrekappegawai();

		$reqPencarian= $this->input->get("reqPencarian");
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqTipeKursus= $this->input->get("reqTipeKursus");
		$reqTahun= $this->input->get("reqTahun");
		$reqGlobalValidasiCheck= $this->input->get("reqGlobalValidasiCheck");
		$arrGlobalValidasiCheck= explode(",", $reqGlobalValidasiCheck);

		$set= new Sepadan();

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

		$arrparam= array("satuankerjaid"=>$reqSatuanKerjaId);
		$statement= $vfpeg->getparampegawai($arrparam);

		if(!empty($reqTahun))
		{
			$statement.= " AND A1.TAHUN = '".$reqTahun."'";
		}

		if(!empty($reqTipeKursus))
		{
			$statement.= " AND A1.TIPE_KURSUS_ID = '".$reqTipeKursus."'";
		}
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
		$set->selectdiklatkursus(array(), $dsplyRange, $dsplyStart, $statement.$searchJson);
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
					$row[$valkey]= $set->getField("TAHUN");
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
			if(count($columnsDefault) - 3 == $column){}
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

	function jsondiklatstruktural()
	{
		$this->load->model("base/Sepadan");
		$this->load->library('globalrekappegawai');
		$this->load->model('SatuanKerja');
		$vfpeg= new globalrekappegawai();

		$reqPencarian= $this->input->get("reqPencarian");
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqDiklat= $this->input->get("reqDiklat");
		$reqTahun= $this->input->get("reqTahun");
		$reqGlobalValidasiCheck= $this->input->get("reqGlobalValidasiCheck");
		$arrGlobalValidasiCheck= explode(",", $reqGlobalValidasiCheck);

		$set= new Sepadan();

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

		$arrparam= array("satuankerjaid"=>$reqSatuanKerjaId);
		$statement= $vfpeg->getparampegawai($arrparam);

		if(!empty($reqTahun))
		{
			$statement.= " AND A1.TAHUN = '".$reqTahun."'";
		}

		if(!empty($reqDiklat))
		{
			$statement.= " AND A1.DIKLAT_ID = '".$reqDiklat."'";
		}
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
		$set->selectdiklatstruktural(array(), $dsplyRange, $dsplyStart, $statement.$searchJson);
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
					$row[$valkey]= $set->getField("TAHUN");
				}
				else if( $valkey == 'TMT_JABATAN')
				{
					$row[$valkey]= dateTimeToPageCheck($set->getField("TMT_JABATAN"));
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
			if(count($columnsDefault) - 3 == $column){}
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

	function jsonhukuman()
	{
		$this->load->model("base/Sepadan");
		$this->load->library('globalrekappegawai');
		$this->load->model('SatuanKerja');
		$vfpeg= new globalrekappegawai();

		$reqPencarian= $this->input->get("reqPencarian");
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqPeraturanId= $this->input->get("reqPeraturanId");
		$reqTingkatHukumanId= $this->input->get("reqTingkatHukumanId");
		$reqJenisHukumanId= $this->input->get("reqJenisHukumanId");
		$reqTahun= $this->input->get("reqTahun");
		$reqGlobalValidasiCheck= $this->input->get("reqGlobalValidasiCheck");
		$arrGlobalValidasiCheck= explode(",", $reqGlobalValidasiCheck);

		$set= new Sepadan();

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

		$arrparam= array("satuankerjaid"=>$reqSatuanKerjaId);
		$statement= $vfpeg->getparampegawai($arrparam);

		if(!empty($reqTahun))
		{
			$statement.= " AND TO_CHAR(A1.TMT_SK, 'YYYY') = '".$reqTahun."'";
		}

		if(!empty($reqPeraturanId))
		{
			$statement.= " AND A1.PERATURAN_ID = '".$reqPeraturanId."'";
		}

		if(!empty($reqTingkatHukumanId))
		{
			$statement.= " AND A1.TINGKAT_HUKUMAN_ID = '".$reqTingkatHukumanId."'";
		}

		if(!empty($reqJenisHukumanId))
		{
			$statement.= " AND A1.JENIS_HUKUMAN_ID = '".$reqJenisHukumanId."'";
		}
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
		$set->selecthukuman(array(), $dsplyRange, $dsplyStart, $statement.$searchJson);
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
					$row[$valkey]= $set->getField("TMT_SK");
				}
				else if( $valkey == 'TMT_SK' || $valkey == 'TANGGAL_AKHIR')
				{
					$row[$valkey]= dateToPageCheck($set->getField($valkey));
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
			if(count($columnsDefault) - 3 == $column){}
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