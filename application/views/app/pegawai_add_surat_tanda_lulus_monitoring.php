<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('SuratTandaLulus');
$tempLoginLevel= $this->LOGIN_LEVEL;

$reqId= $this->input->get("reqId");
$reqPeriode= $this->input->get("reqPeriode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "011306";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

$pelayananid= $this->input->get("pelayananid");
$pelayananjenis= $this->input->get("pelayananjenis");
$pelayananrowid= $this->input->get("pelayananrowid");
$pelayanankembali= $this->input->get("pelayanankembali");

$sessionLoginLevel= $this->LOGIN_LEVEL;
$arrData= array("Surat Tanda Lulus", "No STL", "Tgl STL", "Tgl", "NPR", "NT", "Last create / update", "Last user Access", "Status", "Aksi");
$sOrder= "ORDER BY A.TANGGAL_STLUD";

$statementLevel= "";
if($tempLoginLevel == "99"){}
else
$statementLevel= " AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')";

$set= new SuratTandaLulus();
$set->selectByParams(array(), -1, -1, $statementLevel." AND A.PEGAWAI_ID = ".$reqId, $sOrder);
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
      for($i=0; $i < count($arrData); $i++)
      {
        $index= $i+1;
        ?>
        table.tabel-responsif td:nth-of-type(<?=$index?>):before { content: "<?=$arrData[$i]?>"; }
        <?
      }
      ?>
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
      reqmode= "surat_tanda_lulus_1";
      infoMode= "Apakah anda yakin mengaktifkan data terpilih"
      if(statusaktif == "")
      {
        reqmode= "surat_tanda_lulus_0";
        infoMode= "Apakah anda yakin menonaktifkan data terpilih"
      }

      $.messager.confirm('Konfirmasi', infoMode+" ?",function(r){
       if (r){
        var s_url= "surat_tanda_lulus_json/delete/?reqMode="+reqmode+"&reqId="+id+"&reqPeriode=<?=$reqPeriode?>&reqPegawaiId=<?=$reqId?>";
        //var request = $.get(s_url);
        $.ajax({'url': s_url,'success': function(msg){
          if(msg == ''){}
            else
            {
              // alert(msg);return false;
              $.messager.alert('Info', msg, 'info');
              document.location.href= "app/loadUrl/app/pegawai_add_surat_tanda_lulus_monitoring/?reqId="+pegawai_id+"&reqPeriode=<?=$reqPeriode?>";
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
          <li class="collection-item ubah-color-warna">RIWAYAT Surat Tanda lulus </li>
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
              ?>
                <a style="cursor:pointer"
                <?
                if(!empty($pelayananid))
                {
                ?>
                onClick="parent.setappload('pegawai_add_surat_tanda_lulus_add?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&pelayananid=<?=$pelayananid?>&pelayananjenis=<?=$pelayananjenis?>&pelayananrowid=<?=$pelayananrowid?>&pelayanankembali=<?=$pelayanankembali?>')"
                <?
                }
                else
                {
                ?>
                onClick="parent.setload('pegawai_add_surat_tanda_lulus_add?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>')"
                <?
                }
                ?>
                > <i class="mdi-content-add-circle-outline"> <span class=" material-font">Tambah</span></i></a>
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
              while($set->nextRow())
              {
                $reqRowId= $set->getField('SURAT_TANDA_LULUS_ID');
                $reqId= $set->getField('PEGAWAI_ID');
                $LastLevel= $set->getField('LAST_LEVEL');
                $tempAksiProses= "";
                if($sessionLoginLevel < $LastLevel)
                  $tempAksiProses= "1";
              ?>
                <tr>
                  <td><?=$set->getField('JENIS_NAMA')?></td>
                  <td><?=$set->getField('NO_STLUD')?></td>
                  <td><?=dateToPageCheck($set->getField('TANGGAL_STLUD'))?></td>
                  <td><?=dateToPageCheck($set->getField('TANGGAL_MULAI'))." s/d ".dateToPageCheck($set->getField('TANGGAL_AKHIR'))?></td>
                  <td><?=$set->getField('NILAI_NPR')?></td>
                  <td><?=$set->getField('NILAI_NT')?></td>
                  <td><?=getFormattedDateTime($set->getField('LAST_DATE'))?></td>
                  <td><?=$set->getField('LAST_USER')?></td>
                  <td>
                    <?
                    if($set->getField('STATUS') == 1) 
                    {
                      echo '<i class="mdi-av-not-interested red-text"></i>';
                    } 
                    else if($set->getField('LAST_LEVEL') == 99) 
                    {
                      echo '<i class="mdi-action-done green-text"></i>';
                    } 
                    else 
                    {
                      echo '<i class="mdi-alert-warning orange-text"></i>';
                    }
                    ?>
                  </td>
                  <td width="90px">
                    <span>
                      <?
                      if($tempAksesMenu == "A")
                      {
                        if($tempAksiProses == "1"){}
                        else
                        {
                          if($set->getField('STATUS') == 1)
                          {
					            ?>
                            <a href="javascript:void(0)" class="round waves-effect waves-light green white-text" title="Aktifkan" onClick="hapusdata('<?=$reqRowId?>','1','<?=$reqId?>')">
                              <i class="mdi-action-autorenew"></i>
                            </a>
                      <?
                          }
                          else
                          {
                      ?>
                            <a href="javascript:void(0)" class="round waves-effect waves-light red white-text" title="Hapus" onClick="hapusdata('<?=$reqRowId?>','','<?=$reqId?>')">
                              <i class="mdi-action-delete"></i>
                            </a>
                      <?
                          }
                        }
                      }
                      ?>
                    </span>
                    <span>
                    <a href="javascript:void(0)" class="round waves-effect waves-light purple white-text" title="Log"  onclick="parent.openModal('app/loadUrl/app/informasi_data_log?reqjson=surat_tanda_lulus_json/log/<?=$reqRowId?>&reqjudul=Data STL Riwayat Log')">
                        <i class="mdi-action-query-builder"></i>
                      </a>
                    </span>
                    <span>
                      <?if($set->getField('STATUS')!=1):?>
                        <a href="javascript:void(0)" class="round waves-effect waves-light blue white-text" title="Ubah"
                        <?
                        if(!empty($pelayananid))
                        {
                        ?>
                        onClick="parent.setappload('pegawai_add_surat_tanda_lulus_add?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&pelayananid=<?=$pelayananid?>&pelayananjenis=<?=$pelayananjenis?>&pelayananrowid=<?=$pelayananrowid?>&pelayanankembali=<?=$pelayanankembali?>')"
                        <?
                        }
                        else
                        {
                        ?>
                        onClick="parent.setload('pegawai_add_surat_tanda_lulus_add?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>')"
                        <?
                        }
                        ?>
                        >
                          <i class="mdi-editor-mode-edit"></i>
                        </a>
                      <?endif?>
                    </span>
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

<script type="text/javascript">
  $(document).ready(function(){
    $('.tooltipped').tooltip({delay: 50});
    $('.modal-trigger').leanModal();
  });
</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>