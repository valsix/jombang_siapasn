<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
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

$arrInfo="";
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

if($jumlah_info > 0)
{
	$tempInfoJudul= $arrInfo[0]["JENIS_PELAYANAN_NAMA"];
}
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
	
	.wrapper .active { color: red; }
  </style>
</head>

<body>
 <div id="basic-form" class="section">
  <div class="row">
    <div class="col s12 m10 offset-m1 ">
      <ul class="collection card">
        <li class="collection-item active green"><?=$tempInfoJudul?></li>
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
                	<th class="green-text material-font">Ijin Belajar</th>
                    <th class="green-text material-font">:</th>
                    <th class="green-text material-font" style="width:15%"><?=$reqPendidikanNamaUsulan?></th>
                    <th class="green-text material-font" style="width:10%">Jurusan</th>
                    <th class="green-text material-font" style="width:1%">:</th>
                    <th class="green-text material-font"><?=$reqPendidikanJurusanUsulan?></th>
                </tr>
            </thead>
          </table>
          
          <form id="ff" method="post"  novalidate enctype="multipart/form-data">
          
          
          <div class="wrapper">
    <div class="tabs">
        <span class="tab">Вкладка 1</span>
        <span class="tab">Вкладка 2</span>
        <span class="tab">Вкладка 3</span>        
    </div>
    <div class="tab_content">
        <div class="tab_item animated fadeOutLeftBig">Содержимое 1</div>
        <div class="tab_item animated fadeOutLeftBig">Содержимое 2</div>
        <div class="tab_item animated fadeOutLeftBig">Содержимое 3</div>
    </div>
</div>

          
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
          </form>
           
       </li>
     </ul>
   </div>
 </div>
</div>

<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

<script>
$(".tab_item").not(":first").hide();
$(".wrapper .tab").click(function() {
	$(".wrapper .tab").removeClass("active").eq($(this).index()).addClass("active");
	//$(".tab_item").addClass("fadeOutLeftBig").hide("fast").eq($(this).index()).switchClass("fadeOutLeftBig", "fadeInRightBig").fadeIn("fast")
	$(".tab_item").addClass("fadeOutLeftBig").hide("fast").eq($(this).index()).addClass('fadeInRightBig').removeClass('fadeOutLeftBig').fadeIn("fast")
	
	
}).eq(0).addClass("active");

</script>
</body>
</html>