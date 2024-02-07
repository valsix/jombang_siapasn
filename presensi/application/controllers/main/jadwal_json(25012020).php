<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/image.func.php");

class jadwal_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// trow to unauthenticated page!
			//redirect('Login');
		}		
		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");  
		$this->ID = $this->kauth->getInstance()->getIdentity()->ID;   		
		$this->NAMA = $this->kauth->getInstance()->getIdentity()->NAMA;   
		$this->USERNAME = $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->KODE_CABANG = $this->kauth->getInstance()->getIdentity()->KODE_CABANG;
		$this->CABANG = $this->kauth->getInstance()->getIdentity()->CABANG;
		$this->DEPARTEMEN = $this->kauth->getInstance()->getIdentity()->DEPARTEMEN;
		$this->SUB_DEPARTEMEN = $this->kauth->getInstance()->getIdentity()->SUB_DEPARTEMEN;
		$this->JABATAN = $this->kauth->getInstance()->getIdentity()->JABATAN;

		$this->PERSONAL_TOKEN= $this->kauth->getInstance()->getIdentity()->PERSONAL_TOKEN;
	}	

	function jamkerja()
	{
		$this->load->model('main/KerjaJam');

		$set= new KerjaJam;
		
		$reqJenis= $this->input->get("reqJenis");
		$reqStatus= $this->input->get("reqStatus");
		$reqStatusRamadhan= $this->input->get("reqStatusRamadhan");
		$reqHariKhusus= $this->input->get("reqHariKhusus");
		$reqPencarian= $this->input->get("reqPencarian");

		$aColumns = array("NAMA_JAM_KERJA", "JENIS_JAM_KERJA_INFO", "STATUS_JAM_KERJA_INFO", "HARI_KHUSUS_INFO", "MASUK_NORMAL", "MULAI_MASUK_NORMAL", "AKHIR_MASUK_NORMAL", "KELUAR_NORMAL", "MULAI_KELUAR_NORMAL", "AKHIR_KELUAR_NORMAL", "STATUS_ASK_NORMAL", "AKHIR_ASK_NORMAL", "KERJA_JAM_ID");
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
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY NAMA_JAM_KERJA asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.JENIS_JAM_KERJA, A.NAMA_JAM_KERJA DESC";
				 
			}
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

		if(empty($reqJenis)){}
		else if(!empty($reqJenis) && $reqJenis !== "xxx")
			$statement.= " AND A.JENIS_JAM_KERJA= '".$reqJenis."'";
		else
			$statement.= " AND COALESCE(NULLIF(A.JENIS_JAM_KERJA, ''), NULL) IS NULL";

		if(empty($reqStatus)){}
		else if(!empty($reqStatus) && $reqStatus !== "xxx")
			$statement.= " AND A.STATUS_JAM_KERJA= '".$reqStatus."'";
		else
			$statement.= " AND COALESCE(NULLIF(A.STATUS_JAM_KERJA, ''), NULL) IS NULL";

		if(empty($reqStatusRamadhan)){}
		else if(!empty($reqStatusRamadhan) && $reqStatusRamadhan !== "xxx")
			$statement.= " AND A.STATUS_JAM_KERJA_RAMADHAN= '".$reqStatusRamadhan."'";
		else
			$statement.= " AND COALESCE(NULLIF(A.STATUS_JAM_KERJA_RAMADHAN, ''), NULL) IS NULL";

		if(empty($reqHariKhusus)){}
		else if(!empty($reqHariKhusus) && $reqHariKhusus !== "xxx")
			$statement.= " AND A.HARI_KHUSUS= '".$reqHariKhusus."'";
		else
			$statement.= " AND COALESCE(NULLIF(A.HARI_KHUSUS, ''), NULL) IS NULL";

		$searchJson = " AND ( UPPER(A.NAMA_JAM_KERJA) LIKE '%".strtoupper($reqPencarian)."%' )";
		$allRecord = $set->getCountByParams(array(), $statement);
		//echo $allRecord;
		if($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParams(array(), $searchJson.$statement);
		
		$set->selectByParams(array(), $dsplyRange, $dsplyStart, $searchJson.$statement, $sOrder);
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
				if($aColumns[$i] == "STATUS_JAM_KERJA_INFO")
				{
					$row[] = "N : ".$set->getField($aColumns[$i])."<br/>R : ".$set->getField("STATUS_JAM_KERJA_RAMADHAN_INFO");
				}
				elseif($aColumns[$i] == "MASUK_NORMAL")
				{
					$row[] = "N : ".$set->getField($aColumns[$i])."<br/>R : ".$set->getField("MASUK_RAMADHAN");
				}
				elseif($aColumns[$i] == "MULAI_MASUK_NORMAL")
				{
					$row[] = "N : ".$set->getField($aColumns[$i])."<br/>R : ".$set->getField("MULAI_MASUK_RAMADHAN");
				}
				elseif($aColumns[$i] == "AKHIR_MASUK_NORMAL")
				{
					$row[] = "N : ".$set->getField($aColumns[$i])."<br/>R : ".$set->getField("AKHIR_MASUK_RAMADHAN");
				}

				elseif($aColumns[$i] == "KELUAR_NORMAL")
				{
					$row[] = "N : ".$set->getField($aColumns[$i])."<br/>R : ".$set->getField("KELUAR_RAMADHAN");
				}
				elseif($aColumns[$i] == "MULAI_KELUAR_NORMAL")
				{
					$row[] = "N : ".$set->getField($aColumns[$i])."<br/>R : ".$set->getField("MULAI_KELUAR_RAMADHAN");
				}
				elseif($aColumns[$i] == "AKHIR_KELUAR_NORMAL")
				{
					$row[] = "N : ".$set->getField($aColumns[$i])."<br/>R : ".$set->getField("AKHIR_KELUAR_RAMADHAN");
				}
				elseif($aColumns[$i] == "TANGGAL_MULAI")
				{
					$row[] = dateToPageCheck($set->getField($aColumns[$i]));
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}

	function kerjajam_add()
	{
		$this->load->model('main/KerjaJam');

		$reqId= $this->input->post('reqId');
		$reqMode= $this->input->post('reqMode');

		$reqNamaJamKerja= $this->input->post('reqNamaJamKerja');
		$reqJenisJamKerja= $this->input->post('reqJenisJamKerja');
		$reqHariKhusus= $this->input->post('reqHariKhusus');
		$reqMulaiBerlaku= $this->input->post('reqMulaiBerlaku');
		$reqAkhirBerlaku= $this->input->post('reqAkhirBerlaku');
		
		$reqStatusJamKerja= $this->input->post('reqStatusJamKerja');
		$reqMasukNormal= $this->input->post('reqMasukNormal');
		$reqMulaiMasukNormal= $this->input->post('reqMulaiMasukNormal');
		$reqAkhirMasukNormal= $this->input->post('reqAkhirMasukNormal');
		$reqKeluarNormal= $this->input->post('reqKeluarNormal');
		$reqMulaiKeluarNormal= $this->input->post('reqMulaiKeluarNormal');
		$reqAkhirKeluarNormal= $this->input->post('reqAkhirKeluarNormal');
		$reqKeluarGantiHariNormal= $this->input->post('reqKeluarGantiHariNormal');
		$reqAkhirAskNormal= $this->input->post('reqAkhirAskNormal');
		$reqStatusCekNormal= $this->input->post('reqStatusCekNormal');
		$reqAwalCekNormal= $this->input->post('reqAwalCekNormal');
		$reqAkhirCekNormal= $this->input->post('reqAkhirCekNormal');
		$reqAskNormal= $this->input->post('reqAskNormal');

		$reqMasukRamadhan= $this->input->post('reqMasukRamadhan');
		$reqMulaiMasukRamadhan= $this->input->post('reqMulaiMasukRamadhan');
		$reqAkhirMasukRamadhan= $this->input->post('reqAkhirMasukRamadhan');
		$reqKeluarRamadhan= $this->input->post('reqKeluarRamadhan');
		$reqMulaiKeluarRamadhan= $this->input->post('reqMulaiKeluarRamadhan');
		$reqAkhirKeluarRamadhan= $this->input->post('reqAkhirKeluarRamadhan');
		$reqKeluarGantiHariRamadhan= $this->input->post('reqKeluarGantiHariRamadhan');
		$reqStatusJamKerjaRamadhan= $this->input->post('reqStatusJamKerjaRamadhan');
		$reqAkhirAskRamadhan= $this->input->post('reqAkhirAskRamadhan');
		$reqStatusCekRamadhan= $this->input->post('reqStatusCekRamadhan');
		$reqAwalCekRamadhan= $this->input->post('reqAwalCekRamadhan');
		$reqAkhirCekRamadhan= $this->input->post('reqAkhirCekRamadhan');
		$reqAskRamadhan= $this->input->post('reqAskRamadhan');

		$set= new KerjaJam();
        $set->setField("KERJA_JAM_ID", $reqId);
        $set->setField("NAMA_JAM_KERJA", $reqNamaJamKerja);
        $set->setField("JENIS_JAM_KERJA", $reqJenisJamKerja);
        $set->setField("HARI_KHUSUS", $reqHariKhusus);
        $set->setField("MULAI_BERLAKU", dateTimeToDBCheck($reqMulaiBerlaku));
        $set->setField("AKHIR_BERLAKU", dateTimeToDBCheck($reqAkhirBerlaku));
        $set->setField("STATUS_JAM_KERJA", $reqStatusJamKerja);
        $set->setField("MASUK_NORMAL", $reqMasukNormal);
        $set->setField("MULAI_MASUK_NORMAL", $reqMulaiMasukNormal);
        $set->setField("AKHIR_MASUK_NORMAL", $reqAkhirMasukNormal);
        $set->setField("KELUAR_NORMAL", $reqKeluarNormal);
        $set->setField("MULAI_KELUAR_NORMAL", $reqMulaiKeluarNormal);
        $set->setField("AKHIR_KELUAR_NORMAL", $reqAkhirKeluarNormal);
        $set->setField("KELUAR_GANTI_HARI_NORMAL", $reqKeluarGantiHariNormal);
        $set->setField("STATUS_ASK_NORMAL", $reqAskNormal);
        $set->setField("AKHIR_ASK_NORMAL", $reqAkhirAskNormal);
        $set->setField("STATUS_CEK_NORMAL", $reqStatusCekNormal);
        $set->setField("AWAL_CEK_NORMAL", $reqAwalCekNormal);
        $set->setField("AKHIR_CEK_NORMAL", $reqAkhirCekNormal);
        $set->setField("MASUK_RAMADHAN", $reqMasukRamadhan);
        $set->setField("MULAI_MASUK_RAMADHAN", $reqMulaiMasukRamadhan);
        $set->setField("AKHIR_MASUK_RAMADHAN", $reqAkhirMasukRamadhan);
        $set->setField("KELUAR_RAMADHAN", $reqKeluarRamadhan);
        $set->setField("MULAI_KELUAR_RAMADHAN", $reqMulaiKeluarRamadhan);
        $set->setField("AKHIR_KELUAR_RAMADHAN", $reqAkhirKeluarRamadhan);
        $set->setField("KELUAR_GANTI_HARI_RAMADHAN", $reqKeluarGantiHariRamadhan);
        $set->setField("STATUS_ASK_RAMADHAN", $reqAskRamadhan);
        $set->setField("AKHIR_ASK_RAMADHAN", $reqAkhirAskRamadhan);
        $set->setField("STATUS_CEK_RAMADHAN", $reqStatusCekRamadhan);
        $set->setField("AWAL_CEK_RAMADHAN", $reqAwalCekRamadhan);
        $set->setField("AKHIR_CEK_RAMADHAN", $reqAkhirCekRamadhan);
        $set->setField("STATUS_JAM_KERJA_RAMADHAN", $reqStatusJamKerjaRamadhan);

        $set->setField("LAST_USER", $this->USERNAME);
        $set->setField("LAST_DATE", "CURRENT_TIMESTAMP");

		$simpan="";
		if($reqMode == "insert")
        {
        	$set->setField("LAST_CREATE_USER", $this->USERNAME);
        	$set->setField("LAST_CREATE_DATE", "CURRENT_TIMESTAMP");
            if($set->insert())
            {
            	$reqId= $set->id;
            	$simpan = "1";
            }
        }
        else
        {
        	$set->setField("LAST_UPDATE_USER", $this->USERNAME);
        	$set->setField("LAST_UPDATE_DATE", "CURRENT_TIMESTAMP");
        	if($set->update())
            {
            	$simpan = "1";
            }
        }

		if($simpan == "1")
			echo $reqId."-Data berhasil disimpan.";
		else
			echo "xxx-Data gagal disimpan.";
	}

	function jamkerjapegawai()
	{
		$this->load->model('main/KerjaJam');
		$this->load->model('main/SatuanKerja');

		$set= new KerjaJam;

		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqJenis= $this->input->get("reqJenis");
		$reqStatus= $this->input->get("reqStatus");
		$reqPencarian= $this->input->get("reqPencarian");

		$aColumns = array("NIP_BARU", "NIP_BARU_NAMA_LENGKAP", "JENIS_JAM_KERJA_INFO", "STATUS_PEGAWAI_INFO", "JABATAN_RIWAYAT_NAMA", "SATUAN_KERJA_INFO", "PEGAWAI_ID");
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
				$sOrder = " ORDER BY A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.PANGKAT_RIWAYAT_TMT ASC";
				 
			}
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
				AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
		}
		else
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
			
			$skerja= new SatuanKerja();
			$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
			// echo $skerja->query;exit();
			unset($skerja);
			// echo $reqSatuanKerjaId;exit;
			$satuankerjakondisi.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}

		if(empty($reqJenis)){}
		else
		{
			if($reqJenis == "normal_5_hari")
				$statement.= " AND (B.JENIS_JAM_KERJA= 'normal_5_hari' OR COALESCE(NULLIF(B.JENIS_JAM_KERJA, ''), NULL) IS NULL)";
			else
				$statement.= " AND B.JENIS_JAM_KERJA= '".$reqJenis."'";
		}

		if(empty($reqStatus)){}
		else if(!empty($reqStatus) && $reqStatus !== "xxx")
			$statement.= " AND A.STATUS_PEGAWAI_ID NOT IN (1,2)";
		else
			$statement.= " AND A.STATUS_PEGAWAI_ID IN (1,2)";

		if(!empty($satuankerjakondisi))
		{
			$statement.= $satuankerjakondisi;
		}

		$searchJson = " AND ( UPPER(A.NIP_BARU) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%' )";
		$allRecord = $set->getCountByParamsJamPegawai(array(), $statement);
		//echo $allRecord;
		if($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsJamPegawai(array(), $searchJson.$statement);
		
		$set->selectByParamsJamPegawai(array(), $dsplyRange, $dsplyStart, $searchJson.$statement, $sOrder);
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

	function jamkerjapegawaiadd()
	{
		$this->load->model('main/KerjaJam');

		$reqJenisJamKerja= $this->input->get("reqJenisJamKerja");
		$arrPegawaiId= $this->input->get("reqPegawaiId");

		$arrPegawaiId= explode(",", $arrPegawaiId);
		// print_r($arrPegawaiId);exit;

		foreach ($arrPegawaiId as $pegawaiid) 
		{
			$set= new KerjaJam;
			$set->selectByParamsJamPegawaiData(array("A.PEGAWAI_ID"=>$pegawaiid));
			$set->firstRow();
			$mode= "insert";
			if(!empty($set->getField("PEGAWAI_ID")))
				$mode= "update";

			$set->setField("PEGAWAI_ID", $pegawaiid);
			$set->setField("JENIS_JAM_KERJA", $reqJenisJamKerja);
			$set->setField("LAST_USER", $this->USERNAME);
	        $set->setField("LAST_DATE", "CURRENT_TIMESTAMP");

			$simpan="";
			if($mode == "insert")
	        {
	        	$set->setField("LAST_CREATE_USER", $this->USERNAME);
	        	$set->setField("LAST_CREATE_DATE", "CURRENT_TIMESTAMP");
	            if($set->insertpegawai())
	            {
	            	$simpan = "1";
	            }
	        }
	        else
	        {
	        	$set->setField("LAST_UPDATE_USER", $this->USERNAME);
	        	$set->setField("LAST_UPDATE_DATE", "CURRENT_TIMESTAMP");
	        	if($set->updatepegawai())
	            {
	            	$simpan = "1";
	            }
	        }

		}

		echo json_encode( "1" );
	}

	function jamkerjaawfhpegawai()
	{
		$this->load->model('main/KerjaJam');
		$this->load->model('main/SatuanKerja');

		$set= new KerjaJam;

		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqJenis= $this->input->get("reqJenis");
		$reqStatus= $this->input->get("reqStatus");
		$reqPencarian= $this->input->get("reqPencarian");

		$aColumns = array("NIP_BARU", "NIP_BARU_NAMA_LENGKAP", "JENIS_JAM_KERJA_INFO", "STATUS_PEGAWAI_INFO", "JABATAN_RIWAYAT_NAMA", "SATUAN_KERJA_INFO", "PEGAWAI_ID");
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
				$sOrder = " ORDER BY A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.PANGKAT_RIWAYAT_TMT ASC";
				 
			}
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
				AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
		}
		else
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
			
			$skerja= new SatuanKerja();
			$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
			// echo $skerja->query;exit();
			unset($skerja);
			// echo $reqSatuanKerjaId;exit;
			$satuankerjakondisi.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}

		if(empty($reqJenis)){}
		else
		{
			if($reqJenis == "normal_5_hari")
				$statement.= " AND (B.JENIS_JAM_KERJA= 'normal_5_hari' OR COALESCE(NULLIF(B.JENIS_JAM_KERJA, ''), NULL) IS NULL)";
			else
				$statement.= " AND B.JENIS_JAM_KERJA= '".$reqJenis."'";
		}

		if(empty($reqStatus)){}
		else if(!empty($reqStatus) && $reqStatus !== "xxx")
			$statement.= " AND A.STATUS_PEGAWAI_ID NOT IN (1,2)";
		else
			$statement.= " AND A.STATUS_PEGAWAI_ID IN (1,2)";

		if(!empty($satuankerjakondisi))
		{
			$statement.= $satuankerjakondisi;
		}

		$searchJson = " AND ( UPPER(A.NIP_BARU) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%' )";
		$allRecord = $set->getCountByParamsJamPegawai(array(), $statement);
		//echo $allRecord;
		if($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsJamPegawai(array(), $searchJson.$statement);
		
		$set->selectByParamsJamPegawai(array(), $dsplyRange, $dsplyStart, $searchJson.$statement, $sOrder);
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

	function jamkerjaawfhpegawaiadd()
	{
		$this->load->model('main/KerjaJam');

		$reqJenisJamKerja= $this->input->get("reqJenisJamKerja");
		$arrPegawaiId= $this->input->get("reqPegawaiId");

		$arrPegawaiId= explode(",", $arrPegawaiId);
		// print_r($arrPegawaiId);exit;

		foreach ($arrPegawaiId as $pegawaiid) 
		{
			$set= new KerjaJam;
			$set->selectByParamsJamPegawaiData(array("A.PEGAWAI_ID"=>$pegawaiid));
			$set->firstRow();
			$mode= "insert";
			if(!empty($set->getField("PEGAWAI_ID")))
				$mode= "update";

			$set->setField("PEGAWAI_ID", $pegawaiid);
			$set->setField("JENIS_JAM_KERJA", $reqJenisJamKerja);
			$set->setField("LAST_USER", $this->USERNAME);
	        $set->setField("LAST_DATE", "CURRENT_TIMESTAMP");

			$simpan="";
			if($mode == "insert")
	        {
	        	$set->setField("LAST_CREATE_USER", $this->USERNAME);
	        	$set->setField("LAST_CREATE_DATE", "CURRENT_TIMESTAMP");
	            if($set->insertpegawai())
	            {
	            	$simpan = "1";
	            }
	        }
	        else
	        {
	        	$set->setField("LAST_UPDATE_USER", $this->USERNAME);
	        	$set->setField("LAST_UPDATE_DATE", "CURRENT_TIMESTAMP");
	        	if($set->updatepegawai())
	            {
	            	$simpan = "1";
	            }
	        }

		}

		echo json_encode( "1" );
	}

}