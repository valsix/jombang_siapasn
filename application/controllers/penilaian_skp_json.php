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

		$this->load->model('PenilaianSkp');
		$set = new PenilaianSkp();
		
		$reqId = $this->input->post("reqId");
		$reqRowId = $this->input->post("reqRowId");
		$reqMode = $this->input->post("reqMode");

		$reqPenilaianSkpDinilaiNama = $this->input->post('reqPenilaianSkpDinilaiNama');
		$reqPenilaianSkpDinilaiNip = $this->input->post('reqPenilaianSkpDinilaiNip');
		$reqSatkerAtasan = $this->input->post('reqSatkerAtasan');
		$reqPenilaianSkpDinilaiId = $this->input->post('reqPenilaianSkpDinilaiId');

		$reqTahun = $this->input->post('reqTahun');
		$reqPegawaiJenisJabatan = $this->input->post('reqPegawaiJenisJabatan');
		$reqPegawaiUnorNama = $this->input->post('reqPegawaiUnorNama');
		$reqPegawaiUnorId = $this->input->post('reqPegawaiUnorId');

		$reqPenilaianSkpPenilaiId = $this->input->post('reqPenilaianSkpPenilaiId');
		$reqPenilaianSkpPenilaiNama = $this->input->post('reqPenilaianSkpPenilaiNama');
		$reqPenilaianSkpPenilaiNip = $this->input->post('reqPenilaianSkpPenilaiNip');
		$reqPenilaianSkpPenilaiJabatanNama = $this->input->post('reqPenilaianSkpPenilaiJabatanNama');
		$reqPenilaianSkpPenilaiUnorNama = $this->input->post('reqPenilaianSkpPenilaiUnorNama');
		$reqPenilaianSkpPenilaiPangkatId = $this->input->post('reqPenilaianSkpPenilaiPangkatId');


		$reqPenilaianSkpAtasanId = $this->input->post('reqPenilaianSkpAtasanId');
		$reqPenilaianSkpAtasanNama = $this->input->post('reqPenilaianSkpAtasanNama');
		$reqPenilaianSkpAtasanNip = $this->input->post('reqPenilaianSkpAtasanNip');
		$reqPenilaianSkpAtasanJabatanNama = $this->input->post('reqPenilaianSkpAtasanJabatanNama');
		$reqPenilaianSkpAtasanUnorNama = $this->input->post('reqPenilaianSkpAtasanUnorNama');
		$reqPenilaianSkpAtasanPangkatId = $this->input->post('reqPenilaianSkpAtasanPangkatId');

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

		
		$reqPegawaiJenisJabatan2 = $this->input->post('reqPegawaiJenisJabatan2');
		$reqPegawaiUnorNama2 = $this->input->post('reqPegawaiUnorNama2');
		$reqPegawaiUnorId2 = $this->input->post('reqPegawaiUnorId2');
		
		$reqPenilaianSkpPenilaiId2 = $this->input->post("reqPenilaianSkpPenilaiId2");
		$reqPenilaianSkpPenilaiNama2 = $this->input->post('reqPenilaianSkpPenilaiNama2');
		$reqPenilaianSkpPenilaiNip2 = $this->input->post('reqPenilaianSkpPenilaiNip2');
		$reqPenilaianSkpPenilaiJabatanNama2 = $this->input->post('reqPenilaianSkpPenilaiJabatanNama2');
		$reqPenilaianSkpPenilaiUnorNama2 = $this->input->post('reqPenilaianSkpPenilaiUnorNama2');
		$reqPenilaianSkpPenilaiPangkatId2 = $this->input->post('reqPenilaianSkpPenilaiPangkatId2');

		$reqPenilaianSkpAtasanId2 = $this->input->post("reqPenilaianSkpAtasanId2");
		$reqPenilaianSkpAtasanNama2 = $this->input->post('reqPenilaianSkpAtasanNama2');
		$reqPenilaianSkpAtasanNip2 = $this->input->post('reqPenilaianSkpAtasanNip2');
		$reqPenilaianSkpAtasanJabatanNama2 = $this->input->post('reqPenilaianSkpAtasanJabatanNama2');
		$reqPenilaianSkpAtasanUnorNama2 = $this->input->post('reqPenilaianSkpAtasanUnorNama2');
		$reqPenilaianSkpAtasanPangkatId2 = $this->input->post('reqPenilaianSkpAtasanPangkatId2');

		$reqSkpNilai2 = $this->input->post('reqSkpNilai2');
		$reqSkpHasil2 = $this->input->post('reqSkpHasil2');
		$reqOrientasiNilai2 = $this->input->post('reqOrientasiNilai2'); 
		$reqKomitmenNilai2 = $this->input->post('reqKomitmenNilai2'); 
		$reqKerjasamaNilai2 = $this->input->post('reqKerjasamaNilai2'); 
		$reqKepemimpinanNilai2 = $this->input->post('reqKepemimpinanNilai2');
		$reqInisiatifkerjaNilai2 = $this->input->post('reqInisiatifkerjaNilai2');
		$reqJumlahNilai2= $this->input->post('reqJumlahNilai2'); 
		$reqRataNilai2= $this->input->post('reqRataNilai2');
		
		$reqNilaiHasilKerja= $this->input->post('reqNilaiHasilKerja'); 
		$reqNilaiPerilakuKerja= $this->input->post('reqNilaiPerilakuKerja');	


		
		// // $set->setField('NAMA', $reqNama);

		$set->setField("PEGAWAI_ID", ValToNullDB($reqId));
		$set->setField("TAHUN", ValToNullDB($reqTahun));

		$set->setField("JENIS_JABATAN_DINILAI", $reqPegawaiJenisJabatan);
		$set->setField("PEGAWAI_UNOR_NAMA", $reqPegawaiUnorNama);
		$set->setField("PEGAWAI_UNOR_ID", ValToNullDB($reqPegawaiUnorId));

		$set->setField("PEGAWAI_PEJABAT_PENILAI_ID", ValToNullDB($reqPenilaianSkpPenilaiId));
		$set->setField("PEGAWAI_PEJABAT_PENILAI_NIP", $reqPenilaianSkpPenilaiNip);
		$set->setField("PEGAWAI_PEJABAT_PENILAI_NAMA", $reqPenilaianSkpPenilaiNama);
		$set->setField("PEGAWAI_PEJABAT_PENILAI_JABATAN_NAMA", $reqPenilaianSkpPenilaiJabatanNama);
		$set->setField("PEGAWAI_PEJABAT_PENILAI_UNOR_NAMA", $reqPenilaianSkpPenilaiUnorNama);
		$set->setField("PEGAWAI_PEJABAT_PENILAI_PANGKAT_ID", ValToNullDB($reqPenilaianSkpPenilaiPangkatId));

		$set->setField("PEGAWAI_ATASAN_PEJABAT_ID", ValToNullDB($reqPenilaianSkpAtasanId));
		$set->setField("PEGAWAI_ATASAN_PEJABAT_NIP", $reqPenilaianSkpAtasanNip);
		$set->setField("PEGAWAI_ATASAN_PEJABAT_NAMA", $reqPenilaianSkpAtasanNama);
		$set->setField("PEGAWAI_ATASAN_PEJABAT_JABATAN_NAMA", $reqPenilaianSkpAtasanJabatanNama);
		$set->setField("PEGAWAI_ATASAN_PEJABAT_UNOR_NAMA", $reqPenilaianSkpAtasanUnorNama);
		$set->setField("PEGAWAI_ATASAN_PEJABAT_PANGKAT_ID", ValToNullDB($reqPenilaianSkpAtasanPangkatId));

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

		// $set->setField("SKP_NILAI", ValToNullDB($reqSkpNilai));
		// $set->setField("SKP_HASIL", ValToNullDB($reqSkpHasil));
		// $set->setField("ORIENTASI_NILAI", ValToNullDB($reqOrientasiNilai));
		// $set->setField("INTEGRITAS_NILAI", ValToNullDB($reqIntegritasNilai));
		// $set->setField("KOMITMEN_NILAI", ValToNullDB($reqKomitmenNilai));
		// $set->setField("DISIPLIN_NILAI", ValToNullDB($reqDisiplinNilai));
		// $set->setField("KERJASAMA_NILAI", ValToNullDB($reqKerjasamaNilai));
		// $set->setField("KEPEMIMPINAN_NILAI", ValToNullDB($reqKepemimpinanNilai));
		// $set->setField("PERILAKU_NILAI", ValToNullDB($reqPerilakuNilai));
		// $set->setField("PERILAKU_HASIL", ValToNullDB($reqPerilakuHasil));
		// $set->setField("PRESTASI_HASIL", ValToNullDB($reqPrestasiHasil));
		// $set->setField("JUMLAH_NILAI", ValToNullDB($reqJumlahNilai));
		// $set->setField("RATA_NILAI", ValToNullDB($reqRataNilai));
		$set->setField("KEBERATAN", $reqKeberatan);
		$set->setField("KEBERATAN_TANGGAL", dateToDBCheck($reqTanggalKeberatan));
		$set->setField("TANGGAPAN", $reqTanggapan);
		$set->setField("TANGGAPAN_TANGGAL", dateToDBCheck($reqTanggalTanggapan));
		$set->setField("KEPUTUSAN", $reqKeputusan);
		$set->setField("KEPUTUSAN_TANGGAL", dateToDBCheck($reqTanggalKeputusan));
		$set->setField("REKOMENDASI", $reqRekomendasi);
		
		//SEMESTER 2
		$set->setField("JENIS_JABATAN_DINILAI2", ValToNullDB($reqPegawaiJenisJabatan2));
		$set->setField("PEGAWAI_UNOR_NAMA2", $reqPegawaiUnorNama2);
		$set->setField("PEGAWAI_UNOR_ID2", ValToNullDB($reqPegawaiUnorId2));

		$set->setField("PEGAWAI_PEJABAT_PENILAI_ID2", ValToNullDB($reqPenilaianSkpPenilaiId2));
		$set->setField("PEGAWAI_PEJABAT_PENILAI_NIP2", $reqPenilaianSkpPenilaiNip2);
		$set->setField("PEGAWAI_PEJABAT_PENILAI_NAMA2", $reqPenilaianSkpPenilaiNama2);
		$set->setField("PEGAWAI_PEJABAT_PENILAI_JABATAN_NAMA2", $reqPenilaianSkpPenilaiJabatanNama2);
		$set->setField("PEGAWAI_PEJABAT_PENILAI_UNOR_NAMA2", $reqPenilaianSkpPenilaiUnorNama2);
		$set->setField("PEGAWAI_PEJABAT_PENILAI_PANGKAT_ID2", ValToNullDB($reqPenilaianSkpPenilaiPangkatId2));

		$set->setField("PEGAWAI_ATASAN_PEJABAT_ID2", ValToNullDB($reqPenilaianSkpAtasanId2));
		$set->setField("PEGAWAI_ATASAN_PEJABAT_NIP2", $reqPenilaianSkpAtasanNip2);
		$set->setField("PEGAWAI_ATASAN_PEJABAT_NAMA2", $reqPenilaianSkpAtasanNama2);
		$set->setField("PEGAWAI_ATASAN_PEJABAT_JABATAN_NAMA2", $reqPenilaianSkpAtasanJabatanNama2);
		$set->setField("PEGAWAI_ATASAN_PEJABAT_UNOR_NAMA2", $reqPenilaianSkpAtasanUnorNama2);
		$set->setField("PEGAWAI_ATASAN_PEJABAT_PANGKAT_ID2", ValToNullDB($reqPenilaianSkpAtasanPangkatId2));

		$set->setField('SKP_NILAI2', ValToNullDB(CommaToDot($reqSkpNilai2)));
		$set->setField("SKP_HASIL2", ValToNullDB(CommaToDot($reqSkpHasil2)));
		$set->setField("ORIENTASI_NILAI2", ValToNullDB(CommaToDot($reqOrientasiNilai2)));
		$set->setField("KOMITMEN_NILAI2", ValToNullDB(CommaToDot($reqKomitmenNilai2)));
		$set->setField("KERJASAMA_NILAI2", ValToNullDB(CommaToDot($reqKerjasamaNilai2)));
		$set->setField("KEPEMIMPINAN_NILAI2", ValToNullDB(CommaToDot($reqKepemimpinanNilai2)));
		$set->setField("INISIATIFKERJA_NILAI2", ValToNullDB(CommaToDot($reqInisiatifkerjaNilai2)));
		$set->setField("PERILAKU_NILAI2", ValToNullDB(CommaToDot($reqPerilakuNilai2)));
		$set->setField("PERILAKU_HASIL2", ValToNullDB(CommaToDot($reqPerilakuHasil2)));
		$set->setField("JUMLAH_NILAI2", ValToNullDB(CommaToDot($reqJumlahNilai2)));
		$set->setField("RATA_NILAI2", ValToNullDB(CommaToDot($reqRataNilai2)));

		$set->setField("NILAI_HASIL_KERJA", ValToNullDB($reqNilaiHasilKerja));
		$set->setField("NILAI_HASIL_PERILAKU", ValToNullDB($reqNilaiPerilakuKerja));


		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("LAST_DATE", "NOW()");
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));

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
			$set->setField('PENILAIAN_SKP_ID', $reqRowId); 
			if($set->update())
			{
				$infosimpan= "1";
			}
		}

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