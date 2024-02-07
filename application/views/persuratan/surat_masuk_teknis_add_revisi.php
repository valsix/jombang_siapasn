<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/SuratMasukBkd');
$this->load->model('persuratan/SuratMasukPegawai');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqBreadCrum= $this->input->get("reqBreadCrum");
$reqJenis= $this->input->get("reqJenis");
$reqJenisNama= setjenisinfo($reqJenis);

$statement= " AND SMP.SURAT_MASUK_PEGAWAI_ID = ".$reqRowId;
$set= new SuratMasukBkd();
$set->selectByParamsCetakPengantarSatuOrang(array(), -1, -1, $statement);
$set->firstRow();
//echo $set->query;exit;
$reqJenisId= $set->getField('JENIS_ID');
$reqSuratMasukBkdId= $set->getField('SURAT_MASUK_BKD_ID');
$reqSuratMasukUptId= $set->getField('SURAT_MASUK_UPT_ID');
$reqPegawaiId= $set->getField('PEGAWAI_ID');

$reqJumlahUsulanPegawai= $set->getField('JUMLAH_USULAN_PEGAWAI');
$reqSuratKeluarNomor= $set->getField('NOMOR_SURAT_KELUAR');
$reqSuratKeluarTanggal= dateTimeToPageCheck($set->getField('TANGGAL_SURAT_KELUAR'));

$reqTtdSatuanKerja= $set->getField('TTD_SATUAN_KERJA');
$reqTtdNamaPejabat= $set->getField('TTD_NAMA_PEJABAT');
$reqTtdPangkat= $set->getField('TTD_PANGKAT');
$reqTtdNip= $set->getField('TTD_NIP');

//AMBIL_SATKER_NAMA(SMU.SATUAN_KERJA_ASAL_ID) , AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) 
$reqSuratSatuanKerjaAsalId= $set->getField('SATUAN_KERJA_ASAL_ID');
$reqSuratSatuanKerjaPengirimNama= $set->getField('SATUAN_KERJA_PENGIRIM');
$reqSuratSatuanKerjaPengirimKepala= $set->getField('SATUAN_KERJA_PENGIRIM_KEPALA');
$reqSuratNomor= $set->getField('NOMOR');
$reqSuratTanggal= dateToPageCheck($set->getField('TANGGAL'));
$reqSuratKepada= $set->getField('KEPADA');
$reqSurat= $set->getField('NAMA_LENGKAP');
//echo $reqSuratKepada;exit;
$reqPegawaiSatuanKerja= $set->getField('SATUAN_KERJA_DETIL');
$reqPegawaiNama= $set->getField('NAMA');
$reqPegawaiNamaLengkap= $set->getField('NAMA_LENGKAP');
$reqPegawaiNipBaru= $set->getField('NIP_BARU');
$reqPegawaiTempatLahir= $set->getField('TEMPAT_LAHIR');
$reqPegawaiTanggalLahir= dateToPageCheck($set->getField('TANGGAL_LAHIR'));
$reqPegawaiPangkatNama= $set->getField('PANGKAT_RIWAYAT_NAMA');
$reqPegawaiPangkatKode= $set->getField('PANGKAT_RIWAYAT_KODE');
$reqPegawaiJabatanNama= $set->getField('JABATAN_RIWAYAT_NAMA');
$reqPegawaiPendidikanNama= $set->getField('PENDIDIKAN_NAMA');
$reqPegawaiPendidikanJurusan= $set->getField('JURUSAN');
$reqPegawaiPendidikanUsulanNama= $set->getField('PENDIDIKAN_NAMA_US');
$reqPegawaiPendidikanUsulanFakultas= $set->getField('NAMA_FAKULTAS_US');
$reqPegawaiPendidikanUsulanJurusan= $set->getField('JURUSAN_US');
$reqPegawaiPendidikanUsulanNamaSekolah= $set->getField('NAMA_SEKOLAH_US');
$reqPegawaiPendidikanUsulanTempat= $set->getField('TEMPAT_US');
$reqPegawaiSatuanKerjaNama= $set->getField('SATUAN_KERJA_PEGAWAI_SURAT_KELUAR');

$arrPangkatRiwayat= [];
$index_data= 0;
$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
$table= "PANGKAT_RIWAYAT";
$field= "CONCAT(B.NAMA, ' - (', B.KODE,')')";
$join= "LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID";
$set_detil= new SuratMasukPegawai();
$set_detil->selectByParamsCombo(array(), -1, -1, $statement, $table, $field, $join);
//echo $set_detil->query;exit;
while($set_detil->nextRow())
{
	$arrPangkatRiwayat[$index_data]["NAMA"] = $set_detil->getField("NAMA");
	$index_data++;
}
$jumlah_pangkat= $index_data;

$arrPendidikanRiwayat= [];
$index_data= 0;
$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
$table= "PENDIDIKAN_RIWAYAT";
$field= "C.NAMA";
$join= "LEFT JOIN PENDIDIKAN C ON A.PENDIDIKAN_ID = C.PENDIDIKAN_ID";
$set_detil= new SuratMasukPegawai();
$set_detil->selectByParamsCombo(array(), -1, -1, $statement, $table, $field, $join);
//echo $set_detil->query;exit;
while($set_detil->nextRow())
{
	$arrPendidikanRiwayat[$index_data]["NAMA"] = $set_detil->getField("NAMA");
	$index_data++;
}
$jumlah_pendidikan= $index_data;

$arrJabatanRiwayat= [];
$index_data= 0;
$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId." AND COALESCE(NULLIF(A.NAMA,'') , NULL ) IS NOT NULL";
$table= "JABATAN_RIWAYAT";
$field= "A.NAMA";
$join= "";
$set_detil= new SuratMasukPegawai();
$set_detil->selectByParamsCombo(array(), -1, -1, $statement, $table, $field, $join);
//echo $set_detil->query;exit;
while($set_detil->nextRow())
{
	$arrJabatanRiwayat[$index_data]["NAMA"] = $set_detil->getField("NAMA");
	$index_data++;
}
$jumlah_jabatan= $index_data;

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
  <script type="text/javascript" src="lib/easyui/globalfunction.js"></script>
  
  <!-- AUTO KOMPLIT -->
  <link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
  <script src="lib/autokomplit/jquery-ui.js"></script>
  <script type="text/javascript"> 
  	/*function setCariInfo()
	{
		alert('a');
		document.location.href= "app/loadUrl/persuratan/surat_masuk_teknis_add_verifikasi?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqRowId=<?=$reqRowId?>";
	}*/
	
    $(function(){
        $('#ff').form({
            url:'surat/cetak_ijin_belajar_json/add_revisi',
            onSubmit:function(){
                if($(this).form('validate')){}
                    else
                    {
                        $.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
                        return false;
                    }
                },
                success:function(data){
                //alert(data);return false;
                data = data.split("-");
                rowid= data[0];
                infodata= data[1];
				//infodata= data;
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
						
						document.location.href = "app/loadUrl/persuratan/surat_masuk_teknis_add_verifikasi?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqRowId=<?=$reqRowId?>";
						//document.location.href= "app/loadUrl/persuratan/surat_masuk_teknis_add_revisi?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqRowId=<?=$reqRowId?>";
					}, 1000));
					$(".mbox > .right-align").css({"display": "none"});
				}

            }
        });
		
	});
	</script>
  <!-- SIMPLE TAB -->
  <script type="text/javascript" src="lib/simpletabs_v1.3/js/simpletabs_1.3.js"></script>
  <link href="lib/simpletabs_v1.3/css/simpletabs.css" type="text/css" rel="stylesheet">

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
	
	
	/* CSS for responsive iframe */
/* ========================= */

/* outer wrapper: set max-width & max-height; max-height greater than padding-bottom % will be ineffective and height will = padding-bottom % of max-width */
#Iframe-Master-CC-and-Rs {
  *max-width: 512px;
  max-height: 100%; 
  overflow: hidden;
}

/* inner wrapper: make responsive */
.responsive-wrapper {
  position: relative;
  height: 0;    /* gets height from padding-bottom */
  
  /* put following styles (necessary for overflow and scrolling handling on mobile devices) inline in .responsive-wrapper around iframe because not stable in CSS:
    -webkit-overflow-scrolling: touch; overflow: auto; */
  
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

/* padding-bottom = h/w as % -- sets aspect ratio */
/* YouTube video aspect ratio */
.responsive-wrapper-wxh-572x612 {
  padding-bottom: 107%;
}

/* general styles */
/* ============== */
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

/*.carousel {
    *height: 150vh !important;
	height: 115% !important;
    width: 100%;
	*overflow:auto;
    overflow:hidden;
}*/
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
	
	th, td
	{
		padding: 3px 8px !important;
	}
  </style>
</head>
<link rel="stylesheet" href="css/surat_pengantar.css" type="text/css">
<body>
 <div id="basic-form" class="section">
  <div class="row">
    <div class="col s12 m10 offset-m1 ">
      <ul class="collection card">
        <li class="collection-item ubah-color-warna">Revisi Cetak</li>
        <li class="collection-item">
          <div class="row">
          <form id="ff" method="post"  novalidate enctype="multipart/form-data">
          
          <div id="container">
            <div class="row content" style="text-align:center; margin-bottom:20px">
                <p style="font-size:16pt;margin: 0; text-align:center">
                    <strong>SURAT IZIN BELAJAR</strong>
                </p>
                <p style="margin: 0; text-align:center">Nomor : 
                <input type="text" style="width:20%" required class="easyui-validatebox" id="reqNomorSuratKeluar" name="reqNomorSuratKeluar" value="<?=$reqSuratKeluarNomor?>" />
                </p>
            </div>
            <div class="row content">
                <table>
                    <tr>
                        <td width="80px" style="vertical-align:top">Dasar</td>
                        <td width="10px" style="vertical-align:top">:</td>
                        <td>
                            <table>
                                <tr>
                                    <td style="vertical-align:top">1.</td>
                                    <td>Peraturan Bupati Jombang Nomor 9 Tahun 2011 tentang Persyaratan Mengikuti Pendidikan dan Pelatihan (DIKLAT), Tugas Belajar dan Izin Belajar bagi Pegawai Negeri Sipil Daerah Pemerintah Kabupaten Jombang;</td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:top">2.</td>
                                    <td>
                                        Surat Kepala Dinas Pendidikan Kabupaten Jombang tanggal 
                                        <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" name="reqTanggalSuratDinas" id="reqTanggalSuratDinas" maxlength="10" style="width:100px" onKeyDown="return format_date(event,'reqTanggalSuratDinas');" value="<?=$reqSuratTanggal?>" />
                                        nomor : 
                                        <input type="text" style="width:35%" required class="easyui-validatebox" id="reqNomorSuratDinas" name="reqNomorSuratDinas" value="<?=$reqSuratNomor?>" />
                                        perihal Permohonan Izin Belajar.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="row content" style="text-align:center; margin-top:10px; margin-bottom:10px">
                <p style="font-size:14pt;margin: 0; text-align:center">
                    <strong>MEMBERIKAN IZIN</strong>
                </p>
            </div>
            
            <div class="row content">
                <table>
                    <tr>
                        <td width="80px" style="vertical-align:top">Kepada</td>
                        <td width="30px" style="vertical-align:top">:</td>
                        <td>
                            <table>
                                <tr>
                                    <td width="200px">Nama</td>
                                    <td width="30px">:</td>
                                    <td>
                                    <strong>
                                    	<input type="text" required class="easyui-validatebox" id="reqPegawaiNama" name="reqPegawaiNama" value="<?=$reqPegawaiNama?>" />
                                    </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>NIP</td>
                                    <td>:</td>
                                    <td>
                                    	<input type="text" required class="easyui-validatebox" id="reqPegawaiNipBaru" name="reqPegawaiNipBaru" value="<?=$reqPegawaiNipBaru?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>Pangkat/Gol</td>
                                    <td>:</td>
                                    <td>
                                    	<select id="reqPegawaiPangkat" name="reqPegawaiPangkat">
                                    	<?
										for($i_select=0; $i_select < $jumlah_pangkat; $i_select++)
										{
                                        ?>
                                        <option value="<?=$arrPangkatRiwayat[$i_select]["NAMA"]?>" <? if($reqPegawaiPangkatNama." - (".$reqPegawaiPangkatKode.")" == $arrPangkatRiwayat[$i_select]["NAMA"]) echo "selected"?>><?=$arrPangkatRiwayat[$i_select]["NAMA"]?></option>
                                        <?
										}
                                        ?>
                                        </select>
                                    	<?php /*?><input type="text" required class="easyui-validatebox" id="reqPegawaiPangkat" name="reqPegawaiPangkat" value="<?=$reqPegawaiPangkatNama?> - (<?=$reqPegawaiPangkatKode?>)" /><?php */?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tempat, tanggal lahir</td>
                                    <td>:</td>
                                    <td>
                                    	<input type="text" required class="easyui-validatebox" id="reqPegawaiTtl" name="reqPegawaiTtl" value="<?=$reqPegawaiTempatLahir?>,  <?=$reqPegawaiTanggalLahir?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>Pendidikan</td>
                                    <td>:</td>
                                    <td>
                                    	<select id="reqPegawaiPendidikan" name="reqPegawaiPendidikan">
                                    	<?
										for($i_select=0; $i_select < $jumlah_pendidikan; $i_select++)
										{
                                        ?>
                                        <option value="<?=$arrPendidikanRiwayat[$i_select]["NAMA"]?>" <? if($reqPegawaiPendidikanNama == $arrPendidikanRiwayat[$i_select]["NAMA"]) echo "selected"?>><?=$arrPendidikanRiwayat[$i_select]["NAMA"]?></option>
                                        <?
										}
                                        ?>
                                        </select>
										<?php /*?><input type="text" required class="easyui-validatebox" id="reqPegawaiPendidikan" name="reqPegawaiPendidikan" value="<?=$reqPegawaiPendidikanNama?>" /><?php */?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td>:</td>
                                    <td>
                                    	<select id="reqPegawaiJabatan" name="reqPegawaiJabatan">
                                    	<?
										for($i_select=0; $i_select < $jumlah_jabatan; $i_select++)
										{
                                        ?>
                                        <option value="<?=$arrJabatanRiwayat[$i_select]["NAMA"]?>" <? if($reqPegawaiJabatanNama == $arrJabatanRiwayat[$i_select]["NAMA"]) echo "selected"?>><?=$arrJabatanRiwayat[$i_select]["NAMA"]?></option>
                                        <?
										}
                                        ?>
                                        </select>
                                        <?php /*?><input type="text" required class="easyui-validatebox" id="reqPegawaiJabatan" name="reqPegawaiJabatan" value="<?=$reqPegawaiJabatanNama?>" /><?php */?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Satuan Kerja</td>
                                    <td>:</td>
                                    <td>
                                    	<input type="text" required class="easyui-validatebox" id="reqPegawaiSatuanKerja" name="reqPegawaiSatuanKerja" value="<?=$reqPegawaiSatuanKerjaNama?>" />
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
    
            <div class="row content">
                <table>
                    <tr>
                        <td width="80px" style="vertical-align:top">Untuk</td>
                        <td width="30px" style="vertical-align:top">:</td>
                        <td>
                            <p>
                                Mengikuti pendidikan Program
                                <input type="text" style="width:25%" required class="easyui-validatebox" id="reqPendidikanNama" name="reqPendidikanNama" value="<?=$reqPegawaiPendidikanUsulanNama?>" />
                                program studi / jurusan Pendidikan
                                <input type="text" style="width:25%" required class="easyui-validatebox" id="reqPendidikanJurusan" name="reqPendidikanJurusan" value="<?=$reqPegawaiPendidikanUsulanJurusan?>" />
								pada
                                <input type="text" style="width:25%" required class="easyui-validatebox" id="reqPendidikanSekolah" name="reqPendidikanSekolah" value="<?=$reqPegawaiPendidikanUsulanNamaSekolah?>" />
                                , dengan ketentuan sebagai berikut : 
                            </p>
                            <table style="width:100%">
                                <tr>
                                    <td style="vertical-align:top">1.</td>
                                    <td>Kegiatan perkuliahan dilaksanakan di luar jam kerja;</td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:top">2.</td>
                                    <td>Kegiatan perkuliahan tidak mengganggu kelancaran tugas kedinasan sehari-hari;</td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:top">3.</td>
                                    <td>Seluruh biaya pendidikan ditanggung sepenuhnya oleh PNS yang bersangkutan;</td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:top">4.</td>
                                    <td>Tidak menuntut apabila dikemudian hari pendidikan yang ditempuh dinyatakan tidak memiliki dampak kepegawaian;</td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:top">5.</td>
                                    <td>Apabila dikemudian hari ternyata terdapat kekeliruan, maka surat izin belajar ini akan diubah dan diperbaiki sebagaimana mestinya.</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
    
            <div class="row col-5 float-r" style="">
                <div  style="margin-bottom:100px">
                    <table style="margin-bottom:10px">
                        <tr>
                            <td width="100px">Ditetapkan di</td>
                            <td width="10px">:</td>
                            <td>Jombang </td>
                        </tr>
                        <tr>
                            <td>Pada tanggal</td>
                            <td>:</td>
                            <td>
                            	<input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalSuratKeluar" id="reqTanggalSuratKeluar" maxlength="10" style="width:100px" onKeyDown="return format_date(event,'reqTanggalSuratKeluar');" value="<?=$reqSuratKeluarTanggal?>" />
                            </td>
                        </tr>
                    </table>
                    <span style="margin-left: 0px;">
                        a.n. BUPATI JOMBANG<br>
                    </span>
                    <span>
                    	<input type="text" required class="easyui-validatebox" id="reqNamaJabatanTtdSuratKeluar" name="reqNamaJabatanTtdSuratKeluar" value="<?=ucwordsPertama($reqTtdSatuanKerja)?>" />
                    </span>
                </div>
                <div>
                    <span>
                        <b>
                            <u>
                            <input type="text" required class="easyui-validatebox" id="reqNamaPejabatTtdSuratKeluar" name="reqNamaPejabatTtdSuratKeluar" value="<?=$reqTtdNamaPejabat?>" />
                            </u>
                        </b>
                    </span>
                    <br>
                    <span>
                    <input type="text" required class="easyui-validatebox" id="reqPangkatTtdSuratKeluar" name="reqPangkatTtdSuratKeluar" value="<?=$reqTtdPangkat?>" />
                    </span>
                    <br>
                    <span>Nip. 
                    <input type="text" style="width:65%" required class="easyui-validatebox" id="reqNipPejabatTtdSuratKeluar" name="reqNipPejabatTtdSuratKeluar" value="<?=$reqTtdNip?>" />
                    </span>
                </div>
            </div>
            <div class="row"></div>
        </div>
    
          <table class="bordered striped md-text table_list tabel-responsif">
          	<thead> 
            	<tr>
                	<th>
                    <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                    </button>
                    <button type="submit" style="display:none" id="reqSubmit"></button>
                    <button class="btn waves-effect waves-light green" style="font-size:9pt" type="button" id="reqsimpan">
                    Simpan
                    <i class="mdi-content-save left hide-on-small-only"></i>
                    </button>
                    </th>
                <tr>
                </tr>
            </thead>
          </table>
          </div>
            <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
            <input type="hidden" name="reqJenisId" value="<?=$reqJenisId?>" />
            <input type="hidden" name="reqSuratMasukBkdId" value="<?=$reqSuratMasukBkdId?>" />
            <input type="hidden" name="reqSuratMasukUptId" value="<?=$reqSuratMasukUptId?>" />
            <input type="hidden" name="reqPegawaiId" value="<?=$reqPegawaiId?>" />
            <input type="hidden" name="reqId" value="<?=$reqId?>" />
          </form>

        </div>    
       </li>
     </ul>
   </div>
 </div>
</div>

<link href="lib/materializetemplate/css/materializeslide.css" rel="stylesheet" />
<?php /*?><script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>
<?php */?>
<script src="lib/materializetemplate/js/materializeslide.min.js"></script>

<?php /*?><script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script><?php */?>

<script>
function kembalitab()
{
	iframeLoaded();
	$('ul.tabs').tabs('select_tab', 'swipe-1');
}

function setpdf(urlfile)
{
	//$("#mainframe").attr('src',"uploads/3/c95bd2d2a2ee5471550aa470e446ce39.pdf");
	$("#mainframe").attr('src',urlfile);
	//$('ul.tabs').tabs('swipeable', true);
	$("#tabs").tabs({
	  //swipeable : true,
	  swipeable: true
	});
	
	$('ul.tabs').tabs('select_tab', 'swipe-2');
	iframeLoaded();
}

$(window).bind("load", function() {
	//alert('a');
	//parent.iframeLoadeds();
	//alert('a');
   // code here
});

$(document).ready( function () {
	$('select').material_select();
	
	$("#kembali").click(function() { 
	  document.location.href = "app/loadUrl/persuratan/surat_masuk_teknis_add_verifikasi?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqRowId=<?=$reqRowId?>";
	});

});

function iframeLoaded() {
	var iFrameID= document.getElementById('mainFrame');
	if(iFrameID) {
			// here you can make the height, I delete it first, then I make it again
			iFrameID.height = "";
			iFrameID.height = iFrameID.contentWindow.document.body.scrollHeight + "px";
	}
}
</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>