<?php
 
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Info_bagor_json extends REST_Controller {
 
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

        // $user_login_log = new UserLoginLog();
        // $reqSatuanKerjaId = $user_login_log->getTokenSatuanKerjaId(array("TOKEN" => $reqToken, "STATUS" => '1'));

        // if($reqSatuanKerjaId == "")
        // {
        //     $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
        // }
        if($reqToken == md5('valsixbagor'))
        {
            // echo $reqSatuanKerjaId;exit();
            if(!empty($nip))
            {
                // $statementsatuankerja= " AND A.PEGAWAI_ID = ".$id;
                $statementsatuankerja= " AND PEGAWAI.NIP_BARU = '".$nip."'";
            }
            
            if($reqSatuanKerjaId !== "-1" && !empty($reqSatuanKerjaId))
            {
                $skerja= new SatuanKerja();
                $reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
                unset($skerja);
                $statementsatuankerja.= " AND PEGAWAI.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
                // echo $statementsatuankerja;exit;
            }
            
            //$tahunnow= date("Y")-1;
            $statementsatuankerja.= " 
            AND 
                pegawai.status_pegawai_id IN ( 1, 2, 6 )
            ";
            // echo $statementsatuankerja;exit();
            
            $set = new Pegawai;
            $aColumns = array(
                "nip", "nama_lengkap", "nama_statuspeg", "jenis_jabatan", "tmtjab"
                , "nama_jabatan", "nama_eselon", "unit", "unitkerja", "pangkat", "gol"
            );
            $statement= $statementsatuankerja;
            $set->selectByParamsInfoEoffice(array(), -1, -1, $statement);
            // echo $set->query;exit();

            $total = 0;
            while($set->nextRow())
            {
                $row = array();
               for ( $i=0 ; $i<count($aColumns) ; $i++ )
                {
                    if($aColumns[$i] == "TMT")
                        $row[trim($aColumns[$i])] = getFormattedDateView($set->getField(trim($aColumns[$i])));
                    else if($aColumns[$i] == "tmt_pensiun")
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
        else
        {
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
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