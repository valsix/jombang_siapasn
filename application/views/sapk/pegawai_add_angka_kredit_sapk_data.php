<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();
$this->load->model('base-api/InfoData');
$this->load->model('base-api/DataCombo');
$this->load->model('JenisKelamin');
$reqId= $this->input->get("reqId");
$reqNip= $this->input->get("reqNip");

// $reqId ='8ae483a85f044631015f2e08ff5d288e';
$set= new InfoData();
$infonipbaru= $set->getnip($reqNip);


$arrdatabkn= [];
// $infonipbaru ='198305022011011001';

$arrparam= ["vid"=>$reqId, "vurl"=>"Data_rw_angkakredit_json"];
$set= new DataCombo();
$set->selectdata($arrparam, "", "firstrow");

 $arrdataField= [];
while($set->nextRow())
{
              $arrdata= [];
 
 
              // data sesuai api bkn
               $date      = new DateTime($set->getField("tanggalSk"));
               $tmtFormat = $date->format('d-m-Y');


               $infokunci= $tmtFormat ;
               $arrdata= [];
             // kunci untuk kondisi
               $arrdata["key"]= $infokunci;

            // data sesuai api bkn
               $arrdata["id"]= $set->getField("id");
               $arrdata["pns"]= $set->getField("pns");
               $arrdata["nomorSk"]= $set->getField("nomorSk");
               $arrdata["tanggalSk"]=$infokunci;
               $arrdata["bulanMulaiPenailan"]= $set->getField("bulanMulaiPenailan");
               $arrdata["tahunMulaiPenailan"]= $set->getField("tahunMulaiPenailan");
               $arrdata["bulanSelesaiPenailan"]= $set->getField("bulanSelesaiPenailan");
               $arrdata["tahunSelesaiPenailan"]= $set->getField("tahunSelesaiPenailan");
               $arrdata["kreditUtamaBaru"]= $set->getField("kreditUtamaBaru");
               $arrdata["kreditPenunjangBaru"]= $set->getField("kreditPenunjangBaru");
               $arrdata["kreditBaruTotal"]= $set->getField("kreditBaruTotal");
               $arrdata["rwJabatan"]= $set->getField("rwJabatan");
               $arrdata["namaJabatan"]= $set->getField("namaJabatan");
               $arrdata["isAngkaKreditPertama"]= $set->getField("isAngkaKreditPertama");
               $arrdata["path"]= $set->getField("path");


           array_push($arrdataField, $arrdata);
}    

// print_r($arrdatabkn);exit;
$vdatariwayat= in_array_column($reqId, "id", $arrdataField);
 

if(!empty($vdatariwayat))
{
              $arrdata= [];
              $indexdata= $vdatariwayat[0];
              $arrResult = $arrdataField[$indexdata];

              $arrdata['id']= $arrResult['id'];
                 
                $arrdata['pns']= $arrResult["pns"];
                $arrdata['nomorSk']= $arrResult["nomorSk"];
                $arrdata['tanggalSk']= $arrResult["tanggalSk"];
                $arrdata['bulanMulaiPenailan']= $arrResult["bulanMulaiPenailan"];
                $arrdata['tahunMulaiPenailan']= $arrResult["tahunMulaiPenailan"];
                $arrdata['bulanSelesaiPenailan']= $arrResult["bulanSelesaiPenailan"];
                $arrdata['tahunSelesaiPenailan']= $arrResult["tahunSelesaiPenailan"];
                $arrdata['kreditUtamaBaru']= $arrResult["kreditUtamaBaru"];
                $arrdata['kreditPenunjangBaru']= $arrResult["kreditPenunjangBaru"];
                $arrdata['kreditBaruTotal']= $arrResult["kreditBaruTotal"];
                $arrdata['rwJabatan']= $arrResult["rwJabatan"];
                $arrdata['namaJabatan']= $arrResult["namaJabatan"];
                $arrdata['isAngkaKreditPertama']= $arrResult["isAngkaKreditPertama"];
                $arrdata['isIntegrasi']= $arrResult["isIntegrasi"];
                $arrdata['isKonversi']= $arrResult["isKonversi"];
                $arrdata['path']= $arrResult["path"];
    
                array_push($arrdatabkn, $arrdata);
}
// print_r($arrdatabkn);exit;

// print_r($arrjeniskelamin);exit;
$disabled= "disabled";
$infopathdetil= $arrdata["path"];
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
    <ul class="collection card" style="margin-top: -1px">
        <li class="collection-item ubah-color-warna">Data BKN</li>
        <li class="collection-item">
    <div class="col s12">
      <ul class="tabs">
        <li class="tab col s3"><a class="active" href="#test1"> Data</a></li>
        <li class="tab col s3"><a href="#test2">Preview Dokumen</a></li>
      </ul>
    </div>
    <div id="test1" class="col s12">

      <div class="row">
          <div class="input-field col s12 m4">
            <label for="reqNoSk">Nama Jabatan</label>
            <input placeholder="" type="text" class="easyui-validatebox" <?=$disabled?> value="<?=$arrdatabkn[0]["namaJabatan"]?>" />
          </div>

          <div class="input-field col s12 m1">
            <input type="radio" id="reqCheckPakAwal" name="reqCheckPakAwal" value="1" <? if($arrdatabkn[0]["isAngkaKreditPertama"] == 1) echo 'checked'?>/>
            <label for="reqCheckPakAwal">Pertama</label>
          </div>
          <div class="input-field col s12 m1">
            <input type="radio" id="reqCheckPakIntegrasi" name="reqCheckPakAwal" value="2" <? if($arrdatabkn[0]["isIntegrasi"] == 1) echo 'checked'?>/>
            <label for="reqCheckPakIntegrasi">Integrasi</label>
          </div>
          <div class="input-field col s12 m1">
            <input type="radio" id="reqCheckPakKonversi" name="reqCheckPakAwal" value="3" <? if($arrdatabkn[0]["isKonversi"] == 1) echo 'checked'?>/>
            <label for="reqCheckPakKonversi">Konversi</label>
          </div>
      </div> 
      <div class="row">
        <div class="input-field col s12 m4">
          <label for="reqNoSk">SK Nomor</label>
          <input placeholder="" type="text" class="easyui-validatebox" <?=$disabled?> value="<?=$arrdatabkn[0]["nomorSk"]?>" />
        </div>
        <div class="input-field col s12 m4">
          <label for="reqTglSk">SK Tanggal</label>
          <input placeholder="" required class="easyui-validatebox"  type="text" name="reqTglSk" id="reqTglSk" value="<?=$arrdatabkn[0]["tanggalSk"]?>" <?=$disabled?> />
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12 m4">
          <label for="reqNoSk">Bulan Mulai </label>
          <input placeholder="" type="text" class="easyui-validatebox" <?=$disabled?> value="<?=$arrdatabkn[0]["bulanMulaiPenailan"]?>" />
        </div>
        <div class="input-field col s12 m4">
          <label for="reqTglSk">Tahun Mulai</label>
          <input placeholder="" required class="easyui-validatebox"  type="text" name="reqTglSk" id="reqTglSk" value="<?=$arrdatabkn[0]["tahunMulaiPenailan"]?>" <?=$disabled?> />
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12 m4">
          <label for="reqNoSk">Bulan Selesai</label>
          <input placeholder="" type="text" class="easyui-validatebox" <?=$disabled?> value="<?=$arrdatabkn[0]["bulanSelesaiPenailan"]?>" />
        </div>
        <div class="input-field col s12 m4">
          <label for="reqTglSk">Tahun Selesai</label>
          <input placeholder="" required class="easyui-validatebox"  type="text" name="reqTglSk" id="reqTglSk" value="<?=$arrdatabkn[0]["tahunSelesaiPenailan"]?>" <?=$disabled?> />
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12 m4">
          <label for="reqNoSk">Kredit Utama </label>
          <input placeholder="" type="text" class="easyui-validatebox" <?=$disabled?> value="<?=$arrdatabkn[0]["kreditUtamaBaru"]?>" />
        </div>
        <div class="input-field col s12 m4">
          <label for="reqTglSk">Kredit Penunjang</label>
          <input placeholder="" required class="easyui-validatebox"  type="text" name="reqTglSk" id="reqTglSk" value="<?=$arrdatabkn[0]["kreditPenunjangBaru"]?>" <?=$disabled?> />
        </div>
        <div class="input-field col s12 m4">
          <label for="reqTglSk">Kredit  Total</label>
          <input placeholder="" required class="easyui-validatebox"  type="text" name="reqTglSk" id="reqTglSk" value="<?=$arrdatabkn[0]["kreditBaruTotal"]?>" <?=$disabled?> />
        </div>
      </div>
      
    </div>
         
        </li>
      </ul>
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