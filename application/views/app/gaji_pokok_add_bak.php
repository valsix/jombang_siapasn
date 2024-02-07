<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('GajiPokok');
$this->load->model('GajiPokokDetil');
$this->load->model('Pangkat');

$set= new GajiPokok();
$pangkat= new Pangkat();

$reqId = $this->input->get("reqId");

if($reqId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$set->selectByParams(array("GAJI_POKOK_ID"=>$reqId));
	$set->firstRow();
	$reqMasaKerja= $set->getField("MASA_KERJA");
	$reqPangkaId= $set->getField("PANGKAT_ID");
	
	$set_data = new GajiPokokDetil();
	$set_data->selectByParams(array("GAJI_POKOK_ID"=>$reqId));
	
	$arrData="";
	$index_data= 0;
	$set_data= new GajiPokokDetil();
	$set_data->selectByParams(array("GAJI_POKOK_ID"=>$reqId));
	while($set_data->nextRow())
	{
		$arrData[$index_data]["GAJI_POKOK_DETIL_ID"] = $set_data->getField("GAJI_POKOK_DETIL_ID");
		$arrData[$index_data]["GAJI"] = $set_data->getField("GAJI");
		$arrData[$index_data]["TANGGAL_AWAL"] =  dateTimeToPageCheck($set_data->getField("TANGGAL_AWAL"));
		$arrData[$index_data]["TANGGAL_AKHIR"] =  dateTimeToPageCheck($set_data->getField("TANGGAL_AKHIR"));
		$index_data++;
	}
}

$pangkat->selectByParams(array(),-1,-1, " AND STATUS IS NULL");
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


    <!-- BOOTSTRAP -->
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> -->

    <!--<script src="js/jquery-1.10.2.min.js"></script>-->
    <script src="lib/bootstrap/js/jquery.min.js"></script>
    <!-- // <script src="lib/bootstrap/js/bootstrap.js"></script> -->
    <!-- <link href="lib/bootstrap/css/bootstrap.css" rel="stylesheet"> -->

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

    
    <script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script>
    <script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
    <script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

    <script type="text/javascript">	
    	$(function(){
    		$('#ff').form({
    			url:'gaji_pokok_json/add',
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
         				document.location.href= "app/loadUrl/app/gaji_pokok_add/?reqId="+rowid;
         			}, 1000));
         		}

					// document.location.href="app/loadUrl/app/gaji_pokok_add/?reqId="+data[0];
					// top.frames['mainFrame'].location.reload();
				}
			});

    	});

    	function createRowData()
    	{		
			/*if (!document.getElementsByTagName) return;
			tabBody=document.getElementsByTagName("TBODY").item(1);
			
			var rownum= tabBody.rows.length;*/
			//alert(rownum);

			var s_url= "app/loadUrl/app/gaji_pokok_add_row.php";
			$.ajax({'url': s_url,'success': function(data){
				$("#tbDataData").append(data);
			}});
		}
	</script>

	<?php /*?><!-- BOOTSTRAP CORE -->
	<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script><?php */?>

	<!-- Materialize -->
	<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
	<link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
	<!-- Custome CSS-->    
	<link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

	<link href="lib/mbox/mbox.css" rel="stylesheet">
	<script src="lib/mbox/mbox.js"></script>
	<link href="lib/mbox/mbox-modif.css" rel="stylesheet">
</head>

<body>
	<div class="container-fluid full-height">
		<div class="row full-height">
			<div class="col-md-12 area-form full-height">

				<div id="judul-popup">Tambah Gaji Pokok</div>
				<div id="area-form-inner">
					<form id="ff" method="post"  novalidate enctype="multipart/form-data">

						
						<div class="row">
							<div class="input-field col s12">
								<label for="reqMasaKerja">Masa Kerja</label>
								<input name="reqMasaKerja" id="reqMasaKerja"  type="text" required value="<?=$reqMasaKerja?>" />
							</div>
						</div>


						<div class="row">
							<div class="input-field col s12">
								<select id="reqPangkatId" name="reqPangkatId">
									<?
									while($pangkat->nextRow())
									{
										?>
										<option value="<?=$pangkat->getField("PANGKAT_ID")?>" <? if($pangkat->getField("PANGKAT_ID")==$reqPangkaId) echo 'selected'?>><?=$pangkat->getField("NAMA")?></option>
										<?
									}
									?>
								</select>
								<label>Pangkat</label>
							</div>
						</div>

						<!-- <div class="form-group"> -->


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
								for($checkbox_index=0; $checkbox_index < $index_data; $checkbox_index++)
								{
									$reqRowDetilId = $arrData[$checkbox_index]["GAJI_POKOK_DETIL_ID"];
									$reqGaji = $arrData[$checkbox_index]["GAJI"];
									$reqTglAwal = $arrData[$checkbox_index]["TANGGAL_AWAL"];
									$reqTglAkhir = $arrData[$checkbox_index]["TANGGAL_AKHIR"];
									?>
									<div class="col s12 m4">
                                    	<input id="reqGaji<?=$checkbox_index?>" name="reqGaji[]" type="text" OnFocus="FormatAngka('reqGaji<?=$checkbox_index?>')" OnKeyUp="FormatUang('reqGaji<?=$checkbox_index?>')" OnBlur="FormatUang('reqGaji<?=$checkbox_index?>')" value="<?=numberToIna($reqGaji)?>" /> 
										<input type="hidden" name="reqRowDetilId[]" id="reqRowDetilId<?=$checkbox_index?>" value="<?=$reqRowDetilId?>" />
									</div>
									<div class="col s12 m4">
										<input name="reqTglAwal[]" id="reqTglAwal<?=$checkbox_index?>" type="text"  value="<?=$reqTglAwal?>" />
									</div>
									<div class="col s12 m4">
										<input name="reqTglAkhir[]" id="reqTglAkhir<?=$checkbox_index?>" type="text"  value="<?=$reqTglAkhir?>" />
									</div>
									<?
								}
								?>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<input type="hidden" name="reqId" value="<?=$reqId?>" />
									<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
									<button class="btn cyan waves-effect waves-light green" type="submit" name="action">
										<i class="mdi-content-save"></i>
										Simpan
									</button>
								</div>
							</div>

						</form>
					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			$(document).ready(function() {
				$('select').material_select();
			});


		</script>
		<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>
	</body>
	</html>