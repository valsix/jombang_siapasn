<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$validasiakses= $CI->validasiakses('5401');
// echo $validasiakses;exit;

$this->load->model('main/PermohonanFile');
$this->load->model('main/KerjaJam');

$jenis= "jadwal_jam_kerja";

$arrjenisjamkerja= jenisjamkerja();
$jumlahjamkerja= count($arrjenisjamkerja);
// print_r($arrjenisjamkerja);exit();

$reqId= $this->input->get("reqId");

$set= new KerjaJam();
if($reqId == "")
{
	$reqMode = "insert";
	$reqStatusJamKerjaRamadhan= "1";
}
else
{
	$reqMode = "update";
	$set->selectByParams(array("A.KERJA_JAM_ID"=>$reqId));
	// echo $set->query;exit();
	$set->firstRow();

	$reqNamaJamKerja= $set->getField("NAMA_JAM_KERJA");
	$reqJenisJamKerja= $set->getField("JENIS_JAM_KERJA");
	$reqHariKhusus= $set->getField("HARI_KHUSUS");
	$reqMulaiBerlaku= dateTimeToPageCheck($set->getField("MULAI_BERLAKU_INFO"));
	$reqAkhirBerlaku= dateTimeToPageCheck($set->getField("AKHIR_BERLAKU_INFO"));
	$reqMulaiRamadhanBerlaku= dateTimeToPageCheck($set->getField("MULAI_RAMADHAN_BERLAKU_INFO"));
	$reqAkhirRamadhanBerlaku= dateTimeToPageCheck($set->getField("AKHIR_RAMADHAN_BERLAKU_INFO"));
	$reqMulaiRamadhanBerlakuInfo= getFormattedDateTime($set->getField("MULAI_RAMADHAN_BERLAKU_INFO"));
	$reqAkhirRamadhanBerlakuInfo= getFormattedDateTime($set->getField("AKHIR_RAMADHAN_BERLAKU_INFO"));

	$reqMulaiRamadhanBerlakuTanggal= datetimeToPage($set->getField("MULAI_RAMADHAN_BERLAKU_INFO"), "date");
	$reqMulaiRamadhanBerlakuWaktu= datetimeToPage($set->getField("MULAI_RAMADHAN_BERLAKU_INFO"), "");
	$reqAkhirRamadhanBerlakuTanggal= datetimeToPage($set->getField("AKHIR_RAMADHAN_BERLAKU_INFO"), "date");
	$reqAkhirRamadhanBerlakuWaktu= datetimeToPage($set->getField("AKHIR_RAMADHAN_BERLAKU_INFO"), "");

	$reqMasukNormal= $set->getField("MASUK_NORMAL");
	$reqMulaiMasukNormal= $set->getField("MULAI_MASUK_NORMAL");
	$reqAkhirMasukNormal= $set->getField("AKHIR_MASUK_NORMAL");
	$reqKeluarNormal= $set->getField("KELUAR_NORMAL");
	$reqMulaiKeluarNormal= $set->getField("MULAI_KELUAR_NORMAL");
	$reqAkhirKeluarNormal= $set->getField("AKHIR_KELUAR_NORMAL");
	$reqKeluarGantiHariNormal= $set->getField("KELUAR_GANTI_HARI_NORMAL");
	$reqStatusJamKerja= $set->getField("STATUS_JAM_KERJA");
	$reqAkhirAskNormal= $set->getField("AKHIR_ASK_NORMAL");
	$reqStatusCekNormal= $set->getField("STATUS_CEK_NORMAL");
	$reqAwalCekNormal= $set->getField("AWAL_CEK_NORMAL");
	$reqAkhirCekNormal= $set->getField("AKHIR_CEK_NORMAL");
	$reqAskNormal= $set->getField("STATUS_ASK_NORMAL");

	$reqMasukRamadhan= $set->getField("MASUK_RAMADHAN");
	$reqMulaiMasukRamadhan= $set->getField("MULAI_MASUK_RAMADHAN");
	$reqAkhirMasukRamadhan= $set->getField("AKHIR_MASUK_RAMADHAN");
	$reqKeluarRamadhan= $set->getField("KELUAR_RAMADHAN");
	$reqMulaiKeluarRamadhan= $set->getField("MULAI_KELUAR_RAMADHAN");
	$reqAkhirKeluarRamadhan= $set->getField("AKHIR_KELUAR_RAMADHAN");
	$reqKeluarGantiHariRamadhan= $set->getField("KELUAR_GANTI_HARI_RAMADHAN");
	$reqStatusJamKerjaRamadhan= $set->getField("STATUS_JAM_KERJA_RAMADHAN");
	$reqAkhirAskRamadhan= $set->getField("AKHIR_ASK_RAMADHAN");
	$reqStatusCekRamadhan= $set->getField("STATUS_CEK_RAMADHAN");
	$reqAwalCekRamadhan= $set->getField("AWAL_CEK_RAMADHAN");
	$reqAkhirCekRamadhan= $set->getField("AKHIR_CEK_RAMADHAN");
	$reqAskRamadhan= $set->getField("STATUS_ASK_RAMADHAN");

	$reqLastUser= $set->getField("LAST_USER");
	$reqLastUpdate= dateTimeToPageCheck($set->getField("LAST_DATE_INFO"));
}

$simpan= $disabled= "";
if($validasiakses == "R")
{
	$simpan= "1";
	$disabled= "disabled";
}
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
<!-- <script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> -->

<!-- AUTO KOMPLIT -->
<link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
<script src="lib/autokomplit/jquery-ui.js"></script>

<script src='lib/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='lib/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='lib/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>

<link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

<script type="text/javascript">	
$(function(){
	$.fn.window.defaults.closable = false;
	$('#ff').form({
		url:'main/jadwal_json/kerjajam_add',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data) {
			// console.log(data);return false;
			data = data.split("-");
			rowid= data[0];
			infodata= data[1];


			if(rowid == "xxx")
			{
				$.messager.alert('Error', infodata, 'error');
			}
			else
			{
				$.messager.alert('Info',infodata,'info',function(){
					document.location.href = "app/loadUrl/main/<?=$jenis?>";
				});
			}
		}
	});

});

</script>
</head>

<body class="bg-permohonan">
	<div class="area-permohonan">
		<div class="judul-monitoring"><span>Form Jadwal Jam Kerja</span></div>
        <form id="ff" method="post" novalidate enctype="multipart/form-data">
			<ul class="nav nav-tabs">
                <li class="active">
                    <a data-toggle="tab" href="#tab-general">
                        <span style="display: none;" id="tab-general-success"><i class="fa fa-check-circle text-success" aria-hidden="true"></i></span>
                        <span id="tab-general-danger"><i class="fa fa-times-circle text-danger" aria-hidden="true"></i></span>
                        General
                    </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#tab-normal">
                        <span style="display: none;" id="tab-normal-success"><i class="fa fa-check-circle text-success" aria-hidden="true"></i></span>
                        <span id="tab-normal-danger"><i class="fa fa-times-circle text-danger" aria-hidden="true"></i></span>
                        Normal
                    </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#tab-ramadhan">
                        <span style="display: none;" id="tab-ramadhan-success"><i class="fa fa-check-circle text-success" aria-hidden="true"></i></span>
                        <span id="tab-ramadhan-danger"><i class="fa fa-times-circle text-danger" aria-hidden="true"></i></span>
                        Ramadhan
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div id="tab-general" class="tab-pane fade in active">
					<table class="table">
						<thead>
							<tr>
			                    <td style="width: 15%">Nama Jam Kerja</td>
			                    <td>:</td>
			                    <td>
			                    	<input type="text" id="reqNamaJamKerja" name="reqNamaJamKerja" class="easyui-validatebox" style="width:50%;" value="<?=$reqNamaJamKerja?>" <?=$disabled?> />
			                    </td>
			                </tr>
							<tr>
		                        <td>Jenis Jam Kerja</td>
		                        <td>:</td>
		                        <td>
		                            <select name="reqJenisJamKerja" id="reqJenisJamKerja" <?=$disabled?> >
		                            	<option value="">Pilih</option>
		                            	<?
		                            	for ($i=0; $i < $jumlahjamkerja; $i++) 
		                            	{ 
		                            		$infoid= $arrjenisjamkerja[$i]["id"];
		                            		$infonama= $arrjenisjamkerja[$i]["nama"];
		                            	?>
		                            		<option value="<?=$infoid?>" <? if($reqJenisJamKerja == $infoid) { ?> selected="selected" <? } ?> >
		                            			<?=$infonama?>
		                            		</option>
		                            	<?
		                            	}
		                            	?>
		                            </select>
		                        </td>
		                    </tr>
		                    <tr>
		                        <td>Hari Khusus</td>
		                        <td>:</td>
		                        <td>
		                            <select name="reqHariKhusus" id="reqHariKhusus" <?=$disabled?> >
		                            	<option value="">-</option>
		                            	<option value="1" <? if($reqHariKhusus == "1") echo "selected";?>>Jumat</option>
		                            	<option value="2" <? if($reqHariKhusus == "2") echo "selected";?>>Sabtu</option>
		                            </select>
		                        </td>
		                    </tr>
		                    <tr>
			                    <td>Berlaku</td>
			                    <td>:</td>
			                    <td>
			                    	<input class="easyui-datetimebox" name="reqMulaiBerlaku" data-options="required:true, showSeconds:true" style="width:155px" value="<?=$reqMulaiBerlaku?>" <?=$disabled?> />
			                    	s/d
			                    	<input class="easyui-datetimebox" name="reqAkhirBerlaku" data-options="required:true, showSeconds:true" style="width:155px" value="<?=$reqAkhirBerlaku?>" <?=$disabled?> />
			                    </td>
			                </tr>
						</thead>
					</table>
				</div>

				<div id="tab-normal" class="tab-pane fade">
					<table class="table">
						<thead>
			                <tr>
			                    <td style="width: 15%">
				                    Masuk <span class="infolabel"><br/>(hh:mm:ss)</span>
				                </td>
			                    <td style="width: 10px">:</td>
			                    <td colspan="4">
			                    	<input type="text" id="reqMasukNormal" name="reqMasukNormal" size="8" class="easyui-validatebox" onkeydown="return format_detik(event,'reqMasukNormal');" maxlength="8" value="<?=$reqMasukNormal?>" <?=$disabled?> />
			                    </td>
			                    <td style="width: 10%">Status A/S/K</td>
		                        <td style="width: 10px">:</td>
		                        <td>
		                            <select name="reqAskNormal" id="reqAskNormal" <?=$disabled?> >
		                            	<option value="">Tidak Aktif</option>
		                            	<option value="1" <? if($reqAskNormal == "1") echo "selected";?>>Aktif</option>
		                            </select>
		                        </td>
		                        <td>
				                    Akhir A/S/K <span class="infolabel"><br/>(hh:mm:ss)</span>
				                </td>
			                    <td>:</td>
			                    <td>
			                    	<input type="text" id="reqAkhirAskNormal" name="reqAkhirAskNormal" size="8" class="easyui-validatebox" onkeydown="return format_detik(event,'reqAkhirAskNormal');" maxlength="8" value="<?=$reqAkhirAskNormal?>" <?=$disabled?> />
			                    </td>
			                </tr>
			                <tr>
			                    <td>
				                    Mulai Masuk <span class="infolabel"><br/>(hh:mm:ss)</span>
				                </td>
			                    <td>:</td>
			                    <td style="width: 10%">
			                    	<input type="text" id="reqMulaiMasukNormal" name="reqMulaiMasukNormal" size="8" class="easyui-validatebox" onkeydown="return format_detik(event,'reqMulaiMasukNormal');" maxlength="8" value="<?=$reqMulaiMasukNormal?>" <?=$disabled?> />
			                    </td>
			                    <td style="width: 10%">
				                    Akhir Masuk <span class="infolabel"><br/>(hh:mm:ss)</span>
				                </td>
			                    <td style="width: 10px">:</td>
			                    <td style="width: 10%">
			                    	<input type="text" id="reqAkhirMasukNormal" name="reqAkhirMasukNormal" size="8" class="easyui-validatebox" onkeydown="return format_detik(event,'reqAkhirMasukNormal');" maxlength="8" value="<?=$reqAkhirMasukNormal?>" <?=$disabled?> />
			                    </td>
			                    <td>Status Cek</td>
		                        <td>:</td>
		                        <td colspan="4">
		                            <select name="reqStatusCekNormal" id="reqStatusCekNormal" <?=$disabled?> >
		                            	<option value="">Tidak Aktif</option>
		                            	<option value="1" <? if($reqStatusCekNormal == "1") echo "selected";?>>Aktif</option>
		                            </select>
		                        </td>
			                </tr>
			                <tr>
			                    <td>
				                    Keluar <span class="infolabel"><br/>(hh:mm:ss)</span>
				                </td>
			                    <td>:</td>
			                    <td colspan="4">
			                    	<input type="text" id="reqKeluarNormal" name="reqKeluarNormal" size="8" class="easyui-validatebox" onkeydown="return format_detik(event,'reqKeluarNormal');" maxlength="8" value="<?=$reqKeluarNormal?>" <?=$disabled?> />
			                    </td>
			                    <td>
				                    Awal Cek <span class="infolabel"><br/>(hh:mm:ss)</span>
				                </td>
			                    <td>:</td>
			                    <td>
			                    	<input type="text" id="reqAwalCekNormal" name="reqAwalCekNormal" size="8" class="easyui-validatebox" onkeydown="return format_detik(event,'reqAwalCekNormal');" maxlength="8" value="<?=$reqAwalCekNormal?>" <?=$disabled?> />
			                    </td>
			                    <td>
				                    Akhir Cek <span class="infolabel"><br/>(hh:mm:ss)</span>
				                </td>
			                    <td>:</td>
			                    <td>
			                    	<input type="text" id="reqAkhirCekNormal" name="reqAkhirCekNormal" size="8" class="easyui-validatebox" onkeydown="return format_detik(event,'reqAkhirCekNormal');" maxlength="8" value="<?=$reqAkhirCekNormal?>" <?=$disabled?> />
			                    </td>
			                </tr>
			                <tr>
			                    <td>
				                    Mulai Keluar <span class="infolabel"><br/>(hh:mm:ss)</span>
				                </td>
			                    <td>:</td>
			                    <td>
			                    	<input type="text" id="reqMulaiKeluarNormal" name="reqMulaiKeluarNormal" size="8" class="easyui-validatebox" onkeydown="return format_detik(event,'reqMulaiKeluarNormal');" maxlength="8" value="<?=$reqMulaiKeluarNormal?>" <?=$disabled?> />
			                    </td>
			                    <td>
				                    Akhir Keluar <span class="infolabel"><br/>(hh:mm:ss)</span>
				                </td>
			                    <td>:</td>
			                    <td>
			                    	<input type="text" id="reqAkhirKeluarNormal" name="reqAkhirKeluarNormal" size="8" class="easyui-validatebox" onkeydown="return format_detik(event,'reqAkhirKeluarNormal');" maxlength="8" value="<?=$reqAkhirKeluarNormal?>" <?=$disabled?> />
			                    </td>
			                    <td>Keluar Ganti Hari</td>
		                        <td>:</td>
		                        <td>
		                            <select name="reqKeluarGantiHariNormal" id="reqKeluarGantiHariNormal" <?=$disabled?> >
		                            	<option value="">Tidak</option>
		                            	<option value="1" <? if($reqKeluarGantiHariNormal == "1") echo "selected";?>>Ya</option>
		                            </select>
		                        </td>
		                        <td>Status Jam Kerja</td>
		                        <td>:</td>
		                        <td>
		                            <select name="reqStatusJamKerja" id="reqStatusJamKerja" <?=$disabled?> >
		                            	<option value="">Aktif</option>
		                            	<option value="1" <? if($reqStatusJamKerja == "1") echo "selected";?>>Tidak Aktif</option>
		                            </select>
		                        </td>
			                </tr>
						</thead>
					</table>
				</div>
				
				<div id="tab-ramadhan" class="tab-pane fade">
					<table class="table">
						<thead>
			                <tr>
			                	<td style="width: 15%">
				                    Status Jam Kerja
				                </td>
			                    <td style="width: 10px">:</td>
			                    <td>
				                	<select name="reqStatusJamKerjaRamadhan" id="reqStatusJamKerjaRamadhan" <?=$disabled?> >
				                		<option value="">Aktif</option>
				                		<option value="1" <? if($reqStatusJamKerjaRamadhan == "1") echo "selected";?>>Tidak Aktif</option>
				                	</select>
				                </td>
			                    <td style="width: 15%">
				                    Masuk <span class="infolabel"><br/>(hh:mm:ss)</span>
				                </td>
			                    <td style="width: 10px">:</td>
			                    <td>
			                    	<input type="text" id="reqMasukRamadhan" name="reqMasukRamadhan" size="8" class="easyui-validatebox" onkeydown="return format_detik(event,'reqMasukRamadhan');" maxlength="8" value="<?=$reqMasukRamadhan?>" <?=$disabled?> />
			                    </td>
			                    <td style="width: 10%">Status A/S/K</td>
		                        <td style="width: 10px">:</td>
		                        <td>
		                            <select name="reqAskRamadhan" id="reqAskRamadhan" <?=$disabled?> >
		                            	<option value="">Tidak Aktif</option>
		                            	<option value="1" <? if($reqAskRamadhan == "1") echo "selected";?>>Aktif</option>
		                            </select>
		                        </td>
		                        <td>
				                    Akhir A/S/K <span class="infolabel"><br/>(hh:mm:ss)</span>
				                </td>
			                    <td>:</td>
			                    <td>
			                    	<input type="text" id="reqAkhirAskRamadhan" name="reqAkhirAskRamadhan" size="8" class="easyui-validatebox" onkeydown="return format_detik(event,'reqAkhirAskRamadhan');" maxlength="8" value="<?=$reqAkhirAskRamadhan?>" <?=$disabled?> />
			                    </td>
			                </tr>
			                <tr>
			                    <td>
				                    Mulai Masuk <span class="infolabel"><br/>(hh:mm:ss)</span>
				                </td>
			                    <td>:</td>
			                    <td style="width: 10%">
			                    	<input type="text" id="reqMulaiMasukRamadhan" name="reqMulaiMasukRamadhan" size="8" class="easyui-validatebox" onkeydown="return format_detik(event,'reqMulaiMasukRamadhan');" maxlength="8" value="<?=$reqMulaiMasukRamadhan?>" <?=$disabled?> />
			                    </td>
			                    <td style="width: 10%">
				                    Akhir Masuk <span class="infolabel"><br/>(hh:mm:ss)</span>
				                </td>
			                    <td style="width: 10px">:</td>
			                    <td style="width: 10%">
			                    	<input type="text" id="reqAkhirMasukRamadhan" name="reqAkhirMasukRamadhan" size="8" class="easyui-validatebox" onkeydown="return format_detik(event,'reqAkhirMasukRamadhan');" maxlength="8" value="<?=$reqAkhirMasukRamadhan?>" <?=$disabled?> />
			                    </td>
			                    <td>Status Cek</td>
		                        <td>:</td>
		                        <td>
		                            <select name="reqStatusCekRamadhan" id="reqStatusCekRamadhan" <?=$disabled?> >
		                            	<option value="">Tidak Aktif</option>
		                            	<option value="1" <? if($reqStatusCekRamadhan == "1") echo "selected";?>>Aktif</option>
		                            </select>
		                        </td>
		                        <td>Keluar Ganti Hari</td>
		                        <td>:</td>
		                        <td>
		                            <select name="reqKeluarGantiHariRamadhan" id="reqKeluarGantiHariRamadhan" <?=$disabled?> >
		                            	<option value="">Tidak</option>
		                            	<option value="1" <? if($reqKeluarGantiHariRamadhan == "1") echo "selected";?>>Ya</option>
		                            </select>
		                        </td>
			                </tr>
			                <tr>
			                    <td>
				                    Keluar <span class="infolabel"><br/>(hh:mm:ss)</span>
				                </td>
			                    <td>:</td>
			                    <td colspan="4">
			                    	<input type="text" id="reqKeluarRamadhan" name="reqKeluarRamadhan" size="8" class="easyui-validatebox" onkeydown="return format_detik(event,'reqKeluarRamadhan');" maxlength="8" value="<?=$reqKeluarRamadhan?>" <?=$disabled?> />
			                    </td>
			                    <td>
				                    Awal Cek <span class="infolabel"><br/>(hh:mm:ss)</span>
				                </td>
			                    <td>:</td>
			                    <td>
			                    	<input type="text" id="reqAwalCekRamadhan" name="reqAwalCekRamadhan" size="8" class="easyui-validatebox" onkeydown="return format_detik(event,'reqAwalCekRamadhan');" maxlength="8" value="<?=$reqAwalCekRamadhan?>" <?=$disabled?> />
			                    </td>
			                    <td>
				                    Akhir Cek <span class="infolabel"><br/>(hh:mm:ss)</span>
				                </td>
			                    <td>:</td>
			                    <td>
			                    	<input type="text" id="reqAkhirCekRamadhan" name="reqAkhirCekRamadhan" size="8" class="easyui-validatebox" onkeydown="return format_detik(event,'reqAkhirCekRamadhan');" maxlength="8" value="<?=$reqAkhirCekRamadhan?>" <?=$disabled?> />
			                    </td>
			                </tr>
			                <tr>
			                    <td>
				                    Mulai Keluar <span class="infolabel"><br/>(hh:mm:ss)</span>
				                </td>
			                    <td>:</td>
			                    <td>
			                    	<input type="text" id="reqMulaiKeluarRamadhan" name="reqMulaiKeluarRamadhan" size="8" class="easyui-validatebox" onkeydown="return format_detik(event,'reqMulaiKeluarRamadhan');" maxlength="8" value="<?=$reqMulaiKeluarRamadhan?>" <?=$disabled?> />
			                    </td>
			                    <td>
				                    Akhir Keluar <span class="infolabel"><br/>(hh:mm:ss)</span>
				                </td>
			                    <td>:</td>
			                    <td>
			                    	<input type="text" id="reqAkhirKeluarRamadhan" name="reqAkhirKeluarRamadhan" size="8" class="easyui-validatebox" onkeydown="return format_detik(event,'reqAkhirKeluarRamadhan');" maxlength="8" value="<?=$reqAkhirKeluarRamadhan?>" <?=$disabled?> />
			                    </td>
		                        <td>Berlaku</td>
		                        <td>:</td>
		                        <td colspan="4">
		                        	<!-- <input class="easyui-datetimebox" id="reqMulaiRamadhanBerlaku" name="reqMulaiRamadhanBerlaku" data-options="showSeconds:true" style="width:155px" value="<?=$reqMulaiRamadhanBerlaku?>" <?=$disabled?> />
			                    	s/d
			                    	<input class="easyui-datetimebox" id="reqAkhirRamadhanBerlaku" name="reqAkhirRamadhanBerlaku" data-options="showSeconds:true" style="width:155px" value="<?=$reqAkhirRamadhanBerlaku?>" <?=$disabled?> /> -->
			                    	<!-- <label id="reqMulaiRamadhanBerlakuTanggal"><?=$reqMulaiRamadhanBerlakuTanggal?></label>
			                    	<input type="text" id="reqMulaiRamadhanBerlakuWaktu" name="reqMulaiRamadhanBerlakuWaktu" size="8" class="easyui-validatebox" onkeydown="return format_detik(event,'reqMulaiRamadhanBerlakuWaktu');" maxlength="8" value="<?=$reqMulaiRamadhanBerlakuWaktu?>" />
			                    	<input type="hidden" id="reqMulaiRamadhanBerlaku" name="reqMulaiRamadhanBerlaku" value="<?=$reqMulaiRamadhanBerlaku?>" />
			                    	&nbsp;&nbsp;&nbsp;s/d&nbsp;&nbsp;&nbsp;
			                    	<label id="reqAkhirRamadhanBerlakuTanggal"><?=$reqAkhirRamadhanBerlakuTanggal?></label>
			                    	<input type="text" id="reqAkhirRamadhanBerlakuWaktu" name="reqAkhirRamadhanBerlakuWaktu" size="8" class="easyui-validatebox" onkeydown="return format_detik(event,'reqAkhirRamadhanBerlakuWaktu');" maxlength="8" value="<?=$reqAkhirRamadhanBerlakuWaktu?>" />
			                    	<input type="hidden" id="reqAkhirRamadhanBerlaku" name="reqAkhirRamadhanBerlaku" value="<?=$reqAkhirRamadhanBerlaku?>" /> -->
			                    	<label id="reqMulaiRamadhanBerlakuInfo"><?=$reqMulaiRamadhanBerlakuInfo?></label>
			                    	<input type="hidden" id="reqMulaiRamadhanBerlaku" name="reqMulaiRamadhanBerlaku" value="<?=$reqMulaiRamadhanBerlaku?>" />
			                    	&nbsp;&nbsp;&nbsp;s/d&nbsp;&nbsp;&nbsp;
			                    	<label id="reqAkhirRamadhanBerlakuInfo"><?=$reqAkhirRamadhanBerlakuInfo?></label>
			                    	<input type="hidden" id="reqAkhirRamadhanBerlaku" name="reqAkhirRamadhanBerlaku" value="<?=$reqAkhirRamadhanBerlaku?>" />
		                        	<!-- <input type="hidden" id="reqStatusJamKerjaRamadhan" name="reqStatusJamKerjaRamadhan" value="1" /> -->
		                        </td>
			                </tr>
						</thead>
					</table>
				</div>
			</div>

			<table class="table">
				<thead>
					<tr>
						<td style="width: 10%">User Terakhir</td>
						<td style="width: 10px">:</td>
						<td><?=$reqLastUser?></td>
					</tr>
					<tr>
						<td style="width: 15%">Dibuat/update Terakhir</td>
						<td style="width: 10px">:</td>
						<td><?=$reqLastUpdate?></td>
					</tr>
				</thead>
			</table>

			<input type="hidden" name="reqId" value="<?=$reqId?>" />
			<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
			<input type="button" onclick="document.location.href='app/loadUrl/main/<?=$jenis?>'" class="btn btn-primary" value="Kembali" />
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
    
    <script type="text/javascript">
    	$(function(){
    		setstatusrequired("Normal");
    		setstatusrequired("Ramadhan");
    		setstatusaskrequired("Normal");
    		setstatusaskrequired("Ramadhan");
    		setstatuscekrequired("Normal");
    		setstatuscekrequired("Ramadhan");

	    	$('[id^="reqStatusJamKerja"]').change(function(e) {
	    		infoid= $(this).attr('id');
	    		// console.log(infoid);
	    		jenis= "Normal";
	    		if(infoid == "reqStatusJamKerjaRamadhan")
	    			jenis= "Ramadhan";
	    		
	    		setstatusrequired(jenis);
	    	});

	    	$('[id^="reqAsk"]').change(function(e) {
	    		infoid= $(this).attr('id');
	    		// console.log(infoid);
	    		jenis= "Normal";
	    		if(infoid == "reqAskRamadhan")
	    			jenis= "Ramadhan";
	    		
	    		setstatusaskrequired(jenis);
	    	});

	    	$('[id^="reqStatusCek"]').change(function(e) {
	    		infoid= $(this).attr('id');
	    		// console.log(infoid);
	    		jenis= "Normal";
	    		if(infoid == "reqStatusCekRamadhan")
	    			jenis= "Ramadhan";
	    		
	    		setstatuscekrequired(jenis);
	    	});

	    	$('input[id^="reqMasuk"], input[id^="reqMulaiMasuk"], input[id^="reqAkhirMasuk"], input[id^="reqKeluar"], input[id^="reqMulaiKeluar"], input[id^="reqAkhirKeluar"], input[id^="reqAkhirAsk"], input[id^="reqAwalCek"], input[id^="reqAkhirCek"]').keyup(function(e) {
	    		setinfovalidasi();
	    	});

	    	$("#reqMulaiRamadhanBerlakuWaktu,#reqAkhirRamadhanBerlaku").keyup(function(e) {
	    		infoid= $(this).attr('id');
	    		infoval= $(this).val();
	    		if(infoid == "reqMulaiRamadhanBerlakuWaktu")
	    		{
	    			reqMulaiRamadhanBerlakuTanggal= $("#reqMulaiRamadhanBerlakuTanggal").text();
	    			reqMulaiRamadhanBerlaku= reqMulaiRamadhanBerlakuTanggal+" "+infoval;
	    			$("#reqMulaiRamadhanBerlaku").val(reqMulaiRamadhanBerlaku);
	    		}
	    		else
	    		{
	    			reqAkhirRamadhanBerlakuTanggal= $("#reqAkhirRamadhanBerlakuTanggal").text();
	    			reqAkhirRamadhanBerlaku= reqAkhirRamadhanBerlakuTanggal+" "+infoval;
	    			$("#reqAkhirRamadhanBerlaku").val(reqAkhirRamadhanBerlaku);
	    		}

	    		setinfovalidasi();
	    	});

	    	// $("#reqMulaiRamadhanBerlaku,#reqAkhirRamadhanBerlaku").datetimebox({
	    	// 	onChange: function (newValue, oldValue) {
	    	// 		setinfovalidasi();
	    	// 	}
	    	// });
	    });

	    function setstatusrequired(jenis)
	    {
	    	if(jenis == "Normal")
	    		reqStatusJamKerja= $("#reqStatusJamKerja").val();
	    	else
	    		reqStatusJamKerja= $("#reqStatusJamKerjaRamadhan").val();

	    	if(reqStatusJamKerja == "1")
	    	{
	    		$("#reqMasuk"+jenis+",#reqMulaiMasuk"+jenis+",#reqAkhirMasuk"+jenis+",#reqKeluar"+jenis+",#reqMulaiKeluar"+jenis+",#reqAkhirKeluar"+jenis).validatebox({required: false});
	    		// +",#reqAkhirAsk"+jenis+",#reqAwalCek"+jenis+",#reqAkhirCek"+jenis
	    		$("#reqMasuk"+jenis+",#reqMulaiMasuk"+jenis+",#reqAkhirMasuk"+jenis+",#reqKeluar"+jenis+",#reqMulaiKeluar"+jenis+",#reqAkhirKeluar"+jenis).removeClass('validatebox-invalid');
	    		// +",#reqAkhirAsk"+jenis+",#reqAwalCek"+jenis+",#reqAkhirCek"+jenis

	    		if(jenis == "Normal"){}
	    		else
	    		{
	    			// $("#reqMulaiRamadhanBerlaku,#reqAkhirRamadhanBerlaku").datetimebox({required: false});
		    		// $("#reqMulaiRamadhanBerlaku,#reqAkhirRamadhanBerlaku").removeClass('validatebox-invalid');
	    		}
	    	}
	    	else
	    	{
	    		$("#reqMasuk"+jenis+",#reqMulaiMasuk"+jenis+",#reqAkhirMasuk"+jenis+",#reqKeluar"+jenis+",#reqMulaiKeluar"+jenis+",#reqAkhirKeluar"+jenis).validatebox({required: true});
	    		// +",#reqAkhirAsk"+jenis+",#reqAwalCek"+jenis+",#reqAkhirCek"+jenis

	    		if(jenis == "Normal"){}
	    		else
	    		{
	    			// $("#reqMulaiRamadhanBerlaku,#reqAkhirRamadhanBerlaku").datetimebox({required: true});
	    		}
	    	}

	    	setinfovalidasi();
	    }

	    function setstatusaskrequired(jenis)
	    {
	    	if(jenis == "Normal")
	    		reqAsk= $("#reqAskNormal").val();
	    	else
	    		reqAsk= $("#reqAskRamadhan").val();

	    	if(reqAsk == "")
	    	{
	    		$("#reqAkhirAsk"+jenis).validatebox({required: false});
	    		$("#reqAkhirAsk"+jenis).removeClass('validatebox-invalid');
	    	}
	    	else
	    	{
	    		$("#reqAkhirAsk"+jenis).validatebox({required: true});
	    	}

	    	setinfovalidasi();
	    }

	    function setstatuscekrequired(jenis)
	    {
	    	if(jenis == "Normal")
	    		reqStatusCek= $("#reqStatusCekNormal").val();
	    	else
	    		reqStatusCek= $("#reqStatusCekRamadhan").val();

	    	if(reqStatusCek == "")
	    	{
	    		$("#reqAwalCek"+jenis+",#reqAkhirCek"+jenis).validatebox({required: false});
	    		$("#reqAwalCek"+jenis+",#reqAkhirCek"+jenis).removeClass('validatebox-invalid');
	    	}
	    	else
	    	{
	    		$("#reqAwalCek"+jenis+",#reqAkhirCek"+jenis).validatebox({required: true});
	    	}

	    	setinfovalidasi();
	    }

    	function setundefined(val)
	    {
	        if(typeof val == "undefined")
	            val= "";
	        return val;
	    }

	    function setinfovalidasi()
	    {
	    	reqNamaJamKerja= $("#reqNamaJamKerja").val();
	    	$("#tab-general-danger").hide();
    		$("#tab-general-success").show();
    		if(reqNamaJamKerja == "")
    		{
	    		$("#tab-general-danger").show();
	    		$("#tab-general-success").hide();
    		}

	    	jenis= "Normal";
	    	reqStatusJamKerja= $("#reqStatusJamKerja").val();
	    	reqAsk= $("#reqAskNormal").val();
	    	reqStatusCek= $("#reqStatusCekNormal").val();
	    	if(reqStatusJamKerja == "1")
	    	{
	    		$("#tab-normal-danger").hide();
	    		$("#tab-normal-success").show();
	    	}
	    	else
	    	{
	    		reqMasuk= $("#reqMasuk"+jenis).val();
	    		reqMulaiMasuk= $("#reqMulaiMasuk"+jenis).val();
	    		reqAkhirMasuk= $("#reqAkhirMasuk"+jenis).val();
	    		reqKeluar= $("#reqKeluar"+jenis).val();
	    		reqMulaiKeluar= $("#reqMulaiKeluar"+jenis).val();
	    		reqAkhirKeluar= $("#reqAkhirKeluar"+jenis).val();
	    		reqAkhirAsk= $("#reqAkhirAsk"+jenis).val();
	    		reqAwalCek= $("#reqAwalCek"+jenis).val();
	    		reqAkhirCek= $("#reqAkhirCek"+jenis).val();
	    		// reqKeluarGantiHari= $("#reqKeluarGantiHari"+jenis).val();
	    		// reqStatusCek= $("#reqStatusCek"+jenis).val();
	    		// reqAsk= $("#reqAsk"+jenis).val();

	    		$("#tab-normal-danger").hide();
	    		$("#tab-normal-success").show();
	    		if(reqMasuk == "" || reqMulaiMasuk == "" || reqAkhirMasuk == "" || reqKeluar == "" || reqMulaiKeluar == "" || reqAkhirKeluar == "" || (reqAwalCek == "" && reqStatusCek == "1") || (reqAkhirCek == "" && reqStatusCek == "1") || (reqAkhirAsk == "" && reqAsk == "1"))
	    		{
		    		$("#tab-normal-danger").show();
		    		$("#tab-normal-success").hide();
	    		}
	    	}

	    	jenis= "Ramadhan";
	    	reqStatusJamKerja= $("#reqStatusJamKerjaRamadhan").val();
	    	reqAsk= $("#reqAskRamadhan").val();
	    	reqStatusCek= $("#reqStatusCekRamadhan").val();
	    	if(reqStatusJamKerja == "1")
	    	{
	    		$("#tab-ramadhan-danger").hide();
	    		$("#tab-ramadhan-success").show();
	    	}
	    	else
	    	{
	    		reqMasuk= $("#reqMasuk"+jenis).val();
	    		reqMulaiMasuk= $("#reqMulaiMasuk"+jenis).val();
	    		reqAkhirMasuk= $("#reqAkhirMasuk"+jenis).val();
	    		reqKeluar= $("#reqKeluar"+jenis).val();
	    		reqMulaiKeluar= $("#reqMulaiKeluar"+jenis).val();
	    		reqAkhirKeluar= $("#reqAkhirKeluar"+jenis).val();
	    		reqAkhirAsk= $("#reqAkhirAsk"+jenis).val();
	    		reqAwalCek= $("#reqAwalCek"+jenis).val();
	    		reqAkhirCek= $("#reqAkhirCek"+jenis).val();
	    		// reqKeluarGantiHari= $("#reqKeluarGantiHari"+jenis).val();
	    		// reqStatusCek= $("#reqStatusCek"+jenis).val();
	    		// reqAsk= $("#reqAsk"+jenis).val();
	    		// reqMulaiRamadhanBerlaku= $("#reqMulaiRamadhanBerlaku").datebox('getValue');
	    		// reqAkhirRamadhanBerlaku= $("#reqAkhirRamadhanBerlaku").datebox('getValue');
	    		reqMulaiRamadhanBerlaku= $("#reqMulaiRamadhanBerlaku").val();
	    		reqAkhirRamadhanBerlaku= $("#reqAkhirRamadhanBerlaku").val();

	    		$("#tab-ramadhan-danger").hide();
	    		$("#tab-ramadhan-success").show();
	    		if(reqMasuk == "" || reqMulaiMasuk == "" || reqAkhirMasuk == "" || reqKeluar == "" || reqMulaiKeluar == "" || reqAkhirKeluar == "" || (reqAwalCek == "" && reqStatusCek == "1") || (reqAkhirCek == "" && reqStatusCek == "1") || (reqAkhirAsk == "" && reqAsk == "1") || reqMulaiRamadhanBerlaku == "" || reqAkhirRamadhanBerlaku == "")
	    		{
		    		$("#tab-ramadhan-danger").show();
		    		$("#tab-ramadhan-success").hide();
	    		}
	    	}
	    }
    </script>
    <script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> 
</body>
</html>