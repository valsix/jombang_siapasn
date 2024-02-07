
<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/personal.func.php");


$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('validasi/Pegawai');
$this->load->model('Bank');
$this->load->model('Agama');
$this->load->model('SatuanKerja');
$this->load->model('JenisPegawai');
$this->load->model('PegawaiFile');
$this->load->model('Propinsi');
$this->load->model('Kabupaten');
$this->load->model('Kecamatan');
$this->load->model('Kelurahan');


$tempLoginLevel= $this->LOGIN_LEVEL;

$set= new Pegawai();


$reqId = $this->input->get("reqId");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "0101";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

$arrstatusvalidasi= [];
$arrinfocombo= [];
$arrinfocombo= array(
  array("id"=>"1", "text"=>"Valid")
  , array("id"=>"2", "text"=>"Ditolak")
);
for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
{
  $arrdata= [];
  $arrdata["id"]= $arrinfocombo[$icombo]["id"];
  $arrdata["text"]= $arrinfocombo[$icombo]["text"];
  array_push($arrstatusvalidasi, $arrdata);
}

$arrbank= [];
$set= new Bank();
$set->selectByParams(array(), -1,-1, "");
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["id"]= $set->getField("BANK_ID");
  $arrdata["text"]= $set->getField("NAMA");
  array_push($arrbank, $arrdata);
}

$arragama= [];
$set= new Agama();
$set->selectByParams(array(), -1,-1, "");
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["id"]= $set->getField("AGAMA_ID");
  $arrdata["text"]= $set->getField("NAMA");
  array_push($arragama, $arrdata);
}

$arrjenispegawai= [];
$set= new JenisPegawai();
$set->selectByParams(array(), -1,-1, "");
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["id"]= $set->getField("JENIS_PEGAWAI_ID");
  $arrdata["text"]= $set->getField("NAMA");
  array_push($arrjenispegawai, $arrdata);
}

$arrpropinsi= [];
$set= new Propinsi();
$set->selectByParams(array(), -1,-1, "");
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["id"]= $set->getField("PROPINSI_ID");
  $arrdata["text"]= $set->getField("NAMA");
  array_push($arrpropinsi, $arrdata);
}

$arrkabupaten= [];
$set= new Kabupaten();
$set->selectByParams(array(), -1,-1, "");
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["id"]= $set->getField("KABUPATEN_ID");
  $arrdata["text"]= $set->getField("NAMA");
  array_push($arrkabupaten, $arrdata);
}

$arrkecamatan= [];
$set= new Kecamatan();
$set->selectByParams(array(), -1,-1, "");
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["id"]= $set->getField("KECAMATAN_ID");
  $arrdata["text"]= $set->getField("NAMA");
  array_push($arrkecamatan, $arrdata);
}

$arrdesa= [];
$set= new Kelurahan();
$set->selectByParams(array(), -1,-1, "");
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["id"]= $set->getField("KELURAHAN_ID");
  $arrdata["text"]= $set->getField("NAMA");
  array_push($arrdesa, $arrdata);
}


$arrjeniskelamin= [];
$arrinfocombo= [];
$arrinfocombo= array(
	array("id"=>"L", "text"=>"Laki-laki")
	, array("id"=>"P", "text"=>"Perempuan")
);
for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
{
	$arrdata= [];
	$arrdata["id"]= $arrinfocombo[$icombo]["id"];
	$arrdata["text"]= $arrinfocombo[$icombo]["text"];
	array_push($arrjeniskelamin, $arrdata);
}

$arrgoldarah= [];
$arrinfocombo= [];
$arrinfocombo= array(
	array("id"=>"", "text"=>"")
	, array("id"=>"A", "text"=>"A")
  , array("id"=>"B", "text"=>"B")
  , array("id"=>"AB", "text"=>"AB")
  , array("id"=>"O", "text"=>"O")

);
for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
{
	$arrdata= [];
	$arrdata["id"]= $arrinfocombo[$icombo]["id"];
	$arrdata["text"]= $arrinfocombo[$icombo]["text"];
	array_push($arrgoldarah, $arrdata);
}

$arrstatusmutasi= [];
$arrinfocombo= [];
$arrinfocombo= array(
  array("id"=>"", "text"=>"Tidak")
  , array("id"=>"1", "text"=>"Ya")
);
for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
{
  $arrdata= [];
  $arrdata["id"]= $arrinfocombo[$icombo]["id"];
  $arrdata["text"]= $arrinfocombo[$icombo]["text"];
  array_push($arrstatusmutasi, $arrdata);
}


$statement= "";
$set= new Pegawai();

$infoperubahan= "Perubahan Data";
if(!empty($reqRowHapusId))
{
  $infoperubahan= "Hapus Data";
  $set->selectByPersonal(array(), -1, -1, $reqId, $reqRowHapusId, "", $statement);
}
else
  $set->selectByPersonal(array(), -1, -1, $reqId, $reqRowHapusId, $reqRowId, $statement);

// echo $set->query;exit;
$set->firstRow();
$reqTempValidasiId= $set->getField('TEMP_VALIDASI_ID');
$reqTempValidasiHapusId= $set->getField('TEMP_VALIDASI_HAPUS_ID');
$reqValidasi= $set->getField('VALIDASI');
$reqPerubahanData= $set->getField('PERUBAHAN_DATA');

$reqValRowId= $set->getField('PEGAWAI_ID');
if(empty($reqValRowId))
{
  $infoperubahan= "Data Baru";
}

$reqRowId= $set->getField('PEGAWAI_ID');
$reqStatus= $set->getField("STATUS");
$reqSatuanKerjaNamaDetil= $set->getField("SATUAN_KERJA_NAMA_DETIL");$valLabelSatuanKerja= checkwarna($reqPerubahanData, 'SATUAN_KERJA_NAMA_DETIL');
$reqSatuanKerjaId= $set->getField("SATUAN_KERJA_ID");
$reqJenisPegawaiId= $set->getField("JENIS_PEGAWAI_ID");$valJenisPegawai= checkwarna($reqPerubahanData, 'JENIS_PEGAWAI_ID', $arrjenispegawai, array("id", "text"));
$reqJenisPegawaiNama= $set->getField("JENIS_PEGAWAI_NAMA");
$reqBpjs= $set->getField("BPJS");$valBpjs= checkwarna($reqPerubahanData, 'BPJS');
$reqBpjsTanggal= dateToPageCheck($set->getField("BPJS_TANGGAL"));$valBpjsTanggal= checkwarna($reqPerubahanData, 'BPJS_TANGGAL', "date");
$reqNpwpTanggal= dateToPageCheck($set->getField("NPWP_TANGGAL"));$valNpwpTanggal= checkwarna($reqPerubahanData, 'NPWP_TANGGAL', "date");
$reqEmailKantor= $set->getField("EMAIL_KANTOR");$valEmailKantor= checkwarna($reqPerubahanData, 'EMAIL_KANTOR');
$reqRekeningNama= $set->getField("REKENING_NAMA");$valRekeningNama= checkwarna($reqPerubahanData, 'REKENING_NAMA');
$reqGajiPokok= $set->getField("GAJI_POKOK");$valGajiPokok= checkwarna($reqPerubahanData, 'GAJI_POKOK');
$reqTunjangan= $set->getField("TUNJANGAN");$valTunjangan= checkwarna($reqPerubahanData, 'TUNJANGAN');
$reqTunjanganKeluarga= $set->getField("TUNJANGAN_KELUARGA");$valTunjanganKeluarga= checkwarna($reqPerubahanData, 'TUNJANGAN_KELUARGA');
$reqGajiBersih= $set->getField("GAJI_BERSIH");$valGajiBersih= checkwarna($reqPerubahanData, 'GAJI_BERSIH');
$reqStatusMutasi= $set->getField("STATUS_MUTASI");$valStatusMutasi= checkwarna($reqPerubahanData, 'STATUS_MUTASI');
$reqTmtMutasi= dateToPageCheck($set->getField("TMT_MUTASI"));$valTmtMutasi= checkwarna($reqPerubahanData, 'TMT_MUTASI', "date");
$reqInstansiSebelum= $set->getField("INSTANSI_SEBELUM");$valInstansiSebelum= checkwarna($reqPerubahanData, 'INSTANSI_SEBELUM');
$reqJenisPegawai= $set->getField("JENIS_PEGAWAI_ID");
$reqTipePegawai= $set->getField("TIPE_PEGAWAI_ID");
$reqStatusPegawai= $set->getField("STATUS_PEGAWAI");
$reqPegawaiStatusNama= $set->getField("PEGAWAI_STATUS_NAMA");$valPegawaiStatusNama= checkwarna($reqPerubahanData, 'PEGAWAI_STATUS_NAMA');
$reqPegawaiKedudukanTmt= dateToPageCheck($set->getField("PEGAWAI_KEDUDUKAN_TMT"));$valPegawaiKedudukanTmt= checkwarna($reqPerubahanData, 'PEGAWAI_KEDUDUKAN_TMT', "date");
$reqPegawaiKedudukanNama= $set->getField("PEGAWAI_KEDUDUKAN_NAMA");$valPegawaiKedudukanNama= checkwarna($reqPerubahanData, 'PEGAWAI_KEDUDUKAN_NAMA');
$reqNipLama= $set->getField("NIP_LAMA");$valNipLama= checkwarna($reqPerubahanData, 'NIP_LAMA');
$reqNipBaru= $set->getField("NIP_BARU");$valNipBaru= checkwarna($reqPerubahanData, 'NIP_BARU');
$reqNama= $set->getField("NAMA");$valNama= checkwarna($reqPerubahanData, 'NAMA');
$reqGelarDepan= $set->getField("GELAR_DEPAN");$valGelarDepan= checkwarna($reqPerubahanData, 'GELAR_DEPAN');
$reqGelarBelakang= $set->getField("GELAR_BELAKANG");$valGelarBelakang= checkwarna($reqPerubahanData, 'GELAR_BELAKANG');
$reqTempatLahir= $set->getField("TEMPAT_LAHIR");$valTempatLahir= checkwarna($reqPerubahanData, 'TEMPAT_LAHIR');
$reqTanggalLahir= $set->getField("TANGGAL_LAHIR");$valTanggalLahir= checkwarna($reqPerubahanData, 'TANGGAL_LAHIR', "date");
$reqJenisKelamin= $set->getField("JENIS_KELAMIN");$valJenisKelamin= checkwarna($reqPerubahanData, 'JENIS_KELAMIN');
$reqStatusKawin= $set->getField("STATUS_KAWIN");$valStatusKawin= checkwarna($reqPerubahanData, 'STATUS_KAWIN');
$reqSukuBangsa= $set->getField("SUKU_BANGSA");$valSukuBangsa= checkwarna($reqPerubahanData, 'SUKU_BANGSA');
$reqGolonganDarah= $set->getField("GOLONGAN_DARAH");$valGolonganDarah= checkwarna($reqPerubahanData, 'GOLONGAN_DARAH', $arrgoldarah, array("id", "text"));
$reqEmail= $set->getField("EMAIL");$valEmail= checkwarna($reqPerubahanData, 'EMAIL');
$reqAlamat= $set->getField("ALAMAT");$valAlamat= checkwarna($reqPerubahanData, 'ALAMAT');
$reqAlamatKeterangan= $set->getField("ALAMAT_KETERANGAN");$valAlamatKeterangan= checkwarna($reqPerubahanData, 'ALAMAT_KETERANGAN');
$reqRt= $set->getField("RT");$valRt= checkwarna($reqPerubahanData, 'RT');
$reqRw= $set->getField("RW");$valRw= checkwarna($reqPerubahanData, 'RW');
$reqKodePos= $set->getField("KODEPOS");$valKodePos= checkwarna($reqPerubahanData, 'KODEPOS');
$reqTelepon= $set->getField("TELEPON");$valTelepon= checkwarna($reqPerubahanData, 'TELEPON');
$reqHp= $set->getField("HP");$valHp= checkwarna($reqPerubahanData, 'HP');
$reqKartuPegawai= $set->getField("KARTU_PEGAWAI");$valKartuPegawai= checkwarna($reqPerubahanData, 'KARTU_PEGAWAI');
$reqAskes= $set->getField("ASKES");$valKartuPegawai= checkwarna($reqPerubahanData, 'KARTU_PEGAWAI');
$reqTaspen= $set->getField("TASPEN");$valTaspen= checkwarna($reqPerubahanData, 'TASPEN');
$reqNpwp= $set->getField("NPWP");$valNpwp= checkwarna($reqPerubahanData, 'NPWP');
$reqNik= $set->getField("NIK");$valNik= checkwarna($reqPerubahanData, 'NIK');
$reqNoRekening= $set->getField("NO_REKENING");$valNoRekening= checkwarna($reqPerubahanData, 'NO_REKENING');
$reqSkKonversiNip= $set->getField("SK_KONVERSI_NIP");$valSkKonversiNip= checkwarna($reqPerubahanData, 'SK_KONVERSI_NIP');
$reqBank= $set->getField("BANK_ID");$valBank= checkwarna($reqPerubahanData, 'BANK_ID', "date");
$reqAgama= $set->getField("AGAMA_ID");$valAgama= checkwarna($reqPerubahanData, 'AGAMA_ID', "date");
$reqUrut= $set->getField("NO_URUT");$valUrut= checkwarna($reqPerubahanData, 'NO_URUT');
$reqNoKk= $set->getField("NO_KK");$valNoKk= checkwarna($reqPerubahanData, 'NO_KK');
$reqNoRakBerkas= $set->getField("NO_RAK_BERKAS");$valNoRakBerkas= checkwarna($reqPerubahanData, 'NO_RAK_BERKAS');
$reqTeleponKantor= $set->getField("TELEPON_KANTOR");$valTeleponKantor= checkwarna($reqPerubahanData, 'TELEPON_KANTOR');
$reqFacebook= $set->getField("FACEBOOK");$valFacebook= checkwarna($reqPerubahanData, 'FACEBOOK');
$reqTwitter= $set->getField("TWITTER");$valTwitter= checkwarna($reqPerubahanData, 'TWITTER');
$reqWhatsApp= $set->getField("WHATSAPP");$valWhatsApp= checkwarna($reqPerubahanData, 'WHATSAPP');
$reqTelegram= $set->getField("TELEGRAM");$valTelegram= checkwarna($reqPerubahanData, 'TELEGRAM');
$reqKeterangan1= $set->getField("KETERANGAN_1");$valKeterangan1= checkwarna($reqPerubahanData, 'KETERANGAN_1');
$reqKeterangan2= $set->getField("KETERANGAN_2");$valKeterangan2= checkwarna($reqPerubahanData, 'KETERANGAN_2');
$reqPropinsiId= $set->getField("PROPINSI_ID");$valPropinsiId= checkwarna($reqPerubahanData, 'PROPINSI_ID', $arrpropinsi, array("id", "text"));
$reqPropinsi= $set->getField("PROPINSI_NAMA");
$reqKabupatenId= $set->getField("KABUPATEN_ID");$valKabupatenId= checkwarna($reqPerubahanData, 'KABUPATEN_ID', $arrkabupaten, array("id", "text"));
$reqKabupaten= $set->getField("KABUPATEN_NAMA");
$reqKecamatanId= $set->getField("KECAMATAN_ID");$valKecamatanId= checkwarna($reqPerubahanData, 'KECAMATAN_ID', $arrkecamatan, array("id", "text"));
$reqKecamatan= $set->getField("KECAMATAN_NAMA");
$reqDesaId= $set->getField("DESA_ID");$valDesaId= checkwarna($reqPerubahanData, 'DESA_ID', $arrdesa, array("id", "text"));
$reqDesa= $set->getField("DESA_NAMA");

$statement= " AND A.RIWAYAT_ID = 3 AND A.RIWAYAT_TABLE = 'PEGAWAI' AND A.PEGAWAI_ID = ".$reqId."";
$pegawai_file= new PegawaiFile();
$pegawai_file->selectByParamsLastRiwayatTable(array(), -1,-1,$statement);
$pegawai_file->firstRow();
$tempPathKarpeg= $pegawai_file->getField("PATH");

$statement= " AND A.RIWAYAT_ID = 4 AND A.RIWAYAT_TABLE = 'PEGAWAI' AND A.PEGAWAI_ID = ".$reqId."";
$pegawai_file= new PegawaiFile();
$pegawai_file->selectByParamsLastRiwayatTable(array(), -1,-1,$statement);
$pegawai_file->firstRow();
$tempPathTaspen= $pegawai_file->getField("PATH");

$statement= " AND A.RIWAYAT_ID = 2 AND A.RIWAYAT_TABLE = 'PEGAWAI' AND A.PEGAWAI_ID = ".$reqId."";
$pegawai_file= new PegawaiFile();
$pegawai_file->selectByParamsLastRiwayatTable(array(), -1,-1,$statement);
$pegawai_file->firstRow();
$tempPathSkKonversiNip= $pegawai_file->getField("PATH");

$statement= " AND A.RIWAYAT_ID = 5 AND A.RIWAYAT_TABLE = 'PEGAWAI' AND A.PEGAWAI_ID = ".$reqId."";
$pegawai_file= new PegawaiFile();
$pegawai_file->selectByParamsLastRiwayatTable(array(), -1,-1,$statement);
$pegawai_file->firstRow();
$tempPathNik= $pegawai_file->getField("PATH");

$statement= " AND A.RIWAYAT_ID = 6 AND A.RIWAYAT_TABLE = 'PEGAWAI' AND A.PEGAWAI_ID = ".$reqId."";
$pegawai_file= new PegawaiFile();
$pegawai_file->selectByParamsLastRiwayatTable(array(), -1,-1,$statement);
$pegawai_file->firstRow();
$tempPathKartuKeluarga= $pegawai_file->getField("PATH");

$statement= " AND A.RIWAYAT_ID = 7 AND A.RIWAYAT_TABLE = 'PEGAWAI' AND A.PEGAWAI_ID = ".$reqId."";
$pegawai_file= new PegawaiFile();
$pegawai_file->selectByParamsLastRiwayatTable(array(), -1,-1,$statement);
$pegawai_file->firstRow();
$tempPathNpwp= $pegawai_file->getField("PATH");

$statement= " AND A.RIWAYAT_ID = 9 AND A.RIWAYAT_TABLE = 'PEGAWAI' AND A.PEGAWAI_ID = ".$reqId."";
$pegawai_file= new PegawaiFile();
$pegawai_file->selectByParamsLastRiwayatTable(array(), -1,-1,$statement);
$pegawai_file->firstRow();
$reqPathBpjs= $pegawai_file->getField("PATH");


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

			$("#reqSatuanKerja, #reqJenisxxxPegawai").each(function(){
				$(this).autocomplete({
						source:function(request, response){
							var id= this.element.attr('id');
							var replaceAnakId= replaceAnak= urlAjax= "";

							if (id.indexOf('reqSatuanKerja') !== -1)
							{
								var element= id.split('reqSatuanKerja');
								var indexId= "reqSatuanKerjaId"+element[1];
								urlAjax= "satuan_kerja_json/auto";
							}
							else if (id.indexOf('reqJenisPegawai') !== -1)
							{
								var element= id.split('reqJenisPegawai');
								var indexId= "reqJenisPegawaiId"+element[1];
								urlAjax= "jenis_pegawai_json/auto";
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
							if (id.indexOf('reqSatuanKerja') !== -1)
							{
								var element= id.split('reqSatuanKerja');
								var indexId= "reqSatuanKerjaId"+element[1];
							}
							else if (id.indexOf('reqJenisPegawai') !== -1)
							{
								var element= id.split('reqJenisPegawai');
								var indexId= "reqJenisPegawaiId"+element[1];
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

			$('#ff').form({
				url:'validasi/pegawai_json/add',
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
				},
				success:function(data){
				  // console.log(data);return false;
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
				  		document.location.href= "app/loadUrl/verifikasi/validasi_verifikator/";
				  	}, 1000));
				  	$(".mbox > .right-align").css({"display": "none"});
				  }
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

			setmutasiluardaerah();
			$('#reqStatusMutasi').bind('change', function(ev) {
				setmutasiluardaerah();
			});

		});

		function setmutasiluardaerah()
		{
			var reqStatusMutasi= "";
			reqStatusMutasi= $("#reqStatusMutasi").val();
			$("#reqInstansiSebelum, #reqTmtMutasi").validatebox({required: false});
			$("#reqInstansiSebelum, #reqTmtMutasi").removeClass('validatebox-invalid');

			if(reqStatusMutasi == "")
			{
				$(".setstatusmutasi").hide();
				$("#reqInstansiSebelum, #reqTmtMutasi").val("");
			}
			else
			{
				$(".setstatusmutasi").show();
				$("#reqInstansiSebelum, #reqTmtMutasi").validatebox({required: true});
			}
		}
	</script>

	<!-- SIMPLE TAB -->

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
    <link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

    
</head>

<body>
	<div id="basic-form" class="section">
		<div class="row">
			<div class="col s12 m10 offset-m1">

				<ul class="collection card">
					<li class="collection-item ubah-color-warna"> <?=$infoperubahan?> PEGAWAI</li>
					<li class="collection-item">

						<div class="row">
							<form id="ff" method="post"  novalidate enctype="multipart/form-data">

								<div id="tabs" style="height:100%;">
                                    <ul id="tabs-swipe-demo" class="tabs">
                                        <li class="tab col s3"><a class="active" href="#swipe-1">Data Pribadi</a></li>
                                        <li class="tab col s3"><a href="#swipe-2">Dokumen Pribadi</a></li>
                                        <li class="tab col s3"><a href="#swipe-3">Alamat</a></li>
                                        <li class="tab col s3"><a href="#swipe-4">Kontak</a></li>
                                        <li class="tab col s3"><a href="#swipe-5">Finansial</a></li>
                                        <li class="tab col s3"><a href="#swipe-6">Lainnya</a></li>
                                    </ul>
                                </div>

								<div id="swipe-1" class="col s12" style="height:auto !important">
									<?
									if($tempLoginLevel == "99" || ($reqId == "" && $tempAksesMenu == "A" && isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1"))
									{
									?>
									<div class="row">
										<div class="input-field col s12 m6">
											<label for="reqNipBaru" class="<?=$valNipBaru['warna']?>">
												NIP Baru
												<?
												if(!empty($valNipBaru['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNipBaru['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input required name="reqNipBaru" id="reqNipBaru" class="easyui-validatebox"  type="text"  value="<?=$reqNipBaru?>" />
										</div>
										<div class="input-field col s12 m6">
											<label for="reqNipLama" class="<?=$valNipLama['warna']?>">
												NIP Lama
												<?
												if(!empty($valNipLama['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNipLama['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input name="reqNipLama" id="reqNipLama" class="easyui-validatebox" type="text" value="<?=$reqNipLama?>" />
										</div>
									</div>		
									<div class="row">
										<div class="input-field col s12 m12">
											<label for="reqNama" class="<?=$valNama['warna']?>">
												Nama
												<?
												if(!empty($valNama['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNama['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input required name="reqNama" id="reqNama" class="easyui-validatebox" type="text" value="<?=$reqNama?>" />
										</div>
									</div>	
									<?
									}
									else
									{
									?>
									<div class="row">
										<div class="input-field col s12 m6">
											<label for="reqNipBaruInfo" class="<?=$valNipBaru['warna']?>">
												NIP Baru
												<?
												if(!empty($valNipBaru['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNipBaru['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input name="reqNipBaru" id="reqNipBaru" type="hidden" value="<?=$reqNipBaru?>" />
											<input id="reqNipBaruInfo" type="text" value="<?=$reqNipBaru?>" disabled />
										</div>
										<div class="input-field col s12 m6">
											<label for="reqNipBaruInfo" class="<?=$valNipLama['warna']?>">
												NIP Lama
												<?
												if(!empty($valNipLama['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNipLama['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input name="reqNipLama" id="reqNipLama" type="hidden"  value="<?=$reqNipLama?>" />
											<input id="reqNipLamaInfo" type="text" value="<?=$reqNipLama?>" disabled />
										</div>
									</div>		
									<div class="row">
										<div class="input-field col s12 m12">
											<label for="reqNipBaruInfo" class="<?=$valNama['warna']?>">
												Nama
												<?
												if(!empty($valNama['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNama['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input name="reqNama" id="reqNama" type="hidden"  value="<?=$reqNama?>" />
											<input id="reqNamaInfo" type="text" value="<?=$reqNama?>" disabled />
										</div>
									</div>	
									<?
									}
									?>
									<div class="row">
										<div class="input-field col s12 m3">
											<label for="reqStatusPegawai" class="<?=$valStatusPegawai['warna']?>">
												Status Pegawai
												<?
												if(!empty($valStatusPegawai['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valStatusPegawai['data']?></span>
													</a>
													<?
												}
												?>
											</label>
                                            <input id="reqStatusPegawai" type="text" disabled="disabled"  value="<?=$reqPegawaiStatusNama?>" />
										</div>
										<div class="input-field col s12 m6">
											<label for="reqPegawaiKedudukanNama" class="<?=$valPegawaiKedudukanNama['warna']?>">
												Kedudukan
												<?
												if(!empty($valPegawaiKedudukanNama['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valPegawaiKedudukanNama['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input name="reqPegawaiKedudukanNama" id="reqPegawaiKedudukanNama" class="easyui-validatebox" type="text" readonly="readonly" value="<?=$reqPegawaiKedudukanNama?>" />
										</div>
                                        <div class="input-field col s12 m3">
                                            <label for="reqPegawaiKedudukanTmt" class="<?=$valPegawaiKedudukanTmt['warna']?>">
												TMT Kedudukan
												<?
												if(!empty($valPegawaiKedudukanTmt['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valPegawaiKedudukanTmt['data']?></span>
													</a>
													<?
												}
												?>
											</label>
                                            <input type="text" id="reqPegawaiKedudukanTmt" disabled value="<?=$reqPegawaiKedudukanTmt?> " />
              							</div>
									</div>		
									<div class="row">
										<div class="input-field col s12 m3">
											<label for="reqTempatLahir" class="<?=$valTempatLahir['warna']?>">
												Tempat Lahir
												<?
												if(!empty($valTempatLahir['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTempatLahir['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input name="reqTempatLahir" id="reqTempatLahir" class="easyui-validatebox" type="text" value="<?=$reqTempatLahir?>" />
										</div>
                                        <div class="input-field col s12 m2">
                                            <select <?=$disabled?> name="reqJenisKelamin" id="reqJenisKelamin">
                                            	<option value=""></option>
                                            	<?
                                            	foreach($arrjeniskelamin as $item) 
                                            	{
                                            		$selectvalid= $item["id"];
                                            		$selectvaltext= $item["text"];
                                            		?>
                                            		<option value="<?=$selectvalid?>" <? if($reqJenisKelamin == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                                            		<?
                                            	}
                                            	?>
                                            </select>
                                            <label for="reqJenisKelamin" class="<?=$valJenisKelamin['warna']?>">
                                            	Jenis Kelamin
                                            	<?
                                            	if(!empty($valJenisKelamin['data']))
                                            	{
                                            		?>
                                            		<a class="tooltipe" href="javascript:void(0)">
                                            			<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valJenisKelamin['data']?></span>
                                            		</a>
                                            		<?
                                            	}
                                            	?>
                                            </label>
										</div>
                                        <div class="input-field col s12 m1">
											<select <?=$disabled?> name="reqGolonganDarah" id="reqGolonganDarah">
												<option value=""></option>
												<?
												foreach($arrgoldarah as $item) 
												{
													$selectvalid= $item["id"];
													$selectvaltext= $item["text"];
													?>
													<option value="<?=$selectvalid?>" <? if($reqGolonganDarah == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
													<?
												}
												?>
											</select>
											<label class="control-label small col-md-2 text-right top_15" for="reqGolonganDarah">
												Gol/Darah
												<?
												if(!empty($valGolonganDarah['data']))
												{
													?>
													<span aria-label="<?=$valGolonganDarah['data']?>" class="tooltip-red" data-balloon-pos="up"><i class="fa fa-question-circle"></i></span>
													<?
												}
												?>
											</label>
										</div>
										<div class="input-field col s12 m2">
											<select <?=$disabled?> name="reqAgama" id="reqAgama">
												<option value=""></option>
												<?
												foreach($arragama as $item) 
												{
													$selectvalid= $item["id"];
													$selectvaltext= $item["text"];
													?>
													<option value="<?=$selectvalid?>" <? if($reqAgama == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
													<?
												}
												?>
											</select>
											<label for="reqAgama" class="<?=$valAgama['warna']?>">
												Agama
												<?
												if(!empty($valAgama['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valAgama['data']?></span>
													</a>
													<?
												}
												?>
											</label>
										</div>
										<div class="input-field col s12 m4">
											<label for="reqSukuBangsa">Suku Bangsa</label>
											<input name="reqSukuBangsa" id="reqSukuBangsa" class="easyui-validatebox" type="text" value="<?=$reqSukuBangsa?>" />
										</div>
									</div>
									<div class="row">
										<div class="input-field col s12 m12">
											<?
											if($tempLoginLevel == "99" || isStrContain(strtoupper($this->USER_GROUP), "TEKNIS MUTASI") == "1"  || ($reqId == "" && $tempAksesMenu == "A" && isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1"))
											{
											?>
											<select <?=$disabled?> name="reqJenisPegawaiId" id="reqJenisPegawaiId">
												<option value=""></option>
												<?
												foreach($arrjenispegawai as $item) 
												{
													$selectvalid= $item["id"];
													$selectvaltext= $item["text"];
													?>
													<option value="<?=$selectvalid?>" <? if($reqJenisPegawaiId == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
													<?
												}
												?>
											</select>
											<label for="reqJenisPegawaiId" class="<?=$valJenisPegawai['warna']?>">
												Jenis Pegawai
												<?
												if(!empty($valJenisPegawai['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valJenisPegawai['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<?
											}
											else
											{
											?>
											<label for="reqLabelJenisPegawai">Jenis Pegawai</label>
                                            <input id="reqLabelJenisPegawai" placeholder type="text" disabled="disabled"  value="<?=$reqJenisPegawaiNama?>" />
											<input type="hidden" name="reqJenisPegawaiId" id="reqJenisPegawaiId" value="<?=$reqJenisPegawaiId?>" />
											<?
											}
											?>
										</div>
									</div>
									<div class="row">
										<div class="input-field col s12 m12">
											<?
											if($tempLoginLevel == "99" || isStrContain(strtoupper($this->USER_GROUP), "TEKNIS MUTASI") == "1"  || ($reqId == "" && $tempAksesMenu == "A" && isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1"))
											{
											?>
                                        	<label for="reqSatuanKerja" class="<?=$valLabelSatuanKerja['warna']?>">
												Satuan Kerja
												<?
												if(!empty($valLabelSatuanKerja['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valLabelSatuanKerja['data']?></span>
													</a>
													<?
												}
												?>
											</label>

                                            <input id="reqSatuanKerja" type="text" value="<?=$reqSatuanKerjaNamaDetil?>" />
											<input type="hidden" name="reqSatuanKerjaId" id="reqSatuanKerjaId" value="<?=$reqSatuanKerjaId?>" />
											<?
											}
											else
											{
											?>
											<label for="reqLabelSatuanKerja">Satuan Kerja</label>
                                            <input id="reqLabelSatuanKerja" type="text" disabled="disabled"  value="<?=$reqSatuanKerjaNamaDetil?>" />
											<input type="hidden" name="reqSatuanKerjaId" id="reqSatuanKerjaId" value="<?=$reqSatuanKerjaId?>" />
											<?
											}
											?>
										</div>
									</div>		
								</div>

								<div id="swipe-2" class="col s12" style="height:auto !important">
									<div class="row">
										<div class="input-field col s12 m5">
											<label for="reqNik" class="<?=$valNik['warna']?>">
												NIK
												<?
												if(!empty($valNik['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNik['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input placeholder name="reqNik" class="easyui-validatebox" id="reqNik"  type="text" value="<?=$reqNik?>" />
										</div>
					                    <div class="col s12 m1">
					                      <?
						                  if($tempPathNik == "")
						                  {
						                  ?>
						                  &nbsp;
						                  <?
						                  }
						                  else
						                  {
						                  ?>
					                      <a href="<?=base_url().$tempPathNik?>" target="_new" class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
					                      <?
					                  	  }
					                  	  ?>
					                    </div>
										<div class="input-field col s12 m5">
											<label for="reqNoKk" class="<?=$valNoKk['warna']?>">
												No KK
												<?
												if(!empty($valNoKk['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNoKk['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input placeholder name="reqNoKk" class="easyui-validatebox" id="reqNoKk"  type="text"  value="<?=$reqNoKk?>" />
										</div>
					                    <div class="col s12 m1">
					                      <?
						                  if($tempPathKartuKeluarga == "")
						                  {
						                  ?>
						                  &nbsp;
						                  <?
						                  }
						                  else
						                  {
						                  ?>
					                      <a href="<?=base_url().$tempPathKartuKeluarga?>" target="_new" class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
					                      <?
					                  	  }
					                  	  ?>
					                    </div>
									</div>
									<div class="row">
										<div class="input-field col s12 m5">
											<label for="reqKartuPegawai" class="<?=$valKartuPegawai['warna']?>">
												Kartu Pegawai
												<?
												if(!empty($valKartuPegawai['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valKartuPegawai['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input name="reqKartuPegawai" id="reqKartuPegawai" class="easyui-validatebox"   type="text" value="<?=$reqKartuPegawai?>" />
										</div>
										<div class="col s12 m1">
										  <?
						                  if($tempPathKarpeg == "")
						                  {
						                  ?>
						                  &nbsp;
						                  <?
						                  }
						                  else
						                  {
						                  ?>
					                      <a href="<?=base_url().$tempPathKarpeg?>" target="_new" class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
					                      <?
					                  	  }
					                  	  ?>
					                    </div>
					                    
										<div class="input-field col s12 m5" style="display: none;">
											<label for="reqTaspen">Taspen</label>
											<input name="reqTaspen" id="reqTaspen" class="easyui-validatebox" type="text" value="<?=$reqTaspen?>" />
										</div>
					                    <div class="col s12 m1" style="display: none;">
					                      <?
						                  if($tempPathTaspen == "")
						                  {
						                  ?>
						                  &nbsp;
						                  <?
						                  }
						                  else
						                  {
						                  ?>
					                      <a href="<?=base_url().$tempPathTaspen?>" target="_new" class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
					                      <?
					                  	  }
					                  	  ?>
					                    </div>
									</div>
									<div class="row">
										<div class="input-field col s12 m5">
											<label for="reqBpjs" class="<?=$valBpjs['warna']?>">
												BPJS
												<?
												if(!empty($valBpjs['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valBpjs['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input placeholder name="reqBpjs" class="easyui-validatebox" id="reqBpjs" type="text" value="<?=$reqBpjs?>" />
										</div>
					                    <div class="col s12 m1">
					                      <?
						                  if($reqPathBpjs == "")
						                  {
						                  ?>
						                  &nbsp;
						                  <?
						                  }
						                  else
						                  {
						                  ?>
					                      <a href="<?=base_url().$reqPathBpjs?>" target="_new" class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
					                      <?
					                  	  }
					                  	  ?>
					                    </div>
					                    <div class="input-field col s12 m4">
					                    	<label for="reqBpjsTanggal" class="<?=$valBpjsTanggal['warna']?>">
												Tanggal BPJS
												<?
												if(!empty($valBpjs['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valBpjs['data']?></span>
													</a>
													<?
												}
												?>
											</label>
					                    	<input placeholder class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqBpjsTanggal" id="reqBpjsTanggal" value="<?=$reqBpjsTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqBpjsTanggal');"/>
										</div>
									</div>
									<div class="row">
										<div class="input-field col s12 m5">
											<label for="reqNpwp" class="<?=$valNpwp['warna']?>">
												NPWP
												<?
												if(!empty($valNpwp['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNpwp['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input placeholder name="reqNpwp" class="easyui-validatebox" id="reqNpwp" type="text" value="<?=$reqNpwp?>" />
										</div>
					                    <div class="col s12 m1">
					                      <?
						                  if($tempPathNpwp == "")
						                  {
						                  ?>
						                  &nbsp;
						                  <?
						                  }
						                  else
						                  {
						                  ?>
					                      <a href="<?=base_url().$tempPathNpwp?>" target="_new" class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
					                      <?
					                  	  }
					                  	  ?>
					                    </div>
					                    <div class="input-field col s12 m4">
					                    	<label for="reqNpwpTanggal" class="<?=$valNpwpTanggal['warna']?>">
												Tanggal NPWP
												<?
												if(!empty($valNpwpTanggal['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNpwpTanggal['data']?></span>
													</a>
													<?
												}
												?>
											</label>
					                    	<input placeholder class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqNpwpTanggal" id="reqNpwpTanggal" value="<?=$reqNpwpTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqNpwpTanggal');"/>
										</div>
									</div>
									<div class="row">
										<div class="input-field col s12 m5">
											<label for="reqSkKonversiNip" class="<?=$valSkKonversiNip['warna']?>">
												SK Konversi NIP
												<?
												if(!empty($valSkKonversiNip['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valSkKonversiNip['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input placeholder name="reqSkKonversiNip" id="reqSkKonversiNip" class="easyui-validatebox" type="text" value="<?=$reqSkKonversiNip?>" />
										</div>
					                    <div class="col s12 m1">
					                      <?
						                  if($tempPathSkKonversiNip == "")
						                  {
						                  ?>
						                  &nbsp;
						                  <?
						                  }
						                  else
						                  {
						                  ?>
					                      <a href="<?=base_url().$tempPathSkKonversiNip?>" target="_new" class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
					                      <?
					                  	  }
					                  	  ?>
					                    </div>
										<div class="input-field col s12 m4">
											<label for="reqUrut" class="<?=$valUrut['warna']?>">
												No Urut SK Konversi NIP
												<?
												if(!empty($valUrut['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valUrut['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input placeholder name="reqUrut" class="easyui-validatebox" id="reqUrut" type="text"  value="<?=$reqUrut?>" />
										</div>
										<div class="input-field col s12 m2">
											<label for="reqNoRakBerkas" class="<?=$valNoRakBerkas['warna']?>">
												No Rak Berkas Arsip
												<?
												if(!empty($valNoRakBerkas['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNoRakBerkas['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input placeholder name="reqNoRakBerkas" class="easyui-validatebox" id="reqNoRakBerkas"  type="text" value="<?=$reqNoRakBerkas?>" />
										</div>
									</div>
								</div>

								<div id="swipe-3" class="col s12" style="height:auto !important">
									<div class="row">
										<div class="input-field col s12 m6">
											<label for="reqPropinsi" class="<?=$valPropinsi['warna']?>">
												Propinsi
												<?
												if(!empty($valPropinsi['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valPropinsi['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input type="hidden" name="reqPropinsiId" id="reqPropinsiId" value="<?=$reqPropinsiId?>" /> 
							                <input type="text" class="easyui-validatebox" id="reqPropinsi" 
							                data-options="validType:['sameAutoLoder[\'reqPropinsi\', \'\']']"
							                value="<?=$reqPropinsi?>" />
										</div>
										<div class="input-field col s12 m6">
											<label for="reqKabupaten" class="<?=$valKabupaten['warna']?>">
												Kabupaten
												<?
												if(!empty($valKabupaten['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valKabupaten['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input type="hidden" name="reqKabupatenId" id="reqKabupatenId" value="<?=$reqKabupatenId?>" /> 
							                <input type="text" class="easyui-validatebox" id="reqKabupaten" 
							                data-options="validType:['sameAutoLoder[\'reqKabupaten\', \'\']']"
							                value="<?=$reqKabupaten?>" />
										</div>
									</div>
									<div class="row">
										<div class="input-field col s12 m6">
											<label for="reqKecamatan" class="<?=$valKecamatan['warna']?>">
												Kecamatan
												<?
												if(!empty($valKecamatan['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valKecamatan['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input type="hidden" name="reqKecamatanId" id="reqKecamatanId" value="<?=$reqKecamatanId?>" /> 
							                <input type="text" class="easyui-validatebox" id="reqKecamatan" 
							                data-options="validType:['sameAutoLoder[\'reqKecamatan\', \'\']']"
							                value="<?=$reqKecamatan?>" />
										</div>
										<div class="input-field col s12 m6">
											<label for="reqDesa" class="<?=$valDesa['warna']?>">
												Desa
												<?
												if(!empty($valDesa['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valDesa['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input type="hidden" name="reqDesaId" id="reqDesaId" value="<?=$reqDesaId?>" /> 
							                <input type="text" class="easyui-validatebox" id="reqDesa" 
							                data-options="validType:['sameAutoLoder[\'reqDesa\', \'\']']"
							                value="<?=$reqDesa?>" />
										</div>
									</div>	
									<div class="row">
										<div class="input-field col s12 m6">
											<label for="reqRt" class="<?=$valRt['warna']?>">
												RT
												<?
												if(!empty($valRt['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valRt['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input name="reqRt" id="reqRt" class="easyui-validatebox" type="text" value="<?=$reqRt?>" />
										</div>
										<div class="input-field col s12 m6">
											<label for="reqRw" class="<?=$valRw['warna']?>">
												RW
												<?
												if(!empty($valRw['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valRw['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input name="reqRw" id="reqRw" class="easyui-validatebox " type="text" value="<?=$reqRw?>" />
										</div>
									</div>	
									<div class="row">
										<div class="input-field col s12 m12">
											<label for="reqAlamat" class="<?=$valAlamat['warna']?>">
												Alamat
												<?
												if(!empty($valAlamat['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valAlamat['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input name="reqAlamat" id="reqAlamat" class="easyui-validatebox" type="text" value="<?=$reqAlamat?>" />
										</div>
									</div>
									<div class="row">
										<div class="input-field col s12 m12">
											<label for="reqAlamatKeterangan" class="<?=$valAlamatKeterangan['warna']?>">
												Keterangan
												<?
												if(!empty($valAlamatKeterangan['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valAlamatKeterangan['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input placeholder name="reqAlamatKeterangan" id="reqAlamatKeterangan" class="easyui-validatebox" type="text" value="<?=$reqAlamatKeterangan?>" />
										</div>
									</div>
								</div>

								<div id="swipe-4" class="col s12" style="height:auto !important">
									<div class="row">
										<div class="input-field col s12 m6">
											<label for="reqHp" class="<?=$valHp['warna']?>">
												No HP
												<?
												if(!empty($valHp['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valHp['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input placeholder name="reqHp" id="reqHp" class="easyui-validatebox" type="text" value="<?=$reqHp?>" />
										</div>
										<div class="input-field col s12 m6">
											<label for="reqTeleponKantor" class="<?=$valTeleponKantor['warna']?>">
												No Telepon Kantor
												<?
												if(!empty($valTeleponKantor['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTeleponKantor['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input placeholder name="reqTeleponKantor" id="reqTeleponKantor" class="easyui-validatebox" type="text" value="<?=$reqTeleponKantor?>" />
										</div>
									</div>	
									<div class="row">
										<div class="input-field col s12 m6">
											<label for="reqTelepon" class="<?=$valTelepon['warna']?>">
												No Telepon Rumah
												<?
												if(!empty($valTelepon['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTelepon['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input placeholder name="reqTelepon" id="reqTelepon" class="easyui-validatebox" type="text" value="<?=$reqTelepon?>" />
										</div>
									</div>
									<div class="row">
										<div class="input-field col s12 m6">
											<label for="reqEmail" class="<?=$valEmail['warna']?>">
												Email Pribadi
												<?
												if(!empty($valEmail['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valEmail['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input placeholder name="reqEmail" id="reqEmail" class="easyui-validatebox" type="text" value="<?=$reqEmail?>" />
										</div>
										<div class="input-field col s12 m6">
											<label for="reqEmailKantor" class="<?=$valEmailKantor['warna']?>">
												Email go.id
												<?
												if(!empty($valEmailKantor['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valEmailKantor['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input placeholder name="reqEmailKantor" id="reqEmailKantor" class="easyui-validatebox" type="text" value="<?=$reqEmailKantor?>" />
										</div>
									</div>
									<div class="row">
										<div class="input-field col s12 m6">
											<label for="reqFacebook" class="<?=$valFacebook['warna']?>">
												Facebook
												<?
												if(!empty($valFacebook['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valFacebook['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input placeholder name="reqFacebook" id="reqFacebook" class="easyui-validatebox" type="text" value="<?=$reqFacebook?>" />
										</div>
										<div class="input-field col s12 m6">
											<label for="reqTwitter" class="<?=$valTwitter['warna']?>">
												Twitter
												<?
												if(!empty($valTwitter['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTwitter['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input placeholder name="reqTwitter" id="reqTwitter" class="easyui-validatebox" type="text" value="<?=$reqTwitter?>" />
										</div>
									</div>	
									<div class="row">
										<div class="input-field col s12 m6">
											<label for="reqWhatsApp" class="<?=$valTwitter['warna']?>">
												WhatsApp
												<?
												if(!empty($valWhatsApp['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valWhatsApp['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input placeholder name="reqWhatsApp" id="reqWhatsApp" class="easyui-validatebox" type="text" value="<?=$reqWhatsApp?>" />
										</div>
										<div class="input-field col s12 m6">
											<label for="reqTelegram" class="<?=$valTelegram['warna']?>">
												Telegram
												<?
												if(!empty($valTelegram['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTelegram['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input placeholder name="reqTelegram" id="reqTelegram" class="easyui-validatebox" type="text" value="<?=$reqTelegram?>" />
										</div>
									</div>	
								</div>

								<div id="swipe-5" class="col s12" style="height:auto !important">
									<div class="row">
										<div class="input-field col s12 m3">
											<select <?=$disabled?> name="reqBank" id="reqBank">
												<option value=""></option>
												<?
												foreach($arrbank as $item) 
												{
													$selectvalid= $item["id"];
													$selectvaltext= $item["text"];
													?>
													<option value="<?=$selectvalid?>" <? if($reqBank == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
													<?
												}
												?>
											</select>
											<label for="reqBank" class="<?=$valBank['warna']?>">
												Bank
												<?
												if(!empty($valBank['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valBank['data']?></span>
													</a>
													<?
												}
												?>
											</label>
										</div>
										<div class="input-field col s12 m3">
											<label for="reqNoRekening" class="<?=$valNoRekening['warna']?>">
												No Rekening
												<?
												if(!empty($valNoRekening['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNoRekening['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input placeholder name="reqNoRekening" id="reqNoRekening" class="easyui-validatebox" type="text" value="<?=$reqNoRekening?>" />
										</div>										
										<div class="input-field col s12 m6">
											<label for="reqRekeningNama" class="<?=$valRekeningNama['warna']?>">
												Nama Pemilik Rekening (Sesuai Buku Rekening)
												<?
												if(!empty($valRekeningNama['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valRekeningNama['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input placeholder name="reqRekeningNama" id="reqRekeningNama" class="easyui-validatebox" type="text" value="<?=$reqRekeningNama?>" />
										</div>
									</div>
									<div class="row">
										<div class="input-field col s12 m3">
											<label for="reqGajiPokok" class="<?=$valGajiPokok['warna']?>">
												Gaji Pokok
												<?
												if(!empty($valGajiPokok['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valGajiPokok['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input type="text" placeholder class="easyui-validatebox" id="reqGajiPokok" name="reqGajiPokok" OnFocus="FormatAngka('reqGajiPokok')" OnKeyUp="FormatUang('reqGajiPokok')" OnBlur="FormatUang('reqGajiPokok')" value="<?=numberToIna($reqGajiPokok)?>" />
										</div>
										<div class="input-field col s12 m3">
											<label for="reqTunjanganKeluarga" class="<?=$valTunjanganKeluarga['warna']?>">
												Tunjangan Keluarga
												<?
												if(!empty($valTunjanganKeluarga['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTunjanganKeluarga['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input type="text" placeholder class="easyui-validatebox" id="reqTunjanganKeluarga" name="reqTunjanganKeluarga" OnFocus="FormatAngka('reqTunjanganKeluarga')" OnKeyUp="FormatUang('reqTunjanganKeluarga')" OnBlur="FormatUang('reqTunjanganKeluarga')" value="<?=numberToIna($reqTunjanganKeluarga)?>" />
										</div>
										<div class="input-field col s12 m3">
											<label for="reqTunjangan" class="<?=$valTunjangan['warna']?>">
												Tunjangan / Penghasilan Lainnya
												<?
												if(!empty($valTunjangan['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTunjangan['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input type="text" placeholder class="easyui-validatebox" id="reqTunjangan" name="reqTunjangan" OnFocus="FormatAngka('reqTunjangan')" OnKeyUp="FormatUang('reqTunjangan')" OnBlur="FormatUang('reqTunjangan')" value="<?=numberToIna($reqTunjangan)?>" />
										</div>
										<div class="input-field col s12 m3">
											<label for="reqGajiBersih" class="<?=$valGajiBersih['warna']?>">
												Gaji Bersih
												<?
												if(!empty($valGajiBersih['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valGajiBersih['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input type="text" placeholder class="easyui-validatebox" id="reqGajiBersih" name="reqGajiBersih" OnFocus="FormatAngka('reqGajiBersih')" OnKeyUp="FormatUang('reqGajiBersih')" OnBlur="FormatUang('reqGajiBersih')" value="<?=numberToIna($reqGajiBersih)?>" />
										</div>
									</div>
								</div>

								<div id="swipe-6" class="col s12" style="height:auto !important">
									<div class="row">
										<div class="input-field col s12 m6">
											<select <?=$disabled?> name="reqStatusMutasi" id="reqStatusMutasi">
												<option value=""></option>
												<?
												foreach($arrstatusmutasi as $item) 
												{
													$selectvalid= $item["id"];
													$selectvaltext= $item["text"];
													?>
													<option value="<?=$selectvalid?>" <? if($reqStatusMutasi == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
													<?
												}
												?>
											</select>
											<label for="reqStatusMutasi" class="<?=$valStatusMutasi['warna']?>">
												Mutasi Masuk Luar Daerah?
												<?
												if(!empty($valStatusMutasi['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valStatusMutasi['data']?></span>
													</a>
													<?
												}
												?>
											</label>
										</div>
										<div class="input-field col s12 m6 setstatusmutasi">
											<label for="reqTmtMutasi" class="<?=$valTmtMutasi['warna']?>">
												TMT Mutasi Masuk
												<?
												if(!empty($valTmtMutasi['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTmtMutasi['data']?></span>
													</a>
													<?
												}
												?>
											</label>
					                    	<input placeholder class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTmtMutasi" id="reqTmtMutasi" value="<?=$reqTmtMutasi?>" maxlength="10" onKeyDown="return format_date(event,'reqTmtMutasi');"/>
										</div>
									</div>
									<div class="row setstatusmutasi">
										<div class="input-field col s12 m12">
											<label for="reqInstansiSebelum" class="<?=$valInstansiSebelum['warna']?>">
												Instansi Sebelumnya
												<?
												if(!empty($valInstansiSebelum['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valInstansiSebelum['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input placeholder name="reqInstansiSebelum" id="reqInstansiSebelum" class="easyui-validatebox" type="text" value="<?=$reqInstansiSebelum?>" />
										</div>
									</div>
									<div class="row">
										<div class="input-field col s12 m12">
											<label for="reqKeterangan1" class="<?=$valKeterangan1['warna']?>">
												Keterangan 1
												<?
												if(!empty($valKeterangan1['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valKeterangan1['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input placeholder name="reqKeterangan1" id="reqKeterangan1" class="easyui-validatebox" type="text" value="<?=$reqKeterangan1?>" />
										</div>
									</div>
									<div class="row">
										<div class="input-field col s12 m12">
											<label for="reqKeterangan2" class="<?=$valKeterangan2['warna']?>">
												Keterangan 2
												<?
												if(!empty($valKeterangan2['data']))
												{
													?>
													<a class="tooltipe" href="javascript:void(0)">
														<i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valKeterangan2['data']?></span>
													</a>
													<?
												}
												?>
											</label>
											<input placeholder name="reqKeterangan2" id="reqKeterangan2" class="easyui-validatebox" type="text" value="<?=$reqKeterangan2?>" />
										</div>
									</div>
									<div class="row">
										<div class="input-field col s12 m12">
											<select <?=$disabled?> name="reqStatusValidasi" id="reqStatusValidasi">
												<option value="" <? if($reqStatusSkCpns == "") echo 'selected';?>></option>
												<?
												foreach($arrstatusvalidasi as $item) 
												{
													$selectvalid= $item["id"];
													$selectvaltext= $item["text"];
													?>
													<option value="<?=$selectvalid?>" <? if($reqStatusValidasi == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
													<?
												}
												?>
											</select>
											<label for="reqStatusValidasi">Status Klarifikasi</label>
										</div>
									</div>
								</div>
							</div>

								<div class="row">
									<div class="input-field col s12 m12">
										<?
										if($tempLoginLevel == "99" || ($reqId == "" && $tempAksesMenu == "A" && isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1"))
										{
										?>
										<br/><br/><br/><br/><br/><br/>
										<?
										}
										?>
										<input type="hidden" name="reqPeriode" value="<?=$reqPeriode?>" />
										<input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
										<input type="hidden" name="reqId" value="<?=$reqId?>" />
										<input type="hidden" name="reqTempValidasiId" value="<?=$reqTempValidasiId?>" />
										<input type="hidden" name="reqMode" value="<?=$reqMode?>" />

										<?
										if($tempAksesMenu == "A")
										{
											if(!empty($reqTempValidasiId))
											{
												?>
												<button class="btn waves-effect waves-light green" style="font-size:9pt" type="submit" name="action">Simpan
													<i class="mdi-content-save left hide-on-small-only"></i>
												</button>
												<?
											}
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
		$('#reqNoKk,#reqNik,#reqHp,#reqTeleponKantor,#reqTelepon').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');
		});

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

					info= "Apakah yakin untuk Laporakan an. <?=$reqNama?>?";
					reqStatusPegawaiId= reqStatusPegawaiKedudukanId= "";
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

	<style type="text/css">
	ul.dropdown-content.select-dropdown {
		/*border: 2px solid red;*/
		height: 200px !important;
		overflow: auto !important;
	}
</style>

</body>
</html>
