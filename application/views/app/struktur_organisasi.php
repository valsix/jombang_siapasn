<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('Pangkat');

$reqBreadCrum= $this->input->get("reqBreadCrum");

$reqTahun= date("Y");
$reqBulan= date("m");

$pangkat= new Pangkat();
$pangkat->selectByParams();

$tinggi = 156;
$reqSatuanKerjaNama= "Semua Satuan Kerja"
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<title>STRUKTUR ORGANISASI</title>
<base href="<?=base_url()?>" />
<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico">
<!--<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.datatables.net/rss.xml">-->

<style type="text/css" media="screen">
	@import "lib/media/css/site_jui.css";
	@import "lib/media/css/demo_table_jui.css";
	@import "lib/media/css/themes/base/jquery-ui.css";
</style>

<style type="text/css" class="init">

	div.container {
		max-width: 100%;
		padding-top: 0px;
	}
	
	.select-wrapper{width:700px !important}

</style>
<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="lib/easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="lib/easyui/demo/demo.css">

<script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.easyui.min.js"></script>


<script type="text/javascript" src="lib/easyui/breadcrum.js"></script>
<script type="text/javascript" charset="utf-8">
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


<link rel="stylesheet" type="text/css" href="css/gaya-monitoring.css">

<link href="lib/treeTable2/doc/stylesheets/master.css" rel="stylesheet" type="text/css" />
<link href="lib/treeTable2/src/stylesheets/jquery.treeTable.css" rel="stylesheet" type="text/css" />

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
					<div class="container1">
						<div class="row">
							<div class="col s12 m12 l12">
                            
                            <ol class="breadcrumb right" id="setBreacrum"></ol>
                            
							<h5 class="breadcrumbs-title">STRUKTUR ORGANISASI</h5>
								<ol class="breadcrumbs">
								</ol>
							</div>
						</div>
					</div>
				</div>
				<!--breadcrumbs end-->

				<div class="area-parameter">
                	<div class="kiri">

                    </div>
				</div>

				<!--start container-->
				<div class="container1" style="clear:both;">
					<div class="section">
					<label for="satuankerja">Pilih Perangkat Daerah / Unit Kerja:</label>
					<select id="satuankerja" name="satuankerja">
					<option value="66">Badan Kepegawaian dan Pengembangan Sumber Daya Manusia</option>
					<option value="97">Kecamatan Bandarkedungmulyo</option>
					<option value="98">Kecamatan Bareng</option>
					<option value="99">Kecamatan Diwek</option>
					</select> 
					<input type="submit" value="Proses">

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
	</script>

</body>
</html>