<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$reqBreadCrum= $this->input->get("reqBreadCrum");

$tinggi = 156;
$reqSatuanKerjaNama= "Semua Satuan Kerja"
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<title>SIAP ASN</title>
	<base href="<?=base_url()?>" />
	<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico">
	<!--<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.datatables.net/rss.xml">-->

	<style type="text/css" media="screen">
		@import "lib/media/css/site_jui.css";
		@import "lib/media/css/demo_table_jui.css";
		@import "lib/media/css/themes/base/jquery-ui.css";

		.area-2{
			background-color: #eaeaea;
			font-size: 10pt;
		  }
		
		  .content-kanan{
			*height: 100vh;
			overflow: auto;
		  }
		
		  .area-1 {
			*background-color: rgba(202, 203, 212, 0.5);
			font-size: 10pt;
			/*padding: 1vw;*/
		  }
		
		  .content-kiri{
			*height: 100vh;
			overflow: auto;
		  }
	</style>

	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/media/js/jquery.js"></script>

	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="lib/easyui/demo/demo.css">

	<script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.min.js"></script>
	<script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.easyui.min.js"></script>

    <script type="text/javascript" src="lib/easyui/breadcrum.js"></script>

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

	<link rel="stylesheet" type="text/css" href="css/gaya.css">
    <link rel="stylesheet" type="text/css" href="css/gaya-monitoring.css">
	<script src="lib/highcharts/highchartsmodul.js"></script>
	<script src="lib/highcharts/drilldown.js"></script>
    <script src="lib/highcharts/exporting.js"></script>
    
    <script>
	$(document).ready( function () {
		setCariInfo();
		$("#btnCari").on("click", function () {
			
			var reqSatuanKerjaId= "";
			reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
			// alert(reqSatuanKerjaId);

			//====================
			var s_url= "statistik_json/table_golongan_ruang?reqSatuanKerjaId="+reqSatuanKerjaId;
			$.ajax({'url': s_url,'success': function(data) {
				if(data == ''){}
				else
				{
					$('#reqGolonganRuangTable').html(data);
				}
			}});
			
			var s_url= "statistik_json/grafik_golongan_ruang?reqSatuanKerjaId="+reqSatuanKerjaId;
			$.ajax({'url': s_url,'success': function(data) {
				if(data == ''){}
				else
				{
					var json= JSON.parse(data);
					//alert(json.result);return false;
					jsonkategori= json.kategori;
					jsonresult= json.result;
					setgrafik('reqGolonganRuangGrafik', "Grafik Pegawai Berdasarkan Golongan Ruang", "Jumlah Pegawai", "Jumlah pegawai", jsonkategori, jsonresult);
				}
			}});
			//====================
			
			//====================
			var s_url= "statistik_json/table_eselon?reqSatuanKerjaId="+reqSatuanKerjaId;
			$.ajax({'url': s_url,'success': function(data) {
				if(data == ''){}
				else
				{
					$('#reqEselonTable').html(data);
				}
			}});
			
			var s_url= "statistik_json/grafik_eselon?reqSatuanKerjaId="+reqSatuanKerjaId;
			$.ajax({'url': s_url,'success': function(data) {
				if(data == ''){}
				else
				{
					var json= JSON.parse(data);
					//alert(json.result);return false;
					jsonkategori= json.kategori;
					jsonresult= json.result;
					setgrafik('reqEselonGrafik', "Grafik Pegawai Berdasarkan Eselon", "Jumlah Pegawai", "Jumlah pegawai", jsonkategori, jsonresult);
				}
			}});
			//====================
			
			//====================
			var s_url= "statistik_json/table_pendidikan?reqSatuanKerjaId="+reqSatuanKerjaId;
			$.ajax({'url': s_url,'success': function(data) {
				if(data == ''){}
				else
				{
					$('#reqPendidikanTable').html(data);
				}
			}});
			
			var s_url= "statistik_json/grafik_pendidikan?reqSatuanKerjaId="+reqSatuanKerjaId;
			$.ajax({'url': s_url,'success': function(data) {
				if(data == ''){}
				else
				{
					var json= JSON.parse(data);
					//alert(json.result);return false;
					jsonkategori= json.kategori;
					jsonresult= json.result;
					setgrafik('reqPendidikanGrafik', "Grafik Pegawai Berdasarkan Pendidikan", "Jumlah Pegawai", "Jumlah pegawai", jsonkategori, jsonresult);
				}
			}});
			//====================
			
			//====================
			var s_url= "statistik_json/table_jenis_kelamin?reqSatuanKerjaId="+reqSatuanKerjaId;
			$.ajax({'url': s_url,'success': function(data) {
				if(data == ''){}
				else
				{
					$('#reqJenisKelaminTable').html(data);
				}
			}});
			
			var s_url= "statistik_json/grafik_jenis_kelamin?reqSatuanKerjaId="+reqSatuanKerjaId;
			$.ajax({'url': s_url,'success': function(data) {
				if(data == ''){}
				else
				{
					var json= JSON.parse(data);
					//alert(json.result);return false;
					jsonkategori= json.kategori;
					jsonresult= json.result;
					setgrafik('reqJenisKelaminGrafik', "Grafik Pegawai Berdasarkan Jenis Kelamin", "Jumlah Pegawai", "Jumlah pegawai", jsonkategori, jsonresult);
				}
			}});
			//====================
			
			//====================
			var s_url= "statistik_json/table_agama?reqSatuanKerjaId="+reqSatuanKerjaId;
			$.ajax({'url': s_url,'success': function(data) {
				if(data == ''){}
				else
				{
					$('#reqAgamaTable').html(data);
				}
			}});
			
			var s_url= "statistik_json/grafik_agama?reqSatuanKerjaId="+reqSatuanKerjaId;
			$.ajax({'url': s_url,'success': function(data) {
				if(data == ''){}
				else
				{
					var json= JSON.parse(data);
					//alert(json.result);return false;
					jsonkategori= json.kategori;
					jsonresult= json.result;
					setgrafik('reqAgamaGrafik', "Grafik Pegawai Berdasarkan Agama", "Jumlah Pegawai", "Jumlah pegawai", jsonkategori, jsonresult);
				}
			}});
			//====================
			
			//====================
			var s_url= "statistik_json/table_golongan_umur?reqSatuanKerjaId="+reqSatuanKerjaId;
			$.ajax({'url': s_url,'success': function(data) {
				if(data == ''){}
				else
				{
					$('#reqGolonganUmurTable').html(data);
				}
			}});
			
			var s_url= "statistik_json/grafik_golongan_umur?reqSatuanKerjaId="+reqSatuanKerjaId;
			$.ajax({'url': s_url,'success': function(data) {
				if(data == ''){}
				else
				{
					var json= JSON.parse(data);
					//alert(json.result);return false;
					jsonkategori= json.kategori;
					jsonresult= json.result;
					setgrafik('reqGolonganUmurGrafik', "Grafik Pegawai Berdasarkan Golongan Umur", "Jumlah Pegawai", "Jumlah pegawai", jsonkategori, jsonresult);
				}
			}});
			//====================
			
			//====================
			var s_url= "statistik_json/table_tipe_pegawai?reqSatuanKerjaId="+reqSatuanKerjaId;
			$.ajax({'url': s_url,'success': function(data) {
				if(data == ''){}
				else
				{
					$('#reqTipePegawaiTable').html(data);
				}
			}});
			
			var s_url= "statistik_json/grafik_tipe_pegawai?reqSatuanKerjaId="+reqSatuanKerjaId;
			$.ajax({'url': s_url,'success': function(data) {
				if(data == ''){}
				else
				{
					var json= JSON.parse(data);
					//alert(json.result);return false;
					jsonkategori= json.kategori;
					jsonresult= json.result;
					setgrafik('reqTipePegawaiGrafik', "Grafik Pegawai Berdasarkan Tipe Pegawai", "Jumlah Pegawai", "Jumlah pegawai", jsonkategori, jsonresult);
				}
			}});
			//====================
			
		});
	});
	
	function setgrafik(target, judul, yaxisinfo, tooltipinfo, kategori, serialjson)
	{
		var chart = new Highcharts.Chart({
			chart: {
				renderTo: target,
				defaultSeriesType: 'column'
			},
			title:{
				text:judul
			},
			xAxis: {
				categories: kategori
			},
			yAxis: {
				title: {
					text: yaxisinfo
				}
			},
			legend: false,
			tooltip: {
				formatter: function() {
					return '<b>'+tooltipinfo+' '+ this.x +'</b>'+': '+ this.y +'<br/>';
				}
			},
			plotOptions: {
				column: {
					stacking: 'normal',
					dataLabels: {
						enabled: true,
						formatter: function() {
							if(this.y == null)
								return '';
							else
								return this.y;
						},
						color: 'black'
					}
				}
			},
			series: serialjson
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
	
	function calltreeid(id, nama)
	{
		$("#reqLabelSatuanKerjaNama").text(nama);
		$("#reqSatuanKerjaId").val(id);
		setCariInfo();
	}
	
	function setCariInfo()
	{
		$(document).ready( function () {
			$("#btnCari").click();			
		});
	}
	</script>
<!--<link href="css/normalize.css" rel="stylesheet" type="text/css" />-->
</head>
<body>
	<!-- START MAIN -->
	<div id="main">
		<!-- START WRAPPER -->
		<div class="wrapper">
			<!-- START CONTENT -->
			<section id="content-full">
				<a href="#" style="display:none" id="btnCari" title="Cari">Cari</a>
				<!--breadcrumbs start-->
				<div id="breadcrumbs-wrapper">
					<div class="container">
						<div class="row">
							<div class="col s12 m12 l12">
                            
                            <ol class="breadcrumb right" id="setBreacrum"></ol>
                            
							<h5 class="breadcrumbs-title">Statistik Pegawai</h5>
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
                
                <div class="area-parameter">
					<div class="kanan">
						<span>Search :</span>
						<input type="text" id="reqCariFilter" />
						<button id="clicktoggle">Filter ▾</button>
					</div>
				</div>

				<div class="area-parameter no-marginbottom">

					<div id="settoggle">
						<div class="row">
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
						<div class="container-fluid">
                        	<div class="row">
                            	
                                <div id="tabs" style="height:100%;">
                                    <ul id="tabs-swipe-demo" class="tabs">
                                        <li class="tab col s3"><a class="active" href="#swipe-1">Golongan Ruang</a></li>
                                        <li class="tab col s3"><a href="#swipe-2">Eselon</a></li>
                                        <li class="tab col s3"><a href="#swipe-3">Pendidikan</a></li>
                                        <li class="tab col s3"><a href="#swipe-4">Jenis Kelamin</a></li>
                                        <li class="tab col s3"><a href="#swipe-5">Agama</a></li>
                                        <li class="tab col s3"><a href="#swipe-6">Golongan Umur</a></li>
                                        <li class="tab col s3"><a href="#swipe-7">Tipe Pegawai</a></li>
                                    </ul>
                                </div>
            					
                                <div id="swipe-1" class="col s12" style="height:auto !important">
                                	<div class="col s12 m3 area-2">
                                        <div class="content-kanan">
                                        	<div id="reqGolonganRuangTable"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col s12 m9 area-1">
                                        <div class="content-kiri">
                                        	<div id="reqGolonganRuangGrafik" style="min-width: 100%; height: 70vh; margin: 0 auto"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="swipe-2" class="col s12" style="height:auto !important">
                                	<div class="col s12 m3 area-2">
                                        <div class="content-kanan">
                                        	<div id="reqEselonTable"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col s12 m9 area-1">
                                        <div class="content-kiri">
                                        	<div id="reqEselonGrafik" style="min-width: 100%; height: 70vh; margin: 0 auto"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="swipe-3" class="col s12" style="height:auto !important">
                                	<div class="col s12 m3 area-2">
                                        <div class="content-kanan">
                                        	<div id="reqPendidikanTable"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col s12 m9 area-1">
                                        <div class="content-kiri">
                                        	<div id="reqPendidikanGrafik" style="min-width: 100%; height: 70vh; margin: 0 auto"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="swipe-4" class="col s12" style="height:auto !important">
                                	<div class="col s12 m3 area-2">
                                        <div class="content-kanan">
                                        	<div id="reqJenisKelaminTable"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col s12 m9 area-1">
                                        <div class="content-kiri">
                                        	<div id="reqJenisKelaminGrafik" style="min-width: 100%; height: 70vh; margin: 0 auto"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="swipe-5" class="col s12" style="height:auto !important">
                                	<div class="col s12 m3 area-2">
                                        <div class="content-kanan">
                                        	<div id="reqAgamaTable"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col s12 m9 area-1">
                                        <div class="content-kiri">
                                        	<div id="reqAgamaGrafik" style="min-width: 100%; height: 70vh; margin: 0 auto"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="swipe-6" class="col s12" style="height:auto !important">
                                	<div class="col s12 m3 area-2">
                                        <div class="content-kanan">
                                        	<div id="reqGolonganUmurTable"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col s12 m9 area-1">
                                        <div class="content-kiri">
                                        	<div id="reqGolonganUmurGrafik" style="min-width: 100%; height: 70vh; margin: 0 auto"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="swipe-7" class="col s12" style="height:auto !important">
                                	<div class="col s12 m3 area-2">
                                        <div class="content-kanan">
                                        	<div id="reqTipePegawaiTable"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col s12 m9 area-1">
                                        <div class="content-kiri">
                                        	<div id="reqTipePegawaiGrafik" style="min-width: 100%; height: 70vh; margin: 0 auto"></div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        
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
			
			$("#tabs").tabs({
			  swipeable: true
			});
	
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