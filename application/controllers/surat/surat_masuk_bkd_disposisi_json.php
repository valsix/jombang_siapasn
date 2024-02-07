<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class surat_masuk_bkd_disposisi_json extends CI_Controller {

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
	
	function surat_masuk_add()
	{
		$this->load->model('persuratan/SuratMasukBkd');
		$this->load->library("FileHandler"); 

		$file = new FileHandler();
		
		$reqId = $this->input->post("reqId");
		$reqIsManual= $this->input->post("reqIsManual");
		$reqSatuanKerjaAsal= $this->input->post("reqSatuanKerjaAsal");
		$reqMode = $this->input->post("reqMode");
		$reqSatuanKerjaTujuanId= $this->input->post("reqSatuanKerjaTujuanId");
		$reqKepada= $this->input->post("reqKepada");
		$reqSatuanKerjaAsalId= $this->input->post("reqSatuanKerjaAsalId");
		$reqTanggal= $this->input->post("reqTanggal");
		$reqNomor= $this->input->post("reqNomor");
		$reqNomorAgenda= $this->input->post("reqNomorAgenda");
		$reqPerihal= $this->input->post("reqPerihal");
		$reqTanggalTerima= $this->input->post("reqTanggalTerima");


		$reqLinkFile = $_FILES['reqLinkFile'];
		$reqLinkFileTemp = $_POST["reqLinkFileTemp"];
		/*reqSatuanKerjaTujuanId
		reqKepada
		reqSatuanKerjaAsalId
		reqTanggal
		reqNomor
		reqPerihal
		reqTanggalTerima*/
		// echo $reqLinkFile;exit;

		// Allow certain file formats
		$bolehupload = "";
		$fileuploadexe= strtolower(getExtension($reqLinkFile['name']));

		if($fileuploadexe == "")
		{
			$bolehupload = 1;
		}
		else
		{
			if($fileuploadexe == "pdf")
				$bolehupload = 1;
		}

		if($bolehupload == "")
		{
			echo "xxx-Data gagal disimpan, check file upload harus format pdf.";
		}
		else
		{
			$target_dir= "uploadsurat/masuk/";
			if(file_exists($target_dir)){}
			else
			{
				makedirs($target_dir);
			}
			
			if($reqMode == "insert")
			{
				$set= new SuratMasukBkd();
				$set->setField("JENIS_ID", ValToNullDB($req));
				$set->setField('IS_MANUAL', ValToNullDB($reqIsManual));
				$set->setField('SATUAN_KERJA_ASAL_NAMA', ValToNullDB($reqSatuanKerjaAsal));
				
				$set->setField("NOMOR", $reqNomor);
				$set->setField("NO_AGENDA", $reqNomorAgenda);
				$set->setField("TANGGAL", dateToDBCheck($reqTanggal));
				$set->setField("TANGGAL_TERIMA", dateToDBCheck($reqTanggalTerima));

				$set->setField("KEPADA", $reqKepada);
				$set->setField("PERIHAL", $reqPerihal);
				$set->setField("SATUAN_KERJA_TUJUAN_ID", $reqSatuanKerjaTujuanId);
				$set->setField("SATUAN_KERJA_ASAL_ID", ValToNullDB($reqSatuanKerjaAsalId));

				if($set->insertSurat())
				{
					$reqId= $set->id;
					echo $reqId."-Data berhasil disimpan.";

					$renameFile = $reqId.".".getExtension($reqLinkFile['name']);
					$target_file= $target_dir.$renameFile;
					if(file_exists($target_file)){}
					else
					{
						$file->uploadToDir('reqLinkFile', $target_dir, $renameFile);
					}
					
					
				}
				else 
				{
					echo "xxx-Data gagal disimpan.";
				}
				// echo $set->query;exit;
			}
		}
	}
	
	function nomor_agenda_terima()
	{
		$this->load->model('persuratan/SuratMasukBkdDisposisi');
		$this->load->model('persuratan/SuratMasukPegawai');
		
		$set = new SuratMasukBkdDisposisi();

		$reqId = $this->input->post("reqId");
		$reqRowId= $this->input->post("reqRowId");
		$reqMode = $this->input->post("reqMode");
		$reqModeUbah= $this->input->post("reqModeUbah");
		
		$reqNomorAgenda= $this->input->post("reqNomorAgenda");
		$reqTanggalTerima= $this->input->post("reqTanggalTerima");
		$reqSuratAwal= $this->input->post("reqSuratAwal");
		$reqPosisiTeknis= $this->input->post("reqPosisiTeknis");
		
		$set->setField('NO_AGENDA', $reqNomorAgenda);
		$set->setField('TERBACA', "1");
		$set->setField('SURAT_AWAL', ValToNullDB($reqSuratAwal));
		$set->setField('TANGGAL', dateToDBCheck($reqTanggalTerima));
		$set->setField('SURAT_MASUK_BKD_ID', $reqId);
		
		if($reqMode == "insert"){}
		else
		{
			$set->setField('SURAT_MASUK_BKD_DISPOSISI_ID', $reqRowId);
			$set->setField('POSISI_TEKNIS', ValToNullDB($reqPosisiTeknis));
			
			if($set->updateAgendaBaca())
			{
				// reset status posisi surat
				$set->updateStatusPosisiSurat();
				
				$set_detil= new SuratMasukPegawai();
				$set_detil->setField("SURAT_MASUK_BKD_DISPOSISI_ID", $reqRowId);
				$set_detil->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
				$set_detil->setField("LAST_USER", $this->LOGIN_USER);
				$set_detil->setField("LAST_DATE", "NOW()");
				
				if($reqModeUbah == "1")
				$set_detil->updateStatusBerkasUbahSebelumTeknis();
				else
				$set_detil->updateStatusBerkasSebelumTeknis();

				// untuk bypass
				$statementbypass= "
				AND A.JENIS_PELAYANAN_ID IN (SELECT JENIS_ID FROM persuratan.surat_masuk_bkd WHERE SURAT_MASUK_BKD_ID = ".$reqId.")
				AND NOT EXISTS
				(
					SELECT 1
					FROM
					(
						SELECT SATUAN_KERJA_ASAL_ID FROM persuratan.surat_masuk_bkd_disposisi WHERE SURAT_MASUK_BKD_ID = ".$reqId."
					) X WHERE X.SATUAN_KERJA_ASAL_ID = A.SATUAN_KERJA_ID
				)";
				$setdetil= new SuratMasukBkdDisposisi();
				$setdetil->selectjenispelayanantujuan(array(), -1,-1, $statementbypass);
				$setdetil->firstRow();
				$infosatuankerjaid= $setdetil->getField("SATUAN_KERJA_ID");

				if(!empty($infosatuankerjaid))
				{
					$tahunIni= date("Y");
					$statementbypass= " 
					AND EXISTS
					(
						SELECT 1
						FROM
						(
							SELECT A.NO_AGENDA, A.TANGGAL
							FROM PERSURATAN.SURAT_MASUK_BKD A
							INNER JOIN PERSURATAN.SURAT_MASUK_BKD_DISPOSISI B ON A.SURAT_MASUK_BKD_ID = B.SURAT_MASUK_BKD_ID
							WHERE SURAT_AWAL = 1 
							AND '".$tahunIni."' = TO_CHAR(B.TANGGAL, 'YYYY')
						) X
						WHERE A.NO_AGENDA = X.NO_AGENDA
						AND A.TANGGAL = X.TANGGAL
					)
					AND TO_CHAR(TANGGAL, 'YYYY') = '".$tahunIni."'
					";

					$setdetil= new SuratMasukBkdDisposisi();
					$setdetil->selectByParamsNoAgenda($statementbypass);
					// echo $setdetil->query;exit();
					$setdetil->firstRow();
					$infonoagenda= $setdetil->getField("NO_AGENDA_BARU");

					$setdetil= new SuratMasukBkdDisposisi();
					$setdetil->setField('SURAT_MASUK_BKD_ID', $reqId);
					$setdetil->setField("SURAT_MASUK_BKD_DISPOSISI_ID", $reqRowId);
					$setdetil->setField("SATUAN_KERJA_TUJUAN_ID", $infosatuankerjaid);
					$setdetil->setField("NO_AGENDA", $infonoagenda);
					$setdetil->updatebypass();
				}

				echo $reqId."-Data berhasil disimpan.";
			}
			else
				echo "xxx-Data gagal disimpan.";
		}
		
	}
	
	function nomor_agenda_disposisi()
	{
		$this->load->model('persuratan/SuratMasukBkdDisposisi');
		$this->load->model('persuratan/SuratMasukPegawai');
		$this->load->library("FileHandler"); 

		$file = new FileHandler();
		$set = new SuratMasukBkdDisposisi();

		$reqId = $this->input->post("reqId");
		$reqRowId= $this->input->post("reqRowId");
		$reqRowId = $this->input->post("reqRowId");
		$reqMode = $this->input->post("reqMode");
		$reqModeUbah= $this->input->post("reqModeUbah");
		$reqLinkFile = $_FILES['reqLinkFile'];
		
		$reqSatuanKerjaDiteruskanKepadaId= $this->input->post("reqSatuanKerjaDiteruskanKepadaId");
		$reqTanggalDisposisi= $this->input->post("reqTanggalDisposisi");
		$reqIsi= $this->input->post("reqIsi");

		$bolehupload = "";
		$fileuploadexe= strtolower(getExtension($reqLinkFile['name']));

		if($fileuploadexe == "")
		{
			$bolehupload = 1;
		}
		else
		{
			if($fileuploadexe == "pdf")
				$bolehupload = 1;
		}

		if($bolehupload == "")
		{
			echo "xxx-Data gagal disimpan, check file upload harus format pdf.";
		}
		else
		{
			$set->setField('ISI', $reqIsi);
			$set->setField('TANGGAL_DISPOSISI', dateTimeToDBCheck($reqTanggalDisposisi));
			$set->setField('SATUAN_KERJA_TUJUAN_ID', $reqSatuanKerjaDiteruskanKepadaId);
			$set->setField('SURAT_MASUK_BKD_ID', $reqId);
			if($reqMode == "insert"){}
			else
			{
				$set->setField('SURAT_MASUK_BKD_DISPOSISI_ID', $reqRowId); 
				if($set->updateDisposisi())
				{
					$target_dir= "uploadsurat/masuk/";
					if(file_exists($target_dir)){}
					else
					{
						makedirs($target_dir);
					}

					$renameFile = $reqId.".".getExtension($reqLinkFile['name']);
					$target_file= $target_dir.$renameFile;
					// // if(file_exists($target_file)){}
					if(getExtension($reqLinkFile['name']) == ""){}
					else
					{
						$file->uploadToDir('reqLinkFile', $target_dir, $renameFile);
					}

					$set_detil= new SuratMasukPegawai();
					$set_detil->setField("SURAT_MASUK_BKD_DISPOSISI_ID", $reqRowId);
					$set_detil->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
					$set_detil->setField("LAST_USER", $this->LOGIN_USER);
					$set_detil->setField("LAST_DATE", "NOW()");
					
					if($reqModeUbah == "1")
					$set_detil->updateStatusBerkasUbahSebelumTeknis();
					else
					$set_detil->updateStatusBerkasSebelumTeknis();
					
					echo $reqId."-Data berhasil disimpan.";
				}
				else
					echo "xxx-Data gagal disimpan.";
			}
		}
		
	}
	
	function catatan()
	{
		$this->load->model('persuratan/SuratMasukBkdDisposisiKeterangan');
		
		$set = new SuratMasukBkdDisposisiKeterangan();
		
		$reqId= $this->input->post("reqId");
		$reqRowId= $this->input->post("reqRowId");
		$reqJenis= $this->input->post("reqJenis");
		$reqCatatan= $this->input->post("reqCatatan");
		$reqPegawaiId= $this->input->post("reqPegawaiId");
		$reqRowDetilId= $this->input->post("reqRowDetilId");
		
		$set->setField('SURAT_MASUK_BKD_DISPOSISI_ID', $reqRowId);
		$set->setField('SURAT_MASUK_BKD_ID', $reqId);
		$set->setField('JENIS_ID', ValToNullDB($reqJenis));
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('ISI', $reqCatatan);
		$set->setField('SURAT_MASUK_BKD_DISPOSISI_KETERANGAN_ID', $reqRowDetilId);
		if($reqRowDetilId == "")
		{
			if($set->insert())
			{
				$reqId= $set->id;
				echo $reqId."-Data berhasil disimpan.";
			} else
				echo "xxx-Data gagal disimpan.";
		}
		else
		{
			if($set->update())
			{
				echo $reqId."-Data berhasil disimpan.";
			} else
				echo "xxx-Data gagal disimpan.";
		}
	}
	
	function add()
	{
		$this->load->model('persuratan/SuratMasukBkdDisposisi');
		
		$set = new SuratMasukBkdDisposisi();

		$reqId = $this->input->post("reqId");
		$reqRowId= $this->input->post("reqRowId");
		$reqRowId = $this->input->post("reqRowId");
		$reqMode = $this->input->post("reqMode");
		
		$reqNomorAgenda= $this->input->post("reqNomorAgenda");
		$reqSatuanKerjaDiteruskanKepadaId= $this->input->post("reqSatuanKerjaDiteruskanKepadaId");
		$reqTanggalDisposisi= $this->input->post("reqTanggalDisposisi");
		$reqIsi= $this->input->post("reqIsi");
		
		$set->setField('NO_AGENDA', $reqNomorAgenda);
		$set->setField('ISI', $reqIsi);
		$set->setField('TERBACA', "1");
		$set->setField('TANGGAL', dateTimeToDBCheck($reqTanggalDisposisi));
		$set->setField('SATUAN_KERJA_TUJUAN_ID', $reqSatuanKerjaDiteruskanKepadaId);
		$set->setField('SURAT_MASUK_BKD_ID', $reqId);
		if($reqMode == "insert"){}
		else
		{
			$set->setField('SURAT_MASUK_BKD_DISPOSISI_ID', $reqRowId); 
			if($set->update())
			{
				echo $reqId."-Data berhasil disimpan.";
			}
			else
				echo "xxx-Data gagal disimpan.";
		}
		
	}
	
	function statusbelumbaca()
	{
		$this->load->model('persuratan/SuratMasukBkdDisposisi');
		$set = new SuratMasukBkdDisposisi();
		
		$reqId=  $this->input->get('reqId');
		$reqSatuanKerjaId=  $this->input->get('reqSatuanKerjaId');

		$statement= " AND A.SURAT_MASUK_BKD_ID NOT IN (".$reqId.")";
		$set->selectByParamsSuratBerikutnya(array(), -1,-1, $reqSatuanKerjaId, $statement);
		// echo $set->query;exit();
		$set->firstRow();
		$iddata= $set->getField("SURAT_MASUK_BKD_ID");
		$jenis= $set->getField("JENIS_ID");

		if($jenis == null)
			$jenis= "";

		$arrJson["iddata"] = $iddata;
		$arrJson["jenis"] = $jenis;
		echo json_encode($arrJson);
	}

	function statuskirim()
	{
		$this->load->model('persuratan/SuratMasukBkdDisposisi');
		$set = new SuratMasukBkdDisposisi();
		
		$reqRowId=  $this->input->get('reqRowId');
		$set->setField("SURAT_MASUK_BKD_DISPOSISI_ID", $reqRowId);
		$set->setField("TERDISPOSISI", ValToNullDB("1"));
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("LAST_DATE", "NOW()");
		
		if($set->updateKirim())
			$arrJson["PESAN"] = "Data berhasil di kirim.";
		else
			$arrJson["PESAN"] = "Data gagal di kirim.";		
		
		echo json_encode($arrJson);
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
		
}
?>