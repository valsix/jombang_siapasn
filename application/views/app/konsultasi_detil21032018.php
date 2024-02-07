<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$reqBreadCrum= $this->input->get("reqBreadCrum");
$reqId= $this->input->get("reqId");

$this->load->model('Mailbox');

$set= new Mailbox();
$set->selectByParams(array("A.MAILBOX_ID"=>$reqId), -1,-1);
$set->firstRow();
//echo $set->query;
$tempJudul= $set->getField("SUBYEK");
$tempTanggal= $set->getField("TANGGAL");
$tempIsi= $set->getField("ISI");
$tempStatusInfoId= $set->getField("STATUS_INFO_ID");

$set->selectByParamsDetil(array("A.MAILBOX_ID"=>$reqId), -1,-1, "", "ORDER BY B.MAILBOX_DETIL_ID ASC");

$tinggi = 156;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<title>Diklat</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="msapplication-tap-highlight" content="no">
	<meta name="description" content="Simpeg Jombang">
	<meta name="keywords" content="Simpeg Jombang">
	<title>Simpeg Jombang</title>
	<base href="<?=base_url()?>" />
	<link href="font/google-font.css" rel="stylesheet">

	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
	<script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
	<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

	<!-- AUTO KOMPLIT -->
	<link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
	<script src="lib/autokomplit/jquery-ui.js"></script>
	
	<script type="text/javascript">	
		$(function(){
			$('#ff').form({
				url:'konsultasi_detil_json/add',
				onSubmit:function(){
					return $(this).form('validate');
				},
				success:function(data){
				    //alert(data);return false;
					data = data.split("-");
					rowid= data[0];
					infodata= data[1];
         			//$.messager.alert('Info', infodata, 'info');

         			if(rowid == "xxx")
         			{
         				mbox.alert(infodata, {open_speed: 0});
         			}
         			else
         			{
         				mbox.alert(infodata, {open_speed: 500}, interval = window.setInterval(function() 
         				{
							clearInterval(interval);
         					mbox.close();
         					document.location.href= "app/loadUrl/app/konsultasi";
         				}, 1000));
						$(".mbox > .right-align").css({"display": "none"});
         			}
				}
			});
		});
		
		function setSimpan()
		{
			$("#ff").submit();
			return false;
		}
	</script>
	<link rel="stylesheet" type="text/css" href="css/gaya.css">

	<!-- CORE CSS-->    
	<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
	<link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
	<!-- CSS style Horizontal Nav-->    
	<link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
	<!-- Custome CSS-->    
	<link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

	<!-- <link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/dataTables.materialize.css"> -->
	<?php /*?><link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/dataTables.material.min.css"><?php */?>
    
    <link href="lib/mbox/mbox.css" rel="stylesheet">
  	<script src="lib/mbox/mbox.js"></script>
    <link href="lib/mbox/mbox-modif.css" rel="stylesheet">

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
												<p class="judul-chat"><?=$tempJudul?></p>
											</div>
										</div>

										<div class="list-wrap">
                                        	<?
											$index=1;
											while($set->nextRow())
											{
												
												$tempLogin = $this->USER_LOGIN_ID;
												
												$tempUserLogin= $set->getField("USER_LOGIN_ID");
												
												
													if($tempUserLogin == $tempLogin && $set->getField("ISI_DETIL") !== "")
													{
											?>
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
                                                                <?=$set->getField("ISI_DETIL")?> 
                                                            </p>
                                                        </div>
                                                        <p class="tgl-chat">
                                                            <?=getFormattedDateTime($set->getField("TANGGAL_DETIL"))?> 
                                                        </p>
                                                    </div>
                                                </div>
                                                <!-- End chat masuk -->
                                            <?
													}
													else
													{
											?>
                                            	<!-- Chat keluar -->
                                                <div class="row">
                                                    <div class="col s11 m8 offset-m3 kanan">
                                                        <div class="buble-keluar">
                                                            <p>
                                                                <?=$set->getField("ISI_DETIL")?> 
                                                            </p>
                                                        </div>
                                                        <p class="tgl-chat">
                                                            <?=getFormattedDateTime($set->getField("TANGGAL_DETIL"))?> 
                                                        </p>
                                                    </div>
                                                    <div class="col s1 m1 hide-on-small-only">
                                                        <div class="icon-list orange accent-2">
                                                            <i class="material-icons">mail</i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End chat keluar -->
                                            	
                                            <?
													
												}
											$index+=1;
											}
											?>

										</div>
										<?
										if($tempStatusInfoId == "3")
										{
										?>
										<div class="row form-balas">
											<form id="ff" method="post"  novalidate enctype="multipart/form-data">
												<div class="col s10 m11">
													<div class="form">
														<textarea style="border:none" name="reqRespon"></textarea>
                                                        <input type="hidden" name="reqStatus" value="2">
                                                        <input type="hidden" name="reqId" value="<?=$reqId?>">
                                                        <input type="hidden" name="reqMode" value="insert">
													</div>
												</div>
												<div class="col s2 m1">
													<div class="icon-list orange accent-2">
														<a onClick="setSimpan()"> <i class="material-icons">send</i></a>
													</div>
												</div>
											</form>
										</div>
                                        <?
										}
                                        ?>
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
	<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			$('select').material_select();
		});

		$('.materialize-textarea').trigger('autoresize');
	</script>
</body>
</html>