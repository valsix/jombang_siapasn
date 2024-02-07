<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$tinggi = 235;

$arrData= array(
	array("label"=>"Klarifikasi", "width"=>"", "display"=>"")
	, array("label"=>"Status Upload", "width"=>"100", "display"=>"")
	, array("label"=>"Batas Entri", "width"=>"80", "display"=>"")
);
// print_r($arrData);exit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<title>Diklat</title>
<base href="<?=base_url()?>">
<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico">
<link href="css/admin.css" rel="stylesheet" type="text/css">

<style type="text/css" media="screen">
    @import "lib/media/css/site_jui.css";
    @import "lib/media/css/demo_table_jui.css";
    @import "lib/media/css/themes/base/jquery-ui.css";
</style>

<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/jquery.dataTables.css">
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

<script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.easyui.min.js"></script>

<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/Responsive/js/dataTables.responsive.js"></script>
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/media/js/jquery.dataTables.rowGrouping.js"></script>
<!-- <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/FixedColumns/js/dataTables.fixedColumns.js"></script> -->
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/FixedColumns/js/newfixedcolumns.js"></script>
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/TableTools/js/dataTables.tableTools.min.js"></script>	

<script type="text/javascript" charset="utf-8">
	var oTable;
	var json= "klarifikasi_json/settingupload";
	var form= "master_klarifikasi_upload_add";
    $(document).ready( function () {
										
        var id = -1;//simulation of id
		$(window).resize(function() {
		  console.log($(window).height());
		  $('.dataTables_scrollBody').css('height', ($(window).height() - <?=$tinggi?>));
		});
		oTable = $('#example').dataTable({ bJQueryUI: true,"iDisplayLength": 50,
			"aoColumns": [
			<?
			for($col=0; $col<count($arrData); $col++)
			{
				if($col == 0){}
				else
					echo ",";

				if($arrData[$col]["display"] == "1")
					echo "{'bVisible': false}";
				else
					echo "null";
			}
			?>
			],
			"lengthChange": false,
			"bSort":false,
			"bProcessing": true,
			"bServerSide": true,
			// "FixedColumns": true,
			"sAjaxSource": "main/"+json+"/",
			"sScrollY": ($(window).height() - <?=$tinggi?>),
			"scrollX": true,
			"responsive": false,
			"sScrollX": "100%",								  
			"sScrollXInner": "100%",
			"sPaginationType": "full_numbers",
			"fnDrawCallback": function( oSettings ) {
				$('#example_filter input').unbind();
				$('#example_filter input').bind('keyup', function(e) {
					if(e.keyCode == 13) {
						setCariInfo();
					}
				});
			}
		});

		var anSelectedData = '';
		var anSelectedPosition = '';
		var anSelectedId = '';

		function fnGetSelected( oTableLocal )
		{
			var aReturn = new Array();
			var aTrs = oTableLocal.fnGetNodes();
			for ( var i=0 ; i<aTrs.length ; i++ )
			{
				if ( $(aTrs[i]).hasClass('selected') )
				{
					aReturn.push( aTrs[i] );
					anSelectedPosition = i;
				}
			}
			return aReturn;
		}

		$('#example tbody').on( 'click', 'tr', function () {
			$("#example tr").removeClass('selected');
			$(".DTFC_Cloned tr").removeClass("selected");
			var row = $(this);
			var rowIndex = row.index() + 1;

			if (row.parent().parent().hasClass("DTFC_Cloned")) {
				$("#example tr:nth-child(" + rowIndex + ")").addClass("selected");;
			} else {
				$(".DTFC_Cloned tr:nth-child(" + rowIndex + ")").addClass("selected");
			}

			row.addClass("selected");
			var anSelected = fnGetSelected(oTable);	
			anSelectedData = String(oTable.fnGetData(anSelected[0]));

			var element = anSelectedData.split(',');
			anSelectedId = element[element.length-1];
			// console.log(element[0]);
		});

	    $("#btnCari").on("click", function () {
			var reqSatuanKerjaId= reqStatus= reqBulan= reqTahun= reqCariFilter= "";
			// reqCariFilter= $("#reqCariFilter").val();
			reqCariFilter= $('#example_filter input').val();
			// reqStatus= $("#reqStatus").val();
			// reqBulan= $("#reqBulan").val();
			// reqTahun= $("#reqTahun").val();
			reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
			oTable.fnReloadAjax("main/"+json+"/?reqPencarian="+reqCariFilter);
		});

		$("#btnubah").on("click", function () {
			var tempId= $(this).attr('id');
			var tempLihat= "";
			if(tempId == "btnAdd")
			{
				anSelectedId= "";
				tempLihat= 1;
			}
			else if(tempId == "btnubah")
			{
				if(anSelectedId == ""){}
				else
				{
					tempLihat= 1;
				}
			}

			if(tempLihat == 1)
				document.location.href = "app/loadUrl/main/"+form+"?reqId="+anSelectedId;

			$('div.flexmenumobile').hide()
			$('div.flexoverlay').css('display', 'none')

		});

		$("#clicktoggle").hide();
		
	});
	
	function setCariInfo()
	{
		$(document).ready( function () {
		  $("#btnCari").click();      
		});
	}

</script>

<style type="text/css">
	table tr th:nth-child(1){
		text-align: center;
	}
	.row_selected td,
	.row_selected td.sorting_1{
		background-color: #005c99 !important;
		color:  #fff;
	}

	.fg-toolbar.ui-toolbar.ui-widget-header.ui-helper-clearfix.ui-corner-tl.ui-corner-tr .dataTables_filter {
		padding-right: 5px !important;
	}

	.fg-toolbar.ui-toolbar.ui-widget-header.ui-helper-clearfix.ui-corner-tl.ui-corner-tr .dataTables_filter label input[type=search] {
		width: 250px !important;
	}
</style>
<link rel="stylesheet" type="text/css" href="lib/filter/filter.css">

<link href="css/dropdown.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/dropdowntabs.js"></script>

</head>
<body style="overflow:hidden;">
<div id="begron"></div>
<div id="wadah">

    <div class="judul-halaman">
    	<span>Setting Klarifikasi Upload</span>
		<a href="#" id="btnCari" style="display:none" title="Cari">Cari</a>
    </div>

    <div class="area-parameter" style="margin-top: 5px;"></div>

	<div id="parameter-tambahan">
    	<ul>
        	<li><a class="btn btn-warning btn-xs" id="btnubah" title="Lihat"><i class="fa fa-edit"></i> Ubah</a></li>
        </ul>
	</div>

	<div id="rightclickarea">
	    <table cellpadding="0" cellspacing="0" border="0" class="table table-responsive table-bordered table-striped table-hover" id="example">
	    <thead>
	    	<tr>
	    		<?
	        	for($i=0; $i < count($arrData); $i++)
	        	{
	        		$infolabel= $arrData[$i]["label"];
	        		$infowidth= $arrData[$i]["width"];
	        	?>
	        		<th class="th_like" style="<?=$style?>;" width="<?=$infowidth?>px"><?=$infolabel?></th>
	        	<?
	        	}
	        	?>
	        </tr>
	    </thead>
	    </table>
	</div>

</div>

<link rel="stylesheet" href="lib/bootstrap/css/bootstrap.css" type="text/css">
<script src="lib/bootstrap/js/bootstrap.js" type="text/javascript"></script>

</body>
</html>