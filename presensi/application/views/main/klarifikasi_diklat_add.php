<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('main/PermohonanFile');
$this->load->model('main/Klarifikasi');

$jenis= "klarifikasi_diklat";

$reqId= $this->input->get("reqId");

$set= new Klarifikasi();
if($reqId == "")
{
	$reqMode = "insert";
	// $reqTanggalSurat= $reqTanggalAwal= $reqTanggalAkhir= date("d-m-Y");
	$reqNoSurat= "";
}
else
{
	$index=0;
	$arrdata= [];
	$reqMode = "update";
	$set->selectByParamsKlarifikasi(array("A.KLARIFIKASI_ID" => $reqId));
	// echo $set->query;exit();
	while($set->nextRow())
	{
		$arrdata[$index]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
		$arrdata[$index]["NOMOR_SURAT"]= $set->getField("NOMOR_SURAT");
		$arrdata[$index]["TANGGAL_SURAT"]= dateToPageCheck($set->getField("TANGGAL_SURAT"));
		$arrdata[$index]["TANGGAL_MULAI"]= dateToPageCheck($set->getField("TANGGAL_MULAI"));
		$arrdata[$index]["TANGGAL_SELESAI"]= dateToPageCheck($set->getField("TANGGAL_SELESAI"));
		$arrdata[$index]["JABATAN_RIWAYAT_ID"]= $set->getField("JABATAN_RIWAYAT_ID");
		$arrdata[$index]["JABATAN_RIWAYAT_NAMA"]= $set->getField("JABATAN_RIWAYAT_NAMA");
		$arrdata[$index]["PANGKAT_RIWAYAT_ID"]= $set->getField("PANGKAT_RIWAYAT_ID");
		$arrdata[$index]["NIP_BARU"]= $set->getField("NIP_BARU");
		$arrdata[$index]["NAMA_LENGKAP"]= $set->getField("NAMA_LENGKAP");
		$arrdata[$index]["LAST_USER"]= $set->getField("LAST_USER");
		$arrdata[$index]["LAST_UPDATE"]= getFormattedExtDateTimeCheck($set->getField("LAST_UPDATE"));
		$arrdata[$index]["LAST_CREATE_DATE"]= getFormattedExtDateTimeCheck($set->getField("LAST_CREATE_DATE"));
		$arrdata[$index]["KETERANGAN"]= $set->getField("KETERANGAN");
		$arrdata[$index]["UBAH_STATUS"]= $set->getField("UBAH_STATUS");
		$arrdata[$index]["STATUS"]= $set->getField("STATUS");
		$arrdata[$index]["ALASAN_TOLAK"]= $set->getField("ALASAN_TOLAK");
		$index++;
	}
	// print_r($arrdata);exit;
	$jumlahpegawai= $index;

	if($jumlahpegawai > 0)
	{
		$reqTanggalAwal= $arrdata[0]["TANGGAL_MULAI"];
		$reqTanggalAkhir= $arrdata[0]["TANGGAL_SELESAI"];
		$reqPegawaiId= $arrdata[0]["PEGAWAI_ID"];
		$reqPegawaiJabatanId= $arrdata[0]["JABATAN_RIWAYAT_ID"];
		$reqPegawaiJabatan= $arrdata[0]["JABATAN_RIWAYAT_NAMA"];
		$reqPegawaiPangkatId= $arrdata[0]["PANGKAT_RIWAYAT_ID"];
		$reqNoSurat= $arrdata[0]["NOMOR_SURAT"];
		$reqTanggalSurat= $arrdata[0]["TANGGAL_SURAT"];
		$reqPegawaiNip= $arrdata[0]["NIP_BARU"];
		$reqPegawaiNama= $arrdata[0]["NAMA_LENGKAP"];
		$reqLastUser= $arrdata[0]["LAST_USER"];
		$reqCreateDate= $arrdata[0]["LAST_CREATE_DATE"];
		$reqLastUpdate= $arrdata[0]["LAST_UPDATE"];
		$reqKeterangan= $arrdata[0]["KETERANGAN"];
		$reqUbahStatus= $arrdata[0]["UBAH_STATUS"];
		$reqStatus= $arrdata[0]["STATUS"];
		$reqAlasanTolak= $arrdata[0]["ALASAN_TOLAK"];
	}

	$set= new PermohonanFile();
	$index_data= 0;
	$arrPermohonanFile= [];
	$statement= " AND A.PERMOHONAN_TABLE_NAMA = '".$jenis."' AND A.PERMOHONAN_TABLE_ID = ".$reqId;
	$set->selectByParams(array(), -1,-1, $statement);
	// echo $set->query;exit;
	while($set->nextRow())
	{
		$arrPermohonanFile[$index_data]["PERMOHONAN_FILE_ID"]= $set->getField("PERMOHONAN_FILE_ID");
		$arrPermohonanFile[$index_data]["NAMA"]= $set->getField("NAMA");
		$arrPermohonanFile[$index_data]["TIPE"]= $set->getField("TIPE");
		$arrPermohonanFile[$index_data]["LINK_FILE"]= $set->getField("LINK_FILE");
		$arrPermohonanFile[$index_data]["KETERANGAN"]= $set->getField("KETERANGAN");
		$index_data++;
	}
	$jumlahpermohonanfile = $index_data;
}

$simpan= $disabled= "";
if($reqStatus == "Y" OR $reqStatus == "T")
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

<link rel="stylesheet" type="text/css" href="lib/datetimepicker/jquery.datetimepicker.css"/>
<script src="lib/datetimepicker/build/jquery.datetimepicker.full.js"></script>

<script type="text/javascript">	
$(function(){
	$.fn.window.defaults.closable = false;
	$('#ff').form({
		url:'main/klarifikasi_json/diklatadd',
		onSubmit:function(){

			reqPegawaiId= "";
			$('[name^=reqinfopegawaiid]').each(function() {
				reqPegawaiId= $(this).val();
			});

			if(reqPegawaiId == "")
			{
				$.messager.alert('Info', "Mohon Pilih Pegawai", 'info');
				return false;
			}

			reqUbahStatus= $("#reqUbahStatus").val();
			if(reqUbahStatus == "")
			{
				$.messager.alert('Info', "Mohon Pilih Ubah Semua Status", 'info');
				return false;
			}

			reqStatus= $("#reqStatus").val();
			reqAlasan= $("#reqAlasanTolak").val();

			if( (reqAlasan == "" || reqAlasan == null) && reqStatus == "T" )
			{
				$.messager.alert('Info', "isikan terlebih dahulu alasan.", 'info');
				return false;
			}
			
			return $(this).form('validate');
		},
		success:function(data){
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

	setalasantolak();
	$("#reqStatus").change(function() {
		setalasantolak();
	});
	
	var dataformat= { format: 'd-m-Y', scrollInput: false, autoclose: true, timepicker: false};
	const $in = $('#reqTanggalAwal'), $out = $('#reqTanggalAkhir');
	$in.datetimepicker(dataformat); $out.datetimepicker(dataformat);

	$in.on('change', function() {
	  var minDate = $in.val();
	  var startingVal = '';
	  if (minDate) 
	  {
	    minDate = minDate.split('-');
	    // console.log(minDate);
	    minDate = new Date(minDate[2], minDate[1] - 1, minDate[0], 0, 0);
	    startingVal = (function(aDate) {
	      var dd = "",
	        mm = "",
	        yyyy = "";
	      dd += aDate.getDate() + 1;
	      mm += (aDate.getMonth() + 1);
	      yyyy += aDate.getFullYear();

	      if (dd.length === 1)
	        dd = "0" + dd;
	      if (mm.length === 1)
	        mm = "0" + mm;

	      return dd + "-" + mm + "-" + yyyy;
	    })(minDate);

	  } 
	  else 
	  {
	    minDate = new Date(0, 0, 0, 0, 0);
	  }
	  dataformat.minDate = minDate;
	  //$out.datetimepicker('remove');
	  $out.datetimepicker(dataformat);
	  $out.val(startingVal);
	});

});

function setaddpegawai()
{
	reqTanggal= $('#reqTanggalAwal').val();
  	lengthdata= reqTanggal.length;
  	// console.log(lengthdata);
  	if(lengthdata == 10)
  	{
  		parent.openPopup('app/loadUrl/main/lookup_pegawai/?reqTanggal='+reqTanggal);
  		// addPegawai(reqTanggal);
  	}
  	else
  	{
  		$.messager.alert('Info', "isikan terlebih dahulu tanggal awal.", 'info');
  	}
}

function setsimpanulang()
{
	$.messager.confirm('Konfirmasi', 'Apakah anda yakin simpan ulang ?',function(r){
		if (r){
			$.ajax({
	            url: "main/klarifikasi_json/simpanulang/?reqId=<?=$reqId?>",
	            method: 'GET',
	            success: function (data) {
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
	            },
	            error: function (data) {
	            },
	            complete: function () {
	            }
	        });
		}
	});
}
</script>
</head>

<body class="bg-permohonan">
	<div class="area-permohonan">
		<div class="judul-monitoring"><span>Form Klarifikasi Diklat</span></div>

		<form id="ff" method="post" novalidate enctype="multipart/form-data">
			<table class="table">
				<thead>
					<tr>
	                    <td>Tanggal Mulai</td>
	                    <td>:</td>
	                    <td colspan="4">
	                    	<input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalAwal" id="reqTanggalAwal" maxlength="10" onKeyDown="return format_date(event,'reqTanggalAwal');" value="<?=$reqTanggalAwal?>" autocomplete="off" <?=$disabled?> />
	                    </td>
	                </tr>
	                <tr>
                		<td>Tanggal Selesai</td>
	                    <td>:</td>
	                    <td>
	                    	<input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalAkhir" id="reqTanggalAkhir" maxlength="10" onKeyDown="return format_date(event,'reqTanggalAkhir');" value="<?=$reqTanggalAkhir?>" autocomplete="off" <?=$disabled?> />
	                    </td>
	                    <td>User Terakhir</td>
	                    <td style="width: 1%">:</td>
	                    <td style="width: 30%">
	                    	<label><?=$reqLastUser?></label>
	                    </td>
	                </tr>
	                <tr>
	                    <td>Nomor Surat</td>
	                    <td>:</td>
	                    <td>
	                       <input required id="reqNoSurat" name="reqNoSurat" class="easyui-validatebox" style="width:250px;" value="<?=$reqNoSurat?>" <?=$disabled?> />
	                    </td>
	                    <td>Dibuat Tanggal</td>
	                    <td>:</td>
	                    <td>
	                    	<label><?=$reqCreateDate?></label>
	                    </td>
	                </tr>
	                <tr>
	                    <td>Tanggal Surat</td>
	                    <td>:</td>
	                    <td>
	                    	<input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalSurat" id="reqTanggalSurat" maxlength="10" onKeyDown="return format_date(event,'reqTanggalSurat');" value="<?=$reqTanggalSurat?>" <?=$disabled?> />
	                    </td>
	                    <td>Update Terakhir</td>
	                    <td>:</td>
	                    <td>
	                    	<label><?=$reqLastUpdate?></label>
	                    </td>
	                </tr>
	                <tr>
	                    <td>Keterangan</td>
	                    <td>:</td>
	                    <td colspan="4">
	                       <textarea name="reqKeterangan" class="easyui-validatebox" style="height:70px; width:80%;" <?=$disabled?> ><?=$reqKeterangan?></textarea>
	                    </td>
	                </tr>

	                <tr>
	                	<td colspan="7">
	                		<?
	                    	if($simpan == "")
	                    	{
	                    	?>
	                		<span>
                                <a href="javascript:void(0)" class="round waves-effect waves-light red white-text" title="Tambah Pegawai" onClick="setaddpegawai()">
                                	<img src="images/icon-tambah.png" /> Tambah Pegawai
                                </a>
                            </span>
                            <?
                        	}
                            ?>
	                		<table class="bordered highlight md-text table_list tabel-responsif responsive-table" id="tablepegawai">
				                <thead class="teal white-text"> 
				                    <tr>
				                        <th style="width: 100px">NIP</th>
				                        <th style="width: 250px">Nama</th>
				                        <th>Jabatan</th>
				                        <?
				                    	if($simpan == "")
				                    	{
				                    	?>
				                        <th style="text-align:center" width="70">Aksi</th>
				                        <?
				                    	}
				                        ?>
				                    </tr>
				                </thead>
				                <tbody>
				                	<?
									for($index=0; $index < $jumlahpegawai; $index++)
				                	{
				                		$infopegawaiid= $arrdata[$index]["PEGAWAI_ID"];
				                		$infopegawaijabatanriwayatid= $arrdata[$index]["JABATAN_RIWAYAT_ID"];
				                		$infopegawaipangkatriwayatid= $arrdata[$index]["PANGKAT_RIWAYAT_ID"];
				                		$infopegawainip= $arrdata[$index]["NIP_BARU"];
				                		$infopegawainama= $arrdata[$index]["NAMA_LENGKAP"];
				                		$infopegawaijabatan= $arrdata[$index]["JABATAN_RIWAYAT_NAMA"];
				                	?>
				                	<tr>
				                		<td><?=$infopegawainip?></td>
				                		<td><?=$infopegawainama?></td>
				                		<td><?=$infopegawaijabatan?></td>
				                		<?
				                    	if($simpan == "")
				                    	{
				                    	?>
				                		<td style="text-align:center">
				                			<input type="hidden" name="reqinfopegawaiid" value="<?=$infopegawaiid?>" />
				                			<input type="hidden" name="reqPegawaiId[]" value="<?=$infopegawaiid?>" />
				                			<input type="hidden" name="reqPegawaiJabatanId[]" value="<?=$infopegawaijabatanriwayatid?>" />
				                			<input type="hidden" name="reqPegawaiPangkatId[]" value="<?=$infopegawaipangkatriwayatid?>" />
				                			<a href="javascript:void(0)" class="round waves-effect waves-light red white-text" title="Hapus" onClick="$(this).parent().parent().remove();"><img src="images/icon-hapus.png" /></a>
				                		</td>
				                		<?
				                		}
				                		?>
				                	</tr>
				                	<?
				                	}
				                	?>
				                </tbody>
				            </table>
	                	</td>
	                </tr>

	                <tr>
	                    <td>
	                    	Upload Pendukung
	                    </td>
	                    <td>:</td>
	                    <td colspan="4">
	                    	<div class="kotak-dokumen">
                                <div class="kontak">
                                    <div class="inner-lampiran">
                                    	<?
				                    	if($simpan == "")
				                    	{
				                    	?>
                                        <input id ="reqFile" name="reqLinkFile[]" type="file" maxlength="6" class="multi maxsize-10240" value="" />
                                        <?
                                    	}
                                        ?>
                                        <?
										for($index_data=0; $index_data < $jumlahpermohonanfile; $index_data++)
					                	{
					                	?>
                                            <div class="MultiFile-label">
                                            	<?
						                    	if($simpan == "")
						                    	{
						                    	?>
                                                <a class="MultiFile-remove">
                                                	<img src="images/icon-hapus.png" onclick="infolampiran('min'); $(this).parent().parent().remove(); ajaxdeletefile('main/klarifikasi1_json/hapusfile?reqId=<?=$arrPermohonanFile[$index_data]["PERMOHONAN_FILE_ID"]?>');" />
                                                </a>
                                                <?
                                            	}
                                                ?>
                                                <a href="javascript:void(0)" class="round waves-effect waves-light red white-text" title="Download" onClick="opendownload('<?=$arrPermohonanFile[$index_data]["LINK_FILE"]?>')">
                                                	<?=$arrPermohonanFile[$index_data]["KETERANGAN"]?>
                                                	<img src="images/icon-download.png" />
                                                </a>
                                            </div>
					                	<?
					                	}
					                	?>
                                        <div class="small">
                                        	File harus berformat (pdf/jpg/jpeg)
                                        	<br/>PDF ukuran maksimal 1mb
                                        	<br/>JPG maksimal 750kb
                                        </div>
                                    </div>
                                </div>
                            </div>
	                    </td>
	                </tr>
					<tr>
	                    <td>
	                    	Ubah Semua Status
	                    </td>
	                    <td>:</td>
	                    <td colspan="4">
	                    	<select name="reqUbahStatus" id="reqUbahStatus" <?=$disabled?> >
                            	<option value="">Pilih</option>
                                <option value="Y" <? if($reqUbahStatus == 'Y') { ?> selected="selected" <? } ?>>Ya</option>
                                <option value="T" <? if($reqUbahStatus == 'T') { ?> selected="selected" <? } ?>>Tidak</option>
                            </select>
	                    </td>
	                </tr>
					<tr>
                        <td>Status</td>
                        <td>:</td>
                        <td colspan="4">
                            <select name="reqStatus" id="reqStatus" <?=$disabled?> >
                            	<option value="">Pilih</option>
                                <option value="Y" <? if($reqStatus == 'Y') { ?> selected="selected" <? } ?>>Disetujui</option>
                                <option value="T" <? if($reqStatus == 'T') { ?> selected="selected" <? } ?>>Ditolak</option>
                            </select>
                        </td>
                    </tr>
                    <tr id="reqAlasanInfo" style="display:none">
                    	<td>Alasan Ditolak</td>
                        <td>:</td>
                    	<td colspan="4">
                    		<textarea name="reqAlasanTolak" id="reqAlasanTolak" class="easyui-validatebox" style="height:70px; width:80%;" <?=$disabled?> ><?=$reqAlasanTolak?></textarea>
                    	</td>
                    </tr>
	                
				</thead>
			</table>

			<input type="hidden" name="reqId" value="<?=$reqId?>" />
			<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
			<input type="button" onclick="document.location.href='app/loadUrl/main/<?=$jenis?>'" class="btn btn-primary" value="Kembali" />

			<?
			if($simpan == "1" && $this->LOGIN_USER == $reqLastUser)
			{
			?>
			<input type="button" onclick="setsimpanulang()" class="btn btn-warning" value="Simpan Ulang" />
			<?
			}
			?>
			
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