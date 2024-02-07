<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Sk_cpns_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base-new/Skcpns');

        $user_login_log= new UserLoginLog;
        
        $reqToken= $this->input->get("reqToken");
        $reqRowId= $this->input->get("reqRowId");

        //CEK PEGAWAI ID DARI TOKEN
        $user_login_log = new UserLoginLog();
        $reqPegawaiId = $user_login_log->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        // echo $user_login_log->query;exit();
        // echo $reqPegawaiId;exit();

        if($reqPegawaiId == "")
        {
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
        }
        else
        {
            $set = new Skcpns;
            $aColumns = array("SK_CPNS_ID","PEGAWAI_ID","PEJABAT_PENETAP_ID","PEJABAT_PENETAP","PANGKAT_ID","NO_NOTA","TANGGAL_NOTA","NO_SK","TANGGAL_SK","TMT_CPNS","TANGGAL_TUGAS","NO_STTPP","TANGGAL_STTPP","NAMA_PENETAP","NIP_PENETAP","MASA_KERJA_TAHUN","MASA_KERJA_BULAN","GAJI_POKOK","NO_PERSETUJUAN_NIP","TANGGAL_PERSETUJUAN_NIP","PENDIDIKAN_RIWAYAT_ID","PENDIDIKAN_NAMA","PENDIDIKAN_JURUSAN_NAMA","PANGKAT_KODE","PANGKAT_NAMA","NAMA PEJABAT_PENETAP_NAMA","FORMASI_CPNS_ID","JABATAN_TUGAS", "JENIS_FORMASI_TUGAS_ID", "JABATAN_FU_ID", "JABATAN_FT_ID", "STATUS_SK_CPNS", "SPMT_NOMOR", "SPMT_TANGGAL", "SPMT_TMT"
                , "TEMP_VALIDASI_ID", "TEMP_VALIDASI_HAPUS_ID", "VALIDASI", "VALIDATOR", "PERUBAHAN_DATA", "TIPE_PERUBAHAN_DATA", "TANGGAL_VALIDASI"
                );
            
            $statement= "";
            $set->selectByPersonal(array(), -1, -1, $reqPegawaiId, $reqRowId, $statement);
            // echo $set->query;exit();
            
            $total = 0;
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

            $this->load->model('base-new/Skcpns');

            $reqNoNotaBakn= $this->input->post("reqNoNotaBakn");
            $reqTanggalNotaBakn= $this->input->post("reqTanggalNotaBakn");
            $reqPejabatPenetapId= $this->input->post("reqPejabatPenetapId");
            $reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
            $reqNamaPejabatPenetap= $this->input->post("reqNamaPejabatPenetap");
            $reqNipPejabatPenetap= $this->input->post("reqNipPejabatPenetap");
            $reqNoSuratKeputusan= $this->input->post("reqNoSuratKeputusan");
            $reqTanggalSuratKeputusan= $this->input->post("reqTanggalSuratKeputusan");
            $reqTerhitungMulaiTanggal= $this->input->post("reqTerhitungMulaiTanggal");
            $reqGolRuang= $this->input->post("reqGolRuang");
            $reqTanggalTugas= $this->input->post("reqTanggalTugas");
            $reqSkcpnsId= $this->input->post("reqSkcpnsId");
            $reqTh= $this->input->post("reqTh");
            $reqBl= $this->input->post("reqBl");
            $reqNoPersetujuanNip= $this->input->post("reqNoPersetujuanNip");
            $reqTanggalPersetujuanNip= $this->input->post("reqTanggalPersetujuanNip");
            $reqPendidikan= $this->input->post("reqPendidikan");
            $reqJurusan= $this->input->post("reqJurusan");
            $reqGajiPokok= $this->input->post("reqGajiPokok");
            $reqFormasiCpnsId= $this->input->post("reqFormasiCpnsId");
            $reqJabatanTugas= $this->input->post("reqJabatanTugas");

            $reqJenisFormasiTugasId= $this->input->post("reqJenisFormasiTugasId");
            $reqJabatanFuId= $this->input->post("reqJabatanFuId");
            $reqJabatanFtId= $this->input->post("reqJabatanFtId");
            $reqStatusSkCpns= $this->input->post("reqStatusSkCpns");
            $reqSpmtNomor= $this->input->post("reqSpmtNomor");
            $reqSpmtTanggal= $this->input->post("reqSpmtTanggal");
            $reqSpmtTmt= $this->input->post("reqSpmtTmt");

            $set= new Skcpns();
            $set->setField("JENIS_FORMASI_TUGAS_ID", ValToNullDB($reqJenisFormasiTugasId));
            $set->setField("JABATAN_FU_ID", ValToNullDB($reqJabatanFuId));
            $set->setField("JABATAN_FT_ID", ValToNullDB($reqJabatanFtId));
            $set->setField("STATUS_SK_CPNS", ValToNullDB($reqStatusSkCpns));
            $set->setField("SPMT_NOMOR", $reqSpmtNomor);
            $set->setField("SPMT_TANGGAL", dateToDBCheck($reqSpmtTanggal));
            $set->setField("SPMT_TMT", dateToDBCheck($reqSpmtTmt));

            //kalau pejabat tidak ada
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

            $set->setField('PANGKAT_ID', $reqGolRuang);
            // $set->setField('PEJABAT_PENETAP_ID', ValToNullDB($reqPejabatPenetapId)); 
            $set->setField('PEJABAT_PENETAP_ID', $reqPejabatPenetapId);
            $set->setField('PEJABAT_PENETAP', $reqPejabatPenetap);
            $set->setField('TMT_CPNS', dateToDBCheck($reqTerhitungMulaiTanggal));
            $set->setField('TANGGAL_TUGAS', dateToDBCheck($reqTanggalTugas)); 
            // $set->setField('NO_STTPP', '');
            $set->setField('NO_NOTA', $reqNoNotaBakn);
            $set->setField('TANGGAL_NOTA', dateToDBCheck($reqTanggalNotaBakn));
            $set->setField('NO_SK', $reqNoSuratKeputusan);
            $set->setField('TANGGAL_STTPP', dateToDBCheck($reqTanggalTugas));
            $set->setField('NAMA_PENETAP', $reqNamaPejabatPenetap);
            $set->setField('TANGGAL_SK', dateToDBCheck($reqTanggalSuratKeputusan));
            $set->setField('NIP_PENETAP', $reqNipPejabatPenetap);
            $set->setField('TANGGAL_UPDATE',$reqTan);
            $set->setField('MASA_KERJA_TAHUN', ValToNullDB($reqTh));
            $set->setField('MASA_KERJA_BULAN', ValToNullDB($reqBl));
            $set->setField("GAJI_POKOK", ValToNullDB(dotToNo($reqGajiPokok)));
            $set->setField("TANGGAL_PERSETUJUAN_NIP", dateToDBCheck($reqTanggalPersetujuanNip));
            $set->setField("NO_PERSETUJUAN_NIP", $reqNoPersetujuanNip);
            $set->setField("PENDIDIKAN_ID", ValToNullDB($reqPendidikan));
            $set->setField("JURUSAN", $reqJurusan);
            $set->setField("FORMASI_CPNS_ID", ValToNullDB($reqFormasiCpnsId));
            $set->setField("JABATAN_TUGAS", $reqJabatanTugas);
            $set->setField('SK_CPNS_ID',$reqRowId);

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