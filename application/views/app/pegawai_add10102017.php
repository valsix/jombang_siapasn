<?
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$this->load->model("Menu");

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
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Blank Page</title>
  <base href="<?=base_url()?>" />
  
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
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  
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
<body class="hold-transition skin-blue sidebar-mini fixed">
<!-- Site wrapper -->
<div class="wrapper">

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="lib/AdminLTE-2.4.0-rc/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Alexander Pierce</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <!--<form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>-->
      
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
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
        </li>
        
      </ul>
      
      <!--- ####### --->
      <!--<div class="sidebar-nav navbar-collapse">-->
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
            </li>
        </ul>
    <!--</div>-->
    <!-- /.sidebar-collapse -->

    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <?php /*?><!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Blank page
        <small>it all starts here</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Examples</a></li>
        <li class="active">Blank page</li>
      </ol>
    </section><?php */?>

    <!-- Main content -->
    <section class="content">
		
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

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.0
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>

      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
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
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
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

<!-- jQuery 3 -->
<script src="lib/AdminLTE-2.4.0-rc/bower_components/jquery/dist/jquery.min.js"></script>
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
    $('.sidebar-menu').tree()
  })
</script>
</body>
</html>
