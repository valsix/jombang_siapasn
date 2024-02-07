<?
$CI =& get_instance();
$CI->checkUserLogin();

$tempUserLoginId= $this->USER_LOGIN_ID;
$reqInfoSipeta= $this->INFO_SIPETA;
// echo $reqInfoSipeta;exit;

// if($tempUserLoginId == "376" || $tempUserLoginId == "1" || $tempUserLoginId == "411" || $tempUserLoginId == "359" || $tempUserLoginId == "524" || $tempUserLoginId == "516"){}
if(!empty($reqInfoSipeta)){}
else
{
  redirect('app/index');
  exit;
}

$reqTahun = $this->input->get("reqTahun");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SIAP ASN</title>
<base href="<?=base_url()?>" />

<link rel="shortcut icon" type="image/ico">
<!--<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.datatables.net/rss.xml">-->
<link href="css/gaya.css" rel="stylesheet" type="text/css">
<link href="css/kuadran.css" rel="stylesheet" type="text/css">
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

<style>
#wadah{
  display: flex;
  justify-content: center; /* align horizontal */
  align-items: center; /* align vertical */
}
.area-main-menu{
  
}
</style>

</head>
<body>
<div id="wadah" style="height:100%; min-height:100%;">
  <div class="info-area area-main-menu" style="position:relative; height:calc(100% - 0px);">
    <div class="item"><a href="app/index"><span><img src="images/siap-asn.png"></span> SIAP-ASN</a></div>
    <!-- <div class="item"><a href="sidak/index"><span><img src="images/portal.png"></span> Sidak!</a></div> -->
    <div class="item"><a href="sidak_2023/index"><span><img src="images/sipeta-logo.png"></span> SIPETA</a></div>
  </div>
</div>
</body>
</html>