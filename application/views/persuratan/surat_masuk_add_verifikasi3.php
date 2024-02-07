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
	
	/** Style for the body **/
 body {
    font: 12px Tahoma, Arial, Helvetica, Sans-Serif;
}
/** Style for the button & div **/
 .myButton {
    padding: .2em 1em;
    font-size: 1em;
}
.mySelect {
    padding: .2em 0;
    font-size: 1em;
}
#myDiv {
    color:Green;
    background-color:#eee;
    border:2px solid #333;
    display:none;
    text-align:justify;
}
#myDiv p {
    margin: 15px;
    font-size: 0.917em;
}
/** Style for the cointainer **/
 #body {
    clear: both;
    margin: 0 auto;
    max-width: 534px;
}
html, body {
    background-color:White;
}
hr {
    margin-bottom:40px;
}

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
          
          
          <div id="body">
    
<h2>Slide toggle from right to left and left to right.</h2>

    <hr/>
    <p>
        <select class="mySelect">
            <option value="right">Right</option>
            <option value="left">Left</option>            
            <option value="up">Up</option>
            <option value="down">Down</option>
        </select>
        <button id="button" class="myButton">Run Effect</button>
    </p>
    <div id="myDiv">
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
        <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
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
$(".myButton").click(function () {

    // Set the effect type
    var effect = 'slide';

    // Set the options for the effect type chosen
    var options = { direction: $('.mySelect').val() };

    // Set the duration (default: 400 milliseconds)
    var duration = 500;

    $('#myDiv').toggle(effect, options, duration);
});

</script>
</body>
</html>