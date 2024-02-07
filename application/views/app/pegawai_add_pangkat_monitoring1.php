<?
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$this->load->model('PangkatRiwayat');

$reqId= $this->input->get("reqId");
$reqRowId= httpFilterRequest("reqRowId");

$set= new PangkatRiwayat();
$set->selectByParams(array(), -1, -1, " AND A.PEGAWAI_ID = ".$reqId);
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

		$.messager.confirm('Konfirmasi','Yakin menghapus data terpilih ?',function(r){
				if (r){
					var jqxhr = $.get( "program_aksi_ren_json/delete/?reqId="+id, function() {
						document.location.reload();
					})
					.done(function() {
						document.location.reload();
					})
					.fail(function() {
						alert( "error" );
					});								
				}
			});				
	}
</script>

<link href="css/bluetabs.css" rel="stylesheet" type="text/css" />
</head>

<body class="bg-kanan-full">
	<div id="judul-popup">Riwayat Pangkat</div>
    <div id="konten-table">
    	<div id="popup-tabel2">
    	<table class="table" id="link-table">
    	<thead> 
        <tr>
        	<td></td>
            <th>Gol</th>
            <th>TMT Gol</th>
            <th>No. SK</th>
            <th>Tgl. SK</th>          
            <th>Jenis Gol/ KP</th>
            <th>Last create / update</th>
            <th>Last user Access</th>
        </tr>
        </thead>
        <tbody>
        <?
		while($set->nextRow())
		{
        ?>
        <tr style="cursor:pointer">
        	<td><a href="app/loadUrl/app/pegawai_add_pangkat_data/?reqId=<?=$reqId?>&reqRowId=<?=$set->getField('PANGKAT_RIWAYAT_ID')?>&reqMode=view">link data</a></td>
            <td><?=$set->getField('PANGKAT_KODE')?></td>
            <td><?=dateToPageCheck($set->getField('TMT_PANGKAT'))?></td>
            <td><?=$set->getField('NO_SK')?></td>
            <td><?=dateToPageCheck($set->getField('TANGGAL_SK'))?></td>
            <td><?=$set->getField('JENIS_RIWAYAT_NAMA')?></td>
            <td><?=dateToPageCheck($set->getField('LAST_PROSES_DATE'))?></td>
            <td><?=$set->getField('LAST_PROSES_USER')?></td>
        </tr>
        <?
		}
        ?>
        </tbody>
        </table>
		<script type="text/javascript">
			$(function ()
			{
			  // Hide the first cell for JavaScript enabled browsers.
			  $('#link-table td:first-child').hide();
		
			  // Apply a class on mouse over and remove it on mouse out.
			  $('#link-table tr').hover(function ()
			  {
				$(this).toggleClass('Highlight');
			  });
		  
			  // Assign a click handler that grabs the URL 
			  // from the first cell and redirects the user.
			  $('#link-table tr').click(function ()
			  {
				var id= $(this).find('td a').attr('href');
				if(typeof id == "undefined" || id == ''){}
				else
				parent.frames['mainFrameDetil'].location.href = $(this).find('td a').attr('href');
			  });
			});
		</script>
    </div>
</div>
</form>

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