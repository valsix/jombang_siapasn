<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once("functions/string.func.php");
include_once("functions/encrypt.func.php");

class globalfilepegawai
{
	function enkripdekripkunci()
	{
		return "KuNc1";
	}

	function enkripdata($arrparam)
	{
		$reqdata= urldecode($arrparam["reqdata"]);
		$reqkunci= urldecode($arrparam["reqkunci"]);

		return mencrypt($reqdata, $reqkunci);
	}

	function dekripdata($arrparam)
	{
		$reqdata= urldecode($arrparam["reqdata"]);
		$reqkunci= urldecode($arrparam["reqkunci"]);

		return mdecrypt($reqdata, $reqkunci);
	}

}