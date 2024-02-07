<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model('persuratan/SuratMasukUpt');
$this->load->model('persuratan/SuratMasukPegawai');

$reqId= $this->input->get("reqId");
$reqPegawaiPilihKepalaId= $this->input->get("reqPegawaiPilihKepalaId");
$reqTipeId= $this->input->get("reqTipeId");
$reqJabatanManual= $this->input->get("reqJabatanManual");
$reqJabatanPilihan= $this->input->get("reqJabatanPilihan");
$reqGantiBaris= $this->input->get("reqGantiBaris");

$arrInfo= [];
$index_data= 0;
$statement= " AND SMP.SURAT_MASUK_UPT_ID = ".$reqId;
$set= new SuratMasukUpt();
$set->selectByParamsCetakPengantarSatuOrang(array(), -1, -1, $statement);
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
  $arrInfo[$index_data]["JABATAN_RIWAYAT_NAMA"] = $set->getField("JABATAN_RIWAYAT_NAMA");
  $arrInfo[$index_data]["SATUAN_KERJA_INDUK"] = $set->getField("SATUAN_KERJA_INDUK");
  $arrInfo[$index_data]["PENDIDIKAN_NAMA"] = $set->getField("PENDIDIKAN_NAMA");
  $arrInfo[$index_data]["JURUSAN"] = $set->getField("JURUSAN");
  $arrInfo[$index_data]["PENDIDIKAN_NAMA_US"] = $set->getField("PENDIDIKAN_NAMA_US");
  $arrInfo[$index_data]["JURUSAN_US"] = $set->getField("JURUSAN_US");
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
    // $reqSuratSatuanKerjaPengirimKepala= $set->getField('SATUAN_KERJA_PENGIRIM_KEPALA');
  }
  else
  {
    $styleBoldAn= "font-weight: bold;";
    $reqSuratSatuanKerjaPengirimKepala= $reqJabatanManual;
  }

	$reqSuratNomor= $arrInfo[0]["NOMOR"];
	$reqSuratTanggal= dateToPageCheck($arrInfo[0]["TANGGAL"]);

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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<base href="<?=base_url()?>" />
</head>

<body>
<p align="center"><strong>NOMINATIF PENGANTAR PERMOHONAN IZIN / TUGAS BELAJAR</strong><br />
  <?=$reqSuratSatuanKerjaPengirim?><br />
  Tanggal, <?=$reqSuratTanggal?>, Nomor : <?=$reqSuratNomor?></p>
<p align="center">&nbsp;</p>

<div class="area-rekap-gaji">
  <table border="0" cellspacing="0" cellpadding="0" width="1171" class="myTable">
    <thead>
    <tr>
      <td style="text-align:center" class="garisleft garistop garisbottom" width="37"><p align="center"><strong>No</strong></p></td>
      <td style="text-align:center" class="garisleft garistop garisbottom" width="156"><p align="center"><strong>NIP</strong></p></td>
      <td style="text-align:center" class="garisleft garistop garisbottom" width="222"><p align="center"><strong>NAMA</strong></p></td>
      <td style="text-align:center" class="garisleft garistop garisbottom" width="255"><p align="center"><strong>JABATAN    / UNIT KERJA</strong></p></td>
      <td style="text-align:center" class="garisleft garistop garisbottom" width="236"><p align="center"><strong>PENDIDIKAN TERAKHIR</strong></p></td>
      <td style="text-align:center" class="garisleft garisright garistop garisbottom" width="265"><p align="center"><strong>PENDIDIKAN YG DIMOHONKAN</strong></p></td>
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
      <td style="text-align:center" class="garisleft garisbottom" width="37"><p align="center"><?=$nomor?></p></td>
      <td style="padding:10px" class="garisleft garisbottom" width="156"><p align="center"><?=$arrInfo[$index_data]["NIP_BARU"]?></p></td>
      <td style="padding:10px" class="garisleft garisbottom" width="222"><p><?=$arrInfo[$index_data]["NAMA_LENGKAP"]?></p></td>
      <td style="padding:10px" class="garisleft garisbottom" width="255"><p><?=$arrInfo[$index_data]["JABATAN_RIWAYAT_NAMA"]?> / <?=$arrInfo[$index_data]["SATUAN_KERJA_INDUK"]?></p></td>
      <td style="padding:10px" class="garisleft garisbottom" width="236"><p><?=$arrInfo[$index_data]["PENDIDIKAN_NAMA"]?> / <?=$arrInfo[$index_data]["JURUSAN"]?></p></td>
      <td style="padding:10px" class="garisleft garisright garisbottom" width="265"><p><?=$arrInfo[$index_data]["PENDIDIKAN_NAMA_US"]?> / <?=$arrInfo[$index_data]["JURUSAN_US"]?></p></td>
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
      <td style="text-align:center" class="garisleft garistop garisbottom" width="255"><p align="center"><strong>JABATAN    / UNIT KERJA</strong></p></td>
      <td style="text-align:center" class="garisleft garistop garisbottom" width="236"><p align="center"><strong>PENDIDIKAN TERAKHIR</strong></p></td>
      <td style="text-align:center" class="garisleft garisright garistop garisbottom" width="265"><p align="center"><strong>PENDIDIKAN YG DIMOHONKAN</strong></p></td>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td style="text-align:center" class="garisleft garisbottom" width="37"><p align="center"><?=$nomor?></p></td>
      <td style="padding:10px" class="garisleft garisbottom" width="156"><p align="center"><?=$arrInfo[$index_data]["NIP_BARU"]?></p></td>
      <td style="padding:10px" class="garisleft garisbottom" width="222"><p><?=$arrInfo[$index_data]["NAMA_LENGKAP"]?></p></td>
      <td style="padding:10px" class="garisleft garisbottom" width="255"><p><?=$arrInfo[$index_data]["JABATAN_RIWAYAT_NAMA"]?> / <?=$arrInfo[$index_data]["SATUAN_KERJA_INDUK"]?></p></td>
      <td style="padding:10px" class="garisleft garisbottom" width="236"><p><?=$arrInfo[$index_data]["PENDIDIKAN_NAMA"]?> / <?=$arrInfo[$index_data]["JURUSAN"]?></p></td>
      <td style="padding:10px" class="garisleft garisright garisbottom" width="265"><p><?=$arrInfo[$index_data]["PENDIDIKAN_NAMA_US"]?> / <?=$arrInfo[$index_data]["JURUSAN_US"]?></p></td>
    </tr>
    </tbody>
  </table>
  <?
  }
  ?>

  <!-- margin-top:70px;  -->
  <div class="row col-6 float-r" style="float:right; margin-right:-250px; margin-top:0px;">
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
          <!-- <?
          if($reqTipeId == "2")
          {
          ?>
          <br/><span style="">Sekretaris</span>
          <?
          }
          ?> -->
      </div>
      <div>
          <span><b><u><?=$reqKepalaPegawaiNama?></u></b></span><br>
          <span><?=$reqKepalaPegawaiPangkatNama?></span><br>
          <span>NIP. <?=$reqKepalaPegawaiNipBaru?></span>
      </div>
  </div>
</div>
</body>
</html>