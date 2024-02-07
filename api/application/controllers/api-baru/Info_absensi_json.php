<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Info_absensi_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
    }
 
    // show data entitas
    function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base-new/Absensi');
        $this->load->model('base-new/Pegawai');
        $this->load->model('base-new/SatuanKerja');
        $this->load->model('base-new/PresensiRekap');


        $user_login_log= new UserLoginLog;
        
        $reqToken = $this->input->get("reqToken");
        $reqMode = $this->input->get("reqMode");
        $reqId= $this->input->get("reqId");
        $reqRowId= $this->input->get("reqRowId");

        $tanggalmulai= dateToPageCheck($this->input->get("tanggalmulai"));
        $tanggalakhir= dateToPageCheck($this->input->get("tanggalakhir"));

        $start= strtotime(getYear($tanggalmulai)."-".getMonth($tanggalmulai)."-01");
        $end= strtotime(getYear($tanggalakhir)."-".getMonth($tanggalakhir)."-01");

        //CEK PEGAWAI ID DARI TOKEN
        $user_login_log = new UserLoginLog();
        $reqPegawaiId = $user_login_log->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        // echo $user_login_log->query;exit();
        // echo $reqPegawaiId;exit();
        // $reqPegawaiId = '9014140KP';

        $reqPeriode= getMonth($tanggalmulai).getYear($tanggalmulai);
        $set= new PresensiRekap();
        $arrInfoLog= [];
        $set->selectpermohonanlog($reqPeriode, $reqPegawaiId);

        $vquery = $this->db->query("select pegawai_id, tanggal_info, jam, to_char(tanggal_info, 'ddmmyyyy') infohari from presensi.permohonan_log where to_char(tanggal_info, 'mmyyyy') = '".$reqPeriode."' and pegawai_id::numeric in (".$reqPegawaiId.") group by pegawai_id, tanggal_info, jam order by tanggal_info, jam ");
        foreach ($vquery->result() as $row)
        {
            $arrdata= [];
            $arrdata["KEY"]= $row->pegawai_id."-".$row->infohari;
            $arrdata["JAM"]= $row->jam;
            array_push($arrInfoLog, $arrdata);
        }


        if($reqPegawaiId == "")
        {
            $this->response(array('status' => 'fail', 'message' => 'Anda tidak berhak untuk mendapatkan info personal pegawai.', 'code' => 502));
        }
        else
        {
          
             $aColumns = array("PEGAWAI_ID", "NIP_BARU", "NAMA_LENGKAP", "TANGGAL", "HARI", "MASUK", "JAM_MASUK", "PULANG", "JAM_PULANG", "EX_MASUK", "TERLAMBAT", "PULANG_CEPAT","LOG_SEMUA");
            $statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
            $infotanggaldetil= "01".getMonth($tanggalmulai).getYear($tanggalmulai);
            $set= new Absensi();

           $set->selectByDataPegawai(array(), -1, -1, $infotanggaldetil, $statement, "ORDER BY A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.PANGKAT_RIWAYAT_TMT ASC");
            // echo $set->query;exit();
            // echo $set->errorMsg."---";exit();
            
            $total = 0;
            while($set->nextRow())
            {
               
               $infopegawaiid= $set->getField("PEGAWAI_ID");

             

                while($start <= $end)
                {
                    $infoperiode= date('mY', $start);
                    $infotanggalperiode=getTahunPeriode($infoperiode)."-".getBulanPeriode($infoperiode);
                    // echo $infoperiode;exit;

                    $setkoreksi= new Absensi();
                    $setkoreksi->selectByDataKoreksiPegawai(array(),-1,-1, $infoperiode, $infopegawaiid);
                    $setkoreksi->firstRow();
                    // echo $setkoreksi->query;exit;

                    $setrekap= new Absensi();
                    $setrekap->selectByDataRekapPegawai(array(),-1,-1, $infoperiode, $infopegawaiid);
                    $setrekap->firstRow();

                    if($c == "1")
                    {
                        echo $setkoreksi->query;exit;
                    }
                    // echo $setrekap->query;exit;

                    for($n=1; $n <= 31; $n++)
                    {
                        $infohari= generateZeroDate($n,2);
                        $infotanggal= $infotanggalperiode."-".$infohari;

                        $today_time= strtotime($infotanggal);
                        $expire_time= strtotime($tanggalakhir);
                        // echo $infotanggal."<br/>";
                        if($expire_time < $today_time)
                        {
                            // echo $today_time."--".$expire_time."--".$infotanggal."<br/>";//exit;
                            break;
                        }
                        else
                        {
                            $row = array();
                            for($i=0 ; $i<count($aColumns); $i++)
                            {
                                if(validateDate($infotanggal))
                                {
                                    $arrDataSemua=array();
                                    if($aColumns[$i] == "TANGGAL")
                                    {
                                        $row[trim($aColumns[$i])] = dateToPageCheck($infotanggal);
                                    }
                                    elseif($aColumns[$i] == "HARI")
                                    {
                                        $tgl = explode("-", $infotanggal);
                                        $row[trim($aColumns[$i])] = getNamaHari($tgl[2], $tgl[1], $tgl[0]);
                                    }
                                    elseif($aColumns[$i] == "MASUK" || $aColumns[$i] == "JAM_MASUK" || $aColumns[$i] == "PULANG" || $aColumns[$i] == "JAM_PULANG" || $aColumns[$i] == "EX_MASUK" || $aColumns[$i] == "TERLAMBAT" || $aColumns[$i] == "PULANG_CEPAT")
                                    {
                                        if($aColumns[$i] == "MASUK" || $aColumns[$i] == "PULANG" || $aColumns[$i] == "EX_MASUK")
                                            $infodata= $setkoreksi->getField(trim($aColumns[$i]."_".$n));
                                        else
                                            $infodata= $setrekap->getField(trim($aColumns[$i]."_".$n));


                                        $infoarray= trim($aColumns[$i]);
                                      
                                        if($infoarray == "EX_MASUK"){
                                            $infoarray= "ASK";
                                        }
                                        else if($infoarray == "JAM_MASUK"){
                                            $infodata= coalesce($infodata, "--:--");
                                            $infoarray= "LOG_MASUK";
                                        }
                                        else if($infoarray == "JAM_PULANG"){
                                            $infodata= coalesce($infodata, "--:--");
                                            $infoarray= "LOG_PULANG";
                                        } 

                                        $infodata= coalesce($infodata, "-");
                                        $row[$infoarray] = $infodata;

                                    } elseif($aColumns[$i] == "LOG_SEMUA")
                                    {
                                     

                                        $vinfolog= "";
                                        $infocarikey= $infopegawaiid."-".generateZero($n,2).$reqPeriode;
                                        $logcheck= in_array_column($infocarikey, "KEY", $arrInfoLog);

                                        if(!empty($logcheck))
                                        {
                                            $idx = 0;
                                            foreach ($logcheck as $vlog)
                                            {
                                                 // $vinfolog= getconcatseparator($vinfolog, $arrInfoLog[$vlog]["JAM"]);

                                                if ($idx == 0) {
                                                    $vinfolog = $arrInfoLog[$vlog]["JAM"];
                                                } else {
                                                    $vinfolog = $vinfolog.", ".$arrInfoLog[$vlog]["JAM"];
                                                }
                                                
                                                $idx++;
                                            }
                                        }
                                        
                                        $row[trim($aColumns[$i])]= $vinfolog;

                                    }
                                    else
                                    {
                                        $row[trim($aColumns[$i])] = $set->getField(trim($aColumns[$i]));
                                    }
                                }
                            }
                            
                            if(!empty($row))
                            {
                                $result[]= $row;
                            }
                        }
                        $total++;
                    }
                    $start = strtotime("+1 month", $start);
                }

               
            }
             // print_r($result);exit;
           
            
            if($total == 0)
            {
                for ( $i=0 ; $i<count($aColumns) ; $i++ )
                {
                    $row[trim($aColumns[$i])] = "";
                }
                $result[] = $row;
            }
            
            $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => $total,'result' => $result));
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