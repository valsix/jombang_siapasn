<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class pasangan_json extends CI_Controller {

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

       
    }

    function bkn_siapasn(){

        $this->load->model('Pegawai');
        $this->load->model('SuamiIstri');
       


        $this->load->model('base-api/InfoData');
        $this->load->model('base-api/DataCombo');

        $reqBknId= $this->input->get('reqBknId');
        $reqRiwayatId= $this->input->get('reqRiwayatId');
        $infonipbaru=$this->input->get('reqNip');

        $arrdatabkn= [];

        $arrparam= ["nip"=>$infonipbaru, "vurl"=>"Data_pasangan_json"];
        $set= new DataCombo();
        $set->selectdata($arrparam, "","allrow");
        $arrDataResults = $set->rowResult;
        $arrDataDataPasangan = $arrDataResults['listPasangan'];
        $nipBaru  = $arrDataResults['nipBaru'];
        foreach ($arrDataDataPasangan as $row) 
        {
               $arrdata= [];
 
              // data sesuai api bkn
              $arrdata["id"]= $row['dataPernikahan']['id'];
              $arrdata["nama"]= $row['orang']['nama'];
              $arrdata["statusNikah"]= $row['statusNikah'];
              $arrdata["tglLahir"]=  $row['orang']['tglLahir'];
              $arrdata["tempatLahir"]=  $row['orang']['tempatLahir'];
              $arrdata["jenisKelamin"]=  $row['orang']['jenisKelamin'];
              $arrdata["aktaMenikah"]=  $row['dataPernikahan']['aktaMenikah'];
              $arrdata["tgglMenikah"]=  $row['dataPernikahan']['tgglMenikah'];

              $arrdata["tgglCerai"]=  $row['dataPernikahan']['tgglCerai'];
              $arrdata["aktaCerai"]=  $row['dataPernikahan']['aktaCerai'];
              $arrdata["gelarDepan"]=  $row['orang']['gelarDepan'];
              $arrdata["gelarBlk"]=  $row['orang']['gelarBlk'];
              $arrdata["aktaMeninggal"]=  $row['orang']['aktaMeninggal'];
              $arrdata["tglMeninggal"]=  $row['orang']['tglMeninggal'];
              $arrdata["status"]=  $row['dataPernikahan']['status'];
              $arrdata["isPns"]=  $row['dataPernikahan']['isPns'];
            

              array_push($arrdatabkn, $arrdata);
        }    


        $arrDataResult = $arrdatabkn;
   
        $vdatariwayat= in_array_column($reqBknId, "id", $arrDataResult);
       
        if(!empty($vdatariwayat))
        {
           
           $indexdata= $vdatariwayat[0];
           $arrResult = $arrDataResult[$indexdata];
        
           $id= $arrResult['id'];
           $nama= $arrResult['nama'];
           $statusNikah= $arrResult['statusNikah'];
           $tglLahir= $arrResult['tglLahir'];
           $tempatLahir= $arrResult['tempatLahir'];
           $jenisKelamin= $arrResult['jenisKelamin'];
           $aktaMenikah= $arrResult['aktaMenikah'];
           $tgglMenikah= $arrResult['tgglMenikah'];

           $aktaCerai = $arrResult['aktaCerai'];
           $tgglCerai = $arrResult['tgglCerai'];
           $gelarDepan = $arrResult['gelarDepan'];
           $gelarBlk = $arrResult['gelarBlk'];
           $aktaMeninggal = $arrResult['aktaMeninggal'];
           $tglMeninggal = $arrResult['tglMeninggal'];
           $status = $arrResult['status'];
           $isPns = $arrResult['isPns'];

           if($isPns){
            $statusPns=1;
           }
           
        }


       
        

        $pegawai = new Pegawai();
        $pegawai->selectByParams(array("A.NIP_BARU"=>$nipBaru));
        $pegawai->firstRow();
        $idPegawai = $pegawai->getField('PEGAWAI_ID');

        

        

        $set = new SuamiIstri();
        $set->setField('SUAMI_ISTRI_ID',$reqRiwayatId);
        $set->setField('PEGAWAI_ID',ValToNullDB($idPegawai));
        $set->setField('NAMA',$nama);
        $set->setField('TEMPAT_LAHIR',$tempatLahir);
        $set->setField('TANGGAL_LAHIR',dateToDBCheck($tglLahir));
        $set->setField('SURAT_NIKAH',$aktaMenikah);
        $set->setField('TANGGAL_KAWIN',dateToDBCheck($tgglMenikah));
        $set->setField('AKTA_NIKAH_NO',$aktaMenikah);

        $set->setField('GELAR_DEPAN',$gelarDepan);
        $set->setField('GELAR_BELAKANG',$gelarBlk);
        $set->setField('JENIS_KELAMIN',$jenisKelamin);
        $set->setField('CERAI_SURAT',$aktaCerai);
        $set->setField('CERAI_TANGGAL',dateToDBCheck($tgglCerai));
        $set->setField('CERAI_TMT',dateToDBCheck($tgglCerai));
        $set->setField('TANGGAL_MENINGGAL',dateToDBCheck($tglMeninggal));
        $set->setField('KEMATIAN_SURAT',$aktaMeninggal);
        $set->setField('KEMATIAN_TMT',dateToDBCheck($tglMeninggal));
        $set->setField('AKTA_CERAI_NO',$aktaCerai);
        $set->setField('AKTA_CERAI_TANGGAL',dateToDBCheck($tgglCerai));
        $set->setField('STATUS_PNS',ValToNullDB($statusPns));
        
        
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
        $this->load->model('SuamiIstri');

        $reqRiwayatId= $arrparam["reqRiwayatId"];
        $id= $arrparam["id"];

        if(!empty($reqRiwayatId))
        {
            $set= new SuamiIstri();
            $set->setField("SUAMI_ISTRI_ID", $reqRiwayatId);
            $set->setField("ID_SAPK", $id);
            $set->updateIdSapk();
        }

        return "1";
    }

}
?>