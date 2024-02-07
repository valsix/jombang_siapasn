<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

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

	<script type="text/javascript" charset="utf-8">
		var oTable;
		$(document).ready( function () {

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
				null,
				null
        	],
			"lengthMenu": [[10, 25, 500, -1], [10, 25, 500, "All"]],
			"bSort":false,
		    "bFilter": true,
		    "bLengthChange": true,
        	"bProcessing": true,
        	"bServerSide": true,
        	"sAjaxSource": "pegawai_json/json",
        	"sScrollX": "100%",								  
        	"sScrollXInner": "100%",
        	"sPaginationType": "full_numbers"
        });
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
					  anSelectedId = element[element.length-1];
					});

        $('#btnAdd').on('click', function () {
			newWindow = window.open("app/loadUrl/app/pegawai_add", 'Cetak');
			newWindow.focus();
        	//window.parent.createWindowMaxFull("app/loadUrl/app/pegawai_add");

				  // tutup flex dropdown => untuk versi mobile
				  $('div.flexmenumobile').hide()
				  $('div.flexoverlay').css('display', 'none')

				});

        $('#btnEdit').on('click', function () {
        	if(anSelectedData == "")
        		return false;				
			newWindow = window.open("app/loadUrl/app/pegawai_add?reqId="+anSelectedId, 'Cetak');
			newWindow.focus();
        	//window.parent.createWindowMaxFull("app/loadUrl/app/pegawai_add/?reqId="+anSelectedId);

				  // tutup flex dropdown => untuk versi mobile
				  $('div.flexmenumobile').hide()
				  $('div.flexoverlay').css('display', 'none')
				});

        $('#btnDelete').on('click', function () {
        	if(anSelectedData == "")
        		return false;	
        	$.messager.confirm('Konfirmasi',"Hapus data terpilih?",function(r){
        		if (r){
        			$.getJSON("pegawai_json/delete/?reqId="+anSelectedId,
        				function(data){
        					$.messager.alert('Info', data.PESAN, 'info');
        					oTable.fnReloadAjax("pegawai_json/json");
        				});

        		}
        	});	
        });

        $('#btnLog').on('click', function () {
        	window.parent.openPopup("app/loadUrl/app/pegawai_log");

        	// tutup flex dropdown => untuk versi mobile
        	$('div.flexmenumobile').hide()
        	$('div.flexoverlay').css('display', 'none')
        });
		
		$('#reqProgramKodeAkunCabangRekeningJsonId').combotree('setValue', "");
		var url = "satuan_kerja_json/combotree";
		$('#reqProgramKodeAkunCabangRekeningJsonId').combotree('reload', url);
		
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
	
	function clickNode(cc, id)
	{
		var opts= cc.combotree('options');
		var values= cc.combotree('getValues');
		$("#"+id).val(values);
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
    
	<link href="lib/treeTable2/doc/stylesheets/master.css" rel="stylesheet" type="text/css" />
    <link href="lib/treeTable2/src/stylesheets/jquery.treeTable.css" rel="stylesheet" type="text/css" />

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
                <h5 class="breadcrumbs-title">Pegawai</h5>
                <ol class="breadcrumbs">
                    <li class="active">Bidang Pengadaan, Profesi dan Informasi Pegawai / Sub Bidang Informasi Pegawai</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--breadcrumbs end-->
        

        <!--start container-->
        <div class="container">
          <div class="section">
            <nav>
                <div class="nav-wrapper">
                    <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
                    <ul class="left hide-on-med-and-down">
                        <li><a id="btnAdd" class="waves-effect waves-light btn" title="Tambah">Tambah</a></li>
                        <li><a id="btnEdit" class="waves-effect waves-light btn" title="Ubah">Ubah</a></li>
                        <li><a id="btnLog" class="waves-effect waves-light btn" title="Log">Log</a></li>
                    </ul>
                    <ul class="side-nav" id="mobile-demo">
                        <li><a href="sass.html">Sass</a></li>
                        <li><a href="badges.html">Components</a></li>
                        <li><a href="collapsible.html">Javascript</a></li>
                        <li><a href="mobile.html">Mobile</a></li>
                    </ul>
                </div>
            </nav>
  			
            <div class="table-bg">
              <table class="material-font striped" style="width:100%" id="link-table">
                <thead> 
                  <tr>
                    <td style="width:50px">Status</td>
                    <td style="width:15px">:</td>
                    <td style="width:50px">
                    	<select>
                        	<option>CPNS/PNS</option>
                            <option>Pensiun</option>
                        </select>
                    </td>
                  </tr>
                  <tr>
                  	<th style="width:100%" colspan="3">
                    	<table id="tt" class="easyui-treegrid" style="width:100%; height:150px">
                            <thead>
                                <tr>
                                    <th field="NAMA" width="90%">Nama</th>
                                    <th field="LINK_URL_INFO" width="10%" align="center">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </th>
                  </tr>
                </thead>
              </table>
            </div>
        	
          	<table id="example" class="display mdl-data-table dt-responsive" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th width="100px">FOTO</th>
                        <th>NIP Baru</th>
                        <th>Nama</th>
                        <th>Satuan Kerja</th>
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
  <script type="text/javascript" src="lib/materializetemplate/js/materialize.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('select').material_select();
    });

    $('.materialize-textarea').trigger('autoresize');
	
	$(function(){
		var tt = $('#tt').treegrid({
			url: 'satuan_kerja_json/tree',
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

  </script>
</body>
</html>