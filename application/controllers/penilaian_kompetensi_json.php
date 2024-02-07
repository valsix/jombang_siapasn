<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class penilaian_kompetensi_json extends CI_Controller {

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

		$this->load->model('PenilaianKompetensi');
		
		$set = new PenilaianKompetensi();
		
		$reqId= $this->input->post("reqId");
		$reqMode= $this->input->post("reqMode");
		$reqRowId= $this->input->post('reqRowId');

		$reqJabatanRiwayatId= $this->input->post('reqJabatanRiwayatId');
		$reqTanggalKompetensi= $this->input->post('reqTanggalKompetensi');
		$reqTanggalMulai= $this->input->post('reqTanggalMulai');
		$reqTanggalSelesai= $this->input->post('reqTanggalSelesai');
		$reqTahun= $this->input->post('reqTahun');
		$reqAsesor= $this->input->post('reqAsesor');
		
		$reqIntegritasNilai= $this->input->post('reqIntegritasNilai');
		$reqKerjasamaNilai= $this->input->post('reqKerjasamaNilai');
		$reqKomunikasiNilai= $this->input->post('reqKomunikasiNilai');
		$reqOrientasiNilai= $this->input->post('reqOrientasiNilai');
		$reqPelayananPublikNilai= $this->input->post('reqPelayananPublikNilai');
		$reqPengembanganDiriNilai= $this->input->post('reqPengembanganDiriNilai');
		$reqKelolaPerubahanNilai= $this->input->post('reqKelolaPerubahanNilai');
		$reqAmbilKeputusanNilai= $this->input->post('reqAmbilKeputusanNilai');
		$reqPerekatBangsaNilai= $this->input->post('reqPerekatBangsaNilai');
		$reqSkorKompetensi= $this->input->post('reqSkorKompetensi');
		$reqNilaiIndeksKompetensi= $this->input->post('reqNilaiIndeksKompetensi');
		$reqKesimpulan= $this->input->post('reqKesimpulan');
		$reqPenyelenggara= $this->input->post('reqPenyelenggara');
		$reqDeskripsi= $this->input->post('reqDeskripsi');

		$set->setField("JABATAN_RIWAYAT_ID", ValToNullDB($reqJabatanRiwayatId));
		$set->setField('TANGGAL_KOMPETENSI', dateToDBCheck($reqTanggalKompetensi));
		$set->setField('TANGGAL_MULAI', dateToDBCheck($reqTanggalMulai));
		$set->setField('TANGGAL_SELESAI', dateToDBCheck($reqTanggalSelesai));
		$set->setField('TAHUN', $reqTahun);
		$set->setField('ASESOR', $reqAsesor);

		$set->setField("INTEGRITAS_NILAI", ValToNullDB(CommaToDot($reqIntegritasNilai)));
		$set->setField("KERJASAMA_NILAI", ValToNullDB(CommaToDot($reqKerjasamaNilai)));
		$set->setField("KOMUNIKASI_NILAI", ValToNullDB(CommaToDot($reqKomunikasiNilai)));
		$set->setField("ORIENTASI_NILAI", ValToNullDB(CommaToDot($reqOrientasiNilai)));
		$set->setField("PELAYANAN_PUBLIK_NILAI", ValToNullDB(CommaToDot($reqPelayananPublikNilai)));
		$set->setField("PENGEMBANGAN_DIRI_NILAI", ValToNullDB(CommaToDot($reqPengembanganDiriNilai)));
		$set->setField("KELOLA_PERUBAHAN_NILAI", ValToNullDB(CommaToDot($reqKelolaPerubahanNilai)));
		$set->setField("AMBIL_KEPUTUSAN_NILAI", ValToNullDB(CommaToDot($reqAmbilKeputusanNilai)));
		$set->setField("PEREKAT_BANGSA_NILAI", ValToNullDB(CommaToDot($reqPerekatBangsaNilai)));
		$set->setField("SKOR_KOMPETENSI", ValToNullDB(CommaToDot($reqSkorKompetensi)));

		$set->setField('NILAI_INDEKS_KOMPETENSI', setQuote($reqNilaiIndeksKompetensi, '1'));
		$set->setField('KESIMPULAN', setQuote($reqKesimpulan, '1'));
		$set->setField('PENYELENGGARA', setQuote($reqPenyelenggara, '1'));
		$set->setField('DESKRIPSI', setQuote($reqDeskripsi, '1'));

		$set->setField('PEGAWAI_ID', $reqId);
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_CREATE_USER", ValToNullDB($this->LOGIN_PEGAWAI_ID));

		$infosimpan= "";
		if($reqMode == "insert")
		{
			if($set->insert())
			{
				$reqRowId= $set->id;
				$infosimpan= "1";
			}
		}
		else
		{
			$set->setField('PENILAIAN_KOMPETENSI_ID', $reqRowId);
			if($set->update())
			{
				$infosimpan= "1";
			}
		}
		// echo $set->query;exit;

		if($infosimpan == "1")
		{
			// untuk simpan file
			$vpost= $this->input->post();
			$vsimpanfilepegawai= new globalfilepegawai();
			$vsimpanfilepegawai->simpanfilepegawai($vpost, $reqRowId, $reqLinkFile);
			
			echo $reqRowId."-Data berhasil disimpan.";
		}
		else
		{
			echo "xxx-Data gagal disimpan.";	
		}
	}
	
	function delete()
	{
		$this->load->model('PenilaianKompetensi');
		$set = new PenilaianKompetensi();
		
		$reqId =  $this->input->get('reqId');
		$reqMode =  $this->input->get('reqMode');
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		$set->setField("PENILAIAN_KOMPETENSI_ID", $reqId);
		
		if($reqMode == "diklat_kursus_0")
		{
			$set->setField("STATUS", "1");
			if($set->updateStatus())
				echo "Data berhasil dihapus.";
			else
				echo "Data gagal dihapus.";	
		}
		elseif($reqMode == "diklat_kursus_1")
		{
			$set->setField("STATUS", "2");
			if($set->updateStatus())
				echo "Data berhasil di aktifkan.";
			else
				echo "Data gagal di aktifkan.";	
		}
	}

	function log($riwayatId) 
	{	
		$this->load->model('PenilaianKompetensiLog');

		$set = new PenilaianKompetensiLog();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		
		$aColumns = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "DIKLAT_TEKNIS_ID");
		$aColumnsAlias = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "DIKLAT_TEKNIS_ID");
		

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
		
		$arrayWhere = array("DIKLAT_TEKNIS_ID" => $riwayatId);
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


	function jabatanriwayat() 
	{
		$this->load->model("JabatanRiwayat");
		$reqPegawaiId= $this->input->get("reqPegawaiId");
		$reqTanggal= $this->input->get("reqTanggal");

		$set= new JabatanRiwayat();

		$statement.= " AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2') ";
		$statement.= " AND A.PEGAWAI_ID = ".$reqPegawaiId." AND TO_DATE('".dateToPageCheck($reqTanggal)."', 'YYYY-MM-DD') >= TO_DATE(TO_CHAR(A.TMT_JABATAN, 'YYYY-MM-DD'), 'YYYY-MM-DD')";


		$set->selectByParams(array(), 1, 0, $statement);
		// echo $set->query;exit;

		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['JABATAN_RIWAYAT_ID'] = $set->getField("JABATAN_RIWAYAT_ID");
			$arr_json[$i]['JABATAN_RIWAYAT_NAMA'] = $set->getField("NAMA");
			$arr_json[$i]['JABATAN_RIWAYAT_TMT'] = dateTimeToPageCheck($set->getField("TMT_JABATAN"));
			$arr_json[$i]['JABATAN_RIWAYAT_UNIT_KERJA'] = $set->getField("SATKER_NAMA");
			$i++;
		}
		echo json_encode($arr_json);
	}

}
?>