<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");
include_once("functions/talent.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$tempUserLoginId= $this->USER_LOGIN_ID;

if($tempUserLoginId == "1" || $tempUserLoginId == "411" || $tempUserLoginId == "359" || $tempUserLoginId == "376"){}
else
{
  redirect('app/index');
  exit;
}

$reqBreadCrum= $this->input->get("reqBreadCrum");
$reqStatusPegawaiId= $this->input->get("reqStatusPegawaiId");
$reqEselonGroupId= $this->input->get("reqEselonGroupId");
$reqTahun= $this->input->get("reqTahun");
$reqTipePegawaiId= $this->input->get("reqTipePegawaiId");
$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
$reqSatuanKerjaNama= $this->input->get("reqSatuanKerjaNama");

$kondisi= kondisifield();

$this->load->model('talent/Rekap');
$this->load->model('SatuanKerja');

if(!empty($reqSatuanKerjaId))
{
  $statementAktif= " AND EXISTS(
    SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE)
    AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
  )";

  $skerja= new SatuanKerja();
  $reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
  unset($skerja);
  // echo $reqSatuanKerjaId;exit;
  $statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
}

$arrtahun= array();
$index= 0;
$set= new Rekap();
$set->selectByParamsTahun();
// echo $set->query;exit;
while($set->nextRow())
{
  $arrtahun[$index]["TAHUN"]= $set->getField("TAHUN");
  $index++;
}
$jumlahtahun= $index;
// print_r($arrtahun);exit;

$reqTahun= date("Y");
// if(empty($reqTahun) && $jumlahtahun > 0)
// {
//   $reqTahun= $arrtahun[0]["TAHUN"];
// }

if(!empty($reqEselonGroupId))
  $statement.= " AND ESELON_GROUP_ID = '".$reqEselonGroupId."'";

if(!empty($reqTipePegawaiId))
  $statement.= " AND TIPE_PEGAWAI_ID LIKE '".$reqTipePegawaiId."%'";

$arrdata= array();
$index= 0;
$set= new Rekap();
$set->selectByParamsRekapJumlahKuadran($reqTahun, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
  $arrdata[$index]["GROUP_AREA"]= $set->getField("GROUP_AREA");
  $arrdata[$index]["JUMLAH_DATA"]= $set->getField("JUMLAH_DATA");
  $arrdata[$index]["KETERANGAN"]= $kondisi[$index]["keterangan"];
  $index++;
}
// print_r($arrdata);exit;

$arrstatus= array(
    array("info"=>"Semua", "value"=>"")
    , array("info"=>"CPNS/PNS", "value"=>"12")
    , array("info"=>"CPNS", "value"=>"1")
    , array("info"=>"PNS", "value"=>"2")
);

$arrtipepegawai= array(
    array("info"=>"Semua", "value"=>"")
    , array("info"=>"Struktural", "value"=>"11")
    , array("info"=>"Pelaksana", "value"=>"12")
    , array("info"=>"JFT", "value"=>"2")
);
  
$arreselon= array(
    array("info"=>"Semua", "value"=>"")
    , array("info"=>"II", "value"=>"2")
    , array("info"=>"III", "value"=>"3")
    , array("info"=>"IV", "value"=>"4")
);
// print_r($arrData);exit;

$tinggi = 156;
// $tinggi = 580;

if(empty($reqSatuanKerjaNama))
  $reqSatuanKerjaNama= "Semua Satuan Kerja";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
  <title>SIDAK - Sistem Informasi Data Analisis Kepegawaian</title>
  <base href="<?=base_url()?>" />
  <link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico">
  <!--<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.datatables.net/rss.xml">-->

  <style type="text/css" media="screen">
    @import "lib/media/css/site_jui.css";
    @import "lib/media/css/demo_table_jui.css";
    @import "lib/media/css/themes/base/jquery-ui.css";

    .hukumanstyle { background-color:#FC7370; }
    .hukumanpernahstyle { background-color:#F9C; }
  </style>

  <link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/jquery.dataTables.css">
  <link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/extensions/Responsive/css/dataTables.responsive.css">
  <link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/examples/resources/syntax/shCore.css">
  <link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/examples/resources/demo.css">
  <style type="text/css" class="init">

    div.container { max-width: 100%;}

  </style>
  <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/media/js/jquery.js"></script>

    <?php /*?><link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
  <script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
  <script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script><?php */?>

  <link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
  <link rel="stylesheet" type="text/css" href="lib/easyui/themes/icon.css">
  <link rel="stylesheet" type="text/css" href="lib/easyui/demo/demo.css">

  <script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.min.js"></script>
  <script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.easyui.min.js"></script>

  <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/media/js/jquery.dataTables.js"></script>
  <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/extensions/Responsive/js/dataTables.responsive.js"></script>
  <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/syntax/shCore.js"></script>
  <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/demo.js"></script>

<script type="text/javascript" src="lib/easyui/breadcrum.js"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready( function () {
  <?
  if($reqBreadCrum == ""){}
  else
  {
  ?>
    setinfobreacrum("<?=$reqBreadCrum?>", "setBreacrum");
  <?
  }
  ?>

  $("#btnCari").on("click", function () {
      var reqSatuanKerjaId= reqSatuanKerjaNama= reqStatusPegawaiId= reqEselonGroupId= reqTahun= reqTipePegawaiId= reqCariFilter= "";
      reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
      reqSatuanKerjaNama= $("#reqLabelSatuanKerjaNama").text();
      // reqCariFilter= $("#reqCariFilter").val();
      reqStatusPegawaiId= $("#reqStatusPegawaiId").val();
      reqEselonGroupId= $("#reqEselonGroupId").val();
      reqTahun= $("#reqTahun").val();
      reqTipePegawaiId= $("#reqTipePegawaiId").val();

      document.location.href="kinerja/index?reqSatuanKerjaId="+reqSatuanKerjaId+"&reqStatusPegawaiId="+reqStatusPegawaiId+"&reqEselonGroupId="+reqEselonGroupId+"&reqTahun="+reqTahun+"&reqTipePegawaiId="+reqTipePegawaiId+"&reqSatuanKerjaNama="+reqSatuanKerjaNama;
    });

    $("#reqStatusPegawaiId, #reqEselonGroupId, #reqTahun, #reqTipePegawaiId").change(function() { 
      setCariInfo();
    });

});

var tempinfodetilpencarian="0";
function showIconCari()
{
  if(tempinfodetilpencarian == "0")
  {
    $("#tabpencarian").show();
    tempinfodetilpencarian= 1;
  }
  else
  {
    $("#tabpencarian").hide();
    tempinfodetilpencarian= 0;
  }
}

function setCariInfo()
{
  $(document).ready( function () {
    $("#btnCari").click();
  });
}

function calltreeid(id, nama)
{
  $("#reqLabelSatuanKerjaNama").text(nama);
  $("#reqSatuanKerjaId").val(id);
  setCariInfo();
}
</script>

<link href="css/bluetabs.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/dropdowntabs.js"></script>

<!-- CORE CSS-->
<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<!-- CSS style Horizontal Nav-->
<link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
<!-- Custome CSS-->
<link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/dataTables.materialize.css">
<?php /*?><link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/dataTables.material.min.css"><?php */?>

<link rel="stylesheet" type="text/css" href="css/gaya-monitoring.css">

<link href="lib/treeTable2/doc/stylesheets/master.css" rel="stylesheet" type="text/css" />
<link href="lib/treeTable2/src/stylesheets/jquery.treeTable.css" rel="stylesheet" type="text/css" />

<!--<link href="css/normalize.css" rel="stylesheet" type="text/css" />-->

<style type="text/css">
  table{
        table-layout:fixed;
        width: 100%;
    }

  table.dataTable tbody td th{
    vertical-align: top;
    white-space: nowrap;
  }

  @media only screen and (max-width: 767px) {
    table{
          table-layout:auto !important;
      }
  }
</style>

<link href="css/kuadran.css" rel="stylesheet" type="text/css" />
<link href="lib/font-awesome-4.7.0/css/font-awesome.css" rel="stylesheet" type="text/css" />

<style>
.fancybox-wrap.fancybox-desktop.fancybox-type-iframe.fancybox-opened{
  width:  80% !important;
}
.fancybox-wrap.fancybox-desktop.fancybox-type-iframe.fancybox-opened .fancybox-inner{
  width: 100% !important;
}

/****/
.btn{
  margin-left: 10px;
}
.btn-danger {
    color: #fff !important;
    background-color: #d9534f;
    border-color: #d43f3a;
}
.btn-primary {
    color: #fff !important;
    background-color: #337ab7;
    border-color: #2e6da4;
}
.btn a{
  color: #fff !important;
}
</style>

</head>
<body>
  <!-- START MAIN -->
  <div id="main">
    <!-- START WRAPPER -->
    <div class="wrapper">
      <!-- START CONTENT -->
      <section id="content-full">

        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">

                <ol class="breadcrumb right" id="setBreacrum"></ol>
                <h5 class="breadcrumbs-title">Pegawai
                  <span class="pull-right btn btn-danger"><a href="app/logout"><i class="fa fa-sign-out"></i></a></span>
                  <span class="pull-right btn btn-primary"><a href="codeportal/index"><i class="fa fa-home"></i></a></span>
                </h5>
                <ol class="breadcrumbs">
                  <li class="active">
                    <input type="hidden" id="reqSatuanKerjaId" value="<?=$reqSatuanKerjaId?>" />
                    <label id="reqLabelSatuanKerjaNama"><?=$reqSatuanKerjaNama?></label>
                  </li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--breadcrumbs end-->

        <div class="area-parameter">
          <div class="kiri">
            <!--<span>Show</span>-->
            <select>
              <option>10</option>
              <option>25</option>
              <option>50</option>
            </select>
            <span>data</span>
          </div>

          <div class="kanan">
            <a href="#" id="btnCari" style="display:none" title="Cari">Cari</a>
            <span>Cari :</span>
            <input type="text" id="reqCariFilter" />
            <button id="clicktoggle">Filter ▾</button>
          </div>
        </div>

        <div class="area-parameter no-marginbottom">

          <div id="settoggle">
            <div class="row">
              <div class="col s3 m1">Status</div>
              <div class="col s2 m1 select-semicolon">:</div>
              <div class="col s3 m2">
                <select class="option-vw9" id="reqStatusPegawaiId">
                  <?
                  for($i=0; $i < count($arrstatus); $i++)
                  {
                    $infoid= $arrstatus[$i]["value"];
                    $infotext= $arrstatus[$i]["info"];
                  ?>
                    <option value="<?=$infoid?>" <? if($infoid == $reqStatusPegawaiId) echo "selected";?> ><?=$infotext?></option>
                  <?
                  }
                  ?>
                  <!-- <option value="" selected>Semua</option>
                  <option value="12">CPNS/PNS</option>
                  <option value="1">CPNS</option>
                  <option value="2">PNS</option>
                  <option value="3">Pensiun</option>
                  <option value="spk21">Pensiun BUP</option>
                  <option value="spk24">Pensiun Wafat</option>
                  <option value="spk25">Pensiun Tewas</option>
                  <option value="spk27">Pemberhentian Dengan Tidak Hormat</option>
                  <option value="spk28">Mutasi Keluar/Pindah Atas Permintaan Sendiri</option>
                  <option value="hk">Hukuman</option>
                  <option value="pk">Pernah Kena Hukuman</option> -->
                </select>
              </div>

              <input type="hidden" id="reqTahun" value="<?=$reqTahun?>" />
              <div class="col s3 m2">Tipe Pegawai</div>
              <div class="col s2 m1 select-semicolon">:</div>
              <div class="col s3 m2">
                <select class="option-vw9" id="reqTipePegawaiId">
                  <?
                  for($i=0; $i < count($arrtipepegawai); $i++)
                  {
                    $infoid= $arrtipepegawai[$i]["value"];
                    $infotext= $arrtipepegawai[$i]["info"];
                  ?>
                    <option value="<?=$infoid?>" <? if($infoid == $reqTipePegawaiId) echo "selected";?> ><?=$infotext?></option>
                  <?
                  }
                  ?>
                </select>
              </div>
              

              <div class="col s3 m1">Eselon</div>
              <div class="col s2 m1 select-semicolon">:</div>
              <div class="col s3 m3">
                <select class="option-vw3" id="reqEselonGroupId">
                  <?
                  for($i=0; $i < count($arreselon); $i++)
                  {
                    $infoid= $arreselon[$i]["value"];
                    $infotext= $arreselon[$i]["info"];
                  ?>
                    <option value="<?=$infoid?>" <? if($infoid == $reqEselonGroupId) echo "selected";?> ><?=$infotext?></option>
                  <?
                  }
                  ?>
                </select>
              </div>

              <div class="col s12">
                <table id="tt" class="easyui-treegrid" style="width:100%; height:250px">
                  <thead>
                    <tr>
                      <th field="NAMA" width="90%">Nama</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>

        </div>

        <div class="container" style="clear:both;">
          <div class="section">

            <div class="area-kuadran-wrapper">
              <div class="area-kuadran">
                <div class="inner">
                  <div class="item"><a class="fancybox fancybox.iframe" href="kinerja/loadUrl/kinerja/info_detil?reqKuadran=<?=$arrdata[3]["GROUP_AREA"]?>&reqTahun=<?=$reqTahun?>&reqTipePegawaiId=<?=$reqTipePegawaiId?>&reqEselonGroupId=<?=$reqEselonGroupId?>&reqSatuanKerjaId=<?=$reqSatuanKerjaId?>"><span class="nomor">IV</span><br><span class="pns"><?=$arrdata[3]["JUMLAH_DATA"]?> pns</span></a></div>
                  <div class="item"><a class="fancybox fancybox.iframe" href="kinerja/loadUrl/kinerja/info_detil?reqKuadran=<?=$arrdata[6]["GROUP_AREA"]?>&reqTahun=<?=$reqTahun?>&reqTipePegawaiId=<?=$reqTipePegawaiId?>&reqEselonGroupId=<?=$reqEselonGroupId?>&reqSatuanKerjaId=<?=$reqSatuanKerjaId?>"><span class="nomor">VII</span><br><span class="pns"><?=$arrdata[6]["JUMLAH_DATA"]?> pns</span></a></div>
                  <div class="item"><a class="fancybox fancybox.iframe" href="kinerja/loadUrl/kinerja/info_detil?reqKuadran=<?=$arrdata[8]["GROUP_AREA"]?>&reqTahun=<?=$reqTahun?>&reqTipePegawaiId=<?=$reqTipePegawaiId?>&reqEselonGroupId=<?=$reqEselonGroupId?>&reqSatuanKerjaId=<?=$reqSatuanKerjaId?>"><span class="nomor">IX</span><br><span class="pns"><?=$arrdata[8]["JUMLAH_DATA"]?> pns</span></a></div>
                  <div class="item"><a class="fancybox fancybox.iframe" href="kinerja/loadUrl/kinerja/info_detil?reqKuadran=<?=$arrdata[1]["GROUP_AREA"]?>&reqTahun=<?=$reqTahun?>&reqTipePegawaiId=<?=$reqTipePegawaiId?>&reqEselonGroupId=<?=$reqEselonGroupId?>&reqSatuanKerjaId=<?=$reqSatuanKerjaId?>"><span class="nomor">II</span><br><span class="pns"><?=$arrdata[1]["JUMLAH_DATA"]?> pns</span></a></div>
                  <div class="item"><a class="fancybox fancybox.iframe" href="kinerja/loadUrl/kinerja/info_detil?reqKuadran=<?=$arrdata[4]["GROUP_AREA"]?>&reqTahun=<?=$reqTahun?>&reqTipePegawaiId=<?=$reqTipePegawaiId?>&reqEselonGroupId=<?=$reqEselonGroupId?>&reqSatuanKerjaId=<?=$reqSatuanKerjaId?>"><span class="nomor">V</span><br><span class="pns"><?=$arrdata[4]["JUMLAH_DATA"]?> pns</span></a></div>
                  <div class="item"><a class="fancybox fancybox.iframe" href="kinerja/loadUrl/kinerja/info_detil?reqKuadran=<?=$arrdata[7]["GROUP_AREA"]?>&reqTahun=<?=$reqTahun?>&reqTipePegawaiId=<?=$reqTipePegawaiId?>&reqEselonGroupId=<?=$reqEselonGroupId?>&reqSatuanKerjaId=<?=$reqSatuanKerjaId?>"><span class="nomor">VIII</span><br><span class="pns"><?=$arrdata[7]["JUMLAH_DATA"]?> pns</span></a></div>
                  <div class="item"><a class="fancybox fancybox.iframe" href="kinerja/loadUrl/kinerja/info_detil?reqKuadran=<?=$arrdata[0]["GROUP_AREA"]?>&reqTahun=<?=$reqTahun?>&reqTipePegawaiId=<?=$reqTipePegawaiId?>&reqEselonGroupId=<?=$reqEselonGroupId?>&reqSatuanKerjaId=<?=$reqSatuanKerjaId?>"><span class="nomor">I</span><br><span class="pns"><?=$arrdata[0]["JUMLAH_DATA"]?> pns</span></a></div>
                  <div class="item"><a class="fancybox fancybox.iframe" href="kinerja/loadUrl/kinerja/info_detil?reqKuadran=<?=$arrdata[2]["GROUP_AREA"]?>&reqTahun=<?=$reqTahun?>&reqTipePegawaiId=<?=$reqTipePegawaiId?>&reqEselonGroupId=<?=$reqEselonGroupId?>&reqSatuanKerjaId=<?=$reqSatuanKerjaId?>"><span class="nomor">III</span><br><span class="pns"><?=$arrdata[2]["JUMLAH_DATA"]?> pns</span></a></div>
                  <div class="item"><a class="fancybox fancybox.iframe" href="kinerja/loadUrl/kinerja/info_detil?reqKuadran=<?=$arrdata[5]["GROUP_AREA"]?>&reqTahun=<?=$reqTahun?>&reqTipePegawaiId=<?=$reqTipePegawaiId?>&reqEselonGroupId=<?=$reqEselonGroupId?>&reqSatuanKerjaId=<?=$reqSatuanKerjaId?>"><span class="nomor">VI</span><br><span class="pns"><?=$arrdata[5]["JUMLAH_DATA"]?> pns</span></a></div>
                  <div class="clearfix"></div>
                </div>
              </div>
              <div class="area-kuadran-legend">
                <table>
                  <thead>
                    <tr>
                      <th>KUADRAN</th>
                      <th>JUMLAH</th>
                      <th>KETERANGAN</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?
                    for($idata=0; $idata < 9; $idata++)
                    {
                      $nomor= $idata+1;
                    ?>
                    <tr>
                      <td><?=romanic_number($nomor)?></td>
                      <td><a class="fancybox fancybox.iframe" href="kinerja/loadUrl/kinerja/info_detil?reqKuadran=<?=$nomor?>&reqTahun=<?=$reqTahun?>&reqTipePegawaiId=<?=$reqTipePegawaiId?>&reqEselonGroupId=<?=$reqEselonGroupId?>&reqSatuanKerjaId=<?=$reqSatuanKerjaId?>"><?=$arrdata[$idata]["JUMLAH_DATA"]?> orang</a></td>
                      <td><?=$arrdata[$idata]["KETERANGAN"]?></td>
                    </tr>
                    <?
                    }
                    ?>
                  </tbody>
                </table>
              </div>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>

      </section>
      <!-- END CONTENT -->
    </div>
    <!-- END WRAPPER -->

  </div>
  <!-- END MAIN -->

  <!--materialize js-->
  <script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('select').material_select();
    });

    $('.materialize-textarea').trigger('autoresize');

    $(function(){
      var tt = $('#tt').treegrid({
        url: 'satuan_kerja_json/treepilih',
        rownumbers: false,
        pagination: false,
        idField: 'ID',
        treeField: 'NAMA',
        onBeforeLoad: function(row,param){
          if (!row) { // load top level rows
          param.id = 0; // set id=0, indicate to load new page rows
          }
        }
      });
    });

    var outer = document.getElementById('settoggle');
    document.getElementById('clicktoggle').addEventListener('click', function(evnt) {
    if (outer.style.maxHeight){
        //alert('a');
        outer.style.maxHeight = null;
        outer.classList.add('settoggle-closed');
      }
      else {
        //alert('b');
        outer.style.maxHeight = outer.scrollHeight + 'px';
        outer.classList.remove('settoggle-closed');
      }
    });

    outer.style.maxHeight = outer.scrollHeight + 'px';
    $('#clicktoggle').trigger('click');
  </script>

  <style>
    .option-vw3 {
      width: 30% !important;
    }

    .option-vw9 {
      /*width: 35vw !important;*/
      width: 100% !important;
    }
    .dropdown-content
    {
      max-height: 250px !important;
    }

    .dropdown-content li
    {
      min-height: 15px !important;
      line-height: 0.1rem !important;
    }
    .dropdown-content li > span
    {
      font-size: 14px;
      line-height: 12px !important;
    }
  </style>

  <!-- Add fancyBox main JS and CSS files -->
  <script type="text/javascript" src="lib/fancybox-2.1.7/source/jquery.fancybox.pack.js?v=2.1.5"></script>
  <link rel="stylesheet" type="text/css" href="lib/fancybox-2.1.7/source/jquery.fancybox.css?v=2.1.5" media="screen" />

  <script type="text/javascript">
    $(document).ready(function() {
      $('.fancybox').fancybox();
    });
  </script>
</body>
</html>