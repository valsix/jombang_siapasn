<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('MailboxKategori');

$tempSatuanKerjaBkdId= $this->SATUAN_KERJA_BKD_ID;

$set= new MailboxKategori();
$reqId = $this->input->get("reqId");

if($reqId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$set->selectByParamsMonitoring(array("A.MAILBOX_KATEGORI_ID"=>$reqId));
	$set->firstRow();
	$reqJenisPelayananId= $set->getField("JENIS_PELAYANAN_ID");
	$reqNama= $set->getField("NAMA");
	$reqSatuanKerjaNama= $set->getField("SATUAN_KERJA_NAMA");
	$reqSatuanKerjaId= $set->getField("SATUAN_KERJA_ID");
	// $reqStatus= $set->getField("STATUS_NAMA");
}

$jenis_pelayanan= new MailboxKategori();
$jenis_pelayanan->selectByParamsJenisPelayan();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Untitled Document</title>
	<base href="<?=base_url()?>" />

	<link rel="stylesheet" type="text/css" href="css/gaya.css">
	<link rel="stylesheet" href="css/admin.css" type="text/css">

	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">

	<!-- BOOTSTRAP -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!--<script src="js/jquery-1.10.2.min.js"></script>-->
	<!-- <script src="lib/bootstrap/js/jquery.min.js"></script>
	<script src="lib/bootstrap/js/bootstrap.js"></script> -->
	<link href="lib/bootstrap/css/bootstrap.css" rel="stylesheet">

	<!-- FONT AWESOME -->
	<link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

	<script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
	<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

	<!-- AUTO KOMPLIT -->
	<link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
	<script src="lib/autokomplit/jquery-ui.js"></script>

	<script type="text/javascript">	
		$(function(){
			$('#ff').form({
				url:'mailbox_kategori_json/add',
				onSubmit:function(){
					return $(this).form('validate');
				},
				success:function(data){
          			//alert(data);return false;
          			data = data.split("-");
          			rowid= data[0];
          			infodata= data[1];
         			//$.messager.alert('Info', infodata, 'info');

         			if(rowid == "xxx")
         			{
         				mbox.alert(infodata, {open_speed: 0});
         			}
         			else
         			{
         				mbox.alert(infodata, {open_speed: 500}, interval = window.setInterval(function() 
         				{
							clearInterval(interval);
         					mbox.close();
         					document.location.href= "app/loadUrl/app/mailbox_kategori_add/?reqId="+rowid;
         				}, 1000));
						$(".mbox > .right-align").css({"display": "none"});
         			}
					// top.parent.parent.parent.closePopup(data);
					top.frames['mainFrame'].location.reload();
				}
			});

			$('input[id^="reqSatuanKerjaNama"]').each(function(){
			$(this).autocomplete({
	        source:function(request, response){
	        var id= this.element.attr('id');
	        var replaceAnakId= replaceAnak= urlAjax= "";

			if (id.indexOf('reqSatuanKerjaNama') !== -1)
	        {
	          var element= id.split('reqSatuanKerjaNama');
	          var indexId= "reqSatuanKerjaId"+element[1];
	          urlAjax= "satuan_kerja_json/namasatuankerja?reqRowId=<?=$tempSatuanKerjaBkdId?>";
	        }
			
			$.ajax({
	          url: urlAjax,
	          type: "GET",
	          dataType: "json",
	          data: { term: request.term },
	          success: function(responseData){
	            if(responseData == null)
	            {
	              response(null);
	            }
	            else
	            {
	              var array = responseData.map(function(element) {
	                return {desc: element['desc'], id: element['id'], label: element['label'], satuan_kerja: element['satuan_kerja'], eselon_id: element['eselon_id'], eselon_nama: element['eselon_nama']};
	              });
	              response(array);
	            }
	          }
	        })
	      },
	      focus: function (event, ui) 
	      { 
	        var id= $(this).attr('id');

			if (id.indexOf('reqSatuanKerjaNama') !== -1)
	        {
	          var element= id.split('reqSatuanKerjaNama');
	          var indexId= "reqSatuanKerjaId"+element[1];
	        }
	        
	         $("#"+indexId).val(ui.item.id).trigger('change');
	       },
	       autoFocus: true
	       }).autocomplete( "instance" )._renderItem = function( ul, item ) {
	        //return
	        return $( "<li>" )
	        .append( "<a>" + item.desc + "</a>" )
	        .appendTo( ul );
	      }
		  ;
		  });

		});
	</script>

	<!-- BOOTSTRAP CORE -->
	<!-- <link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
 -->
	<link href="lib/mbox/mbox.css" rel="stylesheet">
	<script src="lib/mbox/mbox.js"></script>
	<link href="lib/mbox/mbox-modif.css" rel="stylesheet">
</head>

<body>

	<div class="container-fluid full-height">
		<div class="row full-height">
			<div class="col-md-12 area-form full-height">

				<div id="judul-popup">Tambah Informasi Kategori</div>
				<div id="area-form-inner">
					<form id="ff" method="post"  novalidate enctype="multipart/form-data">

						<div class="form-group">
							<label>Jenis Pelayanan</label>
							<select id="reqJenisPelayananId" name="reqJenisPelayananId">
								<option value="">Usul Umum</option>
								<?
								while($jenis_pelayanan->nextRow())
								{
								?>
								<option value="<?=$jenis_pelayanan->getField("JENIS_PELAYANAN_ID")?>" <? if($reqJenisPelayananId == $jenis_pelayanan->getField("JENIS_PELAYANAN_ID")) echo "selected"?>><?=$jenis_pelayanan->getField("NAMA")?></option>
								<?
								}
								?>
							</select>
						</div>

						<div class="form-group">
							<label>Nama</label>
							<input name="reqNama" id="reqNama" class="form-control easyui-validatebox" type="text" required value="<?=$reqNama?>" />
						</div>

						<div class="form-group">
							<label>Di tujukan ke</label>
							<input type="text" id="reqSatuanKerjaNama" name="reqSatuanKerjaNama" value="<?=$reqSatuanKerjaNama?>" class="form-control easyui-validatebox" />
                            <input type="hidden" name="reqSatuanKerjaId" id="reqSatuanKerjaId" value="<?=$reqSatuanKerjaId?>" />
						</div>
						<div class="form-group" >
							<div class="col-md-2">
								<input type="hidden" name="reqId" value="<?=$reqId?>" />
								<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
								<input type="submit" name="reqSubmit"  class="btn btn-primary" value="Submit" />
							</div>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</body>
</html>