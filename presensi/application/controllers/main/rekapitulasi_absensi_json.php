<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class rekapitulasi_absensi_json extends CI_Controller {

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
	}	

	function proses()
	{
		$this->load->model('main/AbsensiRekap');
		$this->load->model('main/SatuanKerja');

		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqTahun= $this->input->get("reqTahun");
		$reqBulan= $this->input->get("reqBulan");
		$reqPeriode= $reqBulan.$reqTahun;

		if($reqSatuanKerjaId !== "")
		{
			$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			)";
			
			$skerja= new SatuanKerja();
			$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
			// echo $skerja->query;exit();
			unset($skerja);
			// echo $reqSatuanKerjaId;exit;
			$statement= " AND EXISTS
			(
				SELECT 1
				FROM
				(
					SELECT A.PEGAWAI_ID FROM PEGAWAI A WHERE 1=1
					AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")
				) X WHERE CAST(X.PEGAWAI_ID AS CHARACTER VARYING) = A.PEGAWAI_ID
			)";
		}

		$set= new AbsensiRekap();
		$set->getDataAbsensiPeriode($reqPeriode, setQuote($statement));
		// echo $set->query;exit();
	}

	function json() 
	{
		$this->load->model('main/AbsensiRekap');
		$this->load->model('main/SatuanKerja');

		$set= new AbsensiRekap;
		
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqTahun= $this->input->get("reqTahun");
		$reqBulan= $this->input->get("reqBulan");

		$reqPeriode= $reqBulan.$reqTahun;
		$date=$reqTahun."-".$reqBulan;
		$totalhari =  getDay(date("Y-m-t",strtotime($date)));

		$aColumns = array("NIP_BARU_NAMA_LENGKAP", "SATUAN_KERJA_NAMA");

		for($i=1; $i <= $totalhari; $i++) 
		{
			array_push($aColumns, "IN_".$i);
			array_push($aColumns, "OUT_".$i);
		}
		array_push($aColumns, "PEGAWAI_ID");
		// print_r($aColumns);exit();
		$aColumnsAlias = $aColumns;
		
		/*
		 * Ordering
		 */
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = " ORDER BY ";
			 
			//Go over all sorting cols
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				//If need to sort by current col
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[ intval( $_GET['iSortCol_'.$i] ) ];
					 
					//Determine if it is sorted asc or desc
					if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc") == 0)
					{
						$sOrder .=" asc, ";
					}else
					{
						$sOrder .=" desc, ";
					}
				}
			}
			
			//Remove the last space / comma
			$sOrder = substr_replace( $sOrder, "", -2 );
			
			//Check if there is an order by clause
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY DEPT_NAME asc, DEPT_NAME asc") || trim(strtoupper($sOrder)) == strtoupper("ORDER BY DEPT_NAME asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY B.DEPT_NAME, A.AREA_NAME";
				 
			}
		}
		 
		/*
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables.
		 */
		$sWhere = "";
		$nWhereGenearalCount = 0;
		if (isset($_GET['sSearch']))
		{
			$sWhereGenearal = $_GET['sSearch'];
		}
		else
		{
			$sWhereGenearal = '';
		}
		
		if ( $_GET['sSearch'] != "" )
		{
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ( $i=0 ; $i<count($aColumnsAlias)+1 ; $i++ )
			{
				//If current col has a search param
				if ( $_GET['bSearchable_'.$i] == "true" )
				{
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i]." LIKE '%".$_GET['sSearch']."%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		 
		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ( $i=0 ; $i<count($aColumnsAlias) ; $i++ )
		{
			if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				//If there was no where clause
				if ( $sWhere == "" )
				{
					$sWhere = "AND ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				 
				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i]." LIKE '%' || :whereSpecificParam".$sWhereSpecificArrayCount." || '%' ";
				 
				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;
				 
				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_'.$i];
				 
			}
		}
		 
		//If there is still no where clause - set a general - always true where clause
		if ( $sWhere == "" )
		{
			$sWhere = " AND 1=1";
		}
		 
		//Bind variables.
		// if ( isset( $_GET['iDisplayStart'] ))
		// {
		// 	$dsplyStart = $_GET['iDisplayStart'];
		// }
		// else{
		// 	$dsplyStart = 0;
		// }


		// if ( isset( $_GET['iDisplayLength'] ) && $_GET['iDisplayLength'] != '-1' )
		// {
		// 	$dsplyRange = $_GET['iDisplayLength'];
		// 	if ($dsplyRange > (2147483645 - intval($dsplyStart)))
		// 	{
		// 		$dsplyRange = 2147483645;
		// 	}
		// 	else

		// 	{
		// 		$dsplyRange = intval($dsplyRange);
		// 	}
		// }
		// else
		// {
		// 	$dsplyRange = 2147483645;
		// }
		
		// echo $_POST['draw']."--".$_POST['length']."--".$_POST['start'];
		if ( isset( $_POST['start'] ))
		{
			$dsplyStart = $_POST['start'];
		}
		else{
			$dsplyStart = 0;
		}

		if ( isset( $_POST['length'] ) && $_POST['length'] != '-1' )
		{
			$dsplyRange = $_POST['length'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart)))
			{
				$dsplyRange = 2147483645;
			}
			else
			{
				$dsplyRange = intval($dsplyRange);
			}
		}
		else
		{
			// $dsplyRange = 2147483645;
			$dsplyRange = 50;
		}

		// buat parstisi
		$this->setpartisi($reqPeriode);
		
		if($reqSatuanKerjaId == "")
		{
			$tempSatuanKerjaId= $reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}

		$statementAktif= "";
		if($reqSatuanKerjaId == "")
		{
			$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			)";
		}
		else
		{
			$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			)";
			
			$skerja= new SatuanKerja();
			$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
			// echo $skerja->query;exit();
			unset($skerja);
			// echo $reqSatuanKerjaId;exit;
			$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}
		// echo $statement;exit();

		$searchJson = " AND (UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $set->getCountByParamsRekap(array(), $reqPeriode, $statement);
		//echo $allRecord;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsRekap(array(), $reqPeriode, $searchJson.$statement);
		
		$sOrder= " ORDER BY A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.TMT_PANGKAT ASC";
		$set->selectByParamsRekap(array(), $dsplyRange, $dsplyStart, $reqPeriode, $searchJson.$statement, $sOrder);
		// echo $set->query;exit;
		// echo $set->errorMsg;exit;

		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);
		
		while($set->nextRow())		
		{
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{
				if($aColumns[$i] == "NIP_BARU_NAMA_LENGKAP")
				{
					$row[] = $set->getField("NIP_BARU")."<br/>".$set->getField("NAMA_LENGKAP")."<br/>".$set->getField("PEGAWAI_ID");
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}

	function rekap_json() 
	{
		$this->load->model('main/AbsensiRekap');
		$this->load->model('main/SatuanKerja');

		$set= new AbsensiRekap;
		
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqTahun= $this->input->get("reqTahun");
		$reqBulan= $this->input->get("reqBulan");

		$reqPeriode= $reqBulan.$reqTahun;
		$date=$reqTahun."-".$reqBulan;
		$totalhari =  getDay(date("Y-m-t",strtotime($date)));

		$aColumns = array("NIP_BARU_NAMA_LENGKAP", "SATUAN_KERJA_NAMA");

		for($i=1; $i <= $totalhari; $i++) 
		{
			array_push($aColumns, "IN_".$i);
			array_push($aColumns, "OUT_".$i);
			array_push($aColumns, "JJ_".$i);
		}
		array_push($aColumns, "PEGAWAI_ID");
		// print_r($aColumns);exit();
		$aColumnsAlias = $aColumns;
		
		/*
		 * Ordering
		 */
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = " ORDER BY ";
			 
			//Go over all sorting cols
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				//If need to sort by current col
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					//Add to the order by clause
					$sOrder .= $aColumnsAlias[ intval( $_GET['iSortCol_'.$i] ) ];
					 
					//Determine if it is sorted asc or desc
					if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc") == 0)
					{
						$sOrder .=" asc, ";
					}else
					{
						$sOrder .=" desc, ";
					}
				}
			}
			
			//Remove the last space / comma
			$sOrder = substr_replace( $sOrder, "", -2 );
			
			//Check if there is an order by clause
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY DEPT_NAME asc, DEPT_NAME asc") || trim(strtoupper($sOrder)) == strtoupper("ORDER BY DEPT_NAME asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY B.DEPT_NAME, A.AREA_NAME";
				 
			}
		}
		 
		/*
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables.
		 */
		$sWhere = "";
		$nWhereGenearalCount = 0;
		if (isset($_GET['sSearch']))
		{
			$sWhereGenearal = $_GET['sSearch'];
		}
		else
		{
			$sWhereGenearal = '';
		}
		
		if ( $_GET['sSearch'] != "" )
		{
			//Set a default where clause in order for the where clause not to fail
			//in cases where there are no searchable cols at all.
			$sWhere = " AND (";
			for ( $i=0 ; $i<count($aColumnsAlias)+1 ; $i++ )
			{
				//If current col has a search param
				if ( $_GET['bSearchable_'.$i] == "true" )
				{
					//Add the search to the where clause
					$sWhere .= $aColumnsAlias[$i]." LIKE '%".$_GET['sSearch']."%' OR ";
					$nWhereGenearalCount += 1;
				}
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		 
		/* Individual column filtering */
		$sWhereSpecificArray = array();
		$sWhereSpecificArrayCount = 0;
		for ( $i=0 ; $i<count($aColumnsAlias) ; $i++ )
		{
			if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				//If there was no where clause
				if ( $sWhere == "" )
				{
					$sWhere = "AND ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				 
				//Add the clause of the specific col to the where clause
				$sWhere .= $aColumnsAlias[$i]." LIKE '%' || :whereSpecificParam".$sWhereSpecificArrayCount." || '%' ";
				 
				//Inc sWhereSpecificArrayCount. It is needed for the bind var.
				//We could just do count($sWhereSpecificArray) - but that would be less efficient.
				$sWhereSpecificArrayCount++;
				 
				//Add current search param to the array for later use (binding).
				$sWhereSpecificArray[] =  $_GET['sSearch_'.$i];
				 
			}
		}
		 
		//If there is still no where clause - set a general - always true where clause
		if ( $sWhere == "" )
		{
			$sWhere = " AND 1=1";
		}
		 
		//Bind variables.
		// if ( isset( $_GET['iDisplayStart'] ))
		// {
		// 	$dsplyStart = $_GET['iDisplayStart'];
		// }
		// else{
		// 	$dsplyStart = 0;
		// }


		// if ( isset( $_GET['iDisplayLength'] ) && $_GET['iDisplayLength'] != '-1' )
		// {
		// 	$dsplyRange = $_GET['iDisplayLength'];
		// 	if ($dsplyRange > (2147483645 - intval($dsplyStart)))
		// 	{
		// 		$dsplyRange = 2147483645;
		// 	}
		// 	else

		// 	{
		// 		$dsplyRange = intval($dsplyRange);
		// 	}
		// }
		// else
		// {
		// 	$dsplyRange = 2147483645;
		// }
		
		// echo $_POST['draw']."--".$_POST['length']."--".$_POST['start'];
		if ( isset( $_POST['start'] ))
		{
			$dsplyStart = $_POST['start'];
		}
		else{
			$dsplyStart = 0;
		}

		if ( isset( $_POST['length'] ) && $_POST['length'] != '-1' )
		{
			$dsplyRange = $_POST['length'];
			if ($dsplyRange > (2147483645 - intval($dsplyStart)))
			{
				$dsplyRange = 2147483645;
			}
			else
			{
				$dsplyRange = intval($dsplyRange);
			}
		}
		else
		{
			// $dsplyRange = 2147483645;
			$dsplyRange = 50;
		}
		
		if($reqSatuanKerjaId == "")
		{
			$tempSatuanKerjaId= $reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}

		// buat parstisi
		$this->setpartisi($reqPeriode);

		$statementAktif= "";
		if($reqSatuanKerjaId == "")
		{
			$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			)";
		}
		else
		{
			$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			)";
			
			$skerja= new SatuanKerja();
			$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
			// echo $skerja->query;exit();
			unset($skerja);
			// echo $reqSatuanKerjaId;exit;
			$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}
		// echo $statement;exit();

		$searchJson = " AND (UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $set->getCountByParamsRekap(array(), $reqPeriode, $statement);
		//echo $allRecord;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsRekap(array(), $reqPeriode, $searchJson.$statement);
		
		$sOrder= " ORDER BY A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.TMT_PANGKAT ASC";
		$set->selectByParamsRekap(array(), $dsplyRange, $dsplyStart, $reqPeriode, $searchJson.$statement, $sOrder);
		// echo $set->query;exit;
		// echo $set->errorMsg;exit;

		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);
		
		while($set->nextRow())		
		{
			$row = array();		
			for ( $i=0 ; $i<count($aColumns) ; $i++ )		
			{
				if($aColumns[$i] == "NIP_BARU_NAMA_LENGKAP")
				{
					$row[] = $set->getField("NIP_BARU")."<br/>".$set->getField("NAMA_LENGKAP");
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}

	function setpartisi($reqPeriode)
	{
		$this->load->model('main/AbsensiRekap');

		if(!empty($reqPeriode))
		{
			$tahun= getTahunPeriode($reqPeriode);
			for($i= 1; $i <= 12; $i++)
			{
				$reqPeriode= generateZeroDate($i,2).$tahun;
				// echo $reqPeriode."<br/>";

				$set= new AbsensiRekap();
				$set->setPartisiTablePeriode($reqPeriode);
				// echo $set->query;exit();
			}
			// exit();
		}
	}
	
}

