<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('PenilaianSkp');
$tempLoginLevel= $this->LOGIN_LEVEL;

$reqId= $this->input->get("reqId");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "010701";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

$pelayananid= $this->input->get("pelayananid");
$pelayananjenis= $this->input->get("pelayananjenis");
$pelayananrowid= $this->input->get("pelayananrowid");
$pelayanankembali= $this->input->get("pelayanankembali");

$statementLevel= "";
if($tempLoginLevel == "99"){}
else
$statementLevel= " AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')";

$set= new PenilaianSkp();
$set->selectByParams(array(), -1, -1, $statementLevel." AND A.PEGAWAI_ID = ".$reqId);
// echo $set->query; exit;

// kondisi untuk menu
$this->load->library('globalmenusapk');
$vmenusapk= new globalmenusapk();
$arrmenusapk= $vmenusapk->setmenusapk($tempMenuId);
// print_r($arrmenusapk);exit;
$lihatsapk= $arrmenusapk["lihat"];
$kirimsapk= $arrmenusapk["kirim"];
$tariksapk= $arrmenusapk["tarik"];
$syncsapk= $arrmenusapk["sync"];
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

  <style type="text/css">
    @media 
    only screen and (max-width: 760px),
    (min-device-width: 768px) and (max-device-width: 1024px)  {
      table.tabel-responsif thead th{ display:none; }
      /*
      Label the data
      */
      <?
      $arrData= array("Tahun", "Pejabat Penilai", "Status", "Aksi");


      for($i=0; $i < count($arrData); $i++)
      {
        $index= $i+1;
        ?>
        table.tabel-responsif td:nth-of-type(<?=$index?>):before { content: "<?=$arrData[$i]?>"; }
        <? 
      }  ?>
    }

    .round {
      border-radius: 50%;
      padding: 5px;
    }
  </style>
  <script type="text/javascript">
    function hapusdata(id, statusaktif, pegawai_id)
    {
      $.messager.defaults.ok = 'Ya';
      $.messager.defaults.cancel = 'Tidak';
      reqmode= "penilaian_skp_1";
      infoMode= "Apakah anda yakin mengaktifkan data terpilih"
      if(statusaktif == "")
      {
        reqmode= "penilaian_skp_0";
        infoMode= "Apakah anda yakin menonaktifkan data terpilih"
      }

      $.messager.confirm('Konfirmasi', infoMode+" ?",function(r){
       if (r){
        var s_url= "penilaian_skp_json/delete/?reqMode="+reqmode+"&reqId="+id;
        //var request = $.get(s_url);
        $.ajax({'url': s_url,'success': function(msg){
          if(msg == ''){}
            else
            {
              // alert(msg);return false;
              $.messager.alert('Info', msg, 'info');
              document.location.href= "app/loadUrl/app/pegawai_add_skp_monitoring/?reqId="+pegawai_id;
            }
          }});
      }
    });  
    }

  </script>
</head>

<body>
 <div id="basic-form" class="section">
  <div class="row">
    <?
    if(!empty($pelayananid))
    {
    ?>
    <div class="col s12 m12" style="padding-left: 15px;">
    <?
    }
    else
    {
    ?>
    <div class="col s12 m10 offset-m1 ">
    <?
    }
    ?>
      <ul class="collection card">
        <li class="collection-item ubah-color-warna">PENILAIAN SKP / PPK</li>
        <li class="collection-item">
          <div class="row">
            <?
            if(!empty($pelayananid))
            {
            ?>
            <input type="hidden" id="pelayananid" value="<?=$pelayananid?>" />
            <input type="hidden" id="pelayananjenis" value="<?=$pelayananjenis?>" />
            <input type="hidden" id="pelayananrowid" value="<?=$pelayananrowid?>" />
            <input type="hidden" id="pelayanankembali" value="<?=$pelayanankembali?>" />
            <a href="javascript:void(0)" class="reloadpelayananriwayat" title="Kembali" >
              <i class="orange mdi-navigation-arrow-back"></i>
              <span class=" material-font">Kembali</span></i>
            </a>
            <?
            }
            ?>
            <?
            if($tempAksesMenu == "A")
            
            {
              // PENNNNNTIIIIINGGGG
              // PERLU DITAMBAHKAN TOMBOL TAMBAH SKP DIBAWAH TAHUN 2021
              // ke halaman formulir lama pegawai_add_skp21_data.php
            ?>
              <a style="cursor:pointer"
              <?
              if(!empty($pelayananid))
              {
              ?>
              onClick="parent.setappload('pegawai_add_skp_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&pelayananid=<?=$pelayananid?>&pelayananjenis=<?=$pelayananjenis?>&pelayananrowid=<?=$pelayananrowid?>&pelayanankembali=<?=$pelayanankembali?>')"
              <?
              }
              else
              {
              ?>
              onClick="parent.setload('pegawai_add_skp_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>')"
              <?
              }
              ?>
              > <i class="mdi-content-add-circle-outline"> <span class=" material-font">Tambah SKP 2020 ke bawah</span></i></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

              <a style="cursor:pointer"
              <?
              if(!empty($pelayananid))
              {
              ?>
              onClick="parent.setappload('pegawai_add_skp21_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&pelayananid=<?=$pelayananid?>&pelayananjenis=<?=$pelayananjenis?>&pelayananrowid=<?=$pelayananrowid?>&pelayanankembali=<?=$pelayanankembali?>')"
              <?
              }
              else
              {
              ?>
              onClick="parent.setload('pegawai_add_skp21_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>')"
              <?
              }
              ?>
              > <i class="mdi-content-add-circle-outline"> <span class=" material-font">Tambah SKP 2021</span></i></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

              <a style="cursor:pointer"
              <?
              if(!empty($pelayananid))
              {
              ?>
              onClick="parent.setappload('pegawai_add_skp22_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&pelayananid=<?=$pelayananid?>&pelayananjenis=<?=$pelayananjenis?>&pelayananrowid=<?=$pelayananrowid?>&pelayanankembali=<?=$pelayanankembali?>')"
              <?
              }
              else
              {
              ?>
              onClick="parent.setload('pegawai_add_skp22_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>')"
              <?
              }
              ?>
              > <i class="mdi-content-add-circle-outline"> <span class=" material-font">Tambah SKP 2022</span></i></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

              
              <a style="cursor:pointer"
               onClick="parent.setload('pegawai_add_kinerja_monitoring?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>')"
              > <i class="mdi-content-add-circle-outline"> <span class=" material-font">Tambah Kinerja</span></i></a>

            <?
            }
            ?>

            <?
            if(!empty($lihatsapk))
            {
            ?>
              <a style="cursor:pointer; margin-left: 20px" onClick="parent.setload('pegawai_add_skp22_monitoring_bkn?reqId=<?=$reqId?>')"> <i class="mdi-action-view-list"> <span class=" material-font">BKN</span></i></a>
            <?
            }
            ?>
          </div>
          <table class="bordered striped md-text table_list tabel-responsif responsive-table" id="link-table">
            <thead> 
              <tr>
                <?
                for($i=0; $i < count($arrData); $i++)
                {
                  ?>
                  <th class="green-text material-font"><?=$arrData[$i]?></th>
                  <?
                }
                ?>
              </tr>
            </thead>
            <tbody>
              <?
              while($set->nextRow()){
                ?>
                <tr>
                  <td><?=$set->getField('TAHUN')?></td>
                  <td><?=$set->getField('PEGAWAI_PEJABAT_PENILAI_NAMA')?></td>
                  <td>
                    <?
                    if($set->getField('STATUS') == 1) {
                      echo '<i class="mdi-av-not-interested red-text"></i>';
                    } else if($set->getField('LAST_LEVEL') == 99) {
                      echo '<i class="mdi-action-done green-text"></i>';
                    } else {
                      echo '<i class="mdi-alert-warning orange-text"></i>';
                    }
                    ?>
                  </td>
                  <td width="90px">
                    <?
                    $reqRowId = $set->getField('PENILAIAN_SKP_ID');
                    $reqId = $set->getField('PEGAWAI_ID');
                    ?>

                    <span>
                      <span>
                        <?
                        // A;R;D
                        if($tempAksesMenu == "A")
                        {
                        ?>
                        <?if($set->getField('STATUS')==1):?>
                        <a href="javascript:void(0)" class="round waves-effect waves-light green white-text" title="Aktifkan" onClick="hapusdata('<?=$reqRowId?>','1','<?=$reqId?>')">
                          <i class="mdi-action-autorenew"></i>
                        </a>
                        <?else:?>
                        <a href="javascript:void(0)" class="round waves-effect waves-light red white-text" title="Hapus" onClick="hapusdata('<?=$reqRowId?>','','<?=$reqId?>')">
                          <i class="mdi-action-delete"></i>
                        </a>
                        <?endif?>
                        <?
                        }
                        ?>
                      </span>
                      <span>
                        <a href="javascript:void(0)" class="round waves-effect waves-light purple white-text" title="Log"  onclick="parent.openModal('app/loadUrl/app/informasi_data_log?reqjson=penilaian_skp_json/log/<?=$reqRowId?>&reqjudul=Data Penilaian SKP Log')">
                          <i class="mdi-action-query-builder"></i>
                        </a>
                      </span>
                      <span>
                        <?if($set->getField('STATUS')!=1):?>
                        <a href="javascript:void(0)" class="round waves-effect waves-light blue white-text" title="Ubah"
                        <?
                          $vurl= "xxx";
                          if($set->getField('TAHUN') < 2021)
                          {
                            $vurl= "pegawai_add_skp_data";
                          }
                          else if($set->getField('TAHUN') == 2021)
                          {
                            $vurl= "pegawai_add_skp21_data";
                          }
                          else if($set->getField('TAHUN') == 2022)
                          {
                            $vurl= "pegawai_add_skp22_data";
                          }
                          else if($set->getField('TAHUN') == 2023)
                          {
                            $vurl= "pegawai_add_skp23_data";
                          }
                          if(!empty($pelayananid))
                          {
                        ?>
                            onClick="parent.setappload('<?=$vurl?>?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&pelayananid=<?=$pelayananid?>&pelayananjenis=<?=$pelayananjenis?>&pelayananrowid=<?=$pelayananrowid?>&pelayanankembali=<?=$pelayanankembali?>')"
                        <?
                          }
                          else
                          {
                        ?>
                            onClick="parent.setload('<?=$vurl?>?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>')"
                        <?
                          }
                        ?>
                        >
                          <i class="mdi-editor-mode-edit"></i>
                        </a>
                        <!-- <a href="javascript:void(0)" class="rounded material-bold blue white-text" onClick="parent.setload('pegawai_add_pangkat_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>')">UBAH</a> -->
                        <?endif?>
                      </span>

                   <!--  <span>
                     <a href="javascript:void(0)" class="rounded material-bold blue white-text" onClick="parent.setload('pegawai_add_pendidikan_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>')">UBAH</a>
                   </span>
                   <span>
                     <?if($set->getField('STATUS')==1):?>
                     <a href="javascript:void(0)" class="rounded material-bold green white-text" onClick="hapusdata('<?=$reqRowId?>','1','<?=$reqId?>')">AKTIFKAN</a>
                     <?else:?>
                     <a href="javascript:void(0)" class="rounded material-bold red white-text" onClick="hapusdata('<?=$reqRowId?>','','<?=$reqId?>')">HAPUS</a>
                     <?endif?>
                   </span> -->
                   
                 </td>
               </tr>
               <?
             }
             ?>
           </tbody>
         </table>
       </li>
     </ul>
   </div>
 </div>
</div>

<!-- jQuery Library -->
<!-- <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script> -->

<?
if(!empty($pelayananid))
{
?>
<script type="text/javascript" src="lib/easyui/pelayanan-kembali.js"></script>
<?
}
?>

<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/materialize.min.js"></script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>