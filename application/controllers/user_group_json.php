<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class user_group_json extends CI_Controller {

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
		$this->LOGIN_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_ID;
		$this->LOGIN_PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID;
	}	
	
	function json() 
	{
		$this->load->model('UserGroup');

		$set = new UserGroup();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		
		$aColumns = array("NAMA", "AKSES_APP_SIMPEG_NAMA", "LINK_URL_INFO", "USER_GROUP_ID");
		$aColumnsAlias = array("NAMA", "AKSES_APP_SIMPEG_NAMA", "LINK_URL_INFO", "USER_GROUP_ID");
		
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
		
		// $searchJson = "  AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $set->getCountByParamsMonitoring(array());
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParamsMonitoring(array(), $searchJson);
		
		$set->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $searchJson, $sOrder);     		
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
		$this->load->model('UserGroup');
		
		$user_group= new UserGroup();
		
		$reqMode= $this->input->post("reqMode");
		$reqId= $this->input->post("reqId");
		$reqNama= $this->input->post("reqNama");
		$reqAksesAppSimpegId= $this->input->post("reqAksesAppSimpegId");
		$reqAksesAppAbsensiId= $this->input->post("reqAksesAppAbsensiId");
		$reqTampil= $this->input->post("reqTampil");

		// print_r($reqTampil);exit();
		
		$user_group->setField("NAMA", $reqNama);
		$user_group->setField("AKSES_APP_SIMPEG_ID", ValToNullDB($reqAksesAppSimpegId));
		$user_group->setField("AKSES_APP_ABSENSI_ID", ValToNullDB($reqAksesAppAbsensiId));
		$user_group->setField("LAST_USER", $this->LOGIN_USER);
		$user_group->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$user_group->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
	    $user_group->setField("LAST_DATE", "NOW()");
		$user_group->setField("USER_GROUP_ID", $reqId);
		$user_group->setField("TAMPIL_RESET", ValToNullDB($reqTampil));
			
		if($reqMode == "insert")
		{
			if($user_group->insert())
			{
				$reqId= $user_group->id;
				echo $reqId."-Data berhasil disimpan.";
			}else {
				echo "xxx-Data gagal disimpan.";
			}
		}
		else
		{
			if($user_group->update())
			{	
				echo $reqId."-Data berhasil disimpan.";
			}else {
				echo "xxx-Data gagal disimpan.";
			}
		}
		
	}
	
	function add_menu()
	{
		$this->load->model('UserGroup');
		
		$reqMode= $this->input->post("reqMode");
		$reqMenuId= $this->input->post("reqMenuId");
		$reqMenuIconId= $this->input->post("reqMenuIconId");
		$reqMenuGroupId= $this->input->post("reqMenuGroupId");
		$reqCheck= $this->input->post("reqCheck");
		$reqNama= $this->input->post("reqNama");
		$reqRowId= $this->input->post("reqRowId");
		$reqTable= $this->input->post("reqTable");
		
		//$akses_adm_intranet = new AksesAdmIntranet();
		//$akses_adm_intranet_menu = new AksesAdmIntranetMenu();
		$akses_adm_intranet = new UserGroup();
		$akses_adm_intranet_menu = new UserGroup();

		$setquery="";
		if($reqMode == "insert")
		{	
			  $akses_adm_intranet->setField("NAMA", $reqNama);
			  $akses_adm_intranet->setField("TABLE", $reqTable);
			  $akses_adm_intranet->setField("LAST_USER", $this->LOGIN_USER);
			  $akses_adm_intranet->setField("USER_LOGIN_ID", $this->LOGIN_ID);
			  $akses_adm_intranet->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			  $akses_adm_intranet->setField("LAST_DATE", "NOW()");
			  $akses_adm_intranet->insertMenu();
			  $reqRowId= $akses_adm_intranet->id;
			  for($i=0;$i<count($reqMenuId);$i++)
			  {
				  $akses_adm_intranet_menu = new UserGroup();
				  $akses_adm_intranet_menu->setField("AKSES_ADM_INTRANET_ID", $reqRowId);
				  $akses_adm_intranet_menu->setField("MENU_ID", $reqMenuId[$i]);
				  $akses_adm_intranet_menu->setField("MENU_ICON_ID", $reqMenuIconId[$i]);
				  $akses_adm_intranet_menu->setField("AKSES", $reqCheck[$i]);
				  $akses_adm_intranet_menu->setField("TABLE", $reqTable);
				  $akses_adm_intranet_menu->setField("MENU_GROUP_ID", $reqMenuGroupId);
				  $akses_adm_intranet_menu->setField("LAST_USER", $this->LOGIN_USER);
				  $akses_adm_intranet_menu->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				  $akses_adm_intranet_menu->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			  	  $akses_adm_intranet_menu->setField("LAST_DATE", "NOW()");
				  $akses_adm_intranet_menu->insertMenuDetil();
				  unset($akses_adm_intranet_menu);	  
			  }
			  echo $reqRowId."-Data berhasil disimpan.";
		}
		else
		{
			  $akses_adm_intranet->setField("NAMA", $reqNama);
			  $akses_adm_intranet->setField("AKSES_ADM_INTRANET_ID", $reqRowId);	  
			  $akses_adm_intranet->setField("TABLE", $reqTable);
			  $akses_adm_intranet->setField("LAST_USER", $this->LOGIN_USER);
			  $akses_adm_intranet->setField("USER_LOGIN_ID", $this->LOGIN_ID);
			  $akses_adm_intranet->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			  $akses_adm_intranet->setField("LAST_DATE", "NOW()");
			  $akses_adm_intranet->updateMenu();
			  $setquery=$akses_adm_intranet->query;

			  $akses_adm_intranet_menu->setField("AKSES_ADM_INTRANET_ID", $reqRowId);
			  $akses_adm_intranet_menu->setField("TABLE", $reqTable);
			  $akses_adm_intranet_menu->deleteMenuDetil();
			  $setquery.=";".$akses_adm_intranet_menu->query;
			  
			  for($i=0;$i<count($reqMenuId);$i++)
			  {
				  $akses_adm_intranet_menu = new UserGroup();
				  $akses_adm_intranet_menu->setField("AKSES_ADM_INTRANET_ID", $reqRowId);
				  $akses_adm_intranet_menu->setField("MENU_ID", $reqMenuId[$i]);
				  $akses_adm_intranet_menu->setField("MENU_ICON_ID", $reqMenuIconId[$i]);
				  $akses_adm_intranet_menu->setField("AKSES", $reqCheck[$i]);
				  $akses_adm_intranet_menu->setField("TABLE", $reqTable);
				  $akses_adm_intranet_menu->setField("MENU_GROUP_ID", $reqMenuGroupId);
				  $akses_adm_intranet_menu->setField("LAST_USER", $this->LOGIN_USER);
				  $akses_adm_intranet_menu->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				  $akses_adm_intranet_menu->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
				  $akses_adm_intranet_menu->setField("LAST_DATE", "NOW()");
				  $akses_adm_intranet_menu->insertMenuDetil();
				  $setquery.=";".$akses_adm_intranet_menu->query;
				  //echo $akses_adm_intranet_menu->query;exit;
				  //unset($akses_adm_intranet_menu);	  
			  }
			  // echo $setquery;exit();
			  echo $reqRowId."-Data berhasil disimpan.";
		}
		
	}
	
	function delete()
	{
		$this->load->model('UserGroup');
		$set = new UserGroup();
		
		$reqId =  $this->input->get('reqId');
		$reqMode =  $this->input->get('reqMode');
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		$set->setField("USER_GROUP_ID", $reqId);
		
		if($reqMode == "pangkat_0")
		{
			$set->setField("STATUS", "1");
			if($set->updateStatus())
				$arrJson["PESAN"] = "Data berhasil dihapus.";
			else
				$arrJson["PESAN"] = "Data gagal dihapus.";	
		}
		elseif($reqMode == "pangkat_1")
		{
			$set->setField("STATUS", ValToNullDB($req));
			if($set->updateStatus())
				$arrJson["PESAN"] = "Data berhasil dihapus.";
			else
				$arrJson["PESAN"] = "Data gagal dihapus.";	
		}	
		
		echo json_encode($arrJson);
	}

	function combo() 
	{
		$this->load->model("UserGroup");
		$set = new UserGroup();
		
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
		$this->load->model('UserGroupLog');

		$set = new UserGroupLog();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		$columnsShow = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "USER_GROUP_ID");
		$aColumns = $columnsShow;
		$aColumnsAlias = $columnsShow;
		
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
				$sOrder = " ORDER BY LAST_DATE ASC ";
				 
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
		
		$sOrder= " ORDER BY A.LAST_DATE DESC ";
		// $searchJson = "  AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
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
				if($aColumns[$i] == "LAST_DATE")
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

	function addbkn()
	{
		$this->load->model('Menu');

		$reqId= $this->input->post("reqId");
		$reqMenuGroupId= 1;
		$list_modul= $this->input->post("modul");
		$lihat= $this->input->post("lihat");
		$kirim= $this->input->post("kirim");
		$tarik= $this->input->post("tarik");
		$sync= $this->input->post("sync");

		$set = new Menu();
		$set->setField("USER_GROUP_ID", $reqId);
		$set->deletemenubkngroup();

		foreach($list_modul as $key=>$value)
		{	
			$set = new Menu();
			$set->setField("MENU_ID", $value);
			$set->setField("MENU_GROUP_ID", $reqMenuGroupId);
			$set->setField("USER_GROUP_ID", $reqId);
			$set->setField("LIHAT", (isset($lihat[$key]))?1:0);
			$set->setField("KIRIM", (isset($kirim[$key]))?1:0);
			$set->setField("TARIK", (isset($tarik[$key]))?1:0);
			$set->setField("SYNC", (isset($sync[$key]))?1:0);
			if($set->insertmenubkngroup()) {}
		}

		echo $reqId."-Data berhasil disimpan.";
	}
		
}
?>