<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model("base-cuti/CutiUrutan");

$reqBreadCrum= $this->input->get("reqBreadCrum");
$reqJudulBreadCrum= array_pop( explode(';', $reqBreadCrum) );

$arrurutan= [];
$set= new CutiUrutan();
$set->selectByParams(array());
// echo $set->query;exit;
while($set->nextRow())
{
  $vuserloginid= $set->getField("USER_LOGIN_ID");
  $arrdata= [];
  $arrdata["MENU_ID"]= $set->getField("MENU_ID");
  $arrdata["NAMA"]= $set->getField("NAMA");
  $arrdata["USER_LOGIN_ID"]= $vuserloginid;
  $pegawaiinfo= "";
  if(!empty($vuserloginid))
    $pegawaiinfo= $set->getField("NAMA_LENGKAP")." (".$set->getField("NIP_BARU").")";
  $arrdata["PEGAWAI_INFO"]= $pegawaiinfo;
  $arrdata["LOGIN_USER"]= $set->getField("LOGIN_USER");
  $arrdata["URUTAN"]= $set->getField("URUTAN");
  array_push($arrurutan, $arrdata);
}
// print_r($arrurutan);exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Untitled Document</title>
  <base href="<?=base_url()?>" />

  <link rel="stylesheet" type="text/css" href="css/gaya.css">
  <link rel="stylesheet" href="css/admin.css" type="text/css">

  <link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">

  <!-- MATERIAL CORE CSS-->    
  <link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

  <link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">

  <!-- FONT AWESOME -->
  <link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

  <script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>
  <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script>
  <script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
  <script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
  <script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

  <!-- AUTO KOMPLIT -->
  <link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
  <script src="lib/autokomplit/jquery-ui.js"></script>

  <script type="text/javascript"> 
    $(function(){
      $('#ff').form({
        url:'cuti_baru_json/settingadd',
        onSubmit:function(){
          return $(this).form('validate');
        },
        success:function(data){
          // console.log(data);return false;
          data = data.split("yyy");
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
              document.location.reload();
            }, 1000));
            $(".mbox > .right-align").css({"display": "none"});
          }
        }
      });
      
    });
  </script>

  <link href="lib/mbox/mbox.css" rel="stylesheet">
  <script src="lib/mbox/mbox.js"></script>
  <link href="lib/mbox/mbox-modif.css" rel="stylesheet">

    <style type="text/css">
      button[data-toggle=modal] {
        height: 25px;
        line-height: 23px;

        -webkit-border-radius: 13px;
      -moz-border-radius: 13px;
      border-radius: 13px;

        font-size: 10px;
        margin-top: 3px;
        margin-bottom: 3px;

        background: none;
        color: #333;
        border: 1px solid rgba(0,0,0,0.1);
        box-shadow: none;
        width: auto;
        display: inline-block;
        padding-left: 7px;
      padding-right: 7px;
      letter-spacing: normal;
      }
      .modal {
        z-index: 1;
        top: 10vh;
        width: 90%;
        height: calc(100vh - 0px);
      }
      .modal .modal-dialog {
        height: 100%;
      /*border: 1px solid red;*/
      }
      .modal .modal-dialog .modal-content {
        /*border: 1px solid cyan;*/
        height: 100%;
      }
      .modal .modal-dialog .modal-content .modal-body {
        /*border: 1px solid green;*/
        height: calc(100% - 110px);
      }
      .modal .modal-dialog .modal-content .modal-body iframe {
        height: 100%;
      }
      .modal .modal-header {
        display: inline-block;
      width: 100%;
      padding: 10px 15px;
      border-bottom: 1px solid #dadada;
      }
      .modal .modal-title {
        float: left;
        margin-bottom: 0px;
      font-size: 18px;
      }
      .modal button.close {
        float: right;
        margin-top: 0px;
      /*margin-right: 10px;*/
      }
    </style>

    
</head>

<body>
  <textarea id="setquery" style="display: none;"></textarea>
  <div class="container-fluid full-height">
    <div class="row full-height">
      <div class="col-md-12 area-form full-height">

        <div class="ubah-color-warna white-text" style="padding: 1em"><?=$reqJudulBreadCrum?></div>
        <div id="area-form-inner">
          <form id="ff" class="form-horizontal" role="form" method="post"  novalidate enctype="multipart/form-data">

            <div class="row" style="margin-top: 2em; background-color: grey; color: white">
              <div class="col s12 m4">
                <b>Nama</b>
              </div>
              <div class="col s12 m7">
                <b>User Login</b> 
              </div>
              <div class="col s12 m1">
                <b>Urutan</b>
              </div>
            </div>
            <div class="divider"></div>
            <?
            $arrexcept= array("130104", "130105");
            foreach ($arrurutan as $key => $value) 
            {
              $reqMenuId= $value["MENU_ID"];
              $reqUserLoginId= $value["USER_LOGIN_ID"];
              $reqUserLoginNama= $value["LOGIN_USER"];
              $reqPegawaiInfo= $value["PEGAWAI_INFO"];
              $reqUrutan= $value["URUTAN"];
            ?>
            <div class="row">
              <div class="col s12 m4">
                <?=$value["NAMA"]?>
              </div>
              <div class="col s12 m7">
                <?
                if(in_array($reqMenuId, $arrexcept))
                {
                ?>
                &nbsp;
                <input type="hidden" id="reqUserLoginId<?=$key?>" name="reqUserLoginId[]" value="<?=$reqUserLoginId?>" />
                <?
                }
                else
                {
                ?>
                <input type="text" id="reqUserLoginNama<?=$key?>" value="<?=$reqUserLoginNama?>" />
                <input type="hidden" id="reqUserLoginId<?=$key?>" name="reqUserLoginId[]" value="<?=$reqUserLoginId?>" />
                <label id="reqPegawaiInfo<?=$key?>"><?=$reqPegawaiInfo?></label>
                <?
                }
                ?>
              </div>
              <div class="col s12 m1">
                <input type="hidden" id="reqMenuId<?=$key?>" name="reqMenuId[]" value="<?=$reqMenuId?>" />
                <input class="classangka" type="text" id="reqUrutan<?=$key?>" name="reqUrutan[]" value="<?=$reqUrutan?>" />
              </div>
            </div>
            <div class="divider"></div>
            <?
            }
            ?>
            <div class="row">
              <div class="input-field col s12 m10 offset-m2">
                <input type="submit" class="btn green" value="Submit">
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>

<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>
<script type="text/javascript">

$('.classangka').bind('keyup paste', function(){
  this.value = this.value.replace(/[^0-9]/g, '');
  // this.value = this.value.replace(/[^0-9\.]/g, '');
});

$(function(){
  $('input[id^="reqUserLoginNama"]').each(function(){
    $(this).autocomplete({
        source:function(request, response){
          var id= this.element.attr('id');
          var replaceAnakId= replaceAnak= urlAjax= "";

          if (id.indexOf('reqUserLoginNama') !== -1)
          {
            var element= id.split('reqUserLoginNama');
            var indexId= "reqUserLoginId"+element[1];
            urlAjax= "cuti_baru_json/cariuserlogin";
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
                  return {desc: element['desc'], id: element['id'], label: element['label'], pegawaiinfo: element['pegawaiinfo']};
                });
                response(array);
              }
            }
          })
        },
        focus: function (event, ui) 
        { 
          var id= $(this).attr('id');
          if (id.indexOf('reqUserLoginNama') !== -1)
          {
            var element= id.split('reqUserLoginNama');
            indexrow= element[1];
            var indexId= "reqUserLoginId"+indexrow;
            $("#reqPegawaiInfo"+indexrow).text(ui.item.pegawaiinfo).trigger('change');
          }

          $("#"+indexId).val(ui.item.id).trigger('change');

        },
        autoFocus: true
      })
      .autocomplete( "instance" )._renderItem = function( ul, item ) {
      //return
      return $( "<li>" )
      .append( "<a>" + item.desc  + "</a>" )
      .appendTo( ul );
    }
    ;
  });

});
</script>
<script src="lib/AdminLTE-2.4.0-rc/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

</body>
</html>