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
			
			<?
			if(!empty($reqId))
			{
			?>
			reqAlasan= $("#reqAlasan").val();
			if(reqAlasan == "" || reqAlasan == null )
			{
				$.messager.alert('Info', "mohon pilih persetujuan.", 'info');
				return false;
			}
			else
				return $(this).form('validate');
			<?
			}
			else
			{
			?>
			reqPegawaiId= $("#reqPegawaiId").val();
			if(reqPegawaiId == "")
			{
				$.messager.alert('Info', "Mohon Pilih Pegawai", 'info');
				return false;
			}
			<?
			}
			?>
			
			// return false;
			
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

	<?
	if(!empty($reqId))
	{
	?>
		setAlasanTolak();
		$("#reqAlasan").change(function() {
			setAlasanTolak();
		});
	<?
	}
	?>
	
	setJenis();
	$("#reqJenis").change(function() {
		setJenis();
	});
	
	$('#reqTanggalAwal').datebox({
		onChange: function(date){
			$('#reqTanggalAkhir').datebox('setValue', '');		
		}
	});
	
	$('#reqTanggalAkhir').datebox({
		onChange: function(date){
			validasitanggal();
		}
	});

	$('input[id^="reqRadio"]').change(function(e) {
		var tempId= $(this).attr('id');
		var tempValId= $(this).val();
		tempId= tempId.split('reqRadio');
		tempId= tempId[1];

		if(tempId == "Masuk")
		{
			$("#reqStatusMasuk").val(tempValId);
			$("#reqStatusPulang").val("");
		}
		else if(tempId == "Pulang")
		{
			$("#reqStatusMasuk").val("");
			$("#reqStatusPulang").val(tempValId);
		}
	});

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
	var mulai = $('#reqTanggalAwal').datebox('getValue');	
	var selesai = $('#reqTanggalAkhir').datebox('getValue');		
	
	if(mulai == "")
	{
		$('#reqTanggalAkhir').datebox('setValue', '');	
		$.messager.alert('Info', "Isi tanggal mulai terlebih dahulu.", 'info');		
		return;
	}
	
	// console.log(mulai+";"+selesai);
	// var selisih = get_day_between(mulai, selesai);
		
	if(setvalidasitanggal(mulai, selesai))
	{
		$('#reqTanggalAkhir').datebox('setValue', '');
		$.messager.alert('Info', "Tanggal akhir lebih kecil.", 'info');		
		return;
	}
}

function setAlasanTolak()
{
	var reqAlasan= "";
	reqAlasan= $("#reqAlasan").val();
	//console.log(reqAlasan);
	document.getElementById("reqAlasanInfo").style.display="none";
	if(reqAlasan == "T")
	{
		document.getElementById("reqAlasanInfo").style.display="";	
		//$("#reqAlasanInfo").show();
	}
	else
	{
		$("#reqAlasanTolak").val("");
	}
}

function setJenis()
{
	var reqJenis= "";
	reqJenis= $("#reqJenis").val();
	//console.log(reqJenis);
	$("#reqTanggalIjinInfo, #reqJamDatangInfo, #reqKeperluanInfo, #reqTanggalAkhirInfo, #reqTanggalAwalInfo, #reqLampiran").hide();
	$("#reqTanggalAkhirInfo, #reqTanggalAwalInfo, #reqLampiran").show();
		
	$('#reqJamDatang').validatebox({required: false});
	$('#reqTanggalIjin').datebox({required: false});
	$('#reqJamDatang, #reqTanggalIjin').removeClass('validatebox-invalid');
	
	$('#reqTanggalAkhir, #reqTanggalAwal').datebox({required: true});
}

$(function(){
	$('#reqTanggalIjin').datebox().datebox('calendar').calendar({
		validator: function(date){
			var now = new Date();
			var d1 = new Date(now.getFullYear(), now.getMonth(), now.getDate());
			//var d2 = new Date(now.getFullYear(), now.getMonth()-1, 10);
			//return d1>=date && date>=d2;
			return d1>=date;
		}
	});

	$('input[id^="reqPegawaiNip"]').each(function(){
      $(this).autocomplete({
        source:function(request, response){
          $(".preloader-wrapper").show();
          var id= this.element.attr('id');
          var replaceAnakId= replaceAnak= urlAjax= "";

          urlAjax= "main/permohonan_json/cari_pegawai";

          $.ajax({
            url: urlAjax,
            type: "GET",
            dataType: "json",
            data: { term: request.term },
            success: function(responseData){
              $(".preloader-wrapper").hide();

              console.log(responseData);
              if(responseData == null)
              {
              	/*var id= $(this).attr('id');
              	var indexId= "reqPegawaiId";

              	if (id.indexOf('reqPegawaiNip') !== -1)
              	{
              		$("#reqPegawaiId").val("");
              		$("#reqPegawaiNama").text("");
              	}*/
                response(null);
              }
              else
              {
                var array = responseData.map(function(element) {
                  return {desc: element['desc'], id: element['id'], label: element['label'], namapegawai: element['namapegawai']};
                });
                response(array);
              }
            }
          })
        },
        // focus: function (event, ui) 
        select: function (event, ui) 
        { 
          var id= $(this).attr('id');
          var indexId= "reqPegawaiId";
          var idrow= namapegawai= "";
          idrow= ui.item.id;
          namapegawai= ui.item.namapegawai;

          if (id.indexOf('reqPegawaiNip') !== -1)
          {
            $("#reqPegawaiId").val(idrow);
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
		<div class="judul-monitoring"><span>Permohonan Dinas Luar Kantor</span></div>

		<form id="ff" method="post" novalidate enctype="multipart/form-data">
			<table class="table">
				<thead>
                	<tr>
						<td>NIP</td>
						<td>:</td>
						<td>
							<input type="hidden" id="reqPegawaiId" name="reqPegawaiId" value="<?=$reqPegawaiId?>" />
							<?
							if(!empty($reqId))
							{
							?>
							<label id="reqPegawaiNip"><?=$reqPegawaiNip?></label>
							<?
							}
							else
							{
							?>
							<input type="text" id="reqPegawaiNip" class="easyui-validatebox" style="width:50%;" value="<?=$reqPegawaiNip?>" />
							<?
							}
							?>
						</td>
					</tr>
                	<tr>
						<td>Nama Pegawai</td>
						<td>:</td>
						<td>
							<label id="reqPegawaiNama"><?=$reqPegawaiNama?></label>
						</td>
					</tr>
					<tr>
	                	<td>Jenis</td>
	                    <td>:</td>
	                    <td>
	                    	<input type="hidden" name="reqJenis" id="reqJenis" value="SPPD" /> Dinas Luar Kantor
	                    </td>
	                </tr>
					<tr>
						<td>Tanggal</td>
						<td>:</td>
						<td>
							<?=$reqTanggal?>
							<input type="hidden" name="reqTanggal" id="reqTanggal" value="<?=$reqTanggal?>" />
						</td>
					</tr>
					<tr id="reqTanggalIjinInfo" style="display:none">
	                    <td>Tanggal Ijin</td>
	                    <td>:</td>
	                    <td>
	                        <input class="easyui-datebox" id="reqTanggalIjin" name="reqTanggalIjin" value="<?=$reqTanggalIjin?>" />
	                    </td>
	                </tr> 
	                <tr id="reqJamDatangInfo" style="display:none">
	                    <td>Jam Datang / Pulang</td>
	                    <td>:</td>
	                    <td>
	                        <input type="text" id="reqJamDatang" name="reqJamDatang" size="6" class="easyui-validatebox" value="<?=$reqJamDatang?>" onkeydown="return format_menit(event,'reqJamDatang');" maxlength="5" /> * (format = hh:mm)
	                    </td>
	                </tr> 
	                <tr id="reqKeperluanInfo" style="display:none">
	                    <td>Keperluan</td>
	                    <td>:</td>
	                    <td>
	                        <select name="reqKeperluan">
	                            <option value="Kedinasan" <? if($reqKeperluan == 'Kedinasan') { ?> selected="selected" <? } ?>>Kedinasan</option>
	                            <option value="Pribadi" <? if($reqKeperluan == 'Pribadi') { ?> selected="selected" <? } ?>>Pribadi</option>
	                        </select>
	                    </td>
	                </tr>
	                <tr id="reqTanggalAwalInfo" style="display:none">
	                    <td>Tanggal Awal</td>
	                    <td>:</td>
	                    <td>
	                        <input class="easyui-datebox" id="reqTanggalAwal" name="reqTanggalAwal" value="<?=$reqTanggalAwal?>" />
	                    </td>
	                </tr> 
	                   
	                <tr id="reqTanggalAkhirInfo" style="display:none">
	                    <td>Tanggal Akhir</td>
	                    <td>:</td>
	                    <td>
	                        <input class="easyui-datebox" id="reqTanggalAkhir" name="reqTanggalAkhir" value="<?=$reqTanggalAkhir?>" />
	                    </td>
	                </tr>  
	                <tr>
	                    <td>Lokasi</td>
	                    <td>:</td>
	                    <td>
	                       <input type="text" id="reqLokasi" name="reqLokasi" class="easyui-validatebox" style="width:250px;" value="<?=$reqLokasi?>" />
	                    </td>
	                </tr>
	                <tr>
	                    <td>Keterangan</td>
	                    <td>:</td>
	                    <td>
	                       <input type="text" id="reqKeterangan" name="reqKeterangan" class="easyui-validatebox" style="width:350px;" value="<?=$reqKeterangan?>" />
	                    </td>
	                </tr>
	                <tr id="reqLampiran" style="display:none">
	                    <td>
	                    	Lampiran <!--<span style="color:red">*</span>-->
	                    </td>
	                    <td>:</td>
	                    <td>
	                    	<input name="reqLampiran[]" type="file" multiple class="maxsize-1024" accept="application/pdf,image/*" id="reqLampiran" value="" />
	                    	<span>File harus berfromat (pdf/jpg/jpeg)</span>
	                    </td>
	                </tr>
					<?
					if(!empty($reqId))
					{
					?>
					<tr>
                        <td>Persetujuan Anda</td>
                        <td>:</td>
                        <td>
                            <select name="reqAlasan" id="reqAlasan">
                            	<option value="">Pilih</option>
                                <option value="Y" <? if($reqAlasan == 'Y') { ?> selected="selected" <? } ?>>Disetujui</option>
                                <option value="T" <? if($reqAlasan == 'T') { ?> selected="selected" <? } ?>>Ditolak</option>
                            </select>
                        </td>
                    </tr>
                    <tr id="reqAlasanInfo" style="display:none">
                    	<td>Alasan Penolakan</td>
                        <td>:</td>
                    	<td>
							<input type="text" class="easyui-validatebox" id="reqAlasanTolak" name="reqAlasanTolak" value="<?=$reqAlasanTolak?>" style="width:80%">
                    	</td>
                    </tr>
                    <?
                	}
                    ?>
                    
					<? //$approval->echoInputField();?>
				</thead>
			</table>

			<table class="bordered highlight md-text table_list tabel-responsif responsive-table" id="link-table">
                <thead class="teal white-text"> 
                    <tr>
                        <th>Nama File Asli</th>
                        <th>Nama File</th>
                        <th style="text-align:center" width="70">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                	<?
					for($x=0; $x < $jumlahpermohonanfile; $x++)
                	{
                		// ;TIPE;
                	?>
                	<tr class="md-text">
                		<td><?=$arrPermohonanFile[$x]["KETERANGAN"]?></td>
                        <td><?=$arrPermohonanFile[$x]["NAMA"]?></td>
                        <td style="text-align:center">
                        	<span>
                                <a href="javascript:void(0)" class="round waves-effect waves-light red white-text" title="Download" onClick="opendownload('<?=$arrPermohonanFile[$x]["LINK_FILE"]?>')">
                                	<img src="images/icon-download.png" />
                                </a>
                            </span>
                        </td>
                    </tr>
                	<?
                	$i++;
                	}
                	?>

                	<?
                	if($jumlahpermohonanfile == 0)
                	{
                	?>
                	<tr class="md-text">
                		<td style="text-align: center;" colspan="3">Data Belum ada</td>
                	</tr>
                	<?
                	}
                	?>
                </tbody>
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