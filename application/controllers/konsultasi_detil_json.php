<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class konsultasi_detil_json extends CI_Controller {

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
		$this->USER_LOGIN_ID= $this->kauth->getInstance()->getIdentity()->USER_LOGIN_ID;
		$this->SATUAN_KERJA_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID;
		$this->PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->PEGAWAI_ID;
		$this->LOGIN_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_ID;
		$this->LOGIN_PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID;
	}	
	
	function json() 
	{
		$this->load->model('MailboxDetil');

		$set = new MailboxDetil();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		
		$aColumns = array("PEGAWAI_NAMA", "TANGGAL", "SUBYEK", "STATUS_NAMA", "MAILBOX_ID");
		$aColumnsAlias = array("PEGAWAI_NAMA", "TANGGAL", "SUBYEK", "STATUS_NAMA", "MAILBOX_ID");
		
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
			if ( trim($sOrder) == "ORDER BY NAMA desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY NAMA ASC ";

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
		
		$searchJson = "  AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $set->getCountByParams(array());
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParams(array(), $searchJson);
		
		$set->selectByParams(array(), $dsplyRange, $dsplyStart, $searchJson, $sOrder);     		
		
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
					$row[] = getFormattedDateTime($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "KETERANGAN")
					$row[] = truncate($set->getField($aColumns[$i]), 5)."...";
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}
	
	function add()
	{
		$this->load->model('MailboxDetil');
		$this->load->model('Mailbox');
				
		$reqId = $this->input->post("reqId");
		$reqMode = $this->input->post("reqMode");
		$reqKeterangan= $_POST["reqKeterangan"]; 
		$reqStatus = $this->input->post("reqStatus");
		$reqTipe = $this->input->post("reqTipe");
		$reqSatuanKerjaId = $this->input->post("reqSatuanKerjaId");
		$reqMailBoxKategoriId = $this->input->post("reqMailBoxKategoriId");

		if($reqMode == "insert")
		{
			// kalau yg update upt maka update data status jadikan value 1
			if($reqStatus == "1")
			{
				$set= new Mailbox();
				$set->setField('MAILBOX_ID', $reqId);
				$set->setField('FIELD', "STATUS");
				$set->setField('FIELD_VALUE', "1");
				$set->updateByFieldIsNull();
			}

			$set= new MailboxDetil();
			$set->setField('TANGGAL', "CURRENT_TIMESTAMP");
			$set->setField('ISI', $reqKeterangan);
			$set->setField('MAILBOX_KATEGORI_ID', $reqMailBoxKategoriId);
			$set->setField('PEGAWAI_ID', ValToNullDB($this->PEGAWAI_ID));
			$set->setField('USER_LOGIN_ID', $this->USER_LOGIN_ID);
			$set->setField('SATUAN_KERJA_JAWAB_ID', $reqSatuanKerjaId);
			$set->setField('TIPE', ValToNullDB($reqTipe));
			$set->setField("LAST_USER", $this->LOGIN_USER);
			$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
			$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			$set->setField("LAST_DATE", "NOW()");
			$set->setField('STATUS', ValToNullDB($reqStatus));
			$set->setField('MAILBOX_ID', $reqId);
			
			if($set->insert()){
				echo $reqId."-Data berhasil disimpan.";
			} else {
				echo "xxx-Data gagal disimpan.";
			}
		}
	}

}
?>