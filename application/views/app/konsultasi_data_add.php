<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$reqId= $this->input->get("reqId");


$this->load->model('Mailbox');

$statement= "AND (A.STATUS IS NULL OR A.STATUS = 0) AND A.MAILBOX_ID = ".$reqId;
$mailbox= new Mailbox();
$jumlah_data= $mailbox->getCountByParams(array(), $statement);
$mailbox->selectByParams(array("A.MAILBOX_ID"=>$reqId), -1,-1);
$mailbox->firstRow();
//echo $mailbox->query;exit;
$tempJudul= $mailbox->getField("SUBYEK");
$tempStatusInfoId= $mailbox->getField("STATUS_INFO_ID");

//echo $mailbox->query;
if($jumlah_data == 1)
{
	$mailbox->setField("FIELD", "STATUS");
	$mailbox->setField("FIELD_VALUE", "1");
	$mailbox->setField("MAILBOX_ID", $reqId);
	$mailbox->updateByField();
}

$statement= "AND A.STATUS = 1 AND A.MAILBOX_ID = ".$reqId;
$jumlah_status= $mailbox->getCountByParamsMaxDetil($statement);
//echo $mailbox->query;exit;

$set= new Mailbox();
$set->selectByParamsDetil(array("A.MAILBOX_ID"=>$reqId), -1,-1, "", "ORDER BY B.MAILBOX_DETIL_ID ASC");
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
				url:'konsultasi_detil_json/add',
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
         					document.location.href= "app/loadUrl/app/konsultasi_data_add?reqId="+rowid;
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
</head>

<body>
	<div class="container-fluid full-height">
		<div class="row full-height">
			<div class="col-md-12 area-form full-height">

				<div class="ubah-color-warna white-text" style="padding: 1em">Tambah Konsultasi</div>
				<div id="area-form-inner">
					<?
					if($jumlah_status == 0)
					{
						?>
						<form id="ff" method="post"  novalidate enctype="multipart/form-data">                        
							<div class="row">
								<label for="reqJudul" class="col s12 m2 label-control-txtarea">Respon</label>
								<div class="input-field col s12 m10">
									<textarea name="reqRespon" id="reqRespon" class="materialize-textarea"><?=$reqRespon?></textarea>
								</div>
							</div>

							<div class="row">
								<div class="input-field col s12 m3 offset-m2">
									<input type="hidden" name="reqId" value="<?=$reqId?>" />
									<input type="hidden" name="reqStatus" value="1" />
									<input type="hidden" name="reqMode" value="insert" />
									<input type="submit" name="reqSubmit"  class="btn green" value="Submit" />
								</div>
							</div>
						</form>                    
						<?
					}
					?>

					<div class="row" style="margin-top: 2em">
						<div class="col s12 m10 offset-m2">
							<?
							$index=1;
							while($set->nextRow())
							{
								$tempUserLogin= $set->getField("USER_LOGIN_ID");

								if($tempUserLogin !== "" && $set->getField("ISI_DETIL") !== "")
								{
									$tempCss= "topik-jawab";
								}
								else
								{
									$tempCss= "topik-tanya";
								}

								if($index == 1)
								{
									?>
									<div id="topik-tanya">
										<span class="tgl-log"><?=getFormattedDateTime($set->getField("TANGGAL"))?></span> 
										<span class=""><?=$set->getField("ISI").$index?></span>
									</div>
									<?
									if($tempUserLogin !== "" && $set->getField("ISI_DETIL") !== "")
									{
										?>
										<div id="topik-jawab">
											<span class="tgl-log"><?=getFormattedDateTime($set->getField("TANGGAL_DETIL"))?></span> 
											<span class=""><?=$set->getField("ISI_DETIL")?></span>
										</div>
										<?
									}
								}
								else
								{
									?>
									<div id="<?=$tempCss?>">
										<span class="tgl-log"><?=getFormattedDateTime($set->getField("TANGGAL_DETIL"))?></span> 
										<span class=""><?=$set->getField("ISI_DETIL")?></span>
									</div>
									<?
								}
								$index+=1;
							}
							?>
						</div>
					</div>
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
			$('.materialize-textarea').trigger('autoresize');
		});
	</script>
</body>
</html>