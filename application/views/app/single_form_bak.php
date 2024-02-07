<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="<?=base_url();?>" />

<!--<link rel="stylesheet" href="css/gaya.css" type="text/css">
<link rel="stylesheet" href="css/gaya-bootstrap.css" type="text/css">
-->
<!-- BOOTSTRAP -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<!--<script src="js/jquery-1.10.2.min.js"></script>-->
<script src="lib/bootstrap/js/jquery.min.js"></script>
<script src="lib/bootstrap/js/bootstrap.js"></script>
<link href="lib/bootstrap/css/bootstrap.css" rel="stylesheet">

<!-- FONT AWESOME -->
<link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

</head>

<body>
    <div class="container-fluid" style="border:1px solid red;">
   
        <div class="row" style="border:1px solid red;">
        <div class="col-md-12" style="border:1px solid red;">
        
        	<div id="judul-popup">Contoh single form</div>
            
            <form style="border:1px solid red;">
 
            <div class="form-group" style="border:1px solid green;">
                    <label for="inputEmail" class="col-md-3" style="border:1px solid cyan;">Email</label>
                    <div class="col-md-9">
                    	<input type="email" class="form-control" id="inputEmail" placeholder="Email">
                    </div>
                </div>
             
             
            <div class="form-group">
                    <label for="inputPassword">Password</label>
                    <input type="password" class="form-control" id="inputPassword" placeholder="Password">
                </div>
             
             
            <div class="checkbox">
                    <label><input type="checkbox"> Remember me</label>
                </div>
             
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
            
            <form class="form-horizontal" role="form">
                <div class="form-group">
                  <label for="inputType" class="col-md-2 control-label">Type</label>
                  <div class="col-md-3">
                      <input type="text" class="form-control" id="inputType" placeholder="Type">
                  </div>
                </div>
                <div class="form-group">
                    <span class="col-md-2 control-label">Metadata</span>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="inputKey" class="col-md-1 control-label">Key</label>
                            <div class="col-md-2">
                                <input type="text" class="form-control" id="inputKey" placeholder="Key">
                            </div>
                            <label for="inputValue" class="col-md-1 control-label">Value</label>
                            <div class="col-md-2">
                                <input type="text" class="form-control" id="inputValue" placeholder="Value">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
           
                <form>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                  </div>
                  <div class="form-group">
                    <label for="exampleSelect1">Example select</label>
                    <select class="form-control" id="exampleSelect1">
                      <option>1</option>
                      <option>2</option>
                      <option>3</option>
                      <option>4</option>
                      <option>5</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="exampleSelect2">Example multiple select</label>
                    <select multiple class="form-control" id="exampleSelect2">
                      <option>1</option>
                      <option>2</option>
                      <option>3</option>
                      <option>4</option>
                      <option>5</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="exampleTextarea">Example textarea</label>
                    <textarea class="form-control" id="exampleTextarea" rows="3"></textarea>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputFile">File input</label>
                    <input type="file" class="form-control-file" id="exampleInputFile" aria-describedby="fileHelp">
                    <small id="fileHelp" class="form-text text-muted">This is some placeholder block-level help text for the above input. It's a bit lighter and easily wraps to a new line.</small>
                  </div>
                  <fieldset class="form-group">
                    <legend>Radio buttons</legend>
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios1" value="option1" checked>
                        Option one is this and that&mdash;be sure to include why it's great
                      </label>
                    </div>
                    <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios2" value="option2">
                        Option two can be something else and selecting it will deselect option one
                      </label>
                    </div>
                    <div class="form-check disabled">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios3" value="option3" disabled>
                        Option three is disabled
                      </label>
                    </div>
                  </fieldset>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input">
                      Check me out
                    </label>
                  </div>
                  <button type="submit" class="btn btn-primary">Submit</button>
                </form>
                
            </div>
        </div>        
    </div>
    
</body>
</html>
