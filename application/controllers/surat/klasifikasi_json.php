<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class klasifikasi_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// trow to unauthenticated page!
			//redirect('Login');
		}       
		
		/* GLOBAL VARIABLE */
		$this->LOGIN_USER= $this->kauth->getInstance()->getIdentity()->LOGIN_USER;
	}	
	
	function combo() 
	{
		$this->load->model("persuratan/Klasifikasi");
		$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
		if(empty($search_term))
		{
			$search_term = isset($_GET['term']) ? $_GET['term'] : "";
		}
		
		$reqId= $this->input->get("reqId");
		$reqParentId= $this->input->get("reqParentId");
		
		$set= new Klasifikasi();
		//$statement= " AND A.STATUS IS NULL";
		if($reqParentId == ""){}
		else
		{
			$statement.= " AND A.KLASIFIKASI_PARENT_ID = ".$reqParentId;
		}
		$statement.= " AND UPPER(A.KODE) LIKE '%".strtoupper($search_term)."%' ";
		
		$set->selectByParams(array(), -1,-1, $statement);
		//echo $set->query;exit;
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("KLASIFIKASI_ID");
			$arr_json[$i]['label'] = $set->getField("KODE");
			$arr_json[$i]['infonama'] = $set->getField("KODE")." - ".$set->getField("KLASIFIKASI_DETIL");
			$arr_json[$i]['desc'] = $set->getField("KODE")."<br/>".$set->getField("KLASIFIKASI_DETIL");
			$i++;
		}
		echo json_encode($arr_json);		
		
	}	

		
}
?>