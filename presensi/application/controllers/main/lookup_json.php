<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/image.func.php");

class lookup_json extends CI_Controller {

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

	function pegawai()
	{
		$reqId = $this->input->get("reqId");
		$reqCari= $this->input->get("reqCari");
		$reqCheckId= $this->input->get("reqCheckId");
		$arrayCheckId= explode(',', $reqCheckId);

		// $this->load->model('main/Klarifikasi');
		$this->load->model('main/SatuanKerja');
		$this->load->model('main/Popup');

		$set= new Popup;
		
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqTanggal= $this->input->get("reqTanggal");

		$aColumns = array("NIP_BARU", "NAMA_LENGKAP", "JABATAN_RIWAYAT_NAMA", "PANGKAT_RIWAYAT_ID", "JABATAN_RIWAYAT_ID", "PEGAWAI_ID");
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
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY NIP_BARU asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY XXX.ESELON_ID ASC, XXX.PANGKAT_ID DESC, XXX.PANGKAT_RIWAYAT_TMT ASC";
				 
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
			$tempSatuanKerjaId= $reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}

		$satuankerjakondisi= "";
		if($reqSatuanKerjaId == "")
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND XXX.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
		}
		else
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND XXX.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
			
			$skerja= new SatuanKerja();
			$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
			// echo $skerja->query;exit();
			unset($skerja);
			// echo $reqSatuanKerjaId;exit;
			$satuankerjakondisi.= " AND XXX.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}
		// echo $satuankerjakondisi;exit();

		$reqPencarian= $_GET['reqPencarian'];

		if(!empty($reqTanggal))
		{
			$statement.= " AND 
			(
				XXX.STATUS_PEGAWAI_ID IN (1,2)
				OR
				(
					XXX.STATUS_PEGAWAI_ID IN (3,4,5) AND TO_DATE(TO_CHAR(XXX.PEGAWAI_STATUS_TMT, 'YYYY-MM-DD'), 'YYYY/MM/DD') >= TO_DATE('".dateToPageCheck($reqTanggal)."','YYYY/MM/DD')
				)
			)";
		}
		else
			$statement= " AND XXX.STATUS_PEGAWAI_ID IN (1,2)";
		
		$searchJson = " AND ( UPPER(NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(NIP_BARU) LIKE '%".strtoupper($reqPencarian)."%' )";
		$allRecord = $set->getCountByParamsPopupPegawai(array(), $satuankerjakondisi, $statement);
		//echo $allRecord;
		if($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsPopupPegawai(array(), $satuankerjakondisi, $searchJson.$statement);
		
		$set->selectByParamsPopupPegawai(array(), $dsplyRange, $dsplyStart, $satuankerjakondisi, $searchJson.$statement, $sOrder);
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
				else if($aColumns[$i] == 'CHECK')
				{
					$checked= "";
					/*$keyArray= array_search($set->getField("PEGAWAI_ID"), $arrayTreeId);
					if($keyArray == ""){}
					else
						$checked= "checked";*/
					$tempCheckId= $set->getField("PEGAWAI_ID");
					if(in_array($tempCheckId, $arrayCheckId))
					{
						$checked= "checked";
					}

					$row[] = "<input type='checkbox' $checked onclick='setKlikCheck()' class='editor-active' id='reqPilihCheck".$set->getField("PEGAWAI_ID")."' ".$checked." value='".$set->getField("PEGAWAI_ID")."'>";
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}

	function search()
	{

		$this->load->model('main/Popup');
		$set = new Popup();
		// $setLine = new QueryCombo();
		
		$reqPencarian = $this->input->get("reqPencarian");
		$reqId = $this->input->get("reqId");
		$reqMode = $this->input->get("reqMode");
		$reqRowId = $this->input->get("reqRowId");
		$reqRegionalId= $this->input->get('reqRegionalId');
		$reqUnitId= $this->input->get('reqUnitId');
		$reqWhId= $this->input->get('reqWhId');
		$reqCode= $this->input->get("reqCode");
		$reqValId= $this->input->get("reqValId");

		$reqCompanyId = $this->input->get("reqCompanyId");
		// echo "-".$reqMode;exit();

		if($reqMode == "codegroup")
		{
			$statement = " AND ( UPPER(A.NAME) LIKE '%".strtoupper($reqPencarian)."%' ) AND A.COMPANY_ID = '".$reqCompanyId."'AND A.IS_DELETE = '0'";

			$set->selectByParamsCodeGroup(array(), 5, 0, $statement);
			// echo $set->query;exit;
			$infoid= "ID";
			$infonama= "NAME";
		}
		elseif($reqMode == "pegawaiById")
		{
			$statement = " AND PEGAWAI_ID IN (".$reqId.")";
			$set->selectByParamsPopupPegawai(array(), -1, -1, $statement);
			 // echo $set->query;exit();
		}

		// echo 'as';exit;
		// echo $set->query;exit();
		// echo $reqMode;exit;

		$arr_json = array();

		if($reqMode == "catalogget" || $reqMode == "pegawaviById")
		{
			for($i=0;$i<count($arrColumn);$i++)
			{
				$kolom = $arrColumn[$i];
				$arr_json[$kolom] = $set->getField($kolom);	
			}
		}	
		else if ($reqMode == "pegawaiById") 
		{
			$i = 0;
			while($set->nextRow())
			{				
				$arr_json[$i]['pegawai_id'] = $set->getField("PEGAWAI_ID");
				$arr_json[$i]['pangkat_id'] = $set->getField("PANGKAT_RIWAYAT_ID");
				$arr_json[$i]['jabatan_id'] = $set->getField("JABATAN_RIWAYAT_ID");
				$arr_json[$i]['nama'] = $set->getField("NAMA_LENGKAP");
				$arr_json[$i]['nip'] = $set->getField("NIP_BARU");
				$arr_json[$i]['jabatan'] = $set->getField("JABATAN_RIWAYAT_NAMA");

				$i++;
			}
		}	
		// else
		// {
		// 	$i = 0;
		// 	while($set->nextRow())
		// 	{
		// 		$arr_json[$i]['id'] = $set->getField($infoid);

		// 		if(is_array($infonama))
		// 		{
		// 			$text = "";
		// 			for ($idx=0; $idx < count($infonama); $idx++) { 
		// 				$text .= $set->getField($infonama[$idx]);
		// 				if($idx < count($infonama) - 1){
		// 					$text .= " - ";
		// 				}
		// 			}
		// 			$arr_json[$i]['text'] = $text;
		// 		}
		// 		else
		// 		{
		// 			$arr_json[$i]['text'] = $set->getField($infonama);
		// 		}
				
		// 		$arr_json[$i]['code'] = $set->getField($infocode);
		// 		$arr_json[$i]['currencyrate'] = $set->getField($infocurrencyrate);
		// 		$arr_json[$i]['taxrate'] = $set->getField($infotaxrate);
		// 		$arr_json[$i]['paymentremake'] = $set->getField($inforemake);
		// 		$arr_json[$i]['reqCompanyId'] = $reqCompanyId;

		// 		$i++;
		// 	}
		// }
		echo json_encode($arr_json);
		
	}

}

