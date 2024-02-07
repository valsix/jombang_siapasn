<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"> 
        <meta charset="utf-8">
        <title>PJB Presensi</title>
    	<base href="<?=base_url()?>">
        <meta name="generator" content="Bootply" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="description" content="Bootstrap In this tutorial, I want to show you how to create a beautiful website template that gives a wonderful look and comfort of your Web applicat" />
		<link href="lib/bootstrap/css/bootstrap.css" rel="stylesheet">
        
        <!--[if lt IE 9]>
          <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link rel="apple-touch-icon" href="/bootstrap/img/apple-touch-icon.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/bootstrap/img/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/bootstrap/img/apple-touch-icon-114x114.png">

        <!-- CSS code from Bootply.com editor -->
        
        <style type="text/css">
            /*!
 * Start Bootstrap - Simple Sidebar HTML Template (http://startbootstrap.com)
 * Code licensed under the Apache License v2.0.
 * For details, see http://www.apache.org/licenses/LICENSE-2.0.
 */

/* Toggle Styles */

#wrapper {
    padding-left: 0;
    -webkit-transition: all 0.5s ease;
    -moz-transition: all 0.5s ease;
    -o-transition: all 0.5s ease;
    transition: all 0.5s ease;
}

#wrapper.toggled {
    padding-left: 250px;
}

#sidebar-wrapper {
    position: fixed;
    left: 250px;
    z-index: 1000;
    overflow-y: auto;
    margin-left: -250px;
    width: 0;
    height: 100%;
    *background: #000;
	*background:rgba(255,255,255,0.5);
	background:url(images/border-sidebar.png) top right no-repeat;
	background-position:249px 60px;
    -webkit-transition: all 0.5s ease;
    -moz-transition: all 0.5s ease;
    -o-transition: all 0.5s ease;
    transition: all 0.5s ease;
}

#wrapper.toggled #sidebar-wrapper {
    width: 250px;
}

#page-content-wrapper {
    padding: 15px 15px 15px 15px;
    width: 100%;
	
	margin-top:60px;
}

#wrapper.toggled #page-content-wrapper {
    position: absolute;
    margin-right: -250px;
}

/* Sidebar Styles */

.sidebar-nav {
    position: absolute;
    top: 0;
    margin: 0;
    padding: 0;
    width: 250px;
    list-style: none;
}

.sidebar-nav li {
    text-indent: 20px;
    line-height: 40px;
}

.sidebar-nav li a {
    display: block;
    color: #999999;
    text-decoration: none;
}

.sidebar-nav li a:hover {
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    text-decoration: none;
}

.sidebar-nav li a:active,
.sidebar-nav li a:focus {
    text-decoration: none;
}

.sidebar-nav > .sidebar-brand {
    height: 65px;
    font-size: 18px;
    line-height: 60px;
}

.sidebar-nav > .sidebar-brand a {
    color: #999999;
}

.sidebar-nav > .sidebar-brand a:hover {
    background: none;
    color: #fff;
}

@media (min-width: 768px) {
    #wrapper {
        padding-left: 250px;
    }

    #wrapper.toggled {
        padding-left: 0;
    }

    #sidebar-wrapper {
        width: 250px;
    }

    #wrapper.toggled #sidebar-wrapper {
        width: 0;
    }

    #page-content-wrapper {
        padding: 20px;
    }

    #wrapper.toggled #page-content-wrapper {
        position: relative;
        margin-right: 0;
    }
}
        </style>
        
<link rel="stylesheet" href="css/gaya.css" type="text/css">
<link rel="stylesheet" href="css/gaya-bootstrap.css" type="text/css">
<link rel="stylesheet" href="css/gaya-navbar-hover.css" type="text/css">

<style>
html{
	height:	100%;
}
</style>

<!-- FONT AWESOME -->
<link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

<!-- DOUGHNUT CHART JS -->
<script src="lib/Chart.js-master/Chart.js">
</script>

<!-- SCROLLING TABLE MASTER -->
<link rel="stylesheet" href="lib/ScrollingTable-master/style.css" />

</head>

<body class="body-pjb">
        
<!--<div id="wrapper" class="toggled" style="height:100%; border:1px solid red;">-->
<div id="wrapper" class="wrapper-corporate">
    
</div>
<!-- /#wrapper -->
        
<script type='text/javascript' src="lib/bootstrap/js/jquery.js"></script>

<script type='text/javascript' src="lib/bootstrap/js/bootstrap.js"></script>

<!-- JavaScript jQuery code from Bootply.com editor  -->

<script type='text/javascript'>

$(document).ready(function() {
	$("#wrapper").toggleClass("toggled");
	$("#menu-toggle").click(function(e) {
		//alert("hhh");
		e.preventDefault();
		$("#wrapper").toggleClass("toggled");
	});
});

</script>


</body>
</html>