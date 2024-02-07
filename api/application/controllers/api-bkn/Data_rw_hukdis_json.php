<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Data_rw_hukdis_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        $this->load->library('gapi');
    }
 
    // show data entitas
	function index_get() {
        $nip= $this->input->get("nip");
        $id= $this->input->get("id");
        $gp= new gapi();

        if(!empty($id)){
             $arrparam= ["ctrl"=>"hukdis/id", "value"=>$id];
             $vreturn= $gp->getdataParam($arrparam);
        }else{
             $arrparam= ["vjenis"=>"rw-hukdis", "nip"=>$nip];
             $vreturn= $gp->getdata($arrparam);
        }

       
        // print_r($vreturn);exit;
        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'result' => $vreturn));
    }
	
    // insert new data to entitas
    function index_post() {

       $akhirHukumanTanggal= $this->input->post('akhirHukumanTanggal');
       $alasanHukumanDisiplinId= $this->input->post('alasanHukumanDisiplinId');
       $golonganId= $this->input->post('golonganId');
       $golonganLama= $this->input->post('golonganLama');
       $hukdisYangDiberhentikanId= $this->input->post('hukdisYangDiberhentikanId');
       $hukumanTanggal= $this->input->post('hukumanTanggal');
       $id= $this->input->post('id');
       $jenisHukumanId= $this->input->post('jenisHukumanId');
       $kedudukanHukumId= $this->input->post('kedudukanHukumId');
       $keterangan= $this->input->post('keterangan');
       $masaBulan= $this->input->post('masaBulan');
       $masaTahun= $this->input->post('masaTahun');
       $nomorPp= $this->input->post('nomorPp');
       $path= $this->input->post('path');
       $pnsOrangId= $this->input->post('pnsOrangId');
       $skNomor= $this->input->post('skNomor');
       $skPembatalanNomor= $this->input->post('skPembatalanNomor');
       $skPembatalanTanggal= $this->input->post('skPembatalanTanggal');
       $skPembatalanTanggal= $this->input->post('skPembatalanTanggal');


        $dok_id=$this->input->post('dok_id');
        $dok_nama=$this->input->post('dok_nama');
        $dok_uri=$this->input->post('dok_uri');
        $object=$this->input->post('object');
        $slug=$this->input->post('slug');
        $path[]= array("dok_id"=>$dok_id,"dok_nama"=>$dok_nama,"dok_uri"=>$dok_uri,"object"=>$object,"slug"=>$slug);
            
        $id=$id?$id:null;    

        $arrData = array(
            "akhirHukumanTanggal"=>$akhirHukumanTanggal,
            "alasanHukumanDisiplinId"=>$alasanHukumanDisiplinId,
            "golonganId"=>$golonganId,
            "golonganLama"=>$golonganLama,
            "hukdisYangDiberhentikanId"=>$hukdisYangDiberhentikanId,
            "hukumanTanggal"=>$hukumanTanggal,
             "id"=>$id,
            "jenisHukumanId"=>$jenisHukumanId,
            "jenisTingkatHukumanId"=>$jenisTingkatHukumanId,
            "kedudukanHukumId"=>$kedudukanHukumId,
            "keterangan"=>$keterangan,
            "masaBulan"=>$masaBulan,
            "masaTahun"=>$masaTahun,
            "nomorPp"=>$nomorPp,
            "path"=>$path,
            "pnsOrangId"=>$pnsOrangId,
            "skNomor"=>$skNomor,
            "skPembatalanNomor"=>$skPembatalanNomor,
            "skPembatalanTanggal"=>$skPembatalanTanggal,
            "skTanggal"=>$skTanggal

        );
        $arrparam= ["ctrl"=>"hukdis/save"];
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