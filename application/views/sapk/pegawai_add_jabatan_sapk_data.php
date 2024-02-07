<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('base-api/DataCombo');

$reqId= $this->input->get("reqId");
// $reqId= "198305022011011001";
$arrdatabkn= [];
$arrparam= ["vid"=>$reqId, "vurl"=>"Data_rw_jabatan_json"];
$set= new DataCombo();
$set->selectdata($arrparam, "", "firstrow");
while($set->nextRow())
{
  $date= new DateTime($set->getField("tmtJabatan"));
  $tmtFormat= $date->format('Y-m-d');

  $infokunci= dateToPageCheck($tmtFormat);

  $arrdata= [];
  // kunci untuk kondisi
  $arrdata["key"]= $infokunci;

  // data sesuai api bkn
  $arrdata["id"]= $set->getField("id");
  $arrdata["idPns"]= $set->getField("idPns");
  $arrdata["nipBaru"]= $set->getField("nipBaru");
  $arrdata["nipLama"]= $set->getField("nipLama");
  $arrdata["jenisJabatan"]= $set->getField("jenisJabatan");
  $arrdata["instansiKerjaId"]= $set->getField("instansiKerjaId");
  $arrdata["instansiKerjaNama"]= $set->getField("instansiKerjaNama");
  $arrdata["satuanKerjaId"]= $set->getField("satuanKerjaId");
  $arrdata["satuanKerjaNama"]= $set->getField("satuanKerjaNama");
  $arrdata["unorId"]= $set->getField("unorId");
  $arrdata["unorNama"]= $set->getField("unorNama");
  $arrdata["unorIndukId"]= $set->getField("unorIndukId");
  $arrdata["tmtJabatan"]= $set->getField("tmtJabatan");
  $arrdata["unorIndukId"]= $set->getField("unorIndukId");
  $arrdata["eselon"]= $set->getField("eselon");
  $arrdata["eselonId"]= $set->getField("eselonId");
  $arrdata["jabatanFungsionalId"]= $set->getField("jabatanFungsionalId");
  $arrdata["jabatanFungsionalNama"]= $set->getField("jabatanFungsionalNama");
  $arrdata["jabatanFungsionalUmumId"]= $set->getField("jabatanFungsionalUmumId");
  $arrdata["jabatanFungsionalUmumNama"]= $set->getField("jabatanFungsionalUmumNama");
  $arrdata["nomorSk"]= $set->getField("nomorSk");
  $arrdata["tanggalSk"]= $set->getField("tanggalSk");
  $arrdata["namaUnor"]= $set->getField("namaUnor");
  $arrdata["namaJabatan"]= $set->getField("namaJabatan");
  $arrdata["unorIndukNama"]= $set->getField("unorIndukNama");

  $infonamajabatan= 1;
  if(!empty($arrdata["jabatanFungsionalId"]))
    $infonamajabatan= 3;
  else if(!empty($arrdata["jabatanFungsionalUmumId"]))
    $infonamajabatan= 2;

  $arrNamaJabatan = array();
  $arrNamaJabatan[1]='Jabatan Struktural';
  $arrNamaJabatan[2]='Jabatan Fungsional Umum';
  $arrNamaJabatan[3]='Jabatan Fungsional Tertentu';
  $arrdata["jenisJabatanNama"]= $arrNamaJabatan[$infonamajabatan];

  $arrdata["tmtPelantikan"]= $set->getField("tmtPelantikan");
  $arrdata["path"]= $set->getField("path");
  $arrdata["id"]= $set->getField("id");
  array_push($arrdatabkn, $arrdata);
}
// print_r($arrdatabkn);exit;
$disabled= "disabled";

// untuk ambil data path
$infopathdetil= $arrdata["path"];
// print_r($infopathdetil);exit;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="msapplication-tap-highlight" content="no">
<meta name="description" content="Simpeg Jombang">
<meta name="keywords" content="Simpeg Jombang">
<title>Simpeg Jombang</title>
<base href="<?=base_url()?>" />

<script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script>
<script src="lib/autokomplit/jquery-ui.js"></script>

<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

<link href="lib/mbox/mbox.css" rel="stylesheet">
<script src="lib/mbox/mbox.js"></script>
<link href="lib/mbox/mbox-modif.css" rel="stylesheet">

</head>

<div class="test">
	<ul class="collection card" style="margin-top: -1px; margin-left: -5px">
	  <li class="collection-item ubah-color-warna">Data BKN</li>
	  <li class="collection-item">

      <div class="col s12">
        <ul class="tabs">
          <li class="tab col s3"><a class="active" href="#test1">Data</a></li>
          <li class="tab col s3"><a href="#test2">Preview Dokumen</a></li>
        </ul>
      </div>

      <div id="test1" class="col s12">
		    <div class="row">
		      <div class="input-field col s12 m6">
		        <label for="reqNoSk">Jenis Jabatan</label>
		        <input placeholder="" type="text" class="easyui-validatebox" <?=$disabled?> value="<?=$arrdatabkn[0]["jenisJabatanNama"]?>" />
		      </div>
		    </div>
		    <div class="row">
		      <div class="input-field col s12 m6">
		        <label for="reqNoSk">No. SK</label>
		        <input placeholder="" type="text" class="easyui-validatebox" <?=$disabled?> value="<?=$arrdatabkn[0]["nomorSk"]?>" />
		      </div>
		      <div class="input-field col s12 m6">
		        <label for="reqTglSk">Tgl. SK</label>
		        <input placeholder="" required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglSk" id="reqTglSk" value="<?=$arrdatabkn[0]["tanggalSk"]?>" <?=$disabled?> maxlength="10" onKeyDown="return format_date(event,'reqTglSk');"/>
		      </div>
		    </div>
		    <div class="row">
		      <div class="input-field col s12 m12">
		        <label for="reqNama">Satuan Kerja</label>
		        <input placeholder="" type="text" id="reqNama" name="reqSatker" <?=$disabled?> value="<?=$arrdatabkn[0]["satuanKerjaNama"]?>" class="easyui-validatebox" required />
		      </div>
		    </div>
		    <div class="row">
		      <div class="input-field col s12 m4">
		        <label for="reqBlnDibayar">TMT Jabatan</label>
		        <input placeholder="" class="easyui-validatebox" type="text" name="reqTmtSpmt" id="reqTmtSpmt" value="<?=$arrdatabkn[0]["tmtJabatan"]?>" <?=$disabled?> />
		      </div>
		      <div class="input-field col s12 m4">
		        <label for="reqBlnDibayar">TMT Pelantikan</label>
		        <input placeholder="" class="easyui-validatebox" <?=$disabled?>  type="text" name="reqBlnDibayar" id="reqBlnDibayar" value="<?=$arrdatabkn[0]["tmtPelantikan"]?>" maxlength="10" />
		      </div>
		      <div class="input-field col s12 m4">
		        <label for="reqTunjangan">Nama Jabatan</label>
		        <input placeholder="" type="text" id="reqTunjangan" <?=$disabled?> name="reqTunjangan"  value="<?=$arrdatabkn[0]["namaJabatan"]?>" />
		      </div>
		    </div>
        <div class="row">
          <div class="input-field col s12 m4">
            <label for="reqTunjangan">Jabatan Fungsional </label>
            <input placeholder="" type="text" id="reqTunjangan" <?=$disabled?> name="reqTunjangan"  value="<?=$arrdatabkn[0]["jabatanFungsionalNama"]?>" />
          </div>
          <div class="input-field col s12 m4">
            <label for="reqTunjangan">Jabatan Fungsional Umum</label>
            <input placeholder="" type="text" id="reqTunjangan" <?=$disabled?> name="reqTunjangan"  value="<?=$arrdatabkn[0]["jabatanFungsionalUmumNama"]?>" />
          </div>
		    </div>
        <div class="row">
          <div class="input-field col s12 m4">
            <label for="reqBlnDibayar">Eselon</label>
            <input placeholder="" class="easyui-validatebox" <?=$disabled?>  type="text" name="reqTmtSpmt" id="reqTmtSpmt" value="<?=$arrdatabkn[0]["eselon"]?>" />
          </div>

          <div class="input-field col s12 m4">
            <label for="reqBlnDibayar">Unor Nama</label>
            <input placeholder="" class="easyui-validatebox" <?=$disabled?>  type="text" name="reqTmtSpmt" id="reqTmtSpmt" value="<?=$arrdatabkn[0]["unorNama"]?>" />
          </div>
          <div class="input-field col s12 m4">
            <label for="reqTunjangan">Unor Induk Nama</label>
            <input placeholder="" type="text" id="reqTunjangan" <?=$disabled?>  name="reqTunjangan"  value="<?=$arrdatabkn[0]["unorIndukNama"]?>" />
          </div>
        </div>
      </div>

      <div id="test2" class="col s12">
        <ul class="collapsible">
          <?
          $totaldata= 0;
          if(!empty($infopathdetil))
          {
            foreach ($infopathdetil as $key => $value)
            {
              $vurldetilnama= $value->dok_nama;
              $vurldetilurl= urlencode($value->dok_uri);

              if(!empty($vurldetilurl))
              {
                $infoactive= $totaldata == 0 ?"active":"";
          ?>
              <li class="<?=$infoactive?>">
                <div class="collapsible-header <?=$infoactive?>"><?=$vurldetilnama?></div>
                <div class="collapsible-body">
                  <embed src="app/loadUrl/sapk/readfile?id=<?=$vurldetilurl?>" width="100%" height="375" type="application/pdf">
                </div>
              </li>
          <?
                $totaldata++;
              }
            }
          }

          if($totaldata == 0)
          {
          ?>
          <li>
            <div class="collapsible-header">Tidak ada file</div>
          </li>
          <?
          }
          ?>
        </ul>
      </div>

	  </li>
	</ul>
</div>

<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>
<script type="text/javascript" src="lib/materializetemplate/js/plugins/formatter/jquery.formatter.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('select').material_select();
  });
  $('.materialize-textarea').trigger('autoresize');
</script>
<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>