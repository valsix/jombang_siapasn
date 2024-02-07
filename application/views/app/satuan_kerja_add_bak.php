<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('SatuanKerja');

$set= new SatuanKerja();

$reqId = $this->input->get("reqId");
$reqMode = $this->input->get("reqMode");

if($reqMode == "insert")
{
}
else
{
	$reqMode = "update";	
	$set->selectByParamsData(array("SATUAN_KERJA_ID"=>$reqId));
	$set->firstRow();
	$reqInfoDetil= $set->getField("SATUAN_KERJA_NAMA_DETIL");
	$reqNama= $set->getField("NAMA");
	$reqNamaSingkat= $set->getField("NAMA_SINGKAT");
	$reqTipeId= $set->getField("TIPE_ID");
	$reqTipeJabatanId= $set->getField("TIPE_JABATAN_ID");
	$reqJenisJabatanId= $set->getField("JENIS_JABATAN_ID");
	$reqSatuanKerjaUrutanSuratNama= $set->getField("SATUAN_KERJA_URUTAN_SURAT_NAMA");
	$reqSatuanKerjaUrutanSurat= $set->getField("SATUAN_KERJA_URUTAN_SURAT");
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Untitled Document</title>
  <base href="<?=base_url()?>" />

  <link rel="stylesheet" type="text/css" href="css/gaya.css">

  <link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
  <script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script>
  <script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
  <script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
  <script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

  <!-- AUTO KOMPLIT -->
  <link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
  <script src="lib/autokomplit/jquery-ui.js"></script>
  <script type="text/javascript">	
    $(function(){
     $('input[id^="reqSatuanKerjaUrutanSurat"]').autocomplete({
	 //$('input[id^="reqPejabatPenetap"], input[id^="reqSatker"]').autocomplete({
    source:function(request, response){
      var id= this.element.attr('id');
      var replaceAnakId= replaceAnak= urlAjax= "";

      if (id.indexOf('reqSatuanKerjaUrutanSurat') !== -1)
      {
        var element= id.split('reqSatuanKerjaUrutanSurat');
        var indexId= "reqSatuanKerjaUrutanSuratId"+element[1];
        urlAjax= "satuan_kerja_json/auto";
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
              return {desc: element['desc'], id: element['id'], label: element['label'], satuan_kerja: element['satuan_kerja']};
            });
            response(array);
          }
        }
      })
    },
    focus: function (event, ui) 
    { 
      var id= $(this).attr('id');
      if (id.indexOf('reqSatuanKerjaUrutanSurat') !== -1)
      {
        var element= id.split('reqSatuanKerjaUrutanSurat');
        var indexId= "reqSatuanKerjaUrutanSuratId"+element[1];
		  //$("#reqSatuanKerjaUrutanSurat").val("").trigger('change');
    }

    var statusht= "";
            //statusht= ui.item.statusht;
            $("#"+indexId).val(ui.item.id).trigger('change');
          },
          //minLength:3,
          autoFocus: true
        }).autocomplete( "instance" )._renderItem = function( ul, item ) {
        //return
        return $( "<li>" )
        .append( "<a>" + item.desc + "</a>" )
        .appendTo( ul );
      };

      $('#ff').form({
        url:'satuan_kerja_json/add',
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
          mbox.alert(infodata, {open_speed: 500}, window.setInterval(function() 
          {
            mbox.close();
            document.location.href= "app/loadUrl/app/satuan_kerja_add/?reqId="+rowid;
          }, 1000));
        }
         // top.frames['mainFrame'].location.reload();
       }
     });

    });
  </script>

  <!-- BOOTSTRAP CORE -->
  <link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

  <link href="lib/mbox/mbox.css" rel="stylesheet">
  <script src="lib/mbox/mbox.js"></script>
  <link href="lib/mbox/mbox-modif.css" rel="stylesheet">
</head>

<body class="bg-kanan-full">
	<div id="judul-popup">Tambah Satker</div>
	<div id="konten">
   <div id="popup-tabel2">
    <form id="ff" method="post"  novalidate enctype="multipart/form-data">
      <table class="table">
        <thead>
         <?
         if($reqId == ""){}
          else
          {
            ?>
            <tr>           
             <td colspan="3"><?=$reqInfoDetil?></td>
           </tr>  

           <?
         }
         ?>
         <tr>           
           <td style="width:20%">Nama</td>
           <td style="width:20px">:</td>
           <td>
            <input name="reqNama" style="width:90%" type="text" class="easyui-validatebox" required value="<?=$reqNama?>" />
          </td>			
        </tr>  
        <tr>           
         <td>Nama Singkat</td>
         <td>:</td>
         <td>
          <input name="reqNamaSingkat" class="easyui-validatebox" style="width:50%" type="text" value="<?=$reqNamaSingkat?>" />
        </td>			
      </tr>
      <tr>
       <td>Tipe</td>
       <td>:</td>
       <td>
         <select id="reqTipeId" name="reqTipeId">
           <option value=""></option>
           <option value="1" <? if($reqTipeId == "1") echo "selected"?>>Dinas</option>
           <option value="2" <? if($reqTipeId == "2") echo "selected"?>>UPTD Pendidikan</option>
           <option value="3" <? if($reqTipeId == "3") echo "selected"?>>SMAN</option>
           <option value="4" <? if($reqTipeId == "4") echo "selected"?>>SMPN</option>
           <option value="5" <? if($reqTipeId == "5") echo "selected"?>>SDN</option>
           <option value="6" <? if($reqTipeId == "6") echo "selected"?>>TK Swasta</option>
           <option value="7" <? if($reqTipeId == "7") echo "selected"?>>Non Satker</option>
         </select>
       </td>
     </tr>
     <tr>
       <td>Tipe Jabatan</td>
       <td>:</td>
       <td>
         <select id="reqTipeJabatanId" name="reqTipeJabatanId">
           <option value=""></option>
           <option value="1" <? if($reqTipeJabatanId == "1") echo "selected"?>>Struktural</option>
           <option value="2" <? if($reqTipeJabatanId == "2") echo "selected"?>>Tugas Tambahan</option>
           <option value="3" <? if($reqTipeJabatanId == "3") echo "selected"?>>Non Eselon</option>
           <option value="4" <? if($reqTipeJabatanId == "4") echo "selected"?>>Sekretaris</option>
         </select>
       </td>
     </tr>
     <tr>
       <td>Jenis Jabatan</td>
       <td>:</td>
       <td>
         <select id="reqJenisJabatanId" name="reqJenisJabatanId">
           <option value=""></option>
           <option value="1" <? if($reqJenisJabatanId == "1") echo "selected"?>>Pendidikan</option>
           <option value="2" <? if($reqJenisJabatanId == "2") echo "selected"?>>Kesehatan</option>
           <option value="3" <? if($reqJenisJabatanId == "3") echo "selected"?>>Lain-lain</option>
         </select>
       </td>
     </tr>
     <tr>           
       <td>Satuan Kerja Tujuan Otomatis</td>
       <td>:</td>
       <td>
        <input type="text" id="reqSatuanKerjaUrutanSurat" <?=$read?> value="<?=$reqSatuanKerjaUrutanSuratNama?>" class="easyui-validatebox" style="width:80%" />
        <input type="hidden" name="reqSatuanKerjaUrutanSurat" id="reqSatuanKerjaUrutanSuratId" value="<?=$reqSatuanKerjaUrutanSurat?>" />
      </td>			
    </tr>  
  </table>
</thead>
<input type="hidden" name="reqId" value="<?=$reqId?>" />
<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
<input type="submit" name="reqSubmit"  class="btn btn-primary" value="Submit" />
</form>
</div>
</div>
</div>
</body>
</html>