<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Jabatan_tambahan_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base-new/Jabatantambahan');

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
            $set = new Jabatantambahan;
            $aColumns = array("JABATAN_TAMBAHAN_ID","PEGAWAI_ID","PEJABAT_PENETAP_ID","PEJABAT_PENETAP","NO_SK","TANGGAL_SK","TMT_JABATAN","TMT_JABATAN_AKHIR","NO_PELANTIKAN","TANGGAL_PELANTIKAN","TUNJANGAN","BULAN_DIBAYAR","NAMA","TUGAS_TAMBAHAN_ID","IS_MANUAL","SATKER_NAMA","SATKER_ID","STATUS_PLT","STATUS","LAST_USER","LAST_DATE","LAST_LEVEL", "TEMP_VALIDASI_ID", "TEMP_VALIDASI_HAPUS_ID", "VALIDASI", "VALIDATOR", "PERUBAHAN_DATA", "TIPE_PERUBAHAN_DATA", "TANGGAL_VALIDASI");
            $statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
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

            $this->load->model('base/JabatanTambahan');

            $reqStatusPlt= $this->input->post("reqStatusPlt");
            $reqIsManual= $this->input->post("reqIsManual");
            $reqTugasTambahanId= $this->input->post("reqTugasTambahanId");
            $reqTmtTugas= $this->input->post("reqTmtTugas");
            $reqTmtWaktuTugas= $this->input->post("reqTmtWaktuTugas");
            $reqNamaTugas= $this->input->post("reqNamaTugas");
            $reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
            $reqPejabatPenetapId= $this->input->post("reqPejabatPenetapId");
            $reqNoSk= $this->input->post("reqNoSk");
            $reqTanggalSk= $this->input->post("reqTanggalSk");
            $reqTmtJabatan= $this->input->post("reqTmtJabatan");
            $reqTmtJabatanAkhir= $this->input->post("reqTmtJabatanAkhir");
            $reqSatker= $this->input->post("reqSatker");
            $reqSatkerId= $this->input->post("reqSatkerId");
            $reqNoPelantikan= $this->input->post("reqNoPelantikan");
            $reqTanggalPelantikan= $this->input->post("reqTanggalPelantikan");
            $reqTunjangan= $this->input->post("reqTunjangan");
            $reqBulanDibayar= $this->input->post("reqBulanDibayar");


            $set= new JabatanTambahan();
            $set->setField('NO_PELANTIKAN', $reqNoPelantikan);
            $set->setField('TANGGAL_PELANTIKAN', dateToDBCheck($reqTanggalPelantikan));
            $set->setField("TUNJANGAN", ValToNullDB(dotToNo($reqTunjangan)));
            $set->setField('BULAN_DIBAYAR', dateToDBCheck($reqBulanDibayar));
            $set->setField('NAMA', $reqNamaTugas);
            $set->setField('PEJABAT_PENETAP_ID', $reqPejabatPenetapId);
            $set->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
            $set->setField('TUGAS_TAMBAHAN_ID', ValToNullDB($reqTugasTambahanId));
            $set->setField('NO_SK', $reqNoSk);
            $set->setField('TANGGAL_SK', dateToDBCheck($reqTanggalSk));

            $set->setField('SATKER_ID', ValToNullDB($reqSatkerId));
            $set->setField('SATKER_NAMA', $reqSatker);
            $set->setField('IS_MANUAL', ValToNullDB($reqIsManual));
            $set->setField('STATUS_PLT', $reqStatusPlt);

            if(strlen($reqTmtWaktuTugas) == 5)
                $set->setField('TMT_JABATAN', dateTimeToDBCheck($reqTmtTugas." ".$reqTmtWaktuTugas));
            else
                $set->setField('TMT_JABATAN', dateToDBCheck($reqTmtTugas));
            $set->setField('TMT_JABATAN_AKHIR', dateToDBCheck($reqTmtJabatanAkhir));

            $set->setField('JABATAN_TAMBAHAN_ID', ValToNullDB($reqRowId));

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