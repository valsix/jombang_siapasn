<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('SuamiIstri');
$this->load->model('Pendidikan');
$this->load->model('Pensiun');
$this->load->model('PegawaiFile');

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
 
 $statement= " AND A.RIWAYAT_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId." AND A.RIWAYAT_TABLE = 'SUAMI_ISTRI' AND A.RIWAYAT_FIELD = 'foto'";
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
 $tempPathKaris= $pegawai_file->getField("PATH");

}

$pendidikan= new Pendidikan();
$pendidikan->selectByParams();
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

  <link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
  <script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script>
  <script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
  <script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
  <script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

  <script type="text/javascript"> 
    $(function(){
      $("#reqsimpan").click(function() { 
        if($("#ff").form('validate') == false){
          return false;
        }

        var reqStatusSi= reqRowId= reqTanggalKawin= "";
        reqStatusSi= $("#reqStatusSi").val();
        reqRowId= $("#reqRowId").val();
        reqTanggalKawin= $("#reqTanggalKawin").val();

        // if(reqStatusSi == "1")
        // {
          var s_url= "suami_istri_json/checkData?reqId=<?=$reqId?>&reqRowId="+reqRowId+"&reqStatusSi="+reqStatusSi+"&reqTanggalKawin="+reqTanggalKawin;
          $.ajax({'url': s_url,'success': function(dataajax){

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
          if($(this).form('validate')){}
            else
            {
              $.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
              return false;
            }
          },
          success:function(data){
            // alert(data);return false;
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
               document.location.href= "app/loadUrl/app/pegawai_add_suami_istri_data/?reqId=<?=$reqId?>&reqRowId="+rowid;
              }, 1000));
              $(".mbox > .right-align").css({"display": "none"});
            }

          }
        });

      setstatussi('1');
      $("#reqStatusSi").change(function() { 
        setstatussi('2');
      });

      setcetang();
      $("#reqStatusPns").click(function () {
        setcetang();
      });

      
    });

    function setstatussi(kondisi)
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
  }
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
     <div class="col s12 m10 offset-m1">

       <ul class="collection card">
         <li class="collection-item ubah-color-warna">EDIT SUAMI/ISTRI</li>
         <li class="collection-item">

          <div class="row">
            <form id="ff" method="post" enctype="multipart/form-data">

              <div class="row">
                <div class="input-field col s12 m6" style="text-align:center">
                  <label for="reqKartu">Foto Suami/Istri</label>
                  <?
                  if($tempPath == "")
                  {
                    $tempPath= "images/foto-profile.jpg";
                  }
                  ?>
                  <br/><br/>
                  <img src="<?=base_url().$tempPath?>" style="max-width:300px" id="reqKartu" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12">
                  <div class="row">
                    <div class="input-field col s12 m6">
                      <label for="reqNama">Nama Suami/Istri</label>
                      <input type="text" class="easyui-validatebox" required id="reqNama" name="reqNama" <?=$read?> value="<?=$reqNama?>" title="Nama harus diisi" />
                    </div>

                    <div class="input-field col s12 m2">
                      <label for="reqTanggalLahir">Tgl. Lahir</label>
                      <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalLahir" id="reqTanggalLahir"  value="<?=$reqTanggalLahir?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalLahir');"/>
                    </div>

                    <div class="input-field col s12 m4">
                      <label for="reqTempatLahir">Tempat Lahir</label>
                      <input type="text" class="easyui-validatebox" id="reqTempatLahir" required name="reqTempatLahir" <?=$read?> value="<?=$reqTempatLahir?>" />
                    </div>

                  </div>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <div class="row">
                    <div class="input-field col s12 m6">
                      <label for="reqSuratKawin">Surat nikah</label>
                      <input id="reqSuratKawin" type="text" name="reqSuratKawin" class="easyui-validatebox" required <?=$disabled?>  value="<?=$reqSuratKawin?>" />
                    </div>
                    <div class="input-field col s12 m4">
                      <label for="reqTanggalKawin">Tgl. nikah</label>
                      <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalKawin" id="reqTanggalKawin"  value="<?=$reqTanggalKawin?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalKawin');"/>
                    </div>
                    <?
                    if($tempPathAktaNikah == ""){}
                    else
                    {
                    ?>
                    <div class="col s12 m2">
                      <a href="<?=base_url().$tempPathAktaNikah?>" target="_new" class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
                    </div>
                    <?
                    }
                    ?>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <div class="row">
                    <div class="input-field col s12 m10">
                      <label for="reqNik">NIK</label>
                      <input type="text" class="easyui-validatebox"  name="reqNik" required <?=$read?> id="reqNik" value="<?=$reqNik?>" />
                    </div>

                    <div class="col s12 m2">
                    <?
                    if($tempPathNik == ""){}
                    else
                    {
                    ?>
                      <a href="<?=base_url().$tempPathNik?>" target="_new" class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
                    <?
                    }
                    ?>
                    </div>
                  </div>
                </div>

                <div class="input-field col s12 m6">
                  <div class="row">
                    <div class="input-field col s12 m10">
                      <label for="reqKartu">Karis/karsu</label>
                      <input type="text" class="easyui-validatebox" name="reqKartu" id="reqKartu" <?=$read?> value="<?=$reqKartu?>" />
                    </div>

                    <div class="col s12 m2">
                    <?
                    if($tempPathKaris == ""){}
                    else
                    {
                    ?>
                      <a href="<?=base_url().$tempPathKaris?>" target="_new" class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
                    <?
                    }
                    ?>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <div class="row">

                    <div class="input-field col s12 m4">
                      <input type="checkbox" id="reqStatusPns" name="reqStatusPns" value="1" <? if($reqStatusPns == 1) echo 'checked'?> />
                      <label for="reqStatusPns"></label>
                      PNS
                    </div>

                    <div class="input-field col s12 m8" id="reqLabelNipBaru">
                      <label for="reqNipPns">NIP Baru</label>
                      <input id="reqNipPns" type="text" name="reqNipPns" value="<?=$reqNipPns?>" />
                    </div>

                  </div>
                </div>

              </div>


              <div class="row">
                <div class="input-field col s12 m6">
                  <div class="row">
                    
                    <div class="input-field col s12">
                      <select name="reqPendidikanId" id="reqPendidikanId">
                        <? 
                        while($pendidikan->nextRow())
                        {
                          ?>
                          <option value="<?=$pendidikan->getField('pendidikan_id')?>" <? if($reqPendidikanId == $pendidikan->getField('PENDIDIKAN_ID')) echo 'selected';?>><?=$pendidikan->getField('NAMA')?></option>
                          <? 
                        }
                        ?>
                      </select>
                      <label for="reqPendidikanId">Pendidikan</label>
                    </div>

                  </div>
                </div>
                
                <div class="input-field col s12 m6">
                  <div class="row">
                    
                    <div class="input-field col s12">
                      <label for="reqPekerjaan">Pekerjaan</label>
                      <input type="text" <?=$read?> class="easyui-validatebox" id="reqPekerjaan" name="reqPekerjaan" value="<?=$reqPekerjaan?>" />
                    </div>

                  </div>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12">
                  <div class="row">
                    <div class="input-field col s12">
                      <select <?=$disabled?> name="reqStatusSi" id="reqStatusSi">
                        <option value="1" <? if($reqStatusSi == 1) echo 'selected';?>>Nikah</option>
                        <option value="2" <? if($reqStatusSi == 2) echo 'selected';?>>Cerai Hidup</option>
                        <option value="3" <? if($reqStatusSi == 3) echo 'selected';?>>Cerai Mati</option>
                      </select>
                      <label for="reqStatusSi">Status</label>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12">

                  <div class="row" id="reqLabelCerai">
                    <div class="input-field col s12 m7">
                      <label for="reqCeraiSurat">Surat Pengadilan</label>
                      <input type="text" class="easyui-validatebox" id="reqCeraiSurat" name="reqCeraiSurat" <?=$read?> value="<?=$reqCeraiSurat?>"  />
                    </div>
                    <div class="input-field col s12 m2">
                      <label for="reqCeraiTanggal">Tgl. Surat Pengadilan</label>
                      <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqCeraiTanggal" id="reqCeraiTanggal"  value="<?=$reqCeraiTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqCeraiTanggal');"/>
                    </div>
                    <div class="input-field col s12 m2">
                      <label for="reqCeraiTmt">TMT Cerai</label>
                      <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqCeraiTmt" id="reqCeraiTmt"  value="<?=$reqCeraiTmt?>" maxlength="10" onKeyDown="return format_date(event,'reqCeraiTmt');"/>
                    </div>
                    <div class="input-field col s12 m1">
                      <a class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
                    </div>
                  </div>

                  <div class="row" id="reqLabelKematian">
                    <div class="input-field col s12 m7">
                      <label for="reqKematianSurat">Akte Kematian</label>
                      <input type="text" class="easyui-validatebox" id="reqKematianSurat" name="reqKematianSurat" <?=$read?> value="<?=$reqKematianSurat?>"  />
                    </div>
                    <div class="input-field col s12 m2">
                      <label for="reqKematianTanggal">Tgl. Akte Kematian</label>
                      <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqKematianTanggal" id="reqKematianTanggal"  value="<?=$reqKematianTanggal?>" maxlength="10" onKeyDown="return format_date(event,'reqKematianTanggal');"/>
                    </div>
                    <div class="input-field col s12 m2">
                      <label for="reqKematianTmt">Tgl. Meninggal</label>
                      <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqKematianTmt" id="reqKematianTmt"  value="<?=$reqKematianTmt?>" maxlength="10" onKeyDown="return format_date(event,'reqKematianTmt');"/>
                    </div>
                    <div class="input-field col s12 m1">
                      <a class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
                    </div>
                  </div>

                </div>
              </div>


              <div class="row">
                <div class="input-field col s12">
                  <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>

                  <script type="text/javascript">
                    $("#kembali").click(function() { 
                      document.location.href = "app/loadUrl/app/pegawai_add_suami_istri_monitoring?reqId=<?=$reqId?>";
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
                </div>
              </div>

            </form>
          </div>
        </li>
      </ul>
    </div>
  </div>
</div>
<!-- jQuery Library -->
<!-- <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script> -->

<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('select').material_select();
  });

  $('.materialize-textarea').trigger('autoresize');

</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>