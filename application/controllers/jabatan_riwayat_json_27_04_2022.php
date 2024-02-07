<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class jabatan_riwayat_json extends CI_Controller {

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
		$this->load->model('JabatanRiwayat');
		$this->load->model('JabatanFu');
		$this->load->model('JabatanFt');
		$this->load->model('PejabatPenetap');

		$set = new JabatanRiwayat();
		
		$reqId= $this->input->post("reqId");
		$reqMode= $this->input->post("reqMode");
		$reqRowId= $this->input->post('reqRowId');

		$reqJenisJabatan= $this->input->post("reqJenisJabatan");
		$reqIsManual= $this->input->post("reqIsManual");
		$reqJabatanFuId= $this->input->post("reqJabatanFuId");
		$reqJabatanFtId= $this->input->post("reqJabatanFtId");
		$reqTipePegawaiId= $this->input->post("reqTipePegawaiId");
		$reqNoSk= $this->input->post("reqNoSk");
		$reqTglSk= $this->input->post("reqTglSk");
		$reqNama= $this->input->post("reqNama");
		$reqTmtJabatan= $this->input->post("reqTmtJabatan");
		$reqTmtWaktuJabatan= $this->input->post("reqTmtWaktuJabatan");
		$reqTmtEselon= $this->input->post("reqTmtEselon");
		$reqEselonId= $this->input->post("reqEselonId");
		$reqNama= $this->input->post("reqNama");
		$reqTmtEselon= $this->input->post("reqTmtEselon");
		$reqKeteranganBUP= $this->input->post("reqKeteranganBUP");
		$reqNoPelantikan= $this->input->post("reqNoPelantikan");    
		$reqTglPelantikan= $this->input->post("reqTglPelantikan");
		$reqTunjangan= $this->input->post("reqTunjangan");
		$reqBlnDibayar= $this->input->post("reqBlnDibayar");
		$reqSatkerId= $this->input->post("reqSatkerId");
		$reqSatker= $this->input->post("reqSatker");
		$reqKredit= $this->input->post("reqKredit");
		//echo $reqSatker;exit;
		$reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
		$reqPejabatPenetapId= $this->input->post("reqPejabatPenetapId");

		if($reqJenisJabatan == 2)
		{
			// $statement= " AND UPPER(A.NAMA) = '".strtoupper($reqNama)."'";
			$statement= " AND A.NAMA = '".$reqNama."'";
			$set_detil= new JabatanFu();
			$set_detil->selectByParams(array(), -1,-1, $statement);
			// echo $set_detil->query;exit();
			$set_detil->firstRow();
			$reqJabatanFuId= $set_detil->getField("JABATAN_FU_ID");
			unset($set_detil);

			if($reqJabatanFuId == "")
			{
				echo "xxx-Nama Jabatan (".$reqNama.") tidak ada dalam sistem, hubungi admin untuk menambahkan data nama jabatan.";
				exit();
			}
		}
		elseif($reqJenisJabatan == 3)
		{
			// $statement= " AND UPPER(A.NAMA) = '".strtoupper($reqNama)."'";
			$statement= " AND A.NAMA = '".$reqNama."'";
			$set_detil= new JabatanFt();
			$set_detil->selectByParams(array(), -1,-1, $statement);
			// echo $set_detil->query;exit();
			$set_detil->firstRow();
			$reqJabatanFtId= $set_detil->getField("JABATAN_FT_ID");
			unset($set_detil);

			if($reqJabatanFtId == "")
			{
				echo "xxx-Nama Jabatan (".$reqNama.") tidak ada dalam sistem, hubungi admin untuk menambahkan data nama jabatan.";
				exit();
			}
		}

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
		
		$set->setField('TIPE_PEGAWAI_ID', $reqTipePegawaiId);
		$set->setField('PEJABAT_PENETAP_ID', $reqPejabatPenetapId);
		$set->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
		
		$set->setField('JABATAN_FU_ID', ValToNullDB($reqJabatanFuId));
		$set->setField('JABATAN_FT_ID', ValToNullDB($reqJabatanFtId));
		
		$set->setField('SATKER_ID', ValToNullDB($reqSatkerId));
		$set->setField('SATKER_NAMA', $reqSatker);
		$set->setField('IS_MANUAL', ValToNullDB($reqIsManual));
		$set->setField('NO_SK', $reqNoSk);
		$set->setField('ESELON_ID', ValToNullDB($reqEselonId));
		$set->setField('NAMA', $reqNama);
		$set->setField('NO_PELANTIKAN', $reqNoPelantikan);
		$set->setField("TUNJANGAN", ValToNullDB(dotToNo($reqTunjangan)));
		$set->setField('KREDIT', ValToNullDB(CommaToDot($reqKredit)));
		$set->setField('TMT_BATAS_USIA_PENSIUN', ValToNullDB($req));
		$set->setField('TMT_ESELON', dateToDBCheck($reqTmtEselon));
		$set->setField('TANGGAL_SK', dateToDBCheck($reqTglSk));
		if(strlen($reqTmtWaktuJabatan) == 5)
			$set->setField('TMT_JABATAN', dateTimeToDBCheck($reqTmtJabatan." ".$reqTmtWaktuJabatan));
		else
			$set->setField('TMT_JABATAN', dateToDBCheck($reqTmtJabatan));
		$set->setField('TANGGAL_PELANTIKAN', dateToDBCheck($reqTglPelantikan));
		$set->setField('BULAN_DIBAYAR', dateToDBCheck($reqBlnDibayar));
		$set->setField('KETERANGAN_BUP', $reqKeteranganBUP);
		$set->setField('JENIS_JABATAN_ID', $reqJenisJabatan);
		$set->setField('PEGAWAI_ID', $reqId);
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		
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
			$set->setField('JABATAN_RIWAYAT_ID', $reqRowId);
			if($set->update())
				echo $reqRowId."-Data berhasil disimpan.";
			else 
				echo "xxx-Data gagal disimpan.";
		}
		// echo $set->query;exit;
		
	}
	
	function delete()
	{
		$this->load->model('JabatanRiwayat');
		$set = new JabatanRiwayat();
		
		$reqId =  $this->input->get('reqId');
		$reqMode =  $this->input->get('reqMode');
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		$set->setField("JABATAN_RIWAYAT_ID", $reqId);
		
		if($reqMode == "jabatan_riwayat_0")
		{
			$set->setField("STATUS", "1");
			if($set->updateStatus())
				echo "Data berhasil dihapus.";
			else
				echo "Data gagal dihapus.";	
		}
		elseif($reqMode == "jabatan_riwayat_1")
		{
			$set->setField("STATUS", "2");
			if($set->updateStatus())
				echo "Data berhasil di aktifkan.";
			else
				echo "Data gagal di aktifkan.";	
		}
	}

	function log($riwayatId) 
	{	
		$this->load->model('JabatanRiwayatLog');

		$set = new JabatanRiwayatLog();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		
		$aColumns = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "JABATAN_RIWAYAT_ID");
		$aColumnsAlias = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "JABATAN_RIWAYAT_ID");
		

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
		
		$arrayWhere = array("JABATAN_RIWAYAT_ID" => $riwayatId);
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