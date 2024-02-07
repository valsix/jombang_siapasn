<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Info_absensi_sebelum_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        // $this->db->query("alter session set nls_date_format='DD-MM-YYYY'"); 
        
    }
 
    // show data entitas
    function index_get() {
        // ini_set('memory_limit','2048M');
        // ini_set("memory_limit","500M");
        // ini_set('max_execution_time', 520);
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);

        $this->load->model('UserLoginLog');
        $this->load->model('base-absensi/Absensi');
        $this->load->model('base/Pegawai');
        $this->load->model('base/SatuanKerja');

        $user_login_log= new UserLoginLog;
        
        $reqToken = $this->input->get("reqToken");
        $reqMode = $this->input->get("reqMode");
        // $id = $this->input->get("id");
        $nip= $this->input->get("nip");
        $tanggalmulai= dateToPageCheck($this->input->get("tanggalmulai"));
        $tanggalakhir= dateToPageCheck($this->input->get("tanggalakhir"));

        //CEK PEGAWAI ID DARI TOKEN
        // e6088b42e1f40f4083b0df2a349ed198
        // $user_login_log = new UserLoginLog();
        // $reqSatuanKerjaId = $user_login_log->getTokenSatuanKerjaId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        // echo $statement;exit;

        // if($reqSatuanKerjaId == "")
        // if($reqToken !== "e6088b42e1f40f4083b0df2a349ed198" || empty($nip) || empty($tanggalmulai) || empty($tanggalakhir))
        if($reqToken !== "e6088b42e1f40f4083b0df2a349ed198" || empty($tanggalmulai) || empty($tanggalakhir))
        {
            $this->response(array('status' => 'fail', 'message' => 'Sesi anda telah berakhir', 'code' => 502));
        }
        else
        {
            $total = 0;
            $aColumns = array("PEGAWAI_ID", "NIP_BARU", "NAMA_LENGKAP", "TANGGAL", "MASUK", "PULANG", "EX_MASUK", "TERLAMBAT", "PULANG_CEPAT");

            if(!empty($nip))
            {
                $statementsatuankerja= " AND ( A.NIP_BARU = '".$nip."' OR A.PEGAWAI_ID = ".$nip.") ";
            }

            // $statementsatuankerja= " AND A.PEGAWAI_ID IN (8300, 13782) ";
            $statementsatuankerja.= " 
            AND
            (
                A.STATUS_PEGAWAI_ID IN (1,2)
                OR
                (
                    A.STATUS_PEGAWAI_ID IN (3,4,5)
                    AND 
                    EXISTS
                    (
                        SELECT 1
                        FROM
                        (
                            SELECT PEGAWAI_STATUS_ID
                            FROM pegawai_status
                            WHERE TMT >= TO_DATE('01".getMonth($tanggalmulai).getYear($tanggalmulai)."', 'DDMMYYYY')
                        ) XXX WHERE A.PEGAWAI_STATUS_ID = XXX.PEGAWAI_STATUS_ID
                    )
                )
            )";

            $start= strtotime(getYear($tanggalmulai)."-".getMonth($tanggalmulai)."-01");
            $end= strtotime(getYear($tanggalakhir)."-".getMonth($tanggalakhir)."-01");
            while($start <= $end)
            {
                $infoperiode= date('mY', $start);
                $infotanggalperiode=getTahunPeriode($infoperiode)."-".getBulanPeriode($infoperiode);
                // echo $infotanggalperiode."<br/>";
                // exit;
                $set= new Absensi();
                $set->selectByDataPeriode(array(), -1, -1, $infoperiode, $statementsatuankerja, "ORDER BY A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.PANGKAT_RIWAYAT_TMT ASC");
                $infocheck= $set->errorMsg;
                if(!empty($infocheck)){}
                else
                {
                    // echo $set->query;exit;
                    // $set->firstRow();
                    while ($set->nextRow())
                    {
                        for($n=1; $n <= 31; $n++)
                        {
                            $infohari= generateZeroDate($n,2);
                            $infotanggal= $infotanggalperiode."-".$infohari;

                            $today_time= strtotime($infotanggal);
                            $expire_time= strtotime($tanggalakhir);
                            // echo $infotanggal."<br/>";
                            if($expire_time < $today_time)
                            {
                                // echo $today_time."--".$expire_time."<br/>";
                                break;
                            }
                            // echo $infotanggal;exit;
                            if(validateDate($infotanggal))
                            {
                                $row = array();
                                for($i=0 ; $i<count($aColumns); $i++)
                                {
                                    if($aColumns[$i] == "TANGGAL")
                                        $row[trim($aColumns[$i])] = dateToPageCheck($infotanggal);
                                    elseif($aColumns[$i] == "MASUK" || $aColumns[$i] == "PULANG" || $aColumns[$i] == "EX_MASUK" || $aColumns[$i] == "TERLAMBAT" || $aColumns[$i] == "PULANG_CEPAT")
                                    {
                                        $infodata= $set->getField(trim($aColumns[$i]."_".$n));
                                        $infodata= coalesce($infodata, "-");
                                        $infoarray= trim($aColumns[$i]);
                                        if($infoarray == "EX_MASUK")
                                            $infoarray= "ASK";

                                        $row[$infoarray] = $infodata;
                                    }
                                    else
                                        $row[trim($aColumns[$i])] = $set->getField(trim($aColumns[$i]));
                                }
                                $result[] = $row;
                            }
                            // echo $infohari;
                        }
                        // print_r($result);exit;
                    }
                    $total++;
                }

                $start = strtotime("+1 month", $start);
            }
            // print_r($result);exit;
            // exit;
            
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