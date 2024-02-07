<?
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

// $this->load->model('PendidikanRiwayat');

$reqId= $this->input->get("reqId");
$reqRowId= httpFilterRequest("reqRowId");

// $set= new PendidikanRiwayat();
// $set->selectByParams(array(), -1, -1, " AND A.PEGAWAI_ID = ".$reqId);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Untitled Document</title>
	<base href="<?=base_url()?>" />
	<link rel="stylesheet" type="text/css" href="css/gaya.css">
	<?
	if($reqId == "")
	{
		echo '<script language="javascript">';
		echo 'alert("Isi identitas pegawai terlebih dahulu.");';	
		echo 'window.parent.location.href = "app/loadUrl/app/pegawai_add_data";';
		echo '</script>';
	//exit();
	}
	?>
	<script type="text/javascript" src="lib/easyui/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
	<!--<script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>-->
	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
	<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>
	<script>
		function hapusData(id)
		{		
			$.messager.defaults.ok = 'Ya';
			$.messager.defaults.cancel = 'Tidak';
			reqmode= "prestasi";
			infoMode= "Apakah anda yakin menghapus data terpilih"
			//alert(statusaktif);return false;
			$.messager.confirm('Konfirmasi', infoMode+" ?",function(r){
				if (r){
					var s_url= "../json-anjab/delete.php?reqMode="+reqmode+"&id="+id;
					$.ajax({'url': s_url,'success': function(msg){
						if(msg == ''){}
							else
							{
								parent.frames['mainFrame'].location.href = 'app/loadUrl/app/form_prestasi_monitoring.php/?reqId=<?=$reqId?>';
								parent.frames['mainFrameDetil'].location.href = 'app/loadUrl/app/pegawai_add_pangkat_data.php/?reqId=<?=$reqId?>';
							}
						}});
				}
			});		
		}
		
		function addData(id)
		{
			parent.frames['mainFrameDetil'].location.href = 'app/loadUrl/app/pegawai_add_pangkat_data.php/?reqId=<?=$reqId?>&reqRowId='+id;
		}
	</script>

	<link href="css/bluetabs.css" rel="stylesheet" type="text/css" />
</head>

<body class="bg-kanan-full">
	<div id="judul-popup">Riwayat Pendidikan</div>
	<div id="konten-table">
		<div id="popup-tabel2">
			<table class="table_list table" cellspacing="1" width="100%" border="0" id="link-table">
				<thead> 
					<tr>
						<th style="background:#073b50;color:#FFF; text-align:center; vertical-align:middle;">Tempat Sekolah</th>
						<th style="background:#073b50;color:#FFF; text-align:center; vertical-align:middle;">Kepala Sekolah</th>
						<th style="background:#073b50;color:#FFF; text-align:center; vertical-align:middle;">No. STTB</th>
						<th style="background:#073b50;color:#FFF; text-align:center; vertical-align:middle;">Tgl. STTB</th>          
						<th style="background:#073b50;color:#FFF; text-align:center; vertical-align:middle;">Status Pendidikan</th>
						<th style="background:#073b50;color:#FFF; text-align:center; vertical-align:middle;">Last create / update</th>
						<th style="background:#073b50;color:#FFF; text-align:center; vertical-align:middle;">Last user Access</th>
					</tr>
				</thead>
				<tbody>
					<?
					// while($set->nextRow() {
					?>
					<!-- <tr>
						<td><?=$set->getField('PANGKAT_KODE')?></td>
						<td><?=dateToPageCheck($set->getField('TMT_PANGKAT'))?></td>
						<td><?=$set->getField('NO_SK')?></td>
						<td><?=dateToPageCheck($set->getField('TANGGAL_SK'))?></td>
						<td><?=$set->getField('JENIS_RIWAYAT_NAMA')?></td>
						<td><?=dateToPageCheck($set->getField('LAST_PROSES_DATE'))?></td>
						<td><?=$set->getField('LAST_PROSES_USER')?></td>
						<td>
							<a href="javascript:void(0)" onclick="addData('')"><img src="images/icon-add.png" /></a>-<a href="javascript:void(0)" onclick="addData('<?=$set->getField('PANGKAT_RIWAYAT_ID')?>')"><img src="images/icon-update.png" /></a>-<a href="javascript:void(0)" onclick="hapusData('<?=$arrData[$index_loop]["JABATAN_PRESTASI_ID"]?>')"><img src="images/icon-delete.png" /></a>
						</td>
					</tr> -->
					<?
				// }
					?>
				</tbody>
			</table>
		</div>
	</div>

	<!-- Bootstrap Core CSS -->
	<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- Bootstrap Core JavaScript -->
	<script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

	<!-- eModal -->
	<script src="lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal.min.js"></script>
	<script type="text/javascript">

	// Display an modal whith iframe inside, with a title
	function openPopup(page) {
		eModal.iframe(page, 'A.I.M SYSTEM - Investasi')
	}
</script>

</body>
</html>