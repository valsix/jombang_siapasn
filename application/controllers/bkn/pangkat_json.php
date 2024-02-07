<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");

class pangkat_json extends CI_Controller {

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
        $this->load->model('PangkatRiwayat');
       
       

        $this->load->model('base-api/InfoData');
        $this->load->model('base-api/DataCombo');

        $reqBknId= $this->input->get('reqBknId');
        $reqRiwayatId= $this->input->get('reqRiwayatId');
        $infonipbaru=$this->input->get('reqNip');

        $arrdatabkn= [];

        $arrparam= ["nip"=>$infonipbaru, "vurl"=>"Data_rw_golongan_json"];
        $set= new DataCombo();
        $set->selectdata($arrparam, "");
        
        while($set->nextRow())
        {
               $arrdata= [];
 
              // data sesuai api bkn
               $date      = new DateTime($set->getField("tmtGolongan"));
               $tmtFormat = $date->format('Y-m-d');

               $infokunci= dateToPageCheck($tmtFormat);
               $arrdata= [];
                // kunci untuk kondisi
               $arrdata["key"]= $infokunci;

                // data sesuai api bkn
               $arrdata["id"]= $set->getField("id");
               $arrdata["idPns"]= $set->getField("idPns");
               $arrdata["nipBaru"]= $set->getField("nipBaru");
               $arrdata["nipLama"]= $set->getField("nipLama");
               $arrdata["golonganId"]= $set->getField("golonganId");
               $arrdata["golongan"]= $set->getField("golongan");
               $arrdata["skNomor"]= $set->getField("skNomor");
               $arrdata["skTanggal"]= $set->getField("skTanggal");
               $arrdata["tmtGolongan"]= $infokunci;
               $arrdata["noPertekBkn"]= $set->getField("noPertekBkn");
               $arrdata["tglPertekBkn"]= $set->getField("tglPertekBkn");
               $arrdata["jumlahKreditUtama"]= $set->getField("jumlahKreditUtama");
               $arrdata["jumlahKreditTambahan"]= $set->getField("jumlahKreditTambahan");
               $arrdata["jenisKPId"]= $set->getField("jenisKPId");
               $arrdata["jenisKPNama"]= $set->getField("jenisKPNama");
               $arrdata["masaKerjaGolonganTahun"]= $set->getField("masaKerjaGolonganTahun");
               $arrdata["masaKerjaGolonganBulan"]= $set->getField("masaKerjaGolonganBulan");
              


              array_push($arrdatabkn, $arrdata);
        }    


        $arrDataResult = $arrdatabkn;
        // print_r( $arrDataResult );exit;
        $vdatariwayat= in_array_column($reqBknId, "id", $arrDataResult);
        // print_r( $vdatariwayat );exit;
        if(!empty($vdatariwayat))
        {
           
           $indexdata= $vdatariwayat[0];
           $arrResult = $arrDataResult[$indexdata];
        
           $id= $arrResult["id"];
           $idPns= $arrResult["idPns"];
           $nipBaru= $arrResult["nipBaru"];
           $nipLama= $arrResult["nipLama"];
           $golonganId= $arrResult["golonganId"];
           $golongan= $arrResult["golongan"];
           $skNomor= $arrResult["skNomor"];
           $skTanggal= $arrResult["skTanggal"];
           $tmtGolongan=$arrResult["tmtGolongan"];
           $noPertekBkn= $arrResult["noPertekBkn"];
           $tglPertekBkn= $arrResult["tglPertekBkn"];
           $jumlahKreditUtama= $arrResult["jumlahKreditUtama"];
           $jumlahKreditTambahan= $arrResult["jumlahKreditTambahan"];
           $jenisKPId= $arrResult["jenisKPId"];
           $jenisKPNama= $arrResult["jenisKPNama"];
           $masaKerjaGolonganTahun= $arrResult["masaKerjaGolonganTahun"];
           $masaKerjaGolonganBulan= $arrResult["masaKerjaGolonganBulan"];
      
        }


       
        

        $pegawai = new Pegawai();
        $pegawai->selectByParams(array("A.NIP_BARU"=>$nipBaru));
        $pegawai->firstRow();
        $idPegawai = $pegawai->getField('PEGAWAI_ID');

        
        $arrDataJenisRiwayat[211]=1;       
        $arrDataJenisRiwayat[101]=4;       
        $arrDataJenisRiwayat[201]=5;
        $arrDataJenisRiwayat[202]=6;
        $arrDataJenisRiwayat[203]=7;

         // $arrDataJenisRiwayat[]='11';
         // $arrDataJenisRiwayat[]='2';
        // $arrDataJenisRiwayat[]='10';
        // $arrDataJenisRiwayat[]='8';
        // $arrDataJenisRiwayat[]='9';
        $reqJenisRiwayat = $arrDataJenisRiwayat[$jenisKPId];

        $set = new PangkatRiwayat();
        $set->setField('PANGKAT_RIWAYAT_ID',$reqRiwayatId);
        $set->setField('PEGAWAI_ID',ValToNullDB($idPegawai));
        $set->setField('NO_SK',setQuote($skNomor));        
        $set->setField('TMT_PANGKAT',dateToDBCheck($tmtGolongan));
        $set->setField('PANGKAT_ID',ValToNullDB($golonganId));
        $set->setField('TANGGAL_SK',dateToDBCheck($skTanggal));
        $set->setField('MASA_KERJA_TAHUN',ValToNullDB($masaKerjaGolonganTahun));
        $set->setField('MASA_KERJA_BULAN',ValToNullDB($masaKerjaGolonganBulan));
        $set->setField('NO_NOTA',$noPertekBkn);
        $set->setField('TANGGAL_NOTA',dateToDBCheck($tglPertekBkn));
        $set->setField('KETERANGAN',$jenisKPNama);
        $set->setField('JENIS_RIWAYAT',ValToNullDB($reqJenisRiwayat));
        
        
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
        $this->load->model('PangkatRiwayat');

        $reqRiwayatId= $arrparam["reqRiwayatId"];
        $id= $arrparam["id"];

        if(!empty($reqRiwayatId))
        {
            $set= new PangkatRiwayat();
            $set->setField("PANGKAT_RIWAYAT_ID", $reqRiwayatId);
            $set->setField("ID_SAPK", $id);
            $set->updateIdSapk();
        }

        return "1";
    }

}
?>