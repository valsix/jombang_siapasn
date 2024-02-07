<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$this->load->library('globalrekappegawai');
$vfpeg= new globalrekappegawai();

$tinggi = 156;
// $tinggi = 580;
$reqSatuanKerjaNama= "Semua Satuan Kerja";

$arrtabledata= array(
   array("label"=>"", "field"=> "", "display"=>"",  "width"=>"20", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"NIP", "field"=> "NIP_BARU", "display"=>"",  "width"=>"20", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"Nama", "field"=> "NAMA_LENGKAP", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"Jabatan<br/>TMT<br/>Eselon", "field"=> "JABATAN_TMT_ESELON", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"Gol. Ruang", "field"=> "PANGKAT_RIWAYAT_KODE", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"")
  // , array("label"=>"Info Rekam Jejak", "field"=> "REKAM_JEJAK_DETIL", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"")

  // untuk dua ini kunci, data akhir id, data sebelum akhir untuk order
  , array("label"=>"sorderdefault", "field"=> "SORDERDEFAULT", "display"=>"1", "width"=>"")
  , array("label"=>"fieldid", "field"=> "PEGAWAI_ID", "display"=>"1", "width"=>"")
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
          Pengambangan Kompetensi <div class="page-title-subheading">List Data Pengambangan Kompetensi</div>
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

                <table id="tt" class="easyui-treegrid" style="width:100%; height:250px">
                  <thead>
                    <tr>
                      <th field="NAMA" width="90%">Nama</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
            <br>
            <br>
            <br>
            <br>
            <br>
            <div class="table-responsive">
                <table id="example" class="align-middle mb-0 table table-borderless table-striped table-hover" cellspacing="0" width="100%">
                    <thead style="background-color: #6770d2;color: white;">
                        <tr>
                            <?php
                            foreach($arrtabledata as $valkey => $valitem) 
                            {
                                echo "<th style='border:1px solid #d0d0d0'>".$valitem["label"]."</th>";
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
  var vurlrowdetil= "json/talenta_json/jsondetil?reqMode=diklatriwayat";
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

    newWindow = window.open("app/loadUrl/app/talenta_kompetensi_excel?reqBulan=<?=date('m')?>&reqTahun=<?=date('Y')?>&reqPencarian="+reqCariFilter+"&reqSatuanKerjaId="+reqSatuanKerjaId+"&reqStatusPegawaiId="+reqStatusPegawaiId+"&reqTipePegawaiId="+reqTipePegawaiId+"&reqEselonGroupId="+reqEselonGroupId, 'Cetak');
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

    jsonurl= "json/talenta_json/jsonkompetensi?reqPencarian="+reqCariFilter+"&reqSatuanKerjaId="+reqSatuanKerjaId+"&reqStatusPegawaiId="+reqStatusPegawaiId+"&reqTipePegawaiId="+reqTipePegawaiId+"&reqEselonGroupId="+reqEselonGroupId+"&reqGlobalValidasiCheck="+reqGlobalValidasiCheck;
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
    var jsonurl= "json/talenta_json/jsonkompetensi";
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
      /*var table = $('#example').DataTable();

      $('#example tbody').on( 'click', 'tr', function () {
          if ( $(this).hasClass('selected') ) {
              $(this).removeClass('selected');
          }
          else {
              table.$('tr.selected').removeClass('selected');
              $(this).addClass('selected');

              var dataselected= datanewtable.DataTable().row(this).data();
              // console.log(dataselected);
              // console.log(Object.keys(dataselected).length);

              fieldinfoid= arrdata[indexfieldid]["field"];
              fieldinforowid= arrdata[parseFloat(indexfieldid) - 1]["field"];
              valinfoid= dataselected[fieldinfoid];
              valinforowid= dataselected[fieldinforowid];
              // console.log(valinfoid+"-"+valinforowid);
          }
      } );

      $('#'+infotableid+' tbody').on( 'dblclick', 'tr', function () {
          $("#btnEdit").click();
      });*/

      $('#button').click( function () {
          table.row('.selected').remove().draw( false );
      } );
  } );

  var tempindextab=0;
  function lihatriwayat(vinfo)
  {
    vinfo= JSON.parse(atob(vinfo));
    // console.log(vinfo);return false;

    ajaxurl= "json/talenta_json/sessdetil?vparam="+JSON.stringify(vinfo);
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