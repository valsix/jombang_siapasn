<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");

// $this->load->model('SatuanKerja');


$tinggi = 190;

//$reqSatuanKerjaId = $this->KODE_CABANG;
$arrWidth=array("20", "110", "120", "80", "100", "100");
$arrData=array("ID", "NIP Baru / Nama", "Satuan Kerja SIAP ASN", "Aktif", "Area Default", "Area Absensi");


// $satuankerja = new SatuanKerja();
// $satuankerja->selectByParams();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<title>Diklat</title>
<base href="<?=base_url()?>">
<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico">
<!--<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.datatables.net/rss.xml">-->
<link href="css/admin.css" rel="stylesheet" type="text/css">

<style type="text/css" media="screen">
    @import "lib/media/css/site_jui.css";
    @import "lib/media/css/demo_table_jui.css";
    @import "lib/media/css/themes/base/jquery-ui.css";
</style>

	<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.6/media/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.6/extensions/Responsive/css/dataTables.responsive.css">
	<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.6/examples/resources/syntax/shCore.css">
	<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.6/examples/resources/demo.css">

	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/media/js/jquery.js"></script>

    <!-- <link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
    <script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script> -->

    <link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/icon.css">
	<!-- <link rel="stylesheet" type="text/css" href="lib/easyui/demo/demo.css"> -->

	<script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.min.js"></script>
	<script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.easyui.min.js"></script>

	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/media/js/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/Responsive/js/dataTables.responsive.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/examples/resources/syntax/shCore.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/examples/resources/demo.js"></script>	
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/FixedColumns/js/dataTables.fixedColumns.js"></script>	
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/media/js/jquery.dataTables.rowGrouping.js"></script>

<script type="text/javascript" charset="utf-8">
	var oTable;
    $(document).ready( function () {
										
        var id = -1;//simulation of id
		$(window).resize(function() {
		  console.log($(window).height());
		  $('.dataTables_scrollBody').css('height', ($(window).height() - <?=$tinggi?>));
		});
        oTable = $('#example').dataTable({ bJQueryUI: true,"iDisplayLength": 50,
			  /* UNTUK MENGHIDE KOLOM ID */
			  "aoColumns": [
			  <?
			  for($col=0; $col<count($arrData); $col++)
			  {
			  	if($col == 0){}
		  		else
		  			echo ",";
			  ?>
			  		null
			  <?
			  }
			  ?>
			  ],
			  "responsive": true,
			  "bSort":true,
			  "bProcessing": true,
			  "bServerSide": true,		
			  "sAjaxSource": "bio/pegawai_json/jsonrekon/?reqSatuanKerjaId=<?=$reqSatuanKerjaId?>",
			  // columnDefs: [{ className: 'never', targets: [  0 ] }], 
			  "sScrollY": ($(window).height() - <?=$tinggi?>),
			  "sScrollX": "100%",								  
			  "sScrollXInner": "100%",
			  "sPaginationType": "full_numbers",
			  "fnDrawCallback": function( oSettings ) {
					$('#example_filter input').unbind();
					$('#example_filter input').bind('keyup', function(e) {
						if(e.keyCode == 13) {
							setCariInfo();
							// tesdata= $('#example_filter input').val();
							// console.log(tesdata);
							// oTable.fnFilter(this.value);
						}
					});
				}
			});
			// }).rowGrouping({bExpandableGrouping: true});
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
					  // anSelectedId = element[0];
					  anSelectedId = element[element.length-1];
			  });
			  
			  $('#btnSinkronisasi').on('click', function () {
				$.messager.alert('Info', "undercontraction", 'info');
				// $.messager.confirm('Konfirmasi', "Apakah anda yakin untuk memperbarui data ?",function(r){
				// if (r){
				// 		var win = $.messager.progress({
				// 			title:'Proses integrasi data.',
				// 			msg:'Proses data...'
				// 		});

				// 		var jqxhr = $.get( "bio/pegawai_json/proses/", function() {
				// 		})
				// 		.done(function() {

				// 			integrasi();
				// 		});
				// 	}
				//  });

			  });

			  $("#btnAdd,#btnEdit").on("click", function () {
			  	var tempId= $(this).attr('id');
			  	var tempLihat= "";
			  	if(tempId == "btnAdd")
			  	{
			  		anSelectedId= "";
			  		tempLihat= 1;
			  	}
			  	else if(tempId == "btnEdit")
			  	{
		  			if(anSelectedId == ""){}
		  			else
		  			{
		  				tempLihat= 1;
		  			}
		  			// console.log(anSelectedId);return false;
		  		}
		  		
		  		if(tempLihat == 1)
			  	document.location.href = "app/loadUrl/biotime/pegawai_add?reqId="+anSelectedId;

			  	$('div.flexmenumobile').hide()
			  	$('div.flexoverlay').css('display', 'none')

			  });
			  
			  $("#btnCari").on("click", function () {
			  	var reqSatuanKerjaId= reqStatusIntegrasi= reqCariFilter= "";
			  	// reqCariFilter= $("#reqCariFilter").val();
			  	reqCariFilter= $('#example_filter input').val();
			  	reqStatusIntegrasi= $("#reqStatusIntegrasi").val();
			  	reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
			  	oTable.fnReloadAjax("bio/pegawai_json/jsonrekon/?reqSatuanKerjaId="+reqSatuanKerjaId+"&reqStatusIntegrasi="+reqStatusIntegrasi+"&reqPencarian="+reqCariFilter);
			  });

			  $("#reqCariFilter").keyup(function(e) {
			  	var code = e.which;
			  	if(code==13)
			  	{
			  		setCariInfo();
			  	}
			  });

			  $("#reqSatuanKerjaId, #reqStatusIntegrasi").change(function() { 
			  	setCariInfo();
			  });

               // $(".ui-corner-tl").hide();
			  
		});
		
		function integrasi()
		{
			$.getJSON("bio/bio_integrasi_json/pegawaiadd/",
				function(data){
					// console.log(data);
					if(parseInt(data) > 0)
					{
						integrasi();
						// console.log(data);
					}
					else
					{
						$.messager.progress('close');
						setCariInfo();
					}

					// return data;
				}
			);
		}
		function setCariInfo()
		{
			$(document).ready( function () {
			  $("#btnCari").click();      
			});
		}

		function calltreeid(id, nama)
		{
			nama= ", pada satuan kerja : "+nama;
			$("#reqLabelSatuanKerjaNama").text(nama);
			$("#reqSatuanKerjaId").val(id);
			setCariInfo();
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
      

</head>
<body style="overflow:hidden;">
<div id="begron"><img src="images/bg-kanan.jpg" width="100%" height="100%" alt="Smile"></div>
<div id="wadah">
    <div class="judul-halaman">
    	<span>Pegawai</span>
    	<span>
    		<input type="hidden" id="reqSatuanKerjaId" value="<?=$reqSatuanKerjaId?>" />
    		<label id="reqLabelSatuanKerjaNama"><?=$reqSatuanKerjaNama?></label>
    	</span>
    </div>
    <div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">    
        <ul>
			<li>
				<a href="#" id="btnCari" style="display:none" title="Cari">Cari</a>
            	<!-- <span><a id="btnAdd" title="Tambah"><img src="images/icon-tambah.png" /> Tambah</a></span> -->
                <!-- <span><a id="btnEdit" title="Ubah Data"><img src="images/icon-edit.png" /> Ubah Data</a> </span> -->
                <span><a id="btnSinkronisasi" title="Sinkronisasi"><img src="images/icon-edit.png" /> Sinkronisasi</a> </span>
            </li>
        </ul>
    </div>

    <div id="settoggle">
	    <div class="parameter-tambahan">
	    	<table style="width: 100%">
	    		<tr>
	    			<td style="width:10%">Status Integrasi</td>
	    			<td style="width:10px">:</td>
	    			<td>
	    				<select name="reqStatusIntegrasi" id="reqStatusIntegrasi">
	    					<option value="">Semua</option>
	    					<option value="1">Sudah</option>
	    					<option value="0">Belum</option>
	    				</select>
	    			</td>
	    		</tr>
	    	</table>
	    </div>

	    <div style="width: 100%">
	    	<table style="width:100%; height:250px">
	    		<tr>
	    			<td>
	    				<table id="tt" class="easyui-treegrid" style="width:100%; height: 100%">
	    					<thead>
	    						<tr>
	    							<th field="NAMA" width="90%">Nama</th>
	    						</tr>
	    					</thead>
	    				</table>
	    			</td>
	    		</tr>
	    	</table>
		</div>
	</div>

    <table cellpadding="0" cellspacing="0" border="0" class="display dt-responsive" id="example">
    <thead>
        <tr>
        <?
        for($col=0; $col<count($arrData); $col++)
        {
        	$style= "";
        	if($col == 2)
        	{
        		$style= "text-align: center; ";
        	}
        	$style.= "width='".$arrWidth[$col]."px'; ";
        	?>
        	<th style="<?=$style?>"><?=$arrData[$col]?></th>
        <?
        }
        ?>
    </thead>
    </table> 
</div>

<script type="text/javascript">
$(function(){
	var tt = $('#tt').treegrid({
		url: 'siap/satuan_kerja_json/treepilih',
		rownumbers: false,
		pagination: false,
		idField: 'ID',
		treeField: 'NAMA',
		onBeforeLoad: function(row,param){
			if (!row) {
				param.id = 0;
			}
		}
		, onLoadSuccess: function(row,data){
			if(row == null)
				$("#settoggle").hide();
		}
	});

	// $("#settoggle").hide();
	$('#clicktoggle').on('click', function () {
		vsb= $("#settoggle").is(":visible")
		console.log(vsb);
		if(vsb)
			$("#settoggle").hide();
		else
			$("#settoggle").show();
			
	});
});



// var outer = document.getElementById('settoggle');
// document.getElementById('clicktoggle').addEventListener('click', function(evnt) {
	/*if (outer.style.maxHeight){
		outer.style.maxHeight = null;
		outer.classList.add('settoggle-closed');
	} 
	else {
		outer.style.maxHeight = outer.scrollHeight + 'px';
		outer.classList.remove('settoggle-closed');  
	}*/
// });
// outer.style.maxHeight = outer.scrollHeight + 'px';
// $('#clicktoggle').trigger('click');
</script>
</body>
</html>