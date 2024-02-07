<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class cetak_ijin_belajar_json extends CI_Controller {

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
	
	function add()
	{
		$this->load->model('persuratan/SuratMasukBkd');
		$this->load->model('persuratan/SuratMasukPegawai');
		$this->load->model('persuratan/CetakIjinBelajar');
		
		$reqRowId= $this->input->get("reqRowId");
		$reqMode= $this->input->get("reqMode");
		
		$statement= " AND SMP.SURAT_MASUK_PEGAWAI_ID = ".$reqRowId;
		$set= new SuratMasukBkd();
		$set->selectByParamsCetakPengantarSatuOrang(array(), -1, -1, $statement);
		$set->firstRow();
		//echo $set->query;exit;
		$reqJenisId= $set->getField('JENIS_ID');
		$reqSuratMasukBkdId= $set->getField('SURAT_MASUK_BKD_ID');
		$reqSuratMasukUptId= $set->getField('SURAT_MASUK_UPT_ID');
		$reqPegawaiId= $set->getField('PEGAWAI_ID');
		$reqNomorSuratKeluar= $set->getField('NOMOR_SURAT_KELUAR');
		$reqTanggalSuratKeluar= datetimeToPage($set->getField('TANGGAL_SURAT_KELUAR'), "date");
		$reqNomorSuratDinas= $set->getField('NOMOR');
		$reqTanggalSuratDinas= dateToPageCheck($set->getField('TANGGAL'));
		$reqNamaJabatanTtdSuratKeluar= ucwordsPertama($set->getField('TTD_SATUAN_KERJA'));
		$reqNamaPejabatTtdSuratKeluar= $set->getField('TTD_NAMA_PEJABAT');
		$reqPangkatTtdSuratKeluar= $set->getField('TTD_PANGKAT');
		$reqNipPejabatTtdSuratKeluar= $set->getField('TTD_NIP');
		$reqPegawaiNama= $set->getField('NAMA');
		$reqPegawaiNamaLengkap= $set->getField('NAMA_LENGKAP');
		$reqPegawaiNipBaru= $set->getField('NIP_BARU');
		$reqPegawaiPangkat= $set->getField('PANGKAT_RIWAYAT_NAMA')." - (".$set->getField('PANGKAT_RIWAYAT_KODE').")";
		$reqPegawaiTtl= $set->getField('TEMPAT_LAHIR').",  ".dateToPageCheck($set->getField('TANGGAL_LAHIR'));
		$reqPegawaiPendidikan= $set->getField('PENDIDIKAN_NAMA');
		$reqPegawaiJabatan= $set->getField('JABATAN_RIWAYAT_NAMA');
		$reqPegawaiSatuanKerja= $set->getField('SATUAN_KERJA_PEGAWAI_SURAT_KELUAR');
		$reqPendidikanNama= $set->getField('PENDIDIKAN_NAMA_US');
		$reqPendidikanJurusan= $set->getField('JURUSAN_US');
		$reqPendidikanSekolah= $set->getField('NAMA_SEKOLAH_US');
		
		$set= new CetakIjinBelajar();
		$set->setField("SURAT_MASUK_PEGAWAI_ID", $reqRowId);
		$set->setField("JENIS_ID", $reqJenisId);
		$set->setField("SURAT_MASUK_BKD_ID", $reqSuratMasukBkdId);
		$set->setField("SURAT_MASUK_UPT_ID", ValToNullDB($reqSuratMasukUptId));
		$set->setField("PEGAWAI_ID", $reqPegawaiId);
		$set->setField("NOMOR_SURAT_KELUAR", $reqNomorSuratKeluar);
		$set->setField("TANGGAL_SURAT_KELUAR", dateToDBCheck($reqTanggalSuratKeluar));
		$set->setField("NOMOR_SURAT_DINAS", $reqNomorSuratDinas);
		$set->setField("TANGGAL_SURAT_DINAS", dateToDBCheck($reqTanggalSuratDinas));
		$set->setField("NAMA_JABATAN_TTD_SURAT_KELUAR", $reqNamaJabatanTtdSuratKeluar);
		$set->setField("NAMA_PEJABAT_TTD_SURAT_KELUAR", $reqNamaPejabatTtdSuratKeluar);
		$set->setField("PANGKAT_PEJABAT_TTD_SURAT_KELUAR", $reqPangkatTtdSuratKeluar);
		$set->setField("NIP_PEJABAT_TTD_SURAT_KELUAR", $reqNipPejabatTtdSuratKeluar);
		$set->setField("PEGAWAI_NAMA", $reqPegawaiNama);
		$set->setField("PEGAWAI_NIP_BARU", $reqPegawaiNipBaru);
		$set->setField("PEGAWAI_PANGKAT", $reqPegawaiPangkat);
		$set->setField("PEGAWAI_TTL", $reqPegawaiTtl);
		$set->setField("PEGAWAI_PENDIDIKAN", $reqPegawaiPendidikan);
		$set->setField("PEGAWAI_JABATAN", $reqPegawaiJabatan);
		$set->setField("PEGAWAI_SATUAN_KERJA", $reqPegawaiSatuanKerja);
		$set->setField("PENDIDIKAN_NAMA", $reqPendidikanNama);
		$set->setField("PENDIDIKAN_JURUSAN", $reqPendidikanJurusan);
		$set->setField("PENDIDIKAN_SEKOLAH", $reqPendidikanSekolah);
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("LAST_DATE", "NOW()");
		if($set->insert())
		{
			$set_detil= new CetakIjinBelajar();
			$set_detil->setField("SURAT_MASUK_PEGAWAI_ID", $reqRowId);
			$set_detil->updateStatusKirim();
			echo $reqRowId."-Data berhasil disimpan";
		} else {
			echo "xxx-Data gagal disimpan.";
		}
		//CETAK_IJIN_BELAJAR_ID
	}
	
	function add_revisi()
	{
		$this->load->model('persuratan/CetakIjinBelajar');
		
		$reqRowId= $this->input->post("reqRowId");
		$reqJenisId= $this->input->post("reqJenisId");
		$reqSuratMasukBkdId= $this->input->post("reqSuratMasukBkdId");
		$reqSuratMasukUptId= $this->input->post("reqSuratMasukUptId");
		$reqPegawaiId= $this->input->post("reqPegawaiId");
		$reqNomorSuratKeluar= $this->input->post("reqNomorSuratKeluar");
		$reqTanggalSuratKeluar= $this->input->post("reqTanggalSuratKeluar");
		$reqNomorSuratDinas= $this->input->post("reqNomorSuratDinas");
		$reqTanggalSuratDinas= $this->input->post("reqTanggalSuratDinas");
		$reqNamaJabatanTtdSuratKeluar= $this->input->post("reqNamaJabatanTtdSuratKeluar");
		$reqNamaPejabatTtdSuratKeluar= $this->input->post("reqNamaPejabatTtdSuratKeluar");
		$reqPangkatTtdSuratKeluar= $this->input->post("reqPangkatTtdSuratKeluar");
		$reqNipPejabatTtdSuratKeluar= $this->input->post("reqNipPejabatTtdSuratKeluar");
		$reqPegawaiNama= $this->input->post("reqPegawaiNama");
		$reqPegawaiNipBaru= $this->input->post("reqPegawaiNipBaru");
		$reqPegawaiPangkat= $this->input->post("reqPegawaiPangkat");
		$reqPegawaiTtl= $this->input->post("reqPegawaiTtl");
		$reqPegawaiPendidikan= $this->input->post("reqPegawaiPendidikan");
		$reqPegawaiJabatan= $this->input->post("reqPegawaiJabatan");
		$reqPegawaiSatuanKerja= $this->input->post("reqPegawaiSatuanKerja");
		$reqPendidikanNama= $this->input->post("reqPendidikanNama");
		$reqPendidikanJurusan= $this->input->post("reqPendidikanJurusan");
		$reqPendidikanSekolah= $this->input->post("reqPendidikanSekolah");
		
		$set= new CetakIjinBelajar();
		$set->setField("SURAT_MASUK_PEGAWAI_ID", $reqRowId);
		$set->setField("JENIS_ID", $reqJenisId);
		$set->setField("SURAT_MASUK_BKD_ID", $reqSuratMasukBkdId);
		$set->setField("SURAT_MASUK_UPT_ID", ValToNullDB($reqSuratMasukUptId));
		$set->setField("PEGAWAI_ID", $reqPegawaiId);
		$set->setField("NOMOR_SURAT_KELUAR", $reqNomorSuratKeluar);
		$set->setField("TANGGAL_SURAT_KELUAR", dateToDBCheck($reqTanggalSuratKeluar));
		$set->setField("NOMOR_SURAT_DINAS", $reqNomorSuratDinas);
		$set->setField("TANGGAL_SURAT_DINAS", dateToDBCheck($reqTanggalSuratDinas));
		$set->setField("NAMA_JABATAN_TTD_SURAT_KELUAR", $reqNamaJabatanTtdSuratKeluar);
		$set->setField("NAMA_PEJABAT_TTD_SURAT_KELUAR", $reqNamaPejabatTtdSuratKeluar);
		$set->setField("PANGKAT_PEJABAT_TTD_SURAT_KELUAR", $reqPangkatTtdSuratKeluar);
		$set->setField("NIP_PEJABAT_TTD_SURAT_KELUAR", $reqNipPejabatTtdSuratKeluar);
		$set->setField("PEGAWAI_NAMA", $reqPegawaiNama);
		$set->setField("PEGAWAI_NIP_BARU", $reqPegawaiNipBaru);
		$set->setField("PEGAWAI_PANGKAT", $reqPegawaiPangkat);
		$set->setField("PEGAWAI_TTL", $reqPegawaiTtl);
		$set->setField("PEGAWAI_PENDIDIKAN", $reqPegawaiPendidikan);
		$set->setField("PEGAWAI_JABATAN", $reqPegawaiJabatan);
		$set->setField("PEGAWAI_SATUAN_KERJA", $reqPegawaiSatuanKerja);
		$set->setField("PENDIDIKAN_NAMA", $reqPendidikanNama);
		$set->setField("PENDIDIKAN_JURUSAN", $reqPendidikanJurusan);
		$set->setField("PENDIDIKAN_SEKOLAH", $reqPendidikanSekolah);
		$set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("LAST_DATE", "NOW()");
		if($set->insert())
		{
			$set_detil= new CetakIjinBelajar();
			$set_detil->setField("SURAT_MASUK_PEGAWAI_ID", $reqRowId);
			$set_detil->updateStatusKirim();
			echo $reqRowId."-Data berhasil disimpan";
		}else {
			echo "xxx-Data gagal disimpan.";
		}
		//CETAK_IJIN_BELAJAR_ID
	}
	
}
?>