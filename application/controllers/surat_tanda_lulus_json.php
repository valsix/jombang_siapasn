<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class surat_tanda_lulus_json extends CI_Controller {

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
	
	function combo() 
	{
		$this->load->model("PendidikanRiwayat");
		$set = new PendidikanRiwayat();
		
		$reqId= $this->input->get("reqId");
		$reqPegawaiId= $this->input->get("reqPegawaiId");
		
		$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId." AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2') AND A.PENDIDIKAN_ID IN (".$reqId.")";
		$set->selectByParams(array(), -1,-1, $statement);
		// echo $set->query;exit();

		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("PENDIDIKAN_RIWAYAT_ID")."-".$set->getField("PENDIDIKAN_ID");
			$arr_json[$i]['text'] = $set->getField("PENDIDIKAN_NAMA")." - Tgl STTB ".dateToPageCheck($set->getField("TANGGAL_STTB"));
			$i++;
		}
		echo json_encode($arr_json);		
		
	}

	function add()
	{
		// validasi untuk file
		$this->load->library('globalfilepegawai');
		$reqLinkFile= $_FILES['reqLinkFile'];
		// print_r($reqLinkFile);exit;

		// untuk validasi required file
		$validasifilerequired= new globalfilepegawai();
		$vpost= $this->input->post();
		$vinforequired= $validasifilerequired->validasifilerequired($vpost, $reqLinkFile);
		if(!empty($vinforequired))
		{
			echo "xxx-".$vinforequired;
			exit;
		}

		$this->load->model('SuratTandaLulus');
		
		$set = new SuratTandaLulus();
		
		$reqId= $this->input->post("reqId");
		$reqMode= $this->input->post("reqMode");
		$reqRowId= $this->input->post('reqRowId');

		$reqJenisId= $this->input->post('reqJenisId');
		$reqNoStlud= $this->input->post('reqNoStlud');
		$reqTglStlud= $this->input->post('reqTglStlud');
		$reqNilaiNpr= $this->input->post('reqNilaiNpr');
		$reqNilaiNt= $this->input->post('reqNilaiNt');
		$reqTanggalMulai= $this->input->post('reqTanggalMulai');
		$reqTanggalAkhir= $this->input->post('reqTanggalAkhir');
		$reqPendidikanRiwayatId= $this->input->post('reqPendidikanRiwayatId');
		$reqPendidikanId= $this->input->post('reqPendidikanId');

		$set->setField('PENDIDIKAN_RIWAYAT_ID', ValToNullDB($reqPendidikanRiwayatId));
		$set->setField('PENDIDIKAN_ID', ValToNullDB($reqPendidikanId));

		$set->setField("JENIS_ID", $reqJenisId);
		$set->setField("NO_STLUD", $reqNoStlud);
		$set->setField('TANGGAL_STLUD', dateToDBCheck($reqTglStlud));
		$set->setField('TANGGAL_MULAI', dateToDBCheck($reqTanggalMulai));
		$set->setField('TANGGAL_AKHIR', dateToDBCheck($reqTanggalAkhir));
		$set->setField('NILAI_NPR', ValToNullDB(CommaToDot($reqNilaiNpr)));
		$set->setField('NILAI_NT', ValToNullDB(CommaToDot($reqNilaiNt)));

		$set->setField('PEGAWAI_ID', ValToNullDB($reqId));
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");

		$infosimpan= "";
		if($reqMode == "insert")
		{
			if($set->insert())
			{
				$reqRowId= $set->id;
				$infosimpan= "1";
			}
		}
		else
		{
			$set->setField('SURAT_TANDA_LULUS_ID', $reqRowId);
			if($set->update())
			{
				$infosimpan= "1";
			}
		}
		// echo $set->query;exit;

		if($infosimpan == "1")
		{
			// untuk simpan file
			$vpost= $this->input->post();
			$vsimpanfilepegawai= new globalfilepegawai();
			$vsimpanfilepegawai->simpanfilepegawai($vpost, $reqRowId, $reqLinkFile);
			
			echo $reqRowId."-Data berhasil disimpan.";
		}
		else
		{
			echo "xxx-Data gagal disimpan.";	
		}
		
	}
	
	function delete()
	{
		$this->load->model('SuratTandaLulus');
		$set = new SuratTandaLulus();
		
		$reqId =  $this->input->get('reqId');
		$reqMode =  $this->input->get('reqMode');
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		$set->setField("SURAT_TANDA_LULUS_ID", $reqId);
		
		if($reqMode == "surat_tanda_lulus_0")
		{
			$set->setField("STATUS", "1");
			if($set->updateStatus())
				echo "Data berhasil dihapus.";
			else
				echo "Data gagal dihapus.";	
		}
		elseif($reqMode == "surat_tanda_lulus_1")
		{
			$set->setField("STATUS", "2");
			if($set->updateStatus())
				echo "Data berhasil diubah.";
			else
				echo "Data gagal dihapus.";	
		}	
	}

	function log($riwayatId) 
	{	
		$this->load->model('SuratTandaLulusLog');

		$set = new SuratTandaLulusLog();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		
		$aColumns = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "ANAK_ID");
		$aColumnsAlias = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "ANAK_ID");
		

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
		
		$arrayWhere = array("ANAK_ID" => $riwayatId);
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

	function combokenaikanpangkat()
	{
		$this->load->model("SuratTandaLulus");
		
		$reqId= $this->input->get("reqId");
		$reqJenis= $this->input->get("reqJenis");
		$cekquery= $this->input->get("c");
		
		$set = new SuratTandaLulus();
		$statement= " AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2') AND A.PEGAWAI_ID = ".$reqId;

		if($reqJenis == "ujiandinas")
			$statement.= " AND A.JENIS_ID IN (1,2)";
		else if($reqJenis == "penyesuaianijazah")
			$statement.= " AND A.JENIS_ID IN (3,4,5)";
		
		$set->selectByParams(array(), -1,-1, $statement);
		if(!empty($cekquery))
		{
			echo $set->query;exit();
		}
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id']= $set->getField("SURAT_TANDA_LULUS_ID");
			$arr_json[$i]['text']= $set->getField("NO_STLUD");
			$arr_json[$i]['tanggal']= dateToPageCheck($set->getField('TANGGAL_STLUD'));
			$i++;
		}
		echo json_encode($arr_json);
	}

}
?>