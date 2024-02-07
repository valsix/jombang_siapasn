<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('DiklatStruktural');
$this->load->model('Diklat');

$tempLoginLevel= $this->LOGIN_LEVEL;
$infologinid= $this->LOGIN_ID;

$reqId= $this->input->get("reqId");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "010601";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

// kondisi untuk menu
$this->load->library('globalmenusapk');
$vmenusapk= new globalmenusapk();
$arrmenusapk= $vmenusapk->setmenusapk($tempMenuId);
// print_r($arrmenusapk);exit;
$lihatsapk= $arrmenusapk["lihat"];
$kirimsapk= $arrmenusapk["kirim"];
$tariksapk= $arrmenusapk["tarik"];
$syncsapk= $arrmenusapk["sync"];

if(empty($lihatsapk))
{
  redirect("app/loadUrl/app/pegawai_add_diklat_struktural_monitoring?reqId=".$reqId);
  exit;
}

// untuk tambahan kode
$this->load->model('base-api/InfoData');
$this->load->model('base-api/DataCombo');

$arrkunci= [];
$set= new Diklat();
$set->selectByParams(array(), -1, -1, " AND A.DIKLAT_ID <= 5 ", "ORDER BY A.DIKLAT_ID");
// echo $set->query;exit;
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["DIKLAT_ID"]= $set->getField("DIKLAT_ID");
  $arrdata["NAMA"]= $set->getField("NAMA");
  array_push($arrkunci, $arrdata);
}
// print_r($arrkunci);exit;

$set= new InfoData();
$infonipbaru= $set->getnip($reqId);
// echo $infonipbaru;exit;

$statementLevel= " AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')";
$arrdatariwayat= [];
$set= new DiklatStruktural();
$set->selectByParams(array(), -1, -1, $statementLevel." AND A.PEGAWAI_ID = ".$reqId);
// echo $set->query;exit;
while($set->nextRow())
{
  $date      = new DateTime($set->getField("TANGGAL_MULAI"));
  $tmtFormat = $date->format('d-m-Y');

  $infokunci= $set->getField("DIKLAT_ID");
  $arrdata= [];
  // kunci untuk kondisi
  $arrdata["key"]= $infokunci;
  $arrdata["BKN_ID"]= $set->getField("BKN_ID");

  // data sesuai riwayat
  $arrdata["DIKLAT_STRUKTURAL_ID"]= $set->getField("DIKLAT_STRUKTURAL_ID");
  $arrdata["NAMA"]= $set->getField("NAMA");
  $arrdata["DIKLAT_ID"]= $set->getField("DIKLAT_ID");
  $arrdata["NO_STTPP"]= $set->getField("NO_STTPP");
  $arrdata["ANGKATAN"]= $set->getField("ANGKATAN");
  $arrdata["ID_SAPK"]= $set->getField("ID_SAPK");
  $arrdata["TAHUN"]= $set->getField("TAHUN");
  $arrdata["PENYELENGGARA"]= $set->getField("PENYELENGGARA");
  $arrdata["TANGGAL_MULAI"]= $tmtFormat;
  $arrdata["TANGGAL_SELESAI"]= dateToPageCheck($set->getField("TANGGAL_SELESAI"));
  
  array_push($arrdatariwayat, $arrdata);

  // kalau tidak ada maka masukkan
  // if(!in_array($infokunci, $arrkunci))
  // {
  //   array_push($arrkunci, $infokunci);
  // }
}
// print_r($arrdatariwayat);exit;

$arrdatabkn= [];
$arrparam= ["nip"=>$infonipbaru, "vurl"=>"Data_rw_diklat_json"];
$set= new DataCombo();
$set->selectdata($arrparam, "");
while($set->nextRow())
{

  $date      = new DateTime($set->getField("tanggal"));
  $tmtFormat = $date->format('d-m-Y');
  $infokunci= $set->getField("latihanStrukturalId");
  $arrdata= [];
  // kunci untuk kondisi
  $arrdata["key"]= $infokunci;

  // data sesuai api bkn
  $arrdata["id"]= $set->getField("id");
  $arrdata["idPns"]= $set->getField("idPns");
  $arrdata["institusiPenyelenggara"]= $set->getField("institusiPenyelenggara");
  $arrdata["jumlahJam"]= $set->getField("jumlahJam");
  $arrdata["latihanStrukturalId"]= $set->getField("latihanStrukturalId");
  $arrdata["latihanStrukturalNama"]= $set->getField("latihanStrukturalNama");
  $arrdata["nipBaru"]= $set->getField("nipBaru");
  $arrdata["nipLama"]= $set->getField("nipLama");
  $arrdata["nomor"]= $set->getField("nomor");
  $arrdata["tahun"]= $set->getField("tahun");
  $arrdata["tanggal"]= $tmtFormat;
  $arrdata["tanggalSelesai"]= $set->getField("tanggalSelesai");
  
  array_push($arrdatabkn, $arrdata);

  // kalau tidak ada maka masukkan
  // if(!in_array($infokunci, $arrkunci))
  // {
  //   array_push($arrkunci, $infokunci);
  // }
}
// print_r($arrdatabkn);exit;

// untuk urut tanggal desc
// usort($arrkunci, "sortdatefunctiondesc");
// untuk urut tanggal asc
// usort($arrkunci, "sortdatefunctionasc");
// print_r($arrkunci);exit;
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

  <link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

  <link rel="stylesheet" type="text/css" href="css/gaya-baru.css">
</head>
<body>
  <div class="area-konten area-monitoring-riwayat">
    <div class="judul-konten">
      Monitoring  DIKLAT STRUKTURAL
      <a style="cursor:pointer; float: right; color: white" onClick="parent.setload('pegawai_add_diklat_struktural_monitoring?reqId=<?=$reqId?>')"> <i class="mdi-navigation-arrow-back"> <span class=" material-font">Kembali</span></i></a>
    </div>
    <div class="inner">
      <div class="judul-instansi">
        <div class="tanggal"></div>
        <div class="data">
          <div class="title"></div>
          <div class="data-siapasn">Data SIAPASN</div>
          <div class="data-bkn">Data BKN</div>
          <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
      </div>

      <?
      foreach ($arrkunci as $key => $value)
      {
        $vkeycari= $value["DIKLAT_ID"];
        // print_r($value);exit;

        $infocarikey= $vkeycari;
         // $vkeycari= 2022;

        // untuk ambil data riwayat
        $vdatariwayat= in_array_column($infocarikey, "key", $arrdatariwayat);
        $vurldetil= $infoidsapkriwayat= $infoidriwayat= "";
        $infonamariwayat= $infoidsapkriwayat= $infonosttppriwayat= $infotanggalmulairiwayat= $infotanggalselesairiwayat= $infotahunriwayat= $infopenyelenggarariwayat= "";

        if(!empty($vdatariwayat))
        {
          $indexdata= $vdatariwayat[0];
          $infoidriwayat= $arrdatariwayat[$indexdata]["DIKLAT_STRUKTURAL_ID"];
          $infoidsapkriwayat= $arrdatariwayat[$indexdata]["ID_SAPK"];
          $infobknid= $arrdatariwayat[$indexdata]["BKN_ID"];
          $infonamariwayat= $arrdatariwayat[$indexdata]["NAMA"];
          $infonosttppriwayat= $arrdatariwayat[$indexdata]["NO_STTPP"];
          $infotahunriwayat= $arrdatariwayat[$indexdata]["TAHUN"];
          $infopenyelenggarariwayat= $arrdatariwayat[$indexdata]["PENYELENGGARA"];
          $infotanggalmulairiwayat= $arrdatariwayat[$indexdata]["TANGGAL_MULAI"];
          $infotanggalselesairiwayat= $arrdatariwayat[$indexdata]["TANGGAL_SELESAI"];

          $vurldetil= "pegawai_add_diklat_struktural_data";
        }

        // untuk ambil data bkn
        $infoidbkn= "";
        $vdatabkn= in_array_column($infocarikey, "key", $arrdatabkn);
        $infonamabkn= $infonosttppbkn= $infotahunbkn= $infopenyelenggarabkn= $infotanggalmulaibkn= $infotanggalselesaibkn="";
        if(!empty($vdatabkn))
        {
          $indexdata= $vdatabkn[0];
          $infoidbkn= $arrdatabkn[$indexdata]["id"];
          $infonamabkn= $arrdatabkn[$indexdata]["latihanStrukturalNama"];
          $infonosttppbkn= $arrdatabkn[$indexdata]["nomor"];
          $infotahunbkn= $arrdatabkn[$indexdata]["tahun"];
          $infopenyelenggarabkn= $arrdatabkn[$indexdata]["institusiPenyelenggara"];
         
          $infotanggalmulaibkn= $arrdatabkn[$indexdata]["tanggal"];
          $infotanggalselesaibkn= $arrdatabkn[$indexdata]["tanggalSelesai"];
        }

      ?>
      <div class="item">
        <div class="tanggal"><span><img src="images/icon-tanggal.png"></span> <?=$value["NAMA"]?></div>
        <div class="data">

          <div class="baris atas">
            <div class="title"></div>
            <div class="data-siapasn"></div>
            <div class="data-bkn"></div>
            <div class="clearfix"></div>
          </div>

          <div class="baris">
            <div class="title">Jenjang Diklat</div>
            <div class="data-siapasn">
              <?
              if(empty($infonamariwayat))
              {
              ?>
              <span class="tidak-ada-data"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Belum ada data</span>
              <?
              }
              ?>
              <?=$infonamariwayat?>
            </div>
            <div class="data-bkn">
              <?
              if(empty($infonamabkn))
              {
              ?>
              <span class="tidak-ada-data"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Belum ada data</span>
              <?
              }
              ?>
              <?=$infonamabkn?>
            </div>
            <div class="clearfix"></div>
          </div>

          <div class="baris">
            <div class="title"> No. STTPP</div>
            <div class="data-siapasn"><?=$infonosttppriwayat?$infonosttppriwayat:'-';?></div>
            <div class="data-bkn"><?=$infonosttppbkn?$infonosttppbkn:'-';?></div>
            <div class="clearfix"></div>
          </div>

          <div class="baris">
            <div class="title">Tahun</div>
            <div class="data-siapasn"><?=$infotahunriwayat?$infotahunriwayat:'-';?></div>
            <div class="data-bkn"><?=$infotahunbkn?$infotahunbkn:'-';?></div>
            <div class="clearfix"></div>
          </div>

          <div class="baris">
            <div class="title">Penyelenggara</div>
            <div class="data-siapasn"><?=$infopenyelenggarariwayat?$infopenyelenggarariwayat:'-';?></div>
            <div class="data-bkn"><?=$infopenyelenggarabkn?$infopenyelenggarabkn:'-';?></div>
            <div class="clearfix"></div>
          </div>

          <?
          $infotanggalriwayat= "-";
          if(!empty($infotanggalmulairiwayat) || !empty($infotanggalselesairiwayat))
            $infotanggalriwayat= coalesce($infotanggalmulairiwayat, '-')." s/d ".coalesce($infotanggalselesairiwayat, '');

          $infotanggalbkn= "-";
          if(!empty($infotanggalmulaibkn) || !empty($infotanggalselesaibkn))
            $infotanggalbkn= coalesce($infotanggalmulaibkn, '-')." s/d ".coalesce($infotanggalselesaibkn, '');
          ?>
          <div class="baris">
            <div class="title">Tanggal Mulai</div>
            <div class="data-siapasn"><?=$infotanggalmulairiwayat?$infotanggalmulairiwayat:'-';?></div>
            <div class="data-bkn"><?=$infotanggalmulaibkn?$infotanggalmulaibkn:'-';?></div>
            <div class="clearfix"></div>
          </div>
          <div class="baris">
            <div class="title">Tanggal Selesai</div>
            <div class="data-siapasn"><?=$infotanggalselesairiwayat?$infotanggalselesairiwayat:'-';?></div>
            <div class="data-bkn"><?=$infotanggalselesaibkn?$infotanggalselesaibkn:'-';?></div>
            <div class="clearfix"></div>
          </div>

          <div class="baris bawah">
            <div class="title"></div>
            <div class="data-siapasn"></div>
            <div class="data-bkn"></div>
            <div class="clearfix"></div>
          </div>

        </div>

        <div class="aksi">
          <?
          if(empty($infoidsapkriwayat))
          {
          ?>
          <div class="info-sinkron belum">
            <span class="ikon"><img src="images/icon-belum-sinkron.png"></span>
            <span class="teks">Belum Sinkron</span>
          </div>
          <?
          }
          else
          {
          ?>
          <div class="info-sinkron sudah">
            <span class="ikon"><img src="images/icon-sudah-sinkron.png"></span>
            <span class="teks">Sudah Sinkron</span>
          </div>
          <?
          }
          ?>

          <div class="aksi-tombol">
            <?
            $infoidsinkronsiapasnbkndisabled= $infoidsinkronsiapasnbkn= "";
            // apabila kalau ada id riwayat dan id bkn kosong maka button integrasi siapasn ke bkn akan muncul
            // atau ada id riwayat dan ada id bkn maka button integrasi siapasn ke bkn akan muncul
            if( (!empty($infoidriwayat) && empty($infoidbkn) || !empty($infoidriwayat) && !empty($infoidbkn)) && !empty($kirimsapk) )
            {
              $infoidsinkronsiapasnbkn= "infoidsinkronsiapasnbkn";
            }
            else
            {
              $infoidsinkronsiapasnbkndisabled= "disabled";
            }
            ?>
            <a class="<?=$infoidsinkronsiapasnbkndisabled?>" id="<?=$infoidsinkronsiapasnbkn.$infoidriwayat?>" href="javascript:void(0)" title="update data SIAPASN ke BKN"><img src="images/icon-right.png"></a>
            <input type="hidden" id="<?=$infoidriwayat?>" value="<?=$infoidbkn?>">

            <?
            $infoidsinkronbknsiapasndisabled= $infoidsinkronbknsiapasn= "";
            if(empty($infoidbkn) || empty($tariksapk))
            {
              $infoidsinkronbknsiapasndisabled= "disabled";
            }
            else
            {
              $infoidsinkronbknsiapasn= "infoidsinkronbknsiapasn";
            }
            ?>
            <a class="<?=$infoidsinkronbknsiapasndisabled?>" href="javascript:void(0)" id="<?=$infoidsinkronbknsiapasn.$infoidbkn?>" title="update data BKN ke SIAPASN<?=$infoidbkn?>"><img src="images/icon-left.png"></a>
            <input type="hidden" id="<?=$infoidbkn?>" value="<?=$infoidriwayat?>">

            <?
            $inforesetidsapk= "";
            $inforesetidsapkdisabled= "disabled";
            if(!empty($infoidsapkriwayat) && !empty($syncsapk))
            {
              $inforesetidsapk= "resetsinkron";
              $inforesetidsapkdisabled= "";
            }
            ?>
            <a href="javascript:void(0)" id="<?=$inforesetidsapk.$infoidriwayat?>" class="<?=$inforesetidsapkdisabled?>" title="hapus sinkron data" ><img src="images/icon-del.png"></a>

            <?
            $infolinkdisabled= "disabled";
            if(!empty($vurldetil))
            {
              $infolinkdisabled= "";
            }
            ?>
            <a class="<?=$infolinkdisabled?>" href="app/loadUrl/app/<?=$vurldetil?>?reqId=<?=$reqId?>&reqRowId=<?=$infoidriwayat?>" title="ubah data"><img src="images/icon-pen.png"></a>
          </div>
        </div>

        <div class="clearfix"></div>

      </div>
      <?
      }
      ?>

    </div>
  </div>

  <script type="text/javascript" src="lib/materializetemplate/js/materialize.min.js"></script>
  <link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
  <script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

  <link href="lib/mbox/mbox.css" rel="stylesheet">
  <script src="lib/mbox/mbox.js"></script>
  <link href="lib/mbox/mbox-modif.css" rel="stylesheet">

  <script type="text/javascript">
    $('[id^="resetsinkron"]').click(function() {
      vinfoid= $(this).attr('id');
      vinfoid= vinfoid.replace("resetsinkron", "");
      
      info= "Apakah Anda Yakin, reset sinkron data terpilih ?";
      mbox.custom({
          message: info,
          options: {close_speed: 100},
          buttons: [
          {
            label: 'Ya',
            color: 'green darken-2',
            callback: function() {

              var s_url='bkn/diklat_json/reset_siapasn?reqRiwayatId='+vinfoid;
              $.ajax({'url': s_url, type: "get",'success': function(data){
                // console.log(data);return false;
                mbox.alert('Proses Data', {open_speed: 500}, interval = window.setInterval(function() 
                {
                  clearInterval(interval);
                  document.location.href= "app/loadUrl/app/pegawai_add_diklat_struktural_monitoring_bkn/?reqId=<?=$reqId?>";
                }, 1000));
                $(".mbox > .right-align").css({"display": "none"});
                
              }});
              mbox.close();
          }
          },
          {
            label: 'Tidak',
            color: 'grey darken-2',
            callback: function() {
              mbox.close();
            }
          }
          ]
        });

    });

    $('[id^="infoidsinkronsiapasnbkn"]').click(function() {
      vinfoid= $(this).attr('id');
      vinfoid= vinfoid.replace("infoidsinkronsiapasnbkn", "");
      var vinfoidbkn= $("#"+vinfoid).val();
      
      info= "Apakah Anda Yakin, update data terpilih SIAPASN ke BKN ?";
      mbox.custom({
          message: info,
          options: {close_speed: 100},
          buttons: [
          {
            label: 'Ya',
            color: 'green darken-2',
            callback: function() {

              var s_url='bkn/diklat_json/siapasn_bkn?reqRiwayatId='+vinfoid+"&reqBknId="+vinfoidbkn;
              $.ajax({'url': s_url, type: "get",'success': function(data){
                data= JSON.parse(data);
                // console.log(data.code);return false;
                if(data.code == 400)
                {
                  mbox.alert(data.PESAN);
                }
                else
                {
                  mbox.alert('Proses Data', {open_speed: 500}, interval = window.setInterval(function() 
                  {
                    clearInterval(interval);
                    document.location.href= "app/loadUrl/app/pegawai_add_diklat_struktural_monitoring_bkn/?reqId=<?=$reqId?>";
                  }, 1000));
                  $(".mbox > .right-align").css({"display": "none"});
                }
                
              }});
              mbox.close();
            }
          },
          {
            label: 'Tidak',
            color: 'grey darken-2',
            callback: function() {
              mbox.close();
            }
          }
          ]
        });

    });

    $('[id^="infoidsinkronbknsiapasn"]').click(function() {
      vinfoid= $(this).attr('id');
      vinfoid= vinfoid.replace("infoidsinkronbknsiapasn", "");
      var vinfoidriwayat= $("#"+vinfoid).val();

      info= "Apakah Anda Yakin, update data terpilih BKN ke SIAPASN ?";
      mbox.custom({
          message: info,
          options: {close_speed: 100},
          buttons: [
          {
            label: 'Ya',
            color: 'green darken-2',
            callback: function() {

              var s_url='bkn/diklat_json/bkn_siapasn?reqBknId='+vinfoid+"&reqRiwayatId="+vinfoidriwayat;
              $.ajax({'url': s_url, type: "get",'success': function(data){
                // console.log(data);return false;
                mbox.alert('Proses Data', {open_speed: 500}, interval = window.setInterval(function() 
                {
                  clearInterval(interval);
                  document.location.href= "app/loadUrl/app/pegawai_add_diklat_struktural_monitoring_bkn/?reqId=<?=$reqId?>";
                }, 1000));
                $(".mbox > .right-align").css({"display": "none"});
                
              }});
              mbox.close();
            }
          },
          {
            label: 'Tidak',
            color: 'grey darken-2',
            callback: function() {
              mbox.close();
            }
          }
          ]
        });

    });
  </script>

</body>
</html>