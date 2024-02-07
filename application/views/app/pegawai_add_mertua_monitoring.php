<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('Mertua');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "011004";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

// $statement = " AND NOT STATUS_AKTIF = 0";
$ayah = new Mertua();
$ayah->selectByParams(array("PEGAWAI_ID" => $reqId, "JENIS_KELAMIN" => "L"),-1,-1, $statement);

$ibu  = new Mertua();
$ibu->selectByParams(array("PEGAWAI_ID" => $reqId, "JENIS_KELAMIN" => "P"),-1,-1, $statement);


$ayah->firstRow();
$ibu->firstRow();

$reqIdAyah= (int)$ayah->getField("MERTUA_ID");
$reqIdIbu= (int)$ibu->getField("MERTUA_ID");
$reqNamaAyah= $ayah->getField('NAMA');
$reqNamaIbu= $ibu->getField('NAMA');
$reqPekerjaanAyah= $ayah->getField('PEKERJAAN');
$reqPekerjaanIbu= $ibu->getField('PEKERJAAN');
$reqAlamatAyah= $ayah->getField('ALAMAT');
$reqAlamatIbu= $ibu->getField('ALAMAT');
$reqTeleponAyah= $ayah->getField('TELEPON');
$reqTeleponIbu= $ibu->getField('TELEPON');
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
  
  <!-- CORE CSS-->    
  <link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
  <!-- CSS style Horizontal Nav-->    
  <link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
  <!-- Custome CSS-->    
  <link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">
  
  <style type="text/css">
    .sm-text {
      font-size: 8pt;
    }
    .pad{
      padding: 1vw;
    }
  </style>
</head>

<body>    
  <!--Basic Form-->
  <div id="basic-form" class="section">
    <div class="row">

      <div class="col s12 m6 offset-m3 ">
        <form id="ff" class="pad" method="post" enctype="multipart/form-data">

          <ul class="collection card">
            <li class="collection-item ubah-color-warna">MERTUA</li>
            <li class="collection-item">

             <div class="row">
              <div class="input-field col s12 m12 l6">
                <label for="reqNamaAyah">Nama Ayah</label>
                <input placeholder="" type="text" disabled name="reqNamaAyah"  id="reqNamaAyah" value="<?=$reqNamaAyah?>" />
              </div>

              <div class="input-field col s12 m12 l6">
                <label for="reqNamaIbu">Nama Ibu</label>
                <input placeholder="" type="text" disabled name="reqNamaIbu"  id="reqNamaIbu" value="<?=$reqNamaIbu?>" />
              </div>
              <!-- </div>    -->

              <!-- <div class="row"> -->
              <div class="input-field col s12 m12 l6">
                <label for="reqPekerjaanAyah">Pekerjaan Ayah</label>
                <input placeholder="" type="text" disabled name="reqPekerjaanAyah"  id="reqPekerjaanAyah" value="<?=$reqPekerjaanAyah?>"/>
              </div>

              <div class="input-field col s12 m12 l6">
                <label for="reqPekerjaanIbu">Pekerjaan Ibu</label>
                <input placeholder="" type="text" disabled name="reqPekerjaanIbu"  id="reqPekerjaanIbu" value="<?=$reqPekerjaanIbu?>"/>
              </div>
              <!-- </div>    -->

              <!-- <div class="row"> -->
              <div class="input-field col s12 m12 l6">
                <label for="reqAlamatAyah">Alamat Ayah</label>
                <input placeholder="" type="text" disabled name="reqAlamatAyah"  id="reqAlamatAyah" value="<?=$reqAlamatAyah?>"/>
              </div>

              <div class="input-field col s12 m12 l6">
                <label for="reqAlamatIbu">Alamat Ibu</label>
                <input placeholder="" type="text" disabled name="reqAlamatIbu"  id="reqAlamatIbu" value="<?=$reqAlamatIbu?>"/>
              </div>
              <!-- </div>    -->

              <!-- <div class="row"> -->
              <div class="input-field col s12 m12 l6">
                <label for="reqTeleponAyah">Telepon Ayah</label>
                <input placeholder="" type="text" disabled name="reqTeleponAyah"  id="reqTeleponAyah" value="<?=$reqTeleponAyah?>"/>
              </div>

              <div class="input-field col s12 m12 l6">
                <label for="reqTeleponIbu">Telepon Ibu</label>
                <input placeholder="" type="text" disabled name="reqTeleponIbu"  id="reqTeleponIbu" value="<?=$reqTeleponIbu?>"/>
              </div>
              <!-- </div>    -->

              <!-- <div class="row"> -->
              <div class="col s12 m12 l6">
                <button class="btn green waves-effect waves-light sm-text col s12" type="button" onClick="parent.setload('pegawai_mertua?reqId=<?=$reqId?>')" name="action">Edit
                  <i class="mdi-social-person-outline left"></i>
                </button>
              </div>
              <!-- <div class="col s12 m12 l6">
                <button class="btn green waves-effect waves-light sm-text col s12" type="button" name="action">Lihat Berkas
                  <i class="mdi-social-person-outline left"></i>
                </button>
              </div> -->
            </div>
          </li>
        </ul>

      </form>
    </div>

  </div>
</div>
<!-- jQuery Library -->
<script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script>

<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/materialize.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('select').material_select();
  });

  $('.materialize-textarea').trigger('autoresize');

</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>