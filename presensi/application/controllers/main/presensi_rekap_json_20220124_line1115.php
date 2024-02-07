<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/image.func.php");
include_once("functions/ambilinfo.func.php");

class presensi_rekap_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// trow to unauthenticated page!
			redirect('login');
		}		
		
		// $this->db->query("SET DATESTYLE TO PostgreSQL,European;");  
		$this->USER_GROUP= $this->kauth->getInstance()->getIdentity()->USER_GROUP;
		$this->LOGIN_USER= $this->kauth->getInstance()->getIdentity()->LOGIN_USER;
		$this->LOGIN_LEVEL= $this->kauth->getInstance()->getIdentity()->LOGIN_LEVEL;
		$this->SATUAN_KERJA_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID;
		$this->SATUAN_KERJA_TIPE= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_TIPE;
		$this->SATUAN_KERJA_BKD_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_BKD_ID;
		$this->LOGIN_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_ID;
		$this->LOGIN_PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID;
		$this->STATUS_SATUAN_KERJA_BKD= $this->kauth->getInstance()->getIdentity()->STATUS_SATUAN_KERJA_BKD;
		$this->STATUS_KHUSUS_DINAS= $this->kauth->getInstance()->getIdentity()->STATUS_KHUSUS_DINAS;
	}	

	function rekapawalunitkerja()
	{
		$this->load->model('main/PresensiRekap');
		$this->load->model('main/SatuanKerja');

		$set= new PresensiRekap;

		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqBulan= $this->input->get("reqBulan");
		$reqTahun= $this->input->get("reqTahun");
		$reqPeriode= $reqBulan.$reqTahun;
		$reqPencarian= $this->input->get("reqPencarian");
		$reqKhususDinas= $this->input->get("reqKhususDinas");

		$aColumns = array("NIP_BARU", "NIP_BARU_NAMA_LENGKAP", "SATUAN_KERJA_INFO", "HARIINFO1", "HARIINFO2", "HARIINFO3","HARIINFO4","HARIINFO5","HARIINFO6","HARIINFO7","HARIINFO8","HARIINFO9","HARIINFO10","HARIINFO11","HARIINFO12","HARIINFO13","HARIINFO14","HARIINFO15","HARIINFO16","HARIINFO17","HARIINFO18","HARIINFO19","HARIINFO20","HARIINFO21","HARIINFO22","HARIINFO23","HARIINFO24","HARIINFO25","HARIINFO26","HARIINFO27","HARIINFO28","HARIINFO29","HARIINFO30","HARIINFO31","PEGAWAI_ID");
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
					if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc") == 0)
					{
						$sOrder .=" asc, ";
					}else
					{
						$sOrder .=" desc, ";
					}
				}
			}
			
			//Remove the last space / comma
			$sOrder = substr_replace( $sOrder, "", -2 );
			
			//Check if there is an order by clause
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY NIP_BARU asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY P.ESELON_ID ASC, P.PANGKAT_ID DESC, P.PANGKAT_RIWAYAT_TMT ASC";
				 
			}
		}

		if(empty($sOrder))
		{
			$sOrder = " ORDER BY P.ESELON_ID ASC, P.PANGKAT_ID DESC, P.PANGKAT_RIWAYAT_TMT ASC";
		}
		// echo $sOrder;exit;

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

		$setdetil= new PresensiRekap;
		$setdetil->setField("PERIODE", $reqPeriode);
		$setdetil->setperiodepartisi();
		unset($setdetil);

		if($reqSatuanKerjaId == "")
		{
			$tempSatuanKerjaId= $reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}

		$satuankerjakondisi= "";
		if($reqSatuanKerjaId == "")
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND P.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
		}
		else
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND P.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";

			$infostatuskhususdinas= $this->STATUS_KHUSUS_DINAS;
			$skerja= new SatuanKerja();
			if($reqKhususDinas == "1" && $infostatuskhususdinas == "1")
			{
				$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
			}
			else
			{
				$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
			}
			// echo $skerja->query;exit();
			unset($skerja);
			// echo $reqSatuanKerjaId;exit;
			$satuankerjakondisi.= " AND P.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}
		// echo $satuankerjakondisi;exit();
		$statementdetil= $satuankerjakondisi;

		// $statementdetil.= " AND P.PEGAWAI_ID = 8300";
		/*$statementdetil.= " AND 
		(
			P.STATUS_PEGAWAI_ID IN (1,2)
			OR
			(
				P.STATUS_PEGAWAI_ID IN (3,4,5)
				AND 
				EXISTS
				(
					SELECT 1
					FROM
					(
						SELECT PEGAWAI_STATUS_ID
						FROM pegawai_status
						WHERE TMT >= TO_DATE('01".$reqPeriode."', 'DDMMYYYY')
					) XXX WHERE P.PEGAWAI_STATUS_ID = XXX.PEGAWAI_STATUS_ID
				)
			)
		)";*/
		// echo $statement;exit;

		$infotanggaldetil= "01".$reqPeriode;
		$searchJson = " AND ( UPPER(P.NIP_BARU) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(P.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%' )";
		$allRecord = $set->getCountByParamsNewRekapAwalUnitKerja(array(), $infotanggaldetil, $reqPeriode, $statementdetil);
		//echo $allRecord;
		if($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsNewRekapAwalUnitKerja(array(), $infotanggaldetil, $reqPeriode, $searchJson.$statementdetil);

		$set->selectByParamsNewRekapAwalUnitKerja(array(), $dsplyRange, $dsplyStart, $infotanggaldetil, $reqPeriode, $statementdetil, $searchJson.$statement, $sOrder);
		// echo $set->query;exit;
		// echo $set->errorMsg;exit;

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
				if($aColumns[$i] == "NIP_BARU_NAMA_LENGKAP")
				{
					$row[] = $set->getField("NAMA_LENGKAP")."<br/>".$set->getField("NIP_BARU");
				}
				elseif($aColumns[$i] == "HARIINFO1" || $aColumns[$i] == "HARIINFO2" || $aColumns[$i] == "HARIINFO3" || $aColumns[$i] == "HARIINFO4" || $aColumns[$i] == "HARIINFO5" || $aColumns[$i] == "HARIINFO6" || $aColumns[$i] == "HARIINFO7" || $aColumns[$i] == "HARIINFO8" || $aColumns[$i] == "HARIINFO9" || $aColumns[$i] == "HARIINFO10" || $aColumns[$i] == "HARIINFO11" || $aColumns[$i] == "HARIINFO12" || $aColumns[$i] == "HARIINFO13" || $aColumns[$i] == "HARIINFO14" || $aColumns[$i] == "HARIINFO15" || $aColumns[$i] == "HARIINFO16" || $aColumns[$i] == "HARIINFO17" || $aColumns[$i] == "HARIINFO18" || $aColumns[$i] == "HARIINFO19" || $aColumns[$i] == "HARIINFO20" || $aColumns[$i] == "HARIINFO21" || $aColumns[$i] == "HARIINFO22" || $aColumns[$i] == "HARIINFO23" || $aColumns[$i] == "HARIINFO24" || $aColumns[$i] == "HARIINFO25" || $aColumns[$i] == "HARIINFO26" || $aColumns[$i] == "HARIINFO27" || $aColumns[$i] == "HARIINFO28" || $aColumns[$i] == "HARIINFO29" || $aColumns[$i] == "HARIINFO30" || $aColumns[$i] == "HARIINFO31")
				{
					$infohari= str_replace("HARIINFO", "", $aColumns[$i]);

					$in1= $set->getField("N_JAM_MASUK_".$infohari);
					$in2= $set->getField("N_MASUK_".$infohari);
					$ininfo2= "";
					if(!empty($in2))
					{
						$infosql= " SELECT A.nama FROM presensi.IJIN_KOREKSI A WHERE upper(kode) = upper('".$in2."')";
						$hasilsql = $this->db->query($infosql)->row();
						$ininfo2= $hasilsql->nama;
					}

					$out1= $set->getField("N_JAM_PULANG_".$infohari);
					$out2= $set->getField("N_PULANG_".$infohari);
					$outinfo2= "";
					if(!empty($in2))
					{
						$infosql= " SELECT A.nama FROM presensi.IJIN_KOREKSI A WHERE upper(kode) = upper('".$out2."')";
						$hasilsql = $this->db->query($infosql)->row();
						$outinfo2= $hasilsql->nama;
					}

					$ask1= $set->getField("EX_JAM_MASUK_".$infohari);
					$ask2= $set->getField("EX_MASUK_".$infohari);
					$askinfo2= "";
					if(!empty($in2))
					{
						$infosql= " SELECT A.nama FROM presensi.IJIN_KOREKSI A WHERE upper(kode) = upper('".$ask2."')";
						$hasilsql = $this->db->query($infosql)->row();
						$askinfo2= $hasilsql->nama;
					}

					$row[] = ambilpresensiinfo($in1, $in2, $ininfo2, $out1, $out2, $outinfo2, $ask1, $ask2, $askinfo2);
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}

	function rekapawalpegawai()
	{
		$this->load->model('main/PresensiRekap');
		$this->load->model('main/SatuanKerja');

		$set= new PresensiRekap;

		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqStatus= $this->input->get("reqStatus");
		$reqPencarian= $this->input->get("reqPencarian");
		$reqKhususDinas= $this->input->get("reqKhususDinas");

		$aColumns = array("NIP_BARU", "NAMA_LENGKAP", "JABATAN_RIWAYAT_NAMA", "SATUAN_KERJA_INFO", "PEGAWAI_ID");
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
					if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc") == 0)
					{
						$sOrder .=" asc, ";
					}else
					{
						$sOrder .=" desc, ";
					}
				}
			}
			
			//Remove the last space / comma
			$sOrder = substr_replace( $sOrder, "", -2 );
			
			//Check if there is an order by clause
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY NIP_BARU asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY P.ESELON_ID ASC, P.PANGKAT_ID DESC, P.PANGKAT_RIWAYAT_TMT ASC";
				 
			}
		}

		if(empty($sOrder))
		{
			$sOrder = " ORDER BY P.ESELON_ID ASC, P.PANGKAT_ID DESC, P.PANGKAT_RIWAYAT_TMT ASC";
		}
		// echo $sOrder;exit;

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

		$satuankerjakondisi= "";
		if($reqSatuanKerjaId == "")
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND P.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
		}
		else
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND P.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
			
			$infostatuskhususdinas= $this->STATUS_KHUSUS_DINAS;
			$skerja= new SatuanKerja();
			if($reqKhususDinas == "1" && $infostatuskhususdinas == "1")
			{
				$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
			}
			else
			{
				$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
			}
			// echo $skerja->query;exit();
			unset($skerja);
			// echo $reqSatuanKerjaId;exit;
			$satuankerjakondisi.= " AND P.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}
		// echo $satuankerjakondisi;exit();
		$statement= $satuankerjakondisi;

		if(empty($reqStatus))
		{
			$statement.= " AND P.STATUS_PEGAWAI_ID IN (1,2)";
		}
		else
		{
			if($reqStatus == "xxx"){}
			else
			{
				$statement.= " AND P.STATUS_PEGAWAI_ID NOT IN (1,2)";
			}
		}
		// echo $statement;exit;

		$searchJson = " AND ( UPPER(P.NIP_BARU) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(P.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%' )";
		$allRecord = $set->getCountByParamsRekapAwalPegawai(array(), $statement);
		//echo $allRecord;
		if($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsRekapAwalPegawai(array(), $searchJson.$statement);
		
		$set->selectByParamsRekapAwalPegawai(array(), $dsplyRange, $dsplyStart, $searchJson.$statement, $sOrder);
		// echo $set->query;exit;
		// echo $set->errorMsg;exit;

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
				if($aColumns[$i] == "NIP_BARU_NAMA_LENGKAP")
				{
					$row[] = $set->getField("NAMA_LENGKAP")."<br/>".$set->getField("NIP_BARU");
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}

	function hasilklarifikasi()
	{
		$this->load->model('main/PresensiRekap');
		$this->load->model('main/SatuanKerja');

		$set= new PresensiRekap;

		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqBulan= $this->input->get("reqBulan");
		$reqTahun= $this->input->get("reqTahun");
		$reqPeriode= $reqBulan.$reqTahun;
		$reqPencarian= $this->input->get("reqPencarian");
		$reqKhususDinas= $this->input->get("reqKhususDinas");

		$aColumns = array("NIP_BARU", "NIP_BARU_NAMA_LENGKAP", "SATUAN_KERJA_INFO", "HARIINFO1", "HARIINFO2", "HARIINFO3","HARIINFO4","HARIINFO5","HARIINFO6","HARIINFO7","HARIINFO8","HARIINFO9","HARIINFO10","HARIINFO11","HARIINFO12","HARIINFO13","HARIINFO14","HARIINFO15","HARIINFO16","HARIINFO17","HARIINFO18","HARIINFO19","HARIINFO20","HARIINFO21","HARIINFO22","HARIINFO23","HARIINFO24","HARIINFO25","HARIINFO26","HARIINFO27","HARIINFO28","HARIINFO29","HARIINFO30","HARIINFO31","PEGAWAI_ID");
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
					if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc") == 0)
					{
						$sOrder .=" asc, ";
					}else
					{
						$sOrder .=" desc, ";
					}
				}
			}
			
			//Remove the last space / comma
			$sOrder = substr_replace( $sOrder, "", -2 );
			
			//Check if there is an order by clause
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY NIP_BARU asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY P.ESELON_ID ASC, P.PANGKAT_ID DESC, P.PANGKAT_RIWAYAT_TMT ASC";
				 
			}
		}

		if(empty($sOrder))
		{
			$sOrder = " ORDER BY P.ESELON_ID ASC, P.PANGKAT_ID DESC, P.PANGKAT_RIWAYAT_TMT ASC";
		}
		// echo $sOrder;exit;

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

		$setdetil= new PresensiRekap;
		$setdetil->setField("PERIODE", $reqPeriode);
		$setdetil->setperiodepartisi();
		unset($setdetil);

		if($reqSatuanKerjaId == "")
		{
			$tempSatuanKerjaId= $reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}

		$satuankerjakondisi= "";
		if($reqSatuanKerjaId == "")
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND P.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
		}
		else
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND P.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
			
			$infostatuskhususdinas= $this->STATUS_KHUSUS_DINAS;
			$skerja= new SatuanKerja();
			if($reqKhususDinas == "1" && $infostatuskhususdinas == "1")
			{
				$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
			}
			else
			{
				$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
			}
			// echo $skerja->query;exit();
			unset($skerja);
			// echo $reqSatuanKerjaId;exit;
			$satuankerjakondisi.= " AND P.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}
		// echo $satuankerjakondisi;exit();
		$statementdetil= $satuankerjakondisi;

		// $statementdetil.= " AND P.PEGAWAI_ID = 8300";
		// $statementdetil.= " AND P.PEGAWAI_ID = 14160";
		/*$statementdetil.= " AND 
		(
			P.STATUS_PEGAWAI_ID IN (1,2)
			OR
			(
				P.STATUS_PEGAWAI_ID IN (3,4,5)
				AND 
				EXISTS
				(
					SELECT 1
					FROM
					(
						SELECT PEGAWAI_STATUS_ID
						FROM pegawai_status
						WHERE TMT >= TO_DATE('01".$reqPeriode."', 'DDMMYYYY')
					) XXX WHERE P.PEGAWAI_STATUS_ID = XXX.PEGAWAI_STATUS_ID
				)
			)
		)";*/
		// echo $statement;exit;

		$infotanggaldetil= "01".$reqPeriode;
		$searchJson = " AND ( UPPER(P.NIP_BARU) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(P.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%' )";
		$allRecord = $set->getCountByParamsNewHasilKlarifikasi(array(), $infotanggaldetil, $reqPeriode, $statementdetil);
		//echo $allRecord;
		if($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsNewHasilKlarifikasi(array(), $infotanggaldetil, $reqPeriode, $searchJson.$statementdetil);
		
		$set->selectByParamsNewHasilKlarifikasi(array(), $dsplyRange, $dsplyStart, $infotanggaldetil, $reqPeriode, $statementdetil, $searchJson.$statement, $sOrder);
		// echo $set->query;exit;
		// echo $set->errorMsg;exit;

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
				if($aColumns[$i] == "NIP_BARU_NAMA_LENGKAP")
				{
					$row[] = $set->getField("NAMA_LENGKAP")."<br/>".$set->getField("NIP_BARU");
				}
				elseif($aColumns[$i] == "HARIINFO1" || $aColumns[$i] == "HARIINFO2" || $aColumns[$i] == "HARIINFO3" || $aColumns[$i] == "HARIINFO4" || $aColumns[$i] == "HARIINFO5" || $aColumns[$i] == "HARIINFO6" || $aColumns[$i] == "HARIINFO7" || $aColumns[$i] == "HARIINFO8" || $aColumns[$i] == "HARIINFO9" || $aColumns[$i] == "HARIINFO10" || $aColumns[$i] == "HARIINFO11" || $aColumns[$i] == "HARIINFO12" || $aColumns[$i] == "HARIINFO13" || $aColumns[$i] == "HARIINFO14" || $aColumns[$i] == "HARIINFO15" || $aColumns[$i] == "HARIINFO16" || $aColumns[$i] == "HARIINFO17" || $aColumns[$i] == "HARIINFO18" || $aColumns[$i] == "HARIINFO19" || $aColumns[$i] == "HARIINFO20" || $aColumns[$i] == "HARIINFO21" || $aColumns[$i] == "HARIINFO22" || $aColumns[$i] == "HARIINFO23" || $aColumns[$i] == "HARIINFO24" || $aColumns[$i] == "HARIINFO25" || $aColumns[$i] == "HARIINFO26" || $aColumns[$i] == "HARIINFO27" || $aColumns[$i] == "HARIINFO28" || $aColumns[$i] == "HARIINFO29" || $aColumns[$i] == "HARIINFO30" || $aColumns[$i] == "HARIINFO31")
				{
					$infohari= str_replace("HARIINFO", "", $aColumns[$i]);

					$in1= $set->getField("JAM_MASUK_".$infohari);
					$in2= $set->getField("MASUK_".$infohari);
					$ininfo2= "";
					if(!empty($in2))
					{
						$infosql= " SELECT A.nama FROM presensi.IJIN_KOREKSI A WHERE upper(kode) = upper('".$in2."')";
						$hasilsql = $this->db->query($infosql)->row();
						$ininfo2= $hasilsql->nama;
					}

					$out1= $set->getField("JAM_PULANG_".$infohari);
					$out2= $set->getField("PULANG_".$infohari);
					$outinfo2= "";
					if(!empty($in2))
					{
						$infosql= " SELECT A.nama FROM presensi.IJIN_KOREKSI A WHERE upper(kode) = upper('".$out2."')";
						$hasilsql = $this->db->query($infosql)->row();
						$outinfo2= $hasilsql->nama;
					}

					$ask1= $set->getField("EX_JAM_MASUK_".$infohari);
					$ask2= $set->getField("EX_MASUK_".$infohari);
					$askinfo2= "";
					if(!empty($in2))
					{
						$infosql= " SELECT A.nama FROM presensi.IJIN_KOREKSI A WHERE upper(kode) = upper('".$ask2."')";
						$hasilsql = $this->db->query($infosql)->row();
						$askinfo2= $hasilsql->nama;
					}

					$row[] = ambilpresensiinfo($in1, $in2, $ininfo2, $out1, $out2, $outinfo2, $ask1, $ask2, $askinfo2);
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}

	function absensidetil()
	{
		$this->load->model('main/PresensiRekap');
		$this->load->model('main/SatuanKerja');

		$set= new PresensiRekap;

		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqStatus= $this->input->get("reqStatus");
		$reqBulan= $this->input->get("reqBulan");
		$reqTahun= $this->input->get("reqTahun");
		$reqPeriode= $reqBulan.$reqTahun;
		$reqPencarian= $this->input->get("reqPencarian");
		$reqKhususDinas= $this->input->get("reqKhususDinas");

		$aColumns = array("PEGAWAI_ID", "NAMA_LENGKAP", "NIP_BARU", "TANGGAL", "WAKTU", "TIPE_PRESENSI", "TIPE_LOG", "MESIN_PRESENSI", "TANGGAL_DATA_MASUK");
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
					if (strcasecmp(( $_GET['sSortDir_'.$i] ), "asc") == 0)
					{
						$sOrder .=" asc, ";
					}else
					{
						$sOrder .=" desc, ";
					}
				}
			}
			
			//Remove the last space / comma
			$sOrder = substr_replace( $sOrder, "", -2 );
			
			//Check if there is an order by clause
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY NIP_BARU asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY P.ESELON_ID ASC, P.PANGKAT_ID DESC, P.PANGKAT_RIWAYAT_TMT ASC";
				 
			}
		}

		if(empty($sOrder))
		{
			$sOrder = " ORDER BY PA.JAM DESC";
		}
		// echo $sOrder;exit;

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

		$satuankerjakondisi= "";
		if($reqSatuanKerjaId == "")
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND P.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
		}
		else
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND P.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
			
			$infostatuskhususdinas= $this->STATUS_KHUSUS_DINAS;
			$skerja= new SatuanKerja();
			if($reqKhususDinas == "1" && $infostatuskhususdinas == "1")
			{
				$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
			}
			else
			{
				$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
			}
			// echo $skerja->query;exit();
			unset($skerja);
			// echo $reqSatuanKerjaId;exit;
			$satuankerjakondisi.= " AND P.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}
		// echo $satuankerjakondisi;exit();
		$statement= $satuankerjakondisi;

		// $statement.= " AND P.PEGAWAI_ID = 8300";

		if(empty($reqStatus))
		{
			$statement.= " AND P.STATUS_PEGAWAI_ID IN (1,2,6)";
		}
		else
		{
			if($reqStatus == "xxx"){}
			else
			{
				$statement.= " AND 
				(
					P.STATUS_PEGAWAI_ID IN (3,4,5)
					AND 
					EXISTS
					(
						SELECT 1
						FROM
						(
							SELECT PEGAWAI_STATUS_ID
							FROM pegawai_status
							WHERE TMT >= TO_DATE('01".$reqPeriode."', 'DDMMYYYY')
						) XXX WHERE P.PEGAWAI_STATUS_ID = XXX.PEGAWAI_STATUS_ID
					)
				)
				";
			}
		}
		// echo $statement;exit;

		$searchJson = " AND ( UPPER(P.NIP_BARU) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(P.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%' )";
		$allRecord = $set->getCountByParamsPresensiAbsen(array(), $reqPeriode, $statement);
		//echo $allRecord;
		if($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsPresensiAbsen(array(), $reqPeriode, $searchJson.$statement);
		
		$set->selectByParamsPresensiAbsen(array(), $dsplyRange, $dsplyStart, $reqPeriode, $searchJson.$statement, $sOrder);
		// echo $set->query;exit;
		// echo $set->errorMsg;exit;

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
				{
					$row[] = dateToPageCheck($set->getField($aColumns[$i]));
				}
				else if($aColumns[$i] == "TANGGAL_DATA_MASUK")
				{
					$row[] = dateTimeToPageCheck($set->getField($aColumns[$i]));
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}

	function setabsenharilibur()
	{
		ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        
		$this->load->model('main/PresensiRekap');
		$this->load->model('main/SatuanKerja');

		$reqPeriode= $this->input->get("reqPeriode");

		if($reqSatuanKerjaId == "")
		{
			$tempSatuanKerjaId= $reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}

		$satuankerjakondisi= "";
		if($reqSatuanKerjaId == "")
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND P.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
		}
		else
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND P.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
			
			$skerja= new SatuanKerja();
			$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
			// echo $skerja->query;exit();
			unset($skerja);
			// echo $reqSatuanKerjaId;exit;
			$satuankerjakondisi.= " AND P.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}
		// echo $satuankerjakondisi;exit();
		$statement= $satuankerjakondisi;

		// $statement.= " AND P.PEGAWAI_ID = 8300";

		if(empty($reqStatus))
		{
			$statement.= " AND P.STATUS_PEGAWAI_ID IN (1,2)";
		}
		else
		{
			if($reqStatus == "xxx"){}
			else
			{
				$statement.= " AND 
				(
					P.STATUS_PEGAWAI_ID IN (3,4,5)
					AND 
					EXISTS
					(
						SELECT 1
						FROM
						(
							SELECT PEGAWAI_STATUS_ID
							FROM pegawai_status
							WHERE TMT >= TO_DATE('01".$reqPeriode."', 'DDMMYYYY')
						) XXX WHERE P.PEGAWAI_STATUS_ID = XXX.PEGAWAI_STATUS_ID
					)
				)
				";
			}
		}
		// echo $statement;exit;

		$set= new PresensiRekap();
		$set->selectByParamsPegawai(array(), -1,-1, $statement);
		// echo $set->query;exit;
		while($set->nextRow())
		{
			// $reqPegawaiId= "8300";
			$reqPegawaiId= $set->getField("PEGAWAI_ID");

			$setdetil= new PresensiRekap;
			$setdetil->setField("PERIODE", $reqPeriode);
			$setdetil->setField("PEGAWAI_ID", $reqPegawaiId);
			$setdetil->setField("JAMKERJAJENIS", $req);
			if($setdetil->setabsenharilibur()){}
			else
			{
				echo $reqPegawaiId."<br/>";
			}
		}
	}
	
}