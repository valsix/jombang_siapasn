<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/SuratMasukBkdDisposisi');
$this->load->model('persuratan/SuratMasukPegawai');
$this->load->model('persuratan/SuratMasukBkdDisposisiKeterangan');

$reqId= $this->input->get("reqId");
$reqVRowId= $this->input->get("reqRowId");
$reqbackurl= $this->input->get("reqbackurl");

if(empty($reqbackurl))
  $reqbackurl= "surat_keluar_teknis_add_surat_data";

$reqJenis= $this->input->get("reqJenis");
$reqMode= $this->input->get("reqMode");
$reqJenisNama= setjenisinfo($reqJenis);

$tanggalHariIni= date("d-m-Y");
$tahunIni= date("Y");
$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
$tempStatusKelompokPegawaiUsul= $this->STATUS_KELOMPOK_PEGAWAI_USUL;
$reqLoginLevel= $this->LOGIN_LEVEL;
$tempSatuanKerjaBkdId= $this->SATUAN_KERJA_BKD_ID;

if($reqId==""){}
else
{
   $reqMode = 'update';
   $statement= " AND A.SURAT_MASUK_BKD_ID = ".$reqId."";
   $set= new SuratMasukBkdDisposisi();
   $set->selectByParamsDataSuratLookup(array(), -1, -1, $reqSatuanKerjaId, $reqId, "", $statement);
   // $set->selectByParamsDataSurat(array(), -1, -1, $reqSatuanKerjaId, "", $statement);
   $set->firstRow();
   //echo $set->query;exit;
   
   $reqRowId= $set->getField("SURAT_MASUK_BKD_DISPOSISI_ID");
   $reqJenis= $reqJenisId= $set->getField("JENIS_ID");
   $reqSatkerAsalNama= $set->getField("SATUAN_KERJA_ASAL_NAMA");
   $reqSatuanKerjaDiteruskanKepadaId= $set->getField("SATUAN_KERJA_DITERUSKAN_ID");
   $reqSatuanKerjaDiteruskanKepada= $set->getField("SATUAN_KERJA_TUJUAN_DITERUSKAN_JABATAN_NAMA");
   $reqNomor= $set->getField("NOMOR");
   $reqValNomorAgenda= $set->getField("VAL_NO_AGENDA");
   $reqNomorAgenda= $set->getField("NO_AGENDA");
   $reqTanggal= dateToPageCheck($set->getField("TANGGAL"));
   $reqTanggalTerima= datetimeToPage($set->getField("TANGGAL_TERIMA"), "date");
   $reqTanggalDisposisi= $set->getField("TANGGAL_DISPOSISI");
   $reqPerihal= $set->getField("PERIHAL");
   $reqTerdisposisi= $set->getField("TERDISPOSISI");
   $reqSuratAwal= $set->getField("SURAT_AWAL");
   $reqBatasSatuanKerjaCariId= $set->getField("BATAS_SATUAN_KERJA_CARI_ID");
   $reqIsi= $set->getField("ISI");
   
   $reqTerbaca= $set->getField("TERBACA");
   $reqTerbacaDisposisi= $set->getField("TERBACA_DISPOSISI");
   $reqStatusKelompokPegawaiUsul= $set->getField("STATUS_KELOMPOK_PEGAWAI_USUL");
   
   $reqPosisiTeknis= $set->getField("POSISI_TEKNIS");

   $reqKategori= $set->getField("KATEGORI");
   $reqKategoriNama= $set->getField("KATEGORI_NAMA");
   
   if($reqJenisId == "")
   {
	  $tempPerihalInfo= $reqPerihal;
   }
   else
   {
	  $tempPerihalInfo= $reqPerihal;
   }
   
   // $statement= " AND A.SURAT_MASUK_BKD_DISPOSISI_ID = ".$reqRowId."";
   // $set_detil_catatan= new SuratMasukBkdDisposisiKeterangan();
   // $set_detil_catatan->selectByParams(array(), -1, -1, $statement);
   // //echo $set_detil_catatan->query;exit;
   // $set_detil_catatan->firstRow();
   // $reqRowDetilId= $set_detil_catatan->getField("SURAT_MASUK_BKD_DISPOSISI_KETERANGAN_ID");
   // $reqCatatan= $set_detil_catatan->getField("ISI");
   // $reqPegawaiId= $set_detil_catatan->getField("PEGAWAI_ID");
   // $reqPegawaiNama= $set_detil_catatan->getField("PEGAWAI_NAMA");
   //echo $reqPegawaiNama;exit;
}

$tempJudul= "Usulan Pelayanan";
if($reqJenisId == "")
$tempJudul= "Surat Masuk";

// if($reqJenisId == "" && $reqId == "")
// {
// 	$json="surat_masuk_add";
// }
// else
// {
// 	$json="nomor_agenda_terima";
// 	if($reqTerbaca == "1" && $reqValNomorAgenda != "")
// 	$json="nomor_agenda_disposisi";
// }

// if($reqPosisiTeknis == 1)
// $json="catatan";

if($reqTanggalDisposisi == "")
$reqTanggalDisposisi= dateToPageCheck($tanggalHariIni);

// $arrHistori= [];
// $index_data= 0;
// if($reqId == ""){}
// else
// {
// 	$statement= " AND A.SURAT_MASUK_BKD_ID = ".$reqId;
// 	$statementnode= " AND SATUAN_KERJA_ASAL_ID NOT IN (".$reqSatuanKerjaId.")";
// 	$set_detil= new SuratMasukBkdDisposisi();
// 	$set_detil->selectByParamsHistoriDisposisi($statement, $statementnode);
// 	// echo "s";
// 	// echo $set_detil->query;exit;
// 	while($set_detil->nextRow())
// 	{
// 		$arrHistori[$index_data]["SURAT_MASUK_BKD_DISPOSISI_ID"] = $set_detil->getField("SURAT_MASUK_BKD_DISPOSISI_ID");
// 		$arrHistori[$index_data]["TANGGAL_DISPOSISI"] = $set_detil->getField("TANGGAL_DISPOSISI");
// 		$arrHistori[$index_data]["ISI"] = $set_detil->getField("ISI");
// 		$arrHistori[$index_data]["JABATAN_ASAL"] = $set_detil->getField("JABATAN_ASAL");
// 		$arrHistori[$index_data]["JABATAN_TUJUAN"] = $set_detil->getField("JABATAN_TUJUAN");
// 		$index_data++;
// 	}
// 	unset($set_detil);
// }
// $jumlah_histori= $index_data;

// $statement_disposisi= " AND A.SURAT_MASUK_BKD_ID = ".$reqId." AND A.TERBACA = 1";
// $set_disposisi= new SuratMasukBkdDisposisi();
// $jumlah_terbaca= $set_disposisi->getCountByParams(array(), $statement_disposisi);

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

	<!-- <link rel="stylesheet" type="text/css" href="css/gaya.css"> -->

	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
	<script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
	
	
<!-- CORE CSS-->    
<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<!-- CSS style Horizontal Nav-->    
<link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
<!-- Custome CSS-->    
<link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

<link rel="stylesheet" type="text/css" href="lib/dropzone/dropzone.css">
<link rel="stylesheet" type="text/css" href="lib/dropzone/basic.css">
<script src="lib/dropzone/dropzone.js"></script>

<link href="lib/mbox/mbox.css" rel="stylesheet">
<script src="lib/mbox/mbox.js"></script>
<link href="lib/mbox/mbox-modif.css" rel="stylesheet">

<style>
	.dropdown-content
	{
		max-height: 200px !important;
	}

	.dropdown-content li
	{
		min-height: 15px !important;
		line-height: 0.1rem !important;
	}
	.dropdown-content li > span
	{
		font-size: 14px;
		line-height: 12px !important;
	}

</style>
</head>

<body>
	<div id="basic-form" class="section">
		<div class="row">
			<div class="col s12 m10 offset-m1">
				<ul class="collection card">
					<li class="collection-item ubah-color-warna"><?=$tempJudul?> <?=$reqJenisNama?></li>
					<li class="collection-item">
            <div class="row">
						<?
            if($reqKategori == ""){}
            else
            {
            ?>
            <div class="row" style="display: none;">
              <div class="input-field col s12">
               <label for="reqKategori">Jenis Pensiun</label>
               <input type="hidden" name="reqKategori" id="reqKategori" value="<?=$reqKategori?>" />
               <input type="text" disabled value="<?=$reqKategoriNama?>" />
             </div>
           </div>
           <?
           }
	         ?>
	                    
           <!-- <div class="row">
            <div class="input-field col s12 m4">
              <label for="reqSatuanKerjaAsalNama">Surat Dari</label>
              <input type="text" id="reqSatuanKerjaAsalNama" disabled value="<?=$reqSatkerAsalNama?>" />
            </div>

            <div class="input-field col s12 m8">
              <label for="reqPerihal">Perihal</label>
              <input type="hidden" name="reqPerihal" value="<?=$reqPerihal?>" />
              <input type="text" id="reqPerihal" <?=$read?> value="<?=$tempPerihalInfo?>" disabled />
            </div>
          </div> -->

          <div class="row">
           <div class="input-field col s12">
            <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
             <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
           </button>

           <script type="text/javascript">
             $("#kembali").click(function() { 
              document.location.href = "app/loadUrl/persuratan/<?=$reqbackurl?>?reqId=<?=$reqId?>&reqRowId=<?=$reqVRowId?>&reqJenis=<?=$reqJenis?>";
            });
          </script>
        </div>
      </div>
      
      <div class="row">
       <div id="pdfWrapper">
        <iframe src = "viewerjs/#../uploadsurat/keluar/<?=$reqId?>.pdf" width='100%' height='1024' allowfullscreen webkitallowfullscreen></iframe> 
      </div>

    </div>

                  
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!--materialize js-->
    <script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

    <script type="text/javascript">

    	$('.materialize-textarea').trigger('autoresize');

	</script>

    <link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
	<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>