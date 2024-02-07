<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");
include_once("functions/date.func.php");
include_once("functions/dynaport.func.php");

$this->load->model('PejabatPenetap');
$this->load->model('TipePegawai');
$this->load->model('Agama');
$this->load->model('StatusPegawai');
$this->load->model('Pangkat');
$this->load->model('Eselon');
$this->load->model('Pendidikan');
$this->load->model('TingkatHukuman');

$CI =& get_instance();
$CI->checkUserLogin();

$arrField= menufield();
$arrKondisiField= optionField();
$arrOperatorKondisiField= operatorField();

$reqKondisiJson= $this->input->get("reqKondisi");
$reqKondisi= json_decode($reqKondisiJson);
// print_r($reqKondisi);exit();

$reqInfo= 1;
if($reqKondisi == ""){}
else
{
	$reqInfo= "";
	$jumlah_field= 0;
	for($i= 0; $i < count($reqKondisi->reqFieldName); $i++)
	{
		$idField= $reqKondisi->reqFieldName[$i];
		$urutanField= $reqKondisi->reqUrutan[$i];
		$arrayKey= '';
		$arrayKey= in_array_column($idField, "id", $arrField);
		// print_r($arrayKey);exit();
		if($arrayKey == ''){}
    	else
    	{
    		$index_row= $arrayKey[0];
    		$reqFieldName[$jumlah_field]= $arrField[$index_row]["nama"];
    		$reqFieldId[$jumlah_field]= $idField;
    		$reqUrutanData[$jumlah_field]= $urutanField;
    		$jumlah_field++;
    	}
	}

	$jumlah_kondisi= 0;
	if($reqKondisi->reqKondisiField !== "")
	{
		for($i= 0; $i < count($reqKondisi->reqKondisiField); $i++)
		{
			$idField= $reqKondisi->reqKondisiField[$i];
			$operatorField= $reqKondisi->reqKondisiOperasi[$i];
			$whereField= $reqKondisi->reqKondisiValue[$i];

			$reqKondisiFieldId[$jumlah_kondisi]= $idField;
			$reqOperatorData[$jumlah_kondisi]= $operatorField;
			$reqWhereData[$jumlah_kondisi]= $whereField;
			$jumlah_kondisi++;
		}
	}

}

// print_r($reqWhereData);exit();
// print_r($reqKondisi->reqFieldName);exit();

// echo "-".$reqKondisi;


if($column == '')	$tinggi = 280;
else				$tinggi = 300;

$arrPejabatPenetap= "";
$set= new PejabatPenetap();
$set->selectByParams();
while($set->nextRow())
{
	$arrPejabatPenetap[] = $set->getField("NAMA");
}
unset($set);
// print_r($arrPejabatPenetap);exit();

$arrTipePegawai= "";
$set= new TipePegawai();
$set->selectByParams();
while($set->nextRow())
{
	$arrTipePegawai[] = $set->getField("NAMA");
}
unset($set);
// print_r($arrTipePegawai);exit();

$arrTipePegawai= "";
$set= new TipePegawai();
$set->selectByParams();
while($set->nextRow())
{
	$arrTipePegawai[] = $set->getField("NAMA");
}
unset($set);
// print_r($arrTipePegawai);exit();

$arrAgama= "";
$set= new Agama();
$set->selectByParams();
while($set->nextRow())
{
	$arrAgama[] = $set->getField("NAMA");
}
unset($set);
// print_r($arrAgama);exit();

$arrStatusPegawai= "";
$set= new StatusPegawai();
$set->selectByParams();
while($set->nextRow())
{
	$arrStatusPegawai[] = $set->getField("NAMA");
}
unset($set);
// print_r($arrStatusPegawai);exit();

$arrPangkat= "";
$set= new Pangkat();
$set->selectByParams();
while($set->nextRow())
{
	$arrPangkat[] = $set->getField("KODE");
}
unset($set);
// print_r($arrPangkat);exit();

$arrEselon= "";
$set= new Eselon();
$set->selectByParams();
while($set->nextRow())
{
	$arrEselon[] = $set->getField("NAMA");
}
unset($set);
// print_r($arrEselon);exit();

$arrPendidikan= "";
$set= new Pendidikan();
$set->selectByParams();
while($set->nextRow())
{
	$arrPendidikan[] = $set->getField("NAMA");
}
unset($set);
// print_r($arrPendidikan);exit();

$arrTingkatHukuman= "";
$set= new TingkatHukuman();
$set->selectByParams();
while($set->nextRow())
{
	$arrTingkatHukuman[] = $set->getField("NAMA");
}
unset($set);
// print_r($arrTingkatHukuman);exit();

$arrCariJenisKelamin= kondisiJenisKelamin(); // print_r($arrCariJenisKelamin);exit();
$arrCariStatusNikah= kondisiStatusNikah(); // print_r($arrCariStatusNikah);exit();
$arrCariGolonganDarah= kondisiGolonganDarah(); // print_r($arrCariGolonganDarah);exit();
$arrCariCuti= kondisiCuti(); // print_r($arrCariCuti);exit();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>

	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<title>Diklat</title>
	<base href="<?=base_url()?>" />
	<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico">
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

	<script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

	<link rel="stylesheet" type="text/css" href="lib/combobox/jquery/css/jquery.combobox/style.css">
	<script type="text/javascript" src="lib/combobox/jquery/js/jquery.combobox.js"></script>

	<!-- <script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.min.js"></script>
	<script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.easyui.min.js"></script> -->

	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/media/js/jquery.dataTables.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/extensions/Responsive/js/dataTables.responsive.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/syntax/shCore.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/demo.js"></script>
    <script type="text/javascript" src="lib/easyui/breadcrum.js"></script>

	<script type="text/javascript" charset="utf-8">

	var jenis_kelamin_data = <?php echo json_encode($arrCariJenisKelamin); ?>;
	var pejabat_penetap_hukuman_data = <?php echo json_encode($arrPejabatPenetap); ?>;
	var status_kawin_data = <?php echo json_encode($arrCariStatusNikah); ?>;
	var golongan_darah_data = <?php echo json_encode($arrCariGolonganDarah); ?>;
	var cuti_data = <?php echo json_encode($arrCariCuti); ?>;
	var tipe_pegawai_data = <?php echo json_encode($arrTipePegawai); ?>;
	var agama_data = <?php echo json_encode($arrAgama); ?>;
	var status_pegawai_data = <?php echo json_encode($arrStatusPegawai); ?>;
	var golongan_data = <?php echo json_encode($arrPangkat); ?>;
	var eselon_data = <?php echo json_encode($arrEselon); ?>;
	var pendidikan_data = <?php echo json_encode($arrPendidikan); ?>;
	var tingkat_hukuman_data = <?php echo json_encode($arrTingkatHukuman); ?>;

	function trim(str)
	{
		if(!str || typeof str != 'string')
			return null;
	
		return str.replace(/^[\s]+/,'').replace(/[\s]+$/,'').replace(/[\s]{2,}/,' ');
	}

    $(document).ready( function () {
        var id = -1;//simulation of id
		$(window).resize(function() {
		  	console.log($(window).height());
		  	$('.dataTables_scrollBody').css('height', ($(window).height() - <?=$tinggi?>));
		});

        var oTable = $('#example').dataTable({ "iDisplayLength": 50,bJQueryUI: true,
		  	"aoColumns": [ 
		  		<?
		  		if($reqInfo == "1")
		  		{
		  		?>
		  		null
		  		<?
		  		}
		  		else
		  		{
					for($i=0; $i < $jumlah_field; $i++)
					{
						if($i > 0)
							echo ",";
						echo "null";
					}
				}
				?>
			],
			// "sColumns": false,
			// "bRegex": false,
			// "bSearchable": false,
			// "bSortable": false,
			// "bSort":false,
			// "searching": false,

		  	"bFilter": false,					
		  	"bProcessing": true,
		  	"bServerSide": true,
		  	"sScrollY": ($(window).height() - <?=$tinggi?>),
		  	"sScrollX": "100%",
		  	"sScrollXInner": "100%",	
	  	  	"sAjaxSource": "dynaport_json/json?reqInfo=<?=$reqInfo?>&reqKondisiJson=<?=urlencode($reqKondisiJson)?>",
		  	"sPaginationType": "full_numbers"
		});
				
		$('#example tbody tr').on('dblclick', function () {
			$("#btnEditArsip").click();	
		});														

		/* RIGHT CLICK EVENT */
		function fnGetSelected( oTableLocal )
		{
			var aReturn = new Array();
			var aTrs = oTableLocal.fnGetNodes();
			for ( var i=0 ; i<aTrs.length ; i++ )
			{
				if ( $(aTrs[i]).hasClass('row_selected') )
				{
					aReturn.push( aTrs[i] );
				}
			}
			return aReturn;
		}

		var anSelectedData = '';
		$("#example tbody").click(function(event) {
				$(oTable.fnSettings().aoData).each(function (){
					$(this.nTr).removeClass('row_selected');
				});
				$(event.target.parentNode).addClass('row_selected');
				//
				var anSelected = fnGetSelected(oTable);													
				anSelectedData = oTable.fnGetData(anSelected[0]);
		});

		$('#rightclickarea').bind('contextmenu',function(e){
		var $cmenu = $(this).next();
		$('<div class="overlay"></div>').css({left : '0px', top : '0px',position: 'absolute', width: '100%', height: '100%', zIndex: '100' }).click(function() {				
			$(this).remove();
			$cmenu.hide();
		}).bind('contextmenu' , function(){return false;}).appendTo(document.body);
		$(this).next().css({ left: e.pageX, top: e.pageY, zIndex: '101' }).show();

		return false;
		 });

		 $('.vmenu .first_li').on('click',function() {
			if( $(this).children().size() == 1 ) {
				
			}
		 });

		 $('.vmenu .inner_li span').on('click',function() {
		 	var tempId= $(this).attr('id');
		 	// console.log(tempId+"i");return false;
			// $(this).text() == 'FIP 01'
			addRowField('field', $(this).text(), tempId);

			$('.vmenu').hide();
			$('.overlay').hide();
		 });

		$(".first_li , .sec_li, .inner_li span").hover(function () {
			$(this).css({backgroundColor : '#E0EDFE' , cursor : 'pointer'});
			if ( $(this).children().size() >0 )
				$(this).find('.inner_li').show();	
				$(this).css({cursor : 'default'});
		}, 
		function () {
			$(this).css('background-color' , '#fff' );
			$(this).find('.inner_li').hide();
		});

		/* RIGHT CLICK EVENT */
		$('#rightclickarea1').bind('contextmenu',function(e){
			var $cmenu = $(this).next();
			$('<div class="overlay1"></div>').css({left : '0px', top : '0px',position: 'absolute', width: '100%', height: '100%', zIndex: '100' }).click(function() {				
				$(this).remove();
				$cmenu.hide();
			}).bind('contextmenu' , function(){return false;}).appendTo(document.body);
				$(this).next().css({ left: e.pageX, top: e.pageY, zIndex: '101' }).show();
				return false;
		});

		$('.vmenu1 .first_li1').on('click',function() {
			if( $(this).children().size() == 1 ) {
				addRowKondisi('statement');	
				$('.vmenu1').hide();
				$('.overlay1').hide();
			}
		});

		$(".first_li1 , .sec_li, .inner_li1 span").hover(function () {
			$(this).css({backgroundColor : '#E0EDFE' , cursor : 'pointer'});
			if ( $(this).children().size() >0 )
				$(this).find('.inner_li1').show();	
				$(this).css({cursor : 'default'});
		}, 
		function () {
			$(this).css('background-color' , '#fff' );
			$(this).find('.inner_li1').hide();
		});

		$("#cari-proses").submit(function(event) {

	        var ajaxRequest;
	        event.preventDefault();

	        urlAjax= "dynaport_json/cari/";
	        // $("#content").html('');

	        var varFieldName= "";
	        $('input[name^="reqFieldName"]').each(function() {
	        	varFieldName= $(this).val();
	        });
	        
	        if(varFieldName == '')
	        {
	        	$.messager.alert('Info', 'Pilih salah satu Field yg ditampilkan, terlebih dahulu.', 'info');
	        	return false;
	        }

	        var values = $(this).serialize();

	        ajaxRequest= $.ajax({
	            url: urlAjax,
	            type: "post",
	            data: values
	        });

	        ajaxRequest.done(function (response, textStatus, jqXHR){
	        	// console.log(response);return false;
	        	document.location.href= "app/loadUrl/app/dynaport?reqKondisi="+response;
	            // $("#content").html(response);
	        });

	    });

															
	});

</script>



<link href="css/bluetabs.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/dropdowntabs.js"></script>

<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/dataTables.materialize.css">

<link rel="stylesheet" type="text/css" href="css/gaya-monitoring.css">
<link rel="stylesheet" type="text/css" href="css/gaya-dynaport.css">

<link href="lib/treeTable2/doc/stylesheets/master.css" rel="stylesheet" type="text/css" />
<link href="lib/treeTable2/src/stylesheets/jquery.treeTable.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
function addRowField(tableID, nama, id) {
	// console.log(tableID + "-" + nama + "-" + id);return false;

	var kondisiCheck= "";
	$("#"+tableID+" tr").each(function()
	{
		var hiddenField = $(this).find("input[type='hidden']").val();
		// console.log(hiddenField);

		if(hiddenField == id)
		{
			kondisiCheck= 1;
			return false;
		}

	});

	if(kondisiCheck == 1)
	{
		$.messager.alert('Info', 'Data sudah dipilih.', 'info');
		return false;
	}
	
	var table = document.getElementById(tableID);
	var rowCount = table.rows.length;
	// console.log(rowCount);return false;

	var row = table.insertRow(rowCount);

	var cell2 = row.insertCell(0);
	cell2.style.fontSize = "12px";
	cell2.innerHTML = nama;	
	
	var cell3 = row.insertCell(1);
	cell3.style.fontSize = "12px";
	cell3.innerHTML = "<select name='reqUrutan[]'><option value=''></option><option value='asc'>Asc</option><option value='desc'>Desc</option></select>";
	
	var cell4 = row.insertCell(2);
	cell4.innerHTML = '<input type="hidden" name="reqFieldName[]" value="' + id + '"><a href="javascript:void(0)" onclick="deleteRowField(\'field\', this)"><img src="images/button_cancel.png" width="16" height="16" border="0" /></a>';		
}

function addRowKondisi(tableID) {
	var table = document.getElementById(tableID);
	
	var rowCount = table.rows.length;
			
	var row = table.insertRow(rowCount);
	
 	var selectoption= "";
	var cell2 = row.insertCell(0);
	selectoption= '<select name="reqKondisiField[]" id="reqKondisiField' + rowCount + '">';
	<?
	for($i_field_cari= 0; $i_field_cari < count($arrKondisiField); $i_field_cari++)
	{
		$val= $arrKondisiField[$i_field_cari]["val"];
		$nama= $arrKondisiField[$i_field_cari]["nama"];
	?>
	selectoption= selectoption+ '<option value="<?=$val?>"><?=$nama?></option>';
	<?
	}
	?>
	selectoption= selectoption+ '</select>';
	cell2.innerHTML = selectoption;
	
	var cell3 = row.insertCell(1);
	selectoption= '<select name="reqKondisiOperasi[]" id="reqKondisiOperasi' + rowCount + '">';
	<?
	for($i_field_cari= 0; $i_field_cari < count($arrOperatorKondisiField); $i_field_cari++)
	{
		$val= $arrOperatorKondisiField[$i_field_cari]["val"];
		$nama= $arrOperatorKondisiField[$i_field_cari]["nama"];
	?>
	selectoption= selectoption+ '<option value="<?=$val?>"><?=$nama?></option>';
	<?
	}
	?>
	selectoption= selectoption+ '</select>';
	cell3.innerHTML = selectoption;

	var cell4 = row.insertCell(2);
	cell4.innerHTML = "<input type='text' id='inputKondisiValue" + rowCount + "' name='reqKondisiValue[]'>";

	var cell5 = row.insertCell(3);
	cell5.innerHTML = '<a href="javascript:void(0)" onclick="deleteRowField(\'statement\', this)"><img src="images/button_cancel.png" width="16" height="16" border="0" /></a>';		

    jQuery(function () {
        jQuery('#input' + rowCount).combobox([]);
    });

    $(function(){
		$('select[id^="reqKondisiField"]').change( function(e) {
			var id= $(this).attr('id');
			// console.log(id);
			setkeydowntanggal(id);
			// varFieldName= $(this).val();
		});
	});
}

function setkeydowntanggal(id)
{
	varFieldId= $("#"+id+" option:selected").val();
	varFieldName= $("#"+id+" option:selected").text();
	varFieldName= String(varFieldName);
	rowid= String(id);
	rowid= rowid.replace("reqKondisiField", "");

	// console.log(id+"-"+varFieldName+"-"+varFieldId+"-"+rowid);
	// inputKondisiValue
	var blank = [];

	if(varFieldName.search("Tanggal") != -1 || varFieldName.search("TMT") != -1)
	{
		$("#inputKondisiValue"+rowid).keydown( function(e) {
			// console.log("");
			return format_date(e, 'inputKondisiValue'+rowid);
		});
	}
	else if(varFieldId == "Jenis Kelamin")
		$("#inputKondisiValue"+rowid).combobox(jenis_kelamin_data);
	else if(varFieldId == "Status Kawin")
		$("#inputKondisiValue"+rowid).combobox(status_kawin_data);
	else if(varFieldId == "7") //Tipe Pegawai
		$("#inputKondisiValue"+rowid).combobox(tipe_pegawai_data);
	else if(varFieldId == "6") //Agama
		$("#inputKondisiValue"+rowid).combobox(agama_data);
	else if(varFieldId == "Status Pegawai")
		$("#inputKondisiValue"+rowid).combobox(status_pegawai_data);
	else if(varFieldId == "8") //Golongan Ruang
		$("#inputKondisiValue"+rowid).combobox(golongan_data);
	else if(varFieldId == "Eselon")
		$("#inputKondisiValue"+rowid).combobox(eselon_data);
	else if(varFieldId == "Pendidikan")
		$("#inputKondisiValue"+rowid).combobox(pendidikan_data);
	else if(varFieldId == "Pejabat Penetap Hukuman")
		$("#inputKondisiValue"+rowid).combobox(pejabat_penetap_hukuman_data);
	else if(varFieldId == "Tingkat Hukuman")
		$("#inputKondisiValue"+rowid).combobox(tingkat_hukuman_data);
	else if(varFieldId == "Jenis Cuti")
		$("#inputKondisiValue"+rowid).combobox(cuti_data);
	else if(varFieldId == "Gol Darah")
		$("#inputKondisiValue"+rowid).combobox(golongan_darah_data);
	// else
	// {
	// 	$("#inputKondisiValue"+rowid).combobox(blank);
	// }

}

$(function(){

	$('select[id^="reqKondisiField"]').each(function() {
		var id= $(this).attr('id');
		setkeydowntanggal(id);
	});

	$('select[id^="reqKondisiField"]').change( function(e) {
		var id= $(this).attr('id');
		setkeydowntanggal(id);
		// console.log(id);
		// varFieldName= $(this).val();
	});
});

function deleteRowField(tableID, id) {
	
	try {
	var table = document.getElementById(tableID);
	var rowCount = table.rows.length;
	var id=id.parentNode.parentNode.rowIndex;
	
		for(var i=0; i<=rowCount; i++) {
			if(id == i) {					
				table.deleteRow(i);
			}
		}
	}catch(e) {
		alert(e);
	}
}	

  
</script>


</head>
<body>
	<!-- START MAIN -->
	<div id="main">
		<!-- START WRAPPER -->
		<div class="wrapper">
			<!-- START CONTENT -->
			<section id="content-full">

				<!--breadcrumbs start-->
				<div id="breadcrumbs-wrapper">
					<div class="container">
						<div class="row">
							<div class="col s12 m12 l12">
                            <ol class="breadcrumb right" id="setBreacrum"></ol>
							<h5 class="breadcrumbs-title">Dynaport</h5>
								<ol class="breadcrumbs">
									<li class="active">
                                    <input type="hidden" id="reqSatuanKerjaId" value="<?=$reqSatuanKerjaId?>" />
                                    <label id="reqLabelDynaport">Pencarian data secara fleksibel</label>
                                    </li>
								</ol>
							</div>
						</div>
					</div>
				</div>
				<!--breadcrumbs end-->

				<!--start container-->
				<div class="container" style="clear:both;">
					<form id="formAddNewRow" action="#" title="Add a new browser" style="width:600px;min-width:600px">
					</form>
					<form id="cari-proses" method="post" enctype="multipart/form-data"> 
					<div class="row">
						<div class="col s5" style="border: 1px solid #ff9100;">
							<div id="rightclickarea" style="height:170px; overflow:scroll">
							<table id="field" border="1" width="100%">
								<tr>
									<th>Field yg ditampilkan</th>
									<th>Urutan</th>
									<th>Aksi</th>
								</tr>
								<? 
								for($i=0; $i < $jumlah_field; $i++)
								{
								?>
								<tr>
									<td><?=$reqFieldName[$i]?></td>
									<td>
										<select name="reqUrutan[]">
											<option value="" <? if($reqUrutanData[$i] == "") { ?> selected <? } ?>></option>
					                    	<option value="asc" <? if($reqUrutanData[$i] == "asc") { ?> selected <? } ?>>Asc</option>
					                        <option value="desc" <? if($reqUrutanData[$i] == "desc") { ?> selected <? } ?>>Desc</option>
					                    </select>
									</td>
									<td>
										<input type="hidden" name="reqFieldName[]" value="<?=$reqFieldId[$i]?>">
										<a onclick="$(this).parent().parent().remove();">
											<img src="images/button_cancel.png" width="16" height="16" border="0" />
										</a>
									</td>
								</tr>
								<?	
								}
								?>
							</table>
							</div>

							<?
							function getFieldByParent($id_induk, $arrField)
							{
								$arrayKey= '';
								$arrayKey= in_array_column($id_induk, "parent_id", $arrField);

								if($arrayKey == ''){}
								else
								{
									echo '<div class="inner_li">';
									for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
									{
										$index_row= $arrayKey[$index_detil];
										$tempFieldId= $arrField[$index_row]["id"];
			                			$tempFieldParentId= $arrField[$index_row]["parent_id"];
			                			$tempFieldNama= $arrField[$index_row]["nama"];
			                			$tempFieldLink= $arrField[$index_row]["link"];
			                			$tempFieldChild= $arrField[$index_row]["child"];

										echo '<span id="'.$tempFieldId.'">'.$tempFieldNama.'</span>';
									}
									echo '</div>';
								}
							}
							?>

			                <div class="vmenu">
			                	<?
			                	$arrayKey= '';
			                	$arrayKey= in_array_column("0", "parent_id", $arrField);
			                	//print_r($arrayKey);exit;
			                	if($arrayKey == ''){}
			                	else
			                	{
			                		for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
			                		{
			                			$index_row= $arrayKey[$index_detil];
			                			$tempFieldId= $arrField[$index_row]["id"];
			                			$tempFieldParentId= $arrField[$index_row]["parent_id"];
			                			$tempFieldNama= $arrField[$index_row]["nama"];
			                			$tempFieldLink= $arrField[$index_row]["link"];
			                			$tempFieldChild= $arrField[$index_row]["child"];
			                	?>
			                	<div class="first_li">
			                		<span><?=$tempFieldNama?></span>
			                		<?
			                		if($tempFieldChild == "1")
			                			getFieldByParent($tempFieldId, $arrField);
			                		?>
			                	</div>
			                	<?
			                		}
			                	}
			                	?>

				                <!-- <div class="first_li"><span>Clear</span></div>
				                <div class="first_li"><span>Close</span></div> -->
			            	</div>

						</div>

						<div class="col s7" style="border: 1px solid #ff9100;">
							<div id="rightclickarea1" style="height:170px; overflow:scroll">
							<table id="statement" border="1" width="100%">
								<tr>
									<th>Field</th>
									<th>Operasi</th>
									<th>Isi</th>
									<th>Aksi</th>
								</tr>
								<?
				                for($i=0; $i < $jumlah_kondisi; $i++)
								{
								?>
			                    <tr>
				                    <td>
				                        <select name="reqKondisiField[]" id="reqKondisiField<?=($i + 1)?>">
				                        	<?
				                        	$valIf= $reqKondisiFieldId[$i];
				                        	for($i_field_cari= 0; $i_field_cari < count($arrKondisiField); $i_field_cari++)
				                        	{
				                        		$val= $arrKondisiField[$i_field_cari]["val"];
				                        		$nama= $arrKondisiField[$i_field_cari]["nama"];
				                        	?>
				                            	<option value="<?=$val?>" <? if($val == $valIf) { ?> selected <? } ?>><?=$nama?></option>
				                            <?
				                        	}
				                            ?>
				                        </select>
				                    </td>
				                    <td>
							            <select name="reqKondisiOperasi[]">
							            	<?
				                        	$valIf= $reqOperatorData[$i];
							            	for($i_field_cari= 0; $i_field_cari < count($arrOperatorKondisiField); $i_field_cari++)
				                        	{
				                        		$val= $arrOperatorKondisiField[$i_field_cari]["val"];
				                        		$nama= $arrOperatorKondisiField[$i_field_cari]["nama"];
				                        	?>
				                            	<option value="<?=$val?>" <? if($val == $valIf) { ?> selected <? } ?>><?=$nama?></option>
				                            <?
				                        	}
				                            ?>
				                        </select>                    
				                    </td>
				                    <td>
				                    	<?
				                    	$valIf= $reqWhereData[$i];
				                    	?>
				                    	<input type="text" id="inputKondisiValue<?=($i + 1)?>" name="reqKondisiValue[]" value="<?=$valIf?>">
				                    </td>
				                    <td>
				                    	<a onclick="$(this).parent().parent().remove();"><img src="images/button_cancel.png" width="16" height="16" border="0" /></a>
				                    </td>
			                    </tr>                
				                <?	
								}
								?>
							</table>
							</div>
							<!--RIGHT CLICK EVENT -->
				            <div class="vmenu1">                
				                <div class="first_li1"><span>Tambah Baru</span></div>				    
				            </div>        
				            <!--RIGHT CLICK EVENT -->
						</div>
					</div>
					<div class="row">
						<div class="col s6">
							<div style="float: left;">
								<button type="submit" class="btn btn-primary">
									Cari
								</button>
	                        </div>
	                        <!-- <div style="float: left; margin-left: 20px;">
		                        <a href="dynaport_cetak.php?reqFieldName=<?=$column?>&reqKondisiField=<?=$kondisifield?>&reqKondisiOperasi=<?=$kondisioperasi?>&reqKondisiValue=<?=$kondisivalue?>&reqUrutanValue=<?=$urutanvalue?>" target="_blank">
	                       	 		<img src="images/button-cetak.png" />
	                        	</a>
                        	</div> -->
                    	</div>
					</div>
					</form>
				</div>
				<!--end container-->

				<!--start container-->
				<div class="container" style="clear:both; border: 1px solid #ff9100; margin-bottom: 30px;">
					<div class="section">
						<table id="example" class="mdl-data-table dt-responsive" cellspacing="0" cellpadding="0" width="100%">
							<thead>
								<tr>
									<?
							  		if($reqInfo == "")
							  		{
										for($i=0;$i<count($reqFieldName);$i++)
										{
									?>
										<th><?=trim($reqFieldName[$i])?></th>                                      
									<?
										}
									}
									else
									{
									?> 
										<th width="0px"></th>
									<?
									}
									?>
								</tr>       
							</thead>
						</table>
					</div>
				</div>
				<!--end container-->
			</section>
			<!-- END CONTENT -->
		</div>
		<!-- END WRAPPER -->

	</div>
	<!-- END MAIN -->

</body>
</html>