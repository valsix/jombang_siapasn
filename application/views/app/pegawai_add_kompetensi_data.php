<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('PenilaianKompetensi');
$this->load->model("JabatanRiwayat");
$this->load->model('KualitasFile');
$this->load->library('globalfilepegawai');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
// echo $reqId; exit;
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "010703";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

$infomode= "TAMBAH";
if($reqRowId=="")
{
  $reqMode = 'insert';
}
else
{
  $infomode= "UBAH";
  $reqMode = 'update';
}

$statement= " AND A.PENILAIAN_KOMPETENSI_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
$set = new PenilaianKompetensi();
$set->selectByParams(array(), -1, -1, $statement);
// echo $set->query; exit;
$set->firstRow();
$reqJabatanRiwayatId= $set->getField('JABATAN_RIWAYAT_ID');
$reqTanggalKompetensi= dateToPageCheck($set->getField('TANGGAL_KOMPETENSI'));
$reqTanggalMulai= dateToPageCheck($set->getField('TANGGAL_MULAI'));
$reqTanggalSelesai= dateToPageCheck($set->getField('TANGGAL_SELESAI'));
$reqTahun= $set->getField('TAHUN');
$reqAsesor= $set->getField('ASESOR');

$reqIntegritasNilai= dotToComma($set->getField("INTEGRITAS_NILAI"));
$reqKerjasamaNilai= dotToComma($set->getField("KERJASAMA_NILAI"));
$reqKomunikasiNilai= dotToComma($set->getField("KOMUNIKASI_NILAI"));
$reqOrientasiNilai= dotToComma($set->getField("ORIENTASI_NILAI"));
$reqPelayananPublikNilai= dotToComma($set->getField("PELAYANAN_PUBLIK_NILAI"));
$reqPengembanganDiriNilai= dotToComma($set->getField("PENGEMBANGAN_DIRI_NILAI"));
$reqKelolaPerubahanNilai= dotToComma($set->getField("KELOLA_PERUBAHAN_NILAI"));
$reqAmbilKeputusanNilai= dotToComma($set->getField("AMBIL_KEPUTUSAN_NILAI"));
$reqPerekatBangsaNilai= dotToComma($set->getField("PEREKAT_BANGSA_NILAI"));
$reqSkorKompetensi= dotToComma($set->getField("SKOR_KOMPETENSI"));

$reqNilaiIndeksKompetensi= $set->getField('NILAI_INDEKS_KOMPETENSI');
$reqKesimpulan= $set->getField('KESIMPULAN');
$reqPenyelenggara= $set->getField('PENYELENGGARA');
$reqDeskripsi= $set->getField('DESKRIPSI');

if(!empty($reqJabatanRiwayatId))
{
  $statementdetil= " AND A.JABATAN_RIWAYAT_ID = ".$reqJabatanRiwayatId;
  $setdetil= new JabatanRiwayat();
  $setdetil->selectByParams(array(), -1, -1, $statementdetil);
  $setdetil->firstRow();
  $reqJabatanRiwayatNama= $setdetil->getField("NAMA");
  $reqJabatanRiwayatTmt= dateTimeToPageCheck($setdetil->getField("TMT_JABATAN"));
  $reqJabatanRiwayatUnitKerja= $setdetil->getField("SATKER_NAMA");
}

$arrkesimpulan= array(
  array("value"=>"Belum memenuhi syarat")
  , array("value"=>"Masih memenuhi syarat")
  , array("value"=>"Memenuhi syarat")
);

// untuk kondisi file
$vfpeg= new globalfilepegawai();
$arrpilihfiledokumen= $vfpeg->pilihfiledokumen();
// print_r($arrpilihfiledokumen);exit;

$riwayattable= "PENILAIAN_KOMPETENSI";
$reqDokumenKategoriFileId= "26"; // ambil dari table KATEGORI_FILE, cek sesuai mode
$arrsetriwayatfield= $vfpeg->setriwayatfield($riwayattable);
// print_r($arrsetriwayatfield);exit;

if(empty($reqRowId))
  $arrlistriwayatfilepegawai= $vfpeg->listpilihfilepegawai($reqId, $riwayattable, "baru");
else
  $arrlistriwayatfilepegawai= $vfpeg->listpilihfilepegawai($reqId, $riwayattable, $reqRowId);

$arrlistpilihfile= $arrlistriwayatfilepegawai["pilihfile"];
// print_r($arrlistpilihfile);exit;
$arrlistriwayat= $arrlistriwayatfilepegawai["riwayat"];

// $keymode= $riwayattable.";".$reqRowId.";foto";

$arrlistpilihfilefield= [];
$reqDokumenPilih= [];
foreach ($arrsetriwayatfield as $key => $value)
{
  $keymode= $value["riwayatfield"];
  $arrlistpilihfilefield[$keymode]= [];

  if(!empty($arrlistpilihfile))
  {
    $arrlistpilihfilefield[$keymode]= $vfpeg->ambilfilemode($arrlistpilihfile, $keymode);

    $reqDokumenPilih[$keymode]= "";
    $infocari= "selected";
    $arraycari= in_array_column($infocari, "selected", $arrlistpilihfilefield[$keymode]);
    // print_r($arraycari);exit;
    if(!empty($arraycari))
    {
      // print_r($arraycari);exit;
      $reqDokumenPilih[$keymode]= 2;
    }
  }
}
// print_r($reqDokumenPilih);exit;
// print_r($arrlistpilihfilefield);exit;

$set= new KualitasFile();
$set->selectByParams(array());
// echo $set->query;exit;
$arrkualitasfile=[];
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["ID"]= $set->getField("KUALITAS_FILE_ID");
  $arrdata["TEXT"]= $set->getField("NAMA");
  array_push($arrkualitasfile, $arrdata);
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

<script type="text/javascript" src='lib/datepickernew/jquery-1.8.3.min.js'></script>
<script type="text/javascript" src='lib/datepickernew/bootstrap.min.js'></script>
<link rel="stylesheet" href='lib/datepickernew/bootstrap.min.css' media="screen" />
<link rel="stylesheet" href="lib/datepickernew/bootstrap-datepicker.css" type="text/css" />
<script src="lib/datepickernew/bootstrap-datepicker.js" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
<!-- <script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script> -->
<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

 <!-- AUTO KOMPLIT -->
<link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
<script src="lib/autokomplit/jquery-ui.js"></script>

<script type="text/javascript"> 
  $(function(){
    $('#ff').form({
      url:'penilaian_kompetensi_json/add',
      onSubmit:function(){
        if($(this).form('validate')){}
        else
        {
          $.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
          return false;
        }
      },
      success:function(data){
        // console.log(data);return false;
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
            document.location.href= "app/loadUrl/app/pegawai_add_kompetensi_data/?reqId=<?=$reqId?>&reqRowId="+rowid;
          }, 1000));
          $(".mbox > .right-align").css({"display": "none"});
        }
        
      }
    });

  });

</script>

<!-- CORE CSS-->    
<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<!-- CSS style Horizontal Nav-->    
<link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
<!-- Custome CSS-->    
<link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

<link href="lib/mbox/mbox.css" rel="stylesheet">
<script src="lib/mbox/mbox.js"></script>
<link href="lib/mbox/mbox-modif.css" rel="stylesheet">

</head>

<body>    
  <!--Basic Form-->
  <div id="basic-form" class="section">
    <div class="row">
     <div id='main' class="col s12 m12" style="padding-left: 15px;">

       <ul class="collection card">
         <li class="collection-item ubah-color-warna"><?=$infomode?> KOMPETENSI</li>
         <li class="collection-item">

            <form id="ff" method="post" enctype="multipart/form-data">
            
              <div class="row">
                <div class="input-field col s12 m2">
                  <label class="active" for="reqTanggalKompetensi">Tgl Hasil Kompetensi</label>
                  <table>
                    <tr> 
                      <td style="padding: 0px;">
                        <input placeholder="" required class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalKompetensi" id="reqTanggalKompetensi" value="<?=$reqTanggalKompetensi?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalKompetensi');" />
                      </td>
                      <td style="padding: 0px;">
                        <label class="input-group-btn" for="reqTanggalKompetensi" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
                          <span class="mdi-notification-event-note"></span>
                        </label>
                      </td>
                    </tr>
                  </table>
                </div>
                <div class="input-field col s12 m2">
                  <label class="active" for="reqTanggalMulai">Tgl Mulai</label>
                  <table>
                    <tr> 
                      <td style="padding: 0px;">
                        <input placeholder="" required class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalMulai" id="reqTanggalMulai" value="<?=$reqTanggalMulai?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalMulai');" />
                      </td>
                      <td style="padding: 0px;">
                        <label class="input-group-btn" for="reqTanggalMulai" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
                          <span class="mdi-notification-event-note"></span>
                        </label>
                      </td>
                    </tr>
                  </table>
                </div>
                <div class="input-field col s12 m2">
                  <label class="active" for="reqTanggalSelesai">Tgl Selesai</label>
                  <table>
                    <tr> 
                      <td style="padding: 0px;">
                        <input placeholder="" required class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalSelesai" id="reqTanggalSelesai" value="<?=$reqTanggalSelesai?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalSelesai');" />
                      </td>
                      <td style="padding: 0px;">
                        <label class="input-group-btn" for="reqTanggalSelesai" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
                          <span class="mdi-notification-event-note"></span>
                        </label>
                      </td>
                    </tr>
                  </table>
                </div>
                <div class="input-field col s12 m2">
                  <label for="reqTahun">Tahun</label>
                  <input type="hidden" name="reqTahun" id="reqTahun" value="<?=$reqTahun?>" />
                  <input placeholder="" type="text" id="reqTahunText" disabled value="<?=$reqTahun?>" />
                </div>
                <div class="input-field col s12 m4">
                  <label for="reqAsesor">Asesor </label>
                  <input placeholder="" type="text" name="reqAsesor" <?=$read?> value="<?=$reqAsesor?>" id="reqAsesor" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqJabatanRiwayatNama">Nama jabatan saat tes</label>
                  <input type="hidden" name="reqJabatanRiwayatId" id="reqJabatanRiwayatId" value="<?=$reqJabatanRiwayatId?>" />
                  <input placeholder="" type="text" id="reqJabatanRiwayatNama" disabled value="<?=$reqJabatanRiwayatNama?>" />
                </div>
                <div class="input-field col s12 m2">
                  <label for="reqJabatanRiwayatTmt">TMT Jabatan</label>
                  <input placeholder="" type="text" id="reqJabatanRiwayatTmt" disabled value="<?=$reqJabatanRiwayatTmt?>" />
                </div>
              </div>
              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqJabatanRiwayatUnitKerja">OPD/Unit Kerja</label>
                  <input placeholder="" type="text" id="reqJabatanRiwayatUnitKerja" disabled value="<?=$reqJabatanRiwayatUnitKerja?>" />
                </div>
              </div>
              <div class="row">
                <div class="input-field col s12 m2">
                  <label for="reqIntegritasNilai">Integritas</label>
                  <input placeholder="" <?=$read?> type="text" name="reqIntegritasNilai" id="reqIntegritasNilai" onkeypress='kreditvalidate(event, this)' value="<?=$reqIntegritasNilai?>" />
                </div>
                <div class="input-field col s12 m2">
                  <label for="reqKerjasamaNilai">Kerjasama</label>
                  <input placeholder="" <?=$read?> type="text" name="reqKerjasamaNilai" id="reqKerjasamaNilai" onkeypress='kreditvalidate(event, this)' value="<?=$reqKerjasamaNilai?>" />
                </div>
                <div class="input-field col s12 m2">
                  <label for="reqKomunikasiNilai">Komunikasi</label>
                  <input placeholder="" <?=$read?> type="text" name="reqKomunikasiNilai" id="reqKomunikasiNilai" onkeypress='kreditvalidate(event, this)' value="<?=$reqKomunikasiNilai?>" />
                </div>
                <div class="input-field col s12 m2">
                  <label for="reqOrientasiNilai">Orientasi Pada Hasil</label>
                  <input placeholder="" <?=$read?> type="text" name="reqOrientasiNilai" id="reqOrientasiNilai" onkeypress='kreditvalidate(event, this)' value="<?=$reqOrientasiNilai?>" />
                </div>
                <div class="input-field col s12 m2">
                  <label for="reqPelayananPublikNilai">Pelayanan Publik</label>
                  <input placeholder="" <?=$read?> type="text" name="reqPelayananPublikNilai" id="reqPelayananPublikNilai" onkeypress='kreditvalidate(event, this)' value="<?=$reqPelayananPublikNilai?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m3">
                  <label for="reqPengembanganDiriNilai">Pengembangan diri dan Orang lain</label>
                  <input placeholder="" <?=$read?> type="text" name="reqPengembanganDiriNilai" id="reqPengembanganDiriNilai" onkeypress='kreditvalidate(event, this)' value="<?=$reqPengembanganDiriNilai?>" />
                </div>
                <div class="input-field col s12 m2">
                  <label for="reqKelolaPerubahanNilai">Mengelola Perubahan</label>
                  <input placeholder="" <?=$read?> type="text" name="reqKelolaPerubahanNilai" id="reqKelolaPerubahanNilai" onkeypress='kreditvalidate(event, this)' value="<?=$reqKelolaPerubahanNilai?>" />
                </div>
                <div class="input-field col s12 m2">
                  <label for="reqAmbilKeputusanNilai">Pengambilan Keputusan</label>
                  <input placeholder="" <?=$read?> type="text" name="reqAmbilKeputusanNilai" id="reqAmbilKeputusanNilai" onkeypress='kreditvalidate(event, this)' value="<?=$reqAmbilKeputusanNilai?>" />
                </div>
                <div class="input-field col s12 m2">
                  <label for="reqPerekatBangsaNilai">Perekat Bangsa</label>
                  <input placeholder="" <?=$read?> type="text" name="reqPerekatBangsaNilai" id="reqPerekatBangsaNilai" onkeypress='kreditvalidate(event, this)' value="<?=$reqPerekatBangsaNilai?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m2">
                  <label for="reqSkorKompetensi">Nilai Skor Assesment</label>
                  <input placeholder="" <?=$read?> type="text" name="reqSkorKompetensi" id="reqSkorKompetensi" onkeypress='kreditvalidate(event, this)' value="<?=$reqSkorKompetensi?>" />
                </div>
                <div class="input-field col s12 m2">
                  <label for="reqSkorKompetensi">Nilai Indeks Kompetensi</label>
                  <input placeholder="" <?=$read?> type="text" name="reqNilaiIndeksKompetensi" id="reqNilaiIndeksKompetensi" value="<?=$reqNilaiIndeksKompetensi?>" />
                </div>
                <div class="input-field col s12 m3">
                  <select <?=$disabled?> name="reqKesimpulan" id="reqKesimpulan">
                    <?
                    foreach ($arrkesimpulan as $key => $value)
                    {
                      $optionid= $optiontext= $value["value"];
                      $optionselected= "";
                      if($reqKesimpulan == $optionid)
                        $optionselected= "selected";
                    ?>
                      <option value="<?=$optionid?>" <?=$optionselected?>><?=$optiontext?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqKesimpulan">Kesimpulan</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqPenyelenggara"> Penyelenggara Assesment</label>
                  <input placeholder="" type="text" class="easyui-validatebox" name="reqPenyelenggara" id="reqPenyelenggara" <?=$read?> value="<?=$reqPenyelenggara?>" title="Penyelenggara harus diisi" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqDeskripsi">Deskripsi</label>
                  <textarea style="height: 100px" placeholder="" class="easyui-validatebox" name="reqDeskripsi" id="reqDeskripsi"><?=$reqDeskripsi?></textarea>
                </div>
              </div>


              <div class="row">
                <div class="input-field col s12">
                  <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>

                  <script type="text/javascript">
                    $("#kembali").click(function() { 
                      document.location.href = "app/loadUrl/app/pegawai_add_kompetensi_monitoring?reqId=<?=$reqId?>";
                    });
                  </script>

                  <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                  <input type="hidden" name="reqId" value="<?=$reqId?>" />
                  <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                  <?
                  // A;R;D
                  if($tempAksesMenu == "A")
                  {
                  ?>
                  <button class="btn waves-effect waves-light green" style="font-size:9pt" type="submit" name="action">Simpan
                    <i class="mdi-content-save left hide-on-small-only"></i>
                  </button>
                  <?
                  }
                  ?>
                </div>
              </div>
              

              <div class="row">
                <div class="input-field col s12 m12">
                <?
                // area untuk upload file
                foreach ($arrsetriwayatfield as $key => $value)
                {
                  $riwayatfield= $value["riwayatfield"];
                  $riwayatfieldtipe= $value["riwayatfieldtipe"];
                  $riwayatfieldinfo= $value["riwayatfieldinfo"];
                  $riwayatfieldstyle= $value["riwayatfieldstyle"];
                  // echo $riwayatfieldstyle;exit;
                ?>
                  <button class="btn blue waves-effect waves-light" style="font-size:9pt;<?=$riwayatfieldstyle?>" type="button" id='buttonframepdf<?=$riwayatfield?>'>
                    <input type="hidden" id="labelvpdf<?=$riwayatfield?>" value="<?=$riwayatfieldinfo?>" />
                    <span id="labelframepdf<?=$riwayatfield?>"><?=$riwayatfieldinfo?></span>
                  </button>
                <?
                }
                ?>
                </div>
              </div>

              <div class="row"><div class="col s12 m12"><br/></div></div>

              <?
              // area untuk upload file
              foreach ($arrsetriwayatfield as $key => $value)
              {
                $riwayatfield= $value["riwayatfield"];
                $riwayatfieldtipe= $value["riwayatfieldtipe"];
                $vriwayatfieldinfo= $value["riwayatfieldinfo"];
                $riwayatfieldinfo= " - ".$vriwayatfieldinfo;
                $riwayatfieldrequired= $value["riwayatfieldrequired"];
                $riwayatfieldrequiredinfo= $value["riwayatfieldrequiredinfo"];
                $vriwayattable= $value["vriwayattable"];
                $vriwayatid= "";
                $vpegawairowfile= $reqDokumenKategoriFileId."-".$vriwayattable."-".$riwayatfield."-".$vriwayatid;
              ?>
              <div class="row">
                <div class="input-field col s12 m4">
                  <input type="hidden" id="reqDokumenRequired<?=$riwayatfield?>" name="reqDokumenRequired[]" value="<?=$riwayatfieldrequired?>" />
                  <input type="hidden" id="reqDokumenRequiredNama<?=$riwayatfield?>" name="reqDokumenRequiredNama[]" value="<?=$vriwayatfieldinfo?>" />
                  <input type="hidden" id="reqDokumenRequiredTable<?=$riwayatfield?>" name="reqDokumenRequiredTable[]" value="<?=$vriwayattable?>" />
                  <input type="hidden" id="reqDokumenRequiredTableRow<?=$riwayatfield?>" name="reqDokumenRequiredTableRow[]" value="<?=$vpegawairowfile?>" />
                  <input type="hidden" id="reqDokumenFileId<?=$riwayatfield?>" name="reqDokumenFileId[]" />
                  <input type="hidden" id="reqDokumenKategoriFileId<?=$riwayatfield?>" name="reqDokumenKategoriFileId[]" value="<?=$reqDokumenKategoriFileId?>" />
                  <input type="hidden" id="reqDokumenKategoriField<?=$riwayatfield?>" name="reqDokumenKategoriField[]" value="<?=$riwayatfield?>" />
                  <input type="hidden" id="reqDokumenPath<?=$riwayatfield?>" name="reqDokumenPath[]" value="" />
                  <input type="hidden" id="reqDokumenTipe<?=$riwayatfield?>" name="reqDokumenTipe[]" value="<?=$riwayatfieldtipe?>" />

                  <select id="reqDokumenPilih<?=$riwayatfield?>" name="reqDokumenPilih[]">
                    <?
                    foreach ($arrpilihfiledokumen as $key => $value)
                    {
                      $optionid= $value["id"];
                      $optiontext= $value["nama"];
                    ?>
                      <option value="<?=$optionid?>" <? if($reqDokumenPilih[$riwayatfield] == $optionid) echo "selected";?>><?=$optiontext?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqDokumenPilih<?=$riwayatfield?>">
                    File Dokumen<?=$riwayatfieldinfo?>
                    <span id="riwayatfieldrequiredinfo<?=$riwayatfield?>" style="color: red;"><?=$riwayatfieldrequiredinfo?></span>
                  </label>
                </div>

                <div class="input-field col s12 m4">
                  <select <?=$disabled?> name="reqDokumenFileKualitasId[]" id="reqDokumenFileKualitasId<?=$riwayatfield?>">
                    <option value=""></option>
                    <?
                    foreach ($arrkualitasfile as $key => $value)
                    {
                      $optionid= $value["ID"];
                      $optiontext= $value["TEXT"];
                      $optionselected= "";
                      if($reqDokumenFileKualitasId == $optionid)
                        $optionselected= "selected";

                      $arrkecualitipe= [];
                      $arrkecualitipe= $vfpeg->kondisikategori($riwayatfieldtipe);
                      if(!in_array($optionid, $arrkecualitipe))
                        continue;
                    ?>
                      <option value="<?=$optionid?>" <?=$optionselected?>><?=$optiontext?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqDokumenFileKualitasId<?=$riwayatfield?>">Kualitas Dokumen<?=$riwayatfieldinfo?></label>
                </div>

                <div id="labeldokumenfileupload<?=$riwayatfield?>" class="input-field col s12 m4" style="margin-top: -25px; margin-bottom: 10px;">
                  <div class="file_input_div">
                    <div class="file_input input-field col s12 m4">
                      <label class="labelupload">
                        <i class="mdi-file-file-upload" style="font-family: "Roboto",sans-serif,Material-Design-Icons !important; font-size: 14px !important;">Upload</i>
                        <input id="file_input_file" name="reqLinkFile[]" class="none" type="file" />
                      </label>
                    </div>
                    <div id="file_input_text_div" class=" input-field col s12 m8">
                      <input class="file_input_text" type="text" disabled readonly id="file_input_text" />
                      <label for="file_input_text"></label>
                    </div>
                  </div>
                </div>

                <div id="labeldokumendarifileupload<?=$riwayatfield?>" class="input-field col s12 m4">
                  <select id="reqDokumenIndexId<?=$riwayatfield?>" name="reqDokumenIndexId[]">
                    <option value="" selected></option>
                    <?
                    $arrlistpilihfilepegawai= $arrlistpilihfilefield[$riwayatfield];
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
                  <label for="reqDokumenIndexId<?=$riwayatfield?>">Nama e-File<?=$riwayatfieldinfo?></label>
                </div>

              </div>
              <?
              }
              // area untuk upload file
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
<!-- jQuery Library -->
<!-- <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script> -->

<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

<style type="text/css">
  .select-dropdown {
    max-height:250px !important; overflow:auto !important;
  }
</style>

<!-- tambahan lib cek tanggal -->
<script src="lib/moment/moment-with-locales.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('select').material_select();
    // untuk format date baru
    $('.formattanggalnew').datepicker({
      format: "dd-mm-yyyy"
    });

    $("#reqTanggalMulai,#reqTanggalSelesai").change(function(){
      settanggalmulaiselesai("data");
    });

    $('#reqTanggalMulai,#reqTanggalSelesai').keyup(function() {
      settanggalmulaiselesai("data");
    });

  });

  function settanggalmulaiselesai(infomode)
  {
    if(infomode == "data")
    {
      var vtanggalakhir= $('#reqTanggalSelesai').val();
      var vtanggalawal= $('#reqTanggalMulai').val();
      var checktanggalakhir= moment(vtanggalakhir , 'DD-MM-YYYY', true).isValid();
      var checktanggalawal= moment(vtanggalawal , 'DD-MM-YYYY', true).isValid();
      // console.log(checktanggalakhir+'_'+checktanggalawal);

      if(checktanggalawal == true)
      {
        vtahun= vtanggalawal.substring(6,10);
        $("#reqTahun, #reqTahunText").val(vtahun);

        ajaxurl= "penilaian_kompetensi_json/jabatanriwayat?reqPegawaiId=<?=$reqId?>&reqTanggal="+vtanggalawal;
        $.ajax({
          cache: false,
          url: ajaxurl,
          processData: false,
          contentType: false,
          type: 'GET',
          dataType: 'json',
          success: function (response) {
            // console.log(response);return false;
            // console.log(response);return false;
            if(Array.isArray(response) && response.length)
            {
              response= response[0];
              reqJabatanRiwayatId= response["JABATAN_RIWAYAT_ID"];
              reqJabatanRiwayatNama= response["JABATAN_RIWAYAT_NAMA"];
              reqJabatanRiwayatTmt= response["JABATAN_RIWAYAT_TMT"];
              reqJabatanRiwayatUnitKerja= response["JABATAN_RIWAYAT_UNIT_KERJA"];
            }
            else
            {
              reqJabatanRiwayatId= reqJabatanRiwayatNama= reqJabatanRiwayatTmt= reqJabatanRiwayatUnitKerja= "";
            }

            $("#reqJabatanRiwayatId").val(reqJabatanRiwayatId);
            $("#reqJabatanRiwayatNama").val(reqJabatanRiwayatNama);
            $("#reqJabatanRiwayatTmt").val(reqJabatanRiwayatTmt);
            $("#reqJabatanRiwayatUnitKerja").val(reqJabatanRiwayatUnitKerja);
          },
          error: function(xhr, status, error) {
          },
          complete: function () {
          }
        });
      }

      if(checktanggalakhir == true && checktanggalawal == true)
      {
        var tglakhir = moment(vtanggalakhir, 'DD-MM-YYYY');  // format tanggal
        var tglawal = moment(vtanggalawal, 'DD-MM-YYYY'); 

        if (tglakhir.isSameOrAfter(tglawal)) {} 
        else 
        {
           $('#reqTanggalSelesai').val(vtanggalawal);
        }
      }
    }
  }

  // untuk area untuk upload file
  vbase_url= "<?=base_url()?>";
  getarrlistpilihfilefield= JSON.parse('<?=JSON_encode($arrlistpilihfilefield)?>');
  // console.log(getarrlistpilihfilefield);

  // apabila butuh kualitas dokumen di ubah
  vselectmaterial= "1";
  // untuk area untuk upload file

</script>

<script type="text/javascript" src="lib/easyui/pelayanan-efile.js"></script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>