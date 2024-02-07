<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class sk_pppk_json extends CI_Controller {

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
		$this->load->model('validasi/SkPppk');
		$this->load->model('PejabatPenetap');
		
		$set = new SkPppk();
		
        $reqTempValidasiHapusId= $this->input->post("reqTempValidasiHapusId");
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
            if(empty($reqTempValidasiId) && !empty($reqTempValidasiHapusId))
            {
                $set->setField('TEMP_VALIDASI_ID', $reqTempValidasiHapusId);
                $reqsimpan= "";
                if($set->deletehapusdata())
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


        }
        else
        {

    		$reqNoNotaBakn= $this->input->post("reqNoNotaBakn");
    		$reqTanggalNotaBakn= $this->input->post("reqTanggalNotaBakn");
    		$reqPejabatPenetapId= $this->input->post("reqPejabatPenetapId");
    		$reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
    		$reqNamaPejabatPenetap= $this->input->post("reqNamaPejabatPenetap");
    		$reqNipPejabatPenetap= $this->input->post("reqNipPejabatPenetap");
    		$reqNoSuratKeputusan= $this->input->post("reqNoSuratKeputusan");
    		$reqTanggalSuratKeputusan= $this->input->post("reqTanggalSuratKeputusan");
    		$reqTerhitungMulaiTanggal= $this->input->post("reqTerhitungMulaiTanggal");
    		$reqGolonganPppkId= $this->input->post("reqGolonganPppkId");
    		$reqTanggalTugas= $this->input->post("reqTanggalTugas");
    		$reqSkcpnsId= $this->input->post("reqSkcpnsId");
    		$reqTh= $this->input->post("reqTh");
    		$reqBl= $this->input->post("reqBl");
    		$reqNoPersetujuanNip= $this->input->post("reqNoPersetujuanNip");
    		$reqTanggalPersetujuanNip= $this->input->post("reqTanggalPersetujuanNip");
    		$reqPendidikan= $this->input->post("reqPendidikan");
    		$reqJurusan= $this->input->post("reqJurusan");
    		$reqGajiPokok= $this->input->post("reqGajiPokok");
    		$reqFormasiPppkId= $this->input->post("reqFormasiPppkId");
    		$reqJabatanTugas= $this->input->post("reqJabatanTugas");

    		$reqJenisFormasiTugasId= $this->input->post("reqJenisFormasiTugasId");
    		$reqJabatanFuId= $this->input->post("reqJabatanFuId");
    		$reqJabatanFtId= $this->input->post("reqJabatanFtId");
    		$reqStatusSkPppk= $this->input->post("reqStatusSkPppk");
    		$reqSpmtNomor= $this->input->post("reqSpmtNomor");
    		$reqSpmtTanggal= $this->input->post("reqSpmtTanggal");
    		$reqSpmtTmt= $this->input->post("reqSpmtTmt");
    		$reqNipPPPK= $this->input->post("reqNipPPPK");
    		$reqPendidikanRiwayatId= $this->input->post("reqPendidikanRiwayatId");

    		$set->setField("PENDIDIKAN_RIWAYAT_ID", ValToNullDB($reqPendidikanRiwayatId));
    		$set->setField("JENIS_FORMASI_TUGAS_ID", ValToNullDB($reqJenisFormasiTugasId));
    		$set->setField("JABATAN_FU_ID", ValToNullDB($reqJabatanFuId));
    		$set->setField("JABATAN_FT_ID", ValToNullDB($reqJabatanFtId));
    		$set->setField("STATUS_SK_PPPK", ValToNullDB($reqStatusSkPppk));
    		$set->setField("SPMT_NOMOR", $reqSpmtNomor);
    		$set->setField("SPMT_TANGGAL", dateToDBCheck($reqSpmtTanggal));
    		$set->setField("SPMT_TMT", dateToDBCheck($reqSpmtTmt));
    		$set->setField("NIP_PPPK", $reqNipPPPK);

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

    		$set->setField('GOLONGAN_PPPK_ID', $reqGolonganPppkId);
    		$set->setField('PEJABAT_PENETAP_ID', $reqPejabatPenetapId);
    		$set->setField('PEJABAT_PENETAP', $reqPejabatPenetap);
    		$set->setField('TMT_PPPK', dateToDBCheck($reqTerhitungMulaiTanggal));
    		$set->setField('TANGGAL_TUGAS', dateToDBCheck($reqTanggalTugas)); 
    		$set->setField('NO_NOTA', $reqNoNotaBakn);
    		$set->setField('TANGGAL_NOTA', dateToDBCheck($reqTanggalNotaBakn));
    		$set->setField('NO_SK', $reqNoSuratKeputusan);
    		$set->setField('TANGGAL_STTPP', dateToDBCheck($reqTanggalTugas));
    		$set->setField('NAMA_PENETAP', $reqNamaPejabatPenetap);
    		$set->setField('TANGGAL_SK', dateToDBCheck($reqTanggalSuratKeputusan));
    		$set->setField('NIP_PENETAP', $reqNipPejabatPenetap);
    		$set->setField('TANGGAL_UPDATE',$reqTan);
    		$set->setField('MASA_KERJA_TAHUN', ValToNullDB($reqTh));
    		$set->setField('MASA_KERJA_BULAN', ValToNullDB($reqBl));
    		$set->setField("GAJI_POKOK", ValToNullDB(dotToNo($reqGajiPokok)));
    		$set->setField("TANGGAL_PERSETUJUAN_NIP", dateToDBCheck($reqTanggalPersetujuanNip));
    		$set->setField("NO_PERSETUJUAN_NIP", $reqNoPersetujuanNip);
    		$set->setField("PENDIDIKAN_ID", ValToNullDB($reqPendidikan));
    		$set->setField("JURUSAN", $reqJurusan);
    		$set->setField("FORMASI_PPPK_ID", ValToNullDB($reqFormasiPppkId));
    		$set->setField("JABATAN_TUGAS", $reqJabatanTugas);

    		$set->setField('PEGAWAI_ID', ValToNullDB($reqId));
    		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
    		$set->setField("LAST_USER", $this->LOGIN_USER);
    		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
    		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
    		$set->setField("LAST_DATE", "NOW()");

            $set->setField('SK_PPPK_ID', $reqRowId);
            $set->setField('VALIDASI', $reqStatusValidasi);
            $set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);
    		
    		$reqsimpan= "";
            if(!empty($reqTempValidasiId))
            {
                if($set->update())
                {
                    $set->updatetanggalvalidasi();
                    $reqsimpan= "1";
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
        }

		if($reqsimpan == "1")
		{
			echo $reqRowId."-Data berhasil disimpan.";
		}
		else
			echo "xxx-Data gagal disimpan.";
		
	}

	function combogetjurusan()
	{
		$this->load->model("PendidikanRiwayat");
		
		$reqId= $this->input->get("reqId");
		
		$set= new PendidikanRiwayat();
		$statement= " AND A.PENDIDIKAN_RIWAYAT_ID = ".$reqId;
		$set->selectByParams(array(), -1,-1, $statement);
		$set->firstRow();
		echo $set->getField("PENDIDIKAN_JURUSAN_NAMA");
	}
	
	function delete()
	{
		$this->load->model('SkPppk');
		$set = new SkPppk();
		
		$reqId= $this->input->get('reqId');
		$reqMode=  $this->input->get('reqMode');

		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		$set->setField("SK_PPPK_ID", $reqId);
		
		if($reqMode == "pppk_0")
		{
			$set->setField("STATUS", "1");
			if($set->updateStatus())
				echo "Data berhasil dihapus.";
			else
				echo "Data gagal dihapus.";	
		}
		elseif($reqMode == "pppk_1")
		{
			$set->setField("STATUS", "2");
			if($set->updateStatus())
				echo "Data berhasil di aktifkan.";
			else
				echo "Data gagal di aktifkan.";	
		}
	}

	function hapus()
	{
		$this->load->model('SkPppk');
		$set = new SkPppk();
		
		$reqId= $this->input->get('reqId');
		$reqMode=  $this->input->get('reqMode');

		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		$set->setField("PEGAWAI_ID", $reqId);
		
		if($set->updateHapusStatus())
		{
			if($set->hapus())
				$arrJson["PESAN"] = "Data berhasil dihapus.";
			else
				$arrJson["PESAN"] = "Data gagal dihapus.";
		}
		else
			$arrJson["PESAN"] = "Data gagal dihapus.";
		
		echo json_encode($arrJson);
	}

}
?>