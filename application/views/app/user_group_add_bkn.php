<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('Menu');
$this->load->model('Menu');

$reqId= $this->input->get("reqId");

if(empty($reqId)) $reqId= 0;

// $set= new UserGroup();
// $set->selectByParams(array(), -1,-1, " AND A.USER_GROUP_ID = ".$reqId);
// $set->firstRow();
// $set->getField("")

$arrMenu= [];
$set= new Menu();
$set->selectparam(" AND A.MENU_GROUP_ID = 1 AND A.MENU_PARENT_ID LIKE '01%'");
// echo $set->query;exit;
while($set->nextRow())
{
	$arrdata= [];
	$arrdata["MENU_PARENT_ID"]= $set->getField("MENU_PARENT_ID");
	$arrdata["MENU_ID"]= $set->getField("MENU_ID");
	$arrdata["NAMA"]= $set->getField("NAMA");
	array_push($arrMenu, $arrdata);
}
// print_r($arrMenu);exit;

$arrInfoData= [];
$set= new Menu();
$set->selectparamsapk(" AND A.USER_GROUP_ID = ".$reqId);
// echo $set->query;exit;
while($set->nextRow())
{
	$infomenuid= $reqId."-".$set->getField("MENU_ID");
	$arrdata= [];
	$arrdata["key"]= $infomenuid;
	$arrdata["MENU_ID"]= $set->getField("MENU_ID");
	$arrdata["LIHAT"]= $set->getField("LIHAT");
	$arrdata["KIRIM"]= $set->getField("KIRIM");
	$arrdata["TARIK"]= $set->getField("TARIK");
	$arrdata["SYNC"]= $set->getField("SYNC");
	array_push($arrInfoData, $arrdata);
}
// print_r($arrInfoData);exit;
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
				url:'user_group_json/addbkn',
				onSubmit:function(){
					return $(this).form('validate');
				},
				success:function(data){
					data = data.split("-");
					rowid= data[0];
					infodata= data[1];

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
         					document.location.href= "app/loadUrl/app/user_group_add_bkn/?reqId=<?=$reqId?>";
         					top.frames['mainFrame'].location.reload();
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

	<style type="text/css">
		.tdcenter{
	        text-align: center;
	        border: 1px solid black !important;
	        vertical-align: top;;
	    }

	    .tdleft{
	        border: 1px solid black !important;
	        vertical-align: top;;
	    }
	</style>
</head>

<body>
<textarea id="setquery" style="display: none;"></textarea>
<div class="container-fluid full-height">
	<div class="row full-height">
		<div class="col-md-12 area-form full-height">

			<div class="ubah-color-warna white-text" style="padding: 1em">Setting BKN</div>
			<div id="area-form-inner">
				<form id="ff" class="form-horizontal" role="form" method="post"  novalidate enctype="multipart/form-data">
				
					<table class="bordered table_list">
						<thead>
							<tr>
								<th style="vertical-align: top;" class="tdcenter" rowspan="2">
									Menu<br/>
									<input id="reqRadioHeadCheck-menu" type="checkbox" />
								</th>
								<th class="tdcenter" colspan="2" width="10px">Lihat Menu</th>
								<th class="tdcenter" colspan="2" width="10px">Kirim BKN</th>
								<th class="tdcenter" colspan="2" width="10px">Tarik BKN</th>
								<th class="tdcenter" colspan="2" width="10px">Sync BKN</th>
							</tr>
							<tr>
								<th class="tdcenter" width="5px" colspan="2">
									Hak<br/>
									<input id="reqRadioHeadCheck-lihat" type="checkbox" />
									<label for="reqRadioHeadCheck-lihat">&nbsp;</label>
								</th>
								<th class="tdcenter" width="5px" colspan="2">
									Hak<br/>
									<input id="reqRadioHeadCheck-kirim" type="checkbox" />
									<label for="reqRadioHeadCheck-kirim">&nbsp;</label>
								</th>
								<th class="tdcenter" width="5px" colspan="2">
									Hak<br/>
									<input id="reqRadioHeadCheck-tarik" type="checkbox" />
									<label for="reqRadioHeadCheck-tarik">&nbsp;</label>
								</th>
								<th class="tdcenter" width="5px" colspan="2">
									Hak<br/>
									<input id="reqRadioHeadCheck-sync" type="checkbox" />
									<label for="reqRadioHeadCheck-sync">&nbsp;</label>
								</th>
							</tr>
						</thead>
						<tbody>
                        <?php 
                        foreach($arrMenu as $key=>$value)
                        {
                        	$i= $key;
                        	$keyinfoid= $value['MENU_ID'];
                        	$keyparentinfoid= "";
                        	$infoid= $value['MENU_ID'];
							$infonama= $value['NAMA'];

							$nbps= "";
							if(strlen($infoid) > 4)
							{
								// $keyinfoid= $value['MENU_ID']."-".$value['MENU_PARENT_ID'];
								$keyinfoid= $value['MENU_PARENT_ID'];
								$keyparentinfoid= $value['MENU_ID'];
								for($index=0; $index < strlen($infoid); $index++)
								{
									$nbps.= "&nbsp;";
								}
							}

							$lihat= $kirim= $tarik= $sync= 0;
							$infocarikey= $reqId."-".$infoid;
							$arrcheck= in_array_column($infocarikey, "key", $arrInfoData);
							if(!empty($arrcheck))
							{
								$lihat= $arrInfoData[$arrcheck[0]]["LIHAT"];
								$kirim= $arrInfoData[$arrcheck[0]]["KIRIM"];
								$tarik= $arrInfoData[$arrcheck[0]]["TARIK"];
								$sync= $arrInfoData[$arrcheck[0]]["SYNC"];
							}
                        ?>
                        <tr>
                        	<td class="tdleft">
                        		<b><?=$nbps.$infonama?></b>
                        		<input name="modul[<?=$i?>]" type="hidden" value="<?=$infoid?>" class="span3" readonly="readonly" />
                        		<input id="reqRadioCheck-<?=$keyinfoid?>-<?=$keyparentinfoid?>" type="checkbox" />
                        		<label for="reqRadioCheck-<?=$keyinfoid?>-<?=$keyparentinfoid?>">&nbsp;</label>
                        	</td>
                        	<td class="tdcenter" colspan="2">
                        		<input id="reqRadioCheck-<?=$keyinfoid?>-lihat-<?=$keyparentinfoid?>" name="lihat[<?=$i?>]" type="checkbox" value="1" <?=($lihat==1)?'checked="checked"':''?>/>
                        		<label for="reqRadioCheck-<?=$keyinfoid?>-lihat-<?=$keyparentinfoid?>">&nbsp;</label>
                        	</td>
                        	<td class="tdcenter" colspan="2">
                        		<input id="reqRadioCheck-<?=$keyinfoid?>-kirim-<?=$keyparentinfoid?>" name="kirim[<?=$i?>]" type="checkbox" value="1" <?=($kirim==1)?'checked="checked"':''?>/>
                        		<label for="reqRadioCheck-<?=$keyinfoid?>-kirim-<?=$keyparentinfoid?>">&nbsp;</label>
                        	</td>
                        	<td class="tdcenter" colspan="2">
                        		<input id="reqRadioCheck-<?=$keyinfoid?>-tarik-<?=$keyparentinfoid?>" name="tarik[<?=$i?>]" type="checkbox" value="1" <?=($tarik==1)?'checked="checked"':''?>/>
                        		<label for="reqRadioCheck-<?=$keyinfoid?>-tarik-<?=$keyparentinfoid?>">&nbsp;</label>
                        	</td>
                        	<td class="tdcenter" colspan="2">
                        		<input id="reqRadioCheck-<?=$keyinfoid?>-sync-<?=$keyparentinfoid?>" name="sync[<?=$i?>]" type="checkbox" value="1" <?=($sync==1)?'checked="checked"':''?>/>
                        		<label for="reqRadioCheck-<?=$keyinfoid?>-sync-<?=$keyparentinfoid?>">&nbsp;</label>
                        	</td>
                        </tr>
                        <?
                    	}
                        ?>
					</table>

					<div class="row">
						<div class="input-field col s12 m10 offset-m2">
							<input type="hidden" name="reqId" value="<?=$reqId?>">
							<input type="submit" class="btn green" value="Submit">
						</div>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('[id^="reqRadioHeadCheck"]').click(function() {
            var tempId= $(this).attr('id');
            infochecked= $(this).prop('checked');

            arrTempId= String(tempId);
            arrTempId= arrTempId.split('-');
            reqCheckHeadGrup= arrTempId[1];
            // console.log(reqCheckHeadGrup);
            
            $('[id^="reqRadioCheck"]').each(function(){

                var tempId= $(this).attr('id');

                arrTempId= String(tempId);
                arrTempId= arrTempId.split('-');
                // console.log(arrTempId);

                // reqCheckGrup= arrTempId[1];
                // reqCheckKode= arrTempId[2];
                reqCheckDetil= arrTempId[2];

                if(typeof reqCheckDetil == "undefined")
                    reqCheckDetil= "";

                // console.log(reqCheckGrup+"-"+reqCheckKode+"-"+reqCheckDetil);

                if(reqCheckDetil == reqCheckHeadGrup)
                {
                    if(infochecked == true)
                    {
                        $(this).attr('checked', true);
                        $(this).prop('checked', true);
                    }
                    else
                    {
                        $(this).attr('checked', false);
                        $(this).prop('checked', false); 
                    }
                }

           });

        });

        $('[id^="reqRadioCheck"]').click(function() {
            var tempId= $(this).attr('id');
            infochecked= $(this).prop('checked');

            arrTempId= String(tempId);
            arrTempId= arrTempId.split('-');
            // console.log(arrTempId);

            reqCheckGrup= arrTempId[1];
            reqCheckKode= arrTempId[2];
            reqCheckDetil= arrTempId[3];

            if(typeof reqCheckDetil == "undefined")
                reqCheckDetil= "";

            // console.log(reqCheckGrup+"-"+reqCheckKode+"-"+reqCheckDetil);

            if(reqCheckDetil == "")
            {
            	if(!$.isNumeric(reqCheckKode))
            	{
	                if(infochecked == true)
	                {
	                    $('[id^="reqRadioCheck-'+reqCheckGrup+'-'+reqCheckKode+'"]').attr('checked', true);
	                    $('[id^="reqRadioCheck-'+reqCheckGrup+'-'+reqCheckKode+'"]').prop('checked', true);
	                }
	                else
	                {
	                    $('[id^="reqRadioCheck-'+reqCheckGrup+'-'+reqCheckKode+'"]').attr('checked', false);
	                    $('[id^="reqRadioCheck-'+reqCheckGrup+'-'+reqCheckKode+'"]').prop('checked', false);
	                }
            	}
            	else
            	{
            		vid= "#reqRadioCheck-"+reqCheckGrup+"-lihat-"+reqCheckKode+", #reqRadioCheck-"+reqCheckGrup+"-kirim-"+reqCheckKode+", #reqRadioCheck-"+reqCheckGrup+"-tarik-"+reqCheckKode+", #reqRadioCheck-"+reqCheckGrup+"-sync-"+reqCheckKode;
            		if(infochecked == true)
	                {
	                    $(vid).attr('checked', true);
	                    $(vid).prop('checked', true);
	                }
	                else
	                {
	                    $(vid).attr('checked', false);
	                    $(vid).prop('checked', false);
	                }
            	}
            }

         });
    });
</script>
<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>
<script type="text/javascript">
	$(document).ready( function () {
		$('select').material_select();
	});
</script>
</body>
</html>