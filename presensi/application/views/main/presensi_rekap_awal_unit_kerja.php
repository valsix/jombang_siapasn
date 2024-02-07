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

// if(empty($reqSatuanKerjaId))
// {
// 	$reqSatuanKerjaId= "66";
// 	$reqSatuanKerjaNama= "BADAN KEPEGAWAIAN DAERAH, PENDIDIKAN DAN PELATIHAN";
// }

$infostatuskhususdinas= $this->STATUS_KHUSUS_DINAS;
$reqKhususDinas= $this->input->get("reqKhususDinas");
if($infostatuskhususdinas == "1")
{
	if(empty($reqKhususDinas))
	{
		$reqKhususDinas= "1";
	}
}

$tinggi = 185;

if($reqTahun == "")	{
	$reqLastMonth = strtotime("0 months", strtotime(date("d-m-Y")));
	$reqBulan = strftime ( '%m' , $reqLastMonth );
	// $reqBulan= "11";
	$reqTahun = strftime ( '%Y' , $reqLastMonth );
}

// $reqBulan= "12";$reqTahun= "2019";
$reqPeriode= $reqBulan.$reqTahun;
// echo $reqPeriode;exit;

$infoperiode= $reqTahun."-".$reqBulan;
$maxhari= getDay(date("Y-m-t",strtotime($infoperiode)));
// echo $maxhari;exit;

$arrData= array(
	array("label"=>"NIP Baru", "width"=>"", "display"=>"1")
	, array("label"=>"Nama<br/>NIP Baru", "width"=>"120", "display"=>"")
	, array("label"=>"Unit Kerja", "width"=>"20", "persen"=>"1", "display"=>"")
	, array("label"=>"1", "width"=>"", "display"=>"")
	, array("label"=>"2", "width"=>"", "display"=>"")
	, array("label"=>"3", "width"=>"", "display"=>"")
	, array("label"=>"4", "width"=>"", "display"=>"")
	, array("label"=>"5", "width"=>"", "display"=>"")
	, array("label"=>"6", "width"=>"", "display"=>"")
	, array("label"=>"7", "width"=>"", "display"=>"")
	, array("label"=>"8", "width"=>"", "display"=>"")
	, array("label"=>"9", "width"=>"", "display"=>"")
	, array("label"=>"10", "width"=>"", "display"=>"")
	, array("label"=>"11", "width"=>"", "display"=>"")
	, array("label"=>"12", "width"=>"", "display"=>"")
	, array("label"=>"13", "width"=>"", "display"=>"")
	, array("label"=>"14", "width"=>"", "display"=>"")
	, array("label"=>"15", "width"=>"", "display"=>"")
	, array("label"=>"16", "width"=>"", "display"=>"")
	, array("label"=>"17", "width"=>"", "display"=>"")
	, array("label"=>"18", "width"=>"", "display"=>"")
	, array("label"=>"19", "width"=>"", "display"=>"")
	, array("label"=>"20", "width"=>"", "display"=>"")
	, array("label"=>"21", "width"=>"", "display"=>"")
	, array("label"=>"22", "width"=>"", "display"=>"")
	, array("label"=>"23", "width"=>"", "display"=>"")
	, array("label"=>"24", "width"=>"", "display"=>"")
	, array("label"=>"25", "width"=>"", "display"=>"")
	, array("label"=>"26", "width"=>"", "display"=>"")
	, array("label"=>"27", "width"=>"", "display"=>"")
);

$d28= $d29= $d30= $d31= "";

if($maxhari <= 28)
{
	$d29= $d30= $d31= 1;
}
else if($maxhari <= 29)
{
	$d30= $d31= 1;
}
else if($maxhari <= 30)
{
	$d31= 1;
}

array_push($arrData, array("label"=>"28", "width"=>"", "display"=>$d28));
array_push($arrData, array("label"=>"29", "width"=>"", "display"=>$d29));
array_push($arrData, array("label"=>"30", "width"=>"", "display"=>$d30));
array_push($arrData, array("label"=>"31", "width"=>"", "display"=>$d31));
// print_r($arrData);exit();

$reqStatus= "xxx";
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
	var json= "presensi_rekap_json/rekapawalunitkerja";
	var form= "jadwal_awfh_add";
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
			"initComplete": function(settings, json) {
				reloadtree();
			},
			"lengthChange": false,
			"bSort":false,
			"bProcessing": true,
			"bServerSide": true,
			// "FixedColumns": true,
			"sAjaxSource": "main/"+json+"/?reqSatuanKerjaId=<?=$reqSatuanKerjaId?>&reqKhususDinas=<?=$reqKhususDinas?>&reqBulan=<?=$reqBulan?>&reqTahun=<?=$reqTahun?>",
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

		var fc = new $.fn.dataTable.FixedColumns( oTable, {
			leftColumns: 2
		});

		var anSelectedData = '';
		var anSelectedPosition = '';
		var anSelectedId = '';
		var infoid= [];
		
		$('#example tbody').on( 'click', 'tr', function () {
	        $(this).toggleClass('selected');
	        fc.fnUpdate();

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
	    } );

		var tipereload= "";
	    $("#btnCari").on("click", function () {
			var reqSatuanKerjaId= reqKhususDinas= reqBulan= reqTahun= reqCariFilter= "";
			// reqCariFilter= $("#reqCariFilter").val();
			reqCariFilter= $('#example_filter input').val();
			reqBulan= $("#reqBulan").val();
			reqTahun= $("#reqTahun").val();
			reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
			reqKhususDinas= $("#reqKhususDinas").val();

			if(tipereload == "1")
			{
				document.location.href = "app/loadUrl/main/presensi_rekap_awal_unit_kerja?reqSatuanKerjaId="+reqSatuanKerjaId+"&reqBulan="+reqBulan+"&reqTahun="+reqTahun;
			}
			else
			{
				oTable.fnReloadAjax("main/"+json+"/?reqSatuanKerjaId="+reqSatuanKerjaId+"&reqKhususDinas="+reqKhususDinas+"&reqBulan="+reqBulan+"&reqTahun="+reqTahun+"&reqPencarian="+reqCariFilter);
			}
		});

	    $('#btnrekapawal,#btnrekapawaldetail,#btnrekapawaldinas,#btnrekapawaldetaildinas').on('click', function () {
	    	var reqSatuanKerjaId= reqKhususDinas= reqBulan= reqTahun= reqCariFilter= "";
			// reqCariFilter= $("#reqCariFilter").val();
			reqCariFilter= $('#example_filter input').val();
			reqBulan= $("#reqBulan").val();
			reqTahun= $("#reqTahun").val();
			reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
			reqKhususDinas= $("#reqKhususDinas").val();

			var btninfoid= $(this).attr('id');
			reqMode= "1";
			if(btninfoid == "btnrekapawal" || btninfoid == "btnrekapawaldinas")
				reqMode= "";

			reqModeDinas="";
			if(btninfoid == "btnrekapawaldinas" || btninfoid == "btnrekapawaldetaildinas")
				reqModeDinas="1";

			opUrl= "app/loadUrl/main/presensi_rekap_awal_unit_kerja_excel.php?reqSatuanKerjaId="+reqSatuanKerjaId+"&reqKhususDinas="+reqKhususDinas+"&reqBulan="+reqBulan+"&reqTahun="+reqTahun+"&reqMode="+reqMode+"&reqModeDinas="+reqModeDinas;

			newWindow = window.open(opUrl, 'download'+Math.floor(Math.random()*999999));
			newWindow.focus();
		});

		$("#reqSatuanKerjaId,#reqBulan,#reqTahun,#reqKhususDinas").change(function() {
			var selectchangeinfoid= $(this).attr('id');

			tipereload= "";
			if(selectchangeinfoid == "reqBulan" || selectchangeinfoid == "reqTahun")
			{
				tipereload= "1";
			}

			setCariInfo();
		});

		$("#btnEdit").on("click", function () {
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
			else if(tempId == "btnEdit")
			{
				if(anSelectedId == ""){}
				else
				{
					tempLihat= 1;
				}
			}

			if(tempLihat == 1)
				document.location.href = "app/loadUrl/main/"+form+"?reqId="+selectid+"&reqBulan="+reqBulan+"&reqTahun="+reqTahun;

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
	.row_selected td,
	.row_selected td.sorting_1{
		background-color: #005c99 !important;
		color:  #fff;
	}

	.px{
		/*width: 50% !important;*/
	}
</style>
<link rel="stylesheet" type="text/css" href="lib/filter/filter.css">

<link href="css/dropdown.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/dropdowntabs.js"></script>

<style>
table.table.table-responsive > tbody > tr {
	border-bottom: 1px solid #dddddd;
}
table.table.table-responsive > tbody > tr > td:nth-child(2){
	width: 200px !important;
	display: block;
	border: none !important;
}
</style>

</head>
<body style="overflow:hidden;">
<div id="begron"></div>
<div id="wadah">
    <div class="judul-halaman">
    	<span>Rekap Awal Per Unit Kerja (<?=getNamePeriode($reqPeriode)?>)</span>
    	<input type="hidden" id="reqSatuanKerjaId" value="<?=$reqSatuanKerjaId?>" />
    	<label id="reqLabelSatuanKerjaNama"><?=$reqSatuanKerjaNama?></label>
    </div>

    <div class="area-parameter" style="margin-top: 5px;">
		<div id="settoggle">
			<table class="table" style="width:100%; ">
				<tr>
					<td>
						<table id="tt" class="easyui-treegrid" style="width:100%; height:300px;">
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
        	<li>
            	<div class="dropdown">
                    <button class="btn btn-info btn-xs dropdown-toggle" type="button" data-toggle="dropdown">Export
                    <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a title="Rekap Awal" id="btnrekapawal">Rekap Awal</a></li>
                        <li><a title="Rekap Awal Detail" id="btnrekapawaldetail">Rekap Awal Detail</a></li>
                        <?
                        if($infostatuskhususdinas == "1")
						{
						?>
						<li><a title="Rekap Awal Dinas" id="btnrekapawaldinas">Rekap Awal Dinas</a></li>
                        <li><a title="Rekap Awal Detail Dinas" id="btnrekapawaldetaildinas">Rekap Awal Detail Dinas</a></li>
						<?
						}
						?>
                    </ul>
                </div>
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
	        		<th class="th_like <?=$infopx?>" style="<?=$style?>;" width="<?=$infowidth?>px"><?=$infolabel?></th>
	        	<?
	        	}
	        	?>
	        </tr>
	    </thead>
	    </table>
	</div>
</div>

<script type="text/javascript">
	function reloadtree()
	{
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
	}

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