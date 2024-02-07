<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model('bio/PersonnelEmployee');
$this->load->model('bio/PersonnelEmployeeArea');
$this->load->model('siap/SatuanKerja');

$reqId= $this->input->get("reqId");
$reqMode= $this->input->get("reqMode");

$set= new PersonnelEmployee();
$set->selectByParamsSync(array("P.PEGAWAI_ID" => $reqId));
// echo $set->query;exit();
$set->firstRow();
$reqRowId= $set->getField("ID");
$reqPersonalEmployeId= $set->getField("V_PERSONNEL_EMPLOYEE_ID");
$reqPegawaiId= $set->getField("PEGAWAI_ID");
$reqPegawaiFirstName= $set->getField("FIRST_NAME");
$reqPegawaiAktifAbsen= $set->getField("IS_AKTIF");
$reqPegawaiNama= $set->getField("NAMA_LENGKAP");
$reqPegawaiNipBaru= $set->getField("NIP_BARU");
$reqPegawaiJabatan= $set->getField("JABATAN_RIWAYAT_NAMA");
$reqPegawaiPin= $set->getField("DEVICE_PASSWORD");
$reqPegawaiOpd= $set->getField("SATUAN_KERJA_INFO");
$reqPegawaiAreaCode= $set->getField("AREA_CODE");
$reqPegawaiAreaNama= $set->getField("AREA_NAME");
$reqPegawaiFp= $set->getField("JUMLAH_FP");
$reqPegawaiFf= $set->getField("JUMLAH_FF");

if(empty($reqPersonalEmployeId))
{
	$reqMode = "insert";
}
else
{
	$reqMode = "update";
}

$set= new PersonnelEmployeeArea();
$index_data= 0;
if(!empty($reqRowId))
{
	$arrAreaAbsensi= [];
	// $statement= " AND A.PERSONNEL_EMPLOYEE_ID = ".$reqRowId;
	$statement= " AND VBIO.EMP_CODE = '".$reqPegawaiId."'";

	$set->selectByParams(array(), -1,-1, $statement);
	// echo $set->query;exit;
	while($set->nextRow())
	{
		$arrAreaAbsensi[$index_data]["AREA_CODE"]= $set->getField("AREA_CODE");
		$arrAreaAbsensi[$index_data]["AREA_NAME"]= $set->getField("AREA_NAME");
		$arrAreaAbsensi[$index_data]["STATUS_DEFAULT"]= $set->getField("STATUS_DEFAULT");
		$index_data++;
	}
}
$jumlahareaabsensi = $index_data;
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

	// $.messager.progress({title:'Proses integrasi data.',msg:'Proses data...'});
	// var bar = $.messager.progress('bar');
	// bar.progressbar({text: ''});
	// $.messager.progress('close');

	$('#ff').form({
		url:'bio/pegawai_json/adddetil',
		onSubmit:function(){
			var reqPegawaiAreaCode= "";

			reqPegawaiAreaCode= $("#reqPegawaiAreaCode").val();
			if(reqPegawaiAreaCode == "" || reqPegawaiAreaCode == null )
			{
				$.messager.alert('Info', "isikan terlebih dahulu Area Default.", 'info');
				return false;
			}
			else
				return $(this).form('validate');
		},
		success:function(data){
			// console.log(data);return false;
			data = data.split("-");
			rowid= data[0];
			infodata= data[1];

			if(rowid == "xxx")
			{
				$.messager.alert('Info', infodata, 'info');
			}
			else
			{
				$.messager.progress({title:'Proses integrasi data.',msg:'Proses data...'});
				var bar = $.messager.progress('bar');
				bar.progressbar({text: ''});

				checksync();
			}
		}
	});

	$('input[id^="reqPegawaiAreaDefault"]').each(function(){
      $(this).autocomplete({
        source:function(request, response){
          // $(".preloader-wrapper").show();
          var id= this.element.attr('id');
          var replaceAnakId= replaceAnak= urlAjax= "";

          urlAjax= "siap/satuan_kerja_json/automesin?reqDefault=1";

          $.ajax({
            url: urlAjax,
            type: "GET",
            dataType: "json",
            data: { term: request.term },
            success: function(responseData){
              // $(".preloader-wrapper").hide();

              // console.log(responseData);
              if(responseData == null)
              {
                response(null);
              }
              else
              {
                var array = responseData.map(function(element) {
                  return {desc: element['desc'], id: element['id'], label: element['label']};
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
          // var indexId= "reqPegawaiId";
          var idrow= namapegawai= "";
          idrow= ui.item.id;
          namalabel= ui.item.label;

          if (id.indexOf('reqPegawaiAreaDefault') !== -1)
          {
            $("#reqPegawaiAreaCode").val(idrow);
            $("#reqPegawaiAreaNama").val(namalabel);

            reqPegawaiAreaCode= "<?=$reqPegawaiAreaCode?>";
            if(reqPegawaiAreaCode == "")
            	$("#reqPegawaiSebelumAreaCode").val(idrow);
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

function addRow()
{
	if (!document.getElementsByTagName) return;
	tabBody=document.getElementsByTagName("TBODY").item(0);

	var rownum= tabBody.rows.length;

	reqAreaCode= reqAreaCodeLain= "";
	$('input[id^="reqAreaCode"]').each(function(){
		// var id= $(this).attr('id');
		// id= id.replace("reqAreaCode", "")
		valdata= $(this).val();
		// console.log(valdata);

		n= String(valdata).indexOf(".");

		if(parseInt(n) > 0)
		{
			if(reqAreaCodeLain == "")
				reqAreaCodeLain= valdata;
			else
				reqAreaCodeLain= reqAreaCodeLain+","+valdata;
		}
		else
		{
			if(reqAreaCode == "")
				reqAreaCode= valdata;
			else
				reqAreaCode= reqAreaCode+","+valdata;
		}

		// console.log(reqAreaCodeLain);
	});

	var s_url= "app/loadUrl/biotime/pegawai_add_area?reqIndex="+rownum+"&reqAreaCode="+reqAreaCode+"&reqAreaCodeLain="+reqAreaCodeLain;
	$.ajax({'url': s_url,'success': function(data){
		$("#tbDataData").append(data);
	}});
}

function checksync()
{
	reqCode= "<?=$reqPegawaiId?>";
	urlAjax= "bio/pegawai_json/flagbelumsyncarea?reqCode="+reqCode;
	$.ajax({'url': urlAjax, dataType: 'json', 'success': function(datajson){
		if(parseInt(datajson) > 0)
		{
			checksync();
		}
		else
		{
			urlAjax= "bio/pegawai_json/syncpersonal?reqCode="+reqCode;
			$.ajax({'url': urlAjax, dataType: 'json', 'success': function(datajson){

				urlAjax= "bio/pegawai_json/flagbelumsyncpersonalarea?reqCode="+reqCode;
				$.ajax({'url': urlAjax, dataType: 'json', 'success': function(datajson){
					if(parseInt(datajson) > 0)
					{
						urlAjax= "bio/pegawai_json/syncpersonalarea?reqCode="+reqCode;
						$.ajax({'url': urlAjax, dataType: 'json', 'success': function(datajson){
							checksync();
						}});
					}
					else
					{
						$.messager.progress('close');
						reloadawal();
					}
				}});

			}});
		}
	}});
}

function reloadawal()
{
	// document.location.href = "app/loadUrl/biotime/pegawai";
	document.location.href = "app/loadUrl/biotime/pegawai_add?reqId=<?=$reqPegawaiId?>";

}

function reloadkembali()
{
	document.location.href = "app/loadUrl/biotime/pegawai";

}
</script>
</head>

<body class="bg-permohonan">
	<div class="area-permohonan">
		<div class="judul-monitoring"><span>Detil Pegawai</span></div>

		<form id="ff" method="post" novalidate enctype="multipart/form-data">
			<table class="table">
				<thead>
                	<tr>
						<td>ID</td>
						<td>:</td>
						<td>
							<label id="reqPegawaiId"><?=$reqPegawaiId?></label>
						</td>
					</tr>
					<tr>
						<td>Status</td>
						<td>:</td>
						<td>
							<select id="reqPegawaiAktifAbsen" name="reqPegawaiAktifAbsen">
								<option value="0" <? if($reqPegawaiAktifAbsen == "0") echo "selected";?>>Tidak</option>
								<option value="1" <? if($reqPegawaiAktifAbsen == "1") echo "selected";?>>Aktif</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Nama</td>
						<td>:</td>
						<td>
							<label id="reqPegawaiNama"><?=$reqPegawaiNama?></label>
						</td>
					</tr>
					<tr>
						<td>NIP Baru</td>
						<td>:</td>
						<td>
							<label id="reqPegawaiNipBaru"><?=$reqPegawaiNipBaru?></label>
						</td>
					</tr>
					<tr>
						<td>Jabatan</td>
						<td>:</td>
						<td>
							<label id="reqPegawaiJabatan"><?=$reqPegawaiJabatan?></label>
						</td>
					</tr>
					<tr>
						<td>OPD</td>
						<td>:</td>
						<td>
							<label id="reqPegawaiOpd"><?=$reqPegawaiOpd?></label>
						</td>
					</tr>
					<tr>
						<td>FP/FF</td>
						<td>:</td>
						<td>
							<label id="reqPegawaiFp"><?=$reqPegawaiFp?></label> / <label id="reqPegawaiFf"><?=$reqPegawaiFf?></label>
						</td>
					</tr>
					<tr>
						<td>PIN</td>
						<td>:</td>
						<td>
							<input type="text" id="reqPegawaiPin" name="reqPegawaiPin" class="easyui-validatebox" style="width:10%;" value="<?=$reqPegawaiPin?>" />
						</td>
					</tr>
					<tr>
						<td>Area Default</td>
						<td>:</td>
						<td>
							<input type="hidden" id="reqPegawaiSebelumAreaCode" name="reqPegawaiSebelumAreaCode" value="<?=$reqPegawaiAreaCode?>" />
							<input type="hidden" id="reqPegawaiAreaCode" name="reqPegawaiAreaCode" value="<?=$reqPegawaiAreaCode?>" />
							<input type="hidden" id="reqPegawaiAreaNama" name="reqPegawaiAreaNama" value="<?=$reqPegawaiAreaNama?>" />
							<input type="text" id="reqPegawaiAreaDefault" disabled class="easyui-validatebox" style="width:50%;" value="<?=$reqPegawaiAreaNama?>" />
						</td>
					</tr>
					<tr>
	                    <td>
	                    	Area Absensi
	                	</td>
                        <td>:</td>
                    	<td style="text-align: top">
							<table class="bordered highlight md-text table_list tabel-responsif responsive-table" id="tbDataData" style="margin-top: 0px !important;">
			                <thead class="teal white-text"> 
			                    <tr>
			                        <th>Nama Area</th>
			                        <th style="text-align:center" width="70">Aksi</th>
			                    </tr>
			                </thead>
			                <tbody>
			                	<?
								for($x=0; $x < $jumlahareaabsensi; $x++)
			                	{
			                	?>
			                	<tr class="md-text">
			                		<td>
			                			<input type="hidden" id="reqAreaCode<?=$x?>" name="reqAreaCode[]" value="<?=$arrAreaAbsensi[$x]["AREA_CODE"]?>" />
			                			<label id="reqAreaLabelCode<?=$x?>"><?=$arrAreaAbsensi[$x]["AREA_NAME"]?></label>
			                		</td>
			                        <td style="text-align:center">
			                        	<?
			                        	if(empty($arrAreaAbsensi[$x]["STATUS_DEFAULT"]))
			                        	{
			                        	?>
			                        	<span>
			                                <a href="javascript:void(0)" class="round waves-effect waves-light red white-text" title="Hapus" onclick="$(this).parent().parent().parent().remove();$('#reqStatusDataHapus').val('1')">
			                                	<img src="images/icon-hapus.png" />
			                                </a>
			                            </span>
			                            <?
			                        	}
			                            ?>
			                        </td>
			                    </tr>
			                	<?
			                	$i++;
			                	}
			                	?>

			                	<?
			                	if($jumlahareaabsensi == 0)
			                	{
			                	?>
			                	<tr class="md-text">
			                		<td style="text-align: center;" colspan="2">Data Belum ada</td>
			                	</tr>
			                	<?
			                	}
			                	?>
			                </tbody>
			                <tfoot>
			                	<tr>
			                		<td colspan="2"><a style="cursor:pointer; text-decoration: none;" title="Tambah" onClick="addRow()"><img src="images/icon-tambah.png" width="16" height="16" border="0" /> Tambah</a></td>
			                	</tr>
			                </tfoot>
			            </table>
                    	</td>
	                </tr>
				</thead>
			</table>

			<input type="hidden" name="reqStatusDataHapus" id="reqStatusDataHapus" />
			<input type="hidden" name="reqId" value="<?=$reqPersonalEmployeId?>" />
			<input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
			<input type="hidden" name="reqPegawaiId" value="<?=$reqPegawaiId?>" />
			<input type="hidden" name="reqPegawaiFirstName" value="<?=$reqPegawaiFirstName?>" />
			<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
			<input type="button" onclick="reloadkembali()" class="btn btn-primary" value="Kembali" />
			<input type="submit" name="reqSubmit" class="btn btn-primary" value="Simpan" />
		</form>
	</div>

<script type="text/javascript">
	$('#reqPegawaiPin').bind('keyup paste', function(){
		this.value = this.value.replace(/[^0-9]/g, '');
	});
</script>

</body>
</html>