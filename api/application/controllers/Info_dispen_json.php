<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Info_dispen_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        // $this->db->query("alter session set nls_date_format='DD-MM-YYYY'"); 
        
    }
 
    // show data entitas
    function index_get() {
        // http://192.168.88.100/jombang/siapasn/api/Info_dispen_json?reqToken=24ef14945de5cf9fcbade3429f7d5fc7
        // https://siapasn.jombangkab.go.id/api/Info_dispen_json?reqToken=24ef14945de5cf9fcbade3429f7d5fc7

        $this->load->model('UserLoginLog');
        $this->load->model('base/InfoKhusus');
        $this->load->model('base/SatuanKerja');

        $user_login_log= new UserLoginLog;
        
        $reqToken = $this->input->get("reqToken");
        $reqMode = $this->input->get("reqMode");
        // $id = $this->input->get("id");
        $nip = $this->input->get("nip");

        //CEK PEGAWAI ID DARI TOKEN
        $user_login_log = new UserLoginLog();
        
        // $reqSatuanKerjaId= $user_login_log->getToken(array("TOKEN" => $reqToken, "STATUS" => '1'));
        // "24ef14945de5cf9fcbade3429f7d5fc7"
        if($reqToken == md5('valsixdispen'))
        {
            $reqSatuanKerjaId= 5492;
            if(!empty($nip))
            {
                $statementsatuankerja= " AND A.NIP_BARU = '".$nip."'";
            }
            
            if($reqSatuanKerjaId !== "-1" && !empty($reqSatuanKerjaId))
            {
                $skerja= new SatuanKerja();
                $reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
                unset($skerja);
                $statementsatuankerja.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
                // echo $statementsatuankerja;exit;
            }

            $tahunnow= date("Y")-1;
            // $statementsatuankerja.= " AND A.STATUS_PEGAWAI_ID IN (1,2)";
            // echo $statementsatuankerja;exit();

            $set = new InfoKhusus;
            $aColumns = array("ID", "NIP_BARU", "NIP_LAMA", "NAMA_LENGKAP", "JENIS_KELAMIN", "AGAMA", "TEMPAT_LAHIR", "TANGGAL_LAHIR", "ID_STATUS_NIKAH", "STATUS_NIKAH", "ALAMAT", "ALAMAT_RT", "ALAMAT_RW", "PROPINSI", "KABUPATEN", "KECAMATAN", "DESA", "TMT_CPNS", "STATUS_PEGAWAI_ID", "STATUS_PEGAWAI", "JENIS_PEGAWAI_ID", "JENIS_JABATAN", "MASA_KERJA_GAJI_BULAN", "MASA_KERJA_GAJI_TAHUN",  "TANGGAL_SK_JABATAN", "TMT_SK_JABATAN", "TMT_ESELON", "PEJABAT_PENETAP_JABATAN", "NAMA_JABATAN", "ID_SATKER_INDUK", "SATUAN_KERJA_INDUK", "ID_SATUAN_KERJA_ANAK", "ESELON_NAMA", "SATUAN_KERJA_ANAK", "NAMA_JURUSAN", "NAMA_LEMBAGA", "TINGKAT_PENDIDIKAN", "TANGGAL_SK_PANGKAT", "TMT_SK_PANGKAT", "PEJABAT_PENETAP_PANGKAT", "GOLONGAN_ID", "GOLONGAN", "PANGKAT", "PAK_TANGGAL_SK", "PAK_KREDIT_UTAMA", "PAK_KREDIT_PENUNJANG");

            $statement= $statementsatuankerja;
            $set->selectByParamsDispen(array(), -1, -1, $statement);
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
            
            $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => $total ,'result' => $result));
        }
        else
        {
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
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