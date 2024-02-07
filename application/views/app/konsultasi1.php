<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$reqBreadCrum= $this->input->get("reqBreadCrum");

$this->load->model('Mailbox');

$mailbox = new Mailbox();
$mailbox->selectByParams();

$tinggi = 156;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
    <title>Diklat</title>
    <base href="<?=base_url()?>" />

    <link href="font/google-font.css" rel="stylesheet">

    <link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico">
    <!--<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.datatables.net/rss.xml">-->
    <link href="css/admin.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="lib/easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="lib/easyui/demo/demo.css">

    <script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>
    <script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>

    <script type="text/javascript" src="lib/easyui/breadcrum.js"></script>

    <link href="css/bluetabs.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="js/dropdowntabs.js"></script>

    <!-- CORE CSS-->    
    <link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
    <!-- CSS style Horizontal Nav-->    
    <link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
    <!-- Custome CSS-->    
    <link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

    <!-- <link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/dataTables.materialize.css"> -->
    <?php /*?><link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/dataTables.material.min.css"><?php */?>

    <link rel="stylesheet" type="text/css" href="css/gaya.css">

    <link href="lib/treeTable2/doc/stylesheets/master.css" rel="stylesheet" type="text/css" />
    <link href="lib/treeTable2/src/stylesheets/jquery.treeTable.css" rel="stylesheet" type="text/css" />
    <script>
        function tambahData()
        {
          document.location.href = "app/loadUrl/app/konsultasi_add";
      }
  </script>

</head>
<body style="background-image:url(images/bg-kon2.png)">
    <div id="main">
        <!-- START WRAPPER -->
        <div class="wrapper">
            <!-- START CONTENT -->
            <section id="content-full">

                <div class="card white">
                    <div class="card-content black-text" style="padding: 0">
                        <div class="header-list red">
                            <div class="row">
                                <div class="col s12 m10 offset-m1">
                                    <div class="title-list">
                                        <div class="col s12 m6">
                                            <p class="title">MAILBOX</p>
                                        </div>
                                        <div class="col s12 m6">
                                            <div class="right-not-small">
                                                <p>Menampilkan 10 dari 56 data</p>
                                                <a href="" class="circle"><i class="material-icons">keyboard_arrow_left</i></a>
                                                <a href="" class="circle"><i class="material-icons">keyboard_arrow_right</i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="body-list white" style="padding-bottom: 2em; background-image:url(images/bg-kon2.png)">
                            <div class="row">
                                <div class="col s12 m10 offset-m1">
                                    <div class="content-list white z-depth-2">
                                        <div class="row">
                                            <div class="col s10 mgt-2em">
                                                <div class="input-field col s12 m12 input-mg-sm">
                                                    <label for="reqJudulMailbox">Cari judul mailbox</label>
                                                    <input type="text" id="reqJudulMailbox" />
                                                </div>
                                                <div class="input-field col s12 m6 input-mg-sm">
                                                    <label for="reqTglMulai">Tgl mulai</label>
                                                    <input type="text" id="reqTglMulai" />
                                                </div>
                                                <div class="input-field col s12 m6 input-mg-sm">
                                                    <label for="reqTglAkhir">Tgl akhir</label>
                                                    <input type="text" id="reqTglAkhir" />
                                                </div>
                                            </div>
                                            <div class="col s2">
                                                <a href="" style="color: grey"><i class="material-icons big-ico">search</i></a>
                                            </div>
                                        </div>

                                        <div class="list-wrap">
                                            <table class="table striped">

                                                <?
                                                while($mailbox->nextRow())
                                                {
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <a href="app/loadUrl/app/konsultasi_detil">
                                                                <div class="col s12 m1 hide-on-small-only">
                                                                    <div class="icon-list red">
                                                                        <i class="material-icons">mail</i>
                                                                    </div>
                                                                </div>
                                                                <div class="col s12 m4">
                                                                    <span class="black-text">
                                                                        <b><?=$mailbox->getField("SUBYEK")?></b>
                                                                    </span> <br>
                                                                    <span class="status-info"><?=$mailbox->getField("STATUS_NAMA")?></span>
                                                                </div>
                                                            </a>
                                                            <div class="col s12 m5"><?=truncate($mailbox->getField("ISI"), 50)?></div>
                                                            <div class="col s12 m2 tgl"><?=getFormattedDateTime($mailbox->getField("TANGGAL"))?></div>
                                                            <div class="divider"></div>
                                                        </td>
                                                    </tr>

                                                    <?
                                                }
                                                ?>  

                                                <!-- CONTOH DATA -->
                                                <tr>
                                                    <td>
                                                        <a href="app/loadUrl/app/konsultasi_detil">
                                                            <div class="col s12 m1 hide-on-small-only">
                                                                <div class="icon-list red">
                                                                    <i class="material-icons">mail</i>
                                                                </div>
                                                            </div>
                                                            <div class="col s12 m4">
                                                                <span class="black-text">
                                                                    <b>abcde abcde abcde abcde abcde </b>
                                                                </span> <br>
                                                                <span class="status-info">status info nama</span>
                                                            </div>
                                                        </a>
                                                        <div class="col s12 m5">abcde abcde abcde abcde abcde abcde abcde abcde abcde abcde abcde abcde abcde </div>
                                                        <div class="col s12 m2 tgl">26 desember 2019</div>
                                                        <div class="divider"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="app/loadUrl/app/konsultasi_detil">
                                                            <div class="col s12 m1 hide-on-small-only">
                                                                <div class="icon-list red">
                                                                    <i class="material-icons">mail</i>
                                                                </div>
                                                            </div>
                                                            <div class="col s12 m4">
                                                                <span class="black-text">
                                                                    <b>abcde abcde abcde abcde abcde abcde </b>
                                                                </span> <br>
                                                                <span class="status-info">status info nama</span>
                                                            </div>
                                                        </a>
                                                        <div class="col s12 m5">abcde abcde abcde abcde abcde abcde abcde abcde abcde abcde abcde abcde abcde </div>
                                                        <div class="col s12 m2 tgl">26 desember 2019</div>
                                                        <div class="divider"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="app/loadUrl/app/konsultasi_detil">
                                                            <div class="col s12 m1 hide-on-small-only">
                                                                <div class="icon-list red">
                                                                    <i class="material-icons">mail</i>
                                                                </div>
                                                            </div>
                                                            <div class="col s12 m4">
                                                                <span class="black-text">
                                                                    <b>abcde abcde abcde abcde abcde abcde abcde abcde abcde </b>
                                                                </span> <br>
                                                                <span class="status-info">status info nama</span>
                                                            </div>
                                                        </a>
                                                        <div class="col s12 m5">abcde abcde abcde abcde abcde abcde abcde abcde abcde abcde abcde abcde abcde </div>
                                                        <div class="col s12 m2 tgl">26 desember 2019</div>
                                                        <div class="divider"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="app/loadUrl/app/konsultasi_detil">
                                                            <div class="col s12 m1 hide-on-small-only">
                                                                <div class="icon-list red">
                                                                    <i class="material-icons">mail</i>
                                                                </div>
                                                            </div>
                                                            <div class="col s12 m4">
                                                                <span class="black-text">
                                                                    <b>abcde abcde abcde abcde abcde abcde </b>
                                                                </span> <br>
                                                                <span class="status-info">status info nama</span>
                                                            </div>
                                                        </a>
                                                        <div class="col s12 m5">abcde abcde abcde abcde abcde abcde abcde abcde abcde abcde abcde abcde abcde </div>
                                                        <div class="col s12 m2 tgl">26 desember 2019</div>
                                                        <div class="divider"></div>
                                                    </td>
                                                </tr>
                                                <!-- END CONTOH DATA -->

                                                
                                            </table>
                                            
                                        </div>

                                    </div>
                                </div>  
                            </div>
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
    <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('select').material_select();
        });

        $('.materialize-textarea').trigger('autoresize');
    </script>
</body>
</html>