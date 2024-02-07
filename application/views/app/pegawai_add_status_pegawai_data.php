<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('PegawaiStatus');
$this->load->model('StatusPegawai');
$this->load->model('StatusPegawaiKedudukan');

$statuspegawai= new StatusPegawai();
$pegawaikedudukan= new StatusPegawaiKedudukan();

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "010601";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

$statuspegawai->selectByParams(array());

if($reqRowId=="")
{
  $reqMode = 'insert';
}
else
{
  $reqMode = 'update';
  $statement= " AND A.PEGAWAI_STATUS_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
  $set= new PegawaiStatus();
  $set->selectByParams(array(), -1, -1, $statement);
  $set->firstRow();

  $reqStatusPegawaiId= $set->getField('STATUS_PEGAWAI_ID');
  $reqPegawaiKedudukanId= $set->getField('STATUS_PEGAWAI_KEDUDUKAN_ID');
  $reqTmt= dateToPageCheck($set->getField('TMT'));
  $reqNamaStatus= $set->getField('STATUS_PEGAWAI_INFO');
  $reqNamaKedudukan= $set->getField('KEDUDUKAN_INFO');
}

$pegawaikedudukan->selectByParams(array('STATUS_PEGAWAI_ID'=>$reqStatusPegawaiId),-1,-1, '');
// echo $pegawaikedudukan->query;exit;
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

  <script type="text/javascript"> 
    $(function(){
      $('#ff').form({
        url:'pegawai_status_json/add',
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
          //$.messager.alert('Info', infodata, 'info');
          mbox.alert(infodata, {open_speed: 500}, interval = window.setInterval(function() 
          {
           clearInterval(interval);
           mbox.close();
           document.location.href= "app/loadUrl/app/pegawai_add_status_pegawai_data/?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>";
         }, 1000));
		 $(".mbox > .right-align").css({"display": "none"});
          
        }
      });

      $("#reqStatusPegawaiId").change(function() { 
        var reqStatusPegawaiId= "";
        reqStatusPegawaiId= $("#reqStatusPegawaiId").val();
        $("#reqPegawaiKedudukanId option").remove();
        $("#reqPegawaiKedudukanId").material_select();

        $("<option value=''></option>").appendTo("#reqPegawaiKedudukanId");
        $.ajax({'url': "pegawai_status_json/combo/?reqId="+reqStatusPegawaiId,'success': function(dataJson) {
          var data= JSON.parse(dataJson);

          var items = "";
          items += "<option></option>";
          $.each(data, function (i, SingleElement) {
            items += "<option value='" + SingleElement.id + "'>" + SingleElement.text + "</option>";
          });
          $("#reqPegawaiKedudukanId").html(items);
          $("#reqPegawaiKedudukanId").material_select();
        }});
      });
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
  <!--Basic Form-->
  <div id="basic-form" class="section">
    <div class="row">
     <div class="col s12 m10 offset-m1">

       <ul class="collection card">
         <li class="collection-item ubah-color-warna">EDIT STATUS PEGAWAI</li>
         <li class="collection-item">

            <form id="ff" method="post" enctype="multipart/form-data">

              <div class="row">
                <div class="input-field col s12 m6">
                  <?/*
                    if($reqRowId == "")
                    {
                      */?>
                  <select <?=$disabled?> name="reqStatusPegawaiId" id="reqStatusPegawaiId">
                    <option value=""></option>
                    <? $statuspegawai->selectByParams(array(),-1,-1, '');
                    while($statuspegawai->nextRow()){?>
                    <option  value="<?=$statuspegawai->getField('STATUS_PEGAWAI_ID')?>" <? if($statuspegawai->getField('STATUS_PEGAWAI_ID')==$reqStatusPegawaiId) echo 'selected'?>><?=$statuspegawai->getField('NAMA')?></option>
                    <? }?>
                  </select>
                  <label for="reqStatusPegawaiId">Status Pegawai</label>
                  <?/*
                }
                else
                {
                  ?>
                  <label for="reqNamaStatus">Status Pegawai</label>
                  <input type="hidden" name="reqStatusPegawaiId" id="reqStatusPegawaiId"  value="<?=$reqStatusPegawaiId?>" />
                  <input type="text" id="reqNamaStatus" disabled value="<?=$reqNamaStatus?> " />
                  <?
                }
                */?>
                </div>
              </div>

              <div class="row">
               
                <div class="input-field col s12 m6">
                   <?/*
                    if($reqRowId == "")
                    {
                      */?>
                  <select <?=$disabled?> name="reqPegawaiKedudukanId" id="reqPegawaiKedudukanId">
                    <option value=""></option>
                    <? $pegawaikedudukan->selectByParams(array(),-1,-1, '');
                    while($pegawaikedudukan->nextRow()){?>
                    <option <?=$read?> value="<?=$pegawaikedudukan->getField('STATUS_PEGAWAI_KEDUDUKAN_ID')?>" <? if($pegawaikedudukan->getField('STATUS_PEGAWAI_KEDUDUKAN_ID')==$reqPegawaiKedudukanId) echo 'selected'?>><?=$pegawaikedudukan->getField('NAMA')?></option>
                    <? }?>
                  </select>
                  <label for="reqPegawaiKedudukanId">Status Kedudukan</label>
                    <? /*
                    }
                    else
                    { 
                      ?>
                      <label for="reqNamaStatus">Status Kedudukan</label>
                      <input type="hidden" name="reqPegawaiKedudukanId" id="reqPegawaiKedudukanId"  value="<?=$reqPegawaiKedudukanId?>" />
                      <input type="text" id="reqNamaKedudukan" disabled value="<?=$reqNamaKedudukan?> " />
                      <? 
                    } 
                    */?>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqTmt">TMT</label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTmt" id="reqTmt"  value="<?=$reqTmt?>" maxlength="10" onKeyDown="return format_date(event,'reqTmt');"/>
                </div>
              </div>


              <div class="row">
                <div class="input-field col s12">
                  <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>

                  <script type="text/javascript">
                    $("#kembali").click(function() { 
                      document.location.href = "app/loadUrl/app/pegawai_add_status_pegawai_monitoring?reqId=<?=$reqId?>";
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

<style type="text/css">
  .select-dropdown {
    max-height:150px !important; overflow:auto !important;
  }
</style>

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