<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqBreadCrum= $this->input->get("reqBreadCrum");
$reqJudulBreadCrum= array_pop( explode(';', $reqBreadCrum) );
$reqJenis= $this->input->get("reqJenis");
$reqKategori= $this->input->get("reqKategori");
$reqJenisNama= setjenisinfo($reqJenis);
// echo setjenisusulanpilih($reqJenis);exit;
//$reqSatuanKerjaNama= "Semua Satuan Kerja";

$tempJenis= $reqJenis;
if($reqJenis == "12")
{
	$tempJenis= 7;
	$reqJudul= "Data Usul ".$reqJenisNama." Ke BKN Pusat";
}
else if($reqJenis == "13")
{
	$tempJenis= 10;
	$reqJudul= "Data Usul ".$reqJenisNama." Ke BKN Pusat";
}
else
{
	$reqJudul= "Data Usul ".$reqJenisNama." Ke Kanreg II BKN";
}

// echo $reqJenis."--".setjeniskanregusulan($reqJenis);exit();
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
        	"bFilter": false,
        	"bLengthChange": false,
        	"bProcessing": true,
        	"bServerSide": true,
        	"sAjaxSource": "surat/usulan_surat_json/json_pilih?reqId=<?=$reqId?>&reqJenis=<?=setjeniskanregusulan($reqJenis)?>&reqKategori=<?=$reqKategori?>",
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
			$("#btnEdit").click();	
		});
		
		var tempindextab=0;
        $('#btnAdd').on('click', function () {
        	newWindow = window.open("app/loadUrl/persuratan/usulan_surat_add?reqJenis=<?=$reqJenis?>", 'Cetak'+Math.floor(Math.random()*999999));
        	newWindow.focus();
			tempindextab= parseInt(tempindextab) + 1;
        	//window.parent.createWindowMaxFull("app/loadUrl/app/pegawai_add");

				  // tutup flex dropdown => untuk versi mobile
				  $('div.flexmenumobile').hide()
				  $('div.flexoverlay').css('display', 'none')

				});

        $('#btnEdit').on('click', function () {
        	if(anSelectedData == "")
        		return false;				
        	newWindow = window.open("app/loadUrl/persuratan/usulan_surat_add?reqJenis=<?=$reqJenis?>&reqId="+anSelectedId, 'Cetak'+Math.floor(Math.random()*999999));
        	newWindow.focus();
			tempindextab= parseInt(tempindextab) + 1;

				  // tutup flex dropdown => untuk versi mobile
				  $('div.flexmenumobile').hide()
				  $('div.flexoverlay').css('display', 'none')
				});

		$("#btnCari").on("click", function () {
			var reqSatuanKerjaId= "";
			oTable.fnReloadAjax("surat/usulan_surat_json/json_pilih?reqId=<?=$reqId?>&reqJenis=<?=setjeniskanregusulan($reqJenis)?>&reqKategori=<?=$reqKategori?>");
		});
		  
		$('#btnDelete').on('click', function () {
        	if(anSelectedData == "")
        		return false;	
        	$.messager.confirm('Konfirmasi',"Hapus data terpilih?",function(r){
        		if (r){
        			$.getJSON("surat/usulan_surat_json/delete/?reqRowId="+anSelectedId,
        				function(data){
        					$.messager.alert('Info', data.PESAN, 'info');
        					oTable.fnReloadAjax("surat/usulan_surat_json/json?reqJenis=<?=$reqJenis?>");
        				});

        		}
        	});	
        });

        $('#btnLog').on('click', function () {
        	window.parent.openPopup("app/loadUrl/persuratan/surat_masuk_log");

        	// tutup flex dropdown => untuk versi mobile
        	$('div.flexmenumobile').hide()
        	$('div.flexoverlay').css('display', 'none')
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

function pilihdata(id)
{
	mbox.custom({
		   message: "Apakah Anda Yakin, pilih pegawai ?",
		   options: {close_speed: 100},
		   buttons: [
			   {
				   label: 'Ya',
				   color: 'green darken-2',
				   callback: function() {
					   $.ajax({'url': 'surat/surat_masuk_pegawai_json/add_dinas_bkd/?reqId='+id+'&reqJenis=<?=$reqJenis?>&reqJenisUsulan=<?=$reqJenisUsulan?>&reqPegawaiId=-<?=$reqRowId?>','success': function(data) {
							//$("#reqCheckAll").prop('checked', false);
							//parent.document.location.reload();
							//parent.document.location.href= "app/loadUrl/persuratan/surat_masuk_teknis_add_verifikasi_karpeg?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqRowId=<?=$reqRowId?>";
							parent.mainFrame.location.href= "app/loadUrl/persuratan/<?=setjenisusulanpilih($reqJenis)?>?reqId=<?=$reqId?>&reqJenis=<?=$tempJenis?>&reqRowId=<?=$reqRowId?>";
							parent.closeModal();
							setCariInfo();
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

<link href="lib/mbox/mbox.css" rel="stylesheet">
  <script src="lib/mbox/mbox.js"></script>
  <link href="lib/mbox/mbox-modif.css" rel="stylesheet">
  
<style>
#example td:nth-child(3),td:nth-child(4),td:nth-child(5),td:nth-child(6) {
    text-align : center;
    *font-weight: bold;
	*color:#F00 !important
}
</style>

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
					<div class="container">
						<div class="row">
							<div class="col s12 m12 l12">
                            
                            <ol class="breadcrumb right" id="setBreacrum"></ol>
                            
							<h5 class="breadcrumbs-title"><?=$reqJudul?></h5>
								<ol class="breadcrumbs">
									<li class="active">
                                    <a id="btnCari" style="display:none" title="Cari">Cari</a>
                                    <input type="hidden" id="reqSatuanKerjaId" value="<?=$reqSatuanKerjaId?>" />
                                    <label id="reqLabelSatuanKerjaNama"><?=$reqSatuanKerjaNama?></label>
                                    </li>
								</ol>
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

					<div class="kanan">
						<span>Search :</span>
						<input type="text">
						<button id="clicktoggle">Filter ▾</button>
					</div>
				</div>

				<!--start container-->
				<div class="container" style="clear:both;">
					<div class="section">
						<table id="example" class="display mdl-data-table dt-responsive" cellspacing="0" width="100%">
							<thead>
								<tr>
                                	<th style="text-align:center">ID Sementara</th>
									<th style="text-align:center">Jumlah</th>
                                    <th style="text-align:center">Tanggal di buat</th>
                                    <th style="text-align:center">Aksi</th>
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

		var outer = document.getElementById('settoggle');
		document.getElementById('clicktoggle').addEventListener('click', function(evnt) {
		if (outer.style.maxHeight){
				//alert('a');
				outer.style.maxHeight = null;
				outer.classList.add('settoggle-closed');
			} 
			else {
				//alert('b');
				outer.style.maxHeight = outer.scrollHeight + 'px';
				outer.classList.remove('settoggle-closed');  
			}
		});

		outer.style.maxHeight = outer.scrollHeight + 'px';
		$('#clicktoggle').trigger('click');
	</script>
</body>
</html>