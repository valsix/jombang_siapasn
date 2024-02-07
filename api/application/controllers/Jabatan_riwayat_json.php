<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Jabatan_riwayat_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        // $this->db->query("alter session set nls_date_format='DD-MM-YYYY'"); 
        
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base/JabatanRiwayat');

        $user_login_log= new UserLoginLog;
        
        $reqToken = $this->input->get("reqToken");
        $reqId= $this->input->get("reqId");
        $reqRowId= $this->input->get("reqRowId");

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
            $set = new JabatanRiwayat;
            $aColumns = array("JABATAN_RIWAYAT_ID","PEGAWAI_ID","PEJABAT_PENETAP_ID","PEJABAT_PENETAP","TIPE_PEGAWAI_ID","JABATAN_FU_ID","JABATAN_FT_ID","ESELON_ID","ESELON_NAMA","NO_SK","TANGGAL_SK","TMT_JABATAN","NAMA","NO_PELANTIKAN","TANGGAL_PELANTIKAN","TUNJANGAN","KREDIT","SATKER_ID","SATUAN_KERJA_NAMA_DETILbak","SATUAN_KERJA_NAMA_DETIL","JENIS_JABATAN_ID","JENIS_JABATAN_NAMA","IS_MANUAL","BULAN_DIBAYAR","TMT_BATAS_USIA_PENSIUN","STATUS","LAST_USER","LAST_DATE","LAST_LEVEL","DATA_HUKUMAN","TMT_ESELON", "TEMP_VALIDASI_ID", "TEMP_VALIDASI_HAPUS_ID", "VALIDASI", "VALIDATOR", "PERUBAHAN_DATA", "TIPE_PERUBAHAN_DATA", "TANGGAL_VALIDASI");
            $statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
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