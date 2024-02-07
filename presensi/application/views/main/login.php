<?
$this->load->library("crfs_protect"); $csrf = new crfs_protect('_crfs_login');
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"> 
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<title>Aplikasi Presensi Jombang</title>
<base href="<?=base_url()?>">
        
<link rel="stylesheet" href="css/gaya.css" type="text/css">
<!--<link rel="stylesheet" href="css/gaya-bootstrap.css" type="text/css">-->
    
<!-- BOOTSTRAP -->
<link href="lib/bootstrap/css/bootstrap.css" rel="stylesheet">
<script type='text/javascript' src="lib/bootstrap/js/jquery.js"></script>
<script type='text/javascript' src="lib/bootstrap/js/bootstrap.js"></script>

<!-- FONT AWESOME -->
<link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

<!-- BG FULLSCREEN -->
<link rel="stylesheet" type="text/css" href="lib/vegas/jquery.vegas.min.css">
<script type="text/javascript" src="lib/vegas/jquery.vegas.min.js"></script>
<script>
$( function() {
    $.vegas( 'slideshow', {
        delay: 18000,
        backgrounds: [
            //{ src: 'images/bg-login.png', fade: 4000 },
            //{ src: 'images/bg-login2.png', fade: 4000 }
            { src: 'images/bg-login-gear3.jpg', fade: 4000 },
            { src: 'images/bg-login-gear4.jpg', fade: 4000 }
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
    
    $('#a').keyup(function(){
        // this.value = this.value.toUpperCase();
    });
} );
</script>

</head>

<body>

<div class="container-fluid login-pjb-presensi">
    
    <div class="row">
        <div class="col-md-12">
            
            <div class="area-login">
                
                <div class="area-logo">
                    <div class="inner">
                        <img src="images/logo-login.png">
                    </div>
                </div>
                
                <div class="area-form">
                    <form role="form" action="login/action" method="post" class="login-form" >
                        <div class="form-group">
                            <label class="sr-only" for="form-username">Username</label>
                            <input type="text" placeholder="Username..." class="form-username form-control" name="reqUser" id="a" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" />
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="form-password">Password</label>
                            <input type="password" placeholder="Password..." class="form-password form-control" name="reqPasswd" id="s" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" />
                        </div>
                        <?=$csrf->echoInputField();?>
                        <div class="form-group">
                            <input type="submit" value="LOGIN" class="btn btn-info" style="width:100% !important;" />  
                        </div>
                        
                        <input type="hidden" name="reqMode" value="submitLogin"/>
                        <span><?=$pesan?></span>
                    </form>
                    
                    <div class="copyright">&copy; 2019</div>
                </div>
                
            </div>
            
        </div>
        
    </div>
    
</div>


<!--<footer data-role="footer" id="footer" style="background:cyan;">footer</footer>-->

</body>
</html>