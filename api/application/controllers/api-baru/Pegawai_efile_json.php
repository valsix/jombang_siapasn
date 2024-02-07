<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 include_once("functions/encrypt.func.php");
class Pegawai_efile_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();
    }
 
    // show data entitas
	function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base-new/PegawaiFile');

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
            // $this->load->library('globalfilepegawai');echo "ASd";exit;
            // $vfpeg= new globalfilepegawai();
            $enkripdekripkunci= enkripdekripkunci();
            // echo $enkripdekripkunci;exit;

            $statement= "";
            if(!empty($reqRiwayat))
            {

                $statement= " AND A.RIWAYAT_ID = ".$reqRiwayat." AND A.RIWAYAT_TABLE = 'PEGAWAI' AND A.PEGAWAI_ID = ".$reqPegawaiId."";
                $set = new PegawaiFile;
                $aColumns = array("PEGAWAI_FILE_ID", "PEGAWAI_ID", "PATH");
                $set->selectByParamsLastRiwayatTable(array(), -1, -1, $statement);

            }
            else
            {
                if(!empty($reqRowId))
                {
                    $arrparamjenis= [];
                    $arrparamjenis= ["reqdata"=>$reqRowId, "reqkunci"=>$enkripdekripkunci];
                    $reqRowId= dekripdata($arrparamjenis);
                    $statement.= " AND A.PEGAWAI_FILE_ID = ".$reqRowId;
                }

                $set = new PegawaiFile;
                $aColumns = array("PEGAWAI_FILE_ID", "PEGAWAI_FILE_ENKRIP_ID", "PEGAWAI_ID", "RIWAYAT_TABLE", "RIWAYAT_FIELD", "RIWAYAT_ID", "FILE_KUALITAS_ID", "FILE_KUALITAS_NAMA", "PATH", "PATH_ASLI", "STATUS_VERIFIKASI", "KETERANGAN", "STATUS", "STATUS_NAMA", "INFO_GROUP_DATA", "KATEGORI_FILE_ID");
                $set->selectByParams(array(), -1, -1, $statement, $reqPegawaiId);

            }
           

            $total = 0;
            while($set->nextRow())
            {
            
                $row = array();
                for ( $i=0 ; $i<count($aColumns) ; $i++ )
                {
                    if($aColumns[$i] == "TMT")
                        $row[trim($aColumns[$i])] = getFormattedDateView($set->getField(trim($aColumns[$i])));
                    else if($aColumns[$i] == "PEGAWAI_FILE_ENKRIP_ID")
                    {
                        $arrparamjenis= [];
                        $arrparamjenis= ["reqdata"=>$set->getField("PEGAWAI_FILE_ID"), "reqkunci"=>$enkripdekripkunci];
                        $infoval= enkripdata($arrparamjenis);
                        $row[trim($aColumns[$i])] = $infoval;
                    }
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
       
    }
 
    // update data entitas
    function index_put() {
    }
 
    // delete entitas
    function index_delete() {
    }
 
}