<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Saudara_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base-new/Saudara');

        $user_login_log= new UserLoginLog;
        
        $reqToken = $this->input->get("reqToken");
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
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
        }
        else
        {
            $set = new Saudara;
            $aColumns = array("NAMA","TEMPAT_LAHIR","TANGGAL_LAHIR","JENIS_KELAMIN","STATUS_SDR","STATUS_SDR_NAMA","STATUS_HIDUP","STATUS_HIDUP_NAMA","PEKERJAAN","LAST_DATE","LAST_USER","STATUS","SAUDARA_ID","PEGAWAI_ID"
                , "ALAMAT","KODEPOS","TELEPON"
                , "TEMP_VALIDASI_ID", "TEMP_VALIDASI_HAPUS_ID", "VALIDASI", "VALIDATOR", "PERUBAHAN_DATA", "TIPE_PERUBAHAN_DATA", "TANGGAL_VALIDASI"
            );
            $statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
            $set->selectByPersonal(array(), -1, -1, $reqPegawaiId, $reqId, $reqRowId, $statement);
            // $set->selectByParams(array(), -1, -1, $reqPegawaiId, $reqId, $reqRowId, $statement);
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

            $this->load->model('base-new/Saudara');

            $reqNama= $this->input->post("reqNama");
            $reqTmpLahir= $this->input->post("reqTmpLahir");
            $reqTglLahir= $this->input->post("reqTglLahir");
            $reqJenisKelamin= $this->input->post("reqJenisKelamin");
            $reqPekerjaan= $this->input->post("reqPekerjaan");
            $reqAlamat= $this->input->post("reqAlamat");
            $reqKodePos= $this->input->post("reqKodePos");
            $reqTelepon= $this->input->post("reqTelepon");
            $reqPropinsi= $this->input->post("reqPropinsi");
            $reqKabupaten= $this->input->post("reqKabupaten");
            $reqKecamatan= $this->input->post("reqKecamatan");
            $reqKelurahan= $this->input->post("reqKelurahan");
            $reqStatusHidup= $this->input->post("reqStatusHidup");
            $reqStatusSdr= $this->input->post("reqStatusSdr");

            $set= new Saudara();
            $set->setField('NAMA', $reqNama);
            $set->setField('TEMPAT_LAHIR', $reqTmpLahir);
            $set->setField("TANGGAL_LAHIR", dateToDBCheck($reqTglLahir));
            $set->setField('JENIS_KELAMIN', $reqJenisKelamin);
            $set->setField('PEKERJAAN', $reqPekerjaan);
            $set->setField('ALAMAT', $reqAlamat);
            $set->setField('KODEPOS', $reqKodePos);
            $set->setField('TELEPON', $reqTelepon);
            $set->setField('PROPINSI_ID', ValToNullDB($reqPropinsi));
            $set->setField('KABUPATEN_ID', ValToNullDB($reqKabupaten));
            $set->setField('KECAMATAN_ID', ValToNullDB($reqKecamatan));
            $set->setField('KELURAHAN_ID', ValToNullDB($reqKelurahan));
            $set->setField('STATUS_HIDUP', $reqStatusHidup);
            $set->setField('STATUS_SDR', $reqStatusSdr);

            $set->setField('SAUDARA_ID', ValToNullDB($reqRowId));
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

                // $this->response(array('status' => 'fail', 'code' =>  502, 'query' => $set->query));
                // exit();
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