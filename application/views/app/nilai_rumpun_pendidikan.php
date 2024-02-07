<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$reqBreadCrum= $this->input->get("reqBreadCrum");

$arrtabledata= array(
  array("label"=>"Pilih", "field"=> "CHECK", "display"=>"",  "width"=>"2", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"Pendidikan", "field"=> "NAMA", "display"=>"",  "width"=>"20", "colspan"=>"", "rowspan"=>"")

  // untuk dua ini kunci, data akhir id, data sebelum akhir untuk order
  , array("label"=>"sorderdefault", "field"=> "SORDERDEFAULT", "display"=>"1", "width"=>"")
  , array("label"=>"fieldid", "field"=> "PENDIDIKAN_ID", "display"=>"1", "width"=>"")
);

$tinggi = 156;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<title>Diklat</title>
	<base href="<?=base_url()?>" />
    <link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico">
    <!--<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.datatables.net/rss.xml">-->
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

<!-- ============================ -->
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

    $("#clicktoggle").hide();
});

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

<!-- =========================================== -->
<link rel="stylesheet" type="text/css" href="css/gaya-monitoring.css">

<link href="lib/treeTable2/doc/stylesheets/master.css" rel="stylesheet" type="text/css" />
<link href="lib/treeTable2/src/stylesheets/jquery.treeTable.css" rel="stylesheet" type="text/css" />

</head>
<body>

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
                                <!-- =========================================== -->
                                <ol class="breadcrumb right" id="setBreacrum"></ol>

                                <h5 class="breadcrumbs-title">Nilai Kualifikasi Pendidikan</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <!--breadcrumbs end-->

                <div id="bluemenu" class="bluetabs">
                    <ul>
                        <li>
                            <a id="btnEdit" title="Ubah"><img src="images/icon-edit.png" /> Setting Nilai</a>
                        </li>
                    </ul>
                </div>

                <div class="area-parameter">
                  <div class="row">
                    <div class="col s12 m12">

                      <div class="row" style="margin-left: -24px;">

                        <div class="input-field col s12 m6" style="float: right;">
                          <input type="text" id="reqCariFilter" placeholder />
                          <label for="reqCariFilter">Search</label>
                          <button id="clicktoggle">Filter ▾</button>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>

                <!--start container-->
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
                <!--end container-->
            </section>
            <!-- END CONTENT -->
        </div>
        <!-- END WRAPPER -->

    </div>
    <!-- END MAIN -->

    <!-- kalau multicheck -->
    <input type="hidden" id="reqGlobalValidasiCheck" name="reqGlobalValidasiCheck" />
    <a href="#" id="btnCari" style="display:none" title="btnCari">triggercari</a>
    <a href="#" id="triggercari" style="display:none" title="triggercari">triggercari</a>

    <!--materialize js-->
    <script type="text/javascript" src="lib/materializetemplate/js/materialize.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('select').material_select();
        });

        $('.materialize-textarea').trigger('autoresize');

        var datanewtable;
        var infotableid= "example";
        var carijenis= "";
        var arrdata= <?php echo json_encode($arrtabledata); ?>;
        var indexfieldid= arrdata.length - 1;
        var valinfoid= valinforowid='';
        var datainforesponsive= datainfofilter= datainfolengthchange= "1";
        var datainfoscrollx= 100;
        // console.log(arrdata);
        // datainfostatesave= "1";
        // datastateduration= 60 * 2;

        // infobold= arrdata.length - 4;
        // infocolor= arrdata.length - 3;

        // kalau multicheck
        var infoglobalarrid= [];
        var arrChecked = [];

        infoscrolly= 50;

        $("#btnEdit").on("click", function () {
          btnid= $(this).attr('id');
          reqGlobalValidasiCheck= $("#reqGlobalValidasiCheck").val();

          if(reqGlobalValidasiCheck == "" && btnid == "btnEdit")
          {
            $.messager.alert('Info', "Pilih salah satu data terlebih dahulu.", 'warning');
            return false;
          }

          window.parent.openPopup("app/loadUrl/app/nilai_rumpun_pendidikan_add/?reqId="+reqGlobalValidasiCheck);
        });

        $('#btnCari').on('click', function () {
          var reqGlobalValidasiCheck= reqPencarian= reqPendidikanId= "";
          // reqPencarian= $('#example_filter input').val();
          reqPencarian= $("#reqCariFilter").val();

          jsonurl= "json/rumpun_nilai_json/jsonpendidikan?reqPencarian="+reqPencarian+"&reqGlobalValidasiCheck="+reqGlobalValidasiCheck;
          datanewtable.DataTable().ajax.url(jsonurl).load();
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
          var jsonurl= "json/rumpun_nilai_json/jsonpendidikan";
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
                    valinfoid= dataselected[fieldinfoid];
                    valinforowid= dataselected[fieldinforowid];
                    // console.log(valinfoid+"-"+valinforowid);
                }
            } );

            $('#'+infotableid+' tbody').on( 'dblclick', 'tr', function () {
                $("#btnEdit").click();
            });

            $('#button').click( function () {
                table.row('.selected').remove().draw( false );
            } );
        } );
    </script>

</body>
</html>