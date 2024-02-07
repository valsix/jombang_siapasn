<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/personal.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('validasi/Penghargaan');
$this->load->model('PejabatPenetap');
$pejabat_penetap= new PejabatPenetap();

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "010901";
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

$arrpenghargaan= [];
$arrinfocombo= [];
$arrinfocombo= array(
  array("id"=>"", "text"=>"Belum di tentukan")
  , array("id"=>"1", "text"=>"Satya Lencana Karya Satya X (Perunggu)")
  , array("id"=>"2", "text"=>"Satya Lencana Karya Satya XX (Perak)")
  , array("id"=>"3", "text"=>"Satya Lencana Karya Satya XXX (Emas)")
);
for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
{
  $arrdata= [];
  $arrdata["id"]= $arrinfocombo[$icombo]["id"];
  $arrdata["text"]= $arrinfocombo[$icombo]["text"];
  array_push($arrpenghargaan, $arrdata);
}

$statement= "";
$set= new Penghargaan();

$infoperubahan= "Perubahan Data";
if(!empty($reqRowHapusId))
{
  $infoperubahan= "Hapus Data";
  $set->selectByParamsPersonal(array(), -1, -1, $reqId, $reqRowHapusId, "", $statement);
}
else
  $set->selectByParamsPersonal(array(), -1, -1, $reqId, $reqRowHapusId, $reqRowId, $statement);

// echo $set->query;exit;
$set->firstRow();
$reqTempValidasiId= $set->getField('TEMP_VALIDASI_ID');
$reqTempValidasiHapusId= $set->getField('TEMP_VALIDASI_HAPUS_ID');
$reqValidasi= $set->getField('VALIDASI');
$reqPerubahanData= $set->getField('PERUBAHAN_DATA');

$reqValRowId= $set->getField('PENGHARGAAN_ID');
if(empty($reqValRowId))
{
  $infoperubahan= "Data Baru";
}

$reqValRowId= $set->getField('PENGHARGAAN_ID');
$reqPejabatPenetapId= $set->getField('PEJABAT_PENETAP_ID');
$reqPejabatPenetap= $set->getField('PEJABAT_PENETAP_NAMA');$valPejabatPenetap= checkwarna($reqPerubahanData, 'PEJABAT_PENETAP');
$reqNamaPenghargaan= $set->getField('NAMA');$valNamaPenghargaan= checkwarna($reqPerubahanData, 'NAMA');
$reqTahun= $set->getField('TAHUN');$valTahun= checkwarna($reqPerubahanData, 'TAHUN');
$reqTglSK= dateToPageCheck($set->getField('TANGGAL_SK'));$valTglSK= checkwarna($reqPerubahanData, 'TANGGAL_SK', "date");
$reqNoSK= $set->getField('NO_SK');$valNoSK= checkwarna($reqPerubahanData, 'NO_SK');

if($set->getField('PEJABAT_PENETAP_ID')==''){
  $reqStatus='baru';
  $reqDisplayBaru='';
  $reqDisplay='none';
}else{
  $reqDisplayBaru='none';
  $reqDisplay='';
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
        url:'validasi/penghargaan_json/add',
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
         <li class="collection-item ubah-color-warna"><?=$infoperubahan?>  PENGHARGAAN</li>
         <li class="collection-item">
          <div class="row">
            <form id="ff" method="post" enctype="multipart/form-data">

              <div class="row">
                <div class="input-field col s12 ">
                  <input type="hidden" name="reqPegawaiId" value="<?=$reqPegawaiId?>">
                  <label for="reqTahun">Tahun</label>
                  <label for="reqTahun" class="<?=$valTahun['warna']?>">
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
                  <input type="text" name="reqTahun" <?=$read?> value="<?=$reqTahun?>" id="reqTahun" title="Tahun harus diisi" class="required" />
                  <script>
                    $("#reqTahun").keypress(function(e) {
                      if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
                      {
                        return false;
                      }
                    });       
                  </script>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 ">
                   <select <?=$disabled?> name="reqNamaPenghargaan">
                    <?
                    foreach($arrpenghargaan as $item) 
                    {
                      $selectvalid= $item["id"];
                      $selectvaltext= $item["text"];
                    ?>
                    <option value="<?=$selectvalid?>" <? if($reqNamaPenghargaan == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                    <?
                    }
                    ?>
                  </select>     
                  <label for="reqNamaPenghargaan" class="<?=$valNamaPenghargaan['warna']?>">
                    Nama Penghargaan
                    <?
                    if(!empty($valNamaPenghargaan['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNamaPenghargaan['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 ">
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
                  <input type="text" name="reqNoSK" id="reqNoSK" <?=$read?> value="<?=$reqNoSK?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 ">
                  <label for="reqTglSK" class="<?=$valTglSK['warna']?>">
                    Tgl. SK
                    <?
                    if(!empty($valTglSK['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTglSK['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglSK" id="reqTglSK"  value="<?=$reqTglSK?>" maxlength="10" onKeyDown="return format_date(event,'reqTglSK');"/>
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
  });

  $('.materialize-textarea').trigger('autoresize');

</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>