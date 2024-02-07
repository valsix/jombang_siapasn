<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Data_skp22_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        $this->load->library('gapi');
    }
 
    // show data entitas
	function index_get() {
        $id= $this->input->get("id");
        $gp= new gapi();
        $arrparam= ["ctrl"=>"skp22/id", "value"=>$id];
        $vreturn= $gp->getdataParam($arrparam);
        // print_r($vreturn);exit;
        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'result' => $vreturn));
    }
	
    // insert new data to entitas
    function index_post() {

        $hasilKinerjaNilai = (int)$this->input->post("hasilKinerjaNilai");
        $id = $this->input->post("id");
        $kuadranKinerjaNilai = (int)$this->input->post("kuadranKinerjaNilai");
        $penilaiGolongan = $this->input->post("penilaiGolongan");
        $penilaiJabatan = $this->input->post("penilaiJabatan");
        $penilaiNama = $this->input->post("penilaiNama");
        $penilaiNipNrp = $this->input->post("penilaiNipNrp");
        $penilaiUnorNama = $this->input->post("penilaiUnorNama");
        $perilakuKerjaNilai = (int)$this->input->post("perilakuKerjaNilai");
        $pnsDinilaiOrang = $this->input->post("pnsDinilaiOrang");
        $statusPenilai = $this->input->post("statusPenilai");
        $tahun = (int)$this->input->post("tahun");
        $id = $id?$id:null;
         // $id=null;
        $path[]= array("dok_id"=>$dok_id,"dok_nama"=>$dok_nama,"dok_uri"=>$dok_uri,"object"=>$object,"slug"=>$slug);
        $hasilKinerjaNilai =$hasilKinerjaNilai?$hasilKinerjaNilai:null;

           $arrData = array(
           "hasilKinerjaNilai"=>$hasilKinerjaNilai,
           "id"=>$id,
           "kuadranKinerjaNilai"=>$kuadranKinerjaNilai,
           "path"=>$path,
           "penilaiGolongan"=>$penilaiGolongan,
           "penilaiJabatan"=>$penilaiJabatan,
           "penilaiNama"=>$penilaiNama,
           "penilaiNipNrp"=>$penilaiNipNrp,
           "penilaiUnorNama"=>$penilaiUnorNama,
           "perilakuKerjaNilai"=>$perilakuKerjaNilai,
           "pnsDinilaiOrang"=>$pnsDinilaiOrang,
           "statusPenilai"=>$statusPenilai,
           "tahun"=>$tahun,


         );



        

        $jsonData  = json_encode($arrData);
       
     // print_r($jsonData);exit;

        $arrparam= ["ctrl"=>"skp22/save"];
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