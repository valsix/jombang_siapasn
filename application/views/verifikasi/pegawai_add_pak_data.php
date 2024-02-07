<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/personal.func.php");


// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);
$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('validasi/Pak');
$this->load->model('JabatanFt');


$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "010702";
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

$arrjabatanft= [];
$set= new JabatanFt();
$set->selectByParams(array());
// print_r ($set);exit;
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["id"]= $set->getField("JABATAN_FT_ID");
  $arrdata["text"]= $set->getField("NAMA");
  array_push($arrjabatanft, $arrdata);
}

// echo 'asdas';exit;
$statement= "";
$set= new Pak();

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

$reqValRowId= $set->getField('PAK_ID');
if(empty($reqValRowId))
{
  $infoperubahan= "Data Baru";
}

$reqJabatanFtId= $set->getField('JABATAN_FT_ID');
$reqCheckPakAwal = $set->getField('PAK_AWAL');
$reqJabatanFt= $set->getField('JABATAN');$valJabatanFt= checkwarna($reqPerubahanData, 'JABATAN_FT_ID', $arrjabatanft, array("id", "text"), $reqTempValidasiHapusId);
$reqNoSK= $set->getField('NO_SK');$valNoSK= checkwarna($reqPerubahanData, 'NO_SK');
// echo $reqNoSK;exit;
$reqTglMulai= dateToPageCheck($set->getField('TANGGAL_MULAI'));$valTglMulai= checkwarna($reqPerubahanData, 'TANGGAL_MULAI', "date");
$reqTglSelesai= dateToPageCheck($set->getField('TANGGAL_SELESAI'));$valTglSelesai= checkwarna($reqPerubahanData, 'TANGGAL_SELESAI', "date");
$reqTglSK= dateToPageCheck($set->getField('TANGGAL_SK'));$valTglSK= checkwarna($reqPerubahanData, 'TANGGAL_SK', "date");
$reqKreditUtama= dotToComma($set->getField('KREDIT_UTAMA'));$valKreditUtama= checkwarna($reqPerubahanData, 'KREDIT_UTAMA');
$reqKreditPenunjang= dotToComma($set->getField('KREDIT_PENUNJANG'));$valKreditPenunjang= checkwarna($reqPerubahanData, 'KREDIT_PENUNJANG');
$reqTotalKredit= dotToComma($set->getField('TOTAL_KREDIT'));$valTotalKredit= checkwarna($reqPerubahanData, 'TOTAL_KREDIT');

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
        url:'validasi/pak_json/add',
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

      $("#reqKreditUtama, #reqKreditPenunjang").keyup(function(){
        var reqKreditUtama= reqKreditPenunjang= "";
        reqKreditUtama= $("#reqKreditUtama").val();
        reqKreditUtama= String(reqKreditUtama);
        reqKreditUtama= reqKreditUtama.replace(",", ".");

        reqKreditPenunjang= $("#reqKreditPenunjang").val();
        reqKreditPenunjang= String(reqKreditPenunjang);
        reqKreditPenunjang= reqKreditPenunjang.replace(",", ".");

        reqTotalKredit= parseFloat(reqKreditUtama) + parseFloat(reqKreditPenunjang);
        reqTotalKredit= reqTotalKredit.toFixed(3);
        reqTotalKredit= String(reqTotalKredit);
        reqTotalKredit= reqTotalKredit.replace(".", ",");
        $("#reqTotalKredit").val(reqTotalKredit);
      });
	  
	  $('input[id^="reqJabatanFt"]').autocomplete({
      source:function(request, response){
        var id= this.element.attr('id');
        var replaceAnakId= replaceAnak= urlAjax= "";
		
        if (id.indexOf('reqJabatanFt') !== -1)
        {
          var element= id.split('reqJabatanFt');
          var indexId= "reqJabatanFtId"+element[1];
          urlAjax= "jabatan_ft_json/namajabatan";
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
				return {desc: element['desc'], id: element['id'], label: element['label'], satuan_kerja: element['satuan_kerja']};
              });
              response(array);
            }
          }
        })
      },
      focus: function (event, ui) 
      { 
        var id= $(this).attr('id');
        if (id.indexOf('reqJabatanFt') !== -1)
        {
          var element= id.split('reqJabatanFt');
          var indexId= "reqJabatanFtId"+element[1];
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
         <li class="collection-item ubah-color-warna"><?=$infoperubahan?> PAK</li>
         <li class="collection-item">

            <form id="ff" method="post" enctype="multipart/form-data">
              
              <div class="row">
                <div class="input-field col s12 m3">
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
                  <input type="text" class="easyui-validatebox" required name="reqNoSK" id="reqNoSK" <?=$read?> value="<?=$reqNoSK?>" />
                </div>
                <div class="input-field col s12 m3">
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

                <div class="input-field col s12 m1">
                    <input type="checkbox" id="reqCheckPakAwal" name="reqCheckPakAwal" value="1" <? if($reqCheckPakAwal == 1) echo 'checked'?>/>
                    <label for="reqCheckPakAwal">Pertama</label>
                </div>

              </div>

              <div class="row">
                <div class="input-field col s12 m3">
                  <label for="reqTglMulai" class="<?=$valTglMulai['warna']?>">
                  Tgl. Mulai Penilaian
                  </label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglMulai" id="reqTglMulai"  value="<?=$reqTglMulai?>" maxlength="10" onKeyDown="return format_date(event,'reqTglMulai');"/>
                </div>
                <div class="input-field col s12 m3">
                  <label for="reqTglSelesai" class="<?=$valTglSelesai['warna']?>">
                  Tgl Selesai Penilaian
                  <?
                  if(!empty($valTglSelesai['data']))
                  {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTglSelesai['data']?></span>
                    </a>
                    <?
                  }
                  ?>
                  </label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglSelesai" id="reqTglSelesai"  value="<?=$reqTglSelesai?>" maxlength="10" onKeyDown="return format_date(event,'reqTglSelesai');"/>
                </div>
              </div>

               <div class="row">
                <div class="input-field col s12 m3">
                  <label for="reqKredit" class="<?=$valKreditUtama['warna']?>">
                    Kredit Utama
                    <?
                    if(!empty($valKreditUtama['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valKreditUtama['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input type="text" id="reqKreditUtama" name="reqKreditUtama" <?=$read?>  value="<?=$reqKreditUtama?>" onkeypress='kreditvalidate(event, this)' />
                </div>
                <div class="input-field col s12 m3">
                  <label for="reqKreditPenunjang" class="<?=$valKreditPenunjang['warna']?>">
                    Kredit Penunjang
                    <?
                    if(!empty($valKreditPenunjang['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valKreditPenunjang['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input type="text" id="reqKreditPenunjang" name="reqKreditPenunjang" <?=$read?>  value="<?=$reqKreditPenunjang?>" onkeypress='kreditvalidate(event, this)' />
                </div>

                <div class="input-field col s12 m3">
                  <label for="reqKredit" class="<?=$valTotalKredit['warna']?>">
                    Total Kredit
                    <?
                    if(!empty($valTotalKredit['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTotalKredit['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input type="text" placeholder id="reqTotalKredit" name="reqTotalKredit" <?=$read?>  value="<?=$reqTotalKredit?>" onkeypress='kreditvalidate(event, this)' />
                </div>

              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqJabatanFt" class="<?=$valJabatanFt['warna']?>">
                    Nama Jabatan Fungsional Yang Diusulkan
                    <?
                    if(!empty($valJabatanFt['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valJabatanFt['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input type="hidden" name="reqJabatanFtId" id="reqJabatanFtId" value="<?=$reqJabatanFtId?>" /> 
                  <input type="text" id="reqJabatanFt" name="reqJabatanFt" <?=$read?> value="<?=$reqJabatanFt?>" class="easyui-validatebox" required />
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