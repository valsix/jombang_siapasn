<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class anak_json extends CI_Controller {

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
        $this->load->model('Anak');
       


        $this->load->model('base-api/InfoData');
        $this->load->model('base-api/DataCombo');

        $reqBknId= $this->input->get('reqBknId');
        $reqRiwayatId= $this->input->get('reqRiwayatId');
        $infonipbaru=$this->input->get('reqNip');

        $arrdatabkn= [];

        $arrparam= ["nip"=>$infonipbaru, "vurl"=>"Data_anak_json"];
        $set= new DataCombo();
        $set->selectdata($arrparam, "","allrow");
        $arrDataResults = $set->rowResult;
        $arrDataDataPasangan = $arrDataResults['listAnak'];
        $nipBaru  = $arrDataResults['nipBaru'];
        foreach ($arrDataDataPasangan as $row) 
        {
               $arrdata= [];
 
              // data sesuai api bkn
               $date      = new DateTime($row['tglLahir']);
               $tmtFormat = $date->format('d-m-Y');


               $infokunci= $tmtFormat ;
               $arrdata["id"]= $row['id'];          
               $arrdata["nama"]= $row['nama'];
               $arrdata["jenisKelamin"]= $row['jenisKelamin'];
               $arrdata["jenisAnak"]=  $row['jenisAnak'] ;
               $arrdata["tempatLahir"]=  $row['tempatLahir'];
               $arrdata["kabupatenId"]=  $row['kabupatenId'];
               $arrdata["tglLahir"]=   $infokunci;

            

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
           $jenisKelamin= $arrResult['jenisKelamin'];
           $jenisAnak= $arrResult['jenisAnak'];
           $tempatLahir= $arrResult['tempatLahir'];
           $kabupatenId= $arrResult['kabupatenId'];
           $tglLahir= $arrResult['tglLahir'];
      
        }


       
        

        $pegawai = new Pegawai();
        $pegawai->selectByParams(array("A.NIP_BARU"=>$nipBaru));
        $pegawai->firstRow();
        $idPegawai = $pegawai->getField('PEGAWAI_ID');

        

        

        $set = new Anak();
        $set->setField('ANAK_ID',$reqRiwayatId);
        $set->setField('PEGAWAI_ID',ValToNullDB($idPegawai));
        $set->setField('NAMA',setQuote($nama));
        $set->setField('TEMPAT_LAHIR',$tempatLahir);
        $set->setField('TANGGAL_LAHIR',dateToDBCheck($tglLahir));
        $set->setField('JENIS_KELAMIN',$jenisKelamin);
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
        $this->load->model('Anak');

        $reqRiwayatId= $arrparam["reqRiwayatId"];
        $id= $arrparam["id"];

        if(!empty($reqRiwayatId))
        {
            $set= new Anak();
            $set->setField("ANAK_ID", $reqRiwayatId);
            $set->setField("ID_SAPK", $id);
            $set->updateIdSapk();
        }

        return "1";
    }

}
?>