<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model('main/SatuanKerja');

$reqTahun= $this->input->get("reqTahun");
$reqBulan= $this->input->get("reqBulan");
$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
$reqSatuanKerjaNama= "Semua Satuan Kerja";
$reqSatuanKerjaId= "66";
$reqSatuanKerjaNama= "BADAN KEPEGAWAIAN DAERAH, PENDIDIKAN DAN PELATIHAN";

$tinggi = 235;

if($reqTahun == "")	{
	$reqLastMonth = strtotime("0 months", strtotime(date("d-m-Y")));
	$reqBulan = strftime ( '%m' , $reqLastMonth );
	// $reqBulan= "11";
	$reqTahun = strftime ( '%Y' , $reqLastMonth );
}

$reqBulan= "12";$reqTahun= "2019";
$reqPeriode= $reqBulan.$reqTahun;
// echo $reqPeriode;exit;

$arrData= array(
	array("label"=>"ID", "width"=>"34", "display"=>"")
	, array("label"=>"Nama", "width"=>"", "display"=>"")
	, array("label"=>"NIP Baru", "width"=>"100", "display"=>"")
	, array("label"=>"Tanggal", "width"=>"80", "display"=>"")
	, array("label"=>"Waktu", "width"=>"50", "display"=>"")
	, array("label"=>"Tipe Presensi", "width"=>"50", "display"=>"")
	, array("label"=>"Tipe Log", "width"=>"50", "display"=>"")
	, array("label"=>"Mesin Presensi", "width"=>"50", "display"=>"")
	, array("label"=>"Tanggal Data Masuk", "width"=>"80", "display"=>"")
);
// print_r($arrData);exit();

// $reqStatus= "xxx";
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
	var json= "presensi_rekap_json/absensidetil";
	// var form= "presensi_rekap_awal_pegawai_add";
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
			"sAjaxSource": "main/"+json+"/?reqSatuanKerjaId=<?=$reqSatuanKerjaId?>&reqBulan=<?=$reqBulan?>&reqTahun=<?=$reqTahun?>",
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
			},
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
				infonip= aData[0];
				checkindex= infoid.findIndex(function(row){return row.nip == infonip;});
				// console.log(infoid);
				if(checkindex >= 0)
				{
					$($(nRow).children()).attr('class', 'dataselected');
				}
			}
		});

		var anSelectedData = '';
		var anSelectedPosition = '';
		var anSelectedId = '';
		var infoid= [];
		
		/*$('#example tbody').on( 'click', 'tr', function () {
	        $(this).toggleClass('selected');
	        // fc.fnUpdate();

	        var rowdata = $(this).closest('tr').index();
	        if ( $(this).hasClass('selected') )
	        {
		        var aReturn = new Array();
				var aTrs = oTable.fnGetNodes();
		        anSelectedData= oTable.fnGetData(aTrs[rowdata]);
		        // console.log(anSelectedData);

		        var infodetil= {};
		        infodetil.nip= anSelectedData[0];
		        infodetil.pegawaiid= anSelectedData[anSelectedData.length-1];
		        infoid.push(infodetil);
		        anSelectedId =infoid;
		        // console.log(anSelectedId);
		    }
		    else
		    {
		    	var aReturn = new Array();
				var aTrs = oTable.fnGetNodes();
				aReturn.push( aTrs[rowdata] );
		    	var anSelected = aReturn;
		    	anSelectedData= oTable.fnGetData(anSelected[rowdata]);

		    	infonip= anSelectedData[rowdata];
		    	if(rowdata > 0)
		    	{
		    		infonip= infonip[0];
		    	}

		        checkindex= infoid.findIndex(function(row){return row.nip == infonip;});
		        delete infoid[checkindex];

		        infoid= infoid.filter(function (el) {
		        	return el != null;
		        });
		        anSelectedId =infoid;
		        // console.log(anSelectedId);
		    }
	    } );*/

	    $("#btnCari").on("click", function () {
			var reqSatuanKerjaId= reqStatus= reqBulan= reqTahun= reqCariFilter= "";
			// reqCariFilter= $("#reqCariFilter").val();
			reqCariFilter= $('#example_filter input').val();
			reqStatus= $("#reqStatus").val();
			reqBulan= $("#reqBulan").val();
			reqTahun= $("#reqTahun").val();
			reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
			oTable.fnReloadAjax("main/"+json+"/?reqSatuanKerjaId="+reqSatuanKerjaId+"&reqStatus="+reqStatus+"&reqBulan="+reqBulan+"&reqTahun="+reqTahun+"&reqPencarian="+reqCariFilter);
		});

		$("#reqSatuanKerjaId,#reqStatus,#reqBulan,#reqTahun").change(function() {
			setCariInfo();
		});

		$("#btnlihat").on("click", function () {
			// console.log(anSelectedId.length);return false;
			var selectid= [];
			for (var i = 0; i < anSelectedId.length; i++) {
				selectid.push(anSelectedId[i].pegawaiid);
			}
			// console.log(selectid);return false;

			var tempId= $(this).attr('id');
			var tempLihat= "";
			var reqSatuanKerjaId= reqBulan= reqTahun= "";
			reqBulan= $("#reqBulan").val();
			reqTahun= $("#reqTahun").val();
			reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
			if(tempId == "btnAdd")
			{
				anSelectedId= "";
				tempLihat= 1;
			}
			else if(tempId == "btnlihat")
			{
				if(anSelectedId == ""){}
				else
				{
					tempLihat= 1;
				}
			}

			if(tempLihat == 1)
				document.location.href = "app/loadUrl/main/"+form+"?reqId="+selectid;

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

	function calltreeid(id, nama)
	{
		$("#reqLabelSatuanKerjaNama").text(nama);
		$("#reqSatuanKerjaId").val(id);
		setCariInfo();
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
<link rel="stylesheet" type="text/css" href="lib/filter/filter.css">

<link href="css/dropdown.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/dropdowntabs.js"></script>

</head>
<body style="overflow:hidden;">
<div id="begron"></div>
<div id="wadah">
    <div class="judul-halaman">
    	<span>Data Presensi Detail</span>
    	<input type="hidden" id="reqSatuanKerjaId" value="<?=$reqSatuanKerjaId?>" />
    	<label id="reqLabelSatuanKerjaNama"><?=$reqSatuanKerjaNama?></label>
	    <a id="btnPilih" style="display:none" title="Tambah"><img src="images/icon-tambah.png" /> Pilih</a>
		<a href="#" id="btnCari" style="display:none" title="Cari">Cari</a>
    </div>

    <!-- <div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">    
        <ul>
			<li>
            </li>
        </ul>
    </div> -->

    <div class="area-parameter" style="margin-top: 5px;">
		<div id="settoggle">
			<table class="table" style="width:100%; ">
				<tr>
					<td>
						<table id="tt" class="easyui-treegrid" style="width:100%; height:250px;">
							<thead>
								<tr>
									<th field="NAMA" width="100%">Nama</th>
								</tr>
							</thead>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<!--<div style="padding:7px 10px 0px; box-sizing:border-box; clear:both; color:#FFF; border: 1px solid red;">
		<span style=""><a style="text-decoration: none; padding: 3px 7px; color: #2d2b2b; 	background:rgba(255,255,255,0.3); 	border:1px solid rgba(255,255,255,0.3); 	display:inline-block; 	margin-right:4px;" id="btnlihat" title="Lihat"> Cetak</a></span>
	</div>-->

	<div id="parameter-tambahan">
    	<ul>
        	<li><a class="btn btn-danger btn-xs" id="btnlihat" title="Lihat"><i class="fa fa-print"></i> Cetak</a></li>
            <li>
            	<div class="dropdown">
                    <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">Dropdown Example
                    <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a href="#">HTML</a></li>
                        <li><a href="#">CSS</a></li>
                        <li><a href="#">JavaScript</a></li>
                    </ul>
                </div>
            </li>
            <li><a class="btn btn-info btn-xs" id="btnlihat" title="Lihat">Contoh Tombol Lain</a></li>
            <li>
            	Status :
                <select name="reqStatus" id="reqStatus">
                    <option value="xxx">Semua</option>
                    <option value="" selected>Aktif</option>
                    <option value="1">Tidak Aktif</option>
                </select>
            </li>
            <li>
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
            </li>
            <li>
            	Tahun :
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
            </li>
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

<script type="text/javascript">
	$(function(){
		var tt = $('#tt').treegrid({
			url: 'siap/satuan_kerja_json/treepilih',
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

<link rel="stylesheet" href="lib/bootstrap/css/bootstrap.css" type="text/css">
<script src="lib/bootstrap/js/bootstrap.js" type="text/javascript"></script>

</body>
</html>