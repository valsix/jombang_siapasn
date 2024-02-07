<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Combo_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
        $this->load->library('globalvalidasifilepegawai');
        // $this->db->query("alter session set nls_date_format='DD-MM-YYYY'"); 
        
    }
 
    // show data entitas
    function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base-new/Combo');
        $this->load->model('base-new/SatuanKerja');

        $user_login_log= new UserLoginLog;
        
        $reqToken = $this->input->get("reqToken");
        $reqMode = $this->input->get("reqMode");

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
            $set= new Combo;
            $loopdata= 0;
            if(isStrContain($reqMode, "pangkat") == true)
            {
                $statement= "";
                if($reqMode == "pangkat_cpns")
                {
                    $statement= " AND A.PANGKAT_ID <= 32";
                }

                $aColumns= array("PANGKAT_ID", "KODE");
                $set->selectByParamsPangkat(array(), -1,-1, $statement);
            }
            else if($reqMode == "gajipokok")
            {
                $reqPangkatId= $this->input->get("reqPangkatId");
                $reqMasaKerja= $this->input->get("reqMasaKerja");
                $reqPeriode= $this->input->get("reqPeriode");

                $tempGajiPokok= $set->getCountByParamsGajiBerlaku($reqPeriode, $reqMasaKerja, $reqPangkatId);
                if($tempGajiPokok == "")
                    $tempGajiPokok= 0;
                // echo $set->query;exit;

                $loopdata= 1;
                $row = array();
                $row["data"] = $tempGajiPokok;
            }
            elseif($reqMode == "formasicpns")
            {
                $statement= "";

                $aColumns= array("FORMASI_CPNS_ID", "NAMA");
                $set->selectByParamsFormasiCpns(array(), -1,-1, $statement);
            }
            elseif($reqMode == "jabatanfu")
            {
                $term= $this->input->get("term");
                $statement= " AND UPPER(A.NAMA) LIKE '".strtoupper($term)."%' ";
                // echo $statement;exit;

                $aColumns= array("JABATAN_FU_ID", "NAMA", "SATUAN_KERJA_NAMA_DETIL");
                $set->selectByParamsJabatanFu(array(), 70, 0, $statement);
                // echo $set->query;exit;
            }
            elseif($reqMode == "jabatanft")
            {
                $term= $this->input->get("term");
                $statement= " AND UPPER(A.NAMA) LIKE '%".strtoupper($term)."%' ";
                // echo $statement;exit;

                $aColumns= array("JABATAN_FT_ID", "NAMA", "SATUAN_KERJA_NAMA_DETIL");
                $set->selectByParamsJabatanFt(array(), 70, 0, $statement);
                // echo $set->query;exit;
            }

            elseif($reqMode == "pejabatpenetap")
            {
                $term= $this->input->get("term");
                $statement= " AND A.STATUS IS NULL AND UPPER(A.NAMA) LIKE '%".strtoupper($term)."%' ";
                // echo $statement;exit;

                $aColumns= array("PEJABAT_PENETAP_ID", "NAMA");
                $set->selectByParamsPejabatPenetap(array(), 5, 0, $statement);
                // echo $set->query;exit;
            }
              elseif($reqMode == "capainorganisasi")
            {
                // $term= $this->input->get("term");
                // $statement= " AND A.STATUS IS NULL AND UPPER(A.NAMA) LIKE '%".strtoupper($term)."%' ";
                // echo $statement;exit;

                $aColumns= array("ID", "NAMA","KETERANGAN");
                $set->selectByParamsCapainOrganisasi(array(), 5, 0, $statement);
                // echo $set->query;exit;
            }
             elseif($reqMode == "nilaikuadran")
            {
                // $term= $this->input->get("term");
                // $statement= " AND A.STATUS IS NULL AND UPPER(A.NAMA) LIKE '%".strtoupper($term)."%' ";
                // echo $statement;exit;
                $reqId= $this->input->get("reqId");
                if(!empty($reqId)){
                     $statement =" AND A.KODE='".$reqId."'";
                }
                $aColumns= array("NILAI_KUADRAN_ID", "KODE","NAMA","KETERANGAN");
                $set->selectByParamsNilaiKuadran(array(), -1, -1, $statement);
                // echo $set->query;exit;
            }
            elseif($reqMode == "pendidikan")
            {
                $statement= "";

                $aColumns= array("PENDIDIKAN_ID", "NAMA");
                $set->selectByParamsPendidikan(array(), -1,-1, $statement);
            }
            elseif($reqMode == "skdasar")
            {
                $statement= "";

                $aColumns= array("SK_DASAR_JABATAN_ID", "NAMA");
                $set->selectskdasarjabatan(array(), -1,-1, $statement);
            }
            elseif($reqMode == "pendidikan_pppk")
            {
                $statement= " AND A.STATUS IS NULL AND A.PPPK_STATUS = 1 AND A.PEGAWAI_ID = ".$reqPegawaiId;


                $aColumns= array("PENDIDIKAN_RIWAYAT_ID", "PENDIDIKAN_NAMA");
                $set->selectByParamsPendidikanRiwayat(array(), -1,-1, $statement);
            }
            elseif($reqMode == "surat_tanda_lulus_combo")
            {
              
                $term= $this->input->get("term");
                $reqId= $this->input->get("reqId");
                $statement= " AND  UPPER(C.NAMA) LIKE '%".strtoupper($term)."%' ";
               $statement .= " AND A.PEGAWAI_ID = ".$reqPegawaiId." AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2') AND A.PENDIDIKAN_ID IN (".$reqId.")";


                $aColumns= array("PENDIDIKAN_RIWAYAT_ID", "PENDIDIKAN_ID","PENDIDIKAN_NAMA","TANGGAL_STTB");
                $set->selectByParamsPendidikanRiwayat(array(), -1,-1, $statement);
                // ECHO $set->query;exit;
            }
             elseif($reqMode == "pendidikanJurusan")
            {
                 $reqJurusan= $this->input->get("reqJurusan");
                 $reqPendidikanId= $this->input->get("reqPendidikanId");
                  $statement='';
                 if(!empty($reqJurusan)){
                    $statement .= " AND STATUS IS NULL AND UPPER(NAMA) = '".strtoupper($reqJurusan)."'"; 
                 } if(!empty($reqPendidikanId)){
                    $statement .= " AND PENDIDIKAN_ID= '".$reqPendidikanId."'"; 
                 }
               

               
                $aColumns= array("PENDIDIKAN_JURUSAN_ID", "NAMA");
                $set->selectByParamsPendidikanJurusan(array(), -1,-1, $statement);
                
            }
            elseif($reqMode == "gol_pppk")
            {
                $statement= "";


                $aColumns= array("GOLONGAN_PPPK_ID", "KODE");
                $set->selectByParamsGolonganPppk(array(), -1,-1, $statement);
            }
            elseif($reqMode == "suamiistri")
            {
                $term= $this->input->get("term");
                $reqId= $this->input->get("reqId");
                $statement= " AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')";
                $statement.= " AND UPPER(A.NAMA) LIKE '%".strtoupper($term)."%' ";
                // echo $statement;exit;
                if($reqId == ""){}
                    else
                    {
                        $statement.= " AND A.PEGAWAI_ID = ".$reqId;
                    }

                $aColumns= array("SUAMI_ISTRI_ID", "NAMA");
                $set->selectByParamsSuamiIstri(array(), 5, 0, $statement);
                // echo $set->query;exit;
            }
            elseif($reqMode == "jurusan")
            {
                $term= $this->input->get("term");
                $statement= " AND A.STATUS IS NULL AND UPPER(A.NAMA) LIKE '%".strtoupper($term)."%' ";
                // echo $statement;exit;
                if($reqId == ""){
                }
                else
                {
                    $statement.= " AND A.PENDIDIKAN_ID = ".$reqId;
                }

                $aColumns= array("PENDIDIKAN_JURUSAN_ID", "NAMA");
                $set->selectByParamsJurusan(array(), 5, 0, $statement);
                // echo $set->query;exit;
            }
            elseif($reqMode == "jurusan_pppk")
            {
                $term= $this->input->get("term");
                $reqId= $this->input->get("reqId");
                $statement= " AND A.PENDIDIKAN_RIWAYAT_ID = ".$reqId;
                // echo $statement;exit;
                $aColumns= array("PENDIDIKAN_RIWAYAT_ID", "PENDIDIKAN_JURUSAN_NAMA");
                $set->selectByParamsPendidikanRiwayat(array(), 5, 0, $statement);
                // echo $set->query;exit;
            }
            elseif($reqMode == "diklat_struktural")
            {
                $term= $this->input->get("term");
                $statement= " AND UPPER(A.NAMA) LIKE '%".strtoupper($term)."%' ";
                // echo $statement;exit;
                if($reqId == ""){
                }
                else
                {
                    // $statement.= " AND A.PENDIDIKAN_ID = ".$reqId;
                }

                $aColumns= array("DIKLAT_ID", "NAMA");
                $set->selectByParamsDiklatStruktural(array(), 5, 0, $statement);
                // echo $set->query;exit;
            }
            elseif($reqMode == "caripegawai")
            {
                $term= $this->input->get("term");
                $statement.= " AND UPPER(A.NIP_BARU) LIKE '%".strtoupper(str_replace(" ", "", $term))."%' ";
                // echo $statement;exit;

                $aColumns= array("PEGAWAI_ID", "NIP_BARU", "NAMA_LENGKAP");
                $set->selectByParamsCariPegawai(array(), 5, 0, $statement);
                // echo $set->query;exit;
            }
             elseif($reqMode == "pegawai")
            {
                $reqPegawaiId= $this->input->get("reqPegawaiId");
               
                 $statement = " AND A.PEGAWAI_ID ='".$reqPegawaiId."'";
              

                $aColumns= array("PEGAWAI_ID", "NIP_BARU", "NAMA_LENGKAP","SATKER_ID_ATASAN");
                $set->selectByParamsDataPegawai(array(), -1, -1, $statement);
                // echo $set->query;exit;
            }
            elseif($reqMode == "satuankerja")
            {
                
                $term= $this->input->get("term");
                $reqTanggalBatas= $this->input->get("reqTanggalBatas");
                $reqSatuanKerjaIndukId= $this->input->get("reqSatuanKerjaIndukId");
                // $statement.= " AND UPPER(A.NAMA) LIKE '%".strtoupper(str_replace(" ", "", $term))."%' ";
                $statement.= " AND UPPER(A.NAMA) LIKE '".strtoupper($term)."%' ";

                if($reqTanggalBatas == "")
                {
                    $statementAktif= " AND EXISTS(
                    SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
                    AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
                     )";
                }
                else
                {
                    $statementAktif= " AND EXISTS(
                    SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL, TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD')) <= TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD') AND COALESCE(MASA_BERLAKU_AKHIR, TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD')) >= TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD')
                    AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
                    )";
                }
                if($reqSatuanKerjaIndukId == ""){}
                else
                {
                    $skerja= new SatuanKerja();
                    $reqSatuanKerjaIndukId= $skerja->getSatuanKerja($reqSatuanKerjaIndukId);
                    unset($skerja);
                    $statement.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaIndukId.") AND A.SATUAN_KERJA_MUTASI_STATUS = '1'" ;
                  
                }
                $aColumns= array("SATUAN_KERJA_ID", "NAMA");
                $set->selectByParamsSatuanKerja(array(), 5, 0, $statement);
                // echo $set->query;exit;
            }elseif($reqMode == "skpNamajabatan")
            {


                $term= $this->input->get("term");

                $reqId=  $this->input->get('reqId');
                $reqTipeJabatanId= $this->input->get('reqTipeJabatanId');
                $reqTanggalBatas= $this->input->get('reqTanggalBatas');
                $statement = " AND UPPER(A.NAMA) LIKE '%".strtoupper($term)."%' ";    
                 $aColumns= array("ID", "NAMA","NAMA_DETAIL");               
                $set->selectByParamsCariJabatan(array(), 70, 0, $statement);    
                   
            }elseif($reqMode == "skpNamaUnor")
            {

                $term= $this->input->get("term");
                $reqId=  $this->input->get('reqId');
                $reqTipeJabatanId= $this->input->get('reqTipeJabatanId');
                $reqTanggalBatas= $this->input->get('reqTanggalBatas');
               $statement = " AND UPPER(A.NAMA) LIKE '%".strtoupper($term)."%' ";    
                 $aColumns= array("ID", "NAMA","NAMA_DETAIL");               
                $set->selectByParamsCariUnor(array(), 70, 0, $statement);                       
            }

            elseif($reqMode == "namajabatan")
            {
                
                $term= $this->input->get("term");
                $reqStatusPlt=  $this->input->get('reqStatusPlt');
                $reqTanggalBatas= $this->input->get('reqTanggalBatas');
                $statement.= " AND UPPER(A.NAMA) LIKE '%".strtoupper(str_replace(" ", "", $term))."%' ";

                if($reqStatusPlt == "plt")
                {
                    $statement.= " AND UPPER(A.NAMA_JABATAN) LIKE '%".strtoupper($search_term)."%' ";
                    if($reqTanggalBatas == "")
                    {
                        $statement .= " AND 1 = 2";
                    }
                    else
                    {
                        $statement .= " 
                        AND
                        COALESCE(A.MASA_BERLAKU_AWAL, TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD'))
                        <= TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD')
                        AND
                        COALESCE(A.MASA_BERLAKU_AKHIR, TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD'))
                        >= TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD')";
                    }
                    $set->selectByParamsPlt(array(), 70, 0, $statement.$statementSatuanKerja);
                } 
                elseif($reqStatusPlt == "plh")
                {
                    $statement.= " AND UPPER(A.NAMA_JABATAN) LIKE '%".strtoupper(str_replace(" ", "", $term))."%' ";
                    if($reqTanggalBatas == "")
                    {
                        $statement .= " AND 1 = 2";
                    }
                    else
                    {
                        $statement .= " 
                        AND
                        COALESCE(A.MASA_BERLAKU_AWAL, TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD'))
                        <= TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD')
                        AND
                        COALESCE(A.MASA_BERLAKU_AKHIR, TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD'))
                        >= TO_DATE('".dateToPageCheck($reqTanggalBatas)."','YYYY/MM/DD')";
                    }
                    $set->selectByParamsPlh(array(), 70, 0, $statement.$statementSatuanKerja);
                }
                else
                {
                    $statement.= " AND UPPER(A.NAMA) LIKE '%".strtoupper(str_replace(" ", "", $term))."%' ";
                    $set->selectByParamsSatuanKerja(array(), 5, 0, $statement);
                }
                $aColumns= array("TUGAS_TAMBAHAN_ID", "NAMA","SATKER_NAMA","SATKER_ID");
                
                // echo $set->query;exit;
            }
            elseif($reqMode == "propinsi")
            {
                $term= $this->input->get("term");
                $statement.= " AND UPPER(NAMA) LIKE '%".strtoupper(str_replace(" ", "", $term))."%' ";
                // echo $statement;exit;

                $aColumns= array("PROPINSI_ID", "NAMA");
                $set->selectByParamsPropinsi(array(), 10, 0, $statement);
                // echo $set->query;exit;
            }
            elseif($reqMode == "kabupaten")
            {
                $term= $this->input->get("term");
                $reqPropinsiId= $this->input->get('reqPropinsiId');
                $statement.= " AND UPPER(NAMA) LIKE '%".strtoupper(str_replace(" ", "", $term))."%' ";
                if($reqPropinsiId == ""){}
                else
                $statement .= " AND PROPINSI_ID = ".$reqPropinsiId;
                // echo $statement;exit;

                $aColumns= array("KABUPATEN_ID", "NAMA");
                $set->selectByParamsKabupaten(array(), 10, 0, $statement);
                // echo $set->query;exit;
            }
            elseif($reqMode == "kecamatan")
            {
                $term= $this->input->get("term");
                $reqPropinsiId= $this->input->get('reqPropinsiId');
                $reqKabupatenId= $this->input->get('reqKabupatenId');
                $statement.= " AND UPPER(NAMA) LIKE '%".strtoupper(str_replace(" ", "", $term))."%' ";
                if($reqPropinsiId == ""){}
                else
                $statement .= " AND PROPINSI_ID = ".$reqPropinsiId;

                if($reqKabupatenId == ""){}
                else
                $statement .= " AND KABUPATEN_ID = ".$reqKabupatenId;

                // echo $statement;exit;

                $aColumns= array("KECAMATAN_ID", "NAMA");
                $set->selectByParamsKecamatan(array(), 10, 0, $statement);
                // echo $set->query;exit;
            }
            elseif($reqMode == "kelurahan")
            {
                $term= $this->input->get("term");
                $reqPropinsiId= $this->input->get('reqPropinsiId');
                $reqKabupatenId= $this->input->get('reqKabupatenId');
                $reqKecamatanId= $this->input->get('reqKecamatanId');

                $statement.= " AND UPPER(NAMA) LIKE '%".strtoupper(str_replace(" ", "", $term))."%' ";
                if($reqPropinsiId == ""){}
                else
                $statement .= " AND PROPINSI_ID = ".$reqPropinsiId;

                if($reqKabupatenId == ""){}
                else
                $statement .= " AND KABUPATEN_ID = ".$reqKabupatenId;

                if($reqKecamatanId == ""){}
                else
                $statement .= " AND KECAMATAN_ID = ".$reqKecamatanId;
                // echo $statement;exit;

                $aColumns= array("KELURAHAN_ID", "NAMA");
                $set->selectByParamsKelurahan(array(), 10, 0, $statement);
                // echo $set->query;exit;
            }
            elseif($reqMode == "eselon")
            {
                $term= $this->input->get("term");
                $statement= " AND UPPER(A.NAMA) LIKE '".strtoupper($term)."%' ";
                // echo $statement;exit;

                $aColumns= array("ESELON_ID", "NAMA");
                $set->selectByParamsEselon(array(), 10, 0, $statement);
                // echo $set->query;exit;
            }
            elseif($reqMode == "agama")
            {
                $statement= "";

                $aColumns= array("AGAMA_ID", "NAMA");
                $set->selectByParamsAgama(array(), -1,-1, $statement);
            }
            elseif($reqMode == "jenis_pegawai")
            {
                $statement= "";

                $aColumns= array("JENIS_PEGAWAI_ID", "NAMA");
                $set->selectByParamsJenisPegawai(array(), -1,-1, $statement);
            }
            elseif($reqMode == "bank")
            {
                $statement= "";

                $aColumns= array("BANK_ID", "NAMA");
                $set->selectByParamsBank(array(), -1,-1, $statement);
            }
            elseif($reqMode == "namapenghargaan")
            {
                $aColumns= array("REF_PENGHARGAAN_ID", "NAMA","INFO_DETIL");
                $set->selectpenghargaan(array());
                // echo $set->query;exit;
            }
            elseif($reqMode == "penghargaanjenjang")
            {
                $aColumns= array("REF_PENGHARGAAN_JENJANG_ID", "NAMA");
                $set->selectpenghargaanjenjang(array());
                // echo $set->query;exit;
            }
            elseif($reqMode == "jenisdokumen")
            {
                $aColumns= array("ID", "KODE");
                $set->selectbyparamsjenisdokumen(array());
                // echo $set->query;exit;
            }
            elseif($reqMode == "jeniskelamin")
            {
                $aColumns= array("KODE", "NAMA");
                $set->selectbyparamsjeniskelamin(array());
                // echo $set->query;exit;
            }
            elseif($reqMode == "tipekursus")
            {
                $statement= " AND A.TIPE_KURSUS_ID NOT IN (1)";
                $aColumns= array("TIPE_KURSUS_ID", "NAMA");
                $set->selecttipekursus(array(), -1,-1, $statement);
            }
            elseif($reqMode == "jeniskursus")
            {
                $term= $this->input->get("term");
                $statement.= " AND UPPER(A.NAMA) LIKE '%".strtoupper($term)."%' ";
                // echo $statement;exit;
                $aColumns= array("REF_JENIS_KURSUS_ID", "NAMA", "RUMPUN_ID", "RUMPUN_NAMA");
                $set->selectjeniskursus(array(), 70, 0, $statement);
                // echo $set->query;exit;
            }
            elseif($reqMode == "instansi")
            {
                $term= $this->input->get("term");
                $statement.= " AND UPPER(A.NAMA) LIKE '%".strtoupper($term)."%' ";
                // echo $statement;exit;
                $aColumns= array("REF_INSTANSI_ID", "NAMA");
                $set->selectrefinstansi(array(), 70, 0, $statement);
                // echo $set->query;exit;
            }elseif($reqMode == "bidangterkait")
            {
                $term= $this->input->get("term");
                $statement.= " AND UPPER(A.NAMA) LIKE '%".strtoupper($term)."%' ";
                // echo $statement;exit;
                $aColumns= array("BIDANG_TERKAIT_ID", "NAMA","RUMPUN_ID","RUMPUN_NAMA");
                $set->selectbidangterkait(array(), -1, -1, $statement);
                // echo $set->query;exit;
            }
            elseif($reqMode == "rumpun")
            {
                $statement= "";
                $aColumns= array("RUMPUN_ID", "KETERANGAN");
                $set->selectrumpun(array(), -1,-1, $statement);
            }
            elseif($reqMode == "rumpunnilai")
            {
                $infodetilmode= "tipe_kursus";
                $reqRumpunId= -1;
                $reqPermenId= 1;

                $statement= " AND INFOMODE = '".$infodetilmode."' AND A.PERMEN_ID = ".$reqPermenId." AND A.RUMPUN_ID = ".$reqRumpunId;
                $aColumns= array("INFOID", "NILAI");
                $set->selectrumpunnilai(array(), -1,-1, $statement);
            }
            elseif($reqMode == "kualitasfile")
            {
                $statement= "";
                $aColumns= array("KUALITAS_FILE_ID", "NAMA");
                $set->selectkualitasfile(array(), -1,-1, $statement);
            }
            elseif($reqMode == "kondisikategori")
            {
                $vfpeg= new globalvalidasifilepegawai();
                $arrreturn= $vfpeg->tipekondisikategori();
                // print_r($arrreturn);exit;
                $loopdata= 1;
                $row = array();
                $row["data"] = $arrreturn;
            }
            elseif($reqMode == "pilihfiledokumen")
            {
                $vfpeg= new globalvalidasifilepegawai();
                $arrreturn= $vfpeg->pilihfiledokumen();
                // print_r($arrreturn);exit;
                $loopdata= 1;
                $row = array();
                $row["data"] = $arrreturn;
            }
            elseif($reqMode == "setriwayatfield")
            {
                $riwayattable= $this->input->get("riwayattable");
                $vfpeg= new globalvalidasifilepegawai();
                $arrreturn= $vfpeg->setriwayatfield($riwayattable);
                // print_r($arrreturn);exit;
                $loopdata= 1;
                $row = array();
                $row["data"] = $arrreturn;
            }
            elseif($reqMode == "listpilihfilepegawai")
            {
                $riwayattable= $this->input->get("riwayattable");
                $reqId= $this->input->get("reqId");
                $reqRowId= $this->input->get("reqRowId");
                $reqTempValidasiId= $this->input->get("reqTempValidasiId");

                $infopushparam= "";
                $arrdata= $infodetilparam= [];
                if(!empty($reqTempValidasiId))
                {
                    $arrdata["reqTempValidasiId"]= $reqTempValidasiId;
                    $infopushparam= "1";
                }

                if($infopushparam == "1")
                {
                    array_push($infodetilparam, $arrdata);
                    // print_r($infodetilparam);exit;
                }

                $vfpeg= new globalvalidasifilepegawai();
                $arrsetriwayatfield= $vfpeg->setriwayatfield($riwayattable);
                $arrlistriwayatfilepegawai= $vfpeg->listpilihfilepegawai($reqPegawaiId, $riwayattable, $reqRowId, "", "", $infodetilparam);
                // print_r($arrlistriwayatfilepegawai);exit;

                $arrlistpilihfile= $arrlistriwayatfilepegawai["pilihfile"];
                // print_r($arrlistpilihfile);exit;

                $arrlistpilihfilefield= [];
                $reqDokumenPilih= [];
                foreach ($arrsetriwayatfield as $key => $value)
                {
                    $keymodeubah= $keymode= $value["riwayatfield"];
                    if(empty($keymodeubah))
                    {
                        $keymodeubah= "kosong";
                    }
                    $arrlistpilihfilefield[$keymodeubah]= [];

                    if(!empty($arrlistpilihfile))
                    {
                        $arrlistpilihfilefield[$keymodeubah]= $vfpeg->ambilfilemode($arrlistpilihfile, $keymode);

                        $reqDokumenPilih[$keymodeubah]= "";
                        $infocari= "selected";
                        $arraycari= in_array_column($infocari, "selected", $arrlistpilihfilefield[$keymodeubah]);
                        // print_r($arraycari);exit;
                        if(!empty($arraycari))
                        {
                          // print_r($arraycari);exit;
                          $reqDokumenPilih[$keymodeubah]= 2;
                        }
                    }
                }

                $loopdata= 1;
                $rowdata= array();
                // $rowdata[0]["reqDokumenPilih"] = $reqDokumenPilih;
                // $rowdata[0]["arrlistpilihfilefield"] = $arrlistpilihfilefield;
                $rowdata["reqDokumenPilih"] = $reqDokumenPilih;
                $rowdata["arrlistpilihfilefield"] = $arrlistpilihfilefield;
                $row = array();
                // $row["reqDokumenPilih"] = $reqDokumenPilih;
                // $row["arrlistpilihfilefield"] = $arrlistpilihfilefield;
                $row["data"] = $rowdata;
                // print_r($row);exit;
            }
            else if($reqMode == "hapusdata")
            {
                $setdetil= new UserLoginLog();
                $setdetil->selectByParams(array("A.TOKEN" => $reqToken, "A.STATUS" => '1'), -1,-1);
                $setdetil->firstRow();
                // echo $setdetil->query;exit;
                $lastuser= $setdetil->getField("NAMA_PEGAWAI");
                $lastloginpegawaiid= $setdetil->getField("PEGAWAI_ID");

                $reqRowId= $this->input->get("reqRowId");
                $reqTable= $this->input->get("reqTable");
                $reqStatus= $this->input->get("reqStatus");

                $inforeturn= 0;
                if($reqStatus == "1")
                {
                    $set->setField("HAPUS_NAMA", strtoupper($reqTable));
                    $set->setField("PEGAWAI_ID", $reqPegawaiId);
                    $set->setField("TEMP_VALIDASI_ID", $reqRowId);
                    $set->setField("LAST_CREATE_USER", $lastuser);
                    if($set->inserthapusdata())
                        $inforeturn= 1;
                }
                elseif($reqStatus == "2")
                {
                    $set->setField("HAPUS_NAMA", strtoupper($reqTable));
                    $set->setField("TEMP_VALIDASI_ID", $reqRowId);
                    if($set->deletehapusdata())
                        $inforeturn= 1;
                }
                else
                {
                    $set->setField("TABLE", $reqTable);
                    $set->setField("TEMP_VALIDASI_ID", $reqRowId);

                    if($set->hapusdata())
                        $inforeturn= 1;
                }

                $loopdata= 1;
                $row = array();
                $row["data"] = $tempGajiPokok;
            }

            if($loopdata == 0)
            {
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
            }
            else
            {
                $result[] = $row;
            }
            // $query = $this->db->last_query();
            // echo $query;exit;

            if(empty($aColumns))
            {
                $aColumns= [];
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
            $this->load->model('base/PejabatPenetap');

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

            if($reqMode == "penghargaan")
            {
            }
            else if($reqMode == "seminar")
            {
                $this->load->model('base/seminar');

                $reqNamaSeminar= $this->input->post('reqNamaSeminar');
                $reqTempat= $this->input->post('reqTempat');
                $reqTglPiagam= $this->input->post('reqTglPiagam');
                $reqPenyelenggara = $this->input->post('reqPenyelenggara');
                $reqNoPiagam= $this->input->post('reqNoPiagam');
                $reqTglMulai= $this->input->post('reqTglMulai');
                $reqTglSelesai= $this->input->post('reqTglSelesai');

                $set= new Seminar();
                $set->setField('TEMPAT', $reqTempat);   
                $set->setField('PENYELENGGARA', $reqPenyelenggara); 
                $set->setField('TANGGAL_MULAI', dateToDBCheck($reqTglMulai));   
                $set->setField('TANGGAL_SELESAI', dateToDBCheck($reqTglSelesai));   
                $set->setField('NO_PIAGAM', $reqNoPiagam);  
                $set->setField('TANGGAL_PIAGAM', dateToDBCheck($reqTglPiagam)); 
                $set->setField('NAMA', $reqNamaSeminar);
                $set->setField('SEMINAR_ID', ValToNullDB($reqRowId));

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

            }
            else if($reqMode == "kursus")
            {
                $this->load->model('base/kursus');

                $reqNamaKursus= $this->input->post('reqNamaKursus');
                $reqTempat= $this->input->post('reqTempat');
                $reqTglPiagam= $this->input->post('reqTglPiagam');
                $reqPenyelenggara = $this->input->post('reqPenyelenggara');
                $reqNoPiagam= $this->input->post('reqNoPiagam');
                $reqTglMulai= $this->input->post('reqTglMulai');
                $reqTglSelesai= $this->input->post('reqTglSelesai');

                $set= new Kursus();
                $set->setField('TEMPAT', $reqTempat);   
                $set->setField('PENYELENGGARA', $reqPenyelenggara); 
                $set->setField('TANGGAL_MULAI', dateToDBCheck($reqTglMulai));   
                $set->setField('TANGGAL_SELESAI', dateToDBCheck($reqTglSelesai));   
                $set->setField('NO_PIAGAM', $reqNoPiagam);  
                $set->setField('TANGGAL_PIAGAM', dateToDBCheck($reqTglPiagam)); 
                $set->setField('NAMA', $reqNamaKursus);
                $set->setField('KURSUS_ID', ValToNullDB($reqRowId));

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

            }
            else if($reqMode == "tambahan_masa_kerja")
            {
            }
            else if($reqMode == "pendidikanriwayat")
            {
            }
            else if($reqMode == "cuti")
            {
                $this->load->model('base/Cuti');

                $reqNoSurat = $this->input->post('reqNoSurat');
                $reqJenisCuti = $this->input->post('reqJenisCuti');
                $reqTanggalPermohonan = $this->input->post('reqTanggalPermohonan');
                $reqTanggalSurat = $this->input->post('reqTanggalSurat');
                $reqTanggalMulai = $this->input->post('reqTanggalMulai');
                $reqTanggalSelesai = $this->input->post('reqTanggalSelesai');
                $reqCutiKeterangan = $this->input->post('reqCutiKeterangan');
                $reqLama = $this->input->post('reqLama');
                $reqKeterangan = $this->input->post('reqKeterangan');

                $set= new Cuti();
                $set->setField('NO_SURAT', $reqNoSurat);
                $set->setField('JENIS_CUTI', ValToNullDB($reqJenisCuti));
                $set->setField('TANGGAL_PERMOHONAN', dateToDBCheck($reqTanggalPermohonan));
                $set->setField('TANGGAL_SURAT', dateToDBCheck($reqTanggalSurat));
                $set->setField('TANGGAL_MULAI', dateToDBCheck($reqTanggalMulai));
                $set->setField('TANGGAL_SELESAI', dateToDBCheck($reqTanggalSelesai));
                $set->setField('LAMA', ValToNullDB($reqLama));
                $set->setField('CUTI_KETERANGAN', $reqCutiKeterangan);
                $set->setField('KETERANGAN', $reqKeterangan);
                $set->setField('CUTI_ID', ValToNullDB($reqRowId));

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

            }
            else if($reqMode == "gajiriwayat")
            {
            }
            else if($reqMode == "pak")
            {
            }

            else if($reqMode == "pangkatriwayat")
            {
            }
            else if($reqMode == "diklatstruktural")
            {
            }
            else if($reqMode == "diklatteknis")
            {
                $this->load->model('base/DiklatTeknis');

                $reqNamaDiklat= $this->input->post('reqNamaDiklat');
                $reqTahun= $this->input->post('reqTahun');
                $reqNoSttpp= $this->input->post('reqNoSttpp');
                $reqPenyelenggara= $this->input->post('reqPenyelenggara');
                $reqAngkatan= $this->input->post('reqAngkatan');
                $reqTglMulai= $this->input->post('reqTglMulai');
                $reqTglSelesai= $this->input->post('reqTglSelesai');
                $reqTglSttpp= $this->input->post('reqTglSttpp');
                $reqTempat= $this->input->post('reqTempat');
                $reqJumlahJam= $this->input->post('reqJumlahJam');

                $set= new DiklatTeknis();
                $set->setField('TEMPAT', $reqTempat);
                $set->setField('NAMA', $reqNamaDiklat);
                $set->setField('PENYELENGGARA', $reqPenyelenggara);
                $set->setField('ANGKATAN', $reqAngkatan);
                $set->setField('TAHUN', getDay($reqTglSttpp));
                $set->setField('TANGGAL_MULAI', dateToDBCheck($reqTglMulai));
                $set->setField('TANGGAL_SELESAI', dateToDBCheck($reqTglSelesai));
                $set->setField('NO_STTPP', $reqNoSttpp);
                $set->setField('TANGGAL_STTPP', dateToDBCheck($reqTglSttpp));
                $set->setField("JUMLAH_JAM", ValToNullDB(dotToNo($reqJumlahJam)));
                $set->setField('DIKLAT_TEKNIS_ID', ValToNullDB($reqRowId));

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

            }
            else if($reqMode == "diklatfungsional")
            {
                $this->load->model('base/DiklatFungsional');

                $reqNamaDiklat= $this->input->post('reqNamaDiklat');
                $reqTahun= $this->input->post('reqTahun');
                $reqNoSttpp= $this->input->post('reqNoSttpp');
                $reqPenyelenggara= $this->input->post('reqPenyelenggara');
                $reqAngkatan= $this->input->post('reqAngkatan');
                $reqTglMulai= $this->input->post('reqTglMulai');
                $reqTglSelesai= $this->input->post('reqTglSelesai');
                $reqTglSttpp= $this->input->post('reqTglSttpp');
                $reqTempat= $this->input->post('reqTempat');
                $reqJumlahJam= $this->input->post('reqJumlahJam');

                $set= new DiklatFungsional();
                $set->setField('TEMPAT', $reqTempat);
                $set->setField('NAMA', $reqNamaDiklat);
                $set->setField('PENYELENGGARA', $reqPenyelenggara);
                $set->setField('ANGKATAN', $reqAngkatan);
                $set->setField('TAHUN', getDay($reqTglSttpp));
                $set->setField('TANGGAL_MULAI', dateToDBCheck($reqTglMulai));
                $set->setField('TANGGAL_SELESAI', dateToDBCheck($reqTglSelesai));
                $set->setField('NO_STTPP', $reqNoSttpp);
                $set->setField('TANGGAL_STTPP', dateToDBCheck($reqTglSttpp));
                $set->setField("JUMLAH_JAM", ValToNullDB(dotToNo($reqJumlahJam)));
                $set->setField('DIKLAT_FUNGSIONAL_ID', ValToNullDB($reqRowId));

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

            }
            else if($reqMode == "skp")
            {
            }
            else if($reqMode == "jabatan_tambahan")
            {
            }
            else if($reqMode == "orang_tua")
            {
            }
            else if($reqMode == "jabatanriwayat")
            {
            }
            else if($reqMode == "surat_tanda_lulus")
            {
            }
            else if($reqMode == "mertua")
            {
            }
            else if($reqMode == "pns")
            {
            }
            else if($reqMode == "skpppk")
            {
            }
            else if($reqMode == "pegawai_personal")
            {
                $this->load->model('base/Pegawai');

                $reqStatus = $this->input->post("reqStatus");
                $reqSatuanKerjaId = $this->input->post("reqSatuanKerjaId");
                $reqJenisPegawai = $this->input->post("reqJenisPegawai");
                $reqTipePegawai = $this->input->post("reqTipePegawai");
                $reqStatusPegawai = $this->input->post("reqStatusPegawai");
                $reqNipLama = $this->input->post("reqNipLama");
                $reqNipBaru = $this->input->post("reqNipBaru");
                $reqNama = $this->input->post("reqNama");
                $reqGelarDepan = $this->input->post("reqGelarDepan");
                $reqGelarBelakang = $this->input->post("reqGelarBelakang");
                $reqTempatLahir = $this->input->post("reqTempatLahir");
                $reqTanggalLahir = $this->input->post("reqTanggalLahir");
                $reqJenisKelamin = $this->input->post("reqJenisKelamin");
                $reqStatusKawin = $this->input->post("reqStatusKawin");
                $reqSukuBangsa = $this->input->post("reqSukuBangsa");
                $reqGolonganDarah = $this->input->post("reqGolonganDarah");
                $reqEmail = $this->input->post("reqEmail");
                $reqAlamat = $this->input->post("reqAlamat");
                $reqRt = $this->input->post("reqRt");
                $reqRw = $this->input->post("reqRw");
                $reqKodePos = $this->input->post("reqKodePos");
                $reqTelepon = $this->input->post("reqTelepon");
                $reqHp = $this->input->post("reqHp");
                $reqKartuPegawai = $this->input->post("reqKartuPegawai");
                $reqAskes = $this->input->post("reqAskes");
                $reqTaspen = $this->input->post("reqTaspen");
                $reqNpwp = $this->input->post("reqNpwp");
                $reqNik = $this->input->post("reqNik");
                $reqNoRekening = $this->input->post("reqNoRekening");
                $reqSkKonversiNip = $this->input->post("reqSkKonversiNip");
                $reqBank = $this->input->post("reqBank");
                $reqAgama = $this->input->post("reqAgama");
                $reqPegawaiKedudukanNama = $this->input->post("reqPegawaiKedudukanNama");
                $reqUrut= $this->input->post("reqUrut");
                $reqNoKk= $this->input->post("reqNoKk");
                $reqNoRakBerkas= $this->input->post("reqNoRakBerkas");
                $reqTeleponKantor= $this->input->post("reqTeleponKantor");
                $reqFacebook= $this->input->post("reqFacebook");
                $reqTwitter= $this->input->post("reqTwitter");
                $reqWhatsApp= $this->input->post("reqWhatsApp");
                $reqTelegram= $this->input->post("reqTelegram");
                $reqKeterangan1= $this->input->post("reqKeterangan1");
                $reqKeterangan2= $this->input->post("reqKeterangan2");
                $reqDesaId= $this->input->post("reqDesaId");
                $reqKecamatanId= $this->input->post("reqKecamatanId");
                $reqKabupatenId= $this->input->post("reqKabupatenId");
                $reqPropinsiId= $this->input->post("reqPropinsiId");
                $reqJenisPegawaiId= $this->input->post("reqJenisPegawaiId");
                $reqBpjs= $this->input->post("reqBpjs");
                $reqBpjsTanggal= $this->input->post("reqBpjsTanggal");
                $reqNpwpTanggal= $this->input->post("reqNpwpTanggal");
                $reqAlamatKeterangan= $this->input->post("reqAlamatKeterangan");
                $reqEmailKantor= $this->input->post("reqEmailKantor");
                $reqRekeningNama= $this->input->post("reqRekeningNama");
                $reqGajiPokok= $this->input->post("reqGajiPokok");
                $reqTunjangan= $this->input->post("reqTunjangan");
                $reqTunjanganKeluarga= $this->input->post("reqTunjanganKeluarga");
                $reqGajiBersih= $this->input->post("reqGajiBersih");
                $reqStatusMutasi= $this->input->post("reqStatusMutasi");
                $reqTmtMutasi= $this->input->post("reqTmtMutasi");
                $reqInstansiSebelum= $this->input->post("reqInstansiSebelum");
                $reqPegawaiStatus= $this->input->post("reqPegawaiStatus");

                $set= new Pegawai();
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

                $set->setField('STATUS_PEGAWAI_ID', ValToNullDB($reqStatusPegawai));
                $set->setField('PEGAWAI_STATUS_ID', ValToNullDB($reqPegawaiStatus));

                $set->setField('PROPINSI_ID', ValToNullDB($reqPropinsiId));
                $set->setField('KABUPATEN_ID', ValToNullDB($reqKabupatenId));
                $set->setField('KECAMATAN_ID', ValToNullDB($reqKecamatanId));
                $set->setField('DESA_ID', ValToNullDB($reqDesaId));

                $set->setField('JENIS_PEGAWAI_ID', ValToNullDB($reqJenisPegawaiId));

                $set->setField('BPJS', $reqBpjs);
                $set->setField('BPJS_TANGGAL', dateToDBCheck($reqBpjsTanggal));
                $set->setField('NPWP_TANGGAL', dateToDBCheck($reqNpwpTanggal));
                $set->setField('ALAMAT_KETERANGAN', $reqAlamatKeterangan);
                $set->setField('PEGAWAI_ID', ValToNullDB($reqRowId));
                
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

            }
            else if($reqMode == "reset_password")
            {
                $this->load->model('base/UserLoginPersonal');

                $reqPasswordBaru= $this->input->post('reqPasswordBaru');
                $reqId = $this->input->post("reqId");
                $reqLoginUser = $this->input->post("reqLoginUser");
                $reqRowId = $this->input->post("reqRowId");
                $reqNama = $this->input->post("reqNama");
                $reqSatkerId = $this->input->post("reqSatkerId");

                $validasi=preg_match('#^(?=(.*[A-Za-z]){6})(?=[A-Za-z]*\d)[A-Za-z0-9]{7,}$#',$reqPasswordBaru);

                if(!$validasi) 
                {
                    $this->response(array('status' => 'gagal','message' => 'Password harus kombinasi huruf dan angka, Huruf minimal 6 karakter dan angka minimal 1 karakter ', 'code' =>  502));
                }
  

                $set_data = new UserLoginPersonal();
                $set_data->setField('LOGIN_PASS', md5($reqPasswordBaru));
                $set_data->setField("LOGIN_USER", $reqLoginUser);
                $set_data->setField("LAST_USER", $reqNama);
                $set_data->setField("PEGAWAI_ID", $reqRowId);
                $set_data->setField("STATUS", 1);
                $set_data->setField("LAST_DATE", "NOW()");
                $set_data->setField("SATUAN_KERJA_ID", $reqSatkerId);

                $set_check = new UserLoginPersonal();
                $statement=" AND A.PEGAWAI_ID=".$reqRowId;
                $set_check->selectByParamsLogin(array(),-1,-1,$statement);
                $set_check->firstRow();
                $reqPegawaiId= $set_check->getField('PEGAWAI_ID');

                if(empty($reqPegawaiId))
                {
                    if($set_data->insertpass())
                    {
                       $reqsimpan= "1";
                       $reqTempValidasiId= "1";
                    }
                    // $this->response(array('status' => 'gagal','message' => $set_data->query, 'code' =>  502));
                }
                else
                {
                   if($set_data->resetPassword())
                   {
                       $reqsimpan= "1";
                       $reqTempValidasiId= "1";
                   }

                }
               
                if($reqsimpan !== "1")
                {
                    $reqTempValidasiId= "";
                }
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