<?
// $this->load->model("Cabang");
// $this->load->model("Peraturan");
// $this->load->model("Pegawai");
// $this->load->model("PegawaiStatusPegawai");
// $this->load->model("Rekapitulasi");

// $cabang = new Cabang();
// $peraturan = new Peraturan();
// $pegawai_kehadiran = new Pegawai();
// $pegawai = new Pegawai();
// $pegawai_total = new Pegawai();
// $pegawai_status_pegawai = new PegawaiStatusPegawai();
// $pegawai_umur = new Pegawai();
// $rekapitulasi_kehadiran_cabang = new Rekapitulasi();

// $cabang->selectByParams(array("A.CABANG_ID" => $this->KODE_CABANG));
// $cabang->firstRow();

// $nama_cabang = $cabang->getField("NAMA");
// $alamat_cabang = $cabang->getField("ALAMAT_CABANG");
// $telepon_cabang = $cabang->getField("TELEPON_CABANG");
// $fax_cabang = $cabang->getField("FAX_CABANG");
// $email_cabang = $cabang->getField("EMAIL_CABANG");

// $peraturan->selectByParams();
// $tempLinkFile = $peraturan->getField("LINK_FILE");

// $statement_total = " AND PEGAWAI_ID IN (SELECT PEGAWAI_ID FROM PEGAWAI_STATUS_PEGAWAI X WHERE X.STATUS_PEGAWAI_ID IN ('2', '3', '4')) ";
// $total_pegawai = $pegawai_total->getCountByParams(array(), $statement_total);
// $total_pegawai = numberToIna($total_pegawai);

// $pegawai_umur->selectByParamsGrafikUmur();
// $jumlah1825 = 0;
// $jumlah2535 = 0;
// $jumlah3545 = 0;
// $jumlah4560 = 0;

// while($pegawai_umur->nextRow())
// {
// 	if($pegawai_umur->getField("KETERANGAN") == "18-25")
// 	{
// 		$jumlah1825 = $pegawai_umur->getField("JUMLAH");
// 	}
// 	elseif($pegawai_umur->getField("KETERANGAN") == "25-35")
// 	{
// 		$jumlah2535 = $pegawai_umur->getField("JUMLAH");
// 	}
// 	elseif($pegawai_umur->getField("KETERANGAN") == "35-45")
// 	{
// 		$jumlah3545 = $pegawai_umur->getField("JUMLAH");
// 	}
// 	else
// 		$jumlah4560 = $pegawai_umur->getField("JUMLAH");
		
// }

// $pegawai_status_pegawai->selectByParamsGrafik();
// $pegawai_status_pegawai->firstRow();

// $statement_jenis_kelamin = " AND PEGAWAI_ID IN (SELECT PEGAWAI_ID FROM PEGAWAI_STATUS_PEGAWAI X WHERE X.STATUS_PEGAWAI_ID IN ('2', '3', '4')) ";
// $pegawai->selectByParamsJenisKelamin(array(), -1, -1, $statement_jenis_kelamin);
// while($pegawai->nextRow())
// {
// 	if($pegawai->getField("JENIS_KELAMIN") == "L")
// 		$total_laki = $pegawai->getField("JUMLAH");
// 	else
// 		$total_perempuan = $pegawai->getField("JUMLAH");
// }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"> 
        <meta charset="utf-8">
        <title>PJB Presensi</title>
    	<base href="<?=base_url()?>">
        <meta name="generator" content="Bootply" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="description" content="Bootstrap In this tutorial, I want to show you how to create a beautiful website template that gives a wonderful look and comfort of your Web applicat" />
		<link href="lib/bootstrap/css/bootstrap.css" rel="stylesheet">
        
        <!--[if lt IE 9]>
          <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link rel="apple-touch-icon" href="/bootstrap/img/apple-touch-icon.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/bootstrap/img/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/bootstrap/img/apple-touch-icon-114x114.png">

        <!-- CSS code from Bootply.com editor -->
        
        <style type="text/css">
            /*!
 * Start Bootstrap - Simple Sidebar HTML Template (http://startbootstrap.com)
 * Code licensed under the Apache License v2.0.
 * For details, see http://www.apache.org/licenses/LICENSE-2.0.
 */

/* Toggle Styles */

#wrapper {
    padding-left: 0;
    -webkit-transition: all 0.5s ease;
    -moz-transition: all 0.5s ease;
    -o-transition: all 0.5s ease;
    transition: all 0.5s ease;
}

#wrapper.toggled {
    padding-left: 250px;
}

#sidebar-wrapper {
    position: fixed;
    left: 250px;
    z-index: 1000;
    overflow-y: auto;
    margin-left: -250px;
    width: 0;
    height: 100%;
    *background: #000;
	*background:rgba(255,255,255,0.5);
	background:url(images/border-sidebar.png) top right no-repeat;
	background-position:249px 60px;
    -webkit-transition: all 0.5s ease;
    -moz-transition: all 0.5s ease;
    -o-transition: all 0.5s ease;
    transition: all 0.5s ease;
}

#wrapper.toggled #sidebar-wrapper {
    width: 250px;
}

#page-content-wrapper {
    padding: 15px 15px 15px 15px;
    width: 100%;
	
	margin-top:60px;
}

#wrapper.toggled #page-content-wrapper {
    position: absolute;
    margin-right: -250px;
}

/* Sidebar Styles */

.sidebar-nav {
    position: absolute;
    top: 0;
    margin: 0;
    padding: 0;
    width: 250px;
    list-style: none;
}

.sidebar-nav li {
    text-indent: 20px;
    line-height: 40px;
}

.sidebar-nav li a {
    display: block;
    color: #999999;
    text-decoration: none;
}

.sidebar-nav li a:hover {
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    text-decoration: none;
}

.sidebar-nav li a:active,
.sidebar-nav li a:focus {
    text-decoration: none;
}

.sidebar-nav > .sidebar-brand {
    height: 65px;
    font-size: 18px;
    line-height: 60px;
}

.sidebar-nav > .sidebar-brand a {
    color: #999999;
}

.sidebar-nav > .sidebar-brand a:hover {
    background: none;
    color: #fff;
}

@media (min-width: 768px) {
    #wrapper {
        padding-left: 250px;
    }

    #wrapper.toggled {
        padding-left: 0;
    }

    #sidebar-wrapper {
        width: 250px;
    }

    #wrapper.toggled #sidebar-wrapper {
        width: 0;
    }

    #page-content-wrapper {
        padding: 20px;
    }

    #wrapper.toggled #page-content-wrapper {
        position: relative;
        margin-right: 0;
    }
}
        </style>
        
<link rel="stylesheet" href="css/gaya.css" type="text/css">
<link rel="stylesheet" href="css/gaya-bootstrap.css" type="text/css">
<link rel="stylesheet" href="css/gaya-navbar-hover.css" type="text/css">

<style>
html{
	height:	100%;
}
</style>

<!-- FONT AWESOME -->
<link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

<!-- DOUGHNUT CHART JS -->
<script src="lib/Chart.js-master/Chart.js">
</script>

<!-- SCROLLING TABLE MASTER -->
<link rel="stylesheet" href="lib/ScrollingTable-master/style.css" />

</head>

<body class="body-pjb">
        
<!--<div id="wrapper" class="toggled" style="height:100%; border:1px solid red;">-->
<div id="wrapper" class="wrapper-corporate">

	<div class="container-fluid area-main">
    	<div class="row pertama">
			<div class="col-md-6">
            	<div class="area-data-pribadi-wrapper korporat">
                    <div class="area-data-pribadi">
                        
                        <div class="area-slide-inner">
                        	    
                            <ul id="demo1">
                                <li><img src="images/slide-1.png" /></li>
                                <li><img src="images/slide-2.png" /></li>
                                <li><img src="images/slide-3.png" /></li>
                                <li><img src="images/slide-4.png" /></li>
                            </ul>
                            
                            <div class="judul-area-vertical">
                                <div class="inner">
                                    <i class="fa fa-user" aria-hidden="true"></i> Data Korporat
                                </div>
                            </div>
                            
                            <div class="area-data-workforce">
                            	
                                <div class="kiri">
                                    <div class="judul">Workforce</div>
                                    <div class="inner">
                                        
                                        <div class="area-grafik-home">
                                            <div id="canvas-holder">
                                                <canvas id="chart-jenis-pegawai" width="165" height="100"></canvas>
                                                <div class="icon-inner-grafik"><i class="fa fa-black-tie" aria-hidden="true"></i></div>
                                                <div class="nama-grafik">Jenis Pegawai</div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                
                                <div class="kanan">
                                	<div class="area-grafik-home">
                            	
                                        <div class="area-total-pegawai">
                                            <div class="title">total pegawai</div>
                                            <div class="angka"><?=$total_pegawai?></div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-lg-4">
                                                
                                                <div id="canvas-holder">
                                                    <canvas id="chart-usia" width="165" height="100"></canvas>
                                                    <div class="icon-inner-grafik"><i class="fa fa-birthday-cake" aria-hidden="true"></i></div>
                                                    <div class="nama-grafik">Usia Rata-rata</div>
                                                </div>                                        
                                                        
                                            </div>
                                            <div class="col-lg-4">
                                                
                                                <div id="canvas-holder">
                                                    <canvas id="chart-jenis-kelamin" width="165" height="100"></canvas>
                                                    <div class="icon-inner-grafik"><i class="fa fa-venus-mars" aria-hidden="true"></i></div>
                                                    <div class="nama-grafik">Jenis Kelamin</div>
                                                </div>
                                                
                                            </div>
                                            
                                        </div>
                                    </div>
                                    
                                </div>
                                
                            </div>                          
                        </div>
                    </div>
                </div>
                
                <div class="area-footer-home">
                    <div class="area-footer-home-inner">
                        <div class="area-peraturan-home">
                            <div class="judul-area"><i class="fa fa-gavel" aria-hidden="true"></i> Peraturan</div>
                            <div class="area-peraturan-home-inner">
                            <?
                                // while($peraturan->nextRow())
                                // {
                                // $peraturan->getField("LINK_FILE");
                                // $peraturan->getField("NAMA")
                            ?>
                                <div class="item">
                                    <a href="uploads/peraturan/" target="_blank">
                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                    </a>
                                </div>
                             <?
                                // }
                             ?>
                            </div>
                        </div>
                        
                        <div class="area-kontak-home">
                            <div class="judul-area"><?=$nama_cabang?></div>
                            <div class="area-kontak-home-inner">
                                <div class="item">
                                    <div class="ket">
                                        <?=$alamat_cabang?>
                                    </div>
                                    <div class="ikon">
                                        <img src="images/icon-gedung.png">
                                    </div>
                                </div>
                                
                                <div class="item">
                                    <div class="ket">
                                        Telp. <? if($telepon_cabang == "") { echo '-'; } else { echo $telepon_cabang; } ?><br>
                                        Fax. <? if($fax_cabang == "") { echo '-'; } else { echo $fax_cabang; } ?><br>
                                        E-Mail. <? if($email_cabang == "") { echo '-'; } else { echo $email_cabang; } ?>
                                    </div>
                                    <div class="ikon">
                                        <img src="images/icon-phone-email.png">
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    
                </div>
                
            </div>
            <div class="col-md-6 home-kanan">
            
            	<div class="area-grafik-bar">
                	
                    <div class="judul-area-vertical" style="z-index:999;">
                    	<div class="inner">
                        	<i class="fa fa-bar-chart" aria-hidden="true"></i> Grafik Kehadiran
                        </div>
                    </div>
                    
                	<div class="area-grafik-bar-inner">
                        <div id="container"></div>
                    </div>
                </div>
                
                <div class="area-notifikasi-wrapper">
                	<div class="area-notifikasi">
                        <div class="judul-area">
                        	<i class="fa fa-file-text" aria-hidden="true"></i> Rekap Kehadiran
                            <div class="area-tanggal">
                            	<input class="easyui-datebox" id="reqTanggal" name="reqTanggal" value="<?=date("d-m-Y")?>">
                            </div>
                        </div>
                                                    
                        <div class="area-kehadiran-home">
                        	<table id="Demo">
                            	<thead>
                                    <tr>
                                        <th class="headerHor">Unit</th>
                                        <th class="headerHor">Jumlah Pegawai</th>
                                        <th class="headerHor">Pegawai yang Hadir</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyRekapKehadiran">
                                <?
								/*
								for($x=0;$x<count($arrPegawaiKehadiran);$x++)
								{
								?>
                                <tr>
                                    <td class="headerVer"><?=$arrPegawaiKehadiran[$x]["NAMA"]?></td>
                                    <td><?=$arrPegawaiKehadiran[$x]["JUMLAH_PEGAWAI"]?></td>
                                    <td><?=$arrPegawaiKehadiran[$x]["HADIR"]?></td>
                                </tr>
                                <?
								}
								*/
								?>
                                </tbody>
                            </table>
                            
                        </div>
                        
                    </div>
                </div>
                
                
                
            </div>
        </div>
        
        <div class="footer-bottom">

            <div class="container-fluid">
        
                <div class="row">
        
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        
                        <div class="copyright">
        
                            © 2017 PT Pembangkitan Jawa-Bali (PJB). All Rights Reserved.
        
                        </div>
        
                    </div>
        
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        
                        <div class="design">
        
                             <!--<a href="#">Franchisee </a> |  <a target="_blank" href="http://www.webenlance.com">Web Design & Development by Webenlance</a>-->
        
                        </div>
        
                    </div>
        
                </div>
        
            </div>
        
        </div>
        
        
    </div>
    
</div>
<!-- /#wrapper -->
        
<script type='text/javascript' src="lib/bootstrap/js/jquery.js"></script>

<script type='text/javascript' src="lib/bootstrap/js/bootstrap.js"></script>

<!-- JavaScript jQuery code from Bootply.com editor  -->

<script type='text/javascript'>

$(document).ready(function() {
	$("#wrapper").toggleClass("toggled");
	$("#menu-toggle").click(function(e) {
		//alert("hhh");
		e.preventDefault();
		$("#wrapper").toggleClass("toggled");
	});
});

</script>


<!-- EASY UI -->
<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

<!-- SKD SLIDER -->
<!--<script src="http://code.jquery.com/jquery.js"></script>-->
<script src="lib/skdslider/src/skdslider.min.js"></script>
<link href="lib/skdslider/src/skdslider.css" rel="stylesheet">
<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('#demo1').skdslider({
				delay:5000, 
				animationSpeed: 2000,
				showNextPrev:false,
				showPlayButton:false,
				autoSlide:true,
				animationType:'fading',
				showNav:false
			});

			jQuery('#responsive').change(function(){
			  $('#responsive_wrapper').width(jQuery(this).val());
			});
			
		});
</script>

<!-- CHART JS DOUGHNUT -->
<script>
                                
var doughnutDataUsia = [
	{
		value: <?=$jumlah1825?>,
		color:"#287e02",
		//highlight: "#FF5A5E",
		label: "Usia 18-25"
	},
	{
		value: <?=$jumlah2535?>,
		color: "#539835",
		//highlight: "#5AD3D1",
		label: "Usia 25-35"
	},
	{
		value: <?=$jumlah3545?>,
		color: "#94bf81",
		//highlight: "#FFC870",
		label: "Usia 35-45"
	},
	{
		value: <?=$jumlah4560?>,
		color: "#e5e8eb",
		//highlight: "#A8B3C5",
		label: "Usia 45-60"
	}

];

var doughnutDataJenisKelamin = [
	{
		value: <?=$total_laki?>,
		color:"#fbe049",
		//highlight: "#00bad3",
		label: "Laki-laki"
	},
	{
		value: <?=$total_perempuan?>,
		color: "#e5e8eb",
		//highlight: "#323a44",
		label: "Perempuan"
	}

];

<?
// $pegawai_status_pegawai->getField("JUMLAH_ORGANIK")
// $pegawai_status_pegawai->getField("JUMLAH_NON_ORGANIK")
?>
var doughnutDataJenisPegawai = [
	{
		value: 0,
		color:"#2556a1",
		//highlight: "#00bad3",
		label: "Organik"
	},
	{
		value: 0,
		color: "#e5e8eb",
		//highlight: "#323a44",
		label: "Tugas Karya"
	}

];

var options = {
	percentageInnerCutout: 80,
	segmentShowStroke: false
};

window.onload = function(){
	// var ctxUsia = document.getElementById("chart-usia").getContext("2d");
	// window.myDoughnut = new Chart(ctxUsia).Doughnut(doughnutDataUsia, options, {responsive : true});
	
	// var ctxJenisKelamin = document.getElementById("chart-jenis-kelamin").getContext("2d");
	// window.myDoughnut = new Chart(ctxJenisKelamin).Doughnut(doughnutDataJenisKelamin, options, {responsive : true});
	
	// var ctxJenisPegawai = document.getElementById("chart-jenis-pegawai").getContext("2d");
	// window.myDoughnut = new Chart(ctxJenisPegawai).Doughnut(doughnutDataJenisPegawai, options, {responsive : true});
	
	// //loadRekapKehadiran($("#reqTanggal").datebox("getValue"));
	// //loadRekapKehadiranGrafik($("#reqTanggal").datebox("getValue"));
	
	// setloadData($("#reqTanggal").datebox("getValue"), 1);
};

function setloadData(tanggal, infoId)
{
	if(infoId == "1")
	{
		//menggantikan loadRekapKehadiran
		$("#tbodyRekapKehadiran").empty();
		$("#tbodyRekapKehadiran").append('<tr><td colspan="3" style="text-align:center">Processing . . . .</td></tr>')
		
		$.ajax({'url': "rekapitulasi_json/ambil_rekap_kehadiran/?reqTanggal="+tanggal,'success': function(data) {
			data= JSON.parse(data);
			$("#tbodyRekapKehadiran").empty();
			$.each(data, function(key, value) {
					var kd_cabang = '<?=$this->KODE_CABANG?>';
					var user_group_id = '<?=$this->USER_GROUP_ID?>';
					var url = '';
					if(user_group_id == '1')
					{
						url = 'onClick="top.openPopup(\'app/loadUrl/main/rekapitulasi_cabang_detil/?reqCabangId='+value.CABANG_ID+'&reqTanggal='+tanggal+'\')" style="cursor:pointer"' ;
					}
					else
					{
						if(kd_cabang == value.CABANG_ID)
							url = 'onClick="top.openPopup(\'app/loadUrl/main/rekapitulasi_cabang_detil/?reqCabangId='+value.CABANG_ID+'&reqTanggal='+tanggal+'\')" style="cursor:pointer"' ;
						else
							url = '';
					}
					
				 $("#tbodyRekapKehadiran").append('<tr><td class="headerVer">'+value.NAMA+'</td><td '+url+'>'+value.JUMLAH_PEGAWAI+'</td><td>'+value.EFEKTIF+'</td></tr>');
			});
			
			setloadData(tanggal, 2);
		}});
	}
	else if(infoId == "2")
	{
		//menggantikan loadRekapKehadiranGrafik
		$.ajax({'url': "rekapitulasi_json/ambil_rekap_kehadiran_grafik/?reqTanggal="+tanggal,'success': function(json) {
			var json= JSON.parse(json);
			tempcategori= json.categori.data;
			jsonSeries= json.result;
			
			var chart = new Highcharts.Chart({
				chart: {
					type: 'column',
					backgroundColor: null,
					renderTo: "container"
				},
				exporting: {
					enabled: false
				},
				title: {
					text: null
				},
				subtitle: {
					text: null
				},
				xAxis: {
					categories: tempcategori
				},
				yAxis: {
					min: 0,
					title: {
						//text: 'Total fruit consumption'
						text: null
					},
					stackLabels: {
						enabled: true,
						style: {
							fontWeight: 'bold',
							color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
						}
					}
				},
				legend: {
					align: 'right',
					x: -30,
					verticalAlign: 'top',
					y: 25,
					floating: true,
					backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
					borderColor: '#CCC',
					borderWidth: 1,
					shadow: false,
					enabled: false
				},
				tooltip: {
					headerFormat: '<b>{point.x}</b><br/>',
					pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
				},
				plotOptions: {
					column: {
						stacking: 'normal',
						dataLabels: {
							enabled: false,
							color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
						}
					},
					series: {
						//stacking: 'normal',
						borderWidth: 0
					}
				}
				,series: jsonSeries
			});
				
		}});
	}
}

$('#reqTanggal').datebox({
		onSelect: function(date){
			setloadData($("#reqTanggal").datebox("getValue"), 1);
		}
});
</script>

<!-- HIGHCHART -->
<script src="lib/highcharts/highcharts.js"></script>
<script src="lib/highcharts/exporting.js"></script>

	<!-- SCROLLING TABLE MASTER -->
	<!--<script src="lib/ScrollingTable-master/jquery.min.js"></script>-->
	<script src="lib/ScrollingTable-master/scrollingtable.js"></script>
	<script>
		$('#Demo').ScrollingTable();
	</script>


    </body>
</html>