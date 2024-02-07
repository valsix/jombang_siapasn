<?
$arrData= array("No", "Nama File", "Jenis/Judul Dokumen", "Penamaan File", "Status", "Aksi");
$reqId = $this->input->get("reqId");

$lokasi_link_file= "uploads/".$reqId."/";
$ambil_data_file= lihatfiledirektori($lokasi_link_file);
//print_r($ambil_data_file);exit;
//echo pathinfo($ambil_data_file[0], PATHINFO_BASENAME);exit;
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="msapplication-tap-highlight" content="no">
	<meta name="description" content="Simpeg Jombang">
	<meta name="keywords" content="Simpeg Jombang">
	<title>Simpeg Jombang</title>
	<base href="<?=base_url()?>" />

	<link rel="stylesheet" type="text/css" href="css/gaya.css">

	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
	<script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>

	<!-- CORE CSS-->    
	<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
	<link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
	<!-- CSS style Horizontal Nav-->    
	<link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
	<!-- Custome CSS-->    
	<link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

	<link rel="stylesheet" type="text/css" href="lib/dropzone/dropzone.css">
	<link rel="stylesheet" type="text/css" href="lib/dropzone/basic.css">
	<script src="lib/dropzone/dropzone.js"></script>

	<style type="text/css">
		@media 
		only screen and (max-width: 760px),
		(min-device-width: 768px) and (max-device-width: 1024px)  {
			table.tabel-responsif thead th{ display:none; }
      /*
      Label the data
      */
      <?
      for($i=0; $i < count($arrData); $i++)
      {
      	$index= $i+1;
      	?>
      	table.tabel-responsif td:nth-of-type(<?=$index?>):before { content: "<?=$arrData[$i]?>"; }
      	<?
      }
      ?>
  }

  .round {
  	border-radius: 50%;
  	padding: 5px;
  }
</style>

<script type="text/javascript">
	function hapusdata(id, statusaktif, pegawai_id)
	{
		$.messager.defaults.ok = 'Ya';
		$.messager.defaults.cancel = 'Tidak';
		reqmode= "pangkat_riwayat_1";
		infoMode= "Apakah anda yakin mengaktifkan data terpilih"
		if(statusaktif == "")
		{
			reqmode= "pangkat_riwayat_0";
			infoMode= "Apakah anda yakin menonaktifkan data terpilih"
		}

		$.messager.confirm('Konfirmasi', infoMode+" ?",function(r){
			if (r){
				var s_url= "pangkat_riwayat_json/delete/?reqMode="+reqmode+"&reqId="+id;
			//var request = $.get(s_url);
				$.ajax({'url': s_url,'success': function(msg){
					if(msg == ''){}
						else
						{
					  // alert(msg);return false;
					  $.messager.alert('Info', msg, 'info');
					  document.location.href= "app/loadUrl/app/pegawai_add_pangkat_monitoring/?reqId="+pegawai_id;
				  }
			  }});
			}
		});  
	}

</script>
</head>

<body>
	<div id="basic-form" class="section">
		<div class="row">
			<div class="col s12 m10 offset-m1">
				<ul class="collection card">
					<li class="collection-item ubah-color-warna">UPLOAD FILE</li>
					<li class="collection-item">
						<?php /*?><div class="row center">
							<button class="btn waves-effect waves-light indigo" style="font-size:9pt" type="button" id="upload">Upload
								<i class="mdi-file-file-upload left hide-on-small-only"></i>
							</button>
						</div><?php */?>
						<div class="row">
							<div class="col s12">
								<form action="pegawai_file_json/upload/?reqId=<?=$reqId?>" class="dropzone" id="mydropzone"  method="post" enctype="multipart/form-data">
									<?php /*?><div class="fallback">
										<input type="file" name="file" />
										<input type="hidden" name="reqId" value="<?=$reqId?>" />
										<!-- <input type="submit" name="submit" value="submit" /> -->
									</div><?php */?>
								</form>
							</div>

							<script type="text/javascript">
								Dropzone.options.mydropzone = {
									dictDefaultMessage:"Klik Saya Untuk Upload File",
									init: function () {
										this.on("success", function (data) {
											//alert(data);return false;
											if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
												document.location.reload(); 
											}
										});
									}
								};
							</script>
						</div>
						<?php /*?><div class="row center">
							<button class="btn waves-effect waves-light green" style="font-size:9pt" type="submit" name="action">Simpan
								<i class="mdi-content-save left hide-on-small-only"></i>
							</button>
						</div><?php */?>
						<div class="row">
							<div class="col s12">
								<table class="bordered highlight md-text table_list tabel-responsif" id="link-table">
									<thead class="teal white-text"> 
										<tr>
											<th width="20">No</th>
											<th>Nama File</th>
											<th>Jenis/Judul Dokumen</th>
											<th>Penamaan File</th>
											<th>Status</th>
											<th>Aksi</th>
										</tr>
									</thead>
									<tbody>
										<tr class="md-text">
											<td>1</td>
											<td>askasasasasasasasases.jpg</td>
											<td>Kartu Askes</td>
											<td>AKES_12312312313213123123</td>
											<td>
												<i class="mdi-alert-warning orange-text"></i>
											</td>
											<td>
												<span>
													<a href="javascript:void(0)" class="round waves-effect waves-light red white-text" title="Hapus" onClick="hapusdata('<?=$reqRowId?>','','<?=$reqId?>')">
														<i class="mdi-action-delete"></i>
													</a>
												</span>
												<span>
													<a href="javascript:void(0)" class="round waves-effect waves-light purple white-text" title="Log"  onclick="parent.openModal('app/loadUrl/app/informasi_data_log?reqjson=pangkat_riwayat_json/log/<?=$reqRowId?>&reqjudul=Data Pangkat Riwayat Log')">
														<i class="mdi-action-query-builder"></i>
													</a>
												</span>
												<span>
													<a href="javascript:void(0)" class="round waves-effect waves-light blue white-text" title="Ubah" onClick="parent.setload('pegawai_add_e_file_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>')">
														<i class="mdi-editor-mode-edit"></i>
													</a>
												</span>
											</td>
										</tr>
										<tr class="md-text">
											<td>2</td>
											<td>sasasas.jpg</td>
											<td>Lampiran Lain</td>
											<td></td>
											<td>
												<i class="mdi-action-done green-text"></i>
											</td>
											<td>
												<span>
													<a href="javascript:void(0)" class="round waves-effect waves-light red white-text" title="Hapus" onClick="hapusdata('<?=$reqRowId?>','','<?=$reqId?>')">
														<i class="mdi-action-delete"></i>
													</a>
												</span>
												<span>
													<a href="javascript:void(0)" class="round waves-effect waves-light purple white-text" title="Log"  onclick="parent.openModal('app/loadUrl/app/informasi_data_log?reqjson=pangkat_riwayat_json/log/<?=$reqRowId?>&reqjudul=Data Pangkat Riwayat Log')">
														<i class="mdi-action-query-builder"></i>
													</a>
												</span>
												<span>
													<a href="javascript:void(0)" class="round waves-effect waves-light blue white-text" title="Ubah" onClick="parent.setload('pegawai_add_e_file_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>')">
														<i class="mdi-editor-mode-edit"></i>
													</a>
												</span>
											</td>

										</tr>
										<tr class="md-text">
											<td>3</td>
											<td>ijazah.jpg</td>
											<td>Ijazah Kuliah</td>
											<td>IJAZAH_S1_212567890056789987657</td>
											<td>
												<i class="mdi-av-not-interested red-text"></i>
											</td>
											<td>
												<span>
													<a href="javascript:void(0)" class="round waves-effect waves-light green white-text" title="Aktifkan" onClick="hapusdata('<?=$reqRowId?>','1','<?=$reqId?>')">
														<i class="mdi-action-autorenew"></i>
													</a>
												</span>
												<span>
													<a href="javascript:void(0)" class="round waves-effect waves-light purple white-text" title="Log"  onclick="parent.openModal('app/loadUrl/app/informasi_data_log?reqjson=pangkat_riwayat_json/log/<?=$reqRowId?>&reqjudul=Data Pangkat Riwayat Log')">
														<i class="mdi-action-query-builder"></i>
													</a>
												</span>
												<span>

												</span>
											</td>

										</tr>
										<tr class="md-text">
											<td>4</td>
											<td>askes.jpg</td>
											<td>Kartu Askes</td>
											<td>ASKES_75698767564567878675665678765</td>
											<td>
												<i class="mdi-action-done green-text"></i>
											</td>
											<td>
												<span>
													<a href="javascript:void(0)" class="round waves-effect waves-light red white-text" title="Hapus" onClick="hapusdata('<?=$reqRowId?>','','<?=$reqId?>')">
														<i class="mdi-action-delete"></i>
													</a>
												</span>
												<span>
													<a href="javascript:void(0)" class="round waves-effect waves-light purple white-text" title="Log"  onclick="parent.openModal('app/loadUrl/app/informasi_data_log?reqjson=pangkat_riwayat_json/log/<?=$reqRowId?>&reqjudul=Data Pangkat Riwayat Log')">
														<i class="mdi-action-query-builder"></i>
													</a>
												</span>
												<span>
													<a href="javascript:void(0)" class="round waves-effect waves-light blue white-text" title="Ubah" onClick="parent.setload('pegawai_add_pangkat_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>')">
														<i class="mdi-editor-mode-edit"></i>
													</a>
												</span>
											</td>

											<!-- <td>
												<a href="javascript:void(0)" class="green-text"><i class="mdi-action-search search-ico"></i></a>
											</td> -->
										</tr>
									</tbody>
								</table>
							</div>	
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
</body>
</html>
