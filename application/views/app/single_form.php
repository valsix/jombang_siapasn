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
                                <div class="col-md-4 mb-r">

                                    <div class="md-form">
                                        <select name="reqPendidikan" id="reqPendidikan"  style="width:60px">
                                            <option value="1" >SD</option>
                                            <option value="2" >SLTP</option>
                                            <option value="3" >SLTA</option>
                                            <option value="4" >D.I</option>
                                            <option value="5" >D.II</option>
                                            <option value="6" >D.III</option>
                                            <option value="7" >D.IV</option>
                                            <option value="8" >S.1</option>
                                            <option value="9" >S.2</option>
                                            <option value="10" >S.3</option>
                                        </select>
                                        <label for="form5" class="active">Pendidikan</label>
                                    </div>

                                    <div class="input-field col s12">
                                        <select>
                                          <option value="" disabled selected>Choose your option</option>
                                          <option value="1">Option 1</option>
                                          <option value="2">Option 2</option>
                                          <option value="3">Option 3</option>
                                      </select>
                                      <label>Materialize Select</label>
                                  </div>
                                  <script type="text/javascript">
                                      $(document).ready(function() {
                                         $('select').material_select();
                                     });
                                  </script>

                              </div>
                              <!--Grid column-->

                          </div>

                      </div>

                  </section>
                  <!--Section: Inputs-->

              </div>
          </main>
          <!--Main layout-->

      </div>

  </div>
</div>        
</div>

<script type="text/javascript" src="lib/mdform/materialize.min.js"></script>

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
        <?php /*?>$(".button-collapse").sideNav();

        var container = document.getElementById('slide-out');
        Ps.initialize(container, {
            wheelSpeed: 2,
            wheelPropagation: true,
            minScrollbarLength: 20
        });<?php */?>

        // Material Select Initialization
        $(document).ready(function () {
            $('.mdb-select').material_select();
        });
    </script>
    
</body>
</html>
