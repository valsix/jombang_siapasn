<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class penilaian_json extends CI_Controller {

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
		$this->load->model('talent/Rekap');

		$reqKuadran= $this->input->get("reqKuadran");
		$reqTahun= $this->input->get("reqTahun");
		$reqEselonGroupId= $this->input->get("reqEselonGroupId");
		$reqTipePegawaiId= $this->input->get("reqTipePegawaiId");
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");

		$set = new Rekap();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);

		$aColumns = array("NIP_BARU_LAMA", "NAMA_LENGKAP", "NILAI_KINERJA", "NILAI_KOMPETENSI", "JABATAN_NAMA", "SATUAN_KERJA_INDUK", "PEGAWAI_ID");
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

		$statement= " AND A.GROUP_AREA = ".$reqKuadran;

		if(!empty($reqEselonGroupId))
		{
			$statement.= " AND ESELON_GROUP_ID = '".$reqEselonGroupId."'";
		}


		if(!empty($reqTipePegawaiId))
		{
			$statement.= " AND TIPE_PEGAWAI_ID LIKE '".$reqTipePegawaiId."%'";
		}

		if(!empty($reqSatuanKerjaId))
		{
			$statement.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}

		$searchJson = "  AND (UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($_GET['sSearch'])."%')";

		$allRecord = $set->getCountByParamsInfoDetil(array(), $reqTahun, $statement);
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParamsInfoDetil(array(), $reqTahun, $statement.$searchJson);
		
		$set->selectByParamsInfoDetil(array(), $dsplyRange, $dsplyStart, $reqTahun, $statement.$searchJson, $sOrder);
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
				else if($aColumns[$i] == "NILAI")
					$row[] = $set->getField("NILAI_KINERJA")."<br/>".$set->getField("NILAI_KOMPETENSI");
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

		
}
?>