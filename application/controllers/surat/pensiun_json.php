<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class pensiun_json extends CI_Controller {

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
	}	

	function addshortcut()
	{
		$this->load->model('persuratan/Pensiun');
		$this->load->model('TandaTanganBkd');
		$this->load->model('PejabatPenetap');
		
		$reqMode= $this->input->get("reqMode");
		$reqStatusKgb= $this->input->get("reqStatusKgb");
		$reqPegawaiId= $this->input->get("reqPegawaiId");
		$reqBulan= $this->input->get("reqBulan");
		$reqTahun= $this->input->get("reqTahun");
		$reqPeriodeLama= $reqPeriode= $reqBulan.$reqTahun;
		
		$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.PERIODE = '".$reqPeriode."'";
		$set= new Pensiun();
		$set->selectByParamsData(array(), -1, -1, $statement);
		$set->firstRow();
		//echo $set->query;exit;
		$reqRiwayatGajiBaruTmt= dateToPageCheck($set->getField('TMT_BARU'));
		$reqRiwayatGajiBaruMasaKerjaTahun= $set->getField('MASA_KERJA_TAHUN_BARU');
		$reqRiwayatGajiBaruMasaKerjaBulan= $set->getField('MASA_KERJA_BULAN_BARU');
		$reqRiwayatGajiBaruGaji= numberToIna($set->getField('GAJI_BARU'));
		$reqRiwayatGajiBaruTanggalBaru= dateToPageCheck($set->getField('TANGGAL_BARU'));
		$reqGajiRiwayatLamaId= $set->getField('GAJI_RIWAYAT_LAMA_ID');
		$reqRiwayatGajiLamaGolId= $set->getField('PANGKAT_ID');
		$reqSatuanKerjaId= $set->getField('SATUAN_KERJA_ID');
		$reqSuratKeluarBkdId= $set->getField('SURAT_KELUAR_BKD_ID');
		$reqNomorUrut= $set->getField('NOMOR_URUT');
		$reqKeteranganTeknis= $set->getField('KETERANGAN_TEKNIS');
		
		if($reqMode == "updateselesai" || $reqMode == "update")
		{
			$statement= " AND A.TANDA_TANGAN_BKD_ID = (SELECT AMBIL_TANDA_TANGAN_BKD_TGL(TO_DATE('".$reqRiwayatGajiBaruTmt."', 'DD-MM-YYYY')))";
			$tandatangan= new TandaTanganBkd();
			$tandatangan->selectByParams(array(),-1,-1, $statement);
			$tandatangan->firstRow();
			//echo $tandatangan->query;exit;
			$reqPejabatPenetap= strtoupper($tandatangan->getField("PEJABAT_PENETAP"));
			
			if($reqPejabatPenetap == "")
			{
				$statementpejabat= " AND (COALESCE(NULLIF(A.NAMA, ''), NULL) IS NULL)";
			}
			else
			{
				$statementpejabat= " AND UPPER(A.NAMA) = '".$reqPejabatPenetap."'";
			}
			
			$pejabatpenetap= new PejabatPenetap();
			$pejabatpenetap->selectByParams(array(),-1,-1, $statementpejabat);
			//echo $pejabatpenetap->query;exit;
			$pejabatpenetap->firstRow();
			$reqPejabatPenetapId= $pejabatpenetap->getField("PEJABAT_PENETAP_ID");
			
			if($reqPejabatPenetapId == "")
			{
				$pejabatpenetap= new PejabatPenetap();
				$pejabatpenetap->setField("NAMA", strtoupper($reqPejabatPenetap));
				$pejabatpenetap->setField("LAST_USER", $this->LOGIN_USER);
				$pejabatpenetap->setField("LAST_DATE", "NOW()");
				$pejabatpenetap->insert();
				$reqPejabatPenetapId= $pejabatpenetap->id;
			}
		}
		//echo $reqPejabatPenetapId;exit;
		//TandaTanganBkd
		//PejabatPenetap
				
		//echo $reqRiwayatGajiLamaGolId;exit;
		$set = new Pensiun();
		$set->setField("GAJI_RIWAYAT_LAMA_ID", $reqGajiRiwayatLamaId);
		$set->setField("SATUAN_KERJA_ID", $reqSatuanKerjaId);
		$set->setField("TMT_BARU", dateToDBCheck($reqRiwayatGajiBaruTmt));
		$set->setField("MASA_KERJA_TAHUN_BARU", $reqRiwayatGajiBaruMasaKerjaTahun);
		$set->setField("MASA_KERJA_BULAN_BARU", $reqRiwayatGajiBaruMasaKerjaBulan);
		$set->setField("GAJI_BARU", ValToNullDB(dotToNo($reqRiwayatGajiBaruGaji)));
		$set->setField("TANGGAL_BARU", dateToDBCheck($reqRiwayatGajiBaruTanggalBaru));
		$set->setField("NOMOR_URUT", ValToNullDB($reqNomorUrut));
		$set->setField("PEGAWAI_ID", $reqPegawaiId);
		$set->setField("PERIODE", $reqPeriode);
		$set->setField("SURAT_KELUAR_BKD_ID", $reqSuratKeluarBkdId);
		$set->setField("STATUS_KGB", $reqStatusKgb);
		$set->setField("KETERANGAN_TEKNIS", $reqKeteranganTeknis);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("LAST_DATE", "NOW()");
			
		if($reqMode == "update" || $reqMode == "updateselesai")
		{
			$reqSimpan= "";
			if($reqMode == "updateselesai")
			{
				if($set->updateSelesai())
				{
					$reqSimpan= "1";
				}
			}
			else
			{
				//kalau kosong maka buat nomor urut baru
				if($reqNomorUrut == "")
				{
					if($set->updateSetNomor())
					{
						$reqSimpan= "1";
					}
				}
				else
				{
					if($set->update())
					{
						$reqSimpan= "1";
					}
				}
			}
			
			if($reqStatusKgb == "3")
			{
				$set_detil= new Pensiun();
				$set_detil->setField("PERIODE", $reqPeriode);
				$set_detil->setField('JENIS_KENAIKAN', 3);
				$set_detil->setField('NO_SK', $reqNoSk);
				$set_detil->setField('PANGKAT_ID', $reqRiwayatGajiLamaGolId);
				$set_detil->setField('TANGGAL_SK', dateToDBCheck($reqRiwayatGajiBaruTanggalBaru));
				$set_detil->setField("GAJI_POKOK", ValToNullDB(dotToNo($reqRiwayatGajiBaruGaji)));
				$set_detil->setField('MASA_KERJA_TAHUN', ValToNullDB($reqRiwayatGajiBaruMasaKerjaTahun));
				$set_detil->setField('MASA_KERJA_BULAN', ValToNullDB($reqRiwayatGajiBaruMasaKerjaBulan));
				$set_detil->setField('PEJABAT_PENETAP_ID', ValToNullDB($reqPejabatPenetapId));	
				$set_detil->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
				$set_detil->setField('TMT_SK', dateToDBCheck($reqRiwayatGajiBaruTmt));
				$set_detil->setField('BULAN_DIBAYAR', dateToDBCheck($reqRiwayatGajiBaruTanggalBaru));
				$set_detil->setField('PEGAWAI_ID', $reqPegawaiId);
				$set_detil->setField('GAJI_RIWAYAT_ID', $reqGajiRiwayatBaruId);
				$set_detil->setField("LAST_LEVEL", "50");
				$set_detil->setField("LAST_USER", "SIAP ASN");
				//$set_detil->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
				//$set_detil->setField("LAST_USER", $this->LOGIN_USER);
				$set_detil->setField("LAST_DATE", "NOW()");
				
				$reqSimpan= "";
				if($reqGajiRiwayatBaruId == "")
				{
					if($set_detil->insertGajiRiwayat())
					{
						$reqSimpan= "1";
					}
				}
				else
				{
					if($set_detil->updateGajiRiwayat())
					{
						$reqSimpan= "1";
					}
				}
				//echo $set_detil->query;exit;
				
				//kalau gagal
				if($reqSimpan == "")
				{
					$set_status = new Pensiun();
					$set_status->setField("PEGAWAI_ID", $reqPegawaiId);
					$set_status->setField("PERIODE", $reqPeriodeLama);
					$set_status->setField("STATUS", "NULL");
					$set_status->setField("STATUS_KGB", 2);
					$set_status->setField("LAST_USER", $this->LOGIN_USER);
					$set_status->setField("LAST_DATE", "NOW()");
					if($set_status->updateStatus()){}
				}
				
			}
			
			if($reqSimpan == "1")
			{
				$set_status = new Pensiun();
				$set_status->setField("PEGAWAI_ID", $reqPegawaiId);
				$set_status->setField("PERIODE", $reqPeriodeLama);
				$set_status->setField("LAST_USER", $this->LOGIN_USER);
				$set_status->setField("LAST_DATE", "NOW()");
				if($set_status->updateResetStatusHitung()){}
				
				echo "Data berhasil disimpan.";
			}
			else
			echo "Data gagal disimpan.";
		}
		else
			echo "Data gagal disimpan.";
		
	}
	
	function add()
	{
		$this->load->model('persuratan/Pensiun');
		$this->load->model('TandaTanganBkd');
		$this->load->model('PejabatPenetap');
		
		$reqMode= $this->input->post("reqMode");
		$reqStatusKgb= $this->input->post("reqStatusKgb");
		$reqSuratKeluarBkdId= $this->input->post("reqSuratKeluarBkdId");
		$reqPeriode= $this->input->post("reqPeriode");
		$reqPegawaiId= $this->input->post("reqPegawaiId");
		$reqNomorUrut= $this->input->post("reqNomorUrut");
		$reqRiwayatGajiBaruTanggalBaru= $this->input->post("reqRiwayatGajiBaruTanggalBaru");
		$reqRiwayatGajiBaruGaji= $this->input->post("reqRiwayatGajiBaruGaji");
		$reqRiwayatGajiBaruMasaKerjaBulan= $this->input->post("reqRiwayatGajiBaruMasaKerjaBulan");
		$reqRiwayatGajiBaruMasaKerjaTahun= $this->input->post("reqRiwayatGajiBaruMasaKerjaTahun");
		$reqRiwayatGajiBaruTmt= $this->input->post("reqRiwayatGajiBaruTmt");
		$reqSatuanKerjaId= $this->input->post("reqSatuanKerjaId");
		$reqGajiRiwayatLamaId= $this->input->post("reqGajiRiwayatLamaId");
		$reqKeteranganTeknis= $this->input->post("reqKeteranganTeknis");
		$reqPegawaiStatusId= $this->input->post("reqPegawaiStatusId");
		$reqJabatanRiwayatId= $this->input->post("reqJabatanRiwayatId");
		$reqHukumanRiwayatId= $this->input->post("reqHukumanRiwayatId");
		$reqPeriodeLama= $this->input->post("reqPeriodeLama");
		$reqRiwayatGajiLamaGolId= $this->input->post("reqRiwayatGajiLamaGolId");
		$reqGajiRiwayatBaruId= $this->input->post("reqGajiRiwayatBaruId");
		$reqStatusHitungUlang= $this->input->post("reqStatusHitungUlang");
		
		//echo $reqGajiRiwayatLamaId;exit;
		//set pejabat penetap
		if($reqMode == "updateselesai" || $reqMode == "update")
		{
			$statement= " AND A.TANDA_TANGAN_BKD_ID = (SELECT AMBIL_TANDA_TANGAN_BKD_TGL(TO_DATE('".$reqRiwayatGajiBaruTmt."', 'DD-MM-YYYY')))";
			$tandatangan= new TandaTanganBkd();
			$tandatangan->selectByParams(array(),-1,-1, $statement);
			$tandatangan->firstRow();
			//echo $tandatangan->query;exit;
			$reqPejabatPenetap= strtoupper($tandatangan->getField("PEJABAT_PENETAP"));
			
			if($reqPejabatPenetap == "")
			{
				$statementpejabat= " AND (COALESCE(NULLIF(A.NAMA, ''), NULL) IS NULL)";
			}
			else
			{
				$statementpejabat= " AND UPPER(A.NAMA) = '".$reqPejabatPenetap."'";
			}
			
			$pejabatpenetap= new PejabatPenetap();
			$pejabatpenetap->selectByParams(array(),-1,-1, $statementpejabat);
			//echo $pejabatpenetap->query;exit;
			$pejabatpenetap->firstRow();
			$reqPejabatPenetapId= $pejabatpenetap->getField("PEJABAT_PENETAP_ID");
			
			if($reqPejabatPenetapId == "")
			{
				$pejabatpenetap= new PejabatPenetap();
				$pejabatpenetap->setField("NAMA", strtoupper($reqPejabatPenetap));
				$pejabatpenetap->setField("LAST_USER", $this->LOGIN_USER);
				$pejabatpenetap->setField("LAST_DATE", "NOW()");
				$pejabatpenetap->insert();
				$reqPejabatPenetapId= $pejabatpenetap->id;
			}
		}
		//echo $reqPejabatPenetapId;exit;
		//TandaTanganBkd
		//PejabatPenetap
				
		//echo $reqRiwayatGajiLamaGolId;exit;
		$set = new Pensiun();
		$set->setField("GAJI_RIWAYAT_LAMA_ID", $reqGajiRiwayatLamaId);
		$set->setField("SATUAN_KERJA_ID", $reqSatuanKerjaId);
		$set->setField("TMT_BARU", dateToDBCheck($reqRiwayatGajiBaruTmt));
		$set->setField("MASA_KERJA_TAHUN_BARU", $reqRiwayatGajiBaruMasaKerjaTahun);
		$set->setField("MASA_KERJA_BULAN_BARU", $reqRiwayatGajiBaruMasaKerjaBulan);
		$set->setField("GAJI_BARU", ValToNullDB(dotToNo($reqRiwayatGajiBaruGaji)));
		$set->setField("TANGGAL_BARU", dateToDBCheck($reqRiwayatGajiBaruTanggalBaru));
		$set->setField("NOMOR_URUT", ValToNullDB($reqNomorUrut));
		$set->setField("PEGAWAI_ID", $reqPegawaiId);
		$set->setField("PERIODE", $reqPeriode);
		$set->setField("SURAT_KELUAR_BKD_ID", $reqSuratKeluarBkdId);
		$set->setField("STATUS_KGB", $reqStatusKgb);
		$set->setField("KETERANGAN_TEKNIS", $reqKeteranganTeknis);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("LAST_DATE", "NOW()");
			
		if($reqMode == "update" || $reqMode == "updateselesai")
		{
			$reqSimpan= "";
			if($reqMode == "updateselesai")
			{
				if($set->updateSelesai())
				{
					$reqSimpan= "1";
				}
			}
			else
			{
				//kalau kosong maka buat nomor urut baru
				if($reqNomorUrut == "")
				{
					if($set->updateSetNomor())
					{
						$reqSimpan= "1";
					}
				}
				else
				{
					if($set->update())
					{
						$reqSimpan= "1";
					}
				}
			}
			
			if($reqStatusKgb == "3")
			{
				$set_detil= new Pensiun();
				$set_detil->setField("PERIODE", $reqPeriode);
				$set_detil->setField('JENIS_KENAIKAN', 3);
				$set_detil->setField('NO_SK', $reqNoSk);
				$set_detil->setField('PANGKAT_ID', $reqRiwayatGajiLamaGolId);
				$set_detil->setField('TANGGAL_SK', dateToDBCheck($reqRiwayatGajiBaruTanggalBaru));
				$set_detil->setField("GAJI_POKOK", ValToNullDB(dotToNo($reqRiwayatGajiBaruGaji)));
				$set_detil->setField('MASA_KERJA_TAHUN', ValToNullDB($reqRiwayatGajiBaruMasaKerjaTahun));
				$set_detil->setField('MASA_KERJA_BULAN', ValToNullDB($reqRiwayatGajiBaruMasaKerjaBulan));
				$set_detil->setField('PEJABAT_PENETAP_ID', ValToNullDB($reqPejabatPenetapId));	
				$set_detil->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
				$set_detil->setField('TMT_SK', dateToDBCheck($reqRiwayatGajiBaruTmt));
				$set_detil->setField('BULAN_DIBAYAR', dateToDBCheck($reqRiwayatGajiBaruTanggalBaru));
				$set_detil->setField('PEGAWAI_ID', $reqPegawaiId);
				$set_detil->setField('GAJI_RIWAYAT_ID', $reqGajiRiwayatBaruId);
				$set_detil->setField("LAST_LEVEL", "50");
				$set_detil->setField("LAST_USER", "SIAP ASN");
				//$set_detil->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
				//$set_detil->setField("LAST_USER", $this->LOGIN_USER);
				$set_detil->setField("LAST_DATE", "NOW()");
				
				$reqSimpan= "";
				if($reqGajiRiwayatBaruId == "")
				{
					if($set_detil->insertGajiRiwayat())
					{
						$reqSimpan= "1";
					}
				}
				else
				{
					if($set_detil->updateGajiRiwayat())
					{
						$reqSimpan= "1";
					}
				}
				
				//kalau gagal
				if($reqSimpan == "")
				{
					$set_status = new Pensiun();
					$set_status->setField("PEGAWAI_ID", $reqPegawaiId);
					$set_status->setField("PERIODE", $reqPeriodeLama);
					$set_status->setField("STATUS", "NULL");
					$set_status->setField("STATUS_KGB", 2);
					$set_status->setField("LAST_USER", $this->LOGIN_USER);
					$set_status->setField("LAST_DATE", "NOW()");
					if($set_status->updateStatus()){}
				}
				
			}
			
			if($reqSimpan == "1")
			{
				$set_status = new Pensiun();
				$set_status->setField("PEGAWAI_ID", $reqPegawaiId);
				$set_status->setField("PERIODE", $reqPeriodeLama);
				$set_status->setField("LAST_USER", $this->LOGIN_USER);
				$set_status->setField("LAST_DATE", "NOW()");
				if($set_status->updateResetStatusHitung()){}
				
				echo "-Data berhasil disimpan.";
			}
			else
			echo "xxx-Data gagal disimpan.";
			
					
		}
		else
		{
			$statement= " AND A.PERIODE = '".$reqPeriode."' AND A.PEGAWAI_ID = ".$reqPegawaiId;
			$set_data= new Pensiun();
			$set_data->selectByParams(array(), -1,-1, $statement);
			$set_data->firstRow();
			//echo $set_data->query;exit;
			$tempPegawaiId= $set_data->getField("PEGAWAI_ID");
			
			$set_data = new Pensiun();
			$set_data->setField("PEGAWAI_ID", $reqPegawaiId);
			$set_data->setField("PERIODE", $reqPeriode);			
			$set_data->setField("PEGAWAI_STATUS_ID", $reqPegawaiStatusId);
			$set_data->setField("JABATAN_RIWAYAT_ID", $reqJabatanRiwayatId);
			$set_data->setField("SURAT_KELUAR_BKD_ID", $reqSuratKeluarBkdId);
			$set_data->setField("HUKUMAN_RIWAYAT_ID", ValToNullDB($reqHukumanRiwayatId));
			$set_data->setField("GAJI_RIWAYAT_LAMA_ID", $reqGajiRiwayatLamaId);
			$set_data->setField("SATUAN_KERJA_ID", $reqSatuanKerjaId);
			$set_data->setField("GAJI_BARU", ValToNullDB(dotToNo($reqRiwayatGajiBaruGaji)));
			$set_data->setField("TANGGAL_BARU", dateToDBCheck($reqRiwayatGajiBaruTanggalBaru));
			$set_data->setField("NOMOR_URUT", ValToNullDB($reqNomorUrut));
			$set_data->setField("TMT_BARU", dateToDBCheck($reqRiwayatGajiBaruTmt));
			$set_data->setField("MASA_KERJA_TAHUN_BARU", $reqRiwayatGajiBaruMasaKerjaTahun);
			$set_data->setField("MASA_KERJA_BULAN_BARU", $reqRiwayatGajiBaruMasaKerjaBulan);
			$set_data->setField("STATUS_KGB", 2);
			$set_data->setField("LAST_USER", $this->LOGIN_USER);
			$set_data->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
			$set_data->setField("LAST_DATE", "NOW()");
			
			$reqSimpan= "";
			if($tempPegawaiId == "")
			{
				if($set_data->insert())
				{
					$reqSimpan= "1";
				}
			}
			else
			{
				if($set_data->update())
				{
					$reqSimpan= "1";
				}
				//echo $set_data->query;exit;
			}
			
			if($reqSimpan == "1")
			{
				//kalau reqStatusKgb masih proses
				//if($reqStatusKgb == "2")
				//{
					$set_status = new Pensiun();
					$set_status->setField("PEGAWAI_ID", $reqPegawaiId);
					$set_status->setField("PERIODE", $reqPeriodeLama);
					$set_status->setField("STATUS", 1);
					$set_status->setField("STATUS_KGB", 4);
					$set_status->setField("LAST_USER", $this->LOGIN_USER);
					$set_status->setField("LAST_DATE", "NOW()");
					if($set_status->updateStatus())
					{
						echo "-Data berhasil disimpan.";
					}
					else
					{
						echo "xxx-Data gagal disimpan.";
					}
				//}
				//else
				//{
					//echo "xxx-Data gagal disimpan.";
				//}
			}
			else
			{
				echo "xxx-Data gagal disimpan.";
			}
				
			//echo $set_data->query;exit;
			//echo "xxx-Data gagal disimpan.";
		}
		//echo "-".$reqMode;exit;
	}
	
	function json() 
	{
		$this->load->model('persuratan/Pensiun');
		$this->load->model('persuratan/SuratMasukPegawai');

		$set = new Pensiun();
		
		$reqMode= $this->input->get("reqMode");
		$reqJenis= $this->input->get("reqJenis");
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$tempSatuanKerjaId= $reqSatuanKerjaId;
		$reqStatusKgb= $this->input->get("reqStatusKgb");
		$reqBulan= $this->input->get("reqBulan");
		$reqTahun= $this->input->get("reqTahun");
		$reqPangkatId= $this->input->get("reqPangkatId");
		$reqTipe= $this->input->get("reqTipe");
		
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);
		
		$aColumns = array("PEGAWAI_INFO", "NIP_BARU_LAMA", "NAMA_LENGKAP", "GOL_TMT", "JABATAN_TMT_ESELON", "SATUAN_KERJA_NAMA", "TMT", "KONDISI_NAMA", "KONDISI_PILIH", "JENIS", "PEGAWAI_ID");
		$aColumnsAlias = array("PEGAWAI_INFO", "NIP_BARU_LAMA", "NAMA_LENGKAP", "GOL_TMT", "JABATAN_TMT_ESELON", "SATUAN_KERJA_NAMA", "TMT", "KONDISI_NAMA", "KONDISI_PILIH", "JENIS", "PEGAWAI_ID");

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
		
		// if($reqMode == "proses" && $reqJenis == "bup")
		if($reqMode == "proses" && $reqJenis == "proses")
		{
			$set_detil= new Pensiun();
			// $set_detil->setField("JENIS", $reqJenis);
			$set_detil->setField("JENIS", "bup");
			$set_detil->setField("SATKERID", ValToNullDB($reqSatuanKerjaId));
			$set_detil->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
			$set_detil->setField("LAST_USER", $this->LOGIN_USER);
			$set_detil->prosesPensiun();
			// echo $set_detil->query;exit;
		}
		
		if($reqSatuanKerjaId == ""){}
		else
		{
			if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1")
			{
				$reqSatuanKerjaId= "";
				if($tempSatuanKerjaId == ""){}
				else
				{
					$reqSatuanKerjaId= $tempSatuanKerjaId;
					$skerja= new SuratMasukPegawai();
					$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
					unset($skerja);
					//echo $reqSatuanKerjaId;exit;
					$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				}
			}
			else
			{
				$skerja= new SuratMasukPegawai();
				$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
				unset($skerja);
				//echo $reqSatuanKerjaId;exit;
				$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
				//$statement= " AND ( A.SATUAN_KERJA_ID = ANY( AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$reqSatuanKerjaId.") ) OR A.SATUAN_KERJA_ID = ".$reqSatuanKerjaId." )";
			}
		}
		
		// AND SATUAN_KERJA_PARENT_ID > 0
		if($reqTipe == "bkd")
		{
			$statement.= " 
			AND A.SATUAN_KERJA_ID IN
			(
			SELECT SATUAN_KERJA_ID FROM SATUAN_KERJA A WHERE TIPE_ID = 1
			AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")
			)";
		}
		
		// echo $statement;exit();
		// if($reqSatuanKerjaId == ""){}
		// else
		// {
		// 	$statement= " AND A.SATUAN_KERJA_ID = ".$reqSatuanKerjaId;
		// }
		
		if($reqStatusKgb ==""){}
		else
		{
			$statement .= " AND A.STATUS_KGB = '".$reqStatusKgb."'";
		}
		
		if($reqBulan == "")
		{
			if($reqTahun == ""){}
			else
			$statement .= " AND TO_CHAR(A.TMT, 'YYYY') = '".$reqTahun."'";
		}
		else
		{
			if($reqTahun == ""){}
			else
			$statement .= " AND TO_CHAR(A.TMT, 'MMYYYY') = '".$reqBulan.$reqTahun."'";
		}

		if($reqJenis == "" || $reqJenis == "proses")
		{
			$statement .= " AND A.JENIS IN ('bup', 'meninggal')";
		}
		else
		$statement .= " AND A.JENIS = '".$reqJenis."'";

		// $statement .= " AND A.PEGAWAI_ID NOT IN (SELECT PEGAWAI_ID FROM persuratan.SURAT_MASUK_PEGAWAI WHERE KATEGORI = '".$reqJenis."')";
		// echo $statement;exit();
		$searchJson= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%' ) ";
		$allRecord = $set->getCountByParamsMonitoring(array(), $statement);
		// echo $allRecord;

		if($_GET['sSearch'] == "")
			$allRecordFilter = $allRecord;
		else	
			$allRecordFilter = $set->getCountByParamsMonitoring(array(), $statement.$searchJson);
		//echo $set->query;exit;
		$sOrder= "ORDER BY A.TMT";
		$set->selectByParamsMonitoringPegawai(array(), $dsplyRange, $dsplyStart, $statement.$searchJson, $sOrder);
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
				if($aColumns[$i] == "TMT")
					$row[] = getFormattedDate($set->getField($aColumns[$i]));
				else if($aColumns[$i] == "PEGAWAI_INFO")
				{
					$tempPath= $set->getField("PATH");
					if($tempPath == "")
					$tempPath= "images/foto-profile.jpg";
					
					$row[] = '<img src="'.$tempPath.'" style="width:100%;height:100%;" />';
				}
				else if($aColumns[$i] == "NIP_BARU_LAMA")
					$row[] = $set->getField("NIP_BARU")."<br/>".$set->getField("NIP_LAMA");
				else if($aColumns[$i] == "GOL_TMT")
					$row[] = $set->getField("PANGKAT_RIWAYAT_KODE")."<br/>".dateToPageCheck($set->getField("PANGKAT_RIWAYAT_TMT"));
				else if($aColumns[$i] == "JABATAN_TMT_ESELON")
					$row[] = $set->getField("JABATAN_RIWAYAT_NAMA")."<br/>".dateTimeToPageCheck($set->getField("JABATAN_RIWAYAT_TMT"))."<br/>".$set->getField("JABATAN_RIWAYAT_ESELON");
				else
					$row[] = $set->getField($aColumns[$i]);
			}

			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
	}
	
	function getinfototal() 
	{
		$this->load->model('persuratan/Pensiun');
		
		$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
		$reqBulan= $this->input->get("reqBulan");
		$reqTahun= $this->input->get("reqTahun");
		$reqPeriode= $reqBulan.$reqTahun;
		
		$set= new Pensiun();
		$set->selectByParamsInfoTotal($reqPeriode, $statement);
		$set->firstRow();
		$jumlah_data_kgb= $set->getField("JUMLAH_DATA_KGB");
		$jumlah_data_kgb_proses= $set->getField("JUMLAH_DATA_KGB_PROSES");
		$jumlah_data_kgb_selesai= $set->getField("JUMLAH_DATA_KGB_SELESAI");
		$jumlah_data_kgb_hukuman= $set->getField("JUMLAH_DATA_KGB_HUKUMAN");
		
		$arrData= array("jumlah_data_kgb"=>$jumlah_data_kgb, "jumlah_data_kgb_proses"=>$jumlah_data_kgb_proses, "jumlah_data_kgb_selesai"=>$jumlah_data_kgb_selesai, "jumlah_data_kgb_hukuman"=>$jumlah_data_kgb_hukuman);
		echo json_encode($arrData);
	}
	
	function getstatushitungulang() 
	{
		$this->load->model('persuratan/Pensiun');
		
		$reqPegawaiId= $this->input->get("reqPegawaiId");
		$reqBulan= $this->input->get("reqBulan");
		$reqTahun= $this->input->get("reqTahun");
		$reqPeriode= $reqBulan.$reqTahun;
		
		$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.PERIODE = '".$reqPeriode."'";
		$set= new Pensiun();
		$set->selectByParamsData(array(), -1, -1, $statement);
		$set->firstRow();
		//echo $set->query;exit;
		$reqStatusHitungUlang= $set->getField('STATUS_HITUNG_ULANG');
		$arrData= array("reqStatusHitungUlang"=>$reqStatusHitungUlang);
		echo json_encode($arrData);
	}
		
}
?>