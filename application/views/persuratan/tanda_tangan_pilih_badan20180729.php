<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$reqBreadCrum= $this->input->get("reqBreadCrum");
$reqId= $this->input->get("reqId");
$reqMode= $this->input->get("reqMode");
$reqPegawaiId= $this->input->get("reqPegawaiId");
$reqStatusBkdUptId= $this->input->get("reqStatusBkdUptId");
$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
$reqJenis= $this->input->get("reqJenis");
$reqJenisNama= setjenisinfo($reqJenis);
$reqSatuanKerjaNama= "Semua Satuan Kerja";

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

    <?php /*?><link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script><?php */?>

	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="lib/easyui/demo/demo.css">

	<script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.min.js"></script>
	<script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.easyui.min.js"></script>

	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/media/js/jquery.dataTables.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/extensions/Responsive/js/dataTables.responsive.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/syntax/shCore.js"></script>
	<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/demo.js"></script>
    
    <script type="text/javascript" src="lib/easyui/breadcrum.js"></script>
	<script type="text/javascript" charset="utf-8">
		var oTable;
		var arrChecked = [];
		$(document).ready( function () {
		<?
		if($reqBreadCrum == ""){}
		else
		{
		?>
		setinfobreacrum("<?=$reqBreadCrum?>", "setBreacrum");
		<?
		}
		?>

        var id = -1;//simulation of id
        $(window).resize(function() {
        	console.log($(window).height());
        	$('.dataTables_scrollBody').css('height', ($(window).height() - <?=$tinggi?>));
        });
        oTable = $('#example').dataTable({ bJQueryUI: true,"iDisplayLength": 25,
        	/* UNTUK MENGHIDE KOLOM ID */
        	"aoColumns": [ 
			null,
        	null,
        	null
        	],
        	"lengthMenu": [[10, 25, 500, -1], [10, 25, 500, "All"]],
        	"bSort":false,
        	"bFilter": false,
        	"bLengthChange": false,
        	"bProcessing": true,
        	"bServerSide": true,
        	"sAjaxSource": "surat/surat_masuk_pegawai_json/json_pilih_tanda_tangan/?reqStatusBkdUptId=<?=$reqStatusBkdUptId?>&reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqSatuanKerjaId=<?=$reqSatuanKerjaId?>",
        	"sScrollX": "100%",								  
        	"sScrollXInner": "100%",
        	"sPaginationType": "full_numbers",
			"fnDrawCallback": function( oSettings ) {
			 	setChecked();
			 }

        });
        /* Click event handler */

        /* RIGHT CLICK EVENT */
        var anSelectedData = '';
        var anSelectedId = anSelectedTipeId= '';
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
					  // alert(parseInt(element.length));
					  // if(parseInt(element.length) >= 6)
					  // {
					  // 	anSelectedJabatanPilihan= element[element.length-4]+", "+element[element.length-3];
					  // }
					  // else
					  anSelectedJabatanPilihan= element[element.length-3];
					  anSelectedJabatanPilihan= String(anSelectedJabatanPilihan);
					  anSelectedJabatanPilihan= anSelectedJabatanPilihan.replace("#", ",");
					  anSelectedTipeId= element[element.length-2];
					  anSelectedId= element[element.length-1];
					  // alert(anSelectedJabatanPilihan);
					  // alert(element.length)
					  // alert(element[element.length-3]+"--"+element.length);
					});
		
		$('#example').on( 'change', 'input.editor-active', function () {
			  if($(this).prop('checked'))
			  {
				var elementRow= arrChecked.indexOf($(this).val());
				if(elementRow == -1)
				{
					arrChecked.push($(this).val());
				}
			  }
			  else
			  {
				var i = arrChecked.indexOf($(this).val());
				if(i != -1)
					arrChecked.splice(i, 1);
			  }
		  });
		  
		$("#btnCari").on("click", function () {
			var reqSatuanKerjaId= reqCariFilter= "";
			reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
			reqCariFilter= $("#reqCariFilter").val();

			oTable.fnReloadAjax("surat/surat_masuk_pegawai_json/json_pilih_tanda_tangan/?reqStatusBkdUptId=<?=$reqStatusBkdUptId?>&reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqSatuanKerjaId=<?=$reqSatuanKerjaId?>&sSearch="+reqCariFilter);
		});

		$("#reqCariFilter").keyup(function(e) {
			var code = e.which;
			if(code==13)
			{
				setCariInfo();
			}
		});
		
		$("#reqinfojabatan").hide();
		$("#reqCheckJabatan").click(function () {
			$("#reqinfojabatan").hide();
			$("#reqJabatan").val("");
			if($("#reqCheckJabatan").prop('checked')) 
			{
				$("#reqinfojabatan").show();
			}
		});

		$('#btnPilih').on('click', function () {
			  /*if($("#reqCheckAll").prop('checked')) 
			  {
				  $.messager.confirm('Konfirmasi', "Apakah Anda Yakin, broadcast ke semua ?",function(r){
						if (r){
							var reqCabangId= "";
							reqCabangId= $("#reqCabangId").val();
							$("#reqCheckAll").prop('checked', false);
							setCariInfo();
							window.parent.OpenDHTMLFull("../informasicenter/outbox_group_divisi_add.php?reqModeKirim=crm&reqCabangId="+reqCabangId, "Aplikasi Data", "600", "300");
						}
				  });
			  }
			  else
			  {*/
				  var data= "";
				  //data= getChecked();
				  data= anSelectedId;
				  if(data == "")
				  {
					mbox.alert("Pilih salah satu data terlebih dahulu.", {open_speed: 0});
					//$.messager.alert('Informasi', "Pilih salah satu data terlebih dahulu.", 'info');
					return false;
				  }
				  
				  var reqJabatan= "";
				  if($("#reqCheckJabatan").prop('checked')) 
				  {
				  	reqJabatan= $("#reqJabatan").val();

				  	if(reqJabatan == "")
				  	{
				  		mbox.alert("isikan jabatan terlebih.", {open_speed: 0});
				  		return false;
				  	}
				  }

				  var reqGantiBaris= "";
				  if($("#reqCheckGantiBaris").prop('checked')) 
				  {
				  	reqGantiBaris= 1;
				  }

				  mbox.custom({
					   message: "Apakah Anda Yakin, pilih pegawai ?",
					   options: {close_speed: 100},
					   buttons: [
						   {
							   label: 'Ya',
							   color: 'green darken-2',
							   callback: function() {
								   //parent.cetakpengantar(data);
								   // alert(reqJabatan+"--"+$("#reqCheckJabatan").prop('checked'));return false;

								   if('<?=$reqMode?>' == "rekomendasi")
								   {
								   	parent.cetakrekomendasitipejabatan(data, anSelectedTipeId, anSelectedJabatanPilihan, reqJabatan);
								   }
								   else if('<?=$reqMode?>' == "kgb1")
								   {
								   	parent.cetakpengantartipejabatan('<?=$reqPegawaiId?>', data , '<?=$reqMode?>', anSelectedTipeId, anSelectedJabatanPilihan, reqJabatan);
								   }
								   else
								   {
								   	// alert(anSelectedJabatanPilihan);
								   	parent.cetakpengantartipejabatan(reqGantiBaris, data, anSelectedTipeId, anSelectedJabatanPilihan, reqJabatan);
								   }
								   // return false;

								   parent.closeModal();
								   //console.log('do action for yes answer');
								   mbox.close();
							   }
						   },
						   {
							   label: 'Tidak',
							   color: 'grey darken-2',
							   callback: function() {
								   //console.log('do action for no answer');
								   mbox.close();
							   }
						   }
					   ]
				  });

				  /*$.messager.confirm('Konfirmasi', "Apakah Anda Yakin, pilih data ?",function(r){
						if (r){
							//parent.cetakpengantar(data);
							parent.cetakpengantartipe(data, anSelectedTipeId);
							parent.closeModal();
						}
				  });
				  */
				  
				  //alert(data);return false;
				  arrChecked= [];
				  //window.parent.OpenDHTMLFull("../informasicenter/outbox_group_divisi_add.php?reqPilihHp="+data, "Aplikasi Data", "600", "300");
			  //}
			  
		  });
			    
		$("#clicktoggle").hide();

    });

var tempinfodetilpencarian="0";
function showIconCari()
{	
	if(tempinfodetilpencarian == "0")
	{
		$("#tabpencarian").show();
		tempinfodetilpencarian= 1;
	}
	else
	{
		$("#tabpencarian").hide();
		tempinfodetilpencarian= 0;
	}
}

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

function getChecked()
{
	var data = "";
	var validasi = 0;
	for(i=0;i<arrChecked.length; i++)
	{
		value = arrChecked[i];
		arrValue = value.split("-");
		
		if(data == "")
		data = arrValue[0];
		else
		data = data + "," + arrValue[0];
	}
	return data;
}
			
function setChecked()
{
	for(i=0;i<arrChecked.length; i++)
	{
		value = arrChecked[i];
		arrValue = value.split("-");
		
		$("#reqPilihCheck"+arrValue[0]).prop('checked', true);
	}
}

function setCheckedAll()
{
	if($("#reqCheckAll").prop('checked')) {
		// do what you need here
		//alert("Checked");
		$('input[id^="reqPilihCheck"]').each(function(){
		var id= $(this).attr('id');
		id= id.replace("reqPilihCheck", "")
		$(this).prop('checked', true);
		//arrChecked.push($(this).val());
		});
	}
	else {
		// do what you need here
		//alert("Unchecked");
		$('input[id^="reqPilihCheck"]').each(function(){
		var id= $(this).attr('id');
		id= id.replace("reqPilihCheck", "")
		$(this).prop('checked', false);
		});
		
		//arrChecked = [];
	}
}
</script>

<link href="css/bluetabs.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/dropdowntabs.js"></script>

<!-- CORE CSS-->    
<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<!-- CSS style Horizontal Nav-->    
<link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
<!-- Custome CSS-->    
<link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/dataTables.materialize.css">
<?php /*?><link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/dataTables.material.min.css"><?php */?>

<link rel="stylesheet" type="text/css" href="css/gaya-monitoring.css">

<link href="lib/treeTable2/doc/stylesheets/master.css" rel="stylesheet" type="text/css" />
<link href="lib/treeTable2/src/stylesheets/jquery.treeTable.css" rel="stylesheet" type="text/css" />

<style>
.dataTables_scrollBody
{
	overflow:none !important;
}
</style>
<!--<link href="css/normalize.css" rel="stylesheet" type="text/css" />-->
<link href="lib/mbox/mbox.css" rel="stylesheet">
<script src="lib/mbox/mbox.js"></script>
<link href="lib/mbox/mbox-modif.css" rel="stylesheet">

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
							<h5 class="breadcrumbs-title">Daftar Pilihan Penandatangan Surat</h5>
							</div>
						</div>
					</div>
				</div>
				<!--breadcrumbs end-->
                
                <?php /*?><div id="bluemenu" class="bluetabs">
                    <ul>
                        <li>
                            <a id="btnCari" style="display:none" title="Cari">Cari</a>
                            <a id="btnPilih" title="Pilih">Pilih</a>
                        </li>
                    </ul>
                </div><?php */?>

				<div class="area-parameter">
					<div class="kiri">
                    	<span>
                        <a id="btnCari" style="display:none" title="Cari">Cari</a>
                        <button id="btnPilih" title="Pilih">Pilih</button>
                        </span>
                        
						<span>Show</span>
						<select>
							<option>10</option>
							<option>25</option>
							<option>50</option>
						</select>
						<span>entries</span>
					</div>

					<div class="kanan">
						<span>Search :</span>
						<input type="text" id="reqCariFilter" />
					</div>
				</div>

				<div class="area-parameter">
					<div class="kiri">
						<span>
							<input type="checkbox" id="reqCheckGantiBaris" name="reqCheckGantiBaris" value="1" />
							<label for="reqCheckGantiBaris">ganti baris</label>
						</span>
						<span style="margin-left: 5px">
							<input type="checkbox" id="reqCheckJabatan" name="reqCheckJabatan" value="1" />
							<label for="reqCheckJabatan">an.</label>
							<label for="reqJabatan">Jabatan</label>
						</span>
						<span style="margin-top: 4px" id="reqinfojabatan">
							<input placeholder type="text" id="reqJabatan" class="easyui-validatebox" />
						<span>
					</div>
				</div>

				<!--start container-->
				<div class="container" style="clear:both;">
					<div class="section">
						<table id="example" class="display mdl-data-table dt-responsive" cellspacing="0" width="95%">
							<thead>
								<tr>
                                	<th style="text-align:center; width:10%">NIP BARU</th>
									<th style="text-align:center; width:30%">NAMA</th>
									<th style="text-align:center">JABATAN / TUGAS</th>
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

	<!--materialize js-->
    <script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			$('select').material_select();
		});

		$('.materialize-textarea').trigger('autoresize');
	</script>
</body>
</html>