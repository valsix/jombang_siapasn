<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Gaji_riwayat_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base-new/Gajiriwayat');

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
            $set = new Gajiriwayat;
            $aColumns = array("GAJI_RIWAYAT_ID","PEGAWAI_ID","PEJABAT_PENETAP_ID","PEJABAT_PENETAP","PANGKAT_ID","NO_SK","TANGGAL_SK","TMT_SK","MASA_KERJA_TAHUN","MASA_KERJA_BULAN","GAJI_POKOK","JENIS_KENAIKAN","STATUS","LAST_USER","LAST_DATE","LAST_LEVEL","PANGKAT_KODE"," PANGKAT_NAMA","JENIS_KENAIKAN_NAMA","PEJABAT_PENETAP_NAMA", "TEMP_VALIDASI_ID", "TEMP_VALIDASI_HAPUS_ID", "VALIDASI", "VALIDATOR", "PERUBAHAN_DATA", "TIPE_PERUBAHAN_DATA", "TANGGAL_VALIDASI");
            $statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
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
            $this->load->model('base-new/PejabatPenetap');
            
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

            $this->load->model('base-new/Gajiriwayat');

            $reqNoSk= $this->input->post("reqNoSk");
            $reqTanggalSk= $this->input->post("reqTanggalSk");
            $reqGolRuang= $this->input->post("reqGolRuang");
            $reqTmtSk= $this->input->post("reqTmtSk");
            $reqTh= $this->input->post('reqTh');
            $reqTempTh= $this->input->post('reqTempTh');
            $reqBl= $this->input->post('reqBl');
            $reqTempBl= $this->input->post('reqTempBl');
            $reqPejabatPenetapId= $this->input->post('reqPejabatPenetapId');
            $reqPejabatPenetap= $this->input->post('reqPejabatPenetap');
            $reqGajiPokok= $this->input->post("reqGajiPokok");
            $reqJenis= $this->input->post("reqJenis");

            if($reqPejabatPenetapId == "")
            {
                $set_pejabat=new PejabatPenetap();
                $set_pejabat->setField('NAMA', strtoupper($reqPejabatPenetap));
                $set_pejabat->setField("LAST_USER", $this->LOGIN_USER);
                $set_pejabat->setField("USER_LOGIN_ID", $this->LOGIN_ID);
                $set_pejabat->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
                $set_pejabat->setField("LAST_DATE", "NOW()");
                $set_pejabat->insert();
                 // echo $set_pejabat->query;exit();
                $reqPejabatPenetapId=$set_pejabat->id;
                unset($set_pejabat);
            }

            $set= new Gajiriwayat();
            $set->setField('JENIS_KENAIKAN', $reqJenis);
            $set->setField('NO_SK', $reqNoSk);
            $set->setField('PANGKAT_ID', $reqGolRuang);
            $set->setField('TANGGAL_SK', dateToDBCheck($reqTanggalSk));
            $set->setField("GAJI_POKOK", ValToNullDB(dotToNo($reqGajiPokok)));
            $set->setField('MASA_KERJA_TAHUN', ValToNullDB($reqTh));
            $set->setField('MASA_KERJA_BULAN', ValToNullDB($reqBl));
            $set->setField('PEJABAT_PENETAP_ID', $reqPejabatPenetapId);
            $set->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
            $set->setField('TMT_SK', dateToDBCheck($reqTmtSk));
            $set->setField('BULAN_DIBAYAR', dateToDBCheck($reqTanggalSk));
            $set->setField('GAJI_RIWAYAT_ID', ValToNullDB($reqRowId));

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

            $query = $this->db->last_query();
            if(!empty($reqTempValidasiId))
            {
                $this->response(array('status' => 'success', 'message' => 'Data berhasil disimpan.', 'id' => $reqTempValidasiId));
            }
            else
            {
                $this->response(array('status' => 'fail', 'code' =>  502,'query'=>$query));
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