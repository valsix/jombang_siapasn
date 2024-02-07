<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('SuamiIstri');
$this->load->model('Agama');
$this->load->model('Pendidikan');
$this->load->model('Pensiun');
$this->load->model('PegawaiFile');
$this->load->model('JenisIdDokumen');
$this->load->model('JenisKawin');
$this->load->model('JenisKelamin');
$this->load->model('KualitasFile');
$this->load->library('globalfilepegawai');

$pelayananid= $this->input->get("pelayananid");
$pelayananjenis= $this->input->get("pelayananjenis");
$pelayananrowid= $this->input->get("pelayananrowid");
$pelayanankembali= $this->input->get("pelayanankembali");

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "011001";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

$statement= " AND JENIS_ID = 7 AND A.PEGAWAI_ID = ".$reqId;
$set= new Pensiun();
$tempJumlah= $set->getCountByParamsSuratMasukPegawai(array(), $statement);
// $tempJumlah= 0;

$pendidikan= new Pendidikan();
$pendidikan->selectByParams();

if($reqRowId=="")
{
  $reqMode = 'insert';
  // $reqNama= "data a";
  // $reqSuratKawin= $reqNik= $reqTempatLahir= "tempat lahit";
  // $reqTanggalLahir= $reqTanggalKawin= "01-10-2018";
}
else
{
  $reqMode = 'update';
  $statement= " AND A.SUAMI_ISTRI_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
  $set= new SuamiIstri();
  $set->selectByParams(array(), -1, -1, $statement);
  // echo $set->query;exit();
  $set->firstRow();

  $reqPendidikanId= $set->getField("PENDIDIKAN_ID");
  $reqNama= $set->getField("NAMA");
  $reqTempatLahir= $set->getField("TEMPAT_LAHIR");
  $reqTanggalLahir= dateToPageCheck($set->getField("TANGGAL_LAHIR"));
  $reqTanggalKawin= dateToPageCheck($set->getField("TANGGAL_KAWIN"));
  $reqKartu= $set->getField("KARTU");
  $reqStatusPns= $set->getField("STATUS_PNS");
  $reqNipPns= $set->getField("NIP_PNS");
  $reqPekerjaan= $set->getField("PEKERJAAN");
  $reqStatusTunjangan= $set->getField("STATUS_TUNJANGAN");
  $reqStatusBayar= $set->getField("STATUS_BAYAR");
  $reqBulanBayar= $set->getField("BULAN_BAYAR");
  $reqStatusSi= $set->getField("STATUS_S_I");

  $reqSuratKawin= $set->getField("SURAT_NIKAH");
  $reqNik= $set->getField("NIK");
  $reqCeraiSurat= $set->getField("CERAI_SURAT");
  $reqCeraiTanggal= dateToPageCheck($set->getField("CERAI_TANGGAL"));
  $reqCeraiTmt= dateToPageCheck($set->getField("CERAI_TMT"));
  $reqKematianSurat= $set->getField("KEMATIAN_SURAT");
  $reqKematianTanggal= dateToPageCheck($set->getField("KEMATIAN_TANGGAL"));
  $reqKematianTmt= dateToPageCheck($set->getField("KEMATIAN_TMT"));

  $reqStatusHidup= $set->getField('STATUS_AKTIF');
  $reqStatusBekerja= $set->getField('STATUS_BEKERJA');
  $reqJenisKelamin= $set->getField('JENIS_KELAMIN');
  // $reqNoInduk= $set->getField('NOMOR_INDUK');
  $reqNoInduk= $set->getField("NIK");
  $reqTanggalMeninggal= dateToPageCheck($set->getField('TANGGAL_MENINGGAL'));
  $reqAktaKelahiran= $set->getField('AKTA_KELAHIRAN');
  $reqJenisIdDokumen= $set->getField('JENIS_ID_DOKUMEN');
  $reqAgamaId= $set->getField('AGAMA_ID');
  $reqEmail= $set->getField('EMAIL');
  $reqHp= $set->getField('HP');
  $reqTelepon= $set->getField('TELEPON');
  $reqAlamat= $set->getField('ALAMAT');
  $reqBpjsNo= $set->getField('BPJS_NO');
  $reqBpjsTanggal= dateToPageCheck($set->getField('BPJS_TANGGAL'));
  $reqNpwpNo= $set->getField('NPWP_NO');
  $reqNpwpTanggal= dateToPageCheck($set->getField('NPWP_TANGGAL'));
  $reqStatusLulus= $set->getField('STATUS_LULUS');
  $reqKematianNo= $set->getField('KEMATIAN_NO');
  $reqKematianTanggal= dateToPageCheck($set->getField('KEMATIAN_TANGGAL'));
  $reqJenisKawinId= $set->getField('JENIS_KAWIN_ID');
  $reqAktaNikahNo= $set->getField('AKTA_NIKAH_NO');
  $reqAktaNikahTanggal= dateToPageCheck($set->getField('AKTA_NIKAH_TANGGAL'));
  $reqNikahTanggal= dateToPageCheck($set->getField('NIKAH_TANGGAL'));
  $reqAktaCeraiNo= $set->getField('AKTA_CERAI_NO');
  $reqAktaCeraiTanggal= dateToPageCheck($set->getField('AKTA_CERAI_TANGGAL'));
  $reqCeraiTanggal= dateToPageCheck($set->getField('CERAI_TANGGAL'));
  $vidsapk= $set->getField("ID_SAPK");
 
 /*$statement= " AND A.RIWAYAT_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId." AND A.RIWAYAT_TABLE = 'SUAMI_ISTRI' AND A.RIWAYAT_FIELD = 'foto'";
 $pegawai_file= new PegawaiFile();
 $pegawai_file->selectByParamsLastRiwayatTable(array(), -1,-1,$statement);
 $pegawai_file->firstRow();
 $tempPath= $pegawai_file->getField("PATH");

 $statement= " AND A.RIWAYAT_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId." AND A.RIWAYAT_TABLE = 'SUAMI_ISTRI' AND A.RIWAYAT_FIELD = 'aktanikah'";
 $pegawai_file= new PegawaiFile();
 $pegawai_file->selectByParamsLastRiwayatTable(array(), -1,-1,$statement);
 $pegawai_file->firstRow();
 // echo $pegawai_file->query;exit();
 $tempPathAktaNikah= $pegawai_file->getField("PATH");

 $statement= " AND A.RIWAYAT_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId." AND A.RIWAYAT_TABLE = 'SUAMI_ISTRI' AND A.RIWAYAT_FIELD = 'ktp'";
 $pegawai_file= new PegawaiFile();
 $pegawai_file->selectByParamsLastRiwayatTable(array(), -1,-1,$statement);
 $pegawai_file->firstRow();
 $tempPathNik= $pegawai_file->getField("PATH");

 $statement= " AND A.RIWAYAT_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId." AND A.RIWAYAT_TABLE = 'SUAMI_ISTRI' AND A.RIWAYAT_FIELD = 'kariskarsu'";
 $pegawai_file= new PegawaiFile();
 $pegawai_file->selectByParamsLastRiwayatTable(array(), -1,-1,$statement);
 $pegawai_file->firstRow();
 $tempPathKaris= $pegawai_file->getField("PATH");*/

}

$statement= " AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')";
$set= new Agama();
$set->selectByParams(array(), -1, -1, $statement);
// echo $set->query;exit;
$arragama=[];
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["ID"]= $set->getField("AGAMA_ID");
  $arrdata["TEXT"]= $set->getField("NAMA");
  array_push($arragama, $arrdata);
}
// print_r($arragama);exit;

$set= new JenisIdDokumen();
$set->selectbyparams(array());
// echo $set->query;exit;
$arrjenisiddokumen=[];
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["ID"]= $set->getField("ID");
  $arrdata["TEXT"]= $set->getField("KODE");
  array_push($arrjenisiddokumen, $arrdata);
}

$set= new JenisKawin();
$set->selectbyparams(array());
// echo $set->query;exit;
$arrjeniskawin=[];
while($set->nextRow())
{
  if($set->getField("ID") == 4)
    continue;
  
  $arrdata= [];
  $arrdata["ID"]= $set->getField("ID");
  $arrdata["TEXT"]= $set->getField("NAMA");
  array_push($arrjeniskawin, $arrdata);
}
// print_r($arrjeniskawin);exit;

$set= new JenisKelamin();
$set->selectbyparams(array());
// echo $set->query;exit;
$arrjeniskelamin=[];
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["ID"]= $set->getField("KODE");
  $arrdata["TEXT"]= $set->getField("NAMA");
  array_push($arrjeniskelamin, $arrdata);
}

// untuk kondisi file
$vfpeg= new globalfilepegawai();
$arrpilihfiledokumen= $vfpeg->pilihfiledokumen();
// print_r($arrpilihfiledokumen);exit;

$riwayattable= "SUAMI_ISTRI";
$reqDokumenKategoriFileId= "15"; // ambil dari table KATEGORI_FILE, cek sesuai mode
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

    <!-- untuk format date baru -->
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
  

  <script type="text/javascript"> 
    $(function(){
      $("#reqsimpan").click(function() { 
        if($("#ff").form('validate') == false){
          return false;
        }

        var reqStatusHidup= reqStatusSi= reqRowId= reqTanggalKawin= "";
        reqStatusHidup= $("#reqStatusHidup").val();
        reqStatusSi= $("#reqStatusSi").val();
        reqRowId= $("#reqRowId").val();
        reqTanggalKawin= $("#reqTanggalKawin").val();

        if(reqStatusHidup == "")
        {
          mbox.alert('Data tidak bisa disimpan, karena Status Hidup belum diisi.', {open_speed: 0});
          return false;
        }

        if(reqStatusSi == "")
        {
          mbox.alert('Data tidak bisa disimpan, karena Status Pernikahan belum diisi.', {open_speed: 0});
          return false;
        }

        // if(reqStatusSi == "1")
        // {
          var s_url= "suami_istri_json/checkData?reqId=<?=$reqId?>&reqRowId="+reqRowId+"&reqStatusHidup="+reqStatusHidup+"&reqStatusSi="+reqStatusSi+"&reqTanggalKawin="+reqTanggalKawin;
          $.ajax({'url': s_url,'success': function(dataajax){
            // console.log(dataajax);return false;

            var tempStatusNikah= "";
            dataajax= String(dataajax);
            var element = dataajax.split('-'); 
            dataajax= element[0];
            tempStatusNikah= element[1];

            if(tempStatusNikah == "1")
            {
              mbox.alert('Data tidak bisa disimpan, karena ada data dengan status nikah lainnya', {open_speed: 0});
              return false;
            }

            if(dataajax == '1')
            {
              mbox.alert('Data tidak bisa disimpan, karena tanggal yang anda tulis lebih kecil dari pernikahan terakhir', {open_speed: 0});
              return false;
            }
            else
              $("#reqSubmit").click();
          }});
        // }
        // else
        // {
        //   $("#reqSubmit").click();
        // }
      });

      $('#ff').form({
        url:'suami_istri_json/add',
        onSubmit:function(){
          reqValidasiNoInduk= $("#reqValidasiNoInduk").val();
          if(reqValidasiNoInduk == ""){}
          else
          {
            mbox.alert(reqValidasiNoInduk, {open_speed: 0});
            return false;
          }

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

             <?
             if(!empty($pelayananid))
             {
             ?>
             vkembali= "app/loadUrl/app/pegawai_add_suami_istri_data?reqId=<?=$reqId?>&reqRowId="+rowid+"&pelayananid=<?=$pelayananid?>&pelayananjenis=<?=$pelayananjenis?>&pelayananrowid=<?=$pelayananrowid?>&pelayanankembali=<?=$pelayanankembali?>";
             <?
             }
             else
             {
             ?>
             vkembali= "app/loadUrl/app/pegawai_add_suami_istri_data?reqId=<?=$reqId?>&reqRowId="+rowid;
             <?
             }
             ?>
             document.location.href= vkembali;
             
            }, 1000));
            $(".mbox > .right-align").css({"display": "none"});
          }

        }
      });

      /*setstatussi('1');
      $("#reqStatusSi").change(function() { 
        setstatussi('2');
      });

      setcetang();
      $("#reqStatusPns").click(function () {
        setcetang();
      });*/

      
    });

    /*function setstatussi(kondisi)
    {
      var reqStatusSi= "";
      reqStatusSi= $("#reqStatusSi").val();

      $('#reqCeraiSurat,#reqCeraiTanggal,#reqCeraiTmt,#reqKematianSurat,#reqKematianTanggal,#reqKematianTmt').validatebox({required: false});
      $('#reqCeraiSurat,#reqCeraiTanggal,#reqCeraiTmt,#reqKematianSurat,#reqKematianTanggal,#reqKematianTmt').removeClass('validatebox-invalid');

      if(reqStatusSi == "1")
      {
       $("#reqLabelCerai,#reqLabelKematian").hide();

       if(kondisi == "1"){}
       else
       {
        $("#reqCeraiSurat,#reqCeraiTanggal,#reqCeraiTmt,#reqKematianSurat,#reqKematianTanggal,#reqKematianTmt").val("");
       }
     }
     else if(reqStatusSi == "2")
     {
       $("#reqLabelKematian").hide();
       $("#reqLabelCerai").show();

       if(kondisi == "1"){}
       else
       {
        $("#reqCeraiSurat,#reqCeraiTanggal,#reqCeraiTmt").val("");
        $("#reqCeraiSurat,#reqCeraiTanggal,#reqCeraiTmt").validatebox({required: true});
       }
       
     }
     else if(reqStatusSi == "3")
     {
       $("#reqLabelKematian").show();
       $("#reqLabelCerai").hide();
       if(kondisi == "1"){}
       else
       {
        $("#reqKematianSurat,#reqKematianTanggal,#reqKematianTmt").val("");
        $("#reqKematianSurat,#reqKematianTanggal,#reqKematianTmt").validatebox({required: true});
       }
       
     }
   }

   function setcetang()
   {
    if($("#reqStatusPns").prop('checked')) 
    {
      $("#reqLabelNipBaru").show();
    }
    else
    {
      $("#reqNipPns").val("");
      $("#reqLabelNipBaru").hide();
    }
  }*/
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
     <!-- <div class="col s12 m10 offset-m1"> -->
      <div id='main' class="col s12 m12" style="padding-left: 15px;">

       <ul class="collection card">
         <li class="collection-item ubah-color-warna">EDIT SUAMI/ISTRI</li>
         <li class="collection-item">

          <div class="row">
            <form id="ff" method="post" enctype="multipart/form-data">

              <div class="row">
                <div class="input-field col s12 m4" style="text-align:center">
                  <label for="reqInfoFoto">Foto Suami/Istri</label>
                  <?
                  if($tempPath == "")
                  {
                    $tempPath= "images/foto-profile.jpg";
                  }
                  ?>
                  <br/><br/>
                  <img id="infoimage" src="<?=base_url().$tempPath?>" style="width:inherit; height: 150px" id="reqInfoFoto" />
                </div>
                <div class="input-field col s12 m8">
                  <div class="row">
                    <div class="input-field col s12 m6">
                      <label for="reqNama">Nama Suami/Istri</label>
                      <input placeholder="" type="text" class="easyui-validatebox" required id="reqNama" name="reqNama" <?=$read?> value="<?=$reqNama?>" title="Nama harus diisi" />
                    </div>

                    <div class="input-field col s12 m3">
                      <label for="reqGelarDepan">Gelar Depan</label>
                      <input placeholder="" type="text" class="easyui-validatebox" name="reqGelarDepan" id="reqGelarDepan" value="<?=$reqGelarDepan?>" />
                    </div>

                    <div class="input-field col s12 m3">
                      <label for="reqGelarBelakang">Gelar Belakang</label>
                      <input type="text" placeholder="" class="easyui-validatebox" id="reqGelarBelakang" name="reqGelarBelakang" <?=$read?> value="<?=$reqGelarBelakang?>" />
                    </div>
                  </div>

                  <div class="row">
                    <div class="input-field col s12 m5">
                      <label for="reqTempatLahir">Tempat Lahir</label>
                      <input placeholder="" type="text" class="easyui-validatebox" id="reqTempatLahir" required name="reqTempatLahir" <?=$read?> value="<?=$reqTempatLahir?>" />
                    </div>
                    <div class="input-field col s12 m3">
                      <label class="active" for="reqTanggalLahir">Tgl. Lahir</label>
                      <table>
                        <tr> 
                          <td style="padding: 0px;">
                            <input placeholder="" required class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalLahir" id="reqTanggalLahir" value="<?=$reqTanggalLahir?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalLahir');" />
                          </td>
                          <td style="padding: 0px;">
                            <label class="input-group-btn" for="reqTanggalLahir" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
                              <span class="mdi-notification-event-note"></span>
                            </label>
                          </td>
                        </tr>
                      </table>
                    </div>
                    <div class="input-field col s12 m4">
                      <label for="reqAktaKelahiran">Akta Kelahiran</label>
                      <input type="text"  placeholder="" class="easyui-validatebox" id="reqAktaKelahiran" name="reqAktaKelahiran" <?=$read?> value="<?=$reqAktaKelahiran?>" />
                    </div>
                  </div>
                  <div class="row">
                    <div class="input-field col s12 m5">
                      <label for="reqSuratKawin">Surat nikah</label>
                      <input id="reqSuratKawin" placeholder=""  type="text" name="reqSuratKawin" class="easyui-validatebox" required <?=$disabled?>  value="<?=$reqSuratKawin?>" />
                    </div>
                    <div class="input-field col s12 m3">
                      <label class="active" for="reqTanggalKawin">Tgl. nikah</label>
                      <table>
                        <tr> 
                          <td style="padding: 0px;">
                            <input placeholder="" required class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalKawin" id="reqTanggalKawin" value="<?=$reqTanggalKawin?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalKawin');" />
                          </td>
                          <td style="padding: 0px;">
                            <label class="input-group-btn" for="reqTanggalKawin" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
                              <span class="mdi-notification-event-note"></span>
                            </label>
                          </td>
                        </tr>
                      </table>
                    </div>
                    <div class="input-field col s12 m3">
                      <label class="active" for="reqAktaNikahTanggal">Tanggal Akta Nikah</label>
                      <table>
                        <tr> 
                          <td style="padding: 0px;">
                            <input placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqAktaNikahTanggal" id="reqAktaNikahTanggal" value="<?=$reqAktaNikahTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqAktaNikahTanggal');" />
                          </td>
                          <td style="padding: 0px;">
                            <label class="input-group-btn" for="reqAktaNikahTanggal" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
                              <span class="mdi-notification-event-note"></span>
                            </label>
                          </td>
                        </tr>
                      </table>
                    </div>
                  </div>
                  <div class="row">
                    <div class="input-field col s12 m12">
                      <label for="reqKartu">Karis/karsu</label>
                      <input type="text" placeholder="" class="easyui-validatebox" name="reqKartu" id="reqKartu" <?=$read?> value="<?=$reqKartu?>" />
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="row">
                <div class="input-field col s12 m8">
                  <div class="row">

                    <div class="input-field col s12 m2 mtmin">
                      <select <?=$disabled?> name="reqJenisIdDokumen" id="reqJenisIdDokumen">
                        <option value="" selected></option>
                        <?
                        foreach ($arrjenisiddokumen as $key => $value)
                        {
                          $optionid= $value["ID"];
                          $optiontext= $value["TEXT"];
                          $optionselected= "";
                          if($reqJenisIdDokumen == $optionid)
                            $optionselected= "selected";
                        ?>
                          <option value="<?=$optionid?>" <?=$optionselected?>><?=$optiontext?></option>
                        <?
                        }
                        ?>
                      </select>
                      <label for="reqJenisIdDokumen">Jenis Dok</label>
                    </div>
                    <div class="input-field col s12 m4 mtmin">
                      <label for="reqNoInduk">Nomor Identitas</label>
                      <input type="hidden" id="reqValidasiNoInduk" />
                      <input placeholder="" name="reqNoInduk" class="easyui-validatebox" id="reqNoInduk" type="text" value="<?=$reqNoInduk?>" />
                    </div>
                    <div class="input-field col s12 m3 mtmin">
                      <select <?=$disabled?> name="reqJenisKelamin" id="reqJenisKelamin">
                        <option value="" selected></option>
                        <?
                        foreach ($arrjeniskelamin as $key => $value)
                        {
                          $optionid= $value["ID"];
                          $optiontext= $value["TEXT"];
                          $optionselected= "";
                          if($reqJenisKelamin == $optionid)
                            $optionselected= "selected";
                        ?>
                          <option value="<?=$optionid?>" <?=$optionselected?>><?=$optiontext?></option>
                        <?
                        }
                        ?>
                      </select>
                      <label for="reqJenisKelamin">Jenis Kel</label>
                    </div>
                    <div class="input-field col s12 m3 mtmin">
                      <select <?=$disabled?> name="reqAgamaId" id="reqAgamaId">
                        <option value="" selected></option>
                        <?
                        foreach ($arragama as $key => $value)
                        {
                          $optionid= $value["ID"];
                          $optiontext= $value["TEXT"];
                          $optionselected= "";
                          if($reqAgamaId == $optionid)
                            $optionselected= "selected";
                        ?>
                          <option value="<?=$optionid?>" <?=$optionselected?>><?=$optiontext?></option>
                        <?
                        }
                        ?>
                      </select>
                      <label for="reqAgamaId">Agama</label>
                    </div>

                  </div>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m3">
                  <label for="reqEmail">Email</label>
                  <input placeholder name="reqEmail" id="reqEmail" class="easyui-validatebox" data-options="validType:'email'" type="text" value="<?=$reqEmail?>" />
                </div>
                <div class="input-field col s12 m3">
                  <label for="reqHp">No HP / WA</label>
                  <input placeholder name="reqHp" id="reqHp" class="easyui-validatebox validasiangka" type="text" value="<?=$reqHp?>" />
                </div>
                <div class="input-field col s12 m3">
                  <label for="reqTelepon">No Telp Rumah</label>
                  <input placeholder name="reqTelepon" id="reqTelepon" class="easyui-validatebox validasiangka" type="text" value="<?=$reqTelepon?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqAlamat">Alamat</label>
                  <textarea class="materialize-textarea" name="reqAlamat"><?=$reqAlamat?></textarea>
                </div>
              </div>

               <div class="row">
                  <div class="input-field col s12 m3">
                    <label for="reqBpjsNo">No BPJS</label>
                    <input placeholder="" type="text" class="easyui-validatebox validasiangka" name="reqBpjsNo" id="reqBpjsNo" <?=$read?> value="<?=$reqBpjsNo?>" />
                  </div>

                  <div class="input-field col s12 m3">
                    <label class="active" for="reqBpjsTanggal">Tanggal BPJS</label>
                    <table>
                      <tr> 
                        <td style="padding: 0px;">
                          <input placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqBpjsTanggal" id="reqBpjsTanggal" value="<?=$reqBpjsTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqBpjsTanggal');" />
                        </td>
                        <td style="padding: 0px;">
                          <label class="input-group-btn" for="reqBpjsTanggal" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
                            <span class="mdi-notification-event-note"></span>
                          </label>
                        </td>
                      </tr>
                    </table>
                  </div>

                  <div class="input-field col s12 m3">
                    <label for="reqNpwpNo">No NPWP</label>
                    <input placeholder="" type="text" class="easyui-validatebox validasiangka" name="reqNpwpNo" id="reqNpwpNo" <?=$read?> value="<?=$reqNpwpNo?>" />
                  </div>

                  <div class="input-field col s12 m3">
                    <label class="active" for="reqNpwpTanggal">Tanggal NPWP</label>
                    <table>
                      <tr> 
                        <td style="padding: 0px;">
                          <input placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqNpwpTanggal" id="reqNpwpTanggal" value="<?=$reqNpwpTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqNpwpTanggal');" />
                        </td>
                        <td style="padding: 0px;">
                          <label class="input-group-btn" for="reqNpwpTanggal" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
                            <span class="mdi-notification-event-note"></span>
                          </label>
                        </td>
                      </tr>
                    </table>
                  </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m3">
                  <input type="checkbox" id="reqStatusPns" name="reqStatusPns" value="1" <? if($reqStatusPns == 1) echo 'checked'?> />
                  <label for="reqStatusPns"></label>
                  PNS
                </div>
                <div class="input-field col s12 m3" id="reqLabelNipBaru">
                  <label for="reqNipPns">NIP Baru</label>
                  <input placeholder="" class="validasiangka" id="reqNipPns" type="text" name="reqNipPns" value="<?=$reqNipPns?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m3">
                  <select name="reqPendidikanId" id="reqPendidikanId" <?=$disabled?>>
                    <?
                    while($pendidikan->nextRow())
                    {
                    ?>
                      <option value="<?=$pendidikan->getField('PENDIDIKAN_ID')?>" <? if($reqPendidikanId == $pendidikan->getField('PENDIDIKAN_ID')) echo 'selected';?>><?=$pendidikan->getField('NAMA')?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqPendidikanId">Pendidikan</label>
                </div>
                <div class="input-field col s12 m3" id="labelstatuslulus">
                  <input type="checkbox" id="reqStatusLulus" name="reqStatusLulus" value="1" <? if($reqStatusLulus == 1) echo 'checked'?> />
                  <label for="reqStatusLulus"></label>
                  Sudah Lulus
                </div>
                <div class="input-field col s12 m2">
                  <select <?=$disabled?> name="reqStatusBekerja" id="reqStatusBekerja">
                    <option value="1" <? if($reqStatusBekerja == 1) echo 'selected';?>>Sudah</option>
                    <option value="" <? if($reqStatusBekerja == "") echo 'selected';?>>Belum</option>
                  </select>
                  <label for="reqStatusBekerja">Status Bekerja</label>
                </div>
                <div class="input-field col s12 m4 labelpekerjaan">
                  <label for="reqPekerjaan">Pekerjaan</label>
                  <input placeholder="" type="text" <?=$read?> name="reqPekerjaan" id="reqPekerjaan" value="<?=$reqPekerjaan?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m2">
                  <select <?=$disabled?> name="reqStatusHidup" id="reqStatusHidup">
                    <option value="1" <? if($reqStatusHidup == 1) echo 'selected';?>>Hidup</option>
                    <option value="2" <? if($reqStatusHidup == 2) echo 'selected';?>>Wafat</option>
                  </select>
                  <label for="reqStatusHidup">Status Hidup</label>
                </div>

                <div class="input-field col s12 m3 reqLabelTanggalMeninggal">
                  <label for="reqKematianNo">Surat Keterangan Kematian</label>
                  <input placeholder="" type="text" class="easyui-validatebox" name="reqKematianNo" id="reqKematianNo" <?=$read?> value="<?=$reqKematianNo?>" />
                </div>
                <div class="input-field col s12 m3 reqLabelTanggalMeninggal">
                  <label class="active" for="reqKematianTanggal">Tanggal Surat Kematian</label>
                  <table>
                    <tr> 
                      <td style="padding: 0px;">
                        <input placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqKematianTanggal" id="reqKematianTanggal" value="<?=$reqKematianTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqKematianTanggal');" />
                      </td>
                      <td style="padding: 0px;">
                        <label class="input-group-btn" for="reqKematianTanggal" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
                          <span class="mdi-notification-event-note"></span>
                        </label>
                      </td>
                    </tr>
                  </table>
                </div>

                <div class="input-field col s12 m3 reqLabelTanggalMeninggal">
                  <label class="active" for="reqTanggalMeninggal">Tanggal Meninggal</label>
                  <table>
                    <tr> 
                      <td style="padding: 0px;">
                        <input placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalMeninggal" id="reqTanggalMeninggal" value="<?=$reqTanggalMeninggal?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalMeninggal');" />
                      </td>
                      <td style="padding: 0px;">
                        <label class="input-group-btn" for="reqTanggalMeninggal" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
                          <span class="mdi-notification-event-note"></span>
                        </label>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m2">
                  <select <?=$disabled?> name="reqStatusSi" id="reqStatusSi">
                    <option value=""></option>
                  </select>
                  <label for="reqStatusSi">Status Pernikahan</label>
                </div>

                <div class="input-field col s12 m3 labelnikah">
                  <label for="reqAktaNikahNo">No Akta Nikah</label>
                  <input placeholder="" type="text" class="easyui-validatebox" name="reqAktaNikahNo" id="reqAktaNikahNo" <?=$read?> value="<?=$reqAktaNikahNo?>" />
                </div>
                <div class="input-field col s12 m3 labelnikah">
                  
                </div>
                <div class="input-field col s12 m3 labelnikah">
                  <label class="active" for="reqNikahTanggal">Tanggal Nikah</label>
                  <table>
                    <tr> 
                      <td style="padding: 0px;">
                        <input placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqNikahTanggal" id="reqNikahTanggal" value="<?=$reqNikahTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqNikahTanggal');" />
                      </td>
                      <td style="padding: 0px;">
                        <label class="input-group-btn" for="reqNikahTanggal" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
                          <span class="mdi-notification-event-note"></span>
                        </label>
                      </td>
                    </tr>
                  </table>
                </div>

                <div class="input-field col s12 m3 labelcerai">
                  <label for="reqAktaCeraiNo">Surat Pengadilan / Cerai</label>
                  <input placeholder="" type="text" class="easyui-validatebox" name="reqAktaCeraiNo" id="reqAktaCeraiNo" <?=$read?> value="<?=$reqAktaCeraiNo?>" />
                </div>
                <div class="input-field col s12 m3 labelcerai">
                  <label class="active" for="reqAktaCeraiTanggal">Tanggal Akta Cerai</label>
                  <table>
                    <tr> 
                      <td style="padding: 0px;">
                        <input placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqAktaCeraiTanggal" id="reqAktaCeraiTanggal" value="<?=$reqAktaCeraiTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqAktaCeraiTanggal');" />
                      </td>
                      <td style="padding: 0px;">
                        <label class="input-group-btn" for="reqAktaCeraiTanggal" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
                          <span class="mdi-notification-event-note"></span>
                        </label>
                      </td>
                    </tr>
                  </table>
                </div>
                <div class="input-field col s12 m3 labelcerai">
                  <label class="active" for="reqCeraiTanggal">Tanggal Cerai</label>
                  <table>
                    <tr> 
                      <td style="padding: 0px;">
                        <input placeholder="" class="easyui-validatebox formattanggalnew" data-options="validType:'dateValidPicker'" type="text" name="reqCeraiTanggal" id="reqCeraiTanggal" value="<?=$reqCeraiTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqCeraiTanggal');" />
                      </td>
                      <td style="padding: 0px;">
                        <label class="input-group-btn" for="reqCeraiTanggal" style="display: inline-block;width: 90%;text-align: right;top: 0px;">
                          <span class="mdi-notification-event-note"></span>
                        </label>
                      </td>
                    </tr>
                  </table>
                </div>

              </div>

              <!-- <div class="row">
                <div class="input-field col s12 m2">
                  <select <?=$disabled?> name="reqStatusHidup" id="reqStatusHidup">
                    <option value="1" <? if($reqStatusHidup == 1) echo 'selected';?>>Hidup</option>
                    <option value="2" <? if($reqStatusHidup == 2) echo 'selected';?>>Wafat</option>
                  </select>
                  <label for="reqStatusHidup">Status</label>
                </div>
                <div class="input-field col s12 m2">
                  <select <?=$disabled?> name="reqStatusSi" id="reqStatusSi">
                    <option value="1" <? if($reqStatusSi == 1) echo 'selected';?>>Menikah</option>
                    <option value="2" <? if($reqStatusSi == 2) echo 'selected';?>>Cerai</option>
                    <option value="3" <? if($reqStatusSi == 3) echo 'selected';?>>Janda / Duda</option>
                  </select>
                  <label for="reqStatusSi">Status Perkawinan</label>
                </div>
              </div> -->

              <div class="row">
                <div class="input-field col s12">
                  <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>

                  <script type="text/javascript">
                    $("#kembali").click(function() { 
                      <?
                      if(!empty($pelayananid))
                      {
                      ?>
                      vkembali= "app/loadUrl/app/pegawai_add_suami_istri_monitoring?reqId=<?=$reqId?>&pelayananid=<?=$pelayananid?>&pelayananjenis=<?=$pelayananjenis?>&pelayananrowid=<?=$pelayananrowid?>&pelayanankembali=<?=$pelayanankembali?>";
                      <?
                      }
                      else
                      {
                      ?>
                      vkembali= "app/loadUrl/app/pegawai_add_suami_istri_monitoring?reqId=<?=$reqId?>";
                      <?
                      }
                      ?>
                      document.location.href = vkembali;
                    });
                  </script>

                  <input type="hidden" name="reqRowId" id="reqRowId" value="<?=$reqRowId?>" />
                  <input type="hidden" name="reqId" id="reqId" value="<?=$reqId?>" />
                  <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                  <button type="submit" style="display:none" id="reqSubmit"></button>
                  <?
                  if($tempJumlah == 0)
                  {
                  ?>

                  <?
                  // A;R;D
                  if($tempAksesMenu == "A")
                  {
                  ?>
                  <button class="btn waves-effect waves-light green" style="font-size:9pt" type="button" id="reqsimpan">
                    Simpan
                    <i class="mdi-content-save left hide-on-small-only"></i>
                  </button>
                  <?
                  }
                  ?>
                  
                  <?
                  }
                  ?>

                    <?
                  if(!empty($vidsapk))
                  {
                    $vdetilsapk= $vidsapk."___pegawai_add_suami_istri_sapk_data";
                    $vdetillabelsapk= "Data SAPK BKN";
                  ?>
                  <button class="btn blue waves-effect waves-light" style="font-size:9pt" type="button" id='buttondatasapk<?=$vdetilsapk?>'>
                    <input type="hidden" id="labelvsapk<?=$vdetilsapk?>" value="<?=$vdetillabelsapk?>" />
                       <input type="hidden" id="<?=$vidsapk?>" value="<?=$reqId?>" />
                    <span id="labelframesapk<?=$vdetilsapk?>">Cek <?=$vdetillabelsapk?></span>
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
                $vriwayatid= $vriwayatfield= "";
                $vpegawairowfile= $reqDokumenKategoriFileId."-".$vriwayattable."-".$vriwayatfield."-".$vriwayatid;
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
          </div>
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

<!-- tambahan lib cek tanggal -->
<script src="lib/moment/moment-with-locales.js"></script>
<script type="text/javascript">
  getarrjeniskawin= JSON.parse('<?=JSON_encode($arrjeniskawin)?>');

  $(document).ready(function() {
    $('select').material_select();

    // untuk format date baru
    $('.formattanggalnew').datepicker({
      format: "dd-mm-yyyy"
    });
  });

  tanggalsekarang= "<?=$tanggalsekarang?>";
  $('#reqTanggalLahir').keyup(function() {
    var vold= $('#reqTanggalLahir').val();
    var vnew= tanggalsekarang;

    getparamyearoldnew("reqUsia", vold, vnew);
  });

  $("#reqTanggalLahir").change(function(){
    var vold= $('#reqTanggalLahir').val();
    var vnew= tanggalsekarang;

    getparamyearoldnew("reqUsia", vold, vnew);
  });

  setjenisiddokumen("");
  $("#reqJenisIdDokumen").change(function(){
    setjenisiddokumen("data");
  });

  $("#reqNoInduk").keyup(function(){
    setnoinduk("data");
  });

  function setjenisiddokumen(infomode)
  {
    reqJenisIdDokumen= $("#reqJenisIdDokumen").val();
    setnoinduk(infomode);

    // infomode
  }

  function setnoinduk(infomode)
  {
    reqJenisIdDokumen= $("#reqJenisIdDokumen").val();
    reqNoInduk= $("#reqNoInduk").val();
    reqNoIndukLength= reqNoInduk.length;

    $("#reqNoInduk").validatebox({required: false});
    $("#reqNoInduk").removeClass('validatebox-invalid');

    $("#reqDokumenRequiredktp,#reqDokumenRequiredsim,#reqDokumenRequiredpasport").val("");
    $("#riwayatfieldrequiredinfoktp,#riwayatfieldrequiredinfosim,#riwayatfieldrequiredinfopasport").text("");
    reqValidasiNoInduk= "";
    // 1 KTP
    if(reqJenisIdDokumen == "1")
    {
      // 1111111111111111
      // console.log(reqNoIndukLength);
      if(!$.isNumeric(reqNoInduk) || reqNoIndukLength !== 16)
      {
        reqValidasiNoInduk= "KTP harus 16 digit angka, tanpa spasi dan tanda baca";
      }

      $("#riwayatfieldrequiredinfoktp").text(" *");
      $("#reqDokumenRequiredktp").val("1");
    }
    // 2 Pasport
    else if(reqJenisIdDokumen == "2")
    {
      if(reqNoIndukLength < 5 || reqNoIndukLength > 8)
      {
        reqValidasiNoInduk= "Passport minimal 5 char maksimal 8 char, bisa angka dan huruf";
      }

      $("#riwayatfieldrequiredinfopasport").text(" *");
      $("#reqDokumenRequiredpasport").val("1");
    }
    // 3 SIM
    else if(reqJenisIdDokumen == "3")
    {
      if(!$.isNumeric(reqNoInduk) || reqNoIndukLength !== 12)
      {
        reqValidasiNoInduk= "SIM harus 12 digit angkat, tanpa spasi dan tanda baca";
      }

      $("#riwayatfieldrequiredinfosim").text(" *");
      $("#reqDokumenRequiredsim").val("1");
    }
    else
    {
      reqValidasiNoInduk= "Isikan terlebih dahulu Jenis Dokumen Identitas";
      $("#reqNoInduk").validatebox({required: true});
    }

    // console.log(reqValidasiNoInduk);
    $("#reqValidasiNoInduk").val(reqValidasiNoInduk);
    // reqJenisIdDokumen;reqNoInduk

  }

  setpendidikan("");
  $("#reqPendidikanId").change(function () {
    setpendidikan("data");
  });

  function setpendidikan(infomode)
  {
    reqPendidikanId= $("#reqPendidikanId").val();
    $("#labelstatuslulus").show();

    if(infomode == "data")
    {
      $("#reqStatusLulus").prop('checked', false);
    }

    if(reqPendidikanId == 0)
    {
      $("#labelstatuslulus").hide();
      $("#reqStatusLulus").prop('checked', false);
    }
  }

  setcetang();
  $("#reqStatusPns").click(function () {
    setcetang();
  });

  function setcetang()
  {
    if($("#reqStatusPns").prop('checked')) 
    {
      $("#reqLabelNipBaru").show();
    }
    else
    {
      $("#reqNipPns").val("");
      $("#reqLabelNipBaru").hide();
    }
  }

  setstatusbekerja("");
  $("#reqStatusBekerja").change(function() { 
    setstatusbekerja("data");
  });

  function setstatusbekerja(infomode)
  {
    $(".labelpekerjaan").hide();

    if(infomode == "")
      vinfodata= "<?=$reqStatusBekerja?>";
    else
      vinfodata= $("#reqStatusBekerja").val();

    if(vinfodata == "1")
    {
      $(".labelpekerjaan").show();
    }
  }

  setstatusaktif("");
  $("#reqStatusHidup").change(function() { 
    setstatusaktif("data");
  });

  function setstatusaktif(infomode)
  {
    var reqStatusHidup= $("#reqStatusHidup").val();
    $(".reqLabelTanggalMeninggal").hide();

    // kalau suratkematian, maka
    $("#reqDokumenRequiredsuratkematian").val("");
    $("#riwayatfieldrequiredinfosuratkematian").text("");
    if(reqStatusHidup == "2")
    {
      $("#reqKematianTanggal, #reqKematianNo, #reqTanggalMeninggal").validatebox({required: true});
      $(".reqLabelTanggalMeninggal").show();

      $("#riwayatfieldrequiredinfosuratkematian").text(" *");
      $("#reqDokumenRequiredsuratkematian").val("1");
    }
    else
    {
      $("#reqKematianTanggal, #reqKematianNo, #reqTanggalMeninggal").validatebox({required: false});
      $("#reqKematianTanggal, #reqKematianNo, #reqTanggalMeninggal").removeClass('validatebox-invalid');
      $("#reqKematianTanggal, #reqKematianNo, #reqTanggalMeninggal").val("");
    }

    setjeniskawinid(infomode);
  }
  
  setjeniskawinid("");
  $("#reqStatusSi").change(function() { 
    setjeniskawinid("data");
  });

  function setjeniskawinid(infomode)
  {
    reqStatusHidup= $("#reqStatusHidup").val();

    if(infomode == "")
      vinfodata= "<?=$reqStatusSi?>";
    else
      vinfodata= $("#reqStatusSi").val();

    vlabelid= "reqStatusSi";
    $("#"+vlabelid+" option").remove();
    $("#"+vlabelid).material_select();
    var voption= "<option value=''></option>";

    if(Array.isArray(getarrjeniskawin) && getarrjeniskawin.length)
    {
      $.each(getarrjeniskawin, function( index, value ) {
        // console.log( index + ": " + value["id"] );
        infoid= value["ID"];
        infotext= value["TEXT"];
        setoption= "1";

        // 1:Hidup
        if(reqStatusHidup == "1")
        {
          // if(infoid == "3")
          // {
          //   setoption= "";
          // }
        }
        // 2:Wafat
        else if(reqStatusHidup == "2")
        {
          // setoption= "";
          if(infoid == "3")
          {
            // setoption= "1";
          }
        }
        else
        {
          // setoption= "";
          if(infoid == "4")
          {
            // setoption= "1";
          } 
        }

        if(setoption == "1")
        {
          vselected= "";
          if(infoid == vinfodata)
          {
            vselected= "selected";
          }

          voption+= "<option value='"+infoid+"' "+vselected+" >"+infotext+"</option>";
        }
      });
    }

    $("#"+vlabelid).html(voption);
    $("#"+vlabelid).material_select();

    if(infomode == ""){}
    else
    {
      $("#reqAktaNikahNo, #reqxxxAktaNikahTanggal, #reqNikahTanggal, #reqAktaCeraiNo, #reqAktaCeraiTanggal, #reqCeraiTanggal").val("");
    }

    $("#reqAktaNikahNo, #reqAktaNikahTanggal, #reqNikahTanggal, #reqAktaCeraiNo, #reqAktaCeraiTanggal, #reqCeraiTanggal").validatebox({required: false});
    $("#reqAktaNikahNo, #reqAktaNikahTanggal, #reqNikahTanggal, #reqAktaCeraiNo, #reqAktaCeraiTanggal, #reqCeraiTanggal").removeClass('validatebox-invalid');
    
    // kalau cerai, maka
    $("#reqDokumenRequiredaktacerai").val("");
    $("#riwayatfieldrequiredinfoaktacerai").text("");
    if(vinfodata == "2")
    {
      $("#riwayatfieldrequiredinfoaktacerai").text(" *");
      $("#reqDokumenRequiredaktacerai").val("1");
    }
    // kalau cerai, maka

    $(".labelnikah, .labelcerai").hide();
    if(reqStatusHidup == "2")
    {
      $(".infohidup").hide();
    }
    else
    {
      $(".infohidup").show();
      $(".labelnikah, .labelcerai").hide();
      if(vinfodata == "1")
      {
        // $("#reqAktaNikahNo, #reqAktaNikahTanggal, #reqNikahTanggal").validatebox({required: true});
        // $(".labelnikah").show();
      }
      // else if(vinfodata == "2" || vinfodata == "3")
      else if(vinfodata == "2")
      {
        // $("#reqAktaNikahNo, #reqAktaNikahTanggal, #reqNikahTanggal, #reqAktaCeraiNo, #reqAktaCeraiTanggal, #reqCeraiTanggal").validatebox({required: true});
        $("#reqAktaCeraiNo, #reqAktaCeraiTanggal, #reqCeraiTanggal").validatebox({required: true});
        // $(".labelnikah, .labelcerai").show();
        $(".labelcerai").show();
      }
    }

    setfotorequired();
  }

  function setfotorequired()
  {
    reqStatusHidup= $("#reqStatusHidup").val();
    reqStatusSi= $("#reqStatusSi").val();

    // kalau hidup, dan menikah, maka
    $("#reqDokumenRequiredfoto").val("");
    $("#riwayatfieldrequiredinfofoto").text("");
    // if(reqStatusHidup == "1")
    if(reqStatusHidup == "1" && reqStatusSi == "1")
    {
      $("#riwayatfieldrequiredinfofoto").text(" *");
      $("#reqDokumenRequiredfoto").val("1");
    }
  }
  $('.materialize-textarea').trigger('autoresize');

  // untuk area untuk upload file
  vbase_url= "<?=base_url()?>";
  getarrlistpilihfilefield= JSON.parse('<?=JSON_encode($arrlistpilihfilefield)?>');
  // console.log(getarrlistpilihfilefield);

  // apabila butuh kualitas dokumen di ubah
  vselectmaterial= "1";
  // untuk area untuk upload file

</script>

<script type="text/javascript" src="lib/easyui/pelayanan-efile.js"></script>
<script type="text/javascript" src="lib/easyui/pelayanan-bkndetil.js"></script>
<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>