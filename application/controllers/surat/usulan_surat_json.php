<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class usulan_surat_json extends CI_Controller {

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
		$this->LOGIN_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_ID;
		$this->LOGIN_PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID;
	}	
	
	function json() 
	{
		$this->load->model('persuratan/UsulanSurat');

		$set = new UsulanSurat();
		$reqJenis= $this->input->get("reqJenis");
		$reqKirim= $this->input->get("reqKirim");

		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
					
		$aColumns = array("ID_SEMENTARA", "NOMOR", "TANGGAL", "TOTAL_USULAN", "POSISI_TERAKHIR", "AKSI", "USULAN_SURAT_ID");
		$aColumnsAlias = array("ID_SEMENTARA", "NOMOR", "TANGGAL", "TOTAL_USULAN", "POSISI_TERAKHIR", "AKSI", "USULAN_SURAT_ID");

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
			if ( trim($sOrder) == "ORDER BY ID_SEMENTARA desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.TANGGAL DESC NULLS FIRST, A.USULAN_SURAT_ID DESC";
				 
			}
			// echo $sOrder;exit;
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
		
		$tempLoginLevel= $this->LOGIN_LEVEL;
		//kalau admin cari satuan kerja aktif n status kelompok pegawai usul 1
		if($this->LOGIN_LEVEL == 99)
		{
			$set_detil= new UsulanSurat();
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
		
		if($reqJenis == ""){}
		else
		{
			$statement= " AND A.JENIS_ID = ".$reqJenis;
		}

		// if($reqKirim == ""){}
		// else
		// {
		// 	$statement.= " AND A.KIRIM_KE = ".$reqKirim;
		// }
		
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
			$row = array();
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				if($aColumns[$i] == "TANGGAL")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "TANGGAL_DITERUSKAN")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "TOTAL_PEGAWAI_PROGRES_KEMBALI")
				{
					$tempValue= $set->getField("TOTAL_PEGAWAI")." / ".$set->getField("TOTAL_PEGAWAI_PROGRES")." / ".$set->getField("TOTAL_PEGAWAI_KEMBALI");
					if($set->getField("TOTAL_PEGAWAI_KEMBALI") > 0)
					$tempValue= $set->getField("TOTAL_PEGAWAI")." / ".$set->getField("TOTAL_PEGAWAI_PROGRES")." / <label style='color:red; font-size:10px'>".$set->getField("TOTAL_PEGAWAI_KEMBALI")."</label>";
					$row[] = $tempValue;
				}
				else if($aColumns[$i] == "TANGGAL_BATAS")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "AKSI")
				{
					if($tempLoginLevel == 99)
					{
						$row[] = '<a href="javascript:void(0)" onclick="hapusdata(\''.$set->getField("USULAN_SURAT_ID").'\')" class="round waves-effect waves-light red white-text" title="Hapus" ><i class="mdi-action-delete"></i></a>';
					}
					else
					{
						if($set->getField("STATUS_KIRIM") == "")
						$row[] = '<a href="javascript:void(0)" onclick="hapusdata(\''.$set->getField("USULAN_SURAT_ID").'\')" class="round waves-effect waves-light red white-text" title="Hapus" ><i class="mdi-action-delete"></i></a>';
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
	
	function json_pilih() 
	{
		$this->load->model('persuratan/UsulanSurat');

		$set = new UsulanSurat();
		
		$reqId= $this->input->get("reqId");
		$reqJenis= $this->input->get("reqJenis");
		$reqKategori= $this->input->get("reqKategori");
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);

		$aColumns = array("ID_SEMENTARA", "JUMLAH_DATA", "TANGGAL_DIBUAT", "AKSI", "USULAN_SURAT_ID");
		$aColumnsAlias = array("ID_SEMENTARA", "JUMLAH_DATA", "TANGGAL_DIBUAT", "AKSI", "USULAN_SURAT_ID");

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
			if ( trim($sOrder) == "ORDER BY ID_SEMENTARA desc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.TANGGAL_DIBUAT DESC ";
				 
			}
			// echo $sOrder;exit;
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
			//$set_detil= new UsulanSurat();
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
			//$statement= " AND A.USULAN_SURAT_ID IN (SELECT USULAN_SURAT_ID FROM persuratan.USULAN_SURAT_DISPOSISI WHERE SATUAN_KERJA_ASAL_ID = ".$reqSatuanKerjaId." AND TERBACA = 1 GROUP BY USULAN_SURAT_ID)";
		}
		
		// kirim ke BKN Pusat jenis pensiun
		// if($reqJenis == 12)
		// $statement.= " AND A.JENIS_ID = 7";
		// else
		$statement.= " AND A.JENIS_ID = ".$reqJenis;

		if($reqKategori == ""){}
		else
			$statement.= " AND A.KATEGORI = '".$reqKategori."'";

		$statement.= " AND A.STATUS_KIRIM IS NULL";
		
		// $searchJson = "  AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $set->getCountByParamsPilihUsulan(array(), $statement);
		// echo $allRecord;

		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParamsPilihUsulan(array(), $statement.$searchJson);
		
		$set->selectByParamsPilihUsulan(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
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
				elseif($aColumns[$i] == "TANGGAL_DIBUAT")
					$row[] = getFormattedDateTime($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "TANGGAL_DITERUSKAN")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "TOTAL_PEGAWAI_PROGRES_KEMBALI")
				{
					$row[] = $set->getField("TOTAL_PEGAWAI")." / ".$set->getField("TOTAL_PEGAWAI_PROGRES")." / ".$set->getField("TOTAL_PEGAWAI_KEMBALI");
				}
				else if($aColumns[$i] == "TANGGAL_BATAS")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "AKSI")
				{
					$row[] = '<a href="javascript:void(0)" onclick="pilihdata(\''.$set->getField("USULAN_SURAT_ID").'\')" class="round waves-effect waves-light red white-text" title="Pilih" > Pilih </a>';
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}
	
	function json_teknis() 
	{
		$this->load->model('persuratan/UsulanSurat');

		$set = new UsulanSurat();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
					
		$aColumns = array("NOMOR", "TANGGAL", "TOTAL_PEGAWAI_PROGRES_KEMBALI", "SATUAN_KERJA_ASAL_NAMA", "POSISI_TERAKHIR", "AKSI", "USULAN_SURAT_ID");
		$aColumnsAlias = array("NOMOR", "TANGGAL", "TOTAL_PEGAWAI_PROGRES_KEMBALI", "SATUAN_KERJA_ASAL_NAMA", "POSISI_TERAKHIR", "AKSI", "USULAN_SURAT_ID");

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
			//$set_detil= new UsulanSurat();
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
			$statement= " AND A.USULAN_SURAT_ID IN (SELECT USULAN_SURAT_ID FROM persuratan.USULAN_SURAT_DISPOSISI WHERE SATUAN_KERJA_ASAL_ID = ".$reqSatuanKerjaId." AND TERBACA = 1 GROUP BY USULAN_SURAT_ID)";
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
				else if($aColumns[$i] == "TOTAL_PEGAWAI_PROGRES_KEMBALI")
				{
					$row[] = $set->getField("TOTAL_PEGAWAI")." / ".$set->getField("TOTAL_PEGAWAI_PROGRES")." / ".$set->getField("TOTAL_PEGAWAI_KEMBALI");
				}
				else if($aColumns[$i] == "TANGGAL_BATAS")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "AKSI")
				{
					if($set->getField("STATUS_KIRIM") == "1")
					$row[] = '';
					else
					$row[] = '<a href="javascript:void(0)" onclick="hapusdata(\''.$set->getField("USULAN_SURAT_ID").'\')" class="round waves-effect waves-light red white-text" title="Hapus" ><i class="mdi-action-delete"></i></a>';
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}
	
	function json_surat() 
	{
		$this->load->model('persuratan/UsulanSurat');
		
		$reqJenis=  $this->input->get('reqJenis');
		
		$set = new UsulanSurat();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
			
		$aColumns = array("NO_AGENDA", "TANGGAL", "NOMOR", "PERIHAL", "SATUAN_KERJA_ASAL_NAMA", "POSISI_TERAKHIR", "JENIS_ID", "USULAN_SURAT_ID");
		$aColumnsAlias = array("NO_AGENDA", "TANGGAL", "NOMOR", "PERIHAL", "SATUAN_KERJA_ASAL_NAMA", "POSISI_TERAKHIR", "JENIS_ID", "USULAN_SURAT_ID");

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
			$set_detil= new UsulanSurat();
			$reqSatuanKerjaId= $set_detil->getCountByParamsSatuanKerjaIdSurat();
		}
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}	
		//echo $reqSatuanKerjaId;exit;
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
					$row[] = dateToPageCheck($set->getField($aColumns[$i]));
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
	
	function add()
	{
		$this->load->model('persuratan/UsulanSurat');
		
		$set = new UsulanSurat();

		$reqId = $this->input->post("reqId");
		$reqMode = $this->input->post("reqMode");

		$reqNomor = $this->input->post("reqNomor");
		$reqAgenda = $this->input->post("reqAgenda");
		$reqJenis= $this->input->post("reqJenis");
		$reqTanggal = $this->input->post("reqTanggal");
		$reqTanggalDiteruskan = $this->input->post("reqTanggalDiteruskan");
		$reqTanggalBatas = $this->input->post("reqTanggalBatas");
		$reqKepada = $this->input->post("reqKepada");
		$reqPerihal = $this->input->post("reqPerihal");
		$reqSatuanKerjaTujuanId = $this->input->post("reqSatuanKerjaTujuanId");
		$reqSatuanKerjaAsalId = $this->input->post("reqSatuanKerjaAsalId");
		$reqIdSementara= $this->input->post("reqIdSementara");
		$reqSatuanKerjaTujuanNama= $this->input->post("reqSatuanKerjaTujuanNama");
		
		$reqKategori = $this->input->post("reqKategori");
		
		$set->setField('JENIS_ID', $reqJenis);
		$set->setField('NOMOR', $reqNomor);
		$set->setField('NO_AGENDA', $reqAgenda);
		$set->setField('TANGGAL', dateToDBCheck($reqTanggal));
		$set->setField('TANGGAL_DITERUSKAN', dateToDBCheck($reqTanggalDiteruskan));
		$set->setField('TANGGAL_BATAS', dateToDBCheck($reqTanggalBatas));
		$set->setField('KEPADA', $reqKepada);
		$set->setField('PERIHAL', $reqPerihal);
		$set->setField('ID_SEMENTARA', $reqIdSementara);
		$set->setField('SATUAN_KERJA_TUJUAN_NAMA', $reqSatuanKerjaTujuanNama);
		$set->setField('SATUAN_KERJA_TUJUAN_ID', ValToNullDB($reqSatuanKerjaTujuanId));
		$set->setField('SATUAN_KERJA_ASAL_ID', ValToNullDB($reqSatuanKerjaAsalId));
		
		$set->setField('KATEGORI', $reqKategori);

		if($reqMode == "insert")
		{
			if($set->insert())
			{
				$reqId= $set->id;
				echo $reqId."-Data berhasil disimpan.";
			}
			else
			{
				//echo $set->query;exit;
				echo "xxx-Data gagal disimpan.";
			}
		}
		else
		{
			$set->setField('USULAN_SURAT_ID', $reqId); 
			if($set->update())
			{
				echo $reqId."-Data berhasil disimpan.";
			}
			else
				echo "xxx-Data gagal disimpan.";
		}
		
	}
	
	function statuskirim()
	{
		$this->load->model('persuratan/UsulanSurat');
		$this->load->model('persuratan/SuratMasukPegawai');
		$set = new UsulanSurat();
		
		$reqId =  $this->input->get('reqId');
		$reqJenis=  $this->input->get('reqJenis');
		$reqPerihal=  $this->input->get('reqPerihal');
		$reqStatusBerkas=  $this->input->get('reqStatusBerkas');
		$reqStatusKirim=  $this->input->get('reqStatusKirim');
		
		$statement= " AND A.USULAN_SURAT_ID = ".$reqId;
		$setdata= new UsulanSurat();
		$setdata->selectByParams(array(), -1,-1, $statement);
		$setdata->firstRow();
		$tempPerihalData= $setdata->getField("PERIHAL");

		$statement= " AND SMP.USULAN_SURAT_ID = ".$reqId;
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

		// echo setjenisperihalinfo(setjenisusulan($reqJenis));exit();
		if(setjenisusulan($reqJenis) == 7)
		{
			$tempPerihalInfo= $tempPerihalData." a.n ".$tempNamaSaja;
		}
		else
		$tempPerihalInfo= setjenisperihalinfo(setjenisusulan($reqJenis))." a.n ".$tempNamaSaja;

		if($tempJumlahDataUsulan == 1){}
		else
		$tempPerihalInfo= $tempPerihalInfo." dkk";
		// echo $tempPerihalInfo;exit;
		
		$set->setField("USULAN_SURAT_ID", $reqId);
		$set->setField("PERIHAL", setQuote($tempPerihalInfo));
		$set->setField("STATUS_KIRIM", ValToNullDB($reqStatusKirim));
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("LAST_DATE", "NOW()");
		
		if($set->updatePerihalKirim())
		{
			/*$set_detil= new SuratMasukPegawai();
			$set_detil->setField("USULAN_SURAT_ID", $reqId);
			$set_detil->setField("STATUS_BERKAS", $reqStatusBerkas);
			$set_detil->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
			$set_detil->setField("LAST_USER", $this->LOGIN_USER);
			$set_detil->setField("LAST_DATE", "NOW()");
			$set_detil->updateStatusBerkasBkd();*/
			
			if($reqStatusKirim == "")
			$arrJson["PESAN"] = "Data berhasil di batalkan.";
			else
			$arrJson["PESAN"] = "Data berhasil di kirim.";
		}
		else
			$arrJson["PESAN"] = "Data gagal di kirim.";		
		// echo $set->query;exit;
		echo json_encode($arrJson);
	}

	function statusdiambil()
	{
		$this->load->model('persuratan/UsulanSurat');
		$set = new UsulanSurat();
		
		$reqId =  $this->input->get('reqId');
		
		$set->setField("SURAT_MASUK_PEGAWAI_ID", $reqId);
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		
		if($set->updateDiambil())
		{
			$arrJson["PESAN"] = "Data berhasil di update.";
		}
		else
			$arrJson["PESAN"] = "Data gagal di update.";
		// echo $set->query;exit;
		echo json_encode($arrJson);
	}
	
	function deletesurat()
	{
		$this->load->model('persuratan/UsulanSurat');
		$set = new UsulanSurat();
		
		$reqId =  $this->input->get('reqId');
		$set->setField("USULAN_SURAT_ID", $reqId);
		$set->setField("STATUS_BERKAS", "10");
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("LAST_DATE", "NOW()");
		
		if($set->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";		
		
		echo json_encode($arrJson);
	}
	
	function delete()
	{
		$this->load->model('UsulanSurat');
		$set = new UsulanSurat();
		
		$reqId =  $this->input->get('reqId');
		$set->setField("PEGAWAI_ID", $reqId);
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
		$set->setField("SURAT_MASUK_PEGAWAI_ID", $reqId);
		$set->setField("USULAN_SURAT_ID", ValToNullDB($req));
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("LAST_DATE", "NOW()");
		if($set->updateUsulanSurat())
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
	
	function set_info_pegawai_data()
	{
		$this->load->model('persuratan/UsulanSurat');
		$set= new UsulanSurat();
		
		$reqId= $this->input->get('reqId');
		
		$statement= " AND A.USULAN_SURAT_ID = ".$reqId."";
		$set= new UsulanSurat();
		$set->selectByParams(array(), -1, -1, $statement);
		$set->firstRow();
		$reqIdSementara= $set->getField("ID_SEMENTARA");
		$reqNomor= $set->getField("NOMOR");
		$reqTanggal= dateToPageCheck($set->getField("TANGGAL"));
		
		$tempInfo='
			<div class="row">
				<div class="col s3 m2">Id Sementara</div>
				<div class="col s9 m10">'.$reqIdSementara.'</div>
			</div>
			<div class="row">
				<div class="col s3 m2">Nomor</div>
				<div class="col s9 m10">'.$reqNomor.'</div>
			</div>
			<div class="row">
				<div class="col s3 m2">Tanggal</div>
				<div class="col s9 m10">'.$reqTanggal.'</div>
			</div>
		';
		echo $tempInfo;
	}
		
}
?>