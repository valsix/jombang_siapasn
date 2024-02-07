<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Diklat_struktural_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
        $this->load->library('globalvalidasifilepegawai');
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base-new/DiklatStruktural');

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
            $set = new DiklatStruktural;
            $aColumns = array("DIKLAT_STRUKTURAL_ID","DIKLAT_ID","PEGAWAI_ID","TEMPAT","PENYELENGGARA","ANGKATAN","TAHUN","TANGGAL_MULAI","TANGGAL_SELESAI","NO_STTPP","TANGGAL_STTPP","JUMLAH_JAM","LAST_USER","LAST_DATE","LAST_LEVEL","STATUS","NAMA", "TEMP_VALIDASI_ID", "TEMP_VALIDASI_HAPUS_ID", "VALIDASI", "VALIDATOR", "PERUBAHAN_DATA", "TIPE_PERUBAHAN_DATA", "TANGGAL_VALIDASI","JABATAN_RIWAYAT_ID","JABATAN_RIWAYAT_NAMA","JABATAN_RIWAYAT_ESELON","JABATAN_RIWAYAT_SATKER","NILAI_REKAM_JEJAK");
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

            $cekfile= $this->input->post("cekfile");
            if($cekfile == "1")
            {
                $validasifilerequired= new globalvalidasifilepegawai();
                $vpost= $this->input->post("vpost");
                // print_r($vpost);//exit;
                $reqLinkFile= $this->input->post("reqLinkFile");
                // $reqLinkFile= $_FILES["reqLinkFile"];
                // print_r($reqLinkFile);exit;

                $vinforequired= $validasifilerequired->validasifilerequired($vpost, $reqLinkFile);
                if(!empty($vinforequired))
                {
                    $this->response(array('status' => 'cekfile', 'message' => $vinforequired));
                    exit;
                }
            }

            $this->load->model('base-new/DiklatStruktural');

            $reqDiklat= $this->input->post('reqDiklat');
            $reqTahun= $this->input->post('reqTahun');
            $reqNoSttpp= $this->input->post('reqNoSttpp');
            $reqPenyelenggara= $this->input->post('reqPenyelenggara');
            $reqAngkatan= $this->input->post('reqAngkatan');
            $reqTglMulai= $this->input->post('reqTglMulai');
            $reqTglSelesai= $this->input->post('reqTglSelesai');
            $reqTglSttpp= $this->input->post('reqTglSttpp');
            $reqTempat= $this->input->post('reqTempat');
            $reqJumlahJam= $this->input->post('reqJumlahJam');

            $reqJabatanRiwayatId= $this->input->post('reqJabatanRiwayatId');
            $reqRumpunJabatan= $this->input->post('reqRumpunJabatan');
            $reqNilaiKompentensi= $this->input->post('reqNilaiKompentensi');

            $set= new DiklatStruktural();
            $set->setField('DIKLAT_ID', ValToNullDB($reqDiklat));   
            $set->setField('TEMPAT', $reqTempat);
            $set->setField('PENYELENGGARA', $reqPenyelenggara);
            $set->setField('ANGKATAN', $reqAngkatan);
            $set->setField('TAHUN', getDay($reqTglSttpp));
            $set->setField('TANGGAL_MULAI', dateToDBCheck($reqTglMulai));
            $set->setField('TANGGAL_SELESAI', dateToDBCheck($reqTglSelesai));
            $set->setField('NO_STTPP', $reqNoSttpp);
            $set->setField('TANGGAL_STTPP', dateToDBCheck($reqTglSttpp));
            $set->setField("JUMLAH_JAM", ValToNullDB(dotToNo($reqJumlahJam)));
            $set->setField('DIKLAT_STRUKTURAL_ID', ValToNullDB($reqRowId));
            $set->setField("JUMLAH_JAM", ValToNullDB(dotToNo($reqJumlahJam)));
            $set->setField("JUMLAH_JAM", ValToNullDB(dotToNo($reqJumlahJam)));
            $set->setField("JUMLAH_JAM", ValToNullDB(dotToNo($reqJumlahJam)));
            $set->setField('DS_JABATAN_RIWAYAT_ID', ValToNullDB($reqJabatanRiwayatId));
            $set->setField('NILAI_REKAM_JEJAK', ValToNullDB($reqNilaiKompentensi));
            $set->setField('RUMPUN_ID', ValToNullDB($reqRumpunJabatan));
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