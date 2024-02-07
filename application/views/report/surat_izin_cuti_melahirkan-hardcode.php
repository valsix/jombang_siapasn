<?php
/* INCLUDE FILE */
include_once("functions/date.func.php");
include_once("functions/default.func.php");
include_once("functions/string.func.php");
// include_once("libraries/vendor/autoload.php");
// echo "dada";exit;
// // $this->load->model("SatuanKerja");
// // $this->load->library('suratmasukinfo');
// // $this->load->model("Disposisi");
// // $suratmasukinfo = new suratmasukinfo();

// $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
// $xx=explode("/",$actual_link);
// $link="http://192.168.88.100/".$xx[3];

// $reqId = httpFilterGet("reqId");
// $reqJenisSurat = httpFilterGet("reqJenisSurat");
// $suratmasukinfo->getInfoAsc($reqId, $reqJenisSurat);
// // print_r($suratmasukinfo->TANGGAL);
// $telp=$suratmasukinfo->TELEPON_UNIT;
// $fax=$suratmasukinfo->FAX_UNIT;
// $alamat=$suratmasukinfo->ALAMAT_UNIT;
// $an_status = $suratmasukinfo->AN_STATUS;
// $an_nama = $suratmasukinfo->AN_NAMA;
// $kepada = $suratmasukinfo->KEPADA;
// $kepadainfonew = $suratmasukinfo->KEPADA_INFO_NEW;

// $infokepada= [];
// $arrkepadainfonew= explode(",", $kepadainfonew);
// $jumlahkepada= count($arrkepadainfonew);
// // echo $jumlahkepada;
// // print_r($suratmasukinfo);exit;
// foreach ($arrkepadainfonew as $vkepada) 
// {
//   array_push($infokepada, $vkepada);
// }
// // print_r($infokepada);
// // exit;

// // $statement="";
// // $kepada = new SuratMasuk();
// // $kepada->selectByParamsKepada(array("A.SURAT_MASUK_ID" => $reqId), -1, -1, $statement);


// $alamatunit=str_replace(array('<p>', '</p>'), array('<i>', '</i>'), $alamat);
// $this->load->model("SuratMasuk");
// $suratmasuk = new SuratMasuk();
// $suratmasuk->selectByParamsPltJabatan(array("A.SURAT_MASUK_ID" => $reqId));
// $suratmasuk->firstRow();
// $reqJabatan                    = $suratmasuk->getField("JABATAN");
// $reqAtasanJabatan                    = $suratmasuk->getField("USER_ATASAN_JABATAN");
// $reqAnTambahan                    = $suratmasuk->getField("AN_TAMBAHAN");

// $disposisi   = new Disposisi();
// $reqKepada = $disposisi->getJson(array("SURAT_MASUK_ID" => $reqId, "STATUS_DISPOSISI" => "TUJUAN"));
// $arrKepada = json_decode($reqKepada);
// foreach ($arrKepada as $key => $value) {
//  $arrayNamaPegawai= $value->NAMA_PEGAWAI;
//  $arraySatkerPegawai= $value->SATUAN_KERJA;
//  $arrayCabangPegawai= $value->CABANG;
// }

?>

<link href="<?= base_url() ?>css/gaya-surat.css" rel="stylesheet" type="text/css">
<style>
  body{
      background-image:url('<?= base_url() ?>images/bg_cetak.jpg')  ;
      background-image-resize:6;
      background-size: cover;
  }
</style>
<body>


<div  class="kop-surat" >
  <center><u style="text-transform:uppercase;font-size: 16px;">SURAT IZIN CUTI MELAHIRKAN</u></center>
  <div class="nomor-naskah" style=" text-align: center;font-size: 14px;">Nomor :  </div>
</div>

<div class="isi-naskah">
  <p style="font-size: 14px;">Diberikan Cuti Melahirkan kepada Pegawai Negeri Sipil :<br></p>

  <p>
    <table width="100%" style="font-size: 14px;">
      <tr>
        <td width="15%">Nama</td>
        <td width="1%">:</td>
        <td width="59%"> </td>
      </tr>
      <tr>
        <td width="15%">Nomor Induk Pegawai</td>
        <td width="1%">:</td>
        <td width="59%"> </td>
      </tr>
      <tr>
        <td width="15%">Pangkat/Gol. Ruang</td>
        <td width="1%">:</td>
        <td width="59%"> </td>
      </tr>
      <tr>
        <td width="15%">Jabatan</td>
        <td width="1%">:</td>
        <td width="59%"> </td>
      </tr>
      <tr>
        <td width="15%">Unit Kerja</td>
        <td width="1%">:</td>
        <td width="59%"> </td>
      </tr>
    </table>
  </p>

  <p style="font-size: 14px;">
    terhitung mulai tanggal TANGGAL_MULAI sampai dengan tanggal TANGGAL_AKHIR dengan ketentuan sebagai berikut :
  </p>

  <p>
    <table width="100%" style="font-size: 14px;">
      <tr>
        <td width="1%">a.</td>
        <td>Sebelum menjalankan Cuti Melahirkan wajib menyerahkan pekerjaannya kepada atasan langsungnya atau pejabat lain yang ditunjuk.</td>
      </tr>
      <tr>
        <td width="1%">b.</td>
        <td>Setelah menjalankan Cuti Melahirkan wajib melaporkan diri kepada atasan langsungnya dan bekerja kembali sebagaimana biasa.</td>
      </tr>
    </table>
  </p>

  <p style="font-size: 14px;">
    Demikian Surat Izin Cuti Melahirkan ini diberikan untuk dapat dipergunakan sebagaimana mestinya.
  </p>

</div> 

<!-- End Isi Naskah -->

<!-- Start Tanda Tangan -->
<div class="tanda-tangan-kanan">
  <table width="100%" style="font-size: 14px;">
    <tr>
      <td width="20%">Ditetapkan di</td>
      <td width="1%">:</td>
      <td width="59%"> Jombang</td>
    </tr>
    <tr class="border-bottom">
      <td>Pada tanggal</td>
      <td>:</td>
      <td>
        
      </td>
    </tr>

    <tr>
      <td colspan="3"><br>JABATAN_PENGIRIM</td>
    </tr>
    <tr>
      <td colspan="3">
        <img src="<?=base_url()?>images/logo-dp3.png" height="100px">
        <br>
      </td>
    </tr>
    <tr>
      <td colspan="3">NAMA_PENGIRIM</td>
    </tr>
    <tr>
      <td colspan="3">NIP. NIP_PENGIRIM</td>
    </tr>
  </table>
  <br>&nbsp;
   
  <br>



</div>
<!-- End Isi Naskah -->


<!-- Start Tembusan -->
<?
if ($suratmasukinfo->TEMBUSAN == "") {
} else {
?>
  <!-- <div class="tembusan" style="font-size:14px"> -->
  <div class="tembusan" style="font-size: 9px;font-family: 'FrutigerCnd-Normal'">

    <b style="font-size:14px" ><u>Tembusan Yth. :</u></b>
    <br>
    <?
    $arrTembusan = explode(",", $suratmasukinfo->TEMBUSAN);
    ?>
    <ol type="1">
      <?
      for ($i = 0; $i < count($arrTembusan); $i++) {
      ?>
        <li><?= $arrTembusan[$i] ?></li>
      <?
      }
      ?>
    </ol>
  </div>
<?
}
?>

<?
if($jumlahkepada > 4)
{
?>
<pagebreak />
<div class="isi-naskah">
  <table width="100%">
    <tr>
      <td style="width: 150px;"><b>Lampiran No</b></td>
      <td width="5%">:</td>
      <td width="65%" align="justify"><?=$suratmasukinfo->NOMOR?></td>
    </tr>
    <tr>
      <td><b>Tanggal</b></td>
      <td>:</td>
       <!--  <td align="justify"><?=$suratmasukinfo->TANGGAL?></td> -->
      <td align="justify"><?=getFormattedDate2($suratmasukinfo->TANGGAL, false)?></td>
    </tr>
    <tr>
      <td><b>Tentang</b></td>
      <td>:</td>
      <td align="justify"><?=$suratmasukinfo->PERIHAL?></td>
    </tr>
    <tr>
      <td style="padding-top: 50px"><b>Kepada Yth. </b></td>
      <td style="padding-top: 50px">:</td>
      <td style="padding-top: 50px" align="justify">
        <ol>
          <?
          foreach ($infokepada as $itemKepada) 
          {
          ?>
          <li><?= $itemKepada ?></li>
          <?
          }
          ?>
        </ol>
      </td>
    </tr>
  </table>
</div>
<?
}
?>

</body>