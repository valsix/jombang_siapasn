<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('Penghargaan');
$this->load->model('PejabatPenetap');
$this->load->model('RefPenghargaan');
$this->load->model('RefPenghargaanJenjang');
$this->load->model('KualitasFile');
$this->load->library('globalfilepegawai');

$pejabat_penetap= new PejabatPenetap();

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "010901";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

$statement= "";
$set= new Penghargaan();
$set->selectpredikat(array());
// echo $set->query;exit;
$arrpredikatpenghargaan=[];
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["ID"]= $set->getField("PENGHARGAAN_PREDIKAT_ID");
  $arrdata["NAMA"]= $set->getField("NAMA");
  array_push($arrpredikatpenghargaan, $arrdata);
}

$reqMode = 'update';
$statement= " AND A.PENGHARGAAN_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
$set= new Penghargaan();
$set->selectByParams(array(), -1, -1, $statement);
$set->firstRow();

if($set->getField('PEJABAT_PENETAP_ID')==''){
  $reqStatus='baru';
  $reqDisplayBaru='';
  $reqDisplay='none';
}else{
  $reqDisplayBaru='none';
  $reqDisplay='';
}

if($reqRowId == ""){
  $reqMode = "insert";
}
else
{
  $reqPejabatPenetapId= $set->getField('PEJABAT_PENETAP_ID');
  $reqPejabatPenetap= $set->getField('PEJABAT_PENETAP_NAMA');
  $reqNamaPenghargaan= $set->getField('NAMA');
  $reqTahun= $set->getField('TAHUN');
  $reqTglSK= dateToPageCheck($set->getField('TANGGAL_SK'));
  $reqNoSK= $set->getField('NO_SK');

  $reqRefPenghargaanId= $set->getField('REF_PENGHARGAAN_ID');
  $reqNamaDetil= $set->getField('NAMA_DETIL');
  $reqJenjangPeringkatDetil= $set->getField('INFO_DETIL');
  $reqJenjangPeringkatId= $set->getField('JENJANG_PERINGKAT_ID');
  $reqPenghargaanPredikatId= $set->getField('PENGHARGAAN_PREDIKAT_ID');
}

$statement= "";
$set= new RefPenghargaan();
$set->selectbyparams(array());
// echo $set->query;exit;
$arrpenghargaan=[];
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["ID"]= $set->getField("REF_PENGHARGAAN_ID");
  $arrdata["NAMA"]= $set->getField("NAMA");
  $arrdata["INFO_DETIL"]= $set->getField("INFO_DETIL");
  array_push($arrpenghargaan, $arrdata);
}
// print_r($arrpenghargaan);exit;

$statement= "";
$set= new RefPenghargaanJenjang();
$set->selectbyparams(array());
// echo $set->query;exit;
$arrpenghargaanjenjang=[];
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["ID"]= $set->getField("REF_PENGHARGAAN_JENJANG_ID");
  $arrdata["NAMA"]= $set->getField("NAMA");
  array_push($arrpenghargaanjenjang, $arrdata);
}
// print_r($arrpenghargaanjenjang);exit;

// untuk kondisi file
$vfpeg= new globalfilepegawai();
$arrpilihfiledokumen= $vfpeg->pilihfiledokumen();
// print_r($arrpilihfiledokumen);exit;

$riwayattable= "PENGHARGAAN";
$reqDokumenKategoriFileId= "22"; // ambil dari table KATEGORI_FILE, cek sesuai mode
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
    $('#ff').form({
      url:'penghargaan_json/add',
      onSubmit:function(){

        reqRefPenghargaanId= $("#reqRefPenghargaanId").val();
        if(reqRefPenghargaanId == "")
        {
          mbox.alert("Isikan terlebih dahulu Nama Penghargaan", {open_speed: 0});
          return false;
        }

        // reqPenghargaanPredikatId= $("#reqPenghargaanPredikatId").val();
        // if(reqPenghargaanPredikatId == "")
        // {
        //   mbox.alert("Isikan terlebih dahulu Predikat", {open_speed: 0});
        //   return false;
        // }

        reqJenjangPeringkatDetil= $("#reqJenjangPeringkatDetil").val();
        if(reqJenjangPeringkatDetil == "1")
        {
          reqNamaDetil= $("#reqNamaDetil").val();
          reqJenjangPeringkatId= $("#reqJenjangPeringkatId").val();

          if(reqNamaDetil == "")
          {
            mbox.alert("Isikan terlebih dahulu Perihal Penghargaan", {open_speed: 0});
            return false;
          }

          if(reqJenjangPeringkatId == "")
          {
            mbox.alert("Isikan terlebih dahulu Jenjang Peringkat", {open_speed: 0});
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
            document.location.href= "app/loadUrl/app/pegawai_add_penghargaan_data/?reqId=<?=$reqId?>&reqRowId="+rowid;
          }, 1000));
          $(".mbox > .right-align").css({"display": "none"});
        }
        
      }
    });

    $('input[id^="reqPejabatPenetap"]').autocomplete({
      source:function(request, response){
        var id= this.element.attr('id');
        var replaceAnakId= replaceAnak= urlAjax= "";

        if (id.indexOf('reqPejabatPenetap') !== -1)
        {
          var element= id.split('reqPejabatPenetap');
          var indexId= "reqPejabatPenetapId"+element[1];
          urlAjax= "pejabat_penetap_json/combo";
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
                return {desc: element['desc'], id: element['id'], label: element['label'], statusht: element['statusht']};
              });
              response(array);
            }
          }
        })
      },
      focus: function (event, ui) 
      { 
        var id= $(this).attr('id');
        if (id.indexOf('reqPejabatPenetap') !== -1)
        {
          var element= id.split('reqPejabatPenetap');
          var indexId= "reqPejabatPenetapId"+element[1];
        }

        var statusht= "";
          //statusht= ui.item.statusht;
          $("#"+indexId).val(ui.item.id).trigger('change');
        },
        //minLength:3,
        autoFocus: true
      }).autocomplete( "instance" )._renderItem = function( ul, item ) {
      //return
      return $( "<li>" )
      .append( "<a>" + item.desc + "</a>" )
      .appendTo( ul );
    };

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
  <div id="basic-form" class="section">
    <div class="row">
      <div id='main' class="col s12 m12" style="padding-left: 15px;">
        <ul class="collection card">
          <li class="collection-item ubah-color-warna">EDIT PENGHARGAAN</li>
          <li class="collection-item">
            <div class="row">
              <form id="ff" method="post" enctype="multipart/form-data">

                <div class="row">
                  <div class="input-field col s12 m12">
                    <select name="reqRefPenghargaanId" id="reqRefPenghargaanId">
                      <option value="" <? if($reqRefPenghargaanId == "") echo 'selected';?>>Belum di tentukan</option>
                      <?
                      foreach ($arrpenghargaan as $key => $value)
                      {
                        $optionid= $value["ID"];
                        $optiontext= $value["NAMA"];
                        $optionselected= "";
                        if($reqRefPenghargaanId == $optionid)
                          $optionselected= "selected";
                      ?>
                        <option value="<?=$optionid?>" <?=$optionselected?>><?=$optiontext?></option>
                      <?
                      }
                      ?>
                    </select>      
                    <label for="reqNamaPenghargaan">Nama Penghargaan</label>
                  </div>

                  <div class="input-field col s12 m6" style="display: none;">
                    <select name="reqPenghargaanPredikatId" id="reqPenghargaanPredikatId">
                      <option value="" <? if($reqPenghargaanPredikatId == "") echo 'selected';?>>Belum di tentukan</option>
                      <?
                      foreach ($arrpredikatpenghargaan as $key => $value)
                      {
                        $optionid= $value["ID"];
                        $optiontext= $value["NAMA"];
                        $optionselected= "";
                        if($reqPenghargaanPredikatId == $optionid)
                          $optionselected= "selected";
                      ?>
                        <option value="<?=$optionid?>" <?=$optionselected?>><?=$optiontext?></option>
                      <?
                      }
                      ?>
                    </select>      
                    <label for="reqPenghargaanPredikatId">Predikat</label>
                  </div>

                </div>

                <div class="row penghargaandetil">
                  <div class="input-field col s12 m12">
                    <label for="reqNamaDetil">Perihal Penghargaan</label>
                    <input placeholder="" type="text" class="easyui-validatebox" name="reqNamaDetil" id="reqNamaDetil" <?=$read?> value="<?=$reqNamaDetil?>" />
                  </div>
                </div>

                <div class="row penghargaandetil">
                  <div class="input-field col s12 ">
                    <input type="hidden" id="reqJenjangPeringkatDetil" value="<?=$reqJenjangPeringkatDetil?>" />
                    <select name="reqJenjangPeringkatId" id="reqJenjangPeringkatId">
                      <option value="" <? if($reqJenjangPeringkatId == "") echo 'selected';?>>Belum di tentukan</option>
                      <?
                      foreach ($arrpenghargaanjenjang as $key => $value)
                      {
                        $optionid= $value["ID"];
                        $optiontext= $value["NAMA"];
                        $optionselected= "";
                        if($reqJenjangPeringkatId == $optionid)
                          $optionselected= "selected";
                      ?>
                        <option value="<?=$optionid?>" <?=$optionselected?>><?=$optiontext?></option>
                      <?
                      }
                      ?>
                    </select>      
                    <label for="reqJenjangPeringkatId">Jenjang Peringkat</label>
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12 m5">
                    <label for="reqNoSK">No. SK</label>
                    <input placeholder="" required type="text" class="easyui-validatebox" name="reqNoSK" id="reqNoSK" <?=$read?> value="<?=$reqNoSK?>" />
                  </div>

                  <div class="input-field col s12 m5">
                    <label for="reqTglSK">Tgl. SK</label>
                    <input placeholder="" required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglSK" id="reqTglSK"  value="<?=$reqTglSK?>" maxlength="10" onKeyDown="return format_date(event,'reqTglSK');" />
                  </div>

                  <div class="input-field col s12 m2">
                    <input type="hidden" name="reqPegawaiId" value="<?=$reqPegawaiId?>">
                    <label for="reqTahun">Tahun</label>
                    <label for="reqTahun">Tahun</label>
                    <input type="hidden" name="reqTahun" id="reqTahun" value="<?=$reqTahun?>" />
                    <input placeholder="" type="text" id="reqTahunText" disabled value="<?=$reqTahun?>" />
                  </div>

                </div>

                <div class="row">
                  <div class="input-field col s12 m6">
                    <label for="reqPejabatPenetap">Pejabat Penetapan</label>
                    <input type="hidden" name="reqPejabatPenetapId" id="reqPejabatPenetapId" value="<?=$reqPejabatPenetapId?>" /> 
                    <input placeholder="" type="text" id="reqPejabatPenetap"  name="reqPejabatPenetap" <?=$read?> value="<?=$reqPejabatPenetap?>" class="easyui-validatebox" required />
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12">
                    <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                      <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                    </button>

                    <script type="text/javascript">
                      $("#kembali").click(function() { 
                        document.location.href = "app/loadUrl/app/pegawai_add_penghargaan_monitoring?reqId=<?=$reqId?>";
                      });
                    </script>

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
                <br/><br/><br/><br/><br/><br/>

              </form>
            </div>
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

<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

<style type="text/css">
  .select-dropdown {
    max-height:250px !important; overflow:auto !important;
  }
</style>

<!-- tambahan lib cek tanggal -->
<script src="lib/moment/moment-with-locales.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('select').material_select();
  });

  // ambil rating penggalian
  getarrpenghargaan= JSON.parse('<?=JSON_encode($arrpenghargaan)?>');
  // console.log(getarrpenghargaan);

  $("#reqRefPenghargaanId").change(function() { 

    setpenghargaan("data");
    // $("#reqJenjangPeringkatId, #reqNamaDetil").validatebox({required: false});
    // $("#reqJenjangPeringkatId, #reqNamaDetil").removeClass('validatebox-invalid');

    // $("#reqKematianTanggal, #reqKematianNo, #reqTanggalMeninggal").validatebox({required: true});
  });

  setpenghargaan("");
  function setpenghargaan(infomode)
  {
    if(infomode == "")
      vinfodata= "<?=$reqRefPenghargaanId?>";
    else
      vinfodata= $("#reqRefPenghargaanId").val();;

    infoid= vinfodata;
    valarrpenghargaan= getarrpenghargaan.filter(item => item.ID === infoid);

    if(Array.isArray(valarrpenghargaan) && valarrpenghargaan.length)
    {
      infodetil= valarrpenghargaan[0]["INFO_DETIL"];
      // console.log(valarrpenghargaan);
      // console.log(infodetil);

      $(".penghargaandetil").hide();
      if(infodetil == "1")
      {
        $(".penghargaandetil").show();

        if(infomode == ""){}
        else
        {
          $("#reqJenjangPeringkatDetil").val(infodetil);
        }
      }
      else
      {
        if(infomode == ""){}
        else
        {
          $("#reqJenjangPeringkatDetil, #reqJenjangPeringkatId, #reqNamaDetil").val("");
          $("#reqJenjangPeringkatId").material_select();
        }
      }
    }
    else
    {
      $(".penghargaandetil").hide();
    }


  }

  $('#reqTglSK').keyup(function() {
    var vtanggalakhir= $('#reqTglSK').val();
    var checktanggalakhir= moment(vtanggalakhir , 'DD-MM-YYYY', true).isValid();

    if(checktanggalakhir == true)
    {
      vtanggalakhir= $('#reqTglSK').val();
      vtahun= vtanggalakhir.substring(6,10);
      $("#reqTahun, #reqTahunText").val(vtahun);
    }

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
</html>