<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Data_pegawai_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        $this->load->library('gapi');
    }
 
    // show data entitas
	function index_get() {
        $nip= $this->input->get("nip");
        $id= $this->input->get("id");
        $gp= new Gapi();

        if(!empty($id))
        {
        }
        else
        {
            $arrparam= ["vjenis"=>"data-utama", "nip"=>$nip, "lihatdata"=>""];
            $vreturn= $gp->getdata($arrparam);
        }
        // print_r($vreturn);exit;
        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'result' => $vreturn));
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