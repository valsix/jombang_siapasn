<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class surat_keluar_bkd_json extends CI_Controller {

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
		$this->SATUAN_KERJA_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID;
		$this->SATUAN_KERJA_BKD_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_BKD_ID;
	}	
	
	function json_surat()
	{
		$this->load->model('persuratan/SuratKeluarBkd');

		$set = new SuratKeluarBkd();
		
		$reqJenis=  $this->input->get('reqJenis');
		$reqSatuanKerjaId=  $this->input->get('reqSatuanKerjaId');
		$reqTahun=  $this->input->get('reqTahun');
		$reqStatusTerima=  $this->input->get('reqStatusTerima');
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		$aColumns = array("TANGGAL", "NOMOR_AWAL", "NOMOR", "PERIHAL", "AKSI", "PERIODE_BULAN", "PERIODE_TAHUN", "SURAT_MASUK_PEGAWAI_ID", "JENIS_ID", "SURAT_KELUAR_BKD_ID");
		$aColumnsAlias = array("TANGGAL", "NOMOR_AWAL", "NOMOR", "PERIHAL", "AKSI", "PERIODE_BULAN", "PERIODE_TAHUN", "SURAT_MASUK_PEGAWAI_ID", "JENIS_ID", "SURAT_KELUAR_BKD_ID");

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
			if ( trim($sOrder) == "ORDER BY TANGGAL desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY CAST(TO_CHAR(A.TANGGAL, 'YYYY') AS NUMERIC) DESC, A.NOMOR_AWAL DESC, COALESCE(A.NOMOR_URUT, 0) DESC, A.TANGGAL DESC ";
				 
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
		
		//kalau admin cari satuan kerja aktif n status kelompok pegawai usul 1
		if($this->LOGIN_LEVEL == 99)
		{
			//$set_detil= new SuratKeluarBkd();
			//$reqSatuanKerjaId= $set_detil->getCountByParamsSatuanKerjaIdSurat();
			//$reqSatuanKerjaId= -1;
		}
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		if($reqSatuanKerjaId == ""){}
		else
		{
			$statement= " AND (A.SATUAN_KERJA_TEKNIS_ID = ".$reqSatuanKerjaId." OR A.SATUAN_KERJA_TERIMA_SURAT_ID = ".$reqSatuanKerjaId.")";
		}
		
		if($reqJenis == ""){}
		else
		$statement.= " AND A.JENIS_ID = ".$reqJenis;
		
		if($reqTahun == ""){}
		else
		$statement.= " AND TO_CHAR(A.TANGGAL, 'YYYY') = '".$reqTahun."'";
		
		if($reqStatusTerima == "1")
		{
			$statement.= " AND A.STATUS_TERIMA IS NOT NULL";
		}
		elseif($reqStatusTerima == "2")
		{
			$statement.= " AND A.STATUS_TERIMA IS NULL";
		}
		else
		$statement.= "";

		$tempSearch= $_GET['sSearch'];
		if(isDate($tempSearch))
		{
			$searchJson .= " AND (A.TANGGAL = TO_DATE('".dateToPageCheck($tempSearch)."','YYYY/MM/DD'))";
		}
		else
		{
			$searchJson= " AND (UPPER(A.NOMOR) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.PERIHAL) LIKE '%".strtoupper($_GET['sSearch'])."%') ";
		}

		// $searchJson = "  AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $set->getCountByParams(array(), $statement);
		// echo $allRecord;

		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParams(array(), $statement.$searchJson);
		// echo $sOrder;exit();
		$set->selectByParams(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
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
			$tempStatusKirimTeknis= $set->getField("STATUS_KIRIM_TEKNIS");
			$tempStatusTerima= $set->getField("STATUS_TERIMA");
			$tempJenisId= $set->getField("JENIS_ID");
			$row = array();
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				if($aColumns[$i] == "TANGGAL")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "TANGGAL_DITERUSKAN")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "TANGGAL_BATAS")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "AKSI")
				{
					if($tempJenisId == "")
					{
						$temp= "";
						//if($this->LOGIN_LEVEL == 99)
						// if($tempStatusTerima == "")
						// {
						// 	$temp= '<a href="javascript:void(0)" onclick="hapussuratdata(\''.$set->getField("SURAT_KELUAR_BKD_ID").'\')" class="round waves-effect waves-light red white-text" title="Hapus" ><i class="mdi-action-delete"></i></a>';
						// 	$row[] = $temp;
						// }
						// else
						// $row[] = $temp;

						$temp= '<a href="javascript:void(0)" onclick="hapussuratdata(\''.$set->getField("SURAT_KELUAR_BKD_ID").'\')" class="round waves-effect waves-light red white-text" title="Hapus" ><i class="mdi-action-delete"></i></a>';

						$row[] = $temp;
					}
					else
					{
						$temp= "";
						if($this->LOGIN_LEVEL == 99 || $tempStatusTerima == "")
						{
							if($tempStatusTerima == ""){}
							else
							{
								if($tempJenisId == 5)
								$temp= '<a href="javascript:void(0)" onclick="hapussuratdata(\''.$set->getField("SURAT_KELUAR_BKD_ID").'\')" class="round waves-effect waves-light red white-text" title="Hapus" ><i class="mdi-action-delete"></i></a>';
								else
								$temp= '<a href="javascript:void(0)" onclick="hapusdata(\''.$set->getField("SURAT_MASUK_PEGAWAI_ID").'\')" class="round waves-effect waves-light red white-text" title="Hapus" ><i class="mdi-action-delete"></i></a>';
							}
						}
						if($tempStatusTerima == "")
						{
							//$temp= '<a href="javascript:void(0)" onclick="terimasuratdata(\''.$set->getField("SURAT_KELUAR_BKD_ID").'\')" title="Surat di Terima" ><img src="images/icon-nonaktip.png" width="15px" heigth="15px"></a>';
						}
						
						if($tempStatusKirimTeknis == "")
						{
							$row[] = $temp;
						}
						else
						$row[] = $temp;
					}
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}
	
	function json_surat_keluar_kgb()
	{
		$this->load->model('persuratan/SuratKeluarBkd');

		$set = new SuratKeluarBkd();
		
		$reqJenis=  $this->input->get('reqJenis');
		$reqSatuanKerjaId=  $this->input->get('reqSatuanKerjaId');
		$reqTahun=  $this->input->get('reqTahun');
		$reqBulan=  $this->input->get('reqBulan');
		$reqTahunData=  $this->input->get('reqTahunData');
		$reqBulanData=  $this->input->get('reqBulanData');
		$reqStatusPilih=  $this->input->get('reqStatusPilih');
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);

		$aColumns = array("PERIODE", "TANGGAL", "NOMOR", "NOMOR_AWAL", "STATUS_TERIMA_NAMA", "NOMOR_AWAL", "JENIS_ID", "SURAT_KELUAR_BKD_ID");
		$aColumnsAlias = array("PERIODE", "TANGGAL", "NOMOR", "NOMOR_AWAL", "STATUS_TERIMA_NAMA", "NOMOR_AWAL", "JENIS_ID", "SURAT_KELUAR_BKD_ID");

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
			if ( trim($sOrder) == "ORDER BY PERIODE desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.NOMOR_AWAL DESC ";
				 
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
		
		//kalau admin cari satuan kerja aktif n status kelompok pegawai usul 1
		if($this->LOGIN_LEVEL == 99)
		{
			//$set_detil= new SuratKeluarBkd();
			//$reqSatuanKerjaId= $set_detil->getCountByParamsSatuanKerjaIdSurat();
			$reqSatuanKerjaId= -1;
		}
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		/*if($reqSatuanKerjaId == ""){}
		else
		{
			$statement= " AND (A.SATUAN_KERJA_TEKNIS_ID = ".$reqSatuanKerjaId." OR A.SATUAN_KERJA_TERIMA_SURAT_ID = ".$reqSatuanKerjaId.")";
		}*/
		
		if($reqJenis == ""){}
		else
		$statement.= " AND A.JENIS_ID = ".$reqJenis;
		
		if($reqTahun == ""){}
		else
		$statement.= " AND TO_CHAR(A.TANGGAL, 'YYYY') = '".$reqTahun."'";
		
		if($reqStatusPilih == "1")
		$statement.= " AND A.STATUS_TERIMA IS NOT NULL";
		
		if($reqBulan == "" && $reqTahun == ""){}
		else
		{
			if($reqBulan == "" && $reqTahun !== "")
			{
				$statement.= " AND SUBSTRING(A.PERIODE,3,4) = '".$reqTahun."'";
			}
			elseif($reqBulan !== "" && $reqTahun !== "")
			{
				$statement.= " AND A.PERIODE = '".$reqBulan.$reqTahun."'";
			}
		}

		// $searchJson = "  AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $set->getCountByParamsData(array(), $statement);
		// echo $allRecord;

		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParamsData(array(), $statement.$searchJson);
		//echo $sOrder;exit;
		$set->selectByParamsData(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
		//echo $set->query;exit;
		/* Output */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);
		 
		while($set->nextRow())
		{
			$tempStatusKirimTeknis= $set->getField("STATUS_KIRIM_TEKNIS");
			$tempJenisId= $set->getField("JENIS_ID");
			$row = array();
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				if($aColumns[$i] == "PERIODE")
				{
					$tempPeriode= $set->getField($aColumns[$i]);
					if($tempPeriode == "")
					$row[] = "";
					else
					$row[] = "01-".getBulanPeriode($tempPeriode)."-".getTahunPeriode($tempPeriode);
				}
				elseif($aColumns[$i] == "TANGGAL")
					$row[] = dateToPageCheck($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "TANGGAL_DITERUSKAN")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "TANGGAL_BATAS")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "STATUS_TERIMA_NAMA")
				{
					if($reqStatusPilih == "1")
					{
						$tempDataPilih= "";
						$tempPeriode= $set->getField("PERIODE");
						$tempPeriodeData= $reqBulanData.$reqTahunData;
						if($tempPeriode == $tempPeriodeData)
						{
							$tempDataPilih= '<a href="javascript:void(0)" onclick="pilihdata(\''.$set->getField("SURAT_KELUAR_BKD_ID").'\')" class="round waves-effect waves-light red white-text" title="Pilih" >Pilih Data</a>';
						}
						$row[] = $tempDataPilih;
					}
					else
					$row[] = $set->getField($aColumns[$i]);
				}
				else if($aColumns[$i] == "AKSI")
				{
					if($tempJenisId == "")
					{
						$row[] = '<a href="javascript:void(0)" onclick="hapussuratdata(\''.$set->getField("SURAT_KELUAR_BKD_ID").'\')" class="round waves-effect waves-light red white-text" title="Hapus" ><i class="mdi-action-delete"></i></a>';
					}
					else
					{
						if($tempStatusKirimTeknis == "")
						$row[] = '<a href="javascript:void(0)" onclick="hapusdata(\''.$set->getField("SURAT_MASUK_PEGAWAI_ID").'\')" class="round waves-effect waves-light red white-text" title="Hapus" ><i class="mdi-action-delete"></i></a>';
						else
						$row[] = '';
					}
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}
	
	function json_surat_keluar()
	{
		$this->load->model('persuratan/SuratKeluarBkd');

		$set = new SuratKeluarBkd();
		
		$reqJenis=  $this->input->get('reqJenis');
		$reqSatuanKerjaId=  $this->input->get('reqSatuanKerjaId');
		$reqTahun=  $this->input->get('reqTahun');
		$reqBulan=  $this->input->get('reqBulan');
			
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		$aColumns = array("TANGGAL", "KLASIFIKASI_KODE", "NOMOR_AWAL", "NOMOR_URUT", "PERIHAL", "SATUAN_KERJA_TUJUAN_NAMA", "JENIS_ID", "SURAT_KELUAR_BKD_ID");
		$aColumnsAlias = array("TANGGAL", "KLASIFIKASI_KODE", "NOMOR_AWAL", "NOMOR_URUT", "PERIHAL", "SATUAN_KERJA_TUJUAN_NAMA", "JENIS_ID", "SURAT_KELUAR_BKD_ID");

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
			if ( trim($sOrder) == "ORDER BY TANGGAL desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.NOMOR_AWAL DESC, A.NOMOR_URUT DESC ";
				 
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
		
		//kalau admin cari satuan kerja aktif n status kelompok pegawai usul 1
		if($this->LOGIN_LEVEL == 99)
		{
			//$set_detil= new SuratKeluarBkd();
			//$reqSatuanKerjaId= $set_detil->getCountByParamsSatuanKerjaIdSurat();
			$reqSatuanKerjaId= -1;
		}
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		/*if($reqSatuanKerjaId == ""){}
		else
		{
			$statement= " AND (A.SATUAN_KERJA_TEKNIS_ID = ".$reqSatuanKerjaId." OR A.SATUAN_KERJA_TERIMA_SURAT_ID = ".$reqSatuanKerjaId.")";
		}*/
		
		if($reqJenis == ""){}
		else
		$statement.= " AND A.JENIS_ID = ".$reqJenis;
		
		if($reqTahun == ""){}
		else
		$statement.= " AND TO_CHAR(A.TANGGAL, 'YYYY') = '".$reqTahun."'";
		
		//$statement.= " AND A.STATUS_TERIMA IS NOT NULL";
		
		if($reqBulan == "" && $reqTahun == ""){}
		else
		{
			if($reqBulan == "" && $reqTahun !== "")
			{
				$statement.= " AND TO_CHAR(A.TANGGAL, 'YYYY') = '".$reqTahun."'";
			}
			elseif($reqBulan !== "" && $reqTahun !== "")
			{
				$statement.= " AND TO_CHAR(A.TANGGAL, 'MMYYYY') = '".$reqBulan.$reqTahun."'";
			}
		}

		$statement.= " AND A.NOMOR_AWAL IS NOT NULL";

		$searchJson = "  AND (UPPER(A.PERIHAL) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NOMOR) LIKE '%".strtoupper($_GET['sSearch'])."%' )";
		$allRecord = $set->getCountByParamsMonitoring(array(), $statement);
		// echo $allRecord;

		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParamsMonitoring(array(), $statement.$searchJson);
		
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
			$tempStatusKirimTeknis= $set->getField("STATUS_KIRIM_TEKNIS");
			$tempJenisId= $set->getField("JENIS_ID");
			$row = array();
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				if($aColumns[$i] == "TANGGAL")
					$row[] = dateToPageCheck($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "TANGGAL_DITERUSKAN")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "TANGGAL_BATAS")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "AKSI")
				{
					if($tempJenisId == "")
					{
						$row[] = '<a href="javascript:void(0)" onclick="hapussuratdata(\''.$set->getField("SURAT_KELUAR_BKD_ID").'\')" class="round waves-effect waves-light red white-text" title="Hapus" ><i class="mdi-action-delete"></i></a>';
					}
					else
					{
						if($tempStatusKirimTeknis == "")
						$row[] = '<a href="javascript:void(0)" onclick="hapusdata(\''.$set->getField("SURAT_MASUK_PEGAWAI_ID").'\')" class="round waves-effect waves-light red white-text" title="Hapus" ><i class="mdi-action-delete"></i></a>';
						else
						$row[] = '';
					}
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}
	
	function json_pegawai()
	{
		$this->load->model('persuratan/SuratMasukPegawai');

		$set = new SuratMasukPegawai();
		
		$reqJenis=  $this->input->get('reqJenis');
		$reqSatuanKerjaId=  $this->input->get('reqSatuanKerjaId');
		$reqTahun=  $this->input->get('reqTahun');
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		$aColumns = array("NIP_BARU", "NAMA_LENGKAP", "SATUAN_KERJA_INDUK", "PROSES_STATUS", "PROSES_TANGGAL");
		$aColumnsAlias = array("NIP_BARU", "NAMA_LENGKAP", "SATUAN_KERJA_INDUK", "PROSES_STATUS", "PROSES_TANGGAL");

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
			if ( trim($sOrder) == "ORDER BY TANGGAL desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.TANGGAL ASC ";
				 
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
		
		//kalau admin cari satuan kerja aktif n status kelompok pegawai usul 1
		if($this->LOGIN_LEVEL == 99)
		{
			//$set_detil= new SuratKeluarBkd();
			//$reqSatuanKerjaId= $set_detil->getCountByParamsSatuanKerjaIdSurat();
			//$reqSatuanKerjaId= -1;
		}
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		if($reqSatuanKerjaId == ""){}
		else
		{
			// $statement= " AND (A.SATUAN_KERJA_TEKNIS_ID = ".$reqSatuanKerjaId." OR A.SATUAN_KERJA_TERIMA_SURAT_ID = ".$reqSatuanKerjaId.")";
		}
		
		if($reqJenis == ""){}
		else
		{
			$statement.= " AND SMP.JENIS_ID = ".$reqJenis;
		}
		
		if($reqTahun == ""){}
		else
		$statement.= " AND TO_CHAR(COALESCE(SMU.TANGGAL, SMB.TANGGAL), 'YYYY') = '".$reqTahun."'";
		
		$searchJson = "  AND (UPPER(A.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $set->getCountByParamsUsulanPegawai(array(), $statement);
		// echo $allRecord;

		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParamsUsulanPegawai(array(), $statement.$searchJson);
		
		$sOrder= " ORDER BY COALESCE(SMU.TANGGAL, SMB.TANGGAL) DESC";
		$set->selectByParamsUsulanPegawai(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
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
			$tempStatusKirimTeknis= $set->getField("STATUS_KIRIM_TEKNIS");
			$row = array();
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				if($aColumns[$i] == "PROSES_TANGGAL")
					$row[] = datetimeToPage($set->getField($aColumns[$i]), "datetime");
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}

	function json_teknis() 
	{
		$this->load->model('persuratan/SuratKeluarBkd');

		$set = new SuratKeluarBkd();
		
		$reqJenis=  $this->input->get('reqJenis');
		$reqSatuanKerjaId=  $this->input->get('reqSatuanKerjaId');
		$reqTahun=  $this->input->get('reqTahun');
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		$aColumns = array("NOMOR", "TANGGAL", "NIP_BARU", "NAMA_LENGKAP", "SATUAN_KERJA_LENGKAP", "AKSI", "SURAT_MASUK_PEGAWAI_ID", "JENIS_ID", "SURAT_KELUAR_BKD_ID");
		$aColumnsAlias = array("NOMOR", "TANGGAL", "NIP_BARU", "NAMA_LENGKAP", "SATUAN_KERJA_LENGKAP", "AKSI", "SURAT_MASUK_PEGAWAI_ID", "JENIS_ID", "SURAT_KELUAR_BKD_ID");

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
			if ( trim($sOrder) == "ORDER BY TANGGAL desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.TANGGAL ASC ";
				 
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
		
		//kalau admin cari satuan kerja aktif n status kelompok pegawai usul 1
		if($this->LOGIN_LEVEL == 99)
		{
			//$set_detil= new SuratKeluarBkd();
			//$reqSatuanKerjaId= $set_detil->getCountByParamsSatuanKerjaIdSurat();
			//$reqSatuanKerjaId= -1;
		}
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		if($reqSatuanKerjaId == ""){}
		else
		{
			$statement= " AND (A.SATUAN_KERJA_TEKNIS_ID = ".$reqSatuanKerjaId." OR A.SATUAN_KERJA_TERIMA_SURAT_ID = ".$reqSatuanKerjaId.")";
		}
		
		if($reqJenis == ""){}
		else
		{
			// if($reqJenis == 7)
			// {
			// 	$statement.= " AND A.JENIS_ID IN (7,12)";
			// }
			// else
			$statement.= " AND A.JENIS_ID = ".$reqJenis;
		}
		
		if($reqTahun == ""){}
		else
		$statement.= " AND TO_CHAR(A.TANGGAL, 'YYYY') = '".$reqTahun."'";
		
		$statement.= " AND A.STATUS_TERIMA IS NOT NULL";
		
		// $searchJson = "  AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $set->getCountByParams(array(), $statement);
		// echo $allRecord;

		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParams(array(), $statement.$searchJson);
		
		$set->selectByParams(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
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
			$tempStatusKirimTeknis= $set->getField("STATUS_KIRIM_TEKNIS");
			$row = array();
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				if($aColumns[$i] == "TANGGAL")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "TANGGAL_DITERUSKAN")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "TANGGAL_BATAS")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "AKSI")
				{
					if($tempStatusKirimTeknis == "")
					$row[] = '<a href="javascript:void(0)" onclick="hapusdata(\''.$set->getField("SURAT_MASUK_PEGAWAI_ID").'\')" class="round waves-effect waves-light red white-text" title="Hapus" ><i class="mdi-action-delete"></i></a>';
					else
					$row[] = '';
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}
	
	function surat_keluar_kgb_nomor()
	{
		$this->load->model('persuratan/SuratKeluarBkd');
		
		$set = new SuratKeluarBkd();
		$reqId= $this->input->post("reqId");
		$reqMode= $this->input->post("reqMode");
		$reqTipe= $this->input->post("reqTipe");
		
		$reqTanggal= $this->input->post("reqTanggal");
		$reqJenis= $this->input->post("reqJenis");
		$reqKlasifikasiId= $this->input->post("reqKlasifikasiId");
		$reqNomorAwal= $this->input->post("reqNomorAwal");
		$reqNomorUrut= $this->input->post("reqNomorUrut");
		$reqNomor= $this->input->post("reqNomor");
		$reqPerihal= $this->input->post("reqPerihal");
		$reqIsManual= $this->input->post("reqIsManual");
		$reqSatuanKerjaTujuanId= $this->input->post("reqSatuanKerjaTujuanId");
		$reqSatuanKerjaTujuanNama= $this->input->post("reqSatuanKerjaTujuanNama");
		$reqPeriode= $this->input->post("reqPeriode");
		
		$set->setField('NOMOR', $reqNomor);
		$set->setField('JENIS_ID', ValToNullDB($reqJenis));
		$set->setField('NOMOR_AWAL', ValToNullDB($reqNomorAwal));
		$set->setField('NOMOR_URUT', ValToNullDB($reqNomorUrut));
		$set->setField('KLASIFIKASI_ID', $reqKlasifikasiId);
		$set->setField('TANGGAL', dateToDBCheck($reqTanggal));
		$set->setField('PERIHAL', $reqPerihal);
		$set->setField('IS_MANUAL', ValToNullDB($reqIsManual));
		//$set->setField('SATUAN_KERJA_TERIMA_SURAT_ID', $this->SATUAN_KERJA_ID);
		//$set->setField('SATUAN_KERJA_TERIMA_SURAT_ID', 231);
		$set->setField('SATUAN_KERJA_TERIMA_SURAT_ID', $this->SATUAN_KERJA_BKD_ID);
		$set->setField('SATUAN_KERJA_TUJUAN_ID', ValToNullDB($reqSatuanKerjaTujuanId));
		$set->setField('SATUAN_KERJA_TUJUAN_NAMA', $reqSatuanKerjaTujuanNama);
		if($reqTipe == "1")
		$set->setField('STATUS_TERIMA', ValToNullDB($req));
		else
		$set->setField('STATUS_TERIMA', ValToNullDB("1"));
		
		$set->setField('PERIODE', ValToNullDB($reqPeriode));
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("LAST_DATE", "NOW()");
		
		if($reqMode == "insert")
		{
			if($set->insert())
			{
				$reqId= $set->id;
				echo $reqId."-Data berhasil disimpan.";
			}
			else
				echo "xxx-Data gagal disimpan.";
		}
		else
		{
			$set->setField('SURAT_KELUAR_BKD_ID', $reqId);
			if($set->update())
				echo $reqId."-Data berhasil disimpan.";
			else 
				echo "xxx-Data gagal disimpan.";
		}
	}
	
	function add()
	{
		$this->load->model('persuratan/SuratKeluarBkd');
		
		$set = new SuratKeluarBkd();
		$reqId= $this->input->post("reqId");
		$reqMode= $this->input->post("reqMode");
		
		$reqTanggal= $this->input->post("reqTanggal");
		$reqKlasifikasiId= $this->input->post("reqKlasifikasiId");
		$reqNomorAwal= $this->input->post("reqNomorAwal");
		$reqNomorUrut= $this->input->post("reqNomorUrut");
		$reqNomor= $this->input->post("reqNomor");
		$reqPerihal= $this->input->post("reqPerihal");
		$reqIsManual= $this->input->post("reqIsManual");
		$reqSatuanKerjaTujuanId= $this->input->post("reqSatuanKerjaTujuanId");
		$reqSatuanKerjaTujuanNama= $this->input->post("reqSatuanKerjaTujuanNama");
		
		$set->setField('NOMOR', $reqNomor);
		$set->setField('JENIS_ID', ValToNullDB($req));
		$set->setField('NOMOR_AWAL', ValToNullDB($reqNomorAwal));
		$set->setField('NOMOR_URUT', ValToNullDB($reqNomorUrut));
		$set->setField('KLASIFIKASI_ID', $reqKlasifikasiId);
		$set->setField('TANGGAL', dateToDBCheck($reqTanggal));
		$set->setField('PERIHAL', $reqPerihal);
		$set->setField('IS_MANUAL', ValToNullDB($reqIsManual));
		if($this->LOGIN_LEVEL == 99)
		{
			if($this->SATUAN_KERJA_ID == "")
			$set->setField('SATUAN_KERJA_TERIMA_SURAT_ID', $this->SATUAN_KERJA_BKD_ID);
			else
			$set->setField('SATUAN_KERJA_TERIMA_SURAT_ID', $this->SATUAN_KERJA_ID);
		}
		else
		$set->setField('SATUAN_KERJA_TERIMA_SURAT_ID', $this->SATUAN_KERJA_ID);
		
		$set->setField('SATUAN_KERJA_TUJUAN_ID', ValToNullDB($reqSatuanKerjaTujuanId));
		$set->setField('SATUAN_KERJA_TUJUAN_NAMA', $reqSatuanKerjaTujuanNama);
		$set->setField('STATUS_TERIMA', ValToNullDB("1"));
		$set->setField('PERIODE', ValToNullDB($req));
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("LAST_DATE", "NOW()");
		
		if($reqMode == "insert")
		{
			if($set->insert())
			{
				$reqId= $set->id;
				echo $reqId."-Data berhasil disimpan.";
			}
			else
				echo "xxx-Data gagal disimpan.";
		}
		else
		{
			$set->setField('SURAT_KELUAR_BKD_ID', $reqId);
			if($set->update())
				echo $reqId."-Data berhasil disimpan.";
			else 
				echo "xxx-Data gagal disimpan.";
		}
	}
	
	function surat_keluar_nomor()
	{
		$this->load->model('persuratan/SuratKeluarBkd');
		
		$set = new SuratKeluarBkd();

		$reqId = $this->input->post("reqId");
		$reqMode = $this->input->post("reqMode");

		$reqNomorAwal= $this->input->post("reqNomorAwal");
		$reqNomorUrut= $this->input->post("reqNomorUrut");
		$reqTanggal= $this->input->post("reqTanggal");
		$reqNomor= $this->input->post("reqNomor");
		$reqTandaTanganBkdId= $this->input->post("reqTandaTanganBkdId");
		
		$set->setField('NOMOR', $reqNomor);
		$set->setField('NOMOR_AWAL', $reqNomorAwal);
		$set->setField('NOMOR_URUT', ValToNullDB($reqNomorUrut));
		$set->setField('TANDA_TANGAN_BKD_ID', $reqTandaTanganBkdId);
		$set->setField('TANGGAL', dateToDBCheck($reqTanggal));
		$set->setField('SURAT_KELUAR_BKD_ID', $reqId); 
		
		$statement= " AND A.SURAT_KELUAR_BKD_ID = ".$reqId;
		$set_detil= new SuratKeluarBkd();
		$set_detil->selectByParamsData(array(), -1,-1, $statement);
		$set_detil->firstRow();
		$tempStatusKirimTeknis= $set_detil->getField("STATUS_KIRIM_TEKNIS");
		$tempJenisId= $set_detil->getField("JENIS_ID");
		// echo $tempStatusKirimTeknis;exit;
		if($tempStatusKirimTeknis == "2")
		{
			if($set->updateNomorDataSurat())
			{
				echo $reqId."-Data berhasil disimpan.";
			}
			else
				echo $reqId."-Data gagal disimpan.";
		}
		else
		{
			if($tempJenisId == "6" || $tempJenisId == "8" || $tempJenisId == "9" || $tempJenisId == "12")
			{
				if($set->updateNomorDataSurat2())
				{
					echo $reqId."-Data berhasil disimpan.";
				}
				else
					echo $reqId."-Data gagal disimpan.";
			}
			else
			{
				if($set->updateNomorSurat())
				{
					echo $reqId."-Data berhasil disimpan.";
				}
				else
					echo $reqId."-Data gagal disimpan.";
			}
		}
		
	}
	
	function json() 
	{
		$this->load->model('persuratan/SuratKeluarBkd');

		$set = new SuratKeluarBkd();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
					
		$aColumns = array("NOMOR", "TANGGAL", "TOTAL_PEGAWAI", "TOTAL_PEGAWAI_PROGRES", "TOTAL_PEGAWAI_KEMBALI", "SATUAN_KERJA_ASAL_NAMA", "POSISI_TERAKHIR", "AKSI", "SURAT_KELUAR_BKD_ID");
		$aColumnsAlias = array("NOMOR", "TANGGAL", "TOTAL_PEGAWAI", "TOTAL_PEGAWAI_PROGRES", "TOTAL_PEGAWAI_KEMBALI", "SATUAN_KERJA_ASAL_NAMA", "POSISI_TERAKHIR", "AKSI", "SURAT_KELUAR_BKD_ID");

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
			if ( trim($sOrder) == "ORDER BY TANGGAL desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.TANGGAL ASC ";
				 
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
		
		//kalau admin cari satuan kerja aktif n status kelompok pegawai usul 1
		if($this->LOGIN_LEVEL == 99)
		{
			$set_detil= new SuratKeluarBkd();
			$reqSatuanKerjaId= $set_detil->getCountByParamsSatuanKerjaIdSurat();
		}
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		if($reqSatuanKerjaId == ""){}
		else
		{
			$statement= " AND A.SATUAN_KERJA_ASAL_ID = ".$reqSatuanKerjaId;
		}
		
		// $searchJson = "  AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $set->getCountByParams(array(), $statement);
		// echo $allRecord;

		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParams(array(), $statement.$searchJson);
		
		$set->selectByParams(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
		//echo $set->query;exit;
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
				else if($aColumns[$i] == "TANGGAL_DITERUSKAN")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "TANGGAL_BATAS")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "AKSI")
				{
					if($set->getField("STATUS_KIRIM") == "1")
					$row[] = '';
					else
					$row[] = '<a href="javascript:void(0)" onclick="hapusdata(\''.$set->getField("SURAT_KELUAR_BKD_ID").'\')" class="round waves-effect waves-light red white-text" title="Hapus" ><i class="mdi-action-delete"></i></a>';
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}
	
	function json_suratbak() 
	{
		$this->load->model('persuratan/SuratKeluarBkd');
		
		$reqJenis=  $this->input->get('reqJenis');
		
		$set = new SuratKeluarBkd();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
			
		$aColumns = array("NO_AGENDA", "TANGGAL", "PERIHAL", "SATUAN_KERJA_ASAL_NAMA", "NOMOR", "POSISI_TERAKHIR", "JENIS_ID", "SURAT_KELUAR_BKD_ID");
		$aColumnsAlias = array("NO_AGENDA", "TANGGAL", "PERIHAL", "SATUAN_KERJA_ASAL_NAMA", "NOMOR", "POSISI_TERAKHIR", "JENIS_ID", "SURAT_KELUAR_BKD_ID");

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
			if ( trim($sOrder) == "ORDER BY TANGGAL desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.TANGGAL ASC ";
				 
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
		
		//kalau admin cari satuan kerja aktif n status kelompok pegawai usul 1
		if($this->LOGIN_LEVEL == 99)
		{
			$set_detil= new SuratKeluarBkd();
			$reqSatuanKerjaId= $set_detil->getCountByParamsSatuanKerjaIdSurat();
		}
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}	
		
		if($reqJenis == ""){}
		else
		$statementdisposisi= " AND JENIS_ID = ".$reqJenis;
		
		// $searchJson = "  AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $set->getCountByParamsSurat(array(), $reqSatuanKerjaId, $statementdisposisi, $statement);
		// echo $allRecord;

		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParamsSurat(array(), $reqSatuanKerjaId, $statementdisposisi, $statement.$searchJson);
		
		$set->selectByParamsSurat(array(), $dsplyRange, $dsplyStart, $reqSatuanKerjaId, $statementdisposisi, $statement.$searchJson, $sOrder);
		//echo $set->query;exit;
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
				else if($aColumns[$i] == "TANGGAL_DITERUSKAN")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "TANGGAL_BATAS")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}
	
	function statuskirim()
	{
		$this->load->model('persuratan/SuratKeluarBkd');
		$this->load->model('persuratan/SuratMasukPegawai');
		$set = new SuratKeluarBkd();
		
		$reqId =  $this->input->get('reqId');
		$reqJenis=  $this->input->get('reqJenis');
		$reqPerihal=  $this->input->get('reqPerihal');
		$reqStatusBerkas=  $this->input->get('reqStatusBerkas');
		
		$statement= " AND SMP.JENIS_ID = ".$reqJenis." AND SMP.SURAT_KELUAR_BKD_ID = ".$reqId;
		$set_detil= new SuratMasukPegawai();
		$set_detil->selectByParamsUsulanPegawai(array(), -1, -1, $statement, "ORDER BY A.PEGAWAI_ID");
		//echo $set_detil->query;exit;
		$tempJumlahDataUsulan= 0;
		while($set_detil->nextRow())
		{
			//echo $set_detil->query;exit;
			if($tempJumlahDataUsulan == 0)
			$tempNamaSaja= $set_detil->getField("NAMA_SAJA");
			
			$tempJumlahDataUsulan++;
		}
		
		$tempPerihalInfo= $reqPerihal." a.n ".$tempNamaSaja;
		if($tempJumlahDataUsulan == 1){}
		else
		$tempPerihalInfo= $tempPerihalInfo." dkk";
		
		$set->setField("SURAT_KELUAR_BKD_ID", $reqId);
		$set->setField("PERIHAL", setQuote($tempPerihalInfo));
		$set->setField("STATUS_KIRIM", ValToNullDB("1"));
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("LAST_DATE", "NOW()");
		
		if($set->updatePerihalKirim())
		{
			$set_detil= new SuratMasukPegawai();
			$set_detil->setField("SURAT_KELUAR_BKD_ID", $reqId);
			$set_detil->setField("STATUS_BERKAS", $reqStatusBerkas);
			$set_detil->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
			$set_detil->setField("LAST_USER", $this->LOGIN_USER);
			$set_detil->setField("LAST_DATE", "NOW()");
			$set_detil->updateStatusBerkasBkd();
			
			$arrJson["PESAN"] = "Data berhasil di kirim.";
		}
		else
			$arrJson["PESAN"] = "Data gagal di kirim.";		
		
		echo json_encode($arrJson);
	}
	
	function statusbatal()
	{
		$this->load->model('persuratan/SuratKeluarBkd');
		$set = new SuratKeluarBkd();
		
		$reqId =  $this->input->get('reqId');
		$set->setField("SURAT_KELUAR_BKD_ID", $reqId);
		
		if($set->updateBatal())
			$arrJson["PESAN"] = "Data berhasil dibatalkan.";
		else
			$arrJson["PESAN"] = "Data gagal dibatalkan.";		
		
		echo json_encode($arrJson);
	}

	function status_terima()
	{
		$this->load->model('persuratan/SuratKeluarBkd');
		$set = new SuratKeluarBkd();
		
		$reqId =  $this->input->get('reqId');
		$set->setField("SURAT_KELUAR_BKD_ID", $reqId);
		$set->setField("STATUS_TERIMA", "1");
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("LAST_DATE", "NOW()");
		
		if($set->updateStatusTerima())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";		
		
		echo json_encode($arrJson);
	}
	
	function delete()
	{
		$this->load->model('persuratan/SuratKeluarBkd');
		$set = new SuratKeluarBkd();
		
		$reqId =  $this->input->get('reqId');
		$set->setField("SURAT_KELUAR_BKD_ID", $reqId);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("LAST_DATE", "NOW()");
		
		if($set->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";		
		
		echo json_encode($arrJson);
	}
	
	function delete_pegawai()
	{
		$this->load->model('persuratan/SuratMasukPegawai');
		$set = new SuratMasukPegawai();
		
		$reqId =  $this->input->get('reqId');
		$set->setField("SURAT_KELUAR_PEGAWAI_ID", $reqId);
		$set->setField("STATUS_BERKAS", "3");
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("LAST_DATE", "NOW()");
		if($set->resetbidang())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";		
		
		echo json_encode($arrJson);
	}
	
	function combo() 
	{
		$this->load->model("Eselon");
		$set = new Eselon();
		
		$set->selectByParams();
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("BANK_ID");
			$arr_json[$i]['text'] = $set->getField("NAMA");	
			$i++;
		}
		echo json_encode($arr_json);		
		
	}	

	function log() 
	{
		$this->load->model('PegawaiLog');

		$set = new PegawaiLog();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		$aColumns = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "PEGAWAI_ID");
		$aColumnsAlias = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "PEGAWAI_ID");

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
				$sOrder = " ORDER BY A.LAST_DATE ASC ";
				 
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
		// echo $allRecord;

		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParams(array(), $searchJson);
		
		$set->selectByParams(array(), $dsplyRange, $dsplyStart, $searchJson, $sOrder);
		//echo $set->query;exit;
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
					//$row[] = truncate($set->getField($aColumns[$i]), 5)."...";
					$row[] = '<img src="images/foto-profile.jpg" />';
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}
	
	function getrevisiinfo()
	{
	}
	
	function getinfo()
	{
		$this->load->model('persuratan/SuratKeluarBkd');

		$set = new SuratKeluarBkd();
		$reqId= $this->input->get('reqId');
		$reqPangkatId= $this->input->get('reqPangkatId');
		$reqTmtLama= $this->input->get('reqTmtLama');
		$reqMasaKerjaBulan= $this->input->get('reqMasaKerjaBulan');
		$reqMasaKerjaTahun= $this->input->get('reqMasaKerjaTahun');
		
		$statement= " AND A.SURAT_KELUAR_BKD_ID = ".$reqId;
		$set->selectByParamsData(array(), -1,-1, $statement);
		$set->firstRow();
		//echo $set->query;exit;
		$reqNomorAwal= $set->getField('NOMOR_AWAL');
		$reqNomorUrut= $set->getField('NOMOR_URUT');
		$reqPeriode= $set->getField('PERIODE');
		$reqRiwayatGajiBaruTanggalBaru= dateToPageCheck($set->getField('TANGGAL'));
		$reqRiwayatGajiBaruTmt= "01-".getBulanPeriode($reqPeriode)."-".getTahunPeriode($reqPeriode);
		
		$set= new SuratKeluarBkd();
		$set->selectByParamsPangkatTurunInfo($reqPangkatId, dateToDbCheck($reqTmtLama), dateToDbCheck($reqRiwayatGajiBaruTmt));
		//echo $set->query;exit;
		$set->firstRow();
		$tempPangkatId= $reqPangkatId;
		$tempSelisih= $set->getField("SELISIH");
		$arrSelisih= explode(" - ", $tempSelisih);
		$tempMasaKerjaTahun= $arrSelisih[0];
		$tempMasaKerjaBulan= $arrSelisih[1];
		
		$tempMasaKerjaBulanPerubahan= $reqMasaKerjaBulan + $tempMasaKerjaBulan;
		if($tempMasaKerjaBulanPerubahan % 12 == 0)
		$tempMasaKerjaBulanPerubahan= 0;
		
		$tempMasaKerjaTahunPerubahan= $reqMasaKerjaTahun + $tempMasaKerjaTahun + floor(($reqMasaKerjaBulan + $tempMasaKerjaBulan) / 12);
		
		$set= new SuratKeluarBkd();
		$tempPeriode= str_replace("-","",$reqRiwayatGajiBaruTmt);
		$tempGajiPokok= numberToIna($set->getCountByParamsGajiBerlaku($tempPeriode, $tempMasaKerjaTahunPerubahan, $tempPangkatId));
		if($tempGajiPokok == "")
		$tempGajiPokok= 0;
		
		$arrData= array("reqNomorAwal"=>$reqNomorAwal, "reqNomorUrut"=>$reqNomorUrut
		, "reqRiwayatGajiBaruTanggalBaru"=>$reqRiwayatGajiBaruTanggalBaru, "reqPeriode"=>$reqPeriode
		, "reqRiwayatGajiBaruTmt"=>$reqRiwayatGajiBaruTmt
		, "reqRiwayatGajiBaruGaji"=>$tempGajiPokok, "reqRiwayatGajiBaruMasaKerjaBulan"=>$tempMasaKerjaBulanPerubahan
		, "reqRiwayatGajiBaruMasaKerjaTahun"=>$tempMasaKerjaTahunPerubahan
		);
		echo json_encode($arrData);
	}
	
	function setnomorawal()
	{
		$reqTipe= $this->input->get('reqTipe');
		$tanggalHariIni= $this->input->get('tanggalHariIni');
		$reqNomorSuratTahun= $this->input->get('reqNomorSuratTahun');
		$reqTanggal= $this->input->get('reqTanggal');
		
		$this->load->model('persuratan/SuratKeluarBkd');
		$statement= " AND TO_CHAR(TANGGAL, 'YYYY') = '".$reqNomorSuratTahun."'";
		$set_detil= new SuratKeluarBkd();
		$reqNomorAwal= $set_detil->getCountByParamsNomorAwalPerTahun($statement);
		// echo $reqNomorAwal;exit();
		
		$statement= " AND TO_CHAR(TANGGAL, 'YYYY') = '".$reqNomorSuratTahun."' AND A.NOMOR_AWAL IS NOT NULL";
		$reqTanggalCek= $set_detil->getCountByParamsTanggalPerTahun($statement);
		// echo $set_detil->query;exit();
		
		$TglSKeluarUsul= $reqTanggal;
		$TglSKeluarTRecord= dateToPageCheck($reqTanggalCek);
		if($reqTanggalCek == "")
		$TglSKeluarTRecordPlus= "";
		else
		{
		$TglSKeluarTRecordPlus= date('Y-m-d',strtotime($date . "+1 days"));
		/*$TglSKeluarTRecordPlus= date_create($reqTanggalCek);
		date_add($TglSKeluarTRecordPlus,date_interval_create_from_date_string("1 days"));
		$TglSKeluarTRecordPlus= date_format($TglSKeluarTRecordPlus,"Y-m-d");*/
		$TglSKeluarTRecordPlus= dateToPageCheck($TglSKeluarTRecordPlus);
		}
		$TglAkses= $tanggalHariIni;
		
		//echo $TglSKeluarUsul." == ".$TglSKeluarTRecord."&&".$TglSKeluarTRecord." <= ".$TglAkses;exit;
		// && (strtotime($TglSKeluarUsul) <= strtotime($TglAkses))
		// if
		// (
		// 	( (strtotime($TglSKeluarUsul) > strtotime($TglSKeluarTRecord)) )
		// 	// (strtotime($TglSKeluarUsul) <= strtotime($TglAkses))
		// )
		// 	echo "s";

		// echo $TglSKeluarUsul." == ".$TglSKeluarTRecord." && ".$TglSKeluarTRecord." <= ".$TglAkses." || ".$TglSKeluarUsul." == ".$TglAkses." && ".$TglSKeluarTRecordPlus." == ".$TglAkses." || ".$TglSKeluarUsul." > ".$TglSKeluarTRecord." && ".$TglSKeluarUsul."  <= ".$TglAkses;exit();

		$tahunsekarang= date("Y");

		$incrementnomor= "";
		if(
		(
		(strtotime($TglSKeluarUsul) == strtotime($TglSKeluarTRecord)) && (strtotime($TglSKeluarTRecord) <= strtotime($TglAkses))
		) 
		|| 
		((strtotime($TglSKeluarUsul) == strtotime($TglAkses)) && (strtotime($TglSKeluarTRecordPlus) == strtotime($TglAkses))) 
		|| ((strtotime($TglSKeluarUsul) > strtotime($TglSKeluarTRecord)) && (strtotime($TglSKeluarUsul) <= strtotime($TglAkses)))

		// tambahan coba
		|| $reqNomorSuratTahun < $tahunsekarang
		)
		$incrementnomor= "1";

		// echo $incrementnomor;exit();
		
		if($incrementnomor == "1")
		{
			if($reqNomorAwal == "")
			$reqNomorAwal= 1;
			
			if($reqStatusTerima == "" && $reqTipe == "1")
			{
				$reqNomorAwal= "";
			}
		}
		else
		{
			$reqNomorAwal= "";
		}
		
		$arrData= array("reqNomorAwal"=>$reqNomorAwal);
		echo json_encode($arrData);
		
	}
	
}
?>