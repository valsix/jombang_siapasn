<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class satuan_kerja_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// trow to unauthenticated page!
			//redirect('Login');
		}       
		
		/* GLOBAL VARIABLE */
		$this->USER_GROUP= $this->kauth->getInstance()->getIdentity()->USER_GROUP;
		$this->LOGIN_USER= $this->kauth->getInstance()->getIdentity()->LOGIN_USER;
		$this->SATUAN_KERJA_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID;
		$this->SATUAN_KERJA_BKD_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_BKD_ID;
		$this->LOGIN_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_ID;
		$this->LOGIN_PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID;
		$this->STATUS_SATUAN_KERJA_BKD= $this->kauth->getInstance()->getIdentity()->STATUS_SATUAN_KERJA_BKD;
	}	
	
	function json() 
	{
		$this->load->model('SatuanKerja');

		$set = new SatuanKerja();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		
		$aColumns = array("NAMA", "NAMA_SINGKAT", "SATUAN_KERJA_ID");
		$aColumnsAlias = array("NAMA", "NAMA_SINGKAT", "SATUAN_KERJA_ID");
		
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
		$this->load->model('SatuanKerja');
		
		$set = new SatuanKerja();
		
		$reqId = $this->input->post("reqId");
		$reqMode = $this->input->post("reqMode");
		$reqNama= $this->input->post("reqNama");
		$reqNamaSingkat= $this->input->post("reqNamaSingkat");
		$reqTipeId= $this->input->post("reqTipeId");
		$reqTipeJabatanId= $this->input->post("reqTipeJabatanId");
		$reqJenisJabatanId= $this->input->post("reqJenisJabatanId");
		$reqSatuanKerjaUrutanSurat= $this->input->post("reqSatuanKerjaUrutanSurat");

		$reqKode= $this->input->post("reqKode");
		$reqEselonId= $this->input->post("reqEselonId");
		$reqSatuanKerjaMutasiStatus= $this->input->post("reqSatuanKerjaMutasiStatus");
		$reqStatusSediaan= $this->input->post("reqStatusSediaan");
		$reqSatuanKerjaSediaanId= $this->input->post("reqSatuanKerjaSediaanId");
		$reqRumpunId= $this->input->post("reqRumpunId");
		$reqSatuanKerjaIndukId= $this->input->post("reqSatuanKerjaIndukId");
		$reqNamaPenandatangan= $this->input->post("reqNamaPenandatangan");
		$reqNamaJabatan= $this->input->post("reqNamaJabatan");
		$reqMasaBerlakuAwal= $this->input->post("reqMasaBerlakuAwal");
		$reqMasaBerlakuAkhir= $this->input->post("reqMasaBerlakuAkhir");

		$set->setField('NAMA', $reqNama);
		$set->setField('NAMA_SINGKAT', $reqNamaSingkat);
		$set->setField('TIPE_ID', ValToNullDB($reqTipeId));
		$set->setField('TIPE_JABATAN_ID', ValToNullDB($reqTipeJabatanId));
		$set->setField('JENIS_JABATAN_ID', ValToNullDB($reqJenisJabatanId));
		$set->setField('SATUAN_KERJA_URUTAN_SURAT', ValToNullDB($reqSatuanKerjaUrutanSurat));

		$set->setField('KODE', ValToNullDB($reqKode));
		$set->setField('ESELON_ID', ValToNullDB($reqEselonId));
		$set->setField('NAMA_PENANDATANGAN', ValToNullDB($reqNamaPenandatangan));
		$set->setField('NAMA_JABATAN', ValToNullDB($reqNamaJabatan));
		$set->setField('SATUAN_KERJA_INDUK_ID', ValToNullDB($reqSatuanKerjaIndukId));
		$set->setField('SATUAN_KERJA_MUTASI_STATUS', ValToNullDB($reqSatuanKerjaMutasiStatus));
		$set->setField('STATUS_SEDIAAN', $reqStatusSediaan);
		$set->setField('SATUAN_KERJA_SEDIAAN', ValToNullDB($reqSatuanKerjaSediaanId));
		$set->setField('RUMPUN_ID', ValToNullDB($reqRumpunId));
		$set->setField('MASA_BERLAKU_AWAL', dateToDbCheck($reqMasaBerlakuAwal));
		$set->setField('MASA_BERLAKU_AKHIR', dateToDbCheck($reqMasaBerlakuAkhir));

		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		
		if($reqMode == "insert")
		{
			/*$set->setField('SATUAN_KERJA_PARENT_ID', $reqId); 
			$set->setField("LAST_CREATE_USER", $userLogin->nama);
			$set->setField("LAST_CREATE_DATE", "NOW()");
				
			if($set->insert())
				echo "Data berhasil disimpan.";*/
		}
		else
		{
			$set->setField('SATUAN_KERJA_ID', $reqId); 
				
			if($set->update()){
				echo $reqId."***Data berhasil disimpan.";
			} else {
				echo "xxx***Data gagal disimpan.";
			}
			
		}
		
	}
	
	function delete()
	{

		$this->load->model('SatuanKerja');
		$set = new SatuanKerja();
		
		$reqId =  $this->input->get('reqId');
		$reqMode =  $this->input->get('reqMode');
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		$set->setField("SATUAN_KERJA_ID", $reqId);
		
		if($reqMode == "satuan_kerja_0")
		{
			$set->setField("STATUS", "1");
			if($set->updateStatus())
				$arrJson["PESAN"] = "Data berhasil dihapus.";
			else
				$arrJson["PESAN"] = "Data gagal dihapus.";	
		}
		elseif($reqMode == "satuan_kerja_1")
		{
			$set->setField("STATUS", ValToNullDB($req));
			if($set->updateStatus())
				$arrJson["PESAN"] = "Data berhasil dihapus.";
			else
				$arrJson["PESAN"] = "Data gagal dihapus.";	
		}	
		
		echo json_encode($arrJson);
	}
	
	function namajabatancombo() 
	{
		$this->load->model("SatuanKerja");
		
		$reqId=  $this->input->get('reqId');
		$reqTipeJabatanId= $this->input->get('reqTipeJabatanId');
		$reqTanggalBatas= $this->input->get('reqTanggalBatas');
		
		$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
		if(empty($search_term))
		{
			$search_term = isset($_GET['term']) ? $_GET['term'] : "";
		}
		
		$set = new SatuanKerja();
		
		//$statement= " AND A.STATUS IS NULL";
		$statement.= " AND UPPER(A.NAMA_JABATAN) LIKE '%".strtoupper($search_term)."%' ";
		
		if($reqId == ""){}
		else
		$statement.= " AND A.SATUAN_KERJA_PARENT_ID = ".$reqId;

		if($reqTipeJabatanId == "x2")
		$statement.= " AND (A.TIPE_JABATAN_ID NOT IN (2) OR A.TIPE_JABATAN_ID IS NULL)";

		if($reqTanggalBatas == ""){}
		else
		{
			$statement .= " 
			AND
			COALESCE(A.MASA_BERLAKU_AWAL, TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD'))
			<= TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD')
			AND
			COALESCE(A.MASA_BERLAKU_AKHIR, TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD'))
			>= TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD')";
		}
		//$statement.= " AND ( A.SATUAN_KERJA_ID = ANY( AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$reqId.") ) )";
		
		$set->selectByParams(array(), -1,-1, $statement);
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['text'] = $set->getField("NAMA_JABATAN");	
			$i++;
		}
		echo json_encode($arr_json);
	}

	function namajabatan() 
	{
		$this->load->model("SatuanKerja");
		
		$reqId=  $this->input->get('reqId');
		$reqTipeJabatanId= $this->input->get('reqTipeJabatanId');
		$reqTanggalBatas= $this->input->get('reqTanggalBatas');
		
		$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
		if(empty($search_term))
		{
			$search_term = isset($_GET['term']) ? $_GET['term'] : "";
		}
		
		$set = new SatuanKerja();
		
		//$statement= " AND A.STATUS IS NULL";
		$statement.= " AND UPPER(A.NAMA_JABATAN) LIKE '%".strtoupper($search_term)."%' ";
		
		if($reqId == ""){}
		else
		$statement.= " AND A.SATUAN_KERJA_PARENT_ID = ".$reqId;

		if($reqTipeJabatanId == "x2")
		$statement.= " AND (A.TIPE_JABATAN_ID NOT IN (2) OR A.TIPE_JABATAN_ID IS NULL)";

		if($reqTanggalBatas == ""){}
		else
		{
			$statement .= " 
			AND
			COALESCE(A.MASA_BERLAKU_AWAL, TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD'))
			<= TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD')
			AND
			COALESCE(A.MASA_BERLAKU_AKHIR, TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD'))
			>= TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD')";
		}
		//$statement.= " AND ( A.SATUAN_KERJA_ID = ANY( AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$reqId.") ) )";
		
		$set->selectByParams(array(), 70, 0, $statement);
		//echo $set->query;exit;
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['label'] = $set->getField("NAMA_JABATAN");
			$arr_json[$i]['satuan_kerja'] = $set->getField("SATUAN_KERJA_NAMA_INDUK");
			$arr_json[$i]['eselon_id'] = $set->getField("ESELON_ID");
			$arr_json[$i]['eselon_nama'] = $set->getField("ESELON_NAMA");
			$arr_json[$i]['rumpun_id'] = $set->getField("RUMPUN_ID");
			$arr_json[$i]['rumpun_nama'] = $set->getField("RUMPUN_NAMA");
			$arr_json[$i]['desc'] = $set->getField("NAMA_JABATAN")."<br/><label style='font-size:12px'>".$set->getField("SATUAN_KERJA_NAMA_DETIL")."</label>";
			$i++;
		}
		echo json_encode($arr_json);		
		
	}
	
	function namasatuankerja() 
	{
		$this->load->model("SatuanKerja");
		
		$reqId=  $this->input->get('reqId');
		$reqRowId=  $this->input->get('reqRowId');
		
		$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
		if(empty($search_term))
		{
			$search_term = isset($_GET['term']) ? $_GET['term'] : "";
		}
		
		$set = new SatuanKerja();
		
		//$statement= " AND A.STATUS IS NULL";
		$statement.= " AND UPPER(A.NAMA) LIKE '%".strtoupper($search_term)."%' ";
		
		if($reqId == ""){}
		else
		$statement.= " AND A.SATUAN_KERJA_PARENT_ID = ".$reqId;

		if($reqRowId == ""){}
		else
		{
			$skerja= new SatuanKerja();
			$reqRowId= $skerja->getSatuanKerja($reqRowId);
			unset($skerja);
			//echo $reqSatuanKerjaId;exit;
			$statement.= " AND A.SATUAN_KERJA_ID IN (".$reqRowId.")";
		}

		$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
		)";

		//$statement.= " AND ( A.SATUAN_KERJA_ID = ANY( AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$reqId.") ) )";
		
		$set->selectByParams(array(), 70, 0, $statementAktif.$statement);
		//echo $set->query;exit;
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['label'] = $set->getField("NAMA");
			$arr_json[$i]['satuan_kerja'] = $set->getField("SATUAN_KERJA_NAMA_INDUK");
			$arr_json[$i]['eselon_id'] = $set->getField("ESELON_ID");
			$arr_json[$i]['eselon_nama'] = $set->getField("ESELON_NAMA");
			$arr_json[$i]['desc'] = $set->getField("NAMA_JABATAN")."<br/><label style='font-size:12px'>".$set->getField("SATUAN_KERJA_NAMA_DETIL")."</label>";
			$i++;
		}
		echo json_encode($arr_json);		
		
	}
	
	function cari_nama_jabatan_mutasi() 
	{
		$this->load->model("SatuanKerja");
		
		$reqId=  $this->input->get('reqId');
		$reqTipeJabatanId= $this->input->get('reqTipeJabatanId');
		$reqTanggalBatas= $this->input->get('reqTanggalBatas');

		$reqSatuanKerjaIndukId= $this->input->get("reqSatuanKerjaIndukId");
		$reqSatkerAsalId= $this->input->get("reqSatkerAsalId");
		$reqPegawaiId= $this->input->get("reqPegawaiId");
		$reqSatkerPembuatId= $this->SATUAN_KERJA_ID;

		$kondisilihatsatuankerja= "";
		// kondisi bukan untuk admin n teknis
		if($reqSatkerPembuatId == ""){}
		else
		{
			$skerja= new SatuanKerja();
			$dataSatkerPembuatId= $skerja->getSatuanKerja($reqSatkerPembuatId);
			$arrSatkerPembuatId= explode(",", $dataSatkerPembuatId);
			unset($skerja);
			// print_r($arrSatkerPembuatId);exit();
			
			if (in_array($reqSatkerAsalId, $arrSatkerPembuatId, true)) {}
			else
			{
				// kalau 1 maka pegawai dari satuan kerja lain, maka set satuan kerja sesuai dengan satuankerja pembuat ke bawah
				$kondisilihatsatuankerja= "1";
			}
		}

		$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
		if(empty($search_term))
		{
			$search_term = isset($_GET['term']) ? $_GET['term'] : "";
		}
		
		$set = new SatuanKerja();
		
		//$statement= " AND A.STATUS IS NULL";
		$statement.= " AND UPPER(A.NAMA_JABATAN) LIKE '%".strtoupper($search_term)."%' ";
		
		if($reqId == ""){}
		else
		$statement.= " AND A.SATUAN_KERJA_PARENT_ID = ".$reqId;

		if($reqTipeJabatanId == "x2")
		$statement.= " AND (A.TIPE_JABATAN_ID NOT IN (2) OR A.TIPE_JABATAN_ID IS NULL)";

		if($reqTanggalBatas == ""){}
		else
		{
			$statement .= " 
			AND
			COALESCE(A.MASA_BERLAKU_AWAL, TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD'))
			<= TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD')
			AND
			COALESCE(A.MASA_BERLAKU_AKHIR, TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD'))
			>= TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD')";
		}
		//$statement.= " AND ( A.SATUAN_KERJA_ID = ANY( AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$reqId.") ) )";

		if($kondisilihatsatuankerja == "1")
		{
			$statement.= " AND A.SATUAN_KERJA_ID IN (".$dataSatkerPembuatId.")";
		}
		
		$set->selectByParams(array(), 70, 0, $statement);
		//echo $set->query;exit;
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['label'] = $set->getField("NAMA_JABATAN");
			$arr_json[$i]['satuan_kerja'] = $set->getField("SATUAN_KERJA_NAMA_INDUK");
			$arr_json[$i]['eselon_id'] = $set->getField("ESELON_ID");
			$arr_json[$i]['eselon_nama'] = $set->getField("ESELON_NAMA");
			$arr_json[$i]['desc'] = $set->getField("NAMA_JABATAN")."<br/><label style='font-size:12px'>".$set->getField("SATUAN_KERJA_NAMA_DETIL")."</label>";
			$i++;
		}
		echo json_encode($arr_json);		
		
	}

	function cari_satuan_kerja_mutasi() 
	{
		$this->load->model("SatuanKerja");
		
		$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
		if(empty($search_term))
		{
			$search_term = isset($_GET['term']) ? $_GET['term'] : "";
		}

		$reqTanggalBatas= $this->input->get("reqTanggalBatas");
		$reqSatuanKerjaIndukId= $this->input->get("reqSatuanKerjaIndukId");
		$reqSatkerAsalId= $this->input->get("reqSatkerAsalId");
		$reqPegawaiId= $this->input->get("reqPegawaiId");
		$reqSatkerPembuatId= $this->SATUAN_KERJA_ID;

		$kondisilihatsatuankerja= "";
		if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1 || $this->SATUAN_KERJA_ID == ""){}
		else
		{
			// kondisi bukan untuk admin n teknis
			if($reqSatkerPembuatId == ""){}
			else
			{
				$skerja= new SatuanKerja();
				$dataSatkerPembuatId= $skerja->getSatuanKerja($reqSatkerPembuatId);
				$arrSatkerPembuatId= explode(",", $dataSatkerPembuatId);
				unset($skerja);
				// print_r($arrSatkerPembuatId);exit();
				
				if (in_array($reqSatkerAsalId, $arrSatkerPembuatId, true)) {}
				else
				{
					// kalau 1 maka pegawai dari satuan kerja lain, maka set satuan kerja sesuai dengan satuankerja pembuat ke bawah
					$kondisilihatsatuankerja= "1";
				}
			}
			// echo $reqSatkerAsalId."--".$reqSatkerSatkerPembuatId."-".$kondisilihatsatuankerja;
			// exit();
		}

		$set = new SatuanKerja();
		
		//$statement= " AND A.STATUS IS NULL";
		//$statement.= " AND UPPER(A.NAMA) LIKE '%".strtoupper($search_term)."%' ";
		$statement.= " AND UPPER(A.NAMA) LIKE '".strtoupper($search_term)."%' ";

		if($reqTanggalBatas == "")
		{
			$statementAktif= " AND EXISTS(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			)";
		}
		else
		{
			$statementAktif= " AND EXISTS(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL, TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD')) <= TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD') AND COALESCE(MASA_BERLAKU_AKHIR, TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD')) >= TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD')
				AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			)";
		}

		// kalau 1 maka pegawai dari satuan kerja lain, maka set satuan kerja sesuai dengan satuankerja pembuat ke bawah
		if($kondisilihatsatuankerja == "1")
		{
			$statement.= " AND A.SATUAN_KERJA_ID IN (".$dataSatkerPembuatId.")";
		}

		$set->selectByParams(array(), 70, 0, $statementAktif.$statement);
		// echo $set->query;exit;
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("SATUAN_KERJA_ID");
			//$arr_json[$i]['label'] = $set->getField("NAMA");
			$arr_json[$i]['label'] = $set->getField("NAMA");
			$arr_json[$i]['desc'] = $set->getField("NAMA")."<br/><label style='font-size:12px'>".$set->getField("SATUAN_KERJA_NAMA_DETIL")."</label>";
			$i++;
		}
		echo json_encode($arr_json);		
		
	}

	function auto() 
	{
		$this->load->model("SatuanKerja");
		
		$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
		if(empty($search_term))
		{
			$search_term = isset($_GET['term']) ? $_GET['term'] : "";
		}
		
		$reqTanggalBatas= $this->input->get("reqTanggalBatas");
		$reqSatuanKerjaIndukId= $this->input->get("reqSatuanKerjaIndukId");

		$set = new SatuanKerja();
		
		//$statement= " AND A.STATUS IS NULL";
		//$statement.= " AND UPPER(A.NAMA) LIKE '%".strtoupper($search_term)."%' ";
		$statement.= " AND UPPER(A.NAMA) LIKE '".strtoupper($search_term)."%' ";

		if($reqTanggalBatas == "")
		{
			$statementAktif= " AND EXISTS(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			)";
		}
		else
		{
			$statementAktif= " AND EXISTS(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL, TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD')) <= TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD') AND COALESCE(MASA_BERLAKU_AKHIR, TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD')) >= TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD')
				AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			)";
		}

		// set untuk mutasi SATUAN_KERJA_MUTASI_STATUS HARUS 1
		if($reqSatuanKerjaIndukId == ""){}
		else
		{
			$skerja= new SatuanKerja();
			$reqSatuanKerjaIndukId= $skerja->getSatuanKerja($reqSatuanKerjaIndukId);
			unset($skerja);
			//echo $reqSatuanKerjaId;exit;
			$statement.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaIndukId.") AND A.SATUAN_KERJA_MUTASI_STATUS = '1'" ;
			// $statement.= " AND A.SATUAN_KERJA_INDUK_ID IN (SELECT SATUAN_KERJA_INDUK_ID FROM SATUAN_KERJA X WHERE 1=1 AND X.SATUAN_KERJA_ID = ".$reqSatuanKerjaIndukId.")";
		}

		$set->selectByParams(array(), 70, 0, $statementAktif.$statement);
		//echo $set->query;exit;
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("SATUAN_KERJA_ID");
			//$arr_json[$i]['label'] = $set->getField("NAMA");
			$arr_json[$i]['label'] = $set->getField("NAMA");
			$arr_json[$i]['desc'] = $set->getField("NAMA")."<br/><label style='font-size:12px'>".$set->getField("SATUAN_KERJA_NAMA_DETIL")."</label>";
			$arr_json[$i]['rumpun_id'] = $set->getField("RUMPUN_ID");
			$arr_json[$i]['rumpun_nama'] = $set->getField("RUMPUN_NAMA");
			$i++;
		}
		echo json_encode($arr_json);		
		
	}
	
	function combo() 
	{
		$this->load->model("SatuanKerja");
		$set = new SatuanKerja();
		
		$set->selectByParams();
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['text'] = $set->getField("NAMA");	
			$i++;
		}
		echo json_encode($arr_json);		
		
	}	
	
	function combotree() 
	{
		$this->load->model("SatuanKerja");
		$set = new SatuanKerja();
		$statement= " AND A.SATUAN_KERJA_PARENT_ID = 0";
		$set->selectByParams(array(),-1,-1, $statement);
		//echo $set->query;exit;
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['text'] = $set->getField("NAMA");
			$arr_json[$i]['children'] = $this->combotreegetchild($set->getField("SATUAN_KERJA_ID"));
			$i++;
		}
		echo json_encode($arr_json);
	}
	
	function combotreegetchild($id)
	{
		$this->load->model("SatuanKerja");
		
		$statement= " AND A.SATUAN_KERJA_PARENT_ID = ".$id;
		$set = new SatuanKerja();
		$set->selectByParams(array(), -1, -1, $statement);
		//echo $set->query;exit;
		
		$arr_json = array();
		$j=0;
		while($set->nextRow())
		{
			$arr_json[$j]['id'] = $set->getField("SATUAN_KERJA_ID");
			$arr_json[$j]['text'] = $set->getField("NAMA");
			
			$statement= " AND A.SATUAN_KERJA_PARENT_ID = ".$set->getField("SATUAN_KERJA_ID");
			$set_detil= new SatuanKerja();
			$record= $set_detil->getCountByParams(array(), $statement);
			unset($set_detil);
			
			if($record > 0)
				$arr_json[$j]['children'] = $this->combotreegetchild($set->getField("SATUAN_KERJA_ID"));
				
			$j++;
		}
		return $arr_json;
	}

	function treepilih() 
	{
		$this->load->model("SatuanKerja");
		$set = new SatuanKerja();

		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;//
		$id = isset($_POST['id']) ? $_POST['id'] : 0;//
		$result = array();
		
		$reqId =  $this->input->get('reqId');
		//$statement = " AND A.SATKER_ID = '".$reqId."'";
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		$statementAktif= "";
		if($reqSatuanKerjaId == "")
		{
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1")
			{
				$statementAktif= " AND EXISTS(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
				)";
			}
		}
		else
		{

			$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			)";

			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
			{
				$reqSatuanKerjaId= "";
				// if($tempSatuanKerjaId == ""){}
				// else
				// {
				// 	$reqSatuanKerjaId= $tempSatuanKerjaId;
				// 	$skerja= new SatuanKerja();
				// 	$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				// 	unset($skerja);
				// 	//echo $reqSatuanKerjaId;exit;
				// 	$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				// }
			}
			// else
			// {
			// 	$skerja= new SatuanKerja();
			// 	$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
			// 	unset($skerja);
			// 	//echo $reqSatuanKerjaId;exit;
			// 	$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			// 	//$statement= " AND ( A.SATUAN_KERJA_ID = ANY( AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$reqSatuanKerjaId.") ) OR A.SATUAN_KERJA_ID = ".$reqSatuanKerjaId." )";
			// }
		}
		// echo $statementAktif;exit();

		$i=0;
		// echo $id;
		if ($id == 0)
		{
			$result["total"] = 0;
			//$set->selectByParamsTreeMonitoring(array("A.SATUAN_KERJA_PARENT_ID" => 0), $rows, $offset, $statement);

			if($reqSatuanKerjaId == "")
			{
				$statement.= " AND A.SATUAN_KERJA_PARENT_ID = 0";
			}
			else
			{
				$statement.= " AND A.SATUAN_KERJA_ID = ".$reqSatuanKerjaId;
			}
			
			$tempSatuanKerjaInduk= "Pemerintah Kabupaten Jombang";
			$tempSatuanKerjaIndukInfo= "Semua Satuan Kerja";
			$i=0;
			$items[$i]['ID'] = "0";
			$items[$i]['NAMA'] = "<a onclick=\"calltreeid('', '".$tempSatuanKerjaIndukInfo."')\">".$tempSatuanKerjaInduk."</a>";
			// $items[$i]['state'] = $this->has_child("", $statement) ? 'closed' : 'open';
			$i++;

			$set->selectByParamsTreeMonitoring(array(), -1, -1, $statementAktif.$statement);
			// echo $set->query;exit;
			//echo $set->errorMsg;exit;
			while($set->nextRow())
			{
				$items[$i]['ID'] = $set->getField("SATUAN_KERJA_ID");
				$items[$i]['NAMA'] = "<a onclick=\"calltreeid('".$set->getField("SATUAN_KERJA_ID")."', '".$set->getField("SATUAN_KERJA_NAMA_DETIL")."')\">".$set->getField("NAMA")."</a>";
				$items[$i]['state'] = $this->has_child($set->getField("SATUAN_KERJA_ID"), $statementAktif) ? 'closed' : 'open';
				$i++;
			}
			$result["rows"] = $items;
		} 
		else 
		{
			$set->selectByParamsTreeMonitoring(array("A.SATUAN_KERJA_PARENT_ID" => $id), -1, -1, $statementAktif.$statement);
			//echo $set->query;exit;
			while($set->nextRow())
			{
				$result[$i]['ID'] = $set->getField("SATUAN_KERJA_ID");
				$result[$i]['NAMA'] = "<a onclick=\"calltreeid('".$set->getField("SATUAN_KERJA_ID")."', '".$set->getField("SATUAN_KERJA_NAMA_DETIL")."')\">".$set->getField("NAMA")."</a>";
				$result[$i]['state'] = $this->has_child($set->getField("SATUAN_KERJA_ID"), $statementAktif) ? 'closed' : 'open';
				$i++;
			}
		}
		
		echo json_encode($result);
	}
	
	function tree() 
	{
		$this->load->model("SatuanKerja");
		$set = new SatuanKerja();

		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;//
		$id = isset($_POST['id']) ? $_POST['id'] : 0;//
		$result = array();
		
		$reqId =  $this->input->get('reqId');
		//$statement = " AND A.SATKER_ID = '".$reqId."'";
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		$statementAktif= "";
		if($reqSatuanKerjaId == "")
		{
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1")
			{
				$statementAktif= " AND EXISTS(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
				)";
			}
		}
		else
		{

			$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			)";

			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1")
			{
				$reqSatuanKerjaId= "";
			}
		}
		// echo $statement;exit();

		if ($id == 0)
		{
			$result["total"] = 0;
			//$set->selectByParamsTreeMonitoring(array("A.SATUAN_KERJA_PARENT_ID" => 0), $rows, $offset, $statement);
			$set->selectByParamsTreeMonitoring(array("A.SATUAN_KERJA_PARENT_ID" => 0), -1, -1, $statementAktif.$statement);
			//echo $set->query;exit;
			//echo $set->errorMsg;exit;
			$i=0;
			while($set->nextRow())
			{
				$items[$i]['ID'] = $set->getField("SATUAN_KERJA_ID");
				$items[$i]['NAMA'] = $set->getField("NAMA");
				$items[$i]['LINK_URL_INFO'] = $set->getField("LINK_URL_INFO");
				$items[$i]['state'] = $this->has_child($set->getField("SATUAN_KERJA_ID"), $statementAktif) ? 'closed' : 'open';
				$i++;
			}
			$result["rows"] = $items;
		} 
		else 
		{
			$set->selectByParamsTreeMonitoring(array("A.SATUAN_KERJA_PARENT_ID" => $id), -1, -1, $statementAktif.$statement);
			//echo $set->query;exit;
			$i=0;
			while($set->nextRow())
			{
				$result[$i]['ID'] = $set->getField("SATUAN_KERJA_ID");
				$result[$i]['NAMA'] = $set->getField("NAMA");
				$result[$i]['LINK_URL_INFO'] = $set->getField("LINK_URL_INFO");
				$result[$i]['state'] = $this->has_child($set->getField("SATUAN_KERJA_ID"), $statementAktif) ? 'closed' : 'open';
				$i++;
			}
		}
		
		echo json_encode($result);
	}	
	
	function has_child($id, $stat)
	{
		$child = new SatuanKerja();
		$child->selectByParamsTreeMonitoring(array("SATUAN_KERJA_PARENT_ID" => $id), -1,-1, $stat);
		// echo $child->query;exit;
		$child->firstRow();
		$tempId= $child->getField("SATUAN_KERJA_ID");
		//echo $child->errorMsg;exit;
		//echo $tempId;exit;
		if($tempId == "")
		return false;
		else
		return true;
		unset($child);
	}
		
}
?>