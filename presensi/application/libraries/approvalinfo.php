<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'kloader.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Kauth
 *
 * @author user
 */
class ApprovalInfo{
	var $approvalId1;
	var $approvalNama1;
	
    /******************** CONSTRUCTOR **************************************/
    function ApprovalInfo(){
		 $this->emptyProps();
    }

    /******************** METHODS ************************************/
    /** Empty the properties **/
    function emptyProps(){
		$this->approvalId1 = "";
		$this->approvalNama1 = "";
    }
		
    
    /** Verify user login. True when login is valid**/
    function getApproval($pegawaiId){			
		$CI =& get_instance();
		$CI->load->model("PegawaiApprovalView");
		
		$pegawai_atasan = new PegawaiApprovalView();
		$pegawai_atasan->selectByParamsAtasan(array("A.PEGAWAI_ID" => $pegawaiId));
		$pegawai_atasan->firstRow();
		$this->atasan = $pegawai_atasan->getField("NAMA_ATASAN")." (".$pegawai_atasan->getField("PEGAWAI_ID_ATASAN").")";
		$this->approvalId = $pegawai_atasan->getField("PEGAWAI_ID_ATASAN");
		$this->approvalNama = $pegawai_atasan->getField("NAMA_ATASAN");
		
    }

    function getApprovalBak($pegawaiId){			
		$CI =& get_instance();
		$CI->load->model("PegawaiApprovalView");

		// $urlAtasan = "http://10.254.250.108:1108/kpi2016/api/v2/atasan/".$reqPegawaiId;
		// $jsonAtasan = file_get_contents($urlAtasan, true);  
		// $arrAtasan = json_decode($jsonAtasan, true);
		
		// //echo $urlAtasan; 
		// $arrDataAtasan = $arrAtasan["result"]["atasanLangsung"];
		// $reqApprovalId = $arrDataAtasan["nip"];
		
		// unset($jsonAtasan);
		// unset($arrAtasan);
		// unset($arrDataAtasan);
				
		$this->approvalId = "128812185";
		$this->approvalNama = "128812185";
		
    }
			   
}
	
  /***** INSTANTIATE THE GLOBAL OBJECT */
  $approvalInfo = new ApprovalInfo();

?>
