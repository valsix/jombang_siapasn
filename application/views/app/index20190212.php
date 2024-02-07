<?
include_once("functions/default.func.php");
include_once("functions/date.func.php");
include_once("functions/string.func.php");

$this->load->model("Menu");

$index_set=0;
$arrMenu="";
$set= new Menu();

$tempUserLoginInfoNama= $this->PEGAWAI_NAMA_LENGKAP;

//echo $this->AKSES_APP_SIMPEG_ID;exit;
//selectByParamsMenu(1, $akses_id, "AKSES_APP_ZIS", " AND A.MENU_PARENT_ID = '".$id_induk."'");
$set->selectByParamsMenu(1, $this->AKSES_APP_SIMPEG_ID, "AKSES_APP_SIMPEG", " AND A.STATUS IS NULL", "ORDER BY A.URUT");
//echo $set->query;exit;
while($set->nextRow())
{
	$arrMenu[$index_set]["MENU_ID"]= $set->getField("MENU_ID");
	$arrMenu[$index_set]["MENU_PARENT_ID"]= $set->getField("MENU_PARENT_ID");
	$arrMenu[$index_set]["NAMA"]= $set->getField("NAMA");
	$arrMenu[$index_set]["LINK_FILE"]= $set->getField("LINK_FILE");
	$arrMenu[$index_set]["AKSES"]= $set->getField("AKSES");
	$arrMenu[$index_set]["JUMLAH_CHILD"]= $set->getField("JUMLAH_CHILD");
	$arrMenu[$index_set]["JUMLAH_MENU"]= $set->getField("JUMLAH_MENU");
	$arrMenu[$index_set]["JUMLAH_DISABLE"]= $set->getField("JUMLAH_DISABLE");
	$index_set++;
}
$tempMenu= $index_set;
unset($set);
//print_r($arrMenu);exit;
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SIAP ASN</title>
  <base href="<?=base_url()?>" />
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
  
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <!--<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">-->
  
  <link rel="stylesheet" href="css/gaya-lte.css">
  
</head>
<body class="hold-transition skin-blue sidebar-mini fixed">
<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="app/index" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>A</b>LT</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><img src="images/logo.png"></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success" id="setInformasiTotal">0</span>
            </a>
            
            <ul class="dropdown-menu" id="setInformasiInfo">
              <!-- <li class="header"></li>
              <li>
                
                <ul class="menu">

                  <li>
                    <a href="app/loadUrl/app/informasi_detil?reqId=1" target="mainFrame">
                      <h4>
                        Informasi (belum terbaca)
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                      </h4>
                      <p>Klik Menu Informasi -> Preview</p>
                    </a>
                  </li>

                </ul>

              </li> -->
              <!-- <li class="footer"><a href="#">See All Messages</a></li> -->
            </ul>
          </li>
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning" id="setKonsultasiTotal">0</span>
            </a>
            <ul class="dropdown-menu" id="setKonsultasiInfo">
              <!-- <li class="header">You have 10 notifications</li>
              <li>
                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li> -->
            </ul>
          </li>
          
          <!-- Tasks: style can be found in dropdown.less -->
          <!-- <li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger">9</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 9 tasks</li>
              <li>
                <ul class="menu">

                  <li>
                    <a href="#">
                      <h3>
                        Design some buttons
                        <small class="pull-right">20%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"
                             aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">20% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>

                </ul>
              </li>
              <li class="footer">
                <a href="#">View all tasks</a>
              </li>
            </ul>
          </li> -->
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <?php /*?><img src="lib/AdminLTE-2.4.0-rc/dist/img/user2-160x160.jpg" class="user-image" alt="User Image"><?php */?>
              <img src="images/foto-profile.jpg" width="160" height="160" class="user-image" alt="User Image" />
              <span class="hidden-xs"><?=$tempUserLoginInfoNama?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <?php /*?><img src="lib/AdminLTE-2.4.0-rc/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image"><?php */?>
                <img src="images/foto-profile.jpg" width="160" height="160" class="user-image" alt="User Image" />
                <p>
                  <?=$tempUserLoginInfoNama?>
                  <small>Member since ....</small>
                </p>
              </li>
              <?php /*?><!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
                <!-- /.row -->
              </li><?php */?>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="app/loadUrl/app/ubah_password" target="mainFrame" class="btn btn-default btn-flat">Ubah Password</a>
                </div>
                <div class="pull-right">
                  <a href="app/logout" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <!--<li class="header">MAIN NAVIGATION</li>-->
        
        <?php /*?>
		<li class="treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
		<li class="treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="lib/AdminLTE-2.4.0-rc/index.html"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>
            <li><a href="lib/AdminLTE-2.4.0-rc/index2.html"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>
          </ul>
        </li>
        
        <li class="treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="lib/AdminLTE-2.4.0-rc/index.html"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>
            <li><a href="lib/AdminLTE-2.4.0-rc/index2.html"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>
          </ul>
        </li>
        
        <li class="treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="lib/AdminLTE-2.4.0-rc/index.html"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>
            <li><a href="lib/AdminLTE-2.4.0-rc/index2.html"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>
          </ul>
        </li>
        
        <li class="treeview">
          <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Layout Options</span>
            <span class="pull-right-container">
              <span class="label label-primary pull-right">4</span>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="lib/AdminLTE-2.4.0-rc/pages/layout/top-nav.html"><i class="fa fa-circle-o"></i> Top Navigation</a></li>
            <li><a href="lib/AdminLTE-2.4.0-rc/pages/layout/boxed.html"><i class="fa fa-circle-o"></i> Boxed</a></li>
            <li><a href="lib/AdminLTE-2.4.0-rc/pages/layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>
            <li><a href="lib/AdminLTE-2.4.0-rc/pages/layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>
          </ul>
        </li><?php */?>
        <!-- MENU KIRI -->
        <?
        function getMenuByParent($id_induk, $arrMenu, $tempParentNama)
        {
            $arrayKey= '';
            $arrayKey= in_array_column($id_induk, "MENU_PARENT_ID", $arrMenu);
			
			/*if($id_induk == "1401")
			{
            	print_r($arrayKey);exit;
			}*/
			
            if($arrayKey == ''){}
            else
            {
        ?>
        <ul class="treeview-menu">
        <?
                for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
                {
                    $index_row= $arrayKey[$index_detil];
                    $tempMenuId= $arrMenu[$index_row]["MENU_ID"];
                    $arrMenu[$index_row]["MENU_PARENT_ID"];
                    $tempNama= $arrMenu[$index_row]["NAMA"];
                    $tempLinkFile= $arrMenu[$index_row]["LINK_FILE"];
                    $tempAkses= $arrMenu[$index_row]["AKSES"];
					          $tempJumlahChild= $arrMenu[$index_row]["JUMLAH_CHILD"];
                    $tempJumlahMenu= $arrMenu[$index_row]["JUMLAH_MENU"];
                    $tempJumlahDisable= $arrMenu[$index_row]["JUMLAH_DISABLE"];
					          $tempInfoNama= $tempParentNama.";".$tempNama;
					
					if($tempAkses == "D")
					continue;
					
					if($tempLinkFile == "")
					{
						if($tempMenuId == "14z01")
						{
        ?>
        <?
						}
						else
						{
                            if($tempJumlahChild > 0)
                            {
        					?>
                            <li class="treeview">
                            <a>
                                <i class="fa fa-files-o"></i>
                                    <span><?=$tempNama?></span>
                                    <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
        					<?
							getMenuByParent($tempMenuId, $arrMenu, $tempNama);
                			//getMenuDetilByParent($tempMenuId, $arrMenu, $tempNama);
        					?>
                			</li>
        					<?
							}
							else
							{
							?>
                            <li>
                            	<a href="nopage" target="mainFrame"><i class="fa fa-circle-o"></i> <?=$tempNama?></a>
                            </li>
        					<?
							}
						}
					}
					else
					{
						$tempUrl= "";
						if(isStrContain($tempLinkFile, "?") == false)
						$tempUrl= "app/loadUrl/app/".$tempLinkFile."?reqAkses=".$tempAkses."&reqBreadCrum=".$tempInfoNama;
						else
						$tempUrl= "app/loadUrl/persuratan/".$tempLinkFile."&reqAkses=".$tempAkses."&reqBreadCrum=".$tempInfoNama;
		?>
                <li>
                <a href="<?=$tempUrl?>" <?php /*?>onClick="setBreacrum('<?=$tempInfoNama?>');"<?php */?> target="mainFrame"><i class="fa fa-circle-o"></i> <?=$tempNama?></a>
                </li>
        <?
					}
                }
            }
        ?>
        </ul>
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
                $tempLinkFile= $arrMenu[$index_row]["LINK_FILE"];
                $tempAkses= $arrMenu[$index_row]["AKSES"];
				        $tempJumlahChild= $arrMenu[$index_row]["JUMLAH_CHILD"];
                $tempJumlahMenu= $arrMenu[$index_row]["JUMLAH_MENU"];
                $tempJumlahDisable= $arrMenu[$index_row]["JUMLAH_DISABLE"];
                
                if($tempJumlahMenu == $tempJumlahDisable){}
                else
                {
					if($tempJumlahChild > 0)
					{
        ?>
                <li class="treeview">
                <a>
                    <i class="fa fa-files-o"></i>
                        <span><?=$tempNama?></span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
        <?
                getMenuByParent($tempMenuId, $arrMenu, $tempNama);
        ?>
                </li>
        <?
					}
					else
					{
						if($tempLinkFile == "")
						{
        ?>
        		<li><a href="nopage" target="mainFrame"><i class="fa fa-circle-o"></i> <?=$tempNama?></a></li>
        <?
						}
						else
						{
							$tempUrl= "";
							if(isStrContain($tempLinkFile, "?") == false)
							$tempUrl= "app/loadUrl/app/".$tempLinkFile."?reqAkses=".$tempAkses."&reqBreadCrum=".$tempInfoNama;
							else
							$tempUrl= "app/loadUrl/persuratan/".$tempLinkFile."&reqAkses=".$tempAkses."&reqBreadCrum=".$tempInfoNama;
		?>
        		<li>
                <a href="<?=$tempUrl?>" target="mainFrame"><i class="fa fa-circle-o"></i> <?=$tempNama?></a>
                </li>
        <?
						}
					}
                }
            }
        }
        ?>
        
        <?php /*?><li class="treeview">
          <a href="#">
            <i class="fa fa-share"></i> <span>Multilevel</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="treeview">
              <a href="#"><i class="fa fa-circle-o"></i> Level One
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
              </ul>
            </li>
          </ul>
        </li><?php */?>
        
        
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <!--<h1>
        Dashboard
        <small>it all starts here</small>
      </h1>-->
      <?php /*?><ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      </ol><?php */?>
    </section>

    <!-- Main content -->
    <section class="content area-main">
		<!-- onload="iframeIndexLoaded()"  -->
        <div class="konten-utama">
            <iframe src="<?=site_url('app/loadUrl/app/home')?>" name="mainFrame" id="mainFrame" onload="iframeIndexLoaded()" style="height: 100% !important; position: relative !important;" ></iframe>

            <!-- <iframe src="<?=site_url('app/loadUrl/app/home')?>" name="mainFrame" id="mainFrame" class="iframe" scrolling="no" frameborder="0"></iframe> -->
        </div>

        <?php /*?><div class="row" style="border:1px solid orange;">
            <div class="col-md-12" style="border:1px solid pink;">
                <!-- PAGE CONTENT BY VALSIX -->
                <div class="konten-utama" style="border:1px solid cyan;">
                    <iframe src="<?=site_url('app/loadUrl/app/home')?>" name="mainFrame"></iframe>
                </div>
            </div>
        </div><?php */?>
        
      <?php /*?><!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Title</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          Start creating your amazing application!
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          Footer
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box --><?php */?>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php /*?><footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.0
    </div>
    <strong>Copyright &copy; 2017 BKDPP Kabupaten Jombang.</strong> All rights
    reserved.
  </footer><?php */?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark" >
    <!-- Create the tabs -->
    <?php /*?><ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>

      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul><?php */?>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <?php /*?><div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div><?php */?>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="js/jquery-2.1.3.min.js" ></script>

<!-- jQuery 3 -->
<?php /*?><script src="lib/AdminLTE-2.4.0-rc/bower_components/jquery/dist/jquery.min.js"></script><?php */?>
<!-- Bootstrap 3.3.7 -->
<script src="lib/AdminLTE-2.4.0-rc/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="lib/AdminLTE-2.4.0-rc/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="lib/AdminLTE-2.4.0-rc/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="lib/AdminLTE-2.4.0-rc/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="lib/AdminLTE-2.4.0-rc/dist/js/demo.js"></script>
<script>
  $(document).ready(function () {
    $('.sidebar-menu').tree();
  })
</script>


<!-- eModal -->
<script src="lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal-admin.min.js"></script>
<style>
.modal-kk{
	width:200px;
	height:200px !important;
	border:10px solid red;
	overflow:auto;
}
</style>
<script type="text/javascript">
//		function changeElement('.modal-kk') {
//			var el = document.getElementsByClassName('.modal-kk');
//			el.style.color = "red";
//			el.style.fontSize = "15px";
//			el.style.backgroundColor = "#f00";
//		}

$(document).ready(function(){
	$(this).find(".modal-kk").css({"border": "2px solid red !important"});
	//$(this).find("body").css({'border':'2px solid red !important'});
});
	
	function setBreacrum(datamenu)
	{
		//alert(datamenu);return false;
		datamenu= datamenu.split(';'); 
		var templi= "";
		templi= '<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>';
		for(i=0; i < datamenu.length; i++)
		{
			if(i == datamenu.length - 1)
			{
				templi+= '<li class="active">'+datamenu[i]+'</li>';
			}
			else
			{
        		templi+= '<li>'+datamenu[i]+'</li>';
			}
		}
		//alert(templi);
		//<li class="active">Blank page</li>
		//$('#setBreacrum').html(templi);
		//$("iframe#mainFrame")[0].contentWindow.$('#setBreacrum').html(templi);
		window.frames['mainFrame'].window.setinfobreacrum(templi);
		//$("#mainFrame").prop('contentWindow').setinfobreacrum(templi);
		//$('#mainFrame')[0].contentWindow.setinfobreacrum(templi);
		//document.getElementById('mainFrame').contentWindow.setinfobreacrum(templi);
		//$("iframe#mainFrame").setinfobreacrum(templi);
		
		//$("#mainFrame").contents().find('setBreacrum').html(templi);
		//mainFrame.$('#setBreacrum').html(templi);
	}
	
	function openPopup(page) {
		eModal.iframe(page, 'SIMPEG KABUPATEN JOMBANG')
	}
	
	function openPopupModif(page, judul) {
		eModal.iframe({
		url: page,
		//size:ukuran,
		//size:"width=800,toolbar=1,resizable=1,scrollbars=yes,height=400,top=100,left=100",
		size:eModal.size.kk,
		title:judul
		});
	}
	
	function closePopup(pesan)
	{
		eModal.alert(pesan);		
		setInterval(function(){ document.location.reload(); }, 2000); 	
	}
	
	function closePopupImport()
	{
		eModal.close();
	}
	
	function hapusData(id, statusaktif)
	{
		alert('asdfasdf');
	}
	
	// OK
	//function openPopup(page) {
	//	eModal.iframe(page, 'Aplikasi Presensi - PJB Services')
	//}
	
</script>

<style type="text/css">
  .navbar-nav > .messages-menu > .dropdown-menu > li .menu > li > a > h4 {
      margin: 0 0 0 5px !important;
  }

  .navbar-nav > .messages-menu > .dropdown-menu > li .menu > li > a > p {
      margin: 0 0 0 5px !important;
  }
</style>
<!-- eModal Full -->
<script src="lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal-full.min.js"></script>
<script type="text/javascript">
	function createWindowFull(page) {
		eModalFull.iframe(page, 'SIMPEG KABUPATEN JOMBANG')
	}
	
	window.closeModal = function(){
		$('#iframeModal').modal('hide');
	};
</script>

<!-- eModal Max Full -->
<script src="lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal-maxfull.min.js"></script>
<script type="text/javascript">
	function createWindowMaxFull(page) {
		eModalMaxFull.iframe(page, 'SIMPEG KABUPATEN JOMBANG')
	}
	
	window.closeModal = function(){
		$('#iframeModal').modal('hide');
	};
</script>

<script type="text/javascript">
  function setInformasi()
  {
      // $("#setInformasiTotal,#setInformasiInfo").text("");
      $("#setInformasiTotal").text("");
      $('#setInformasiInfo').empty();
      $.ajax({'url': "informasi_data_json/set_info_total/?reqMode=1",'success': function(datahtml) {
        $("#setInformasiTotal").text(datahtml);
        // $.ajax({'url': "informasi_data_json/set_info_total/?reqMode=2",'success': function(datahtml) {
          // $("#setInformasiInfo").text(datahtml);
          $.ajax({'url': "informasi_data_json/set_info_detil/",'success': function(datahtml) {
            $('#setInformasiInfo').append(datahtml);

            $("#setKonsultasiTotal").text("");
            $('#setKonsultasiInfo').empty();
            $.ajax({'url': "informasi_data_json/set_konsultasi_total/?reqMode=1",'success': function(datahtml) {
              $("#setKonsultasiTotal").text(datahtml);
              $.ajax({'url': "informasi_data_json/set_konsultasi_detil/",'success': function(datahtml) {
                $('#setKonsultasiInfo').append(datahtml);
              }});

            }});

          }});
        // }});
      }});
  }

  // function adjustIframes()
  // {
  //   // alert("s");
  //   $('iframe').each(function(){
  //     var
  //     $this       = $(this),
  //     proportion  = $this.data( 'proportion' ),
  //     w           = $this.attr('width'),
  //     actual_w    = $this.width();
      
  //     if ( ! proportion )
  //     {
  //         proportion = $this.attr('height') / w;
  //         $this.data( 'proportion', proportion );
  //     }
    
  //     if ( actual_w != w )
  //     {
  //         // $this.css( 'height', Math.round( actual_w * proportion ) + 'px' );
  //         $this.css( 'height', '50000px' );
  //     }
  //   });
  // }

  function iframeIndexLoaded() {
    // alert("");
    var iFrameID = document.getElementById('mainFrame');
    if(iFrameID) {
        // here you can make the height, I delete it first, then I make it again
        iFrameID.height = "";
        // iFrameID.height = iFrameID.contentWindow.document.body.scrollHeight + "px";
        iFrameID.height = "1000px";
      }   
    }

  // $(function(){

  //     var iFrames = $('iframe');
      
  //     function iResize() {

  //       for (var i = 0, j = iFrames.length; i < j; i++) {
  //         iFrames[i].style.height = iFrames[i].contentWindow.document.body.offsetHeight + 'px';}
  //       }

  //       if ($.browser.safari || $.browser.opera) { 

  //        iFrames.load(function(){
  //          setTimeout(iResize, 100);
  //        });

  //        for (var i = 0, j = iFrames.length; i < j; i++) {
  //         var iSource = iFrames[i].src;
  //         iFrames[i].src = '';
  //         iFrames[i].src = iSource;
  //       }

  //     } else {
  //      iFrames.load(function() { 
  //        this.style.height = this.contentWindow.document.body.offsetHeight + 'px';
  //      });
  //    }

  //  });


  $(function(){
    // $(window).bind('resize load',adjustIframes);

    //300000 milliseconds = 300 seconds = 5 minutes
    //as 60000 milliseconds = 60 seconds = 1 minute.
    setInformasi();
    window.setInterval(function() {
      setInformasi();
    }, 300000);

  });
</script>

</body>
</html>