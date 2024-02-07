<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Penghargaan_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base-new/Penghargaan');

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
            $set = new Penghargaan;
            $aColumns = array("PENGHARGAAN_ID","PEGAWAI_ID","PEJABAT_PENETAP_ID","PEJABAT_PENETAP","NAMA","NAMA_NAMA","NO_SK","TANGGAL_SK","TAHUN","LAST_USER","LAST_DATE","LAST_LEVEL","STATUS","PEJABAT_PENETAP_NAMA","REF_PENGHARGAAN_ID","NAMA_DETIL","INFO_DETIL","JENJANG_PERINGKAT_ID"

                , "TEMP_VALIDASI_ID", "TEMP_VALIDASI_HAPUS_ID", "VALIDASI", "VALIDATOR", "PERUBAHAN_DATA", "TIPE_PERUBAHAN_DATA", "TANGGAL_VALIDASI");
            $statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
            // $set->selectByParams(array(), -1, -1, $statement);
            $set->selectByParamsPersonal(array(), -1, -1, $reqPegawaiId, $reqId, $reqRowId, $statement);
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

            $this->load->model('base-new/Penghargaan');

            $reqNamaPenghargaan= $this->input->post('reqNamaPenghargaan');
            $reqTahun= $this->input->post('reqTahun');
            $reqTglSK= $this->input->post('reqTglSK');
            $reqNoSK= $this->input->post('reqNoSK');
            $reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
            $reqPejabatPenetapId= $this->input->post("reqPejabatPenetapId");

            $reqRefPenghargaanId= $this->input->post("reqRefPenghargaanId");
            $reqNamaDetil= $this->input->post("reqNamaDetil");
            $reqJenjangPeringkatId= $this->input->post("reqJenjangPeringkatId");

            $set= new Penghargaan();
            $set->setField('PEJABAT_PENETAP_ID', ValToNullDB($reqPejabatPenetapId));    
            $set->setField('PEJABAT_PENETAP', $reqPejabatPenetap);  
            $set->setField('NAMA', $reqNamaPenghargaan);    
            $set->setField('NO_SK', $reqNoSK);  
            $set->setField('TANGGAL_SK', dateToDBCheck($reqTglSK)); 
            $set->setField('TAHUN', ValToNullDB($reqTahun));

            $set->setField('REF_PENGHARGAAN_ID', ValToNullDB($reqRefPenghargaanId));
            $set->setField('NAMA_DETIL', $reqNamaDetil);
            $set->setField('JENJANG_PERINGKAT_ID', ValToNullDB($reqJenjangPeringkatId)); 

            $set->setField('PENGHARGAAN_ID', ValToNullDB($reqRowId));

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