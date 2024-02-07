<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/class-list-util.php");
include_once("functions/class-list-util-serverside.php");

class datatable_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        // $this->db->query("alter session set nls_date_format='DD-MM-YYYY'"); 
        
    }
 
    // show data entitas
	function index_get() {
    }
	
    // insert new data to entitas
    function index_post() {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, PUT, OPTIONS, PATCH, DELETE');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Headers: Authorization, Content-Type, x-xsrf-token, x_csrftoken, Cache-Control, X-Requested-With');

        $this->load->model('UserLoginLog');

        $user_login_log= new UserLoginLog;

        // $reqToken = $this->input->post("reqToken");
        $reqToken= $_REQUEST['reqToken'];
        // $this->response(array('reqToken' => $reqToken));exit();

        //CEK PEGAWAI ID DARI TOKEN
        $user_login_log = new UserLoginLog();
        $reqPegawaiId= $user_login_log->getTokenPegawaiId(array("TOKEN" => $reqToken, "STATUS" => '1'));
        // echo $user_login_log->query;exit();

        if($reqPegawaiId == "")
        {
            $this->response(array('status' => 'fail', 'message' => 'Anda tidak berhak untuk mendapatkan info personal pegawai.', 'code' => 502));
        }
        else
        {
            $this->load->model('base/Combo');

            $set= new Combo();

            if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
                $columnsDefault = [];
                foreach ( $_REQUEST['columnsDef'] as $field ) {
                    $columnsDefault[ $field ] = "true";
                }
            }
            // print_r($columnsDefault);exit;

            $displaystart= -1;
            $displaylength= -1;

            $arrinfodata= [];
            $statement= "";
            $sOrder = "";
            $set->selectByParamsInfoUpdateData(array(), $displaylength, $displaystart, $reqPegawaiId, $statement, $sOrder);
            // echo $set->query;exit;
            while ($set->nextRow()) 
            {
                $row= [];
                foreach($columnsDefault as $valkey => $valitem) 
                {
                    if ($valkey == "SORDERDEFAULT")
                        $row[$valkey]= "1";
                    else
                        $row[$valkey]= $set->getField($valkey);
                }
                array_push($arrinfodata, $row);
            }

            // get all raw data
            $alldata = $arrinfodata;
            // print_r($alldata);exit;

            $data = [];
            // internal use; filter selected columns only from raw data
            foreach ( $alldata as $d ) {
                // $data[] = filterArray( $d, $columnsDefault );
                $data[] = $d;
            }

            // count data
            $totalRecords = $totalDisplay = count( $data );

            // filter by general search keyword
            if ( isset( $_REQUEST['search'] ) ) {
                $data         = filterKeyword( $data, $_REQUEST['search'] );
                $totalDisplay = count( $data );
            }

            if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
                foreach ( $_REQUEST['columns'] as $column ) {
                    if ( isset( $column['search'] ) ) {
                        $data         = filterKeyword( $data, $column['search'], $column['data'] );
                        $totalDisplay = count( $data );
                    }
                }
            }

            // sort
            if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
                $column = $_REQUEST['order'][0]['column'];
                if(count($columnsDefault) - 2 == $column){}
                else
                {
                    $dir    = $_REQUEST['order'][0]['dir'];
                    usort( $data, function ( $a, $b ) use ( $column, $dir ) {
                        $a = array_slice( $a, $column, 1 );
                        $b = array_slice( $b, $column, 1 );
                        $a = array_pop( $a );
                        $b = array_pop( $b );

                        if ( $dir === 'asc' ) {
                            return $a > $b ? true : false;
                        }

                        return $a < $b ? true : false;
                    } );
                }
            }

            // pagination length
            if ( isset( $_REQUEST['length'] ) && $_REQUEST['length'] > 0 ) {
                $data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
            }

            // return array values only without the keys
            if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
                $tmp  = $data;
                $data = [];
                foreach ( $tmp as $d ) {
                    $data[] = array_values( $d );
                }
            }

            $result = [
                'recordsTotal'    => $totalRecords,
                'recordsFiltered' => $totalDisplay,
                'data'            => $data,
            ];

            header('Content-Type: application/json');
            echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); 
        }
    }
 
    // update data entitas
    function index_put() {
    }
 
    // delete entitas
    function index_delete() {
    }
 
}