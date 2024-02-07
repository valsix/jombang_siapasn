<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model('main/SatuanKerja');

$reqTahun= $this->input->get("reqTahun");
$reqBulan= $this->input->get("reqBulan");
$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");

$reqSatuanKerjaNama= "Semua Satuan Kerja";

$tinggi = 235;

if($reqTahun == "")	{
	$reqLastMonth = strtotime("0 months", strtotime(date("d-m-Y")));
	$reqBulan = strftime ( '%m' , $reqLastMonth );
	// $reqBulan= "11";
	$reqTahun = strftime ( '%Y' , $reqLastMonth );
}

$reqPeriode= $reqBulan.$reqTahun;

$arrData= array(
	array("label"=>"NIP Baru", "width"=>"", "display"=>"1")
	, array("label"=>"Nama<br/>NIP Baru", "width"=>"120", "display"=>"")
	, array("label"=>"Jenis Jam Kerja", "width"=>"150", "display"=>"")
	, array("label"=>"Status", "width"=>"100", "display"=>"")
	, array("label"=>"Jabatan", "width"=>"250", "display"=>"")
	, array("label"=>"OPD/Unit Kerja", "width"=>"", "display"=>"")
);
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
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/FixedColumns/js/dataTables.fixedColumns.js"></script>
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/TableTools/js/dataTables.tableTools.min.js"></script>	

<script type="text/javascript" charset="utf-8">
	var oTable;
	var json= "jadwal_json/jamkerjaawfhpegawai"; var form= "jadwal_json/jamkerjaawfhpegawaiadd";
    $(document).ready( function () {
										
        var id = -1;//simulation of id
		$(window).resize(function() {
		  console.log($(window).height());
		  $('.dataTables_scrollBody').css('height', ($(window).height() - <?=$tinggi?>));
		});
		oTable = $('#example').dataTable({ bJQueryUI: true,"iDisplayLength": 25,
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
			"bSort":true,
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
		var infoid= [];
		
		$('#example tbody').on( 'click', 'tr', function () {
	        $(this).toggleClass('selected');

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
		        // console.log(infoid);
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
		        // console.log(infoid);
		    }
	    } );

	    $("#btnPilih").on("click", function () {
	    	if(infoid=="") 
	    	{
	    		$.messager.alert('Info', "Pilih data terlebih dahulu", 'info');
				return false;
	    	}

	    	infopegawaiid= "";
	    	$.each(infoid, function( key, value ) { 
                // console.log(key+' '+value["pegawaiid"]);
                if(infopegawaiid == "")
                	infopegawaiid= value["pegawaiid"];
                else
                	infopegawaiid= infopegawaiid+","+value["pegawaiid"];
            });
	    	// return false;

	    	reqJenisJamKerja= $("#reqJenisJamKerja").val();
	    	reqJenisJamKerjaNama= $("#reqJenisJamKerjaNama").val();

	    	konfirmasi= "Apakah anda yakin merubah jenis jam kerja pegawai terpilih menjadi "+reqJenisJamKerjaNama+" ?";
	    	$.messager.confirm('Konfirmasi',konfirmasi,function(r){
	            if (r){

	            	if(reqJenisJamKerjaNama == "Reset")
	            	{
	            		$(".vmenu").hide();
	            		infoid= [];
	            		setCariInfo();
	            	}
	            	else
	            	{
	            		urllink= "main/"+form+"/?reqJenisJamKerja="+reqJenisJamKerja+"&reqPegawaiId="+infopegawaiid;
	            		method= "GET";
	            		var win = $.messager.progress({title:'Proses simpan', msg:'proses data...'});
	            		$.ajax({
		                    url: urllink,
		                    method: method
		                    /*, data: {
		                        reqJenis: JENIS,
		                        reqSatkerId: SATUAN_KERJA_ID,
		                        reqNama: NAMA
		                    }*/
		                    // dataType: 'json',
		                    , success: function (response) {
		                    	$(".vmenu").hide();
		                    	infoid= [];
		                    	setCariInfo();
		                        $.messager.progress('close');
		                    },
		                    error: function (response) {
		                    },
		                    complete: function () {
		                    }
		                });

	            		
	            		
	            	}
	            }
	        });
		});
		  
		$("#btnCari").on("click", function () {
			var reqSatuanKerjaId= reqBulan= reqTahun= reqCariFilter= "";
			// reqCariFilter= $("#reqCariFilter").val();
			reqCariFilter= $('#example_filter input').val();
			reqBulan= $("#reqBulan").val();
			reqTahun= $("#reqTahun").val();
			reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
			oTable.fnReloadAjax("main/"+json+"/?reqSatuanKerjaId="+reqSatuanKerjaId+"&reqBulan="+reqBulan+"&reqTahun="+reqTahun+"&reqPencarian="+reqCariFilter);
		});

		$("#reqSatuanKerjaId,#reqBulan,#reqTahun").change(function() {
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
    	<span>Penjadwalan Libur/AWFH</span>
    	<input type="hidden" id="reqSatuanKerjaId" value="<?=$reqSatuanKerjaId?>" />
    	<label id="reqLabelSatuanKerjaNama"><?=$reqSatuanKerjaNama?></label>
	    <a id="btnPilih" style="display:none" title="Tambah"><img src="images/icon-tambah.png" /> Pilih</a>
    </div>

    <div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">    
        <ul>
			<li>
				<a href="#" id="btnCari" style="display:none" title="Cari">Cari</a>
            	<span><a id="btnAdd" title="Tambah"><img src="images/icon-tambah.png" /> Tambah</a></span>
                <span><a id="btnEdit" title="Ubah Data"><img src="images/icon-edit.png" /> Ubah Data</a> </span>
                <span><a id="btnDelete" title="Hapus Data"><img src="images/icon-hapus.png" /> Hapus</a> </span>
            </li>
        </ul>
    </div>

    <div class="area-parameter" style="margin-top: 5px;">
		<div id="settoggle">
			<table class="table" style="width:100%; ">
				<tr>
                	<td>
                    	<label>
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
                        </label>
                        <label>
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
                        </label>
                    </td>
                </tr>
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
</body>
</html>