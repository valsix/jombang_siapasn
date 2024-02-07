<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Pelayanan_karis_karsu_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        // $this->db->query("alter session set nls_date_format='DD-MM-YYYY'"); 
        
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base/Pelayanan');

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
            $set = new Pelayanan;
            $aColumns = array("PEGAWAI_ID","SATUAN_KERJA_ID","NIP_BARU","SURAT_MASUK_PEGAWAI_ID","SURAT_MASUK_BKD_ID","SURAT_MASUK_UPT_ID","SATUAN_KERJA_NAMA","SATUAN_KERJA_INDUK","SATUAN_KERJA_NAMA_UPT","SATUAN_KERJA_NAMA_BKD","SATUAN_KERJA_LENGKAP","NAMA_LENGKAP","NAMA NAMA_SAJA","NOMOR_USUL_BKDPP","PROSES_TANGGAL","PROSES_STATUS","STATUS_KEMBALI","STATUS_PERNAH_TURUN","NOMOR_SURAT_KELUAR","STATUS_SURAT_KELUAR","USULAN_SURAT_URUT");
            $statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
            $statement.= " AND SMP.JENIS_ID = 3 ";
            
            $set->selectByParamsKarisKarsu(array(), -1, -1, $statement);
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