<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Absensi_permohonan_file_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        // $this->db->query("alter session set nls_date_format='DD-MM-YYYY'"); 
        
    }
 
    // show data entitas
    function index_get() {
    }

    // insert new data to entitas
    function index_post() {
        $this->load->model('UserLoginLog');
        $this->load->model('base/Absensi');

        $user_login_log= new UserLoginLog;

        $reqToken = $this->input->post("reqToken");
        // $this->response(array('reqToken' => $reqToken));exit();

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
            $reqTableNama= $this->input->post('reqTableNama');
            $reqTableId= $this->input->post('reqTableId');
            $reqNama= $this->input->post('reqNama');
            $reqKeterangan= $this->input->post('reqKeterangan');
            $reqFileLink= $this->input->post('reqFileLink');
            $reqFileType= $this->input->post('reqFileType');
            $reqMode= "insert";

            if($reqMode == "insert")
            {
                // $set = new Absensi();
                // $reqTableId= $set->nextpermohonanfileid();
                // echo $reqTableId;exit();

                $set = new Absensi();
                $set->setField("PEGAWAI_ID", $reqPegawaiId);
                $set->setField("PERMOHONAN_TABLE_NAMA", $reqTableNama);
                $set->setField("PERMOHONAN_TABLE_ID", $reqTableId);
                $set->setField("NAMA", $reqNama);
                $set->setField("KETERANGAN", $reqKeterangan);
                $set->setField("LINK_FILE", $reqFileLink);
                $set->setField("TIPE", strtolower($reqFileType));
                $set->setField("LAST_USER", $reqPegawaiId);
                $set->setField("USER_LOGIN_ID", ValToNullDB($reqPegawaiId));
                $set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($reqPegawaiId));
                $set->setField("USER_LOGIN_CREATE_ID", ValToNullDB($reqPegawaiId));

                if($set->insertPermohonanFile())
                {
                    $reqId= $set->id;
                }

            }

            if(!empty($reqId))
            {
                $this->response(array('status' => 'success', 'id' => $reqId));
            }
            else
            {
                $this->response(array('status' => 'fail', 'code' => 502));
            }

        }
    }
 
    // update data entitas
    function index_put() {
    }
 
    // delete entitas
    function index_delete() {
    }
 
}