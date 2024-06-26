<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class diklat_struktural_json extends CI_Controller {

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
		$this->load->model('validasi/DiklatStruktural');
		
		$set = new DiklatStruktural();

		$reqTempValidasiHapusId= $this->input->post("reqTempValidasiHapusId");
		$reqTempValidasiId= $this->input->post("reqTempValidasiId");
		$reqStatusValidasi= $this->input->post("reqStatusValidasi");
		$reqId= $this->input->post("reqId");
		$reqMode= $this->input->post("reqMode");
		$reqRowId= $this->input->post('reqRowId');
		$reqValRowId= $this->input->post('reqValRowId');
		$cekquery= $this->input->post('cekquery');

		/*if(empty($reqStatusValidasi))
		{
			echo "xxx-Isikan terlebih dahulu Status Klarifikasi.";
			exit;
		}
		else*/
		if($reqStatusValidasi == "2")
		{
			$reqsimpan= "";
			$vtempvalidasiid= "";
			if(empty($reqTempValidasiId) && !empty($reqTempValidasiHapusId))
			{
				$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiHapusId);
				if($set->deletehapusdata())
				{
					$vtempvalidasiid= $reqTempValidasiHapusId;
					$reqsimpan= "1";
				}
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

				if($set->updatevalidasi())
				{
					$vtempvalidasiid= $reqTempValidasiId;
					$reqsimpan= "1";
				}
			}

			if($reqsimpan == "1")
			{
				// tambahan untuk reset e file
				$setfile= new DiklatStruktural();
				$setfile->setField('TEMP_VALIDASI_ID', $vtempvalidasiid);
				$setfile->resetpegawaifile();

				echo $reqRowId."-Data berhasil disimpan.";
			}
			else
				echo "xxx-Data gagal disimpan.";

		}
		else
		{

			$reqDiklat= $this->input->post('reqDiklat');
			$reqTahun= $this->input->post('reqTahun');
			$reqNoSttpp= $this->input->post('reqNoSttpp');
			$reqPenyelenggara= $this->input->post('reqPenyelenggara');
			$reqAngkatan= $this->input->post('reqAngkatan');
			$reqTglMulai= $this->input->post('reqTglMulai');
			$reqTglSelesai= $this->input->post('reqTglSelesai');
			$reqTglSttpp= $this->input->post('reqTglSttpp');
			$reqTempat= $this->input->post('reqTempat');
			$reqJumlahJam= $this->input->post('reqJumlahJam');

			$reqJabatanRiwayatId= $this->input->post('reqJabatanRiwayatId');
			$reqRumpunJabatan= $this->input->post('reqRumpunJabatan');
			$reqNilaiKompentensi= $this->input->post('reqNilaiKompentensi');

			$set->setField('DIKLAT_ID', ValToNullDB($reqDiklat));	
			$set->setField('TEMPAT', $reqTempat);
			$set->setField('PENYELENGGARA', $reqPenyelenggara);
			$set->setField('ANGKATAN', $reqAngkatan);
			$set->setField('TAHUN', getDay($reqTglSttpp));
			$set->setField('TANGGAL_MULAI', dateToDBCheck($reqTglMulai));
			$set->setField('TANGGAL_SELESAI', dateToDBCheck($reqTglSelesai));
			$set->setField('NO_STTPP', $reqNoSttpp);
			$set->setField('TANGGAL_STTPP', dateToDBCheck($reqTglSttpp));
			$set->setField("JUMLAH_JAM", ValToNullDB(dotToNo($reqJumlahJam)));

			$set->setField("DS_JABATAN_RIWAYAT_ID", ValToNullDB($reqJabatanRiwayatId));
			$set->setField("NILAI_REKAM_JEJAK", ValToNullDB($reqNilaiKompentensi));
			$set->setField("RUMPUN_ID", ValToNullDB($reqRumpunJabatan));

			$set->setField('PEGAWAI_ID', $reqId);
			$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
			$set->setField("LAST_USER", $this->LOGIN_USER);
			$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
			$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			$set->setField("LAST_DATE", "NOW()");
			// $set->setField('DIKLAT_STRUKTURAL_ID', $reqRowId);
			$set->setField('VALIDASI', ValToNullDB($reqStatusValidasi));
			$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);
			
			$reqsimpan= "";
			// untuk simpan data tanpa validasi
			if(!empty($reqTempValidasiId) && empty($reqStatusValidasi))
			{
				if($set->update())
				{
					$reqsimpan= "1";
				}
			}
			elseif(!empty($reqTempValidasiId))
			{
				if($set->update())
				{
					if($set->updatetanggalvalidasi())
					{
						$reqsimpan= "1";

						// kalau kosong berarti data baru
						if(empty($reqValRowId))
						{
							$setlast= new DiklatStruktural();
							$reqValRowId= $setlast->getlastid($reqId);
						}

						// tambahan untuk update e file
						$setfile= new DiklatStruktural();
						$setfile->setField('RIWAYAT_ID', $reqValRowId);
						$setfile->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);
						$setfile->riwayatvalidasipegawaifile();

					}
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

			if(!empty($cekquery))
			{
				echo $set->query;exit;
			}

			if($reqsimpan == "1")
			{
				echo $reqValRowId."-Data berhasil disimpan.";
			}
			else
				echo "xxx-Data gagal disimpan.";

			// echo $set->query;exit;
		}
			
	}
	
	function delete()
	{
		$this->load->model('DiklatStruktural');
		$set = new DiklatStruktural();
		
		$reqId =  $this->input->get('reqId');
		$reqMode =  $this->input->get('reqMode');
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		$set->setField("DIKLAT_STRUKTURAL_ID", $reqId);
		
		if($reqMode == "diklat_struktural_0")
		{
			$set->setField("STATUS", "1");
			if($set->updateStatus())
				echo "Data berhasil dihapus.";
			else
				echo "Data gagal dihapus.";	
		}
		elseif($reqMode == "diklat_struktural_1")
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
		$this->load->model('DiklatStrukturalLog');

		$set = new DiklatStrukturalLog();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		
		$aColumns = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "DIKLAT_STRUKTURAL_ID");
		$aColumnsAlias = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "DIKLAT_STRUKTURAL_ID");
		

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
		
		$arrayWhere = array("DIKLAT_STRUKTURAL_ID" => $riwayatId);
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

	function combokenaikanpangkat()
	{
		$this->load->model("DiklatStruktural");
		
		$reqId= $this->input->get("reqId");
		$reqDiklatId= $this->input->get("reqDiklatId");
		
		$set = new DiklatStruktural();
		// $statement= " AND A.DIKLAT_ID = ".$reqDiklatId." AND A.PEGAWAI_ID = ".$reqId;
		$statement= " AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2') AND A.PEGAWAI_ID = ".$reqId;
		$set->selectByParams(array(), -1,-1, $statement);
		// echo $set->query;exit();
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("DIKLAT_STRUKTURAL_ID");
			$arr_json[$i]['text'] = $set->getField("NO_STTPP");	
			$i++;
		}
		echo json_encode($arr_json);
	}

}
?>