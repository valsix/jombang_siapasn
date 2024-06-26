<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class user_login_json extends CI_Controller {

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
		$this->LOGIN_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_ID;
		$this->LOGIN_PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID;
	}	
	
	function json() 
	{
		$this->load->model('UserLogin');

		$set = new UserLogin();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		
		$aColumns = array("LOGIN_USER", "LOGIN_LEVEL_INFO", "SATUAN_KERJA_NAMA", "USER_GROUP_NAMA", "LINK_URL_INFO", "USER_LOGIN_ID");
		$aColumnsAlias = array("LOGIN_USER", "LOGIN_LEVEL_INFO", "SATUAN_KERJA_NAMA", "USER_GROUP_NAMA", "LINK_URL_INFO", "USER_LOGIN_ID");
		
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
			if ( trim($sOrder) == "ORDER BY LOGIN_USER desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY USER_LOGIN_ID ASC ";
				 
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
		
		$searchJson =  " AND (UPPER(LOGIN_USER) LIKE '%".strtoupper($_GET['sSearch'])."%')";
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

	function addfile()
	{
		$this->load->model('UserLogin');

		$reqId= $this->input->post("reqId");
		$reqKategoriFileId= $this->input->post("reqKategoriFileId");
		$reqRiwayatTable= $this->input->post("reqRiwayatTable");
		$reqRiwayatId= $this->input->post("reqRiwayatId");
		$reqRiwayatField= $this->input->post("reqRiwayatField");
		$reqStatus= $this->input->post("reqStatus");

		$set= new UserLogin();
		$set->setField("USER_LOGIN_ID", $reqId);
		$set->deletefile();

		foreach ($reqKategoriFileId as $key => $value) {
			$vStatus= $reqStatus[$key];

			if(!empty($vStatus))
			{
				$vKategoriFileId= $reqKategoriFileId[$key];
				$vRiwayatTable= $reqRiwayatTable[$key];
				$vRiwayatId= $reqRiwayatId[$key];
				$vRiwayatField= $reqRiwayatField[$key];

				// echo $vKategoriFileId."-".$vRiwayatTable."\n";
				$set->setField("USER_LOGIN_ID", $reqId);
				$set->setField("KATEGORI_FILE_ID", $vKategoriFileId);
				$set->setField("RIWAYAT_TABLE", $vRiwayatTable);
				$set->setField("RIWAYAT_ID", $vRiwayatId);
				$set->setField("RIWAYAT_FIELD", $vRiwayatField);
				$set->setField("STATUS", $vStatus);
				$set->setField("LAST_USER", $this->LOGIN_USER);
				$set->insertfile();
			}
		}

		echo $reqId."-Data berhasil disimpan.";
	}
	
	function add()
	{
		$this->load->model('UserLogin');
		$this->load->model('UserLoginDetil');
		
		$set = new UserLogin();
		
		$reqId = $this->input->post("reqId");
		$reqMode = $this->input->post("reqMode");
		$reqLoginLevel = $this->input->post("reqLoginLevel");
		$reqLoginPassword= $this->input->post("reqLoginPassword");
		$reqSatkerId= $this->input->post("reqSatkerId");
		$reqLoginUser= $this->input->post("reqLoginUser");
		$reqUserGroupId= $this->input->post("reqUserGroupId");
		$reqStatusMenuKhusus= $this->input->post("reqStatusMenuKhusus");

		$reqPegawaiId= $this->input->post("reqPegawaiId");
		$reqTglAwal= $this->input->post("reqTglAwal");
		$reqTglAkhir= $this->input->post("reqTglAkhir");
		$reqTglAkhir= $this->input->post("reqTglAkhir");
		$reqRowDetilId= $this->input->post("reqRowDetilId");
		
		$set->setField('LOGIN_LEVEL', ValToNullDB($reqLoginLevel));
		$set->setField('LOGIN_USER', $reqLoginUser);
		$set->setField('USER_GROUP_ID', ValToNullDB($reqUserGroupId));
		$set->setField('SATUAN_KERJA_ID',  ValToNullDB($reqSatkerId));
		$set->setField('STATUS_MENU_KHUSUS',  ValToNullDB($reqStatusMenuKhusus));
		
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		
		$tempSimpan = "";
		if($reqMode == "insert")
		{
			$set->setField('STATUS', 1);	
			$set->setField('LOGIN_PASS', $reqLoginPassword);				
			if($set->insert())
			{
				$reqId=$set->id;
				$tempSimpan=1;
			}
		}
		else
		{
			$set->setField('USER_LOGIN_ID', $reqId); 
			if($set->update())
			{				
				$tempSimpan=1;
			}
		}
		//echo $set->query;exit;
		
		if($tempSimpan==1)
		{
			for($i=0;$i<count($reqPegawaiId);$i++)
			{
				$set_data = new UserLoginDetil();
				$set_data->setField('USER_LOGIN_ID', $reqId);
				$set_data->setField('PEGAWAI_ID', ValToNullDB($reqPegawaiId[$i]));
				$set_data->setField('TANGGAL_AWAL', dateToDBCheck($reqTglAwal[$i]));
				$set_data->setField('TANGGAL_AKHIR', dateToDBCheck($reqTglAkhir[$i]));
				$set_data->setField("LAST_USER", $this->LOGIN_USER);
				// $set_data->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				$set_data->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
				$set_data->setField("LAST_DATE", "NOW()");
			
				if($reqRowDetilId[$i]=="")
				{
					$set_data->insert();
				}
				else
				{
					$set_data->setField('USER_LOGIN_DETIL_ID', $reqRowDetilId[$i]);
					$set_data->update();
				}
				// echo $set_data->query;exit;
			}
			
			echo $reqId."-Data berhasil disimpan.";
		}
		
	}
	
	function delete()
	{
		$this->load->model('UserLogin');
		$set = new UserLogin();
		
		$reqId =  $this->input->get('reqId');
		$reqMode =  $this->input->get('reqMode');
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		$set->setField("USER_LOGIN_ID", $reqId);
		
		if($reqMode == "user_login_0")
		{
			$set->setField("STATUS", "1");
			if($set->updateStatus())
				$arrJson["PESAN"] = "Data berhasil dihapus.";
			else
				$arrJson["PESAN"] = "Data gagal dihapus.";	
		}
		elseif($reqMode == "user_login_1")
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
		$this->load->model("UserLogin");
		$set = new UserLogin();
		
		$set->selectByParams();
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("USER_LOGIN_ID");
			$arr_json[$i]['text'] = $set->getField("NAMA");	
			$i++;
		}
		echo json_encode($arr_json);		
		
	}	

	function log() 
	{	
		$this->load->model('UserLoginLog');

		$set = new UserLoginLog();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		
		$aColumns = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "USER_LOGIN_ID");
		$aColumnsAlias = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "USER_LOGIN_ID");
		

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

	function ubah_password()
	{
		$reqPaswordLama = $this->input->post("reqPaswordLama");
		$reqPaswordBaru = $this->input->post("reqPaswordBaru");
		
		if($this->USER_LOGIN_ID == "")
			echo "xxx-Data gagal disimpan.";
		else
		{
			$this->load->model('UserLogin');
			$set = new UserLogin();

			$set->selectByParams(array("A.USER_LOGIN_ID" => $this->USER_LOGIN_ID),-1,-1);
			$set->firstRow();
			if($set->getField('LOGIN_PASS') != md5($reqPaswordLama))
			{
				echo "xxx-Password lama salah.";
				exit();
			}
			else
			{
				$set_data = new UserLogin();
				$set_data->setField('LOGIN_PASS', md5($reqPaswordBaru));
				$set_data->setField("LAST_USER", $this->LOGIN_USER);
				$set_data->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				$set_data->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
				$set_data->setField("LAST_DATE", "NOW()");
				$set_data->resetPassword();
				echo "-Password berhasil di ubah.";

			}

		}
	}

	function reset_password()
	{
		$reqId = $this->input->post("reqId");
		$reqPaswordBaru = $this->input->post("reqPaswordBaru");
		
		if($reqId == "")
			echo "xxx-Data gagal disimpan.";
		else
		{
			$this->load->model('UserLogin');

			$set_data = new UserLogin();
			$set_data->setField('USER_LOGIN_ID', $reqId);
			$set_data->setField('LOGIN_PASS', md5($reqPaswordBaru));
			$set_data->setField("LAST_USER", $this->LOGIN_USER);
			// $set_data->setField("USER_LOGIN_ID", $this->LOGIN_ID);
			$set_data->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			$set_data->setField("LAST_DATE", "NOW()");
			$set_data->resetPassword();
			echo "-Password berhasil di ubah.";

		}
	}

}
?>