<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class suami_istri_json extends CI_Controller {

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

		$this->load->model('SuamiIstri');

		$set = new SuamiIstri();
		
		$reqId = $this->input->post("reqId");
		$reqRowId= $this->input->post("reqRowId");
		$reqMode = $this->input->post("reqMode");

		$reqPendidikanId= $this->input->post("reqPendidikanId");
		$reqNama= $this->input->post("reqNama");
		$reqTempatLahir= $this->input->post("reqTempatLahir");
		$reqTanggalLahir= $this->input->post("reqTanggalLahir");
		$reqTanggalKawin= $this->input->post("reqTanggalKawin");
		$reqKartu= $this->input->post("reqKartu");
		$reqStatusPns= $this->input->post("reqStatusPns");
		$reqNipPns= $this->input->post("reqNipPns");
		$reqPekerjaan= $this->input->post("reqPekerjaan");
		$reqStatusTunjangan= $this->input->post("reqStatusTunjangan");
		$reqStatusBayar= $this->input->post("reqStatusBayar");
		$reqBulanBayar= $this->input->post("reqBulanBayar");
		$reqStatusSi= $this->input->post("reqStatusSi");
		
		$reqSuratKawin= $this->input->post("reqSuratKawin");
		$reqNik= $this->input->post("reqNik");
		$reqCeraiSurat= $this->input->post("reqCeraiSurat");
		$reqCeraiTanggal= $this->input->post("reqCeraiTanggal");
		$reqCeraiTmt= $this->input->post("reqCeraiTmt");
		$reqKematianSurat= $this->input->post("reqKematianSurat");
		$reqKematianTanggal= $this->input->post("reqKematianTanggal");
		$reqKematianTmt= $this->input->post("reqKematianTmt");

		$reqStatusHidup= $this->input->post('reqStatusHidup');
		$reqStatusBekerja= $this->input->post('reqStatusBekerja');
		$reqGelarDepan= $this->input->post('reqGelarDepan');
		$reqGelarBelakang= $this->input->post('reqGelarBelakang');
		$reqAktaKelahiran= $this->input->post('reqAktaKelahiran');
		$reqJenisIdDokumen= $this->input->post('reqJenisIdDokumen');
		$reqNoInduk= $this->input->post('reqNoInduk');
		$reqAgamaId= $this->input->post('reqAgamaId');
		$reqEmail= $this->input->post('reqEmail');
		$reqHp= $this->input->post('reqHp');
		$reqTelepon= $this->input->post('reqTelepon');
		$reqAlamat= $this->input->post('reqAlamat');
		$reqBpjsNo= $this->input->post('reqBpjsNo');
		$reqBpjsTanggal= $this->input->post('reqBpjsTanggal');
		$reqNpwpNo= $this->input->post('reqNpwpNo');
		$reqNpwpTanggal= $this->input->post('reqNpwpTanggal');
		$reqStatusPns= $this->input->post('reqStatusPns');
		$reqNipPns= $this->input->post('reqNipPns');
		$reqStatusLulus= $this->input->post('reqStatusLulus');
		$reqKematianNo= $this->input->post('reqKematianNo');
		$reqKematianTanggal= $this->input->post('reqKematianTanggal');
		$reqJenisKawinId= $this->input->post('reqJenisKawinId');
		$reqAktaNikahNo= $this->input->post('reqAktaNikahNo');
		$reqAktaNikahTanggal= $this->input->post('reqAktaNikahTanggal');
		$reqNikahTanggal= $this->input->post('reqNikahTanggal');
		$reqAktaCeraiNo= $this->input->post('reqAktaCeraiNo');
		$reqAktaCeraiTanggal= $this->input->post('reqAktaCeraiTanggal');
		$reqCeraiTanggal= $this->input->post('reqCeraiTanggal');
		$reqTanggalMeninggal= $this->input->post('reqTanggalMeninggal');

		$reqAgamaId= $this->input->post('reqAgamaId');
		$reqJenisKelamin= $this->input->post('reqJenisKelamin');

		$set->setField('STATUS_AKTIF', $reqStatusHidup);
		$set->setField('GELAR_DEPAN', $reqGelarDepan);
		$set->setField('GELAR_BELAKANG', $reqGelarBelakang);
		$set->setField('AKTA_KELAHIRAN', $reqAktaKelahiran);
		$set->setField('JENIS_ID_DOKUMEN', ValToNullDB($reqJenisIdDokumen));
		$set->setField('AGAMA_ID', ValToNullDB($reqAgamaId));
		$set->setField('JENIS_KELAMIN', $reqJenisKelamin);
		$set->setField('EMAIL', $reqEmail);
		$set->setField('HP', $reqHp);
		$set->setField('TELEPON', $reqTelepon);
		$set->setField('ALAMAT', $reqAlamat);
		$set->setField('BPJS_NO', $reqBpjsNo);
		$set->setField('BPJS_TANGGAL', dateToDBCheck($reqBpjsTanggal));
		$set->setField('NPWP_NO', $reqNpwpNo);
		$set->setField('NPWP_TANGGAL', dateToDBCheck($reqNpwpTanggal));
		$set->setField('STATUS_PNS', ValToNullDB($reqStatusPns));
		$set->setField('NIP_PNS', $reqNipPns);
		$set->setField('STATUS_LULUS', ValToNullDB($reqStatusLulus));
		$set->setField('KEMATIAN_NO', $reqKematianNo);
		$set->setField('KEMATIAN_TANGGAL', dateToDBCheck($reqKematianTanggal));
		$set->setField('JENIS_KAWIN_ID', ValToNullDB($reqJenisKawinId));
		$set->setField('AKTA_NIKAH_NO', $reqAktaNikahNo);
		$set->setField('AKTA_NIKAH_TANGGAL', dateToDBCheck($reqAktaNikahTanggal));
		$set->setField('NIKAH_TANGGAL', dateToDBCheck($reqNikahTanggal));
		$set->setField('AKTA_CERAI_NO', $reqAktaCeraiNo);
		$set->setField('AKTA_CERAI_TANGGAL', dateToDBCheck($reqAktaCeraiTanggal));
		$set->setField('CERAI_TANGGAL', dateToDBCheck($reqCeraiTanggal));
		$set->setField('TANGGAL_MENINGGAL', dateToDBCheck($reqTanggalMeninggal));
		$set->setField('STATUS_BEKERJA', ValToNullDB($reqStatusBekerja));
		
		$set->setField('PEGAWAI_ID', ValToNullDB($reqId));
		$set->setField('PENDIDIKAN_ID', ValToNullDB($reqPendidikanId));
		$set->setField('NAMA', $reqNama);
		$set->setField('TEMPAT_LAHIR', $reqTempatLahir);
		$set->setField('TANGGAL_LAHIR', dateToDBCheck($reqTanggalLahir));
		$set->setField('TANGGAL_KAWIN', dateToDBCheck($reqTanggalKawin));
		$set->setField('KARTU', $reqKartu);
		$set->setField('STATUS_PNS', ValToNullDB($reqStatusPns));
		$set->setField('NIP_PNS', $reqNipPns);
		$set->setField('PEKERJAAN', $reqPekerjaan);
		$set->setField('STATUS_TUNJANGAN', ValToNullDB($reqStatusTunjangan));
		$set->setField('STATUS_BAYAR', ValToNullDB($reqStatusBayar));
		$set->setField('BULAN_BAYAR', dateToDBCheck($reqBulanBayar));
		$set->setField('STATUS_S_I', $reqStatusSi);
		
		$set->setField('SURAT_NIKAH', $reqSuratKawin);
		// $set->setField('NIK', $reqNik);
		$set->setField('NIK', $reqNoInduk);
		$set->setField('CERAI_SURAT', $reqCeraiSurat);
		$set->setField('CERAI_TANGGAL', dateToDBCheck($reqCeraiTanggal));
		$set->setField('CERAI_TMT', dateToDBCheck($reqCeraiTmt));
		$set->setField('KEMATIAN_SURAT', $reqKematianSurat);
		$set->setField('KEMATIAN_TANGGAL', dateToDBCheck($reqKematianTanggal));
		$set->setField('KEMATIAN_TMT', dateToDBCheck($reqKematianTmt));

		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");

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
			$set->setField('SUAMI_ISTRI_ID',$reqRowId);
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
	
	function combo() 
	{
		$this->load->model("SuamiIstri");
		
		$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
		$reqId= $this->input->get("reqId");

		$set = new SuamiIstri();
		
		$statement= " AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')";
		$statement.= " AND UPPER(A.NAMA) LIKE '%".strtoupper($search_term)."%' ";

		if($reqId == ""){}
		else
		{
			$statement.= " AND A.PEGAWAI_ID = ".$reqId;
		}
		

		$set->selectByParams(array(), 5,0, $statement);
		//echo $set->query;exit;
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("SUAMI_ISTRI_ID");
			$arr_json[$i]['label'] = $set->getField("NAMA");
			$arr_json[$i]['desc'] = $set->getField("NAMA");
			$i++;
		}
		echo json_encode($arr_json);		
		
	}
	
	function delete()
	{
		$this->load->model('SuamiIstri');
		$set = new SuamiIstri();
		
		$reqId =  $this->input->get('reqId');
		$reqMode =  $this->input->get('reqMode');
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		$set->setField("SUAMI_ISTRI_ID", $reqId);
		
		if($reqMode == "suami_istri_0")
		{
			$set->setField("STATUS", "1");
			if($set->updateStatus())
				echo "Data berhasil dihapus.";
			else
				echo "Data gagal dihapus.";	
		}
		elseif($reqMode == "suami_istri_1")
		{
			$set->setField("STATUS", "2");
			if($set->updateStatus())
				echo "Data berhasil diubah.";
			else
				echo "Data gagal diubah.";	
		}	
	}

	function log($riwayatId) 
	{	
		$this->load->model('SuamiIstriLog');

		$set = new SuamiIstriLog();
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		
		$aColumns = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "SUAMI_ISTRI_ID");
		$aColumnsAlias = array("INFO_LOG", "LAST_USER", "LAST_DATE", "STATUS_NAMA", "SUAMI_ISTRI_ID");
		

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
		
		$arrayWhere = array("SUAMI_ISTRI_ID" => $riwayatId);
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

	function checkData()
	{
		$this->load->model('SuamiIstri');

		$reqId= $this->input->get("reqId");
		$reqRowId= $this->input->get("reqRowId");
		$reqTanggalKawin= $this->input->get("reqTanggalKawin");
		$reqStatusHidup= $this->input->get("reqStatusHidup");
		$reqStatusSi= $this->input->get("reqStatusSi");

		$statement= " AND A.PEGAWAI_ID = ".$reqId;
		$set = new SuamiIstri();
		$set->selectByParamsTanggalKawinAkhir($statement);
		// echo $set->query;exit;
		$set->firstRow();
		$tempRowId= $set->getField("SUAMI_ISTRI_ID");
		$tempStatusHidup= $set->getField('STATUS_AKTIF');
		$tempTanggalKawin= dateToPageCheck($set->getField("TANGGAL_KAWIN"));

		$tempTanggalKawin= strtotime($tempTanggalKawin);
		$tempEntriTanggalKawin= strtotime($reqTanggalKawin);

		$tempStatusSimpan= "";
		if($tempEntriTanggalKawin <= $tempTanggalKawin && $reqStatusSi == "1")
		$tempStatusSimpan= "1";

		if($tempStatusSimpan == "1" && $tempRowId == $reqRowId)
		$tempStatusSimpan= "";

		$statement= " AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2') AND A.STATUS_S_I = '1' AND A.PEGAWAI_ID = ".$reqId;
		$set = new SuamiIstri();
		$set->selectByParams(array(), -1,-1, $statement);
		$set->firstRow();
		// echo $set->query;exit;
		$tempAktifRowId= $set->getField("SUAMI_ISTRI_ID");

		/*
		rule nya tambah suami istri :
		- Jika riwayat terakhir (dr tanggal nikah desc) dengan pasangan status hidup adalah Hidup DAN Status Pernikahan adalah Menikah, maka boleh menambahkan riwayat jika yang di input status hidup adalah wafat ATAU Status menikah Cerai ATAU Janda/duda
		- Jika riwayat terakhir (dr tanggal nikah desc) dengan pasangan status hidup adalah HIDUP DAN Status Pernikahan adalah Cerai maka boleh menambahkan pasangan
		- Jika riwayat terakhir (dr tanggal nikah desc) dengan pasangan status hidup adalah WAFAT DAN Status Pernikahan adalah apapun maka boleh menambahkan pasangan
		*/

		$tempStatusNikah= "";
		if($reqRowId == "")
		{
			if($reqStatusSi == "1")
			{
				// echo "a";exit;
				if($tempAktifRowId == ""){}
				else
				{
					$tempStatusNikah= "1";
				}
			}
		}
		else
		{
			// echo "b";
			if($reqStatusSi == "1")
			{
				if($tempAktifRowId == $reqRowId || $tempAktifRowId == ""){}
				else
				$tempStatusNikah= "1";
			}
		}

		echo $tempStatusSimpan."-".$tempStatusNikah;

	}

}
?>