<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");
$this->load->model('SatuanKerja');

$CI =& get_instance();
$CI->checkUserLogin();

$tinggi = 156;
// $tinggi = 580;
$reqSatuanKerjaNama= "Semua Satuan Kerja";

$infouserloginid= $this->USER_LOGIN_ID;
$reqInfoSipeta= $this->INFO_SIPETA;
// echo $reqInfoSipeta;exit;

$infomenid= "3405";
$infoaksesmenu= $CI->checkmenupegawai($infouserloginid, $infomenid);
// echo $infoaksesmenu;exit;

$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
$reqEselonGroupId= $this->input->get("reqEselonGroupId");
$reqTipePegawaiId= $this->input->get("reqTipePegawaiId");
if($reqSatuanKerjaId == "")
{
  if($reqInfoSipeta == "sipeta_all"){}
  else
  {
    $tempSatuanKerjaId= $reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
  }
}

$this->load->library('globalrekappegawai');
$vfpeg= new globalrekappegawai();

if(!empty($reqSatuanKerjaId))
{
  $arrparam= array("satuankerjaid"=>$reqSatuanKerjaId);
  $reqSatuanKerjaNama= $vfpeg->getsatuankerjanama($arrparam);
}

$kotakpetatalentaketerangan= $vfpeg->kotakpetatalentaketerangan();

// $getperumpunan= $vfpeg->getperumpunan();
// $getperumpunan= $vfpeg->getglobalperumpunan("nilai_akhir");
// $inforumpun= $vfpeg->inforumpun();
// print_r($getperumpunan);exit;

$arrparam= array("satuankerjaid"=>$reqSatuanKerjaId, "tipepegawaiid"=>$reqTipePegawaiId, "statuspegawaiid"=>$reqStatusPegawaiId, "eselongroupid"=>$reqEselonGroupId);
$statement= $vfpeg->getparampegawai($arrparam);

$this->load->model("talent/RekapTalent2023");

$set= new RekapTalent2023();
$set->selectbyparamsformulapenilaiannineboxstandart(array(), -1,-1, " AND A.PERMEN_ID = 2");
$set->firstRow();
// echo $set->query;exit;
$reqSkpX0= $set->getField("SKP_X0");
$reqSkpY0= $set->getField("SKP_Y0");
$reqGmX0= $set->getField("GM_X0");
$reqGmY0= $set->getField("GM_Y0");
$reqSkpX1= $set->getField("SKP_X1");
$reqSkpY1= $reqSkpX0+1;
$reqGmX1= $set->getField("GM_X1");
$reqGmY1= $set->getField("GM_Y1");
$reqSkpX2= $set->getField("SKP_X2");
$reqSkpY2= $reqSkpX1+1;
$reqGmX2= $set->getField("GM_X2");
$reqGmY2= $set->getField("GM_Y2");

if($reqSkpY0 == "") $reqSkpY0= 0;
if($reqGmX0 == "") $reqGmX0= 0;
if($reqSkpY1 == "") $reqSkpY1= 0;
if($reqGmX1 == "") $reqGmX1= 0;
if($reqSkpY2 == "") $reqSkpY2= 0;
if($reqGmX2 == "") $reqGmX2= 0;

$statement= $statementdetil= "";
if(!empty($reqSatuanKerjaId))
{
  $skerja= new SatuanKerja();
  $reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
  unset($skerja);
}
else
{
  /*$vSatuanKerjaId= "";
  $skerja= new SatuanKerja();
  $vSatuanKerjaId.= $skerja->getSatuanKerja(62);
  unset($skerja);

  $skerja= new SatuanKerja();
  $vSatuanKerjaId.= $skerja->getSatuanKerja(66);
  unset($skerja);

  $skerja= new SatuanKerja();
  $vSatuanKerjaId.= $skerja->getSatuanKerja(76);
  unset($skerja);

  $reqSatuanKerjaId= $vSatuanKerjaId;*/
}

if(!empty($reqSatuanKerjaId))
{
  $statementdetil.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
}

// $statementdetil.= " AND A.PEGAWAI_ID = 8300";
// $statementdetil.= " AND A.PEGAWAI_ID = 11761";
$arrdata= [];
$set= new RekapTalent2023();
$set->selectbykuadranpegawai2023(array(), -1, -1, $statementdetil);
// echo $set->query;exit;
while($set->nextRow())
{
  $kotakrumpun= $set->getField("ORDER_KUADRAN");
  $arrdata[$kotakrumpun]["JUMLAH_DATA"]= $set->getField("JUMLAH");
}
// print_r($arrdata);exit;

for($x=1; $x < 10; $x++)
{
  if(empty($arrdata[$x]["JUMLAH_DATA"]))
    $arrdata[$x]["JUMLAH_DATA"]= 0;

  $arrdata[$x]["KETERANGAN"]= $kotakpetatalentaketerangan[$x-1]["nama"];
}
// print_r($arrdata);exit;

$infodata= [];
$infodata[0]["INDEX"]= "";
$infodata[0]["NAMA"]= "";
$infodata[0]["JUMLAH_DATA"]= "";
for($x=1; $x < 10; $x++)
{
  $infoindex= $x;
  if($x == 1)
    $infoindex= 4;
  if($x == 2)
    $infoindex= 7;
  if($x == 3)
    $infoindex= 9;
  if($x == 4)
    $infoindex= 2;
  if($x == 6)
    $infoindex= 8;
  if($x == 7)
    $infoindex= 1;
  if($x == 8)
    $infoindex= 3;
  if($x == 9)
    $infoindex= 6;
  
  $vkodekuadran= romanic_number($infoindex);
  $infodata[$x]["INDEX"]= $infoindex;
  $infodata[$x]["NAMA"]= $vkodekuadran;
  $infodata[$x]["JUMLAH_DATA"]= coalesce($arrdata[$infoindex]["JUMLAH_DATA"],0);
}
// print_r($infodata);exit;

$arrstatus= $vfpeg->pilihstatus();
$arrtipepegawai= $vfpeg->pilihtipepegawai();
$arreselon= $vfpeg->piliheselon();
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/gaya-monitoring.css">
<style type="text/css">
  .dataTables_scrollBody{
    overflow-x: scroll !important;
  }
  div.dataTables_wrapper div.dataTables_paginate{
    margin-right: 50px;
  }
</style>
<style type="text/css">
  table.grid{
    table-layout:fixed;
    width: 100%;
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
</html>
<base href="<?=base_url()?>" />

<div class="app-page-title">
  <div class="container fiori-container">
    <div class="page-title-wrapper">
      <div class="page-title-heading">
        <div>
          Peta<div class="page-title-subheading">List Data Peta</div>
          <label id="reqLabelSatuanKerjaNama"><?=$reqSatuanKerjaNama?></label>
          <input type="hidden" id="reqSatuanKerjaId" value="<?=$reqSatuanKerjaId?>" />
        </div>
      </div>
      <div class="page-title-actions">
        <div class="d-inline-block dropdown">          
        </div>
      </div>
    </div>
  </div>
</div>
<div class="app-inner-layout app-inner-layout-page">
  <div class="app-inner-layout__wrapper">
    <div class="app-inner-layout__content">
      <div class="tab-content">
        <div class="container fiori-container">
          <div class="main-card mb-3 card">
            <div class="d-block clearfix card-footer">
              <div class="float-left" style="display:none">
                <a href="#" id="btnCari" style="display:none" title="Cari">Cari</a>
                <input type="text" id="reqCariFilter" class="form-control" placeholder="Pencarian"/>
              </div>
              <div class="float-right">
                <?
                if($reqInfoSipeta == "sipeta_all")
                {
                ?>
                <button class="btn-wide btn-shadow btn btn-primary btn-sm" id="btnhitungulang" title="Hitung Ulang"> Hitung Ulang</button>
                <?
                }
                ?>
                <button class="btn-wide btn-shadow btn btn-primary btn-sm" id="clicktoggle" >Show Filter Tree</button>
              </div>
            </div>
            <div class="d-block clearfix card-footer">
              <div id="settoggle">
                <div class="row" style="display: none;">
                  <div class="input-field col s12 m2" style="text-align: center; padding-top: 5px;">Status</div>
                  <div class="input-field col s12 m4">
                    <select name="select" id="reqStatusPegawaiId" class="form-control">
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
                    </select>
                  </div>
                  <div class="input-field col s12 m2" style="text-align: center; padding-top: 5px;">Tipe Pegawai</div>
                  <div class="input-field col s12 m4">
                    <select name="select" id="reqTipePegawaiId" class="form-control">
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
                  <div class="input-field col s12 m2" style="text-align: center; padding-top: 5px;">Eselon</div>
                  <div class="input-field col s12 m4">
                    <select name="select" id="reqEselonGroupId" class="form-control">
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
                </div>

                <!-- <div class="row"> -->
                  <table id="tt" class="easyui-treegrid" style="width:100%; height:250px">
                    <thead>
                      <tr>
                        <th field="NAMA" width="90%">Nama</th>
                      </tr>
                    </thead>
                  </table>
                <!-- </div> -->

              </div>
            </div>
            <br>

            <button type="button" id="areaidgrafik" class="btn btn-light-primary font-weight-bolder">
              <span class="svg-icon svg-icon-md"></span>Tutup Chart
            </button>

            <div class="table-responsive">
                <div class="container" style="clear:both;">
                  <div class="section">

                    <div class="area-kuadran-wrapper">
                      <div class="area-kuadran">
                        <div class="inner" style="background: inherit !important;">

                          <div id="kontenidgrafik" style="width:100%; height: calc(60vh - 10px)"> </div>

                          <input type="hidden" id="reqInfoSkpY0" value="<?=$reqSkpY0?>" />
                          <input type="hidden" id="reqInfoSkpX0" value="<?=$reqSkpX0?>" />
                          <input type="hidden" id="reqInfoGmX0" value="<?=$reqGmX0?>" />
                          <input type="hidden" id="reqInfoGmY0" value="<?=$reqGmY0?>" />
                          <input type="hidden" id="reqInfoSkpY1" value="<?=$reqSkpY1?>" />
                          <input type="hidden" id="reqInfoSkpX1" value="<?=$reqSkpX1?>" />
                          <input type="hidden" id="reqInfoGmX1" value="<?=$reqGmX1?>" />
                          <input type="hidden" id="reqInfoGmY1" value="<?=$reqGmY1?>" />
                          <input type="hidden" id="reqInfoSkpY2" value="<?=$reqSkpY2?>" />
                          <input type="hidden" id="reqInfoSkpX2" value="<?=$reqSkpX2?>" />
                          <input type="hidden" id="reqInfoGmX2" value="<?=$reqGmX2?>" />
                          <input type="hidden" id="reqInfoGmY2" value="<?=$reqGmY2?>" />

                          <div class="clearfix"></div>
                        </div>
                      </div>
                      <div class="area-kuadran-legend">
                        <table class="grid">
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
                              $vnomor= romanic_number($nomor);

                              $vjumlah= 0;
                              $infocari= $nomor;
                              $arraycari= in_array_column($infocari, "INDEX", $infodata);
                              // print_r($arraycari);exit;
                              if(!empty($arraycari))
                              {
                                $vjumlah= $infodata[$arraycari[0]]["JUMLAH_DATA"];
                              }
                            ?>
                            <tr>
                              <td style="text-align: center;"><?=$vnomor?></td>
                              <td>
                                <a class="fancybox fancybox.iframe" href="app/loadUrl/app/peta_pegawai_2023?reqKotakRumpun=<?=$nomor?>&reqTipePegawaiId=<?=$reqTipePegawaiId?>&reqEselonGroupId=<?=$reqEselonGroupId?>&reqSatuanKerjaId=<?=$reqSatuanKerjaId?>"><?=$arrdata[$nomor]["JUMLAH_DATA"]?> orang</a>
                                <!-- <?=$vjumlah?> orang -->
                              </td>
                              <td><?=$arrdata[$nomor]["KETERANGAN"]?></td>
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
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  function showTree(argument) {
     $("#settoggle").show();
     $("#buttonHideTree").show();
     $("#tree").show();
     $("#clicktoggle").hide();
  }
  function hideTree(argument) {
     $("#settoggle").hide();
     $("#buttonHideTree").hide();
     $("#tree").hide();
     $("#clicktoggle").show();
  }

</script>
<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
<!-- <script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script> -->
<link href="lib/treeTable2/doc/stylesheets/master.css" rel="stylesheet" type="text/css" />
<link href="lib/treeTable2/src/stylesheets/jquery.treeTable.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/media/js/jquery.js"></script>

<script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="lib/easyui/jquery-easyui-1.4.2/jquery.easyui.min.js"></script>

<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/extensions/Responsive/js/dataTables.responsive.js"></script>
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/syntax/shCore.js"></script>
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/demo.js"></script>
<script src="lib/js/valsix-serverside.js"></script>
    
<script type="text/javascript" src="lib/easyui/breadcrum.js"></script>
<script type="text/javascript" charset="utf-8">
  var oTable;
</script>

</head>

<link href="css/bluetabs.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/dropdowntabs.js"></script>

<?php /*?><link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/dataTables.material.min.css"><?php */?>

<link rel="stylesheet" type="text/css" href="css/gaya-monitoring.css">

<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

<link href="lib/mbox/mbox.css" rel="stylesheet">
<script src="lib/mbox/mbox.js"></script>
<link href="lib/mbox/mbox-modif.css" rel="stylesheet">

<script type="text/javascript" src="lib/fancybox-2.1.7/source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="lib/fancybox-2.1.7/source/jquery.fancybox.css?v=2.1.5" media="screen" />

<script script type="text/javascript" src="lib/highcharts/highcharts.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('.fancybox').fancybox();
  });
</script>

<style type="text/css">
  .loading {
    position: fixed;
    z-index: 999;
    height: 2em;
    width: 2em;
    overflow: show;
    margin: auto;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
  }

  /* Transparent Overlay */
  .loading:before {
    content: '';
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0, .8));

    background: -webkit-radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0,.8));
  }

  /* :not(:required) hides these rules from IE9 and below */
  .loading:not(:required) {
    /* hide "loading..." text */
    font: 0/0 a;
    color: transparent;
    text-shadow: none;
    background-color: transparent;
    border: 0;
  }

  .loading:not(:required):after {
    content: '';
    display: block;
    font-size: 10px;
    width: 1em;
    height: 1em;
    margin-top: -0.5em;
    -webkit-animation: spinner 150ms infinite linear;
    -moz-animation: spinner 150ms infinite linear;
    -ms-animation: spinner 150ms infinite linear;
    -o-animation: spinner 150ms infinite linear;
    animation: spinner 150ms infinite linear;
    border-radius: 0.5em;
    -webkit-box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
    box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
  }

  /* Animation */

  @-webkit-keyframes spinner {
    0% {
      -webkit-transform: rotate(0deg);
      -moz-transform: rotate(0deg);
      -ms-transform: rotate(0deg);
      -o-transform: rotate(0deg);
      transform: rotate(0deg);
    }
    100% {
      -webkit-transform: rotate(360deg);
      -moz-transform: rotate(360deg);
      -ms-transform: rotate(360deg);
      -o-transform: rotate(360deg);
      transform: rotate(360deg);
    }
  }
  @-moz-keyframes spinner {
    0% {
      -webkit-transform: rotate(0deg);
      -moz-transform: rotate(0deg);
      -ms-transform: rotate(0deg);
      -o-transform: rotate(0deg);
      transform: rotate(0deg);
    }
    100% {
      -webkit-transform: rotate(360deg);
      -moz-transform: rotate(360deg);
      -ms-transform: rotate(360deg);
      -o-transform: rotate(360deg);
      transform: rotate(360deg);
    }
  }
  @-o-keyframes spinner {
    0% {
      -webkit-transform: rotate(0deg);
      -moz-transform: rotate(0deg);
      -ms-transform: rotate(0deg);
      -o-transform: rotate(0deg);
      transform: rotate(0deg);
    }
    100% {
      -webkit-transform: rotate(360deg);
      -moz-transform: rotate(360deg);
      -ms-transform: rotate(360deg);
      -o-transform: rotate(360deg);
      transform: rotate(360deg);
    }
  }
  @keyframes spinner {
    0% {
      -webkit-transform: rotate(0deg);
      -moz-transform: rotate(0deg);
      -ms-transform: rotate(0deg);
      -o-transform: rotate(0deg);
      transform: rotate(0deg);
    }
    100% {
      -webkit-transform: rotate(360deg);
      -moz-transform: rotate(360deg);
      -ms-transform: rotate(360deg);
      -o-transform: rotate(360deg);
      transform: rotate(360deg);
    }
  }
</style>

<div class="loading" id='vlsxloading'>Loading&#8230;</div>
      
<script type="text/javascript">
  // getinforumpun= JSON.parse('<?=JSON_encode($inforumpun)?>');
    // console.log(getinforumpun);

  $(document).ready(function() {
    $('select').material_select();
  });

  function calltreeid(id, nama)
  {
    $("#reqLabelSatuanKerjaNama").text(nama);
    $("#reqSatuanKerjaId").val(id);
    setCariInfo();
  }

  $(function(){
    var tt = $('#tt').treegrid({
      url: 'json/talenta_2023_json/treepilih',
      rownumbers: false,
      pagination: false,
      idField: 'ID',
      treeField: 'NAMA',
      onBeforeLoad: function(row,param){
        if (!row) {
          param.id = 0;
        }
      }
    });

    reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
    setGrafik("json/talenta_2023_json/grafik?reqSatuanKerjaId="+reqSatuanKerjaId);

    $("#areaidgrafik").hide();
    $("#areaidgrafik").on("click", function () {
        vchartkuadran.myTooltip.hide(0);
        // console.log(vchartkuadran);
        // $("#areaidgrafik").hide();
    });
  });

  function getdetilindividu(valinfoid)
  {
    newWindow = window.open("kinerja/loadUrl/sidak/biodata_detil?reqId="+valinfoid, 'Cetak');
    newWindow.focus();
  }


  function setGrafik(link_url)
  {
      var s_url= link_url;

      // alert(s_url);return false;
      var request = $.get(s_url);
      request.done(function(dataJson)
      {
          if(dataJson == ''){}
          else
          {
              dataValue= JSON.parse(dataJson);
              // console.log(dataValue);

              var reqSkpY0= reqSkpX0= reqGmY0= reqGmX0=
              reqSkpY1= reqSkpX1= reqGmY1= reqGmX1=
              reqSkpY2= reqSkpX2= reqGmY2= reqGmX2= 0;

              reqSkpY0= parseFloat($("#reqInfoSkpY0").val());
              reqSkpX0= parseFloat($("#reqInfoSkpX0").val());
              reqGmY0= parseFloat($("#reqInfoGmY0").val());
              reqGmX0= parseFloat($("#reqInfoGmX0").val());
              reqSkpY1= parseFloat($("#reqInfoSkpY1").val());
              reqSkpX1= parseFloat($("#reqInfoSkpX1").val());
              reqGmY1= parseFloat($("#reqInfoGmY1").val());
              reqGmX1= parseFloat($("#reqInfoGmX1").val());
              reqSkpY2= parseFloat($("#reqInfoSkpY2").val());
              reqSkpX2= parseFloat($("#reqInfoSkpX2").val());
              reqGmY2= parseFloat($("#reqInfoGmY2").val());
              reqGmX2= parseFloat($("#reqInfoGmX2").val());

              // untuk fixed posisi tick
              const arrtickPotensiPositions = [0, reqSkpX0, reqSkpX1, reqSkpX2];
              const arrtickKompetensiPositions = [0, reqGmY0, reqGmY1, reqGmY2];

              chartkuadran = new Highcharts.Chart({
              chart: 
                  {
                      renderTo: 'kontenidgrafik',
                  },
                  exporting: {
                      enabled: false
                  },
                  credits: {
                      enabled: false
                  },
                  legend:{
                      enabled:false
                  },
                  xAxis: {
                      title:{
                           text:'Unsur Potensial'
                           , style: {
                              // color: 'white',
                              // fontSize: '15px'
                           }
                      },
                      min: 0,
                      max: reqSkpX2,
                      tickLength:0,
                      minorTickLength:0,
                      gridLineWidth:0,
                      showLastLabel:true,
                      showFirstLabel:false,
                      // lineColor:'#ccc',
                      // lineWidth:1,
                      lineColor:'white',
                      lineWidth:0,
                      bgColor: "#ff0",
                      tickPositions: arrtickPotensiPositions,
                      labels: {
                          style: {
                              // color: 'white',
                              // fontSize: '15px'
                          }
                      },
                  },
                  yAxis: {
                      title:{
                          text:'Unsur Kinerja'
                          , rotation:270
                          , style: {
                              // color: 'white',
                              // fontSize: '15px'
                          }
                      },
                      min: 0,
                      max: reqGmY2,
                      tickLength:3,
                      minorTickLength:0,
                      gridLineWidth:0,
                      // lineColor:'#ccc',
                      // lineWidth:1
                      lineColor:'white',
                      lineWidth:0,
                      tickPositions: arrtickKompetensiPositions,
                      labels: {
                          style: {
                              // color: 'white',
                              // fontSize: '15px'
                          }
                      },
                  },
                  tooltip: {
                      enabled: false,
                      useHTML: true,
                      hideDelay: 25000,
                      formatter: function() {
                          var s = this.point.myData;
                          return s;
                      }
                  },
                  title: {
                      text:''
                  },
                  plotOptions: {
                      series: {
                          stickyTracking: false,
                          cursor: 'pointer',
                          events: {
                              click: function(evt) {
                                  vchartkuadran = this.chart;
                                  vchartkuadran.myTooltip.refresh(evt.point, evt);
                                  // $("#areaidgrafik").show();
                              },
                              mouseOut: function() {
                              }                       
                          }           
                      }
                  },
                  series: [
                  {
                      turboThreshold: 100000,
                      type: 'line',
                      name: 'SKP Kurang',
                      lineWidth: 0,
                      // borderWidth: 0,
                      data: [[reqSkpX0, reqSkpY0], [reqSkpX0, reqSkpX2]],
                      marker: {
                          enabled: false
                      },
                      states: {
                          hover: {
                              lineWidth: 0
                          }
                      },
                      enableMouseTracking: false
                  },
                  {
                      turboThreshold: 100000,
                      type: 'line',
                      name: 'GM Kurang',
                      lineWidth: 0,
                      // borderWidth: 0,
                      data: [[reqGmX0, reqGmY0], [reqGmY2, reqGmY0]],
                      marker: {
                          enabled: false
                      },
                      states: {
                          hover: {
                              lineWidth: 0
                          }
                      },
                      enableMouseTracking: false
                  },
                  {
                      turboThreshold: 100000,
                      type: 'line',
                      name: 'SKP Sedang',
                      lineWidth: 0,
                      // borderWidth: 0,
                      data: [[reqSkpX1, reqSkpY1], [reqSkpX1, reqSkpX2]],
                      marker: {
                          enabled: false
                      },
                      states: {
                          hover: {
                              lineWidth: 0
                          }
                      },
                      enableMouseTracking: false
                  },
                  {
                      turboThreshold: 100000,
                      type: 'line',
                      name: 'GM Sedang',
                      lineWidth: 0,
                      // borderWidth: 0,
                      data: [[reqGmX1, reqGmY1], [reqGmY2, reqGmY1]],
                      marker: {
                          enabled: false
                      },
                      states: {
                          hover: {
                              lineWidth: 0
                          }
                      },
                      enableMouseTracking: false
                  },
                  {
                      turboThreshold: 100000,
                      type: 'line',
                      name: 'SKP Baik',
                      lineWidth: 0,
                      // borderWidth: 0,
                      data: [[reqSkpX2, reqSkpY2], [reqSkpX2, reqSkpX2]],
                      marker: {
                          enabled: false
                      },
                      states: {
                          hover: {
                              lineWidth: 0
                          }
                      },
                      enableMouseTracking: false
                  },
                  {
                      turboThreshold: 100000,
                      type: 'line',
                      name: 'GM Baik',
                      lineWidth: 0,
                      // borderWidth: 0,
                      data: [[reqGmX2, reqGmY2], [reqGmY2, reqGmY2]],
                      marker: {
                          enabled: false
                      },
                      states: {
                          hover: {
                              lineWidth: 0
                          }
                      },
                      enableMouseTracking: false
                  },
                  {
                      turboThreshold: 100000,
                      type: 'scatter',
                      name: 'Observations',
                      color: 'blue',
                      data: dataValue,
                      marker: {
                          radius: 4
                      }
                  }
                  ]

                  }
                  ,
                  function(chart) { // on complete
                      // kondisi tooltip
                      chart.myTooltip = new Highcharts.Tooltip(chart, chart.options.tooltip);
                      // console.log(chart.myTooltip);

                      var width= chart.plotBox.width;
                      var height= chart.plotBox.height;
                      var tempplotbox= tempplotboy= tempwidth= tempxwidth= tempheight= 0;
                      var modif= 45;
                      var modif= 55;

                      //garis I
                      //=====================================================================================
                      tempwidth1= tempwidth= parseFloat(width) * (parseFloat(reqSkpX0) / reqSkpX2);
                      tempheight1= tempheight= parseFloat(height) * ((reqGmY2 - parseFloat(reqGmY1)) / reqGmY2);
                      tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3) - modif;
                      tempplotbox= chart.plotBox.x;
                      tempxwidth= tempplotbox + parseFloat(parseFloat(tempwidth) / 2);
                      tempplotboy= chart.plotBox.y;
                      chart.renderer.rect(tempplotbox,tempplotboy, tempwidth, tempheight, 1).attr({
                          fill: '#559fc8',
                          zIndex: 0
                      }).add();

                      var text = chart.renderer.text("IV", tempwidth, tempheight).css({
                          fontSize: '14px'
                          // , color: '#666666'
                      }).add();
                      text.attr({
                          x: tempxwidth,
                          y: tempyheight,
                          zIndex:99
                      });
                      //=====================================================================================
                      tempwidth2= tempwidth= parseFloat(width) * ((parseFloat(reqSkpX1) - parseFloat(reqSkpX0)) / reqSkpX2);
                      tempheight= parseFloat(height) * ((reqGmY2 - parseFloat(reqGmY1)) / reqGmY2);
                      tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3) - modif;
                      tempplotbox= parseFloat(chart.plotBox.x) + parseFloat(tempwidth1);
                      tempxwidth= tempplotbox + parseFloat(parseFloat(tempwidth) / 2);
                      tempplotboy= chart.plotBox.y;
                      chart.renderer.rect(tempplotbox,tempplotboy, tempwidth, tempheight, 1).attr({
                          fill: '#b7c89c',
                          zIndex: 0
                      }).add();

                      var text = chart.renderer.text("VII", tempwidth, tempheight).css({
                          fontSize: '14px'
                          // , color: '#666666'
                      }).add();
                      text.attr({
                          x: tempxwidth,
                          y: tempyheight,
                          zIndex:99
                      });
                      //=====================================================================================
                      tempwidth= parseFloat(width) * ((reqSkpX2 - parseFloat(reqSkpX1)) / reqSkpX2);
                      tempheight= parseFloat(height) * ((reqGmY2 - parseFloat(reqGmY1)) / reqGmY2);
                      tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3) - modif;
                      tempplotbox= chart.plotBox.x + parseFloat(tempwidth1) + parseFloat(tempwidth2);
                      tempxwidth= tempplotbox + parseFloat(parseFloat(tempwidth) / 2);
                      tempplotboy= chart.plotBox.y;
                      //alert(tempwidth);
                      chart.renderer.rect(tempplotbox,tempplotboy, tempwidth, tempheight, 1).attr({
                          fill: '#84a059',
                          zIndex: 0
                      }).add();

                      var text = chart.renderer.text("IX", tempwidth, tempheight).css({
                          fontSize: '14px'
                          // , color: '#666666'
                      }).add();
                      text.attr({
                          x: tempxwidth,
                          y: tempyheight,
                          zIndex:99
                      });
                      //=====================================================================================

                      //garis II
                      //=====================================================================================
                      tempwidth1= tempwidth= parseFloat(width) * (parseFloat(reqSkpX0) / reqSkpX2);
                      tempheight2= tempheight= parseFloat(height) * ((parseFloat(reqGmY1) - parseFloat(reqGmY0)) / reqGmY2);
                      tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3) + parseFloat(tempheight1) - modif;
                      tempplotbox= chart.plotBox.x;
                      tempxwidth= tempplotbox + parseFloat(parseFloat(tempwidth) / 2);
                      tempplotboy= chart.plotBox.y + parseFloat(tempheight1);
                      chart.renderer.rect(tempplotbox,tempplotboy, tempwidth, tempheight, 1).attr({
                          fill: '#f7b94a',
                          zIndex: 0
                      }).add();

                      var text = chart.renderer.text("II", tempwidth, tempheight).css({
                          fontSize: '14px'
                          // , color: '#666666'
                      }).add();
                      text.attr({
                          x: tempxwidth,
                          y: tempyheight,
                          zIndex:99
                      });
                      //=====================================================================================
                      tempwidth2= tempwidth= parseFloat(width) * ((parseFloat(reqSkpX1) - parseFloat(reqSkpX0)) / reqSkpX2);
                      tempheight= parseFloat(height) * ((parseFloat(reqGmY1) - parseFloat(reqGmY0)) / reqGmY2);
                      tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3) + parseFloat(tempheight1) - modif;
                      tempplotbox= parseFloat(chart.plotBox.x) + parseFloat(tempwidth1);
                      tempxwidth= tempplotbox + parseFloat(parseFloat(tempwidth) / 2);
                      tempplotboy= chart.plotBox.y + parseFloat(tempheight1);
                      chart.renderer.rect(tempplotbox,tempplotboy, tempwidth, tempheight, 1).attr({
                          fill: '#fad38e',
                          zIndex: 0
                      }).add();

                      var text = chart.renderer.text("V", tempwidth, tempheight).css({
                          fontSize: '14px'
                          // , color: '#666666'
                      }).add();
                      text.attr({
                          x: tempxwidth,
                          y: tempyheight,
                          zIndex:99
                      });
                      //=====================================================================================
                      tempwidth= parseFloat(width) * ((reqSkpX2 - parseFloat(reqSkpX1)) / reqSkpX2);
                      tempheight= parseFloat(height) * ((parseFloat(reqGmY1) - parseFloat(reqGmY0)) / reqGmY2);
                      tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3) + parseFloat(tempheight1) - modif;
                      tempplotbox= chart.plotBox.x + parseFloat(tempwidth1) + parseFloat(tempwidth2);
                      tempxwidth= tempplotbox + parseFloat(parseFloat(tempwidth) / 2);
                      tempplotboy= chart.plotBox.y + parseFloat(tempheight1);
                      chart.renderer.rect(tempplotbox,tempplotboy, tempwidth, tempheight, 1).attr({
                          fill: '#b7c89c',
                          zIndex: 0
                      }).add();

                      var text = chart.renderer.text("VIII", tempwidth, tempheight).css({
                          fontSize: '14px'
                          // , color: '#666666'
                      }).add();
                      text.attr({
                          x: tempxwidth,
                          y: tempyheight,
                          zIndex:99
                      });
                      //=====================================================================================

                      //garis III
                      //=====================================================================================
                      tempwidth1= tempwidth= parseFloat(width) * (parseFloat(reqSkpX0) / reqSkpX2);
                      tempheight3= tempheight= parseFloat(height) * (parseFloat(reqGmY0) / reqGmY2);
                      tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3) + parseFloat(tempheight1) + parseFloat(tempheight2) - modif;
                      tempplotbox= chart.plotBox.x;
                      tempxwidth= tempplotbox + parseFloat(parseFloat(tempwidth) / 2);
                      tempplotboy= chart.plotBox.y + parseFloat(tempheight1) + parseFloat(tempheight2);
                      chart.renderer.rect(tempplotbox,tempplotboy, tempwidth, tempheight, 1).attr({
                          fill: '#ec5a0d',
                          zIndex: 0
                      }).add();

                      var text = chart.renderer.text("I", tempwidth, tempheight).css({
                          fontSize: '14px'
                          // , color: '#666666'
                      }).add();
                      text.attr({
                          x: tempxwidth,
                          y: tempyheight,
                          zIndex:99
                      });
                      //=====================================================================================
                      tempwidth2= tempwidth= parseFloat(width) * ((parseFloat(reqSkpX1) - parseFloat(reqSkpX0)) / reqSkpX2);
                      tempheight= parseFloat(height) * (parseFloat(reqGmY0) / reqGmY2);
                      tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3) + parseFloat(tempheight1) + parseFloat(tempheight2) - modif;
                      tempplotbox= parseFloat(chart.plotBox.x) + parseFloat(tempwidth1);
                      tempxwidth= tempplotbox + parseFloat(parseFloat(tempwidth) / 2);
                      tempplotboy= chart.plotBox.y + parseFloat(tempheight1) + parseFloat(tempheight2);
                      chart.renderer.rect(tempplotbox,tempplotboy, tempwidth, tempheight, 1).attr({
                          fill: '#f7b94a',
                          zIndex: 0
                      }).add();

                      var text = chart.renderer.text("III", tempwidth, tempheight).css({
                          fontSize: '14px'
                          // , color: '#666666'
                      }).add();
                      text.attr({
                          x: tempxwidth,
                          y: tempyheight,
                          zIndex:99
                      });
                      //=====================================================================================
                      tempwidth= parseFloat(width) * ((reqSkpX2 - parseFloat(reqSkpX1)) / reqSkpX2);
                      tempheight= parseFloat(height) * (parseFloat(reqGmY0) / reqGmY2);
                      tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3) + parseFloat(tempheight1) + parseFloat(tempheight2) - modif;
                      tempplotbox= chart.plotBox.x + parseFloat(tempwidth1) + parseFloat(tempwidth2);
                      tempxwidth= tempplotbox + parseFloat(parseFloat(tempwidth) / 2);
                      tempplotboy= chart.plotBox.y + parseFloat(tempheight1) + parseFloat(tempheight2);
                      chart.renderer.rect(tempplotbox,tempplotboy, tempwidth, tempheight, 1).attr({
                          fill: '#559fc8',
                          zIndex: 0
                      }).add();

                      var text = chart.renderer.text("VI", tempwidth, tempheight).css({
                          fontSize: '14px'
                          // , color: '#666666'
                      }).add();
                      text.attr({
                          x: tempxwidth,
                          y: tempyheight,
                          zIndex:99
                      });
                      //=====================================================================================

                      $('#vlsxloading').hide();
                  }

              );

          }

      });
      
  }

  var outer = document.getElementById('settoggle');
  document.getElementById('clicktoggle').addEventListener('click', function(evnt) {
  if (outer.style.maxHeight){
      outer.style.maxHeight = null;
      outer.classList.add('settoggle-closed');
    }
    else {
      outer.style.maxHeight = outer.scrollHeight + 'px';
      outer.classList.remove('settoggle-closed');
    }
  });

  outer.style.maxHeight = outer.scrollHeight + 'px';
  $('#clicktoggle').trigger('click');


  $('.materialize-textarea').trigger('autoresize');

  $("#reqStatusPegawaiId,#reqTipePegawaiId,#reqEselonGroupId").change(function() {
    setCariInfo();
  });

  function setCariInfo()
  {
    $(document).ready( function () {
      $("#btnCari").click();
    });
  }

  $('#btnhitungulang').on('click', function () {
    mbox.custom({
      message: 'Apakah anda yakin mengurutkan hitung ulang?',
      options: {}, // see Options below for options and defaults
      buttons: [
          {
              label: 'Ya',
              color: 'orange darken-2',
              callback: function() {
                mbox.close();

                ajaxurl= "json/talenta_2023_json/hitungulang";
                $.ajax({
                  cache: false,
                  url: ajaxurl,
                  processData: false,
                  contentType: false,
                  type: 'GET',
                  // dataType: 'json',
                  beforeSend:function(){
                    $.messager.progress({height:75, text:'Proses Hitung'});
                  },
                  success: function (response) {
                    // console.log(response);return false;
                    setCariInfo();
                  },
                  error: function(xhr, status, error) {
                  },
                  complete: function () {
                  }
                });
              }
          },
          {
              label: 'Tidak',
              color: 'red darken-2',
              callback: function() {
                mbox.close();
              }
          }
      ]
    })

  });

  $('#btnCari').on('click', function () {
    var reqGlobalValidasiCheck= reqCariFilter= reqSatuanKerjaId= reqStatusPegawaiId= reqStatusPegawaiId= reqStatusPegawaiId= "";
    // reqPencarian= $('#example_filter input').val();
    reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
    reqCariFilter= $("#reqCariFilter").val();
    reqStatusPegawaiId= $("#reqStatusPegawaiId").val();
    reqTipePegawaiId= $("#reqTipePegawaiId").val();
    reqEselonGroupId= $("#reqEselonGroupId").val();

    document.location.href= "sidak_2023/index/peta?reqPencarian="+reqCariFilter+"&reqSatuanKerjaId="+reqSatuanKerjaId+"&reqStatusPegawaiId="+reqStatusPegawaiId+"&reqTipePegawaiId="+reqTipePegawaiId+"&reqEselonGroupId="+reqEselonGroupId;
  });
</script>