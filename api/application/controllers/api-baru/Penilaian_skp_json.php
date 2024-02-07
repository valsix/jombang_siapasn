<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Penilaian_skp_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base-new/PenilaianSkp');

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
            $set = new PenilaianSkp;
            $aColumns = array("PENILAIAN_SKP_ID","STATUS","PEGAWAI_ID","TAHUN","PEGAWAI_PEJABAT_PENILAI_ID","PEGAWAI_ATASAN_PEJABAT_ID","SKP_NILAI","SKP_HASIL","ORIENTASI_NILAI","INTEGRITAS_NILAI","KOMITMEN_NILAI","DISIPLIN_NILAI","KERJASAMA_NILAI","KEPEMIMPINAN_NILAI","PERILAKU_NILAI","PERILAKU_HASIL","PRESTASI_HASIL","JUMLAH_NILAI","RATA_NILAI","KEBERATAN","KEBERATAN_TANGGAL","TANGGAPAN","TANGGAPAN_TANGGAL","KEPUTUSAN","KEPUTUSAN_TANGGAL","REKOMENDASI","PEGAWAI_PEJABAT_PENILAI_NIP","PEGAWAI_PEJABAT_PENILAI_NAMA","PEGAWAI_ATASAN_PEJABAT_NIP","PEGAWAI_ATASAN_PEJABAT_NAMA","LAST_USER","LAST_DATE","LAST_LEVEL" , "TEMP_VALIDASI_ID", "TEMP_VALIDASI_HAPUS_ID", "VALIDASI", "VALIDATOR", "PERUBAHAN_DATA", "TIPE_PERUBAHAN_DATA", "TANGGAL_VALIDASI","JENIS_JABATAN_DINILAI","PEGAWAI_UNOR_ID","PEGAWAI_UNOR_NAMA","PEGAWAI_PEJABAT_PENILAI_JABATAN_NAMA","PEGAWAI_PEJABAT_PENILAI_UNOR_NAMA","PEGAWAI_ATASAN_PEJABAT_JABATAN_NAMA","PEGAWAI_ATASAN_PEJABAT_UNOR_NAMA","PEGAWAI_ATASAN_PEJABAT_PANGKAT_ID","NILAI_HASIL_KERJA","NILAI_HASIL_PERILAKU","PEGAWAI_PEJABAT_PENILAI_PANGKAT_ID");
            $statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
            // $statement .= " AND A.TAHUN > '2022' ";
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

            $this->load->model('base-new/PenilaianSkp');

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

            $reqPenilaianSkpDinilaiNama = $this->input->post('reqPenilaianSkpDinilaiNama');
            $reqPenilaianSkpDinilaiNip = $this->input->post('reqPenilaianSkpDinilaiNip');
            $reqSatkerAtasan = $this->input->post('reqSatkerAtasan');

            $reqPenilaianSkpDinilaiId = $this->input->post('reqPenilaianSkpDinilaiId');
            $reqPenilaianSkpPenilaiId = $this->input->post('reqPenilaianSkpPenilaiId');
            $reqPenilaianSkpAtasanId = $this->input->post('reqPenilaianSkpAtasanId');
            $reqPenilaianSkpPenilaiNama = $this->input->post('reqPenilaianSkpPenilaiNama');
            $reqPenilaianSkpPenilaiNip = $this->input->post('reqPenilaianSkpPenilaiNip');
            $reqPenilaianSkpAtasanNama = $this->input->post('reqPenilaianSkpAtasanNama');
            $reqPenilaianSkpAtasanNip = $this->input->post('reqPenilaianSkpAtasanNip');
            $reqTahun = $this->input->post('reqTahun');

            $reqNilaiHasilKerja= $this->input->post('reqNilaiHasilKerja'); 
            $reqNilaiPerilakuKerja= $this->input->post('reqNilaiPerilakuKerja');
            $reqPegawaiJenisJabatan = $this->input->post('reqPegawaiJenisJabatan');
            $reqPegawaiUnorId = $this->input->post('reqPegawaiUnorId');
            $reqPegawaiUnorNama = $this->input->post('reqPegawaiUnorNama');
            $reqPenilaianSkpPenilaiJabatanNama = $this->input->post('reqPenilaianSkpPenilaiJabatanNama');
            $reqPenilaianSkpPenilaiUnorNama = $this->input->post('reqPenilaianSkpPenilaiUnorNama');
            $reqPenilaianSkpAtasanJabatanNama = $this->input->post('reqPenilaianSkpAtasanJabatanNama');
            $reqPenilaianSkpAtasanUnorNama = $this->input->post('reqPenilaianSkpAtasanUnorNama');
            $reqPenilaianSkpAtasanPangkatId = $this->input->post('reqPenilaianSkpAtasanPangkatId');
            $reqPenilaianSkpPenilaiPangkatId = $this->input->post('reqPenilaianSkpPenilaiPangkatId');

            $set= new PenilaianSkp();
            $set->setField("PEGAWAI_ID", ValToNullDB($reqId));
            $set->setField("TAHUN", ValToNullDB($reqTahun));
            $set->setField("PEGAWAI_PEJABAT_PENILAI_ID", ValToNullDB($reqPenilaianSkpPenilaiId));
            $set->setField("PEGAWAI_ATASAN_PEJABAT_ID", ValToNullDB($reqPenilaianSkpAtasanId));

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
            $set->setField("KEBERATAN", $reqKeberatan);
            $set->setField("KEBERATAN_TANGGAL", dateToDBCheck($reqTanggalKeberatan));
            $set->setField("TANGGAPAN", $reqTanggapan);
            $set->setField("TANGGAPAN_TANGGAL", dateToDBCheck($reqTanggalTanggapan));
            $set->setField("KEPUTUSAN", $reqKeputusan);
            $set->setField("KEPUTUSAN_TANGGAL", dateToDBCheck($reqTanggalKeputusan));
            $set->setField("REKOMENDASI", $reqRekomendasi);
            $set->setField("PEGAWAI_PEJABAT_PENILAI_NIP", $reqPenilaianSkpPenilaiNip);
            $set->setField("PEGAWAI_PEJABAT_PENILAI_NAMA", $reqPenilaianSkpPenilaiNama);
            $set->setField("PEGAWAI_ATASAN_PEJABAT_NIP", $reqPenilaianSkpAtasanNip);
            $set->setField("PEGAWAI_ATASAN_PEJABAT_NAMA", $reqPenilaianSkpAtasanNama);

            $set->setField('PENILAIAN_SKP_ID', ValToNullDB($reqRowId));

            $set->setField('PEGAWAI_ID', ValToNullDB($reqPegawaiId));
            $set->setField("LAST_LEVEL", $lastlevel);
            $set->setField("LAST_USER", $lastuser);
            $set->setField("USER_LOGIN_ID", ValToNullDB($lastloginid));
            $set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($lastloginpegawaiid));
            $set->setField("LAST_DATE", "NOW()");

             $set->setField("JENIS_JABATAN_DINILAI", ValToNullDB($reqPegawaiJenisJabatan));
             $set->setField("PEGAWAI_UNOR_ID", ValToNullDB($reqPegawaiUnorId));
             $set->setField("PEGAWAI_UNOR_NAMA", ValToNullDB( $reqPegawaiUnorNama));
             $set->setField("PEGAWAI_PEJABAT_PENILAI_JABATAN_NAMA", ValToNullDB($reqPenilaianSkpPenilaiJabatanNama));
             $set->setField("PEGAWAI_PEJABAT_PENILAI_UNOR_NAMA", ValToNullDB($reqPenilaianSkpPenilaiUnorNama));
             $set->setField("PEGAWAI_ATASAN_PEJABAT_JABATAN_NAMA", ValToNullDB($reqPenilaianSkpAtasanJabatanNama));
             $set->setField("PEGAWAI_ATASAN_PEJABAT_UNOR_NAMA", ValToNullDB($reqPenilaianSkpAtasanUnorNama));
             $set->setField("PEGAWAI_ATASAN_PEJABAT_PANGKAT_ID", ValToNullDB($reqPenilaianSkpAtasanPangkatId));
             $set->setField("NILAI_HASIL_KERJA", ValToNullDB($reqNilaiHasilKerja));
             $set->setField("NILAI_HASIL_PERILAKU", ValToNullDB($reqNilaiPerilakuKerja));
             $set->setField("PEGAWAI_PEJABAT_PENILAI_PANGKAT_ID", ValToNullDB($reqPenilaianSkpPenilaiPangkatId));

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
            // $tes = $set->query;

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