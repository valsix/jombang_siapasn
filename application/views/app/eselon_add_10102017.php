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

<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
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
			$.messager.alert('Info', data, 'info');	
			
			<?
			if($reqMode == "update")
			{
			?>
				document.location.reload();
			<?	
			}
			else
			{
			?>
				$('#rst_form').click();
			<?
			}
			?>
			top.frames['mainFrame'].location.reload();
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

</head>

<body class="bg-kanan-full">
	<div id="judul-popup">Tambah Eselon</div>
	<div id="konten">
    	<div id="popup-tabel2">
            <form id="ff" method="post"  novalidate enctype="multipart/form-data">
                    <table class="table">
                        <tr>           
                             <td>Nama</td>
                             <td>:</td>
                             <td>
                                <input name="reqNama" class="easyui-validatebox" style="width:170px" type="text" value="<?=$reqNama?>" />
                            </td>			
                        </tr>  
                        <tr>           
                             <td>Pangkat Minimal</td>
                             <td>:</td>
                             <td>
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
                            </td>			
                        </tr>  
                        <tr>           
                             <td>Pangkat Maksimal</td>
                             <td>:</td>
                             <td>
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
                            </td>			
                        </tr>
                        <tr>
                        	<td colspan="3">
                            	<a style="cursor:pointer" title="Tambah" onClick="addRow()"><img src="images/icon-add.png" width="16" height="16" border="0" /></a>
                            	<table style="width:100%" id="tbDataData">
                                <tbody>
                                	<tr>
                                    	<td>Tunjangan</td>
                                        <td>Tmt</td>
                                    </tr>
                                </tbody>
                				<tbody>
                                	<tr>
                                        <td>
                                            <input type="hidden" name="reqsRowId[]" id="reqRowId<?=$checkbox_index?>" value="<?=$tempRowId?>" />
                                            <input type="hidden" name="reqsBlankoId[]" id="reqBlankoId<?=$checkbox_index?>" value="<?=$tempBlankoId?>" /> 
                                            <input class="easyui-validatebox" style="width:170px" type="text" value="10.0000" />
                                        </td>
                                        <td>
                                        	<input class="easyui-validatebox" style="width:170px" type="text" value="10-10-2017" />
                                            <a style="cursor:pointer" title="Hapus" onClick="deleteRowDrawTablePhp('tbDataData', this, 'reqRowId<?=$checkbox_index?>', 'blanko_koordinator_pilih', 1)"><img src="images/icon-delete.png"></a>
                                        </td>
                                    </tr>
                                </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                    <input type="submit" name="reqSubmit"  class="btn btn-primary" value="Submit" />
                    <input type="reset" id="rst_form"  class="btn btn-primary" value="Reset" />
                    
            </form>
        </div>
    </div>
    </div>
</body>
</html>