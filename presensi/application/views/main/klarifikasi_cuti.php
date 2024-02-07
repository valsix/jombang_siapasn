<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('main/SatuanKerja');

$reqTahun= $this->input->get("reqTahun");
$reqBulan= $this->input->get("reqBulan");
$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");

$reqSatuanKerjaNama= "Semua Satuan Kerja";

$infostatuskhususdinas= $this->STATUS_KHUSUS_DINAS;
$reqKhususDinas= $this->input->get("reqKhususDinas");
if($infostatuskhususdinas == "1")
{
	if(empty($reqKhususDinas))
	{
		$reqKhususDinas= "1";
	}
}

if($reqTahun == "")	{
	$reqLastMonth = strtotime("0 months", strtotime(date("d-m-Y")));
	$reqBulan = strftime ( '%m' , $reqLastMonth );
	// $reqBulan= "11";
	$reqTahun = strftime ( '%Y' , $reqLastMonth );
}

$tinggi = 185;

//$reqSatuanKerjaId = $this->KODE_CABANG;

$date=$reqTahun."-".$reqBulan;
$totalhari =  getDay(date("Y-m-t",strtotime($date)));
// echo $date."--".$totalhari;exit();

$arrData= array(
	array("label"=>"Nama<br/>NIP Baru", "width"=>"")
	, array("label"=>"Unit Kerja", "width"=>"")
	, array("label"=>"Jenis Cuti", "width"=>"")
	, array("label"=>"Mulai", "width"=>"")
	, array("label"=>"Selesai", "width"=>"")
);
// print_r($arrData);exit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Klarifikasi Cuti</title>
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
<script type="text/javascript" src="lib/easyui-autocomplete/globalfunction.js"></script>

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
		oTable = $('#example').dataTable({ bJQueryUI: true,"iDisplayLength": 50,
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
			"lengthChange": false,
			"bSort":true,
			"bProcessing": true,
			"bServerSide": true,
			"FixedColumns": true,
			"sAjaxSource": "main/klarifikasi_json/cuti/?reqSatuanKerjaId=<?=$reqSatuanKerjaId?>&reqKhususDinas=<?=$reqKhususDinas?>&reqBulan=<?=$reqBulan?>&reqTahun=<?=$reqTahun?>",
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
			}

			if(tempLihat == 1)
				document.location.href = "app/loadUrl/main/klarifikasi_dinas_luar_add?reqId="+anSelectedId;

			$('div.flexmenumobile').hide()
			$('div.flexoverlay').css('display', 'none')

		});
		  
		$("#btnCari").on("click", function () {
			var reqSatuanKerjaId= reqKhususDinas= reqBulan= reqTahun= reqStatus= reqCariFilter= "";
			// reqCariFilter= $("#reqCariFilter").val();
			reqCariFilter= $('#example_filter input').val();
			reqBulan= $("#reqBulan").val();
			reqTahun= $("#reqTahun").val();
			reqStatus= $("#reqStatus").val();
			reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
			reqKhususDinas= $("#reqKhususDinas").val();

			oTable.fnReloadAjax("main/klarifikasi_json/cuti/?reqSatuanKerjaId="+reqSatuanKerjaId+"&reqKhususDinas="+reqKhususDinas+"&reqBulan="+reqBulan+"&reqTahun="+reqTahun+"&reqStatus="+reqStatus+"&reqPencarian="+reqCariFilter);
		});

		$("#reqSatuanKerjaId,#reqBulan,#reqTahun,#reqStatus,#reqKhususDinas").change(function() {
			setCariInfo();
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

</head>
<body style="overflow:hidden;">
<div id="begron"></div>
<div id="wadah">
    <div class="judul-halaman">
    	<span>Monitoring Klarifikasi Cuti</span>
    	<input type="hidden" id="reqSatuanKerjaId" value="<?=$reqSatuanKerjaId?>" />
    	<label id="reqLabelSatuanKerjaNama"><?=$reqSatuanKerjaNama?></label>
    </div>
    
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

	<div id="parameter-tambahan">
    	<ul>
    		<a href="#" id="btnCari" style="display:none" title="Cari">Cari</a>
        	<!-- <li><a class="btn btn-success btn-xs" id="btnAdd" title="Tambah"><i class="fa fa-plus-square"></i> Tambah</a></li>
        	<li><a class="btn btn-warning btn-xs" id="btnEdit" title="Ubah"><i class="fa fa-pencil-square-o"></i> Ubah</a></li>
        	<li><a class="btn btn-danger btn-xs" id="btnDelete" title="Hapus"><i class="fa fa-trash-o"></i> Hapus</a></li> -->
            <li>
            	Status :
            	<select name="reqStatus" id="reqStatus">
            		<option value="">Semua</option>
            		<option value="1" <? if($reqJenisCuti == 1) echo 'selected';?>>Cuti Tahunan</option>       
                    <option value="2" <? if($reqJenisCuti == 2) echo 'selected';?>>Cuti Besar</option>       
                    <option value="3" <? if($reqJenisCuti == 3) echo 'selected';?>>Cuti Sakit</option>       
                    <option value="4" <? if($reqJenisCuti == 4) echo 'selected';?>>Cuti Bersalin</option>        
                    <option value="5" <? if($reqJenisCuti == 5) echo 'selected';?>>Cuti Alasan Penting</option>       
                    <option value="6" <? if($reqJenisCuti == 6) echo 'selected';?>>Cuti Bersama</option>
                    <option value="7" <? if($reqJenisCuti == 7) echo 'selected';?>>CLTN</option>
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
            <?
        	if($infostatuskhususdinas == "1")
        	{
        	?>
        	<li>
        		Status Dinas :
        		<select name="reqKhususDinas" id="reqKhususDinas">
        			<option value="">Semua</option>
        			<option value="1" <? if($reqKhususDinas == "1") echo "selected";?>>Khusus Dinas</option>
        		</select>
        	</li>
        	<?
        	}
        	else
        	{
        	?>
        	<input type="hidden" name="reqKhususDinas" id="reqKhususDinas" value="" />
        	<?
        	}
        	?>
        </ul>
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

<link rel="stylesheet" href="lib/bootstrap/css/bootstrap.css" type="text/css">
<script src="lib/bootstrap/js/bootstrap.js" type="text/javascript"></script>

</body>
</html>