<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class orang_tua_json extends CI_Controller {

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

		// khusus
		if(!empty($reqLinkFile))
		{
			// untuk validasi required file
			$validasifilerequired= new globalfilepegawai();
			$vpost= $this->input->post();
			$vinforequired= $validasifilerequired->validasifilerequired($vpost, $reqLinkFile);
			if(!empty($vinforequired))
			{
				echo "xxx-".$vinforequired;
				exit;
			}
		}

		$this->load->model('OrangTua');
		
		$ayah= new OrangTua();
		$ibu= new OrangTua();
		
		$reqId= $this->input->post("reqId");

		// $reqRowId= $this->input->post("reqRowId");
		$reqMode= $this->input->post("reqMode");

		$reqIdAyah= $this->input->post("reqIdAyah");
		$reqIdIbu= $this->input->post("reqIdIbu");

		$reqNamaAyah= $this->input->post("reqNamaAyah");
		$reqNamaIbu= $this->input->post("reqNamaIbu");
		$reqTmptLahirAyah= $this->input->post("reqTmptLahirAyah");
		$reqTmptLahirIbu= $this->input->post("reqTmptLahirIbu");
		$reqTglLahirAyah= $this->input->post("reqTglLahirAyah");
		$reqTglLahirIbu= $this->input->post("reqTglLahirIbu");
		// $reqUsiaAyah= $this->input->post("reqUsiaAyah");
		// $reqUsiaIbu= $this->input->post("reqUsiaIbu");
		$reqPekerjaanAyah= $this->input->post("reqPekerjaanAyah");
		$reqPekerjaanIbu= $this->input->post("reqPekerjaanIbu");
		$reqAlamatAyah= $this->input->post("reqAlamatAyah");
		$reqAlamatIbu= $this->input->post("reqAlamatIbu");
		$reqPropinsiAyahId= $this->input->post("reqPropinsiAyahId");
		$reqPropinsiIbuId= $this->input->post("reqPropinsiIbuId");
		$reqKabupatenAyahId= $this->input->post("reqKabupatenAyahId");
		$reqKabupatenIbuId= $this->input->post("reqKabupatenIbuId");
		$reqKecamatanAyahId= $this->input->post("reqKecamatanAyahId");
		$reqKecamatanIbuId= $this->input->post("reqKecamatanIbuId");
		$reqDesaAyahId= $this->input->post("reqDesaAyahId");
		$reqDesaIbuId= $this->input->post("reqDesaIbuId");
		$reqKodePosAyah= $this->input->post("reqKodePosAyah");
		$reqKodePosIbu= $this->input->post("reqKodePosIbu");
		$reqTeleponAyah= $this->input->post("reqTeleponAyah");
		$reqTeleponIbu= $this->input->post("reqTeleponIbu");
		$reqStatusAktifAyah= $this->input->post("reqStatusAktifAyah");
		$reqStatusAktifIbu= $this->input->post("reqStatusAktifIbu");
		
		
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
				$reqIdAyah= $this->id;
				$cek = 1;
			}

		}
		else
		{
			$ayah->setField("JENIS_KELAMIN", 'L');
			$ayah->setField('ORANG_TUA_ID', $reqIdAyah);

			if($ayah->update()) {
				$cek = 1;
				// echo "gagal";
			}
		}
		// echo $ayah->query;exit();

		// Add ibu
		if ($cek == 1) {
			
			// $ibu->setField('NAMA', setQuote($reqNamaIbu, '1'));
			$reqNamaIbu= str_replace("''", "`", $reqNamaIbu);
			$reqNamaIbu= str_replace("'", "`", $reqNamaIbu);
			$ibu->setField('NAMA', $reqNamaIbu);
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

			$infosimpan= "";
			if($reqIdIbu==0)
			{
				$ibu->setField("JENIS_KELAMIN", "P");

				if($ibu->insert())
				{
					$reqIdIbu= $this->id;
					$infosimpan= "1";
				}
			}
			else
			{
				$ibu->setField("JENIS_KELAMIN", "P");
				$ibu->setField('ORANG_TUA_ID', $reqIdIbu);

				if($ibu->update())
					$infosimpan= "1";
			}

			if($infosimpan == "1")
			{
				// khusus
				if(!empty($reqLinkFile))
				{
					// untuk simpan file
					$vpost= $this->input->post();
					$vsimpanfilepegawai= new globalfilepegawai();
					$vsimpanfilepegawai->simpanfilepegawai($vpost, $reqRowId, $reqLinkFile);
				}
				
				echo "-Data berhasil disimpan.";
			}
			else
			{
				echo "xxx-Data gagal disimpan.";	
			}
		}
		else
		{
			echo "xxx-Data Gagal disimpan";
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