<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/personal.func.php");


$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('validasi/PenilaianSkp');
$this->load->model('Pegawai');

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

$arrstatusvalidasi= [];
$arrinfocombo= [];
$arrinfocombo= array(
  array("id"=>"1", "text"=>"Valid")
  , array("id"=>"2", "text"=>"Ditolak")
);
for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
{
  $arrdata= [];
  $arrdata["id"]= $arrinfocombo[$icombo]["id"];
  $arrdata["text"]= $arrinfocombo[$icombo]["text"];
  array_push($arrstatusvalidasi, $arrdata);
}
 
$pegawai_dinilai->selectByParams(array("A.PEGAWAI_ID" => $reqId)); 
$pegawai_dinilai->firstRow();
//echo $pegawai_dinilai->query;exit;

$reqPenilaianSkpDinilaiNama = $pegawai_dinilai->getField('NAMA_LENGKAP');
$reqPenilaianSkpDinilaiNip = $pegawai_dinilai->getField('NIP_BARU');
$reqSatkerAtasan = $pegawai_dinilai->getField('SATKER_ID_ATASAN');

$statement= "";
$set= new PenilaianSkp();

$infoperubahan= "Perubahan Data";
if(!empty($reqRowHapusId))
{
  $infoperubahan= "Hapus Data";
  $set->selectByPersonal(array(), -1, -1, $reqId, $reqRowHapusId, "", $statement);
}
else
  $set->selectByPersonal(array(), -1, -1, $reqId, $reqRowHapusId, $reqRowId, $statement);

// echo $set->query;exit;
$set->firstRow();
$reqTempValidasiId= $set->getField('TEMP_VALIDASI_ID');
$reqTempValidasiHapusId= $set->getField('TEMP_VALIDASI_HAPUS_ID');
$reqValidasi= $set->getField('VALIDASI');
$reqPerubahanData= $set->getField('PERUBAHAN_DATA');

$reqValRowId= $set->getField('PENILAIAN_SKP_ID');
// echo  $reqValRowId;exit;
if(empty($reqValRowId))
{
  $infoperubahan= "Data Baru";
}

$reqTahun = $set->getField('TAHUN');$valTahun = checkwarna($reqPerubahanData, 'TAHUN');
$reqPenilaianSkpPenilaiId = $set->getField("PEGAWAI_PEJABAT_PENILAI_ID");
$reqPenilaianSkpPenilaiNama = $set->getField('PEGAWAI_PEJABAT_PENILAI_NAMA');$valPenilaianSkpPenilaiNama= checkwarna($reqPerubahanData, 'PEGAWAI_PEJABAT_PENILAI_NAMA');
$reqPenilaianSkpPenilaiNip = $set->getField('PEGAWAI_PEJABAT_PENILAI_NIP');$valPenilaianSkpPenilaiNip= checkwarna($reqPerubahanData, 'PEGAWAI_PEJABAT_PENILAI_NIP');
$reqPenilaianSkpAtasanId = $set->getField("PEGAWAI_ATASAN_PEJABAT_ID");
$reqPenilaianSkpAtasanNama = $set->getField('PEGAWAI_ATASAN_PEJABAT_NAMA');$valPenilaianSkpAtasanNama= checkwarna($reqPerubahanData, 'PEGAWAI_ATASAN_PEJABAT_NAMA');
$reqPenilaianSkpAtasanNip = $set->getField('PEGAWAI_ATASAN_PEJABAT_NIP');$valPenilaianSkpAtasanNip= checkwarna($reqPerubahanData, 'PEGAWAI_ATASAN_PEJABAT_NIP');
$reqSkpNilai = dotToComma($set->getField('SKP_NILAI'));$valSkpNilai = checkwarna($reqPerubahanData, 'SKP_NILAI');
$reqSkpHasil = dotToComma($set->getField('SKP_HASIL'));$valSkpHasil = checkwarna($reqPerubahanData, 'SKP_HASIL');
$reqOrientasiNilai = dotToComma($set->getField('ORIENTASI_NILAI'));$valOrientasiNilai = checkwarna($reqPerubahanData, 'ORIENTASI_NILAI'); 
$reqIntegritasNilai = dotToComma($set->getField('INTEGRITAS_NILAI'));$valIntegritasNilai = checkwarna($reqPerubahanData, 'INTEGRITAS_NILAI'); 
$reqKomitmenNilai = dotToComma($set->getField('KOMITMEN_NILAI'));$valKomitmenNilai = checkwarna($reqPerubahanData, 'KOMITMEN_NILAI');  
$reqDisiplinNilai = dotToComma($set->getField('DISIPLIN_NILAI'));$valDisiplinNilai = checkwarna($reqPerubahanData, 'DISIPLIN_NILAI');
$reqKerjasamaNilai = dotToComma($set->getField('KERJASAMA_NILAI'));$valKerjasamaNilai = checkwarna($reqPerubahanData, 'KERJASAMA_NILAI'); 
$reqKepemimpinanNilai = dotToComma($set->getField('KEPEMIMPINAN_NILAI'));$valKepemimpinanNilai = checkwarna($reqPerubahanData, 'KEPEMIMPINAN_NILAI');  
$reqJumlahNilai= dotToComma(setLebihNol($set->getField('JUMLAH_NILAI')));$valJumlahNilai = checkwarna($reqPerubahanData, 'JUMLAH_NILAI'); 
$reqRataNilai= dotToComma(setLebihNol($set->getField('RATA_NILAI')));$valRataNilai = checkwarna($reqPerubahanData, 'RATA_NILAI'); 
$reqPerilakuNilai= dotToComma(setLebihNol($set->getField('PERILAKU_NILAI')));$valPerilakuNilai = checkwarna($reqPerubahanData, 'PERILAKU_NILAI');  
$reqPerilakuHasil= dotToComma(setLebihNol($set->getField('PERILAKU_HASIL')));$valPerilakuHasil = checkwarna($reqPerubahanData, 'PERILAKU_HASIL');
$reqPrestasiNilai = $set->getField('PRESTASI_NILAI');$valPrestasiNilai = checkwarna($reqPerubahanData, 'PRESTASI_NILAI'); 
$reqPrestasiHasil= dotToComma(setLebihNol($set->getField('PRESTASI_HASIL')));$valPrestasiHasil = checkwarna($reqPerubahanData, 'PRESTASI_HASIL');  
$reqKeberatan = $set->getField('KEBERATAN');$valKeberatan = checkwarna($reqPerubahanData, 'KEBERATAN');
$reqTanggalKeberatan = dateToPageCheck($set->getField('KEBERATAN_TANGGAL'));$valTanggalKeberatan = checkwarna($reqPerubahanData, 'KEBERATAN_TANGGAL', "date"); 
$reqTanggapan = $set->getField('TANGGAPAN');$valTanggapan = checkwarna($reqPerubahanData, 'TANGGAPAN'); 
$reqTanggalTanggapan = dateToPageCheck($set->getField('TANGGAPAN_TANGGAL'));$valTanggalTanggapan=checkwarna($reqPerubahanData, 'TANGGAPAN_TANGGAL', "date");
$reqKeputusan = $set->getField('KEPUTUSAN');$valKeputusan = checkwarna($reqPerubahanData, 'KEPUTUSAN'); 
$reqTanggalKeputusan = dateToPageCheck($set->getField('KEPUTUSAN_TANGGAL'));$valTanggalKeputusan = checkwarna($reqPerubahanData, 'KEPUTUSAN_TANGGAL', "date");
$reqRekomendasi = $set->getField('REKOMENDASI');$valRekomendasi = checkwarna($reqPerubahanData, 'REKOMENDASI'); 

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

    function setpejabatpenilaicetang()
    {
      // reqPenilaianSkpPenilaiId;reqPenilaianSkpPenilaiNip;reqPenilaianSkpPenilaiNama;color-disb
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

    $(function(){

      <?
      if($reqTempValidasiId == 'insert')
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
      }
      ?>

      $("#reqPejabatPenilaiIsManual").click(function () {
        setpejabatpenilaicetang();
      });

      $("#reqAtasanPejabatPenilaiIsManual").click(function () {
        setatasanpejabatpenilaicetang();
      });

    $(".preloader-wrapper").hide();

     $('#ff').form({
      url:'validasi/penilaian_skp_json/add',
      onSubmit:function(){
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
              document.location.href= "app/loadUrl/verifikasi/validasi_verifikator/";
            }, 1000));
            $(".mbox > .right-align").css({"display": "none"});
          }
        }
      });

      $('input[id^="reqPenilaianSkpPenilaiNip"], input[id^="reqPenilaianSkpAtasanNip"]').each(function(){
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

          if (id.indexOf('reqPenilaianSkpPenilaiNip') !== -1)
          {
            $("#reqPenilaianSkpPenilaiId").val(idrow);
            $("#reqPenilaianSkpPenilaiNama").val(namapegawai);
          }
          else if (id.indexOf('reqPenilaianSkpAtasanNip') !== -1)
          {
            $("#reqPenilaianSkpAtasanId").val(idrow);
            $("#reqPenilaianSkpAtasanNama").val(namapegawai);
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

//alert(data);return false;

  // });
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

<link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">


</head>

<body>    
  <!--Basic Form-->
  <div id="basic-form" class="section">
    <div class="row">
     <div class="col s12 m10 offset-m1">

       <ul class="collection card">
         <li class="collection-item ubah-color-warna">EDIT PENILAIAN SKP / PPK</li>
         <li class="collection-item">

          <form id="ff" method="post" enctype="multipart/form-data">
            <div class="row">
              <div class="input-field col s12 m6">
                <label  class="<?=$valTahun['warna']?>">
                  Tahun
                  <?
                  if(!empty($valTahun['data']))
                  {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTahun['data']?></span>
                    </a>
                    <?
                  }
                  ?>
                </label>
                <input type="text" class="easyui-validatebox" required id="reqTahun" name="reqTahun" <?=$read?> value="<?=$reqTahun?>"/>
             
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
                <label for="reqPenilaianSkpDinilaiNama" class="<?=$valPenilaianSkpDinilaiNama['warna']?>">
                  NAMA
                  <?
                  if(!empty($valPenilaianSkpDinilaiNama['data']))
                  {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valPenilaianSkpDinilaiNama['data']?></span>
                    </a>
                    <?
                  }
                  ?></label>
                <input type="text" id="reqPenilaianSkpDinilaiNama" name="reqPenilaianSkpDinilaiNama" readonly="readonly" value="<?=$reqPenilaianSkpDinilaiNama?>"/>
              </div>
            </div>
            
            <div class="row">
              <div class="input-field col s12 m1 offset-m1">
                b.
              </div>
              <div class="input-field col s12 m6">
                <label for="reqPenilaianSkpDinilaiNip" class="<?=$valPenilaianSkpDinilaiNip['warna']?>">
                  NIP
                  <?
                  if(!empty($valPenilaianSkpDinilaiNip['data']))
                  {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valPenilaianSkpDinilaiNip['data']?></span>
                    </a>
                    <?
                  }
                  ?>
                </label>
                <input type="text" id="reqPenilaianSkpDinilaiNip" name="reqPenilaianSkpDinilaiNip" readonly="readonly" value="<?=$reqPenilaianSkpDinilaiNip?>"/>
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
                <label for="reqPenilaianSkpPenilaiNip" class="<?=$valPenilaianSkpPenilaiNip['warna']?>"> 
                  NIP
                  <?
                  if(!empty($valPenilaianSkpPenilaiNip['data']))
                  {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valPenilaianSkpPenilaiNip['data']?></span>
                    </a>
                    <?
                  }
                  ?>
                  </label>
                <input placeholder type="text" class="easyui-validatebox" required id="reqPenilaianSkpPenilaiNip" name="reqPenilaianSkpPenilaiNip" <?=$read?> value="<?=$reqPenilaianSkpPenilaiNip?>"/>
              </div>
            </div>
            
            <div class="row">
              <div class="input-field col s12 m1 offset-m1">
                b.
              </div>
              <div class="input-field col s12 m6">
                <label for="reqPenilaianSkpPenilaiNama" class="<?=$valPenilaianSkpPenilaiNama['warna']?>"> 
                  NAMA
                  <?
                  if(!empty($valPenilaianSkpPenilaiNama['data']))
                  {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valPenilaianSkpPenilaiNama['data']?></span>
                    </a>
                    <?
                  }
                  ?>
                  </label>
                <input placeholder type="text" class="easyui-validatebox color-disb" id="reqPenilaianSkpPenilaiNama" name="reqPenilaianSkpPenilaiNama" value="<?=$reqPenilaianSkpPenilaiNama?>" readonly />
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
                <label for="reqPenilaianSkpAtasanNip" class="<?=$valPenilaianSkpAtasanNip['warna']?>">
                  NIP
                  <?
                  if(!empty($valPenilaianSkpAtasanNip['data']))
                  {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valPenilaianSkpAtasanNip['data']?></span>
                    </a>
                    <?
                  }
                  ?>
                </label>
                <input placeholder type="text" class="easyui-validatebox" required id="reqPenilaianSkpAtasanNip"  name="reqPenilaianSkpAtasanNip" <?=$read?> value="<?=$reqPenilaianSkpAtasanNip?>"/>
              </div>
            </div>
            
            <div class="row">
              <div class="input-field col s12 m1 offset-m1">
                b.
              </div>
              <div class="input-field col s12 m6">
                <label for="reqPenilaianSkpAtasanNama" class="<?=$valPenilaianSkpAtasanNama['warna']?>"> 
                  NAMA
                  <?
                  if(!empty($valPenilaianSkpAtasanNama['data']))
                  {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valPenilaianSkpAtasanNama['data']?></span>
                    </a>
                    <?
                  }
                  ?>
                </label>
                <input placeholder type="text" class="color-disb" id="reqPenilaianSkpAtasanNama" name="reqPenilaianSkpAtasanNama" value="<?=$reqPenilaianSkpAtasanNama?>" readonly />
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
                  <label for="reqSkpNilai" class="<?=$valSkpNilai['warna']?>"> 
                    Sasaran Kerja Penilaian Skp
                    <?
                    if(!empty($valSkpNilai['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valSkpNilai['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  <input type="text" class="easyui-validatebox" required id="reqSkpNilai" name="reqSkpNilai" <?=$read?> value="<?=$reqSkpNilai?>" onkeypress='kreditvalidate(event, this)' />
                </div>
                <div class="input-field col s12 m3">
                  <label for="reqSkpHasil">Jumlah</label>
                  <input type="text" placeholder name="reqSkpHasil" id="reqSkpHasil" value="<?=maybePrefixZero($reqSkpHasil)?>" readonly="readonly"/>
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
                <label for="reqOrientasiNilai" class="<?=$valOrientasiNilai['warna']?>">
                  Orientasi Pelayanan
                  <?
                  if(!empty($valOrientasiNilai['data']))
                  {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valOrientasiNilai['data']?></span>
                    </a>
                    <?
                  }
                  ?>
                <input type="text" class="easyui-validatebox" required id="reqOrientasiNilai" name="reqOrientasiNilai" <?=$read?> value="<?=$reqOrientasiNilai?>" onkeypress='kreditvalidate(event, this)' />
              </div>
              <div class="input-field col s12 m3 offset-m3">
                <label for="reqIntegritasNilai" class="<?=$valIntegritasNilai['warna']?>">Integritas
                  <?
                  if(!empty($valIntegritasNilai['data']))
                  {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valIntegritasNilai['data']?></span>
                    </a>
                    <?
                  }
                  ?>
                </label>
                <input type="text" class="easyui-validatebox" required id="reqIntegritasNilai" name="reqIntegritasNilai" <?=$read?> value="<?=$reqIntegritasNilai?>" onkeypress='kreditvalidate(event, this)' />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col s12 m10 offset-m2">
              <div class="input-field col s12 m3 offset-m3">
                <label for="reqKomitmenNilai" class="<?=$valKomitmenNilai['warna']?>">
                  Komitmen
                  <?
                  if(!empty($valKomitmenNilai['data']))
                  {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valKomitmenNilai['data']?></span>
                    </a>
                    <?
                  }
                  ?>
                </label>
                <input type="text" class="easyui-validatebox" required id="reqKomitmenNilai" name="reqKomitmenNilai" <?=$read?> value="<?=$reqKomitmenNilai?>" onkeypress='kreditvalidate(event, this)' />
              </div>
              <div class="input-field col s12 m3 offset-m3">
                <label for="reqDisiplinNilai" class="<?=$valDisiplinNilai['warna']?>">
                  Disiplin
                  <?
                  if(!empty($valDisiplinNilai['data']))
                  {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valDisiplinNilai['data']?></span>
                    </a>
                    <?
                  }
                  ?>
                </label>
                <input type="text" class="easyui-validatebox" required id="reqDisiplinNilai" name="reqDisiplinNilai" <?=$read?> value="<?=$reqDisiplinNilai?>" onkeypress='kreditvalidate(event, this)' />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col s12 m10 offset-m2">
              <div class="input-field col s12 m3 offset-m3">
                <label for="reqKerjasamaNilai" class="<?=$valKerjasamaNilai['warna']?>">Kerjasama
                  <?
                  if(!empty($valKerjasamaNilai['data']))
                  {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valKerjasamaNilai['data']?></span>
                    </a>
                    <?
                  }
                  ?>
                </label>
                <input type="text" class="easyui-validatebox" required id="reqKerjasamaNilai" name="reqKerjasamaNilai" <?=$read?> value="<?=$reqKerjasamaNilai?>" onkeypress='kreditvalidate(event, this)' />
              </div>
              <div class="input-field col s12 m3 offset-m3">
                <label for="reqKepemimpinanNilai" class="<?=$valKepemimpinanNilai['warna']?>"> Kepemimpinan
                  <?
                  if(!empty($valKepemimpinanNilai['data']))
                  {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valKepemimpinanNilai['data']?></span>
                    </a>
                    <?
                  }
                  ?>
                </label>
                <input type="text" class="easyui-validatebox" id="reqKepemimpinanNilai" name="reqKepemimpinanNilai" <?=$read?> value="<?=$reqKepemimpinanNilai?>" onkeypress='kreditvalidate(event, this)' />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col s12 m10 offset-m2">
              <div class="input-field col s12 m3 offset-m3">
                <label for="reqJumlahNilai" class="<?=$valJumlahNilai['warna']?>">Jumlah
                  <?
                  if(!empty($valJumlahNilai['data']))
                  {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valJumlahNilai['data']?></span>
                    </a>
                    <?
                  }
                  ?>
                </label>
                <input type="text" name="reqJumlahNilai" id="reqJumlahNilai" value="<?=maybePrefixZero($reqJumlahNilai)?>" readonly="readonly" />
              </div>
              <div class="input-field col s12 m3 offset-m3">
                <label for="reqRataNilai" class="<?=$valRataNilai['warna']?>">Nilai Rata - Rata
                  <?
                  if(!empty($valRataNilai['data']))
                  {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valRataNilai['data']?></span>
                    </a>
                    <?
                  }
                  ?></label>
                <input type="text" name="reqRataNilai" id="reqRataNilai" value="<?=maybePrefixZero($reqRataNilai)?>" readonly="readonly" />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col s12 m10 offset-m2">
              <div class="input-field col s12 m3 offset-m3">
                <label for="reqPerilakuNilai" class="<?=$valPerilakuNilai['warna']?>">Nilai Perilaku Kerja
                  <?
                  if(!empty($valPerilakuNilai['data']))
                  {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valPerilakuNilai['data']?></span>
                    </a>
                    <?
                  }
                  ?>
                </label>
                <input type="text" name="reqPerilakuNilai" id="reqPerilakuNilai" value="<?=maybePrefixZero($reqPerilakuNilai)?>" readonly="readonly" />
              </div>
              <div class="input-field col s12 m3">
                <label for="reqPerilakuHasil" class="<?=$valPerilakuHasil['warna']?>" class="active">Jumlah
                  <?
                  if(!empty($valPerilakuHasil['data']))
                  {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valPerilakuHasil['data']?></span>
                    </a>
                    <?
                  }
                  ?>
                </label>
                <input type="text" name="reqPerilakuHasil" id="reqPerilakuHasil" value="<?=maybePrefixZero($reqPerilakuHasil)?>" readonly="readonly"/>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col s12 m10 offset-m2">
              <div class="input-field col s12 m3 offset-m3">
                <label for="reqPrestasiHasil" class="<?=$valPrestasiHasil['warna']?>"> Nilai Prestasi Kerja
                  <?
                  if(!empty($valPrestasiHasil['data']))
                  {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valPrestasiHasil['data']?></span>
                    </a>
                    <?
                  }
                  ?>
                </label>
                <input type="text" name="reqPrestasiHasil" id="reqPrestasiHasil" value="<?=maybePrefixZero($reqPrestasiHasil)?>" readonly="readonly"/>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="input-field col s12 m1">
              5.
            </div>
            <div class="input-field col s12 m5">
              <label for="reqKeberatan" class="<?=$valKeberatan['warna']?>">KEBERATAN DARI PEGAWAI NEGERI SIPIL YANG DINILAI (APABILA ADA)
                <?
                if(!empty($valKeberatan['data']))
                {
                  ?>
                  <a class="tooltipe" href="javascript:void(0)">
                    <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valKeberatan['data']?></span>
                  </a>
                  <?
                }
                ?>
              </label>
              <textarea class="materialize-textarea" id="reqKeberatan" name="reqKeberatan" <?=$read?> cols="70" rows="3"><?=$reqKeberatan?></textarea>
            </div>
            <div class="input-field col s12 m1">
              6.
            </div>
            <div class="input-field col s12 m5">
              <label for="reqTanggapan" class="<?=$valTanggapan['warna']?>">TANGGAPAN PEJABAT PENILAI ATAS KEBERATAN
                <?
                if(!empty($valTanggapan['data']))
                {
                  ?>
                  <a class="tooltipe" href="javascript:void(0)">
                    <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTanggapan['data']?></span>
                  </a>
                  <?
                }
                ?>
                </label>
              <textarea class="materialize-textarea" id="reqTanggapan" name="reqTanggapan"  <?=$read?> cols="70" rows="3"><?=$reqTanggapan?></textarea>
            </div>
          </div>

          <div class="row">
            <div class="input-field col s12 m5 offset-m1">
              <label for="reqTanggalKeberatan" class="<?=$valTanggalKeberatan['warna']?>">Tanggal
                <?
                if(!empty($valTanggalKeberatan['data']))
                {
                  ?>
                  <a class="tooltipe" href="javascript:void(0)">
                    <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTanggalKeberatan['data']?></span>
                  </a>
                  <?
                }
                ?>
              </label>
              <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalKeberatan" id="reqTanggalKeberatan"  value="<?=$reqTanggalKeberatan?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalKeberatan');"/>
            </div>
            <div class="input-field col s12 m5 offset-m1">
              <label for="reqTanggalTanggapan" class="<?=$valTanggalTanggapan['warna']?>">Tanggal
                <?
                if(!empty($valTanggalTanggapan['data']))
                {
                  ?>
                  <a class="tooltipe" href="javascript:void(0)">
                    <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTanggalTanggapan['data']?></span>
                  </a>
                  <?
                }
                ?>
              </label>
              <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalTanggapan" id="reqTanggalTanggapan"  value="<?=$reqTanggalTanggapan?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalTanggapan');"/>
            </div>
          </div>

          <div class="row">
            <div class="input-field col s12 m1">
              7.
            </div>
            <div class="input-field col s12 m5">
              <label for="reqKeputusan" class="<?=$valKeputusan['warna']?>">KEPUTUSAN ATASAN PEJABAT PENILAI ATAS KEBERATAN
                <?
                if(!empty($valKeputusan['data']))
                {
                  ?>
                 <a class="tooltipe" href="javascript:void(0)">
                    <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valKeputusan['data']?></span>
                  </a>
                  <?
                }
                ?>
              </label>
              <textarea name="reqKeputusan" id="reqKeputusan"  class="materialize-textarea" <?=$read?> cols="70" rows="3"><?=$reqKeputusan?></textarea>
            </div>
            <div class="input-field col s12 m1">
              8.
            </div>
            <div class="input-field col s12 m5">
              <label for="reqRekomendasi" class="<?=$valRekomendasi['warna']?>">REKOMENDASI
                <?
                if(!empty($valRekomendasi['data']))
                {
                  ?>
                  <a class="tooltipe" href="javascript:void(0)">
                    <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valRekomendasi['data']?></span>
                  </a>
                  <?
                }
                ?>
                </label>
              <textarea name="reqRekomendasi" id="reqRekomendasi" class="materialize-textarea" <?=$read?> cols="70" rows="3"><?=$reqRekomendasi?></textarea>
            </div>
          </div>

          <div class="row">
            <div class="input-field col s12 m5 offset-m1">
              <label for="reqTanggalKeputusan" class="<?=$valTanggalKeputusan['warna']?>">Tanggal 
                <?
                if(!empty($valTanggalKeputusan['data']))
                {
                  ?>
                  <a class="tooltipe" href="javascript:void(0)">
                    <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTanggalKeputusan['data']?></span>
                  </a>
                  <?
                }
                ?>
            </label>
              <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalKeputusan" id="reqTanggalKeputusan"  value="<?=$reqTanggalKeputusan?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalKeputusan');"/>
            </div>
          </div>

          <div class="row">
            <div class="input-field col s12 m12">
              <select <?=$disabled?> name="reqStatusValidasi" id="reqStatusValidasi">
                <option value="" <? if($reqStatusSkCpns == "") echo 'selected';?>></option>
                <?
                foreach($arrstatusvalidasi as $item) 
                {
                  $selectvalid= $item["id"];
                  $selectvaltext= $item["text"];
                  ?>
                  <option value="<?=$selectvalid?>" <? if($reqStatusValidasi == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                  <?
                }
                ?>
              </select>
              <label for="reqStatusValidasi">Status Klarifikasi</label>
            </div>
          </div>


          <div class="row">
            <div class="input-field col s12">
              <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
              </button>

              <script type="text/javascript">
                $("#kembali").click(function() { 
                  document.location.href = "app/loadUrl/verifikasi/validasi_verifikator";
                });
              </script>

              <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
              <input type="hidden" name="reqId" value="<?=$reqId?>" />
              <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
              <input type="hidden" name="reqTempValidasiId" value="<?=$reqTempValidasiId?>" />
              <input type="hidden" name="reqTempValidasiHapusId" value="<?=$reqTempValidasiHapusId?>" />
              <?
              // A;R;D
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
          <!-- </div> -->
        </form>
      </li>
    </ul>
  </div>
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

<script type="text/javascript">
	$('#reqTahun').bind('keyup paste', function(){
		this.value = this.value.replace(/[^0-9]/g, '');
	});
	
 //  	$("#reqSkpNilai, #reqOrientasiNilai, #reqDisiplinNilai, #reqIntegritasNilai, #reqKerjasamaNilai, #reqKomitmenNilai, #reqKepemimpinanNilai").keypress(function(e) {
	// 	//alert(e.which);
	// 	if( e.which!=8 && e.which!=0 && e.which!=46 && (e.which<48 || e.which>57))
	// 	{
	// 	return false;
	// 	}
	// });
	
	
	$('#reqSkpNilai, #reqOrientasiNilai, #reqDisiplinNilai, #reqIntegritasNilai, #reqKerjasamaNilai, #reqKomitmenNilai, #reqKepemimpinanNilai').keyup(function() {

    var valueSkpHasil = '';

    valueSkpHasil= Number(String(getNominal($("#reqSkpNilai").val())))*0.6;
    valueSkpHasil = roundToTwo(valueSkpHasil);
    $("#reqSkpHasil").val(setNominal(valueSkpHasil)); 
    
    var valueParse1 = '';
    valueParse1= Number(String(getNominal($("#reqOrientasiNilai").val())))+Number(String(getNominal($("#reqDisiplinNilai").val())))+Number(String(getNominal($("#reqIntegritasNilai").val())))+
          Number(String(getNominal($("#reqKerjasamaNilai").val())))+Number(String(getNominal($("#reqKomitmenNilai").val())))+Number(String(getNominal($("#reqKepemimpinanNilai").val())));
    //alert(myRound(valueParse1, 2));
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

		// var valueSkpHasil = '';
		// valueSkpHasil= Number(String($("#reqSkpNilai").val()))*0.6;
		// valueSkpHasil = roundToTwo(valueSkpHasil);
		// $("#reqSkpHasil").val(valueSkpHasil);	
		
		// var valueParse1 = '';
		// valueParse1= Number(String($("#reqOrientasiNilai").val()))+Number(String($("#reqDisiplinNilai").val()))+Number(String($("#reqIntegritasNilai").val()))+
		// 			Number(String($("#reqKerjasamaNilai").val()))+Number(String($("#reqKomitmenNilai").val()))+Number(String($("#reqKepemimpinanNilai").val()));
		// //alert(myRound(valueParse1, 2));
		// valueParse1 = roundToTwo(valueParse1);
		// $("#reqJumlahNilai").val(valueParse1);
		
		// if($("#reqKepemimpinanNilai").val()=="")
		// {
		// 	valueRata=(valueParse1/5);
		// 	valueRata = roundToTwo(valueRata);
		// 	valuePerilakuHasil=valueRata*0.4;	
		// 	valuePerilakuHasil = roundToTwo(valuePerilakuHasil);
		// 	valuePrestasiHasil=valuePerilakuHasil+valueSkpHasil;
		// 	valuePrestasiHasil = roundToTwo(valuePrestasiHasil);	
		// 	$("#reqRataNilai").val(valueRata);
		// 	$("#reqPerilakuNilai").val(valueRata);
		// 	$("#reqPerilakuHasil").val(valuePerilakuHasil);
		// 	$("#reqPrestasiHasil").val(valuePrestasiHasil);
		// }
		// else
		// {
		// 	valueRata=(valueParse1/6);	
		// 	valueRata = roundToTwo(valueRata);
		// 	valuePerilakuHasil=valueRata*0.4;	
		// 	valuePerilakuHasil = roundToTwo(valuePerilakuHasil);
		// 	valuePrestasiHasil=valuePerilakuHasil+valueSkpHasil;
		// 	valuePrestasiHasil = roundToTwo(valuePrestasiHasil);	
		// 	$("#reqRataNilai").val(valueRata);
		// 	$("#reqPerilakuNilai").val(valueRata);
		// 	$("#reqPerilakuHasil").val(valuePerilakuHasil);
		// 	$("#reqPrestasiHasil").val(valuePrestasiHasil);
		// }
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
  
</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>