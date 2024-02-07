<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('Eselon');
$this->load->model('EselonDetil');
$this->load->model('Pangkat');

$set= new Eselon();
$reqId = $this->input->get("reqId");

if($reqId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$set->selectByParams(array("ESELON_ID"=>$reqId));
	$set->firstRow();
	$reqNama= $set->getField("NAMA");
	$reqTunjangan= $set->getField("TUNJANGAN");
	$reqPangkatMinimal= $set->getField("PANGKAT_MINIMAL");
	$reqPangkatMaksimal= $set->getField("PANGKAT_MAKSIMAL");
	// $reqStatus= $set->getField("STATUS_NAMA");
	
	$arrDetil= [];
	$index_detil= 0;
	$set_data= new EselonDetil();
	$set_data->selectByParams(array("ESELON_ID"=>$reqId));
	while($set_data->nextRow())
	{
		$arrDetil[$index_detil]["ESELON_DETIL_ID"] = $set_data->getField("ESELON_DETIL_ID");
		$arrDetil[$index_detil]["TUNJANGAN"] = $set_data->getField("TUNJANGAN");
		$arrDetil[$index_detil]["TANGGAL_AWAL"] =  dateTimeToPageCheck($set_data->getField("TANGGAL_AWAL"));
		$arrDetil[$index_detil]["TANGGAL_AKHIR"] =  dateTimeToPageCheck($set_data->getField("TANGGAL_AKHIR"));
		$index_detil++;
	}
	unset($set);
	$jumlah_detil= $index_detil;
}

$arrPangkat= [];
$index_data= 0;
$set= new Pangkat();
$set->selectByParams(array());
while($set->nextRow())
{
	$arrPangkat[$index_data]["PANGKAT_ID"] = $set->getField("PANGKAT_ID");
	$arrPangkat[$index_data]["KODE"] = $set->getField("KODE");
	$index_data++;
}
unset($set);
$jumlah_pangkat= $index_data;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Untitled Document</title>
	<base href="<?=base_url()?>" />

	<link rel="stylesheet" type="text/css" href="css/gaya.css">
	<link rel="stylesheet" href="css/admin.css" type="text/css">

	<!-- MATERIAL CORE CSS-->    
	<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
	<link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
	<link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

	<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">

	<!-- FONT AWESOME -->
	<link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">
	
	<script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script>
	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
	<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

    <script type="text/javascript">	
    $(function(){
		$('#ff').form({
			url:'eselon_json/add',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
			//alert(data);return false;
			data = data.split("-");
			rowid= data[0];
			infodata= data[1];
			//$.messager.alert('Info', infodata, 'info');

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
					document.location.href= "app/loadUrl/app/eselon_add/?reqId="+rowid;
				}, 1000));
				$(".mbox > .right-align").css({"display": "none"});
			}

				// document.location.href="app/loadUrl/app/gaji_pokok_add/?reqId="+data[0];
				top.frames['mainFrame'].location.reload();
			}
		});

	});
  </script>


  <script>	
		function createRowData()
		{
			 /*if (!document.getElementsByTagName) return;
			 tabBody=document.getElementsByTagName("TBODY").item(1);
			//tabBody=document.getElementById("tbDataData").item(1);
			
			var rownum= tabBody.rows.length;*/
			//alert(rownum);
			var s_url= "app/loadUrl/app/eselon_add_row.php";
			$.ajax({'url': s_url,'success': function(data){
				$("#tbDataData").append(data);
			}});
		}
	</script>

<link href="lib/mbox/mbox.css" rel="stylesheet">
<script src="lib/mbox/mbox.js"></script>
<link href="lib/mbox/mbox-modif.css" rel="stylesheet">
</head>

<body>
  <div class="container-fluid full-height">
    <div class="row full-height">
      <div class="col-md-12 area-form full-height">

        <div class="ubah-color-warna white-text" style="padding: 1em">Tambah Eselon</div>
        <div id="area-form-inner">
          <form id="ff" method="post"  novalidate enctype="multipart/form-data">

            <div class="row">
              <label for="reqNama" class="col s12 m2 label-control">Nama</label>
              <div class="input-field col s12 m10">
                <input name="reqNama" id="reqNama" class="" type="text" required value="<?=$reqNama?>" />
              </div>
            </div>


            <div class="row">
              <label for="reqPendidikan" class="col s12 m2 label-control">Pangkat Minimal</label>
              <div class="input-field col s12 m10">
                <select name="reqPangkatMinimal" id="reqPangkatMinimal">
                  <?
                  for($index= 0; $index < $jumlah_pangkat; $index++)
                  {
                    ?>
                    <option value="<?=$arrPangkat[$index]["PANGKAT_ID"]?>" <? if($arrPangkat[$index]["PANGKAT_ID"] == $reqPangkatMinimal) echo "selected"?>><?=$arrPangkat[$index]["KODE"]?></option>
                    <?
                  }
                  ?>
                </select>

              </div>
            </div>

            <div class="row">
              <label for="reqPendidikan" class="col s12 m2 label-control">Pangkat Maksimal</label>
              <div class="input-field col s12 m10">
                <select name="reqPangkatMaksimal" id="reqPangkatMaksimal">
                  <?
                  for($index= 0; $index < $jumlah_pangkat; $index++)
                  {
                    ?>
                    <option value="<?=$arrPangkat[$index]["PANGKAT_ID"]?>" <? if($arrPangkat[$index]["PANGKAT_ID"] == $reqPangkatMaksimal) echo "selected"?>><?=$arrPangkat[$index]["KODE"]?></option>
                    <?
                  }
                  ?>
                </select>
              </div>
            </div>

            <div class="row">
                <div class="col s12 m4">
                    <b>Gaji</b> <a style="cursor:pointer" title="Tambah" onclick="createRowData()"><img src="images/icon-tambah.png" width="16" height="16" border="0" /></a>
                </div>
                <div class="col s12 m4">
                    <b>Tanggal Awal</b> 
                </div>
                <div class="col s12 m4">
                    <b>Tanggal Akhir</b>
                </div>
            </div>
			<div class="divider"></div>
            <div class="row" id="tbDataData">
                <?
                for($checkbox_index=0; $checkbox_index < $index_detil; $checkbox_index++)
                {
                    $reqRowDetilId = $arrDetil[$checkbox_index]["ESELON_DETIL_ID"];
                    $reqTunjangan = $arrDetil[$checkbox_index]["TUNJANGAN"];
                    $reqTglAwal = $arrDetil[$checkbox_index]["TANGGAL_AWAL"];
                    $reqTglAkhir = $arrDetil[$checkbox_index]["TANGGAL_AKHIR"];
                    ?>
                    <div class="col s12 m4">
                        <input id="reqTunjangan<?=$checkbox_index?>" name="reqTunjangan[]" type="text" OnFocus="FormatAngka('reqTunjangan<?=$checkbox_index?>')" OnKeyUp="FormatUang('reqTunjangan<?=$checkbox_index?>')" OnBlur="FormatUang('reqTunjangan<?=$checkbox_index?>')" value="<?=numberToIna($reqTunjangan)?>" /> 
                        <input type="hidden" name="reqRowDetilId[]" id="reqRowDetilId<?=$checkbox_index?>" value="<?=$reqRowDetilId?>" />
                    </div>
                    <div class="col s12 m4">
                        <input name="reqTglAwal[]" id="reqTglAwal<?=$checkbox_index?>" type="text" class="easyui-validatebox" data-options="validType:['dateValidPickerMulti[\'reqTglAwal\', \'<?=$checkbox_index?>\']']" maxlength="10" onKeyDown="return format_date(event,'reqTglAwal<?=$checkbox_index?>');"  value="<?=$reqTglAwal?>" />
                    </div>
                    <div class="col s12 m4">
                        <input name="reqTglAkhir[]" id="reqTglAkhir<?=$checkbox_index?>" type="text" class="easyui-validatebox" data-options="validType:['dateValidPickerMulti[\'reqTglAkhir\', \'<?=$checkbox_index?>\']']" maxlength="10" onKeyDown="return format_date(event,'reqTglAkhir<?=$checkbox_index?>');"  value="<?=$reqTglAkhir?>" />
                    </div>
                    <?
                }
                ?>
            </div>
            <div class="row">
              <div class="input-field col s12 m3 offset-m2">
                <input type="hidden" name="reqId" value="<?=$reqId?>" />
                <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                <input type="submit" name="reqSubmit"  class="btn green" value="Submit" />
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script> -->
  <script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>
  <script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>
  <script type="text/javascript">
    $(document).ready( function () {
      $('select').material_select();
    });
  </script>
</body>
</html>