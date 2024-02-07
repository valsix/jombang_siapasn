<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/klasifikasi');

$set= new klasifikasi();

$reqId = $this->input->get("reqId");

if($reqId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$set->selectByParams(array("KLASIFIKASI_ID"=>$reqId));
	$set->firstRow();

	$reqKlasifikasiParentId= $set->getField("KLASIFIKASI_PARENT_ID");
	$reqKode= $set->getField("KODE");
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

	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">

	<!-- BOOTSTRAP -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!--<script src="js/jquery-1.10.2.min.js"></script>-->
	<script src="lib/bootstrap/js/jquery.min.js"></script>
	<script src="lib/bootstrap/js/bootstrap.js"></script>
	<link href="lib/bootstrap/css/bootstrap.css" rel="stylesheet">

	<!-- FONT AWESOME -->
	<link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
	<script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
	<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

	<!-- AUTO KOMPLIT -->
	<link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
	<script src="lib/autokomplit/jquery-ui.js"></script>

	<!-- <script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>
	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
	<script type="text/javascript" src="lib/easyui/globalfunction.js"></script> -->

	<script type="text/javascript">	
		$(function(){
			$('input[id^="reqKlasifikasiParent"]').each(function(){
				$(this).autocomplete({
					source:function(request, response){
						var id= this.element.attr('id');
						var replaceAnakId= replaceAnak= urlAjax= "";

						if (id.indexOf('reqKlasifikasiParent') !== -1)
						{
							var element= id.split('reqKlasifikasiParent');
							var indexId= "reqKlasifikasiParentId"+element[1];
							urlAjax= "surat/klasifikasi_json/combo?reqParentId=0";
						}

						$.ajax({
							url: urlAjax,
							type: "GET",
							dataType: "json",
							data: { term: request.term },
							success: function(responseData){
								if(responseData == null || responseData == "")
								{
									response(null);
								}
								else
								{
									var array = responseData.map(function(element) {
										return {desc: element['desc'], id: element['id'], label: element['infonama'], kode: element['label']};
									});
									response(array);
								}
							}
						})
					},
					focus: function (event, ui) 
					{ 
						var id= $(this).attr('id');
						if (id.indexOf('reqKlasifikasiParent') !== -1)
						{
							var element= id.split('reqKlasifikasiParent');
							var indexId= "reqKlasifikasiParentId"+element[1];
							$("#reqKode").val(ui.item.kode);
						}
						$("#"+indexId).val(ui.item.id).trigger('change');
					},
					autoFocus: true
				})
				.autocomplete( "instance" )._renderItem = function( ul, item ) {
					return $( "<li>" )
					.append( "<a>" + item.desc  + "</a>" )
					.appendTo( ul );
				}
				;
			});

			$('#ff').form({
				url:'klasifikasi_json/add',
				onSubmit:function(){
					return $(this).form('validate');
				},
				success:function(data){
					// alert(data);return false;
					data = data.split("-");
					rowid= data[0];
					infodata= data[1];

					$.messager.alert('Info', infodata, 'info');
					if(rowid == "xxx")
					{
						// mbox.alert(infodata, {open_speed: 0});
						return false;
					}
					else
					{
						// mbox.alert(infodata, {open_speed: 500}, interval = window.setInterval(function() 
						// {
						// 	clearInterval(interval);
						// 	mbox.close();
							top.frames['mainFrame'].location.reload();
							document.location.href= "app/loadUrl/app/klasifikasi_add/?reqId="+rowid;
						// }, 1000));
						// $(".mbox > .right-align").css({"display": "none"});
					}

					// $.messager.alert('Info', data, 'info');
					// return false;
					// <?
					// if($reqMode == "update")
					// {
					// 	?>
					// 	document.location.reload();
					// 	<?	
					// }
					// else
					// {
					// 	?>
					// 	$('#rst_form').click();
					// 	<?
					// }
					// ?>


				}
			});
			
		});
	</script>

	<!-- BOOTSTRAP CORE -->
	<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

</head>

<body>
	<div class="container-fluid full-height">
		<div class="row full-height">
			<div class="col-md-12 area-form full-height">

				<div id="judul-popup">Tambah Tanda Tangan Bkd</div>
				<div id="area-form-inner">
					<form id="ff" method="post"  novalidate enctype="multipart/form-data">
						<? 
						if($reqMode == 'insert') 
						{ 
						?>
						<div class="form-group">
							<label>Klasifikasi</label><br>
							<input class="easyui-validatebox" required type="text" style="width: 40%" name="reqKlasifikasiParent" id="reqKlasifikasiParent" value="<?=$reqKlasifikasi?>" />
							<input name="reqKlasifikasiParentId" id="reqKlasifikasiParentId" type="hidden" value="<?=$reqKlasifikasiParentId?>" />
						</div>
						<?
						} 
						?>
						<div class="form-group">
							<label>Kode</label><br>
							<input name="reqKode" id="reqKode" class="form-control easyui-validatebox" type="text" required value="<?=$reqKode?>" />
						</div>

						<div class="form-group">
							<label>Nama</label>
							<input name="reqNama" class="form-control easyui-validatebox" class="" type="text" required value="<?=$reqNama?>" />
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