<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('PangkatRiwayat');
$this->load->model('Pendidikan');
$this->load->model('PendidikanRiwayat');
$this->load->model('Rumpun');
$this->load->model('KualitasFile');
$this->load->library('globalfilepegawai');

$pelayananid= $this->input->get("pelayananid");
$pelayananjenis= $this->input->get("pelayananjenis");
$pelayananrowid= $this->input->get("pelayananrowid");
$pelayanankembali= $this->input->get("pelayanankembali");

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "0105";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

// kondisi untuk menu
$this->load->library('globalmenusapk');
$vmenusapk= new globalmenusapk();
$arrmenusapk= $vmenusapk->setmenusapk($tempMenuId);
// print_r($arrmenusapk);exit;
$lihatsapk= $arrmenusapk["lihat"];
$kirimsapk= $arrmenusapk["kirim"];
$tariksapk= $arrmenusapk["tarik"];
$syncsapk= $arrmenusapk["sync"];

$tempKondisiCpns= "";
$statement= " AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2') AND A.STATUS_PENDIDIKAN = '1' AND A.PEGAWAI_ID = ".$reqId;
$set= new PendidikanRiwayat();
$set->selectByParams(array(), -1, -1, $statement);
if($set->firstRow())
  $tempKondisiCpns= 1;
unset($set);
//echo $tempKondisiCpns;exit;

if($reqRowId=="")
{
  $reqMode = 'insert';
  $reqStatusPendidikan= 4;
}
else
{
  $reqMode = 'update';
  $statement= " AND A.PENDIDIKAN_RIWAYAT_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
  $set= new PendidikanRiwayat();
  $set->selectByParams(array(), -1, -1, $statement);
  // echo $set->query;exit();
  $set->firstRow();

  $reqRowId= $set->getField('PENDIDIKAN_RIWAYAT_ID');

  $reqNamaSekolah= $set->getField('NAMA');
  $reqNamaFakultas= $set->getField('NAMA_FAKULTAS');
  $reqPendidikanId= $set->getField('PENDIDIKAN_ID');
  $reqPendidikan= $set->getField('PENDIDIKAN_NAMA');
  $reqTglSttb= dateToPageCheck($set->getField('TANGGAL_STTB'));
  $reqJurusan= $set->getField('JURUSAN');
  $reqJurusanId= $set->getField('PENDIDIKAN_JURUSAN_ID');
  $reqAlamatSekolah= $set->getField('TEMPAT');
  $reqKepalaSekolah= $set->getField('KEPALA');
  $reqNoSttb= $set->getField('NO_STTB');
  // $reqPegawaiId= $set->getField('PEGAWAI_ID');
 
  $reqStatus= $set->getField('STATUS');
  $reqStatusTugasIjinBelajar= $set->getField('STATUS_TUGAS_IJIN_BELAJAR');
  $reqStatusPendidikan= $set->getField('STATUS_PENDIDIKAN');
  $reqStatusPendidikanNama= $set->getField('STATUS_PENDIDIKAN_NAMA');
  $reqNoSuratIjin= $set->getField('NO_SURAT_IJIN');
  $reqTglSuratIjin= dateToPageCheck($set->getField('TANGGAL_SURAT_IJIN'));
  $reqGelarTipe= $set->getField('GELAR_TIPE');
  $reqGelarNamaDepan= $set->getField('GELAR_DEPAN');
  $reqGelarNama= $set->getField('GELAR_NAMA');

  $reqPppkStatus= $set->getField('PPPK_STATUS');

  $reqSkDasarPengakuan= $set->getField('STATUS_SK_DASAR_PENGAKUAN');
  $reqCantumGelarTgl= dateToPageCheck($set->getField('CANTUM_GELAR_TANGGAL'));
  $reqCantumGelarNoSk= $set->getField('CANTUM_GELAR_NO_SK');
  $reqDasarPangkatRiwayatId= $set->getField('DASAR_PANGKAT_RIWAYAT_ID');
  $reqNilaiKualifikasi= $set->getField('NILAI_REKAM_JEJAK');
  $reqRumpunJabatan= $set->getField('RUMPUN_ID');
  $vidsapk= $set->getField("ID_SAPK");

  $reqRumpunJabatanNama= "";
  if(!empty($reqRumpunJabatan))
  {
    $setdetil= new Rumpun();
    $setdetil->selectByParams(array(), -1,-1, " AND A.RUMPUN_ID IN (".$reqRumpunJabatan.")");
    while($setdetil->nextRow())
    {
      if(empty($reqRumpunJabatanNama))
        $reqRumpunJabatanNama= $setdetil->getField("KETERANGAN");
      else
        $reqRumpunJabatanNama.= ", ".$setdetil->getField("KETERANGAN");
    }
  }
  // $reqRumpunJabatanNama= $set->getField('RUMPUN_NAMA');
}

if(empty($reqNilaiKualifikasi))
  $reqNilaiKualifikasi= 0;

// $pendidikan->selectByParams(array(), -1,-1);

$arrpendidikan=[];
$statement= "";
$set = new Pendidikan();
$set->selectByParams(array(), -1, -1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["ID"]= $set->getField("PENDIDIKAN_ID");
  $arrdata["TEXT"]= $set->getField("NAMA");
  $arrdata["nilai_rumpun"]= $set->getField("NILAI_RUMPUN");
  array_push($arrpendidikan, $arrdata);
}
// print_r($arrpendidikan);exit;

$statement= "";
$set= new Rumpun();
$set->selectskdasarpengakuan(array(), -1, -1, $statement);
// echo $set->query;exit;
$arrinfonilairumpun=[];
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["id"]= $set->getField("SK_DASAR_PENGAKUAN_ID");
  $arrdata["text"]= $set->getField("NAMA");
  array_push($arrinfonilairumpun, $arrdata);
}
// print_r($arrinfonilairumpun);exit;

$arrpangkatriwayat=[];
$sorder= "ORDER BY A.TMT_PANGKAT DESC, A.TANGGAL_SK";
$statementLevel= " AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')";
$set= new PangkatRiwayat();
$set->selectByParams(array(), -1, -1, $statementLevel." AND A.PEGAWAI_ID = ".$reqId, $sorder);
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["id"]= $set->getField("PANGKAT_RIWAYAT_ID");
  $arrdata["text"]= $set->getField('PANGKAT_KODE')." - ".$set->getField('NO_SK');
  array_push($arrpangkatriwayat, $arrdata);
}
// print_r($arrpangkatriwayat);exit;

// untuk kondisi file
$vfpeg= new globalfilepegawai();
$arrpilihfiledokumen= $vfpeg->pilihfiledokumen();
// print_r($arrpilihfiledokumen);exit;

$riwayattable= "PENDIDIKAN_RIWAYAT";

// WHEN '5' THEN 'Ijin belajar' WHEN '6' THEN 'Tugas Belajar'
if($reqStatusPendidikan == "5" || $reqStatusPendidikan == "6")
  $reqDokumenKategoriFileId= "14"; // ambil dari table KATEGORI_FILE, cek sesuai mode
else
  $reqDokumenKategoriFileId= "6"; // ambil dari table KATEGORI_FILE, cek sesuai mode
$arrsetriwayatfield= $vfpeg->setriwayatfield($riwayattable, $reqDokumenKategoriFileId);
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

<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

<!-- AUTO KOMPLIT -->
<link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
<script src="lib/autokomplit/jquery-ui.js"></script>

<script type="text/javascript"> 
// ambil rating penggalian
getarrpendidikan= JSON.parse('<?=JSON_encode($arrpendidikan)?>');

$(function(){
  $('#ff').form({
    url:'pendidikan_riwayat_json/add',
    onSubmit:function(){
      var reqJurusanId= reqPendidikanId= "";
      reqJurusanId= $("#reqJurusanId").val();
      reqPendidikanId= $("#reqPendidikanId").val();

      if(reqJurusanId == "" && parseInt(reqPendidikanId) > 6)
      {
       $.messager.alert('Info', "Jurusan tidak ada dalam sistem, hubungi admin untuk menambahkan data jurusan", 'info');
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
          <?
          if(!empty($pelayananid))
          {
          ?>
            vkembali= "app/loadUrl/app/pegawai_add_pendidikan_data?reqId=<?=$reqId?>&reqRowId="+rowid+"&pelayananid=<?=$pelayananid?>&pelayananjenis=<?=$pelayananjenis?>&pelayananrowid=<?=$pelayananrowid?>&pelayanankembali=<?=$pelayanankembali?>";
          <?
          }
          else
          {
          ?>
            vkembali= "app/loadUrl/app/pegawai_add_pendidikan_data?reqId=<?=$reqId?>&reqRowId="+rowid;
          <?
          }
          ?>
          document.location.href= vkembali;
        }, 1000));
        $(".mbox > .right-align").css({"display": "none"});
      }
      
    }


  });

  $('input[id^="reqxPendidikan"], input[id^="reqJurusan"]').autocomplete({
    source:function(request, response){
      var id= this.element.attr('id');
      var replaceAnakId= replaceAnak= urlAjax= "";

      if (id.indexOf('reqJurusan') !== -1)
      {
        var reqPendidikanId= "";
        reqPendidikanId= $("#reqPendidikanId").val();
        var element= id.split('reqJurusan');
        var indexId= "reqJurusanId"+element[1];
        urlAjax= "pendidikan_jurusan_json/combo?reqId="+reqPendidikanId;
      }
      else if (id.indexOf('reqPendidikan') !== -1)
      {
        var element= id.split('reqPendidikan');
        var indexId= "reqPendidikanId"+element[1];
        urlAjax= "pendidikan_riwayat_json/auto";
      }

      $.ajax({
        url: urlAjax,
        type: "GET",
        dataType: "json",
        data: { term: request.term },
        success: function(responseData){
          if(responseData == null)
          {
            response(null);
          }
          else
          {
            var array = responseData.map(function(element) {
              return {desc: element['desc'], id: element['id'], label: element['label'], statusht: element['statusht'], nilai_rumpun: element['nilai_rumpun'], rumpun_id: element['rumpun_id'], rumpun_nama: element['rumpun_nama']};
            });
            response(array);
          }
        }
      })
    },
    focus: function (event, ui) 
    { 
      var id= $(this).attr('id');
      if (id.indexOf('reqJurusan') !== -1)
      {
        var element= id.split('reqJurusan');
        var indexId= "reqJurusanId"+element[1];

        $("#reqRumpunJabatan").val(ui.item.rumpun_id).trigger('change');
        $("#reqRumpunJabatanNama").val(ui.item.rumpun_nama).trigger('change');
      }
      else if (id.indexOf('reqPendidikan') !== -1)
      {
        var element= id.split('reqPendidikan');
        var indexId= "reqPendidikanId"+element[1];

        vnilairumpun= ui.item.nilai_rumpun;

        if(parseFloat(vnilairumpun) >= 0)
        {
          $("#labelrumpunset").show();
        }
        else
        {
          $("#labelrumpunset").hide();
        }
        $("#reqNilaiKualifikasi, #reqNilaiKualifikasiText").val(vnilairumpun);
      }

      var statusht= "";
        //statusht= ui.item.statusht;
        $("#"+indexId).val(ui.item.id).trigger('change');
      },
      //minLength:3,
      autoFocus: true
    }).autocomplete( "instance" )._renderItem = function( ul, item ) {
    //
    return $( "<li>" )
    .append( "<a>" + item.desc + "</a>" )
    .appendTo( ul );
  };

  setGelarTipe();
  $('#reqGelarTipe').bind('change', function(ev) {
    setGelarTipe();
  });

  $("#reqPendidikanId").change(function(){
    $("#reqJurusan, #reqJurusanId").val("");

    var reqPendidikanId= $("#reqPendidikanId").val();
    reqNilaiKualifikasiText= 0;
    infoid= reqPendidikanId;
    valarrpendidikan= getarrpendidikan.filter(item => item.ID === infoid);
    // console.log(valarrpendidikan);
    if(Array.isArray(valarrpendidikan) && valarrpendidikan.length)
    {
      reqNilaiKualifikasiText= valarrpendidikan[0]["nilai_rumpun"];
    }
    $("#reqNilaiKualifikasi, #reqNilaiKualifikasiText").val(reqNilaiKualifikasiText);

  });

});

function setGelarTipe()
{
  var reqGelarTipe= "";
  reqGelarTipe= $("#reqGelarTipe").val();
  $("#reqInfoNamaGelarDepan,#reqInfoNamaGelarBelakang").hide();

  $('#reqGelarNamaDepan,#reqGelarNama').validatebox({required: false});
  $('#reqGelarNamaDepan,#reqGelarNama').removeClass('validatebox-invalid');

  if(reqGelarTipe == "")
  {
    $("#reqGelarNamaDepan,#reqGelarNama").val("");
  }
  else if(reqGelarTipe == "1")
  {
    $("#reqInfoNamaGelarDepan").show();
    $("#reqGelarNama").val("");
    $('#reqGelarNamaDepan').validatebox({required: true});
  }
  else if(reqGelarTipe == "2")
  {
    $("#reqInfoNamaGelarBelakang").show();
    $("#reqGelarNamaDepan").val("");
    $('#reqGelarNama').validatebox({required: true});
  }
  else
  {
    $("#reqInfoNamaGelarDepan,#reqInfoNamaGelarBelakang").show();
    $('#reqGelarNamaDepan,#reqGelarNama').validatebox({required: true});
  }
}
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
  <div id="basic-form" class="section">
    <div class="row">
      <div id='main' class="col s12 m12" style="padding-left: 15px;">

        <ul class="collection card">
          <li class="collection-item ubah-color-warna">EDIT PENDIDIKAN</li>
          <li class="collection-item">

            <form id="ff" method="post" enctype="multipart/form-data">

              <div class="row">
                <div class="input-field col s12 m3">
                  <?
                  if($tempKondisiCpns == "1" && $reqStatusPendidikan == "1")
                  {
                  ?>
                  <select name="reqStatusPendidikan" id="reqStatusPendidikan" <?=$disabled?>>
                      <option value="1" <? if($reqStatusPendidikan == 1) echo 'selected'?>>Pendidikan CPNS</option>
                      <option value="2" <? if($reqStatusPendidikan == 2) echo 'selected'?>>Diakui</option>
                      <option value="3" <? if($reqStatusPendidikan == 3) echo 'selected'?>>Belum Diakui</option>
                      <option value="4" <? if($reqStatusPendidikan == 4) echo 'selected'?>>Riwayat</option>
                    </select>
                  <?
                  }
                  elseif($tempKondisiCpns == "1")
                  {
                  ?>
                    <select name="reqStatusPendidikan" id="reqStatusPendidikan" <?=$disabled?>>
                      <option value="2" <? if($reqStatusPendidikan == 2) echo 'selected'?>>Diakui</option>
                      <option value="3" <? if($reqStatusPendidikan == 3) echo 'selected'?>>Belum Diakui</option>
                      <option value="4" <? if($reqStatusPendidikan == 4) echo 'selected'?>>Riwayat</option>
                    </select>
                  <?
                  }
                  else
                  {
                    ?>
                    <select name="reqStatusPendidikan" id="reqStatusPendidikan" <?=$disabled?>>
                      <option value="1" <? if($reqStatusPendidikan == 1) echo 'selected'?>>Pendidikan CPNS</option>
                      <option value="2" <? if($reqStatusPendidikan == 2) echo 'selected'?>>Diakui</option>
                      <option value="3" <? if($reqStatusPendidikan == 3) echo 'selected'?>>Belum Diakui</option>
                      <option value="4" <? if($reqStatusPendidikan == 4) echo 'selected'?>>Riwayat</option>
                    </select>
                  <?
                  }
                  ?>
                  <label for="reqStatusPendidikan">Status Pendidikan</label>
                </div>

                <div class="input-field col s12 m3" id="labelpppkstatus">
                  <input type="checkbox" id="reqPppkStatus" name="reqPppkStatus" value="1" <? if($reqPppkStatus == 1) echo 'checked'?>/>
                  <label for="reqPppkStatus">Sebagai Ijazah PPPK</label>
                </div>

                <div class="input-field col s12 m3" id="labelskdasarpengakuan">
                  <select id="reqSkDasarPengakuan" name="reqSkDasarPengakuan" >
                    <option value=""></option>
                  </select>
                  <label for="reqSkDasarPengakuan">SK Dasar Jabatan</label>
                </div>

                <div class="input-field col s12 m3" id="labeldasarpangkatriwayatid">
                  <select id="reqDasarPangkatRiwayatId" name="reqDasarPangkatRiwayatId" >
                    <option value=""></option>
                  </select>
                  <label for="reqDasarPangkatRiwayatId">Mulai Golongan</label>
                </div>

              </div>

              <div class="row" id="labeltugasbelajar">
                <div class="input-field col s12 m6">
                  <label for="reqNoSuratIjin">No. Surat Ijin / Tugas Belajar</label>
                  <input placeholder type="text" id="reqNoSuratIjin" name="reqNoSuratIjin" <?=$read?> value="<?=$reqNoSuratIjin?>"  />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTglSuratIjin">Tgl. Surat Ijin / Tugas Belajar</label>
                  <input placeholder class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglSuratIjin" id="reqTglSuratIjin" value="<?=$reqTglSuratIjin?>" maxlength="10" onKeyDown="return format_date(event,'reqTglSuratIjin');" />
                </div>
              </div>

              <div class="row" id="labelpencantumangelar">
                <div class="input-field col s12 m6">
                  <label for="reqCantumGelarNoSk">No SK Pencatuman Gelar</label>
                  <input placeholder type="text" id="reqCantumGelarNoSk" name="reqCantumGelarNoSk" value="<?=$reqCantumGelarNoSk?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqCantumGelarTgl">Tgl. SK Pencatuman Gelar</label>
                  <input placeholder class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqCantumGelarTgl" id="reqCantumGelarTgl" value="<?=$reqCantumGelarTgl?>" maxlength="10" onKeyDown="return format_date(event,'reqCantumGelarTgl');" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNoSttb">No. Ijazah</label>
                  <input placeholder type="text" class="easyui-validatebox" required id="reqNoSttb" name="reqNoSttb" <?=$read?> value="<?=$reqNoSttb?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTglSttb">Tgl. Kelulusan</label>
                  <input placeholder required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglSttb" id="reqTglSttb" value="<?=$reqTglSttb?>" maxlength="10" onKeyDown="return format_date(event,'reqTglSttb');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m3">
                  <select <?=$disabled?> name="reqPendidikanId" id="reqPendidikanId">
                    <option value=""></option>
                    <?
                    foreach ($arrpendidikan as $key => $value)
                    {
                      $optionid= $value["ID"];
                      $optiontext= $value["TEXT"];
                      $optionselected= "";

                      // if($optionid == 4)
                      //   continue;

                      if($reqPendidikanId == $optionid)
                        $optionselected= "selected";
                    ?>
                      <option value="<?=$optionid?>" <?=$optionselected?>><?=$optiontext?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqPendidikanId">Pendidikan</label>
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqJurusan">Jurusan</label>
                  <input type="hidden" name="reqJurusanId" id="reqJurusanId" value="<?=$reqJurusanId?>" /> 
                  <input placeholder type="text" name="reqJurusan" id="reqJurusan" value="<?=$reqJurusan?>" title="Jurusan harus diisi" />
                </div>
                <div class="input-field col s12 m3">
                  <label for="reqNilaiKualifikasi">Nilai Kualifikasi Pendidikan</label>
                  <input type="hidden" name="reqNilaiKualifikasi" id="reqNilaiKualifikasi" value="<?=$reqNilaiKualifikasi?>" /> 
                  <input placeholder disabled type="text" id="reqNilaiKualifikasiText" value="<?=$reqNilaiKualifikasi?>" />
                </div>
              </div>

              <div class="row">
                <?
                if($reqStatus == "3")
                {
                ?>
                  <div class="input-field col s12 m3">
                    <select name="reqStatusTugasIjinBelajar" id="reqStatusTugasIjinBelajar">
                      <option value="1" <? if($reqStatusTugasIjinBelajar == "1") echo "selected"?>>Ijin Belajar</option>
                      <option value="2" <? if($reqStatusTugasIjinBelajar == "2") echo "selected"?>>Tugas Belajar</option>
                    </select>
                    <label for="reqStatusTugasIjinBelajar">Status Ijin belajar / Tugas Belajar</label>
                  </div>
                <?
                }
                ?>
                <div class="input-field col s12 m3">
                  <select name="reqGelarTipe" id="reqGelarTipe" <?=$disabled?>>
                    <option value="" <? if($reqGelarTipe == "") echo 'selected';?>>Tanpa gelar</option>
                    <option value="1" <? if($reqGelarTipe == "1") echo 'selected';?>>Depan</option>
                    <option value="2" <? if($reqGelarTipe == "2") echo 'selected';?>>Belakang</option>
                    <option value="3" <? if($reqGelarTipe == "3") echo 'selected';?>>Depan Belakang</option>
                  </select>
                  <label for="reqGelarTipe">Tipe Gelar</label>
                </div>
                <div class="input-field col s12 m3" id="reqInfoNamaGelarDepan">
                  <label for="reqGelarNamaDepan">Gelar Depan</label>
                  <input placeholder type="text" class="easyui-validatebox" name="reqGelarNamaDepan" id="reqGelarNamaDepan" <?=$read?> value="<?=$reqGelarNamaDepan?>"/>
                </div>
                <div class="input-field col s12 m3" id="reqInfoNamaGelarBelakang">
                  <label for="reqGelarNama">Gelar Belakang</label>
                  <input placeholder type="text" class="easyui-validatebox" name="reqGelarNama" id="reqGelarNama" <?=$read?> value="<?=$reqGelarNama?>"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNamaSekolah">Nama Sekolah / PT</label>
                  <input placeholder type="text" class="easyui-validatebox" required id="reqNamaSekolah" name="reqNamaSekolah" <?=$read?> value="<?=$reqNamaSekolah?>" title="Nama sekolah harus diisi" />
                </div>
                <div class="input-field col s12 m3">
                  <label for="reqKepalaSekolah">Kepala Sekolah / PT</label>
                  <input placeholder type="text" class="easyui-validatebox" required id="reqKepalaSekolah" name="reqKepalaSekolah" <?=$read?> value="<?=$reqKepalaSekolah?>" />
                </div>
                <div class="input-field col s3">
                  <label for="reqAlamatSekolah">Kota Sekolah / PT</label>
                  <input placeholder type="text" class="easyui-validatebox" id="reqAlamatSekolah" name="reqAlamatSekolah" <?=$read?> value="<?=$reqAlamatSekolah?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s3 m12" id="labelrumpunset">
                  <label for="reqRumpunJabatanNama">Rumpun Kualifikasi</label>
                  <input type="hidden" name="reqRumpunJabatan" id="reqRumpunJabatan" value="<?=$reqRumpunJabatan?>" />
                  <input placeholder="" type="text" disabled class="easyui-validatebox" id="reqRumpunJabatanNama" value="<?=$reqRumpunJabatanNama?>" />
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
                      vkembali= "app/loadUrl/app/pegawai_add_pendidikan_monitoring?reqId=<?=$reqId?>&pelayananid=<?=$pelayananid?>&pelayananjenis=<?=$pelayananjenis?>&pelayananrowid=<?=$pelayananrowid?>&pelayanankembali=<?=$pelayanankembali?>";
                      <?
                      }
                      else
                      {
                      ?>
                      vkembali= "app/loadUrl/app/pegawai_add_pendidikan_monitoring?reqId=<?=$reqId?>";
                      <?
                      }
                      ?>
                      document.location.href = vkembali;
                    });
                  </script>

                  <input type="hidden" name="reqNamaFakultas" value="<?=$reqNamaFakultas?>" />
                  <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                  <input type="hidden" name="reqId" value="<?=$reqId?>" />
                  <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
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
                  <?
                  for($index_loop=0; $index_loop < 7; $index_loop++)
                  {
                  ?>
                  <!-- <br/> -->
                  <?
                  }
                  ?>

                  <?
                  if(!empty($reqRowId) && !empty($kirimsapk))
                  {
                  ?>
                  <button class="btn  waves-effect waves-light" style="font-size:9pt;background: #9C28B0;" type="button" id='buttonbtn'>
                    <input type="hidden" id="reqIdField" value="<?=$reqRowId?>" />
                    <input type="hidden" id="reqIdBkn" value="<?=$vidsapk?>" />
                    <input type="hidden" id="reqUrlBkn" value="pendidikan_json" />
                    <i class="mdi-content-save left hide-on-small-only"></i> <span > UPDATE KE SIASN BKN</span>
                  </button>
                  <?
                  }
                  ?>

                  <?
                  if(!empty($vidsapk) && !empty($lihatsapk))
                  {
                    $vdetilsapk= $vidsapk."___pegawai_add_pendidikan_sapk_data";
                    $vdetillabelsapk= "Data SAPK BKN";
                  ?>
                  <button class="btn blue waves-effect waves-light" style="font-size:9pt" type="button" id='buttondatasapk<?=$vdetilsapk?>'>
                    <input type="hidden" id="labelvsapk<?=$vdetilsapk?>" value="<?=$vdetillabelsapk?>" />
                    <input type="hidden" id="<?=$vidsapk?>" value="<?=$reqId?>" />
                    <span id="labelframesapk<?=$vdetilsapk?>">Cek <?=$vdetillabelsapk?></span>
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
                        <i class="mdi-file-file-upload" style="font-family: "Roboto",sans-serif,Material-Design-Icons !important; font-size: 14px !important;">Upload</i>
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

              <?
              for($i=0; $i<4;$i++)
              {
              ?>
              <br/>
              <?
              }
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

<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

<style type="text/css">
  .select-dropdown {
    max-height:250px !important; overflow:auto !important;
  }
</style>

<!-- tambahan lib cek tanggal -->
<script src="lib/moment/moment-with-locales.js"></script>
<script type="text/javascript">
  // ambil rating penggalian
  getarrinfonilairumpun= JSON.parse('<?=JSON_encode($arrinfonilairumpun)?>');
  getarrpangkatriwayat= JSON.parse('<?=JSON_encode($arrpangkatriwayat)?>');

  $(document).ready(function() {
    $('select').material_select();

    setstatuspendidikan("");
    $("#reqStatusPendidikan").change(function(){
      setstatuspendidikan("data");
    });

    setskdasarpengakuan("");
    $("#reqSkDasarPengakuan").change(function(){
      setskdasarpengakuan("data");
    });

    $('#reqCantumGelarTgl').keyup(function() {
      var vtanggalakhir= $('#reqCantumGelarTgl').val();
      var vtanggalawal= $('#reqTglSttb').val();
      var checktanggalakhir= moment(vtanggalakhir , 'DD-MM-YYYY', true).isValid();
      var checktanggalawal= moment(vtanggalawal , 'DD-MM-YYYY', true).isValid();
      // console.log(checktanggalakhir+'_'+checktanggalawal);
      if(checktanggalakhir == true && checktanggalawal == true)
      {
        var tglakhir = moment(vtanggalakhir, 'DD-MM-YYYY');  // format tanggal
        var tglawal = moment(vtanggalawal, 'DD-MM-YYYY'); 

        if (tglakhir.isSameOrAfter(tglawal)) {} 
        else 
        {
           $('#reqCantumGelarTgl').val(vtanggalawal);
        }
      }

    });

  });

  function setstatuspendidikan(infomode)
  {
    reqStatusPendidikan= $("#reqStatusPendidikan").val();

    // khusus status cpns, maka muncul
    if(infomode == "")
      vinfodata= "<?=$reqPppkStatus?>";
    else
      vinfodata= $("#reqPppkStatus").val();

    $("#labelpppkstatus").hide();
    $("#reqPppkStatus").prop('checked', false);
    if(reqStatusPendidikan == "1")
    {
      if(vinfodata == "1")
      {
        $("#reqPppkStatus").prop('checked', true);
      }

      $("#labelpppkstatus").show();
    }

    // setting untuk sk dasar pengakuan
    if(infomode == "")
      vinfodata= "<?=$reqSkDasarPengakuan?>";
    else
      vinfodata= $("#reqSkDasarPengakuan").val();

    vlabelid= "reqSkDasarPengakuan";
    $("#"+vlabelid+" option").remove();
    $("#"+vlabelid).material_select();

    var voption= "<option value=''></option>";
    if(reqStatusPendidikan == "1" || reqStatusPendidikan == "2")
    {
      if(Array.isArray(getarrinfonilairumpun) && getarrinfonilairumpun.length)
      {
        $.each(getarrinfonilairumpun, function( index, value ) {
          // console.log( index + ": " + value["id"] );
          infoid= value["id"];
          infotext= value["text"];
          setoption= "";

          if(reqStatusPendidikan == "1")
          {
            if(infoid == "1")
            {
              setoption= "1";
            }
          }
          else if(reqStatusPendidikan == "2")
          {
            if(infoid == "1"){}
            else
            {
              setoption= "1";
            }
          }

          if(setoption == "1")
          {
            vselected= "";
            if(infoid == vinfodata)
            {
              vselected= "selected";
            }

            voption+= "<option value='"+infoid+"' "+vselected+" >"+infotext+"</option>";
          }
        });
      }
    }
    $("#"+vlabelid).html(voption);
    $("#"+vlabelid).material_select();

    $("#labelskdasarpengakuan").hide();
    if(reqStatusPendidikan == "1" || reqStatusPendidikan == "2")
    {
      $("#labelskdasarpengakuan").show();
    }

    setskdasarpengakuan("data");
  }

  function setskdasarpengakuan(infomode)
  {
    reqSkDasarPengakuan= $("#reqSkDasarPengakuan").val();

    // setting untuk sk dasar pengakuan
    if(infomode == "")
      vinfodata= "<?=$reqDasarPangkatRiwayatId?>";
    else
      vinfodata= $("#reqDasarPangkatRiwayatId").val();

    vlabelid= "reqDasarPangkatRiwayatId";
    $("#"+vlabelid+" option").remove();
    $("#"+vlabelid).material_select();

    var voption= "<option value=''></option>";
    if(reqSkDasarPengakuan == "2")
    {
      if(Array.isArray(getarrpangkatriwayat) && getarrpangkatriwayat.length)
      {
        $.each(getarrpangkatriwayat, function( index, value ) {
          // console.log( index + ": " + value["id"] );
          infoid= value["id"];
          infotext= value["text"];
          setoption= "1";

          if(setoption == "1")
          {
            vselected= "";
            if(infoid == vinfodata)
            {
              vselected= "selected";
            }

            voption+= "<option value='"+infoid+"' "+vselected+" >"+infotext+"</option>";
          }
        });
      }
    }
    $("#"+vlabelid).html(voption);
    $("#"+vlabelid).material_select();

    $("#labeldasarpangkatriwayatid").hide();
    $("#labelpencantumangelar, #labeltugasbelajar").show();
    if(reqSkDasarPengakuan == "1") // kalau sk cpns / pns
    {
      $("#labelpencantumangelar, #labeltugasbelajar").hide();
    }
    else if(reqSkDasarPengakuan == "2") // kalau kenaikan pangkat
    {
      $("#labeldasarpangkatriwayatid").show();
      $("#labelpencantumangelar").hide();
    }
    else if(reqSkDasarPengakuan == "3") // kalau sk pencatuman gelar
    {
      $("#labelpencantumangelar").show();
    }
    else
    {
      $("#labelpencantumangelar").hide();
    }
    // labelpencantumangelar;reqNoSuratIjin;reqTglSuratIjin;labeltugasbelajar;reqCantumGelarNoSk;reqCantumGelarTgl

  }

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
<script type="text/javascript" src="lib/easyui/pelayanan-bkndetil.js"></script>

<link href="lib/mbox/mbox.css" rel="stylesheet">
<script src="lib/mbox/mbox.js"></script>
<link href="lib/mbox/mbox-modif.css" rel="stylesheet">

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>