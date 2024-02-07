<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('AppUserLogin');
$set= new AppUserLogin();


$reqId = $this->input->get("reqId");

if($reqId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$set->selectByParamsMonitoring(array("t1.USER_LOGIN_ID"=>$reqId));
	$set->firstRow();
	$reqLoginUser= $set->getField("LOGIN_USER");
	
	//$reqUserGroupId= $set->getField("NAMA");
	//$reqUserGroupId= $set->getField("USER_GROUP_ID");
	$reqSatkerId= $set->getField("SATUAN_KERJA_ID");
	$reqSatker= $set->getField("NAMA_SATUAN");
	$reqPegawai=$set->getField("NIP_BARU");
	$reqPegawaiId=$set->getField("PEGAWAI_ID");
	//$reqStatusMenuKhusus= $set->getField("STATUS_MENU_KHUSUS");
	//$set_data = new UserLoginDetil();
	//$set_data->selectByParams(array("USER_LOGIN_ID"=>$reqId));
	
	//$arrData="";
	//$index_data= 0;
	//$set_data= new AppUserLogin();
	//$set_data->selectByParamsMonitoring(array("USER_LOGIN_ID"=>$reqId));
	//while($set_data->nextRow())
	//{
		//$arrData[$index_data]["USER_LOGIN_DETIL_ID"] = $set_data->getField("USER_LOGIN_DETIL_ID");
		//$arrData[$index_data]["PEGAWAI_ID"] = $set_data->getField("PEGAWAI_ID");
		//$arrData[$index_data]["NAMA"] = $set_data->getField("NAMA_SATUAN");
		//$arrData[$index_data]["PEGAWAI_ID"] = $set_data->getField("PEGAWAI_ID");
		//$arrData[$index_data]["LOGIN_USER"] = $set_data->getField("LOGIN_USER");
		//$arrData[$index_data]["USER_LOGIN_ID"] = $set_data->getField("USER_LOGIN_ID");
				
		//$arrData[$index_data]["SATUAN_KERJA_ID"] = $set_data->getField("SATUAN_KERJA_ID");
		//$arrData[$index_data]["TANGGAL_AWAL"] =  dateTimeToPageCheck($set_data->getField("TANGGAL_AWAL"));
		//$arrData[$index_data]["TANGGAL_AKHIR"] =  dateTimeToPageCheck($set_data->getField("TANGGAL_AKHIR"));
		//$index_data++;
	//}
}

//$user_group->selectByParams(array(),-1,-1, " AND STATUS IS NULL");
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

    <!-- AUTO KOMPLIT -->
    <link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
    <script src="lib/autokomplit/jquery-ui.js"></script>

    <script type="text/javascript">	
    	$(function(){
    		$('#ff').form({
    			url:'app_user_login_json/add',
    			onSubmit:function(){
    				reqSatker;reqSatkerId
    				var reqSatker= "";
    				reqSatker= $("#reqSatker").val();

    				if(reqSatker == "")
    				$("#reqSatkerId").val("");

    				return $(this).form('validate');
    			},
    			success:function(data){
				// alert(data);return false;
				data = data.split("-");
				rowid= data[0];
				infodata= data[1];
         		//$.messager.alert('Info', infodata, 'info');

         		if(rowid == "xxx")
         		{
         			mbox.alert(infodata, {open_speed: 0});
					  $('.modal-title').html('helllllooooooo');
					//  $('.modal').hide();
					//$('.x close').click(); 
						$('#iframeModal').modal('hide');
         		}
         		else
         		{
					 // $('.modal-title').html('helllllooooooo');
					  //$('.x close').click(); 
				//	  $('.modal').hide();
						//$(this).closest('.ui-dialog-content').dialog('close'); 
         			mbox.alert(infodata, {open_speed: 500}, interval = window.setInterval(function() 
         			{
						clearInterval(interval);
         				mbox.close();
						//$('.modal').hide();
						//	$('#iframeModal').modal('hide');
					  //$('.Modal').modal('hide');
					//	window.parent.closePopup('Penyimpanan Berhasil');
					//	popupWindow.close();
					//close();
					//	console.log('penyimpangan berhasil');
					
         			//	document.location.href= "app/loadUrl/app/app_user_login_add/?reqId="+rowid;
					//	document.location.href= "app/loadUrl/app/app_user_login/?reqId="+rowid;
         			//	top.frames['mainFrame'].location.reload();
         			}, 1000));
					$(".mbox > .right-align").css({"display": "none"});
         		}

         	}
         });

    		$('input[id^="reqSatker"], input[id^="reqPegawai"]').each(function(){
    			$(this).autocomplete({
    				source:function(request, response){
    					var id= this.element.attr('id');
    					var replaceAnakId= replaceAnak= urlAjax= "";

    					if (id.indexOf('reqSatker') !== -1)
    					{
    						var element= id.split('reqSatker');
    						var indexId= "reqSatkerId"+element[1];
    						urlAjax= "satuan_kerja_json/auto";
    					}
    					else if (id.indexOf('reqPegawai') !== -1)
    					{
    						var element= id.split('reqPegawai');
    						var indexId= "reqPegawaiId"+element[1];
    						urlAjax= "pegawai_json/auto";
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
    									return {desc: element['desc'], id: element['id'], label: element['label']};
    								});
    								response(array);
    							}
    						}
    					})
    				},
    				focus: function (event, ui) 
    				{ 
    					var id= $(this).attr('id');
    					if (id.indexOf('reqSatker') !== -1)
    					{
    						var element= id.split('reqSatker');
    						var indexId= "reqSatkerId"+element[1];
    					}
    					else if (id.indexOf('reqPegawai') !== -1)
    					{
    						var element= id.split('reqPegawai');
    						var indexId= "reqPegawaiId"+element[1];
    					}

    					$("#"+indexId).val(ui.item.id).trigger('change');
    				},
    				autoFocus: true
    			})
    			.autocomplete( "instance" )._renderItem = function( ul, item ) {
				//return
				return $( "<li>" )
				.append( "<a>" + item.desc  + "</a>" )
				.appendTo( ul );
			}
			;
		});

    	});

    	function createRowData()
    	{		
			/*if (!document.getElementsByTagName) return;
			tabBody=document.getElementsByTagName("TBODY").item(1);
			
			var rownum= tabBody.rows.length;*/
			//alert(rownum);

			var s_url= "app/loadUrl/app/app_user_login_add_row.php";
			$.ajax({'url': s_url,'success': function(data){
				$("#tbDataData").append(data);
			}});
		}
	</script>

	<?php /*?><!-- BOOTSTRAP CORE -->
	<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script><?php */?>


	<link href="lib/mbox/mbox.css" rel="stylesheet">
	<script src="lib/mbox/mbox.js"></script>
	<link href="lib/mbox/mbox-modif.css" rel="stylesheet">
</head>

<body>
	<div class="container-fluid full-height">
		<div class="row full-height">
			<div class="col-md-12 area-form full-height">

				<div class="ubah-color-warna white-text" style="padding: 1em">Tambah User Login</div>
				<div id="area-form-inner">
					<form id="ff" method="post"  novalidate enctype="multipart/form-data">

						<div class="row">
							<label for="reqLoginUser" class="col s12 m2 label-control">Login User</label>
							<div class="input-field col s12 m10">
								<input name="reqLoginUser" id="reqLoginUser" class="easyui-validatebox"  type="text" required value="<?=$reqLoginUser?>" />
							</div>
						</div>

						<?
						if($reqId=="")
						{
							?>
							<div class="row">
								<label for="reqLoginPassword" class="col s12 m2 label-control">Login Password</label>
								<div class="input-field col s12 m10">
									<input name="reqLoginPassword" id="reqLoginPassword"  type="password" />
									<input type="hidden" name="reqId" id="regiId" value="<?=$reqId?>" />
								</div>
							</div>
							<?
						}
						?>
					
						<div class="row">
							<label for="reqPegawai" class="col s12 m2 label-control">Pegawai</label>
							<div class="input-field col s12 m10">
								<input type="text" id="reqPegawai"  name="reqPegawai" value="<?=$reqPegawai?>" required />
								<input type="hidden" name="reqPegawaiId" id="reqPegawaiId" value="<?=$reqPegawaiId?>" />
							</div>
						</div>


				<div class="row">
							<label for="reqSatker" class="col s12 m2 label-control">Satuan Kerja</label>
							<div class="input-field col s12 m10">
								<input type="text" id="reqSatker"  name="reqSatker" value="<?=$reqSatker?>" required />
								<input type="hidden" name="reqSatkerId" id="reqSatkerId" value="<?=$reqSatkerId?>" />
							</div>
						</div>						
							
					



						
						<div class="divider"></div>
						
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

	<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>
	<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>
	<script type="text/javascript">
		$(document).ready( function () {
			$('select').material_select();
		});
	</script>
</body>
</html>