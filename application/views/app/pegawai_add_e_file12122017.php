<?
$this->load->model('JenisDokumen');
$this->load->model('KualitasFile');
$this->load->model('PegawaiFile');

$arrData= array("No", "Nama File", "Jenis/Judul Dokumen", "Status", "Aksi");
$reqId = $this->input->get("reqId");
$reqKualitasFileId= $this->input->get("reqKualitasFileId");
$reqRiwayatTable= $this->input->get("reqRiwayatTable");
$reqRiwayatId= $this->input->get("reqRiwayatId");
$reqRiwayatField= $this->input->get("reqRiwayatField");
$reqJenisDokumen= $reqRiwayatTable.";".$reqRiwayatId.";".$reqRiwayatField;

$lokasi_link_file= "uploads/".$reqId."/";
$ambil_data_file= lihatfiledirektori($lokasi_link_file);
//print_r($ambil_data_file);exit;
//echo pathinfo($ambil_data_file[0], PATHINFO_BASENAME);exit;

$arrPegawaiDokumen="";
$index_data= 0;
$statement= " AND A.PEGAWAI_ID = ".$reqId;
$set_detil= new PegawaiFile();
$set_detil->selectByParams(array(), -1,-1, $statement);
//echo $set_detil->query;exit;
while($set_detil->nextRow())
{
	//NO_URUT, PEGAWAI_ID, RIWAYAT_TABLE, RIWAYAT_FIELD, RIWAYAT_ID, INFO_DATA
	//$arrPegawaiDokumen[$index_data]["ROWID"] = $set_detil->getField("RIWAYAT_TABLE").";".$set_detil->getField("RIWAYAT_ID").";".$set_detil->getField("RIWAYAT_FIELD");
	$arrPegawaiDokumen[$index_data]["PEGAWAI_FILE_ID"] = $set_detil->getField("PEGAWAI_FILE_ID");
	$arrPegawaiDokumen[$index_data]["ROWID"] = $set_detil->getField("PATH");
	$arrPegawaiDokumen[$index_data]["JENIS_DOKUMEN"] = $set_detil->getField("RIWAYAT_TABLE").";".$set_detil->getField("RIWAYAT_ID").";".$set_detil->getField("RIWAYAT_FIELD");
	$arrPegawaiDokumen[$index_data]["FILE_KUALITAS_ID"] = $set_detil->getField("FILE_KUALITAS_ID");
	$arrPegawaiDokumen[$index_data]["FILE_KUALITAS_NAMA"] = $set_detil->getField("FILE_KUALITAS_NAMA");
	$arrPegawaiDokumen[$index_data]["PEGAWAI_ID"] = $set_detil->getField("PEGAWAI_ID");
	$arrPegawaiDokumen[$index_data]["RIWAYAT_TABLE"] = $set_detil->getField("RIWAYAT_TABLE");
	$arrPegawaiDokumen[$index_data]["RIWAYAT_FIELD"] = $set_detil->getField("RIWAYAT_FIELD");
	$arrPegawaiDokumen[$index_data]["RIWAYAT_ID"] = $set_detil->getField("RIWAYAT_ID");
	$arrPegawaiDokumen[$index_data]["INFO_DATA"] = $set_detil->getField("INFO_DATA");
	$arrPegawaiDokumen[$index_data]["INFO_GROUP_DATA"] = $set_detil->getField("INFO_GROUP_DATA");
	$index_data++;
}
unset($set_detil);
$jumlah_pegawai_dokumen= $index_data;
//print_r($arrPegawaiDokumen);exit;

$arrJenisDokumen="";
$index_data= 0;
$statement= " AND A.PEGAWAI_ID = ".$reqId;
$set_detil= new PegawaiFile();
$set_detil->selectByParamsJenisDokumen(array(), -1,-1, $statement);
//echo $set_detil->query;exit;
while($set_detil->nextRow())
{
	//NO_URUT, PEGAWAI_ID, RIWAYAT_TABLE, RIWAYAT_FIELD, RIWAYAT_ID, INFO_DATA
	$arrJenisDokumen[$index_data]["NO_URUT"] = $set_detil->getField("NO_URUT");
	$arrJenisDokumen[$index_data]["PEGAWAI_ID"] = $set_detil->getField("PEGAWAI_ID");
	$arrJenisDokumen[$index_data]["RIWAYAT_TABLE"] = $set_detil->getField("RIWAYAT_TABLE");
	$arrJenisDokumen[$index_data]["RIWAYAT_FIELD"] = $set_detil->getField("RIWAYAT_FIELD");
	$arrJenisDokumen[$index_data]["RIWAYAT_ID"] = $set_detil->getField("RIWAYAT_ID");
	$arrJenisDokumen[$index_data]["INFO_DATA"] = $set_detil->getField("INFO_DATA");
	$arrJenisDokumen[$index_data]["INFO_GROUP_DATA"] = $set_detil->getField("INFO_GROUP_DATA");
	$index_data++;
}
unset($set_detil);
$jumlah_jenis_dokumen= $index_data;

$kualitas= new KualitasFile();
$kualitas->selectByParams(array());

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
	$(function(){
		$("#reqJenisDokumen, #reqKualitasFileId").change(function(){
			setreloaddokumen();
		});
	});
	
	function setreloaddokumen()
	{
		var reqKualitasFileId= reqJenisDokumen= reqRiwayatTable= reqRiwayatField= reqRiwayatId= "";
		reqKualitasFileId= $("#reqKualitasFileId").val();
		reqJenisDokumen= $("#reqJenisDokumen").val();
		//alert(reqJenisDokumen);return false;
		reqJenisDokumen= String(reqJenisDokumen);
	  	reqJenisDokumen= reqJenisDokumen.split(';'); 
		//$tempRiwayatTable.";".$tempRiwayatId.";".$tempRiwayatField.";".$tempRiwayatId;
		reqRiwayatTable= reqJenisDokumen[0];
		if(typeof reqRiwayatTable == "undefined") reqRiwayatTable= "";
		reqRiwayatId= reqJenisDokumen[1];
		if(typeof reqRiwayatId == "undefined") reqRiwayatId= "";
		reqRiwayatField= reqJenisDokumen[2];
		if(typeof reqRiwayatField == "undefined") reqRiwayatField= "";
		
		document.location.href= "app/loadUrl/app/pegawai_add_e_file/?reqId=<?=$reqId?>&reqKualitasFileId="+reqKualitasFileId+"&reqRiwayatTable="+reqRiwayatTable+"&reqRiwayatId="+reqRiwayatId+"&reqRiwayatField="+reqRiwayatField;
	}
	
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
                            	<div class="row">
              						<div class="input-field col s12 m6">
                                    <label for="reqJenisDokumen" class="active">Jenis Dokumen</label>
                                    <select id="reqJenisDokumen">
                                        <option value="">Semua Data</option>
										<?
										for($index_loop=0; $index_loop < $jumlah_jenis_dokumen; $index_loop++)
										{
											$arrJenisDokumen[$index_loop]["NO_URUT"];
											$arrJenisDokumen[$index_loop]["PEGAWAI_ID"];
											$tempRiwayatTable= $arrJenisDokumen[$index_loop]["RIWAYAT_TABLE"];
											$tempRiwayatTableNext= $arrJenisDokumen[$index_loop+1]["RIWAYAT_TABLE"];
											$tempRiwayatField= $arrJenisDokumen[$index_loop]["RIWAYAT_FIELD"];
											$tempRiwayatId= $arrJenisDokumen[$index_loop]["RIWAYAT_ID"];
											$tempInfoData= $arrJenisDokumen[$index_loop]["INFO_DATA"];
											$tempInfoGroupData= $arrJenisDokumen[$index_loop]["INFO_GROUP_DATA"];
											$tempValue= $tempRiwayatTable.";".$tempRiwayatId.";".$tempRiwayatField;
											//$tempValue= $tempRiwayatTable;
											if($tempRiwayatId == "")
											{
										?>
											<option value="<?=$tempValue?>" <? if($reqJenisDokumen == $tempValue) echo "selected"?>><?=$tempInfoData?></option>
										<?
											}
											else
											{
												if($tempKondisiRiwayatTable == $tempRiwayatTable)
												{
										?>
                                        		<option value="<?=$tempValue?>" <? if($reqJenisDokumen == $tempValue) echo "selected"?> style="padding-left: 30px;"><?=$tempInfoData?></option>
                                        <?
													if($tempRiwayatTableNext == $tempRiwayatTable){}
													{
										?>
                                        			</optgroup>
                                        <?
													}
												}
												else
												{
										?>
                                        		<optgroup label="<?=$tempInfoGroupData?>" style="padding-left: 20px;">
                                                <option value="<?=$tempValue?>" <? if($reqJenisDokumen == $tempValue) echo "selected"?> style="padding-left: 30px;"><?=$tempInfoData?></option>
                                        <?
												}
												$tempKondisiRiwayatTable= $tempRiwayatTable;
											}
										}
										?>
                                    </select>
                                    </div>
                                    <div class="input-field col s12 m6">
                                    <label for="reqKualitasFileId" class="active">Kualitas Dokumen</label>
                                    <select id="reqKualitasFileId">
                                        <option value="">Semua data</option>
                                        <?
										while($kualitas->nextRow())
										{
											?>
											<option value="<?=$kualitas->getField('KUALITAS_FILE_ID')?>" <? if($reqKualitasFileId == $kualitas->getField('KUALITAS_FILE_ID')) echo "selected"?>><?=$kualitas->getField('NAMA')?></option>
											<? 
										}
										?>
                                    </select>
                                    </div>
                                </div>
                                
                                <div class="row">
              						<div class="input-field col s12 m12">
                                        <table class="bordered highlight md-text table_list tabel-responsif" id="link-table">
                                            <thead class="teal white-text"> 
                                                <tr>
                                                    <th width="20">No</th>
                                                    <th>Nama File</th>
                                                    <th>Jenis Dokumen</th>
                                                    <th>Kualitas Dokumen</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?
												if($ambil_data_file == "")
												{
												?>
                                                <tr class="md-text">
                                                    <td colspan="6" style="text-align:center">Data tidak ada</td>
                                                </tr>
                                                <?
												}
												else
												{
                                                for($index_file=0; $index_file < count($ambil_data_file); $index_file++)
                                                {
													$reqRowId= "";
                                                    $tempUrlFile= $ambil_data_file[$index_file];
                                                    $tempNamaUrlFile= pathinfo($tempUrlFile, PATHINFO_BASENAME);
													$tempRiwayatTable= $tempInfoGroupData= $tempFileKualitasNama= $tempFileKualitasId= "";
													
													$arrayKey= '';
													$arrayKey= in_array_column($tempUrlFile, "ROWID", $arrPegawaiDokumen);
													//print_r($arrayKey);exit;
													if($arrayKey == ''){}
													else
													{
														$index_row= $arrayKey[0];
														$reqRowId= $arrPegawaiDokumen[$index_row]["PEGAWAI_FILE_ID"];
														$tempInfoGroupData= $arrPegawaiDokumen[$index_row]["INFO_GROUP_DATA"];
														$tempRiwayatTable= $arrPegawaiDokumen[$index_row]["JENIS_DOKUMEN"];
														$tempFileKualitasId= $arrPegawaiDokumen[$index_row]["FILE_KUALITAS_ID"];
														$tempFileKualitasNama= $arrPegawaiDokumen[$index_row]["FILE_KUALITAS_NAMA"];
													}
													
													if($reqKualitasFileId == ""){}
													else
													{
														if($tempFileKualitasId == $reqKualitasFileId){}
														else
														continue;
													}
													
													if($reqRiwayatTable == ""){}
													else
													{
														$tempCheck= $reqRiwayatTable.";".$reqRiwayatId.";".$reqRiwayatField;
														if($tempRiwayatTable == $tempCheck){}
														else
														continue;
													}
													
                                                ?>
                                                <tr class="md-text">
                                                    <td><?=$index_file+1?></td>
                                                    <td><?=$tempNamaUrlFile?></td>
                                                    <td><?=$tempInfoGroupData?></td>
                                                    <td><?=$tempFileKualitasNama?></td>
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
                                                            <a href="javascript:void(0)" class="round waves-effect waves-light blue white-text" title="Ubah" onClick="parent.setload('pegawai_add_e_file_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqUrlFile=<?=$tempUrlFile?>')">
                                                                <i class="mdi-editor-mode-edit"></i>
                                                            </a>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <?
                                                }
												}
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
								</div>	
                            </div>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('select').material_select();
  });

  $('.materialize-textarea').trigger('autoresize');
  
</script>
</body>
</html>