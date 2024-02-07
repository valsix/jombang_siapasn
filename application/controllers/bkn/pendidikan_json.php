<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class pendidikan_json extends CI_Controller {

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
        $this->load->model('PendidikanRiwayat');
        $this->load->model('PendidikanJurusan');
        $this->load->model('Pendidikan');


        $this->load->model('base-api/InfoData');
        $this->load->model('base-api/DataCombo');

        $reqBknId= $this->input->get('reqBknId');
        $reqRiwayatId= $this->input->get('reqRiwayatId');
        $infonipbaru=$this->input->get('reqNip');


        $arrparam= ["nip"=>$infonipbaru, "vurl"=>"Data_rw_pendidikan_json"];
        $set= new DataCombo();
        $set->selectdata($arrparam, "");
        $arrDataResult = $set->rowResult;

        $vdatariwayat= in_array_column($reqBknId, "id", $arrDataResult);
       
        if(!empty($vdatariwayat))
        {
           
           $indexdata= $vdatariwayat[0];
           $arrResult = $arrDataResult[$indexdata];
        
           $id= $arrResult['id'];
           $idPns= $arrResult['idpns'];
           $nipBaru= $arrResult['nipbaru'];
           $nipLama= $arrResult['niplama'];
           $pendidikanId= $arrResult['pendidikanid'];
           $pendidikanNama= $arrResult['pendidikannama'];
           $tkPendidikanId= $arrResult['tkpendidikanid'];
           $tkPendidikanNama= $arrResult['tkpendidikannama'];
           $tahunLulus= $arrResult['tahunlulus'];
           $tglLulus= $arrResult['tgllulus'];
           $isPendidikanPertama= $arrResult['ispendidikanpertama'];
           $nomorIjasah= $arrResult['nomorijasah'];
           $namaSekolah= $arrResult['namasekolah'];
           $gelarDepan= $arrResult['gelardepan'];
           $gelarBelakang= $arrResult['gelarbelakang'];
           $path= $arrResult['path'];

           if($isPendidikanPertama=='0'){
                $isPendidikanPertama=2;
            }
        }


       
        

        $pegawai = new Pegawai();
        $pegawai->selectByParams(array("A.NIP_BARU"=>$nipBaru));
        $pegawai->firstRow();
        $idPegawai = $pegawai->getField('PEGAWAI_ID');

        $pendidikanjurusan = new PendidikanJurusan();
        $pendidikanjurusan->selectByParams(array("A.JURUSAN_ID_SAPK"=>$pendidikanId));
        $pendidikanjurusan->firstRow();
        $idPendidikanJurusan = $pendidikanjurusan->getField('PENDIDIKAN_JURUSAN_ID');


        $pendidikan = new Pendidikan();
        $pendidikan->selectByParams(array("A.KODE_TK_PENDIDIKAN"=>$tkPendidikanId));
        $pendidikan->firstRow();
        $idPendidikan = $pendidikan->getField('PENDIDIKAN_ID');

        

        $set = new PendidikanRiwayat();
        $set->setField('PENDIDIKAN_RIWAYAT_ID',$reqRiwayatId);
        $set->setField('PEGAWAI_ID',ValToNullDB($idPegawai));
        $set->setField('PENDIDIKAN_ID',ValToNullDB($idPendidikan));
        $set->setField('PENDIDIKAN_JURUSAN_ID',ValToNullDB($idPendidikanJurusan));
        $set->setField('NAMA',$namaSekolah);
        $set->setField('NO_STTB',$nomorIjasah);
        $set->setField('TANGGAL_STTB',dateToDBCheck($tglLulus));
        $set->setField('JURUSAN',$pendidikanNama);
        $set->setField('GELAR_DEPAN',$gelarDepan);
        $set->setField('GELAR_NAMA',$gelarBelakang);
        $set->setField('STATUS_PENDIDIKAN',$isPendidikanPertama);
     
        
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
        $this->load->model('PendidikanRiwayat');

        $reqRiwayatId= $arrparam["reqRiwayatId"];
        $id= $arrparam["id"];

        if(!empty($reqRiwayatId))
        {
            $set= new PendidikanRiwayat();
            $set->setField("PENDIDIKAN_RIWAYAT_ID", $reqRiwayatId);
            $set->setField("ID_SAPK", $id);
            $set->updateIdSapk();
        }

        return "1";
    }

}
?>