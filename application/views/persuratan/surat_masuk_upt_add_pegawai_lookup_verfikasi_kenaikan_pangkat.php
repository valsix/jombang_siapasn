<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/SuratMasukPegawaiCheck');
$this->load->model('persuratan/SuratMasukPegawai');
$this->load->model('persuratan/SuratMasukUpt');
$this->load->model('persuratan/SuratMasukPegawaiTurunStatus');
$this->load->model('persuratan/CetakIjinBelajar');
$this->load->model('SuamiIstri');
$this->load->library('globalfilepegawai');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqBreadCrum= $this->input->get("reqBreadCrum");
$reqJenis= $this->input->get("reqJenis");
$reqJenisNama= setjenisinfo($reqJenis);
$kembali= $this->input->get("kembali");

$statement= " AND SMP.SURAT_MASUK_PEGAWAI_ID = ".$reqRowId;
$set= new SuratMasukPegawai();
$set->selectByParamsMonitoring(array(), -1, -1, $statement);
$set->firstRow();
// echo $set->query;exit;
$reqStatusBerkas= $set->getField('STATUS_BERKAS');
$reqPegawaiId= $set->getField('PEGAWAI_ID');
$reqPegawaiNama= $set->getField('NAMA_LENGKAP');
$reqPegawaiNipLama= $set->getField('NIP_LAMA');
$reqPegawaiNipBaru= $set->getField('NIP_BARU');
$reqPegawaiJabatan= $set->getField('JABATAN_RIWAYAT_NAMA');
$reqPendidikanNamaUsulan= $set->getField('PENDIDIKAN_NAMA_US');
$reqPendidikanJurusanUsulan= $set->getField('JURUSAN_US');
$reqStatusTms= $set->getField('STATUS_TMS');
$reqStatusKembali= $set->getField('STATUS_KEMBALI');
$reqStatusVerifikasi= $set->getField('STATUS_VERIFIKASI');
$reqStatusSuratKeluar= $set->getField('STATUS_SURAT_KELUAR');
//echo "--".$reqStatusVerifikasi;exit;
$reqKeteranganTeknis= $set->getField('KETERANGAN_TEKNIS');
$reqTahunSurat= $set->getField('TAHUN_SURAT');
$reqJenisKarpeg= $set->getField('JENIS_KARSU');
$reqJenisKarpegNama= $set->getField('JENIS_KARSU_NAMA');
$reqPangkatRiwayatAkhir= $set->getField('PANGKAT_RIWAYAT_KODE');
$reqSatuanKerjaNama= $set->getField('SATUAN_KERJA_PEGAWAI_USULAN_NAMA');
$reqKeterangan= $set->getField('KETERANGAN');
$reqNoSuratKehilangan= $set->getField('NO_SURAT_KEHILANGAN');
$reqTanggalSuratKehilangan= dateToPageCheck($set->getField('TANGGAL_SURAT_KEHILANGAN'));

$reqSuratMasukUptId= $set->getField('SURAT_MASUK_UPT_ID');

$reqKartuPegawaiLama= $set->getField('KARTU_PEGAWAI');

$reqKpStatusPendidikanRiwayatBelumDiakui= $set->getField("KP_STATUS_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI");
$reqKpPendidikanRiwayatBelumDiakuiId= $set->getField("KP_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI_ID");
$reqKpStatusSuratTandaLulus= $set->getField("KP_STATUS_SURAT_TANDA_LULUS");
$reqKpStatusPendidikanRiwayatIjinTugas= $set->getField("KP_STATUS_PENDIDIKAN_RIWAYAT_IJIN_TUGAS");
$reqKpStatusPendidikanRiwayatCantumGelar= $set->getField("KP_STATUS_PENDIDIKAN_RIWAYAT_CANTUM_GELAR");
$reqKpStatusStrukturalId= $set->getField("KP_STATUS_STRUKTURAL_ID");

$reqKpPakPertamaStatusId= $set->getField("KP_PAK_LAMA_STATUS");
$reqKpPakLamaId= $set->getField("KP_PAK_LAMA_ID");
$reqKpPakBaruId= $set->getField("KP_PAK_BARU_ID");
$reqKpStatusSertifikatKeaslian= $set->getField("KP_STATUS_SERTIFIKAT_KEASLIAN");
$reqKpStatusSertifikatPendidik= $set->getField("KP_STATUS_SERTIFIKAT_PENDIDIK");

$reqKategori= $set->getField('KP_JENIS');
$reqKategoriNama= $set->getField('KP_JENIS_NAMA');
unset($set);

// kalau data cek kelengkapan file
$reqStatusKirimUpt= "";
if(!empty($reqSuratMasukUptId))
{
  $statement= " AND A.SURAT_MASUK_UPT_ID = ".$reqSuratMasukUptId."";
  $set= new SuratMasukUpt();
  $set->selectByParams(array(), -1, -1, $statement);
  $set->firstRow();
  $reqStatusKirimUpt= $set->getField("STATUS_KIRIM");
  unset($set);
}
// echo $reqStatusKirimUpt;exit;

// untuk kondisi file
$vfpeg= new globalfilepegawai();
$arrpilihfiledokumen= $vfpeg->pilihfiledokumen();
// print_r($arrpilihfiledokumen);exit;

$arrsuamiistri= [];
$infomode= "kenaikanpangkat";
$arrparam= ["reqJenis"=>$reqJenis, "reqRowId"=>$reqRowId, "reqKategori"=>$reqKategori, "reqTahun"=>$reqTahunSurat, "reqMode"=>$infomode
, "reqKpStatusPendidikanRiwayatBelumDiakui"=>$reqKpStatusPendidikanRiwayatBelumDiakui
, "reqKpPendidikanRiwayatBelumDiakuiId"=>$reqKpPendidikanRiwayatBelumDiakuiId
, "reqKpStatusSuratTandaLulus"=>$reqKpStatusSuratTandaLulus
, "reqKpStatusPendidikanRiwayatIjinTugas"=>$reqKpStatusPendidikanRiwayatIjinTugas
, "reqKpStatusPendidikanRiwayatCantumGelar"=>$reqKpStatusPendidikanRiwayatCantumGelar
, "reqKpPakPertamaStatusId"=>$reqKpPakPertamaStatusId
, "reqKpPakLamaId"=>$reqKpPakLamaId
, "reqKpPakBaruId"=>$reqKpPakBaruId
, "reqKpStatusSertifikatKeaslian"=>$reqKpStatusSertifikatKeaslian
, "reqKpStatusSertifikatPendidik"=>$reqKpStatusSertifikatPendidik
, "reqKpStatusStrukturalId"=>$reqKpStatusStrukturalId
];
$arrdatainfo= $vfpeg->persyaratandata($arrparam);
$arrInfo= $arrdatainfo["syarat"];
$riwayattable= $arrdatainfo["table"];
// print_r($arrInfo);exit;

$arrlistriwayatfilepegawai= $vfpeg->listpilihfilepegawai($reqPegawaiId, $riwayattable, "", $reqRowId);
$arrlistpilihfile= $arrlistriwayatfilepegawai["pilihfile"];
// print_r($arrlistpilihfile);exit;

$infotahunppk= $reqTahunSurat;

$arrlistpilihfilefield= [];
$reqDokumenPilih= [];
foreach ($arrInfo as $key => $value)
{
  $keynomor= $value["NOMOR"];
  $keycheckfile= $value["LINK_FILE"];
  $keymode= $value["TIPE"];
  $keytable= $value["INFORMASI_TABLE"];
  $keyidsuamiistri= $value["SUAMI_ISTRI_ID"];
  $keyidanak= $value["ANAK_ID"];
  $keyinfotanpaupload= $value["INFO_TANPA_UPLOAD"];

  if($keymode == "0")
    $keymode= "";

  $keycarimode= $keynomor;
  $keycarimode= str_replace(".", "_", $keycarimode);
  // $keycarimode= $keytable."-".$keymode;
  $arrlistpilihfilefield[$keycarimode]= [];

  if($keycheckfile !== "1" && !empty($keycheckfile) && empty($keyinfotanpaupload))
  {
    // echo $keycarimode."-".$keymode."-".$keytable."\n";
    if
    (
      (
        (
          $reqKategori == "kpreguler" 
          || $reqKategori == "kppi"
          || $reqKategori == "kpstugas"
          || $reqKategori == "kpbtugas"
          || $reqKategori == "kpjft"
          || $reqKategori == "kpstruktural"
        )
        && isStrContain($keynomor, "3.")
      )
      || ($reqKategori == "kpreguler" && isStrContain($keynomor, "3."))
    )
    {
      $infotahunppk--;
      $statement= " AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2') AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.TAHUN = ".$infotahunppk;
      $set_detil= new SuratMasukPegawaiCheck();
      $set_detil->selectByParamsPenilaianSkp(array(),-1,-1, $statement);
      $set_detil->firstRow();
      // echo $set_detil->query;exit;
      $infopenilaianskpid= $set_detil->getField("PENILAIAN_SKP_ID");
      unset($set_detil);

      $arrlistpilihfilefield[$keycarimode]= $vfpeg->ambilfilemode($arrlistpilihfile, $keymode, $keytable, $infopenilaianskpid);
    }
    // pangkat akhir
    else if($keynomor == "1")
    {
      $arrlistpilihfilefield[$keycarimode]= $vfpeg->ambilfilemode($arrlistpilihfile, $keymode, "PANGKAT_RIWAYAT_AKHIR", $value["PANGKAT_RIWAYAT_ID"]);
    }
    // jabatan akhir
    else if($keynomor == "2")
    {
      $arrlistpilihfilefield[$keycarimode]= $vfpeg->ambilfilemode($arrlistpilihfile, $keymode, "JABATAN_RIWAYAT_AKHIR", $value["JABATAN_RIWAYAT_ID"]);
    }
    // pendidikan akhir
    else if
    (
      (
        (
          $reqKategori == "kpreguler" 
          || $reqKategori == "kppi"
          || $reqKategori == "kpstugas"
          || $reqKategori == "kpstruktural"
        )
        && 
        (
          (
            $keynomor >= 4 && $keynomor < 8
          )
          || $keynomor == 10
        )
      )
      || ($reqKategori == "kpjft" && $keynomor >= 6 && $keynomor <= 10)
    )
    {
      $arrlistpilihfilefield[$keycarimode]= $vfpeg->ambilfilemode($arrlistpilihfile, $keymode, "PENDIDIKAN_RIWAYAT_AKHIR", $value["PENDIDIKAN_RIWAYAT_ID"]);
    }
    // pak 1
    else if($reqKategori == "kpjft" && $keynomor == "5.1")
    {
      $arrlistpilihfilefield[$keycarimode]= $vfpeg->ambilfilemode($arrlistpilihfile, $keymode, "PAK_AKHIR", $reqKpPakLamaId);
    }
    // pak 2
    else if($reqKategori == "kpjft" && $keynomor == "5.2")
    {
      $arrlistpilihfilefield[$keycarimode]= $vfpeg->ambilfilemode($arrlistpilihfile, $keymode, "PAK_AKHIR", $reqKpPakBaruId);
    }
    else
    {
      $arrlistpilihfilefield[$keycarimode]= $vfpeg->ambilfilemode($arrlistpilihfile, $keymode, $keytable, $reqRowId);
    }

    $reqDokumenPilih[$keycarimode]= "";
    $infocari= "selected";
    $arraycari= in_array_column($infocari, "selected", $arrlistpilihfilefield[$keycarimode]);
    // print_r($arraycari);exit;
    if(!empty($arraycari))
    {
      $reqDokumenPilih[$keycarimode]= 2;
    }
  }
  else
  {
    $arrlistpilihfilefield[$keycarimode]= "continue";
    $reqDokumenPilih[$keycarimode]= "";
  }

}
// print_r($reqDokumenPilih);exit;
// print_r($arrlistpilihfilefield);exit;

$reqStatusEfileField= "upt";

if(!empty($arrInfo))
{
  $tempInfoJudul= $arrInfo[0]["JENIS_PELAYANAN_NAMA"];
}

$arrparam= ["reqRowId"=>$reqRowId];
$arrLog= $vfpeg->turunstatusdata($arrparam);

$readonly= "";
$infostatuskirimupt= "";
if($kembali == "surat_masuk_dinas_upt_add_pegawai")
{
  $infostatuskirimupt= $reqStatusKirimUpt;
}

if(!empty($infostatuskirimupt))
{
  $readonly= "disabled";
}
// echo $readonly;exit;
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

<link rel="stylesheet" type="text/css" href="css/gaya.css">

<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>

<link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
<script src="lib/autokomplit/jquery-ui.js"></script>

<script type="text/javascript">
  
    $(function(){
      $('#ff').form({
          url:'surat/surat_masuk_pegawai_check_json/validasi_efile_dinas',
          onSubmit:function(){
              $(".preloader-wrapper").show();

              if($(this).form('validate')){}
              else
              {
                  $.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
                  return false;
              }
          },
          success:function(data){
            $(".preloader-wrapper").hide();
            // console.log(data);return false;
            
            data = data.split("-");
            rowid= data[0];
            infodata= data[1];
        
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
                //parent.reloadparenttab();
                
                document.location.href= "app/loadUrl/persuratan/surat_masuk_upt_add_pegawai_lookup_verfikasi_kenaikan_pangkat?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqRowId=<?=$reqRowId?>";
              }, 1000));
              $(".mbox > .right-align").css({"display": "none"});
            }

          }
      });
    
      /*$('input[id^="reqStatusCheckBoxFix"]').click(function () {
        var id= $(this).attr('id');
        id= id.replace("reqStatusCheckBoxFix", "")
        $("#reqInfoChecked"+id).val("");
        if($(this).prop('checked')) 
        {
          $("#reqInfoChecked"+id).val("1");
        }
      });*/
   
  });
  </script>

<script type="text/javascript" src="lib/simpletabs_v1.3/js/simpletabs_1.3.js"></script>
<link href="lib/simpletabs_v1.3/css/simpletabs.css" type="text/css" rel="stylesheet">
<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

<link href="lib/mbox/mbox.css" rel="stylesheet">
<script src="lib/mbox/mbox.js"></script>
<link href="lib/mbox/mbox-modif.css" rel="stylesheet">
  
<style>
  td, th {
  padding: 5px 5px;
  display: table-cell;
  text-align: left;
  vertical-align: middle;
  border-radius: 2px;
}

.carousel .carousel-item{
  width:100%;
}

#Iframe-Master-CC-and-Rs {
  *max-width: 512px;
  max-height: 100%; 
  overflow: hidden;
}

.responsive-wrapper {
  position: relative;
  height: 0;    /* gets height from padding-bottom */
}
 
.responsive-wrapper iframe {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  
  margin: 0;
  padding: 0;
  border: none;
}

.responsive-wrapper-wxh-572x612 {
  padding-bottom: 107%;
}

.set-border {
  border: 5px inset #4f4f4f;
}
.set-box-shadow { 
  -webkit-box-shadow: 4px 4px 14px #4f4f4f;
  -moz-box-shadow: 4px 4px 14px #4f4f4f;
  box-shadow: 4px 4px 14px #4f4f4f;
}
.set-padding {
  padding: 40px;
}
.set-margin {
  margin: 30px;
}
.center-block-horiz {
  margin-left: auto !important;
  margin-right: auto !important;
}

*html,body{height:100%;}
.carousel{
    height: 100%;
  height: 100vh !important;
    margin-bottom: 60px !important;
}

.carousel .carousel-inner {
    height:100% !important;
}

.responsive-iframe {
  display: block;
  *position: relative;
  *padding-bottom: 56.25%;
  padding-bottom: 86.25%;
  *padding-top: 35px;
  height: 0;
  *height: 150vh !important;
  overflow: hidden;
}
     
.responsive-iframe iframe {
  position: absolute;
  top:0;
  left: 0;
  width: 100%;
  height: 100%;
}

.table-syarat th, .table-syarat td{
   /*background-color: teal;*/
   padding: 1px 1px !important;
   font-weight: normal !important;
   font-size: 12px !important;
}

th, td
{
  padding: 3px 8px !important;
}
</style>
</head>

<body>

<div id="basic-form" class="section">
  <div class="row">
    <div id='main' class="col s12 m12" style="padding-left: 15px;">
      <ul class="collection card">
        <li class="collection-item ubah-color-warna white-text"><?=$tempInfoJudul?></li>
        <li class="collection-item">
          <form id="ff" method="post"  novalidate enctype="multipart/form-data">
            <div class="row">
                <table class="bordered striped md-text">
                  <thead>
                      <?
                      if($reqNomorSuratKeluar == ""){}
                      else
                      {
                      ?>
                      <tr>
                          <th class="green-text material-font" style="width:20%">No Usul Ke KAnreg</th>
                          <th class="green-text material-font" style="width:1%">:</th>
                          <th colspan="4" class="green-text material-font"><?=$reqNomorSuratKeluar ?></th>
                      </tr>
                      <?
                      }
                      ?>
                      <tr>
                          <th class="green-text " style="width:20%">Jenis KP</th>
                          <th class="green-text " style="width:1%">:</th>
                          <th colspan="4" class="green-text "><?=$reqKategoriNama ?></th>
                      </tr>
                      <tr>
                          <th class="green-text material-font">Nama</th>
                          <th class="green-text material-font">:</th>
                          <th colspan="4" class="green-text material-font"><?=$reqPegawaiNama?></th>
                      </tr>
                      <tr>
                          <th class="green-text material-font">NIP Baru</th>
                          <th class="green-text material-font">:</th>
                          <th colspan="4" class="green-text material-font"><?=$reqPegawaiNipBaru?></th>
                      </tr>
                      <tr>
                          <th class="green-text material-font">Gol Terakhir</th>
                          <th class="green-text material-font">:</th>
                          <th colspan="4" class="green-text material-font"><?=$reqPangkatRiwayatAkhir?></th>
                      </tr>
                      <tr>
                          <th class="green-text material-font">Jabatan</th>
                          <th class="green-text material-font">:</th>
                          <th colspan="4" class="green-text material-font"><?=$reqPegawaiJabatan?></th>
                      </tr>
                      <tr>
                        <th class="green-text material-font">Satuan Kerja</th>
                          <th class="green-text material-font">:</th>
                          <th colspan="4" class="green-text material-font"><?=$reqSatuanKerjaNama?></th>
                      </tr>
                      <tr>
                        <th class="green-text material-font">Keterangan Petugas Teknis</th>
                          <th class="green-text material-font">:</th>
                          <th colspan="4" class="green-text material-font">
                          <input type="text" class="easyui-validatebox" id="reqKeteranganTeknis" name="reqKeteranganTeknis" <?=$read?> value="<?=$reqKeteranganTeknis?>" />
                          </th>
                      </tr>
                  </thead>
                </table>

                <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                  <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                </button>

                <?
                if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || strtoupper($this->USER_GROUP) == "ADMIN")
                {
                ?>
                <button class="btn purple waves-effect waves-light downloadfilepdf" style="font-size:9pt" type="button">Download File PDF
                  <i class="mdi-navigation-arrow-forward left hide-on-small-only"></i>
                </button>
                <?
                }
                ?>

                <?
                if(empty($readonly))
                {
                ?>
                <button type="submit" style="display:none" id="reqSubmit"></button>
                <button class="btn waves-effect waves-light green reqfilesimpan" style="font-size:9pt" type="button">
                  Simpan File
                  <i class="mdi-content-save left hide-on-small-only"></i>
                </button>
                <?
                }
                if($kembali == "surat_masuk_dinas_upt_add_pegawai")
                {
                  if($reqStatusBerkas !== "99")
                  {
                ?>
                <button class="btn waves-effect waves-light red requsulanbatal" style="font-size:9pt" type="button">
                  Batalkan
                  <i class="mdi-content-save left hide-on-small-only"></i>
                </button>
                <?
                  }
                }
                ?>
            
                <input type="hidden" name="reqPegawaiId" value="<?=$reqPegawaiId?>" />
                <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                <input type="hidden" name="reqId" value="<?=$reqId?>" />
                <input type="hidden" name="reqStatusEfileField" value="<?=$reqStatusEfileField?>" />
            </div>

            <div class="row">
              <div class="col s12 m12" style="padding-left: 0px;">
              <?
              // area untuk upload file
              foreach ($arrInfo as $key => $value)
              {
                $infonama= $value["NAMA"];
                $infonomor= $value["NOMOR"];

                // $riwayatfieldinfo= "Lihat ".$infonama;
                $riwayatfieldinfo= $infonama;

                $infonomorid= str_replace(".", "_", $infonomor);
              ?>
                <button class="btn blue waves-effect waves-light" style="font-size:9pt;<?=$riwayatfieldstyle?>" type="button" id='buttonframepdf<?=$infonomorid?>'>
                  <input type="hidden" id="labelvpdf<?=$infonomorid?>" value="<?=$riwayatfieldinfo?>" />
                  <span id="labelframepdf<?=$infonomorid?>"><?=$riwayatfieldinfo?></span>
                </button>
              <?
              }
              ?>
              </div>
            </div>

            <div class="row">
              <table class="bordered striped md-text table_list tabel-responsif" id="link-table" style="margin-bottom:10px;">
                <tr class="ubah-color-warna">
                  <!-- <th class="white-text ">Persyaratan<?=$reqPegawaiId?></th> -->
                  <th class="white-text ">Persyaratan</th>
                </tr>
                <?
                $infotahunppk= $reqTahunSurat;
                foreach ($arrInfo as $key => $value)
                {
                  $infonama= $value["NAMA"].$value["NAMA_DETIL"];
                  $infosuamiistriid= $value["SUAMI_ISTRI_ID"];
                  $infoanakid= $value["ANAK_ID"];
                  $reqDokumenFileHarusUpload= $value["REQUIRED_UPLOAD"];
                  $reqDokumenFileHarusUploadInfo= $value["REQUIRED_UPLOAD_INFO"];
                  $infonomor= $value["NOMOR"];
                  $infonomorid= str_replace(".", "_", $infonomor);
                  $infobutuhupload= $value["LINK_FILE"];
                  $infopangkatriwayatakhirid= $value["PANGKAT_RIWAYAT_ID"];
                  $infojabatanriwayatakhirid= $value["JABATAN_RIWAYAT_ID"];
                  $infopendidikanriwayatakhirid= $value["PENDIDIKAN_RIWAYAT_ID"];
                  $infogajiriwayatakhirid= $value["GAJI_RIWAYAT_ID"];
                  $vinforiwayattable= $value["INFORMASI_TABLE"];
                  
                  $arrparam= ["value"=>$value];
                  $arrparam= array_merge($arrparam, $arrsuamiistri);

                  $infonamadetil= $vfpeg->ambilinfodetil($arrparam);

                  $reqDokumenKategoriField= $value["TIPE"];
                  if($reqDokumenKategoriField == "0")
                    $reqDokumenKategoriField= "";

                  $cheksimpan= "";
                  $reqDokumenKategoriFileId= $value["KATEGORI_FILE_ID"];

                  if($arrlistpilihfilefield[$infonomorid] !== "continue")
                  {
                    $cheksimpan= "1";
                  }

                  if
                  (
                    (
                      (
                        $reqKategori == "kpreguler" 
                        || $reqKategori == "kppi"
                        || $reqKategori == "kpstugas"
                        || $reqKategori == "kpbtugas"
                        || $reqKategori == "kpjft"
                        || $reqKategori == "kpstruktural"
                      )
                      && isStrContain($infonomor, "3.")
                    )
                  || ($reqKategori == "kpreguler" && isStrContain($infonomor, "3."))
                  )
                  {
                    $infotahunppk--;
                  }

                  $arrparam= ["reqKategori"=>$reqKategori, "reqJenis"=>$reqJenis, "infotipe"=>$reqDokumenKategoriField, "infonomor"=>$infonomor, "infopegawaiid"=>$reqPegawaiId, "infokategorifileid"=>$reqDokumenKategoriFileId, "infotahunppk"=>$infotahunppk, "infosuamiistriid"=>$infosuamiistriid, "infoanakid"=>$infoanakid, "infopangkatriwayatakhirid"=>$infopangkatriwayatakhirid, "infogajiriwayatakhirid"=>$infogajiriwayatakhirid, "infopendidikanriwayatakhirid"=>$infopendidikanriwayatakhirid, "infojabatanriwayatakhirid"=>$infojabatanriwayatakhirid, "reqKpPakLamaId"=>$reqKpPakLamaId, "reqKpPakBaruId"=>$reqKpPakBaruId];

                  $arrparam= array_merge($arrparam, $arrsuamiistri);
                  $arrinforiwayat= $vfpeg->ambilinforiwayat($arrparam);

                  $reqDokumenFileRiwayatTable= $arrinforiwayat["RIWAYAT_TABLE"];
                  $reqDokumenFileRiwayatId= $arrinforiwayat["RIWAYAT_ID"];
                  // $reqDokumenKategoriField= $arrinforiwayat["RIWAYAT_FIELD"];

                  // $riwayatfield= $value["riwayatfield"];
                  // $riwayatfieldtipe= $value["riwayatfieldtipe"];
                  $riwayatfieldtipe= $value["jenisdokumen"];

                  // khusus kehilangan
                  $ambilriwayatfield= "";
                  if($vinforiwayattable == "PERSURATAN.SURAT_MASUK_PEGAWAI")
                    $ambilriwayatfield= "1";

                  if($ambilriwayatfield == "1")
                  {
                    $reqDokumenKategoriField= $reqRowId;
                  }

                  $riwayatfieldinfo= "Lihat ".$infonama;
                ?>
                  <tr>
                    <td style="text-align:left; width:18%">
                      <?=$reqDokumenFileHarusUploadInfo.$infonama?>

                      <?
                      if(!empty($infonamadetil))
                      {
                      ?>
                        <span style="color: red">: <?=$infonamadetil?></span>
                      <?
                      }
                      ?>

                      <?
                      if(!empty($cheksimpan))
                      {
                      ?>
                      <input type="hidden" id="infobutuhupload<?=$infonomorid?>" name="infobutuhupload[]" value="<?=$infobutuhupload?>" />
                      <input type="hidden" id="reqDokumenFileId<?=$infonomorid?>" name="reqDokumenFileId[]" />
                      <input type="hidden" id="reqDokumenKategoriFileId<?=$infonomorid?>" name="reqDokumenKategoriFileId[]" value="<?=$reqDokumenKategoriFileId?>" />
                      <input type="hidden" id="reqDokumenKategoriField<?=$infonomorid?>" name="reqDokumenKategoriField[]" value="<?=$reqDokumenKategoriField?>" />
                      <input type="hidden" id="reqDokumenPath<?=$infonomorid?>" name="reqDokumenPath[]" value="" />
                      <input type="hidden" id="reqDokumenTipe<?=$infonomorid?>" name="reqDokumenTipe[]" value="<?=$riwayatfieldtipe?>" />
                      <input type="hidden" id="reqDokumenFileKualitasId<?=$infonomorid?>" name="reqDokumenFileKualitasId[]" value="" />
                      <input type="hidden" id="reqDokumenFileRiwayatTable<?=$infonomorid?>" name="reqDokumenFileRiwayatTable[]" value="<?=$reqDokumenFileRiwayatTable?>" />
                      <!-- apabila riwayat lebih dari satu field -->
                      <input type="hidden" id="reqDokumenFileRiwayatId<?=$infonomorid?>" name="reqDokumenFileRiwayatId[]" value="<?=$reqDokumenFileRiwayatId?>" />
                      <input type="hidden" id="reqDokumenFileHarusUpload<?=$infonomorid?>" name="reqDokumenFileHarusUpload[]" value="<?=$reqDokumenFileHarusUpload?>" />
                      <?
                      }
                      ?>

                    </td>
                  </tr>

                  <?
                  if(!empty($cheksimpan))
                  {
                  ?>
                  <tr>
                    <td>
                      <table class="bordered striped md-text table_list tabel-responsif table-syarat" style="margin-bottom:10px;">
                        <tr>
                          <td>
                            File Dokumen
                          </td>
                          <td>
                            <select id="reqDokumenPilih<?=$infonomorid?>" name="reqDokumenPilih[]" <?=$readonly?>>
                              <?
                              foreach ($arrpilihfiledokumen as $key => $value)
                              {
                                $optionid= $value["id"];
                                $optiontext= $value["nama"];
                              ?>
                                <option value="<?=$optionid?>" <? if($reqDokumenPilih[$infonomorid] == $optionid) echo "selected";?>><?=$optiontext?></option>
                              <?
                              }
                              ?>
                            </select>
                          </td>
                        </tr>
                        <tr>
                          <th>
                            Nama e-File
                          </th>
                          <td>
                            <div id="labeldokumenfileupload<?=$infonomorid?>">
                              <div class="file_input_div">
                                <div class="file_input input-field col s12 m12">
                                  <label class="labelupload">
                                    <i class="mdi-file-file-upload" style="font-family: "Roboto",sans-serif,Material-Design-Icons !important; font-size: 14px !important;">Upload</i>
                                    <input id="file_input_file" name="reqLinkFile[]" class="none" type="file" />
                                  </label>
                                </div>
                                <div id="file_input_text_div" class=" input-field col s1 m1">
                                  <input class="file_input_text" type="text" disabled readonly id="file_input_text" />
                                  <label for="file_input_text"></label>
                                </div>
                              </div>
                            </div>

                            <div id="labeldokumendarifileupload<?=$infonomorid?>">
                              <select id="reqDokumenIndexId<?=$infonomorid?>" <?=$readonly?>>
                                <option value="" selected></option>
                                <?
                                $arrlistpilihfilepegawai= $arrlistpilihfilefield[$infonomorid];
                                foreach ($arrlistpilihfilepegawai as $key => $value)
                                {
                                  $optionid= $value["index"];
                                  $optiontext= $value["nama"];
                                  $optionselected= $value["selected"];
                                ?>
                                  <option value="<?=$optionid?>" <?=$optionselected?>><?=$optiontext?></option>
                                <?
                                }
                                ?>
                              </select>
                            </div>

                          </td>
                        </tr>
                        <!-- <tr>
                          <th colspan="2">
                            <button class="btn blue waves-effect waves-light" style="font-size:9pt;<?=$infonama?>" type="button" id='buttonframepdf<?=$infonomor?>'>
                              <input type="hidden" id="labelvpdf<?=$infonomorid?>" value="<?=$riwayatfieldinfo?>" />
                              <span id="labelframepdf<?=$infonomorid?>"><?=$riwayatfieldinfo?></span>
                            </button>
                          </th>
                        </tr> -->
                      </table>
                    </td>
                  </tr>
                <?
                  }
                }
                ?>
              </table>
            </div>

            <?
            if(empty($readonly))
            {
            ?>
            <div class="row">
              <button class="btn waves-effect waves-light green reqfilesimpan" style="font-size:9pt" type="button">
                Simpan File
                <i class="mdi-content-save left hide-on-small-only"></i>
              </button>
            </div>
            <?
            }
            if($kembali == "surat_masuk_dinas_upt_add_pegawai")
            {
              if($reqStatusBerkas !== "99")
              {
            ?>
            <div class="row">
              <button class="btn waves-effect waves-light red requsulanbatal" style="font-size:9pt" type="button">
                Batalkan
                <i class="mdi-content-save left hide-on-small-only"></i>
              </button>
            </div>
            <?
              }
            }
            ?>
          </form>
        </li>
      </ul>
    </div>

    <div id='divframepdf' class="col s12 m6" style="padding-top: 10px;">
      <input type="hidden" id="vnewframe" value="">
      <span id="labelriwayatframepdf"></span>
      <img id="infonewimage" style="width:inherit; width: 100%; height: 100%" />
      <iframe id="infonewframe" style="width: 100%; height: 160%" src=""></iframe>
    </div>

  </div>
</div>

<div class="preloader-wrapper big active loader">
  <div class="spinner-layer spinner-blue-only">
    <div class="circle-clipper left">
      <div class="circle"></div>
    </div><div class="gap-patch">
      <div class="circle"></div>
    </div><div class="circle-clipper right">
      <div class="circle"></div>
    </div>
  </div>
</div>

<style type="text/css">
  .select-dropdown {
    max-height:250px !important; overflow:auto !important;
  }
</style>

<link href="lib/materializetemplate/css/materializeslide.css" rel="stylesheet" />
<script src="lib/materializetemplate/js/materializeslide.min.js"></script>

<script>
$(document).ready( function () {
  $(".preloader-wrapper").hide();
  $('select').material_select();

  // untuk area untuk upload file
  vbase_url= "<?=base_url()?>";
  getarrlistpilihfilefield= JSON.parse('<?=JSON_encode($arrlistpilihfilefield)?>');
  // console.log(getarrlistpilihfilefield);
  // untuk area untuk upload file
  
  $("#kembali").click(function() {
    kembali= "<?=$kembali?>";
    if(kembali == "")
      infolinkurl= "surat_masuk_upt_add_pegawai";
    else
      infolinkurl= kembali;
    document.location.href = "app/loadUrl/persuratan/"+infolinkurl+"?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>";
  });

  $(".downloadfilepdf").click(function() {
    mbox.custom({
       message: "Apakah Anda Yakin, download file pdf data terpilih ?",
       options: {close_speed: 100},
       buttons: [
         {
           label: 'Ya',
           color: 'green darken-2',
           callback: function() {

             $(".preloader-wrapper").show();

             var s_url= "app/loadUrl/persuratan/download_file?reqMode=personal&reqId=<?=$reqRowId?>";
              $.ajax({'url': s_url,'success': function(dataajax){
              var requrl= requrllist= "";
              dataajax= String(dataajax);
              var element = dataajax.split('-'); 
              dataajax= element[0];
              requrl= element[1];
              requrllist= element[2];
              requrlcss= element[3];

              if(dataajax == '0')
              {
                $(".preloader-wrapper").hide();
                mbox.alert('File download belum ada', {open_speed: 0});
              }
              else
              {
                $(".preloader-wrapper").hide();
                newWindow = window.open("app/loadUrl/persuratan/download_file?reqDownload=1&reqMode=personal&reqId=<?=$reqRowId?>", 'Cetak');
                // newWindow = window.open(dataajax, 'Cetak');
                newWindow.focus();
              }
              }});

             //console.log('do action for yes answer');
             mbox.close();
           }
         },
         {
           label: 'Tidak',
           color: 'grey darken-2',
           callback: function() {
             //console.log('do action for no answer');
             $(".preloader-wrapper").hide();
             mbox.close();
           }
         }
       ]
    });

  });

  $(".requsulanbatal").click(function() { 
    parent.openModal('app/loadUrl/persuratan/surat_masuk_upt_add_batal?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqRowId=<?=$reqRowId?>&kembali=surat_masuk_upt_add_pegawai_lookup_verfikasi_kenaikan_pangkat')
  });

  function setUrlInfo(url)
  {
      document.location.href= url;
  }

});
</script>

<script type="text/javascript" src="lib/easyui/pelayanan-efile.js"></script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>
</body>
</html>