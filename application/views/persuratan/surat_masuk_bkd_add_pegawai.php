<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/SuratMasukBkd');

$reqId= $this->input->get("reqId");
$reqBreadCrum= $this->input->get("reqBreadCrum");
$reqJenis= $this->input->get("reqJenis");
$reqJenisNama= setjenisinfo($reqJenis);

$statement= " AND A.SURAT_MASUK_BKD_ID = ".$reqId."";
$set= new SuratMasukBkd();
$set->selectByParams(array(), -1, -1, $statement);
$set->firstRow();
// echo $set->query;exit();
$reqStatusKirim= $set->getField("STATUS_KIRIM");
$reqKategori= $set->getField("KATEGORI");
unset($set);
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

<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

<style type="text/css">
  .sm-text {
    font-size: 8pt;
  }

  .md-text {
    font-size: 11pt;
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
    font-weight: 400;
  }

  .material-bold {
    font-family: Roboto;
    font-weight: 500;
  }

  .round {
    border-radius: 50%;
    padding: 5px;
  }

  @media 
  only screen and (max-width: 760px),
  (min-device-width: 768px) and (max-device-width: 1024px)  {
    table.tabel-responsif thead th{ display:none; }
    /*
    Label the data
    */
    <?
    $arrData= array("Tahun", "Jenis Cuti", "No Surat", "Tgl. Surat", "Tgl. Permohonan", "Lama", "Tgl. Mulai", "Tgl. Selesai", "Keterangan", "Status", "Aksi");

    for($i=0; $i < count($arrData); $i++)
    {
      $index= $i+1;
    ?>
      table.tabel-responsif td:nth-of-type(<?=$index?>):before { content: "<?=$arrData[$i]?>"; }
    <?
    }
    ?>
  }
</style>

<script type="text/javascript">
  $(function(){
    setCariInfo();
  });

  function setCariInfo()
  {
    $('#reqDataPegawai').empty();
		$.ajax({'url': "app/loadUrl/persuratan/surat_masuk_bkd_add_pegawai_data/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>",'success': function(datahtml) {
			$('#reqDataPegawai').append(datahtml);
			parent.iframeLoaded();
			parent.reloadparenttab();
			//$("#reqTotalUser").text(data);
		}});
  }

  function hapusdata(id)
  {

    var s_url= "surat/surat_masuk_pegawai_json/cek_kirim_bkd?reqId=<?=$reqId?>";
    $.ajax({'url': s_url,'success': function(dataajax){
      var requrl= requrllist= "";
      dataajax= String(dataajax);
      if(dataajax == '1')
      {
        mbox.alert('Data sudah dikirim', {open_speed: 0});
      }
      else
      {
        mbox.custom({
           message: "Apakah Anda Yakin, Hapus data terpilih ?",
           options: {close_speed: 100},
           buttons: [
             {
               label: 'Ya',
               color: 'green darken-2',
               callback: function() {
                 $.getJSON("surat/surat_masuk_pegawai_json/delete_pegawai/?reqId="+id,
                function(data){
                  mbox.alert(data.PESAN, {open_speed: 500}, interval = window.setInterval(function() 
                  {
                    clearInterval(interval);
                    mbox.close();
                    document.location.href= "app/loadUrl/persuratan/surat_masuk_bkd_add_pegawai/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>";
                  }, 1000));
                  $(".mbox > .right-align").css({"display": "none"});
                  //$(".right-align").hide();
                });
                 //console.log('do action for yes answer');
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
      }
    }});

	}

  function hapusdatabidang(id)
  {
    var s_url= "surat/surat_masuk_pegawai_json/cek_kirim_bkd?reqId=<?=$reqId?>";
    $.ajax({'url': s_url,'success': function(dataajax){
      var requrl= requrllist= "";
      dataajax= String(dataajax);
      if(dataajax == '1')
      {
        mbox.alert('Data sudah dikirim', {open_speed: 0});
      }
      else
      {
        mbox.custom({
           message: "Apakah Anda Yakin, Hapus data terpilih ?",
           options: {close_speed: 100},
           buttons: [
             {
               label: 'Ya',
               color: 'green darken-2',
               callback: function() {
                 $.getJSON("surat/surat_masuk_bkd_json/delete_pegawai/?reqId="+id,
                  function(data){
                  mbox.alert(data.PESAN, {open_speed: 500}, interval = window.setInterval(function() 
                  {
                    clearInterval(interval);
                    mbox.close();
                    document.location.href= "app/loadUrl/persuratan/surat_masuk_bkd_add_pegawai/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>";
                    //setCariInfo();
                  }, 1000));
                  $(".mbox > .right-align").css({"display": "none"});
                  //$(".right-align").hide();
                  });
                 //console.log('do action for yes answer');
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
      }

    }});

  }

  function tambahdata()
  {
    var s_url= "surat/surat_masuk_pegawai_json/cek_kirim_bkd?reqId=<?=$reqId?>";
    $.ajax({'url': s_url,'success': function(dataajax){
      var requrl= requrllist= "";
      dataajax= String(dataajax);
      if(dataajax == '1')
      {
        mbox.alert('Data sudah dikirim', {open_speed: 0});
      }
      else
      {
        parent.setload('<?=seturllinkBkd($reqJenis)?>?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>');
      }
    }});
  }

  function tambahdataupt()
  {
    var s_url= "surat/surat_masuk_pegawai_json/cek_kirim_bkd?reqId=<?=$reqId?>";
    $.ajax({'url': s_url,'success': function(dataajax){
      var requrl= requrllist= "";
      dataajax= String(dataajax);
      if(dataajax == '1')
      {
        mbox.alert('Data sudah dikirim', {open_speed: 0});
      }
      else
      {
        parent.openModal('app/loadUrl/persuratan/pegawai_pilih_upt?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>&reqKategori=<?=$reqKategori?>');
      }
    }});
  
  }
</script>
  
<link href="lib/mbox/mbox.css" rel="stylesheet">
<script src="lib/mbox/mbox.js"></script>
<link href="lib/mbox/mbox-modif.css" rel="stylesheet">
</head>

<body>
 <div id="basic-form" class="section">
  <div class="row">
    <div class="col s12 m12" style="padding-left: 15px;">
      <ul class="collection card">
        <li class="collection-item ubah-color-warna">List Pegawai Usulan Pelayanan <?=$reqJenisNama?></li>
        <li class="collection-item">
        <?
        if($reqStatusKirim == "1"){}
        else
        {
        ?>
          <div class="row">
          	<div class="col s12 m2">
            <a style="cursor:pointer" onClick="tambahdata()"> <i class="mdi-content-add-circle-outline"> Tambah</i></a>
            </div>
            <div class="col s12 m3">
            <a style="cursor:pointer" onClick="tambahdataupt()"> <i class="mdi-content-add-circle-outline"> Tambah Dari data Upt</i></a>
            </div>
          </div>
        <?
		    }
        ?>
          <table class="bordered striped md-text table_list tabel-responsif" id="link-table">
            <?
            $arrjenis= [3,4,7,10];
            if(in_array($reqJenis, $arrjenis))
            {
            ?>
            <thead> 
              <tr>
                <th rowspan="2" class="green-text material-font" style="text-align:center">NIP Baru</th>
                <th rowspan="2" class="green-text material-font" style="text-align:center">Nama</th>
                <th rowspan="2" class="green-text material-font" style="text-align:center">Unit Kerja</th>
                <th rowspan="2" class="green-text material-font" style="text-align:center">No Usul ke BKDPP</th>
                <th colspan="3" class="green-text material-font" style="text-align:center">Status</th>
                <th rowspan="2" class="green-text material-font">Aksi</th>
              </tr>
              <tr>
                <th class="green-text material-font" style="text-align:center">E-File</th>
                <th class="green-text material-font" style="text-align:center">Tanggal</th>
                <th class="green-text material-font" style="text-align:center">Proses</th>
              </tr>
            </thead>
            <?
            }
            else
            {
            ?>
            <thead> 
              <tr>
                <th rowspan="2" class="green-text material-font" style="text-align:center">NIP Baru</th>
                <th rowspan="2" class="green-text material-font" style="text-align:center">Nama</th>
                <th rowspan="2" class="green-text material-font" style="text-align:center">Unit Kerja</th>
                <th rowspan="2" class="green-text material-font" style="text-align:center">No Usul ke BKDPP</th>
                <th colspan="2" class="green-text material-font" style="text-align:center">Status</th>
                <th rowspan="2" class="green-text material-font">Aksi</th>
              </tr>
              <tr>
                <th class="green-text material-font" style="text-align:center">Tanggal</th>
                <th class="green-text material-font" style="text-align:center">Proses</th>
              </tr>
            </thead>
            <?
            }
            ?>
            <tbody id="reqDataPegawai"></tbody>
           </table>
        </li>
      </ul>

      <button class="btn red waves-effect waves-light" style="font-size:9pt" type="button" id="tambah" onClick="parent.closeparenttab()">Close
        <i class="mdi-navigation-close left hide-on-small-only"></i>
      </button>

     </div>
   </div>
 </div>
 <!-- jQuery Library -->
 <!-- <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script> -->

 <!--materialize js-->
 <script type="text/javascript" src="lib/materializetemplate/js/materialize.min.js"></script>
 <link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
 <script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>