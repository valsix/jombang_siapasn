<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Data_utama_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        $this->load->library('gapi');
    }
 
    // show data entitas
	function index_get() {
        $nip= $this->input->get("nip");
        $gp= new gapi();
        $arrparam= ["vjenis"=>"data-utama", "nip"=>$nip];
        $vreturn= $gp->getdata($arrparam);
        // print_r($vreturn);exit;
        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'result' => $vreturn));
    }
	
    // insert new data to entitas
    function index_post() {
        
        $agama_id  = $this->input->post('agama_id');
        $alamat   = $this->input->post('alamat');
        $email  = $this->input->post('email');
        $email_gov = $this->input->post('email_gov');
        $kabupaten_id = $this->input->post('kabupaten_id');
        $karis_karsu = $this->input->post('karis_karsu');
        $kelas_jabatan = $this->input->post('kelas_jabatan');
        $kpkn_id = $this->input->post('kpkn_id');
        $lokasi_kerja_id = $this->input->post('lokasi_kerja_id');
        $nomor_bpjs = $this->input->post('nomor_bpjs'); 
        $nomor_hp = $this->input->post('nomor_hp');
        $nomor_telpon = $this->input->post('nomor_telpon');
        $npwp_nomor = $this->input->post('npwp_nomor');
        $pns_orang_id = $this->input->post('pns_orang_id');
        $tanggal_taspen = $this->input->post('tanggal_taspen');
        $tapera_nomor = $this->input->post('tapera_nomor');
        $taspen_nomor = $this->input->post('taspen_nomor');


        $arrData = array(
            "agama_id"=>$agama_id
            , "alamat"=>$alamat
            , "email"=>$email
            , "email_gov"=>$email_gov
            , "kabupaten_id"=>$kabupaten_id
            , "karis_karsu"=>$karis_karsu
            , "kelas_jabatan"=>$kelas_jabatan
            , "kpkn_id"=>$kpkn_id
            , "lokasi_kerja_id"=>$lokasi_kerja_id
            , "nomor_bpjs"=>$nomor_bpjs
            , "nomor_hp"=>$nomor_hp
            , "nomor_telpon"=>$nomor_telpon
            , "npwp_nomor"=>$npwp_nomor
            , "pns_orang_id"=>$pns_orang_id
            , "tanggal_taspen"=>$tanggal_taspen
            , "tapera_nomor"=>$tapera_nomor
            , "taspen_nomor"=>$taspen_nomor
        );

         $jsonData  = json_encode($arrData);
        // print_r($jsonData);exit;

        $arrparam= ["ctrl"=>"pns/data-utama-update"];
        $gp= new gapi();
        $vreturn= $gp->postdata($arrparam,$jsonData);
        
        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'result' => $vreturn));

    }
 
    // update data entitas
    function index_put() {
    }
 
    // delete entitas
    function index_delete() {
    }
 
}