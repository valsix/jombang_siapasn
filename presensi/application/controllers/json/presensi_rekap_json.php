<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/class-list-util.php");
include_once("functions/class-list-util-serverside.php");

class presensi_rekap_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// trow to unauthenticated page!
			redirect('login');
		}		
		
		// $this->db->query("SET DATESTYLE TO PostgreSQL,European;");  
		$this->USER_GROUP= $this->kauth->getInstance()->getIdentity()->USER_GROUP;
		$this->LOGIN_USER= $this->kauth->getInstance()->getIdentity()->LOGIN_USER;
		$this->LOGIN_LEVEL= $this->kauth->getInstance()->getIdentity()->LOGIN_LEVEL;
		$this->SATUAN_KERJA_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID;
		$this->SATUAN_KERJA_TIPE= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_TIPE;
		$this->SATUAN_KERJA_BKD_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_BKD_ID;
		$this->LOGIN_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_ID;
		$this->LOGIN_PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID;
		$this->STATUS_SATUAN_KERJA_BKD= $this->kauth->getInstance()->getIdentity()->STATUS_SATUAN_KERJA_BKD;
		$this->STATUS_KHUSUS_DINAS= $this->kauth->getInstance()->getIdentity()->STATUS_KHUSUS_DINAS;
	}

	function absensidetil()
	{
		ini_set('memory_limit', -1);
		ini_set('max_execution_time', -1);
		
		$this->load->model('main/PresensiRekap');
		$this->load->model('main/SatuanKerja');

		$set= new PresensiRekap;

		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqStatus= $this->input->get("reqStatus");
		$reqBulan= $this->input->get("reqBulan");
		$reqTahun= $this->input->get("reqTahun");
		$reqPeriode= $reqBulan.$reqTahun;
		$reqPencarian= $this->input->get("reqPencarian");
		$reqKhususDinas= $this->input->get("reqKhususDinas");

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
				UPPER(P.NIP_BARU) LIKE '%".strtoupper($reqPencarian)."%'
				OR UPPER(P.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%'
			)";
		}

		if($reqSatuanKerjaId == "")
		{
			$tempSatuanKerjaId= $reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}

		$satuankerjakondisi= "";
		if($reqSatuanKerjaId == "")
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND P.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
		}
		else
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND P.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
			
			$infostatuskhususdinas= $this->STATUS_KHUSUS_DINAS;
			$skerja= new SatuanKerja();
			if($reqKhususDinas == "1" && $infostatuskhususdinas == "1")
			{
				$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
			}
			else
			{
				$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
			}
			// echo $skerja->query;exit();
			unset($skerja);
			// echo $reqSatuanKerjaId;exit;
			$satuankerjakondisi.= " AND P.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}
		// echo $satuankerjakondisi;exit();
		$statement= $satuankerjakondisi;

		// $statement.= " AND P.PEGAWAI_ID = 8300";

		if(empty($reqStatus))
		{
			$statement.= " AND P.STATUS_PEGAWAI_ID IN (1,2,6)";
		}
		else
		{
			if($reqStatus == "xxx"){}
			else
			{
				$statement.= " AND 
				(
					P.STATUS_PEGAWAI_ID IN (3,4,5)
					AND 
					EXISTS
					(
						SELECT 1
						FROM
						(
							SELECT PEGAWAI_STATUS_ID
							FROM pegawai_status
							WHERE TMT >= TO_DATE('01".$reqPeriode."', 'DDMMYYYY')
						) XXX WHERE P.PEGAWAI_STATUS_ID = XXX.PEGAWAI_STATUS_ID
					)
				)
				";
			}
		}
		// echo $statement;exit;

  		// $sOrder = "ORDER BY P.ESELON_ID ASC, P.PANGKAT_ID DESC, P.PANGKAT_RIWAYAT_TMT ASC";
  		if(empty($sOrder))
		{
			$sOrder = " ORDER BY PA.JAM DESC";
		}
		$set->selectByParamsMPresensiAbsen(array(), $dsplyRange, $dsplyStart, $reqPeriode, $searchJson.$statement, $sOrder);
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
				else if( $valkey == 'TANGGAL')
				{
					$row[$valkey]= dateToPageCheck($set->getField($valkey));
				}
				else if( $valkey == 'TANGGAL_DATA_MASUK')
				{
					$row[$valkey]= dateTimeToPageCheck($set->getField($valkey));
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