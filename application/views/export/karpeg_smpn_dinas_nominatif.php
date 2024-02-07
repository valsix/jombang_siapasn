<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model('persuratan/SuratMasukUpt');
$this->load->model('persuratan/SuratMasukBkd');
$this->load->model('persuratan/SuratMasukPegawai');

$reqId= $this->input->get("reqId");
$reqStatusBkdUptId= $this->input->get("reqStatusBkdUptId");
$reqPegawaiPilihKepalaId= $this->input->get("reqPegawaiPilihKepalaId");
$reqTipeId= $this->input->get("reqTipeId");
$reqJabatanManual= $this->input->get("reqJabatanManual");
$reqJabatanPilihan= $this->input->get("reqJabatanPilihan");
$reqGantiBaris= $this->input->get("reqGantiBaris");

// $reqJabatanPilihan= str_replace("Plt. ", "", $reqJabatanPilihan);

$arrInfo= [];
$index_data= 0;
if($reqStatusBkdUptId == "1")
{
  $statement= " AND SMP.SURAT_MASUK_UPT_ID = ".$reqId;
  $set= new SuratMasukUpt();
  $set->selectByParamsCetakPengantarSatuOrang(array(), -1, -1, $statement);
  $set->firstRow();

  $reqSuratSatuanKerjaAsalId= $set->getField('SATUAN_KERJA_ASAL_ID');

  $statement= " AND SMP.SURAT_MASUK_UPT_ID = ".$reqId;
  $set= new SuratMasukUpt();
  $set->selectByParamsCetakPengantarSatuOrang(array(), -1, -1, $statement);

  $statement= " AND A.SATUAN_KERJA_ID = ".$reqSuratSatuanKerjaAsalId;
  //echo $statement;exit;
}
elseif($reqStatusBkdUptId == "2")
{
  $statement= " AND SMP.SURAT_MASUK_BKD_ID = ".$reqId;
  $set= new SuratMasukBkd();
  $set->selectByParamsCetakPengantarSatuOrang(array(), -1, -1, $statement);
  $set->firstRow();

  $statement= " AND SMP.SURAT_MASUK_BKD_ID = ".$reqId;
  $set= new SuratMasukBkd();
  $set->selectByParamsCetakPengantarSatuOrang(array(), -1, -1, $statement);
  
  $statement= " AND A.PEGAWAI_ID = ".$reqPegawaiPilihKepalaId;
}
elseif($reqStatusBkdUptId == "3")
{
  $statement= " AND SMP.USULAN_SURAT_ID = ".$reqId;
  $set= new SuratMasukBkd();
  $set->selectByParamsCetakPengantarSatuOrangUsulan(array(), -1, -1, $statement);
  $set->firstRow();

  $statement_satuan_kerja= " AND STATUS_SATUAN_KERJA_BKPP = 1";
  $skerja= new SuratMasukPegawai();
  $tempsatuankerjaidbkdpp= $skerja->getSatuanKerjaId($statement_satuan_kerja);

  $skerja= new SuratMasukPegawai();
  $reqSatuanKerjaId= $skerja->getSatuanKerja($tempsatuankerjaidbkdpp);
  unset($skerja);
  //echo $reqSatuanKerjaId;exit;
  $statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";

  $sOrder= " ORDER BY SMP.USULAN_SURAT_URUT ASC";
  $statement= " AND SMP.USULAN_SURAT_ID = ".$reqId;
  $set= new SuratMasukBkd();
  $set->selectByParamsCetakPengantarSatuOrangUsulan(array(), -1, -1, $statement, $sOrder);
}

//echo $set->query;exit;
while($set->nextRow())
{
  $arrInfo[$index_data]["SATUAN_KERJA_ASAL_ID"] = $set->getField("SATUAN_KERJA_ASAL_ID");
  $arrInfo[$index_data]["NOMOR"] = $set->getField("NOMOR");
  $arrInfo[$index_data]["TANGGAL"] = $set->getField("TANGGAL");
  $arrInfo[$index_data]["SATUAN_KERJA_PENGIRIM"] = $set->getField("SATUAN_KERJA_PENGIRIM");
  $arrInfo[$index_data]["SATUAN_KERJA_PENGIRIM_KEPALA"] = $set->getField("SATUAN_KERJA_PENGIRIM_KEPALA");
  
  $arrInfo[$index_data]["NIP_BARU"] = $set->getField("NIP_BARU");
  $arrInfo[$index_data]["NAMA_LENGKAP"] = $set->getField("NAMA_LENGKAP");
  $arrInfo[$index_data]["PANGKAT_RIWAYAT_KODE"] = $set->getField("PANGKAT_RIWAYAT_KODE");
  $arrInfo[$index_data]["JABATAN_RIWAYAT_NAMA"] = $set->getField("JABATAN_RIWAYAT_NAMA");
  $arrInfo[$index_data]["SATUAN_KERJA_INDUK"] = $set->getField("SATUAN_KERJA_INDUK");
  $arrInfo[$index_data]["SATUAN_KERJA_DETIL1"] = $set->getField("SATUAN_KERJA_DETIL1");

  $arrInfo[$index_data]["NOMOR_SURAT_KELUAR"] = $set->getField("NOMOR_SURAT_KELUAR");
  $arrInfo[$index_data]["TANGGAL_SURAT_KELUAR"] = $set->getField("TANGGAL_SURAT_KELUAR");

  $arrInfo[$index_data]["NOMOR_SURAT_KELUAR_USULAN"] = $set->getField("NOMOR_SURAT_KELUAR_USULAN");
  
  $tempJenisKarpeg= $set->getField("JENIS_KARPEG");
  $tempKeterangan= "";
  if($tempJenisKarpeg == "1")
  $tempKeterangan= "BARU";
  elseif($tempJenisKarpeg == "2")
  $tempKeterangan= "REVISI<br/>".$set->getField("KETERANGAN");
  elseif($tempJenisKarpeg == "3")
  $tempKeterangan= "KEHILANGAN";
  
  $arrInfo[$index_data]["KETERANGAN"] = $tempKeterangan;
  
  $index_data++;
}
$jumlah_info= $index_data;

//print_r($arrInfo);exit;
if($jumlah_info > 0)
{
  $reqSuratSatuanKerjaPengirim= $arrInfo[0]["SATUAN_KERJA_PENGIRIM"];

  $styleBoldAn= "";
  if($reqJabatanManual == "")
  {
    $reqSuratSatuanKerjaPengirimKepala= $reqJabatanPilihan;
    // $reqSuratSatuanKerjaPengirimKepala= $arrInfo[0]["SATUAN_KERJA_PENGIRIM_KEPALA"];
  }
  else
  {
    $styleBoldAn= "font-weight: bold;";
    $reqSuratSatuanKerjaPengirimKepala= $reqJabatanManual;
  }

  $reqSuratNomor= $arrInfo[0]["NOMOR"];
  $reqSuratTanggal= dateToPageCheck($arrInfo[0]["TANGGAL"]);

  // $reqNomorSuratKeluar= $arrInfo[0]["NOMOR_SURAT_KELUAR"];
  $reqNomorSuratKeluar= $arrInfo[0]["NOMOR_SURAT_KELUAR_USULAN"];
  $reqTanggalSuratKeluar= getFormattedDateJson($arrInfo[0]["TANGGAL_SURAT_KELUAR"]);

  if(trim($reqTanggalSuratKeluar) == "")
  {
    $reqTanggalSuratKeluar= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".getNameMonth(date("m"))." ".date("Y");
  }

  $set= new SuratMasukPegawai();
  // $statement= " AND A.SATUAN_KERJA_ID = ".$arrInfo[0]["SATUAN_KERJA_ASAL_ID"];
  $statement= " AND A.PEGAWAI_ID = ".$reqPegawaiPilihKepalaId;
  $set->selectByParamsKepala(array(), -1, -1, $statement, "ORDER BY JAB_RIW.ESELON_ID ASC, PANG_RIW.PANGKAT_ID DESC, PANG_RIW.TMT_PANGKAT ASC");
  $set->firstRow();
  //echo $set->query;exit;
  $reqKepalaSatuanKerjaKepala= $set->getField('SATUAN_KERJA_KEPALA');
  $reqKepalaSatuanKerjaInduk= $set->getField('SATUAN_KERJA_INDUK');
  $reqKepalaPegawaiNama= $set->getField('NAMA_LENGKAP');
  $reqKepalaPegawaiNipBaru= $set->getField('NIP_BARU');
  $reqKepalaPegawaiPangkatNama= $set->getField('PANGKAT_RIWAYAT_NAMA');
  $reqKepalaPegawaiPangkatKode= $set->getField('PANGKAT_RIWAYAT_KODE');
  $reqKepalaPegawaiJabatanNama= $set->getField('JABATAN_RIWAYAT_NAMA');

  if($reqStatusBkdUptId == "3")
  {
    // $reqSuratSatuanKerjaPengirimKepala= $reqKepalaSatuanKerjaKepala;
    $reqSuratSatuanKerjaPengirimKepala= $reqJabatanPilihan;
    
    $reqSuratKepada= "Kepala Kantor Regional II BKN    Surabaya";
    $styleBoldAn= "";

    $reqSuratNomor= $reqNomorSuratKeluar;
    $reqSuratTanggal= $reqTanggalSuratKeluar;
  }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <base href="<?=base_url()?>" />
</head>

<body>

<table border="0" cellspacing="0" cellpadding="0" width="1171">
  <tr>
      <td width="856">&nbsp;</td>
      <td colspan="3">Lampiran</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Surat Nomor</td><td>:</td>
        <td><?=$reqSuratNomor?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Tanggal</td><td>:</td>
        <td><?=$reqSuratTanggal?></td>
    </tr>
    <tr>
      <td colspan="4" style="text-align:center"><br/>DAFTAR NAMA PERMOHONAN KARTU PEGAWAI<br/><br/><br/></td>
    </tr>
</table>

<div class="area-rekap-gaji">
  <table border="0" cellspacing="0" cellpadding="0" width="1171" class="myTable">
    <thead>
    <tr>
      <td style="text-align:center" class="garisleft garistop garisbottom" width="37"><p align="center"><strong>No</strong></p></td>
      <td style="text-align:center" class="garisleft garistop garisbottom" width="156"><p align="center"><strong>NIP</strong></p></td>
      <td style="text-align:center" class="garisleft garistop garisbottom" width="222"><p align="center"><strong>NAMA</strong></p></td>
      <td style="text-align:center" class="garisleft garistop garisbottom" width="106"><p align="center"><strong>GOL. RUANG</strong></p></td>
      <td style="text-align:center" class="garisleft garistop garisbottom" width="255"><p align="center"><strong>JABATAN    / UNIT KERJA</strong></p></td>
      <td style="text-align:center" class="garisleft garisright garistop garisbottom" width="236"><p align="center"><strong>KETERANGAN</strong></p></td>
    </tr>
    </thead>
    <tbody>
    <?
    $nomor=1;
    // $reqGantiBaris=1;
    $temBatas= $jumlah_info;
    if($reqGantiBaris == "1")
      $temBatas= $jumlah_info - 1;
    for($index_data=0; $index_data<$temBatas; $index_data++)
    {
    ?>
      <tr>
        <td style="padding:6px; text-align:center;" class="garisleft garisbottom" width="37"><p><?=$nomor?></p></td>
        <td style="padding:6px;" class="garisleft garisbottom" width="156"><p><?=$arrInfo[$index_data]["NIP_BARU"]?></p></td>
        <td style="padding:6px;" class="garisleft garisbottom" width="222"><p><?=$arrInfo[$index_data]["NAMA_LENGKAP"]?></p></td>
        <td style="padding:6px; text-align:center" class="garisleft garisbottom" width="106"><p><?=$arrInfo[$index_data]["PANGKAT_RIWAYAT_KODE"]?></p></td>
        <td style="padding:6px;" class="garisleft garisbottom" width="255"><p><?=$arrInfo[$index_data]["JABATAN_RIWAYAT_NAMA"]?> / <?=$arrInfo[$index_data]["SATUAN_KERJA_DETIL1"]?></p></td>
        <td style="padding:6px;" class="garisleft garisright garisbottom" width="222"><p><?=$arrInfo[$index_data]["KETERANGAN"]?></p></td>
      </tr>
    <?
    $nomor++;
    }
    ?>
    </tbody>
  </table>

  <?
  if($reqGantiBaris == "1")
  {
  ?>
  <pagebreak></pagebreak>
  <table border="0" cellspacing="0" cellpadding="0" width="1171" class="myTable">
    <thead>
    <tr>
      <td style="text-align:center" class="garisleft garistop garisbottom" width="37"><p align="center"><strong>No</strong></p></td>
      <td style="text-align:center" class="garisleft garistop garisbottom" width="156"><p align="center"><strong>NIP</strong></p></td>
      <td style="text-align:center" class="garisleft garistop garisbottom" width="222"><p align="center"><strong>NAMA</strong></p></td>
      <td style="text-align:center" class="garisleft garistop garisbottom" width="106"><p align="center"><strong>GOL. RUANG</strong></p></td>
      <td style="text-align:center" class="garisleft garistop garisbottom" width="255"><p align="center"><strong>JABATAN    / UNIT KERJA</strong></p></td>
      <td style="text-align:center" class="garisleft garisright garistop garisbottom" width="236"><p align="center"><strong>KETERANGAN</strong></p></td>
    </tr>
    </thead>
    <tbody>
      <tr>
        <td style="padding:6px; text-align:center;" class="garisleft garisbottom" width="37"><p><?=$nomor?></p></td>
        <td style="padding:6px;" class="garisleft garisbottom" width="156"><p><?=$arrInfo[$index_data]["NIP_BARU"]?></p></td>
        <td style="padding:6px;" class="garisleft garisbottom" width="222"><p><?=$arrInfo[$index_data]["NAMA_LENGKAP"]?></p></td>
        <td style="padding:6px; text-align:center" class="garisleft garisbottom" width="106"><p><?=$arrInfo[$index_data]["PANGKAT_RIWAYAT_KODE"]?></p></td>
        <td style="padding:6px;" class="garisleft garisbottom" width="255"><p><?=$arrInfo[$index_data]["JABATAN_RIWAYAT_NAMA"]?> / <?=$arrInfo[$index_data]["SATUAN_KERJA_DETIL1"]?></p></td>
        <td style="padding:6px;" class="garisleft garisright garisbottom" width="222"><p><?=$arrInfo[$index_data]["KETERANGAN"]?></p></td>
      </tr>
    </tbody>
  </table>
  <?
  }
  ?>

  <div class="row col-6 float-r" style="float:right; margin-right:-250px; margin-top:0px;">
      <?
      if($reqStatusBkdUptId == "3")
      {
        // $reqSuratSatuanKerjaPengirimKepala= "Kepala Badan Kepegawaian Daerah, Pendidikan dan Pelatihan";
        $styleBoldAn= "";
      ?>
      <div style="margin-bottom:100px !important">
        <p style="margin-left:-25px; margin-bottom: 0px; <?=$styleBoldAn?>">
          <?
          if($reqJabatanManual == "")
          {
          ?>
          <?
          }
          else
          {
          ?>
          a.n <?=$reqJabatanManual?>
          <br/>
          <?
          }
          $reqSuratSatuanKerjaPengirimKepala= $reqJabatanPilihan;
          $panjangSuratSatuanKerjaPengirimKepala= strlen($reqSuratSatuanKerjaPengirimKepala);
          $tempbatassurat= 30;
          ?>
          <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
          <span style="text-transform: uppercase;"><?=substrfullword($reqSuratSatuanKerjaPengirimKepala,0,$tempbatassurat)?></span>
        </p>
        <?
        if($panjangSuratSatuanKerjaPengirimKepala > $tempbatassurat)
        {
          ?>
          <span style="text-transform: uppercase; <?=$styleBoldAn?>"><?=substr($reqSuratSatuanKerjaPengirimKepala,getpanjangword($reqSuratSatuanKerjaPengirimKepala,0, $tempbatassurat))?></span>
          <br/>
          <?
        }
        ?>
        <!-- <p style="margin-left:-20px; margin-top:0px; margin-bottom: 0px;"> -->
        <span style="">KABUPATEN JOMBANG</span>
        </div>
      <?
      }
      else
      {
      ?>
      <div style="margin-bottom:100px">
        <p style="margin-left:-25px; margin-bottom: 0px; <?=$styleBoldAn?>">
          <?
          $setpanjangttd= 29;
          if($reqTipeId == "2")
          {
            ?>
            <span >a.n</span>
            <?
          }
          elseif($reqTipeId == "3")
          {
            ?>
            <span >plt. </span>
            <?
          }
          else
          {
            ?>
            <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <?
          }

          $panjangSuratSatuanKerjaPengirimKepala= strlen($reqSuratSatuanKerjaPengirimKepala);
          ?>
          <span style="text-transform: uppercase;"><?=substrfullword($reqSuratSatuanKerjaPengirimKepala,0,$setpanjangttd)?></span>
        </p>
        <?
        if($panjangSuratSatuanKerjaPengirimKepala > $setpanjangttd)
        {
          ?>
          <span style="text-transform: uppercase; <?=$styleBoldAn?>"><?=substr($reqSuratSatuanKerjaPengirimKepala,getpanjangword($reqSuratSatuanKerjaPengirimKepala,0, $setpanjangttd))?></span>
          <br/>
          <?
        }
        ?>

        <?
        if($reqTipeId == "2")
        {
          ?>
          <span style=""><?=$reqJabatanPilihan?></span>
          <br/>
          <?
        }
        ?>
        <span style="">KABUPATEN JOMBANG</span>
      </div>
      <?
      }
      ?>
      <!-- <div  style="margin-bottom:100px">
          <span><?=$reqSuratSatuanKerjaPengirimKepala?></span><br>
          <span>KABUPATEN JOMBANG</span>
      </div> -->
      <div>
          <span><b><u><?=$reqKepalaPegawaiNama?></u></b></span><br>
          <span><?=$reqKepalaPegawaiPangkatNama?></span><br>
          <span>NIP. <?=$reqKepalaPegawaiNipBaru?></span>
      </div>
  </div>
</div>
</body>
</html>