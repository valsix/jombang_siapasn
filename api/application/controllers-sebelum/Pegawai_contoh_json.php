<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class pegawai_contoh_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        // $this->db->query("alter session set nls_date_format='DD-MM-YYYY'"); 
        
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base/Contoh');
        $this->load->model('base/SatuanKerja');

        $user_login_log= new UserLoginLog;
        
        $reqToken = $this->input->get("reqToken");
        $reqMode = $this->input->get("reqMode");

        //CEK PEGAWAI ID DARI TOKEN
        $user_login_log = new UserLoginLog();
        $reqPegawaiId = $user_login_log->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        // echo $user_login_log->query;exit();
        // echo $reqPegawaiId;exit();
        $reqSatuanKerjaId = $user_login_log->getTokenSatuanKerjaId(array("TOKEN" => $reqToken, "STATUS" => '1'));
         // echo $user_login_log->query;exit();
        // echo $reqSatuanKerjaId;exit();

        // echo $reqPegawaiId."-".$reqSatuanKerjaId;exit();
        if($reqMode == "satuankerja")
        {
            if($reqSatuanKerjaId == "")
            {
                $this->response(array('status' => 'fail', 'message' => 'Anda tidak berhak untuk mendapatkan info personal pegawai.', 'code' => 502));
                exit();
            }

            // echo $reqSatuanKerjaId;exit;
            if($reqSatuanKerjaId == "-1")
            {
                $reqSatuanKerjaId= "";
            }
            else
            {
                $skerja= new SatuanKerja();
                $reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
                unset($skerja);
                $statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
            }
            // echo $statement;exit;

            $statementsatuankerja= " 
            AND EXISTS
            (
                SELECT 1
                FROM
                (
                    SELECT PEGAWAI_ID FROM PEGAWAI A WHERE A.STATUS_PEGAWAI_ID IN (1,2) 
                    ".$statement."
                ) X WHERE 1=1 AND A.PEGAWAI_ID = X.PEGAWAI_ID
            )
            ";
            // echo $statementsatuankerja;exit();

            $set = new Contoh;
            $aColumns = array("NIP_BARU", "NAMA_LENGKAP");
            $statement= $statementsatuankerja;
            // $set->selectByParams(array(), -1, -1, $statement);
            $set->selectByParams(array(), 2, 1, $statement);
            // echo $set->query;exit();

            $total = 0;
            while($set->nextRow())
            {
                $row = array();
                for ( $i=0 ; $i<count($aColumns) ; $i++ )
                {
                    if($aColumns[$i] == "TMT")
                        $row[trim($aColumns[$i])] = getFormattedDateView($set->getField(trim($aColumns[$i])));
                    else
                        $row[trim($aColumns[$i])] = $set->getField(trim($aColumns[$i]));
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
            if($reqPegawaiId == "")
            {
                $this->response(array('status' => 'fail', 'message' => 'Anda tidak berhak untuk mendapatkan info personal pegawai.', 'code' => 502));
            }
            else
            {
                $set = new Contoh;
                $aColumns = array("NIP_BARU", "NAMA_LENGKAP");
                $statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
                $set->selectByParams(array(), -1, -1, $statement);
                // echo $set->query;exit();
                
                $total = 0;
                while($set->nextRow())
                {
                
                    $row = array();
                    for ( $i=0 ; $i<count($aColumns) ; $i++ )
                    {
                        if($aColumns[$i] == "TMT")
                            $row[trim($aColumns[$i])] = getFormattedDateView($set->getField(trim($aColumns[$i])));
                        else
                            $row[trim($aColumns[$i])] = $set->getField(trim($aColumns[$i]));
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