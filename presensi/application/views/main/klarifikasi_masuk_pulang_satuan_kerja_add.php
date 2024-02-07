<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('main/PermohonanFile');
$this->load->model('main/Klarifikasi');
$this->load->model('siap/SatuanKerja');

$jenis= "klarifikasi_masuk_pulang_satuan_kerja";

$arrjenisfm= jenisfm();

$reqId= $this->input->get("reqId");

$set= new Klarifikasi();
if($reqId == "")
{
	$reqMode = "insert";
	// $reqTanggalAwal = date("d-m-Y");
	// $reqTanggalAwal= "01-02-2021";
	// $reqPegawaiNip= "197711182005011008";
	$jumlahdata= 0;
	$reqSatuanKerjaStatus= "1";
}
else
{
	$reqMode = "update";
	$set->selectByParamsKlarifikasi(array("A.KLARIFIKASI_ID"=>$reqId));
	// echo $set->query;exit();
	$set->firstRow();

	$reqTanggalAwal= dateToPageCheck($set->getField("TANGGAL_MULAI"));
	$reqTanggalAwalDetik= $set->getField("TANGGAL_MULAI_DETIK");
	$reqTanggalAkhir= dateToPageCheck($set->getField("TANGGAL_SELESAI"));
	$reqTanggalAkhirDetik= $set->getField("TANGGAL_SELESAI_DETIK");
	$reqTanggalSurat= dateToPageCheck($set->getField("TANGGAL_SURAT"));
	$reqNoSurat= $set->getField("NOMOR_SURAT");
	$reqPegawaiId= $set->getField("PEGAWAI_ID");
	$reqPegawaiJabatanId= $set->getField("JABATAN_RIWAYAT_ID");
	$reqPegawaiPangkatId= $set->getField("PANGKAT_RIWAYAT_ID");
	$reqPegawaiNip= $set->getField("NIP_BARU");
	$reqPegawaiNama= $set->getField("NAMA_LENGKAP");
	$reqPegawaiJabatan= $set->getField("JABATAN_RIWAYAT_NAMA");
	$reqLastUser= $set->getField("LAST_USER");
	$reqCreateDate= $set->getField("LAST_CREATE_DATE");
	$reqLastUpdate= $set->getField("LAST_UPDATE");
	$reqLastDate= $reqCreateDate;
	if(!empty($reqLastUpdate))
	{
		$reqLastDate= $reqLastUpdate;
	}
	
	$reqJenisKlarifikasi= $set->getField("KODE");
	$reqKeterangan= $set->getField("KETERANGAN");
	$reqUbahStatus= $set->getField("UBAH_STATUS");
	$reqStatus= $set->getField("STATUS");
	$reqAlasanTolak= $set->getField("ALASAN_TOLAK");

	$reqSatuanKerjaId= $set->getField("SATUAN_KERJA_ID");
	$reqSatuanKerjaStatus= $set->getField("SATUAN_KERJA_STATUS");
	$reqJenisFm= $set->getField("JENIS_FM");

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

	$statement= " AND PEGAWAI_ID = '".$reqPegawaiId."' AND TO_DATE(TO_CHAR(A.JAM, 'YYYY-MM-DD'), 'YYYY/MM/DD') = TO_DATE('".dateToPageCheck($reqTanggalAwal)."','YYYY/MM/DD') AND A.VALIDASI = 1";
	$set= new Klarifikasi();
	$set->selectByParamsAbsensi(array(), -1, -1, $statement);
	// echo $set->query;exit;
	$arrdata= []; $i=0;
	while ($set->nextRow()) 
	{
		$arrdata[$i]["TANGGAL"]= $set->getField("TANGGAL");
		$arrdata[$i]["WAKTU"]= $set->getField("WAKTU");
		$arrdata[$i]["TIPE_ABSEN"]= $set->getField("TIPE_ABSEN");
		$arrdata[$i]["TIPE_ABSEN_INFO"]= $set->getField("TIPE_ABSEN_INFO");
		$i++;
	}
	$jumlahdata= $i;
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
		url:'main/klarifikasi_json/masukpulangsatuankerja_add',
		onSubmit:function(){
			
			reqPegawaiId= $("#reqPegawaiId").val();

			if(reqPegawaiId == "")
			{
				$.messager.alert('Info', "Mohon Pilih Pegawai", 'info');
				return false;
			}

			reqStatus= $("#reqStatus").val();
			reqAlasanTolak= $("#reqAlasanTolak").val();

			if( (reqAlasanTolak == "" || reqAlasanTolak == null) && reqStatus == "T" )
			{
				$.messager.alert('Info', "isikan terlebih dahulu alasan.", 'info');
				return false;
			}
			
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

	setalasantolak();
	$("#reqStatus").change(function() {
		setalasantolak();
	});

	$('#reqTanggalAkhirDetik, #reqTanggalAwalDetik').datetimepicker({datepicker:false, format: 'H:i:s', defaultTime:'00:00', step:1});

	// var dataformat= { format: 'd-m-Y H:i:s', scrollInput: false, autoclose: true, timepicker: true, step:1};
	var dataformat= { format: 'd-m-Y', scrollInput: false, autoclose: true, timepicker: false, step:1};
	const $in = $('#reqTanggalAwal'), $out = $('#reqTanggalAkhir');
	$in.datetimepicker(dataformat); $out.datetimepicker(dataformat);
	$('#reqTanggalSurat').datetimepicker(dataformat);

	$in.on('change', function() {
	  var minDate = $in.val();
	  var startingVal = '';
	  if (minDate) 
	  {
	    // console.log(minDate);
	    // infodate= minDate;
	    // infodate= infodate.split(' ');
	    // minDate = infodate[0].split('-');

	    // // kalau detik kosong
	    // if(infodate[1] !== "")
	    // {
	    // 	infosecond = infodate[1].split(':');
	    // 	minDate = new Date(minDate[2], minDate[1] - 1, minDate[0], infosecond[0], infosecond[1], 59, 0);
	    // }
	    // else
	    // {
	    // 	minDate = new Date(minDate[2], minDate[1] - 1, minDate[0], 0, 0);
	    // }

	    minDate = minDate.split('-');
	    minDate = new Date(minDate[2], minDate[1] - 1, minDate[0], 0, 0);
	    // console.log(minDate);
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
	      // return dd + "-" + mm + "-" + yyyy + " " + "59" + ":" + "59" + ":" + "59";
	    })(minDate);

	  } 
	  else 
	  {
	    minDate = new Date(0, 0, 0, 0, 0);
	  }
	  // console.log(minDate);
	  dataformat.minDate = minDate;
	  //$out.datetimepicker('remove');
	  $out.datetimepicker(dataformat);
	  $out.val(startingVal);
	});

});

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
		<div class="judul-monitoring"><span>Form Klarifikasi Masuk Pulang Force Majeur Unit Kerja</span></div>

		<form id="ff" method="post" novalidate enctype="multipart/form-data">
			<table class="table">
				<thead>
					<tr>
	                    <td>Mulai Berlaku</td>
	                    <td>:</td>
	                    <td colspan="6">
	                    	<input style="width: 80px" required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalAwal" id="reqTanggalAwal" maxlength="10" onKeyDown="return format_date(event,'reqTanggalAwal');" value="<?=$reqTanggalAwal?>" autocomplete="off" <?=$disabled?> />
	                    	<input style="width: 60px" required class="easyui-validatebox" type="text" name="reqTanggalAwalDetik" id="reqTanggalAwalDetik" maxlength="8" onKeyDown="return format_detik(event,'reqTanggalAwalDetik');" value="<?=$reqTanggalAwalDetik?>" autocomplete="off" <?=$disabled?> />
	                    </td>
	                </tr>
	                <tr>
	                    <td>Akhir Berlaku</td>
	                    <td>:</td>
	                    <td colspan="6">
	                    	<input style="width: 80px" required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalAkhir" id="reqTanggalAkhir" maxlength="10" onKeyDown="return format_date(event,'reqTanggalAkhir');" value="<?=$reqTanggalAkhir?>" autocomplete="off" <?=$disabled?> />
	                    	<input style="width: 60px" required class="easyui-validatebox" type="text" name="reqTanggalAkhirDetik" id="reqTanggalAkhirDetik" maxlength="8" onKeyDown="return format_detik(event,'reqTanggalAkhirDetik');" value="<?=$reqTanggalAkhirDetik?>" autocomplete="off" <?=$disabled?> />
	                    </td>
	                </tr>
	                <tr>
	                	<td>Nomor Surat</td>
	                    <td>:</td>
	                    <td colspan="6">
	                       <input required id="reqNoSurat" name="reqNoSurat" class="easyui-validatebox" style="width:250px;" value="<?=$reqNoSurat?>" <?=$disabled?> />
	                    </td>
	                </tr>
	                <tr>
	                	<td>Tanggal Surat</td>
	                	<td>:</td>
	                    <td colspan="6">
	                    	<input style="width: 80px" required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalSurat" id="reqTanggalSurat" maxlength="10" onKeyDown="return format_date(event,'reqTanggalSurat');" value="<?=$reqTanggalSurat?>" autocomplete="off" <?=$disabled?> />
	                    </td>
	                </tr>
	                <tr>
						<td>Jenis FM</td>
						<td>:</td>
						<td colspan="6">
							<select id="reqJenisFm" name="reqJenisFm" <?=$disabled?> >
								<?
								for($i=0; $i < count($arrjenisfm); $i++)
								{
									$infoid= $arrjenisfm[$i]["id"];
									$infotext= $arrjenisfm[$i]["nama"];
								?>
								<option value="<?=$infoid?>" <? if($reqJenisFm == $infoid) echo "selected";?>><?=$infotext?></option>
								<?
								}
								?>
							</select>
						</td>
					</tr>
					<tr>
	                    <td>Keterangan</td>
	                    <td>:</td>
	                    <td colspan="6">
	                       <textarea name="reqKeterangan" class="easyui-validatebox" style="height:70px; width:80%;" <?=$disabled?> ><?=$reqKeterangan?></textarea>
	                    </td>
	                </tr>
	                <tr>
	                	<td>Berlaku Untuk</td>
                        <td>:</td>
                        <td colspan="6">
                        	<input type="text" required id="reqSatuanKerjaId" class="easyui-combotree" name="reqSatuanKerjaId" data-options="width:'500'
                        	, panelHeight:'244'
                            , valueField:'id'
                            , textField:'text'
                            //, data:infodatasatker
                            , url:'siap/satuan_kerja_json/combotree/?reqStatusMesinPosisi=1'
                            , prompt:'Tentukan Pemesan...'
                            , editable:true
                            , keyHandler : {
								query : function(q) {
									var t = $(this).combotree('tree');
									t.tree('doFilter', q);
								}
							}
                            " value="<?=$reqSatuanKerjaId?>"
                            <?=$disabled?>
                            />
                        </td>
	                </tr>
	                <tr>
                        <td>Unit Kerja</td>
                        <td>:</td>
                        <td colspan="6">
                            <select name="reqSatuanKerjaStatus" id="reqSatuanKerjaStatus" <?=$disabled?> >
                                <option value="1" <? if($reqSatuanKerjaStatus == '1') { ?> selected="selected" <? } ?>>Hanya Induk</option>
                                <option value="" <? if($reqSatuanKerjaStatus == '') { ?> selected="selected" <? } ?>>Semua</option>
                            </select>
                        </td>
                    </tr>
	                <tr>
                        <td>Status Ditiadakan</td>
                        <td>:</td>
                        <td colspan="6">
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
                    	<td colspan="6">
                    		<textarea name="reqAlasanTolak" id="reqAlasanTolak" class="easyui-validatebox" style="height:70px; width:80%;"><?=$reqAlasanTolak?></textarea>
                    	</td>
                    </tr>
                    <tr>
	                    <td>Upload Pendukung</td>
	                    <td>:</td>
	                    <td colspan="6">
	                    	<div class="kotak-dokumen">
                                <div class="kontak">
                                    <div class="inner-lampiran">
                                        <input id ="reqFile" name="reqLinkFile[]" type="file" maxlength="6" class="multi maxsize-10240" value="" />
                                        <?
										for($index_data=0; $index_data < $jumlahpermohonanfile; $index_data++)
					                	{
					                	?>
                                            <div class="MultiFile-label">
                                                <a class="MultiFile-remove">
                                                	<img src="images/icon-hapus.png" onclick="infolampiran('min'); $(this).parent().parent().remove(); ajaxdeletefile('main/klarifikasi_json/hapusfile?reqId=<?=$arrPermohonanFile[$index_data]["PERMOHONAN_FILE_ID"]?>');" />
                                                </a>
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
						<td>User Terakhir</td>
	                    <td>:</td>
	                    <td>
	                    	<label><?=$reqLastUser?></label>
	                    </td>
						<td>Dibuat /Update Terakhir</td>
	                    <td>:</td>
	                    <td>
	                       	<label><?=$reqLastDate?></label>
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