<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();
$this->load->model('base-api/InfoData');
$this->load->model('base-api/DataCombo');

$reqId= $this->input->get("reqId");
$reqNip= $this->input->get("reqNip");

$set= new InfoData();
$infonipbaru= $set->getnip($reqNip);


$arrdatabkn= [];



$arrparam= ["nip"=>$infonipbaru, "vurl"=>"Data_rw_pendidikan_json"];
$set= new DataCombo();
$set->selectdata($arrparam, "");
$arrDataResult = $set->rowResult;

$vdatariwayat= in_array_column($reqId, "id", $arrDataResult);


if(!empty($vdatariwayat))
{
 
   $indexdata= $vdatariwayat[0];
   $arrResult = $arrDataResult[$indexdata];
        
    $arrdata["id"]= $arrResult['id'];
    $arrdata["idPns"]= $arrResult['idpns'];
    $arrdata["nipBaru"]= $arrResult['nipbaru'];
    $arrdata["nipLama"]= $arrResult['niplama'];
    $arrdata["pendidikanId"]= $arrResult['pendidikanid'];
    $arrdata["pendidikanNama"]= $arrResult['pendidikannama'];
    $arrdata["tkPendidikanId"]= $arrResult['tkpendidikanid'];
    $arrdata["tkPendidikanNama"]= $arrResult['tkpendidikannama'];
    $arrdata["tahunLulus"]= $arrResult['tahunlulus'];
    $arrdata["tglLulus"]= $arrResult['tgllulus'];
    $isPendidikanPertama= $arrResult['ispendidikanpertama'];
    $arrdata["nomorIjasah"]= $arrResult['nomorijasah'];
    $arrdata["namaSekolah"]= $arrResult['namasekolah'];
    $arrdata["gelarDepan"]= $arrResult['gelardepan'];
    $arrdata["gelarBelakang"]= $arrResult['gelarbelakang'];
    $arrdata["path"]= $arrResult['path'];

   if($isPendidikanPertama=='0'){
    $isPendidikanPertama=2;
  }
    $arrdata["isPendidikanPertama"]=$isPendidikanPertama;



  array_push($arrdatabkn, $arrdata);
}
// print_r($arrdatabkn);exit;

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
              <label for="reqBlnDibayar">TK Pendidikan</label>
              <input placeholder="" class="easyui-validatebox" <?=$disabled?>  type="text" name="reqBlnDibayar" id="reqBlnDibayar" value="<?=$arrdatabkn[0]["tkPendidikanNama"]?>" maxlength="10" />
            </div>
          </div>

          <div class="row">
            <div class="input-field col s12 m6">
              <label for="reqNoSk">Nama Sekolah</label>
              <input placeholder="" type="text" class="easyui-validatebox" <?=$disabled?> value="<?=$arrdatabkn[0]["namaSekolah"]?>" />
            </div>
            <div class="input-field col s12 m6">
              <label for="reqTglSk">Jurusan</label>
              <input placeholder="" required class="easyui-validatebox"  type="text" name="reqTglSk" id="reqTglSk" value="<?=$arrdatabkn[0]["pendidikanNama"]?>" <?=$disabled?> maxlength="10"/>
            </div>
          </div>

          <div class="row">
            <div class="input-field col s12 m4">
              <label for="reqNoSk">Nomor Ijasah</label>
              <input placeholder="" type="text" class="easyui-validatebox" <?=$disabled?> value="<?=$arrdatabkn[0]["nomorIjasah"]?>" />
            </div>
            <div class="input-field col s12 m4">
              <label for="reqTglSk">Tgl Lulus</label>
              <input placeholder="" required class="easyui-validatebox"  type="text" name="reqTglSk" id="reqTglSk" value="<?=$arrdatabkn[0]["tglLulus"]?>" <?=$disabled?> maxlength="10"/>
            </div>
             <div class="input-field col s12 m4">
              <label for="reqTglSk">Tahun</label>
              <input placeholder="" required class="easyui-validatebox"  type="text" name="reqTglSk" id="reqTglSk" value="<?=$arrdatabkn[0]["tahunLulus"]?>" <?=$disabled?> maxlength="10"/>
            </div>
          </div>


          <div class="row">
            <div class="input-field col s12 m3">
              <label for="reqNoSk">Gelar Depan</label>
              <input placeholder="" type="text" class="easyui-validatebox" <?=$disabled?> value="<?=$arrdatabkn[0]["gelarDepan"]?>" />
            </div>
            <div class="input-field col s12 m3">
              <label for="reqTglSk">Gelar Belakang</label>
              <input placeholder="" required class="easyui-validatebox"  type="text" name="reqTglSk" id="reqTglSk" value="<?=$arrdatabkn[0]["gelarBelakang"]?>" <?=$disabled?> maxlength="10"/>
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