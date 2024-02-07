<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/image.func.php");

class klarifikasi_json extends CI_Controller {

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

	function cari_pegawai()
	{
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/SatuanKerja');
		
		$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
		
		$reqId= $this->input->get("reqId");
        $reqMode= $this->input->get("reqMode");
        $reqTanggal= $this->input->get("reqTanggal");
		
		$set= new Klarifikasi();
		$statement.= " AND UPPER(A.NIP_BARU) LIKE '%".strtoupper(str_replace(" ", "", $search_term))."%' ";

		if(!empty($reqTanggal))
		{
			$statement.= " AND 
			(
				A.STATUS_PEGAWAI_ID IN (1,2)
				OR
				(
					A.STATUS_PEGAWAI_ID IN (3,4,5) AND TO_DATE(TO_CHAR(A.PEGAWAI_STATUS_TMT, 'YYYY-MM-DD'), 'YYYY/MM/DD') >= TO_DATE('".dateToPageCheck($reqTanggal)."','YYYY/MM/DD')
				)
			)";
		}
		
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
		}

		$set->selectByParamsPegawai(array(), 10, 0, $statement);
		// echo $set->query;exit;
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("PEGAWAI_ID");
			$arr_json[$i]['jabatanid'] = $set->getField("JABATAN_RIWAYAT_ID");
			$arr_json[$i]['pangkatid'] = $set->getField("PANGKAT_RIWAYAT_ID");
			$arr_json[$i]['label'] = $set->getField("NIP_BARU");
			$arr_json[$i]['desc'] = $set->getField("NIP_BARU")."<br/><label style='font-size:12px'>".$set->getField("NAMA_LENGKAP")."(".$set->getField("JABATAN_RIWAYAT_NAMA").")</label>";
			$arr_json[$i]['namajabatan'] = $set->getField("JABATAN_RIWAYAT_NAMA");
			$arr_json[$i]['namapegawai'] = $set->getField("NAMA_LENGKAP");
			$i++;
		}
		echo json_encode($arr_json);
	}

	function dinasluar()
	{
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/SatuanKerja');

		$set= new Klarifikasi;
		
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqTahun= $this->input->get("reqTahun");
		$reqBulan= $this->input->get("reqBulan");
		$reqPencarian= $this->input->get("reqPencarian");
		$reqKhususDinas= $this->input->get("reqKhususDinas");

		$aColumns = array("NOMOR_SURAT", "NIP_NAMA", "TANGGAL_MULAI", "TANGGAL_SELESAI", "LAMA", "STATUS_INFO", "UBAH_STATUS_INFO", "KETERANGAN", "ALASAN_TOLAK", "BUKTI_PENDUKUNG", "KLARIFIKASI_ID");
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
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY NOMOR_SURAT asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.TANGGAL_SURAT desc";
				 
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
				AND XXX.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
		}
		else
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND XXX.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
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
			$satuankerjakondisi.= " AND XXX.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}
		// echo $satuankerjakondisi;exit();

		$statement= " AND A.JENIS_KLARIFIKASI= 'klarifikasi_dinas_luar'";

		$searchJson = " AND ( UPPER(A.NOMOR_SURAT) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(presensi.info_pegawai_klarifikasi(A.KLARIFIKASI_ID)) LIKE '%".strtoupper($reqPencarian)."%' )";
		$allRecord = $set->getCountByParamsDinasLuar(array(), $satuankerjakondisi, $statement);
		//echo $allRecord;
		if($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsDinasLuar(array(), $satuankerjakondisi, $searchJson.$statement);
		
		$set->selectByParamsDinasLuar(array(), $dsplyRange, $dsplyStart, $satuankerjakondisi, $searchJson.$statement, $sOrder);
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
					$row[] = $set->getField("NIP_BARU")."<br/>".$set->getField("NAMA_LENGKAP");
				}
				elseif($aColumns[$i] == "TANGGAL_MULAI" || $aColumns[$i] == "TANGGAL_SELESAI")
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

	function dinasluaradd()
	{
		$this->load->library("FileHandler");
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/KlarifikasiRequired');
		$this->load->model('main/PermohonanFile');

		$reqId= $this->input->post('reqId');
		$reqJenis= "klarifikasi_dinas_luar";
		$infomenuid= "5102";

		$filedata= $_FILES["reqLinkFile"];
		$file= new FileHandler();

		$checkFile= $file->checkfile($filedata, 6);
		$namaLinkFile= $file->setlinkfile($filedata);

		// cek required upload
		$setdetil= new KlarifikasiRequired();
		$validasirequired= $setdetil->getCountByParams(array(), " AND A.MENU_ID = '".$infomenuid."' AND B.STATUS_UPLOAD = '1'");
		// echo $setdetil->query;exit;
		$infovalidasifile= "1";
		if($validasirequired > 0)
		{
			$infovalidasifile= "";
		}

		$jumlahfiledata= 0;
		if(!empty($reqId))
		{
			$statement= " AND A.PERMOHONAN_TABLE_NAMA = '".$reqJenis."' AND A.PERMOHONAN_TABLE_ID = ".$reqId;
			$setfile= new PermohonanFile();
			$jumlahfiledata= $setfile->getCountByParams(array(), $statement);
			unset($setfile);
		}
		
		$jumlahdata= 0;
		if(empty($namaLinkFile))
		{
			if($infovalidasifile == "1")
			{
				if($jumlahfiledata > 0){}
				else
				{
					echo "xxx-Anda belum upload file lampiran.";
					exit();
				}
			}
		}
		else
		{
			if(!empty($checkFile))
			{
				echo "xxx-File harus berformat (pdf/jpg/jpeg)";
				exit();
			}

			$sizeLinkFile= $file->checkmodifsizefile($filedata);
			if(!empty($sizeLinkFile))
			{
				echo "xxx-".$sizeLinkFile;
				exit();
			}

			$jumlahdata= count($filedata);
		}

		$reqMode= $this->input->post('reqMode');

		$reqTanggalAwal= $this->input->post('reqTanggalAwal');
		$reqTanggalAkhir= $this->input->post('reqTanggalAkhir');
		$reqNoSurat= $this->input->post('reqNoSurat');
		$reqTanggalSurat= $this->input->post('reqTanggalSurat');
		$reqKeterangan= $this->input->post('reqKeterangan');
		$reqUbahStatus= $this->input->post('reqUbahStatus');
		$reqStatus= $this->input->post('reqStatus');
		$reqAlasanTolak= $this->input->post('reqAlasanTolak');

		$reqPegawaiId= $this->input->post('reqPegawaiId');
		$reqPegawaiJabatanId= $this->input->post('reqPegawaiJabatanId');
		$reqPegawaiPangkatId= $this->input->post('reqPegawaiPangkatId');
		$reqJenisKlarifikasi= "DL";

		$set= new Klarifikasi();
        $set->setField("KLARIFIKASI_ID", $reqId);
        $set->setField("TANGGAL_SURAT", dateToDBCheck($reqTanggalSurat));
        $set->setField("TANGGAL_MULAI", dateToDBCheck($reqTanggalAwal));
        $set->setField("TANGGAL_SELESAI", dateToDBCheck($reqTanggalAkhir));
        $set->setField("JENIS_KLARIFIKASI", $reqJenis);
        $set->setField("KETERANGAN", setQuote($reqKeterangan));
        $set->setField("UBAH_STATUS", $reqUbahStatus);
        $set->setField("STATUS", $reqStatus);
        $set->setField("ALASAN_TOLAK", $reqAlasanTolak);
        $set->setField("KODE", $reqJenisKlarifikasi);
        $set->setField("NOMOR_SURAT", $reqNoSurat);
        $set->setField("LAST_CREATE_USER", $this->LOGIN_USER);
        $set->setField("LAST_CREATE_DATE", "CURRENT_TIMESTAMP");
        $set->setField("LAST_USER", $this->LOGIN_USER);
        $set->setField("LAST_UPDATE", "CURRENT_TIMESTAMP");
        $set->setField("LAST_UPDATE_USER", $this->LOGIN_USER);
        $set->setField("LAST_UPDATE_DATE", "CURRENT_TIMESTAMP");

		$simpan="";
		if($reqMode == "insert")
        {
            if($set->insert())
            {
            	$reqId= $set->id;
            	$simpan = "1";
            }
        }
        else
        {
        	if($set->update())
            {
            	$simpan = "1";
            }
        }

        if($simpan == "1")
        {
        	$set->setField("KLARIFIKASI_ID", $reqId);
        	if($set->deleteDetil())
            {
	        	for($i=0; $i < count($reqPegawaiId); $i++)
	        	{
	        		$set->setField("PEGAWAI_ID", $reqPegawaiId[$i]);
	        		$set->setField("JABATAN_TAMBAHAN_ID", ValToNullDB($reqPegawaiJabatanId[$i]));
	        		$set->setField("PANGKAT_RIWAYAT_ID", ValToNullDB($reqPegawaiPangkatId[$i]));
	        		if($set->insertDetil()){}
	        	}
            }
        }

        // proses hapus file
        $arrdatafile= array();
		$pfile = new PermohonanFile();
		$pfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqId, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis));
		$indexpfile=0;
		while ($pfile->nextRow()) 
		{
			$arrdatafile[$indexpfile]['NAMA'] = $pfile->getField("NAMA");
			$arrdatafile[$indexpfile]['LINK_FILE'] = $pfile->getField("LINK_FILE");
			$indexpfile++;
		}

        // simpan file sesuai format
        $folderfilesimpan= "uploads/".gettanggalperiode($reqTanggalAwal);
        if(file_exists($folderfilesimpan)){}
		else
		{
			makedirs($folderfilesimpan);
		}

		$penamaanfile= $reqId."_".str_replace("-", "", $reqTanggalAwal)."_dinasluar_";

		for($i=0; $i < $jumlahdata; $i++)
		{
			$namafile= $filedata["name"][$i];
			$fileType= $filedata["type"][$i];
			$datafileupload= $filedata["tmp_name"][$i];
			$filepath= $file->getExtension($namafile);

			if($namafile == ""){}
			else
			{
				$linkfile= $penamaanfile.md5($penamaanfile.$namafile).".".strtolower($filepath);
				$targetsimpan= $folderfilesimpan."/".$linkfile;

				if (move_uploaded_file($datafileupload, $targetsimpan))
				{
					$setfile= new PermohonanFile();
					$setfile->setField("PEGAWAI_ID", "dinasluar");
					$setfile->setField("PERMOHONAN_TABLE_NAMA", $reqJenis);
					$setfile->setField("PERMOHONAN_TABLE_ID", $reqId);
					$setfile->setField("NAMA", $linkfile);
					$setfile->setField("KETERANGAN", $namafile);
					$setfile->setField("LINK_FILE", $targetsimpan);
					$setfile->setField("TIPE", strtolower($fileType));
					$setfile->setField("LAST_USER", $this->LOGIN_USER);
					$setfile->setField("USER_LOGIN_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->setField("USER_LOGIN_CREATE_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->insert();
					unset($setfile);

					$arrdatafile[$indexpfile]['NAMA'] = $linkfile;
					$arrdatafile[$indexpfile]['LINK_FILE'] = $targetsimpan;
					$indexpfile++;
				}
			}
		}
        // exit;

        // proses hapus file
        for ($i=0; $i < $indexpfile; $i++)
        { 
			$infofilecheck=  $arrdatafile[$i]['NAMA'];
			$cfile= new PermohonanFile();
			$cfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqId, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis, "A.NAMA"=>setQuote($infofilecheck, "")));
			$cfile->firstRow();
			$infofilelokasi= $cfile->getField("LINK_FILE");
			if (empty($infofilelokasi))
			{
				unlink($infofilelokasi);
			}
		}

		if($simpan == "1")
			echo $reqId."-Data berhasil disimpan.";
		else
			echo "xxx-Data gagal disimpan.";
	}

	function diklat()
	{
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/SatuanKerja');

		$set= new Klarifikasi;
		
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqTahun= $this->input->get("reqTahun");
		$reqBulan= $this->input->get("reqBulan");
		$reqPencarian= $this->input->get("reqPencarian");
		$reqKhususDinas= $this->input->get("reqKhususDinas");

		$aColumns = array("NOMOR_SURAT", "NIP_NAMA", "TANGGAL_MULAI", "TANGGAL_SELESAI", "LAMA", "STATUS_INFO", "UBAH_STATUS_INFO", "KETERANGAN", "ALASAN_TOLAK", "BUKTI_PENDUKUNG", "KLARIFIKASI_ID");
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
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY NOMOR_SURAT asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.TANGGAL_SURAT desc";
				 
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
				AND XXX.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
		}
		else
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND XXX.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
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
			$satuankerjakondisi.= " AND XXX.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}
		// echo $satuankerjakondisi;exit();

		$statement= " AND A.JENIS_KLARIFIKASI= 'klarifikasi_diklat'";

		$searchJson = " AND ( UPPER(A.NOMOR_SURAT) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(presensi.info_pegawai_klarifikasi(A.KLARIFIKASI_ID)) LIKE '%".strtoupper($reqPencarian)."%' )";
		$allRecord = $set->getCountByParamsDiklat(array(), $satuankerjakondisi, $statement);
		//echo $allRecord;
		if($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsDiklat(array(), $satuankerjakondisi, $searchJson.$statement);
		
		$set->selectByParamsDiklat(array(), $dsplyRange, $dsplyStart, $satuankerjakondisi, $searchJson.$statement, $sOrder);
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
					$row[] = $set->getField("NIP_BARU")."<br/>".$set->getField("NAMA_LENGKAP");
				}
				elseif($aColumns[$i] == "TANGGAL_MULAI" || $aColumns[$i] == "TANGGAL_SELESAI")
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

	function diklatadd()
	{
		$this->load->library("FileHandler");
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/KlarifikasiRequired');
		$this->load->model('main/PermohonanFile');

		$reqId= $this->input->post('reqId');
		$reqJenis= "klarifikasi_diklat";
		$infomenuid= "5106";

		$filedata= $_FILES["reqLinkFile"];
		$file= new FileHandler();

		$checkFile= $file->checkfile($filedata, 6);
		$namaLinkFile= $file->setlinkfile($filedata);

		// cek required upload
		$setdetil= new KlarifikasiRequired();
		$validasirequired= $setdetil->getCountByParams(array(), " AND A.MENU_ID = '".$infomenuid."' AND B.STATUS_UPLOAD = '1'");
		// echo $setdetil->query;exit;
		$infovalidasifile= "1";
		if($validasirequired > 0)
		{
			$infovalidasifile= "";
		}

		$jumlahfiledata= 0;
		if(!empty($reqId))
		{
			$statement= " AND A.PERMOHONAN_TABLE_NAMA = '".$reqJenis."' AND A.PERMOHONAN_TABLE_ID = ".$reqId;
			$setfile= new PermohonanFile();
			$jumlahfiledata= $setfile->getCountByParams(array(), $statement);
			unset($setfile);
		}
		
		$jumlahdata= 0;
		if(empty($namaLinkFile))
		{
			if($infovalidasifile == "1")
			{
				if($jumlahfiledata > 0){}
				else
				{
					echo "xxx-Anda belum upload file lampiran.";
					exit();
				}
			}
		}
		else
		{
			if(!empty($checkFile))
			{
				echo "xxx-File harus berformat (pdf/jpg/jpeg)";
				exit();
			}

			$sizeLinkFile= $file->checkmodifsizefile($filedata);
			if(!empty($sizeLinkFile))
			{
				echo "xxx-".$sizeLinkFile;
				exit();
			}

			$jumlahdata= count($filedata);
		}

		$reqMode= $this->input->post('reqMode');

		$reqTanggalAwal= $this->input->post('reqTanggalAwal');
		$reqTanggalAkhir= $this->input->post('reqTanggalAkhir');
		$reqNoSurat= $this->input->post('reqNoSurat');
		$reqTanggalSurat= $this->input->post('reqTanggalSurat');
		$reqKeterangan= $this->input->post('reqKeterangan');
		$reqUbahStatus= $this->input->post('reqUbahStatus');
		$reqStatus= $this->input->post('reqStatus');
		$reqAlasanTolak= $this->input->post('reqAlasanTolak');

		$reqPegawaiId= $this->input->post('reqPegawaiId');
		$reqPegawaiJabatanId= $this->input->post('reqPegawaiJabatanId');
		$reqPegawaiPangkatId= $this->input->post('reqPegawaiPangkatId');
		$reqJenisKlarifikasi= "DKLT";

		$set= new Klarifikasi();
        $set->setField("KLARIFIKASI_ID", $reqId);
        $set->setField("TANGGAL_SURAT", dateToDBCheck($reqTanggalSurat));
        $set->setField("TANGGAL_MULAI", dateToDBCheck($reqTanggalAwal));
        $set->setField("TANGGAL_SELESAI", dateToDBCheck($reqTanggalAkhir));
        $set->setField("JENIS_KLARIFIKASI", $reqJenis);
        $set->setField("KETERANGAN", setQuote($reqKeterangan));
        $set->setField("UBAH_STATUS", $reqUbahStatus);
        $set->setField("STATUS", $reqStatus);
        $set->setField("ALASAN_TOLAK", $reqAlasanTolak);
        $set->setField("KODE", $reqJenisKlarifikasi);
        $set->setField("NOMOR_SURAT", $reqNoSurat);
        $set->setField("LAST_CREATE_USER", $this->LOGIN_USER);
        $set->setField("LAST_CREATE_DATE", "CURRENT_TIMESTAMP");
        $set->setField("LAST_USER", $this->LOGIN_USER);
        $set->setField("LAST_UPDATE", "CURRENT_TIMESTAMP");
        $set->setField("LAST_UPDATE_USER", $this->LOGIN_USER);
        $set->setField("LAST_UPDATE_DATE", "CURRENT_TIMESTAMP");

		$simpan="";
		if($reqMode == "insert")
        {
            if($set->insert())
            {
            	$reqId= $set->id;
            	$simpan = "1";
            }
        }
        else
        {
        	if($set->update())
            {
            	$simpan = "1";
            }
        }

        if($simpan == "1")
        {
        	$set->setField("KLARIFIKASI_ID", $reqId);
        	if($set->deleteDetil())
            {
	        	for($i=0; $i < count($reqPegawaiId); $i++)
	        	{
	        		$set->setField("PEGAWAI_ID", $reqPegawaiId[$i]);
	        		$set->setField("JABATAN_TAMBAHAN_ID", ValToNullDB($reqPegawaiJabatanId[$i]));
	        		$set->setField("PANGKAT_RIWAYAT_ID", ValToNullDB($reqPegawaiPangkatId[$i]));
	        		if($set->insertDetil()){}
	        	}
            }
        }

        // proses hapus file
        $arrdatafile= array();
		$pfile = new PermohonanFile();
		$pfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqId, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis));
		$indexpfile=0;
		while ($pfile->nextRow()) 
		{
			$arrdatafile[$indexpfile]['NAMA'] = $pfile->getField("NAMA");
			$arrdatafile[$indexpfile]['LINK_FILE'] = $pfile->getField("LINK_FILE");
			$indexpfile++;
		}

        // simpan file sesuai format
        $folderfilesimpan= "uploads/".gettanggalperiode($reqTanggalAwal);
        if(file_exists($folderfilesimpan)){}
		else
		{
			makedirs($folderfilesimpan);
		}

		$penamaanfile= $reqId."_".str_replace("-", "", $reqTanggalAwal)."_diklat_";

		for($i=0; $i < $jumlahdata; $i++)
		{
			$namafile= $filedata["name"][$i];
			$fileType= $filedata["type"][$i];
			$datafileupload= $filedata["tmp_name"][$i];
			$filepath= $file->getExtension($namafile);

			if($namafile == ""){}
			else
			{
				$linkfile= $penamaanfile.md5($penamaanfile.$namafile).".".strtolower($filepath);
				$targetsimpan= $folderfilesimpan."/".$linkfile;

				if (move_uploaded_file($datafileupload, $targetsimpan))
				{
					$setfile= new PermohonanFile();
					$setfile->setField("PEGAWAI_ID", "diklat");
					$setfile->setField("PERMOHONAN_TABLE_NAMA", $reqJenis);
					$setfile->setField("PERMOHONAN_TABLE_ID", $reqId);
					$setfile->setField("NAMA", $linkfile);
					$setfile->setField("KETERANGAN", $namafile);
					$setfile->setField("LINK_FILE", $targetsimpan);
					$setfile->setField("TIPE", strtolower($fileType));
					$setfile->setField("LAST_USER", $this->LOGIN_USER);
					$setfile->setField("USER_LOGIN_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->setField("USER_LOGIN_CREATE_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->insert();
					unset($setfile);

					$arrdatafile[$indexpfile]['NAMA'] = $linkfile;
					$arrdatafile[$indexpfile]['LINK_FILE'] = $targetsimpan;
					$indexpfile++;
				}
			}
		}
        // exit;

        // proses hapus file
        for ($i=0; $i < $indexpfile; $i++)
        { 
			$infofilecheck=  $arrdatafile[$i]['NAMA'];
			$cfile= new PermohonanFile();
			$cfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqId, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis, "A.NAMA"=>setQuote($infofilecheck, "")));
			$cfile->firstRow();
			$infofilelokasi= $cfile->getField("LINK_FILE");
			if (empty($infofilelokasi))
			{
				unlink($infofilelokasi);
			}
		}

		if($simpan == "1")
			echo $reqId."-Data berhasil disimpan.";
		else
			echo "xxx-Data gagal disimpan.";
	}

	function masukpulang()
	{
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/SatuanKerja');

		$set= new Klarifikasi;
		
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqStatus= $this->input->get("reqStatus");
		$reqTahun= $this->input->get("reqTahun");
		$reqBulan= $this->input->get("reqBulan");
		$reqPencarian= $this->input->get("reqPencarian");
		$reqKhususDinas= $this->input->get("reqKhususDinas");

		$aColumns = array("KLARIFIKASI_ID", "NIP_NAMA", "TANGGAL_MULAI", "KODE", "STATUS_INFO", "UBAH_STATUS_INFO", "KETERANGAN", "ALASAN_TOLAK", "BUKTI_PENDUKUNG");
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
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY KLARIFIKASI_ID asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.KLARIFIKASI_ID desc";
				 
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
				AND XXX.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
		}
		else
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND XXX.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
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
			$satuankerjakondisi.= " AND XXX.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}
		// echo $satuankerjakondisi;exit();

		$statement= " AND A.JENIS_KLARIFIKASI= 'klarifikasi_masuk_pulang'";

		if(!empty($reqStatus))
		{
			$statement.= " AND A.STATUS = '".$reqStatus."'";
		}

		$searchJson = " AND ( UPPER(A.NIP_BARU) LIKE '%".$reqPencarian."%' OR UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%' )";
		$allRecord = $set->getCountByParamsMasukPulang(array(), $satuankerjakondisi, $statement);
		//echo $allRecord;
		if($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsMasukPulang(array(), $satuankerjakondisi, $searchJson.$statement);
		
		$set->selectByParamsMasukPulang(array(), $dsplyRange, $dsplyStart, $satuankerjakondisi, $searchJson.$statement, $sOrder);
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
					$row[] = $set->getField("NAMA_LENGKAP")."<br/>".$set->getField("NIP_BARU")."<br/>".$set->getField("PEGAWAI_ID");
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

	function masukpulang_option()
	{
		$this->load->model('main/Klarifikasi');

		$reqPegawaiId= $this->input->get('reqPegawaiId');
		$reqTanggal= $this->input->get('reqTanggal');

		$arrdata= []; $i=0;
		$statement= " AND A.PEGAWAI_ID = '".$reqPegawaiId."'";
		$reqPeriode= getperiodetanggal($reqTanggal);
		$set= new Klarifikasi();
		$set->selectByParamsAbsensiKoreksi($reqPeriode, $statement);
		$infocheck= $set->errorMsg;
		if(!empty($infocheck)){}
		else
		{
			$set->firstRow();
			// echo $set->query;exit;
			$infohari= (int)getYear($reqTanggal);
			$infomasuk= $set->getField("MASUK_".$infohari);
			$infopulang= $set->getField("PULANG_".$infohari);
			// echo $infomasuk."--".$infopulang;exit;

			// kondisi data
			/*
			MTSK->HMA
			PTSK->HPL/LMBR
			MT->IMT
			PC->IPC
			kalau MT & PC-> set IMT & IPC
			kalau MTSK & PTSK-> set HMA & HPL
			*/
			$statement= " AND 1=2";
			if($infomasuk == "MTSK" && $infopulang == "PTSK")
			{
				$statement= " AND A.KODE IN ('HMA','HPL')";
			}
			elseif($infomasuk == "MT" && $infopulang == "PC")
			{
				$statement= " AND A.KODE IN ('IMT-IPC')";
			}
			elseif($infomasuk == "MTSK")
			{
				$statement= " AND A.KODE IN ('HMA')";
			}
			elseif($infopulang == "PTSK")
			{
				if($infomasuk == "MT")
					$statement= " AND A.KODE IN ('IMT','HPL','LMBR')";
				else
					$statement= " AND A.KODE IN ('HPL','LMBR')";
			}
			elseif($infomasuk == "MT")
			{
				$statement= " AND A.KODE IN ('IMT')";
			}
			elseif($infopulang == "PC")
			{
				$statement= " AND A.KODE IN ('IPC')";
			}

			$set= new Klarifikasi();
			$set->selectByParamsJenisKlarifikasi(array(),-1,-1,$statement);
			// echo $set->query;exit();

			$arrdata[$i]["KODE"]= "";
			$arrdata[$i]["NAMA"]= "Pilih";
			$i++;
			while ($set->nextRow()) 
			{
				$infokode= $set->getField("KODE");
				$arrdata[$i]["KODE"]= $infokode;
				$arrdata[$i]["NAMA"]= $set->getField("NAMA")." (".$arrdata[$i]["KODE"].")";
				$i++;
			}
		}
		echo json_encode($arrdata);
	}

	function masukpulang_add()
	{
		$this->load->library("FileHandler");
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/PermohonanFile');
		$this->load->model('main/KlarifikasiRequired');

		$reqId= $this->input->post('reqId');
		$reqJenis= "klarifikasi_masuk_pulang";
		$infomenuid= "5101";

		$filedata= $_FILES["reqLinkFile"];
		$file= new FileHandler();

		$checkFile= $file->checkfile($filedata, 6);
		$namaLinkFile= $file->setlinkfile($filedata);

		// cek required upload
		$setdetil= new KlarifikasiRequired();
		$validasirequired= $setdetil->getCountByParams(array(), " AND A.MENU_ID = '".$infomenuid."' AND B.STATUS_UPLOAD = '1'");
		// echo $setdetil->query;exit;
		$infovalidasifile= "1";
		if($validasirequired > 0)
		{
			$infovalidasifile= "";
		}

		$jumlahfiledata= 0;
		if(!empty($reqId))
		{
			$statement= " AND A.PERMOHONAN_TABLE_NAMA = '".$reqJenis."' AND A.PERMOHONAN_TABLE_ID = ".$reqId;
			$setfile= new PermohonanFile();
			$jumlahfiledata= $setfile->getCountByParams(array(), $statement);
			unset($setfile);
		}

		$jumlahdata= 0;
		if(empty($namaLinkFile))
		{
			if($infovalidasifile == "1")
			{
				if($jumlahfiledata > 0){}
				else
				{
					echo "xxx-Anda belum upload file lampiran.";
					exit();
				}
			}
		}
		else
		{
			if(!empty($checkFile))
			{
				echo "xxx-File harus berformat (pdf/jpg/jpeg)";
				exit();
			}

			$sizeLinkFile= $file->checkmodifsizefile($filedata);
			if(!empty($sizeLinkFile))
			{
				echo "xxx-".$sizeLinkFile;
				exit();
			}

			$jumlahdata= count($filedata);
		}

		$reqMode= $this->input->post('reqMode');

		$reqTanggalAwal= $this->input->post('reqTanggalAwal');
		$reqPegawaiId= $this->input->post('reqPegawaiId');
		$reqPegawaiJabatanId= $this->input->post('reqPegawaiJabatanId');
		$reqPegawaiPangkatId= $this->input->post('reqPegawaiPangkatId');
		$reqJenisKlarifikasi= $this->input->post('reqJenisKlarifikasi');
		$reqKeterangan= $this->input->post('reqKeterangan');
		$reqUbahStatus= $this->input->post('reqUbahStatus');
		$reqStatus= $this->input->post('reqStatus');
		$reqAlasanTolak= $this->input->post('reqAlasanTolak');

		$set= new Klarifikasi();
        $set->setField("KLARIFIKASI_ID", $reqId);
        $set->setField("PEGAWAI_ID", $reqPegawaiId);
        $set->setField("JABATAN_TAMBAHAN_ID", ValToNullDB($reqPegawaiJabatanId));
        $set->setField("PANGKAT_RIWAYAT_ID", ValToNullDB($reqPegawaiPangkatId));
        $set->setField("TANGGAL_MULAI", dateToDBCheck($reqTanggalAwal));
        $set->setField("TANGGAL_SELESAI", dateToDBCheck($reqTanggalAwal));
        $set->setField("JENIS_KLARIFIKASI", $reqJenis);
        $set->setField("KETERANGAN", setQuote($reqKeterangan));
        $set->setField("UBAH_STATUS", $reqUbahStatus);
        $set->setField("STATUS", $reqStatus);
        $set->setField("ALASAN_TOLAK", $reqAlasanTolak);
        $set->setField("KODE", $reqJenisKlarifikasi);

        $set->setField("TANGGAL_SURAT", "NULL");

        $set->setField("LAST_USER", $this->LOGIN_USER);
        $set->setField("LAST_UPDATE", "CURRENT_TIMESTAMP");

		$simpan="";
		if($reqMode == "insert")
        {
        	$set->setField("LAST_CREATE_USER", $this->LOGIN_USER);
        	$set->setField("LAST_CREATE_DATE", "CURRENT_TIMESTAMP");
            if($set->insert())
            {
            	$reqId= $set->id;
            	$simpan = "1";
            }
        }
        else
        {
        	$set->setField("LAST_UPDATE_USER", $this->LOGIN_USER);
        	$set->setField("LAST_UPDATE_DATE", "CURRENT_TIMESTAMP");
        	if($set->update())
            {
            	$simpan = "1";
            }
        }

        if ($simpan == "1") {
        	$set->setField("KLARIFIKASI_ID", $reqId);
            if($set->deleteDetil())
            {
                if($set->insertDetil())
	            {
	                $simpan = "2";
	            }
            }
        }

        // proses hapus file
        $arrdatafile= array();
		$pfile = new PermohonanFile();
		$pfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqId, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis));
		$indexpfile=0;
		while ($pfile->nextRow()) 
		{
			$arrdatafile[$indexpfile]['NAMA'] = $pfile->getField("NAMA");
			$arrdatafile[$indexpfile]['LINK_FILE'] = $pfile->getField("LINK_FILE");
			$indexpfile++;
		}

        // simpan file sesuai format
        $folderfilesimpan= "uploads/".gettanggalperiode($reqTanggalAwal);
        if(file_exists($folderfilesimpan)){}
		else
		{
			makedirs($folderfilesimpan);
		}

		$penamaanfile= $reqId."_".str_replace("-", "", $reqTanggalAwal)."_".$reqPegawaiId."_masukpulang_";

		for($i=0; $i < $jumlahdata; $i++)
		{
			$namafile= $filedata["name"][$i];
			$fileType= $filedata["type"][$i];
			$datafileupload= $filedata["tmp_name"][$i];
			$filepath= $file->getExtension($namafile);

			if($namafile == ""){}
			else
			{
				$linkfile= $penamaanfile.md5($penamaanfile.$namafile).".".strtolower($filepath);
				$targetsimpan= $folderfilesimpan."/".$linkfile;

				if (move_uploaded_file($datafileupload, $targetsimpan))
				{
					$setfile= new PermohonanFile();
					$setfile->setField("PEGAWAI_ID", $reqPegawaiId);
					$setfile->setField("PERMOHONAN_TABLE_NAMA", $reqJenis);
					$setfile->setField("PERMOHONAN_TABLE_ID", $reqId);
					$setfile->setField("NAMA", $linkfile);
					$setfile->setField("KETERANGAN", $namafile);
					$setfile->setField("LINK_FILE", $targetsimpan);
					$setfile->setField("TIPE", strtolower($fileType));
					$setfile->setField("LAST_USER", $this->LOGIN_USER);
					$setfile->setField("USER_LOGIN_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->setField("USER_LOGIN_CREATE_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->insert();
					unset($setfile);

					$arrdatafile[$indexpfile]['NAMA'] = $linkfile;
					$arrdatafile[$indexpfile]['LINK_FILE'] = $targetsimpan;
					$indexpfile++;
				}
			}
		}
        // exit;

		// proses hapus file
        for ($i=0; $i < $indexpfile; $i++)
        { 
			$infofilecheck=  $arrdatafile[$i]['NAMA'];
			$cfile= new PermohonanFile();
			$cfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqId, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis, "A.NAMA"=>setQuote($infofilecheck, "")));
			$cfile->firstRow();
			$infofilelokasi= $cfile->getField("LINK_FILE");
			if (empty($infofilelokasi))
			{
				unlink($infofilelokasi);
			}
		}

		if($simpan == "2")
			echo $reqId."-Data berhasil disimpan.";
		else
			echo "xxx-Data gagal disimpan.";
	}

	function ijinsakit()
	{
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/SatuanKerja');

		$set= new Klarifikasi;
		
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqTahun= $this->input->get("reqTahun");
		$reqBulan= $this->input->get("reqBulan");
		$reqPencarian= $this->input->get("reqPencarian");
		$reqKhususDinas= $this->input->get("reqKhususDinas");

		$aColumns = array("KLARIFIKASI_ID", "NIP_NAMA", "TANGGAL_MULAI", "TANGGAL_SELESAI", "LAMA", "STATUS_INFO", "UBAH_STATUS_INFO", "KETERANGAN", "ALASAN_TOLAK", "BUKTI_PENDUKUNG");
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
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY KLARIFIKASI_ID asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.KLARIFIKASI_ID desc";
				 
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
				AND XXX.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
		}
		else
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND XXX.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
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
			$satuankerjakondisi.= " AND XXX.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}
		// echo $satuankerjakondisi;exit();

		$statement= " AND A.JENIS_KLARIFIKASI= 'klarifikasi_ijin_sakit'";

		$searchJson = " AND ( UPPER(A.NIP_BARU) LIKE '%".$reqPencarian."%' OR UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%' )";
		$allRecord = $set->getCountByParamsIjinSakit(array(), $satuankerjakondisi, $statement);
		//echo $allRecord;
		if($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsIjinSakit(array(), $satuankerjakondisi, $searchJson.$statement);
		
		$set->selectByParamsIjinSakit(array(), $dsplyRange, $dsplyStart, $satuankerjakondisi, $searchJson.$statement, $sOrder);
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
					$row[] = $set->getField("NAMA_LENGKAP")."<br/>".$set->getField("NIP_BARU")."<br/>".$set->getField("PEGAWAI_ID");
				}
				elseif($aColumns[$i] == "TANGGAL_MULAI" || $aColumns[$i] == "TANGGAL_SELESAI")
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

	function ijinsakit_add()
	{
		$this->load->library("FileHandler");
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/PermohonanFile');
		$this->load->model('main/KlarifikasiRequired');

		$reqId= $this->input->post('reqId');
		$reqJenis= "klarifikasi_ijin_sakit";
		$infomenuid= "5103";

		$filedata= $_FILES["reqLinkFile"];
		$file= new FileHandler();

		$checkFile= $file->checkfile($filedata, 6);
		$namaLinkFile= $file->setlinkfile($filedata);

		// cek required upload
		$setdetil= new KlarifikasiRequired();
		$validasirequired= $setdetil->getCountByParams(array(), " AND A.MENU_ID = '".$infomenuid."' AND B.STATUS_UPLOAD = '1'");
		// echo $setdetil->query;exit;
		$infovalidasifile= "1";
		if($validasirequired > 0)
		{
			$infovalidasifile= "";
		}

		$jumlahfiledata= 0;
		if(!empty($reqId))
		{
			$statement= " AND A.PERMOHONAN_TABLE_NAMA = '".$reqJenis."' AND A.PERMOHONAN_TABLE_ID = ".$reqId;
			$setfile= new PermohonanFile();
			$jumlahfiledata= $setfile->getCountByParams(array(), $statement);
			unset($setfile);
		}
		
		$jumlahdata= 0;
		if(empty($namaLinkFile))
		{
			if($infovalidasifile == "1")
			{
				if($jumlahfiledata > 0){}
				else
				{
					echo "xxx-Anda belum upload file lampiran.";
					exit();
				}
			}
		}
		else
		{
			if(!empty($checkFile))
			{
				echo "xxx-File harus berformat (pdf/jpg/jpeg)";
				exit();
			}

			$sizeLinkFile= $file->checkmodifsizefile($filedata);
			if(!empty($sizeLinkFile))
			{
				echo "xxx-".$sizeLinkFile;
				exit();
			}

			$jumlahdata= count($filedata);
		}

		$reqMode= $this->input->post('reqMode');

		$reqTanggalAwal= $this->input->post('reqTanggalAwal');
		$reqTanggalAkhir= $this->input->post('reqTanggalAkhir');
		$reqPegawaiId= $this->input->post('reqPegawaiId');
		$reqPegawaiJabatanId= $this->input->post('reqPegawaiJabatanId');
		$reqPegawaiPangkatId= $this->input->post('reqPegawaiPangkatId');
		$reqKeterangan= $this->input->post('reqKeterangan');
		$reqStatus= $this->input->post('reqStatus');
		$reqAlasanTolak= $this->input->post('reqAlasanTolak');

		$checkhari= datediffhari($reqTanggalAwal, $reqTanggalAkhir);

		if($checkhari > 2)
		{
			echo "xxx-Data gagal disimpan, karena melebihi 3 hari.";
			exit;
		}

		$reqJenisKlarifikasi= "ISS";

		$set= new Klarifikasi();
        $set->setField("KLARIFIKASI_ID", $reqId);
        $set->setField("PEGAWAI_ID", $reqPegawaiId);
        $set->setField("JABATAN_TAMBAHAN_ID", ValToNullDB($reqPegawaiJabatanId));
        $set->setField("PANGKAT_RIWAYAT_ID", ValToNullDB($reqPegawaiPangkatId));
        $set->setField("TANGGAL_MULAI", dateToDBCheck($reqTanggalAwal));
        $set->setField("TANGGAL_SELESAI", dateToDBCheck($reqTanggalAkhir));
        $set->setField("JENIS_KLARIFIKASI", $reqJenis);
        $set->setField("KETERANGAN", setQuote($reqKeterangan));
        $set->setField("STATUS", $reqStatus);
        $set->setField("ALASAN_TOLAK", $reqAlasanTolak);
        $set->setField("KODE", $reqJenisKlarifikasi);

        $set->setField("TANGGAL_SURAT", "NULL");

        $set->setField("LAST_USER", $this->LOGIN_USER);
        $set->setField("LAST_UPDATE", "CURRENT_TIMESTAMP");

		$simpan="";
		if($reqMode == "insert")
        {
        	$set->setField("LAST_CREATE_USER", $this->LOGIN_USER);
        	$set->setField("LAST_CREATE_DATE", "CURRENT_TIMESTAMP");
            if($set->insert())
            {
            	$reqId= $set->id;
            	$simpan = "1";
            }
        }
        else
        {
        	$set->setField("LAST_UPDATE_USER", $this->LOGIN_USER);
        	$set->setField("LAST_UPDATE_DATE", "CURRENT_TIMESTAMP");
        	if($set->update())
            {
            	$simpan = "1";
            }
        }

        if ($simpan == "1") {
        	$set->setField("KLARIFIKASI_ID", $reqId);
            if($set->deleteDetil())
            {
                if($set->insertDetil())
	            {
	                $simpan = "2";
	            }
            }
        }

        // proses hapus file
        $arrdatafile= array();
		$pfile = new PermohonanFile();
		$pfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqId, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis));
		$indexpfile=0;
		while ($pfile->nextRow()) 
		{
			$arrdatafile[$indexpfile]['NAMA'] = $pfile->getField("NAMA");
			$arrdatafile[$indexpfile]['LINK_FILE'] = $pfile->getField("LINK_FILE");
			$indexpfile++;
		}

        // simpan file sesuai format
        $folderfilesimpan= "uploads/".gettanggalperiode($reqTanggalAwal);
        if(file_exists($folderfilesimpan)){}
		else
		{
			makedirs($folderfilesimpan);
		}

		$penamaanfile= $reqId."_".str_replace("-", "", $reqTanggalAwal)."_".$reqPegawaiId."_ijinsakit_";

		for($i=0; $i < $jumlahdata; $i++)
		{
			$namafile= $filedata["name"][$i];
			$fileType= $filedata["type"][$i];
			$datafileupload= $filedata["tmp_name"][$i];
			$filepath= $file->getExtension($namafile);

			if($namafile == ""){}
			else
			{
				$linkfile= $penamaanfile.md5($penamaanfile.$namafile).".".strtolower($filepath);
				$targetsimpan= $folderfilesimpan."/".$linkfile;

				if (move_uploaded_file($datafileupload, $targetsimpan))
				{
					$setfile= new PermohonanFile();
					$setfile->setField("PEGAWAI_ID", $reqPegawaiId);
					$setfile->setField("PERMOHONAN_TABLE_NAMA", $reqJenis);
					$setfile->setField("PERMOHONAN_TABLE_ID", $reqId);
					$setfile->setField("NAMA", $linkfile);
					$setfile->setField("KETERANGAN", $namafile);
					$setfile->setField("LINK_FILE", $targetsimpan);
					$setfile->setField("TIPE", strtolower($fileType));
					$setfile->setField("LAST_USER", $this->LOGIN_USER);
					$setfile->setField("USER_LOGIN_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->setField("USER_LOGIN_CREATE_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->insert();
					unset($setfile);

					$arrdatafile[$indexpfile]['NAMA'] = $linkfile;
					$arrdatafile[$indexpfile]['LINK_FILE'] = $targetsimpan;
					$indexpfile++;
				}
			}
		}
        // exit;

        // proses hapus file
        for ($i=0; $i < $indexpfile; $i++)
        { 
			$infofilecheck=  $arrdatafile[$i]['NAMA'];
			$cfile= new PermohonanFile();
			$cfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqId, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis, "A.NAMA"=>setQuote($infofilecheck, "")));
			$cfile->firstRow();
			$infofilelokasi= $cfile->getField("LINK_FILE");
			if (empty($infofilelokasi))
			{
				unlink($infofilelokasi);
			}
		}

		if($simpan == "2")
			echo $reqId."-Data berhasil disimpan.";
		else
			echo "xxx-Data gagal disimpan.";
	}

	function lupa()
	{
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/SatuanKerja');

		$set= new Klarifikasi;
		
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqTahun= $this->input->get("reqTahun");
		$reqBulan= $this->input->get("reqBulan");
		$reqPencarian= $this->input->get("reqPencarian");
		$reqKhususDinas= $this->input->get("reqKhususDinas");

		$aColumns = array("KLARIFIKASI_ID", "NIP_NAMA", "TANGGAL_MULAI", "KODE", "STATUS_INFO", "KETERANGAN", "ALASAN_TOLAK", "BUKTI_PENDUKUNG");
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
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY KLARIFIKASI_ID asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.KLARIFIKASI_ID desc";
				 
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
				AND XXX.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
		}
		else
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND XXX.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
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
			$satuankerjakondisi.= " AND XXX.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}
		// echo $satuankerjakondisi;exit();

		$statement= " AND A.JENIS_KLARIFIKASI= 'klarifikasi_lupa'";

		$searchJson = " AND ( UPPER(A.NIP_BARU) LIKE '%".$reqPencarian."%' OR UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%' )";
		$allRecord = $set->getCountByParamsLupa(array(), $satuankerjakondisi, $statement);
		//echo $allRecord;
		if($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsLupa(array(), $satuankerjakondisi, $searchJson.$statement);
		
		$set->selectByParamsLupa(array(), $dsplyRange, $dsplyStart, $satuankerjakondisi, $searchJson.$statement, $sOrder);
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

	function lupa_add()
	{
		$this->load->library("FileHandler");
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/PermohonanFile');
		$this->load->model('main/KlarifikasiRequired');

		$reqId= $this->input->post('reqId');
		$reqJenis= "klarifikasi_lupa";
		$infomenuid= "5105";

		$filedata= $_FILES["reqLinkFile"];
		$file= new FileHandler();

		$checkFile= $file->checkfile($filedata, 6);
		$namaLinkFile= $file->setlinkfile($filedata);


		// cek required upload
		$setdetil= new KlarifikasiRequired();
		$validasirequired= $setdetil->getCountByParams(array(), " AND A.MENU_ID = '".$infomenuid."' AND B.STATUS_UPLOAD = '1'");
		// echo $setdetil->query;exit;
		$infovalidasifile= "1";
		if($validasirequired > 0)
		{
			$infovalidasifile= "";
		}

		$jumlahfiledata= 0;
		if(!empty($reqId))
		{
			$statement= " AND A.PERMOHONAN_TABLE_NAMA = '".$reqJenis."' AND A.PERMOHONAN_TABLE_ID = ".$reqId;
			$setfile= new PermohonanFile();
			$jumlahfiledata= $setfile->getCountByParams(array(), $statement);
			unset($setfile);
		}
		
		$jumlahdata= 0;
		if(empty($namaLinkFile))
		{
			if($infovalidasifile == "1")
			{
				if($jumlahfiledata > 0){}
				else
				{
					echo "xxx-Anda belum upload file lampiran.";
					exit();
				}
			}
		}
		else
		{
			if(!empty($checkFile))
			{
				echo "xxx-File harus berformat (pdf/jpg/jpeg)";
				exit();
			}

			$sizeLinkFile= $file->checkmodifsizefile($filedata);
			if(!empty($sizeLinkFile))
			{
				echo "xxx-".$sizeLinkFile;
				exit();
			}

			$jumlahdata= count($filedata);
		}

		$reqMode= $this->input->post('reqMode');

		$reqTanggalAwal= $this->input->post('reqTanggalAwal');
		$reqPegawaiId= $this->input->post('reqPegawaiId');
		$reqPegawaiJabatanId= $this->input->post('reqPegawaiJabatanId');
		$reqPegawaiPangkatId= $this->input->post('reqPegawaiPangkatId');
		$reqJenisKlarifikasi= $this->input->post('reqJenisKlarifikasi');
		$reqKeterangan= $this->input->post('reqKeterangan');
		// $reqUbahStatus= $this->input->post('reqUbahStatus');
		$reqStatus= $this->input->post('reqStatus');
		$reqAlasanTolak= $this->input->post('reqAlasanTolak');

		$set= new Klarifikasi();
        $set->setField("KLARIFIKASI_ID", $reqId);
        $set->setField("PEGAWAI_ID", $reqPegawaiId);
        $set->setField("JABATAN_TAMBAHAN_ID", ValToNullDB($reqPegawaiJabatanId));
        $set->setField("PANGKAT_RIWAYAT_ID", ValToNullDB($reqPegawaiPangkatId));
        $set->setField("TANGGAL_MULAI", dateToDBCheck($reqTanggalAwal));
        $set->setField("TANGGAL_SELESAI", dateToDBCheck($reqTanggalAwal));
        $set->setField("JENIS_KLARIFIKASI", $reqJenis);
        $set->setField("KETERANGAN", setQuote($reqKeterangan));
        // $set->setField("UBAH_STATUS", $reqUbahStatus);
        $set->setField("STATUS", $reqStatus);
        $set->setField("ALASAN_TOLAK", $reqAlasanTolak);
        $set->setField("KODE", $reqJenisKlarifikasi);

        $set->setField("TANGGAL_SURAT", "NULL");

        $set->setField("LAST_USER", $this->LOGIN_USER);
        $set->setField("LAST_UPDATE", "CURRENT_TIMESTAMP");

		$simpan="";
		if($reqMode == "insert")
        {
        	$set->setField("LAST_CREATE_USER", $this->LOGIN_USER);
        	$set->setField("LAST_CREATE_DATE", "CURRENT_TIMESTAMP");
            if($set->insert())
            {
            	$reqId= $set->id;
            	$simpan = "1";
            }
        }
        else
        {
        	$set->setField("LAST_UPDATE_USER", $this->LOGIN_USER);
        	$set->setField("LAST_UPDATE_DATE", "CURRENT_TIMESTAMP");
        	if($set->update())
            {
            	$simpan = "1";
            }
        }

        if ($simpan == "1") {
        	$set->setField("KLARIFIKASI_ID", $reqId);
            if($set->deleteDetil())
            {
                if($set->insertDetil())
	            {
	                $simpan = "2";
	            }
            }
        }

        // proses hapus file
        $arrdatafile= array();
		$pfile = new PermohonanFile();
		$pfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqId, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis));
		$indexpfile=0;
		while ($pfile->nextRow()) 
		{
			$arrdatafile[$indexpfile]['NAMA'] = $pfile->getField("NAMA");
			$arrdatafile[$indexpfile]['LINK_FILE'] = $pfile->getField("LINK_FILE");
			$indexpfile++;
		}

        // simpan file sesuai format
        $folderfilesimpan= "uploads/".gettanggalperiode($reqTanggalAwal);
        if(file_exists($folderfilesimpan)){}
		else
		{
			makedirs($folderfilesimpan);
		}

		$penamaanfile= $reqId."_".str_replace("-", "", $reqTanggalAwal)."_".$reqPegawaiId."_lupa_";

		for($i=0; $i < $jumlahdata; $i++)
		{
			$namafile= $filedata["name"][$i];
			$fileType= $filedata["type"][$i];
			$datafileupload= $filedata["tmp_name"][$i];
			$filepath= $file->getExtension($namafile);

			if($namafile == ""){}
			else
			{
				$linkfile= $penamaanfile.md5($penamaanfile.$namafile).".".strtolower($filepath);
				$targetsimpan= $folderfilesimpan."/".$linkfile;

				if (move_uploaded_file($datafileupload, $targetsimpan))
				{
					$setfile= new PermohonanFile();
					$setfile->setField("PEGAWAI_ID", $reqPegawaiId);
					$setfile->setField("PERMOHONAN_TABLE_NAMA", $reqJenis);
					$setfile->setField("PERMOHONAN_TABLE_ID", $reqId);
					$setfile->setField("NAMA", $linkfile);
					$setfile->setField("KETERANGAN", $namafile);
					$setfile->setField("LINK_FILE", $targetsimpan);
					$setfile->setField("TIPE", strtolower($fileType));
					$setfile->setField("LAST_USER", $this->LOGIN_USER);
					$setfile->setField("USER_LOGIN_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->setField("USER_LOGIN_CREATE_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->insert();
					unset($setfile);

					$arrdatafile[$indexpfile]['NAMA'] = $linkfile;
					$arrdatafile[$indexpfile]['LINK_FILE'] = $targetsimpan;
					$indexpfile++;
				}
			}
		}
        // exit;

        // proses hapus file
        for ($i=0; $i < $indexpfile; $i++)
        { 
			$infofilecheck=  $arrdatafile[$i]['NAMA'];
			$cfile= new PermohonanFile();
			$cfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqId, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis, "A.NAMA"=>setQuote($infofilecheck, "")));
			$cfile->firstRow();
			$infofilelokasi= $cfile->getField("LINK_FILE");
			if (empty($infofilelokasi))
			{
				unlink($infofilelokasi);
			}
		}

		if($simpan == "2")
			echo $reqId."-Data berhasil disimpan.";
		else
			echo "xxx-Data gagal disimpan.";
	}

	function tugasbelajar()
	{
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/SatuanKerja');

		$set= new Klarifikasi;
		
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqTahun= $this->input->get("reqTahun");
		$reqBulan= $this->input->get("reqBulan");
		$reqPencarian= $this->input->get("reqPencarian");
		$reqKhususDinas= $this->input->get("reqKhususDinas");

		$aColumns = array("KLARIFIKASI_ID", "NIP_NAMA", "TANGGAL_MULAI", "TANGGAL_SELESAI", "STATUS_INFO", "KETERANGAN", "ALASAN_TOLAK", "BUKTI_PENDUKUNG");
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
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY KLARIFIKASI_ID asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.KLARIFIKASI_ID desc";
				 
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
				AND XXX.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
		}
		else
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND XXX.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
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
			$satuankerjakondisi.= " AND XXX.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}
		// echo $satuankerjakondisi;exit();

		$statement= " AND A.JENIS_KLARIFIKASI= 'klarifikasi_tugas_belajar'";

		$searchJson = " AND ( UPPER(A.NIP_BARU) LIKE '%".$reqPencarian."%' OR UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%' )";
		$allRecord = $set->getCountByParamsTugasBelajar(array(), $satuankerjakondisi, $statement);
		//echo $allRecord;
		if($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsTugasBelajar(array(), $satuankerjakondisi, $searchJson.$statement);
		
		$set->selectByParamsTugasBelajar(array(), $dsplyRange, $dsplyStart, $satuankerjakondisi, $searchJson.$statement, $sOrder);
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
				elseif($aColumns[$i] == "TANGGAL_MULAI" || $aColumns[$i] == "TANGGAL_SELESAI")
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

	function tugasbelajar_add()
	{
		$this->load->library("FileHandler");
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/PermohonanFile');
		$this->load->model('main/KlarifikasiRequired');

		$reqId= $this->input->post('reqId');
		$reqJenis= "klarifikasi_tugas_belajar";
		$infomenuid= "5111";

		$filedata= $_FILES["reqLinkFile"];
		$file= new FileHandler();

		$checkFile= $file->checkfile($filedata, 6);
		$namaLinkFile= $file->setlinkfile($filedata);

		// cek required upload
		$setdetil= new KlarifikasiRequired();
		$validasirequired= $setdetil->getCountByParams(array(), " AND A.MENU_ID = '".$infomenuid."' AND B.STATUS_UPLOAD = '1'");
		// echo $setdetil->query;exit;
		$infovalidasifile= "1";
		if($validasirequired > 0)
		{
			$infovalidasifile= "";
		}

		$jumlahfiledata= 0;
		if(!empty($reqId))
		{
			$statement= " AND A.PERMOHONAN_TABLE_NAMA = '".$reqJenis."' AND A.PERMOHONAN_TABLE_ID = ".$reqId;
			$setfile= new PermohonanFile();
			$jumlahfiledata= $setfile->getCountByParams(array(), $statement);
			unset($setfile);
		}
		
		$jumlahdata= 0;
		if(empty($namaLinkFile))
		{
			if($infovalidasifile == "1")
			{
				if($jumlahfiledata > 0){}
				else
				{
					echo "xxx-Anda belum upload file lampiran.";
					exit();
				}
			}
		}
		else
		{
			if(!empty($checkFile))
			{
				echo "xxx-File harus berformat (pdf/jpg/jpeg)";
				exit();
			}

			$sizeLinkFile= $file->checkmodifsizefile($filedata);
			if(!empty($sizeLinkFile))
			{
				echo "xxx-".$sizeLinkFile;
				exit();
			}

			$jumlahdata= count($filedata);
		}

		$reqMode= $this->input->post('reqMode');

		$reqTanggalAwal= $this->input->post('reqTanggalAwal');
		$reqTanggalAkhir= $this->input->post('reqTanggalAkhir');
		$reqPegawaiId= $this->input->post('reqPegawaiId');
		$reqPegawaiJabatanId= $this->input->post('reqPegawaiJabatanId');
		$reqPegawaiPangkatId= $this->input->post('reqPegawaiPangkatId');
		$reqKeterangan= $this->input->post('reqKeterangan');
		$reqStatus= $this->input->post('reqStatus');
		$reqAlasanTolak= $this->input->post('reqAlasanTolak');

		$reqJenisKlarifikasi= "TB";

		$set= new Klarifikasi();
        $set->setField("KLARIFIKASI_ID", $reqId);
        $set->setField("PEGAWAI_ID", $reqPegawaiId);
        $set->setField("JABATAN_TAMBAHAN_ID", ValToNullDB($reqPegawaiJabatanId));
        $set->setField("PANGKAT_RIWAYAT_ID", ValToNullDB($reqPegawaiPangkatId));
        $set->setField("TANGGAL_MULAI", dateToDBCheck($reqTanggalAwal));
        $set->setField("TANGGAL_SELESAI", dateToDBCheck($reqTanggalAkhir));
        $set->setField("JENIS_KLARIFIKASI", $reqJenis);
        $set->setField("KETERANGAN", setQuote($reqKeterangan));
        $set->setField("STATUS", $reqStatus);
        $set->setField("ALASAN_TOLAK", $reqAlasanTolak);
        $set->setField("KODE", $reqJenisKlarifikasi);

        $set->setField("TANGGAL_SURAT", "NULL");

        $set->setField("LAST_USER", $this->LOGIN_USER);
        $set->setField("LAST_UPDATE", "CURRENT_TIMESTAMP");

		$simpan="";
		if($reqMode == "insert")
        {
        	$set->setField("LAST_CREATE_USER", $this->LOGIN_USER);
        	$set->setField("LAST_CREATE_DATE", "CURRENT_TIMESTAMP");
            if($set->insert())
            {
            	$reqId= $set->id;
            	$simpan = "1";
            }
        }
        else
        {
        	$set->setField("LAST_UPDATE_USER", $this->LOGIN_USER);
        	$set->setField("LAST_UPDATE_DATE", "CURRENT_TIMESTAMP");
        	if($set->update())
            {
            	$simpan = "1";
            }
        }

        if ($simpan == "1") {
        	$set->setField("KLARIFIKASI_ID", $reqId);
            if($set->deleteDetil())
            {
                if($set->insertDetil())
	            {
	                $simpan = "2";
	            }
            }
        }

        // proses hapus file
        $arrdatafile= array();
		$pfile = new PermohonanFile();
		$pfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqId, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis));
		$indexpfile=0;
		while ($pfile->nextRow()) 
		{
			$arrdatafile[$indexpfile]['NAMA'] = $pfile->getField("NAMA");
			$arrdatafile[$indexpfile]['LINK_FILE'] = $pfile->getField("LINK_FILE");
			$indexpfile++;
		}

        // simpan file sesuai format
        $folderfilesimpan= "uploads/".gettanggalperiode($reqTanggalAwal);
        if(file_exists($folderfilesimpan)){}
		else
		{
			makedirs($folderfilesimpan);
		}

		$penamaanfile= $reqId."_".str_replace("-", "", $reqTanggalAwal)."_".$reqPegawaiId."_ijinsakit_";

		for($i=0; $i < $jumlahdata; $i++)
		{
			$namafile= $filedata["name"][$i];
			$fileType= $filedata["type"][$i];
			$datafileupload= $filedata["tmp_name"][$i];
			$filepath= $file->getExtension($namafile);

			if($namafile == ""){}
			else
			{
				$linkfile= $penamaanfile.md5($penamaanfile.$namafile).".".strtolower($filepath);
				$targetsimpan= $folderfilesimpan."/".$linkfile;

				if (move_uploaded_file($datafileupload, $targetsimpan))
				{
					$setfile= new PermohonanFile();
					$setfile->setField("PEGAWAI_ID", $reqPegawaiId);
					$setfile->setField("PERMOHONAN_TABLE_NAMA", $reqJenis);
					$setfile->setField("PERMOHONAN_TABLE_ID", $reqId);
					$setfile->setField("NAMA", $linkfile);
					$setfile->setField("KETERANGAN", $namafile);
					$setfile->setField("LINK_FILE", $targetsimpan);
					$setfile->setField("TIPE", strtolower($fileType));
					$setfile->setField("LAST_USER", $this->LOGIN_USER);
					$setfile->setField("USER_LOGIN_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->setField("USER_LOGIN_CREATE_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->insert();
					unset($setfile);

					$arrdatafile[$indexpfile]['NAMA'] = $linkfile;
					$arrdatafile[$indexpfile]['LINK_FILE'] = $targetsimpan;
					$indexpfile++;
				}
			}
		}
        // exit;

        // proses hapus file
        for ($i=0; $i < $indexpfile; $i++)
        { 
			$infofilecheck=  $arrdatafile[$i]['NAMA'];
			$cfile= new PermohonanFile();
			$cfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqId, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis, "A.NAMA"=>setQuote($infofilecheck, "")));
			$cfile->firstRow();
			$infofilelokasi= $cfile->getField("LINK_FILE");
			if (empty($infofilelokasi))
			{
				unlink($infofilelokasi);
			}
		}

		if($simpan == "2")
			echo $reqId."-Data berhasil disimpan.";
		else
			echo "xxx-Data gagal disimpan.";
	}

	function gantistatus()
	{
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/SatuanKerja');

		$set= new Klarifikasi;
		
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqStatus= $this->input->get("reqStatus");
		$reqTahun= $this->input->get("reqTahun");
		$reqBulan= $this->input->get("reqBulan");
		$reqPencarian= $this->input->get("reqPencarian");
		$reqKhususDinas= $this->input->get("reqKhususDinas");

		$aColumns = array("KLARIFIKASI_ID", "PEGAWAI_ID", "NAMA_LENGKAP", "NIP_BARU", "JAM_TANGGAL", "JAM_WAKTU", "TIPE_ABSEN_AWAL_INFO", "TIPE_ABSEN_REVISI_INFO", "STATUS_INFO");
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
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY KLARIFIKASI_ID asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.KLARIFIKASI_ID desc";
				 
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
				AND XXX.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
		}
		else
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND XXX.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
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
			$satuankerjakondisi.= " AND XXX.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}
		// echo $satuankerjakondisi;exit();

		$statement= " AND A.JENIS_KLARIFIKASI= 'klarifikasi_ganti_status'";

		if(!empty($reqStatus))
		{
			if($reqStatus == "xxx"){}
			else
			{
				$statement.= " AND A.STATUS = '".$reqStatus."'";
			}
		}
		else
		{
			$statement.= " AND COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL";
		}

		$searchJson = " AND ( UPPER(A.NIP_BARU) LIKE '%".$reqPencarian."%' OR UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%' )";
		$allRecord = $set->getCountByParamsGantiStatus(array(), $satuankerjakondisi, $statement);
		//echo $allRecord;
		if($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsGantiStatus(array(), $satuankerjakondisi, $searchJson.$statement);
		
		$set->selectByParamsGantiStatus(array(), $dsplyRange, $dsplyStart, $satuankerjakondisi, $searchJson.$statement, $sOrder);
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
				if($aColumns[$i] == "JAM_TANGGAL")
				{
					$row[] = datetimeToPage($set->getField("JAM"), "date");
				}
				elseif($aColumns[$i] == "JAM_WAKTU")
				{
					$row[] = datetimeToPage($set->getField("JAM"), "");
				}
				else
					$row[] = $set->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}

	function gantistatus_option()
	{
		$this->load->model('main/Klarifikasi');

		$reqPegawaiId= $this->input->get('reqPegawaiId');
		$reqTanggal= $this->input->get('reqTanggal');

		$statement= " AND PEGAWAI_ID = '".$reqPegawaiId."' AND TO_DATE(TO_CHAR(A.JAM, 'YYYY-MM-DD'), 'YYYY/MM/DD') = TO_DATE('".dateToPageCheck($reqTanggal)."','YYYY/MM/DD') AND A.VALIDASI = 1";
		$set= new Klarifikasi();
		$set->selectByParamsAbsensi(array(), -1, -1, $statement);
		// echo $set->query;exit;
		$arrdata= []; $i=0;
		// $arrdata[$i]["TANGGAL"]= "";
		// $arrdata[$i]["WAKTU"]= "";
		// $arrdata[$i]["TIPE_ABSEN"]= "";
		// $arrdata[$i]["TIPE_ABSEN_INFO"]= "";
		// $i++;

		while ($set->nextRow()) 
		{
			$arrdata[$i]["TANGGAL"]= $set->getField("TANGGAL");
			$arrdata[$i]["WAKTU"]= $set->getField("WAKTU");
			$arrdata[$i]["TIPE_ABSEN"]= $set->getField("TIPE_ABSEN");
			$arrdata[$i]["TIPE_ABSEN_INFO"]= $set->getField("TIPE_ABSEN_INFO");
			$i++;
		}

		echo json_encode($arrdata);
	}

	function gantistatus_optionrevisi()
	{
		$reqJenis= $this->input->get('reqJenis');
		$arrtipeabsensi= tipeabsensi();
		// print_r($arrtipeabsensi);exit;

		$arrdata= []; $i=0;
		$arrdata[$i]["id"]= "";
		$arrdata[$i]["nama"]= "";
		$i++;
		for($x=0; $x < count($arrtipeabsensi); $x++)
		{
			$infoid= $arrtipeabsensi[$x]["id"];

			if($reqJenis == $infoid){}
			else
			{
				$arrdata[$i]["id"]= $arrtipeabsensi[$x]["id"];
				$arrdata[$i]["nama"]= $arrtipeabsensi[$x]["nama"];
				$i++;
			}
		}
		echo json_encode($arrdata);
	}

	function gantistatus_add()
	{
		$this->load->library("FileHandler");
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/PermohonanFile');
		$this->load->model('main/KlarifikasiRequired');

		$reqId= $this->input->post('reqId');
		$reqJenis= "klarifikasi_ganti_status";
		$infomenuid= "5107";

		// $filedata= $_FILES["reqLinkFile"];
		// $file= new FileHandler();

		// $checkFile= $file->checkfile($filedata, 6);
		// $namaLinkFile= $file->setlinkfile($filedata);

		// // cek required upload
		// $setdetil= new KlarifikasiRequired();
		// $validasirequired= $setdetil->getCountByParams(array(), " AND A.MENU_ID = '".$infomenuid."' AND B.STATUS_UPLOAD = '1'");
		// // echo $setdetil->query;exit;
		// $infovalidasifile= "1";
		// if($validasirequired > 0)
		// {
		// 	$infovalidasifile= "";
		// }

		// $jumlahfiledata= 0;
		// if(!empty($reqId))
		// {
		// 	$statement= " AND A.PERMOHONAN_TABLE_NAMA = '".$reqJenis."' AND A.PERMOHONAN_TABLE_ID = ".$reqId;
		// 	$setfile= new PermohonanFile();
		// 	$jumlahfiledata= $setfile->getCountByParams(array(), $statement);
		// 	unset($setfile);
		// }

		// $jumlahdata= 0;
		// if(empty($namaLinkFile))
		// {
		// 	if($infovalidasifile == "1")
		// 	{
		// 		if($jumlahfiledata > 0){}
		// 		else
		// 		{
		// 			echo "xxx-Anda belum upload file lampiran.";
		// 			exit();
		// 		}
		// 	}
		// }
		// else
		// {
		// 	if(!empty($checkFile))
		// 	{
		// 		echo "xxx-File harus berformat (pdf/jpg/jpeg)";
		// 		exit();
		// 	}

		// 	$sizeLinkFile= $file->checkmodifsizefile($filedata);
		// 	if(!empty($sizeLinkFile))
		// 	{
		// 		echo "xxx-".$sizeLinkFile;
		// 		exit();
		// 	}

		// 	$jumlahdata= count($filedata);
		// }

		$reqMode= $this->input->post('reqMode');

		$reqTanggalAwal= $this->input->post('reqTanggalAwal');
		$reqPegawaiId= $this->input->post('reqPegawaiId');
		$reqPegawaiJabatanId= $this->input->post('reqPegawaiJabatanId');
		$reqPegawaiPangkatId= $this->input->post('reqPegawaiPangkatId');
		$reqJenisKlarifikasi= $this->input->post('reqJenisKlarifikasi');
		$reqKeterangan= $this->input->post('reqKeterangan');
		$reqUbahStatus= $this->input->post('reqUbahStatus');
		$reqStatus= $this->input->post('reqStatus');
		$reqAlasanTolak= $this->input->post('reqAlasanTolak');
		$reqJam= $this->input->post('reqJam');
		$reqTipeAbsenAwal= $this->input->post('reqTipeAbsenAwal');
		$reqTipeAbsenRevisi= $this->input->post('reqTipeAbsenRevisi');
		// echo $reqJam;exit;

		$set= new Klarifikasi();
        $set->setField("KLARIFIKASI_ID", $reqId);
        $set->setField("PEGAWAI_ID", $reqPegawaiId);
        $set->setField("JABATAN_TAMBAHAN_ID", ValToNullDB($reqPegawaiJabatanId));
        $set->setField("PANGKAT_RIWAYAT_ID", ValToNullDB($reqPegawaiPangkatId));
        $set->setField("TANGGAL_MULAI", dateToDBCheck($reqTanggalAwal));
        $set->setField("TANGGAL_SELESAI", dateToDBCheck($reqTanggalAwal));
        $set->setField("JENIS_KLARIFIKASI", $reqJenis);
        $set->setField("KETERANGAN", setQuote($reqKeterangan));
        $set->setField("UBAH_STATUS", $reqUbahStatus);
        $set->setField("STATUS", $reqStatus);
        $set->setField("ALASAN_TOLAK", $reqAlasanTolak);
        $set->setField("KODE", $reqJenisKlarifikasi);

        $set->setField("JAM", $reqJam);
        $set->setField("TIPE_ABSEN_AWAL", $reqTipeAbsenAwal);
        $set->setField("TIPE_ABSEN_REVISI", $reqTipeAbsenRevisi);

        $set->setField("TANGGAL_SURAT", "NULL");

        $set->setField("LAST_USER", $this->LOGIN_USER);
        $set->setField("LAST_UPDATE", "CURRENT_TIMESTAMP");

		$simpan="";
		if($reqMode == "insert")
        {
        	$set->setField("LAST_CREATE_USER", $this->LOGIN_USER);
        	$set->setField("LAST_CREATE_DATE", "CURRENT_TIMESTAMP");
            if($set->insertgantistatus())
            {
            	$reqId= $set->id;
            	$simpan = "1";
            }
        }
        else
        {
        	$set->setField("LAST_UPDATE_USER", $this->LOGIN_USER);
        	$set->setField("LAST_UPDATE_DATE", "CURRENT_TIMESTAMP");
        	if($set->updategantistatus())
            {
            	$simpan = "1";
            }
        }

        if ($simpan == "1") {
        	$set->setField("KLARIFIKASI_ID", $reqId);
            if($set->deleteDetil())
            {
            	if($reqStatus == "Y")
            	{
            		$setdetil= new Klarifikasi();
            		$statement= " AND PEGAWAI_ID = '".$reqPegawaiId."' AND TO_CHAR(A.JAM, 'YYYY-MM-DD HH24:MI:SS') = '".$reqJam."' AND A.VALIDASI = 1";
					$setdetil->selectByParamsAbsensi(array(), -1, -1, $statement);
					// echo $setdetil->query;exit;
					$setdetil->firstRow();
					$reqJenisKlarifikasi= $setdetil->getField("ABSENSI_ID");

					$set->setField("KODE", $reqJenisKlarifikasi);
            	}

            	// echo $set->query;
                if($set->insertdetilgantistatus())
	            {
	                $simpan = "2";
	            }
	            // echo $set->query;
            }
        }

        // proses hapus file
		// $arrdatafile= array();
		// $pfile = new PermohonanFile();
		// $pfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqId, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis));
		// $indexpfile=0;
		// while ($pfile->nextRow()) 
		// {
		// 	$arrdatafile[$indexpfile]['NAMA'] = $pfile->getField("NAMA");
		// 	$arrdatafile[$indexpfile]['LINK_FILE'] = $pfile->getField("LINK_FILE");
		// 	$indexpfile++;
		// }

		// // simpan file sesuai format
  		// $folderfilesimpan= "uploads/".gettanggalperiode($reqTanggalAwal);
        // if(file_exists($folderfilesimpan)){}
		// else
		// {
		// 	makedirs($folderfilesimpan);
		// }

		// $penamaanfile= $reqId."_".str_replace("-", "", $reqTanggalAwal)."_".$reqPegawaiId."_gantistatus_";

		// for($i=0; $i < $jumlahdata; $i++)
		// {
		// 	$namafile= $filedata["name"][$i];
		// 	$fileType= $filedata["type"][$i];
		// 	$datafileupload= $filedata["tmp_name"][$i];
		// 	$filepath= $file->getExtension($namafile);

		// 	if($namafile == ""){}
		// 	else
		// 	{
		// 		$linkfile= $penamaanfile.md5($penamaanfile.$namafile).".".strtolower($filepath);
		// 		$targetsimpan= $folderfilesimpan."/".$linkfile;

		// 		if (move_uploaded_file($datafileupload, $targetsimpan))
		// 		{
		// 			$setfile= new PermohonanFile();
		// 			$setfile->setField("PEGAWAI_ID", $reqPegawaiId);
		// 			$setfile->setField("PERMOHONAN_TABLE_NAMA", $reqJenis);
		// 			$setfile->setField("PERMOHONAN_TABLE_ID", $reqId);
		// 			$setfile->setField("NAMA", $linkfile);
		// 			$setfile->setField("KETERANGAN", $namafile);
		// 			$setfile->setField("LINK_FILE", $targetsimpan);
		// 			$setfile->setField("TIPE", strtolower($fileType));
		// 			$setfile->setField("LAST_USER", $this->LOGIN_USER);
		// 			$setfile->setField("USER_LOGIN_ID", ValToNullDB($this->PEGAWAI_ID));
		// 			$setfile->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->PEGAWAI_ID));
		// 			$setfile->setField("USER_LOGIN_CREATE_ID", ValToNullDB($this->PEGAWAI_ID));
		// 			$setfile->insert();
		// 			unset($setfile);

		// 			$arrdatafile[$indexpfile]['NAMA'] = $linkfile;
		// 			$arrdatafile[$indexpfile]['LINK_FILE'] = $targetsimpan;
		// 			$indexpfile++;
		// 		}
		// 	}
		// }
        // exit;

		// proses hapus file
        // for ($i=0; $i < $indexpfile; $i++)
        // { 
        // 	$infofilecheck=  $arrdatafile[$i]['NAMA'];
        // 	$cfile= new PermohonanFile();
        // 	$cfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqId, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis, "A.NAMA"=>setQuote($infofilecheck, "")));
        // 	$cfile->firstRow();
        // 	$infofilelokasi= $cfile->getField("LINK_FILE");
        // 	if (empty($infofilelokasi))
        // 	{
        // 		unlink($infofilelokasi);
        // 	}
        // }

		if($simpan == "2")
		{
			echo $reqId."-Data berhasil disimpan.";
		}
		else
			echo "xxx-Data gagal disimpan.";
	}

	function gantistatustriger()
	{
		$this->load->model('main/Klarifikasi');

		$set= new Klarifikasi();
		$set->selectByParamsKlarifikasDetil(" AND A.JENIS_KLARIFIKASI = 'klarifikasi_ganti_status' AND COALESCE(NULLIF(KODE, ''), NULL) IS NULL");
		while($set->nextRow())
		{
			$reqPegawaiId= $set->getField("PEGAWAI_ID");
			$reqJam= $set->getField("JAM_INFO");
			$reqRowId= $set->getField("KLARIFIKASI_DETIL_ID");
			// echo $reqJam;exit;

			$setdetil= new Klarifikasi();
    		$statement= " AND PEGAWAI_ID = '".$reqPegawaiId."' AND TO_CHAR(A.JAM, 'YYYY-MM-DD HH24:MI:SS') = '".$reqJam."' AND A.VALIDASI = 1";
			$setdetil->selectByParamsAbsensi(array(), -1, -1, $statement);
			// echo $setdetil->query;exit;
			$setdetil->firstRow();
			$reqJenisKlarifikasi= $setdetil->getField("ABSENSI_ID");
			// echo $reqJenisKlarifikasi;exit;

			$setdetil= new Klarifikasi();
			$setdetil->setField("KODE", $reqJenisKlarifikasi);
			$setdetil->setField("KLARIFIKASI_DETIL_ID", $reqRowId);
			$setdetil->updatedetilkode();
		}

		/*$reqId= $this->input->get('reqId');
		// $reqStatus= $this->input->post('reqStatus');

		$set= new Klarifikasi();
		$set->selectByParamsKlarifikasi(array("A.KLARIFIKASI_ID"=>$reqId));
		// echo $set->query;exit();
		$set->firstRow();
		$reqStatus= $set->getField("STATUS");

		if($reqStatus == "Y")
		{
			$setdetil= new Klarifikasi();
	        $setdetil->setField("STATUS", $reqStatus);
	        $setdetil->setField("KLARIFIKASI_ID", $reqId);
	        if($setdetil->updatedetildata())
            {
            	echo $reqId."-Data berhasil disimpan.";
            }
			else
			{
				echo "xxx-Data gagal disimpan.";
			}
			echo $setdetil->query;
		}
		else
			echo $reqId."-Data berhasil disimpan.";*/
	}

	function ask()
	{
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/SatuanKerja');

		$set= new Klarifikasi;
		
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqStatus= $this->input->get("reqStatus");
		$reqTahun= $this->input->get("reqTahun");
		$reqBulan= $this->input->get("reqBulan");
		$reqPencarian= $this->input->get("reqPencarian");
		$reqKhususDinas= $this->input->get("reqKhususDinas");

		$aColumns = array("KLARIFIKASI_ID", "NOMOR_SURAT_TANGGAL_SURAT", "TANGGAL_MULAI", "TANGGAL_SELESAI", "STATUS_INFO", "KETERANGAN", "SATUAN_KERJA_INFO", "BUKTI_PENDUKUNG");
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
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY KLARIFIKASI_ID asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.KLARIFIKASI_ID desc";
				 
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
			$satuankerjakondisi.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}
		// echo $satuankerjakondisi;exit();

		$statement= " AND A.JENIS_KLARIFIKASI= 'klarifikasi_ask'";

		if(!empty($reqStatus))
		{
			$statement.= " AND A.STATUS = '".$reqStatus."'";
		}

		$searchJson = " AND ( UPPER(A.NOMOR_SURAT) LIKE '%".$reqPencarian."%' OR UPPER(A.KETERANGAN) LIKE '%".strtoupper($reqPencarian)."%' )";
		$allRecord = $set->getCountByParamsAsk(array(), $satuankerjakondisi, $statement);
		//echo $allRecord;
		if($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsAsk(array(), $satuankerjakondisi, $searchJson.$statement);
		
		$set->selectByParamsAsk(array(), $dsplyRange, $dsplyStart, $satuankerjakondisi, $searchJson.$statement, $sOrder);
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
				if($aColumns[$i] == "NOMOR_SURAT_TANGGAL_SURAT")
				{
					$row[] = $set->getField("NOMOR_SURAT")."<br/>".getFormattedDate($set->getField("TANGGAL_SURAT"));
				}
				// 
				elseif($aColumns[$i] == "TANGGAL_MULAI" || $aColumns[$i] == "TANGGAL_SELESAI")
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

	function ask_add()
	{
		$this->load->library("FileHandler");
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/PermohonanFile');
		$this->load->model('main/KlarifikasiRequired');

		$reqId= $this->input->post('reqId');
		$reqJenis= "klarifikasi_ask";
		$infomenuid= "5108";

		$filedata= $_FILES["reqLinkFile"];
		$file= new FileHandler();

		$checkFile= $file->checkfile($filedata, 6);
		$namaLinkFile= $file->setlinkfile($filedata);

		// cek required upload
		$setdetil= new KlarifikasiRequired();
		$validasirequired= $setdetil->getCountByParams(array(), " AND A.MENU_ID = '".$infomenuid."' AND B.STATUS_UPLOAD = '1'");
		// echo $setdetil->query;exit;
		$infovalidasifile= "1";
		if($validasirequired > 0)
		{
			$infovalidasifile= "";
		}

		$jumlahfiledata= 0;
		if(!empty($reqId))
		{
			$statement= " AND A.PERMOHONAN_TABLE_NAMA = '".$reqJenis."' AND A.PERMOHONAN_TABLE_ID = ".$reqId;
			$setfile= new PermohonanFile();
			$jumlahfiledata= $setfile->getCountByParams(array(), $statement);
			unset($setfile);
		}

		$jumlahdata= 0;
		if(empty($namaLinkFile))
		{
			if($infovalidasifile == "1")
			{
				if($jumlahfiledata > 0){}
				else
				{
					echo "xxx-Anda belum upload file lampiran.";
					exit();
				}
			}
		}
		else
		{
			if(!empty($checkFile))
			{
				echo "xxx-File harus berformat (pdf/jpg/jpeg)";
				exit();
			}

			$sizeLinkFile= $file->checkmodifsizefile($filedata);
			if(!empty($sizeLinkFile))
			{
				echo "xxx-".$sizeLinkFile;
				exit();
			}

			$jumlahdata= count($filedata);
		}

		$reqMode= $this->input->post('reqMode');

		$reqTanggalData= $reqTanggalAwal= $this->input->post('reqTanggalAwal');
		$reqTanggalAwalDetik= $this->input->post('reqTanggalAwalDetik');
		$reqTanggalAwal.= " ".$reqTanggalAwalDetik;
		$reqTanggalAkhir= $this->input->post('reqTanggalAkhir');
		$reqTanggalAkhirDetik= $this->input->post('reqTanggalAkhirDetik');
		$reqTanggalAkhir.= " ".$reqTanggalAkhirDetik;
		$reqNoSurat= $this->input->post('reqNoSurat');
		$reqTanggalSurat= $this->input->post('reqTanggalSurat');
		$reqPegawaiId= $this->input->post('reqPegawaiId');
		$reqPegawaiJabatanId= $this->input->post('reqPegawaiJabatanId');
		$reqPegawaiPangkatId= $this->input->post('reqPegawaiPangkatId');
		$reqJenisKlarifikasi= $this->input->post('reqJenisKlarifikasi');
		$reqKeterangan= $this->input->post('reqKeterangan');
		$reqUbahStatus= $this->input->post('reqUbahStatus');
		$reqStatus= $this->input->post('reqStatus');
		$reqAlasanTolak= $this->input->post('reqAlasanTolak');
		$reqSatuanKerjaId= $this->input->post('reqSatuanKerjaId');
		$reqSatuanKerjaStatus= $this->input->post('reqSatuanKerjaStatus');
		$reqJenisFm= $this->input->post('reqJenisFm');
		// echo $reqTanggalAwal;exit;

		$set= new Klarifikasi();
        $set->setField("KLARIFIKASI_ID", $reqId);
        $set->setField("PEGAWAI_ID", ValToNullDB($reqPegawaiId));
        $set->setField("JABATAN_TAMBAHAN_ID", ValToNullDB($reqPegawaiJabatanId));
        $set->setField("PANGKAT_RIWAYAT_ID", ValToNullDB($reqPegawaiPangkatId));
        $set->setField("TANGGAL_MULAI", dateTimeToDBCheck($reqTanggalAwal));
        $set->setField("TANGGAL_SELESAI", dateTimeToDBCheck($reqTanggalAkhir));
        $set->setField("JENIS_KLARIFIKASI", $reqJenis);
        $set->setField("KETERANGAN", setQuote($reqKeterangan));
        $set->setField("UBAH_STATUS", $reqUbahStatus);
        $set->setField("STATUS", $reqStatus);
        $set->setField("ALASAN_TOLAK", $reqAlasanTolak);
        $set->setField("KODE", $reqJenisKlarifikasi);

        $set->setField("SATUAN_KERJA_ID", $reqSatuanKerjaId);
        $set->setField("SATUAN_KERJA_STATUS", $reqSatuanKerjaStatus);
        $set->setField("JENIS_FM", $reqJenisFm);

        $set->setField("NOMOR_SURAT", $reqNoSurat);
        $set->setField("TANGGAL_SURAT", dateToDBCheck($reqTanggalSurat));

        $set->setField("LAST_USER", $this->LOGIN_USER);
        $set->setField("LAST_UPDATE", "CURRENT_TIMESTAMP");

		$simpan="";
		if($reqMode == "insert")
        {
        	$set->setField("LAST_CREATE_USER", $this->LOGIN_USER);
        	$set->setField("LAST_CREATE_DATE", "CURRENT_TIMESTAMP");
            if($set->insertsatuankerja())
            {
            	$reqId= $set->id;
            	$simpan = "1";
            }
        }
        else
        {
        	$set->setField("LAST_UPDATE_USER", $this->LOGIN_USER);
        	$set->setField("LAST_UPDATE_DATE", "CURRENT_TIMESTAMP");
        	if($set->updatesatuankerja())
            {
            	$simpan = "1";
            }
        }

        if ($simpan == "1") {
        	$set->setField("KLARIFIKASI_ID", $reqId);
            if($set->deleteDetil())
            {
                if($set->insertdetilsatuankerja())
	            {
	                $simpan = "2";
	            }
            }
        }

        // proses hapus file
		$arrdatafile= array();
		$pfile = new PermohonanFile();
		$pfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqId, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis));
		$indexpfile=0;
		while ($pfile->nextRow()) 
		{
			$arrdatafile[$indexpfile]['NAMA'] = $pfile->getField("NAMA");
			$arrdatafile[$indexpfile]['LINK_FILE'] = $pfile->getField("LINK_FILE");
			$indexpfile++;
		}

		// simpan file sesuai format
  		$folderfilesimpan= "uploads/".gettanggalperiode($reqTanggalData);
        if(file_exists($folderfilesimpan)){}
		else
		{
			makedirs($folderfilesimpan);
		}

		$penamaanfile= $reqId."_".str_replace("-", "", $reqTanggalData)."_".$reqPegawaiId."_ask_";

		for($i=0; $i < $jumlahdata; $i++)
		{
			$namafile= $filedata["name"][$i];
			$fileType= $filedata["type"][$i];
			$datafileupload= $filedata["tmp_name"][$i];
			$filepath= $file->getExtension($namafile);

			if($namafile == ""){}
			else
			{
				$linkfile= $penamaanfile.md5($penamaanfile.$namafile).".".strtolower($filepath);
				$targetsimpan= $folderfilesimpan."/".$linkfile;

				if (move_uploaded_file($datafileupload, $targetsimpan))
				{
					$setfile= new PermohonanFile();
					$setfile->setField("PEGAWAI_ID", $reqPegawaiId);
					$setfile->setField("PERMOHONAN_TABLE_NAMA", $reqJenis);
					$setfile->setField("PERMOHONAN_TABLE_ID", $reqId);
					$setfile->setField("NAMA", $linkfile);
					$setfile->setField("KETERANGAN", $namafile);
					$setfile->setField("LINK_FILE", $targetsimpan);
					$setfile->setField("TIPE", strtolower($fileType));
					$setfile->setField("LAST_USER", $this->LOGIN_USER);
					$setfile->setField("USER_LOGIN_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->setField("USER_LOGIN_CREATE_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->insert();
					unset($setfile);

					$arrdatafile[$indexpfile]['NAMA'] = $linkfile;
					$arrdatafile[$indexpfile]['LINK_FILE'] = $targetsimpan;
					$indexpfile++;
				}
			}
		}
        // exit;

		// proses hapus file
        for ($i=0; $i < $indexpfile; $i++)
        { 
        	$infofilecheck=  $arrdatafile[$i]['NAMA'];
        	$cfile= new PermohonanFile();
        	$cfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqId, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis, "A.NAMA"=>setQuote($infofilecheck, "")));
        	$cfile->firstRow();
        	$infofilelokasi= $cfile->getField("LINK_FILE");
        	if (empty($infofilelokasi))
        	{
        		unlink($infofilelokasi);
        	}
        }

		if($simpan == "2")
			echo $reqId."-Data berhasil disimpan.";
		else
			echo "xxx-Data gagal disimpan.";
	}

	function masukpulangsatuankerja()
	{
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/SatuanKerja');

		$set= new Klarifikasi;
		
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqStatus= $this->input->get("reqStatus");
		$reqTahun= $this->input->get("reqTahun");
		$reqBulan= $this->input->get("reqBulan");
		$reqPencarian= $this->input->get("reqPencarian");
		$reqKhususDinas= $this->input->get("reqKhususDinas");

		$aColumns = array("KLARIFIKASI_ID", "NOMOR_SURAT_TANGGAL_SURAT", "TANGGAL_MULAI", "TANGGAL_SELESAI", "STATUS_INFO", "JENIS_FM_INFO", "KETERANGAN", "SATUAN_KERJA_INFO", "BUKTI_PENDUKUNG");
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
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY KLARIFIKASI_ID asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.KLARIFIKASI_ID desc";
				 
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
			$satuankerjakondisi.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}
		// echo $satuankerjakondisi;exit();

		$statement= " AND A.JENIS_KLARIFIKASI= 'klarifikasi_masuk_pulang_satuan_kerja'";

		if(!empty($reqStatus))
		{
			$statement.= " AND A.STATUS = '".$reqStatus."'";
		}

		$searchJson = " AND ( UPPER(A.NOMOR_SURAT) LIKE '%".$reqPencarian."%' OR UPPER(A.KETERANGAN) LIKE '%".strtoupper($reqPencarian)."%' )";
		$allRecord = $set->getCountByParamsForceMajerUnitKerja(array(), $satuankerjakondisi, $statement);
		//echo $allRecord;
		if($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsForceMajerUnitKerja(array(), $satuankerjakondisi, $searchJson.$statement);
		
		$set->selectByParamsForceMajerUnitKerja(array(), $dsplyRange, $dsplyStart, $satuankerjakondisi, $searchJson.$statement, $sOrder);
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
				if($aColumns[$i] == "NOMOR_SURAT_TANGGAL_SURAT")
				{
					$row[] = $set->getField("NOMOR_SURAT")."<br/>".getFormattedDate($set->getField("TANGGAL_SURAT"));
				}
				// 
				elseif($aColumns[$i] == "TANGGAL_MULAI" || $aColumns[$i] == "TANGGAL_SELESAI")
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

	function masukpulangsatuankerja_add()
	{
		$this->load->library("FileHandler");
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/PermohonanFile');
		$this->load->model('main/KlarifikasiRequired');

		$reqId= $this->input->post('reqId');
		$reqJenis= "klarifikasi_masuk_pulang_satuan_kerja";
		$infomenuid= "5109";

		$filedata= $_FILES["reqLinkFile"];
		$file= new FileHandler();

		$checkFile= $file->checkfile($filedata, 6);
		$namaLinkFile= $file->setlinkfile($filedata);

		// cek required upload
		$setdetil= new KlarifikasiRequired();
		$validasirequired= $setdetil->getCountByParams(array(), " AND A.MENU_ID = '".$infomenuid."' AND B.STATUS_UPLOAD = '1'");
		// echo $setdetil->query;exit;
		$infovalidasifile= "1";
		if($validasirequired > 0)
		{
			$infovalidasifile= "";
		}

		$jumlahfiledata= 0;
		if(!empty($reqId))
		{
			$statement= " AND A.PERMOHONAN_TABLE_NAMA = '".$reqJenis."' AND A.PERMOHONAN_TABLE_ID = ".$reqId;
			$setfile= new PermohonanFile();
			$jumlahfiledata= $setfile->getCountByParams(array(), $statement);
			unset($setfile);
		}

		$jumlahdata= 0;
		if(empty($namaLinkFile))
		{
			if($infovalidasifile == "1")
			{
				if($jumlahfiledata > 0){}
				else
				{
					echo "xxx-Anda belum upload file lampiran.";
					exit();
				}
			}
		}
		else
		{
			if(!empty($checkFile))
			{
				echo "xxx-File harus berformat (pdf/jpg/jpeg)";
				exit();
			}

			$sizeLinkFile= $file->checkmodifsizefile($filedata);
			if(!empty($sizeLinkFile))
			{
				echo "xxx-".$sizeLinkFile;
				exit();
			}

			$jumlahdata= count($filedata);
		}

		$reqMode= $this->input->post('reqMode');

		$reqTanggalData= $reqTanggalAwal= $this->input->post('reqTanggalAwal');
		$reqTanggalAwalDetik= $this->input->post('reqTanggalAwalDetik');
		$reqTanggalAwal.= " ".$reqTanggalAwalDetik;
		$reqTanggalAkhir= $this->input->post('reqTanggalAkhir');
		$reqTanggalAkhirDetik= $this->input->post('reqTanggalAkhirDetik');
		$reqTanggalAkhir.= " ".$reqTanggalAkhirDetik;
		$reqNoSurat= $this->input->post('reqNoSurat');
		$reqTanggalSurat= $this->input->post('reqTanggalSurat');
		$reqPegawaiId= $this->input->post('reqPegawaiId');
		$reqPegawaiJabatanId= $this->input->post('reqPegawaiJabatanId');
		$reqPegawaiPangkatId= $this->input->post('reqPegawaiPangkatId');
		$reqJenisKlarifikasi= $this->input->post('reqJenisKlarifikasi');
		$reqKeterangan= $this->input->post('reqKeterangan');
		$reqUbahStatus= $this->input->post('reqUbahStatus');
		$reqStatus= $this->input->post('reqStatus');
		$reqAlasanTolak= $this->input->post('reqAlasanTolak');
		$reqSatuanKerjaId= $this->input->post('reqSatuanKerjaId');
		$reqSatuanKerjaStatus= $this->input->post('reqSatuanKerjaStatus');
		$reqJenisFm= $this->input->post('reqJenisFm');
		// echo $reqTanggalAwal;exit;

		$set= new Klarifikasi();
        $set->setField("KLARIFIKASI_ID", $reqId);
        $set->setField("PEGAWAI_ID", ValToNullDB($reqPegawaiId));
        $set->setField("JABATAN_TAMBAHAN_ID", ValToNullDB($reqPegawaiJabatanId));
        $set->setField("PANGKAT_RIWAYAT_ID", ValToNullDB($reqPegawaiPangkatId));
        $set->setField("TANGGAL_MULAI", dateTimeToDBCheck($reqTanggalAwal));
        $set->setField("TANGGAL_SELESAI", dateTimeToDBCheck($reqTanggalAkhir));
        $set->setField("JENIS_KLARIFIKASI", $reqJenis);
        $set->setField("KETERANGAN", setQuote($reqKeterangan));
        $set->setField("UBAH_STATUS", $reqUbahStatus);
        $set->setField("STATUS", $reqStatus);
        $set->setField("ALASAN_TOLAK", $reqAlasanTolak);
        $set->setField("KODE", $reqJenisKlarifikasi);

        $set->setField("SATUAN_KERJA_ID", $reqSatuanKerjaId);
        $set->setField("SATUAN_KERJA_STATUS", $reqSatuanKerjaStatus);
        $set->setField("JENIS_FM", $reqJenisFm);

        $set->setField("NOMOR_SURAT", $reqNoSurat);
        $set->setField("TANGGAL_SURAT", dateToDBCheck($reqTanggalSurat));

        $set->setField("LAST_USER", $this->LOGIN_USER);
        $set->setField("LAST_UPDATE", "CURRENT_TIMESTAMP");

		$simpan="";
		if($reqMode == "insert")
        {
        	$set->setField("LAST_CREATE_USER", $this->LOGIN_USER);
        	$set->setField("LAST_CREATE_DATE", "CURRENT_TIMESTAMP");
            if($set->insertsatuankerja())
            {
            	$reqId= $set->id;
            	$simpan = "1";
            }
        }
        else
        {
        	$set->setField("LAST_UPDATE_USER", $this->LOGIN_USER);
        	$set->setField("LAST_UPDATE_DATE", "CURRENT_TIMESTAMP");
        	if($set->updatesatuankerja())
            {
            	$simpan = "1";
            }
        }

        if ($simpan == "1") {
        	$set->setField("KLARIFIKASI_ID", $reqId);
            if($set->deleteDetil())
            {
                if($set->insertdetilsatuankerja())
	            {
	                $simpan = "2";
	            }
            }
        }

        // proses hapus file
		$arrdatafile= array();
		$pfile = new PermohonanFile();
		$pfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqId, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis));
		$indexpfile=0;
		while ($pfile->nextRow()) 
		{
			$arrdatafile[$indexpfile]['NAMA'] = $pfile->getField("NAMA");
			$arrdatafile[$indexpfile]['LINK_FILE'] = $pfile->getField("LINK_FILE");
			$indexpfile++;
		}

		// simpan file sesuai format
  		$folderfilesimpan= "uploads/".gettanggalperiode($reqTanggalData);
        if(file_exists($folderfilesimpan)){}
		else
		{
			makedirs($folderfilesimpan);
		}

		$penamaanfile= $reqId."_".str_replace("-", "", $reqTanggalData)."_".$reqPegawaiId."_masukpulangsatuankerja_";

		for($i=0; $i < $jumlahdata; $i++)
		{
			$namafile= $filedata["name"][$i];
			$fileType= $filedata["type"][$i];
			$datafileupload= $filedata["tmp_name"][$i];
			$filepath= $file->getExtension($namafile);

			if($namafile == ""){}
			else
			{
				$linkfile= $penamaanfile.md5($penamaanfile.$namafile).".".strtolower($filepath);
				$targetsimpan= $folderfilesimpan."/".$linkfile;

				if (move_uploaded_file($datafileupload, $targetsimpan))
				{
					$setfile= new PermohonanFile();
					$setfile->setField("PEGAWAI_ID", $reqPegawaiId);
					$setfile->setField("PERMOHONAN_TABLE_NAMA", $reqJenis);
					$setfile->setField("PERMOHONAN_TABLE_ID", $reqId);
					$setfile->setField("NAMA", $linkfile);
					$setfile->setField("KETERANGAN", $namafile);
					$setfile->setField("LINK_FILE", $targetsimpan);
					$setfile->setField("TIPE", strtolower($fileType));
					$setfile->setField("LAST_USER", $this->LOGIN_USER);
					$setfile->setField("USER_LOGIN_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->setField("USER_LOGIN_CREATE_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->insert();
					unset($setfile);

					$arrdatafile[$indexpfile]['NAMA'] = $linkfile;
					$arrdatafile[$indexpfile]['LINK_FILE'] = $targetsimpan;
					$indexpfile++;
				}
			}
		}
        // exit;

		// proses hapus file
        for ($i=0; $i < $indexpfile; $i++)
        { 
        	$infofilecheck=  $arrdatafile[$i]['NAMA'];
        	$cfile= new PermohonanFile();
        	$cfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqId, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis, "A.NAMA"=>setQuote($infofilecheck, "")));
        	$cfile->firstRow();
        	$infofilelokasi= $cfile->getField("LINK_FILE");
        	if (empty($infofilelokasi))
        	{
        		unlink($infofilelokasi);
        	}
        }

		if($simpan == "2")
			echo $reqId."-Data berhasil disimpan.";
		else
			echo "xxx-Data gagal disimpan.";
	}

	function masukpulangindividu()
	{
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/SatuanKerja');

		$set= new Klarifikasi;
		
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqStatus= $this->input->get("reqStatus");
		$reqTahun= $this->input->get("reqTahun");
		$reqBulan= $this->input->get("reqBulan");
		$reqPencarian= $this->input->get("reqPencarian");
		$reqKhususDinas= $this->input->get("reqKhususDinas");

		$aColumns = array("KLARIFIKASI_ID", "NOMOR_SURAT_TANGGAL_SURAT", "NIP_NAMA", "TANGGAL_MULAI", "TANGGAL_SELESAI", "STATUS_INFO", "JENIS_FM_INFO", "KETERANGAN", "ALASAN_TOLAK", "BUKTI_PENDUKUNG");
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
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY KLARIFIKASI_ID asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.KLARIFIKASI_ID desc";
				 
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
				AND XXX.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
		}
		else
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND XXX.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
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
			$satuankerjakondisi.= " AND XXX.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}
		// echo $satuankerjakondisi;exit();

		$statement= " AND A.JENIS_KLARIFIKASI= 'klarifikasi_masuk_pulang_individu'";

		if(!empty($reqStatus))
		{
			$statement.= " AND A.STATUS = '".$reqStatus."'";
		}

		$searchJson = " AND ( UPPER(A.NOMOR_SURAT) LIKE '%".$reqPencarian."%' OR UPPER(A.KETERANGAN) LIKE '%".strtoupper($reqPencarian)."%' )";
		$allRecord = $set->getCountByParamsForceMajerIndividu(array(), $satuankerjakondisi, $statement);
		//echo $allRecord;
		if($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsForceMajerIndividu(array(), $satuankerjakondisi, $searchJson.$statement);
		
		$set->selectByParamsForceMajerIndividu(array(), $dsplyRange, $dsplyStart, $satuankerjakondisi, $searchJson.$statement, $sOrder);
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
					$row[] = $set->getField("NIP_BARU")."<br/>".$set->getField("NAMA_LENGKAP");
				}
				elseif($aColumns[$i] == "NOMOR_SURAT_TANGGAL_SURAT")
				{
					$row[] = $set->getField("NOMOR_SURAT")."<br/>".getFormattedDate($set->getField("TANGGAL_SURAT"));
				}
				// 
				elseif($aColumns[$i] == "TANGGAL_MULAI" || $aColumns[$i] == "TANGGAL_SELESAI")
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

	function masukpulangindividu_add()
	{
		$this->load->library("FileHandler");
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/PermohonanFile');
		$this->load->model('main/KlarifikasiRequired');

		$reqId= $this->input->post('reqId');
		$reqJenis= "klarifikasi_masuk_pulang_individu";
		$infomenuid= "5110";

		$filedata= $_FILES["reqLinkFile"];
		$file= new FileHandler();

		$checkFile= $file->checkfile($filedata, 6);
		$namaLinkFile= $file->setlinkfile($filedata);

		// cek required upload
		$setdetil= new KlarifikasiRequired();
		$validasirequired= $setdetil->getCountByParams(array(), " AND A.MENU_ID = '".$infomenuid."' AND B.STATUS_UPLOAD = '1'");
		// echo $setdetil->query;exit;
		$infovalidasifile= "1";
		if($validasirequired > 0)
		{
			$infovalidasifile= "";
		}

		$jumlahfiledata= 0;
		if(!empty($reqId))
		{
			$statement= " AND A.PERMOHONAN_TABLE_NAMA = '".$reqJenis."' AND A.PERMOHONAN_TABLE_ID = ".$reqId;
			$setfile= new PermohonanFile();
			$jumlahfiledata= $setfile->getCountByParams(array(), $statement);
			unset($setfile);
		}

		$jumlahdata= 0;
		if(empty($namaLinkFile))
		{
			if($infovalidasifile == "1")
			{
				if($jumlahfiledata > 0){}
				else
				{
					echo "xxx-Anda belum upload file lampiran.";
					exit();
				}
			}
		}
		else
		{
			if(!empty($checkFile))
			{
				echo "xxx-File harus berformat (pdf/jpg/jpeg)";
				exit();
			}

			$sizeLinkFile= $file->checkmodifsizefile($filedata);
			if(!empty($sizeLinkFile))
			{
				echo "xxx-".$sizeLinkFile;
				exit();
			}

			$jumlahdata= count($filedata);
		}

		$reqMode= $this->input->post('reqMode');

		$reqTanggalData= $reqTanggalAwal= $this->input->post('reqTanggalAwal');
		$reqTanggalAwalDetik= $this->input->post('reqTanggalAwalDetik');
		$reqTanggalAwal.= " ".$reqTanggalAwalDetik;
		$reqTanggalAkhir= $this->input->post('reqTanggalAkhir');
		$reqTanggalAkhirDetik= $this->input->post('reqTanggalAkhirDetik');
		$reqTanggalAkhir.= " ".$reqTanggalAkhirDetik;
		$reqNoSurat= $this->input->post('reqNoSurat');
		$reqTanggalSurat= $this->input->post('reqTanggalSurat');
		$reqPegawaiId= $this->input->post('reqPegawaiId');
		$reqPegawaiJabatanId= $this->input->post('reqPegawaiJabatanId');
		$reqPegawaiPangkatId= $this->input->post('reqPegawaiPangkatId');
		$reqJenisKlarifikasi= $this->input->post('reqJenisKlarifikasi');
		$reqKeterangan= $this->input->post('reqKeterangan');
		$reqUbahStatus= $this->input->post('reqUbahStatus');
		$reqStatus= $this->input->post('reqStatus');
		$reqAlasanTolak= $this->input->post('reqAlasanTolak');
		$reqSatuanKerjaId= $this->input->post('reqSatuanKerjaId');
		$reqSatuanKerjaStatus= $this->input->post('reqSatuanKerjaStatus');
		$reqJenisFm= $this->input->post('reqJenisFm');
		// echo $reqTanggalAwal;exit;

		$set= new Klarifikasi();
        $set->setField("KLARIFIKASI_ID", $reqId);
        $set->setField("TANGGAL_MULAI", dateTimeToDBCheck($reqTanggalAwal));
        $set->setField("TANGGAL_SELESAI", dateTimeToDBCheck($reqTanggalAkhir));
        $set->setField("JENIS_KLARIFIKASI", $reqJenis);
        $set->setField("KETERANGAN", setQuote($reqKeterangan));
        $set->setField("UBAH_STATUS", $reqUbahStatus);
        $set->setField("STATUS", $reqStatus);
        $set->setField("ALASAN_TOLAK", $reqAlasanTolak);
        $set->setField("KODE", $reqJenisKlarifikasi);

        $set->setField("SATUAN_KERJA_ID", ValToNullDB($reqSatuanKerjaId));
        $set->setField("SATUAN_KERJA_STATUS", $reqSatuanKerjaStatus);
        $set->setField("JENIS_FM", $reqJenisFm);

        $set->setField("NOMOR_SURAT", $reqNoSurat);
        $set->setField("TANGGAL_SURAT", dateToDBCheck($reqTanggalSurat));

        $set->setField("LAST_USER", $this->LOGIN_USER);
        $set->setField("LAST_UPDATE", "CURRENT_TIMESTAMP");

		$simpan="";
		if($reqMode == "insert")
        {
        	$set->setField("LAST_CREATE_USER", $this->LOGIN_USER);
        	$set->setField("LAST_CREATE_DATE", "CURRENT_TIMESTAMP");
            if($set->insertsatuankerja())
            {
            	$reqId= $set->id;
            	$simpan = "1";
            }
        }
        else
        {
        	$set->setField("LAST_UPDATE_USER", $this->LOGIN_USER);
        	$set->setField("LAST_UPDATE_DATE", "CURRENT_TIMESTAMP");
        	if($set->updatesatuankerja())
            {
            	$simpan = "1";
            }
        }

        if($simpan == "1")
        {
        	$set->setField("KLARIFIKASI_ID", $reqId);
        	if($set->deleteDetil())
            {
	        	for($i=0; $i < count($reqPegawaiId); $i++)
	        	{
	        		$set->setField("PEGAWAI_ID", $reqPegawaiId[$i]);
	        		$set->setField("JABATAN_TAMBAHAN_ID", ValToNullDB($reqPegawaiJabatanId[$i]));
	        		$set->setField("PANGKAT_RIWAYAT_ID", ValToNullDB($reqPegawaiPangkatId[$i]));
	        		if($set->insertdetilsatuankerja()){}
	        	}
	        	$simpan = "2";
            }
        }

        // proses hapus file
		$arrdatafile= array();
		$pfile = new PermohonanFile();
		$pfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqId, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis));
		$indexpfile=0;
		while ($pfile->nextRow()) 
		{
			$arrdatafile[$indexpfile]['NAMA'] = $pfile->getField("NAMA");
			$arrdatafile[$indexpfile]['LINK_FILE'] = $pfile->getField("LINK_FILE");
			$indexpfile++;
		}

		// simpan file sesuai format
  		$folderfilesimpan= "uploads/".gettanggalperiode($reqTanggalData);
        if(file_exists($folderfilesimpan)){}
		else
		{
			makedirs($folderfilesimpan);
		}

		$penamaanfile= $reqId."_".str_replace("-", "", $reqTanggalData)."_".$reqPegawaiId."_masukpulangindividu_";

		for($i=0; $i < $jumlahdata; $i++)
		{
			$namafile= $filedata["name"][$i];
			$fileType= $filedata["type"][$i];
			$datafileupload= $filedata["tmp_name"][$i];
			$filepath= $file->getExtension($namafile);

			if($namafile == ""){}
			else
			{
				$linkfile= $penamaanfile.md5($penamaanfile.$namafile).".".strtolower($filepath);
				$targetsimpan= $folderfilesimpan."/".$linkfile;

				if (move_uploaded_file($datafileupload, $targetsimpan))
				{
					$setfile= new PermohonanFile();
					$setfile->setField("PEGAWAI_ID", $reqPegawaiId);
					$setfile->setField("PERMOHONAN_TABLE_NAMA", $reqJenis);
					$setfile->setField("PERMOHONAN_TABLE_ID", $reqId);
					$setfile->setField("NAMA", $linkfile);
					$setfile->setField("KETERANGAN", $namafile);
					$setfile->setField("LINK_FILE", $targetsimpan);
					$setfile->setField("TIPE", strtolower($fileType));
					$setfile->setField("LAST_USER", $this->LOGIN_USER);
					$setfile->setField("USER_LOGIN_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->setField("USER_LOGIN_CREATE_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->insert();
					unset($setfile);

					$arrdatafile[$indexpfile]['NAMA'] = $linkfile;
					$arrdatafile[$indexpfile]['LINK_FILE'] = $targetsimpan;
					$indexpfile++;
				}
			}
		}
        // exit;

		// proses hapus file
        for ($i=0; $i < $indexpfile; $i++)
        { 
        	$infofilecheck=  $arrdatafile[$i]['NAMA'];
        	$cfile= new PermohonanFile();
        	$cfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqId, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis, "A.NAMA"=>setQuote($infofilecheck, "")));
        	$cfile->firstRow();
        	$infofilelokasi= $cfile->getField("LINK_FILE");
        	if (empty($infofilelokasi))
        	{
        		unlink($infofilelokasi);
        	}
        }

		if($simpan == "2")
			echo $reqId."-Data berhasil disimpan.";
		else
			echo "xxx-Data gagal disimpan.";
	}

	function cuti()
	{
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/SatuanKerja');

		$set= new Klarifikasi;
		
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqStatus= $this->input->get("reqStatus");
		$reqTahun= $this->input->get("reqTahun");
		$reqBulan= $this->input->get("reqBulan");
		$reqPencarian= $this->input->get("reqPencarian");
		$reqKhususDinas= $this->input->get("reqKhususDinas");

		$aColumns = array("NIP_BARU_NAMA_LENGKAP", "SATUAN_KERJA_INFO", "JENIS_CUTI_NAMA", "TANGGAL_MULAI", "TANGGAL_SELESAI");
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
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY NIP_BARU_NAMA_LENGKAP asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.TANGGAL_MULAI DESC";
				 
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
				AND XXX.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
		}
		else
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND XXX.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
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
			$satuankerjakondisi.= " AND XXX.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}
		// echo $satuankerjakondisi;exit();

		// $statement= " AND A.JENIS_KLARIFIKASI= 'klarifikasi_masuk_pulang_individu'";

		if(!empty($reqStatus))
		{
			$statement.= " AND A.JENIS_CUTI  = '".$reqStatus."'";
		}

		if(!empty($reqBulan) && !empty($reqTahun))
		{
			// $statement.= " AND TO_CHAR(TO_DATE(A.TANGGAL_MULAI, 'YYYY-MM-DD'), 'MMYYYY') = '".$reqBulan.$reqTahun."'";
			$statement.= " 
			AND 
			(
				TO_CHAR(TO_DATE(A.TANGGAL_MULAI, 'YYYY-MM-DD'), 'MMYYYY') = '".$reqBulan.$reqTahun."'
				OR TO_CHAR(TO_DATE(A.TANGGAL_SELESAI, 'YYYY-MM-DD'), 'MMYYYY') = '".$reqBulan.$reqTahun."'
			)
			";
		}

		$searchJson = " AND ( UPPER(A.NO_SURAT) LIKE '%".$reqPencarian."%' OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(A.KETERANGAN) LIKE '%".strtoupper($reqPencarian)."%' )";
		$allRecord = $set->getCountByParamsCuti(array(), $satuankerjakondisi, $statement);
		//echo $allRecord;
		if($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsCuti(array(), $satuankerjakondisi, $searchJson.$statement);
		
		$set->selectByParamsCuti(array(), $dsplyRange, $dsplyStart, $satuankerjakondisi, $searchJson.$statement, $sOrder);
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
				elseif($aColumns[$i] == "NOMOR_SURAT_TANGGAL_SURAT")
				{
					$row[] = $set->getField("NOMOR_SURAT")."<br/>".getFormattedDate($set->getField("TANGGAL_SURAT"));
				}
				// 
				elseif($aColumns[$i] == "TANGGAL_MULAI" || $aColumns[$i] == "TANGGAL_SELESAI")
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

	function kalkulasiulang()
	{
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/SatuanKerja');

		$set= new Klarifikasi;
		
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqTahun= $this->input->get("reqTahun");
		$reqBulan= $this->input->get("reqBulan");
		$reqPencarian= $this->input->get("reqPencarian");
		$reqKhususDinas= $this->input->get("reqKhususDinas");

		$aColumns = array("NIP_NAMA", "TANGGAL_MULAI", "TANGGAL_SELESAI", "STATUS_INFO", "KETERANGAN", "ALASAN_TOLAK", "KLARIFIKASI_ID");
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
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY NOMOR_SURAT asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.TANGGAL_SURAT desc";
				 
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
				AND XXX.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
		}
		else
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND XXX.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
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
			$satuankerjakondisi.= " AND XXX.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}
		// echo $satuankerjakondisi;exit();

		$statement= " AND A.JENIS_KLARIFIKASI= 'klarifikasi_kalkulasi_ulang'";

		$searchJson = " AND ( UPPER(A.NOMOR_SURAT) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(presensi.info_pegawai_klarifikasi(A.KLARIFIKASI_ID)) LIKE '%".strtoupper($reqPencarian)."%' )";
		$allRecord = $set->getCountByParamsKalkulasiUlang(array(), $satuankerjakondisi, $statement);
		//echo $allRecord;
		if($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsKalkulasiUlang(array(), $satuankerjakondisi, $searchJson.$statement);
		
		$set->selectByParamsKalkulasiUlang(array(), $dsplyRange, $dsplyStart, $satuankerjakondisi, $searchJson.$statement, $sOrder);
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
					$row[] = $set->getField("NIP_BARU")."<br/>".$set->getField("NAMA_LENGKAP");
				}
				elseif($aColumns[$i] == "TANGGAL_MULAI" || $aColumns[$i] == "TANGGAL_SELESAI")
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

	function kalkulasiulangadd()
	{
		$this->load->library("FileHandler");
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/KlarifikasiRequired');
		$this->load->model('main/PermohonanFile');

		$reqId= $this->input->post('reqId');
		$reqJenis= "klarifikasi_kalkulasi_ulang";
		$infomenuid= "5005";

		/*$filedata= $_FILES["reqLinkFile"];
		$file= new FileHandler();

		$checkFile= $file->checkfile($filedata, 6);
		$namaLinkFile= $file->setlinkfile($filedata);

		// cek required upload
		$setdetil= new KlarifikasiRequired();
		$validasirequired= $setdetil->getCountByParams(array(), " AND A.MENU_ID = '".$infomenuid."' AND B.STATUS_UPLOAD = '1'");
		// echo $setdetil->query;exit;
		$infovalidasifile= "1";
		if($validasirequired > 0)
		{
			$infovalidasifile= "";
		}

		$jumlahfiledata= 0;
		if(!empty($reqId))
		{
			$statement= " AND A.PERMOHONAN_TABLE_NAMA = '".$reqJenis."' AND A.PERMOHONAN_TABLE_ID = ".$reqId;
			$setfile= new PermohonanFile();
			$jumlahfiledata= $setfile->getCountByParams(array(), $statement);
			unset($setfile);
		}
		
		$jumlahdata= 0;
		if(empty($namaLinkFile))
		{
			if($infovalidasifile == "1")
			{
				if($jumlahfiledata > 0){}
				else
				{
					echo "xxx-Anda belum upload file lampiran.";
					exit();
				}
			}
		}
		else
		{
			if(!empty($checkFile))
			{
				echo "xxx-File harus berformat (pdf/jpg/jpeg)";
				exit();
			}

			$sizeLinkFile= $file->checkmodifsizefile($filedata);
			if(!empty($sizeLinkFile))
			{
				echo "xxx-".$sizeLinkFile;
				exit();
			}

			$jumlahdata= count($filedata);
		}*/

		$reqMode= $this->input->post('reqMode');

		$reqTanggalData= $reqTanggalAwal= $this->input->post('reqTanggalAwal');
		$reqTanggalAwalDetik= $this->input->post('reqTanggalAwalDetik');
		$reqTanggalAwal.= " ".$reqTanggalAwalDetik;
		$reqTanggalAkhir= $this->input->post('reqTanggalAkhir');
		$reqTanggalAkhirDetik= $this->input->post('reqTanggalAkhirDetik');
		$reqTanggalAkhir.= " ".$reqTanggalAkhirDetik;
		$reqNoSurat= $this->input->post('reqNoSurat');
		$reqTanggalSurat= $this->input->post('reqTanggalSurat');
		$reqKeterangan= $this->input->post('reqKeterangan');
		$reqUbahStatus= $this->input->post('reqUbahStatus');
		$reqStatus= $this->input->post('reqStatus');
		$reqAlasanTolak= $this->input->post('reqAlasanTolak');

		$reqPegawaiId= $this->input->post('reqPegawaiId');
		$reqPegawaiJabatanId= $this->input->post('reqPegawaiJabatanId');
		$reqPegawaiPangkatId= $this->input->post('reqPegawaiPangkatId');
		$reqJenisKlarifikasi= $this->input->post('reqJenisKlarifikasi');

		$set= new Klarifikasi();
        $set->setField("KLARIFIKASI_ID", $reqId);
        $set->setField("TANGGAL_SURAT", dateToDBCheck($reqTanggalSurat));
        $set->setField("TANGGAL_MULAI", dateTimeToDBCheck($reqTanggalAwal));
        $set->setField("TANGGAL_SELESAI", dateTimeToDBCheck($reqTanggalAkhir));
        $set->setField("JENIS_KLARIFIKASI", $reqJenis);
        $set->setField("KETERANGAN", setQuote($reqKeterangan));
        $set->setField("UBAH_STATUS", $reqUbahStatus);
        $set->setField("STATUS", $reqStatus);
        $set->setField("ALASAN_TOLAK", $reqAlasanTolak);
        $set->setField("KODE", $reqJenisKlarifikasi);
        $set->setField("NOMOR_SURAT", $reqNoSurat);
        $set->setField("LAST_CREATE_USER", $this->LOGIN_USER);
        $set->setField("LAST_CREATE_DATE", "CURRENT_TIMESTAMP");
        $set->setField("LAST_USER", $this->LOGIN_USER);
        $set->setField("LAST_UPDATE", "CURRENT_TIMESTAMP");
        $set->setField("LAST_UPDATE_USER", $this->LOGIN_USER);
        $set->setField("LAST_UPDATE_DATE", "CURRENT_TIMESTAMP");

		$simpan="";
		if($reqMode == "insert")
        {
            if($set->insert())
            {
            	$reqId= $set->id;
            	$simpan = "1";
            }
        }
        else
        {
        	if($set->update())
            {
            	$simpan = "1";
            }
        }

        if($simpan == "1")
        {
        	$set->setField("KLARIFIKASI_ID", $reqId);
        	if($set->deleteDetil())
            {
	        	for($i=0; $i < count($reqPegawaiId); $i++)
	        	{
	        		$set->setField("PEGAWAI_ID", $reqPegawaiId[$i]);
	        		$set->setField("JABATAN_TAMBAHAN_ID", ValToNullDB($reqPegawaiJabatanId[$i]));
	        		$set->setField("PANGKAT_RIWAYAT_ID", ValToNullDB($reqPegawaiPangkatId[$i]));
	        		if($set->insertDetil()){}
	        	}
            }
        }

        // proses hapus file
        /*$arrdatafile= array();
		$pfile = new PermohonanFile();
		$pfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqId, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis));
		$indexpfile=0;
		while ($pfile->nextRow()) 
		{
			$arrdatafile[$indexpfile]['NAMA'] = $pfile->getField("NAMA");
			$arrdatafile[$indexpfile]['LINK_FILE'] = $pfile->getField("LINK_FILE");
			$indexpfile++;
		}

		$folderfilesimpan= "uploads/".gettanggalperiode($reqTanggalData);
        if(file_exists($folderfilesimpan)){}
		else
		{
			makedirs($folderfilesimpan);
		}

		$penamaanfile= $reqId."_".str_replace("-", "", $reqTanggalData)."_kalkulasiulang_";

		for($i=0; $i < $jumlahdata; $i++)
		{
			$namafile= $filedata["name"][$i];
			$fileType= $filedata["type"][$i];
			$datafileupload= $filedata["tmp_name"][$i];
			$filepath= $file->getExtension($namafile);

			if($namafile == ""){}
			else
			{
				$linkfile= $penamaanfile.md5($penamaanfile.$namafile).".".strtolower($filepath);
				$targetsimpan= $folderfilesimpan."/".$linkfile;

				if (move_uploaded_file($datafileupload, $targetsimpan))
				{
					$setfile= new PermohonanFile();
					$setfile->setField("PEGAWAI_ID", "kalkulasiulang");
					$setfile->setField("PERMOHONAN_TABLE_NAMA", $reqJenis);
					$setfile->setField("PERMOHONAN_TABLE_ID", $reqId);
					$setfile->setField("NAMA", $linkfile);
					$setfile->setField("KETERANGAN", $namafile);
					$setfile->setField("LINK_FILE", $targetsimpan);
					$setfile->setField("TIPE", strtolower($fileType));
					$setfile->setField("LAST_USER", $this->LOGIN_USER);
					$setfile->setField("USER_LOGIN_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->setField("USER_LOGIN_CREATE_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->insert();
					unset($setfile);

					$arrdatafile[$indexpfile]['NAMA'] = $linkfile;
					$arrdatafile[$indexpfile]['LINK_FILE'] = $targetsimpan;
					$indexpfile++;
				}
			}
		}
        // exit;

        // proses hapus file
        for ($i=0; $i < $indexpfile; $i++)
        { 
			$infofilecheck=  $arrdatafile[$i]['NAMA'];
			$cfile= new PermohonanFile();
			$cfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqId, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis, "A.NAMA"=>setQuote($infofilecheck, "")));
			$cfile->firstRow();
			$infofilelokasi= $cfile->getField("LINK_FILE");
			if (empty($infofilelokasi))
			{
				unlink($infofilelokasi);
			}
		}*/

		if($simpan == "1")
			echo $reqId."-Data berhasil disimpan.";
		else
			echo "xxx-Data gagal disimpan.";
	}

	function paktaintegritas()
	{
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/SatuanKerja');

		$set= new Klarifikasi;
		
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqStatus= $this->input->get("reqStatus");
		$reqTahun= $this->input->get("reqTahun");
		$reqBulan= $this->input->get("reqBulan");
		$reqPencarian= $this->input->get("reqPencarian");
		$reqKhususDinas= $this->input->get("reqKhususDinas");

		$aColumns = array("KLARIFIKASI_ID", "NIP_NAMA", "TANGGAL_MULAI", "KODE", "STATUS_INFO", "KETERANGAN", "ALASAN_TOLAK", "BUKTI_PENDUKUNG");
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
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY KLARIFIKASI_ID asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.KLARIFIKASI_ID desc";
				 
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
				AND XXX.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
			) ";
		}
		else
		{
			$satuankerjakondisi= " 
			AND EXISTS
			(
				SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
				AND XXX.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
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
			$satuankerjakondisi.= " AND XXX.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}
		// echo $satuankerjakondisi;exit();

		$statement= " AND A.JENIS_KLARIFIKASI= 'klarifikasi_pakta_integritas'";

		if(!empty($reqStatus))
		{
			$statement.= " AND A.STATUS = '".$reqStatus."'";
		}

		$searchJson = " AND ( UPPER(A.NIP_BARU) LIKE '%".$reqPencarian."%' OR UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%' )";
		$allRecord = $set->getCountByParamsMasukPulang(array(), $satuankerjakondisi, $statement);
		//echo $allRecord;
		if($reqPencarian == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter =  $set->getCountByParamsMasukPulang(array(), $satuankerjakondisi, $searchJson.$statement);
		
		$set->selectByParamsMasukPulang(array(), $dsplyRange, $dsplyStart, $satuankerjakondisi, $searchJson.$statement, $sOrder);
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
					$row[] = $set->getField("NAMA_LENGKAP")."<br/>".$set->getField("NIP_BARU")."<br/>".$set->getField("PEGAWAI_ID");
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

	function paktaintegritas_option()
	{
		$this->load->model('main/Klarifikasi');

		$reqPegawaiId= $this->input->get('reqPegawaiId');
		$reqTanggal= $this->input->get('reqTanggal');

		$arrdata= []; $i=0;
		$statement= " AND A.PEGAWAI_ID = '".$reqPegawaiId."'";
		$reqPeriode= getperiodetanggal($reqTanggal);
		$set= new Klarifikasi();
		$set->selectByParamsAbsensiKoreksi($reqPeriode, $statement);
		$infocheck= $set->errorMsg;
		if(!empty($infocheck)){}
		else
		{
			$set->firstRow();
			// echo $set->query;exit;
			$infohari= (int)getYear($reqTanggal);
			$infomasuk= $set->getField("MASUK_".$infohari);
			$infopulang= $set->getField("PULANG_".$infohari);
			$infoexmasuk= $set->getField("EX_MASUK_".$infohari);
			// echo $infomasuk."--".$infopulang."--".$infoexmasuk;exit;

			// kondisi data
			$statement= " AND 1=2";
			if($infomasuk == "MTSK" && $infopulang == "PTSK")
			{
				if(empty($infoexmasuk) || $infoexmasuk == "AASK")
					$statement= " AND A.KODE IN ('HMPI','HPPI','ASKD')";
				else
					$statement= " AND A.KODE IN ('HMPI','HPPI')";
			}
			elseif($infomasuk == "MTSK" || $infomasuk == "AM" || $infomasuk == "MT")
			{
				if(empty($infoexmasuk) || $infoexmasuk == "AASK")
				{
					if($infopulang == "AP")
						$statement= " AND A.KODE IN ('HMPI','HPPI','ASKD')";
					else
						$statement= " AND A.KODE IN ('HMPI','ASKD')";
				}
				elseif($infopulang == "AP")
					$statement= " AND A.KODE IN ('HMPI','HPPI')";
				else
					$statement= " AND A.KODE IN ('HMPI')";
			}
			elseif($infopulang == "PTSK" || $infopulang == "AP" || $infopulang == "PC")
			{
				if(empty($infoexmasuk) || $infoexmasuk == "AASK")
					$statement= " AND A.KODE IN ('HPPI','ASKD')";
				else
					$statement= " AND A.KODE IN ('HPPI')";
			}
			else
			{
				if($infoexmasuk == "ASKD")
				{
					if($infomasuk == "AM" && $infopulang == "AP")
						$statement= " AND A.KODE IN ('HMPI/HPPI')";
				}
				elseif($infomasuk == "AM" && $infopulang == "AP"){}
				else
					$statement= " AND A.KODE IN ('HMPI/ASKD/HPPI')";
			}

			$set= new Klarifikasi();
			$set->selectByParamsJenisKlarifikasi(array(),-1,-1,$statement);
			// echo $set->query;exit();

			$arrdata[$i]["KODE"]= "";
			$arrdata[$i]["NAMA"]= "Pilih";
			$i++;
			while ($set->nextRow()) 
			{
				$infokode= $set->getField("KODE");
				$arrdata[$i]["KODE"]= $infokode;
				$arrdata[$i]["NAMA"]= $set->getField("NAMA")." (".$arrdata[$i]["KODE"].")";
				$i++;
			}
		}
		echo json_encode($arrdata);
	}

	function paktaintegritas_add()
	{
		$this->load->library("FileHandler");
		$this->load->model('main/Klarifikasi');
		$this->load->model('main/PermohonanFile');
		$this->load->model('main/KlarifikasiRequired');

		$reqId= $this->input->post('reqId');
		$reqJenis= "klarifikasi_pakta_integritas";
		$infomenuid= "5112";

		$filedata= $_FILES["reqLinkFile"];
		$file= new FileHandler();

		$checkFile= $file->checkfile($filedata, 6);
		$namaLinkFile= $file->setlinkfile($filedata);

		// cek required upload
		$setdetil= new KlarifikasiRequired();
		$validasirequired= $setdetil->getCountByParams(array(), " AND A.MENU_ID = '".$infomenuid."' AND B.STATUS_UPLOAD = '1'");
		// echo $setdetil->query;exit;
		$infovalidasifile= "1";
		if($validasirequired > 0)
		{
			$infovalidasifile= "";
		}

		$jumlahfiledata= 0;
		if(!empty($reqId))
		{
			$statement= " AND A.PERMOHONAN_TABLE_NAMA = '".$reqJenis."' AND A.PERMOHONAN_TABLE_ID = ".$reqId;
			$setfile= new PermohonanFile();
			$jumlahfiledata= $setfile->getCountByParams(array(), $statement);
			unset($setfile);
		}

		$jumlahdata= 0;
		if(empty($namaLinkFile))
		{
			if($infovalidasifile == "1")
			{
				if($jumlahfiledata > 0){}
				else
				{
					echo "xxx-Anda belum upload file lampiran.";
					exit();
				}
			}
		}
		else
		{
			if(!empty($checkFile))
			{
				echo "xxx-File harus berformat (pdf/jpg/jpeg)";
				exit();
			}

			$sizeLinkFile= $file->checkmodifsizefile($filedata);
			if(!empty($sizeLinkFile))
			{
				echo "xxx-".$sizeLinkFile;
				exit();
			}

			$jumlahdata= count($filedata);
		}

		$reqMode= $this->input->post('reqMode');

		$reqTanggalAwal= $this->input->post('reqTanggalAwal');
		$reqPegawaiId= $this->input->post('reqPegawaiId');
		$reqPegawaiJabatanId= $this->input->post('reqPegawaiJabatanId');
		$reqPegawaiPangkatId= $this->input->post('reqPegawaiPangkatId');
		$reqJenisKlarifikasi= $this->input->post('reqJenisKlarifikasi');
		$reqKeterangan= $this->input->post('reqKeterangan');
		$reqUbahStatus= $this->input->post('reqUbahStatus');
		$reqStatus= $this->input->post('reqStatus');
		$reqAlasanTolak= $this->input->post('reqAlasanTolak');

		$set= new Klarifikasi();
        $set->setField("KLARIFIKASI_ID", $reqId);
        $set->setField("PEGAWAI_ID", $reqPegawaiId);
        $set->setField("JABATAN_TAMBAHAN_ID", ValToNullDB($reqPegawaiJabatanId));
        $set->setField("PANGKAT_RIWAYAT_ID", ValToNullDB($reqPegawaiPangkatId));
        $set->setField("TANGGAL_MULAI", dateToDBCheck($reqTanggalAwal));
        $set->setField("TANGGAL_SELESAI", dateToDBCheck($reqTanggalAwal));
        $set->setField("JENIS_KLARIFIKASI", $reqJenis);
        $set->setField("KETERANGAN", setQuote($reqKeterangan));
        $set->setField("UBAH_STATUS", $reqUbahStatus);
        $set->setField("STATUS", $reqStatus);
        $set->setField("ALASAN_TOLAK", $reqAlasanTolak);
        $set->setField("KODE", $reqJenisKlarifikasi);

        $set->setField("TANGGAL_SURAT", "NULL");

        $set->setField("LAST_USER", $this->LOGIN_USER);
        $set->setField("LAST_UPDATE", "CURRENT_TIMESTAMP");

		$simpan="";
		if($reqMode == "insert")
        {
        	$set->setField("LAST_CREATE_USER", $this->LOGIN_USER);
        	$set->setField("LAST_CREATE_DATE", "CURRENT_TIMESTAMP");
            if($set->insert())
            {
            	$reqId= $set->id;
            	$simpan = "1";
            }
        }
        else
        {
        	$set->setField("LAST_UPDATE_USER", $this->LOGIN_USER);
        	$set->setField("LAST_UPDATE_DATE", "CURRENT_TIMESTAMP");
        	if($set->update())
            {
            	$simpan = "1";
            }
        }

        if ($simpan == "1") {
        	$set->setField("KLARIFIKASI_ID", $reqId);
            if($set->deleteDetil())
            {
                if($set->insertDetil())
	            {
	                $simpan = "2";
	            }
            }
        }

        // proses hapus file
        $arrdatafile= array();
		$pfile = new PermohonanFile();
		$pfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqId, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis));
		$indexpfile=0;
		while ($pfile->nextRow()) 
		{
			$arrdatafile[$indexpfile]['NAMA'] = $pfile->getField("NAMA");
			$arrdatafile[$indexpfile]['LINK_FILE'] = $pfile->getField("LINK_FILE");
			$indexpfile++;
		}

        // simpan file sesuai format
        $folderfilesimpan= "uploads/".gettanggalperiode($reqTanggalAwal);
        if(file_exists($folderfilesimpan)){}
		else
		{
			makedirs($folderfilesimpan);
		}

		$penamaanfile= $reqId."_".str_replace("-", "", $reqTanggalAwal)."_".$reqPegawaiId."_paktaintegritas_";

		for($i=0; $i < $jumlahdata; $i++)
		{
			$namafile= $filedata["name"][$i];
			$fileType= $filedata["type"][$i];
			$datafileupload= $filedata["tmp_name"][$i];
			$filepath= $file->getExtension($namafile);

			if($namafile == ""){}
			else
			{
				$linkfile= $penamaanfile.md5($penamaanfile.$namafile).".".strtolower($filepath);
				$targetsimpan= $folderfilesimpan."/".$linkfile;

				if (move_uploaded_file($datafileupload, $targetsimpan))
				{
					$setfile= new PermohonanFile();
					$setfile->setField("PEGAWAI_ID", $reqPegawaiId);
					$setfile->setField("PERMOHONAN_TABLE_NAMA", $reqJenis);
					$setfile->setField("PERMOHONAN_TABLE_ID", $reqId);
					$setfile->setField("NAMA", $linkfile);
					$setfile->setField("KETERANGAN", $namafile);
					$setfile->setField("LINK_FILE", $targetsimpan);
					$setfile->setField("TIPE", strtolower($fileType));
					$setfile->setField("LAST_USER", $this->LOGIN_USER);
					$setfile->setField("USER_LOGIN_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->setField("USER_LOGIN_CREATE_ID", ValToNullDB($this->PEGAWAI_ID));
					$setfile->insert();
					unset($setfile);

					$arrdatafile[$indexpfile]['NAMA'] = $linkfile;
					$arrdatafile[$indexpfile]['LINK_FILE'] = $targetsimpan;
					$indexpfile++;
				}
			}
		}
        // exit;

		// proses hapus file
        for ($i=0; $i < $indexpfile; $i++)
        { 
			$infofilecheck=  $arrdatafile[$i]['NAMA'];
			$cfile= new PermohonanFile();
			$cfile->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqId, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis, "A.NAMA"=>setQuote($infofilecheck, "")));
			$cfile->firstRow();
			$infofilelokasi= $cfile->getField("LINK_FILE");
			if (empty($infofilelokasi))
			{
				unlink($infofilelokasi);
			}
		}

		if($simpan == "2")
			echo $reqId."-Data berhasil disimpan.";
		else
			echo "xxx-Data gagal disimpan.";
	}

	function hapusfile()
	{
		$this->load->model('main/PermohonanFile');
		$reqId= $this->input->get('reqId');

		$setfile= new PermohonanFile();
		$statement= " AND A.PERMOHONAN_FILE_ID = ".$reqId;
		$setfile->selectByParams(array(), -1,-1, $statement);
		// echo $setfile->query;exit;
		$setfile->firstRow();
		$linkfile= $setfile->getField("LINK_FILE");
		// echo $linkfile;

		$setfile->setField("PERMOHONAN_FILE_ID", $reqId);
		if($setfile->delete())
		{
			unlink($linkfile);
		}
		echo "1";
	}

	function settingupload()
	{
		$this->load->model('main/KlarifikasiRequired');
		$set= new KlarifikasiRequired;
		
		$reqPencarian= $this->input->get("reqPencarian");

		$aColumns = array("NAMA", "STATUS_UPLOAD_NAMA", "BATAS_ENTRI", "MENU_ID");
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
			if ( trim(strtoupper($sOrder)) == strtoupper("ORDER BY NOMOR_SURAT asc") )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY A.TANGGAL_SURAT desc";
				 
			}
		}

		if(empty($sOrder))
		{
			$sOrder = " ORDER BY A.MENU_ID";
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

		$searchJson = " AND ( UPPER(A.NAMA) LIKE '%".strtoupper($reqPencarian)."%' )";
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
				$row[] = $set->getField($aColumns[$i]);
			}
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}

	function settingupload_add()
	{
		$this->load->model('main/KlarifikasiRequired');

		$reqId= $this->input->post('reqId');
		$reqRowId= $this->input->post('reqRowId');
		$reqStatusUpload= $this->input->post('reqStatusUpload');
		$reqBatasEntri= $this->input->post('reqBatasEntri');

		// echo $reqId;

		$simpan= "";

		$set= new KlarifikasiRequired();
		$set->setField("MENU_ID", $reqId);
		$set->setField("STATUS_UPLOAD", $reqStatusUpload);
		$set->setField("BATAS_ENTRI", ValToNullDB($reqBatasEntri));

		if(empty($reqRowId))
        {
        	$set->setField("LAST_CREATE_USER", $this->LOGIN_USER);
        	$set->setField("LAST_CREATE_DATE", "CURRENT_TIMESTAMP");
            if($set->insert())
            {
            	$simpan = "1";
            }
        }
        else
        {
        	$set->setField("LAST_UPDATE_USER", $this->LOGIN_USER);
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

	function harilibur()
	{
		$this->load->model('main/HariLibur');
		
		$set= new HariLibur;
		
		$reqTahun = $this->input->get("reqTahun");
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		$aColumns = array('TANGGAL_AWAL', 'TANGGAL_AKHIR', 'TANGGAL_FIX', 'NAMA', 'KETERANGAN', 'HARI_LIBUR_ID');
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
			if ( trim($sOrder) == "ORDER BY NAMA asc" )
			{
				/*
				* If there is no order by clause - ORDER BY INDEX COLUMN!!! DON'T DELETE IT!
				* If there is no order by clause there might be bugs in table display.
				* No order by clause means that the db is not responsible for the data ordering,
				* which means that the same row can be displayed in two pages - while
				* another row will not be displayed at all.
				*/
				$sOrder = " ORDER BY NAMA DESC";
				
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
		
		if($reqTahun == "")
		{
			$statement = " AND TO_CHAR(TANGGAL_AWAL, 'YYYY') = '".$reqTahun."' OR TANGGAL_FIX IS NOT NULL";
			
		}
		else
			$statement = " AND TO_CHAR(TANGGAL_AWAL, 'YYYY') = '".$reqTahun."' OR TANGGAL_FIX IS NOT NULL";
		
		$searchJson= "  AND (UPPER(NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%')";
		$allRecord = $set->getCountByParams(array(), $statement);
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParams(array(),  $searchJson.$statement);
		
		$set->selectByParams(array(),  $dsplyRange, $dsplyStart, $searchJson.$statement, $sOrder);
		//echo $set->query;exit;
		//echo "IKI ".$_GET['iDisplayStart'];

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
				if($aColumns[$i] == "TANGGAL_FIX")
					$row[] = getDayMonth($set->getField(trim($aColumns[$i])));
				else			
					$row[] = $set->getField(trim($aColumns[$i]));
			}

			$output['aaData'][] = $row;
			$duk++;
		}

		echo json_encode( $output );
	}

	function harilibur_add()
	{
		$this->load->model('main/HariLibur');

		$set = new HariLibur();

		$reqId = $this->input->post("reqId");
		$reqMode = $this->input->post("reqMode");
		$reqPilih= $this->input->post("reqPilih");
		$reqNama= $this->input->post("reqNama");
		$reqKeterangan= $this->input->post("reqKeterangan");
		$reqTanggalAwal= $this->input->post("reqTanggalAwal");
		$reqTanggalAkhir= $this->input->post("reqTanggalAkhir");
		$reqHari= $this->input->post("reqHari");
		$reqBulan= $this->input->post("reqBulan");
		$reqTanggalFix=get_null_10($reqHari).get_null_10($reqBulan);
		$reqStatusCutiBersama = $this->input->post("reqStatusCutiBersama");
		$reqCabangId = $this->input->post("reqCabangId");

		if($reqMode == "insert")
		{
			$set->setField('NAMA', $reqNama);
			$set->setField('KETERANGAN', $reqKeterangan);
			$set->setField('STATUS_CUTI_BERSAMA', $reqStatusCutiBersama);
			$set->setField('CABANG_ID', $reqCabangId);

			if($reqPilih == "Statis")
			{
				$set->setField('TANGGAL_AWAL', "NULL");
				$set->setField('TANGGAL_AKHIR', "NULL");
			}
			elseif ($reqPilih == "Dinamis" && $reqTanggalAkhir == "")
			{
				$set->setField('TANGGAL_AWAL', dateToDBCheck($reqTanggalAwal));
				$set->setField('TANGGAL_AKHIR', dateToDBCheck($reqTanggalAwal));
			}
			else
			{
				$set->setField('TANGGAL_AWAL', dateToDBCheck($reqTanggalAwal));
				$set->setField('TANGGAL_AKHIR', dateToDBCheck($reqTanggalAkhir));
			}

			$set->setField('TANGGAL_FIX', ValToNullDB($reqTanggalFix));
			if($set->insert()){
				$reqId= $set->id;
				echo $reqId."-Data berhasil disimpan.";
			} else {
				echo "xxx-Data gagal disimpan.";
			}
			//echo $set->query;
		}
		else
		{
			$set->setField('HARI_LIBUR_ID', $reqId);
			$set->setField('NAMA', $reqNama);
			$set->setField('KETERANGAN', $reqKeterangan);
			$set->setField('TANGGAL_AWAL', dateToDBCheck($reqTanggalAwal));
			$set->setField('TANGGAL_AKHIR', dateToDBCheck($reqTanggalAkhir));
			$set->setField('STATUS_CUTI_BERSAMA', $reqStatusCutiBersama);
			$set->setField('CABANG_ID', $reqCabangId);

			$set->setField('TANGGAL_FIX', ValToNullDB($reqTanggalFix));
			if($set->update()){
				echo $reqId."-Data berhasil disimpan.";
			} else {
				echo "xxx-Data gagal disimpan.";
			}
		}

	}

	function simpanulang()
	{
		$this->load->model('main/Klarifikasi');

		$reqId= $this->input->get("reqId");

		$set= new Klarifikasi();
		$set->setField("KLARIFIKASI_ID", $reqId);
		$set->setField("LAST_USER", $this->LOGIN_USER);
        $set->setField("LAST_UPDATE", "CURRENT_TIMESTAMP");
        if($set->updatesimpanulang())
        {
			echo $reqId."-Data berhasil disimpan.";
		}
		else 
		{
			echo "xxx-Data gagal disimpan.";
		}
	}

}