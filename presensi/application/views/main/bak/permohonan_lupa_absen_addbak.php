<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// $this->load->library("approval"); 
// $approval = new approval($this->ID, '0');

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('main/PermohonanLupaAbsen');
// $this->load->model('Pegawai');
// $this->load->model('IjinKoreksiBackdatePengecualian');

// $pegawai= new Pegawai();
// $ijin_koreksi_backdate_pengecualian= new IjinKoreksiBackdatePengecualian();

$reqId= $this->input->get("reqId");

$set= new PermohonanLupaAbsen();
if($reqId == "")
{
	$reqMode = "insert";
	$reqTanggal = date("d-m-Y");
}
else
{
	
	$reqMode = "update";	
	// $set->selectByParams(array("PERMOHONAN_LAMBAT_PC_ID" => $reqId));
	// $set->firstRow();
	// $reqPegawaiId = $set->getField("PEGAWAI_ID");
	// $reqTahun = $set->getField("TAHUN");
	// $reqNomor = $set->getField("NOMOR");
	// $reqTanggal = $set->getField("TANGGAL");
	// $reqJabatan = $set->getField("JABATAN");
	// $reqCabang = $set->getField("CABANG");
	// $reqDepartemen = $set->getField("DEPARTEMEN");
	// $reqSubDepartemen = $set->getField("SUB_DEPARTEMEN");
	// $reqTanggalAwal = $set->getField("TANGGAL_AWAL");
	// $reqTanggalAkhir = $set->getField("TANGGAL_AKHIR");
	// $reqJumlahHari = $set->getField("LAMA_CUTI");
	// $reqKeterangan = $set->getField("KETERANGAN");
	// $reqAlamat = $set->getField("ALAMAT");
	// $reqTelepon = $set->getField("TELEPON");
	// $reqPegawaiIdApproval = $set->getField("PEGAWAI_ID_APPROVAL");
	// $reqApproval = $set->getField("APPROVAL");
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
		url:'main/permohonan_lupa_absen_json/add',
		onSubmit:function(){
			
			reqPegawaiId= $("#reqPegawaiId").val();
			if(reqPegawaiId == "")
			{
				$.messager.alert('Info', "Mohon Pilih Pegawai", 'info');
				return false;
			}

			<?
			if(!empty($reqId))
			{
			?>
			if($("#reqAlasan").val() == "" || $("#reqAlasan").val() == null )
			{
				$.messager.alert('Info', "mohon pilih persetujuan.", 'info');
				return false;
			}
			<?
			}
			?>

			if($(":checkbox").is(':checked') == true)
			{
				return $(this).form('validate');
			}
			else
			{
				$.messager.alert('Info', "Mohon Pilih Jenis Lupa Absen", 'info');	
				return false;
			}
			
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
				document.location.href = "app/loadUrl/main/permohonan_lupa_absen";
		}
	});

	<?
	if(!empty($reqId))
	{
	?>
		$("#reqAlasan").change(function() {
			setAlasanTolak();
		});
	<?
	}
	?>
	
	$('#reqTanggalIjin').datebox(
	{
		editable : false
	});
	
	// setJenis();
	// $("#reqJenis").change(function() {
	// 	setJenis();
	// });
});

function setJenis()
{
	//reqJamDatangInfo;reqTanggalIjinInfo
	var reqJenis= "";
	reqJenis= $("#reqJenis").val();
	//alert(reqJenis);
	$("#reqTanggalIjinInfo, #reqJamDatangInfo, #reqKeperluanInfo, #reqTanggalAkhirInfo, #reqTanggalAwalInfo").hide();
	if(reqJenis == "SPPD")
	{
		$("#reqTanggalAkhirInfo, #reqTanggalAwalInfo").show();
		
		$('#reqJamDatang').validatebox({required: false});
		$('#reqTanggalIjin').datebox({required: false});
		$('#reqJamDatang, #reqTanggalIjin').removeClass('validatebox-invalid');
		
		$('#reqTanggalAkhir, #reqTanggalAwal').datebox({required: true});
	}
	else
	{
		$("#reqTanggalIjinInfo, #reqJamDatangInfo, #reqKeperluanInfo").show();
		$("#reqTanggal").val("");
		$("#reqJamDatang").val("");
		//$('#reqTanggalAkhir').datebox('setValue', '');	
		
		$('#reqJamDatang').validatebox({required: true});
		$('#reqTanggalIjin').datebox({required: true});
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

$( document ).ready(function() {
	var jqxhr = $.get("ijin_koreksi_backdate_json/cek_status/?reqIjinKoreksiId=26", function(data) {
		data = data.split("-");
		<?
		if($jumlah_pegawai_pengecualian > 0)
		{
		?>
		if(data[0] == 1)
		{
			$('#reqTanggalIjin').datebox().datebox('calendar').calendar({
				validator: function(date){
					var now = new Date();
					var d1 = new Date(now.getFullYear(), now.getMonth()-1000, now.getDate());
					var d2 = new Date(now.getFullYear(), now.getMonth(), now.getDate());
					//var d2 = new Date(now.getFullYear(), now.getMonth()-1, 10);
					//return d1>=date && date>=d2;
					return d1<=date && date<=d2;
				}
			});
		}
		<?
		}
		else
		{
		?>
		if(data[0] == 1)
		{
			var tanggal_maksimal = data[1];
			/*
			var now = new Date();
			var tanggal_sekarang = now.getDate();
			*/
			var tanggal_sekarang = <?=date('d')?>;
			if (tanggal_maksimal >= tanggal_sekarang)
			{	
				var tanggal_awal = data[2];
				
				$("#reqTanggalIjin").datebox().datebox('calendar').calendar({
					validator: function(date){
						var now = new Date();
						var d1 = new Date(now.getFullYear(), now.getMonth()-data[4], tanggal_awal);
						var d2 = new Date(now.getFullYear(), now.getMonth(), now.getDate());
						//return d1>=date;
						return d1<=date && date<=d2;
					}
				});
			}
			else
			{
				$("#reqTanggalIjin").datebox().datebox('calendar').calendar({
					validator: function(date){
						var now = new Date();
						var d1 = new Date(now.getFullYear(), now.getMonth(), 1);
						var d2 = new Date(now.getFullYear(), now.getMonth(), now.getDate());
						//return d1<=date;
						return d1<=date && date<=d2;
					}
				});
			}
		}
		else
		{
			$('#reqTanggalIjin').datebox().datebox('calendar').calendar({
				validator: function(date){
					var now = new Date();
					var d1 = new Date(now.getFullYear(), now.getMonth(), now.getDate());
					//var d2 = new Date(now.getFullYear(), now.getMonth()-1, 10);
					//return d1>=date && date>=d2;
					return d1>=date;
				}
			});
		}
		<?
		}
		?>
	});
	
});

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

</script>
</head>

<body class="bg-permohonan">
	<div class="area-permohonan">
		<div class="judul-monitoring"><span>Permohonan Lupa Absen</span></div>

		<form id="ff" method="post" novalidate enctype="multipart/form-data">
			<table class="table">
				<thead>
                	<tr>
						<td>NIP</td>
						<td>:</td>
						<td>
							<input type="hidden" id="reqPegawaiId" name="reqPegawaiId" value="<?=$reqPegawaiId?>" />
							<input type="text" id="reqPegawaiNip" class="easyui-validatebox" style="width:50%;" value="<?=$reqPegawaiNip?>" />
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
						<td>Tanggal</td>
						<td>:</td>
						<td>
							<?=$reqTanggal?>
							<input type="hidden" name="reqTanggal" id="reqTanggal" value="<?=$reqTanggal?>" />
						</td>
					</tr>
					<tr>
						<td>Jenis Lupa Absen</td>
						<td>:</td>
						<td>
							<input type="checkbox" id="reqStatusMasuk" name="reqStatusMasuk" value="1">Masuk<br>
							<input type="checkbox" id="reqStatusPulang" name="reqStatusPulang" value="1">Pulang<br>
						</td>
					</tr>
					<tr>
						<td>Tanggal Lupa Absen</td>
						<td>:</td>
						<td>
							<input class="easyui-datebox" id="reqTanggalIjin" name="reqTanggalIjin" value="<?=$reqTanggalIjin?>">
						</td>
					</tr> 
					<tr>
						<td>Keterangan</td>
						<td>:</td>
						<td>
							<input type="text" id="reqKeterangan" name="reqKeterangan" class="easyui-validatebox" style="width:350px;" value="<?=$reqKeterangan?>" />
						</td>
					</tr>

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
                    
					<? //$approval->echoInputField();?>
				</thead>
			</table>
			<input type="hidden" name="reqId" value="<?=$reqId?>" />
			<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
			<input type="button" onclick="document.location.href='app/loadUrl/main/permohonan'" class="btn btn-primary" value="Kembali" />
			<input type="submit" name="reqSubmit" class="btn btn-primary" value="Simpan" />
		</form>
	</div>
</body>
</html>