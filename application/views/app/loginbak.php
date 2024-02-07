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

</head>

<body  scroll="no" onLoad="javascript:frmLogin.reqUser.focus()" style="overflow:hidden;">
<div id="begron"><img src="images/bg-login-admin.jpg" width="100%" height="100%" alt="Smile"></div>
<div id="wadah">

	<div class="header-login-admin">
    	<span><img src="images/logo.png"></span> Admin BKDPP Jombang
    </div>

    <div class="area-login-admin">
    	<div class="judul">Login Area</div>
        <form name="frmLogin" action="app/login" method="post">
            <table>
                  <tr>
                    <td><i class="fa fa-user"></i></td>
                    <td><input name="reqUser" type="text" id="reqUser" size="16" placeholder="username"></td>
                  </tr>
                  <tr>
                    <td><i class="fa fa-lock"></i></td>
                    <td><input name="reqPasswd" type="password" id="reqPasswd" size="16" placeholder="password"></td>
                  </tr>
                  <tr>
                    <td align="center">&nbsp;</td>
                    <td align="center">
                        <input name="slogin_POST_send" type="submit" value="Login" class="button" alt="DO LOGIN!" width="68" height="20">
                        <input type="reset" value="Reset">
                    </td>
                  </tr>
            </table>
			<input type="hidden" name="reqMode" value="submitLogin"> 
		</form>
    </div>
</div><!-- #wadah -->
<div class="footer-login-admin">Copyright © 2016 BKD Kabupaten Jombang. All Rights Reserved. </div>
</body>
</html>