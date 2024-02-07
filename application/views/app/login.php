<?
$this->load->library("crfs_protect"); $csrf = new crfs_protect('_crfs_login');
?>
<html>
<head>
	<title>SIAP ASN - BKPSDM JOMBANG</title>
	<base href="<?=base_url()?>" />
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">

	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

	<link href="css/gaya.css" rel="stylesheet" type="text/css">  
	<link href="css/begron.css" rel="stylesheet" type="text/css">  
	<link href="lib/font-awesome-4.7.0/css/font-awesome.css" rel="stylesheet" type="text/css">  

	<link rel="stylesheet" href="lib/velocity/css/style.css">

	<link rel="stylesheet" type="text/css" href="lib/vegas/jquery.vegas.min.css">
	<script type="text/javascript" src="lib/vegas/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="lib/vegas/jquery.vegas.min.js"></script>

	<script>
		$( function() {
			$.vegas( 'slideshow', {
				delay: 8000,
				backgrounds: [
				{ src: 'images/bg1n.png', fade: 4000 },
				{ src: 'images/bg2n.png', fade: 4000 },
				{ src: 'images/bg3n.png', fade: 4000 },
				{ src: 'images/bg4n.png', fade: 4000 }
			]
		} )( 'overlay' );

		$( '.documentation' ).click( function() {
			$( 'ul ul' ).slideToggle();
			return false;
		});

		$( '.credits, .contact' ).click( function() {
			$( '#overlay, #credits' ).fadeIn();
			return false;
		});

		$( '#overlay a, #credits a' ).click( function(e) {
			e.stopPropagation();
		});

		$( '#overlay, #credits, #download' ).click( function() {
			$( '#overlay, #credits, #download' ).fadeOut();
			return false;
		});

		$( '.mailto' ).click( function() {
			var a = $( this ).attr( 'href' );
			e = a.replace( '#', '' ).replace( '|', '@' ) + '.com';
			document.location = 'ma' + 'il' + 'to:' + e + "?subject=[Vegas] I'd like to hire you!";
			e.preventDefault;
			return false;
		});

		$("#superheader h6").click(function(e) {
			var $$ = $( this ),
			$menu = $('#superheader ul');

			e.stopPropagation();

			if ( $menu.is(':visible') ) {
				$menu.hide();
				$$.removeClass( 'open' );
			} else {
				$menu.show();
				$$.addClass( 'open' );
				$('body').one('click', function() {
					$('#superheader ul').hide();
				});
			}
		});
		$( "#superheader li" ).click( function( e ) {
			document.location = $( this ).find( 'a' ).attr( 'href' );
		});
		
		$( '.download' ).click( function() {
			$( '#overlay, #download' ).show();	  
		});
	} );
</script>

<style>
#footer{
	position: fixed;
	bottom: 0px;
	background: url(images/hr-footer.png) top center no-repeat;
	width: 100%;
	*height: 80px;

	background: #56CCF2;  /* fallback for old browsers */
	background: -webkit-linear-gradient(to top, #5d007d, #4d0068);  /* Chrome 10-25, Safari 5.1-6 */
	background: linear-gradient(to top, #5d007d, #4d0068); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

}

#footer-kiri{
	float:left;
	color:#FFF;
	font-size:13px;
	padding-top:14px;
	padding-left:50px;
}
#footer-kiri span{
	font-weight:bold;
}
#footer-kanan{
	float:right;
	color:#FFF;
	font-size:13px;
	padding-top:14px;
	padding-right:10px;
	text-align:right;
}
#footer-kanan span{
	font-weight:bold;
}

#footer-waktu{
	background:url(images/bg-hitam-opacity.png);
	*float:right;
	padding-top:10px;
	padding-right:130px;
	padding-left:130px;
	*height:49px;
	*margin-top:1px;
	color:#deb09a;
}
#footer-waktu img{
	vertical-align:middle;
}
#footer-waktu-ikon{
	float:left;
	margin-right:10px;
}
#footer-waktu-jam{
	float:left;
	font-size:14px;
	line-height:20px;
}
#footer-waktu-hari{
	float:left;
	font-size:14px;
}


#footer-waktu font{
	color: #fff;
}

#login-area-atas{
	width:400px; 
	height:50px; 
	margin:50px auto 0;
	padding: 20px 10px 0px 10px;
	
	-webkit-border-radius: 0px;
	-moz-border-radius: 0px;
	border-top-left-radius: 30px;
	border-top-right-radius: 30px;
	box-shadow: 0px 0px 3px 0px #000;

	background: #1c92d2;  /* fallback for old browsers */
	background: -webkit-linear-gradient(to top, #f2fcfe, #1c92d2);  /* Chrome 10-25, Safari 5.1-6 */
	background: linear-gradient(to top, #22c836, #76defe); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

}

#login-area-bawah{
	width:400px; 
	height:50px; 
	margin:10px auto 0;
	padding: 20px 10px 0px 10px;
	
	-webkit-border-radius: 0px;
	-moz-border-radius: 0px;
	border-bottom-left-radius: 30px;
	border-bottom-right-radius: 30px;
	box-shadow: 0px 0px 3px 0px #000;

	background: #1c92d2;  /* fallback for old browsers */
	background: -webkit-linear-gradient(to bottom, #f2fcfe, #1c92d2);  /* Chrome 10-25, Safari 5.1-6 */
	background: linear-gradient(to bottom, #f2fcfe, #1c92d2); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */


}

#login-area{
	position:relative;
	z-index:5;
	width:400px; 
	height:200px; 
	margin: 10px auto;
	padding: 20px 10px 0px 10px;
	
	-webkit-border-radius: 0px;
	-moz-border-radius: 0px;

	background: #C9D6FF;  /* fallback for old browsers */
	background: -webkit-linear-gradient(to right, #E2E2E2, #C9D6FF);  /* Chrome 10-25, Safari 5.1-6 */
	background: linear-gradient(to right, #E2E2E2, #C9D6FF); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

	box-shadow: 0px 0px 3px 0px #000;
}

#login-area input[type = text],
#login-area input[type = password],
#inline1 input[type = password]{

	width: 100%;	
	height:28px;
	border:1px solid #9090f5;
	margin-bottom:14px;
	padding-left:10px;
	padding-right:10px;	
	border-radius: 20px;
}

#login-area input[type = submit],
#inline1 input[type = submit]{
	width: 360px;
	height: 30px;
	background-color: #9090f5;
	border-color: #367fa9;
	color: #fff;
	font-size: 14px;
	border: none;
	font-weight: bold;
	border-radius: 20px;
}

#login-area-bawah img{
	vertical-align:middle;
	margin-right:10px;
}

.box-header {
    background-color: #665851;
    margin-top: 0;
    border-radius: 5px 5px 0 0;
}

/*small screen sizes*/
/*@media (max-width: 991px) {
    #footer{
        display:none !important;
    }
}*/
</style>

<!-- LIVE DATE -->
<!-- <script type="text/javascript" src="lib/liveDate/live_date.js"></script> -->
<!--============================================NEW===================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="assets/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="assets/vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="assets/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="assets/vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="assets/css/util.css">
	<link rel="stylesheet" type="text/css" href="assets/css/main.css">
<!--===========================================NEW====================================================-->

</head>

<body scroll="no" onLoad="javascript:frmLogin.reqUser.focus()" style="overflow:hidden;">

	<?php /*?><div id="begron"><img src="images/bg-login-admin.jpg" width="100%" height="100%" alt="Smile"></div><?php */?>
	
	<div id="wadah">
	
		<div class="header-login-admin">
			<span><img src="images/logo.png"></span>
		</div class="box-header">  

		<div style="clear:both"></div>

		<div class="container">
		<form name="frmLogin" action="app/login" method="post">
		<div id="login" class="login">
			<div class="login-icon-field" style="text-align: center;">
				<img src="images/logo1.png" width="250px">
			</div>
			<div class="login-form">
				<div class="username-row row">
					<label for="username_input">
						<svg version="1.1" class="user-icon" x="0px" y="0px"
						viewBox="-255 347 100 100" xml:space="preserve" height="36px" width="30px">
						<path class="user-path" d="
						M-203.7,350.3c-6.8,0-12.4,6.2-12.4,13.8c0,4.5,2.4,8.6,5.4,11.1c0,0,2.2,1.6,1.9,3.7c-0.2,1.3-1.7,2.8-2.4,2.8c-0.7,0-6.2,0-6.2,0
						c-6.8,0-12.3,5.6-12.3,12.3v2.9v14.6c0,0.8,0.7,1.5,1.5,1.5h10.5h1h13.1h13.1h1h10.6c0.8,0,1.5-0.7,1.5-1.5v-14.6v-2.9
						c0-6.8-5.6-12.3-12.3-12.3c0,0-5.5,0-6.2,0c-0.8,0-2.3-1.6-2.4-2.8c-0.4-2.1,1.9-3.7,1.9-3.7c2.9-2.5,5.4-6.5,5.4-11.1
						C-191.3,356.5-196.9,350.3-203.7,350.3L-203.7,350.3z"/>
					</svg>
				</label>
				 <!-- style="padding-left: 40px; width: 80%" -->
				<input type="text" style="padding-left: 40px; width: 80%" id="username_input" name="reqUser" class="username-input" placeholder="Username" autocomplete="oxff" rexadonly onfocus="this.removeAttribute('rexadonly');" />
				</div>
				<div class="password-row row">
				<label for="password_input">
						<svg version="1.1" class="password-icon" x="0px" y="0px"
						viewBox="-255 347 100 100" height="36px" width="30px">
						<path class="key-path" d="M-191.5,347.8c-11.9,0-21.6,9.7-21.6,21.6c0,4,1.1,7.8,3.1,11.1l-26.5,26.2l-0.9,10h10.6l3.8-5.7l6.1-1.1
						l1.6-6.7l7.1-0.3l0.6-7.2l7.2-6.6c2.8,1.3,5.8,2,9.1,2c11.9,0,21.6-9.7,21.6-21.6C-169.9,357.4-179.6,347.8-191.5,347.8z"/>
					</svg>
				</label>
				<input style="padding-left: 40px; width: 80%" type="password" id="password_input" name="reqPasswd" class="password-input" class="input" placeholder="Password" autocomplete="oxff" rexadonly onfocus="this.removeAttribute('rexadonly');" />
				</div>
			</div>
			<?=$csrf->echoInputField();?>
			<div style="text-align: center;">
				<input type="hidden" name="reqMode" value="submitLogin"> 
				<input type="submit"  class="btn red waves-effect waves-light" value="LOGIN">
				<!-- <button id="login-button" type="button">Log In</button> -->
				<!-- <p>Don't have an account? <a>Sign Up</a></p> -->
			</div>
		</div>
		</form>
		</div>
				<!-- <div class="limiter">
					<div class="container-login100">
						<div class="wrap-login100" style="margin-top:-150px;  background: url('images/login-b.jpg') no-repeat ; background-size: 980px 580px;"  >
							<div class="login100-pic js-tilt" >
								<span class="login100-form-title">
									<img src="images/logo1.png" width="250px">
								</span>
							</div>

							<form name="frmLogin" class="login100-form validate-form" action="app/login" method="post" style="margin-top:20px;">
								<div class="wrap-input100 validate-input">
									<input class="input100" type="text" name="reqUser" placeholder="Username" required>
									<span class="focus-input100"></span>
									<span class="symbol-input100">
										<i class="fa fa-envelope" aria-hidden="true"></i>
									</span>
								</div>

								<div class="wrap-input100 validate-input" data-validate = "Password is required">
									<input class="input100" name="reqPasswd" type="password" placeholder="Password" required>
									<span class="focus-input100"></span>
									<span class="symbol-input100">
										<i class="fa fa-lock" aria-hidden="true"></i>
									</span>
								</div>
								
								<div class="container-login100-form-btn">
									<input type="hidden" name="reqMode" value="submitLogin"> 
									<input type="submit"  class="login100-form-btn" value="LOGIN">
								</div>

								<div class="text-center p-t-12" style="display: none;">
									<span class="txt1">
										Forgot
									</span>
									<a class="txt2" href="#">
										Username / Password?
									</a>
								</div>
							</form>
						</div>
					</div>
				</div> -->

		<div id="footer" class="hide-on-small-only">

			<div class="row">

				<div class="col s12 m3">
					<div id="footer-kiri"><span>KONTAK KAMI</span><br>Jl. KH. Wahid Hasyim No. 137, Jombang. <br/>No telp : (0321) 862086, Fax : (0321)877010</div>
				</div>
				<div class="col s12 m6">
					<div id="footer-waktu">
					<span><img src="images/bsre-logo-full.png" width="150px"></span>
						<!-- <div id="footer-waktu-hari"><span id="hari" style="text-transform:uppercase"></span> <span id="tanggal"></span> <span id="jam" style="text-transform:uppercase;"></span> </div>
						-->
					</div>
				</div>
				<div class="col s12 m3">
					<div id="footer-kanan">Badan Kepegawaian dan Pengembangan<br>Sumber Daya Manusia Kabupaten Jombang.<br>All Rights Reserved. Copyright @2018</div>
				</div>

			</div>

		</div>

	</div><!-- #wadah -->

	<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
	<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>
	
	<!--======================================NEW=========================================================-->
		<!-- <script src="assert/vendor/jquery/jquery-3.2.1.min.js"></script>		 -->
	<!--===============================================================================================-->
		<script src="assets/vendor/bootstrap/js/popper.js"></script>
		<script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
	<!--===============================================================================================-->
		<script src="assets/vendor/select2/select2.min.js"></script>
	<!--===============================================================================================-->
		<script src="assets/vendor/tilt/tilt.jquery.min.js"></script>

		<!-- <script src='lib/velocity/js/velocity.min.js'></script>
		<script src='lib/velocity/js/velocity.ui.min.js'></script> -->
		<!-- <script  src="lib/velocity/js/index.js"></script> -->
		<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">

		<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
		</script>
	<!--===============================================================================================-->
		
	
	<!--======================================NEW=========================================================-->	
</body>
</html>