<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/encrypt.func.php");

class pegawai_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		// echo "asd";exit();
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// trow to unauthenticated page!
			redirect('app');
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
		$this->load->model('verifikasi/Pegawai');
		$this->load->model('SatuanKerja');

		$set = new Pegawai();
		
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqSatuanKerjaTeknisId= $tempSatuanKerjaId= $reqSatuanKerjaId;

		$reqStatusPegawaiId= $this->input->get("reqStatusPegawaiId");
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);

		$aColumns = array("NIP_BARU_LAMA", "RIWAYAT_JENIS", "INFO_KETERANGAN_TANGGAL", "INFO_JENIS_UPDATE", "INFO_STATUS", "SATUAN_KERJA_NAMA", "INFO_LEVEL_NAMA", "SATUAN_KERJA_INDUK", "ROW_HAPUS_ID", "TEMP_VALIDASI_ID", "INFO_LINK", "PEGAWAI_ID");
		$aColumnsAlias = $aColumns;

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
			if ( trim($sOrder) == "ORDER BY NIP_BARU_LAMA desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				// $sOrder = " ORDER BY JAB_RIW.ESELON_ID ASC, PANG_RIW.PANGKAT_ID DESC, PANG_RIW.TMT_PANGKAT ASC";
				$sOrder = " ORDER BY A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.TMT_PANGKAT ASC";
				 
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
			$tempSatuanKerjaId= $reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		// echo $reqSatuanKerjaId;exit();
		

		// "Dinas Pendidikan"
		// $reqSatuanKerjaId=83;

		// "Badan Kepegawaian Daerah, Pendidikan dan Pelatihan"
		// $reqSatuanKerjaId=66;

		// "DINAS PENDIDIKAN"
		// $reqSatuanKerjaId=16;
		// $statement.= " AND A.PEGAWAI_ID = 6317";
		$statementAktif= "";
		if($reqSatuanKerjaId == "")
		{
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
			{
				$statementAktif= " AND EXISTS(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE)
				AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
				)";
			}
		}
		else
		{
			$statementAktif= " AND EXISTS(
			SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE)
			AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			)";
			
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
			{
				if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" && $reqSatuanKerjaTeknisId == ""){}
				else
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
						$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
					}
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
				$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				//$statement= " AND ( A.SATUAN_KERJA_ID = ANY( AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$reqSatuanKerjaId.") ) OR A.SATUAN_KERJA_ID = ".$reqSatuanKerjaId." )";
			}
		}
		// echo $statement;exit();

		if($reqStatusPegawaiId == ""){}
		else if($reqStatusPegawaiId == "12")
		{
			$statement.= " AND A.STATUS_PEGAWAI_ID IN (1,2)";
		}
		else if($reqStatusPegawaiId == "hk")
		{
			$statement.= " AND EXISTS(SELECT 1 FROM HUKUMAN_TERAKHIR WHERE CURRENT_DATE <= G.TANGGAL_AKHIR AND CURRENT_DATE >= G.TANGGAL_MULAI)";
		}
		else if($reqStatusPegawaiId == "pk")
		{
			$statement.= " AND EXISTS(SELECT 1 FROM HUKUMAN_TERAKHIR WHERE CURRENT_DATE >= G.TANGGAL_AKHIR)";
		}
		else if(isStrContain($reqStatusPegawaiId, "spk"))
		{
			$reqStatusPegawaiId= str_replace("spk", "", $reqStatusPegawaiId);
			$statement.= " AND A.PEGAWAI_ID IN (SELECT B.PEGAWAI_ID FROM STATUS_PEGAWAI_KEDUDUKAN A INNER JOIN PEGAWAI B ON A.STATUS_PEGAWAI_ID = B.STATUS_PEGAWAI_ID WHERE A.STATUS_PEGAWAI_KEDUDUKAN_ID = ".$reqStatusPegawaiId.")";
		}
		else
		{
			$statement.= " AND A.STATUS_PEGAWAI_ID = ".$reqStatusPegawaiId;
		}

		$statementdetil= " AND INFO_VALIDASI IS NULL";
		
		// $_GET['sSearch']= "196105041985091001";
		// echo $statement;exit;
		$searchJson= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NIP_LAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%') ";
		$allRecord = $set->getCountByParamsMonitoring(array(), $statement, $statementdetil);
		// echo $allRecord;

		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParamsMonitoring(array(), $statement.$searchJson, $statementdetil);
		//echo $set->query;exit;
		$set->selectByParamsMonitoringPegawai(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $statementdetil, $sOrder);
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
				if($aColumns[$i] == "TANGGAL")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "PEGAWAI_INFO")
				{
					$tempPath= $set->getField("PATH");
					if($tempPath == "")
					$tempPath= "images/foto-profile.jpg";

					$row[] = '<img src="'.$tempPath.'" style="width:60px;height:90px" />';
				}
				else if($aColumns[$i] == "NIP_BARU_LAMA")
					$row[] = $set->getField("NAMA_LENGKAP")."<br/>".$set->getField("NIP_BARU");
				else if($aColumns[$i] == "RIWAYAT_JENIS")
					$row[] = $set->getField("INFO_RIWAYAT")."<br/>".$set->getField("INFO_JENIS");
				else if($aColumns[$i] == "INFO_KETERANGAN_TANGGAL")
					$row[] = $set->getField("INFO_KETERANGAN")."<br/>".$set->getField("INFO_TANGGAL");
				else if($aColumns[$i] == "GOL_TMT")
					$row[] = $set->getField("PANGKAT_RIWAYAT_KODE")."<br/>".dateToPageCheck($set->getField("PANGKAT_RIWAYAT_TMT"));
				else if($aColumns[$i] == "JABATAN_TMT_ESELON")
					$row[] = $set->getField("JABATAN_RIWAYAT_NAMA")."<br/>".dateTimeToPageCheck($set->getField("JABATAN_RIWAYAT_TMT"))."<br/>".$set->getField("JABATAN_RIWAYAT_ESELON");
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}
		
}
?>