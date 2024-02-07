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

// $reqId ='8ae482a451e6bd880151f55b49bc68ee';


$set= new InfoData();
$infonipbaru= $set->getnip($reqNip);


$arrdatabkn= [];

// $infonipbaru ='198305022011011001';

$arrparam= ["nip"=>$infonipbaru, "vurl"=>"Data_pasangan_json"];
$set= new DataCombo();
$set->selectdata($arrparam, "","allrow");
$arrDataResults = $set->rowResult;
$arrDataDataPasangan = $arrDataResults['listPasangan'];
$nipBaru  = $arrDataResults['nipBaru'];

 $arrdataField= [];
foreach ($arrDataDataPasangan as $row) 
{
 $arrdata= [];
 
 // data sesuai api bkn
 $arrdata["id"]= $row['dataPernikahan']['id'];
 $arrdata["nama"]= $row['orang']['nama'];
 $arrdata["statusNikah"]= $row['statusNikah'];
 $arrdata["tglLahir"]=  $row['orang']['tglLahir'];
 $arrdata["tempatLahir"]=  $row['orang']['tempatLahir'];
 $arrdata["jenisKelamin"]=  $row['orang']['jenisKelamin'];
 $arrdata["aktaMenikah"]=  $row['dataPernikahan']['aktaMenikah'];
 $arrdata["tgglMenikah"]=  $row['dataPernikahan']['tgglMenikah'];

 $arrdata["tgglCerai"]=  $row['dataPernikahan']['tgglCerai'];
 $arrdata["aktaCerai"]=  $row['dataPernikahan']['aktaCerai'];
 $arrdata["gelarDepan"]=  $row['orang']['gelarDepan'];
 $arrdata["gelarBlk"]=  $row['orang']['gelarBlk'];
 $arrdata["aktaMeninggal"]=  $row['orang']['aktaMeninggal'];
 $arrdata["tglMeninggal"]=  $row['orang']['tglMeninggal'];
 $arrdata["status"]=  $row['dataPernikahan']['status'];
 $arrdata["isPns"]=  $row['dataPernikahan']['isPns'];


 array_push($arrdataField, $arrdata);
}    

// print_r($arrdatabkn);exit;
$vdatariwayat= in_array_column($reqId, "id", $arrdataField);
 

if(!empty($vdatariwayat))
{
    $arrdata= [];
    $indexdata= $vdatariwayat[0];
    $arrResult = $arrdataField[$indexdata];
        
    $arrdata["id"]= $arrResult['id'];
    $arrdata["nama"]= $arrResult['nama'];
    $arrdata["statusNikah"]= $arrResult['statusNikah'];
    $arrdata["tglLahir"]= $arrResult['tglLahir'];
    $arrdata["tempatLahir"]= $arrResult['tempatLahir'];
    $arrdata["jenisKelamin"]= $arrResult['jenisKelamin'];
    $arrdata["aktaMenikah"]= $arrResult['aktaMenikah'];
    $arrdata["tgglMenikah"]= $arrResult['tgglMenikah'];
    $arrdata["tgglCerai"]= $arrResult['tgglCerai'];
    $arrdata["aktaCerai"]= $arrResult['aktaCerai'];
   
    $arrdata["gelarDepan"]= $arrResult['gelarDepan'];
    $arrdata["gelarBlk"]= $arrResult['gelarBlk'];
    $arrdata["aktaMeninggal"]= $arrResult['aktaMeninggal'];
    $arrdata["tglMeninggal"]= $arrResult['tglMeninggal'];
    $arrdata["status"]= $arrResult['status'];
    $arrdata["isPns"]= $arrResult['isPns'];
    
     array_push($arrdatabkn, $arrdata);
}
// print_r($arrdatabkn);exit;

$disabled= "disabled";
$infopathdetil= $arrdata["path"];


$set= new JenisKelamin();
$set->selectbyparams(array());
// echo $set->query;exit;
$arrjeniskelamin=[];
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["ID"]= $set->getField("KODE");
  $arrdata["TEXT"]= $set->getField("NAMA");
  array_push($arrjeniskelamin, $arrdata);
}


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
          <label for="reqNoSk">Nama Suami/Istri</label>
          <input placeholder="" type="text" class="easyui-validatebox" <?=$disabled?> value="<?=$arrdatabkn[0]["nama"]?>" />
        </div>
        <div class="input-field col s12 m4">
          <label for="reqTglSk">Gelar Depan</label>
          <input placeholder="" required class="easyui-validatebox"  type="text" name="reqTglSk" id="reqTglSk" value="<?=$arrdatabkn[0]["gelarDepan"]?>" <?=$disabled?> maxlength="10"/>
        </div>
        <div class="input-field col s12 m4">
          <label for="reqTglSk">Gelar Belakang</label>
          <input placeholder="" required class="easyui-validatebox"  type="text" name="reqTglSk" id="reqTglSk" value="<?=$arrdatabkn[0]["gelarBlk"]?>" <?=$disabled?> maxlength="10"/>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12 m4">
          <label for="reqNoSk">Tempat Lahir</label>
          <input placeholder="" type="text" class="easyui-validatebox" <?=$disabled?> value="<?=$arrdatabkn[0]["tempatLahir"]?>" />
        </div>
        <div class="input-field col s12 m4">
          <label for="reqTglSk">Tgl. Lahir</label>
          <input placeholder="" required class="easyui-validatebox"  type="text" name="reqTglSk" id="reqTglSk" value="<?=$arrdatabkn[0]["tglLahir"]?>" <?=$disabled?> maxlength="10"/>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12 m4">
          <label for="reqNoSk">Surat nikah</label>
          <input placeholder="" type="text" class="easyui-validatebox" <?=$disabled?> value="<?=$arrdatabkn[0]["aktaMenikah"]?>" />
        </div>
        <div class="input-field col s12 m4">
          <label for="reqTglSk">Tgl. nikah</label>
          <input placeholder="" required class="easyui-validatebox"  type="text" name="reqTglSk" id="reqTglSk" value="<?=$arrdatabkn[0]["tgglMenikah"]?>" <?=$disabled?> maxlength="10"/>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12 m4">
          <label for="reqNoSk">Surat Cerai</label>
          <input placeholder="" type="text" class="easyui-validatebox" <?=$disabled?> value="<?=$arrdatabkn[0]["aktaCerai"]?>" />
        </div>
        <div class="input-field col s12 m4">
          <label for="reqTglSk">Tgl. Cerai</label>
          <input placeholder="" required class="easyui-validatebox"  type="text" name="reqTglSk" id="reqTglSk" value="<?=$arrdatabkn[0]["tgglCerai"]?>" <?=$disabled?> maxlength="10"/>
        </div>      
      </div>
      <div class="row">
        <div class="input-field col s12 m4">
         
            <select <?=$disabled?> name="reqJenisKelamin" id="reqJenisKelamin">
                        <option value="" selected></option>
                        <?
                        foreach ($arrjeniskelamin as $key => $value)
                        {
                          $optionid= $value["ID"];
                          $optiontext= $value["TEXT"];
                          $optionselected= "";
                          if($arrdatabkn[0]["jenisKelamin"] == $optionid)
                            $optionselected= "selected";
                        ?>
                          <option value="<?=$optionid?>" <?=$optionselected?>><?=$optiontext?></option>
                        <?
                        }
                        ?>
                      </select>
                      <label for="reqJenisKelamin">Jenis Kel</label>
        </div>
        <div class="input-field col s12 m4">
            <input type="checkbox" id="reqStatusPns" name="reqStatusPns" value="1" <? if($arrdatabkn[0]["isPns"] ) echo 'checked'?> <?=$disabled?>  />
             <label for="reqStatusPns"></label>
                  PNS
         
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