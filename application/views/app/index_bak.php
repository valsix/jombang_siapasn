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
    <title>SIMPEG Kabupaten Jombang</title>
    <base href="<?=base_url()?>" />

    <link rel="stylesheet" href="lib/BootSideMenu/css/bootstrap.min.css">
    <link rel="stylesheet" href="lib/BootSideMenu/css/BootSideMenu.css">

    <!--[if lt IE 9]>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv-printshiv.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style type="text/css">
        .user {
            padding: 5px;
            margin-bottom: 5px;
            text-align: center;
        }
    </style>
    
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css">
    
    <style>
	.body-index > .container-fluid{
		border:2px solid orange; height:100%;
		padding-left:0px;
		padding-right:0px;
	}
	.body-index > .container-fluid > .navbar.navbar-default{
		border:2px solid yellow;
		
		-webkit-border-radius: 4px;
		-moz-border-radius: 4px;
		border-radius: 4px;
		
		margin-bottom:0px;
		
	}
	.body-index > .container-fluid > .row{
		border:2px solid red;
		height:calc(100% - 50px);
		margin-left:0px;
		margin-right:0px;
	}
	.body-index > .container-fluid > .row > .col-md-12{
		border:2px solid cyan;
		height:100%;
		padding-left:0px;
		padding-right:0px;
	}
	.body-index > .container-fluid > .row .col-md-12 > .konten-utama{
		border:2px solid green;
		height:100%;
	}
	.body-index > .container-fluid > .row .col-md-12 > .konten-utama > iframe{
		display:block;
		border:none;
		width:100%;
		height:100%;
	}
	
	/** BOOTSTRAP MODIFIED **/
	.navbar-default {
		background-color: #f5d650;
		border-color: green;
	}
	
	/****/
	.body-index{
		border:2px solid blue;
		height:100vh;
		*margin-left:215px !important;
	}
	</style>
    
</head>
<body class="body-index">


<div class="container-fluid">

    <!-- Static navbar -->
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">SIMPEG JOMBANG</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <!--<ul class="nav navbar-nav">
                    <li class="active"><a href="lib/BootSideMenu/index.html"><span class="fa fa-home"></span> Home</a></li>
                    <li><a href="https://github.com/AndreaLombardo/BootSideMenu/tree/master/examples"><span class="fa fa-code"></span> Examples</a></li>
                    <li><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DUNFGKA32BFGE"><span class="fa fa-paypal"></span> Donation</a></li>
                    <li><a href="https://twitter.com/AndreaL0mbardo" target="_blank"><span class="fa fa-twitter"></span> Twitter</a></li>
                    <li><a href="https://github.com/AndreaLombardo/" target="_blank"><span class="fa fa-github"></span> Github</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">More <span
                                class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="https://github.com/AndreaLombardo/BootSideMenu/">Github</a></li>
                            <li><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DUNFGKA32BFGE">Donation</a></li>
                            <li><a href="http://www.lombardoandrea.com">My Site</a></li>
                        </ul>
                    </li>
                </ul>-->
                
                <!--<ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">More <span
                                class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="https://github.com/AndreaLombardo/BootSideMenu/">Github</a></li>
                            <li><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DUNFGKA32BFGE">Donation</a></li>
                            <li><a href="http://www.lombardoandrea.com">My Site</a></li>
                        </ul>
                    </li>
                </ul>-->
                
            </div>
        </div>
    </nav>
	
    <div class="row">
		<div class="col-md-12">
        	<!-- PAGE CONTENT BY VALSIX -->
            <div class="konten-utama">
                <iframe src="<?=site_url('app/loadUrl/app/home')?>" name="mainFrame"></iframe>
            </div>
        </div>
    </div>
</div>

<!--Test -->
<div id="test">
    <div class="area-user">
    	<i class="fa fa-user"></i>
        <!--<img src="lib/BootSideMenu/img/avatar.png" alt="Esempio" class="img-thumbnail"><br>
        <a href="http://www.lombardoandrea.com" target="_blank" class="navbar-link">Andrea Lombardo</a>-->
    </div>

    <div class="list-group">        
    	<!-- MENU KIRI -->
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
            <div class="list-group collapse" id="<?=$id_induk?>">
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
            		<a href="app/loadUrl/app/<?=$tempLinkFile?>?reqStatus=1" target="mainFrame" class="list-group-item"><?=$tempNama?></a>
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
            	<a href="#<?=$tempMenuId?>" class="list-group-item" data-toggle="collapse"><?=$tempNama?></a>
            <?
                    getMenuByParent($tempMenuId, $arrMenu);
                    }
                }
            }
            ?>
    </div>
</div>
<!--/Test -->

<script src="lib/BootSideMenu/js/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="lib/BootSideMenu/js/bootstrap.min.js"></script>
<script src="lib/BootSideMenu/js/BootSideMenu.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#test').BootSideMenu({
            side: "left",
            closeOnClick: false
        });
    });
</script>

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
<!--<link href="lib/jquery-accordion-menu/style/text.css" rel="stylesheet" type="text/css" />-->
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