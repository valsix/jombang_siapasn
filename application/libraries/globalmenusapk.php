<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once("functions/string.func.php");
include_once("functions/encrypt.func.php");

class globalmenusapk
{
	function setmenusapk($menuid="")
	{
		$CI = &get_instance();
		$CI->load->model("Menu");
		$USER_GROUP_ID= $CI->kauth->getInstance()->getIdentity()->USER_GROUP_ID;
		// echo $USER_GROUP_ID;exit;

		if(empty($menuid))
		{
			$menuid= "-1";
		}

		$statement= " AND A.MENU_GROUP_ID = 1 AND A.USER_GROUP_ID = ".$USER_GROUP_ID." AND A.MENU_ID = '".$menuid."'";
		$set= new Menu();
		$set->selectparamsapk($statement);
		// echo $set->query;exit;
		$set->firstRow();
		$vreturn= [];
		$vreturn["lihat"]= $set->getField("LIHAT");
		$vreturn["kirim"]= $set->getField("KIRIM");
		$vreturn["tarik"]= $set->getField("TARIK");
		$vreturn["sync"]= $set->getField("SYNC");
		return $vreturn;
	}
}