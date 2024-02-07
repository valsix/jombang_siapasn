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

$arrtabledata= array(
  array("label"=>"ID", "field"=> "PEGAWAI_ID", "width"=>"34", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"")
	, array("label"=>"Nama", "field"=> "NAMA_LENGKAP", "width"=>"", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"")
	, array("label"=>"NIP Baru", "field"=> "NIP_BARU", "width"=>"100", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"")
	, array("label"=>"Tanggal", "field"=> "TANGGAL", "width"=>"80", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"")
	, array("label"=>"Waktu", "field"=> "WAKTU", "width"=>"50", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"")
	, array("label"=>"Tipe Presensi", "field"=> "TIPE_PRESENSI", "width"=>"50", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"")
	, array("label"=>"Tipe Log", "field"=> "TIPE_LOG", "width"=>"50", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"")
	, array("label"=>"Mesin Presensi", "field"=> "MESIN_PRESENSI", "width"=>"150", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"")
	, array("label"=>"Tanggal Data Masuk", "field"=> "TANGGAL_DATA_MASUK", "width"=>"150", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"")

	// untuk dua ini kunci, data akhir id, data sebelum akhir untuk order
  , array("label"=>"sorderdefault", "field"=> "SORDERDEFAULT", "display"=>"1", "width"=>"")
  , array("label"=>"fieldid", "field"=> "PEGAWAI_ID", "display"=>"1", "width"=>"")
);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<title>Data Presensi Detil</title>
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
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/FixedColumns/js/newfixedcolumns.js"></script>
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/TableTools/js/dataTables.tableTools.min.js"></script>	

<script src="lib/js/valsix-serverside.js"></script>

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

<style type="text/css">
	.dataTables_filter {
		background: #005c99;
		width: 50%;
	}
</style>

</head>
<body style="overflow:hidden;">
<div id="begron"></div>
<div id="wadah">
    <div class="judul-halaman">
    	<span>Data Presensi Detail</span>
    	<input type="hidden" id="reqSatuanKerjaId" value="<?=$reqSatuanKerjaId?>" />
    	<label id="reqLabelSatuanKerjaNama"><?=$reqSatuanKerjaNama?></label>
	    <a id="btnPilih" style="display:none" title="Tambah"><img src="images/icon-tambah.png" />Pilih</a>
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

	<div id="parameter-tambahan" style="width: calc(100% - 40px); background: #005c99; width: 50%;">
    	<ul>
        	<li><a class="btn btn-danger btn-xs" id="btncetak" title="Cetak"><i class="fa fa-print"></i> Cetak</a></li>
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
	<!-- <div class="clearfix"></div> -->
	<div id="rightclickarea">
	    <table cellpadding="0" cellspacing="0" border="0" class="table table-responsive table-bordered table-striped table-hover" id="example">
	    <thead>
	    	<tr>
	    		<?
	        	foreach($arrtabledata as $valkey => $valitem) 
	        	{
	        		$infolabel= $valitem["label"];
	        		$infowidth= $valitem["width"];
	        	?>
	        		<th style="<?=$style?>;" width="<?=$infowidth?>px"><?=$infolabel?></th>
	        	<?
	        	}
	        	?>
	        </tr>
	    </thead>
	    </table>
	</div>
</div>

<!-- kalau multicheck -->
<input type="hidden" id="reqGlobalValidasiCheck" name="reqGlobalValidasiCheck" />
<a href="#" id="btnCari" style="display:none" title="btnCari">triggercari</a>
<a href="#" id="triggercari" style="display:none" title="triggercari">triggercari</a>

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

/*var outer = document.getElementById('settoggle');
document.getElementById('clicktoggle').addEventListener('click', function(evnt) {
if (outer.style.maxHeight){
    outer.style.maxHeight = null;
    outer.classList.add('settoggle-closed');
  }
  else {
    outer.style.maxHeight = outer.scrollHeight + 'px';
    outer.classList.remove('settoggle-closed');
  }
});

outer.style.maxHeight = outer.scrollHeight + 'px';*/
// $('#clicktoggle').trigger('click');

$(document).ready(function() {
	// $('#clicktoggle').on('click', function () {
	// 	var outer = document.getElementById('settoggle');
	// 	if (outer.classList == "")
	// 	{
	// 		outer.style.maxHeight = null;
	// 		outer.classList.add('settoggle-closed');
	// 	} 
	// 	else 
	// 	{
	// 		outer.style.maxHeight = outer.scrollHeight + 'px';
	// 		outer.classList.remove('settoggle-closed');
	// 	}
	// });

	// $('#clicktoggle').trigger('click');
});

var datanewtable;
var infotableid= "example";
var carijenis= "";
var arrdata= <?php echo json_encode($arrtabledata); ?>;
var indexfieldid= arrdata.length - 1;
var valinfoid= valinforowid='';
var datainforesponsive= datainfolengthchange= datainfosort= "1";
// datainfofilter= 
var datainfoscrollx= 100;
infoscrolly= 78;

$("#btnCari").on("click", function () {
	var reqSatuanKerjaId= reqKhususDinas= reqStatus= reqBulan= reqTahun= reqCariFilter= "";
	// reqCariFilter= $("#reqCariFilter").val();
	reqCariFilter= $('#example_filter input').val();
	reqStatus= $("#reqStatus").val();
	reqBulan= $("#reqBulan").val();
	reqTahun= $("#reqTahun").val();
	reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
	reqKhususDinas= $("#reqKhususDinas").val();

	jsonurl= "json/presensi_rekap_json/absensidetil/?reqSatuanKerjaId="+reqSatuanKerjaId+"&reqKhususDinas="+reqKhususDinas+"&reqStatus="+reqStatus+"&reqBulan="+reqBulan+"&reqTahun="+reqTahun+"&reqPencarian="+reqCariFilter;
	datanewtable.DataTable().ajax.url(jsonurl).load();
});

$("#btncetak").on("click", function () {
	var reqSatuanKerjaId= reqKhususDinas= reqStatus= reqBulan= reqTahun= reqCariFilter= "";
	// reqCariFilter= $("#reqCariFilter").val();
	reqCariFilter= $('#example_filter input').val();
	reqStatus= $("#reqStatus").val();
	reqBulan= $("#reqBulan").val();
	reqTahun= $("#reqTahun").val();
	reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
	reqKhususDinas= $("#reqKhususDinas").val();

	opUrl= "app/loadUrl/main/presensi_data_detil_excel?reqSatuanKerjaId="+reqSatuanKerjaId+"&reqKhususDinas="+reqKhususDinas+"&reqStatus="+reqStatus+"&reqBulan="+reqBulan+"&reqTahun="+reqTahun;

	newWindow = window.open(opUrl, 'download'+Math.floor(Math.random()*999999));
	newWindow.focus();
});

$("#reqSatuanKerjaId,#reqStatus,#reqBulan,#reqTahun,#reqKhususDinas").change(function() {
	setCariInfo();
});

$("#triggercari").on("click", function () {
    if(carijenis == "1")
    {
        // pencarian= $('#'+infotableid+'_filter input').val();
        pencarian= $("#reqCariFilter").val();
        // console.log(pencarian);
        datanewtable.DataTable().search( pencarian ).draw();
    }
    else
    {
        
    }
});

jQuery(document).ready(function() {
	// $('#btncetak').trigger('click');

	var jsonurl= "json/presensi_rekap_json/absensidetil?reqSatuanKerjaId=<?=$reqSatuanKerjaId?>&reqKhususDinas=<?=$reqKhususDinas?>&reqBulan=<?=$reqBulan?>&reqTahun=<?=$reqTahun?>";
	ajaxserverselectsingle.init(infotableid, jsonurl, arrdata);
});

function calltriggercari()
{
    $(document).ready( function () {
      $("#triggercari").click();      
    });
}

function setCariInfo()
{
  $(document).ready( function () {
    $("#btnCari").click();
  });
}
    
/*$("#btncetak").on("click", function () {
	var outer = document.getElementById('settoggle');
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
});*/

// $(document).ready(function() {

	// $("#settoggle").hide();
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
	// $('#clicktoggle').trigger('click');

// });
</script>

<link rel="stylesheet" href="lib/bootstrap/css/bootstrap.css" type="text/css">
<script src="lib/bootstrap/js/bootstrap.js" type="text/javascript"></script>

</body>
</html>