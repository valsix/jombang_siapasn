<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class penilaian_skp_json extends CI_Controller {

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
	
	function add()
	{
		$this->load->model('validasi/PenilaianSkp');
		$set = new PenilaianSkp();
		
		$reqTempValidasiHapusId= $this->input->post("reqTempValidasiHapusId");
		$reqTempValidasiId= $this->input->post("reqTempValidasiId");
		$reqStatusValidasi= $this->input->post("reqStatusValidasi");
		$reqId = $this->input->post("reqId");
		$reqRowId = $this->input->post("reqRowId");
		$reqMode = $this->input->post("reqMode");

		if(empty($reqStatusValidasi))
		{
			echo "xxx-Isikan terlebih dahulu Status Klarifikasi.";
			exit;
		}
		elseif($reqStatusValidasi == "2")
		{
			if(empty($reqTempValidasiId) && !empty($reqTempValidasiHapusId))
			{
				$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiHapusId);
				$reqsimpan= "";
				if($set->deletehapusdata())
				{
					$reqsimpan= "1";
				}

				if($reqsimpan == "1")
				{
					echo $reqRowId."-Data berhasil disimpan.";
				}
				else
					echo "xxx-Data gagal disimpan.";
			}
			else
			{
				$set->setField('VALIDASI', $reqStatusValidasi);
				$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
				$set->setField("LAST_USER", $this->LOGIN_USER);
				$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
				$set->setField("LAST_DATE", "NOW()");
				$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);

				$reqsimpan= "";
				if($set->updatevalidasi())
				{
					$reqsimpan= "1";
				}

				if($reqsimpan == "1")
				{
					echo $reqRowId."-Data berhasil disimpan.";
				}
				else
					echo "xxx-Data gagal disimpan.";
			}


		}
		else
		{

			$reqSkpNilai = $this->input->post('reqSkpNilai');
			$reqSkpHasil = $this->input->post('reqSkpHasil');
			$reqOrientasiNilai = $this->input->post('reqOrientasiNilai'); 
			$reqIntegritasNilai = $this->input->post('reqIntegritasNilai');
			$reqKomitmenNilai = $this->input->post('reqKomitmenNilai'); 
			$reqDisiplinNilai = $this->input->post('reqDisiplinNilai'); 
			$reqKerjasamaNilai = $this->input->post('reqKerjasamaNilai'); 
			$reqKepemimpinanNilai = $this->input->post('reqKepemimpinanNilai'); 
			$reqJumlahNilai = $this->input->post('reqJumlahNilai');
			$reqRataNilai = $this->input->post('reqRataNilai'); 
			$reqPerilakuNilai = $this->input->post('reqPerilakuNilai'); 
			$reqPerilakuHasil = $this->input->post('reqPerilakuHasil'); 
			$reqPrestasiNilai = $this->input->post('reqPrestasiNilai'); 
			$reqPrestasiHasil = $this->input->post('reqPrestasiHasil'); 
			$reqKeberatan = $this->input->post('reqKeberatan');
			$reqTanggalKeberatan = $this->input->post('reqTanggalKeberatan'); 
			$reqTanggapan = $this->input->post('reqTanggapan'); 
			$reqTanggalTanggapan = $this->input->post('reqTanggalTanggapan'); 
			$reqKeputusan = $this->input->post('reqKeputusan'); 
			$reqTanggalKeputusan = $this->input->post('reqTanggalKeputusan');
			$reqRekomendasi = $this->input->post('reqRekomendasi'); 

			$reqPenilaianSkpDinilaiNama = $this->input->post('reqPenilaianSkpDinilaiNama');
			$reqPenilaianSkpDinilaiNip = $this->input->post('reqPenilaianSkpDinilaiNip');
			$reqSatkerAtasan = $this->input->post('reqSatkerAtasan');

			$reqPenilaianSkpDinilaiId = $this->input->post('reqPenilaianSkpDinilaiId');
			$reqPenilaianSkpPenilaiId = $this->input->post('reqPenilaianSkpPenilaiId');
			$reqPenilaianSkpAtasanId = $this->input->post('reqPenilaianSkpAtasanId');
			$reqPenilaianSkpPenilaiNama = $this->input->post('reqPenilaianSkpPenilaiNama');
			$reqPenilaianSkpPenilaiNip = $this->input->post('reqPenilaianSkpPenilaiNip');
			$reqPenilaianSkpAtasanNama = $this->input->post('reqPenilaianSkpAtasanNama');
			$reqPenilaianSkpAtasanNip = $this->input->post('reqPenilaianSkpAtasanNip');
			$reqTahun = $this->input->post('reqTahun');
			
			// // $set->setField('NAMA', $reqNama);

			$set->setField("PEGAWAI_ID", ValToNullDB($reqId));
			$set->setField("TAHUN", ValToNullDB($reqTahun));
			$set->setField("PEGAWAI_PEJABAT_PENILAI_ID", ValToNullDB($reqPenilaianSkpPenilaiId));
			$set->setField("PEGAWAI_ATASAN_PEJABAT_ID", ValToNullDB($reqPenilaianSkpAtasanId));

			$set->setField('SKP_NILAI', ValToNullDB(CommaToDot($reqSkpNilai)));
			$set->setField("SKP_HASIL", ValToNullDB(CommaToDot($reqSkpHasil)));
			$set->setField("ORIENTASI_NILAI", ValToNullDB(CommaToDot($reqOrientasiNilai)));
			$set->setField("INTEGRITAS_NILAI", ValToNullDB(CommaToDot($reqIntegritasNilai)));
			$set->setField("KOMITMEN_NILAI", ValToNullDB(CommaToDot($reqKomitmenNilai)));
			$set->setField("DISIPLIN_NILAI", ValToNullDB(CommaToDot($reqDisiplinNilai)));
			$set->setField("KERJASAMA_NILAI", ValToNullDB(CommaToDot($reqKerjasamaNilai)));
			$set->setField("KEPEMIMPINAN_NILAI", ValToNullDB(CommaToDot($reqKepemimpinanNilai)));
			$set->setField("PERILAKU_NILAI", ValToNullDB(CommaToDot($reqPerilakuNilai)));
			$set->setField("PERILAKU_HASIL", ValToNullDB(CommaToDot($reqPerilakuHasil)));
			$set->setField("PRESTASI_HASIL", ValToNullDB(CommaToDot($reqPrestasiHasil)));
			$set->setField("JUMLAH_NILAI", ValToNullDB(CommaToDot($reqJumlahNilai)));
			$set->setField("RATA_NILAI", ValToNullDB(CommaToDot($reqRataNilai)));
			$set->setField("KEBERATAN", $reqKeberatan);
			$set->setField("KEBERATAN_TANGGAL", dateToDBCheck($reqTanggalKeberatan));
			$set->setField("TANGGAPAN", $reqTanggapan);
			$set->setField("TANGGAPAN_TANGGAL", dateToDBCheck($reqTanggalTanggapan));
			$set->setField("KEPUTUSAN", $reqKeputusan);
			$set->setField("KEPUTUSAN_TANGGAL", dateToDBCheck($reqTanggalKeputusan));
			$set->setField("REKOMENDASI", $reqRekomendasi);
			$set->setField("PEGAWAI_PEJABAT_PENILAI_NIP", $reqPenilaianSkpPenilaiNip);
			$set->setField("PEGAWAI_PEJABAT_PENILAI_NAMA", $reqPenilaianSkpPenilaiNama);
			$set->setField("PEGAWAI_ATASAN_PEJABAT_NIP", $reqPenilaianSkpAtasanNip);
			$set->setField("PEGAWAI_ATASAN_PEJABAT_NAMA", $reqPenilaianSkpAtasanNama);

			$set->setField('PEGAWAI_ID', $reqId);
			$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
			$set->setField("LAST_USER", $this->LOGIN_USER);
			$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
			$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			$set->setField("LAST_DATE", "NOW()");
			$set->setField('PENILAIAN_SKP_ID', $reqRowId);
			$set->setField('VALIDASI', $reqStatusValidasi);
			$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);

			$reqsimpan= "";
			if(!empty($reqTempValidasiId))
			{
				if($set->update())
				{
					$set->updatetanggalvalidasi();
					$reqsimpan= "1";
				}
			}
			elseif(empty($reqTempValidasiId) && !empty($reqTempValidasiHapusId))
			{
				$set->setField('VALIDASI', $reqStatusValidasi);
				$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
				$set->setField("LAST_USER", $this->LOGIN_USER);
				$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
				$set->setField("LAST_DATE", "NOW()");
				$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiHapusId);

				$reqsimpan= "";
				if($set->updatevalidasihapusdata())
				{
					$reqsimpan= "1";
				}
			}

			if($reqsimpan == "1")
			{
				echo $reqRowId."-Data berhasil disimpan.";
			}
			else
				echo "xxx-Data gagal disimpan.";
		}

				// echo $set->query;exit;


	}

	function delete()
	{
		$this->load->model('PenilaianSkp');
		$set = new PenilaianSkp();

		$reqId =  $this->input->get('reqId');
		$reqMode =  $this->input->get('reqMode');
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		$set->setField("PENILAIAN_SKP_ID", $reqId);

		if($reqMode == "penilaian_skp_0")
		{
			$set->setField("STATUS", "1");
			if($set->updateStatus())
				$arrJson["PESAN"] = "Data berhasil dihapus.";
			else
				$arrJson["PESAN"] = "Data gagal dihapus.";	
		}
		elseif($reqMode == "penilaian_skp_1")
		{
			$set->setField("STATUS", "2");
			if($set->updateStatus())
				$arrJson["PESAN"] = "Data berhasil dihapus.";
			else
				$arrJson["PESAN"] = "Data gagal dihapus.";	
		}
		// echo $set->query;exit;	

		echo json_encode($arrJson);
	}

	function json() 
	{
		$this->load->model('PenilaianSkp');
		$this->load->model('SatuanKerja');

		$set = new PenilaianSkp();
		
		$reqMode= $this->input->get("reqMode");
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$tempSatuanKerjaId= $reqSatuanKerjaId;
		$reqTipePegawaiId= $this->input->get("reqTipePegawaiId");
		$reqBulan= $this->input->get("reqBulan");
		$reqTahun= $this->input->get("reqTahun");
		$reqPangkatId= $this->input->get("reqPangkatId");
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);

		$aColumns = array("NIP_BARU", "NAMA_LENGKAP", "SKP_NILAI", "SKP_HASIL", "ORIENTASI_NILAI", "INTEGRITAS_NILAI", "KOMITMEN_NILAI", "DISIPLIN_NILAI", "KERJASAMA_NILAI", "KEPEMIMPINAN_NILAI", "JUMLAH_NILAI", "RATA_NILAI", "PERILAKU_HASIL", "PRESTASI_HASIL", "SATUAN_KERJA_NAMA", "SATUAN_KERJA_INDUK");

		$aColumnsAlias = array("NIP_BARU", "NAMA_LENGKAP", "SKP_NILAI", "SKP_HASIL", "ORIENTASI_NILAI", "INTEGRITAS_NILAI", "KOMITMEN_NILAI", "DISIPLIN_NILAI", "KERJASAMA_NILAI", "KEPEMIMPINAN_NILAI", "JUMLAH_NILAI", "RATA_NILAI", "PERILAKU_HASIL", "PRESTASI_HASIL", "SATUAN_KERJA_NAMA", "SATUAN_KERJA_INDUK");

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
		
		if($reqTipePegawaiId ==""){}
		else
		{
			$statement .= " AND A.TIPE_PEGAWAI_ID LIKE '".$reqTipePegawaiId."%'";
		}
		
		if($reqPangkatId ==''){}
		else
		{
			$statement .= " AND A.PANGKAT_ID = '".$reqPangkatId."'";
		}
		
		$statement .= " AND PSKP.TAHUN = '".$reqTahun."'";
		$searchJson= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%' ) ";
		$allRecord = $set->getCountByParamsRekap(array(), $statement);
		// echo $allRecord;

		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParamsRekap(array(), $statement.$searchJson);
		//echo $set->query;exit;
		$sOrder= "";
		$set->selectByParamsRekap(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
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