<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$reqBreadCrum= $this->input->get("reqBreadCrum");
$reqJenisPelayananId= $this->input->get("reqJenisPelayananId");
$reqKategoriId= $this->input->get("reqKategoriId");
$reqStatus= $this->input->get("reqStatus");

$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;

$this->load->model('Mailbox');
$this->load->model('MailboxKategori');
$this->load->library('Pagination');

$mailbox_kategori = new MailboxKategori();
$mailbox_kategori->selectByParams();

$statementcari= " AND EXISTS(SELECT X.JENIS_PELAYANAN_ID FROM MAILBOX_KATEGORI X WHERE A.JENIS_PELAYANAN_ID = X.JENIS_PELAYANAN_ID)";
$jenis_pelananan = new MailboxKategori();
$jenis_pelananan->selectByParamsJenisPelayan(array(), -1,-1, $statementcari);

if($reqJenisPelayananId == ""){}
else
$statement.= " AND D.JENIS_PELAYANAN_ID = ".$reqJenisPelayananId;

if($reqKategoriId == ""){}
else
$statement.= " AND A.MAILBOX_KATEGORI_ID = ".$reqKategoriId;

if($reqStatus == ""){}
else
$statement.= " AND CASE 
                WHEN A.STATUS = 0 OR A.STATUS IS NULL THEN '1'
                WHEN A.STATUS = 1 AND C.STATUS IS NULL THEN '2'
                WHEN A.STATUS = 1 AND C.STATUS = 1 THEN '3'
                WHEN C.STATUS = 2 THEN '4'
                ELSE '5'
                END = '".$reqStatus."'";

$mailbox = new Mailbox();
$showRecord = 20;
$pageView = "konsultasi_json/reloadajax/?reqInformasiKategori=".$reqInformasiKategori.'&reqJenisPelayananId='.$reqJenisPelayananId.'&reqKategoriId='.$reqKategoriId.'&reqStatus='.$reqStatus.'&reqTanggalMulai='.$reqTanggalMulai.'&reqTanggalAkhir='.$reqTanggalAkhir;
$rowCount = $mailbox->getCountByParams(array(), $statement);
$pagConfig = array('baseURL'=>$pageView, 'showRecord' => $showRecord, 'totalRows'=>$rowCount, 'perPage'=>$showRecord, 'contentDiv'=>'content');
$pagination =  new Pagination($pagConfig);  
$mailbox->selectByParams(array(), $showRecord, 0, $statement);

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
<body>
    
    <section class="topic-area">
    <div class="wrap" id="main-outlet">
        <div class="ember-view" id="suggested-topics"><h5 class="suggested-topics-title">Konsultasi</h5>

        <div class="search-topics">
            <div class="search-bar">
                <div class="row">
                    <div class="col s3">
                        <div class="row">
                            <div class="input-field col s6">
                                <button class="btn-primary btn no-text btn-icon ember-view" onClick="tambahData()"> Buat Pertanyaan</i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col s9">
                        <!-- <div class="row" style="float: right; width: 80%"> -->
                        <div class="row" style="float: right; width: 50%">
                            <!-- <div class="input-field col s2">
                                <label for="reqTanggalMulai">Tanggal Mulai</label>
                                <input placeholder data-options="validType:'dateValidPicker'" type="text" name="reqTanggalMulai" id="reqTanggalMulai" onKeyDown="return format_date(event,'reqTanggalMulai');"/>
                            </div>
                            <div class="input-field col s2">
                                <label for="reqTanggalAkhir">Tanggal Akhir</label>
                                <input placeholder data-options="validType:'dateValidPicker'" type="text" name="reqTanggalAkhir" id="reqTanggalAkhir" onKeyDown="return format_date(event,'reqTanggalAkhir');"/>
                            </div> -->
                            <!-- <div class="input-field col s7"> -->
                            <div class="input-field col s10">
                                <input placeholder="Ketik pertanyaan yang ingin di cari" type="text">
                            </div>
                            <div class="input-field col s1">
                                <button class="btn-primary btn no-text btn-icon ember-view"> Cari</i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s4">
                        <div class="row">
                            <div class="col s3 m1">Status</div>
                            <div class="col s2 m1 select-semicolon">:</div>
                            <div class="col s7 m10">
                                <select class="option-vw9" id="reqStatus">
                                    <option value="" <? if($reqStatus == "") echo "selected";?>>Semua</option>
                                    <option value="1" <? if($reqStatus == "1") echo "selected";?>>baru(belum dibuka)</option>
                                    <option value="2" <? if($reqStatus == "2") echo "selected";?>>sudah terbaca(belum balas)</option>
                                    <option value="3" <? if($reqStatus == "3") echo "selected";?>>dibalas(dibalas admin)</option>
                                    <option value="4" <? if($reqStatus == "4") echo "selected";?>>user respon(user membalas)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col s4">
                        <div class="row">
                            <div class="col s3 m4">Jenis Pelayanan</div>
                            <div class="col s2 m1 select-semicolon">:</div>
                            <div class="col s7 m7">
                                <select class="option-vw9" id="reqJenisPelayananId">
                                    <option value="" selected>Semua</option>
                                    <?
                                    while($jenis_pelananan->nextRow())
                                    {
                                    ?>
                                    <option value="<?=$jenis_pelananan->getField("JENIS_PELAYANAN_ID")?>" <? if($reqJenisPelayananId == $jenis_pelananan->getField("JENIS_PELAYANAN_ID")) echo "selected";?>><?=$jenis_pelananan->getField("NAMA")?></option>
                                    <?
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col s3">
                        <div class="row">
                            <div class="col s3 m3">Kategori</div>
                            <div class="col s2 m1 select-semicolon">:</div>
                            <div class="col s7 m7">
                                <select class="option-vw9" id="reqKategoriId">
                                    <option value="" selected>Semua</option>
                                    <?
                                    while($mailbox_kategori->nextRow())
                                    {
                                    ?>
                                    <option value="<?=$mailbox_kategori->getField("MAILBOX_KATEGORI_ID")?>" <? if($reqKategoriId == $mailbox_kategori->getField("MAILBOX_KATEGORI_ID")) echo "selected";?>><?=$mailbox_kategori->getField("NAMA")?></option>
                                    <?
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="topics" id="content">
        <div class="ember-view" id="content_data">
            <table class="topic-list ember-view" id="ember844">
            <thead>
                <tr>
                    <th data-sort-order="default" class="default">Pertanyaan</th>
                    <th data-sort-order="posts" class="posts num">Jenis Pelayanan</th>
                    <th data-sort-order="category" class="category">Kategori</th>
                    <th data-sort-order="category" class="category">Status</th>
                    <th data-sort-order="activity" class="activity num">Terakhir Update</th>
                </tr>
            </thead>
            <tbody>
                <?
                $i=0;
                while($mailbox->nextRow())
                {
                ?>
                <tr style="cursor: pointer;" onclick='document.location="app/loadUrl/app/konsultasi_detil?reqId=<?=$mailbox->getField("MAILBOX_ID")?>"'>
                    <td style="width: 30%"><span><?=$mailbox->getField("SUBYEK")?></span></td>
                    <td><span><?=$mailbox->getField("JENIS_PELAYANAN_NAMA")?></span></td>
                    <td><span><?=$mailbox->getField("MAILBOX_KATEGORI_NAMA")?></span></td>
                    <td><span><?=$mailbox->getField("STATUS_NAMA")?></span></td>
                    <td><span><?=getFormattedDateTime($mailbox->getField("TANGGAL"))?></span></td>
                </tr>
                <?
                $i++;
                }
                if($i == 0)
                {
                ?>
                <tr>
                    <td colspan="5">Data Tidak ada</td>
                </tr>
                <?
                }
                ?>
            </tbody>
            </table>
            <?=$pagination->createLinks()?>
        </div>
        </div>
    </div>
    </section>

    <style type="text/css">
    html {
        color: #222;
        font-family: Helvetica,Arial,sans-serif;
        font-size: 14px;
        line-height: 1.4;
        direction: ltr;
    }

    .wrap {
        margin-right: auto;
        margin-left: auto;
        padding: 0 8px;
    }

    .search-topics
    {
        width: 100%;
        margin-bottom: 20px;
        text-align: right;
    }

    .search.row .search-bar {
        display: flex;
        margin-bottom: 20px;
        width: 80%;
        /*max-width: 780px;*/
    }

    .search.row .search-bar input {
        /*height: 22px;*/
        /*padding-left: 6px;*/
        /*margin: 0 5px 0 0;*/
    }

    .btn-primary {
        border: none;
        font-weight: normal;
        color: #fff;
        background: #08c;
    }

    .btn {
        display: inline-block;
        margin: 0;
        /*padding: 6px 12px;*/
        font-weight: 500;
        font-size: 0.8em;
        line-height: 1.2;
        text-align: center;
        cursor: pointer;
        transition: all .25s;
    }

    #suggested-topics .suggested-topics-title {
        display: flex;
        align-items: center;
    }
    h1, h2, h3, h4, h5, h6 {
        margin-top: 0;
        margin-bottom: .5rem;
    }

    #suggested-topics table {
        margin-top: 10px;
    }

    .topic-list {
        margin: 0 0 10px;
    }

    .topic-list {
        width: 100%;
        border-collapse: collapse;
    }

    .topic-list th:first-of-type, .topic-list td:first-of-type {
        padding-left: 10px;
    }

    .topic-list th, .topic-list td {
        padding: 12px 5px;
    }

    .topic-list th {
        color: #919191;
        font-weight: normal;
        font-size: 1em;
    }

    .topic-list th, .topic-list td {
        line-height: 1.4;
        text-align: left;
        vertical-align: middle;
    }

    .topic-list {
        border-collapse: collapse;
    }

    .topic-list > tbody > tr:first-of-type {
        border-top: 3px solid #e9e9e9;
    }

    .topic-list > tbody > tr {
        border-bottom: 1px solid #e9e9e9;
    }

    .topic-list {
        border-collapse: collapse;
    }
    </style>

    <!--materialize js-->
    <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('select').material_select();

            $("#reqJenisPelayananId,#reqKategoriId,#reqStatus").change(function() { 
                setCariInfo();
            });

        });

        function setCariInfo()
        {
            var reqJenisPelayananId= reqKategoriId= reqStatus= "";
            reqJenisPelayananId= $("#reqJenisPelayananId").val();
            reqKategoriId= $("#reqKategoriId").val();
            reqStatus= $("#reqStatus").val();
            // alert(reqStatus);

            document.location.href = "app/loadUrl/app/konsultasi?reqJenisPelayananId="+reqJenisPelayananId+"&reqKategoriId="+reqKategoriId+"&reqStatus="+reqStatus+"&reqBreadCrum=<?=$reqBreadCrum?>";
        }

        $('.materialize-textarea').trigger('autoresize');
    </script>
</body>
</html>