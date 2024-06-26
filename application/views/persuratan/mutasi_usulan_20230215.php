<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");
$this->load->model('persuratan/JenisPelayanan');

$reqBreadCrum= $this->input->get("reqBreadCrum");
$reqJudulBreadCrum= array_pop( explode(';', $reqBreadCrum) );
$reqJenis= $this->input->get("reqJenis");
$reqJenisNama= setjenisinfo($reqJenis);
$reqSatuanKerjaNama= "Semua Satuan Kerja";

$tempStatusKelompokPegawaiUsul= $this->STATUS_KELOMPOK_PEGAWAI_USUL;
$tempLoginLevel= $this->LOGIN_LEVEL;

$tempStatusMenuKhusus= $this->STATUS_MENU_KHUSUS;
// echo $tempStatusMenuKhusus;exit();

$arrInfoData= array("NIP Lama<br/>NIP Baru", "Nama", "Satuan Kerja Asal", "Satuan Kerja Tujuan", "Jenis Mutasi<br/>Jenis Jabatan / Tugas", "TMT", "Status", "Aksi");

$reqStatusUsulan= "x";
$tinggi = 156;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<title>Diklat</title>
	<base href="<?=base_url()?>" />
	<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico">
	<!--<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.datatables.net/rss.xml">-->

	<style type="text/css" media="screen">
		@import "lib/media/css/site_jui.css";
		@import "lib/media/css/demo_table_jui.css";
		@import "lib/media/css/themes/base/jquery-ui.css";
	</style>

	<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/extensions/Responsive/css/dataTables.responsive.css">
	<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/examples/resources/syntax/shCore.css">
	<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/examples/resources/demo.css">
	<style type="text/css" class="init">

		div.container { max-width: 100%;}
		.select-wrapper{width:10vw !important}

	</style>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/media/js/jquery.js"></script>

    <?php /*?><link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script><?php */?>

	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="lib/easyui/demo/demo.css">

	<script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.min.js"></script>
	<script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.easyui.min.js"></script>

	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/media/js/jquery.dataTables.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/extensions/Responsive/js/dataTables.responsive.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/syntax/shCore.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/demo.js"></script>
    
    <script type="text/javascript" src="lib/easyui/breadcrum.js"></script>
	<script type="text/javascript" charset="utf-8">
		var oTable;
		$(document).ready( function () {
		<?
		if($reqBreadCrum == ""){}
		else
		{
		?>
		setinfobreacrum("<?=$reqBreadCrum?>", "setBreacrum");
		<?
		}
		?>

        var id = -1;//simulation of id
        $(window).resize(function() {
        	console.log($(window).height());
        	$('.dataTables_scrollBody').css('height', ($(window).height() - <?=$tinggi?>));
        });
        oTable = $('#example').dataTable({ bJQueryUI: true,"iDisplayLength": 25,
        	/* UNTUK MENGHIDE KOLOM ID */
        	"aoColumns": [ 
        	<?
        	for($i_data=0; $i_data < count($arrInfoData); $i_data++)
        	{
        		if($i_data == "0"){}
        		else
        		{
        	?>
        		,
        	<?
        		}
        	?>
        	null
        	<?
        	}
        	?>
        	],
        	"lengthMenu": [[10, 25, 500, -1], [10, 25, 500, "All"]],
        	"bSort":true,
        	"bFilter": false,
        	"bLengthChange": false,
        	"bProcessing": true,
        	"bServerSide": true,
        	"sAjaxSource": "surat/mutasi_usulan_json/json?reqStatusUsulan=<?=$reqStatusUsulan?>",
        	"sScrollX": "100%",								  
        	"sScrollXInner": "100%",
        	"sPaginationType": "full_numbers"
        });
        /* Click event handler */

        /* RIGHT CLICK EVENT */
        var anSelectedData = '';
        var anSelectedId = anSelectedUrl= '';
        var anSelectedDownload = '';
        var anSelectedPosition = '';	

        function fnGetSelected( oTableLocal )
        {
        	var aReturn = new Array();
        	var aTrs = oTableLocal.fnGetNodes();
        	for ( var i=0 ; i<aTrs.length ; i++ )
        	{
        		if ( $(aTrs[i]).hasClass('row_selected') )
        		{
        			aReturn.push( aTrs[i] );
        			anSelectedPosition = i;
        		}
        	}
        	return aReturn;
        }

        $("#example tbody").click(function(event) {
        	$(oTable.fnSettings().aoData).each(function (){
        		$(this.nTr).removeClass('row_selected');
        	});
        	$(event.target.parentNode).addClass('row_selected');
					  //
					  var anSelected = fnGetSelected(oTable);													
					  anSelectedData = String(oTable.fnGetData(anSelected[0]));
					  var element = anSelectedData.split(','); 
					  anSelectedUrl = element[element.length-2];
					  anSelectedId = element[element.length-1];
					});
		
		$('#example tbody').on( 'dblclick', 'tr', function () {
			$("#btnEdit").click();
		});
		
		var tempindextab=0;
		
		$('#btnTambah').on('click', function () {
			newWindow = window.open("app/loadUrl/persuratan/mutasi_usulan_add", 'Cetak'+Math.floor(Math.random()*999999));
			newWindow.focus();
			tempindextab= parseInt(tempindextab) + 1;
			
			// tutup flex dropdown => untuk versi mobile
			$('div.flexmenumobile').hide()
			$('div.flexoverlay').css('display', 'none')
		});
		
		$('#btnEdit').on('click', function () {
			if(anSelectedData == "")
			return false;
			newWindow = window.open("app/loadUrl/persuratan/"+anSelectedUrl+"?reqId="+anSelectedId, 'Cetak'+Math.floor(Math.random()*999999));
			newWindow.focus();
			tempindextab= parseInt(tempindextab) + 1;
			
			// tutup flex dropdown => untuk versi mobile
			$('div.flexmenumobile').hide()
			$('div.flexoverlay').css('display', 'none')
		});

		$("#btnCari").on("click", function () {
			var reqSatuanKerjaId= reqCariFilter= reqStatusUsulan= reqJenisMutasiId= "";
			// reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
			reqStatusUsulan= $("#reqStatusUsulan").val();
			reqJenisMutasiId= $("#reqJenisMutasiId").val();

			reqCariFilter= $("#reqCariFilter").val();

			oTable.fnReloadAjax("surat/mutasi_usulan_json/json/?reqJenis=<?=$reqJenis?>&reqStatusUsulan="+reqStatusUsulan+"&reqJenisMutasiId="+reqJenisMutasiId+"&sSearch="+reqCariFilter);
		});
		
		$("#reqStatusUsulan,#reqJenisMutasiId").change(function() { 
			setCariInfo();
		});
		  
		$("#reqCariFilter").keyup(function(e) {
			var code = e.which;
			if(code==13)
			{
				setCariInfo();
			}
		});

		$('#btnDelete').on('click', function () {
        	if(anSelectedData == "")
        		return false;	
        	$.messager.confirm('Konfirmasi',"Hapus data terpilih?",function(r){
        		if (r){
        			$.getJSON("surat/surat_masuk_bkd_json/delete/?reqRowId="+anSelectedId,
        				function(data){
        					$.messager.alert('Info', data.PESAN, 'info');
        					oTable.fnReloadAjax("surat/mutasi_usulan_json/json/?reqJenis=<?=$reqJenis?>");
        				});

        		}
        	});	
        });

        $('#btnLog').on('click', function () {
        	window.parent.openPopup("app/loadUrl/persuratan/surat_masuk_log");

        	// tutup flex dropdown => untuk versi mobile
        	$('div.flexmenumobile').hide()
        	$('div.flexoverlay').css('display', 'none')
        });
		
		$("#clicktoggle").hide();

    });

function hapusdata(id, jenisjabatantugasid)
{
    info= "Apakah yakin untuk data";
	mbox.custom({
	   message: info,
	   options: {close_speed: 100},
	   buttons: [
		   {
			   label: 'Ya',
			   color: 'green darken-2',
			   callback: function() {
				   $.ajax({'url': "surat/mutasi_usulan_json/delete/?reqId="+id+"&reqJenisJabatanTugasid="+jenisjabatantugasid,'success': function(datahtml) {
					setCariInfo();
				   }});
				   mbox.close();
			   }
		   },
		   {
			   label: 'Tidak',
			   color: 'grey darken-2',
			   callback: function() {
				   //console.log('do action for no answer');
				   mbox.close();
			   }
		   }
	   ]
	});
	
}

var tempinfodetilpencarian="0";
function showIconCari()
{	
	if(tempinfodetilpencarian == "0")
	{
		$("#tabpencarian").show();
		tempinfodetilpencarian= 1;
	}
	else
	{
		$("#tabpencarian").hide();
		tempinfodetilpencarian= 0;
	}
}

function setCariInfo()
{
	$(document).ready( function () {
		$("#btnCari").click();			
	});
}
	
function calltreeid(id, nama)
{
	$("#reqLabelSatuanKerjaNama").text(nama);
	$("#reqSatuanKerjaId").val(id);
	setCariInfo();
}

function hapusdata1(id, jenisjabatantugasid)
{
	//alert(id);
	$.messager.confirm('Konfirmasi',"Hapus data terpilih?",function(r){
		if (r){
			setCariInfo();
			// $.getJSON("surat/mutasi_usulan_json/delete/?reqId="+id+"&reqJenisJabatanTugasid="+jenisjabatantugasid,
			// function(data){
			// 	// alert();return false;
			// 	// $.messager.alert('Info', data.PESAN, 'info');
			// 	setCariInfo();
			// });

		}
	});
}
</script>

<link href="css/bluetabs.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/dropdowntabs.js"></script>

<!-- CORE CSS-->    
<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<!-- CSS style Horizontal Nav-->    
<link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
<!-- Custome CSS-->    
<link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/dataTables.materialize.css">
<?php /*?><link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/dataTables.material.min.css"><?php */?>

<link rel="stylesheet" type="text/css" href="css/gaya-monitoring.css">

<link href="lib/treeTable2/doc/stylesheets/master.css" rel="stylesheet" type="text/css" />
<link href="lib/treeTable2/src/stylesheets/jquery.treeTable.css" rel="stylesheet" type="text/css" />

<style>
#example td:nth-child(7), td:nth-child(8) {
    text-align : center;
    *font-weight: bold;
	*color:#F00 !important
}

.option-vw8 {
	/*width: 35vw !important;*/
	/*width: 30% !important;*/

	width: 50% !important;

}

.labelkhusus
{
	top: -20px !important;
}
/*.select-wrapper
{
	width: 200px !important;
}*/

</style>

<link href="lib/mbox/mbox.css" rel="stylesheet">
<script src="lib/mbox/mbox.js"></script>
<link href="lib/mbox/mbox-modif.css" rel="stylesheet">

<!--<link href="css/normalize.css" rel="stylesheet" type="text/css" />-->
</head>
<body>
	<!-- START MAIN -->
	<div id="main">
		<!-- START WRAPPER -->
		<div class="wrapper">
			<!-- START CONTENT -->
			<section id="content-full">

				<!--breadcrumbs start-->
				<div id="breadcrumbs-wrapper">
					<div class="container">
						<div class="row">
							<div class="col s12 m12 l12">
                            
                            <ol class="breadcrumb right" id="setBreacrum"></ol>
                            
							<h5 class="breadcrumbs-title"><?=$reqJudulBreadCrum?></h5>
								<ol class="breadcrumbs">
									<li class="active">
                                    <input type="hidden" id="reqSatuanKerjaId" value="<?=$reqSatuanKerjaId?>" />
                                    <label id="reqLabelSatuanKerjaNama"><?=$reqSatuanKerjaNama?></label>
                                    </li>
								</ol>
							</div>
						</div>
					</div>
				</div>
				<!--breadcrumbs end-->
                
                
                <div id="bluemenu" class="bluetabs">
                    <ul>
                        <li>
                        	<a href="#" id="btnCari" style="display:none" title="Cari">Cari</a>
                            <a id="btnTambah" title="Tambah"><img src="images/icon-edit.png" /> Tambah</a>
                            <a id="btnEdit" title="Lihat Data"><img src="images/icon-edit.png" /> Lihat Data</a>
                        </li>
                    </ul>
                </div>

                <!-- style="background-color: red !important" -->
				<div class="area-parameter">
					<div class="row">
						<div class="col s12 m12">

							<div class="row" style="margin-left: -24px;">

		                        <div class="input-field col s12 m3">
		                        	<select id='reqStatusUsulan' class="option-vw8">
		                        		<option value="">Semua</option>
		                        		<option value="x" <? if($reqStatusUsulan == "x") echo "selected"?> >Proses Usul</option>
		                        		<option value="2" <? if($reqStatusUsulan == "2") echo "selected"?>>Setujui</option>
		                        		<option value="1" <? if($reqStatusUsulan == "1") echo "selected"?>>Di Tolak</option>
		                        	</select>
		                        	<label for="reqStatusUsulan" class="labelkhusus">Status</label>
		                        </div>

		                        <div class="input-field col s12 m5">
		                        	<select id='reqJenisMutasiId' class="option-vw8">
		                        		<option value=''>Semua</option>
		                        		<option value='1'>Mutasi Struktural / Pelaksana</option>
		                        		<option value='2'>Tugas JFT / T. Tambahan / Mutasi Intern Pelaksana</option>
		                        	</select>
		                        	<label for="reqJenisMutasiId" class="labelkhusus">Jenis Mutasi</label>
		                        </div>

				                <div class="input-field col s12 m4" style="float: right;">
				                	<input type="text" id="reqCariFilter" placeholder />
				                	<label for="reqCariFilter">Search</label>
				                	<button id="clicktoggle">Filter ▾</button>
				                </div>

							</div>
<!-- 
							<div class="kanan" style="margin-top: -40px !important;">
								<span>Search :</span>
								<input type="text" id="reqCariFilter" />
								<button id="clicktoggle">Filter ▾</button>
							</div> -->

						</div>
					</div>
				<?
				/*
				?>
					<div class="kiri">
						<span>Show</span>
						<select>
							<option>10</option>
							<option>25</option>
							<option>50</option>
						</select>
						<span>entries</span>
						<?
		                if($tempStatusMenuKhusus == "1")
		                {
		                ?>
		                <span style="padding-left:50px">Status Disposisi</span>
						<select class="option-vw8" id='reqStatusSurat'>
							<option value=''>Semua</option>
							<option value='2'>Belum</option>
							<option value='xx'>Sudah</option>
						</select>

		                <?
		                }
		                else
		                {
		                ?>
						<?
						if($tempStatusKelompokPegawaiUsul == "1" || $tempLoginLevel == 99)
						{
                        ?>
						<span style="padding-left:50px">Jenis Pelayanan</span>
                        <select id="reqJenisPelayananId">
							<option value="">Semua</option>
                            <?
							while($jenis_pelayanan->nextRow())
							{
								$tempId= $jenis_pelayanan->getField("JENIS_PELAYANAN_ID");
								$tempNama= $jenis_pelayanan->getField("NAMA");
                            ?>
                            <option value="<?=$tempId?>" <? if($tempId == $temp) echo "selected"?>><?=$tempNama?></option>
                            <?
							}
                            ?>
						</select>
						<span style="padding-left:5px">Di beri Nomor</span>
						<select id='reqStatusNomor'>
							<option value=''>Semua</option>
							<option value='1'>Sudah</option>
							<option value='2'>Belum</option>
						</select>
						<?
						}
						else
						{
						?>
						<span style="padding-left:50px">Status Surat</span>
						<select class="option-vw8" id='reqStatusSurat'>
							<option value=''>Semua</option>
							<!-- - Belum Diterima -> Surat e belum diklik Terima Berkas
							- Sudah Diterima/Belum Isi Disposisi -> Surat sudah diterima tapi form disposisi kosong
							- Sudah Isi Disposisi -> Surat sudah diterima dan form disposisi sudah diisi tapi belum diterima tujuan
							- Sudah Di Disposisi -> Surat sudah diterima oleh tujuan -->
							<option value='1'>Belum Diterima</option>
							<option value='2'>Sudah Diterima/Belum Isi Disposisi</option>
							<option value='3'>Sudah Isi Disposisi</option>
							<option value='4'>Sudah Di Disposisi</option>
						</select>
						<?
						}
						}
						?>
					</div>

					<div class="kanan">
						<span>Search :</span>
						<input type="text" id="reqCariFilter" />
						<button id="clicktoggle">Filter ▾</button>
					</div>
				<?
				*/
				?>
				</div>

				<div class="area-parameter no-marginbottom">

					<div id="settoggle">
						<div class="row">
							<div class="col s3 m1">Status</div>
							<div class="col s2 m1 select-semicolon">:</div>
							<div class="col s7 m2">
								<select class="option-vw8">
									<option>CPNS/PNS</option>
									<option>Pensiun</option>
								</select>
							</div>

							<div class="col s12">
								<table id="tt" class="easyui-treegrid" style="width:100%; height:250px">
									<thead>
										<tr>
											<th field="NAMA" width="90%">Nama</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>

				</div>

				<!--start container-->
				<div class="container" style="clear:both;">
					<div class="section">
						<table id="example" class="display mdl-data-table dt-responsive" cellspacing="0" width="100%">
							<thead>
								<tr>
									<?
						        	for($i_data=0; $i_data < count($arrInfoData); $i_data++)
						        	{
						        	?>
						        	<th style="text-align:center"><?=$arrInfoData[$i_data]?></th>
						        	<?
						        	}
						        	?>

									<!-- <?
									if($tempStatusKelompokPegawaiUsul == "1" || $tempLoginLevel == 99)
									{
		                            ?>
									<th style="text-align:center">Nomor Agenda</th>
                                    <th style="text-align:center">Tanggal Terima</th>
                                    <th style="text-align:center">Tanggal Surat</th>
                                    <th style="text-align:center">Nomor Surat</th>
                                    <th style="text-align:center">Perihal</th>
                                    <th style="text-align:center">Asal Surat</th>
                                    <th style="text-align:center">Aksi</th>
                                    <?
                                	}
                                	else
                                	{
                                    ?>
                                    <th style="text-align:center">Nomor Agenda</th>
                                    <th style="text-align:center">Tanggal Surat</th>
                                    <th style="text-align:center">Nomor Surat</th>
                                    <th style="text-align:center">Perihal</th>
                                    <th style="text-align:center">Asal Surat</th>
                                    <th style="text-align:center">Posisi Surat</th>
                                    <?
                                	}
                                    ?> -->
								</tr>
							</thead>
						</table>
					</div>
				</div>
				<!--end container-->
			</section>
			<!-- END CONTENT -->
		</div>
		<!-- END WRAPPER -->

	</div>
	<!-- END MAIN -->

	<!--materialize js-->
    <script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			$('select').material_select();
		});

		$('.materialize-textarea').trigger('autoresize');

		<?php /*?>$(function(){
			var tt = $('#tt').treegrid({
				url: 'satuan_kerja_json/treepilih',
				rownumbers: false,
				pagination: false,
				idField: 'ID',
				treeField: 'NAMA',
				onBeforeLoad: function(row,param){
					if (!row) { // load top level rows
					param.id = 0; // set id=0, indicate to load new page rows
					}
				}
			});
		});<?php */?>

		var outer = document.getElementById('settoggle');
		document.getElementById('clicktoggle').addEventListener('click', function(evnt) {
		if (outer.style.maxHeight){
				//alert('a');
				outer.style.maxHeight = null;
				outer.classList.add('settoggle-closed');
			} 
			else {
				//alert('b');
				outer.style.maxHeight = outer.scrollHeight + 'px';
				outer.classList.remove('settoggle-closed');  
			}
		});

		outer.style.maxHeight = outer.scrollHeight + 'px';
		$('#clicktoggle').trigger('click');
	</script>
</body>
</html>