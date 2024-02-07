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

$arrData= array(
	array("label"=>"PEGAWAI ID", "width"=>"")
	, array("label"=>"PANGKAT RIWAYAT ID", "width"=>"")
	, array("label"=>"JABATAN TAMBAHAN ID", "width"=>"")
	, array("label"=>"NAMA", "width"=>"100")
	, array("label"=>"NIP", "width"=>"")
	, array("label"=>"JABATAN", "width"=>"")
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
			"aoColumns": [
            <?
			for($col=0; $col<3; $col++)
			{
				if($col == 0){}
				else
					echo ",";
				?>
				{
					bVisible: false
				}
				<?
			}
			?>
			<?
			for($col=3; $col<count($arrData); $col++)
			{
				// if($col == 3){}
				// else
					echo ",";
				?>
				null
				<?
			}
			?>
			],
			"bSort":true,
			"bProcessing": true,
			"bServerSide": true,
			"FixedColumns": true,
			"sAjaxSource": "main/popup_json/pegawai/?reqSatuanKerjaId=<?=$reqSatuanKerjaId?>&reqBulan=<?=$reqBulan?>&reqTahun=<?=$reqTahun?>",
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
		// var anSelectedId = '';
		var anSelectedDownload = '';
		var anSelectedPosition = '';	

		var arrPegawaiId= [];
		var arrPangkatId= [];
		var arrJabatanId= [];
		var arrNama= [];
		var arrNip= [];
		var arrJabatan= [];

		var allData=[];
		  			  
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
	        $(this).toggleClass('selected');

	        var anSelected = fnGetSelected(oTable);	

            for (var i = 0; i < anSelected.length; i++) {
            	anSelectedData = oTable.fnGetData(anSelected[i]);
                arrPegawaiId[i]= anSelectedData[0];
                arrPangkatId[i]= anSelectedData[1];
                arrJabatanId[i]= anSelectedData[2];
                arrNama[i]= anSelectedData[3];
                arrNip[i]= anSelectedData[4];
                arrJabatan[i]= anSelectedData[5];
            }

            if (anSelected.length !== arrPegawaiId.length) 
            { 
                arrPegawaiId= [];
                arrPangkatId= [];
                arrJabatanId= [];
                arrNama= [];
                arrNip= [];
                arrJabatan= []; 
                for (var i = 0; i < anSelected.length; i++) 
                {
                	anSelectedData = oTable.fnGetData(anSelected[i]);
                    arrPegawaiId[i]= anSelectedData[0];
	                arrPangkatId[i]= anSelectedData[1];
	                arrJabatanId[i]= anSelectedData[2];
	                arrNama[i]= anSelectedData[3];
	                arrNip[i]= anSelectedData[4];
	                arrJabatan[i]= anSelectedData[5];
                }
            }
            // console.log(anSelected);

            allData["pegawai_id"]= arrPegawaiId;
            allData["pangkat_id"]= arrPangkatId;
            allData["jabatan_id"]= arrJabatanId;
            allData["nama"]= arrNama;
            allData["nip"]= arrNip;
            allData["jabatan"]= arrJabatan;
	        console.log(allData);
	    } );

		// $('#example tbody').on( 'click', 'tr', function () {
		// 	$("#example tr").removeClass('selected');
		// 	$(".DTFC_Cloned tr").removeClass("selected");
		// 	var row = $(this);
		// 	var rowIndex = row.index() + 1;

		// 	if (row.parent().parent().hasClass("DTFC_Cloned")) {
		// 		$("#example tr:nth-child(" + rowIndex + ")").addClass("selected");;
		// 	} else {
		// 		$(".DTFC_Cloned tr:nth-child(" + rowIndex + ")").addClass("selected");
		// 	}

		// 	row.addClass("selected");
		// 	var anSelected = fnGetSelected(oTable);	
		// 	anSelectedData = String(oTable.fnGetData(anSelected[0]));

		// 	var element = anSelectedData.split(',');
		// 	anSelectedId = element[element.length-1];
		// 	console.log(anSelectedData);
		// });

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
			}

			if(tempLihat == 1)
				document.location.href = "app/loadUrl/main/klarifikasi_dinas_luar_add?reqId="+anSelectedId;

			$('div.flexmenumobile').hide()
			$('div.flexoverlay').css('display', 'none')

		});
		  
		$("#btnCari").on("click", function () {
			var reqSatuanKerjaId= reqBulan= reqTahun= reqStatusIntegrasi= reqCariFilter= "";
			// reqCariFilter= $("#reqCariFilter").val();
			reqCariFilter= $('#example_filter input').val();
			reqBulan= $("#reqBulan").val();
			reqTahun= $("#reqTahun").val();
			// reqStatusIntegrasi= $("#reqStatusIntegrasi").val();
			reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
			oTable.fnReloadAjax("main/popup_json/pegawai/?reqSatuanKerjaId="+reqSatuanKerjaId+"&reqBulan="+reqBulan+"&reqTahun="+reqTahun+"&sSearch="+reqCariFilter);
		});

		// $("#reqCariFilter").keyup(function(e) {
		// 	var code = e.which;
		// 	if(code==13)
		// 	{
		// 		setCariInfo();
		// 	}
		// });

		$("#reqSatuanKerjaId, #reqBulan, #reqTahun").change(function() { 
			var reqSatuanKerjaId= reqBulan= reqTahun= reqStatusIntegrasi= reqCariFilter= "";
			reqCariFilter= $("#reqCariFilter").val();
			reqBulan= $("#reqBulan").val();
			reqTahun= $("#reqTahun").val();
			// reqStatusIntegrasi= $("#reqStatusIntegrasi").val();
			reqSatuanKerjaId= $("#reqSatuanKerjaId").val();

			// document.location.href= "app/loadUrl/main/klarifikasi_dinas_luar/?reqSatuanKerjaId="+reqSatuanKerjaId+"&reqBulan="+reqBulan+"&reqTahun="+reqTahun;
			setCariInfo();
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
    <div class="judul-halaman"><span>Monitoring Klarifikasi Dinas Luar</span></div>
    <div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">    
        <ul>
			<li>
				<a href="#" id="btnCari" style="display:none" title="Cari">Cari</a>
            	<span><a id="btnPilih" title="Tambah"><img src="images/icon-tambah.png" /> Pilih</a></span>
                <!-- <span><a id="btnEdit" title="Ubah Data"><img src="images/icon-edit.png" /> Ubah Data</a> </span>
                <span><a id="btnDelete" title="Hapus Data"><img src="images/icon-hapus.png" /> Hapus</a> </span> -->
            </li>
        </ul>
    </div>

    <div class="area-parameter">
		<div id="settoggle">
			<table class="table" style="width:100%; ">
				<tr>
					<td style="width: 10%">Bulan</td>
					<td style="width: 5px">:</td>
					<td style="width: 15%">
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
					</td>
					<td style="width: 10%">Tahun</td>
					<td style="width: 5px">:</td>
					<td>
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
					</td>
				</tr>
				<tr>
					<td colspan="6">
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
</body>
</html>