<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Absensi_permohonan_ubah_absen_json extends REST_Controller {
 
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
        // $reqPeriode= "122019";
        $reqMode = $this->input->get("reqMode");

        //CEK PEGAWAI ID DARI TOKEN
        $user_login_log = new UserLoginLog();
        $reqPegawaiId = $user_login_log->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        // echo $user_login_log->query;exit();

        if($reqPegawaiId == "")
        {
            $this->response(array('status' => 'fail', 'message' => 'Anda tidak berhak untuk mendapatkan info personal pegawai.', 'code' => 502));
        }
        else
        {
            // buat parstisi
            /*$this->setpartisi($reqPeriode);

            $set = new Absensi;
            $aColumns = array("NIP_BARU_NAMA_LENGKAP", "SATUAN_KERJA_NAMA", "NAMA_IJIN_KOREKSI", "TANGGAL", "TANGGAL_AWAL", "STATUS", "PERMOHONAN_CUTI_ID");
            
            $statement= " AND A.PEGAWAI_ID = '".$reqPegawaiId."'";
            $statement.= " AND P.NAMA_PERMOHONAN = 'PERMOHONAN_LUPA_ABSEN'";

            if(!empty($reqPeriode))
            {
                $statement.= " AND TO_CHAR(P.TANGGAL, 'MMYYYY') = '".$reqPeriode."'";
            }

            // $statement= " AND A.HARI = '".$reqHariTampil."'";
            $set->selectByParamsPermohonan(array(), -1, -1, $statement);
            // echo $set->query;exit();
            
            $total = 0;
            while($set->nextRow())
            {
                $row = array();
                for ( $i=0 ; $i<count($aColumns) ; $i++ )
                {
                    $tempValue= $set->getField(trim($aColumns[$i]));
                    if(empty($tempValue)) $tempValue= "";
                    
                    $row[trim($aColumns[$i])] = $tempValue;
                }
                $result[] = $row;

                $total++;
            }*/
            
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

            $reqTanggalIjin = $this->input->post('reqTanggalIjin');
            $reqUbahStatus = $this->input->post('reqUbahStatus');
            $reqStatusMasuk = $this->input->post('reqStatusMasuk');
            $reqStatusPulang = $this->input->post('reqStatusPulang');
            $reqTanggal = $this->input->post('reqTanggal');
            $reqJam = $this->input->post('reqJam');
            $reqPeriode= getMonth($reqTanggal).getDay($reqTanggal);
            $reqKeperluan = "";//$this->input->post('reqKeperluan');
            $reqKeterangan = $this->input->post('reqKeterangan');
            $reqLokasi = $this->input->post('reqLokasi');
            $reqMode = $this->input->post("reqMode");
            $reqLastCreateUser = $this->input->post("reqLastCreateUser");

            // buat parstisi
            $this->setpartisi($reqPeriode);

            $tambahanwaktu= " ".$reqJam;
            $reqTanggalIjin= $reqTanggalIjin.$tambahanwaktu;

            // echo $reqUbahStatus;exit();

            if($reqMode == "insert")
            {
                $set = new Absensi();
                $set->setField("PERMOHONAN_LUPA_ABSEN_ID", $reqId);
                $set->setField("PEGAWAI_ID", $reqPegawaiId);
                $set->setField("TAHUN", date('Y'));
                $set->setField("TANGGAL", dateToDBCheck($reqTanggal));
                $set->setField("JENIS_LUPA_ABSEN", valToNull($reqUbahStatus));
                $set->setField("TANGGAL_IJIN", dateTimeToDBCheck($reqTanggalIjin));
                $set->setField("KETERANGAN", $reqKeterangan);
                $set->setField("STATUS_MASUK", valToNull($reqStatusMasuk));
                $set->setField("STATUS_PULANG", valToNull($reqStatusPulang));
                $set->setField("LAST_CREATE_DATE", "CURRENT_DATE");
                $set->setField("LAST_CREATE_USER", $reqLastCreateUser);

                if($set->insertPermohonanLupaAbsen())
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