<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Surat_tanda_lulus_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base-new/SuratTandaLulus');

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
            $set = new SuratTandaLulus;
            $aColumns = array("SURAT_TANDA_LULUS_ID","PEGAWAI_ID","JENIS_ID","NO_STLUD","TANGGAL_STLUD","TANGGAL_MULAI","TANGGAL_AKHIR","NILAI_NPR","NILAI_NT","PENDIDIKAN_RIWAYAT_ID","PENDIDIKAN_ID","JENIS_NAMA","STATUS","LAST_USER","LAST_DATE","LAST_LEVEL","JENIS_ID","STATUS","LAST_USER","LAST_DATE", "TEMP_VALIDASI_ID", "TEMP_VALIDASI_HAPUS_ID", "VALIDASI", "VALIDATOR", "PERUBAHAN_DATA", "TIPE_PERUBAHAN_DATA", "TANGGAL_VALIDASI");
            $statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
            $set->selectByPersonal(array(), -1, -1, $reqPegawaiId);
             // echo $set->query;exit();
            
            $result = array();
            while($set->nextRow())
            {
            
                $row = array();
                for ( $i=0 ; $i<count($aColumns) ; $i++ )
                {
                    if( $aColumns[$i] == "TMT" )
                        $row[trim($aColumns[$i])] = getFormattedDate($set->getField(trim($aColumns[$i])));
                    else
                        $row[trim($aColumns[$i])] = $set->getField(trim($aColumns[$i]));
                }
                $result[] = $row;

            }
            
            
            $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($aColumns) ,'result' => $result));
        }
    }
	
    // insert new data to entitas
    function index_post() {
        $this->load->model('UserLoginLog');

        $user_login_log= new UserLoginLog;

        $reqToken = $this->input->post("reqToken");
        // $this->response(array('reqToken' => $reqToken));exit();

        //CEK PEGAWAI ID DARI TOKEN
        $user_login_log = new UserLoginLog();
        $reqPegawaiId= $user_login_log->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        // echo  $reqPegawaiId;exit;
        // echo $user_login_log->query;exit();

        if($reqPegawaiId == "")
        {
            $this->response(array('status' => 'fail', 'message' => 'Anda tidak berhak untuk mendapatkan info personal pegawai.', 'code' => 502));
        }
        else
        {
            $setdetil= new UserLoginLog();
            $setdetil->selectByParams(array("A.TOKEN" => $reqToken, "A.STATUS" => '1'), -1,-1);
            $setdetil->firstRow();
            // echo $setdetil->query;exit;
            $lastuser= $setdetil->getField("NAMA_PEGAWAI");
            $lastlevel= "0";
            $lastloginid= "";
            $lastloginpegawaiid= $setdetil->getField("PEGAWAI_ID");
            // echo $lastloginpegawaiid;exit;

            $reqMode= $this->input->post("reqMode");
            $reqTempValidasiId= $this->input->post("reqTempValidasiId");
            $reqId= $this->input->post("reqId");
            $reqRowId= $this->input->post("reqRowId");
            // echo $reqMode;exit;

            $this->load->model('base-new/SuratTandaLulus');

            $reqJenisId= $this->input->post('reqJenisId');
            $reqNoStlud= $this->input->post('reqNoStlud');
            $reqTglStlud= $this->input->post('reqTglStlud');
            $reqNilaiNpr= $this->input->post('reqNilaiNpr');
            $reqNilaiNt= $this->input->post('reqNilaiNt');
            $reqTanggalMulai= $this->input->post('reqTanggalMulai');
            $reqTanggalAkhir= $this->input->post('reqTanggalAkhir');
            $reqPendidikanRiwayatId= $this->input->post('reqPendidikanRiwayatId');
            $reqPendidikanId= $this->input->post('reqPendidikanId');

            $set= new SuratTandaLulus();
            $set->setField('PENDIDIKAN_RIWAYAT_ID', ValToNullDB($reqPendidikanRiwayatId));
            $set->setField('PENDIDIKAN_ID', ValToNullDB($reqPendidikanId));
            $set->setField("JENIS_ID", $reqJenisId);
            $set->setField("NO_STLUD", $reqNoStlud);
            $set->setField('TANGGAL_STLUD', dateToDBCheck($reqTglStlud));
            $set->setField('TANGGAL_MULAI', dateToDBCheck($reqTanggalMulai));
            $set->setField('TANGGAL_AKHIR', dateToDBCheck($reqTanggalAkhir));
            $set->setField('NILAI_NPR', ValToNullDB(CommaToDot($reqNilaiNpr)));
            $set->setField('NILAI_NT', ValToNullDB(CommaToDot($reqNilaiNt)));
            $set->setField('SURAT_TANDA_LULUS_ID', ValToNullDB($reqRowId));

            $set->setField('PEGAWAI_ID', ValToNullDB($reqPegawaiId));
            $set->setField("LAST_LEVEL", $lastlevel);
            $set->setField("LAST_USER", $lastuser);
            $set->setField("USER_LOGIN_ID", ValToNullDB($lastloginid));
            $set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($lastloginpegawaiid));
            $set->setField("LAST_DATE", "NOW()");

            $reqsimpan= "";
            if(empty($reqTempValidasiId))
            {
                if($set->insert())
                {
                    $reqsimpan= "1";
                    $reqTempValidasiId= $set->id;
                }
            }
            else
            {
                $set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);
                if($set->update())
                {
                    $reqsimpan= "1";
                }
            }

            if($reqsimpan !== "1")
            {
                $reqTempValidasiId= "";
            }
            // $tes = $set->query;

            if(!empty($reqTempValidasiId))
            {
                $this->response(array('status' => 'success', 'message' => 'Data berhasil disimpan.', 'id' => $reqTempValidasiId));
            }
            else
            {
                $this->response(array('status' => 'fail', 'code' =>  502));
            }

        }
    }
 
    // update data entitas
    function index_put() {
    }
 
    // delete entitas
    function index_delete() {
    }
 
}