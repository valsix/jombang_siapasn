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

   $reqId ='8ae483a7697027a90169b8c127f8782c';


$set= new InfoData();
$infonipbaru= $set->getnip($reqNip);


$arrdatabkn= [];

 $infonipbaru ='196312131989031006';


$arrSelectAnak[1]='Kandung';
$arrSelectAnak[2]='Tiri';
$arrSelectAnak[3]='Angkat';

$arrparam= ["nip"=>$infonipbaru, "vurl"=>"Data_rw_hukdis_json"];
$set= new DataCombo();
$set->selectdata($arrparam, "");

 $arrdataField= [];
while($set->nextRow())
{
 $arrdata= [];
 
 
              // data sesuai api bkn
               $date      = new DateTime($set->getField("hukumanTanggal"));
               $tmtFormat = $date->format('d-m-Y');


               $infokunci= $tmtFormat ;
               $arrdata= [];
              // kunci untuk kondisi
               $arrdata["key"]= $infokunci;

                // data sesuai api bkn
               $arrdata["id"]= $set->getField("id");
               $arrdata["rwHukumanDisiplin"]= $set->getField("rwHukumanDisiplin");
               $arrdata["golongan"]= $set->getField("golongan");
               $arrdata["kedudukanHukum"]= $set->getField("kedudukanHukum");
               $arrdata["jenisHukuman"]= $set->getField("jenisHukuman");
               $arrdata["pnsOrang"]= $set->getField("pnsOrang");
               $arrdata["skNomor"]= $set->getField("skNomor");
               $arrdata["skTanggal"]= $set->getField("skTanggal");
               $arrdata["hukumanTanggal"]= $set->getField("hukumanTanggal");
               $arrdata["masaTahun"]= $set->getField("masaTahun");
               $arrdata["masaBulan"]= $set->getField("masaBulan");
               $arrdata["akhirHukumTanggal"]= $set->getField("akhirHukumTanggal");
               $arrdata["nomorPp"]= $set->getField("nomorPp");
               $arrdata["golonganLama"]= $set->getField("golonganLama");
               $arrdata["skPembatalanNomor"]= $set->getField("skPembatalanNomor");
               $arrdata["skPembatalanTanggal"]= $set->getField("skPembatalanTanggal");
               $arrdata["alasanHukumanDisiplin"]= $set->getField("alasanHukumanDisiplin");
               $arrdata["alasanHukumanDisiplinNama"]= $set->getField("alasanHukumanDisiplinNama");
               $arrdata["jenisHukumanNama"]= $set->getField("jenisHukumanNama");
               $arrdata["path"]= $set->getField("path");
               $arrdata["keterangan"]= $set->getField("keterangan");
               $arrdata["jenisTingkatHukumanId"]= $set->getField("jenisTingkatHukumanId");


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
   

               $arrdata['rwHukumanDisiplin']= $arrResult["rwHukumanDisiplin"];
               $arrdata['golongan']= $arrResult["golongan"];
               $arrdata['kedudukanHukum']= $arrResult["kedudukanHukum"];
               $arrdata['jenisHukuman']= $arrResult["jenisHukuman"];
               $arrdata['pnsOrang']= $arrResult["pnsOrang"];
               $arrdata['skNomor']= $arrResult["skNomor"];
               $arrdata['skTanggal']= $arrResult["skTanggal"];
               $arrdata['hukumanTanggal']= $arrResult["hukumanTanggal"];
               $arrdata['masaTahun']= $arrResult["masaTahun"];
               $arrdata['masaBulan']= $arrResult["masaBulan"];
               $arrdata['akhirHukumTanggal']= $arrResult["akhirHukumTanggal"];
               $arrdata['nomorPp']= $arrResult["nomorPp"];
               $arrdata['golonganLama']= $arrResult["golonganLama"];
               $arrdata['skPembatalanNomor']= $arrResult["skPembatalanNomor"];
               $arrdata['skPembatalanTanggal']= $arrResult["skPembatalanTanggal"];
               $arrdata['alasanHukumanDisiplin']= $arrResult["alasanHukumanDisiplin"];
               $arrdata['alasanHukumanDisiplinNama']= $arrResult["alasanHukumanDisiplinNama"];
               $arrdata['jenisHukumanNama']= $arrResult["jenisHukumanNama"];
               $arrdata['path']= $arrResult["path"];
               $arrdata['keterangan']=$arrResult["keterangan"];
               $arrdata['jenisTingkatHukumanId']= $arrResult["jenisTingkatHukumanId"];
    
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
            <label for="reqNoSk">Jenis Hukuman Nama</label>
            <input placeholder="" type="text" class="easyui-validatebox" <?=$disabled?> value="<?=$arrdatabkn[0]["jenisHukumanNama"]?>" />
          </div>
      </div>    
       <div class="row">
          <div class="input-field col s12 m8">
            <label for="reqNoSk">Alasan Hukuman Disiplin Nama</label>
            <textarea class="easyui-validatebox materialize-textarea"   <?=$disabled?> name="reqPermasalahan" id="reqPermasalahan"><?=$arrdatabkn[0]["alasanHukumanDisiplinNama"]?></textarea>
          </div>
      </div>    


      <div class="row">
        <div class="input-field col s12 m4">
          <label for="reqNoSk">SK Nomor</label>
          <input placeholder="" type="text" class="easyui-validatebox" <?=$disabled?> value="<?=$arrdatabkn[0]["skNomor"]?>" />
        </div>
        <div class="input-field col s12 m4">
          <label for="reqTglSk">SK Tanggal</label>
          <input placeholder="" required class="easyui-validatebox"  type="text" name="reqTglSk" id="reqTglSk" value="<?=$arrdatabkn[0]["skTanggal"]?>" <?=$disabled?> />
        </div>
        <div class="input-field col s12 m4">
          <label for="reqTglSk">Tmt Tanggal</label>
          <input placeholder="" required class="easyui-validatebox"  type="text" name="reqTglSk" id="reqTglSk" value="<?=$arrdatabkn[0]["hukumanTanggal"]?>" <?=$disabled?> />
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