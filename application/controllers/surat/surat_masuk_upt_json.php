<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class surat_masuk_upt_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// trow to unauthenticated page!
			//redirect('Login');
		}       
		
		/* GLOBAL VARIABLE */
		$this->USER_LOGIN_ID= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;
		$this->LOGIN_USER= $this->kauth->getInstance()->getIdentity()->LOGIN_USER;
		$this->LOGIN_LEVEL= $this->kauth->getInstance()->getIdentity()->LOGIN_LEVEL;
		$this->SATUAN_KERJA_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID;
		$this->SATUAN_KERJA_BKD_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_BKD_ID;
	}
		
	function json() 
	{
		$this->load->model('persuratan/SuratMasukUpt');

		$set = new SuratMasukUpt();
		$reqJenis= $this->input->get("reqJenis");
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
					
		$aColumns = array("NOMOR", "TANGGAL", "TOTAL_PEGAWAI_PROGRES_KEMBALI", "SATUAN_KERJA_ASAL_NAMA", "POSISI_TERAKHIR", "AKSI", "SURAT_MASUK_UPT_ID");
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
					if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc ") == 0)
					{
						$sOrder .=" asc , ";
					}else
					{
						$sOrder .=" desc , ";
					}
				}
			}
			
			 
			//Remove the last space / comma
			$sOrder = substr_replace( $sOrder, "", -2 );
			
			//Check if there is an order by clause
			if ( trim($sOrder) == "ORDER BY NOMOR desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.TANGGAL DESC ";
			}
			// echo $sOrder;exit;
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
		if ( isset( $_GET['iDisplayStart'] ))
		{
			$dsplyStart = $_GET['iDisplayStart'];
		}
		else{
			$dsplyStart = 0;
		}
		 
		if ( isset( $_GET['iDisplayLength'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$dsplyRange = $_GET['iDisplayLength'];
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
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
			
		if($reqSatuanKerjaId == ""){}
		else
		{
			$statement.= " AND A.SATUAN_KERJA_ASAL_ID = ".$reqSatuanKerjaId;
		}
		
		if($reqJenis == ""){}
		else
		{
			$statement.= " AND A.JENIS_ID = ".$reqJenis;
		}
		
		$searchJson = "  AND (UPPER(A.NOMOR) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $set->getCountByParams(array(), $statement);
		// echo $allRecord;

		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParams(array(), $statement.$searchJson);
		
		$set->selectByParams(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
		//echo $set->query;exit;
		/* Output */
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
				if($aColumns[$i] == "TANGGAL")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "TANGGAL_DITERUSKAN")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "TOTAL_PEGAWAI_PROGRES_KEMBALI")
				{
					$tempValue= $set->getField("TOTAL_PEGAWAI")." / ".$set->getField("TOTAL_PEGAWAI_PROGRES")." / ".$set->getField("TOTAL_PEGAWAI_KEMBALI");
					if($set->getField("TOTAL_PEGAWAI_KEMBALI") > 0)
					$tempValue= $set->getField("TOTAL_PEGAWAI")." / ".$set->getField("TOTAL_PEGAWAI_PROGRES")." / <label style='color:red; font-size:10px'>".$set->getField("TOTAL_PEGAWAI_KEMBALI")."</label>";
					$row[] = $tempValue;
				}
				else if($aColumns[$i] == "TANGGAL_BATAS")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "AKSI")
				{
					if($set->getField("STATUS_KIRIM") == "1")
					{
						if($this->USER_LOGIN_ID == "1")
						{
							$row[] = '<a href="javascript:void(0)" onclick="hapusdata(\''.$set->getField("SURAT_MASUK_UPT_ID").'\')" class="round waves-effect waves-light red white-text" title="Hapus" ><i class="mdi-action-delete"></i></a>';
						}
						else
						$row[] = '';
					}
					else
					$row[] = '<a href="javascript:void(0)" onclick="hapusdata(\''.$set->getField("SURAT_MASUK_UPT_ID").'\')" class="round waves-effect waves-light red white-text" title="Hapus" ><i class="mdi-action-delete"></i></a>';
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}
	
	function json_pilih() 
	{
		$this->load->model('persuratan/SuratMasukUpt');

		$set = new SuratMasukUpt();
		$reqPegawaiId= $this->input->get("reqPegawaiId");
		$reqJenis= $this->input->get("reqJenis");
		$reqKategori= $this->input->get("reqKategori");
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
					
		$aColumns = array("NOMOR", "TANGGAL", "SURAT_MASUK_UPT_ID");
		$aColumnsAlias = array("NOMOR", "TANGGAL", "SURAT_MASUK_UPT_ID");

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
					if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc ") == 0)
					{
						$sOrder .=" asc , ";
					}else
					{
						$sOrder .=" desc , ";
					}
				}
			}
			
			 
			//Remove the last space / comma
			$sOrder = substr_replace( $sOrder, "", -2 );
			
			//Check if there is an order by clause
			if ( trim($sOrder) == "ORDER BY TANGGAL desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.TANGGAL ASC ";
				 
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
		if ( isset( $_GET['iDisplayStart'] ))
		{
			$dsplyStart = $_GET['iDisplayStart'];
		}
		else{
			$dsplyStart = 0;
		}
		 
		if ( isset( $_GET['iDisplayLength'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$dsplyRange = $_GET['iDisplayLength'];
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
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
			
		if($reqSatuanKerjaId == ""){}
		else
		{
			$statement= " AND A.SATUAN_KERJA_ASAL_ID = ".$reqSatuanKerjaId;
		}
		
		if($reqJenis == ""){}
		else
		{
			$statement.= " AND A.JENIS_ID = ".$reqJenis;
		}
		
		$statement.= " AND COALESCE(NULLIF(A.STATUS_KIRIM, ''), NULL) IS NULL
		AND 
		(
			SELECT TMT FROM PENSIUN WHERE JENIS = '".$reqKategori."' AND PEGAWAI_ID IN (".$reqPegawaiId.")
		)
		<=
		(
			CAST(CAST(DATE_TRUNC('MONTH', A.TANGGAL) + INTERVAL '1 MONTH' - INTERVAL '1 DAY' AS DATE) + INTERVAL '1 YEAR' AS DATE)
		)
		AND A.SURAT_MASUK_UPT_ID NOT IN (SELECT SURAT_MASUK_UPT_ID FROM persuratan.SURAT_MASUK_PEGAWAI WHERE KATEGORI = '".$reqKategori."' AND PEGAWAI_ID IN (".$reqPegawaiId.") AND JENIS_ID = ".$reqJenis.")
		AND A.KATEGORI = '".$reqKategori."'
		";



		// $searchJson = "  AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $set->getCountByParams(array(), $statement);
		// echo $allRecord;

		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParams(array(), $statement.$searchJson);
		
		$set->selectByParams(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
		//echo $set->query;exit;
		/* Output */
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
				if($aColumns[$i] == "TANGGAL")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "TANGGAL_DITERUSKAN")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "TOTAL_PEGAWAI_PROGRES_KEMBALI")
				{
					$tempValue= $set->getField("TOTAL_PEGAWAI")." / ".$set->getField("TOTAL_PEGAWAI_PROGRES")." / ".$set->getField("TOTAL_PEGAWAI_KEMBALI");
					if($set->getField("TOTAL_PEGAWAI_KEMBALI") > 0)
					$tempValue= $set->getField("TOTAL_PEGAWAI")." / ".$set->getField("TOTAL_PEGAWAI_PROGRES")." / <label style='color:red; font-size:10px'>".$set->getField("TOTAL_PEGAWAI_KEMBALI")."</label>";
					$row[] = $tempValue;
				}
				else if($aColumns[$i] == "TANGGAL_BATAS")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "AKSI")
				{
					if($set->getField("STATUS_KIRIM") == "1")
					$row[] = '';
					else
					$row[] = '<a href="javascript:void(0)" onclick="hapusdata(\''.$set->getField("SURAT_MASUK_UPT_ID").'\')" class="round waves-effect waves-light red white-text" title="Hapus" ><i class="mdi-action-delete"></i></a>';
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}

	function json_surat() 
	{
		$this->load->model('persuratan/SuratMasukUpt');
		
		$reqJenis=  $this->input->get('reqJenis');
		$reqTerbaca=  $this->input->get('reqTerbaca');
		
		$set = new SuratMasukUpt();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
			
		$aColumns = array("TANGGAL", "PERIHAL", "SATUAN_KERJA_ASAL_NAMA", "NOMOR", "TERBACA_NAMA", "JENIS_ID", "SURAT_MASUK_UPT_ID");
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
					if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc ") == 0)
					{
						$sOrder .=" asc , ";
					}else
					{
						$sOrder .=" desc , ";
					}
				}
			}
			
			 
			//Remove the last space / comma
			$sOrder = substr_replace( $sOrder, "", -2 );
			
			//Check if there is an order by clause
			if ( trim($sOrder) == "ORDER BY TANGGAL desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.TANGGAL DESC ";
				 
			}
		}
		// echo $sOrder;exit;
		 
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
		if ( isset( $_GET['iDisplayStart'] ))
		{
			$dsplyStart = $_GET['iDisplayStart'];
		}
		else{
			$dsplyStart = 0;
		}
		 
		if ( isset( $_GET['iDisplayLength'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$dsplyRange = $_GET['iDisplayLength'];
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
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}	
		
		if($reqSatuanKerjaId == ""){}
		else
		{
			$statement.= " AND A.SATUAN_KERJA_TUJUAN_ID = ".$reqSatuanKerjaId;
		}

		if($reqJenis == ""){}
		else
		$statementdisposisi= " AND JENIS_ID = ".$reqJenis;
		
		if($reqTerbaca == ""){}
		else
		{
			$statement.= " AND COALESCE(A.TERBACA,0) = ".$reqTerbaca;
		}

		$statement.= " AND A.STATUS_KIRIM = '1'";
		$searchJson = "  AND (UPPER(A.PERIHAL) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NOMOR) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $set->getCountByParamsSurat(array(), $reqSatuanKerjaId, $statementdisposisi, $statement);
		// echo $allRecord;

		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParamsSurat(array(), $reqSatuanKerjaId, $statementdisposisi, $statement.$searchJson);
		
		$set->selectByParamsSurat(array(), $dsplyRange, $dsplyStart, $reqSatuanKerjaId, $statementdisposisi, $statement.$searchJson, $sOrder);
		// echo $set->query;exit;
		/* Output */
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
				if($aColumns[$i] == "TANGGAL")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "TANGGAL_DITERUSKAN")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "TANGGAL_BATAS")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}
	
	function add()
	{
		$this->load->model('persuratan/SuratMasukUpt');
		
		$set = new SuratMasukUpt();

		$reqId = $this->input->post("reqId");
		$reqMode = $this->input->post("reqMode");

		$reqNomor = $this->input->post("reqNomor");
		$reqAgenda = $this->input->post("reqAgenda");
		$reqJenis= $this->input->post("reqJenis");
		$reqTanggal = $this->input->post("reqTanggal");
		$reqTanggalDiteruskan = $this->input->post("reqTanggalDiteruskan");
		$reqTanggalBatas = $this->input->post("reqTanggalBatas");
		$reqKepada = $this->input->post("reqKepada");
		$reqPerihal = $this->input->post("reqPerihal");
		$reqSatuanKerjaTujuanId = $this->input->post("reqSatuanKerjaTujuanId");
		$reqSatuanKerjaAsalId = $this->input->post("reqSatuanKerjaAsalId");

		$reqPengaturanKenaikanPangkatId = $this->input->post("reqPengaturanKenaikanPangkatId");
		$reqKategori = $this->input->post("reqKategori");
		$reqJkmNomor = $this->input->post("reqJkmNomor");
		$reqJkmTanggal = $this->input->post("reqJkmTanggal");

		$set->setField('JENIS_ID', $reqJenis);
		$set->setField('NOMOR', $reqNomor);
		$set->setField('NO_AGENDA', $reqAgenda);
		$set->setField('TANGGAL', dateToDBCheck($reqTanggal));
		$set->setField('TANGGAL_DITERUSKAN', dateToDBCheck($reqTanggalDiteruskan));
		$set->setField('TANGGAL_BATAS', dateToDBCheck($reqTanggalBatas));
		$set->setField('KEPADA', $reqKepada);
		$set->setField('PERIHAL', $reqPerihal);
		$set->setField('SATUAN_KERJA_TUJUAN_ID', ValToNullDB($reqSatuanKerjaTujuanId));
		$set->setField('SATUAN_KERJA_ASAL_ID', ValToNullDB($reqSatuanKerjaAsalId));

		$set->setField('PENGATURAN_KENAIKAN_PANGKAT_ID', ValToNullDB($reqPengaturanKenaikanPangkatId));
		$set->setField('KATEGORI', $reqKategori);
		$set->setField('JKM_NOMOR', $reqJkmNomor);
		$set->setField('JKM_TANGGAL', dateToDBCheck($reqJkmTanggal));

		if($reqMode == "insert")
		{
			if($set->insert())
			{
				$reqId= $set->id;
				echo $reqId."-Data berhasil disimpan.";
			}
			else
			{
				// echo $set->query;exit();
				echo "xxx-Data gagal disimpan.";
			}
			
		}
		else
		{
			$set->setField('SURAT_MASUK_UPT_ID', $reqId); 
			if($set->update())
			{
				echo $reqId."-Data berhasil disimpan.";
			}
			else
				echo "xxx-Data gagal disimpan.";
		}
		
	}
	
	function statuskirim()
	{
		$this->load->model('persuratan/SuratMasukUpt');
		$this->load->model('persuratan/SuratMasukPegawai');
		$set = new SuratMasukUpt();
		
		$reqId=  $this->input->get('reqId');
		$reqJenis=  $this->input->get('reqJenis');
		$reqPerihal=  $this->input->get('reqPerihal');
		$reqStatusBerkas=  $this->input->get('reqStatusBerkas');

		$infovalid= 0;
		// untuk cek apakah belum valid
		// saat ii hanya karpeg, karsu dan pensiun, kenaikan pangkat
		if($reqJenis == "3" || $reqJenis == "4" || $reqJenis == "7" || $reqJenis == "10")
		{
			$setdetil = new SuratMasukPegawai();
			$statement= " AND A.STATUS_E_FILE_UPT IS NULL AND A.SURAT_MASUK_UPT_ID = ".$reqId;
			$infovalid= $setdetil->getCountByParams(array(), $statement);
		}

		// untuk cek apakah belum valid
		if($infovalid > 0)
		{
			$arrJson["PESAN"] = "Data gagal di kirim, karena ada data belum valid.";
		}
		else
		{
			$statement= " AND SMP.JENIS_ID = ".$reqJenis." AND SMP.SURAT_MASUK_UPT_ID = ".$reqId;
			$set_detil= new SuratMasukPegawai();
			$set_detil->selectByParamsUsulanPegawai(array(), -1, -1, $statement, "ORDER BY A.PEGAWAI_ID");
			//echo $set_detil->query;exit;
			$tempJumlahDataUsulan= 0;
			while($set_detil->nextRow())
			{
				//echo $set_detil->query;exit;
				if($tempJumlahDataUsulan == 0)
				$tempNamaSaja= $set_detil->getField("NAMA_SAJA");
				
				$tempJumlahDataUsulan++;
			}
			
			$tempPerihalInfo= $reqPerihal." a.n ".$tempNamaSaja;
			if($tempJumlahDataUsulan == 1){}
			else
			$tempPerihalInfo= $tempPerihalInfo." dkk";
		  
			$set->setField("SURAT_MASUK_UPT_ID", $reqId);
			$set->setField("PERIHAL", setQuote($tempPerihalInfo));
			$set->setField("STATUS_KIRIM", ValToNullDB("1"));
			$set->setField("LAST_USER", $this->LOGIN_USER);
			$set->setField("LAST_DATE", "NOW()");
			
			if($set->updatePerihalKirim())
			{
				$set_detil= new SuratMasukPegawai();
				$set_detil->setField("SURAT_MASUK_UPT_ID", $reqId);
				$set_detil->setField("STATUS_BERKAS", $reqStatusBerkas);
				$set_detil->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
				$set_detil->setField("LAST_USER", $this->LOGIN_USER);
				$set_detil->setField("LAST_DATE", "NOW()");
				$set_detil->updateStatusBerkasUpt();
				$arrJson["PESAN"] = "Data berhasil di kirim.";
			}
			else
				$arrJson["PESAN"] = "Data gagal di kirim.";		
		}
		//echo $set->query;exit;
		echo json_encode($arrJson);
	}
	
	function delete()
	{
		$this->load->model('persuratan/SuratMasukUpt');
		$set = new SuratMasukUpt();
		
		$reqId =  $this->input->get('reqId');
		$set->setField("SURAT_MASUK_UPT_ID", $reqId);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("LAST_DATE", "NOW()");
		
		if($set->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";		
		
		
		echo json_encode($arrJson);
	}
	
	function combo() 
	{
		$this->load->model("Eselon");
		$set = new Eselon();
		
		$set->selectByParams();
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("BANK_ID");
			$arr_json[$i]['text'] = $set->getField("NAMA");	
			$i++;
		}
		echo json_encode($arr_json);		
		
	}	

	function log() 
	{
		$this->load->model('PegawaiLog');

		$set = new PegawaiLog();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		$aColumns = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "PEGAWAI_ID");
		$aColumnsAlias = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "PEGAWAI_ID");

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
					if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc ") == 0)
					{
						$sOrder .=" asc , ";
					}else
					{
						$sOrder .=" desc , ";
					}
				}
			}
			
			 
			//Remove the last space / comma
			$sOrder = substr_replace( $sOrder, "", -2 );
			
			//Check if there is an order by clause
			if ( trim($sOrder) == "ORDER BY INFO_LOG desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.LAST_DATE ASC ";
				 
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
		if ( isset( $_GET['iDisplayStart'] ))
		{
			$dsplyStart = $_GET['iDisplayStart'];
		}
		else{
			$dsplyStart = 0;
		}
		 
		if ( isset( $_GET['iDisplayLength'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$dsplyRange = $_GET['iDisplayLength'];
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
		
		// $searchJson = "  AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $set->getCountByParams(array());
		// echo $allRecord;

		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParams(array(), $searchJson);
		
		$set->selectByParams(array(), $dsplyRange, $dsplyStart, $searchJson, $sOrder);
		//echo $set->query;exit;
		/* Output */
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
				if($aColumns[$i] == "TANGGAL")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "PEGAWAI_INFO")
					//$row[] = truncate($set->getField($aColumns[$i]), 5)."...";
					$row[] = '<img src="images/foto-profile.jpg" />';
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}
	
	function baca_surat()
	{
		$this->load->model('persuratan/SuratMasukUpt');
		$this->load->model('persuratan/SuratMasukPegawai');
		$set = new SuratMasukUpt();
		
		$reqId=  $this->input->get('reqId');
		$reqStatusBerkas=  $this->input->get('reqStatusBerkas');
		
		$set->setField("SURAT_MASUK_UPT_ID", $reqId);
		$set->setField("TERBACA", ValToNullDB("1"));
		if($set->updateBaca())
		{
			$set_detil= new SuratMasukPegawai();
			$set_detil->setField("SURAT_MASUK_UPT_ID", $reqId);
			$set_detil->setField("STATUS_BERKAS", $reqStatusBerkas);
			$set_detil->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
			$set_detil->setField("LAST_USER", $this->LOGIN_USER);
			$set_detil->setField("LAST_DATE", "NOW()");
			$set_detil->updateStatusBerkasUpt();
			//echo $set_detil->query;exit;
		}
		echo $tempData;
	}
	
	function set_info_pegawai_data()
	{
		$this->load->model('persuratan/SuratMasukUpt');
		$this->load->model('persuratan/SuratMasukPegawai');
		
		$reqId= $this->input->get('reqId');

		$statement= " AND A.SURAT_MASUK_UPT_ID = ".$reqId."";
		$set= new SuratMasukPegawai();
		$jumlah= $set->getCountByParams(array(), $statement);
		
		$set= new SuratMasukUpt();
		$statement= " AND A.SURAT_MASUK_UPT_ID = ".$reqId."";
		$set= new SuratMasukUpt();
		$set->selectByParams(array(), -1, -1, $statement);
		$set->firstRow();
		$reqNomor= $set->getField("NOMOR");
		$reqTanggal= dateToPageCheck($set->getField("TANGGAL"));
		
		$tempInfo='
			<div class="row" style="color:white">
				<div class="col s3 m1">Nomor</div>
				<div class="col s4 m4">'.$reqNomor.'</div>
				<div class="col s5 m5" style="text-align: right;">Jumlah</div>
				<div class="col s1 m1">'.$jumlah.'</div>
			</div>
			<div class="row" style="color:white">
				<div class="col s3 m1">Tanggal</div>
				<div class="col s9 m11">'.$reqTanggal.'</div>
			</div>
		';
		echo $tempInfo;
	}

	function add_surat_usulan()
	{
		$this->load->model('persuratan/SuratMasukPegawai');
		$this->load->model('persuratan/SuratMasukUpt');
		
		$reqId= $this->input->get("reqId");
		$reqJenisId= $this->input->get("reqJenisId");
		$reqJenis= $this->input->get("reqJenis");
		$reqPegawaiId= $this->input->get("reqPegawaiId");

		$set = new SuratMasukUpt();
		$statement= " AND A.SURAT_MASUK_UPT_ID = ".$reqId;
		$set->selectByParamsSimple(array(), -1,-1,$statement);
		$set->firstRow();
		//echo $set->query;exit;
		$tempStatusKirim= $set->getField("STATUS_KIRIM");
		if($tempStatusKirim == "1"){}
		else
		{
			$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JENIS_ID = 7";
			$set= new SuratMasukPegawai();
			$set->selectByParams(array(), -1,-1, $statement);
			$set->firstRow();
			// echo $set->query;exit();
			$reqSuratMasukPegawaiId= $set->getField("SURAT_MASUK_PEGAWAI_ID");
			// echo $reqSuratMasukPegawaiId;exit();

			$set= new SuratMasukPegawai();
			$set->setField("SURAT_MASUK_PEGAWAI_ID", $reqSuratMasukPegawaiId);
			$set->delete();

			$set= new SuratMasukPegawai();
			$statement= " AND A.PEGAWAI_ID NOT IN (SELECT PEGAWAI_ID FROM persuratan.SURAT_MASUK_PEGAWAI WHERE JENIS_ID = ".$reqJenisId." AND (STATUS_BERKAS NOT IN (10) OR STATUS_BERKAS IS NULL OR (STATUS_BERKAS = 10 AND (STATUS_SURAT_KELUAR NOT IN (3) OR STATUS_SURAT_KELUAR IS NULL ))))";
			$statement.= " AND P.TMT <= (SELECT CAST(CAST(DATE_TRUNC('MONTH', TANGGAL) + INTERVAL '1 MONTH' - INTERVAL '1 DAY' AS DATE) + INTERVAL '1 YEAR' AS DATE) TANGGAL_BATAS FROM PERSURATAN.SURAT_MASUK_UPT WHERE SURAT_MASUK_UPT_ID = ".$reqId.")";
			$statement.= " AND P.JENIS = '".$reqJenis."' AND P.PEGAWAI_ID = ".$reqPegawaiId;
			$set->selectByParamsPegawaiCariPensiun(array(), -1, -1, $statement);
			$set->firstRow();
			// echo $set->query;exit();
			$reqJabatanRiwayatAkhirId= $set->getField("JABATAN_RIWAYAT_ID");
			$reqPendidikanRiwayatAkhirId= $set->getField("PENDIDIKAN_RIWAYAT_ID");
			$reqGajiRiwayatAkhirId= $set->getField("GAJI_RIWAYAT_ID");
			$reqPangkatRiwayatAkhirId= $set->getField("PANGKAT_RIWAYAT_ID");
			$reqSatuanKerjaPegawaiUsulanId= $set->getField("SATUAN_KERJA_ID");
			$reqKategori= $reqJenis;

			$set = new SuratMasukPegawai();
			$set->setField("JENIS_ID", $reqJenisId);
			$set->setField("SURAT_MASUK_BKD_ID", ValToNullDB($req));
			$set->setField("SURAT_MASUK_UPT_ID", ValToNullDB($reqId));
			$set->setField("PEGAWAI_ID", $reqPegawaiId);
			$set->setField("STATUS_BERKAS", "1");
			$set->setField("JABATAN_RIWAYAT_AKHIR_ID", ValToNullDB($reqJabatanRiwayatAkhirId));
			$set->setField("JABATAN_RIWAYAT_SEKARANG_ID", ValToNullDB($req));
			$set->setField("PENDIDIKAN_RIWAYAT_AKHIR_ID", ValToNullDB($reqPendidikanRiwayatAkhirId));
			$set->setField("PENDIDIKAN_RIWAYAT_SEKARANG_ID", ValToNullDB($reqPendidikanRiwayatSekarangId));
			$set->setField("GAJI_RIWAYAT_AKHIR_ID", ValToNullDB($reqGajiRiwayatAkhirId));
			$set->setField("GAJI_RIWAYAT_SEKARANG_ID", ValToNullDB($req));
			$set->setField("PANGKAT_RIWAYAT_AKHIR_ID", ValToNullDB($reqPangkatRiwayatAkhirId));
			$set->setField("PANGKAT_RIWAYAT_SEKARANG_ID", ValToNullDB($req));
			$set->setField("SATUAN_KERJA_PEGAWAI_USULAN_ID", $reqSatuanKerjaPegawaiUsulanId);
			$set->setField("KATEGORI", $reqKategori);
			$set->setField("KETERANGAN_PENSIUN", $reqKeteranganPensiun);
			$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
			$set->setField("LAST_USER", $this->LOGIN_USER);
			$set->setField("LAST_DATE", "NOW()");

			if($set->insertPensiun())
			{
				$tempSimpan=1;
				$reqRowId= $set->id;
			}

			if($tempSimpan == 1)
			{
				$statement= " AND A.NOMOR <= 5";
				$set= new SuratMasukPegawai();
				$set->selectByParamsAmbilAnak($reqPegawaiId, $reqKategori, $statement);
				while($set->nextRow())
				{
					$reqAnakId= $set->getField("ANAK_ID");
					$set_detil= new SuratMasukPegawai();
					$set_detil->setField("SURAT_MASUK_PEGAWAI_ID", $reqRowId);
					$set_detil->setField("JENIS_ID", $reqJenisId);
					$set_detil->setField("KATEGORI", $reqKategori);
					$set_detil->setField("SURAT_MASUK_BKD_ID", ValToNullDB($req));
					$set_detil->setField("SURAT_MASUK_UPT_ID", ValToNullDB($reqId));
					$set_detil->setField("PEGAWAI_ID", $reqPegawaiId);
					$set_detil->setField("ANAK_ID", $reqAnakId);
					$set_detil->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
					$set_detil->setField("LAST_USER", $this->LOGIN_USER);
					$set_detil->setField("LAST_DATE", "NOW()");
					$set_detil->insertPensiunAnak();
				}
			}

		}

	}

	function cobagabung()
	{
		require_once("lib/mergepdf/MergePdf.class.php");

		$filelokasiformatbaru= "uploads/28/pdf-merge.pdf";
		$filelokasi1= "uploads/28/0OzPKU6jsqju4ffffXs5cn6IKc3.pdf";
		$filelokasi2= "uploads/28/2jqtkORxKA.pdf";

		$set_merge= new MergePdf();
		$set_merge->merge(
			Array(
				$filelokasi1,
				$filelokasi2
			), "F", $filelokasiformatbaru
		);
		echo "Asd";exit;
	}
		
}
?>