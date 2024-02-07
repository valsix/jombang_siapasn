<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class kabupaten_json extends CI_Controller {

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
		$this->LOGIN_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_ID;
		$this->LOGIN_PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID;
	}	
	
	function combo() 
	{
		$this->load->model("Kabupaten");
		$set = new Kabupaten();

		$reqPropinsiId= $this->input->get('reqPropinsiId');

		$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
		if(empty($search_term))
		{
			$search_term = isset($_GET['term']) ? $_GET['term'] : "";
		}

		if($reqPropinsiId == ""){}
		else
		$statement .= " AND PROPINSI_ID = ".$reqPropinsiId;

		$set->selectByParams(array(), 10, 0, $statement." AND UPPER(NAMA) LIKE '%".strtoupper($search_term)."%' ");

		$j=0;
		//echo $set->query;exit;
		while($set->nextRow())
		{
			$arr_parent[$j]['id'] = $set->getField("KABUPATEN_ID");
			$arr_parent[$j]['label'] = $set->getField("NAMA");
			$arr_parent[$j]['desc'] = $set->getField("NAMA");
			$j++;
		}

		if($j == 0)
		{
			$arr_parent[$j]['id'] = "";
			$arr_parent[$j]['label'] = "";
			$arr_parent[$j]['desc'] = "";
		}

		//echo json_encode($arr_parent, JSON_UNESCAPED_SLASHES);
		echo json_encode($arr_parent);

	}
}
?>