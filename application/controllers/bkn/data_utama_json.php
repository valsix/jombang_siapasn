<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class data_utama_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		//kauth
		if (!$this->kauth->getInstance()->hasIdentity())
		{
			// trow to unauthenticated page!
			//redirect('Login');
		}       
		
		/* GLOBAL VARIABLE */
		$this->LOGIN_USER= $this->kauth->getInstance()->getIdentity()->LOGIN_USER;
		$this->LOGIN_LEVEL= $this->kauth->getInstance()->getIdentity()->LOGIN_LEVEL;
		$this->LOGIN_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_ID;
		$this->LOGIN_PEGAWAI_ID= $this->kauth->getInstance()->getIdentity()->LOGIN_PEGAWAI_ID;
	}

	function siapasn_bkn(){
        $this->load->model('Pegawai');
       

        $reqRiwayatId= $this->input->get('reqRiwayatId');
        $reqBknId= $this->input->get('reqBknId');

        $set= new Pegawai();
        $set->selectByParams(array("A.PEGAWAI_ID"=>$reqRiwayatId));
        $set->firstRow();
        $reqNipBaru = $set->getField("NIP_BARU");

        $settingurlapi= $this->config->config["settingurlapi"];
        $url =  $settingurlapi.'Data_pegawai_json?nip='.$reqNipBaru;
        // echo $url;

        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );

        $html= file_get_contents($url, false, stream_context_create($arrContextOptions));
        
        $arrData = json_decode($html,true);
        // print_r( $arrData);exit;
      
        $arrResult = $arrData['result'];
       
        $kpknId = $arrResult['kpknId'];
        $agamaId = $arrResult['agamaId'];
        $lokasiKerjaId= $arrResult['lokasiKerjaId'];
        $reqBknId = $arrResult['id'];

        // echo 'TEST DATA EXIT';exit;
        
        $this->load->model('Pegawai');
       
        $set= new Pegawai();

        
        $this->load->model('CurlData');

		

        // Data SIAPANS 
         $set->selectByParams(array("A.PEGAWAI_ID"=>$reqRiwayatId));
         $set->firstRow();

         $reqBpjs= $set->getField("BPJS");
         $reqEmailKantor= $set->getField("EMAIL_KANTOR");
         $reqEmail= $set->getField("EMAIL");
         $reqAlamat = $set->getField("ALAMAT");
         $reqTelepon= $set->getField("TELEPON");
         $reqHp= $set->getField("HP");
         $reqTaspen= $set->getField("TASPEN");
         $reqNoTapera = $set->getField("NO_TAPERA");
       
        $path[]= array("dok_id"=>$dok_id,"dok_nama"=>$dok_nama,"dok_uri"=>$dok_uri,"object"=>$object,"slug"=>$slug);

        $sql = " SELECT * FROM INSTANSI WHERE instansi_id = '1' ";
        $row = $this->db->query($sql)->row();


        // $instansiId = 'A5EB03E23CD0F6A0E040640A040252AD';
        // $instansiIndukSapk='A5EB03E24217F6A0E040640A040252AD';

        $instansiId = $row->instansiid_bkn;
       
        $reqValnull =null;

        $arrData = array(
            "agama_id"=>$agamaId
            , "alamat"=>$reqAlamat
            , "email"=>$reqEmail
            , "email_gov"=>$reqEmailKantor
            , "kabupaten_id"=>$reqValnull
            , "karis_karsu"=>$reqValnull
            , "kelas_jabatan"=>$reqValnull
            , "kpkn_id"=>$kpknId
            , "lokasi_kerja_id"=>$lokasiKerjaId
            , "nomor_bpjs"=>$reqBpjs
            , "nomor_hp"=>$reqHp
            , "nomor_telpon"=>$reqTelepon
            , "npwp_nomor"=>$reqNpwp
            , "pns_orang_id"=>$reqBknId
            , "tanggal_taspen"=>$reqValnull
            , "tapera_nomor"=>$reqNoTapera
            , "taspen_nomor"=>$reqTaspen
        );
        
        $jsonData= json_encode($arrData);
        // print_r($jsonData);exit;

        $arrData['param']=$jsonData;
        $vurl ='Data_utama_json';

        $set= new CurlData();
        $response= $set->curlpost($vurl,$arrData);
        // print_r($response);exit();
		$returnStatus= $response->result->success;
		$returnId= $response->result->mapData->rwJabatanId;
        $pesan = $response->result->message;
        $simpan="";
        if($returnStatus == "1")
        {
            $reqId= $returnId;
            $simpan=1;

            $arrparam= ["reqRiwayatId"=>$reqRiwayatId, "id"=>$reqBknId];
             $this->setidsapk($arrparam);
        }

        if($simpan == "1")
        {
            $arrDataStatus =array("PESAN"=>'Data berhasil disimpan',"code"=>200);
        }
        else
        {
            $arrDataStatus =array("PESAN"=>$pesan,"code"=>400);
        }

        echo json_encode( $arrDataStatus,true);
	}

    

    function reset_siapasn()
    {
        $reqRiwayatId= $this->input->get('reqRiwayatId');

        $arrparam= ["reqRiwayatId"=>$reqRiwayatId, "id"=>""];
        $this->setidsapk($arrparam);

        $arrDataStatus= array("PESAN"=>'Data berhasil reset',"code"=>200);
        echo json_encode( $arrDataStatus,true);
    }

    function setidsapk($arrparam)
    {
        $this->load->model('Pegawai');

        $reqRiwayatId= $arrparam["reqRiwayatId"];
        $id= $arrparam["id"];

        if(!empty($reqRiwayatId))
        {
            $set= new JabatanRiwayat();
            $set->setField("PEGAWAI_ID", $reqRiwayatId);
            $set->setField("PEGAWAI_ID_SAPK", $id);
            $set->updateIdSapk();
        }

        return "1";
    }

}
?>