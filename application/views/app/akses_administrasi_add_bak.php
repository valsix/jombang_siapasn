<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN */

$this->load->model('Menu');

$reqId= $this->input->get("reqId");
$reqMenuGroupId= $this->input->get("reqMenuGroupId");
$reqTable= $this->input->get("reqTable");
$reqRowId= $this->input->get("reqRowId");

$i=0;
if($reqRowId == "")
	$reqMode = "insert";
else
{
	$reqMode = "update";
	$menu= new Menu();
	$menu->selectByParams(array("MENU_GROUP_ID" => $reqMenuGroupId), -1, -1, "", $reqRowId, $reqTable);
	//echo $menu->query;exit;
	while($menu->nextRow())
	{
		$arrMenu[$i]["MENU_PARENT_ID"] = $menu->getField("MENU_PARENT_ID");
		$arrMenu[$i]["MENU_ID"] = $menu->getField("MENU_ID");
		$arrMenu[$i]["NAMA"] = $menu->getField("NAMA");
		$arrMenu[$i]["AKSES"] = $menu->getField("AKSES");
		$arrMenu[$i]["MENU"] = $menu->getField("MENU");
		$arrMenu[$i]["PANJANG_MENU"] = $menu->getField("PANJANG_MENU");
		$arrMenu[$i]["JUMLAH_CHILD"] = $menu->getField("JUMLAH_CHILD");
		$i++;
	}
}

$jumlah_menu= $i;
//print_r($arrMenu);exit;
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
				url:'user_group_json/add_menu',
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
         					document.location.href= "app/loadUrl/app/akses_administrasi_add/?reqId=<?=$reqId?>&reqMenuGroupId=<?=$reqMenuGroupId?>&reqTable=<?=$reqTable?>&reqRowId="+rowid;
							top.frames['mainFrame'].location.reload();
         				}, 1000));
         			}
					// document.location.href= "app/loadUrl/app/akses_administrasi_add?reqId=<?=$reqId?>&reqMenuGroupId=<?=$reqMenuGroupId?>&reqTable=<?=$reqTable?>&reqRowId="+rowid;
				}
			});
			
		});
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

				<div id="judul-popup">Menu Akses</div>
				<div id="area-form-inner">
					<form id="ff" class="form-horizontal" role="form" method="post"  novalidate enctype="multipart/form-data">

						<div class="form-group">
							<label for="reqNama" class="col-sm-2 control-label">Nama</label>
							<div class="col-sm-10">
								<input name="reqNama" class="easyui-validatebox" required="true" size="40" type="text" value="<?=$arrMenu[0]["NAMA"]?>" />
							</div>
						</div>

                      <?php /*?><?
					  $tempJumlahData= 0;
					  for($i=0;$i<$jumlah_menu;$i++)
            		  {
						  $tempId= $arrMenu[$i]["MENU_ID"];
						  $tempParentId= $arrMenu[$i]["MENU_PARENT_ID"];
						  $tempJumlahChild= $arrMenu[$i]["JUMLAH_CHILD"];
                      ?>
                      <div class="form-group" style="background-color: #d9edf7;">
                        <div class="col-sm-4" style="padding-left:5%">
                        	<?=$arrMenu[$i]["MENU"]?>
                        </div>
                        <div class="col-sm-8">
                        	<input type="radio" <? if($arrMenu[$i]["AKSES"] == 'A') echo 'checked'; else echo 'checked'; ?> name="reqCheck<?=$i?>" value="A" onClick="document.getElementById('reqCheck<?=$i?>').value = 'A'" /> All &nbsp;&nbsp;&nbsp;
                            <input type="radio" <? if($arrMenu[$i]["AKSES"] == 'R') echo 'checked';?> name="reqCheck<?=$i?>" value="R" onClick="document.getElementById('reqCheck<?=$i?>').value = 'R'" /> Readonly &nbsp;&nbsp;&nbsp;
                            <input type="radio" <? if($arrMenu[$i]["AKSES"] == 'D') echo 'checked';?> name="reqCheck<?=$i?>" value="D" onClick="document.getElementById('reqCheck<?=$i?>').value = 'D'" /> Disabled
                            <input type="hidden" name="reqMenuId[]" value="<?=$arrMenu[$i]["MENU_ID"]?>">
                            <input type="hidden" name="reqCheck[]" id="reqCheck<?=$i?>" value="<?=$arrMenu[$i]["AKSES"]?>">
                        </div>
                      </div>
                      <?
					  }
					  ?><?php */?>

					  <?
					  $tempJumlahData= 0;
					  for($i=0;$i<count($arrMenu);$i++)
					  {
					  	$tempId= $arrMenu[$i]["MENU_ID"];
					  	$tempParentId= $arrMenu[$i]["MENU_PARENT_ID"];
					  	$tempJumlahChild= $arrMenu[$i]["JUMLAH_CHILD"];
					  	$tempPanjangMenu= $arrMenu[$i]["PANJANG_MENU"];

					  	if($tempJumlahChild == '0')
					  	{
					  		?>
					  		<div class="form-group" style="background-color: #d9edf7;">
					  			<div class="col-sm-4" style="padding-left:<?=$tempPanjangMenu?>%">
					  				<?=$arrMenu[$i]["MENU"]?>
					  			</div>
					  			<div class="col-sm-8">
					  				<input type="radio" <? if($arrMenu[$i]["AKSES"] == 'A') echo 'checked'; else echo 'checked'; ?> name="reqCheck<?=$i?>" value="A" onClick="document.getElementById('reqCheck<?=$i?>').value = 'A'" /> All &nbsp;&nbsp;&nbsp;
					  				<input type="radio" <? if($arrMenu[$i]["AKSES"] == 'R') echo 'checked';?> name="reqCheck<?=$i?>" value="R" onClick="document.getElementById('reqCheck<?=$i?>').value = 'R'" /> Readonly &nbsp;&nbsp;&nbsp;
					  				<input type="radio" <? if($arrMenu[$i]["AKSES"] == 'D') echo 'checked';?> name="reqCheck<?=$i?>" value="D" onClick="document.getElementById('reqCheck<?=$i?>').value = 'D'" /> Disabled
					  				<input type="hidden" name="reqMenuId[]" value="<?=$arrMenu[$i]["MENU_ID"]?>">
					  				<input type="hidden" name="reqCheck[]" id="reqCheck<?=$i?>" value="<?=$arrMenu[$i]["AKSES"]?>">
					  			</div>
					  		</div>
					  		<?
					  	}
					  	else
					  	{
					  		?>
					  		<div class="form-group">
					  			<?php /*?><div class="col-sm-12" style="padding-left:<?=$tempPanjangMenu?>%">
					  				<?=$arrMenu[$i]["MENU"]?>
					  			</div><?php */?>
                                <div class="col-sm-4" style="padding-left:<?=$tempPanjangMenu?>%">
					  				<?=$arrMenu[$i]["MENU"]?>
					  			</div>
					  			<div class="col-sm-8">
					  				<input type="radio" <? if($arrMenu[$i]["AKSES"] == 'A') echo 'checked'; else echo 'checked'; ?> name="reqCheck<?=$i?>" value="A" onClick="document.getElementById('reqCheck<?=$i?>').value = 'A'" /> All &nbsp;&nbsp;&nbsp;
					  				<input type="radio" <? if($arrMenu[$i]["AKSES"] == 'R') echo 'checked';?> name="reqCheck<?=$i?>" value="R" onClick="document.getElementById('reqCheck<?=$i?>').value = 'R'" /> Readonly &nbsp;&nbsp;&nbsp;
					  				<input type="radio" <? if($arrMenu[$i]["AKSES"] == 'D') echo 'checked';?> name="reqCheck<?=$i?>" value="D" onClick="document.getElementById('reqCheck<?=$i?>').value = 'D'" /> Disabled
					  				<input type="hidden" name="reqMenuId[]" value="<?=$arrMenu[$i]["MENU_ID"]?>">
					  				<input type="hidden" name="reqCheck[]" id="reqCheck<?=$i?>" value="<?=$arrMenu[$i]["AKSES"]?>">
					  			</div>
					  		</div>
					  		<?
					  		$tempJumlahData++;
					  	}
					  }
					  ?>

					  <div>
					  	<input type="hidden" name="reqRowId" value="<?=$reqRowId?>">
					  	<input type="hidden" name="reqMode" value="<?=$reqMode?>">
					  	<input type="hidden" name="reqTable" value="<?=$reqTable?>">
					  	<input type="button" onclick="OpenDHTML('app/loadUrl/app/user_group_add?reqId=<?=$reqId?>');" value="Kembali" />
					  	<input type="submit" value="Submit">
					  </div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	function OpenDHTML(url)
	{
		document.location.href= url;
	}
</script>
</body>
</html>