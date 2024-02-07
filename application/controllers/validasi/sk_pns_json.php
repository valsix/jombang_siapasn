<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class sk_pns_json extends CI_Controller {

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
		$this->load->model('validasi/SkPns');
		$this->load->model('KenaikanGajiBerkala');
		$this->load->model('PejabatPenetap');
		
		$set = new SkPns();
		

		$reqTempValidasiId= $this->input->post("reqTempValidasiId");
		$reqStatusValidasi= $this->input->post("reqStatusValidasi");
		$reqId = $this->input->post("reqId");
		$reqRowId= $this->input->post("reqRowId");
		$reqMode = $this->input->post("reqMode");
		$reqPeriode= $this->input->post('reqPeriode');

		if(empty($reqStatusValidasi))
		{
			echo "xxx-Isikan terlebih dahulu Status Klarifikasi.";
			exit;
		}
		elseif($reqStatusValidasi == "2")
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
		else
		{

			$reqPejabatPenetapId= $this->input->post("reqPejabatPenetapId");
			$reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
			$reqNamaPejabatPenetap= $this->input->post("reqNamaPejabatPenetap");
			$reqNipPejabatPenetap= $this->input->post("reqNipPejabatPenetap");
			$reqNoSuratKeputusan= $this->input->post("reqNoSuratKeputusan");
			$reqTanggalSuratKeputusan= $this->input->post("reqTanggalSuratKeputusan");
			$reqTerhitungMulaiTanggal= $this->input->post("reqTerhitungMulaiTanggal");
			$reqGolRuang= $this->input->post("reqGolRuang");
			$reqTanggalTugas= $this->input->post("reqTanggalTugas");
			$reqNoDiklatPrajabatan= $this->input->post("reqNoDiklatPrajabatan");
			$reqTanggalDiklatPrajabatan= $this->input->post("reqTanggalDiklatPrajabatan");
			$reqNoSuratUjiKesehatan= $this->input->post("reqNoSuratUjiKesehatan");
			$reqTanggalSuratUjiKesehatan= $this->input->post("reqTanggalSuratUjiKesehatan");
			$reqPengambilanSumpah= $this->input->post("reqPengambilanSumpah");
			$reqSkPnsId= $this->input->post("reqSkPnsId");
			$reqTanggalSumpah= $this->input->post("reqTanggalSumpah");
			$reqTh= $this->input->post("reqTh");
			$reqBl= $this->input->post("reqBl");
			
			$reqNoBeritaAcara= $this->input->post("reqNoBeritaAcara");
			$reqTanggalBeritaAcara= $this->input->post("reqTanggalBeritaAcara");
			$reqKeteranganLpj= $this->input->post("reqKeteranganLpj");
			
			$reqGajiPokok= $this->input->post("reqGajiPokok");

			$reqJenisJabatanId= $this->input->post("reqJenisJabatanId");
			$reqStatusCalonJft= $this->input->post("reqStatusCalonJft");
			$reqJabatanTugas= $this->input->post("reqJabatanTugas");
			$reqJabatanFuId= $this->input->post("reqJabatanFuId");
			$reqJabatanFtId= $this->input->post("reqJabatanFtId");
			$reqJalurPengangkatan= $this->input->post("reqJalurPengangkatan");

			$set->setField("JENIS_JABATAN_ID", ValToNullDB($reqJenisJabatanId));
			$set->setField("STATUS_CALON_JFT", ValToNullDB($reqStatusCalonJft));
			$set->setField("JABATAN_TUGAS", $reqJabatanTugas);
			$set->setField("JABATAN_FU_ID", ValToNullDB($reqJabatanFuId));
			$set->setField("JABATAN_FT_ID", ValToNullDB($reqJabatanFtId));
			$set->setField("JALUR_PENGANGKATAN", ValToNullDB($reqJalurPengangkatan));

			//kalau pejabat tidak ada
			if($reqPejabatPenetapId == "")
			{
				$set_pejabat=new PejabatPenetap();
				$set_pejabat->setField('NAMA', strtoupper($reqPejabatPenetap));
				$set_pejabat->setField("LAST_USER", $this->LOGIN_USER);
				$set_pejabat->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				$set_pejabat->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
				$set_pejabat->setField("LAST_DATE", "NOW()");
				$set_pejabat->insert();
				// echo $set_pejabat->query;exit();
				$reqPejabatPenetapId=$set_pejabat->id;
				unset($set_pejabat);
			}
			
			$set->setField('PANGKAT_ID', $reqGolRuang);
			// $set->setField('PEJABAT_PENETAP_ID', ValToNullDB($reqPejabatPenetapId));	
			$set->setField('PEJABAT_PENETAP_ID', $reqPejabatPenetapId);
			$set->setField('PEJABAT_PENETAP_SUMPAH_ID', ValToNullDB($req));
			$set->setField('TMT_PNS', dateToDBCheck($reqTerhitungMulaiTanggal));
			$set->setField('TANGGAL_TUGAS', dateToDBCheck($reqTanggalTugas)); 
			$set->setField('NO_STTPP', '');
			$set->setField('PEGAWAI_ID', $reqPegawaiId);
			$set->setField('NO_NOTA', $reqNoNotaBAKN);
			$set->setField('TANGGAL_NOTA', dateToDBCheck($reqTanggalNotaBAKN));
			$set->setField('NO_SK', $reqNoSuratKeputusan);
			$set->setField('TANGGAL_STTPP', dateToDBCheck($reqTanggalTugas));
			$set->setField('NAMA_PENETAP', $reqNamaPejabatPenetap);
			$set->setField('TANGGAL_SK', dateToDBCheck($reqTanggalSuratKeputusan));
			$set->setField('NIP_PENETAP', $reqNipPejabatPenetap);
			$set->setField('NO_PRAJAB',$reqNoDiklatPrajabatan);
			$set->setField('NO_UJI_KESEHATAN',$reqNoSuratUjiKesehatan);		
			$set->setField('SK_PNS_ID',$reqSkPnsId);
			$set->setField('TANGGAL_UJI_KESEHATAN', dateToDBCheck($reqTanggalSuratUjiKesehatan));
			$set->setField('TANGGAL_PRAJAB', dateToDBCheck($reqTanggalDiklatPrajabatan));
			$set->setField('TANGGAL_SUMPAH', dateToDBCheck($reqTanggalSumpah));
			$set->setField('SUMPAH', (int)$reqPengambilanSumpah);
			$set->setField('MASA_KERJA_TAHUN', ValToNullDB($reqTh));
			$set->setField('MASA_KERJA_BULAN', ValToNullDB($reqBl));
			$set->setField("GAJI_POKOK", ValToNullDB(dotToNo($reqGajiPokok)));
			$set->setField('NOMOR_BERITA_ACARA',$reqNoBeritaAcara);
			$set->setField('TANGGAL_BERITA_ACARA', dateToDBCheck($reqTanggalBeritaAcara));
			$set->setField('KETERANGAN_LPJ',$reqKeteranganLpj);

			$set->setField('SK_PNS_ID',$reqRowId);
			$set->setField('VALIDASI', $reqStatusValidasi);
			$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);

			$set->setField('PEGAWAI_ID', $reqId);
			$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
			$set->setField("LAST_USER", $this->LOGIN_USER);
			$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
			$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			$set->setField("LAST_DATE", "NOW()");
			
			$reqsimpan= "";
			if(!empty($reqTempValidasiId))
			{
				if($set->update())
				{
					$set->updatetanggalvalidasi();
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
		
	}
	
	function delete()
	{
		$this->load->model('SkPns');
		$set = new SkPns();
		
		$reqId =  $this->input->get('reqId');
		$reqMode =  $this->input->get('reqMode');
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		$set->setField("ESELON_ID", $reqId);
		
		if($reqMode == "eselon_0")
		{
			$set->setField("STATUS", "1");
			if($set->updateStatus())
				$arrJson["PESAN"] = "Data berhasil dihapus.";
			else
				$arrJson["PESAN"] = "Data gagal dihapus.";	
		}
		elseif($reqMode == "eselon_1")
		{
			$set->setField("STATUS", ValToNullDB($req));
			if($set->updateStatus())
				$arrJson["PESAN"] = "Data berhasil dihapus.";
			else
				$arrJson["PESAN"] = "Data gagal dihapus.";	
		}	
		
		echo json_encode($arrJson);
	}
		
}
?>