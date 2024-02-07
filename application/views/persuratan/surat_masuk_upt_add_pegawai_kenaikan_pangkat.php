<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/SuratMasukPegawai');
$this->load->model('persuratan/SuratMasukUpt');
$this->load->model('persuratan/SuratMasukPegawaiCheck');
$this->load->library('globalfilepegawai');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqBreadCrum= $this->input->get("reqBreadCrum");
$reqJenis= $this->input->get("reqJenis");
$reqJenisNama= setjenisinfo($reqJenis);
$reqJenisSuratRekomendasi= setjenissuratrekomendasiinfo($reqJenis);
$reqMode= $this->input->get("reqMode");
$reqKpJenis= $this->input->get("reqKpJenis");

$lihatpangkatmode= "upt";
$statement= " AND A.SURAT_MASUK_UPT_ID = ".$reqId."";
$set= new SuratMasukUpt();
$set->selectByParams(array(), -1, -1, $statement);
$set->firstRow();
$reqPengaturanKenaikanPangkatTanggalPeriode= $set->getField("PENGATURAN_KENAIKAN_PANGKAT_TANGGAL_PERIODE");
$reqTahunSurat= getYear($reqPengaturanKenaikanPangkatTanggalPeriode);
$reqStatusKirim= $set->getField("STATUS_KIRIM");
$reqKategori= $set->getField("KATEGORI");
unset($set);

$disabled="";
if($reqRowId=="")
{
	$reqMode = 'insert';
	$reqNamaPegawai= $reqNamaPegawai= $reqPendidikanRiwayatAkhir= $reqStatusPendidikanTerakhirNama= $reqJurusanTerakhir= $reqNoSuratKehilanganTerakhir= " ";
	$reqKpStatusPendidikanRiwayatBelumDiakui= $reqKpStatusPendidikanRiwayatIjinTugas= $reqKpStatusSuratTandaLulus= $reqKpStatusSertifikatPendidik= $reqKpStatusSertifikatKeaslian= $reqKpPakPertamaStatusId= -1;
	$reqNipBaru= "";
	// $reqNipBaru= "196911172008011011";
}
else
{
	$disabled="disabled";
	$reqMode = 'update';
	/*$statement= " AND SMP.SURAT_MASUK_PEGAWAI_ID = ".$reqRowId;
	$set= new SuratMasukPegawai();
	$set->selectByParamsMonitoringPensiun(array(), -1, -1, $reqKategori,$statement);
	$set->firstRow();
  	//echo $set->query;exit;

	$reqSatuanKerjaPegawaiUsulanId= $set->getField('SATUAN_KERJA_ID');
	$reqPegawaiId= $set->getField('PEGAWAI_ID');

	$reqJabatanRiwayatAkhirId= $set->getField('JABATAN_RIWAYAT_AKHIR_ID');
	$reqPendidikanRiwayatAkhirId= $set->getField('PENDIDIKAN_RIWAYAT_AKHIR_ID');
	$reqGajiRiwayatAkhirId= $set->getField('GAJI_RIWAYAT_AKHIR_ID');
	$reqPangkatRiwayatAkhirId= $set->getField('PANGKAT_RIWAYAT_AKHIR_ID');

	$reqNipBaru= $set->getField('NIP_BARU');
	$reqNamaPegawai= $set->getField('NAMA_LENGKAP');
	
	$reqPangkatRiwayatAkhir= $set->getField('PANGKAT_RIWAYAT_KODE');
	$reqSatuanKerjaNama= $set->getField('SATUAN_KERJA_PEGAWAI_USULAN_NAMA');

	$reqPangkatRiwayatAkhir= $set->getField('PANGKAT_KODE');
	// echo $reqPangkatRiwayatAkhir;exit;
	$reqPangkatRiwayatAkhirTmt= dateToPageCheck($set->getField('PANGKAT_TMT'));
	$reqPangkatRiwayatAkhirTh= $set->getField('PANGKAT_TH');
	$reqPangkatRiwayatAkhirBl= $set->getField('PANGKAT_BL');
	$reqPensiunRiwayatAkhirTmt= dateToPageCheck($set->getField('PENSIUN_TMT'));
	$reqPensiunRiwayatAkhirTh= $set->getField('PENSIUN_TH');
	$reqPensiunRiwayatAkhirBl= $set->getField('PENSIUN_BL');
	$reqKematianNomorSK= $set->getField('PENSIUN_NOMOR_SK');
	$reqKematianTanggalSkKematian= dateToPageCheck($set->getField('PENSIUN_TANGGAL_SK_KEMATIAN'));
	$reqKematianTanggalKematian= dateToPageCheck($set->getField('PENSIUN_TANGGAL_KEMATIAN'));
	$reqKematianKeterangan= $set->getField('PENSIUN_KETERANGAN');
	$reqJabatanEselon= $set->getField('JABATAN_ESELON');
	$reqJabatanNama= $set->getField('JABATAN_NAMA');
	$reqJabatanTmt= dateTimeToPageCheck($set->getField('JABATAN_TMT'));

	$reqKeteranganPensiun= $set->getField('KETERANGAN_PENSIUN');*/

	$statement= " AND SMP.SURAT_MASUK_PEGAWAI_ID = ".$reqRowId;
	$set= new SuratMasukPegawai();
	$set->selectByParamsMonitoringKenaikanPangkat(array(), -1, -1, $reqKategori,$statement);
	$set->firstRow();
  	// echo $set->query;exit;

	$reqSatuanKerjaPegawaiUsulanId= $set->getField('SATUAN_KERJA_ID');
	$reqPegawaiId= $set->getField('PEGAWAI_ID');

	$reqJabatanTambahanAkhirId= $set->getField('JABATAN_TAMBAHAN_ID');
	$reqJabatanRiwayatAkhirId= $set->getField('JABATAN_RIWAYAT_AKHIR_ID');
	$reqPendidikanRiwayatAkhirId= $set->getField('PENDIDIKAN_RIWAYAT_AKHIR_ID');
	$reqGajiRiwayatAkhirId= $set->getField('GAJI_RIWAYAT_AKHIR_ID');
	$reqPangkatRiwayatAkhirId= $set->getField('PANGKAT_RIWAYAT_AKHIR_ID');

	$reqNipBaru= $set->getField('NIP_BARU');
	$reqNamaPegawai= $set->getField('NAMA_LENGKAP');
	
	$reqPangkatRiwayatAkhir= $set->getField('PANGKAT_RIWAYAT_KODE');
	$reqSatuanKerjaNama= $set->getField('SATUAN_KERJA_NAMA');

	$reqPangkatRiwayatAkhir= $set->getField('PANGKAT_KODE');
	// echo $reqPangkatRiwayatAkhir;exit;
	$reqPangkatRiwayatAkhirTmt= dateToPageCheck($set->getField('PANGKAT_TMT'));
	$reqPangkatRiwayatAkhirTh= $set->getField('PANGKAT_TH');
	$reqPangkatRiwayatAkhirBl= $set->getField('PANGKAT_BL');
	$reqJabatanEselon= $set->getField('JABATAN_ESELON');
	$reqJabatanNama= $set->getField('JABATAN_NAMA');
	$reqJabatanTmt= dateTimeToPageCheck($set->getField('JABATAN_TMT'));

	$reqPendidikanNama= $set->getField('PENDIDIKAN_JURUSAN_NAMA');
	$reqPendidikanTanggal= dateToPageCheck($set->getField('TANGGAL_STTB'));
	$reqPendidikanStatusNama= $set->getField('STATUS_PENDIDIKAN_NAMA');

	$reqKpJenis= $set->getField("KP_JENIS");
	$reqKpJenisNama= $set->getField("KP_JENIS_NAMA");

	$reqKpPangkatId= $set->getField("KP_PANGKAT_ID");
	$reqKpPangkatNama= $set->getField("KP_PANGKAT_NAMA");
	$reqKpStatusSuratTandaLulus= $set->getField("KP_STATUS_SURAT_TANDA_LULUS");
	$reqKpStatusSuratTandaLulusNama= $set->getField("KP_STATUS_SURAT_TANDA_LULUS_NAMA");
	$reqKpSuratTandaLulusIdNama= $set->getField("KP_NO_STLUD");
	$reqKpSuratTandaLulusTanggal= dateToPageCheck($set->getField("KP_TANGGAL_STLUD"));
	$reqKpStatusPendidikanRiwayatBelumDiakui= $set->getField("KP_STATUS_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI");
	$reqKpStatusPendidikanRiwayatBelumDiakuiNama= $set->getField("KP_STATUS_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI_NAMA");
	$reqKpPendidikanRiwayatBelumDiakuiNama= $set->getField("KP_PENDIDIKAN_JURUSAN_NAMA");
	$reqKpPegawaiFileIdNama= $set->getField("KP_PATH_NAMA");

	$reqKpStatusStrukturalId= $set->getField("KP_STATUS_STRUKTURAL_ID");
	$reqKpStatusStrukturalNama= $set->getField("KP_STATUS_STRUKTURAL_NAMA");
	$reqKpDiklatStrukturalId= $set->getField("KP_DIKLAT_STRUKTURAL_ID");
	$reqKpDiklatStrukturalNama= $set->getField("KP_DIKLAT_STRUKTURAL_NAMA");
	$reqKpDiklatStrukturalTanggal= dateToPageCheck($set->getField("KP_DIKLAT_STRUKTURAL_TANGGAL"));
	$reqKpDiklatId= $set->getField("KP_DIKLAT_ID");

	$reqKpPakLamaId= $set->getField("KP_PAK_LAMA_ID");
	$reqKpPakLamaNama= $set->getField("KP_PAK_LAMA_NAMA");
	$reqKpPakLamaTanggal= dateToPageCheck($set->getField("KP_PAK_LAMA_TANGGAL"));
	$reqKpPakLamaNilai= $set->getField("KP_PAK_LAMA_NILAI");
	$reqKpPakBaruId= $set->getField("KP_PAK_BARU_ID");
	$reqKpPakBaruNama= $set->getField("KP_PAK_BARU_NAMA");
	$reqKpPakBaruTanggal= dateToPageCheck($set->getField("KP_PAK_BARU_TANGGAL"));
	$reqKpPakBaruNilai= $set->getField("KP_PAK_BARU_NILAI");

	$reqKpStatusSertifikatKeaslian= $set->getField("KP_STATUS_SERTIFIKAT_KEASLIAN");
	$reqKpStatusSertifikatKeaslianNama= $set->getField("KP_STATUS_SERTIFIKAT_KEASLIAN_NAMA");
	$reqKpStatusSertifikatPendidik= $set->getField("KP_STATUS_SERTIFIKAT_PENDIDIK");
	$reqKpStatusSertifikatPendidikNama= $set->getField("KP_STATUS_SERTIFIKAT_PENDIDIK_NAMA");

	$reqKpPegawaiFileSertifikatKeaslianIdNama= $set->getField("KP_SERTIFIKASI_ASLI_PATH_NAMA");
	$reqKpPegawaiFileSertifikatPendidikIdNama= $set->getField("KP_SERTIFIKASI_PENDIDIK_PATH_NAMA");

	$reqKpPakPertamaStatusId= $set->getField("KP_PAK_LAMA_STATUS");
	$reqKpPakPertamaStatusNama= $set->getField("KP_PAK_LAMA_STATUS_NAMA");
	
	$reqKeteranganPensiun= $set->getField('KETERANGAN_PENSIUN');

	$reqKpStatusPendidikanRiwayatIjinTugasNama= $set->getField('KP_STATUS_PENDIDIKAN_RIWAYAT_IJIN_TUGAS_NAMA');
	$reqKpStatusPendidikanRiwayatIjinTugas= $set->getField('KP_STATUS_PENDIDIKAN_RIWAYAT_IJIN_TUGAS');

	$reqKpStatusPendidikanRiwayatCantumGelarNama= $set->getField('KP_STATUS_PENDIDIKAN_RIWAYAT_CANTUM_GELAR_NAMA');
	$reqKpStatusPendidikanRiwayatCantumGelar= $set->getField('KP_STATUS_PENDIDIKAN_RIWAYAT_CANTUM_GELAR');
	$reqKpPendidikanRiwayatBelumDiakuiLabel= "Pilih terlebih dahulu Pencantuman Gelar.";
	if($reqKpStatusPendidikanRiwayatCantumGelar == "1")
		$reqKpPendidikanRiwayatBelumDiakuiLabel= "Pilih Pendidikan Yang Sudah Diakui Gelarnya";
	else if($reqKpStatusPendidikanRiwayatCantumGelar == "2")
		$reqKpPendidikanRiwayatBelumDiakuiLabel= "Pilih Pendidikan Yang Belum Diakui Gelarnya";
}
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="msapplication-tap-highlight" content="no">
	<meta name="description" content="Simpeg Jombang">
	<meta name="keywords" content="Simpeg Jombang">
	<title>Simpeg Jombang</title>
	<base href="<?=base_url()?>" />

	<link rel="stylesheet" type="text/css" href="css/gaya.css">

	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
	<script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
	<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

	<!-- AUTO KOMPLIT -->
	<link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
	<script src="lib/autokomplit/jquery-ui.js"></script>

	<script type="text/javascript"> 
		surattandalulusinfo= paklamainfo= diklatpiminfo= [];

		$(function(){
    		$(".preloader-wrapper").hide();

			$("#reqsimpan").click(function() { 
			  kondisisimpan= "";
			  <?
			  if($reqKpJenis == "kpreguler" && $reqRowId == "")
			  {
			  ?>
				  reqKpStatusSuratTandaLulus= $("#reqKpStatusSuratTandaLulus").val();
				  reqKpSuratTandaLulusId= $("#reqKpSuratTandaLulusId").val();
				  reqKpStatusPendidikanRiwayatBelumDiakui= $("#reqKpStatusPendidikanRiwayatBelumDiakui").val();
				  reqKpStatusPendidikanRiwayatIjinTugas= $("#reqKpStatusPendidikanRiwayatIjinTugas").val();
				  reqKpPendidikanRiwayatBelumDiakuiId= $("#reqKpPendidikanRiwayatBelumDiakuiId").val();
				  reqKpPegawaiFileId= $("#reqKpPegawaiFileId").val();
				  reqKpPegawaiFileId= "xxx";

				  // console.log(reqKpStatusSuratTandaLulus +"--"+reqKpSuratTandaLulusId);
				  // console.log(reqKpStatusPendidikanRiwayatBelumDiakui +"--"+reqKpPendidikanRiwayatBelumDiakuiId);
				  // console.log(reqKpPegawaiFileId);

				  if( 
				  		reqKpStatusPendidikanRiwayatBelumDiakui == "-1" ||
				  		reqKpSuratTandaLulusId == "-1" ||
				  		(reqKpStatusSuratTandaLulus == "1" && reqKpSuratTandaLulusId == "") ||
				  		(reqKpStatusSuratTandaLulus == "1" && reqKpSuratTandaLulusId == "-1") ||
				  		(reqKpStatusPendidikanRiwayatBelumDiakui == "1" && reqKpPendidikanRiwayatBelumDiakuiId == "") ||
				  		(reqKpStatusPendidikanRiwayatBelumDiakui == "1" && reqKpPendidikanRiwayatBelumDiakuiId == "-1") ||
				  		(reqKpStatusPendidikanRiwayatBelumDiakui == "1" && reqKpStatusPendidikanRiwayatIjinTugas == "") ||
				  		(reqKpStatusPendidikanRiwayatBelumDiakui == "1" && reqKpStatusPendidikanRiwayatIjinTugas == "-1") ||
				  		(reqKpStatusPendidikanRiwayatBelumDiakui == "1" && reqKpPegawaiFileId == "")
				  	)
				  {
				  	kondisisimpan= "1";
				  	// console.log("false");
				  }
			  <?
			  }
			  elseif($reqKpJenis == "kpstruktural" && $reqRowId == "")
			  {
			  ?>
				  reqKpPangkatId= $("#reqKpPangkatId").val();
				  reqKpStatusStruktural= $("#reqKpStatusStruktural").val();
				  reqKpSuratTandaLulusId= $("#reqKpSuratTandaLulusId").val();
				  reqKpDiklatStrukturalId= $("#reqKpDiklatStrukturalId").val();
				  reqKpStatusPendidikanRiwayatBelumDiakui= $("#reqKpStatusPendidikanRiwayatBelumDiakui").val();
				  reqKpStatusPendidikanRiwayatIjinTugas= $("#reqKpStatusPendidikanRiwayatIjinTugas").val();
				  reqKpPendidikanRiwayatBelumDiakuiId= $("#reqKpPendidikanRiwayatBelumDiakuiId").val();
				  reqKpPegawaiFileId= $("#reqKpPegawaiFileId").val();
				  reqKpPegawaiFileId= "xxx";

				  if( 
				  		reqKpStatusPendidikanRiwayatBelumDiakui == "-1" ||
				  		reqKpSuratTandaLulusId == "-1" ||
				  		(reqKpPangkatId == "41" && reqKpStatusStruktural == "") ||
				  		(reqKpStatusStruktural == "1" && reqKpSuratTandaLulusId == "") ||
				  		(reqKpStatusStruktural == "1" && reqKpSuratTandaLulusId == "-1") ||
				  		(reqKpStatusStruktural == "2" && reqKpDiklatStrukturalId == "") ||
				  		(reqKpStatusPendidikanRiwayatBelumDiakui == "1" && reqKpPendidikanRiwayatBelumDiakuiId == "") ||
				  		(reqKpStatusPendidikanRiwayatBelumDiakui == "1" && reqKpPendidikanRiwayatBelumDiakuiId == "-1") ||
				  		(reqKpStatusPendidikanRiwayatBelumDiakui == "1" && reqKpStatusPendidikanRiwayatIjinTugas == "") ||
				  		(reqKpStatusPendidikanRiwayatBelumDiakui == "1" && reqKpStatusPendidikanRiwayatIjinTugas == "-1") ||
				  		(reqKpStatusPendidikanRiwayatBelumDiakui == "1" && reqKpPegawaiFileId == "")
				  	)
				  {
				  	kondisisimpan= "1";
				  	// console.log("false");
				  }
			  <?
			  }
			  elseif($reqKpJenis == "kpjft" && $reqRowId == "")
			  {
			  ?>
			  	  reqKpPakPertamaStatusId= $("#reqKpPakPertamaStatusId").val();
			  	  reqKpPakLamaId= $("#reqKpPakLamaId").val();
			  	  reqKpPakBaruId= $("#reqKpPakBaruId").val();
			  	  reqKpStatusSertifikatKeaslian= $("#reqKpStatusSertifikatKeaslian").val();
			  	  reqKpPegawaiFileSertifikatKeaslianId= $("#reqKpPegawaiFileSertifikatKeaslianId").val();
				  reqKpStatusSertifikatPendidik= $("#reqKpStatusSertifikatPendidik").val();
				  reqKpPegawaiFileSertifikatPendidikId= $("#reqKpPegawaiFileSertifikatPendidikId").val();
				  reqKpStatusPendidikanRiwayatBelumDiakui= $("#reqKpStatusPendidikanRiwayatBelumDiakui").val();
				  reqKpStatusPendidikanRiwayatIjinTugas= $("#reqKpStatusPendidikanRiwayatIjinTugas").val();
				  reqKpPendidikanRiwayatBelumDiakuiId= $("#reqKpPendidikanRiwayatBelumDiakuiId").val();
				  reqKpPegawaiFileId= $("#reqKpPegawaiFileId").val();
				  reqKpPegawaiFileId= "xxx";

				  if( 
				  		reqKpStatusPendidikanRiwayatBelumDiakui == "-1" ||
				  		reqKpPakPertamaStatusId == "-1" ||
				  		reqKpStatusSertifikatPendidik == "-1" ||
				  		reqKpStatusSertifikatKeaslian == "-1" ||
				  		(reqKpPakLamaId == "" && reqKpPakBaruId == "" && reqKpPakPertamaStatusId == "") ||
				  		(reqKpPakLamaId == "" && reqKpPakPertamaStatusId == "1") ||
				  		(reqKpStatusSertifikatKeaslian == "1" && reqKpPegawaiFileSertifikatKeaslianId == "") ||
				  		(reqKpStatusSertifikatPendidik == "1" && reqKpPegawaiFileSertifikatPendidikId == "") ||
				  		(reqKpStatusPendidikanRiwayatBelumDiakui == "1" && reqKpPendidikanRiwayatBelumDiakuiId == "") ||
				  		(reqKpStatusPendidikanRiwayatBelumDiakui == "1" && reqKpPendidikanRiwayatBelumDiakuiId == "-1") ||
				  		(reqKpStatusPendidikanRiwayatBelumDiakui == "1" && reqKpStatusPendidikanRiwayatIjinTugas == "") ||
				  		(reqKpStatusPendidikanRiwayatBelumDiakui == "1" && reqKpStatusPendidikanRiwayatIjinTugas == "-1") ||
				  		(reqKpStatusPendidikanRiwayatBelumDiakui == "1" && reqKpPegawaiFileId == "")
				  	)
				  {
				  	kondisisimpan= "1";
				  	// console.log("false");
				  }
			  <?
			  }
			  elseif($reqKpJenis == "kppi" && $reqRowId == "")
			  {
			  ?>
			  	  reqKpSuratTandaLulusId= $("#reqKpSuratTandaLulusId").val();
			  	  reqKpPendidikanRiwayatBelumDiakuiId= $("#reqKpPendidikanRiwayatBelumDiakuiId").val();
				  reqKpPegawaiFileId= $("#reqKpPegawaiFileId").val();
				  reqKpPegawaiFileId= "xxx";

				  if( 
				  		reqKpPendidikanRiwayatBelumDiakuiId == "-1" ||
				  		reqKpSuratTandaLulusId == "-1" ||
				  		reqKpSuratTandaLulusId == "" ||
				  		reqKpPendidikanRiwayatBelumDiakuiId == "" || reqKpPegawaiFileId == ""
				  	)
				  {
				  	kondisisimpan= "1";
				  	// console.log("false");
				  }
			  <?
			  }
			  elseif($reqKpJenis == "kpstugas" && $reqRowId == "")
			  {
			  ?>
			  	  reqKpPendidikanRiwayatBelumDiakuiId= $("#reqKpPendidikanRiwayatBelumDiakuiId").val();
				  reqKpPegawaiFileId= $("#reqKpPegawaiFileId").val();
				  reqKpPegawaiFileId= "xxx";

				  if( 
				  		reqKpPendidikanRiwayatBelumDiakuiId == "-1" ||
				  		reqKpPendidikanRiwayatBelumDiakuiId == "" || reqKpPegawaiFileId == ""
				  	)
				  {
				  	kondisisimpan= "1";
				  	// console.log("false");
				  }
			  <?
			  }
			  ?>

			  reqKpPangkatId= $("#reqKpPangkatId").val();
			  if(reqKpPangkatId == "")
			  {
			  	kondisisimpan= "1";
			  	// console.log("false");
			  }

			  reqPenilaianSkpJumlah= $("#reqPenilaianSkpJumlah").val();
			  if(reqPenilaianSkpJumlah < 2)
			  {
			  	mbox.alert('penilaian skp/ppk harus ada minimal 2 tahun mundur sebelum tahun <?=$reqTahunSurat?>', {open_speed: 0});
		        return false;
			  }

		      if($("#ff").form('validate') == false || kondisisimpan == "1"){
		      	mbox.alert('Lengkapi data terlebih dahulu', {open_speed: 0});
		        return false;
		      }
		      // return false;
		      
		      var s_url= "surat/surat_masuk_pegawai_json/cek_kirim_upt?reqId=<?=$reqId?>";
		      $.ajax({'url': s_url,'success': function(dataajax){
		        var requrl= requrllist= "";
		        dataajax= String(dataajax);
		        if(dataajax == '1')
		        {
		          mbox.alert('Data sudah dikirim', {open_speed: 0});
		          return false;
		        }
		        else
		        $("#reqSubmit").click();
		      }});
		    });


			$('#ff').form({
				url:'surat/surat_masuk_pegawai_json/add_kenaikan_pangkat',
				onSubmit:function(){
					var reqPegawaiId= "";
					reqPegawaiId= $("#reqPegawaiId").val();

					if(reqPegawaiId == "")
					{
						mbox.alert("Lengkapi data terlebih dahulu", {open_speed: 0});
						return false;
					}

					if($(this).form('validate')){}
						else
						{
							$.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
							return false;
						}
					},
					success:function(data){
						// console.log(data);return false;
						data = data.split("-");
						rowid= data[0];
						infodata= data[1];
						if(rowid == "xxx")
						{
							mbox.alert(infodata, {open_speed: 0});
						}
						else
						{
							mbox.alert(infodata, {open_speed: 500}, interval = window.setInterval(function() 
							{
								clearInterval(interval);
								mbox.close();
								document.location.href= "app/loadUrl/persuratan/surat_masuk_upt_add_pegawai_kenaikan_pangkat/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqRowId="+rowid;
							}, 1000));
							$(".mbox > .right-align").css({"display": "none"});
						}

					}
				});

			$('input[id^="reqNipBaru"]').autocomplete({
				source:function(request, response){

					var id= this.element.attr('id');
					var replaceAnakId= replaceAnak= urlAjax= "";

					if (id.indexOf('reqNipBaru') !== -1)
					{
						urlAjax= "pendidikan_riwayat_json/cari_pegawai_kenaikan_pangkat_reguler?reqId=<?=$reqId?>&reqKpJenis=<?=$reqKpJenis?>&reqJenis=<?=$reqJenis?>";
					}

					// cegah 10 karakter baru bisa cari
					valcari= request.term;
					panjangcari= valcari.length;
					if(panjangcari < 10) return false;
					// console.log(panjangcari);

					$(".preloader-wrapper").show();

					$.ajax({
						url: urlAjax,
						type: "GET",
						dataType: "json",
						data: { term: request.term },
						success: function(responseData){
							$(".preloader-wrapper").hide();

							if(responseData == null)
							{
								response(null);
							}
							else
							{
								var array = responseData.map(function(element) {
									return {desc: element['desc'], id: element['id'], label: element['label'], namapegawai: element['namapegawai']
									, satuankerjaid: element['satuankerjaid'], satuankerjanama: element['satuankerjanama']
									, pangkatkode: element['pangkatkode'], pangkattmt: element['pangkattmt'], pangkatth: element['pangkatth']
									, pangkatid: element['pangkatid'], pangkatbl: element['pangkatbl']
									, pendidikanid: element['pendidikanid'], pendidikanama: element['pendidikanama'], pendidikantanggal: element['pendidikantanggal'], pendidikanstatusnama: element['pendidikanstatusnama']
									, jabatantambahanid: element['jabatantambahanid'], jabataneselon: element['jabataneselon']
									, jabatannama: element['jabatannama'], jabatantmt: element['jabatantmt']
									, jabatanriwayatid: element['jabatanriwayatid'], pendidikanriwayatid: element['pendidikanriwayatid']
					  				, gajiriwayatid: element['gajiriwayatid'], pangkatriwayatid: element['pangkatriwayatid']
									};
								});
								response(array);
							}
						}
					})
				},
				select: function (event, ui) 
				{ 
					var id= $(this).attr('id');
					if (id.indexOf('reqNipBaru') !== -1)
					{
						var indexId= "reqPegawaiId";
						pegawaiid= ui.item.id;
						namapegawai= ui.item.namapegawai;
						satuankerjaid= ui.item.satuankerjaid;
						satuankerjanama= ui.item.satuankerjanama;

						pangkatid= ui.item.pangkatid;
						pangkatkode= ui.item.pangkatkode;
						pangkattmt= ui.item.pangkattmt;
						pangkatth= ui.item.pangkatth;
						pangkatbl= ui.item.pangkatbl;
						
						jabatantambahanid= ui.item.jabatantambahanid;
						jabataneselon= ui.item.jabataneselon;
						jabatannama= ui.item.jabatannama;
						jabatantmt= ui.item.jabatantmt;

						pendidikanid= ui.item.pendidikanid;
						pendidikanama= ui.item.pendidikanama;
						pendidikantanggal= ui.item.pendidikantanggal;
						pendidikanstatusnama= ui.item.pendidikanstatusnama;

						jabatanriwayatid= ui.item.jabatanriwayatid;
						pendidikanriwayatid= ui.item.pendidikanriwayatid;
						gajiriwayatid= ui.item.gajiriwayatid;
						pangkatriwayatid= ui.item.pangkatriwayatid;

						$("#reqJabatanTambahanAkhirId").val(jabatantambahanid);
						$("#reqJabatanRiwayatAkhirId").val(jabatanriwayatid);
						$("#reqPendidikanRiwayatAkhirId").val(pendidikanriwayatid);
						$("#reqGajiRiwayatAkhirId").val(gajiriwayatid);
						$("#reqPangkatRiwayatAkhirId").val(pangkatriwayatid);
				
						$("#reqNamaPegawai").val(namapegawai);
						$("#reqSatuanKerjaPegawaiUsulanId").val(satuankerjaid);
						$("#reqSatuanKerjaNama").val(satuankerjanama);
						$("#reqPangkatRiwayatAkhir").val(pangkatkode);
						$("#reqPangkatRiwayatAkhirTmt").val(pangkattmt);
						$("#reqPangkatRiwayatAkhirTh").val(pangkatth);
						$("#reqPangkatRiwayatAkhirBl").val(pangkatbl);
						
						$("#reqJabatanEselon").val(jabataneselon);
						$("#reqJabatanNama").val(jabatannama);
						$("#reqJabatanTmt").val(jabatantmt);

						$("#reqPendidikanNama").val(pendidikanama);
						$("#reqPendidikanTanggal").val(pendidikantanggal);
						$("#reqPendidikanStatusNama").val(pendidikanstatusnama);

						$("#reqSatuanKerjaNama").val(satuankerjanama);

						// $('#labeldetilinfo').empty();
						$("#reqKpPangkatId option").remove();
						$("#reqKpPangkatId").material_select();
						$("<option value=''></option>").appendTo("#reqKpPangkatId");
						$.ajax({'url': "pangkat_json/combokenaikanpangkat/?reqRowId=<?=$reqId?>&reqMode=<?=$lihatpangkatmode?>&reqId="+pangkatid,'success': function(dataJson) {
							var data= JSON.parse(dataJson);

							var items = "";
							items += "<option></option>";
							$.each(data, function (i, SingleElement) {
								items += "<option value='" + SingleElement.id + "'>" + SingleElement.text + "</option>";
							});
							$("#reqKpPangkatId").html(items);
							$("#reqKpPangkatId").material_select();

							// untuk select kondisi harus diisi
							// $('#reqKpPangkatId').validatebox({required: true});
						}});

						<?
						if($reqRowId == "" && $reqKpJenis == "kpjft")
						{
						?>

						$("#reqKpPakLamaId option").remove();
						$("#reqKpPakLamaId").material_select();
						$("#reqKpPakBaruId option").remove();
						$("#reqKpPakBaruId").material_select();

						$("<option value=''></option>").appendTo("#reqKpPakLamaId");
						$("<option value=''></option>").appendTo("#reqKpPakBaruId");
						$.ajax({'url': "pak_json/combokenaikanpangkat/?reqId="+pegawaiid,'success': function(dataJson) {
							var data= JSON.parse(dataJson);
							paklamainfo= data;

							var items = "";
							items += "<option></option>";
							$.each(data, function (i, SingleElement) {
								items += "<option value='" + SingleElement.id + "'>" + SingleElement.text + "</option>";
							});
							$("#reqKpPakLamaId").html(items);
							$("#reqKpPakLamaId").material_select();

							$("#reqKpPakBaruId").html(items);
							$("#reqKpPakBaruId").material_select();
						}});

						<?
						}
						elseif($reqRowId == "" && $reqKpJenis == "kppi")
						{
						?>

						$("<option value=''></option>").appendTo("#reqKpSuratTandaLulusId");
						$.ajax({'url': "surat_tanda_lulus_json/combokenaikanpangkat/?reqJenis=penyesuaianijazah&reqId="+pegawaiid,'success': function(dataJson) {
							var data= JSON.parse(dataJson);

							var items = "";
							items += "<option></option>";
							$.each(data, function (i, SingleElement) {
								items += "<option value='" + SingleElement.id + "'>" + SingleElement.text + "</option>";
							});
							$("#reqKpSuratTandaLulusId").html(items);
							$("#reqKpSuratTandaLulusId").material_select();
						}});

						$("<option value=''></option>").appendTo("#reqKpPendidikanRiwayatBelumDiakuiId");
						$.ajax({'url': "pendidikan_riwayat_json/combokenaikanpangkat/?reqId="+pegawaiid,'success': function(dataJson) {
							var data= JSON.parse(dataJson);

							var items = "";
							items += "<option></option>";
							$.each(data, function (i, SingleElement) {
								items += "<option value='" + SingleElement.id + "'>" + SingleElement.text + "</option>";
							});
							$("#reqKpPendidikanRiwayatBelumDiakuiId").html(items);
							$("#reqKpPendidikanRiwayatBelumDiakuiId").material_select();
						}});

						$("<option value=''></option>").appendTo("#reqKpPegawaiFileId");
						$.ajax({'url': "pegawai_file_json/combokenaikanpangkat/?reqId="+pegawaiid,'success': function(dataJson) {
							var data= JSON.parse(dataJson);

							var items = "";
							items += "<option></option>";
							$.each(data, function (i, SingleElement) {
								items += "<option value='" + SingleElement.id + "'>" + SingleElement.text + "</option>";
							});
							$("#reqKpPegawaiFileId").html(items);
							$("#reqKpPegawaiFileId").material_select();
						}});

						<?
						}
						elseif($reqRowId == "" && $reqKpJenis == "kpbtugas")
						{
						?>

						$("<option value=''></option>").appendTo("#reqKpPendidikanRiwayatBelumDiakuiId");
						$.ajax({'url': "pendidikan_riwayat_json/combokenaikanpangkat/?reqId="+pegawaiid,'success': function(dataJson) {
							var data= JSON.parse(dataJson);

							var items = "";
							items += "<option></option>";
							$.each(data, function (i, SingleElement) {
								items += "<option value='" + SingleElement.id + "'>" + SingleElement.text + "</option>";
							});
							$("#reqKpPendidikanRiwayatBelumDiakuiId").html(items);
							$("#reqKpPendidikanRiwayatBelumDiakuiId").material_select();
						}});
						<?
						}
						elseif($reqRowId == "" && $reqKpJenis == "kpstugas")
						{
						?>

						$("<option value=''></option>").appendTo("#reqKpPendidikanRiwayatBelumDiakuiId");
						$.ajax({'url': "pendidikan_riwayat_json/combokenaikanpangkat/?reqId="+pegawaiid,'success': function(dataJson) {
							var data= JSON.parse(dataJson);

							var items = "";
							items += "<option></option>";
							$.each(data, function (i, SingleElement) {
								items += "<option value='" + SingleElement.id + "'>" + SingleElement.text + "</option>";
							});
							$("#reqKpPendidikanRiwayatBelumDiakuiId").html(items);
							$("#reqKpPendidikanRiwayatBelumDiakuiId").material_select();
						}});

						$("<option value=''></option>").appendTo("#reqKpPegawaiFileId");
						$.ajax({'url': "pegawai_file_json/combokenaikanpangkat/?reqId="+pegawaiid,'success': function(dataJson) {
							var data= JSON.parse(dataJson);

							var items = "";
							items += "<option></option>";
							$.each(data, function (i, SingleElement) {
								items += "<option value='" + SingleElement.id + "'>" + SingleElement.text + "</option>";
							});
							$("#reqKpPegawaiFileId").html(items);
							$("#reqKpPegawaiFileId").material_select();
						}});

						<?
						}
						?>

						// tambahan untuk surat ijin / tugas belajar, khusus slta kebawah
						if(pendidikanid <= 6)
						{
							$("<option value='3'>Tanpa Ijin Belajar</option>").appendTo("#reqKpStatusPendidikanRiwayatIjinTugas");
						}
						else
						{
							$("#reqKpStatusPendidikanRiwayatIjinTugas option[value='3']").remove();
						}

						// one reset
						$('#labeldetilinfo').show();
						if (window.parent && window.parent.document)
						{
							if (typeof window.parent.iframeLoaded === 'function')
							{
								parent.iframeLoaded();
							}
						}

						$('#labeldetiltambahaninfo').empty();
						vurl= "app/loadUrl/persuratan/surat_masuk_upt_add_pegawai_kenaikan_pangkat_detil/?reqId=<?=$reqId?>&reqKpJenis=<?=$reqKpJenis?>&reqPegawaiId="+ui.item.id+"&reqKpTahun=<?=$reqTahunSurat?>";
						$.ajax({
							'url': vurl
							, beforeSend: function () {
								$(".preloader-wrapper").show();
							}
							,'success': function(datahtml) {
								$(".preloader-wrapper").hide();
								$('#labeldetiltambahaninfo').append(datahtml);

								if (window.parent && window.parent.document)
								{
									if (typeof window.parent.iframeLoaded === 'function')
									{
										parent.iframeLoaded();
									}
								}
							}
						});
					}

					$("#"+indexId).val(ui.item.id).trigger('change');
				},
				autoFocus: true
			}).autocomplete( "instance" )._renderItem = function( ul, item ) {
			//
			return $( "<li>" )
			.append( "<a>" + item.desc + "</a>" )
			.appendTo( ul );
		};

		<?
		if($reqRowId == "")
		{
		?>
			$(".reqKpSuratTandaLulusIdInfo,.reqKpPendidikanRiwayatBelumDiakuiIdInfo,.reqKpPegawaiFileIdInfo").hide();
			$(".reqKpStatusStrukturalInfo,.reqKpDiklatIdInfo,.reqKpPakPertamaStatusInfo1,.reqKpPakPertamaStatusInfo2").hide();
			$(".reqKpPegawaiFileSertifikatKeaslianIdInfo,.reqKpPegawaiFileSertifikatPendidikIdInfo").hide();

			// untuk select kondisi harus diisi
			// $('#reqKpSuratTandaLulusId,#reqKpPendidikanRiwayatBelumDiakuiId,#reqKpPegawaiFileId').validatebox({required: false});
			// $('#reqKpSuratTandaLulusId,#reqKpPendidikanRiwayatBelumDiakuiId,#reqKpPegawaiFileId').removeClass('validatebox-invalid');
		<?
		}
		else
		{
		?>
			$('#labeldetilinfo').show();
			<?
			if($reqKpJenis == "kpreguler")
			{
			?>
				$(".reqKpSuratTandaLulusIdInfo,.reqKpPendidikanRiwayatBelumDiakuiIdInfo,.reqKpPegawaiFileIdInfo").hide();
			<?
				if($reqKpStatusSuratTandaLulus == "1")
				{
			?>
				$(".reqKpSuratTandaLulusIdInfo").show();	
			<?
				}

				if($reqKpStatusPendidikanRiwayatBelumDiakui == "1")
				{
			?>
				$(".reqKpPendidikanRiwayatBelumDiakuiIdInfo,.reqKpPegawaiFileIdInfo").show();	
			<?
				}
			}
			elseif($reqKpJenis == "kpstruktural")
			{
			?>
				$(".reqKpPendidikanRiwayatBelumDiakuiIdInfo,.reqKpPegawaiFileIdInfo").hide();
			<?
				if($reqKpStatusPendidikanRiwayatBelumDiakui == "1")
				{
			?>
				$(".reqKpPendidikanRiwayatBelumDiakuiIdInfo,.reqKpPegawaiFileIdInfo").show();	
			<?
				}
			}
			elseif($reqKpJenis == "kpjft")
			{
			?>
				$(".reqKpPendidikanRiwayatBelumDiakuiIdInfo,.reqKpPegawaiFileIdInfo").hide();
				$(".reqKpPegawaiFileSertifikatKeaslianIdInfo,.reqKpPegawaiFileSertifikatPendidikIdInfo,.reqKpPakPertamaStatusInfo1,.reqKpPakPertamaStatusInfo2").hide();
			<?
				if($reqKpStatusSertifikatKeaslian == "1")
				{
			?>
				$(".reqKpPegawaiFileSertifikatKeaslianIdInfo").show();	
			<?
				}

				if($reqKpStatusSertifikatPendidik == "1")
				{
			?>
				$(".reqKpPegawaiFileSertifikatPendidikIdInfo").show();	
			<?
				}

				if($reqKpStatusPendidikanRiwayatBelumDiakui == "1")
				{
			?>
				$(".reqKpPendidikanRiwayatBelumDiakuiIdInfo,.reqKpPegawaiFileIdInfo").show();	
			<?
				}

				if($reqKpPakPertamaStatusId == "")
				{
			?>
				$(".reqKpPakPertamaStatusInfo1,.reqKpPakPertamaStatusInfo2").show();
			<?
				}

				if($reqKpPakPertamaStatusId == "1")
				{
			?>
				$(".reqKpPakPertamaStatusInfo1").show();
			<?
				}
			}
			?>

		<?
		}
		?>

		$("#reqKpStatusSuratTandaLulus").change(function() { 
			setKpStatusSuratTandaLulus();
		});

		$("#reqKpStatusSertifikatKeaslian").change(function() { 
			setKpStatusSertifikatKeaslian();
		});

		$("#reqKpStatusSertifikatPendidik").change(function() { 
			setKpStatusSertifikatPendidik();
		});

		$("#reqKpStatusPendidikanRiwayatBelumDiakui").change(function() { 
			var reqKpStatusPendidikanRiwayatBelumDiakui= reqPegawaiId= "";
			reqKpStatusPendidikanRiwayatBelumDiakui= $("#reqKpStatusPendidikanRiwayatBelumDiakui").val();

			$("#reqKpPendidikanRiwayatBelumDiakuiId option").remove();
			$("#reqKpPendidikanRiwayatBelumDiakuiId").material_select();

			$("#reqKpPegawaiFileId option").remove();
			$("#reqKpPegawaiFileId").material_select();

			$(".reqKpPendidikanRiwayatBelumDiakuiIdInfo,.reqKpPegawaiFileIdInfo").hide();
			if(reqKpStatusPendidikanRiwayatBelumDiakui == "1")
			{
				reqKpPendidikanRiwayatBelumDiakuiLabel= "Pilih terlebih dahulu Pencantuman Gelar.";
				$("#reqKpPendidikanRiwayatBelumDiakuiLabel").text(reqKpPendidikanRiwayatBelumDiakuiLabel);

				$(".reqKpPendidikanRiwayatBelumDiakuiIdInfo,.reqKpPegawaiFileIdInfo").show();
			}
			else
			{
				$("#reqKpStatusPendidikanRiwayatCantumGelar").val("");
				$("#reqKpStatusPendidikanRiwayatCantumGelar").material_select();
			}
			
			if (window.parent && window.parent.document)
			{
				if (typeof window.parent.iframeLoaded === 'function')
				{
					parent.iframeLoaded();
				}
			}
		});

		$("#reqKpStatusPendidikanRiwayatCantumGelar").change(function() { 
			var reqKpStatusPendidikanRiwayatCantumGelar= reqPegawaiId= "";
			reqKpStatusPendidikanRiwayatCantumGelar= $("#reqKpStatusPendidikanRiwayatCantumGelar").val();
			reqKpStatusPendidikanRiwayatBelumDiakui= $("#reqKpStatusPendidikanRiwayatBelumDiakui").val();
			reqPegawaiId= $("#reqPegawaiId").val();

			$("#reqKpPendidikanRiwayatBelumDiakuiId option").remove();
			$("#reqKpPendidikanRiwayatBelumDiakuiId").material_select();

			$("#reqKpPegawaiFileId option").remove();
			$("#reqKpPegawaiFileId").material_select();

			reqKpPendidikanRiwayatBelumDiakuiLabel= "Pilih terlebih dahulu Pencantuman Gelar.";
			$("#reqKpPendidikanRiwayatBelumDiakuiLabel").text(reqKpPendidikanRiwayatBelumDiakuiLabel);
			if(reqKpStatusPendidikanRiwayatCantumGelar == ""){}
			else
			{
				reqStatusPendidikan= "";
				// ya
				if(reqKpStatusPendidikanRiwayatCantumGelar == "1")
				{
					reqKpPendidikanRiwayatBelumDiakuiLabel= "Pilih Pendidikan Yang Sudah Diakui Gelarnya";
					reqStatusPendidikan= "2";
				}
				// tidak
				else if(reqKpStatusPendidikanRiwayatCantumGelar == "2")
				{
					reqKpPendidikanRiwayatBelumDiakuiLabel= "Pilih Pendidikan Yang Belum Diakui Gelarnya";
					reqStatusPendidikan= "3";
				}

				$("#reqKpPendidikanRiwayatBelumDiakuiLabel").text(reqKpPendidikanRiwayatBelumDiakuiLabel);
				$("<option value=''></option>").appendTo("#reqKpPendidikanRiwayatBelumDiakuiId");
				$.ajax({'url': "pendidikan_riwayat_json/combokenaikanpangkat/?reqId="+reqPegawaiId+"&reqStatusPendidikan="+reqStatusPendidikan,'success': function(dataJson) {
					var data= JSON.parse(dataJson);

					var items = "";
					items += "<option></option>";
					$.each(data, function (i, SingleElement) {
						items += "<option value='" + SingleElement.id + "'>" + SingleElement.text + "</option>";
					});
					$("#reqKpPendidikanRiwayatBelumDiakuiId").html(items);
					$("#reqKpPendidikanRiwayatBelumDiakuiId").material_select();
				}});

				/*$("<option value=''></option>").appendTo("#reqKpPegawaiFileId");
				$.ajax({'url': "pegawai_file_json/combokenaikanpangkat/?reqId="+reqPegawaiId,'success': function(dataJson) {
					var data= JSON.parse(dataJson);

					var items = "";
					items += "<option></option>";
					$.each(data, function (i, SingleElement) {
						items += "<option value='" + SingleElement.id + "'>" + SingleElement.text + "</option>";
					});
					$("#reqKpPegawaiFileId").html(items);
					$("#reqKpPegawaiFileId").material_select();
				}});*/

			}
			
			if (window.parent && window.parent.document)
			{
				if (typeof window.parent.iframeLoaded === 'function')
				{
					parent.iframeLoaded();
				}
			}
		});

		$('#reqKpJenis').bind('change', function(ev) {
			setJenisKenaikanPangkat();
		});

		$('#reqKpPakPertamaStatusId').bind('change', function(ev) {
			setPakPertamaStatus();
		});

		$('#reqKpPangkatId').bind('change', function(ev) {
			var reqKpPangkatId= "";
			reqKpPangkatId= $("#reqKpPangkatId").val();

			$(".reqKpStatusStrukturalInfo").hide();
			if(reqKpPangkatId == "41")
				$(".reqKpStatusStrukturalInfo").show();

		});

		$('#reqKpStatusStruktural').bind('change', function(ev) {
			$("#reqKpDiklatStrukturalId option, #reqKpSuratTandaLulusId option").remove();
			$("#reqKpDiklatStrukturalId, #reqKpSuratTandaLulusId").material_select();

			var reqKpStatusStruktural= "";
			reqKpStatusStruktural= $("#reqKpStatusStruktural").val();

			$(".reqKpDiklatIdInfo,.reqKpSuratTandaLulusIdInfo").hide();
			$("#reqKpStatusSuratTandaLulus,#reqKpDiklatId").val("");
			if(reqKpStatusStruktural == "1")
			{
				$("#reqKpStatusSuratTandaLulus").val("1");
				$(".reqKpSuratTandaLulusIdInfo").show();
				setKpStatusSuratTandaLulus();
			}
			else if(reqKpStatusStruktural == "2")
			{
				$("#reqKpDiklatId").val("1");
				$(".reqKpDiklatIdInfo").show();
				setKpDiklatStruktural();
			}
			
			if (window.parent && window.parent.document)
			{
				if (typeof window.parent.iframeLoaded === 'function')
				{
					parent.iframeLoaded();
				}
			}
		});

	});
	
	function setPakPertamaStatus()
	{
		var reqKpPakPertamaStatusId= "";
		reqKpPakPertamaStatusId= $("#reqKpPakPertamaStatusId").val();
		$(".reqKpPakPertamaStatusInfo1,.reqKpPakPertamaStatusInfo2").hide();

		if(reqKpPakPertamaStatusId == "-1")
		{
			$("#reqKpPakLamaId,#reqKpPakLamaTanggal,#reqKpPakLamaNilai,#reqKpPakBaruId,#reqKpPakBaruTanggal,#reqKpPakBaruNilai").val("");
			$("#reqKpPakLamaId,#reqKpPakBaruId").material_select();
		}
		else if(reqKpPakPertamaStatusId == "")
		{
			$(".reqKpPakPertamaStatusInfo1,.reqKpPakPertamaStatusInfo2").show();
		}
		else if(reqKpPakPertamaStatusId == "1")
		{
			$(".reqKpPakPertamaStatusInfo1").show();
			$("#reqKpPakBaruId,#reqKpPakBaruTanggal,#reqKpPakBaruNilai").val("");
			$("#reqKpPakBaruId").material_select();
		}
	}

	function setKpStatusSuratTandaLulus()
	{
		var reqKpStatusSuratTandaLulus= reqPegawaiId= "";
		reqKpStatusSuratTandaLulus= $("#reqKpStatusSuratTandaLulus").val();
		reqPegawaiId= $("#reqPegawaiId").val();

		$("#reqKpSuratTandaLulusId option").remove();
		$("#reqKpSuratTandaLulusId").material_select();

		$(".reqKpSuratTandaLulusIdInfo").hide();
		reqKpJenis= "<?=$reqKpJenis?>";
		var reqKpPangkatId= "";
		reqKpPangkatId= $("#reqKpPangkatId").val();
		if(reqKpStatusSuratTandaLulus == "1" || (reqKpPangkatId == "41" && reqKpJenis == "kpstruktural"))
		{
			reqJenis= "";
			if(reqKpJenis == "kpreguler" || reqKpJenis == "kpstruktural")
				reqJenis= "ujiandinas";
			else if(reqKpJenis == "kppi")
				reqJenis= "penyesuaianijazah";

			$(".reqKpSuratTandaLulusIdInfo").show();
			$("<option value=''></option>").appendTo("#reqKpSuratTandaLulusId");
			$.ajax({'url': "surat_tanda_lulus_json/combokenaikanpangkat/?reqJenis="+reqJenis+"&reqId="+reqPegawaiId,'success': function(dataJson) {
				var data= JSON.parse(dataJson);
				surattandalulusinfo= data;

				var items = "";
				items += "<option></option>";
				$.each(data, function (i, SingleElement) {
					items += "<option value='" + SingleElement.id + "'>" + SingleElement.text + "</option>";
				});
				$("#reqKpSuratTandaLulusId").html(items);
				$("#reqKpSuratTandaLulusId").material_select();

			}});
		}
	}

	function setKpStatusSertifikatKeaslian()
	{
		var reqKpStatusSertifikatKeaslian= reqPegawaiId= "";
		reqKpStatusSertifikatKeaslian= $("#reqKpStatusSertifikatKeaslian").val();
		reqPegawaiId= $("#reqPegawaiId").val();

		$("#reqKpPegawaiFileSertifikatKeaslianId option").remove();
		$("#reqKpPegawaiFileSertifikatKeaslianId").material_select();

		$(".reqKpPegawaiFileSertifikatKeaslianIdInfo").hide();

		if(reqKpStatusSertifikatKeaslian == "1")
		{
			$("#reqKpPegawaiFileSertifikatKeaslianIdInfo").show();
			// $("<option value=''></option>").appendTo("#reqKpPegawaiFileSertifikatKeaslianId");
			// $.ajax({'url': "pegawai_file_json/combokenaikanpangkatsertifikat/?reqMode=2&reqId="+reqPegawaiId,'success': function(dataJson) {
			// 	var data= JSON.parse(dataJson);

			// 	var items = "";
			// 	items += "<option></option>";
			// 	$.each(data, function (i, SingleElement) {
			// 		items += "<option value='" + SingleElement.id + "'>" + SingleElement.text + "</option>";
			// 	});
			// 	$("#reqKpPegawaiFileSertifikatKeaslianId").html(items);
			// 	$("#reqKpPegawaiFileSertifikatKeaslianId").material_select();
			// }});
		}
	}

	function setKpStatusSertifikatPendidik()
	{
		var reqKpStatusSertifikatPendidik= reqPegawaiId= "";
		reqKpStatusSertifikatPendidik= $("#reqKpStatusSertifikatPendidik").val();
		reqPegawaiId= $("#reqPegawaiId").val();

		$("#reqKpPegawaiFileSertifikatPendidikId option").remove();
		$("#reqKpPegawaiFileSertifikatPendidikId").material_select();

		$(".reqKpPegawaiFileSertifikatPendidikIdInfo").hide();

		if(reqKpStatusSertifikatPendidik == "1")
		{
			$(".reqKpPegawaiFileSertifikatPendidikIdInfo").show();
			// $("<option value=''></option>").appendTo("#reqKpPegawaiFileSertifikatPendidikId");
			// $.ajax({'url': "pegawai_file_json/combokenaikanpangkatsertifikat/?reqMode=1&reqId="+reqPegawaiId,'success': function(dataJson) {
			// 	var data= JSON.parse(dataJson);

			// 	var items = "";
			// 	items += "<option></option>";
			// 	$.each(data, function (i, SingleElement) {
			// 		items += "<option value='" + SingleElement.id + "'>" + SingleElement.text + "</option>";
			// 	});
			// 	$("#reqKpPegawaiFileSertifikatPendidikId").html(items);
			// 	$("#reqKpPegawaiFileSertifikatPendidikId").material_select();
			// }});
		}
	}

	function setKpDiklatStruktural()
	{
		var reqKpDiklatId= reqPegawaiId= "";
		reqKpDiklatId= $("#reqKpDiklatId").val();
		reqPegawaiId= $("#reqPegawaiId").val();

		$("#reqKpDiklatStrukturalId option").remove();
		$("#reqKpDiklatStrukturalId").material_select();

		$(".reqKpDiklatIdInfo").hide();

		if(reqKpDiklatId == "1")
		{
			$(".reqKpDiklatIdInfo").show();
			$("<option value=''></option>").appendTo("#reqKpDiklatStrukturalId");
			$.ajax({'url': "diklat_struktural_json/combokenaikanpangkat/?reqId="+reqPegawaiId,'success': function(dataJson) {
				var data= JSON.parse(dataJson);
				diklatpiminfo= data;

				var items = "";
				items += "<option></option>";
				$.each(data, function (i, SingleElement) {
					items += "<option value='" + SingleElement.id + "'>" + SingleElement.text + "</option>";
				});
				$("#reqKpDiklatStrukturalId").html(items);
				$("#reqKpDiklatStrukturalId").material_select();
			}});
		}
	}

	function setJenisKenaikanPangkat()
    {
		var reqKpJenis= "";
		reqKpJenis= $("#reqKpJenis").val();
	    document.location.href = "app/loadUrl/persuratan/surat_masuk_upt_add_pegawai_kenaikan_pangkat?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqRowId=<?=$reqRowId?>&reqKpJenis="+reqKpJenis;
	}

	$(function(){
		$("#reqKpDiklatStrukturalId").change(function() {
			infoval= $(this).val();
			// console.log(diklatpiminfo);
			// console.log(infoval);

			if(Array.isArray(diklatpiminfo) && diklatpiminfo.length)
			{
				vdiklatpiminfo= diklatpiminfo.filter(item => item.id === infoval);
				vtanggal= "";
				if(Array.isArray(vdiklatpiminfo) && vdiklatpiminfo.length)
				{
					// console.log(vdiklatpiminfo);
					vtanggal= vdiklatpiminfo[0]["tanggal"];
				}
				$("#reqKpDiklatStrukturalTanggal").val(vtanggal);
			}

		});

		$("#reqKpSuratTandaLulusId").change(function() {
			infoval= $(this).val();
			// console.log(surattandalulusinfo);
			// console.log(infoval);

			if(Array.isArray(surattandalulusinfo) && surattandalulusinfo.length)
			{
				vsurattandalulusinfo= surattandalulusinfo.filter(item => item.id === infoval);
				vtanggal= "";
				if(Array.isArray(vsurattandalulusinfo) && vsurattandalulusinfo.length)
				{
					// console.log(vsurattandalulusinfo);
					vtanggal= vsurattandalulusinfo[0]["tanggal"];
				}
				$("#reqKpSuratTandaLulusTanggal").val(vtanggal);
			}
		});
		
		$("#reqKpPakLamaId, #reqKpPakBaruId").change(function() {
			infoval= $(this).val();
			infoid= $(this).attr('id');

			if(infoid == "reqKpPakLamaId")
				infoiddetil= "Lama";
			else
				infoiddetil= "Baru";

			// console.log(vpaklamainfo);
			// console.log(infoval);
			if(Array.isArray(paklamainfo) && paklamainfo.length)
			{
				vpaklamainfo= paklamainfo.filter(item => item.id === infoval);
				vtanggal= vnilai= "";
				if(Array.isArray(vpaklamainfo) && vpaklamainfo.length)
				{
					// console.log(vsurattandalulusinfo);
					vtanggal= vpaklamainfo[0]["tanggal"];
					vnilai= vpaklamainfo[0]["total"];
				}

				$("#reqKpPak"+infoiddetil+"Tanggal").val(vtanggal);
				$("#reqKpPak"+infoiddetil+"Nilai").val(vnilai);
			}
		});
	});

</script>

<!-- CORE CSS-->    
<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<!-- CSS style Horizontal Nav-->    
<link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
<!-- Custome CSS-->    
<link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

<link href="lib/mbox/mbox.css" rel="stylesheet">
<script src="lib/mbox/mbox.js"></script>
<link href="lib/mbox/mbox-modif.css" rel="stylesheet">

</head>

<body>    
	<!--Basic Form-->
	<div id="basic-form" class="section">
		<div class="row">
			<div class="col s12 m12" style="padding-left: 15px;">

				<ul class="collection card">
					<li class="collection-item ubah-color-warna">Pegawai Usul <?=$reqJenisNama?></li>
					<li class="collection-item">

						<div class="row">
							<form id="ff" method="post" enctype="multipart/form-data" novalidate>

								<div class="row">
									<div class="input-field col s12 m6">
										<label for="reqKpJenis" class="active">Jenis Kenaikan Pangkat</label>
										<?
										if($reqRowId == "")
										{
										?>
										<select name="reqKpJenis" id="reqKpJenis" >
											<option value="" <? if($reqKpJenis == "") echo 'selected'?>></option>
											<option value="kpreguler" <? if($reqKpJenis == "kpreguler") echo 'selected'?>>KP Reguler</option>
											<option value="kpstruktural" <? if($reqKpJenis == "kpstruktural") echo 'selected'?>>KP Pilihan (Jabatan Struktural)</option>
											<option value="kpjft" <? if($reqKpJenis == "kpjft") echo 'selected'?>>KP Pilihan (Jabatan Fungsional Tertentu)</option>
											<option value="kppi" <? if($reqKpJenis == "kppi") echo 'selected'?>>KP Pilihan (Penyesuian Ijazah)</option>
											<option value="kpbtugas" <? if($reqKpJenis == "kpbtugas") echo 'selected'?>>KP Pilihan (Sedang Melaksanakan Tugas)</option>
											<option value="kpstugas" <? if($reqKpJenis == "kpstugas") echo 'selected'?>>KP Pilihan (Setelah Selesai Tugas Belajar)</option>
										</select>
										<?
										}
										else
										{
										?>
										<input type="text" value="<?=$reqKpJenisNama?>" disabled />
										<?
										}
										?>
									</div>
								</div>

								<div class="row">
									<div class="input-field col s12 m6">
										<label for="reqNipBaru">NIP Baru</label>
										<?
										if($reqRowId == "")
										{
										?>
											<input placeholder="" required id="reqNipBaru" class="easyui-validatebox" type="text" value="<?=$reqNipBaru?>" />
											<?
										}
										else
										{
										?>
											<input id="reqNipBaru" type="hidden" value="<?=$reqNipBaru?>" />
											<input required type="text" value="<?=$reqNipBaru?>" disabled />
											<?
										}
										?>
									</div>
									<?
									if($reqRowId == "")
									{
										?>
										<div class="input-field col s12 m6" id="reqKeteranganInfoDetil">
										</div>
										<?
									}
									?>
								</div>

								<div class="row">
									<div class="input-field col s12">
										<label for="reqNamaPegawai" class="active">Nama Pegawai</label>
										<input placeholder id="reqNamaPegawai" class="easyui-validatebox" type="text" value="<?=$reqNamaPegawai?>" disabled />
									</div>
								</div>

								<div class="row">
									<div class="input-field col s12 m3">
										<label for="reqPangkatRiwayatAkhir">Gol Terakhir</label>
										<input placeholder type="text" id="reqPangkatRiwayatAkhir" value="<?=$reqPangkatRiwayatAkhir?>" disabled />
									</div>
									<div class="input-field col s12 m2">
										<label for="reqPangkatRiwayatAkhirTmt">TMT Gol Akhir</label>
										<input placeholder readonly class="color-disb" type="text" name="reqPangkatRiwayatAkhirTmt" id="reqPangkatRiwayatAkhirTmt" value="<?=$reqPangkatRiwayatAkhirTmt?>" />
									</div>
									<div class="input-field col s6 m1">
										<label for="reqPangkatRiwayatAkhirTh">MK Tahun</label>
										<input placeholder type="text" disabled class="easyui-validatebox" name="reqPangkatRiwayatAkhirTh" id="reqPangkatRiwayatAkhirTh" value="<?=$reqPangkatRiwayatAkhirTh?> " />
									</div>
									<div class="input-field col s6 m1">
										<label for="reqPangkatRiwayatAkhirBl">MK Bulan</label>
										<input placeholder type="text" class="easyui-validatebox" disabled name="reqPangkatRiwayatAkhirBl" id="reqPangkatRiwayatAkhirBl" value="<?=$reqPangkatRiwayatAkhirBl?> " />
									</div>
								</div>

								<div class="row">
									<div class="input-field col s12 m7">
										<label for="reqJabatanNama">Jabatan</label>
										<input placeholder type="text" id="reqJabatanNama" value="<?=$reqJabatanNama?>" disabled />
									</div>
									<div class="input-field col s12 m2">
										<label for="reqJabatanTmt">Tmt Jabatan</label>
										<input placeholder type="text" id="reqJabatanTmt" value="<?=$reqJabatanTmt?>" disabled />
									</div>
									<div class="input-field col s12 m2">
										<label for="reqJabatanEselon">Eselon</label>
										<input placeholder type="text" id="reqJabatanEselon" value="<?=$reqJabatanEselon?>" disabled />
									</div>
								</div>

								<div class="row">
									<div class="input-field col s12 m7">
										<label for="reqPendidikanNama">Pendidikan</label>
										<input placeholder type="text" id="reqPendidikanNama" value="<?=$reqPendidikanNama?>"disabled />
									</div>
									<div class="input-field col s12 m2">
										<label for="reqPendidikanTanggal">Tgl Ijazah</label>
										<input placeholder type="text" id="reqPendidikanTanggal" value="<?=$reqPendidikanTanggal?>" disabled />
									</div>
									<div class="input-field col s12 m2">
										<label for="reqPendidikanStatusNama">Status</label>
										<input placeholder type="text" id="reqPendidikanStatusNama" value="<?=$reqPendidikanStatusNama?>" disabled />
									</div>
								</div>

								<div class="row">
									<div class="input-field col s12 m12">
										<label for="reqSatuanKerjaNama">Satuan Kerja</label>
										<input placeholder type="text" id="reqSatuanKerjaNama" value="<?=$reqSatuanKerjaNama?>" disabled />
									</div>
								</div>

								<div id="labeldetilinfo" style="display: none;">
									<?
									if($reqRowId == "")
									{
									?>
										<div class="row">
											<div class="input-field col s12 m3">
												<select name="reqKpPangkatId" id="reqKpPangkatId" >
													<option value="" <? if($reqKpPangkatId == "") echo "selected";?>></option>
												</select>
												<label for="reqKpPangkatId">Golongan Baru Yang Diusulkan?</label>
											</div>
											<?
											if($reqKpJenis == "kpjft")
											{
											?>
											<div class="input-field col s12 m3">
												<select name="reqKpStatusSertifikatKeaslian" id="reqKpStatusSertifikatKeaslian" >
													<option value="-1" <? if($reqKpStatusSertifikatKeaslian == "-1") echo "selected";?>></option>
													<option value="" <? if($reqKpStatusSertifikatKeaslian == "") echo "selected";?>>Tidak</option>
													<option value="1" <? if($reqKpStatusSertifikatKeaslian == "1") echo "selected";?>>Ya</option>
												</select>
												<label for="reqKpStatusSertifikatKeaslian">Sertifikat Keaslian PAK (khusus IV/c)?</label>
											</div>
											<div class="input-field col s12 m3">
												<select name="reqKpStatusSertifikatPendidik" id="reqKpStatusSertifikatPendidik" >
													<option value="-1" <? if($reqKpStatusSertifikatPendidik == "-1") echo "selected";?>></option>
													<option value="" <? if($reqKpStatusSertifikatPendidik == "") echo "selected";?>>Tidak</option>
													<option value="1" <? if($reqKpStatusSertifikatPendidik == "1") echo "selected";?>>Ya</option>
												</select>
												<label for="reqKpStatusSertifikatPendidik">Sertifikat Pendidik?</label>
											</div>
											<?
											}
											elseif($reqKpJenis == "kpstruktural")
											{
											?>
											<div class="input-field col s12 m3">
												<select id="reqKpStatusStruktural" name="reqKpStatusStruktural" >
													<option value="" <? if($reqKpStatusStruktural == "") echo "selected";?>></option>
													<option value="3" <? if($reqKpStatusStruktural == "3") echo "selected";?>>Tidak Ada STLUD / DIklatpim</option>
													<option value="1" <? if($reqKpStatusStruktural == "1") echo "selected";?>>Ada STLUD</option>
													<option value="2" <? if($reqKpStatusStruktural == "2") echo "selected";?>>Ada Diklatpim</option>
												</select>
												<label for="reqKpStatusStruktural">Diklatpim/STLUD ?</label>
											</div>
											<?
											}
											?>
										</div>
									<?
									}
									else
									{
									?>
										<div class="row">
											<div class="input-field col s12 m3">
												<label for="reqKpPangkatNama">Golongan Baru Yang Diusulkan?</label>
												<input type="text" value="<?=$reqKpPangkatNama?>" disabled />
											</div>
											<?
											if($reqKpJenis == "kpjft")
											{
											?>
											<div class="input-field col s12 m3">
												<input placeholder type="text" value="<?=$reqKpStatusSertifikatKeaslianNama?>" disabled />
												<label for="reqKpStatusSertifikatKeaslianNama">Sertifikat Keaslian PAK (khusus IV/c)?</label>
											</div>
											<div class="input-field col s12 m3">
												<input placeholder type="text" value="<?=$reqKpStatusSertifikatPendidikNama?>" disabled />
												<label for="reqKpStatusSertifikatPendidikNama">Sertifikat Pendidik?</label>
											</div>
											<?
											}
											elseif($reqKpJenis == "kpstruktural")
											{
												if($reqKpPangkatId == "41")
												{
											?>
											<div class="input-field col s12 m3">
												<input placeholder type="text" value="<?=$reqKpStatusStrukturalNama?>" disabled />
												<label for="reqKpStatusStrukturalNama">Diklatpim/STLUD ?</label>
											</div>
											<?
												}
											}
											?>
										</div>
									<?
									}
									?>

									<?
									if($reqKpJenis == "kpreguler")
									{
										if($reqRowId == "")
										{
										?>
										<div class="row">
											<div class="input-field col s12 m3">
												<select name="reqKpStatusSuratTandaLulus" id="reqKpStatusSuratTandaLulus" >
													<option value="-1" <? if($reqKpStatusSuratTandaLulus == "-1") echo "selected";?>></option>
													<option value="" <? if($reqKpStatusSuratTandaLulus == "") echo "selected";?>>Tidak</option>
													<option value="1" <? if($reqKpStatusSuratTandaLulus == "1") echo "selected";?>>Ya</option>
												</select>
												<label for="reqKpStatusSuratTandaLulus">Ada STLUD / Ujian Dinas?</label>
											</div>
										</div>
										<div class="reqKpSuratTandaLulusIdInfo">
											<div class="row">
												<div class="input-field col s12 m12">
													Jika ada data STL Ujian Dinas yang akan dimasukkan belum ada di formulir usulan, silakan update di riwayat Surat Tanda Lulus PNS yang bersangkutan. Silakan <a href="javascript:void();" style="text-decoration: none;" onclick="reloadpelayananriwayat('pegawai_add_surat_tanda_lulus_monitoring', 'surat_masuk_upt_add_pegawai_kenaikan_pangkat')">klik disini.</a>
												</div>
											</div>
											<div class="row">
												<div class="input-field col s12 m3">
													<select name="reqKpSuratTandaLulusId" id="reqKpSuratTandaLulusId" >
														<option value="" <? if($reqKpSuratTandaLulusId == "") echo "selected";?>></option>
													</select>
													<label for="reqKpSuratTandaLulusId">Jenis STLUD Terakhir</label>
												</div>
												<div class="input-field col s12 m3">
													<label for="reqKpSuratTandaLulusTanggal">Tanggal STL</label>
													<input placeholder type="text" id="reqKpSuratTandaLulusTanggal" value="<?=$reqKpSuratTandaLulusTanggal?>" disabled />
												</div>
											</div>
										</div>
										<div class="row">
											<div class="input-field col s12 m12">
												Jika ada data Pendidikan yang akan dimasukkan belum ada di formulir usulan, silakan update di riwayat Pendidikan PNS yang bersangkutan. Silakan <a href="javascript:void();" style="text-decoration: none;" onclick="reloadpelayananriwayat('pegawai_add_pendidikan_monitoring', 'surat_masuk_upt_add_pegawai_kenaikan_pangkat')">klik disini.</a>
											</div>
										</div>
										<div class="row">
											<div class="input-field col s12 m3">
												<select name="reqKpStatusPendidikanRiwayatBelumDiakui" id="reqKpStatusPendidikanRiwayatBelumDiakui" >
													<option value="-1" <? if($reqKpStatusPendidikanRiwayatBelumDiakui == "") echo "selected";?>></option>
													<option value="" <? if($reqKpStatusPendidikanRiwayatBelumDiakui == "") echo "selected";?>>Tidak</option>
													<option value="1" <? if($reqKpStatusPendidikanRiwayatBelumDiakui == "1") echo "selected";?>>Ya</option>
												</select>
												<label for="reqKpStatusPendidikanRiwayatBelumDiakui">Tambah Pendidikan Baru?</label>
											</div>
											<div class="reqKpPendidikanRiwayatBelumDiakuiIdInfo">
												<div class="input-field col s12 m3">
													<select name="reqKpStatusPendidikanRiwayatIjinTugas" id="reqKpStatusPendidikanRiwayatIjinTugas" >
														<option value="" <? if($reqKpStatusPendidikanRiwayatIjinTugas == "") echo "selected";?>></option>
														<option value="1" <? if($reqKpStatusPendidikanRiwayatIjinTugas == "1") echo "selected";?>>Ijin belajar / tugas belajar mandiri</option>
														<option value="2" <? if($reqKpStatusPendidikanRiwayatIjinTugas == "2") echo "selected";?>>Tugas Belajar</option>
													</select>
													<label for="reqKpStatusPendidikanRiwayatIjinTugas">Surat Ijin Belajar/ Tugas Belajar?</label>
												</div>
												<div class="input-field col s12 m2">
													<select name="reqKpStatusPendidikanRiwayatCantumGelar" id="reqKpStatusPendidikanRiwayatCantumGelar" >
														<option value="" <? if($reqKpStatusPendidikanRiwayatCantumGelar == "") echo "selected";?>></option>
														<option value="1" <? if($reqKpStatusPendidikanRiwayatCantumGelar == "1") echo "selected";?>>Ya</option>
														<option value="2" <? if($reqKpStatusPendidikanRiwayatCantumGelar == "2") echo "selected";?>>Tidak</option>
													</select>
													<label for="reqKpStatusPendidikanRiwayatCantumGelar">Sudah Pencantuman Gelar?</label>
												</div>
												<div class="input-field col s12 m4">
													<select name="reqKpPendidikanRiwayatBelumDiakuiId" id="reqKpPendidikanRiwayatBelumDiakuiId" >
														<option value="" <? if($reqKpPendidikanRiwayatBelumDiakuiId == "") echo "selected";?>></option>
													</select>
													<label id="reqKpPendidikanRiwayatBelumDiakuiLabel" for="reqKpPendidikanRiwayatBelumDiakuiId"></label>
												</div>
											</div>
										</div>

										<!-- <div class="row">
											<div class="reqKpPegawaiFileIdInfo">
												<div class="input-field col s12 m8">
													<select name="reqKpPegawaiFileId" id="reqKpPegawaiFileId" >
														<option value="" <? if($reqKpPegawaiFileId == "") echo "selected";?>></option>
													</select>
													<label for="reqKpPegawaiFileId">File Uraian Tugas</label>
												</div>
											</div>
										</div> -->
										<?
										}
										else
										{
										?>
										<div class="row">
											<div class="input-field col s12 m3">
												<input placeholder type="text" value="<?=$reqKpStatusSuratTandaLulusNama?>" disabled />
												<label for="reqKpStatusSuratTandaLulusNama">Ada STLUD / Ujian Dinas?</label>
											</div>
										</div>

										<div class="reqKpSuratTandaLulusIdInfo">
											<div class="row">
												<div class="input-field col s12 m12">
													Jika ada data STL Ujian Dinas yang akan dimasukkan belum ada di formulir usulan, silakan update di riwayat Surat Tanda Lulus PNS yang bersangkutan. Silakan <a href="javascript:void();" style="text-decoration: none;" onclick="reloadpelayananriwayat('pegawai_add_surat_tanda_lulus_monitoring', 'surat_masuk_upt_add_pegawai_kenaikan_pangkat')">klik disini.</a>
												</div>
											</div>
											<div class="row">
												<div class="input-field col s12 m3">
													<input placeholder type="text" value="<?=$reqKpSuratTandaLulusIdNama?>" disabled />
													<label for="reqKpSuratTandaLulusIdNama">Jenis STLUD Terakhir</label>
												</div>
												<div class="input-field col s12 m3">
													<label for="reqKpSuratTandaLulusTanggal">Tanggal STL</label>
													<input placeholder type="text" id="reqKpSuratTandaLulusTanggal" value="<?=$reqKpSuratTandaLulusTanggal?>" disabled />
												</div>
											</div>
										</div>
										<div class="row">
											<div class="input-field col s12 m12">
												Jika ada data Pendidikan yang akan dimasukkan belum ada di formulir usulan, silakan update di riwayat Pendidikan PNS yang bersangkutan. Silakan <a href="javascript:void();" style="text-decoration: none;" onclick="reloadpelayananriwayat('pegawai_add_pendidikan_monitoring', 'surat_masuk_upt_add_pegawai_kenaikan_pangkat')">klik disini.</a>
											</div>
										</div>
										<div class="row">
											<div class="input-field col s12 m3">
												<input placeholder type="text" value="<?=$reqKpStatusPendidikanRiwayatBelumDiakuiNama?>" disabled />
												<label for="reqKpStatusPendidikanRiwayatBelumDiakuiNama">Tambah Pendidikan Baru?</label>
											</div>
											<div class="reqKpPendidikanRiwayatBelumDiakuiIdInfo">
												<div class="input-field col s12 m3">
													<input placeholder type="text" value="<?=$reqKpStatusPendidikanRiwayatIjinTugasNama?>" disabled />
													<label for="reqKpStatusPendidikanRiwayatIjinTugas">Surat Ijin Belajar/ Tugas Belajar?</label>
												</div>
												<div class="input-field col s12 m2">
													<input placeholder type="text" value="<?=$reqKpStatusPendidikanRiwayatCantumGelarNama?>" disabled />
													<label for="reqKpStatusPendidikanRiwayatCantumGelar">Sudah Pencantuman Gelar?</label>
												</div>
												<div class="input-field col s12 m4">
													<input placeholder type="text" value="<?=$reqKpPendidikanRiwayatBelumDiakuiNama?>" disabled />
													<label for="reqKpPendidikanRiwayatBelumDiakuiNama"><?=$reqKpPendidikanRiwayatBelumDiakuiLabel?></label>
												</div>
											</div>
										</div>

										<!-- <div class="row">
											<div class="reqKpPegawaiFileIdInfo">
												<div class="input-field col s12 m8">
													<input placeholder type="text" value="<?=$reqKpPegawaiFileIdNama?>" disabled />
													<label for="reqKpPegawaiFileIdNama">File Uraian Tugas</label>
												</div>
											</div>
										</div> -->
										<?
										}
										?>

									<?
									}
									elseif($reqKpJenis == "kpstruktural")
									{
										if($reqRowId == "")
										{
										?>
										<div class="reqKpStatusStrukturalInfo">
											<div class="row">
												<div class="reqKpDiklatIdInfo">
													<input type="hidden" name="reqKpDiklatId" id="reqKpDiklatId" value="<?=$reqKpDiklatId?>" />
													<div class="row">
														<div class="input-field col s12 m12">
															Jika ada data Diklat Pim yang akan dimasukkan belum ada di formulir usulan, silakan update di riwayat Diklat Struktural PNS yang bersangkutan. Silakan <a href="javascript:void();" style="text-decoration: none;" onclick="reloadpelayananriwayat('pegawai_add_diklat_struktural_monitoring', 'surat_masuk_upt_add_pegawai_kenaikan_pangkat')">klik disini.</a>
														</div>
													</div>
													<div class="row">
														<div class="input-field col s12 m3">
															<select name="reqKpDiklatStrukturalId" id="reqKpDiklatStrukturalId" >
																<option value="" <? if($reqKpDiklatStrukturalId == "") echo "selected";?>></option>
															</select>
															<label for="reqKpDiklatStrukturalId">Jenjang Diklatpim Terakhir</label>
														</div>
														<div class="input-field col s12 m3">
															<label for="reqKpDiklatStrukturalTanggal">Tanggal Sertifikat</label>
															<input placeholder type="text" id="reqKpDiklatStrukturalTanggal" value="<?=$reqKpDiklatStrukturalTanggal?>" disabled />
														</div>
													</div>
												</div>

												<div class="reqKpSuratTandaLulusIdInfo">
													<input type="hidden" name="reqKpStatusSuratTandaLulus" id="reqKpStatusSuratTandaLulus" value="<?=$reqKpStatusSuratTandaLulus?>" />
													<div class="row">
														<div class="input-field col s12 m12">
															Jika ada data STL Ujian Dinas yang akan dimasukkan belum ada di formulir usulan, silakan update di riwayat Surat Tanda Lulus PNS yang bersangkutan. Silakan <a href="javascript:void();" style="text-decoration: none;" onclick="reloadpelayananriwayat('pegawai_add_surat_tanda_lulus_monitoring', 'surat_masuk_upt_add_pegawai_kenaikan_pangkat')">klik disini.</a>
														</div>
													</div>
													<div class="row">
														<div class="input-field col s12 m3">
															<select name="reqKpSuratTandaLulusId" id="reqKpSuratTandaLulusId" >
																<option value="" <? if($reqKpSuratTandaLulusId == "") echo "selected";?>></option>
															</select>
															<label for="reqKpSuratTandaLulusId">Jenis STLUD Terakhir</label>
														</div>
														<div class="input-field col s12 m3">
															<label for="reqKpSuratTandaLulusTanggal">Tanggal STL</label>
															<input placeholder type="text" id="reqKpSuratTandaLulusTanggal" value="<?=$reqKpSuratTandaLulusTanggal?>" disabled />
														</div>
													</div>
												</div>

											</div>
										</div>

										<div class="row">
											<div class="input-field col s12 m12">
												Jika ada data Pendidikan yang akan dimasukkan belum ada di formulir usulan, silakan update di riwayat Pendidikan PNS yang bersangkutan. Silakan <a href="javascript:void();" style="text-decoration: none;" onclick="reloadpelayananriwayat('pegawai_add_pendidikan_monitoring', 'surat_masuk_upt_add_pegawai_kenaikan_pangkat')">klik disini.</a>
											</div>
										</div>
										<div class="row">
											<div class="input-field col s12 m3">
												<select name="reqKpStatusPendidikanRiwayatBelumDiakui" id="reqKpStatusPendidikanRiwayatBelumDiakui" >
													<option value="-1" <? if($reqKpStatusPendidikanRiwayatBelumDiakui == "") echo "selected";?>></option>
													<option value="" <? if($reqKpStatusPendidikanRiwayatBelumDiakui == "") echo "selected";?>>Tidak</option>
													<option value="1" <? if($reqKpStatusPendidikanRiwayatBelumDiakui == "1") echo "selected";?>>Ya</option>
												</select>
												<label for="reqKpStatusPendidikanRiwayatBelumDiakui">Tambah Pendidikan Baru?</label>
											</div>
											<div class="reqKpPendidikanRiwayatBelumDiakuiIdInfo">
												<div class="input-field col s12 m3">
													<select name="reqKpStatusPendidikanRiwayatIjinTugas" id="reqKpStatusPendidikanRiwayatIjinTugas" >
														<option value="" <? if($reqKpStatusPendidikanRiwayatIjinTugas == "") echo "selected";?>></option>
														<option value="1" <? if($reqKpStatusPendidikanRiwayatIjinTugas == "1") echo "selected";?>>Ijin belajar / tugas belajar mandiri</option>
														<option value="2" <? if($reqKpStatusPendidikanRiwayatIjinTugas == "2") echo "selected";?>>Tugas Belajar</option>
													</select>
													<label for="reqKpStatusPendidikanRiwayatIjinTugas">Surat Ijin Belajar/ Tugas Belajar?</label>
												</div>
												<div class="input-field col s12 m2">
													<select name="reqKpStatusPendidikanRiwayatCantumGelar" id="reqKpStatusPendidikanRiwayatCantumGelar" >
														<option value="" <? if($reqKpStatusPendidikanRiwayatCantumGelar == "") echo "selected";?>></option>
														<option value="1" <? if($reqKpStatusPendidikanRiwayatCantumGelar == "1") echo "selected";?>>Ya</option>
														<option value="2" <? if($reqKpStatusPendidikanRiwayatCantumGelar == "2") echo "selected";?>>Tidak</option>
													</select>
													<label for="reqKpStatusPendidikanRiwayatCantumGelar">Sudah Pencantuman Gelar?</label>
												</div>
												<div class="input-field col s12 m4">
													<select name="reqKpPendidikanRiwayatBelumDiakuiId" id="reqKpPendidikanRiwayatBelumDiakuiId" >
														<option value="" <? if($reqKpPendidikanRiwayatBelumDiakuiId == "") echo "selected";?>></option>
													</select>
													<label id="reqKpPendidikanRiwayatBelumDiakuiLabel" for="reqKpPendidikanRiwayatBelumDiakuiId"></label>
												</div>
											</div>
										</div>

										<!-- <div class="row">
											<div class="reqKpPegawaiFileIdInfo">
												<div class="input-field col s12 m8">
													<select name="reqKpPegawaiFileId" id="reqKpPegawaiFileId" >
														<option value="" <? if($reqKpPegawaiFileId == "") echo "selected";?>></option>
													</select>
													<label for="reqKpPegawaiFileId">File Uraian Tugas</label>
												</div>
											</div>
										</div> -->
										<?
										}
										else
										{
										?>

										<?
										if($reqKpPangkatId == "41")
										{
											if($reqKpStatusStrukturalId == "1")
											{
											?>
											<div class="row">
												<div class="input-field col s12 m12">
													Jika ada data STL Ujian Dinas yang akan dimasukkan belum ada di formulir usulan, silakan update di riwayat Surat Tanda Lulus PNS yang bersangkutan. Silakan <a href="javascript:void();" style="text-decoration: none;" onclick="reloadpelayananriwayat('pegawai_add_surat_tanda_lulus_monitoring', 'surat_masuk_upt_add_pegawai_kenaikan_pangkat')">klik disini.</a>
												</div>
											</div>
											<div class="row">
												<div class="input-field col s12 m3">
													<input placeholder type="text" value="<?=$reqKpSuratTandaLulusIdNama?>" disabled />
													<label for="reqKpSuratTandaLulusIdNama">Jenis STLUD Terakhir</label>
												</div>
												<div class="input-field col s12 m3">
													<label for="reqKpSuratTandaLulusTanggal">Tanggal STL</label>
													<input placeholder type="text" id="reqKpSuratTandaLulusTanggal" value="<?=$reqKpSuratTandaLulusTanggal?>" disabled />
												</div>
											</div>
											<?
											}
											elseif($reqKpStatusStrukturalId == "2")
											{
											?>
											<div class="row">
												<div class="input-field col s12 m12">
													Jika ada data Diklat Pim yang akan dimasukkan belum ada di formulir usulan, silakan update di riwayat Diklat Struktural PNS yang bersangkutan. Silakan <a href="javascript:void();" style="text-decoration: none;" onclick="reloadpelayananriwayat('pegawai_add_diklat_struktural_monitoring', 'surat_masuk_upt_add_pegawai_kenaikan_pangkat')">klik disini.</a>
												</div>
											</div>
											<div class="row">
												<div class="input-field col s12 m3">
													<input placeholder type="text" value="<?=$reqKpDiklatStrukturalNama?>" disabled />
													<label for="reqKpDiklatStrukturalNama">Jenjang Diklatpim Terakhir</label>
												</div>
												<div class="input-field col s12 m3">
													<label for="reqKpDiklatStrukturalTanggal">Tanggal Sertifikat</label>
													<input placeholder type="text" id="reqKpDiklatStrukturalTanggal" value="<?=$reqKpDiklatStrukturalTanggal?>" disabled />
												</div>
											</div>
											<?
											}
										}
										?>

										<div class="row">
											<div class="input-field col s12 m12">
												Jika ada data Pendidikan yang akan dimasukkan belum ada di formulir usulan, silakan update di riwayat Pendidikan PNS yang bersangkutan. Silakan <a href="javascript:void();" style="text-decoration: none;" onclick="reloadpelayananriwayat('pegawai_add_pendidikan_monitoring', 'surat_masuk_upt_add_pegawai_kenaikan_pangkat')">klik disini.</a>
											</div>
										</div>
										<div class="row">
											<div class="input-field col s12 m3">
												<input placeholder type="text" value="<?=$reqKpStatusPendidikanRiwayatBelumDiakuiNama?>" disabled />
												<label for="reqKpStatusPendidikanRiwayatBelumDiakuiNama">Tambah Pendidikan Baru?</label>
											</div>
											<div class="reqKpPendidikanRiwayatBelumDiakuiIdInfo">
												<div class="input-field col s12 m3">
													<input placeholder type="text" value="<?=$reqKpStatusPendidikanRiwayatIjinTugasNama?>" disabled />
													<label for="reqKpStatusPendidikanRiwayatIjinTugas">Surat Ijin Belajar/ Tugas Belajar?</label>
												</div>
												<div class="input-field col s12 m2">
													<input placeholder type="text" value="<?=$reqKpStatusPendidikanRiwayatCantumGelarNama?>" disabled />
													<label for="reqKpStatusPendidikanRiwayatCantumGelar">Sudah Pencantuman Gelar?</label>
												</div>
												<div class="input-field col s12 m4">
													<input placeholder type="text" value="<?=$reqKpPendidikanRiwayatBelumDiakuiNama?>" disabled />
													<label for="reqKpPendidikanRiwayatBelumDiakuiNama"><?=$reqKpPendidikanRiwayatBelumDiakuiLabel?></label>
												</div>
											</div>
										</div>

										<!-- <div class="row">
											<div class="reqKpPegawaiFileIdInfo">
												<div class="input-field col s12 m8">
													<input placeholder type="text" value="<?=$reqKpPegawaiFileIdNama?>" disabled />
													<label for="reqKpPegawaiFileIdNama">File Uraian Tugas</label>
												</div>
											</div>
										</div> -->
										<?
										}
										?>

									<?
									}
									elseif($reqKpJenis == "kpjft")
									{
										if($reqRowId == "")
										{
										?>
										<div class="row">
											<div class="input-field col s12 m12">
												Jika ada data Penilaian Angka Kredit belum ada di formulir usulan, silakan update di riwayat Surat Tanda Lulus PNS yang bersangkutan. Silakan <a href="javascript:void();" style="text-decoration: none;" onclick="reloadpelayananriwayat('pegawai_add_pak_monitoring', 'surat_masuk_upt_add_pegawai_kenaikan_pangkat')">klik disini.</a>
											</div>
										</div>
										<div class="row">
											<div class="input-field col s12 m3">
												<select name="reqKpPakPertamaStatusId" id="reqKpPakPertamaStatusId" >
													<option value="-1" <? if($reqKpPakPertamaStatusId == "-1") echo "selected";?>></option>
													<option value="" <? if($reqKpPakPertamaStatusId == "") echo "selected";?>>Tidak</option>
													<option value="1" <? if($reqKpPakPertamaStatusId == "1") echo "selected";?>>Ya</option>
												</select>
												<label for="reqKpPakPertamaStatusId">PAK Pertama Kali?</label>
											</div>
										</div>

										<div class="row reqKpPakPertamaStatusInfo1">
											<div class="input-field col s12 m5">
												<select name="reqKpPakLamaId" id="reqKpPakLamaId" >
													<option value="" <? if($reqKpPakLamaId == "") echo "selected";?>></option>
												</select>
												<label for="reqKpPakLamaId">PAK Lama (sebelum PAK Terbaru) No SK</label>
											</div>
											<div class="input-field col s12 m3">
												<label for="reqKpPakLamaTanggal">Tanggal Mulai</label>
												<input placeholder type="text" id="reqKpPakLamaTanggal" value="<?=$reqKpPakLamaTanggal?>" disabled />
											</div>
											<div class="input-field col s12 m3">
												<label for="reqKpPakLamaNilai">Nilai Total</label>
												<input placeholder type="text" id="reqKpPakLamaNilai" value="<?=$reqKpPakLamaNilai?>" disabled />
											</div>
										</div>
										<div class="row reqKpPakPertamaStatusInfo2">
											<div class="input-field col s12 m5">
												<select name="reqKpPakBaruId" id="reqKpPakBaruId" >
													<option value="" <? if($reqKpPakBaruId == "") echo "selected";?>></option>
												</select>
												<label for="reqKpPakBaruId">PAK Terbaru No SK</label>
											</div>
											<div class="input-field col s12 m3">
												<label for="reqKpPakBaruTanggal">Tanggal Mulai</label>
												<input placeholder type="text" id="reqKpPakBaruTanggal" value="<?=$reqKpPakBaruTanggal?>" disabled />
											</div>
											<div class="input-field col s12 m3">
												<label for="reqKpPakBaruNilai">Nilai Total</label>
												<input placeholder type="text" id="reqKpPakBaruNilai" value="<?=$reqKpPakBaruNilai?>" disabled />
											</div>
										</div>
										<div class="row">
											<!-- <div class="reqKpPegawaiFileSertifikatKeaslianIdInfo">
												<div class="input-field col s12 m8">
													<select name="reqKpPegawaiFileSertifikatKeaslianId" id="reqKpPegawaiFileSertifikatKeaslianId" >
														<option value="" <? if($reqKpPegawaiFileSertifikatKeaslianId == "") echo "selected";?>></option>
													</select>
													<label for="reqKpPegawaiFileSertifikatKeaslianId">Pilih File Sertifikat Keaslian</label>
												</div>
											</div> -->
										</div>
										<div class="row">
											<!-- <div class="reqKpPegawaiFileSertifikatPendidikIdInfo">
												<div class="input-field col s12 m8">
													<select name="reqKpPegawaiFileSertifikatPendidikId" id="reqKpPegawaiFileSertifikatPendidikId" >
														<option value="" <? if($reqKpPegawaiFileSertifikatPendidikId == "") echo "selected";?>></option>
													</select>
													<label for="reqKpPegawaiFileSertifikatPendidikId">Pilih File Sertifikat Pendidik</label>
												</div>
											</div> -->
										</div>
										<div class="row">
											<div class="input-field col s12 m12">
												Jika ada data Pendidikan yang akan dimasukkan belum ada di formulir usulan, silakan update di riwayat Pendidikan PNS yang bersangkutan. Silakan <a href="javascript:void();" style="text-decoration: none;" onclick="reloadpelayananriwayat('pegawai_add_pendidikan_monitoring', 'surat_masuk_upt_add_pegawai_kenaikan_pangkat')">klik disini.</a>
											</div>
										</div>
										<div class="row">
											<div class="input-field col s12 m3">
												<select name="reqKpStatusPendidikanRiwayatBelumDiakui" id="reqKpStatusPendidikanRiwayatBelumDiakui" >
													<option value="-1" <? if($reqKpStatusPendidikanRiwayatBelumDiakui == "") echo "selected";?>></option>
													<option value="" <? if($reqKpStatusPendidikanRiwayatBelumDiakui == "") echo "selected";?>>Tidak</option>
													<option value="1" <? if($reqKpStatusPendidikanRiwayatBelumDiakui == "1") echo "selected";?>>Ya</option>
												</select>
												<label for="reqKpStatusPendidikanRiwayatBelumDiakui">Tambah Pendidikan Baru?</label>
											</div>
											<div class="reqKpPendidikanRiwayatBelumDiakuiIdInfo">
												<div class="input-field col s12 m3">
													<select name="reqKpStatusPendidikanRiwayatIjinTugas" id="reqKpStatusPendidikanRiwayatIjinTugas" >
														<option value="" <? if($reqKpStatusPendidikanRiwayatIjinTugas == "") echo "selected";?>></option>
														<option value="1" <? if($reqKpStatusPendidikanRiwayatIjinTugas == "1") echo "selected";?>>Ijin belajar / tugas belajar mandiri</option>
														<option value="2" <? if($reqKpStatusPendidikanRiwayatIjinTugas == "2") echo "selected";?>>Tugas Belajar</option>
													</select>
													<label for="reqKpStatusPendidikanRiwayatIjinTugas">Surat Ijin Belajar/ Tugas Belajar?</label>
												</div>
												<div class="input-field col s12 m2">
													<select name="reqKpStatusPendidikanRiwayatCantumGelar" id="reqKpStatusPendidikanRiwayatCantumGelar" >
														<option value="" <? if($reqKpStatusPendidikanRiwayatCantumGelar == "") echo "selected";?>></option>
														<option value="1" <? if($reqKpStatusPendidikanRiwayatCantumGelar == "1") echo "selected";?>>Ya</option>
														<option value="2" <? if($reqKpStatusPendidikanRiwayatCantumGelar == "2") echo "selected";?>>Tidak</option>
													</select>
													<label for="reqKpStatusPendidikanRiwayatCantumGelar">Sudah Pencantuman Gelar?</label>
												</div>
												<div class="input-field col s12 m4">
													<select name="reqKpPendidikanRiwayatBelumDiakuiId" id="reqKpPendidikanRiwayatBelumDiakuiId" >
														<option value="" <? if($reqKpPendidikanRiwayatBelumDiakuiId == "") echo "selected";?>></option>
													</select>
													<label id="reqKpPendidikanRiwayatBelumDiakuiLabel" for="reqKpPendidikanRiwayatBelumDiakuiId"></label>
												</div>
											</div>
										</div>
										<!-- <div class="row">
											<div class="reqKpPegawaiFileIdInfo">
												<div class="input-field col s12 m8">
													<select name="reqKpPegawaiFileId" id="reqKpPegawaiFileId" >
														<option value="" <? if($reqKpPegawaiFileId == "") echo "selected";?>></option>
													</select>
													<label for="reqKpPegawaiFileId">File Uraian Tugas</label>
												</div>
											</div>
										</div> -->
										<?
										}
										else
										{
										?>
										<div class="row">
											<div class="input-field col s12 m12">
												Jika ada data Penilaian Angka Kredit belum ada di formulir usulan, silakan update di riwayat Surat Tanda Lulus PNS yang bersangkutan. Silakan <a href="javascript:void();" style="text-decoration: none;" onclick="reloadpelayananriwayat('pegawai_add_pak_monitoring', 'surat_masuk_upt_add_pegawai_kenaikan_pangkat')">klik disini.</a>
											</div>
										</div>
										<div class="row">
											<div class="input-field col s12 m3">
												<label for="reqKpPakPertamaStatusNama">PAK Pertama Kali?</label>
												<input type="text" value="<?=$reqKpPakPertamaStatusNama?>" disabled />
											</div>
										</div>
										<div class="row reqKpPakPertamaStatusInfo1">
											<div class="input-field col s12 m5">
												<input placeholder type="text" value="<?=$reqKpPakLamaNama?>" disabled />
												<label for="reqKpPakLamaNama">PAK Lama (sebelum PAK Terbaru) No SK</label>
											</div>
											<div class="input-field col s12 m3">
												<label for="reqKpPakLamaTanggal">Tanggal Mulai</label>
												<input placeholder type="text" id="reqKpPakLamaTanggal" value="<?=$reqKpPakLamaTanggal?>" disabled />
											</div>
											<div class="input-field col s12 m3">
												<label for="reqKpPakLamaNilai">Nilai Total</label>
												<input placeholder type="text" id="reqKpPakLamaNilai" value="<?=$reqKpPakLamaNilai?>" disabled />
											</div>
										</div>
										<div class="row reqKpPakPertamaStatusInfo2">
											<div class="input-field col s12 m5">
												<input placeholder type="text" value="<?=$reqKpPakBaruNama?>" disabled />
												<label for="reqKpPakBaruNama">PAK Terbaru No SK</label>
											</div>
											<div class="input-field col s12 m3">
												<label for="reqKpPakBaruTanggal">Tanggal Mulai</label>
												<input placeholder type="text" id="reqKpPakBaruTanggal" value="<?=$reqKpPakBaruTanggal?>" disabled />
											</div>
											<div class="input-field col s12 m3">
												<label for="reqKpPakBaruNilai">Nilai Total</label>
												<input placeholder type="text" id="reqKpPakBaruNilai" value="<?=$reqKpPakBaruNilai?>" disabled />
											</div>
										</div>
										<div class="row">
											<!-- <div class="reqKpPegawaiFileSertifikatKeaslianIdInfo">
												<div class="input-field col s12 m8">
													<input placeholder type="text" value="<?=$reqKpPegawaiFileSertifikatKeaslianIdNama?>" disabled />
													<label for="reqKpPegawaiFileSertifikatKeaslianIdNama">Pilih File Sertifikat Keaslian</label>
												</div>
											</div> -->
										</div>

										<div class="row">
											<!-- <div class="reqKpPegawaiFileSertifikatPendidikIdInfo">
												<div class="input-field col s12 m8">
													<input placeholder type="text" value="<?=$reqKpPegawaiFileSertifikatPendidikIdNama?>" disabled />
													<label for="reqKpPegawaiFileSertifikatPendidikIdNama">Pilih File Sertifikat Pendidik</label>
												</div>
											</div> -->
										</div>
										<div class="row">
											<div class="input-field col s12 m12">
												Jika ada data Pendidikan yang akan dimasukkan belum ada di formulir usulan, silakan update di riwayat Pendidikan PNS yang bersangkutan. Silakan <a href="javascript:void();" style="text-decoration: none;" onclick="reloadpelayananriwayat('pegawai_add_pendidikan_monitoring', 'surat_masuk_upt_add_pegawai_kenaikan_pangkat')">klik disini.</a>
											</div>
										</div>
										<div class="row">
											<div class="input-field col s12 m3">
												<input placeholder type="text" value="<?=$reqKpStatusPendidikanRiwayatBelumDiakuiNama?>" disabled />
												<label for="reqKpStatusPendidikanRiwayatBelumDiakuiNama">Tambah Pendidikan Baru?</label>
											</div>
											<div class="reqKpPendidikanRiwayatBelumDiakuiIdInfo">
												<div class="input-field col s12 m3">
													<input placeholder type="text" value="<?=$reqKpStatusPendidikanRiwayatIjinTugasNama?>" disabled />
													<label for="reqKpStatusPendidikanRiwayatIjinTugas">Surat Ijin Belajar/ Tugas Belajar?</label>
												</div>
												<div class="input-field col s12 m2">
													<input placeholder type="text" value="<?=$reqKpStatusPendidikanRiwayatCantumGelarNama?>" disabled />
													<label for="reqKpStatusPendidikanRiwayatCantumGelar">Sudah Pencantuman Gelar?</label>
												</div>
												<div class="input-field col s12 m4">
													<input placeholder type="text" value="<?=$reqKpPendidikanRiwayatBelumDiakuiNama?>" disabled />
													<label for="reqKpPendidikanRiwayatBelumDiakuiNama"><?=$reqKpPendidikanRiwayatBelumDiakuiLabel?></label>
												</div>
											</div>
										</div>
										<!-- <div class="row">
											<div class="reqKpPegawaiFileIdInfo">
												<div class="input-field col s12 m8">
													<input placeholder type="text" value="<?=$reqKpPegawaiFileIdNama?>" disabled />
													<label for="reqKpPegawaiFileIdNama">File Uraian Tugas</label>
												</div>
											</div>
										</div> -->
										<?
										}
										?>

									<?
									}
									elseif($reqKpJenis == "kppi")
									{
										if($reqRowId == "")
										{
										?>
										<div class="row">
											<div>
												<div class="input-field col s12 m8">
													<select name="reqKpSuratTandaLulusId" id="reqKpSuratTandaLulusId" >
														<option value="" <? if($reqKpSuratTandaLulusId == "") echo "selected";?>></option>
													</select>
													<label for="reqKpSuratTandaLulusId">Pilih STLUD/PI</label>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="input-field col s12 m12">
												Jika ada data Pendidikan yang akan dimasukkan belum ada di formulir usulan, silakan update di riwayat Pendidikan PNS yang bersangkutan. Silakan <a href="javascript:void();" style="text-decoration: none;" onclick="reloadpelayananriwayat('pegawai_add_pendidikan_monitoring', 'surat_masuk_upt_add_pegawai_kenaikan_pangkat')">klik disini.</a>
											</div>
										</div>
										<div class="row">
											<div class="input-field col s12 m3">
												<select name="reqKpStatusPendidikanRiwayatIjinTugas" id="reqKpStatusPendidikanRiwayatIjinTugas" >
													<option value="" <? if($reqKpStatusPendidikanRiwayatIjinTugas == "") echo "selected";?>></option>
													<option value="1" <? if($reqKpStatusPendidikanRiwayatIjinTugas == "1") echo "selected";?>>Ijin belajar / tugas belajar mandiri</option>
													<option value="2" <? if($reqKpStatusPendidikanRiwayatIjinTugas == "2") echo "selected";?>>Tugas Belajar</option>
												</select>
												<label for="reqKpStatusPendidikanRiwayatIjinTugas">Surat Ijin Belajar/ Tugas Belajar?</label>
											</div>
											<div class="input-field col s12 m2">
												<select name="reqKpStatusPendidikanRiwayatCantumGelar" id="reqKpStatusPendidikanRiwayatCantumGelar" >
													<option value="" <? if($reqKpStatusPendidikanRiwayatCantumGelar == "") echo "selected";?>></option>
													<option value="1" <? if($reqKpStatusPendidikanRiwayatCantumGelar == "1") echo "selected";?>>Ya</option>
													<option value="2" <? if($reqKpStatusPendidikanRiwayatCantumGelar == "2") echo "selected";?>>Tidak</option>
												</select>
												<label for="reqKpStatusPendidikanRiwayatCantumGelar">Sudah Pencantuman Gelar?</label>
											</div>
											<div class="input-field col s12 m4">
												<select name="reqKpPendidikanRiwayatBelumDiakuiId" id="reqKpPendidikanRiwayatBelumDiakuiId" >
													<option value="" <? if($reqKpPendidikanRiwayatBelumDiakuiId == "") echo "selected";?>></option>
												</select>
												<label id="reqKpPendidikanRiwayatBelumDiakuiLabel" for="reqKpPendidikanRiwayatBelumDiakuiId"></label>
											</div>
										</div>
										<div class="row">
											<!-- <div>
												<div class="input-field col s12 m8">
													<select name="reqKpPegawaiFileId" id="reqKpPegawaiFileId" >
														<option value="" <? if($reqKpPegawaiFileId == "") echo "selected";?>></option>
													</select>
													<label for="reqKpPegawaiFileId">File Uraian Tugas</label>
												</div>
											</div> -->
										</div>
										<?
										}
										else
										{
										?>
										<div class="row">
											<div>
												<div class="input-field col s12 m8">
													<input placeholder type="text" value="<?=$reqKpSuratTandaLulusIdNama?>" disabled />
													<label for="reqKpSuratTandaLulusIdNama">Pilih STLUD/PI</label>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="input-field col s12 m12">
												Jika ada data Pendidikan yang akan dimasukkan belum ada di formulir usulan, silakan update di riwayat Pendidikan PNS yang bersangkutan. Silakan <a href="javascript:void();" style="text-decoration: none;" onclick="reloadpelayananriwayat('pegawai_add_pendidikan_monitoring', 'surat_masuk_upt_add_pegawai_kenaikan_pangkat')">klik disini.</a>
											</div>
										</div>
										<div class="row">
											<div class="input-field col s12 m3">
												<input placeholder type="text" value="<?=$reqKpStatusPendidikanRiwayatBelumDiakuiNama?>" disabled />
												<label for="reqKpStatusPendidikanRiwayatBelumDiakuiNama">Tambah Pendidikan Baru?</label>
											</div>
											<div class="reqKpPendidikanRiwayatBelumDiakuiIdInfo">
												<div class="input-field col s12 m3">
													<input placeholder type="text" value="<?=$reqKpStatusPendidikanRiwayatIjinTugasNama?>" disabled />
													<label for="reqKpStatusPendidikanRiwayatIjinTugas">Surat Ijin Belajar/ Tugas Belajar?</label>
												</div>
												<div class="input-field col s12 m2">
													<input placeholder type="text" value="<?=$reqKpStatusPendidikanRiwayatCantumGelarNama?>" disabled />
													<label for="reqKpStatusPendidikanRiwayatCantumGelar">Sudah Pencantuman Gelar?</label>
												</div>
												<div class="input-field col s12 m4">
													<input placeholder type="text" value="<?=$reqKpPendidikanRiwayatBelumDiakuiNama?>" disabled />
													<label for="reqKpPendidikanRiwayatBelumDiakuiNama"><?=$reqKpPendidikanRiwayatBelumDiakuiLabel?></label>
												</div>
											</div>
										</div>
										<!-- <div class="row">
											<div class="reqKpPegawaiFileIdInfo">
												<div class="input-field col s12 m8">
													<input placeholder type="text" value="<?=$reqKpPegawaiFileIdNama?>" disabled />
													<label for="reqKpPegawaiFileIdNama">File Uraian Tugas</label>
												</div>
											</div>
										</div> -->
										<?
										}
										?>
									<?
									}
									elseif($reqKpJenis == "kpstugas")
									{
										if($reqRowId == "")
										{
										?>
										<div class="row">
											<div class="input-field col s12 m12">
												Jika ada data Pendidikan yang akan dimasukkan belum ada di formulir usulan, silakan update di riwayat Pendidikan PNS yang bersangkutan. Silakan <a href="javascript:void();" style="text-decoration: none;" onclick="reloadpelayananriwayat('pegawai_add_pendidikan_monitoring', 'surat_masuk_upt_add_pegawai_kenaikan_pangkat')">klik disini.</a>
											</div>
										</div>
										<div class="row">
											<div class="input-field col s12 m3">
												<select name="reqKpStatusPendidikanRiwayatIjinTugas" id="reqKpStatusPendidikanRiwayatIjinTugas" >
													<option value="" <? if($reqKpStatusPendidikanRiwayatIjinTugas == "") echo "selected";?>></option>
													<option value="1" <? if($reqKpStatusPendidikanRiwayatIjinTugas == "1") echo "selected";?>>Ijin belajar / tugas belajar mandiri</option>
													<option value="2" <? if($reqKpStatusPendidikanRiwayatIjinTugas == "2") echo "selected";?>>Tugas Belajar</option>
												</select>
												<label for="reqKpStatusPendidikanRiwayatIjinTugas">Surat Ijin Belajar/ Tugas Belajar?</label>
											</div>
											<div class="input-field col s12 m2">
												<select name="reqKpStatusPendidikanRiwayatCantumGelar" id="reqKpStatusPendidikanRiwayatCantumGelar" >
													<option value="" <? if($reqKpStatusPendidikanRiwayatCantumGelar == "") echo "selected";?>></option>
													<option value="1" <? if($reqKpStatusPendidikanRiwayatCantumGelar == "1") echo "selected";?>>Ya</option>
													<option value="2" <? if($reqKpStatusPendidikanRiwayatCantumGelar == "2") echo "selected";?>>Tidak</option>
												</select>
												<label for="reqKpStatusPendidikanRiwayatCantumGelar">Sudah Pencantuman Gelar?</label>
											</div>
											<div class="input-field col s12 m4">
												<select name="reqKpPendidikanRiwayatBelumDiakuiId" id="reqKpPendidikanRiwayatBelumDiakuiId" >
													<option value="" <? if($reqKpPendidikanRiwayatBelumDiakuiId == "") echo "selected";?>></option>
												</select>
												<label id="reqKpPendidikanRiwayatBelumDiakuiLabel" for="reqKpPendidikanRiwayatBelumDiakuiId"></label>
											</div>
										</div>
										<div class="row">
											<!-- <div>
												<div class="input-field col s12 m8">
													<select name="reqKpPegawaiFileId" id="reqKpPegawaiFileId" >
														<option value="" <? if($reqKpPegawaiFileId == "") echo "selected";?>></option>
													</select>
													<label for="reqKpPegawaiFileId">File Uraian Tugas</label>
												</div>
											</div> -->
										</div>
										<?
										}
										else
										{
										?>
										<div class="row">
											<div class="input-field col s12 m12">
												Jika ada data Pendidikan yang akan dimasukkan belum ada di formulir usulan, silakan update di riwayat Pendidikan PNS yang bersangkutan. Silakan <a href="javascript:void();" style="text-decoration: none;" onclick="reloadpelayananriwayat('pegawai_add_pendidikan_monitoring', 'surat_masuk_upt_add_pegawai_kenaikan_pangkat')">klik disini.</a>
											</div>
										</div>
										<div class="row">
											<div class="input-field col s12 m3">
												<input placeholder type="text" value="<?=$reqKpStatusPendidikanRiwayatBelumDiakuiNama?>" disabled />
												<label for="reqKpStatusPendidikanRiwayatBelumDiakuiNama">Tambah Pendidikan Baru?</label>
											</div>
											<div class="reqKpPendidikanRiwayatBelumDiakuiIdInfo">
												<div class="input-field col s12 m3">
													<input placeholder type="text" value="<?=$reqKpStatusPendidikanRiwayatIjinTugasNama?>" disabled />
													<label for="reqKpStatusPendidikanRiwayatIjinTugas">Surat Ijin Belajar/ Tugas Belajar?</label>
												</div>
												<div class="input-field col s12 m2">
													<input placeholder type="text" value="<?=$reqKpStatusPendidikanRiwayatCantumGelarNama?>" disabled />
													<label for="reqKpStatusPendidikanRiwayatCantumGelar">Sudah Pencantuman Gelar?</label>
												</div>
												<div class="input-field col s12 m4">
													<input placeholder type="text" value="<?=$reqKpPendidikanRiwayatBelumDiakuiNama?>" disabled />
													<label for="reqKpPendidikanRiwayatBelumDiakuiNama"><?=$reqKpPendidikanRiwayatBelumDiakuiLabel?></label>
												</div>
											</div>
										</div>
										<!-- <div class="row">
											<div class="reqKpPegawaiFileIdInfo">
												<div class="input-field col s12 m8">
													<input placeholder type="text" value="<?=$reqKpPegawaiFileIdNama?>" disabled />
													<label for="reqKpPegawaiFileIdNama">File Uraian Tugas</label>
												</div>
											</div>
										</div> -->
										<?
										}
										?>
									<?
									}
									?>

								</div>

								<div id="labeldetiltambahaninfo">
									<?
									if(!empty($reqRowId))
									{
									?>
										<div class="row">
											<div class="input-field col s12 m12">
												Jika ada data penilaian skp/ppk belum ada di formulir usulan, silakan update di riwayat penilaian skp/ppk PNS yang bersangkutan. Silakan <a href="javascript:void();" style="text-decoration: none;" onclick="reloadpelayananriwayat('pegawai_add_skp_monitoring', 'surat_masuk_upt_add_pegawai_pensiun')">klik disini.</a>
											</div>
										</div>

										<?
										$vfpeg= new globalfilepegawai();
										$infotahunmundur= $vfpeg->gettahunmundur($reqTahunSurat);

										$reqPenilaianSkpJumlah= 0;
										$statement= " AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2') AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.TAHUN IN (".$infotahunmundur.")";
										$set= new SuratMasukPegawaiCheck();
										$set->selectByParamsPenilaianSkp(array(),-1,-1, $statement, "ORDER BY A.TAHUN DESC");
										// echo $set->query;exit;
										while($set->nextRow())
										{
											$reqPenilaianSkpTahun= $set->getField("TAHUN");
											$reqPenilaianSkpHasil= $set->getField("PRESTASI_HASIL");
											$reqPenilaianSkpJumlah++;
										?>
										<div class="row">
											<div class="input-field col s12 m3">
												<label>Tahun</label>
												<input placeholder="" type="text" value="<?=$reqPenilaianSkpTahun?>" disabled />
											</div>
											<div class="input-field col s12 m9">
												<label>Penilaian SKP</label>
												<input placeholder="" type="text" value="<?=$reqPenilaianSkpHasil?>" disabled />
											</div>
										</div>
										<?
										}
										?>
										<input type="hidden" name="reqPenilaianSkpJumlah" id="reqPenilaianSkpJumlah" value="<?=$reqPenilaianSkpJumlah?>" />
									<?
									}
									?>
								</div>

								<div class="row">
									<div class="input-field col s12 m12">
										<label for="reqKeteranganPensiun">Keterangan</label>
										<?
										if($reqRowId == "")
										{
										?>
										<input placeholder type="text" name="reqKeteranganPensiun" id="reqKeteranganPensiun" value="<?=$reqKeteranganPensiun?>" />
										<?
										}
										else
										{
										?>
										<input placeholder readonly type="text" class="color-disb" name="reqKeteranganPensiun" id="reqKeteranganPensiun" value="<?=$reqKeteranganPensiun?>" />
										<?
										}
										?>
									</div>
								</div>

								<div class="row">
									<div class="input-field col s12">
										<button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
											<i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
										</button>

										<?
										if($reqStatusKirim == "1"){}
										else
										{
										?>
											<input type="hidden" name="reqSatuanKerjaPegawaiUsulanId" id="reqSatuanKerjaPegawaiUsulanId" value="<?=$reqSatuanKerjaPegawaiUsulanId?>" />
											<input type="hidden" name="reqJabatanTambahanAkhirId" id="reqJabatanTambahanAkhirId" value="<?=$reqJabatanTambahanAkhirId?>" />
											<input type="hidden" name="reqJabatanRiwayatAkhirId" id="reqJabatanRiwayatAkhirId" value="<?=$reqJabatanRiwayatAkhirId?>" />
											<input type="hidden" name="reqPendidikanRiwayatAkhirId" id="reqPendidikanRiwayatAkhirId" value="<?=$reqPendidikanRiwayatAkhirId?>" />
											<input type="hidden" name="reqGajiRiwayatAkhirId" id="reqGajiRiwayatAkhirId" value="<?=$reqGajiRiwayatAkhirId?>" />
											<input type="hidden" name="reqPangkatRiwayatAkhirId" id="reqPangkatRiwayatAkhirId" value="<?=$reqPangkatRiwayatAkhirId?>" />

											<input type="hidden" name="reqJenis" id="reqJenis" value="<?=$reqJenis?>" />
											<input type="hidden" name="reqPegawaiId" id="reqPegawaiId" value="<?=$reqPegawaiId?>" />
											<input type="hidden" name="reqRowId" id="reqRowId" value="<?=$reqRowId?>" />
											<input type="hidden" name="reqRowDetilId" value="<?=$reqRowDetilId?>" />
											<input type="hidden" name="reqKategori" value="<?=$reqKategori?>" />

											<input type="hidden" name="reqId" id="reqId" value="<?=$reqId?>" />
											<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
											<?
											if($reqRowId == "")
											{
											?>
												<button type="submit" style="display:none" id="reqSubmit"></button>
												<button class="btn waves-effect waves-light green" style="font-size:9pt" type="button" id="reqsimpan">Simpan
													<i class="mdi-content-save left hide-on-small-only"></i>
												</button>
											<?
											}
										}
										?>

										<?
										if($reqRowId == ""){}
										else
										{
											if($reqStatusKirim == "1"){}
											else
											{
										?>
												<button class="btn purple waves-effect waves-light" style="font-size:9pt" type="button" id="reqselanjutnya">Selanjutnya
													<i class="mdi-content-inbox left hide-on-small-only"></i>
												</button>

												<button class="btn pink waves-effect waves-light" style="font-size:9pt" type="button" id="reqhapus">Hapus
													<i class="mdi-content-inbox left hide-on-small-only"></i>
												</button>

												<button class="btn blue waves-effect waves-light" style="font-size:9pt" type="button" id="tambah">Tambah Lainnya
													<i class="mdi-content-add left hide-on-small-only"></i>
												</button>
										<?
											}
										}
										?>

									</div>
								</div>


							</form>
						</div>
					</li>
				</ul>
			</div>
		</div>

	</div>

<div class="preloader-wrapper big active loader">
  <div class="spinner-layer spinner-blue-only">
    <div class="circle-clipper left">
      <div class="circle"></div>
    </div><div class="gap-patch">
      <div class="circle"></div>
    </div><div class="circle-clipper right">
      <div class="circle"></div>
    </div>
  </div>
</div>

<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

<style type="text/css">
  .select-dropdown {
    max-height:250px !important; overflow:auto !important;
  }
</style>

<script type="text/javascript">
$(document).ready(function() {
	$('select').material_select();

	$("#reqhapus").click(function() { 

	  var s_url= "surat/surat_masuk_pegawai_json/cek_kirim_upt?reqId=<?=$reqId?>";
      $.ajax({'url': s_url,'success': function(dataajax){
        var requrl= requrllist= "";
        dataajax= String(dataajax);
        if(dataajax == '1')
        {
          mbox.alert('Data sudah dikirim', {open_speed: 0});
          return false;
        }
        else
        {
        	mbox.custom({
				message: "Apakah Anda Yakin, Hapus data terpilih ?",
				options: {close_speed: 100},
				buttons: [
				{
					label: 'Ya',
					color: 'green darken-2',
					callback: function() {
						$.getJSON("surat/surat_masuk_pegawai_json/delete_pegawai/?reqId=<?=$reqRowId?>",
							function(data){
								mbox.alert(data.PESAN, {open_speed: 500}, interval = window.setInterval(function() 
								{
									clearInterval(interval);
									document.location.href= "app/loadUrl/persuratan/surat_masuk_upt_add_pegawai_kenaikan_pangkat/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>";
								}, 1000));
								$(".mbox > .right-align").css({"display": "none"});
							});
						mbox.close();
					}
				},
				{
					label: 'Tidak',
					color: 'grey darken-2',
					callback: function() {
						mbox.close();
					}
				}
				]
			});
        }
      }});

	});

	$("#reqselanjutnya").click(function() { 
    	document.location.href= "app/loadUrl/persuratan/surat_masuk_upt_add_pegawai_lookup_verfikasi_kenaikan_pangkat/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqRowId=<?=$reqRowId?>";
    });

	$("#tambah").click(function() { 
		document.location.href= "app/loadUrl/persuratan/surat_masuk_upt_add_pegawai_kenaikan_pangkat/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>";
	});

	$("#kembali").click(function() { 
		document.location.href = "app/loadUrl/persuratan/surat_masuk_upt_add_pegawai?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>";
	});

	$("#reqcetakrekomendasi").click(function() { 
		newWindow = window.open("app/loadUrl/persuratan/cetak_pdf?reqCss=surat_rekomendasi&reqUrl=<?=$reqJenisSuratRekomendasi?>&reqId=<?=$reqRowId?>", 'Cetak');
		newWindow.focus();
	});

});

$('.materialize-textarea').trigger('autoresize');

</script>

<script type="text/javascript" src="lib/easyui/pelayanan-kembali.js"></script>
<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>