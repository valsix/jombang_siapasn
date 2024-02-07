<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Pegawai_baru_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        // $this->db->query("alter session set nls_date_format='DD-MM-YYYY'"); 
        
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base-new/Pegawai');
        $this->load->model('base/SatuanKerja');

        $user_login_log= new UserLoginLog;
        
        $reqToken = $this->input->get("reqToken");
        $reqMode = $this->input->get("reqMode");

        //CEK PEGAWAI ID DARI TOKEN
     

        
        // echo $reqPegawaiId;exit();
        // $reqPegawaiId = $reqToken;
        // echo $reqMode;exit();
        // echo $reqSatuanKerjaId;exit();

                $set = new Pegawai;
                $aColumns = array("PEGAWAI_ID", "PEGAWAI_ID_SAPK", "STATUS", "SATUAN_KERJA_ID", "JABATAN_RIWAYAT_ID", "PENDIDIKAN_RIWAYAT_ID","GAJI_RIWAYAT_ID","PANGKAT_RIWAYAT_ID","JENIS_PEGAWAI_ID","KEDUDUKAN","TIPE_PEGAWAI_ID","PEGAWAI_STATUS_ID","PEGAWAI_STATUS_NAMA","PEGAWAI_KEDUDUKAN_TMT","PEGAWAI_KEDUDUKAN_NAMA","STATUS_PEGAWAI_ID","NIP_LAMA","NIP_BARU","NAMA","GELAR_DEPAN", "GELAR_BELAKANG", "TEMPAT_LAHIR", "TANGGAL_LAHIR", "BPJS","BPJS_TANGGAL","NPWP_TANGGAL","ALAMAT_KETERANGAN","EMAIL_KANTOR","REKENING_NAMA","GAJI_POKOK",
                    "TUNJANGAN_KELUARGA","TUNJANGAN","GAJI_BERSIH",
                    "JENIS_KELAMIN", "STATUS_KAWIN", "SUKU_BANGSA", "GOLONGAN_DARAH", "EMAIL", "ALAMAT", "RT", "RW", "KODEPOS", "TELEPON", "HP", 
                    "TELEPON_KANTOR", "FACEBOOK", "TWITTER", "WHATSAPP", "TELEGRAM", "KARTU_PEGAWAI","ASKES", "TASPEN","NPWP", "NIK", "NO_REKENING","SK_KONVERSI_NIP", "NO_URUT", "NO_KK", "NO_RAK_BERKAS", 
                    "BANK_ID", "AGAMA_ID"
                    , "KETERANGAN_1", "KETERANGAN_2", "LAST_USER", "LAST_DATE", "JENIS_PENGADAAN_ID","SATUAN_KERJA_NAMA_DETIL","NAMA_LENGKAP","PROPINSI_ID","PROPINSI_NAMA","KABUPATEN_ID","KABUPATEN_NAMA","KECAMATAN_ID","KECAMATAN_NAMA","DESA_ID","DESA_NAMA","TEMP_VALIDASI_ID", "TEMP_VALIDASI_HAPUS_ID", "VALIDASI", "VALIDATOR", "PERUBAHAN_DATA", "TIPE_PERUBAHAN_DATA", "TANGGAL_VALIDASI", "PATH", "BARCODE" );
                $statement= " AND A.TOKEN_BARU_PEGAWAI = '".$reqToken."'";
                            
                $set->selectByParams(array(), -1, -1,$statement);
                  // echo $set->query;exit();
                $set->firstRow();
                
                $result = array();
                $row = array();
                for ( $i=0 ; $i<count($aColumns) ; $i++ )
                {
                    if($aColumns[$i] == "TMT")
                        $row[trim($aColumns[$i])] = getFormattedDateView($set->getField(trim($aColumns[$i])));
                    elseif($aColumns[$i] == "PATH")
                        $row[trim($aColumns[$i])] = $set->getField("PATH")."?random=".rand();
                    elseif($aColumns[$i] == "BARCODE")
                        $row[trim($aColumns[$i])] = "uploads/qr/".$set->getField("NIP_BARU").".png";
                    else
                        $row[trim($aColumns[$i])] = $set->getField(trim($aColumns[$i]));
                }
                $result[] = $row;
                
                
                $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($aColumns) ,'result' => $result));
            }
        

    

	
  
 
    // update data entitas
    function index_put() {
    }
 
    // delete entitas
    function index_delete() {
    }
 
}