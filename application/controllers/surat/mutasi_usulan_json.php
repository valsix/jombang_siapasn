<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class mutasi_usulan_json extends CI_Controller {

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
		$this->SATUAN_KERJA_BKD_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_BKD_ID;
		$this->STATUS_KELOMPOK_PEGAWAI_USUL= $this->kauth->getInstance()->getIdentity()->STATUS_KELOMPOK_PEGAWAI_USUL;
		$this->STATUS_MENU_KHUSUS= $this->kauth->getInstance()->getIdentity()->STATUS_MENU_KHUSUS;
		$this->LOGIN_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_ID;
		$this->LOGIN_PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID;
	}	
	
	function json() 
	{
		$this->load->model('persuratan/MutasiUsulan');

		$set = new MutasiUsulan();
		$reqStatusUsulan= $this->input->get("reqStatusUsulan");
		$reqJenisMutasiId= $this->input->get("reqJenisMutasiId");

		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
					
		$aColumns = array("NIP_BARU_LAMA", "NAMA_LENGKAP", "SATKER_ASAL_NAMA", "SATKER_NAMA", "JENIS_MUTASI_JABATAN", "TMT", "STATUS_INFO", "AKSI", "ROW_ID_VAL", "INFO_URL", "ID_VAL");
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
				$sOrder = " ORDER BY A.TMT DESC ";
				 
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
		// if($this->LOGIN_LEVEL == 99)
		// {
		// 	$set_detil= new SuratMasukBkd();
		// 	$reqSatuanKerjaId= $set_detil->getCountByParamsSatuanKerjaIdSurat();
		// }
		
		// if($reqSatuanKerjaId == "")
		// {
		// 	$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		// }
		
		// if($reqSatuanKerjaId == ""){}
		// else
		// {
		// 	$statement= " AND A.SATUAN_KERJA_ASAL_ID = ".$reqSatuanKerjaId;
		// }
		
		if($reqStatusUsulan == ""){}
		elseif($reqStatusUsulan == "x")
		{
			$statement.= " AND COALESCE(NULLIF(A.STATUS_USULAN, ''), NULL) IS NULL";
		}
		else
		{
			$statement.= " AND A.STATUS_USULAN = '".$reqStatusUsulan."'";
		}

		if($reqJenisMutasiId == ""){}
		else
		{
			$statement.= " AND A.JENIS_MUTASI_ID = ".$reqJenisMutasiId;
		}

		if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1 || $this->SATUAN_KERJA_ID == ""){}
		else
		{
			// untuk user bukan teknis ataupun admin
			$statement.= "
			AND 
			(
				( A.SATKER_ID = ANY( AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$this->SATUAN_KERJA_ID.") ) OR A.SATKER_ID = ".$this->SATUAN_KERJA_ID." )
				OR
				( A.SATKER_ASAL_ID = ANY( AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$this->SATUAN_KERJA_ID.") ) OR A.SATKER_ASAL_ID = ".$this->SATUAN_KERJA_ID." )
			)";
		}
		
		$searchJson = "  AND (UPPER(P.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(P.NIP_LAMA) LIKE '%".strtoupper($_GET['sSearch'])."%'OR UPPER(P.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $set->getCountByParamsMonitoring(array(), $statement);
		// echo $allRecord;

		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParamsMonitoring(array(), $statement.$searchJson);
		//echo $set->query;exit;
		
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
			$row = array();
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				if($aColumns[$i] == "TANGGAL")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "TMT")
					$row[] = dateTimeToPageCheck($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "NIP_BARU_LAMA")
					$row[] = $set->getField("NIP_BARU")."<br/>".$set->getField("NIP_LAMA");
				else if($aColumns[$i] == "TOTAL_PEGAWAI_PROGRES_KEMBALI")
				{
					$tempValue= $set->getField("TOTAL_PEGAWAI")." / ".$set->getField("TOTAL_PEGAWAI_PROGRES")." / ".$set->getField("TOTAL_PEGAWAI_KEMBALI");
					if($set->getField("TOTAL_PEGAWAI_KEMBALI") > 0)
					$tempValue= $set->getField("TOTAL_PEGAWAI")." / ".$set->getField("TOTAL_PEGAWAI_PROGRES")." / <label style='color:red; font-size:10px'>".$set->getField("TOTAL_PEGAWAI_KEMBALI")."</label>";
					$row[] = $tempValue;
				}
				else if($aColumns[$i] == "AKSI")
				{
					if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1 || $this->SATUAN_KERJA_ID == "")
					{
						if($set->getField("STATUS_USULAN") == "2" || $set->getField("STATUS_USULAN") == "")
						$row[] = '<a href="javascript:void(0)" onclick="hapusdata(\''.$set->getField("ID_VAL").'\', \''.$set->getField("JENIS_JABATAN_TUGAS_ID").'\')" class="round waves-effect waves-light red white-text" title="Hapus" ><i class="mdi-action-delete"></i></a>';
						else
							$row[] = '';
					}
					else
					{
						if($set->getField("STATUS_USULAN") == "")
						$row[] = '<a href="javascript:void(0)" onclick="hapusdata(\''.$set->getField("ID_VAL").'\', \''.$set->getField("JENIS_JABATAN_TUGAS_ID").'\')" class="round waves-effect waves-light red white-text" title="Hapus" ><i class="mdi-action-delete"></i></a>';
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

	function cari_pegawai_usulan()
	{
		$this->load->model('persuratan/MutasiUsulan');

		$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
		if(empty($search_term))
		{
			$search_term = isset($_GET['term']) ? $_GET['term'] : "";
		}
		
		$reqId= $this->input->get("reqId");
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
        $reqMode= $this->input->get("reqMode");
        $reqTipePegawaiId= $this->input->get("reqTipePegawaiId");
		
		$set= new MutasiUsulan();
		// $statement= " AND A.STATUS IS NULL";
		$statement.= " AND UPPER(A.NIP_BARU) LIKE '%".strtoupper(str_replace(" ", "", $search_term))."%' ";
		
		// if($reqSatuanKerjaId == "")
		// {
		// 	$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		// }
		
		if($reqSatuanKerjaId == ""){}
		else
		{
			$skerja= new MutasiUsulan();
			$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
			unset($skerja);
			//echo $reqSatuanKerjaId;exit;
			$statement.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}

		// CARI PEGAWAI YG AKTIF
		$statement.= " AND A.STATUS_PEGAWAI_ID IN (1,2)";

		// CEK TIDAK BOLEH PEGAWAI YG SDH PROSES
		$statement.= " 
		AND NOT EXISTS
		(
		SELECT 1
		FROM
		(
			SELECT 
			A.PEGAWAI_ID, A.STATUS_USULAN
			FROM persuratan.US_JABATAN_RIWAYAT A
			UNION ALL
			SELECT 
			A.PEGAWAI_ID, A.STATUS_USULAN
			FROM persuratan.US_JABATAN_TAMBAHAN A
			UNION ALL
			SELECT 
			A.PEGAWAI_ID, A.STATUS_USULAN
			FROM persuratan.US_JABATAN_MUTASI_INTERN A
		) USM
		WHERE COALESCE(NULLIF(USM.STATUS_USULAN, ''), NULL) IS NULL
		AND A.PEGAWAI_ID = USM.PEGAWAI_ID
		)
		";

		if($reqTipePegawaiId == ""){}
		else
		{
			$statement.= " AND A.TIPE_PEGAWAI_ID = '".$reqTipePegawaiId."'";
		}
		
		$set->selectByParamsPegawaiCari(array(), 10, 0, $statement);
		// echo $set->query;exit;
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("PEGAWAI_ID");
			$arr_json[$i]['label'] = $set->getField("NIP_BARU");
			$arr_json[$i]['desc'] = $set->getField("NIP_BARU")."<br/><label style='font-size:12px'>".$set->getField("NAMA_LENGKAP")."</label>";
			$arr_json[$i]['satuankerjaid'] = $set->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['satuankerjanama'] = $set->getField("SATUAN_KERJA_NAMA");
			$arr_json[$i]['namapegawai'] = $set->getField("NAMA_LENGKAP");
			$i++;
		}
		echo json_encode($arr_json);
	}

	function add_usulan_jabatan_riwayat()
	{
		$this->load->model('persuratan/UsJabatanRiwayat');
		$this->load->model('persuratan/MutasiUsulan');
		$this->load->library("FileHandler"); 

		$file = new FileHandler();

		$set = new UsJabatanRiwayat();
		
		$reqId= $this->input->post("reqId");
		$reqMode= $this->input->post("reqMode");
		$reqRowId= $this->input->post('reqRowId');

		$reqJenisJabatan= $this->input->post("reqJenisJabatan");
		$reqIsManual= $this->input->post("reqIsManual");
		$reqJabatanFuId= $this->input->post("reqJabatanFuId");
		$reqJabatanFtId= $this->input->post("reqJabatanFtId");
		$reqTipePegawaiId= $this->input->post("reqTipePegawaiId");
		$reqNoSk= $this->input->post("reqNoSk");
		$reqTglSk= $this->input->post("reqTglSk");
		$reqNama= $this->input->post("reqNama");
		$reqTmtJabatan= $this->input->post("reqTmtJabatan");
		$reqTmtWaktuJabatan= $this->input->post("reqTmtWaktuJabatan");
		$reqTmtEselon= $this->input->post("reqTmtEselon");
		$reqEselonId= $this->input->post("reqEselonId");
		$reqNama= $this->input->post("reqNama");
		$reqTmtEselon= $this->input->post("reqTmtEselon");
		$reqKeteranganBUP= $this->input->post("reqKeteranganBUP");
		$reqNoPelantikan= $this->input->post("reqNoPelantikan");    
		$reqTglPelantikan= $this->input->post("reqTglPelantikan");
		$reqTunjangan= $this->input->post("reqTunjangan");
		$reqBlnDibayar= $this->input->post("reqBlnDibayar");
		$reqSatkerId= $this->input->post("reqSatkerId");
		$reqSatker= $this->input->post("reqSatker");
		$reqKredit= $this->input->post("reqKredit");
		//echo $reqSatker;exit;
		$reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
		$reqPejabatPenetapId= $this->input->post("reqPejabatPenetapId");

		$reqPegawaiId= $this->input->post("reqPegawaiId");
		$reqSatkerAsalId= $this->input->post("reqSatkerAsalId");
		$reqJenisMutasiId= $this->input->post("reqJenisMutasiId");
		$reqJenisJabatanTugasId= $this->input->post("reqJenisJabatanTugasId");

		$reqLinkFile= $_FILES['reqLinkFile'];

		// Allow certain file formats
		$bolehupload = "";
		$fileuploadexe= strtolower(getExtension($reqLinkFile['name']));

		if($reqJenisJabatanTugasId == "11")
			$target_dir= "uploadsurat/mutasi_struktural/";
		elseif($reqJenisJabatanTugasId == "12")
			$target_dir= "uploadsurat/mutasi_fungsional/";

		if($reqJenisJabatanTugasId == "12")
		{
			$this->load->model('JabatanFu');
			$statement= " AND A.NAMA = '".$reqNama."'";
			$set_detil= new JabatanFu();
			$set_detil->selectByParams(array(), -1,-1, $statement);
			// echo $set_detil->query;exit();
			$set_detil->firstRow();
			$reqJabatanFuId= $set_detil->getField("JABATAN_FU_ID");
			unset($set_detil);

			if($reqJabatanFuId == "")
			{
				echo "xxx-Nama Jabatan (".$reqNama.") tidak ada dalam sistem, hubungi admin untuk menambahkan data nama jabatan.";
				exit();
			}
		}

		if($fileuploadexe == "")
		{
			if(!empty($reqId))
			{
				$tempUrlFile= $target_dir.$reqId.".pdf";
				if(file_exists($tempUrlFile))
				{
					$bolehupload = 1;
				}
				else
					$bolehupload = 2;
			}
			else
				$bolehupload = 2;
		}
		else
		{
			// $arrFileBolehUpload= array("png", "jpeg", "jpg", "pdf");
			$arrFileBolehUpload= array("pdf");
			// if($fileuploadexe == "pdf")
			if (in_array($fileuploadexe, $arrFileBolehUpload, true))
				$bolehupload = 1;
		}

		// echo $bolehupload;exit();
		if($bolehupload == "")
		{
			// echo "xxx-Data gagal disimpan, check file upload harus format pdf, png, jpeg, jpg.";
			echo "xxx-Data gagal disimpan, check file upload harus format pdf.";
		}
		else if($bolehupload == "2")
		{
			echo "xxx-Data gagal disimpan, file upload harus diisi.";
		}
		else
		{
			if(file_exists($target_dir)){}
			else
			{
				makedirs($target_dir);
			}

			//kalau pejabat tidak ada
			if($reqPejabatPenetapId == "")
			{
				$set_pejabat=new MutasiUsulan();
				$set_pejabat->setField('NAMA', strtoupper($reqPejabatPenetap));
				$set_pejabat->setField("LAST_USER", $this->LOGIN_USER);
				$set_pejabat->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				$set_pejabat->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
				$set_pejabat->setField("LAST_DATE", "NOW()");
				$set_pejabat->insertPejabat();
				// echo $set_pejabat->query;exit();
				$reqPejabatPenetapId=$set_pejabat->id;
				unset($set_pejabat);
			}
			
			$kondisilihatsatuankerja= "";
			$reqSatkerPembuatId= $this->SATUAN_KERJA_ID;
			// kondisi bukan untuk admin n teknis
			if($reqSatkerPembuatId == ""){}
			else
			{
				if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1 || $this->SATUAN_KERJA_ID == "")
				{}
				else
				{
					$skerja= new MutasiUsulan();
					$dataSatkerPembuatId= $skerja->getSatuanKerja($reqSatkerPembuatId);
					// echo $skerja->query;exit();
					$arrSatkerPembuatId= explode(",", $dataSatkerPembuatId);
					unset($skerja);
					// print_r($arrSatkerPembuatId);exit();
					
					if (in_array($reqSatkerAsalId, $arrSatkerPembuatId, true) && $this->LOGIN_LEVEL >= 20) {}
					else
					{
						// kalau 1 maka pegawai dari satuan kerja lain, maka set satuan kerja sesuai dengan satuankerja pembuat ke bawah
						$kondisilihatsatuankerja= "1";
					}
				}
			}
			// 735
			// echo $reqSatkerAsalId."--".$reqSatkerPembuatId."--".$kondisilihatsatuankerja."--".$this->LOGIN_LEVEL;
			// exit();

			$reqStatusUsulan= "";
			// set simpan data otomatis dan get val id next utama
			// atau kondisilihatsatuankerja kosong, brrti satuan kerja di lingkup login, dan level dinas ke atas
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1 || $this->SATUAN_KERJA_ID == "" || $kondisilihatsatuankerja == "")
			{
				/*if($reqUtamaRowId == "")
				{
					$set_detil= new UsJabatanRiwayat();
					$set_detil->setField('TIPE_PEGAWAI_ID', $reqTipePegawaiId);
					$set_detil->setField('PEJABAT_PENETAP_ID', $reqPejabatPenetapId);
					$set_detil->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
					
					$set_detil->setField('JABATAN_FU_ID', ValToNullDB($reqJabatanFuId));
					$set_detil->setField('JABATAN_FT_ID', ValToNullDB($reqJabatanFtId));
					
					$set_detil->setField('SATKER_ID', ValToNullDB($reqSatkerId));
					$set_detil->setField('SATKER_NAMA', $reqSatker);
					$set_detil->setField('IS_MANUAL', ValToNullDB($reqIsManual));
					$set_detil->setField('NO_SK', $reqNoSk);
					$set_detil->setField('ESELON_ID', ValToNullDB($reqEselonId));
					$set_detil->setField('NAMA', $reqNama);
					$set_detil->setField('NO_PELANTIKAN', $reqNoPelantikan);
					$set_detil->setField("TUNJANGAN", ValToNullDB(dotToNo($reqTunjangan)));
					$set_detil->setField('KREDIT', ValToNullDB(dotToNo($reqKredit)));
					$set_detil->setField('TMT_BATAS_USIA_PENSIUN', ValToNullDB($req));
					$set_detil->setField('TMT_ESELON', dateToDBCheck($reqTmtEselon));
					$set_detil->setField('TANGGAL_SK', dateToDBCheck($reqTglSk));
					if(strlen($reqTmtWaktuJabatan) == 5)
						$set_detil->setField('TMT_JABATAN', dateTimeToDBCheck($reqTmtJabatan." ".$reqTmtWaktuJabatan));
					else
						$set_detil->setField('TMT_JABATAN', dateToDBCheck($reqTmtJabatan));
					$set_detil->setField('TANGGAL_PELANTIKAN', dateToDBCheck($reqTglPelantikan));
					$set_detil->setField('BULAN_DIBAYAR', dateToDBCheck($reqBlnDibayar));
					$set_detil->setField('KETERANGAN_BUP', $reqKeteranganBUP);
					$set_detil->setField('JENIS_JABATAN_ID', $reqJenisJabatan);
					$set_detil->setField('PEGAWAI_ID', $reqId);
					$set_detil->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
					$set_detil->setField("LAST_USER", $this->LOGIN_USER);
					$set_detil->setField("USER_LOGIN_ID", $this->LOGIN_ID);
					$set_detil->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
					$set_detil->setField("LAST_DATE", "NOW()");

					$set_detil->setField('PEGAWAI_ID', $reqPegawaiId);
					$set_detil->setField('SATKER_ASAL_ID', $reqSatkerAsalId);
					$set_detil->setField('JENIS_MUTASI_ID', $reqJenisMutasiId);
					$set_detil->setField('JENIS_JABATAN_TUGAS_ID', $reqJenisJabatanTugasId);
					$set_detil->setField("LAST_USER_CREATE", $this->LOGIN_USER);
					$set_detil->setField("LAST_DATE_CREATE", "NOW()");
					$set_detil->setField("USER_LOGIN_ID_CREATE", $this->LOGIN_ID);
					$set_detil->setField("USER_LOGIN_PEGAWAI_ID_CREATE", ValToNullDB($this->LOGIN_PEGAWAI_ID));
					$set_detil->insertUtama();
					$reqUtamaRowId=$set_detil->id;
					$reqStatusUsulan= "2";
				}*/

			}
			// exit();

			$set->setField('JABATAN_RIWAYAT_ID', ValToNullDB($reqUtamaRowId));
			$set->setField('TIPE_PEGAWAI_ID', $reqTipePegawaiId);
			$set->setField('PEJABAT_PENETAP_ID', $reqPejabatPenetapId);
			$set->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
			
			$set->setField('JABATAN_FU_ID', ValToNullDB($reqJabatanFuId));
			$set->setField('JABATAN_FT_ID', ValToNullDB($reqJabatanFtId));
			
			$set->setField('SATKER_ID', ValToNullDB($reqSatkerId));
			$set->setField('SATKER_NAMA', $reqSatker);
			$set->setField('IS_MANUAL', ValToNullDB($reqIsManual));
			$set->setField('NO_SK', $reqNoSk);
			$set->setField('ESELON_ID', ValToNullDB($reqEselonId));
			$set->setField('NAMA', $reqNama);
			$set->setField('NO_PELANTIKAN', $reqNoPelantikan);
			$set->setField("TUNJANGAN", ValToNullDB(dotToNo($reqTunjangan)));
			$set->setField('KREDIT', ValToNullDB(dotToNo($reqKredit)));
			$set->setField('TMT_BATAS_USIA_PENSIUN', ValToNullDB($req));
			$set->setField('TMT_ESELON', dateToDBCheck($reqTmtEselon));
			$set->setField('TANGGAL_SK', dateToDBCheck($reqTglSk));
			if(strlen($reqTmtWaktuJabatan) == 5)
				$set->setField('TMT_JABATAN', dateTimeToDBCheck($reqTmtJabatan." ".$reqTmtWaktuJabatan));
			else
				$set->setField('TMT_JABATAN', dateToDBCheck($reqTmtJabatan));
			$set->setField('TANGGAL_PELANTIKAN', dateToDBCheck($reqTglPelantikan));
			$set->setField('BULAN_DIBAYAR', dateToDBCheck($reqBlnDibayar));
			$set->setField('KETERANGAN_BUP', $reqKeteranganBUP);
			$set->setField('JENIS_JABATAN_ID', $reqJenisJabatan);
			$set->setField('PEGAWAI_ID', $reqId);
			$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
			$set->setField("LAST_USER", $this->LOGIN_USER);
			$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
			$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			$set->setField("LAST_DATE", "NOW()");

			$set->setField('PEGAWAI_ID', $reqPegawaiId);
			$set->setField('SATKER_ASAL_ID', $reqSatkerAsalId);
			$set->setField('JENIS_MUTASI_ID', $reqJenisMutasiId);
			$set->setField('JENIS_JABATAN_TUGAS_ID', $reqJenisJabatanTugasId);
			$set->setField("LAST_USER_CREATE", $this->LOGIN_USER);
			$set->setField("LAST_DATE_CREATE", "NOW()");
			$set->setField("USER_LOGIN_ID_CREATE", $this->LOGIN_ID);
			$set->setField("USER_LOGIN_PEGAWAI_ID_CREATE", ValToNullDB($this->LOGIN_PEGAWAI_ID));

			$set->setField("STATUS_USULAN", ValToNullDB($reqStatusUsulan));
			
			$simpan= "";
			if($reqMode == "insert")
			{
				if($set->insert())
				{
					$reqId= $set->id;
					$simpan= "1";
				}
			}
			else
			{
				$set->setField('US_JABATAN_RIWAYAT_ID', $reqId);
				if($set->update())
					$simpan= "1";
			}

			if($simpan == "1")
			{
				if($fileuploadexe == ""){}
				else
				{
					$renameFile = $reqId.".".getExtension($reqLinkFile['name']);
					$target_file= $target_dir.$renameFile;
					if(file_exists($target_file)){}
					else
					{
						$file->uploadToDir('reqLinkFile', $target_dir, $renameFile);
					}
				}

				echo $reqId."-Data berhasil disimpan.";
			}
			else
				echo "xxx-Data gagal disimpan.";

			// echo $set->query;exit;
		}
		
	}

	function add_tugas()
	{
		$this->load->model('persuratan/UsJabatanTambahan');
		$this->load->model('persuratan/MutasiUsulan');
		$this->load->library("FileHandler"); 

		$file = new FileHandler();

		$set = new UsJabatanTambahan();
		
		$reqId= $this->input->post("reqId");
		$reqMode= $this->input->post("reqMode");
		$reqRowId= $this->input->post('reqRowId');
		$reqUtamaRowId= $this->input->post('reqUtamaRowId');

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

		$reqIsDpkManual= $this->input->post("reqIsDpkManual");
		$reqSatkerDpkNama= $this->input->post("reqSatkerDpkNama");

		$reqPegawaiId= $this->input->post("reqPegawaiId");
		$reqSatkerAsalId= $this->input->post("reqSatkerAsalId");
		$reqJenisMutasiId= $this->input->post("reqJenisMutasiId");
		$reqJenisJabatanTugasId= $this->input->post("reqJenisJabatanTugasId");

		$reqLinkFile= $_FILES['reqLinkFile'];

		// Allow certain file formats
		$bolehupload = "";
		$fileuploadexe= strtolower(getExtension($reqLinkFile['name']));

		if($reqJenisJabatanTugasId == "21")
		{
			$target_dir= "uploadsurat/mutasi_tugas_pendidikan/";
		}
		elseif($reqJenisJabatanTugasId == "22")
		{
			$target_dir= "uploadsurat/mutasi_tugas_kesehatan/";
		}

		if(!empty($reqNamaTugas) && ($reqJenisJabatanTugasId == "21" || $reqJenisJabatanTugasId == "22"))
		{
			$this->load->model('JabatanTambahan');

			$statementcaridetil= " AND A.NAMA_JABATAN = '".$reqNamaTugas."'";
			$reqTanggalBatas= $reqTanggalSk;
			$set_detil = new JabatanTambahan();
			if($reqStatusPlt == "plt")
			{
				if($reqTanggalBatas == "")
				{
					$statementcaridetil .= " AND 1 = 2";
				}
				else
				{
					$statementcaridetil .= " 
					AND
					COALESCE(A.MASA_BERLAKU_AWAL, TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD'))
					<= TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD')
					AND
					COALESCE(A.MASA_BERLAKU_AKHIR, TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD'))
					>= TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD')";
				}
				$set_detil->selectByParamsPlt(array(), 70, 0, $statementcaridetil.$statementSatuanKerja);
			}
			elseif($reqStatusPlt == "plh")
			{
				if($reqTanggalBatas == "")
				{
					$statementcaridetil .= " AND 1 = 2";
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
				$set_detil->selectByParamsPlh(array(), 70, 0, $statementcaridetil.$statementSatuanKerja);
			}
			else
			{
				$statementcaridetil= " AND A.NAMA = '".$reqNamaTugas."'";
				$set_detil->selectByParamsTugasDetilTambahan(array(), 70, 0, $reqStatusPlt, $statementcaridetil, $statementSatuanKerja);
			}
			// echo $set_detil->query;exit;
			if(empty($set_detil->firstRow()))
			{
				echo "xxx-Nama Tugas (".$reqNamaTugas.") tidak ada dalam sistem, hubungi admin untuk menambahkan data nama tugas.";
				exit();
			}
			// exit;
		}
		// exit;

		if($fileuploadexe == "")
		{
			if(!empty($reqId))
			{
				$tempUrlFile= $target_dir.$reqId.".pdf";
				if(file_exists($tempUrlFile))
				{
					$bolehupload = 1;
				}
				else
					$bolehupload = 2;
			}
			else
				$bolehupload = 2;
		}
		else
		{
			// $arrFileBolehUpload= array("png", "jpeg", "jpg", "pdf");
			$arrFileBolehUpload= array("pdf");
			// if($fileuploadexe == "pdf")
			if (in_array($fileuploadexe, $arrFileBolehUpload, true))
				$bolehupload = 1;
		}
		// echo $bolehupload;exit();

		if($bolehupload == "")
		{
			// echo "xxx-Data gagal disimpan, check file upload harus format pdf, png, jpeg, jpg.";
			echo "xxx-Data gagal disimpan, check file upload harus format pdf.";
		}
		else if($bolehupload == "2")
		{
			echo "xxx-Data gagal disimpan, file upload harus diisi.";
		}
		else
		{
			if(file_exists($target_dir)){}
			else
			{
				makedirs($target_dir);
			}

			//kalau pejabat tidak ada
			if($reqPejabatPenetapId == "")
			{
				$set_pejabat=new MutasiUsulan();
				$set_pejabat->setField('NAMA', strtoupper($reqPejabatPenetap));
				$set_pejabat->setField("LAST_USER", $this->LOGIN_USER);
				$set_pejabat->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				$set_pejabat->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
				$set_pejabat->setField("LAST_DATE", "NOW()");
				$set_pejabat->insertPejabat();
				// echo $set_pejabat->query;exit();
				$reqPejabatPenetapId=$set_pejabat->id;
				unset($set_pejabat);
			}

			$kondisilihatsatuankerja= "";
			$reqSatkerPembuatId= $this->SATUAN_KERJA_ID;
			// kondisi bukan untuk admin n teknis
			if($reqSatkerPembuatId == ""){}
			else
			{
				if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1 || $this->SATUAN_KERJA_ID == "")
				{}
				else
				{
					$skerja= new MutasiUsulan();
					$dataSatkerPembuatId= $skerja->getSatuanKerja($reqSatkerPembuatId);
					// echo $skerja->query;exit();
					$arrSatkerPembuatId= explode(",", $dataSatkerPembuatId);
					unset($skerja);
					// print_r($arrSatkerPembuatId);exit();
					
					if (in_array($reqSatkerAsalId, $arrSatkerPembuatId, true) && $this->LOGIN_LEVEL >= 20) {}
					else
					{
						// kalau 1 maka pegawai dari satuan kerja lain, maka set satuan kerja sesuai dengan satuankerja pembuat ke bawah
						$kondisilihatsatuankerja= "1";
					}
				}
			}
			// 735
			// echo $reqSatkerAsalId."--".$reqSatkerPembuatId."--".$kondisilihatsatuankerja."--".$this->LOGIN_LEVEL;
			// exit();

			$reqStatusUsulan= "";
			// set simpan data otomatis dan get val id next utama
			// atau kondisilihatsatuankerja kosong, brrti satuan kerja di lingkup login, dan level dinas ke atas
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1 || $this->SATUAN_KERJA_ID == "" || $kondisilihatsatuankerja == "")
			{
				/*if($reqUtamaRowId == "")
				{
					$set_detil= new UsJabatanTambahan();
					$set_detil->setField('NO_PELANTIKAN', $reqNoPelantikan);
					$set_detil->setField('TANGGAL_PELANTIKAN', dateToDBCheck($reqTanggalPelantikan));
					$set_detil->setField("TUNJANGAN", ValToNullDB(dotToNo($reqTunjangan)));
					$set_detil->setField('BULAN_DIBAYAR', dateToDBCheck($reqBulanDibayar));
					$set_detil->setField('NAMA', $reqNamaTugas);
					$set_detil->setField('PEJABAT_PENETAP_ID', $reqPejabatPenetapId);
					$set_detil->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
					$set_detil->setField('TUGAS_TAMBAHAN_ID', ValToNullDB($reqTugasTambahanId));
					$set_detil->setField('NO_SK', $reqNoSk);
					$set_detil->setField('TANGGAL_SK', dateToDBCheck($reqTanggalSk));
					
					$set_detil->setField('SATKER_ID', ValToNullDB($reqSatkerId));
					$set_detil->setField('SATKER_NAMA', $reqSatker);
					$set_detil->setField('IS_MANUAL', ValToNullDB($reqIsManual));
					$set_detil->setField('STATUS_PLT', $reqStatusPlt);
					
					if(strlen($reqTmtWaktuTugas) == 5)
					$set_detil->setField('TMT_JABATAN', dateTimeToDBCheck($reqTmtTugas." ".$reqTmtWaktuTugas));
					else
					$set_detil->setField('TMT_JABATAN', dateToDBCheck($reqTmtTugas));
					$set_detil->setField('TMT_JABATAN_AKHIR', dateToDBCheck($reqTmtJabatanAkhir));

					$set_detil->setField('PEGAWAI_ID', $reqId);
					$set_detil->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
					$set_detil->setField("LAST_USER", $this->LOGIN_USER);
					$set_detil->setField("USER_LOGIN_ID", $this->LOGIN_ID);
					$set_detil->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
					$set_detil->setField("LAST_DATE", "NOW()");

					$set_detil->setField('SATKER_DPK_NAMA', $reqSatkerDpkNama);
					$set_detil->setField('IS_DPK_MANUAL', ValToNullDB($reqIsDpkManual));

					$set_detil->setField('PEGAWAI_ID', $reqPegawaiId);
					$set_detil->setField('SATKER_ASAL_ID', $reqSatkerAsalId);
					$set_detil->setField('JENIS_MUTASI_ID', $reqJenisMutasiId);
					$set_detil->setField('JENIS_JABATAN_TUGAS_ID', $reqJenisJabatanTugasId);
					$set_detil->setField("LAST_USER_CREATE", $this->LOGIN_USER);
					$set_detil->setField("LAST_DATE_CREATE", "NOW()");
					$set_detil->setField("USER_LOGIN_ID_CREATE", $this->LOGIN_ID);
					$set_detil->setField("USER_LOGIN_PEGAWAI_ID_CREATE", ValToNullDB($this->LOGIN_PEGAWAI_ID));
					$set_detil->insertUtama();
					$reqUtamaRowId=$set_detil->id;
					$reqStatusUsulan= "2";
				}*/

			}
			// exit();

			$set->setField('JABATAN_TAMBAHAN_ID', ValToNullDB($reqUtamaRowId));

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

			$set->setField('SATKER_DPK_NAMA', $reqSatkerDpkNama);
			$set->setField('IS_DPK_MANUAL', ValToNullDB($reqIsDpkManual));

			$set->setField('PEGAWAI_ID', $reqPegawaiId);
			$set->setField('SATKER_ASAL_ID', $reqSatkerAsalId);
			$set->setField('JENIS_MUTASI_ID', $reqJenisMutasiId);
			$set->setField('JENIS_JABATAN_TUGAS_ID', $reqJenisJabatanTugasId);
			$set->setField("LAST_USER_CREATE", $this->LOGIN_USER);
			$set->setField("LAST_DATE_CREATE", "NOW()");
			$set->setField("USER_LOGIN_ID_CREATE", $this->LOGIN_ID);
			$set->setField("USER_LOGIN_PEGAWAI_ID_CREATE", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			
			$set->setField("STATUS_USULAN", ValToNullDB($reqStatusUsulan));
			
			$simpan= "";
			if($reqMode == "insert")
			{
				if($set->insert())
				{
					$reqId= $set->id;
					$simpan= "1";
				}
			}
			else
			{
				$set->setField('US_JABATAN_TAMBAHAN_ID', $reqId);
				if($set->update())
					$simpan= "1";
			}

			if($simpan == "1")
			{
				if($fileuploadexe == ""){}
				else
				{
					$renameFile = $reqId.".".getExtension($reqLinkFile['name']);
					$target_file= $target_dir.$renameFile;
					if(file_exists($target_file)){}
					else
					{
						$file->uploadToDir('reqLinkFile', $target_dir, $renameFile);
					}
				}

				echo $reqId."-Data berhasil disimpan.";
			}
			else
				echo "xxx-Data gagal disimpan.";

			// echo $set->query;exit;
		}
		
	}

	function add_mutasi_intern()
	{
		$this->load->model('persuratan/UsJabatanMutasiIntern');
		$this->load->model('persuratan/MutasiUsulan');

		$set = new UsJabatanMutasiIntern();
		
		$reqId= $this->input->post("reqId");
		$reqMode= $this->input->post("reqMode");
		$reqRowId= $this->input->post('reqRowId');

		$reqSatkerId= $this->input->post("reqSatkerId");
		$reqSatker= $this->input->post("reqSatker");
		//echo $reqSatker;exit;
		$reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
		$reqPejabatPenetapId= $this->input->post("reqPejabatPenetapId");

		$reqPegawaiId= $this->input->post("reqPegawaiId");
		$reqSatkerAsalId= $this->input->post("reqSatkerAsalId");
		$reqJenisMutasiId= $this->input->post("reqJenisMutasiId");
		$reqJenisJabatanTugasId= $this->input->post("reqJenisJabatanTugasId");

		$kondisilihatsatuankerja= "";
		$reqSatkerPembuatId= $this->SATUAN_KERJA_ID;
		// kondisi bukan untuk admin n teknis
		if($reqSatkerPembuatId == ""){}
		else
		{
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1 || $this->SATUAN_KERJA_ID == "")
			{}
			else
			{
				$skerja= new MutasiUsulan();
				$dataSatkerPembuatId= $skerja->getSatuanKerja($reqSatkerPembuatId);
				// echo $skerja->query;exit();
				$arrSatkerPembuatId= explode(",", $dataSatkerPembuatId);
				unset($skerja);
				// print_r($arrSatkerPembuatId);exit();
				
				if (in_array($reqSatkerAsalId, $arrSatkerPembuatId, true) && $this->LOGIN_LEVEL >= 20) {}
				else
				{
					// kalau 1 maka pegawai dari satuan kerja lain, maka set satuan kerja sesuai dengan satuankerja pembuat ke bawah
					$kondisilihatsatuankerja= "1";
				}
			}
		}
		// 735
		// echo $reqSatkerAsalId."--".$reqSatkerPembuatId."--".$kondisilihatsatuankerja."--".$this->LOGIN_LEVEL;
		// exit();

		$reqStatusUsulan= "";
		if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1 || $this->SATUAN_KERJA_ID == "" || $kondisilihatsatuankerja == "")
		{
			$set_detil= new UsJabatanMutasiIntern();
			$set_detil->setField('PEGAWAI_ID', $reqPegawaiId);
			$set_detil->setField('SATKER_ID', ValToNullDB($reqSatkerId));
			$set_detil->setField("LAST_USER_CREATE", $this->LOGIN_USER);
			$set_detil->setField("LAST_DATE_CREATE", "NOW()");
			$set_detil->setField("USER_LOGIN_ID_CREATE", $this->LOGIN_ID);
			$set_detil->setField("USER_LOGIN_PEGAWAI_ID_CREATE", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			$set_detil->insertUtama();
			$reqStatusUsulan= "2";
		}

		$set->setField('SATKER_ID', ValToNullDB($reqSatkerId));
		$set->setField('SATKER_NAMA', $reqSatker);
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");

		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('SATKER_ASAL_ID', $reqSatkerAsalId);
		$set->setField('JENIS_MUTASI_ID', $reqJenisMutasiId);
		$set->setField('JENIS_JABATAN_TUGAS_ID', $reqJenisJabatanTugasId);
		$set->setField("LAST_USER_CREATE", $this->LOGIN_USER);
		$set->setField("LAST_DATE_CREATE", "NOW()");
		$set->setField("USER_LOGIN_ID_CREATE", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID_CREATE", ValToNullDB($this->LOGIN_PEGAWAI_ID));

		$set->setField("STATUS_USULAN", ValToNullDB($reqStatusUsulan));
		
		$simpan= "";
		if($reqMode == "insert")
		{
			if($set->insert())
			{
				$reqId= $set->id;
				$simpan= "1";
			}
		}
		else
		{
			$set->setField('US_JABATAN_MUTASI_INTERN_ID', $reqId);
			if($set->update())
				$simpan= "1";
		}

		if($simpan == "1")
		{
			echo $reqId."-Data berhasil disimpan.";
		}
		else
			echo "xxx-Data gagal disimpan.";

		// echo $set->query;exit;
		
	}

	function valid()
	{
		$reqId =  $this->input->get('reqId');
		$reqJenisJabatanTugasId =  $this->input->get('reqJenisJabatanTugasId');
		$reqMode =  $this->input->get('reqMode');
		// echo "asd".$reqId;exit();
		if($reqJenisJabatanTugasId == "11" || $reqJenisJabatanTugasId == "12")
		{
			$this->load->model('persuratan/UsJabatanRiwayat');
			$set = new UsJabatanRiwayat();
			$set->setField("US_JABATAN_RIWAYAT_ID", $reqId);
		}
		else if($reqJenisJabatanTugasId == "21" || $reqJenisJabatanTugasId == "22")
		{
			$this->load->model('persuratan/UsJabatanTambahan');
			$set = new UsJabatanTambahan();
			$set->setField("US_JABATAN_TAMBAHAN_ID", $reqId);
		}
		else if($reqJenisJabatanTugasId == "29")
		{
			$this->load->model('persuratan/UsJabatanMutasiIntern');
			$set = new UsJabatanMutasiIntern();
			$set->setField("US_JABATAN_MUTASI_INTERN_ID", $reqId);
		}
		// exit();
		
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		$set->setField("STATUS", "2");

		if($set->validData())
			echo "-Data berhasil di validasi.";
		else
			echo "-Data gagal di validasi.";	
	}

	function reject()
	{
		$reqId =  $this->input->get('reqId');
		$reqJenisJabatanTugasId =  $this->input->get('reqJenisJabatanTugasId');
		$reqMode =  $this->input->get('reqMode');
		$reqAlasan = $this->input->get('reqAlasan');
		// echo $reqAlasan;exit;

		// echo "asd".$reqId;exit();
		if($reqJenisJabatanTugasId == "11" || $reqJenisJabatanTugasId == "12")
		{
			$this->load->model('persuratan/UsJabatanRiwayat');
			$set = new UsJabatanRiwayat();
			$set->setField("US_JABATAN_RIWAYAT_ID", $reqId);
		}
		else if($reqJenisJabatanTugasId == "21" || $reqJenisJabatanTugasId == "22")
		{
			$this->load->model('persuratan/UsJabatanTambahan');
			$set = new UsJabatanTambahan();
			$set->setField("US_JABATAN_TAMBAHAN_ID", $reqId);
		}
		
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		$set->setField("STATUS", "1");
		$set->setField("ALASAN_TOLAK", $reqAlasan);

		if($set->rejectdata())
			echo "-Data berhasil di tolak.";
		else
			echo "-Data gagal di tolak.";
	}

	function delete()
	{
		$reqId =  $this->input->get('reqId');
		$reqJenisJabatanTugasid =  $this->input->get('reqJenisJabatanTugasid');
		$reqMode =  $this->input->get('reqMode');
		
		if($reqJenisJabatanTugasid == "11" || $reqJenisJabatanTugasid == "12")
		{
			$this->load->model('persuratan/UsJabatanRiwayat');
			$set = new UsJabatanRiwayat();
			$set->setField("US_JABATAN_RIWAYAT_ID", $reqId);
		}
		else if($reqJenisJabatanTugasid == "21" || $reqJenisJabatanTugasid == "22")
		{
			$this->load->model('persuratan/UsJabatanTambahan');
			$set = new UsJabatanTambahan();
			$set->setField("US_JABATAN_TAMBAHAN_ID", $reqId);
		}
		else if($reqJenisJabatanTugasid == "29")
		{
			$this->load->model('persuratan/UsJabatanMutasiIntern');
			$set = new UsJabatanMutasiIntern();
			$set->setField("US_JABATAN_MUTASI_INTERN_ID", $reqId);
		}
		
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		$set->setField("STATUS", "1");

		if($set->updateStatus())
			echo "Data berhasil dihapus.";
		else
			echo "Data gagal dihapus.";	
	}

}
?>