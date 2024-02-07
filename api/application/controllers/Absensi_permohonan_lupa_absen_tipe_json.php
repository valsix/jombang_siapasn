<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Absensi_permohonan_lupa_absen_tipe_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        // $this->db->query("alter session set nls_date_format='DD-MM-YYYY'"); 
        
    }
 
    // show data entitas
    function index_get() {
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
        $this->load->model('UserLoginLog');
        $this->load->model('base/Absensi');

        // print_r($data);exit();

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
            $reqId = $this->input->post('reqId');
            $reqJenis = $this->input->post('reqJenis');
            $reqTipe = $this->input->post('reqTipe');
            $reqTanggal = $this->input->post('reqTanggal');
            $reqPeriode= getMonth($reqTanggal).getDay($reqTanggal);
            $reqKeperluan = "";//$this->input->post('reqKeperluan');
            $reqKeterangan = $this->input->post('reqKeterangan');
            $reqTanggalAwal = $this->input->post('reqTanggalAwal');
            $reqLokasi = $this->input->post('reqLokasi');
            $namaLinkFile = $this->input->post('namaLinkFile');
            $reqMode = $this->input->post("reqMode");
            $reqLastCreateUser = $this->input->post("reqLastCreateUser");

            // $this->response(array('status' => 'success', 'namaLinkFile' => $namaLinkFile, 'reqPeriode' => $reqPeriode));exit();

            // buat parstisi
            $this->setpartisi($reqPeriode);

            if($reqMode == "insert")
            {
                $set = new Absensi();
                $set->setField("PERMOHONAN_LUPA_ABSEN_ID", $reqId);
                $set->setField("PEGAWAI_ID", $reqPegawaiId);
                $set->setField("TANGGAL", dateToDBCheck($reqTanggal));
                $set->setField("JENIS_LUPA_ABSEN", $reqTipe);
                $set->setField("TANGGAL_IJIN", dateToDBCheck($reqTanggalAwal));
                $set->setField("KETERANGAN", $reqKeterangan);
                
                $set->setField("LAST_CREATE_DATE", "CURRENT_DATE");
                $set->setField("LAST_CREATE_USER", $reqLastCreateUser);

                if($set->insertPermohonanTipeLupaAbsen())
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