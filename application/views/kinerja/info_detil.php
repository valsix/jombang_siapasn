<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

// $reqBreadCrum= $this->input->get("reqBreadCrum");
$reqKuadran= httpFilterGet("reqKuadran");
$reqTahun= httpFilterGet("reqTahun");
$reqEselonGroupId= httpFilterGet("reqEselonGroupId");
$reqTipePegawaiId= httpFilterGet("reqTipePegawaiId");
$reqSatuanKerjaId= httpFilterGet("reqSatuanKerjaId");
$reqTahunSebelum= $reqTahun - 1;
// $reqBreadCrum= "KUADRAN ".romanic_number($reqKuadran);


$arrkolomdata= array(
    array("info"=>"NIP BARU<br/>NIP LAMA", "width"=>"10")
    , array("info"=>"NAMA", "width"=>"20")
    , array("info"=>"NILAI KINERJA", "width"=>"5")
    , array("info"=>"NILAI KOMPETENSI", "width"=>"5")
    , array("info"=>"JABATAN", "width"=>"20")
    , array("info"=>"UNIT KERJA", "width"=>"30")
);

$tinggi = 156;


//tambahan dari rendy
switch ($reqKuadran) {
  case "1":
    $reqKuadranNama = "I";
    break;
  case "2":
    $reqKuadranNama = "II";
    break;
  case "3":
    $reqKuadranNama = "III";
    break;
  case "4":
    $reqKuadranNama = "IV";
    break;
  case "5":
    $reqKuadranNama = "V";
    break;
  case "6":
    $reqKuadranNama = "VI";
    break;
  case "7":
    $reqKuadranNama = "VII";
    break;
  case "8":
    $reqKuadranNama = "VIII";
    break;
  default:
    $reqKuadranNama = "";
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
    <title>Sistem Informasi Data Analisis Kepegawaian</title>
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
    
</style>
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/media/js/jquery.js"></script>

<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="lib/easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="lib/easyui/demo/demo.css">

<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="lib/easyui/breadcrum.js"></script>

<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/extensions/Responsive/js/dataTables.responsive.js"></script>
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/syntax/shCore.js"></script>
<script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/examples/resources/demo.js"></script>

<script type="text/javascript" charset="utf-8">
  var oTable;
  $(document).ready(function(){
        <?
        if($reqBreadCrum == ""){}
        else
        {
        ?>
        setinfobreacrum("<?=$reqBreadCrum?>", "setBreacrum");
        <?
        }
        ?>

        var id = -1;//simulation of id
        $(window).resize(function() {
            console.log($(window).height());
            $('.dataTables_scrollBody').css('height', ($(window).height() - <?=$tinggi?>));
        });
        oTable = $('#example').dataTable({ bJQueryUI: true,"iDisplayLength": 25,
            /* UNTUK MENGHIDE KOLOM ID */
            "aoColumns": [ 
            <?
            for($i=0; $i < count($arrkolomdata); $i++)
            {
                if($i > 0)
                    echo ",";
            ?>
                null
            <?
            }
            ?>
            ],
          "bSort":true,
          "bFilter": false,
          "bLengthChange": false,
          "bProcessing": true,
          "bServerSide": true,
          "sAjaxSource": "jkinerja/penilaian_json/json/?reqKuadran=<?=$reqKuadran?>&reqTahun=<?=$reqTahun?>&reqEselonGroupId=<?=$reqEselonGroupId?>&reqTipePegawaiId=<?=$reqTipePegawaiId?>&reqSatuanKerjaId=<?=$reqSatuanKerjaId?>",
          "sScrollY": ($(window).height() - <?=$tinggi?>),
          "sScrollX": "100%",                                 
          "sScrollXInner": "100%",
          "sPaginationType": "full_numbers"
      });
        /* Click event handler */

        /* RIGHT CLICK EVENT */
        var anSelectedData = '';
        var anSelectedId = '';
        var anSelectedDownload = '';
        var anSelectedPosition = '';    

        function fnGetSelected( oTableLocal )
        {
            var aReturn = new Array();
            var aTrs = oTableLocal.fnGetNodes();
            for ( var i=0 ; i<aTrs.length ; i++ )
            {
                if ( $(aTrs[i]).hasClass('row_selected') )
                {
                    aReturn.push( aTrs[i] );
                    anSelectedPosition = i;
                }
            }
            return aReturn;
        }

        $("#example tbody").click(function(event) {
            $(oTable.fnSettings().aoData).each(function (){
                $(this.nTr).removeClass('row_selected');
            });
            $(event.target.parentNode).addClass('row_selected');

            var anSelected = fnGetSelected(oTable);                                                 
            anSelectedData = String(oTable.fnGetData(anSelected[0]));
            var element = anSelectedData.split(','); 
            anSelectedId = element[element.length-1];
        });

        $("#reqCariFilter").keyup(function(e) {
            var code = e.which;
            if(code==13)
            {
                setCariInfo();
            }
        });

        $("#btnCari").on("click", function () {
            var reqCariFilter= "";
            reqCariFilter= $("#reqCariFilter").val();
            oTable.fnReloadAjax("jkinerja/penilaian_json/json/?reqKuadran=<?=$reqKuadran?>&reqTahun=<?=$reqTahun?>&sSearch="+reqCariFilter);
        });

        $("#setIconCari").hide();

        $('#btnEdit').on('click', function () {
            if(anSelectedData == "")
            {
                $.messager.alert('Info', "Pilih salah satu data terlebih dahulu", 'info');
                return false;
            }

            newWindow = window.open("kinerja/loadUrl/kinerja/biodata_detil?reqId="+anSelectedId+"&reqKuadran=<?=$reqKuadran?>&reqTahun=<?=$reqTahunSebelum?>", 'Cetak');
            newWindow.focus();
            // window.parent.openPopup("app/loadUrl/app/eselon_add/?reqId="+anSelectedId);
        });

    });

    function setCariInfo()
    {
        $(document).ready( function () {
            $("#btnCari").click();          
        });
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
                            <ol class="breadcrumb right" id="setBreacrum"></ol>
                            <h5 class="breadcrumbs-title">Pegawai Kuadran <? echo $reqKuadranNama;?></h5>
                        </div>
                    </div>
                </div>
            </div>
            <!--breadcrumbs end-->

            <div id="bluemenu" class="bluetabs">
                <ul>
                    <li>
                        <a href="#" id="btnCari" style="display:none" title="Cari">Cari</a>
                        <a id="btnEdit" title="Biodata Detil"><img src="images/icon-user.png" width="15" height="15" /> Biodata Detil</a>
                    </li>
                </ul>
            </div>

            <div class="area-parameter">
                <div class="kiri">
                    <span>Show</span>
                    <select>
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                    </select>
                    <span>entries</span>
                </div>

                <div class="kanan">
                    <span>Search :</span>
                    <input type="text" id="reqCariFilter" />
                    <button id="clicktoggle" style="display: none;">Filter ▾</button>
                </div>
            </div>

            <!--start container-->
            <div class="container" style="clear:both;">
                <div class="section">
                    <table id="example" class="display mdl-data-table dt-responsive" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <?
                                for($i=0; $i < count($arrkolomdata); $i++)
                                {
                                ?>
                                <th style="width: <?=$arrkolomdata[$i]["width"]?>%"><?=$arrkolomdata[$i]["info"]?></th>
                                <?
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

<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/materialize.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('select').material_select();
    });

    $('.materialize-textarea').trigger('autoresize');
</script>

</body>
</html>