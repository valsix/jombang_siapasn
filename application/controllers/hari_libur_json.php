<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class hari_libur_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// trow to unauthenticated page!
			//redirect('Login');
		}		
		$this->db->query("SET DATESTYLE TO PostgreSQL,European;");
		$this->USER_GROUP= $this->kauth->getInstance()->getIdentity()->USER_GROUP;
		$this->LOGIN_LEVEL= $this->kauth->getInstance()->getIdentity()->LOGIN_LEVEL;
		$this->LOGIN_USER= $this->kauth->getInstance()->getIdentity()->LOGIN_USER;
		$this->LOGIN_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_ID;
		$this->LOGIN_PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID;
		$this->ID = $this->kauth->getInstance()->getIdentity()->ID;   
		$this->KELOMPOK = $this->kauth->getInstance()->getIdentity()->KELOMPOK;			
		$this->username = $this->kauth->getInstance()->getIdentity()->USERNAME;
		$this->KODE_CABANG = $this->kauth->getInstance()->getIdentity()->KODE_CABANG;
	}	
	
	function json() 
	{
		$this->load->model('HariLibur');
		$hari_libur = new HariLibur();
		
		/* LOGIN CHECK */
		/*if ($userLogin->checkUserLogin()) 
		{ 
			$userLogin->retrieveUserInfo();
		}*/
		$reqTahun = $this->input->get("reqTahun");
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		$aColumns = array('TANGGAL_AWAL', 'TANGGAL_AKHIR', 'TANGGAL_FIX', 'NAMA', 'KETERANGAN', 'HARI_LIBUR_ID');
		$aColumnsAlias = array('TANGGAL_AWAL', 'TANGGAL_AKHIR', 'TANGGAL_FIX', 'NAMA', 'KETERANGAN', 'HARI_LIBUR_ID');
		
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
		$allRecord = $hari_libur->getCountByParams(array(), $statement);
		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $hari_libur->getCountByParams(array(),  $searchJson.$statement);
		
		$hari_libur->selectByParams(array(),  $dsplyRange, $dsplyStart, $searchJson.$statement, $sOrder);
		//echo $hari_libur->query;exit;
		//echo "IKI ".$_GET['iDisplayStart'];

		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $allRecord,
			"iTotalDisplayRecords" => $allRecordFilter,
			"aaData" => array()
		);

		while($hari_libur->nextRow())
		{
			$row = array();
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				if($aColumns[$i] == "TANGGAL_FIX")
					$row[] = getDayMonth($hari_libur->getField(trim($aColumns[$i])));
				else			
					$row[] = $hari_libur->getField(trim($aColumns[$i]));
			}

			$output['aaData'][] = $row;
			$duk++;
		}

		echo json_encode( $output );

	}

	function add()
	{

		$this->load->model('HariLibur');

		$hari_libur = new HariLibur();

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

		$hari_libur->setField("LAST_USER", $this->LOGIN_USER);
		$hari_libur->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$hari_libur->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$hari_libur->setField("LAST_DATE", "NOW()");

		if($reqMode == "insert")
		{
			$hari_libur->setField('NAMA', $reqNama);
			$hari_libur->setField('KETERANGAN', $reqKeterangan);
			$hari_libur->setField('STATUS_CUTI_BERSAMA', $reqStatusCutiBersama);
			$hari_libur->setField('CABANG_ID', $reqCabangId);

			if($reqPilih == "Statis")
			{
				$hari_libur->setField('TANGGAL_AWAL', "NULL");
				$hari_libur->setField('TANGGAL_AKHIR', "NULL");
			}
			elseif ($reqPilih == "Dinamis" && $reqTanggalAkhir == "")
			{
				$hari_libur->setField('TANGGAL_AWAL', dateToDBCheck($reqTanggalAwal));
				$hari_libur->setField('TANGGAL_AKHIR', dateToDBCheck($reqTanggalAwal));
			}
			else
			{
				$hari_libur->setField('TANGGAL_AWAL', dateToDBCheck($reqTanggalAwal));
				$hari_libur->setField('TANGGAL_AKHIR', dateToDBCheck($reqTanggalAkhir));
			}

			$hari_libur->setField('TANGGAL_FIX', ValToNullDB($reqTanggalFix));
			if($hari_libur->insert()){
				$reqId= $set->id;
				echo $reqId."-Data berhasil disimpan.";
			} else {
				echo "xxx-Data gagal disimpan.";
			}
			//echo $hari_libur->query;
		}
		else
		{
			$hari_libur->setField('HARI_LIBUR_ID', $reqId);
			$hari_libur->setField('NAMA', $reqNama);
			$hari_libur->setField('KETERANGAN', $reqKeterangan);
			$hari_libur->setField('TANGGAL_AWAL', dateToDBCheck($reqTanggalAwal));
			$hari_libur->setField('TANGGAL_AKHIR', dateToDBCheck($reqTanggalAkhir));
			$hari_libur->setField('STATUS_CUTI_BERSAMA', $reqStatusCutiBersama);
			$hari_libur->setField('CABANG_ID', $reqCabangId);

			$hari_libur->setField('TANGGAL_FIX', ValToNullDB($reqTanggalFix));
			if($hari_libur->update()){
				echo $reqId."-Data berhasil disimpan.";
			} else {
				echo "xxx-Data gagal disimpan.";
			}
		}

	}

	function delete()
	{
		$this->load->model('HariLibur');
		$hari_libur = new HariLibur();

		$reqId = $this->uri->segment(3, "");
		$hari_libur->setField("HARI_LIBUR_ID", $reqId);

		if($hari_libur->delete())
			$arrJson["PESAN"] = "Data berhasil dihapus.";
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";	

		echo json_encode($arrJson);
	}	


	function hari_aktif()
	{
		$this->load->model('HariLibur');
		$hari_libur = new HariLibur();

		$reqAwal = $this->input->get("reqAwal");
		$reqAkhir = $this->input->get("reqAkhir");

		echo $hari_libur->getAktif($reqAwal, $reqAkhir, $this->KODE_CABANG);		
	}		

	function hari_aktif_awal()
	{
		$this->load->model('HariLibur');
		$hari_libur = new HariLibur();

		$reqAwal = $this->input->get("reqAwal");

		echo $hari_libur->getHariLibur($reqAwal, $reqAwal, $this->KODE_CABANG);		
	}	

	function hari_aktif_shift()
	{
		$this->load->model('HariLibur');
		$hari_libur = new HariLibur();

		$reqAwal = str_replace('-', '', $this->input->get("reqAwal"));
		$reqAkhir = str_replace('-', '', $this->input->get("reqAkhir"));

		echo $hari_libur->getCountHariAktifShift($this->ID, $reqAwal, $reqAkhir);	
	}

	function verifikasi_permohonan()
	{
		$this->load->model('HariLibur');
		$hari_libur = new HariLibur();

		$reqAwal = $this->input->get("reqAwal");
		$reqAkhir = $this->input->get("reqAkhir");

		echo $hari_libur->getVerifikasiPermohonan($this->ID, $reqAwal, $reqAkhir);	
	}

	function verifikasi_permohonan_pegawai()
	{
		$this->load->model('HariLibur');
		$hari_libur = new HariLibur();

		$reqPegawaiId = $this->input->get("reqPegawaiId");
		$reqAwal = $this->input->get("reqAwal");
		$reqAkhir = $this->input->get("reqAkhir");

		echo $hari_libur->getVerifikasiPermohonan($reqPegawaiId, $reqAwal, $reqAkhir);	
	}



	function verifikasi_permohonan_haid()
	{
		$this->load->model('HariLibur');
		$hari_libur = new HariLibur();

		$reqAwal = $this->input->get("reqAwal");
		$reqAkhir = $this->input->get("reqAkhir");

		echo $hari_libur->getVerifikasiPermohonanCutiHaid($this->ID, $reqAwal);	
	}

	function verifikasi_hari_aktif()
	{
		$this->load->model('HariLibur');
		$hari_libur = new HariLibur();

		$reqAwal = $this->input->get("reqAwal");
		$reqPenambahan = $this->input->get("reqPenambahan");

		$index = 0;
		for($i=0;$i<$reqPenambahan;$i++)
		{
			$hari_libur = new HariLibur();

			$hari_libur->selectByParamsCheckHariAktif($reqAwal, $i, $this->KODE_CABANG);
			$hari_libur->firstRow();

			if($this->KELOMPOK == "S")
				$aktif = "1";
			else
				$aktif = $hari_libur->getField("AKTIF");

			if($aktif == "1")
			{
				$arrJson[$index]["TANGGAL"] = $hari_libur->getField("TANGGAL");
				$arrJson[$index]["AKTIF"] = $aktif;		
				$index++;
			}

			unset($hari_libur);							
		}

		echo json_encode($arrJson);

		//echo $hari_libur->getField("TANGGAL")."[]".$aktif;	
	}	

	function verifikasi_hari()
	{
		$this->load->model('HariLibur');
		$hari_libur = new HariLibur();

		$reqAwal = $this->input->get("reqAwal");
		$reqPenambahan = $this->input->get("reqPenambahan");

		$index = 0;
		for($i=0;$i<$reqPenambahan;$i++)
		{
			$hari_libur = new HariLibur();

			$hari_libur->selectByParamsCheckHari($reqAwal, $i);
			$hari_libur->firstRow();

			$arrJson[$index]["TANGGAL"] = $hari_libur->getField("TANGGAL");
			$index++;

			unset($hari_libur);							
		}

		echo json_encode($arrJson);

		//echo $hari_libur->getField("TANGGAL")."[]".$aktif;	
	}	

	function checkHariLibur()
	{
		$this->load->model('HariLibur');
		$set= new HariLibur();

		$reqTanggal= $this->input->get("reqTanggal");

		$tempValue= $set->getHariAktif(dateToDBCheck($reqTanggal),dateToDBCheck($reqTanggal));
		echo $tempValue;
	}

	function pangkat()
	{
		$reqTanggal= $this->input->get("reqTanggal");
		$tempTanggal= strtotime($reqTanggal);
		$tempBatas= strtotime("01-04-2017");
		
		if($tempTanggal >= $tempBatas)
		$tempValue= 1;

		echo $tempValue;
	}

	function gaji()
	{
		$reqTanggal= $this->input->get("reqTanggal");
		$tempTanggal= strtotime($reqTanggal);
		$tempBatas= strtotime("01-02-2016");
		
		if($tempTanggal >= $tempBatas)
		$tempValue= 1;

		echo $tempValue;
	}

	function hakentrigaji()
	{
		$this->load->library('globalfilepegawai');

		$reqMode= $this->input->get("reqMode");
		$reqTanggal= $this->input->get("reqTanggal");

		$tempTanggal= strtotime($reqTanggal);
		$tempBatasInfo= $tempBatas= "";

		if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS PANGKAT/KGB") == "1")
		{
			$tempBatas= "";
			if($reqMode == "gaji")
			{
				$tempBatasInfo= "01-07-2018";
				$tempBatas= strtotime("01-07-2018");
			}
		}
		else
		{
			$tempBatasInfo= "01-04-2016";
			$tempBatas= strtotime("01-04-2016");
		}

		$settingpegawaifile= new globalfilepegawai();
		$infocarikey= "5";
		$settingpegawaifile= $settingpegawaifile->settingpegawaifile();
		$arrcheck= in_array_column($infocarikey, "KATEGORI_FILE_ID", $settingpegawaifile);
		// print_r($settingpegawaifile);exit;
		// print_r($arrcheck);exit;
		if(!empty($arrcheck))
		{
			$arrcheck= $settingpegawaifile[$arrcheck[0]];
			// print_r($arrcheck);exit;
			if($arrcheck["STATUS"] == "1")
			{
				$tempBatasInfo= "";
			}
		}

		if($tempBatasInfo == ""){}
		else
		{
			// echo $tempTanggal." > ".$tempBatas;exit();
			if($tempTanggal >= $tempBatas)
			$tempValue= 1;
		}

		// if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1")
		// {
		// 	if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS PANGKAT/KGB") == "1")
		// 	{
		// 		$tempBatas= "";
		// 	}
		// 	else
		// 	{
		// 		$tempBatasInfo= "01-07-2018";
		// 		$tempBatas= strtotime("01-07-2018");
		// 	}
		// }
		// // elseif($this->LOGIN_LEVEL == "99")
		// // {
		// // 	$tempBatas= "";
		// // }
		// else
		// {
		// 	$tempBatasInfo= "01-04-2016";
		// 	$tempBatas= strtotime("01-04-2016");
		// }

		// if($tempBatasInfo == ""){}
		// else
		// {
		// 	// echo $tempTanggal." > ".$tempBatas;exit();
		// 	if($tempTanggal >= $tempBatas)
		// 	$tempValue= 1;
		// }

		echo "".$tempValue.";".$tempBatasInfo;
	}
}
?>
