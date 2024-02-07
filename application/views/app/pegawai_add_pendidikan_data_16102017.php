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

<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<link type="text/css" rel="stylesheet" href="lib/materialize/material-icons-regular/material.css"/>
<link type="text/css" rel="stylesheet" href="lib/materialize/materialize.min.css"  media="screen,projection"/>
<script type="text/javascript" src="lib/materialize/materialize.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/material-font.css"/>

<style>
	html, body{
		height:100%;
	}
	@media screen and (max-width:767px) {
		html, body{
			height: auto;
		}
	}

	.rounded-bg {
		border-radius: 8px;
	}

	.table-bg {
		padding: 2vw;
	}

	.material-font {
		font-family: Roboto;
		font-weight: 300;
	}

	.material-bold {
		font-family: Roboto;
		font-weight: 500;
	}
</style>

</head>

<body>
	<div class="container-fluid full-height">
		<div class="row full-height">
			<div class="table-bg">

				<div class="card-panel rounded-bg white material-font ">

					<form id="ff" method="post" novalidate enctype="multipart/form-data">
						<!-- <div class="content" style="height:90%; width:100%;overflow:hidden; overflow:-x:hidden; overflow-y:auto; position:fixed;"> -->

						<input type="hidden" name="reqPegawaiId" value="<?=$tempPegawaiId?>">

						<div class="row">
							<div class="input-field col s12 m3">
								<select name="reqPendidikan" id="reqPendidikan" <?=$disabled?>>
									<? 
									$pendidikan->selectByParams(array(), -1,-1, " AND PENDIDIKAN_ID > 0 ");
									while($pendidikan->nextRow()){
										?>
										<option value="<?=$pendidikan->getField('PENDIDIKAN_ID')?>" <? if($tempPendidikan == $pendidikan->getField('PENDIDIKAN_ID')) echo 'selected';?>><?=$pendidikan->getField('NAMA')?></option>
										<? 
									}?>
								</select>
								<label for="reqPendidikan">Pendidikan</label>
							</div>
							<div class="input-field col s12 m3">
								<select name="reqStatusPendidikan" id="reqStatusPendidikan" <?=$disabled?>>
									<option value="1" <? if($tempStatusPendidikan == 1) echo 'selected'?>>Pendidikan CPNS</option>
									<option value="2" <? if($tempStatusPendidikan == 2) echo 'selected'?>>Diakui</option>
									<option value="3" <? if($tempStatusPendidikan == 3) echo 'selected'?>>Belum Diakui</option>
									<option value="4" <? if($tempStatusPendidikan == 4) echo 'selected'?>>Riwayat</option>
								</select>
								<label for="reqStatusPendidikan">Status Pendidikan</label>
							</div>
							<div class="input-field col s12 m6">
								<label for="reqJurusan">Jurusan</label>
								<input type="hidden" name="reqJurusanId" id="reqJurusanId" value="<?=$tempJurusanId?>" /> 
								<input type="text"   name="reqJurusan" id="reqJurusan" value="<?=$tempJurusan?>" title="Jurusan harus diisi" class="autocompletevalidator" />
							</div>
						</div>		

						<div class="row">
							<div class="input-field col s12 m6">
								<select name="reqGelarTipe" id="reqGelarTipe" <?=$disabled?>>
									<option value="" <? if($tempGelarTipe == "") echo 'selected';?>>Tanpa gelar</option>
									<option value="1" <? if($tempGelarTipe == "1") echo 'selected';?>>Depan</option>
									<option value="2" <? if($tempGelarTipe == "2") echo 'selected';?>>Belakang</option>
								</select>
								<label for="reqGelarTipe">Tipe Gelar</label>
							</div>
							<div class="input-field col s12 m6">
								<label for="reqGelarNama">Gelar</label>
								<input type="text" name="reqGelarNama" id="reqGelarNama" <?=$read?> value="<?=$tempGelarNama?>"/>
							</div>
						</div>		

						<div class="row">
							<div class="input-field col s12 m6">
								<label for="reqNamaSekolah">Nama Sekolah</label>
								<input type="text" id="reqNamaSekolah"  name="reqNamaSekolah" <?=$read?> value="<?=$tempNamaSekolah?>" title="Nama sekolah harus diisi" class="required" />
							</div>
							<div class="input-field col s12 m6">
								<label for="reqKepalaSekolah">Kepala Sekolah</label>
								<input type="text" id="reqKepalaSekolah"  name="reqKepalaSekolah" <?=$read?> value="<?=$tempKepalaSekolah?>" class="required" />
							</div>
						</div>		

						<div class="row">
							<div class="input-field col s12 m6">
								<label for="reqNoSTTB">No. STTB</label>
								<input type="text"  id="reqNoSTTB" name="reqNoSTTB" <?=$read?> value="<?=$tempNoSTTB?>" class="required" />
							</div>
							<div class="input-field col s12 m6">
								<label for="reqTglSTTB">Tgl. STTB</label>
								<input type="text"   name="reqTglSTTB" id="reqTglSTTB" required maxlength="10" class="dateIna" onkeydown="return format_date(event,'reqTglSTTB');" <?=$read?> value="<?=$tempTglSTTB?>" />
							</div>
						</div>		

						<div class="row">
							<div class="input-field col s12 m6">
								<label for="reqNoSuratIjin">No. Surat Ijin / Tugas Belajar</label>
								<input type="text"  id="reqNoSuratIjin" name="reqNoSuratIjin" <?=$read?> value="<?=$tempNoSuratIjin?>"  />
							</div>
							<div class="input-field col s12 m6">
								<label for="reqTglSuratIjin">Tgl. Surat Ijin / Tugas Belajar</label>
								<input type="text"   name="reqTglSuratIjin" id="reqTglSuratIjin" maxlength="10" class="dateIna" onkeydown="return format_date(event,'reqTglSuratIjin');" <?=$read?> value="<?=$tempTglSuratIjin?>" />
							</div>
						</div>		

						<div class="row">
							<div class="input-field col s12 m6">
								<textarea name="reqAlamatSekolah" id="reqAlamatSekolah" class="required materialize-textarea"><?=$tempAlamatSekolah?></textarea>
								<label for="reqAlamatSekolah">Tempat Sekolah</label>
							</div>
						</div>	


						<input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
						<input type="hidden" name="reqId" value="<?=$reqId?>" />
						<input type="hidden" name="reqMode" value="<?=$reqMode?>" />

						<div class="row">
							<div class="input-field col s12 m4">
								<input type="submit" name="reqSubmit"  class="btn btn-primary" value="submit" />
							</div>
						</div>	
						
						<!-- </div> -->
					</form>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function() {
			$('select').material_select();
		});

		$('.materialize-textarea').trigger('autoresize');
	</script>
</body>
</html>