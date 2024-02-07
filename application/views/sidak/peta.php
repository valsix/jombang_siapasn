<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$tinggi = 156;
// $tinggi = 580;
$reqSatuanKerjaNama= "Semua Satuan Kerja";

$infouserloginid= $this->USER_LOGIN_ID;
$infomenid= "3405";
$infoaksesmenu= $CI->checkmenupegawai($infouserloginid, $infomenid);
// echo $infoaksesmenu;exit;

$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
$reqEselonGroupId= $this->input->get("reqEselonGroupId");
$reqTipePegawaiId= $this->input->get("reqTipePegawaiId");
if($reqSatuanKerjaId == "")
{
  $tempSatuanKerjaId= $reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
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

$this->load->model("talent/RekapTalent");

$arrdata= [];
$set= new RekapTalent();
$set->selectpetatalentrumpun(array(), -1, -1, $statement);
while($set->nextRow())
{
  $kotakrumpun= $set->getField("KOTAK_RUMPUN");

  $arrdata[$kotakrumpun]["JUMLAH_DATA"]= $set->getField("JUMLAH");
}
// print_r($arrdata);exit;

for($x=1; $x < 10; $x++)
{
  if(empty($arrdata[$x]["JUMLAH_DATA"]))
    $arrdata[$x]["JUMLAH_DATA"]= 0;

  $arrdata[$x]["KETERANGAN"]= $kotakpetatalentaketerangan[$x-1]["nama"];
}

$infodata= [];
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

  $infodata[$x]["INDEX"]= $infoindex;
  $infodata[$x]["NAMA"]= romanic_number($infoindex);
  $infodata[$x]["JUMLAH_DATA"]= $arrdata[$infoindex]["JUMLAH_DATA"];
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

  /*table.dataTable tbody td th{
    vertical-align: top;
    white-space: nowrap;
  }

  @media only screen and (max-width: 767px) {
    table{
          table-layout:auto !important;
      }
  }*/
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
                <button class="btn-wide btn-shadow btn btn-primary btn-sm" id="btnhitungulang" title="Hitung Ulang"> Hitung Ulang</button>
                <button class="btn-wide btn-shadow btn btn-primary btn-sm" id="clicktoggle" >Show Filter Tree</button>
              </div>
            </div>
            <div class="d-block clearfix card-footer">
              <div id="settoggle">
                <div class="row">
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

                <div class="row">
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
            <br>

            <div class="table-responsive">
                <div class="container" style="clear:both;">
                  <div class="section">

                    <div class="area-kuadran-wrapper">
                      <div class="area-kuadran">
                        <div class="inner">
                          <?
                          foreach ($infodata as $key => $value)
                          {
                          ?>
                          <div class="item"><a class="fancybox fancybox.iframe" href="app/loadUrl/app/peta_pegawai?reqKotakRumpun=<?=$value['INDEX']?>&reqTipePegawaiId=<?=$reqTipePegawaiId?>&reqEselonGroupId=<?=$reqEselonGroupId?>&reqSatuanKerjaId=<?=$reqSatuanKerjaId?>"><span class="nomor"><?=$value["NAMA"]?></span><br><span class="pns"><?=$value["JUMLAH_DATA"]?> pns</span></a></div>
                          <?
                          }
                          ?>
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
                            ?>
                            <tr>
                              <td style="text-align: center;"><?=romanic_number($nomor)?></td>
                              <td><a class="fancybox fancybox.iframe" href="app/loadUrl/app/peta_pegawai?reqKotakRumpun=<?=$nomor?>&reqTipePegawaiId=<?=$reqTipePegawaiId?>&reqEselonGroupId=<?=$reqEselonGroupId?>&reqSatuanKerjaId=<?=$reqSatuanKerjaId?>"><?=$arrdata[$nomor]["JUMLAH_DATA"]?> orang</a></td>
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

<script type="text/javascript">
  $(document).ready(function() {
    $('.fancybox').fancybox();
  });
</script>

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
      url: 'satuan_kerja_json/treepilih',
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
  });

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

  // var dlg = $.messager.progress({
  //   border: 'thin',
  //   msg: '<img src="https://www.jeasyui.com/images/logo1.png" style="height:30px">'
  // });
  // $.messager.progress('bar').hide();
  // dlg.dialog('resize');

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

                ajaxurl= "json/talenta_json/hitungulang";
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

    document.location.href= "sidak/index/peta?reqPencarian="+reqCariFilter+"&reqSatuanKerjaId="+reqSatuanKerjaId+"&reqStatusPegawaiId="+reqStatusPegawaiId+"&reqTipePegawaiId="+reqTipePegawaiId+"&reqEselonGroupId="+reqEselonGroupId;
  });
</script>