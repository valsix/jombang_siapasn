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

	<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.6/media/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.6/examples/resources/syntax/shCore.css">
	<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.6/examples/resources/demo.css">
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/media/js/jquery.js"></script>
    <link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
    <script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/media/js/jquery.dataTables.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/examples/resources/syntax/shCore.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/examples/resources/demo.js"></script>	
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/FixedColumns/js/dataTables.fixedColumns.js"></script>	
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/TableTools/js/dataTables.tableTools.min.js"></script>	
  
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
							 null,												 								 
							 null,												 								 
							 null,												 								 
							 null,												 								 
							 null,												 								 
							 null,												 								 
							 null,												 								 
							 null,												 								 
							 null,												 								 
							 null,												 								 
							 null,												 								 
							 null,												 								 
							 null,												 								 
							 null,												 								 
							 null,												 								 
							 null,												 								 
							 null,												 								 
							 null,												 								 
							 null,												 								 
							 null,												 								 
							 null,												 								 
							 null,												 								 
							 null,												 								 
							 null,												 								 
							 null,												 								 
							 null,												 								 
							 null,												 								 
							 null,												 								 
							 null												 								 
						],
			  "bSort":true,
			  "bProcessing": true,
			  "bServerSide": true,
			  "sAjaxSource": "pegawai_json/json",			  
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
		});
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
      

</head>
<body style="overflow:hidden;">
<div id="begron"><img src="images/bg-kanan.jpg" width="100%" height="100%" alt="Smile"></div>
<div id="wadah">
    <div class="judul-halaman">PEGAWAI</div>
    <div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">    
        <ul>
			<li>
                <a id="btnAdd" title="Tambah"><img src="images/icon-tambah.png" /> Tambah</a>
                <a id="btnEdit" title="Ubah"><img src="images/icon-edit.png" /> Ubah</a>
                <a id="btnDelete" title="Hapus"><img src="images/icon-hapus.png" /> Hapus</a>        
            </li>       
           <!-- <li>
            	<a href="#" id="" title="Tambah"><img src="images/icon-sinkronisasi.png" /> Sinkronisasi</a>
            </li>-->
        </ul>
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>
            <th width="400px">NAMA</th>                                       
            <th width="400px">STATUS</th>                                       
            <th width="400px">STATUS_PEGAWAI</th>                                       
            <th width="400px">NIP_LAMA</th>                                       
            <th width="400px">NIP_BARU</th>                                       
            <th width="400px">GELAR_DEPAN</th>                                       
            <th width="400px">GELAR_BELAKANG</th>                                       
            <th width="400px">TEMPAT_LAHIR</th>                                       
            <th width="400px">TANGGAL_LAHIR</th>                                       
            <th width="400px">JENIS_KELAMIN</th>                                       
            <th width="400px">STATUS_KAWIN</th>                                       
            <th width="400px">SUKU_BANGSA</th>                                       
            <th width="400px">GOLONGAN_DARAH</th>                                       
            <th width="400px">EMAIL</th>                                       
            <th width="400px">ALAMAT</th>                                       
            <th width="400px">RT</th>                                       
            <th width="400px">RW</th>                                       
            <th width="400px">KODEPOS</th>                                       
            <th width="400px">TELEPON</th>                                       
            <th width="400px">HP</th>                                       
            <th width="400px">KARTU_PEGAWAI</th>                                       
            <th width="400px">ASKES</th>                                       
            <th width="400px">TASPEN</th>                                       
            <th width="400px">NPWP</th>                                       
            <th width="400px">NIK</th>                                       
            <th width="400px">NO_REKENING</th>                                       
            <th width="400px">SK_KONVERSI_NIP</th>                                       
            <th width="400px">JENIS_PEGAWAI</th>                                       
            <th width="400px">BANK</th>                                       
            <th width="400px">AGAMA</th>                                       
                                             
        </tr>
     </thead>
    </table> 
</div>
</body>
</html>

 