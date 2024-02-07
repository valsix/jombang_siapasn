<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

// $this->load->model('PendidikanRiwayat');
$this->load->model('Pendidikan');
// $this->load->model('JurusanPendidikan');

$pendidikan = new Pendidikan();
$pendidikan->selectByParams();

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");

if($reqRowId=="")
{
	$reqMode = 'insert';
}
else
{
	// $reqMode = 'update';
	// $statement= " AND A.PANGKAT_RIWAYAT_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
	// $set= new PangkatRiwayat();
	// $set->selectByParams(array(), -1, -1, $statement);
	// $set->firstRow();
	// $tempRowId 				= $set->getField('PANGKAT_RIWAYAT_ID');
	// $tempGolRuang			= $set->getField('PANGKAT_ID');
	// $tempTglSTLUD			= dateToPageCheck($set->getField('TANGGAL_STLUD'));
	// $tempSTLUD				= $set->getField('STLUD');
	// $tempNoSTLUD			= $set->getField('NO_STLUD');
	// $tempNoNota 			= $set->getField('NO_NOTA');
	// $tempNoSK 				= $set->getField('NO_SK');
	// $tempTh					= $set->getField('MASA_KERJA_TAHUN');
	// $tempBl					= $set->getField('MASA_KERJA_BULAN');
	// $tempKredit				= $set->getField('KREDIT');
	// $tempJenisKP			= $set->getField('JENIS_RIWAYAT');
	// $tempJenisKPNama		= $set->getField('NMJENIS');
	// $tempKeterangan			= $set->getField('KETERANGAN');
	// $tempGajiPokok			= $set->getField('GAJI_POKOK');
	// $tempTglNota			= dateToPageCheck($set->getField('TANGGAL_NOTA'));
	// $tempTglSK 				= dateToPageCheck($set->getField('TANGGAL_SK'));
	// $tempTMTGol 			= dateToPageCheck($set->getField('TMT_PANGKAT'));
	// $tempPejabatPenetapId= $set->getField('PEJABAT_PENETAP_ID');
	// $tempPejabatPenetap= $set->getField('PEJABAT_PENETAP_NAMA');
	// $tempLastProsesUser= $set->getField('LAST_PROSES_USER');
}

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
						<!-- <div class="content" style="height:90%; width:100%;overflow:hidden; overflow:-x:hidden; overflow-y:auto; position:fixed;"> -->

						<input type="hidden" name="reqPegawaiId" value="<?=$tempPegawaiId?>">
						<div class="form-group" >
							<div class="row">
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-6">
											<label>Pendidikan</label>
											<select name="reqPendidikan" id="reqPendidikan" <?=$disabled?> style="padding:0" class="form-control">
												<? 
												$pendidikan->selectByParams(array(), -1,-1, " AND PENDIDIKAN_ID > 0 ");
												while($pendidikan->nextRow()){
													?>
													<option value="<?=$pendidikan->getField('PENDIDIKAN_ID')?>" <? if($tempPendidikan == $pendidikan->getField('PENDIDIKAN_ID')) echo 'selected';?>><?=$pendidikan->getField('NAMA')?></option>
													<? 
												}?>
											</select>
										</div>
										<div class="col-md-6">
											<label>Status Pendidikan</label>
											<select name="reqStatusPendidikan" id="reqStatusPendidikan" <?=$disabled?> class="form-control" style="padding:0">
												<option value="1" <? if($tempStatusPendidikan == 1) echo 'selected'?>>Pendidikan CPNS</option>
												<option value="2" <? if($tempStatusPendidikan == 2) echo 'selected'?>>Diakui</option>
												<option value="3" <? if($tempStatusPendidikan == 3) echo 'selected'?>>Belum Diakui</option>
												<option value="4" <? if($tempStatusPendidikan == 4) echo 'selected'?>>Riwayat</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<label>Jurusan</label>
									<input type="hidden" name="reqJurusanId" id="reqJurusanId" value="<?=$tempJurusanId?>" /> 
									<input type="text"   name="reqJurusan" id="reqJurusan" value="<?=$tempJurusan?>" title="Jurusan harus diisi" class="autocompletevalidator form-control" />
								</div>
							</div>
						</div>

						<div class="form-group" >
							<div class="row">
								<div class="col-md-6">
									<label>Tipe Gelar</label>
									<select name="reqGelarTipe" id="reqGelarTipe" <?=$disabled?> class="form-control" style="padding:0">
										<option value="" <? if($tempGelarTipe == "") echo 'selected';?>>Tanpa gelar</option>
										<option value="1" <? if($tempGelarTipe == "1") echo 'selected';?>>Depan</option>
										<option value="2" <? if($tempGelarTipe == "2") echo 'selected';?>>Belakang</option>
									</select>
								</div>
								<div class="col-md-6">
									<label>Gelar</label>
									<input type="text" name="reqGelarNama" id="reqGelarNama" <?=$read?> value="<?=$tempGelarNama?>" class="form-control"/>
								</div>
							</div>
						</div>

						<div class="form-group" >
							<div class="row">
								<div class="col-md-6">
									<label>Nama Sekolah</label>
									<input type="text"   name="reqNamaSekolah" <?=$read?> value="<?=$tempNamaSekolah?>" title="Nama sekolah harus diisi" class="required form-control" />
								</div>
								<div class="col-md-6">
									<label>Kepala Sekolah</label>
									<input type="text"   name="reqKepalaSekolah" <?=$read?> value="<?=$tempKepalaSekolah?>" class="required form-control" />
								</div>
							</div>
						</div>

						<div class="form-group" >
							<div class="row">
								<div class="col-md-6">
									<label>No. STTB</label>
									<input type="text"   name="reqNoSTTB" <?=$read?> value="<?=$tempNoSTTB?>" class="required form-control" />
								</div>
								<div class="col-md-6">
									<label>Tgl. STTB</label>
									<input type="text"   name="reqTglSTTB" id="reqTglSTTB" required maxlength="10" class="dateIna form-control" onkeydown="return format_date(event,'reqTglSTTB');" <?=$read?> value="<?=$tempTglSTTB?>" />
								</div>
							</div>
						</div>

						<div class="form-group" >
							<div class="row">
								<div class="col-md-6">
									<label>No. Surat Ijin / Tugas Belajar</label>
									<input type="text"   name="reqNoSuratIjin" <?=$read?> value="<?=$tempNoSuratIjin?>" class="form-control" />
								</div>
								<div class="col-md-6">
									<label>Tgl. Surat Ijin / Tugas Belajar</label>
									<input type="text"   name="reqTglSuratIjin" id="reqTglSuratIjin" maxlength="10" class="dateIna form-control" onkeydown="return format_date(event,'reqTglSuratIjin');" <?=$read?> value="<?=$tempTglSuratIjin?>" />
								</div>
							</div>
						</div>

						<div class="form-group" >
							<div class="row">
								<div class="col-md-6">
									<label>Tempat Sekolah</label>
									<textarea name="reqAlamatSekolah" style="padding:0" class="required form-control"><?=$tempAlamatSekolah?></textarea>
								</div>
							</div>
						</div>

						<input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
						<input type="hidden" name="reqId" value="<?=$reqId?>" />
						<input type="hidden" name="reqMode" value="<?=$reqMode?>" />

						<div class="form-group" >
							<div class="row">
								<div class="col-md-6">
									<input type="submit" name="reqSubmit"  class="btn btn-primary" value="submit" />
								</div>
							</div>
						</div>
						
						<!-- </div> -->
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>