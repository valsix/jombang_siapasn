<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class pendidikan_riwayat_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        // $this->db->query("alter session set nls_date_format='DD-MM-YYYY'"); 
        
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base/PendidikanRiwayat');

        $user_login_log= new UserLoginLog;
        
        $reqToken = $this->input->get("reqToken");
        $reqId= $this->input->get("reqId");
        $reqRowId= $this->input->get("reqRowId");
        $reqKondisi= $this->input->get("reqKondisi");
        // print_r ($reqKondisi);exit;
        //CEK PEGAWAI ID DARI TOKEN
        $user_login_log = new UserLoginLog();
        $reqPegawaiId = $user_login_log->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        // echo $user_login_log->query;exit();
        // echo $reqPegawaiId;exit();
        // $reqPegawaiId = '9014140KP';

        if($reqPegawaiId == "")
        {
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
        }
        else
        {
            $set = new PendidikanRiwayat;
            $aColumns = array("PENDIDIKAN_RIWAYAT_ID","PEGAWAI_ID","PENDIDIKAN_ID","PENDIDIKAN_JURUSAN_ID","PENDIDIKAN_JURUSAN_NAMA","NAMA","TEMPAT","KEPALA","NO_STTB","TANGGAL_STTB","JURUSAN","NO_SURAT_IJIN","TANGGAL_SURAT_IJIN","STATUS_PENDIDIKAN","GELAR_TIPE_NAMA","GELAR_DEPAN","GELAR_TIPE","GELAR_NAMA","LAST_USER","LAST_DATE","LAST_LEVEL","STATUS","PENDIDIKAN_NAMA","STATUS_NAMA","STATUS_PENDIDIKAN_NAMA","STATUS_TUGAS_IJIN_BELAJAR","STATUS_TUGAS_IJIN_BELAJAR_NAMA","STATUS_VALIDASI_TUGAS_IJIN_BELAJAR","PPPK_STATUS", "TEMP_VALIDASI_ID", "TEMP_VALIDASI_HAPUS_ID", "VALIDASI", "VALIDATOR", "PERUBAHAN_DATA", "TIPE_PERUBAHAN_DATA", "TANGGAL_VALIDASI");

            if(!empty($reqKondisi))
            {
                $statement= " AND A.STATUS_PENDIDIKAN = '1' AND A.PEGAWAI_ID = ".$reqId;
            }
            else
            {
                 $statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
            } 
            $set->selectByPersonal(array(), -1, -1, $reqPegawaiId, $reqId, $reqRowId, $statement);
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