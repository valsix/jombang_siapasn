<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class bidang_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// trow to unauthenticated page!
			//redirect('Login');
		}       
		
		/* GLOBAL VARIABLE */
		$this->username = $this->kauth->getInstance()->getIdentity()->USERNAME;  
		$this->LOGIN_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_ID;
		$this->LOGIN_PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID;
		 
	}	
	
	function json() 
	{
		$this->load->model('Bidang');

		$bidang = new Bidang();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		
		$aColumns = array("BIDANG_ID", "NAMA", "KETERANGAN");
		$aColumnsAlias = array("BIDANG_ID", "NAMA", "KETERANGAN");
		
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
			if ( trim($sOrder) == " ORDER BY BIDANG_ID asc " )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY BIDANG_ID ASC ";
				
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
		
		
		$allRecord = $bidang->getCountByParams(array());
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $bidang->getCountByParams(array(), " AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')");
		
		$bidang->selectByParams(array(), $dsplyRange, $dsplyStart, " AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')", $sOrder);     		
		
		/* Output */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);
		
		while($bidang->nextRow())
		{
			$row = array();
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				if($aColumns[$i] == "TANGGAL")
					$row[] = getFormattedDate($bidang->getField($aColumns[$i]));
				else if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($bidang->getField($aColumns[$i]), 5)."...";
				else
					$row[] = $bidang->getField($aColumns[$i]);
			}
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}
	
	function add()
	{
		$this->load->model('Bidang');
		
		$bidang = new Bidang();
		
		$reqId = $this->input->post("reqId");
		$reqMode = $this->input->post("reqMode");
		$reqNama= $this->input->post("reqNama");
		$reqKeterangan= $this->input->post("reqKeterangan");
		
		$bidang->setField('NAMA', $reqNama);
		$bidang->setField('KETERANGAN', $reqKeterangan);
		
		if($reqMode == "insert")
		{
			$bidang->setField("LAST_CREATE_USER", $userLogin->nama);
			$bidang->setField("LAST_CREATE_DATE", "NOW()");
			
			if($bidang->insert()){
				$reqId= $bidang->id;
				echo $reqId."-Data berhasil disimpan.";
			} else {
				echo "xxx-Data gagal disimpan.";
			}
		}
		else
		{
			$bidang->setField('BIDANG_ID', $reqId); 
			$bidang->setField("LAST_UPDATE_USER", $userLogin->nama);
			$bidang->setField("LAST_UPDATE_DATE", "NOW()");
			
			if($bidang->update()){
				echo $reqId."-Data berhasil disimpan.";
			} else {
				echo "xxx-Data gagal disimpan.";
			}
			
		}
		
	}
	
	function delete()
	{
		$this->load->model('Bidang');
		$bidang = new Bidang();
		
		$reqId =  $this->input->get('reqId');
		$bidang->setField("BIDANG_ID", $reqId);
		
		if($bidang->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";		
		
		echo json_encode($arrJson);
	}
	
	function combo() 
	{
		$this->load->model("Bidang");
		$bidang = new Bidang();
		
		$bidang->selectByParams();
		$arr_json = array();
		$i = 0;
		while($bidang->nextRow())
		{
			$arr_json[$i]['id'] = $bidang->getField("BIDANG_ID");
			$arr_json[$i]['text'] = $bidang->getField("NAMA");	
			$i++;
		}
		echo json_encode($arr_json);		
		
	}	
	
}
?>