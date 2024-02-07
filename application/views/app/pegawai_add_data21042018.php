<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('Pegawai');
$this->load->model('Bank');
$this->load->model('Agama');
$this->load->model('SatuanKerja');
$this->load->model('JenisPegawai');

$tempLoginLevel= $this->LOGIN_LEVEL;

$set= new Pegawai();
$setBank= new Bank();
$setAgama= new Agama();
$setSatuanKerja= new SatuanKerja();
$setJenisPegawai= new JenisPegawai();

$setBank->selectByParams();
$setAgama->selectByParams();
$setSatuanKerja->selectByParams();
$setJenisPegawai->selectByParams();

$reqId = $this->input->get("reqId");

if($reqId == ""){
	$reqMode = "insert";
	$reqSatuanKerjaId= -1;
}
else
{
	$reqMode = "update";	
	$set->selectByParams(array("A.PEGAWAI_ID"=>$reqId));
	//echo $set->query;exit;
	$set->firstRow();
	$reqStatus= $set->getField("STATUS");
	$reqSatuanKerjaNamaDetil= $set->getField("SATUAN_KERJA_NAMA_DETIL");
	$reqSatuanKerjaId= $set->getField("SATUAN_KERJA_ID");
	$reqJenisPegawai= $set->getField("JENIS_PEGAWAI_ID");
	$reqTipePegawai= $set->getField("TIPE_PEGAWAI_ID");
	$reqStatusPegawai= $set->getField("STATUS_PEGAWAI");
	$reqPegawaiStatusNama= $set->getField("PEGAWAI_STATUS_NAMA");
	$reqPegawaiKedudukanTmt= dateToPageCheck($set->getField("PEGAWAI_KEDUDUKAN_TMT"));
	$reqPegawaiKedudukanNama= $set->getField("PEGAWAI_KEDUDUKAN_NAMA");
	$reqNipLama= $set->getField("NIP_LAMA");
	$reqNipBaru= $set->getField("NIP_BARU");
	$reqNama= $set->getField("NAMA");
	$reqGelarDepan= $set->getField("GELAR_DEPAN");
	$reqGelarBelakang= $set->getField("GELAR_BELAKANG");
	$reqTempatLahir= $set->getField("TEMPAT_LAHIR");
	$reqTanggalLahir= $set->getField("TANGGAL_LAHIR");
	$reqJenisKelamin= $set->getField("JENIS_KELAMIN");
	$reqStatusKawin= $set->getField("STATUS_KAWIN");
	$reqSukuBangsa= $set->getField("SUKU_BANGSA");
	$reqGolonganDarah= $set->getField("GOLONGAN_DARAH");
	$reqEmail= $set->getField("EMAIL");
	$reqAlamat= $set->getField("ALAMAT");
	$reqRt= $set->getField("RT");
	$reqRw= $set->getField("RW");
	$reqKodePos= $set->getField("KODEPOS");
	$reqTelepon= $set->getField("TELEPON");
	$reqHp= $set->getField("HP");
	$reqKartuPegawai= $set->getField("KARTU_PEGAWAI");
	$reqAskes= $set->getField("ASKES");
	$reqTaspen= $set->getField("TASPEN");
	$reqNpwp= $set->getField("NPWP");
	$reqNik= $set->getField("NIK");
	$reqNoRekening= $set->getField("NO_REKENING");
	$reqSkKonversiNip= $set->getField("SK_KONVERSI_NIP");
	$reqBank= $set->getField("BANK_ID");
	$reqAgama= $set->getField("AGAMA_ID");
	$reqUrut= $set->getField("NO_URUT");
	$reqNoKk= $set->getField("NO_KK");
	$reqNoRakBerkas= $set->getField("NO_RAK_BERKAS");
	$reqTeleponKantor= $set->getField("TELEPON_KANTOR");
	$reqFacebook= $set->getField("FACEBOOK");
	$reqTwitter= $set->getField("TWITTER");
	$reqWhatsApp= $set->getField("WHATSAPP");
	$reqTelegram= $set->getField("TELEGRAM");
	$reqKeterangan1= $set->getField("KETERANGAN_1");
	$reqKeterangan2= $set->getField("KETERANGAN_2");

	$reqPropinsiId= $set->getField("PROPINSI_ID");
	$reqPropinsi= $set->getField("PROPINSI_NAMA");
	$reqKabupatenId= $set->getField("KABUPATEN_ID");
	$reqKabupaten= $set->getField("KABUPATEN_NAMA");
	$reqKecamatanId= $set->getField("KECAMATAN_ID");
	$reqKecamatan= $set->getField("KECAMATAN_NAMA");
	$reqDesaId= $set->getField("DESA_ID");
	$reqDesa= $set->getField("DESA_NAMA");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="msapplication-tap-highlight" content="no">
	<meta name="description" content="Simpeg Jombang">
	<meta name="keywords" content="Simpeg Jombang">
	<title>Simpeg Jombang</title>
	<base href="<?=base_url()?>" />

	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
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
				url:'pegawai_json/add',
				onSubmit:function(){
					if($(this).form('validate')){}
					else
					{
						$.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
						return false;
					}

					var reqNipBaru= "";
					reqNipBaru= $("#reqNipBaru").val();
					if(reqNipBaru == "")
					{
						mbox.alert("Lengkapi data terlebih dahulu", {open_speed: 0});
						return false;
					}
					//return $(this).form('validate');
				},
				success:function(data){
				  // alert(data);return false;
				  data = data.split("-");
				  rowid= data[0];
				  infodata= data[1];
				  //$.messager.alert('Info', infodata, 'info');
					/*data = data.split("-");
					rowid= data[0];
					infodata= data[1];
					$.messager.alert('Info', infodata, 'info');
					parent.reloadparenttab();
					*/
					
					mbox.alert(infodata, {open_speed: 500}, interval = window.setInterval(function() 
					{
					  clearInterval(interval);
					  mbox.close();
					<?
					//if($reqId == "")
					//{
					?>
						top.location.href= "app/loadUrl/app/pegawai_add/?reqId="+rowid;
					<?
					//}
					//else
					//{
					?>
						//document.location.href= "app/loadUrl/app/pegawai_add_data/?reqId="+rowid;
					<?
					//}
					?>
					}, 1000));
					$(".mbox > .right-align").css({"display": "none"});
				}
			});
			

			$('input[id^="reqPropinsi"], input[id^="reqKabupaten"], input[id^="reqKecamatan"], input[id^="reqDesa"]').each(function(){
				$(this).autocomplete({
					source:function(request, response){
						var id= this.element.attr('id');
						var replaceAnakId= replaceAnak= urlAjax= "";

						if (id.indexOf('reqPropinsi') !== -1)
						{
							var element= id.split('reqPropinsi');
							var indexId= "reqPropinsiId"+element[1];
							urlAjax= "propinsi_json/combo";
							replaceAnakId= "reqKabupatenId";
							replaceAnak= "reqKabupaten";
							$("#reqKabupatenId, #reqKecamatanId, #reqDesaId").val("");
							$("#reqKabupaten, #reqKecamatan, #reqDesa").val("");
						}
						else if (id.indexOf('reqKabupaten') !== -1)
						{
							var element= id.split('reqKabupaten');
							var indexId= "reqKabupatenId"+element[1];
							var idPropVal= $("#reqPropinsiId").val();
							urlAjax= "kabupaten_json/combo?reqPropinsiId="+idPropVal;
							replaceAnakId= "reqKecamatanId";
							replaceAnak= "reqKecamatan";
							$("#reqKecamatanId, #reqDesaId").val("");
							$("#reqKecamatan, #reqDesa").val("");
						}
						else if (id.indexOf('reqKecamatan') !== -1)
						{
							var element= id.split('reqKecamatan');
							var indexId= "reqKecamatanId"+element[1];
							var idPropVal= $("#reqPropinsiId").val();
							var idKabVal= $("#reqKabupatenId").val();
							urlAjax= "kecamatan_json/combo?reqPropinsiId="+idPropVal+"&reqKabupatenId="+idKabVal;
							replaceAnakId= "reqDesaId";
							replaceAnak= "reqDesa";
							$("#reqDesaId").val("");
							$("#reqDesa").val("");
						}
						else if (id.indexOf('reqDesa') !== -1)
						{
							var element= id.split('reqDesa');
							var indexId= "reqDesaId"+element[1];
							var idPropVal= $("#reqPropinsiId").val();
							var idKabVal= $("#reqKabupatenId").val();
							var idKecVal= $("#reqKecamatanId").val();
							urlAjax= "kelurahan_json/combo?reqPropinsiId="+idPropVal+"&reqKabupatenId="+idKabVal+"&reqKecamatanId="+idKecVal;
						}

						$.ajax({
							url: urlAjax,
							type: "GET",
							dataType: "json",
							data: { term: request.term },
							success: function(responseData){
								$("#"+indexId).val("").trigger('change');
								if(replaceAnakId == ""){}
								else
								{
									$("#"+replaceAnakId).val("").trigger('change');
									$("#"+replaceAnak).val("").trigger('change');
								}

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

						var replaceAnakId= replaceAnak= "";
						
						if (id.indexOf('reqPropinsi') !== -1)
						{
							var element= id.split('reqPropinsi');
							var indexId= "reqPropinsiId"+element[1];
							replaceAnakId= "reqKabupatenId";
							replaceAnak= "reqKabupaten";
							$("#reqKabupatenId, #reqKecamatanId, #reqDesaId").val("");
							$("#reqKabupaten, #reqKecamatan, #reqDesa").val("");
						}
						else if (id.indexOf('reqKabupaten') !== -1)
						{
							var element= id.split('reqKabupaten');
							var indexId= "reqKabupatenId"+element[1];
							replaceAnakId= "reqKecamatanId";
							replaceAnak= "reqKecamatan";
							$("#reqKecamatanId, #reqDesaId").val("");
							$("#reqKecamatan, #reqDesa").val("");
						}
						else if (id.indexOf('reqKecamatan') !== -1)
						{
							var element= id.split('reqKecamatan');
							var indexId= "reqKecamatanId"+element[1];
							replaceAnakId= "reqDesaId";
							replaceAnak= "reqDesa";
							$("#reqDesaId").val("");
							$("#reqDesa").val("");
						}
						else if (id.indexOf('reqDesa') !== -1)
						{
							var element= id.split('reqDesa');
							var indexId= "reqDesaId"+element[1];
						}

						$("#"+indexId).val(ui.item.id).trigger('change');
					},
					autoFocus: true
				})
				.autocomplete( "instance" )._renderItem = function( ul, item ) {
					return $( "<li>" )
					.append( "<a>" + item.desc  + "</a>" )
					.appendTo( ul );
				};
			});

			<?php /*?>$('#reqSatuanKerjaJsonId').combotree('setValue', "<?=$reqSatuanKerjaId?>");
			var url = "satuan_kerja_json/combotree";
			$('#reqSatuanKerjaJsonId').combotree('reload', url);<?php */?>

		});
	</script>

	<!-- SIMPLE TAB -->
	<!-- <script type="text/javascript" src="lib/simpletabs_v1.3/js/simpletabs_1.3.js"></script>
	<link href="lib/simpletabs_v1.3/css/simpletabs.css" type="text/css" rel="stylesheet"> -->


	<!-- CORE CSS-->    
	<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
	<link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
	<!-- CSS style Horizontal Nav-->    
	<link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
	<!-- Custome CSS-->    
	<link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">
	<style>
		span.combo
		{
			width:50% !important
		}
		
		.combo-label
		{
			top:-2rem !important;
		}
		
		@media screen and (max-width:767px) {
			span.combo
			{
				width:100% !important
			}
		}
	</style>
    
    <link href="lib/mbox/mbox.css" rel="stylesheet">
  	<script src="lib/mbox/mbox.js"></script>
    <link href="lib/mbox/mbox-modif.css" rel="stylesheet">
    
</head>

<body>
	<div id="basic-form" class="section">
		<div class="row">
			<div class="col s12 m10 offset-m1">

				<ul class="collection card">
					<li class="collection-item ubah-color-warna">PEGAWAI</li>
					<li class="collection-item">

						<div class="row">
							<form id="ff" method="post"  novalidate enctype="multipart/form-data">

								<div class="simpleTabs" style="margin-left:-9px !important">
									<ul class="simpleTabsNavigation">
										<li><a href="#pribadi" id="btnReload">Data Pribadi</a></li>
										<li><a href="#dokumen" id="btnReload">Dokumen Pribadi</a></li>
										<li><a href="#alamat" id="btnReload">Alamat</a></li>
										<li><a href="#kontak" id="btnReload">Kontak</a></li>
										<li><a href="#lain" id="btnReload">Lainnya</a></li>
									</ul>

									<div class="simpleTabsContent" id="pribadi">
										<?
										if($tempLoginLevel == "99")
										{
										?>
										<div class="row">
											<div class="input-field col s12 m6">
												<label for="reqNipBaru">NIP Baru</label>
												<input required name="reqNipBaru" id="reqNipBaru" class="easyui-validatebox"  type="text"  value="<?=$reqNipBaru?>" />
											</div>
											<div class="input-field col s12 m6">
												<label for="reqNipLama">NIP Lama</label>
												<input name="reqNipLama" id="reqNipLama" class="easyui-validatebox"   type="text"  value="<?=$reqNipLama?>" />
											</div>
										</div>		
										<div class="row">
											<div class="input-field col s12 m12">
												<label for="reqNama">Nama</label>
												<input required name="reqNama" id="reqNama" class="easyui-validatebox"   type="text"  value="<?=$reqNama?>" />
											</div>
										</div>	
										<?
										}
										else
										{
										?>
										<div class="row">
											<div class="input-field col s12 m6">
												<label for="reqNipBaruInfo">NIP Baru</label>
												<input name="reqNipBaru" id="reqNipBaru" type="hidden" value="<?=$reqNipBaru?>" />
												<input id="reqNipBaruInfo" type="text" value="<?=$reqNipBaru?>" disabled />
											</div>
											<div class="input-field col s12 m6">
												<label for="reqNipLamaInfo">NIP Lama</label>
												<input name="reqNipLama" id="reqNipLama" type="hidden"  value="<?=$reqNipLama?>" />
												<input id="reqNipLamaInfo" type="text" value="<?=$reqNipLama?>" disabled />
											</div>
										</div>		
										<div class="row">
											<div class="input-field col s12 m12">
												<label for="reqNamaInfo">Nama</label>
												<input name="reqNama" id="reqNama" type="hidden"  value="<?=$reqNama?>" />
												<input id="reqNamaInfo" type="text" value="<?=$reqNama?>" disabled />
											</div>
										</div>	
										<?
										}
										?>
										<div class="row">
											<div class="input-field col s12 m3">
												<label for="reqStatusPegawai">Status Pegawai</label>
                                                <input id="reqStatusPegawai" type="text" disabled="disabled"  value="<?=$reqPegawaiStatusNama?>" />
											</div>
                                            <div class="input-field col s12 m3">
                                                <label for="reqPegawaiKedudukanTmt">TMT Status</label>
                                                <input type="text" id="reqPegawaiKedudukanTmt" disabled value="<?=$reqPegawaiKedudukanTmt?> " />
                  							</div>
											<div class="input-field col s12 m6">
												<label for="reqPegawaiKedudukanNama">Kedudukan</label>
												<input name="reqPegawaiKedudukanNama" id="reqPegawaiKedudukanNama" class="easyui-validatebox" type="text" readonly="readonly" value="<?=$reqPegawaiKedudukanNama?>" />
											</div>
										</div>		
										<div class="row">
											<div class="input-field col s12 m3">
												<label for="reqTempatLahir">Tempat Lahir</label>
												<input name="reqTempatLahir" id="reqTempatLahir" class="easyui-validatebox"   type="text"  value="<?=$reqTempatLahir?>" />
											</div>
                                            <div class="input-field col s12 m2">
												<select id="reqJenisKelamin" name="reqJenisKelamin" style="padding:0" class="form-control"  >
                                                	<option value=""></option>
                                                    <option value="L" <? if($reqJenisKelamin == "L") echo "selected"?>>Laki-laki</option>
                                                    <option value="P" <? if($reqJenisKelamin == "P") echo "selected"?>>Perempuan</option>
                                                </select>
                                                <label for="reqJenisKelamin">Jenis Kelamin</label>
											</div>
                                            <div class="input-field col s12 m1">
												<select name="reqGolonganDarah" id="reqGolonganDarah" style="padding:0" class="form-control" >
                                                	<option value="" <? if($reqGolonganDarah=="")echo "selected"?>></option>
													<option value="A" <? if($reqGolonganDarah=='A') echo ' selected'?>>A</option>
													<option value="B" <? if($reqGolonganDarah=='B') echo ' selected'?>>B</option>
													<option value="AB" <? if($reqGolonganDarah=='AB') echo ' selected'?>>AB</option>
													<option value="O" <? if($reqGolonganDarah=='O') echo ' selected'?>>O</option>
												</select>
												<label for="reqGolonganDarah">Gol Darah</label>
											</div>
											<div class="input-field col s12 m2">
												<select name="reqAgama" id="reqAgama" style="padding:0" class="form-control"  >
													<?
													while ($setAgama->nextRow()) 
													{
														?>
														<option value="<?=$setAgama->getField('AGAMA_ID')?>" <? if($reqAgama==$setAgama->getField("AGAMA_ID")) echo ' selected'?>><?=$setAgama->getField("NAMA")?></option>
														<?
													}
													?>
												</select>
												<label for="reqAgama">Agama</label>
											</div>
											<div class="input-field col s12 m4">
												<label for="reqSukuBangsa">Suku Bangsa</label>
												<input name="reqSukuBangsa" id="reqSukuBangsa" class="easyui-validatebox"   type="text"  value="<?=$reqSukuBangsa?>" />
											</div>
										</div>	
										<div class="row">
											<div class="input-field col s12 m12">
                                            	<label for="reqLabelSatuanKerja">Satuan Kerja</label>
                                                <input id="reqLabelSatuanKerja" type="text" disabled="disabled"  value="<?=$reqSatuanKerjaNamaDetil?>" />
												<input type="hidden" name="reqSatuanKerjaId" id="reqSatuanKerjaId" value="<?=$reqSatuanKerjaId?>" />
											</div>
										</div>		
									</div>

									<div class="simpleTabsContent" id="dokumen">
										<div class="row">
											<div class="input-field col s12 m5">
												<label for="reqKartuPegawai">Kartu Pegawai</label>
												<input name="reqKartuPegawai" id="reqKartuPegawai" class="easyui-validatebox"   type="text"  value="<?=$reqKartuPegawai?>" />
											</div>
											<div class="col s12 m1 ">
												<a class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
											</div>
											<div class="input-field col s12 m5">
												<label for="reqTaspen">Taspen</label>
												<input name="reqTaspen" id="reqTaspen" class="easyui-validatebox"   type="text"  value="<?=$reqTaspen?>" />
											</div>
											<div class="col s12 m1 ">
												<a class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
											</div>
										</div>	
										<div class="row">
											<div class="input-field col s12 m5">
												<label for="reqSkKonversiNip">SK Konversi NIP</label>
												<input name="reqSkKonversiNip" id="reqSkKonversiNip" class="easyui-validatebox"   type="text"  value="<?=$reqSkKonversiNip?>" />
											</div>
											<div class="col s12 m1 ">
												<a class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
											</div>
											<div class="input-field col s12 m5">
												<label for="reqUrut">No Urut</label>
												<input name="reqUrut" class="easyui-validatebox" id="reqUrut"  type="text"  value="<?=$reqUrut?>" />
											</div>
											<div class="col s12 m1 ">
												<a class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
											</div>
										</div>	
										<div class="row">
											<div class="input-field col s12 m5">
												<label for="reqNik">NIK</label>
												<input name="reqNik" class="easyui-validatebox" id="reqNik"  type="text"  value="<?=$reqNik?>" />
											</div>
											<div class="col s12 m1 ">
												<a class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
											</div>
											<div class="input-field col s12 m5">
												<label for="reqNoKk">No KK</label>
												<input name="reqNoKk" class="easyui-validatebox" id="reqNoKk"  type="text"  value="<?=$reqNoKk?>" />
											</div>
											<div class="col s12 m1 ">
												<a class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
											</div>
										</div>	
										<div class="row">
											<div class="input-field col s12 m5">
												<label for="reqNoRakBerkas">No Rak Berkas</label>
												<input name="reqNoRakBerkas" class="easyui-validatebox" id="reqNoRakBerkas"  type="text"  value="<?=$reqNoRakBerkas?>" />
											</div>
											<div class="col s12 m1 ">
												<a class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
											</div>
											<div class="input-field col s12 m5">
												<label for="reqNpwp">NPWP</label>
												<input name="reqNpwp" class="easyui-validatebox" id="reqNpwp"  type="text"  value="<?=$reqNpwp?>" />
											</div>
											<div class="col s12 m1 ">
												<a class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
											</div>
										</div>	
									</div>

									<div class="simpleTabsContent" id="alamat">
										<div class="row">
											<div class="input-field col s12 m12">
												<label for="reqAlamat">Alamat</label>
												<input name="reqAlamat" id="reqAlamat" class="easyui-validatebox"   type="text"  value="<?=$reqAlamat?>" />
											</div>
										</div>	
										<div class="row">
											<div class="input-field col s12 m6">
												<label for="reqRt">RT</label>
												<input name="reqRt" id="reqRt" class="easyui-validatebox"   type="text"  value="<?=$reqRt?>" />
											</div>
											<div class="input-field col s12 m6">
												<label for="reqRw">RW</label>
												<input name="reqRw" id="reqRw" class="easyui-validatebox "   type="text"  value="<?=$reqRw?>" />
											</div>
										</div>	
										<div class="row">
											<div class="input-field col s12 m6">
												<label for="reqDesa">Desa</label>
												<input type="hidden" name="reqDesaId" id="reqDesaId" value="<?=$reqDesaId?>" /> 
								                <input type="text" class="easyui-validatebox" id="reqDesa" 
								                data-options="validType:['sameAutoLoder[\'reqDesa\', \'\']']"
								                value="<?=$reqDesa?>" />
											</div>
											<div class="input-field col s12 m6">
												<label for="reqKecamatan">Kecamatan</label>
												<input type="hidden" name="reqKecamatanId" id="reqKecamatanId" value="<?=$reqKecamatanId?>" /> 
								                <input type="text" class="easyui-validatebox" id="reqKecamatan" 
								                data-options="validType:['sameAutoLoder[\'reqKecamatan\', \'\']']"
								                value="<?=$reqKecamatan?>" />
											</div>
										</div>	
										<div class="row">
											<div class="input-field col s12 m6">
												<label for="reqKabupaten">Kabupaten</label>
												<input type="hidden" name="reqKabupatenId" id="reqKabupatenId" value="<?=$reqKabupatenId?>" /> 
								                <input type="text" class="easyui-validatebox" id="reqKabupaten" 
								                data-options="validType:['sameAutoLoder[\'reqKabupaten\', \'\']']"
								                value="<?=$reqKabupaten?>" />
											</div>
											<div class="input-field col s12 m6">
												<label for="reqPropinsi">Propinsi</label>
												<input type="hidden" name="reqPropinsiId" id="reqPropinsiId" value="<?=$reqPropinsiId?>" /> 
								                <input type="text" class="easyui-validatebox" id="reqPropinsi" 
								                data-options="validType:['sameAutoLoder[\'reqPropinsi\', \'\']']"
								                value="<?=$reqPropinsi?>" />
											</div>
										</div>											
									</div>

									<div class="simpleTabsContent" id="kontak">
										<div class="row">
											<div class="input-field col s12 m6">
												<label for="reqTelepon">No Telepon Rumah</label>
												<input name="reqTelepon" id="reqTelepon" class="easyui-validatebox"   type="text"  value="<?=$reqTelepon?>" />
											</div>
											<div class="input-field col s12 m6">
												<label for="reqHp">HP</label>
												<input name="reqHp" id="reqHp" class="easyui-validatebox"   type="text"  value="<?=$reqHp?>" />
											</div>
										</div>	
										<div class="row">
											<div class="input-field col s12 m6">
												<label for="reqTeleponKantor">No Telepon Kantor</label>
												<input name="reqTeleponKantor" id="reqTeleponKantor" class="easyui-validatebox"   type="text"  value="<?=$reqTeleponKantor?>" />
											</div>
										</div>	
										<div class="row">
											<div class="input-field col s12 m6">
												<label for="reqFacebook">Facebook</label>
												<input name="reqFacebook" id="reqFacebook" class="easyui-validatebox"   type="text"  value="<?=$reqFacebook?>" />
											</div>
											<div class="input-field col s12 m6">
												<label for="reqTwitter">Twitter</label>
												<input name="reqTwitter" id="reqTwitter" class="easyui-validatebox"   type="text"  value="<?=$reqTwitter?>" />
											</div>
										</div>	
										<div class="row">
											<div class="input-field col s12 m6">
												<label for="reqWhatsApp">WhatsApp</label>
												<input name="reqWhatsApp" id="reqWhatsApp" class="easyui-validatebox"   type="text"  value="<?=$reqWhatsApp?>" />
											</div>
											<div class="input-field col s12 m6">
												<label for="reqTelegram">Telegram</label>
												<input name="reqTelegram" id="reqTelegram" class="easyui-validatebox"   type="text"  value="<?=$reqTelegram?>" />
											</div>
										</div>	
									</div>

									<div class="simpleTabsContent" id="lain">
										<div class="row">
											<div class="input-field col s12 m6">
												<select name="reqBank" id="reqBank" style="padding:0" class="form-control"  >
                                                	<option value="" <? if($reqGolonganDarah=="")echo "selected"?>></option>
													<?
													while ($setBank->nextRow()) 
													{
														?>
														<option value="<?=$setBank->getField('BANK_ID')?>" <? if($reqBank==$setBank->getField("BANK_ID")) echo ' selected'?>><?=$setBank->getField("NAMA")?></option>
														<?
													}
													?>
												</select>
												<label for="reqBank">Bank</label>
											</div>
											<div class="input-field col s12 m6">
												<label for="reqNoRekening">No Rekening</label>
												<input name="reqNoRekening" id="reqNoRekening" class="easyui-validatebox"   type="text"  value="<?=$reqNoRekening?>" />
											</div>
										</div>	

										<div class="row">
											<div class="input-field col s12 m12">
												<label for="reqKeterangan1">Keterangan 1</label>
												<input name="reqKeterangan1" id="reqKeterangan1" class="easyui-validatebox"   type="text"  value="<?=$reqKeterangan1?>" />
											</div>
										</div>

										<div class="row">
											<div class="input-field col s12 m12">
												<label for="reqKeterangan2">Keterangan 2</label>
												<input name="reqKeterangan2" id="reqKeterangan2" class="easyui-validatebox"   type="text"  value="<?=$reqKeterangan2?>" />
											</div>
										</div>	
									</div>
								</div>

								<div class="row">
									<div class="input-field col s12 m12">
										<input type="hidden" name="reqId" value="<?=$reqId?>" />
										<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
										<button class="btn waves-effect waves-light green" style="font-size:9pt" type="submit" name="action">Simpan
											<i class="mdi-content-save left hide-on-small-only"></i>
										</button>
										<?
										if($reqId == ""){}
										else
										{
										?>
										<button class="btn waves-effect waves-light red" style="font-size:9pt" type="button" id="buttonwafattewas">Laporkan Wafat/Tewas
											<i class="mdi-image-timer-off left hide-on-small-only"></i>
										</button>
										<!-- <button class="btn waves-effect waves-light red" style="font-size:9pt" type="button" id="buttonwafat">Laporkan Wafat
											<i class="mdi-image-timer-off left hide-on-small-only"></i>
										</button>
										<button class="btn waves-effect waves-light red" style="font-size:9pt" type="button" id="buttontewas">Laporkan Tewas
											<i class="mdi-image-timer-off left hide-on-small-only"></i>
										</button> -->
										<?
										}
										?>
									</div>
								</div>

							</form>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<!--materialize js-->
	<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {

			$("#tabs").tabs({
			  swipeable: true
			});
			
			$("#buttonwafat,#buttontewas,#buttonwafattewas").click(function() { 
				var id= $(this).attr('id');

				if(id == "buttonwafat")
				{
					info= "Apakah yakin untuk an. <?=$reqNama?>, laporkan wafat?";
					reqStatusPegawaiKedudukanId= "24";
					reqStatusPegawaiId= "3";
				}
				else if(id == "buttontewas")
				{
					info= "Apakah yakin untuk an. <?=$reqNama?>, laporkan tewas?";
					reqStatusPegawaiKedudukanId= "25";
					reqStatusPegawaiId= "3";
				}
				else if(id == "buttonwafattewas")
				{
					info= "Apakah yakin untuk an. <?=$reqNama?>, laporkan wafat/tewas?";
					reqStatusPegawaiKedudukanId= "24,25";
					reqStatusPegawaiId= "3";
				}

				
				mbox.custom({
					message: info,
					options: {close_speed: 100},
					buttons: [
					{
						label: 'Ya',
						color: 'green darken-2',
						callback: function() {
							document.location.href= "app/loadUrl/app/pegawai_add_pegawai_status_lapor?reqId=<?=$reqId?>&reqStatusPegawaiKedudukanId="+reqStatusPegawaiKedudukanId+"&reqStatusPegawaiId="+reqStatusPegawaiId;
							mbox.close();
						}
					},
					{
						label: 'Tidak',
						color: 'grey darken-2',
						callback: function() {
					   //console.log('do action for no answer');
					   mbox.close();
					}
				}
				]
				});
			});

			$('select').material_select();
		});

		$('.materialize-textarea').trigger('autoresize');

	</script>
    
    <link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
	<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>
