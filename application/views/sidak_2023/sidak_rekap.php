<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");
$this->load->model('SatuanKerja');
$this->load->library('globalrekappegawai');
$vfpeg= new globalrekappegawai();

$tinggi = 156;
// $tinggi = 580;
$reqSatuanKerjaNama= "Semua Satuan Kerja";

$arrtabledata= array(
  array("label"=>"", "field"=> "", "display"=>"", "width"=>"20", "colspan"=>"", "rowspan"=>"2")
  , array("label"=>"Nama Pegawai<br/>NIP", "field"=> "NAMA_LENGKAP_NIP_BARU", "display"=>"", "width"=>"20", "colspan"=>"", "rowspan"=>"2")
  , array("label"=>"Unsur Kinerja", "field"=> "SKOR_KINERJA", "display"=>"", "width"=>"20", "colspan"=>"", "rowspan"=>"2")
  , array("label"=>"", "field"=> "SKOR_PER_RUMPUN_A", "display"=>"", "width"=>"20", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"", "field"=> "SKOR_PER_RUMPUN_P", "display"=>"", "width"=>"20", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"", "field"=> "SKOR_PER_RUMPUN_M", "display"=>"", "width"=>"20", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"", "field"=> "SKOR_PER_RUMPUN_H", "display"=>"", "width"=>"20", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"", "field"=> "SKOR_PER_RUMPUN_E", "display"=>"", "width"=>"20", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"", "field"=> "SKOR_PER_RUMPUN_PU", "display"=>"", "width"=>"20", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"", "field"=> "SKOR_PER_RUMPUN_T", "display"=>"", "width"=>"20", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"sorderdefault", "field"=> "SORDERDEFAULT", "display"=>"1", "width"=>"", "colspan"=>"", "rowspan"=>"2")
  , array("label"=>"Unsur Potensial", "field"=> "SKOR_POTENSIAL", "display"=>"", "width"=>"20", "colspan"=>"", "rowspan"=>"2")

  // array("label"=>"", "field"=> "", "display"=>"", "width"=>"20", "colspan"=>"", "rowspan"=>"2")
  // , array("label"=>"NIP", "field"=> "NIP_BARU", "display"=>"", "width"=>"20", "colspan"=>"", "rowspan"=>"2")
  // , array("label"=>"Nama", "field"=> "NAMA_LENGKAP", "display"=>"", "width"=>"", "colspan"=>"", "rowspan"=>"2")


  /*, array("label"=>"", "field"=> "PREDIKAT_KINERJA", "display"=>"", "width"=>"", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"", "field"=> "SKOR_KINERJA", "display"=>"", "width"=>"", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"", "field"=> "PENGHARGAAN_NAMA", "display"=>"", "width"=>"", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"", "field"=> "PENGHARGAAN_NILAI", "display"=>"", "width"=>"", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"", "field"=> "HUKUMAN_NAMA", "display"=>"", "width"=>"", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"", "field"=> "HUKUMAN_NILAI", "display"=>"", "width"=>"", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"", "field"=> "SKOR_KOMPETENSI", "display"=>"", "width"=>"", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"", "field"=> "SKOR_KONVERSI_KOMPETENSI", "display"=>"", "width"=>"", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"Nilai Masa Kerja", "field"=> "HASIL_JABATAN_MASA_KERJA", "display"=>"", "width"=>"", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"A", "field"=> "HASIL_JABATAN_MASA_JABATAN_RUMPUN_A", "display"=>"", "width"=>"", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"P", "field"=> "HASIL_JABATAN_MASA_JABATAN_RUMPUN_P", "display"=>"", "width"=>"", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"M", "field"=> "HASIL_JABATAN_MASA_JABATAN_RUMPUN_M", "display"=>"", "width"=>"", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"H", "field"=> "HASIL_JABATAN_MASA_JABATAN_RUMPUN_H", "display"=>"", "width"=>"", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"E", "field"=> "HASIL_JABATAN_MASA_JABATAN_RUMPUN_E", "display"=>"", "width"=>"", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"PU", "field"=> "HASIL_JABATAN_MASA_JABATAN_RUMPUN_PU", "display"=>"", "width"=>"", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"T", "field"=> "HASIL_JABATAN_MASA_JABATAN_RUMPUN_T", "display"=>"", "width"=>"", "colspan"=>"", "rowspan"=>"")*/

// HUKUMAN_ID, , , PENILAIAN_KOMPETENSI_ID, , , HASIL_KOMPETENSI, , , , , , , , , DIKLAT_STRUKTURAL_NAMA, DIKLAT_STRUKTURAL_NILAI, HASIL_DIKLAT_STRUKTURAL, HASIL_DIKLAT_KURSUS_RUMPUN_A, HASIL_DIKLAT_KURSUS_RUMPUN_P, HASIL_DIKLAT_KURSUS_RUMPUN_M, HASIL_DIKLAT_KURSUS_RUMPUN_H, HASIL_DIKLAT_KURSUS_RUMPUN_E, HASIL_DIKLAT_KURSUS_RUMPUN_PU, HASIL_DIKLAT_KURSUS_RUMPUN_T, HASIL_PENDIDIKAN_RUMPUN_A, HASIL_PENDIDIKAN_RUMPUN_P, HASIL_PENDIDIKAN_RUMPUN_M, HASIL_PENDIDIKAN_RUMPUN_H, HASIL_PENDIDIKAN_RUMPUN_E, HASIL_PENDIDIKAN_RUMPUN_PU, HASIL_PENDIDIKAN_RUMPUN_T, SKOR_PER_RUMPUN_A, SKOR_PER_RUMPUN_P, SKOR_PER_RUMPUN_M, SKOR_PER_RUMPUN_H, SKOR_PER_RUMPUN_E, SKOR_PER_RUMPUN_PU, SKOR_PER_RUMPUN_T, SKOR_POTENSIAL, KUADRAN_PEGAWAI, NAMA_POTENSIAL

  // untuk dua ini kunci, data akhir id, data sebelum akhir untuk order
  // , array("label"=>"sorderdefault", "field"=> "SORDERDEFAULT", "display"=>"1", "width"=>"", "colspan"=>"", "rowspan"=>"2")
  // , array("label"=>"fieldid", "field"=> "PEGAWAI_ID", "display"=>"1", "width"=>"", "colspan"=>"", "rowspan"=>"2")
);

$arrstatus= $vfpeg->pilihstatus();
$arrtipepegawai= $vfpeg->pilihtipepegawai();
$arreselon= $vfpeg->piliheselon();
?>
<html>
<head>
</html>
<base href="<?=base_url()?>" />

<div class="app-page-title">
  <div class="container fiori-container">
    <div class="page-title-wrapper">
      <div class="page-title-heading">
        <div>
          Rekap <div class="page-title-subheading">List Data Rekap</div>
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
              <div class="float-left">
                <a href="#" id="btnCari" style="display:none" title="Cari">Cari</a>
                <input type="text" id="reqCariFilter" class="form-control" placeholder="Pencarian"/>
              </div>
              <div class="float-right">
                <!-- <button class="btn-wide btn-shadow btn btn-primary btn-sm" id='btnAdd'>Add</button>
                <button class="btn-wide btn-shadow btn btn-primary btn-sm" id='btnEdit'>Edit</button>
                <button class="btn-wide btn-shadow btn btn-primary btn-sm" id='btnDelete'>Delete</button> -->
                <button class="btn-wide btn-shadow btn btn-primary btn-sm" id="btncetak" title="Cetak"><img src="images/icon-excel.png" /> Cetak</button>
              </div>
            </div>
            <div class="d-block clearfix card-footer">
              <button class="btn-wide btn-shadow btn btn-primary btn-sm" id="clicktoggle" >Show Filter Tree</button>
              
              <br>
              <br>
              <div id="settoggle">
                <div class="row" style="display: none;">
                  <div class="page-title-subheading">Status</div>
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
                  <div class="page-title-subheading">Tipe Pegawai</div>
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
                  <div class="page-title-subheading">Eselon</div>
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
            <br>
            <br>
            <br>
            <br>
            <div class="table-responsive">
                <table id="example" class="dt-responsive align-middle mb-0 table table-borderless table-striped table-hover" cellspacing="0" width="100%">
                <!-- <table id="example" class="align-middle mb-0 table table-borderless table-striped table-hover" cellspacing="0" width="100%" > -->
                    <thead style="background-color: #6770d2;color: white;">
                        <tr>
                          <?php
                          foreach($arrtabledata as $valkey => $valitem) 
                          {
                            $infolabel= $valitem["label"];
                            $inforowspan= $valitem["rowspan"];
                            $infocolspan= $valitem["colspan"];
                            $infofield= $valitem["field"];
                            $infodisplay= $valitem["display"];

                            if( (empty($infocolspan) && empty($inforowspan) ) )
                              continue;
                          ?>
                            <th style='border:1px solid #d0d0d0; text-align: center;' colspan="<?=$infocolspan?>" rowspan="<?=$inforowspan?>"><?=$infolabel?></th>
                          <?
                            if($infofield == "NAMA_LENGKAP")
                            {
                          ?>
                            <th style='border:1px solid #d0d0d0; text-align: center;' colspan="2">Predikat Kinerja</th>
                            <th style='border:1px solid #d0d0d0; text-align: center;' colspan="2">Penerimaan Penghargaan</th>
                            <th style='border:1px solid #d0d0d0; text-align: center;' colspan="2">Penjatuhan Hukuman Disiplin</th>
                            <th style='border:1px solid #d0d0d0; text-align: center;' colspan="2">Nilai Uji Kompetensi</th>
                            <th style='border:1px solid #d0d0d0; text-align: center;' colspan="8">Masa Kerja dan Masa Jabatan</th>
                          <?
                            }

                            if($infofield == "SKOR_KINERJA")
                            {
                          ?>
                            <th style='border:1px solid #d0d0d0; text-align: center;' colspan="7">Skor Potensial Per Rumpun</th>
                          <?
                            }
                          }
                          ?>
                        </tr>
                        <tr>
                          <?php
                          foreach($arrtabledata as $valkey => $valitem) 
                          {
                            $infolabel= $valitem["label"];
                            $inforowspan= $valitem["rowspan"];
                            $infocolspan= $valitem["colspan"];
                            $infofield= $valitem["field"];
                            $infodisplay= $valitem["display"];

                            if(!empty($infocolspan) || !empty($inforowspan))
                              continue;
                          ?>
                            <th style='border:1px solid #d0d0d0' colspan="<?=$infocolspan?>" rowspan="<?=$inforowspan?>"><?=$infolabel?></th>
                          <?
                          }
                          ?>
                        </tr>
                    </thead>
                </table>
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

  <link href="lib/mbox/mbox.css" rel="stylesheet">
  <script src="lib/mbox/mbox.js"></script>
  <link href="lib/mbox/mbox-modif.css" rel="stylesheet">

</head>

<link href="css/bluetabs.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/dropdowntabs.js"></script>

<?php /*?><link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/dataTables.material.min.css"><?php */?>

<link rel="stylesheet" type="text/css" href="css/gaya-monitoring.css">

 <script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>


<script type="text/javascript">
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

  var datanewtable;
  var infotableid= "example";
  var carijenis= "";
  var arrdata= <?php echo json_encode($arrtabledata); ?>;
  var indexfieldid= arrdata.length - 1;
  var valinfoid= valinforowid='';
  var datainforesponsive= datainfofilter= datainfolengthchange= "1";
  var datainfoscrollx= 100;

  // kalau untuk detil row
  var ajaxrowdetil= "1";
  // var vurlrowdetil= "json/talenta_2023_json/jsondetil?reqMode=pendidikanriwayat";
  // console.log(arrdata);
  // datainfostatesave= "1";
  // datastateduration= 60 * 2;

  // infobold= arrdata.length - 4;
  // infocolor= arrdata.length - 3;

  // kalau multicheck
  var infoglobalarrid= [];
  var arrChecked = [];

  infoscrolly= 50;

  $("#btncetak").on("click", function () {
    var reqGlobalValidasiCheck= reqCariFilter= reqSatuanKerjaId= reqStatusPegawaiId= reqStatusPegawaiId= reqStatusPegawaiId= "";
    // reqPencarian= $('#example_filter input').val();
    reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
    reqCariFilter= $("#reqCariFilter").val();
    reqStatusPegawaiId= $("#reqStatusPegawaiId").val();
    reqTipePegawaiId= $("#reqTipePegawaiId").val();
    reqEselonGroupId= $("#reqEselonGroupId").val();

    if(reqSatuanKerjaId == "")
    {
      mbox.alert('Pilih salah satu satuan kerja terlebih dahulu.', {open_speed: 0});
      return false;
    }

    newWindow = window.open("app/loadUrl/app/sidak_rekap_excel?reqBulan=<?=date('m')?>&reqTahun=<?=date('Y')?>&reqPencarian="+reqCariFilter+"&reqSatuanKerjaId="+reqSatuanKerjaId+"&reqStatusPegawaiId="+reqStatusPegawaiId+"&reqTipePegawaiId="+reqTipePegawaiId+"&reqEselonGroupId="+reqEselonGroupId, 'Cetak');
    newWindow.focus();
  });

  $('#btnCari').on('click', function () {
    var reqGlobalValidasiCheck= reqCariFilter= reqSatuanKerjaId= reqStatusPegawaiId= reqStatusPegawaiId= reqStatusPegawaiId= "";
    // reqPencarian= $('#example_filter input').val();
    reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
    reqCariFilter= $("#reqCariFilter").val();
    reqStatusPegawaiId= $("#reqStatusPegawaiId").val();
    reqTipePegawaiId= $("#reqTipePegawaiId").val();
    reqEselonGroupId= $("#reqEselonGroupId").val();

    jsonurl= "json/talenta_2023_json/jsonrekap?reqPencarian="+reqCariFilter+"&reqSatuanKerjaId="+reqSatuanKerjaId+"&reqStatusPegawaiId="+reqStatusPegawaiId+"&reqTipePegawaiId="+reqTipePegawaiId+"&reqEselonGroupId="+reqEselonGroupId+"&reqGlobalValidasiCheck="+reqGlobalValidasiCheck;
    datanewtable.DataTable().ajax.url(jsonurl).load();
  });

  $("#reqStatusPegawaiId,#reqTipePegawaiId,#reqEselonGroupId").change(function() {
    setCariInfo();
    reloadglobalklikcheck();
  });

  $("#reqCariFilter").keyup(function(e) {
    var code = e.which
    if(code==13)
    {
      setCariInfo();
      reloadglobalklikcheck();
    }
  });

  $("#triggercari").on("click", function () {
      if(carijenis == "1")
      {
          // pencarian= $('#'+infotableid+'_filter input').val();
          pencarian= $("#reqCariFilter").val();
          // console.log(pencarian);
          datanewtable.DataTable().search( pencarian ).draw();
      }
      else
      {
          
      }
  });

  jQuery(document).ready(function() {
    var jsonurl= "json/talenta_2023_json/jsonrekap";
      ajaxserverselectsingle.init(infotableid, jsonurl, arrdata);
  });

  function calltriggercari()
  {
      $(document).ready( function () {
        $("#triggercari").click();      
      });
  }

  function setCariInfo()
  {
    $(document).ready( function () {
      $("#btnCari").click();
    });
  }

  $(document).ready(function() {

      $('#button').click( function () {
          table.row('.selected').remove().draw( false );
      } );
  } );

  var tempindextab=0;
  function lihatriwayat(vinfo)
  {
    vinfo= JSON.parse(atob(vinfo));
    // console.log(vinfo);return false;

    ajaxurl= "json/talenta_2023_json/sessdetil?vparam="+JSON.stringify(vinfo);
    $.ajax({
      cache: false,
      url: ajaxurl,
      processData: false,
      contentType: false,
      type: 'GET',
      dataType: 'json',
      success: function (response) {
        // console.log(response);return false;

        newWindow = window.open("app/loadUrl/app/pegawai_add?reqId="+vinfo["vreqid"], 'Cetak'+tempindextab);
        newWindow.focus();
        tempindextab= parseInt(tempindextab) + 1;

      },
      error: function(xhr, status, error) {
      },
      complete: function () {
      }
    });

  }
</script>