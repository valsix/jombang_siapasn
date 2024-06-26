<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('GajiRiwayat');
$this->load->model('Pangkat');
$this->load->model('PejabatPenetap');

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
	$statement= " AND A.GAJI_RIWAYAT_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
	$set= new GajiRiwayat();
	$set->selectByParams(array(), -1, -1, $statement);
	 //echo $set->query;exit;
	$set->firstRow();
	$tempRowId 				= $set->getField('PANGKAT_RIWAYAT_ID');
	$tempNoSK	= $set->getField('NO_SK');
	$tempGolRuang= $set->getField('PANGKAT_ID');
	$tempGolRuangNama= $set->getField('NMPANGKAT');
	$tempTglSK = dateToPageCheck($set->getField('TANGGAL_SK'));
	$tempGajiPokok	= $set->getField('GAJI_POKOK');
	$tempTh	= $set->getField('MASA_KERJA_TAHUN');
	$tempBl	= $set->getField('MASA_KERJA_BULAN');
	$tempJenis= $set->getField('JENIS_KENAIKAN');
	$tempJenisNama= $set->getField('NMJENISKENAIKAN');
	$tempLastProsesUser= $set->getField('LAST_PROSES_USER');
	$tempTMTSK= dateToPageCheck($set->getField('TMT_SK'));
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
    			url:'gaji_riwayat_json/add',
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
						<!--<div class="content" style="height:90%; width:100%;overflow:hidden; overflow:-x:hidden; overflow-y:auto; position:fixed;">-->
                        <div class="content">
							
							<div class="form-group" >
								<input type="hidden" name="reqPegawaiId" value="<?=$tempPegawaiId?>">
								<label for="" class="col-md-2" >No. SK</label>
								<div class="col-md-4">
									<input type="text"   name="reqNoSK" <?=$read?> value="<?=$tempNoSK?>" title="No SK harus diisi" class="required form-control" />
								</div>
								<label for="" class="col-md-2" >Tgl. SK</label>
								<div class="col-md-4">
									<input type="hidden" id="reqTglSKLama" name="reqTglSKLama" value="<?=$tempTglSK?>" />
									<input type="text"   id="reqTglSK" name="reqTglSK" title="Tanggal SK harus diisi" class="required dateIna form-control" maxlength="10" onkeydown="return format_date(event,'reqTglSK');" <?=$read?> value="<?=$tempTglSK?>" />

								</div>
							</div>

							<div class="form-group" >
								<label for="" class="col-md-2" >Gol/Ruang</label>
								<div class="col-md-4">
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
								<div class="col-md-4">
									<input type="hidden" id="reqTMTSKLama" name="reqTMTSKLama" value="<?=$tempTMTSK?>" />
									<input type="text"   id="reqTMTSK" name="reqTMTSK" maxlength="10" class="required dateIna form-control" onkeydown="return format_date(event,'reqTMTSK');" <?=$read?> value="<?=$tempTMTSK?>" title="Tmt Sk harus diisi" />

								</div>
							</div>

							<div class="form-group" >
								<label for="" class="col-md-2" >Pejabat Penetap</label>
								<div class="col-md-4">
									<input type="hidden" name="reqPejabatPenetapId" id="reqPejabatPenetapId" value="<?=$tempPejabatPenetapId?>" /> 
									<input type="text" required   name="reqPejabatPenetap" <?=$disabled?> id="reqPejabatPenetap" value="<?=$tempPejabatPenetap?>" title="Pejabat Penetap harus diisi" class="form-control autocompletevalidator" />

								</div>
							</div>


							<div class="form-group" >
								<label for="" class="col-md-2" >Masa Kerja</label>
								<div class="col-md-1">
									<input type="text" name="reqTh" <?=$read?> value="<?=$tempTh?>" id="reqTh" title="Masa kerja tahun harus diisi" class="required form-control"/>
								</div>
								<div class="col-md-1">
									Tahun
								</div>
								<div class="col-md-1">
									<input type="text" name="reqBl" <?=$read?> value="<?=$tempBl?>" id="reqBl" title="Masa kerja bulan diisi" class="required form-control"/>

								</div>
								<div class="col-md-1">
									Bulan
								</div>
							</div>

							<div class="form-group" >
								<label for="" class="col-md-2" >Jenis</label>
								<div class="col-md-4">
									<select <?=$disabled?> name="reqJenis" class="form-control" style="padding:0">
										<option value="1" <? if($tempJenis == 1) echo 'selected'?>>CPNS</option>
										<option value="2" <? if($tempJenis == 2) echo 'selected'?>>PNS</option>
										<option value="3" <? if($tempJenis == 3) echo 'selected'?>>Kenaikan Pangkat</option>
										<option value="4" <? if($tempJenis == 4) echo 'selected'?>>Gaji Berkala</option>
									</select>
								</div>
							</div>

							
							<input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
							<input type="hidden" name="reqId" value="<?=$reqId?>" />
							<input type="hidden" name="reqMode" value="<?=$reqMode?>" />

							<div class="col-md-2">
								<input type="submit" name="reqSubmit"  class="btn btn-primary" value="submit" />
							</div>
						</div>
					</form>

				</div>
			</div>
		</div>
	</div>
</body>
</html>