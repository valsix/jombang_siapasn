<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$reqBreadCrum= $this->input->get("reqBreadCrum");

$this->load->model('Mailbox');

$mailbox = new Mailbox();
$mailbox->selectByParams();

$set= new Mailbox();
$set->selectByParams(array("A.MAILBOX_ID"=>$reqId), -1,-1);
$set->firstRow();
//echo $set->query;
$tempJudul= $set->getField("SUBYEK");
$tempStatusInfoId= $set->getField("STATUS_INFO_ID");

$tinggi = 156;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<title>Diklat</title>
	<base href="<?=base_url()?>" />

	<link href="font/google-font.css" rel="stylesheet">

	<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico">
	<!--<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.datatables.net/rss.xml">-->
	<link href="css/admin.css" rel="stylesheet" type="text/css">

	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="lib/easyui/demo/demo.css">

	<script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>
	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>

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

	<!-- <link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/dataTables.materialize.css"> -->
	<?php /*?><link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/dataTables.material.min.css"><?php */?>

	<link rel="stylesheet" type="text/css" href="css/gaya.css">

	<link href="lib/treeTable2/doc/stylesheets/master.css" rel="stylesheet" type="text/css" />
	<link href="lib/treeTable2/src/stylesheets/jquery.treeTable.css" rel="stylesheet" type="text/css" />
	<script>
		function tambahData()
		{
			document.location.href = "app/loadUrl/app/konsultasi_add";
		}
	</script>

</head>
<body style="background: #eaeaea">
	<div id="main">
		<!-- START WRAPPER -->
		<div class="wrapper">
			<!-- START CONTENT -->
			<section id="content-full">

				<div class="card white">
					<div class="card-content black-text" style="padding: 0">
						<div class="header-list red">
							<div class="row">
								<div class="col s12 m10 offset-m1">
									<div class="title-list">
										<div class="col s12 m6">
											<p class="title">MAILBOX</p>
										</div>
										<div class="col s12 m6">
											<div class="right-not-small">

											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="body-list white">
							<div class="row">
								<div class="col s12 m10 offset-m1">
									<div class="content-list white z-depth-2">
										<div class="row">
											<div class="col s12 mgt-2em">
												<p class="judul-chat">Judul chat bla blablba blablbalbbbla blbalb</p>
											</div>
										</div>

										<div class="list-wrap">
											<!-- Chat masuk -->
											<div class="row">
												<div class="col s1 m1 hide-on-small-only">
													<div class="icon-list green">
														<i class="material-icons">mail</i>
													</div>
												</div>
												<div class="col s11 m8">
													<div class="buble-masuk">
														<p>
															blab bla blablabla blblablb balblbaba lblabl 
														</p>
													</div>
													<p class="tgl-chat">
														27 Februari 2016 
													</p>
												</div>
											</div>
											<!-- End chat masuk -->

											<!-- Chat keluar -->
											<div class="row">
												<div class="col s11 m8 offset-m3 kanan">
													<div class="buble-keluar">
														<p>
															blab bla blablabla blblablb balblbaba lblabl 
															blab bla blablabla blblablb balblbaba lblabl 
															blab bla blablabla blblablb balblbaba lblabl 
														</p>
													</div>
													<p class="tgl-chat">
														27 Februari 2016 
													</p>
												</div>
												<div class="col s1 m1 hide-on-small-only">
													<div class="icon-list orange accent-2">
														<i class="material-icons">mail</i>
													</div>
												</div>
											</div>
											<!-- End chat keluar -->

											<!-- Chat masuk -->
											<div class="row">
												<div class="col s1 m1 hide-on-small-only">
													<div class="icon-list green">
														<i class="material-icons">mail</i>
													</div>
												</div>
												<div class="col s11 m8">
													<div class="buble-masuk">
														<p>
															blab bla blablabla blblablb balblbaba lblabl 
															blab bla blablabla blblablb balblbaba lblabl 
															blab bla blablabla blblablb balblbaba lblabl 
															blab bla blablabla blblablb balblbaba lblabl 
															blab bla blablabla blblablb balblbaba lblabl 
														</p>
													</div>
													<p class="tgl-chat">
														27 Februari 2016 
													</p>
												</div>
											</div>
											<!-- End chat masuk -->

										</div>

										<div class="row form-balas">
											<form name="ff">
												<div class="col s10 m11">
													<div class="form">
														<textarea style="border:none" name=""></textarea>
													</div>
												</div>
												<div class="col s2 m1">
													<div class="icon-list orange accent-2">
														<i class="material-icons">send</i>
													</div>
												</div>
											</form>
										</div>
									</div>
								</div>  

								<div class="col s12 m10 offset-m1" style="margin-bottom: 2em">
									<a href="app/loadUrl/app/konsultasi" class="btn orange">Kembali</a>
								</div>

							</div>
						</div>
					</div>
				</div>

			</section>
			<!-- END CONTENT -->
		</div>
		<!-- END WRAPPER -->

	</div>
	<!-- END MAIN -->

	<!--materialize js-->
	<script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script>
	<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			$('select').material_select();
		});

		$('.materialize-textarea').trigger('autoresize');
	</script>
</body>
</html>