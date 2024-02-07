<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class kenaikan_pangkat_hitung_json extends CI_Controller {

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
		$this->SATUAN_KERJA_TIPE= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_TIPE;
		$this->SATUAN_KERJA_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID;
		$this->SATUAN_KERJA_BKD_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_BKD_ID;
		$this->LOGIN_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_ID;
		$this->LOGIN_PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID;
	}	

	function json() 
	{
		$this->load->model('KenaikanPangkatHitung');
		$this->load->model('SatuanKerja');

		$set = new KenaikanPangkatHitung();
		
		$reqMode= $this->input->get("reqMode");
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqKpStatus= $this->input->get("reqKpStatus");
		$reqKpJenisRiwayat= $this->input->get("reqKpJenisRiwayat");
		$reqBulan= $this->input->get("reqBulan");
		$reqTahun= $this->input->get("reqTahun");
		$reqPangkatId= $this->input->get("reqPangkatId");
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		$aColumns = array("NIP_BARU", "NAMA_LENGKAP", "JABATAN_RIWAYAT", "PANGKAT_RIWAYAT", "KP_PANGKAT", "KP_STATUS");
		$aColumnsAlias = array("NIP_BARU", "NAMA_LENGKAP", "JABATAN_RIWAYAT", "PANGKAT_RIWAYAT", "KP_PANGKAT", "KP_STATUS");

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
			if ( trim($sOrder) == "ORDER BY PEGAWAI_INFO desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.NAMA ASC ";
				 
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
		
		
		if($reqMode == "proses")
		{
			// if($reqBulan == "" && $reqTahun == ""){}
			// else
			// {
				$set_detil= new KenaikanPangkatHitung();
				$set_detil->setField("PERIODE", $reqBulan.$reqTahun);
				$set_detil->setField("SATKERID", ValToNullDB($reqSatuanKerjaId));
				$set_detil->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
				$set_detil->setField("LAST_USER", $this->LOGIN_USER);
				$set_detil->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				$set_detil->proseshitung();
			// }
			//echo $set_detil->query;exit;
		}

		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}

		if($reqSatuanKerjaId == ""){}
		else
		{
			$skerja= new SatuanKerja();
			// kalau 1 maka dinas
			if($this->SATUAN_KERJA_TIPE == "1")
			{
				$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
			}
			else
			{
				$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
			}
			unset($skerja);
			// echo $reqSatuanKerjaId;exit;
			$statement.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			// $statement= " AND A.SATUAN_KERJA_ID = ".$reqSatuanKerjaId;
		}
		// reqKpJenisRiwayat
		if($reqKpStatus ==""){}
		else
		{
			$statement .= " AND KP.KP_STATUS = ".$reqKpStatus;
		}
		
		if($reqKpJenisRiwayat ==""){}
		else
		{
			$statement .= " AND KP.KP_JENIS_RIWAYAT = ".$reqKpJenisRiwayat;
		}

		// if($reqPangkatId ==''){}
		// else
		// {
		// 	$statement .= " AND A.PANGKAT_ID = '".$reqPangkatId."'";
		// }

		if($reqBulan == "" && $reqTahun == ""){}
		elseif($reqBulan == "")
		{
			if($reqTahun == ""){}
			else
			{
				$statement .= " AND TO_CHAR(KP.KP_TMT, 'YYYY') = '".$reqTahun."'";
			}
		}
		else
		{
			$statement .= " AND TO_CHAR(KP.KP_TMT, 'MMYYYY') = '".$reqBulan.$reqTahun."'";
		}

		// $statement .= " AND A.PERIODE = '".$reqBulan.$reqTahun."'";
		$searchJson= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%' ) ";
		$allRecord = $set->getCountByParamsMonitoring(array(), $statement);
		// echo $allRecord;

		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParamsMonitoring(array(), $statement.$searchJson);
		//echo $set->query;exit;
		//$sOrder= "ORDER BY DUK";
		$set->selectByParamsMonitoring(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
		// echo $set->query;exit;
		/* Output */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);
		 
		while($set->nextRow())
		{
			$reqSuratKeluarBkdId= $set->getField("SURAT_KELUAR_BKD_ID");
			$reqKpStatus= $set->getField("STATUS_KGB");
			
			$row = array();
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				if($aColumns[$i] == "JABATAN_RIWAYAT")
					$row[] = $set->getField("JABATAN_RIWAYAT_NAMA")."<br/>".dateTimeToPageCheck($set->getField("JABATAN_RIWAYAT_TMT"));
				elseif($aColumns[$i] == "PANGKAT_RIWAYAT")
					$row[] = $set->getField("PANGKAT_RIWAYAT_KODE")."<br/>".getFormattedDate($set->getField("PANGKAT_RIWAYAT_TMT"));
				elseif($aColumns[$i] == "KP_PANGKAT")
					$row[] = $set->getField("KP_PANGKAT_KODE")."<br/>".getFormattedDate($set->getField("KP_TMT_PANGKAT"));
				elseif($aColumns[$i] == "KP_STATUS")
					$row[] = $set->getField("KP_STATUS_NAMA")."<br/>".$set->getField("KP_JENIS_RIWAYAT_NAMA");
				else
					$row[] = $set->getField(trim($aColumns[$i]));
			}
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}

	function getinfototal() 
	{
		$this->load->model('KenaikanPangkatHitung');
		$this->load->model('SatuanKerja');

		$set = new KenaikanPangkatHitung();
		
		$reqMode= $this->input->get("reqMode");
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqKpStatus= $this->input->get("reqKpStatus");
		$reqKpJenisRiwayat= $this->input->get("reqKpJenisRiwayat");
		$reqBulan= $this->input->get("reqBulan");
		$reqTahun= $this->input->get("reqTahun");
		$reqPangkatId= $this->input->get("reqPangkatId");
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}

		if($reqSatuanKerjaId == ""){}
		else
		{
			$skerja= new SatuanKerja();
			// kalau 1 maka dinas
			if($this->SATUAN_KERJA_TIPE == "1")
			{
				$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
			}
			else
			{
				$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
			}
			unset($skerja);
			// echo $reqSatuanKerjaId;exit;
			$statement.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			// $statement= " AND A.SATUAN_KERJA_ID = ".$reqSatuanKerjaId;
		}

		if($reqBulan == "" && $reqTahun == ""){}
		elseif($reqBulan == "")
		{
			if($reqTahun == ""){}
			else
			{
				$statement .= " AND TO_CHAR(KP.KP_TMT, 'YYYY') = '".$reqTahun."'";
			}
		}
		else
		{
			$statement .= " AND TO_CHAR(KP.KP_TMT, 'MMYYYY') = '".$reqBulan.$reqTahun."'";
		}

		$set= new KenaikanPangkatHitung();
		$set->selectByParamsInfoTotal($statement);
		$set->firstRow();
		// echo $set->query;exit();
		$jumlah_data_kgb= $set->getField("JUMLAH_DATA");
		$jumlah_data_kgb_proses= $set->getField("JUMLAH_DATA_UD");
		$jumlah_data_kgb_selesai= $set->getField("JUMLAH_DATA_PI");
		$jumlah_data_kgb_hukuman= $set->getField("JUMLAH_DATA_TIDAK");
		
		$arrData= array("jumlah_data_kgb"=>$jumlah_data_kgb, "jumlah_data_kgb_proses"=>$jumlah_data_kgb_proses, "jumlah_data_kgb_selesai"=>$jumlah_data_kgb_selesai, "jumlah_data_kgb_hukuman"=>$jumlah_data_kgb_hukuman);
		echo json_encode($arrData);
	}
	
}
?>