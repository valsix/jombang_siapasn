<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$tempLoginLevel= $this->LOGIN_LEVEL;
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "1201";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

$reqBreadCrum= $this->input->get("reqBreadCrum");

$reqTampilReset= $this->TAMPIL_RESET;
// print_r($reqTampilReset);exit;

$tinggi = 156;
// $tinggi = 580;
$reqSatuanKerjaNama= "Semua Satuan Kerja"
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<title>Pegawai</title>
	<base href="<?=base_url()?>" />
	<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico">
	<!--<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.datatables.net/rss.xml">-->

	<style type="text/css" media="screen">
		@import "lib/media/css/site_jui.css";
		@import "lib/media/css/demo_table_jui.css";
		@import "lib/media/css/themes/base/jquery-ui.css";

		.hukumanstyle { background-color:#FC7370; }
		.hukumanpernahstyle { background-color:#F9C; }
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
        oTable = $('#example').dataTable({ bJQueryUI: true,"iDisplayLength": 25,
        	/* UNTUK MENGHIDE KOLOM ID */
        	"aoColumns": [ 
        	// { data: null, width: '20px'},
        	{sWidth: '60px', className: 'all'},// null,
        	{sWidth: '100px', className: 'all'},// null,
        	{sWidth: '100px', className: 'all'},
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
        	"sAjaxSource": "pegawai_json/json?reqStatusPegawaiId=126",
        	"sScrollX": "100%",								  
        	"sScrollXInner": "100%",
        	"sPaginationType": "full_numbers",
        	"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
	    		var valueStyle= loopIndex= "";
	    		valueStyle= nRow % 2;
	    		loopIndex= 6;				
				if( aData[7] == '1')
				{
					$($(nRow).children()).attr('class', 'hukumanstyle');
				}
				else if( aData[7] == '2')
				{
					$($(nRow).children()).attr('class', 'hukumanpernahstyle');
				}

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
        	$(event.target.parentNode.parentNode).addClass('row_selected');

        	var anSelected = fnGetSelected(oTable);													
        	anSelectedData = String(oTable.fnGetData(anSelected[0]));
        	var element = anSelectedData.split(','); 
        	anSelectedId = element[element.length-1];
        	// alert(anSelectedData);return false;
        });
		
		$('#example tbody').on( 'dblclick', 'tr', function () {
			$("#btnEdit").click();	
		});
		  
		var tempindextab=0;
        $('#btnAdd').on('click', function () {
        	newWindow = window.open("app/loadUrl/app/pegawai_add", 'Cetak'+Math.floor(Math.random()*999999));
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
        	newWindow = window.open("app/loadUrl/app/pegawai_add?reqId="+anSelectedId, 'Cetak'+tempindextab);
        	newWindow.focus();
			tempindextab= parseInt(tempindextab) + 1;
        	//window.parent.createWindowMaxFull("app/loadUrl/app/pegawai_add/?reqId="+anSelectedId);

				  // tutup flex dropdown => untuk versi mobile
				  $('div.flexmenumobile').hide()
				  $('div.flexoverlay').css('display', 'none')
				});

        $('#btnDataPNS').on('click', function () {
        	if(anSelectedData == "")
        		return false;				
        	newWindow = window.open("app/loadUrl/datapns/biodata_detil?reqId="+anSelectedId, 'Cetak'+tempindextab);
        	newWindow.focus();
			tempindextab= parseInt(tempindextab) + 1;
        	//window.parent.createWindowMaxFull("app/loadUrl/app/pegawai_add/?reqId="+anSelectedId);

				  // tutup flex dropdown => untuk versi mobile
				  $('div.flexmenumobile').hide()
				  $('div.flexoverlay').css('display', 'none')
				});

		$("#btnCari").on("click", function () {
			var reqSatuanKerjaId= reqStatusPegawaiId= reqCariFilter= "";
			reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
			reqCariFilter= $("#reqCariFilter").val();
			reqStatusPegawaiId= $("#reqStatusPegawaiId").val();
			oTable.fnReloadAjax("pegawai_json/json?reqSatuanKerjaId="+reqSatuanKerjaId+"&reqStatusPegawaiId="+reqStatusPegawaiId+"&sSearch="+reqCariFilter);
		});
		
		$("#reqCariFilter").keyup(function(e) {
			var code = e.which;
			if(code==13)
			{
				setCariInfo();
			}
		});
		
		$("#reqStatusPegawaiId").change(function() { 
			setCariInfo();
		});

		$('#btnDelete').on('click', function () {
        	if(anSelectedData == "")
        		return false;	
        	$.messager.confirm('Konfirmasi',"Hapus data terpilih?",function(r){
        		if (r){
        			$.getJSON("pegawai_json/delete/?reqId="+anSelectedId,
        				function(data){
        					$.messager.alert('Info', data.PESAN, 'info');
        					oTable.fnReloadAjax("pegawai_json/json");
        				});

        		}
        	});	
        });

        $('#btnLog').on('click', function () {
        	window.parent.openPopup("app/loadUrl/app/pegawai_log");

        	// tutup flex dropdown => untuk versi mobile
        	$('div.flexmenumobile').hide()
        	$('div.flexoverlay').css('display', 'none')
        });

        $('#btntalentrekamjejak').on('click', function () {

        	vinfoid= $(this).attr('id');

        	var reqSatuanKerjaId= reqStatusPegawaiId= reqCariFilter= "";
			reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
			reqCariFilter= $("#reqCariFilter").val();
			reqStatusPegawaiId= $("#reqStatusPegawaiId").val();

        	newWindow = window.open("app/loadUrl/app/pegawai_talent_excel?reqBulan=<?=date('m')?>&reqTahun=<?=date('Y')?>&reqSatuanKerjaId="+reqSatuanKerjaId+"&reqStatusPegawaiId="+reqStatusPegawaiId+"&reqButtonMode="+vinfoid+"&sSearch="+reqCariFilter, 'Cetak');
			newWindow.focus();

        });


        $('#btnCetakPegawai').on('click', function () {

        	var reqSatuanKerjaId= reqStatusPegawaiId= reqCariFilter= "";
			reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
			reqCariFilter= $("#reqCariFilter").val();
			reqStatusPegawaiId= $("#reqStatusPegawaiId").val();

        	newWindow = window.open("app/loadUrl/app/pegawai_cetak_excel?reqBulan=<?=date('m')?>&reqTahun=<?=date('Y')?>&reqSatuanKerjaId="+reqSatuanKerjaId+"&reqStatusPegawaiId="+reqStatusPegawaiId+"&sSearch="+reqCariFilter, 'Cetak');
			newWindow.focus();

        });


        $('#btnCetakPegawaiDinas').on('click', function () {

        	var reqSatuanKerjaId= reqStatusPegawaiId= reqCariFilter= "";
			reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
			reqCariFilter= $("#reqCariFilter").val();
			reqStatusPegawaiId= $("#reqStatusPegawaiId").val();

        	newWindow = window.open("app/loadUrl/app/pegawai_cetak_excel?reqModeDinas=1&reqBulan=<?=date('m')?>&reqTahun=<?=date('Y')?>&reqSatuanKerjaId="+reqSatuanKerjaId+"&reqStatusPegawaiId="+reqStatusPegawaiId+"&sSearch="+reqCariFilter, 'Cetak');
			newWindow.focus();

        });


        $('#btnCetakRekon').on('click', function () {

        	var reqSatuanKerjaId= reqStatusPegawaiId= reqCariFilter= "";
			reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
			reqCariFilter= $("#reqCariFilter").val();
			reqStatusPegawaiId= $("#reqStatusPegawaiId").val();

        	newWindow = window.open("app/loadUrl/app/rekon_cetak_excel?reqBulan=<?=date('m')?>&reqTahun=<?=date('Y')?>&reqSatuanKerjaId="+reqSatuanKerjaId+"&reqStatusPegawaiId="+reqStatusPegawaiId+"&sSearch="+reqCariFilter, 'Cetak');
			newWindow.focus();

        });

        $('#btnCetakRekonDinas').on('click', function () {

        	var reqSatuanKerjaId= reqStatusPegawaiId= reqCariFilter= "";
			reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
			reqCariFilter= $("#reqCariFilter").val();
			reqStatusPegawaiId= $("#reqStatusPegawaiId").val();

        	newWindow = window.open("app/loadUrl/app/rekon_cetak_excel?reqModeDinas=1&reqBulan=<?=date('m')?>&reqTahun=<?=date('Y')?>&reqSatuanKerjaId="+reqSatuanKerjaId+"&reqStatusPegawaiId="+reqStatusPegawaiId+"&sSearch="+reqCariFilter, 'Cetak');
			newWindow.focus();

        });

        $('#btnCetakLogin').on('click', function () {

        	var reqSatuanKerjaId= reqStatusPegawaiId= reqCariFilter= "";
			reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
			reqCariFilter= $("#reqCariFilter").val();
			reqStatusPegawaiId= $("#reqStatusPegawaiId").val();

        	newWindow = window.open("app/loadUrl/app/pegawai_login_cetak_excel?reqBulan=<?=date('m')?>&reqTahun=<?=date('Y')?>&reqSatuanKerjaId="+reqSatuanKerjaId+"&reqStatusPegawaiId="+reqStatusPegawaiId+"&sSearch="+reqCariFilter, 'Cetak');
			newWindow.focus();

        });

        $('#btnCetakLoginDinas').on('click', function () {

        	var reqSatuanKerjaId= reqStatusPegawaiId= reqCariFilter= "";
			reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
			reqCariFilter= $("#reqCariFilter").val();
			reqStatusPegawaiId= $("#reqStatusPegawaiId").val();

        	newWindow = window.open("app/loadUrl/app/pegawai_login_cetak_excel?reqModeDinas=1&reqBulan=<?=date('m')?>&reqTahun=<?=date('Y')?>&reqSatuanKerjaId="+reqSatuanKerjaId+"&reqStatusPegawaiId="+reqStatusPegawaiId+"&sSearch="+reqCariFilter, 'Cetak');
			newWindow.focus();

        });

        $('#btnCetakPegawaiCpnsPns').on('click', function () {

        	var reqSatuanKerjaId= reqStatusPegawaiId= reqCariFilter= "";
			reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
			reqCariFilter= $("#reqCariFilter").val();
			reqStatusPegawaiId= $("#reqStatusPegawaiId").val();

        	newWindow = window.open("app/loadUrl/app/pegawaicpnspns_cetak_excel?reqBulan=<?=date('m')?>&reqTahun=<?=date('Y')?>&reqSatuanKerjaId="+reqSatuanKerjaId+"&reqStatusPegawaiId="+reqStatusPegawaiId+"&sSearch="+reqCariFilter, 'Cetak');
			newWindow.focus();

        });


        $('#btnCetakIdpresensi').on('click', function () {

		var reqSatuanKerjaId= reqStatusPegawaiId= reqCariFilter= "";
		reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
		reqCariFilter= $("#reqCariFilter").val();
		reqStatusPegawaiId= $("#reqStatusPegawaiId").val();

		newWindow = window.open("app/loadUrl/app/idpresensi_cetak_excel?reqBulan=<?=date('m')?>&reqTahun=<?=date('Y')?>&reqSatuanKerjaId="+reqSatuanKerjaId+"&reqStatusPegawaiId="+reqStatusPegawaiId+"&sSearch="+reqCariFilter, 'Cetak');
		newWindow.focus();

		});


        $('#btnReset').on('click', function () {
        	if(anSelectedData == "")
        		return false;
        	else	
        	{
        		$.messager.confirm('Konfirmasi',"Reset Password data yang terpilih?",function(r){
        			if (r){
        				$.getJSON("pegawai_json/reset_password/?reqId="+anSelectedId,
        					function(data){
        						$.messager.alert('Info', data.PESAN, 'info');
        						oTable.fnReloadAjax("pegawai_json/json");
        				});

        			}
        		});	
        	}			
        });

        $('#btnPersonalMenu').on('click', function () {
        	if(anSelectedData == "")
        		return false;				
        	window.parent.openPopup("app/loadUrl/app/global_aksi_menu/?reqId="+anSelectedId);
		});

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

<style type="text/css">
	/*.col1 { width: 5% !important; }
	.col2 { width: 12% !important; }
	.col4 { width: 10% !important; }*/

	table{
        table-layout:fixed;
     /* word-wrap:break-word; */ 
        width: 100%;
    }

	table.dataTable tbody td th{
	  vertical-align: top;
	  white-space: nowrap;
	}

	/*table tr th:nth-child(1){
       width: 8% !important;
    }*/

	@media only screen and (max-width: 767px) {
		table{
	        table-layout:auto !important;
	    }

		/*table tr th:nth-child(1){
	       width: 8px !important;
	       background: red !important;
	    }

	    table tr th:nth-child(2){
	       width: 10px !important;
	       background: blue !important;
	    }*/

	    /*table tr th:nth-child(3){
	       width: 100px !important;
	       background: blue !important;
	    }*/
	    /*table tr th:nth-child(2){
	       width: 10% !important;
	    }
	    table tr th:nth-child(3){
	       width: 50% !important;
	    }*/
	}
	

	/*table tr th:nth-child(2){
       width: 12% !important;
    }

    table tr th:nth-child(3){
       width: 15% !important;
    }

    table tr th:nth-child(4){
       width: 10% !important;
    }*/
</style>

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
                            
							<h5 class="breadcrumbs-title">Pegawai</h5>
								<ol class="breadcrumbs">
									<li class="active">
                                    <input type="hidden" id="reqSatuanKerjaId" value="<?=$reqSatuanKerjaId?>" />
                                    <label id="reqLabelSatuanKerjaNama"><?=$reqSatuanKerjaNama?></label>
                                    </li>
								</ol>
							</div>
						</div>
					</div>
				</div>
				<!--breadcrumbs end-->
                
                <div id="bluemenu" class="bluetabs">
                    <ul>
                        <li>
                            <a href="#" id="btnCari" style="display:none" title="Cari">Cari</a>
                            <?
                            if($tempAksesMenu == "R" || $tempAksesMenu == "D"){}
                            else
                            {
                            ?>
                            <a id="btnAdd" title="Tambah"><img src="images/icon-tambah.png" /> Tambah</a>
                            <a id="btnDataPNS" title="Data PNS"><img src="images/icon-edit.png" /> Data Profil</a>
                            <?
                        	}
                            ?>

                            <?                            
                            if($tempUserLoginId == "376" || $tempUserLoginId == "338"){
                            ?>
                            <a id="btnDataPNS" title="Data PNS"><img src="images/icon-edit.png" /> Data Profil</a>
                            <?
                        	}
                            ?> 

                            <a id="btnEdit" title="Ubah"><img src="images/icon-edit.png" /> Ubah</a>
                            <!--<a id="btnLog" title="Log"><img src="images/icon-lihat.png" /> Log</a> -->
                            <a title="Cetak" rel="dropmenu2_b"><img src="images/icon-excel.png" /> Cetak</a>
                            <!-- <a title="Talent" rel="droptalent"><img src="images/icon-excel.png" /> Talent</a> -->
                            <?if($reqTampilReset==1)
                            {
                            ?>
                            	<a id="btnReset" title="Ubah"><img src="images/icon-user.png" width="20px" /> Reset Password</a>
                            	<a id="btnPersonalMenu" title="Ubah"><img src="images/icon-user.png" width="20px" /> Personal Menu</a>
                            <?
                        	}
                            ?>
                        </li>
                    </ul>

                    <div id="droptalent" class="dropmenudiv_b" style="width: 250px">
                    	<a title="Rekam Jejak" id="btntalentrekamjejak">Rekam Jejak</a>
                    </div>

                    <div id="dropmenu2_b" class="dropmenudiv_b" style="width: 250px">
                    	<a title="Cetak Pegawai" id="btnCetakPegawai">Cetak Pegawai</a>
                    	<a title="Cetak Rekon PNS" id="btnCetakRekon">Cetak Rekon PNS</a>
                    	<a title="Cetak Rekon PNS" id="btnCetakLogin">Cetak Login PNS</a>
                    <? if ($tempLoginLevel >= 20)
                    	{
                    	?>
                    	<a title="Cetak Pegawai Dinas" id="btnCetakPegawaiDinas">Cetak Pegawai Dinas</a>
                    	<a title="Cetak Rekon PNS" id="btnCetakRekonDinas">Cetak Rekon Dinas</a>
                    	<a title="Cetak Rekon PNS" id="btnCetakLoginDinas">Cetak Login Dinas</a>
                    <? } ?>

                     	<a title="Cetak Pegawai" id="btnCetakPegawaiCpnsPns">Cetak Data CPNS PNS</a>   
					<? if ($tempLoginLevel >= 30)
                    	{
                    	?>
                    	<a title="Cetak Pegawai Dinas" id="btnCetakIdpresensi">Cetak ID dan Data Area Presensi</a>
                    <? } ?>						
			        </div>

					<script type="text/javascript">
						tabdropdown.init("bluemenu")
					</script>

                </div>

				<div class="area-parameter">
					<div class="kiri">
						<!--<span>Show</span>
						<select>
							<option>10</option>
							<option>25</option>
							<option>50</option>
						</select>
						<span>data</span>-->
					</div>

					<div class="kanan">
						<span>Cari :</span>
						<input type="text" id="reqCariFilter" />
						<button id="clicktoggle">Filter ▾</button>
					</div>
				</div>

				<div class="area-parameter no-marginbottom">

					<div id="settoggle">
						<div class="row">
							<div class="col s3 m1">Status</div>
							<div class="col s2 m1 select-semicolon">:</div>
							<div class="col s7 m5">
								<select class="option-vw9" id="reqStatusPegawaiId">
									<option value="">Semua</option>
									<option value="126" selected>CPNS/PNS/PPPK</option>
									<option value="12">CPNS/PNS</option>
									<option value="1">CPNS</option>
									<option value="2">PNS</option>
									<option value="3">Pensiun</option>
									<option value="6">PPPK</option>
									<option value="spk21">Pensiun BUP</option>
									<option value="spk24">Pensiun Wafat</option>
									<option value="spk25">Pensiun Tewas</option>
									<option value="spk27">Pemberhentian Dengan Tidak Hormat</option>
									<option value="spk28">Mutasi Keluar/Pindah Atas Permintaan Sendiri</option>
									<option value="hk">Hukuman</option>
									<option value="pk">Pernah Kena Hukuman</option>
								</select>
							</div>

							<div class="col s12">
								<table id="tt" class="easyui-treegrid" style="width:100%; height:250px">
									<thead>
										<tr>
											<th field="NAMA" width="90%">Nama</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>

				</div>

				<!--start container-->
				<div class="container" style="clear:none; padding-top: 0px !important;">
					<div class="section">
						<table id="example" class="display mdl-data-table dt-responsive" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th style="text-align:center;" class="col1">FOTO</th>
									<th style="text-align:center;" class="col2">NIP BARU<br/>NIP LAMA</th>
									<th data-priority="1" style="text-align:center" class="col3">NAMA</th>
									<th data-priority="2" style="text-align:center" class="col4">GOL<br/>TMT</th>
                                    <th style="text-align:center">JABATAN<br/>TMT<br/>ESELON</th>
                                    <th style="text-align:center">UNIT KERJA</th>
                                    <th style="text-align:center">INDUK</th>
									<!-- <th class="all" style="text-align:center; width:60px">FOTO</th>
									<th class="all" style="text-align:center">NIP BARU<br/>NIP LAMA</th>
									<th class="all" style="text-align:center">NAMA</th>
									<th class="none" style="text-align:center">GOL<br/>TMT</th>
                                    <th class="none" style="text-align:center">JABATAN<br/>TMT<br/>ESELON</th>
                                    <th class="none" style="text-align:center">UNIT KERJA</th>
                                    <th class="none" style="text-align:center">INDUK</th> -->
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

		$(function(){
			var tt = $('#tt').treegrid({
				url: 'satuan_kerja_json/treepilih',
				rownumbers: false,
				pagination: false,
				idField: 'ID',
				treeField: 'NAMA',
				onBeforeLoad: function(row,param){
					if (!row) { // load top level rows
					param.id = 0; // set id=0, indicate to load new page rows
					}
				}
			});
		});

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

	<style>
		.option-vw9 {
			/*width: 35vw !important;*/
			width: 100% !important;
		}
		.dropdown-content
		{
			max-height: 250px !important;
		}

		.dropdown-content li
		{
			min-height: 15px !important;
			line-height: 0.1rem !important;
		}
		.dropdown-content li > span
		{
			font-size: 14px;
			line-height: 12px !important;
		}

	</style>
</body>
</html>