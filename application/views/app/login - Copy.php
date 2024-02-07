<html>
<head>
	<title>Login Sistem</title>
	<base href="<?=base_url()?>" />

	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

	<link href="css/gaya.css" rel="stylesheet" type="text/css">  
	<link href="css/begron.css" rel="stylesheet" type="text/css">  
	<link href="lib/font-awesome-4.7.0/css/font-awesome.css" rel="stylesheet" type="text/css">  

	<link rel="stylesheet" type="text/css" href="lib/vegas/jquery.vegas.min.css">
	<script type="text/javascript" src="lib/vegas/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="lib/vegas/jquery.vegas.min.js"></script>

	<script>
		$( function() {
			$.vegas( 'slideshow', {
				delay: 4000,
				backgrounds: [
				{ src: 'images/bg.jpg', fade: 4000 },
				{ src: 'images/bg2.jpg', fade: 4000 }
			/*{ src: 'images/background6.jpg', fade: 4000 },
			{ src: 'images/background7.jpg', fade: 4000 },
			{ src: 'images/background8.jpg', fade: 4000 }*/
			//{ src: 'images/background2.jpg', fade: 4000 },
			//{ src: 'images/background1.jpg', fade: 4000 }
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
	height: 90px;

	background: #56CCF2;  /* fallback for old browsers */
	background: -webkit-linear-gradient(to top, #2F80ED, #56CCF2);  /* Chrome 10-25, Safari 5.1-6 */
	background: linear-gradient(to top, #2F80ED, #56CCF2); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

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
	float:right;
	padding-top:20px;
	padding-right:130px;
	padding-left:10px;
	height:49px;
	margin-top:1px;
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
	font-size:30px;
	line-height:27px;
	margin-right:10px;
}
#footer-waktu-hari{
	float:left;
	font-size:12px;
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
	background: linear-gradient(to top, #f2fcfe, #1c92d2); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

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
</style>

<!-- LIVE DATE -->
<script type="text/javascript" src="lib/liveDate/live_date.js"></script>

</head>

<body scroll="no" onLoad="goforit(); javascript:frmLogin.reqUser.focus()" style="overflow:hidden;">
	<?php /*?><div id="begron"><img src="images/bg-login-admin.jpg" width="100%" height="100%" alt="Smile"></div><?php */?>
	<div id="wadah">
		<div class="header-login-admin">
			<span><img src="images/logo.png"></span> Admin BKDPP Jombang
		</div> 

		<div style="clear:both"></div>

		<div id="login-area-atas">
		</div>

		<div id="login-area">
			<div style="margin-top:10px; padding:20px 18px;">
				<img src="images/pen2.png" style="width: 150px; margin-top: -100px; float: right;">
				<form name="frmLogin" action="app/login" method="post">
					<input name="reqUser" type="text" id="reqUser" size="16" placeholder="username"><br>
					<input name="reqPasswd" type="password" id="reqPasswd" size="16" placeholder="password"><br>
					<input type="hidden" name="reqMode" value="submitLogin"> 
					<input type="submit" value="LOGIN">
				</form>    
			</div>
		</div>

		<div id="login-area-bawah">
		</div>

		<div id="footer">
			<div id="footer-kiri"><span>KONTAK KAMI</span><br>Jl. KH. Wahid Hasyim No. 137, Jombang. No telp : (0321) 862086, <br>Fax : (0321)877010</div>
			<div id="footer-kanan">Copyright © 2015 <span>Badan Kepegawaian Daerah Kabupaten Jombang</span>.<br>All Rights Reserved.</div>

			<div id="footer-waktu">			
				<div id="footer-waktu-ikon"><img src="images/jam.png"></div>
				<div id="footer-waktu-jam"><span id="jam" style="text-transform:uppercase;"></span></div>
				<div id="footer-waktu-hari"><span id="hari" style="text-transform:uppercase"></span><br><span id="tanggal"></span></div>
			</div>

		</div>
	</div><!-- #wadah -->

	<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
	<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>