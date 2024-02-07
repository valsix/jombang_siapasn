<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$reqBreadCrum= $this->input->get("reqBreadCrum");

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

<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>

<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/extensions/Responsive/js/dataTables.responsive.js"></script>
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/syntax/shCore.js"></script>
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/demo.js"></script>
<script type="text/javascript" src="lib/easyui/breadcrum.js"></script>

<script type="text/javascript" charset="utf-8">
  var oTable;
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
        oTable = $('#example').dataTable({ bJQueryUI: true,"iDisplayLength": 500,
        	/* UNTUK MENGHIDE KOLOM ID */
        	"aoColumns": [ 
        	null,												 								 
        	null,												 								 
        	null												 								 
        	],
           "bSort":true,
           "bFilter": false,
           "bLengthChange": false,
           "bProcessing": true,
           "bServerSide": true,
           "sAjaxSource": "user_group_json/json",			  
           "sScrollY": ($(window).height() - <?=$tinggi?>),
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

        $("#setIconCari").hide();
		
		$('#example tbody').on( 'dblclick', 'tr', function () {
			$("#btnEdit").click();	
		});

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

        $("#btnCari").on("click", function () {
            tempJsonParsing= "";
              //setJsonFilter();
              //alert(tempJsonParsing);
              oTable.fnReloadAjax("user_group_json/json?"+tempJsonParsing);
          });

        $('#btnAdd').on('click', function () {
        	window.parent.openPopup("app/loadUrl/app/user_group_add");

		  // tutup flex dropdown => untuk versi mobile
		  $('div.flexmenumobile').hide()
		  $('div.flexoverlay').css('display', 'none')

		});

        $('#btnBkn').on('click', function () {
            if(anSelectedData == "")
                return false;

            window.parent.openPopup("app/loadUrl/app/user_group_add_bkn/?reqId="+anSelectedId);
            $('div.flexmenumobile').hide()
            $('div.flexoverlay').css('display', 'none')
        });

        $('#btnEdit').on('click', function () {
        	if(anSelectedData == "")
        		return false;				
        	window.parent.openPopup("app/loadUrl/app/user_group_add/?reqId="+anSelectedId);

		  // tutup flex dropdown => untuk versi mobile
		  $('div.flexmenumobile').hide()
		  $('div.flexoverlay').css('display', 'none')
		});

        $('#btnDelete').on('click', function () {
        	if(anSelectedData == "")
        		return false;	
        	$.messager.confirm('Konfirmasi',"Hapus data terpilih?",function(r){
        		if (r){
        			$.getJSON("user_group_json/delete/?reqId="+anSelectedId,
        				function(data){
        					$.messager.alert('Info', data.PESAN, 'info');
        					oTable.fnReloadAjax("user_group_json/json");
        				});

        		}
        	});	
        });

        $('#btnLog').on('click', function () {
            window.parent.openPopup("app/loadUrl/app/informasi_data_log?reqjson=user_group_json/log&reqjudul=Pangkat Log");

        	// tutup flex dropdown => untuk versi mobile
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

function hapusData(id, statusaktif)
{
    $.messager.defaults.ok = 'Ya';
    $.messager.defaults.cancel = 'Tidak';
    reqmode= "user_group_1";
    infoMode= "Apakah anda yakin mengaktifkan data terpilih"
    if(statusaktif == "")
    {
        reqmode= "user_group_0";
        infoMode= "Apakah anda yakin menonaktifkan data terpilih"
    }
        //alert(statusaktif);return false;
        $.messager.confirm('Konfirmasi', infoMode+" ?",function(r){
            if (r){
                var s_url= "user_group_json/delete/?reqMode="+reqmode+"&reqId="+id;
                //var request = $.get(s_url);
                $.ajax({'url': s_url,'success': function(msg){
                    if(msg == ''){}
                        else
                        {
                            setCariInfo();
                        }
                    }});
            }
        }); 
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
<link rel="stylesheet" type="text/css" href="css/gaya-monitoring.css">

<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/dataTables.materialize.css">
<?php /*?><link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/dataTables.material.min.css"><?php */?>

<link href="lib/treeTable2/doc/stylesheets/master.css" rel="stylesheet" type="text/css" />
<link href="lib/treeTable2/src/stylesheets/jquery.treeTable.css" rel="stylesheet" type="text/css" />

</head>
<body>

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
                            <h5 class="breadcrumbs-title">User Group</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <!--breadcrumbs end-->

                <div id="bluemenu" class="bluetabs">
                    <ul>
                        <li>
                            <a href="#" id="btnCari" style="display:none" title="Cari">Cari</a>
                            <a id="btnAdd" title="Tambah"><img src="images/icon-tambah.png" /> Tambah</a>
                            <a id="btnEdit" title="Ubah"><img src="images/icon-edit.png" /> Ubah</a>
                            <a id="btnBkn" title="BKN Kondisi"><img src="images/icon-edit.png" /> BKN Kondisi</a>
                            <a id="btnLog" title="Log"><img src="images/icon-lihat.png" /> Log</a>
                        </li>
                    </ul>
                </div>

                <div class="area-parameter">
                    <div class="kiri">
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
                        <input type="text">
                        <button id="clicktoggle">Filter ▾</button>
                    </div>
                </div>

                <!--start container-->
                <div class="container" style="clear:both;">
                    <div class="section">
                        <table id="example" class="display mdl-data-table dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Pengaturan</th>
                                    <th>Aksi</th>
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
    </script>

</body>
</html>