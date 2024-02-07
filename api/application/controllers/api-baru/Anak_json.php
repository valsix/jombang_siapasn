<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Anak_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base-new/Anak');

        $user_login_log= new UserLoginLog;
        
        $reqToken = $this->input->get("reqToken");
        $reqMode = $this->input->get("reqMode");
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
            $this->response(array('status' => 'fail', 'message' => 'Anda tidak berhak untuk mendapatkan info personal pegawai.', 'code' => 502));
        }
        else
        {
            $set = new Anak;
            $aColumns = array("ANAK_ID", "PEGAWAI_ID", "SUAMI_ISTRI_ID", "SUAMI_ISTRI_NAMA"," PENDIDIKAN_ID", "NAMA", "NOMOR_INDUK", "TEMPAT_LAHIR", "TANGGAL_LAHIR", "JENIS_KELAMIN", "STATUS_KELUARGA", "STATUS_TUNJANGAN", "PEKERJAAN", "AWAL_BAYAR", "AKHIR_BAYAR", "STATUS_NIKAH", "STATUS_BEKERJA", "STATUS", "STATUS_AKTIF", "LAST_USER", "LAST_DATE", "LAST_LEVEL", "STATUS_KELUARGA", "STATUS_AKTIF", "TANGGAL_MENINGGAL", "PENSIUN_ANAK_ID","PENDIDIKAN_NAMA", "TEMP_VALIDASI_ID", "TEMP_VALIDASI_HAPUS_ID", "VALIDASI", "VALIDATOR", "PERUBAHAN_DATA", "TIPE_PERUBAHAN_DATA", "TANGGAL_VALIDASI");
            $statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
            // $statement= " AND A.PEGAWAI_ID = 8300";
            // $set->selectByParams(array(), -1, -1, $statement);
            $set->selectByParamsPersonal(array(), -1, -1, $reqPegawaiId, $reqId, $reqRowId, $statement);
            // echo $set->query;exit();
            // echo $set->errorMsg."---";exit();
            
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

            $this->load->model('base-new/Anak');

            $reqNama= $this->input->post("reqNama");
            $reqStatusKeluarga= $this->input->post("reqStatusKeluarga");
            $reqSuamiIstriId= $this->input->post("reqSuamiIstriId");
            $reqTempatLahir= $this->input->post("reqTempatLahir");
            $reqTanggalLahir= $this->input->post("reqTanggalLahir");
            $reqJenisKelamin= $this->input->post("reqJenisKelamin");
            $reqNoInduk= $this->input->post("reqNoInduk");
            $reqPendidikanId= $this->input->post("reqPendidikanId");
            $reqPekerjaan= $this->input->post("reqPekerjaan");
            $reqDapatTunjangan= $this->input->post("reqDapatTunjangan");
            $reqAwalBayar= $this->input->post("reqAwalBayar");
            $reqAkhirBayar= $this->input->post("reqAkhirBayar");
            $reqStatusAktif= $this->input->post("reqStatusAktif");
            $reqTanggalMeninggal= $this->input->post("reqTanggalMeninggal");
            $reqStatusNikah= $this->input->post("reqStatusNikah");
            $reqStatusBekerja= $this->input->post("reqStatusBekerja");

            $set= new Anak();
            $set->setField('SUAMI_ISTRI_ID', ValToNullDB($reqSuamiIstriId));
            $set->setField('PENDIDIKAN_ID', ValToNullDB($reqPendidikanId));
            $set->setField("NAMA", $reqNama);
            $set->setField('NOMOR_INDUK', $reqNoInduk);
            $set->setField('TEMPAT_LAHIR', $reqTempatLahir);
            $set->setField('TANGGAL_LAHIR', dateToDBCheck($reqTanggalLahir));
            $set->setField('JENIS_KELAMIN', $reqJenisKelamin);
            $set->setField('STATUS_KELUARGA', ValToNullDB($reqStatusKeluarga));
            $set->setField('STATUS_TUNJANGAN', ValToNullDB($reqDapatTunjangan));
            $set->setField('PEKERJAAN', $reqPekerjaan);
            $set->setField('AWAL_BAYAR', dateToDBCheck($reqAwalBayar));
            $set->setField('AKHIR_BAYAR', dateToDBCheck($reqAkhirBayar));
            $set->setField('TANGGAL_MENINGGAL', dateToDBCheck($reqTanggalMeninggal));
            $set->setField('STATUS_AKTIF', $reqStatusAktif);
            $set->setField('STATUS_NIKAH', ValToNullDB($reqStatusNikah));
            $set->setField('STATUS_BEKERJA', ValToNullDB($reqStatusBekerja));
            $set->setField('ANAK_ID', ValToNullDB($reqRowId));

          
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