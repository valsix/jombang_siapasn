<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Api_list_pensiun extends REST_Controller {
 
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
        $reqTanggalAwal = $this->input->get("reqTanggalAwal");
        $reqTanggalAkhir = $this->input->get("reqTanggalAkhir");

        // $id = $this->input->get("id");
        $nip = $this->input->get("nip");
        //CEK PEGAWAI ID DARI TOKEN
        // e6088b42e1f40f4083b0df2a349ed198
        $user_login_log = new UserLoginLog();
        // $reqPegawaiId = $user_login_log->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        //echo $user_login_log->query;exit();
        $reqSatuanKerjaId = $user_login_log->getTokenSatuanKerjaId(array("TOKEN" => $reqToken, "STATUS" => '1'));

        // echo $user_login_log->query;exit();
        // echo $reqPegawaiId;exit();
        // $reqPegawaiId = '9014140KP';
        // echo $reqMode;exit();
        // echo $reqSatuanKerjaId;exit();

        // echo $statement;exit;

        $checktanggal= dateToPageCheck($reqTanggalAwal);
        $checktanggal= DateTime::createFromFormat('Y-m-d', $checktanggal);
        if ($checktanggal) {}
        else
        {
            $this->response(array('status' => 'fail', 'message' => 'reqTanggalAwal ('.$reqTanggalAwal.') anda tidak valid. Untuk tanggal valid dengan format dd-mm-yyyy.', 'code' => 502));
        }

        $checktanggal= dateToPageCheck($reqTanggalAkhir);
        $checktanggal= DateTime::createFromFormat('Y-m-d', $checktanggal);
        if ($checktanggal) {}
        else
        {
            $this->response(array('status' => 'fail', 'message' => 'reqTanggalAkhir ('.$reqTanggalAwal.') anda tidak valid. Untuk tanggal valid dengan format dd-mm-yyyy.', 'code' => 502));
        }

        if($reqSatuanKerjaId == "")
        {
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
        }
        else
        {
           //echo $reqSatuanKerjaId;exit();
            if(!empty($nip))
            {
                // $statementsatuankerja= " AND A.PEGAWAI_ID = ".$id;
                $statementsatuankerja= " AND A.NIP_BARU = '".$nip."'";
            }
            
            if($reqSatuanKerjaId !== "-1" && !empty($reqSatuanKerjaId))
            {
                $skerja= new SatuanKerja();
                $reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
                unset($skerja);
                $statementsatuankerja.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
                // echo $statementsatuankerja;exit;
            }
            
            //$tahunnow= date("Y")-1;
            $statementsatuankerja.= " ";
            // echo $statementsatuankerja;exit();
            
            $set = new Pegawai;
            $aColumns = array("NIP_BARU", "NAMA", "STATUS_PEGAWAI", "TMT");
            $statement= $statementsatuankerja;

            $statement.= " AND TO_DATE(TO_CHAR(A4.TMT, 'YYYY-MM-DD'), 'YYYY/MM/DD') BETWEEN TO_DATE('".dateToPageCheck($reqTanggalAwal)."','YYYY/MM/DD') AND TO_DATE('".dateToPageCheck($reqTanggalAkhir)."','YYYY/MM/DD')";

            $set->selectparampensiun(array(), -1, -1, $statement);
            // echo $set->query;exit();

            $total = 0;
            while($set->nextRow())
            {
                $row = array();
               for ( $i=0 ; $i<count($aColumns) ; $i++ )
                {
                    if($aColumns[$i] == "XXTMT")
                        $row[trim($aColumns[$i])] = getFormattedDateView($set->getField(trim($aColumns[$i])));
                    else if($aColumns[$i] == "tmt_pensiun" || $aColumns[$i] == "TMT")
                        $row[trim($aColumns[$i])] = dateToPageCheck($set->getField(trim($aColumns[$i])));
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