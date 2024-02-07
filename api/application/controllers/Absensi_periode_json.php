<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Absensi_periode_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        // $this->db->query("alter session set nls_date_format='DD-MM-YYYY'"); 
        
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base/Absensi');

        $user_login_log= new UserLoginLog;
        
        $reqToken = $this->input->get("reqToken");
        $reqPeriode = $this->input->get("reqPeriode");
        $reqHari = $this->input->get("reqHari");
        // $reqPeriode= "122019";
        $reqMode = $this->input->get("reqMode");

        //CEK PEGAWAI ID DARI TOKEN
        $user_login_log = new UserLoginLog();
        $reqPegawaiId = $user_login_log->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        // echo $user_login_log->query;exit();
        // echo $reqPegawaiId;exit();
        // $reqPegawaiId = '9014140KP';

        if($reqPegawaiId == "")
        {
            $this->response(array('status' => 'fail', 'message' => 'Anda tidak berhak untuk mendapatkan info personal pegawai.', 'code' => 502));
        }
        else
        {
            // buat parstisi
            $this->setpartisi($reqPeriode);

            $set = new Absensi;
            $aColumns = array("PEGAWAI_ID", "HARI", "MASUK", "NAMA_HARI", "KETERANGAN", "AUTH_PULANG", "JAM_MASUK", "JAM_PULANG", "TERLAMBAT", "TERLAMBAT", "PULANG_CEPAT", "EX_JAM_MASUK", "K_MASUK", "K_MASUK_KETERANGAN", "T_MASUK", "S_MASUK", "K_PULANG", "K_PULANG_KETERANGAN", "T_PULANG", "S_PULANG", "STATUS_KOREKSI");

            if(!empty($reqHari))
            {
                $statement= " AND A.HARI = '".$reqHari."'";
            }
            $set->selectByParamsAbensiInfo(array(), -1, -1, $reqPegawaiId, $reqPeriode, $statement);
            // echo $set->query;exit();
            
            $total = 0;
            while($set->nextRow())
            {
                $row = array();
                for ( $i=0 ; $i<count($aColumns) ; $i++ )
                {
                    $tempValue= $set->getField(trim($aColumns[$i]));

                    if($aColumns[$i] == "S_MASUK" || $aColumns[$i] == "S_PULANG" || $aColumns[$i] == "STATUS_KOREKSI"){}
                    else
                    {
                        if(empty($tempValue)) $tempValue= "";
                    }

                    
                    $row[trim($aColumns[$i])] = $tempValue;
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

    function setpartisi($reqPeriode)
    {
        $this->load->model('base/Absensi');

        if(!empty($reqPeriode))
        {
            $tahun= getTahunPeriode($reqPeriode);
            // $tahun= $reqPeriode;
            for($i= 1; $i <= 12; $i++)
            {
                $reqPeriode= generateZeroDate($i,2).$tahun;
                // echo $reqPeriode."<br/>";exit();

                $set= new Absensi();
                $set->setPartisiTablePeriode($reqPeriode);
                // echo $set->query;exit();
            }
            // exit();
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