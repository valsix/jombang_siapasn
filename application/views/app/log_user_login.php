<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

function getxIpAddress()
{
	/*if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    } else {
        $ip = $_SERVER["REMOTE_ADDR"];
    }*/

    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key)
    {
    	if (array_key_exists($key, $_SERVER) === true)
    	{
    		foreach (array_map('trim', explode(',', $_SERVER[$key])) as $ip)
    		{
    			if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false)
    			{
    				return $ip;
    			}
    		}
    	}
    }
    
    /*$ip .= "HTTP_CF_CONNECTING_IP:".$_SERVER['HTTP_CF_CONNECTING_IP']."<br/>";
    $ip .= "HTTP_X_SUCURI_CLIENTIP:".$_SERVER['HTTP_X_SUCURI_CLIENTIP']."<br/>";
    $ip .= "HTTP_SERVER_VARS-HTTP_X_FORWARDED_FOR:".$HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR']."<br/>";
    $ip .= "HTTP_CLIENT_IP:".$_SERVER['HTTP_CLIENT_IP']."<br/>";
    $ip .= "HTTP_X_FORWARDED_FOR:".$_SERVER['HTTP_X_FORWARDED_FOR']."<br/>";
    $ip .= "HTTP_X_FORWARDED:".$_SERVER['HTTP_X_FORWARDED']."<br/>";
    $ip .= "HTTP_FORWARDED_FOR:".$_SERVER['HTTP_FORWARDED_FOR']."<br/>";
    $ip .= "HTTP_FORWARDED:".$_SERVER['HTTP_FORWARDED']."<br/>";
    $ip .= "REMOTE_ADDR:".$_SERVER['REMOTE_ADDR']."<br/>";

    $ip .= "getenv(HTTP_CLIENT_IP):".getenv('HTTP_CLIENT_IP')."<br/>";
    $ip .= "getenv(HTTP_X_FORWARDED_FOR):".getenv('HTTP_X_FORWARDED_FOR')."<br/>";
    $ip .= "getenv(HTTP_X_FORWARDED):".getenv('HTTP_X_FORWARDED')."<br/>";
    $ip .= "getenv(HTTP_FORWARDED_FOR):".getenv('HTTP_FORWARDED_FOR')."<br/>";
    $ip .= "getenv(HTTP_FORWARDED):".getenv('HTTP_FORWARDED')."<br/>";
    $ip .= "getenv(REMOTE_ADDR):".getenv('REMOTE_ADDR')."<br/>";
    $ip .= "getHostByName(getHostName()):".getHostByName(getHostName())."<br/>";

    return $ip;*/

	// return $_SERVER['REMOTE_ADDR'];
	/*$ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;*/

    /*$ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;*/
}

$reqBreadCrum= $this->input->get("reqBreadCrum");

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
	<link href="css/admin.css" rel="stylesheet" type="text/css">

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

	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="lib/easyui/demo/demo.css">

	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>

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
        oTable = $('#example').dataTable({ bJQueryUI: true,"iDisplayLength": 500,
        	/* UNTUK MENGHIDE KOLOM ID */
        	"aoColumns": [ 
        		null,
        		null,
				null,
				null																		
        	],
        	"bSort":true,
        	"bFilter": false,
        	"bLengthChange": false,
        	"bProcessing": true,
        	"bServerSide": true,
        	"sAjaxSource": "log_user_login_json/json",			  
        	"sScrollY": ($(window).height() - <?=$tinggi?>),
        	"sScrollX": "100%",								  
        	"sScrollXInner": "100%",
        	"sPaginationType": "full_numbers"
        });
        /* Click event handler */

        /* RIGHT CLICK EVENT */
        var anSelectedData = '';
        var anSelectedId = '';
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

        $("#setIconCari").hide();
		
		$('#example tbody').on( 'dblclick', 'tr', function () {
			$("#btnEdit").click();	
		});

        $("#example tbody").click(function(event) {
        	$(oTable.fnSettings().aoData).each(function (){
        		$(this.nTr).removeClass('row_selected');
        	});
        	$(event.target.parentNode).addClass('row_selected');
					  //
					  var anSelected = fnGetSelected(oTable);													
					  anSelectedData = String(oTable.fnGetData(anSelected[0]));
					  var element = anSelectedData.split(','); 
					  anSelectedId = element[element.length-1];
					  console.log('selected :'+ anSelectedId);
					});

        $("#btnCari").on("click", function () {
        	tempJsonParsing= "";
        	var reqCariFilter= "";
			reqCariFilter= $("#reqCariFilter").val();

				  //setJsonFilter();
				  //alert(tempJsonParsing);
				  oTable.fnReloadAjax("log_user_login_json/json?sSearch="+reqCariFilter);
				});

        $("#reqCariFilter").keyup(function(e) {
			var code = e.which;
			if(code==13)
			{
				setCariInfo();
			}
		});

    });

function setCariInfo()
{
	$(document).ready( function () {
		$("#btnCari").click();			
	});
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
								<ol class="breadcrumb right" id="setBreacrum"></ol>
								<h5 class="breadcrumbs-title">Log Login</h5>
							</div>
						</div>
					</div>
				</div>
				<!--breadcrumbs end-->

				<div id="bluemenu" class="bluetabs" style="height: 20px; background: none !important">
					<ul>
						<li>
							<a href="#" id="btnCari" style="display:none" title="Cari">Cari</a>
						</li>
					</ul>
				</div>

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

					<div class="kanan">
						<span>Search :</span>
						<input type="text" id="reqCariFilter" />
						<button id="clicktoggle">Filter ▾</button>
					</div>
				</div>

				<!--start container-->
				<div class="container" style="clear:both;">
					<div class="section">
						<table id="example" class="display mdl-data-table dt-responsive" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Tanggal<?=getxIpAddress()?></th>
									<th>Ip Addres</th>                                       
									<th>Keterangan</th>
									<th>User Login</th>
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
	<script type="text/javascript" src="lib/materializetemplate/js/materialize.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			$('select').material_select();
		});

		$('.materialize-textarea').trigger('autoresize');
	</script>

</body>
</html>