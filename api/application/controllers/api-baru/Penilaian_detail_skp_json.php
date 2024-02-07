<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Penilaian_detail_skp_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base-new/PenilaianSkpDetil');

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
        // ECHO 'TEST';EXIT;
        if($reqPegawaiId == "")
        {
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
        }
        else
        {
            $set = new PenilaianSkpDetil;
            $aColumns = array("PENILAIAN_SKP_DETIL_ID","TAHUN","TRIWULAN","NAMA_TRIWULAN","PEGAWAI_ID","PEGAWAI_UNOR_ID","PEGAWAI_UNOR_NAMA","JENIS_JABATAN_DINILAI","PEGAWAI_PEJABAT_PENILAI_ID","PEGAWAI_PEJABAT_PENILAI_NIP","PEGAWAI_PEJABAT_PENILAI_NAMA","PEGAWAI_PEJABAT_PENILAI_JABATAN_NAMA","PEGAWAI_PEJABAT_PENILAI_UNOR_NAMA","PEGAWAI_PEJABAT_PENILAI_PANGKAT_ID","PEGAWAI_ATASAN_PEJABAT_ID","PEGAWAI_ATASAN_PEJABAT_NIP","PEGAWAI_ATASAN_PEJABAT_NAMA","PEGAWAI_ATASAN_PEJABAT_JABATAN_NAMA","PEGAWAI_ATASAN_PEJABAT_UNOR_NAMA","PEGAWAI_ATASAN_PEJABAT_PANGKAT_ID","NILAI_HASIL_KERJA","NILAI_HASIL_PERILAKU","NILAI_QUADRAN","NILAI_CAPAIAN_ORGANISASI","LAST_USER","LAST_DATE","LAST_LEVEL","USER_LOGIN_ID","USER_LOGIN_PEGAWAI_ID","LAST_CREATE_USER","LAST_CREATE_DATE","STATUS","VALIDASI","VALIDATOR","PERUBAHAN_DATA","PERUBAHAN_VERIFIKATOR_DATA","TIPE_PERUBAHAN_DATA","TANGGAL_VALIDASI","TEMP_VALIDASI_ID");
            $statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
            // $statement .= " AND A.TAHUN > '2022' ";
            $set->selectByPersonal(array(), -1, -1, $reqPegawaiId, $reqId, $reqRowId, $statement," ORDER  BY A.TAHUN, A.TRIWULAN DESC" );
           // ECHO $set->query;exit;
            
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

            $this->load->model('base-new/PenilaianSkpDetil');

          $reqPenilaianSkpDinilaiNama = $this->input->post('reqPenilaianSkpDinilaiNama');
        $reqPenilaianSkpDinilaiNip = $this->input->post('reqPenilaianSkpDinilaiNip');
        $reqSatkerAtasan = $this->input->post('reqSatkerAtasan');
        $reqPenilaianSkpDinilaiId = $this->input->post('reqPenilaianSkpDinilaiId');

        $reqTahun = $this->input->post('reqTahun');
        $reqPegawaiJenisJabatan = $this->input->post('reqPegawaiJenisJabatan');
        $reqPegawaiUnorNama = $this->input->post('reqPegawaiUnorNama');
        $reqPegawaiUnorId = $this->input->post('reqPegawaiUnorId');

        $reqPenilaianSkpPenilaiId = $this->input->post('reqPenilaianSkpPenilaiId');
        $reqPenilaianSkpPenilaiNama = $this->input->post('reqPenilaianSkpPenilaiNama');
        $reqPenilaianSkpPenilaiNip = $this->input->post('reqPenilaianSkpPenilaiNip');
        $reqPenilaianSkpPenilaiJabatanNama = $this->input->post('reqPenilaianSkpPenilaiJabatanNama');
        $reqPenilaianSkpPenilaiUnorNama = $this->input->post('reqPenilaianSkpPenilaiUnorNama');
        $reqPenilaianSkpPenilaiPangkatId = $this->input->post('reqPenilaianSkpPenilaiPangkatId');


        $reqPenilaianSkpAtasanId = $this->input->post('reqPenilaianSkpAtasanId');
        $reqPenilaianSkpAtasanNama = $this->input->post('reqPenilaianSkpAtasanNama');
        $reqPenilaianSkpAtasanNip = $this->input->post('reqPenilaianSkpAtasanNip');
        $reqPenilaianSkpAtasanJabatanNama = $this->input->post('reqPenilaianSkpAtasanJabatanNama');
        $reqPenilaianSkpAtasanUnorNama = $this->input->post('reqPenilaianSkpAtasanUnorNama');
        $reqPenilaianSkpAtasanPangkatId = $this->input->post('reqPenilaianSkpAtasanPangkatId');

        $reqSkpNilai = $this->input->post('reqSkpNilai');
        $reqSkpHasil = $this->input->post('reqSkpHasil');
        $reqOrientasiNilai = $this->input->post('reqOrientasiNilai'); 
        $reqIntegritasNilai = $this->input->post('reqIntegritasNilai');
        $reqKomitmenNilai = $this->input->post('reqKomitmenNilai'); 
        $reqDisiplinNilai = $this->input->post('reqDisiplinNilai'); 
        $reqKerjasamaNilai = $this->input->post('reqKerjasamaNilai'); 
        $reqKepemimpinanNilai = $this->input->post('reqKepemimpinanNilai'); 
        $reqJumlahNilai = $this->input->post('reqJumlahNilai');
        $reqRataNilai = $this->input->post('reqRataNilai'); 
        $reqPerilakuNilai = $this->input->post('reqPerilakuNilai'); 
        $reqPerilakuHasil = $this->input->post('reqPerilakuHasil'); 
        $reqPrestasiNilai = $this->input->post('reqPrestasiNilai'); 
        $reqPrestasiHasil = $this->input->post('reqPrestasiHasil'); 
        $reqKeberatan = $this->input->post('reqKeberatan');
        $reqTanggalKeberatan = $this->input->post('reqTanggalKeberatan'); 
        $reqTanggapan = $this->input->post('reqTanggapan'); 
        $reqTanggalTanggapan = $this->input->post('reqTanggalTanggapan'); 
        $reqKeputusan = $this->input->post('reqKeputusan'); 
        $reqTanggalKeputusan = $this->input->post('reqTanggalKeputusan');
        $reqRekomendasi = $this->input->post('reqRekomendasi'); 

        
        $reqPegawaiJenisJabatan2 = $this->input->post('reqPegawaiJenisJabatan2');
        $reqPegawaiUnorNama2 = $this->input->post('reqPegawaiUnorNama2');
        $reqPegawaiUnorId2 = $this->input->post('reqPegawaiUnorId2');
        
        $reqPenilaianSkpPenilaiId2 = $this->input->post("reqPenilaianSkpPenilaiId2");
        $reqPenilaianSkpPenilaiNama2 = $this->input->post('reqPenilaianSkpPenilaiNama2');
        $reqPenilaianSkpPenilaiNip2 = $this->input->post('reqPenilaianSkpPenilaiNip2');
        $reqPenilaianSkpPenilaiJabatanNama2 = $this->input->post('reqPenilaianSkpPenilaiJabatanNama2');
        $reqPenilaianSkpPenilaiUnorNama2 = $this->input->post('reqPenilaianSkpPenilaiUnorNama2');
        $reqPenilaianSkpPenilaiPangkatId2 = $this->input->post('reqPenilaianSkpPenilaiPangkatId2');

        $reqPenilaianSkpAtasanId2 = $this->input->post("reqPenilaianSkpAtasanId2");
        $reqPenilaianSkpAtasanNama2 = $this->input->post('reqPenilaianSkpAtasanNama2');
        $reqPenilaianSkpAtasanNip2 = $this->input->post('reqPenilaianSkpAtasanNip2');
        $reqPenilaianSkpAtasanJabatanNama2 = $this->input->post('reqPenilaianSkpAtasanJabatanNama2');
        $reqPenilaianSkpAtasanUnorNama2 = $this->input->post('reqPenilaianSkpAtasanUnorNama2');
        $reqPenilaianSkpAtasanPangkatId2 = $this->input->post('reqPenilaianSkpAtasanPangkatId2');

        $reqSkpNilai2 = $this->input->post('reqSkpNilai2');
        $reqSkpHasil2 = $this->input->post('reqSkpHasil2');
        $reqOrientasiNilai2 = $this->input->post('reqOrientasiNilai2'); 
        $reqKomitmenNilai2 = $this->input->post('reqKomitmenNilai2'); 
        $reqKerjasamaNilai2 = $this->input->post('reqKerjasamaNilai2'); 
        $reqKepemimpinanNilai2 = $this->input->post('reqKepemimpinanNilai2');
        $reqInisiatifkerjaNilai2 = $this->input->post('reqInisiatifkerjaNilai2');
        $reqJumlahNilai2= $this->input->post('reqJumlahNilai2'); 
        $reqRataNilai2= $this->input->post('reqRataNilai2');
        
        $reqNilaiHasilKerja= $this->input->post('reqNilaiHasilKerja'); 
        $reqNilaiPerilakuKerja= $this->input->post('reqNilaiPerilakuKerja');    

        $reqTriwulan= $this->input->post('reqTriwulan');    
        $reqKuadratPredikatKinerja= $this->input->post('reqKuadratPredikatKinerja');    
        $reqCapaianOrganisasi= $this->input->post('reqCapaianOrganisasi');

        $set= new PenilaianSkpDetil();
        $set->setField("PENILAIAN_SKP_DETIL_ID", ValToNullDB($reqRowId));
        $set->setField("PEGAWAI_ID", ValToNullDB($reqPegawaiId));
        $set->setField("TAHUN", ValToNullDB($reqTahun));
        $set->setField("TRIWULAN", ValToNullDB($reqTriwulan));

        $set->setField("JENIS_JABATAN_DINILAI", ValToNullDB($reqPegawaiJenisJabatan));
        $set->setField("PEGAWAI_UNOR_NAMA", ValToNullDB($reqPegawaiUnorNama));
        $set->setField("PEGAWAI_UNOR_ID", ValToNullDB($reqPegawaiUnorId));

        $set->setField("PEGAWAI_PEJABAT_PENILAI_ID", ValToNullDB($reqPenilaianSkpPenilaiId));
        $set->setField("PEGAWAI_PEJABAT_PENILAI_NIP", ValToNullDB($reqPenilaianSkpPenilaiNip));
        $set->setField("PEGAWAI_PEJABAT_PENILAI_NAMA", ValToNullDB($reqPenilaianSkpPenilaiNama));
        $set->setField("PEGAWAI_PEJABAT_PENILAI_JABATAN_NAMA", ValToNullDB($reqPenilaianSkpPenilaiJabatanNama));
        $set->setField("PEGAWAI_PEJABAT_PENILAI_UNOR_NAMA", ValToNullDB($reqPenilaianSkpPenilaiUnorNama));
        $set->setField("PEGAWAI_PEJABAT_PENILAI_PANGKAT_ID", ValToNullDB($reqPenilaianSkpPenilaiPangkatId));

        $set->setField("PEGAWAI_ATASAN_PEJABAT_ID", ValToNullDB($reqPenilaianSkpAtasanId));
        $set->setField("PEGAWAI_ATASAN_PEJABAT_NIP", ValToNullDB($reqPenilaianSkpAtasanNip));
        $set->setField("PEGAWAI_ATASAN_PEJABAT_NAMA", ValToNullDB($reqPenilaianSkpAtasanNama));
        $set->setField("PEGAWAI_ATASAN_PEJABAT_JABATAN_NAMA", ValToNullDB($reqPenilaianSkpAtasanJabatanNama));
        $set->setField("PEGAWAI_ATASAN_PEJABAT_UNOR_NAMA", ValToNullDB($reqPenilaianSkpAtasanUnorNama));
        $set->setField("PEGAWAI_ATASAN_PEJABAT_PANGKAT_ID", ValToNullDB($reqPenilaianSkpAtasanPangkatId));

        $set->setField('SKP_NILAI', ValToNullDB(CommaToDot($reqSkpNilai)));
        $set->setField("SKP_HASIL", ValToNullDB(CommaToDot($reqSkpHasil)));
        $set->setField("ORIENTASI_NILAI", ValToNullDB(CommaToDot($reqOrientasiNilai)));
        $set->setField("INTEGRITAS_NILAI", ValToNullDB(CommaToDot($reqIntegritasNilai)));
        $set->setField("KOMITMEN_NILAI", ValToNullDB(CommaToDot($reqKomitmenNilai)));
        $set->setField("DISIPLIN_NILAI", ValToNullDB(CommaToDot($reqDisiplinNilai)));
        $set->setField("KERJASAMA_NILAI", ValToNullDB(CommaToDot($reqKerjasamaNilai)));
        $set->setField("KEPEMIMPINAN_NILAI", ValToNullDB(CommaToDot($reqKepemimpinanNilai)));
        $set->setField("PERILAKU_NILAI", ValToNullDB(CommaToDot($reqPerilakuNilai)));
        $set->setField("PERILAKU_HASIL", ValToNullDB(CommaToDot($reqPerilakuHasil)));
        $set->setField("PRESTASI_HASIL", ValToNullDB(CommaToDot($reqPrestasiHasil)));
        $set->setField("JUMLAH_NILAI", ValToNullDB(CommaToDot($reqJumlahNilai)));
        $set->setField("RATA_NILAI", ValToNullDB(CommaToDot($reqRataNilai)));

        $set->setField("KEBERATAN", ValToNullDB($reqKeberatan));
        $set->setField("KEBERATAN_TANGGAL", dateToDBCheck($reqTanggalKeberatan));
        $set->setField("TANGGAPAN", ValToNullDB($reqTanggapan));
        $set->setField("TANGGAPAN_TANGGAL", dateToDBCheck($reqTanggalTanggapan));
        $set->setField("KEPUTUSAN", ValToNullDB($reqKeputusan));
        $set->setField("KEPUTUSAN_TANGGAL", dateToDBCheck($reqTanggalKeputusan));
        $set->setField("REKOMENDASI", ValToNullDB($reqRekomendasi));

        $set->setField("JENIS_JABATAN_DINILAI2", ValToNullDB($reqPegawaiJenisJabatan2));
        $set->setField("PEGAWAI_UNOR_NAMA2", ValToNullDB($reqPegawaiUnorNama2));
        $set->setField("PEGAWAI_UNOR_ID2", ValToNullDB($reqPegawaiUnorId2));

        $set->setField("PEGAWAI_PEJABAT_PENILAI_ID2", ValToNullDB($reqPenilaianSkpPenilaiId2));
        $set->setField("PEGAWAI_PEJABAT_PENILAI_NIP2", ValToNullDB($reqPenilaianSkpPenilaiNip2));
        $set->setField("PEGAWAI_PEJABAT_PENILAI_NAMA2", ValToNullDB($reqPenilaianSkpPenilaiNama2));
        $set->setField("PEGAWAI_PEJABAT_PENILAI_JABATAN_NAMA2", ValToNullDB($reqPenilaianSkpPenilaiJabatanNama2));
        $set->setField("PEGAWAI_PEJABAT_PENILAI_UNOR_NAMA2", ValToNullDB($reqPenilaianSkpPenilaiUnorNama2));
        $set->setField("PEGAWAI_PEJABAT_PENILAI_PANGKAT_ID2", ValToNullDB($reqPenilaianSkpPenilaiPangkatId2));

        $set->setField("PEGAWAI_ATASAN_PEJABAT_ID2", ValToNullDB($reqPenilaianSkpAtasanId2));
        $set->setField("PEGAWAI_ATASAN_PEJABAT_NIP2", ValToNullDB($reqPenilaianSkpAtasanNip2));
        $set->setField("PEGAWAI_ATASAN_PEJABAT_NAMA2", ValToNullDB($reqPenilaianSkpAtasanNama2));
        $set->setField("PEGAWAI_ATASAN_PEJABAT_JABATAN_NAMA2", ValToNullDB($reqPenilaianSkpAtasanJabatanNama2));
        $set->setField("PEGAWAI_ATASAN_PEJABAT_UNOR_NAMA2", ValToNullDB($reqPenilaianSkpAtasanUnorNama2));
        $set->setField("PEGAWAI_ATASAN_PEJABAT_PANGKAT_ID2", ValToNullDB($reqPenilaianSkpAtasanPangkatId2));
        $set->setField("NILAI_HASIL_KERJA", ValToNullDB($reqNilaiHasilKerja));
        $set->setField("NILAI_HASIL_PERILAKU", ValToNullDB($reqNilaiPerilakuKerja));
        $set->setField("NILAI_QUADRAN", ValToNullDB($reqKuadratPredikatKinerja));
        $set->setField("NILAI_CAPAIAN_ORGANISASI", ValToNullDB($reqCapaianOrganisasi));


        $set->setField("LAST_USER", $reqPegawaiId);
        $set->setField("LAST_DATE", "NOW()");
        $set->setField("LAST_LEVEL",  ValToNullDB($this->LOGIN_LEVEL));
        $set->setField("USER_LOGIN_ID", $reqPegawaiId);
        $set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($reqPegawaiId));

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


            $query = $this->db->last_query();
            if($reqsimpan !== "1")
            {
                $reqTempValidasiId= "";
            }
           
            if(!empty($reqTempValidasiId))
            {
                $this->response(array('status' => 'success', 'message' => 'Data berhasil disimpan.', 'id' => $reqTempValidasiId,"query"=>$query ));
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