<?
$CI =& get_instance();
$CI->checkUserLogin();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html><head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<title>Diklat</title>
<base href="<?=base_url()?>" />

<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="lib/easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="lib/easyui/demo/demo.css">

<script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.easyui.min.js"></script>

<!-- POPUP WINDOW -->
<link rel="stylesheet" href="lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script>
<link rel="stylesheet" type="text/css" href="css/bluetabs.css" />

<script type="text/javascript">
	function setCariInfo()
	{
		$('#tt').treegrid({url:'satuan_kerja_json/tree'});
	}
	
	function adddata(id, mode)
	{
		window.parent.openPopup("app/loadUrl/app/satuan_kerja_add/?reqId="+id+"&reqMode="+mode);
	}
	
	function hapusData(id, statusaktif)
	{
		$.messager.defaults.ok = 'Ya';
		$.messager.defaults.cancel = 'Tidak';
		reqmode= "satuan_kerja_1";
		infoMode= "Apakah anda yakin mengaktifkan data terpilih"
		if(statusaktif == "")
		{
			reqmode= "satuan_kerja_0";
			infoMode= "Apakah anda yakin menonaktifkan data terpilih"
		}
			
		$.messager.confirm('Konfirmasi', infoMode+" ?",function(r){
				if (r){
					var s_url= "satuan_kerja_json/delete/?reqMode="+reqmode+"&reqId="+id;
					//var request = $.get(s_url);
					$.ajax({'url': s_url,'success': function(msg){
						if(msg == ''){}
							else
							{
								reloadSelectedTree();
							}
						}});
				}
		});	
	}
	
	function iecompattest(){
	return (!window.opera && document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
	}
	
	function opendhtmlcenter(opAddress, opCaption, opWidth, opHeight)
	{
		 var width  = opWidth;
		 var height = opHeight;
		 var left   = (screen.width  - width)/4;
		 var top    = (screen.height - height)/4;
		 var params = 'width='+width+', height='+height;
		 params += ', top='+top+', left='+left;
		 params += ', directories=no';
		 params += ', location=no';
		 params += ', menubar=no';
		 params += ', resizable=no';
		 params += ', scrollbars=no';
		 params += ', status=no';
		 params += ', toolbar=no';
		divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, params); return false;
	}
	
</script>

<!-- CORE CSS-->    
<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<!-- CSS style Horizontal Nav-->    
<link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
<!-- Custome CSS-->    
<link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/dataTables.materialize.css">

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
                                <!-- =========================================== -->
                                <ol class="breadcrumb right" id="setBreacrum"></ol>

                                <h5 class="breadcrumbs-title">Satuan Kerja</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <!--breadcrumbs end-->

                <div id="bluemenu" class="bluetabs">
                    <ul>
                    	<li><a href="javascript:void(0)" title="Tambah" onClick="adddata('0', 'insert')"><img src="images/icon-tambah.png" /> Tambah</a></li>
                    </ul>
                </div>

                <!--start container-->
                <div class="container" style="clear:both;">
                    <div class="section">
                        <table id="tt" class="easyui-treegrid" style="width:100%; min-height:550px">
                            <thead>
                                <tr>
                                    <th field="NAMA" width="90%">Nama</th>
                                    <th field="LINK_URL_INFO" width="10%" align="center">Aksi</th>
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

<script>
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

function reloadSelectedTree(){
	var tt = $('#tt').treegrid({
		url: 'satuan_kerja_json/tree',
		onLoadSuccess:function(){
			var node= $('#tt').treegrid('getSelected');
			var itemSubsting= "";
			valNode= node.ID;
			panjangNode= parseInt(node.ID.length) / 3;
			//alert(panjangNode+'-'+node.ID);
			for(var i=0;i<panjangNode;i++)
			{
				
				itemSubsting= parseInt(i) + 1;
				itemSubsting= 3 * parseInt(itemSubsting);
				itemNode= valNode.substring(0, itemSubsting);
				$('#tt').treegrid('expand', itemNode);
			}
		}
	});
	//tt.treegrid('enableFilter');
}
</script>
</body>
</html>