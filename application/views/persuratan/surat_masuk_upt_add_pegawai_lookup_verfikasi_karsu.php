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
$set->selectByParamsMonitoringKaris(array(), -1, -1, $statement);
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

$infosuamiistrisekarang= $set->getField('SUAMI_ISTRI_ID');
$infosuamiistrisebelum= $set->getField('SUAMI_ISTRI_PISAH_ID');
$infopernikahanpertamapns= $set->getField('SUAMI_ISTRI_PERTAMA_PNS_STATUS');
$infopernikahanpertamapnsnama= $set->getField('SUAMI_ISTRI_PERTAMA_PNS_STATUS_NAMA');
$infopernikahanpertamapasanganstatus= $set->getField('SUAMI_ISTRI_PERTAMA_PASANGAN_STATUS');
$infopernikahanpertamapasanganstatusnama= $set->getField('SUAMI_ISTRI_PERTAMA_PASANGAN_STATUS_NAMA');
$infopernikahanpertamastatus= $set->getField('SUAMI_ISTRI_PERTAMA_PNS_STATUS_S_I');
$infopernikahanpertamastatusnama= $set->getField('SUAMI_ISTRI_PERTAMA_PNS_STATUS_S_I_NAMA');
$infopernikahanpasanganstatus= $set->getField('SUAMI_ISTRI_PERTAMA_PASANGAN_STATUS_S_I');
$infopernikahanpasanganstatusnama= $set->getField('SUAMI_ISTRI_PERTAMA_PASANGAN_STATUS_S_I_NAMA');
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

$statement= " AND A.SUAMI_ISTRI_ID = ".$infosuamiistrisekarang;
$set= new SuamiIstri();
$set->selectByParams(array(), -1, -1, $statement);
// echo $set->query;exit();
$set->firstRow();
$infopasangansaatinipns= $set->getField("STATUS_PNS");
$infopasangansaatinipnsnama= $set->getField("STATUS_PNS_NAMA");

// untuk kondisi file
$vfpeg= new globalfilepegawai();
$arrpilihfiledokumen= $vfpeg->pilihfiledokumen();
// print_r($arrpilihfiledokumen);exit;

$arrparam= ["reqPegawaiId"=>$reqPegawaiId];
$ambilcpnspns= $vfpeg->ambilcpnspns($arrparam);
// print_r($ambilcpnspns);exit;
$cpnspangkatriwayatid= $ambilcpnspns["cpnspangkatriwayatid"];
$pnspangkatriwayatid= $ambilcpnspns["pnspangkatriwayatid"];
$pnsjalurpengangkatan= $ambilcpnspns["pnsjalurpengangkatan"];

$arrsuamiistri= ["infosuamiistrisekarang"=>$infosuamiistrisekarang, "infosuamiistrisebelum"=>$infosuamiistrisebelum, "infopernikahanpertamapns"=>$infopernikahanpertamapns, "infopernikahanpertamapnsnama"=>$infopernikahanpertamapnsnama, "infopernikahanpertamapasanganstatus"=>$infopernikahanpertamapasanganstatus, "infopernikahanpertamapasanganstatusnama"=>$infopernikahanpertamapasanganstatusnama, "infopasangansaatinipns"=>$infopasangansaatinipns, "infopasangansaatinipnsnama"=>$infopasangansaatinipnsnama, "infopernikahanpertamastatus"=>$infopernikahanpertamastatus, "infopernikahanpertamastatusnama"=>$infopernikahanpertamastatusnama, "infopernikahanpasanganstatus"=>$infopernikahanpasanganstatus, "infopernikahanpasanganstatusnama"=>$infopernikahanpasanganstatusnama];

$infomode= "karsu";
$arrparam= ["reqJenis"=>$reqJenis, "reqRowId"=>$reqRowId, "reqJenisKarpeg"=>$reqJenisKarpeg, "reqMode"=>$infomode, "pnsjalurpengangkatan"=>$pnsjalurpengangkatan];
$arrparam= array_merge($arrparam, $arrsuamiistri);
$arrdatainfo= $vfpeg->persyaratandata($arrparam);
$arrInfo= $arrdatainfo["syarat"];
$riwayattable= $arrdatainfo["table"];
// print_r($arrInfo);exit;

$arrlistriwayatfilepegawai= $vfpeg->listpilihfilepegawai($reqPegawaiId, $riwayattable, "", $reqRowId);
$arrlistpilihfile= $arrlistriwayatfilepegawai["pilihfile"];
if($reqRowId == 8255)
{
  // print_r($arrlistpilihfile);exit;
}

$arrlistpilihfilefield= [];
$reqDokumenPilih= [];
foreach ($arrInfo as $key => $value)
{
  $keynomor= $value["NOMOR"];
  $keycheckfile= $value["LINK_FILE"];
  $keymode= $value["TIPE"];
  $keytable= $value["INFORMASI_TABLE"];

  if($keymode == "0")
    $keymode= "";

  if($keytable == "PERSURATAN.SURAT_MASUK_PEGAWAI")
  {
    // $keymode= ";80;".$reqRowId;
    $keymode= ";80";
  }

  $keycarimode= $keynomor;
  // $keycarimode= $keytable."-".$keymode;
  $arrlistpilihfilefield[$keycarimode]= [];

  if($keycheckfile !== "1" && !empty($keycheckfile))
  {
    // echo $keycarimode."-".$keymode."-".$keytable."\n";

    // khusus kehilangan
    if($keynomor == "9" || $keynomor == "11")
    {
      $arrlistpilihfilefield[$keycarimode]= $vfpeg->ambilfilemode($arrlistpilihfile, $keymode, $keytable, $infosuamiistrisebelum);
    }
    else if($keynomor == "5" || $keynomor == "10" || $keynomor == "12")
    {
      $arrlistpilihfilefield[$keycarimode]= $vfpeg->ambilfilemode($arrlistpilihfile, $keymode, $keytable, $infosuamiistrisekarang);
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
                
                document.location.href= "app/loadUrl/persuratan/surat_masuk_upt_add_pegawai_lookup_verfikasi_karsu?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqRowId=<?=$reqRowId?>";
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
                    <tr>
                        <th class="green-text " style="width:20%">Jenis Usul</th>
                          <th class="green-text " style="width:1%">:</th>
                          <th colspan="4" class="green-text "><?=$reqJenisKarpegNama ?></th>
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
              ?>
                <button class="btn blue waves-effect waves-light" style="font-size:9pt;<?=$riwayatfieldstyle?>" type="button" id='buttonframepdf<?=$infonomor?>'>
                  <input type="hidden" id="labelvpdf<?=$infonomor?>" value="<?=$riwayatfieldinfo?>" />
                  <span id="labelframepdf<?=$infonomor?>"><?=$riwayatfieldinfo?></span>
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
                foreach ($arrInfo as $key => $value)
                {
                  $infonama= $value["NAMA"];
                  $infonomor= $value["NOMOR"];
                  $infobutuhupload= $value["LINK_FILE"];
                  $vinforiwayattable= $value["INFORMASI_TABLE"];
                  
                  $arrparam= ["value"=>$value];
                  $arrparam= array_merge($arrparam, $arrsuamiistri);

                  $infonamadetil= $vfpeg->ambilinfodetil($arrparam);

                  $reqDokumenKategoriField= $value["TIPE"];
                  if($reqDokumenKategoriField == "0")
                    $reqDokumenKategoriField= "";

                  $cheksimpan= "";
                  $reqDokumenKategoriFileId= $value["KATEGORI_FILE_ID"];

                  if($arrlistpilihfilefield[$infonomor] !== "continue")
                  {
                    $cheksimpan= "1";
                  }

                  $arrparam= ["reqJenis"=>$reqJenis, "infotipe"=>$reqDokumenKategoriField, "infonomor"=>$infonomor, "infopegawaiid"=>$reqPegawaiId, "infokategorifileid"=>$reqDokumenKategoriFileId];
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
                      <?=$infonama?>

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
                      <input type="hidden" id="infobutuhupload<?=$infonomor?>" name="infobutuhupload[]" value="<?=$infobutuhupload?>" />
                      <input type="hidden" id="reqDokumenFileId<?=$infonomor?>" name="reqDokumenFileId[]" />
                      <input type="hidden" id="reqDokumenKategoriFileId<?=$infonomor?>" name="reqDokumenKategoriFileId[]" value="<?=$reqDokumenKategoriFileId?>" />
                      <input type="hidden" id="reqDokumenKategoriField<?=$infonomor?>" name="reqDokumenKategoriField[]" value="<?=$reqDokumenKategoriField?>" />
                      <input type="hidden" id="reqDokumenPath<?=$infonomor?>" name="reqDokumenPath[]" value="" />
                      <input type="hidden" id="reqDokumenTipe<?=$infonomor?>" name="reqDokumenTipe[]" value="<?=$riwayatfieldtipe?>" />
                      <input type="hidden" id="reqDokumenFileKualitasId<?=$infonomor?>" name="reqDokumenFileKualitasId[]" value="" />
                      <input type="hidden" id="reqDokumenFileRiwayatTable<?=$infonomor?>" name="reqDokumenFileRiwayatTable[]" value="<?=$reqDokumenFileRiwayatTable?>" />
                      <input type="hidden" id="reqDokumenFileRiwayatId<?=$infonomor?>" name="reqDokumenFileRiwayatId[]" value="<?=$reqDokumenFileRiwayatId?>" />
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
                            <select id="reqDokumenPilih<?=$infonomor?>" name="reqDokumenPilih[]" <?=$readonly?>>
                              <?
                              foreach ($arrpilihfiledokumen as $key => $value)
                              {
                                $optionid= $value["id"];
                                $optiontext= $value["nama"];
                              ?>
                                <option value="<?=$optionid?>" <? if($reqDokumenPilih[$infonomor] == $optionid) echo "selected";?>><?=$optiontext?></option>
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
                            <div id="labeldokumenfileupload<?=$infonomor?>">
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

                            <div id="labeldokumendarifileupload<?=$infonomor?>">
                              <select id="reqDokumenIndexId<?=$infonomor?>" <?=$readonly?>>
                                <option value="" selected></option>
                                <?
                                $arrlistpilihfilepegawai= $arrlistpilihfilefield[$infonomor];
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
                              <input type="hidden" id="labelvpdf<?=$infonomor?>" value="<?=$riwayatfieldinfo?>" />
                              <span id="labelframepdf<?=$infonomor?>"><?=$riwayatfieldinfo?></span>
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

  $(".requsulanbatal").click(function() { 
    parent.openModal('app/loadUrl/persuratan/surat_masuk_upt_add_batal?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqRowId=<?=$reqRowId?>&kembali=surat_masuk_upt_add_pegawai_lookup_verfikasi_karsu')
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