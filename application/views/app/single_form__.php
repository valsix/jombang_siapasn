<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="<?=base_url();?>" />

<link rel="stylesheet" href="css/gaya.css" type="text/css">
<link rel="stylesheet" href="css/admin.css" type="text/css">
<!--
<link rel="stylesheet" href="css/gaya-bootstrap.css" type="text/css">
-->
<!-- BOOTSTRAP -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<!--<script src="js/jquery-1.10.2.min.js"></script>-->
<?php /*?><script src="lib/bootstrap/js/jquery.min.js"></script>
<script src="lib/bootstrap/js/bootstrap.js"></script>
<link href="lib/bootstrap/css/bootstrap.css" rel="stylesheet"><?php */?>

<!-- FONT AWESOME -->
<link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

<style>
html, body{
	height:100%;
}
@media screen and (max-width:767px) {
	html, body{
		height: auto;
	}
}
</style>

<!-- Bootstrap core CSS -->
<link rel="stylesheet" href="lib/mdform/css/bootstrap.min.css">
<!-- Material Design Bootstrap -->
<link rel="stylesheet" href="lib/mdform/css/mdb.css">


</head>

<body>
    <div class="container-fluid full-height">
   
        <div class="row full-height">
	        <div class="col-md-12 area-form full-height" style="border:1px solid cyan;">
        
	        	<div id="judul-popup">Contoh single form</div>
            	
                <div class="area-form-inner" style="border:1px solid red;">
                	
                    <!--Main layout-->
                    <main style="border:1px solid cyan;">
                        <div class="container-fluid" style="border:1px solid green;">
                
                            <!--Section: Inputs-->
                            <section class="section card mb-5">
                
                                <div class="card-body">
                
                                    <!--Section heading-->
                                    <h1 class="section-heading h1">Inputs</h1>
                
                                    <h5 class="pb-5">Input fields</h5>
                
                                    <!--Grid row-->
                                    <div class="row">
                
                                        <!--Grid column-->
                                        <div class="col-md-4 mb-r">
                
                                            <div class="md-form">
                                                <input type="text" id="form1" class="form-control">
                                                <label for="form1" class="">Basic example</label>
                                            </div>
                
                                        </div>
                                        <!--Grid column-->
                
                                        <!--Grid column-->
                                        <div class="col-md-4 mb-r" style="margin-top: 11px;">
                
                                            <div class="md-form form-sm">
                                                <input type="text" id="form1" class="form-control">
                                                <label for="form1" class="">Small input</label>
                                            </div>
                
                                        </div>
                                        <!--Grid column-->
                
                                        <!--Grid column-->
                                        <div class="col-md-4 mb-r">
                
                                            <div class="md-form">
                                                <input placeholder="Placeholder" type="text" id="form5" class="form-control">
                                                <label for="form5" class="active">Example label</label>
                                            </div>
                
                                        </div>
                                        <!--Grid column-->
                
                                    </div>
                                    <!--Grid row-->
                
                                    <!--Grid row-->
                                    <div class="row">
                
                                        <!--Grid column-->
                                        <div class="col-md-6 mb-r">
                
                                            <div class="md-form">
                                                <input value="John Doe" type="text" id="form6" class="form-control">
                                                <label class="active" for="form6">Prefilling text inputs</label>
                                            </div>
                
                                        </div>
                                        <!--Grid column-->
                
                                        <!--Grid column-->
                                        <div class="col-md-6 mb-r">
                
                                            <div class="md-form">
                                                <input type="text" id="form11" class="form-control" disabled="">
                                                <label for="form11" class="disabled">Disabled input</label>
                                            </div>
                
                                        </div>
                                        <!--Grid column-->
                
                                    </div>
                                    <!--Grid row-->
                
                                    <!--Grid row-->
                                    <div class="row text-left">
                
                                        <!--Grid column-->
                                        <div class="col-md-6 mb-r">
                
                                            <!--Email validation-->
                                            <div class="md-form">
                                                <i class="fa fa-envelope prefix"></i>
                                                <input type="email" id="form9" class="form-control validate">
                                                <label for="form9" data-error="wrong" data-success="right">Type your email</label>
                                            </div>
                
                                        </div>
                                        <!--Grid column-->
                
                                        <!--Grid column-->
                                        <div class="col-md-6 mb-r">
                
                                            <!--Password validation-->
                                            <div class="md-form">
                                                <i class="fa fa-lock prefix"></i>
                                                <input type="password" id="form10" class="form-control validate">
                                                <label for="form10" data-error="wrong" data-success="right">Type your password</label>
                                            </div>
                
                                        </div>
                                        <!--Grid column-->
                
                                    </div>
                                    <!--Grid row-->
                
                                    <h5 class="pb-5">Error or Success Messages</h5>
                
                                    <!--Grid row-->
                                    <div class="row text-left">
                
                                        <!--Grid column-->
                                        <div class="col-md-6 mb-r">
                
                                            <!--Email validation-->
                                            <div class="md-form">
                                                <i class="fa fa-envelope prefix"></i>
                                                <input type="email" id="form9" class="form-control validate">
                                                <label for="form9" data-error="wrong" data-success="right">Type your email</label>
                                            </div>
                
                                        </div>
                                        <!--Grid column-->
                
                                        <!--Grid column-->
                                        <div class="col-md-6 mb-r">
                
                                            <!--Password validation-->
                                            <div class="md-form">
                                                <i class="fa fa-lock prefix"></i>
                                                <input type="password" id="form10" class="form-control validate">
                                                <label for="form10" data-error="wrong" data-success="right">Type your password</label>
                                            </div>
                
                                        </div>
                                        <!--Grid column-->
                
                                    </div>
                                    <!--Grid row-->
                
                                    <h5 class="pb-5">Textarea</h5>
                
                                    <!--Grid row-->
                                    <div class="row text-left">
                
                                        <!--Grid column-->
                                        <div class="col-md-6 mb-r">
                
                                            <!--Basic textarea-->
                                            <div class="md-form">
                                                <textarea type="text" id="form7" class="md-textarea"></textarea>
                                                <label for="form7">Basic textarea</label>
                                            </div>
                
                                        </div>
                                        <!--Grid column-->
                
                                        <!--Grid column-->
                                        <div class="col-md-6 mb-r">
                
                                            <!--Textarea with icon prefix-->
                                            <div class="md-form" style="margin-top: 12px;">
                                                <i class="fa fa-pencil prefix"></i>
                                                <textarea type="text" id="form8" class="md-textarea"></textarea>
                                                <label for="form8">Icon Prefix</label>
                                            </div>
                
                                        </div>
                                        <!--Grid column-->
                
                                    </div>
                                    <!--Grid row-->
                
                                    <h5 class="pb-5">Checkboxes and Radio</h5>
                
                                    <!--Grid row-->
                                    <div class="row">
                
                                        <!--Grid column-->
                                        <div class="col-lg-4 col-md-12 mb-r">
                
                                            <fieldset class="form-group">
                                                <input type="checkbox" id="checkbox1" checked="checked">
                                                <label for="checkbox1">Classic checkbox</label>
                                            </fieldset>
                
                                            <fieldset class="form-group">
                                                <input type="checkbox" class="filled-in" id="checkbox2" checked="checked">
                                                <label for="checkbox2">Filled-in checkbox</label>
                                            </fieldset>
                
                                        </div>
                                        <!--Grid column-->
                
                                        <!--Grid column-->
                                        <div class="col-lg-4 col-md-6 mb-r">
                
                                            <fieldset class="form-group">
                                                <input name="group1" type="radio" id="radio1" checked="checked">
                                                <label for="radio1">Option 1</label>
                                            </fieldset>
                
                                            <fieldset class="form-group">
                                                <input name="group1" type="radio" id="radio2">
                                                <label for="radio2">Option 2</label>
                                            </fieldset>
                
                                            <fieldset class="form-group">
                                                <input name="group1" type="radio" id="radio3">
                                                <label for="radio3">Option 3</label>
                                            </fieldset>
                
                                        </div>
                                        <!--Grid column-->
                
                                        <!--Grid column-->
                                        <div class="col-lg-4 col-md-6 mb-r">
                
                                            <!-- Switch -->
                                            <div class="switch">
                                                <label>
                                            Off
                                            <input type="checkbox" checked="checked">
                                            <span class="lever"></span> On
                                        </label>
                                            </div>
                                            <!-- Disabled Switch -->
                                            <div class="switch">
                                                <label>
                                            Off
                                            <input disabled="" type="checkbox">
                                            <span class="lever"></span> On
                                        </label>
                                            </div>
                
                
                                        </div>
                                        <!--Grid column-->
                
                                    </div>
                                    <!--Grid row-->
                
                                    <h5 class="pb-5">File input</h5>
                
                                    <!--Grid row-->
                                    <div class="row">
                
                                        <!--Grid column-->
                                        <div class="col-md-6 mb-r">
                
                                            <form>
                                                <div class="file-field">
                                                    <div class="btn btn-primary btn-sm waves-effect waves-light">
                                                        <span>Choose file</span>
                                                        <input type="file">
                                                    </div>
                                                    <div class="file-path-wrapper">
                                                        <input class="file-path validate" type="text" placeholder="Upload your file">
                                                    </div>
                                                </div>
                                            </form>
                
                                        </div>
                                        <!--Grid column-->
                
                                        <!--Grid column-->
                                        <div class="col-md-6 mb-r">
                
                                            <form action="#">
                                                <div class="file-field">
                                                    <div class="btn btn-primary btn-sm waves-effect waves-light">
                                                        <span>Choose files</span>
                                                        <input type="file" multiple>
                                                    </div>
                                                    <div class="file-path-wrapper">
                                                        <input class="file-path validate" type="text" placeholder="Upload one or more files">
                                                    </div>
                                                </div>
                                            </form>
                
                                        </div>
                                        <!--Grid column-->
                
                                    </div>
                                    <!--Grid row-->
                
                                    <h5 class="pb-5">Range</h5>
                
                                    <!--Grid row-->
                                    <div class="row">
                
                                        <!--Grid column-->
                                        <div class="col-md-12 mb-r">
                
                                            <form class="range-field">
                                                <input type="range" min="0" max="100"><span class="thumb"><span class="value"></span></span>
                                            </form>
                
                                        </div>
                                        <!--Grid column-->
                
                                    </div>
                                    <!--Grid row-->
                
                                    <h5 class="pb-5">Character counters</h5>
                
                                    <!--Grid row-->
                                    <div class="row">
                
                                        <!--Grid column-->
                                        <div class="col-md-6">
                
                                            <div class="md-form" style="margin-top: 63px;">
                                                <input id="input-char-counter" type="text" length="10">
                                                <label for="input-char-counter">Input text</label>
                                                <span class="character-counter" style="float: right; font-size: 12px; height: 1px;"></span></div>
                
                                        </div>
                                        <!--Grid column-->
                
                                        <!--Grid column-->
                                        <div class="col-md-6">
                
                                            <div class="md-form">
                                                <textarea id="textarea-char-counter" class="md-textarea" length="120"></textarea>
                                                <label for="textarea-char-counter">Type your text</label>
                                                <span class="character-counter" style="float: right; font-size: 12px; height: 1px;"></span></div>
                
                                        </div>
                                        <!--Grid column-->
                
                                    </div>
                                    <!--Grid row-->
                
                                    <h5 class="pb-5">Select</h5>
                
                                    <!--Grid row-->
                                    <div class="row mb-5">
                
                                        <!--Grid column-->
                                        <div class="col-lg-4 col-md-12">
                
                                            <!--Name-->
                                            <select class="mdb-select" multiple>
                                                <option value="" disabled selected>Basic select</option>
                                                <option value="1">USA</option>
                                                <option value="2">Germany</option>
                                                <option value="3">France</option>
                                                <option value="3">Poland</option>
                                                <option value="3">Japan</option>
                                            </select>
                
                                        </div>
                                        <!--Grid column-->
                
                                        <!--Grid column-->
                                        <div class="col-lg-4 col-md-6">
                
                                            <!--Name-->
                                            <!--Name-->
                                            <select class="mdb-select colorful-select dropdown-primary" multiple>
                                                <option value="" disabled selected>Material select</option>
                                                <option value="1">USA</option>
                                                <option value="2">Germany</option>
                                                <option value="3">France</option>
                                                <option value="3">Poland</option>
                                                <option value="3">Japan</option>
                                            </select>
                                            <label>Label example</label>
                
                                        </div>
                                        <!--Grid column-->
                
                                        <!--Grid column-->
                                        <div class="col-lg-4 col-md-6">
                
                                            <!--Name-->
                                            <select class="mdb-select" multiple>
                                                <optgroup label="team 1" >
                                                    <option value="1">Option 1</option>
                                                    <option value="2">Option 2</option>
                                                </optgroup>
                                                <optgroup label="team 2">
                                                    <option value="3">Option 3</option>
                                                    <option value="4">Option 4</option>
                                                </optgroup>
                                            </select>
                
                                        </div>
                                        <!--Grid column-->
                
                                    </div>
                                    <!--Grid row-->
                
                                    <!--Grid row-->
                                    <div class="row">
                
                                        <!--Grid column-->
                                        <div class="col-lg-4 col-md-12">
                
                                            <!--Name-->
                                            <select class="mdb-select" multiple searchable="Search here..">
                                                <option value="" disabled selected>Search select</option>
                                                <option value="1">USA</option>
                                                <option value="2">Germany</option>
                                                <option value="3">France</option>
                                                <option value="3">Poland</option>
                                                <option value="3">Japan</option>
                                            </select>
                
                                        </div>
                                        <!--Grid column-->
                
                                        <!--Grid column-->
                                        <div class="col-lg-4 col-md-6">
                
                                            <!--Name-->
                                            <select class="mdb-select colorful-select dropdown-primary" multiple searchable="Search here..">
                                                <option value="" disabled selected>Choose your country</option>
                                                <option value="1">USA</option>
                                                <option value="2">Germany</option>
                                                <option value="3">France</option>
                                                <option value="3">Poland</option>
                                                <option value="3">Japan</option>
                                            </select>
                                            <label>Search material selects</label>
                
                                        </div>
                                        <!--Grid column-->
                
                                        <!--Grid column-->
                                        <div class="col-lg-4 col-md-6">
                
                                            <!--Name-->
                                            <select class="mdb-select" multiple searchable="Search here..">
                                                <option value="" data-icon="https://mdbootstrap.com/img/Photos/Avatars/avatar-1.jpg" class="rounded-circle">example 1</option>
                                                <option value="" data-icon="https://mdbootstrap.com/img/Photos/Avatars/avatar-2.jpg" class="rounded-circle">example 2</option>
                                                <option value="" data-icon="https://mdbootstrap.com/img/Photos/Avatars/avatar-3.jpg" class="rounded-circle">example 1</option>
                                            </select>
                
                                        </div>
                                        <!--Grid column-->
                
                                    </div>
                                    <!--Grid row-->
                
                                </div>
                
                            </section>
                            <!--Section: Inputs-->
                
                            <!--Section: Docs link-->
                            <section class="pb-5">
                
                               <!--Panel-->
                                <div class="card text-center">
                                    <h3 class="card-header primary-color white-text">Full documentation</h3>
                                    <div class="card-body">
                                        <p class="card-text">Read the full documentation for these components.</p>
                                        <a href="https://mdbootstrap.com/components/inputs/" target="_blank" class="btn btn-primary">Learn more</a>
                                    </div>
                                </div>
                                <!--/.Panel-->
                
                
                            </section>
                            <!--Section: Docs link-->
                
                        </div>
                    </main>
                    <!--Main layout-->
                    
                </div>
                
                
            </div>
        </div>        
    </div>
    
    <!-- SCRIPTS -->
    <!-- JQuery -->
    <script src="lib/mdform/js/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="lib/mdform/js/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="lib/mdform/js/bootstrap.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="lib/mdform/js/mdb.min.js"></script>
    <!--Custom scripts-->
    <script>
        // SideNav Initialization
        $(".button-collapse").sideNav();

        var container = document.getElementById('slide-out');
        Ps.initialize(container, {
            wheelSpeed: 2,
            wheelPropagation: true,
            minScrollbarLength: 20
        });

        // Material Select Initialization
        $(document).ready(function () {
            $('.mdb-select').material_select();
        });
    </script>
    
</body>
</html>
