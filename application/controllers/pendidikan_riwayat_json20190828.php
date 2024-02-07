<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class pendidikan_riwayat_json extends CI_Controller {

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
	
	function add()
	{
		$this->load->model('PendidikanRiwayat');
		$this->load->model('PendidikanJurusan');
		
		$set = new PendidikanRiwayat();
		
		$reqId = $this->input->post("reqId");
		$reqRowId= $this->input->post("reqRowId");
		$reqMode = $this->input->post("reqMode");
		
		$reqNamaSekolah=  $this->input->post('reqNamaSekolah');
		$reqNamaFakultas=  $this->input->post('reqNamaFakultas');
		$reqPendidikanId=  $this->input->post('reqPendidikanId');
		$reqTglSttb= $this->input->post('reqTglSttb');
		$reqJurusan=  $this->input->post('reqJurusan');
		$reqJurusanId=  $this->input->post('reqJurusanId');
		$reqAlamatSekolah=  $this->input->post('reqAlamatSekolah');
		$reqKepalaSekolah=  $this->input->post('reqKepalaSekolah');
		$reqNoSttb=  $this->input->post('reqNoSttb');
		$reqStatusTugasIjinBelajar=  $this->input->post('reqStatusTugasIjinBelajar');
		$reqStatusPendidikan=  $this->input->post('reqStatusPendidikan');
		$reqNoSuratIjin=  $this->input->post('reqNoSuratIjin');
		$reqTglSuratIjin= $this->input->post('reqTglSuratIjin');
		$reqGelarTipe=  $this->input->post('reqGelarTipe');
		$reqGelarNamaDepan=  $this->input->post('reqGelarNamaDepan');
		$reqGelarNama=  $this->input->post('reqGelarNama');


		// $statement= " AND A.STATUS IS NULL AND UPPER(A.NAMA) LIKE '%".strtoupper($reqJurusan)."%'  AND A.PENDIDIKAN_ID = ".$reqPendidikanId;

		if($reqPendidikanId < 6){}
		else
		{
			$statement= " AND A.STATUS IS NULL AND UPPER(A.NAMA) = '".strtoupper($reqJurusan)."' AND A.PENDIDIKAN_ID = ".$reqPendidikanId;
			$set_detil= new PendidikanJurusan();
			$set_detil->selectByParams(array(), -1,-1, $statement);
			// echo $set_detil->query;exit();
			$set_detil->firstRow();
			$reqJurusanId= $set_detil->getField("PENDIDIKAN_JURUSAN_ID");
			$reqJurusan= $set_detil->getField("NAMA");
			unset($set_detil);

			if($reqJurusanId == "")
			{
				echo "xxx-Jurusan tidak ada dalam sistem, hubungi admin untuk menambahkan data jurusan.";
				exit();
			}
		}
		
		$set->setField('NAMA', $reqNamaSekolah);
		$set->setField('NAMA_FAKULTAS', $reqNamaFakultas);
		$set->setField('PENDIDIKAN_ID', $reqPendidikanId);
		$set->setField('TANGGAL_STTB', dateToDBCheck($reqTglSttb));
		$set->setField('JURUSAN', $reqJurusan);
		$set->setField('PENDIDIKAN_JURUSAN_ID', ValToNullDB($reqJurusanId));
		$set->setField('TEMPAT', $reqAlamatSekolah);
		$set->setField('KEPALA', $reqKepalaSekolah);
		$set->setField('NO_STTB', $reqNoSttb);
		$set->setField('STATUS_TUGAS_IJIN_BELAJAR', ValToNullDB($reqStatusTugasIjinBelajar));
		$set->setField('STATUS_PENDIDIKAN', $reqStatusPendidikan);
		$set->setField('NO_SURAT_IJIN', $reqNoSuratIjin);
		$set->setField('TANGGAL_SURAT_IJIN', dateToDBCheck($reqTglSuratIjin));
		$set->setField('GELAR_TIPE', $reqGelarTipe);
		$set->setField('GELAR_DEPAN', $reqGelarNamaDepan);
		$set->setField('GELAR_NAMA', $reqGelarNama);
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
			$set->setField('PENDIDIKAN_RIWAYAT_ID', $reqRowId);
			if($set->update())
				echo $reqRowId."-Data berhasil disimpan.";
			else
				echo "xxx-Data gagal disimpan.";
		}
		// echo $set->query;exit;
		
	}
	
	function delete()
	{
		$this->load->model('PendidikanRiwayat');
		$set = new PendidikanRiwayat();
		
		$reqId =  $this->input->get('reqId');
		$reqMode =  $this->input->get('reqMode');
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		$set->setField("PENDIDIKAN_RIWAYAT_ID", $reqId);
		
		if($reqMode == "pendidikan_riwayat_0")
		{
			$set->setField("STATUS", "1");
			if($set->updateStatus())
				echo "Data berhasil dihapus.";
			else
				echo "Data gagal dihapus.";	
		}
		elseif($reqMode == "pendidikan_riwayat_1")
		{
			$set->setField("STATUS", "2");
			if($set->updateStatus())
				echo "Data berhasil diaktifkan.";
			else
				echo "Data gagal diaktifkan.";	
		}
	}

	function log($riwayatId) 
	{	
		$this->load->model('PendidikanRiwayatLog');

		$set = new PendidikanRiwayatLog();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		
		$aColumns = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "PENDIDIKAN_RIWAYAT_ID");
		$aColumnsAlias = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "PENDIDIKAN_RIWAYAT_ID");
		

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
		
		$arrayWhere = array("PENDIDIKAN_RIWAYAT_ID" => $riwayatId);
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
	
	function cari_pegawai_usulan()
	{
		$this->load->model("PendidikanRiwayat");
		$this->load->model('SatuanKerja');
		
		$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
		
		$reqId= $this->input->get("reqId");
		$reqJenis= $this->input->get("reqJenis");
        $reqMode= $this->input->get("reqMode");
		
		$set= new PendidikanRiwayat();
		$statement= " AND A.STATUS IS NULL";
		$statement.= " AND UPPER(A.NIP_BARU) LIKE '%".strtoupper(str_replace(" ", "", $search_term))."%' ";
		
		$statement.= " AND UST.STATUS_PENDIDIKAN IN ('1', '2')";
		
		//if($reqMode == "1")
		//$statement.= " AND A.PEGAWAI_ID NOT IN (SELECT PEGAWAI_ID FROM persuratan.SURAT_MASUK_PEGAWAI WHERE JENIS_ID = ".$reqJenis." AND ( SURAT_MASUK_BKD_ID = ".$reqId." OR SURAT_MASUK_UPT_ID IS NOT NULL ))";
		////$statement.= " AND A.PEGAWAI_ID NOT IN (SELECT PEGAWAI_ID FROM persuratan.SURAT_MASUK_PEGAWAI X WHERE X.JENIS_ID = ".$reqJenis." AND ( X.SURAT_MASUK_BKD_ID = ".$reqId." OR (X.SURAT_MASUK_UPT_ID IS NOT NULL AND '1' = (SELECT Y.STATUS_KIRIM FROM persuratan.SURAT_MASUK_UPT Y WHERE X.SURAT_MASUK_UPT_ID = Y.SURAT_MASUK_UPT_ID))))";
		//else
		//$statement.= " AND A.PEGAWAI_ID NOT IN (SELECT PEGAWAI_ID FROM persuratan.SURAT_MASUK_PEGAWAI WHERE JENIS_ID = ".$reqJenis." AND SURAT_MASUK_UPT_ID = ".$reqId.")";

		//$statement.= " AND A.PEGAWAI_ID NOT IN (SELECT PEGAWAI_ID FROM persuratan.SURAT_MASUK_PEGAWAI WHERE STATUS_BERKAS NOT IN (10) OR STATUS_BERKAS IS NULL)";
		
		$statement.= " AND A.PEGAWAI_ID NOT IN (SELECT PEGAWAI_ID FROM persuratan.SURAT_MASUK_PEGAWAI WHERE JENIS_ID = 1 AND (STATUS_BERKAS NOT IN (10) OR STATUS_BERKAS IS NULL OR (STATUS_BERKAS = 10 AND (STATUS_SURAT_KELUAR NOT IN (3) OR STATUS_SURAT_KELUAR IS NULL ))))";
		
		//check scan asli file pangkat
		// $statement.= " AND PSCANPANG.JUMLAH_DATA > 0";
		
		//check scan asli file pendidikan
 		// $statement.= " AND PSCANPEND.JUMLAH_DATA > 0";
 
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		if($reqSatuanKerjaId == ""){}
		else
		{
			$skerja= new SatuanKerja();
			$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
			unset($skerja);
			//echo $reqSatuanKerjaId;exit;
			$statement.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			//$statement.= " AND ( A.SATUAN_KERJA_ID = ANY( AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$reqSatuanKerjaId.") ) OR A.SATUAN_KERJA_ID = ".$reqSatuanKerjaId." )";
		}
		
		// AND SATUAN_KERJA_PARENT_ID > 0
		//data dinas
		if($reqMode == "1")
		{
			$statement.= " 
			AND A.SATUAN_KERJA_ID IN
			(
			SELECT SATUAN_KERJA_ID FROM SATUAN_KERJA A WHERE TIPE_ID = 1
			AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")
			)";
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
			$arr_json[$i]['jabatanriwayatid'] = $set->getField("JABATAN_RIWAYAT_ID");
			$arr_json[$i]['pendidikanriwayatid'] = $set->getField("PENDIDIKAN_RIWAYAT_ID");
			$arr_json[$i]['gajiriwayatid'] = $set->getField("GAJI_RIWAYAT_ID");
			$arr_json[$i]['pangkatriwayatid'] = $set->getField("PANGKAT_RIWAYAT_ID");
			$arr_json[$i]['namapegawai'] = $set->getField("NAMA_LENGKAP");
			$arr_json[$i]['pendidikanid'] = $set->getField("PENDIDIKAN_ID");
			$arr_json[$i]['pendidikannama'] = $set->getField("PENDIDIKAN_NAMA");
			$arr_json[$i]['pendidikanstatus'] = $set->getField("STATUS_PENDIDIKAN_NAMA");
			$arr_json[$i]['pendidikanjurusan'] = $set->getField("JURUSAN");
			$arr_json[$i]['pendidikannamasekolah'] = $set->getField("NAMA_SEKOLAH");
			$i++;
		}
		echo json_encode($arr_json);
	}
	
	function cari_pegawai_karpeg_usulan()
	{
		$this->load->model("PendidikanRiwayat");
		$this->load->model('SatuanKerja');
		
		$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
		
		$reqId= $this->input->get("reqId");
		$reqJenis= $this->input->get("reqJenis");
        $reqMode= $this->input->get("reqMode");
		
		$set= new PendidikanRiwayat();
		$statement= " AND A.STATUS IS NULL";
		$statement.= " AND UPPER(A.NIP_BARU) LIKE '%".strtoupper(str_replace(" ", "", $search_term))."%' ";
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		if($reqSatuanKerjaId == ""){}
		else
		{
			$skerja= new SatuanKerja();
			$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
			unset($skerja);
			//echo $reqSatuanKerjaId;exit;
			$statement.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			//$statement.= " AND ( A.SATUAN_KERJA_ID = ANY( AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$reqSatuanKerjaId.") ) OR A.SATUAN_KERJA_ID = ".$reqSatuanKerjaId." )";
		}
		$statement.= " AND A.PEGAWAI_ID NOT IN (SELECT PEGAWAI_ID FROM persuratan.SURAT_MASUK_PEGAWAI WHERE JENIS_ID = 4 AND (STATUS_BERKAS NOT IN (10) OR STATUS_BERKAS IS NULL OR (STATUS_BERKAS = 10 AND (STATUS_SURAT_KELUAR NOT IN (3) OR STATUS_SURAT_KELUAR IS NULL ))))";
		
		// AND SATUAN_KERJA_PARENT_ID > 0
		//data dinas
		if($reqMode == "1")
		{
			$statement.= " 
			AND A.SATUAN_KERJA_ID IN
			(
			SELECT SATUAN_KERJA_ID FROM SATUAN_KERJA A WHERE TIPE_ID = 1
			AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")
			)";
		}
		
		$set->selectByParamsPegawaiCariKarpeg(array(), 10, 0, $statement);
		//echo $set->query;exit;
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("PEGAWAI_ID");
			$arr_json[$i]['label'] = $set->getField("NIP_BARU");
			$arr_json[$i]['desc'] = $set->getField("NIP_BARU")."<br/><label style='font-size:12px'>".$set->getField("NAMA_LENGKAP")."</label>";
			$arr_json[$i]['satuankerjaid'] = $set->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['satuankerjanama'] = $set->getField("SATUAN_KERJA_NAMA");
			$arr_json[$i]['jabatanriwayatid'] = $set->getField("JABATAN_RIWAYAT_ID");
			$arr_json[$i]['jabatannama'] = $set->getField("JABATAN_NAMA");
			$arr_json[$i]['pendidikanriwayatid'] = $set->getField("PENDIDIKAN_RIWAYAT_ID");
			$arr_json[$i]['gajiriwayatid'] = $set->getField("GAJI_RIWAYAT_ID");
			$arr_json[$i]['pangkatriwayatid'] = $set->getField("PANGKAT_RIWAYAT_ID");
			$arr_json[$i]['namapegawai'] = $set->getField("NAMA_LENGKAP");
			$arr_json[$i]['pangkatkode'] = $set->getField("PANGKAT_KODE");
			$arr_json[$i]['kartupegawailama'] = $set->getField("KARTU_PEGAWAI");

			$i++;
		}
		echo json_encode($arr_json);
	}

	function cari_pegawai_karsu_usulan()
	{
		$this->load->model("PendidikanRiwayat");
		$this->load->model('SatuanKerja');
		
		$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
		
		$reqId= $this->input->get("reqId");
		$reqJenis= $this->input->get("reqJenis");
        $reqMode= $this->input->get("reqMode");
		
		$set= new PendidikanRiwayat();
		$statement= " AND A.STATUS IS NULL";
		$statement.= " AND UPPER(A.NIP_BARU) LIKE '%".strtoupper(str_replace(" ", "", $search_term))."%' ";
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		if($reqSatuanKerjaId == ""){}
		else
		{
			$skerja= new SatuanKerja();
			$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
			unset($skerja);
			//echo $reqSatuanKerjaId;exit;
			$statement.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			//$statement.= " AND ( A.SATUAN_KERJA_ID = ANY( AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$reqSatuanKerjaId.") ) OR A.SATUAN_KERJA_ID = ".$reqSatuanKerjaId." )";
		}
		$statement.= " AND A.PEGAWAI_ID NOT IN (SELECT PEGAWAI_ID FROM persuratan.SURAT_MASUK_PEGAWAI WHERE JENIS_ID = 3 AND (STATUS_BERKAS NOT IN (10) OR STATUS_BERKAS IS NULL OR (STATUS_BERKAS = 10 AND (STATUS_SURAT_KELUAR NOT IN (3) OR STATUS_SURAT_KELUAR IS NULL ))))";
		
		// AND SATUAN_KERJA_PARENT_ID > 0
		//data dinas
		if($reqMode == "1")
		{
			$statement.= " 
			AND A.SATUAN_KERJA_ID IN
			(
			SELECT SATUAN_KERJA_ID FROM SATUAN_KERJA A WHERE TIPE_ID = 1
			AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")
			)";
		}
		
		$set->selectByParamsPegawaiCariKaris(array(), 10, 0, $statement);
		//echo $set->query;exit;
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("PEGAWAI_ID");
			$arr_json[$i]['label'] = $set->getField("NIP_BARU");
			$arr_json[$i]['desc'] = $set->getField("NIP_BARU")."<br/><label style='font-size:12px'>".$set->getField("NAMA_LENGKAP")."</label>";
			$arr_json[$i]['satuankerjaid'] = $set->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['satuankerjanama'] = $set->getField("SATUAN_KERJA_NAMA");
			$arr_json[$i]['jabatanriwayatid'] = $set->getField("JABATAN_RIWAYAT_ID");
			$arr_json[$i]['jabataneselon'] = $set->getField("JABATAN_ESELON");
			$arr_json[$i]['jabatannama'] = $set->getField("JABATAN_NAMA");
			$arr_json[$i]['jabatantmt'] = dateTimeToPageCheck($set->getField("JABATAN_TMT"));

			$arr_json[$i]['pendidikanriwayatid'] = $set->getField("PENDIDIKAN_RIWAYAT_ID");
			$arr_json[$i]['gajiriwayatid'] = $set->getField("GAJI_RIWAYAT_ID");
			$arr_json[$i]['pangkatriwayatid'] = $set->getField("PANGKAT_RIWAYAT_ID");
			$arr_json[$i]['pangkatkode'] = $set->getField("PANGKAT_KODE");
			$arr_json[$i]['pangkattmt'] = dateToPageCheck($set->getField("PANGKAT_TMT"));

			$arr_json[$i]['namapegawai'] = $set->getField("NAMA_LENGKAP");

			$arr_json[$i]['suamiistriid'] = $set->getField("SUAMI_ISTRI_ID");
			$arr_json[$i]['suamiistrinama'] = $set->getField("SUAMI_ISTRI_NAMA");
			$arr_json[$i]['suamiistritanggallahir'] = dateToPageCheck($set->getField("SUAMI_ISTRI_TANGGAL_LAHIR"));
			$arr_json[$i]['suamiistritanggalkawin'] = dateToPageCheck($set->getField("SUAMI_ISTRI_TANGGAL_KAWIN"));
			$arr_json[$i]['suamiistripertamapnsstatus'] = $set->getField("SUAMI_ISTRI_PERTAMA_PNS_STATUS");
			$arr_json[$i]['suamiistripertamapnsstatusnama'] = $set->getField("SUAMI_ISTRI_PERTAMA_PNS_STATUS_NAMA");
			$arr_json[$i]['suamiistripertamapnsstatussi'] = $set->getField("SUAMI_ISTRI_PERTAMA_PNS_STATUS_S_I");
			$arr_json[$i]['suamiistripertamapnsstatussinama'] = $set->getField("SUAMI_ISTRI_PERTAMA_PNS_STATUS_S_I_NAMA");
			$arr_json[$i]['suamiistripertamapnstanggal'] = dateToPageCheck($set->getField("SUAMI_ISTRI_PERTAMA_PNS_TANGGAL"));
			$arr_json[$i]['suamiistripisahid'] = $set->getField("SUAMI_ISTRI_PISAH_ID");
			
			$i++;
		}
		echo json_encode($arr_json);
	}

	function cari_pegawai_pensiun_usulan()
	{
		$this->load->model("PendidikanRiwayat");
		$this->load->model('SatuanKerja');
		
		$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
		
		$reqId= $this->input->get("reqId");
		$reqJenis= $this->input->get("reqJenis");
		$reqKategori= $this->input->get("reqKategori");
        $reqMode= $this->input->get("reqMode");
		
		$set= new PendidikanRiwayat();
		// $statement= " AND A.STATUS IS NULL";
		$statement= " AND UPPER(A.NIP_BARU) LIKE '%".strtoupper(str_replace(" ", "", $search_term))."%' ";
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		if($reqSatuanKerjaId == ""){}
		else
		{
			$skerja= new SatuanKerja();
			$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
			unset($skerja);
			//echo $reqSatuanKerjaId;exit;
			$statement.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			//$statement.= " AND ( A.SATUAN_KERJA_ID = ANY( AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$reqSatuanKerjaId.") ) OR A.SATUAN_KERJA_ID = ".$reqSatuanKerjaId." )";
		}
		$statement.= " AND A.PEGAWAI_ID NOT IN (SELECT PEGAWAI_ID FROM persuratan.SURAT_MASUK_PEGAWAI WHERE JENIS_ID = ".$reqJenis." AND (STATUS_BERKAS NOT IN (10) OR STATUS_BERKAS IS NULL OR (STATUS_BERKAS = 10 AND (STATUS_SURAT_KELUAR NOT IN (3) OR STATUS_SURAT_KELUAR IS NULL ))))";
		
		// AND SATUAN_KERJA_PARENT_ID > 0
		//data dinas
		if($reqMode == "1")
		{
			$statement.= " 
			AND A.SATUAN_KERJA_ID IN
			(
			SELECT SATUAN_KERJA_ID FROM SATUAN_KERJA A WHERE TIPE_ID = 1
			AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")
			)";

			$statement.= " AND P.TMT <= (SELECT CAST(CAST(DATE_TRUNC('MONTH', TANGGAL) + INTERVAL '1 MONTH' - INTERVAL '1 DAY' AS DATE) + INTERVAL '1 YEAR 1 MONTH' AS DATE) TANGGAL_BATAS FROM PERSURATAN.SURAT_MASUK_BKD WHERE SURAT_MASUK_BKD_ID = ".$reqId.")";
		}
		else
		{
			$statement.= " AND P.TMT <= (SELECT CAST(CAST(DATE_TRUNC('MONTH', TANGGAL) + INTERVAL '1 MONTH' - INTERVAL '1 DAY' AS DATE) + INTERVAL '1 YEAR 1 MONTH' AS DATE) TANGGAL_BATAS FROM PERSURATAN.SURAT_MASUK_UPT WHERE SURAT_MASUK_UPT_ID = ".$reqId.")";
		}

		$statement.= " AND P.JENIS = '".$reqKategori."'";


		$set->selectByParamsPegawaiCariPensiun(array(), 10, 0, $statement);
		// echo $set->query;exit;
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("PEGAWAI_ID");
			$arr_json[$i]['label'] = $set->getField("NIP_BARU");
			$arr_json[$i]['desc'] = $set->getField("NIP_BARU")."<br/><label style='font-size:12px'>".$set->getField("NAMA_LENGKAP")."</label>";
			$arr_json[$i]['namapegawai'] = $set->getField("NAMA_LENGKAP");
			$arr_json[$i]['satuankerjaid'] = $set->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['satuankerjanama'] = $set->getField("SATUAN_KERJA_NAMA");
			$arr_json[$i]['pangkatkode'] = $set->getField("PANGKAT_KODE");
			$arr_json[$i]['pangkattmt'] = dateToPageCheck($set->getField("PANGKAT_TMT"));
			$arr_json[$i]['pangkatth'] = $set->getField("PANGKAT_TH");
			$arr_json[$i]['pangkatbl'] = $set->getField("PANGKAT_BL");
			$arr_json[$i]['pensiuntmt'] = dateToPageCheck($set->getField("PENSIUN_TMT"));
			$arr_json[$i]['pensiunth'] = $set->getField("PENSIUN_TH");
			$arr_json[$i]['pensiunbl'] = $set->getField("PENSIUN_BL");
			$arr_json[$i]['pensiuntanggalkematian'] = dateToPageCheck($set->getField("PENSIUN_TANGGAL_KEMATIAN"));
			$arr_json[$i]['pensiunnomorsk'] = $set->getField("PENSIUN_NOMOR_SK");
			$arr_json[$i]['pensiuntanggalskkematian'] = dateToPageCheck($set->getField("PENSIUN_TANGGAL_SK_KEMATIAN"));
			$arr_json[$i]['pensiunketerangan'] = $set->getField("PENSIUN_KETERANGAN");
			$arr_json[$i]['jabataneselon'] = $set->getField("JABATAN_ESELON");
			$arr_json[$i]['jabatannama'] = $set->getField("JABATAN_NAMA");
			$arr_json[$i]['jabatantmt'] = dateTimeToPageCheck($set->getField("JABATAN_TMT"));

			$arr_json[$i]['jabatanriwayatid'] = $set->getField("JABATAN_RIWAYAT_ID");
			$arr_json[$i]['pendidikanriwayatid'] = $set->getField("PENDIDIKAN_RIWAYAT_ID");
			$arr_json[$i]['gajiriwayatid'] = $set->getField("GAJI_RIWAYAT_ID");
			$arr_json[$i]['pangkatriwayatid'] = $set->getField("PANGKAT_RIWAYAT_ID");

			$i++;
		}
		echo json_encode($arr_json);
	}

	function cari_pegawai_kenaikan_pangkat_reguler()
	{
		$this->load->model("PendidikanRiwayat");
		$this->load->model('SatuanKerja');
		
		$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
		
		$reqId= $this->input->get("reqId");
		$reqJenis= $this->input->get("reqJenis");
        $reqMode= $this->input->get("reqMode");
        $reqKpJenis= $this->input->get("reqKpJenis");

		$set= new PendidikanRiwayat();
		$statement= " AND A.STATUS IS NULL";
		$statement.= " AND UPPER(A.NIP_BARU) LIKE '%".strtoupper(str_replace(" ", "", $search_term))."%' ";
		
		if($reqSatuanKerjaId == "")
		{
			$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
		}
		
		if($reqSatuanKerjaId == ""){}
		else
		{
			$skerja= new SatuanKerja();
			$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
			unset($skerja);
			//echo $reqSatuanKerjaId;exit;
			$statement.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			//$statement.= " AND ( A.SATUAN_KERJA_ID = ANY( AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$reqSatuanKerjaId.") ) OR A.SATUAN_KERJA_ID = ".$reqSatuanKerjaId." )";
		}
		$statement.= " AND A.PEGAWAI_ID NOT IN (SELECT PEGAWAI_ID FROM persuratan.SURAT_MASUK_PEGAWAI WHERE JENIS_ID = 10 AND (STATUS_BERKAS NOT IN (10) OR STATUS_BERKAS IS NULL OR (STATUS_BERKAS = 10 AND (STATUS_SURAT_KELUAR NOT IN (3) OR STATUS_SURAT_KELUAR IS NULL ))))";
		
		// AND SATUAN_KERJA_PARENT_ID > 0
		//data dinas
		if($reqMode == "1")
		{
			$statement.= " 
			AND A.SATUAN_KERJA_ID IN
			(
			SELECT SATUAN_KERJA_ID FROM SATUAN_KERJA A WHERE TIPE_ID = 1
			AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")
			)";
		}
		
		if($reqKpJenis == "kpreguler")
			$statement.= " AND JR.JENIS_JABATAN_ID = '2'";
		elseif($reqKpJenis == "kpstruktural")
			$statement.= " AND JR.ESELON_ID IS NOT NULL AND JR.ESELON_ID != 99";
		elseif($reqKpJenis == "kpjft")
			$statement.= " AND JR.JENIS_JABATAN_ID = '3'";
		else
			$statement.= " AND 1=2";

		$set->selectByParamsPegawaiCariKenaikanPangkatReguler(array(), 10, 0, $statement);
		// echo $set->query;exit;
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("PEGAWAI_ID");
			$arr_json[$i]['label'] = $set->getField("NIP_BARU");
			$arr_json[$i]['desc'] = $set->getField("NIP_BARU")."<br/><label style='font-size:12px'>".$set->getField("NAMA_LENGKAP")."</label>";
			$arr_json[$i]['namapegawai'] = $set->getField("NAMA_LENGKAP");
			$arr_json[$i]['satuankerjaid'] = $set->getField("SATUAN_KERJA_ID");
			$arr_json[$i]['satuankerjanama'] = $set->getField("SATUAN_KERJA_NAMA");
			$arr_json[$i]['pangkatid'] = $set->getField("PANGKAT_ID");
			$arr_json[$i]['pangkatkode'] = $set->getField("PANGKAT_KODE");
			$arr_json[$i]['pangkattmt'] = dateToPageCheck($set->getField("PANGKAT_TMT"));
			$arr_json[$i]['pangkatth'] = $set->getField("PANGKAT_TH");
			$arr_json[$i]['pangkatbl'] = $set->getField("PANGKAT_BL");

			$arr_json[$i]['jabataneselon'] = $set->getField("JABATAN_ESELON");
			$arr_json[$i]['jabatannama'] = $set->getField("JABATAN_NAMA");
			$arr_json[$i]['jabatantmt'] = dateTimeToPageCheck($set->getField("JABATAN_TMT"));

			$arr_json[$i]['pendidikanama'] = $set->getField("PENDIDIKAN_JURUSAN_NAMA");
			$arr_json[$i]['pendidikantanggal'] = dateToPageCheck($set->getField("TANGGAL_STTB"));
			$arr_json[$i]['pendidikanstatusnama'] = $set->getField("STATUS_PENDIDIKAN_NAMA");

			$arr_json[$i]['jabatanriwayatid'] = $set->getField("JABATAN_RIWAYAT_ID");
			$arr_json[$i]['pendidikanriwayatid'] = $set->getField("PENDIDIKAN_RIWAYAT_ID");
			$arr_json[$i]['gajiriwayatid'] = $set->getField("GAJI_RIWAYAT_ID");
			$arr_json[$i]['pangkatriwayatid'] = $set->getField("PANGKAT_RIWAYAT_ID");
			
			$i++;
		}
		echo json_encode($arr_json);
	}

	function combokenaikanpangkat()
	{
		$this->load->model("PendidikanRiwayat");
		
		$reqId= $this->input->get("reqId");
		
		$set = new PendidikanRiwayat();
		$statement= " AND A.STATUS_PENDIDIKAN = '3' AND A.PEGAWAI_ID = ".$reqId;
		$set->selectByParams(array(), -1,-1, $statement);
		// echo $set->query;exit();
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("PENDIDIKAN_RIWAYAT_ID");
			$arr_json[$i]['text'] = $set->getField("PENDIDIKAN_JURUSAN_NAMA");	
			$i++;
		}
		echo json_encode($arr_json);
	}

}
?>