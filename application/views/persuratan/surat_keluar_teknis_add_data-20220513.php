<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/SuratMasukBkdDisposisi');
$this->load->model('persuratan/SuratKeluarBkd');
$this->load->model('persuratan/TandaTanganBkd');

$reqId= $this->input->get("reqId");
$reqJenis= $this->input->get("reqJenis");
$reqMode= $this->input->get("reqMode");
$reqJenisNama= setjenisinfo($reqJenis);

$tanggalHariIni= date("d-m-Y");
$tahunIni= date("Y");
$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
$tempStatusKelompokPegawaiUsul= $this->STATUS_KELOMPOK_PEGAWAI_USUL;
$reqLoginLevel= $this->LOGIN_LEVEL;

if($reqId=="")
{
	$reqMode = 'insert';
	$reqSatuanKerjaTujuanId= $reqSatuanKerjaId;
	$reqKepada= $this->SATUAN_KERJA_LOGIN_KEPALA_JABATAN;
	//echo $reqKepada."--".$reqSatuanKerjaId;exit;
	
	/*$statement= " AND TO_CHAR(TANGGAL, 'YYYY') = '".$tahunIni."'";
	$set= new SuratMasukBkdDisposisi();
	$set->selectByParamsNoAgenda($statement);
	$set->firstRow();
	$reqNomorAgenda= $set->getField("NO_AGENDA_BARU");
	if($reqNomorAgenda == "")
	$reqNomorAgenda= "0001";*/
	//echo $reqNomorAgenda;exit;
}
else
{
   $reqMode = 'update';
   $statement= " AND A.SURAT_KELUAR_BKD_ID = ".$reqId."";
   $set= new SuratKeluarBkd();
   $set->selectByParams(array(), -1, -1, $statement);
   $set->firstRow();
   //echo $set->query;exit;
   $reqTandaTanganBkdId= $set->getField("TANDA_TANGAN_BKD_ID");
   $reqNomorSuratKodeKlasifikasi= $set->getField("KLASIFIKASI_KODE");
   $reqPerihal= $set->getField("PERIHAL");
   $reqTanggal= dateToPageCheck($set->getField("TANGGAL"));
   $reqNomor= $set->getField("NOMOR");
   $tempNomorAwal= $reqNomorAwal= $set->getField("NOMOR_AWAL");
   $reqNomorUrut= $set->getField("NOMOR_URUT");
   $reqKlasifikasiId= $set->getField("KLASIFIKASI_ID");
   $reqKlasifikasi= $set->getField("KLASIFIKASI_KODE");
   $reqIsManual= $set->getField("IS_MANUAL");
   $reqSatuanKerjaTujuanId= $set->getField("SATUAN_KERJA_TUJUAN_ID");
   $reqSatuanKerjaTujuanNama= $set->getField("SATUAN_KERJA_TUJUAN_NAMA");
}

if($reqTanggal == "")
$reqTanggal= $tanggalHariIni;

$statement= " AND A.TANDA_TANGAN_BKD_ID = (SELECT AMBIL_TANDA_TANGAN_BKD_TGL(TO_DATE('".$reqTanggal."', 'DD-MM-YYYY')))";
$tandatangan= new TandaTanganBkd();
$tandatangan->selectByParams(array(),-1,-1, $statement);
$tandatangan->firstRow();
$reqNomorSuratTandaTanganBkdId= $tandatangan->getField("TANDA_TANGAN_BKD_ID");
$reqNomorSuratNoNomenklaturKab= $tandatangan->getField("NO_NOMENKLATUR_KAB");
$reqNomorSuratNoNomenklaturBkd= $tandatangan->getField("NO_NOMENKLATUR_BKD");
$reqNomorSuratTahun= getDay($reqTanggal);
$reqNomorNomenklatur= $reqNomorSuratNoNomenklaturKab.".".$reqNomorSuratNoNomenklaturBkd;

//echo $tandatangan->query;exit;

if($reqTandaTanganBkdId == "")
$reqTandaTanganBkdId= $reqNomorSuratTandaTanganBkdId;

if($reqNomorAwal == "")
{
	$statement= " AND TO_CHAR(TANGGAL, 'YYYY') = '".$reqNomorSuratTahun."'";
	$set_detil= new SuratKeluarBkd();
	$reqNomorAwal= $set_detil->getCountByParamsNomorAwalPerTahun($statement);
	
	$statement= " AND TO_CHAR(TANGGAL, 'YYYY') = '".$reqNomorSuratTahun."' AND A.NOMOR_AWAL IS NOT NULL";
	$reqTanggalCek= $set_detil->getCountByParamsTanggalPerTahun($statement);
	
	$TglSKeluarUsul= $reqTanggal;
	$TglSKeluarTRecord= dateToPageCheck($reqTanggalCek);
	if($reqTanggalCek == "")
	$TglSKeluarTRecordPlus= "";
	else
	{
	$TglSKeluarTRecordPlus= $reqTanggalCek;
	$TglSKeluarTRecordPlus= date('Y-m-d',strtotime($date . "+1 days"));
	/*$TglSKeluarTRecordPlus= date_create($reqTanggalCek);
	date_add($TglSKeluarTRecordPlus,date_interval_create_from_date_string("1 days"));
	$TglSKeluarTRecordPlus= date_format($TglSKeluarTRecordPlus,"Y-m-d");*/
	$TglSKeluarTRecordPlus= dateToPageCheck($TglSKeluarTRecordPlus);
	}
	$TglAkses= $tanggalHariIni;
	
	if(
	(
	(strtotime($TglSKeluarUsul) == strtotime($TglSKeluarTRecord)) && (strtotime($TglSKeluarTRecord) <= strtotime($TglAkses))) 
	|| 
	((strtotime($TglSKeluarUsul) == strtotime($TglAkses)) && (strtotime($TglSKeluarTRecordPlus) == strtotime($TglAkses))) 
	|| ((strtotime($TglSKeluarUsul) > strtotime($TglSKeluarTRecord)) && (strtotime($TglSKeluarUsul)  <= strtotime($TglAkses)))
	)
	$incrementnomor= "1";
	
	if($incrementnomor == "1")
	{
		if($reqNomorAwal == "")
		$reqNomorAwal= 1;
	}
	else
	{
		$reqNomorAwal= "";
	}
	
	/*if($reqNomorAwal == "")
	$reqNomorAwal= 1;*/
	
	$reqNomor= $reqNomorSuratKodeKlasifikasi."/".$reqNomorAwal."/".$reqNomorSuratNoNomenklaturKab.".".$reqNomorSuratNoNomenklaturBkd."/".$reqNomorSuratTahun;
}

if($reqNomor == "")
{
	//$reqNomor= $reqNomorSuratKodeKlasifikasi."/".$reqNomorAwal."/".$reqNomorSuratNoNomenklaturKab.".".$reqNomorSuratNoNomenklaturBkd."/".$reqNomorSuratTahun;
}

$tempJudul= "Nomor Surat Keluar";
$json="surat_keluar_nomor";

/*$arrHistori="";
$index_data= 0;
if($reqId == ""){}
else
{
	$statement= " AND A.SURAT_MASUK_BKD_ID = ".$reqId;
	$statementnode= " AND SATUAN_KERJA_ASAL_ID NOT IN (".$reqSatuanKerjaId.")";
	$set_detil= new SuratMasukBkdDisposisi();
	$set_detil->selectByParamsHistoriDisposisi($statement, $statementnode);
	//echo $set_detil->query;exit;
	while($set_detil->nextRow())
	{
		$arrHistori[$index_data]["SURAT_MASUK_BKD_DISPOSISI_ID"] = $set_detil->getField("SURAT_MASUK_BKD_DISPOSISI_ID");
		$arrHistori[$index_data]["TANGGAL_DISPOSISI"] = $set_detil->getField("TANGGAL_DISPOSISI");
		$arrHistori[$index_data]["ISI"] = $set_detil->getField("ISI");
		$arrHistori[$index_data]["JABATAN_ASAL"] = $set_detil->getField("JABATAN_ASAL");
		$arrHistori[$index_data]["JABATAN_TUJUAN"] = $set_detil->getField("JABATAN_TUJUAN");
		$index_data++;
	}
	unset($set_detil);
}
$jumlah_histori= $index_data;*/
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
		$("#reqsimpan").click(function() { 
			if($("#ff").form('validate') == false){
				return false;
			}
			
			info= "Pastikan sudah melengkapi nomor surat. Apakah anda Yakin untuk simpan ?";
			mbox.custom({
			   message: info,
			   options: {close_speed: 100},
			   buttons: [
				   {
					   label: 'Ya',
					   color: 'green darken-2',
					   callback: function() {
						   $("#reqSubmit").click();
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
	
        $('#ff').form({
            url:'surat/surat_keluar_bkd_json/<?=$json?>',
            onSubmit:function(){
                if($(this).form('validate')){}
                    else
                    {
                        $.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
                        return false;
                    }
                },
                success:function(data){
                // alert(data);return false;
                data = data.split("-");
                rowid= data[0];
                infodata= data[1];
                //$.messager.alert('Info', infodata, 'info');
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
						parent.reloadparenttab();
						document.location.href= "app/loadUrl/persuratan/surat_keluar_teknis_add_data/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>";
					}, 1000));
				}

            }
        });
	
	$("#reqTanggal").keyup(function() {
		setnomorawal();
	});
	  
	$("#reqNomorAwal,#reqNomorUrut").keyup(function() {
		setNomorSurat();
	});
			
});

function setnomorawal()
{
	var reqTanggal= "";
	reqTanggal= $("#reqTanggal").val();
	panjangTanggal= reqTanggal.length;
	if(panjangTanggal == 10)
	{
		reqNomorSuratTahun= reqTanggal.substring(6)
		$.ajax({'url': "surat/surat_keluar_bkd_json/setnomorawal/?reqNomorSuratTahun="+reqNomorSuratTahun+"&tanggalHariIni=<?=$tanggalHariIni?>&reqTipe=<?=$reqTipe?>&reqTanggal="+reqTanggal,'success': function(dataJson) {
			var data= JSON.parse(dataJson);
			reqNomorAwal= data.reqNomorAwal;
			$("#reqNomorAwal").val(reqNomorAwal);
			
			// tambahan coba
			$("#reqNomorSuratTahun").val(reqNomorSuratTahun);
			setNomorSurat();
		}});
						
	}
	else
	{
		$("#reqNomorAwal").val("");
		setNomorSurat();
	}
}

function setNomorSurat()
{
	var panjang= reqNomor= reqNomorSuratKodeKlasifikasi= reqNomorAwal= reqNomorUrut= reqNomorSuratNoNomenklaturKab= reqNomorSuratNoNomenklaturBkd= reqNomorSuratTahun= "";
	panjang= $("#reqTanggal").val().length;
	reqNomorSuratKodeKlasifikasi= $("#reqNomorSuratKodeKlasifikasi").val();
	reqNomorAwal= $("#reqNomorAwal").val();
	reqNomorUrut= $("#reqNomorUrut").val();
	reqNomorSuratNoNomenklaturKab= $("#reqNomorSuratNoNomenklaturKab").val();
	reqNomorSuratNoNomenklaturBkd= $("#reqNomorSuratNoNomenklaturBkd").val();
	reqNomorSuratTahun= $("#reqNomorSuratTahun").val();
	
	if(panjang == 10)
	{
		if(reqNomorUrut == "")
			reqNomor= reqNomorSuratKodeKlasifikasi+"/"+reqNomorAwal+"/"+reqNomorSuratNoNomenklaturKab+"."+reqNomorSuratNoNomenklaturBkd+"/"+reqNomorSuratTahun;
		else
		reqNomor= reqNomorSuratKodeKlasifikasi+"/"+reqNomorAwal+"."+reqNomorUrut+"/"+reqNomorSuratNoNomenklaturKab+"."+reqNomorSuratNoNomenklaturBkd+"/"+reqNomorSuratTahun;
	}
	$("#reqNomor").val(reqNomor);
}
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
        <div class="col s12 m10 offset-m1">

            <ul class="collection card">
                <li class="collection-item ubah-color-warna">Usulan Pelayanan <?=$tempJudul?></li>
                <li class="collection-item">
                
                <div class="row">
                    <form id="ff" method="post" enctype="multipart/form-data">
                    	 <div class="row">
                         	<div class="input-field col s12 m3">
                            <label for="reqTanggal">Tanggal Surat</label>
                            <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggal" id="reqTanggal"  value="<?=$reqTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggal');"/>
                            </div>
                            <div class="input-field col s12 m3">
                            <button class="btn waves-effect waves-light green" style="font-size:9pt" type="button" id="reqcek"
                            onClick="parent.openModal('app/loadUrl/persuratan/surat_keluar_informasi?reqBreadCrum=Data Surat Keluar')" >
                            Cek
                            <i class="mdi-content-save left hide-on-small-only"></i>
                            </button>
                            </div>
                         </div>
                         <div class="row">
                         	<div class="input-field col s12 m3">
                              <label for="reqKlasifikasi">Jenis Surat</label>
                              <input type="hidden" name="reqKlasifikasiId" id="reqKlasifikasiId" value="<?=$reqKlasifikasiId?>" /> 
                              <input class="easyui-validatebox" required type="text" name="reqKlasifikasi" id="reqKlasifikasi" value="<?=$reqKlasifikasi?>" readonly />
                            </div>
                            <div class="input-field col s12 m2">
                            <label for="reqNomor">Nomor Agenda</label>
                            <input type="text" name="reqNomorAwal" id="reqNomorAwal" value="<?=$reqNomorAwal?>" class="easyui-validatebox" required />
                            </div>
                            <div class="input-field col s12 m2">
                            <label for="reqNomorUrut">Nomor Sub Agenda</label>
                            <input type="text" name="reqNomorUrut" id="reqNomorUrut" value="<?=$reqNomorUrut?>" class="easyui-validatebox" />
                            </div>
                            <div class="input-field col s12 m2">
                            <label for="reqNomorNomenklatur">Nomor Nomenklatur</label>
                            <input type="text" disabled id="reqNomorNomenklatur" value="<?=$reqNomorNomenklatur?>" />
                            </div>
                            <div class="input-field col s12 m2">
                            <label for="reqTahun">Tahun</label>
                            <input type="text" readonly id="reqNomorSuratTahun" value="<?=$reqNomorSuratTahun?>" />
                            </div>
                         </div>
                         
                         <div class="row">
                          <div class="input-field col s12 m3">
                          	<label for="reqNomorSurat">No Surat</label>
                            <input type="text" id="reqNomor" style="font-size:20px; font-weight:bold" name="reqNomor"  value="<?=$reqNomor?>" class="easyui-validatebox" readonly required />
                          </div>
                        </div>
                        
                         <div class="row">
                          <div class="input-field col s12 m6">
                          	<label for="reqPerihal">Perihal</label>
                            <input type="hidden" name="reqPerihal" value="<?=$reqPerihal?>" />
                            <input type="text" id="reqPerihal"  value="<?=$reqPerihal?>" disabled />
                          </div>
                         </div>
                         
                         <div class="row" style="display:none">
                            <div class="input-field col s12">
                              <input type="checkbox" id="reqIsManual" name="reqIsManual" value="1" <? if($reqIsManual == 1) echo 'checked'?> />
                              <label for="reqIsManual"></label>
                              *centang jika satker di luar kab jombang
                            </div>
                         </div>
                         
                         <div class="row" style="display:none">
                            <div class="input-field col s12 m12">
                              <label for="reqSatuanKerjaTujuanNama">Tujuan Surat</label>
                              <input type="hidden" name="reqSatuanKerjaTujuanId" id="reqSatuanKerjaTujuanId" value="<?=$reqSatuanKerjaTujuanId?>" />
                              <input type="text" id="reqSatuanKerjaTujuanNama" name="reqSatuanKerjaTujuanNama" <?=$read?> value="<?=$reqSatuanKerjaTujuanNama?>" class="easyui-validatebox" />
                            </div>
                         </div>

                        <div class="row">
                            <div class="input-field col s12 m12">
                                <input type="hidden" name="reqId" value="<?=$reqId?>" />
                                <input type="hidden" name="reqJenis" value="<?=$reqJenis?>" />
                                <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                                <input type="hidden" name="reqTandaTanganBkdId" value="<?=$reqTandaTanganBkdId?>" />
                                
                                <input type="hidden" id="reqNomorSuratKodeKlasifikasi" value="<?=$reqNomorSuratKodeKlasifikasi?>" />
                                <input type="hidden" id="reqNomorSuratNoNomenklaturKab" value="<?=$reqNomorSuratNoNomenklaturKab?>" />
                                <input type="hidden" id="reqNomorSuratNoNomenklaturBkd" value="<?=$reqNomorSuratNoNomenklaturBkd?>" />
                                
                                <button type="submit" style="display:none" id="reqSubmit"></button>
                                <button class="btn waves-effect waves-light green" style="font-size:9pt" type="button" id="reqsimpan">
                                Simpan
                                <i class="mdi-content-save left hide-on-small-only"></i>
                                </button>

                                <?php /*?><button class="btn pink waves-effect waves-light" style="font-size:9pt" type="button" id="reqcetakdisposisi">Cetak Lembar Disposisi
                                    <i class="mdi-content-inbox left hide-on-small-only"></i>
                                </button><?php */?>
                                                                
                                <button class="btn red waves-effect waves-light" style="font-size:9pt" type="button" id="tambah" onClick="parent.closeparenttab()">Close
                                   <i class="mdi-navigation-close left hide-on-small-only"></i>
                                </button>

                                <?
                                if($tempNomorAwal == "")
                                {
                                ?>
                                <button class="btn waves-effect waves-light pink" style="font-size:9pt" type="button" id="reqbatal">
                                Batal
                                <i class="mdi-content-save left hide-on-small-only"></i>
                                </button>
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
<!-- jQuery Library -->
<!-- <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script> -->

<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('select').material_select();
	
	$("#reqcetakdisposisi").click(function() { 
	  newWindow = window.open("app/loadUrl/persuratan/cetak_pdf?reqCss=surat_pengantar&reqUrl=cetak_disposisi&reqId=<?=$reqId?>&reqPegawaiPilihKepalaId=<?=$reqSatuanKerjaId?>", 'Cetak');
	  newWindow.focus();
	});
	
	$("#reqbatal").click(function() { 
		$.messager.confirm('Konfirmasi', "Apakah Anda Yakin, batal usulan surat keluar ?",function(r){
			if (r){
				$.getJSON("surat/surat_keluar_bkd_json/statusbatal/?reqId=<?=$reqId?>",
				function(data){
					$.messager.alert('Info', data.PESAN, 'info');
					parent.reloadparenttab();
					document.location.href= "app/loadUrl/persuratan/surat_keluar_teknis_add_data/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>";
				});
			}
		});
	  //document.location.href = "app/loadUrl/app/pegawai_add_anak_monitoring?reqId=<?=$reqId?>";
	});
	
});

  $('.materialize-textarea').trigger('autoresize');

  $('#reqNomorAwal,#reqNomorUrut').bind('keyup paste', function(){
   this.value = this.value.replace(/[^0-9]/g, '');
 });

</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>