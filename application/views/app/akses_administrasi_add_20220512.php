<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

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

	<script type="text/javascript">	
		$(function(){
			$('#ff').form({
				url:'user_group_json/add_menu',
				onSubmit:function(){
					return $(this).form('validate');
				},
				success:function(data){
					// $("#setquery").val(data);return false;
					// alert(data);return false;
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
         					document.location.href= "app/loadUrl/app/akses_administrasi_add/?reqId=<?=$reqId?>&reqMenuGroupId=<?=$reqMenuGroupId?>&reqTable=<?=$reqTable?>&reqRowId="+rowid;
         					top.frames['mainFrame'].location.reload();
         				}, 1000));
						$(".mbox > .right-align").css({"display": "none"});
         			}
					// document.location.href= "app/loadUrl/app/akses_administrasi_add?reqId=<?=$reqId?>&reqMenuGroupId=<?=$reqMenuGroupId?>&reqTable=<?=$reqTable?>&reqRowId="+rowid;
				}
			});
			
		});
	</script>

	<!-- BOOTSTRAP CORE -->
	<!-- <link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"> -->
	<!-- <script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> -->
	<link href="lib/mbox/mbox.css" rel="stylesheet">
	<script src="lib/mbox/mbox.js"></script>
	<link href="lib/mbox/mbox-modif.css" rel="stylesheet">
</head>

<body>
	<textarea id="setquery" style="display: none;"></textarea>
	<div class="container-fluid full-height">
		<div class="row full-height">
			<div class="col-md-12 area-form full-height">

				<div class="ubah-color-warna white-text" style="padding: 1em">Menu Akses</div>
				<div id="area-form-inner">
					<form id="ff" class="form-horizontal" role="form" method="post"  novalidate enctype="multipart/form-data">

						<div class="row">
							<label for="reqNama" class="col s12 m2 label-control">Nama</label>
							<div class="input-field col s12 m10">
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
					  		<div class="row" style="background-color: #d9edf7;">
					  			<div class="col s4" style="padding-left:<?=$tempPanjangMenu?>%">
					  				<?=$arrMenu[$i]["MENU"]?>
					  			</div>
					  			<div class="col s8">
					  				<span>
					  					<input type="radio" <? if($arrMenu[$i]["AKSES"] == 'A') echo 'checked'; else echo 'checked'; ?> name="reqCheck<?=$i?>" id="all<?=$i?>" value="A" onClick="document.getElementById('reqCheck<?=$i?>').value = 'A'" />  
					  					<label for="all<?=$i?>">All</label>
					  				</span>

					  				<span>
					  					<input type="radio" <? if($arrMenu[$i]["AKSES"] == 'R') echo 'checked';?> name="reqCheck<?=$i?>" id="read<?=$i?>" value="R" onClick="document.getElementById('reqCheck<?=$i?>').value = 'R'" />  
					  					<label for="read<?=$i?>">Readonly</label>
					  				</span>

					  				<span>
					  					<input type="radio" <? if($arrMenu[$i]["AKSES"] == 'D') echo 'checked';?> name="reqCheck<?=$i?>" value="D" id="dis<?=$i?>" onClick="document.getElementById('reqCheck<?=$i?>').value = 'D'" /> 
					  					<label for="dis<?=$i?>">Disabled</label>
					  				</span>

					  				<input type="hidden" name="reqMenuId[]" value="<?=$arrMenu[$i]["MENU_ID"]?>">
					  				<input type="hidden" name="reqCheck[]" id="reqCheck<?=$i?>" value="<?=$arrMenu[$i]["AKSES"]?>">
					  			</div>
					  		</div>
					  		<?
					  	}
					  	else
					  	{
					  		?>
					  		<div class="row">
					  			<?php /*?><div class="col-sm-12" style="padding-left:<?=$tempPanjangMenu?>%">
					  				<?=$arrMenu[$i]["MENU"]?>
					  			</div><?php */?>
					  			<div class="col s4" style="padding-left:<?=$tempPanjangMenu?>%">
					  				<?=$arrMenu[$i]["MENU"]?>
					  			</div>
					  			<div class="col s8">
					  				<span>
					  					<input type="radio" <? if($arrMenu[$i]["AKSES"] == 'A') echo 'checked'; else echo 'checked'; ?> name="reqCheck<?=$i?>" id="pall<?=$i?>" value="A" onClick="document.getElementById('reqCheck<?=$i?>').value = 'A'" />
					  					<label for="pall<?=$i?>">All</label>
					  				</span>

					  				<span>
					  					<input type="radio" <? if($arrMenu[$i]["AKSES"] == 'R') echo 'checked';?> name="reqCheck<?=$i?>" id="pread<?=$i?>" value="R" onClick="document.getElementById('reqCheck<?=$i?>').value = 'R'" />  
					  					<label for="pread<?=$i?>">Readonly</label>
					  				</span>

					  				<span>
					  					<input type="radio" <? if($arrMenu[$i]["AKSES"] == 'D') echo 'checked';?> name="reqCheck<?=$i?>" id="pdis<?=$i?>" value="D" onClick="document.getElementById('reqCheck<?=$i?>').value = 'D'" /> 
					  					<label for="pdis<?=$i?>">Disabled</label>
					  				</span>

					  				<input type="hidden" name="reqMenuId[]" value="<?=$arrMenu[$i]["MENU_ID"]?>">
					  				<input type="hidden" name="reqCheck[]" id="reqCheck<?=$i?>" value="<?=$arrMenu[$i]["AKSES"]?>">
					  			</div>
					  		</div>
					  		<?
					  		$tempJumlahData++;
					  	}
					  }
					  ?>

					  <div class="row">
					  	<div class="input-field col s12 m10 offset-m2">
					  		<input type="hidden" name="reqRowId" value="<?=$reqRowId?>">
					  		<input type="hidden" name="reqMode" value="<?=$reqMode?>">
					  		<input type="hidden" name="reqTable" value="<?=$reqTable?>">
					  		<input type="button" class="btn orange" onclick="OpenDHTML('app/loadUrl/app/user_group_add?reqId=<?=$reqId?>');" value="Kembali" />
					  		<input type="submit" class="btn green" value="Submit">
					  	</div>
					  </div>

					  <div>
					  	
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