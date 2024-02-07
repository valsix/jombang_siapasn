<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class hukuman_json extends CI_Controller {

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

         $this->load->model('Hukuman');
        $this->load->model('CurlData');

        $reqRiwayatId= $this->input->get('reqRiwayatId');
        $reqBknId= $this->input->get('reqBknId');

        $hukuman = new Hukuman();
        $hukuman->selectByParams(array("A.HUKUMAN_ID"=>$reqRiwayatId));
        ECHO  $hukuman->query;exit;
        $hukuman->firstRow();
        $pegawai_id_sapk =$hukuman->getField('PEGAWAI_ID_SAPK');
        $nosk= $hukuman->getField("NO_SK");
        $tanggalsk= dateToPageCheck($hukuman->getField("TANGGAL_SK"));
        $tmtsk=dateToPageCheck($hukuman->getField("TMT_SK"));
        $tingkathukumannama= $hukuman->getField("TINGKAT_HUKUMAN_NAMA");
        $jenishukumannama= $hukuman->getField("JENIS_HUKUMAN_NAMA");
        $keterangan= $hukuman->getField("KETERANGAN");
        $pejabatpenetapnama= $hukuman->getField("PEJABAT_PENETAP_NAMA");
        $berlakunama= $hukuman->getField("BERLAKU_NAMA");
         
         $tingkathukumanid= $hukuman->getField("tingkat_hukuman_id");   
          $jenishukumanid= $hukuman->getField("jenis_hukuman_id");     
            
        $path[]= array("dok_id"=>$dok_id,"dok_nama"=>$dok_nama,"dok_uri"=>$dok_uri,"object"=>$object,"slug"=>$slug);

        
        $arrData = array(
            "akhirHukumanTanggal"=>$akhirHukumanTanggal,
            "alasanHukumanDisiplinId"=>$alasanHukumanDisiplinId,
            "golonganId"=>$golonganId,
            "golonganLama"=>$golonganLama,
            "hukdisYangDiberhentikanId"=>$hukdisYangDiberhentikanId,
            "hukumanTanggal"=>$tmtsk,
             "id"=>$reqBknId,
            "jenisHukumanId"=>$jenishukumanid,
            "jenisTingkatHukumanId"=>$tingkathukumanid,
            "kedudukanHukumId"=>$kedudukanHukumId,
            "keterangan"=>$keterangan,
            "masaBulan"=>$masaBulan,
            "masaTahun"=>$masaTahun,
            "nomorPp"=>$nomorPp,
            "path"=>$path,
            "pnsOrangId"=>$pegawai_id_sapk,
            "skNomor"=>$nosk,
            "skPembatalanNomor"=>$skPembatalanNomor,
            "skPembatalanTanggal"=>$skPembatalanTanggal,
            "skTanggal"=>$tanggalsk

        );

        $jsonData= json_encode($arrData);
        $arrData['param']=$jsonData;
        $vurl ='Data_rw_diklat_json';
   
        $set= new CurlData();

        $response= $set->curlpost($vurl,$arrData);
           // print_r($response);exit();
        $returnStatus= $response->result->success;
        $returnId= $response->result->mapData->rwDiklatId;
        $pesan = $response->result->message;
        $simpan="";
        if($returnStatus == "1")
        {
            $reqId= $returnId;
            $simpan=1;

            $arrparam= ["reqRiwayatId"=>$reqRiwayatId, "id"=>$reqId];
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

    function bkn_siapasn(){

        $this->load->model('Pegawai');
        $this->load->model('Hukuman');
       


        $this->load->model('base-api/InfoData');
        $this->load->model('base-api/DataCombo');

        $reqBknId= $this->input->get('reqBknId');
        $reqRiwayatId= $this->input->get('reqRiwayatId');
        $infonipbaru=$this->input->get('reqNip');

         $settingurlapi= $this->config->config["settingurlapi"];
         $url =  $settingurlapi.'Data_rw_hukdis_json?id='.$reqBknId;
        echo $url;exit;

        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );

        $html= file_get_contents($url, false, stream_context_create($arrContextOptions));
        // print_r($html);exit;
      
        $arrData = json_decode($html,true);
        print_r( $arrData);exit;
      
        $arrResult = $arrData['result'];
        
        $id= $arrResult['id'];
        $rwHukumanDisiplin= $arrResult["rwHukumanDisiplin"];
        $golongan= $arrResult["golongan"];
        $kedudukanHukum= $arrResult["kedudukanHukum"];
        $jenisHukuman= $arrResult["jenisHukuman"];
        $pnsOrang= $arrResult["pnsOrang"];
        $skNomor= $arrResult["skNomor"];
        $skTanggal= $arrResult["skTanggal"];
        $hukumanTanggal= $arrResult["hukumanTanggal"];
        $masaTahun= $arrResult["masaTahun"];
        $masaBulan= $arrResult["masaBulan"];
        $akhirHukumTanggal= $arrResult["akhirHukumTanggal"];
        $nomorPp= $arrResult["nomorPp"];
        $golonganLama= $arrResult["golonganLama"];
        $skPembatalanNomor= $arrResult["skPembatalanNomor"];
        $skPembatalanTanggal= $arrResult["skPembatalanTanggal"];
        $alasanHukumanDisiplin= $arrResult["alasanHukumanDisiplin"];
        $alasanHukumanDisiplinNama= $arrResult["alasanHukumanDisiplinNama"];
        $jenisHukumanNama= $arrResult["jenisHukumanNama"];
        $path= $arrResult["path"];
        $keterangan=$arrResult["keterangan"];
        $jenisTingkatHukumanId= $arrResult["jenisTingkatHukumanId"];
      
        


       
        

        $pegawai = new Pegawai();
        $pegawai->selectByParams(array("A.PEGAWAI_ID_SAPK"=>$pnsOrang));
        $pegawai->firstRow();
        $idPegawai = $pegawai->getField('PEGAWAI_ID');

        

        

        $set = new Hukuman();
        $set->setField('HUKUMAN_ID',$reqRiwayatId);
        $set->setField('PEGAWAI_ID',ValToNullDB($idPegawai));
        $set->setField('no_sk',setQuote($nama));
        $set->setField('tanggal_sk',$tempatLahir);
        $set->setField('tmt_sk',dateToDBCheck($tglLahir));
        $set->setField('keterangan',$jenisKelamin);
        $set->setField('STATUS_KELUARGA',ValToNullDB($jenisAnak));
       
        
        
        if(empty($reqRiwayatId))
        {
            $set->insertDataBkn();
            $reqRiwayatId= $set->id;
            
        }
        else
        {
            
            $set->updateBknData();
        }
        // echo $set->query;exit;
        // exit;
        $arrparam= ["reqRiwayatId"=>$reqRiwayatId, "id"=>$id];
        $this->setidsapk($arrparam);

        $arrDataStatus =array("PESAN"=>'Data berhasil disimpan',"code"=>200);
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
        $this->load->model('Hukuman');

        $reqRiwayatId= $arrparam["reqRiwayatId"];
        $id= $arrparam["id"];

        if(!empty($reqRiwayatId))
        {
            $set= new Anak();
            $set->setField("HUKUMAN_ID", $reqRiwayatId);
            $set->setField("ID_SAPK", $id);
            $set->updateIdSapk();
        }

        return "1";
    }

}
?>