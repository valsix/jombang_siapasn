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
        $this->load->model('base/Pegawaisapk');

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
            $set = new PegawaiSapk;
            $aColumns = array("pns_id", "nip_baru", "nip_lama", "nama", "gelar_depan", "gelar_blk", "tempat_lahir_id", "tempat_lahir_nama", "tgl_lahir", "jenis_kelamin", "agama_id", "agama_nama", "jenis_kawin_id", "jenis_kawin_nama", "nik", "nomor_hp", "email", "alamat", "npwp_nomor", "bpjs", "jenis_pegawai_id", "jenis_pegawai_nama", "kedudukan_hukum_id", "kedudukan_hukum_nama", "status_cpns_pns", "kartu_pegawai", "nomor_sk_cpns", "tgl_sk_cpns", "tmt_cpns", "nomor sk pns", "tgl sk pns", "tmt_pns", "gol_awal_id", "gol_awal_nama", "gol_id", "gol_nama", "tmt_golongan", "mk_tahun", "mk_bulan", "jenis_jabatan_id", "jenis_jabatan_nama", "jabatan_id", "jabatan_nama", "tmt_jabatan", "tingkat pendidikan id", "tingkat pendidikan_nama", "pendidikan_id", "pendidikan_nama", "tahun_lulus", "kpkn_id", "kpkn_nama", "lokasi_kerja_id", "lokasi_kerja_nama", "unor_id", "unor_nama", "unor_induk_id", "unor_induk_nama", "instansi_induk_id", "instansi_induk_nama", "instansi_kerja_id", "instansi_kerja_nama", "satuan_kerja_induk_id", "satuan_kerja_induk_nama", "satuan_kerja_kerja_id", "satuan_kerja_kerja_nama", "pegawai_id");

            $statement= " AND pegawai_id = ".$reqPegawaiId;
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