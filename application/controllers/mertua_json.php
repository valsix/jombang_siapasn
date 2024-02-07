<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class mertua_json extends CI_Controller {

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
		$this->load->model('Mertua');
		
		$ayah = new Mertua();
		$ibu = new Mertua();
		
		$reqId = $this->input->post("reqId");

		// $reqRowId= $this->input->post("reqRowId");
		$reqMode = $this->input->post("reqMode");

		$reqIdAyah = $this->input->post("reqIdAyah");
		$reqIdIbu = $this->input->post("reqIdIbu");

		$reqNamaAyah = $this->input->post("reqNamaAyah");
		$reqNamaIbu = $this->input->post("reqNamaIbu");
		$reqTmptLahirAyah = $this->input->post("reqTmptLahirAyah");
		$reqTmptLahirIbu = $this->input->post("reqTmptLahirIbu");
		$reqTglLahirAyah = $this->input->post("reqTglLahirAyah");
		$reqTglLahirIbu = $this->input->post("reqTglLahirIbu");
		// $reqUsiaAyah = $this->input->post("reqUsiaAyah");
		// $reqUsiaIbu = $this->input->post("reqUsiaIbu");
		$reqPekerjaanAyah = $this->input->post("reqPekerjaanAyah");
		$reqPekerjaanIbu = $this->input->post("reqPekerjaanIbu");
		$reqAlamatAyah = $this->input->post("reqAlamatAyah");
		$reqAlamatIbu = $this->input->post("reqAlamatIbu");
		$reqPropinsiAyahId = $this->input->post("reqPropinsiAyahId");
		$reqPropinsiIbuId = $this->input->post("reqPropinsiIbuId");
		$reqKabupatenAyahId = $this->input->post("reqKabupatenAyahId");
		$reqKabupatenIbuId = $this->input->post("reqKabupatenIbuId");
		$reqKecamatanAyahId = $this->input->post("reqKecamatanAyahId");
		$reqKecamatanIbuId = $this->input->post("reqKecamatanIbuId");
		$reqDesaAyahId = $this->input->post("reqDesaAyahId");
		$reqDesaIbuId = $this->input->post("reqDesaIbuId");
		$reqKodePosAyah = $this->input->post("reqKodePosAyah");
		$reqKodePosIbu = $this->input->post("reqKodePosIbu");
		$reqTeleponAyah = $this->input->post("reqTeleponAyah");
		$reqTeleponIbu = $this->input->post("reqTeleponIbu");
		$reqStatusAktifAyah = $this->input->post("reqStatusAktifAyah");
		$reqStatusAktifIbu = $this->input->post("reqStatusAktifIbu");
		
		
		$ayah->setField("NAMA", setQuote($reqNamaAyah, '1'));
		$ayah->setField("TEMPAT_LAHIR", $reqTmptLahirAyah);
		$ayah->setField("TANGGAL_LAHIR", dateToDBCheck($reqTglLahirAyah));
		$ayah->setField("PEKERJAAN", $reqPekerjaanAyah);
		$ayah->setField("ALAMAT", $reqAlamatAyah);
		$ayah->setField("KODEPOS", $reqKodePosAyah);
		$ayah->setField("TELEPON", $reqTeleponAyah);
		$ayah->setField("PROPINSI_ID", ValToNullDB($reqPropinsiAyahId));
		$ayah->setField("KABUPATEN_ID", ValToNullDB($reqKabupatenAyahId));
		$ayah->setField("KECAMATAN_ID", ValToNullDB($reqKecamatanAyahId));
		$ayah->setField("KELURAHAN_ID", ValToNullDB($reqDesaAyahId));
		
		$ayah->setField("PEGAWAI_ID", ValToNullDB($reqId));
		$ayah->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$ayah->setField("LAST_USER", $this->LOGIN_USER);
		$ayah->setField("LAST_DATE", "NOW()");
		$ayah->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$ayah->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));

		$cek = 0;

		// Add ayah
		if($reqIdAyah==0)
		{
			$ayah->setField("JENIS_KELAMIN", 'L');

			if($ayah->insert()) {
				$cek = 1;
				// echo "berhasil";
			}

		}
		else
		{
			$ayah->setField("JENIS_KELAMIN", 'L');
			$ayah->setField('MERTUA_ID', $reqIdAyah);

			if($ayah->update()) {
				$cek = 1;
				// echo "gagal";
			}
		}

		// Add ibu
		if ($cek == 1) {
			
			$ibu->setField('NAMA', setQuote($reqNamaIbu, '1'));
			$ibu->setField('TEMPAT_LAHIR', $reqTmptLahirIbu);
			$ibu->setField('TANGGAL_LAHIR', dateToDBCheck($reqTglLahirIbu));
			$ibu->setField('PEKERJAAN', $reqPekerjaanIbu);
			$ibu->setField('ALAMAT', $reqAlamatIbu);
			$ibu->setField('KODEPOS', $reqKodePosIbu);
			$ibu->setField('TELEPON', $reqTeleponIbu);
			$ibu->setField('PROPINSI_ID', ValToNullDB($reqPropinsiIbuId));
			$ibu->setField('KABUPATEN_ID', ValToNullDB($reqKabupatenIbuId));
			$ibu->setField('KECAMATAN_ID', ValToNullDB($reqKecamatanIbuId));
			$ibu->setField('KELURAHAN_ID', ValToNullDB($reqDesaIbuId));

			$ibu->setField("PEGAWAI_ID", ValToNullDB($reqId));
			$ibu->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
			$ibu->setField("LAST_USER", $this->LOGIN_USER);
			$ibu->setField("LAST_DATE", "NOW()");
			$ibu->setField("USER_LOGIN_ID", $this->LOGIN_ID);
			$ibu->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));

			if($reqIdIbu==0)
			{
				$ibu->setField("JENIS_KELAMIN", "P");

				if($ibu->insert()){
					$reqId= $ibu->id;
					echo $reqId."-Data berhasil disimpan.";
				} else {
					echo "xxx-Data gagal disimpan.";
				}
			}
			else
			{
				$ibu->setField("JENIS_KELAMIN", "P");
				$ibu->setField('MERTUA_ID', $reqIdIbu);

				if($ibu->update()){
					echo $reqIdIbu."-Data berhasil disimpan.";
				} else {
					echo "xxx-Data gagal disimpan.";
				}
			}
		} else {
			echo "xxx-Data gagal disimpan.";
		}

		
	}
	
	function delete()
	{
		$this->load->model('PangkatRiwayat');
		$set = new PangkatRiwayat();
		
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