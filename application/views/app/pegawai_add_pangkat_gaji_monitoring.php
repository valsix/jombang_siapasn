<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('PangkatRiwayat');
$this->load->model('GajiRiwayat');

$reqId= $this->input->get("reqId");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "1201";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

// $reqRowId= httpFilterRequest("reqRowId");

$arrData= array("Gol", "TMT Gol", "No. SK", "Tgl.SK", "Jenis Gol/KP", "Last create / update", "Last user Access", "Aksi");
$pangkat= new PangkatRiwayat();
$pangkat->selectByParams(array(), -1, -1, " AND A.PEGAWAI_ID = ".$reqId);
// echo $pangkat->query; exit;


$gaji= new GajiRiwayat();
$gaji->selectByParams(array(), -1, -1, " AND A.PEGAWAI_ID = ".$reqId);
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
  
  <!-- CORE CSS-->    
  <link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
  <!-- CSS style Horizontal Nav-->    
  <link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
  <!-- Custome CSS-->    
  <link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">
  
  <style type="text/css">
    .sm-text {
      font-size: 8pt;
    }

    .md-text {
      font-size: 10pt;
    }

    .pad{
      padding: 1vw;
    }

    .mar {
      margin-top: 0;
    }

    .rounded {
      padding:5px;
      border-radius: 50px;
      font-size: 6pt;
      /*margin-top: 30px;*/
    }

    .rounded-bg {
      border-radius: 8px;
    }

    .material-font {
      font-family: Roboto;
      font-weight: 300;
    }

    .material-bold {
      font-family: Roboto;
      font-weight: 500;
    }
  </style>
</head>

<body>    
  <!--Basic Form-->
  <div id="basic-form" class="section">
    <div class="row">

      <div class="col s12 m12 l6 ">
        <div class=" card-panel white rounded-bg">
          <p class="flow-text mar">Riwayat Pangkat</p>
          <table class="bordered material-font striped" id="link-table">
            <thead> 
              <tr>
                <th class="purple-text material-font md-text">Gol</th>
                <th class="purple-text material-font md-text">TMT Gol</th>
                <th class="purple-text material-font md-text">No. SK</th>
                <th class="purple-text material-font md-text">Tgl.SK</th>
                <th class="purple-text material-font md-text">Jenis Gol/KP</th>
                <th class="purple-text material-font md-text">Last create / update</th>
                <th class="purple-text material-font md-text">Last user Access</th> 
                <th class="purple-text material-font md-text" style="min-width:80px">Aksi</th> 
              </tr>
            </thead>
            <tbody>
              <?
              while($pangkat->nextRow())
              {
                ?>
                <tr class="md-text">
                  <td><?=$pangkat->getField('PANGKAT_KODE')?></td>
                  <td><?=dateToPageCheck($pangkat->getField('TMT_PANGKAT'))?></td>
                  <td><?=$pangkat->getField('NO_SK')?></td>
                  <td><?=dateToPageCheck($pangkat->getField('TANGGAL_SK'))?></td>
                  <td><?=$pangkat->getField('JENIS_RIWAYAT_NAMA')?></td>
                  <td><?=dateToPageCheck($pangkat->getField('LAST_DATE'))?></td>
                  <td><?=$pangkat->getField('LAST_USER')?></td>
                  <td>

                    <?$reqRowId = $pangkat->getField('PANGKAT_RIWAYAT_ID')?>
                    <!-- <a href="javascript:void(0)" class="rounded material-bold purple white-text" onclick="addData('')">TAMBAH</a> -->
                    <a href="javascript:void(0)" class="rounded material-bold blue white-text" onClick="parent.setload('pegawai_add_pangkat_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>')">UBAH</a>
                    <a href="javascript:void(0)" class="rounded material-bold red white-text" onClick="hapusData('<?=$arrData[$index_loop]["JABATAN_PRESTASI_ID"]?>')">HAPUS</a>
                  </td>
                </tr>
                <?
              }
              ?>
            </tbody>
          </table>
        </div>


      </div>

      <div class="col s12 m12 l6 ">
        <div class=" card-panel white rounded-bg">
          <p class="flow-text mar">Riwayat Gaji</p>
          <table class="bordered material-font striped" id="link-table">
            <thead> 
              <tr>
                <th class="purple-text material-font md-text">Jenis</th>
                <th class="purple-text material-font md-text">No. SK</th>
                <th class="purple-text material-font md-text">Tmt riwayat</th>
                <th class="purple-text material-font md-text">Gol/Pangkat</th>
                <th class="purple-text material-font md-text">Last create / update</th>
                <th class="purple-text material-font md-text">Last user Access</th> 
                <th class="purple-text material-font md-text" style="min-width:70px">Aksi</th> 
              </tr>
            </thead>
            <? 
            while($gaji->nextRow())
            {
              ?>
              <tr class="md-text">
                <td><?=$gaji->getField('JENIS_KENAIKAN_NAMA')?></td>
                <td><?=$gaji->getField('NO_SK')?></td>
                <td><?=dateToPageCheck($gaji->getField('TMT_SK'))?></td>
                <td><?=$gaji->getField('PANGKAT_KODE')?></td>
                <td><?=dateToPageCheck($gaji->getField('LAST_DATE'))?></td>
                <td><?=$gaji->getField('LAST_USER')?></td>
                <td>

                  <?$reqRowId = $gaji->getField('GAJI_RIWAYAT_ID')?>
                  <!-- <a href="javascript:void(0)" class="rounded material-bold purple white-text" onclick="addData('')">TAMBAH</a> -->
                  <a href="javascript:void(0)" class="rounded material-bold blue white-text" onClick="parent.setload('pegawai_add_gaji_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>')">UBAH</a>
                  <a href="javascript:void(0)" class="rounded material-bold red white-text" onClick="hapusData('<?=$arrData[$index_loop]["JABATAN_PRESTASI_ID"]?>')">HAPUS</a>
                </td>
              </tr>
              <? 
            }
            ?>
          </table>
        </div>
      </div>

    </div>
  </div>
  <!-- jQuery Library -->
  <!-- <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script> -->

  <!--materialize js-->
  <script type="text/javascript" src="lib/materializetemplate/js/materialize.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('select').material_select();
    });

    $('.materialize-textarea').trigger('autoresize');

  </script>
</body>