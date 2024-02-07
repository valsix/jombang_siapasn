<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// $this->load->library("approval"); 
// $approval = new approval($this->ID, '0');

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('main/PermohonanLambatPc');
$this->load->model('main/PermohonanFile');

// $this->load->model('Pegawai');
// $this->load->model('IjinKoreksiBackdatePengecualian');

// $pegawai= new Pegawai();
// $ijin_koreksi_backdate_pengecualian= new IjinKoreksiBackdatePengecualian();

$reqId= $this->input->get("reqId");

// $reqTanggalAwal= "12-01-2020";
// $reqTanggalAkhir= "14-01-2020";

$set= new PermohonanLambatPc();
if($reqId == "")
{
	$reqMode = "insert";
	$reqTanggal = date("d-m-Y");
}
else
{
	$reqMode = "update";
	$set->selectByParams(array("A.PERMOHONAN_LAMBAT_PC_ID" => $reqId));
	// echo $set->query;exit();
	$set->firstRow();
	$reqPegawaiId = $set->getField("PEGAWAI_ID");
	$reqPegawaiNip = $set->getField("NIP_BARU");
	$reqPegawaiNama = $set->getField("NAMA_LENGKAP");
	$reqTanggal = $set->getField("TANGGAL");
	$reqTanggalIjin = $set->getField("TANGGAL_IJIN");
	$reqStatusMasuk = $set->getField("STATUS_MASUK");
	$reqStatusPulang = $set->getField("STATUS_PULANG");
	$reqKeterangan = $set->getField("KETERANGAN");

	$reqTanggalAwal = $set->getField("TANGGAL_AWAL");
	$reqTanggalAkhir = $set->getField("TANGGAL_AKHIR");

	$reqAlasan = $set->getField("APPROVAL1");
	$reqAlasanTolak = $set->getField("ALASAN_TOLAK1");

	// $reqTahun = $set->getField("TAHUN");
	// $reqNomor = $set->getField("NOMOR");
	// $reqJabatan = $set->getField("JABATAN");
	// $reqCabang = $set->getField("CABANG");
	// $reqDepartemen = $set->getField("DEPARTEMEN");
	// $reqSubDepartemen = $set->getField("SUB_DEPARTEMEN");
	// $reqTanggalAwal = $set->getField("TANGGAL_AWAL");
	// $reqTanggalAkhir = $set->getField("TANGGAL_AKHIR");
	// $reqJumlahHari = $set->getField("LAMA_CUTI");
	// $reqAlamat = $set->getField("ALAMAT");
	// $reqTelepon = $set->getField("TELEPON");
	// $reqPegawaiIdApproval = $set->getField("PEGAWAI_ID_APPROVAL");
	// $reqApproval = $set->getField("APPROVAL");

	$set= new PermohonanFile();
	$index_data= 0;
	$arrPermohonanFile="";
	$statement= " AND A.PERMOHONAN_TABLE_NAMA = 'permohonan_lambat_pc' AND A.PERMOHONAN_TABLE_ID = ".$reqId;
	$set->selectByParams(array(), -1,-1, $statement);
	// echo $set->query;exit;
	while($set->nextRow())
	{
		$arrPermohonanFile[$index_data]["NAMA"]= $set->getField("NAMA");
		$arrPermohonanFile[$index_data]["TIPE"]= $set->getField("TIPE");
		$arrPermohonanFile[$index_data]["LINK_FILE"]= $set->getField("LINK_FILE");
		$arrPermohonanFile[$index_data]["KETERANGAN"]= $set->getField("KETERANGAN");
		$index_data++;
	}
	$jumlahpermohonanfile = $index_data;
}

$simpan= "";
if(!empty($reqAlasan))
{
	$simpan= "1";
}
// $jumlah_pegawai_pengecualian = $ijin_koreksi_backdate_pengecualian->getCountByParams(array("PEGAWAI_ID"=>$this->ID));
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>	
<base href="<?=base_url()?>">
<link rel="stylesheet" type="text/css" href="css/gaya.css">

<link rel="stylesheet" type="text/css" href="lib/easyui-autocomplete/themes/default/easyui.css">
<script type="text/javascript" src="lib/easyui-autocomplete/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="lib/easyui-autocomplete/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/easyui-autocomplete/kalender-easyui.js"></script>
<script type="text/javascript" src="lib/easyui-autocomplete/globalfunction.js"></script>

<!-- BOOTSTRAP CORE -->
<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- AUTO KOMPLIT -->
<link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
<script src="lib/autokomplit/jquery-ui.js"></script>

<script type="text/javascript">	
$(function(){
	$('#ff').form({
		url:'main/permohonan_dinas_json/add',
		onSubmit:function(){
			var reqNama= reqBatasAwal= reqBatasAkhir= "";

			reqNama= $("#reqNama").val();
			reqBatasAwal= $("#reqBatasAwal").val().length;
			reqBatasAkhir= $("#reqBatasAkhir").val().length;
			// console.log(reqBatasAwal);

			if(reqNama == "")
			{
				$.messager.alert('Info', "Isi Nama terlebih dahulu.", 'info');
				return false;
			}

			if(parseInt(reqBatasAwal) < 8)
			{
				$.messager.alert('Info', "Isi Batas Awal terlebih dahulu.", 'info');
				return false;
			}

			if(parseInt(reqBatasAkhir) < 8)
			{
				$.messager.alert('Info', "Isi Batas Akhir terlebih dahulu.", 'info');
				return false;
			}

			return false;
			
			//return $(this).form('validate');
		},
		success:function(data){
			// console.log(data);return false;
			data = data.split("-");
			rowid= data[0];
			infodata= data[1];

			$.messager.alert('Info', infodata, 'info');

			if(rowid == "xxx"){}
			else
				document.location.href = "app/loadUrl/main/permohonan_dinas";
		}
	});

	$("#reqTanggalAwal,#reqTanggalAkhir").keyup(function() {
		old = $(this).attr("data-initial-value");
		value= $(this).val();

		// console.log("old:"+old);
		// console.log("value:"+value);
		panjang= value.length;

		if(old == value){}
		else
		{
			if(panjang == 10)
			{
				if(validasiregextanggal(value) == false)
				{
					$.messager.alert('Info', "Isi tanggal "+value+" salah format.", 'info');
					$(this).val("");
				}
				validasitanggal();
			}
		}

		$(this).attr('data-initial-value', value);

	});
	

	// setJenis();
	// $("#reqJenis").change(function() {
	// 	setJenis();
	// });
	
	// $('#reqTanggalAwal').datebox({
	// 	onChange: function(date){
	// 		$('#reqTanggalAkhir').datebox('setValue', '');		
	// 	}
	// });
	
	// $('#reqTanggalAkhir').datebox({
	// 	onChange: function(date){
	// 		validasitanggal();
	// 	}
	// });

});

// console.log(setvalidasitanggal('16-01-2020', '15-01-2020')+"--");
function setvalidasitanggal(tanggalawal, tanggalakhir)
{
	var dt1   = parseInt(tanggalakhir.substring(0,2),10); 
	var mon1  = parseInt(tanggalakhir.substring(3,5),10) - 1;
	var yr1   = parseInt(tanggalakhir.substring(6,10),10); 

	var dt2   = parseInt(tanggalawal.substring(0,2),10); 
	var mon2  = parseInt(tanggalawal.substring(3,5),10) - 1; 
	var yr2   = parseInt(tanggalawal.substring(6,10),10); 
	var date1 = new Date(yr1, mon1, dt1); 
	var date2 = new Date(yr2, mon2, dt2); 
	// console.log(date2+" < "+date1);return false;
	return (date2 > date1);
}

function validasitanggal()
{
	// var mulai = $('#reqTanggalAwal').datebox('getValue');
	// var selesai = $('#reqTanggalAkhir').datebox('getValue');

	var mulai = $('#reqTanggalAwal').val();
	var selesai = $('#reqTanggalAkhir').val();
	
	if(mulai == "")
	{
		// $('#reqTanggalAkhir').datebox('setValue', '');
		$('#reqTanggalAkhir').val("");
		$.messager.alert('Info', "Isi tanggal mulai terlebih dahulu.", 'info');		
		return;
	}
	
	// console.log(mulai+";"+selesai);
	// var selisih = get_day_between(mulai, selesai);
		
	if(setvalidasitanggal(mulai, selesai))
	{
		// $('#reqTanggalAkhir').datebox('setValue', '');
		$('#reqTanggalAkhir').val(mulai);
		$.messager.alert('Info', "Tanggal akhir lebih kecil.", 'info');		
		return;
	}
}

// function setJenis()
// {
// 	// $('#reqTanggalIjin').datebox({required: false});
// 	// $('#reqBatasAwal, #reqTanggalIjin').removeClass('validatebox-invalid');
// 	$('#reqNama,#reqBatasAwal').validatebox({required: true});
// 	$('#reqNama,#reqBatasAwal').addClass('validatebox-invalid');

// 	$('#reqTanggalAkhir, #reqTanggalAwal').datebox({required: true});
// }

function opendownload(pageurl)
{
	// console.log(link);
	newWindow = window.open(pageurl);
	newWindow.focus();
}
</script>
</head>

<body class="bg-permohonan">
	<div class="area-permohonan">
		<div class="judul-monitoring"><span>Data</span></div>

		<form id="ff" method="post" novalidate enctype="multipart/form-data">
			<table class="table">
				<thead>
					<tr>
	                    <td>Nama</td>
	                    <td>:</td>
	                    <td>
	                       <input type="text" id="reqNama" name="reqNama" class="easyui-validatebox" style="width:90%;" value="<?=$reqNama?>" />
	                    </td>
	                </tr>
	                <tr>
	                    <td>Prioritas</td>
	                    <td>:</td>
	                    <td>
	                        <select name="reqPrioritas">
	                            <option value="1" <? if($reqPrioritas == '1') { ?> selected="selected" <? } ?>>Wajib</option>
	                            <option value="2" <? if($reqPrioritas == '2') { ?> selected="selected" <? } ?>>Optional</option>
	                        </select>
	                    </td>
	                </tr>
	                <tr>
	                    <td>Tanggal</td>
	                    <td>:</td>
	                    <td>
	                    	<input required data-initial-value="<?=$reqTanggalAwal?>" class="easyui-validatebox" style="width: 100px" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalAwal" id="reqTanggalAwal" value="<?=$reqTanggalAwal?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalAwal');"/>
	                        <!-- <input class="easyui-datebox" style="width: 100px" id="reqTanggalAwal" name="reqTanggalAwal" value="<?=$reqTanggalAwal?>" required /> -->
	                        s/d
	                        <input required data-initial-value="<?=$reqTanggalAkhir?>" class="easyui-validatebox" style="width: 100px" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalAkhir" id="reqTanggalAkhir" value="<?=$reqTanggalAkhir?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalAkhir');"/>
	                        <!-- <input required data-initial-value="<?=$reqTanggalAkhir?>" class="easyui-datebox" style="width: 100px" id="reqTanggalAkhir" name="reqTanggalAkhir" value="<?=$reqTanggalAkhir?>" required /> -->
	                    </td>
	                </tr>
	                <tr>
	                    <td>Batas Awal - Akhir</td>
	                    <td>:</td>
	                    <td>
	                        <input type="text" id="reqBatasAwal" name="reqBatasAwal" size="6" class="easyui-validatebox" value="<?=$reqBatasAwal?>" onkeydown="return format_detik(event,'reqBatasAwal');" maxlength="8" /> 
	                        <input type="text" id="reqBatasAkhir" name="reqBatasAkhir" size="6" class="easyui-validatebox" value="<?=$reqBatasAkhir?>" onkeydown="return format_detik(event,'reqBatasAkhir');" maxlength="8" /> 
	                        * (format = hh:mm:ss)
	                    </td>
	                </tr>
				</thead>
			</table>

			<input type="hidden" name="reqId" value="<?=$reqId?>" />
			<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
			<input type="button" onclick="document.location.href='app/loadUrl/main/permohonan_dinas'" class="btn btn-primary" value="Kembali" />
			<?
			if($simpan == "")
			{
			?>
			<input type="submit" name="reqSubmit" class="btn btn-primary" value="Simpan" />
			<?
			}
			?>
		</form>
	</div>
</body>
</html>