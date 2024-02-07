<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class data_utama_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        // $this->db->query("alter session set nls_date_format='DD-MM-YYYY'"); 
        
    }
 
    // show data entitas
    function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base/Pegawai');

        $user_login_log= new UserLoginLog;
        
        $reqToken= $this->input->get("reqToken");
        $reqId= $this->input->get("reqId");
        $reqRowId= $this->input->get("reqRowId");

        //CEK PEGAWAI ID DARI TOKEN
        $user_login_log = new UserLoginLog();
        $reqPegawaiId = $user_login_log->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        // echo $user_login_log->query;exit();
        // echo $reqPegawaiId;exit();

        if($reqPegawaiId == "")
        {
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
        }
        else
        {
            $set = new Pegawai;
            $aColumns = array(
                "PEGAWAI_ID", "SATUAN_KERJA_NAMA_DETIL", "SATUAN_KERJA_ID", "JENIS_PEGAWAI_ID", "JENIS_PEGAWAI_NAMA", "BPJS", "BPJS_TANGGAL", "NPWP_TANGGAL", "EMAIL_KANTOR", "REKENING_NAMA", "GAJI_POKOK", "TUNJANGAN", "TUNJANGAN_KELUARGA", "GAJI_BERSIH", "STATUS_MUTASI", "TMT_MUTASI", "INSTANSI_SEBELUM", "JENIS_PEGAWAI_ID", "TIPE_PEGAWAI_ID", "STATUS_PEGAWAI_ID", "PEGAWAI_STATUS_NAMA", "PEGAWAI_KEDUDUKAN_TMT", "PEGAWAI_KEDUDUKAN_NAMA", "NIP_LAMA", "NIP_BARU","NAMA", "GELAR_DEPAN", "GELAR_BELAKANG", "TEMPAT_LAHIR", "TANGGAL_LAHIR", "JENIS_KELAMIN", "STATUS_KAWIN", "SUKU_BANGSA", "GOLONGAN_DARAH", "EMAIL", "ALAMAT", "ALAMAT_KETERANGAN", "RT", "RW", "KODEPOS", "TELEPON", "HP", "KARTU_PEGAWAI", "ASKES", "TASPEN", "NPWP", "NIK", "NO_REKENING", "SK_KONVERSI_NIP", "BANK_ID", "AGAMA_ID", "NO_URUT", "NO_KK", "NO_RAK_BERKAS", "TELEPON_KANTOR", "FACEBOOK", "TWITTER", "WHATSAPP", "TELEGRAM", "KETERANGAN_1", "KETERANGAN_2", "PROPINSI_ID", "PROPINSI_NAMA", "KABUPATEN_ID", "KABUPATEN_NAMA", "KECAMATAN_ID", "KECAMATAN_NAMA", "DESA_ID", "DESA_NAMA","PEGAWAI_STATUS_ID"
                    , "TEMP_VALIDASI_ID", "TEMP_VALIDASI_HAPUS_ID", "VALIDASI", "VALIDATOR", "PERUBAHAN_DATA", "TIPE_PERUBAHAN_DATA", "TANGGAL_VALIDASI"
            );

            $statement= "";
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