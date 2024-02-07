<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();
$this->load->model('base-api/InfoData');
$this->load->model('base-api/DataCombo');

$reqId= $this->input->get("reqId");
$reqNip= $this->input->get("reqNip");

  // $reqId ='8ae483a7653c1ec201653c2fdd7c09dc';


$set= new InfoData();
$infonipbaru= $set->getnip($reqNip);


$arrdatabkn= [];

 // $infonipbaru ='197207101998031010';



$arrparam= ["nip"=>$infonipbaru, "vurl"=>"Data_rw_golongan_json"];
$set= new DataCombo();
$set->selectdata($arrparam, "");

// print_r($arrDataDataPasangan);exit;
 $arrdataField= [];
while($set->nextRow())
{
 $arrdata= [];
 
 $date      = new DateTime($set->getField("tmtGolongan"));
 $tmtFormat = $date->format('Y-m-d');

 $infokunci= dateToPageCheck($tmtFormat);
 $arrdata= [];
                    // kunci untuk kondisi
 $arrdata["key"]= $infokunci;

                    // data sesuai api bkn
 $arrdata["id"]= $set->getField("id");
 $arrdata["idPns"]= $set->getField("idPns");
 $arrdata["nipBaru"]= $set->getField("nipBaru");
 $arrdata["nipLama"]= $set->getField("nipLama");
 $arrdata["golonganId"]= $set->getField("golonganId");
 $arrdata["golongan"]= $set->getField("golongan");
 $arrdata["skNomor"]= $set->getField("skNomor");
 $arrdata["skTanggal"]= $set->getField("skTanggal");
 $arrdata["tmtGolongan"]= $infokunci;
 $arrdata["noPertekBkn"]= $set->getField("noPertekBkn");
 $arrdata["tglPertekBkn"]= $set->getField("tglPertekBkn");
 $arrdata["jumlahKreditUtama"]= $set->getField("jumlahKreditUtama");
 $arrdata["jumlahKreditTambahan"]= $set->getField("jumlahKreditTambahan");
 $arrdata["jenisKPId"]= $set->getField("jenisKPId");
 $arrdata["jenisKPNama"]= $set->getField("jenisKPNama");
 $arrdata["masaKerjaGolonganTahun"]= $set->getField("masaKerjaGolonganTahun");
 $arrdata["masaKerjaGolonganBulan"]= $set->getField("masaKerjaGolonganBulan");
  $arrdata["path"]= $set->getField("path");

 array_push($arrdataField, $arrdata);
}    

// print_r($arrdataField);exit;
$vdatariwayat= in_array_column($reqId, "id", $arrdataField);
 

if(!empty($vdatariwayat))
{
    $arrdata= [];
    $indexdata= $vdatariwayat[0];
    $arrResult = $arrdataField[$indexdata];
        
    $arrdata['id']= $arrResult['id'];
    $arrdata['idPns']= $arrResult["idPns"];
    $arrdata['nipBaru']= $arrResult["nipBaru"];
    $arrdata['nipLama']= $arrResult["nipLama"];
    $arrdata['golonganId']= $arrResult["golonganId"];
    $arrdata['golongan']= $arrResult["golongan"];
    $arrdata['skNomor']= $arrResult["skNomor"];
    $arrdata['skTanggal']= $arrResult["skTanggal"];
    $arrdata['tmtGolongan']=$arrResult["tmtGolongan"];
    $arrdata['noPertekBkn']= $arrResult["noPertekBkn"];
    $arrdata['tglPertekBkn']= $arrResult["tglPertekBkn"];
    $arrdata['jumlahKreditUtama']= $arrResult["jumlahKreditUtama"];
    $arrdata['jumlahKreditTambahan']= $arrResult["jumlahKreditTambahan"];
    $arrdata['jenisKPId']= $arrResult["jenisKPId"];
    $arrdata['jenisKPNama']= $arrResult["jenisKPNama"];
    $arrdata['masaKerjaGolonganTahun']= $arrResult["masaKerjaGolonganTahun"];
    $arrdata['masaKerjaGolonganBulan']= $arrResult["masaKerjaGolonganBulan"];
    $arrdata['masaKerjaGolonganBulan']= $arrResult["masaKerjaGolonganBulan"];
    $arrdata['path']= $arrResult["path"];
    
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
          <label for="reqNoSk">Jenis KP Nama</label>
          <input placeholder="" type="text" class="easyui-validatebox" <?=$disabled?> value="<?=$arrdatabkn[0]["jenisKPNama"]?>" />
        </div>
      
      </div>
      <div class="row">
          <div class="input-field col s12 m4">
            <label for="reqNoSk">Sk Nomor</label>
            <input placeholder="" type="text" class="easyui-validatebox" <?=$disabled?> value="<?=$arrdatabkn[0]["skNomor"]?>" />
          </div>
          <div class="input-field col s12 m4">
            <label for="reqTglSk">SK Tanggal</label>
            <input placeholder="" required class="easyui-validatebox"  type="text" name="reqTglSk" id="reqTglSk" value="<?=$arrdatabkn[0]["skTanggal"]?>" <?=$disabled?> maxlength="10"/>
          </div>        
       </div>
        <div class="row">
          <div class="input-field col s12 m4">
            <label for="reqNoSk">No Pertek Bkn</label>
            <input placeholder="" type="text" class="easyui-validatebox" <?=$disabled?> value="<?=$arrdatabkn[0]["noPertekBkn"]?>" />
          </div>
          <div class="input-field col s12 m4">
            <label for="reqTglSk">Tgl Pertek Bkn</label>
            <input placeholder="" required class="easyui-validatebox"  type="text" name="reqTglSk" id="reqTglSk" value="<?=$arrdatabkn[0]["tglPertekBkn"]?>" <?=$disabled?> maxlength="10"/>
          </div>        
       </div>
         <div class="row">
          <div class="input-field col s12 m4">
            <label for="reqNoSk">Golongan</label>
            <input placeholder="" type="text" class="easyui-validatebox" <?=$disabled?> value="<?=$arrdatabkn[0]["golongan"]?>" />
          </div>
          <div class="input-field col s12 m4">
            <label for="reqTglSk">Tmt Golongan</label>
            <input placeholder="" required class="easyui-validatebox"  type="text" name="reqTglSk" id="reqTglSk" value="<?=$arrdatabkn[0]["tmtGolongan"]?>" <?=$disabled?> maxlength="10"/>
          </div>        
       </div>
        <div class="row">
          <div class="input-field col s12 m4">
            <label for="reqNoSk">Masa Kerja Golongan Tahun</label>
            <input placeholder="" type="text" class="easyui-validatebox" <?=$disabled?> value="<?=$arrdatabkn[0]["masaKerjaGolonganTahun"]?>" />
          </div>
          <div class="input-field col s12 m4">
            <label for="reqTglSk">Masa Kerja Golongan Bulan</label>
            <input placeholder="" required class="easyui-validatebox"  type="text" name="reqTglSk" id="reqTglSk" value="<?=$arrdatabkn[0]["masaKerjaGolonganBulan"]?>" <?=$disabled?> maxlength="10"/>
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