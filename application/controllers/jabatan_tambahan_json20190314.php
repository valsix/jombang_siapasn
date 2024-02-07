<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class jabatan_tambahan_json extends CI_Controller {

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
	
	function add()
	{
		$this->load->model('JabatanTambahan');
		
		$set = new JabatanTambahan();
		
		$reqId= $this->input->post("reqId");
		$reqMode= $this->input->post("reqMode");
		$reqRowId= $this->input->post('reqRowId');

		$reqStatusPlt= $this->input->post("reqStatusPlt");
		$reqIsManual= $this->input->post("reqIsManual");
		$reqTugasTambahanId= $this->input->post("reqTugasTambahanId");
		$reqTmtTugas= $this->input->post("reqTmtTugas");
		$reqTmtWaktuTugas= $this->input->post("reqTmtWaktuTugas");
		$reqNamaTugas= $this->input->post("reqNamaTugas");
		$reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
		$reqPejabatPenetapId= $this->input->post("reqPejabatPenetapId");
		$reqNoSk= $this->input->post("reqNoSk");
		$reqTanggalSk= $this->input->post("reqTanggalSk");
		$reqTmtJabatan= $this->input->post("reqTmtJabatan");
		$reqTmtJabatanAkhir= $this->input->post("reqTmtJabatanAkhir");
		$reqSatker= $this->input->post("reqSatker");
		$reqSatkerId= $this->input->post("reqSatkerId");
		$reqNoPelantikan= $this->input->post("reqNoPelantikan");
		$reqTanggalPelantikan= $this->input->post("reqTanggalPelantikan");
		$reqTunjangan= $this->input->post("reqTunjangan");
		$reqBulanDibayar= $this->input->post("reqBulanDibayar");
		
		$set->setField('NO_PELANTIKAN', $reqNoPelantikan);
		$set->setField('TANGGAL_PELANTIKAN', dateToDBCheck($reqTanggalPelantikan));
		$set->setField("TUNJANGAN", ValToNullDB(dotToNo($reqTunjangan)));
		$set->setField('BULAN_DIBAYAR', dateToDBCheck($reqBulanDibayar));
		$set->setField('NAMA', $reqNamaTugas);
		$set->setField('PEJABAT_PENETAP_ID', $reqPejabatPenetapId);
		$set->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
		$set->setField('TUGAS_TAMBAHAN_ID', ValToNullDB($reqTugasTambahanId));
		$set->setField('NO_SK', $reqNoSk);
		$set->setField('TANGGAL_SK', dateToDBCheck($reqTanggalSk));
		
		$set->setField('SATKER_ID', ValToNullDB($reqSatkerId));
		$set->setField('SATKER_NAMA', $reqSatker);
		$set->setField('IS_MANUAL', ValToNullDB($reqIsManual));
		$set->setField('STATUS_PLT', $reqStatusPlt);
		
		if(strlen($reqTmtWaktuTugas) == 5)
		$set->setField('TMT_JABATAN', dateTimeToDBCheck($reqTmtTugas." ".$reqTmtWaktuTugas));
		else
		$set->setField('TMT_JABATAN', dateToDBCheck($reqTmtTugas));
		$set->setField('TMT_JABATAN_AKHIR', dateToDBCheck($reqTmtJabatanAkhir));

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
			$set->setField('JABATAN_TAMBAHAN_ID', $reqRowId);
			if($set->update())
				echo $reqRowId."-Data berhasil disimpan.";
			else 
				// echo $set->query;exit;
				echo "xxx-Data gagal disimpan.";
		}
		
	}
	
	function namajabatan() 
	{
		$this->load->model('JabatanTambahan');
		$this->load->model('SatuanKerja');
		
		$reqId=  $this->input->get('reqId');
		$reqStatusPlt=  $this->input->get('reqStatusPlt');
		$reqTanggalBatas= $this->input->get('reqTanggalBatas');

		$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
		
		// set tes
		/*$reqSatuanKerjaIndukId= $this->SATUAN_KERJA_ID;
		// echo $reqSatuanKerjaIndukId;exit();
		if($reqSatuanKerjaIndukId == ""){}
		else
		{
			$skerja= new SatuanKerja();
			$reqSatuanKerjaIndukId= $skerja->getSatuanKerja($reqSatuanKerjaIndukId);
			unset($skerja);
			// echo $reqSatuanKerjaIndukId;exit;
			$statementSatuanKerja= " AND (A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaIndukId.") OR CAST(A.SATUAN_KERJA_ID_OLD AS NUMERIC) IN (".$reqSatuanKerjaIndukId.") )" ;
		}*/

		$set = new JabatanTambahan();
		if($reqStatusPlt == "plt")
		{
			$statement.= " AND UPPER(A.NAMA_JABATAN) LIKE '%".strtoupper($search_term)."%' ";
			if($reqTanggalBatas == "")
			{
				$statement .= " AND 1 = 2";
			}
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
			$set->selectByParamsPlt(array(), 70, 0, $statement.$statementSatuanKerja);
		}
		elseif($reqStatusPlt == "plh")
		{
			$statement.= " AND UPPER(A.NAMA_JABATAN) LIKE '%".strtoupper($search_term)."%' ";
			if($reqTanggalBatas == "")
			{
				$statement .= " AND 1 = 2";
			}
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
			$set->selectByParamsPlh(array(), 70, 0, $statement.$statementSatuanKerja);
		}
		else
		{
			$statement.= " AND UPPER(A.NAMA) LIKE '%".strtoupper($search_term)."%' ";
			$set->selectByParamsTugasDetilTambahan(array(), 70, 0, $reqStatusPlt, $statement, $statementSatuanKerja);
		}
		// echo $set->query;exit;
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$tempReadonly= "";
			if($set->getField("SATKER_ID") == "")
			$tempReadonly= "1";
			
			$arr_json[$i]['id'] = $set->getField("TUGAS_TAMBAHAN_ID");
			$arr_json[$i]['label'] = $set->getField("NAMA");
			$arr_json[$i]['satuan_kerja'] = $set->getField("SATKER_NAMA");
			$arr_json[$i]['satuan_kerja_id'] = $set->getField("SATKER_ID");
			$arr_json[$i]['satuan_kerja_validasi'] = $tempReadonly;
			$arr_json[$i]['desc'] = $set->getField("NAMA")."<br/><label style='font-size:12px'>".$set->getField("SATUAN_KERJA_NAMA_DETIL")."</label>";
			$i++;
		}
		echo json_encode($arr_json);
		
	}
	
	function delete()
	{
		$this->load->model('JabatanTambahan');
		$set = new JabatanTambahan();
		
		$reqId =  $this->input->get('reqId');
		$reqMode =  $this->input->get('reqMode');
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		$set->setField("JABATAN_TAMBAHAN_ID", $reqId);
		
		if($reqMode == "jabatan_tambahan_0")
		{
			$set->setField("STATUS", "1");
			if($set->updateStatus())
				echo "Data berhasil dihapus.";
			else
				echo "Data gagal dihapus.";	
		}
		elseif($reqMode == "jabatan_tambahan_1")
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
		$this->load->model('JabatanTambahanLog');

		$set = new JabatanTambahanLog();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		
		$aColumns = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "JABATAN_TAMBAHAN_ID");
		$aColumnsAlias = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "JABATAN_TAMBAHAN_ID");
		

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

$arrayWhere = array("JABATAN_TAMBAHAN_ID" => $riwayatId);
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