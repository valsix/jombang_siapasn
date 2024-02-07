<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Info_cuti_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        // $this->db->query("alter session set nls_date_format='DD-MM-YYYY'"); 
        
    }
 
    // show data entitas
    function index_get() {
       $this->load->model('UserLoginLog');
        $this->load->model('base/Pegawai');
        $this->load->model('base/Cuti');

        $user_login_log= new UserLoginLog;
        
        $reqToken = $this->input->get("reqToken");
        $reqMode = $this->input->get("reqMode");
        // $id = $this->input->get("id");
        $nip = $this->input->get("nip");
        $bulanMulai = $this->input->get("bulanMulai");

        //CEK PEGAWAI ID DARI TOKEN
        // e6088b42e1f40f4083b0df2a349ed198
        $user_login_log = new UserLoginLog();
        // $reqPegawaiId = $user_login_log->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        // echo $user_login_log->query;exit();
        $reqSatuanKerjaId = $user_login_log->getTokenSatuanKerjaId(array("TOKEN" => $reqToken, "STATUS" => '1'));

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
            if(!empty($bulanMulai))
            {
                // $statementsatuankerja= " AND A.PEGAWAI_ID = ".$id;
                $statementsatuankerja= " AND to_char(A.TANGGAL_MULAI, 'MM-YYYY') = '".$bulanMulai."'";
            }else{
                $statementsatuankerja= " AND to_char(A.TANGGAL_MULAI, 'MM-YYYY') = '-1'";
            }

            

            $set = new Cuti;
            $aColumns = array(  "NIP_BARU","NAMA",  'JENIS_CUTI'
            , 'TANGGAL_MULAI','TANGGAL_SELESAI', 'KETERANGAN');
            $statement= $statementsatuankerja;
            $set->selectByParams(array(), -1, -1, $statement);
             // echo $set->query;exit();
            $arrJenisCuti[1]='Cuti Tahunan';
            $arrJenisCuti[2]='Cuti Besar';
            $arrJenisCuti[3]='Cuti Sakit';
            $arrJenisCuti[4]='Cuti Bersalin';
            $arrJenisCuti[5]='Cuti Alasan Penting';
            $arrJenisCuti[6]='Cuti Bersama';
            $arrJenisCuti[7]='CLTN';
            $total = 0;
            while($set->nextRow())
            {
                $row = array();
                for ( $i=0 ; $i<count($aColumns) ; $i++ )
                {
                    if($aColumns[$i] == "TANGGAL_SELESAI" || $aColumns[$i] == "TANGGAL_MULAI" ){
                        $row[trim($aColumns[$i])] = dateToPageCheck($set->getField(trim($aColumns[$i])));
                    }else if($aColumns[$i] == "JENIS_CUTI"){
                         $row[trim($aColumns[$i])] = $arrJenisCuti[$set->getField(trim($aColumns[$i]))];
                    }else{
                        $row[trim($aColumns[$i])] = $set->getField(trim($aColumns[$i]));
                    }
                }
                $result[] = $row;

                $total++;
            }
            // print_r($result);exit;
            // echo $total;exit;
            
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