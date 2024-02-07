<?php
error_reporting(0);
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class App_launcher_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
    }
 
    // show data entitas
	function index_get() {
        $aColumns = array("APLIKASI_ID", "NAMA", "ICON");
		
        $this->load->model('UserLoginLog');
		$this->load->model('Pegawai');
		$this->load->model('portal/Aplikasi');

        $reqToken = $this->input->get("reqToken");

		//CEK PEGAWAI ID DARI TOKEN
        $user_login_log = new UserLoginLog();
		$reqPegawaiId = $user_login_log->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
		// $reqPegawaiId = '9014140KP';

		if($reqPegawaiId <> "0")
		{
			$reqTotal = 0;
			$this->load->model('Aplikasi');
	        $aplikasi = new Aplikasi();
			$statement_privacy = " AND NOT A.APLIKASI_ID = 1 AND (EXISTS(SELECT 1 FROM PEGAWAI_USER_GROUP X WHERE X.APLIKASI_ID = A.APLIKASI_ID AND NID = '".$reqPegawaiId."') OR EXISTS(SELECT 1 FROM USER_GROUP X WHERE X.APLIKASI_ID = A.APLIKASI_ID AND X.STATUS_DEFAULT = '1'))";

			$aplikasi->selectByParamsAplikasiByToken($reqPegawaiId, array(), -1, -1, $statement_privacy);
	         // echo $penilaian_kinerja->query; exit;
	        while($aplikasi->nextRow())
	        {
				$rowDetil = array();
				for ( $i=0 ; $i<count($aColumns) ; $i++ )
				{
					$rowDetil[trim($aColumns[$i])] = $aplikasi->getField(trim($aColumns[$i]));
				}

				$result[] = $rowDetil;
				$reqTotal++;
			}

			if ($reqTotal==0) {
				$rowDetil = array();
				for ( $i=0 ; $i<count($aColumns) ; $i++ )
				{
					
						$rowDetil[trim($aColumns[$i])] = '';
				}

				$result[] = $rowDetil;
			}


			$this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($aColumns) ,'result' => $result));
		}
		else
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakahir', 'code' => 502));
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