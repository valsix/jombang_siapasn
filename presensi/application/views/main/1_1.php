<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model('main/SatuanKerja');

$reqTahun= $this->input->get("reqTahun");
$reqBulan= $this->input->get("reqBulan");
$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");

if($reqTahun == "")	{
	$reqLastMonth = strtotime("0 months", strtotime(date("d-m-Y")));
	$reqBulan = strftime ( '%m' , $reqLastMonth );
	// $reqBulan= "11";
	$reqTahun = strftime ( '%Y' , $reqLastMonth );
}

$tinggi = 280;

//$reqSatuanKerjaId = $this->KODE_CABANG;

$statement= " AND A.SATUAN_KERJA_PARENT_ID = 0
AND EXISTS(
SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
)";

$satuankerja = new SatuanKerja();
$satuankerja->selectByParams(array(), -1, -1, $statement);
// echo $satuankerja->query;exit();

$date=$reqTahun."-".$reqBulan;
$totalhari =  getDay(date("Y-m-t",strtotime($date)));
// echo $date."--".$totalhari;exit();

$arrWidth=array("150", "320");
$arrData=array("NIP / NAMA", "Satuan Kerja");
// print_r($arrData);exit();

$arrDataDetil= "";
$arrDataDetil= array();
for($i=1; $i <= $totalhari; $i++) 
{
	array_push($arrDataDetil, "IN");
	array_push($arrDataDetil, "OUT");
}
// print_r($arrDataDetil);exit();

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

<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/Responsive/js/dataTables.responsive.js"></script>
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/media/js/jquery.dataTables.rowGrouping.js"></script>
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
        oTable = $('#example').dataTable({ bJQueryUI: true,"iDisplayLength": 25,
        	  // "pageLength": 50,
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
			  <?
			  for($col=0; $col<count($arrDataDetil); $col++)
			  {
			  ?>
			  		, null
			  <?
			  }
			  ?>
			  ],
			  "bSort":true,
			  // "bFilter": false,
			  // "bAutoWidth": false,
			  // "scrollCollapse": true,
			  "bProcessing": true,
			  "bServerSide": true,
			  "FixedColumns": true,
			  /*"ajax": {	
	            "url": "main/rekapitulasi_absensi_json/json/?reqSatuanKerjaId=<?=$reqSatuanKerjaId?>&reqBulan=<?=$reqBulan?>&reqTahun=<?=$reqTahun?>",
        	  	"type": "POST"
        	  },*/
			  "sAjaxSource": "main/rekapitulasi_absensi_json/json/?reqSatuanKerjaId=<?=$reqSatuanKerjaId?>&reqBulan=<?=$reqBulan?>&reqTahun=<?=$reqTahun?>",
			  // columnDefs: [{ className: 'never', targets: [  0 ] }], 
			  "sScrollY": ($(window).height() - <?=$tinggi?>),
			  "scrollX": true,
			  "responsive": false,
			  "sScrollX": "100%",								  
			  "sScrollXInner": "100%",
			  "sPaginationType": "full_numbers"
			});
			// }).rowGrouping({bExpandableGrouping: true});

			var fc = new $.fn.dataTable.FixedColumns( oTable, {
				leftColumns: 2
			});

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
				  if ( $(aTrs[i]).hasClass('selected') )
				  {
					  aReturn.push( aTrs[i] );
					  anSelectedPosition = i;
				  }
			  }
			  return aReturn;
		  }

		  // $(".rowData").live("click", function () {
		  // 	var row = $(this);

		  // 	$("#example tr.rowData").removeClass("rowData-selected");
		  // 	$(".DTFC_Cloned tr.rowData").removeClass("rowData-selected");

		  // 	var rowIndex = row.index() + 1;

		  // 	if (row.parent().parent().hasClass("DTFC_Cloned")) {
		  // 		$("#example tr.rowData:nth-child(" + rowIndex + ")").addClass("rowData-selected");;
		  // 	} else {
		  // 		$(".DTFC_Cloned tr.rowData:nth-child(" + rowIndex + ")").addClass("rowData-selected");
		  // 	}

		  // 	row.addClass("rowData-selected");
		  // });

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
		  	// alert(anSelectedId);
		  });

		  /*$("#example tbody").click(function(event) {

		  	// $("#example tr").removeClass('selected');
		  	// $(".DTFC_Cloned tr").removeClass("selected");
		  	var row = $(this);
		  	// var rowIndex = row.index() + 1;

		  	// if (row.parent().parent().hasClass("DTFC_Cloned")) 
		  	// {
		  	// 	$("#example tr:nth-child(" + rowIndex + ")").addClass("selected");;
		  	// } 
		  	// else 
		  	// {
		  	// 	$(".DTFC_Cloned tr:nth-child(" + rowIndex + ")").addClass("selected");
		  	// }
		  	// row.addClass("selected");

		  	$(oTable.fnSettings().aoData).each(function (){
		  		$(this.nTr).removeClass('row_selected');
		  	});

		  	$(event.target.parentNode).addClass('row_selected');

		  	var anSelected = fnGetSelected(oTable);
		  	anSelectedData = String(oTable.fnGetData(anSelected[0]));

		  	var element = anSelectedData.split(',');
		  	anSelectedId = element[element.length-1];

		  	// fc.fnUpdate();
		  	if(row.parent().hasClass("DTFC_Cloned"))
		  	{
		  		// console.log(fc.data())
		  		// $(fc.aoData).each(function (){
		  		// 	$(this.nTr).removeClass('row_selected');
		  		// });

		  		// console.log($(fc.aoData));
		  		// var anSelected = fnGetSelected(fc.aoData);
		  		// console.log(anSelected);
		  		// console.log(oTable.fnGetData(anSelected[0]));
		  		anSelectedId= "";
		  	}
		  	else
		  	{
		  	}

		  	console.log(anSelectedId);
		  });*/
		  
		  $('#btnSinkronisasi').on('click', function () {
			var reqSatuanKerjaId= reqBulan= reqTahun= reqStatusIntegrasi= reqCariFilter= "";
		  	// reqCariFilter= $("#reqCariFilter").val();
		  	reqBulan= $("#reqBulan").val();
		  	reqTahun= $("#reqTahun").val();
		  	// reqStatusIntegrasi= $("#reqStatusIntegrasi").val();
		  	reqSatuanKerjaId= $("#reqSatuanKerjaId").val();

			$.messager.confirm('Konfirmasi', "Apakah anda yakin untuk ambil data ?",function(r){
			if (r){
					var win = $.messager.progress({title:'Proses integrasi data.', msg:'Proses data...'});
					var jqxhr = $.get( "main/rekapitulasi_absensi_json/proses/?reqSatuanKerjaId="+reqSatuanKerjaId+"&reqBulan="+reqBulan+"&reqTahun="+reqTahun, function() {
					})
					.done(function() {
						setCariInfo();
						$.messager.progress('close');
					})
					.fail(function() {
						$.messager.progress('close');	
					});
				}
			 });

		  });
		  
		  $('#btnEditFinger').on('click', function () {
			  if(anSelectedData == "")
				  return false;				
				  
			  top.openPopup("app/loadUrl/app/sinkronisasi_rekapitulasi_absensi_add/?reqId="+anSelectedId+"&reqMode=view");
			  
			  // tutup flex dropdown => untuk versi mobile
			  $('div.flexmenumobile').hide()
			  $('div.flexoverlay').css('display', 'none')
		  });
		  
		  $("#btnCari").on("click", function () {
		  	var reqSatuanKerjaId= reqBulan= reqTahun= reqStatusIntegrasi= reqCariFilter= "";
		  	// reqCariFilter= $("#reqCariFilter").val();
		  	reqBulan= $("#reqBulan").val();
		  	reqTahun= $("#reqTahun").val();
		  	// reqStatusIntegrasi= $("#reqStatusIntegrasi").val();
		  	reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
		  	oTable.fnReloadAjax("main/rekapitulasi_absensi_json/json/?reqSatuanKerjaId="+reqSatuanKerjaId+"&reqBulan="+reqBulan+"&reqTahun="+reqTahun+"&sSearch="+reqCariFilter);
		  });

		  $("#reqCariFilter").keyup(function(e) {
		  	var code = e.which;
		  	if(code==13)
		  	{
		  		setCariInfo();
		  	}
		  });

		  $("#reqSatuanKerjaId, #reqBulan, #reqTahun").change(function() { 
		  	var reqSatuanKerjaId= reqBulan= reqTahun= reqStatusIntegrasi= reqCariFilter= "";
		  	reqCariFilter= $("#reqCariFilter").val();
		  	reqBulan= $("#reqBulan").val();
		  	reqTahun= $("#reqTahun").val();
		  	// reqStatusIntegrasi= $("#reqStatusIntegrasi").val();
		  	reqSatuanKerjaId= $("#reqSatuanKerjaId").val();

		  	document.location.href= "app/loadUrl/main/rekapitulasi_absensi/?reqSatuanKerjaId="+reqSatuanKerjaId+"&reqBulan="+reqBulan+"&reqTahun="+reqTahun;
		  	// setCariInfo();
		  });

	});
		
	function setCariInfo()
	{
		$(document).ready( function () {
		  $("#btnCari").click();      
		});
	}
</script>

<style type="text/css">
	table.display th.th_like:nth-child(1),
	table.display tbody tr td:nth-child(1){
		*width: 300px !important;
		*border: 1px solid green !important;
	}
	.row_selected td,
	.row_selected td.sorting_1{
		background-color: #005c99 !important;
		color:  #fff;

	}
</style>

</head>
<body style="overflow:hidden;">
<div id="begron"></div>
<div id="wadah">
    <div class="judul-halaman"><span>Monitoring Jam Masuk/Pulang</span></div>
    <div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">    
        <ul>
			<li>
				<a href="#" id="btnCari" style="display:none" title="Cari">Cari</a>
            	<!--<span><a id="btnAdd" title="Tambah"><img src="images/icon-tambah.png" /> Tambah</a></span>
                <span><a id="btnEditData" title="Ubah Data"><img src="images/icon-edit.png" /> Ubah Data</a> </span>-->
                <span><a id="btnSinkronisasi" title="Sinkronisasi"><img src="images/icon-edit.png" /> Sinkronisasi</a> </span>
            </li>
        </ul>
    </div>

    <div class="area-parameter no-marginbottom">
		<div id="settoggle">
			<div class="row">
				<div class="col s12">
					Bulan :
			        <select name="reqBulan" id="reqBulan">
			        <?
			        for($i=1; $i<=12; $i++)
			        {
			            $tempNama=getNameMonth($i);
			            $temp=generateZeroDate($i,2);
			        ?>
			            <option value="<?=$temp?>" <? if($temp == $reqBulan) echo 'selected'?>><?=$tempNama?></option>
			        <?
			        }
			        ?>
			        </select>
			        Tahun
			        <select name="reqTahun" id="reqTahun">
			            <? 
			            for($i=date("Y")-2; $i < date("Y")+2; $i++)
			            {
			            ?>
			            <option value="<?=$i?>" <? if($i == $reqTahun) echo 'selected'?>><?=$i?></option>
			            <?
			            }
			            ?>
			        </select>
				</div>
			</div>
			<div class="row">
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

    <table cellpadding="0" cellspacing="0" border="0" class="table table-responsive table-bordered table-striped table-hover" id="example">
    <thead>
    	<tr>
    		<?
	        for($col=0; $col<count($arrData); $col++)
	        {
	        	// $wd= $arrWidth[$col];
	        ?>
	        <th rowspan="2" class="th_like" style="<?=$style?>; width: <?=$wd?>px;"><?=$arrData[$col]?></th>
            <?
        	}
            ?>
			<?
			for($i=1; $i <= $totalhari; $i++) 
			{
			?>
			<th colspan="2" style="text-align:center"><?=$i?></th>
			<?	
			}
			?>
        </tr>
        <tr>
        	<?
	        for($col=0; $col<count($arrDataDetil); $col++)
	        {
	        ?>
	        <th class="th_like"><?=$arrDataDetil[$col]?></th>
            <?
        	}
            ?>
        </tr>
    </thead>
    </table>
</div>
<script type="text/javascript">
	// $(function(){
	// 	var tt = $('#tt').treegrid({
	// 		url: 'satuan_kerja_json/treepilih',
	// 		rownumbers: false,
	// 		pagination: false,
	// 		idField: 'ID',
	// 		treeField: 'NAMA',
	// 		onBeforeLoad: function(row,param){
	// 			if (!row) { // load top level rows
	// 			param.id = 0; // set id=0, indicate to load new page rows
	// 			}
	// 		}
	// 	});
	// });

	$(document).ready(function() {

		$('#clicktoggle').on('click', function () {
			var outer = document.getElementById('settoggle');
			if (outer.classList == "")
			{
				outer.style.maxHeight = null;
				outer.classList.add('settoggle-closed');
			} 
			else 
			{
				outer.style.maxHeight = outer.scrollHeight + 'px';
				outer.classList.remove('settoggle-closed');
			}
		});

		$('#clicktoggle').trigger('click');
	});
</script>
</body>
</html>