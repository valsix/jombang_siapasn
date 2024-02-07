<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Statistik_jenis_kelamin_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        // $this->db->query("alter session set nls_date_format='DD-MM-YYYY'"); 
        
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base/SatuanKerja');
        $this->load->model('base/Statistik');

        $user_login_log= new UserLoginLog;
        
        $reqToken = $this->input->get("reqToken");

        //CEK PEGAWAI ID DARI TOKEN
        $user_login_log = new UserLoginLog();
        $reqSatuanKerjaId = $user_login_log->getTokenSatuanKerjaId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        // echo $user_login_log->query;exit();
        // echo $reqSatuanKerjaId;exit();

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

        $statementAktif= " AND EXISTS(
            SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
            AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
        )";

        $set = new Statistik;
        $aColumns = array("ID","NAMA", "JUMLAH");
        $set->selectByParamsJenisKelamin($statementAktif.$statement);
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
        
        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => count($aColumns) ,'result' => $result));
    }
	
    // insert new data to entitas
    function index_post() {
    }
 
    // update data entitas
    function index_put() {
    }
 
    // delete entitas
    function index_delete() {
    }
 
}