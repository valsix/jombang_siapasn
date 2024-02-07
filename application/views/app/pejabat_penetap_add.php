<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('PejabatPenetap');

$set= new PejabatPenetap();

$reqId = $this->input->get("reqId");

if($reqId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$set->selectByParams(array("PEJABAT_PENETAP_ID"=>$reqId));
	$set->firstRow();
	$reqNama= $set->getField("NAMA");
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Untitled Document</title>
	<base href="<?=base_url()?>" />

	<link rel="stylesheet" type="text/css" href="css/gaya.css">
	<link rel="stylesheet" href="css/admin.css" type="text/css">

	<!-- <link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css"> -->
    <?php /*?><script type="text/javascript" src="lib/easyui/jquery-2.2.4.js"></script>
    <script type="text/javascript" src="lib/easyui/jquery-2.2.4.min.js"></script><?php */?>


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
    			url:'pejabat_penetap_json/add',
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
         					document.location.href= "app/loadUrl/app/pejabat_penetap_add/?reqId="+rowid;
         				}, 1000));
						$(".mbox > .right-align").css({"display": "none"});
         			}
         			top.frames['mainFrame'].location.reload();
         		}
         	});

    	});

    	function createRowData()
    	{		
			/*if (!document.getElementsByTagName) return;
			tabBody=document.getElementsByTagName("TBODY").item(1);
			
			var rownum= tabBody.rows.length;*/

			var s_url= "app/loadUrl/app/pejabat_penetap_add_row.php";
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
				<div class="ubah-color-warna white-text" style="padding: 1em">Tambah Pejabat Penetap</div>
				<div id="area-form-inner">
					<form id="ff" method="post"  novalidate enctype="multipart/form-data">

						<div class="row">
							<label for="reqNama" class="col s12 m2 label-control">Nama</label>
							<div class="input-field col s12 m10">
								<input name="reqNama" id="reqNama" class="" type="text" required value="<?=$reqNama?>" />
							</div>
						</div>

						<div class="row" style="margin-top: 2em">
							<div class="col s12 m3">
								<b>Nama</b> <a style="cursor:pointer" title="Tambah" onclick="createRowData()"><img src="images/icon-tambah.png" width="16" height="16" border="0" /></a>
							</div>
							<div class="col s12 m3">
								<b>NIP</b> 
							</div>
							<div class="col s12 m3">
								<b>Tanggal Awal</b>
							</div>
							<div class="col s12 m3">
								<b>Tanggal Akhir</b>
							</div>
						</div>
						<div class="divider"></div>
						<div class="row" id="tbDataData"></div>

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