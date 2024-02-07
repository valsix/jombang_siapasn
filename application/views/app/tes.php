<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="description" content="Simpeg Jombang">
    <meta name="keywords" content="Simpeg Jombang">
    <title>Simpeg Jombang</title>
    <base href="<?=base_url()?>" />
  
  	<!-- CORE CSS-->    
    <link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
    <!-- CSS style Horizontal Nav-->    
    <link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
    <!-- Custome CSS-->    
    <link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

<body>    
<!--Basic Form-->
<div id="basic-form" class="section">
  <div class="row">
    <div class="col s12 m12 l6">
      <div class="card-panel">
        <h4 class="header2">Basic Form</h4>
        <div class="row">
          <form class="col s12">
            <div class="row">
              <div class="input-field col s12">
                <input id="name" type="text">
                <label for="first_name">Name</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12">
                <input id="email" type="email">
                <label for="email">Email</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12">
                <input id="password" type="password">
                <label for="password">Password</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12">
                <textarea id="message" class="materialize-textarea"></textarea>
                <label for="message">Message</label>
              </div>
              <div class="row">
                <div class="input-field col s12">
                  <button class="btn cyan waves-effect waves-light right" type="submit" name="action">Submit
                    <i class="mdi-content-send right"></i>
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Form with placeholder -->
    <div class="col s12 m12 l6">
      <div class="card-panel">
        <h4 class="header2">Form with placeholder</h4>
        <div class="row">
          <form class="col s12">
            <div class="row">
              <div class="input-field col s12">
                <input placeholder="John Doe" id="name2" type="text">
                <label for="first_name">Name</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12">
                <input placeholder="john@domainname.com" id="email2" type="email">
                <label for="email">Email</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12">
                <input placeholder="YourPassword" id="password2" type="password">
                <label for="password">Password</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12">
                <textarea placeholder="Oh WoW! Let me check this one too." id="message2" class="materialize-textarea"></textarea>
                <label for="message">Message</label>
              </div>
              <div class="row">
                <div class="input-field col s12">
                  <button class="btn cyan waves-effect waves-light right" type="submit" name="action">Submit
                    <i class="mdi-content-send right"></i>
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- jQuery Library -->
<script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script>

<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/materialize.min.js"></script>
</body>