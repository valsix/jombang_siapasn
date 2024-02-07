<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Pegawai_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        // $this->db->query("alter session set nls_date_format='DD-MM-YYYY'"); 
        
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base-new/Pegawai');
        $this->load->model('base/SatuanKerja');

        $user_login_log= new UserLoginLog;
        
        $reqToken = $this->input->get("reqToken");
        $reqMode = $this->input->get("reqMode");

        //CEK PEGAWAI ID DARI TOKEN
        $user_login_log = new UserLoginLog();
        $reqPegawaiId = $user_login_log->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
		// echo $user_login_log->query;exit();
        $reqSatuanKerjaId = $user_login_log->getTokenSatuanKerjaId(array("TOKEN" => $reqToken, "STATUS" => '1'));

        
        // echo $reqPegawaiId;exit();
        // $reqPegawaiId = '9014140KP';
        // echo $reqMode;exit();
        // echo $reqSatuanKerjaId;exit();

        if($reqMode == "satuankerja")
        {

            if($reqSatuanKerjaId == "")
            {
                $this->response(array('status' => 'fail', 'message' => 'Anda tidak berhak untuk mendapatkan info personal pegawai.', 'code' => 502));
                exit();
            }

            // echo $reqSatuanKerjaId;exit;
            if($reqSatuanKerjaId == "-1")
            {
                $reqSatuanKerjaId= "";
            }
            else
            {
                $skerja= new SatuanKerja();
                $reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
                unset($skerja);
                $statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
            }
            // echo $statement;exit;

            $statementsatuankerja= " 
            AND EXISTS
            (
                SELECT 1
                FROM
                (
                    SELECT PEGAWAI_ID FROM PEGAWAI A WHERE A.STATUS_PEGAWAI_ID IN (1,2) 
                    ".$statement."
                ) X WHERE 1=1 AND A.PEGAWAI_ID = X.PEGAWAI_ID
            )
            ";
             // echo $statementsatuankerja;exit();

            $set = new Pegawai;
            $aColumns = array("PEGAWAI_ID", "PEGAWAI_ID_SAPK", "STATUS", "SATUAN_KERJA_ID", "JABATAN_RIWAYAT_ID", "PENDIDIKAN_RIWAYAT_ID","GAJI_RIWAYAT_ID","PANGKAT_RIWAYAT_ID","JENIS_PEGAWAI_ID","KEDUDUKAN","TIPE_PEGAWAI_ID","PEGAWAI_STATUS_ID","PEGAWAI_STATUS_NAMA","PEGAWAI_KEDUDUKAN_TMT","PEGAWAI_KEDUDUKAN_NAMA","STATUS_PEGAWAI_ID","NIP_LAMA","NIP_BARU","NAMA","GELAR_DEPAN", "GELAR_BELAKANG", "TEMPAT_LAHIR", "TANGGAL_LAHIR", "BPJS","BPJS_TANGGAL","NPWP_TANGGAL","ALAMAT_KETERANGAN","EMAIL_KANTOR","REKENING_NAMA","GAJI_POKOK",
                "TUNJANGAN_KELUARGA","TUNJANGAN","GAJI_BERSIH",
                "JENIS_KELAMIN", "STATUS_KAWIN", "SUKU_BANGSA", "GOLONGAN_DARAH", "EMAIL", "ALAMAT", "RT", "RW", "KODEPOS", "TELEPON", "HP", 
                "TELEPON_KANTOR", "FACEBOOK", "TWITTER", "WHATSAPP", "TELEGRAM", "KARTU_PEGAWAI","ASKES", "TASPEN","NPWP", "NIK", "NO_REKENING","SK_KONVERSI_NIP", "NO_URUT", "NO_KK", "NO_RAK_BERKAS", 
                "BANK_ID", "AGAMA_ID"
                , "KETERANGAN_1", "KETERANGAN_2", "LAST_USER", "LAST_DATE", "JENIS_PENGADAAN_ID","SATUAN_KERJA_NAMA_DETIL","NAMA_LENGKAP","PROPINSI_ID","PROPINSI_NAMA","KABUPATEN_ID","KABUPATEN_NAMA","KECAMATAN_ID","KECAMATAN_NAMA","DESA_ID","DESA_NAMA","TEMP_VALIDASI_ID", "TEMP_VALIDASI_HAPUS_ID", "VALIDASI", "VALIDATOR", "PERUBAHAN_DATA", "TIPE_PERUBAHAN_DATA", "TANGGAL_VALIDASI" );
            $statement= $statementsatuankerja;
            $set->selectByPersonal(array(), 2, 1, $statement);


             // echo $set->query;exit();
             // echo $statement;exit();

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
        else
        {

            if($reqPegawaiId == "")
            {
                $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
            }
            else
            {
                $set = new Pegawai;
                $aColumns = array("PEGAWAI_ID", "PEGAWAI_ID_SAPK", "STATUS", "SATUAN_KERJA_ID", "JABATAN_RIWAYAT_ID", "PENDIDIKAN_RIWAYAT_ID","GAJI_RIWAYAT_ID","PANGKAT_RIWAYAT_ID","JENIS_PEGAWAI_ID","KEDUDUKAN","TIPE_PEGAWAI_ID","PEGAWAI_STATUS_ID","PEGAWAI_STATUS_NAMA","PEGAWAI_KEDUDUKAN_TMT","PEGAWAI_KEDUDUKAN_NAMA","STATUS_PEGAWAI_ID","NIP_LAMA","NIP_BARU","NAMA","GELAR_DEPAN", "GELAR_BELAKANG", "TEMPAT_LAHIR", "TANGGAL_LAHIR", "BPJS","BPJS_TANGGAL","NPWP_TANGGAL","ALAMAT_KETERANGAN","EMAIL_KANTOR","REKENING_NAMA","GAJI_POKOK",
                    "TUNJANGAN_KELUARGA","TUNJANGAN","GAJI_BERSIH",
                    "JENIS_KELAMIN", "STATUS_KAWIN", "SUKU_BANGSA", "GOLONGAN_DARAH", "EMAIL", "ALAMAT", "RT", "RW", "KODEPOS", "TELEPON", "HP", 
                    "TELEPON_KANTOR", "FACEBOOK", "TWITTER", "WHATSAPP", "TELEGRAM", "KARTU_PEGAWAI","ASKES", "TASPEN","NPWP", "NIK", "NO_REKENING","SK_KONVERSI_NIP", "NO_URUT", "NO_KK", "NO_RAK_BERKAS", 
                    "BANK_ID", "AGAMA_ID"
                    , "KETERANGAN_1", "KETERANGAN_2", "LAST_USER", "LAST_DATE", "JENIS_PENGADAAN_ID","SATUAN_KERJA_NAMA_DETIL","NAMA_LENGKAP","PROPINSI_ID","PROPINSI_NAMA","KABUPATEN_ID","KABUPATEN_NAMA","KECAMATAN_ID","KECAMATAN_NAMA","DESA_ID","DESA_NAMA","TEMP_VALIDASI_ID", "TEMP_VALIDASI_HAPUS_ID", "VALIDASI", "VALIDATOR", "PERUBAHAN_DATA", "TIPE_PERUBAHAN_DATA", "TANGGAL_VALIDASI", "PATH", "BARCODE" );
                $statement= " AND A.PEGAWAI_ID = '".$reqPegawaiId."'";
                            
                $set->selectByPersonal(array(), -1, -1,$reqPegawaiId ,'','',$statement);
                  // echo $set->query;exit();
                $set->firstRow();
                
                $result = array();
                $row = array();
                for ( $i=0 ; $i<count($aColumns) ; $i++ )
                {
                    if($aColumns[$i] == "TMT")
                        $row[trim($aColumns[$i])] = getFormattedDateView($set->getField(trim($aColumns[$i])));
                    elseif($aColumns[$i] == "PATH")
                        $row[trim($aColumns[$i])] = $set->getField("PATH")."?random=".rand();
                    elseif($aColumns[$i] == "BARCODE")
                        $row[trim($aColumns[$i])] = "uploads/qr/".$set->getField("NIP_BARU").".png";
                    else
                        $row[trim($aColumns[$i])] = $set->getField(trim($aColumns[$i]));
                }
                $result[] = $row;
                
                
                $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($aColumns) ,'result' => $result));
            }
        }

    }

	
    // insert new data to entitas
      function index_post() {
        $this->load->model('base-new/Pegawai');
        $this->load->model('UserLoginLog');
         $set = new Pegawai();

        $reqTempValidasiId= $this->input->post("reqTempValidasiId");
        $reqId = $this->input->post("reqId");
        $reqRowId= $this->input->post("reqRowId");

        $reqKartuPegawai = $this->input->post("reqKartuPegawai");
        $reqTempatLahir = $this->input->post("reqTempatLahir");
        $reqNipBaru = $this->input->post("reqNipBaru");
        $reqNipLama = $this->input->post("reqNipLama");
        $reqNama = $this->input->post("reqNama");
        $reqPegawaiKedudukanNama = $this->input->post("reqPegawaiKedudukanNama");
        $reqJenisKelamin = $this->input->post("reqJenisKelamin");
        $reqGolonganDarah = $this->input->post("reqGolonganDarah");
        $reqAgama = $this->input->post("reqAgama");
        $reqSukuBangsa = $this->input->post("reqSukuBangsa");
        $reqJenisPegawaiId = $this->input->post("reqJenisPegawaiId");
        $reqSatuanKerjaId = $this->input->post("reqSatuanKerjaId");
        $reqNik = $this->input->post("reqNik");
        $reqNoKk = $this->input->post("reqNoKk");
        $reqBpjs = $this->input->post("reqBpjs");
        $reqBpjsTanggal = $this->input->post("reqBpjsTanggal");
        $reqNpwp = $this->input->post("reqNpwp");
        $reqNpwpTanggal = $this->input->post("reqNpwpTanggal");
        $reqSkKonversiNip = $this->input->post("reqSkKonversiNip");
        $reqUrut = $this->input->post("reqUrut");
        $reqNoRakBerkas = $this->input->post("reqNoRakBerkas");
        $reqPropinsiId = $this->input->post("reqPropinsiId");
        $reqKabupatenId = $this->input->post("reqKabupatenId");
        $reqKecamatanId = $this->input->post("reqKecamatanId");
        $reqDesaId = $this->input->post("reqDesaId");
        $reqRt = $this->input->post("reqRt");
        $reqRw = $this->input->post("reqRw");
        $reqAlamat = $this->input->post("reqAlamat");
        $reqAlamatKeterangan = $this->input->post("reqAlamatKeterangan");
        $reqHp = $this->input->post("reqHp");
        $reqTeleponKantor = $this->input->post("reqTeleponKantor");
        $reqTelepon = $this->input->post("reqTelepon");
        $reqEmail = $this->input->post("reqEmail");
        $reqEmailKantor = $this->input->post("reqEmailKantor");
        $reqFacebook = $this->input->post("reqFacebook");
        $reqTwitter = $this->input->post("reqTwitter");
        $reqWhatsApp = $this->input->post("reqWhatsApp");
        $reqTelegram = $this->input->post("reqTelegram");
        $reqBank = $this->input->post("reqBank");
        $reqNoRekening = $this->input->post("reqNoRekening");
        $reqRekeningNama = $this->input->post("reqRekeningNama");
        $reqGajiPokok = $this->input->post("reqGajiPokok");
        $reqTunjanganKeluarga = $this->input->post("reqTunjanganKeluarga");
        $reqTunjangan = $this->input->post("reqTunjangan");
        $reqGajiBersih = $this->input->post("reqGajiBersih");
        $reqStatusMutasi = $this->input->post("reqStatusMutasi");
        $reqKeterangan1 = $this->input->post("reqKeterangan1");
        $reqKeterangan2 = $this->input->post("reqKeterangan2");
        
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
        else{


        $set->setField('STATUS_PEGAWAI_ID', $reqPegawaiId);    
        $set->setField('PEGAWAI_STATUS_ID', $reqPegawaiId);    
        $set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);        


        $set->setField('PEGAWAI_ID', $reqPegawaiId);    

        $set->setField('EMAIL_KANTOR', $reqEmailKantor);
        $set->setField('REKENING_NAMA', $reqRekeningNama);
        $set->setField("GAJI_POKOK", ValToNullDB(dotToNo($reqGajiPokok)));
        $set->setField("TUNJANGAN", ValToNullDB(dotToNo($reqTunjangan)));
        $set->setField("TUNJANGAN_KELUARGA", ValToNullDB(dotToNo($reqTunjanganKeluarga)));
        $set->setField("GAJI_BERSIH", ValToNullDB(dotToNo($reqGajiBersih)));
        $set->setField('STATUS_MUTASI', ValToNullDB($reqStatusMutasi));
        $set->setField('TMT_MUTASI', dateToDBCheck($reqTmtMutasi));
        $set->setField('INSTANSI_SEBELUM', $reqInstansiSebelum);

        $reqTanggalLahir= substr($reqNipBaru,6,2)."-".substr($reqNipBaru,4,2)."-".substr($reqNipBaru,0,4);
        $set->setField('SATUAN_KERJA_ID', $reqSatuanKerjaId);
        $set->setField('NIP_LAMA', $reqNipLama);
        $set->setField('NIP_BARU', $reqNipBaru);
        $set->setField('NAMA', $reqNama);
        $set->setField('GELAR_DEPAN', $reqGelarDepan);
        $set->setField('GELAR_BELAKANG', $reqGelarBelakang);
        $set->setField('TEMPAT_LAHIR', $reqTempatLahir);
        $set->setField('TANGGAL_LAHIR', dateToDBCheck($reqTanggalLahir));
        $set->setField('JENIS_KELAMIN', $reqJenisKelamin);
        $set->setField('STATUS_KAWIN', $reqStatusKawin);
        $set->setField('SUKU_BANGSA', $reqSukuBangsa);
        $set->setField('GOLONGAN_DARAH', $reqGolonganDarah);
        $set->setField('EMAIL', $reqEmail);
        $set->setField('ALAMAT', $reqAlamat);
        $set->setField('RT', $reqRt);
        $set->setField('RW', $reqRw);
        $set->setField('KODEPOS', $reqKodePos);
        $set->setField('TELEPON', $reqTelepon);
        $set->setField('HP', $reqHp);
        $set->setField('KARTU_PEGAWAI', $reqKartuPegawai);
        $set->setField('ASKES', $reqAskes);
        $set->setField('TASPEN', $reqTaspen);
        $set->setField('NPWP', $reqNpwp);
        $set->setField('NIK', $reqNik);
        $set->setField('NO_REKENING', $reqNoRekening);
        $set->setField('SK_KONVERSI_NIP', $reqSkKonversiNip);
        $set->setField('BANK_ID', ValToNullDB($reqBank));
        $set->setField('AGAMA_ID', $reqAgama);
        $set->setField('KEDUDUKAN', $reqPegawaiKedudukanNama);
        $set->setField('NO_URUT', $reqUrut);
        $set->setField('NO_KK', $reqNoKk);
        $set->setField('NO_RAK_BERKAS', $reqNoRakBerkas);
        $set->setField('TELEPON_KANTOR', $reqTeleponKantor);
        $set->setField('FACEBOOK', $reqFacebook);
        $set->setField('TWITTER', $reqTwitter);
        $set->setField('WHATSAPP', $reqWhatsApp);
        $set->setField('TELEGRAM', $reqTelegram);
        $set->setField('KETERANGAN_1', $reqKeterangan1);
        $set->setField('KETERANGAN_2', $reqKeterangan2);
        
        $set->setField('PROPINSI_ID', ValToNullDB($reqPropinsiId));
        $set->setField('KABUPATEN_ID', ValToNullDB($reqKabupatenId));
        $set->setField('KECAMATAN_ID', ValToNullDB($reqKecamatanId));
        $set->setField('DESA_ID', ValToNullDB($reqDesaId));

        $set->setField('JENIS_PEGAWAI_ID', ValToNullDB($reqJenisPegawaiId));

        $set->setField('BPJS', $reqBpjs);
        $set->setField('BPJS_TANGGAL', dateToDBCheck($reqBpjsTanggal));
        $set->setField('NPWP_TANGGAL', dateToDBCheck($reqNpwpTanggal));
        $set->setField('ALAMAT_KETERANGAN', $reqAlamatKeterangan);
        
        $set->setField("LAST_LEVEL",  ValToNullDB($this->LOGIN_LEVEL));
        $set->setField("LAST_USER",  ValToNullDB($this->LOGIN_USER));
        $set->setField("USER_LOGIN_ID",  ValToNullDB($reqPegawaiId));
        $set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($reqPegawaiId));
        $set->setField("LAST_DATE", "NOW()");

       // $sql = $this->db->last_query();

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
             $stringQURY = $sql = $this->db->last_query();
            if($reqsimpan !== "1")
            {
                $reqTempValidasiId= "";
            }
            // $tes = $set->query;

            if(!empty($reqTempValidasiId))
            {
                $this->response(array('status' => "success", 'message' => 'Data berhasil disimpan.', 'id' => $reqTempValidasiId));
            }
            else
            {
               
                $this->response(array('status' => "fail", 'code' =>  502));
            }

        }
         
      }

    function index_post22() {

        

       
 

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

       
        }
        
    }
 
    // update data entitas
    function index_put() {
    }
 
    // delete entitas
    function index_delete() {
    }
 
}