<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/SuratMasukPegawaiCheck');
$this->load->model('persuratan/SuratMasukPegawai');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqBreadCrum= $this->input->get("reqBreadCrum");
$reqJenis= $this->input->get("reqJenis");
$reqJenisNama= setjenisinfo($reqJenis);

$statement= " AND SMP.SURAT_MASUK_PEGAWAI_ID = ".$reqRowId;
$set= new SuratMasukPegawai();
$set->selectByParamsMonitoring(array(), -1, -1, $statement);
$set->firstRow();
//echo $set->query;exit;
$reqPegawaiNama= $set->getField('NAMA_LENGKAP');
$reqPegawaiJabatan= $set->getField('JABATAN_RIWAYAT_NAMA');
$reqPendidikanNamaUsulan= $set->getField('PENDIDIKAN_NAMA_US');
$reqPendidikanJurusanUsulan= $set->getField('JURUSAN_US');
unset($set);

$arrInfo= [];
$index_data= 0;
$statement= " AND A.JENIS_PELAYANAN_ID = ".$reqJenis." AND A.TIPE = '0'";
$statementCheck= " AND B.SURAT_MASUK_PEGAWAI_ID = ".$reqRowId;
$set= new SuratMasukPegawaiCheck();
$set->selectByParamsMonitoringCheck(array(), -1,-1, $statement, $statementCheck);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrInfo[$index_data]["JENIS_PELAYANAN_NAMA"] = $set->getField("JENIS_PELAYANAN_NAMA");
	$arrInfo[$index_data]["NAMA"] = $set->getField("NAMA");
	$index_data++;
}
$jumlah_info= $index_data;
//print_r($arrInfo);exit;
if($jumlah_info > 0)
{
	$tempInfoJudul= $arrInfo[0]["JENIS_PELAYANAN_NAMA"];
}
//echo $jumlah_info;exit;
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="msapplication-tap-highlight" content="no">
  <meta name="description" content="Simpeg Jombang">
  <meta name="keywords" content="Simpeg Jombang">
  <title>Simpeg Jombang</title>
  <base href="<?=base_url()?>" />


  <link rel="stylesheet" type="text/css" href="css/gaya.css">

  <link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
  <script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script>
  <script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
  
  <!-- AUTO KOMPLIT -->
  <link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
  <script src="lib/autokomplit/jquery-ui.js"></script>
    
  <!-- SIMPLE TAB -->
  <script type="text/javascript" src="lib/simpletabs_v1.3/js/simpletabs_1.3.js"></script>
  <link href="lib/simpletabs_v1.3/css/simpletabs.css" type="text/css" rel="stylesheet">

  <!-- CORE CSS-->    
  <link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
  <!-- CSS style Horizontal Nav-->    
  <link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
  <!-- Custome CSS-->    
  <link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

  <?php /*?><style type="text/css">
    @media 
    only screen and (max-width: 760px),
    (min-device-width: 768px) and (max-device-width: 1024px)  {
      table.tabel-responsif thead th{ display:none; }
      <?
      $arrData= array("Persyaratan", "Checklist", "File");

      for($i=0; $i < count($arrData); $i++)
      {
        $index= $i+1;
        ?>
        table.tabel-responsif td:nth-of-type(<?=$index?>):before { content: "<?=$arrData[$i]?>"; }
        <? 
      }  ?>
    }

    .round {
      border-radius: 50%;
      padding: 5px;
    }
  </style><?php */?>
  
  <style>
  	td, th {
		padding: 5px 5px;
		display: table-cell;
		text-align: left;
		vertical-align: middle;
		border-radius: 2px;
	}
	
	.carousel .carousel-item{
		width:100%;
	}
	
	
	/* CSS for responsive iframe */
/* ========================= */

/* outer wrapper: set max-width & max-height; max-height greater than padding-bottom % will be ineffective and height will = padding-bottom % of max-width */
#Iframe-Master-CC-and-Rs {
  *max-width: 512px;
  max-height: 100%; 
  overflow: hidden;
}

/* inner wrapper: make responsive */
.responsive-wrapper {
  position: relative;
  height: 0;    /* gets height from padding-bottom */
  
  /* put following styles (necessary for overflow and scrolling handling on mobile devices) inline in .responsive-wrapper around iframe because not stable in CSS:
    -webkit-overflow-scrolling: touch; overflow: auto; */
  
}
 
.responsive-wrapper iframe {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  
  margin: 0;
  padding: 0;
  border: none;
}

/* padding-bottom = h/w as % -- sets aspect ratio */
/* YouTube video aspect ratio */
.responsive-wrapper-wxh-572x612 {
  padding-bottom: 107%;
}

/* general styles */
/* ============== */
.set-border {
  border: 5px inset #4f4f4f;
}
.set-box-shadow { 
  -webkit-box-shadow: 4px 4px 14px #4f4f4f;
  -moz-box-shadow: 4px 4px 14px #4f4f4f;
  box-shadow: 4px 4px 14px #4f4f4f;
}
.set-padding {
  padding: 40px;
}
.set-margin {
  margin: 30px;
}
.center-block-horiz {
  margin-left: auto !important;
  margin-right: auto !important;
}

.carousel {
    height: 100vh !important;
    width: 100%;
    overflow:hidden;
}
.carousel .carousel-inner {
    height:100% !important;
}

  </style>
</head>

<body>
 <div id="basic-form" class="section">
  <div class="row">
    <div class="col s12 m10 offset-m1 ">
      <ul class="collection card">
        <li class="collection-item ubah-color-warna"><?=$tempInfoJudul?></li>
        <li class="collection-item">
          <table class="bordered striped md-text table_list tabel-responsif">
          	<thead> 
            	<tr>
                	<th class="green-text material-font" style="width:10%">Nama</th>
                    <th class="green-text material-font" style="width:1%">:</th>
                    <th colspan="3" class="green-text material-font"><?=$reqPegawaiNama?></th>
                </tr>
                <tr>
                	<th class="green-text material-font">Jabatan</th>
                    <th class="green-text material-font">:</th>
                    <th colspan="3" class="green-text material-font"><?=$reqPegawaiJabatan?></th>
                </tr>
                <tr>
                	<th class="green-text material-font">Pendidikan Diusulkan</th>
                    <th class="green-text material-font">:</th>
                    <th class="green-text material-font" style="width:15%"><?=$reqPendidikanNamaUsulan?></th>
                    <th class="green-text material-font" style="width:10%">Jurusan Diusulkan</th>
                    <th class="green-text material-font" style="width:1%">:</th>
                    <th class="green-text material-font"><?=$reqPendidikanJurusanUsulan?></th>
                </tr>
            </thead>
          </table>
          
          <form id="ff" method="post"  novalidate enctype="multipart/form-data">
          <div id="resize">
            <div id="tabs" style="display:none; height:100%;">
            <ul id="tabs-swipe-demo" class="tabs" <?php /*?>style="display:none"<?php */?>>
                <li class="tab col s3"><a class="active" href="#swipe-1">Test 1</a></li>
                <li class="tab col s3"><a href="#swipe-2">Test 2</a></li>
            </ul>
            </div>
            
            <main>
            <div id="swipe-1" class="col s12 " style=" <?php /*?>height:auto !important; max-height: 400px; overflow-y: auto;<?php */?>">
            	<table class="bordered striped md-text table_list tabel-responsif" id="link-table" >
                <thead> 
                  <tr class="ubah-color-warna">
                    <th class="white-text material-font">Persyaratan</th>
                    <th class="white-text material-font" style="width:5%">Checklist</th>
                    <th class="white-text material-font" style="width:5%; text-align:center">File</th>
                  </tr>
                </thead>
                <tbody>
                <?
				for($index=0; $index < $jumlah_info; $index++)
				{
                ?>
                    <tr>
                      <td><?=$arrInfo[$index]["NAMA"]?></td>
                      <td style="text-align:center">
                      <input type="checkbox" id="reqStatusCheckBoxFix<?=$index?>" <?=$tempRequiredOtomatis?>
                	  class="easyui-validatebox" data-options="validType:'checkedEasyui[\'#reqStatusCheckBoxFix<?=$index?>\']'" <?=$tempInfoChecked?> />
                      <label for="reqStatusCheckBoxFix<?=$index?>"></label>
                      </td>
                      <td style="text-align:center">
                        <a href="javascript:void(0)" title="Ubah" onClick="setpdf()">
                        	<img src="images/iconviewpdf.png" style="width:25px" height="25px" />
                        </a>
                      </td>
                   </tr>
                <?
                }
                ?>
               </tbody>
             </table>
            </div>
            </main>
            
            <main>
            <div id="swipe-2" class="col s12" style="height:auto !important">
            	<?php /*?><?php
				$reqUrlFile= "uploads/3/726d2550c3899c6c5683e376d8fc34d7.pdf";
				//$reqUrlFile= "uploads/3/c95bd2d2a2ee5471550aa470e446ce39.pdf";
				function pdf_h($pdfname) {
					$pdftext = file_get_contents($pdfname);
					$num = preg_match_all("/\/Page\W/", $pdftext, $dummy);
					return $num*1300;
				}
				?><?php */?>
				<?php /*?><div class="input-field col s12" style="min-height:<?=pdf_h($reqUrlFile)?>px;"><?php */?>
                <?php /*?><div class="input-field col s12">
					<iframe src="<?=$reqUrlFile?>" id="mainFrame" onload="iframeLoaded()" style="top: 0; left: 0; width: 100%; height: 100%; margin: 0; padding: 0; border: none;" scrolling="no"></iframe>
				</div><?php */?>
                
                <button class="btn red waves-effect waves-light" style="font-size:9pt" type="button" id="tambah" onClick="kembalitab()">Kembali
                   <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                </button>
                
                <iframe src="<?=$reqUrlFile?>" id="mainframe"></iframe>
                
                <?php /*?><div id="Iframe-Master-CC-and-Rs" class="set-margin set-padding set-border set-box-shadow center-block-horiz">
                  <div class="responsive-wrapper responsive-wrapper-wxh-572x612" style="-webkit-overflow-scrolling: touch; overflow: auto;">
                    <iframe src="<?=$reqUrlFile?>" id="mainframe"> 
                      <p style="font-size: 110%;"><em><strong>ERROR: </strong>  
                An &#105;frame should be displayed here but your browser version does not support &#105;frames. </em>Please update your browser to its most recent version and try again.</p>
                    </iframe>
                    
                  </div>
                </div><?php */?>

			</div>
            </main>
          
          <?php /*?><div class="simpleTabs" style="margin-left:-9px !important">
            <ul class="simpleTabsNavigation">
                <li><a href="#pribadi" onClick="javascript:void()" id="btnReload">Persyaratan</a></li>
                <li><a href="#file" onClick="javascript:void()" id="btnFile">File</a></li>
            </ul>
            
            <div class="simpleTabsContent" id="pribadi">
              <table class="bordered striped md-text table_list tabel-responsif" style="margin-top:20px" id="link-table">
                <thead> 
                  <tr class="green">
                    <th class="white-text material-font">Persyaratan</th>
                    <th class="white-text material-font" style="width:10%">Checklist</th>
                    <th class="white-text material-font" style="width:5%; text-align:center">File</th>
                  </tr>
                </thead>
                <tbody>
                <?
				for($index=0; $index < $jumlah_info; $index++)
				{
                ?>
                    <tr>
                      <td><?=$arrInfo[$index]["NAMA"]?></td>
                      <td style="text-align:center">
                      <input type="checkbox" id="reqStatusCheckBoxFix<?=$index?>" <?=$tempRequiredOtomatis?>
                	  class="easyui-validatebox" data-options="validType:'checkedEasyui[\'#reqStatusCheckBoxFix<?=$index?>\']'" <?=$tempInfoChecked?> />
                      <label for="reqStatusCheckBoxFix<?=$index?>"></label>
                      </td>
                      <td style="text-align:center">
                        <a href="javascript:void(0)" class="round waves-effect waves-light blue white-text" title="Ubah" onClick="parent.setload('pegawai_add_pendidikan_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>')">
                        	<i class="mdi-action-pageview"></i>
                        </a>
                      </td>
                   </tr>
                <?
                }
                ?>
               </tbody>
             </table>
           </div>
           
           <div class="simpleTabsContent" id="file">
           	<?php
			$reqUrlFile= "uploads/3/726d2550c3899c6c5683e376d8fc34d7.pdf";
			function pdf_h($pdfname) {
				$pdftext = file_get_contents($pdfname);
				$num = preg_match_all("/\/Page\W/", $pdftext, $dummy);
				return $num*1300;
			}
			?>
			<div class="input-field col s12" style="min-height:<?=pdf_h($reqUrlFile)?>px;">
				<iframe src="<?=$reqUrlFile?>" id="mainFrame" onload="iframeLoaded()" style="top: 0; left: 0; width: 100%; height: 100%; margin: 0; padding: 0; border: none;" scrolling="no"></iframe>
			</div>
           </div>
           
          </div> <?php */?>
          </div>
          </form>
           
       </li>
     </ul>
   </div>
 </div>
</div>

<link href="lib/materializetemplate/css/materializeslide.css" rel="stylesheet" />
<?php /*?><script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>
<?php */?>
<script src="lib/materializetemplate/js/materializeslide.min.js"></script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

<?php /*?><script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script><?php */?>

<script>
function kembalitab()
{
	$('ul.tabs').tabs('select_tab', 'swipe-1');
}

function setpdf()
{
	$("#mainframe").attr('src',"uploads/3/c95bd2d2a2ee5471550aa470e446ce39.pdf");
	//$('ul.tabs').tabs('swipeable', true);
	$('ul.tabs').tabs('select_tab', 'swipe-2');
}

$(document).ready( function () {
	//parent.iframeLoadedx();
	
	/*var height = 0;
	$("main").each(function( index ) {
	  if(height<$(this).height()){
		height = $(this).height()
	  }
	});
	$("main").height(height);*/
	
	//$('ul.tabs').tabs({
	$("#tabs").tabs({
	  //swipeable : true,
	  swipeable: true
	  ,heightStyle: "fill"
	  //,fxAutoHeight: true
	  //, responsiveThreshold : Infinity
	});
	
	/*$('#resize').resizable({
	 handles: 's',
	 alsoResize: '.ui-tabs-panel'
    });*/

	//$("ul.tabs a").css('height', $("ul.tabs").height());
});
</script>
</body>
</html>