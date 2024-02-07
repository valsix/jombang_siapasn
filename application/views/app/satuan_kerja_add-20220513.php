<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('SatuanKerja');
$this->load->model('Eselon');

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

  $reqKode= $set->getField("KODE");
  $reqEselonId= $set->getField("ESELON_ID");
  $reqNamaPenandatangan= $set->getField("NAMA_PENANDATANGAN");
  $reqNamaJabatan= $set->getField("NAMA_JABATAN");
  $reqSatuanKerjaMutasiStatus= $set->getField("SATUAN_KERJA_MUTASI_STATUS");
  $reqSatuanKerjaIndukNama= $set->getField("SATUAN_KERJA_INDUK_NAMA");
  $reqSatuanKerjaIndukId= $set->getField("SATUAN_KERJA_INDUK_ID");

  $reqMasaBerlakuAwal= dateTimeToPageCheck($set->getField('MASA_BERLAKU_AWAL'));
  $reqMasaBerlakuAkhir= dateTimeToPageCheck($set->getField('MASA_BERLAKU_AKHIR'));

}

$eselon = new Eselon();
$eselon->selectByParams();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Untitled Document</title>
  <base href="<?=base_url()?>" />

  <link rel="stylesheet" type="text/css" href="css/gaya.css">

  <!-- MATERIAL CORE CSS-->    
  <link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

  <link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">

  <link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
  <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script>
  <script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script>
  <script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
  <script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
  <script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

  <!-- AUTO KOMPLIT -->
  <link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
  <script src="lib/autokomplit/jquery-ui.js"></script>
  <script type="text/javascript">	
    $(function(){

    $('input[id^="reqSatuanKerjaUrutanSurat"], input[id^="reqSatuanKerjaInduk"]').each(function(){
    $(this).autocomplete({
      source:function(request, response){
        var id= this.element.attr('id');
        var replaceAnakId= replaceAnak= urlAjax= "";

        if (id.indexOf('reqSatuanKerjaUrutanSurat') !== -1)
        {
          var element= id.split('reqSatuanKerjaUrutanSurat');
          var indexId= "reqSatuanKerjaUrutanSuratId"+element[1];
          urlAjax= "satuan_kerja_json/auto";
        }
        else if (id.indexOf('reqSatuanKerjaInduk') !== -1)
        {
          var element= id.split('reqSatuanKerjaInduk');
          var indexId= "reqSatuanKerjaIndukId"+element[1];
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
                return {desc: element['desc'], id: element['id'], label: element['label']};
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
        }
        else if (id.indexOf('reqSatuanKerjaInduk') !== -1)
        {
          var element= id.split('reqSatuanKerjaInduk');
          var indexId= "reqSatuanKerjaIndukId"+element[1];
        }

        $("#"+indexId).val(ui.item.id).trigger('change');
      },
      autoFocus: true
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
        //return
        return $( "<li>" )
        .append( "<a>" + item.desc  + "</a>" )
        .appendTo( ul );
    }
    ;
    });

      $('#ff').form({
        url:'satuan_kerja_json/add',
        onSubmit:function(){
         return $(this).form('validate');
       },
       success:function(data){
        // alert(data);return false;
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
			  document.location.href= "app/loadUrl/app/satuan_kerja_add/?reqId="+rowid;
          }, 1000));
		  $(".mbox > .right-align").css({"display": "none"});
        }
        top.frames['mainFrame'].location.reload();
      }
    });

    });
  </script>

  <!-- BOOTSTRAP CORE -->
 <!--  <link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
-->
<link href="lib/mbox/mbox.css" rel="stylesheet">
<script src="lib/mbox/mbox.js"></script>
<link href="lib/mbox/mbox-modif.css" rel="stylesheet">
</head>

<body class="bg-kanan-full">
  <div class="ubah-color-warna white-text" style="padding: 1em">Tambah Satker</div>
  <div id="konten">
   <div id="popup-tabel2">
    <form id="ff" method="post"  novalidate enctype="multipart/form-data">

      <?
      if($reqId == ""){
      }
      else
      {
        ?>
        <div class="row">
          <div class="input-field col s12 m10 offset-m2">
            <?=$reqInfoDetil?>
          </div>
        </div>
        <?
      }
      ?>

      <div class="row">
        <label for="reqKode" class="col s12 m2 label-control">Kode</label>
        <div class="input-field col s12 m4">
          <input name="reqKode" class="" type="text" value="<?=$reqKode?>" />
        </div>
        <label for="reqNamaSingkat" class="col s12 m2 label-control">Nama Singkat</label>
        <div class="input-field col s12 m4">
          <input name="reqNamaSingkat" class="" type="text" value="<?=$reqNamaSingkat?>" />
        </div>
      </div>

      <div class="row">
        <label for="reqNama" class="col s12 m2 label-control">Nama</label>
        <div class="input-field col s12 m10">
          <input name="reqNama" id="reqNama" class="" type="text" required value="<?=$reqNama?>" />
        </div>
      </div>

      <div class="row">
        <label for="reqNamaPenandatangan" class="col s12 m2 label-control">Nama Penandatangan</label>
        <div class="input-field col s12 m4">
          <input name="reqNamaPenandatangan" class="" type="text" value="<?=$reqNamaPenandatangan?>" />
        </div>
        <label for="reqNamaJabatan" class="col s12 m2 label-control">Nama Jabatan</label>
        <div class="input-field col s12 m4">
          <input name="reqNamaJabatan" class="" type="text" value="<?=$reqNamaJabatan?>" />
        </div>
      </div>

      <div class="row">
        <label for="reqMasaBerlakuAwal" class="col s12 m2 label-control">Masa Berlaku Awal</label>
        <div class="input-field col s12 m4">
         <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqMasaBerlakuAwal" id="reqMasaBerlakuAwal"  value="<?=$reqMasaBerlakuAwal?>" maxlength="10" onKeyDown="return format_date(event,'reqMasaBerlakuAwal');"/>
        </div>
        <label for="reqMasaBerlakuAkhir" class="col s12 m2 label-control">Masa Berlaku Akhir</label>
        <div class="input-field col s12 m4">
         <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqMasaBerlakuAkhir" id="reqMasaBerlakuAkhir"  value="<?=$reqMasaBerlakuAkhir?>" maxlength="10" onKeyDown="return format_date(event,'reqMasaBerlakuAkhir');"/>
        </div>
      </div>

      <div class="row">
        <label for="reqTipeId" class="col s12 m2 label-control">Eselon</label>
        <div class="input-field col s12 m4">
          <select id="reqEselonId" name="reqEselonId">
            <option value=""></option>
            <?
			while($eselon->nextRow())
			{
            ?>
            <option value="<?=$eselon->getField("ESELON_ID")?>" <? if($reqEselonId == $eselon->getField("ESELON_ID")) echo "selected"?>><?=$eselon->getField("NAMA")?></option>
            <?
			}
            ?>
          </select>
        </div>
        <label for="reqTipeId" class="col s12 m2 label-control">Tipe</label>
        <div class="input-field col s12 m4">
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
        </div>
      </div>

      <div class="row">
        <label for="reqTipeJabatanId" class="col s12 m2 label-control">Tipe Jabatan</label>
        <div class="input-field col s12 m4">
          <select id="reqTipeJabatanId" name="reqTipeJabatanId">
            <option value=""></option>
            <option value="1" <? if($reqTipeJabatanId == "1") echo "selected"?>>Struktural</option>
            <option value="2" <? if($reqTipeJabatanId == "2") echo "selected"?>>Tugas Tambahan</option>
            <option value="3" <? if($reqTipeJabatanId == "3") echo "selected"?>>Non Eselon</option>
            <option value="4" <? if($reqTipeJabatanId == "4") echo "selected"?>>Sekretaris</option>
          </select>
        </div>
        <label for="reqJenisJabatanId" class="col s12 m2 label-control">Jenis Jabatan</label>
        <div class="input-field col s12 m4">
          <select id="reqJenisJabatanId" name="reqJenisJabatanId">
            <option value=""></option>
            <option value="1" <? if($reqJenisJabatanId == "1") echo "selected"?>>Pendidikan</option>
            <option value="2" <? if($reqJenisJabatanId == "2") echo "selected"?>>Kesehatan</option>
            <option value="3" <? if($reqJenisJabatanId == "3") echo "selected"?>>Lain-lain</option>
          </select>
        </div>
      </div>

      <div class="row">
        <label for="reqNama" class="col s12 m2 label-control">Satuan Kerja Tujuan Otomatis</label>
        <div class="input-field col s12 m10">
          <input type="text" id="reqSatuanKerjaUrutanSurat" <?=$read?> value="<?=$reqSatuanKerjaUrutanSuratNama?>" class="" />
          <input type="hidden" name="reqSatuanKerjaUrutanSurat" id="reqSatuanKerjaUrutanSuratId" value="<?=$reqSatuanKerjaUrutanSurat?>" />
        </div>
      </div>

      <div class="row">
        <label for="reqSatuanKerjaIndukId" class="col s12 m2 label-control">Satuan Kerja Induk</label>
        <div class="input-field col s12 m4">
          <input type="text" id="reqSatuanKerjaInduk" <?=$read?> value="<?=$reqSatuanKerjaIndukNama?>" class="" />
          <input type="hidden" name="reqSatuanKerjaIndukId" id="reqSatuanKerjaIndukId" value="<?=$reqSatuanKerjaIndukId?>" />
        </div>
        <label for="reqSatuanKerjaMutasiStatus" class="col s12 m2 label-control">Status Lihat Mutasi</label>
        <div class="input-field col s12 m4">
          <select id="reqSatuanKerjaMutasiStatus" name="reqSatuanKerjaMutasiStatus">
            <option value="1" <? if($reqSatuanKerjaMutasiStatus == "1") echo "selected"?>>Ya</option>
            <option value="" <? if($reqSatuanKerjaMutasiStatus == "2") echo "selected"?>>Tidak</option>
          </select>
        </div>
      </div>

      <div class="row">
        <label for="reqKonversi" class="col s12 m2 label-control">Konversi</label>
        <div class="input-field col s12 m10">
          <input name="reqKonversi" class="" type="text" value="<?=$reqKonversi?>" />
        </div>
      </div>

      <div class="row">
        <label for="reqIDSapk" class="col s12 m2 label-control">ID Sapk</label>
        <div class="input-field col s12 m10">
          <input name="reqIDSapk" class="" type="text" value="<?=$reqIDSapk?>" />
        </div>
      </div>

      <div class="row">
        <div class="input-field col s12 m3 offset-m2">
          <input type="hidden" name="reqId" value="<?=$reqId?>" />
          <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
          <input type="submit" name="reqSubmit"  class="btn green" value="Submit" />
        </div>
      </div>

    </form>
  </div>

  <!-- <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script> -->
  <script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>
  <script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>
  <script type="text/javascript">
    $(document).ready( function () {
      $('select').material_select();
    });
  </script>
</body>
</html>