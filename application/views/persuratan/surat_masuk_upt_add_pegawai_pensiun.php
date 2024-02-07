<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/SuratMasukPegawai');
$this->load->model('persuratan/SuratMasukUpt');
$this->load->model('persuratan/SuratMasukPegawaiCheck');
$this->load->model('SuamiIstri');
$this->load->model('JenisKawin');
$this->load->model('Pendidikan');
$this->load->library('globalfilepegawai');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqBreadCrum= $this->input->get("reqBreadCrum");
$reqJenis= $this->input->get("reqJenis");
$reqJenisNama= setjenisinfo($reqJenis);
$reqJenisSuratRekomendasi= setjenissuratrekomendasiinfo($reqJenis);
$reqMode= $this->input->get("reqMode");

$statement= " AND A.SURAT_MASUK_UPT_ID = ".$reqId."";
$set= new SuratMasukUpt();
$set->selectByParams(array(), -1, -1, $statement);
$set->firstRow();
$reqStatusKirim= $set->getField("STATUS_KIRIM");
$reqKategori= $set->getField("KATEGORI");
$reqTahunSurat= getYear($set->getField("TANGGAL"));
unset($set);

$disabled="";
if($reqRowId=="")
{
	$reqMode = 'insert';
	$reqNamaPegawai= $reqNamaPegawai= $reqPendidikanRiwayatAkhir= $reqStatusPendidikanTerakhirNama= $reqJurusanTerakhir= $reqNoSuratKehilanganTerakhir= " ";
}
else
{
	$disabled="disabled";
	$reqMode = 'update';
	$statement= " AND SMP.SURAT_MASUK_PEGAWAI_ID = ".$reqRowId;
	$set= new SuratMasukPegawai();

	$infokategori= $reqKategori;
	$arrkhususkategori= array("dini", "udzur");
	if(in_array($reqKategori, $arrkhususkategori))
	{
		$infokategori= "";
	}
	$set->selectByParamsMonitoringPensiun(array(), -1, -1, $infokategori,$statement);
	$set->firstRow();
  	// echo $set->query;exit;

	$reqSatuanKerjaPegawaiUsulanId= $set->getField('SATUAN_KERJA_ID');
	$reqPegawaiId= $set->getField('PEGAWAI_ID');

	$reqJabatanRiwayatAkhirId= $set->getField('JABATAN_RIWAYAT_AKHIR_ID');
	$reqPendidikanRiwayatAkhirId= $set->getField('PENDIDIKAN_RIWAYAT_AKHIR_ID');
	$reqGajiRiwayatAkhirId= $set->getField('GAJI_RIWAYAT_AKHIR_ID');
	$reqPangkatRiwayatAkhirId= $set->getField('PANGKAT_RIWAYAT_AKHIR_ID');

	$reqNipBaru= $set->getField('NIP_BARU');
	$reqNamaPegawai= $set->getField('NAMA_LENGKAP');
	
	$reqPangkatRiwayatAkhir= $set->getField('PANGKAT_RIWAYAT_KODE');
	$reqSatuanKerjaNama= $set->getField('SATUAN_KERJA_PEGAWAI_USULAN_NAMA');

	$reqPangkatRiwayatAkhir= $set->getField('PANGKAT_KODE');
	// echo $reqPangkatRiwayatAkhir;exit;
	$reqPangkatRiwayatAkhirTmt= dateToPageCheck($set->getField('PANGKAT_TMT'));
	$reqPangkatRiwayatAkhirTh= $set->getField('PANGKAT_TH');
	$reqPangkatRiwayatAkhirBl= $set->getField('PANGKAT_BL');
	$reqPensiunRiwayatAkhirTmt= dateToPageCheck($set->getField('PENSIUN_TMT'));
	$reqPensiunRiwayatAkhirTmtTahun= getYear($set->getField('PENSIUN_TMT'));
	$reqPensiunRiwayatAkhirTh= $set->getField('PENSIUN_TH');
	$reqPensiunRiwayatAkhirBl= $set->getField('PENSIUN_BL');
	$reqKematianNomorSK= $set->getField('PENSIUN_NOMOR_SK');
	$reqKematianTanggalSkKematian= dateToPageCheck($set->getField('PENSIUN_TANGGAL_SK_KEMATIAN'));
	$reqKematianTanggalKematian= dateToPageCheck($set->getField('PENSIUN_TANGGAL_KEMATIAN'));
	$reqKematianKeterangan= $set->getField('PENSIUN_KETERANGAN');
	$reqJabatanEselon= $set->getField('JABATAN_ESELON');
	$reqJabatanNama= $set->getField('JABATAN_NAMA');
	$reqJabatanTmt= dateTimeToPageCheck($set->getField('JABATAN_TMT'));

	$reqKeteranganPensiun= $set->getField('KETERANGAN_PENSIUN');
}

$set= new JenisKawin();
$set->selectbyparams(array());
// echo $set->query;exit;
$arrjeniskawin=[];
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["ID"]= $set->getField("ID");
  $arrdata["TEXT"]= $set->getField("NAMA");
  array_push($arrjeniskawin, $arrdata);
}
// print_r($arrjeniskawin);exit;

$set= new Pendidikan();
$set->selectByParams(array());
// echo $set->query;exit;
$arrpendidikan=[];
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["ID"]= $set->getField("PENDIDIKAN_ID");
  $arrdata["TEXT"]= $set->getField("NAMA");
  array_push($arrpendidikan, $arrdata);
}
// print_r($arrpendidikan);exit;

if(empty($reqPegawaiId))
	$statement= " AND A.PEGAWAI_ID = -1";
else
	$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
$statement.= " AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')";
$set= new SuamiIstri();
$set->selectByParams(array(), -1, -1, $statement);
// echo $set->query;exit;
$arrsuamiistri=[];
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["ID"]= $set->getField("SUAMI_ISTRI_ID");
  $arrdata["TEXT"]= $set->getField("NAMA");
  array_push($arrsuamiistri, $arrdata);
}

// $reqNipBaru= "196305301985041001";
// $reqNipBaru= "196705201986101001";
// $reqNipBaru= "196907242000121001";
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

<script type="text/javascript" src='lib/datepickernew/jquery-1.8.3.min.js'></script>
<script type="text/javascript" src='lib/datepickernew/bootstrap.min.js'></script>
<link rel="stylesheet" href='lib/datepickernew/bootstrap.min.css' media="screen" />
<link rel="stylesheet" href="lib/datepickernew/bootstrap-datepicker.css" type="text/css" />
<script src="lib/datepickernew/bootstrap-datepicker.js" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
<!-- <script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script> -->
<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

<link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
<script src="lib/autokomplit/jquery-ui.js"></script>

<script type="text/javascript"> 
$(function(){
	$(".preloader-wrapper").hide();

	$("#reqsimpan").click(function() { 
      if($("#ff").form('validate') == false){
        return false;
      }
      
      var s_url= "surat/surat_masuk_pegawai_json/cek_kirim_upt?reqId=<?=$reqId?>";
      $.ajax({'url': s_url,'success': function(dataajax){
        var requrl= requrllist= "";
        dataajax= String(dataajax);
        if(dataajax == '1')
        {
          mbox.alert('Data sudah dikirim', {open_speed: 0});
          return false;
        }
        else
        $("#reqSubmit").click();
      }});
    });

	$('#ff').form({
		url:'surat/surat_masuk_pegawai_json/add_pensiun',
		onSubmit:function(){
			kondisigagalsimpan= "";
			$('[id^="reqAnakSuamiIstriId"]').each(function(){
				vinfoid= $(this).attr('id');
				infoval= $(this).val();
				if(infoval == "" && kondisigagalsimpan == "")
				{
					kondisigagalsimpan= "1";
				}
			});
			if(kondisigagalsimpan == "1")
			{
				mbox.alert('Data tidak bisa disimpan, karena Nama Orang tua Anak belum diisi.', {open_speed: 0});
				return false;
			}

			kondisigagalsimpan= "";
			$('[id^="reqAnakPendidikanId"]').each(function(){
				vinfoid= $(this).attr('id');
				infoval= $(this).val();
				if(infoval == "" && kondisigagalsimpan == "")
				{
					kondisigagalsimpan= "1";
				}
			});
			if(kondisigagalsimpan == "1")
			{
				mbox.alert('Data tidak bisa disimpan, karena Pendidikan terakhir Anak belum diisi.', {open_speed: 0});
				return false;
			}

			kondisigagalsimpan= "";
			$('[id^="reqAnakJenisKawinId"]').each(function(){
				vinfoid= $(this).attr('id');
				infoval= $(this).val();
				if(infoval == "" && kondisigagalsimpan == "")
				{
					kondisigagalsimpan= "1";
				}
			});
			if(kondisigagalsimpan == "1")
			{
				mbox.alert('Data tidak bisa disimpan, karena Status Pernikahan Anak belum diisi.', {open_speed: 0});
				return false;
			}

			kondisigagalsimpan= "";
			$('[id^="reqAnakStatusKeluarga"]').each(function(){
				vinfoid= $(this).attr('id');
				infoval= $(this).val();
				if(infoval == "" && kondisigagalsimpan == "")
				{
					kondisigagalsimpan= "1";
				}
			});
			if(kondisigagalsimpan == "1")
			{
				mbox.alert('Data tidak bisa disimpan, karena Status Keluarga Anak belum diisi.', {open_speed: 0});
				return false;
			}
			
			var reqPegawaiId= "";
			reqPegawaiId= $("#reqPegawaiId").val();

			if(reqPegawaiId == "")
			{
				mbox.alert("Lengkapi data terlebih dahulu", {open_speed: 0});
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
					document.location.href= "app/loadUrl/persuratan/surat_masuk_upt_add_pegawai_pensiun/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqRowId="+rowid;
				}, 1000));
				$(".mbox > .right-align").css({"display": "none"});
			}

		}
	});

	$('input[id^="reqNipBaru"]').autocomplete({
		source:function(request, response){

			var id= this.element.attr('id');
			var replaceAnakId= replaceAnak= urlAjax= "";

			if (id.indexOf('reqNipBaru') !== -1)
			{
				urlAjax= "pendidikan_riwayat_json/cari_pegawai_pensiun_usulan?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqKategori=<?=$reqKategori?>";
			}

			// cegah 10 karakter baru bisa cari
			valcari= request.term;
			panjangcari= valcari.length;
			if(panjangcari < 10) return false;
			// console.log(panjangcari);

			$(".preloader-wrapper").show();

			$.ajax({
				url: urlAjax,
				type: "GET",
				dataType: "json",
				data: { term: request.term },
				success: function(responseData){
					$(".preloader-wrapper").hide();

					if(responseData == null)
					{
						response(null);
					}
					else
					{
						var array = responseData.map(function(element) {
							return {desc: element['desc'], id: element['id'], label: element['label'], namapegawai: element['namapegawai']
							, satuankerjaid: element['satuankerjaid'], satuankerjanama: element['satuankerjanama']
							, pangkatkode: element['pangkatkode'], pangkattmt: element['pangkattmt'], pangkatth: element['pangkatth']
							, pangkatbl: element['pangkatbl'], pensiuntmt: element['pensiuntmt'], pensiuntmttahun: element['pensiuntmttahun'], pensiunth: element['pensiunth']
							, pensiunbl: element['pensiunbl'], pensiuntanggalkematian: element['pensiuntanggalkematian'], pensiunnomorsk: element['pensiunnomorsk']
							, pensiuntanggalskkematian: element['pensiuntanggalskkematian'], pensiunketerangan: element['pensiunketerangan'], jabataneselon: element['jabataneselon']
							, jabatannama: element['jabatannama'], jabatantmt: element['jabatantmt']
							, jabatanriwayatid: element['jabatanriwayatid'], pendidikanriwayatid: element['pendidikanriwayatid']
			  				, gajiriwayatid: element['gajiriwayatid'], pangkatriwayatid: element['pangkatriwayatid']
							};
						});
						response(array);
					}
				}
			})
		},
		select: function (event, ui) 
		{ 
			var id= $(this).attr('id');
			if (id.indexOf('reqNipBaru') !== -1)
			{
				var indexId= "reqPegawaiId";
				namapegawai= ui.item.namapegawai;
				satuankerjaid= ui.item.satuankerjaid;
				satuankerjanama= ui.item.satuankerjanama;
				pangkatkode= ui.item.pangkatkode;
				pangkattmt= ui.item.pangkattmt;
				pangkatth= ui.item.pangkatth;
				pangkatbl= ui.item.pangkatbl;
				pensiuntmt= ui.item.pensiuntmt;
				// pensiuntmttahun= ui.item.pensiuntmttahun;
				pensiuntmttahun= "<?=$reqTahunSurat?>";
				pensiunth= ui.item.pensiunth;
				pensiunbl= ui.item.pensiunbl;
				pensiuntanggalkematian= ui.item.pensiuntanggalkematian;
				pensiunnomorsk= ui.item.pensiunnomorsk;
				pensiuntanggalskkematian= ui.item.pensiuntanggalskkematian;
				pensiunketerangan= ui.item.pensiunketerangan;
				jabataneselon= ui.item.jabataneselon;
				jabatannama= ui.item.jabatannama;
				jabatantmt= ui.item.jabatantmt;

				jabatanriwayatid= ui.item.jabatanriwayatid;
				pendidikanriwayatid= ui.item.pendidikanriwayatid;
				gajiriwayatid= ui.item.gajiriwayatid;
				pangkatriwayatid= ui.item.pangkatriwayatid;

				$("#reqJabatanRiwayatAkhirId").val(jabatanriwayatid);
				$("#reqPendidikanRiwayatAkhirId").val(pendidikanriwayatid);
				$("#reqGajiRiwayatAkhirId").val(gajiriwayatid);
				$("#reqPangkatRiwayatAkhirId").val(pangkatriwayatid);
		
				$("#reqNamaPegawai").val(namapegawai);
				$("#reqSatuanKerjaPegawaiUsulanId").val(satuankerjaid);
				$("#reqSatuanKerjaNama").val(satuankerjanama);
				$("#reqPangkatRiwayatAkhir").val(pangkatkode);
				$("#reqPangkatRiwayatAkhirTmt").val(pangkattmt);
				$("#reqPangkatRiwayatAkhirTh").val(pangkatth);
				$("#reqPangkatRiwayatAkhirBl").val(pangkatbl);
				$("#reqPensiunRiwayatAkhirTmt").val(pensiuntmt);
				$("#reqPensiunRiwayatAkhirTh").val(pensiunth);
				$("#reqPensiunRiwayatAkhirBl").val(pensiunbl);
				$("#reqKematianNomorSK").val(pensiunnomorsk);
				$("#reqKematianTanggalSkKematian").val(pensiuntanggalskkematian);
				$("#reqKematianTanggalKematian").val(pensiuntanggalkematian);
				$("#reqKematianKeterangan").val(pensiunketerangan);
				$("#reqJabatanEselon").val(jabataneselon);
				$("#reqJabatanNama").val(jabatannama);
				$("#reqJabatanTmt").val(jabatantmt);
				$("#reqSatuanKerjaNama").val(satuankerjanama);

				$('#labeldetilinfo').empty();

				$(".tanggalentri").hide();
				$("#reqTmtPensiun").val();
				reqKategori= "<?=$reqKategori?>";

				$('#reqTmtPensiun').validatebox({required: false});
				$('#reqTmtPensiun').removeClass('validatebox-invalid');
				if(reqKategori == "dini" || reqKategori == "udzur")
				{
					$(".tanggalentri").show();
					$("#reqPensiunRiwayatAkhirTmt").hide();
					$('#reqTmtPensiun').validatebox({required: true});

					$("#reqTmtPensiun, #reqPensiunRiwayatAkhirTh, #reqPensiunRiwayatAkhirBl").val("");
				}

				vurl= "app/loadUrl/persuratan/surat_masuk_upt_add_pegawai_pensiun_anak/?reqId=<?=$reqId?>&reqKategori=<?=$reqKategori?>&reqPegawaiId="+ui.item.id+"&reqPensiunTmtTahun="+pensiuntmttahun;
				$.ajax({
					'url': vurl
					, beforeSend: function () {
						$(".preloader-wrapper").show();
					}
					,'success': function(datahtml) {
						$(".preloader-wrapper").hide();
						$('#labeldetilinfo').append(datahtml);
						if (window.parent && window.parent.document)
						{
							if (typeof window.parent.iframeLoaded === 'function')
							{
								parent.iframeLoaded();
							}
						}
					}
				});

			}

			$("#"+indexId).val(ui.item.id).trigger('change');
		},
		autoFocus: true
	}).autocomplete( "instance" )._renderItem = function( ul, item ) {
	//
	return $( "<li>" )
	.append( "<a>" + item.desc + "</a>" )
	.appendTo( ul );
	};

});

</script>

<!-- CORE CSS-->    
<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<!-- CSS style Horizontal Nav-->    
<link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
<!-- Custome CSS-->    
<link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

<link href="lib/mbox/mbox.css" rel="stylesheet">
<script src="lib/mbox/mbox.js"></script>
<link href="lib/mbox/mbox-modif.css" rel="stylesheet">

</head>

<body>    
  <!--Basic Form-->
  <div id="basic-form" class="section">
    <div class="row">
     <div class="col s12 m12" style="padding-left: 15px;">

       <ul class="collection card">
         <li class="collection-item ubah-color-warna">Pegawai Usul <?=$reqJenisNama?></li>
         <li class="collection-item">

          <div class="row">
            <form id="ff" method="post" enctype="multipart/form-data" novalidate>
            	<div class="row">
					<div class="input-field col s12 m6">
						<label for="reqNipBaru">NIP Baru</label>
						<?
						if($reqRowId == "")
						{
						?>
							<input required placeholder="" id="reqNipBaru" class="easyui-validatebox" type="text"  value="<?=$reqNipBaru?>" />
						<?
						}
						else
						{
						?>
							<input id="reqNipBaru" type="hidden" value="<?=$reqNipBaru?>" />
							<input required type="text"  value="<?=$reqNipBaru?>" disabled />
						<?
						}
						?>
					</div>
					<?
					if($reqRowId == "")
					{
					?>
						<div class="input-field col s12 m6" id="reqKeteranganInfoDetil">
						</div>
					<?
					}
					?>
				</div>

				<div class="row">
					<div class="input-field col s12">
						<label for="reqNamaPegawai" class="active">Nama Pegawai</label>
						<input placeholder="" id="reqNamaPegawai" class="easyui-validatebox" type="text"  value="<?=$reqNamaPegawai?>" disabled />
					</div>
				</div>

				<div class="row">
					<div class="input-field col s12 m3">
						<label for="reqPangkatRiwayatAkhir">Gol Terakhir</label>
						<input placeholder="" type="text" id="reqPangkatRiwayatAkhir" value="<?=$reqPangkatRiwayatAkhir?>" disabled />
					</div>
					<div class="input-field col s12 m2">
						<label for="reqPangkatRiwayatAkhirTmt">TMT Akhir</label>
						<input placeholder="" readonly class="color-disb" type="text" name="reqPangkatRiwayatAkhirTmt" id="reqPangkatRiwayatAkhirTmt" value="<?=$reqPangkatRiwayatAkhirTmt?>" />
					</div>
					<div class="input-field col s12 m2">
						<label for="reqPangkatRiwayatAkhirTh">MK Tahun</label>
						<input placeholder="" type="text" disabled class="easyui-validatebox" name="reqPangkatRiwayatAkhirTh" id="reqPangkatRiwayatAkhirTh" value="<?=$reqPangkatRiwayatAkhirTh?> " />
					</div>
					<div class="input-field col s12 m2">
						<label for="reqPangkatRiwayatAkhirBl">MK Bulan</label>
						<input placeholder="" type="text" class="easyui-validatebox" disabled name="reqPangkatRiwayatAkhirBl" id="reqPangkatRiwayatAkhirBl" value="<?=$reqPangkatRiwayatAkhirBl?> " />
					</div>
				</div>

				<?
				if($reqKategori == "meninggal")
				{
				?>
					<div class="row">
						<div class="input-field col s12 m3">
							<label for="reqKematianNomorSK">Nomor Surat Kematian</label>
							<input placeholder="" type="text" readonly class="color-disb" name="reqKematianNomorSK" id="reqKematianNomorSK" value="<?=$reqKematianNomorSK?>" />
						</div>
						<div class="input-field col s12 m2">
							<label for="reqKematianTanggalSkKematian">Tanggal SK Kematian</label>
							<input placeholder="" readonly class="color-disb" type="text" name="reqKematianTanggalSkKematian" id="reqKematianTanggalSkKematian"  value="<?=$reqKematianTanggalSkKematian?>" />
						</div>

						<div class="input-field col s12 m2">
							<label for="reqKematianTanggalKematian">Tanggal Kematian</label>
							<input placeholder="" readonly class="color-disb" type="text" name="reqKematianTanggalKematian" id="reqKematianTanggalKematian"  value="<?=$reqKematianTanggalKematian?>" />
						</div>
					</div>
				<?
				}
				?>

				<?
				// $arrkhususkategori= array("dini", "udzur");
				$reqTmtPensiunDisplay= "none";
				// if(!in_array($reqKategori, $arrkategori))
				// {
				// 	$reqTmtPensiunDisplay= "";
				// }
				?>
				<div class="row">
					<div class="input-field col s12 m3">
						<label for="reqPensiunRiwayatAkhir"></label>
					</div>
					<div class="input-field col s12 m2">
						<label for="reqPensiunRiwayatAkhirTmt">TMT Pensiun</label>
						<input placeholder="" readonly class="color-disb" type="text" name="reqPensiunRiwayatAkhirTmt" id="reqPensiunRiwayatAkhirTmt" value="<?=$reqPensiunRiwayatAkhirTmt?>" />
						<table style="display: <?=$reqTmtPensiunDisplay?>" class="tanggalentri">
							<tr> 
								<td style="padding: 0px;">
									<input <?=$disabled?> placeholder="" class="xcolor-disb easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTmtPensiun" id="reqTmtPensiun" value="" maxlength="10" onKeyDown="return format_date(event,'reqTmtPensiun');" />
								</td>
								<td style="padding: 0px;">
									<label class="input-group-btn" for="reqTmtPensiun" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
										<span class="mdi-notification-event-note"></span>
									</label>
								</td>
							</tr>
						</table>
						
					</div>
					<div class="input-field col s12 m2">
						<label for="reqPensiunRiwayatAkhirTh">MK Tahun</label>
						<input placeholder="" type="text" disabled class="easyui-validatebox" name="reqPensiunRiwayatAkhirTh" id="reqPensiunRiwayatAkhirTh" value="<?=$reqPensiunRiwayatAkhirTh?> " />
					</div>
					<div class="input-field col s12 m2">
						<label for="reqPensiunRiwayatAkhirBl">MK Bulan</label>
						<input placeholder="" type="text" class="easyui-validatebox" disabled name="reqPensiunRiwayatAkhirBl" id="reqPensiunRiwayatAkhirBl" value="<?=$reqPensiunRiwayatAkhirBl?> " />
					</div>
				</div>

				<div class="row">
					<div class="input-field col s12 m3">
						<label for="reqJabatanEselon">Eselon</label>
						<input placeholder="" type="text" id="reqJabatanEselon" value="<?=$reqJabatanEselon?>" disabled />
					</div>
					<div class="input-field col s12 m6">
						<label for="reqJabatanNama">Jabatan</label>
						<input placeholder="" type="text" id="reqJabatanNama" value="<?=$reqJabatanNama?>" disabled />
					</div>
					<div class="input-field col s12 m3">
						<label for="reqJabatanTmt">Tmt Jabatan</label>
						<input placeholder="" type="text" id="reqJabatanTmt" value="<?=$reqJabatanTmt?>" disabled />
					</div>
				</div>
				<div class="row">
					<div class="input-field col s12 m12">
						<label for="reqSatuanKerjaNama">Satuan Kerja</label>
						<input placeholder="" type="text" id="reqSatuanKerjaNama" value="<?=$reqSatuanKerjaNama?>" disabled />
					</div>
				</div>

				<div id="labeldetilinfo">
					<?
					if(!empty($reqRowId))
					{
					?>
					<div class="row">
						<div class="input-field col s12 m12">
							Jika ada data Suami/istri belum ada di formulir usulan, silakan update di riwayat Suami/istri PNS yang bersangkutan. Silakan <a href="javascript:void();" style="text-decoration: none;" onclick="reloadpelayananriwayat('pegawai_add_suami_istri_monitoring', 'surat_masuk_upt_add_pegawai_pensiun')">klik disini.</a>

							<!-- infolink, infopegawaiid, infoid, infojenis, inforowid

							reqPegawaiId -->
						</div>
					</div>
					<?
						$nomor=1;
						$sOrder= " ORDER BY A.TANGGAL_KAWIN ";
						$statement= " AND (COALESCE(NULLIF(A.STATUS::TEXT, ''), NULL) IS NULL OR A.STATUS = '2') AND A.PEGAWAI_ID = ".$reqPegawaiId;
						$suamiistri= new SuratMasukPegawai();
						$suamiistri->selectByParamsSuamiIstri(array(), -1, -1, $statement, $sOrder);
						// echo $suamiistri->query;exit;
						while($suamiistri->nextRow())
						{
							$reqSuamiIstriId= $suamiistri->getField("SUAMI_ISTRI_ID");
							$reqSuamiIstriNama= $suamiistri->getField("NAMA");
							$reqSuamiIstriTanggalLahir= dateToPageCheck($suamiistri->getField("TANGGAL_LAHIR"));
							$reqSuamiIstriStatusNama= $suamiistri->getField("STATUS_S_I_NAMA");
							$reqSuamiIstriTanggalKawin= dateToPageCheck($suamiistri->getField("TANGGAL_KAWIN"));
							$reqSuamiIstriStatusSi= $suamiistri->getField("STATUS_S_I");
							$reqSuamiIstriTanggalCerai= dateToPageCheck($suamiistri->getField("CERAI_TMT"));
							$reqSuamiIstriTanggalKematian= dateToPageCheck($suamiistri->getField("KEMATIAN_TMT"));

							$reqSuamiIstriStatusHidup= $suamiistri->getField("STATUS_AKTIF");
							$reqSuamiIstriSuratNikah= $suamiistri->getField("SURAT_NIKAH");
							$reqSuamiIstriAktaNikahTanggal= dateToPageCheck($suamiistri->getField("AKTA_NIKAH_TANGGAL"));

							$reqSuamiIstriCeraiSurat= $suamiistri->getField("CERAI_SURAT");
							$reqSuamiIstriCeraiTanggal= dateToPageCheck($suamiistri->getField("CERAI_TANGGAL"));
							$reqSuamiIstriCeraiTmt= dateToPageCheck($suamiistri->getField("CERAI_TMT"));
							$reqSuamiIstriKematianNo= $suamiistri->getField('KEMATIAN_NO');
							$reqSuamiIstriKematianSurat= $suamiistri->getField("KEMATIAN_SURAT");
							$reqSuamiIstriKematianTanggal= dateToPageCheck($suamiistri->getField("KEMATIAN_TANGGAL"));
							$reqSuamiIstriKematianTmt= dateToPageCheck($suamiistri->getField("KEMATIAN_TMT"));
							$reqSuamiIstriTanggalMeninggal= dateToPageCheck($suamiistri->getField("TANGGAL_MENINGGAL"));

							$reqSuamiIstriAktaNikahNo= $suamiistri->getField('AKTA_NIKAH_NO');
							$reqSuamiIstriAktaNikahTanggal= dateToPageCheck($suamiistri->getField('AKTA_NIKAH_TANGGAL'));
							$reqSuamiIstriNikahTanggal= dateToPageCheck($suamiistri->getField('NIKAH_TANGGAL'));
							$reqSuamiIstriAktaCeraiNo= $suamiistri->getField('AKTA_CERAI_NO');
							$reqSuamiIstriAktaCeraiTanggal= dateToPageCheck($suamiistri->getField('AKTA_CERAI_TANGGAL'));
							$reqSuamiIstriCeraiTanggal= dateToPageCheck($suamiistri->getField('CERAI_TANGGAL'));
					?>
							<div class="row">
								<div class="input-field col s12 m3">
									<label for="reqSuamiIstriNama<?=$nomor?>">Nama Suami Istri</label>
									<input placeholder="" type="text" id="reqSuamiIstriNama<?=$nomor?>" value="<?=$reqSuamiIstriNama?>" disabled />
									<input type="hidden" name="reqSuamiIstriId[]" value="<?=$reqSuamiIstriId?>" />
								</div>
								<div class="input-field col s12 m1">
									<label for="reqSuamiIstriTanggalLahir<?=$nomor?>">Tanggal Lahir</label>
									<input placeholder="" readonly type="text" class="color-disb" name="reqSuamiIstriTanggalLahir[]" id="reqSuamiIstriTanggalLahir<?=$nomor?>" value="<?=$reqSuamiIstriTanggalLahir?> " />
								</div>
								<div class="input-field col s12 m2">
									<select <?=$disabled?> name="reqSuamiIstriStatusHidup[]" id="reqSuamiIstriStatusHidup<?=$nomor?>">
										<option value="1" <? if($reqSuamiIstriStatusHidup == 1) echo 'selected';?>>Hidup</option>
										<option value="2" <? if($reqSuamiIstriStatusHidup == 2) echo 'selected';?>>Wafat</option>
									</select>
									<label for="reqSuamiIstriStatusHidup<?=$nomor?>">Status Hidup</label>
								</div>

								<?
								$reqSuamiIstriStatusHidupDisplay= "none";
								$reqSuamiIstriStatusHidupValidasi= "";
								if($reqSuamiIstriStatusHidup == "2")
								{
									$reqSuamiIstriStatusHidupDisplay= "";
									$reqSuamiIstriStatusHidupValidasi= "required";
								}
								?>
								<div style="display: <?=$reqSuamiIstriStatusHidupDisplay?>" class="input-field col s12 m2 reqSuamiIstriLabelTanggalMeninggal<?=$nomor?>">
									<label for="reqSuamiIstriKematianNo<?=$nomor?>">Surat Ket. Kematian</label>
									<input <?=$disabled?> <?=$reqSuamiIstriStatusHidupValidasi?> placeholder="" type="text" class="easyui-validatebox" name="reqSuamiIstriKematianNo[]" id="reqSuamiIstriKematianNo<?=$nomor?>" <?=$read?> value="<?=$reqSuamiIstriKematianNo?>" />
								</div>
								<div style="display: <?=$reqSuamiIstriStatusHidupDisplay?>" class="input-field col s12 m2 reqSuamiIstriLabelTanggalMeninggal<?=$nomor?>">
									<label class="active" for="reqSuamiIstriKematianTanggal<?=$nomor?>">Tanggal Surat Kematian</label>
									<table>
										<tr> 
											<td style="padding: 0px;">
												<input <?=$disabled?> <?=$reqSuamiIstriStatusHidupValidasi?> placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqSuamiIstriKematianTanggal[]" id="reqSuamiIstriKematianTanggal<?=$nomor?>" value="<?=$reqSuamiIstriKematianTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqSuamiIstriKematianTanggal<?=$nomor?>');" />
											</td>
											<td style="padding: 0px;">
												<label class="input-group-btn" for="reqSuamiIstriKematianTanggal<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
													<span class="mdi-notification-event-note"></span>
												</label>
											</td>
										</tr>
									</table>
								</div>

								<div style="display: <?=$reqSuamiIstriStatusHidupDisplay?>" class="input-field col s12 m2 reqSuamiIstriLabelTanggalMeninggal<?=$nomor?>">
									<label class="active" for="reqSuamiIstriTanggalMeninggal<?=$nomor?>">Tanggal Meninggal</label>
									<table>
										<tr> 
											<td style="padding: 0px;">
												<input <?=$disabled?> <?=$reqSuamiIstriStatusHidupValidasi?> placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqSuamiIstriTanggalMeninggal[]" id="reqSuamiIstriTanggalMeninggal<?=$nomor?>" value="<?=$reqSuamiIstriTanggalMeninggal?>" maxlength="10" onKeyDown="return format_date(event,'reqSuamiIstriTanggalMeninggal<?=$nomor?>');" />
											</td>
											<td style="padding: 0px;">
												<label class="input-group-btn" for="reqSuamiIstriTanggalMeninggal<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
													<span class="mdi-notification-event-note"></span>
												</label>
											</td>
										</tr>
									</table>
								</div>
							</div>
							<?
							$suamiistrilabelceraidisplay= "none";
							$suamiistrilabelcerairequired= "";
							if($reqSuamiIstriStatusSi == "2")
							{
								$suamiistrilabelceraidisplay= "";
								$suamiistrilabelcerairequired= "required";
							}
							?>
							<div class="row">
								<div class="input-field col s12 m2">
									<select <?=$disabled?> name="reqSuamiIstriStatusSi[]" id="reqSuamiIstriStatusSi<?=$nomor?>">
										<option value=""></option>
										<?
						                foreach ($arrjeniskawin as $key => $value)
						                {
						                  $optionid= $value["ID"];
						                  $optiontext= $value["TEXT"];
						                  $optionselected= "";
						                  if($reqSuamiIstriStatusSi == $optionid)
						                    $optionselected= "selected";
						                ?>
						                  <option value="<?=$optionid?>" <?=$optionselected?>><?=$optiontext?></option>
						                <?
						                }
						                ?>
									</select>
									<label for="reqSuamiIstriStatusSi<?=$nomor?>">Status Pernikahan</label>
								</div>
								<div class="input-field col s12 m2">
									<label for="reqSuamiIstriSuratNikah<?=$nomor?>">Surat Nikah</label>
									<input <?=$disabled?> placeholder="" type="text" class="xcolor-disb" name="reqSuamiIstriSuratNikah[]" id="reqSuamiIstriSuratNikah<?=$nomor?>" value="<?=$reqSuamiIstriSuratNikah?> " />
								</div>
								<div class="input-field col s12 m2">
									<label class="active" for="reqSuamiIstriTanggalKawin<?=$nomor?>">Tanggal Nikah</label>
									<table>
										<tr> 
											<td style="padding: 0px;">
												<input <?=$disabled?> placeholder="" class="xcolor-disb easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqSuamiIstriTanggalKawin[]" id="reqSuamiIstriTanggalKawin<?=$nomor?>" value="<?=$reqSuamiIstriTanggalKawin?>" maxlength="10" onKeyDown="return format_date(event,'reqSuamiIstriTanggalKawin<?=$nomor?>');" />
											</td>
											<td style="padding: 0px;">
												<label class="input-group-btn" for="reqSuamiIstriTanggalKawin<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
													<span class="mdi-notification-event-note"></span>
												</label>
											</td>
										</tr>
									</table>
								</div>
								<div class="input-field col s12 m2">
									<label class="active" for="reqSuamiIstriAktaNikahTanggal<?=$nomor?>">Tanggal Akta Nikah</label>
									<table>
										<tr> 
											<td style="padding: 0px;">
												<input <?=$disabled?> placeholder="" class="xcolor-disb easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqSuamiIstriAktaNikahTanggal[]" id="reqSuamiIstriAktaNikahTanggal<?=$nomor?>" value="<?=$reqSuamiIstriAktaNikahTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqSuamiIstriAktaNikahTanggal<?=$nomor?>');" />
											</td>
											<td style="padding: 0px;">
												<label class="input-group-btn" for="reqSuamiIstriAktaNikahTanggal<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
													<span class="mdi-notification-event-note"></span>
												</label>
											</td>
										</tr>
									</table>
								</div>
							</div>
							<div class="row">
								<div style="display: none;" class="input-field col s12 m3 suamiistrilabelnikah<?=$nomor?>">
									<label for="reqSuamiIstriAktaNikahNo<?=$nomor?>">No Akta Nikah</label>
									<input <?=$disabled?> placeholder="" type="text" class="easyui-validatebox" name="reqSuamiIstriAktaNikahNo[]" id="reqSuamiIstriAktaNikahNo<?=$nomor?>" <?=$read?> value="<?=$reqSuamiIstriAktaNikahNo?>" />
								</div>

								<div style="display: none;" class="input-field col s12 m3 suamiistrilabelnikah<?=$nomor?>">
								</div>

								<div style="display: none;" class="input-field col s12 m2 suamiistrilabelnikah<?=$nomor?>">
									<label class="active" for="reqSuamiIstriNikahTanggal<?=$nomor?>">Tanggal Nikah</label>
									<table>
										<tr> 
											<td style="padding: 0px;">
												<input <?=$disabled?> placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqSuamiIstriNikahTanggal[]" id="reqSuamiIstriNikahTanggal<?=$nomor?>" value="<?=$reqSuamiIstriNikahTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqSuamiIstriNikahTanggal<?=$nomor?>');" />
											</td>
											<td style="padding: 0px;">
												<label class="input-group-btn" for="reqSuamiIstriNikahTanggal<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
													<span class="mdi-notification-event-note"></span>
												</label>
											</td>
										</tr>
									</table>
								</div>

								<div style="display: <?=$suamiistrilabelceraidisplay?>" class="input-field col s12 m4 suamiistrilabelcerai<?=$nomor?>">
									<label for="reqSuamiIstriAktaCeraiNo<?=$nomor?>">Surat Pengadilan / Cerai</label>
									<input <?=$disabled?> <?=$suamiistrilabelcerairequired?> placeholder="" type="text" class="easyui-validatebox" name="reqSuamiIstriAktaCeraiNo[]" id="reqSuamiIstriAktaCeraiNo<?=$nomor?>" <?=$read?> value="<?=$reqSuamiIstriAktaCeraiNo?>" />
								</div>
								<div style="display: <?=$suamiistrilabelceraidisplay?>" class="input-field col s12 m2 suamiistrilabelcerai<?=$nomor?>">
									<label class="active" for="reqSuamiIstriAktaCeraiTanggal<?=$nomor?>">Tanggal Akta Cerai</label>
									<table>
										<tr> 
											<td style="padding: 0px;">
												<input <?=$disabled?> <?=$suamiistrilabelcerairequired?> placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqSuamiIstriAktaCeraiTanggal[]" id="reqSuamiIstriAktaCeraiTanggal<?=$nomor?>" value="<?=$reqSuamiIstriAktaCeraiTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqSuamiIstriAktaCeraiTanggal<?=$nomor?>');" />
											</td>
											<td style="padding: 0px;">
												<label class="input-group-btn" for="reqSuamiIstriAktaCeraiTanggal<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
													<span class="mdi-notification-event-note"></span>
												</label>
											</td>
										</tr>
									</table>
								</div>
								<div style="display: <?=$suamiistrilabelceraidisplay?>" class="input-field col s12 m2 suamiistrilabelcerai<?=$nomor?>">
									<label class="active" for="reqSuamiIstriCeraiTanggal">Tanggal Cerai</label>
									<table>
										<tr> 
											<td style="padding: 0px;">
												<input <?=$disabled?> <?=$suamiistrilabelcerairequired?> placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqSuamiIstriCeraiTanggal[]" id="reqSuamiIstriCeraiTanggal<?=$nomor?>" value="<?=$reqSuamiIstriCeraiTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqSuamiIstriCeraiTanggal<?=$nomor?>');" />
											</td>
											<td style="padding: 0px;">
												<label class="input-group-btn" for="reqSuamiIstriCeraiTanggal<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
													<span class="mdi-notification-event-note"></span>
												</label>
											</td>
										</tr>
									</table>
								</div>
							</div>
					<?
							$nomor++;
						}
					?>

						<div class="row">
							<div class="input-field col s12 m12">
								Jika ada data anak belum ada di formulir usulan, silakan update di riwayat anak PNS yang bersangkutan. Silakan <a href="javascript:void();" style="text-decoration: none;" onclick="reloadpelayananriwayat('pegawai_add_anak_monitoring', 'surat_masuk_upt_add_pegawai_pensiun')">klik disini.</a>
							</div>
						</div>
						<?
						$nomor=1;
						$statement= " AND A.NOMOR <= 5";
						$set= new SuratMasukPegawai();
						$set->selectByParamsAnak($reqPegawaiId, $reqKategori, $statement);
						// echo $set->query;exit;
						$nomor=1;
						while($set->nextRow())
						{
							$reqAnakId= $set->getField("ANAK_ID");
							$reqAnakNama= $set->getField("NAMA");
							$reqAnakUsia= $set->getField("ANAK_USIA");
							$reqAnakTanggalLahir= dateToPageCheck($set->getField("TANGGAL_LAHIR"));
							$reqAnakStatusNama= $set->getField("ANAK_STATUS_NAMA");
							$reqSuamiIstriNama= $set->getField("SUAMI_ISTRI_NAMA");

							$reqAnakSuamiIstriId= $set->getField("SUAMI_ISTRI_ID");
							$reqAnakStatusKeluarga= $set->getField("STATUS_KELUARGA");
							$reqAnakPendidikanId= $set->getField("PENDIDIKAN_ID");
							$reqAnakStatusLulus= $set->getField("STATUS_LULUS");
							$reqAnakStatusBekerja= $set->getField("STATUS_BEKERJA");
							$reqAnakStatusAktif= $set->getField("STATUS_AKTIF");
							$reqAnakJenisKawinId= $set->getField("JENIS_KAWIN_ID");
							$reqAnakKematianNo= $set->getField('KEMATIAN_NO');
							$reqAnakKematianTanggal= dateToPageCheck($set->getField('KEMATIAN_TANGGAL'));
							$reqAnakTanggalMeninggal= dateToPageCheck($set->getField('TANGGAL_MENINGGAL'));

							$reqAnakAktaNikahNo= $set->getField('AKTA_NIKAH_NO');
							$reqAnakAktaNikahTanggal= dateToPageCheck($set->getField('AKTA_NIKAH_TANGGAL'));
							$reqAnakNikahTanggal= dateToPageCheck($set->getField('NIKAH_TANGGAL'));
							$reqAnakAktaCeraiNo= $set->getField('AKTA_CERAI_NO');
							$reqAnakAktaCeraiTanggal= dateToPageCheck($set->getField('AKTA_CERAI_TANGGAL'));
							$reqAnakCeraiTanggal= dateToPageCheck($set->getField('CERAI_TANGGAL'));
						?>
							<div class="row">
								<div class="input-field col s12 m3">
									<label for="reqAnakNama<?=$nomor?>">Anak <?=$nomor?></label>
									<input placeholder="" type="text" id="reqAnakNama<?=$nomor?>" value="<?=$reqAnakNama?>" disabled />
									<input type="hidden" name="reqAnakId[]" value="<?=$reqAnakId?>" />
								</div>
								<div class="input-field col s12 m1">
									<label for="reqAnakTanggalLahir<?=$nomor?>">Tanggal Lahir</label>
									<input placeholder="" readonly type="text" class="color-disb" name="reqAnakTanggalLahir[]" id="reqAnakTanggalLahir<?=$nomor?>" value="<?=$reqAnakTanggalLahir?> " />
									<!-- <label class="active" for="reqAnakTanggalLahir<?=$nomor?>">Tanggal Lahir</label>
									<table>
										<tr> 
											<td style="padding: 0px;">
												<input placeholder="" required class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqAnakTanggalLahir[]" id="reqAnakTanggalLahir<?=$nomor?>" value="<?=$reqAnakTanggalLahir?>" maxlength="10" onKeyDown="return format_date(event,'reqAnakTanggalLahir<?=$nomor?>');" />
											</td>
											<td style="padding: 0px;">
												<label class="input-group-btn" for="reqAnakTanggalLahir<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
													<span class="mdi-notification-event-note"></span>
												</label>
											</td>
										</tr>
									</table> -->
								</div>
								<div class="input-field col s12 m1">
						        	<select <?=$disabled?> name="reqAnakStatusAktif[]" id="reqAnakStatusAktif<?=$nomor?>">
						        		<option value="1" <? if($reqAnakStatusAktif == 1) echo 'selected';?>>Hidup</option>
						        		<option value="2" <? if($reqAnakStatusAktif == 2) echo 'selected';?>>Wafat</option>
						        	</select>
						        	<label for="reqAnakStatusAktif<?=$nomor?>">Status Hidup</label>
						        </div>
								<div class="input-field col s12 m2">
									<select <?=$disabled?> name="reqAnakStatusKeluarga[]" id="reqAnakStatusKeluarga<?=$nomor?>">
										<option value="" <? if($reqAnakStatusKeluarga == "") echo 'selected';?>>Belum diisi</option>
										<option value="1" <? if($reqAnakStatusKeluarga == 1) echo 'selected';?>>Kandung</option>
										<option value="2" <? if($reqAnakStatusKeluarga == 2) echo 'selected';?>>Tiri</option>
										<option value="3" <? if($reqAnakStatusKeluarga == 3) echo 'selected';?>>Angkat</option>
									</select>
									<label for="reqAnakStatusKeluarga<?=$nomor?>">Status Keluarga</label>
								</div>
								<div class="input-field col s12 m1">
									<label for="reqAnakUsia<?=$nomor?>">Usia</label>
									<input placeholder="" readonly type="text" class="color-disb" id="reqAnakUsia<?=$nomor?>" value="<?=$reqAnakUsia?> " />
								</div>
								<div class="input-field col s12 m3">
									<select <?=$disabled?> name="reqAnakSuamiIstriId[]" id="reqAnakSuamiIstriId<?=$nomor?>">
						              <option value="" selected></option>
						              <?
						              foreach ($arrsuamiistri as $key => $value)
						              {
						                $optionid= $value["ID"];
						                $optiontext= $value["TEXT"];
						                $optionselected= "";
						                if($reqAnakSuamiIstriId == $optionid)
						                  $optionselected= "selected";
						              ?>
						                <option value="<?=$optionid?>" <?=$optionselected?>><?=$optiontext?></option>
						              <?
						              }
						              ?>
						            </select>
						            <label for="reqAnakSuamiIstriId<?=$nomor?>">Nama Orang tua</label>
								</div>
							</div>

							<div class="row">
								<div class="input-field col s12 m3">
									<select <?=$disabled?> name="reqAnakPendidikanId[]" id="reqAnakPendidikanId<?=$nomor?>">
						              <option value="" selected></option>
						              <?
						              foreach ($arrpendidikan as $key => $value)
						              {
						                $optionid= $value["ID"];
						                $optiontext= $value["TEXT"];
						                $optionselected= "";
						                if($reqAnakPendidikanId == $optionid)
						                  $optionselected= "selected";
						              ?>
						                <option value="<?=$optionid?>" <?=$optionselected?>><?=$optiontext?></option>
						              <?
						              }
						              ?>
						            </select>
						            <label for="reqAnakPendidikanId<?=$nomor?>">Pendidikan terakhir</label>
						        </div>
						        <div class="input-field col s12 m2" id="labelstatuslulus<?=$nomor?>">
						        	<select <?=$disabled?> name="reqAnakStatusLulus[]" id="reqAnakStatusLulus<?=$nomor?>">
						        		<option value="1" <? if($reqAnakStatusLulus == 1) echo 'selected';?>>Ya</option>
						        		<option value="" <? if($reqAnakStatusLulus == "") echo 'selected';?>>Belum</option>
						        	</select>
						        	<label for="reqAnakStatusLulus<?=$nomor?>">Sudah Lulus?</label>
						        </div>
						        <div class="input-field col s12 m2">
						        	<select <?=$disabled?> name="reqAnakStatusBekerja[]" id="reqAnakStatusBekerja<?=$nomor?>">
						        		<option value="1" <? if($reqAnakStatusBekerja == 1) echo 'selected';?>>Sudah</option>
						        		<option value="" <? if($reqAnakStatusBekerja == "") echo 'selected';?>>Belum</option>
						        	</select>
						        	<label for="reqAnakStatusBekerja<?=$nomor?>">Bekerja?</label>
						        </div>
						        <div class="input-field col s12 m2">
						        	<select <?=$disabled?> name="reqAnakJenisKawinId[]" id="reqAnakJenisKawinId<?=$nomor?>">
						        		<option value=""></option>
						        		<?
						                foreach ($arrjeniskawin as $key => $value)
						                {
						                  $optionid= $value["ID"];
						                  $optiontext= $value["TEXT"];
						                  $optionselected= "";
						                  if($reqAnakJenisKawinId == $optionid)
						                    $optionselected= "selected";
						                ?>
						                  <option value="<?=$optionid?>" <?=$optionselected?>><?=$optiontext?></option>
						                <?
						                }
						                ?>
						        	</select>
						        	<label for="reqAnakJenisKawinId<?=$nomor?>">Status Pernikahan</label>
						        </div>
						    </div>
						    <?
						    $anaklabelmeninggaldisplay= "none";
							$anaklabelmeninggalrequired= "";
							// if($reqAnakStatusAktif == "2")
							// {
							// 	$anaklabelmeninggaldisplay= "";
							// 	$anaklabelmeninggalrequired= "required";
							// }
						    ?>
						    <div class="row">
						    	<div style="display: <?=$anaklabelmeninggaldisplay?>" class="input-field col s12 m3 reqLabelTanggalMeninggal<?=$nomor?>">
						    		<label for="reqAnakKematianNo<?=$nomor?>">Surat Keterangan Kematian</label>
						    		<input <?=$disabled?> <?=$anaklabelmeninggalrequired?> placeholder="" type="text" class="easyui-validatebox" name="reqAnakKematianNo[]" id="reqAnakKematianNo<?=$nomor?>" <?=$read?> value="<?=$reqAnakKematianNo?>" />
						    	</div>
						    	<div style="display: <?=$anaklabelmeninggaldisplay?>" class="input-field col s12 m3 reqLabelTanggalMeninggal<?=$nomor?>">
						    		<label class="active" for="reqAnakKematianTanggal<?=$nomor?>">Tanggal Surat Kematian</label>
						    		<table>
						    			<tr> 
						    				<td style="padding: 0px;">
						    					<input <?=$disabled?> <?=$anaklabelmeninggalrequired?> placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqAnakKematianTanggal[]" id="reqAnakKematianTanggal<?=$nomor?>" value="<?=$reqAnakKematianTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqAnakKematianTanggal<?=$nomor?>');" />
						    				</td>
						    				<td style="padding: 0px;">
						    					<label class="input-group-btn" for="reqAnakKematianTanggal<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
						    						<span class="mdi-notification-event-note"></span>
						    					</label>
						    				</td>
						    			</tr>
						    		</table>
						    	</div>
						    	<div style="display: <?=$anaklabelmeninggaldisplay?>" class="input-field col s12 m3 reqLabelTanggalMeninggal<?=$nomor?>">
						    		<label class="active" for="reqAnakTanggalMeninggal<?=$nomor?>">Tanggal Meninggal</label>
						    		<table>
						    			<tr> 
						    				<td style="padding: 0px;">
						    					<input <?=$disabled?> <?=$anaklabelmeninggalrequired?> placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqAnakTanggalMeninggal[]" id="reqAnakTanggalMeninggal<?=$nomor?>" value="<?=$reqAnakTanggalMeninggal?>" maxlength="10" onKeyDown="return format_date(event,'reqAnakTanggalMeninggal<?=$nomor?>');" />
						    				</td>
						    				<td style="padding: 0px;">
						    					<label class="input-group-btn" for="reqAnakTanggalMeninggal<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
						    						<span class="mdi-notification-event-note"></span>
						    					</label>
						    				</td>
						    			</tr>
						    		</table>
						    	</div>
						    </div>
						    <?
							$anaklabelnikahdisplay= $anaklabelceraidisplay= "none";
							$anaklabelnikahrequired= $anaklabelcerairequired= "";
							if($reqAnakStatusAktif == "2"){}
							else
							{
								if($reqAnakJenisKawinId == "1")
								{
									$anaklabelnikahdisplay= "";
									$anaklabelnikahrequired= "required";
								}
								else if($reqAnakJenisKawinId == "2" || $reqAnakJenisKawinId == "3")
								{
									$anaklabelnikahdisplay= "";
									$anaklabelnikahrequired= "required";

									$anaklabelceraidisplay= "";
									$anaklabelcerairequired= "required";
								}
							}

						    ?>
						    <div class="row">
						    	<div style="display: <?=$anaklabelnikahdisplay?>" class="input-field col s12 m3 anaklabelnikah<?=$nomor?>">
						    		<label for="reqAnakAktaNikahNo<?=$nomor?>">No Akta Nikah</label>
						    		<input <?=$disabled?> <?=$anaklabelnikahrequired?> placeholder="" type="text" class="easyui-validatebox" name="reqAnakAktaNikahNo[]" id="reqAnakAktaNikahNo<?=$nomor?>" <?=$read?> value="<?=$reqAnakAktaNikahNo?>" />
						    	</div>
						    	<div style="display: <?=$anaklabelnikahdisplay?>" class="input-field col s12 m3 anaklabelnikah<?=$nomor?>">
						    		<label class="active" for="reqAnakAktaNikahTanggal<?=$nomor?>">Tanggal Akta Nikah</label>
						    		<table>
						    			<tr> 
						    				<td style="padding: 0px;">
						    					<input <?=$disabled?> <?=$anaklabelnikahrequired?> placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqAnakAktaNikahTanggal[]" id="reqAnakAktaNikahTanggal<?=$nomor?>" value="<?=$reqAnakAktaNikahTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqAnakAktaNikahTanggal<?=$nomor?>');" />
						    				</td>
						    				<td style="padding: 0px;">
						    					<label class="input-group-btn" for="reqAnakAktaNikahTanggal<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
						    						<span class="mdi-notification-event-note"></span>
						    					</label>
						    				</td>
						    			</tr>
						    		</table>
						    	</div>
						    	<div style="display: <?=$anaklabelnikahdisplay?>" class="input-field col s12 m3 anaklabelnikah<?=$nomor?>">
						    		<label class="active" for="reqAnakNikahTanggal<?=$nomor?>">Tanggal Nikah</label>
						    		<table>
						    			<tr> 
						    				<td style="padding: 0px;">
						    					<input <?=$disabled?> <?=$anaklabelnikahrequired?> placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqAnakNikahTanggal[]" id="reqAnakNikahTanggal<?=$nomor?>" value="<?=$reqAnakNikahTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqAnakNikahTanggal<?=$nomor?>');" />
						    				</td>
						    				<td style="padding: 0px;">
						    					<label class="input-group-btn" for="reqAnakNikahTanggal<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
						    						<span class="mdi-notification-event-note"></span>
						    					</label>
						    				</td>
						    			</tr>
						    		</table>
						    	</div>
						    	<div style="display: <?=$anaklabelceraidisplay?>" class="input-field col s12 m3 anaklabelcerai<?=$nomor?>">
						    		<label for="reqAnakAktaCeraiNo<?=$nomor?>">Surat Pengadilan / Cerai</label>
						    		<input <?=$disabled?> <?=$anaklabelcerairequired?> placeholder="" type="text" class="easyui-validatebox" name="reqAnakAktaCeraiNo[]" id="reqAnakAktaCeraiNo<?=$nomor?>" <?=$read?> value="<?=$reqAnakAktaCeraiNo?>" />
						    	</div>
						    	<div style="display: <?=$anaklabelceraidisplay?>" class="input-field col s12 m3 anaklabelcerai<?=$nomor?>">
						    		<label class="active" for="reqAnakAktaCeraiTanggal<?=$nomor?>">Tanggal Akta Cerai</label>
						    		<table>
						    			<tr> 
						    				<td style="padding: 0px;">
						    					<input <?=$disabled?> <?=$anaklabelcerairequired?> placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqAnakAktaCeraiTanggal[]" id="reqAnakAktaCeraiTanggal<?=$nomor?>" value="<?=$reqAnakAktaCeraiTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqAnakAktaCeraiTanggal<?=$nomor?>');" />
						    				</td>
						    				<td style="padding: 0px;">
						    					<label class="input-group-btn" for="reqAnakAktaCeraiTanggal<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
						    						<span class="mdi-notification-event-note"></span>
						    					</label>
						    				</td>
						    			</tr>
						    		</table>
						    	</div>
						    	<div style="display: <?=$anaklabelceraidisplay?>" class="input-field col s12 m3 anaklabelcerai<?=$nomor?>">
						    		<label class="active" for="reqAnakCeraiTanggal<?=$nomor?>">Tanggal Cerai</label>
						    		<table>
						    			<tr>
						    				<td style="padding: 0px;">
						    					<input <?=$disabled?> <?=$anaklabelcerairequired?> placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqAnakCeraiTanggal[]" id="reqAnakCeraiTanggal<?=$nomor?>" value="<?=$reqAnakCeraiTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqAnakCeraiTanggal<?=$nomor?>');" />
						    				</td>
						    				<td style="padding: 0px;">
						    					<label class="input-group-btn" for="reqAnakCeraiTanggal<?=$nomor?>" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
						    						<span class="mdi-notification-event-note"></span>
						    					</label>
						    				</td>
						    			</tr>
						    		</table>
						    	</div>
						    </div>
						<?
							$nomor++;
						}
					?>

						<div class="row">
							<div class="input-field col s12 m12">
								Jika ada data penilaian skp/ppk belum ada di formulir usulan, silakan update di riwayat penilaian skp/ppk PNS yang bersangkutan. Silakan <a href="javascript:void();" style="text-decoration: none;" onclick="reloadpelayananriwayat('pegawai_add_skp_monitoring', 'surat_masuk_upt_add_pegawai_pensiun')">klik disini.</a>
							</div>
						</div>

						<?
						$vfpeg= new globalfilepegawai();
						$infotahunmundur= $vfpeg->gettahunmundur($reqTahunSurat);

						$reqPenilaianSkpJumlah= 0;
						$statement= " AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2') AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.TAHUN IN (".$infotahunmundur.")";
						$set= new SuratMasukPegawaiCheck();
						$set->selectByParamsPenilaianSkp(array(),-1,-1, $statement, "ORDER BY A.TAHUN DESC");
						// echo $set->query;exit;
						while($set->nextRow())
						{
							$reqPenilaianSkpTahun= $set->getField("TAHUN");
							$reqPenilaianSkpHasil= $set->getField("PRESTASI_HASIL");
							$reqPenilaianSkpJumlah++;
						?>
						<div class="row">
							<div class="input-field col s12 m3">
								<label>Tahun</label>
								<input  placeholder="" type="text" value="<?=$reqPenilaianSkpTahun?>" disabled />
							</div>
							<div class="input-field col s12 m9">
								<label>Penilaian SKP</label>
								<input  placeholder="" type="text" value="<?=$reqPenilaianSkpHasil?>" disabled />
							</div>
						</div>
						<?
						}
						?>
						<input type="hidden" name="reqPenilaianSkpJumlah" id="reqPenilaianSkpJumlah" value="<?=$reqPenilaianSkpJumlah?>" />
					<?
					}
					?>
				</div>

				<div class="row">
					<div class="input-field col s12 m12">
						<label for="reqKeteranganPensiun">Keterangan</label>
						<?
						if($reqRowId == "")
						{
						?>
							<input placeholder="" type="text" name="reqKeteranganPensiun" id="reqKeteranganPensiun" value="<?=$reqKeteranganPensiun?>" />
						<?
						}
						else
						{
						?>
							<input placeholder="" readonly type="text" class="color-disb" name="reqKeteranganPensiun" id="reqKeteranganPensiun" value="<?=$reqKeteranganPensiun?>" />
						<?
						}
						?>
					</div>
				</div>

				<div class="row">
					<div class="input-field col s12">
						<button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
							<i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
						</button>

						<?
						if($reqStatusKirim == "1"){}
						else
						{
						?>
						<input type="hidden" name="reqSatuanKerjaPegawaiUsulanId" id="reqSatuanKerjaPegawaiUsulanId" value="<?=$reqSatuanKerjaPegawaiUsulanId?>" />
						<input type="hidden" name="reqJabatanRiwayatAkhirId" id="reqJabatanRiwayatAkhirId" value="<?=$reqJabatanRiwayatAkhirId?>" />
						<input type="hidden" name="reqPendidikanRiwayatAkhirId" id="reqPendidikanRiwayatAkhirId" value="<?=$reqPendidikanRiwayatAkhirId?>" />
						<input type="hidden" name="reqGajiRiwayatAkhirId" id="reqGajiRiwayatAkhirId" value="<?=$reqGajiRiwayatAkhirId?>" />
						<input type="hidden" name="reqPangkatRiwayatAkhirId" id="reqPangkatRiwayatAkhirId" value="<?=$reqPangkatRiwayatAkhirId?>" />

						<input type="hidden" name="reqJenis" id="reqJenis" value="<?=$reqJenis?>" />
						<input type="hidden" name="reqPegawaiId" id="reqPegawaiId" value="<?=$reqPegawaiId?>" />
						<input type="hidden" name="reqRowId" id="reqRowId" value="<?=$reqRowId?>" />
						<input type="hidden" name="reqRowDetilId" value="<?=$reqRowDetilId?>" />
						<input type="hidden" name="reqKategori" value="<?=$reqKategori?>" />

						<input type="hidden" name="reqId" id="reqId" value="<?=$reqId?>" />
						<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
						<?
							if($reqRowId == "")
							{
						?>
							<button type="submit" style="display:none" id="reqSubmit"></button>
							<button class="btn waves-effect waves-light green" style="font-size:9pt" type="button" id="reqsimpan">Simpan
								<i class="mdi-content-save left hide-on-small-only"></i>
							</button>
						<?
							}
						}

						if($reqRowId == ""){}
						else
						{
							if($reqStatusKirim == "1"){}
							else
							{
						?>
							<button class="btn purple waves-effect waves-light" style="font-size:9pt" type="button" id="reqselanjutnya">Selanjutnya
								<i class="mdi-content-inbox left hide-on-small-only"></i>
							</button>

							<button class="btn pink waves-effect waves-light" style="font-size:9pt" type="button" id="reqhapus">Hapus
								<i class="mdi-content-inbox left hide-on-small-only"></i>
							</button>

							<button class="btn blue waves-effect waves-light" style="font-size:9pt" type="button" id="tambah">Tambah Lainnya
								<i class="mdi-content-add left hide-on-small-only"></i>
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

<div class="preloader-wrapper big active loader">
  <div class="spinner-layer spinner-blue-only">
    <div class="circle-clipper left">
      <div class="circle"></div>
    </div><div class="gap-patch">
      <div class="circle"></div>
    </div><div class="circle-clipper right">
      <div class="circle"></div>
    </div>
  </div>
</div>

<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

<style type="text/css">
  .select-dropdown {
    max-height:250px !important; overflow:auto !important;
  }
</style>

<script src="lib/moment/moment-with-locales.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('select').material_select();
    
	$("#reqhapus").click(function() { 

		var s_url= "surat/surat_masuk_pegawai_json/cek_kirim_upt?reqId=<?=$reqId?>";
	      $.ajax({'url': s_url,'success': function(dataajax){
	        var requrl= requrllist= "";
	        dataajax= String(dataajax);
	        if(dataajax == '1')
	        {
	          mbox.alert('Data sudah dikirim', {open_speed: 0});
	          return false;
	        }
	        else
	        {
	        	mbox.custom({
				   message: "Apakah Anda Yakin, Hapus data terpilih ?",
				   options: {close_speed: 100},
				   buttons: [
					   {
						   label: 'Ya',
						   color: 'green darken-2',
						   callback: function() {
								$.getJSON("surat/surat_masuk_pegawai_json/delete_pegawai/?reqId=<?=$reqRowId?>",
								function(data){
									mbox.alert(data.PESAN, {open_speed: 500}, interval = window.setInterval(function() 
									{
										clearInterval(interval);
										document.location.href= "app/loadUrl/persuratan/surat_masuk_upt_add_pegawai_pensiun/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>";
									}, 1000));
									$(".mbox > .right-align").css({"display": "none"});
									//$(".right-align").hide();
								});
							   //console.log('do action for yes answer');
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
	        }
	    }});
		
	});

	$("#reqselanjutnya").click(function() { 
    	document.location.href= "app/loadUrl/persuratan/surat_masuk_upt_add_pegawai_lookup_verfikasi_pensiun/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqRowId=<?=$reqRowId?>";
    });
	
	$("#tambah").click(function() { 
		document.location.href= "app/loadUrl/persuratan/surat_masuk_upt_add_pegawai_pensiun/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>";
	});
	
	$("#kembali").click(function() {
		kembali= "<?=$kembali?>";
		if(kembali == "")
			infolinkurl= "surat_masuk_upt_add_pegawai";
		else
			infolinkurl= kembali;
		document.location.href = "app/loadUrl/persuratan/"+infolinkurl+"?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>";
	});
	
	$("#reqcetakrekomendasi").click(function() { 
	  newWindow = window.open("app/loadUrl/persuratan/cetak_pdf?reqCss=surat_rekomendasi&reqUrl=<?=$reqJenisSuratRekomendasi?>&reqId=<?=$reqRowId?>", 'Cetak');
	  newWindow.focus();
	});
	
  });

  $('.materialize-textarea').trigger('autoresize');

</script>

<script type="text/javascript" src="lib/easyui/pelayanan-kembali.js"></script>
<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>