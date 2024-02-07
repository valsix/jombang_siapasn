<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('main/PermohonanFile');
$this->load->model('main/Klarifikasi');

$jenis= "klarifikasi_lupa";

$statement= " AND A.KODE IN ('HTPAS','HTPM','HTPP','HTPASK','HTPM/P','HTPP/ASK','HTPM/ASK/P')";
$klarifikasi= new Klarifikasi();
$klarifikasi->selectByParamsJenisKlarifikasi(array(),-1,-1,$statement);
// echo $klarifikasi->query;exit();

$reqId= $this->input->get("reqId");

$set= new Klarifikasi();
if($reqId == "")
{
	$reqMode = "insert";
	// $reqTanggalAwal = date("d-m-Y");
}
else
{
	$reqMode = "update";
	$set->selectByParamsKlarifikasi(array("A.KLARIFIKASI_ID"=>$reqId));
	// echo $set->query;exit();
	$set->firstRow();

	$reqTanggalAwal= dateToPageCheck($set->getField("TANGGAL_MULAI"));
	$reqPegawaiId= $set->getField("PEGAWAI_ID");
	$reqPegawaiJabatanId= $set->getField("JABATAN_RIWAYAT_ID");
	$reqPegawaiPangkatId= $set->getField("PANGKAT_RIWAYAT_ID");
	$reqPegawaiNip= $set->getField("NIP_BARU");
	$reqPegawaiNama= $set->getField("NAMA_LENGKAP");
	$reqPegawaiJabatan= $set->getField("JABATAN_RIWAYAT_NAMA");
	$reqLastUser= $set->getField("LAST_USER");
	$reqCreateDate= $set->getField("LAST_CREATE_DATE");
	$reqLastUpdate= $set->getField("LAST_UPDATE");
	$reqJenisKlarifikasi= $set->getField("KODE");
	$reqKeterangan= $set->getField("KETERANGAN");
	$reqUbahStatus= $set->getField("UBAH_STATUS");
	$reqStatus= $set->getField("STATUS");
	$reqAlasanTolak= $set->getField("ALASAN_TOLAK");

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
		url:'main/klarifikasi_json/lupa_add',
		onSubmit:function(){
			
			reqPegawaiId= $("#reqPegawaiId").val();

			if(reqPegawaiId == "")
			{
				$.messager.alert('Info', "Mohon Pilih Pegawai", 'info');
				return false;
			}

			// reqUbahStatus= $("#reqUbahStatus").val();
			// if(reqUbahStatus == "")
			// {
			// 	$.messager.alert('Info', "Mohon Pilih Ubah Status ASK", 'info');
			// 	return false;
			// }

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

	var dataformat= { format: 'd-m-Y', scrollInput: false, autoclose: true, timepicker: false};
	const $in = $('#reqTanggalAwal');
	$in.datetimepicker(dataformat);

});

$(function() {
	$('input[id^="reqPegawaiNip"]').each(function() {
	    $(this).autocomplete({
	        source:function(request, response) {
	          	$(".preloader-wrapper").show();
	          	var id= this.element.attr('id');
	          	var replaceAnakId= replaceAnak= urlAjax= "";
	          	reqTanggal= $('#reqTanggalAwal').val();
	          	lengthdata= reqTanggal.length;
	          	// console.log(lengthdata);
	          	if(lengthdata == 10)
	          	{
		          	urlAjax= "main/klarifikasi_json/cari_pegawai?reqTanggal="+reqTanggal;

		          	$.ajax({
		            	url: urlAjax,
		            	type: "GET",
		            	dataType: "json",
		            	data: { term: request.term },
		            	success: function(responseData) {
		              		$(".preloader-wrapper").hide();

			              	// console.log(responseData);
			              	if(responseData == null)
			              	{
			                	response(null);
			              	}
			              	else
			              	{	
			                	var array = responseData.map(function(element) {
			                  	return {desc: element['desc'], id: element['id'], label: element['label'], namapegawai: element['namapegawai'], namajabatan: element['namajabatan'], jabatanid: element['jabatanid'], pangkatid: element['pangkatid']};
			                	});
			               	 	response(array);
			              	}
			            }
		          	})
		        }
		        else
		        {
		        	$("#"+id).val("");
		        }
	        },
	        // focus: function (event, ui) 
	        select: function (event, ui) 
	        { 
	          	var id= $(this).attr('id');
	          	var indexId= "reqPegawaiId";
	          	var idrow= namapegawai= namajabatan= pangkatid= jabatanid= "";
	          	idrow= ui.item.id;
	          	namapegawai= ui.item.namapegawai;
	          	namajabatan= ui.item.namajabatan;
	          	jabatanid= ui.item.jabatanid;
	          	pangkatid= ui.item.pangkatid;

	          	if (namajabatan==""||namajabatan==null) 
	          	{
	          		namajabatan= " - ";
	          	}

	          	if (id.indexOf('reqPegawaiNip') !== -1)
	          	{
	            	$("#reqPegawaiId").val(idrow);
	            	$("#reqPegawaiJabatanId").val(jabatanid);
	            	$("#reqPegawaiPangkatId").val(pangkatid);
	            	$("#reqPegawaiJabatan").text(namajabatan);
	            	$("#reqPegawaiNama").text(namapegawai);
	          	}
	        },
	        autoFocus: true
	   	})
		.autocomplete( "instance" )._renderItem = function( ul, item ) {
			return $( "<li>" )
			.append( "<a>" + item.desc  + "</a>" )
			.appendTo( ul );
		}
		;
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
		<div class="judul-monitoring"><span>Form Klarifikasi Lupa</span></div>

		<form id="ff" method="post" novalidate enctype="multipart/form-data">
			<table class="table">
				<thead>
					<tr>
	                    <td>Tanggal Masuk</td>
	                    <td>:</td>
	                    <td colspan="4">
	                    	<input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalAwal" id="reqTanggalAwal" maxlength="10" onKeyDown="return format_date(event,'reqTanggalAwal');" value="<?=$reqTanggalAwal?>" autocomplete="off" <?=$disabled?> />
	                    </td>
	                </tr>
                	<tr>
						<td>NIP</td>
						<td>:</td>
						<td>
							<input type="hidden" id="reqPegawaiId" name="reqPegawaiId" value="<?=$reqPegawaiId?>" />
							<input type="hidden" id="reqPegawaiJabatanId" name="reqPegawaiJabatanId" value="<?=$reqPegawaiJabatanId?>" />
							<input type="hidden" id="reqPegawaiPangkatId" name="reqPegawaiPangkatId" value="<?=$reqPegawaiPangkatId?>" />
							<input type="text" id="reqPegawaiNip" class="easyui-validatebox" style="width:50%;" value="<?=$reqPegawaiNip?>" <?=$disabled?> />
						</td>

						<td>User Terakhir</td>
	                    <td style="width: 1%">:</td>
	                    <td style="width: 30%">
	                    	<label><?=$reqLastUser?></label>
	                    </td>
					</tr>
                	<tr>
						<td>Nama Pegawai</td>
						<td>:</td>
						<td>
							<label id="reqPegawaiNama"><?=$reqPegawaiNama?></label>
						</td>

						<td>Dibuat Tanggal</td>
	                    <td>:</td>
	                    <td>
	                       	<label><?=$reqCreateDate?></label>
	                    </td>
					</tr>
					<tr>
						<td>Jabatan Pegawai</td>
						<td>:</td>
						<td>
							<label id="reqPegawaiJabatan"><?=$reqPegawaiJabatan?></label>
						</td>

						<td>Update Terakhir</td>
	                    <td>:</td>
	                    <td>
	                       	<label><?=$reqLastUpdate?></label>
	                    </td>
					</tr>
					<tr>
						<td>Jenis Klarifikasi Lupa</td>
						<td>:</td>
						<td colspan="4">
							<select id="reqJenisKlarifikasi" name="reqJenisKlarifikasi" <?=$disabled?> >
								<option value=''>Pilih</option>
								<?
								while($klarifikasi->nextRow()) 
								{
									?>
									<option value="<?=$klarifikasi->getField("KODE")?>" <? if($reqJenisKlarifikasi == $klarifikasi->getField("KODE")) { ?> selected="selected" <? } ?> >
										<?=$klarifikasi->getField("NAMA")?>	(<?=$klarifikasi->getField("KODE")?>)	
									</option>
									<?
								}
								?>
							</select>
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
	                    <td>
	                    	Upload Pendukung
	                    </td>
	                    <td>:</td>
	                    <td colspan="4">
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

					<!-- <tr>
	                    <td>
	                    	Ubah Status ASK
	                    </td>
	                    <td>:</td>
	                    <td colspan="4">
	                    	<select name="reqUbahStatus" id="reqUbahStatus">
                            	<option value="">Pilih</option>
                                <option value="Y" <? if($reqUbahStatus == 'Y') { ?> selected="selected" <? } ?>>Ya</option>
                                <option value="T" <? if($reqUbahStatus == 'T') { ?> selected="selected" <? } ?>>Tidak</option>
                            </select>
	                    </td>
	                </tr> -->

					<tr>
                        <td>Status Klarifikasi</td>
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
                    		<textarea name="reqAlasanTolak" id="reqAlasanTolak" class="easyui-validatebox" style="height:70px; width:80%;"><?=$reqAlasanTolak?></textarea>
                    	</td>
                    </tr>
              
                    
					<? //$approval->echoInputField();?>
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