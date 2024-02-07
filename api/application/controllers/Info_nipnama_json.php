<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Info_nipnama_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        // $this->db->query("alter session set nls_date_format='DD-MM-YYYY'"); 
        
    }
 
    // show data entitas
    function index_get() {
        $this->load->model('UserLoginLog');
        $this->load->model('base/Pegawai');
        $this->load->model('base/SatuanKerja');

        $user_login_log= new UserLoginLog;
        
        $reqToken = $this->input->get("reqToken");
        $reqMode = $this->input->get("reqMode");
        // $id = $this->input->get("id");
        $nip = $this->input->get("nip");

        //CEK PEGAWAI ID DARI TOKEN
        // e6088b42e1f40f4083b0df2a349ed198
        $user_login_log = new UserLoginLog();
        // $reqPegawaiId = $user_login_log->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        // echo $user_login_log->query;exit();
        $reqSatuanKerjaId = $user_login_log->getToken(array("TOKEN" => $reqToken, "STATUS" => '1'));

        // echo $user_login_log->query;exit();
        // echo $reqPegawaiId;exit();
        // $reqPegawaiId = '9014140KP';
        // echo $reqMode;exit();
        // echo $reqSatuanKerjaId;exit();

        // echo $statement;exit;

        if($reqSatuanKerjaId == "")
        {
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
        }
        else
        {
            if(!empty($nip))
            {
                // $statementsatuankerja= " AND A.PEGAWAI_ID = ".$id;
                $statementsatuankerja= " AND A.NIP_BARU = '".$nip."'";
            }

            /*
            if($reqSatuanKerjaId !== "-1" && !empty($reqSatuanKerjaId))
            {
                $skerja= new SatuanKerja();
                $reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
                unset($skerja);
                $statementsatuankerja.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
                // echo $statementsatuankerja;exit;
            }*/

            $tahunnow= date("Y")-1;
            $statementsatuankerja.= " AND A.STATUS_PEGAWAI_ID IN (1,2)";
            // echo $statementsatuankerja;exit();

            $set = new Pegawai;
            // $aColumns = array("PEGAWAI_ID", "NIP_BARU", "NAMA_LENGKAP");
            $aColumns = array("NIP_BARU", "NAMA_LENGKAP", "OPD_INDUK" );
            $statement= $statementsatuankerja;
            $set->selectByParamsSimple(array(), -1, -1, $statement);
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
            
            $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'count' => $total ,'result' => $result));
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