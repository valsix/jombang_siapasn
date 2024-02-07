<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$reqjson= $this->input->get("reqjson");
$reqjudul= $this->input->get("reqjudul");

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

	</style>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/media/js/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/media/js/jquery.dataTables.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/extensions/Responsive/js/dataTables.responsive.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/syntax/shCore.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/demo.js"></script>
	
	<script type="text/javascript" charset="utf-8">
		var oTable;
		$(document).ready( function () {
			
        var id = -1;//simulation of id
        oTable = $('#example').dataTable({ bJQueryUI: true,"iDisplayLength": 25,
        	/* UNTUK MENGHIDE KOLOM ID */
        	"aoColumns": [ 
        	null,												 								 
        	null,												 								 
        	null,												 								 
        	null												 								 
        	],
			"lengthMenu": [[10, 25, 500, -1], [10, 25, 500, "All"]],
        	"bSort":true,
        	"bProcessing": true,
        	"bServerSide": true,
        	"sAjaxSource": "<?=$reqjson?>",
        	"sScrollX": "100%",								  
        	"sScrollXInner": "100%",
        	"sPaginationType": "full_numbers"
        });
    });
	</script>

<!-- CORE CSS-->    
<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<!-- CSS style Horizontal Nav-->    
<link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
<!-- Custome CSS-->    
<link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/dataTables.materialize.css">

<style>
    /** TITIP BRO **/
    .area-parameter{
        float:left;
        width:100%;
        
        margin-left:20px;
        margin-right:20px;
        
        width:calc(100% - 40px);
        line-height:30px;
        margin-bottom:10px;
        
        *background:cyan;
    }
    
    .area-parameter .kiri{
        float:left;
        width:auto;
        *background:cyan;
    }
    .area-parameter .kiri > span,
    .area-parameter .kiri > .select-wrapper{
        float:left;
        margin-right:5px;
    }

    .area-parameter .kiri > .select-wrapper{
        border:1px solid #eee;
        vertical-align:middle;
        width:5vw;
        *background:cyan;
    }

    .select-semicolon
    {
        *background:cyan;
        width:1vw !important;
    }

    .option-vw9
    {
        *background:cyan;
        width:9vw !important;
        padding-top:1vh !important;
    }

    .area-parameter > .row
    {
        margin-bottom:0px !important;
    }

    .area-parameter.no-marginbottom
    {
        margin-bottom:0px !important;
    }

    @media screen and (max-width:767px) {
        .area-parameter .kiri > .select-wrapper{
            border:1px solid #eee;
            vertical-align:middle;
            width:15vw;
            *background:cyan;
        }

        .select-semicolon
        {
            *background:cyan;
            width:10vw !important;
        }

        .option-vw9
        {
            *background:cyan;
            width:100% !important;
            padding-top:1vh !important;
        }
    }
    .area-parameter .kiri > .select-wrapper > input.select-dropdown{
        height:30px !important;
        line-height:30px !important;
        margin-bottom:0px !important;
    }
    .area-parameter .kanan{
        float:right;
        width:auto;
        *background: orange;
    }
    .area-parameter .kanan > span,
    .area-parameter .kanan > input[type=text],
    .area-parameter .kanan > button{

        float:left;
        width:auto;
        margin-left:5px;
    }
    .area-parameter .kanan > input[type=text]{
        border:1px solid #eee;
        height:30px !important;
        line-height:30px !important;
        margin-bottom:0px !important;
    }
    .area-parameter .kanan > button{
        height:32px !important;
        line-height:32px !important;
    }

    #settoggle{
        display:none;
        overflow:hidden;
        transition-property:max-height;
        transition-duration:500ms;
        transition-timing-function:ease-out;
    }
    .settoggle-closed{
        max-height:0;
    }

    .bluetabs{
        margin-bottom: 10px;
    }

</style>

</head>
<body>

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
                                <h5 class="breadcrumbs-title"><?=$reqjudul?></h5>
                            </div>
                        </div>
                    </div>
                </div>
                <!--breadcrumbs end-->

                <div class="area-parameter">
                    <div class="kiri">
                        <span>Show</span>
                        <select>
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select>
                        <span>entries</span>
                    </div>

                    <?php /*?><div class="kanan">
                        <span>Search :</span>
                        <input type="text">
                        <button id="clicktoggle">Filter ▾</button>
                    </div><?php */?>
                </div>

                <!--start container-->
                <div class="container" style="clear:both;">
                    <div class="section">
                        <table id="example" class="display mdl-data-table dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Info Log</th>
                                    <th>Last User</th>
                                    <th>Last Date</th>
                                    <th>Status</th>
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
    </script>

</body>
</html>