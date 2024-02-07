<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/personal.func.php");


$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('validasi/SuratTandaLulus');

$sessionLoginLevel= $this->LOGIN_LEVEL;
$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "011306";
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

$arrjenisstl= [];
$arrinfocombo= [];
$arrinfocombo= array(
  array("id"=>"", "text"=>"")
  , array("id"=>"1", "text"=>"STL Ujian Dinas I")
  , array("id"=>"2", "text"=>"STL Ujian Dinas II")
  , array("id"=>"3", "text"=>"STL Ujian Kenaikan Pangkat Penyesuaian Ijazah SMA")
  , array("id"=>"4", "text"=>"STL Ujian Kenaikan Pangkat Penyesuaian Ijazah D-4/S-1")
  , array("id"=>"5", "text"=>"STL Ujian Kenaikan Pangkat Penyesuaian Ijazah S-2")
);
for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
{
  $arrdata= [];
  $arrdata["id"]= $arrinfocombo[$icombo]["id"];
  $arrdata["text"]= $arrinfocombo[$icombo]["text"];
  array_push($arrjenisstl, $arrdata);
}

$statement= "";
$set= new SuratTandaLulus();

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

$reqValRowId= $set->getField('SURAT_TANDA_LULUS_ID');
if(empty($reqValRowId))
{
  $infoperubahan= "Data Baru";
}

$reqTglStlud= dateToPageCheck($set->getField('TANGGAL_STLUD'));$valTglStlud= checkwarna($reqPerubahanData, 'TANGGAL_STLUD', "date");
$reqTanggalMulai= dateToPageCheck($set->getField('TANGGAL_MULAI'));$valTanggalMulai= checkwarna($reqPerubahanData, 'TANGGAL_MULAI', "date");
$reqTanggalAkhir= dateToPageCheck($set->getField('TANGGAL_AKHIR'));$valTanggalAkhir= checkwarna($reqPerubahanData, 'TANGGAL_AKHIR', "date");
$reqJenisId= $set->getField('JENIS_ID');$valJenisId= checkwarna($reqPerubahanData, 'JENIS_ID', $arrjenisstl, array("id", "text"), $reqTempValidasiHapusId);
$reqNoStlud= $set->getField('NO_STLUD');$valNoStlud= checkwarna($reqPerubahanData, 'NO_STLUD', "", "", $reqTempValidasiHapusId);
$reqPendidikanRiwayatId= $set->getField('PENDIDIKAN_RIWAYAT_ID');
  // echo $reqPendidikanRiwayatId;exit();
$reqPendidikanId= $set->getField('PENDIDIKAN_ID');
$reqNilaiNpr= dotToComma($set->getField('NILAI_NPR'));$valNilaiNpr= checkwarna($reqPerubahanData, 'NILAI_NPR', "", "", $reqTempValidasiHapusId);
$reqNilaiNt= dotToComma($set->getField('NILAI_NT'));$valNilaiNt= checkwarna($reqPerubahanData, 'NILAI_NT', "", "", $reqTempValidasiHapusId);
$LastLevel= $set->getField('LAST_LEVEL');
$tempAksiProses= "";
if($sessionLoginLevel < $LastLevel)
$tempAksiProses= "1";
// echo $set->query;exit;
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
        // alert($("#ff").form('validate'));
        if($("#ff").form('validate') == false){
          return false;
        }
        else
        $("#reqSubmit").click();

 
      });


       $('#ff').form({
        url:'validasi/surat_tanda_lulus_json/add',
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
            document.location.href= "app/loadUrl/verifikasi/validasi_verifikator/";
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
<link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

</head>

<body>    
  <!--Basic Form-->
  <div id="basic-form" class="section">
    <div class="row">
     <div class="col s12 m10 offset-m1">

       <ul class="collection card">
         <li class="collection-item ubah-color-warna"><?=$infoperubahan?> SURAT TANDA LULUS</li>
         <li class="collection-item">

          <form id="ff" method="post" enctype="multipart/form-data">

            <div class="row">
              <div class="input-field col s12 m6">
                <label for="reqJenisId" class="active">
                 Surat Tanda Lulus
                    <?
                    if(!empty($valJenisId['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valJenisId['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                </label>
                <select name="reqJenisId" <?=$disabled?> id="reqJenisId" >
                  <option value="" <? if($reqJenisId == "") echo 'selected';?>></option>
                  <?
                  foreach($arrjenisstl as $item) 
                  {
                    $selectvalid= $item["id"];
                    $selectvaltext= $item["text"];
                    ?>
                    <option value="<?=$selectvalid?>" <? if($reqJenisId == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                    <?
                  }
                  ?>
                </select>
              </div>
            </div>
            
            <div class="row">
            	
            </div>
            
            <span>
              <div class="row">
                <div class="input-field col s12 m5">
                  <label for="reqNoStlud" class="<?=$valNoStlud['warna']?>">
                    No STL
                    <?
                    if(!empty($valNoStlud['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNoStlud['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input type="text" class="easyui-validatebox" required id="reqNoStlud" name="reqNoStlud" value="<?=$reqNoStlud?>" />
                </div>
                <div class="input-field col s12 m2">
                  <label for="reqTglStlud" class="<?=$valTglStlud['warna']?>">
                    Tgl STL
                    <?
                    if(!empty($valTglStlud['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTglStlud['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglStlud" id="reqTglStlud"  value="<?=$reqTglStlud?>" maxlength="10" onKeyDown="return format_date(event,'reqTglStlud');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m2">
                  <label for="reqTanggalMulai" class="<?=$valTanggalMulai['warna']?>">
                    Tanggal Mulai
                    <?
                    if(!empty($valTanggalMulai['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTanggalMulai['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalMulai" id="reqTanggalMulai" value="<?=$reqTanggalMulai?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalMulai');"/>
                </div>
                <div class="input-field col s12 m2">
                  <label for="reqTanggalAkhir" class="<?=$valTanggalAkhir['warna']?>">
                    Tanggal Akhir
                    <?
                    if(!empty($valTanggalAkhir['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTanggalAkhir['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalAkhir" id="reqTanggalAkhir" value="<?=$reqTanggalAkhir?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalAkhir');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m3">
                  <label for="reqNilaiNpr" class="<?=$valNilaiNpr['warna']?>">
                    Nilai Persentasi (NPR)
                    <?
                    if(!empty($valNilaiNpr['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNilaiNpr['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input type="text" id="reqNilaiNpr" name="reqNilaiNpr" value="<?=$reqNilaiNpr?>" onkeypress='kreditvalidate(event, this)' />
                </div>
                <div class="input-field col s12 m3">
                  <label for="reqNilaiNt">Nilai Terbilang (NT)</label>
                  <input type="text" id="reqNilaiNt" name="reqNilaiNt" value="<?=$reqNilaiNt?>" onkeypress='kreditvalidate(event, this)' />
                </div>
              </div>

              <div id="reqinfopendidikan">
                <div class="row">
                  <div class="input-field col s12 m6">
                    <input type="hidden" name="reqPendidikanRiwayatId" id="reqPendidikanRiwayatId" value="<?=$reqPendidikanRiwayatId?>" />
                    <input type="hidden" name="reqPendidikanId" id="reqPendidikanId" value="<?=$reqPendidikanId?>" />
                    <select id="reqPendidikan"></select>
                    <label for="reqPendidikan" >
                    </label>
                  </div>
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

                  <input type="hidden" name="reqPeriode" value="<?=$reqPeriode?>" />
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
                      ?>

                      <?
                    }
                    ?>
                </div>
              </div>
              
            </span>

            <!-- </div> -->
          </form>
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
  
  $('#reqNoUrutCetak,#reqTh,#reqBl').bind('keyup paste', function(){
   this.value = this.value.replace(/[^0-9]/g, '');
 });

</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>