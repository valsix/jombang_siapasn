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

$statement= " AND A.JENIS_PELAYANAN_ID NOT IN (6,8,9,11)";
$jenis_pelayanan= new JenisPelayanan();
$jenis_pelayanan->selectByParams(array(), -1,-1, $statement);

$arrInfoData= [];
// if($tempStatusMenuKhusus == "1")
// {
// 	$arrInfoData= array("Nomor Agenda","Perihal","Asal Surat");
// }
// else
// {
// 	if($tempStatusKelompokPegawaiUsul == "1" || $tempLoginLevel == 99)
// 	{
// 		$arrInfoData= array("Nomor Agenda","Tanggal Terima","Tanggal Surat","Nomor Surat","Perihal","Asal Surat","Aksi");
// 	}
// 	else
// 	{
// 		$arrInfoData= array("Nomor Agenda","Tanggal Surat","Nomor Surat","Perihal","Asal Surat","Posisi Surat");
// 	}	
// }

$arrInfoData= array("Nomor Agenda", "Tanggal Terima", "Tanggal Surat", "Nomor Surat", "Perihal", "Asal Surat");

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
        	"sAjaxSource": "surat/surat_keluar_bkd_json/json_surat_sediaan/?reqJenis=<?=$reqJenis?>",
        	"sScrollX": "100%",								  
        	"sScrollXInner": "100%",
        	"sPaginationType": "full_numbers"
        });
        /* Click event handler */

        /* RIGHT CLICK EVENT */
        var anSelectedData = '';
        var anSelectedId = anSelectedRowId= '';
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
			anSelectedRowId = element[element.length-2];
			anSelectedId = element[element.length-1];
		});
		
		$('#example tbody').on( 'dblclick', 'tr', function () {
			<?
			// if($tempStatusMenuKhusus == "1"){}
			// else
			// {
			?>
			$("#btnEdit").click();
			<?
			// }
			?>
		});
		
		var tempindextab=0;
		
		$('#btnTambah').on('click', function () {
			newWindow = window.open("app/loadUrl/persuratan/surat_masuk_add", 'Cetak'+Math.floor(Math.random()*999999));
			newWindow.focus();
			tempindextab= parseInt(tempindextab) + 1;
			
			// tutup flex dropdown => untuk versi mobile
			$('div.flexmenumobile').hide()
			$('div.flexoverlay').css('display', 'none')
		});
		
		$('#btnEdit').on('click', function () {
			if(anSelectedData == "")
			return false;				
			newWindow = window.open("app/loadUrl/persuratan/surat_masuk_sediaan_add?reqId="+anSelectedId+"&reqRowId="+anSelectedRowId, 'Cetak'+Math.floor(Math.random()*999999));
			newWindow.focus();
			tempindextab= parseInt(tempindextab) + 1;
			
			// tutup flex dropdown => untuk versi mobile
			$('div.flexmenumobile').hide()
			$('div.flexoverlay').css('display', 'none')
		});

		$("#btnCari").on("click", function () {
			var reqSatuanKerjaId= reqCariFilter= reqJenisPelayananId= reqStatusNomor= reqStatusSurat= "";
			reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
			reqCariFilter= $("#reqCariFilter").val();

			<?
            if($tempStatusMenuKhusus == "1")
            {
            ?>
			reqStatusSurat= $("#reqStatusSurat").val();
            <?
            }
            else
            {
            ?>
			<?
			if($tempStatusKelompokPegawaiUsul == "1" || $tempLoginLevel == 99)
			{
	        ?>
			reqJenisPelayananId= $("#reqJenisPelayananId").val();
			reqStatusNomor= $("#reqStatusNomor").val();
			<?
			}
			else
			{
			?>
			reqStatusSurat= $("#reqStatusSurat").val();
			<?
			}
			}
			?>

			oTable.fnReloadAjax("surat/surat_keluar_bkd_json/json_surat_sediaan/?reqJenis=<?=$reqJenis?>&reqJenisPelayananId="+reqJenisPelayananId+"&reqStatusSurat="+reqStatusSurat+"&reqStatusNomor="+reqStatusNomor+"&sSearch="+reqCariFilter);
		});
		
		$("#reqJenisPelayananId,#reqStatusNomor,#reqStatusSurat").change(function() { 
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
        					oTable.fnReloadAjax("surat/surat_keluar_bkd_json/json_surat_sediaan/?reqJenis=<?=$reqJenis?>");
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

function hapussuratdata(id)
{
    info= "Apakah yakin untuk hapus surat";
	mbox.custom({
	   message: info,
	   options: {close_speed: 100},
	   buttons: [
		   {
			   label: 'Ya',
			   color: 'green darken-2',
			   callback: function() {
				   $.ajax({'url': "surat/surat_masuk_bkd_json/deletedata/?reqId="+id,'success': function(datahtml) {
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
#example td:nth-child(7) {
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
                
                <?
                if($tempStatusMenuKhusus == "1")
                {
                ?>
                <a href="#" id="btnCari" style="display:none" title="Cari">Cari</a>
                <!-- <a id="btnEdit" title="Terima Berkas" style="display:none"><img src="images/icon-edit.png" /> Terima Berkas</a> -->
                <?
                }
                else
                {
                ?>
                <div id="bluemenu" class="bluetabs">
                    <ul>
                        <li>
                            <a href="#" id="btnCari" style="display:none" title="Cari">Cari</a>
                            <?
                            if($tempStatusKelompokPegawaiUsul == "1" || $tempLoginLevel == 99)
							{
                            ?>
                            <!-- <a id="btnTambah" title="Tambah"><img src="images/icon-edit.png" /> Tambah</a> -->
                            <?
							}
                            ?>
                            <a id="btnEdit" title="Terima Berkas"><img src="images/icon-edit.png" /> Terima Berkas</a>
                        </li>
                    </ul>
                </div>
                <?
            	}
                ?>
                <!-- style="background-color: red !important" -->
				<div class="area-parameter">
					<div class="row">
						<div class="col s12 m12">

							<div class="row" style="margin-left: -24px;">

								<?
								if($tempStatusMenuKhusus == "1")
								{
								?>

								<div class="input-field col s12 m2" style="display: none;">
									<select id='reqStatusSurat' class="option-vw8">
										<option value=''>Semua</option>
										<option value='2'>Belum</option>
										<option value='xx'>Sudah</option>
									</select>
									<label for="reqStatusSurat" class="labelkhusus">Status Disposisi</label>
								</div>

								<?
				                }
				                else
				                {
				                ?>

					                <?
									if($tempStatusKelompokPegawaiUsul == "1" || $tempLoginLevel == 99)
									{
			                        ?>

			                        <div class="input-field col s12 m2" style="display: none;">
			                        	<select id='reqJenisPelayananId' class="option-vw8">
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
			                        	<label for="reqJenisPelayananId" class="labelkhusus">Jenis Pelayanan</label>
			                        </div>

			                        <div class="input-field col s12 m2" style="display: none;">
			                        	<select id='reqStatusNomor' class="option-vw8">
			                        		<option value=''>Semua</option>
			                        		<option value='1'>Sudah</option>
			                        		<option value='2'>Belum</option>
			                        	</select>
			                        	<label for="reqStatusNomor" class="labelkhusus">Di beri Nomor</label>
			                        </div>

			                        <?
									}
									else
									{
									?>

									<div class="input-field col s12 m2" style="display: none;">
			                        	<select id='reqStatusSurat' class="option-vw8">
			                        		<option value=''>Semua</option>
											<option value='1'>Belum Diterima</option>
											<option value='2'>Sudah Diterima/Belum Isi Disposisi</option>
											<option value='3'>Sudah Isi Disposisi</option>
											<option value='4'>Sudah Di Disposisi</option>
			                        	</select>
			                        	<label for="reqStatusSurat" class="labelkhusus">Status Surat</label>
			                        </div>

									<?
									}
									?>

				                <?
				            	}
				                ?>

				                <div class="input-field col s12 m6" style="float: right;">
				                	<input type="text" id="reqCariFilter" placeholder />
				                	<label for="reqCariFilter">Search</label>
				                	<button id="clicktoggle">Filter ▾</button>
				                </div>

							</div>

						</div>
					</div>
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