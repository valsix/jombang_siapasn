<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$this->load->model('Menu');

$menu = new Menu();

// $this->load->model('NotifikasiPesan');

// $notifikasi_pesan_popup = new NotifikasiPesan();
// $jumlah_notifikasi_popup = $notifikasi_pesan_popup->getCountByParams($this->ID, date("Y"));
$jumlah_notifikasi_popup=0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"> 
    <meta charset="utf-8">
    <title>Presensi</title>
    <base href="<?=base_url()?>">
    <meta name="generator" content="Bootply" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="Bootstrap In this tutorial, I want to show you how to create a beautiful website template that gives a wonderful look and comfort of your Web applicat" />
    <link href="lib/bootstrap/css/bootstrap.css" rel="stylesheet">


    <!-- CSS code from Bootply.com editor -->
    <link rel="stylesheet" href="css/gaya.css" type="text/css">
    <link rel="stylesheet" href="css/gaya-bootstrap.css" type="text/css">
    <!--<link rel="stylesheet" href="css/gaya-navbar-hover.css" type="text/css">-->
    
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">
    
    <!-- TABLE RESPONSIVE -->
    <!--[if !IE]><!-->
	<style>

	/*
	Max width before this PARTICULAR table gets nasty
	This query will take effect for any screen smaller than 760px
	and also iPads specifically.
	*/
	@media
	only screen and (max-width: 760px),
	(min-device-width: 768px) and (max-device-width: 1024px)  {

		/* Force table to not be like tables anymore */
		.area-rekap-kehadiran-bawahan table,
		.area-rekap-kehadiran-bawahan thead,
		.area-rekap-kehadiran-bawahan tbody,
		.area-rekap-kehadiran-bawahan th,
		.area-rekap-kehadiran-bawahan td,
		.area-rekap-kehadiran-bawahan tr {
			display: block;
		}

		/* Hide table headers (but not display: none;, for accessibility) */
		.area-rekap-kehadiran-bawahan thead tr {
			position: absolute;
			top: -9999px;
			left: -9999px;
		}
		.area-rekap-kehadiran-bawahan tr:first-child { 
			*display:none;
		}
		.area-rekap-kehadiran-bawahan tr {
			*border: 1px solid red /*#ccc*/; 
			margin-bottom:4px;
		}

		.area-rekap-kehadiran-bawahan td {
			/* Behave  like a "row" */
			border: none;
			*border-bottom: 1px solid  green /*#eee*/;
			margin-bottom:1px;
			position: relative;
			padding-left: calc(50% - 0px) !important;
			text-align:left !important;
		}

		.area-rekap-kehadiran-bawahan td:before {
			/* Now like a table header */
			position: absolute;
			/* Top/left values mimic padding */
			top: 12px;
			left: 6px;
			width: calc(50% - 10px);
			padding-right: 10px;
			white-space: nowrap;
			
			*background:cyan;
			text-align:right;
		}

		/*
		Label the data
		*/
		.area-rekap-kehadiran-bawahan td:nth-of-type(1):before { content: "NRP :"; }
		.area-rekap-kehadiran-bawahan td:nth-of-type(2):before { content: "Nama :"; }
		.area-rekap-kehadiran-bawahan td:nth-of-type(3):before { content: "Kehadiran :"; }
		/*.area-rekap-kehadiran-bawahan td:nth-of-type(4):before { content: "Tidak Hadir :"; }*/
		.area-rekap-kehadiran-bawahan td:nth-of-type(4):before { content: "Ijin :"; }
		.area-rekap-kehadiran-bawahan td:nth-of-type(5):before { content: "Cuti :"; }
		.area-rekap-kehadiran-bawahan td:nth-of-type(6):before { content: "Alpha :"; }
		.area-rekap-kehadiran-bawahan td:nth-of-type(7):before { content: "Dinas :"; }
		
	}

	/* Smartphones (portrait and landscape) ----------- */
	@media only screen
	and (min-device-width : 320px)
	and (max-device-width : 480px) {
		/*body {
			padding: 0;
			margin: 0;
			width: 320px; }
		}*/

	/* iPads (portrait and landscape) ----------- */
	@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
		/*body {
			width: 495px;
		}*/
	}

	</style>
	<!--<![endif]-->
    
    <style>
	.scrollable-menu {
		height: auto;
		max-height: 300px;
		overflow-x: hidden;
	}
    </style>
</head>    

<body class="body-pjb">

    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <!--<div class="navbar navbar-default navbar-fixed-top" role="navigation">-->
        <div class="container-fluid">
            <div class="navbar-header">
            	
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                
            <!--<a class="navbar-brand" href="#">Project name</a>-->
            
                <!--<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>-->
                <span class="nama-aplikasi"><img src="images/logo-pjb-presensi.png"></span>
                
                
                <div class="area-tombol-logout">

                    <div class="info-user">
                        <div class="selamat-datang">Selamat datang,</div>
                        <div class="nama"><?=$this->FIRST_NAME?></div>                                
                        <div class="jabatan"><?=getFormattedDate(date("Y-m-d"));?></div>
                    </div>
                    
                    <div class="area-tombol">
                    	
                        <div class="notifikasi-faq-logout">
                        
                        	<div class="area-unread-notifikasi dropdown" >
                                <a id="dLabel" role="button" data-toggle="dropdown" data-target="#" title="Notifikasi" class="notifikasi" onClick="loadNotifikasi()" >
                                    <!--<i class="glyphicon glyphicon-bell"></i> -->
                                    <i class="fa fa-bell" aria-hidden="true"></i>
                                    <span class="jumlah-notifikasi" id="jumlah-notifikasi"><?=$jumlah_notifikasi_popup?></span> 
                                    <!--<span class="ket">notifikasi</span>-->
                                </a>
                              
                              <ul class="dropdown-menu dropdown-menu-left notifications" role="menu" aria-labelledby="dLabel">

                               <div class="notifications-wrapper" id="isi-notifikasi">
                                 
                               </div>

                              </ul>
                              
                            </div>
                            
                            
                        	<!--<a id="dLabel" role="button" data-toggle="dropdown" data-target="#" title="Notifikasi" class="notifikasi" >-->
                        	<!--<a href="#" title="Notifikasi" class="notifikasi"><i class="fa fa-bell" aria-hidden="true"></i></a>-->
                            <a href="#" title="FAQ/Help" class="faq"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                            <a href="login/logout" title="Logout" class="logout" onclick="window.ReactNativeWebView.postMessage('logout')"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
                        </div>
                        
                        <!--<a href="login/logout" class="logout"><span>Logout</span></a>-->
                        
                        <!--<div class="area-rekap-kehadiran-bawahan-overlay dropdown">-->
                        <div class="area-rekap-kehadiran-bawahan-overlay">
                        	
                            <?
                            if($this->STATUS_ATASAN == 1)
							{
							?>
                            <a id="dLabel" role="button" data-toggle="dropdown" data-target="#" class="lihat-bawahan" onClick="loadRekapBawahan($('#reqPeriodeRekapBawahan').val()); $('#area-bawahan-detil').hide();" >
                                <span>Rekap Kehadiran Bawahan</span>
                            </a>
                            <?
							}
							?>   
                            <ul class="dropdown-menu dropdown-menu-right notifications" role="menu" aria-labelledby="dLabel">
                                <div class="area-untuk-pimpinan" style="background:#a1a1a1;">
                                    <div class="area-rekap-kehadiran-bawahan">
                                      <div class="judul-area">
                                      	<i class="fa fa-list-alt" aria-hidden="true"></i> Rekap Kehadiran Bawahan 
                                        <select id="reqPeriodeRekapBawahan" onChange="loadRekapBawahan(this.value); $('#area-bawahan-detil').hide();">
                                        <?
                                        for($i=0;$i<6;$i++)
										{
											$periodeRekapKehadiran = date('mY', strtotime(date("Y-m"). ' - '.$i.' month'));
										?>
                                        	<option value="<?=$periodeRekapKehadiran?>"><?=getNamePeriode($periodeRekapKehadiran)?></option>
                                        <?
										}
										?>
                                        </select>
                                      </div>
                                        <table id="Demo">
                                        <thead>
                                            <tr>
                                                <th class="headerHor">NRP</th>
                                                <th class="headerHor">Nama</th>
                                                <th class="headerHor">H</th>
                                                <th class="headerHor">I</th>
                                                <th class="headerHor">C</th>
                                                <th class="headerHor">A</th>
                                                <th class="headerHor">D</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyRekapBawahan">
                                        </tbody>
                                        </table>
                    
                                    </div>
                                    
                                  <div class="area-tidak-hadir-3kali" id="area-bawahan-detil" style="display:none">
                                      <div class="judul-area" id="judul-area-bawahan-detil"></div>
                                      <table id="Demo2">
										<tbody id="tbodyRekapBawahanDetil">
										</tbody>
                                      </table>
                    
                                    </div>
                                    
                                    
                                </div>
                                
                            </ul>
                        </div>
                            
                    </div>
                    
                </div>
                
            </div>
            
            <div class="navbar-collapse collapse menu-utama-presensi">
            	
                <!-- Right nav -->
                <ul class="nav navbar-nav navbar-right">
                	
                    <?
					/*if($this->USER_GROUP_ID == "")
					{
						$reqUserGroupId = "0";
						$statement_global = " ";
						// $statement_privacy = " AND STATUS_DEFAULT = '1'";
						$statement_privacy = " AND COALESCE(NULLIF(A.STATUS_DEFAULT, ''), NULL) IS NULL";
					}
					else
					{
						$reqUserGroupId = $this->USER_GROUP_ID;
						$statement_global = "  AND EXISTS (SELECT 1 FROM USER_GROUP_MENU X WHERE X.MENU_ID = A.MENU_ID AND USER_GROUP_ID = ".$reqUserGroupId.")";
						$statement_privacy = " ";
					}*/
					
					
					$statement = " AND MENU_PARENT_ID = '0' ";
					$statement.= " AND A.MENU_ID IN (SELECT MENU_ID FROM AKSES_APP_ABSENSI_MENU WHERE AKSES_APP_ABSENSI_ID = ".$this->AKSES_APP_ABSENSI_ID.")";
					// $statement .= $statement_global.$statement_privacy;
					$menu->selectByParams(array(), -1, -1, $statement, "ORDER BY URUT ASC");
					// echo $menu->query;exit();
					
					while($menu->nextRow())
					{
						if(stristr($_SERVER['HTTP_USER_AGENT'], "Mobile") && $menu->getField("MENU_ID") == '04')
						{}
						else
						{
							if($menu->getField("CHILD") > 0 )
							{	
								?>
								<li><a href="#"><?=$menu->getField("NAMA")?> <span class="caret"></span></a>
									<ul class="dropdown-menu">
									<?
										$menu_parent = new Menu();
										$statement_parent = " AND MENU_PARENT_ID = '".$menu->getField("MENU_ID")."'";
										$statement_parent .= $statement_global;
										$menu_parent->selectByParams(array(), -1, -1, $statement_parent, " ORDER BY URUT ASC");
										
										while($menu_parent->nextRow())
										{
											$menu_parent_id= $menu_parent->getField("MENU_ID");
											if($this->STATUS_ATASAN != 1 && $menu_parent->getField("STATUS_DEFAULT") == '2')
											{}
											else
											{
												if($menu_parent->getField("CHILD") > 0)
												{
												?>
													<li><a href="#"><?=$menu_parent->getField("NAMA")?> <span class="caret"></span></a>
														<ul class="dropdown-menu">
														<?
															$menu_child = new Menu();
															$statement_child = " AND MENU_PARENT_ID = '".$menu_parent->getField("MENU_ID")."' ";
															$statement_child .= $statement_global;
															$menu_child->selectByParams(array(), -1, -1, $statement_child, " ORDER BY URUT ASC");
															
															while($menu_child->nextRow())
															{
																$menu_child_id= $menu_child->getField("MENU_ID");
																if($menu_child->getField("CHILD") > 0)
																{
																	?>
																	<li><a href="#"><?=$menu_child->getField("NAMA")?> <span class="caret"></span></a>
																		<ul class="dropdown-menu">
																	<?
																	$menu_child_sub = new Menu();
																	$statement_child_sub = "AND MENU_PARENT_ID = '".$menu_child->getField("MENU_ID")."' ";
																	$statement_child_sub .= $statement_global;
																	$menu_child_sub->selectByParams(array(), -1, -1, $statement_child_sub, "ORDER BY URUT ASC");
																	while($menu_child_sub->nextRow())
																	{
																	?>
																		<li><a href="<?=$menu_child_sub->getField("LINK_FILE")?>" class="menu-item" target="mainFrame"><?=$menu_child_sub->getField("NAMA")?></a></li>
																	<?
																	}
																	?>
																		</ul>
																	</li>
																<?
																}
																else
																{

																	if($this->STATUS_ATASAN != 1 && $menu_child->getField("STATUS_DEFAULT") == '2')
																	{}
																	else
																	{
																		if($reqUserGroupId == '0' && $menu_child->getField("STATUS_DEFAULT") == '3')
																		{}
																		else
																		{
																			$menu_child_nama= $menu_child->getField("NAMA");
																			$menu_child_link= $menu_child->getField("LINK_FILE");
																			// kalau dalam if maka masuk ke app bio
																			// if($menu_child_id == "040601" || $menu_child_id == "040602" || $menu_child_id == "040603")
																			// {
																			// 	$menu_child_link= $menu_child->getField("LINK_FILE");
																			// }
																	?>
																			<li><a href="<?=$menu_child_link?>" class="menu-item" target="mainFrame"><?=$menu_child_nama?></a></li>
																	<?
																		}
																	}
																}
															}
														?>
														</ul>
													</li>
												<?
												}
												else
												{
													?>
														<li><a href="<?=$menu_parent->getField("LINK_FILE")?>" class="menu-item" target="mainFrame"><?=$menu_parent->getField("NAMA")?></a></li>
													<?
												}
											}
										}
									?>
									</ul>
								</li>
								<?
							}
							else
							{
							?>
								<li><a href="<?=$menu->getField("LINK_FILE")?>" class="utama menu-item" target="mainFrame"><?=$menu->getField("NAMA")?></a></li>
							<?
							}
						}
					}
                    ?>
                </ul>
            </div><!--/.nav-collapse -->
        
        </div>
    </nav>
    
    <div class="container-fluid area-index">
    <? 
    	if($this->session->userdata('currentUrl') == "")
			// $link = "main/dashboard";
			$link = "main/default";
		else
			$link = $this->session->userdata('currentFolder')."/".$this->session->userdata('currentUrl');	
	?>
		<iframe id="mainFrame" name="mainFrame" src="app/loadUrl/<?=$link?>"></iframe>
        <!--<iframe name="mainFrame" src="home.php"></iframe>-->
    </div>
            
	<script type='text/javascript' src="lib/bootstrap/js/jquery.js"></script>
    <script type='text/javascript' src="lib/bootstrap/js/bootstrap.js"></script>
	
    <!-- SMARTMENU -->
    <!-- SmartMenus jQuery Bootstrap Addon CSS -->
    <link href="lib/smartmenus-1.0.1/addons/bootstrap/jquery.smartmenus.bootstrap.css" rel="stylesheet">

    <!-- SmartMenus jQuery plugin -->
    <script type="text/javascript" src="lib/smartmenus-1.0.1/jquery.smartmenus.js"></script>
    <!-- SmartMenus jQuery Bootstrap Addon -->
    <script type="text/javascript" src="lib/smartmenus-1.0.1/addons/bootstrap/jquery.smartmenus.bootstrap.js"></script>


    
    <?php
	if(stristr($_SERVER['HTTP_USER_AGENT'], "Mobile")){ // if mobile browser
	?>
    <script>
	$('.nav a.menu-item').on('click', function(){
		$('.btn-navbar').click(); //bootstrap 2.x
		$('.navbar-toggle').click() //bootstrap 3.x by Richard
	});
	</script>
    <?
	} else { }
	?>
        
    <!-- EMODAL -->
    
    <script src="lib/emodal/eModal-aksi.js"></script>
    <script src="lib/emodal/eModal-shift.js"></script>
    <script>
    function openPopup(pageUrl) {
        // eModalAksi.iframe(pageUrl, 'Aplikasi Presensi | PT Pembangkitan Jawa Bali')
        eModalAksi.iframe(pageUrl, 'Aplikasi Presensi')
    }
	
	function closePopup() {
		eModalAksi.close();
	}
	
	function openPopupShift(pageUrl) {
        // eModalShift.iframe(pageUrl, 'Aplikasi Presensi | PT Pembangkitan Jawa Bali')
        eModalShift.iframe(pageUrl, 'Aplikasi Presensi')
    }
	
	function closePopupShift() {
		eModalShift.close();
	}
	
    </script>
	
    <script>
	$(document).ready(function() {
		ambilJumlahNotifikasi();
		setTimeout(ambilJumlahNotifikasi(), 100000);
	});
	
	function ambilJumlahNotifikasi()
	{

		/*var jqxhr = $.get( "notifikasi_json/ambil_jumlah_notifikasi", function(data) {
		  $("#jumlah-notifikasi").text(data);
		});*/
		 
		
	}
	
	function loadNotifikasi()
	{
		$("#isi-notifikasi").empty();
		$.getJSON("notifikasi_json/ambil_notifikasi", function(data) {
			$.each(data, function(key, value) {
				 $("#isi-notifikasi").append('<a class="content" href="'+value.LINK_FILE+'" target="mainFrame"> <div class="notification-item">'+value.NOTIFIKASI+'</div></a> ');
			});
		});
		
	}

	function loadRekapBawahan(periode)
	{
		$("#tbodyRekapBawahan").empty();
		$("#tbodyRekapBawahan").append('<tr><td colspan="7" style="text-align:center">Processing . . . .</td></tr>');
		$.getJSON("rekapitulasi_json/ambil_rekap_kehadiran_bawahan/?reqPeriode="+periode, function(data) {
			$("#tbodyRekapBawahan").empty();
			$.each(data, function(key, value) {
				$("#tbodyRekapBawahan").append('<tr><td class="headerVer">'+value.PEGAWAI_ID+'</td><td>'+value.NAMA_ASLI+'</td><td onClick="loadRekapBawahanDetil('+"'" + value.PEGAWAI_ID + "'" +', '+"'" + value.NAMA + "'" +', '+"'" + periode + "'" +', '+"'" + 'efektif' + "'" +')">'+value.EFEKTIF+'</td><td onClick="loadRekapBawahanDetil('+"'" + value.PEGAWAI_ID + "'" +', '+"'" + value.NAMA + "'" +', '+"'" + periode + "'" +', '+"'" + 'ijin' + "'" +')">'+value.IJIN+'</td><td onClick="loadRekapBawahanDetil('+"'" + value.PEGAWAI_ID + "'" +', '+"'" + value.NAMA + "'" +', '+"'" + periode + "'" +', '+"'" + 'cuti' + "'" +')">'+value.CUTI+'</td><td onClick="loadRekapBawahanDetil('+"'" + value.PEGAWAI_ID + "'" +', '+"'" + value.NAMA + "'" +', '+"'" + periode + "'" +', '+"'" + 'alpha' + "'" +')">'+value.ALPHA+'</td><td onClick="loadRekapBawahanDetil('+"'" + value.PEGAWAI_ID + "'" +', '+"'" + value.NAMA + "'" +', '+"'" + periode + "'" +', '+"'" + 'dinas' + "'" +')">'+value.DINAS+'</td></tr>');
			});
		});                           
	}

	function loadRekapBawahanDetil(pegawaiid,namapegawai,periode, kode)
	{
		$("#area-bawahan-detil").show();
		$("#judul-area-bawahan-detil").text("DETIL " + kode + " (" + namapegawai + " - " + pegawaiid + ")");
		$("#tbodyRekapBawahanDetil").empty();
		$.getJSON("rekapitulasi_json/ambil_rekap_kehadiran_bawahan_detil/?reqPeriode="+periode+"&reqKode="+kode+"&reqPegawaiId="+pegawaiid, function(data) {
			$.each(data, function(key, value) {
				 $("#tbodyRekapBawahanDetil").append('<tr><td class="headerVer"><span class="jumlah">'+value.HARI+'</span> '+value.MASUK_KETERANGAN+'</td></tr>');
			});
		});
		                                            
	}
		
	$(document).on('click', '.area-rekap-kehadiran-bawahan-overlay .dropdown-menu', function (e) {
		e.stopPropagation();
	});
	</script>
    
    <!-- SCROLLING TABLE MASTER -->
    <link rel="stylesheet" href="lib/ScrollingTable-master/style.css" />
    <script src="lib/ScrollingTable-master/scrollingtable.js"></script>
    <script>
        $('#Demo').ScrollingTable();
        $('#Demo2').ScrollingTable();
    </script>

    
    <!-- FIRST TIME VISIT -->
    <script src="lib/first-time-visit/js/jquery.js" type="text/javascript"></script>
	
	<script src="lib/first-time-visit/js/jquery_002.js" type="text/javascript"></script>
    <script type="text/javascript">
             var popupStatus = 0;
    
    //loading popup with jQuery magic!
    function loadPopup(){
        centerPopup();
        //loads popup only if it is disabled
        if(popupStatus==0){
            $("#backgroundPopup").css({
                "opacity": "0.7"
            });
            $("#backgroundPopup").fadeIn("slow");
            $("#popupContact").fadeIn("slow");
            popupStatus = 1;
        }
    }
    
    //disabling popup with jQuery magic!
    function disablePopup(){
        //disables popup only if it is enabled
        if(popupStatus==1){
            $("#backgroundPopup").fadeOut("slow");
            $("#popupContact").fadeOut("slow");
            popupStatus = 0;
        }
    }
    
    //centering popup
    function centerPopup(){
        //request data for centering
        var windowWidth = document.documentElement.clientWidth;  
        var windowHeight = document.documentElement.clientHeight;  
        var windowscrolltop = document.documentElement.scrollTop; 
        var windowscrollleft = document.documentElement.scrollLeft; 
        var popupHeight = $("#popupContact").height();
        var popupWidth = $("#popupContact").width();
        var toppos = windowHeight/2-popupHeight/2+windowscrolltop;
        var leftpos = windowWidth/2-popupWidth/2+windowscrollleft;
        //centering
        $("#popupContact").css({
            "position": "absolute",
            "top": toppos,
            "left": leftpos
        });
        //only need force for IE6
        
        $("#backgroundPopup").css({
            "height": windowHeight
        });
        
    }
    
    </script>
    <style>
    #popupContactClose{
    cursor: pointer;
    text-decoration:none;
    }
    #backgroundPopup{
        display:none;
        position:fixed;
        _position:absolute; /* hack for internet explorer 6*/
        height:100%;
        width:100%;
        top:0;
        left:0;
        background:#000000;
        border:1px solid #cecece;
        z-index:9999;
    }
    @media screen and (max-width:767px) {
        #backgroundPopup{
            border:none;
        }
    }
    #popupContact{
        display:none;
        position:fixed;
        _position:absolute; /* hack for internet explorer 6*/
        height:384px;
        width:408px;
        background:#edece7;
        *border:2px solid #cecece;
        z-index:10000;
        *padding:12px;
        font-size:13px;
        
        -webkit-border-radius: 7px;
        -moz-border-radius: 7px;
        border-radius: 7px;
    }
    @media screen and (max-width:767px) {
        #popupContact{
            width:90%;
            height:80%;
        }
    }
    #popupContact .header{
        background:#fefb00;
        display:inline-block;
        width:100%;
        
        -webkit-border-top-left-radius: 7px;
        -webkit-border-top-right-radius: 7px;
        -moz-border-radius-topleft: 7px;
        -moz-border-radius-topright: 7px;
        border-top-left-radius: 7px;
        border-top-right-radius: 7px;
        
        border-bottom:1px solid #d0d0d0;
    
    }
    #popupContact h1{
        text-align:left;
        color:#333;
        font-size:18px;
        text-transform:uppercase;
        padding:0 20px;
        *font-weight:700;
        
        padding-bottom:2px;
        *margin-bottom:20px;
        
        *background:red;
    }
    #popupContactClose{
        font-size:14px;
        line-height:14px;
        right:10px;
        top:10px;
        position:absolute;
        color:red;
        font-weight:700;
        display:block;
    }
    #popupContact .sub-header{
        background:#dbdbdb;
        padding:10px 20px;
        font-size:16px;
        font-weight:bold;
    }
    #contactArea{
        height:280px !important;
        overflow:auto !important;
    }
    #contactArea ul{
        list-style-type:decimal;
        *border:1px solid cyan;
        
        *padding:0 0;
        padding-right:20px;
    }
    #contactArea ul li{
        border-bottom:1px solid rgba(0,0,0,0.05);
        padding:10px 0 10px 20px;
        *display:inline-block;
        *width:100%;
        
        clear:both;
        float:left;
        width:100%;
    }
    #contactArea ul li span{
        float:left;
        width:calc(100% - 60px);
        font-size:16px;
        padding-right:15px;
    }
    #contactArea ul li a{
        float:right;
        width:60px;
        height:60px;
        line-height:60px;
        text-align:center;
        border:1px solid rgba(255,255,255,0.5);
        
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
    
    }
    #contactArea ul li i{
        display:inline-block;
        *border:1px solid red;
        line-height:60px;
        color:#999;
        
    }
    @media screen and (max-width:767px) {
        #contactArea{
            overflow:auto;
            *border:1px solid red;
            *max-height:calc(100vh - 230px);
            height:calc(100% - 100px);
        }
    }
    
    </style>
	
    <!-- EMODAL -->
	<script src="lib/startbootstrap-sb-admin-2-1.0.7/dist/js/eModal.min.js"></script>
    <style>
	.modal-kk{
		width:200px;
		height:200px !important;
		border:10px solid red;
		overflow:auto;
	}
	.modal-dialog{
		border:0px solid red;
		height:calc(100% - 120px) !important;
	}
	.dataTables_wrapper.no-footer{
		background-color:transparent;
	}
	</style>
    
    <script>
//	$('a.lihat-bawahan').on('click', function (event) {
//		alert("hooo");
//		$(this).parent().toggleClass("open");
//	});
//	
//	$('body').on('click', function (e) {
//    if (!$('a.lihat-bawahan').is(e.target) && $('a.lihat-bawahan').has(e.target).length === 0 && $('.open').has(e.target).length === 0) {
//        $('a.lihat-bawahan').removeClass('open');
//    }
	</script>
    
    <?php
	if(stristr($_SERVER['HTTP_USER_AGENT'], "Mobile")){ // if mobile browser
	?>
	<script>
	$("ul.dropdown-menu a.menu-anak").click(function(){
		//$('.btn-navbar').click(); //bootstrap 2.x
		$('.navbar-toggle').click() //bootstrap 3.x by Richard
	});
	</script>
	<?
	} else {
	?>
	<script>
	//alert("desktop");
    </script>
    <?
		}
	?>
    
    </body>
</html>