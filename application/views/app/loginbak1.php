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
		delay: 8000,
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
	position:absolute;
	bottom:0;
	background:url(images/hr-footer.png) top center no-repeat;
	width:100%;
	height:70px;
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
	padding-right:50px;
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

#login-area{
	position:relative;
	z-index:5;
	background:#9C0; width:400px; height:356px; margin:80px auto 0;
	
	/*background: #FFF;*/	
	/* fallback */ 
	background-color: #26bfff; background: url(images/linear_bg_2.png); background-repeat: repeat-x; 	
	/* Safari 4-5, Chrome 1-9 */ 
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#26bfff), to(#ab3035)); 	
	/* Safari 5.1, Chrome 10+ */ 
	background: -webkit-linear-gradient(top, #26bfff, #ab3035); 	
	/* Firefox 3.6+ */ 
	background: -moz-linear-gradient(top, #26bfff, #ab3035); 	
	/* IE 10 */ 
	background: -ms-linear-gradient(top, #26bfff, #ab3035); 	
	/* Opera 11.10+ */ 
	background: -o-linear-gradient(top, #26bfff, #ab3035);
	
	-webkit-border-radius: 7px;
	-moz-border-radius: 7px;
	border-radius: 7px;
	
	box-shadow: 0 0 4px 1px #000000;

}

#login-area input[type = text],
#login-area input[type = password],
#inline1 input[type = password]{

	width:338px;	
	height:28px;
	border:1px solid #dbdee3;
	margin-bottom:14px;
	padding-left:10px;
	padding-right:10px;	
}

#login-area input[type = submit],
#inline1 input[type = submit]{
	width:360px;	
	height:30px;
	
	/*background: #FFF;*/	
	/* fallback */ 
	background-color: #3b77a8; background: url(images/linear_bg_2.png); background-repeat: repeat-x; 	
	/* Safari 4-5, Chrome 1-9 */ 
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#3b77a8), to(#1b4b72)); 	
	/* Safari 5.1, Chrome 10+ */ 
	background: -webkit-linear-gradient(top, #3b77a8, #1b4b72); 	
	/* Firefox 3.6+ */ 
	background: -moz-linear-gradient(top, #3b77a8, #1b4b72); 	
	/* IE 10 */ 
	background: -ms-linear-gradient(top, #3b77a8, #1b4b72); 	
	/* Opera 11.10+ */ 
	background: -o-linear-gradient(top, #3b77a8, #1b4b72);
	
	color:#FFF;
	font-size:14px;
	border:none;
	
}

#login-area-bawah{
	background:#f5f7f8; border-top:1px solid #dbdee3; height:45px; line-height:45px; padding-left:20px; color:#b3b0ae;
	font-size:12px;
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
	<div class="header-login-admin ubah-color-warna">
    	<span><img src="images/logo.png"></span> Admin BKDPP Jombang
    </div> 
    
    <div style="clear:both"></div>
    <div id="login-area">
    	<div><img src="images/pen.png" style="margin-top:-12px;"></div>
        <div style="background:#e7eaee; margin-top:-21px; padding:20px 18px;">
        <form name="frmLogin" action="app/login" method="post">
        	Sign in to continue<br style="margin-bottom:14px;">
            <input name="reqUser" type="text" id="reqUser" size="16" placeholder="username"><br>
            <input name="reqPasswd" type="password" id="reqPasswd" size="16" placeholder="password"><br>
            <input type="hidden" name="reqMode" value="submitLogin"> 
            <input type="submit" value="LOGIN">
        </form>    
        </div>
        <div id="login-area-bawah">
        	
        </div>
    </div>
    
    <div id="footer">
    	<div id="footer-kiri">Copyright © 2015 <span>Badan Kepegawaian Daerah Kabupaten Jombang</span>.<br>All Rights Reserved.</div>
    	
        <div id="footer-waktu">			
            
        	<div id="footer-waktu-ikon"><img src="images/jam.png"></div>
            <div id="footer-waktu-jam"><span id="jam" style="text-transform:uppercase;"></span></div>
            <div id="footer-waktu-hari"><span id="hari" style="text-transform:uppercase"></span><br><span id="tanggal"></span></div>
            
        </div>
        <div id="footer-kanan"><span>KONTAK KAMI</span><br>Jl. KH. Wahid Hasyim No. 137,<br/>Jombang.<br/>No telp : (0321) 862086,<br/>Fax : (0321)877010</div>
        
    </div>
</div><!-- #wadah -->

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>