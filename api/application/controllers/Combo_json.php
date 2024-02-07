<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Combo_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        // $this->db->query("alter session set nls_date_format='DD-MM-YYYY'"); 
        
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base/Combo');
        $this->load->model('base/SatuanKerja');

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
            elseif($reqMode == "pendidikan")
            {
                $statement= "";

                $aColumns= array("PENDIDIKAN_ID", "NAMA");
                $set->selectByParamsPendidikan(array(), -1,-1, $statement);
            }
            elseif($reqMode == "pendidikan_pppk")
            {
                $statement= " AND A.STATUS IS NULL AND A.PPPK_STATUS = 1 AND A.PEGAWAI_ID = ".$reqPegawaiId;


                $aColumns= array("PENDIDIKAN_RIWAYAT_ID", "PENDIDIKAN_NAMA");
                $set->selectByParamsPendidikanRiwayat(array(), -1,-1, $statement);
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

            if($reqMode == "cpns")
            {
                $this->load->model('base/SkCpns');

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

                $set= new SkCpns();
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

            }
            else if($reqMode == "bahasa")
            {
                $this->load->model('base/Bahasa');

                $reqNamaBahasa= $this->input->post('reqNamaBahasa');
                $reqJenisBahasa= $this->input->post('reqJenisBahasa');
                $reqKemampuanBicara= $this->input->post('reqKemampuanBicara');

                $set= new Bahasa();
                $set->setField('JENIS', $reqJenisBahasa);
                $set->setField('NAMA', $reqNamaBahasa);
                $set->setField('KEMAMPUAN', $reqKemampuanBicara);
                $set->setField('BAHASA_ID', ValToNullDB($reqRowId));

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
            else if($reqMode == "anak")
            { 
                $this->load->model('base/Anak');

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

            }
            else if($reqMode == "penghargaan")
            {
                $this->load->model('base/Penghargaan');

                $reqNamaPenghargaan= $this->input->post('reqNamaPenghargaan');
                $reqTahun= $this->input->post('reqTahun');
                $reqTglSK= $this->input->post('reqTglSK');
                $reqNoSK= $this->input->post('reqNoSK');
                $reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
                $reqPejabatPenetapId= $this->input->post("reqPejabatPenetapId");

                $set= new Penghargaan();
                $set->setField('PEJABAT_PENETAP_ID', ValToNullDB($reqPejabatPenetapId));    
                $set->setField('PEJABAT_PENETAP', $reqPejabatPenetap);  
                $set->setField('NAMA', $reqNamaPenghargaan);    
                $set->setField('NO_SK', $reqNoSK);  
                $set->setField('TANGGAL_SK', dateToDBCheck($reqTglSK)); 
                $set->setField('TAHUN', ValToNullDB($reqTahun));    
                $set->setField('PENGHARGAAN_ID', ValToNullDB($reqRowId));

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

                // $tes = $set->query;

                if($reqsimpan !== "1")
                {
                    $reqTempValidasiId= "";
                }

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
                $this->load->model('base/Tambahanmasakerja');

                $reqNoSK= $this->input->post('reqNoSK');
                $reqTanggalSk= $this->input->post('reqTanggalSk');
                $reqTmtSk= $this->input->post('reqTmtSk');
                $reqTahunTambahan= $this->input->post('reqTahunTambahan');
                $reqBulanTambahan= $this->input->post('reqBulanTambahan');
                $reqTahunBaru= $this->input->post('reqTahunBaru');
                $reqBulanBaru= $this->input->post('reqBulanBaru');
                $reqGolRuang= $this->input->post('reqGolRuang');
                $reqNoNota= $this->input->post('reqNoNota');
                $reqTglNota= $this->input->post('reqTglNota');
                $reqGajiPokok= $this->input->post('reqGajiPokok');
                $reqPejabatPenetapId= $this->input->post('reqPejabatPenetapId');
                $reqPejabatPenetap= $this->input->post('reqPejabatPenetap');

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

                $set= new Tambahanmasakerja();
                $set->setField('PANGKAT_ID', $reqGolRuang);
                $set->setField('PEJABAT_PENETAP_ID', $reqPejabatPenetapId);
                $set->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
                $set->setField('NO_NOTA', $reqNoNota);
                $set->setField('TANGGAL_NOTA', dateToDBCheck($reqTglNota));
                $set->setField("GAJI_POKOK", ValToNullDB(dotToNo($reqGajiPokok)));
                $set->setField('NO_SK', $reqNoSK);
                $set->setField('TANGGAL_SK', dateToDBCheck($reqTanggalSk));     
                $set->setField('TMT_SK', dateToDBCheck($reqTmtSk));
                $set->setField('TAHUN_TAMBAHAN', $reqTahunTambahan);
                $set->setField('BULAN_TAMBAHAN', $reqBulanTambahan);
                $set->setField('TAHUN_BARU', $reqTahunBaru);
                $set->setField('BULAN_BARU', $reqBulanBaru);
                $set->setField('TAMBAHAN_MASA_KERJA_ID', ValToNullDB($reqRowId));

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
            else if($reqMode == "pendidikanriwayat")
            {
                $this->load->model('base/PendidikanRiwayat');

                $reqNamaSekolah= $this->input->post('reqNamaSekolah');
                $reqNamaFakultas= $this->input->post('reqNamaFakultas');
                $reqPendidikanId= $this->input->post('reqPendidikanId');
                $reqTglSttb= $this->input->post('reqTglSttb');
                $reqJurusan= $this->input->post('reqJurusan');
                $reqJurusanId= $this->input->post('reqJurusanId');
                $reqAlamatSekolah= $this->input->post('reqAlamatSekolah');
                $reqKepalaSekolah= $this->input->post('reqKepalaSekolah');
                $reqNoSttb= $this->input->post('reqNoSttb');
                $reqStatusTugasIjinBelajar= $this->input->post('reqStatusTugasIjinBelajar');
                $reqStatusPendidikan= $this->input->post('reqStatusPendidikan');
                $reqNoSuratIjin= $this->input->post('reqNoSuratIjin');
                $reqTglSuratIjin= $this->input->post('reqTglSuratIjin');
                $reqGelarTipe= $this->input->post('reqGelarTipe');
                $reqGelarNamaDepan= $this->input->post('reqGelarNamaDepan');
                $reqGelarNama= $this->input->post('reqGelarNama');
                $reqPppkStatus= $this->input->post('reqPppkStatus');

                $set= new PendidikanRiwayat();
                $set->setField('PPPK_STATUS', ValToNullDB($reqPppkStatus));
                $set->setField('NAMA', $reqNamaSekolah);
                $set->setField('NAMA_FAKULTAS', $reqNamaFakultas);
                $set->setField('PENDIDIKAN_ID', $reqPendidikanId);
                $set->setField('TANGGAL_STTB', dateToDBCheck($reqTglSttb));
                $set->setField('JURUSAN', $reqJurusan);
                $set->setField('PENDIDIKAN_JURUSAN_ID', ValToNullDB($reqJurusanId));
                $set->setField('TEMPAT', $reqAlamatSekolah);
                $set->setField('KEPALA', $reqKepalaSekolah);
                $set->setField('NO_STTB', $reqNoSttb);
                $set->setField('STATUS_TUGAS_IJIN_BELAJAR', ValToNullDB($reqStatusTugasIjinBelajar));
                $set->setField('STATUS_PENDIDIKAN', $reqStatusPendidikan);
                $set->setField('NO_SURAT_IJIN', $reqNoSuratIjin);
                $set->setField('TANGGAL_SURAT_IJIN', dateToDBCheck($reqTglSuratIjin));
                $set->setField('GELAR_TIPE', $reqGelarTipe);
                $set->setField('GELAR_DEPAN', $reqGelarNamaDepan);
                $set->setField('GELAR_NAMA', $reqGelarNama);
                $set->setField('PENDIDIKAN_RIWAYAT_ID', ValToNullDB($reqRowId));

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
                $this->load->model('base/GajiRiwayat');

                $reqNoSk= $this->input->post("reqNoSk");
                $reqTanggalSk= $this->input->post("reqTanggalSk");
                $reqGolRuang= $this->input->post("reqGolRuang");
                $reqTmtSk= $this->input->post("reqTmtSk");
                $reqTh= $this->input->post('reqTh');
                $reqTempTh= $this->input->post('reqTempTh');
                $reqBl= $this->input->post('reqBl');
                $reqTempBl= $this->input->post('reqTempBl');
                $reqPejabatPenetapId= $this->input->post('reqPejabatPenetapId');
                $reqPejabatPenetap= $this->input->post('reqPejabatPenetap');
                $reqGajiPokok= $this->input->post("reqGajiPokok");
                $reqJenis= $this->input->post("reqJenis");

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

                $set= new GajiRiwayat();
                $set->setField('JENIS_KENAIKAN', $reqJenis);
                $set->setField('NO_SK', $reqNoSk);
                $set->setField('PANGKAT_ID', $reqGolRuang);
                $set->setField('TANGGAL_SK', dateToDBCheck($reqTanggalSk));
                $set->setField("GAJI_POKOK", ValToNullDB(dotToNo($reqGajiPokok)));
                $set->setField('MASA_KERJA_TAHUN', ValToNullDB($reqTh));
                $set->setField('MASA_KERJA_BULAN', ValToNullDB($reqBl));
                $set->setField('PEJABAT_PENETAP_ID', $reqPejabatPenetapId);
                $set->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
                $set->setField('TMT_SK', dateToDBCheck($reqTmtSk));
                $set->setField('BULAN_DIBAYAR', dateToDBCheck($reqTanggalSk));
                $set->setField('GAJI_RIWAYAT_ID', ValToNullDB($reqRowId));

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
            else if($reqMode == "pak")
            {
                $this->load->model('base/Pak');

                $reqJabatanFtId= $this->input->post('reqJabatanFtId');
                $reqCheckPakAwal= $this->input->post('reqCheckPakAwal');
                $reqNoSK= $this->input->post('reqNoSK');
                $reqTglMulai= $this->input->post('reqTglMulai');
                $reqTglSelesai= $this->input->post('reqTglSelesai');
                $reqTglSK= $this->input->post('reqTglSK');
                $reqKreditUtama= $this->input->post('reqKreditUtama');
                $reqKreditPenunjang= $this->input->post('reqKreditPenunjang');
                $reqTotalKredit= $this->input->post('reqTotalKredit');

                $set= new Pak();
                $set->setField('JABATAN_FT_ID', ValToNullDB($reqJabatanFtId));   
                $set->setField('TANGGAL_MULAI', dateToDBCheck($reqTglMulai));
                $set->setField('TANGGAL_SELESAI', dateToDBCheck($reqTglSelesai));
                $set->setField('NO_SK', $reqNoSK);
                $set->setField('PAK_AWAL', $reqCheckPakAwal);
                $set->setField('PERIODE_AWAL', ValToNullDB($req));  
                $set->setField('PERIODE_AKHIR', ValToNullDB($req)); 
                $set->setField('TANGGAL_SK', dateToDBCheck($reqTglSK));
                $set->setField('KREDIT_UTAMA', ValToNullDB(CommaToDot($reqKreditUtama)));
                $set->setField('KREDIT_PENUNJANG', ValToNullDB(CommaToDot($reqKreditPenunjang)));
                $set->setField('TOTAL_KREDIT', ValToNullDB(CommaToDot($reqTotalKredit)));
                $set->setField('PAK_ID', ValToNullDB($reqRowId));

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

            else if($reqMode == "pangkatriwayat")
            {
                $this->load->model('base/PangkatRiwayat');

                $reqTglStlud= $this->input->post('reqTglStlud');
                $reqStlud= $this->input->post('reqStlud');
                $reqNoStlud= $this->input->post('reqNoStlud');
                $reqNoNota= $this->input->post('reqNoNota');
                $reqNoSk= $this->input->post('reqNoSk');
                $reqNoUrutCetak= $this->input->post('reqNoUrutCetak');
                $reqTh= $this->input->post('reqTh');
                $reqTempTh= $this->input->post('reqTempTh');
                $reqBl= $this->input->post('reqBl');
                $reqTempBl= $this->input->post('reqTempBl');
                $reqKredit= $this->input->post('reqKredit');
                $reqJenisKp= $this->input->post('reqJenisKp');
                $reqKeterangan= $this->input->post('reqKeterangan');
                $reqGajiPokok= $this->input->post('reqGajiPokok');
                $reqTglNota= $this->input->post('reqTglNota');
                $reqTglSk= $this->input->post('reqTglSk');
                $reqTmtGol= $this->input->post('reqTmtGol');
                $reqPejabatPenetapId= $this->input->post('reqPejabatPenetapId');
                $reqPejabatPenetap= $this->input->post('reqPejabatPenetap');
                $reqGolRuang= $this->input->post('reqGolRuang');

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

                $set= new PangkatRiwayat();
                $set->setField('PANGKAT_ID', $reqGolRuang);
                $set->setField('PEJABAT_PENETAP_ID', $reqPejabatPenetapId);
                $set->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
                $set->setField('STLUD', $reqStlud);
                $set->setField('NO_STLUD', $reqNoStlud);
                $set->setField('NO_NOTA', $reqNoNota);
                $set->setField('NO_SK', $reqNoSk);
                $set->setField('NO_URUT_CETAK', ValToNullDB($reqNoUrutCetak));
                $set->setField('MASA_KERJA_TAHUN', ValToNullDB($reqTh));
                $set->setField('MASA_KERJA_BULAN', ValToNullDB($reqBl));
                $set->setField("GAJI_POKOK", ValToNullDB(dotToNo($reqGajiPokok)));
                $set->setField('KREDIT', ValToNullDB(CommaToDot($reqKredit)));
                $set->setField('KETERANGAN', $reqKeterangan);
                $set->setField('JENIS_RIWAYAT', $reqJenisKp);
                $set->setField('TANGGAL_STLUD', dateToDBCheck($reqTglStlud));
                $set->setField('TANGGAL_NOTA', dateToDBCheck($reqTglNota));
                $set->setField('TANGGAL_SK', dateToDBCheck($reqTglSk));
                $set->setField('TMT_PANGKAT', dateToDBCheck($reqTmtGol));
                $set->setField('PANGKAT_RIWAYAT_ID', ValToNullDB($reqRowId));

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
                if($reqPeriode == ""){}
                else
                {
                    if($reqJenisKp == 8 || $reqJenisKp == 9){}
                        else
                        {
                            $statement= " AND A.PEGAWAI_ID = ".$reqId." AND A.STATUS_KGB IN ('2','3') AND A.PERIODE = '".$reqPeriode."'";
                            $set_kgb= new KenaikanGajiBerkala();
                            $set_kgb->selectByParamsData(array(), -1,-1, $statement);
                            $set_kgb->firstRow();
                            $tempDasarTanggalVal= $tempDasarTanggal= dateToPageCheck($set_kgb->getField("TANGGAL_SK"));
                            $tempDasarTmtVal= $tempDasarTmt= dateToPageCheck($set_kgb->getField("TMT_LAMA"));
                            $tempKgbTanggal= dateToPageCheck($set_kgb->getField("TANGGAL_BARU"));
                            $tempKgbTmt= dateToPageCheck($set_kgb->getField("TMT_BARU"));

                            $tempDasarTanggal= strtotime($tempDasarTanggal);
                            $tempDasarTmt= strtotime($tempDasarTmt);
                            $tempKgbTanggal= strtotime($tempKgbTanggal);
                            $tempKgbTmt= strtotime($tempKgbTmt);
                            $tempDataBaruTanggal= strtotime($reqTglSk);
                            $tempDataBaruTmt= strtotime($reqTmtGol);

                            if($tempDasarTmt < $tempDataBaruTmt && $tempDataBaruTmt < $tempKgbTmt && $tempDasarTanggal < $tempDataBaruTanggal)
                                $reqstatushitung= "1";
                            if($reqTh == $reqTempTh){}
                                else
                                    $reqstatushitung= "1";
                                if($reqBl == $reqTempBl){}
                                    else
                                        $reqstatushitung= "1";
                                    if($tempDasarTanggalVal == $reqTglSk && $tempDasarTmtVal == $reqTmtGol)
                                        $reqstatushitung= "1";
                        }
                }

                if($reqsimpan == "1")
                {
                    if($reqstatushitung == "1")
                    {
                        $set = new KenaikanGajiBerkala();
                        $set->setField('PERIODE', $reqPeriode);
                        $set->setField('PEGAWAI_ID', $reqId);
                        $set->setField("LAST_LEVEL", $lastlevel);
                        $set->setField("LAST_USER", $lastuser);
                        $set->setField("USER_LOGIN_ID", ValToNullDB($lastloginid));
                        $set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($lastloginpegawaiid));
                        $set->setField("LAST_DATE", "NOW()");
                        $set->updateStatusHitung();
                    }
                }

                if($reqsimpan !== "1")
                {
                    $reqTempValidasiId= "";
                }

            }
            else if($reqMode == "diklatstruktural")
            {
                $this->load->model('base/DiklatStruktural');

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
                $this->load->model('base/PenilaianSkp');

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

            else if($reqMode == "jabatan_tambahan")
            {
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

            }

            else if($reqMode == "orang_tua")
            {
                $this->load->model('base/OrangTua');

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

            }
            else if($reqMode == "jabatanriwayat")
            {
                $this->load->model('base/JabatanRiwayat');
                $this->load->model('base/JabatanFu');
                $this->load->model('base/JabatanFt');

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

                if($reqsimpan !== "1")
                {
                    $reqTempValidasiId= "";
                }

            }
            else if($reqMode == "surat_tanda_lulus")
            {
                $this->load->model('base/SuratTandaLulus');

                $reqJenisId= $this->input->post('reqJenisId');
                $reqNoStlud= $this->input->post('reqNoStlud');
                $reqTglStlud= $this->input->post('reqTglStlud');
                $reqNilaiNpr= $this->input->post('reqNilaiNpr');
                $reqNilaiNt= $this->input->post('reqNilaiNt');
                $reqTanggalMulai= $this->input->post('reqTanggalMulai');
                $reqTanggalAkhir= $this->input->post('reqTanggalAkhir');
                $reqPendidikanRiwayatId= $this->input->post('reqPendidikanRiwayatId');
                $reqPendidikanId= $this->input->post('reqPendidikanId');

                $set= new SuratTandaLulus();
                $set->setField('PENDIDIKAN_RIWAYAT_ID', ValToNullDB($reqPendidikanRiwayatId));
                $set->setField('PENDIDIKAN_ID', ValToNullDB($reqPendidikanId));
                $set->setField("JENIS_ID", $reqJenisId);
                $set->setField("NO_STLUD", $reqNoStlud);
                $set->setField('TANGGAL_STLUD', dateToDBCheck($reqTglStlud));
                $set->setField('TANGGAL_MULAI', dateToDBCheck($reqTanggalMulai));
                $set->setField('TANGGAL_AKHIR', dateToDBCheck($reqTanggalAkhir));
                $set->setField('NILAI_NPR', ValToNullDB(CommaToDot($reqNilaiNpr)));
                $set->setField('NILAI_NT', ValToNullDB(CommaToDot($reqNilaiNt)));
                $set->setField('SURAT_TANDA_LULUS_ID', ValToNullDB($reqRowId));

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

            else if($reqMode == "mertua")
            {
                $this->load->model('base/Mertua');

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

                $set= new Mertua();
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
                $set->setField('MERTUA_ID', ValToNullDB($reqIdAyah));

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
                    $set->setField('MERTUA_ID', ValToNullDB($reqIdIbu));

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

            }

            else if($reqMode == "pns")
            {
                $this->load->model('base/SkPns');

                $reqPeriode= $this->input->post('reqPeriode');
                $reqPejabatPenetapId= $this->input->post("reqPejabatPenetapId");
                $reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
                $reqNamaPejabatPenetap= $this->input->post("reqNamaPejabatPenetap");
                $reqNipPejabatPenetap= $this->input->post("reqNipPejabatPenetap");
                $reqNoSuratKeputusan= $this->input->post("reqNoSuratKeputusan");
                $reqTanggalSuratKeputusan= $this->input->post("reqTanggalSuratKeputusan");
                $reqTerhitungMulaiTanggal= $this->input->post("reqTerhitungMulaiTanggal");
                $reqGolRuang= $this->input->post("reqGolRuang");
                $reqTanggalTugas= $this->input->post("reqTanggalTugas");
                $reqNoDiklatPrajabatan= $this->input->post("reqNoDiklatPrajabatan");
                $reqTanggalDiklatPrajabatan= $this->input->post("reqTanggalDiklatPrajabatan");
                $reqNoSuratUjiKesehatan= $this->input->post("reqNoSuratUjiKesehatan");
                $reqTanggalSuratUjiKesehatan= $this->input->post("reqTanggalSuratUjiKesehatan");
                $reqPengambilanSumpah= $this->input->post("reqPengambilanSumpah");
                $reqSkPnsId= $this->input->post("reqSkPnsId");
                $reqTanggalSumpah= $this->input->post("reqTanggalSumpah");
                $reqTh= $this->input->post("reqTh");
                $reqBl= $this->input->post("reqBl");
                $reqNoBeritaAcara= $this->input->post("reqNoBeritaAcara");
                $reqTanggalBeritaAcara= $this->input->post("reqTanggalBeritaAcara");
                $reqKeteranganLpj= $this->input->post("reqKeteranganLpj");
                $reqGajiPokok= $this->input->post("reqGajiPokok");
                $reqJenisJabatanId= $this->input->post("reqJenisJabatanId");
                $reqStatusCalonJft= $this->input->post("reqStatusCalonJft");
                $reqJabatanTugas= $this->input->post("reqJabatanTugas");
                $reqJabatanFuId= $this->input->post("reqJabatanFuId");
                $reqJabatanFtId= $this->input->post("reqJabatanFtId");
                $reqJalurPengangkatan= $this->input->post("reqJalurPengangkatan");

                $set= new SkPns();
                $set->setField("JENIS_JABATAN_ID", ValToNullDB($reqJenisJabatanId));
                $set->setField("STATUS_CALON_JFT", ValToNullDB($reqStatusCalonJft));
                $set->setField("JABATAN_TUGAS", $reqJabatanTugas);
                $set->setField("JABATAN_FU_ID", ValToNullDB($reqJabatanFuId));
                $set->setField("JABATAN_FT_ID", ValToNullDB($reqJabatanFtId));
                $set->setField("JALUR_PENGANGKATAN", ValToNullDB($reqJalurPengangkatan));

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
                $set->setField('PEJABAT_PENETAP_ID', $reqPejabatPenetapId);
                $set->setField('PEJABAT_PENETAP_SUMPAH_ID', ValToNullDB($req));
                $set->setField('TMT_PNS', dateToDBCheck($reqTerhitungMulaiTanggal));
                $set->setField('TANGGAL_TUGAS', dateToDBCheck($reqTanggalTugas)); 
                $set->setField('NO_STTPP', '');
                $set->setField('PEGAWAI_ID', $reqPegawaiId);
                $set->setField('NO_NOTA', $reqNoNotaBAKN);
                $set->setField('TANGGAL_NOTA', dateToDBCheck($reqTanggalNotaBAKN));
                $set->setField('NO_SK', $reqNoSuratKeputusan);
                $set->setField('TANGGAL_STTPP', dateToDBCheck($reqTanggalTugas));
                $set->setField('NAMA_PENETAP', $reqNamaPejabatPenetap);
                $set->setField('TANGGAL_SK', dateToDBCheck($reqTanggalSuratKeputusan));
                $set->setField('NIP_PENETAP', $reqNipPejabatPenetap);
                $set->setField('NO_PRAJAB',$reqNoDiklatPrajabatan);
                $set->setField('NO_UJI_KESEHATAN',$reqNoSuratUjiKesehatan);     
                $set->setField('SK_PNS_ID',$reqSkPnsId);
                $set->setField('TANGGAL_UJI_KESEHATAN', dateToDBCheck($reqTanggalSuratUjiKesehatan));
                $set->setField('TANGGAL_PRAJAB', dateToDBCheck($reqTanggalDiklatPrajabatan));
                $set->setField('TANGGAL_SUMPAH', dateToDBCheck($reqTanggalSumpah));
                $set->setField('SUMPAH', (int)$reqPengambilanSumpah);
                $set->setField('MASA_KERJA_TAHUN', ValToNullDB($reqTh));
                $set->setField('MASA_KERJA_BULAN', ValToNullDB($reqBl));
                $set->setField("GAJI_POKOK", ValToNullDB(dotToNo($reqGajiPokok)));
                $set->setField('NOMOR_BERITA_ACARA',$reqNoBeritaAcara);
                $set->setField('TANGGAL_BERITA_ACARA', dateToDBCheck($reqTanggalBeritaAcara));
                $set->setField('KETERANGAN_LPJ',$reqKeteranganLpj);
                $set->setField('SK_PNS_ID',$reqRowId);

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
            else if($reqMode == "skpppk")
            {
                $this->load->model('base/SkPppk');

                $reqNoNotaBakn= $this->input->post("reqNoNotaBakn");
                $reqTanggalNotaBakn= $this->input->post("reqTanggalNotaBakn");
                $reqPejabatPenetapId= $this->input->post("reqPejabatPenetapId");
                $reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
                $reqNamaPejabatPenetap= $this->input->post("reqNamaPejabatPenetap");
                $reqNipPejabatPenetap= $this->input->post("reqNipPejabatPenetap");
                $reqNoSuratKeputusan= $this->input->post("reqNoSuratKeputusan");
                $reqTanggalSuratKeputusan= $this->input->post("reqTanggalSuratKeputusan");
                $reqTerhitungMulaiTanggal= $this->input->post("reqTerhitungMulaiTanggal");
                $reqGolonganPppkId= $this->input->post("reqGolonganPppkId");
                $reqTanggalTugas= $this->input->post("reqTanggalTugas");
                $reqSkcpnsId= $this->input->post("reqSkcpnsId");
                $reqTh= $this->input->post("reqTh");
                $reqBl= $this->input->post("reqBl");
                $reqNoPersetujuanNip= $this->input->post("reqNoPersetujuanNip");
                $reqTanggalPersetujuanNip= $this->input->post("reqTanggalPersetujuanNip");
                $reqPendidikan= $this->input->post("reqPendidikan");
                $reqJurusan= $this->input->post("reqJurusan");
                $reqGajiPokok= $this->input->post("reqGajiPokok");
                $reqFormasiPppkId= $this->input->post("reqFormasiPppkId");
                $reqJabatanTugas= $this->input->post("reqJabatanTugas");

                $reqJenisFormasiTugasId= $this->input->post("reqJenisFormasiTugasId");
                $reqJabatanFuId= $this->input->post("reqJabatanFuId");
                $reqJabatanFtId= $this->input->post("reqJabatanFtId");
                $reqStatusSkPppk= $this->input->post("reqStatusSkPppk");
                $reqSpmtNomor= $this->input->post("reqSpmtNomor");
                $reqSpmtTanggal= $this->input->post("reqSpmtTanggal");
                $reqSpmtTmt= $this->input->post("reqSpmtTmt");
                $reqNipPPPK= $this->input->post("reqNipPPPK");
                $reqPendidikanRiwayatId= $this->input->post("reqPendidikanRiwayatId");

                $set= new SkPppk();
                $set->setField("PENDIDIKAN_RIWAYAT_ID", ValToNullDB($reqPendidikanRiwayatId));
                $set->setField("JENIS_FORMASI_TUGAS_ID", ValToNullDB($reqJenisFormasiTugasId));
                $set->setField("JABATAN_FU_ID", ValToNullDB($reqJabatanFuId));
                $set->setField("JABATAN_FT_ID", ValToNullDB($reqJabatanFtId));
                $set->setField("STATUS_SK_PPPK", ValToNullDB($reqStatusSkPppk));
                $set->setField("SPMT_NOMOR", $reqSpmtNomor);
                $set->setField("SPMT_TANGGAL", dateToDBCheck($reqSpmtTanggal));
                $set->setField("SPMT_TMT", dateToDBCheck($reqSpmtTmt));
                $set->setField("NIP_PPPK", $reqNipPPPK);

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

                $set->setField('GOLONGAN_PPPK_ID', $reqGolonganPppkId);
                $set->setField('PEJABAT_PENETAP_ID', $reqPejabatPenetapId);
                $set->setField('PEJABAT_PENETAP', $reqPejabatPenetap);
                $set->setField('TMT_PPPK', dateToDBCheck($reqTerhitungMulaiTanggal));
                $set->setField('TANGGAL_TUGAS', dateToDBCheck($reqTanggalTugas)); 
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
                $set->setField("FORMASI_PPPK_ID", ValToNullDB($reqFormasiPppkId));
                $set->setField("JABATAN_TUGAS", $reqJabatanTugas);
                $set->setField('SK_PPPK_ID',$reqRowId);

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