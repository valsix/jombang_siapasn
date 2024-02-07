<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class skp_combo_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// trow to unauthenticated page!
			//redirect('Login');
		}       
		
		/* GLOBAL VARIABLE */
		$this->USER_GROUP= $this->kauth->getInstance()->getIdentity()->USER_GROUP;
		$this->LOGIN_USER= $this->kauth->getInstance()->getIdentity()->LOGIN_USER;
		$this->SATUAN_KERJA_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_ID;
		$this->SATUAN_KERJA_BKD_ID= $this->kauth->getInstance()->getIdentity()->SATUAN_KERJA_BKD_ID;
		$this->LOGIN_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_ID;
		$this->LOGIN_PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID;
		$this->STATUS_SATUAN_KERJA_BKD= $this->kauth->getInstance()->getIdentity()->STATUS_SATUAN_KERJA_BKD;
	}	

	function namajabatan() 
	{
		$this->load->model("PenilaianSkp");
		
		$reqId=  $this->input->get('reqId');
		$reqTipeJabatanId= $this->input->get('reqTipeJabatanId');
		$reqTanggalBatas= $this->input->get('reqTanggalBatas');
		
		$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
		if(empty($search_term))
		{
			$search_term = isset($_GET['term']) ? $_GET['term'] : "";
		}
		
		$set = new PenilaianSkp();
		
		//$statement= " AND A.STATUS IS NULL";
		$statement.= " AND UPPER(A.NAMA) LIKE '%".strtoupper($search_term)."%' ";
		
		// if($reqId == ""){}
		// else
		// $statement.= " AND A.SATUAN_KERJA_PARENT_ID = ".$reqId;

		// if($reqTipeJabatanId == "x2")
		// $statement.= " AND (A.TIPE_JABATAN_ID NOT IN (2) OR A.TIPE_JABATAN_ID IS NULL)";

		// if($reqTanggalBatas == ""){}
		// else
		// {
		// 	$statement .= " 
		// 	AND
		// 	COALESCE(A.MASA_BERLAKU_AWAL, TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD'))
		// 	<= TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD')
		// 	AND
		// 	COALESCE(A.MASA_BERLAKU_AKHIR, TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD'))
		// 	>= TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD')";
		// }
		//$statement.= " AND ( A.SATUAN_KERJA_ID = ANY( AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$reqId.") ) )";
		
		$set->selectByParamsCariJabatan(array(), 70, 0, $statement);
		// echo $set->query;exit;
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("ID");
			$arr_json[$i]['label'] = $set->getField("NAMA");
			$arr_json[$i]['desc'] = $set->getField("NAMA")."<br/><label style='font-size:12px'>".$set->getField("NAMA_DETIL")."</label>";
			$i++;
		}
		echo json_encode($arr_json);		
		
	}

	function namaunor() 
	{
		$this->load->model("PenilaianSkp");
		
		$reqId=  $this->input->get('reqId');
		$reqTipeJabatanId= $this->input->get('reqTipeJabatanId');
		$reqTanggalBatas= $this->input->get('reqTanggalBatas');
		
		$search_term = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
		if(empty($search_term))
		{
			$search_term = isset($_GET['term']) ? $_GET['term'] : "";
		}
		
		$set = new PenilaianSkp();
		
		//$statement= " AND A.STATUS IS NULL";
		$statement.= " AND UPPER(A.NAMA) LIKE '%".strtoupper($search_term)."%' ";
		
		
		$set->selectByParamsCariUnor(array(), 70, 0, $statement);
		//echo $set->query;exit;
		$arr_json = array();
		$i = 0;
		while($set->nextRow())
		{
			$arr_json[$i]['id'] = $set->getField("ID");
			$arr_json[$i]['label'] = $set->getField("NAMA");
			$arr_json[$i]['desc'] = $set->getField("NAMA")."<br/><label style='font-size:12px'>".$set->getField("NAMA_DETIL")."</label>";
			$i++;
		}
		echo json_encode($arr_json);		
		
	}


}
?>