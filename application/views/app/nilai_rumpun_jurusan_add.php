<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model("base/Rumpun");
$this->load->model("base/RumpunNilai");

$reqId= $this->input->get("reqId");

$reqPermenId= 1;

$infopendidikanmode= "pendidikan";
$reqPendidikanRumpunId= -1;
$statement= " AND A.PERMEN_ID = ".$reqPermenId." AND A.INFOMODE = '".$infopendidikanmode."'";
$arrpendidikan=[];
$set= new RumpunNilai();
$set->selectparams(array(), -1,-1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$vgrumpunid= $set->getField("RUMPUN_ID");
	$vgpermenid= $set->getField("PERMEN_ID");
	$vginfomode= $set->getField("INFOMODE");
	$vginfoid= $set->getField("INFOID");

	$arrdata= [];
	$arrdata["KEY"]= $vgrumpunid.":".$vgpermenid.":".$vginfomode.":".$vginfoid;
	$arrdata["RUMPUN_ID"]= $vgrumpunid;
	$arrdata["PERMEN_ID"]= $vgpermenid;
	$arrdata["INFOMODE"]= $vginfomode;
	$arrdata["INFOID"]= $vginfoid;
	$arrdata["NILAI"]= $set->getField("NILAI");
	array_push($arrpendidikan, $arrdata);
}
// print_r($arrpendidikan);exit();

$infomode= "pendidikan_jurusan";
$statement= " AND A.PERMEN_ID = ".$reqPermenId;
$arrrumpun=[];
$set= new Rumpun();
$set->selectByParams(array(), -1,-1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["RUMPUN_ID"]= $set->getField("RUMPUN_ID");
  $arrdata["NAMA"]= $set->getField("NAMA");
  $arrdata["KETERANGAN"]= $set->getField("KETERANGAN");
  array_push($arrrumpun, $arrdata);
}
// print_r($arrrumpun);exit;

$statement= " AND B.PENDIDIKAN_JURUSAN_ID IN (".$reqId.")";
$set= new RumpunNilai();
$set->selectrumpunkualifikasi(array(), -1, -1, $statement);
$arrdatainfo=[];
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["PENDIDIKAN_ID"]= $set->getField("PENDIDIKAN_ID");
  $arrdata["PENDIDIKAN_JURUSAN_ID"]= $set->getField("PENDIDIKAN_JURUSAN_ID");
  $arrdata["INFO_NAMA"]= $set->getField("PENDIDIKAN_NAMA")." (".$set->getField("PENDIDIKAN_JURUSAN_NAMA").")";
  array_push($arrdatainfo, $arrdata);
}
// print_r($arrdatainfo);exit;

$statement= " AND A.PERMEN_ID = ".$reqPermenId." AND A.INFOMODE = '".$infomode."' AND INFOID IN (".$reqId.")";
$arrnilai=[];
$set= new RumpunNilai();
$set->selectparams(array(), -1,-1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$vgrumpunid= $set->getField("RUMPUN_ID");
	$vgpermenid= $set->getField("PERMEN_ID");
	$vginfomode= $set->getField("INFOMODE");
	$vginfoid= $set->getField("INFOID");

	$arrdata= [];
	$arrdata["KEY"]= $vgrumpunid.":".$vgpermenid.":".$vginfomode.":".$vginfoid;
	$arrdata["RUMPUN_ID"]= $vgrumpunid;
	$arrdata["PERMEN_ID"]= $vgpermenid;
	$arrdata["INFOMODE"]= $vginfomode;
	$arrdata["INFOID"]= $vginfoid;
	$arrdata["NILAI"]= $set->getField("NILAI");
	array_push($arrnilai, $arrdata);
}
// print_r($arrnilai);exit();
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
	<style type="text/css">
		table > tbody > tr > td, table > thead > tr > th { 
			border:1px solid #d0d0d0 !important
		}
	</style>

	<script type="text/javascript">	
		$(function(){
			$('#ff').form({
				url:'json/rumpun_nilai_json/addjurusan',
				onSubmit:function(){
					var reqPangkatId= "";
					reqPangkatId= $("#reqPangkatId").val();
					
					if(reqPangkatId == "")
					{
						$.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
						return false;
					}
					return $(this).form('validate');
				},
				success:function(data){
					// console.log(data);return false;
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
         					document.location.href= "app/loadUrl/app/nilai_rumpun_jurusan_add/?reqId=<?=$reqId?>";
         				}, 1000));
						$(".mbox > .right-align").css({"display": "none"});
         			}
         			top.frames['mainFrame'].location.reload();
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

				<div class="ubah-color-warna white-text" style="padding: 1em">Setting Nilai Rumpun Pendidikan Jurusan</div>
				<div id="area-form-inner">
					<form id="ff" method="post"  novalidate enctype="multipart/form-data">

						<table class="striped tabel-responsif">
							<thead>
								<tr>
									<th rowspan="2">Pendidikan Formal</th>
									<th style="text-align: center;" colspan="<?=count($arrrumpun)?>">NILAI PER RUMPUN</th>
	                        	</tr>
	                        	<tr>
								<?
		                        foreach ($arrrumpun as $key => $value) 
		                        {
		                        	$infodetilnama= $value["NAMA"];
		                        ?>
		                        	<th style="width: 10%; text-align: center;"><?=$infodetilnama?></th>
		                        <?
		                    	}
		                        ?>
	                        	</tr>
	                        </thead>
	                        <tbody>
	                        	<?
		                        foreach ($arrdatainfo as $keydetil => $valuedetil) 
		                        {
		                        	$infodetilpendidikanid= $valuedetil["PENDIDIKAN_ID"];
		                        	$infodetilid= $valuedetil["PENDIDIKAN_JURUSAN_ID"];
		                        	$infodetilnama= $valuedetil["INFO_NAMA"];

		                        	$reqPendidikanNilai= 0;
		                        	$infocari= $reqPendidikanRumpunId.":".$reqPermenId.":".$infopendidikanmode.":".$infodetilpendidikanid;
		                        	$arrval= [];
                                    $arrval= in_array_column($infocari, "KEY", $arrpendidikan);
                                    if(!empty($arrval))
                                    {
                                    	$reqPendidikanNilai= $arrpendidikan[$arrval[0]]["NILAI"];
                                    }
		                        ?>
		                        <tr>
									<td><?=$infodetilnama?></td>
									<?
			                        foreach ($arrrumpun as $key => $value) 
			                        {
			                        	$infodetilnama= $value["NAMA"];
			                        	$infodetilrumpunid= $value["RUMPUN_ID"];
			                        	$vdetilid= $infodetilid."_".$infodetilrumpunid;

			                        	$checked= "";
			                        	$reqNilai= 0;
			                        	$infocari= $infodetilrumpunid.":".$reqPermenId.":".$infomode.":".$infodetilid;
			                        	$arrval= [];
	                                    $arrval= in_array_column($infocari, "KEY", $arrnilai);
	                                    if(!empty($arrval))
	                                    {
	                                    	$checked= "checked";
	                                    	$reqNilai= $arrnilai[$arrval[0]]["NILAI"];
	                                    }
			                        ?>
			                        	<td style="text-align: center;">
			                        		<input type="hidden" name="reqInfoId[]" value="<?=$infodetilid?>" />
			                        		<input type="hidden" name="reqRumpunId[]" value="<?=$infodetilrumpunid?>" />
			                        		<input type="hidden" name="reqNilai[]" id="reqNilai<?=$vdetilid?>" value="<?=$reqNilai?>" />
			                        		<input type="hidden" id="reqPendidikan<?=$vdetilid?>" value="<?=$reqPendidikanNilai?>" />
			                        		<input type="checkbox" <?=$checked?> class="editor-active" id="infocheck<?=$vdetilid?>" /><label for='infocheck<?=$vdetilid?>'></label>
			                        	</td>
			                        <?
			                    	}
			                        ?>
								</tr>
		                        <?
		                    	}
		                        ?>
	                        </tbody>
	                    </table>

                        <div class="row">
							<div class="input-field col s12">
								<input type="hidden" name="reqId" value="<?=$reqId?>" />
								<input type="hidden" name="reqInfoMode" value="<?=$infomode?>" />
								<input type="hidden" name="reqPermenId" value="<?=$reqPermenId?>" />
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
	<!-- <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script> -->
	<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>
	<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>
	<script type="text/javascript">
		$(document).ready( function () {
			$('select').material_select();
		});

		$('input[id^="reqNilai"]').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');
		});

		$('input[id^="infocheck"]').change(function() {
			vid= $(this).attr('id');
			vid= vid.replace("infocheck", "")
			// console.log(vid);

			infochecked= $(this).prop('checked');
			// console.log(infochecked);

			if(infochecked)
				vnilai= $("#reqPendidikan"+vid).val();
			else
				vnilai= 0;

			$("#reqNilai"+vid).val(vnilai);
		});

	</script>
</body>
</html>