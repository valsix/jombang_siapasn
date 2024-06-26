<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('main/PermohonanFile');
$this->load->model('main/Klarifikasi');

$jenis= "klarifikasi_pakta_integritas";

$reqId= $this->input->get("reqId");

$set= new Klarifikasi();
if($reqId == "")
{
	$reqMode = "insert";
	// $reqPegawaiNip= "198305022011011001";
	// $reqTanggalAwal = "02-02-2021";
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

	$arrdata= []; $i=0;
	$statement= " AND A.PEGAWAI_ID = '".$reqPegawaiId."'";
	$reqPeriode= getperiodetanggal($reqTanggalAwal);
	$set= new Klarifikasi();
	$set->selectByParamsAbsensiKoreksi($reqPeriode, $statement);
	$infocheck= $set->errorMsg;
	if(!empty($infocheck)){}
	else
	{
		$set->firstRow();
		// echo $set->query;exit;
		$infohari= (int)getYear($reqTanggalAwal);
		$infomasuk= $set->getField("MASUK_".$infohari);
		$infopulang= $set->getField("PULANG_".$infohari);
		$infoexmasuk= $set->getField("EX_MASUK_".$infohari);
		// echo $infomasuk."--".$infopulang."--".$infoexmasuk;exit;

		// kondisi data
		$statement= " AND 1=2";
		if($infomasuk == "MTSK" && $infopulang == "PTSK")
		{
			if(empty($infoexmasuk) || $infoexmasuk == "AASK")
				$statement= " AND A.KODE IN ('HMPI','HPPI','ASKD')";
			else
				$statement= " AND A.KODE IN ('HMPI','HPPI')";
		}
		elseif($infomasuk == "MTSK" || $infomasuk == "AM" || $infomasuk == "MT")
		{
			if(empty($infoexmasuk) || $infoexmasuk == "AASK")
			{
				if($infopulang == "AP")
					$statement= " AND A.KODE IN ('HMPI','HPPI','ASKD')";
				else
					$statement= " AND A.KODE IN ('HMPI','ASKD')";
			}
			elseif($infopulang == "AP")
				$statement= " AND A.KODE IN ('HMPI','HPPI')";
			else
				$statement= " AND A.KODE IN ('HMPI')";
		}
		elseif($infopulang == "PTSK" || $infopulang == "AP" || $infopulang == "PC")
		{
			if(empty($infoexmasuk) || $infoexmasuk == "AASK")
				$statement= " AND A.KODE IN ('HPPI','ASKD')";
			else
				$statement= " AND A.KODE IN ('HPPI')";
		}
		else
		{
			if($infoexmasuk == "ASKD")
			{
				if($infomasuk == "AM" && $infopulang == "AP")
					$statement= " AND A.KODE IN ('HMPI/HPPI')";
			}
			elseif($infomasuk == "AM" && $infopulang == "AP"){}
			else
				$statement= " AND A.KODE IN ('HMPI/ASKD/HPPI')";
		}

		$set= new Klarifikasi();
		$set->selectByParamsJenisKlarifikasi(array(),-1,-1,$statement);
		// echo $set->query;exit();

		$arrdata[$i]["KODE"]= "";
		$arrdata[$i]["NAMA"]= "Pilih";
		$i++;
		while ($set->nextRow()) 
		{
			$infokode= $set->getField("KODE");
			$arrdata[$i]["KODE"]= $infokode;
			$arrdata[$i]["NAMA"]= $set->getField("NAMA")." (".$arrdata[$i]["KODE"].")";
			$i++;
		}
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
		url:'main/klarifikasi_json/paktaintegritas_add',
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

			reqJenisKlarifikasi= $("#reqJenisKlarifikasi").val();
			if(reqJenisKlarifikasi == "")
			{
				$.messager.alert('Info', "Mohon Pilih Jenis Klarifikasi", 'info');
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

	var dataformat= { format: 'd-m-Y', scrollInput: false, autoclose: true, timepicker: false};
	const $in = $('#reqTanggalAwal');
	$in.datetimepicker(dataformat);

	$in.on('change', function() {
		$("#reqPegawaiId, #reqPegawaiNip, #reqPegawaiJabatanId, #reqPegawaiPangkatId").val("");
		$("#reqPegawaiNama, #reqPegawaiJabatan").text("");
		ambildatavalidabsensi();
	});

	$('select[id^="reqJenisSelectedKlarifikasi"]').change(function(e) {
		// var infoid= $(this).attr('id');
		// var infoval= $(this).val();
		// console.log(infoval);
		settipeabsen();
	});

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
	            	ambildatavalidabsensi();
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

function ambildatavalidabsensi()
{
	reqPegawaiId= $("#reqPegawaiId").val();
	reqTanggal= $("#reqTanggalAwal").val();
	lengthdata= reqTanggal.length;

	infoidselected= "reqJenisSelectedKlarifikasi";
	$("#"+infoidselected+" :selected").remove();
	$("#"+infoidselected+" option").remove();
	if(reqPegawaiId !== "" && lengthdata == 10)
	{
		var s_url= "main/klarifikasi_json/paktaintegritas_option?reqPegawaiId="+reqPegawaiId+"&reqTanggal="+reqTanggal;
		var request = $.get(s_url);
		request.done(function(dataJson)
		{
			var data= JSON.parse(dataJson);
			// console.log(data);
			if(data == ""){}
			else
			{
				selected= "";
				// console.log(data.length);
				for(i=0;i<data.length; i++)
				{
					if(data[i].NAMA == "")
					{
						infoid= infotext= "";
					}
					else
					{
						infoid= data[i].KODE;
						infotext= data[i].NAMA;
					}
					$("<option value='" + infoid + "' "+selected+" >" + infotext + "</option>").appendTo("#"+infoidselected);

					if(i == 0)
					{
						settipeabsen();
					}
				}
			}
		});
	}
	else
	{
		$("#reqJenisKlarifikasi").val("");
	}
}

function settipeabsen()
{
	reqJenisSelectedKlarifikasi= $("#reqJenisSelectedKlarifikasi").val();
	// console.log(reqJenisSelectedKlarifikasi);
	$("#reqJenisKlarifikasi").val(reqJenisSelectedKlarifikasi);
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
		<div class="judul-monitoring"><span>Form Klarifikasi Pakta Integritas</span></div>

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
						<td>Jenis Klarifikasi</td>
						<td>:</td>
						<td colspan="4">
							<?
	                    	if($simpan == "")
	                    	{
	                    	?>
							<input type="hidden" name="reqJenisKlarifikasi" id="reqJenisKlarifikasi" value="<?=$reqJenisKlarifikasi?>" />
							<select id="reqJenisSelectedKlarifikasi">
								<?
								for($i=0; $i < $jumlahdata; $i++)
								{
									$infoid= $arrdata[$i]["KODE"];
									$infotext= $arrdata[$i]["NAMA"];
								?>
								<option value="<?=$infoid?>" <? if($reqJenisKlarifikasi == $infoid) echo "selected";?>><?=$infotext?></option>
								<?
								}
								?>
							</select>
							<?
							}
							else
							{
							?>
							<label><?=$reqJenisKlarifikasi?></label>
							<?
							}
							?>
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
                                                	<img src="images/icon-hapus.png" onclick="infolampiran('min'); $(this).parent().parent().remove(); ajaxdeletefile('main/klarifikasi_json/hapusfile?reqId=<?=$arrPermohonanFile[$index_data]["PERMOHONAN_FILE_ID"]?>');" />
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

					<tr style="display: none;">
	                    <td>
	                    	Ubah Status ASK
	                    </td>
	                    <td>:</td>
	                    <td colspan="4">
	                    	<input type="hidden" name="reqUbahStatus" value="<?=$reqUbahStatus?>" />
	                    	<!-- <select name="reqUbahStatus" id="reqUbahStatus" <?=$disabled?> >
                            	<option value="">Pilih</option>
                                <option value="Y" <? if($reqUbahStatus == 'Y') { ?> selected="selected" <? } ?>>Ya</option>
                                <option value="T" <? if($reqUbahStatus == 'T') { ?> selected="selected" <? } ?>>Tidak</option>
                            </select> -->
	                    </td>
	                </tr>

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