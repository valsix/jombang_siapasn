<?

$CI =& get_instance();
$CI->checkUserLogin();

$reqTahun = $this->input->get("reqTahun");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dp3 Pelindo</title>
<base href="<?=base_url()?>" />

<link rel="shortcut icon" type="image/ico">
<!--<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.datatables.net/rss.xml">-->
<link href="css/gaya.css" rel="stylesheet" type="text/css">
<link href="css/admin.css" rel="stylesheet" type="text/css">
<link href="css/begron.css" rel="stylesheet" type="text/css">

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

<!-- Bootstrap Core CSS -->
<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Core JavaScript -->
<script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

</head>
<body>
<div id="wadah" style="height:100%; min-height:100%;">
    <div class="info-area" style="border:0px solid red; position:relative; height:calc(100% - 0px);">
    	<!--<img src="images/img-home-admin.png" style="position:absolute; *left:calc(50% - 275px); right:100px; bottom:80px;" >-->
    </div>
</div>
</body>
</html>