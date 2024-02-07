<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('Eselon');
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
}

$arrPangkat="";
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

  <link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">

  <!-- BOOTSTRAP -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <!--<script src="js/jquery-1.10.2.min.js"></script>-->
  <script src="lib/bootstrap/js/jquery.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.js"></script>
  <link href="lib/bootstrap/css/bootstrap.css" rel="stylesheet">

  <!-- FONT AWESOME -->
  <link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

  <script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>
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
        mbox.alert(infodata, {open_speed: 500}, window.setInterval(function() 
        {
          mbox.close();
          document.location.href= "app/loadUrl/app/eselon_add/?reqId="+rowid;
        }, 1000));
      }
       // top.frames['mainFrame'].location.reload();
     }
   });

   });
 </script>


 <!-- UPLOAD CORE -->
 <!-- <script src="lib/multifile-master/jquery.MultiFile.js"></script> -->
 <script>
    // wait for document to load
    $(function(){
	// invoke plugin
	$('#reqLinkFile').MultiFile({
		onFileChange: function(){
			console.log(this, arguments);
		}
	});

});

    $(function(){
     $("#reqLinkFile").prop('required',true);
   });

    function addRow()
    {
     if (!document.getElementsByTagName) return;
     tabBody=document.getElementsByTagName("TBODY").item(1);
	//tabBody=document.getElementById("tbDataData").item(1);
	
	
	var rownum= tabBody.rows.length;
	//alert(rownum);
	var s_url= "request_blanko_add_row.php?reqIndex="+rownum;
	$.ajax({'url': s_url,'success': function(data){
		$("#tbDataData").append(data);
	}});
}
</script>

<!-- BOOTSTRAP CORE -->
<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<link href="lib/mbox/mbox.css" rel="stylesheet">
<script src="lib/mbox/mbox.js"></script>
<link href="lib/mbox/mbox-modif.css" rel="stylesheet">
</head>

<body>
  <div class="container-fluid full-height">
    <div class="row full-height">
      <div class="col-md-12 area-form full-height">

        <div id="judul-popup">Tambah Eselon</div>
        <div id="area-form-inner">
          <form id="ff" method="post"  novalidate enctype="multipart/form-data">

            <div class="form-group">
              <label>Nama</label>
              <input name="reqNama" class="form-control easyui-validatebox" type="text" required value="<?=$reqNama?>" />
            </div>

            <div class="form-group">
              <label>Pangkat Minimal</label>
              <select name="reqPangkatMinimal" id="reqPangkatMinimal" class="form-control easyui-validatebox" style="padding:0">
                <?
                for($index= 0; $index < $jumlah_pangkat; $index++)
                {
                  ?>
                  <option value="<?=$arrPangkat[$index]["PANGKAT_ID"]?>" <? if($arrPangkat[$index]["PANGKAT_ID"] == $reqPangkatMinimal) echo "selected"?>><?=$arrPangkat[$index]["KODE"]?></option>
                  <?}
                  ?>
                </select>
              </div>

              <div class="form-group">
                <label>Pangkat Maksimal</label>
                <select name="reqPangkatMaksimal" id="reqPangkatMaksimal" class="form-control easyui-validatebox" style="padding:0">
                  <?
                  for($index= 0; $index < $jumlah_pangkat; $index++)
                  {
                    ?>
                    <option value="<?=$arrPangkat[$index]["PANGKAT_ID"]?>" <? if($arrPangkat[$index]["PANGKAT_ID"] == $reqPangkatMaksimal) echo "selected"?>><?=$arrPangkat[$index]["KODE"]?></option>
                    <?}
                    ?>
                  </select>
                </div>

                <div class="form-group" style="padding-top:1%">
                  <div class="row">
                    <div class="col-sm-12">
                      <a style="cursor:pointer" title="Tambah" onClick="addRow()"><img src="images/icon-add.png" width="16" height="16" border="0" /></a>
                    </div>
                    <div class="col-sm-6">
                      <label>Tunjangan</label>
                      <input type="hidden" name="reqsRowId[]" id="reqRowId<?=$checkbox_index?>" value="<?=$tempRowId?>" />
                      <input type="hidden" name="reqsBlankoId[]" id="reqBlankoId<?=$checkbox_index?>" value="<?=$tempBlankoId?>" /> 
                      <input class="easyui-validatebox form-control" type="text" value="10.0000" />
                    </div>
                    <div class="col-sm-5">
                      <label>Tmt</label>
                      <input class="easyui-validatebox form-control" type="text" value="10-10-2017" style="padding:0" />
                    </div>
                    <div class="col-sm-1">
                      <label>&nbsp</label>
                      <a style="cursor:pointer; padding:0; border:none" class="form-control" title="Hapus" onClick="deleteRowDrawTablePhp('tbDataData', this, 'reqRowId<?=$checkbox_index?>', 'blanko_koordinator_pilih', 1)"><img src="images/icon-delete.png"></a>
                    </div>

                  </div>
                </div>

                <div class="form-group" >
                  <div class="col-md-2">
                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                    <input type="submit" name="reqSubmit"  class="btn btn-primary" value="Submit" />
                  </div>
                </div>

              </form>
            </div>
          </div>
        </div>
      </div>
    </body>
    </html>