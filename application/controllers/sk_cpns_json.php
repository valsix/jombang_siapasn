<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class sk_cpns_json extends CI_Controller {

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

		$this->load->model('SkCpns');
		$this->load->model('KenaikanGajiBerkala');
		$this->load->model('PejabatPenetap');
		
		$set = new SkCpns();
		
		$reqId = $this->input->post("reqId");
		$reqRowId= $this->input->post("reqRowId");
		$reqMode = $this->input->post("reqMode");
		$reqPeriode= $this->input->post('reqPeriode');

		$reqNoNotaBakn= $this->input->post("reqNoNotaBakn");
		$reqTanggalNotaBakn= $this->input->post("reqTanggalNotaBakn");
		$reqPejabatPenetapId= $this->input->post("reqPejabatPenetapId");
		$reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
		$reqNamaPejabatPenetap= $this->input->post("reqNamaPejabatPenetap");
		$reqNipPejabatPenetap= $this->input->post("reqNipPejabatPenetap");
		$reqNoSuratKeputusan= $this->input->post("reqNoSuratKeputusan");
		$reqTanggalSuratKeputusan= $this->input->post("reqTanggalSuratKeputusan");
		$reqTerhitungMulaiTanggal= $this->input->post("reqTerhitungMulaiTanggal");
		$reqGolRuang= $this->input->post("reqGolRuang");
		$reqTanggalTugas= $this->input->post("reqTanggalTugas");
		$reqSkcpnsId= $this->input->post("reqSkcpnsId");
		$reqTh= $this->input->post("reqTh");
		$reqBl= $this->input->post("reqBl");
		$reqNoPersetujuanNip= $this->input->post("reqNoPersetujuanNip");
		$reqTanggalPersetujuanNip= $this->input->post("reqTanggalPersetujuanNip");
		$reqPendidikan= $this->input->post("reqPendidikan");
		$reqJurusan= $this->input->post("reqJurusan");
		$reqGajiPokok= $this->input->post("reqGajiPokok");
		$reqFormasiCpnsId= $this->input->post("reqFormasiCpnsId");
		$reqJabatanTugas= $this->input->post("reqJabatanTugas");

		$reqJenisFormasiTugasId= $this->input->post("reqJenisFormasiTugasId");
		$reqJabatanFuId= $this->input->post("reqJabatanFuId");
		$reqJabatanFtId= $this->input->post("reqJabatanFtId");
		$reqStatusSkCpns= $this->input->post("reqStatusSkCpns");
		$reqSpmtNomor= $this->input->post("reqSpmtNomor");
		$reqSpmtTanggal= $this->input->post("reqSpmtTanggal");
		$reqSpmtTmt= $this->input->post("reqSpmtTmt");

		$set->setField("JENIS_FORMASI_TUGAS_ID", ValToNullDB($reqJenisFormasiTugasId));
		$set->setField("JABATAN_FU_ID", ValToNullDB($reqJabatanFuId));
		$set->setField("JABATAN_FT_ID", ValToNullDB($reqJabatanFtId));
		$set->setField("STATUS_SK_CPNS", ValToNullDB($reqStatusSkCpns));
		$set->setField("SPMT_NOMOR", $reqSpmtNomor);
		$set->setField("SPMT_TANGGAL", dateToDBCheck($reqSpmtTanggal));
		$set->setField("SPMT_TMT", dateToDBCheck($reqSpmtTmt));

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
		$set->setField('PEJABAT_PENETAP', $reqPejabatPenetap);
		$set->setField('TMT_CPNS', dateToDBCheck($reqTerhitungMulaiTanggal));
		$set->setField('TANGGAL_TUGAS', dateToDBCheck($reqTanggalTugas)); 
		// $set->setField('NO_STTPP', '');
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
		$set->setField("FORMASI_CPNS_ID", ValToNullDB($reqFormasiCpnsId));
		$set->setField("JABATAN_TUGAS", $reqJabatanTugas);

		$set->setField('PEGAWAI_ID', ValToNullDB($reqId));
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
		$set->setField("LAST_DATE", "NOW()");
		
		$reqstatushitung= "";
		$reqsimpan= "";

		if($reqMode == "insert")
		{
			if($set->insert())
			{
				$reqsimpan= "1";
				$reqRowId= $set->id;
			}
		}
		else
		{
			$set->setField('SK_CPNS_ID',$reqRowId);
			if($set->update())
			{
				$reqsimpan= "1";
			}
		}

		if($reqPeriode == ""){}
		else
		{
			if($reqJenis == 8 || $reqJenis == 9){}
			else
			{
				//apbila ada data baru,
				//- apabila tmt dasar < tmt data baru 
				//  dan tmt data baru <= tmt kgb baru
				//  dan tgl dasar < tgl data baru 
				//  dan tgl data baru <= tgl kgb baru
				
				$statement= " AND A.PEGAWAI_ID = ".$reqId." AND A.STATUS_KGB IN ('2','3') AND A.PERIODE = '".$reqPeriode."'";
				$set_kgb= new KenaikanGajiBerkala();
				$set_kgb->selectByParamsData(array(), -1,-1, $statement);
				$set_kgb->firstRow();
				$tempDasarTanggalVal= $tempDasarTanggal= dateToPageCheck($set_kgb->getField("TANGGAL_SK"));
				$tempDasarTmtVal= $tempDasarTmt= dateToPageCheck($set_kgb->getField("TMT_LAMA"));
				$tempKgbTanggal= dateToPageCheck($set_kgb->getField("TANGGAL_BARU"));
				$tempKgbTmt= dateToPageCheck($set_kgb->getField("TMT_BARU"));
				
				$tempDasarTanggal= strtotime($tempDasarTanggal);
				$tempDasarTmt= strtotime($tempDasarTmt);
				$tempKgbTanggal= strtotime($tempKgbTanggal);
				$tempKgbTmt= strtotime($tempKgbTmt);
				$tempDataBaruTanggal= strtotime($reqTanggalSuratKeputusan);
				$tempDataBaruTmt= strtotime($reqTerhitungMulaiTanggal);
				
				//if($tempDasarTmt < $tempDataBaruTmt && $tempDataBaruTmt <= $tempKgbTmt && $tempDasarTanggal < $tempDataBaruTanggal && $tempDataBaruTanggal <= $tempKgbTanggal)
				//if($tempDasarTmt < $tempDataBaruTmt && $tempDataBaruTmt < $tempKgbTmt && $tempDasarTanggal < $tempDataBaruTanggal && $tempDataBaruTanggal <= $tempKgbTanggal)
				if($tempDasarTmt < $tempDataBaruTmt && $tempDataBaruTmt < $tempKgbTmt && $tempDasarTanggal < $tempDataBaruTanggal)
				$reqstatushitung= "1";
				
				if($reqTh == $reqTempTh){}
				else
				$reqstatushitung= "1";
				
				if($reqBl == $reqTempBl){}
				else
				$reqstatushitung= "1";

				//echo $tempDasarTanggalVal." == ".$reqTanggalSuratKeputusan." && ".$tempDasarTmtVal." == ".$reqTerhitungMulaiTanggal;
				//exit;
				
				if($tempDasarTanggalVal == $reqTanggalSuratKeputusan && $tempDasarTmtVal == $reqTerhitungMulaiTanggal)
					$reqstatushitung= "1";
				
				// echo $tempDasarTanggalVal." == ".$reqTanggalSuratKeputusan." && ".$tempDasarTmtVal." == ".$reqTerhitungMulaiTanggal.";;".$reqstatushitung;
				// exit;
				
			}
		}
				
		if($reqsimpan == "1")
		{
			// untuk simpan file
			if($reqMode == "insert")
			{
				$this->load->model('PangkatRiwayat');
				$setdetil= new PangkatRiwayat();
				$setdetil->selectByParams(array(), -1,-1, " AND A.PEGAWAI_ID = ".$reqId." AND A.JENIS_RIWAYAT = 1");
				$setdetil->firstRow();
				$vpangkatriwayatid= $setdetil->getField("PANGKAT_RIWAYAT_ID");
				$vRowId= $vpangkatriwayatid;
			}
			else
			{
				$vRowId= $this->input->post("vRowId");
			}

			$vpost= $this->input->post();
			$vsimpanfilepegawai= new globalfilepegawai();
			$vsimpanfilepegawai->simpanfilepegawai($vpost, $vRowId, $reqLinkFile);

			if($reqstatushitung == "1")
			{
				$set = new KenaikanGajiBerkala();
				
				$set->setField('PERIODE', $reqPeriode);
				$set->setField('PEGAWAI_ID', $reqId);
				$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
				$set->setField("LAST_USER", $this->LOGIN_USER);
				$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
				$set->setField("LAST_DATE", "NOW()");
				$set->updateStatusHitung();
			}
			
			echo $reqRowId."-Data berhasil disimpan.";
		}
		else
			echo "xxx-Data gagal disimpan.";
		
	}
	
	function delete()
	{
		$this->load->model('SkCpns');
		$set = new SkCpns();
		
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

	function hapus()
	{
		$this->load->model('SkCpns');
		$set = new SkCpns();
		
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