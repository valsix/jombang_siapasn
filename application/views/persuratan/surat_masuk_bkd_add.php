<?
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$this->load->library("kauth");
$this->load->model("Menu");
$this->load->library('globalfilepegawai');

if($this->USER_LOGIN_ID == "")
	redirect('app');

$reqId= $this->input->get("reqId");
$reqJenis= $this->input->get("reqJenis");
$reqJenisNama= setjenisinfo($reqJenis);
$reqData = $this->input->get("reqData");

$tempInfoTab= "Simpeg Jombang";
if($reqId == ""){}
else
{
    $statement= " AND A.SURAT_MASUK_BKD_ID = ".$reqId."";
    $set= new Menu();
    $set->selectByParamsBkd(array(), -1, -1, $statement);
    $set->firstRow();
    $reqNomor= $set->getField("NOMOR");
    $reqTanggal= dateToPageCheck($set->getField("TANGGAL"));
    $tempInfoTab= "Usulan ".$reqJenisNama." - ".$reqNomor;
}

$index_set=0;
$arrMenu=setmenusuratbkd($reqId);
$jumlah_menu= count($arrMenu);
// echo $jumlah_menu;exit;
// print_r($arrMenu);exit;

$reqDetilId = $this->input->get("reqDetilId");
$infourlawal= "app/loadUrl/persuratan/".$arrMenu[0]["LINK_FILE"]."/?reqId=".$reqId."&reqJenis=".$reqJenis;
if(!empty($reqDetilId))
{
    $vfpeg= new globalfilepegawai();
    $enkripdekripkunci= $vfpeg->enkripdekripkunci();
    $arrparamjenis= ["reqdata"=>$reqDetilId, "reqkunci"=>$enkripdekripkunci];
    $arrparamjenis= $vfpeg->dekripdata($arrparamjenis);
    $arrparamjenis= explode(";", $arrparamjenis);
    $infourlawal= "app/loadUrl/persuratan/".$arrparamjenis[0]."/?reqId=".$reqId."&reqJenis=".$reqJenis."&reqRowId=".$arrparamjenis[1];
}
// echo $infourlawal;exit;
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="description" content="Simpeg Jombang">
    <meta name="keywords" content="Simpeg Jombang">
    <title><?=$tempInfoTab?></title>
    <base href="<?=base_url()?>" />
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">

    <link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">

    <link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">
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
                else if(varItem == '<?=$tempMenuId?>')
                {
                    $("#<?=$tempMenuId?>").addClass("aktif");
                    $('#<?=$tempMenuId?>').css({'background-position': '0 -27px'});

                    <?
                    if($tempLinkDetilFile == "")
                    {
                    ?>
                        mainFrame.location.href='app/loadUrl/persuratan/<?=$tempLinkFile?>/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>';
						iframeLoaded();
                    <?
                    }
                    else
                    {
                    ?>
                        mainFrame.location.href='app/loadUrl/persuratan/<?=$tempLinkFile?>/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>';
						iframeLoaded();
                    <?
                    }
                    ?>
                }
            <?
            }
            ?>
            return true;
        }

        function setload(linkfile)
        {
            mainFrame.location.href='app/loadUrl/persuratan/'+linkfile;
        }

        function setappload(linkfile)
        {
            mainFrame.location.href='app/loadUrl/app/'+linkfile;
        }

    </script>

    <style type="text/css">
        #profile-dropdown{
            padding: 10px;
            position: relative !important; 
            top: 20px !important; 
            left: 0 !important;
        }

        .profile-image{
            width: 130px;
        }

        .menu-utama{
            font-size:10pt
        }

        .d-down{
            line-height: 20px;
        }

        .content-wrap{
            padding: 10px;
        }

        .profil-photo-wrap{
            background-image: url("images/profpic-bg.jpg");
            background-size: 100%;
            background-repeat: no-repeat;
            padding: 15px;
        }

        @media only screen and (max-width: 1200px) {
            .menu-utama{
                font-size:7pt
            }
        }
		
		.info-text{
			background: #4CAF50;
            *background: #67B26F;  /* fallback for old browsers */
            *background: -webkit-linear-gradient(to left, #29d4c7, #67B26F);  /* Chrome 10-25, Safari 5.1-6 */
            *background: linear-gradient(to left, #29d4c7, #67B26F); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
            margin-top: 2vh;
            padding-left: 20px !important;
            border: 1px solid #49b349;
        }
    </style>

</head>
<body id="layouts-horizontal">
    <header id="header" class="page-topbar">
        <div class="navbar-fixed">
            <nav id="horizontal-menu-nav" class="white hide-on-med-and-down">
                <div class="nav-wrapper-center">
                    <ul id="ul-horizontal-menu-nav" class="left hide-on-med-and-down">
                        <?
                        $arrayKey= [];
                        $arrayKey= in_array_column("0", "MENU_PARENT_ID", $arrMenu);
                        // print_r($arrayKey);exit;
                        if(!empty($arrayKey))
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
                                $tempIcon= $arrMenu[$index_row]["ICON"];

                                $arrayKeyChild= [];
                                $arrayKeyChild= in_array_column($tempMenuId, "MENU_PARENT_ID", $arrMenu);
							     // print_r($arrayKey);exit;
                                if(empty($arrayKeyChild))
                                {
                        ?>
                                    <li>
                                        <a class="dropdown-menu ubah-color-warna text-darken-1 menu-utama waves-effect waves-cyan" onClick="executeOnClick('<?=$tempMenuId?>');">
                                            <?=$tempIcon?>
                                            <span><?=$tempNama?></span>
                                        </a>
                                    </li>
                        <?
                                }
                                else
                                {
                        ?>
                                    <li>
                                        <a class="dropdown-menu ubah-color-warna text-darken-1 menu-utama" data-activates="<?=$tempMenuId?>">
                                            <?=$tempIcon?>
                                            <span><?=$tempNama?><i class="mdi-navigation-arrow-drop-down right"></i></span>
                                        </a>
                                    </li>
                        <?
                                }
                            }
                        }
                        ?>
                    </ul>
                </div>
            </nav>

            <?
            $arrayKey= [];
            $arrayKey= in_array_column("0", "MENU_PARENT_ID", $arrMenu);
			// print_r($arrayKey);exit;
            if(!empty($arrayKey))
            {
                for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
                {
                    $index_row= $arrayKey[$index_detil];
                    $tempMenuId= $arrMenu[$index_row]["MENU_ID"];
                    getMenuByParent($tempMenuId, $arrMenu);
                }
            }

            function getMenuByParent($id_induk, $arrMenu)
            {
                $arrayKey= [];
                $arrayKey= in_array_column($id_induk, "MENU_PARENT_ID", $arrMenu);
				// print_r($arrayKey);exit;
                if(!empty($arrayKey))
                {
            ?>
                    <!-- CSSdropdown -->
                    <ul id="<?=$id_induk?>" class="dropdown-content dropdown-horizontal-list">
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
                          <li><a class="ubah-color-warna text-darken-1 waves-effect waves-cyan" onClick="executeOnClick('<?=$tempMenuId?>');"><?=$tempNama?></a></li>
                          <?
                      }
                  }
              }
              ?>
                    </ul>

        </div>
    </header>

    <!-- //////////////////////////////////////////////////////////////////////////// -->
    <div id="mainhead">
        <div class="wrapper">
            <aside id="left-sidebar-nav hide-on-large-only">
                <ul id="slide-out" class="side-nav leftside-navigation ">

                    <?
                    $arrayKey= [];
                    $arrayKey= in_array_column("0", "MENU_PARENT_ID", $arrMenu);
                    // print_r($arrayKey);exit;
                    if(!empty($arrayKey))
                    {
                        for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
                        {
                            $index_row= $arrayKey[$index_detil];
                            $tempMenuId= $arrMenu[$index_row]["MENU_ID"];
                            $tempMenuParent= $arrMenu[$index_row]["MENU_PARENT_ID"];
                            $tempNama= $arrMenu[$index_row]["NAMA"];
                            $arrMenu[$index_row]["LINK_FILE"];
                            $arrMenu[$index_row]["LINK_DETIL_FILE"];
                            $arrMenu[$index_row]["AKSES"];
                            $tempJumlahMenu= $arrMenu[$index_row]["JUMLAH_MENU"];
                            $tempJumlahDisable= $arrMenu[$index_row]["JUMLAH_DISABLE"];
                            $tempIcon= $arrMenu[$index_row]["ICON"];

                            $arrayKeyChild= [];
                            $arrayKeyChild= in_array_column($tempMenuId, "MENU_PARENT_ID", $arrMenu);
                            // print_r($arrayKey);exit;
                            if(empty($arrayKeyChild))
                            {
                    ?>
                                <li>
                                    <a class="dropdown-menu ubah-color-warna text-darken-1 menu-utama waves-effect waves-cyan" onClick="executeOnClick('<?=$tempMenuId?>');">
                                        <!-- <i class="mdi-action-invert-colors"></i> -->
                                        <?=$tempIcon?>
                                        <span><?=$tempNama?></span>
                                    </a>
                                </li>
                                <?
                            }
                            else
                            {
                                ?>
                                <ul class="collapsible collapsible-accordion">
                                    <li class="bold">
                                        <a class="collapsible-header green-text waves-effect waves-cyan">
                                            <?=$tempIcon?>
                                            <span><?=$tempNama?></span>
                                        </a>
                                        <div class="collapsible-body">
                                            <ul>
                                                <?
                                                for ($i=0; $i < count($arrMenu); $i++) { 
                                                    if ($arrMenu[$i]['MENU_PARENT_ID'] == $tempMenuId){ 
                                                        $childNama = $arrMenu[$i]['NAMA'];
                                                        $childMenuId = $arrMenu[$i]['MENU_ID'];
                                                        ?>
                                                        <li><a onClick="executeOnClick('<?=$childMenuId?>');" class="green-text waves-effect waves-cyan"><?=$childNama?></a></li>                                        
                                                        <?
                                                    }
                                                }
                                                ?>

                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                                <?
                            }
                        }
                    }
                    ?>

                </ul>
                <a href="#" data-activates="slide-out" style="position: fixed;" class="sidebar-collapse btn-floating btn-medium waves-effect waves-light hide-on-large-only light-green darken-2"><i class="mdi-navigation-menu"></i></a>
            </aside>

            <section id="content-noheader">

                <div class="row">
                    <div class="container">
                        <div class="col s12 m12" style="padding-left: 15px;">
                            <div class="col s12 ubah-color-warna" id="info-surat"></div>
                        </div>
                    </div>
                    <div class="container">
                        <iframe src="<?=$infourlawal?>" name="mainFrame" id="mainFrame" onload="iframeLoaded()" style="border: none; width:100%" scrolling="no"></iframe>
                    </div>
                </div>
            </section>
        </div>

    </div>

  <!-- jQuery Library -->
  <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script>

  <!-- RESIZE IFRAME HEIGHT ONLOAD -->
  <script type="text/javascript">
	function closeparenttab()
    {
		if (window.opener && window.opener.document)
        {
            if (typeof window.opener.setCariInfo === 'function')
            {
                window.opener.setCariInfo();
            }
        }
        window.close();
    }

    function reloadparenttab()
    {
		if (window.opener && window.opener.document)
        {
			if (typeof window.opener.setCariInfo === 'function')
			{
				window.opener.setCariInfo();
			}
		}
    }
	
	$(function(){
	   reloadinfosurat('<?=$reqId?>');
	});
	  
	function reloadinfosurat(id)
	{
		if(id == ""){}
		else
		{
			$('#info-surat').empty();
			$.ajax({'url': "surat/surat_masuk_bkd_json/set_info_pegawai_data/?reqId="+id,'success': function(datahtml) {
				$('#info-surat').append(datahtml);
			}});
		}
	}
		
    function iframeLoaded() {
       var iFrameID = document.getElementById('mainFrame');
        if(iFrameID) {
				// here you can make the height, I delete it first, then I make it again
				iFrameID.height = "";
				iFrameID.height = iFrameID.contentWindow.document.body.scrollHeight + "px";
            }   
        }
    </script>
    <!---->

    
    <!--materialize js-->
    <script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>
    
    <!--plugins.js - Some Specific JS codes for Plugin Settings-->
    <script type="text/javascript" src="lib/materializetemplate/js/plugins.min.js"></script>
    
    <?php /*?><!-- eModal -->
	<script src="lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal-admin.min.js"></script>
    <script type="text/javascript">
	$(function () {
		$('.modal').modal();
	});
	
	function openPopup(page) {
		eModal.iframe(page, 'SIMPEG KABUPATEN JOMBANG')
	} </script><?php */?>


    <!-- ---------------------------------------------MODAL MATERIAL DESIGN-------------------------------------------------- -->
    
    <style type="text/css">
        #my-modal{
            height: 100%;
        }

        .modal-content{
            height: 88%;
        }

        #judul-modal{
            font-size: 14pt;
        }

        .judul{
            padding: 15px;
        }
		.modal
		{
			width:90% !important
		}
		
    </style>
    <script type="text/javascript">
        function openModal(url){
            $('.modal-place').html('<div id="my-modal" class="modal"><div class="judul"><span id="judul-modal">SIMPEG KABUPATEN JOMBANG</span><a class="modal-action modal-close grey-text right" title="Keluar"><i class="mdi-navigation-close"></i></a></div><div class="modal-content"><iframe src="'+url+'" id="m-iframe" width="100%" height="100%" frameBorder="1"></iframe></div></div>')
            $('#my-modal').openModal();
        }
		
		function closeModal(){
			$('#my-modal').closeModal();
		}
  
		function cetakpengantar(id)
		{
			var s_url= "surat/surat_masuk_pegawai_json/total_pegawai_dinas?reqId=<?=$reqId?>";
			$.ajax({'url': s_url,'success': function(dataajax){
				var requrl= requrllist= "";
				dataajax= String(dataajax);
				var element = dataajax.split('-'); 
				dataajax= element[0];
				requrl= element[1];
				requrllist= element[2];
				requrlcss= element[3];
				
				/*var requrl= requrllist= "";
				if(dataajax == '1')
				{
					requrl= "ijin_belajar_pengantar_dinas_1_org";
				}
				else
				{
					requrl= "ijin_belajar_pengantar_dinas_lebih_1_org";
					requrllist= "ijin_belajar_pengantar_dinas_nominatif";
				}*/
				
			  newWindow = window.open("app/loadUrl/persuratan/cetak_pdf?reqStatusBkdUptId=2&reqCss="+requrlcss+"&reqUrl="+requrl+"&reqUrlList="+requrllist+"&reqId=<?=$reqId?>&reqPegawaiPilihKepalaId="+id, 'Cetak');
			  newWindow.focus();
			}});
		}
		
        function cetakrekomendasi(rowid, id, tipeid)
        {
            newWindow = window.open("app/loadUrl/persuratan/cetak_pdf?reqStatusBkdUptId=1&reqCss=surat_rekomendasi&reqUrl=ijin_belajar_surat_rekomendasi&reqId="+rowid+"&reqPegawaiPilihKepalaId="+id+"&reqTipeId="+tipeid, 'Cetak');
            newWindow.focus();
        }

        function cetakrekomendasitipejabatan(id, tipeid, jabatanpilihan, jabatanmanual)
        {
            var s_url= "surat/surat_masuk_pegawai_json/total_pegawai_rekomendasi?reqId=<?=$reqId?>";
            $.ajax({'url': s_url,'success': function(dataajax){
                var requrl= requrllist= "";
                dataajax= String(dataajax);
                var element = dataajax.split('-'); 
                dataajax= element[0];
                requrl= element[1];
                requrllist= element[2];
                requrlcss= element[3];
                
                reqJabatanManual= "";
                if(jabatanmanual == ""){}
                else
                {
                    if(tipeid == "3"){}
                    else
                    {
                        tipeid= 2;
                        reqJabatanManual= jabatanmanual;
                    }
                }
                
              newWindow = window.open("app/loadUrl/persuratan/cetak_pdf?reqStatusBkdUptId=2&reqCss="+requrlcss+"&reqUrl="+requrl+"&reqUrlList="+requrllist+"&reqId=<?=$reqId?>&reqPegawaiPilihKepalaId="+id+"&reqTipeId="+tipeid+"&reqJabatanPilihan="+jabatanpilihan+"&reqJabatanManual="+reqJabatanManual, 'Cetak');
              newWindow.focus();
            }});
        }

        function cetakrekomendasitipejabatandetil(id, rowid, tipeid, jabatanpilihan, jabatanmanual)
        {
            var s_url= "surat/surat_masuk_pegawai_json/total_pegawai_rekomendasi?reqId=<?=$reqId?>";
            $.ajax({'url': s_url,'success': function(dataajax){
                var requrl= requrllist= "";
                dataajax= String(dataajax);
                var element = dataajax.split('-'); 
                dataajax= element[0];
                requrl= element[1];
                requrllist= element[2];
                requrlcss= element[3];
                
                reqJabatanManual= "";
                if(jabatanmanual == ""){}
                else
                {
                    if(tipeid == "3"){}
                    else
                    {
                        tipeid= 2;
                        reqJabatanManual= jabatanmanual;
                    }
                }
                
              newWindow = window.open("app/loadUrl/persuratan/cetak_pdf?reqStatusBkdUptId=2&reqCss="+requrlcss+"&reqUrl="+requrl+"&reqUrlList="+requrllist+"&reqId="+rowid+"&reqPegawaiPilihKepalaId="+id+"&reqTipeId="+tipeid+"&reqJabatanPilihan="+jabatanpilihan+"&reqJabatanManual="+reqJabatanManual, 'Cetak');
              newWindow.focus();
            }});
        }

		// function cetakpengantartipejabatan(reqGantiBaris, id, tipeid, jabatanpilihan, jabatanmanual)
  //       {
  //           var s_url= "surat/surat_masuk_pegawai_json/total_pegawai_dinas?reqId=<?=$reqId?>";
  //           alert(s_url);return false;
  //           $.ajax({'url': s_url,'success': function(dataajax){
  //               var requrl= requrllist= "";
  //               dataajax= String(dataajax);
  //               var element = dataajax.split('-'); 
  //               dataajax= element[0];
  //               requrl= element[1];
  //               requrllist= element[2];
  //               requrlcss= element[3];
                
  //               reqJabatanManual= "";
  //               if(jabatanmanual == ""){}
  //               else
  //               {
  //                   if(tipeid == "3"){}
  //                   else
  //                   {
  //                       tipeid= 2;
  //                       reqJabatanManual= jabatanmanual;
  //                   }
  //               }
                
  //             newWindow = window.open("app/loadUrl/persuratan/cetak_pdf?reqGantiBaris="+reqGantiBaris+"&reqStatusBkdUptId=2&reqCss="+requrlcss+"&reqUrl="+requrl+"&reqUrlList="+requrllist+"&reqId=<?=$reqId?>&reqPegawaiPilihKepalaId="+id+"&reqTipeId="+tipeid+"&reqJabatanPilihan="+jabatanpilihan+"&reqJabatanManual="+reqJabatanManual, 'Cetak');
  //             newWindow.focus();
  //           }});
  //       }

        function cetakpengantartipejabatan(reqGantiBaris, id, tipeid, jabatanpilihan, jabatanmanual)
        {
            var s_url= "surat/surat_masuk_pegawai_json/total_pegawai_bkd?reqId=<?=$reqId?>";
            // alert(s_url);return false;
            $.ajax({'url': s_url,'success': function(dataajax){
                var requrl= requrllist= "";
                dataajax= String(dataajax);
                var element = dataajax.split('-'); 
                dataajax= element[0];
                requrl= element[1];
                requrllist= element[2];
                requrlcss= element[3];
                
                reqJabatanManual= "";
                if(jabatanmanual == ""){}
                else
                {
                    if(tipeid == "3"){}
                    else
                    {
                        tipeid= 2;
                        reqJabatanManual= jabatanmanual;
                    }
                }
                
              newWindow = window.open("app/loadUrl/persuratan/cetak_pdf?reqGantiBaris="+reqGantiBaris+"&reqStatusBkdUptId=2&reqCss="+requrlcss+"&reqUrl="+requrl+"&reqUrlList="+requrllist+"&reqId=<?=$reqId?>&reqPegawaiPilihKepalaId="+id+"&reqTipeId="+tipeid+"&reqJabatanPilihan="+jabatanpilihan+"&reqJabatanManual="+reqJabatanManual, 'Cetak');
              newWindow.focus();
            }});
        }

        function cetakpengantartipe(id, tipeid)
		{
			var s_url= "surat/surat_masuk_pegawai_json/total_pegawai_dinas?reqId=<?=$reqId?>";
			$.ajax({'url': s_url,'success': function(dataajax){
				var requrl= requrllist= "";
				dataajax= String(dataajax);
				var element = dataajax.split('-'); 
				dataajax= element[0];
				requrl= element[1];
				requrllist= element[2];
				requrlcss= element[3];
				
				/*var requrl= requrllist= "";
				if(dataajax == '1')
				{
					requrl= "ijin_belajar_pengantar_dinas_1_org";
				}
				else
				{
					requrl= "ijin_belajar_pengantar_dinas_lebih_1_org";
					requrllist= "ijin_belajar_pengantar_dinas_nominatif";
				}*/
				
			  newWindow = window.open("app/loadUrl/persuratan/cetak_pdf?reqStatusBkdUptId=2&reqCss="+requrlcss+"&reqUrl="+requrl+"&reqUrlList="+requrllist+"&reqId=<?=$reqId?>&reqPegawaiPilihKepalaId="+id+"&reqTipeId="+tipeid, 'Cetak');
			  newWindow.focus();
			}});
		}
		
		function setCariInfo()
		{
			$("iframe#mainFrame")[0].contentWindow.setCariInfo();
		}

        function simpandataturunstatus()
        {
            $("iframe#mainFrame")[0].contentWindow.simpandataturunstatus();
        }
        
        function setUrlInfo(url)
        {
            document.location.href= url;
            //parent.setload(url)
            //mainFrame.location.href=url;
        }
    </script>
    <div class="modal-place"></div>
    <!-- -------------------------------------------------------------------------------------------------------------------- -->

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>