<?
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model("Menu");

$reqId = $this->input->get("reqId");
$reqData = $this->input->get("reqData");
$CI->checkpegawai($reqId);

$index_set=0;
$arrMenu="";
$set= new Menu();
//selectByParamsMenu(1, $akses_id, "AKSES_APP_ZIS", " AND A.MENU_PARENT_ID = '".$id_induk."'");
$set->selectByParamsMenu(1, 1, "AKSES_APP_SIMPEG", " AND A.STATUS = 1", "ORDER BY A.URUT");
//echo $set->query;exit;
while($set->nextRow())
{
	$arrMenu[$index_set]["MENU_ID"]= $set->getField("MENU_ID");
	$arrMenu[$index_set]["MENU_PARENT_ID"]= $set->getField("MENU_PARENT_ID");
	$arrMenu[$index_set]["NAMA"]= $set->getField("NAMA");
	$arrMenu[$index_set]["LINK_FILE"]= $set->getField("LINK_FILE");
	$arrMenu[$index_set]["LINK_DETIL_FILE"]= $set->getField("LINK_DETIL_FILE");
	$arrMenu[$index_set]["AKSES"]= $set->getField("AKSES");
	$arrMenu[$index_set]["JUMLAH_MENU"]= $set->getField("JUMLAH_MENU");
	$arrMenu[$index_set]["JUMLAH_DISABLE"]= $set->getField("JUMLAH_DISABLE");
	$index_set++;
}
$jumlah_menu= $index_set;
unset($set);
//print_r($arrMenu);exit;
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<meta name="viewport" content="initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,width=device-width,user-scalable=0"/>
<title>A.I.M SYSTEM</title>
<base href="<?=base_url()?>">

 <!-- Bootstrap Core CSS -->
<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- MetisMenu CSS -->
<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
<!-- Timeline CSS -->
<link href="lib/startbootstrap-sb-admin-2-1.0.7/dist/css/timeline.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="lib/startbootstrap-sb-admin-2-1.0.7/dist/css/sb-admin-2.css" rel="stylesheet">
<!-- Morris Charts CSS -->
<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/morrisjs/morris.css" rel="stylesheet">
<!-- Custom Fonts -->
<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

<script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/jquery/dist/jquery.min.js"></script>	
<!-- Bootstrap Core JavaScript -->
<script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Metis Menu Plugin JavaScript -->
<script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/metisMenu/dist/metisMenu.min.js"></script>
<!-- Custom Theme JavaScript -->
<script src="lib/startbootstrap-sb-admin-2-1.0.7/dist/js/sb-admin-2.js"></script>

<!-- eModal -->
<script src="lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal.min.js"></script>
<script type="text/javascript">
    function createWindow(page) {
        eModal.iframe(page, 'SIMPEG KABUPATEN JOMBANG')
    }
</script>
    
<!---->
<link rel="stylesheet" href="css/admin.css" type="text/css">

<style>

</style>

<script type="text/javascript">
function executeOnClick(varItem){
	$("a").removeClass("aktif");
	if(varItem == ''){}
	<?
	for($index_loop=0; $index_loop < $jumlah_menu; $index_loop++)
    {
		$tempMenuId= $arrMenu[$index_loop]["MENU_ID"];
		$arrMenu[$index_loop]["MENU_PARENT_ID"];
		$tempNama= $arrMenu[$index_loop]["NAMA"];
		$tempLinkFile= $arrMenu[$index_loop]["LINK_FILE"];
		$tempLinkDetilFile= $arrMenu[$index_loop]["LINK_DETIL_FILE"];
	?>
	else if(varItem == '<?=$tempMenuId?>'){
		$("#<?=$tempMenuId?>").addClass("aktif");
		$('#<?=$tempMenuId?>').css({'background-position': '0 -27px'});
		
		<?
		if($tempLinkDetilFile == "")
		{
		?>
		mainFrame.location.href='app/loadUrl/app/<?=$tempLinkFile?>/?reqId=<?=$reqId?>';
		mainFrameDetil.location.href='';
		document.getElementById('trdetil').style.display = 'none';	
		<?
		}
		else
		{
		?>
		mainFrame.location.href='app/loadUrl/app/<?=$tempLinkFile?>/?reqId=<?=$reqId?>';
		mainFrameDetil.location.href='app/loadUrl/app/<?=$tempLinkDetilFile?>/?reqId=<?=$reqId?>';
		document.getElementById('trdetil').style.display = '';	
		<?
		}
		?>
	}
	<?
	}
	?>
	return true;
}
</script>
<link rel="stylesheet" type="text/css" href="css/gaya.css">


</head>

<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0">
	<div id="wrapper" class="bg-kiri-popup" style="overflow:hidden;">
            <!-- /.navbar-header -->
            <div class="navbar-default sidebar" role="navigation" style="margin-top:0px;">
            	
                <a class="menu-kiri-popup" data-flexmenu="flexmenu2" data-dir="h" data-offsets="8,0"><i class="fa fa-bars"></i></a>
                <!-- MOBILE VERSION -->
                <ul id="flexmenu2" class="flexdropdownmenu">
                    <li><a href="#">Data Administrasi Umum</a>
                        <ul>
                        <li><a onclick="closePopupMenu()" href="app/loadUrl/main/daftar_rekanan_administrasi_umum/?reqId=<?=$reqId?>" target="popupFrame">Umum</a></li>
                        <li><a onclick="closePopupMenu()" href="app/loadUrl/main/daftar_rekanan_surat_pernyataan_rekanan/?reqId=<?=$reqId?>" target="popupFrame">Surat Pernyataan Rekanan</a></li>
                        <li><a onclick="closePopupMenu()" href="app/loadUrl/main/daftar_rekanan_administrasi_ijin/?reqId=<?=$reqId?>" target="popupFrame">Izin Usaha</a>
                        <li><a onclick="closePopupMenu()" class="crot" href="app/loadUrl/main/daftar_rekanan_administrasi_sbu/?reqId=<?=$reqId?>" target="popupFrame">Sertifikat Badan Usaha</a></li>
                        <li><a onclick="closePopupMenu()" href="app/loadUrl/main/daftar_rekanan_administrasi_landasan/?reqId=<?=$reqId?>" target="popupFrame">Landasan Hukum</a></li>
                        <li><a onclick="closePopupMenu()" href="app/loadUrl/main/daftar_rekanan_administrasi_pengurus/?reqId=<?=$reqId?>" target="popupFrame">Pengurus Perusahaan</a></li>
                        <li><a onclick="closePopupMenu()" href="app/loadUrl/main/daftar_rekanan_administrasi_keuangan/?reqId=<?=$reqId?>" target="popupFrame">Kepemilikan Saham</a></li>
                        <li><a onclick="closePopupMenu()" href="app/loadUrl/main/daftar_rekanan_administrasi_surat_domisili/?reqId=<?=$reqId?>" target="popupFrame">Surat Domisili</a></li>
                        <li><a onclick="closePopupMenu()" href="app/loadUrl/main/daftar_rekanan_administrasi_tdp/?reqId=<?=$reqId?>" target="popupFrame">Tanda Daftar Perusahaan</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Data Keuangan</a>
                        <ul>
                        <li><a onclick="closePopupMenu()" href="app/loadUrl/main/daftar_rekanan_keuangan_rekening/?reqId=<?=$reqId?>" target="popupFrame">Rekening Koran</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Data Perpajakan </a>
                        <ul>
                        <li><a onclick="closePopupMenu()" href="app/loadUrl/main/daftar_rekanan_pajak_pkp/?reqId=<?=$reqId?>" target="popupFrame">PKP</a></li>
                        <li><a onclick="closePopupMenu()" href="app/loadUrl/main/daftar_rekanan_pajak_spt_tahunan/?reqId=<?=$reqId?>" target="popupFrame">SPT Tahunan</a></li>
                        <li><a onclick="closePopupMenu()" href="app/loadUrl/main/daftar_rekanan_pajak_bulanan/?reqId=<?=$reqId?>" target="popupFrame">SPT Masa (PPH/PPN)</a></li>
                        <li><a onclick="closePopupMenu()" href="app/loadUrl/main/daftar_rekanan_pajak_neraca/?reqId=<?=$reqId?>" target="popupFrame">Neraca</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Data Teknis</a>
                        <ul>
                        <li><a onclick="closePopupMenu()" href="app/loadUrl/main/daftar_rekanan_teknis_tenaga/?reqId=<?=$reqId?>" target="popupFrame">Tenaga Ahli</a></li>
                        <li><a onclick="closePopupMenu()" href="app/loadUrl/main/daftar_rekanan_teknis_pengalaman/?reqId=<?=$reqId?>" target="popupFrame">Pengalaman</a></li>
                        <li><a onclick="closePopupMenu()" href="app/loadUrl/main/daftar_rekanan_teknis_peralatan/?reqId=<?=$reqId?>" target="popupFrame">Peralatan</a></li>
                        <li><a onclick="closePopupMenu()" href="app/loadUrl/main/daftar_rekanan_teknis_sertifikat/?reqId=<?=$reqId?>" target="popupFrame">Sertifikat Lain</a></li>
                        </ul>
                    </li>
                </ul>
                <!-- END MOBILE VERSION -->
                
                
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li style="border:none;">
							<?
							function getMenuByParent($id_induk, $arrMenu)
							{
								$arrayKey= '';
								$arrayKey= in_array_column($id_induk, "MENU_PARENT_ID", $arrMenu);
								//print_r($arrayKey);exit;
								if($arrayKey == ''){}
								else
								{
							?>
							<div id="menu-kiri">
                            <div>
							<?
									for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
									{
										$index_row= $arrayKey[$index_detil];
										$tempMenuId= $arrMenu[$index_row]["MENU_ID"];
										$arrMenu[$index_row]["MENU_PARENT_ID"];
										$tempNama= $arrMenu[$index_row]["NAMA"];
										$tempLinkFile= $arrMenu[$index_row]["LINK_FILE"];
										$arrMenu[$index_row]["AKSES"];
										$tempJumlahMenu= $arrMenu[$index_row]["JUMLAH_MENU"];
										$tempJumlahDisable= $arrMenu[$index_row]["JUMLAH_DISABLE"];
							?>
									<a id="<?=$tempMenuId?>" onClick="executeOnClick('<?=$tempMenuId?>');" class=""><?=$tempNama?></a>
							<?
									}
								}
							?>
                            </div>
							</div>
							<?
							}
                            $arrayKey= '';
                            $arrayKey= in_array_column("0", "MENU_PARENT_ID", $arrMenu);
                            //print_r($arrayKey);exit;
                            if($arrayKey == ''){}
                            else
                            {
                                for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
                                {
                                    $index_row= $arrayKey[$index_detil];
                                    $tempMenuId= $arrMenu[$index_row]["MENU_ID"];
                                    $arrMenu[$index_row]["MENU_PARENT_ID"];
                                    $tempNama= $arrMenu[$index_row]["NAMA"];
                                    $arrMenu[$index_row]["LINK_FILE"];
									$arrMenu[$index_row]["LINK_DETIL_FILE"];
                                    $arrMenu[$index_row]["AKSES"];
                                    $tempJumlahMenu= $arrMenu[$index_row]["JUMLAH_MENU"];
                                    $tempJumlahDisable= $arrMenu[$index_row]["JUMLAH_DISABLE"];
                                    
                                    if($tempJumlahMenu == $tempJumlahDisable){}
                                    else
                                    {
                            ?>
                                	<div id="menu-kiri-judul"><?=$tempNama?></div>
                            <?
                                    getMenuByParent($tempMenuId, $arrMenu);
                                    }
                                }
                            }
                            ?>
                            <?php /*?><div id="menu-kiri">
                                <div>
                                	<a id="data" onClick="executeOnClick('data');" class="">Pegawai</a>
                                	<a id="pangkat_data" onClick="executeOnClick('pangkat_data');" class="">Pangkat Riwayat</a>
                                </div>
                            </div><?php */?>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
        
        <!-- PAGE CONTENT BY VALSIX -->
        <div class="konten-utama-popup">
        <table cellpadding="0" cellspacing="0" style="width:100%; height:100%;">
            <tr height="40%">
                <td><iframe src="app/loadUrl/app/<?=$arrMenu[2]["LINK_FILE"]?>/?reqId=<?=$reqId?>" class="mainframe" id="mainFrame" name="mainFrame" width="100%" height="100%" scrolling="auto" frameborder="0" style="display:block;"></iframe></td>
            </tr>
            <tr height="60%" id="trdetil" style="display:none">
                <td><iframe src="#" class="mainframe" id="mainFrameDetil" name="mainFrameDetil" width="100%" height="100%" scrolling="auto" frameborder="0" style="display:block;"></iframe></td>
            </tr>
        </table> 
        </div>

    </div>
    <!-- /#wrapper -->
    
    <!-------->
    <link rel="stylesheet" type="text/css" href="lib/Flex-Level-Drop-Down-Menu-V2/flexdropdown.css" />

	<script type="text/javascript" src="lib/Flex-Level-Drop-Down-Menu-V2/jquery.min.js"></script>
    
    <script type="text/javascript" src="lib/Flex-Level-Drop-Down-Menu-V2/flexdropdown.js">
    
    /***********************************************
    * Flex Level Drop Down Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
    * Please keep this notice intact
    * Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
    ***********************************************/
    
    </script>
    
    <script>
	function closePopupMenu() {	
		$('div.flexmenumobile').hide()
		$('div.flexoverlay').css('display', 'none')
	}
	</script>
    

</body>
</html>