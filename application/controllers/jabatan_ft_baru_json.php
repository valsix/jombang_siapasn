<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class jabatan_ft_baru_json extends CI_Controller {

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
	
	

	function namajabatan() 
	{
		$this->load->model("JabatanFt");

		$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
		if(empty($search_term))
		{
			$search_term = isset($_GET['term']) ? $_GET['term'] : "";
		}

		$set = new JabatanFt();
		$reqTipePegawaiId= $this->input->get("reqTipePegawaiId");

		//$statement= " AND A.STATUS IS NULL";
		$statement.= " AND UPPER(A.NAMA) LIKE '%".strtoupper($search_term)."%' ";

		if($reqTipePegawaiId == ""){}
		else
		{
			$statement.= " AND A.TIPE_PEGAWAI_ID =  ".$reqTipePegawaiId;
		}

		$set->selectByParams(array(), 70, 0, $statement);
		echo $set->query;exit;
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("JABATAN_FT_ID");
			$arr_json[$i]['label'] = $set->getField("NAMA");
			$arr_json[$i]['satuan_kerja'] = $set->getField("SATUAN_KERJA_NAMA_DETIL");
			$arr_json[$i]['desc'] = $set->getField("NAMA");
			$i++;
		}
		echo json_encode($arr_json);		

	}

}
?>