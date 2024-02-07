<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class tambahan_masa_kerja_json extends CI_Controller {

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
		$this->LOGIN_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_ID;
		$this->LOGIN_PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID;
	}	
	
	function add()
	{
		$this->load->model('validasi/TambahanMasaKerja');
		$this->load->model('PejabatPenetap');
		
		$set = new TambahanMasaKerja();

		$reqTempValidasiHapusId= $this->input->post("reqTempValidasiHapusId");
		$reqTempValidasiId= $this->input->post("reqTempValidasiId");
		$reqStatusValidasi= $this->input->post("reqStatusValidasi");
		$reqId= $this->input->post("reqId");
		$reqMode= $this->input->post("reqMode");
		$reqRowId= $this->input->post('reqRowId');

		if(empty($reqStatusValidasi))
		{
			echo "xxx-Isikan terlebih dahulu Status Klarifikasi.";
			exit;
		}
		elseif($reqStatusValidasi == "2")
		{
			if(empty($reqTempValidasiId) && !empty($reqTempValidasiHapusId))
			{
				$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiHapusId);
				$reqsimpan= "";
				if($set->deletehapusdata())
				{
					$reqsimpan= "1";
				}

				if($reqsimpan == "1")
				{
					echo $reqRowId."-Data berhasil disimpan.";
				}
				else
					echo "xxx-Data gagal disimpan.";
			}
			else
			{
				$set->setField('VALIDASI', $reqStatusValidasi);
				$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
				$set->setField("LAST_USER", $this->LOGIN_USER);
				$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
				$set->setField("LAST_DATE", "NOW()");
				$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);

				$reqsimpan= "";
				if($set->updatevalidasi())
				{
					$reqsimpan= "1";
				}

				if($reqsimpan == "1")
				{
					echo $reqRowId."-Data berhasil disimpan.";
				}
				else
					echo "xxx-Data gagal disimpan.";
			}


		}
		else
		{
		
			$reqNoSK= $this->input->post('reqNoSK');
			$reqTanggalSk= $this->input->post('reqTanggalSk');
			$reqTmtSk= $this->input->post('reqTmtSk');
			$reqTahunTambahan= $this->input->post('reqTahunTambahan');
			$reqBulanTambahan= $this->input->post('reqBulanTambahan');
			$reqTahunBaru= $this->input->post('reqTahunBaru');
			$reqBulanBaru= $this->input->post('reqBulanBaru');
			$reqGolRuang= $this->input->post('reqGolRuang');
			$reqNoNota= $this->input->post('reqNoNota');
			$reqTglNota= $this->input->post('reqTglNota');
			$reqGajiPokok= $this->input->post('reqGajiPokok');
			$reqPejabatPenetapId= $this->input->post('reqPejabatPenetapId');
			$reqPejabatPenetap= $this->input->post('reqPejabatPenetap');

			//kalau pejabat tidak ada
			if($reqPejabatPenetapId == "")
			{
				$set_pejabat=new PejabatPenetap();
				$set_pejabat->setField('NAMA', strtoupper($reqPejabatPenetap));
				$set_pejabat->setField("LAST_USER", $this->LOGIN_USER);
				$set_pejabat->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				$set_pejabat->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
				$set_pejabat->setField("LAST_DATE", "NOW()");
				$set_pejabat->insert();
				// echo $set_pejabat->query;exit();
				$reqPejabatPenetapId=$set_pejabat->id;
				unset($set_pejabat);
			}

			$set->setField('PANGKAT_ID', $reqGolRuang);
			$set->setField('PEJABAT_PENETAP_ID', $reqPejabatPenetapId);
			$set->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
			$set->setField('NO_NOTA', $reqNoNota);
			$set->setField('TANGGAL_NOTA', dateToDBCheck($reqTglNota));
			$set->setField("GAJI_POKOK", ValToNullDB(dotToNo($reqGajiPokok)));

			$set->setField('NO_SK', $reqNoSK);
			$set->setField('TANGGAL_SK', dateToDBCheck($reqTanggalSk));		
			$set->setField('TMT_SK', dateToDBCheck($reqTmtSk));
			$set->setField('TAHUN_TAMBAHAN', $reqTahunTambahan);
			$set->setField('BULAN_TAMBAHAN', $reqBulanTambahan);
			$set->setField('TAHUN_BARU', $reqTahunBaru);
			$set->setField('BULAN_BARU', $reqBulanBaru);
			$set->setField('PEGAWAI_ID', $reqId);
			$set->setField('TAMBAHAN_MASA_KERJA_ID', $reqRowId);
			$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
			$set->setField("LAST_USER", $this->LOGIN_USER);
			$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
			$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			$set->setField("LAST_DATE", "NOW()");
			$set->setField('VALIDASI', $reqStatusValidasi);
			$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);
			
			if($reqMode == "insert")
			{
				if($set->insert())
				{
					$reqRowId= $set->id;
					echo $reqRowId."-Data berhasil disimpan.";
				}
				else
					echo "xxx-Data gagal disimpan.";
			}
			else
			{
				$set->setField('BAHASA_ID', $reqRowId);
				if($set->update())
					echo $reqRowId."-Data berhasil disimpan.";
				else 
					// echo $set->query;exit;
					echo "xxx-Data gagal disimpan.";
			}
		}
			
	}
	
	function delete()
	{
		$this->load->model('TambahanMasaKerja');
		$set = new TambahanMasaKerja();
		
		$reqRowId=  $this->input->get('reqRowId');
		$reqMode =  $this->input->get('reqMode');
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		$set->setField("TAMBAHAN_MASA_KERJA_ID", $reqRowId);
		
		if($reqMode == "tambahanmasakerja_0")
		{
			$set->setField("STATUS", "1");
			if($set->updateStatus())
				$temp= "Data berhasil di batalkan.";
			else
				$temp= "Data gagal di batalkan.";
		}
		elseif($reqMode == "tambahanmasakerja_1")
		{
			$set->setField("STATUS", "2");
			if($set->updateStatus())
				$temp= "Data berhasil di aktifkan.";
			else
				$temp= "Data gagal di aktifkan.";	
		}

		$arrJson["PESAN"]= $temp;
		echo json_encode($arrJson);
	}

	function log($riwayatId) 
	{	
		$this->load->model('TambahanMasaKerjaLog');

		$set = new TambahanMasaKerjaLog();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		
		$aColumns = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "BAHASA_ID");
		$aColumnsAlias = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "BAHASA_ID");
		

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
		
		$arrayWhere = array("BAHASA_ID" => $riwayatId);
		$set->selectByParams($arrayWhere, $dsplyRange, $dsplyStart, $searchJson, $sOrder);     		
		
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

}
?>