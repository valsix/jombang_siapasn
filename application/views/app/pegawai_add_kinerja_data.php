<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('PenilaianSkpDetil');
$this->load->model('Pegawai');
$this->load->model('KualitasFile');
$this->load->library('globalfilepegawai');
$this->load->model('Pangkat');
$this->load->model('NilaiKuadran');
$this->load->model('CapaianOrganisasi');


$pangkat= new Pangkat();
$pangkat->selectByParams(array());

$pangkat2= new Pangkat();
$pangkat2->selectByParams(array());

$pangkat3= new Pangkat();
$pangkat3->selectByParams(array());

$pangkat4= new Pangkat();
$pangkat4->selectByParams(array());

$pelayananid= $this->input->get("pelayananid");
$pelayananjenis= $this->input->get("pelayananjenis");
$pelayananrowid= $this->input->get("pelayananrowid");
$pelayanankembali= $this->input->get("pelayanankembali");

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqParamTahun = $this->input->get('reqParamTahun');


$penilaian_skp_detil = new PenilaianSkpDetil();
$arrDataTahun = array();

$arrDataTriwulanUnix =  array();
$tahun = date('Y');
$nextTahun = $tahun +1;
$arrDataTahun = array($tahun);
array_push($arrDataTahun,date('Y')-1);

$statement= " AND A.PEGAWAI_ID = ".$reqId;
$penilaian_skp_detil->selectByParams(array(), -1, -1, $statement);
$arrDataField = $penilaian_skp_detil->rowResult;
$arrDataField = array_change_key_case_recursive_upper($arrDataField);
foreach($arrDataField as $value){
  array_push($arrDataTahun,$value['TAHUN']);
  
}
$arrDataTahun=array_unique($arrDataTahun);
rsort($arrDataTahun);

$set = new PenilaianSkpDetil();

$nilai_kuadran  = new NilaiKuadran();
$nilai_kuadran->selectByParams(array());
$arrNilaiKuadran = $nilai_kuadran->rowResult;
$arrNilaiKuadran = array_change_key_case_recursive_upper($arrNilaiKuadran);

$capaian_organisasi  = new CapaianOrganisasi();
$capaian_organisasi->selectByParams(array());
$arrCapaianOrganisasi = $capaian_organisasi->rowResult;
$arrCapaianOrganisasi = array_change_key_case_recursive_upper($arrCapaianOrganisasi);

 $setTahun= new PenilaianSkpDetil();

$pegawai_dinilai = new Pegawai();
$pegawai_penilai = new Pegawai();
$pegawai_atasan  = new Pegawai();

$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "010701";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);


if($reqRowId=="" || $reqRowId=="-1" )
{
  $reqRowId='';
  $reqMode = 'insert';
}
else
{
  $reqMode = 'update';
}

if(empty($reqRowId))
  $reqRowId= -1;

$statement= " AND A.PENILAIAN_SKP_DETIL_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
$set->selectByParams(array(), -1, -1, $statement);
$set->firstRow();
#echo $set->query;exit;

$reqTahun =$set->getField('TAHUN');

//Semester 1
$reqPegawaiJenisJabatan = $set->getField('JENIS_JABATAN_DINILAI');
$reqPegawaiUnorNama = $set->getField('PEGAWAI_UNOR_NAMA');
$reqPegawaiUnorId = $set->getField('PEGAWAI_UNOR_ID');

$reqPenilaianSkpPenilaiId = $set->getField("PEGAWAI_PEJABAT_PENILAI_ID");
$reqPenilaianSkpPenilaiNama = $set->getField('PEGAWAI_PEJABAT_PENILAI_NAMA');
$reqPenilaianSkpPenilaiNip = $set->getField('PEGAWAI_PEJABAT_PENILAI_NIP');
$reqPenilaianSkpPenilaiJabatanNama = $set->getField('PEGAWAI_PEJABAT_PENILAI_JABATAN_NAMA');
$reqPenilaianSkpPenilaiUnorNama = $set->getField('PEGAWAI_PEJABAT_PENILAI_UNOR_NAMA');
$reqPenilaianSkpPenilaiPangkatId = $set->getField('PEGAWAI_PEJABAT_PENILAI_PANGKAT_ID');

$reqPenilaianSkpAtasanId = $set->getField("PEGAWAI_ATASAN_PEJABAT_ID");
$reqPenilaianSkpAtasanNama = $set->getField('PEGAWAI_ATASAN_PEJABAT_NAMA');
$reqPenilaianSkpAtasanNip = $set->getField('PEGAWAI_ATASAN_PEJABAT_NIP');
$reqPenilaianSkpAtasanJabatanNama = $set->getField('PEGAWAI_ATASAN_PEJABAT_JABATAN_NAMA');
$reqPenilaianSkpAtasanUnorNama = $set->getField('PEGAWAI_ATASAN_PEJABAT_UNOR_NAMA');
$reqPenilaianSkpAtasanPangkatId = $set->getField('PEGAWAI_ATASAN_PEJABAT_PANGKAT_ID');

//Semester 2
$reqPegawaiJenisJabatan2 = $set->getField('JENIS_JABATAN_DINILAI2');
$reqPegawaiUnorNama2 = $set->getField('PEGAWAI_UNOR_NAMA2');
$reqPegawaiUnorId2 = $set->getField('PEGAWAI_UNOR_ID2');

$reqPenilaianSkpPenilaiId2 = $set->getField("PEGAWAI_PEJABAT_PENILAI_ID2");
$reqPenilaianSkpPenilaiNama2 = $set->getField('PEGAWAI_PEJABAT_PENILAI_NAMA2');
$reqPenilaianSkpPenilaiNip2 = $set->getField('PEGAWAI_PEJABAT_PENILAI_NIP2');
$reqPenilaianSkpPenilaiJabatanNama2 = $set->getField('PEGAWAI_PEJABAT_PENILAI_JABATAN_NAMA2');
$reqPenilaianSkpPenilaiUnorNama2 = $set->getField('PEGAWAI_PEJABAT_PENILAI_UNOR_NAMA2');
$reqPenilaianSkpPenilaiPangkatId2 = $set->getField('PEGAWAI_PEJABAT_PENILAI_PANGKAT_ID2');

$reqPenilaianSkpAtasanId2 = $set->getField("PEGAWAI_ATASAN_PEJABAT_ID2");
$reqPenilaianSkpAtasanNama2 = $set->getField('PEGAWAI_ATASAN_PEJABAT_NAMA2');
$reqPenilaianSkpAtasanNip2 = $set->getField('PEGAWAI_ATASAN_PEJABAT_NIP2');
$reqPenilaianSkpAtasanJabatanNama2 = $set->getField('PEGAWAI_ATASAN_PEJABAT_JABATAN_NAMA2');
$reqPenilaianSkpAtasanUnorNama2 = $set->getField('PEGAWAI_ATASAN_PEJABAT_UNOR_NAMA2');
$reqPenilaianSkpAtasanPangkatId2 = $set->getField('PEGAWAI_ATASAN_PEJABAT_PANGKAT_ID2');

if($reqPenilaianSkpPenilaiId == "")
{
  // $reqPejabatPenilaiIsManual= "1";
}
if($reqPenilaianSkpPenilaiId2 == "")
{
  // $reqPejabatPenilaiIsManual2= "1";
}
// echo $reqPenilaianSkpPenilaiId."--".$reqPenilaianSkpAtasanId;exit();
if($reqPenilaianSkpAtasanId == "")
{
  // $reqAtasanPejabatPenilaiIsManual= "1";
}
if($reqPenilaianSkpAtasanId2 == "")
{
  // $reqAtasanPejabatPenilaiIsManual2= "1";
}

//semester I
$reqSkpNilai = dotToComma($set->getField('SKP_NILAI'));
$reqSkpHasil = dotToComma($set->getField('SKP_HASIL'));
$reqOrientasiNilai = dotToComma($set->getField('ORIENTASI_NILAI')); 
$reqIntegritasNilai = dotToComma($set->getField('INTEGRITAS_NILAI'));
$reqKomitmenNilai = dotToComma($set->getField('KOMITMEN_NILAI')); 
$reqDisiplinNilai = dotToComma($set->getField('DISIPLIN_NILAI')); 
$reqKerjasamaNilai = dotToComma($set->getField('KERJASAMA_NILAI')); 
$reqKepemimpinanNilai = dotToComma($set->getField('KEPEMIMPINAN_NILAI')); 
$reqJumlahNilai= dotToComma(setLebihNol($set->getField('JUMLAH_NILAI'))); 
$reqRataNilai= dotToComma(setLebihNol($set->getField('RATA_NILAI'))); 
$reqPerilakuNilai= dotToComma(setLebihNol($set->getField('PERILAKU_NILAI'))); 
$reqPerilakuHasil= dotToComma(setLebihNol($set->getField('PERILAKU_HASIL')));
$reqPrestasiNilai = $set->getField('PRESTASI_NILAI'); 
$reqPrestasiHasil= dotToComma(setLebihNol($set->getField('PRESTASI_HASIL'))); 

//semester II
$reqSkpNilai2 = dotToComma($set->getField('SKP_NILAI2'));
$reqSkpHasil2 = dotToComma($set->getField('SKP_HASIL2'));
$reqOrientasiNilai2 = dotToComma($set->getField('ORIENTASI_NILAI2')); 
$reqKomitmenNilai2 = dotToComma($set->getField('KOMITMEN_NILAI2')); 
$reqKerjasamaNilai2 = dotToComma($set->getField('KERJASAMA_NILAI2')); 
$reqKepemimpinanNilai2 = dotToComma($set->getField('KEPEMIMPINAN_NILAI2'));
$reqInisiatifkerjaNilai2 = dotToComma($set->getField('INISIATIFKERJA_NILAI2'));
$reqJumlahNilai2= dotToComma(setLebihNol($set->getField('JUMLAH_NILAI2'))); 
$reqRataNilai2= dotToComma(setLebihNol($set->getField('RATA_NILAI2'))); 

//Nilai SKP Tahun > 2022

$reqNilaiHasilKerja= $set->getField('NILAI_HASIL_KERJA'); 
$reqNilaiPerilakuKerja= $set->getField('NILAI_HASIL_PERILAKU'); 
$reqTriwulan= $set->getField('TRIWULAN'); 
$reqKuadratPredikatKinerja= $set->getField('NILAI_QUADRAN'); 
$reqCapaianOrganisasi= $set->getField('NILAI_CAPAIAN_ORGANISASI'); 

$reqKeberatan = $set->getField('KEBERATAN');
$reqTanggalKeberatan = dateToPageCheck($set->getField('KEBERATAN_TANGGAL')); 
$reqTanggapan = $set->getField('TANGGAPAN'); 
$reqTanggalTanggapan = dateToPageCheck($set->getField('TANGGAPAN_TANGGAL')); 
$reqKeputusan = $set->getField('KEPUTUSAN'); 
$reqTanggalKeputusan = dateToPageCheck($set->getField('KEPUTUSAN_TANGGAL'));
$reqRekomendasi = $set->getField('REKOMENDASI'); 


$pegawai_dinilai->selectByParams(array("A.PEGAWAI_ID" => $reqId)); 
$pegawai_dinilai->firstRow();
//echo $pegawai_dinilai->query;exit;

$reqPenilaianSkpDinilaiNama = $pegawai_dinilai->getField('NAMA_LENGKAP');
$reqPenilaianSkpDinilaiNip = $pegawai_dinilai->getField('NIP_BARU');
$reqSatkerAtasan = $pegawai_dinilai->getField('SATKER_ID_ATASAN');

// untuk kondisi file
$vfpeg= new globalfilepegawai();
$arrpilihfiledokumen= $vfpeg->pilihfiledokumen();
// print_r($arrpilihfiledokumen);exit;

$riwayattable= "PENILAIAN_SKP";
$reqDokumenKategoriFileId= "7"; // ambil dari table KATEGORI_FILE, cek sesuai mode
$arrsetriwayatfield= $vfpeg->setriwayatfield($riwayattable);
// print_r($arrsetriwayatfield);exit;

if(empty($reqRowId))
  $arrlistriwayatfilepegawai= $vfpeg->listpilihfilepegawai($reqId, $riwayattable, "baru");
else
  $arrlistriwayatfilepegawai= $vfpeg->listpilihfilepegawai($reqId, $riwayattable, $reqRowId);

$arrlistpilihfile= $arrlistriwayatfilepegawai["pilihfile"];
// print_r($arrlistpilihfile);exit;
$arrlistriwayat= $arrlistriwayatfilepegawai["riwayat"];

// $keymode= $riwayattable.";".$reqRowId.";foto";

$arrlistpilihfilefield= [];
$reqDokumenPilih= [];
foreach ($arrsetriwayatfield as $key => $value)
{
  $keymode= $value["riwayatfield"];
  $arrlistpilihfilefield[$keymode]= [];

  if(!empty($arrlistpilihfile))
  {
    $arrlistpilihfilefield[$keymode]= $vfpeg->ambilfilemode($arrlistpilihfile, $keymode);

    $reqDokumenPilih[$keymode]= "";
    $infocari= "selected";
    $arraycari= in_array_column($infocari, "selected", $arrlistpilihfilefield[$keymode]);
    // print_r($arraycari);exit;
    if(!empty($arraycari))
    {
      // print_r($arraycari);exit;
      $reqDokumenPilih[$keymode]= 2;
    }
  }
}
// print_r($reqDokumenPilih);exit;
// print_r($arrlistpilihfilefield);exit;

$set= new KualitasFile();
$set->selectByParams(array());
// echo $set->query;exit;
$arrkualitasfile=[];
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["ID"]= $set->getField("KUALITAS_FILE_ID");
  $arrdata["TEXT"]= $set->getField("NAMA");
  array_push($arrkualitasfile, $arrdata);
}

    $reqTahun =$reqTahun?$reqTahun:$tahun;
 // print_r($arrDataTriwulanForm);exit;
    $paramTahun = $reqParamTahun?$reqParamTahun:$reqTahun;
    $keyTriwulan = $reqTriwulan;
    if($keyTriwulan == $reqTahun ){
      $keyTriwulan='';
    }

    $set = new PenilaianSkpDetil();
    $arrDataTriwulan = array();
    $arrDataTriwulanForm = array("1"=>'I',"2"=>'II',"3"=>'III',"4"=>'IV',"99"=>"FINAL");
    $statement= " AND A.PEGAWAI_ID = ".$reqId." AND A.TAHUN=".$paramTahun;
    $statementLevel= " AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')";
    $set->selectByParams(array(), -1, -1, $statement.$statementLevel);
  
    $arrDataField = $set->rowResult;
    $arrDataField = array_change_key_case_recursive_upper($arrDataField);
    foreach($arrDataField as $value){
      array_push($arrDataTriwulan,$value['TRIWULAN']);
    }
    foreach($arrDataTriwulan as $value){
      $val = $arrDataTriwulanForm[$value];

      if(!empty($val)){
        if( $value == $keyTriwulan){}
          else{ 
            unset($arrDataTriwulanForm[$value]);
        }
      }
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

function setpejabatpenilaicetang(vinfo)
{
  if(vinfo == "1")
  {
    $("#reqPenilaianSkpPenilaiId,#reqPenilaianSkpPenilaiNip,#reqPenilaianSkpPenilaiNama").val("");
  }

  if($("#reqPejabatPenilaiIsManual").prop('checked')) 
  {
    $("#reqPenilaianSkpPenilaiNip").attr("readonly", true);
    $("#reqPenilaianSkpPenilaiNip").addClass('color-disb');
    $('#reqPenilaianSkpPenilaiNip').validatebox({required: false});
    $('#reqPenilaianSkpPenilaiNip').removeClass('validatebox-invalid');

    $("#reqPenilaianSkpPenilaiNama").attr("readonly", false);
    $("#reqPenilaianSkpPenilaiNama").removeClass('color-disb');
    $('#reqPenilaianSkpPenilaiNama').validatebox({required: true});
  }
  else
  { 
    $("#reqPenilaianSkpPenilaiNama").attr("readonly", true);
    $("#reqPenilaianSkpPenilaiNama").addClass('color-disb');
    $('#reqPenilaianSkpPenilaiNama').validatebox({required: false});
    $('#reqPenilaianSkpPenilaiNama').removeClass('validatebox-invalid');

    $("#reqPenilaianSkpPenilaiNip").attr("readonly", false);
    $("#reqPenilaianSkpPenilaiNip").removeClass('color-disb');
    $('#reqPenilaianSkpPenilaiNip').validatebox({required: true});
  }
}

function setpejabatpenilaicetang2(vinfo)
{
  if(vinfo == "1")
  {
    $("#reqPenilaianSkpPenilaiId2,#reqPenilaianSkpPenilaiNip2,#reqPenilaianSkpPenilaiNama2").val("");
  }

  if($("#reqPejabatPenilaiIsManual2").prop('checked')) 
  {
    $("#reqPenilaianSkpPenilaiNip2").attr("readonly", true);
    $("#reqPenilaianSkpPenilaiNip2").addClass('color-disb');
    $('#reqPenilaianSkpPenilaiNip2').validatebox({required: false});
    $('#reqPenilaianSkpPenilaiNip2').removeClass('validatebox-invalid');

    $("#reqPenilaianSkpPenilaiNama2").attr("readonly", false);
    $("#reqPenilaianSkpPenilaiNama2").removeClass('color-disb');
    $('#reqPenilaianSkpPenilaiNama2').validatebox({required: true});
  }
  else
  { 
    $("#reqPenilaianSkpPenilaiNama2").attr("readonly", true);
    $("#reqPenilaianSkpPenilaiNama2").addClass('color-disb');
    $('#reqPenilaianSkpPenilaiNama2').validatebox({required: false});
    $('#reqPenilaianSkpPenilaiNama2').removeClass('validatebox-invalid');

    $("#reqPenilaianSkpPenilaiNip2").attr("readonly", false);
    $("#reqPenilaianSkpPenilaiNip2").removeClass('color-disb');
    $('#reqPenilaianSkpPenilaiNip2').validatebox({required: true});
  }
}

function setatasanpejabatpenilaicetang(vinfo)
{
  if(vinfo == "1")
  {
    $("#reqPenilaianSkpAtasanId,#reqPenilaianSkpAtasanNip,#reqPenilaianSkpAtasanNama").val("");
  }

  if($("#reqAtasanPejabatPenilaiIsManual").prop('checked')) 
  {
    $("#reqPenilaianSkpAtasanNip").attr("readonly", true);
    $("#reqPenilaianSkpAtasanNip").addClass('color-disb');
    $('#reqPenilaianSkpAtasanNip').validatebox({required: false});
    $('#reqPenilaianSkpAtasanNip').removeClass('validatebox-invalid');

    $("#reqPenilaianSkpAtasanNama").attr("readonly", false);
    $("#reqPenilaianSkpAtasanNama").removeClass('color-disb');
    $('#reqPenilaianSkpAtasanNama').validatebox({required: true});
  }
  else
  { 
    $("#reqPenilaianSkpAtasanNama").attr("readonly", true);
    $("#reqPenilaianSkpAtasanNama").addClass('color-disb');
    $('#reqPenilaianSkpAtasanNama').validatebox({required: false});
    $('#reqPenilaianSkpAtasanNama').removeClass('validatebox-invalid');

    $("#reqPenilaianSkpAtasanNip").attr("readonly", false);
    $("#reqPenilaianSkpAtasanNip").removeClass('color-disb');
    $('#reqPenilaianSkpAtasanNip').validatebox({required: true});
  }
}

function setatasanpejabatpenilaicetang2(vinfo)
{
  if(vinfo == "1")
  {
    $("#reqPenilaianSkpAtasanId2,#reqPenilaianSkpAtasanNip2,#reqPenilaianSkpAtasanNama2").val("");
  }
  
  if($("#reqAtasanPejabatPenilaiIsManual2").prop('checked')) 
  {
    $("#reqPenilaianSkpAtasanNip2").attr("readonly", true);
    $("#reqPenilaianSkpAtasanNip2").addClass('color-disb');
    $('#reqPenilaianSkpAtasanNip2').validatebox({required: false});
    $('#reqPenilaianSkpAtasanNip2').removeClass('validatebox-invalid');

    $("#reqPenilaianSkpAtasanNama2").attr("readonly", false);
    $("#reqPenilaianSkpAtasanNama2").removeClass('color-disb');
    $('#reqPenilaianSkpAtasanNama2').validatebox({required: true});
  }
  else
  { 
    $("#reqPenilaianSkpAtasanNama2").attr("readonly", true);
    $("#reqPenilaianSkpAtasanNama2").addClass('color-disb');
    $('#reqPenilaianSkpAtasanNama2').validatebox({required: false});
    $('#reqPenilaianSkpAtasanNama2').removeClass('validatebox-invalid');

    $("#reqPenilaianSkpAtasanNip2").attr("readonly", false);
    $("#reqPenilaianSkpAtasanNip2").removeClass('color-disb');
    $('#reqPenilaianSkpAtasanNip2').validatebox({required: true});
  }
}


$(function(){
  $.extend($.fn.validatebox.defaults.rules, {  
    nilaiKurangSamaDengan: {  
      //alert('asdsad');
      validator: function(value, param){  
        return parseInt(value) <= parseInt(param[0]);
      },
      message: 'Nilai harus <= {0}.'
    }  
  });

  <?
  if($reqMode == 'insert')
  {
  ?>
  <?
  }
  else
  {
    if($reqPenilaianSkpPenilaiId == "")
    {
    ?>
      $("#reqPenilaianSkpPenilaiNip").attr("readonly", true);
      $("#reqPenilaianSkpPenilaiNip").addClass('color-disb');
      $('#reqPenilaianSkpPenilaiNip').validatebox({required: false});
      $('#reqPenilaianSkpPenilaiNip').removeClass('validatebox-invalid');

      $("#reqPenilaianSkpPenilaiNama").attr("readonly", false);
      $("#reqPenilaianSkpPenilaiNama").removeClass('color-disb');
      $('#reqPenilaianSkpPenilaiNama').validatebox({required: true});
    <?
    }
    ?>

    <?
    if($reqPenilaianSkpPenilaiId2 == "")
    {
    ?>
      $("#reqPenilaianSkpPenilaiNip2").attr("readonly", true);
      $("#reqPenilaianSkpPenilaiNip2").addClass('color-disb');
      $('#reqPenilaianSkpPenilaiNip2').validatebox({required: false});
      $('#reqPenilaianSkpPenilaiNip2').removeClass('validatebox-invalid');

      $("#reqPenilaianSkpPenilaiNama2").attr("readonly", false);
      $("#reqPenilaianSkpPenilaiNama2").removeClass('color-disb');
      $('#reqPenilaianSkpPenilaiNama2').validatebox({required: true});
    <?
    }
    ?>

    <?
    if($reqPenilaianSkpAtasanId == "")
    {
    ?>
      $("#reqPenilaianSkpAtasanNip").attr("readonly", true);
      $("#reqPenilaianSkpAtasanNip").addClass('color-disb');
      $('#reqPenilaianSkpAtasanNip').validatebox({required: false});
      $('#reqPenilaianSkpAtasanNip').removeClass('validatebox-invalid');

      $("#reqPenilaianSkpAtasanNama").attr("readonly", false);
      $("#reqPenilaianSkpAtasanNama").removeClass('color-disb');
      $('#reqPenilaianSkpAtasanNama').validatebox({required: true});
    <?
    }


    if($reqPenilaianSkpAtasanId2 == "")
    {
    ?>
      $("#reqPenilaianSkpAtasanNip2").attr("readonly", true);
      $("#reqPenilaianSkpAtasanNip2").addClass('color-disb');
      $('#reqPenilaianSkpAtasanNip2').validatebox({required: false});
      $('#reqPenilaianSkpAtasanNip2').removeClass('validatebox-invalid');

      $("#reqPenilaianSkpAtasanNama2").attr("readonly", false);
      $("#reqPenilaianSkpAtasanNama2").removeClass('color-disb');
      $('#reqPenilaianSkpAtasanNama2').validatebox({required: true});
    <?
    }
  }
  ?>

  setpejabatpenilaicetang("");
  $("#reqPejabatPenilaiIsManual").click(function () {
    setpejabatpenilaicetang("1");
  });

  setatasanpejabatpenilaicetang("");
  $("#reqAtasanPejabatPenilaiIsManual").click(function () {
    setatasanpejabatpenilaicetang("1");
  });

  setpejabatpenilaicetang2("");
  $("#reqPejabatPenilaiIsManual2").click(function () {
    setpejabatpenilaicetang2("1");
  });

  setatasanpejabatpenilaicetang2("");
  $("#reqAtasanPejabatPenilaiIsManual2").click(function () {
    setatasanpejabatpenilaicetang2("1");
  });

  $(".preloader-wrapper").hide();

  $('#ff').form({
    url:'penilaian_kinerja_json/add',
    onSubmit:function(){
      if($(this).form('validate')){}
      else
      {
        $.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
        return false;
      }
    },
    success:function(data){
      //console.log(data);return false;
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
           <?
           if(!empty($pelayananid))
           {
           ?>
           vkembali= "app/loadUrl/app/pegawai_add_kinerja_monitoring?reqId=<?=$reqId?>&reqRowId="+rowid+"&pelayananid=<?=$pelayananid?>&pelayananjenis=<?=$pelayananjenis?>&pelayananrowid=<?=$pelayananrowid?>&pelayanankembali=<?=$pelayanankembali?>";
           <?
           }
           else
           {
           ?>
           vkembali= "app/loadUrl/app/pegawai_add_kinerja_monitoring?reqId=<?=$reqId?>";
           <?
           }
           ?>
           document.location.href= vkembali;
        }, 1000));
        $(".mbox > .right-align").css({"display": "none"});
      }
    }
  });

  $('#reqPenilaianSkpPenilaiJabatanNama, #reqPenilaianSkpAtasanJabatanNama, #reqPenilaianSkpPenilaiJabatanNama2, #reqPenilaianSkpAtasanJabatanNama2').each(function(){
  $(this).autocomplete({
    source:function(request, response){
      $(".preloader-wrapper").show();
      var id= this.element.attr('id');
      var replaceAnakId= replaceAnak= urlAjax= "";

      // if (id.indexOf('reqPenilaianSkpPenilaiJabatanNama') !== -1)
      if (id == 'reqPenilaianSkpPenilaiJabatanNama')
      {
        var element= id.split('reqPenilaianSkpPenilaiJabatanNama');
        var indexId= "reqPenilaianSkpPenilaiJabatanNama"+element[1];
        urlAjax= "skp_combo_json/namajabatan";
      }
      else if (id == 'reqPenilaianSkpAtasanJabatanNama')
      {
        var element= id.split('reqPenilaianSkpAtasanJabatanNama');
        var indexId= "reqPenilaianSkpAtasanJabatanNama"+element[1];
        urlAjax= "skp_combo_json/namajabatan";
      }      
      else if (id == 'reqPenilaianSkpPenilaiJabatanNama2')
      {
        var element= id.split('reqPenilaianSkpPenilaiJabatanNama2');
        var indexId= "reqPenilaianSkpPenilaiJabatanNama2"+element[1];
        urlAjax= "skp_combo_json/namajabatan";
      }
      else if (id == 'reqPenilaianSkpAtasanJabatanNama2')
      {
        var element= id.split('reqPenilaianSkpAtasanJabatanNama2');
        var indexId= "reqPenilaianSkpAtasanJabatanNama2"+element[1];
        urlAjax= "skp_combo_json/namajabatan";
      }    

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
              return {desc: element['desc'], id: element['id'], label: element['label']};
            });
            response(array);
          }
        }
      })
    },
    // focus: function (event, ui) 
    select: function (event, ui) 
    { 
      var id= $(this).attr('id');
      
      if (id == 'reqPenilaianSkpPenilaiJabatanNama')
      {
        // console.log(ui.item);
        $("#reqPenilaianSkpPenilaiJabatanId").val(ui.item.id);
        // $("#reqPenilaianSkpPenilaiNama").val(namapegawai);
      }
      else if (id == 'reqPenilaianSkpAtasanJabatanNama')
      {
        // console.log(ui.item);
        $("#reqPenilaianSkpAtasanJabatanNama").val(ui.item.id);
        // $("#reqPenilaianSkpPenilaiNama").val(namapegawai);
      }
      else if (id == 'reqPenilaianSkpPenilaiJabatanNama2')
      {
        // console.log(ui.item);
        $("#reqPenilaianSkpPenilaiJabatanId2").val(ui.item.id);
        // $("#reqPenilaianSkpPenilaiNama").val(namapegawai);
      }
      else if (id == 'reqPenilaianSkpAtasanJabatanNama2')
      {
        //  console.log(ui.item);
        $("#reqPenilaianSkpAtasanJabatanId2").val(ui.item.id);
        // $("#reqPenilaianSkpPenilaiNama").val(namapegawai);
      }
    },
    autoFocus: true
  })
  .autocomplete( "instance" )._renderItem = function( ul, item ) {
    return $( "<li>" )
    .append( "<a>" + item.desc  + "</a>" )
    .appendTo( ul );
  }
  ;
  });

  $('input[id^="reqPenilaianSkpPenilaiNip"], input[id^="reqPenilaianSkpAtasanNip"], input[id^="reqPenilaianSkpPenilaiNip2"], input[id^="reqPenilaianSkpAtasanNip2"]').each(function(){
  $(this).autocomplete({
    source:function(request, response){
      $(".preloader-wrapper").show();
      var id= this.element.attr('id');
      var replaceAnakId= replaceAnak= urlAjax= "";

      urlAjax= "pegawai_json/cari_pegawai";

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
              return {desc: element['desc'], id: element['id'], label: element['label'], namapegawai: element['namapegawai']};
            });
            response(array);
          }
        }
      })
    },
    // focus: function (event, ui) 
    select: function (event, ui) 
    { 
      var id= $(this).attr('id');
      var indexId= "reqPegawaiId";
      var idrow= namapegawai= "";
      idrow= ui.item.id;
      namapegawai= ui.item.namapegawai;
      
      //if (id.indexOf('reqPenilaianSkpPenilaiNip') !== -1)

      if (id =='reqPenilaianSkpPenilaiNip')
      {
        $("#reqPenilaianSkpPenilaiId").val(idrow);
        $("#reqPenilaianSkpPenilaiNama").val(namapegawai);
      }
      else if (id =='reqPenilaianSkpAtasanNip')
      {
        $("#reqPenilaianSkpAtasanId").val(idrow);
        $("#reqPenilaianSkpAtasanNama").val(namapegawai);
      }
      else if (id == 'reqPenilaianSkpPenilaiNip2')
      {
        $("#reqPenilaianSkpPenilaiId2").val(idrow);
        $("#reqPenilaianSkpPenilaiNama2").val(namapegawai);
      }
      else if (id == 'reqPenilaianSkpAtasanNip2')
      {
        $("#reqPenilaianSkpAtasanId2").val(idrow);
        $("#reqPenilaianSkpAtasanNama2").val(namapegawai);
      }
    },
    autoFocus: true
  })
  .autocomplete( "instance" )._renderItem = function( ul, item ) {
    return $( "<li>" )
    .append( "<a>" + item.desc  + "</a>" )
    .appendTo( ul );
  }
  ;
  });

  $('#reqPenilaianSkpPenilaiUnorNama, #reqPenilaianSkpAtasanUnorNama, #reqPenilaianSkpPenilaiUnorNama2, #reqPenilaianSkpAtasanUnorNama2, #reqPegawaiUnorNama, #reqPegawaiUnorNama2').each(function(){
  $(this).autocomplete({
    source:function(request, response){
      $(".preloader-wrapper").show();
      var id= this.element.attr('id');
      var replaceAnakId= replaceAnak= urlAjax= "";

      // if (id.indexOf('reqPenilaianSkpPenilaiUnorNama') !== -1)
      if (id == 'reqPenilaianSkpPenilaiUnorNama')
      {
        var element= id.split('reqPenilaianSkpPenilaiUnorNama');
        var indexId= "reqPenilaianSkpPenilaiUnorNama"+element[1];
        urlAjax= "skp_combo_json/namaunor";
      }    
      else if (id == 'reqPenilaianSkpAtasanUnorNama')
      {
        var element= id.split('reqPenilaianSkpAtasanUnorNama');
        var indexId= "reqPenilaianSkpAtasanUnorNama"+element[1];
        urlAjax= "skp_combo_json/namaunor";
      }      
      else if (id == 'reqPenilaianSkpPenilaiUnorNama2')
      {
        var element= id.split('reqPenilaianSkpPenilaiUnorNama2');
        var indexId= "reqPenilaianSkpPenilaiUnorNama2"+element[1];
        urlAjax= "skp_combo_json/namaunor";
      }
      else if (id == 'reqPenilaianSkpAtasanUnorNama2')
      {
        var element= id.split('reqPenilaianSkpAtasanUnorNama2');
        var indexId= "reqPenilaianSkpAtasanUnorNama2"+element[1];
        urlAjax= "skp_combo_json/namaunor";
      }
      else if (id == 'reqPegawaiUnorNama')
      {
        var element= id.split('reqPegawaiUnorNama');
        var indexId= "reqPegawaiUnorNama"+element[1];
        urlAjax= "skp_combo_json/namaunor";
      }
      else if (id == 'reqPegawaiUnorNama2')
      {
        var element= id.split('reqPegawaiUnorNama2');
        var indexId= "reqPegawaiUnorNama2"+element[1];
        urlAjax= "skp_combo_json/namaunor";
      }

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
              return {desc: element['desc'], id: element['id'], label: element['label']};
            });
            response(array);
          }
        }
      })
    },
    // focus: function (event, ui) 
    select: function (event, ui) 
    { 
      var id= $(this).attr('id');
      
      if (id == 'reqPenilaianSkpPenilaiUnorNama')
      {
        // console.log(ui.item);
        $("#reqPenilaianSkpPenilaiUnorId").val(ui.item.id);
        // $("#reqPenilaianSkpPenilaiNama").val(namapegawai);
      }
      else if (id == 'reqPenilaianSkpAtasanUnorNama')
      {
        // console.log(ui.item);
        $("#reqPenilaianSkpAtasanUnorNama").val(ui.item.id);
        // $("#reqPenilaianSkpPenilaiNama").val(namapegawai);
      }
      else if (id == 'reqPenilaianSkpPenilaiUnorNama2')
      {
        // console.log(ui.item);
        $("#reqPenilaianSkpPenilaiUnorId2").val(ui.item.id);
        // $("#reqPenilaianSkpPenilaiNama").val(namapegawai);
      }
      else if (id == 'reqPenilaianSkpAtasanUnorNama2')
      {
         // console.log(ui.item);
        $("#reqPenilaianSkpAtasanUnorId2").val(ui.item.id);
        // $("#reqPenilaianSkpPenilaiNama").val(namapegawai);
      }
      else if (id == 'reqPegawaiUnorNama')
      {
         // console.log(ui.item);
        $("#reqPegawaiUnorId").val(ui.item.id);
        // $("#reqPenilaianSkpPenilaiNama").val(namapegawai);
      }
      else if (id == 'reqPegawaiUnorNama2')
      {
         // console.log(ui.item);
        $("#reqPegawaiUnorId2").val(ui.item.id);
        // $("#reqPenilaianSkpPenilaiNama").val(namapegawai);
      }

    },
    autoFocus: true
  })
  .autocomplete( "instance" )._renderItem = function( ul, item ) {
    return $( "<li>" )
    .append( "<a>" + item.desc  + "</a>" )
    .appendTo( ul );
  }
  ;
  });

  $('#reqNilaiPerilakuKerja,#reqNilaiHasilKerja').on('change', function() {
    loadNilaiKuadran();
  });
  $('#reqTahun').on('change', function() {
    var url   = "app/loadUrl/app/pegawai_add_kinerja_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqParamTahun="+this.value;
    document.location.href=url;
  });

  setTimeout(function() {
    loadNilaiKuadran();
    
  }, 1500); 

});


  function loadNilaiKuadran(){
    var kodePertama = $("#reqNilaiHasilKerja").val();
    var kodeKedua = $("#reqNilaiPerilakuKerja").val();
    var kode = kodePertama+kodeKedua;
    $.get("penilaian_kinerja_json/comboNilaiKuadran?reqId="+kode )
    .done(function( data ) {
      var reqkode = '';
      var reqkodeNama = '';
      var   datax = JSON.parse(data);
      if(data !='[]' ){
         var   datas = datax[0];
        reqkode = datas['KODE'];
         reqkodeNama = datas['NAMA'];
      }
      $("#reqKuadratPredikatKinerja").val(reqkode);
      $("#reqKuadratPredikatKinerjaNama").val(reqkodeNama.toUpperCase());

    });
  } 
  
</script>

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
  <div id="basic-form" class="section">
    <div class="row">
      <div id='main' class="col s12 m12" style="padding-left: 15px;">

        <ul class="collection card">
          <li class="collection-item ubah-color-warna">EDIT KINERJA</li>
          <li class="collection-item">

            <form id="ff" method="post" enctype="multipart/form-data">
              <div class="row">
                <div class="input-field col s12 m3">
                  <select id="reqTahun"  name="reqTahun" >
                  <?
                  foreach($arrDataTahun as $value){
                  ?>
                   <option value="<?=$value?>" <? if($paramTahun==$value) echo 'selected';?>><?=$value?></option>
                  <?
                  }
                  ?>
                  </select>
                  <label for="reqTahun">Tahun </label>
                  <!-- <input type="text" readonly class="easyui-validatebox color-disb" required id="reqTahun" name="reqTahun" value="2023"/> -->
                </div>
              </div>
              <div class="row">
                <div class="input-field col s12 m3">  
                   <select id="reqTriwulan"   name="reqTriwulan" >
                  <!-- <option value="" <? if($reqTriwulan=="") echo 'selected';?>></option> -->
                  <?
                  foreach($arrDataTriwulanForm as $key=>$value){
                  ?>
                   <option value="<?=$key?>" <? if($reqTriwulan==$key) echo 'selected';?>><?=$value?></option>
                  <?
                  }
                  ?>
                </select>
                   <label for="reqTriwulan">Triwulan</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1">
                  1.
                  <input type="hidden" name="reqPenilaianSkpDinilaiId" value="<?=$reqPenilaianSkpDinilaiId?>"/>
                </div>
                <div class="input-field col s12 m6">
                  Yang dinilai
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  a.
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqPenilaianSkpDinilaiNama">NAMA</label>
                  <input type="text" id="reqPenilaianSkpDinilaiNama" name="reqPenilaianSkpDinilaiNama" readonly="readonly" value="<?=$reqPenilaianSkpDinilaiNama?>"/>
                </div>
              </div>
              
              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  b.
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqPenilaianSkpDinilaiNip">NIP</label>
                  <input type="text" id="reqPenilaianSkpDinilaiNip" name="reqPenilaianSkpDinilaiNip" readonly="readonly" value="<?=$reqPenilaianSkpDinilaiNip?>"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  c.
                </div>
                <div class="input-field col s12 m6">
                  <select id="reqPegawaiJenisJabatan" name="reqPegawaiJenisJabatan" >
                    <option value="" <? if($reqPegawaiJenisJabatan=="") echo 'selected';?>></option>
                    <option value="1" <? if($reqPegawaiJenisJabatan==1) echo 'selected';?>>Struktural (pejabat yang memiliki Eselon)</option>
                    <option value="2" <? if($reqPegawaiJenisJabatan==2) echo 'selected';?>>Fungsional (Penyuluh, Guru, Tenaga Kesehatan, Kepala Sekolah, Kepala Puskesmas, Koorwil dll)</option>
                    <option value="4" <? if($reqPegawaiJenisJabatan==4) echo 'selected';?>>Pelaksana / Staf / Fungsional Umum</option>
                  </select>
                  <label for="reqPegawaiJenisJabatan">Jenis Jabatan PNS Yang Dinilai</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  d.
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqPegawaiUnorNama">Unit Kerja PNS Yang Dinilai</label>
                  <input type="hidden" id="reqPegawaiUnorId" name="reqPegawaiUnorId" value="<?=$reqPegawaiUnorId?>" />
                  <input placeholder type="text" class="easyui-validatebox" required id="reqPegawaiUnorNama" name="reqPegawaiUnorNama" <?=$read?> value="<?=$reqPegawaiUnorNama?>"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1"></div>
                <div class="input-field col s12 m6">
                  <input type="checkbox" id="reqPejabatPenilaiIsManual" name="reqPejabatPenilaiIsManual" value="1" <? if($reqPejabatPenilaiIsManual == 1) echo 'checked'?> />
                  <label for="reqPejabatPenilaiIsManual"></label>
                  *centang jika Pejabat Penilai Non-PNS
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1">
                  2.
                </div>
                <div class="input-field col s12 m6">
                  Pejabat Penilai
                  <input type="hidden" id="reqPenilaianSkpPenilaiId" name="reqPenilaianSkpPenilaiId" value="<?=$reqPenilaianSkpPenilaiId?>"/>
                </div>
              </div>
              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  a.
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqPenilaianSkpPenilaiNip">NIP</label>
                  <input placeholder type="text" class="easyui-validatebox" required id="reqPenilaianSkpPenilaiNip" name="reqPenilaianSkpPenilaiNip" <?=$read?> value="<?=$reqPenilaianSkpPenilaiNip?>"/>
                </div>
              </div>
              
              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  b.
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqPenilaianSkpPenilaiNama">Nama</label>
                  <input placeholder type="text" class="easyui-validatebox color-disb" id="reqPenilaianSkpPenilaiNama" name="reqPenilaianSkpPenilaiNama" value="<?=$reqPenilaianSkpPenilaiNama?>" readonly />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  c.
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqPenilaianSkpPenilaiJabatanNama">Jabatan Ketika Menilai</label>
                  <input type="hidden" id="reqPenilaianSkpPenilaiJabatanId" name="reqPenilaianSkpPenilaiJabatanId" value="" />
                  <input placeholder type="text" class="easyui-validatebox" id="reqPenilaianSkpPenilaiJabatanNama" name="reqPenilaianSkpPenilaiJabatanNama" required value="<?=$reqPenilaianSkpPenilaiJabatanNama?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  d.
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqPenilaianSkpPenilaiUnorNama">Unit Kerja Ketika Menilai</label>
                  <input placeholder type="text" class="easyui-validatebox" id="reqPenilaianSkpPenilaiUnorNama" name="reqPenilaianSkpPenilaiUnorNama" required value="<?=$reqPenilaianSkpPenilaiUnorNama?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  e.
                </div>
                <div class="input-field col s12 m6">
                  <select name="reqPenilaianSkpPenilaiPangkatId" id="reqPenilaianSkpPenilaiPangkatId" >
                    <option value=""></option>
                    <? 
                    while($pangkat->nextRow())
                    {
                    ?>
                      <option value="<?=$pangkat->getField('PANGKAT_ID')?>" <? if($reqPenilaianSkpPenilaiPangkatId == $pangkat->getField('PANGKAT_ID')) echo 'selected';?>><?=$pangkat->getField('KODE')?></option>
                    <? 
                    }
                    ?>
                  </select>
                  <label>Gol/Ruang Ketika Menilai</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1"></div>
                <div class="input-field col s12 m6">
                  <input type="checkbox" id="reqAtasanPejabatPenilaiIsManual" name="reqAtasanPejabatPenilaiIsManual" value="1" <? if($reqAtasanPejabatPenilaiIsManual == 1) echo 'checked'?> />
                  <label for="reqAtasanPejabatPenilaiIsManual"></label>
                  *centang jika Atasan Pejabat Penilai Non-PNS
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1">
                  3.
                </div>
                <div class="input-field col s12 m6">
                  Atasan Pejabat Penilai
                  <input type="hidden" name="reqPenilaianSkpAtasanId" id="reqPenilaianSkpAtasanId" value="<?=$reqPenilaianSkpAtasanId?>"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  a.
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqPenilaianSkpAtasanNip">NIP</label>
                  <input placeholder type="text" class="easyui-validatebox" required id="reqPenilaianSkpAtasanNip"  name="reqPenilaianSkpAtasanNip" <?=$read?> value="<?=$reqPenilaianSkpAtasanNip?>"/>
                </div>
              </div>
              
              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  b.
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqPenilaianSkpAtasanNama">Nama</label>
                  <input placeholder type="text" class="color-disb" id="reqPenilaianSkpAtasanNama" name="reqPenilaianSkpAtasanNama" value="<?=$reqPenilaianSkpAtasanNama?>" readonly />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  c.
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqPenilaianSkpAtasanJabatanNama">Jabatan Ketika Menandatangani</label>
                  <input placeholder type="text" class="easyui-validatebox" id="reqPenilaianSkpAtasanJabatanNama" name="reqPenilaianSkpAtasanJabatanNama" required value="<?=$reqPenilaianSkpAtasanJabatanNama?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  d.
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqPenilaianSkpAtasanUnorNama">Unit Kerja Ketika Menandatangani</label>
                  <input placeholder type="text" class="easyui-validatebox" id="reqPenilaianSkpAtasanUnorNama" name="reqPenilaianSkpAtasanUnorNama" required value="<?=$reqPenilaianSkpAtasanUnorNama?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  e.
                </div>
                <div class="input-field col s12 m6">
                  <select name="reqPenilaianSkpAtasanPangkatId" id="reqPenilaianSkpAtasanPangkatId" >
                    <option value=""></option>
                    <? 
                    while($pangkat2->nextRow())
                    {
                    ?>
                      <option value="<?=$pangkat2->getField('PANGKAT_ID')?>" <? if($reqPenilaianSkpAtasanPangkatId == $pangkat2->getField('PANGKAT_ID')) echo 'selected';?>><?=$pangkat2->getField('KODE')?></option>
                    <? 
                    }
                    ?>
                  </select>
                  <label>Gol/Ruang Ketika Menandatangani</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1">
                  4.
                </div>
                <div class="input-field col s12 m6">
                  Unsur Yang Dinilai
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  a.
                </div>
                <div class="input-field col s12 m6">
                  <select id="reqNilaiHasilKerja" name="reqNilaiHasilKerja" >
                    <option value="" <? if($reqNilaiHasilKerja=="") echo 'selected';?>></option>
                    <option value="1" <? if($reqNilaiHasilKerja==1) echo 'selected';?>>DIATAS EKSPEKTASI</option>
                    <option value="2" <? if($reqNilaiHasilKerja==2) echo 'selected';?>>SESUAI EKSPEKTASI</option>
                    <option value="3" <? if($reqNilaiHasilKerja==3) echo 'selected';?>>DIBAWAH EKSPEKTASI</option>
                  </select>
                  <label for="reqNilaiHasilKerja">Nilai Hasil Kerja</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  b.
                </div>
                <div class="input-field col s12 m6">
                  <select id="reqNilaiPerilakuKerja" name="reqNilaiPerilakuKerja" >
                    <option value="" <? if($reqNilaiPerilakuKerja=="") echo 'selected';?>></option>
                    <option value="1" <? if($reqNilaiPerilakuKerja==1) echo 'selected';?>>DIATAS EKSPEKTASI</option>
                    <option value="2" <? if($reqNilaiPerilakuKerja==2) echo 'selected';?>>SESUAI EKSPEKTASI</option>
                    <option value="3" <? if($reqNilaiPerilakuKerja==3) echo 'selected';?>>DIBAWAH EKSPEKTASI</option>
                  </select>
                  <label for="reqNilaiPerilakuKerja">Nilai Perilaku Kerja</label>
                </div>
              </div>
              <div class="row">
              <div class="input-field col s12 m1 offset-m1">
                  c.
                </div>
                <div class="input-field col s12 m6">
                   <!-- <select id="reqKuadratPredikatKinerja" name="reqKuadratPredikatKinerja" >
                    <option value="" <? if($reqKuadratPredikatKinerja=="") echo 'selected';?>></option>
                    <?
                    foreach($arrNilaiKuadran as $value){
                      ?>
                      <option value="<?=$value['KODE']?>" <? if($reqKuadratPredikatKinerja==$value['KODE']) echo 'selected';?>><?=strtoupper($value['NAMA'])?></option>
                      <?
                    }
                    ?>

                  </select>  -->
                  <label for="reqKuadratPredikatKinerja">Kuadrat Predikat Kinerja</label>
                  <input type="hidden"    id="reqKuadratPredikatKinerja" name="reqKuadratPredikatKinerja" value=""/>
                  <!-- <input type="text"  readonly class="color-disb"  id="reqKuadratPredikatKinerjaNama" name="reqKuadratPredikatKinerjaNama" value=""/> -->
                    <input placeholder type="text" class="color-disb" id="reqKuadratPredikatKinerjaNama" name="reqKuadratPredikatKinerjaNama" value="" readonly />
                </div>
              </div>
              <div class="row">
              <div class="input-field col s12 m1 offset-m1">
                  d.
                </div>
                <div class="input-field col s12 m6">
                  <select id="reqCapaianOrganisasi" name="reqCapaianOrganisasi" >
                    <option value="" <? if($reqCapaianOrganisasi=="") echo 'selected';?>></option>
                    <?
                    foreach($arrCapaianOrganisasi as $value){
                      ?>
                      <option value="<?=$value['ID']?>" <? if($reqCapaianOrganisasi==$value['ID']) echo 'selected';?>><?=strtoupper($value['NAMA'])?></option>
                      <?
                    }
                    ?>

                  </select> 
                  <label for="reqCapaianOrganisasi">Capaian Organisasi</label>
                </div>
              </div>


              <div class="row">
                <div class="input-field col s12">
                  <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>

                  <script type="text/javascript">
                    $("#kembali").click(function() { 
                      <?
                      if(!empty($pelayananid))
                      {
                      ?>
                      vkembali= "app/loadUrl/app/pegawai_add_kinerja_monitoring?reqId=<?=$reqId?>&pelayananid=<?=$pelayananid?>&pelayananjenis=<?=$pelayananjenis?>&pelayananrowid=<?=$pelayananrowid?>&pelayanankembali=<?=$pelayanankembali?>";
                      <?
                      }
                      else
                      {
                      ?>
                      vkembali= "app/loadUrl/app/pegawai_add_kinerja_monitoring?reqId=<?=$reqId?>";
                      <?
                      }
                      ?>
                      document.location.href = vkembali;
                    });
                  </script>

                  <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                  <input type="hidden" name="reqId" value="<?=$reqId?>" />
                  <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                  


                  <?
                  if($tempAksesMenu == "A")
                  {
                  ?>
                  <button class="btn waves-effect waves-light green" style="font-size:9pt" type="submit" name="action">Simpan
                    <i class="mdi-content-save left hide-on-small-only"></i>
                  </button>
                  <?
                  }
                  ?>
                </div>
              </div>
            </form>
          </li>
        </ul>
      </div>

      <div id='divframepdf' class="col s12 m6" style="padding-top: 10px;">
        <input type="hidden" id="vnewframe" value="">
        <span id="labelriwayatframepdf"></span>
        <img id="infonewimage" style="width:inherit; width: 100%; height: 100%" />
        <iframe id="infonewframe" style="width: 100%; height: 160%" src=""></iframe>
      </div>

    </div>
  </div>

<!-- jQuery Library -->
<!-- <script type="text/javascript" src="lib/materializereqlate/js/plugins/jquery-1.11.2.min.js"></script> -->

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
<script type="text/javascript" src="lib/materializetemplate/js/plugins/formatter/jquery.formatter.min.js"></script>   

<style type="text/css">
  .select-dropdown {
    max-height:250px !important; overflow:auto !important;
  }
</style>

<script type="text/javascript">
  $('#reqTahun').bind('keyup paste', function(){
    this.value = this.value.replace(/[^0-9]/g, '');
  });
  
  
  $('#reqSkpNilai, #reqOrientasiNilai, #reqDisiplinNilai, #reqIntegritasNilai, #reqKerjasamaNilai, #reqKomitmenNilai, #reqKepemimpinanNilai').keyup(function() {

    var valueSkpHasil = '';

    valueSkpHasil= Number(String(getNominal($("#reqSkpNilai").val())))*0.6;
    valueSkpHasil = roundToTwo(valueSkpHasil);
    $("#reqSkpHasil").val(setNominal(valueSkpHasil)); 
    
    var valueParse1 = '';
    valueParse1= Number(String(getNominal($("#reqOrientasiNilai").val())))+Number(String(getNominal($("#reqDisiplinNilai").val())))+Number(String(getNominal($("#reqIntegritasNilai").val())))+
          Number(String(getNominal($("#reqKerjasamaNilai").val())))+Number(String(getNominal($("#reqKomitmenNilai").val())))+Number(String(getNominal($("#reqKepemimpinanNilai").val())));
    // console.log(myRound(valueParse1, 2));
    valueParse1 = roundToTwo(valueParse1);
    $("#reqJumlahNilai").val(setNominal(valueParse1));
    
    if($("#reqKepemimpinanNilai").val()=="")
    {
      valueRata=(valueParse1/5);
      valueRata = roundToTwo(valueRata);
      valuePerilakuHasil=valueRata*0.4; 
      valuePerilakuHasil = roundToTwo(valuePerilakuHasil);
      valuePrestasiHasil=valuePerilakuHasil+valueSkpHasil;
      valuePrestasiHasil = roundToTwo(valuePrestasiHasil);  
      $("#reqRataNilai").val(setNominal(valueRata));
      $("#reqPerilakuNilai").val(setNominal(valueRata));
      $("#reqPerilakuHasil").val(setNominal(valuePerilakuHasil));
      $("#reqPrestasiHasil").val(setNominal(valuePrestasiHasil));
    }
    else
    {
      valueRata=(valueParse1/6);  
      valueRata = roundToTwo(valueRata);
      valuePerilakuHasil=valueRata*0.4; 
      valuePerilakuHasil = roundToTwo(valuePerilakuHasil);
      valuePrestasiHasil=valuePerilakuHasil+valueSkpHasil;
      valuePrestasiHasil = roundToTwo(valuePrestasiHasil);  
      $("#reqRataNilai").val(setNominal(valueRata));
      $("#reqPerilakuNilai").val(setNominal(valueRata));
      $("#reqPerilakuHasil").val(setNominal(valuePerilakuHasil));
      $("#reqPrestasiHasil").val(setNominal(valuePrestasiHasil));
    }

  });
  
  function getNominal(value)
  {
    value= String(value);
    value= value.replace(",", ".");
    return value;
  }

  function setNominal(value)
  {
    value= String(value);
    value= value.replace(".", ",");
    return value;
  }

  function roundToTwo(value) {
    return(Math.round(value * 100) / 100);
  }

  $(document).ready(function() {
    $('select').material_select();
  });
  
  $('#reqTmtWaktuJabatan').formatter({
    'pattern': '{{99}}:{{99}}',
  });
  
  $('.materialize-textarea').trigger('autoresize');

  // untuk area untuk upload file
  vbase_url= "<?=base_url()?>";
  getarrlistpilihfilefield= JSON.parse('<?=JSON_encode($arrlistpilihfilefield)?>');
  // console.log(getarrlistpilihfilefield);

  // apabila butuh kualitas dokumen di ubah
  vselectmaterial= "1";
  // untuk area untuk upload file
  
</script>

<script type="text/javascript" src="lib/easyui/pelayanan-efile.js"></script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>