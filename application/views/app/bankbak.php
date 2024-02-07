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
        oTable = $('#example').dataTable({ bJQueryUI: true,"iDisplayLength": 500,
        	/* UNTUK MENGHIDE KOLOM ID */
        	"aoColumns": [ 
        	null,												 								 
        	null												 								 
        	],
        	"bSort":false,
        	"bProcessing": true,
        	"bServerSide": true,
        	"sAjaxSource": "bank_json/json",			  
        	//"sScrollY": ($(window).height() - <?=$tinggi?>),
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
            var anSelected = fnGetSelected(oTable);													
            anSelectedData = String(oTable.fnGetData(anSelected[0]));
            var element = anSelectedData.split(','); 
            anSelectedId = element[element.length-1];
        });

        $("#btnCari").on("click", function () {
            tempJsonParsing= "";
              //setJsonFilter();
              //alert(tempJsonParsing);
              oTable.fnReloadAjax("bank_json/json?"+tempJsonParsing);
          });

        $('#btnAdd').on('click', function () {
        	window.parent.openPopup("app/loadUrl/app/bank_add");
            // tutup flex dropdown => untuk versi mobile

            $('div.flexmenumobile').hide()
            $('div.flexoverlay').css('display', 'none')

        });

        $('#btnEdit').on('click', function () {
        	if(anSelectedData == "")
        		return false;				
        	window.parent.openPopup("app/loadUrl/app/bank_add/?reqId="+anSelectedId);

		      // tutup flex dropdown => untuk versi mobile
             $('div.flexmenumobile').hide()
             $('div.flexoverlay').css('display', 'none')
         });

        $('#btnDelete').on('click', function () {
        	if(anSelectedData == "")
        		return false;	
        	$.messager.confirm('Konfirmasi',"Hapus data terpilih?",function(r){
        		if (r){
        			$.getJSON("bank_json/delete/?reqId="+anSelectedId,
        				function(data){
        					$.messager.alert('Info', data.PESAN, 'info');
        					oTable.fnReloadAjax("bank_json/json");
        				});

        		}
        	});	
        });

        $('#btnLog').on('click', function () {
        	window.parent.openPopup("app/loadUrl/app/bank_log");

		    // tutup flex dropdown => untuk versi mobile
            $('div.flexmenumobile').hide()
            $('div.flexoverlay').css('display', 'none')
        });
    });

function setCariInfo()
{
    $(document).ready( function () {
        $("#btnCari").click();          
    });
}

function hapusdata(id, statusaktif)
{
    $.messager.defaults.ok = 'Ya';
    $.messager.defaults.cancel = 'Tidak';
    reqmode= "bank_1";
    infoMode= "Apakah anda yakin mengaktifkan data terpilih"
    if(statusaktif == "") {
        reqmode= "bank_0";
        infoMode= "Apakah anda yakin menonaktifkan data terpilih"
    }
    //alert(statusaktif);return false;
    $.messager.confirm('Konfirmasi', infoMode+" ?",function(r){
        if (r){
            var s_url= "bank_json/delete/?reqMode="+reqmode+"&reqId="+id;
                //var request = $.get(s_url);
                $.ajax({'url': s_url,'success': function(msg){
                    if(msg == ''){}
                        else
                        {
                            setCariInfo();
                        }
                    }});
            }
        }); 
}
</script>

<!--RIGHT CLICK EVENT-->		
<style>

	.vmenu{
		border:1px solid #aaa;
		position:absolute;
		background:#fff;
		display:none;font-size:0.75em;
	}
	.first_li{}
	.first_li span{
		width:100px;
		display:block;
		padding:5px 10px;
		cursor:pointer
	}
	.inner_li{display:none;margin-left:120px;position:absolute;border:1px solid #aaa;border-left:1px solid #ccc;margin-top:-28px;background:#fff;}
	.sep_li{border-top: 1px ridge #aaa;margin:5px 0}
	.fill_title{font-size:11px;font-weight:bold;/height:15px;/overflow:hidden;word-wrap:break-word;}
</style>

<link href="lib/media/themes/main_datatables.css" rel="stylesheet" type="text/css" /> 
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
		<div class="judul-halaman">Data Bank</div>
		<!--<div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">    -->
        <div id="bluemenu" class="bluetabs">
			<ul>
				<li>
                    <a href="#" id="btnCari" style="display:none" title="Cari">Cari</a>
                    <a id="btnAdd" title="Tambah"><img src="images/icon-tambah.png" /> Tambah</a>
                    <a id="btnEdit" title="Ubah"><img src="images/icon-edit.png" /> Ubah</a>
                    <!-- <a id="btnDelete" title="Hapus"><img src="images/icon-hapus.png" /> Hapus</a>         -->
                    <a id="btnLog" title="Log"><img src="images/icon-lihat.png" /> Log</a>        
                </li>       
                <!-- <li>
                <a href="#" id="" title="Tambah"><img src="images/icon-sinkronisasi.png" /> Sinkronisasi</a>
                </li>-->
            </ul>
        </div>
        <div class="parameter-tambahan">
        	<div class="tombol-filter"><button> Filter</button></div>
            <div class="breadcrumb-sub">
            	<a href="#">BKDPP</a><span> / </span>
                <a href="#">Bidang Pengadaan, Profesi dan Informasi Pegawai</a><span> / </span>
                <span>Sub Bidang Informasi Pegawai</span>
            </div>
        </div>
        
        <div class="simpleTabs">
            <ul class="simpleTabsNavigation">
                <li><a href="#" id="btnReload"><i class="fa fa-line-chart fa-lg"></i> Data Pribadi</a></li>
                <li><a href="#" id="btnReload"><i class="fa fa-line-chart fa-lg"></i> Data Alamat</a></li>
            </ul>

			<div class="simpleTabsContent">
            	<table border="0" cellpadding="5" cellspacing="5" class="gradient-style" style="width:100%">
                <tbody>
                    <tr>
                        <td style="width:150px">
                            <input type="hidden" id="reqStatusTanggalPesan" name="reqStatusTanggalPesan" value="1" />
                            <input type="checkbox" id="reqCheckBoxTanggalPesan" name="reqCheckBoxTanggalPesan" checked />
                            Tgl Pesan
                        </td>
                        <td style="width:5px">:</td>
                        <td style="width:180px">
                            <input type="text" id="reqTanggalPemesananAwal" name="reqTanggalPemesananAwal" style="width:70px" value="<?=$tempTanggalPemesananAwal?>" />
                             s/d
                             <input type="text" id="reqTanggalPemesananAkhir" name="reqTanggalPemesananAkhir" style="width:70px" />
                        </td>
                    </tr>
                </tbody>
                </table>
            </div>
            
            <div class="simpleTabsContent">
            	<table border="0" cellpadding="5" cellspacing="5" class="gradient-style" style="width:100%">
                <tbody>
                    <tr>
                        <td style="width:150px">
                            <input type="hidden" id="reqStatusTanggalPesan" name="reqStatusTanggalPesan" value="1" />
                            <input type="checkbox" id="reqCheckBoxTanggalPesan" name="reqCheckBoxTanggalPesan" checked />
                            AA
                        </td>
                        <td style="width:5px">:</td>
                        <td style="width:180px">
                            <input type="text" id="reqTanggalPemesananAwal" name="reqTanggalPemesananAwal" style="width:70px" value="<?=$tempTanggalPemesananAwal?>" />
                             s/d
                             <input type="text" id="reqTanggalPemesananAkhir" name="reqTanggalPemesananAkhir" style="width:70px" />
                        </td>
                    </tr>
                </tbody>
                </table>
            </div>
        </div>
        
        <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
            <thead>
                <tr>
                    <th width="400px">Nama</th>                                       
                    <th style="width:60px">Aksi</th>                                       
                </tr>
            </thead>
        </table> 
    </div>
</body>
</html>