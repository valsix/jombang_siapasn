<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('Pensiun');
$this->load->model('StatusPegawaiKedudukan');

$reqId= $this->input->get("reqId");
// $reqStatusPegawaiId= $this->input->get("reqStatusPegawaiId");
// $reqStatusPegawaiKedudukanId= $this->input->get("reqStatusPegawaiKedudukanId");

$reqStatusPegawaiId="3";
$tempLoginLevel= $this->LOGIN_LEVEL;

if($tempLoginLevel == "30" || $tempLoginLevel == "99")
{
  $reqStatusPegawaiKedudukanId= "21,22,23,24,25,28";
}
else
{
  $reqStatusPegawaiKedudukanId= "23,24";
}

$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "0101";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

// $statement= " AND A.PEGAWAI_ID = ".$reqId." AND A.JENIS = 'meninggal'";
$statement= " AND A.PEGAWAI_ID = ".$reqId;
$set= new Pensiun();
$set->selectByParams(array(), -1,-1, $statement);
$set->firstRow();
$reqPensiunPegawaiId= $set->getField("PEGAWAI_ID");

// $statement= " AND A.PEGAWAI_ID = ".$reqId." AND A.STATUS_PEGAWAI_ID = ".$reqStatusPegawaiId." AND A.STATUS_PEGAWAI_KEDUDUKAN_ID IN (".$reqStatusPegawaiKedudukanId.")";
$statement= " AND A.PEGAWAI_ID = ".$reqId." AND A.STATUS_PEGAWAI_ID IN (3,5) AND A.STATUS_PEGAWAI_KEDUDUKAN_ID IN (".$reqStatusPegawaiKedudukanId.")";
$set= new Pensiun();
$set->selectByParamsStatus(array(), -1,-1, $statement);
// echo $set->query;exit;
$set->firstRow();
$reqRowId= $set->getField("PEGAWAI_STATUS_ID");
$tempStatusPegawaiKedudukanId= $set->getField("STATUS_PEGAWAI_KEDUDUKAN_ID");
$tempStatusPegawaiKedudukanNama= $set->getField("STATUS_PEGAWAI_KEDUDUKAN_NAMA");

$tempinfosimpan="";
// echo $reqStatusPegawaiKedudukanId." == ".$tempStatusPegawaiKedudukanId;exit;
if($reqRowId=="")
{
  $reqMode = 'insert';

  $statement= " AND A.STATUS_PEGAWAI_KEDUDUKAN_ID IN (".$reqStatusPegawaiKedudukanId.")";
  $statuspegawaikedudukan= new StatusPegawaiKedudukan();
  $statuspegawaikedudukan->selectByParams(array(), -1,-1, $statement);
  // echo $statuspegawaikedudukan->query;exit();
}
else
{
  $reqMode = 'update';
  // $statement= " AND A.PEGAWAI_ID = ".$reqId." AND A.JENIS = 'meninggal'";
  $statement= " AND A.JENIS NOT IN ('bup') AND A.PEGAWAI_ID = ".$reqId;
  $set= new Pensiun();
  $set->selectByParams(array(),-1,-1, $statement);
  // echo $set->query;exit;
  // echo $set->errorMsg;exit;
  $set->firstRow();

  $reqJenis= $set->getField("JENIS");
  $reqNomorSK= $set->getField("NOMOR_SK");
  $reqTanggalSkKematian= dateToPageCheck($set->getField("TANGGAL_SK_KEMATIAN"));
  $reqTanggalKematian= dateToPageCheck($set->getField("TANGGAL_KEMATIAN"));
  $reqTmt= dateToPageCheck($set->getField("TMT"));
  $reqKeterangan= $set->getField("KETERANGAN");
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

  <link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
  <script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script>
  <script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
  <script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
  <script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

  <script type="text/javascript"> 
    $(function(){
      $('#ff').form({
        url:'pensiun_json/add',
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
            mbox.alert(infodata, {open_speed: 500}, interval = window.setInterval(function() 
            {
             clearInterval(interval);
             mbox.close();

             document.location.href= "app/loadUrl/app/pegawai_add_pegawai_status_lapor/?reqId=<?=$reqId?>&reqStatusPegawaiId=<?=$reqStatusPegawaiId?>&reqStatusPegawaiKedudukanId=<?=$reqStatusPegawaiKedudukanId?>";
           }, 1000));
            $(".mbox > .right-align").css({"display": "none"});

          }
        });

      $("#reqStatusPegawaiKedudukanId").change(function() {
        setstatuspegawaikedudukan();
      });

      $("#reqTanggalKematian").keyup(function(){
        setInfoMasaBerlaku();
      });

      <?
      if($reqRowId=="")
      {
      ?>
      setstatuspegawaikedudukan();
      <?
      }
      ?>

    });

    function setstatuspegawaikedudukan()
    {
      var reqStatusPegawaiKedudukanId= $("#reqStatusPegawaiKedudukanId").val();
      console.log(reqStatusPegawaiKedudukanId);
      var reqStatusPegawaiId= "3";
      if(reqStatusPegawaiKedudukanId == "24" || reqStatusPegawaiKedudukanId == "25")
      {
          // Kematian
          $("span[for*='labelketeranganinfo']").html("Kematian");
          $("span[for*='labeltmtketeranganinfo']").html("Pensiun");
          $("#reqJenis").val("meninggal");
      }
      else if(reqStatusPegawaiKedudukanId == "21" || reqStatusPegawaiKedudukanId == "22" || reqStatusPegawaiKedudukanId == "23")
      {
          // Pensiun BUP / /udzur Dini
          $("span[for*='labelketeranganinfo']").html("Pensiun");
          $("span[for*='labeltmtketeranganinfo']").html("Pensiun");

          if(reqStatusPegawaiKedudukanId == "21")
            $("#reqJenis").val("pensiunbup");
          else if(reqStatusPegawaiKedudukanId == "22")
            $("#reqJenis").val("pensiunudzur");
          else
            $("#reqJenis").val("pensiundini");
      }
      else
      {
          reqStatusPegawaiId= "5";
          $("span[for*='labelketeranganinfo']").html("Mutasi");
          $("span[for*='labeltmtketeranganinfo']").html("Mutasi");
          $("#reqJenis").val("mutasikeluar");
      }

      $("#reqStatusPegawaiId").val(reqStatusPegawaiId);

      setInfoMasaBerlaku();
    }

    function setInfoMasaBerlaku()
    {
      var reqTanggalKematian= reqTmt= panjangTanggalKematian= "";
      reqTanggalKematian= $("#reqTanggalKematian").val();

      panjangTanggalKematian= reqTanggalKematian.length;

      if(panjangTanggalKematian == 10)
      {
        var dt1= "01";
        var mon1= parseInt(reqTanggalKematian.substring(3,5),10);
        var yr1= parseInt(reqTanggalKematian.substring(6,10),10);
        yrawal= yr1;
        var tempmon1= mon1;

        if(mon1 == "12")
        {
          mon1= "01";
          yr1= parseInt(yr1)+1;
        }
        else
        {
          mon1= parseInt(mon1)+1;

          if(parseInt(mon1) <10)
          {
            mon1= "0"+mon1;
          }
        }

        var reqStatusPegawaiKedudukanId= $("#reqStatusPegawaiKedudukanId").val();
        if(reqStatusPegawaiKedudukanId == "28")
        {
          mon1= tempmon1;
          if(parseInt(mon1) <10)
          {
            mon1= "0"+mon1;
          }
          yr1= yrawal;
        }

        reqTmt= dt1+"-"+mon1+"-"+yr1;
        $("#reqTmt").val(reqTmt);
      }
      else
      {
        $("#reqTmt").val("");
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
         <li class="collection-item ubah-color-warna">Lapor Pegawai Wafat/Tewas</li>
         <li class="collection-item">

          <form id="ff" method="post" enctype="multipart/form-data">
            <?
            if($tempinfosimpan == "1")
            {
            ?>
            <div class="row">
              <div class="input-field col s12">
                <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                  <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                </button>

                <script type="text/javascript">
                  $("#kembali").click(function() { 
                    document.location.href = "app/loadUrl/app/pegawai_add_data?reqId=<?=$reqId?>";
                  });
                </script>
              </div>
            </div>
            <?
            }
            else
            {
            ?>
            <div class="row">
              <div class="input-field col s12 m4">
                <?
                if($reqRowId == "")
                {
                ?>
                <select id="reqStatusPegawaiKedudukanId" name="reqStatusPegawaiKedudukanId">
                  <?
                  while($statuspegawaikedudukan->nextRow())
                  {
                  ?>
                  <option value="<?=$statuspegawaikedudukan->getField("STATUS_PEGAWAI_KEDUDUKAN_ID")?>"><?=$statuspegawaikedudukan->getField("NAMA")?></option>
                  <?
                  }
                  ?>
                </select>
                <label for="reqKategori">Kategori</label>
                <?
                }
                else
                {
                ?>
                <label for="reqKategori">Kategori</label>
                <input type="hidden" name="reqStatusPegawaiKedudukanId" value="<?=$reqStatusPegawaiKedudukanId?>" />
                <input placeholder type="text" id="reqKategori" disabled value="<?=$tempStatusPegawaiKedudukanNama?>" />
                <?
                }
                ?>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12 m4">
                <label for="reqNomorSK">Nomor SK <span for="labelketeranganinfo"></span></label>
                <input placeholder type="text" class="easyui-validatebox" required name="reqNomorSK" id="reqNomorSK" <?=$read?> value="<?=$reqNomorSK?>" />
              </div>
            </div>

            <div class="row">
              <div class="input-field col s12 m3">
                <label for="reqTanggalSkKematian">Tanggal SK <span for="labelketeranganinfo"></span>
                </label>
                <input placeholder required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalSkKematian" id="reqTanggalSkKematian"  value="<?=$reqTanggalSkKematian?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalSkKematian');"/>
              </div>
              <div class="input-field col s12 m3">
                <label for="reqTanggalKematian">Tanggal <span for="labelketeranganinfo"></span></label>
                <input placeholder required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalKematian" id="reqTanggalKematian"  value="<?=$reqTanggalKematian?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalKematian');"/>
              </div>
              <div class="input-field col s12 m3">
                <label for="reqTmt">TMT <span for="labeltmtketeranganinfo"></span></label>
                <input placeholder required readonly class="color-disb easyui-validatebox" type="text" name="reqTmt" id="reqTmt" value="<?=$reqTmt?>" />
              </div>
            </div>

            <div class="row">
              <div class="input-field col s12">
                <label for="reqKeterangan">Keterangan</label>
                <input placeholder type="text" class="easyui-validatebox" name="reqKeterangan" id="reqKeterangan" <?=$read?> value="<?=$reqKeterangan?>" />
              </div>
            </div>

            <div class="row">
              <div class="input-field col s12">
                <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                  <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                </button>

                <script type="text/javascript">
                  $("#kembali").click(function() { 
                    document.location.href = "app/loadUrl/app/pegawai_add_data?reqId=<?=$reqId?>";
                  });
                </script>

                <input type="hidden" name="reqStatusPegawaiId" id="reqStatusPegawaiId" value="<?=$reqStatusPegawaiId?>" />
                <input type="hidden" name="reqJenis" id="reqJenis" value="<?=$reqJenis?>" />
                <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                <input type="hidden" name="reqId" value="<?=$reqId?>" />
                <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                <input type="hidden" name="reqPensiunPegawaiId" value="<?=$reqPensiunPegawaiId?>" />
                
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

                <?
                if($reqRowId == ""){}
                else
                {
                ?>
                <button class="btn waves-effect waves-light pink" style="font-size:9pt" type="button" id="reqbatal">
                Batal
                <i class="mdi-content-save left hide-on-small-only"></i>
                </button>
                <?
                }
                ?>
              </div>
            </div>
            <?
            }
            ?>
            <!-- </div> -->
          </form>
        </li>
      </ul>
    </div>
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

    $("#reqbatal").click(function() { 

      mbox.custom({
       message: "Apakah Anda Yakin, batal lapor pegawai ?",
       options: {close_speed: 100},
       buttons: [
       {
         label: 'Ya',
         color: 'green darken-2',
         callback: function() {
           $.getJSON("pensiun_json/batal/?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>",
            function(data){
              mbox.alert(data.PESAN, {open_speed: 500}, interval = window.setInterval(function() 
              {
                clearInterval(interval);
                document.location.href= "app/loadUrl/app/pegawai_add_pegawai_status_lapor/?reqId=<?=$reqId?>&reqStatusPegawaiId=<?=$reqStatusPegawaiId?>&reqStatusPegawaiKedudukanId=<?=$reqStatusPegawaiKedudukanId?>";
              }, 1000));
              $(".mbox > .right-align").css({"display": "none"});
            });
           mbox.close();
         }
       },
       {
         label: 'Tidak',
         color: 'grey darken-2',
         callback: function() {
             //console.log('do action for no answer');
             mbox.close();
           }
         }
         ]
       });

    });

  });

  $('.materialize-textarea').trigger('autoresize');

</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>