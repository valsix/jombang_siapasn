<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");
$this->load->library('globalfilepegawai');

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/SuratKeluarBkd');

$reqBreadCrum= $this->input->get("reqBreadCrum");
$reqJudulBreadCrum= array_pop( explode(';', $reqBreadCrum) );
$reqJenis= $this->input->get("reqJenis");
$reqJenisNama= setjenisinfo($reqJenis);
$reqSatuanKerjaNama= "Semua Satuan Kerja";

$tempStatusKelompokPegawaiUsul= $this->STATUS_KELOMPOK_PEGAWAI_USUL;
$tempLoginLevel= $this->LOGIN_LEVEL;

$arrTahun= [];
if($reqJenis == "10")
{
	$index_data= 0;
	$set= new SuratKeluarBkd();
	$statement= " AND A.JENIS_ID = ".$reqJenis;
	$set->selectByParamsUptDinasKpTahun($statement);
	// echo $set->query;exit;
	while($set->nextRow())
	{
		$arrTahun[$index_data]["PENGATURAN_KENAIKAN_PANGKAT_ID"] = $set->getField("PENGATURAN_KENAIKAN_PANGKAT_ID");
		$arrTahun[$index_data]["TANGGAL_PERIODE"] = getFormattedDateJson($set->getField("TANGGAL_PERIODE"));
		$index_data++;
	}

	if($index_data > 0)
	{
		$reqTahun= $arrTahun[0]["PENGATURAN_KENAIKAN_PANGKAT_ID"];
	}
}
else
{
	$reqTahun= date("Y");
	$index_data= 0;
	$set= new SuratKeluarBkd();
	$statement= " AND SMP.JENIS_ID = ".$reqJenis;
	$set->selectByParamsUptDinasTahun($statement);
	// echo $set->query;exit;
	while($set->nextRow())
	{
		$arrTahun[$index_data]["TAHUN"] = $set->getField("TAHUN");
		$index_data++;
	}
}

if($index_data == 0)
{
	$arrTahun[$index_data]["TAHUN"] = $reqTahun;
	$index_data++;
}
$jumlah_tahun= $index_data;
// print_r($arrTahun);exit;

$vfpeg= new globalfilepegawai();
$arrpilihstatussuratmasukpegawai= $vfpeg->pilihstatussuratmasukpegawai();

$tinggi = 156;
$arrData= array("NIP", "Nama", "Unit Kerja", "Status", "Tgl Status");
$json= "json_pegawai";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<title>Diklat</title>
	<base href="<?=base_url()?>" />
	<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico">

	<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/AdminLTE.min.css">
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
        	null
        	<?
            for($i=1; $i < count($arrData); $i++)
            {
            ?>
        	, null
        	<?
        	}
        	?>
        	],
			//"columnDefs": [{ className: 'never', targets: [ 3 ] }],
        	"lengthMenu": [[10, 25, 500, -1], [10, 25, 500, "All"]],
        	"bSort":true,
        	"bFilter": false,
        	"bLengthChange": false,
        	"bProcessing": true,
        	"bServerSide": true,
        	"sAjaxSource": "surat/surat_keluar_bkd_json/<?=$json?>?reqJenis=<?=$reqJenis?>&reqTahun=<?=$reqTahun?>",
        	"sScrollX": "100%",								  
        	"sScrollXInner": "100%",
        	"sPaginationType": "full_numbers",
			"fnDrawCallback": function( oSettings ) {
				setInfoTotal();
			}
        });
        /* Click event handler */

        /* RIGHT CLICK EVENT */
        var anSelectedData = '';
        var anSelectedId = anSelectedJenisId= anSelectedSuratMasukPegawaiId= '';
        var anSelectedDownload = '';
        var anSelectedPosition = '';
        var surat_masuk_pegawai_id= surat_masuk_bkd_id= surat_masuk_upt_id= vlink= vlinkdetil= "";

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
			// anSelectedData = String(oTable.fnGetData(anSelected[0]));
			anSelectedData= oTable.fnGetData(anSelected[0]);
			// console.log(anSelectedData);

			surat_masuk_pegawai_id= anSelectedData[5];
			surat_masuk_bkd_id= anSelectedData[6];
			surat_masuk_upt_id= anSelectedData[7];
			vlink= anSelectedData[8];
			vlinkdetil= anSelectedData[9];
			// console.log(vlinkdetil);

			/*var element = anSelectedData.split(','); 
			anSelectedSuratMasukPegawaiId= element[element.length-3];
			anSelectedJenisId = element[element.length-2];
			anSelectedId = element[element.length-1];*/
		});
		
		$('#example tbody').on( 'dblclick', 'tr', function () {
			$("#btnEdit").click();	
		});
		
		var tempindextab=0;
        $('#btnAdd').on('click', function () {
			newWindow = window.open("app/loadUrl/persuratan/surat_keluar_teknis_add?reqJenis=<?=$reqJenis?>", 'Cetak'+Math.floor(Math.random()*999999));
			newWindow.focus();
			tempindextab= parseInt(tempindextab) + 1;
			//window.parent.createWindowMaxFull("app/loadUrl/app/pegawai_add");

			// tutup flex dropdown => untuk versi mobile
			$('div.flexmenumobile').hide()
			$('div.flexoverlay').css('display', 'none')
		});

        <?
        // if($reqJenis == "3"){}
        // else
		// {
        ?>
        $('#btnEdit').on('click', function () {
        	if(anSelectedData == "")
        		return false;

        	newWindow= window.open("app/loadUrl/persuratan/"+vlink+"&reqDetilId="+vlinkdetil, 'Cetak'+Math.floor(Math.random()*999999));
        	newWindow.focus();
        	tempindextab= parseInt(tempindextab) + 1;

        	$('div.flexmenumobile').hide()
        	$('div.flexoverlay').css('display', 'none')
        });
        <?
    	// }
        ?>

        $("#btnCetak").on("click", function () {
			if(anSelectedData == "")
        		return false;
			
			var requrl= requrllist= "";
			requrl= "ijin_belajar_surat_keluar";
			
		newWindow = window.open("app/loadUrl/persuratan/cetak_pdf?reqCss=surat_pengantar&reqUrl="+requrl+"&reqUrlList="+requrllist+"&reqId="+anSelectedSuratMasukPegawaiId, 'Cetak');
			newWindow.focus();
		});
		
		$("#reqTahun,#reqStatusSuratMasuk").change(function() {
			setCariInfo();
		});

		$("#reqCariFilter").keyup(function(e) {
			var code = e.which;
			if(code==13)
			{
				setCariInfo();
			}
		});
		
		$("#btnCari").on("click", function () {
			var reqSatuanKerjaId= reqTahun= reqStatusSuratMasuk= reqCariFilter= "";
			reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
			reqTahun= $("#reqTahun").val();
			reqStatusSuratMasuk= $("#reqStatusSuratMasuk").val();
			reqCariFilter= $("#reqCariFilter").val();

			oTable.fnReloadAjax("surat/surat_keluar_bkd_json/<?=$json?>?reqJenis=<?=$reqJenis?>&reqSatuanKerjaId="+reqSatuanKerjaId+"&reqTahun="+reqTahun+"&reqStatusSuratMasuk="+reqStatusSuratMasuk+"&sSearch="+reqCariFilter);
		});
		  
		$('#btnDelete').on('click', function () {
        	if(anSelectedData == "")
        		return false;	
        	$.messager.confirm('Konfirmasi',"Hapus data terpilih?",function(r){
        		if (r){
        			$.getJSON("surat/surat_keluar_bkd_json/delete/?reqRowId="+anSelectedId,
        				function(data){
        					$.messager.alert('Info', data.PESAN, 'info');
							setCariInfo();
        					// oTable.fnReloadAjax("surat/surat_keluar_bkd_json/<?=$json?>?reqJenis=<?=$reqJenis?>");
        				});

        		}
        	});	
        });

        $('#btnLog').on('click', function () {
        	window.parent.openPopup("app/loadUrl/persuratan/surat_keluar_log");

        	// tutup flex dropdown => untuk versi mobile
        	$('div.flexmenumobile').hide()
        	$('div.flexoverlay').css('display', 'none')
        });
		
    });

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

function hapusdata(id)
{
    info= "Apakah yakin untuk membatalkan nomor usul surat";
	$.messager.confirm('Konfirmasi', info,function(r){
		if (r){
			$.ajax({'url': "surat/surat_masuk_pegawai_check_json/status_surat_keluar/?reqRowId="+id+"&reqStatusSuratKeluar=2",'success': function(datahtml) {
				setCariInfo();
			}});
		}
	});
}

function setInfoTotal()
{
	var reqJenisKgb= reqSatuanKerjaId= reqCariFilter= reqStatusKgb= reqPangkatId= reqBulan= reqTahun= reqStatusSuratMasuk= "";
	reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
	reqCariFilter= $("#reqCariFilter").val();
	reqStatusKgb= $("#reqStatusKgb").val();
	reqJenisKgb= $("#reqJenisKgb").val();
	//reqPangkatId= $("#reqPangkatId").val();
	reqBulan= $("#reqBulan").val();
	reqTahun= $("#reqTahun").val();
	reqStatusSuratMasuk= $("#reqStatusSuratMasuk").val();

	$.ajax({'url': "surat/surat_keluar_bkd_json/getinfototal/?reqJenis=<?=$reqJenis?>&reqSatuanKerjaId="+reqSatuanKerjaId+"&reqTahun="+reqTahun+"&reqStatusSuratMasuk="+reqStatusSuratMasuk,'success': function(dataJson) {
		var data= JSON.parse(dataJson);
		infojumlahturunstatus= data.infojumlahturunstatus;
		infojumlahkirim= data.infojumlahkirim;
		infojumlahterima= data.infojumlahterima;
		infojumlahterverifikasi= data.infojumlahterverifikasi;
		
		$("#infojumlahturunstatus").text(infojumlahturunstatus);
		$("#infojumlahkirim").text(infojumlahkirim);
		$("#infojumlahterima").text(infojumlahterima);
		$("#infojumlahterverifikasi").text(infojumlahterverifikasi);
	}});
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
#example td:nth-child(3),td:nth-child(4),td:nth-child(5),td:nth-child(6) {
    text-align : center;
    *font-weight: bold;
	*color:#F00 !important
}
</style>

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

				<div class="container" style="clear:none; padding-top: 0px !important;">
                    <div class="row">
                    	<div class="col s12 m3">
                          <div class="info-box">
                            <span class="info-box-icon bg-red"><i class="fa fa-star-o"></i></span>
                
                            <div class="info-box-content">
                              <span class="info-box-text">TURUN STATUS</span>
                              <span class="info-box-number"><label id="infojumlahturunstatus"></label></span>
                            </div>

                          </div>
                        </div>
                        
                        <div class="col s12 m3">
                          <div class="info-box">
                            <span class="info-box-icon bg-yellow"><i class="fa fa-files-o"></i></span>
                            <div class="info-box-content">
                              <span class="info-box-text">KIRIM</span>
                              <span class="info-box-number"><label id="infojumlahkirim"></label></span>
                            </div>
                          </div>
                        </div>

                        <div class="col s12 m3">
                          <div class="info-box">
                            <span class="info-box-icon bg-aqua"><i class="fa fa-envelope-o"></i></span>
                            <div class="info-box-content">
                              <span class="info-box-text">DI TERIMA BKPSDM</span>
                              <span class="info-box-number"><label id="infojumlahterima"></label></span>
                            </div>
                          </div>
                        </div>
            
                        <div class="col s12 m3">
                          <div class="info-box">
                            <span class="info-box-icon bg-green"><i class="fa fa-flag-o"></i></span>
                            <div class="info-box-content">
                              <span class="info-box-text">TERVERIFIKASI</span>
                              <span class="info-box-number"><label id="infojumlahterverifikasi"></label></span>
                            </div>
                          </div>
                        </div>
					</div>
                </div>
                
                <div id="bluemenu" class="bluetabs">
                    <ul>
                        <li>
                            <a href="#" id="btnCari" style="display:none" title="Cari">Cari</a>
                            <a id="btnEdit" title="Lihat Detil"><img src="images/icon-edit.png" /> Lihat Detil</a>
                        </li>
                    </ul>
                </div>

				<div class="area-parameter">
					<div class="kiri">
                        <span style="padding-left:5px">Tahun</span>
                        <select id="reqTahun" style="width: 200px">
							<option value="">Semua</option>
                            <?
							for($index=0; $index < $jumlah_tahun; $index++)
							{
								if($reqJenis == "10")
								{
									$infoid= $arrTahun[$index]["PENGATURAN_KENAIKAN_PANGKAT_ID"];
									$infoval= $arrTahun[$index]["TANGGAL_PERIODE"];
								}
								else
								{
									$infoid= $infoval= $arrTahun[$index]["TAHUN"];
								}
                            ?>
                            <option value="<?=$infoid?>" <? if($reqTahun == $infoid) echo "selected"?>><?=$infoval?></option>
                            <?
							}
                            ?>
						</select>
						<span style="padding-left:5px">Status</span>
						<select id="reqStatusSuratMasuk">
							<?
							foreach ($arrpilihstatussuratmasukpegawai as $key => $value)
							{
	                            $optionid= $value["id"];
	                            $optiontext= $value["nama"];
                          	?>
                            	<option value="<?=$optionid?>"><?=$optiontext?></option>
                            <?
                            }
                            ?>
                        </select>
					</div>

					<div class="kanan">
						<span>Search :</span>
						<input type="text" id="reqCariFilter" />
						<button id="clicktoggle">Filter ▾</button>
					</div>
				</div>

				<div class="area-parameter no-marginbottom">

					<div id="settoggle">
						<div class="row">
							<div class="col s3 m1">Status</div>
							<div class="col s2 m1 select-semicolon">:</div>
							<div class="col s7 m2">
								<select class="option-vw9">
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
				<div class="container" style="clear:none; padding-top: 0px !important;">
					<div class="section">
						<table id="example" class="display mdl-data-table dt-responsive" cellspacing="0" width="100%">
							<thead>
								<tr>
									<?
									for($i=0; $i < count($arrData); $i++)
									{
										$width= "10";
										/*if($i == 0)
											$width= "100";
										elseif($i == 1)
											$width= "250";*/
									?>
										<th style="text-align:center" width="<?=$width?>px"><?=$arrData[$i]?></th>
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

    <style type="text/css">
      .area-parameter .kiri > .select-wrapper
      {
      	<?
		if($reqJenis == "10")
		{
		?>
			width: 10vw;
		<?
		}
		?>
      }

	  .select-dropdown {
	  	width: 200px !important;
	    max-height:250px !important; overflow:auto !important;
	  }
	</style>

	<script type="text/javascript">
		$(document).ready(function() {
			$('select').material_select();
		});

		$('.materialize-textarea').trigger('autoresize');

		$(function(){
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
		});

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