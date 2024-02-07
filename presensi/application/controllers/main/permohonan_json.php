<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class permohonan_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// trow to unauthenticated page!
			//redirect('Login');
		}		
		// $this->db->query("SET DATESTYLE TO PostgreSQL;");  
		// $this->db->query("SET datestyle TO 'ISO, MDY';");  

		$this->ID = $this->kauth->getInstance()->getIdentity()->ID;   		
		$this->NAMA = $this->kauth->getInstance()->getIdentity()->NAMA;   
		$this->USERNAME = $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->KODE_CABANG = $this->kauth->getInstance()->getIdentity()->KODE_CABANG;
		$this->CABANG = $this->kauth->getInstance()->getIdentity()->CABANG;
		$this->DEPARTEMEN = $this->kauth->getInstance()->getIdentity()->DEPARTEMEN;
		$this->SUB_DEPARTEMEN = $this->kauth->getInstance()->getIdentity()->SUB_DEPARTEMEN;
		$this->JABATAN = $this->kauth->getInstance()->getIdentity()->JABATAN;
	}	

	function json() 
	{
		$this->load->model('main/Permohonan');
		$this->load->model('main/SatuanKerja');

		$set= new Permohonan;
		
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqTahun= $this->input->get("reqTahun");
		$reqBulan= $this->input->get("reqBulan");

		$reqPeriode= $reqBulan.$reqTahun;
		$date=$reqTahun."-".$reqBulan;
		$totalhari =  getDay(date("Y-m-t",strtotime($date)));

		$aColumns = array("NIP_BARU_NAMA_LENGKAP", "SATUAN_KERJA_NAMA", "NAMA_IJIN_KOREKSI", "TANGGAL", "TANGGAL_AWAL", "TANGGAL_AKHIR", "JUMLAH_HARI", "STATUS", "AKSI", "PERMOHONAN_CUTI_ID", "LINK_FILE");
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
		
		// echo $_POST['draw']."--".$_POST['length']."--".$_POST['start']."--".$_POST['pageLength'];exit();
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
			$dsplyRange = 2147483645;
		}
		// echo $dsplyRange;exit();
		
		if($reqSatuanKerjaId == "")
		{
			$tempSatuanKerjaId= $reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}

		$statementAktif= "";
		/*if($reqSatuanKerjaId == "")
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
		}*/

		if(!empty($reqPeriode))
		{
			$statement.= " AND TO_CHAR(P.TANGGAL, 'MMYYYY') = '".$reqPeriode."'";
		}

		$statement.= " AND COALESCE(NULLIF(P.APPROVAL1, ''), 'X') = 'X'";
		// echo $statement;exit();

		$searchJson = " AND (UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $set->getCountByParamsPermohonan(array(), $statement);

		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsPermohonan(array(), $searchJson.$statement);
		
		$sOrder= " ORDER BY A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.TMT_PANGKAT ASC";
		$set->selectByParamsPermohonan(array(), $dsplyRange, $dsplyStart, $searchJson.$statement, $sOrder);
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
				elseif($aColumns[$i] == "TANGGAL" || $aColumns[$i] == "TANGGAL_AWAL" || $aColumns[$i] == "TANGGAL_AKHIR")
				{
					$row[] = dateToPageCheck(datetimeToPage($set->getField($aColumns[$i]), "date"));
					// $row[] = getFormattedDateTime($set->getField($aColumns[$i]), false);
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}

	function json_lupa_absen() 
	{
		$this->load->model('main/Permohonan');
		$this->load->model('main/SatuanKerja');

		$set= new Permohonan;
		
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqTahun= $this->input->get("reqTahun");
		$reqBulan= $this->input->get("reqBulan");

		$reqPeriode= $reqBulan.$reqTahun;
		$date=$reqTahun."-".$reqBulan;
		$totalhari =  getDay(date("Y-m-t",strtotime($date)));

		$aColumns = array("NIP_BARU_NAMA_LENGKAP", "SATUAN_KERJA_NAMA", "NAMA_IJIN_KOREKSI", "TANGGAL", "TANGGAL_AWAL", "STATUS", "PERMOHONAN_CUTI_ID");
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

		// echo $_POST['length'];exit();
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
			// echo "aaa";exit();
			// $dsplyRange = 2147483645;
			$dsplyRange = 50;
		}
		// echo $dsplyRange;exit();
		
		if($reqSatuanKerjaId == "")
		{
			$tempSatuanKerjaId= $reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}

		$statementAktif= "";
		/*if($reqSatuanKerjaId == "")
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
		}*/

		if(!empty($reqPeriode))
		{
			$statement.= " AND TO_CHAR(P.TANGGAL, 'MMYYYY') = '".$reqPeriode."'";
		}

		$statement.= " AND COALESCE(NULLIF(P.APPROVAL1, ''), 'X') = 'X'";

		$statement.= " AND P.NAMA_PERMOHONAN = 'PERMOHONAN_LUPA_ABSEN'";
		// echo $statement;exit();

		$searchJson = " AND (UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $set->getCountByParamsPermohonan(array(), $statement);
		//echo $allRecord;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsPermohonan(array(), $searchJson.$statement);
		
		$sOrder= " ORDER BY A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.TMT_PANGKAT ASC";
		$set->selectByParamsPermohonan(array(), $dsplyRange, $dsplyStart, $searchJson.$statement, $sOrder);
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
				elseif($aColumns[$i] == "TANGGAL" || $aColumns[$i] == "TANGGAL_AWAL" || $aColumns[$i] == "TANGGAL_AKHIR")
				{
					$row[] = dateToPageCheck(datetimeToPage($set->getField($aColumns[$i]), "date"));
					// $row[] = getFormattedDateTime($set->getField($aColumns[$i]), false);
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}

	function json_dinas() 
	{
		$this->load->model('main/Permohonan');
		$this->load->model('main/SatuanKerja');

		$set= new Permohonan;
		
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqTahun= $this->input->get("reqTahun");
		$reqBulan= $this->input->get("reqBulan");

		$reqPeriode= $reqBulan.$reqTahun;
		$date=$reqTahun."-".$reqBulan;
		$totalhari =  getDay(date("Y-m-t",strtotime($date)));

		$aColumns = array("NIP_BARU_NAMA_LENGKAP", "SATUAN_KERJA_NAMA", "TANGGAL", "TANGGAL_AWAL", "TANGGAL_AKHIR", "KETERANGAN", "STATUS", "PERMOHONAN_CUTI_ID");
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

		// echo $_POST['length'];exit();
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
			// echo "aaa";exit();
			// $dsplyRange = 2147483645;
			$dsplyRange = 50;
		}
		// echo $dsplyRange;exit();
		
		if($reqSatuanKerjaId == "")
		{
			$tempSatuanKerjaId= $reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}

		$statementAktif= "";
		/*if($reqSatuanKerjaId == "")
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
		}*/

		if(!empty($reqPeriode))
		{
			$statement.= " AND TO_CHAR(P.TANGGAL, 'MMYYYY') = '".$reqPeriode."'";
		}

		$statement.= " AND COALESCE(NULLIF(P.APPROVAL1, ''), 'X') = 'X'";

		$statement.= " AND P.NAMA_PERMOHONAN = 'PERMOHONAN_LAMBAT_PC'";
		// echo $statement;exit();

		$searchJson = " AND (UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $set->getCountByParamsPermohonan(array(), $statement);
		//echo $allRecord;
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsPermohonan(array(), $searchJson.$statement);
		
		$sOrder= " ORDER BY A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.TMT_PANGKAT ASC";
		$set->selectByParamsPermohonan(array(), $dsplyRange, $dsplyStart, $searchJson.$statement, $sOrder);
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
				elseif($aColumns[$i] == "TANGGAL" || $aColumns[$i] == "TANGGAL_AWAL" || $aColumns[$i] == "TANGGAL_AKHIR")
				{
					$row[] = dateToPageCheck(datetimeToPage($set->getField($aColumns[$i]), "date"));
					// $row[] = getFormattedDateTime($set->getField($aColumns[$i]), false);
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}

	function cari_pegawai()
	{
		$this->load->model('main/Permohonan');
		
		$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
		
		$reqId= $this->input->get("reqId");
        $reqMode= $this->input->get("reqMode");
		
		$set= new Permohonan();
		$statement.= " AND UPPER(A.NIP_BARU) LIKE '%".strtoupper(str_replace(" ", "", $search_term))."%' ";
		
		// if($reqSatuanKerjaId == "")
		// {
		// 	$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		// }
		
		// if($reqSatuanKerjaId == ""){}
		// else
		// {
		// 	$skerja= new SatuanKerja();
		// 	$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
		// 	unset($skerja);
		// 	//echo $reqSatuanKerjaId;exit;
		// 	$statement.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		// }

		$set->selectByParamsPegawai(array(), 10, 0, $statement);
		//echo $set->query;exit;
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("PEGAWAI_ID");
			$arr_json[$i]['label'] = $set->getField("NIP_BARU");
			$arr_json[$i]['desc'] = $set->getField("NIP_BARU")."<br/><label style='font-size:12px'>".$set->getField("NAMA_LENGKAP")."(".$set->getField("JABATAN_RIWAYAT_NAMA").")</label>";
			$arr_json[$i]['namajabatan'] = $set->getField("JABATAN_RIWAYAT_NAMA");
			$arr_json[$i]['namapegawai'] = $set->getField("NAMA_LENGKAP");
			$i++;
		}
		echo json_encode($arr_json);
	}

}

