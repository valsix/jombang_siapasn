<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
class Data_kursus_post_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        $this->load->library('gapi');
    }
 
    // show data entitas
	function index_get() {
        // $id= $this->input->get("id");
        // $gp= new gapi();
        // $arrparam= ["ctrl"=>"kursus/id", "value"=>$id];
        // $vreturn= $gp->getdataParam($arrparam);
        // print_r($vreturn);exit;
        // $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'result' => $vreturn));
    }
	function   index_post22() {
       
   
        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'result' => null));
    }
    // insert new data to entitas
     function index_post() {
       

        $id=$this->input->post('id');
        $instansiId=$this->input->post('instansiId');
        $institusiPenyelenggara=$this->input->post('institusiPenyelenggara');
        $jenisDiklatId=$this->input->post('jenisDiklatId');
        $jenisKursus=$this->input->post('jenisKursus');
        $jenisKursusSertipikat=$this->input->post('jenisKursusSertipikat');
        $jumlahJam=(int)$this->input->post('jumlahJam');
        $lokasiId=$this->input->post('lokasiId');
        $namaKursus=$this->input->post('namaKursus');
        $nomorSertipikat=$this->input->post('nomorSertipikat');
        $pnsOrangId=$this->input->post('pnsOrangId');
        $tahunKursus=(int)$this->input->post('tahunKursus');
        $tanggalKursus=$this->input->post('tanggalKursus');
        $tanggalSelesaiKursus=$this->input->post('tanggalSelesaiKursus');

        $dok_id=$this->input->post('dok_id');
        $dok_nama=$this->input->post('dok_nama');
        $dok_uri=$this->input->post('dok_uri');
        $object=$this->input->post('object');
        $slug=$this->input->post('slug');

        // $id ='123';
        $id = "kursus-jombang-".$id;
        $id = md5($id);

        //  $id='f7c19cdb-b1f4-4416-b1b5-9a7356a539e9';
        // //$id='7e351c1f-05ed-11ee-9e5f-0a580a8301ad';
        // $instansiId=null;
        // $institusiPenyelenggara='test';
        // $jenisDiklatId='4';
        // $jenisKursus='1';
        // $jenisKursusSertipikat='test';
        // $jumlahJam=3;
        // $lokasiId='2';
        // $namaKursus='test';
        // $nomorSertipikat='222';
        // $pnsOrangId='A5EB03F641F5F6A0E040640A040252AD';
        // $tahunKursus=2001;
        // $tanggalKursus='12-12-2001';
        // $tanggalSelesaiKursus='13-12-2001';

        // $dok_id='12';
        // $dok_nama='test';
        // $dok_uri='www.google.com';
        // $object='1';
        // $slug='112232';

        $path[]= array("dok_id"=>$dok_id,"dok_nama"=>$dok_nama,"dok_uri"=>$dok_uri,"object"=>$object,"slug"=>$slug);
        // $pathx = json_encode($path);

        $arrData = array(
            "id"=>$id,
            "instansiId"=>$instansiId,
            "institusiPenyelenggara"=>$institusiPenyelenggara,
            "jenisDiklatId"=>$jenisDiklatId,
            "jenisKursus"=>$jenisKursus,
            "jenisKursusSertipikat"=>$jenisKursusSertipikat,
            "jumlahJam"=>$jumlahJam,
            "lokasiId"=>$lokasiId,
            "namaKursus"=>$namaKursus,
            "nomorSertipikat"=>$nomorSertipikat,
            "pnsOrangId"=>$pnsOrangId,
            "tahunKursus"=>$tahunKursus,
            "tanggalKursus"=>$tanggalKursus,
            "tanggalSelesaiKursus"=>$tanggalSelesaiKursus,
            "path"=>$path
        );

        $jsonData  = json_encode($arrData);
        // print_r($jsonData );exit;
        $arrparam= ["ctrl"=>"kursus/save"];
        $gp= new gapi();

        $vreturn= $gp->postdata($arrparam,$jsonData);
        // var_dump($vreturn);exit;
        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'result' => $vreturn));


    }

    function index_post2() {
       

        $id=$this->input->post('id');
        $instansiId=$this->input->post('instansiId');
        $institusiPenyelenggara=$this->input->post('institusiPenyelenggara');
        $jenisDiklatId=$this->input->post('jenisDiklatId');
        $jenisKursus=$this->input->post('jenisKursus');
        $jenisKursusSertipikat=$this->input->post('jenisKursusSertipikat');
        $jumlahJam=$this->input->post('jumlahJam');
        $lokasiId=$this->input->post('lokasiId');
        $namaKursus=$this->input->post('namaKursus');
        $nomorSertipikat=$this->input->post('nomorSertipikat');
        $pnsOrangId=$this->input->post('pnsOrangId');
        $tahunKursus=$this->input->post('tahunKursus');
        $tanggalKursus=$this->input->post('tanggalKursus');
        $tanggalSelesaiKursus=$this->input->post('tanggalSelesaiKursus');

        $dok_id=$this->input->post('dok_id');
        $dok_nama=$this->input->post('dok_nama');
        $dok_uri=$this->input->post('dok_uri');
        $object=$this->input->post('object');
        $slug=$this->input->post('slug');

        $id ='123';
        $id = "kursus-jombang-".$id;
        $id = md5($id);

         $id='f7c19cdb-b1f4-4416-b1b5-9a7356a539e9';
        //$id='7e351c1f-05ed-11ee-9e5f-0a580a8301ad';
        $instansiId=null;
        $institusiPenyelenggara='test';
        $jenisDiklatId='4';
        $jenisKursus='1';
        $jenisKursusSertipikat='test';
        $jumlahJam=3;
        $lokasiId='2';
        $namaKursus='test';
        $nomorSertipikat='222';
        $pnsOrangId='A5EB03F641F5F6A0E040640A040252AD';
        $tahunKursus=2001;
        $tanggalKursus='12-12-2001';
        $tanggalSelesaiKursus='13-12-2001';

        $dok_id='12';
        $dok_nama='test';
        $dok_uri='www.google.com';
        $object='1';
        $slug='112232';

        $path[]= array("dok_id"=>$dok_id,"dok_nama"=>$dok_nama,"dok_uri"=>$dok_uri,"object"=>$object,"slug"=>$slug);
        // $pathx = json_encode($path);

        $arrData = array(
            "id"=>$id,
            "instansiId"=>$instansiId,
            "institusiPenyelenggara"=>$institusiPenyelenggara,
            "jenisDiklatId"=>$jenisDiklatId,
            "jenisKursus"=>$jenisKursus,
            "jenisKursusSertipikat"=>$jenisKursusSertipikat,
            "jumlahJam"=>$jumlahJam,
            "lokasiId"=>$lokasiId,
            "namaKursus"=>$namaKursus,
            "nomorSertipikat"=>$nomorSertipikat,
            "pnsOrangId"=>$pnsOrangId,
            "tahunKursus"=>$tahunKursus,
            "tanggalKursus"=>$tanggalKursus,
            "tanggalSelesaiKursus"=>$tanggalSelesaiKursus,
            "path"=>$path
        );

        $jsonData  = json_encode($arrData);
        // print_r($jsonData );exit;
        $arrparam= ["ctrl"=>"kursus/save"];
        $gp= new gapi();

        $vreturn= $gp->postdata($arrparam,$jsonData);
        // var_dump($vreturn);exit;
        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'result' => $vreturn));


    }
 
    // update data entitas
    function index_put() {
    }
 
    // delete entitas
    function index_delete() {
    }
 
}