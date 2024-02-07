<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class cek_data_rekon_email_json extends CI_Controller {

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
		$this->LOGIN_LEVEL= $this->kauth->getInstance()->getIdentity()->LOGIN_LEVEL;
		$this->SATUAN_KERJA_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID;
		$this->SATUAN_KERJA_TIPE= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_TIPE;
		$this->SATUAN_KERJA_BKD_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_BKD_ID;
		$this->LOGIN_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_ID;
		$this->LOGIN_PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID;
		$this->STATUS_SATUAN_KERJA_BKD= $this->kauth->getInstance()->getIdentity()->STATUS_SATUAN_KERJA_BKD;
	}	
	
	function json() 
	{
		$this->load->model('Pegawai');
		$this->load->model('SatuanKerja');

		$set = new Pegawai();

		$reqMode= $this->input->get("reqMode");
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$tempSatuanKerjaId= $reqSatuanKerjaId;
		$reqTipePegawaiId= $this->input->get("reqTipePegawaiId");
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);

		// $aColumns = array("PEGAWAI_ID", "SATUAN_KERJA_ID", "STATUS_PEGAWAI_ID", "STATUS_NAMA", "TIPE_PEGAWAI_ID", "TIPE_PEGAWAI_NAMA", "NIP_BARU", "TMT_CPNS", "CEK_STATUS_SK_PNS", "NAMA", "GELAR_DEPAN", "GELAR_BELAKANG", "PANGKAT_KP", "JENIS_KP", "PANGKAT_GAJI", "TMT_GAJI", "JENIS_GAJI", "CEK_PANGKAT_GAJI", "TIPE_JAB", "ESELON_JAB", "ESELON_NAMA", "NAMA_JAB", "TMT_JAB", "PENDIK_AKHIR_ID", "PENDIK_AKHIR_JUR_ID", "JURUSAN_AKHIR_NAMA", "GELAR_AKHIR_TIPE", "GELAR_AKHIR_BELAKANG", "GELAR_AKHIR_DEPAN", "CEK_JURUSAN", "NAMA_SATUAN_KERJA", "SATUAN_KERJA_INDUK", "SATUAN_KERJA_UPT", "NILAI_PPK", "CEK_PPK", "HASIL_CEK");

		$aColumns = array("NIP_BARU", "NAMA", "HP", "KETERANGAN_1", "SATUAN_KERJA_UPT", "NAMA_SATUAN_KERJA");

		$aColumnsAlias = array("NIP_BARU", "NAMA", "HP", "KETERANGAN_1", "SATUAN_KERJA_UPT", "NAMA_SATUAN_KERJA");

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
			if ( trim($sOrder) == "ORDER BY SATUAN_KERJA_UPT ASC, NAMA_SATUAN_KERJA ASC" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = "ORDER BY SATUAN_KERJA_UPT ASC, NAMA_SATUAN_KERJA ASC";
				 
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
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}

		$statementAktif= "";
		if($reqSatuanKerjaId == "")
		{
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
			{
				$statementAktif= " AND EXISTS(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE)
				AND P.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
				)";
			}
		}
		else
		{
			$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE)
			AND P.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			)";
			
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
			{
				$reqSatuanKerjaId= "";
				if($tempSatuanKerjaId == ""){}
				else
				{
					$reqSatuanKerjaId= $tempSatuanKerjaId;
					$skerja= new SatuanKerja();
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
					unset($skerja);
					//echo $reqSatuanKerjaId;exit;
					$statement= " AND P.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				}
			}
			else
			{
				// echo $reqSatuanKerjaId;exit();
				// echo $this->SATUAN_KERJA_TIPE;exit();
				$skerja= new SatuanKerja();
				// if($this->SATUAN_KERJA_TIPE == "1")
				// {
				// 	$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
				// }
				// else
				// {
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				// }
				// echo $skerja->query;exit();
				unset($skerja);
				// echo $reqSatuanKerjaId;exit;
				$statement= " AND P.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				//$statement= " AND ( A.SATUAN_KERJA_ID = ANY( AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$reqSatuanKerjaId.") ) OR A.SATUAN_KERJA_ID = ".$reqSatuanKerjaId." )";
			}
		}


		$searchJson= " AND (UPPER(P.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(P.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%' ) ";
		$allRecord = $set->getCountByParamsRekapCekDataRekon(array(), $statement);
		// echo $allRecord;

		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParamsRekapCekDataRekon(array(), $statement.$searchJson);
		//echo $set->query;exit;
		$sOrder= "";
		$set->selectByParamsRekapCekDataRekonEmail(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
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
			$row = array();
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				if($aColumns[$i] == "TTL")
					$row[] = $set->getField("TEMPAT_LAHIR").", ".getFormattedDate($set->getField("TANGGAL_LAHIR"));			
				elseif($aColumns[$i] == "MASA_KERJA")
					$row[] = $set->getField("MASA_KERJA_TAHUN")." - ".$set->getField("MASA_KERJA_BULAN");			
				elseif($aColumns[$i] == "TMT_PANGKAT" || $aColumns[$i] == "TMT_JABATAN" || $aColumns[$i] == "TMT_ESELON")
					$row[] = dateToPageCheck($set->getField(trim($aColumns[$i])));	
				else
					$row[] = $set->getField(trim($aColumns[$i]));
			}
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}

}
?>