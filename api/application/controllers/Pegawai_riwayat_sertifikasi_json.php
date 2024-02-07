<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Pegawai_riwayat_sertifikasi_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        // $this->db->query("alter session set nls_date_format='DD-MM-YYYY'"); 
        
    }
 
    // show data entitas
	function index_get() {
        $aColumns = array("KOMPENTENSI_ID", "NAMA", "TMT");
		
        $this->load->model('Elipse');
        $this->load->model('UserLoginLog');
		$elipse = new Elipse;
		$user_login_log = new UserLoginLog;
		
		$reqToken = $this->input->get("reqToken");

		//CEK PEGAWAI ID DARI TOKEN
        $user_login_log = new UserLoginLog();
		$reqPegawaiId = $user_login_log->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
		// $reqPegawaiId = '9014140KP';

		if($reqPegawaiId <> "0")
		{
			$elipse->selectByParamsSertifikasi(array("PEGAWAI_ID"=> $reqPegawaiId), -1, -1, "", " ORDER BY TMT DESC");
			$total = 0;
			while($elipse->nextRow())
			{
			
				$row = array();
				for ( $i=0 ; $i<count($aColumns) ; $i++ )
				{
                    if($aColumns[$i] == "TMT")
                        $row[trim($aColumns[$i])] = getFormattedDateView($elipse->getField(trim($aColumns[$i])));
                    else
						$row[trim($aColumns[$i])] = $elipse->getField(trim($aColumns[$i]));
				}
				$result[] = $row;

				$total++;
			}
			
			if($total == 0)
            {
                for ( $i=0 ; $i<count($aColumns) ; $i++ )
                {
                    $row[trim($aColumns[$i])] = "";
                }
                $result[] = $row;
            }
			
			$this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($aColumns) ,'result' => $result));
        }
        else
        {
        	$this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
        }
    }
	
    // insert new data to entitas
    function index_post() {

    }
 
    // update data entitas
    function index_put() {

    }
 
    // delete entitas
    function index_delete() {
        
    }
 
}