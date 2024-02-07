<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/SuratMasukUpt');

$reqId= $this->input->get("reqId");
$reqBreadCrum= $this->input->get("reqBreadCrum");
$reqJenis= $this->input->get("reqJenis");
$reqJenisNama= setjenisinfo($reqJenis);

$statement= " AND A.SURAT_MASUK_UPT_ID = ".$reqId."";
$set= new SuratMasukUpt();
$set->selectByParams(array(), -1, -1, $statement);
$set->firstRow();
$reqStatusKirim= $set->getField("STATUS_KIRIM");
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
    }  ?>
  }
</style>

<script type="text/javascript">
	$(function(){
	setCariInfo();
});

function setCariInfo()
{
	$('#reqDataPegawai').empty();
	$.ajax({'url': "app/loadUrl/persuratan/surat_masuk_dinas_upt_add_pegawai_data/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>",'success': function(datahtml) {
		$('#reqDataPegawai').append(datahtml);
		parent.iframeLoaded();
		//$("#reqTotalUser").text(data);
	}});
}

function hapusdata(id)
{
	$.messager.confirm('Konfirmasi',"Hapus data terpilih?",function(r){
		if (r){
			$.getJSON("surat/surat_masuk_pegawai_json/delete_pegawai/?reqId="+id,
				function(data){
					$.messager.alert('Info', data.PESAN, 'info');
					setCariInfo();
				});

		}
	});
}
</script>
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
              	<a style="cursor:pointer" onClick="parent.setload('surat_masuk_upt_add_pegawai_pendidikan_data?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>')"> <i class="mdi-content-add-circle-outline"> Tambah</i></a>
              </div>
            <?
    		    }
            ?>
            <table class="bordered striped md-text table_list tabel-responsif" id="link-table">
              <?
              $arrjenis= [3,4,7];
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
                  <?
                  // kalau data sudah terbaca dan terkirim maka muncul cek kelengkapan
                  if($reqStatusKirim == "1")
                  {
                  ?>
                  <th rowspan="2" class="green-text material-font">Aksi</th>
                  <?
                  }
                  ?>
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
      </div>
    </div>
  </div>

<script type="text/javascript" src="lib/materializetemplate/js/materialize.min.js"></script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>