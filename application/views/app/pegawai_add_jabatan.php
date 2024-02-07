<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqJenisJabatan= $this->input->get("reqJenisJabatan");
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "010401";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

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
    function seinfodatacentang()
	{
		$("#reqinfoeselontext,#reqinfoeselonselect").hide();
		if($("#reqIsManual").prop('checked')) 
		{
			$("#reqinfoeselonselect").show();
			$("#reqSatker").attr("readonly", false);
			$("#reqSelectEselonId").material_select();
			//$("#reqNama,#reqNamaId").val("");
		}
		else
		{
			$("#reqSatker").attr("readonly", true);
			$("#reqinfoeselontext").show();
		}
	}
	
    function setcetang()
	{
		//alert($("#reqIsManual").prop('checked'));return false;
		$("#reqinfoeselontext,#reqinfoeselonselect").hide();
		if($("#reqIsManual").prop('checked')) 
		{
			$("#reqinfoeselonselect").show();
			$("#reqSelectEselonId,#reqEselonId, #reqEselonText, #reqNama, #reqSatker, #reqSatkerId").val("");
			$("#reqSatker").attr("readonly", false);
			$("#reqSelectEselonId").material_select();
			//$("#reqNama,#reqNamaId").val("");
		}
		else
		{
			$("#reqSatker").attr("readonly", true);
			$("#reqEselonId, #reqNama, #reqSatker, #reqSatkerId").val("");
			$("#reqinfoeselontext").show();
		}
	}
	
    $(function(){
	 <?
	 if($reqRowId == "")
	 {
	 ?>
	 setcetang();
	 <?
	 }
	 else
	 {
	 ?>
	 seinfodatacentang();
	 <?
	 }
	 ?>
     $('#ff').form({
      url:'jabatan_riwayat_json/add',
      onSubmit:function(){
		var reqEselonId= "";
		reqEselonId= $("#reqEselonId").val();
		if(reqEselonId == "")
		{
			$.messager.alert('Info', "Lengkapi data Eselon terlebih dahulu", 'info');
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
          // alert(data);return false;
          data = data.split("-");
          rowid= data[0];
          infodata= data[1];
          //$.messager.alert('Info', infodata, 'info');
		  mbox.alert(infodata, {open_speed: 500}, interval = window.setInterval(function() 
		  {
			  clearInterval(interval);
			  mbox.close();
			  document.location.href= "app/loadUrl/app/pegawai_add_jabatan_struktural_data/?reqId=<?=$reqId?>&reqRowId="+rowid;
		  }, 1000));
		  $(".mbox > .right-align").css({"display": "none"});
          
        }
	  });

     $("#reqIsManual").click(function () {
		setcetang();
	 });
	 
	 $('#reqSelectEselonId').bind('change', function(ev) {
       $("#reqEselonId").val($(this).val());
     });
	 
	 //$('input[id^="reqPejabatPenetap"], input[id^="reqNama"], input[id^="reqSatker"]').autocomplete({
	 $('input[id^="reqPejabatPenetap"], input[id^="reqNama"]').autocomplete({
      source:function(request, response){
        var id= this.element.attr('id');
        var replaceAnakId= replaceAnak= urlAjax= "";

        if (id.indexOf('reqNama') !== -1 || id.indexOf('reqSatker') !== -1)
        {
			if($("#reqIsManual").prop('checked')) 
			{
				return false;
			}
		}
		
		if (id.indexOf('reqPejabatPenetap') !== -1)
        {
          var element= id.split('reqPejabatPenetap');
          var indexId= "reqPejabatPenetapId"+element[1];
          urlAjax= "pejabat_penetap_json/combo";
        }
		else if (id.indexOf('reqNama') !== -1)
        {
          var element= id.split('reqNama');
          var indexId= "reqNamaId"+element[1];
          urlAjax= "satuan_kerja_json/namajabatan";
        }
		else if (id.indexOf('reqSatker') !== -1)
        {
          var element= id.split('reqSatker');
          var indexId= "reqSatkerId"+element[1];
          urlAjax= "satuan_kerja_json/auto";
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
                return {desc: element['desc'], id: element['id'], label: element['label'], satuan_kerja: element['satuan_kerja'], eselon_id: element['eselon_id'], eselon_nama: element['eselon_nama']};
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
		else if (id.indexOf('reqNama') !== -1)
        {
          var element= id.split('reqNama');
          var indexId= "reqSatkerId"+element[1];
		  $("#reqSatker").val(ui.item.satuan_kerja).trigger('change');
		  $("#reqEselonId").val(ui.item.eselon_id).trigger('change');
		  $("#reqEselonText").val(ui.item.eselon_nama).trigger('change');
        }
		else if (id.indexOf('reqSatker') !== -1)
        {
          var element= id.split('reqSatker');
		  var indexId= "reqSatkerId"+element[1];
		  $("#reqNama").val("").trigger('change');
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

      $("#reqJenisJabatan").change(function() { 
        var jenis_jabatan = $("#reqJenisJabatan").val();
		
		if(jenis_jabatan=="")
        {
          document.location.href = "app/loadUrl/app/pegawai_add_jabatan?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqJenisJabatan=<?=$reqJenisJabatan?>";
        }
        else if(jenis_jabatan==1)
        {
          document.location.href = "app/loadUrl/app/pegawai_add_jabatan_struktural_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqJenisJabatan=<?=$reqJenisJabatan?>";
        }
        else if(jenis_jabatan==2)
        {
          document.location.href = "app/loadUrl/app/pegawai_add_jabatan_fungsional_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqJenisJabatan=<?=$reqJenisJabatan?>";
        }
        else if(jenis_jabatan==3)
        {
          document.location.href = "app/loadUrl/app/pegawai_add_jabatan_tertentu_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqJenisJabatan=<?=$reqJenisJabatan?>";
        }
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
         <li class="collection-item ubah-color-warna">EDIT JABATAN STRUKTURAL</li>
         <li class="collection-item">

            <form id="ff" method="post" enctype="multipart/form-data">
              <div class="row">
                <div class="input-field col s12 m6">
                  <select id="reqJenisJabatan" name="reqJenisJabatan" >
                    <option value="" <? if($reqJenisJabatan=="") echo 'selected';?>></option>
                    <option value="1" <? if($reqJenisJabatan==1) echo 'selected';?>>Jabatan Struktural</option>
                    <option value="2" <? if($reqJenisJabatan==2) echo 'selected';?>>Jabatan Fungsional Umum</option>
                    <option value="3" <? if($reqJenisJabatan==3) echo 'selected';?>>Jabatan Fungsional Tertentu</option>
                  </select>
                  <label for="reqPejabatPenetap">Jenis Jabatan</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12">
                  <br>
                  <br>
                  <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>

                  <script type="text/javascript">
                    $("#kembali").click(function() { 
                      document.location.href = "app/loadUrl/app/pegawai_add_jabatan_monitoring?reqId=<?=$reqId?>";
                    });
                  </script>

                  <input type="hidden"  name="reqTipePegawaiId" value="<?=$reqTipePegawaiId?>" />
                  <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                  <input type="hidden" name="reqId" value="<?=$reqId?>" />
                  <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
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