<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$reqBreadCrum= $this->input->get("reqBreadCrum");
$reqId= $this->input->get("reqId");

$this->load->model('Mailbox');
$this->load->model('SatuanKerja');

$statement= "AND (A.STATUS IS NULL OR A.STATUS = 0) AND A.MAILBOX_ID = ".$reqId;
$set= new Mailbox();
$jumlah_data= $set->getCountByParams(array(), $statement);
$set->selectByParams(array("A.MAILBOX_ID"=>$reqId), -1,-1);
$set->firstRow();
// echo $set->query;exit();
$tempJudul= $set->getField("SUBYEK");
$tempTanggal= $set->getField("TANGGAL");
$tempIsi= $set->getField("ISI");
$tempJenisPelayananNama= $set->getField("JENIS_PELAYANAN_NAMA");
$reqMailBoxKategoriId= $set->getField("MAILBOX_KATEGORI_ID");
$tempMailboxKategoriNama= $set->getField("MAILBOX_KATEGORI_NAMA");
$tempStatusInfoId= $set->getField("STATUS_INFO_ID");
$tempInfoDetil= "Jenis Pelayanan : ".$tempJenisPelayananNama.", Kategori : ".$tempMailboxKategoriNama;

$reqMode= "insert";

// if($jumlah_data == 1)
// {
// 	$set->setField("FIELD", "STATUS");
// 	$set->setField("FIELD_VALUE", "1");
// 	$set->setField("MAILBOX_ID", $reqId);
// 	$set->updateByField();
// }

$statement= "AND A.STATUS = 1 AND A.MAILBOX_ID = ".$reqId;
$jumlah_status= $set->getCountByParamsMaxDetil($statement);
// echo $jumlah_status;exit();
//echo $set->query;exit;

$set->selectByParamsDetil(array("A.MAILBOX_ID"=>$reqId), -1,-1, "", "ORDER BY B.MAILBOX_DETIL_ID ASC");
// echo $set->query;exit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<title>Diklat</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="msapplication-tap-highlight" content="no">
	<meta name="description" content="Simpeg Jombang">
	<meta name="keywords" content="Simpeg Jombang">
	<title>Simpeg Jombang</title>
	<base href="<?=base_url()?>" />
	<link href="font/google-font.css" rel="stylesheet">

	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
	<script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
	<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>


	<!-- AUTO KOMPLIT -->
	<link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
	<script src="lib/autokomplit/jquery-ui.js"></script>
	
	<script type="text/javascript">	
		$(function(){
			$('#ff').form({
				url:'konsultasi_detil_json/add',
				onSubmit:function(){
					return $(this).form('validate');
				},
				success:function(data){
				    // alert(data);return false;
					data = data.split("-");
					rowid= data[0];
					infodata= data[1];
         			//$.messager.alert('Info', infodata, 'info');

         			if(rowid == "xxx")
         			{
         				mbox.alert(infodata, {open_speed: 0});
         			}
         			else
         			{
         				mbox.alert(infodata, {open_speed: 500}, interval = window.setInterval(function() 
         				{
         					top.setInformasi();
							clearInterval(interval);
         					mbox.close();
         					document.location.href= "app/loadUrl/app/konsultasi_detil?reqId=<?=$reqId?>";
         				}, 1000));
						$(".mbox > .right-align").css({"display": "none"});
         			}
				}
			});

			

		});
		
		function setSimpan()
		{
			$("#ff").submit();
			return false;
		}
	</script>
	<link rel="stylesheet" type="text/css" href="css/gaya.css">

	<!-- CORE CSS-->    
	<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
	<link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
	<!-- CSS style Horizontal Nav-->    
	<link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
	<!-- Custome CSS-->    
	<link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

	<!-- <link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/dataTables.materialize.css"> -->
	<?php /*?><link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/dataTables.material.min.css"><?php */?>
    
    <link href="lib/mbox/mbox.css" rel="stylesheet">
  	<script src="lib/mbox/mbox.js"></script>
    <link href="lib/mbox/mbox-modif.css" rel="stylesheet">
	<script src="lib/ckeditor-simple/ckeditor.js"></script>

</head>
<body>
	<section class="topic-area">
    <div class="wrap" id="main-outlet">
		<div class="topic-category ember-view" id="ember779">
			<h5 class="suggested-topics-title" style=" background-color: #605ca896; margin-top: 8px; "><?=$tempJudul?></h5>
			<div class="topic-header-extra">
				<div class="list-tags">
					<div class="discourse-tags" style="font-size: 10px; margin-bottom: 10px"><?=$tempInfoDetil?></div>
				</div>
			</div>
		</div>

		<?
		$reqStatus=0;
		$index=1;
		while($set->nextRow())
		{
			$satuanKerjaJawabId= $set->getField("SATUAN_KERJA_ID");
			$satuanKerjaAsalId= $set->getField("SATUAN_KERJA_ASAL_ID");
			$satuanKerjaTujuanId= $set->getField("SATUAN_KERJA_TUJUAN_ID");
			$tipeJawabId= $set->getField("TIPE");
	    ?>
	        <article class="boxed onscreen-post">
				<div class="row">
					<div class="topic-body clearfix" style=" background-color: #7373734d;  background-size: 300px 300px; ">
						<div class="topic-meta-data">
							<div class="names trigger-user-card">
								<span class="first username"><?=$set->getField("PEGAWAI_NAMA2")?></span>
							</div>
							<div class="post-info post-date">
								<span class="relative-date" data-format="tiny" data-time="1494259572538" title="May 8, 2017 11:06 pm"><?=getFormattedDateTime($set->getField("TANGGAL_DETIL"))?></span>
							</div>
						</div>
						<div class="regular contents">
							<div class="cooked">
								<?=$set->getField("ISI_DETIL")?>
							</div>
						</div>

					</div>
				</div>
				<div class="topic-status-info ember-view"></div>
			</article>
		<?
		$reqStatus++;
		if($reqStatus == 3)
			$reqStatus=1;
		}
		?>

		<?

		$skerja= new SatuanKerja();
		$reqSatuanKerjaInfoId= $skerja->getSatuanKerja($satuanKerjaJawabId);
		$reqSatuanKerjaInfoId= explode(",", $reqSatuanKerjaInfoId);
		// print_r($reqSatuanKerjaInfoId);exit();
		unset($skerja);

		if (in_array($this->SATUAN_KERJA_ID, $reqSatuanKerjaInfoId) || ($this->SATUAN_KERJA_ID == "" && $reqStatus == "1")){}
		else
		{
		?>
		<div class="row">
			<div class="input-field col s12 m12">
				<button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali" onclick="location.href='app/loadUrl/app/konsultasi'">Kembali
					<i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
				</button>
			</div>
		</div>
		<?
		}
		?>

		<?
		// //kalau dari user
		// $reqStatus= 2;
		// //kalau dari admin
		// $reqStatus= 1;

			// $reqStatus= 2;


		if (in_array($this->SATUAN_KERJA_ID, $reqSatuanKerjaInfoId) || ($this->SATUAN_KERJA_ID == "" && $reqStatus == "1")) 
		{
			// kalau 1 maka yg jawab admin dan status = 1
			if($tipeJawabId == "1")
			{
				$reqStatus= 1;
				$reqTipe= 2;
				$reqSatuanKerjaId= $satuanKerjaAsalId;
			}
			else
			{
				$reqStatus= 2;
				$reqTipe= 1;
				$reqSatuanKerjaId= $satuanKerjaTujuanId;
			}
		?>
		<form id="ff" method="post"  novalidate enctype="multipart/form-data">
			<div class="row">
				<label for="reqNama" class="col s12 m2 label-control">Respon</label>
				<div class="input-field col s12 m10">
					<textarea name="reqKeterangan" id="reqKeterangan" style="width:100%; height:100%"><?=$reqKeterangan?></textarea>
				</div>
			</div>
			<article class="boxed onscreen-post">
				<div class="row">
				<div class="topic-body clearfix" >
					<div class="topic-meta-data">
						<div class="names trigger-user-card">
							<span class="first username">   <a href="" class="">  </a> </span>
						</div>
						<div class="post-info post-date">
							<div class="input-field col s12 m12">
								<button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali" onclick="location.href='app/loadUrl/app/konsultasi'">Kembali
			                      <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
			                    </button>
								<input type="hidden" name="reqId" value="<?=$reqId?>" />
								<input type="hidden" name="reqMailBoxKategoriId" value="<?=$reqMailBoxKategoriId?>" />
								<input type="hidden" name="reqSatuanKerjaId" value="<?=$reqSatuanKerjaId?>" />
								<input type="hidden" name="reqTipe" value="<?=$reqTipe?>" />
								<input type="hidden" name="reqStatus" value="<?=$reqStatus?>" />
								<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
								<input type="submit" name="reqSubmit"  class="btn green" value="Submit" />
							</div>
						</div>
					</div>
				</div>
				</div>
			</article>
	 	</form>
	 	<?
		}
		?>
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

    .topic-post article.boxed {
	    position: relative;
	}

	.boxed {
	    height: 100%;
	}



	.topic-avatar {
	    border-top: 1px solid #e9e9e9;
	    padding-top: 15px;
	    width: 45px;
	    float: left;
	    z-index: 2;
	}

	.topic-avatar, .user-card-avatar {
	    position: relative;
	}

	.topic-body {
	    /*width: 690px;*/
	    width: 100%;
	    float: left;
	    position: relative;
	    z-index: 1;
	    border-top: 1px solid #e9e9e9;
	    padding: 12px 11px 0 11px;
	}

	.topic-body .regular {
	    margin-top: 35px;
	}

	.cooked, .d-editor-preview {
	    word-wrap: break-word;
	    line-height: 1.4;
	}

	.names {
	    float: left;
	}

	.names span {
	    font-size: 1em;
	    margin-right: 8px;
	    display: inline-block;
	    max-width: 280px;
	    white-space: nowrap;
	    overflow: hidden;
	    text-overflow: ellipsis;
	}

	.names span a {
	    color: #646464;
	}

	.topic-meta-data .post-info {
	    display: inline-block;
	    float: right;
	}

	.read-state.read {
	    opacity: 0;
	    transition: opacity ease-out 1s;
	}

	.read-state {
	    color: #6cf;
	    position: absolute;
	    right: 0;
	    top: 2em;
	    font-size: 0.571em;
	}

	.wrap .contents {
	    position: relative;
	}

	.post-actions {
	    -webkit-user-select: none;
	    -moz-user-select: none;
	    -ms-user-select: none;
	    clear: both;
	    text-align: right;
	    margin-bottom: 10px;
	}

	.post-links-container {
	    -webkit-user-select: none;
	    -moz-user-select: none;
	    -ms-user-select: none;
	    clear: both;
	}

	.topic-status-info {
	    border-top: 1px solid #e9e9e9;
	    padding: 10px 0;
	    height: 20px;
	    /*max-width: 757px;*/
	}

    </style>

    <script>
        // Replace the <textarea id="reqKeterangan"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace( 'reqKeterangan',
		{
			height: '80px',
		} );
    </script>
    <!--materialize js-->
    <!-- <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script> -->
    <script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('select').material_select();
        });

        $('.materialize-textarea').trigger('autoresize');
    </script>



    <script type="text/javascript">
		$(document).ready( function () {
			$('select').material_select();
		});
	</script>
</body>
</html>