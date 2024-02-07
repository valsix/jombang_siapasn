<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('UserLogin');
$this->load->model('UserLoginDetil');
$this->load->model('UserGroup');

$set= new UserLogin();
$user_group= new UserGroup();

$reqId = $this->input->get("reqId");

if($reqId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$set->selectByParamsMonitoring(array("USER_LOGIN_ID"=>$reqId));
	$set->firstRow();
	$reqLoginUser= $set->getField("LOGIN_USER");
	$reqLoginLevel= $set->getField("LOGIN_LEVEL");
	$reqUserGroupId= $set->getField("USER_GROUP_ID");
	$reqSatkerId= $set->getField("SATUAN_KERJA_ID");
	$reqSatker= $set->getField("SATUAN_KERJA_NAMA");
	
	$set_data = new UserLoginDetil();
	$set_data->selectByParams(array("USER_LOGIN_ID"=>$reqId));
	
	$arrData="";
	$index_data= 0;
	$set_data= new UserLoginDetil();
	$set_data->selectByParamsMonitoring(array("USER_LOGIN_ID"=>$reqId));
	while($set_data->nextRow())
	{
		$arrData[$index_data]["USER_LOGIN_DETIL_ID"] = $set_data->getField("USER_LOGIN_DETIL_ID");
		$arrData[$index_data]["PEGAWAI_ID"] = $set_data->getField("PEGAWAI_ID");
		$arrData[$index_data]["NIP_BARU"] = $set_data->getField("NIP_BARU");
		$arrData[$index_data]["TANGGAL_AWAL"] =  dateTimeToPageCheck($set_data->getField("TANGGAL_AWAL"));
		$arrData[$index_data]["TANGGAL_AKHIR"] =  dateTimeToPageCheck($set_data->getField("TANGGAL_AKHIR"));
		$index_data++;
	}
}

$user_group->selectByParams(array(),-1,-1, " AND STATUS IS NULL");
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

    <!-- AUTO KOMPLIT -->
    <link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
    <script src="lib/autokomplit/jquery-ui.js"></script>

    <script type="text/javascript">	
    	$(function(){
    		$('#ff').form({
    			url:'user_login_json/add',
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
         				document.location.href= "app/loadUrl/app/user_login_add/?reqId="+rowid;
						top.frames['mainFrame'].location.reload();
         			}, 1000));
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

			var s_url= "app/loadUrl/app/user_login_add_row.php";
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

				<div id="judul-popup">Tambah User Login</div>
				<div id="area-form-inner">
					<form id="ff" method="post"  novalidate enctype="multipart/form-data">

						
						<div class="row">
							<div class="input-field col s12">
								<label for="reqLoginUser">Login User</label>
								<input name="reqLoginUser" id="reqLoginUser"  type="text" required value="<?=$reqLoginUser?>" />
							</div>
						</div>
                        
                        <?
						if($reqId=="")
						{
                        ?>
                        <div class="row">
							<div class="input-field col s12">
								<label for="reqLoginPassword">Login Password</label>
								<input name="reqLoginPassword" id="reqLoginPassword"  type="password" />
							</div>
						</div>
                        <?
						}
                        ?>
                        
                        <div class="row">
							<div class="input-field col s12">
								<select id="reqLoginLevel" name="reqLoginLevel">
									<option value="1" <? if(1==$reqLoginLevel) echo 'selected'?>>UPT</option>
									<option value="20" <? if(20==$reqLoginLevel) echo 'selected'?>>Dinas / badan</option>
									<option value="30" <? if(30==$reqLoginLevel) echo 'selected'?>>Teknis - BKD</option>
									<option value="40" <? if(40==$reqLoginLevel) echo 'selected'?>>Sistem</option>
									<option value="99" <? if(99==$reqLoginLevel) echo 'selected'?>>admin - BKD</option>
								</select>
								<label>Login Level</label>
							</div>
						</div>


						<div class="row">
							<div class="input-field col s12">
								<select id="reqUserGroupId" name="reqUserGroupId">
									<?
									while($user_group->nextRow())
									{
										?>
										<option value="<?=$user_group->getField("USER_GROUP_ID")?>" <? if($user_group->getField("USER_GROUP_ID")==$reqUserGroupId) echo 'selected'?>><?=$user_group->getField("NAMA")?></option>
										<?
									}
									?>
								</select>
								<label>User Group</label>
							</div>
						</div>
                        
						<div class="row">
							<div class="input-field col s12">
								<label for="reqLoginUser">Satuan Kerja</label>
								<input type="text" id="reqSatker"  name="reqSatker" value="<?=$reqSatker?>" required />
                                <input type="hidden" name="reqSatkerId" id="reqSatkerId" value="<?=$reqSatkerId?>" />
							</div>
						</div>

						<!-- <div class="form-group"> -->


							<div class="row">
								<div class="col s12 m4">
									<b>Pegawai</b> <a style="cursor:pointer" title="Tambah" onclick="createRowData()"><img src="images/icon-tambah.png" width="16" height="16" border="0" /></a>
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
									$reqRowDetilId = $arrData[$checkbox_index]["USER_LOGIN_DETIL_ID"];
									$reqPegawaiId = $arrData[$checkbox_index]["PEGAWAI_ID"];
									$reqPegawai = $arrData[$checkbox_index]["NIP_BARU"];
									$reqTglAwal = $arrData[$checkbox_index]["TANGGAL_AWAL"];
									$reqTglAkhir = $arrData[$checkbox_index]["TANGGAL_AKHIR"];
									?>
									<div class="col s12 m4">
                                        <input type="text" id="reqPegawai<?=$checkbox_index?>"  name="reqPegawai[]" value="<?=$reqPegawai?>" />
                                        <input type="hidden" name="reqPegawaiId[]" id="reqPegawaiId<?=$checkbox_index?>" value="<?=$reqPegawaiId?>" />
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