<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('Diklat');
$this->load->library('globalrekappegawai');
$vfpeg= new globalrekappegawai();

$reqBreadCrum= $this->input->get("reqBreadCrum");

$arrtabledata= array(
  array("label"=>"NIP", "field"=> "NIP_BARU", "display"=>"",  "width"=>"20", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"Nama", "field"=> "NAMA_LENGKAP", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"Jenjang Diklat", "field"=> "DIKLAT_NAMA", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"Tahun", "field"=> "TAHUN", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"Satuan Kerja Detil", "field"=> "INFO_SATUAN_KERJA_NAMA", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"")

  // untuk dua ini kunci, data akhir id, data sebelum akhir untuk order
  , array("label"=>"sorderdefault", "field"=> "SORDERDEFAULT", "display"=>"1", "width"=>"")
  , array("label"=>"fieldrowid", "field"=> "DIKLAT_STRUKTURAL_ID", "display"=>"1", "width"=>"")
  , array("label"=>"fieldid", "field"=> "PEGAWAI_ID", "display"=>"1", "width"=>"")
);

$arrjenisdiklat= [];
$statement= "";
$diklat= new Diklat();
$diklat->selectByParams(array());
while($diklat->nextRow())
{
  $arrdata= [];
  $arrdata["id"]= $diklat->getField("DIKLAT_ID");
  $arrdata["text"]= $diklat->getField("NAMA");
  array_push($arrjenisdiklat, $arrdata);
}
$tinggi = 156;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<title>Diklat</title>
<base href="<?=base_url()?>" />
<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico">
<link href="css/admin.css" rel="stylesheet" type="text/css">

<style type="text/css" media="screen">
  @import "lib/media/css/site_jui.css";
  @import "lib/media/css/demo_table_jui.css";
  @import "lib/media/css/themes/base/jquery-ui.css";
</style>

<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/extensions/Responsive/css/dataTables.responsive.css">
<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/examples/resources/syntax/shCore.css">
<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/examples/resources/demo.css">

<style type="text/css" class="init">
  div.container { max-width: 100%;}

  .option-vw8 {
    width: 50% !important;
  }

  .labelkhusus
  {
    top: -20px !important;
  }
  
</style>
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/media/js/jquery.js"></script>

<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="lib/easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="lib/easyui/demo/demo.css">

<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>

<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/extensions/Responsive/js/dataTables.responsive.js"></script>
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/syntax/shCore.js"></script>
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/demo.js"></script>

<script src="lib/js/valsix-serverside.js"></script>

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

    // $("#clicktoggle").hide();
});

</script>

<link href="css/bluetabs.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/dropdowntabs.js"></script>

<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
<link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/dataTables.materialize.css">
<link rel="stylesheet" type="text/css" href="css/gaya-monitoring.css">

</head>
<body>

<div id="main">
    <div class="wrapper">
        <section id="content-full">

            <div id="breadcrumbs-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col s12 m12 l12">
                            <ol class="breadcrumb right" id="setBreacrum"></ol>

                            <h5 class="breadcrumbs-title">Sinkron Diklat Struktural</h5>
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

            <div id="bluemenu" class="bluetabs">
                <ul>
                    <li>
                      <a href="javascript:void(0)" id="btnCari" title="Cari">Cari</a>
                        <!-- <a id="btncetak" title="Cetak"><img src="images/icon-excel.png" /> Cetak</a> -->
                    </li>
                    <a href="javascript:void(0)" style="display: none;" id="btnEdit" title="Cari">Cari</a>
                </ul>
            </div>

            <div class="area-parameter">
              <div class="kiri">
              </div>

              <div class="kanan">
                <span></span>
                <input type="text" id="reqCariFilter" />
                <button id="clicktoggle">Filter ▾</button>
              </div>
            </div>

            <div class="area-parameter no-marginbottom">

              <div id="settoggle">
                <div class="row">
                  <div class="col s3 m2">Jenjang Diklat</div>
                  <div class="col s2 m1 select-semicolon">:</div>
                  <div class="col s3 m3">
                    <select id="reqDiklat">
                    <option value="" <? if("" == $reqDiklat) echo "selected";?>>Semua</option>
	                  <?
                    foreach($arrjenisdiklat as $item) 
                    {
                      $selectvalid= $item["id"];
                      $selectvaltext= $item["text"];
	                  ?>
	                    <option value="<?=$selectvalid?>" <? if($selectvalid == $reqDiklat) echo "selected";?> ><?=$selectvaltext?></option>
	                  <?
	                  }
	                  ?>
	                  </select>
	                </div>

                  <div class="col s3 m2">Tahun Diklat/Kursus</div>
                  <div class="col s2 m1 select-semicolon">:</div>
                  <div class="col s3 m1">
                    <input type="text" id="reqTahun" />
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
                <div class="section" style="margin-top: -100px !important;">
                    <table id="example" class="display mdl-data-table dt-responsive" cellspacing="0" width="100%">
                        <thead>
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

        </section>
    </div>
</div>

<!-- kalau multicheck -->
<input type="hidden" id="reqGlobalValidasiCheck" name="reqGlobalValidasiCheck" />
<a href="#" id="triggercari" style="display:none" title="triggercari">triggercari</a>

<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/materialize.min.js"></script>

<style type="text/css">
  table.dataTable.display tbody td {
   vertical-align: top;
 }
</style>

<script type="text/javascript">
    $(document).ready(function() {
        $('select').material_select();

        $('[id^="reqTahun"]').bind('keyup paste', function(){
          this.value = this.value.replace(/[^0-9]/g, '');
        });
    });

    function calltreeid(id, nama)
    {
      $("#reqLabelSatuanKerjaNama").text(nama);
      $("#reqSatuanKerjaId").val(id);
      setCariInfo();
      reloadglobalklikcheck();
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
    var valinfoid= valinforowid= valinfojenisid='';
    var datainfostatesave= datainforesponsive= datainfofilter= datainfolengthchange= "1";
    var datainfoscrollx= 100;

    // kalau multicheck
    var infoglobalarrid= [];
    var arrChecked = [];

    infoscrolly= 50;

    $("#btncetak").on("click", function () {
      var reqGlobalValidasiCheck= reqCariFilter= reqSatuanKerjaId= reqDiklat= reqDiklat= reqDiklat= "";
      // reqPencarian= $('#example_filter input').val();
      reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
      reqCariFilter= $("#reqCariFilter").val();
      reqDiklat= $("#reqDiklat").val();
      reqTipePegawaiId= $("#reqTipePegawaiId").val();
      reqEselonGroupId= $("#reqEselonGroupId").val();

      newWindow = window.open("app/loadUrl/app/talenta_rekam_jejak_excel.php?reqBulan=<?=date('m')?>&reqTahun=<?=date('Y')?>&reqPencarian="+reqCariFilter+"&reqSatuanKerjaId="+reqSatuanKerjaId+"&reqDiklat="+reqDiklat+"&reqTipePegawaiId="+reqTipePegawaiId+"&reqEselonGroupId="+reqEselonGroupId, 'Cetak');
      newWindow.focus();
    });

    $("#btnEdit").on("click", function () {
      var tempindextab=0;

      ajaxurl= "json/sepadan_json/sessdetil?m=diklatstruktural&reqId="+valinfoid+"&reqRowId="+valinforowid+"&reqDiklatId="+valinfojenisid;
      // console.log(ajaxurl);return false;
      $.ajax({
        cache: false,
        url: ajaxurl,
        processData: false,
        contentType: false,
        type: 'GET',
        dataType: 'json',
        success: function (response) {
          // console.log(response);return false;

          newWindow = window.open("app/loadUrl/app/pegawai_add?reqId="+valinfoid, 'Cetak'+tempindextab);
          newWindow.focus();
          tempindextab= parseInt(tempindextab) + 1;

        },
        error: function(xhr, status, error) {
        },
        complete: function () {
        }
      });

    });

    $('#btnCari').on('click', function () {
      var reqGlobalValidasiCheck= reqCariFilter= reqSatuanKerjaId= reqDiklat= reqTahun= "";
      // reqPencarian= $('#example_filter input').val();
      reqSatuanKerjaId= $("#reqSatuanKerjaId").val();
      reqCariFilter= $("#reqCariFilter").val();
      reqDiklat= $("#reqDiklat").val();
      reqTahun= $("#reqTahun").val();

      jsonurl= "json/sepadan_json/jsondiklatstruktural?reqPencarian="+reqCariFilter+"&reqSatuanKerjaId="+reqSatuanKerjaId+"&reqDiklat="+reqDiklat+"&reqTahun="+reqTahun;
      datanewtable.DataTable().ajax.url(jsonurl).load();
    });

    $("#reqDiklat").change(function() {
        setCariInfo();
        // reloadglobalklikcheck();
    });

    $("#reqCariFilter").keyup(function(e) {
      var code = e.which
      if(code==13)
      {
        setCariInfo();
        // reloadglobalklikcheck();
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
      var jsonurl= "json/sepadan_json/jsondiklatstruktural";
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
        var table = $('#example').DataTable();

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
                fieldinfojenisid= arrdata[parseFloat(indexfieldid) - 2]["field"];

                if(typeof dataselected == "undefined")
                {
                  valinfoid= valinforowid= valinfojenisid= "";
                }
                else
                {
                  valinfoid= dataselected[fieldinfoid];
                  valinforowid= dataselected[fieldinforowid];
                  valinfojenisid= dataselected[fieldinfojenisid];
                }
                // console.log(valinfoid+"-"+valinforowid);
            }
        } );

        $('#'+infotableid+' tbody').on( 'dblclick', 'tr', function () {
            $("#btnEdit").click();
        });

    } );
</script>

<link href="lib/treeTable2/doc/stylesheets/master.css" rel="stylesheet" type="text/css" />
<link href="lib/treeTable2/src/stylesheets/jquery.treeTable.css" rel="stylesheet" type="text/css" />
</body>
</html>