<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class surat_masuk_pegawai_json extends CI_Controller {

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
	
	function json() 
	{
		$this->load->model('persuratan/SuratMasukPegawai');

		$set = new SuratMasukPegawai();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		$reqId= $this->input->get("reqId");
		$reqJenis= $this->input->get("reqJenis");
		
		$aColumns = array("CHECK", "PEGAWAI_INFO", "NIP_BARU_LAMA", "NAMA_LENGKAP", "GOL_TMT", "JABATAN_TMT_ESELON", "SATUAN_KERJA_NAMA", "SATUAN_KERJA_INDUK", "PEGAWAI_ID");
		$aColumnsAlias = array("CHECK", "PEGAWAI_INFO", "NIP_BARU_LAMA", "NAMA_LENGKAP", "GOL_TMT", "JABATAN_TMT_ESELON", "SATUAN_KERJA_NAMA", "SATUAN_KERJA_INDUK", "PEGAWAI_ID");

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
			//if ( trim($sOrder) == "ORDER BY PEGAWAI_INFO desc" )
			if ( trim($sOrder) == "ORDER BY CHECK desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.NAMA ASC ";

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
				$skerja= new SuratMasukPegawai();
				$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				unset($skerja);
				//echo $reqSatuanKerjaId;exit;
				$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				//$statement= " AND ( A.SATUAN_KERJA_ID = ANY( AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$reqSatuanKerjaId.") ) OR A.SATUAN_KERJA_ID = ".$reqSatuanKerjaId." )";
			}

			$statement.= " AND A.PEGAWAI_ID NOT IN (SELECT PEGAWAI_ID FROM persuratan.SURAT_MASUK_PEGAWAI WHERE JENIS_ID = ".$reqJenis." AND SURAT_MASUK_UPT_ID = ".$reqId.")";

		// $searchJson = "  AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
			$allRecord = $set->getCountByParamsMonitoringPegawai(array(), $statement);
		// echo $allRecord;

			if($_GET['sSearch'] == "")
				$allRecordFilter = $allRecord;
			else	
				$allRecordFilter = $set->getCountByParamsMonitoringPegawai(array(), $statement.$searchJson);

			$set->selectByParamsMonitoringPegawai(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
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
					if($aColumns[$i] == 'CHECK')
					{
						$row[] = "<input type='checkbox' class='editor-active' id='reqPilihCheck".$set->getField("PEGAWAI_ID")."' value='".$set->getField("PEGAWAI_ID")."' /><label for='reqPilihCheck".$set->getField("PEGAWAI_ID")."'></label>";
					}
					else if($aColumns[$i] == "TANGGAL")
						$row[] = getFormattedDate($set->getField($aColumns[$i]));
					else if($aColumns[$i] == "PEGAWAI_INFO")
						$row[] = '<img src="images/foto-profile.jpg" style="width:100%;height:100%;" />';
					else if($aColumns[$i] == "NIP_BARU_LAMA")
						$row[] = $set->getField("NIP_BARU")."<br/>".$set->getField("NIP_LAMA");
					else if($aColumns[$i] == "GOL_TMT")
						$row[] = $set->getField("PANGKAT_RIWAYAT_KODE")."<br/>".dateToPageCheck($set->getField("PANGKAT_RIWAYAT_TMT"));
					else if($aColumns[$i] == "JABATAN_TMT_ESELON")
						$row[] = $set->getField("JABATAN_RIWAYAT_NAMA")."<br/>".dateTimeToPageCheck($set->getField("JABATAN_RIWAYAT_TMT"))."<br/>".$set->getField("JABATAN_RIWAYAT_ESELON");
					else
						$row[] = $set->getField($aColumns[$i]);
				}

				$output['aaData'][] = $row;
			}

			echo json_encode( $output );
		}

		function json_pilih_tanda_tangan() 
		{
			$this->load->model('persuratan/SuratMasukPegawai');

			$set = new SuratMasukPegawai();

			ini_set("memory_limit","500M");
			ini_set('max_execution_time', 520);

			$reqId= $this->input->get("reqId");
			$reqJenis= $this->input->get("reqJenis");
			$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
			$reqStatusBkdUptId= $this->input->get("reqStatusBkdUptId");

			$aColumns = array("NIP_BARU", "NAMA_LENGKAP", "JABATAN_NAMA", "JABATAN_DATA_NAMA", "TIPE_ID", "PEGAWAI_ID");
			$aColumnsAlias = array("NIP_BARU", "NAMA_LENGKAP", "JABATAN_NAMA", "JABATAN_DATA_NAMA", "TIPE_ID", "PEGAWAI_ID");

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
			//if ( trim($sOrder) == "ORDER BY PEGAWAI_INFO desc" )
			if ( trim($sOrder) == "ORDER BY CHECK desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.NAMA ASC ";

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
				$skerja= new SuratMasukPegawai();
				$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				unset($skerja);
				// echo $reqSatuanKerjaId;exit;
				// $statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				// $satkerStatement= " AND A.SATKER_ID IN (".$reqSatuanKerjaId.")";
				$satkerStatement= " 
				AND 
				(
				A.SATKER_ID IN (".$reqSatuanKerjaId.")
				OR A.SATKER_ID IN (SELECT CAST(SATUAN_KERJA_ID_OLD AS NUMERIC) ID_OLD FROM SATUAN_KERJA WHERE CAST(SATUAN_KERJA_ID AS NUMERIC) IN (".$reqSatuanKerjaId."))
				)
				";

				// $statement= " AND AMBIL_SATKER_ID_INDUK(A.SATUAN_KERJA_ID) = ".$reqSatuanKerjaId;
				//$statement= " AND ( A.SATUAN_KERJA_ID = ANY( AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$reqSatuanKerjaId.") ) OR A.SATUAN_KERJA_ID = ".$reqSatuanKerjaId." )";
			}

			// kalau 1 maka dari uptd
			if($reqStatusBkdUptId == "1")
			{
				// --AND TIPE_JABATAN_ID = 1
				$statementJabatan= " AND 
				(
					A.SATKER_ID IN (SELECT SATUAN_KERJA_ID FROM SATUAN_KERJA WHERE 1=1
					".$satkerStatement.")
				)";

				if($reqId == ""){}
				else
				{
					$statementJabatan.= " AND TO_DATE(TO_CHAR(A.TMT_JABATAN, 'YYYY/MM/DD'), 'YYYY/MM/DD') < 
					(SELECT TANGGAL FROM PERSURATAN.SURAT_MASUK_UPT WHERE SURAT_MASUK_UPT_ID = ".$reqId.")";
				}

				$statementTambahan= $satkerStatement;

				if($reqId == ""){}
				else
				{
					$statementTambahan.="
					AND (SELECT TANGGAL FROM PERSURATAN.SURAT_MASUK_UPT WHERE SURAT_MASUK_UPT_ID = ".$reqId.")
					BETWEEN TO_DATE(TO_CHAR(A.TMT_JABATAN, 'YYYY/MM/DD'), 'YYYY/MM/DD') AND COALESCE(TO_DATE(TO_CHAR(A.TMT_JABATAN_AKHIR, 'YYYY/MM/DD'), 'YYYY/MM/DD'), CURRENT_DATE)";
				}
			}
			else
			{
				// --AND TIPE_JABATAN_ID = 1
				$statementJabatan= " AND 
				(
					A.SATKER_ID IN (SELECT SATUAN_KERJA_ID FROM SATUAN_KERJA WHERE 1=1
					".$satkerStatement.")
				)";

				if($reqId == ""){}
				else
				{
					$statementJabatan.= " AND TO_DATE(TO_CHAR(A.TMT_JABATAN, 'YYYY/MM/DD'), 'YYYY/MM/DD') < 
					(SELECT TANGGAL FROM PERSURATAN.SURAT_MASUK_BKD WHERE SURAT_MASUK_BKD_ID = ".$reqId.")";
				}

				$statementTambahan= $satkerStatement;

				if($reqId == ""){}
				else
				{
					$statementTambahan.="
					AND (SELECT TANGGAL FROM PERSURATAN.SURAT_MASUK_BKD WHERE SURAT_MASUK_BKD_ID = ".$reqId.")
					BETWEEN TO_DATE(TO_CHAR(A.TMT_JABATAN, 'YYYY/MM/DD'), 'YYYY/MM/DD') AND COALESCE(TO_DATE(TO_CHAR(A.TMT_JABATAN_AKHIR, 'YYYY/MM/DD'), 'YYYY/MM/DD'), CURRENT_DATE)";
				}
			}
			// echo $statementJabatan;exit();

			$searchJson = "  AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
			$allRecord = $set->getCountByParamsMonitoringCariKepala(array(), $statementJabatan, $statementTambahan, $statement);
			// echo $allRecord;

			if($_GET['sSearch'] == "")
				$allRecordFilter = $allRecord;
			else	
				$allRecordFilter = $set->getCountByParamsMonitoringCariKepala(array(), $statementJabatan, $statementTambahan, $searchJson.$statement);
			$sOrder= "ORDER BY CASE WHEN CASE WHEN JAB_RIW.ESELON_ID IS NULL THEN 99 ELSE COALESCE(JAB_RIW.ESELON_ID,99) END = 99 THEN 99 WHEN JAB_RIW.ESELON_ID = 0 THEN 99 ELSE JAB_RIW.ESELON_ID END ASC, PANG_RIW.PANGKAT_ID DESC, PANG_RIW.TMT_PANGKAT, JAB_RIW.TMT_JABATAN";
			$set->selectByParamsMonitoringCariKepala(array(), $dsplyRange, $dsplyStart, $statementJabatan, $statementTambahan, $searchJson.$statement, $sOrder);
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
					else if($aColumns[$i] == "PEGAWAI_INFO")
						$row[] = '<img src="images/foto-profile.jpg" style="width:100%;height:100%;" />';
					else if($aColumns[$i] == "NIP_BARU_LAMA")
						$row[] = $set->getField("NIP_BARU")."<br/>".$set->getField("NIP_LAMA");
					else if($aColumns[$i] == "GOL_TMT")
						$row[] = $set->getField("PANGKAT_RIWAYAT_KODE")."<br/>".dateToPageCheck($set->getField("PANGKAT_RIWAYAT_TMT"));
					else if($aColumns[$i] == "JABATAN_DATA_NAMA")
						$row[] = str_replace(",", "#", $set->getField("JABATAN_NAMA"));
					else if($aColumns[$i] == "JABATAN_TMT_ESELON")
						$row[] = $set->getField("JABATAN_RIWAYAT_NAMA")."<br/>".dateTimeToPageCheck($set->getField("JABATAN_RIWAYAT_TMT"))."<br/>".$set->getField("JABATAN_RIWAYAT_ESELON");
					else
						$row[] = $set->getField($aColumns[$i]);
				}

				$output['aaData'][] = $row;
			}

			echo json_encode( $output );
		}

		function json_pilih_usulan_tanda_tangan() 
		{
			$this->load->model('persuratan/SuratMasukPegawai');

			$set = new SuratMasukPegawai();

			ini_set("memory_limit","500M");
			ini_set('max_execution_time', 520);

			$reqId= $this->input->get("reqId");
			$reqJenis= $this->input->get("reqJenis");
			$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
			$reqUptBkdJenis= $this->input->get("reqUptBkdJenis");

			$aColumns = array("NIP_BARU", "NAMA_LENGKAP", "JABATAN_RIWAYAT_NAMA", "TIPE_ID", "PEGAWAI_ID");
			$aColumnsAlias = array("NIP_BARU", "NAMA_LENGKAP", "JABATAN_RIWAYAT_NAMA", "TIPE_ID", "PEGAWAI_ID");

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
			//if ( trim($sOrder) == "ORDER BY PEGAWAI_INFO desc" )
			if ( trim($sOrder) == "ORDER BY CHECK desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.NAMA ASC ";

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
				$skerja= new SuratMasukPegawai();
				$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				unset($skerja);
				//echo $reqSatuanKerjaId;exit;
				$statementsatuankerja= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";

				if($reqUptBkdJenis == "1")
				{
					$statementPlt= " AND TMT_JABATAN <= (SELECT TANGGAL FROM persuratan.SURAT_MASUK_BKD WHERE SURAT_MASUK_BKD_ID = ".$reqId.")
				AND COALESCE(TMT_JABATAN_AKHIR, (SELECT TANGGAL FROM persuratan.SURAT_MASUK_BKD WHERE SURAT_MASUK_BKD_ID = ".$reqId.")) >= (SELECT TANGGAL FROM persuratan.SURAT_MASUK_BKD WHERE SURAT_MASUK_BKD_ID = ".$reqId.")";
				}
				else
				{
					$statementPlt= " AND TMT_JABATAN <= (SELECT TANGGAL FROM persuratan.SURAT_MASUK_UPT WHERE SURAT_MASUK_UPT_ID = ".$reqId.")
				AND COALESCE(TMT_JABATAN_AKHIR, (SELECT TANGGAL FROM persuratan.SURAT_MASUK_UPT WHERE SURAT_MASUK_UPT_ID = ".$reqId.")) >= (SELECT TANGGAL FROM persuratan.SURAT_MASUK_UPT WHERE SURAT_MASUK_UPT_ID = ".$reqId.")";
				}
			}

			// $searchJson = "  AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
			$allRecord = $set->getCountByParamsMonitoringUsulanCariKepala(array(), $statementsatuankerja, $statementPlt);
			// echo $allRecord;

			if($_GET['sSearch'] == "")
				$allRecordFilter = $allRecord;
			else	
				$allRecordFilter = $set->getCountByParamsMonitoringUsulanCariKepala(array(), $statementsatuankerja, $statementPlt, $searchJson);
			$sOrder= "ORDER BY A.NOMOR ASC";
			$set->selectByParamsMonitoringUsulanCariKepala(array(), $dsplyRange, $dsplyStart, $statementsatuankerja, $statementPlt, $searchJson, $sOrder);
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
					else if($aColumns[$i] == "PEGAWAI_INFO")
						$row[] = '<img src="images/foto-profile.jpg" style="width:100%;height:100%;" />';
					else if($aColumns[$i] == "NIP_BARU_LAMA")
						$row[] = $set->getField("NIP_BARU")."<br/>".$set->getField("NIP_LAMA");
					else if($aColumns[$i] == "GOL_TMT")
						$row[] = $set->getField("PANGKAT_RIWAYAT_KODE")."<br/>".dateToPageCheck($set->getField("PANGKAT_RIWAYAT_TMT"));
					else if($aColumns[$i] == "JABATAN_TMT_ESELON")
						$row[] = $set->getField("JABATAN_RIWAYAT_NAMA")."<br/>".dateTimeToPageCheck($set->getField("JABATAN_RIWAYAT_TMT"))."<br/>".$set->getField("JABATAN_RIWAYAT_ESELON");
					else
						$row[] = $set->getField($aColumns[$i]);
				}

				$output['aaData'][] = $row;
			}

			echo json_encode( $output );
		}

		function json_dinas() 
		{
			$this->load->model('persuratan/SuratMasukPegawai');

			$set = new SuratMasukPegawai();

			ini_set("memory_limit","500M");
			ini_set('max_execution_time', 520);

			$reqId= $this->input->get("reqId");
			$reqJenis= $this->input->get("reqJenis");

			$aColumns = array("CHECK", "PEGAWAI_INFO", "NIP_BARU_LAMA", "NAMA_LENGKAP", "GOL_TMT", "JABATAN_TMT_ESELON", "SATUAN_KERJA_NAMA", "SATUAN_KERJA_INDUK", "PEGAWAI_ID");
			$aColumnsAlias = array("CHECK", "PEGAWAI_INFO", "NIP_BARU_LAMA", "NAMA_LENGKAP", "GOL_TMT", "JABATAN_TMT_ESELON", "SATUAN_KERJA_NAMA", "SATUAN_KERJA_INDUK", "PEGAWAI_ID");

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
			//if ( trim($sOrder) == "ORDER BY PEGAWAI_INFO desc" )
			if ( trim($sOrder) == "ORDER BY CHECK desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.NAMA ASC ";

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
				$skerja= new SuratMasukPegawai();
				$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				unset($skerja);
				//echo $reqSatuanKerjaId;exit;
				$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				//$statement= " AND ( A.SATUAN_KERJA_ID = ANY( AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$reqSatuanKerjaId.") ) OR A.SATUAN_KERJA_ID = ".$reqSatuanKerjaId." )";
			}

			$statement.= " AND A.PEGAWAI_ID NOT IN (SELECT PEGAWAI_ID FROM persuratan.SURAT_MASUK_PEGAWAI WHERE JENIS_ID = ".$reqJenis." AND SURAT_MASUK_BKD_ID = ".$reqId.")";

		// $searchJson = "  AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
			$allRecord = $set->getCountByParamsMonitoringPegawai(array(), $statement);
		// echo $allRecord;

			if($_GET['sSearch'] == "")
				$allRecordFilter = $allRecord;
			else	
				$allRecordFilter = $set->getCountByParamsMonitoringPegawai(array(), $statement.$searchJson);

			$set->selectByParamsMonitoringPegawai(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
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
					if($aColumns[$i] == 'CHECK')
					{
						$row[] = "<input type='checkbox' class='editor-active' id='reqPilihCheck".$set->getField("PEGAWAI_ID")."' value='".$set->getField("PEGAWAI_ID")."' /><label for='reqPilihCheck".$set->getField("PEGAWAI_ID")."'></label>";
					}
					else if($aColumns[$i] == "TANGGAL")
						$row[] = getFormattedDate($set->getField($aColumns[$i]));
					else if($aColumns[$i] == "PEGAWAI_INFO")
						$row[] = '<img src="images/foto-profile.jpg" style="width:100%;height:100%;" />';
					else if($aColumns[$i] == "NIP_BARU_LAMA")
						$row[] = $set->getField("NIP_BARU")."<br/>".$set->getField("NIP_LAMA");
					else if($aColumns[$i] == "GOL_TMT")
						$row[] = $set->getField("PANGKAT_RIWAYAT_KODE")."<br/>".dateToPageCheck($set->getField("PANGKAT_RIWAYAT_TMT"));
					else if($aColumns[$i] == "JABATAN_TMT_ESELON")
						$row[] = $set->getField("JABATAN_RIWAYAT_NAMA")."<br/>".dateTimeToPageCheck($set->getField("JABATAN_RIWAYAT_TMT"))."<br/>".$set->getField("JABATAN_RIWAYAT_ESELON");
					else
						$row[] = $set->getField($aColumns[$i]);
				}

				$output['aaData'][] = $row;
			}

			echo json_encode( $output );
		}

		function json_bkd() 
		{
			$this->load->model('persuratan/SuratMasukPegawai');

			$set = new SuratMasukPegawai();

			ini_set("memory_limit","500M");
			ini_set('max_execution_time', 520);

			$reqId= $this->input->get("reqId");
			$reqJenis= $this->input->get("reqJenis");
			$reqJenisUsulan= $this->input->get("reqJenisUsulan");
			$reqStatusUsulan= $this->input->get("reqStatusUsulan");
			$reqKategori= $this->input->get("reqKategori");

			$aColumns = array("GROUP_INFO", "CHECK", "PEGAWAI_INFO", "NIP_BARU_LAMA", "NAMA_LENGKAP", "GOL_TMT", "JABATAN_TMT_ESELON", "SATUAN_KERJA_NAMA", "SATUAN_KERJA_INDUK", "PEGAWAI_ID");
			$aColumnsAlias = array("GROUP_INFO", "CHECK", "PEGAWAI_INFO", "NIP_BARU_LAMA", "NAMA_LENGKAP", "GOL_TMT", "JABATAN_TMT_ESELON", "SATUAN_KERJA_NAMA", "SATUAN_KERJA_INDUK", "PEGAWAI_ID");

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
			//if ( trim($sOrder) == "ORDER BY PEGAWAI_INFO desc" )
			if ( trim($sOrder) == "ORDER BY CHECK desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.NAMA ASC ";

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
				$skerja= new SuratMasukPegawai();
				$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				unset($skerja);
				//echo $reqSatuanKerjaId;exit;
				//$statementsatuankerja= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				//$statementsatuankerja= " AND ( A.SATUAN_KERJA_ID = ANY( AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$reqSatuanKerjaId.") ) OR A.SATUAN_KERJA_ID = ".$reqSatuanKerjaId." )";
			}

			$statement.= " AND SMP.STATUS_VERIFIKASI = '1'";
			$statement.= " AND A.PEGAWAI_ID NOT IN (SELECT PEGAWAI_ID FROM persuratan.SURAT_MASUK_PEGAWAI WHERE JENIS_ID = ".$reqJenisUsulan." AND USULAN_SURAT_ID = ".$reqId.")";
			$statement.= " AND SMP.JENIS_ID = ".$reqJenisUsulan;

			if($reqKategori == "")
			{
				$statement.= " AND COALESCE(NULLIF(SMP.KATEGORI, ''), NULL) IS NULL";
			}
			else
			$statement.= " AND SMP.KATEGORI = '".$reqKategori."'";

			if($reqStatusUsulan == "1")
			{
				$statement.= " AND SMP.USULAN_SURAT_ID IS NULL";
			}

			// $searchJson = "  AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
			$allRecord = $set->getCountByParamsMonitoringBkd(array(), $statementsatuankerja, $statement);

			if($_GET['sSearch'] == "")
				$allRecordFilter = $allRecord;
			else	
				$allRecordFilter = $set->getCountByParamsMonitoringBkd(array(), $statementsatuankerja, $statement.$searchJson);

			$set->selectByParamsMonitoringBkd(array(), $dsplyRange, $dsplyStart, $statementsatuankerja, $statement.$searchJson, $sOrder);
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
					if($aColumns[$i] == 'CHECK')
					{
						$row[] = "<input type='checkbox' class='editor-active' id='reqPilihCheck".$set->getField("PEGAWAI_ID")."-".$set->getField("SURAT_MASUK_PEGAWAI_ID")."' value='".$set->getField("PEGAWAI_ID")."-".$set->getField("SURAT_MASUK_PEGAWAI_ID")."' /><label for='reqPilihCheck".$set->getField("PEGAWAI_ID")."-".$set->getField("SURAT_MASUK_PEGAWAI_ID")."'></label>";
					}
					else if($aColumns[$i] == "TANGGAL")
						$row[] = getFormattedDate($set->getField($aColumns[$i]));
					else if($aColumns[$i] == "PEGAWAI_INFO")
						$row[] = '<img src="images/foto-profile.jpg" style="width:100%;height:100%;" />';
					else if($aColumns[$i] == "NIP_BARU_LAMA")
						$row[] = $set->getField("NIP_BARU")."<br/>".$set->getField("NIP_LAMA");
					else if($aColumns[$i] == "GOL_TMT")
						$row[] = $set->getField("PANGKAT_RIWAYAT_KODE")."<br/>".dateToPageCheck($set->getField("PANGKAT_RIWAYAT_TMT"));
					else if($aColumns[$i] == "JABATAN_TMT_ESELON")
						$row[] = $set->getField("JABATAN_RIWAYAT_NAMA")."<br/>".dateTimeToPageCheck($set->getField("JABATAN_RIWAYAT_TMT"))."<br/>".$set->getField("JABATAN_RIWAYAT_ESELON");
					else
						$row[] = $set->getField($aColumns[$i]);
				}

				$output['aaData'][] = $row;
			}

			echo json_encode( $output );
		}
		
		function json_upt() 
		{
			$this->load->model('persuratan/SuratMasukPegawai');

			$set = new SuratMasukPegawai();

			ini_set("memory_limit","500M");
			ini_set('max_execution_time', 520);

			$reqId= $this->input->get("reqId");
			$reqJenis= $this->input->get("reqJenis");
			$reqKategori= $this->input->get("reqKategori");

			$aColumns = array("GROUP_INFO", "CHECK", "PEGAWAI_INFO", "NIP_BARU_LAMA", "NAMA_LENGKAP", "GOL_TMT", "JABATAN_TMT_ESELON", "SATUAN_KERJA_NAMA", "SATUAN_KERJA_INDUK", "PEGAWAI_ID");
			$aColumnsAlias = array("GROUP_INFO", "CHECK", "PEGAWAI_INFO", "NIP_BARU_LAMA", "NAMA_LENGKAP", "GOL_TMT", "JABATAN_TMT_ESELON", "SATUAN_KERJA_NAMA", "SATUAN_KERJA_INDUK", "PEGAWAI_ID");

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
				//if ( trim($sOrder) == "ORDER BY PEGAWAI_INFO desc" )
				if ( trim($sOrder) == "ORDER BY CHECK desc" )
				{
					/*
					* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
					* If there is no order by clause there might be bugs in table display.
					* No order by clause means that the db is not responsible for the data ordering,
					* which means that the same row can be displayed in two pages - while
					* another row will not be displayed at all.
					*/
					$sOrder = " ORDER BY A.NAMA ASC ";

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
				$skerja= new SuratMasukPegawai();
				$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				unset($skerja);
				//echo $reqSatuanKerjaId;exit;
				$statementsatuankerja= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				//$statementsatuankerja= " AND ( A.SATUAN_KERJA_ID = ANY( AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$reqSatuanKerjaId.") ) OR A.SATUAN_KERJA_ID = ".$reqSatuanKerjaId." )";
			}

			if($reqKategori == ""){}
			else
			{
				$statement.= " AND SMP.KATEGORI = '".$reqKategori."'";
			}

			$statement.= " AND SMU.STATUS_KIRIM = '1' AND SMU.TERBACA = 1";
			$statement.= " AND A.PEGAWAI_ID NOT IN (SELECT PEGAWAI_ID FROM persuratan.SURAT_MASUK_PEGAWAI WHERE JENIS_ID = ".$reqJenis." AND SURAT_MASUK_BKD_ID = ".$reqId.")";
			$statement.= " AND SMP.JENIS_ID = ".$reqJenis;
			
			$statement.= " AND SMP.STATUS_BERKAS <= 3";
			

		// $searchJson = "  AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
			$allRecord = $set->getCountByParamsMonitoringUpt(array(), $statementsatuankerja, $statement);
		// echo $allRecord;

			if($_GET['sSearch'] == "")
				$allRecordFilter = $allRecord;
			else	
				$allRecordFilter = $set->getCountByParamsMonitoringUpt(array(), $statementsatuankerja, $statement.$searchJson);

			$set->selectByParamsMonitoringUpt(array(), $dsplyRange, $dsplyStart, $statementsatuankerja, $statement.$searchJson, $sOrder);
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
					if($aColumns[$i] == 'CHECK')
					{
						$row[] = "<input type='checkbox' class='editor-active' id='reqPilihCheck".$set->getField("PEGAWAI_ID")."-".$set->getField("SURAT_MASUK_PEGAWAI_ID")."' value='".$set->getField("PEGAWAI_ID")."-".$set->getField("SURAT_MASUK_PEGAWAI_ID")."' /><label for='reqPilihCheck".$set->getField("PEGAWAI_ID")."-".$set->getField("SURAT_MASUK_PEGAWAI_ID")."'></label>";
					}
					else if($aColumns[$i] == "TANGGAL")
						$row[] = getFormattedDate($set->getField($aColumns[$i]));
					else if($aColumns[$i] == "PEGAWAI_INFO")
						$row[] = '<img src="images/foto-profile.jpg" style="width:100%;height:100%;" />';
					else if($aColumns[$i] == "NIP_BARU_LAMA")
						$row[] = $set->getField("NIP_BARU")."<br/>".$set->getField("NIP_LAMA");
					else if($aColumns[$i] == "GOL_TMT")
						$row[] = $set->getField("PANGKAT_RIWAYAT_KODE")."<br/>".dateToPageCheck($set->getField("PANGKAT_RIWAYAT_TMT"));
					else if($aColumns[$i] == "JABATAN_TMT_ESELON")
						$row[] = $set->getField("JABATAN_RIWAYAT_NAMA")."<br/>".dateTimeToPageCheck($set->getField("JABATAN_RIWAYAT_TMT"))."<br/>".$set->getField("JABATAN_RIWAYAT_ESELON");
					else
						$row[] = $set->getField($aColumns[$i]);
				}

				$output['aaData'][] = $row;
			}

			echo json_encode( $output );
		}

		function add()
		{
			$this->load->model('persuratan/SuratMasukPegawai');

			$reqId= $this->input->get("reqId");
			$reqJenis= $this->input->get("reqJenis");
			$reqPegawaiId= $this->input->get("reqPegawaiId");
			$arrPegawaiId= explode(",", $reqPegawaiId);

			for($i=0; $i < count($arrPegawaiId); $i++)
			{
				$set = new SuratMasukPegawai();
				$set->setField("JENIS_ID", $reqJenis);
				$set->setField("SURAT_MASUK_BKD_ID", ValToNullDB($req));
				$set->setField("SURAT_MASUK_UPT_ID", ValToNullDB($reqId));
				$set->setField("PEGAWAI_ID", $arrPegawaiId[$i]);
				$set->insert();
				unset($set);
			}

		}

		function add_pendidikan()
		{
			$this->load->model('persuratan/SuratMasukPegawai');

			$set = new SuratMasukPegawai();

			$reqId= $this->input->post("reqId");
			$reqJenis= $this->input->post("reqJenis");
			$reqRowId= $this->input->post("reqRowId");
			$reqRowDetilId= $this->input->post("reqRowDetilId");
			$reqPegawaiId= $this->input->post("reqPegawaiId");
			$reqMode= $this->input->post("reqMode");
			$reqJabatanRiwayatAkhirId= $this->input->post("reqJabatanRiwayatAkhirId");
			$reqPendidikanRiwayatAkhirId= $this->input->post("reqPendidikanRiwayatAkhirId");
			$reqGajiRiwayatAkhirId= $this->input->post("reqGajiRiwayatAkhirId");
			$reqPangkatRiwayatAkhirId= $this->input->post("reqPangkatRiwayatAkhirId");
			$reqSatuanKerjaPegawaiUsulanId= $this->input->post("reqSatuanKerjaPegawaiUsulanId");

			$reqNamaSekolah=  $this->input->post('reqNamaSekolah');
			$reqNamaFakultas=  $this->input->post('reqNamaFakultas');
			$reqPendidikanId=  $this->input->post('reqPendidikanId');
			$reqTglSttb= $this->input->post('reqTglSttb');
			$reqJurusan=  $this->input->post('reqJurusan');
			$reqJurusanId=  $this->input->post('reqJurusanId');
			$reqAlamatSekolah=  $this->input->post('reqAlamatSekolah');
			$reqKepalaSekolah=  $this->input->post('reqKepalaSekolah');
			$reqNoSttb=  $this->input->post('reqNoSttb');
			$reqStatusPendidikan=  $this->input->post('reqStatusPendidikan');
			$reqStatusTugasIjinBelajar=  $this->input->post('reqStatusTugasIjinBelajar');
			$reqNoSuratIjin=  $this->input->post('reqNoSuratIjin');
			$reqTglSuratIjin= $this->input->post('reqTglSuratIjin');
			$reqGelarTipe=  $this->input->post('reqGelarTipe');
			$reqGelarNamaDepan=  $this->input->post('reqGelarNamaDepan');
			$reqGelarNama=  $this->input->post('reqGelarNama');

			$set->setField('NAMA', $reqNamaSekolah);
			$set->setField('NAMA_FAKULTAS', $reqNamaFakultas);
			$set->setField('PENDIDIKAN_ID', $reqPendidikanId);
		//$set->setField('TANGGAL_STTB', dateToDBCheck($reqTglSttb));
			$set->setField('TANGGAL_STTB', dateToDBCheck("00-00-0000"));
			$set->setField('JURUSAN', $reqJurusan);
			$set->setField('PENDIDIKAN_JURUSAN_ID', ValToNullDB($reqJurusanId));
			$set->setField('TEMPAT', $reqAlamatSekolah);
			$set->setField('KEPALA', $reqKepalaSekolah);
			$set->setField('NO_STTB', $reqNoSttb);
			$set->setField('STATUS_PENDIDIKAN', $reqStatusPendidikan);
			$set->setField('STATUS_TUGAS_IJIN_BELAJAR', $reqStatusTugasIjinBelajar);
			$set->setField('NO_SURAT_IJIN', $reqNoSuratIjin);
			$set->setField('TANGGAL_SURAT_IJIN', dateToDBCheck($reqTglSuratIjin));
			$set->setField('GELAR_TIPE', $reqGelarTipe);
			$set->setField('GELAR_DEPAN', $reqGelarNamaDepan);
			$set->setField('GELAR_NAMA', $reqGelarNama);
			$set->setField('PEGAWAI_ID', $reqPegawaiId);
			$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
			$set->setField("LAST_USER", $this->LOGIN_USER);
			$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
			$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			$set->setField("LAST_DATE", "NOW()");

			$tempSimpan="";
			if($reqMode == "insert")
			{
				if($set->insertPendidikanUsulan())
				{
					$reqRowDetilId= $set->id;
					$tempSimpan=1;
				//echo $reqRowId."-Data berhasil disimpan.";
				}
			}
			else
			{
				$set->setField('PENDIDIKAN_RIWAYAT_ID', $reqRowDetilId);
				if($set->updatePendidikanUsulan())
				{
					echo $reqRowId."-Data berhasil disimpan.";
				}
				else
					echo "xxx-Data gagal disimpan.";
			}

			if($tempSimpan == 1)
			{
				$reqPendidikanRiwayatSekarangId= $reqRowDetilId;
			//echo $reqPendidikanRiwayatSekarangId;exit;
				$set = new SuratMasukPegawai();
				$set->setField("JENIS_ID", $reqJenis);
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
				$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
				$set->setField("LAST_USER", $this->LOGIN_USER);
				$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
				$set->setField("LAST_DATE", "NOW()");

				if($set->insert()){
					$reqRowId= $set->id;
					unset($set);
					echo $reqRowId."-Data berhasil disimpan.";
				} else {
					echo "xxx-Data gagal disimpan.";
				}
			}
		}

		function add_pendidikan_dinas()
		{
			$this->load->model('persuratan/SuratMasukPegawai');

			$set = new SuratMasukPegawai();

			$reqId= $this->input->post("reqId");
			$reqJenis= $this->input->post("reqJenis");
			$reqRowId= $this->input->post("reqRowId");
			$reqRowDetilId= $this->input->post("reqRowDetilId");
			$reqPegawaiId= $this->input->post("reqPegawaiId");
			$reqMode= $this->input->post("reqMode");
			$reqJabatanRiwayatAkhirId= $this->input->post("reqJabatanRiwayatAkhirId");
			$reqPendidikanRiwayatAkhirId= $this->input->post("reqPendidikanRiwayatAkhirId");
			$reqGajiRiwayatAkhirId= $this->input->post("reqGajiRiwayatAkhirId");
			$reqPangkatRiwayatAkhirId= $this->input->post("reqPangkatRiwayatAkhirId");
			$reqSatuanKerjaPegawaiUsulanId= $this->input->post("reqSatuanKerjaPegawaiUsulanId");

			$reqNamaSekolah=  $this->input->post('reqNamaSekolah');
			$reqNamaFakultas=  $this->input->post('reqNamaFakultas');
			$reqPendidikanId=  $this->input->post('reqPendidikanId');
			$reqTglSttb= $this->input->post('reqTglSttb');
			$reqJurusan=  $this->input->post('reqJurusan');
			$reqJurusanId=  $this->input->post('reqJurusanId');
			$reqAlamatSekolah=  $this->input->post('reqAlamatSekolah');
			$reqKepalaSekolah=  $this->input->post('reqKepalaSekolah');
			$reqNoSttb=  $this->input->post('reqNoSttb');
			$reqStatusPendidikan=  $this->input->post('reqStatusPendidikan');
			$reqStatusTugasIjinBelajar=  $this->input->post('reqStatusTugasIjinBelajar');
			$reqNoSuratIjin=  $this->input->post('reqNoSuratIjin');
			$reqTglSuratIjin= $this->input->post('reqTglSuratIjin');
			$reqGelarTipe=  $this->input->post('reqGelarTipe');
			$reqGelarNamaDepan=  $this->input->post('reqGelarNamaDepan');
			$reqGelarNama=  $this->input->post('reqGelarNama');

			$set->setField('NAMA', $reqNamaSekolah);
			$set->setField('NAMA_FAKULTAS', $reqNamaFakultas);
			$set->setField('PENDIDIKAN_ID', $reqPendidikanId);
		//$set->setField('TANGGAL_STTB', dateToDBCheck($reqTglSttb));
			$set->setField('TANGGAL_STTB', dateToDBCheck("00-00-0000"));
			$set->setField('JURUSAN', $reqJurusan);
			$set->setField('PENDIDIKAN_JURUSAN_ID', ValToNullDB($reqJurusanId));
			$set->setField('TEMPAT', $reqAlamatSekolah);
			$set->setField('KEPALA', $reqKepalaSekolah);
			$set->setField('NO_STTB', $reqNoSttb);
			$set->setField('STATUS_PENDIDIKAN', $reqStatusPendidikan);
			$set->setField('STATUS_TUGAS_IJIN_BELAJAR', $reqStatusTugasIjinBelajar);
			$set->setField('NO_SURAT_IJIN', $reqNoSuratIjin);
			$set->setField('TANGGAL_SURAT_IJIN', dateToDBCheck($reqTglSuratIjin));
			$set->setField('GELAR_TIPE', $reqGelarTipe);
			$set->setField('GELAR_DEPAN', $reqGelarNamaDepan);
			$set->setField('GELAR_NAMA', $reqGelarNama);
			$set->setField('PEGAWAI_ID', $reqPegawaiId);
			$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
			$set->setField("LAST_USER", $this->LOGIN_USER);
			$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
			$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			$set->setField("LAST_DATE", "NOW()");

			$tempSimpan="";
			if($reqMode == "insert")
			{
				if($set->insertPendidikanUsulan())
				{
					$reqRowDetilId= $set->id;
					$tempSimpan=1;
				//echo $reqRowId."-Data berhasil disimpan.";
				}
			}
			else
			{
				$set->setField('PENDIDIKAN_RIWAYAT_ID', $reqRowDetilId);
				if($set->updatePendidikanUsulan())
				{
					echo $reqRowDetilId."-Data berhasil disimpan.";
				}
				else
					echo $reqRowDetilId."-Data gagal disimpan.";
			}

			if($tempSimpan == 1)
			{
				$reqPendidikanRiwayatSekarangId= $reqRowDetilId;
			//echo $reqPendidikanRiwayatSekarangId;exit;
				$set = new SuratMasukPegawai();
				$set->setField("JENIS_ID", $reqJenis);
				$set->setField("SURAT_MASUK_BKD_ID", ValToNullDB($reqId));
				$set->setField("SURAT_MASUK_UPT_ID", ValToNullDB($req));
				$set->setField("STATUS_BERKAS", "4");
				$set->setField("PEGAWAI_ID", $reqPegawaiId);
				$set->setField("JABATAN_RIWAYAT_AKHIR_ID", ValToNullDB($reqJabatanRiwayatAkhirId));
				$set->setField("JABATAN_RIWAYAT_SEKARANG_ID", ValToNullDB($req));
				$set->setField("PENDIDIKAN_RIWAYAT_AKHIR_ID", ValToNullDB($reqPendidikanRiwayatAkhirId));
				$set->setField("PENDIDIKAN_RIWAYAT_SEKARANG_ID", ValToNullDB($reqPendidikanRiwayatSekarangId));
				$set->setField("GAJI_RIWAYAT_AKHIR_ID", ValToNullDB($reqGajiRiwayatAkhirId));
				$set->setField("GAJI_RIWAYAT_SEKARANG_ID", ValToNullDB($req));
				$set->setField("PANGKAT_RIWAYAT_AKHIR_ID", ValToNullDB($reqPangkatRiwayatAkhirId));
				$set->setField("PANGKAT_RIWAYAT_SEKARANG_ID", ValToNullDB($req));
				$set->setField("SATUAN_KERJA_PEGAWAI_USULAN_ID", $reqSatuanKerjaPegawaiUsulanId);
				$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
				$set->setField("LAST_USER", $this->LOGIN_USER);
				$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
				$set->setField("LAST_DATE", "NOW()");

				if($set->insert()){
					$reqRowId= $set->id;
					unset($set);
					echo $reqRowId."-Data berhasil disimpan.";
				} else {
					echo "xxx-Data gagal disimpan.";
				}
			}
		}
		
		function add_pensiun()
		{
			$this->load->model('persuratan/SuratMasukPegawai');

			$set = new SuratMasukPegawai();

			$reqId= $this->input->post("reqId");
			$reqJenis= $this->input->post("reqJenis");
			$reqRowId= $this->input->post("reqRowId");
			$reqRowDetilId= $this->input->post("reqRowDetilId");
			$reqPegawaiId= $this->input->post("reqPegawaiId");
			$reqMode= $this->input->post("reqMode");
			$reqJabatanRiwayatAkhirId= $this->input->post("reqJabatanRiwayatAkhirId");
			$reqPendidikanRiwayatAkhirId= $this->input->post("reqPendidikanRiwayatAkhirId");
			$reqGajiRiwayatAkhirId= $this->input->post("reqGajiRiwayatAkhirId");
			$reqPangkatRiwayatAkhirId= $this->input->post("reqPangkatRiwayatAkhirId");
			$reqSatuanKerjaPegawaiUsulanId= $this->input->post("reqSatuanKerjaPegawaiUsulanId");

			$reqKategori= $this->input->post("reqKategori");
			$reqAnakId= $this->input->post("reqAnakId");
			$reqKeteranganPensiun= $this->input->post("reqKeteranganPensiun");
			// print_r($reqAnakId);exit;

			$set = new SuratMasukPegawai();
			$set->setField("JENIS_ID", $reqJenis);
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
			$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
			$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			$set->setField("LAST_DATE", "NOW()");

			if($set->insertPensiun())
			{
				$tempSimpan=1;
				$reqRowId= $set->id;
			}

			if($tempSimpan == 1)
			{
				for($i=0; $i < count($reqAnakId); $i++)
				{
					$set_detil= new SuratMasukPegawai();
					$set_detil->setField("SURAT_MASUK_PEGAWAI_ID", $reqRowId);
					$set_detil->setField("JENIS_ID", $reqJenis);
					$set_detil->setField("KATEGORI", $reqKategori);
					$set_detil->setField("SURAT_MASUK_BKD_ID", ValToNullDB($req));
					$set_detil->setField("SURAT_MASUK_UPT_ID", ValToNullDB($reqId));
					$set_detil->setField("PEGAWAI_ID", $reqPegawaiId);
					$set_detil->setField("ANAK_ID", $reqAnakId[$i]);
					$set_detil->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
					$set_detil->setField("LAST_USER", $this->LOGIN_USER);
					$set_detil->setField("USER_LOGIN_ID", $this->LOGIN_ID);
					$set_detil->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
					$set_detil->setField("LAST_DATE", "NOW()");
					$set_detil->insertPensiunAnak();
				}
				echo $reqRowId."-Data berhasil disimpan.";
			}
			else 
			{
				echo "xxx-Data gagal disimpan.";
			}
		}

		function add_pensiun_dinas()
		{
			$this->load->model('persuratan/SuratMasukPegawai');

			$set = new SuratMasukPegawai();

			$reqId= $this->input->post("reqId");
			$reqJenis= $this->input->post("reqJenis");
			$reqRowId= $this->input->post("reqRowId");
			$reqRowDetilId= $this->input->post("reqRowDetilId");
			$reqPegawaiId= $this->input->post("reqPegawaiId");
			$reqMode= $this->input->post("reqMode");
			$reqJabatanRiwayatAkhirId= $this->input->post("reqJabatanRiwayatAkhirId");
			$reqPendidikanRiwayatAkhirId= $this->input->post("reqPendidikanRiwayatAkhirId");
			$reqGajiRiwayatAkhirId= $this->input->post("reqGajiRiwayatAkhirId");
			$reqPangkatRiwayatAkhirId= $this->input->post("reqPangkatRiwayatAkhirId");
			$reqSatuanKerjaPegawaiUsulanId= $this->input->post("reqSatuanKerjaPegawaiUsulanId");

			$reqKategori= $this->input->post("reqKategori");
			$reqAnakId= $this->input->post("reqAnakId");
			$reqKeteranganPensiun= $this->input->post("reqKeteranganPensiun");
			// print_r($reqAnakId);exit;

			$set = new SuratMasukPegawai();
			$set->setField("JENIS_ID", $reqJenis);
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
			$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
			$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			$set->setField("LAST_DATE", "NOW()");

			if($set->insertPensiun())
			{
				$tempSimpan=1;
				$reqRowId= $set->id;
			}

			if($tempSimpan == 1)
			{
				for($i=0; $i < count($reqAnakId); $i++)
				{
					$set_detil= new SuratMasukPegawai();
					$set_detil->setField("SURAT_MASUK_PEGAWAI_ID", $reqRowId);
					$set_detil->setField("JENIS_ID", $reqJenis);
					$set_detil->setField("KATEGORI", $reqKategori);
					$set_detil->setField("SURAT_MASUK_BKD_ID", ValToNullDB($reqId));
					$set_detil->setField("SURAT_MASUK_UPT_ID", ValToNullDB($req));
					$set_detil->setField("PEGAWAI_ID", $reqPegawaiId);
					$set_detil->setField("ANAK_ID", $reqAnakId[$i]);
					$set_detil->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
					$set_detil->setField("LAST_USER", $this->LOGIN_USER);
					$set_detil->setField("USER_LOGIN_ID", $this->LOGIN_ID);
					$set_detil->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
					$set_detil->setField("LAST_DATE", "NOW()");
					$set_detil->insertPensiunAnak();
				}
				echo $reqRowId."-Data berhasil disimpan.";
			}
			else 
			{
				echo "xxx-Data gagal disimpan.";
			}
		}

		function add_karpeg()
		{
			$this->load->model('persuratan/SuratMasukPegawai');

			$set = new SuratMasukPegawai();

			$reqId= $this->input->post("reqId");
			$reqJenis= $this->input->post("reqJenis");
			$reqRowId= $this->input->post("reqRowId");
			$reqRowDetilId= $this->input->post("reqRowDetilId");
			$reqPegawaiId= $this->input->post("reqPegawaiId");
			$reqMode= $this->input->post("reqMode");
			$reqJabatanRiwayatAkhirId= $this->input->post("reqJabatanRiwayatAkhirId");
			$reqPendidikanRiwayatAkhirId= $this->input->post("reqPendidikanRiwayatAkhirId");
			$reqGajiRiwayatAkhirId= $this->input->post("reqGajiRiwayatAkhirId");
			$reqPangkatRiwayatAkhirId= $this->input->post("reqPangkatRiwayatAkhirId");
			$reqSatuanKerjaPegawaiUsulanId= $this->input->post("reqSatuanKerjaPegawaiUsulanId");

			$reqJenisKarpeg= $this->input->post("reqJenisKarpeg");
			$reqNoSuratKehilangan= $this->input->post("reqNoSuratKehilangan");
			$reqTanggalSuratKehilangan= $this->input->post("reqTanggalSuratKehilangan");
			$reqKeterangan= $this->input->post("reqKeterangan");
			
			$set->setField("JENIS_ID", $reqJenis);
			$set->setField("JENIS_KARPEG", $reqJenisKarpeg);
			$set->setField("SURAT_MASUK_BKD_ID", ValToNullDB($req));
			$set->setField("SURAT_MASUK_UPT_ID", ValToNullDB($reqId));
			$set->setField('TANGGAL_SURAT_KEHILANGAN', dateToDBCheck($reqTanggalSuratKehilangan));
			$set->setField("NO_SURAT_KEHILANGAN", $reqNoSuratKehilangan);
			$set->setField("KETERANGAN", $reqKeterangan);
			$set->setField('PEGAWAI_ID', $reqPegawaiId);
			$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
			$set->setField("LAST_USER", $this->LOGIN_USER);
			$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
			$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			$set->setField("LAST_DATE", "NOW()");

			$tempSimpan="";
			if($reqMode == "insert")
			{
				if($set->insertKarpegUsulan())
				{
					$reqRowDetilId= $set->id;
					$tempSimpan=1;
				//echo $reqRowId."-Data berhasil disimpan.";
				}
			}
			else
			{
				$set->setField('SURAT_MASUK_KARPEG_ID', $reqRowDetilId);
				if($set->updateKarpegUsulan())
				{
					echo $reqRowId."-Data berhasil disimpan.";
				}
				else
					echo "xxx-Data gagal disimpan.";
			}
			//echo $set->query;exit;

			if($tempSimpan == 1)
			{
				$reqSuratMasukKarpegId= $reqRowDetilId;
			//echo $reqPendidikanRiwayatSekarangId;exit;
				$set = new SuratMasukPegawai();
				$set->setField("JENIS_ID", $reqJenis);
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
				$set->setField("SURAT_MASUK_KARPEG_ID", ValToNullDB($reqSuratMasukKarpegId));
				$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
				$set->setField("LAST_USER", $this->LOGIN_USER);
				$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
				$set->setField("LAST_DATE", "NOW()");

				if($set->insertKarpeg()){
					$reqRowId= $set->id;
					unset($set);
					echo $reqRowId."-Data berhasil disimpan.";
				} else {
					echo "xxx-Data gagal disimpan.";
				}
			}
		}
		
		function add_karpeg_dinas()
		{
			$this->load->model('persuratan/SuratMasukPegawai');

			$set = new SuratMasukPegawai();

			$reqId= $this->input->post("reqId");
			$reqJenis= $this->input->post("reqJenis");
			$reqRowId= $this->input->post("reqRowId");
			$reqRowDetilId= $this->input->post("reqRowDetilId");
			$reqPegawaiId= $this->input->post("reqPegawaiId");
			$reqMode= $this->input->post("reqMode");
			$reqJabatanRiwayatAkhirId= $this->input->post("reqJabatanRiwayatAkhirId");
			$reqPendidikanRiwayatAkhirId= $this->input->post("reqPendidikanRiwayatAkhirId");
			$reqGajiRiwayatAkhirId= $this->input->post("reqGajiRiwayatAkhirId");
			$reqPangkatRiwayatAkhirId= $this->input->post("reqPangkatRiwayatAkhirId");
			$reqSatuanKerjaPegawaiUsulanId= $this->input->post("reqSatuanKerjaPegawaiUsulanId");

			$reqJenisKarpeg= $this->input->post("reqJenisKarpeg");
			$reqNoSuratKehilangan= $this->input->post("reqNoSuratKehilangan");
			$reqTanggalSuratKehilangan= $this->input->post("reqTanggalSuratKehilangan");
			$reqKeterangan= $this->input->post("reqKeterangan");
			
			$set->setField("JENIS_ID", $reqJenis);
			$set->setField("JENIS_KARPEG", $reqJenisKarpeg);
			$set->setField("SURAT_MASUK_BKD_ID", ValToNullDB($reqId));
			$set->setField("SURAT_MASUK_UPT_ID", ValToNullDB($req));
			$set->setField('TANGGAL_SURAT_KEHILANGAN', dateToDBCheck($reqTanggalSuratKehilangan));
			$set->setField("NO_SURAT_KEHILANGAN", $reqNoSuratKehilangan);
			$set->setField("KETERANGAN", $reqKeterangan);
			$set->setField('PEGAWAI_ID', $reqPegawaiId);
			$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
			$set->setField("LAST_USER", $this->LOGIN_USER);
			$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
			$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			$set->setField("LAST_DATE", "NOW()");

			$tempSimpan="";
			if($reqMode == "insert")
			{
				if($set->insertKarpegUsulan())
				{
					$reqRowDetilId= $set->id;
					$tempSimpan=1;
				//echo $reqRowId."-Data berhasil disimpan.";
				}
			}
			else
			{
				$set->setField('SURAT_MASUK_KARPEG_ID', $reqRowDetilId);
				if($set->updateKarpegUsulan())
				{
					echo $reqRowId."-Data berhasil disimpan.";
				}
				else
					echo "xxx-Data gagal disimpan.";
			}
			//echo $set->query;exit;

			if($tempSimpan == 1)
			{
				$reqSuratMasukKarpegId= $reqRowDetilId;
			//echo $reqPendidikanRiwayatSekarangId;exit;
				$set = new SuratMasukPegawai();
				$set->setField("JENIS_ID", $reqJenis);
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
				$set->setField("SURAT_MASUK_KARPEG_ID", ValToNullDB($reqSuratMasukKarpegId));
				$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
				$set->setField("LAST_USER", $this->LOGIN_USER);
				$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
				$set->setField("LAST_DATE", "NOW()");

				if($set->insertKarpeg()){
					$reqRowId= $set->id;
					unset($set);
					echo $reqRowId."-Data berhasil disimpan.";
				} else {
					echo "xxx-Data gagal disimpan.";
				}
			}
		}

		function add_karsu()
		{
			$this->load->model('persuratan/SuratMasukPegawai');

			$set = new SuratMasukPegawai();

			$reqId= $this->input->post("reqId");
			$reqJenis= $this->input->post("reqJenis");
			$reqRowId= $this->input->post("reqRowId");
			$reqRowDetilId= $this->input->post("reqRowDetilId");
			$reqPegawaiId= $this->input->post("reqPegawaiId");
			$reqMode= $this->input->post("reqMode");
			$reqJabatanRiwayatAkhirId= $this->input->post("reqJabatanRiwayatAkhirId");
			$reqPendidikanRiwayatAkhirId= $this->input->post("reqPendidikanRiwayatAkhirId");
			$reqGajiRiwayatAkhirId= $this->input->post("reqGajiRiwayatAkhirId");
			$reqPangkatRiwayatAkhirId= $this->input->post("reqPangkatRiwayatAkhirId");
			$reqSatuanKerjaPegawaiUsulanId= $this->input->post("reqSatuanKerjaPegawaiUsulanId");

			$reqSuamiIstriTerakhirId= $this->input->post("reqSuamiIstriTerakhirId");
			$reqSuamiIstriPisahTerakhirId= $this->input->post("reqSuamiIstriPisahTerakhirId");
			$reqSuamiIstriTerakhirPernikahanPertamaPnsStatus= $this->input->post("reqSuamiIstriTerakhirPernikahanPertamaPnsStatus");
			$reqSuamiIstriTerakhirPernikahanPertamaPnsSi= $this->input->post("reqSuamiIstriTerakhirPernikahanPertamaPnsSi");
			$reqSuamiIstriTerakhirPernikahanPertamaPnsTanggal= $this->input->post("reqSuamiIstriTerakhirPernikahanPertamaPnsTanggal");
			$reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus= $this->input->post("reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus");
			$reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatus= $this->input->post("reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatus");
			$reqSuamiIstriTerakhirPernikahanPertamaPasanganTanggal= $this->input->post("reqSuamiIstriTerakhirPernikahanPertamaPasanganTanggal");
			$reqJenisKesalahan= $this->input->post("reqJenisKesalahan");
			$reqTertulis= $this->input->post("reqTertulis");
			$reqSeharusnya= $this->input->post("reqSeharusnya");
			$reqSS= $this->input->post("reqSS");


			// echo $reqSuamiIstriTerakhirPernikahanPertamaPasanganTanggal;exit();

			$reqJenisKarpeg= $this->input->post("reqJenisKarpeg");
			// echo $reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatus;exit();

			$reqNoSuratKehilangan= $this->input->post("reqNoSuratKehilangan");
			$reqTanggalSuratKehilangan= $this->input->post("reqTanggalSuratKehilangan");
			$reqKeterangan= $this->input->post("reqKeterangan");
			
			$set->setField("JENIS_ID", $reqJenis);
			$set->setField("JENIS_KARSU", $reqJenisKarpeg);
			$set->setField("SURAT_MASUK_BKD_ID", ValToNullDB($req));
			$set->setField("SURAT_MASUK_UPT_ID", ValToNullDB($reqId));
			$set->setField('TANGGAL_SURAT_KEHILANGAN', dateToDBCheck($reqTanggalSuratKehilangan));
			$set->setField("NO_SURAT_KEHILANGAN", $reqNoSuratKehilangan);
			$set->setField("KETERANGAN", $reqKeterangan);
			$set->setField('PEGAWAI_ID', $reqPegawaiId);

			$set->setField('SUAMI_ISTRI_ID', $reqSuamiIstriTerakhirId);
			$set->setField('SUAMI_ISTRI_PISAH_ID', ValToNullDB($reqSuamiIstriPisahTerakhirId));

			$set->setField('NIKAH_PERTAMA_PNS_STATUS', ValToNullDB($reqSuamiIstriTerakhirPernikahanPertamaPnsStatus));
			$set->setField('NIKAH_PERTAMA_PNS_STATUS_SI', ValToNullDB($reqSuamiIstriTerakhirPernikahanPertamaPnsSi));
			$set->setField('NIKAH_PERTAMA_PNS_TANGGAL', dateToDBCheck($reqSuamiIstriTerakhirPernikahanPertamaPnsTanggal));
			$set->setField('NIKAH_PERTAMA_PASANGAN_STATUS', ValToNullDB($reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus));
			$set->setField('NIKAH_PERTAMA_PASANGAN_STATUS_SI', ValToNullDB($reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatus));
			$set->setField('NIKAH_PERTAMA_PASANGAN_TANGGAL', dateToDBCheck($reqSuamiIstriTerakhirPernikahanPertamaPasanganTanggal));
			$set->setField('JENIS_KESALAHAN', $reqJenisKesalahan);
			$set->setField('TERTULIS', $reqTertulis);
			$set->setField('SEHARUSNYA', $reqSeharusnya);

			$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
			$set->setField("LAST_USER", $this->LOGIN_USER);
			$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
			$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			$set->setField("LAST_DATE", "NOW()");

			$tempSimpan="";
			if($reqMode == "insert")
			{
				if($set->insertKarsuUsulan())
				{
					$reqRowDetilId= $set->id;
					$tempSimpan=1;
				//echo $reqRowId."-Data berhasil disimpan.";
				}
			}
			else
			{
				$set->setField('SURAT_MASUK_KARSU_ID', $reqRowDetilId);
				if($set->updateKarsuUsulan())
				{
					echo $reqRowId."-Data berhasil disimpan.";
				}
				else
					echo "xxx-Data gagal disimpan.";
			}
			//echo $set->query;exit;

			if($tempSimpan == 1)
			{
				$reqSuratMasukKarpegId= $reqRowDetilId;
			//echo $reqPendidikanRiwayatSekarangId;exit;
				$set = new SuratMasukPegawai();
				$set->setField("JENIS_ID", $reqJenis);
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
				$set->setField("SURAT_MASUK_KARSU_ID", ValToNullDB($reqSuratMasukKarpegId));
				$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
				$set->setField("LAST_USER", $this->LOGIN_USER);
				$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
				$set->setField("LAST_DATE", "NOW()");

				if($set->insertKarsu()){
					$reqRowId= $set->id;
					unset($set);
					echo $reqRowId."-Data berhasil disimpan.";
				} else {
					echo "xxx-Data gagal disimpan.";
				}
			}

			// echo "xxx-Data percobaan.";
		}
		
		function add_karsu_dinas()
		{
			$this->load->model('persuratan/SuratMasukPegawai');

			$set = new SuratMasukPegawai();

			$reqId= $this->input->post("reqId");
			$reqJenis= $this->input->post("reqJenis");
			$reqRowId= $this->input->post("reqRowId");
			$reqRowDetilId= $this->input->post("reqRowDetilId");
			$reqPegawaiId= $this->input->post("reqPegawaiId");
			$reqMode= $this->input->post("reqMode");
			$reqJabatanRiwayatAkhirId= $this->input->post("reqJabatanRiwayatAkhirId");
			$reqPendidikanRiwayatAkhirId= $this->input->post("reqPendidikanRiwayatAkhirId");
			$reqGajiRiwayatAkhirId= $this->input->post("reqGajiRiwayatAkhirId");
			$reqPangkatRiwayatAkhirId= $this->input->post("reqPangkatRiwayatAkhirId");
			$reqSatuanKerjaPegawaiUsulanId= $this->input->post("reqSatuanKerjaPegawaiUsulanId");

			$reqSuamiIstriTerakhirId= $this->input->post("reqSuamiIstriTerakhirId");
			$reqSuamiIstriPisahTerakhirId= $this->input->post("reqSuamiIstriPisahTerakhirId");

			$reqSuamiIstriTerakhirPernikahanPertamaPnsStatus= $this->input->post("reqSuamiIstriTerakhirPernikahanPertamaPnsStatus");
			$reqSuamiIstriTerakhirPernikahanPertamaPnsSi= $this->input->post("reqSuamiIstriTerakhirPernikahanPertamaPnsSi");
			$reqSuamiIstriTerakhirPernikahanPertamaPnsTanggal= $this->input->post("reqSuamiIstriTerakhirPernikahanPertamaPnsTanggal");
			$reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus= $this->input->post("reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus");
			$reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatus= $this->input->post("reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatus");
			$reqSuamiIstriTerakhirPernikahanPertamaPasanganTanggal= $this->input->post("reqSuamiIstriTerakhirPernikahanPertamaPasanganTanggal");
			$reqJenisKesalahan= $this->input->post("reqJenisKesalahan");
			$reqTertulis= $this->input->post("reqTertulis");
			$reqSeharusnya= $this->input->post("reqSeharusnya");
			$reqSS= $this->input->post("reqSS");


			// echo $reqSuamiIstriTerakhirPernikahanPertamaPasanganTanggal;exit();

			$reqJenisKarpeg= $this->input->post("reqJenisKarpeg");
			// echo $reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatus;exit();
			
			$reqNoSuratKehilangan= $this->input->post("reqNoSuratKehilangan");
			$reqTanggalSuratKehilangan= $this->input->post("reqTanggalSuratKehilangan");
			$reqKeterangan= $this->input->post("reqKeterangan");
			
			$set->setField("JENIS_ID", $reqJenis);
			$set->setField("JENIS_KARSU", $reqJenisKarpeg);
			$set->setField("SURAT_MASUK_BKD_ID", ValToNullDB($reqId));
			$set->setField("SURAT_MASUK_UPT_ID", ValToNullDB($req));
			$set->setField('TANGGAL_SURAT_KEHILANGAN', dateToDBCheck($reqTanggalSuratKehilangan));
			$set->setField("NO_SURAT_KEHILANGAN", $reqNoSuratKehilangan);
			$set->setField("KETERANGAN", $reqKeterangan);
			$set->setField('PEGAWAI_ID', $reqPegawaiId);

			$set->setField('SUAMI_ISTRI_ID', $reqSuamiIstriTerakhirId);
			$set->setField('SUAMI_ISTRI_PISAH_ID', ValToNullDB($reqSuamiIstriPisahTerakhirId));
			
			$set->setField('NIKAH_PERTAMA_PNS_STATUS', ValToNullDB($reqSuamiIstriTerakhirPernikahanPertamaPnsStatus));
			$set->setField('NIKAH_PERTAMA_PNS_STATUS_SI', ValToNullDB($reqSuamiIstriTerakhirPernikahanPertamaPnsSi));
			$set->setField('NIKAH_PERTAMA_PNS_TANGGAL', dateToDBCheck($reqSuamiIstriTerakhirPernikahanPertamaPnsTanggal));
			$set->setField('NIKAH_PERTAMA_PASANGAN_STATUS', ValToNullDB($reqSuamiIstriTerakhirPernikahanPertamaPasanganStatus));
			$set->setField('NIKAH_PERTAMA_PASANGAN_STATUS_SI', ValToNullDB($reqSuamiIstriTerakhirPernikahanPertamaPasanganSiStatus));
			$set->setField('NIKAH_PERTAMA_PASANGAN_TANGGAL', dateToDBCheck($reqSuamiIstriTerakhirPernikahanPertamaPasanganTanggal));
			$set->setField('JENIS_KESALAHAN', $reqJenisKesalahan);
			$set->setField('TERTULIS', $reqTertulis);
			$set->setField('SEHARUSNYA', $reqSeharusnya);

			$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
			$set->setField("LAST_USER", $this->LOGIN_USER);
			$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
			$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			$set->setField("LAST_DATE", "NOW()");

			$tempSimpan="";
			if($reqMode == "insert")
			{
				if($set->insertKarsuUsulan())
				{
					$reqRowDetilId= $set->id;
					$tempSimpan=1;
				//echo $reqRowId."-Data berhasil disimpan.";
				}
			}
			else
			{
				$set->setField('SURAT_MASUK_KARSU_ID', $reqRowDetilId);
				if($set->updateKarsuUsulan())
				{
					echo $reqRowId."-Data berhasil disimpan.";
				}
				else
					echo "xxx-Data gagal disimpan.";
			}
			//echo $set->query;exit;

			if($tempSimpan == 1)
			{
				$reqSuratMasukKarpegId= $reqRowDetilId;
			//echo $reqPendidikanRiwayatSekarangId;exit;
				$set = new SuratMasukPegawai();
				$set->setField("JENIS_ID", $reqJenis);
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
				$set->setField("SURAT_MASUK_KARSU_ID", ValToNullDB($reqSuratMasukKarpegId));
				$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
				$set->setField("LAST_USER", $this->LOGIN_USER);
				$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
				$set->setField("LAST_DATE", "NOW()");

				if($set->insertKarsu()){
					$reqRowId= $set->id;
					unset($set);
					echo $reqRowId."-Data berhasil disimpan.";
				} else {
					echo "xxx-Data gagal disimpan.";
				}
			}
			
			// echo "xxx-Data percobaan.";

			// $reqId= $this->input->post("reqId");
			// $reqJenis= $this->input->post("reqJenis");
			// $reqRowId= $this->input->post("reqRowId");
			// $reqRowDetilId= $this->input->post("reqRowDetilId");
			// $reqPegawaiId= $this->input->post("reqPegawaiId");
			// $reqMode= $this->input->post("reqMode");
			// $reqJabatanRiwayatAkhirId= $this->input->post("reqJabatanRiwayatAkhirId");
			// $reqPendidikanRiwayatAkhirId= $this->input->post("reqPendidikanRiwayatAkhirId");
			// $reqGajiRiwayatAkhirId= $this->input->post("reqGajiRiwayatAkhirId");
			// $reqPangkatRiwayatAkhirId= $this->input->post("reqPangkatRiwayatAkhirId");
			// $reqSatuanKerjaPegawaiUsulanId= $this->input->post("reqSatuanKerjaPegawaiUsulanId");

			// $reqJenisKarpeg= $this->input->post("reqJenisKarpeg");
			// $reqNoSuratKehilangan= $this->input->post("reqNoSuratKehilangan");
			// $reqTanggalSuratKehilangan= $this->input->post("reqTanggalSuratKehilangan");
			// $reqKeterangan= $this->input->post("reqKeterangan");
			
			// $set->setField("JENIS_ID", $reqJenis);
			// $set->setField("JENIS_KARSU", $reqJenisKarpeg);
			// $set->setField("SURAT_MASUK_BKD_ID", ValToNullDB($reqId));
			// $set->setField("SURAT_MASUK_UPT_ID", ValToNullDB($req));
			// $set->setField('TANGGAL_SURAT_KEHILANGAN', dateToDBCheck($reqTanggalSuratKehilangan));
			// $set->setField("NO_SURAT_KEHILANGAN", $reqNoSuratKehilangan);
			// $set->setField("KETERANGAN", $reqKeterangan);
			// $set->setField('PEGAWAI_ID', $reqPegawaiId);
			// $set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
			// $set->setField("LAST_USER", $this->LOGIN_USER);
			// $set->setField("LAST_DATE", "NOW()");

			// $tempSimpan="";
			// if($reqMode == "insert")
			// {
			// 	if($set->insertKarpegUsulan())
			// 	{
			// 		$reqRowDetilId= $set->id;
			// 		$tempSimpan=1;
			// 	//echo $reqRowId."-Data berhasil disimpan.";
			// 	}
			// }
			// else
			// {
			// 	$set->setField('SURAT_MASUK_KARSU_ID', $reqRowDetilId);
			// 	if($set->updateKarpegUsulan())
			// 	{
			// 		echo $reqRowId."-Data berhasil disimpan.";
			// 	}
			// 	else
			// 		echo "xxx-Data gagal disimpan.";
			// }
			// //echo $set->query;exit;

			// if($tempSimpan == 1)
			// {
			// 	$reqSuratMasukKarpegId= $reqRowDetilId;
			// //echo $reqPendidikanRiwayatSekarangId;exit;
			// 	$set = new SuratMasukPegawai();
			// 	$set->setField("JENIS_ID", $reqJenis);
			// 	$set->setField("SURAT_MASUK_BKD_ID", ValToNullDB($reqId));
			// 	$set->setField("SURAT_MASUK_UPT_ID", ValToNullDB($req));
			// 	$set->setField("PEGAWAI_ID", $reqPegawaiId);
			// 	$set->setField("STATUS_BERKAS", "4");
			// 	$set->setField("JABATAN_RIWAYAT_AKHIR_ID", ValToNullDB($reqJabatanRiwayatAkhirId));
			// 	$set->setField("JABATAN_RIWAYAT_SEKARANG_ID", ValToNullDB($req));
			// 	$set->setField("PENDIDIKAN_RIWAYAT_AKHIR_ID", ValToNullDB($reqPendidikanRiwayatAkhirId));
			// 	$set->setField("PENDIDIKAN_RIWAYAT_SEKARANG_ID", ValToNullDB($reqPendidikanRiwayatSekarangId));
			// 	$set->setField("GAJI_RIWAYAT_AKHIR_ID", ValToNullDB($reqGajiRiwayatAkhirId));
			// 	$set->setField("GAJI_RIWAYAT_SEKARANG_ID", ValToNullDB($req));
			// 	$set->setField("PANGKAT_RIWAYAT_AKHIR_ID", ValToNullDB($reqPangkatRiwayatAkhirId));
			// 	$set->setField("PANGKAT_RIWAYAT_SEKARANG_ID", ValToNullDB($req));
			// 	$set->setField("SATUAN_KERJA_PEGAWAI_USULAN_ID", $reqSatuanKerjaPegawaiUsulanId);
			// 	$set->setField("SURAT_MASUK_KARPEG_ID", ValToNullDB($reqSuratMasukKarpegId));
			// 	$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
			// 	$set->setField("LAST_USER", $this->LOGIN_USER);
			// 	$set->setField("LAST_DATE", "NOW()");

			// 	if($set->insertKarpeg()){
			// 		$reqRowId= $set->id;
			// 		unset($set);
			// 		echo $reqRowId."-Data berhasil disimpan.";
			// 	} else {
			// 		echo "xxx-Data gagal disimpan.";
			// 	}
			// }
		}

		function add_dinas()
		{
			$this->load->model('persuratan/SuratMasukPegawai');

			$reqId= $this->input->get("reqId");
			$reqJenis= $this->input->get("reqJenis");
			$reqPegawaiId= $this->input->get("reqPegawaiId");
			$arrPegawaiId= explode(",", $reqPegawaiId);

			for($i=0; $i < count($arrPegawaiId); $i++)
			{
				$set = new SuratMasukPegawai();
				$set->setField("JENIS_ID", $reqJenis);
				$set->setField("SURAT_MASUK_BKD_ID", ValToNullDB($reqId));
				$set->setField("SURAT_MASUK_UPT_ID", ValToNullDB($req));
				$set->setField("PEGAWAI_ID", $arrPegawaiId[$i]);
				$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
				$set->insert();
				unset($set);
			}

		}

		function add_dinas_bkd()
		{
			$this->load->model('persuratan/SuratMasukPegawai');

			$reqId= $this->input->get("reqId");
			$reqJenis= $this->input->get("reqJenis");
			$reqJenisUsulan= $this->input->get("reqJenisUsulan");
			$reqPegawaiId= $this->input->get("reqPegawaiId");
			$reqUsulanSuratId= $this->input->get("reqUsulanSuratId");
			$arrPegawaiId= explode(",", $reqPegawaiId);
			//exit;
			for($i=0; $i < count($arrPegawaiId); $i++)
			{
				$tempSuratMasukPegawaiId= explode("-", $arrPegawaiId[$i]);
				$tempSuratMasukPegawaiId= $tempSuratMasukPegawaiId[1];
				$set = new SuratMasukPegawai();
				$set->setField("USULAN_SURAT_ID", ValToNullDB($reqId));
				$set->setField("SURAT_MASUK_PEGAWAI_ID", $tempSuratMasukPegawaiId);
				$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
				$set->setField("LAST_USER", $this->LOGIN_USER);
				$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
				$set->setField("LAST_DATE", "NOW()");
				$set->updateUsulanSurat();
				//echo $set->query;exit;
				unset($set);
			}

		}
		
		function add_dinas_upt()
		{
			$this->load->model('persuratan/SuratMasukPegawai');

			$reqId= $this->input->get("reqId");
			$reqJenis= $this->input->get("reqJenis");
			$reqPegawaiId= $this->input->get("reqPegawaiId");
			$arrPegawaiId= explode(",", $reqPegawaiId);
		//SURAT_MASUK_PEGAWAI_ID
			for($i=0; $i < count($arrPegawaiId); $i++)
			{
				$tempSuratMasukPegawaiId= explode("-", $arrPegawaiId[$i]);
				$tempSuratMasukPegawaiId= $tempSuratMasukPegawaiId[1];
				$set = new SuratMasukPegawai();
				$set->setField("SURAT_MASUK_BKD_ID", ValToNullDB($reqId));
				$set->setField("SURAT_MASUK_PEGAWAI_ID", $tempSuratMasukPegawaiId);
				$set->setField("STATUS_BERKAS", "4");
				$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
				$set->setField("LAST_USER", $this->LOGIN_USER);
				$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
				$set->setField("LAST_DATE", "NOW()");
				$set->updateUpt();
			//echo $set->query;exit;
				unset($set);
			}

		}

		function delete()
		{
			$this->load->model('SuratMasukPegawai');
			$set = new SuratMasukPegawai();

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
			$reqJenis =  $this->input->get('reqJenis');
			
			$set->setField("SURAT_MASUK_PEGAWAI_ID", $reqId);
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
	
	function cek_kirim_upt() 
	{
		$this->load->model('persuratan/SuratMasukUpt');
		$set = new SuratMasukUpt();
		
		$reqId=  $this->input->get('reqId');
		$statement= " AND A.SURAT_MASUK_UPT_ID = ".$reqId;
		$set->selectByParamsSimple(array(), -1,-1,$statement);
		$set->firstRow();
		//echo $set->query;exit;
		$tempStatusKirim= $set->getField("STATUS_KIRIM");
		echo $tempStatusKirim;
	}

	function cek_kirim_bkd() 
	{
		$this->load->model('persuratan/SuratMasukBkd');
		$set = new SuratMasukBkd();
		
		$reqId=  $this->input->get('reqId');
		$statement= " AND A.SURAT_MASUK_BKD_ID = ".$reqId;
		$set->selectByParamsSimple(array(), -1,-1,$statement);
		$set->firstRow();
		//echo $set->query;exit;
		$tempStatusKirim= $set->getField("STATUS_KIRIM");
		echo $tempStatusKirim;
	}

	function total_pegawai_upt() 
	{
		$this->load->model('persuratan/SuratMasukPegawai');
		$set = new SuratMasukPegawai();
		
		$reqId=  $this->input->get('reqId');
		$statement= " AND A.SURAT_MASUK_UPT_ID = ".$reqId;
		$tempData= $set->getCountByParams(array(), $statement);
		$set->selectByParams(array(), -1,-1, $statement);
		// echo $set->query;exit();
		$set->firstRow();
		$tempJenisId= $set->getField("JENIS_ID");
		// echo $tempJenisId;exit();
		
		$statement= " AND SMP.SURAT_MASUK_UPT_ID = ".$reqId;
		if($tempJenisId == "3")
		{
			$set->selectByParamsMonitoringKaris(array(), -1,-1, $statement);
			$set->firstRow();
			// echo $set->query;exit;
			$tempJenisKarpeg= $set->getField("JENIS_KARSU");
		}
		else
		{
			$set->selectByParamsMonitoring(array(), -1,-1, $statement);
			$set->firstRow();
			// echo $set->query;exit;
			$tempJenisKarpeg= $set->getField("JENIS_KARPEG");
		}
		$tempJenis= $set->getField("JENIS_ID");
		// echo $tempJenis;exit();
		
		$requrl= setlinksuratpengantar($tempJenis, $tempJenisKarpeg);
		
		if($tempData > 1 || $tempJenis == "7")
		{
			$requrl= setlinklebihsuratpengantar($tempJenis, $tempJenisKarpeg);

			if($tempJenis == "4")
			$requrl= "karpeg_smpn_dinas_4";
			elseif($tempJenis == "3")
			$requrl= "karsu_smpn_dinas_4";
			
			$requrllist= setlinksuratpengantarlebih($tempJenis, $tempJenisKarpeg);
		}
		
		$requrlcss= setlinksuratpengantarcss($tempJenis, $tempJenisKarpeg);
		
		echo $tempData."-".$requrl."-".$requrllist."-".$requrlcss;
	}
	
	function total_pegawai_bkd() 
	{
		$this->load->model('persuratan/SuratMasukPegawai');
		$set = new SuratMasukPegawai();
		
		$reqId=  $this->input->get('reqId');
		$statement= " AND A.SURAT_MASUK_BKD_ID = ".$reqId;
		$tempData= $set->getCountByParams(array(), $statement);
		$set->selectByParams(array(), -1,-1, $statement);
		// echo $set->query;exit();
		$set->firstRow();
		$tempJenisId= $set->getField("JENIS_ID");
		// echo $tempJenisId;exit();
		
		$statement= " AND SMP.SURAT_MASUK_BKD_ID = ".$reqId;
		if($tempJenisId == "3")
		{
			$set->selectByParamsMonitoringKaris(array(), -1,-1, $statement);
			$set->firstRow();
			// echo $set->query;exit;
			$tempJenisKarpeg= $set->getField("JENIS_KARSU");
		}
		else
		{
			$set->selectByParamsMonitoring(array(), -1,-1, $statement);
			$set->firstRow();
			// echo $set->query;exit;
			$tempJenisKarpeg= $set->getField("JENIS_KARPEG");
		}
		$tempJenis= $set->getField("JENIS_ID");
		// echo $tempData."---".$tempJenis."--".$tempJenisKarpeg;exit();
		
		$requrl= setlinksuratpengantarbkd($tempJenis, $tempJenisKarpeg);
		
		if($tempData > 1 || $tempJenis == "7")
		{
			$requrl= setlinksuratpengantarbkdlebih($tempJenis, $tempJenisKarpeg);
			// echo $requrl;exit();

			if($tempJenis == "4")
			$requrl= "karpeg_smpn_dinas_4";
			elseif($tempJenis == "3")
			$requrl= "karsu_smpn_dinas_4";
			
			$requrllist= setlinksuratpengantarlebihbkd($tempJenis, $tempJenisKarpeg);
		}
		
		$requrlcss= setlinksuratpengantarcssbkd($tempJenis, $tempJenisKarpeg);
		
		echo $tempData."-".$requrl."-".$requrllist."-".$requrlcss;


		// $this->load->model('persuratan/SuratMasukPegawai');
		// $set = new SuratMasukPegawai();
		
		// $reqId=  $this->input->get('reqId');
		// $statement= " AND A.USULAN_SURAT_ID = ".$reqId;
		// $tempData= $set->getCountByParams(array(), $statement);
		// echo $tempData;
	}
	
	function total_pegawai_rekomendasi() 
	{
		$this->load->model('persuratan/SuratMasukPegawai');
		$set = new SuratMasukPegawai();
		
		$reqId=  $this->input->get('reqId');
		$statement= " AND A.SURAT_MASUK_BKD_ID = ".$reqId;
		
		$statement= " AND SMP.SURAT_MASUK_BKD_ID = ".$reqId;
		$set->selectByParamsMonitoring(array(), -1,-1, $statement);
		$set->firstRow();
		//echo $set->query;exit;
		$tempJenisKarpeg= $set->getField("JENIS_KARPEG");
		$tempJenis= $set->getField("JENIS_ID");
		
		$requrl= setjenissuratrekomendasiinfo($tempJenis);
		$requrlcss= "surat_rekomendasi";
		
		echo $tempData."-".$requrl."-".$requrllist."-".$requrlcss;
	}

	function total_pegawai_dinas() 
	{
		$this->load->model('persuratan/SuratMasukPegawai');
		$set = new SuratMasukPegawai();
		
		$reqId=  $this->input->get('reqId');
		$statement= " AND A.SURAT_MASUK_BKD_ID = ".$reqId;
		$tempData= $set->getCountByParams(array(), $statement);
		$set->selectByParams(array(), -1,-1, $statement);
		// echo $set->query;exit();
		$set->firstRow();
		$tempJenisId= $set->getField("JENIS_ID");
		// echo $tempJenisId;exit();

		$statement= " AND SMP.SURAT_MASUK_BKD_ID = ".$reqId;
		if($tempJenisId == "3")
		{
			$set->selectByParamsMonitoringKaris(array(), -1,-1, $statement);
			$set->firstRow();
			// echo $set->query;exit;
			$tempJenisKarpeg= $set->getField("JENIS_KARSU");
		}
		else
		{
			$set->selectByParamsMonitoring(array(), -1,-1, $statement);
			$set->firstRow();
			// echo $set->query;exit;
			$tempJenisKarpeg= $set->getField("JENIS_KARPEG");
		}
		$tempJenis= $set->getField("JENIS_ID");
		// echo $tempJenis;exit();
		
		$requrl= setlinksuratpengantarbkd($tempJenis, $tempJenisKarpeg);
		
		if($tempData > 1)
		{
			$requrl= setlinksuratpengantarbkdlebih($tempJenis, $tempJenisKarpeg);

			if($tempJenis == "4")
			$requrl= "karpeg_smpn_dinas_4";
			elseif($tempJenis == "3")
			$requrl= "karsu_smpn_dinas_4";
			
			$requrllist= setlinksuratpengantarlebihbkd($tempJenis, $tempJenisKarpeg);
		}
		
		$requrlcss= setlinksuratpengantarcssbkd($tempJenis, $tempJenisKarpeg);
		
		echo $tempData."-".$requrl."-".$requrllist."-".$requrlcss;
	}
	
	function total_pegawai_usulan() 
	{
		$this->load->model('persuratan/SuratMasukPegawai');
		$set = new SuratMasukPegawai();
		
		$reqId=  $this->input->get('reqId');
		$statement= " AND A.USULAN_SURAT_ID = ".$reqId;
		$tempData= $set->getCountByParams(array(), $statement);
		
		$statement= " AND A.USULAN_SURAT_ID = ".$reqId;
		$set->selectByParams(array(), -1,-1, $statement);
		// echo $set->query;exit();
		$set->firstRow();
		$tempJenisId= $set->getField("JENIS_ID");

		$statement= " AND SMP.USULAN_SURAT_ID = ".$reqId;
		if($tempJenisId == "3")
		{
			$set->selectByParamsMonitoringKaris(array(), -1,-1, $statement);
			$set->firstRow();
			// echo $set->query;exit;
			$tempJenisKarpeg= $set->getField("JENIS_KARSU");
		}
		else
		{
			$set->selectByParamsMonitoring(array(), -1,-1, $statement);
			$set->firstRow();
			// echo $set->query;exit;
			$tempJenisKarpeg= $set->getField("JENIS_KARPEG");
		}
		$tempJenis= $set->getField("JENIS_ID");
		// echo $tempJenis;exit();
		
		$requrl= setlinksuratpengantarusulan($tempJenis, $tempJenisKarpeg);
		
		if($tempData > 1 || $tempJenis == "7")
		{
			if($tempJenis == "4")
			$requrl= "karpeg_smpn_dinas_4";
			elseif($tempJenis == "3")
			$requrl= "karsu_smpn_dinas_4";
			
			$requrllist= setlinksuratpengantarlebihusulan($tempJenis, $tempJenisKarpeg);
		}
		
		$requrlcss= setlinksuratpengantarcssusulan($tempJenis, $tempJenisKarpeg);
		
		echo $tempData."-".$requrl."-".$requrllist."-".$requrlcss;
	}

	function status_batal_usul() 
	{
		$this->load->model('persuratan/SuratMasukPegawai');
		$set = new SuratMasukPegawai();
		
		$reqRowId= $this->input->get('reqRowId');
		
		$set = new SuratMasukPegawai();
		$set->setField("USULAN_SURAT_ID", ValToNullDB($req));
		$set->setField("SURAT_MASUK_PEGAWAI_ID", $reqRowId);
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("LAST_DATE", "NOW()");
		$set->updateUsulanSurat();
	}
	
	function btl_surat() 
	{
		$this->load->model('persuratan/SuratMasukPegawai');
		$set = new SuratMasukPegawai();
		
		$reqId= $this->input->get('reqId');
		$statement= " AND SMP.SURAT_MASUK_PEGAWAI_ID = ".$reqId;
		$set= new SuratMasukPegawai();
		$set->selectByParamsMonitoring(array(), -1, -1, $statement);
		$set->firstRow();
		//echo $set->query;exit;
		$reqStatusSuratKeluar= $set->getField('STATUS_SURAT_KELUAR');
		echo $reqStatusSuratKeluar;
	}	
}
?>