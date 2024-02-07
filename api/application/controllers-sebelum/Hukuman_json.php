<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class hukuman_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        // $this->db->query("alter session set nls_date_format='DD-MM-YYYY'"); 
        
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base/Hukuman');

        $user_login_log= new UserLoginLog;
        
        $reqToken = $this->input->get("reqToken");

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
            $set = new Hukuman;
            $aColumns = array("HUKUMAN_ID","PEGAWAI_ID","PEJABAT_PENETAP_ID","PEJABAT_PENETAP","PERATURAN_ID","TINGKAT_HUKUMAN_ID","TINGKAT_HUKUMAN_NAMA","JENIS_HUKUMAN_ID","JENIS_HUKUMAN_NAMA","NO_SK","TANGGAL_SK","TMT_SK","KETERANGAN","BERLAKU","TANGGAL_MULAI","TANGGAL_AKHIR","LAST_USER","LAST_DATE","LAST_LEVEL","STATUS,TINGKAT_HUKUMAN_NAMA","JENIS_HUKUMAN_NAMA","PEJABAT_PENETAP_NAMA,","BERLAKU","TANGGAL_PEMULIHAN","TMT_BERIKUT_PANGKAT","TMT_BERIKUT_GAJI","PANGKAT_RIWAYAT_TERAKHIR_ID","GAJI_RIWAYAT_TERAKHIR_ID","PANGKAT_RIWAYAT_TURUN_ID","GAJI_RIWAYAT_TURUN_ID","PANGKAT_RIWAYAT_KEMBALI_ID","GAJI_RIWAYAT_KEMBALI_ID","JABATAN_RIWAYAT_ID","PEGAWAI_STATUS_ID");
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