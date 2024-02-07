<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Jabatan_riwayat_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base-new/JabatanRiwayat');

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
            $set = new JabatanRiwayat;
            $aColumns = array("JABATAN_RIWAYAT_ID","PEGAWAI_ID","PEJABAT_PENETAP_ID","PEJABAT_PENETAP","TIPE_PEGAWAI_ID","JABATAN_FU_ID","JABATAN_FT_ID","ESELON_ID","ESELON_NAMA","NO_SK","TANGGAL_SK","TMT_JABATAN","NAMA","NO_PELANTIKAN","TANGGAL_PELANTIKAN","TUNJANGAN","KREDIT","SATKER_ID","SATUAN_KERJA_NAMA_DETILbak","SATUAN_KERJA_NAMA_DETIL","JENIS_JABATAN_ID","JENIS_JABATAN_NAMA","IS_MANUAL","BULAN_DIBAYAR","TMT_BATAS_USIA_PENSIUN","STATUS","LAST_USER","LAST_DATE","LAST_LEVEL","DATA_HUKUMAN","TMT_ESELON", "TEMP_VALIDASI_ID", "TEMP_VALIDASI_HAPUS_ID", "VALIDASI", "VALIDATOR", "PERUBAHAN_DATA", "TIPE_PERUBAHAN_DATA", "TANGGAL_VALIDASI","TMT_SELESAI_JABATAN","LAMA_JABATAN_HITUNG","NILAI_REKAM_JEJAK_HITUNG","STATUS_SK_DASAR_JABATAN");

            $statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
            $set->selectByPersonal(array(), -1, -1, $reqPegawaiId, $reqId, $reqRowId, $statement);
              // echo $set->query;exit();
            $showSql = $this->db->last_query();
            

            $result = array();
            while($set->nextRow())
            {
            
                $row = array();
                for ( $i=0 ; $i<count($aColumns) ; $i++ )
                {
                    if( $aColumns[$i] == "TMT" )
                    {
                        $tgl = explode(" ", $set->getField(trim($aColumns[$i])));
                        $row[trim($aColumns[$i])] = getFormattedDate($tgl[0]);
                    }
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

            $this->load->model('base-new/JabatanRiwayat');
            $this->load->model('base-new/JabatanFu');
            $this->load->model('base-new/JabatanFt');

            $reqJenisJabatan= $this->input->post("reqJenisJabatan");
            $reqIsManual= $this->input->post("reqIsManual");
            $reqJabatanFuId= $this->input->post("reqJabatanFuId");
            $reqJabatanFtId= $this->input->post("reqJabatanFtId");
            $reqTipePegawaiId= $this->input->post("reqTipePegawaiId");
            $reqNoSk= $this->input->post("reqNoSk");
            $reqTglSk= $this->input->post("reqTglSk");
            $reqNama= $this->input->post("reqNama");
            $reqTmtJabatan= $this->input->post("reqTmtJabatan");
            $reqTmtWaktuJabatan= $this->input->post("reqTmtWaktuJabatan");
            $reqTmtEselon= $this->input->post("reqTmtEselon");
            $reqEselonId= $this->input->post("reqEselonId");
            $reqNama= $this->input->post("reqNama");
            $reqTmtEselon= $this->input->post("reqTmtEselon");
            $reqKeteranganBUP= $this->input->post("reqKeteranganBUP");
            $reqNoPelantikan= $this->input->post("reqNoPelantikan");    
            $reqTglPelantikan= $this->input->post("reqTglPelantikan");
            $reqTunjangan= $this->input->post("reqTunjangan");
            $reqBlnDibayar= $this->input->post("reqBlnDibayar");
            $reqSatkerId= $this->input->post("reqSatkerId");
            $reqSatker= $this->input->post("reqSatker");

            $reqKredit= $this->input->post("reqKredit");
            $reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
            $reqPejabatPenetapId= $this->input->post("reqPejabatPenetapId");

            if($reqJenisJabatan == 2)
            {
                $statement= " AND A.NAMA = '".$reqNama."'";
                $set_detil= new JabatanFu();
                $set_detil->selectByParams(array(), -1,-1, $statement);
                $set_detil->firstRow();
                $reqJabatanFuId= $set_detil->getField("JABATAN_FU_ID");
                unset($set_detil);

                if($reqJabatanFuId == "")
                {
                    echo "xxx-Nama Jabatan (".$reqNama.") tidak ada dalam sistem, hubungi admin untuk menambahkan data nama jabatan.";
                    exit();
                }
            }
            elseif($reqJenisJabatan == 3)
            {
                $statement= " AND A.NAMA = '".$reqNama."'";
                $set_detil= new JabatanFt();
                $set_detil->selectByParams(array(), -1,-1, $statement);
                $set_detil->firstRow();
                $reqJabatanFtId= $set_detil->getField("JABATAN_FT_ID");
                unset($set_detil);

                if($reqJabatanFtId == "")
                {
                    echo "xxx-Nama Jabatan (".$reqNama.") tidak ada dalam sistem, hubungi admin untuk menambahkan data nama jabatan.";
                    exit();
                }
            }

            if($reqPejabatPenetapId == "")
            {
                $set_pejabat=new PejabatPenetap();
                $set_pejabat->setField('NAMA', strtoupper($reqPejabatPenetap));
                $set_pejabat->setField("LAST_USER", $this->LOGIN_USER);
                $set_pejabat->setField("USER_LOGIN_ID", $this->LOGIN_ID);
                $set_pejabat->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
                $set_pejabat->setField("LAST_DATE", "NOW()");
                $set_pejabat->insert();
                $reqPejabatPenetapId=$set_pejabat->id;
                unset($set_pejabat);
            }

            $set= new JabatanRiwayat();
            $set->setField('TIPE_PEGAWAI_ID', $reqTipePegawaiId);
            $set->setField('PEJABAT_PENETAP_ID', $reqPejabatPenetapId);
            $set->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
            $set->setField('JABATAN_FU_ID', ValToNullDB($reqJabatanFuId));
            $set->setField('JABATAN_FT_ID', ValToNullDB($reqJabatanFtId));
            $set->setField('SATKER_ID', ValToNullDB($reqSatkerId));
            $set->setField('SATKER_NAMA', $reqSatker);
            $set->setField('IS_MANUAL', ValToNullDB($reqIsManual));
            $set->setField('NO_SK', $reqNoSk);
            $set->setField('ESELON_ID', ValToNullDB($reqEselonId));
            $set->setField('NAMA', $reqNama);
            $set->setField('NO_PELANTIKAN', $reqNoPelantikan);
            $set->setField("TUNJANGAN", ValToNullDB(dotToNo($reqTunjangan)));
            $set->setField('KREDIT', ValToNullDB(CommaToDot($reqKredit)));
            $set->setField('TMT_BATAS_USIA_PENSIUN', ValToNullDB($req));
            $set->setField('TMT_ESELON', dateToDBCheck($reqTmtEselon));
            $set->setField('TANGGAL_SK', dateToDBCheck($reqTglSk));
            if(strlen($reqTmtWaktuJabatan) == 5)
                $set->setField('TMT_JABATAN', dateTimeToDBCheck($reqTmtJabatan." ".$reqTmtWaktuJabatan));
            else
                $set->setField('TMT_JABATAN', dateToDBCheck($reqTmtJabatan));
            $set->setField('TANGGAL_PELANTIKAN', dateToDBCheck($reqTglPelantikan));
            $set->setField('BULAN_DIBAYAR', dateToDBCheck($reqBlnDibayar));
            $set->setField('KETERANGAN_BUP', $reqKeteranganBUP);
            $set->setField('JENIS_JABATAN_ID', $reqJenisJabatan);
            $set->setField('JABATAN_RIWAYAT_ID', ValToNullDB($reqRowId));

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

            $sqlQuery = $this->db->last_query();

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