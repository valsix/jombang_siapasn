<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// include_once("lib/pdfinfo-master/src/Howtomakeaturn/PDFInfo/PDFInfo.php");

$this->load->model('JenisDokumen');
$this->load->model('KualitasFile');
$this->load->model('PegawaiFile');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqUrlFile= $this->input->get("reqUrlFile");
$reqMode= $this->input->get("reqMode");

if($reqRowId=="")
{
	$reqMode = 'insert';
}
else
{
	$reqMode = 'update';
	$statement= " AND A.PEGAWAI_FILE_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
	$set= new PegawaiFile();
	$set->selectByParams(array(), -1, -1, $statement);
    //echo $set->query;exit;
	$set->firstRow();
	$reqKualitasFileId= $set->getField("FILE_KUALITAS_ID");
	$reqJenisDokumen= $set->getField("RIWAYAT_TABLE").";".$set->getField("RIWAYAT_ID").";".$set->getField("RIWAYAT_FIELD");
}

$arrJenisDokumen="";
$index_data= 0;
$statement= " AND A.PEGAWAI_ID = ".$reqId;
$set_detil= new PegawaiFile();
$set_detil->selectByParamsJenisDokumen(array(), -1,-1, $statement);
while($set_detil->nextRow())
{
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

$kualitas= new KualitasFile();
$kualitas->selectByParams(array());

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
	  //parent.iframeLoaded();
	  $('#ff').form({
	  	url:'pegawai_file_json/setting',
	  	onSubmit:function(){
	  		var reqRiwayatTable= reqKualitasFileId= "";
	  		reqRiwayatTable= $("#reqRiwayatTable").val();
	  		reqKualitasFileId= $("#reqKualitasFileId").val();

	  		if(reqRiwayatTable == "")
	  		{
	  			mbox.alert("Isikan Jenis dokumen terlebih dahulu", {open_speed: 500}, window.setInterval(function() 
	  			{
	  				mbox.close();
	  			}, 1000));
	  			return false;
	  		}

	  		if(reqKualitasFileId == "")
	  		{
	  			mbox.alert("Isikan Kualitas dokumen terlebih dahulu", {open_speed: 500}, window.setInterval(function() 
	  			{
	  				mbox.close();
	  			}, 1000));
	  			return false;
	  		}

	  		if($(this).form('validate')){}
	  			else
	  			{
	  				$.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
	  				return false;
	  			}
	  		},
	  		success:function(data){
          //alert(data);return false;
          data = data.split("-");
          rowid= data[0];
          infodata= data[1];
          //$.messager.alert('Info', infodata, 'info');
          mbox.alert(infodata, {open_speed: 500}, window.setInterval(function() 
          {
          	mbox.close();
          	document.location.href= "app/loadUrl/app/pegawai_add_e_file_data/?reqId=<?=$reqId?>&reqRowId="+rowid+"&reqUrlFile=<?=$reqUrlFile?>";
          }, 1000));

      }
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

</head>

<body>
	<div id="basic-form" class="section">
		<div class="row">
			<div class="col s12 m10 offset-m1">
				<ul class="collection card">
					<li class="collection-item ubah-color-warna">NAMA FILE PDF</li>
					<li class="collection-item">
						<form id="ff" method="post" enctype="multipart/form-data">
							<div class="row">
								<div class="input-field col s12">
									<select <?=$disabled?> name="reqJenisDokumen" id="reqJenisDokumen">
                                    	<?php /*?><option value="1" style="padding-left: 30px;">ss</option>
                                    	<optgroup label="kitties" style="padding-left: 20px;">
                                            <option value="1" style="padding-left: 30px;">Fluffykins</option>
                                            <option value="2" style="padding-left: 30px;">Mr Pooky</option>
                                        </optgroup><?php */?>
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
                                        	if($tempRiwayatId == "")
                                        	{
                                        		?>
                                        		<option value="<?=$tempValue?>" <? if($reqJenisDokumen == $tempValue) echo "selected"?>><?=$tempInfoData?></option>
                                        		<?
                                        	}
                                        	else
                                        	{
                                        		if($tempKondisiRiwayatTable == $tempRiwayatTable)
                                        		{
                                        			?>
                                        			<option value="<?=$tempValue?>" <? if($reqJenisDokumen == $tempValue) echo "selected"?> style="padding-left: 30px;"><?=$tempInfoData?></option>
                                        			<?
                                        			if($tempRiwayatTableNext == $tempRiwayatTable){}
                                        			{
                                        				?>
                                        			</optgroup>
                                        			<?
                                        		}
                                        	}
                                        	else
                                        	{
                                        		?>
                                        		<optgroup label="<?=$tempInfoGroupData?>" style="padding-left: 20px;">
                                        			<option value="<?=$tempValue?>" <? if($reqJenisDokumen == $tempValue) echo "selected"?> style="padding-left: 30px;"><?=$tempInfoData?></option>
                                        			<?
                                        		}
                                        		$tempKondisiRiwayatTable= $tempRiwayatTable;
                                        	}
                                        }
                                        ?>
                                    </select>
                                    <label for="reqJenisDokumen">Jenis Dokumen</label>
                                </div>
                            </div>
                            <div class="row">
                            	<div class="input-field col s12">
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
                            </div>
                            <div class="row">
                            	<div class="input-field col s12">
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
                            		<button class="btn waves-effect waves-light green" style="font-size:9pt" type="submit" name="action">Simpan
                            			<i class="mdi-content-save left hide-on-small-only"></i>
                            		</button>
                            	</div>
                            </div>
                            <div class="row">
                            	<div class="input-field col s12" style="min-height:1000px;">
                            		<?php

                            		$im = new Imagick();
                            		$im->pingImage('name_of_pdf_file.pdf');
                            		echo $im->getNumberImages();
                            		// $pdf = new PDFInfo(base_url().$reqUrlFile);

                            		// echo $pdf->title;
                            		// echo '<hr />';
                            		// echo $pdf->author;
                            		// echo '<hr />';
                            		// echo $pdf->creator;
                            		// echo '<hr />';
                            		// echo $pdf->producer;
                            		// echo '<hr />';
                            		// echo $pdf->creationDate;
                            		// echo '<hr />';
                            		// echo $pdf->modDate;
                            		// echo '<hr />';
                            		// echo $pdf->tagged;
                            		// echo '<hr />';
                            		// echo $pdf->form;
                            		// echo '<hr />';
                            		// echo $pdf->pages;
                            		// echo '<hr />';
                            		// echo $pdf->encrypted;
                            		// echo '<hr />';
                            		// echo $pdf->pageSize;
                            		// echo '<hr />';
                            		// echo $pdf->fileSize;
                            		// echo '<hr />';
                            		// echo $pdf->optimized;
                            		// echo '<hr />';
                            		// echo $pdf->PDFVersion;
                            		// echo '<hr />';

                            		?>

                            		<iframe src="<?=$reqUrlFile?>" id="mainFrame" onload="iframeLoaded()" style="top: 0; left: 0; width: 100%; height: 100%; margin: 0; padding: 0; border: none;" scrolling="no"></iframe>
                            	</div>
                            </div>
                        </form>
                    </li>
                </ul>
            </div>
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
				iFrameID.height = iFrameID.contentWindow.document.body.scrollHeight + "px";
			}   
		}

	</script>
</body>
</html>