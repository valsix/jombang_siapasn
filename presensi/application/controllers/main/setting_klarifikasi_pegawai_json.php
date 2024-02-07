<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class setting_klarifikasi_pegawai_json extends CI_Controller {

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
	}	

	function json()
	{
		$this->load->model('main/SettingKlarifikasiPegawai');
		$set= new SettingKlarifikasiPegawai;
		
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqStatusIntegrasi= $this->input->get("reqStatusIntegrasi");
		$reqPencarian= $this->input->get("reqPencarian");
		$reqCheckId= $this->input->get("reqCheckId");
		$arrayCheckId= explode(',', $reqCheckId);

		$aColumns = array("CHECK", "NIP_NAMA", "SATUAN_KERJA_DETIL", "JABATAN_RIWAYAT_NAMA", "EMP_CODE");
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
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY CHECK asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.TMT_PANGKAT ASC";
				 
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

		$statement= "
		AND EXISTS
		(
			SELECT 1
			FROM
			(
				SELECT PEGAWAI_ID FROM presensi.SETTING_KLARIFIKASI_PEGAWAI
			) X WHERE X.PEGAWAI_ID = A.EMP_CODE
		)
		";
		// echo "dsplyRange:".$dsplyRange.";dsplyStart:".$dsplyStart;exit();
		$searchJson = " AND (UPPER(A.FIRST_NAME) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(CAST(A.EMP_CODE AS TEXT)) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%')";

		// echo $searchJson;exit();

		$allRecord = $set->getCountByParamsMonitoring(array(), $statement);
		//echo $allRecord;
		if($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsMonitoring(array(), $searchJson.$statement);
		
		$set->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $searchJson.$statement, $sOrder);
		  // echo $set->query;exit;
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
					$checked= "";
					$tempCheckId= $set->getField("EMP_CODE");
					if(in_array($tempCheckId, $arrayCheckId))
					{
						$checked= "checked";
					}

					$row[] = "<input type='checkbox' $checked onclick='setKlikCheck()' class='editor-active' id='reqPilihCheck".$set->getField("EMP_CODE")."' ".$checked." value='".$set->getField("EMP_CODE")."'>";
				}
				elseif($aColumns[$i] == "NIP_NAMA")
				{
					$row[] = $set->getField("NIP_BARU")."<br/>".$set->getField("NAMA_LENGKAP");
				}
				elseif($aColumns[$i] == "SATUAN_KERJA_DETIL")
				{
					$setdetil= new SettingKlarifikasiPegawai();
					$tempValue= $setdetil->getSatkerNamaDetil($set->getField("SATUAN_KERJA_ID"));
					unset($setdetil);

					$row[] = $tempValue;
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}

	function jsonpilih()
	{
		$this->load->model('main/SettingKlarifikasiPegawai');
		$set= new SettingKlarifikasiPegawai;
		
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqStatusIntegrasi= $this->input->get("reqStatusIntegrasi");
		$reqPencarian= $this->input->get("reqPencarian");
		$reqCheckId= $this->input->get("reqCheckId");
		$arrayCheckId= explode(',', $reqCheckId);

		$aColumns = array("CHECK", "NIP_NAMA", "SATUAN_KERJA_DETIL", "JABATAN_RIWAYAT_NAMA", "EMP_CODE");
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
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY CHECK asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.TMT_PANGKAT ASC";
				 
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

		$statement= "
		AND NOT EXISTS
		(
			SELECT 1
			FROM
			(
				SELECT PEGAWAI_ID FROM presensi.SETTING_KLARIFIKASI_PEGAWAI
			) X WHERE X.PEGAWAI_ID = A.EMP_CODE
		)
		";
		// echo "dsplyRange:".$dsplyRange.";dsplyStart:".$dsplyStart;exit();
		$searchJson = " AND (UPPER(A.FIRST_NAME) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(CAST(A.EMP_CODE AS TEXT)) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%')";

		// echo $searchJson;exit();

		$allRecord = $set->getCountByParamsMonitoring(array(), $statement);
		//echo $allRecord;
		if($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsMonitoring(array(), $searchJson.$statement);
		
		$set->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $searchJson.$statement, $sOrder);
		  // echo $set->query;exit;
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
					$checked= "";
					$tempCheckId= $set->getField("EMP_CODE");
					if(in_array($tempCheckId, $arrayCheckId))
					{
						$checked= "checked";
					}

					$row[] = "<input type='checkbox' $checked onclick='setKlikCheck()' class='editor-active' id='reqPilihCheck".$set->getField("EMP_CODE")."' ".$checked." value='".$set->getField("EMP_CODE")."'>";
				}
				elseif($aColumns[$i] == "NIP_NAMA")
				{
					$row[] = $set->getField("NIP_BARU")."<br/>".$set->getField("NAMA_LENGKAP");
				}
				elseif($aColumns[$i] == "SATUAN_KERJA_DETIL")
				{
					$setdetil= new SettingKlarifikasiPegawai();
					$tempValue= $setdetil->getSatkerNamaDetil($set->getField("SATUAN_KERJA_ID"));
					unset($setdetil);

					$row[] = $tempValue;
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}

	function pilih()
	{
		$this->load->model('main/SettingKlarifikasiPegawai');
		$set= new SettingKlarifikasiPegawai;
		
		$reqPegawaiId= $this->input->get("reqPegawaiId");

		$reqArrPegawaiId= explode(",", $reqPegawaiId);

		for($i=0; $i<count($reqArrPegawaiId); $i++)
		{
			$reqRowId= $reqArrPegawaiId[$i];
			$set= new SettingKlarifikasiPegawai();
			$set->setField('PEGAWAI_ID', $reqRowId);
			$set->insert();
			// echo $set->query;exit();
			unset($set);
		}

		echo json_encode("1");
	}

	function hapus()
	{
		$this->load->model('main/SettingKlarifikasiPegawai');
		$set= new SettingKlarifikasiPegawai;
		
		$reqPegawaiId= $this->input->get("reqPegawaiId");

		$reqArrPegawaiId= explode(",", $reqPegawaiId);
		$jumlahdata= count($reqArrPegawaiId);

        $set= new SettingKlarifikasiPegawai();
        $set->setField('PEGAWAI_ID', $reqPegawaiId);
		if($jumlahdata > 1)
		{
			$set->deleteIn();
		}
		else
		{
			$set->delete();
		}

		echo json_encode("1");
	}
	
}

