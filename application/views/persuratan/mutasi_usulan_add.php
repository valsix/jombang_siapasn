<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqJenisMutasiId= $this->input->get("reqJenisMutasiId");
$reqJenisJabatanTugasId= $this->input->get("reqJenisJabatanTugasId");
$reqMode= $this->input->get("reqMode");
// $CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
// $tempMenuId= "010401";
// $tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

if($reqJenisMutasiId == "1")
{
  $arrJenisJabatanTugasId= array("11", "12");
  $arrJenisJabatanTugasNama= array("Jabatan Struktural", "Pelaksana");
}
elseif($reqJenisMutasiId == "2")
{
  $arrJenisJabatanTugasId= array("21", "22", "29");
  $arrJenisJabatanTugasNama= array("JFT Pendidikan", "JFT Kesehatan", "Mutasi Intern Pelaksana");
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

  <!-- AUTO KOMPLIT -->
  <link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
  <script src="lib/autokomplit/jquery-ui.js"></script>
  
  <script type="text/javascript"> 
	
    $(function(){
      
      $("#reqJenisMutasiId,#reqJenisJabatanTugasId").change(function() { 
        var reqJenisMutasiId= reqJenisJabatanTugasId= "";
        reqJenisMutasiId= $("#reqJenisMutasiId").val();
        reqJenisJabatanTugasId= $("#reqJenisJabatanTugasId").val();
        // alert(reqJenisMutasiId);

        if(reqJenisMutasiId == "1")
        {
          if(reqJenisJabatanTugasId == "11")
          document.location.href = "app/loadUrl/persuratan/mutasi_usulan_add_struktural?reqJenisMutasiId="+reqJenisMutasiId+"&reqJenisJabatanTugasId="+reqJenisJabatanTugasId+"&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>";
          else if(reqJenisJabatanTugasId == "12")
          document.location.href = "app/loadUrl/persuratan/mutasi_usulan_add_fungsional?reqJenisMutasiId="+reqJenisMutasiId+"&reqJenisJabatanTugasId="+reqJenisJabatanTugasId+"&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>";
          else
          document.location.href = "app/loadUrl/persuratan/mutasi_usulan_add?reqJenisMutasiId="+reqJenisMutasiId+"&reqJenisJabatanTugasId="+reqJenisJabatanTugasId;
        }
        else if(reqJenisMutasiId == "2")
        {
          if(reqJenisJabatanTugasId == "21")
          document.location.href = "app/loadUrl/persuratan/mutasi_usulan_add_jft_pendidikan?reqJenisMutasiId="+reqJenisMutasiId+"&reqJenisJabatanTugasId="+reqJenisJabatanTugasId+"&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>";
          else if(reqJenisJabatanTugasId == "22")
          document.location.href = "app/loadUrl/persuratan/mutasi_usulan_add_jft_kesehatan?reqJenisMutasiId="+reqJenisMutasiId+"&reqJenisJabatanTugasId="+reqJenisJabatanTugasId+"&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>";
          else if(reqJenisJabatanTugasId == "29")
          document.location.href = "app/loadUrl/persuratan/mutasi_usulan_add_intern_pelaksana?reqJenisMutasiId="+reqJenisMutasiId+"&reqJenisJabatanTugasId="+reqJenisJabatanTugasId+"&reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>";
          else
          document.location.href = "app/loadUrl/persuratan/mutasi_usulan_add?reqJenisMutasiId="+reqJenisMutasiId+"&reqJenisJabatanTugasId="+reqJenisJabatanTugasId;
        }
        else
        {
          document.location.href = "app/loadUrl/persuratan/mutasi_usulan_add";
        }
      });
    });

    // if(reqJenisMutasiId=="1")
    // {
    //   document.location.href = "app/loadUrl/app/pegawai_add_jabatan?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqJenisJabatan=<?=$reqJenisJabatan?>";
    // }
    // else if(jenis_jabatan==1)
    // {
    //   document.location.href = "app/loadUrl/app/pegawai_add_jabatan_struktural_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqJenisJabatan=<?=$reqJenisJabatan?>";
    // }
    // else if(jenis_jabatan==2)
    // {
    //   document.location.href = "app/loadUrl/app/pegawai_add_jabatan_fungsional_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqJenisJabatan=<?=$reqJenisJabatan?>";
    // }
    // else if(jenis_jabatan==3)
    // {
    //   document.location.href = "app/loadUrl/app/pegawai_add_jabatan_tertentu_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqJenisJabatan=<?=$reqJenisJabatan?>";
    // }
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
         <li class="collection-item ubah-color-warna">USUL MUTASI UNIT KERJA</li>
         <li class="collection-item">

            <form id="ff" method="post" enctype="multipart/form-data">
              <div class="row">
                <div class="input-field col s12 m6">
                  <select id="reqJenisMutasiId" name="reqJenisMutasiId" >
                    <option value="" <? if($reqJenisMutasiId == "") echo 'selected';?>></option>
                    <option value="1" <? if($reqJenisMutasiId == 1) echo 'selected';?>>Mutasi Struktural / Pelaksana</option>
                    <option value="2" <? if($reqJenisMutasiId == 2) echo 'selected';?>>Tugas JFT / T. Tambahan / Mutasi Intern Pelaksana</option>
                  </select>
                  <label for="reqJenisMutasiId">Jenis Mutasi</label>
                </div>
              </div>

              <?
              if($reqJenisMutasiId == ""){}
              else
              {
              ?>
              <div class="row">
                <div class="input-field col s12 m6">
                  <select id="reqJenisJabatanTugasId" name="reqJenisJabatanTugasId" >
                    <option value="" <? if($reqJenisJabatanTugasId == "") echo 'selected';?>></option>
                    <?
                    for($i=0; $i < count($arrJenisJabatanTugasId); $i++)
                    {
                    ?>
                    <option value="<?=$arrJenisJabatanTugasId[$i]?>" <? if($arrJenisJabatanTugasId[$i] == $reqJenisJabatanTugasId) echo 'selected';?>><?=$arrJenisJabatanTugasNama[$i]?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqJenisJabatanTugasId">Jenis Jabatan / Jenis Tugas</label>
                </div>
              </div>
              <?
              }
              ?>

              <div class="row">
                <div class="input-field col s12">
                  <br>
                  <br>
                  <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>

                  <script type="text/javascript">
                    $("#kembali").click(function() { 
                      closeparenttab();
                    });

                    function closeparenttab()
                    {
                    if (window.opener && window.opener.document)
                        {
                            if (typeof window.opener.setCariInfo === 'function')
                            {
                                window.opener.setCariInfo();
                            }
                        }
                        window.close();
                    }

                    function reloadparenttab()
                    {
                    if (window.opener && window.opener.document)
                      {
                        if (typeof window.opener.setCariInfo === 'function')
                        {
                          window.opener.setCariInfo();
                        }
                      }
                    }
                  </script>

                  <input type="hidden"  name="reqTipePegawaiId" value="<?=$reqTipePegawaiId?>" />
                  <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                  <input type="hidden" name="reqId" value="<?=$reqId?>" />
                  <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                </div>
              </div>

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
  });

  $('.materialize-textarea').trigger('autoresize');

</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>