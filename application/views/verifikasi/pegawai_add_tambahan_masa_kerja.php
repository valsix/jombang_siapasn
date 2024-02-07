<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/personal.func.php");


$this->load->model('validasi/TambahanMasaKerja');
$this->load->model('Pangkat');

$CI =& get_instance();
$CI->checkUserLogin();

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "011303";
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

$arrpangkat= [];
$set= new Pangkat();
$set->selectByParams(array());
// print_r ($set);exit;
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["id"]= $set->getField("PANGKAT_ID");
  $arrdata["text"]= $set->getField("KODE");
  array_push($arrpangkat, $arrdata);
}

$statement= "";
$set= new TambahanMasaKerja();

$infoperubahan= "Perubahan Data";
if(!empty($reqRowHapusId))
{
  $infoperubahan= "Hapus Data";
  $set->selectByPersonal(array(), -1, -1, $reqId, $reqRowHapusId, "", $statement);
}
else
  $set->selectByPersonal(array(), -1, -1, $reqId, $reqRowHapusId, $reqRowId, $statement);

$set->firstRow();
$reqTempValidasiId= $set->getField('TEMP_VALIDASI_ID');
$reqTempValidasiHapusId= $set->getField('TEMP_VALIDASI_HAPUS_ID');
$reqValidasi= $set->getField('VALIDASI');
$reqPerubahanData= $set->getField('PERUBAHAN_DATA');

$reqValRowId= $set->getField('TAMBAHAN_MASA_KERJA_ID');
if(empty($reqValRowId))
{
  $infoperubahan= "Data Baru";
}


$reqNoSK= $set->getField('NO_SK');$valNoSK= checkwarna($reqPerubahanData, 'NO_SK', "", "", $reqTempValidasiHapusId);
$reqTanggalSk= dateToPageCheck($set->getField('TANGGAL_SK'));$valTanggalSk= checkwarna($reqPerubahanData, 'TANGGAL_SK', "date", $reqTempValidasiHapusId);

$reqTmtSk= dateToPageCheck($set->getField('TMT_SK'));$valTmtSk= checkwarna($reqPerubahanData, 'TMT_SK', "date", $reqTempValidasiHapusId);
$reqTahunTambahan= $set->getField('TAHUN_TAMBAHAN');$valTahunTambahan= checkwarna($reqPerubahanData, 'TAHUN_TAMBAHAN', "", "", $reqTempValidasiHapusId);
$reqBulanTambahan= $set->getField('BULAN_TAMBAHAN');$valBulanTambahan= checkwarna($reqPerubahanData, 'BULAN_TAMBAHAN', "", "", $reqTempValidasiHapusId);
$reqTahunBaru= $set->getField('TAHUN_BARU');$valTahunBaru= checkwarna($reqPerubahanData, 'TAHUN_BARU', "", "", $reqTempValidasiHapusId);
$reqBulanBaru= $set->getField('BULAN_BARU');$valBulanBaru= checkwarna($reqPerubahanData, 'BULAN_BARU', "", "", $reqTempValidasiHapusId);
$reqStatus= $set->getField('STATUS');
$reqGolRuang= $set->getField('PANGKAT_ID');$reqGolRuang= $set->getField('PANGKAT_ID');$valGolRuang= checkwarna($reqPerubahanData, 'PANGKAT_ID', $arrpangkat, array("id", "text"), $reqTempValidasiHapusId);
$reqNoNota= $set->getField('NO_NOTA');$valNoNota= checkwarna($reqPerubahanData, 'NO_NOTA', "", "", $reqTempValidasiHapusId);
$reqTglNota= dateToPageCheck($set->getField('TANGGAL_NOTA'));$valTglNota= checkwarna($reqPerubahanData, 'TANGGAL_NOTA', "date", $reqTempValidasiHapusId);
$reqGajiPokok= $set->getField('GAJI_POKOK');$valGajiPokok= checkwarna($reqPerubahanData, 'GAJI_POKOK', "", "", $reqTempValidasiHapusId);
$reqPejabatPenetapId= $set->getField('PEJABAT_PENETAP_ID');
$reqPejabatPenetap= $set->getField('PEJABAT_PENETAP');$valPejabatPenetap= checkwarna($reqPerubahanData, 'PEJABAT_PENETAP', "", "", $reqTempValidasiHapusId);




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
    function setGaji()
    {
      var reqTglSk= reqPangkatId= reqMasaKerja= "";
      reqTglSk= $("#reqTanggalSk").val();
      reqPangkatId= $("#reqGolRuang").val();
      reqMasaKerja= $("#reqTahunBaru").val();

      urlAjax= "gaji_pokok_json/gajipokok?reqPangkatId="+reqPangkatId+"&reqMasaKerja="+reqMasaKerja+"&reqTglSk="+reqTglSk;
      $.ajax({'url': urlAjax,'success': function(data){
       //if(data == ''){}
         //else
         //{
           tempValueGaji= parseFloat(data);
           //tempValueGaji= (tempValueGaji * 80) / 100;
           $("#reqGajiPokok").val(FormatCurrency(tempValueGaji));
         //}
       }});
    }

    $(function(){
      <?
      if($reqGajiPokok == "")
      {
      ?>
      setGaji();
      <?
      }
      ?>

      $("#reqGolRuang").change(function(){
        setGaji();
      });

      $("#reqTanggalSk, #reqTahunBaru").keyup(function(){
        setGaji();
      });

      $('#ff').form({
        url:'validasi/tambahan_masa_kerja_json/add',
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
  <link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

</head>

<body>    
  <!--Basic Form-->
  <div id="basic-form" class="section">
    <div class="row">
     <div class="col s12 m10 offset-m1">

       <ul class="collection card">
         <li class="collection-item ubah-color-warna"> <?=$infoperubahan?> Peninjauan Masa Kerja</li>
         <li class="collection-item">
          <div class="row">
            <form id="ff" method="post" enctype="multipart/form-data">
              
              <div class="row" id="reqinfobkn">
                <div class="input-field col s12 m6">
                  <label for="reqNoNota" class="<?=$valNoNota['warna']?>">
                    No Nota BKN
                    <?
                    if(!empty($valNoNota['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNoNota['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input type="text" name="reqNoNota" id="reqNoNota" <?=$read?> value="<?=$reqNoNota?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTglNota" class="<?=$valTglNota['warna']?>">
                    Tgl Nota BKN
                    <?
                    if(!empty($valTglNota['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTglNota['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglNota" id="reqTglNota"  value="<?=$reqTglNota?>" maxlength="10" onKeyDown="return format_date(event,'reqTglNota');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNoSK" class="<?=$valNoSK['warna']?>">
                    No. SK
                    <?
                    if(!empty($valNoSK['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNoSK['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input type="text" class="easyui-validatebox" required name="reqNoSK" id="reqNoSK" value="<?=$reqNoSK?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTanggalSk" class="<?=$valTanggalSk['warna']?>">
                    Tanggal Sk
                    <?
                    if(!empty($valTanggalSk['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTanggalSk['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalSk" id="reqTanggalSk"  value="<?=$reqTanggalSk?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalSk');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                   <select <?=$disabled?> name="reqGolRuang">
                    <?
                    foreach($arrpangkat as $item) 
                    {
                      $selectvalid= $item["id"];
                      $selectvaltext= $item["text"];
                      ?>
                      <option value="<?=$selectvalid?>" <? if($reqGolRuang == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                      <?
                    }
                    ?>
                  </select>
                  <label for="reqGolRuang" class="<?=$valGolRuang['warna']?>">
                    Gol/Ruang
                    <?
                    if(!empty($valGolRuang['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valGolRuang['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTmtSk" class="<?=$valTmtSk['warna']?>">
                    TMT Sk
                    <?
                    if(!empty($valTmtSk['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTmtSk['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTmtSk" id="reqTmtSk"  value="<?=$reqTmtSk?>" maxlength="10" onKeyDown="return format_date(event,'reqTmtSk');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m3">
                  <label for="reqTahunTambahan" class="<?=$valTahunTambahan['warna']?>">
                    Tambahan Masa Kerja Th
                    <?
                    if(!empty($valTahunTambahan['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTahunTambahan['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input placeholder type="text" class="easyui-validatebox" required name="reqTahunTambahan" <?=$read?> value="<?=$reqTahunTambahan?>" id="reqTahunTambahan" />
                </div>

                <div class="input-field col s12 m3">
                  <label for="reqBulanTambahan" class="<?=$valBulanTambahan['warna']?>">
                    Tambahan Masa Kerja Bl
                    <?
                    if(!empty($valBulanTambahan['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valBulanTambahan['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input placeholder type="text" class="easyui-validatebox" required name="reqBulanTambahan" <?=$read?> value="<?=$reqBulanTambahan?>" id="reqBulanTambahan" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m3">
                  <label for="reqTahunBaru" class="<?=$valTahunBaru['warna']?>">
                    Masa Kerja Th
                    <?
                    if(!empty($valTahunBaru['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTahunBaru['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input placeholder type="text" class="easyui-validatebox" required name="reqTahunBaru" <?=$read?> value="<?=$reqTahunBaru?>" id="reqTahunBaru" />
                </div>

                <div class="input-field col s12 m3">
                  <label for="reqBulanBaru" class="<?=$valBulanBaru['warna']?>">
                    Masa Kerja Bl
                    <?
                    if(!empty($valBulanBaru['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valBulanBaru['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input placeholder type="text" class="easyui-validatebox" required name="reqBulanBaru" <?=$read?> value="<?=$reqBulanBaru?>" id="reqBulanBaru" />
                </div>

                <div class="input-field col s12 m6">
                  <label for="reqGajiPokok" class="<?=$valGajiPokok['warna']?>">
                    Gaji Pokok
                    <?
                    if(!empty($valGajiPokok['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valGajiPokok['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input type="text" placeholder class="easyui-validatebox" required id="reqGajiPokok" name="reqGajiPokok" OnFocus="FormatAngka('reqGajiPokok')" OnKeyUp="FormatUang('reqGajiPokok')" OnBlur="FormatUang('reqGajiPokok')" value="<?=numberToIna($reqGajiPokok)?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqPejabatPenetap" class="<?=$valPejabatPenetap['warna']?>">
                    Pejabat Penetapan
                    <?
                    if(!empty($valPejabatPenetap['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valPejabatPenetap['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input type="hidden" name="reqPejabatPenetapId" id="reqPejabatPenetapId" value="<?=$reqPejabatPenetapId?>" /> 
                  <input type="text" id="reqPejabatPenetap"  name="reqPejabatPenetap" <?=$read?> value="<?=$reqPejabatPenetap?>" class="easyui-validatebox" required />
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
                  <!-- <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>

                  <script type="text/javascript">
                    $("#kembali").click(function() { 
                      document.location.href = "app/loadUrl/app/pegawai_add_bahasa_monitoring?reqId=<?=$reqId?>";
                    });
                  </script> -->

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

                  <?
                  if($reqRowId == "" || ($reqRowId !== "" && $reqStatus !== "1"))
                  {
                  ?>
                  <button class="btn waves-effect waves-light green" style="font-size:9pt" type="submit" name="action">Simpan
                    <i class="mdi-content-save left hide-on-small-only"></i>
                  </button>
                  <?
                  }
                  ?>
                  
                  <?
                  if($reqRowId == ""){}
                  else
                  {
                    if($reqStatus == "1")
                    {
                  ?>
                    <button class="btn waves-effect waves-light pink" style="font-size:9pt" type="button" id="reqaktif">
                    Aktifkan
                    <i class="mdi-content-save left hide-on-small-only"></i>
                    </button>
                  <?
                    }
                    else
                    {
                  ?>
                    <button class="btn waves-effect waves-light pink" style="font-size:9pt" type="button" id="reqbatal">
                    Batal
                    <i class="mdi-content-save left hide-on-small-only"></i>
                    </button>
                  <?
                    }
                  }
                  ?>

                  <?
                  }
                  ?>

                </div>
              </div>
              <div class="row"><br/><br/><br/><br/></div>
            </form>
          </div>
        </li>
      </ul>
    </div>
  </div>
</div>

<!-- jQuery Library -->
<!-- <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script> -->

<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('select').material_select();

    $("#reqbatal,#reqaktif").click(function() { 
      var id= $(this).attr('id');

      if(id == "reqbatal")
      {
        modeinfo= "Apakah Anda Yakin, batal peninjauan masa kerja?"
        mode= "tambahanmasakerja_0";
      }
      else
      {
        modeinfo= "Apakah Anda Yakin, aktifkan peninjauan masa kerja?"
        mode= "tambahanmasakerja_1";
      } 

      mbox.custom({
       message: modeinfo,
       options: {close_speed: 100},
       buttons: [
       {
         label: 'Ya',
         color: 'green darken-2',
         callback: function() {
           $.getJSON("tambahan_masa_kerja_json/delete/?reqMode="+mode+"&reqRowId=<?=$reqRowId?>",
            function(data){
              mbox.alert(data.PESAN, {open_speed: 500}, interval = window.setInterval(function() 
              {
                clearInterval(interval);
                document.location.href= "app/loadUrl/app/pegawai_add_tambahan_masa_kerja/?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>";
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

    });

  });

  $('.materialize-textarea').trigger('autoresize');

  $('#reqTahunTambahan,#reqBulanTambahan,#reqTahunBaru,#reqBulanBaru').bind('keyup paste', function(){
    this.value = this.value.replace(/[^0-9]/g, '');
  });

</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>