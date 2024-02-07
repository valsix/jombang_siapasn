<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class pangkat_riwayat_json extends CI_Controller {

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
		
		$this->load->model('PangkatRiwayat');
		$this->load->model('KenaikanGajiBerkala');
		$this->load->model('PejabatPenetap');
		
		$set = new PangkatRiwayat();
		
		$reqId= $this->input->post("reqId");
		$reqMode= $this->input->post("reqMode");
		$reqRowId= $this->input->post('reqRowId');
		$reqPeriode= $this->input->post('reqPeriode');

		$reqTglStlud= $this->input->post('reqTglStlud');
		$reqStlud= $this->input->post('reqStlud');
		$reqNoStlud= $this->input->post('reqNoStlud');
		$reqNoNota= $this->input->post('reqNoNota');
		$reqNoSk= $this->input->post('reqNoSk');
		$reqNoUrutCetak= $this->input->post('reqNoUrutCetak');
		$reqTh= $this->input->post('reqTh');
		$reqTempTh= $this->input->post('reqTempTh');
		$reqBl= $this->input->post('reqBl');
		$reqTempBl= $this->input->post('reqTempBl');
		$reqKredit= $this->input->post('reqKredit');
		$reqJenisKp= $this->input->post('reqJenisKp');
		$reqKeterangan= $this->input->post('reqKeterangan');
		$reqGajiPokok= $this->input->post('reqGajiPokok');
		$reqTglNota= $this->input->post('reqTglNota');
		$reqTglSk= $this->input->post('reqTglSk');
		$reqTmtGol= $this->input->post('reqTmtGol');
		$reqPejabatPenetapId= $this->input->post('reqPejabatPenetapId');
		$reqPejabatPenetap= $this->input->post('reqPejabatPenetap');
		$reqGolRuang= $this->input->post('reqGolRuang');
		
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
		// $set->setField('PEJABAT_PENETAP_ID', ValToNullDB($reqPejabatPenetapId));	
		$set->setField('PEJABAT_PENETAP_ID', $reqPejabatPenetapId);
		$set->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
		$set->setField('STLUD', $reqStlud);
		$set->setField('NO_STLUD', $reqNoStlud);
		$set->setField('NO_NOTA', $reqNoNota);
		$set->setField('NO_SK', $reqNoSk);
		$set->setField('NO_URUT_CETAK', ValToNullDB($reqNoUrutCetak));
		$set->setField('MASA_KERJA_TAHUN', ValToNullDB($reqTh));
		$set->setField('MASA_KERJA_BULAN', ValToNullDB($reqBl));
		$set->setField("GAJI_POKOK", ValToNullDB(dotToNo($reqGajiPokok)));
		$set->setField('KREDIT', ValToNullDB(CommaToDot($reqKredit)));
		$set->setField('KETERANGAN', $reqKeterangan);
		$set->setField('JENIS_RIWAYAT', $reqJenisKp);
		$set->setField('TANGGAL_STLUD', dateToDBCheck($reqTglStlud));
		$set->setField('TANGGAL_NOTA', dateToDBCheck($reqTglNota));
		$set->setField('TANGGAL_SK', dateToDBCheck($reqTglSk));
		$set->setField('TMT_PANGKAT', dateToDBCheck($reqTmtGol));
		$set->setField('PEGAWAI_ID', $reqId);
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		
		$reqstatushitung= "";
		$reqsimpan= "";
		
		if($reqMode == "insert")
		{
			if($set->insert())
			{
				$reqsimpan= "1";
				$reqRowId= $set->id;
			}
		}
		else
		{
			$set->setField('PANGKAT_RIWAYAT_ID', $reqRowId);
			if($set->update())
			{
				$reqsimpan= "1";
			}
		}
		// echo $set->query;exit;
		if($reqPeriode == ""){}
		else
		{
			if($reqJenisKp == 8 || $reqJenisKp == 9){}
			else
			{
				//apbila ada data baru,
				//- apabila tmt dasar < tmt data baru 
				//  dan tmt data baru <= tmt kgb baru
				//  dan tgl dasar < tgl data baru 
				//  dan tgl data baru <= tgl kgb baru
				
				$statement= " AND A.PEGAWAI_ID = ".$reqId." AND A.STATUS_KGB IN ('2','3') AND A.PERIODE = '".$reqPeriode."'";
				$set_kgb= new KenaikanGajiBerkala();
				$set_kgb->selectByParamsData(array(), -1,-1, $statement);
				$set_kgb->firstRow();
				$tempDasarTanggalVal= $tempDasarTanggal= dateToPageCheck($set_kgb->getField("TANGGAL_SK"));
				$tempDasarTmtVal= $tempDasarTmt= dateToPageCheck($set_kgb->getField("TMT_LAMA"));
				$tempKgbTanggal= dateToPageCheck($set_kgb->getField("TANGGAL_BARU"));
				$tempKgbTmt= dateToPageCheck($set_kgb->getField("TMT_BARU"));
				
				$tempDasarTanggal= strtotime($tempDasarTanggal);
				$tempDasarTmt= strtotime($tempDasarTmt);
				$tempKgbTanggal= strtotime($tempKgbTanggal);
				$tempKgbTmt= strtotime($tempKgbTmt);
				$tempDataBaruTanggal= strtotime($reqTglSk);
				$tempDataBaruTmt= strtotime($reqTmtGol);
				
				//if($tempDasarTmt < $tempDataBaruTmt && $tempDataBaruTmt <= $tempKgbTmt && $tempDasarTanggal < $tempDataBaruTanggal && $tempDataBaruTanggal <= $tempKgbTanggal)
				//if($tempDasarTmt < $tempDataBaruTmt && $tempDataBaruTmt < $tempKgbTmt && $tempDasarTanggal < $tempDataBaruTanggal && $tempDataBaruTanggal <= $tempKgbTanggal)
				if($tempDasarTmt < $tempDataBaruTmt && $tempDataBaruTmt < $tempKgbTmt && $tempDasarTanggal < $tempDataBaruTanggal)
				$reqstatushitung= "1";
				
				if($reqTh == $reqTempTh){}
				else
				$reqstatushitung= "1";
				
				if($reqBl == $reqTempBl){}
				else
				$reqstatushitung= "1";
			
				//echo $tempDasarTmt." < ".$tempDataBaruTmt." && ".$tempDataBaruTmt." < ".$tempKgbTmt." && ".$tempDasarTanggal." < ".$tempDataBaruTanggal;
				//exit;
				
				//echo $tempDasarTanggalVal." == ".$reqTglSk." && ".$tempDasarTmtVal." == ".$reqTmtGol;
				//exit;
				
				if($tempDasarTanggalVal == $reqTglSk && $tempDasarTmtVal == $reqTmtGol)
					$reqstatushitung= "1";
			
			}
		}
		
		if($reqsimpan == "1")
		{
			if($reqstatushitung == "1")
			{
				$set = new KenaikanGajiBerkala();
				
				$set->setField('PERIODE', $reqPeriode);
				$set->setField('PEGAWAI_ID', $reqId);
				$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
				$set->setField("LAST_USER", $this->LOGIN_USER);
				$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
				$set->setField("LAST_DATE", "NOW()");
				$set->updateStatusHitung();
			}

			// khusus 
			$vRowId= $this->input->post('vRowId');
			
			// untuk simpan file
			$vpost= $this->input->post();
			$vsimpanfilepegawai= new globalfilepegawai();
			$vsimpanfilepegawai->simpanfilepegawai($vpost, $vRowId, $reqLinkFile);

			echo $reqRowId."-Data berhasil disimpan.";
		}
		else
			echo "xxx-Data gagal disimpan.";
		
	}
	
	function delete()
	{
		$this->load->model('PangkatRiwayat');
		$this->load->model('KenaikanGajiBerkala');
		$this->load->model('PegawaiStatus');

		$set = new PangkatRiwayat();
		
		$reqId =  $this->input->get('reqId');
		$reqMode =  $this->input->get('reqMode');
		$reqPeriode= $this->input->get('reqPeriode');
		$reqPegawaiId= $this->input->get('reqPegawaiId');
		
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		$set->setField("PANGKAT_RIWAYAT_ID", $reqId);
		
		$reqsimpan= "";
		if($reqMode == "pangkat_riwayat_0")
		{
			$set->setField("STATUS", "1");
			if($set->updateStatus())
			{
				$reqsimpan= "1";
				$reqstatushitung= "1";
				$reqInfo= "Data berhasil dihapus.";
			}
			else
			{
				$reqInfo= "Data gagal dihapus.";
			}
		}
		elseif($reqMode == "pangkat_riwayat_1")
		{
			$set->setField("STATUS", "2");
			if($set->updateStatus())
			{
				$set_detil= new PangkatRiwayat();
				$set_detil->selectByParams(array(), -1, -1, " AND A.PANGKAT_RIWAYAT_ID = ".$reqId);
				$set_detil->firstRow();
				$reqPegawaiId= $set_detil->getField("PEGAWAI_ID");
				$reqTmtPangkat= $set_detil->getField("TMT_PANGKAT");
				$reqJenisRiwayat= $set_detil->getField("JENIS_RIWAYAT");

				// kalau pemulihan insert data di pegawai status
				if($reqJenisRiwayat == "9")
				{
					$set_detil= new PegawaiStatus();
					$set_detil->selectByParams(array(), -1,-1, " AND A.PEGAWAI_ID = ".$reqPegawaiId." AND TO_DATE('".$reqTmtPangkat."', 'YYYY-MM-DD') = A.TMT");
					$set_detil->firstRow();
					// echo $set_detil->query;exit;
					$reqPegawaiStatusId= $set_detil->getField('PEGAWAI_STATUS_ID');
					if($reqPegawaiStatusId == "")
					{
						$set_detil= new PegawaiStatus();
						$set_detil->setField("PEGAWAI_ID", $reqPegawaiId);
						$set_detil->setField("TMT", dateToDBCheck(dateToPageCheck($reqTmtPangkat)));
						$set_detil->setField("STATUS", ValToNullDB($req));
						$set_detil->setField("STATUS_PEGAWAI_ID", "2");
						$set_detil->setField("STATUS_PEGAWAI_KEDUDUKAN_ID", "9");
						$set_detil->setField("LAST_USER", $this->LOGIN_USER);
						$set_detil->setField("LAST_DATE", "NOW()");
						$set_detil->setField("USER_LOGIN_ID", $this->LOGIN_ID);
						$set_detil->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
						$set_detil->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
						$set_detil->insert();
						// echo $set_detil->query;exit();
					}
				}

				$reqsimpan= "1";
				$reqstatushitung= "1";
				$reqInfo= "Data berhasil diaktifkan.";
			}
			else
				$reqInfo= "Data gagal diaktifkan.";	
		}
		
		if($reqsimpan == "1")
		{
			if($reqstatushitung == "1")
			{
				$set = new KenaikanGajiBerkala();
				
				$set->setField('PERIODE', $reqPeriode);
				$set->setField('PEGAWAI_ID', $reqPegawaiId);
				$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
				$set->setField("LAST_USER", $this->LOGIN_USER);
				$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
				$set->setField("LAST_DATE", "NOW()");
				$set->updateStatusHitung();
				//echo $set->query;exit;
			}
			echo $reqInfo;
		}
		else
			echo $reqInfo;
	}

	function log($riwayatId) 
	{	
		$this->load->model('PangkatRiwayatLog');

		$set = new PangkatRiwayatLog();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		
		$aColumns = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "PANGKAT_RIWAYAT_ID");
		$aColumnsAlias = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "PANGKAT_RIWAYAT_ID");
		

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
		
		$arrayWhere = array("PANGKAT_RIWAYAT_ID" => $riwayatId);
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