<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Sk_pns_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        // $this->db->query("alter session set nls_date_format='DD-MM-YYYY'"); 
        
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base/Skpns');

        $user_login_log= new UserLoginLog;
        
        $reqToken = $this->input->get("reqToken");
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
            $set = new Skpns;
            $aColumns = array("SK_PNS_ID","PEGAWAI_ID","PEJABAT_PENETAP_ID","PEJABAT_PENETAP","NO_SK","TANGGAL_SK","TMT_PNS","SUMPAH","NAMA_PENETAP","NIP_PENETAP","NO_UJI_KESEHATAN","TANGGAL_UJI_KESEHATAN","NO_PRAJAB","TANGGAL_PRAJAB","TANGGAL_SUMPAH","MASA_KERJA_TAHUN","MASA_KERJA_BULAN","GAJI_POKOK","TANGGAL_BERITA_ACARA","NOMOR_BERITA_ACARA","KETERANGAN_LPJ","NO_SK_SUMPAH","PEJABAT_PENETAP_SUMPAH","PEJABAT_PENETAP_SUMPAH_ID
                    ","PANGKAT_KODE","PANGKAT_ID","PANGKAT_NAMA
                    ","PEJABAT_PENETAP_NAMA" ,"JENIS_JABATAN_ID","STATUS_CALON_JFT","JABATAN_TUGAS","JABATAN_FU_ID","JABATAN_FT_ID","JALUR_PENGANGKATAN", "TEMP_VALIDASI_ID", "TEMP_VALIDASI_HAPUS_ID", "VALIDASI", "VALIDATOR", "PERUBAHAN_DATA", "TIPE_PERUBAHAN_DATA", "TANGGAL_VALIDASI");
            $statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
            $set->selectByPersonal(array(), -1, -1, $reqPegawaiId, $reqRowId, $statement);
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