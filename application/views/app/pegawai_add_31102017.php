<?
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$this->load->library("kauth");
$this->load->model("Menu");

if($this->USER_LOGIN_ID == "")
	redirect('app');

$reqId = $this->input->get("reqId");
$reqData = $this->input->get("reqData");

$index_set=0;
$arrMenu="";
$set= new Menu();
//selectByParamsMenu(1, $akses_id, "AKSES_APP_ZIS", " AND A.MENU_PARENT_ID = '".$id_induk."'");
$set->selectByParamsMenu(1, 1, "AKSES_APP_SIMPEG", " AND A.STATUS = 1", "ORDER BY A.URUT");
//echo $set->query;exit;
while($set->nextRow())
{
	if($set->getField("MENU_PARENT_ID") == "0")
       continue;

   $arrMenu[$index_set]["MENU_ID"]= $set->getField("MENU_ID");
   $arrMenu[$index_set]["MENU_PARENT_ID"]= $set->getField("MENU_PARENT_ID");
   $arrMenu[$index_set]["NAMA"]= $set->getField("NAMA");
   $arrMenu[$index_set]["LINK_FILE"]= $set->getField("LINK_FILE");
   $arrMenu[$index_set]["LINK_DETIL_FILE"]= $set->getField("LINK_DETIL_FILE");
   $arrMenu[$index_set]["AKSES"]= $set->getField("AKSES");
   $arrMenu[$index_set]["JUMLAH_MENU"]= $set->getField("JUMLAH_MENU");
   $arrMenu[$index_set]["JUMLAH_DISABLE"]= $set->getField("JUMLAH_DISABLE");
   $arrMenu[$index_set]["ICON"]= $set->getField("ICON");
   $index_set++;

   if($reqId == "")
       break;
}
$jumlah_menu= $index_set;
unset($set);
//print_r($arrMenu);exit;
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
    <title>Simpeg Jombang</title>
    <base href="<?=base_url()?>" />

    <!-- CORE CSS-->    
    <link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
    <!-- CSS style Horizontal Nav-->    
    <link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
    <!-- Custome CSS-->    
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
                else if(varItem == '<?=$tempMenuId?>'){
                    $("#<?=$tempMenuId?>").addClass("aktif");
                    $('#<?=$tempMenuId?>').css({'background-position': '0 -27px'});

                    <?
                    if($tempLinkDetilFile == "")
                    {
                        ?>
                        mainFrame.location.href='app/loadUrl/app/<?=$tempLinkFile?>/?reqId=<?=$reqId?>';
                        //mainFrameDetil.location.href='';
                        //document.getElementById('trdetil').style.display = 'none';	
                        <?
                    }
                    else
                    {
                        ?>
                        mainFrame.location.href='app/loadUrl/app/<?=$tempLinkFile?>/?reqId=<?=$reqId?>';
                        //mainFrameDetil.location.href='app/loadUrl/app/<?=$tempLinkDetilFile?>/?reqId=<?=$reqId?>';
                        //document.getElementById('trdetil').style.display = '';	
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
            mainFrame.location.href='app/loadUrl/app/'+linkfile;
        }

        // function openPopup(page) {
        //     eModal.iframe(page, 'SIMPEG KABUPATEN JOMBANG')
        // }

    </script>

    <style type="text/css">
        #profile-dropdown{
            padding: 10px;
        }

        .profile-image{
            width: 150px;
        }

        .horizontal-nav{
            /*font-size: 10px;*/
        }

        .menu-utama{
            font-size:10pt
        }

        .d-down{
            line-height: 20px;
        }

        @media only screen and (max-width: 1200px) {
            .menu-utama{
                font-size:7pt
            }
        }
    </style>

</head>
<body id="layouts-horizontal">
    <!-- START HEADER -->
    <header id="header" class="page-topbar">
        <!-- start header nav-->
        <div class="navbar-fixed">
            <nav class="navbar-color">
                <div class="nav-wrapper">
                    <?php /*?>
                    <ul class="left">                      
                    <li><h1 class="logo-wrapper"><a href="javascript:void(0)" class="brand-logo darken-1"><img src="images/logo.png" ></a></h1></li>
                    </ul>
                    <?php 
                    */?>

                    <div class="row">
                        <!-- <div class="col s4 m1 l1"> -->
                        <!-- <img src="images/avatar.jpg" alt="" class="circle responsive-img valign profile-image"> -->
                        <!-- </div> -->
                        <div class="col s12">
                            <ul id="profile-dropdown" class="dropdown-content center z-depth-2 d-down">
                                <img src="images/avatar.jpg" alt="" class="circle responsive-img valign profile-image">

                                <table class="table bordered stripped black-text">
                                    <tbody >
                                        <tr>
                                            <td><b>NAMA</b></td>
                                            <td>:</td>
                                            <td>ERWANDA RIZKY RIFANTO</td>
                                        </tr>

                                        <tr>
                                            <td><b>NIP</b></td>
                                            <td>:</td>
                                            <td>12121212817817287182</td>
                                        </tr>
                                        <tr>
                                            <td><b>Pangkat</b></td>
                                            <td>:</td>
                                            <td>IV/A</td>
                                        </tr>
                                        <tr>
                                            <td><b>Jabatan</b></td>
                                            <td>:</td>
                                            <td>Kepala Dina Perindustrian Jombang</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </ul>
                            <a class="btn-flat dropdown-button waves-effect waves-light white-text profile-btn" data-constrainwidth="false"  data-activates="profile-dropdown">ERWANDA RIZKY RIFANTO<i class="mdi-navigation-arrow-drop-down right"></i></a>
                        </div>
                    </div>

                </div>
            </nav>

            <!-- HORIZONTL NAV START-->
            <nav id="horizontal-nav" class="white hide-on-med-and-down">
                <div class="nav-wrapper-center">
                    <ul id="ul-horizontal-nav" class="left hide-on-med-and-down">
                        <?
                        $arrayKey= '';
                        $arrayKey= in_array_column("01", "MENU_PARENT_ID", $arrMenu);
					       //print_r($arrayKey);exit;
                        if($arrayKey == ''){
                        } 
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
                                $tempIcon= $arrMenu[$index_row]["ICON"];

                                $arrayKeyChild= '';
                                $arrayKeyChild= in_array_column($tempMenuId, "MENU_PARENT_ID", $arrMenu);
							     //print_r($arrayKey);exit;
                                if($arrayKeyChild == '')
                                {
                                    ?>
                                    <li>
                                        <a class="dropdown-menu green-text text-darken-1 menu-utama" onClick="executeOnClick('<?=$tempMenuId?>');">
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
                                    <li>
                                        <a class="dropdown-menu green-text text-darken-1 menu-utama" onClick="executeOnClick('<?=$tempMenuId?>');" data-activates="<?=$tempMenuId?>">
                                            <!-- <i class="mdi-action-invert-colors"></i> -->
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
            $arrayKey= '';
            $arrayKey= in_array_column("01", "MENU_PARENT_ID", $arrMenu);
				//print_r($arrayKey);exit;
            if($arrayKey == ''){}
                else
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
                $arrayKey= '';
                $arrayKey= in_array_column($id_induk, "MENU_PARENT_ID", $arrMenu);
					//print_r($arrayKey);exit;
                if($arrayKey == ''){}
                    else
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
                              <li><a class="cyan-text" onClick="executeOnClick('<?=$tempMenuId?>');"><?=$tempNama?></a></li>
                              <?
                          }
                      }
                  }
                  ?>
              </ul>
              <!-- HORIZONTL NAV END-->



          </div>
          <!-- end header nav-->
      </header>
      <!-- END HEADER -->

      <!-- //////////////////////////////////////////////////////////////////////////// -->

      <!-- START MAIN -->
      <div id="main">
        <!-- START WRAPPER -->
        <div class="wrapper">

            <!-- START LEFT SIDEBAR NAV-->
            <aside id="left-sidebar-nav hide-on-large-only">
                <ul id="slide-out" class="side-nav leftside-navigation ">
                    <li class="bold active"><a href="index.html" class="waves-effect waves-cyan"><i class="mdi-action-dashboard"></i> Dashboard</a>
                    </li>
                    <li class="no-padding">
                        <ul class="collapsible collapsible-accordion">
                            <li class="bold"><a class="collapsible-header waves-effect waves-cyan"><i class="mdi-action-invert-colors"></i> CSS</a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li><a href="css-typography.html">Typography</a>
                                        </li>                                        
                                        <li><a href="css-icons.html">Icons</a>
                                        </li>
                                        <li><a href="css-shadow.html">Shadow</a>
                                        </li>
                                        <li><a href="css-media.html">Media</a>
                                        </li>
                                        <li><a href="css-sass.html">Sass</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
                <a href="#" data-activates="slide-out" style="position: fixed;" class="sidebar-collapse btn-floating btn-medium waves-effect waves-light hide-on-large-only cyan"><i class="mdi-navigation-menu"></i></a>
            </aside>
            <!-- END LEFT SIDEBAR NAV-->

            <!-- //////////////////////////////////////////////////////////////////////////// -->

            <!-- START CONTENT -->
            <section id="content">

                <!--breadcrumbs start-->
                <?php /*?><div id="breadcrumbs-wrapper">
                  <div class="container">
                    <div class="row">
                      <div class="col s12 m12 l12">
                        <ol class="breadcrumbs">
                          <li><a href="index.html">Dashboard</a></li>
                          <li><a href="#">Forms</a></li>
                          <li class="active">Forms Layouts</li>
                        </ol>
                      </div>
                    </div>
                  </div>
              </div><?php */?>
              <!--breadcrumbs end-->


              <!--start container-->
              <div class="container">

                <iframe src="app/loadUrl/app/pegawai_add_saudara_monitoring/?reqId=<?=$reqId?>" name="mainFrame" id="mainFrame" onload="iframeLoaded()" style="border: none; width:100%" scrolling="no"></iframe>
                <?php /*?><iframe src="app/loadUrl/app/<?=$arrMenu[0]["LINK_FILE"]?>/?reqId=<?=$reqId?>" name="mainFrame" id="mainFrame" onload="iframeLoaded()" style="border: none; width:100%" scrolling="no"></iframe><?php */?>
                
                    <?php /*?><div class="intrinsic-container intrinsic-container-16x9">
                      <iframe src="app/loadUrl/app/pegawai_sk_cpns/?reqId=<?=$reqId?>" name="mainFrame" id="mainFrame" allowfullscreen></iframe>
                  </div><?php */?>

                  <!-- <?php ?><iframe src="app/loadUrl/app/tes/?reqId=<?=$reqId?>" class="mainframe" id="mainFrame" name="mainFrame" width="100%" height="100%" scrolling="auto" frameborder="0" style="display:block;"></iframe><?php ?> -->

              </div>
              <!--end container-->
          </section>
          <!-- END CONTENT -->

      </div>
      <!-- END WRAPPER -->

  </div>
  <!-- END MAIN -->



  <!-- jQuery Library -->
  <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script>

  <!-- RESIZE IFRAME HEIGHT ONLOAD -->
  <script type="text/javascript">
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
            width: 1000px;
            height: 100%;
        }
    </style>
    <script type="text/javascript">
        function openModal(url){
            $('.modal-place').html('<div id="my-modal" class="modal  modal-fixed-footer"><div class="modal-content"><iframe src="'+url+'" width="100%" height="100%" frameBorder="0"></iframe></div><div class="modal-footer"><a class=" modal-action modal-close waves-effect waves-green btn-flat">Keluar</a></div></div>')
            $('#my-modal').openModal();
        }
    </script>
    <div class="modal-place"></div>
    <!-- -------------------------------------------------------------------------------------------------------------------- -->

</body>