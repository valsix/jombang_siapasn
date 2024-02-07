<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/FileHandler.php");

$CI =& get_instance();
$CI->checkUserLogin();

// include_once("lib/pdfinfo-master/src/Howtomakeaturn/PDFInfo/PDFInfo.php");

$this->load->model('JenisDokumen');
$this->load->model('KualitasFile');
$this->load->model('PegawaiFile');

$file = new FileHandler();
$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqUrlFile= $this->input->get("reqUrlFile");
$reqUrlFile= urldecode($reqUrlFile);
$reqUrlFile= str_replace("''", "'", $reqUrlFile);
// $reqUrlFile= str_replace(".PDF", ".pdf", $reqUrlFile);
// echo $reqUrlFile;exit;
$reqMode= $this->input->get("reqMode");

$reqUrlFilePecah=explode(".",$reqUrlFile);
// print_r($reqUrlFilePecah);exit;

$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "0112";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

$reqNamaFile= str_replace("uploads/".$reqId."/","",$reqUrlFile);

$reqCheckImage= $file->getImageCheck($reqUrlFile);

if($reqRowId=="")
{
	$reqMode = 'insert';
	$reqNamaFileNew= str_replace("uploads/".$reqId."/", "", $reqUrlFilePecah[0]);
	$reqNamaFileNew= str_replace(" ", "_", $reqNamaFileNew);
	$reqLinkFileAsli= $reqNamaFileNew;
	$reqLinkFilePath= $reqUrlFile;
	$tempFileDelete= likeMatch("%_delete_%", $reqLinkFileAsli);
	if($tempFileDelete == 1)
	{
		$infostatus= "1";
	}
}
else
{
	$reqMode = 'update';
	$statement= " AND A.PEGAWAI_FILE_ID = ".$reqRowId;
	$set= new PegawaiFile();
	$set->selectByParamsFile(array(), -1,-1, $statement, $reqId);
    // echo $set->query;exit;
	$set->firstRow();
	$reqKategoriFileId= $set->getField("KATEGORI_FILE_ID");
	$reqKualitasFileId= $set->getField("FILE_KUALITAS_ID");
	$reqKeterangan= $set->getField("KETERANGAN");
	$reqPrioritas= $set->getField("PRIORITAS");
	$reqLinkFilePath= $set->getField("PATH");
	$reqLinkFileAsli= $set->getField("PATH_ASLI");
	$reqLinkFileExt= $set->getField("EXT");
	if(empty($reqLinkFileExt))
	{
		$reqLinkFileExt=substr($reqLinkFilePath, -3);
	}
	$reqNamaFileNew=$reqLinkFileAsli.".".$reqLinkFileExt;

	$infostatus= $set->getField("STATUS");
	$tempFileDelete= likeMatch("%_delete_%", $reqLinkFileAsli);
	if($tempFileDelete == 1 && empty($infostatus))
		$infostatus= "1";

	$informasiriwayatable= $set->getField("RIWAYAT_TABLE");
	$informasiriwayaid= $set->getField("RIWAYAT_ID");
	$informasiriwayafield= $set->getField("RIWAYAT_FIELD");
	if($informasiriwayatable == "PERSURATAN.SURAT_MASUK_PEGAWAI")
		$informasiriwayafield= "";
	
	$reqJenisDokumen= $informasiriwayatable.";".$informasiriwayaid.";".$informasiriwayafield;
	
	$arrJenisDokumen= [];
	$index_data= 0;
	$statement= " AND A.PEGAWAI_ID = ".$reqId." AND A.NO_URUT = ".$reqKategoriFileId;
	$set_detil= new PegawaiFile();
	$set_detil->selectByParamsJenisDokumen(array(), -1,-1, $statement);
	// echo $set_detil->query;exit;
	while($set_detil->nextRow())
	{
        if($set_detil->getField("NO_URUT") == 19 && $set_detil->getField("RIWAYAT_ID") == 19)
        	continue;

		//NO_URUT, PEGAWAI_ID, RIWAYAT_TABLE, RIWAYAT_FIELD, RIWAYAT_ID, INFO_DATA
		$arrJenisDokumen[$index_data]["NO_URUT"] = $set_detil->getField("NO_URUT");
		$arrJenisDokumen[$index_data]["PEGAWAI_ID"] = $set_detil->getField("PEGAWAI_ID");
		$arrJenisDokumen[$index_data]["RIWAYAT_TABLE"] = $set_detil->getField("RIWAYAT_TABLE");
		$arrJenisDokumen[$index_data]["RIWAYAT_FIELD"] = $set_detil->getField("RIWAYAT_FIELD");
		$arrJenisDokumen[$index_data]["RIWAYAT_ID"] = $set_detil->getField("RIWAYAT_ID");
		$arrJenisDokumen[$index_data]["INFO_DATA"] = $set_detil->getField("INFO_DATA");
		$arrJenisDokumen[$index_data]["INFO_GROUP_DATA"] = $set_detil->getField("INFO_GROUP_DATA");
		$index_data++;
	}
	unset($set_detil);
	$jumlah_jenis_dokumen= $index_data;
	// print_r($arrJenisDokumen);exit();

}

$infohukumanmenuid= "0111";
$infoakseshukumanmenu= $CI->checkmenupegawai($tempUserLoginId, $infohukumanmenuid);
$disabled= $infolihatfile= "";
if(file_exists($reqLinkFilePath))
{
	$infolihatfile= "1";

	if( ($infoakseshukumanmenu == "R" && $informasiriwayatable == "HUKUMAN") || empty($reqUrlFile))
	{
		$disabled= "disabled";
		$infolihatfile= "";
	}
}

$arrKategoriDokumen= [];
$index_data= 0;
$set_detil= new PegawaiFile();
$set_detil->selectByParamsKategoriDokumen();
// echo $set_detil->query;exit;
while($set_detil->nextRow())
{
	$infokategorifileid= $set_detil->getField("KATEGORI_FILE_ID");
	if($infokategorifileid == 28 && empty($disabled))
	{
		if($infoakseshukumanmenu == "A"){}
		else
			continue;
	}

	$arrKategoriDokumen[$index_data]["KATEGORI_FILE_ID"] = $infokategorifileid;
	$arrKategoriDokumen[$index_data]["NAMA"] = $set_detil->getField("NAMA");
	$index_data++;
}
unset($set_detil);
$jumlah_kategori_dokumen= $index_data;
// print_r($arrKategoriDokumen);exit;

$kualitas= new KualitasFile();
$kualitas->selectByParams(array());

// echo $reqLinkFilePath."?".$reqNamaFileNew;exit;
// $arrvalid= ["png", "jpg"];
$arrvalid= ["pdf"];
$dotexe= strtolower($reqUrlFilePecah[1]);
// print_r($reqUrlFilePecah);exit;
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="msapplication-tap-highlight" content="no">
	<meta name="description" content="Simpeg Jombang">
	<meta name="keywords" content="Simpeg Jombang">
	<title>Simpeg Jombang</title>
	<base href="<?=base_url()?>" />

	<link rel="stylesheet" type="text/css" href="css/gaya.css">

	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
	<script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>

	<script type="text/javascript"> 
		$(function(){
			setJenisDokumen();
	  //parent.iframeLoaded();
	  $('#ff').form({
	  	url:'pegawai_file_json/setting',
	  	onSubmit:function(){
	  		var reqRiwayatTable= reqKualitasFileId= "";
	  		reqRiwayatTable= $("#reqRiwayatTable").val();
	  		reqKualitasFileId= $("#reqKualitasFileId").val();

	  		// if(reqRiwayatTable == "")
	  		if(reqRiwayatId == "")
	  		{
	  			/*mbox.alert("Isikan Jenis dokumen terlebih dahulu", {open_speed: 500}, interval = window.setInterval(function() 
	  			{
					clearInterval(interval);
	  				mbox.close();
	  			}, 1000));
	  			return false;*/
	  		}

	  		if(reqKualitasFileId == "")
	  		{
	  			/*mbox.alert("Isikan Kualitas dokumen terlebih dahulu", {open_speed: 500}, interval = window.setInterval(function() 
	  			{
					clearInterval(interval);
	  				mbox.close();
	  			}, 1000));
	  			return false;*/
	  		}

	  		if($(this).form('validate')){}
  			else
  			{
  				$.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
  				return false;
  			}
	  	},
	  	success:function(data){
	  	  <?
	  	  if($reqId == 11655)
	  	  {
	  	  ?>
          // console.log(data);return false;
	  	  <?
	  	  }
	  	  ?>
	  	  // console.log(data);return false;

          data = data.split("-");
          rowid= data[0];
          infodata= data[1];
          inforeload= data[2];
          // console.log(inforeload);return false;

          if(rowid == "xxx")
          {
				mbox.alert(infodata, {open_speed: 500}, interval = window.setInterval(function() 
				{
					clearInterval(interval);
					mbox.close();
				}, 1000));
				return false;
          }
          else
          {
	          //$.messager.alert('Info', infodata, 'info');
	          mbox.alert(infodata, {open_speed: 500}, interval = window.setInterval(function() 
	          {
				  clearInterval(interval);
				  mbox.close();
				  //top.setInfoPegawaiData();

				  if(typeof inforeload == "undefined" || inforeload == "")
		          {
		          	document.location.href= "app/loadUrl/app/pegawai_add_e_file_data/?reqId=<?=$reqId?>&reqRowId="+rowid+"&reqUrlFile=<?=$reqUrlFile?>";
		          }
		          else
		          {
		          	document.location.href= "app/loadUrl/app/pegawai_add_e_file_data/?reqId=<?=$reqId?>&reqRowId="+rowid+"&reqUrlFile="+inforeload;
		          }
	          }, 1000));
			  $(".mbox > .right-align").css({"display": "none"});
	      	}
	      }
  });

$("#reqKategoriFileId").change(function(){
	var reqKategoriFileId= "";
	reqKategoriFileId= $("#reqKategoriFileId").val();
	$("#reqJenisDokumen option").remove();
	$("#reqJenisDokumen").material_select();
	
	$("<option value=''></option>").appendTo("#reqJenisDokumen");
	$.ajax({'url': "pegawai_file_json/jenis_dokumen/?reqId=<?=$reqId?>&reqKategoriFileId="+reqKategoriFileId+"&reqCheckImage=<?=$reqCheckImage?>",'success': function(dataJson) {
		var data= JSON.parse(dataJson);
		for(i=0;i<data.arrID.length; i++)
		{
			valId= data.arrID[i]; valNama= data.arrNama[i];
			$("<option value='" + valId + "'>" + valNama + "</option>").appendTo("#reqJenisDokumen");
		}
		$("#reqJenisDokumen").material_select();
	}});
		
	/*$("<option value=''></option>").appendTo("#reqJenisDokumen");
	$("<option value='1'>1asdsa</option>").appendTo("#reqJenisDokumen");
	$("<option value='2' selected>2asdsa</option>").appendTo("#reqJenisDokumen");
	$("#reqJenisDokumen").material_select();*/
	
});

$("#reqJenisDokumen").change(function(){
	setJenisDokumen();
});

});

function setJenisDokumen()
{
	var reqJenisDokumen= reqRiwayatTable= reqRiwayatField= reqRiwayatId= "";
	reqJenisDokumen= $("#reqJenisDokumen").val();
	//alert(reqJenisDokumen);return false;
	reqJenisDokumen= String(reqJenisDokumen);
	reqJenisDokumen= reqJenisDokumen.split(';'); 
	//$tempRiwayatTable.";".$tempRiwayatId.";".$tempRiwayatField.";".$tempRiwayatId;
	reqRiwayatTable= reqJenisDokumen[0];
	if(typeof reqRiwayatTable == "undefined") reqRiwayatTable= "";
	$("#reqRiwayatTable").val(reqRiwayatTable);
	reqRiwayatId= reqJenisDokumen[1];
	if(typeof reqRiwayatId == "undefined") reqRiwayatId= "";
	$("#reqRiwayatId").val(reqRiwayatId);
	reqRiwayatField= reqJenisDokumen[2];
	if(typeof reqRiwayatField == "undefined") reqRiwayatField= "";
	$("#reqRiwayatField").val(reqRiwayatField);
}
//reqRiwayatTable;reqRiwayatField;reqRiwayatId
</script>

<!-- CORE CSS-->    
<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<!-- CSS style Horizontal Nav-->    
<link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
<!-- Custome CSS-->    
<link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

<link rel="stylesheet" type="text/css" href="lib/dropzone/dropzone.css">
<link rel="stylesheet" type="text/css" href="lib/dropzone/basic.css">
<script src="lib/dropzone/dropzone.js"></script>

<link href="lib/mbox/mbox.css" rel="stylesheet">
<script src="lib/mbox/mbox.js"></script>
<link href="lib/mbox/mbox-modif.css" rel="stylesheet">

<style>
	.dropdown-content
	{
		max-height: 200px !important;
	}

	.dropdown-content li
	{
		min-height: 15px !important;
		line-height: 0.1rem !important;
	}
	.dropdown-content li > span
	{
		font-size: 14px;
		line-height: 12px !important;
	}

</style>
</head>

<body>
	<div id="basic-form" class="section">
		<div class="row">
			<div class="col s12 m10 offset-m1">
				<ul class="collection card">
					<li class="collection-item ubah-color-warna" style="color: black;"><?=$reqNamaFileNew?></li>
					<li class="collection-item" style="">
						<form id="ff" method="post" enctype="multipart/form-data" autocomplete="off">
							<div class="row">
								<div class="input-field col s6">
									<label for="reqKategoriFileId" class="active">Kategori Dokumen</label>
									<select <?=$disabled?> name="reqKategoriFileId" id="reqKategoriFileId">
										<option></option>
										<?
										for($index_loop=0; $index_loop < $jumlah_kategori_dokumen; $index_loop++)
										{
											$tempValId= $arrKategoriDokumen[$index_loop]["KATEGORI_FILE_ID"];
											$tempNama= $arrKategoriDokumen[$index_loop]["NAMA"];
											?>
											<option value="<?=$tempValId?>" <? if($reqKategoriFileId == $tempValId) echo "selected"?>><?=$tempNama?></option>
											<?
										}
										?>
									</select>
								</div>
								<div class="input-field col s6">
									<select <?=$disabled?> name="reqJenisDokumen" id="reqJenisDokumen">
										<option></option>
					                    <?
					                    for($index_loop=0; $index_loop < $jumlah_jenis_dokumen; $index_loop++)
					                    {
					                    	$arrJenisDokumen[$index_loop]["NO_URUT"];
					                    	$arrJenisDokumen[$index_loop]["PEGAWAI_ID"];
					                    	$tempRiwayatTable= $arrJenisDokumen[$index_loop]["RIWAYAT_TABLE"];
					                    	$tempRiwayatTableNext= $arrJenisDokumen[$index_loop+1]["RIWAYAT_TABLE"];
					                    	$tempRiwayatField= $arrJenisDokumen[$index_loop]["RIWAYAT_FIELD"];
					                    	$tempRiwayatId= $arrJenisDokumen[$index_loop]["RIWAYAT_ID"];
					                    	$tempInfoData= $arrJenisDokumen[$index_loop]["INFO_DATA"];
					                    	$tempInfoGroupData= $arrJenisDokumen[$index_loop]["INFO_GROUP_DATA"];
					                    	$tempValue= $tempRiwayatTable.";".$tempRiwayatId.";".$tempRiwayatField;
										?>
											<option value="<?=$tempValue?>" <? if($reqJenisDokumen == $tempValue) echo "selected"?>><?=$tempInfoData?></option>
										<?
			                    		}
			                    		?>
	                   				</select>
                  	   				<label for="reqJenisDokumen">Jenis Dokumen</label>
                   				</div>
              				</div>
              				<div class="row">
              					<div class="input-field col s6">
				              		<select <?=$disabled?> name="reqKualitasFileId" id="reqKualitasFileId">
				              			<option></option>
				              			<?
				              			while($kualitas->nextRow())
				              			{
				              				?>
				              				<option value="<?=$kualitas->getField('KUALITAS_FILE_ID')?>" <? if($reqKualitasFileId == $kualitas->getField('KUALITAS_FILE_ID')) echo "selected"?>><?=$kualitas->getField('NAMA')?></option>
				              				<? 
				              			}
				              			?>
				              		</select>
				              		<label for="reqKualitasFileId">Kualitas Dokumen</label>
			              		</div>
			              		<div class="input-field col s6 m6">
			              			<label for="reqKeterangan">Keterangan</label>
			              			<input <?=$disabled?> placeholder="" type="text" id="reqKeterangan" name="reqKeterangan" <?=$read?> value="<?=$reqKeterangan?>" class="easyui-validatebox" />
			              		</div>
              				</div>
							<div class="row">
								<div class="input-field col s12 m12">
									<label for="reqNameFileEdit">Nama File </label>
									<input <?=$disabled?> placeholder="" type="text" id="reqNameFileEdit" name="reqNameFileEdit" <?=$read?> value="<?=$reqLinkFileAsli?>" class="easyui-validatebox" />
									<!-- <label for="reqNameFileaEdit">Nama File </label> -->
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<input <?=$disabled?> type="checkbox" id="reqPrioritas" name="reqPrioritas" value="1" <? if($reqPrioritas == 1) echo 'checked'?> >
									<label class="form-check-label" for="reqPrioritas" >Prioritas</label>
								</div>
							</div>
							<div class="row" style="margin-top: 60px">
								<div class="input-field col s12 m12">
									<button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
										<i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
									</button>

				              		<script type="text/javascript">
				              			$("#kembali").click(function() { 
				              				document.location.href = "app/loadUrl/app/pegawai_add_e_file?reqId=<?=$reqId?>";
				              			});
				              		</script>

				              		<input type="hidden" name="reqRiwayatTable" id="reqRiwayatTable" value="<?=$reqRiwayatTable?>" />
				              		<input type="hidden" name="reqRiwayatField" id="reqRiwayatField" value="<?=$reqRiwayatField?>" />
				              		<input type="hidden" name="reqRiwayatId" id="reqRiwayatId" value="<?=$reqRiwayatId?>" />
				              		<input type="hidden" name="reqUrlFile" value="<?=$reqUrlFile?>" />
				              		<input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
				              		<input type="hidden" name="reqId" value="<?=$reqId?>" />
				              		<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
				              		<?
				              		// A;R;D
				              		if($tempAksesMenu == "A" && empty($disabled))
				              		{
				              			if($infostatus == "1"){}
				              			else
				              			{
				              		?>
				              		<button class="btn waves-effect waves-light green" style="font-size:9pt" type="submit" name="action">Simpan
				              			<i class="mdi-content-save left hide-on-small-only"></i>
				              		</button>
				              		<?
				              			}
				              		?>

				              		<a class="btn blue waves-effect waves-light" style="font-size:9pt"  type="button"  href="<?=$reqLinkFilePath?>" download="<?=$reqNamaFileNew?>" >Download
				              			<i class="mdi-download left hide-on-small-only"></i>
				              		</a>
				              		<?
				              		}
				              		?>
              					</div>
              				</div>
            			</form>
          			</li>
        		</ul>
      		</div>

      		<?
      		if(!empty($infolihatfile))
      		{
      		?>
	      	<div class="col s12 m12">
	      		<ul class="collection card">
	      			<li class="collection-item">
					
	            	<!-- <iframe style="width: 100%; height: 100%;"  src="<?=base_url()?>lib/ViewerJS/#../../<?=$reqUrlFile?>?reqLinkFile=<?=$reqUrlFile?>"></iframe> -->

	            	<?
	            	if(in_array($dotexe, $arrvalid))
	            	{
	            	?>
	            		<iframe style="width: 100%; height: 100%;"  src="<?=base_url()?>lib/pdfjs/web/viewer.html?file=../../../<?=$reqLinkFilePath?>?<?=$reqNamaFileNew?>"></iframe>
	            	<?
	            	}
	            	else
	            	{
	            	?>
	            		<img id="infonewimage" src="<?=$reqLinkFilePath?>?<?=$reqNamaFileNew?>" style="width:inherit; width: 100%; height: 100%" />
	            	<?
	            	}
	            	?>
           
          			</li>
        		</ul>
      		</div>
      		<?
      		}
      		?>

    	</div>
  	</div>

    <!--materialize js-->
    <script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

    <script type="text/javascript">
    	$(document).ready(function() {
    		$('select').material_select();
    	});

    	$('.materialize-textarea').trigger('autoresize');

    	function iframeLoaded() {
    		var iFrameID = document.getElementById('mainFrame');
    		if(iFrameID) {
				// here you can make the height, I delete it first, then I make it again
				iFrameID.height = "";
				iFrameID.style.height=this.contentDocument.body.scrollHeight +'px';
			}   
		}

		$('#reqNameFileEdit').on('input', function() {
			var check = $('#reqNameFileEdit').val();
			var re = /^\w+$/;
			if (!re.test(check)) {
				 replacenew = check.replace(/[_\W]+/g, "_");
				 $('#reqNameFileEdit').val(replacenew);
			}
		});


	</script>

	<style type="text/css">
	  ul.dropdown-content.select-dropdown {
	    /*border: 2px solid red;*/
	    height: 250px !important;
	    overflow: auto !important;
	  }
	</style>

	<style type="text/css">
		 #pdfWrapper {
		     position: relative;
		     height: 0; 
		     overflow: hidden;
		     padding-bottom: 120%;
		 }
		   
		 #pdfWrapper iframe {
		   position: absolute;
		     top: 0;
		     left: 0;
		     width: 100%;
		     height: 100%;
		 }
	</style>
    
    <link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
	<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>