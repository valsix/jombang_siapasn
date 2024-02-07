<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('PenilaianSkp');
$this->load->model('Pegawai');
$this->load->model('KualitasFile');
$this->load->library('globalfilepegawai');
$this->load->model('Pangkat');

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

$setTahun= new PenilaianSkp();
$set = new PenilaianSkp();
$pegawai_dinilai = new Pegawai();
$pegawai_penilai = new Pegawai();
$pegawai_atasan  = new Pegawai();

$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "010701";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

if($reqRowId=="")
{
  $reqMode = 'insert';
}
else
{
  $reqMode = 'update';
}

$statement= " AND A.PENILAIAN_SKP_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
$set->selectByParams(array(), -1, -1, $statement);
$set->firstRow();
#echo $set->query;exit;

$reqTahun = "2021";

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

$reqNilaiHasilKerja= dotToComma(setLebihNol($set->getField('NILAI_HASIL_KERJA'))); 
$reqNilaiHasilPerilaku= dotToComma(setLebihNol($set->getField('NILAI_HASIL_PERILAKU'))); 

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

$(document).ready(function() {
$('#reqPegawaiJenisJabatan').change(function() {
  	if( $(this).val() == 1) {
       		$('#reqKepemimpinanNilai').prop( "readonly", false );
    } else {       
      $('#reqKepemimpinanNilai').prop( "readonly", true );
      $('#reqKepemimpinanNilai').val("0");
    }
  });

  $('#reqPegawaiJenisJabatan2').change(function() {
  	if( $(this).val() == 1) {
       		$('#reqKepemimpinanNilai2').prop( "readonly", false );
    } else {       
      $('#reqKepemimpinanNilai2').prop( "readonly", true );
      $('#reqKepemimpinanNilai2').val("0");
    }
  });

});


function setpejabatpenilaicetang()
{
  $("#reqPenilaianSkpPenilaiId,#reqPenilaianSkpPenilaiNip,#reqPenilaianSkpPenilaiNama").val("");
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


function setpejabatpenilaicetang2()
{
  $("#reqPenilaianSkpPenilaiId2,#reqPenilaianSkpPenilaiNip2,#reqPenilaianSkpPenilaiNama2").val("");
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

function setatasanpejabatpenilaicetang()
{
  $("#reqPenilaianSkpAtasanId,#reqPenilaianSkpAtasanNip,#reqPenilaianSkpAtasanNama").val("");
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

function setatasanpejabatpenilaicetang2()
{
  $("#reqPenilaianSkpAtasanId2,#reqPenilaianSkpAtasanNip2,#reqPenilaianSkpAtasanNama2").val("");
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

  $("#reqPejabatPenilaiIsManual").click(function () {
    setpejabatpenilaicetang();
  });

  $("#reqAtasanPejabatPenilaiIsManual").click(function () {
    setatasanpejabatpenilaicetang();
  });

  $("#reqPejabatPenilaiIsManual2").click(function () {
    setpejabatpenilaicetang2();
  });

  $("#reqAtasanPejabatPenilaiIsManual2").click(function () {
    setatasanpejabatpenilaicetang2();
  });

  $(".preloader-wrapper").hide();

  $('#ff').form({
    url:'penilaian_skp_json/add',
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
           vkembali= "app/loadUrl/app/pegawai_add_skp21_data?reqId=<?=$reqId?>&reqRowId="+rowid+"&pelayananid=<?=$pelayananid?>&pelayananjenis=<?=$pelayananjenis?>&pelayananrowid=<?=$pelayananrowid?>&pelayanankembali=<?=$pelayanankembali?>";
           <?
           }
           else
           {
           ?>
           vkembali= "app/loadUrl/app/pegawai_add_skp21_data?reqId=<?=$reqId?>&reqRowId="+rowid;
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


});
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
          <li class="collection-item ubah-color-warna">EDIT PENILAIAN SKP / PPK</li>
          <li class="collection-item">

            <form id="ff" method="post" enctype="multipart/form-data">
              <div class="row">
                <div class="input-field col s12 m3">
                  <label for="reqTahun">Tahun</label>
                  <input type="text" readonly class="easyui-validatebox color-disb" required id="reqTahun" name="reqTahun" value="2021"/>
                </div>
              </div>

              <div class="row">
                <h1>SEMESTER 1</h1>
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
                <div class="col s12 m10">

                  <div class="input-field col s12 m6">
                    <label for="reqSkpNilai">Sasaran Kerja Penilaian Skp</label>
                    <input placeholder="" type="text" class="easyui-validatebox" data-options="validType:'nilaiKurangSamaDengan[100]'" required id="reqSkpNilai" name="reqSkpNilai" <?=$read?> value="<?=$reqSkpNilai?>" onkeypress='kreditvalidate(event, this)' />
                  </div>
                  <div class="input-field col s12 m3">
<!--                    <label for="reqSkpHasil">Jumlah</label> -->
                    <input type="hidden" placeholder name="reqSkpHasil" id="reqSkpHasil" value="<?=maybePrefixZero($reqSkpHasil)?>" readonly="readonly"/>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  b.
                </div>
                <div class="col s12 m10">
                  <div class="input-field col s12 m3">
                    Perilaku Kerja
                  </div>
                  <div class="input-field col s12 m3">
                    <label for="reqOrientasiNilai">Orientasi Pelayanan</label>
                    <input placeholder="" type="text" class="easyui-validatebox" data-options="validType:'nilaiKurangSamaDengan[100]'" required id="reqOrientasiNilai" name="reqOrientasiNilai" <?=$read?> value="<?=$reqOrientasiNilai?>" onkeypress='kreditvalidate(event, this)' />
                  </div>
                  <div class="input-field col s12 m3 offset-m1">
                    <label for="reqIntegritasNilai">Integritas</label>
                    <input placeholder="" type="text" class="easyui-validatebox" data-options="validType:'nilaiKurangSamaDengan[100]'" required id="reqIntegritasNilai" name="reqIntegritasNilai" <?=$read?> value="<?=$reqIntegritasNilai?>" onkeypress='kreditvalidate(event, this)' />
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col s12 m10 offset-m2">
                  <div class="input-field col s12 m3 offset-m3">
                    <label for="reqKomitmenNilai">Komitmen</label>
                    <input placeholder="" type="text" class="easyui-validatebox" data-options="validType:'nilaiKurangSamaDengan[100]'" required id="reqKomitmenNilai" name="reqKomitmenNilai" <?=$read?> value="<?=$reqKomitmenNilai?>" onkeypress='kreditvalidate(event, this)' />
                  </div>
                  <div class="input-field col s12 m3 offset-m1">
                    <label for="reqDisiplinNilai">Disiplin</label>
                    <input placeholder="" type="text" class="easyui-validatebox" data-options="validType:'nilaiKurangSamaDengan[100]'" required id="reqDisiplinNilai" name="reqDisiplinNilai" <?=$read?> value="<?=$reqDisiplinNilai?>" onkeypress='kreditvalidate(event, this)' />
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col s12 m10 offset-m2">
                  <div class="input-field col s12 m3 offset-m3">
                    <label for="reqKerjasamaNilai">Kerjasama</label>
                    <input placeholder="" type="text" class="easyui-validatebox" data-options="validType:'nilaiKurangSamaDengan[100]'" required id="reqKerjasamaNilai" name="reqKerjasamaNilai" <?=$read?> value="<?=$reqKerjasamaNilai?>" onkeypress='kreditvalidate(event, this)' />
                  </div>
                  <div class="input-field col s12 m3 offset-m1">
                    <label for="reqKepemimpinanNilai">Kepemimpinan</label>
                    <input placeholder="" type="text" class="easyui-validatebox" data-options="validType:'nilaiKurangSamaDengan[100]'" required id="reqKepemimpinanNilai" name="reqKepemimpinanNilai" <?=$read?> value="<?=$reqKepemimpinanNilai?>" onkeypress='kreditvalidate(event, this)' />
                  </div>
                </div>
              </div>
<!--
              <div class="row">
                <div class="col s12 m10 offset-m2">
                  <div class="input-field col s12 m3 offset-m3">
                    <label for="reqJumlahNilai">Jumlah</label>
                    <input type="hidden" name="reqJumlahNilai" id="reqJumlahNilai" value="<?=maybePrefixZero($reqJumlahNilai)?>" readonly="readonly" />
                  </div>
                  <div class="input-field col s12 m3 offset-m1">
                    <label for="reqRataNilai">Nilai Rata - Rata</label>
                    <input type="hidden" name="reqRataNilai" id="reqRataNilai" value="<?=maybePrefixZero($reqRataNilai)?>" readonly="readonly" />
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col s12 m10 offset-m2">
                  <div class="input-field col s12 m3 offset-m3">
                    <label for="reqPerilakuNilai">Nilai Perilaku Kerja</label>
                    <input type="text" name="reqPerilakuNilai" id="reqPerilakuNilai" value="<?=maybePrefixZero($reqPerilakuNilai)?>" readonly="readonly" />
                  </div>
                  <div class="input-field col s12 m3">
                    <label for="reqPerilakuHasil" class="active">Jumlah</label>
                    <input type="text" name="reqPerilakuHasil" id="reqPerilakuHasil" value="<?=maybePrefixZero($reqPerilakuHasil)?>" readonly="readonly"/>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col s12 m10 offset-m2">
                  <div class="input-field col s12 m3 offset-m3">
                    <label for="reqPrestasiHasil">Nilai Prestasi Kerja</label>
                    <input type="text" name="reqPrestasiHasil" id="reqPrestasiHasil" value="<?=maybePrefixZero($reqPrestasiHasil)?>" readonly="readonly"/>
                  </div>
                </div>
              </div>
-->

              <div class="row">
                <h1>SEMESTER 2</h1>
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
                  <select id="reqPegawaiJenisJabatan2" name="reqPegawaiJenisJabatan2" >
                    <option value="" <? if($reqPegawaiJenisJabatan2=="") echo 'selected';?>></option>
                    <option value="1" <? if($reqPegawaiJenisJabatan==1) echo 'selected';?>>Struktural (pejabat yang memiliki Eselon)</option>
                    <option value="2" <? if($reqPegawaiJenisJabatan==2) echo 'selected';?>>Fungsional (Penyuluh, Guru, Tenaga Kesehatan, Kepala Sekolah, Kepala Puskesmas, Koorwil dll)</option>
                    <option value="4" <? if($reqPegawaiJenisJabatan==4) echo 'selected';?>>Pelaksana / Staf / Fungsional Umum</option>
                  </select>
                  <label for="reqPegawaiJenisJabatan2">Jenis Jabatan PNS Yang Dinilai</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  d.
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqPegawaiUnorNama2">Unit Kerja PNS Yang Dinilai</label>
                  <input type="hidden" id="reqPegawaiUnorId2" name="reqPegawaiUnorId2" value="<?=$reqPegawaiUnorId2?>" />
                  <input placeholder type="text" class="easyui-validatebox" required id="reqPegawaiUnorNama2" name="reqPegawaiUnorNama2" <?=$read?> value="<?=$reqPegawaiUnorNama2?>"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1"></div>
                <div class="input-field col s12 m6">
                  <input type="checkbox" id="reqPejabatPenilaiIsManual2" name="reqPejabatPenilaiIsManual2" value="1" <? if($reqPejabatPenilaiIsManual2 == 1) echo 'checked'?> />
                  <label for="reqPejabatPenilaiIsManual2"></label>
                  *centang jika Pejabat Penilai Non-PNS
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1">
                  2.
                </div>
                <div class="input-field col s12 m6">
                  Pejabat Penilai
                  <input type="hidden" id="reqPenilaianSkpPenilaiId2" name="reqPenilaianSkpPenilaiId2" value="<?=$reqPenilaianSkpPenilaiId2?>"/>
                </div>
              </div>
              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  a.
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqPenilaianSkpPenilaiNip2">NIP</label>
                  <input placeholder type="text" class="easyui-validatebox" id="reqPenilaianSkpPenilaiNip2" name="reqPenilaianSkpPenilaiNip2" <?=$read?> value="<?=$reqPenilaianSkpPenilaiNip2?>"/>
                </div>
              </div>
              
              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  b.
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqPenilaianSkpPenilaiNama2">Nama</label>
                  <input placeholder type="text" class="easyui-validatebox color-disb" id="reqPenilaianSkpPenilaiNama2" name="reqPenilaianSkpPenilaiNama2" value="<?=$reqPenilaianSkpPenilaiNama2?>" readonly />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  c.
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqPenilaianSkpPenilaiJabatanNama2">Jabatan Ketika Menilai</label>
                  <input type="hidden" id="reqPenilaianSkpPenilaiJabatanId2" name="reqPenilaianSkpPenilaiJabatanId2" value="" />
                  <input placeholder type="text" class="easyui-validatebox" id="reqPenilaianSkpPenilaiJabatanNama2" name="reqPenilaianSkpPenilaiJabatanNama2" required value="<?=$reqPenilaianSkpPenilaiJabatanNama2?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  d.
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqPenilaianSkpPenilaiUnorNama2">Unit Kerja Ketika Menilai</label>
                  <input placeholder type="text" class="easyui-validatebox" id="reqPenilaianSkpPenilaiUnorNama2" name="reqPenilaianSkpPenilaiUnorNama2" required value="<?=$reqPenilaianSkpPenilaiUnorNama2?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  e.
                </div>
                <div class="input-field col s12 m6">
                  <select name="reqPenilaianSkpPenilaiPangkatId2" id="reqPenilaianSkpPenilaiPangkatId2" >
                    <option value=""></option>
                    <? 
                    while($pangkat3->nextRow())
                    {
                    ?>
                      <option value="<?=$pangkat3->getField('PANGKAT_ID')?>" <? if($reqPenilaianSkpPenilaiPangkatId2 == $pangkat3->getField('PANGKAT_ID')) echo 'selected';?>><?=$pangkat3->getField('KODE')?></option>
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
                  <input type="checkbox" id="reqAtasanPejabatPenilaiIsManual2" name="reqAtasanPejabatPenilaiIsManual2" value="1" <? if($reqAtasanPejabatPenilaiIsManual2 == 1) echo 'checked'?> />
                  <label for="reqAtasanPejabatPenilaiIsManual2"></label>
                  *centang jika Atasan Pejabat Penilai Non-PNS
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1">
                  3.
                </div>
                <div class="input-field col s12 m6">
                  Atasan Pejabat Penilai
                  <input type="hidden" name="reqPenilaianSkpAtasanId2" id="reqPenilaianSkpAtasanId2" value="<?=$reqPenilaianSkpAtasanId2?>"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  a.
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqPenilaianSkpAtasanNip2">NIP</label>
                  <input placeholder type="text" class="easyui-validatebox" required id="reqPenilaianSkpAtasanNip2"  name="reqPenilaianSkpAtasanNip2" <?=$read?> value="<?=$reqPenilaianSkpAtasanNip2?>"/>
                </div>
              </div>
              
              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  b.
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqPenilaianSkpAtasanNama2">Nama</label>
                  <input placeholder type="text" class="color-disb" required id="reqPenilaianSkpAtasanNama2" name="reqPenilaianSkpAtasanNama2" value="<?=$reqPenilaianSkpAtasanNama2?>" readonly />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  c.
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqPenilaianSkpAtasanJabatanNama2">Jabatan Ketika Menandatangani</label>
                  <input placeholder type="text" class="easyui-validatebox" id="reqPenilaianSkpAtasanJabatanNama2" name="reqPenilaianSkpAtasanJabatanNama2" required value="<?=$reqPenilaianSkpAtasanJabatanNama2?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  d.
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqPenilaianSkpAtasanUnorNama2">Unit Kerja Ketika Menandatangani</label>
                  <input placeholder type="text" class="easyui-validatebox" id="reqPenilaianSkpAtasanUnorNama2" name="reqPenilaianSkpAtasanUnorNama2" required value="<?=$reqPenilaianSkpAtasanUnorNama2?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  e.
                </div>
                <div class="input-field col s12 m6">
                  <select name="reqPenilaianSkpAtasanPangkatId2" id="reqPenilaianSkpAtasanPangkatId2" >
                    <option value=""></option>
                    <? 
                    while($pangkat4->nextRow())
                    {
                    ?>
                      <option value="<?=$pangkat4->getField('PANGKAT_ID')?>" <? if($reqPenilaianSkpAtasanPangkatId2 == $pangkat4->getField('PANGKAT_ID')) echo 'selected';?>><?=$pangkat4->getField('KODE')?></option>
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
                <div class="col s12 m10">

                  <div class="input-field col s12 m6">
                    <label for="reqSkpNilai2">Sasaran Kerja Penilaian Skp</label>
                    <input placeholder="" type="text" class="easyui-validatebox" data-options="validType:'nilaiKurangSamaDengan[120]'" required id="reqSkpNilai2" name="reqSkpNilai2" <?=$read?> value="<?=$reqSkpNilai2?>" onkeypress='kreditvalidate(event, this)' />
                  </div>
                  <div class="input-field col s12 m3">
<!--                <label for="reqSkpHasil2">Jumlah</label>  -->
                    <input type="hidden" placeholder name="reqSkpHasil2" id="reqSkpHasil2" value="<?=maybePrefixZero($reqSkpHasil2)?>" readonly="readonly"/>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1 offset-m1">
                  b.
                </div>
                <div class="col s12 m10">
                  <div class="input-field col s12 m3">
                    Perilaku Kerja
                  </div>
                  <div class="input-field col s12 m3">
                    <label for="reqOrientasiNilai2">Orientasi Pelayanan</label>
                    <input placeholder="" type="text" class="easyui-validatebox" data-options="validType:'nilaiKurangSamaDengan[120]'" required id="reqOrientasiNilai2" name="reqOrientasiNilai2" <?=$read?> value="<?=$reqOrientasiNilai2?>" onkeypress='kreditvalidate(event, this)' />
                  </div>
                  <div class="input-field col s12 m3 offset-m1">
                    <label for="reqKomitmenNilai2">Komitmen</label>
                    <input placeholder="" type="text" class="easyui-validatebox" data-options="validType:'nilaiKurangSamaDengan[120]'" required id="reqKomitmenNilai2" name="reqKomitmenNilai2" <?=$read?> value="<?=$reqKomitmenNilai2?>" onkeypress='kreditvalidate(event, this)' />
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col s12 m10 offset-m2">
                <div class="input-field col s12 m3 offset-m3">
                    <label for="reqKerjasamaNilai2">Kerjasama</label>
                    <input placeholder="" type="text" class="easyui-validatebox" data-options="validType:'nilaiKurangSamaDengan[120]'" required id="reqKerjasamaNilai2" name="reqKerjasamaNilai2" <?=$read?> value="<?=$reqKerjasamaNilai2?>" onkeypress='kreditvalidate(event, this)' />
                  </div>
                  <div class="input-field col s12 m3 offset-m1">
                    <label for="reqKepemimpinanNilai2">Kepemimpinan</label>
                    <input placeholder="" type="text" class="easyui-validatebox" data-options="validType:'nilaiKurangSamaDengan[120]'" required id="reqKepemimpinanNilai2" name="reqKepemimpinanNilai2" <?=$read?> value="<?=$reqKepemimpinanNilai2?>" onkeypress='kreditvalidate(event, this)' />
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col s12 m10 offset-m2">
                <div class="input-field col s12 m3 offset-m3">
                    <label for="reqInisiatifkerjaNilai2">Inisiatif Kerja</label>
                    <input type="text" class="easyui-validatebox" data-options="validType:'nilaiKurangSamaDengan[120]'" required id="reqInisiatifkerjaNilai2" name="reqInisiatifkerjaNilai2" <?=$read?> value="<?=$reqInisiatifkerjaNilai2?>" onkeypress='kreditvalidate(event, this)' />
                  </div>                  
                </div>
              </div>
<!--
              <div class="row">
                <div class="col s12 m10 offset-m2">
                  <div class="input-field col s12 m3 offset-m3">
                    <label for="reqJumlahNilai">Jumlah</label>
                    <input type="text" name="reqJumlahNilai2" id="reqJumlahNilai" value="<?=maybePrefixZero($reqJumlahNilai2)?>" readonly="readonly" />
                  </div>
                  <div class="input-field col s12 m3 offset-m1">
                    <label for="reqRataNilai">Nilai Rata - Rata</label>
                    <input type="text" name="reqRataNilai2" id="reqRataNilai2" value="<?=maybePrefixZero($reqRataNilai2)?>" readonly="readonly" />
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col s12 m10 offset-m2">
                  <div class="input-field col s12 m3 offset-m3">
                    <label for="reqPerilakuNilai">Nilai Perilaku Kerja</label>
                    <input type="text" name="reqPerilakuNilai" id="reqPerilakuNilai" value="<?=maybePrefixZero($reqPerilakuNilai)?>" readonly="readonly" />
                  </div>
                  <div class="input-field col s12 m3">
                    <label for="reqPerilakuHasil" class="active">Jumlah</label>
                    <input type="text" name="reqPerilakuHasil" id="reqPerilakuHasil" value="<?=maybePrefixZero($reqPerilakuHasil)?>" readonly="readonly"/>
                  </div>
                </div>
              </div>
-->

              <div class="row">
                <div class="input-field col s12 m1">
                  5.
                </div>
                <div class="input-field col s12 m5">
                  <label for="reqKeberatan">KEBERATAN DARI PEGAWAI NEGERI SIPIL YANG DINILAI (APABILA ADA)</label>
                  <textarea class="materialize-textarea" id="reqKeberatan" name="reqKeberatan" <?=$read?> cols="70" rows="3"><?=$reqKeberatan?></textarea>
                </div>
                <div class="input-field col s12 m1">
                  6.
                </div>
                <div class="input-field col s12 m5">
                  <label for="reqTanggapan">TANGGAPAN PEJABAT PENILAI ATAS KEBERATAN</label>
                  <textarea class="materialize-textarea" id="reqTanggapan" name="reqTanggapan"  <?=$read?> cols="70" rows="3"><?=$reqTanggapan?></textarea>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m5 offset-m1">
                  <label for="reqTanggalKeberatan">Tanggal</label>
                  <input placeholder="" class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalKeberatan" id="reqTanggalKeberatan"  value="<?=$reqTanggalKeberatan?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalKeberatan');"/>
                </div>
                <div class="input-field col s12 m5 offset-m1">
                  <label for="reqTanggalTanggapan">Tanggal</label>
                  <input placeholder="" class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalTanggapan" id="reqTanggalTanggapan"  value="<?=$reqTanggalTanggapan?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalTanggapan');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1">
                  7.
                </div>
                <div class="input-field col s12 m5">
                  <label for="reqKeputusan">KEPUTUSAN ATASAN PEJABAT PENILAI ATAS KEBERATAN</label>
                  <textarea name="reqKeputusan" id="reqKeputusan"  class="materialize-textarea" <?=$read?> cols="70" rows="3"><?=$reqKeputusan?></textarea>
                </div>
                <div class="input-field col s12 m1">
                  8.
                </div>
                <div class="input-field col s12 m5">
                  <label for="reqRekomendasi">REKOMENDASI</label>
                  <textarea name="reqRekomendasi" id="reqRekomendasi" class="materialize-textarea" <?=$read?> cols="70" rows="3"><?=$reqRekomendasi?></textarea>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m5 offset-m1">
                  <label for="reqTanggalKeputusan">Tanggal</label>
                  <input placeholder="" class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalKeputusan" id="reqTanggalKeputusan"  value="<?=$reqTanggalKeputusan?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalKeputusan');"/>
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
                      vkembali= "app/loadUrl/app/pegawai_add_skp_monitoring?reqId=<?=$reqId?>&pelayananid=<?=$pelayananid?>&pelayananjenis=<?=$pelayananjenis?>&pelayananrowid=<?=$pelayananrowid?>&pelayanankembali=<?=$pelayanankembali?>";
                      <?
                      }
                      else
                      {
                      ?>
                      vkembali= "app/loadUrl/app/pegawai_add_skp_monitoring?reqId=<?=$reqId?>";
                      <?
                      }
                      ?>
                      document.location.href = vkembali;
                    });
                  </script>

                  <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                  <input type="hidden" name="reqId" value="<?=$reqId?>" />
                  <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                  
                  <input type="hidden" name="reqNilaiHasilKerja" value="<?=$reqNilaiHasilKerja?>" />
                  <input type="hidden" name="reqNilaiHasilPerilaku" value="<?=$reqNilaiHasilPerilaku?>" />


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

              <div class="row">
                <div class="input-field col s12 m12">
                <?
                // area untuk upload file
                foreach ($arrsetriwayatfield as $key => $value)
                {
                  $riwayatfield= $value["riwayatfield"];
                  $riwayatfieldtipe= $value["riwayatfieldtipe"];
                  $riwayatfieldinfo= $value["riwayatfieldinfo"];
                  $riwayatfieldstyle= $value["riwayatfieldstyle"];
                  // echo $riwayatfieldstyle;exit;
                ?>
                  <button class="btn blue waves-effect waves-light" style="font-size:9pt;<?=$riwayatfieldstyle?>" type="button" id='buttonframepdf<?=$riwayatfield?>'>
                    <input type="hidden" id="labelvpdf<?=$riwayatfield?>" value="<?=$riwayatfieldinfo?>" />
                    <span id="labelframepdf<?=$riwayatfield?>"><?=$riwayatfieldinfo?></span>
                  </button>
                <?
                }
                ?>
                </div>
              </div>

              <div class="row"><div class="col s12 m12"><br/></div></div>

              <?
              // area untuk upload file
              foreach ($arrsetriwayatfield as $key => $value)
              {
                $riwayatfield= $value["riwayatfield"];
                $riwayatfieldtipe= $value["riwayatfieldtipe"];
                $vriwayatfieldinfo= $value["riwayatfieldinfo"];
                $riwayatfieldinfo= " - ".$vriwayatfieldinfo;
                $riwayatfieldrequired= $value["riwayatfieldrequired"];
                $riwayatfieldrequiredinfo= $value["riwayatfieldrequiredinfo"];
                $vriwayattable= $value["vriwayattable"];
                $vriwayatid= $vriwayatfield= "";
                $vpegawairowfile= $reqDokumenKategoriFileId."-".$vriwayattable."-".$vriwayatfield."-".$vriwayatid;
              ?>
              <div class="row">
                <div class="input-field col s12 m4">
                  <input type="hidden" id="reqDokumenRequired<?=$riwayatfield?>" name="reqDokumenRequired[]" value="<?=$riwayatfieldrequired?>" />
                  <input type="hidden" id="reqDokumenRequiredNama<?=$riwayatfield?>" name="reqDokumenRequiredNama[]" value="<?=$vriwayatfieldinfo?>" />
                  <input type="hidden" id="reqDokumenRequiredTable<?=$riwayatfield?>" name="reqDokumenRequiredTable[]" value="<?=$vriwayattable?>" />
                  <input type="hidden" id="reqDokumenRequiredTableRow<?=$riwayatfield?>" name="reqDokumenRequiredTableRow[]" value="<?=$vpegawairowfile?>" />
                  <input type="hidden" id="reqDokumenFileId<?=$riwayatfield?>" name="reqDokumenFileId[]" />
                  <input type="hidden" id="reqDokumenKategoriFileId<?=$riwayatfield?>" name="reqDokumenKategoriFileId[]" value="<?=$reqDokumenKategoriFileId?>" />
                  <input type="hidden" id="reqDokumenKategoriField<?=$riwayatfield?>" name="reqDokumenKategoriField[]" value="<?=$riwayatfield?>" />
                  <input type="hidden" id="reqDokumenPath<?=$riwayatfield?>" name="reqDokumenPath[]" value="" />
                  <input type="hidden" id="reqDokumenTipe<?=$riwayatfield?>" name="reqDokumenTipe[]" value="<?=$riwayatfieldtipe?>" />

                  <select id="reqDokumenPilih<?=$riwayatfield?>" name="reqDokumenPilih[]">
                    <?
                    foreach ($arrpilihfiledokumen as $key => $value)
                    {
                      $optionid= $value["id"];
                      $optiontext= $value["nama"];
                    ?>
                      <option value="<?=$optionid?>" <? if($reqDokumenPilih[$riwayatfield] == $optionid) echo "selected";?>><?=$optiontext?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqDokumenPilih<?=$riwayatfield?>">
                    File Dokumen<?=$riwayatfieldinfo?>
                    <span id="riwayatfieldrequiredinfo<?=$riwayatfield?>" style="color: red;"><?=$riwayatfieldrequiredinfo?></span>
                  </label>
                </div>

                <div class="input-field col s12 m4">
                  <select <?=$disabled?> name="reqDokumenFileKualitasId[]" id="reqDokumenFileKualitasId<?=$riwayatfield?>">
                    <option value=""></option>
                    <?
                    foreach ($arrkualitasfile as $key => $value)
                    {
                      $optionid= $value["ID"];
                      $optiontext= $value["TEXT"];
                      $optionselected= "";
                      if($reqDokumenFileKualitasId == $optionid)
                        $optionselected= "selected";

                      $arrkecualitipe= [];
                      $arrkecualitipe= $vfpeg->kondisikategori($riwayatfieldtipe);
                      if(!in_array($optionid, $arrkecualitipe))
                        continue;
                    ?>
                      <option value="<?=$optionid?>" <?=$optionselected?>><?=$optiontext?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqDokumenFileKualitasId<?=$riwayatfield?>">Kualitas Dokumen<?=$riwayatfieldinfo?></label>
                </div>

                <div id="labeldokumenfileupload<?=$riwayatfield?>" class="input-field col s12 m4" style="margin-top: -25px; margin-bottom: 10px;">
                  <div class="file_input_div">
                    <div class="file_input input-field col s12 m4">
                      <label class="labelupload">
                        <i class="mdi-file-file-upload" style="font-family:Roboto,sans-serif,Material-Design-Icons !important; font-size: 14px !important;">Upload</i>
                        <input id="file_input_file" name="reqLinkFile[]" class="none" type="file" />
                      </label>
                    </div>
                    <div id="file_input_text_div" class=" input-field col s12 m8">
                      <input class="file_input_text" type="text" disabled readonly id="file_input_text" />
                      <label for="file_input_text"></label>
                    </div>
                  </div>
                </div>

                <div id="labeldokumendarifileupload<?=$riwayatfield?>" class="input-field col s12 m4">
                  <select id="reqDokumenIndexId<?=$riwayatfield?>" name="reqDokumenIndexId[]">
                    <option value="" selected></option>
                    <?
                    $arrlistpilihfilepegawai= $arrlistpilihfilefield[$riwayatfield];
                    foreach ($arrlistpilihfilepegawai as $key => $value)
                    {
                      $optionid= $value["index"];
                      $optiontext= $value["nama"];
                      $optionselected= $value["selected"];
                    ?>
                      <option value="<?=$optionid?>" <?=$optionselected?>><?=$optiontext?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqDokumenIndexId<?=$riwayatfield?>">Nama e-File<?=$riwayatfieldinfo?></label>
                </div>

              </div>
              <?
              }
              // area untuk upload file
              ?>

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