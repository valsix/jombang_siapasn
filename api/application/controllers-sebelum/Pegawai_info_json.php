<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Pegawai_info_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        // $this->db->query("alter session set nls_date_format='DD-MM-YYYY'"); 
        
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base/PegawaiInfo');

        $user_login_log= new UserLoginLog;
        
        $reqToken = $this->input->get("reqToken");
        $reqTahun = $this->input->get("reqTahun");
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
            $set = new PegawaiInfo;
            $aColumns = array("PEGAWAI_ID", "NIP_LAMA", "NIP_BARU", "NAMA_LENGKAP", "TEMPAT_LAHIR", "TANGGAL_LAHIR", "JENIS_KELAMIN_NAMA", "ALAMAT", "RT", "RW", "TELEPON", "HP", "NIK", "NO_KK", "NPWP", "PROPINSI_NAMA", "KABUPATEN_NAMA", "KECAMATAN_NAMA", "DESA_NAMA", "PEGAWAI_STATUS_NAMA", "PEGAWAI_KEDUDUKAN_NAMA", "PANGKAT_RIWAYAT_KODE", "PANGKAT_RIWAYAT_TMT", "PANGKAT_RIWAYAT_PANGKAT_ID", "GAJI_RIWAYAT_PANGKAT_ID", "JABATAN_RIWAYAT_JENIS_JABATAN_ID", "JABATAN_RIWAYAT_JENIS_JABATAN", "JABATAN_RIWAYAT_TIPE_PEGAWAI_ID", "JABATAN_RIWAYAT_TIPE_PEGAWAI_NAMA", "JABATAN_RIWAYAT_NAMA", "JABATAN_RIWAYAT_ESELON", "JABATAN_RIWAYAT_TMT", "JABATAN_RIWAYAT_FT_ID", "JABATAN_RIWAYAT_PANGKAT_ID_MIN", "JABATAN_RIWAYAT_PANGKAT_ID_MAX", "JABATAN_TUGAS_NAMA", "JABATAN_TUGAS_TMT", "SATUAN_KERJA_NAMA", "SATUAN_KERJA_INDUK", "PATH", "DIKLAT_STRUKTURAL_NAMA", "DIKLAT_STRUKTURAL_TANGGAL", "PPK_TAHUN", "PPK_NILAI", "PENDIDIKAN_ID_CPNS", "PENDIDIKAN_NAMA_CPNS", "PENDIDIKAN_JURUSAN_NAMA_CPNS", "PENDIDIKAN_ID_DIAKUI", "PENDIDIKAN_NAMA_DIAKUI", "PENDIDIKAN_JURUSAN_NAMA_DIAKUI","SATUAN_KERJA_ID");

            $statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
            $set->selectByParams(array(), -1, -1, $reqTahun, $statement);
             // echo $set->query;exit();
            
            $total = 0;
            while($set->nextRow())
            {
                $row = array();
                for ( $i=0 ; $i<count($aColumns) ; $i++ )
                {
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