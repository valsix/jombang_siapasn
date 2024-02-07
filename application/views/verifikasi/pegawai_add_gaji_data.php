<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/personal.func.php");


$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('validasi/GajiRiwayat');
$this->load->model('Pangkat');

$pangkat= new Pangkat();
$pangkat->selectByParams(array());

$sessionLoginLevel= $this->LOGIN_LEVEL;
$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$reqPeriode= $this->input->get("reqPeriode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "010302";
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
$set= new GajiRiwayat();

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

$reqValRowId= $set->getField('GAJI_RIWAYAT_ID');
if(empty($reqValRowId))
{
  $infoperubahan= "Data Baru";
}

$reqNoSk= $set->getField('NO_SK');$valNoSk= checkwarna($reqPerubahanData, 'NO_SK');
$reqTanggalSk= dateToPageCheck($set->getField('TANGGAL_SK'));$valTanggalSk= checkwarna($reqPerubahanData, 'TANGGAL_SK', "date");
$reqTmtSk= dateToPageCheck($set->getField('TMT_SK'));$valTmtSk= checkwarna($reqPerubahanData, 'TMT_SK', "date");
$reqTh= $set->getField('MASA_KERJA_TAHUN');$valTahunBaru= checkwarna($reqPerubahanData, 'MASA_KERJA_TAHUN');
$reqBl= $set->getField('MASA_KERJA_BULAN');$valBulanBaru= checkwarna($reqPerubahanData, 'MASA_KERJA_BULAN');
$reqGolRuang= $set->getField('PANGKAT_ID');$reqGolRuang= $set->getField('PANGKAT_ID');$valGolRuang= checkwarna($reqPerubahanData, 'PANGKAT_ID', $arrpangkat, array("id", "text"));
$reqNoNota= $set->getField('NO_NOTA');$valNoNota= checkwarna($reqPerubahanData, 'NO_NOTA');
$reqTglNota= dateToPageCheck($set->getField('TANGGAL_NOTA'));$valTglNota= checkwarna($reqPerubahanData, 'TANGGAL_NOTA', "date");
$reqGajiPokok= $set->getField('GAJI_POKOK');$valGajiPokok= checkwarna($reqPerubahanData, 'GAJI_POKOK');
$reqPejabatPenetapId= $set->getField('PEJABAT_PENETAP_ID');
$reqPejabatPenetap= $set->getField('PEJABAT_PENETAP');$valPejabatPenetap= checkwarna($reqPerubahanData, 'PEJABAT_PENETAP');
$reqJenis= $set->getField('JENIS_KENAIKAN');
$reqJenisNama= $set->getField('JENIS_KENAIKAN_NAMA');$valJenisNama= checkwarna($reqPerubahanData, 'JENIS_KENAIKAN_NAMA');

$reqLastProsesUser= $set->getField('LAST_PROSES_USER');
$LastLevel= $set->getField('LAST_LEVEL');

$tempAksiProses= "";
if($sessionLoginLevel < $LastLevel)
$tempAksiProses= "1";
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
      reqMasaKerja= $("#reqTh").val();

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

	  $("#reqTanggalSk, #reqTh").keyup(function(){
        setGaji();
      });

      $("#reqsimpan").click(function() { 
        if($("#ff").form('validate') == false){
          return false;
        }

        var reqTanggal= "";
        reqTanggal= $("#reqTmtSk").val();
        var s_url= "hari_libur_json/hakentrigaji?reqMode=gaji&reqTanggal="+reqTanggal;
        $.ajax({'url': s_url,'success': function(dataajax){
              // return false;
              dataajax= dataajax.split(";");
              rowid= parseInt(dataajax[0]);
              infodata= dataajax[1];
              if(rowid == 1)
              {
                mbox.alert('Anda tidak berhak menambah data di atas tmt sk ' + infodata, {open_speed: 0});
                return false;
              }
              else
                $("#reqSubmit").click();
            }});

      });
	  
      $('#ff').form({
        url:'validasi/gaji_riwayat_json/add',
        onSubmit:function(){

          // return false;

          var reqGolRuang= "";
          reqGolRuang= $("#reqGolRuang").val();

          if(reqGolRuang == "")
          {
           $.messager.alert('Info', "Lengkapi data golongan ruang terlebih dahulu", 'info');
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
          // data = data.split("-");
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
         <li class="collection-item ubah-color-warna"><?=$infoperubahan?> Gaji</li>
         <li class="collection-item">


          <div class="row">
            <form id="ff" method="post" enctype="multipart/form-data">

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNoSk" class="<?=$valNoSk['warna']?>">
                    No. SK
                    <?
                    if(!empty($valNoSk['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNoSk['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input type="text" class="easyui-validatebox" required id="reqNoSk" name="reqNoSk" <?=$read?> value="<?=$reqNoSk?>" />
                </div>
                <div class="input-field col s12 m6">
                    Tgl. SK
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
                <div class="input-field col s6 m3">
                  <label for="reqTh" class="<?=$valTahunBaru['warna']?>">
                    Masa Kerja Tahun
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
                  <input type="hidden" name="reqTempTh" value="<?=$reqTh?>" />
                  <input type="text" class="easyui-validatebox" required name="reqTh" <?=$read?> value="<?=$reqTh?>" id="reqTh" title="Masa kerja tahun harus diisi" />
                </div>

                <div class="input-field col s6 m3">
                  <label for="reqBl" class="<?=$valBulanBaru['warna']?>">
                    Masa Kerja Bulan
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
                  <input type="hidden" name="reqTempBl" value="<?=$reqBl?>" />
                  <input type="text" class="easyui-validatebox" required name="reqBl" <?=$read?> value="<?=$reqBl?>" id="reqBl" title="Masa kerja bulan diisi" />
                </div>
                
                <div class="input-field col s12 m6">
                  <label for="reqGajiPokok" class="<?=$valGajiPokok['warna']?>">
                    Masa Kerja Bulan
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
                  <?
                  if($reqJenis == "3")
                  {
                    ?>
                    <label for="reqJenisNama">Jenis</label>
                    <input type="hidden" id="reqJenis" name="reqJenis" value="3" />
                    <input type="text" id="reqJenisNama" value="Gaji Berkala" disabled />
                    <?
                  }
                  else
                  {
                    ?>
                    <label for="reqJenisNama">Jenis</label>
                    <input type="hidden" id="reqJenis" name="reqJenis" value="<?=$reqJenis?>" />
                    <input type="text" id="reqJenisNama" value="<?=$reqJenisNama?>" disabled />
                    <?
                  }
                  ?>
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

                  <?
                  if($reqJenis == "3")
                  {
                   if($tempAksiProses == "1"){}
                     else
                     {
                      ?>
                      <input type="hidden" name="reqPeriode" value="<?=$reqPeriode?>" />
                      <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                      <input type="hidden" name="reqId" value="<?=$reqId?>" />
                      <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                      <input type="hidden" name="reqTempValidasiId" value="<?=$reqTempValidasiId?>" />
                      <input type="hidden" name="reqTempValidasiHapusId" value="<?=$reqTempValidasiHapusId?>" />
                      <?
                      if($tempAksesMenu == "A")
                      {
                        ?>
                        <button type="submit" style="display:none" id="reqSubmit"></button>
                        <button class="btn waves-effect waves-light green" style="font-size:9pt" type="button" id="reqsimpan">Simpan
                          <i class="mdi-content-save left hide-on-small-only"></i>
                        </button>
                        <?
                      }
                      ?>

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
<!-- jQuery Library -->
<!--<script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script>-->

<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('select').material_select();
  });

  $('.materialize-textarea').trigger('autoresize');
  
  $('#reqTh,#reqBl').bind('keyup paste', function(){
   this.value = this.value.replace(/[^0-9]/g, '');
 });

</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>