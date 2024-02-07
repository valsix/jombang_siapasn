<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Absensi_setting_klarifikasi_json extends REST_Controller {
 
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
        $reqTanggal = $this->input->get("reqTanggal");

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
            $set = new Absensi;
            $aColumns = array("SETTING_KLARIFIKASI_INFO");
            $statement= " AND A.MASA_BERLAKU_AWAL IS NOT NULL AND A.SETTING_KLARIFIKASI_ID = 1 AND TO_DATE('".$reqTanggal."','YYYY/MM/DD') BETWEEN MASA_BERLAKU_AWAL AND COALESCE(MASA_BERLAKU_AKHIR, TO_DATE('".$reqTanggal."','YYYY/MM/DD'))";

            $set->selectByParamsSettingKlarifikasi(array(), -1, -1, $statement);
            // echo $set->query;exit();
            
            $total = 0;
            while($set->nextRow())
            {
                $row = array();
                for ( $i=0 ; $i<count($aColumns) ; $i++ )
                {
                    if($aColumns[$i] == "SETTING_KLARIFIKASI_INFO")
                    {
                        $setdetil= new Absensi();
                        $setdetil->selectByParamsSettingKlarifikasiPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId));
                        $setdetil->firstRow();
                        $tempvaluepegawaiid= $setdetil->getField("PEGAWAI_ID");

                        $inforeturn= "";
                        if(empty($tempvaluepegawaiid))
                        {
                            $tempvalue= $set->getField("SETTING_KLARIFIKASI_ID");
                            if(!empty($tempvalue))
                                $inforeturn= 1;

                            $row[trim($aColumns[$i])] = $inforeturn;
                        }
                    }
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