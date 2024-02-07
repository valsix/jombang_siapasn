<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('SuratTandaLulus');
$this->load->model('KualitasFile');
$this->load->library('globalfilepegawai');

$pelayananid= $this->input->get("pelayananid");
$pelayananjenis= $this->input->get("pelayananjenis");
$pelayananrowid= $this->input->get("pelayananrowid");
$pelayanankembali= $this->input->get("pelayanankembali");

$sessionLoginLevel= $this->LOGIN_LEVEL;
$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "011306";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

if($reqRowId=="")
{
  $reqMode = 'insert';
}
else
{
  $reqMode = 'update';
  $statement= " AND A.SURAT_TANDA_LULUS_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
  $set= new SuratTandaLulus();
  $set->selectByParams(array(), -1, -1, $statement);
  $set->firstRow();
  $reqRowId= $set->getField('SURAT_TANDA_LULUS_ID');
  $reqTglStlud= dateToPageCheck($set->getField('TANGGAL_STLUD'));
  $reqTanggalMulai= dateToPageCheck($set->getField('TANGGAL_MULAI'));
  $reqTanggalAkhir= dateToPageCheck($set->getField('TANGGAL_AKHIR'));
  $reqJenisId= $set->getField('JENIS_ID');
  $reqNoStlud= $set->getField('NO_STLUD');
  $reqPendidikanRiwayatId= $set->getField('PENDIDIKAN_RIWAYAT_ID');
  // echo $reqPendidikanRiwayatId;exit();
  $reqPendidikanId= $set->getField('PENDIDIKAN_ID');
  $reqNilaiNpr= dotToComma($set->getField('NILAI_NPR'));
  $reqNilaiNt= dotToComma($set->getField('NILAI_NT'));
  $LastLevel= $set->getField('LAST_LEVEL');
}

$tempAksiProses= "";
if($sessionLoginLevel < $LastLevel)
$tempAksiProses= "1";
// echo $set->query;exit;

// untuk kondisi file
$vfpeg= new globalfilepegawai();
$arrpilihfiledokumen= $vfpeg->pilihfiledokumen();
// print_r($arrpilihfiledokumen);exit;

$riwayattable= "SURAT_TANDA_LULUS";
$reqDokumenKategoriFileId= "9"; // ambil dari table KATEGORI_FILE, cek sesuai mode
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

<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

<!-- AUTO KOMPLIT -->
<link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
<script src="lib/autokomplit/jquery-ui.js"></script>
  
<script type="text/javascript"> 
$(function(){

  $("#reqsimpan").click(function() { 
    if($("#ff").form('validate') == false){
      return false;
    }
    else
      $("#reqSubmit").click();
  });


  $('#ff').form({
    url:'surat_tanda_lulus_json/add',
    onSubmit:function(){
      var reqJenisId= reqPendidikanRiwayatId= "";
      reqJenisId= $("#reqJenisId").val();

      if(reqJenisId == 3 || reqJenisId == 4 || reqJenisId == 5)
      {
        reqPendidikanRiwayatId= $("#reqPendidikanRiwayatId").val();

        if(reqPendidikanRiwayatId == "")
        {
          mbox.alert("Lengkapi data Riwayat Pendidikan terlebih dahulu", {open_speed: 0});
          return false;
        }

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
            vkembali= "app/loadUrl/app/pegawai_add_surat_tanda_lulus_add?reqId=<?=$reqId?>&reqRowId="+rowid+"&pelayananid=<?=$pelayananid?>&pelayananjenis=<?=$pelayananjenis?>&pelayananrowid=<?=$pelayananrowid?>&pelayanankembali=<?=$pelayanankembali?>";
          <?
          }
          else
          {
          ?>
            vkembali= "app/loadUrl/app/pegawai_add_surat_tanda_lulus_add?reqId=<?=$reqId?>&reqRowId="+rowid;
          <?
          }
          ?>
          document.location.href= vkembali;
        }, 1000));
        $(".mbox > .right-align").css({"display": "none"});
      }
    }
  });

  $("#reqJenisId").change(function() { 
    var reqJenisId= "";
    reqJenisId= $("#reqJenisId").val();

    if(reqJenisId == 3 || reqJenisId == 4 || reqJenisId == 5)
    {
      setpendidikan();
      setinfopendidikan();
    }
    else
    {
      $("#reqinfopendidikan").hide();
      $("#reqPendidikanRiwayatId, #reqPendidikanId").val("");
      $("#reqPendidikan option").remove();
      $("#reqPendidikan").material_select();
    }

  });

  $("#reqPendidikan").change(function() { 
    var reqPendidikan= reqPendidikanRiwayatId= reqPendidikanId= "";
    reqPendidikan= $("#reqPendidikan").val();
    reqPendidikan= String(reqPendidikan);
    reqPendidikan= reqPendidikan.split('-');
    reqPendidikanRiwayatId= reqPendidikan[0];
    reqPendidikanId= reqPendidikan[1];

    $("#reqPendidikanRiwayatId").val(reqPendidikanRiwayatId);
    $("#reqPendidikanId").val(reqPendidikanId);
  });

  setinfopendidikan();

  <?
  if($reqPendidikanRiwayatId == ""){}
  else
  {
  ?>
  setpendidikan();
  <?
  }
  ?>

});

function setinfopendidikan()
{
  var reqJenisId= "";
  reqJenisId= $("#reqJenisId").val();
  // alert(reqJenisId);
  if(reqJenisId == 3 || reqJenisId == 4 || reqJenisId == 5)
  {
    $("#reqinfopendidikan").show();
  }
  else
  {
    $("#reqPendidikanRiwayatId, #reqPendidikanId").val("");
    $("#reqPendidikan option").remove();
    $("#reqPendidikan").material_select();
    $("#reqinfopendidikan").hide();
  }
}

function setpendidikan()
{
    var reqJenisId= reqJenisPendidikanId= "";
    reqJenisId= $("#reqJenisId").val();
    // alert(reqJenisId);

    if(reqJenisId == 3)
    {
      reqJenisPendidikanId= "4";
    }
    else if(reqJenisId == 4)
    {
      reqJenisPendidikanId= "10,11";
    }
    else if(reqJenisId == 5)
    {
      reqJenisPendidikanId= "12";
    }

    $("#reqPendidikan option").remove();
    $("#reqPendidikan").material_select();

    $("<option value=''></option>").appendTo("#reqPendidikan");
    $.ajax({'url': "surat_tanda_lulus_json/combo/?reqPegawaiId=<?=$reqId?>&reqId="+reqJenisPendidikanId,'success': function(dataJson) {
      var data= JSON.parse(dataJson);

      var items = "";
      items += "<option></option>";
      $.each(data, function (i, SingleElement) {

        <?
        if($reqPendidikanRiwayatId == "")
        {
        ?>
          items += "<option value='" + SingleElement.id + "'>" + SingleElement.text + "</option>";
        <?
        }
        else
        {
        ?>
          if('<?=$reqPendidikanRiwayatId?>-<?=$reqPendidikanId?>' == SingleElement.id)
          items += "<option value='" + SingleElement.id + "' selected>" + SingleElement.text + "asdasd</option>";
          else
          items += "<option value='" + SingleElement.id + "'>" + SingleElement.text + "</option>";
        <?
        }
        ?>

      });
      $("#reqPendidikan").html(items);
      $("#reqPendidikan").material_select();
    }});

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
          <li class="collection-item ubah-color-warna">EDIT SURAT TANDA LULUS</li>
          <li class="collection-item">

            <form id="ff" method="post" enctype="multipart/form-data">

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqJenisId" class="active">Surat Tanda Lulus</label>
                  <select name="reqJenisId" id="reqJenisId" >
                    <option value="" <? if($reqJenisId == "") echo 'selected'?>></option>
                    <option value="1" <? if($reqJenisId == 1) echo 'selected'?>>STL Ujian Dinas I</option>
                    <option value="2" <? if($reqJenisId == 2) echo 'selected'?>>STL Ujian Dinas II</option>
                    <option value="3" <? if($reqJenisId == 3) echo 'selected'?>>STL Ujian Kenaikan Pangkat Penyesuaian Ijazah SMA</option>
                    <option value="4" <? if($reqJenisId == 4) echo 'selected'?>>STL Ujian Kenaikan Pangkat Penyesuaian Ijazah D-4/S-1</option>
                    <option value="5" <? if($reqJenisId == 5) echo 'selected'?>>STL Ujian Kenaikan Pangkat Penyesuaian Ijazah S-2</option>
                  </select>
                </div>
              </div>
            
              <div class="row">
              	
              </div>
            
              <div class="row">
                <div class="input-field col s12 m5">
                  <label for="reqNoStlud">No STL</label>
                  <input placeholder="" type="text" class="easyui-validatebox" required id="reqNoStlud" name="reqNoStlud" value="<?=$reqNoStlud?>" />
                </div>
                <div class="input-field col s12 m2">
                  <label for="reqTglStlud">Tgl STL</label>
                  <input placeholder="" required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglStlud" id="reqTglStlud"  value="<?=$reqTglStlud?>" maxlength="10" onKeyDown="return format_date(event,'reqTglStlud');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m2">
                  <label for="reqTanggalMulai">Tanggal Mulai</label>
                  <input placeholder="" required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalMulai" id="reqTanggalMulai" value="<?=$reqTanggalMulai?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalMulai');"/>
                </div>
                <div class="input-field col s12 m2">
                  <label for="reqTanggalAkhir">Tanggal Akhir</label>
                  <input placeholder="" required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalAkhir" id="reqTanggalAkhir" value="<?=$reqTanggalAkhir?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalAkhir');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m3">
                  <label for="reqNilaiNpr">Nilai Persentasi (NPR)</label>
                  <input placeholder="" type="text" id="reqNilaiNpr" name="reqNilaiNpr" value="<?=$reqNilaiNpr?>" onkeypress='kreditvalidate(event, this)' />
                </div>
                <div class="input-field col s12 m3">
                  <label for="reqNilaiNt">Nilai Terbilang (NT)</label>
                  <input placeholder="" type="text" id="reqNilaiNt" name="reqNilaiNt" value="<?=$reqNilaiNt?>" onkeypress='kreditvalidate(event, this)' />
                </div>
              </div>

              <div id="reqinfopendidikan">
                <div class="row">
                  <div class="input-field col s12 m6">
                    <input type="hidden" name="reqPendidikanRiwayatId" id="reqPendidikanRiwayatId" value="<?=$reqPendidikanRiwayatId?>" />
                    <input type="hidden" name="reqPendidikanId" id="reqPendidikanId" value="<?=$reqPendidikanId?>" />
                    <select id="reqPendidikan"></select>
                    <label for="reqPendidikan">Pendidikan</label>
                  </div>
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
                      vkembali= "app/loadUrl/app/pegawai_add_surat_tanda_lulus_monitoring?reqId=<?=$reqId?>&pelayananid=<?=$pelayananid?>&pelayananjenis=<?=$pelayananjenis?>&pelayananrowid=<?=$pelayananrowid?>&pelayanankembali=<?=$pelayanankembali?>";
                      <?
                      }
                      else
                      {
                      ?>
                      vkembali= "app/loadUrl/app/pegawai_add_surat_tanda_lulus_monitoring?reqId=<?=$reqId?>";
                      <?
                      }
                      ?>
                      document.location.href = vkembali;
                    });
                  </script>

                  <input type="hidden" name="reqPeriode" value="<?=$reqPeriode?>" />
                  <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                  <input type="hidden" name="reqId" value="<?=$reqId?>" />
                  <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                  
                  <?
                  if($tempAksesMenu == "A")
                  {
                    if($reqRowId == "")
                    {
                  ?>
                    <button type="submit" style="display:none" id="reqSubmit"></button>
                    <button class="btn waves-effect waves-light green" style="font-size:9pt" type="button" id="reqsimpan">Simpan
                      <i class="mdi-content-save left hide-on-small-only"></i>
                    </button>
                  <?
                    }
                    else
                    {
                      if($set->getField('STATUS') ==1 ){}
                      else
                      {
                        if($set->getField('DATA_HUKUMAN') == 0)
                        {
                          if($tempAksiProses == "1"){}
                          else
                          {
                        ?>
                            <button class="btn waves-effect waves-light green" style="font-size:9pt" type="submit" name="action">Simpan
                              <i class="mdi-content-save left hide-on-small-only"></i>
                            </button>
                        <?
                          }
                        }
                      }
                    }
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
                $vriwayatid= "";
                $vpegawairowfile= $reqDokumenKategoriFileId."-".$vriwayattable."-".$riwayatfield."-".$vriwayatid;
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

              <br/><br/><br/>

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

<script type="text/javascript">
  $(document).ready(function() {
    $('select').material_select();
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