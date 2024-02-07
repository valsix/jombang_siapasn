<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$reqBreadCrum= $this->input->get("reqBreadCrum");
$reqId= $this->input->get("reqId");
$reqJenis= $this->input->get("reqJenis");
$reqTipe= $this->input->get("reqTipe");
$reqKategori= $this->input->get("reqKategori");
$reqPegawaiId= $this->input->get("reqPegawaiId");
$reqKategori= $this->input->get("reqKategori");

$reqJenisNama= setjenisinfo($reqJenis);
$reqSatuanKerjaNama= "Semua Satuan Kerja";

if($reqTipe == "")
{
	$json= "surat_masuk_upt_json";
}
else
{
	$json= "surat_masuk_bkd_json";
}

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

    <?php /*?><link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script><?php */?>

	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="lib/easyui/demo/demo.css">

	<script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.min.js"></script>
	<script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.easyui.min.js"></script>

	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/media/js/jquery.dataTables.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/extensions/Responsive/js/dataTables.responsive.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/syntax/shCore.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/demo.js"></script>
    <script type="text/javascript" language="javascript" src="lib/media/js/jquery.dataTables.rowGrouping.js"></script>
    
    <script type="text/javascript" src="lib/easyui/breadcrum.js"></script>
	<script type="text/javascript" charset="utf-8">
		var oTable;
		var arrChecked = [];
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
        oTable = $('#example').dataTable({ bJQueryUI: true,"iDisplayLength": 25,
        	/* UNTUK MENGHIDE KOLOM ID */
        	"aoColumns": [ 
			null,
			null
        	],	
			// columnDefs: [{ className: 'never', targets: [ 0, 2, 5, 8 ] }],
        	"lengthMenu": [[10, 25, 500, -1], [10, 25, 500, "All"]],
        	"bSort":true,
        	"bFilter": false,
        	"bLengthChange": false,
        	"bProcessing": true,
        	"bServerSide": true,
        	"sAjaxSource": "surat/<?=$json?>/json_pilih/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqPegawaiId=<?=$reqPegawaiId?>&reqKategori=<?=$reqKategori?>",
        	"sScrollX": "100%",								  
        	"sScrollXInner": "100%",
        	"sPaginationType": "full_numbers",
			"fnDrawCallback": function( oSettings ) {
			 	setChecked();
			 }
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
					});
		
		$('#example tbody').on( 'dblclick', 'tr', function () {
			$("#btnPilih").click();	
		});

		$('#example').on( 'change', 'input.editor-active', function () {
			  if($(this).prop('checked'))
			  {
				var elementRow= arrChecked.indexOf($(this).val());
				if(elementRow == -1)
				{
					arrChecked.push($(this).val());
				}
			  }
			  else
			  {
				var i = arrChecked.indexOf($(this).val());
				if(i != -1)
					arrChecked.splice(i, 1);
			  }
		  });
		  
		$("#btnCari").on("click", function () {
			var reqSatuanKerjaId= "";
			reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
			oTable.fnReloadAjax("surat/<?=$json?>/json_pilih/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqPegawaiId=<?=$reqPegawaiId?>&reqKategori=<?=$reqKategori?>");
		});
		
		$('#btnPilih').on('click', function () {
			  // alert(anSelectedId+""+anSelectedData);
			  // return false;
			  if(anSelectedId == "")
			  {
				mbox.alert("Pilih salah satu data terlebih dahulu.", {open_speed: 0});
				return false;
			  }
			  
			  mbox.custom({
				   message: "Apakah Anda Yakin, pilih usulan surat ?",
				   options: {close_speed: 100},
				   buttons: [
					   {
						   label: 'Ya',
						   color: 'green darken-2',
						   callback: function() {
							   $.ajax({'url': 'surat/<?=$json?>/add_surat_usulan/?reqJenis=<?=$reqKategori?>&reqJenisId=7&reqPegawaiId=<?=$reqPegawaiId?>&reqId='+anSelectedId,'success': function(data) {
									//$("#reqCheckAll").prop('checked', false);
									anSelectedId= "";
									setCariInfo();
									parent.closeparenttab();
							   }});
							   //console.log('do action for yes answer');
							   mbox.close();
						   }
					   },
					   {
						   label: 'Tidak',
						   color: 'grey darken-2',
						   callback: function() {
							   //console.log('do action for no answer');
							   mbox.close();
						   }
					   }
				   ]
			  });
			  
		  });
			    
		$("#clicktoggle").hide();

    });

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

function setCariInfo()
{
	$(document).ready( function () {
		$("#btnCari").click();			
	});
}

function calltreeid(id, nama)
{
	$("#reqLabelSatuanKerjaNama").text(nama);
	$("#reqSatuanKerjaId").val(id);
	setCariInfo();
}

function getChecked()
{
	var data = "";
	var validasi = 0;
	for(i=0;i<arrChecked.length; i++)
	{
		value= arrChecked[i];
		//arrValue = value.split("-");
		
		if(data == "")
		data = value;//arrValue[0];
		else
		data = data + "," + value;//arrValue[0];
	}
	return data;
}
			
function setChecked()
{
	for(i=0;i<arrChecked.length; i++)
	{
		value= arrChecked[i];
		arrValue = value.split("-");
		
		$("#reqPilihCheck"+value).prop('checked', true);
		//$("#reqPilihCheck"+arrValue[0]).prop('checked', true);
	}
}

function setCheckedAll()
{
	if($("#reqCheckAll").prop('checked')) {
		// do what you need here
		//alert("Checked");
		$('input[id^="reqPilihCheck"]').each(function(){
		var id= $(this).attr('id');
		id= id.replace("reqPilihCheck", "")
		$(this).prop('checked', true);
		//arrChecked.push($(this).val());
		});
	}
	else {
		// do what you need here
		//alert("Unchecked");
		$('input[id^="reqPilihCheck"]').each(function(){
		var id= $(this).attr('id');
		id= id.replace("reqPilihCheck", "")
		$(this).prop('checked', false);
		});
		
		//arrChecked = [];
	}
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

<!--<link href="css/normalize.css" rel="stylesheet" type="text/css" />-->

<style>
table.display td.group{
	font-size: 1.2em;
	font-weight:bold;
}
</style>

<link href="lib/mbox/mbox.css" rel="stylesheet">
<script src="lib/mbox/mbox.js"></script>
<link href="lib/mbox/mbox-modif.css" rel="stylesheet">

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
					<div class="container">
						<div class="row">
							<div class="col s12 m12 l12">
							<h5 class="breadcrumbs-title">Pilih Nomor Usulan</h5>
							</div>
						</div>
					</div>
				</div>
				<!--breadcrumbs end-->
                
                <?php /*?><div id="bluemenu" class="bluetabs">
                    <ul>
                        <li>
                            <a id="btnCari" style="display:none" title="Cari">Cari</a>
                            <a id="btnPilih" title="Pilih">Pilih</a>
                        </li>
                    </ul>
                </div><?php */?>

				<div class="area-parameter" style="margin-top:20px">
					<div class="kiri">
                    	<span>
                        <a id="btnCari" style="display:none" title="Cari">Cari</a>
                        <button id="btnPilih" title="Pilih">Pilih</button>
                        </span>
                        
						<span style="padding-left:5px">Show</span>
						<select>
							<option>10</option>
							<option>25</option>
							<option>50</option>
						</select>
						<span>entries</span>
					</div>

					<div class="kanan">
						<span>Search :</span>
						<input type="text">
					</div>
				</div>

				<!--start container-->
				<div class="container" style="clear:both;">
					<div class="section">
						<table id="example" class="display mdl-data-table dt-responsive" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th style="text-align:center">Nomor Usul</th>
									<th style="text-align:center; width:100px">Tanggal</th>
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