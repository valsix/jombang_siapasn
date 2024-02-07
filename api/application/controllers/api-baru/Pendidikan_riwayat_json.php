<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Pendidikan_riwayat_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base-new/PendidikanRiwayat');

        $user_login_log= new UserLoginLog;
        
        $reqToken = $this->input->get("reqToken");
        $reqId= $this->input->get("reqId");
        $reqRowId= $this->input->get("reqRowId");
        $reqKondisi= $this->input->get("reqKondisi");
        // print_r ($reqKondisi);exit;
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
            $set = new PendidikanRiwayat;
            $aColumns = array("PENDIDIKAN_RIWAYAT_ID","PEGAWAI_ID","PENDIDIKAN_ID","PENDIDIKAN_JURUSAN_ID","PENDIDIKAN_JURUSAN_NAMA","NAMA","TEMPAT","KEPALA","NO_STTB","TANGGAL_STTB","JURUSAN","NO_SURAT_IJIN","TANGGAL_SURAT_IJIN","STATUS_PENDIDIKAN","GELAR_TIPE_NAMA","GELAR_DEPAN","GELAR_TIPE","GELAR_NAMA","LAST_USER","LAST_DATE","LAST_LEVEL","STATUS","PENDIDIKAN_NAMA","STATUS_NAMA","STATUS_PENDIDIKAN_NAMA","STATUS_TUGAS_IJIN_BELAJAR","STATUS_TUGAS_IJIN_BELAJAR_NAMA","STATUS_VALIDASI_TUGAS_IJIN_BELAJAR","PPPK_STATUS", "TEMP_VALIDASI_ID", "TEMP_VALIDASI_HAPUS_ID", "VALIDASI", "VALIDATOR", "PERUBAHAN_DATA", "TIPE_PERUBAHAN_DATA", "TANGGAL_VALIDASI");

            if(!empty($reqKondisi))
            {
                $statement= " AND A.STATUS_PENDIDIKAN = '1' AND A.PEGAWAI_ID = ".$reqId;
            }
            else
            {
                 $statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
            } 
            $set->selectByPersonal(array(), -1, -1, $reqPegawaiId, $reqId, $reqRowId, $statement);
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

            $this->load->model('base-new/PendidikanRiwayat');

            $reqNamaSekolah= $this->input->post('reqNamaSekolah');
            $reqNamaFakultas= $this->input->post('reqNamaFakultas');
            $reqPendidikanId= $this->input->post('reqPendidikanId');
            $reqTglSttb= $this->input->post('reqTglSttb');
            $reqJurusan= $this->input->post('reqJurusan');
            $reqJurusanId= $this->input->post('reqJurusanId');
            $reqAlamatSekolah= $this->input->post('reqAlamatSekolah');
            $reqKepalaSekolah= $this->input->post('reqKepalaSekolah');
            $reqNoSttb= $this->input->post('reqNoSttb');
            $reqStatusTugasIjinBelajar= $this->input->post('reqStatusTugasIjinBelajar');
            $reqStatusPendidikan= $this->input->post('reqStatusPendidikan');
            $reqNoSuratIjin= $this->input->post('reqNoSuratIjin');
            $reqTglSuratIjin= $this->input->post('reqTglSuratIjin');
            $reqGelarTipe= $this->input->post('reqGelarTipe');
            $reqGelarNamaDepan= $this->input->post('reqGelarNamaDepan');
            $reqGelarNama= $this->input->post('reqGelarNama');
            $reqPppkStatus= $this->input->post('reqPppkStatus');

            $set= new PendidikanRiwayat();
            $set->setField('PPPK_STATUS', ValToNullDB($reqPppkStatus));
            $set->setField('NAMA', $reqNamaSekolah);
            $set->setField('NAMA_FAKULTAS', $reqNamaFakultas);
            $set->setField('PENDIDIKAN_ID', $reqPendidikanId);
            $set->setField('TANGGAL_STTB', dateToDBCheck($reqTglSttb));
            $set->setField('JURUSAN', $reqJurusan);
            $set->setField('PENDIDIKAN_JURUSAN_ID', ValToNullDB($reqJurusanId));
            $set->setField('TEMPAT', $reqAlamatSekolah);
            $set->setField('KEPALA', $reqKepalaSekolah);
            $set->setField('NO_STTB', $reqNoSttb);
            $set->setField('STATUS_TUGAS_IJIN_BELAJAR', ValToNullDB($reqStatusTugasIjinBelajar));
            $set->setField('STATUS_PENDIDIKAN', $reqStatusPendidikan);
            $set->setField('NO_SURAT_IJIN', $reqNoSuratIjin);
            $set->setField('TANGGAL_SURAT_IJIN', dateToDBCheck($reqTglSuratIjin));
            $set->setField('GELAR_TIPE', $reqGelarTipe);
            $set->setField('GELAR_DEPAN', $reqGelarNamaDepan);
            $set->setField('GELAR_NAMA', $reqGelarNama);
            $set->setField('PENDIDIKAN_RIWAYAT_ID', ValToNullDB($reqRowId));

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