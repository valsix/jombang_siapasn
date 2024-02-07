<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class surat_masuk_bkd_json extends CI_Controller {

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
		$this->STATUS_KELOMPOK_PEGAWAI_USUL= $this->kauth->getInstance()->getIdentity()->STATUS_KELOMPOK_PEGAWAI_USUL;
		$this->STATUS_MENU_KHUSUS= $this->kauth->getInstance()->getIdentity()->STATUS_MENU_KHUSUS;
	}	
	
	function json() 
	{
		$this->load->model('persuratan/SuratMasukBkd');

		$set = new SuratMasukBkd();
		$reqJenis= $this->input->get("reqJenis");
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
					
		$aColumns = array("NOMOR", "TANGGAL", "TOTAL_PEGAWAI_PROGRES_KEMBALI", "SATUAN_KERJA_ASAL_NAMA", "POSISI_TERAKHIR", "AKSI", "SURAT_MASUK_BKD_ID");
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
		
		//kalau admin cari satuan kerja aktif n status kelompok pegawai usul 1
		if($this->LOGIN_LEVEL == 99)
		{
			$set_detil= new SuratMasukBkd();
			$reqSatuanKerjaId= $set_detil->getCountByParamsSatuanKerjaIdSurat();
		}
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		$tempLoginLevel= $this->LOGIN_LEVEL;
		if($reqSatuanKerjaId == ""){}
		else
		{
			if($tempLoginLevel == 99){}
			else
			$statement= " AND A.SATUAN_KERJA_ASAL_ID = ".$reqSatuanKerjaId;
		}
		
		if($reqJenis == ""){}
		else
		{
			$statement.= " AND A.JENIS_ID = ".$reqJenis;
		}

		// echo $tempLoginLevel;exit();
		
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
					if($tempLoginLevel == 99)
					{
						$row[] = '<a href="javascript:void(0)" onclick="hapusdata(\''.$set->getField("SURAT_MASUK_BKD_ID").'\')" class="round waves-effect waves-light red white-text" title="Hapus" ><i class="mdi-action-delete"></i></a>';
					}
					else
					{
						if($set->getField("STATUS_KIRIM") == "1")
						$row[] = '';
						else
						$row[] = '<a href="javascript:void(0)" onclick="hapusdata(\''.$set->getField("SURAT_MASUK_BKD_ID").'\')" class="round waves-effect waves-light red white-text" title="Hapus" ><i class="mdi-action-delete"></i></a>';
					}
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}
	
	function json_teknis() 
	{
		$this->load->model('persuratan/SuratMasukBkd');

		$set = new SuratMasukBkd();
		$reqJenis= $this->input->get("reqJenis");

		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
					
		$aColumns = array("NOMOR", "TANGGAL", "TOTAL_PEGAWAI_PROGRES_KEMBALI", "SATUAN_KERJA_ASAL_USUL_NAMA", "POSISI_TERAKHIR", "AKSI", "SURAT_MASUK_BKD_ID");
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
				$sOrder = " ORDER BY A.TANGGAL DESC";
				 
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

		//kalau admin cari satuan kerja aktif n status kelompok pegawai usul 1
		if($this->LOGIN_LEVEL == 99)
		{
			$set_detil= new SuratMasukBkd();
			$reqSatuanKerjaId= $set_detil->getCountByParamsJenisPelayananSatuanKerjaId($reqJenis);
		}

		if($reqSatuanKerjaId == ""){}
		else
		{
			$statement= " AND A.SURAT_MASUK_BKD_ID IN (SELECT SURAT_MASUK_BKD_ID FROM persuratan.SURAT_MASUK_BKD_DISPOSISI WHERE SATUAN_KERJA_ASAL_ID = ".$reqSatuanKerjaId." AND TERBACA = 1 GROUP BY SURAT_MASUK_BKD_ID)";
		}
		
		// echo $sOrder;exit();
		$statement.= " AND A.JENIS_ID = ".$reqJenis;

		$tempSearch= $_GET['sSearch'];
		if(isDate($tempSearch))
		{
			$searchJson .= " AND (A.TANGGAL = TO_DATE('".dateToPageCheck($tempSearch)."','YYYY/MM/DD'))";
		}
		else
		{
			$searchJson = "  AND (UPPER(A.NOMOR) LIKE '%".strtoupper($tempSearch)."%' OR UPPER(B.NAMA) LIKE '%".strtoupper($tempSearch)."%'
			)";
		}

		// $searchJson = "  AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $set->getCountByParams(array(), $statement);
		// echo $allRecord;

		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParams(array(), $statement.$searchJson);
		
		$set->selectByParams(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
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
				else if($aColumns[$i] == "TOTAL_PEGAWAI_PROGRES_KEMBALI")
				{
					$row[] = $set->getField("TOTAL_PEGAWAI")." / ".$set->getField("TOTAL_PEGAWAI_PROGRES")." / ".$set->getField("TOTAL_PEGAWAI_KEMBALI");
				}
				else if($aColumns[$i] == "TANGGAL_BATAS")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "AKSI")
				{
					if($set->getField("STATUS_KIRIM") == "1")
					$row[] = '';
					else
					$row[] = '<a href="javascript:void(0)" onclick="hapusdata(\''.$set->getField("SURAT_MASUK_BKD_ID").'\')" class="round waves-effect waves-light red white-text" title="Hapus" ><i class="mdi-action-delete"></i></a>';
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
		$this->load->model('persuratan/SuratMasukBkd');

		$set = new SuratMasukBkd();
		$reqPegawaiId= $this->input->get("reqPegawaiId");
		$reqJenis= $this->input->get("reqJenis");
		$reqKategori= $this->input->get("reqKategori");
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
					
		$aColumns = array("NOMOR", "TANGGAL", "SURAT_MASUK_BKD_ID");
		$aColumnsAlias = array("NOMOR", "TANGGAL", "SURAT_MASUK_BKD_ID");

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
		AND A.SURAT_MASUK_BKD_ID NOT IN (SELECT SURAT_MASUK_BKD_ID FROM persuratan.SURAT_MASUK_PEGAWAI WHERE KATEGORI = '".$reqKategori."' AND PEGAWAI_ID IN (".$reqPegawaiId.") AND JENIS_ID = ".$reqJenis.")
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
		$this->load->model('persuratan/SuratMasukBkd');
		
		$reqJenis=  $this->input->get('reqJenis');
		$reqJenisPelayananId=  $this->input->get('reqJenisPelayananId');
		$reqStatusNomor=  $this->input->get('reqStatusNomor');
		$reqStatusSurat=  $this->input->get('reqStatusSurat');
		
		$set = new SuratMasukBkd();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		$tempStatusKelompokPegawaiUsul= $this->STATUS_KELOMPOK_PEGAWAI_USUL;
		$tempLoginLevel= $this->LOGIN_LEVEL;
		$tempStatusMenuKhusus= $this->STATUS_MENU_KHUSUS;

		if($tempStatusMenuKhusus == "1")
		{
			$aColumns = array("NO_AGENDA", "PERIHAL", "SATUAN_KERJA_ASAL_NAMA", "JENIS_ID", "SURAT_MASUK_BKD_ID");
			$aColumnsAlias = array("NO_AGENDA", "PERIHAL", "SATUAN_KERJA_ASAL_NAMA", "JENIS_ID", "SURAT_MASUK_BKD_ID");
		}
		else
		{
			if($tempStatusKelompokPegawaiUsul == "1" || $tempLoginLevel == 99)
			{
				$aColumns = array("NO_AGENDA", "TANGGAL_TERIMA", "TANGGAL", "NOMOR", "PERIHAL", "SATUAN_KERJA_ASAL_NAMA", "AKSI", "JENIS_ID", "SURAT_MASUK_BKD_ID");
				$aColumnsAlias = array("NO_AGENDA", "TANGGAL_TERIMA", "TANGGAL", "NOMOR", "PERIHAL", "SATUAN_KERJA_ASAL_NAMA", "AKSI", "JENIS_ID", "SURAT_MASUK_BKD_ID");
			}
			else
			{
				$aColumns = array("NO_AGENDA", "TANGGAL", "NOMOR", "PERIHAL", "SATUAN_KERJA_ASAL_NAMA", "POSISI_SURAT_BACA", "AKSI", "JENIS_ID", "SURAT_MASUK_BKD_ID");
				$aColumnsAlias = array("NO_AGENDA", "TANGGAL", "NOMOR", "PERIHAL", "SATUAN_KERJA_ASAL_NAMA", "POSISI_SURAT_BACA", "AKSI", "JENIS_ID", "SURAT_MASUK_BKD_ID");
			}	
		}

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
			if ( trim($sOrder) == "ORDER BY NO_AGENDA desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				
				// $sOrder = " ORDER BY CAST(COALESCE(NULLIF(A.NO_AGENDA, ''), NULL) AS NUMERIC) DESC ";
				$sOrder = " ORDER BY TT.TANGGAL_TERIMA DESC NULLS LAST, A.TANGGAL DESC, CAST(COALESCE(NULLIF(A.NO_AGENDA, ''), NULL) AS NUMERIC) DESC";
				 
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
		
		//kalau admin cari satuan kerja aktif n status kelompok pegawai usul 1
		if($this->LOGIN_LEVEL == 99)
		{
			$set_detil= new SuratMasukBkd();
			$reqSatuanKerjaId= $set_detil->getCountByParamsSatuanKerjaIdSurat();
		}
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}	
		//echo $reqSatuanKerjaId;exit;
		if($reqJenis == ""){}
		else
		$statementdisposisi= " AND JENIS_ID = ".$reqJenis;
		
		if($reqJenisPelayananId == ""){}
		else
		{
			$statement= " AND A.JENIS_ID = ".$reqJenisPelayananId;
		}

		if($reqStatusSurat == "")
		{
			if($tempStatusMenuKhusus == "1")
			{
				$statement.= " AND PD.STATUS_DISPOSISI IN (2,3,4)";

			}
		}
		else
		{
			if($reqStatusSurat == "xx")
			$statement.= " AND PD.STATUS_DISPOSISI IN (3,4)";
			else
			$statement.= " AND PD.STATUS_DISPOSISI = ".$reqStatusSurat;
		}

		if($reqStatusNomor == ""){}
		elseif($reqStatusNomor == "1")
		{
			$statement.= " AND COALESCE(NULLIF(A.NO_AGENDA, ''), NULL) IS NOT NULL ";
		}
		elseif($reqStatusNomor == "2")
		{
			$statement.= " AND COALESCE(NULLIF(A.NO_AGENDA, ''), NULL) IS NULL ";
		}

		$tempSearch= $_GET['sSearch'];

		if(isDate($tempSearch))
		{
			$searchJson .= " AND (TT.TANGGAL_TERIMA = TO_DATE('".dateToPageCheck($tempSearch)."','YYYY/MM/DD') OR A.TANGGAL = TO_DATE('".dateToPageCheck($tempSearch)."','YYYY/MM/DD'))";
		}
		else
		{
			$searchJson = "  AND (UPPER(A.NO_AGENDA) LIKE '%".strtoupper($tempSearch)."%' OR UPPER(A.NOMOR) LIKE '%".strtoupper($tempSearch)."%' OR UPPER(A.PERIHAL) LIKE '%".strtoupper($tempSearch)."%'
			 OR UPPER(CASE WHEN A.SATUAN_KERJA_ASAL_ID IS NULL THEN A.SATUAN_KERJA_ASAL_NAMA ELSE AMBIL_SATKER_NAMA(A.SATUAN_KERJA_ASAL_ID) END) LIKE '%".strtoupper($tempSearch)."%'
			)";
		}
		$allRecord = $set->getCountByParamsSurat(array(), $reqSatuanKerjaId, $statementdisposisi, $statement);
		// echo $allRecord;

		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParamsSurat(array(), $reqSatuanKerjaId, $statementdisposisi, $statement.$searchJson);
		// echo $sOrder;exit();
		$set->selectByParamsSurat(array(), $dsplyRange, $dsplyStart, $reqSatuanKerjaId, $statementdisposisi, $statement.$searchJson, $sOrder);
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
			$tempStatusKirimTeknis= $set->getField("STATUS_KIRIM_TEKNIS");
			$tempStatusTerima= $set->getField("STATUS_TERIMA");
			$tempJenisId= $set->getField("JENIS_ID");
			$row = array();
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				if($aColumns[$i] == "TANGGAL" || $aColumns[$i] == "TANGGAL_TERIMA")
					$row[] = dateToPageCheck($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "TANGGAL_DITERUSKAN")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "TANGGAL_BATAS")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "AKSI")
				{
					$temp= "";
					if($tempJenisId == "")
					{
						if($set->getField("JUMLAH_DISPOSISI") > 1){}
						else
						{
							$temp= '<a href="javascript:void(0)" onclick="hapussuratdata(\''.$set->getField("SURAT_MASUK_BKD_ID").'\')" class="round waves-effect waves-light red white-text" title="Hapus" ><i class="mdi-action-delete"></i></a>';
						}
					}
					else
					{
						if($this->LOGIN_LEVEL == 99)
						{
						}
					}
					$row[] = $temp;
				}

				else
					$row[] = $set->getField($aColumns[$i]);
			}
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}
	
	function add()
	{
		$this->load->model('persuratan/SuratMasukBkd');
		
		$set = new SuratMasukBkd();

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
				//echo $set->query;exit;
				echo "xxx-Data gagal disimpan.";
			}
		}
		else
		{
			$set->setField('SURAT_MASUK_BKD_ID', $reqId); 
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
		$this->load->model('persuratan/SuratMasukBkd');
		$this->load->model('persuratan/SuratMasukPegawai');
		$set = new SuratMasukBkd();
		
		$reqId =  $this->input->get('reqId');
		$reqJenis=  $this->input->get('reqJenis');
		$reqPerihal=  $this->input->get('reqPerihal');
		$reqStatusBerkas=  $this->input->get('reqStatusBerkas');
		
		$infovalid= 0;
		// untuk cek apakah belum valid
		// saat ii hanya karpeg, karsu dan pensiun, kenaikan pangkat
		if($reqJenis == "3" || $reqJenis == "4" || $reqJenis == "7" || $reqJenis == "10")
		{
			$setdetil = new SuratMasukPegawai();
			$statement= " AND A.STATUS_E_FILE_BKD IS NULL AND A.SURAT_MASUK_BKD_ID = ".$reqId;
			$infovalid= $setdetil->getCountByParams(array(), $statement);
		}

		// untuk cek apakah belum valid
		if($infovalid > 0)
		{
			$arrJson["PESAN"] = "Data gagal di kirim, karena ada data belum valid.";
		}
		else
		{
			$statement= " AND SMP.JENIS_ID = ".$reqJenis." AND SMP.SURAT_MASUK_BKD_ID = ".$reqId;
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
			
			$set->setField("SURAT_MASUK_BKD_ID", $reqId);
			$set->setField("PERIHAL", setQuote($tempPerihalInfo));
			$set->setField("STATUS_KIRIM", ValToNullDB("1"));
			$set->setField("LAST_USER", $this->LOGIN_USER);
			$set->setField("LAST_DATE", "NOW()");
			
			if($set->updatePerihalKirim())
			{
				$set_detil= new SuratMasukPegawai();
				$set_detil->setField("SURAT_MASUK_BKD_ID", $reqId);
				$set_detil->setField("STATUS_BERKAS", $reqStatusBerkas);
				$set_detil->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
				$set_detil->setField("LAST_USER", $this->LOGIN_USER);
				$set_detil->setField("LAST_DATE", "NOW()");
				$set_detil->updateStatusBerkasBkd();
				
				$arrJson["PESAN"] = "Data berhasil di kirim.";
			}
			else
			{
				// echo $set->query;exit();
				$arrJson["PESAN"] = "Data gagal di kirim.";		
			}
		}
		
		echo json_encode($arrJson);
	}
	
	function deletesurat()
	{
		$this->load->model('persuratan/SuratMasukBkd');
		$set = new SuratMasukBkd();
		
		$reqId =  $this->input->get('reqId');

		if($this->LOGIN_LEVEL == 99)
		{
			$set_detil= new SuratMasukBkd();
			$reqSatuanKerjaId= $set_detil->getCountByParamsSatuanKerjaIdSurat();

			if($reqSatuanKerjaId == "")
			{
				$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
			}

			$statement= " AND A.SURAT_MASUK_BKD_ID = ".$reqId;
			$check= new SuratMasukBkd();
			$check->selectByParamsSurat(array(), -1, -1, $reqSatuanKerjaId, "", $statement);
			// echo $check->query;exit();
			$check->firstRow();
			$tempJenisId= $check->getField("JENIS_ID");
			$tempJumlahDisposisi= $check->getField("JUMLAH_DISPOSISI");
			// echo $tempJenisId."--".$tempJumlahDisposisi;exit();

			$set = new SuratMasukBkd();
			$set->setField("SURAT_MASUK_BKD_ID", $reqId);
			$set->setField("LAST_USER", $this->LOGIN_USER);
			$set->setField("LAST_DATE", "NOW()");
			
			if($set->deletedata())
				$arrJson["PESAN"] = "Data berhasil dihapus.";
			else
				$arrJson["PESAN"] = "Data gagal dihapus.";
		}
		else
		{
			$set->setField("SURAT_MASUK_BKD_ID", $reqId);
			$set->setField("STATUS_BERKAS", "3");
			$set->setField("LAST_USER", $this->LOGIN_USER);
			$set->setField("LAST_DATE", "NOW()");
			
			if($set->delete())
				$arrJson["PESAN"] = "Data berhasil dihapus.";
			else
				$arrJson["PESAN"] = "Data gagal dihapus.";		
		}
		
		echo json_encode($arrJson);
	}
	
	function deletedata()
	{
		$this->load->model('persuratan/SuratMasukBkd');

		if($this->LOGIN_LEVEL == 99)
		{
			$set_detil= new SuratMasukBkd();
			$reqSatuanKerjaId= $set_detil->getCountByParamsSatuanKerjaIdSurat();
		}
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}	

		$reqId =  $this->input->get('reqId');

		$statement= " AND A.SURAT_MASUK_BKD_ID = ".$reqId;
		$check= new SuratMasukBkd();
		$check->selectByParamsSurat(array(), -1, -1, $reqSatuanKerjaId, "", $statement);
		// echo $check->query;exit();
		$check->firstRow();
		$tempJenisId= $check->getField("JENIS_ID");
		$tempJumlahDisposisi= $check->getField("JUMLAH_DISPOSISI");
		// echo $tempJenisId."--".$tempJumlahDisposisi;exit();

		if($tempJenisId == "" && $tempJumlahDisposisi <= 1)
		{
			$set = new SuratMasukBkd();
			$set->setField("SURAT_MASUK_BKD_ID", $reqId);
			$set->setField("LAST_USER", $this->LOGIN_USER);
			$set->setField("LAST_DATE", "NOW()");
			
			if($set->deletedata())
				$arrJson["PESAN"] = "Data berhasil dihapus.";
			else
				$arrJson["PESAN"] = "Data gagal dihapus.";
		}
		else
		{
			$arrJson["PESAN"] = "Data gagal dihapus.";
		}
		
		echo json_encode($arrJson);
	}

	function delete()
	{
		$this->load->model('SuratMasukBkd');
		$set = new SuratMasukBkd();
		
		$reqId =  $this->input->get('reqId');
		$set->setField("PEGAWAI_ID", $reqId);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("LAST_DATE", "NOW()");
		
		if($set->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";		
		
		echo json_encode($arrJson);
	}
	
	function delete_pegawai()
	{
		$this->load->model('persuratan/SuratMasukPegawai');
		$set = new SuratMasukPegawai();
		
		$reqId =  $this->input->get('reqId');
		$set->setField("SURAT_MASUK_PEGAWAI_ID", $reqId);
		$set->setField("STATUS_BERKAS", "3");
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("LAST_DATE", "NOW()");
		if($set->resetbidang())
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
	
	function set_info_pegawai_data()
	{
		$this->load->model('persuratan/SuratMasukBkd');
		$this->load->model('persuratan/SuratMasukPegawai');
		
		$reqId= $this->input->get('reqId');
		
		$statement= " AND A.SURAT_MASUK_BKD_ID = ".$reqId."";
		$set= new SuratMasukPegawai();
		$jumlah= $set->getCountByParams(array(), $statement);

		$set= new SuratMasukBkd();
		$statement= " AND A.SURAT_MASUK_BKD_ID = ".$reqId."";
		$set= new SuratMasukBkd();
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
		$this->load->model('persuratan/SuratMasukBkd');
		
		$reqId= $this->input->get("reqId");
		$reqJenisId= $this->input->get("reqJenisId");
		$reqJenis= $this->input->get("reqJenis");
		$reqPegawaiId= $this->input->get("reqPegawaiId");

		$set = new SuratMasukBkd();
		$statement= " AND A.SURAT_MASUK_BKD_ID = ".$reqId;
		$set->selectByParamsSimple(array(), -1,-1,$statement);
		$set->firstRow();
		// echo $set->query;exit;
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
			$statement.= " AND P.TMT <= (SELECT CAST(CAST(DATE_TRUNC('MONTH', TANGGAL) + INTERVAL '1 MONTH' - INTERVAL '1 DAY' AS DATE) + INTERVAL '1 YEAR' AS DATE) TANGGAL_BATAS FROM PERSURATAN.SURAT_MASUK_BKD WHERE SURAT_MASUK_BKD_ID = ".$reqId.")";
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
			$set->setField("SURAT_MASUK_BKD_ID", ValToNullDB($reqId));
			$set->setField("SURAT_MASUK_UPT_ID", ValToNullDB($req));
			$set->setField("PEGAWAI_ID", $reqPegawaiId);
			$set->setField("STATUS_BERKAS", "4");
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
					$set_detil->setField("SURAT_MASUK_BKD_ID", ValToNullDB($reqId));
					$set_detil->setField("SURAT_MASUK_UPT_ID", ValToNullDB($req));
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

	function setnomorawal()
	{
		// $reqTipe= $this->input->get('reqTipe');
		// $tanggalHariIni= $this->input->get('tanggalHariIni');
		$tanggalHariIni= date("d-m-Y");
		$tahunIni= date("Y");
		// $reqNomorSuratTahun= $this->input->get('reqNomorSuratTahun');
		$reqTanggal= $this->input->get('reqTanggal');
		
		$this->load->model('persuratan/SuratMasukBkd');
		$statement= " AND A.TANGGAL < TO_DATE('".dateToPageCheck($reqTanggal)."','YYYY/MM/DD')";
		$statement.= " 
		AND EXISTS
		(
			SELECT 1
			FROM
			(
				SELECT A.NO_AGENDA, A.TANGGAL
				FROM PERSURATAN.SURAT_MASUK_BKD A
				INNER JOIN PERSURATAN.SURAT_MASUK_BKD_DISPOSISI B ON A.SURAT_MASUK_BKD_ID = B.SURAT_MASUK_BKD_ID
				WHERE SURAT_AWAL = 1 
				AND '".$tahunIni."' = TO_CHAR(B.TANGGAL, 'YYYY')
			) X
			WHERE A.NO_AGENDA = X.NO_AGENDA
			AND A.TANGGAL = X.TANGGAL
		)
		AND TO_CHAR(TANGGAL, 'YYYY') = '".$tahunIni."'
		";
		$set_detil= new SuratMasukBkd();
		$reqNomorAwal= $set_detil->getCountByParamsNomorAwal($statement);
		// echo $set_detil->query;exit();

		$reqNomorSuratTahun= getDay($reqTanggal);

		$statement= " AND TO_CHAR(TANGGAL, 'YYYY') = '".$reqNomorSuratTahun."' AND COALESCE(NULLIF(A.NO_AGENDA, ''), NULL) IS NOT NULL";
		$reqTanggalCek= $set_detil->getCountByParamsTanggalPerTahun($statement);
		// echo $set_detil->query;exit();
		
		// echo $reqTanggalCek.";;".dateToPageCheck($reqTanggal);exit();
		$reqTanggalCekData= strtotime($reqTanggalCek);
		$reqTanggalData= dateToPageCheck($reqTanggal);
		$reqTanggalData= strtotime($reqTanggalData);
		// echo $reqTanggalCekData.";;".$reqTanggalData;exit();

		$incrementnomor= "";
		if($reqTanggalData < $reqTanggalCekData){}
		else
		$incrementnomor= "1";

		// echo $incrementnomor;exit();

		// $TglSKeluarUsul= $reqTanggal;
		// $TglSKeluarTRecord= dateToPageCheck($reqTanggalCek);
		// if($reqTanggalCek == "")
		// $TglSKeluarTRecordPlus= "";
		// else
		// {
		// $TglSKeluarTRecordPlus= date('Y-m-d',strtotime($date . "+1 days"));
		// /*$TglSKeluarTRecordPlus= date_create($reqTanggalCek);
		// date_add($TglSKeluarTRecordPlus,date_interval_create_from_date_string("1 days"));
		// $TglSKeluarTRecordPlus= date_format($TglSKeluarTRecordPlus,"Y-m-d");*/
		// $TglSKeluarTRecordPlus= dateToPageCheck($TglSKeluarTRecordPlus);
		// }
		// $TglAkses= $tanggalHariIni;
		
		// // echo $TglSKeluarUsul." == ".$TglSKeluarTRecord."&&".$TglSKeluarTRecord." <= ".$TglAkses;exit;
	
		// $incrementnomor= "";
		// if(
		// (
		// (strtotime($TglSKeluarUsul) == strtotime($TglSKeluarTRecord)) && (strtotime($TglSKeluarTRecord) <= strtotime($TglAkses))) 
		// || 
		// ((strtotime($TglSKeluarUsul) == strtotime($TglAkses)) && (strtotime($TglSKeluarTRecordPlus) == strtotime($TglAkses))) 
		// || ((strtotime($TglSKeluarUsul) > strtotime($TglSKeluarTRecord)) && (strtotime($TglSKeluarUsul)  <= strtotime($TglAkses)))
		// )
		// $incrementnomor= "1";
		
		if($incrementnomor == "1")
		{
			if($reqNomorAwal == "")
			$reqNomorAwal= 1;
			
			if($reqStatusTerima == "" && $reqTipe == "1")
			{
				$reqNomorAwal= "";
			}
		}
		else
		{
			$reqNomorAwal= "";
		}
		
		$arrData= array("reqNomorAwal"=>$reqNomorAwal);
		echo json_encode($arrData);
		
	}

}
?>