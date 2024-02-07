<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('PangkatRiwayat');
$this->load->model('Pangkat');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");

if($reqRowId=="")
{
	$reqMode = 'insert';
}
else
{
	$reqMode = 'update';
	$statement= " AND A.PANGKAT_RIWAYAT_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
	$set= new PangkatRiwayat();
	$set->selectByParams(array(), -1, -1, $statement);
	$set->firstRow();
	$tempRowId 				= $set->getField('PANGKAT_RIWAYAT_ID');
	$tempGolRuang			= $set->getField('PANGKAT_ID');
	$tempTglSTLUD			= dateToPageCheck($set->getField('TANGGAL_STLUD'));
	$tempSTLUD				= $set->getField('STLUD');
	$tempNoSTLUD			= $set->getField('NO_STLUD');
	$tempNoNota 			= $set->getField('NO_NOTA');
	$tempNoSK 				= $set->getField('NO_SK');
	$tempTh					= $set->getField('MASA_KERJA_TAHUN');
	$tempBl					= $set->getField('MASA_KERJA_BULAN');
	$tempKredit				= $set->getField('KREDIT');
	$tempJenisKP			= $set->getField('JENIS_RIWAYAT');
	$tempJenisKPNama		= $set->getField('NMJENIS');
	$tempKeterangan			= $set->getField('KETERANGAN');
	$tempGajiPokok			= $set->getField('GAJI_POKOK');
	$tempTglNota			= dateToPageCheck($set->getField('TANGGAL_NOTA'));
	$tempTglSK 				= dateToPageCheck($set->getField('TANGGAL_SK'));
	$tempTMTGol 			= dateToPageCheck($set->getField('TMT_PANGKAT'));
	$tempPejabatPenetapId= $set->getField('PEJABAT_PENETAP_ID');
	$tempPejabatPenetap= $set->getField('PEJABAT_PENETAP_NAMA');
	$tempLastProsesUser= $set->getField('LAST_PROSES_USER');
}

$pangkat= new Pangkat();
$pangkat->selectByParams(array());
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Untitled Document</title>
	<base href="<?=base_url()?>" />

	<link rel="stylesheet" type="text/css" href="css/gaya.css">

	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
    <?php /*?><script type="text/javascript" src="lib/easyui/jquery-2.2.4.js"></script>
    <script type="text/javascript" src="lib/easyui/jquery-2.2.4.min.js"></script><?php */?>


    <!-- BOOTSTRAP -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!--<script src="js/jquery-1.10.2.min.js"></script>-->
    <script src="lib/bootstrap/js/jquery.min.js"></script>
    <script src="lib/bootstrap/js/bootstrap.js"></script>
    <link href="lib/bootstrap/css/bootstrap.css" rel="stylesheet">

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
    			url:'pangkat_riwayat_json/add',
    			onSubmit:function(){
    				if($(this).form('validate')){}
    					else
    					{
    						$.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
    						return false;
    					}
    				},
    				success:function(data){
					//alert(data);return false;
					$.messager.alert('Info', data, 'info');

					<?
					if($reqMode == "update")
					{
						?>
						// document.location.reload();
						<?	
					}
					else
					{
						?>
						$('#rst_form').click();
						<?
					}
					?>
					top.frames['mainFrame'].location.reload();
					top.frames['mainFrameDetil'].location.reload();
				}
			});

    		$('input[id^="reqPejabatPenetap"]').autocomplete({
    			source:function(request, response){
    				var id= this.element.attr('id');
    				var replaceAnakId= replaceAnak= urlAjax= "";

    				if (id.indexOf('reqPejabatPenetap') !== -1)
    				{
    					var element= id.split('reqPejabatPenetap');
    					var indexId= "reqPejabatPenetapId"+element[1];
    					urlAjax= "pejabat_penetap_json/combo";
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
    								return {desc: element['desc'], id: element['id'], label: element['label'], statusht: element['statusht']};
    							});
    							response(array);
    						}
    					}
    				})
    			},
    			focus: function (event, ui) 
    			{ 
    				var id= $(this).attr('id');
    				if (id.indexOf('reqPejabatPenetap') !== -1)
    				{
    					var element= id.split('reqPejabatPenetap');
    					var indexId= "reqPejabatPenetapId"+element[1];
    				}

    				var statusht= "";
						//statusht= ui.item.statusht;
						$("#"+indexId).val(ui.item.id).trigger('change');
					},
					//minLength:3,
					autoFocus: true
				}).autocomplete( "instance" )._renderItem = function( ul, item ) {
	return $( "<li>" )
	.append( "<a>" + item.desc + "</a>" )
	.appendTo( ul );
};

});
</script>

<link rel="stylesheet" type="text/css" href="css/bluetabs.css">

<!-- BOOTSTRAP CORE -->
<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<style>
	html, body{
		height:100%;
	}
	@media screen and (max-width:767px) {
		html, body{
			height: auto;
		}
	}
</style>

</head>

<body>
	<div class="container-fluid full-height">
		<div class="row full-height">
			<div class="col-md-12 area-form full-height">

				<div id="area-form-inner">
					<form id="ff" method="post" novalidate enctype="multipart/form-data">
						
						<div class="content" style="height:90%; width:100%;overflow:hidden; overflow:-x:hidden; overflow-y:auto; position:fixed;">
							
							<div class="form-group" >
								<label for="" class="col-md-1" >STLUD</label>
								<div class="col-md-3">
									
									<select <?=$disabled?> name="reqNoDiklatPrajabatan"  class="form-control" style="padding:0">
										<option></option>
										<option value="1" <? if($tempSTLUD == 1) echo 'selected'?>>Tingkat I</option>
										<option value="2" <? if($tempSTLUD == 2) echo 'selected'?>>Tingkat II</option>
										<option value="3" <? if($tempSTLUD == 3) echo 'selected'?>>Tingkat III</option>
									</select>
								</div>
								<label for="" class="col-md-2" >No. STLUD</label>
								<div class="col-md-3">
									<input type="text"   name="reqNoSTLUD" <?=$read?> class="dateIna form-control" value="<?=$tempNoSTLUD?>" />
								</div>
								<label for="" class="col-md-1" >Tgl. STLUD</label>
								<div class="col-md-2">
									<input type="text"   id="reqTglSTLUD" name="reqTglSTLUD" maxlength="10" class="dateIna form-control" onkeydown="return format_date(event,'reqTglSTLUD');" <?=$read?> value="<?=$tempTglSTLUD?>" />
								</div>
							</div>

							<div class="form-group" >
								<label for="" class="col-md-1" >Gol/Ruang</label>
								<div class="col-md-3">
									<select name="reqGolRuang" <?=$disabled?> id="reqGolRuang"  class="form-control" style="padding:0">
										<? 
										while($pangkat->nextRow())
										{
											?>
											<option value="<?=$pangkat->getField('PANGKAT_ID')?>" <? if($tempGolRuang == $pangkat->getField('PANGKAT_ID')) echo 'selected';?>><?=$pangkat->getField('KODE')?></option>
											<? 
										}
										?>
									</select>
								</div>
								<label for="" class="col-md-2" >TMT SK</label>
								<div class="col-md-3">
									<input type="hidden" id="reqTMTGolLama" name="reqTMTGolLama" value="<?=$tempTMTGol?>" />
									<input type="text" class="easyui-validatebox" required  id="reqTMTGol" name="reqTMTGol" maxlength="10" title="Tmt Gol harus diisi" onkeydown="return format_date(event,'reqTMTGol');" <?=$read?> value="<?=$tempTMTGol?>" />
								</div>
							</div>

							<div class="form-group" >
								<label for="" class="col-md-1" >No Nota</label>
								<div class="col-md-3">
									<input type="text"   name="reqNoNota" <?=$read?> value="<?=$tempNoNota?>" class="easyui-validatebox form-control" />
								</div>
								<label for="" class="col-md-2" >Tgl Nota</label>
								<div class="col-md-3">
									<input type="text"   id="reqTglNota" name="reqTglNota" class="dateIna form-control" maxlength="10" onkeydown="return format_date(event,'reqTglNota');" <?=$read?> value="<?=$tempTglNota?>" />
								</div>
							</div>

							<div class="form-group" >
								<label for="" class="col-md-1" >No SK</label>
								<div class="col-md-3">
									<input type="text"   name="reqNoSK" <?=$read?> value="<?=$tempNoSK?>" title="No SK harus diisi" class="form-control" />
								</div>
								<label for="" class="col-md-2" >Tgl SK</label>
								<div class="col-md-3">
									<input type="hidden" id="reqTglSKLama" name="reqTglSKLama" value="<?=$tempTglSK?>" />
									<input type="text"   id="reqTglSK" name="reqTglSK" <?php /*?>title="Tanggal SK harus diisi"<?php */?> class="dateIna form-control" maxlength="10" onkeydown="return format_date(event,'reqTglSK');" <?=$read?> value="<?=$tempTglSK?>" />
								</div>
							</div>

							<div class="form-group" >
								<label for="" class="col-md-1" >Pj Penetap</label>
								<div class="col-md-3">
									<input type="hidden" name="reqPejabatPenetapId" id="reqPejabatPenetapId" value="<?=$tempPejabatPenetapId?>" /> 
									<input type="text"   name="reqPejabatPenetap" <?=$read?> id="reqPejabatPenetap" value="<?=$tempPejabatPenetap?>" title="Pejabat Penetap harus diisi" class="autocompletevalidator form-control" />
								</div>
								<label for="" class="col-md-2" >Jenis KP</label>
								<div class="col-md-3">
									<select <?=$disabled?> name="reqJenisKP"  class="form-control" style="padding:0">
										<option value="1" <? if($tempJenisKP == 1) echo 'selected'?>>CPNS</option>
										<option value="2" <? if($tempJenisKP == 2) echo 'selected'?>>PNS</option>
										<option value="3" <? if($tempJenisKP == 3) echo 'selected'?>>Reguler</option>
									</select>
								</div>
							</div>

							<div class="form-group" >
								<label for="" class="col-md-1" >Kredit</label>
								<div class="col-md-3">
									<input type="text"   id="reqKredit" name="reqKredit" <?=$read?> class="form-control" value="<?=$tempKredit?>"/>
								</div>
								<label for="" class="col-md-2" >Masa Kerja</label>

								<div class="col-md-3">
									<div class="col-md-6">
										<input type="text" placeholder="Tahun" style="padding:2px" name="reqTh" <?=$read?> value="<?=$tempTh?>" id="reqTh" title="Masa kerja tahun harus diisi" class="form-control"/>
									</div>
									<div class="col-md-6">
										<input type="text" placeholder="Bulan" style="padding:2px" name="reqBl" <?=$read?> value="<?=$tempBl?>" id="reqBl" title="Masa kerja bulan diisi" class="form-control"/>
									</div>
								</div>
								
								<label for="" class="col-md-1" >Gaji Pokok</label>
								<div class="col-md-2">
									<input type="text"   name="reqGajiPokok" <?=$read?> value="<?=$tempGajiPokok?>" id="reqGajiPokok" class="form-control"/>
								</div>
							</div>

							<div class="form-group" >
								<label for="" class="col-md-1" >Keterangan</label>
								<div class="col-md-3">
									<textarea <?=$disabled?> name="reqKeterangan" class="required form-control"> <?=$tempKeterangan?></textarea>
								</div>
							</div>


							<input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
							<input type="hidden" name="reqId" value="<?=$reqId?>" />
							<input type="hidden" name="reqMode" value="<?=$reqMode?>" />

							<div class="col-md-1">
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