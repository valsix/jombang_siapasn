<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Orang_tua_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base-new/Orangtua');

        $user_login_log= new UserLoginLog;
        
        $reqToken = $this->input->get("reqToken");
        $reqJenisKelamin = $this->input->get("reqJenisKelamin");
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
            $set = new Orangtua;
            $aColumns = array("ORANG_TUA_ID","PEGAWAI_ID","JENIS_KELAMIN","NAMA","TEMPAT_LAHIR","TANGGAL_LAHIR","PEKERJAAN","ALAMAT","KODEPOS","TELEPON","LAST_USER"," LAST_DATE"," LAST_LEVEL"," PROPINSI_ID","PROPINSI_NAMA"," KABUPATEN_ID","KABUPATEN_NAMA"," KECAMATAN_ID","KECAMATAN_NAMA"," KELURAHAN_ID DESA_ID","DESA_NAMA", "TEMP_VALIDASI_ID", "TEMP_VALIDASI_HAPUS_ID", "VALIDASI", "VALIDATOR", "PERUBAHAN_DATA", "TIPE_PERUBAHAN_DATA", "TANGGAL_VALIDASI");
            $statement= " AND PEGAWAI_ID = ".$reqPegawaiId;
            if($reqJenisKelamin == ""){}
            else
                $statement.= " AND JENIS_KELAMIN = '".$reqJenisKelamin."'";

            $set->selectByPersonal(array(), -1, -1, $reqPegawaiId, $reqRowId, $statement);
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

            $this->load->model('base-new/OrangTua');

            $reqIdAyah = $this->input->post("reqIdAyah");
            $reqIdIbu = $this->input->post("reqIdIbu");
            $reqTempValidasiIdAyah= $this->input->post("reqTempValidasiIdAyah");
            $reqTempValidasiIdIbu= $this->input->post("reqTempValidasiIdIbu");

            $reqNamaAyah = $this->input->post("reqNamaAyah");
            $reqNamaIbu = $this->input->post("reqNamaIbu");
            $reqTmptLahirAyah = $this->input->post("reqTmptLahirAyah");
            $reqTmptLahirIbu = $this->input->post("reqTmptLahirIbu");
            $reqTglLahirAyah = $this->input->post("reqTglLahirAyah");
            $reqTglLahirIbu = $this->input->post("reqTglLahirIbu");
            $reqPekerjaanAyah = $this->input->post("reqPekerjaanAyah");
            $reqPekerjaanIbu = $this->input->post("reqPekerjaanIbu");
            $reqAlamatAyah = $this->input->post("reqAlamatAyah");
            $reqAlamatIbu = $this->input->post("reqAlamatIbu");
            $reqPropinsiAyahId = $this->input->post("reqPropinsiAyahId");
            $reqPropinsiIbuId = $this->input->post("reqPropinsiIbuId");
            $reqKabupatenAyahId = $this->input->post("reqKabupatenAyahId");
            $reqKabupatenIbuId = $this->input->post("reqKabupatenIbuId");
            $reqKecamatanAyahId = $this->input->post("reqKecamatanAyahId");
            $reqKecamatanIbuId = $this->input->post("reqKecamatanIbuId");
            $reqDesaAyahId = $this->input->post("reqDesaAyahId");
            $reqDesaIbuId = $this->input->post("reqDesaIbuId");
            $reqKodePosAyah = $this->input->post("reqKodePosAyah");
            $reqKodePosIbu = $this->input->post("reqKodePosIbu");
            $reqTeleponAyah = $this->input->post("reqTeleponAyah");
            $reqTeleponIbu = $this->input->post("reqTeleponIbu");
            $reqStatusAktifAyah = $this->input->post("reqStatusAktifAyah");
            $reqStatusAktifIbu = $this->input->post("reqStatusAktifIbu");
            $reqJenisKelaminAyah = $this->input->post("reqJenisKelaminAyah");
            $reqJenisKelaminIbu = $this->input->post("reqJenisKelaminIbu");

            $set= new OrangTua();
            $set->setField("NAMA", setQuote($reqNamaAyah, '1'));
            $set->setField("TEMPAT_LAHIR", $reqTmptLahirAyah);
            $set->setField("TANGGAL_LAHIR", dateToDBCheck($reqTglLahirAyah));
            $set->setField("PEKERJAAN", $reqPekerjaanAyah);
            $set->setField("ALAMAT", $reqAlamatAyah);
            $set->setField("KODEPOS", $reqKodePosAyah);
            $set->setField("TELEPON", $reqTeleponAyah);
            $set->setField("PROPINSI_ID", ValToNullDB($reqPropinsiAyahId));
            $set->setField("KABUPATEN_ID", ValToNullDB($reqKabupatenAyahId));
            $set->setField("KECAMATAN_ID", ValToNullDB($reqKecamatanAyahId));
            $set->setField("KELURAHAN_ID", ValToNullDB($reqDesaAyahId));
            $set->setField('ORANG_TUA_ID', ValToNullDB($reqIdAyah));

            $set->setField('PEGAWAI_ID', ValToNullDB($reqPegawaiId));
            $set->setField("LAST_LEVEL", $lastlevel);
            $set->setField("LAST_USER", $lastuser);
            $set->setField("USER_LOGIN_ID", ValToNullDB($lastloginid));
            $set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($lastloginpegawaiid));
            $set->setField("LAST_DATE", "NOW()");

            $cek = 0;

            if(empty($reqTempValidasiIdAyah) && $reqIdAyah==0)
            {
                $set->setField("JENIS_KELAMIN", 'L');
                if($set->insert())
                {
                    $reqsimpan= "1";
                    $reqTempValidasiIdAyah= $set->id;
                    $cek = 1;
                }
            }
            else if(empty($reqTempValidasiIdAyah) && $reqIdAyah)
            {
                $set->setField("JENIS_KELAMIN", 'L');
                if($set->insert())
                {
                    $reqsimpan= "1";
                    $reqTempValidasiIdAyah= $set->id;
                    $cek = 1;
                }
            }
            if(!empty($reqTempValidasiIdAyah))
            {
                $set->setField("JENIS_KELAMIN", 'L');
                $set->setField('TEMP_VALIDASI_ID', $reqTempValidasiIdAyah);

                if($set->update())
                {
                    $reqsimpan= "1";
                    $cek = 1;
                }
            }
            $reqTempValidasiId=$reqTempValidasiIdAyah;

            if ($cek == 1) 
            {
                $set->setField('NAMA', setQuote($reqNamaIbu, '1'));
                $set->setField('TEMPAT_LAHIR', $reqTmptLahirIbu);
                $set->setField('TANGGAL_LAHIR', dateToDBCheck($reqTglLahirIbu));
                $set->setField('PEKERJAAN', $reqPekerjaanIbu);
                $set->setField('ALAMAT', $reqAlamatIbu);
                $set->setField('KODEPOS', $reqKodePosIbu);
                $set->setField('TELEPON', $reqTeleponIbu);
                $set->setField('PROPINSI_ID', ValToNullDB($reqPropinsiIbuId));
                $set->setField('KABUPATEN_ID', ValToNullDB($reqKabupatenIbuId));
                $set->setField('KECAMATAN_ID', ValToNullDB($reqKecamatanIbuId));
                $set->setField('KELURAHAN_ID', ValToNullDB($reqDesaIbuId));
                // $set->setField('ORANG_TUA_ID', ValToNullDB($reqRowId));
                $set->setField('ORANG_TUA_ID', ValToNullDB($reqIdIbu));

                $set->setField('PEGAWAI_ID', ValToNullDB($reqPegawaiId));
                $set->setField("LAST_LEVEL", $lastlevel);
                $set->setField("LAST_USER", $lastuser);
                $set->setField("USER_LOGIN_ID", ValToNullDB($lastloginid));
                $set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($lastloginpegawaiid));
                $set->setField("LAST_DATE", "NOW()");

                if(empty($reqTempValidasiIdIbu) && $reqIdIbu==0 )
                {
                    $set->setField("JENIS_KELAMIN", 'P');
                    if(!empty($reqTempValidasiIdIbu))
                    {
                        if($set->insert())
                        {
                            $reqsimpan= "1";
                            $reqTempValidasiIdIbu= $set->id;
                        }
                    }

                }
                else if(empty($reqTempValidasiIdIbu) && $reqIdIbu)
                {
                    $set->setField("JENIS_KELAMIN", 'P');
                    if($set->insert())
                    {
                        $reqsimpan= "1";
                        $reqTempValidasiIdIbu= $set->id;
                        $cek = 1;
                    }
                }
                
                if(!empty($reqTempValidasiIdIbu))
                {
                    $set->setField("JENIS_KELAMIN", "P");
                    $set->setField('TEMP_VALIDASI_ID', $reqTempValidasiIdIbu);
                    if($set->update())
                    {
                        $reqsimpan= "1";
                    }
                }
                $reqTempValidasiId=$reqTempValidasiIdIbu;
            } 
            else 
            {
                $reqTempValidasiId= "";
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