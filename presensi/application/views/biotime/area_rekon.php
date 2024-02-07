<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<title>Diklat</title>
<base href="<?=base_url()?>">
<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico">
<link href="css/admin.css" rel="stylesheet" type="text/css">


<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/media/js/jquery.js"></script>

<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="lib/easyui/themes/icon.css">

<script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.easyui.min.js"></script>

<link href="lib/media/themes/main_datatables.css" rel="stylesheet" type="text/css" /> 
<link href="css/begron.css" rel="stylesheet" type="text/css">  
<link href="css/bluetabs.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/dropdowntabs.js"></script>

<script type="text/javascript">
	$(document).ready( function () {
		$('#btnSinkronisasi').on('click', function () {

			$.messager.confirm('Konfirmasi', "Apakah anda yakin untuk memperbarui data ?",function(r){
				if (r){

					$.messager.progress({title:'Proses integrasi data.',msg:'Proses data...'});
					var bar = $.messager.progress('bar');
					bar.progressbar({text: ''});

					urlAjax= "bio/area_json/syncarea";
			        $.ajax({'url': urlAjax, dataType: 'json', 'success': function(datajson){
			        	if(datajson == "1")
			        	{
			        		checksync();
			        	}
			        }});
				}
			});

		});

		$("#reqStatusSync").change(function() { 
			setCariInfo();
		});

	});

	function checksync()
	{
		urlAjax= "bio/area_json/flagbelumsyncarea";
		$.ajax({'url': urlAjax, dataType: 'json', 'success': function(datajson){
			if(parseInt(datajson) > 0)
			{
				checksync();
			}
			else
			{
				urlAjax= "bio/area_json/syncpersonalarea";
				$.ajax({'url': urlAjax, dataType: 'json', 'success': function(datajson){
					$.messager.progress('close');
					setCariInfo();
				}});
			}
		}});
	}

	function setCariInfo()
	{
		reqStatusSync= "";
		reqStatusSync= $("#reqStatusSync").val();
		var tt = $('#tt').treegrid({
			url: 'siap/satuan_kerja_json/treesync?reqStatusSync='+reqStatusSync,
			onLoadSuccess:function(){
				/*var node= $('#tt').treegrid('getSelected');
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
				}*/
			}
		});
		// $(document).ready( function () {
		//   $("#btnCari").click();      
		// });
	}

	function adddata(id, mode)
	{
		document.location.href = "app/loadUrl/biotime/area_add?reqId="+id+"&reqMode="+mode;
	}
</script>

</head>
<body style="overflow:hidden;">
<div id="begron"><img src="images/bg-kanan.jpg" width="100%" height="100%" alt="Smile"></div>
<div id="wadah">
    <div class="judul-halaman">
    	<span>Area</span>
    </div>

    <div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">    
        <ul>
			<li>
                <span><a id="btnSinkronisasi" title="Sinkronisasi">Sinkronisasi</a></span>
            </li>
        </ul>
    </div>
    
    <div id="settoggle">
    	<div class="parameter-tambahan">
	    	<table style="width: 100%">
	    		<tr>
	    			<td style="width:10%">Status Sync</td>
	    			<td style="width:10px">:</td>
	    			<td>
	    				<select id="reqStatusSync">
	    					<option value="">Semua</option>
	    					<option value="1">Sudah</option>
	    					<option value="2">Belum</option>
	    				</select>
	    			</td>
	    		</tr>
	    	</table>
	    </div>

	    <div style="width: 100%">
	    	<table style="width:100%; height:84vh">
	    		<tr>
	    			<td>
	    				<table id="tt" class="easyui-treegrid" style="width:100%; height: 100%">
	    					<thead>
	    						<tr>
	    							<th field="AREA_CODE" width="10%">Area Code</th>
	    							<th field="AREA_NAMA" width="25%">Nama Area</th>
	    							<th field="NAMA" width="35%">Satuan Kerja SIAP ASN</th>
	    							<th field="STATUS_INTEGRASI_INFO" width="5%" align="center">Sync</th>
	    							<th field="STATUS_DEFAULT_INFO" width="5%" align="center">Default</th>
	    							<!-- <th field="LINK_URL_INFO" width="10%" align="center"></th> -->
	    						</tr>
	    					</thead>
	    				</table>
	    			</td>
	    		</tr>
	    	</table>
		</div>
	</div>
    
</div>

<script type="text/javascript">
$(function(){
	var tt = $('#tt').treegrid({
		url: 'siap/satuan_kerja_json/treesync',
		rownumbers: false,
		pagination: false,
		idField: 'ID',
		treeField: 'AREA_CODE',
		onBeforeLoad: function(row,param){
			if (!row) {
				param.id = 0;
			}
		}
		, rowStyler:function(index,row){
			// console.log("index:"+index);
			// console.log("row:"+row.STATUS_INTEGRASI_INFO);
			// console.log(index);
			// if (row.STATUS_INTEGRASI_INFO == "x")
			// {
				// return 'background-color:pink;color:blue;font-weight:bold;';
			// }
		}
		/*, rowStyler:function(index,row){
			// if (row.listprice>50){
				// return 'background-color:pink;color:blue;font-weight:bold;';
			// }
		}*/
	});

	// adddata("id", "mode");
});
</script>
</body>
</html>