<?
include_once("functions/default.func.php");
include_once("functions/date.func.php");
include_once("functions/string.func.php");

$this->load->model("Menu");

$index_set=0;
$arrMenu="";
$set= new Menu();
//selectByParamsMenu(1, $akses_id, "AKSES_APP_ZIS", " AND A.MENU_PARENT_ID = '".$id_induk."'");
$set->selectByParamsMenu(1, 1, "AKSES_APP_SIMPEG", " AND A.STATUS IS NULL");
//echo $set->query;exit;
while($set->nextRow())
{
	$arrMenu[$index_set]["MENU_ID"]= $set->getField("MENU_ID");
	$arrMenu[$index_set]["MENU_PARENT_ID"]= $set->getField("MENU_PARENT_ID");
	$arrMenu[$index_set]["NAMA"]= $set->getField("NAMA");
	$arrMenu[$index_set]["LINK_FILE"]= $set->getField("LINK_FILE");
	$arrMenu[$index_set]["AKSES"]= $set->getField("AKSES");
	$arrMenu[$index_set]["JUMLAH_MENU"]= $set->getField("JUMLAH_MENU");
	$arrMenu[$index_set]["JUMLAH_DISABLE"]= $set->getField("JUMLAH_DISABLE");
	$index_set++;
}
$tempMenu= $index_set;
unset($set);
//print_r($arrMenu);exit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SIMPEG KABUPATEN JOMBANG</title>
	<base href="<?=base_url()?>" />
    
    <!-- Bootstrap Core CSS -->
    <link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="lib/startbootstrap-sb-admin-2-1.0.7/dist/css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="lib/startbootstrap-sb-admin-2-1.0.7/dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="css/admin.css" type="text/css">
    <link rel="stylesheet" href="css/gaya.css" type="text/css">
    
</head>

<body style="overflow:hidden; background:#009b4c url(images/bg-kiri-popup-pejabat.png) bottom left no-repeat;">
    <div id="wrapper">
    		
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0; background:#dddddd url(images/bg-header-bootstrap.png) top right no-repeat; ">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand area-logo" href="admin"><img src="images/logo.png"> <span>Administrator - BKD Sidoarjo</span></a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
            	<?php /*?><li>
                    <a href="<?=site_url('')?>">
                        <i class="fa fa-th fa-fw"></i>
                    </a>
                </li><?php */?>
                
                <li>
                    <a href="<?=site_url('')?>admin" title="Home">
                        <i class="fa fa-home fa-fw"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="app/logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse" >
                    <ul class="nav" id="side-menu" >
                        <li>
                            <!-- MENU KIRI -->
                            <div id="wrapper-accordion-menu">
                                <?
								function getMenuByParent($id_induk, $arrMenu)
								{
									$arrayKey= '';
									$arrayKey= in_array_column($id_induk, "MENU_PARENT_ID", $arrMenu);
									//print_r($arrayKey);exit;
									if($arrayKey == ''){}
									else
									{
								?>
                                <div class="accordionContent">
                                <?
										for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
										{
											$index_row= $arrayKey[$index_detil];
											$tempMenuId= $arrMenu[$index_row]["MENU_ID"];
											$arrMenu[$index_row]["MENU_PARENT_ID"];
											$tempNama= $arrMenu[$index_row]["NAMA"];
											$tempLinkFile= $arrMenu[$index_row]["LINK_FILE"];
											$arrMenu[$index_row]["AKSES"];
											$tempJumlahMenu= $arrMenu[$index_row]["JUMLAH_MENU"];
											$tempJumlahDisable= $arrMenu[$index_row]["JUMLAH_DISABLE"];
								?>
                                		<div class="accordion-item"><a href="app/loadUrl/app/<?=$tempLinkFile?>?reqStatus=1" target="mainFrame"><?=$tempNama?></a></div>
                                <?
										}
									}
								?>
                                </div>
                                <?
								}
								
								$arrayKey= '';
								$arrayKey= in_array_column("0", "MENU_PARENT_ID", $arrMenu);
								//print_r($arrayKey);exit;
								if($arrayKey == ''){}
								else
								{
									for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
									{
										$index_row= $arrayKey[$index_detil];
										$tempMenuId= $arrMenu[$index_row]["MENU_ID"];
										$arrMenu[$index_row]["MENU_PARENT_ID"];
										$tempNama= $arrMenu[$index_row]["NAMA"];
										$arrMenu[$index_row]["LINK_FILE"];
										$arrMenu[$index_row]["AKSES"];
										$tempJumlahMenu= $arrMenu[$index_row]["JUMLAH_MENU"];
										$tempJumlahDisable= $arrMenu[$index_row]["JUMLAH_DISABLE"];
										
										if($tempJumlahMenu == $tempJumlahDisable){}
										else
										{
                                ?>
                                	<div class="accordionButton"><img src="images/icon-menu.png"><?=$tempNama?></div>
                                <?
										getMenuByParent($tempMenuId, $arrMenu);
										}
									}
								}
                                ?>
                                  
                            </div>
                        
                            
                        </li>
                        
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <!-- PAGE CONTENT BY VALSIX -->
        <div class="konten-utama">
        	<iframe src="<?=site_url('app/loadUrl/app/home')?>" name="mainFrame"></iframe>
        </div>

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/metisMenu/dist/metisMenu.min.js"></script>
  
    <!-- Custom Theme JavaScript -->
    <script src="lib/startbootstrap-sb-admin-2-1.0.7/dist/js/sb-admin-2.js"></script>

	<!-- eModal -->
	<script src="lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal-admin.min.js"></script>
    <style>
	.modal-kk{
		width:200px;
		height:200px !important;
		border:10px solid red;
		overflow:auto;
	}
	</style>
    <script type="text/javascript">
	//		function changeElement('.modal-kk') {
	//			var el = document.getElementsByClassName('.modal-kk');
	//			el.style.color = "red";
	//			el.style.fontSize = "15px";
	//			el.style.backgroundColor = "#f00";
	//		}

	$(document).ready(function(){
		$(this).find(".modal-kk").css({"border": "2px solid red !important"});
		//$(this).find("body").css({'border':'2px solid red !important'});
	});

		function openPopup(page) {
			eModal.iframe(page, 'SIMPEG KABUPATEN JOMBANG')
		}
		
		function openPopupModif(page, judul) {
			eModal.iframe({
			url: page,
			//size:ukuran,
			//size:"width=800,toolbar=1,resizable=1,scrollbars=yes,height=400,top=100,left=100",
			size:eModal.size.kk,
			title:judul
			});
		}
		
		function closePopup(pesan)
		{
			eModal.alert(pesan);		
			setInterval(function(){ document.location.reload(); }, 2000); 	
		}
		
		function closePopupImport()
		{
			eModal.close();
		}
		
		function hapusData(id, statusaktif)
		{
			alert('asdfasdf');
		}
		
		// OK
		//function openPopup(page) {
		//	eModal.iframe(page, 'Aplikasi Presensi - PJB Services')
		//}
		
	</script>
    
    <!-- eModal Full -->
    <script src="lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal-full.min.js"></script>
    <script type="text/javascript">
		function createWindowFull(page) {
			eModalFull.iframe(page, 'SIMPEG KABUPATEN JOMBANG')
		}
		
		window.closeModal = function(){
			$('#iframeModal').modal('hide');
		};
	</script>
    
    <!-- eModal Max Full -->
    <script src="lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal-maxfull.min.js"></script>
    <script type="text/javascript">
		function createWindowMaxFull(page) {
			eModalMaxFull.iframe(page, 'SIMPEG KABUPATEN JOMBANG')
		}
		
		window.closeModal = function(){
			$('#iframeModal').modal('hide');
		};
	</script>
    
	<!-- ACCORDION MENU -->
    <link href="lib/jquery-accordion-menu/style/format.css" rel="stylesheet" type="text/css" />
    <link href="lib/jquery-accordion-menu/style/text.css" rel="stylesheet" type="text/css" />
    <!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"> </script>-->
    <script type="text/javascript" src="lib/jquery-accordion-menu/includes/javascript.js"> </script>
    <script type="text/javascript">
    $(document).ready(function() {
        /********************************************************************************************************************
        CLOSES ALL DIVS ON PAGE LOAD
        ********************************************************************************************************************/
		//$("div#wrapper-accordion-menu").show();
        $("div.accordionContent:first").show();
    });
	
	//if (!localStorage['done']) {
		//localStorage['done'] = 'yes';
		//myFunction();
		//$("div#wrapper-accordion-menu").hide();
	//}
    </script>

</body>

</html>

