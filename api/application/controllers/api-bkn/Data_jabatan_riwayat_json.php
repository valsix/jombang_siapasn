<?php
require APPPATH . '/libraries/REST_Controller.php';
include_once("functions/string.func.php");
include_once("functions/date.func.php");
 
class Data_jabatan_riwayat_json extends REST_Controller {
 
    function __construct() {
        parent::__construct();

        $this->load->library('gapi');
    }
 
    // show data entitas
	function index_get() {
        $nip= $this->input->get("id");
        $gp= new gapi();
        $arrparam= ["ctrl"=>"jabatan/id", "value"=>$nip];
        $vreturn= $gp->getdataParam($arrparam);
        // print_r($vreturn);exit;
        $this->response(array('status' => 'success', 'message' => 'success', 'code' => 200, 'result' => $vreturn));
    }
	
    // insert new data to entitas
    function index_post() {

        $eselonId = $this->input->post("eselonId");
        $id       = $this->input->post("id");  
        $instansiId = $this->input->post("instansiId");
        $jabatanFungsionalId= $this->input->post("jabatanFungsionalId");
        $jabatanFungsionalUmumId= $this->input->post("jabatanFungsionalUmumId");
        $jenisJabatan= $this->input->post("jenisJabatan");
        $nomorSk= $this->input->post("nomorSk");
        $pnsId= $this->input->post("pnsId");
        $satuanKerjaId= $this->input->post("satuanKerjaId");
        $tanggalSk= $this->input->post("tanggalSk");
        $tmtJabatan= $this->input->post("tmtJabatan");
        $tmtPelantikan= $this->input->post("tmtPelantikan");
        $unorId= $this->input->post("unorId");
        $path[]= array("dok_id"=>$dok_id,"dok_nama"=>$dok_nama,"dok_uri"=>$dok_uri,"object"=>$object,"slug"=>$slug);

        $eselonId=null;
        $id = $id?$id:null;
         $arrData = array(
                 "eselonId"=>$eselonId,
                 "id"=>$id,
                 "instansiId"=>$instansiId,
                 "jabatanFungsionalId"=>$jabatanFungsionalId?$jabatanFungsionalId:null,
                 "jabatanFungsionalUmumId"=>$jabatanFungsionalUmumId?$jabatanFungsionalUmumId:null,
                 "jenisJabatan"=>$jenisJabatan,
                 "nomorSk"=>$nomorSk,
                 "path"=>$path,
                 "pnsId"=>$pnsId,
                 "satuanKerjaId"=>$satuanKerjaId,
                 "tanggalSk"=>$tanggalSk,
                 "tmtJabatan"=>$tmtJabatan,
                 "tmtPelantikan"=>$tmtPelantikan,
                 "unorId"=>$unorId,

         );
        

        $jsonData  = json_encode($arrData);
       
          // print_r($jsonData);exit;

        $arrparam= ["ctrl"=>"jabatan/save"];
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