<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('main/PermohonanFile');
$this->load->model('main/Klarifikasi');

$jenis= "klarifikasi_ganti_status";

$reqId= $this->input->get("reqId");

$set= new Klarifikasi();
if($reqId == "")
{
	$reqMode = "insert";
	// $reqTanggalAwal = date("d-m-Y");
	// $reqTanggalAwal= "01-02-2021";
	// $reqPegawaiNip= "197711182005011008";
	$jumlahdata= 0;
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

	$reqJam= $set->getField("TANGGAL_MULAI")." ".datetimeToPage($set->getField("JAM"), "");
	$reqTipeAbsenAwal= $set->getField("TIPE_ABSEN_AWAL");
	$reqTipeAbsenRevisi= $set->getField("TIPE_ABSEN_REVISI");
	$reqTipeSelectedAbsen= $set->getField("TANGGAL_MULAI")."#".datetimeToPage($set->getField("JAM"), "")."#".$set->getField("TIPE_ABSEN_AWAL");
	$reqTipeSelectedAbsenInfo= datetimeToPage($set->getField("JAM"), "")." - ".$set->getField("TIPE_ABSEN_AWAL_INFO");
	// echo $reqTipeSelectedAbsenInfo;exit;

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

	$arrtipeabsensi= tipeabsensi();
	$arrdatarevisi= []; $i=0;
	$arrdatarevisi[$i]["id"]= "";
	$arrdatarevisi[$i]["nama"]= "";
	$i++;
	for($x=0; $x < count($arrtipeabsensi); $x++)
	{
		$infoid= $arrtipeabsensi[$x]["id"];

		if($reqTipeAbsenAwal == $infoid){}
		else
		{
			$arrdatarevisi[$i]["id"]= $arrtipeabsensi[$x]["id"];
			$arrdatarevisi[$i]["nama"]= $arrtipeabsensi[$x]["nama"];
			$i++;
		}
	}
	$jumlahdatarevisi= $i;
	// print_r($arrdatarevisi);exit;

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
		url:'main/klarifikasi_json/gantistatus_add',
		onSubmit:function(){
			
			reqPegawaiId= $("#reqPegawaiId").val();

			if(reqPegawaiId == "")
			{
				$.messager.alert('Info', "Mohon Pilih Pegawai", 'info');
				return false;
			}

			reqTipeAbsenAwal= $("#reqTipeAbsenAwal").val();
			if(reqTipeAbsenAwal == "")
			{
				$.messager.alert('Info', "Mohon Pilih Waktu", 'info');
				return false;
			}

			reqTipeAbsenRevisi= $("#reqTipeAbsenRevisi").val();
			if(reqTipeAbsenRevisi == "")
			{
				$.messager.alert('Info', "Mohon Pilih Revisi", 'info');
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
				/*reqStatus= $("#reqStatus").val();
				$.ajax({
			        url: "main/klarifikasi_json/gantistatustriger?reqId="+rowid,
			        method: "GET",
			        success: function (data) {
			        	console.log(data);return false;
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
			        	// console.log(data);return false;
			        },
			        complete: function () {
			        }
			    });*/
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

	$('select[id^="reqTipeSelectedAbsen"]').change(function(e) {
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

	infoidselected= "reqTipeSelectedAbsen";
	$("#"+infoidselected+" :selected").remove();
	$("#"+infoidselected+" option").remove();
	if(reqPegawaiId !== "" && lengthdata == 10)
	{
		var s_url= "main/klarifikasi_json/gantistatus_option?reqPegawaiId="+reqPegawaiId+"&reqTanggal="+reqTanggal;
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
					if(data[i].TANGGAL == "")
					{
						infoid= infotext= "";
					}
					else
					{
						infoid= data[i].TANGGAL+"#"+data[i].WAKTU+"#"+data[i].TIPE_ABSEN;
						infotext= data[i].WAKTU + " - " + data[i].TIPE_ABSEN_INFO + "("+data[i].TIPE_ABSEN+")";
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
		$("#reqJam, #reqTipeAbsenAwal").val("");
	}
}

function settipeabsen()
{
	reqTipeSelectedAbsen= $("#reqTipeSelectedAbsen").val();
	// console.log(reqTipeSelectedAbsen);
	reqTipeSelectedAbsen= reqTipeSelectedAbsen.split('#');

	reqJam= reqTipeSelectedAbsen[0]+" "+reqTipeSelectedAbsen[1];
	reqTipeAbsenAwal= reqTipeSelectedAbsen[2];
	$("#reqJam").val(reqJam);
	$("#reqTipeAbsenAwal").val(reqTipeAbsenAwal);

	infodetilidselected= "reqTipeAbsenRevisi";
	$("#"+infodetilidselected+" :selected").remove();
	$("#"+infodetilidselected+" option").remove();
	if(reqTipeAbsenAwal !== "")
	{
		var s_url= "main/klarifikasi_json/gantistatus_optionrevisi?reqJenis="+reqTipeAbsenAwal;
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
					if(data[i].id == "")
					{
						infoid= infotext= "";
					}
					else
					{
						infoid= data[i].id;
						infotext= data[i].nama;
					}
					$("<option value='" + infoid + "' "+selected+" >" + infotext + "</option>").appendTo("#"+infodetilidselected);
				}
			}
		});
	}

}
</script>
</head>

<body class="bg-permohonan">
	<div class="area-permohonan">
		<div class="judul-monitoring"><span>Form Klarifikasi Ganti Status Log</span></div>

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
							<input type="text" id="reqPegawaiNip" class="easyui-validatebox" style="width:100%;" value="<?=$reqPegawaiNip?>" <?=$disabled?> />
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
						<td>Waktu</td>
						<td>:</td>
						<td colspan="4">
							<?
							if($simpan == "1")
							{
								echo $reqTipeSelectedAbsenInfo;
							}
							else
							{
							?>
							<input type="hidden" name="reqJam" id="reqJam" value="<?=$reqJam?>" />
							<input type="hidden" name="reqTipeAbsenAwal" id="reqTipeAbsenAwal" value="<?=$reqTipeAbsenAwal?>" />
							<select id="reqTipeSelectedAbsen" name="reqTipeSelectedAbsen" <?=$disabled?> >
								<?
								for($i=0; $i < $jumlahdata; $i++)
								{
									$infoid= $arrdata[$i]["TANGGAL"]."#".$arrdata[$i]["WAKTU"]."#".$arrdata[$i]["TIPE_ABSEN"];
									$infotext= $arrdata[$i]["WAKTU"]." - ".$arrdata[$i]["TIPE_ABSEN_INFO"]."(".$arrdata[$i]["TIPE_ABSEN"].")";
								?>
								<option value="<?=$infoid?>" <? if($reqTipeSelectedAbsen == $infoid) echo "selected";?>><?=$infotext?></option>
								<?
								}
								?>
							</select>
							<?
							}
							?>
						</td>
					</tr>
					<tr>
						<td>Revisi</td>
						<td>:</td>
						<td colspan="4">
							<select id="reqTipeAbsenRevisi" name="reqTipeAbsenRevisi" <?=$disabled?> >
								<?
								for($i=0; $i < $jumlahdatarevisi; $i++)
								{
									$infoid= $arrdatarevisi[$i]["id"];
									$infotext= $arrdatarevisi[$i]["nama"];
								?>
								<option value="<?=$infoid?>" <? if($reqTipeAbsenRevisi == $infoid) echo "selected";?>><?=$infotext?></option>
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
</body>
</html>