<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once("functions/image.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");

class Login extends CI_Controller {

	function __construct() {
		parent::__construct();
	}
	
	public function loadUrl()
	{
		redirect('app');
	}	

}