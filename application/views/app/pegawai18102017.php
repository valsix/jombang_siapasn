<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

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
	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
    
    <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/media/js/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/extensions/Responsive/js/dataTables.responsive.js"></script>
    <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/syntax/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/demo.js"></script>

	<script type="text/javascript" charset="utf-8">
		var oTable;
		$(document).ready( function () {

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
			"bSort":false,
		    "bFilter": true,
		    "bLengthChange": true,
        	"bProcessing": true,
        	"bServerSide": true,
        	"sAjaxSource": "pegawai_json/json",
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

        $('#btnAdd').on('click', function () {
			newWindow = window.open("app/loadUrl/app/pegawai_add", 'Cetak');
			newWindow.focus();
        	//window.parent.createWindowMaxFull("app/loadUrl/app/pegawai_add");

				  // tutup flex dropdown => untuk versi mobile
				  $('div.flexmenumobile').hide()
				  $('div.flexoverlay').css('display', 'none')

				});

        $('#btnEdit').on('click', function () {
        	if(anSelectedData == "")
        		return false;				
			newWindow = window.open("app/loadUrl/app/pegawai_add?reqId="+anSelectedId, 'Cetak');
			newWindow.focus();
        	//window.parent.createWindowMaxFull("app/loadUrl/app/pegawai_add/?reqId="+anSelectedId);

				  // tutup flex dropdown => untuk versi mobile
				  $('div.flexmenumobile').hide()
				  $('div.flexoverlay').css('display', 'none')
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
		
		$('#reqProgramKodeAkunCabangRekeningJsonId').combotree('setValue', "");
		var url = "satuan_kerja_json/combotree";
		$('#reqProgramKodeAkunCabangRekeningJsonId').combotree('reload', url);
		
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
	
	function clickNode(cc, id)
	{
		var opts= cc.combotree('options');
		var values= cc.combotree('getValues');
		$("#"+id).val(values);
	}
</script>

<link href="css/begron.css" rel="stylesheet" type="text/css">  
<link href="css/bluetabs.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/dropdowntabs.js"></script>

<!-- SIMPLE TAB -->
<script type="text/javascript" src="lib/simpletabs_v1.3/js/simpletabs_1.3.js"></script>
<link href="lib/simpletabs_v1.3/css/simpletabs.css" type="text/css" rel="stylesheet">

</head>
<body style="overflow:hidden;">
	<div id="begron"><img src="images/bg-kanan.jpg" width="100%" height="100%" alt="Smile"></div>
	<div id="wadah">
		<div class="judul-halaman">PEGAWAI</div>
		
        <div class="breadcrumb-sub">
            <a href="#">BKDPP</a><span> / </span>
            <a href="#">Bidang Pengadaan, Profesi dan Informasi Pegawai</a><span> / </span>
            <span>Sub Bidang Informasi Pegawai</span>
        </div>
        
        <div id="bluemenu" class="bluetabs">
            <ul>
                <li>
                    <a id="btnAdd" title="Tambah"><img src="images/icon-tambah.png" /> Tambah</a>
                    <a id="btnEdit" title="Ubah"><img src="images/icon-edit.png" /> Ubah</a>
                    <!-- <a id="btnDelete" title="Hapus"><img src="images/icon-hapus.png" /> Hapus</a>         -->
                    <a id="btnLog" title="Log"><img src="images/icon-lihat.png" /> Log</a>        
                </li>
            </ul>
        </div>
		
        <div id="tabpencarian" style="display:none">
		<div class="simpleTabs">
            <ul class="simpleTabsNavigation">
                <li><a href="#" id="btnReload"><i class="fa fa-line-chart fa-lg"></i> Detil</a></li>
            </ul>

			<div class="simpleTabsContent">
            	<table border="0" cellpadding="5" cellspacing="5" class="gradient-style" style="width:100%">
                <tbody>
                    <tr>
                        <td style="width:150px">
                            <input type="hidden" id="reqStatusTanggalPesan" name="reqStatusTanggalPesan" value="1" />
                            <input type="checkbox" id="reqCheckBoxTanggalPesan" name="reqCheckBoxTanggalPesan" checked />
                            Satuan Kerja
                        </td>
                        <td style="width:5px">:</td>
                        <td style="width:180px">
                            <input id="reqProgramKodeAkunCabangRekeningJsonId" class="easyui-combotree" data-options="
                            onLoadSuccess: function (row, data) {
                            $('#reqProgramKodeAkunCabangRekeningJsonId').combotree('tree').tree('collapseAll');
                                    },
                                    onClick: function(node){
                                    clickNode($('#reqProgramKodeAkunCabangRekeningJsonId'), 'reqProgramKodeAkunCabangRekeningId');
                                },
                                onCheck: function(node, checked){
                                clickNode($('#reqProgramKodeAkunCabangRekeningJsonId'), 'reqProgramKodeAkunCabangRekeningId');
                            },checkbox:false,cascadeCheck:true" style="width:350px;" />
                            <input type="hidden" name="reqProgramKodeAkunCabangRekeningId" id="reqProgramKodeAkunCabangRekeningId" />
                        </td>
                    </tr>
                </tbody>
                </table>
            </div>
            
        </div>
		</div>
        
        <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
            <thead>
                <tr>
                    <th width="100px">FOTO</th>
                    <th>NIP Baru</th>
                    <th>Nama</th>
                    <th>Satuan Kerja</th>
                </tr>
            </thead>
        </table> 
</div>
</body>
</html>

